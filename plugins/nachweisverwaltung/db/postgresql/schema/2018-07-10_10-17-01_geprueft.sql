BEGIN;

ALTER TABLE nachweisverwaltung.n_nachweise ADD COLUMN geprueft integer NOT NULL DEFAULT 0;

COMMIT;
