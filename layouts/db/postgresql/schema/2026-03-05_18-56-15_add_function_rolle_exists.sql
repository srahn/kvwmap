BEGIN;
  DROP FUNCTION kvwmap.rolle_exists(int, int);

  CREATE OR REPLACE FUNCTION kvwmap.rolle_exists(sid integer, uid integer)
  RETURNS boolean
  LANGUAGE sql
  AS $function$
      SELECT EXISTS (
          SELECT 1
          FROM kvwmap.rolle
          WHERE user_id = uid
            AND stelle_id = sid
      );
  $function$;
END;