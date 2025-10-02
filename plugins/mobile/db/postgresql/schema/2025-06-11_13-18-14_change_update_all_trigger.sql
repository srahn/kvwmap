BEGIN;

  --
  -- UPDATE Trigger
  --
  CREATE OR REPLACE FUNCTION create_update_delta()
  RETURNS trigger AS
  $$
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
      foreach part in array string_to_array(_query, ';')
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

        -- Version wird nur angehaengt wenn es die Spalte version gibt
        IF version_column IS NOT NULL THEN
          _sql := kvw_insert_str_after(_sql, 'version = ' || new_version || ', ', ' ' || TG_TABLE_SCHEMA || '.' || TG_TABLE_NAME || ' set ');
          --RAISE NOTICE 'sql nach insert version value %', _sql;
        END IF;		

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
  $$
  LANGUAGE plpgsql VOLATILE COST 100;

COMMIT;