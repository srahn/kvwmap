BEGIN;

INSERT INTO kvwmap.config 
  (name, prefix, value, description, type, "group", plugin, saved, editable) 
VALUES
  ('layer_status', '', '
  {
    "under_construction" : "im Aufbau",
    "sensible" : "sensibel"
  }
  ', 'Dieses Array dient zur Definition der Layer-Status. Der Layer-Status kann für jeden Layer im Layer-Formular gesetzt werden. Wenn ein Layer-Status gesetzt ist, bekommt der Layername im Themenbaum diesen Status als CSS-Klassennamen. ', 'array', 'Administration', '', 0, 2);

COMMIT;
