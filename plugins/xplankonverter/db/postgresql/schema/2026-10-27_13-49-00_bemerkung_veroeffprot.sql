BEGIN;
	ALTER TABLE xplankonverter.veroeffentlichungsprotokolle ADD COLUMN IF NOT EXISTS bemerkung text;
COMMIT;