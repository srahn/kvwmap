BEGIN;

  INSERT INTO xplankonverter.errors (
    error_id, name, beschreibung
  )
  VALUES
    (6, 'GML-Inhalt-Fehler', 'Es ist kein Wirksamkeitsdatum zum Plan angegeben. Das muss korrigiert werden damit der Plan veröffentlicht werden kann.'),
    (7, 'Importfehler keine Pläne', 'Fehler beim Einlesen der Datei in die Datenbank. Es wurden keine Pläne importiert.'),
    (8, 'Importehler beim Zählen der eingelesenen Pläne.', 'Fehler beim Zählen der mit ogr2ogr eingelesenen Pläne der xplan-GML-Datei.');

COMMIT;
