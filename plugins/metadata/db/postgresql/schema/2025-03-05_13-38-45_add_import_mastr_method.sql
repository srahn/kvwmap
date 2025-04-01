BEGIN;

  INSERT INTO metadata.import_methods (name, beschreibung, reihenfolge) VALUES
  ('mastr', 'Download und Import MaStR', 6);

COMMIT;