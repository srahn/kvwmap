BEGIN;
  ALTER TABLE xplankonverter.konvertierungen ADD COLUMN geoweb_service_created_at timestamp without time zone NOT NULL DEFAULT now();
	ALTER TABLE xplankonverter.konvertierungen ADD COLUMN geoweb_service_updated_at timestamp without time zone;
COMMIT;