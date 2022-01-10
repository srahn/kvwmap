BEGIN;

ALTER TABLE nachweisverwaltung.n_dokumentarten ADD COLUMN pok_pflicht boolean NOT NULL DEFAULT true;

COMMIT;
