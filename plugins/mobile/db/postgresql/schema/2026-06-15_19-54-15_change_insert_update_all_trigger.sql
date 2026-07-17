BEGIN;
  CREATE OR REPLACE FUNCTION gdi_split_sql(sql_text text)
  RETURNS text[]
  LANGUAGE plpgsql
  AS $$
  DECLARE
      i int;
      c text;
      in_string boolean := false;
      stmt text := '';
      result text[] := '{}';
  BEGIN
      FOR i IN 1..length(sql_text) LOOP
          c := substr(sql_text, i, 1);

          -- toggle string state
          IF c = '''' THEN
              in_string := NOT in_string;
          END IF;

          -- split only on semicolon outside string
          IF c = ';' AND NOT in_string THEN

              -- ONLY append if non-empty after trim
              IF btrim(stmt) <> '' THEN
                  result := array_append(result, btrim(stmt));
              END IF;

              stmt := '';
          ELSE
              stmt := stmt || c;
          END IF;
      END LOOP;

      -- last statement (also non-empty only)
      IF lower(stmt) LIKE '%insert%'
        OR lower(stmt) LIKE '%update%'
        OR lower(stmt) LIKE '%delete%'
      THEN
        result := array_append(result, btrim(stmt));
      END IF;

      RETURN result;
  END;
  $$;

  CREATE OR REPLACE FUNCTION public.create_insert_delta()
  RETURNS trigger
  LANGUAGE plpgsql
  AS $function$
    DECLARE
      new_version integer := (SELECT (coalesce(max(version), 1) + 1)::integer FROM deltas_all);
      _query TEXT;
      _sql TEXT;
      part TEXT;
      search_path_schema TEXT;
      version_column TEXT;
    uuid UUID;
    action_time timestamp without time zone;
    BEGIN
      _query := current_query();

      --raise notice '_query: %', _query;
      foreach part in array gdi_split_sql(_query)
      loop
        -- replace horizontal tabs, new lines and carriage returns
        part = trim(regexp_replace(part, E'[\\t\\n\\r]+', ' ', 'g'));

        IF strpos(lower(part), 'set search_path') = 1 THEN
        search_path_schema = trim(lower(split_part(split_part(part, '=', 2), ',', 1)));
        --RAISE notice 'schema in search_path %', search_path_schema;
        END IF;

        IF
          strpos(lower(part), 'insert into ' || TG_TABLE_SCHEMA || '.' || TG_TABLE_NAME) = 1 OR
          strpos(lower(part), 'insert into ' || TG_TABLE_SCHEMA || '.\"' || TG_TABLE_NAME || '\"') = 1 OR
          (
            (
              strpos(lower(part), 'insert into ' || TG_TABLE_NAME) = 1 OR
              strpos(lower(part), 'insert into \"' || TG_TABLE_NAME || '\"') = 1
            ) AND
            TG_TABLE_SCHEMA = search_path_schema
          )
        THEN
          part := replace(part, '\"' || TG_TABLE_NAME || '\"', TG_TABLE_NAME);
          --RAISE notice 'Anfuehrungsstriche von Tabellennamen entfernt: %', part;

          _sql := part;
        END IF;
      END LOOP;
      --raise notice 'sql nach split by ; und select by update: %', _sql;

      _sql := kvw_replace_line_feeds(_sql);
      --RAISE notice 'sql nach remove line feeds %', _sql;

      _sql := replace(_sql, ' ' || TG_TABLE_NAME || ' ', ' ' || TG_TABLE_SCHEMA || '.' || TG_TABLE_NAME || ' ');
      --RAISE notice 'sql nach add schema %', TG_TABLE_SCHEMA || '.';

      -- Frage ab ob es eine Spalte version gibt
      EXECUTE FORMAT('
        SElECT *
        FROM information_schema.columns
        WHERE
          table_schema = %1$L AND
          table_name = %2$L AND
          column_name = %3$L
        ', TG_TABLE_SCHEMA, TG_TABLE_NAME, 'version'
      )
      INTO version_column;

      -- Version wird nur angehaengt wenn es die Spalte version gibt
      IF version_column IS NOT NULL THEN
        _sql := kvw_insert_str_before(_sql, ', version', ')');
        --RAISE notice 'sql nach add column version %', _sql;
      END IF;

      _sql := substr(_sql, 1 , strpos(lower(_sql), 'values') - 1) || 'VALUES' || substr(_sql, strpos(lower(_sql), 'values') + 6, length(_sql) - strpos(lower(_sql), 'values') - 5);
      --RAISE notice 'sql nach upper VALUES %', _sql;

      -- Version wird nur angehaengt wenn es die Spalte version gibt
      IF version_column IS NOT NULL THEN
        _sql := substr(_sql, 1, strpos(_sql, 'VALUES') - 1) || regexp_replace(substr(_sql, strpos(_sql, 'VALUES')), '\)+', ', ' || new_version || ')', 'g');
        --RAISE notice 'sql nach add values for version %', _sql;
      END IF;

      IF strpos(lower(_sql), ' returning ') > 0 THEN
        _sql := substr(_sql, 1, strpos(lower(_sql), ' returning ') -1);
        --RAISE notice 'sql nach entfernen von RETURNING uuid %', _sql;
      END IF;

    -- uuid aus current_setting('public.uuid')
    uuid = (SELECT current_setting('public.uuid', true));

    -- action_time aus current_setting('public.action_time') oder now()
    action_time = (SELECT COALESCE(
    current_setting('public.action_time', true)::timestamp without time zone,
    now()
    ));

      INSERT INTO deltas_all (client_id, sql, schema_name, table_name, action, action_time, uuid) VALUES (current_setting('public.client_id', true), _sql, TG_TABLE_SCHEMA, TG_TABLE_NAME, 'insert', action_time, uuid);

      RAISE NOTICE 'Neuen Datensatz mit Version % in Tabelle deltas_all eingetragen.', new_version;
    --RAISE NOTICE 'Version: %, action: insert, action_time: %, client_id: %, uuid: %, sql: %', new_version, action_time, current_setting('public.client_id', true), uuid, _sql;

      RETURN NEW;
    END;
  $function$;

  -- DROP FUNCTION public.create_update_delta();
  CREATE OR REPLACE FUNCTION public.create_update_delta()
  RETURNS trigger
  LANGUAGE plpgsql
  AS $function$
    DECLARE
      new_version integer := (SELECT (coalesce(max(version), 1) + 1)::integer FROM deltas_all);
      _query TEXT;
      _sql TEXT;
      part TEXT;
      search_path_schema TEXT;
      version_column TEXT;
    uuid UUID;
    updated_at_server timestamp without time zone;
    updated_at_client timestamp without time zone;
    action_time timestamp without time zone;
  BEGIN
      _query := current_query();

      --raise notice '_query: %', _query;
      foreach part in array gdi_split_sql(_query)
      loop
        --raise notice 'part in loop vor trim und replace: %', part;
        -- replace horizontal tabs, new lines and carriage returns
        part = trim(regexp_replace(part, E'[\\t\\n\\r]+', ' ', 'g'));
        --raise notice 'part in loop nach trim und replace: %', part;
        --raise notice 'suche nach %', 'update ' || TG_TABLE_SCHEMA || '.' || TG_TABLE_NAME;

        IF strpos(lower(part), 'set search_path') = 1 THEN
          search_path_schema = trim(lower(split_part(split_part(part, '=', 2), ',', 1)));
          --RAISE notice 'schema in search_path %', search_path_schema;
        END IF;

        part := replace(part, '\"' || TG_TABLE_NAME || '\"', TG_TABLE_NAME);
        --RAISE notice 'Anfuehrungsstriche von Tabellennamen entfernt: %', part;

        IF strpos(lower(part), 'update ' || TG_TABLE_SCHEMA || '.' || TG_TABLE_NAME) = 1 OR (strpos(lower(part), 'update ' || TG_TABLE_NAME) = 1 AND TG_TABLE_SCHEMA = search_path_schema) THEN
        _sql := part;
        END IF;
      end loop;
      --raise notice 'sql nach split by ; und select by update: %', _sql;

      IF _sql IS NOT NULL THEN
        _sql := kvw_replace_line_feeds(_sql);
        --RAISE notice 'sql nach remove line feeds %', _sql;

        _sql := replace(_sql, ' ' || TG_TABLE_NAME || ' ', ' ' || TG_TABLE_SCHEMA || '.' || TG_TABLE_NAME || ' ');
        --RAISE notice 'sql nach remove %', TG_TABLE_SCHEMA || '.';

        -- Frage ab ob es eine Spalte version gibt
        EXECUTE FORMAT('
          SElECT *
          FROM information_schema.columns
          WHERE
            table_schema = %1$L AND
            table_name = %2$L AND
            column_name = %3$L
          ', TG_TABLE_SCHEMA, TG_TABLE_NAME, 'version'
        )
        INTO version_column;

        -- Version wird nur gesetzt wenn es die Spalte version in der Tabelle gibt
        IF version_column IS NOT NULL THEN
          IF (_sql ILIKE '%version%') THEN
            -- Version wird ersetzt
            _sql := regexp_replace(_sql, 'version\s*=\s*''*\d+''*', 'version = ' || new_version);
            --RAISE NOTICE 'sql nach update version value %', _sql;
          ELSE
            -- Version wird nach SET angehängt
            _sql := kvw_insert_str_after(_sql, 'version = ' || new_version || ', ', ' ' || TG_TABLE_SCHEMA || '.' || TG_TABLE_NAME || ' set ');
            --RAISE NOTICE 'sql nach insert version value %', _sql;
          END IF;
        END IF;   

    RAISE NOTICE 'sql: %', _sql;
    -- uuid aus current_setting oder sql ziehen, nach where Part nach uuid, davon Part nach =, davon Part nach '
    uuid = (SELECT COALESCE(
      current_setting('public.uuid', true),
      SPLIT_PART(SPLIT_PART(SPLIT_PART(SUBSTRING(_sql, POSITION('where' IN LOWER(_sql)) + 6), 'uuid', '2'), '=', 2), '''', 2)
    ));

    -- action_time aus current_setting('public.action_time'), updated_at_client, updated_at_server or now()
    updated_at_client = (SELECT (regexp_matches(_sql, 'updated_at_client\s*=\s*''([^'']*)'''))[1])::timestamp without time zone;
    updated_at_server = (SELECT (regexp_matches(_sql, 'updated_at_server\s*=\s*''([^'']*)'''))[1])::timestamp without time zone;
    action_time = (SELECT COALESCE(
      current_setting('public.action_time', true)::timestamp without time zone,
      updated_at_client,
      updated_at_server,
      now()
    )::timestamp without time zone);

    INSERT INTO deltas_all (client_id, sql, schema_name, table_name, action, uuid, action_time) VALUES
    (current_setting('public.client_id', true), _sql, TG_TABLE_SCHEMA, TG_TABLE_NAME, 'update', uuid, action_time);

      RAISE NOTICE 'Änderung mit Version % in Tabelle deltas_all eingetragen.', new_version;
    --RAISE NOTICE 'Version: %, action: update, action_time: %, uuid: %, client_id: %, sql: %', new_version, action_time, uuid, current_setting('public.client_id', true), _sql;
      END IF;

      RETURN NEW;
    END;
  $function$;
COMMIT;