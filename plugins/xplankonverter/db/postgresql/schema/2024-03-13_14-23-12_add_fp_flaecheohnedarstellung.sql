BEGIN;

-- Table: xplan_gml.fp_landwirtschaftsflaeche

-- DROP TABLE xplan_gml.fp_landwirtschaftsflaeche;

CREATE TABLE IF NOT EXISTS xplan_gml.fp_flaecheohnedarstellung
(
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: gml_id uuid NOT NULL DEFAULT uuid_generate_v1mc(),
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: uuid character varying COLLATE pg_catalog."default",
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: text character varying COLLATE pg_catalog."default",
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: rechtsstand xplan_gml.xp_rechtsstand,
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: gesetzlichegrundlage xplan_gml.xp_gesetzlichegrundlage,
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: gliederung1 character varying COLLATE pg_catalog."default",
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: gliederung2 character varying COLLATE pg_catalog."default",
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: ebene integer,
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: hatgenerattribut xplan_gml.xp_generattribut[],
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: hoehenangabe xplan_gml.xp_hoehenangabe[],
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: user_id integer,
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: created_at timestamp without time zone NOT NULL DEFAULT now(),
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: updated_at timestamp without time zone NOT NULL DEFAULT now(),
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: konvertierung_id integer,
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: refbegruendunginhalt text COLLATE pg_catalog."default",
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: gehoertzubereich text COLLATE pg_catalog."default",
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: wirddargestelltdurch text COLLATE pg_catalog."default",
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: externereferenz xplan_gml.xp_spezexternereferenz[],
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: startbedingung xplan_gml.xp_wirksamkeitbedingung,
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: endebedingung xplan_gml.xp_wirksamkeitbedingung,
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: rechtscharakter xplan_gml.fp_rechtscharakter NOT NULL,
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: spezifischepraegung xplan_gml.fp_spezifischepraegungtypen,
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: wirdausgeglichendurchspe text COLLATE pg_catalog."default",
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: wirdausgeglichendurchflaeche text COLLATE pg_catalog."default",
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: reftextinhalt text COLLATE pg_catalog."default",
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: "position" geometry(MultiPolygon) NOT NULL,
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: flaechenschluss boolean NOT NULL,
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: aufschrift character varying COLLATE pg_catalog."default",
    -- Inherited from table xplan_gml.fp_flaechenschlussobjekt: vongenehmigungausgenommen boolean
)
INHERITS (xplan_gml.fp_flaechenschlussobjekt);

COMMENT ON TABLE xplan_gml.fp_flaecheohnedarstellung
    IS 'FeatureType: "FP_FlaecheOhneDarstellung". Fläche, für die keine geplante Nutzung angegben werden kann';

-- Index: fp_fp_flaecheohnedarstellung_gist

-- DROP INDEX xplan_gml.fp_fp_flaecheohnedarstellung_gist;

CREATE INDEX fp_flaecheohnedarstellung_gist 
    ON xplan_gml.fp_flaecheohnedarstellung USING gist
    ("position")
    TABLESPACE pg_default;
-- Index: fp_landwirtschaftsflaeche_gml_id

-- DROP INDEX xplan_gml.fp_landwirtschaftsflaeche_gml_id;

CREATE INDEX fp_flaecheohnedarstellung_gml_id 
    ON xplan_gml.fp_flaecheohnedarstellung USING btree
    (gml_id ASC NULLS LAST)
    TABLESPACE pg_default;

INSERT INTO
	xplankonverter.mappingtable_gmlas_to_gml (feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
VALUES
	(true,'fp_flaecheohnedarstellung','ebene','integer','fp_flaecheohnedarstellung','ebene','integer',NULL,NULL,'gmlas.ebene AS ebene'),
	(true,'fp_flaecheohnedarstellung','flaechenschluss','boolean','fp_flaecheohnedarstellung','flaechenschluss','boolean',NULL,NULL,'gmlas.flaechenschluss AS flaechenschluss'),
	(true,'fp_flaecheohnedarstellung','gehoertzubereich_href','character varying','fp_flaecheohnedarstellung','gehoertzubereich','text',NULL,NULL,'CASE WHEN gmlas.gehoertzubereich_href LIKE ''#GML_%'' OR gmlas.gehoertzubereich_href LIKE ''#gml_%'' OR gmlas.gehoertzubereich_href LIKE ''#Gml_%'' THEN substring(gmlas.gehoertzubereich_href, 6) ELSE gmlas.gehoertzubereich_href END AS gehoertzubereich'),
	(true,'fp_flaecheohnedarstellung','gesetzlichegrundlage','character varying','fp_flaecheohnedarstellung','gesetzlichegrundlage','xp_gesetzlichegrundlage','gesetzlichegrundlage_codespace',NULL,'(gmlas.gesetzlichegrundlage_codespace,gmlas.gesetzlichegrundlage,NULL)::xplan_gml.xp_gesetzlichegrundlage AS gesetzlichegrundlage'),
	(true,'fp_flaecheohnedarstellung','gliederung1','character varying','fp_flaecheohnedarstellung','gliederung1','character varying',NULL,NULL,'gmlas.gliederung1 AS gliederung1'),
	(true,'fp_flaecheohnedarstellung','gliederung2','character varying','fp_flaecheohnedarstellung','gliederung2','character varying',NULL,NULL,'gmlas.gliederung2 AS gliederung2'),
	(true,'fp_flaecheohnedarstellung','id','character varying','fp_flaecheohnedarstellung','gml_id','uuid',NULL,NULL,'CASE WHEN gmlas.id LIKE ''GML_%'' OR gmlas.id LIKE ''gml_%'' OR gmlas.id LIKE ''Gml_%'' THEN substring(gmlas.id, 5)::uuid ELSE gmlas.id::uuid END AS gml_id'),
	(true,'fp_flaecheohnedarstellung','position','geometry','fp_flaecheohnedarstellung','position','geometry',NULL,NULL,'gmlas.position AS position'),
	(true,'fp_flaecheohnedarstellung','rechtscharakter','character varying','fp_flaecheohnedarstellung','rechtscharakter','fp_rechtscharakter',NULL,NULL,'gmlas.rechtscharakter::xplan_gml.fp_rechtscharakter AS rechtscharakter'),
	(true,'fp_flaecheohnedarstellung','rechtsstand','character varying','fp_flaecheohnedarstellung','rechtsstand','xp_rechtsstand',NULL,NULL,'gmlas.rechtsstand::xplan_gml.xp_rechtsstand AS rechtsstand'),
	(true,'fp_flaecheohnedarstellung','spezifischepraegung','character varying','fp_flaecheohnedarstellung','spezifischepraegung','fp_spezifischepraegungtypen','spezifischepraegung_codespace',NULL,'(gmlas.spezifischepraegung_codespace,gmlas.spezifischepraegung,NULL)::xplan_gml.fp_spezifischepraegungtypen AS spezifischepraegung'),
	(true,'fp_flaecheohnedarstellung','text','character varying','fp_flaecheohnedarstellung','text','character varying',NULL,NULL,'gmlas.text AS text'),
	(true,'fp_flaecheohnedarstellung','uuid','character varying','fp_flaecheohnedarstellung','uuid','character varying',NULL,NULL,'gmlas.uuid AS uuid'),
	(true,'fp_flaecheohnedarstellung','vongenehmigungausgenommen','boolean','fp_flaecheohnedarstellung','vongenehmigungausgenommen','boolean',NULL,NULL,'gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
	(true,'fp_flaecheohnedarstellung','aufschrift','character varying','fp_flaecheohnedarstellung','aufschrift','character varying',NULL,NULL,'gmlas.aufschrift AS aufschrift'),
	(true,'fp_flaecheohnedarstellung_hoehenangabe_hoehenangabe','ogc_fid','integer','fp_flaecheohnedarstellung','hoehenangabe','xp_hoehenangabe',NULL,'xp_hoehenangabe[]','ARRAY[gmlas.xp_hoehenangabe_abweichenderhoehenbezug,gmlas.xp_hoehenangabe_hoehenbezug::xplan_gml.xp_arthoehenbezung,gmlas.xp_hoehenangabe_bezugspunkt::xplan_gml.xp_arthoehenbezugspunkt,gmlas.xp_hoehenangabe_hmin,gmlas.xp_hoehenangabe_hmax,gmlas.xp_hoehenangabe_hzwingend,gmlas.xp_hoehenangabe_h,gmlas.xp_hoehenangabe_abweichenderbezugspunkt]::xplan_gml.xp_hoehenangabe[] AS hoehenangabe');

INSERT INTO xplan_uml.uml_classes(
	xmi_id, name, visibility, "isSpecification", "isRoot", "isLeaf", "isActive", package_id, model_id, created_at, updated_at, "isAbstract", id, stereotype_id, general_id)
	VALUES
	('EAID_41242DCE_6D5D_3202_C66D_2CD7DC42351C', 'FP_FlaecheOhneDarstellung', 'public', FALSE, FALSE, FALSE, NULL, 26, NULL, now()::timestamp, NULL, FALSE, 416, 'EAID_6FDC92A7_2818_42bf_9B7D_10397E273699' , 'EAID_D2429BDC_A1CE_45eb_816C_D5201B5CED13');

	
INSERT INTO xplan_uml.class_generalizations (xmi_id,name,"isSpecification",package_id,parent_id,child_id,inheritance_order)
VALUES
('EAID_D2429BDC_A1CE_45eb_816C_D5201B5CED13','<undefined>',false,26,'EAID_38AE9E95_37FE_43a0_82EB_421B61CD7E18','EAID_41242DCE_6D5D_3202_C66D_2CD7DC42351C',NULL);


COMMIT;