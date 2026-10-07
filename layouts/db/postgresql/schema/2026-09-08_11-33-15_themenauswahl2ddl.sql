BEGIN;
  ALTER TABLE kvwmap.datendrucklayouts ADD COLUMN saved_layers_id integer;
COMMIT;