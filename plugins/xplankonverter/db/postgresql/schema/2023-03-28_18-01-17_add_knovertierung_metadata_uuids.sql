BEGIN;
  ALTER TABLE xplankonverter.konvertierungen ADD COLUMN metadata_dataset_uuid uuid;
  ALTER TABLE xplankonverter.konvertierungen ADD COLUMN metadata_viewservice_uuid uuid;
  ALTER TABLE xplankonverter.konvertierungen ADD COLUMN metadata_downloadservice_uuid uuid;
COMMIT;
