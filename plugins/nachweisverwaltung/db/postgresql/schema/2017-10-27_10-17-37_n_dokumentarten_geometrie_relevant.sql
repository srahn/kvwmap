BEGIN;

ALTER TABLE nachweisverwaltung.n_dokumentarten ADD COLUMN geometrie_relevant boolean NOT NULL DEFAULT false;

COMMIT;
