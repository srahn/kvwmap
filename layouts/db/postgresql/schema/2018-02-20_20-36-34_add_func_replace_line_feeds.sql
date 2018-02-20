BEGIN;

CREATE OR REPLACE FUNCTION kvw_replace_line_feeds(str text)
  RETURNS text AS
$BODY$
SELECT regexp_replace(regexp_replace(str, '[\r|\n]+', ' ', 'g'), ' +', ' ', 'g');
$BODY$
  LANGUAGE sql IMMUTABLE
  COST 100;

COMMIT;
