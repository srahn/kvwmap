BEGIN;

CREATE TABLE nachweisverwaltung.n_hauptdokumentarten
(
  id serial NOT NULL,
  art character varying(100),
  abkuerzung character varying(6)
)
WITH (
  OIDS=TRUE
);

INSERT INTO nachweisverwaltung.n_hauptdokumentarten (art, abkuerzung) VALUES 
('Fortführungsriss', 'FFR'),
('Koordinatenverzeichnis', 'KVZ'),
('Grenzniederschrift', 'GN'),
('andere', 'andere');

ALTER TABLE nachweisverwaltung.n_dokumentarten ADD COLUMN hauptart integer NOT NULL DEFAULT 4;

ALTER TABLE nachweisverwaltung.n_nachweise RENAME art TO art_alt;
ALTER TABLE nachweisverwaltung.n_nachweise ADD COLUMN art integer;

UPDATE nachweisverwaltung.n_nachweise SET art = 1 WHERE art_alt = '100';
UPDATE nachweisverwaltung.n_nachweise SET art = 2 WHERE art_alt = '010';
UPDATE nachweisverwaltung.n_nachweise SET art = 3 WHERE art_alt = '001';
UPDATE nachweisverwaltung.n_nachweise SET art = 4 WHERE art_alt = '111';

ALTER TABLE nachweisverwaltung.n_nachweise DROP COLUMN art_alt;

COMMIT;
