BEGIN;

INSERT INTO kvwmap.config 
  (name, prefix, value, description, type, "group", plugin, saved, editable) 
VALUES
  ('SHOW_DATAOWNER_CONTACT', '', 'false', 'Wenn true dann wird in der Themenübersicht die Email-Adresse und die Telefonnummer des Ansprechpartners angezeigt.', 'boolean', 'Administration', '', 0, 2);


COMMIT;
