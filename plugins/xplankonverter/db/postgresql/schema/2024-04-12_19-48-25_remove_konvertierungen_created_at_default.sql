BEGIN;
  ALTER TABLE xplankonverter.konvertierungen
    ALTER COLUMN geoweb_service_created_at DROP DEFAULT,
    ALTER COLUMN geoweb_service_created_at DROP NOT NULL;
COMMIT;