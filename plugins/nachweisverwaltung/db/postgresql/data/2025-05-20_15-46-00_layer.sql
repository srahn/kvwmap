
-- Layerdump aus kvwmap vom 28.05.2025 14:20:20
-- Achtung: Die Datenbank in die der Dump eingespielt wird, sollte die gleiche Migrationsversion haben,
-- wie die Datenbank aus der exportiert wurde! Anderenfalls kann es zu Fehlern bei der Ausführung des SQL kommen.

DO $$
	DECLARE 
		vars_connection_id integer;
		
		vars_group_id_97 integer;
		
		vars_last_layer_id5 integer;
		vars_last_layer_id6 integer;
		vars_last_layer_id7 integer;
		vars_last_layer_id8 integer;
		vars_last_layer_id9 integer;
		vars_last_layer_id10 integer;
		vars_last_layer_id11 integer;
		vars_last_layer_id12 integer;
		vars_last_layer_id13 integer;
		vars_last_layer_id14 integer;
		vars_last_layer_id15 integer;
		vars_last_layer_id16 integer;
		vars_last_layer_id17 integer;
		vars_last_layer_id18 integer;
		vars_last_layer_id44 integer;
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
					('Nachweisverwaltung', NULL, NULL, NULL, NULL, NULL, '5', 'f', NULL)RETURNING id INTO vars_group_id_97;

-- Layer 5

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('alkis_aufnahmepunkt', NULL, NULL, NULL, NULL, 'Aufnahmepunkt', '0', vars_group_id_97, 'SELECT * FROM fp_punkte_alkis WHERE par=''AP'' AND endet IS NULL AND endet_punktort IS NULL', 'fp_punkte', 'id', NULL, '0', 'wkb_geometry from (SELECT oid, par, pktnr,  wkb_geometry FROM nachweisverwaltung.fp_punkte_alkis WHERE endet IS NULL AND endet_punktort IS NULL) as foo using unique oid using srid=25833', 'nachweisverwaltung', NULL, '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', 'par', NULL, NULL, NULL, '3', 'pixels', NULL, '25833', 'nachweisverwaltung/view/Festpunkte_alkis.php', NULL, 't', '1', NULL, '520890', NULL, '0', '50001', NULL, '', NULL, 'EPSG:25833', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 't', '', 'Sonstige Vermessungspunkte, abgeleitet aus ALKIS.', NULL, 'Landkreis Rostock, Kataster- und Vermessungsamt', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id5;

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'aam', 'aam', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '28', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'abm', 'abm', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '25', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'advstandardmodell', 'advstandardmodell', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'anlass', 'anlass', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '10', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'art', 'art', 'fp_punkte_alkis', 'fp_punkte_alkis', '_varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '35', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'beginnt', 'beginnt', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'beginnt_punktort', 'beginnt_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'beziehtsichauf', 'beziehtsichauf', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '46', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'bpn', 'bpn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '27', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'bza', 'bza', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '29', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'datei', 'datei', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '48', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'des', 'des', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '33', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'endet', 'endet', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'endet_punktort', 'endet_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'fdv', 'fdv', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '43', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'fgp', 'fgp', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '26', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'gehoertzu', 'gehoertzu', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '47', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'gml_id', 'gml_id', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'gml_id_punktort', 'gml_id_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'gst', 'gst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '39', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'hat', 'hat', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '45', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'hin', 'hin', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '42', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'hoe', 'hoe', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '9', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '17', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'hop', 'hop', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '9', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '18', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'hw', 'hw', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '11', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '16', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'identifier', 'identifier', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'idn', 'idn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '36', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'kds', 'kds', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '32', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'kst', 'kst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '40', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'land', 'land', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '11', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'lzk', 'lzk', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '38', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'nam', 'nam', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '34', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'ogc_fid', 'ogc_fid', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'par', 'par', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '4', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '19', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'pkn', 'pkn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '13', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'pktnr', 'pktnr', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '6', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '14', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'pru', 'pru', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '41', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'rho', 'rho', 'fp_punkte_alkis', 'fp_punkte_alkis', 'float8', '', '', NULL, '1', '53', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '31', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'rw', 'rw', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '11', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '15', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'soe', 'soe', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '20', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'soe_alk_par', 'soe_alk_par', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '22', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'soe_alk_pkz', 'soe_alk_pkz', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '21', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'soe_alk_vma', 'soe_alk_vma', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '23', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'soe_weitere', 'soe_weitere', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '24', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'sonstigesmodell', 'sonstigesmodell', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'vwl', 'vwl', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '37', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'wkb_geometry', 'wkb_geometry', 'fp_punkte_alkis', 'fp_punkte_alkis', 'geometry', 'POINT', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '49', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'zde', 'zde', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '30', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'zeigtauf', 'zeigtauf', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '44', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'zst', 'zst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '12', '0', '0');

-- Class 3 des Layers 5

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('Aufnahmepunkt', vars_last_layer_id5, '/^AP/', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 3 der Class 3

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'circle', '10', '255 255 255', '0 0 0', NULL, NULL, NULL, NULL, '5.00', '10.00', NULL, NULL, '0', '', '1.00', '1.00', '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 3 zu Class 3
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Layer 6

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('alkis_aufnahmepunktnr', NULL, NULL, NULL, NULL, 'Punktnummer AP', '4', vars_group_id_97, '', 'fp_punkte_ap', 'id', NULL, '0', 'wkb_geometry from (SELECT oid, par, pktnr, wkb_geometry FROM nachweisverwaltung.fp_punkte_alkis WHERE endet IS NULL AND endet_punktort IS NULL) as foo using unique oid using srid=25833', 'nachweisverwaltung', NULL, '', NULL, NULL, '', '', '', 'pktnr', '50001', '50', '', '0', '', vars_connection_id, '', '6', 'par', NULL, NULL, NULL, '3', 'pixels', NULL, '25833', '', NULL, 'f', '1', NULL, '520880', NULL, '0', '50001', NULL, '', NULL, 'EPSG:25833', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', 'Punktnummern der Aufnahmepunkte, abgeleitet aus ALKIS', NULL, 'Landkreis Rostock, Kataster- und Vermessungsamt', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id6;

-- Class 4 des Layers 6

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('', vars_last_layer_id6, '/^AP/', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Label 2 der Class 4

				INSERT INTO kvwmap.labels 
					("font", "type", "color", "outlinecolor", "shadowcolor", "shadowsizex", "shadowsizey", "backgroundcolor", "backgroundshadowcolor", "backgroundshadowsizex", "backgroundshadowsizey", "size", "minsize", "maxsize", "position", "offsetx", "offsety", "angle", "anglemode", "buffer", "minfeaturesize", "maxfeaturesize", "partials", "wrap", "the_force") 
				VALUES 
					('arial_underline_1', NULL, '0 50 150', '255 255 255', '', NULL, NULL, '', '', NULL, NULL, '8', '5', '10', '9', '4.0000', '-2.0000', '', NULL, NULL, NULL, NULL, NULL, NULL, '1')RETURNING label_id INTO vars_last_label_id;
-- Zuordnung Label 2 zu Class 4
INSERT INTO kvwmap.u_labels2classes (label_id, class_id) VALUES (vars_last_label_id, vars_last_class_id);

-- Layer 7

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('alkis_festpunkt', NULL, NULL, NULL, NULL, 'Festpunkte', '0', vars_group_id_97, 'SELECT * FROM fp_punkte_alkis WHERE (par IN (''TP'',''OP'') AND endet IS NULL AND endet_punktort IS NULL)', 'fp_punkte_alkis', 'id', NULL, '0', 'wkb_geometry from (SELECT oid, par, pktnr, wkb_geometry  FROM nachweisverwaltung.fp_punkte_alkis WHERE endet IS NULL AND endet_punktort IS NULL) as foo using unique oid using srid=25833', 'nachweisverwaltung', NULL, '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', 'par', NULL, NULL, NULL, '3', 'pixels', NULL, '25833', 'nachweisverwaltung/view/Festpunkte_alkis.php', NULL, 't', '1', NULL, '520990', NULL, '0', '100001', NULL, '', NULL, 'EPSG:25833', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', 'Benutzungsfestpunktfeld, abgeleitet aus Datenabgabe vom AfGVK', NULL, 'Landkreis Rostock, Kataster- und Vermessungsamt', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id7;

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'aam', 'aam', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '28', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'abm', 'abm', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '25', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'advstandardmodell', 'advstandardmodell', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'anlass', 'anlass', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '10', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'art', 'art', 'fp_punkte_alkis', 'fp_punkte_alkis', '_varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '35', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'beginnt', 'beginnt', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'beginnt_punktort', 'beginnt_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'beziehtsichauf', 'beziehtsichauf', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '46', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'bpn', 'bpn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '27', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'bza', 'bza', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '29', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'datei', 'datei', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '48', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'des', 'des', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '33', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'endet', 'endet', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'endet_punktort', 'endet_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'fdv', 'fdv', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '43', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'fgp', 'fgp', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '26', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'gehoertzu', 'gehoertzu', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '47', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'gml_id', 'gml_id', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'gml_id_punktort', 'gml_id_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'gst', 'gst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '39', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'hat', 'hat', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '45', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'hin', 'hin', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '42', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'hoe', 'hoe', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '9', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '17', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'hop', 'hop', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '9', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '18', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'hw', 'hw', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '11', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '16', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'identifier', 'identifier', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'idn', 'idn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '36', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'kds', 'kds', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '32', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'kst', 'kst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '40', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'land', 'land', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '11', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'lzk', 'lzk', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '38', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'nam', 'nam', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '34', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'ogc_fid', 'ogc_fid', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', 'PRIMARY KEY', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'par', 'par', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '4', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '19', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'pkn', 'pkn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '13', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'pktnr', 'pktnr', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '6', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '14', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'pru', 'pru', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '41', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'rho', 'rho', 'fp_punkte_alkis', 'fp_punkte_alkis', 'float8', '', '', NULL, '1', '53', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '31', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'rw', 'rw', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '11', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '15', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'soe', 'soe', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '20', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'soe_alk_par', 'soe_alk_par', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '22', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'soe_alk_pkz', 'soe_alk_pkz', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '21', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'soe_alk_vma', 'soe_alk_vma', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '23', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'soe_weitere', 'soe_weitere', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '24', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'sonstigesmodell', 'sonstigesmodell', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'vwl', 'vwl', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '37', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'wkb_geometry', 'wkb_geometry', 'fp_punkte_alkis', 'fp_punkte_alkis', 'geometry', 'POINT', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '49', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'zde', 'zde', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '30', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'zeigtauf', 'zeigtauf', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '44', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'zst', 'zst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '12', '0', '0');

-- Class 5 des Layers 7

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('TP', vars_last_layer_id7, '/^TP/', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 4 der Class 5

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'glseitstehdreieck', '10', '255 255 255', '0 50 150', NULL, NULL, NULL, NULL, '5.00', '10.00', NULL, NULL, '0', '', '1.00', '1.00', '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 4 zu Class 5
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Style 5 der Class 5

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'circle', '1', '0 0 0', '0 50 150', NULL, NULL, NULL, NULL, '1.00', '2.00', NULL, NULL, '0', '', '1.00', '1.00', '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 5 zu Class 5
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 1);

-- Class 6 des Layers 7

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('OP', vars_last_layer_id7, '/^OP/', '10', '')RETURNING class_id INTO vars_last_class_id;

-- Style 6 der Class 6

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'glseitkopfdreieck', '10', '255 255 255', '0 50 150', NULL, NULL, NULL, NULL, '5.00', '10.00', NULL, NULL, '0', '', '1.00', '1.00', '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 6 zu Class 6
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Style 7 der Class 6

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'circle', '1', '0 0 0', '0 50 150', NULL, NULL, NULL, NULL, '1.00', '2.00', NULL, NULL, '0', '', '1.00', '1.00', '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 7 zu Class 6
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 1);

-- Layer 8

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('alkis_festpunktnr', NULL, NULL, NULL, NULL, 'Punktnummer FP', '4', vars_group_id_97, '', 'fp_punkte_alkis', 'id', NULL, '0', 'wkb_geometry from (SELECT oid, par, pktnr, wkb_geometry FROM nachweisverwaltung.fp_punkte_alkis WHERE endet IS NULL AND endet_punktort IS NULL) as foo using unique oid using srid=25833', 'nachweisverwaltung', NULL, '', NULL, NULL, '', '', '', 'pktnr', '50001', '50', '', '0', '', vars_connection_id, '', '6', 'par', NULL, NULL, NULL, '3', 'pixels', NULL, '25833', '', NULL, 'f', '1', NULL, '520980', NULL, '0', '50001', NULL, '', NULL, 'EPSG:25833', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', 'Punktummern des Benutzungsfestpunktfeldes, abgeleitet aus Datenabgabe vom AfGVK', NULL, 'Landkreis Rostock, Kataster- und Vermessungsamt', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id8;

-- Class 7 des Layers 8

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('', vars_last_layer_id8, '/^OP/', '0', '')RETURNING class_id INTO vars_last_class_id;

-- Label 3 der Class 7

				INSERT INTO kvwmap.labels 
					("font", "type", "color", "outlinecolor", "shadowcolor", "shadowsizex", "shadowsizey", "backgroundcolor", "backgroundshadowcolor", "backgroundshadowsizex", "backgroundshadowsizey", "size", "minsize", "maxsize", "position", "offsetx", "offsety", "angle", "anglemode", "buffer", "minfeaturesize", "maxfeaturesize", "partials", "wrap", "the_force") 
				VALUES 
					('arial_underline_2', NULL, '0 50 150', '255 255 255', '', NULL, NULL, '', '', NULL, NULL, '8', '7', '10', '9', '5.0000', '-3.0000', '', NULL, NULL, NULL, NULL, NULL, NULL, '1')RETURNING label_id INTO vars_last_label_id;
-- Zuordnung Label 3 zu Class 7
INSERT INTO kvwmap.u_labels2classes (label_id, class_id) VALUES (vars_last_label_id, vars_last_class_id);

-- Class 8 des Layers 8

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('', vars_last_layer_id8, '/^TP/', '0', '')RETURNING class_id INTO vars_last_class_id;

-- Label 4 der Class 8

				INSERT INTO kvwmap.labels 
					("font", "type", "color", "outlinecolor", "shadowcolor", "shadowsizex", "shadowsizey", "backgroundcolor", "backgroundshadowcolor", "backgroundshadowsizex", "backgroundshadowsizey", "size", "minsize", "maxsize", "position", "offsetx", "offsety", "angle", "anglemode", "buffer", "minfeaturesize", "maxfeaturesize", "partials", "wrap", "the_force") 
				VALUES 
					('arial_underline_2', NULL, '0 50 150', '255 255 255', '', NULL, NULL, '', '', NULL, NULL, '8', '7', '10', '9', '5.0000', '-3.0000', '', NULL, NULL, NULL, NULL, NULL, NULL, '1')RETURNING label_id INTO vars_last_label_id;
-- Zuordnung Label 4 zu Class 8
INSERT INTO kvwmap.u_labels2classes (label_id, class_id) VALUES (vars_last_label_id, vars_last_class_id);

-- Layer 9

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('alkis_gebaeudepunkte', NULL, NULL, NULL, NULL, 'Gebäude-/Bauwerkspunkt', '0', vars_group_id_97, 'SELECT * FROM fp_punkte_alkis WHERE (par = ''GebP'' OR par = ''BwP'') AND (endet is null AND endet_punktort IS NULL)', 'fp_punkte_alkis', 'id', NULL, '0', 'wkb_geometry from (SELECT oid, pktnr, par, wkb_geometry FROM nachweisverwaltung.fp_punkte_alkis WHERE (par = ''GebP'' OR par = ''BwP'') AND (endet is null AND endet_punktort IS NULL)) as foo using unique oid using srid=25833', 'nachweisverwaltung', NULL, '', NULL, NULL, '', '', '', 'pktnr', '25001', '50', '', '0', '', vars_connection_id, '', '6', 'pktnr', NULL, NULL, NULL, '3', 'pixels', NULL, '25833', 'nachweisverwaltung/view/Festpunkte_alkis.php', NULL, 't', '1', NULL, '520690', NULL, '50', '5001', NULL, '', NULL, 'EPSG:25833', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', 'Anzeige der Gebäudepunkte aus ALKIS.', NULL, 'Landkreis Rostock, Kataster- und Vermessungsamt', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id9;

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'aam', 'aam', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '28', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'abm', 'abm', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '25', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'advstandardmodell', 'advstandardmodell', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'anlass', 'anlass', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '10', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'art', 'art', 'fp_punkte_alkis', 'fp_punkte_alkis', '_varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '35', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'beginnt', 'beginnt', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'beginnt_punktort', 'beginnt_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'beziehtsichauf', 'beziehtsichauf', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '46', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'bpn', 'bpn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '27', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'bza', 'bza', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '29', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'datei', 'datei', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '48', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'des', 'des', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '33', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'endet', 'endet', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'endet_punktort', 'endet_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'fdv', 'fdv', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '43', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'fgp', 'fgp', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '26', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'gehoertzu', 'gehoertzu', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '47', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'gml_id', 'gml_id', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'gml_id_punktort', 'gml_id_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'gst', 'gst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '39', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'hat', 'hat', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '45', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'hin', 'hin', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '42', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'hoe', 'hoe', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '9', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '17', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'hop', 'hop', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '9', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '18', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'hw', 'hw', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '11', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '16', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'identifier', 'identifier', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'idn', 'idn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '36', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'kds', 'kds', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '32', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'kst', 'kst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '40', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'land', 'land', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '11', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'lzk', 'lzk', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '38', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'nam', 'nam', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '34', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'ogc_fid', 'ogc_fid', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', 'PRIMARY KEY', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'par', 'par', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '4', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '19', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'pkn', 'pkn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '13', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'pktnr', 'pktnr', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '6', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '14', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'pru', 'pru', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '41', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'rho', 'rho', 'fp_punkte_alkis', 'fp_punkte_alkis', 'float8', '', '', NULL, '1', '53', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '31', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'rw', 'rw', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '11', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '15', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'soe', 'soe', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '20', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'soe_alk_par', 'soe_alk_par', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '22', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'soe_alk_pkz', 'soe_alk_pkz', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '21', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'soe_alk_vma', 'soe_alk_vma', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '23', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'soe_weitere', 'soe_weitere', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '24', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'sonstigesmodell', 'sonstigesmodell', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'vwl', 'vwl', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '37', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'wkb_geometry', 'wkb_geometry', 'fp_punkte_alkis', 'fp_punkte_alkis', 'geometry', 'POINT', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '49', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'zde', 'zde', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '30', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'zeigtauf', 'zeigtauf', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '44', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'zst', 'zst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '12', '0', '0');

-- Class 9 des Layers 9

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('erfasst', vars_last_layer_id9, '/./', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 8 der Class 9

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'circle', '5', '128 255 128', '0 0 0', NULL, NULL, NULL, NULL, '4.00', '6.00', NULL, NULL, '0', '', '1.00', '1.00', '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 8 zu Class 9
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Class 10 des Layers 9

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('digitalisiert', vars_last_layer_id9, '', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 9 der Class 10

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'circle', '5', '255 128 255', '0 0 0', NULL, NULL, NULL, NULL, '4.00', '6.00', NULL, NULL, '0', '', '1.00', '1.00', '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 9 zu Class 10
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Layer 10

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('alkis_gebaeudepunktnr', NULL, NULL, NULL, NULL, 'Punktnummer BG/BW', '4', vars_group_id_97, 'SELECT * FROM fp_punkte_alkis WHERE (par = ''GebP'' OR par = ''BwP'') AND (endet IS NULL AND endet_punktort IS NULL)', 'fp_punkte_alkis', 'id', NULL, '0', 'wkb_geometry from (SELECT oid, pktnr, par, wkb_geometry FROM nachweisverwaltung.fp_punkte_alkis WHERE (par = ''GebP'' OR par =  ''BwP'') AND( endet IS NULL AND endet_punktort IS NULL)) as foo using unique oid using srid=25833', 'nachweisverwaltung', NULL, '', NULL, NULL, '', '', '', 'pktnr', '5001', '50', '', '0', '', vars_connection_id, '', '6', 'par', NULL, NULL, NULL, '3', 'pixels', NULL, '25833', '', NULL, 'f', '1', NULL, '520680', NULL, '0', '5001', NULL, '', NULL, 'EPSG:25833', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', 'Anzeige der Gebäudepunkte aus ALKIS.', NULL, 'Landkreis Rostock, Kataster- und Vermessungsamt', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id10;

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'aam', 'aam', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '28', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'abm', 'abm', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '25', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'advstandardmodell', 'advstandardmodell', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'anlass', 'anlass', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '10', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'art', 'art', 'fp_punkte_alkis', 'fp_punkte_alkis', '_varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '35', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'beginnt', 'beginnt', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'beginnt_punktort', 'beginnt_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'beziehtsichauf', 'beziehtsichauf', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '46', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'bpn', 'bpn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '27', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'bza', 'bza', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '29', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'datei', 'datei', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '48', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'des', 'des', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '33', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'endet', 'endet', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'endet_punktort', 'endet_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'fdv', 'fdv', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '43', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'fgp', 'fgp', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '26', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'gehoertzu', 'gehoertzu', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '47', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'gml_id', 'gml_id', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'gml_id_punktort', 'gml_id_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'gst', 'gst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '39', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'hat', 'hat', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '45', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'hin', 'hin', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '42', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'hoe', 'hoe', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '9', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '17', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'hop', 'hop', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '9', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '18', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'hw', 'hw', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '11', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '16', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'identifier', 'identifier', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'idn', 'idn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '36', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'kds', 'kds', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '32', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'kst', 'kst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '40', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'land', 'land', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '11', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'lzk', 'lzk', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '38', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'nam', 'nam', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '34', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'ogc_fid', 'ogc_fid', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', 'PRIMARY KEY', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'par', 'par', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '4', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '19', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'pkn', 'pkn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '13', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'pktnr', 'pktnr', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '6', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '14', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'pru', 'pru', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '41', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'rho', 'rho', 'fp_punkte_alkis', 'fp_punkte_alkis', 'float8', '', '', NULL, '1', '53', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '31', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'rw', 'rw', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '11', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '15', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'soe', 'soe', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '20', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'soe_alk_par', 'soe_alk_par', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '22', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'soe_alk_pkz', 'soe_alk_pkz', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '21', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'soe_alk_vma', 'soe_alk_vma', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '23', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'soe_weitere', 'soe_weitere', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '24', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'sonstigesmodell', 'sonstigesmodell', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'vwl', 'vwl', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '37', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'wkb_geometry', 'wkb_geometry', 'fp_punkte_alkis', 'fp_punkte_alkis', 'geometry', 'POINT', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '49', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'zde', 'zde', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '30', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'zeigtauf', 'zeigtauf', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '44', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'zst', 'zst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '12', '0', '0');

-- Class 11 des Layers 10

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('', vars_last_layer_id10, '', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Label 5 der Class 11

				INSERT INTO kvwmap.labels 
					("font", "type", "color", "outlinecolor", "shadowcolor", "shadowsizex", "shadowsizey", "backgroundcolor", "backgroundshadowcolor", "backgroundshadowsizex", "backgroundshadowsizey", "size", "minsize", "maxsize", "position", "offsetx", "offsety", "angle", "anglemode", "buffer", "minfeaturesize", "maxfeaturesize", "partials", "wrap", "the_force") 
				VALUES 
					('arial', NULL, '20 20 20', '255 255 255', '', NULL, NULL, '', '', NULL, NULL, '6', '4', '8', '4', '4.0000', '-2.0000', '', NULL, NULL, NULL, NULL, NULL, NULL, '1')RETURNING label_id INTO vars_last_label_id;
-- Zuordnung Label 5 zu Class 11
INSERT INTO kvwmap.u_labels2classes (label_id, class_id) VALUES (vars_last_label_id, vars_last_class_id);

-- Layer 11

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('alkis_grenzpktnr', NULL, NULL, NULL, NULL, 'Punktnummer GP', '4', vars_group_id_97, 'SELECT * FROM fp_punkte_alkis WHERE (par = ''GP'' and endet is null AND endet_punktort IS NULL)', 'fp_punkte_alkis', 'id', NULL, '0', 'wkb_geometry from (SELECT oid, par, pktnr, fgp, wkb_geometry FROM nachweisverwaltung.fp_punkte_alkis WHERE par = ''GP'' AND endet is null AND endet_punktort IS NULL) as foo using unique oid using srid=25833', 'nachweisverwaltung', NULL, '', NULL, NULL, '', '', '', 'pktnr', '5001', '50', '', '0', '', vars_connection_id, '', '6', 'fgp', NULL, NULL, NULL, '3', 'pixels', NULL, '25833', 'nachweisverwaltung/view/Festpunkte_alkis.php', NULL, 'f', '1', NULL, '520780', NULL, '1', '30005', NULL, '255 255 255', NULL, 'EPSG:2398', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', 'Qualität der Grenzpunkte', NULL, 'Landkreis Rostock, Vermessungs- und Katasteramt', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id11;

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'aam', 'aam', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '28', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'abm', 'abm', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '25', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'advstandardmodell', 'advstandardmodell', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'anlass', 'anlass', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '10', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'art', 'art', 'fp_punkte_alkis', 'fp_punkte_alkis', '_varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '35', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'beginnt', 'beginnt', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'beginnt_punktort', 'beginnt_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'beziehtsichauf', 'beziehtsichauf', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '46', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'bpn', 'bpn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '27', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'bza', 'bza', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '29', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'datei', 'datei', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '48', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'des', 'des', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '33', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'endet', 'endet', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'endet_punktort', 'endet_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'fdv', 'fdv', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '43', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'fgp', 'fgp', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '26', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'gehoertzu', 'gehoertzu', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '47', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'gml_id', 'gml_id', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'gml_id_punktort', 'gml_id_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'gst', 'gst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '39', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'hat', 'hat', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '45', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'hin', 'hin', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '42', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'hoe', 'hoe', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '9', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '17', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'hop', 'hop', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '9', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '18', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'hw', 'hw', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '11', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '16', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'identifier', 'identifier', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'idn', 'idn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '36', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'kds', 'kds', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '32', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'kst', 'kst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '40', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'land', 'land', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '11', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'lzk', 'lzk', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '38', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'nam', 'nam', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '34', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'ogc_fid', 'ogc_fid', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', 'PRIMARY KEY', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'par', 'par', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '4', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '19', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'pkn', 'pkn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '13', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'pktnr', 'pktnr', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '6', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '14', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'pru', 'pru', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '41', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'rho', 'rho', 'fp_punkte_alkis', 'fp_punkte_alkis', 'float8', '', '', NULL, '1', '53', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '31', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'rw', 'rw', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '11', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '15', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'soe', 'soe', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '20', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'soe_alk_par', 'soe_alk_par', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '22', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'soe_alk_pkz', 'soe_alk_pkz', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '21', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'soe_alk_vma', 'soe_alk_vma', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '23', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'soe_weitere', 'soe_weitere', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '24', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'sonstigesmodell', 'sonstigesmodell', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'vwl', 'vwl', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '37', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'wkb_geometry', 'wkb_geometry', 'fp_punkte_alkis', 'fp_punkte_alkis', 'geometry', 'POINT', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '49', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'zde', 'zde', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '30', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'zeigtauf', 'zeigtauf', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '44', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'zst', 'zst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '12', '0', '0');

-- Class 12 des Layers 11

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('', vars_last_layer_id11, '', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Label 6 der Class 12

				INSERT INTO kvwmap.labels 
					("font", "type", "color", "outlinecolor", "shadowcolor", "shadowsizex", "shadowsizey", "backgroundcolor", "backgroundshadowcolor", "backgroundshadowsizex", "backgroundshadowsizey", "size", "minsize", "maxsize", "position", "offsetx", "offsety", "angle", "anglemode", "buffer", "minfeaturesize", "maxfeaturesize", "partials", "wrap", "the_force") 
				VALUES 
					('arial', NULL, '8 16 0 ', '255 255 255', '', NULL, NULL, '255 255 255', '', NULL, NULL, '8', '5', '8', '4', '4.0000', '-2.0000', '', NULL, NULL, NULL, NULL, '1', NULL, '1')RETURNING label_id INTO vars_last_label_id;
-- Zuordnung Label 6 zu Class 12
INSERT INTO kvwmap.u_labels2classes (label_id, class_id) VALUES (vars_last_label_id, vars_last_class_id);

-- Layer 12

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('alkis_grenzpunkte', NULL, NULL, NULL, NULL, 'Grenzpunkte', '0', vars_group_id_97, 'SELECT * FROM fp_punkte_alkis WHERE (par = ''GP'' AND endet IS NULL AND endet_punktort IS NULL)', 'fp_punkte_alkis', 'id', NULL, '0', 'wkb_geometry from (SELECT oid, par, pktnr, fgp, wkb_geometry FROM nachweisverwaltung.fp_punkte_alkis WHERE par = ''GP'' AND endet IS NULL AND endet_punktort IS NULL AND endet_punktort IS NULL) as foo using unique oid using srid=25833', 'nachweisverwaltung', NULL, '', NULL, NULL, '', '', '', 'pktnr', '5001', '50', '', '0', '', vars_connection_id, '', '6', 'fgp', NULL, NULL, NULL, '3', 'pixels', NULL, '25833', 'generic_layer_editor.php', NULL, 't', '1', NULL, '520790', NULL, '1', '30005', NULL, '255 255 255', NULL, 'EPSG:25833', 'alkis_grenzpunkte', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', 'Qualität der Grenzpunkte', NULL, 'Landkreis Rostock, Vermessungs- und Katasteramt', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id12;

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'aam', 'aam', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '28', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'abm', 'abm', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '25', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'advstandardmodell', 'advstandardmodell', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'anlass', 'anlass', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '10', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'art', 'art', 'fp_punkte_alkis', 'fp_punkte_alkis', '_varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '35', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'beginnt', 'beginnt', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'beginnt_punktort', 'beginnt_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'beziehtsichauf', 'beziehtsichauf', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '46', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'bpn', 'bpn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'BPN', '', '', '', '', 'besondere Punktnummer', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '27', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'bza', 'bza', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', 'BZA', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '29', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'datei', 'datei', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '48', NULL, '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'des', 'des', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', 'DES', '', '', '', '', 'Description', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '33', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'endet', 'endet', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'endet_punktort', 'endet_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'fdv', 'fdv', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '43', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'fgp', 'fgp', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'FGP', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '26', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'gehoertzu', 'gehoertzu', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '47', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'gml_id', 'gml_id', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'gml_id_punktort', 'gml_id_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'gst', 'gst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', 'GST', '', '', '', '', 'Genauigkeitsstufe', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '39', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'hat', 'hat', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '45', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'hin', 'hin', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'HIN', '', '', '', '', 'Hinweise', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '42', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'hoe', 'hoe', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '9', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '17', NULL, '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'hop', 'hop', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '9', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '18', NULL, '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'hw', 'hw', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '11', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '16', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'identifier', 'identifier', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'idn', 'idn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '36', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'kds', 'kds', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '32', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'kst', 'kst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', 'KST', '', '', '', '', 'Koordinatenstatus', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '40', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'land', 'land', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '11', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'lzk', 'lzk', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '38', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'nam', 'nam', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '34', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'ogc_fid', 'ogc_fid', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', 'PRIMARY KEY', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'par', 'par', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '4', NULL, '', 'Text', '', 'par', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '19', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'pkn', 'pkn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '13', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'pktnr', 'pktnr', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '6', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '14', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'pru', 'pru', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '41', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'rho', 'rho', 'fp_punkte_alkis', 'fp_punkte_alkis', 'float8', '', '', NULL, '1', '53', NULL, '', 'Text', '', 'RHO', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '31', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'rw', 'rw', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '11', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '15', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'soe', 'soe', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'SOE', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '20', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'soe_alk_par', 'soe_alk_par', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '22', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'soe_alk_pkz', 'soe_alk_pkz', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '21', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'soe_alk_vma', 'soe_alk_vma', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '23', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'soe_weitere', 'soe_weitere', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '24', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'sonstigesmodell', 'sonstigesmodell', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'vwl', 'vwl', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', 'VWL', '', '', '', '', 'Vertrauenswürdigkeit', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '37', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'wkb_geometry', 'wkb_geometry', 'fp_punkte_alkis', 'fp_punkte_alkis', 'geometry', 'POINT', '', NULL, '1', NULL, NULL, '', 'Geometrie', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '49', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'zde', 'zde', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'ZDE', '', '', '', '', 'Zeitpunkt der Entstehung', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '30', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'zeigtauf', 'zeigtauf', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '44', '0', '0');

-- Attribut  des Layers 12

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id12, 'zst', 'zst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', 'ZST', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '12', '0', '0');

-- Class 13 des Layers 12

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('nicht festgestellt', vars_last_layer_id12, '', '2', '')RETURNING class_id INTO vars_last_class_id;

-- Style 10 der Class 13

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'circle', '5', '255 255 255', '255 255 255', NULL, NULL, NULL, NULL, '3.00', '7.00', NULL, NULL, '0', '', '1.00', '1.00', '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 10 zu Class 13
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Style 11 der Class 13

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'circle', '2', '0 0 0', '0 0 0', NULL, NULL, NULL, NULL, '1.00', '2.00', NULL, NULL, '0', '', '1.00', '1.00', '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 11 zu Class 13
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 1);

-- Class 14 des Layers 12

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('festgestellt', vars_last_layer_id12, '/true/', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 12 der Class 14

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'circle', '8', '255 255 255', '0 0 0', NULL, NULL, NULL, NULL, '4.00', '8.00', NULL, NULL, '0', '', '1.00', '1.00', '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 12 zu Class 14
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Layer 13

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('alkis_sicherungspunkt', NULL, NULL, NULL, NULL, 'Sicherungspunkt', '0', vars_group_id_97, 'SELECT * FROM fp_punkte_alkis WHERE (par=''SiP'' AND endet IS NULL AND endet_punktort IS NULL)', 'fp_punkte_alkis', 'id', NULL, '0', 'wkb_geometry from (SELECT oid, pkn, pktnr, gml_id, par, wkb_geometry FROM nachweisverwaltung.fp_punkte_alkis WHERE endet IS NULL AND endet_punktort is NULL) as foo using unique oid using srid=25833', 'nachweisverwaltung', NULL, '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', 'par', NULL, NULL, NULL, '3', 'pixels', NULL, '25833', 'nachweisverwaltung/view/Festpunkte_alkis.php', NULL, 't', '1', NULL, '520870', NULL, '0', '50001', NULL, '', NULL, 'EPSG:25833', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', 'Sicherungspunkte, abgeleitet aus ALKIS.', NULL, 'Landkreis Rostock, Kataster- und Vermessungsamt', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id13;

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'aam', 'aam', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '28', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'abm', 'abm', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '25', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'advstandardmodell', 'advstandardmodell', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'anlass', 'anlass', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '10', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'art', 'art', 'fp_punkte_alkis', 'fp_punkte_alkis', '_varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '35', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'beginnt', 'beginnt', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'beginnt_punktort', 'beginnt_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'beziehtsichauf', 'beziehtsichauf', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '46', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'bpn', 'bpn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '27', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'bza', 'bza', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '29', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'datei', 'datei', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '48', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'des', 'des', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '33', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'endet', 'endet', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'endet_punktort', 'endet_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'fdv', 'fdv', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '43', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'fgp', 'fgp', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '26', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'gehoertzu', 'gehoertzu', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '47', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'gml_id', 'gml_id', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'gml_id_punktort', 'gml_id_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'gst', 'gst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '39', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'hat', 'hat', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '45', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'hin', 'hin', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '42', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'hoe', 'hoe', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '9', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '17', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'hop', 'hop', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '9', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '18', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'hw', 'hw', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '11', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '16', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'identifier', 'identifier', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'idn', 'idn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '36', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'kds', 'kds', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '32', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'kst', 'kst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '40', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'land', 'land', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '11', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'lzk', 'lzk', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '38', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'nam', 'nam', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '34', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'ogc_fid', 'ogc_fid', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', 'PRIMARY KEY', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'par', 'par', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '4', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '19', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'pkn', 'pkn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '13', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'pktnr', 'pktnr', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '6', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '14', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'pru', 'pru', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '41', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'rho', 'rho', 'fp_punkte_alkis', 'fp_punkte_alkis', 'float8', '', '', NULL, '1', '53', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '31', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'rw', 'rw', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '11', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '15', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'soe', 'soe', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '20', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'soe_alk_par', 'soe_alk_par', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '22', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'soe_alk_pkz', 'soe_alk_pkz', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '21', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'soe_alk_vma', 'soe_alk_vma', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '23', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'soe_weitere', 'soe_weitere', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '24', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'sonstigesmodell', 'sonstigesmodell', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'vwl', 'vwl', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '37', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'wkb_geometry', 'wkb_geometry', 'fp_punkte_alkis', 'fp_punkte_alkis', 'geometry', 'POINT', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '49', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'zde', 'zde', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '30', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'zeigtauf', 'zeigtauf', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '44', '0', '0');

-- Attribut  des Layers 13

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id13, 'zst', 'zst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '12', '0', '0');

-- Class 15 des Layers 13

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('SiP', vars_last_layer_id13, '/^SiP/', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 13 der Class 15

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'circle', '7', '255 255 255', '0 0 0', NULL, NULL, NULL, NULL, '6.00', '8.00', NULL, NULL, '0', '', '1.00', '1.00', '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 13 zu Class 15
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Layer 14

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('alkis_sicherungspunktnr', NULL, NULL, NULL, NULL, 'Punktnummer SiP', '4', vars_group_id_97, '', 'fp_punkte_alkis', 'id', NULL, '0', 'wkb_geometry from (SELECT oid, pkn, pktnr, par, gml_id, wkb_geometry  FROM nachweisverwaltung.fp_punkte_alkis WHERE endet IS NULL AND endet_punktort IS NULL) as foo using unique oid using srid=25833', 'nachweisverwaltung', NULL, '', NULL, NULL, '', '', '', 'pktnr', '50001', '50', '', '0', '', vars_connection_id, '', '6', 'par', NULL, NULL, NULL, '3', 'pixels', NULL, '25833', '', NULL, 'f', '1', NULL, '520860', NULL, '0', '50001', NULL, '', NULL, 'EPSG:25833', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', 'Punktnummern der Sicherungspunkte, abgeleitet aus ALKIS.', NULL, 'Landkreis Rostock, Kataster- und Vermessungsamt', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id14;

-- Class 16 des Layers 14

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('', vars_last_layer_id14, '/^SiP/', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Label 7 der Class 16

				INSERT INTO kvwmap.labels 
					("font", "type", "color", "outlinecolor", "shadowcolor", "shadowsizex", "shadowsizey", "backgroundcolor", "backgroundshadowcolor", "backgroundshadowsizex", "backgroundshadowsizey", "size", "minsize", "maxsize", "position", "offsetx", "offsety", "angle", "anglemode", "buffer", "minfeaturesize", "maxfeaturesize", "partials", "wrap", "the_force") 
				VALUES 
					('arial_underline_1', NULL, '0 50 150', '255 255 255', '', NULL, NULL, '', '', NULL, NULL, '5', '4', '8', '9', '4.0000', '-2.0000', '', NULL, NULL, NULL, NULL, '1', NULL, '1')RETURNING label_id INTO vars_last_label_id;
-- Zuordnung Label 7 zu Class 16
INSERT INTO kvwmap.u_labels2classes (label_id, class_id) VALUES (vars_last_label_id, vars_last_class_id);

-- Layer 15

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('alkis_sonst_vermpunkt', NULL, NULL, NULL, NULL, 'sonst. Vermessungspkt.', '0', vars_group_id_97, 'SELECT * FROM fp_punkte_alkis WHERE (par=''SVP'' AND endet IS NULL  AND endet_punktort IS NULL)', 'fp_punkte_alkis', 'id', NULL, '0', 'wkb_geometry from (SELECT oid, par, pktnr,  wkb_geometry FROM nachweisverwaltung.fp_punkte_alkis WHERE endet IS NULL AND endet_punktort IS NULL) as foo using unique oid using srid=25833', 'nachweisverwaltung', NULL, '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', 'par', NULL, NULL, NULL, '3', 'pixels', NULL, '25833', 'nachweisverwaltung/view/Festpunkte_alkis.php', NULL, 't', '1', NULL, '520850', NULL, '0', '50001', NULL, '', NULL, 'EPSG:25833', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 't', '', 'Aufnahmepunkte, abgeleitet aus ALKIS.', NULL, 'Landkreis Rostock, Kataster- und Vermessungsamt', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id15;

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'aam', 'aam', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '28', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'abm', 'abm', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '25', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'advstandardmodell', 'advstandardmodell', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'anlass', 'anlass', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '10', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'art', 'art', 'fp_punkte_alkis', 'fp_punkte_alkis', '_varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '35', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'beginnt', 'beginnt', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'beginnt_punktort', 'beginnt_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'beziehtsichauf', 'beziehtsichauf', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '46', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'bpn', 'bpn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '27', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'bza', 'bza', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '29', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'datei', 'datei', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '48', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'des', 'des', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '33', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'endet', 'endet', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'endet_punktort', 'endet_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'fdv', 'fdv', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '43', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'fgp', 'fgp', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '26', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'gehoertzu', 'gehoertzu', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '47', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'gml_id', 'gml_id', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'gml_id_punktort', 'gml_id_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'gst', 'gst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '39', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'hat', 'hat', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '45', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'hin', 'hin', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '42', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'hoe', 'hoe', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '9', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '17', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'hop', 'hop', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '9', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '18', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'hw', 'hw', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '11', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '16', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'identifier', 'identifier', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'idn', 'idn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '36', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'kds', 'kds', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '32', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'kst', 'kst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '40', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'land', 'land', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '11', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'lzk', 'lzk', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '38', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'nam', 'nam', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '34', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'ogc_fid', 'ogc_fid', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', 'PRIMARY KEY', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'par', 'par', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '4', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '19', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'pkn', 'pkn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '13', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'pktnr', 'pktnr', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '6', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '14', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'pru', 'pru', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '41', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'rho', 'rho', 'fp_punkte_alkis', 'fp_punkte_alkis', 'float8', '', '', NULL, '1', '53', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '31', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'rw', 'rw', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '11', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '15', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'soe', 'soe', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '20', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'soe_alk_par', 'soe_alk_par', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '22', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'soe_alk_pkz', 'soe_alk_pkz', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '21', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'soe_alk_vma', 'soe_alk_vma', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '23', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'soe_weitere', 'soe_weitere', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '24', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'sonstigesmodell', 'sonstigesmodell', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'vwl', 'vwl', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '37', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'wkb_geometry', 'wkb_geometry', 'fp_punkte_alkis', 'fp_punkte_alkis', 'geometry', 'POINT', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '49', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'zde', 'zde', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '30', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'zeigtauf', 'zeigtauf', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '44', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'zst', 'zst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '12', '0', '0');

-- Class 17 des Layers 15

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('Sonst. Vermpkt', vars_last_layer_id15, '/^SVP/', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 14 der Class 17

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'circle', '8', '255 255 255', '0 0 0', NULL, NULL, NULL, NULL, '6.00', '8.00', NULL, NULL, '0', '', '1.00', '1.00', '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 14 zu Class 17
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Layer 16

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('alkis_sonst_vermpunktnr', NULL, NULL, NULL, NULL, 'Punktnummer sVP', '4', vars_group_id_97, '', 'fp_punkte', 'id', NULL, '0', 'wkb_geometry from (SELECT oid, par, pktnr, wkb_geometry FROM nachweisverwaltung.fp_punkte_alkis WHERE endet IS NULL AND endet_punktort IS NULL) as foo using unique oid using srid=25833', 'nachweisverwaltung', NULL, '', NULL, NULL, '', '', '', 'pktnr', '50001', '50', '', '0', '', vars_connection_id, '', '6', 'par', NULL, NULL, NULL, '3', 'pixels', NULL, '25833', '', NULL, 'f', '1', NULL, '520840', NULL, '0', '50001', NULL, '', NULL, 'EPSG:25833', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', 'Punktnummern sonstiger Vermessungspunkte, abgeleitet aus ALKIS', NULL, 'Landkreis Rostock, Kataster- und Vermessungsamt', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id16;

-- Class 18 des Layers 16

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('', vars_last_layer_id16, '/^SVP/', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Label 8 der Class 18

				INSERT INTO kvwmap.labels 
					("font", "type", "color", "outlinecolor", "shadowcolor", "shadowsizex", "shadowsizey", "backgroundcolor", "backgroundshadowcolor", "backgroundshadowsizex", "backgroundshadowsizey", "size", "minsize", "maxsize", "position", "offsetx", "offsety", "angle", "anglemode", "buffer", "minfeaturesize", "maxfeaturesize", "partials", "wrap", "the_force") 
				VALUES 
					('arial_underline_1', NULL, '0 50 150', '255 255 255', '', NULL, NULL, '', '', NULL, NULL, '6', '6', '8', '9', '4.0000', '-2.0000', '', NULL, NULL, NULL, NULL, NULL, NULL, '1')RETURNING label_id INTO vars_last_label_id;
-- Zuordnung Label 8 zu Class 18
INSERT INTO kvwmap.u_labels2classes (label_id, class_id) VALUES (vars_last_label_id, vars_last_class_id);

-- Layer 17

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('alkis_topopkt', NULL, NULL, NULL, NULL, 'Topogr. Punkte', '0', vars_group_id_97, 'SELECT * FROM fp_punkte_alkis WHERE (par = ''TopP'' AND endet IS NULL AND endet_punktort IS NULL)', 'fp_punkte_alkis', 'id', NULL, '0', 'wkb_geometry from (SELECT oid, par, pktnr, wkb_geometry FROM nachweisverwaltung.fp_punkte_alkis WHERE par = ''TopP'' AND endet IS NULL AND endet_punktort IS NULL) as foo using unique oid using srid=25833', 'nachweisverwaltung', NULL, '', NULL, NULL, '', '', '', 'pktnr', '25001', '50', '', '0', '', vars_connection_id, '', '6', 'par', NULL, NULL, NULL, '3', 'pixels', NULL, '25833', 'nachweisverwaltung/view/Festpunkte_alkis.php', NULL, 't', '1', NULL, '520590', NULL, '0', '50001', NULL, '', NULL, 'EPSG:25833', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', 'Anzeige der Aufnahmepunkte aus ALKIS.', NULL, 'Landkreis Rostock, Kataster- und Vermessungsamt', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id17;

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'aam', 'aam', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '28', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'abm', 'abm', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '25', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'advstandardmodell', 'advstandardmodell', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'anlass', 'anlass', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '10', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'art', 'art', 'fp_punkte_alkis', 'fp_punkte_alkis', '_varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '35', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'beginnt', 'beginnt', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'beginnt_punktort', 'beginnt_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'beziehtsichauf', 'beziehtsichauf', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '46', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'bpn', 'bpn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '27', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'bza', 'bza', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '29', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'datei', 'datei', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '48', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'des', 'des', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '33', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'endet', 'endet', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'endet_punktort', 'endet_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'fdv', 'fdv', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '43', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'fgp', 'fgp', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '26', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'gehoertzu', 'gehoertzu', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '47', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'gml_id', 'gml_id', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'gml_id_punktort', 'gml_id_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'gst', 'gst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '39', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'hat', 'hat', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '45', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'hin', 'hin', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '42', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'hoe', 'hoe', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '9', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '17', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'hop', 'hop', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '9', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '18', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'hw', 'hw', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '11', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '16', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'identifier', 'identifier', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'idn', 'idn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '36', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'kds', 'kds', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '32', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'kst', 'kst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '40', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'land', 'land', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '11', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'lzk', 'lzk', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '38', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'nam', 'nam', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '34', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'ogc_fid', 'ogc_fid', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', 'PRIMARY KEY', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'par', 'par', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '4', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '19', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'pkn', 'pkn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '13', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'pktnr', 'pktnr', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '6', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '14', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'pru', 'pru', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '41', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'rho', 'rho', 'fp_punkte_alkis', 'fp_punkte_alkis', 'float8', '', '', NULL, '1', '53', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '31', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'rw', 'rw', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '11', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '15', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'soe', 'soe', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '20', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'soe_alk_par', 'soe_alk_par', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '22', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'soe_alk_pkz', 'soe_alk_pkz', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '21', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'soe_alk_vma', 'soe_alk_vma', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '23', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'soe_weitere', 'soe_weitere', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '24', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'sonstigesmodell', 'sonstigesmodell', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'vwl', 'vwl', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '37', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'wkb_geometry', 'wkb_geometry', 'fp_punkte_alkis', 'fp_punkte_alkis', 'geometry', 'POINT', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '49', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'zde', 'zde', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '30', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'zeigtauf', 'zeigtauf', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '44', '0', '0');

-- Attribut  des Layers 17

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id17, 'zst', 'zst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '12', '0', '0');

-- Class 19 des Layers 17

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('Top. Punkte', vars_last_layer_id17, '', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 15 der Class 19

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'circle', '5', '230 179 179', '-1 -1 -1', NULL, NULL, NULL, NULL, '4.00', '6.00', NULL, NULL, '0', '', '1.00', '1.00', '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 15 zu Class 19
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Layer 18

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('alkis_topopktnr', NULL, NULL, NULL, NULL, 'Punktnummer TP', '4', vars_group_id_97, 'SELECT * FROM fp_punkte_alkis WHERE par = ''TopP'' and endet is null', 'fp_punkte_alkis', 'id', NULL, '0', 'wkb_geometry  from (select oid, par, pktnr, wkb_geometry from nachweisverwaltung.fp_punkte_alkis where par = ''TopP'' and endet is null) as foo using unique oid using srid=25833', 'nachweisverwaltung', NULL, '', NULL, NULL, '', '', '', 'pktnr', '25001', '50', '', '0', '', vars_connection_id, '', '6', 'par', NULL, NULL, NULL, '3', 'pixels', NULL, '25833', '', NULL, 'f', '1', NULL, '520580', NULL, '0', '50001', NULL, '', NULL, 'EPSG:25833', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', '', NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id18;

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'aam', 'aam', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '28', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'abm', 'abm', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '25', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'advstandardmodell', 'advstandardmodell', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'anlass', 'anlass', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '10', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'art', 'art', 'fp_punkte_alkis', 'fp_punkte_alkis', '_varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '35', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'beginnt', 'beginnt', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'beginnt_punktort', 'beginnt_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'beziehtsichauf', 'beziehtsichauf', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '46', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'bpn', 'bpn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '27', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'bza', 'bza', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '29', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'datei', 'datei', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '48', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'des', 'des', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '33', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'endet', 'endet', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'endet_punktort', 'endet_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'fdv', 'fdv', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '43', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'fgp', 'fgp', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '26', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'gehoertzu', 'gehoertzu', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '47', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'gml_id', 'gml_id', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'gml_id_punktort', 'gml_id_punktort', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'gst', 'gst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '39', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'hat', 'hat', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '45', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'hin', 'hin', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '42', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'hoe', 'hoe', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '9', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '17', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'hop', 'hop', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '9', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '18', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'hw', 'hw', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '11', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '16', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'identifier', 'identifier', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'idn', 'idn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '36', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'kds', 'kds', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '32', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'kst', 'kst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '40', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'land', 'land', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '11', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'lzk', 'lzk', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '38', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'nam', 'nam', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '34', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'ogc_fid', 'ogc_fid', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', 'PRIMARY KEY', NULL, '1', '32', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'par', 'par', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '4', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '19', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'pkn', 'pkn', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '13', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'pktnr', 'pktnr', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '6', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '14', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'pru', 'pru', 'fp_punkte_alkis', 'fp_punkte_alkis', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '41', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'rho', 'rho', 'fp_punkte_alkis', 'fp_punkte_alkis', 'float8', '', '', NULL, '1', '53', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '31', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'rw', 'rw', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', '11', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '15', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'soe', 'soe', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '20', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'soe_alk_par', 'soe_alk_par', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '22', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'soe_alk_pkz', 'soe_alk_pkz', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '21', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'soe_alk_vma', 'soe_alk_vma', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '23', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'soe_weitere', 'soe_weitere', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '24', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'sonstigesmodell', 'sonstigesmodell', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'vwl', 'vwl', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '37', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'wkb_geometry', 'wkb_geometry', 'fp_punkte_alkis', 'fp_punkte_alkis', 'geometry', 'POINT', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '49', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'zde', 'zde', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '30', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'zeigtauf', 'zeigtauf', 'fp_punkte_alkis', 'fp_punkte_alkis', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '44', '0', '0');

-- Attribut  des Layers 18

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id18, 'zst', 'zst', 'fp_punkte_alkis', 'fp_punkte_alkis', 'int4', '', '', NULL, '1', '32', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '12', '0', '0');

-- Class 20 des Layers 18

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('', vars_last_layer_id18, '', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Label 9 der Class 20

				INSERT INTO kvwmap.labels 
					("font", "type", "color", "outlinecolor", "shadowcolor", "shadowsizex", "shadowsizey", "backgroundcolor", "backgroundshadowcolor", "backgroundshadowsizex", "backgroundshadowsizey", "size", "minsize", "maxsize", "position", "offsetx", "offsety", "angle", "anglemode", "buffer", "minfeaturesize", "maxfeaturesize", "partials", "wrap", "the_force") 
				VALUES 
					('arial', NULL, '20 20 20', '255 255 255', '', NULL, NULL, '', '', NULL, NULL, '8', '4', '8', '9', '4.0000', '-2.0000', '', NULL, NULL, NULL, NULL, '1', NULL, '1')RETURNING label_id INTO vars_last_label_id;
-- Zuordnung Label 9 zu Class 20
INSERT INTO kvwmap.u_labels2classes (label_id, class_id) VALUES (vars_last_label_id, vars_last_class_id);

-- Layer 44

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Nachweise', NULL, NULL, NULL, NULL, '', '2', vars_group_id_97, 'SELECT id, flurid, stammnr, rissnummer, blattnummer, datum, vermstelle, gueltigkeit, link_datei, art, format,  fortfuehrung, bemerkungen, bearbeiter, zeit, erstellungszeit, the_geom FROM n_nachweise WHERE (1=1)', 'n_nachweise', 'id', NULL, '0', 'the_geom from (select n.id, n.the_geom, d.hauptart as art from nachweisverwaltung.n_nachweise n, nachweisverwaltung.n_dokumentarten d where d.id = n.art) as foo using unique id using srid=2398', 'nachweisverwaltung', NULL, '', NULL, NULL, '', '', '', '', NULL, '0', '', '0', '', vars_connection_id, '', '6', 'art', NULL, NULL, NULL, '3', 'pixels', NULL, '2398', '', NULL, 't', '1', NULL, '100', NULL, NULL, NULL, NULL, '', NULL, 'EPSG:2398', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', '', NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, 'check_documentpath', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id44;

-- Attribut  des Layers 44

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id44, 'art', 'art', 'n_nachweise', 'n_nachweise', 'bpchar', '', '', NULL, '1', NULL, NULL, '', 'Auswahlfeld', 'select id as value, abkuerzung as output from nachweisverwaltung.n_hauptdokumentarten order by id', 'Art', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Attribut  des Layers 44

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id44, 'bearbeiter', 'bearbeiter', 'n_nachweise', 'n_nachweise', 'varchar', '', '', NULL, '1', '50', NULL, '', 'User', '', 'Bearbeiter', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '13', '0', '0');

-- Attribut  des Layers 44

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id44, 'bemerkungen', 'bemerkungen', 'n_nachweise', 'n_nachweise', 'text', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'Bemerkungen', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '12', '0', '0');

-- Attribut  des Layers 44

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id44, 'blattnummer', 'blattnummer', 'n_nachweise', 'n_nachweise', 'varchar', '', '', NULL, '0', NULL, NULL, '', 'Text', '', 'Blattnummer', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 44

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id44, 'datum', 'datum', 'n_nachweise', 'n_nachweise', 'date', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'Datum', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 44

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id44, 'erstellungszeit', 'erstellungszeit', 'n_nachweise', 'n_nachweise', 'timestamp', '', '', NULL, '1', NULL, NULL, 'SELECT ''2015-06-01 00:00:00''::timestamp without time zone', 'Text', '', 'erstellt am', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '15', '0', '0');

-- Attribut  des Layers 44

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id44, 'flurid', 'flurid', 'n_nachweise', 'n_nachweise', 'int4', '', '', NULL, '0', '32', '0', '', 'Text', '', 'Flur-ID', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 44

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id44, 'format', 'format', 'n_nachweise', 'n_nachweise', 'bpchar', '', '', NULL, '1', NULL, NULL, '', 'Auswahlfeld', 'select ''A4'' as value, ''A4'' as output
UNION
select ''A3'' as value, ''A3'' as output
UNION
select ''SF'' as value, ''Sonderformat'' as output', 'Format', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '10', '0', '0');

-- Attribut  des Layers 44

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id44, 'fortfuehrung', 'fortfuehrung', 'n_nachweise', 'n_nachweise', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', 'Fortführung', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '11', '0', '0');

-- Attribut  des Layers 44

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id44, 'gueltigkeit', 'gueltigkeit', 'n_nachweise', 'n_nachweise', 'int4', '', '', NULL, '1', '32', '0', '', 'Auswahlfeld', 'select 1 as value, ''gültig'' as output
UNION
select 0 as value, ''ungültig'' as output', 'Gültigkeit', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 44

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id44, 'id', 'id', 'n_nachweise', 'n_nachweise', 'int4', '', 'PRIMARY KEY', NULL, '1', '32', '0', '', 'Text', '', 'ID', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 44

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id44, 'link_datei', 'link_datei', 'n_nachweise', 'n_nachweise', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'dynamicLink', 'index.php?go=document_anzeigen&ohnesession=1&id=$id&file=1;Dokument anzeigen', 'Dokument', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 44

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id44, 'rissnummer', 'rissnummer', 'n_nachweise', 'n_nachweise', 'varchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', 'Rissnummer', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 44

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id44, 'stammnr', 'stammnr', 'n_nachweise', 'n_nachweise', 'varchar', '', '', NULL, '1', '15', NULL, '', 'Text', '', 'Auftragsnummer', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 44

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id44, 'the_geom', 'the_geom', 'n_nachweise', 'n_nachweise', 'geometry', 'POLYGON', '', NULL, '1', NULL, NULL, '', 'Geometrie', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '16', '0', '0');

-- Attribut  des Layers 44

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id44, 'vermstelle', 'vermstelle', 'n_nachweise', 'n_nachweise', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Auswahlfeld', 'select id as value, name as output from n_vermstelle order by name', 'Vermessungsstelle', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 44

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id44, 'zeit', 'zeit', 'n_nachweise', 'n_nachweise', 'timestamp', '', '', NULL, '1', NULL, NULL, '', 'Time', '', 'letzte Änderung', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '14', '0', '0');

-- Class 40 des Layers 44

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('FFR', vars_last_layer_id44, '1', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 51 der Class 40

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					('0', '', '0', '255 0 0', '0 0 0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 51 zu Class 40
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Class 41 des Layers 44

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('KVZ', vars_last_layer_id44, '2', '2', '')RETURNING class_id INTO vars_last_class_id;

-- Style 52 der Class 41

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					('0', '', '0', '255 85 0', '0 0 0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 52 zu Class 41
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Class 42 des Layers 44

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('GN', vars_last_layer_id44, '3', '3', '')RETURNING class_id INTO vars_last_class_id;

-- Style 53 der Class 42

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					('0', '', '0', '255 190 0', '0 0 0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 53 zu Class 42
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Class 43 des Layers 44

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('andere', vars_last_layer_id44, '4', '4', '')RETURNING class_id INTO vars_last_class_id;

-- Style 54 der Class 43

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '1', '155 203 0', '', NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, '360', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 54 zu Class 43
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Replace attribute options for Layer 5
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=5', CONCAT('layer_id=', vars_last_layer_id5)) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=5( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '5,', CONCAT(vars_last_layer_id5, ',')) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '5,%';

-- Replace attribute options for Layer 6
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=6', CONCAT('layer_id=', vars_last_layer_id6)) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=6( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '6,', CONCAT(vars_last_layer_id6, ',')) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '6,%';

-- Replace attribute options for Layer 7
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=7', CONCAT('layer_id=', vars_last_layer_id7)) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=7( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '7,', CONCAT(vars_last_layer_id7, ',')) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '7,%';

-- Replace attribute options for Layer 8
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=8', CONCAT('layer_id=', vars_last_layer_id8)) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=8( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '8,', CONCAT(vars_last_layer_id8, ',')) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '8,%';

-- Replace attribute options for Layer 9
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=9', CONCAT('layer_id=', vars_last_layer_id9)) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=9( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '9,', CONCAT(vars_last_layer_id9, ',')) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '9,%';

-- Replace attribute options for Layer 10
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=10', CONCAT('layer_id=', vars_last_layer_id10)) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=10( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '10,', CONCAT(vars_last_layer_id10, ',')) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '10,%';

-- Replace attribute options for Layer 11
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=11', CONCAT('layer_id=', vars_last_layer_id11)) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=11( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '11,', CONCAT(vars_last_layer_id11, ',')) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '11,%';

-- Replace attribute options for Layer 12
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=12', CONCAT('layer_id=', vars_last_layer_id12)) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=12( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '12,', CONCAT(vars_last_layer_id12, ',')) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '12,%';

-- Replace attribute options for Layer 13
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=13', CONCAT('layer_id=', vars_last_layer_id13)) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=13( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '13,', CONCAT(vars_last_layer_id13, ',')) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '13,%';

-- Replace attribute options for Layer 14
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=14', CONCAT('layer_id=', vars_last_layer_id14)) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=14( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '14,', CONCAT(vars_last_layer_id14, ',')) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '14,%';

-- Replace attribute options for Layer 15
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=15', CONCAT('layer_id=', vars_last_layer_id15)) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=15( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '15,', CONCAT(vars_last_layer_id15, ',')) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '15,%';

-- Replace attribute options for Layer 16
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=16', CONCAT('layer_id=', vars_last_layer_id16)) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=16( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '16,', CONCAT(vars_last_layer_id16, ',')) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '16,%';

-- Replace attribute options for Layer 17
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=17', CONCAT('layer_id=', vars_last_layer_id17)) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=17( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '17,', CONCAT(vars_last_layer_id17, ',')) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '17,%';

-- Replace attribute options for Layer 18
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=18', CONCAT('layer_id=', vars_last_layer_id18)) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=18( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '18,', CONCAT(vars_last_layer_id18, ',')) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '18,%';

-- Replace attribute options for Layer 44
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=44', CONCAT('layer_id=', vars_last_layer_id44)) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=44( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '44,', CONCAT(vars_last_layer_id44, ',')) WHERE layer_id IN (vars_last_layer_id5, vars_last_layer_id6, vars_last_layer_id7, vars_last_layer_id8, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id11, vars_last_layer_id12, vars_last_layer_id13, vars_last_layer_id14, vars_last_layer_id15, vars_last_layer_id16, vars_last_layer_id17, vars_last_layer_id18, vars_last_layer_id44) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '44,%';

END $$