BEGIN;

  ALTER TABLE metadata.ressources ADD COLUMN von_eneka boolean;
  ALTER TABLE metadata.ressources RENAME COLUMN last_update TO last_updated_at;
  ALTER TABLE metadata.ressources ADD COLUMN documents character varying[];
  ALTER TABLE metadata.ressources ADD COLUMN import_layer character varying;
  ALTER TABLE metadata.ressources ADD COLUMN import_schema character varying;
  ALTER TABLE metadata.ressources ADD COLUMN import_table character varying;
  ALTER TABLE metadata.ressources ADD COLUMN layer_id integer;
  ALTER TABLE metadata.ressources ADD COLUMN update_time time without time zone;

COMMIT;