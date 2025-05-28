
-- Layerdump aus kvwmap vom 28.05.2025 13:43:29
-- Achtung: Die Datenbank in die der Dump eingespielt wird, sollte die gleiche Migrationsversion haben,
-- wie die Datenbank aus der exportiert wurde! Anderenfalls kann es zu Fehlern bei der Ausführung des SQL kommen.

DO $$
	DECLARE 
		vars_connection_id integer;
		
		vars_group_id_101 integer;
		
		vars_last_layer_id23 integer;
		vars_last_layer_id24 integer;
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
					('Bodenrichtwerte', NULL, NULL, NULL, NULL, NULL, '3', 'f', NULL)RETURNING id INTO vars_group_id_101;

-- Layer 23

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('BORIS ALKIS', NULL, NULL, NULL, NULL, '', '2', vars_group_id_101, 'SELECT *, '''' as bearbeitungslink FROM bw_zonen WHERE (1=1)', 'bw_zonen', 'id', NULL, '0', 'the_geom from (select * from bodenrichtwerte.bw_zonen) as foo using unique gid using srid=25833', 'bodenrichtwerte', NULL, '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', 'zonentyp', NULL, NULL, NULL, '3', 'pixels', NULL, '25833', '', NULL, 't', '1', NULL, '100', NULL, NULL, NULL, NULL, '', NULL, 'EPSG:2398', 'BORIS MV', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 't', 'f', '', '', NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id23;

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'ackerzahl', 'ackerzahl', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '7', NULL, NULL, 'Text', '', 'Ackerzahl', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '25', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'aufwuchs', 'aufwuchs', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '2', NULL, NULL, 'Text', '', 'Aufwuchs', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '27', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'basiskarte', 'basiskarte', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '8', NULL, NULL, 'Text', '', 'Basiskarte', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '11', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'baumassenzahl', 'baumassenzahl', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '9', NULL, NULL, 'Text', '', 'Baumassenzahl', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '20', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'bauweise', 'bauweise', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '2', NULL, NULL, 'Text', '', 'Bauweise', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '16', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'bearbeitungslink', 'bearbeitungslink', '', '', 'unknown', '', '', NULL, NULL, NULL, NULL, NULL, 'dynamicLink', 'index.php?go=Bodenrichtwertformular_Aendern&layer_id=17&gid=$gid;Bodenrichtwertzone bearbeiten;no_new_window', 'Bearbeitung', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '39', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'bedarfswert', 'bedarfswert', 'bw_zonen', 'bw_zonen', 'float4', '', '', NULL, '1', '24', NULL, NULL, 'Text', '', 'Bedarfswert', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '32', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'beitragszustand', 'beitragszustand', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '1', NULL, NULL, 'Text', '', 'Beitragszustand', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '13', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'bemerkungen', 'bemerkungen', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '256', NULL, NULL, 'Text', '', 'Bemerkungen', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '30', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'bodenart', 'bodenart', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '6', NULL, NULL, 'Text', '', 'Bodenart', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '33', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'bodenrichtwert', 'bodenrichtwert', 'bw_zonen', 'bw_zonen', 'float4', '', '', NULL, '1', '24', NULL, NULL, 'Text', '', 'Bodenrichtwert', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'bodenrichtwertnummer', 'bodenrichtwertnummer', 'bw_zonen', 'bw_zonen', 'int4', '', '', NULL, '1', '32', NULL, NULL, 'Text', '', 'Bodenrichtwertnummer', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'breite', 'breite', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '8', NULL, NULL, 'Text', '', 'Breite', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '23', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'brwb', 'brwb', 'bw_zonen', 'bw_zonen', 'float4', '', '', NULL, '1', '24', NULL, NULL, 'Text', '', 'brwb', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '36', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'brws', 'brws', 'bw_zonen', 'bw_zonen', 'float4', '', '', NULL, '1', '24', NULL, NULL, 'Text', '', 'brws', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '35', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'brwu', 'brwu', 'bw_zonen', 'bw_zonen', 'float4', '', '', NULL, '1', '24', NULL, NULL, 'Text', '', 'brwu', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '34', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'entwicklungszustand', 'entwicklungszustand', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '2', NULL, NULL, 'Text', '', 'Entwicklungszustand', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '12', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'ergaenzende_nutzung', 'ergaenzende_nutzung', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '30', NULL, NULL, 'Text', '', 'Ergänzende Nutzung', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '15', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'erschliessungsverhaeltnisse', 'erschliessungsverhaeltnisse', 'bw_zonen', 'bw_zonen', 'int4', '', '', NULL, '1', '32', NULL, NULL, 'Text', '', 'Erschliessungsverhältnisse', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '31', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'flaeche', 'flaeche', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '12', NULL, NULL, 'Text', '', 'Fläche', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '21', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'gemarkung', 'gemarkung', 'bw_zonen', 'bw_zonen', 'int4', '', '', NULL, '1', '32', NULL, NULL, 'Auswahlfeld', 'select land::text||gemarkung::text as value, gemarkungsname as output from alkis.pp_gemarkung as g WHERE g.land::text||regierungsbezirk::text||kreis::text||gemeinde::text = <requires>gemeinde</requires>', 'Gemarkung', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'gemeinde', 'gemeinde', 'bw_zonen', 'bw_zonen', 'int4', '', '', NULL, '1', '32', NULL, NULL, 'Auswahlfeld', 'select schluesselgesamt as value, bezeichnung as output from alkis.ax_gemeinde <required by>gemarkung</required by>', 'Gemeinde', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'geschossflaechenzahl', 'geschossflaechenzahl', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '11', NULL, NULL, 'Text', '', 'Geschossflächenzahl', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '19', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'geschosszahl', 'geschosszahl', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '9', NULL, NULL, 'Text', '', 'Geschosszahl', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '17', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'gid', 'gid', '', '', 'integer', '', '', NULL, NULL, NULL, NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'gruenlandzahl', 'gruenlandzahl', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '7', NULL, NULL, 'Text', '', 'Grünlandzahl', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '26', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'grundflaechenzahl', 'grundflaechenzahl', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '9', NULL, NULL, 'Text', '', 'Grundflaechenzahl', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '18', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'gutachterausschuss', 'gutachterausschuss', 'bw_zonen', 'bw_zonen', 'int4', '', '', NULL, '1', '32', NULL, NULL, 'Text', '', 'Gutachterausschuss', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'nutzungsart', 'nutzungsart', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '7', NULL, NULL, 'Text', '', 'Nutzungsart', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '14', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'oertliche_bezeichnung', 'oertliche_bezeichnung', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '256', NULL, NULL, 'Text', '', 'Örtliche Bezeichnung', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'ortsteilname', 'ortsteilname', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '60', NULL, NULL, 'Text', '', 'Ortsteilname', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'postleitzahl', 'postleitzahl', 'bw_zonen', 'bw_zonen', 'int4', '', '', NULL, '1', '32', NULL, NULL, 'Text', '', 'Postleitzahl', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'stichtag', 'stichtag', 'bw_zonen', 'bw_zonen', 'date', '', '', NULL, '1', NULL, NULL, NULL, 'Text', '', 'Stichtag', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '10', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'textposition', 'textposition', 'bw_zonen', 'bw_zonen', 'geometry', 'POINT', '', NULL, '1', NULL, NULL, NULL, 'Geometrie', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '37', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'the_geom', 'the_geom', 'bw_zonen', 'bw_zonen', 'geometry', 'POLYGON', '', NULL, '1', NULL, NULL, NULL, 'Geometrie', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '38', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'tiefe', 'tiefe', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '8', NULL, NULL, 'Text', '', 'Tiefe', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '22', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'verfahrensgrund', 'verfahrensgrund', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '4', NULL, NULL, 'Text', '', 'Verfahrensgrund', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '28', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'verfahrensgrund_zusatz', 'verfahrensgrund_zusatz', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '2', NULL, NULL, 'Text', '', 'Verfahrensgrund Zusatz', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '29', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'wegeerschliessung', 'wegeerschliessung', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '1', NULL, NULL, 'Text', '', 'Wegeerschliessung', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '24', '0', '0');

-- Attribut  des Layers 23

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id23, 'zonentyp', 'zonentyp', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '256', NULL, NULL, 'Text', '', 'Zonentyp', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Class 23 des Layers 23

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('alle', vars_last_layer_id23, '', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 19 der Class 23

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					('9', '', '2', '-1 -1 -1', '224 92 14', NULL, NULL, NULL, NULL, '2', '4', NULL, NULL, '360', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 19 zu Class 23
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Layer 24

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('BORIS_T', NULL, NULL, NULL, NULL, '', '0', vars_group_id_101, 'SELECT * FROM bw_zonen WHERE (1=1)', 'bw_zonen', 'id', NULL, '0', 'textposition from (select *, round(bodenrichtwert::numeric) AS bw_darstellung from bodenrichtwerte.bw_zonen) as foo using unique gid using srid=25833', 'bodenrichtwerte', NULL, '', NULL, NULL, '', '', '', 'bw_darstellung', '30000', '1', '', '0', '', vars_connection_id, '', '6', 'gid', NULL, NULL, NULL, '3', 'pixels', NULL, '25833', '', NULL, 'f', '1', NULL, '445', NULL, NULL, NULL, NULL, '', NULL, 'EPSG:25833', 'BORIS MV', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', '', NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id24;

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'ackerzahl', 'ackerzahl', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '7', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '24', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'aufwuchs', 'aufwuchs', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '2', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '26', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'basiskarte', 'basiskarte', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '8', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '10', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'baumassenzahl', 'baumassenzahl', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '9', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '19', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'bauweise', 'bauweise', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '2', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '15', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'bedarfswert', 'bedarfswert', 'bw_zonen', 'bw_zonen', 'float4', '', '', NULL, '1', '24', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '31', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'beitragszustand', 'beitragszustand', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '1', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '12', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'bemerkungen', 'bemerkungen', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '256', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '29', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'bodenart', 'bodenart', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '6', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '32', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'bodenrichtwert', 'bodenrichtwert', 'bw_zonen', 'bw_zonen', 'float4', '', '', NULL, '1', '24', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'bodenrichtwertnummer', 'bodenrichtwertnummer', 'bw_zonen', 'bw_zonen', 'int4', '', '', NULL, '1', '32', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'breite', 'breite', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '8', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '22', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'brwb', 'brwb', 'bw_zonen', 'bw_zonen', 'float4', '', '', NULL, '1', '24', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '35', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'brws', 'brws', 'bw_zonen', 'bw_zonen', 'float4', '', '', NULL, '1', '24', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '34', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'brwu', 'brwu', 'bw_zonen', 'bw_zonen', 'float4', '', '', NULL, '1', '24', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '33', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'entwicklungszustand', 'entwicklungszustand', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '2', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '11', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'ergaenzende_nutzung', 'ergaenzende_nutzung', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '30', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '14', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'erschliessungsverhaeltnisse', 'erschliessungsverhaeltnisse', 'bw_zonen', 'bw_zonen', 'int4', '', '', NULL, '1', '32', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '30', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'flaeche', 'flaeche', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '12', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '20', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'gemarkung', 'gemarkung', 'bw_zonen', 'bw_zonen', 'int4', '', '', NULL, '1', '32', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'gemeinde', 'gemeinde', 'bw_zonen', 'bw_zonen', 'int4', '', '', NULL, '1', '32', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'geschossflaechenzahl', 'geschossflaechenzahl', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '11', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '18', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'geschosszahl', 'geschosszahl', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '9', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '16', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'gruenlandzahl', 'gruenlandzahl', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '7', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '25', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'grundflaechenzahl', 'grundflaechenzahl', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '9', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '17', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'gutachterausschuss', 'gutachterausschuss', 'bw_zonen', 'bw_zonen', 'int4', '', '', NULL, '1', '32', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'nutzungsart', 'nutzungsart', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '7', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '13', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'oertliche_bezeichnung', 'oertliche_bezeichnung', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '256', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'ortsteilname', 'ortsteilname', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '60', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'postleitzahl', 'postleitzahl', 'bw_zonen', 'bw_zonen', 'int4', '', '', NULL, '1', '32', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'stichtag', 'stichtag', 'bw_zonen', 'bw_zonen', 'date', '', '', NULL, '1', NULL, NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'textposition', 'textposition', 'bw_zonen', 'bw_zonen', 'geometry', 'POINT', '', NULL, '1', NULL, NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '36', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'the_geom', 'the_geom', 'bw_zonen', 'bw_zonen', 'geometry', 'POLYGON', '', NULL, '1', NULL, NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '37', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'tiefe', 'tiefe', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '8', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '21', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'verfahrensgrund', 'verfahrensgrund', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '4', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '27', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'verfahrensgrund_zusatz', 'verfahrensgrund_zusatz', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '2', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '28', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'wegeerschliessung', 'wegeerschliessung', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '1', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '23', '0', '0');

-- Attribut  des Layers 24

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id24, 'zonentyp', 'zonentyp', 'bw_zonen', 'bw_zonen', 'varchar', '', '', NULL, '1', '256', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Class 24 des Layers 24

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('', vars_last_layer_id24, '', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 20 der Class 24

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '1', '', '', NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, '360', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 20 zu Class 24
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Label 12 der Class 24

				INSERT INTO kvwmap.labels 
					("font", "type", "color", "outlinecolor", "shadowcolor", "shadowsizex", "shadowsizey", "backgroundcolor", "backgroundshadowcolor", "backgroundshadowsizex", "backgroundshadowsizey", "size", "minsize", "maxsize", "position", "offsetx", "offsety", "angle", "anglemode", "buffer", "minfeaturesize", "maxfeaturesize", "partials", "wrap", "the_force") 
				VALUES 
					('arial_bold', NULL, '224 92 14', '255 255 255', '', NULL, NULL, '255 255 255', '', NULL, NULL, '10', '10', '15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING label_id INTO vars_last_label_id;
-- Zuordnung Label 12 zu Class 24
INSERT INTO kvwmap.u_labels2classes (label_id, class_id) VALUES (vars_last_label_id, vars_last_class_id);

-- Replace attribute options for Layer 23
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=23', CONCAT('layer_id=', vars_last_layer_id23)) WHERE layer_id IN (vars_last_layer_id23, vars_last_layer_id24) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=23( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '23,', CONCAT(vars_last_layer_id23, ',')) WHERE layer_id IN (vars_last_layer_id23, vars_last_layer_id24) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '23,%';

-- Replace attribute options for Layer 24
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=24', CONCAT('layer_id=', vars_last_layer_id24)) WHERE layer_id IN (vars_last_layer_id23, vars_last_layer_id24) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=24( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '24,', CONCAT(vars_last_layer_id24, ',')) WHERE layer_id IN (vars_last_layer_id23, vars_last_layer_id24) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '24,%';

END $$