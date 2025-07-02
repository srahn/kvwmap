BEGIN;

ALTER TABLE kvwmap.layer_charts ALTER COLUMN beschreibung DROP NOT NULL;

COMMIT;
