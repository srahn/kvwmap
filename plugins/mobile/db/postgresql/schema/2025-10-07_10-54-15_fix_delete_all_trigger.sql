BEGIN;

  CREATE OR REPLACE FUNCTION public.create_delete_delta()
  RETURNS trigger
  LANGUAGE plpgsql
  AS $function$
    DECLARE
      _sql TEXT;
    BEGIN
      _sql = Format('DELETE FROM %1$s.%2$s WHERE uuid = ''%3$s''', TG_TABLE_SCHEMA, TG_TABLE_NAME, OLD.uuid);

      INSERT INTO deltas_all (client_id, sql, schema_name, table_name, action, uuid) VALUES
      (current_setting('public.client_id', true), _sql, TG_TABLE_SCHEMA, TG_TABLE_NAME, 'delete', OLD.uuid);
      RETURN OLD;

    END;
  $function$;

COMMIT;