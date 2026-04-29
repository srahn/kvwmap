BEGIN;

ALTER TABLE kvwmap.layer_attributes ALTER COLUMN "default" TYPE text USING "default"::text;

COMMIT;
