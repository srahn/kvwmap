BEGIN;

  INSERT INTO metadata.transform_methods (name, beschreibung, reihenfolge) VALUES
  ('gdaltindex', 'Erzeugen einer Index-Datei mit gdaltindex auf einem Verzeichnis von Tiff-Bildern', 4);

COMMIT;