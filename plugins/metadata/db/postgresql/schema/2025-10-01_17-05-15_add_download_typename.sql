BEGIN;

  ALTER TABLE IF EXISTS metadata.ressources ADD COLUMN IF NOT EXISTS download_typenames character varying;
  UPDATE metadata.ressources SET download_typenames = import_layer WHERE download_method = 'wfs' AND import_layer IS NOT NULL;
COMMIT;