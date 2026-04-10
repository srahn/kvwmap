BEGIN;
----------------------------------------
----------------------------------------
----------------------------------------
--XPLAN_UML
----------------------------------------
----------------------------------------
----------------------------------------

INSERT INTO xplan_uml.uml_classes(
	xmi_id, name, visibility, "isSpecification", "isRoot", "isLeaf", "isActive", package_id, model_id, created_at, updated_at, "isAbstract", id, stereotype_id, general_id)
	VALUES
	('EAID_41242DCD_3D5D_3202_D66D_3CC7DC42352D', 'SO_Bauverbotszone', 'public', FALSE, FALSE, FALSE, NULL, 37, NULL, now()::timestamp, NULL, FALSE, 423, 'EAID_6FDC92A7_2818_42bf_9B7D_10397E273699' , '-1'),
	('EAID_6323ADBE_6D5D_3333_D53D_3CD6CC31253B', 'SO_KlassifizBauverbot','public', FALSE, FALSE, FALSE, NULL, 37, NULL, now()::timestamp, NULL, FALSE, 424,'EAID_8D09BC59_987A_481a_9FDE_6816AD7DCEF1', '-1'),
	('EAID_62242ABC_3B3A_4521_C33D_3CD3CD51251D', 'SO_DetailKlassifizBauverbot', 'public', FALSE, FALSE, FALSE, NULL, 37, NULL, now()::timestamp, NULL, FALSE, 425,'EAID_E95F3575_1A65_42f2_B5F2_2EA449652444', '-1'),
	('EAID_6423ADBE_6D5D_3542_C53D_3DD6DD31253C', 'SO_KlassifizRechtlicheGrundlageBauverbot','public', FALSE, FALSE, FALSE, NULL, 37, NULL, now()::timestamp, NULL, FALSE, 426,'EAID_8D09BC59_987A_481a_9FDE_6816AD7DCEF1', '-1');

INSERT INTO xplan_uml.class_generalizations (xmi_id,name,"isSpecification",package_id,parent_id,child_id,inheritance_order)
VALUES
('EAID_D1419ADC_A1CE_45eb_716C_D5201C5CDD13','<undefined>',false,37,'EAID_39AFD230_9897_490b_9DCA_89E2D8CFD7D1','EAID_41242DCD_3D5D_3202_D66D_3CC7DC42352D',NULL);


INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_62236BAD_5D3D_3402_B57D_4DB6BB43042C', 'artDerFestlegung',423,'public','','changeable','instance','unordered',now()::timestamp,'','EAID_6323ADBE_6D5D_3333_D53D_3CD6CC31253B','EAID_445D23B2_B87C_2221_FD8D_ADDCC64EC8D1','EAID_434F23B0_C87D_3431_BC5C_DFCDE32EB3C2','0','1','EAID_3B78ED32_C2C2_8b33_6539_3792DC235236',''
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_62236BAD_5D3D_3402_B57D_4DB6BB43042C'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_61242ECD_5D9D_2602_B57D_3DB5CB43242C', 'detailArtDerFestlegung',423,'public','','changeable','instance','unordered',now()::timestamp,'','EAID_62242ABC_3B3A_4521_C33D_3CD3CD51251D','EAID_454F23B2_D87C_4221_BD8D_BADCD54EB8C3','EAID_254F33B0_B37D_2331_BC5C_DCDCE24EA5D3','0','1','EAID_3C46ED82_D3C2_8a33_8639_3792DD135226',''
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_61242ECD_5D9D_2602_B57D_3DB5CB43242C'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_53226BDA_4C2C_3301_B57D_3CB6CB33043D', 'rechtlicheGrundlage',423,'public','','changeable','instance','unordered',now()::timestamp,'','EAID_6423ADBE_6D5D_3542_C53D_3DD6DD31253C','EAID_245D27B2_B69C_2221_FD7D_ADDCC64EC7D3','EAID_344F22B0_C69D_2131_BC5C_DFCDE45EB3C4','0','1','EAID_2B68ED32_C3D1_8b33_6449_3692DC245236',''
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_53226BDA_4C2C_3301_B57D_3CB6CB33043D'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_31432BCD_5C8D_3403_B56C_3BD7CB55333B', 'name',423,'public','','changeable','instance','unordered',now()::timestamp,'','EAID_AF7C81A6_B1C1_4469_A09F_B97989024A14','EAID_354F23B3_C77D_2324_CD8D_DCDBA4ACC6C3','EAID_254F23B3_C38D_1831_DC5D_CCCDEDEAB3D3','0','1','EAID_3B282D32_C2D3_8a33_7549_6743DD224236',''
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_31432BCD_5C8D_3403_B56C_3BD7CB55333B'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_61232BCC_5C3D_2403_B56C_3CC6CB64443D', 'nummer',423,'public','','changeable','instance','unordered',now()::timestamp,'','EAID_AF7C81A6_B1C1_4469_A09F_B97989024A14','EAID_254F23B3_C77D_4324_CD8D_CDDAF4ACC6C1','EAID_254F23B3_C38D_1831_BC5C_DCDDEBEAA3D3','0','1','EAID_2B782D32_D2C3_8a23_7549_474BDD234231',''
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_61232BCC_5C3D_2403_B56C_3CC6CB64443D'
	);


-- SO_KlassifizEnums
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_2123BDCC_4C4D_3443_C55C_3DBC7B65322B', 'Bauverbotszone',424,'public','','changeable','instance','unordered',now()::timestamp,'','eaxmiid0','EAID_344F23B2_C77D_3324_DD4D_CDCAA3BCC6B4','EAID_354F23B3_C38D_2832_BD3C_DCDDBABAA3D2','0','1','EAID_4B782D32_C2C3_4a23_5649_5743DD334232','1000'
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_2123BDCC_4C4D_3443_C55C_3DBC7B65322B'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_2123ECCC_3C3B_4472_B55A_2DB7BB64543A', 'Baubeschraenkungszone',424,'public','','changeable','instance','unordered',now()::timestamp,'','eaxmiid0','EAID_144F23B2_C77D_3324_BD4D_CDCAA3BDC6C2','EAID_354F23B3_C37D_3842_BC4C_DCBCCEBBA3B2','0','1','EAID_3C782D32_C2C3_5a22_7649_67543AD334312','2000'
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_2123ECCC_3C3B_4472_B55A_2DB7BB64543A'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_6323ECCC_4C4D_1742_C55C_2AB5DB64323B', 'Waldabstand',424,'public','','changeable','instance','unordered',now()::timestamp,'','eaxmiid0','EAID_441D23B2_C72C_3226_DD4D_CDCAB3BBC1A3','EAID_354C23F3_C38D_1832_BC4C_DCBDBEBAB3F2','0','1','EAID_4B782D32_B2C3_8a23_6649_6753CB334222','3000'
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_6323ECCC_4C4D_1742_C55C_2AB5DB64323B'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_6124ECCC_3D4D_2443_D53C_2AB6DB64343B', 'SonstigeBeschraenkung',424,'public','','changeable','instance','unordered',now()::timestamp,'','eaxmiid0','EAID_344F23D2_C72C_2226_CD4D_CDCAA3CBC1A3','EAID_354C23F3_C38D_1832_BC4C_DCBDBEBBA3D1','0','1','EAID_4B782D32_D2C3_8b23_6649_6443CD334221','9999'
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_6124ECCC_3D4D_2443_D53C_2AB6DB64343B'
	);
	
-- SO_RechtlicheGrundlageBauverbot
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_2123BDCC_5C4D_2443_C55C_FDBC7B65B223', 'Luftverkehrsrecht',426,'public','','changeable','instance','unordered',now()::timestamp,'','eaxmiid0','EAID_444F23B2_C77D_2324_FD4D_CDBAA3BCA6B4','EAID_354F23B3_C38D_2822_BD3C_DC2ABABAA3D2','0','1','EAID_4B782D36_C2C3_4a33_5649_5743DD332232','1000'
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_2123BDCC_5C4D_2443_C55C_FDBC7B65B223'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_2123ECCC_2CAB_44A2_B55A_1DB7BB64543F', 'Strassenverkehrsrecht',426,'public','','changeable','instance','unordered',now()::timestamp,'','eaxmiid0','EAID_344F23B2_A77D_3324_BA4D_ADCAA3BDC6C2','EAID_254F23B3_C37D_3842_BC4C_DCACC2BBA3B2','0','1','EAID_3C782D62_C2C3_3a22_7649_67543AD332312','2000'
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_2123ECCC_2CAB_44A2_B55A_1DB7BB64543F'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_6323ECCC_5CDD_1742_C55C_4AB5DB64323C', 'SonstigesRecht',426,'public','','changeable','instance','unordered',now()::timestamp,'','eaxmiid0','EAID_341D23B2_D72C_3226_BD4D_CBCAC3BDC1A3','EAID_254C23F3_C38D_1832_BC4C_DCBFBEBA23F2','0','1','EAID_4B786D32_B2C3_5a23_6649_6753CB334232','9999'
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_6323ECCC_5CDD_1742_C55C_4AB5DB64323C'
	);


----------------------------------------
----------------------------------------
----------------------------------------
--XPLAN_GML
----------------------------------------
----------------------------------------
----------------------------------------


-- Type: so_klassifizbauverbot
CREATE TYPE xplan_gml.so_klassifizbauverbot AS ENUM
    ('1000', '2000', '3000', '9999');
ALTER TYPE xplan_gml.so_klassifizbauverbot
    OWNER TO kvwmap;

-- Table: xplan_gml.enum_so_klassifizbauverbot
CREATE TABLE IF NOT EXISTS xplan_gml.enum_so_klassifizbauverbot
(
    wert integer NOT NULL,
    abkuerzung character varying COLLATE pg_catalog."default",
    beschreibung character varying COLLATE pg_catalog."default",
    CONSTRAINT enum_enum_so_klassifizbauverbot_pkey PRIMARY KEY (wert)
)
TABLESPACE pg_default;

ALTER TABLE IF EXISTS xplan_gml.enum_so_klassifizbauverbot
    OWNER to kvwmap;
COMMENT ON TABLE xplan_gml.enum_so_klassifizbauverbot
    IS 'Alias: "enum_SO_KlassifizBauverbot"';

INSERT INTO xplan_gml.enum_so_klassifizbauverbot(wert,abkuerzung,beschreibung)
VALUES
(1000,'Bauverbotszone','Bereich, in denen keine baulichen Anlagen errichtet werden dürfen.'),
(2000,'Baubeschraenkungszone','Bereich, in denen Bau-Beschränkungen bestehen.'),
(3000,'Waldabstadt','Bereich um Wälder, Moore und Heiden, in dem aus Brandschutzgründen keinen baulichen Anlagen errichtet werden dürfen.'),
(9999,'SonstigeBeschraenkung','Bereich mit sonstigen Baubeschränkungen.');

CREATE TYPE xplan_gml.so_rechtlichegrundlagebauverbot AS ENUM
    ('1000', '2000', '9999');
ALTER TYPE xplan_gml.so_rechtlichegrundlagebauverbot
    OWNER TO kvwmap;


CREATE TABLE IF NOT EXISTS xplan_gml.enum_so_rechtlichegrundlagebauverbot
(
    wert integer NOT NULL,
    abkuerzung character varying COLLATE pg_catalog."default",
    beschreibung character varying COLLATE pg_catalog."default",
    CONSTRAINT enum_so_rechtlichegrundlagebauverbot_pkey PRIMARY KEY (wert)
)
TABLESPACE pg_default;

ALTER TABLE IF EXISTS xplan_gml.enum_so_rechtlichegrundlagebauverbot
    OWNER to kvwmap;
COMMENT ON TABLE xplan_gml.enum_so_rechtlichegrundlagebauverbot
    IS 'Alias: "enum_SO_RechtlicheGrundlageBauverbot"';

INSERT INTO xplan_gml.enum_so_rechtlichegrundlagebauverbot(wert,abkuerzung,beschreibung)
VALUES
(1000,'Luftverkehrsrecht',''),
(2000,'Strassenverkehrsrecht',''),
(9999,'SonstigesRecht','');

-- Table: xplan_gml.so_detailklassifizbauverbot
CREATE TABLE IF NOT EXISTS xplan_gml.so_detailklassifizbauverbot
(
    codespace text COLLATE pg_catalog."default",
    id character varying COLLATE pg_catalog."default" NOT NULL,
    value text COLLATE pg_catalog."default",
    CONSTRAINT so_detailklassifizbauverbot_pkey PRIMARY KEY (id)
)
TABLESPACE pg_default;

ALTER TABLE IF EXISTS xplan_gml.so_detailklassifizbauverbot
    OWNER to kvwmap;
COMMENT ON TABLE xplan_gml.so_detailklassifizbauverbot
    IS 'Alias: "SO_DetailKlassifizBauverbot", UML-Typ: Code Liste';
COMMENT ON COLUMN xplan_gml.so_detailklassifizbauverbot.codespace
    IS 'codeSpace  text ';
COMMENT ON COLUMN xplan_gml.so_detailklassifizbauverbot.id
    IS 'id  character varying ';


CREATE TABLE IF NOT EXISTS xplan_gml.so_bauverbotszone
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
    -- Inherited from table xplan_gml.so_geometrieobjekt: aufschrift character varying COLLATE pg_catalog."default",
    -- Inherited from table xplan_gml.so_geometrieobjekt: rechtscharakter xplan_gml.so_rechtscharakter NOT NULL,
    -- Inherited from table xplan_gml.so_geometrieobjekt: sonstrechtscharakter xplan_gml.so_sonstrechtscharakter,
    -- Inherited from table xplan_gml.so_geometrieobjekt: reftextinhalt text COLLATE pg_catalog."default",
    -- Inherited from table xplan_gml.so_geometrieobjekt: nordwinkel double precision,
    -- Inherited from table xplan_gml.so_geometrieobjekt: flussrichtung boolean,
    -- Inherited from table xplan_gml.so_geometrieobjekt: "position" geometry NOT NULL,
    -- Inherited from table xplan_gml.so_geometrieobjekt: flaechenschluss boolean,
    artderfestlegung xplan_gml.so_klassifizbauverbot,
    nummer character varying COLLATE pg_catalog."default",
    name character varying COLLATE pg_catalog."default",
		rechtlichegrundlage xplan_gml.so_rechtlichegrundlagebauverbot,
    detailartderfestlegung xplan_gml.so_detailklassifizbauverbot
)
    INHERITS (xplan_gml.so_geometrieobjekt)
TABLESPACE pg_default;

ALTER TABLE IF EXISTS xplan_gml.so_bauverbotszone
    OWNER to kvwmap;
COMMENT ON TABLE xplan_gml.so_bauverbotszone
    IS 'FeatureType: "SO_Bauverbotszone"';
COMMENT ON COLUMN xplan_gml.so_bauverbotszone.artderfestlegung
    IS 'artDerFestlegung enumeration SO_KlassifizBauverbot 0..1';
COMMENT ON COLUMN xplan_gml.so_bauverbotszone.nummer
    IS 'nummer  CharacterString 0..1';
COMMENT ON COLUMN xplan_gml.so_bauverbotszone.name
    IS 'name  CharacterString 0..1';
COMMENT ON COLUMN xplan_gml.so_bauverbotszone.rechtlichegrundlage
    IS 'rechtlicheGrundlage enumeration SO_RechtlicheGrundlageBauverbot 0..1';
COMMENT ON COLUMN xplan_gml.so_bauverbotszone.detailartderfestlegung
    IS 'detailArtDerFestlegung CodeList SO_DetailKlassifizNachSonstigemRecht 0..1';

-- Index: so_bauverbotszone_gist
CREATE INDEX IF NOT EXISTS so_bauverbotszone_gist
    ON xplan_gml.so_bauverbotszone USING gist
    ("position")
    WITH (fillfactor=90, buffering=auto)
    TABLESPACE pg_default;
		
-- Index: so_bauverbotszone_gml_id
CREATE INDEX IF NOT EXISTS so_bauverbotszone_gml_id
    ON xplan_gml.so_bauverbotszone USING btree
    (gml_id ASC NULLS LAST)
    WITH (fillfactor=100, deduplicate_items=True)
    TABLESPACE pg_default;



-- Mapping gmlas to gml
INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
VALUES
(true,'so_bauverbotszone','xplan_name','character varying','so_bauverbotszone','name','character varying','','','gmlas.xplan_name AS name'),
(true,'so_bauverbotszone','artderfestlegung','character varying','so_bauverbotszone','artderfestlegung','so_klassifizbauverbot','','','gmlas.artderfestlegung::xplan_gml.so_klassifizbauverbot AS artderfestlegung'),
(true,'so_bauverbotszone','detailartderfestlegung','character varying','so_bauverbotszone','detailartderfestlegung','so_detailklassifizbauverbot','detailartderfestlegung_codespace','','(gmlas.detailartderfestlegung_codespace,gmlas.detailartderfestlegung,NULL)::xplan_gml.so_detailklassifizbauverbot AS detailartderfestlegung'),
(true,'so_bauverbotszone','rechtlichegrundlage','character varying','so_bauverbotszone','rechtlichegrundlage','so_rechtlichegrundlagebauverbot','','','gmlas.rechtlichegrundlage::xplan_gml.so_rechtlichegrundlagebauverbot AS rechtlichegrundlage'),
(true,'so_bauverbotszone','ebene','integer','so_bauverbotszone','ebene','integer','','','gmlas.ebene AS ebene'),
(true,'so_bauverbotszone','flaechenschluss','boolean','so_bauverbotszone','flaechenschluss','boolean','','','gmlas.flaechenschluss AS flaechenschluss'),
(true,'so_bauverbotszone','flussrichtung','boolean','so_bauverbotszone','flussrichtung','boolean','','','gmlas.flussrichtung AS flussrichtung'),
(true,'so_bauverbotszone','gehoertzubereich_href','character varying','so_bauverbotszone','gehoertzubereich','text','','','CASE WHEN gmlas.gehoertzubereich_href LIKE ''#GML_%'' OR gmlas.gehoertzubereich_href LIKE ''#gml_%'' OR gmlas.gehoertzubereich_href LIKE ''#Gml_%'' THEN substring(gmlas.gehoertzubereich_href, 6) ELSE gmlas.gehoertzubereich_href END AS gehoertzubereich'),
(true,'so_bauverbotszone','gesetzlichegrundlage','character varying','so_bauverbotszone','gesetzlichegrundlage','xp_gesetzlichegrundlage','gesetzlichegrundlage_codespace','','(gmlas.gesetzlichegrundlage_codespace,gmlas.gesetzlichegrundlage,NULL)::xplan_gml.xp_gesetzlichegrundlage AS gesetzlichegrundlage'),
(true,'so_bauverbotszone','gliederung1','character varying','so_bauverbotszone','gliederung1','character varying','','','gmlas.gliederung1 AS gliederung1'),
(true,'so_bauverbotszone','gliederung2','character varying','so_bauverbotszone','gliederung2','character varying','','','gmlas.gliederung2 AS gliederung2'),
(true,'so_bauverbotszone','id','character varying','so_bauverbotszone','gml_id','uuid','','','CASE WHEN gmlas.id LIKE ''GML_%'' OR gmlas.id LIKE ''gml_%'' OR gmlas.id LIKE ''Gml_%'' THEN substring(gmlas.id, 5)::uuid ELSE gmlas.id::uuid END AS gml_id'),
(true,'so_bauverbotszone','nordwinkel','double precision','so_bauverbotszone','nordwinkel','double precision','','','gmlas.nordwinkel AS nordwinkel'),
(true,'so_bauverbotszone','nummer','character varying','so_bauverbotszone','nummer','character varying','','','gmlas.nummer AS nummer'),
(true,'so_bauverbotszone','position','geometry','so_bauverbotszone','position','geometry','','','gmlas.position AS position'),
(true,'so_bauverbotszone','rechtscharakter','character varying','so_bauverbotszone','rechtscharakter','','','so_rechtscharakter','gmlas.rechtscharakter::xplan_gml.so_rechtscharakter AS rechtscharakter'),
(true,'so_bauverbotszone','rechtsstand','character varying','so_bauverbotszone','rechtsstand','xp_rechtsstand','','','gmlas.rechtsstand::xplan_gml.xp_rechtsstand AS rechtsstand'),
(true,'so_bauverbotszone','sonstrechtscharakter','character varying','so_bauverbotszone','sonstrechtscharakter','so_sonstrechtscharakter','sonstrechtscharakter_codespace','','(gmlas.sonstrechtscharakter_codespace,gmlas.sonstrechtscharakter,NULL)::xplan_gml.so_sonstrechtscharakter AS sonstrechtscharakter'),
(true,'so_bauverbotszone','text','character varying','so_bauverbotszone','text','character varying','','','gmlas.text AS text'),
(true,'so_bauverbotszone','uuid','character varying','so_bauverbotszone','uuid','character varying','','','gmlas.uuid AS uuid'),
(true,'so_bauverbotszone','aufschrift','character varying','so_bauverbotszone','aufschrift','character varying','','','gmlas.aufschrift AS aufschrift');



COMMIT;