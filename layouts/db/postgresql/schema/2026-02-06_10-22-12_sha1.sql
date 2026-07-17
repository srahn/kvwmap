BEGIN;

CREATE OR REPLACE FUNCTION kvwmap.sha1(str text)
 RETURNS text
 LANGUAGE sql
AS $function$

SELECT encode(digest(str, 'sha1'), 'hex');

$function$
;

COMMIT;
