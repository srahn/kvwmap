BEGIN;
	INSERT INTO xplankonverter.mappingtable_gmlas_to_gml (feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
	VALUES
(true,'bp_landwirtschaftsflaeche','ebene','integer','bp_landwirtschaftsflaeche','ebene','integer',NULL,NULL,'gmlas.ebene AS ebene'),
(true,'bp_landwirtschaftsflaeche','flaechenschluss','boolean','bp_landwirtschaftsflaeche','flaechenschluss','boolean',NULL,NULL,'gmlas.flaechenschluss AS flaechenschluss'),
(true,'bp_landwirtschaftsflaeche','flussrichtung','boolean','bp_landwirtschaftsflaeche','flussrichtung','boolean',NULL,NULL,'gmlas.flussrichtung AS flussrichtung'),
(true,'bp_landwirtschaftsflaeche','gehoertzubereich_href','character varying','bp_landwirtschaftsflaeche','gehoertzubereich','text',NULL,NULL,'CASE WHEN gmlas.gehoertzubereich_href LIKE ''#GML_%'' OR gmlas.gehoertzubereich_href LIKE ''#gml_%'' OR gmlas.gehoertzubereich_href LIKE ''#Gml_%'' THEN substring(gmlas.gehoertzubereich_href, 6) ELSE gmlas.gehoertzubereich_href END AS gehoertzubereich'),
(true,'bp_landwirtschaftsflaeche','gesetzlichegrundlage','character varying','bp_landwirtschaftsflaeche','gesetzlichegrundlage','xp_gesetzlichegrundlage',NULL,'gesetzlichegrundlage_codespace','(gmlas.gesetzlichegrundlage_codespace,gmlas.gesetzlichegrundlage,NULL)::xplan_gml.xp_gesetzlichegrundlage AS gesetzlichegrundlage'),
(true,'bp_landwirtschaftsflaeche','gliederung1','character varying','bp_landwirtschaftsflaeche','gliederung1','character varying',NULL,NULL,'gmlas.gliederung1 AS gliederung1'),
(true,'bp_landwirtschaftsflaeche','gliederung2','character varying','bp_landwirtschaftsflaeche','gliederung2','character varying',NULL,NULL,'gmlas.gliederung2 AS gliederung2'),
(true,'bp_landwirtschaftsflaeche','id','character varying','bp_landwirtschaftsflaeche','gml_id','uuid',NULL,NULL,'CASE WHEN gmlas.id LIKE ''GML_%'' OR gmlas.id LIKE ''gml_%'' OR gmlas.id LIKE ''Gml_%'' THEN substring(gmlas.id, 5)::uuid ELSE gmlas.id::uuid END AS gml_id'),
(true,'bp_landwirtschaftsflaeche_hoehenangabe_hoehenangabe','ogc_fid','integer','bp_landwirtschaftsflaeche','hoehenangabe','xp_hoehenangabe',NULL,'xp_hoehenangabe[]','ARRAY[gmlas.xp_hoehenangabe_abweichenderhoehenbezug,gmlas.xp_hoehenangabe_hoehenbezug::xplan_gml.xp_arthoehenbezung,gmlas.xp_hoehenangabe_bezugspunkt::xplan_gml.xp_arthoehenbezugspunkt,gmlas.xp_hoehenangabe_hmin,gmlas.xp_hoehenangabe_hmax,gmlas.xp_hoehenangabe_hzwingend,gmlas.xp_hoehenangabe_h,gmlas.xp_hoehenangabe_abweichenderbezugspunkt]::xplan_gml.xp_hoehenangabe[] AS hoehenangabe'),
(true,'bp_landwirtschaftsflaeche','nordwinkel','double precision','bp_landwirtschaftsflaeche','nordwinkel','double precision',NULL,NULL,'gmlas.nordwinkel AS nordwinkel'),
(true,'bp_landwirtschaftsflaeche_externereferenz_externereferenz','ogc_fid','integer','bp_landwirtschaftsflaeche','ogc_fid','xp_spezexternereferenz',NULL,'xp_spezexternereferenz','gmlas.ogc_fid AS ogc_fid'),
(true,'bp_landwirtschaftsflaeche','position','geometry','bp_landwirtschaftsflaeche','position','geometry',NULL,NULL,'gmlas.position AS position'),
(true,'bp_landwirtschaftsflaeche','rechtscharakter','character varying','bp_landwirtschaftsflaeche','rechtscharakter','bp_rechtscharakter',NULL,NULL,'gmlas.rechtscharakter::xplan_gml.bp_rechtscharakter AS rechtscharakter'),
(true,'bp_landwirtschaftsflaeche','rechtsstand','character varying','bp_landwirtschaftsflaeche','rechtsstand','xp_rechtsstand',NULL,NULL,'gmlas.rechtsstand::xplan_gml.xp_rechtsstand AS rechtsstand'),
(true,'bp_landwirtschaftsflaeche_refbegruendunginhalt','href','character varying','bp_landwirtschaftsflaeche','refbegruendunginhalt','text',NULL,NULL,'gmlas.href AS refbegruendunginhalt'),
(true,'bp_landwirtschaftsflaeche','text','character varying','bp_landwirtschaftsflaeche','text','character varying',NULL,NULL,'gmlas.text AS text'),
(true,'bp_landwirtschaftsflaeche','uuid','character varying','bp_landwirtschaftsflaeche','uuid','character varying',NULL,NULL,'gmlas.uuid AS uuid'),
(true,'bp_landwirtschaftsflaeche_wirdausgeglichendurchabe','href','character varying','bp_landwirtschaftsflaeche','wirdausgeglichendurchabe','text',NULL,NULL,'gmlas.href AS wirdausgeglichendurchabe'),
(true,'bp_landwirtschaftsflaeche','href','character varying','bp_landwirtschaftsflaeche','wirdausgeglichendurchflaeche','text',NULL,NULL,'gmlas.href AS wirdausgeglichendurchflaeche'),
(true,'bp_landwirtschaftsflaeche_wirdausgeglichendurchmassnahme','href','character varying','bp_landwirtschaftsflaeche','wirdausgeglichendurchmassnahme','text',NULL,NULL,'gmlas.href AS wirdausgeglichendurchmassnahme'),
(true,'bp_landwirtschaftsflaeche_wirdausgeglichendurchspeflaeche','href','character varying','bp_landwirtschaftsflaeche','wirdausgeglichendurchspeflaeche','text',NULL,NULL,'gmlas.href AS wirdausgeglichendurchspeflaeche'),
(true,'bp_landwirtschaftsflaeche_wirdausgeglichendurchspemassnahme','href','character varying','bp_landwirtschaftsflaeche','wirdausgeglichendurchspemassnahme','text',NULL,NULL,'gmlas.href AS wirdausgeglichendurchspemassnahme'),
(true,'bp_landwirtschaftsflaeche_wirddargestelltdurch','href','character varying','bp_landwirtschaftsflaeche','wirddargestelltdurch','text',NULL,NULL,'gmlas.href AS wirddargestelltdurch'),
(true,'bp_landwirtschaftsflaeche','zweckbestimmung','ARRAY character varying','bp_landwirtschaftsflaeche','zweckbestimmung','xp_zweckbestimmunglandwirtschaft[]',NULL,NULL,'gmlas.zweckbestimmung::xplan_gml.xp_zweckbestimmunglandwirtschaft[] AS zweckbestimmung'),
(true,'bp_landwirtschaftsflaeche','aufschrift','character varying','bp_landwirtschaftsflaeche','aufschrift','character varying',NULL,NULL,'gmlas.aufschrift AS aufschrift'),
(true,'bp_landwirtschaftsflaeche_reftextinhalt','href','character varying','bp_landwirtschaftsflaeche','reftextinhalt','text',NULL,NULL,'trim(leading ''GML_'' from trim(leading ''Gml_'' from trim(leading ''gml_'' from trim(leading ''#'' from lower(gmlas.href))))) AS reftextinhalt');


COMMIT;
