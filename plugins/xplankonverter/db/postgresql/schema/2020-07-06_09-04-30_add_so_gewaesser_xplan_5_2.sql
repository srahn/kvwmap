BEGIN;
-- Type: so_klassifizgewaesser

-- DROP TYPE xplan_gml.so_klassifizgewaesser;

DROP TYPE IF EXISTS xplan_gml.so_klassifizgewaesser;
CREATE TYPE xplan_gml.so_klassifizgewaesser AS ENUM
    ('1000', '10000', '10001', '10002', '10003', '2000', '9999');

ALTER TYPE xplan_gml.so_klassifizgewaesser
    OWNER TO kvwmap;

--------------------------------------------------------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS xplan_gml.enum_so_klassifizgewaesser
(
    wert integer NOT NULL,
    abkuerzung character varying COLLATE pg_catalog."default",
    beschreibung character varying COLLATE pg_catalog."default",
    CONSTRAINT enum_so_klassifizgewaesser_pkey PRIMARY KEY (wert)
)
WITH (
    OIDS = TRUE
)
TABLESPACE pg_default;

ALTER TABLE xplan_gml.enum_so_klassifizgewaesser
    OWNER to kvwmap;
COMMENT ON TABLE xplan_gml.enum_so_klassifizgewaesser
    IS 'Alias: "enum_SO_KlassifizGewaesser"';

TRUNCATE xplan_gml.enum_so_klassifizgewaesser;
INSERT INTO xplan_gml.enum_so_klassifizgewaesser (wert,abkuerzung,beschreibung)
VALUES 
(1000,'Gewaesser','Allgemeines, bestehendes Gewässer'),
(10000,'Gewaesser1Ordnung','Bestehendes Gewässer 1. Ordnung'),
(10001,'Gewaesser2Ordnung','Bestehendes Gewässer 2. Ordnung'),
(10002,'Gewaesser3Ordnung','Bestehendes Gewässer 3. Ordnung'),
(10003,'StehendesGewaesser','Stehendes Gewässer'),
(2000,'Hafen','Hafen'),
(9999,'Sonstiges','Sonstiges bestehendes Gewässer');

--------------------------------------------------------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS xplan_gml.so_detailklassifizgewaesser
(
    codespace text COLLATE pg_catalog."default",
    id character varying COLLATE pg_catalog."default" NOT NULL,
    value text COLLATE pg_catalog."default",
    CONSTRAINT so_detailklassifizgewaesser_pkey PRIMARY KEY (id)
)
WITH (
    OIDS = TRUE
)
TABLESPACE pg_default;

ALTER TABLE xplan_gml.so_detailklassifizgewaesser
    OWNER to kvwmap;
COMMENT ON TABLE xplan_gml.so_detailklassifizgewaesser
    IS 'Alias: "SO_DetailKlassifizGewaesser", UML-Typ: Code Liste';

COMMENT ON COLUMN xplan_gml.so_detailklassifizgewaesser.codespace
    IS 'codeSpace  text ';

COMMENT ON COLUMN xplan_gml.so_detailklassifizgewaesser.id
    IS 'id  character varying ';

--------------------------------------------------------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS xplan_gml.so_gewaesser
(
    -- Inherited from table xplan_gml.so_geometrieobjekt: gml_id uuid NOT NULL DEFAULT uuid_generate_v1mc(),
    -- Inherited from table xplan_gml.so_geometrieobjekt: uuid character varying COLLATE pg_catalog."default",
    -- Inherited from table xplan_gml.so_geometrieobjekt: text character varying COLLATE pg_catalog."default",
    -- Inherited from table xplan_gml.so_geometrieobjekt: rechtsstand xplan_gml.xp_rechtsstand,
    -- Inherited from table xplan_gml.so_geometrieobjekt: gesetzlichegrundlage xplan_gml.xp_gesetzlichegrundlage,
    -- Inherited from table xplan_gml.so_geometrieobjekt: gliederung1 character varying COLLATE pg_catalog."default",
    -- Inherited from table xplan_gml.so_geometrieobjekt: gliederung2 character varying COLLATE pg_catalog."default",
    -- Inherited from table xplan_gml.so_geometrieobjekt: ebene integer,
    -- Inherited from table xplan_gml.so_geometrieobjekt: hatgenerattribut xplan_gml.xp_generattribut[],
    -- Inherited from table xplan_gml.so_geometrieobjekt: hoehenangabe xplan_gml.xp_hoehenangabe[],
    -- Inherited from table xplan_gml.so_geometrieobjekt: user_id integer,
    -- Inherited from table xplan_gml.so_geometrieobjekt: created_at timestamp without time zone NOT NULL DEFAULT now(),
    -- Inherited from table xplan_gml.so_geometrieobjekt: updated_at timestamp without time zone NOT NULL DEFAULT now(),
    -- Inherited from table xplan_gml.so_geometrieobjekt: konvertierung_id integer,
    -- Inherited from table xplan_gml.so_geometrieobjekt: refbegruendunginhalt text COLLATE pg_catalog."default",
    -- Inherited from table xplan_gml.so_geometrieobjekt: gehoertzubereich text COLLATE pg_catalog."default",
    -- Inherited from table xplan_gml.so_geometrieobjekt: wirddargestelltdurch text COLLATE pg_catalog."default",
    -- Inherited from table xplan_gml.so_geometrieobjekt: externereferenz xplan_gml.xp_spezexternereferenz[],
    -- Inherited from table xplan_gml.so_geometrieobjekt: startbedingung xplan_gml.xp_wirksamkeitbedingung,
    -- Inherited from table xplan_gml.so_geometrieobjekt: endebedingung xplan_gml.xp_wirksamkeitbedingung,
    -- Inherited from table xplan_gml.so_geometrieobjekt: rechtscharakter xplan_gml.so_rechtscharakter NOT NULL,
    -- Inherited from table xplan_gml.so_geometrieobjekt: sonstrechtscharakter xplan_gml.so_sonstrechtscharakter,
    -- Inherited from table xplan_gml.so_geometrieobjekt: reftextinhalt text COLLATE pg_catalog."default",
    -- Inherited from table xplan_gml.so_geometrieobjekt: nordwinkel double precision,
    -- Inherited from table xplan_gml.so_geometrieobjekt: flussrichtung boolean,
    -- Inherited from table xplan_gml.so_geometrieobjekt: "position" geometry NOT NULL,
    -- Inherited from table xplan_gml.so_geometrieobjekt: flaechenschluss boolean,
    detailartderfestlegung xplan_gml.so_detailklassifizgewaesser,
    artderfestlegung xplan_gml.so_klassifizgewaesser,
    name character varying COLLATE pg_catalog."default",
	nummer character varying COLLATE pg_catalog."default"
    -- Inherited from table xplan_gml.so_geometrieobjekt: aufschrift character varying COLLATE pg_catalog."default"
)
    INHERITS (xplan_gml.so_geometrieobjekt)
WITH (
    OIDS = TRUE
)
TABLESPACE pg_default;

ALTER TABLE xplan_gml.so_gewaesser
    OWNER to kvwmap;
COMMENT ON TABLE xplan_gml.so_gewaesser
    IS 'FeatureType: "SO_Gewaesser"';

COMMENT ON COLUMN xplan_gml.so_gewaesser.detailartderfestlegung
    IS 'detailArtDerFestlegung CodeList SO_DetailKlassifizGewaesser 0..1';

COMMENT ON COLUMN xplan_gml.so_gewaesser.artderfestlegung
    IS 'artDerFestlegung enumeration SO_KlassifizGewaesser 0..1';

COMMENT ON COLUMN xplan_gml.so_gewaesser.nummer
    IS 'nummer  CharacterString 0..1';

COMMENT ON COLUMN xplan_gml.so_gewaesser.name
    IS 'name  CharacterString 0..1';

COMMIT;
