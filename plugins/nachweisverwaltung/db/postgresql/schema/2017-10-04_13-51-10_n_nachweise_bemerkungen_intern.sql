BEGIN;

ALTER TABLE nachweisverwaltung.n_nachweise ADD COLUMN bemerkungen_intern text;

COMMIT;
