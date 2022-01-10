BEGIN;

  CREATE TABLE nachweisverwaltung.n_rissfuehrer (
    id serial NOT NULL PRIMARY KEY,
    name character varying (255)
  );

  ALTER TABLE nachweisverwaltung.n_nachweise
    ADD COLUMN datum_bis date,
    ADD COLUMN aenderungsnummer character varying(255),
    ADD COLUMN antragsnummer_alt character varying(255),
    ADD COLUMN rissfuehrer_id integer,
    ADD COLUMN messungszahlen boolean,
    ADD COLUMN bov_ersetzt boolean,
    ADD COLUMN zeit_geprueft timestamp without time zone,
    ADD COLUMN freigegeben boolean NOT NULL DEFAULT false;

COMMIT;
