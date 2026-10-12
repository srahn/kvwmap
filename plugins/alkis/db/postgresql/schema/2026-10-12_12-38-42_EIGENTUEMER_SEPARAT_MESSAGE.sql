BEGIN;

INSERT INTO kvwmap.config 
  (name, prefix, value, description, type, "group", plugin, saved, editable) 
VALUES (
  'EIGENTUEMER_SEPARAT_MESSAGE', 
  '', 
  'Der Zugriff auf die Eigentümerdaten wird protokolliert.<br>Bitte geben Sie hierfür eine gültige Vorgangsnummer oder einen sonstigen Grund für den Zugriff an:', 
  'Hier kann der Hinweistext für die Eigentümeranzeige definiert werden, wenn die Eigentümer separat angezeigt werden sollen.', 
  'string', 
  'Plugins/alkis', 
  'alkis', 
  0, 
  2);

COMMIT;
