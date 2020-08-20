BEGIN;

  ALTER TABLE layer_attributes MODIFY COLUMN tooltip text;

COMMIT;
