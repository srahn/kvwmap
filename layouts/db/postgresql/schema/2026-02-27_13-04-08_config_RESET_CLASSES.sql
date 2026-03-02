BEGIN;

INSERT INTO kvwmap.config 
  (name, prefix, value, description, type, "group", plugin, saved, editable) 
VALUES
  ('RESET_CLASSES', '', 'true', 'Wenn true, werden die deaktivierten Klassen beim Login wieder zurückgesetzt.', 'boolean', 'Administration', '', 0, 2);

COMMIT;
