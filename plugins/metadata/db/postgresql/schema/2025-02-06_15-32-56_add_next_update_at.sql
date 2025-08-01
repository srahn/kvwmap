BEGIN;

  ALTER TABLE IF EXISTS metadata.ressources ADD COLUMN IF NOT EXISTS next_update_at timestamp without time zone;
  INSERT INTO metadata.download_methods (name, beschreibung, reihenfolge) VALUES
  ('parallel_from_file', 'Download Dateien parallel aus Datei urls.txt', 4);

COMMIT;