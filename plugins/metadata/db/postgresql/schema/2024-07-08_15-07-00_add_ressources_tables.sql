BEGIN;
  DROP TABLE IF EXISTS metadata.ampel CASCADE;
  CREATE TABLE metadata.ampel (
    id serial NOT NULL Primary Key,
    farbe character varying,
    style character varying
  );
  INSERT INTO metadata.ampel (id, farbe, style) VALUES
  (1, 'rot', 'background-color: red'),
  (2, 'gelb', 'background-color: lightyellow'),
  (3, 'grün', 'background-color: lightgreen');

  DROP TABLE IF EXISTS metadata.datenguete CASCADE;
  CREATE TABLE metadata.datenguete (
    id serial NOT NULL Primary Key,
    guete character varying
  );
  INSERT INTO metadata.datenguete (id, guete) VALUES
  (1, 'A'),
  (2, 'B'),
  (3, 'C');

  DROP TABLE IF EXISTS metadata.gruppen CASCADE;
  CREATE TABLE metadata.gruppen (
    id serial NOT NULL Primary Key,
    gruppe character varying
  );
  INSERT INTO metadata.gruppen (gruppe)
  SELECT DISTINCT gruppe FROM import.kwp_datenbestandsaufnahme ORDER BY gruppe;

  DROP TABLE IF EXISTS metadata.dateninhaber CASCADE;
  CREATE TABLE metadata.dateninhaber (
    id serial NOT NULL Primary Key,
    dateninhaber character varying
  );
  INSERT INTO metadata.dateninhaber (dateninhaber)
  SELECT DISTINCT dateninhaber FROM import.kwp_datenbestandsaufnahme ORDER BY dateninhaber;

  DROP TABLE IF EXISTS metadata.formate CASCADE;
  CREATE TABLE metadata.formate (
    id serial NOT NULL Primary Key,
    format character varying
  );
  INSERT INTO metadata.formate (format)
  SELECT DISTINCT format FROM import.kwp_datenbestandsaufnahme ORDER BY format;

  DROP TABLE IF EXISTS metadata.ressources CASCADE;
  CREATE TABLE IF NOT EXISTS metadata.ressources
  (
      ampel_id integer,
      gruppe_id integer,
      datenquelle text COLLATE pg_catalog."default",
      hinweise_auf text COLLATE pg_catalog."default",
      beschreibung text COLLATE pg_catalog."default",
      dateninhaber_id integer,
      ansprechperson text COLLATE pg_catalog."default",
      format_id integer,
      aktualitaet text COLLATE pg_catalog."default",
      url text COLLATE pg_catalog."default",
      datenguete_id integer,
      quelle text COLLATE pg_catalog."default",
      github text COLLATE pg_catalog."default",
      download_url character varying COLLATE pg_catalog."default",
      dest_path character varying COLLATE pg_catalog."default",
      download_script character varying COLLATE pg_catalog."default",
      id serial NOT NULL PRIMARY KEY,
      CONSTRAINT ampel_id FOREIGN KEY (ampel_id)
          REFERENCES metadata.ampel (id) MATCH SIMPLE
          ON UPDATE SET NULL
          ON DELETE SET NULL,
      CONSTRAINT dateninhaber_fk FOREIGN KEY (dateninhaber_id)
          REFERENCES metadata.dateninhaber (id) MATCH SIMPLE
          ON UPDATE SET NULL
          ON DELETE SET NULL,
      CONSTRAINT gruppe_fk FOREIGN KEY (gruppe_id)
          REFERENCES metadata.gruppen (id) MATCH SIMPLE
          ON UPDATE SET NULL
          ON DELETE SET NULL,
      CONSTRAINT guete_fk FOREIGN KEY (datenguete_id)
          REFERENCES metadata.datenguete (id) MATCH SIMPLE
          ON UPDATE SET NULL
          ON DELETE SET NULL
  );

/*
  INSERT INTO metadata.ressources
  SELECT
    a.id AS ampel_id,
    g.id AS gruppe_id,
    datenquelle,
    hinweise_auf,
    beschreibung,
    di.id AS dateninhaber_id,
    ansprechperson,
    f.id AS format_id,
    aktualitaet,
    url,
    d.id AS datenguete_id,
    quelle,
    github,
    ''::character varying AS download_url,
    ''::character varying AS dest_path,
    ''::character varying AS download_script
  FROM
    import.kwp_datenbestandsaufnahme ba JOIN
    metadata.gruppen g ON ba.gruppe = g.gruppe LEFT JOIN
    metadata.ampel a ON ba.ampel = a.id LEFT JOIN
    metadata.datenguete d ON ba.datenguete = d.guete LEFT JOIN
    metadata.dateninhaber di ON ba.dateninhaber = di.dateninhaber LEFT JOIN
    metadata.formate f ON ba.format = f.format
  ORDER BY ba.gruppe, ba.dateninhaber, ba.datenquelle;
*/

  DROP TABLE IF EXISTS metadata.subressources CASCADE;
  CREATE TABLE metadata.subressources (
    id serial NOT NULL Primary Key,
    ressource_id integer NOT NULL,
    bezeichnung character varying,
    download_url character varying
  );

  ALTER TABLE IF EXISTS metadata.subressources
    ADD CONSTRAINT ressource_fk FOREIGN KEY (ressource_id)
    REFERENCES metadata.ressources (id) MATCH SIMPLE
    ON UPDATE CASCADE
    ON DELETE CASCADE;

  DROP TABLE IF EXISTS metadata.subressourceranges CASCADE;
  CREATE TABLE metadata.subressourceranges (
    id serial NOT NULL Primary Key,
    subressource_id integer NOT NULL,
    name character varying,
    von integer,
    bis integer,
    step integer
  );

  ALTER TABLE IF EXISTS metadata.subressourceranges
    ADD CONSTRAINT subressource_fk FOREIGN KEY (subressource_id)
    REFERENCES metadata.subressources (id) MATCH SIMPLE
    ON UPDATE CASCADE
    ON DELETE CASCADE;
COMMIT;