BEGIN;

INSERT INTO kvwmap.config 
  (name, prefix, value, description, type, "group", plugin, saved, editable) 
VALUES (
  'stellen_eigentuemer_separat', 
  '', 
  '[]', 
  'Hier können die Stellen-IDs definiert werden, in denen die Eigentümeranzeige nicht direkt in der Flurstücksanzeige, sondern separat und nur unter Angabe einer Vorgangsnummer erfolgen soll.', 
  'array', 
  'Plugins/alkis', 
  'alkis', 
  0, 
  2);

COMMIT;
