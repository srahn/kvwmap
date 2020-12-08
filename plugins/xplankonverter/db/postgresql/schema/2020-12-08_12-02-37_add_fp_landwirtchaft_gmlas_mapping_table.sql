BEGIN;
INSERT INTO xplankonverter.mappingtable_gmlas_to_gml (id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
VALUES
(4328,TRUE,'fp_landwirtschaft','ebene','integer','fp_landwirtschaft','ebene','integer',NULL,NULL,'gmlas.ebene AS ebene'),
(4329,TRUE,'fp_landwirtschaft','flaechenschluss','boolean','fp_landwirtschaft','flaechenschluss','boolean',NULL,NULL,'gmlas.flaechenschluss AS flaechenschluss'),
(4330,TRUE,'fp_landwirtschaft','gehoertzubereich_href','character varying','fp_landwirtschaft','gehoertzubereich','text',NULL,NULL,'CASE WHEN gmlas.gehoertzubereich_href LIKE ''#GML_%'' OR gmlas.gehoertzubereich_href LIKE ''#gml_%'' OR gmlas.gehoertzubereich_href LIKE ''#Gml_%'' THEN substring(gmlas.gehoertzubereich_href, 6) ELSE gmlas.gehoertzubereich_href END AS gehoertzubereich'),
(4331,TRUE,'fp_landwirtschaft','gesetzlichegrundlage','character varying','fp_landwirtschaft','gesetzlichegrundlage','xp_gesetzlichegrundlage','gesetzlichegrundlage_codespace',NULL,'(gmlas.gesetzlichegrundlage_codespace,gmlas.gesetzlichegrundlage,NULL)::xplan_gml.xp_gesetzlichegrundlage AS gesetzlichegrundlage'),
(4332,TRUE,'fp_landwirtschaft','gliederung1','character varying','fp_landwirtschaft','gliederung1','character varying',NULL,NULL,'gmlas.gliederung1 AS gliederung1'),
(4333,TRUE,'fp_landwirtschaft','gliederung2','character varying','fp_landwirtschaft','gliederung2','character varying',NULL,NULL,'gmlas.gliederung2 AS gliederung2'),
(4334,TRUE,'fp_landwirtschaft','id','character varying''character varying','fp_landwirtschaft','gml_id','uuid',NULL,NULL,'CASE WHEN gmlas.id LIKE ''GML_%'' OR gmlas.id LIKE ''gml_%'' OR gmlas.id LIKE ''Gml_%'' THEN substring(gmlas.id, 5)::uuid ELSE gmlas.id::uuid END AS gml_id'),
(4335,TRUE,'fp_landwirtschaft','ogc_fid','integer','fp_landwirtschaft','hoehenangabe','xp_hoehenangabe',NULL,'xp_hoehenangabe[]','ARRAY[gmlas.xp_hoehenangabe_abweichenderhoehenbezug,gmlas.xp_hoehenangabe_hoehenbezug::xplan_gml.xp_arthoehenbezung,gmlas.xp_hoehenangabe_bezugspunkt::xplan_gml.xp_arthoehenbezugspunkt,gmlas.xp_hoehenangabe_hmin,gmlas.xp_hoehenangabe_hmax,gmlas.xp_hoehenangabe_hzwingend,gmlas.xp_hoehenangabe_h,gmlas.xp_hoehenangabe_abweichenderbezugspunkt]::xplan_gml.xp_hoehenangabe[] AS hoehenangabe'),
(4336,TRUE,'fp_landwirtschaft','ogc_fid','integer','fp_landwirtschaft','ogc_fid','xp_spezexternereferenz',NULL,'xp_spezexternereferenz','gmlas.ogc_fid AS ogc_fid'),
(4337,TRUE,'fp_landwirtschaft','position','geometry','fp_landwirtschaft','position','geometry',NULL,NULL,'gmlas.position AS position'),
(4338,TRUE,'fp_landwirtschaft','rechtscharakter','character varying','fp_landwirtschaft','rechtscharakter','fp_rechtscharakter',NULL,NULL,'gmlas.rechtscharakter::xplan_gml.fp_rechtscharakter AS rechtscharakter'),
(4339,TRUE,'fp_landwirtschaft','rechtsstand','character varying','fp_landwirtschaft','rechtsstand','xp_rechtsstand',NULL,NULL,'gmlas.rechtsstand::xplan_gml.xp_rechtsstand AS rechtsstand'),
(4340,TRUE,'fp_landwirtschaft','href','character varying','fp_landwirtschaft','refbegruendunginhalt','text',NULL,NULL,'gmlas.href AS refbegruendunginhalt'),
(4341,TRUE,'fp_landwirtschaft','href','character varying','fp_landwirtschaft','reftextinhalt','text',NULL,NULL,'gmlas.href AS reftextinhalt'),
(4342,TRUE,'fp_landwirtschaft','spezifischepraegung','character varying','fp_landwirtschaft','spezifischepraegung','fp_spezifischepraegungtypen','spezifischepraegung_codespace',NULL,'(gmlas.spezifischepraegung_codespace,gmlas.spezifischepraegung,NULL)::xplan_gml.fp_spezifischepraegungtypen AS spezifischepraegung'),
(4343,TRUE,'fp_landwirtschaft','text','character varying','fp_landwirtschaft','text','character varying',NULL,NULL,'gmlas.text AS text'),
(4344,TRUE,'fp_landwirtschaft','uuid','character varying','fp_landwirtschaft','uuid','character varying',NULL,NULL,'gmlas.uuid AS uuid'),
(4345,TRUE,'fp_landwirtschaft','href','character varying','fp_landwirtschaft','wirdausgeglichendurchflaeche','text',NULL,NULL,'gmlas.href AS wirdausgeglichendurchflaeche'),
(4346,TRUE,'fp_landwirtschaft','href','character varying', 'fp_landwirtschaft','wirddargestelltdurch','text',NULL,NULL,'gmlas.href AS wirddargestelltdurch'),
(4347,TRUE,'fp_landwirtschaft','href','character varying','fp_landwirtschaft','wirdausgeglichendurchspe','text',NULL,NULL,'gmlas.href AS wirdausgeglichendurchspe'),
(4348,TRUE,'fp_landwirtschaft','zweckbestimmung','ARRAY character varying','fp_landwirtschaft','zweckbestimmung','xp_zweckbestimmunglandwirtschaft[]',NULL,NULL,'gmlas.zweckbestimmung::xplan_gml.xp_zweckbestimmunglandwirtschaft[] AS zweckbestimmung');
COMMIT;