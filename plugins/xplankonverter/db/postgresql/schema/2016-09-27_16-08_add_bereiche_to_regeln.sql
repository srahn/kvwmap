BEGIN;

ALTER TABLE xplankonverter.regeln ADD COLUMN bereiche text[];

COMMIT;