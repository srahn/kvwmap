BEGIN;

ALTER TABLE kvwmap.rolle_saved_layers
  ALTER COLUMN layers TYPE integer[]
  USING string_to_array(layers, ',')::integer[];

COMMIT;
