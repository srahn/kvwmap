BEGIN;

  ALTER TABLE kvwmap.rolle_saved_layers ADD COLUMN layer_params jsonb;

COMMIT;