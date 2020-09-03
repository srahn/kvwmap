BEGIN;

  CREATE TABLE xplankonverter.konformitaetsbedingungen (
    nummer character varying COLLATE pg_catalog."default" NOT NULL,
    version_von character varying COLLATE pg_catalog."default" NOT NULL,
    version_bis character varying COLLATE pg_catalog."default",
    inhalt text COLLATE pg_catalog."default",
    bezeichnung character varying COLLATE pg_catalog."default",
    CONSTRAINT konformitaetsbedingungen_pkey PRIMARY KEY (nummer, version_von)
  )
  WITH ( OIDS = TRUE );

  CREATE TABLE xplankonverter.uml_class2konformitaeten (
    name character varying COLLATE pg_catalog."default" NOT NULL,
    konformitaet_nummer character varying COLLATE pg_catalog."default",
    konformitaet_version_von character varying COLLATE pg_catalog."default",
    CONSTRAINT fk_uml_class2validierungen_name FOREIGN KEY (name)
        REFERENCES xplan_uml.uml_classes (name) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION,
    CONSTRAINT fk_uml_class_konformitaetsbedingungen FOREIGN KEY (konformitaet_nummer, konformitaet_version_von)
        REFERENCES xplankonverter.konformitaetsbedingungen (nummer, version_von) MATCH SIMPLE
        ON UPDATE CASCADE
        ON DELETE CASCADE
  )
  WITH ( OIDS = TRUE );

  CREATE INDEX fki_fk_uml_class2konformitaeten_name
    ON xplankonverter.uml_class2konformitaeten USING btree
    (name COLLATE pg_catalog."default" ASC NULLS LAST)
    TABLESPACE pg_default;

  ALTER TABLE xplankonverter.validierungen
    ADD COLUMN konformitaet_nummer character varying,
    ADD COLUMN konformitaet_version_von character varying,
    ADD COLUMN functionsargumente text[];

  ALTER TABLE xplankonverter.validierungen
    ADD CONSTRAINT fk_validierungen_konformitaetsbedingungen FOREIGN KEY (konformitaet_nummer, konformitaet_version_von)
    REFERENCES xplankonverter.konformitaetsbedingungen (nummer, version_von) MATCH SIMPLE
    ON UPDATE CASCADE
    ON DELETE CASCADE;

    CREATE VIEW xplankonverter.konformitaets_validierungen AS 
    SELECT
      c2k.name AS class_name,
      k.*,
      v.*
    FROM
      xplankonverter.uml_class2konformitaeten c2k JOIN
      xplankonverter.konformitaetsbedingungen k ON c2k.konformitaet_nummer = k.nummer AND c2k.konformitaet_version_von = k.version_von JOIN
      xplankonverter.validierungen v ON k.nummer = v.konformitaet_nummer AND k.version_von = v.konformitaet_version_von


COMMIT;