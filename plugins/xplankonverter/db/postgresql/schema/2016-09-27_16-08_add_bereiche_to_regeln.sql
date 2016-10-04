BEGIN;

ALTER TABLE xplankonverter.regeln ADD COLUMN bereiche text[];
ALTER TABLE xplankonverter.validierungsergebnisse ADD COLUMN regel_id integer;

COMMIT;