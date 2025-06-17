BEGIN;

ALTER TABLE kvwmap.user ADD COLUMN comment text;

COMMIT;
