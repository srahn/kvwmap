
-- Layerdump aus kvwmap vom 13.10.2026 08:21:56
-- Achtung: Die Datenbank in die der Dump eingespielt wird, sollte die gleiche Migrationsversion haben,
-- wie die Datenbank aus der exportiert wurde! Anderenfalls kann es zu Fehlern bei der Ausführung des SQL kommen.

DO $$
	DECLARE 
		vars_connection_id integer;
		
		vars_group_id_70 integer;
		
		vars_last_layer_id270 integer;
		vars_last_layer_id271 integer;
		vars_last_layer_id272 integer;
		vars_last_layer_id276 integer;
		vars_last_layer_id277 integer;
		vars_last_layer_id278 integer;
		vars_last_layer_id279 integer;
		vars_last_layer_id280 integer;
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
					('Ressourcen', NULL, NULL, NULL, NULL, NULL, NULL, 'f', NULL)RETURNING id INTO vars_group_id_70;

-- Layer 270

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment", "cluster_option") 
				VALUES 
					('Attribute', NULL, NULL, NULL, NULL, NULL, '5', vars_group_id_70, 'SELECT
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
  bezeichnung', 'attributes', 'id', NULL, '0', '', 'metadata', '', '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', NULL, NULL, NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '1', NULL, '0', '1', NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', NULL, '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL, '1')RETURNING layer_id INTO vars_last_layer_id270;

-- Attribut  des Layers 270

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id270, 'beschreibung', 'beschreibung', 'attributes', 'attributes', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Beschreibung', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '5', '0', '0');

-- Attribut  des Layers 270

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id270, 'bezeichnung', 'bezeichnung', 'attributes', 'attributes', 'varchar', '', '', '1', '0', NULL, NULL, '', 'Text', '', 'Bezeichnung', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '3', '0', '0');

-- Attribut  des Layers 270

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id270, 'created_at', 'created_at', 'attributes', 'attributes', 'timestamp', '', '', '1', '0', NULL, NULL, 'now()', 'Time', 'insert', 'am', NULL, '', NULL, NULL, '', '', NULL, '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '13', '0', '0');

-- Attribut  des Layers 270

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id270, 'created_from', 'created_from', 'attributes', 'attributes', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', 'insert', 'erstellt von', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '12', '0', '0');

-- Attribut  des Layers 270

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id270, 'datentyp', 'datentyp', 'attributes', 'attributes', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Datentyp', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '6', '0', '0');

-- Attribut  des Layers 270

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id270, 'defaultvalue', 'defaultvalue', 'attributes', 'attributes', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Defaultwert', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '10', '0', '0');

-- Attribut  des Layers 270

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id270, 'id', 'id', 'attributes', 'attributes', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''attributes_id_seq''::regclass)', 'Text', '', 'ID', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, '1', NULL, NULL, '1', NULL, '1', '0', '0');

-- Attribut  des Layers 270

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id270, 'mandatory', 'mandatory', 'attributes', 'attributes', 'bool', '', '', '1', '1', NULL, NULL, '', 'Checkbox', '', 'Pflichtattribut', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '11', '0', '0');

-- Attribut  des Layers 270

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id270, 'maxvalue', 'maxvalue', 'attributes', 'attributes', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Maximalwert', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '9', '0', '0');

-- Attribut  des Layers 270

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id270, 'minvalue', 'minvalue', 'attributes', 'attributes', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Minimalwert', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '8', '0', '0');

-- Attribut  des Layers 270

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id270, 'name', 'name', 'attributes', 'attributes', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Spaltenname', NULL, '', NULL, NULL, 'Name der Spalte in der Tabelle', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '4', '0', '0');

-- Attribut  des Layers 270

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id270, 'ressource_id', 'ressource_id', 'attributes', 'attributes', 'int4', '', '', '1', '0', '32', '0', '', 'Text', '', 'Ressource-ID', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '2', '0', '0');

-- Attribut  des Layers 270

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id270, 'updated_at', 'updated_at', 'attributes', 'attributes', 'timestamp', '', '', '1', '1', NULL, NULL, '', 'Time', 'update', 'am', NULL, '', NULL, NULL, '', '', NULL, '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '15', '0', '0');

-- Attribut  des Layers 270

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id270, 'updated_from', 'updated_from', 'attributes', 'attributes', 'varchar', '', '', '1', '1', NULL, NULL, '', 'User', 'update', 'geändert von', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '14', '0', '0');

-- Attribut  des Layers 270

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id270, 'valuelist', 'valuelist', 'attributes', 'attributes', '_varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Werteliste', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '7', '0', '0');

-- Layer 271

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment", "cluster_option") 
				VALUES 
					('Datenabstammung', NULL, NULL, NULL, NULL, NULL, '5', vars_group_id_70, 'SELECT
  l.id,
  l.source_id,
  l.target_id,
  '''' AS source_link,
  '''' AS target_link
FROM
  metadata.lineages l
WHERE
  true', 'lineages', 'id', NULL, '0', '', 'metadata', '', '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', NULL, NULL, NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '1', NULL, '0', '9', NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', NULL, '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL, '1')RETURNING layer_id INTO vars_last_layer_id271;

-- Attribut  des Layers 271

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id271, 'id', 'id', 'lineages', 'l', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''lineages_id_seq''::regclass)', 'Text', '', 'ID', '', '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '1', '0', '0');

-- Attribut  des Layers 271

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id271, 'source_id', 'source_id', 'lineages', 'l', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  r.id AS value,
  concat_ws('' - '', g.gruppe, r.bezeichnung) AS output
FROM
  metadata.ressources r LEFT JOIN
  metadata.gruppen g ON r.gruppe_id = g.id
ORDER BY
  g.gruppe,
  r.bezeichnung', 'Quelle', '', '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '5', '0', '0');

-- Attribut  des Layers 271

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id271, 'source_link', ''''' ', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=276&value_id=$source_id&operator_id==;anzeigen;no_new_window', '', '', '', NULL, NULL, '', '', '', '1', '2', NULL, NULL, NULL, NULL, '1', NULL, '6', '0', '0');

-- Attribut  des Layers 271

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id271, 'target_id', 'target_id', 'lineages', 'l', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  r.id AS value,
  concat_ws('' - '', g.gruppe, r.bezeichnung) AS output
FROM
  metadata.ressources r LEFT JOIN
  metadata.gruppen g ON r.gruppe_id = g.id
ORDER BY
  g.gruppe,
  r.bezeichnung', 'Ziel', '', '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '2', '0', '0');

-- Attribut  des Layers 271

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id271, 'target_link', ''''' ', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=276&value_id=$target_id&operator_id==;anzeigen;no_new_window', '', '', '', NULL, NULL, '', '', '', '1', '2', NULL, NULL, NULL, NULL, '1', NULL, '3', '0', '0');

-- Layer 272

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment", "cluster_option") 
				VALUES 
					('Datenformate', NULL, NULL, NULL, NULL, NULL, '5', vars_group_id_70, 'SELECT
  id,
  format
FROM
  metadata.formate
WHERE
  true
ORDER BY
  format', 'formate', 'id', NULL, '0', '', 'metadata', '', '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', NULL, NULL, NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '1', NULL, '0', '8', NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', NULL, '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL, '1')RETURNING layer_id INTO vars_last_layer_id272;

-- Attribut  des Layers 272

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id272, 'format', 'format', 'formate', 'formate', 'varchar', '', 'UNIQUE', '1', '1', NULL, NULL, '', 'Text', '', 'Format', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '2', '0', '0');

-- Attribut  des Layers 272

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id272, 'id', 'id', 'formate', 'formate', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''formate_id_seq''::regclass)', 'Text', '', 'ID', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '1', '0', '0');

-- Layer 276

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment", "cluster_option") 
				VALUES 
					('Ressourcen', '', '', NULL, NULL, NULL, '5', vars_group_id_70, 'SELECT
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
  ba.gruppe_id,
  ba.bezeichnung, ba.gebietseinheit_id, 
  ba.hinweise_auf,
  ba.beschreibung, 
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
  metadata.ressources ba
WHERE
  1 = 1', 'ressources', 'id', '', '0', '', 'metadata', '', '/var/www/data/bilder/', '', '', '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', '', '', NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 'f', '1', NULL, '0', '2', NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', '', '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL, '1')RETURNING layer_id INTO vars_last_layer_id276;

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'ampel_id', 'ampel_id', 'ressources', 'ba', 'int4', '', '', '1', '1', '32', '0', '', 'Radiobutton', 'SELECT
  id AS value,
  farbe AS output
FROM
  metadata.ampel
ORDER BY reihenfolge;horizontal ', 'Ampel', '', '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '6', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'datenguete_id', 'datenguete_id', 'ressources', 'ba', 'int4', '', '', '1', '1', '32', '0', '', 'Radiobutton', 'SELECT
  id AS value,
  guete AS output
FROM
  metadata.datenguete
ORDER BY id;horizontal ', 'Datengüte', '', '', NULL, NULL, '', 'Metadaten', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '8', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'layer_id', 'layer_id', 'ressources', 'ba', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  layer_id AS value,
  COALESCE(alias, name) AS output
FROM
  kvwmap.layer
ORDER BY
  COALESCE(alias, name)', 'Layer', '', '', NULL, NULL, '', 'Visualisierung', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '64', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'update_interval', 'update_interval', 'ressources', 'ba', 'interval', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Update-Internval', '', '', NULL, NULL, '', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "t", "operator": "=", "attribute": "auto_update"}]}', '39', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'digital', 'digital', 'ressources', 'ba', 'bool', '', '', '1', '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT
  column1 AS output, column2 AS value
FROM ( VALUES
  (''ja'', true),
  (''nein'', false)
) AS options', 'digital verfügbar', '', '', NULL, NULL, '', 'Metadaten', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '11', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'documents', 'documents', 'ressources', 'ba', '_varchar', '', '', '1', '1', NULL, NULL, '', 'Dokument', '', 'Dokumente', '', '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '29', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'download_method', 'download_method', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT
  name AS value,
  beschreibung AS output
FROM
  metadata.download_methods
ORDER BY reihenfolge', 'Download-Methode', '', '', NULL, NULL, 'Wenn die Importmethode WFS ist und kein Tabellenname angegeben wird, werden statt dessen alle FeatureTypen des WFS runtergeladen und in gml-Dateien geschrieben.', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '43', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'download_path', 'download_path', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Download-Verzeichnis', '', '', NULL, NULL, 'relativ zu /var/www/data/ressources/', 'Prozesse', '', '1', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "download_method"}]}', '44', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'download_url', 'download_url', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Link', '', 'Download-URL', '', '', NULL, NULL, '', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '41', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'flaechendeckend', 'flaechendeckend', 'ressources', 'ba', 'bool', '', '', '1', '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT
  column1 AS output, column2 AS value
FROM ( VALUES
  (''ja'', true),
  (''nein'', false)
) AS options', 'flächendeckend verfügbar', '', '', NULL, NULL, '', 'Metadaten', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '12', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'format_id', 'format_id', 'ressources', 'ba', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  id AS value,
  format AS output
FROM
  metadata.formate
ORDER BY format;layer_id=272 embedded', 'Format', '', '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '20', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'gebietseinheit_id', 'gebietseinheit_id', 'ressources', 'ba', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  id AS value,
  abk AS output
FROM
  metadata.gebietseinheiten
ORDER BY reihenfolge
', 'Gebietseinheit', '', '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '7', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'github', 'github', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Github', '', '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '25', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'hinweise_auf', 'hinweise_auf', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Hinweise auf', '', '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '16', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'id', 'id', 'ressources', 'ba', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''ressources_id_seq''::regclass)', 'Text', '', 'ID', '', '', NULL, NULL, '', 'Metadaten', '', '1', '0', NULL, '1', NULL, NULL, '1', NULL, '2', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'import_file', 'import_file', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Importdatei', '', '', NULL, NULL, 'Bei Shape mit ogr2ogr in Postgres der Name der Shape-Datei ohne Dateiendung. Wenn keine angegeben wird, werden alle Dateien im Import-Verzeichnis eingelesen.', 'Prozesse', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '50', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'quelle', 'quelle', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Quelle', '', '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '23', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'relevanz', 'relevanz', 'ressources', 'ba', 'bool', '', '', '1', '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT
  column1 AS output, column2 AS value
FROM ( VALUES
  (''ja'', true),
  (''nein'', false)
) AS options', 'Relevanz', '', '', NULL, NULL, '', 'Metadaten', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '10', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'status_id', 'status_id', 'ressources', 'ba', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  id AS value,
  status AS output
FROM
  metadata.update_status
ORDER BY reihenfolge
', 'Update Status', '', '', NULL, NULL, 'Wird automatisch gesetzt beim Update der Ressource.', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '61', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'transform_command', 'transform_command', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Prozessbefehl', '', '', NULL, NULL, '', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "exec_sql", "operator": "=", "attribute": "transform_method"}]}', '59', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'transform_method', 'transform_method', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT
  name AS value,
  beschreibung AS output
FROM
  metadata.transform_methods
ORDER BY reihenfolge', 'Prozessmethode', '', '', NULL, NULL, '', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '57', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'datenquellen', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '271,id:target_id,source_id;embedded', 'Datenquellen', '', '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '27', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'datenziele', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '271,id:source_id,target_id;embedded', 'Datenziele', '', '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '28', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'gruppe_id', 'gruppe_id', 'ressources', 'ba', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  id AS value,
  gruppe AS output
FROM
  metadata.gruppen
ORDER BY id;layer_id=277 embedded', 'gruppe', '', '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '1', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'subressources', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '280,id:ressource_id,bezeichnung;embedded', 'Teildaten', '', '', NULL, NULL, '', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "=", "attribute": "download_url"}]}', '42', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'prioritaet', '', '', '', 'int4', '', '', '0', NULL, NULL, NULL, '', 'Textfeld', '', 'Priorität', '', '', NULL, NULL, '1 = relevant, digital und flächendeckend verfügbar
2 = relevant aber nicht digital oder nicht flächendeckend
3 = nicht relevant
Wert wird nach dem Speichern automatisch berechnet.', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '9', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'inquiries_responsible', 'inquiries_responsible', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Für Anfragen verantwortlich', '', '', NULL, NULL, 'Person oder Einrichtung die die Rückfragen an den Datenlieferanten stellen soll und die Rückmeldungen hier notiert.', 'Rückfragen;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "t", "operator": "=", "attribute": "any_required"}]}', '33', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'unpack_method', 'unpack_method', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT
  name AS value,
  beschreibung AS output
FROM
  metadata.unpack_methods
ORDER BY reihenfolge', 'Auspackmethode', '', '', NULL, NULL, 'Methode zum Auspacken oder/und Verschieben der Daten in den Importordner. Wenn manuelles kopieren ausgewählt wurde geht der Prozess davon aus, dass die Daten die für den Import benötigt werden schon dort liegen wo sie hingehören.', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '46', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'import_table', 'import_table', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Importtabelle', '', '', NULL, NULL, 'Name der Tabelle im Import-Schema in die die Daten importiert werden sollen, falls das nicht in der Importmethode schon definiert wurde. Alles klein schreiben.
Wenn die Importmethode WFS ist und kein Tabellenname angegeben wird, werden statt dessen alle GML-Dateien aus dem Import-Ordner eingelesen  und die Tabellen so wie die Dateien benannt.', 'Prozesse', '', '1', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "import_method"}]}', '52', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'auto_update', 'auto_update', 'ressources', 'ba', 'bool', '', '', '1', '1', NULL, NULL, '', 'Checkbox', '', 'Auto-Update', '', '', NULL, NULL, 'Ob der Datensatz automatisch geupdated werden soll. Wirkt im Zusammenhang mit dem Update-Interval und dem Datum des letzten Updates. Status muss leer oder Uptodate sein. Wenn Auto-Update gesetzt und das Interval leer ist wird nur ein mal aktualisiert und das Update-Datum gesetzt.', 'Prozesse', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '38', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'download_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=metadata_update_outdated&ressource_id=$id&method_only=download;run', '', '', '', NULL, NULL, '', 'Prozesse', '', '1', '2', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "download_method"}]}', '45', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'import_filter', 'import_filter', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Importfilter', '', '', NULL, NULL, 'Bedingung, die beim Import verwendet werden soll, z.B. bei SQL-Import eine WHERE-Klausel die passend zum Import-Statement wahr oder falsch liefert.', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "import_method"}]}', '53', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'import_layer', 'import_layer', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Importlayer', '', '', NULL, NULL, 'Name des Layers, der importiert werden soll. Beim GML-Import z.B. der Name des Featuretyps der in die Tabelle importiert werden soll. Beim CSV-Import der Name der CSV-Datei ohne Endung', 'Prozesse', '', '1', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "import_method"}]}', '54', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'import_epsg', 'import_epsg', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT
  srid AS value,
  alias AS output
FROM
  public.spatial_ref_sys_alias
ORDER BY srid
', 'Import-EPSG-Code', '', '', NULL, NULL, 'Koordinatenreferenzsystem in dem die Quelledaten eingeladen werden sollen. Zur Auswahl stehen nur die in dieser Anwendung vom Administrator eingestellten Werte.', 'Prozesse', '', '1', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "import_method"}]}', '55', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'aktualitaet', 'aktualitaet', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Aktualität', '', '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '21', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'ansprechperson', 'ansprechperson', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Ansprechperson', '', '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '19', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'any_required', '', '', '', 'bool', '', '', '0', NULL, NULL, NULL, '', 'Text', '', '', '', '', NULL, NULL, '', 'Rückfragen;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '0', NULL, '32', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'attribute', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '270,id:ressource_id,bezeichnung;embedded', 'Attribute', '', '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '26', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'bemerkung_prioritaet', 'bemerkung_prioritaet', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Bemerkung zur Priorität', '', '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '14', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'beschreibung', 'beschreibung', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Beschreibung', '', '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '17', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'bezeichnung', 'bezeichnung', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Bezeichnung der Datenquelle', '', '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '5', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'check_required', 'check_required', 'ressources', 'ba', 'bool', '', '', '1', '1', NULL, NULL, '', 'Checkbox', '', 'Prüfung der Daten erforderlich', '', '', NULL, NULL, 'Prüfungen die GDI-Service vornehmen muss bevor die Ressource zur Verwendung im Datentool aufgenommen wird.', 'Rückfragen;collapsed', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '31', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'created_at', 'created_at', 'ressources', 'ba', 'timestamp', '', '', '1', '1', NULL, NULL, '', 'Time', 'insert', 'am', '', '', NULL, NULL, '', 'Bearbeitungsvermerke;collapsed', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '69', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'created_from', 'created_from', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'User', 'insert', 'erzeugt von', '', '', NULL, NULL, '', 'Bearbeitungsvermerke;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '68', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'dest_path', 'dest_path', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Zielverzeichnis', '', '', NULL, NULL, 'relativ zu /var/www/data/ressources/', 'Prozesse', '', '1', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "unpack_method"}]}', '47', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'error_msg', 'error_msg', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Fehlermeldung', '', '', NULL, NULL, 'Wird automatisch eingetragen wenn ein Fehler beim Download, Entpacken, Importieren oder Transformieren der Ressource auftritt.', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "-1", "operator": "=", "attribute": "status_id"}]}', '60', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'import_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=metadata_update_outdated&ressource_id=$id&method_only=import;run', '', '', '', NULL, NULL, '', 'Prozesse', '', '1', '2', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "import_method"}]}', '56', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'import_method', 'import_method', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT
  name AS value,
  beschreibung AS output
FROM
  metadata.import_methods
ORDER BY reihenfolge', 'Importmethode', '', '', NULL, NULL, 'Bei der Methode WFS werden wenn keine Import-Tabelle angegeben ist alle GML-Dateien aus dem import-Verzeichnis eingelesen.', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '49', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'import_schema', 'import_schema', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Importschema', '', '', NULL, NULL, 'Name des Schemas in die die Daten importiert werden sollen. Wird hier nichts angegeben wird automatisch in das Schema import importiert.', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "import_method"}]}', '51', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'inquiries', 'inquiries', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Anfragen', '', '', NULL, NULL, '', 'Rückfragen;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "t", "operator": "=", "attribute": "any_required"}]}', '35', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'inquiries_required', 'inquiries_required', 'ressources', 'ba', 'bool', '', '', '1', '1', NULL, NULL, '', 'Checkbox', '', 'Anfragen erforderlich', '', '', NULL, NULL, '', 'Rückfragen;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '30', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'inquiries_responses', 'inquiries_responses', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Rückmeldungen', '', '', NULL, NULL, 'Rückmeldungen zu Rückfragen zu den Daten', 'Rückfragen;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "t", "operator": "=", "attribute": "any_required"}]}', '36', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'inquiries_to', 'inquiries_to', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Anfrage an', '', '', NULL, NULL, 'An wen sich die Anfrage richten soll', 'Rückfragen;collapsed', '', '1', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "t", "operator": "=", "attribute": "any_required"}]}', '34', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'last_updated_at', 'last_updated_at', 'ressources', 'ba', 'date', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'letzter Update', '', '', NULL, NULL, '', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '37', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'layer_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layereditor&selected_layer_id=$layer_id;zum Layer', '', '', '', NULL, NULL, '', 'Visualisierung', '', '1', '2', NULL, NULL, NULL, NULL, '1', NULL, '65', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'pack_logs', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '275,id:ressource_id,packed_at;embedded', '', '', '', NULL, NULL, '', 'Protokollierung;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '67', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'transform_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=metadata_update_outdated&ressource_id=$id&method_only=transform;run', '', '', '', NULL, NULL, '', 'Prozesse', '', '1', '2', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "transform_method"}]}', '58', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'unpack_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=metadata_update_outdated&ressource_id=$id&method_only=unpack;run', '', '', '', NULL, NULL, '', 'Prozesse', '', '1', '2', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "unpack_method"}]}', '48', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'updated_at', 'updated_at', 'ressources', 'ba', 'timestamp', '', '', '1', '1', NULL, NULL, '', 'Time', 'update', 'am', '', '', NULL, NULL, '', 'Bearbeitungsvermerke;collapsed', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '71', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'updated_from', 'updated_from', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'User', 'update', 'geändert von', '', '', NULL, NULL, '', 'Bearbeitungsvermerke;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '70', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'update_logs', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '281,id:ressource_id,update_at;embedded', 'Update logs', '', '', NULL, NULL, '', 'Protokollierung;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '66', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'update_packages_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=metadata_reorder_data_packages&ressource_id=$id;Starte Update', 'Update Pakete', '', '', NULL, NULL, '', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '63', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'update_ressource_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=metadata_update_outdated&ressource_id=$id;Starte Update', 'Update Ressource', '', '', NULL, NULL, '', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '62', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'update_time', 'update_time', 'ressources', 'ba', 'time', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Update-Zeitpunkt', '', '', NULL, NULL, '', 'Prozesse', '', '1', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "t", "operator": "=", "attribute": "auto_update"}]}', '40', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'url', 'url', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Link', '', 'Ressource-URL', '', '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '22', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'use_for_datapackage', 'use_for_datapackage', 'ressources', 'ba', 'bool', '', '', '1', '1', NULL, NULL, '', 'Checkbox', '', 'Für Datentool', '', '', NULL, NULL, '', 'Metadaten', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '3', '0', '0');

-- Attribut  des Layers 276

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id276, 'von_eneka', 'von_eneka', 'ressources', 'ba', 'bool', '', '', '1', '1', NULL, NULL, '', 'Checkbox', '', 'Für EA verwenden', '', '', NULL, NULL, '', 'Metadaten', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '4', '0', '0');

-- Layer 277

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment", "cluster_option") 
				VALUES 
					('Ressourcengruppen', NULL, NULL, NULL, NULL, NULL, '5', vars_group_id_70, 'SELECT
  id,
  gruppe,
  beschreibung
FROM
  metadata.gruppen
WHERE
  true
ORDER bY gruppe', 'gruppen', 'id', NULL, '0', '', 'metadata', '', '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', NULL, NULL, NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '1', NULL, '0', '10', NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', NULL, '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL, '1')RETURNING layer_id INTO vars_last_layer_id277;

-- Attribut  des Layers 277

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id277, 'beschreibung', 'beschreibung', 'gruppen', 'gruppen', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Beschreibung', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '3', '0', '0');

-- Attribut  des Layers 277

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id277, 'gruppe', 'gruppe', 'gruppen', 'gruppen', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Gruppenname', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '2', '0', '0');

-- Attribut  des Layers 277

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id277, 'id', 'id', 'gruppen', 'gruppen', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''gruppen_id_seq''::regclass)', 'Text', '', 'ID', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '1', '0', '0');

-- Layer 278

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment", "cluster_option") 
				VALUES 
					('ressourcen_layer_tables', NULL, NULL, NULL, NULL, 'Ressourcen Layer Tabellen', '5', vars_group_id_70, 'SELECT
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
  gruppe, bezeichnung', 'ressources', 'id', NULL, '0', '', 'metadata', NULL, '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', NULL, NULL, NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '1', NULL, '0', '11', NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', NULL, '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL, '1')RETURNING layer_id INTO vars_last_layer_id278;

-- Attribut  des Layers 278

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id278, 'alias', 'alias', 'layer', 'l', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Layer', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '5', '0', '0');

-- Attribut  des Layers 278

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id278, 'bezeichnung', 'bezeichnung', 'ressources', 'r', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Bezeichnung', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '3', '0', '0');

-- Attribut  des Layers 278

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id278, 'datentyp', 'datentyp', 'layer', 'l', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
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
) AS options', 'Datentyp', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '8', '0', '0');

-- Attribut  des Layers 278

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id278, 'gruppe', 'gruppe', 'gruppen', 'g', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'gruppe', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '2', '0', '0');

-- Attribut  des Layers 278

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id278, 'id', 'id', 'ressources', 'r', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''ressources_id_seq''::regclass)', 'Text', '', 'Ressource-ID', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '1', '0', '0');

-- Attribut  des Layers 278

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id278, 'layer_id', 'layer_id', 'layer', 'l', 'int4', '', '', '1', '1', '32', '0', '', 'Text', '', 'Layer-ID', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '4', '0', '0');

-- Attribut  des Layers 278

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id278, 'link_map', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=show_layer_in_map&layer_id=$layer_id;Karte;no_new_window', 'Karte', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "5", "operator": "!=", "attribute": "datentyp"}]}', '9', '0', '0');

-- Attribut  des Layers 278

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id278, 'link_set', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layereditor&selected_layer_id=$layer_id;Einstellung;no_new_window', 'Einstellung', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '11', '0', '0');

-- Attribut  des Layers 278

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id278, 'link_tab', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=$layer_id;Daten;no_new_window', 'Daten', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '10', '0', '0');

-- Attribut  des Layers 278

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id278, 'maintable', 'maintable', 'layer', 'l', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Tabellenname', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '7', '0', '0');

-- Attribut  des Layers 278

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id278, 'schema', 'schema', 'layer', 'l', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Schemaname', NULL, NULL, NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '6', '0', '0');

-- Layer 279

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment", "cluster_option") 
				VALUES 
					('Ressourcenteilbereiche', NULL, NULL, NULL, NULL, NULL, '5', vars_group_id_70, 'SELECT
  id,
  subressource_id,
  name,
  von,
  bis,
  step
FROM
  metadata.subressourceranges
WHERE
  true', 'subressourceranges', 'id', NULL, '0', '', 'metadata', '', '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', NULL, NULL, NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '1', NULL, '0', '3', NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', NULL, '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL, '1')RETURNING layer_id INTO vars_last_layer_id279;

-- Attribut  des Layers 279

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id279, 'bis', 'bis', 'subressourceranges', 'subressourceranges', 'int4', '', '', '1', '1', '32', '0', '', 'Zahl', '', 'bis', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '5', '0', '0');

-- Attribut  des Layers 279

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id279, 'id', 'id', 'subressourceranges', 'subressourceranges', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''subressourceranges_id_seq''::regclass)', 'Text', '', '', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '1', '0', '0');

-- Attribut  des Layers 279

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id279, 'name', 'name', 'subressourceranges', 'subressourceranges', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'name', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '3', '0', '0');

-- Attribut  des Layers 279

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id279, 'step', 'step', 'subressourceranges', 'subressourceranges', 'int4', '', '', '1', '1', '32', '0', '', 'Zahl', '', 'Schritt', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '6', '0', '0');

-- Attribut  des Layers 279

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id279, 'subressource_id', 'subressource_id', 'subressourceranges', 'subressourceranges', 'int4', '', '', '1', '0', '32', '0', '', 'Text', '', '', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '2', '0', '0');

-- Attribut  des Layers 279

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id279, 'von', 'von', 'subressourceranges', 'subressourceranges', 'int4', '', '', '1', '1', '32', '0', '', 'Zahl', '', 'von', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '4', '0', '0');

-- Layer 280

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment", "cluster_option") 
				VALUES 
					('Ressourcenteile', NULL, NULL, NULL, NULL, NULL, '5', vars_group_id_70, 'SELECT
  id,
  ressource_id,
  bezeichnung,
  download_url,
  '''' ressourceranges
FROM
  metadata.subressources ba
WHERE
  true', 'subressources', 'id', NULL, '0', '', 'metadata', '', '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', NULL, NULL, NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '1', NULL, '0', '4', NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', NULL, '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL, '1')RETURNING layer_id INTO vars_last_layer_id280;

-- Attribut  des Layers 280

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id280, 'bezeichnung', 'bezeichnung', 'subressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Bezeichnung', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '3', '0', '0');

-- Attribut  des Layers 280

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id280, 'download_url', 'download_url', 'subressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Link', '', 'Download-URL', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '4', '0', '0');

-- Attribut  des Layers 280

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id280, 'id', 'id', 'subressources', 'ba', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''subressources_id_seq''::regclass)', 'Text', '', 'ID', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '1', '0', '0');

-- Attribut  des Layers 280

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id280, 'ressource_id', 'ressource_id', 'subressources', 'ba', 'int4', '', '', '1', '0', '32', '0', '', 'Text', '', 'Datensatz', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '2', '0', '0');

-- Attribut  des Layers 280

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id280, 'ressourceranges', ''''' ', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '279,id:subressource_id,name Beginn von Ende bis Schritt step;embedded', 'Bereiche', NULL, '', NULL, NULL, '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '5', '0', '0');

-- Replace attribute options for Layer 270
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=270', CONCAT('layer_id=', vars_last_layer_id270)) WHERE layer_id IN (vars_last_layer_id270, vars_last_layer_id271, vars_last_layer_id272, vars_last_layer_id276, vars_last_layer_id277, vars_last_layer_id278, vars_last_layer_id279, vars_last_layer_id280) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=270( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '270,', CONCAT(vars_last_layer_id270, ',')) WHERE layer_id IN (vars_last_layer_id270, vars_last_layer_id271, vars_last_layer_id272, vars_last_layer_id276, vars_last_layer_id277, vars_last_layer_id278, vars_last_layer_id279, vars_last_layer_id280) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '270,%';

-- Replace attribute options for Layer 271
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=271', CONCAT('layer_id=', vars_last_layer_id271)) WHERE layer_id IN (vars_last_layer_id270, vars_last_layer_id271, vars_last_layer_id272, vars_last_layer_id276, vars_last_layer_id277, vars_last_layer_id278, vars_last_layer_id279, vars_last_layer_id280) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=271( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '271,', CONCAT(vars_last_layer_id271, ',')) WHERE layer_id IN (vars_last_layer_id270, vars_last_layer_id271, vars_last_layer_id272, vars_last_layer_id276, vars_last_layer_id277, vars_last_layer_id278, vars_last_layer_id279, vars_last_layer_id280) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '271,%';

-- Replace attribute options for Layer 272
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=272', CONCAT('layer_id=', vars_last_layer_id272)) WHERE layer_id IN (vars_last_layer_id270, vars_last_layer_id271, vars_last_layer_id272, vars_last_layer_id276, vars_last_layer_id277, vars_last_layer_id278, vars_last_layer_id279, vars_last_layer_id280) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=272( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '272,', CONCAT(vars_last_layer_id272, ',')) WHERE layer_id IN (vars_last_layer_id270, vars_last_layer_id271, vars_last_layer_id272, vars_last_layer_id276, vars_last_layer_id277, vars_last_layer_id278, vars_last_layer_id279, vars_last_layer_id280) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '272,%';

-- Replace attribute options for Layer 276
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=276', CONCAT('layer_id=', vars_last_layer_id276)) WHERE layer_id IN (vars_last_layer_id270, vars_last_layer_id271, vars_last_layer_id272, vars_last_layer_id276, vars_last_layer_id277, vars_last_layer_id278, vars_last_layer_id279, vars_last_layer_id280) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=276( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '276,', CONCAT(vars_last_layer_id276, ',')) WHERE layer_id IN (vars_last_layer_id270, vars_last_layer_id271, vars_last_layer_id272, vars_last_layer_id276, vars_last_layer_id277, vars_last_layer_id278, vars_last_layer_id279, vars_last_layer_id280) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '276,%';

-- Replace attribute options for Layer 277
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=277', CONCAT('layer_id=', vars_last_layer_id277)) WHERE layer_id IN (vars_last_layer_id270, vars_last_layer_id271, vars_last_layer_id272, vars_last_layer_id276, vars_last_layer_id277, vars_last_layer_id278, vars_last_layer_id279, vars_last_layer_id280) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=277( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '277,', CONCAT(vars_last_layer_id277, ',')) WHERE layer_id IN (vars_last_layer_id270, vars_last_layer_id271, vars_last_layer_id272, vars_last_layer_id276, vars_last_layer_id277, vars_last_layer_id278, vars_last_layer_id279, vars_last_layer_id280) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '277,%';

-- Replace attribute options for Layer 278
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=278', CONCAT('layer_id=', vars_last_layer_id278)) WHERE layer_id IN (vars_last_layer_id270, vars_last_layer_id271, vars_last_layer_id272, vars_last_layer_id276, vars_last_layer_id277, vars_last_layer_id278, vars_last_layer_id279, vars_last_layer_id280) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=278( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '278,', CONCAT(vars_last_layer_id278, ',')) WHERE layer_id IN (vars_last_layer_id270, vars_last_layer_id271, vars_last_layer_id272, vars_last_layer_id276, vars_last_layer_id277, vars_last_layer_id278, vars_last_layer_id279, vars_last_layer_id280) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '278,%';

-- Replace attribute options for Layer 279
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=279', CONCAT('layer_id=', vars_last_layer_id279)) WHERE layer_id IN (vars_last_layer_id270, vars_last_layer_id271, vars_last_layer_id272, vars_last_layer_id276, vars_last_layer_id277, vars_last_layer_id278, vars_last_layer_id279, vars_last_layer_id280) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=279( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '279,', CONCAT(vars_last_layer_id279, ',')) WHERE layer_id IN (vars_last_layer_id270, vars_last_layer_id271, vars_last_layer_id272, vars_last_layer_id276, vars_last_layer_id277, vars_last_layer_id278, vars_last_layer_id279, vars_last_layer_id280) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '279,%';

-- Replace attribute options for Layer 280
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=280', CONCAT('layer_id=', vars_last_layer_id280)) WHERE layer_id IN (vars_last_layer_id270, vars_last_layer_id271, vars_last_layer_id272, vars_last_layer_id276, vars_last_layer_id277, vars_last_layer_id278, vars_last_layer_id279, vars_last_layer_id280) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=280( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '280,', CONCAT(vars_last_layer_id280, ',')) WHERE layer_id IN (vars_last_layer_id270, vars_last_layer_id271, vars_last_layer_id272, vars_last_layer_id276, vars_last_layer_id277, vars_last_layer_id278, vars_last_layer_id279, vars_last_layer_id280) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '280,%';
END $$