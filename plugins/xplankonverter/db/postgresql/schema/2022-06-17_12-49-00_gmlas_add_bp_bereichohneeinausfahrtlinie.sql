BEGIN;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_bereichohneeinausfahrtlinie',
'ebene',
'integer',
'bp_bereichohneeinausfahrtlinie',
'ebene',
'integer',
NULL,
NULL,
'gmlas.ebene AS ebene'
FROM xplankonverter.mappingtable_gmlas_to_gml;


INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_bereichohneeinausfahrtlinie',
'gehoertzubereich_href',
'character varying',
'bp_bereichohneeinausfahrtlinie',
'gehoertzubereich',
'text',
NULL,
NULL,
'CASE WHEN gmlas.gehoertzubereich_href LIKE ''#GML_%'' OR gmlas.gehoertzubereich_href LIKE ''#gml_%'' OR gmlas.gehoertzubereich_href LIKE ''#Gml_%'' THEN substring(gmlas.gehoertzubereich_href, 6) ELSE gmlas.gehoertzubereich_href END AS gehoertzubereich'
FROM xplankonverter.mappingtable_gmlas_to_gml;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_bereichohneeinausfahrtlinie',
'gesetzlichegrundlage',
'character varying',
'bp_bereichohneeinausfahrtlinie',
'gesetzlichegrundlage',
'xp_gesetzlichegrundlage',
'gesetzlichegrundlage_codespace',
NULL,
'(gmlas.gesetzlichegrundlage_codespace,gmlas.gesetzlichegrundlage,NULL)::xplan_gml.xp_gesetzlichegrundlage AS gesetzlichegrundlage'
FROM xplankonverter.mappingtable_gmlas_to_gml;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_bereichohneeinausfahrtlinie',
'gliederung1',
'character varying',
'bp_bereichohneeinausfahrtlinie',
'gliederung1',
'character varying',
NULL,
NULL,
'gmlas.gliederung1 AS gliederung1'
FROM xplankonverter.mappingtable_gmlas_to_gml;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_bereichohneeinausfahrtlinie',
'gliederung2',
'character varying',
'bp_bereichohneeinausfahrtlinie',
'gliederung2',
'character varying',
NULL,
NULL,
'gmlas.gliederung2 AS gliederung2'
FROM xplankonverter.mappingtable_gmlas_to_gml;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_bereichohneeinausfahrtlinie',
'id',
'character varying',
'bp_bereichohneeinausfahrtlinie',
'gml_id',
'uuid',
NULL,
NULL,
'CASE WHEN gmlas.id LIKE ''GML_%'' OR gmlas.id LIKE ''gml_%'' OR gmlas.id LIKE ''Gml_%'' THEN substring(gmlas.id, 5)::uuid ELSE gmlas.id::uuid END AS gml_id'
FROM xplankonverter.mappingtable_gmlas_to_gml;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_bereichohneeinausfahrtlinie',
'position',
'geometry',
'bp_bereichohneeinausfahrtlinie',
'position',
'geometry',
NULL,
NULL,
'gmlas.position AS position'
FROM xplankonverter.mappingtable_gmlas_to_gml;


INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_bereichohneeinausfahrtlinie',
'rechtscharakter',
'character varying',
'bp_bereichohneeinausfahrtlinie',
'rechtscharakter',
'bp_rechtscharakter',
NULL,
NULL,
'gmlas.rechtscharakter::xplan_gml.bp_rechtscharakter AS rechtscharakter'
FROM xplankonverter.mappingtable_gmlas_to_gml;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_bereichohneeinausfahrtlinie',
'rechtsstand',
'character varying',
'bp_bereichohneeinausfahrtlinie',
'rechtsstand',
'xp_rechtsstand',
NULL,
NULL,
'gmlas.rechtsstand::xplan_gml.xp_rechtsstand AS rechtsstand'
FROM xplankonverter.mappingtable_gmlas_to_gml;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_bereichohneeinausfahrtlinie',
'text',
'character varying',
'bp_bereichohneeinausfahrtlinie',
'text',
'character varying',
NULL,
NULL,
'gmlas.text AS text'
FROM xplankonverter.mappingtable_gmlas_to_gml;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_bereichohneeinausfahrtlinie',
'typ',
'character varying',
'bp_bereichohneeinausfahrtlinie',
'typ',
'bp_bereichohneeinausfahrttypen',
NULL,
NULL,
'gmlas.typ::xplan_gml.bp_bereichohneeinausfahrttypen AS typ'
FROM xplankonverter.mappingtable_gmlas_to_gml;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_bereichohneeinausfahrtlinie',
'uuid',
'character varying',
'bp_bereichohneeinausfahrtlinie',
'uuid',
'character varying',
NULL,
NULL,
'gmlas.uuid AS uuid'
FROM xplankonverter.mappingtable_gmlas_to_gml;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_einfahrtsbereichlinie_externereferenz_externereferenz',
'ogc_fid',
'integer',
'bp_bereichohneeinausfahrtlinie',
'ogc_fid',
'xp_spezexternereferenz',
NULL,
'xp_spezexternereferenz',
'gmlas.ogc_fid AS ogc_fid'
FROM xplankonverter.mappingtable_gmlas_to_gml;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_einfahrtsbereichlinie_hoehenangabe_hoehenangabe',
'ogc_fid',
'integer',
'bp_bereichohneeinausfahrtlinie',
'hoehenangabe',
'xp_hoehenangabe',
NULL,
'xp_hoehenangabe[]',
'ARRAY[gmlas.xp_hoehenangabe_abweichenderhoehenbezug,gmlas.xp_hoehenangabe_hoehenbezug::xplan_gml.xp_arthoehenbezung,gmlas.xp_hoehenangabe_bezugspunkt::xplan_gml.xp_arthoehenbezugspunkt,gmlas.xp_hoehenangabe_hmin,gmlas.xp_hoehenangabe_hmax,gmlas.xp_hoehenangabe_hzwingend,gmlas.xp_hoehenangabe_h,gmlas.xp_hoehenangabe_abweichenderbezugspunkt]::xplan_gml.xp_hoehenangabe[] AS hoehenangabe'
FROM xplankonverter.mappingtable_gmlas_to_gml;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_einfahrtsbereichlinie_refbegruendunginhalt',
'href',
'character varying',
'bp_bereichohneeinausfahrtlinie',
'refbegruendunginhalt',
'text',
NULL,
NULL,
'gmlas.href AS refbegruendunginhalt'
FROM xplankonverter.mappingtable_gmlas_to_gml;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_einfahrtsbereichlinie_reftextinhalt',
'href',
'character varying',
'bp_bereichohneeinausfahrtlinie',
'reftextinhalt',
'text',
NULL,
NULL,
'gmlas.href AS reftextinhalt'
FROM xplankonverter.mappingtable_gmlas_to_gml;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_einfahrtsbereichlinie_wirdausgeglichendurchabe',
'href',
'character varying',
'bp_bereichohneeinausfahrtlinie',
'wirdausgeglichendurchabe',
'text',
NULL,
NULL,
'gmlas.href AS wirdausgeglichendurchabe'
FROM xplankonverter.mappingtable_gmlas_to_gml;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_einfahrtsbereichlinie_wirdausgeglichendurchflaeche',
'href',
'character varying',
'bp_bereichohneeinausfahrtlinie',
'wirdausgeglichendurchflaeche',
'text',
NULL,
NULL,
'gmlas.href AS wirdausgeglichendurchflaeche'
FROM xplankonverter.mappingtable_gmlas_to_gml;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_einfahrtsbereichlinie_wirdausgeglichendurchmassnahme',
'href',
'character varying',
'bp_bereichohneeinausfahrtlinie',
'wirdausgeglichendurchmassnahme',
'text',
NULL,
NULL,
'gmlas.href AS wirdausgeglichendurchmassnahme'
FROM xplankonverter.mappingtable_gmlas_to_gml;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_einfahrtsbereichlinie_wirdausgeglichendurchspeflaeche',
'href',
'character varying',
'bp_bereichohneeinausfahrtlinie',
'wirdausgeglichendurchspeflaeche',
'text',
NULL,
NULL,
'gmlas.href AS wirdausgeglichendurchspeflaeche'
FROM xplankonverter.mappingtable_gmlas_to_gml;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_einfahrtsbereichlinie_wirdausgeglichendurchspemassnahme',
'href',
'character varying',
'bp_bereichohneeinausfahrtlinie',
'wirdausgeglichendurchspemassnahme',
'text',
NULL,
NULL,
'gmlas.href AS wirdausgeglichendurchspemassnahme'
FROM xplankonverter.mappingtable_gmlas_to_gml;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml 
(id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
SELECT
MAX(id)+1,
TRUE,
'bp_einfahrtsbereichlinie_wirddargestelltdurch',
'href',
'character varying',
'bp_bereichohneeinausfahrtlinie',
'wirddargestelltdurch',
'text',
NULL,
NULL,
'gmlas.href AS wirddargestelltdurch'
FROM xplankonverter.mappingtable_gmlas_to_gml;



COMMIT;