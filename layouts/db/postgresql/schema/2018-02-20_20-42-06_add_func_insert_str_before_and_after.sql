BEGIN;

CREATE OR REPLACE FUNCTION kvw_insert_str_before(
    str text,
    istr text,
    heystack text)
  RETURNS text AS
$BODY$
  select substr(str, 1, strpos(str, heystack) - 1) || istr || substr(str, strpos(str, heystack))
$BODY$
  LANGUAGE sql IMMUTABLE
  COST 100;

CREATE OR REPLACE FUNCTION kvw_insert_str_after(
    str text,
    istr text,
    heystack text)
  RETURNS text AS
$BODY$
  select regexp_replace(str, heystack, heystack || istr, 'i')
$BODY$
  LANGUAGE sql IMMUTABLE
  COST 100;

COMMIT;
