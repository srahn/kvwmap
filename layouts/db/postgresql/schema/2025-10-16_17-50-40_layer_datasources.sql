BEGIN;

ALTER TABLE IF EXISTS kvwmap.layer_datasources ADD COLUMN id serial NOT NULL;

COMMIT;
