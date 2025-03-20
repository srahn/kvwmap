BEGIN;
  ALTER TABLE IF EXISTS metadata.formate DROP CONSTRAINT IF EXISTS formate_format_unique;
  ALTER TABLE IF EXISTS metadata.formate ADD CONSTRAINT formate_format_unique UNIQUE (format);
  INSERT INTO metadata.formate (format) VALUES ('GDB') ON CONFLICT DO NOTHING;

  ALTER TABLE IF EXISTS metadata.import_methods DROP CONSTRAINT IF EXISTS methods_name_unique;
  ALTER TABLE IF EXISTS metadata.import_methods ADD CONSTRAINT methods_name_unique UNIQUE (name);
  INSERT INTO metadata.import_methods (name, beschreibung, reihenfolge) VALUES ('ogr2ogr_gdb', 'Import Geodatabase (GDB) mit ogr2ogr in Postgres', 3) ON CONFLICT DO NOTHING;
COMMIT;