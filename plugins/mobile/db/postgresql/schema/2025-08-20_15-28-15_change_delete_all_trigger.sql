BEGIN;

  --
  -- DELETE Trigger
  --
  CREATE OR REPLACE FUNCTION public.create_delete_delta()
  RETURNS trigger AS
  $$
    DECLARE
      new_version integer := (SELECT (coalesce(max(version), 1) + 1)::integer FROM deltas_all);
      _query TEXT;
      _sql TEXT;
      part TEXT;
      search_path_schema TEXT;
      uuid UUID;
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

      -- uuid aus current_setting oder sql ziehen, nach where Part nach uuid, davon Part nach =, davon Part nach '
      uuid = (SELECT COALESCE(
        current_setting('public.uuid', true),
        SPLIT_PART(SPLIT_PART(SPLIT_PART(SUBSTRING(_sql, POSITION('where' IN LOWER(_sql)) + 6), 'uuid', '2'), '=', 2), '''', 2)
      ));

      INSERT INTO deltas_all (client_id, sql, schema_name, table_name, action, uuid) VALUES
      (current_setting('public.client_id', true), _sql, TG_TABLE_SCHEMA, TG_TABLE_NAME, 'delete', uuid);
      --RAISE NOTICE 'Löschung mit Version % für Synchronisierung eingetragen.', new_version;

      RETURN OLD;
    END;
  $$
  LANGUAGE plpgsql VOLATILE COST 100;

COMMIT;