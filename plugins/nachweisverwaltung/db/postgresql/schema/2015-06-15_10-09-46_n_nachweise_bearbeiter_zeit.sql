BEGIN;

ALTER TABLE nachweisverwaltung.n_nachweise
   ADD COLUMN bearbeiter character varying(50),
   ADD COLUMN zeit timestamp;

COMMIT;
