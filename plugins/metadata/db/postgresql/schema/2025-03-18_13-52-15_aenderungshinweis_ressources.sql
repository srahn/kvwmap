BEGIN;

  ALTER TABLE IF EXISTS metadata.ressources ADD COLUMN IF NOT EXISTS aenderungshinweise text;
  ALTER TABLE IF EXISTS metadata.ressources ADD COLUMN IF NOT EXISTS nutzungsberechtigte text;
  ALTER TABLE IF EXISTS metadata.ressources ADD COLUMN IF NOT EXISTS nutzungsbedingungen text;
  ALTER TABLE IF EXISTS metadata.ressources ADD COLUMN IF NOT EXISTS quellenvermerk text;

COMMIT;