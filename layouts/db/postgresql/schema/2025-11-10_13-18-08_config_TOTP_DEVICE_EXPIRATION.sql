BEGIN;

INSERT INTO kvwmap.config 
  (name, prefix, value, description, type, "group", plugin, saved, editable) 
VALUES
  ('TOTP_DEVICE_EXPIRATION', '', '30', 'Nach erfolgreicher TOTP-Authentifierung kann sich das Gerät des Nutzers gemerkt werden und die Authentifizierung über den 2. Faktor ist dann eine gewisse Zeit nicht nötig. Wie lange kann hier in Tagen angegeben werden. ', 'integer', 'Administration', '', 0, 2);


COMMIT;
