
-- Layerdump aus kvwmap vom 28.05.2025 13:45:33
-- Achtung: Die Datenbank in die der Dump eingespielt wird, sollte die gleiche Migrationsversion haben,
-- wie die Datenbank aus der exportiert wurde! Anderenfalls kann es zu Fehlern bei der Ausführung des SQL kommen.

DO $$
	DECLARE 
		vars_connection_id integer;
		
		vars_group_id_94 integer;
		
		vars_last_layer_id39 integer;
		vars_last_layer_id40 integer;
		vars_last_layer_id41 integer;
		vars_last_class_id integer;
		vars_last_style_id integer;
		vars_last_label_id integer;
		vars_last_ddl_id integer;
		vars_last_druckfreitexte_id integer;
		vars_last_druckfreilinien_id integer;
		vars_last_druckfreirechtecke_id integer;
	BEGIN
		vars_connection_id := 1;
		

				INSERT INTO kvwmap.u_groups 
					("gruppenname", "gruppenname_low_german", "gruppenname_english", "gruppenname_polish", "gruppenname_vietnamese", "obergruppe", "order", "selectable_for_shared_layers", "icon") 
				VALUES 
					('Fortführungslisten', NULL, NULL, NULL, NULL, NULL, '5', 'f', NULL)RETURNING id INTO vars_group_id_94;

-- Layer 39

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Fortführungsfälle', NULL, NULL, NULL, NULL, '', '5', vars_group_id_94, 'SELECT id AS ff_fall_id, ff_auftrag_id, fortfuehrungsfallnummer, laufendenummer,  ''Fall: '' || fortfuehrungsfallnummer::text || '' altes Flst: '' ||  zeigtaufaltesflurstueck[1] AS fall_beschriftung, ueberschriftimfortfuehrungsnachweis,substring(zeigtaufaltesflurstueck[1], 7, 3)::integer as flur,(select array_to_string(array(select ltrim(substring(alte_flurst, 10, 5), ''0'')||CASE WHEN ltrim(substring(alte_flurst, 16, 3), ''0_'') != '''' THEN ''/''||ltrim(substring(alte_flurst, 16, 3), ''0_'') ELSE '''' END from (select unnest(zeigtaufaltesflurstueck) as alte_flurst from ff_faelle ff2 where ff2.id = ff.id) as foo), '','')) as alte_flurst,(select array_to_string(array(select ltrim(substring(neue_flurst, 10, 5), ''0'')||CASE WHEN ltrim(substring(neue_flurst, 16, 3), ''0_'') != '''' THEN ''/''||ltrim(substring(neue_flurst, 16, 3), ''0_'') ELSE '''' END from (select unnest(zeigtaufneuesflurstueck) as neue_flurst from ff_faelle ff2 where ff2.id = ff.id) as foo ORDER BY neue_flurst), '','')) as neue_flurst,zeigtaufaltesflurstueck, zeigtaufneuesflurstueck, anlassart as anlass_code, anlassart, anlassarten  FROM ff_faelle ff WHERE 1=1 ORDER BY alte_flurst', 'ff_faelle', 'id', NULL, '0', '', 'fortfuehrungslisten', NULL, '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', 'id', NULL, NULL, NULL, '10', 'pixels', NULL, '25833', '', NULL, 't', '1', '100', '100', NULL, '-1', '-1', NULL, '', NULL, 'EPSG:25833', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', '', NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, '2', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id39;

-- Attribut  des Layers 39

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id39, 'alte_flurst', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 39

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id39, 'anlassart', 'anlassart', 'ff_faelle', 'ff', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT code AS value, ''('' || code || '') '' || name as output from fortfuehrungslisten.aa_anlassart order by code', 'Anlassart des Fortführungsfalls', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '12', '1', '0');

-- Attribut  des Layers 39

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id39, 'anlassarten', 'anlassarten', 'ff_faelle', 'ff', '_varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'Anlassarten der Flurücke', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '13', NULL, '0');

-- Attribut  des Layers 39

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id39, 'anlass_code', 'anlassart', 'ff_faelle', 'ff', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'Anlass-Schlüssel', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '11', NULL, '0');

-- Attribut  des Layers 39

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id39, 'fall_beschriftung', ' fortfuehrungsfallnummer::text ', '', '', 'not_saveable', '', '', NULL, NULL, NULL, NULL, '', 'Text', '', 'Beschriftung', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 39

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id39, 'ff_auftrag_id', 'ff_auftrag_id', 'ff_faelle', 'ff', 'int4', '', '', NULL, '0', '32', '0', '', 'Text', '940083,ff_auftrag_id,embedded', 'Auftrag Id', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 39

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id39, 'ff_fall_id', 'id', 'ff_faelle', 'ff', 'int4', '', 'PRIMARY KEY', NULL, '1', '32', '0', '', 'Text', '', 'Fall Id', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 39

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id39, 'flur', 'zeigtaufaltesflurstueck[1],', '', '', 'not_saveable', '', '', NULL, NULL, NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 39

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id39, 'fortfuehrungsfallnummer', 'fortfuehrungsfallnummer', 'ff_faelle', 'ff', 'int4', '', '', NULL, '0', '32', '0', '', 'Text', '', 'Fortführungsfallnummer', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '2', '1', '0');

-- Attribut  des Layers 39

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id39, 'laufendenummer', 'laufendenummer', 'ff_faelle', 'ff', 'bpchar', '', '', NULL, '0', NULL, NULL, '', 'Text', '', 'lfd Nr.', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '3', '1', '0');

-- Attribut  des Layers 39

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id39, 'neue_flurst', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 39

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id39, 'ueberschriftimfortfuehrungsnachweis', 'ueberschriftimfortfuehrungsnachweis', 'ff_faelle', 'ff', 'bpchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'Überschrift im Fortführungsnachweis', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '5', '1', '0');

-- Attribut  des Layers 39

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id39, 'zeigtaufaltesflurstueck', 'zeigtaufaltesflurstueck', 'ff_faelle', 'ff', '_varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'alte Flurstücke', '', '', '', '', 'Beispiel: mit Nenner: 134141008000250001__ oder ohne Nenner: 13414100800025______', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '9', '1', '0');

-- Attribut  des Layers 39

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id39, 'zeigtaufneuesflurstueck', 'zeigtaufneuesflurstueck', 'ff_faelle', 'ff', '_varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'neue Flurstücke', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '10', '1', '0');

-- Layer 40

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Fortführungslisten Gemarkungen', NULL, NULL, NULL, NULL, '', '5', vars_group_id_94, 'SELECT oid, gemeindename, gemkgnr, jahr, '''' as nachweise FROM gemarkungen WHERE 1=1', 'gemarkungen', 'id', NULL, '0', '', 'fortfuehrungslisten', NULL, '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', 'id', NULL, NULL, NULL, '10', 'pixels', NULL, '25833', '', NULL, 't', '1', '100', '100', NULL, '-1', '-1', NULL, '', NULL, 'EPSG:25833', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', '', NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id40;

-- Attribut  des Layers 40

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id40, 'gemeindename', 'gemeindename', 'gemarkungen', 'gemarkungen', 'varchar', '', '', NULL, '1', '80', NULL, '', 'Text', '', 'Gemeindename', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 40

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id40, 'gemkgnr', 'gemkgnr', 'gemarkungen', 'gemarkungen', 'int4', '', '', NULL, '1', '32', '0', '', 'Auswahlfeld', 'SELECT * FROM (
select gemarkung as value, coalesce(gemarkungsname, '''') || '' ('' || gemarkung || '')'' as output from alkis.pp_gemarkung
union
select gemarkung as value, gemarkung || '' ('' || coalesce(gemarkungsname, '''') || '')'' as output from alkis.pp_gemarkung
) gem
ORDER BY output', 'Gemarkung', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 40

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id40, 'jahr', 'jahr', 'gemarkungen', 'gemarkungen', 'int4', '', '', NULL, '1', '32', '0', '', 'Auswahlfeld', 'SELECT jahr as value, jahr AS output FROM (
SELECT extract(year from current_timestamp) - generate_series(0, (extract(year from current_timestamp)-1950)::integer) AS jahr) AS foo', 'Jahr', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '1', NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 40

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id40, 'nachweise', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '41,gemkgnr,jahr,fnnr - antragsnr;embedded', 'Fortführungsnachweise', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 40

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id40, 'oid', 'oid', 'gemarkungen', 'gemarkungen', 'int8', '', '', NULL, '1', '64', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Layer 41

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Fortführungsnachweise', NULL, NULL, NULL, NULL, '', '5', vars_group_id_94, 'SELECT a.gemkgnr, a.jahr, a.lfdnr, gesperrt, a.gemkgnr || lpad(a.lfdnr::text, 3, ''0'') || right(a.jahr::text, 2) AS fnnr, a.antragsnr, an_pruefen, a.bemerkung, a.created_at, a.updated_at, a.user_name, a.auftragsdatei, '''' AS auftragsdatei_einlesen, a.datumderausgabe, a.profilkennung, a.auftragsnummer, a.impliziteloeschungderreservierung, a.verarbeitungsart, a.geometriebehandlung, a.mitTemporaeremArbeitsbereich, a.mitObjektenImFortfuehrungsgebiet, a.mitFortfuehrungsnachweis, coalesce(aa.name, '''') as gebaeude, '''' AS ff_faelle, a.oid, a.id AS ff_auftrag_id FROM ff_auftraege a left join aa_anlassart aa on a.gebaeude = aa.code where 1=1 order by fnnr', 'ff_auftraege', 'id', NULL, '0', '', 'fortfuehrungslisten', NULL, '/var/www/data/nachweise/fortfuehrungsauftraege/', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', 'id', NULL, NULL, NULL, '10', 'pixels', NULL, '25833', '', NULL, 't', '1', '100', '100', NULL, '-1', '-1', NULL, '', NULL, 'EPSG:25833', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', '', NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, '2', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id41;

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'an_pruefen', 'an_pruefen', 'ff_auftraege', 'a', 'bool', '', '', NULL, '0', NULL, NULL, 'SELECT true', 'Checkbox', '', 'prüfen', '', '', '', '', '', 'Auftrag', NULL, '0', '0', NULL, NULL, '-1', NULL, '1', NULL, NULL, NULL, '6', '1', '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'antragsnr', 'antragsnr', 'ff_auftraege', 'a', 'int4', '', '', NULL, '0', '32', '0', '', 'Text', '', 'Antragsnr.', '', '', '', '', '', 'Auftrag', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '5', '1', '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'auftragsdatei', 'auftragsdatei', 'ff_auftraege', 'a', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Dokument', '', 'Auftragsdatei', '', '', '', '', 'NAS-Datei mit Fortführungsauftrag im XML-Format', 'Fortführungsdaten;collapse', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '11', '1', '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'auftragsdatei_einlesen', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=lade_fortfuehrungsfaelle&ff_auftrag_id=$ff_auftrag_id&auftragsdatei=$auftragsdatei;Fortführungsfälle laden;no_new_window;all_not_null', '', '', '', '', '', '', 'Fortführungsdaten;collapse', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '12', '0', '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'auftragsnummer', 'auftragsnummer', 'ff_auftraege', 'a', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Zahl', '', 'Auftragsnummer', '', '', '', '', '', 'Fortführungsdaten;collapse', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '15', '1', '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'bemerkung', 'bemerkung', 'ff_auftraege', 'a', 'text', '', '', NULL, '1', NULL, NULL, '', 'Textfeld', '', 'Bemerkung', '', '', '', '', '', 'Auftrag', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '7', '1', '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'created_at', 'created_at', 'ff_auftraege', 'a', 'date', '', '', NULL, '0', NULL, NULL, 'SELECT (''now''::text)::date', 'Text', '', 'erstellt am:', '', '', '', '', '', 'Auftrag', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'datumderausgabe', 'datumderausgabe', 'ff_auftraege', 'a', 'date', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'Datum der Ausgabe', '', '', '', '', '', 'Fortführungsdaten;collapse', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '13', '1', '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'ff_auftrag_id', 'id', 'ff_auftraege', 'a', 'int4', '', 'PRIMARY KEY', NULL, '1', '32', '0', '', 'Text', '', 'Auftrag Id', '', '', '', '', '', 'Intern;collapse', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '25', '0', '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'ff_faelle', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '39,ff_auftrag_id,fall_beschriftung;embedded', 'Fortführungsfälle', '', '', '', '', '', 'Fortführungsfälle;collapse', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '23', '0', '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'fnnr', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, NULL, '', 'Text', '', 'FN-Nr.', '', '', '', '', '', 'Auftrag', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'gebaeude', 'aa.name,', 'ff_auftraege', 'a', 'not_saveable', '', '', NULL, NULL, NULL, NULL, '', 'Text', '', 'Änderungen am Gebäudebestand', '', '', '', '', '', 'Fortführungsdaten;collapse', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '22', '1', '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'gemkgnr', 'gemkgnr', 'ff_auftraege', 'a', 'int4', '', '', NULL, '0', '32', '0', '', 'Auswahlfeld', 'SELECT * FROM (
select gemarkung as value, coalesce(gemarkungsname, '''') || '' ('' || gemarkung || '')'' as output from alkis.pp_gemarkung
union
select gemarkung as value, gemarkung || '' ('' || coalesce(gemarkungsname, '''') || '')'' as output from alkis.pp_gemarkung
) gem
ORDER BY output', 'Gemarkung', '', '', '', '', '', 'Auftrag', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '0', '1', '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'geometriebehandlung', 'geometriebehandlung', 'ff_auftraege', 'a', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Auswahlfeld', 'select false as value, ''nein'' as output UNION select true as value, ''ja'' as output', 'Geometriebehandlung', '', '', '', '', '', 'Fortführungsdaten;collapse', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '18', NULL, '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'gesperrt', 'gesperrt', 'ff_auftraege', 'a', 'bool', '', '', NULL, '0', NULL, NULL, 'SELECT false', 'Checkbox', '', '', '', '', '', '', '', 'Auftrag', NULL, '0', '0', NULL, NULL, '-1', NULL, '1', NULL, NULL, NULL, '3', '1', '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'impliziteloeschungderreservierung', 'impliziteloeschungderreservierung', 'ff_auftraege', 'a', 'int4', '', '', NULL, '1', '32', '0', '', 'Zahl', '', 'implizite Löschung der Reservierung', '', '', '', '', '', 'Fortführungsdaten;collapse', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '16', NULL, '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'jahr', 'jahr', 'ff_auftraege', 'a', 'int4', '', '', NULL, '0', '32', '0', 'SELECT date_part(''year''::text, now())', 'Auswahlfeld', 'SELECT jahr as value, jahr AS output FROM (
SELECT extract(year from current_timestamp) - generate_series(0, (extract(year from current_timestamp)-1950)::integer) AS jahr) AS foo', 'Fortführungsjahr', '', '', '', '', '', 'Auftrag', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '1', '1', '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'lfdnr', 'lfdnr', 'ff_auftraege', 'a', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', 'LfdNr.', '', '', '', '', 'Die laufende Nummer pro Fortführungsjahr und Gemarkung. Beim neu anlegen eines Nachweises wird diese Nummer automatisch vergeben und muss nicht eingetragen werden.', 'Auftrag', NULL, '0', '0', NULL, NULL, '-1', NULL, '1', NULL, NULL, NULL, '2', '1', '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'mitfortfuehrungsnachweis', 'mitfortfuehrungsnachweis', 'ff_auftraege', 'a', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Auswahlfeld', 'select false as value, ''nein'' as output UNION select true as value, ''ja'' as output', 'mit Fortführungsnachweis', '', '', '', '', '', 'Fortführungsdaten;collapse', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '21', NULL, '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'mitobjektenimfortfuehrungsgebiet', 'mitobjektenimfortfuehrungsgebiet', 'ff_auftraege', 'a', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Auswahlfeld', 'select false as value, ''nein'' as output UNION select true as value, ''ja'' as output', 'mit Objekt im Fortführungsgebiet', '', '', '', '', '', 'Fortführungsdaten;collapse', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '20', NULL, '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'mittemporaeremarbeitsbereich', 'mittemporaeremarbeitsbereich', 'ff_auftraege', 'a', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Auswahlfeld', 'select false as value, ''nein'' as output UNION select true as value, ''ja'' as output', 'mit temporärem Arbeitsbereich', '', '', '', '', '', 'Fortführungsdaten;collapse', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '19', NULL, '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'oid', 'oid', 'ff_auftraege', 'a', '', '', '', NULL, NULL, NULL, NULL, '', 'Text', '', 'OID', '', '', '', '', '', 'Intern;collapse', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '24', '0', '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'profilkennung', 'profilkennung', 'ff_auftraege', 'a', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'Profilkennung', '', '', '', '', '', 'Fortführungsdaten;collapse', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '14', '0', '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'updated_at', 'updated_at', 'ff_auftraege', 'a', 'date', '', '', NULL, '0', NULL, NULL, 'SELECT (''now''::text)::date', 'Time', '', 'geändert am:', '', '', '', '', '', 'Auftrag', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'user_name', 'user_name', 'ff_auftraege', 'a', 'varchar', '', '', NULL, '0', NULL, NULL, '', 'User', '', 'bearbeitet von:', '', '', '', '', '', 'Auftrag', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '10', '0', '0');

-- Attribut  des Layers 41

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id41, 'verarbeitungsart', 'verarbeitungsart', 'ff_auftraege', 'a', 'int4', '', '', NULL, '1', '32', '0', '', 'Zahl', '', 'Verarbeitungsart', '', '', '', '', '', 'Fortführungsdaten;collapse', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '17', NULL, '0');

-- Replace attribute options for Layer 39
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=39', CONCAT('layer_id=', vars_last_layer_id39)) WHERE layer_id IN (vars_last_layer_id39, vars_last_layer_id40, vars_last_layer_id41) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=39( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '39,', CONCAT(vars_last_layer_id39, ',')) WHERE layer_id IN (vars_last_layer_id39, vars_last_layer_id40, vars_last_layer_id41) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '39,%';

-- Replace attribute options for Layer 40
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=40', CONCAT('layer_id=', vars_last_layer_id40)) WHERE layer_id IN (vars_last_layer_id39, vars_last_layer_id40, vars_last_layer_id41) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=40( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '40,', CONCAT(vars_last_layer_id40, ',')) WHERE layer_id IN (vars_last_layer_id39, vars_last_layer_id40, vars_last_layer_id41) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '40,%';

-- Replace attribute options for Layer 41
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=41', CONCAT('layer_id=', vars_last_layer_id41)) WHERE layer_id IN (vars_last_layer_id39, vars_last_layer_id40, vars_last_layer_id41) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=41( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '41,', CONCAT(vars_last_layer_id41, ',')) WHERE layer_id IN (vars_last_layer_id39, vars_last_layer_id40, vars_last_layer_id41) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '41,%';

END $$