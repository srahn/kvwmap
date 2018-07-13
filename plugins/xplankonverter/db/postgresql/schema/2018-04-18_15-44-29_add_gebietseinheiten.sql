BEGIN;

	ALTER TABLE xplankonverter.konvertierungen ADD COLUMN gebietseinheiten character varying;

COMMIT;
