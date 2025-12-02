BEGIN;

ALTER TABLE kvwmap.labels ADD COLUMN align varchar;

COMMIT;
