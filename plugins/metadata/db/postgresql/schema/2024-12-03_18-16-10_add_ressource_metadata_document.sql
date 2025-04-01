BEGIN;

  ALTER TABLE IF EXISTS metadata.ressources ADD COLUMN IF NOT EXISTS metadata_document character varying;
  ALTER TABLE IF EXISTS metadata.data_packages ADD COLUMN IF NOT EXISTS updated_at timestamp without time zone NOT NULL default now();
  ALTER TABLE IF EXISTS metadata.dateninhaber ADD COLUMN IF NOT EXISTS abk varchar(10);
  CREATE TABLE metadata.gebietseinheiten (
    id serial NOT NULL PRIMARY KEY,
    name character varying NOT NULL,
    abk varchar(10),
    reihenfolge integer
  );
  ALTER TABLE IF EXISTS metadata.ressources ADD COLUMN IF NOT EXISTS gebietseinheit_id integer;
  ALTER TABLE IF EXISTS metadata.ressources
    ADD CONSTRAINT ressources_gebietseinheit_id FOREIGN KEY (gebietseinheit_id)
    REFERENCES metadata.gebietseinheiten (id) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION;

COMMIT;