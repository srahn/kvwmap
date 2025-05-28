
-- Layerdump aus kvwmap vom 28.05.2025 14:16:30
-- Achtung: Die Datenbank in die der Dump eingespielt wird, sollte die gleiche Migrationsversion haben,
-- wie die Datenbank aus der exportiert wurde! Anderenfalls kann es zu Fehlern bei der Ausführung des SQL kommen.

DO $$
	DECLARE 
		vars_connection_id integer;
		
		vars_group_id_99 integer;
		
		vars_last_layer_id19 integer;
		vars_last_layer_id25 integer;
		vars_last_layer_id26 integer;
		vars_last_layer_id27 integer;
		vars_last_layer_id28 integer;
		vars_last_layer_id45 integer;
		vars_last_layer_id46 integer;
		vars_last_layer_id47 integer;
		vars_last_layer_id50 integer;
		vars_last_layer_id48 integer;
		vars_last_layer_id49 integer;
		vars_last_layer_id52 integer;
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
					('Ressourcen', NULL, NULL, NULL, NULL, NULL, NULL, 'f', NULL)RETURNING id INTO vars_group_id_99;

-- Layer 19

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Attribute', NULL, NULL, NULL, NULL, NULL, '5', vars_group_id_99, 'SELECT
    id,
    ressource_id,
    bezeichnung,
    name,
    beschreibung,
    datentyp,
    valuelist,
    minvalue,
    maxvalue,
    defaultvalue,
    mandatory,
    created_from,
    created_at,
    updated_from,
    updated_at
FROM
  metadata.attributes
WHERE
  true
ORDER BY
  bezeichnung', 'attributes', 'id', NULL, '0', '', 'metadata', NULL, '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', NULL, NULL, NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '1', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', NULL, '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id19;

-- Attribut  des Layers 19

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id19, 'beschreibung', 'beschreibung', 'attributes', 'attributes', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Beschreibung', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '5', '0', '0');

-- Attribut  des Layers 19

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id19, 'bezeichnung', 'bezeichnung', 'attributes', 'attributes', 'varchar', '', '', '1', '0', NULL, NULL, '', 'Text', '', 'Bezeichnung', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '3', '0', '0');

-- Attribut  des Layers 19

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id19, 'created_at', 'created_at', 'attributes', 'attributes', 'timestamp', '', '', '1', '0', NULL, NULL, 'now()', 'Time', 'insert', 'am', NULL, '', NULL, NULL, '', '', NULL, '1', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '13', '0', '0');

-- Attribut  des Layers 19

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id19, 'created_from', 'created_from', 'attributes', 'attributes', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', 'insert', 'erstellt von', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '12', '0', '0');

-- Attribut  des Layers 19

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id19, 'datentyp', 'datentyp', 'attributes', 'attributes', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Datentyp', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '6', '0', '0');

-- Attribut  des Layers 19

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id19, 'defaultvalue', 'defaultvalue', 'attributes', 'attributes', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Defaultwert', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '10', '0', '0');

-- Attribut  des Layers 19

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id19, 'id', 'id', 'attributes', 'attributes', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''attributes_id_seq''::regclass)', 'Text', '', 'ID', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, '1', NULL, NULL, '1', '', '', '', '1', '0', '0');

-- Attribut  des Layers 19

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id19, 'mandatory', 'mandatory', 'attributes', 'attributes', 'bool', '', '', '1', '1', NULL, NULL, '', 'Checkbox', '', 'Pflichtattribut', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '11', '0', '0');

-- Attribut  des Layers 19

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id19, 'maxvalue', 'maxvalue', 'attributes', 'attributes', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Maximalwert', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '9', '0', '0');

-- Attribut  des Layers 19

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id19, 'minvalue', 'minvalue', 'attributes', 'attributes', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Minimalwert', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '8', '0', '0');

-- Attribut  des Layers 19

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id19, 'name', 'name', 'attributes', 'attributes', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Spaltenname', NULL, '', NULL, NULL, 'Name der Spalte in der Tabelle', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '4', '0', '0');

-- Attribut  des Layers 19

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id19, 'ressource_id', 'ressource_id', 'attributes', 'attributes', 'int4', '', '', '1', '0', '32', '0', '', 'Text', '', 'Ressource-ID', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '2', '0', '0');

-- Attribut  des Layers 19

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id19, 'updated_at', 'updated_at', 'attributes', 'attributes', 'timestamp', '', '', '1', '1', NULL, NULL, '', 'Time', 'update', 'am', NULL, '', NULL, NULL, '', '', NULL, '1', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '15', '0', '0');

-- Attribut  des Layers 19

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id19, 'updated_from', 'updated_from', 'attributes', 'attributes', 'varchar', '', '', '1', '1', NULL, NULL, '', 'User', 'update', 'geändert von', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '14', '0', '0');

-- Attribut  des Layers 19

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id19, 'valuelist', 'valuelist', 'attributes', 'attributes', '_varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Werteliste', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '7', '0', '0');

-- Layer 25

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Datenabstammung', NULL, NULL, NULL, NULL, NULL, '5', vars_group_id_99, 'SELECT
  l.id,
  l.source_id,
  l.target_id,
  '''' AS source_link,
  '''' AS target_link
FROM
  metadata.lineages l
WHERE
  true', 'lineages', 'id', NULL, '0', '', 'metadata', NULL, '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', NULL, NULL, NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '1', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', NULL, '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id25;

-- Attribut  des Layers 25

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id25, 'id', 'id', 'lineages', 'l', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''lineages_id_seq''::regclass)', 'Text', '', 'ID', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '1', '0', '0');

-- Attribut  des Layers 25

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id25, 'source_id', 'source_id', 'lineages', 'l', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  r.id AS value,
  concat_ws('' - '', g.gruppe, r.bezeichnung) AS output
FROM
  metadata.ressources r LEFT JOIN
  metadata.gruppen g ON r.gruppe_id = g.id
ORDER BY
  g.gruppe,
  r.bezeichnung', 'Quelle', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '5', '0', '0');

-- Attribut  des Layers 25

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id25, 'source_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=47&value_id=$source_id&operator_id==;anzeigen;no_new_window', '', NULL, '', NULL, NULL, '', '', NULL, '1', '2', NULL, NULL, NULL, NULL, '1', '', '', '', '6', '0', '0');

-- Attribut  des Layers 25

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id25, 'target_id', 'target_id', 'lineages', 'l', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  r.id AS value,
  concat_ws('' - '', g.gruppe, r.bezeichnung) AS output
FROM
  metadata.ressources r LEFT JOIN
  metadata.gruppen g ON r.gruppe_id = g.id
ORDER BY
  g.gruppe,
  r.bezeichnung', 'Ziel', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '2', '0', '0');

-- Attribut  des Layers 25

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id25, 'target_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=47&value_id=$target_id&operator_id==;anzeigen;no_new_window', '', NULL, '', NULL, NULL, '', '', NULL, '1', '2', NULL, NULL, NULL, NULL, '1', '', '', '', '3', '0', '0');

-- Layer 26

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Datenformate', NULL, NULL, NULL, NULL, NULL, '5', vars_group_id_99, 'SELECT
  id,
  format
FROM
  metadata.formate
WHERE
  true
ORDER BY
  format', 'formate', 'id', NULL, '0', '', 'metadata', NULL, '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', NULL, NULL, NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '1', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', NULL, '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id26;

-- Attribut  des Layers 26

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id26, 'format', 'format', 'formate', 'formate', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Format', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '2', '0', '0');

-- Attribut  des Layers 26

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id26, 'id', 'id', 'formate', 'formate', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''formate_id_seq''::regclass)', 'Text', '', 'ID', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '1', '0', '0');

-- Layer 27

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Dateninhaber', NULL, NULL, NULL, NULL, NULL, '5', vars_group_id_99, 'SELECT
  id,
  dateninhaber
FROM
  metadata.dateninhaber
WHERE
  true', 'dateninhaber', 'id', NULL, '0', '', 'metadata', NULL, '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', NULL, NULL, NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '1', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', NULL, '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id27;

-- Attribut  des Layers 27

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id27, 'dateninhaber', 'dateninhaber', 'dateninhaber', 'dateninhaber', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Dateninhaber', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '2', '1', '0');

-- Attribut  des Layers 27

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id27, 'id', 'id', 'dateninhaber', 'dateninhaber', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''dateninhaber_id_seq''::regclass)', 'Text', '', 'ID', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '1', '0', '0');

-- Layer 28

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('datenpakete', NULL, NULL, NULL, NULL, 'Datenpakete', '5', vars_group_id_99, 'SELECT
  r.id AS ressource_id,
  r.bezeichnung,
  p.id,
  p.stelle_id,
  p.pack_status_id,
  p.created_at,
  p.created_from
FROM
  metadata.ressources r LEFT JOIN
  metadata.data_packages p ON r.id = p.ressource_id
WHERE
  r.use_for_datapackage AND
  r.layer_id IS NOT NULL', 'data_packages', 'id', NULL, '0', '', 'metadata', NULL, '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', NULL, NULL, NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '1', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', NULL, '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id28;

-- Attribut  des Layers 28

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id28, 'bezeichnung', 'bezeichnung', 'ressources', 'r', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Bezeichnung', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '1', '0', '0');

-- Attribut  des Layers 28

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id28, 'created_at', 'created_at', 'data_packages', 'p', 'timestamp', '', '', '1', '0', NULL, NULL, 'now()', 'Text', '', 'erzeugt am', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '12', '0', '0');

-- Attribut  des Layers 28

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id28, 'created_from', 'created_from', 'data_packages', 'p', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'erzeugt von', NULL, NULL, NULL, NULL, '', '', NULL, '1', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '13', '0', '0');

-- Attribut  des Layers 28

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id28, 'id', 'id', 'data_packages', 'p', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''data_packages_id_seq''::regclass)', 'Text', '', 'Paket-ID', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '4', '0', '0');

-- Attribut  des Layers 28

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id28, 'pack_status_id', 'pack_status_id', 'data_packages', 'p', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id AS value, status AS output FROM metadata.pack_status ORDER BY id', 'Packstatus', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '3', '0', '0');

-- Attribut  des Layers 28

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id28, 'ressource_id', 'id', 'ressources', 'r', 'int4', '', '', '1', '1', '32', '0', 'nextval(''ressources_id_seq''::regclass)', 'Text', '', 'Ressource-ID', NULL, NULL, NULL, NULL, '', '', NULL, '1', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '2', '0', '0');

-- Attribut  des Layers 28

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id28, 'stelle_id', 'stelle_id', 'data_packages', 'p', 'int4', '', '', '1', '1', '32', '0', '', 'Text', '', 'Stelle-ID', NULL, NULL, NULL, NULL, '', '', NULL, '1', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '5', '0', '0');

-- Layer 45

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('package_errors', NULL, NULL, NULL, NULL, 'Fehler beim Packen', '5', vars_group_id_99, 'SELECT
  l.id,
  r.bezeichnung,
  l.packed_at,
  l.msg,
  l.package_id,
  l.ressource_id,
  l.fixed_at
FROM
  metadata.pack_logs l JOIN
  metadata.data_packages p ON l.package_id = p.id JOIN
  metadata.ressources r ON p.ressource_id = r.id
WHERE
  p.pack_status_id = -1 AND
  l.fixed_at IS NULL
ORDER BY
  packed_at DESC', 'pack_logs', 'id', NULL, '0', '', 'metadata', NULL, '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', NULL, NULL, NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '1', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', NULL, '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id45;

-- Attribut  des Layers 45

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id45, 'bezeichnung', 'bezeichnung', 'ressources', 'r', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Ressource', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '1', '0', '0');

-- Attribut  des Layers 45

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id45, 'fixed_at', 'fixed_at', 'pack_logs', 'l', 'timestamp', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'behoben am', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '4', '1', '0');

-- Attribut  des Layers 45

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id45, 'id', 'id', 'pack_logs', 'l', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''update_log_id_seq''::regclass)', 'Text', '', 'Log-ID', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '5', '0', '0');

-- Attribut  des Layers 45

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id45, 'msg', 'msg', 'pack_logs', 'l', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Fehlermeldung', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '2', '0', '0');

-- Attribut  des Layers 45

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id45, 'package_id', 'package_id', 'pack_logs', 'l', 'int4', '', '', '1', '0', '32', '0', '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=45&value_id=$package_id&operator_id==;zum Paket (ID: $package_id)', 'Paket', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '8', '0', '0');

-- Attribut  des Layers 45

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id45, 'packed_at', 'packed_at', 'pack_logs', 'l', 'timestamp', '', '', '1', '0', NULL, NULL, 'now()', 'Text', '', 'aufgetreten am', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '3', '0', '0');

-- Attribut  des Layers 45

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id45, 'ressource_id', 'ressource_id', 'pack_logs', 'l', 'int4', '', '', '1', '0', '32', '0', '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=47&value_id=$ressource_id&operator_id==;zur Ressource (ID: $ressource_id)', 'Ressource', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '6', '0', '0');

-- Layer 46

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Ressourcen', NULL, NULL, NULL, NULL, NULL, '5', vars_group_id_99, 'SELECT
  ba.id,
  ba.ampel_id,
  relevanz,
  digital,
  flaechendeckend,
  inquiries_required,
  check_required,
  inquiries_required OR check_required AS any_required,
  inquiries_responsible,
  inquiries_to,
  inquiries,
  inquiries_responses,
  CASE WHEN relevanz AND digital AND flaechendeckend THEN 1 WHEN relevanz  THEN 2 ELSE 3 END AS prioritaet,
  bemerkung_prioritaet,
  COALESCE(a.style, ''background-color: lightgray'') as style,
  ba.gruppe_id,
  ba.bezeichnung, ba.gebietseinheit_id, 
  ba.hinweise_auf,
  ba.beschreibung, 
  dateninhaber_id, 
  ansprechperson,
  ba.format_id,
  aktualitaet,
  ba.url,
  ba.datenguete_id,
  quelle,
  '''' datenquellen,
  '''' datenziele,
  '''' Attribute,
  documents,
  github,
  last_updated_at,
  auto_update,
  update_interval,
  update_time,
  download_url,
  '''' subressources,
  download_method,
  download_path,
  '''' download_link,
  unpack_method,
  dest_path,
  '''' unpack_link,
  import_method,
  import_file,
  import_schema,
  import_table,
  import_layer,
  import_filter,
  import_epsg,
  '''' import_link,
  transform_method,
  transform_command,
  '''' transform_link,
  status_id,
  error_msg,
  use_for_datapackage,
  von_eneka,
  '''' update_ressource_link,
  '''' update_packages_link,
  layer_id,
  '''' layer_link,
  '''' update_logs,
  '''' pack_logs,
  created_at,
  created_from,
  updated_at,
  updated_from
FROM
  metadata.ressources ba LEFT JOIN
  metadata.ampel a ON ba.ampel_id = a.id
WHERE
  von_eneka OR use_for_datapackage', 'ressources', 'id', NULL, '0', '', 'metadata', NULL, '/var/www/data/bilder/', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', NULL, NULL, NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '1', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', NULL, '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id46;

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'aktualitaet', 'aktualitaet', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Aktualität', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '21', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'ampel_id', 'ampel_id', 'ressources', 'ba', 'int4', '', '', '1', '1', '32', '0', '', 'Radiobutton', 'SELECT
  id AS value,
  farbe AS output
FROM
  metadata.ampel
ORDER BY reihenfolge;horizontal ', 'Ampel', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '6', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'ansprechperson', 'ansprechperson', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Ansprechperson', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '19', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'any_required', '', '', '', 'bool', '', '', '0', NULL, NULL, NULL, '', 'Text', '', '', NULL, '', NULL, NULL, '', 'Rückfragen;collapsed', NULL, '0', '0', NULL, NULL, NULL, NULL, '0', '', '', '', '32', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'attribute', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '39,id:ressource_id,bezeichnung;embedded', 'Attribute', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '26', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'auto_update', 'auto_update', 'ressources', 'ba', 'bool', '', '', '1', '1', NULL, NULL, '', 'Checkbox', '', 'Auto-Update', NULL, '', NULL, NULL, 'Ob der Datensatz automatisch geupdated werden soll. Wirkt im Zusammenhang mit dem Update-Interval und dem Datum des letzten Updates. Status muss leer oder Uptodate sein. Wenn Auto-Update gesetzt und das Interval leer ist wird nur ein mal aktualisiert und das Update-Datum gesetzt.', 'Prozesse', NULL, '1', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '38', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'bemerkung_prioritaet', 'bemerkung_prioritaet', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Bemerkung zur Priorität', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '14', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'beschreibung', 'beschreibung', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Beschreibung', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '17', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'bezeichnung', 'bezeichnung', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Bezeichnung der Datenquelle', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '5', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'check_required', 'check_required', 'ressources', 'ba', 'bool', '', '', '1', '1', NULL, NULL, '', 'Checkbox', '', 'Prüfung der Daten erforderlich', NULL, '', NULL, NULL, 'Prüfungen die GDI-Service vornehmen muss bevor die Ressource zur Verwendung im Datentool aufgenommen wird.', 'Rückfragen;collapsed', NULL, '1', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '31', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'created_at', 'created_at', 'ressources', 'ba', 'timestamp', '', '', '1', '1', NULL, NULL, '', 'Time', 'insert', 'am', NULL, '', NULL, NULL, '', 'Bearbeitungsvermerke;collapsed', NULL, '1', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '69', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'created_from', 'created_from', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'User', 'insert', 'erzeugt von', NULL, '', NULL, NULL, '', 'Bearbeitungsvermerke;collapsed', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '68', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'datenguete_id', 'datenguete_id', 'ressources', 'ba', 'int4', '', '', '1', '1', '32', '0', '', 'Radiobutton', 'SELECT
  id AS value,
  guete AS output
FROM
  metadata.datenguete
ORDER BY id;horizontal ', 'Datengüte', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '1', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '7', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'dateninhaber_id', 'dateninhaber_id', 'ressources', 'ba', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  id AS value,
  dateninhaber AS output
FROM
  metadata.dateninhaber
ORDER BY dateninhaber;layer_id=27 embedded', 'Dateninhaber', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '18', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'datenquellen', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '25,id:target_id,source_id;embedded', 'Datenquellen', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '27', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'datenziele', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '25,id:source_id,target_id;embedded', 'Datenziele', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '28', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'dest_path', 'dest_path', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Zielverzeichnis', NULL, '', NULL, NULL, 'relativ zu METADATA_DATA_PATH/ressourcen/', 'Prozesse', NULL, '1', '0', NULL, NULL, NULL, NULL, '2', 'unpack_method', '!=', '', '47', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'digital', 'digital', 'ressources', 'ba', 'bool', '', '', '1', '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT
  column1 AS output, column2 AS value
FROM ( VALUES
  (''ja'', true),
  (''nein'', false)
) AS options', 'digital verfügbar', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '1', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '10', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'documents', 'documents', 'ressources', 'ba', '_varchar', '', '', '1', '1', NULL, NULL, '', 'Dokument', '', 'Dokumente', NULL, NULL, NULL, NULL, '', 'Metadaten', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '29', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'download_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=metadata_update_outdated&ressource_id=$id&method_only=download;run', '', NULL, NULL, NULL, NULL, '', 'Prozesse', NULL, '1', '2', NULL, NULL, NULL, NULL, '2', 'download_method', '!=', '', '45', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'download_method', 'download_method', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT
  name AS value,
  beschreibung AS output
FROM
  metadata.download_methods
ORDER BY reihenfolge', 'Download-Methode', NULL, '', NULL, NULL, 'Wenn die Importmethode WFS ist und kein Tabellenname angegeben wird, werden statt dessen alle FeatureTypen des WFS runtergeladen und in gml-Dateien geschrieben.', 'Prozesse', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '43', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'download_path', 'download_path', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Download-Verzeichnis', NULL, '', NULL, NULL, 'relativ zu METADATA_DATA_PATH/ressourcen/', 'Prozesse', NULL, '1', '0', NULL, NULL, NULL, NULL, '2', 'download_method', '!=', '', '44', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'download_url', 'download_url', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Link', '', 'Download-URL', NULL, '', NULL, NULL, '', 'Prozesse', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '41', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'error_msg', 'error_msg', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Fehlermeldung', NULL, '', NULL, NULL, 'Wird automatisch eingetragen wenn ein Fehler beim Download, Entpacken, Importieren oder Transformieren der Ressource auftritt.', 'Prozesse', NULL, '0', '0', NULL, NULL, NULL, NULL, '2', 'status_id', '=', '-1', '60', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'flaechendeckend', 'flaechendeckend', 'ressources', 'ba', 'bool', '', '', '1', '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT
  column1 AS output, column2 AS value
FROM ( VALUES
  (''ja'', true),
  (''nein'', false)
) AS options', 'flächendeckend verfügbar', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '1', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '11', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'format_id', 'format_id', 'ressources', 'ba', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  id AS value,
  format AS output
FROM
  metadata.formate
ORDER BY format;layer_id=8 embedded', 'Format', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '20', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'gebietseinheit_id', 'gebietseinheit_id', 'ressources', 'ba', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  id AS value,
  abk AS output
FROM
  metadata.gebietseinheiten
ORDER BY reihenfolge
', 'Gebietseinheit', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '6', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'github', 'github', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Github', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '24', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'gruppe_id', 'gruppe_id', 'ressources', 'ba', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  id AS value,
  gruppe AS output
FROM
  metadata.gruppen
ORDER BY id;layer_id=47 embedded', 'gruppe', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '1', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'hinweise_auf', 'hinweise_auf', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Hinweise auf', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '16', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'id', 'id', 'ressources', 'ba', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''ressources_id_seq''::regclass)', 'Text', '', 'ID', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '1', '0', NULL, '1', NULL, NULL, '1', '', '', '', '2', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'import_epsg', 'import_epsg', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT
  srid AS value,
  alias AS output
FROM
  public.spatial_ref_sys_alias
ORDER BY srid
', 'Import-EPSG-Code', NULL, '', NULL, NULL, 'Koordinatenreferenzsystem in dem die Quelledaten eingeladen werden sollen. Zur Auswahl stehen nur die in dieser Anwendung vom Administrator eingestellten Werte.', 'Prozesse', NULL, '1', '0', NULL, NULL, NULL, NULL, '2', 'import_method', '!=', '', '55', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'import_file', 'import_file', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Importdatei', NULL, NULL, NULL, NULL, 'Bei Shape mit ogr2ogr in Postgres der Name der Shape-Datei ohne Dateiendung. Wenn keine angegeben wird, werden alle Dateien im Import-Verzeichnis eingelesen.', 'Prozesse', NULL, '1', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '50', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'import_filter', 'import_filter', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Importfilter', NULL, NULL, NULL, NULL, 'Bedingung, die beim Import verwendet werden soll, z.B. bei SQL-Import eine WHERE-Klausel die passend zum Import-Statement wahr oder falsch liefert.', 'Prozesse', NULL, '0', '0', NULL, NULL, NULL, NULL, '2', 'import_method', '!=', '', '53', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'import_layer', 'import_layer', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Importlayer', NULL, NULL, NULL, NULL, 'Name des Layers, der importiert werden soll. Beim GML-Import z.B. der Name des Featuretyps der in die Tabelle importiert werden soll. Beim CSV-Import der Name der CSV-Datei ohne Endung', 'Prozesse', NULL, '1', '0', NULL, NULL, NULL, NULL, '2', 'import_method', '!=', '', '54', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'import_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=metadata_update_outdated&ressource_id=$id&method_only=import;run', '', NULL, NULL, NULL, NULL, '', 'Prozesse', NULL, '1', '2', NULL, NULL, NULL, NULL, '2', 'import_method', '!=', '', '56', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'import_method', 'import_method', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT
  name AS value,
  beschreibung AS output
FROM
  metadata.import_methods
ORDER BY reihenfolge', 'Importmethode', NULL, '', NULL, NULL, 'Bei der Methode WFS werden wenn keine Import-Tabelle angegeben ist alle GML-Dateien aus dem import-Verzeichnis eingelesen.', 'Prozesse', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '49', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'import_schema', 'import_schema', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Importschema', NULL, NULL, NULL, NULL, 'Name des Schemas in die die Daten importiert werden sollen. Wird hier nichts angegeben wird automatisch in das Schema import importiert.', 'Prozesse', NULL, '0', '0', NULL, NULL, NULL, NULL, '2', 'import_method', '!=', '', '51', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'import_table', 'import_table', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Importtabelle', NULL, '', NULL, NULL, 'Name der Tabelle im Import-Schema in die die Daten importiert werden sollen, falls das nicht in der Importmethode schon definiert wurde. Alles klein schreiben.
Wenn die Importmethode WFS ist und kein Tabellenname angegeben wird, werden statt dessen alle GML-Dateien aus dem Import-Ordner eingelesen  und die Tabellen so wie die Dateien benannt.', 'Prozesse', NULL, '1', '0', NULL, NULL, NULL, NULL, '2', 'import_method', '!=', '', '52', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'inquiries', 'inquiries', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Anfragen', NULL, '', NULL, NULL, '', 'Rückfragen;collapsed', NULL, '0', '0', NULL, NULL, NULL, NULL, '2', 'any_required', '=', 't', '35', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'inquiries_required', 'inquiries_required', 'ressources', 'ba', 'bool', '', '', '1', '1', NULL, NULL, '', 'Checkbox', '', 'Anfragen erforderlich', NULL, '', NULL, NULL, '', 'Rückfragen;collapsed', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '30', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'inquiries_responses', 'inquiries_responses', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Rückmeldungen', NULL, '', NULL, NULL, 'Rückmeldungen zu Rückfragen zu den Daten', 'Rückfragen;collapsed', NULL, '0', '0', NULL, NULL, NULL, NULL, '2', 'any_required', '=', 't', '36', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'inquiries_responsible', 'inquiries_responsible', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Für Anfragen verantwortlich', NULL, '', NULL, NULL, 'Person oder Einrichtung die die Rückfragen an den Datenlieferanten stellen soll und die Rückmeldungen hier notiert.', 'Rückfragen;collapsed', NULL, '0', '0', NULL, NULL, NULL, NULL, '2', 'any_required', '=', 't', '33', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'inquiries_to', 'inquiries_to', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Anfrage an', NULL, '', NULL, NULL, 'An wen sich die Anfrage richten soll', 'Rückfragen;collapsed', NULL, '1', '0', NULL, NULL, NULL, NULL, '2', 'any_required', '=', 't', '34', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'last_updated_at', 'last_updated_at', 'ressources', 'ba', 'timestamp', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'letzter Update', NULL, NULL, NULL, NULL, '', 'Prozesse', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '37', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'layer_id', 'layer_id', 'ressources', 'ba', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  layer_id AS value,
  COALESCE(alias, name) AS output
FROM
  kvwmap.layer
ORDER BY
  COALESCE(alias, name)', 'Layer', NULL, NULL, NULL, NULL, '', 'Visualisierung', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '64', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'layer_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layereditor&selected_layer_id=$layer_id;zum Layer', '', NULL, NULL, NULL, NULL, '', 'Visualisierung', NULL, '1', '2', NULL, NULL, NULL, NULL, '1', '', '', '', '65', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'pack_logs', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '449,id:ressource_id,packed_at;embedded', '', NULL, NULL, NULL, NULL, '', 'Protokollierung;collapsed', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '67', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'prioritaet', '', '', '', 'int4', '', '', '0', NULL, NULL, NULL, '', 'Textfeld', '', 'Priorität', NULL, '', NULL, NULL, '1 = relevant, digital und flächendeckend verfügbar
2 = relevant aber nicht digital oder nicht flächendeckend
3 = nicht relevant
Wert wird nach dem Speichern automatisch berechnet.', 'Metadaten', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '8', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'quelle', 'quelle', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Quelle', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '23', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'relevanz', 'relevanz', 'ressources', 'ba', 'bool', '', '', '1', '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT
  column1 AS output, column2 AS value
FROM ( VALUES
  (''ja'', true),
  (''nein'', false)
) AS options', 'Relevanz', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '1', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '9', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'status_id', 'status_id', 'ressources', 'ba', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  id AS value,
  status AS output
FROM
  metadata.update_status
ORDER BY reihenfolge
', 'Update Status', NULL, '', NULL, NULL, 'Wird automatisch gesetzt beim Update der Ressource.', 'Prozesse', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '61', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'style', '', '', '', 'varchar', '', '', '0', NULL, NULL, NULL, '', 'Style', '', 'Style', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '0', '0', NULL, NULL, NULL, NULL, '0', '', '', '', '15', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'subressources', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '49,id:ressource_id,bezeichnung;embedded', 'Teildaten', NULL, '', NULL, NULL, '', 'Prozesse', NULL, '0', '0', NULL, NULL, NULL, NULL, '2', 'download_url', '=', '', '42', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'transform_command', 'transform_command', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Prozessbefehl', NULL, '', NULL, NULL, '', 'Prozesse', NULL, '0', '0', NULL, NULL, NULL, NULL, '2', 'transform_method', '=', 'exec_sql', '59', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'transform_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=metadata_update_outdated&ressource_id=$id&method_only=transform;run', '', NULL, NULL, NULL, NULL, '', 'Prozesse', NULL, '1', '2', NULL, NULL, NULL, NULL, '2', 'transform_method', '!=', '', '58', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'transform_method', 'transform_method', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT
  name AS value,
  beschreibung AS output
FROM
  metadata.transform_methods
ORDER BY reihenfolge', 'Prozessmethode', NULL, '', NULL, NULL, '', 'Prozesse', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '57', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'unpack_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=metadata_update_outdated&ressource_id=$id&method_only=unpack;run', '', NULL, NULL, NULL, NULL, '', 'Prozesse', NULL, '1', '2', NULL, NULL, NULL, NULL, '2', 'unpack_method', '!=', '', '48', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'unpack_method', 'unpack_method', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT
  name AS value,
  beschreibung AS output
FROM
  metadata.unpack_methods
ORDER BY reihenfolge', 'Auspackmethode', NULL, '', NULL, NULL, 'Methode zum Auspacken oder/und Verschieben der Daten in den Importordner. Wenn manuelles kopieren ausgewählt wurde geht der Prozess davon aus, dass die Daten die für den Import benötigt werden schon dort liegen wo sie hingehören.', 'Prozesse', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '46', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'updated_at', 'updated_at', 'ressources', 'ba', 'timestamp', '', '', '1', '1', NULL, NULL, '', 'Time', 'update', 'am', NULL, '', NULL, NULL, '', 'Bearbeitungsvermerke;collapsed', NULL, '1', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '71', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'updated_from', 'updated_from', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'User', 'update', 'geändert von', NULL, '', NULL, NULL, '', 'Bearbeitungsvermerke;collapsed', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '70', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'update_interval', 'update_interval', 'ressources', 'ba', 'interval', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Update-Internval', NULL, '', NULL, NULL, '', 'Prozesse', NULL, '0', '0', NULL, NULL, NULL, NULL, '2', 'auto_update', '=', 't', '39', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'update_logs', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '250,id:ressource_id,update_at;embedded', 'Update logs', NULL, NULL, NULL, NULL, '', 'Protokollierung;collapsed', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '66', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'update_packages_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=metadata_reorder_data_packages&ressource_id=$id;Starte Update', 'Update Pakete', NULL, NULL, NULL, NULL, '', 'Prozesse', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '63', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'update_ressource_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=metadata_update_outdated&ressource_id=$id;Starte Update', 'Update Ressource', NULL, '', NULL, NULL, '', 'Prozesse', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '62', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'update_time', 'update_time', 'ressources', 'ba', 'time', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Update-Zeitpunkt', NULL, NULL, NULL, NULL, '', 'Prozesse', NULL, '1', '0', NULL, NULL, NULL, NULL, '2', 'auto_update', '=', 't', '40', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'url', 'url', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Link', '', 'Ressource-URL', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '22', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'use_for_datapackage', 'use_for_datapackage', 'ressources', 'ba', 'bool', '', '', '1', '1', NULL, NULL, '', 'Checkbox', '', 'Für Datentool', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '1', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '3', '0', '0');

-- Attribut  des Layers 46

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id46, 'von_eneka', 'von_eneka', 'ressources', 'ba', 'bool', '', '', '1', '1', NULL, NULL, '', 'Checkbox', '', 'von ENEKA', NULL, '', NULL, NULL, '', 'Metadaten', NULL, '1', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '4', '0', '0');

-- Layer 47

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Ressourcengruppen', NULL, NULL, NULL, NULL, NULL, '5', vars_group_id_99, 'SELECT
  id,
  gruppe,
  beschreibung
FROM
  metadata.gruppen
WHERE
  true
ORDER bY gruppe', 'gruppen', 'id', NULL, '0', '', 'metadata', NULL, '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', NULL, NULL, NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '1', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', NULL, '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id47;

-- Attribut  des Layers 47

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id47, 'beschreibung', 'beschreibung', 'gruppen', 'gruppen', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Beschreibung', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '3', '0', '0');

-- Attribut  des Layers 47

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id47, 'gruppe', 'gruppe', 'gruppen', 'gruppen', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Gruppenname', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '2', '0', '0');

-- Attribut  des Layers 47

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id47, 'id', 'id', 'gruppen', 'gruppen', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''gruppen_id_seq''::regclass)', 'Text', '', 'ID', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '1', '0', '0');

-- Layer 50

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('ressourcen_layer_tables', NULL, NULL, NULL, NULL, 'Ressourcen Layer Tabellen', '5', vars_group_id_99, 'SELECT
  r.id,
  g.gruppe,
  r.bezeichnung,
  l.layer_id,
  l.alias,
  l.schema,
  l.maintable,
  l.datentyp,
  '''' link_map,
  '''' link_tab,
  '''' link_set
FROM
  metadata.ressources r JOIN
  metadata.gruppen g ON r.gruppe_id = g.id LEFT JOIN
  kvwmap.layer l ON r.layer_id = l.layer_id
WHERE
  r.use_for_datapackage
ORDER BY
  gruppe, bezeichnung', 'ressources', 'id', NULL, '0', '', 'metadata', NULL, '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', NULL, NULL, NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '1', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', NULL, '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id50;

-- Attribut  des Layers 50

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id50, 'alias', 'alias', 'layer', 'l', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Layer', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '5', '0', '0');

-- Attribut  des Layers 50

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id50, 'bezeichnung', 'bezeichnung', 'ressources', 'r', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Bezeichnung', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '3', '0', '0');

-- Attribut  des Layers 50

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id50, 'datentyp', 'datentyp', 'layer', 'l', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  column1 AS output, column2 AS value
FROM ( VALUES
  (''Punkte'', 0),
  (''Linien'', 1),
  (''Polygone'', 2),
  (''Raster'', 3),
  (''Beschriftung'', 4),
  (''Tabelle'', 5),
  (''Kreise'', 6),
  (''Kacheln'', 7),
  (''Diagramme'', 8)
) AS options', 'Datentyp', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '8', '0', '0');

-- Attribut  des Layers 50

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id50, 'gruppe', 'gruppe', 'gruppen', 'g', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'gruppe', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '2', '0', '0');

-- Attribut  des Layers 50

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id50, 'id', 'id', 'ressources', 'r', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''ressources_id_seq''::regclass)', 'Text', '', 'Ressource-ID', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '1', '0', '0');

-- Attribut  des Layers 50

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id50, 'layer_id', 'layer_id', 'layer', 'l', 'int4', '', '', '1', '1', '32', '0', '', 'Text', '', 'Layer-ID', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '4', '0', '0');

-- Attribut  des Layers 50

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id50, 'link_map', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=show_layer_in_map&layer_id=$layer_id;Karte;no_new_window', 'Karte', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '2', 'datentyp', '!=', '5', '9', '0', '0');

-- Attribut  des Layers 50

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id50, 'link_set', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layereditor&selected_layer_id=$layer_id;Einstellung;no_new_window', 'Einstellung', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '11', '0', '0');

-- Attribut  des Layers 50

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id50, 'link_tab', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=$layer_id;Daten;no_new_window', 'Daten', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '10', '0', '0');

-- Attribut  des Layers 50

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id50, 'maintable', 'maintable', 'layer', 'l', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Tabellenname', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '7', '0', '0');

-- Attribut  des Layers 50

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id50, 'schema', 'schema', 'layer', 'l', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Schemaname', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '6', '0', '0');

-- Layer 48

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Ressourcenteilbereiche', NULL, NULL, NULL, NULL, NULL, '5', vars_group_id_99, 'SELECT
  id,
  subressource_id,
  name,
  von,
  bis,
  step
FROM
  metadata.subressourceranges
WHERE
  true', 'subressourceranges', 'id', NULL, '0', '', 'metadata', NULL, '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', NULL, NULL, NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '1', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', NULL, '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id48;

-- Attribut  des Layers 48

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id48, 'bis', 'bis', 'subressourceranges', 'subressourceranges', 'int4', '', '', '1', '1', '32', '0', '', 'Zahl', '', 'bis', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '5', '0', '0');

-- Attribut  des Layers 48

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id48, 'id', 'id', 'subressourceranges', 'subressourceranges', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''subressourceranges_id_seq''::regclass)', 'Text', '', '', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '1', '0', '0');

-- Attribut  des Layers 48

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id48, 'name', 'name', 'subressourceranges', 'subressourceranges', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'name', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '3', '0', '0');

-- Attribut  des Layers 48

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id48, 'step', 'step', 'subressourceranges', 'subressourceranges', 'int4', '', '', '1', '1', '32', '0', '', 'Zahl', '', 'Schritt', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '6', '0', '0');

-- Attribut  des Layers 48

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id48, 'subressource_id', 'subressource_id', 'subressourceranges', 'subressourceranges', 'int4', '', '', '1', '0', '32', '0', '', 'Text', '', '', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '2', '0', '0');

-- Attribut  des Layers 48

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id48, 'von', 'von', 'subressourceranges', 'subressourceranges', 'int4', '', '', '1', '1', '32', '0', '', 'Zahl', '', 'von', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '4', '0', '0');

-- Layer 49

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Ressourcenteile', NULL, NULL, NULL, NULL, NULL, '5', vars_group_id_99, 'SELECT
  id,
  ressource_id,
  bezeichnung,
  download_url,
  '''' ressourceranges
FROM
  metadata.subressources ba
WHERE
  true', 'subressources', 'id', NULL, '0', '', 'metadata', NULL, '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', NULL, NULL, NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '1', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', NULL, '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id49;

-- Attribut  des Layers 49

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id49, 'bezeichnung', 'bezeichnung', 'subressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Bezeichnung', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '3', '0', '0');

-- Attribut  des Layers 49

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id49, 'download_url', 'download_url', 'subressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Link', '', 'Download-URL', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '4', '0', '0');

-- Attribut  des Layers 49

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id49, 'id', 'id', 'subressources', 'ba', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''subressources_id_seq''::regclass)', 'Text', '', 'ID', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '1', '0', '0');

-- Attribut  des Layers 49

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id49, 'ressource_id', 'ressource_id', 'subressources', 'ba', 'int4', '', '', '1', '0', '32', '0', '', 'Text', '', 'Datensatz', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '2', '0', '0');

-- Attribut  des Layers 49

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id49, 'ressourceranges', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '48,id:subressource_id,name Beginn von Ende bis Schritt step;embedded', 'Bereiche', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '5', '0', '0');

-- Layer 52

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('update_logs', NULL, NULL, NULL, NULL, 'Update Logs', '5', vars_group_id_99, 'SELECT
  l.id,
  l.update_at,
  l.abbruch_status_id,
  s.status,
  l.msg,
  l.ressource_id,
  r.bezeichnung
FROM
  metadata.update_logs l JOIN
  metadata.ressources r ON l.ressource_id = r.id JOIN
  metadata.update_status s ON l.abbruch_status_id = s.id
WHERE
  1 = 1
ORDER BY
  update_at DESC', 'update_logs', 'id', NULL, '0', '', 'metadata', NULL, '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', NULL, NULL, NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '1', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', NULL, '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id52;

-- Attribut  des Layers 52

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id52, 'abbruch_status_id', 'abbruch_status_id', 'update_logs', 'l', 'int4', '', '', '1', '1', '32', '0', '', 'Text', '', 'ID', NULL, NULL, NULL, NULL, '', '', NULL, '1', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '6', '0', '0');

-- Attribut  des Layers 52

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id52, 'bezeichnung', 'bezeichnung', 'ressources', 'r', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Ressource', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '3', '0', '0');

-- Attribut  des Layers 52

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id52, 'id', 'id', 'update_logs', 'l', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''update_log_id_seq''::regclass)', 'Text', '', 'ID', NULL, NULL, NULL, NULL, '', '', NULL, '1', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '2', '0', '0');

-- Attribut  des Layers 52

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id52, 'msg', 'msg', 'update_logs', 'l', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Meldung', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '7', '0', '0');

-- Attribut  des Layers 52

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id52, 'ressource_id', 'ressource_id', 'update_logs', 'l', 'int4', '', '', '1', '0', '32', '0', '', 'Text', '', 'ID', NULL, NULL, NULL, NULL, '', '', NULL, '1', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '4', '0', '0');

-- Attribut  des Layers 52

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id52, 'status', 'status', 'update_status', 's', 'varchar', '', '', '1', '0', NULL, NULL, '', 'Text', '', 'Abbruch Status', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '5', '0', '0');

-- Attribut  des Layers 52

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id52, 'update_at', 'update_at', 'update_logs', 'l', 'timestamp', '', '', '1', '0', NULL, NULL, 'now()', 'Text', '', 'update am', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', '', '', '', '1', '0', '0');

-- Replace attribute options for Layer 19
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=19', CONCAT('layer_id=', vars_last_layer_id19)) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=19( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '19,', CONCAT(vars_last_layer_id19, ',')) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '19,%';

-- Replace attribute options for Layer 25
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=25', CONCAT('layer_id=', vars_last_layer_id25)) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=25( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '25,', CONCAT(vars_last_layer_id25, ',')) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '25,%';

-- Replace attribute options for Layer 26
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=26', CONCAT('layer_id=', vars_last_layer_id26)) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=26( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '26,', CONCAT(vars_last_layer_id26, ',')) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '26,%';

-- Replace attribute options for Layer 27
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=27', CONCAT('layer_id=', vars_last_layer_id27)) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=27( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '27,', CONCAT(vars_last_layer_id27, ',')) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '27,%';

-- Replace attribute options for Layer 28
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=28', CONCAT('layer_id=', vars_last_layer_id28)) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=28( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '28,', CONCAT(vars_last_layer_id28, ',')) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '28,%';

-- Replace attribute options for Layer 45
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=45', CONCAT('layer_id=', vars_last_layer_id45)) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=45( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '45,', CONCAT(vars_last_layer_id45, ',')) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '45,%';

-- Replace attribute options for Layer 46
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=46', CONCAT('layer_id=', vars_last_layer_id46)) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=46( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '46,', CONCAT(vars_last_layer_id46, ',')) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '46,%';

-- Replace attribute options for Layer 47
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=47', CONCAT('layer_id=', vars_last_layer_id47)) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=47( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '47,', CONCAT(vars_last_layer_id47, ',')) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '47,%';

-- Replace attribute options for Layer 50
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=50', CONCAT('layer_id=', vars_last_layer_id50)) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=50( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '50,', CONCAT(vars_last_layer_id50, ',')) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '50,%';

-- Replace attribute options for Layer 48
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=48', CONCAT('layer_id=', vars_last_layer_id48)) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=48( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '48,', CONCAT(vars_last_layer_id48, ',')) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '48,%';

-- Replace attribute options for Layer 49
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=49', CONCAT('layer_id=', vars_last_layer_id49)) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=49( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '49,', CONCAT(vars_last_layer_id49, ',')) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '49,%';

-- Replace attribute options for Layer 52
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=52', CONCAT('layer_id=', vars_last_layer_id52)) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=52( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '52,', CONCAT(vars_last_layer_id52, ',')) WHERE layer_id IN (vars_last_layer_id19, vars_last_layer_id25, vars_last_layer_id26, vars_last_layer_id27, vars_last_layer_id28, vars_last_layer_id45, vars_last_layer_id46, vars_last_layer_id47, vars_last_layer_id50, vars_last_layer_id48, vars_last_layer_id49, vars_last_layer_id52) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '52,%';

END $$