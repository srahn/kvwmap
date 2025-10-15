BEGIN;

  ALTER TABLE IF EXISTS metadata.ressources ADD COLUMN IF NOT EXISTS force_nullable boolean NOT NULL DEFAULT false;
  ALTER TABLE IF EXISTS metadata.ressources ADD COLUMN IF NOT EXISTS encoding character varying;
  INSERT INTO metadata.unpack_methods (name, beschreibung, reihenfolge) VALUES ('replace_xml_encoding', 'Encoding in GML-Dateien ersetzen', 7);

COMMIT;