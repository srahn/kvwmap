BEGIN;
  CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

  CREATE TABLE syncs_all (
    id serial NOT NULL PRIMARY KEY,
    client_id character varying,
    username character varying,
    client_time timestamp without time zone,
    pull_from_version integer,
    pull_to_version integer
  );

  CREATE TABLE deltas_all (
    version serial NOT NULL PRIMARY KEY,
    client_id character varying,
    sql text,
    schema_name character varying,
    table_name character varying,
    action character varying
  );

  --
  -- INSERT Trigger
  --
  CREATE OR REPLACE FUNCTION create_insert_delta()
  RETURNS trigger AS
  $$
    DECLARE
      new_version integer := (SELECT (coalesce(max(version), 1) + 1)::integer FROM deltas_all);
      _query TEXT;
      _sql TEXT;
      part TEXT;
      search_path_schema TEXT;
      version_column TEXT;
    BEGIN
      _query := current_query();

      --raise notice '_query: %', _query;
      foreach part in array string_to_array(_query, ';')
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
          table_schema = %1\$L AND
          table_name = %2\$L AND
          column_name = %3\$L
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

      INSERT INTO deltas_all (client_id, sql, schema_name, table_name, action) VALUES (NEW.client_id, _sql, TG_TABLE_SCHEMA, TG_TABLE_NAME, 'insert');
      RAISE NOTICE 'Neuen Datensatz mit Version % für Synchronisierung eingetragen.', new_version;

      RETURN NEW;
    END;
  $$
  LANGUAGE plpgsql VOLATILE COST 100;

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
            table_schema = %1\$L AND
            table_name = %2\$L AND
            column_name = %3\$L
          ', TG_TABLE_SCHEMA, TG_TABLE_NAME, 'version'
        )
        INTO version_column;

        -- Version wird nur angehaengt wenn es die Spalte version gibt
        IF version_column IS NOT NULL THEN
          _sql := kvw_insert_str_after(_sql, 'version = ' || new_version || ', ', ' ' || TG_TABLE_SCHEMA || '.' || TG_TABLE_NAME || ' set ');
          --RAISE NOTICE 'sql nach insert version value %', _sql;
        END IF;

        INSERT INTO deltas_all (client_id, sql, schema_name, table_name, action) VALUES (NEW.client_id, _sql, TG_TABLE_SCHEMA, TG_TABLE_NAME, 'update');

        RAISE NOTICE 'Änderung mit Version % für Synchronisierung eingetragen.', new_version;
      END IF;

      RETURN NEW;
    END;
  $$
  LANGUAGE plpgsql VOLATILE COST 100;


  --
  -- DELETE Trigger
  --
  CREATE OR REPLACE FUNCTION create_delete_delta()
  RETURNS trigger AS
  $$
    DECLARE
      new_version integer := (SELECT (coalesce(max(version), 1) + 1)::integer FROM deltas_all);
      _query TEXT;
      _sql TEXT;
      part TEXT;
      search_path_schema TEXT;
    BEGIN
      _query := current_query();

      --RAISE NOTICE 'Current Query unverändert: %', _query;
      foreach part in array string_to_array(_query, ';')
      loop
        -- replace horizontal tabs, new lines and carriage returns
        part := trim(regexp_replace(part, E'[\\t\\n\\r]+', ' ', 'g'));

        IF strpos(lower(part), 'set search_path') = 1 THEN
          search_path_schema := trim(lower(split_part(split_part(part, '=', 2), ',', 1)));
          --RAISE notice 'schema in search_path %', search_path_schema;
        END IF;

        part := replace(part, ' \"' || TG_TABLE_NAME || '\" ', ' ' || TG_TABLE_NAME || ' ');
        --RAISE notice 'Anfuehrungsstriche von Tabellennamen entfernt: %', part;

        IF strpos(lower(part), 'delete from ' || TG_TABLE_SCHEMA || '.' || TG_TABLE_NAME) = 1 OR (strpos(lower(part), 'delete from ' || TG_TABLE_NAME) = 1 AND TG_TABLE_SCHEMA = search_path_schema) THEN
          _sql := part;
        END IF;
      end loop;
      --raise notice 'sql nach split by ; und select by update: %', _sql;

      _sql := replace(_sql, ' ' || TG_TABLE_NAME || ' ', ' ' || TG_TABLE_SCHEMA || '.' || TG_TABLE_NAME || ' ');
      --RAISE notice 'sql nach replace tablename by schema and tablename: %', _sql;

      --_sql := split_part(_sql, ' WHERE ', 1) || ' WHERE uuid = ''' || OLD.uuid || '''';
      --RAISE NOTICE 'sql ohne replace where by uuid: %', _sql;

      INSERT INTO deltas_all (sql, schema_name, table_name, action) VALUES (_sql, TG_TABLE_SCHEMA, TG_TABLE_NAME, 'delete');
      --RAISE NOTICE 'Löschung mit Version % für Synchronisierung eingetragen.', new_version;

      RETURN OLD;
    END;
  $$
  LANGUAGE plpgsql VOLATILE COST 100;

COMMIT;
