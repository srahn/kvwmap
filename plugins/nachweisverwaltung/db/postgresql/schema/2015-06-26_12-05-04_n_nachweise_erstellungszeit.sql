BEGIN;

ALTER TABLE nachweisverwaltung.n_nachweise  ADD COLUMN erstellungszeit timestamp DEFAULT '2015-06-01 00:00:00';

COMMIT;
