BEGIN;

ALTER TABLE kvwmap.rollenlayer ADD COLUMN max_query_rows integer;

COMMIT;
