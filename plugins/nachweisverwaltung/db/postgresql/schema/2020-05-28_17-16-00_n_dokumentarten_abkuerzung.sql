BEGIN;

ALTER TABLE nachweisverwaltung.n_dokumentarten ADD COLUMN abkuerzung character varying(6);

COMMIT;
