BEGIN;

  ALTER TABLE IF EXISTS metadata.ressources
    ADD COLUMN IF NOT EXISTS upload_file character varying,
    ADD COLUMN IF NOT EXISTS aenderungshinweise text,
    ADD COLUMN IF NOT EXISTS nutzungsberechtigte text,
    ADD COLUMN IF NOT EXISTS nutzungsbedingungen text,
    ADD COLUMN IF NOT EXISTS quellenvermerk text,
    ADD COLUMN IF NOT EXISTS force_nullable boolean,
    ADD COLUMN IF NOT EXISTS "encoding" boolean;

  INSERT INTO metadata.download_methods (name, beschreibung, reihenfolge) VALUES ('upload', 'Datei-Upload manuell', 6);

COMMIT;