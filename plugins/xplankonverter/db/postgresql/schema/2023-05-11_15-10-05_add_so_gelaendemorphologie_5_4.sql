BEGIN;
-- adds gelaendemorphologie from xplan 5.4 to db


-- Table: xplan_gml.so_detailklassifizgelaendemorphologie

-- DROP TABLE IF EXISTS xplan_gml.so_detailklassifizgelaendemorphologie;

CREATE TABLE IF NOT EXISTS xplan_gml.so_detailklassifizgelaendemorphologie
(
    codespace text COLLATE pg_catalog."default",
    id character varying COLLATE pg_catalog."default" NOT NULL,
    value text COLLATE pg_catalog."default",
    CONSTRAINT so_detailklassifizgelaendemorphologie_pkey PRIMARY KEY (id)
)
TABLESPACE pg_default;

ALTER TABLE IF EXISTS xplan_gml.so_detailklassifizgelaendemorphologie
    OWNER to kvwmap;

COMMENT ON TABLE xplan_gml.so_detailklassifizgelaendemorphologie
    IS 'Alias: "SO_DetailKlassifizGelaendeMorphologie", UML-Typ: Code Liste';

COMMENT ON COLUMN xplan_gml.so_detailklassifizgelaendemorphologie.codespace
    IS 'codeSpace  text ';

COMMENT ON COLUMN xplan_gml.so_detailklassifizgelaendemorphologie.id
    IS 'id  character varying ';


DROP TYPE IF EXISTS xplan_gml.so_klassifizgelaendemorphologie;

CREATE TYPE xplan_gml.so_klassifizgelaendemorphologie AS ENUM
    ('1000', '1100', '1200','9999');

ALTER TYPE xplan_gml.so_klassifizgelaendemorphologie
    OWNER TO kvwmap;


CREATE TABLE IF NOT EXISTS xplan_gml.enum_so_klassifizgelaendemorphologie
(
    wert integer NOT NULL,
    abkuerzung character varying COLLATE pg_catalog."default",
    beschreibung character varying COLLATE pg_catalog."default",
    CONSTRAINT enum_so_klassifizgelaendemorphologie_pkey PRIMARY KEY (wert)
)
TABLESPACE pg_default;

ALTER TABLE IF EXISTS xplan_gml.enum_so_klassifizgelaendemorphologie
    OWNER to kvwmap;

COMMENT ON TABLE xplan_gml.enum_so_klassifizgelaendemorphologie
    IS 'Alias: "enum_SO_KlassifizGelaendeMorphologie"';

INSERT INTO xplan_gml.enum_so_klassifizgelaendemorphologie (wert,abkuerzung,beschreibung)
VALUES
(1000,'Terassenkante','Terassenkante'),
(1100,'Rinne','Trockengefallene Gewässerrinne'),
(1200,'EhemMaeander','Ehemalige Fluss- und Bachmäander'),
(9999,'SonstigeStruktur','Sonstige Struktur der Geländemorphologie');



CREATE TABLE IF NOT EXISTS xplan_gml.so_gelaendemorphologie
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
    -- Inherited from table xplan_gml.so_geometrieobjekt: aufschrift character varying COLLATE pg_catalog."default",
	artderfestlegung xplan_gml.so_klassifizgelaendemorphologie,
	detailartderfestlegung xplan_gml.so_detailklassifizgelaendemorphologie,
	name character varying COLLATE pg_catalog."default",
	nummer character varying COLLATE pg_catalog."default"
)
 INHERITS (xplan_gml.so_geometrieobjekt)
 TABLESPACE pg_default;
 ALTER TABLE IF EXISTS xplan_gml.so_gelaendemorphologie
	OWNER to kvwmap;

COMMENT ON TABLE xplan_gml.so_gelaendemorphologie
    IS 'FeatureType: "SO_GelaendeMorphologie"';

COMMENT ON COLUMN xplan_gml.so_gelaendemorphologie.detailartderfestlegung
    IS 'detailArtDerFestlegung CodeList SO_DetailKlassifizGelaendemorphologie 0..1';

COMMENT ON COLUMN xplan_gml.so_gelaendemorphologie.artderfestlegung
    IS 'artDerFestlegung enumeration SO_KlassifizGelaendemorphologie 0..1';

COMMENT ON COLUMN xplan_gml.so_gelaendemorphologie.name
    IS 'name  CharacterString 0..1';

COMMENT ON COLUMN xplan_gml.so_gelaendemorphologie.nummer
    IS 'nummer  CharacterString 0..1';
-- Index: so_gelaendemorphologie_gist

-- DROP INDEX IF EXISTS xplan_gml.so_gelaendemorphologie_gist;

CREATE INDEX IF NOT EXISTS so_gelaendemorphologie_gist
    ON xplan_gml.so_gelaendemorphologie USING gist
    ("position")
    TABLESPACE pg_default;

INSERT INTO xplan_uml.uml_classes(
	xmi_id, name, visibility, "isSpecification", "isRoot", "isLeaf", "isActive", package_id, model_id, created_at, updated_at, "isAbstract", id, stereotype_id, general_id)
	VALUES
	('EAID_61242DBE_6C5D_3502_C56D_4CD7DC61253C', 'SO_GelaendeMorphologie', 'public', FALSE, FALSE, FALSE, NULL, 41, NULL, now()::timestamp, NULL, FALSE, 409, 'EAID_6FDC92A7_2818_42bf_9B7D_10397E273699' , '-1'),
	('EAID_6322ADBE_6C5D_4532_C53D_3CD7CC31252A', 'SO_KlassifizGelaendemorphologie','public', FALSE, FALSE, FALSE, NULL, 41, NULL, now()::timestamp, NULL, FALSE, 410,'EAID_8D09BC59_987A_481a_9FDE_6816AD7DCEF1', '-1'),
	('EAID_61242ABC_4A3A_6501_D23D_4CD3DD51251B', 'SO_DetailKlassifizGelaendeMorphologie', 'public', FALSE, FALSE, FALSE, NULL, 41, NULL, now()::timestamp, NULL, FALSE, 411,'EAID_E95F3575_1A65_42f2_B5F2_2EA449652444', '-1');



INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_61242ECE_6D9D_3602_B57D_3DB7BB43042D', 'artDerFestlegung',409,'public','','changeable','instance','unordered',now()::timestamp,'','EAID_6322ADBE_6C5D_4532_C53D_3CD7CC31252A','EAID_354F23B2_B87C_4221_BD8D_AADCC64EB8C2','EAID_354F23B0_B87D_3331_BB5C_CFCDE32EA8D3','0','1','EAID_3B78ED22_D3C2_8a33_8639_3792DC235237',''
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_61242ECE_6D9D_3602_B57D_3DB7BB43042D'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_61242ECE_6D9D_3602_B57D_3DB7BB43042D', 'detailArtDerFestlegung',409,'public','','changeable','instance','unordered',now()::timestamp,'','EAID_61242ABC_4A3A_6501_D23D_4CD3DD51251B','EAID_354F23B2_B87C_4221_BD8D_AADCC64EB8C2','EAID_354F23B0_B37D_1331_BC5C_CDDCE32EA7D2','0','1','EAID_3B78ED22_D3C2_8a33_8639_3792DC235237',''
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_61242ECE_6D9D_3602_B57D_3DB7BB43042D'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_61232ECC_5C8D_2403_C56C_2DD7CB64333C', 'name',409,'public','','changeable','instance','unordered',now()::timestamp,'','EAID_AF7C81A6_B1C1_4469_A09F_B97989024A14','EAID_354F23B2_C77D_4324_CD8D_CDDAA4ACC6C2','EAID_354F23B3_C38D_1831_BC5C_DCCDEEEAA3D2','0','1','EAID_4B782D32_C2C3_8a23_7649_5743DD234237',''
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_61232ECC_5C8D_2403_C56C_2DD7CB64333C'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_61232ECC_5C8D_2403_C56C_2DD7CB64443D', 'nummer',409,'public','','changeable','instance','unordered',now()::timestamp,'','EAID_AF7C81A6_B1C1_4469_A09F_B97989024A14','EAID_354F23B2_C77D_4324_CD8D_CDDAA4ACC6C2','EAID_354F23B3_C38D_1831_BC5C_DCCDEEEAA3D2','0','1','EAID_4B782D32_C2C3_8a23_7649_5743DD234237',''
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_61232ECC_5C8D_2403_C56C_2DD7CB64443D'
	);


-- SO_KlassifizEnums
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_6123ECCC_3C4D_3443_C55C_3DB7BB64443D', 'Terassenkante',410,'public','','changeable','instance','unordered',now()::timestamp,'','eaxmiid0','EAID_444F23B2_C77D_4324_CD4D_CDCAA3BCC6B2','EAID_354F23B3_C38D_2832_BC4C_DCBDBABAA3D2','0','1','EAID_4B782D32_C2C3_8a23_7649_6743DD334237','1000'
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_6123ECCC_3C4D_3443_C55C_3DB7BB64443D'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_6123ECCC_3C4D_4472_A55A_2DC7BB64542C', 'Rinne',410,'public','','changeable','instance','unordered',now()::timestamp,'','eaxmiid0','EAID_144F23B2_C77D_4324_CD4D_CDCAA3BDC6C1','EAID_354F23B3_C37D_3842_CC4C_DCBCBEBBA3A2','0','1','EAID_3C782D32_C2C3_8a22_7649_6743AD334317','1100'
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'AID_6123ECCC_3C4D_4472_A55A_2DC7BB64542C'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_6123ECCC_3C4D_1743_C55C_2AB7CB64343D', 'EhemMaeander',410,'public','','changeable','instance','unordered',now()::timestamp,'','eaxmiid0','EAID_244F23B2_C72C_2226_CD4D_CDCAA3BBC1A3','EAID_354C23F3_C38D_1832_BC4C_DCBDBEBAA3F2','0','1','EAID_4B782D32_C2C3_8a23_6649_6743CD334227','1200'
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_6123ECCC_3C4D_1743_C55C_2AB7CB64343D'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_6123ECCC_3C4D_2244_C55C_2CBA7454453D', 'SonstigeStruktur',410,'public','','changeable','instance','unordered',now()::timestamp,'','eaxmiid0','EAID_444F23B2_C7CD_3424_CD4D_BDCBB2BCC6C4','EAID_354C23B3_C38D_4832_CC4C_DCB1B1BA1312','0','1','EAID_4B722C32_C2C3_8a23_7649_6113DC114231','9999'
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_6123ECCC_3C4D_2244_C55C_2CBA7454453D'
	);

COMMIT;