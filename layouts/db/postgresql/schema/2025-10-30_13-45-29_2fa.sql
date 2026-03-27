BEGIN;

INSERT INTO kvwmap.config 
  (name, prefix, value, description, type, "group", plugin, saved, editable) 
VALUES
  ('TOTP_AUTHENTICATION', '', 'false', 'Wenn aktiviert, muss sich jeder Nutzer zusätzlich über einen TOTP-basierten 2. Faktor authentifizieren.', 'boolean', 'Administration', '', 0, 2);


ALTER TABLE kvwmap.user ADD COLUMN totp_secret VARCHAR(32);

COMMIT;
