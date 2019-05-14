BEGIN;

  ALTER TABLE xplankonverter.konvertierungen ADD COLUMN veroeffentlicht BOOLEAN NOT NULL DEFAULT FALSE;

COMMIT;
