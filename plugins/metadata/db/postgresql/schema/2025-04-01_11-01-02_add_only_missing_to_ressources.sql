BEGIN;

  ALTER TABLE IF EXISTS metadata.ressources ADD COLUMN IF NOT EXISTS only_missing BOOLEAN DEFAULT false;

COMMIT;