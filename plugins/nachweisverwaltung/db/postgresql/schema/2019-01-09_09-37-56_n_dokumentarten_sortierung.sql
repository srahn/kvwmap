BEGIN;

ALTER TABLE nachweisverwaltung.n_dokumentarten ADD COLUMN sortierung integer;

COMMIT;
