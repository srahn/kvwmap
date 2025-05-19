BEGIN;

CREATE OR REPLACE FUNCTION kvwmap.sha1(str text)
    RETURNS text
    LANGUAGE 'sql'
    COST 100
AS $BODY$

SELECT encode(digest(str::bytea, 'sha1'), 'hex');

$BODY$;

COMMIT;
