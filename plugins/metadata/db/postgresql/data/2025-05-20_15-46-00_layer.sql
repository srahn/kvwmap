
-- Layerdump aus kvwmap vom 13.10.2026 16:08:14
-- Achtung: Die Datenbank in die der Dump eingespielt wird, sollte die gleiche Migrationsversion haben,
-- wie die Datenbank aus der exportiert wurde! Anderenfalls kann es zu Fehlern bei der Ausführung des SQL kommen.

DO $$
	DECLARE 
		vars_connection_id integer;
		
		vars_group_id_61 integer;
		
		vars_last_layer_id11 integer;
		vars_last_layer_id9 integer;
		vars_last_layer_id10 integer;
		vars_last_layer_id145 integer;
		vars_last_layer_id305 integer;
		vars_last_layer_id26 integer;
		vars_last_layer_id15 integer;
		vars_last_layer_id140 integer;
		vars_last_layer_id3 integer;
		vars_last_layer_id7 integer;
		vars_last_layer_id6 integer;
		vars_last_layer_id5 integer;
		vars_last_layer_id25 integer;
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
					('Datenverwaltung', NULL, NULL, NULL, NULL, NULL, '10000', 'f', NULL)RETURNING id INTO vars_group_id_61;

-- Layer 11

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES
					('Attribute', NULL, '', NULL, NULL, 'Beschreibung der Datensatzfelder', '5', vars_group_id_61, 'SELECT
    a.id,
    a.ressource_id,
    r.bezeichnung as ressource_bez,
    a.name as attribut,
    a.bezeichnung as attribut_bez,
    a.beschreibung as attribut_desc,
    a.datentyp,
    a.valuelist,
    a.minvalue,
    a.maxvalue,
    a.defaultvalue,
    a.mandatory,
    a.created_from,
    a.created_at,
    a.updated_from,
    a.updated_at
FROM
  metadata.attributes a
  left join metadata.ressources r on a.ressource_id=r.id
WHERE
    r.use_for_datapackage=''true'' and (r.layer_id is not null or r.id in (322,327,223))
ORDER BY
  ressource_bez, attribut_bez', 'attributes', 'id', '', '0', '', 'metadata', NULL, '', '', '', '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', '', '', NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '0', NULL, '500', '13', NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', '', '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id11;

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'attribut', 'name', 'attributes', 'a', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Attribut-Name', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '4', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'attribut_bez', 'bezeichnung', 'attributes', 'a', 'varchar', '', '', '1', '0', NULL, NULL, '', 'Text', '', 'Attribut-Bez.', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '5', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'attribut_desc', 'beschreibung', 'attributes', 'a', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Attribut-Beschreibung', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '6', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'created_at', 'created_at', 'attributes', 'a', 'timestamp', '', '', '1', '0', NULL, NULL, 'now()', 'Time', 'insert', 'am', NULL, '', NULL, NULL, '', '', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '14', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'created_from', 'created_from', 'attributes', 'a', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', 'insert', 'erstellt von', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '13', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'datentyp', 'datentyp', 'attributes', 'a', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Datentyp', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '7', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'defaultvalue', 'defaultvalue', 'attributes', 'a', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Defaultwert', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '11', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'id', 'id', 'attributes', 'a', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''attributes_id_seq''::regclass)', 'Text', '', 'ID', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, '1', NULL, NULL, '1', NULL, '1', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'mandatory', 'mandatory', 'attributes', 'a', 'bool', '', '', '1', '1', NULL, NULL, '', 'Checkbox', '', 'Pflichtattribut', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '12', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'maxvalue', 'maxvalue', 'attributes', 'a', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Maximalwert', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '10', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'minvalue', 'minvalue', 'attributes', 'a', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Minimalwert', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '9', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'ressource_bez', 'bezeichnung', 'ressources', 'r', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Ressource-Bez.', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '3', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'ressource_id', 'ressource_id', 'attributes', 'a', 'int4', '', '', '1', '0', '32', '0', '', 'Text', '', 'Ressource-ID', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '2', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'updated_at', 'updated_at', 'attributes', 'a', 'timestamp', '', '', '1', '1', NULL, NULL, '', 'Time', 'update', 'am', NULL, '', NULL, NULL, '', '', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '16', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'updated_from', 'updated_from', 'attributes', 'a', 'varchar', '', '', '1', '1', NULL, NULL, '', 'User', 'update', 'geändert von', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '15', '0', '0');

-- Attribut  des Layers 11

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id11, 'valuelist', 'valuelist', 'attributes', 'a', '_varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Werteliste', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '8', '0', '0');

-- Datendrucklayout 5 des Layers 11

				INSERT INTO kvwmap.datendrucklayouts 
					("name", "layer_id", "format", "bgsrc", "bgposx", "bgposy", "bgwidth", "bgheight", "dateposx", "dateposy", "datesize", "userposx", "userposy", "usersize", "font_date", "font_user", "type", "margin_top", "margin_bottom", "margin_left", "margin_right", "gap", "no_record_splitting", "columns", "filename", "use_previews") 
				VALUES 
					('Metadaten', vars_last_layer_id11, 'A4 hoch', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2', '0', '20', '0', '0', '30', '0', '0', NULL, '0')RETURNING id INTO vars_last_ddl_id;

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'valuelist', '100', '20', 'datentyp', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'name', '100', '20', 'bezeichnung', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'bezeichnung', '100', '20', 'ressource_id', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'beschreibung', '10', '35', 'name', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'datentyp', '100', '20', 'attribut_desc', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'id', '100', '830', NULL, NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'minvalue', '100', '20', 'valuelist', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'maxvalue', '100', '20', 'minvalue', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'defaultvalue', '100', '20', 'maxvalue', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'mandatory', '100', '20', 'defaultvalue', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'created_from', '100', '20', 'mandatory', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'created_at', '100', '20', 'created_from', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'updated_from', '100', '20', 'created_at', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'updated_at', '100', '20', 'updated_from', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'attribut_desc', '70', '20', 'attribut_bez', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'attribut_bez', '100', '20', 'attribut', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'attribut', '100', '20', 'id', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('ID', '10', '830', NULL, '11', NULL, NULL, 'Helvetica-Bold.afm', NULL, '0')RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 220 zu DDL 5
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Attribut-ID', '10', '20', 'id', '11', NULL, NULL, 'Helvetica-Bold.afm', NULL, '0')RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 221 zu DDL 5
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Bezeichnung', '10', '20', 'attribut', '11', NULL, NULL, 'Helvetica-Bold.afm', NULL, '0')RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 222 zu DDL 5
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Beschreibung', '10', '20', 'attribut_bez', '11', NULL, NULL, 'Helvetica-Bold.afm', NULL, '0')RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 224 zu DDL 5
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Datentyp', '10', '20', 'attribut_desc', '11', NULL, NULL, 'Helvetica-Bold.afm', NULL, '0')RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 225 zu DDL 5
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Werteliste', '10', '20', 'datentyp', '11', NULL, NULL, 'Helvetica-Bold.afm', NULL, '0')RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 226 zu DDL 5
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Minimalwert', '10', '20', 'valuelist', '11', NULL, NULL, 'Helvetica-Bold.afm', NULL, '0')RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 227 zu DDL 5
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Maximalwert', '10', '20', 'minvalue', '11', NULL, NULL, 'Helvetica-Bold.afm', NULL, '0')RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 228 zu DDL 5
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Defaultwert', '10', '20', 'maxvalue', '11', NULL, NULL, 'Helvetica-Bold.afm', NULL, '0')RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 229 zu DDL 5
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Pflichtattribut', '10', '20', 'defaultvalue', '11', NULL, NULL, 'Helvetica-Bold.afm', NULL, '0')RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 230 zu DDL 5
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('erstellt von', '10', '20', 'mandatory', '11', NULL, NULL, 'Helvetica-Bold.afm', NULL, '0')RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 231 zu DDL 5
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('am', '10', '20', 'created_from', '11', NULL, NULL, 'Helvetica-Bold.afm', NULL, '0')RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 232 zu DDL 5
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('geändert von', '10', '20', 'created_at', '11', NULL, NULL, 'Helvetica-Bold.afm', NULL, '0')RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 233 zu DDL 5
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('am', '10', '20', 'updated_from', '11', NULL, NULL, 'Helvetica-Bold.afm', NULL, '0')RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 234 zu DDL 5
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

-- Layer 9

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Datenabstammung', NULL, '', NULL, NULL, NULL, '5', vars_group_id_61, 'SELECT
  l.id,
  l.source_id,
  l.target_id,
  '''' AS source_link,
  '''' AS target_link
FROM
  metadata.lineages l
WHERE
  1=1', 'lineages', 'id', '', '0', '', 'metadata', NULL, '', '', '', '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', '', '', NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '0', NULL, '500', '8', NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', '', '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id9;

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'id', 'id', 'lineages', 'l', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''lineages_id_seq''::regclass)', 'Text', '', 'ID', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '1', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'source_id', 'source_id', 'lineages', 'l', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  r.id AS value,
  concat_ws('' - '', g.gruppe, r.bezeichnung) AS output
FROM
  metadata.ressources r LEFT JOIN
  metadata.gruppen g ON r.gruppe_id = g.id
ORDER BY
  g.gruppe,
  r.bezeichnung', 'Quelle', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '2', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'source_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=3&value_id=$source_id&operator_id==;anzeigen;no_new_window', '', NULL, '', NULL, NULL, '', '', '', '1', '2', NULL, NULL, NULL, NULL, '1', NULL, '3', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'target_id', 'target_id', 'lineages', 'l', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  r.id AS value,
  concat_ws('' - '', g.gruppe, r.bezeichnung) AS output
FROM
  metadata.ressources r LEFT JOIN
  metadata.gruppen g ON r.gruppe_id = g.id
ORDER BY
  g.gruppe,
  r.bezeichnung', 'Ziel', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '4', '0', '0');

-- Attribut  des Layers 9

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id9, 'target_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=3&value_id=$target_id&operator_id==;anzeigen;no_new_window', '', NULL, '', NULL, NULL, '', '', '', '1', '2', NULL, NULL, NULL, NULL, '1', NULL, '5', '0', '0');

-- Datendrucklayout 7 des Layers 9

				INSERT INTO kvwmap.datendrucklayouts 
					("name", "layer_id", "format", "bgsrc", "bgposx", "bgposy", "bgwidth", "bgheight", "dateposx", "dateposy", "datesize", "userposx", "userposy", "usersize", "font_date", "font_user", "type", "margin_top", "margin_bottom", "margin_left", "margin_right", "gap", "no_record_splitting", "columns", "filename", "use_previews") 
				VALUES 
					('Datenquellen', vars_last_layer_id9, 'A4 hoch', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2', '0', '0', '0', '0', '20', '0', '0', NULL, '0')RETURNING id INTO vars_last_ddl_id;

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Datengrundlagen:', '1', '825', NULL, '9', '105', NULL, 'Helvetica-Bold.afm', NULL, '1')RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 244 zu DDL 7
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('$source_id', '99', '825', NULL, '9', '430', NULL, 'Helvetica.afm', NULL, '0')RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 589 zu DDL 7
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

-- Datendrucklayout 8 des Layers 9

				INSERT INTO kvwmap.datendrucklayouts 
					("name", "layer_id", "format", "bgsrc", "bgposx", "bgposy", "bgwidth", "bgheight", "dateposx", "dateposy", "datesize", "userposx", "userposy", "usersize", "font_date", "font_user", "type", "margin_top", "margin_bottom", "margin_left", "margin_right", "gap", "no_record_splitting", "columns", "filename", "use_previews") 
				VALUES 
					('Datenziele', vars_last_layer_id9, 'A4 hoch', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2', '0', '0', '0', '0', '10', '0', '0', NULL, '0')RETURNING id INTO vars_last_ddl_id;

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Weiterverwendung:', '1', '825', NULL, '9', '105', NULL, 'Helvetica-Bold.afm', NULL, '1')RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 245 zu DDL 8
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('$target_id', '99', '825', NULL, '9', '430', NULL, 'Helvetica.afm', NULL, '0')RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 590 zu DDL 8
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

-- Layer 10

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Datenformate', NULL, '', NULL, NULL, NULL, '5', vars_group_id_61, 'SELECT
  id,
  format
FROM
  metadata.formate
WHERE
  1=1
ORDER BY
  format', 'formate', 'id', '', '0', '', 'metadata', NULL, '', '', '', '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', '', '', NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '0', NULL, '500', '10', NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', '', '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id10;

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'format', 'format', 'formate', 'formate', 'varchar', '', 'UNIQUE', '1', '1', NULL, NULL, '', 'Text', '', 'Format', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '2', '0', '0');

-- Attribut  des Layers 10

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id10, 'id', 'id', 'formate', 'formate', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''formate_id_seq''::regclass)', 'Text', '', 'ID', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '1', '0', '0');

-- Layer 145

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Dateninformationsblatt', NULL, NULL, NULL, NULL, 'Dateninformationsblatt', '5', vars_group_id_61, 'SELECT
  r.id,
  g.gruppe,
  r.bezeichnung,
  l.layer_id,
  l.name,
  l.alias,
  l.schema,
  l.maintable,
  l.datentyp,
  split_part(r.metadata_document, ''='', 2),
  case when r.transform_command like ''%CREATE INDEX%'' then ''ja'' else ''nein'' end as db_index,
  i.dateninhaber,
  r.quelle,
  r.beschreibung,
  r.auto_update,
  r.update_interval,
  r.status_id,
  r.last_updated_at::date,
  r.aktualitaet,
  '''' link_map,
  '''' link_tab,
  '''' link_set
FROM
  metadata.ressources r JOIN
  metadata.gruppen g ON r.gruppe_id = g.id LEFT JOIN
  kvwmap.layer l ON r.layer_id = l.layer_id LEFT JOIN
  metadata.dateninhaber as i on r.dateninhaber_id=i.id JOIN
  kvwmap.used_layer u ON u.layer_id = l.layer_id
WHERE
  u.stelle_id = $STELLE_ID AND	
  r.use_for_datapackage = ''true'' AND
  (
    l.layer_id is not null OR
    r.id in (322,327,223)
  )
ORDER BY
  name,
  gruppe,
  dateninhaber,
  alias,
  bezeichnung', 'ressources', 'id', '', '0', '', 'metadata', '', '', '', '', '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', '', '', NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '0', NULL, '500', '3', NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', '', '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', '', '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id145;

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'aktualitaet', 'aktualitaet', 'ressources', 'r', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Aktualitätsdatum der Quelle', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '19', '0', '0');

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'alias', 'alias', 'layer', 'l', 'varchar', '', '', '1', '1', '255', NULL, 'NULL::character varying', 'Text', '', 'Bez. Layer', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '6', '0', '0');

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'auto_update', 'auto_update', 'ressources', 'r', 'bool', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Aktualisierung (j/n)', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '15', '0', '0');

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'beschreibung', 'beschreibung', 'ressources', 'r', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Beschreibung', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '14', '0', '0');

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'bezeichnung', 'bezeichnung', 'ressources', 'r', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Bez. Datenpaket Menü', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '3', '0', '0');

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'dateninhaber', 'dateninhaber', 'dateninhaber', 'i', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Dateninhaber', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '12', '0', '0');

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'db_index', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'Text', '', 'DB-Index', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '11', '0', '0');

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'gruppe', 'gruppe', 'gruppen', 'g', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Gruppe', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '2', '0', '0');

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'id', 'id', 'ressources', 'r', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''ressources_id_seq''::regclass)', 'Text', '', 'Ressource-ID', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '1', '0', '0');

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'last_updated_at', '', '', '', 'date', '', '', '0', NULL, NULL, NULL, '', 'Text', '', 'DWH-Ladedatum', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '18', '0', '0');

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'layer_id', 'layer_id', 'layer', 'l', 'int4', '', '', '1', '1', '32', '0', 'nextval(''kvwmap.layer_layer_id_seq''::regclass)', 'Text', '', 'Layer-ID', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '4', '0', '0');

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'link_set', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layereditor&selected_layer_id=$layer_id;Einstellung;no_new_window', 'Einstellung', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '22', NULL, '0');

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'link_tab', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=$layer_id;Daten;no_new_window', 'Daten', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '21', '0', '0');

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'name', 'name', 'layer', 'l', 'varchar', '', '', '1', '0', '255', NULL, '', 'Text', '', 'Bez. Datenpaket exportiert', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '5', '0', '0');

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'quelle', 'quelle', 'ressources', 'r', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Quelle', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '13', '0', '0');

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'schema', 'schema', 'layer', 'l', 'varchar', '', '', '1', '1', '50', NULL, 'NULL::character varying', 'Text', '', 'Schemaname', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '7', '0', '0');

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'split_part', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'Text', '', 'Metadaten-Dokument', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '10', '0', '0');

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'status_id', 'status_id', 'ressources', 'r', 'int4', '', '', '1', '1', '32', '0', '', 'Text', '', 'Status der Aktualisierung', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '17', '0', '0');

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'update_interval', 'update_interval', 'ressources', 'r', 'interval', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Fortführungszyklus', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '16', '0', '0');

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'maintable', 'maintable', 'layer', 'l', 'varchar', '', '', '1', '1', '255', NULL, 'NULL::character varying', 'Text', '', 'Tabellenname', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '8', '0', '0');

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'datentyp', 'datentyp', 'layer', 'l', 'int2', '', '', '1', '0', '16', '0', '2', 'Auswahlfeld', 'SELECT
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
) AS options', 'Datentyp', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '9', '0', '0');

-- Attribut  des Layers 145

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id145, 'link_map', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=show_layer_in_map&layer_id=$layer_id;Karte;no_new_window', 'Karte', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "5", "operator": "!=", "attribute": "datentyp"}]}', '20', '0', '0');

-- Layer 305

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('dateninhaber', NULL, NULL, NULL, NULL, 'Dateninhaber', '5', vars_group_id_61, 'SELECT
  id,
  name,
  beschreibung
FROM
  kvwmap.datasources
WHERE
  1=1', 'datasources', 'id', '', '0', '', 'kvwmap', '', '', '', '', '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', '', '', NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '0', NULL, '500', '9', NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', '', '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id305;

-- Attribut  des Layers 305

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id305, 'id', 'id', 'datasources', 'datasources', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''datasources_id_seq''::regclass)', 'Text', '', '', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '1', '0', '0');

-- Attribut  des Layers 305

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id305, 'name', 'name', 'datasources', 'datasources', 'varchar', '', '', '1', '1', '100', NULL, 'NULL::character varying', 'Text', '', 'Abkürzung', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '2', '0', '0');

-- Attribut  des Layers 305

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id305, 'beschreibung', 'beschreibung', 'datasources', 'datasources', 'text', '', '', '1', '0', NULL, NULL, '', 'Text', '', 'Bezeichnung', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '3', '0', '0');

-- Layer 26

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('datenpakete', NULL, NULL, NULL, NULL, NULL, '5', vars_group_id_61, 'SELECT
  r.id AS ressource_id,
  r.bezeichnung,
  p.id,
  p.stelle_id,
  p.pack_status_id,
  p.created_at,
  p.created_from,
  p.updated_at
FROM
  metadata.ressources r LEFT JOIN
  metadata.data_packages p ON r.id = p.ressource_id
WHERE
  r.use_for_datapackage AND
  r.layer_id IS NOT NULL', 'data_packages', 'id', '', '0', '', 'metadata', NULL, '', '', '', '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', '', '', NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '0', NULL, '500', '2', NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', '', '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id26;

-- Attribut  des Layers 26

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id26, 'bezeichnung', 'bezeichnung', 'ressources', 'r', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Bezeichnung', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '2', '0', '0');

-- Attribut  des Layers 26

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id26, 'created_at', 'created_at', 'data_packages', 'p', 'timestamp', '', '', '1', '0', NULL, NULL, 'now()', 'Time', 'insert', 'erzeugt am', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '6', '0', '0');

-- Attribut  des Layers 26

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id26, 'created_from', 'created_from', 'data_packages', 'p', 'varchar', '', '', '1', '1', NULL, NULL, '', 'User', '', 'erzeugt von', NULL, NULL, NULL, NULL, '', '', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '7', '0', '0');

-- Attribut  des Layers 26

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id26, 'id', 'id', 'data_packages', 'p', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''data_packages_id_seq''::regclass)', 'Text', '', 'Paket-ID', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '3', '0', '0');

-- Attribut  des Layers 26

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id26, 'pack_status_id', 'pack_status_id', 'data_packages', 'p', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id AS value, status AS output FROM metadata.pack_status ORDER BY id', 'Packstatus', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '5', '0', '0');

-- Attribut  des Layers 26

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id26, 'ressource_id', 'id', 'ressources', 'r', 'int4', '', '', '1', '1', '32', '0', 'nextval(''ressources_id_seq''::regclass)', 'Text', '', 'Ressource-ID', NULL, NULL, NULL, NULL, '', '', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '1', '0', '0');

-- Attribut  des Layers 26

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id26, 'stelle_id', 'stelle_id', 'data_packages', 'p', 'int4', '', '', '1', '1', '32', '0', '', 'Text', '', 'Stelle-ID', NULL, NULL, NULL, NULL, '', '', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '4', '0', '0');

-- Attribut  des Layers 26

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id26, 'updated_at', 'updated_at', 'data_packages', 'p', 'timestamp', '', '', '1', '0', NULL, NULL, 'now()', 'Time', 'update', '', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '8', '0', '0');

-- Layer 15

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Layerliste Datentool', NULL, '', NULL, NULL, NULL, '5', vars_group_id_61, 'SELECT
  ba.id,
  gr.gruppe,
  ba.bezeichnung,
  ba.beschreibung, 
  di.dateninhaber,
  gr.reihenfolge,
  ''<ul><li>'' || string_agg(''<a href="index.php?go=Layer-Suche_Suchen&selected_layer_id=3&value_id='' || sc.id || ''&operator_id==">'' || sc.bezeichnung || ''<a>'', ''</li><li>'') || ''</li></ul>'' quellen,
  '''' datenquellen,
  '''' datenziele
FROM
  metadata.ressources ba JOIN
  metadata.gruppen gr ON ba.gruppe_id = gr.id LEFT JOIN
  metadata.dateninhaber di ON ba.dateninhaber_id = di.id LEFT JOIN
  metadata.lineages li ON ba.id = li.target_id LEFT JOIN
  metadata.ressources sc ON li.source_id = sc.id
WHERE
  ba.use_for_datapackage
GROUP BY ba.id, gr.gruppe, ba.bezeichnung, ba.beschreibung, di.dateninhaber, gr.reihenfolge
ORDER BY
  reihenfolge,
  bezeichnung', 'ressources', 'id', '', '0', '', 'metadata', NULL, '', '', '', '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', '', '', NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '0', NULL, '500', '15', NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', '', '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id15;

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'beschreibung', 'beschreibung', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Beschreibung', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '4', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'bezeichnung', 'bezeichnung', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Bezeichnung', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '3', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'dateninhaber', 'dateninhaber', 'dateninhaber', 'di', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Dateninhaber', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '5', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'datenquellen', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '9,id:target_id,source_id;no_new_window', 'Datenquellen', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '8', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'datenziele', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '9,id:source_id,target_id;no_new_window', 'Datenziele', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '9', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'gruppe', 'gruppe', 'gruppen', 'gr', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Gruppe', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '2', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'id', 'id', 'ressources', 'ba', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''ressources_id_seq''::regclass)', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=3&value_id=$id&operator_id==;$id;no_new_window', 'ID', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '1', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'quellen', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'Text', '', 'Quellen', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '7', '0', '0');

-- Attribut  des Layers 15

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id15, 'reihenfolge', 'reihenfolge', 'gruppen', 'gr', 'int4', '', '', '1', '1', '32', '0', '', 'Text', '', 'Reihenfolge', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '0', NULL, '6', '0', '0');

-- Layer 140

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('package_errors', NULL, NULL, NULL, NULL, 'Fehler beim Packen', '5', vars_group_id_61, 'SELECT
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
  packed_at DESC', 'pack_logs', 'id', '', '0', '', 'metadata', NULL, '', '', '', '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', '', '', NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '0', NULL, '500', '4', NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', '', '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id140;

-- Attribut  des Layers 140

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id140, 'bezeichnung', 'bezeichnung', 'ressources', 'r', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Ressource', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '2', '0', '0');

-- Attribut  des Layers 140

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id140, 'fixed_at', 'fixed_at', 'pack_logs', 'l', 'timestamp', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'behoben am', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '7', '1', '0');

-- Attribut  des Layers 140

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id140, 'id', 'id', 'pack_logs', 'l', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''pack_log_id_seq''::regclass)', 'Text', '', 'Log-ID', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '1', '0', '0');

-- Attribut  des Layers 140

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id140, 'msg', 'msg', 'pack_logs', 'l', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Fehlermeldung', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '4', '0', '0');

-- Attribut  des Layers 140

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id140, 'package_id', 'package_id', 'pack_logs', 'l', 'int4', '', '', '1', '0', '32', '0', '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=26&value_id=$package_id&operator_id==;zum Paket (ID: $package_id)', 'Paket', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '5', '0', '0');

-- Attribut  des Layers 140

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id140, 'packed_at', 'packed_at', 'pack_logs', 'l', 'timestamp', '', '', '1', '0', NULL, NULL, 'now()', 'Text', '', 'aufgetreten am', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '3', '0', '0');

-- Attribut  des Layers 140

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id140, 'ressource_id', 'ressource_id', 'pack_logs', 'l', 'int4', '', '', '1', '0', '32', '0', '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=3&value_id=$ressource_id&operator_id==;zur Ressource (ID: $ressource_id)', 'Ressource', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '6', '0', '0');

-- Layer 3

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Ressourcen', NULL, '', NULL, NULL, NULL, '5', vars_group_id_61, 'SELECT
  ba.id,
  ba.ampel_id,
  inquiries_required,
  check_required,
  inquiries_required OR check_required AS any_required,
  inquiries_responsible,
  inquiries_to,
  inquiries,
  inquiries_responses,
  CASE WHEN relevanz AND digital AND flaechendeckend THEN 1 WHEN relevanz  THEN 2 ELSE 3 END AS prioritaet,
  COALESCE(a.style, ''background-color: lightgray'') as style,
  ba.gruppe_id,
  ba.bezeichnung,
  ba.hinweise_auf,
  ba.beschreibung, 
  ba.aenderungshinweise,
  ba.datasource_id,
  ba.nutzungsberechtigte,
  ba.nutzungsbedingungen,
  ansprechperson,
  ba.format_id,
  aktualitaet,
  quelle,
  quellenvermerk,
  '''' datenquellen,
  '''' datenziele,
  '''' Attribute,
  documents,
  metadata_document,
  '''' metadata_document_create_link,
  github,
  last_updated_at,
  auto_update,
  next_update_at,
  update_interval,
  update_time,
  download_url,
  '''' subressources,
  download_method,
  download_path,
  download_typenames,
  upload_file,
  '''' download_link,
  '''' download_list_link,
  unpack_method,
  dest_path,
  '''' unpack_link,
  '''' unpack_list_link,
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
  '''' update_ressource_link,
  '''' update_packages_link,
  ba.layer_id,
  '''' layer_link,
  '''' layer_datasources,
  l.pfad,
  l.data,
  l.kurzbeschreibung,
  l.dataowner_name,
  l.dataowner_tel,
  l.dataowner_email,
  l.metalink,
  l.terms_of_use_link,
  '''' update_logs,
  '''' pack_logs,
  created_at,
  created_from,
  updated_at,
  updated_from
FROM
  metadata.ressources ba LEFT JOIN
  metadata.ampel a ON ba.ampel_id = a.id LEFT JOIN
  kvwmap.layer l ON ba.layer_id = l.layer_id
WHERE
  von_eneka OR use_for_datapackage', 'ressources', 'id', '', '0', '', 'metadata', '', '/var/www/data/datentool/', '', '', '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', '', '', NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '0', NULL, '500', '6', NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', '', '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id3;

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'aenderungshinweise', 'aenderungshinweise', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Änderungshinweise', NULL, NULL, NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '17', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'aktualitaet', 'aktualitaet', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Aktualität', NULL, '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '24', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'ansprechperson', 'ansprechperson', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Ansprechperson', NULL, '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '22', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'any_required', '', '', '', 'bool', '', '', '0', NULL, NULL, NULL, '', 'Text', '', '', NULL, '', NULL, NULL, '', 'Rückfragen;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '0', NULL, '6', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'attribute', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '11,id:ressource_id,attribut_bez;embedded', 'Attribute', NULL, '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '29', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'beschreibung', 'beschreibung', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Beschreibung', NULL, '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '16', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'bezeichnung', 'bezeichnung', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Bezeichnung der Datenquelle', NULL, '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '14', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'download_method', 'download_method', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT
  name AS value,
  beschreibung AS output
FROM
  metadata.download_methods
ORDER BY reihenfolge', 'Download-Methode', NULL, '', NULL, NULL, 'Wenn die Importmethode WFS ist und kein TypeName angegeben ist, werden statt dessen alle FeatureTypen des WFS runtergeladen und in gml-Dateien geschrieben.', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '41', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'download_path', 'download_path', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Download-Verzeichnis', NULL, '', NULL, NULL, 'relativ zu /var/www/data/datentool/ressourcen/', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": ["urls", "wfs", "wms", "atom", "parallel_from_file"], "operator": "IN", "attribute": "download_method"}]}', '44', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'download_url', 'download_url', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Link', '', 'Download-URL', NULL, '', NULL, NULL, '', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '39', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'nutzungsbedingungen', 'nutzungsbedingungen', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Nutzungsbedingungen', NULL, NULL, NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '21', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'nutzungsberechtigte', 'nutzungsberechtigte', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Nutzungsberechtigte', NULL, NULL, NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '20', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'pack_logs', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '140,id:ressource_id,packed_at;embedded', '', NULL, NULL, NULL, NULL, '', 'Protokollierung;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '78', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'pfad', 'pfad', 'layer', 'l', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Sachdatenabfrage', NULL, NULL, NULL, NULL, '', 'Layerangaben;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '69', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'quelle', 'quelle', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Link', '', 'Quelle', NULL, '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '25', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'id', 'id', 'ressources', 'ba', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''ressources_id_seq''::regclass)', 'Text', '', 'ID', NULL, '', NULL, NULL, '', 'Metadaten', '', '1', '0', NULL, '1', NULL, NULL, '1', NULL, '1', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'ampel_id', 'ampel_id', 'ressources', 'ba', 'int4', '', '', '1', '1', '32', '0', '', 'Radiobutton', 'SELECT
  id AS value,
  farbe AS output
FROM
  metadata.ampel
ORDER BY reihenfolge;horizontal ', 'Ampel', NULL, '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '2', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'use_for_datapackage', 'use_for_datapackage', 'ressources', 'ba', 'bool', '', '', '1', '1', NULL, NULL, '', 'Checkbox', '', 'Für Datentool', NULL, '', NULL, NULL, '', 'Metadaten', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '3', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'check_required', 'check_required', 'ressources', 'ba', 'bool', '', '', '1', '1', NULL, NULL, '', 'Checkbox', '', 'Prüfung der Daten erforderlich', NULL, '', NULL, NULL, 'Prüfungen die GDI-Service vornehmen muss bevor die Ressource zur Verwendung im Datentool aufgenommen wird.', 'Rückfragen;collapsed', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '5', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'inquiries_responsible', 'inquiries_responsible', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Für Anfragen verantwortlich', NULL, '', NULL, NULL, 'Person oder Einrichtung die die Rückfragen an den Datenlieferanten stellen soll und die Rückmeldungen hier notiert.', 'Rückfragen;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "t", "operator": "=", "attribute": "any_required"}]}', '7', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'prioritaet', '', '', '', 'int4', '', '', '0', NULL, NULL, NULL, '', 'Textfeld', '', 'Priorität', NULL, '', NULL, NULL, '1 = relevant, digital und flächendeckend verfügbar
2 = relevant aber nicht digital oder nicht flächendeckend
3 = nicht relevant
Wert wird nach dem Speichern automatisch berechnet.', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '11', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'datasource_id', 'datasource_id', 'ressources', 'ba', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  id AS value,
  beschreibung AS output
FROM
  kvwmap.datasources
ORDER BY beschreibung;layer_id=305 embedded', 'Dateninhaber', NULL, NULL, NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '19', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'import_table', 'import_table', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Importtabelle', NULL, '', NULL, NULL, 'Name der Tabelle im Import-Schema in die die Daten importiert werden sollen, falls das nicht in der Importmethode schon definiert wurde. Alles klein schreiben.
Wenn die Importmethode WFS ist und kein Tabellenname angegeben wird, werden statt dessen alle GML-Dateien aus dem Import-Ordner eingelesen  und die Tabellen so wie die Dateien benannt.', 'Prozesse', '', '1', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "import_method"}]}', '54', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'auto_update', 'auto_update', 'ressources', 'ba', 'bool', '', '', '1', '1', NULL, NULL, '', 'Checkbox', '', 'Auto-Update', NULL, '', NULL, NULL, 'Ob der Datensatz automatisch geupdated werden soll. Wirkt im Zusammenhang mit dem Update-Interval und dem Datum des letzten Updates. Status muss leer oder Uptodate sein. Wenn Auto-Update gesetzt und das Interval leer ist wird nur ein mal aktualisiert und das Update-Datum gesetzt.', 'Prozesse', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '35', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'created_at', 'created_at', 'ressources', 'ba', 'timestamp', '', '', '1', '1', NULL, NULL, '', 'Time', 'insert', 'am', NULL, '', NULL, NULL, '', 'Bearbeitungsvermerke;collapsed', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '79', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'created_from', 'created_from', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'User', 'insert', 'erzeugt von', NULL, '', NULL, NULL, '', 'Bearbeitungsvermerke;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '80', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'data', 'data', 'layer', 'l', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Kartendatenabfrage', NULL, NULL, NULL, NULL, '', 'Layerangaben;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '70', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'dataowner_email', 'dataowner_email', 'layer', 'l', 'varchar', '', '', '1', '1', '100', NULL, 'NULL::character varying', 'Text', '', 'E-Mail', NULL, NULL, NULL, NULL, '', 'Layerangaben;collapsed', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '74', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'dataowner_name', 'dataowner_name', 'layer', 'l', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Dateninhaber Name', NULL, NULL, NULL, NULL, '', 'Layerangaben;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '72', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'dataowner_tel', 'dataowner_tel', 'layer', 'l', 'varchar', '', '', '1', '1', '50', NULL, 'NULL::character varying', 'Text', '', 'Telefon', NULL, NULL, NULL, NULL, '', 'Layerangaben;collapsed', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '73', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'datenquellen', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '9,id:target_id,source_id;embedded', 'Datenquellen', NULL, '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '27', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'datenziele', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '9,id:source_id,target_id;embedded', 'Datenziele', NULL, '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '28', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'dest_path', 'dest_path', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Zielverzeichnis', NULL, '', NULL, NULL, 'relativ zu /var/www/data/datentool/ressourcen/', 'Prozesse', '', '1', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "unpack_method"}]}', '48', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'documents', 'documents', 'ressources', 'ba', '_varchar', '', '', '1', '1', NULL, NULL, '', 'Dokument', 'SELECT ''dokumente/''', 'Dokumente', NULL, NULL, NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '32', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'format_id', 'format_id', 'ressources', 'ba', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  id AS value,
  format AS output
FROM
  metadata.formate
ORDER BY format;layer_id=8 embedded', 'Format', NULL, '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '23', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'github', 'github', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Github', NULL, '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '33', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'gruppe_id', 'gruppe_id', 'ressources', 'ba', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  id AS value,
  gruppe AS output
FROM
  metadata.gruppen
ORDER BY id;layer_id=7 embedded', 'Gruppe', NULL, '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '13', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'hinweise_auf', 'hinweise_auf', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Hinweise auf', NULL, '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '15', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'import_file', 'import_file', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Importdatei', NULL, NULL, NULL, NULL, 'Wenn keine angegeben wird, werden alle Dateien im Import-Verzeichnis eingelesen.', 'Prozesse', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '52', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'import_method', 'import_method', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT
  name AS value,
  beschreibung AS output
FROM
  metadata.import_methods
ORDER BY reihenfolge', 'Importmethode', NULL, '', NULL, NULL, 'Bei der Methode WFS werden wenn keine Import-Tabelle angegeben ist alle GML-Dateien aus dem import-Verzeichnis eingelesen.', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '51', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'inquiries_required', 'inquiries_required', 'ressources', 'ba', 'bool', '', '', '1', '1', NULL, NULL, '', 'Checkbox', '', 'Anfragen erforderlich', NULL, '', NULL, NULL, '', 'Rückfragen;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '4', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'kurzbeschreibung', 'kurzbeschreibung', 'layer', 'l', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Kurzbeschreibung', NULL, NULL, NULL, NULL, '', 'Layerangaben;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '71', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'last_updated_at', 'last_updated_at', 'ressources', 'ba', 'timestamp', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'letzter Update', NULL, NULL, NULL, NULL, '', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '34', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'layer_datasources', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '204,layer_id,name;embedded', 'Layer Quellen', NULL, NULL, NULL, NULL, '', 'Layerangaben;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '68', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'layer_id', 'layer_id', 'ressources', 'ba', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  layer_id AS value,
  COALESCE(alias, name) AS output
FROM
  kvwmap.layer
ORDER BY
  COALESCE(alias, name)', 'Layer', NULL, NULL, NULL, NULL, '', 'Visualisierung', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '66', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'layer_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layereditor&selected_layer_id=$layer_id;zum Layer', '', NULL, NULL, NULL, NULL, '', 'Visualisierung', '', '1', '2', NULL, NULL, NULL, NULL, '1', NULL, '67', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'metadata_document', 'metadata_document', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Dokument', 'SELECT ''metadaten/''', 'Metadaten-Dokument', NULL, NULL, NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '30', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'metadata_document_create_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=generischer_sachdaten_druck_Drucken&aktivesLayout=4&chosen_layer_id=3&oid=$id&archivieren=1;anlegen;no_new_window', '', NULL, NULL, NULL, NULL, '', 'Metadaten', '', '1', '2', NULL, NULL, NULL, NULL, '1', NULL, '31', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'metalink', 'metalink', 'layer', 'l', 'varchar', '', '', '1', '1', '255', NULL, 'NULL::character varying', 'Link', '', 'Metadaten Link', NULL, NULL, NULL, NULL, '', 'Layerangaben;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '75', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'next_update_at', 'next_update_at', 'ressources', 'ba', 'timestamp', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'nächster Update', NULL, NULL, NULL, NULL, '', 'Prozesse', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '36', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'quellenvermerk', 'quellenvermerk', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Quellenvermerke', NULL, NULL, NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '26', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'status_id', 'status_id', 'ressources', 'ba', 'int4', '', '', '1', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
  id AS value,
  status AS output
FROM
  metadata.update_status
ORDER BY reihenfolge
', 'Update Status', NULL, '', NULL, NULL, 'Wird automatisch gesetzt beim Update der Ressource.', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '62', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'style', '', '', '', 'varchar', '', '', '0', NULL, NULL, NULL, '', 'Style', '', 'Style', NULL, '', NULL, NULL, '', 'Metadaten', '', '0', '0', NULL, NULL, NULL, NULL, '0', NULL, '12', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'terms_of_use_link', 'terms_of_use_link', 'layer', 'l', 'varchar', '', '', '1', '1', '255', NULL, 'NULL::character varying', 'Link', '', 'Nutzungsrechte Link', NULL, NULL, NULL, NULL, '', 'Layerangaben;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '76', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'transform_command', 'transform_command', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Prozessbefehl', NULL, '', NULL, NULL, '', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "exec_sql", "operator": "=", "attribute": "transform_method"}]}', '60', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'transform_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=metadata_update_outdated&ressource_id=$id&force=1&method_only=transform;run', '', NULL, NULL, NULL, NULL, '', 'Prozesse', '', '1', '2', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "transform_method"}]}', '61', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'transform_method', 'transform_method', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT
  name AS value,
  beschreibung AS output
FROM
  metadata.transform_methods
ORDER BY reihenfolge', 'Prozessmethode', NULL, '', NULL, NULL, '', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '59', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'unpack_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=metadata_update_outdated&ressource_id=$id&force=1&method_only=unpack;run', '', NULL, NULL, NULL, NULL, '', 'Prozesse', '', '1', '2', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "unpack_method"}]}', '50', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'unpack_list_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=metadata_list_files&dir=$dest_path&ressource_id=$id;list;;no_new_window', '', NULL, NULL, NULL, NULL, '', 'Prozesse', '', '1', '2', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "unpack_method"}]}', '49', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'unpack_method', 'unpack_method', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT
  name AS value,
  beschreibung AS output
FROM
  metadata.unpack_methods
ORDER BY reihenfolge', 'Auspackmethode', NULL, '', NULL, NULL, 'Methode zum Auspacken oder/und Verschieben der Daten in den Importordner. Wenn manuelles kopieren ausgewählt wurde geht der Prozess davon aus, dass die Daten die für den Import benötigt werden schon dort liegen wo sie hingehören.', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '47', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'updated_at', 'updated_at', 'ressources', 'ba', 'timestamp', '', '', '1', '1', NULL, NULL, '', 'Time', 'update', 'am', NULL, '', NULL, NULL, '', 'Bearbeitungsvermerke;collapsed', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '81', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'updated_from', 'updated_from', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'User', 'update', 'geändert von', NULL, '', NULL, NULL, '', 'Bearbeitungsvermerke;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '82', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'update_interval', 'update_interval', 'ressources', 'ba', 'interval', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Update-Interval', NULL, '', NULL, NULL, '', 'Prozesse', '', '1', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "t", "operator": "=", "attribute": "auto_update"}]}', '37', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'update_logs', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '25,id:ressource_id,update_at;embedded', 'Update logs', NULL, NULL, NULL, NULL, '', 'Protokollierung;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '77', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'update_packages_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=metadata_reorder_data_packages&ressource_id=$id;Starte Update', 'Update Pakete', NULL, NULL, NULL, NULL, '', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '65', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'update_ressource_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=metadata_update_outdated&ressource_id=$id&force=1;Starte Update', 'Update Ressource', NULL, '', NULL, NULL, '', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '64', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'update_time', 'update_time', 'ressources', 'ba', 'time', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Update-Zeitpunkt', NULL, NULL, NULL, NULL, '', 'Prozesse', '', '1', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "t", "operator": "=", "attribute": "auto_update"}]}', '38', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'upload_file', 'upload_file', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Dokument', '', 'Upload-Datei', NULL, NULL, NULL, NULL, 'Manuell hochgeladene Datei', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "upload", "operator": "=", "attribute": "download_method"}]}', '46', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'download_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=metadata_update_outdated&ressource_id=$id&force=1&method_only=download;run', '', NULL, NULL, NULL, NULL, '', 'Prozesse', '', '1', '2', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "download_method"}]}', '43', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'download_list_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=metadata_list_files&dir=$download_path&ressource_id=$id;list;no_new_window', '', NULL, NULL, NULL, NULL, '', 'Prozesse', '', '1', '2', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": ["urls", "wfs", "wms", "atom", "parallel_from_file"], "operator": "IN", "attribute": "download_method"}]}', '45', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'download_typenames', 'download_typenames', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'TypeNames', NULL, NULL, NULL, NULL, 'Wenn angegeben werden nur diese FeatureTypes runtergeladen, vorausgesetzt sie werden vom Dienst angeboten, sonst alle vom Dienst.', 'Prozesse', '', '1', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "wfs", "operator": "=", "attribute": "download_method"}]}', '42', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'error_msg', 'error_msg', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Fehlermeldung', NULL, '', NULL, NULL, 'Wird automatisch eingetragen wenn ein Fehler beim Download, Entpacken, Importieren oder Transformieren der Ressource auftritt.', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "-1", "operator": "=", "attribute": "status_id"}]}', '63', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'import_epsg', 'import_epsg', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Auswahlfeld', 'SELECT
  srid AS value,
  alias AS output
FROM
  public.spatial_ref_sys_alias
ORDER BY srid
', 'Import-EPSG-Code', NULL, '', NULL, NULL, 'Koordinatenreferenzsystem in dem die Quelledaten eingeladen werden sollen. Zur Auswahl stehen nur die in dieser Anwendung vom Administrator eingestellten Werte.', 'Prozesse', '', '1', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "import_method"}]}', '57', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'import_filter', 'import_filter', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Importfilter', NULL, NULL, NULL, NULL, 'Bedingung, die beim Import verwendet werden soll, z.B. bei SQL-Import eine WHERE-Klausel die passend zum Import-Statement wahr oder falsch liefert.', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "import_method"}]}', '56', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'import_layer', 'import_layer', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Importlayer', NULL, NULL, NULL, NULL, 'Name des Layers, der importiert werden soll. Beim GML-Import z.B. der Name des Featuretyps der in die Tabelle importiert werden soll. Beim CSV-Import der Name der CSV-Datei', 'Prozesse', '', '1', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "import_method"}]}', '55', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'import_link', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=metadata_update_outdated&ressource_id=$id&force=1&method_only=import;run', '', NULL, NULL, NULL, NULL, '', 'Prozesse', '', '1', '2', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "import_method"}]}', '58', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'import_schema', 'import_schema', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Importschema', NULL, NULL, NULL, NULL, 'Name des Schemas in die die Daten importiert werden sollen. Wird hier nichts angegeben wird automatisch in das Schema import importiert.', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "!=", "attribute": "import_method"}]}', '53', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'inquiries', 'inquiries', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Anfragen', NULL, '', NULL, NULL, '', 'Rückfragen;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "t", "operator": "=", "attribute": "any_required"}]}', '9', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'inquiries_responses', 'inquiries_responses', 'ressources', 'ba', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Rückmeldungen', NULL, '', NULL, NULL, 'Rückmeldungen zu Rückfragen zu den Daten', 'Rückfragen;collapsed', '', '0', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "t", "operator": "=", "attribute": "any_required"}]}', '10', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'inquiries_to', 'inquiries_to', 'ressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Anfrage an', NULL, '', NULL, NULL, 'An wen sich die Anfrage richten soll', 'Rückfragen;collapsed', '', '1', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "t", "operator": "=", "attribute": "any_required"}]}', '8', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'subressources', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '5,id:ressource_id,bezeichnung;embedded', 'Teildaten', NULL, '', NULL, NULL, '', 'Prozesse', '', '0', '0', NULL, NULL, NULL, NULL, '2', '{"logic": "AND", "rules": [{"value": "", "operator": "=", "attribute": "download_url"}]}', '40', '0', '0');

-- Datendrucklayout 11 des Layers 3

				INSERT INTO kvwmap.datendrucklayouts 
					("name", "layer_id", "format", "bgsrc", "bgposx", "bgposy", "bgwidth", "bgheight", "dateposx", "dateposy", "datesize", "userposx", "userposy", "usersize", "font_date", "font_user", "type", "margin_top", "margin_bottom", "margin_left", "margin_right", "gap", "no_record_splitting", "columns", "filename", "use_previews") 
				VALUES 
					('AutoLayout_2025-03-19_21-03-35', vars_last_layer_id3, 'A4 hoch', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '0', '50', '50', '50', '50', '20', '0', '0', NULL, '0')RETURNING id INTO vars_last_ddl_id;

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'transform_method', '187', '20', 'import_link', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'import_epsg', '187', '20', 'import_layer', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'import_link', '187', '20', 'import_epsg', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'import_layer', '187', '20', 'import_filter', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'import_filter', '57', '40', 'import_table', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'import_table', '187', '20', 'import_schema', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'import_schema', '187', '20', 'import_file', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'import_file', '187', '20', 'import_method', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'import_method', '187', '20', 'unpack_list_link', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'unpack_list_link', '187', '20', 'unpack_link', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'unpack_link', '187', '20', 'dest_path', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'dest_path', '187', '20', 'unpack_method', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'unpack_method', '187', '20', 'download_list_link', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'download_list_link', '187', '20', 'download_link', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'download_link', '187', '20', 'download_path', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'download_path', '187', '20', 'download_method', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'download_method', '187', '20', 'subressources', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'subressources', '187', '20', 'download_url', NULL, NULL, NULL, '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'download_url', '187', '20', 'update_time', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'update_time', '187', '20', 'update_interval', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'update_interval', '187', '20', 'next_update_at', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'next_update_at', '187', '20', 'auto_update', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'auto_update', '187', '20', 'last_updated_at', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'last_updated_at', '187', '55', 'inquiries_responses', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'inquiries_responses', '57', '40', 'inquiries', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'inquiries', '57', '40', 'inquiries_to', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'inquiries_to', '187', '20', 'inquiries_responsible', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'inquiries_responsible', '225.906', '20', 'any_required', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'any_required', '187', '20', 'check_required', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'check_required', '238.743', '20', 'inquiries_required', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'inquiries_required', '187', '55', 'metadata_document_create_link', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'metadata_document_create_link', '237.555', '20', 'datenziele', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'datenziele', '187', '20', 'datenquellen', NULL, NULL, NULL, '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'datenquellen', '187', '20', 'attribute', NULL, NULL, NULL, '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'attribute', '187', '20', 'github', NULL, NULL, NULL, '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'github', '187', '20', 'quellenvermerk', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'quellenvermerk', '57', '40', 'quelle', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'quelle', '187', '20', 'aktualitaet', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'aktualitaet', '187', '20', 'format_id', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'format_id', '187', '20', 'ansprechperson', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'ansprechperson', '187', '20', 'nutzungsbedingungen', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'nutzungsbedingungen', '57', '40', 'nutzungsberechtigte', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'nutzungsberechtigte', '57', '40', 'dateninhaber_id', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'dateninhaber_id', '187', '20', 'aenderungshinweise', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'aenderungshinweise', '57', '40', 'beschreibung', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'beschreibung', '57', '40', 'hinweise_auf', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'hinweise_auf', '57', '40', 'style', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'style', '187', '20', 'prioritaet', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'prioritaet', '57', '40', 'ampel_id', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'ampel_id', '187', '20', 'bezeichnung', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'bezeichnung', '221.033', '20', 'use_for_datapackage', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'use_for_datapackage', '187', '20', 'id', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'id', '187', '20', 'gruppe_id', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'gruppe_id', '187', '751', NULL, NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'transform_link', '187', '20', 'transform_method', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'transform_command', '57', '40', 'transform_link', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'error_msg', '57', '40', 'transform_command', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'status_id', '187', '20', 'error_msg', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'update_ressource_link', '187', '20', 'status_id', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'update_packages_link', '187', '20', 'update_ressource_link', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'layer_id', '187', '55', 'update_packages_link', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'layer_link', '187', '20', 'layer_id', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'layer_datasources', '187', '55', 'layer_link', NULL, NULL, NULL, '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'pfad', '187', '20', 'layer_datasources', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'data', '187', '20', 'pfad', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'kurzbeschreibung', '187', '20', 'data', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'dataowner_name', '187', '20', 'kurzbeschreibung', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'dataowner_tel', '187', '20', 'dataowner_name', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'dataowner_email', '187', '20', 'dataowner_tel', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'metalink', '187', '20', 'dataowner_email', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'terms_of_use_link', '187', '20', 'metalink', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'update_logs', '187', '55', 'terms_of_use_link', NULL, NULL, NULL, '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'pack_logs', '187', '20', 'update_logs', NULL, NULL, NULL, '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'created_from', '187', '55', 'pack_logs', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'created_at', '187', '20', 'created_from', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'updated_from', '187', '20', 'created_at', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'updated_at', '187', '20', 'updated_from', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Gruppe', '57', '751', NULL, '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 417 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('ID', '57', '20', 'gruppe_id', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 418 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Für Datentool', '57', '20', 'id', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 419 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Bezeichnung der Datenquelle', '57', '20', 'use_for_datapackage', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 420 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Priorität', '57', '20', 'ampel_id', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 422 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Ampel', '57', '20', 'bezeichnung', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 421 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Hinweise auf', '57', '20', 'style', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 424 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Style', '57', '20', 'prioritaet', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 423 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Beschreibung', '57', '20', 'hinweise_auf', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 425 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('pack_logs', '57', '20', 'update_logs', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 489 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('erzeugt von', '57', '55', 'pack_logs', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 490 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Metadaten Link', '57', '20', 'dataowner_email', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 486 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Nutzungsrechte Link', '57', '20', 'metalink', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 487 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Update logs', '57', '55', 'terms_of_use_link', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 488 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('E-Mail', '57', '20', 'dataowner_tel', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 485 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Telefon', '57', '20', 'dataowner_name', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 484 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Dateninhaber Name', '57', '20', 'kurzbeschreibung', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 483 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Kurzbeschreibung', '57', '20', 'data', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 482 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Kartendatenabfrage', '57', '20', 'pfad', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 481 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Sachdatenabfrage', '57', '20', 'layer_datasources', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 480 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Layer Quellen', '57', '55', 'layer_link', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 479 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('layer_link', '57', '20', 'layer_id', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 478 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Update Pakete', '57', '20', 'update_ressource_link', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 476 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Layer', '57', '55', 'update_packages_link', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 477 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Update Ressource', '57', '20', 'status_id', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 475 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Importlayer', '57', '20', 'import_filter', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 467 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Import-EPSG-Code', '57', '20', 'import_layer', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 468 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('import_link', '57', '20', 'import_epsg', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 469 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Prozessmethode', '57', '20', 'import_link', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 470 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('transform_link', '57', '20', 'transform_method', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 471 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Prozessbefehl', '57', '20', 'transform_link', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 472 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Fehlermeldung', '57', '20', 'transform_command', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 473 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Update Status', '57', '20', 'error_msg', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 474 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Importfilter', '57', '20', 'import_table', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 466 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Importtabelle', '57', '20', 'import_schema', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 465 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Importschema', '57', '20', 'import_file', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 464 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Importdatei', '57', '20', 'import_method', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 463 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Importmethode', '57', '20', 'unpack_list_link', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 462 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('unpack_list_link', '57', '20', 'unpack_link', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 461 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('unpack_link', '57', '20', 'dest_path', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 460 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Zielverzeichnis', '57', '20', 'unpack_method', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 459 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Auspackmethode', '57', '20', 'download_list_link', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 458 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('download_list_link', '57', '20', 'download_link', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 457 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Download-Methode', '57', '20', 'subressources', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 454 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Download-Verzeichnis', '57', '20', 'download_method', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 455 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('download_link', '57', '20', 'download_path', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 456 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Teildaten', '57', '20', 'download_url', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 453 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Download-URL', '57', '20', 'update_time', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 452 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Update-Interval', '57', '20', 'next_update_at', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 450 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Update-Zeitpunkt', '57', '20', 'update_interval', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 451 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('nächster Update', '57', '20', 'auto_update', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 449 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Auto-Update', '57', '20', 'last_updated_at', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 448 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('letzter Update', '57', '55', 'inquiries_responses', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 447 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Rückmeldungen', '57', '20', 'inquiries', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 446 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Anfragen', '57', '20', 'inquiries_to', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 445 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Anfrage an', '57', '20', 'inquiries_responsible', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 444 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Für Anfragen verantwortlich', '57', '20', 'any_required', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 443 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('any_required', '57', '20', 'check_required', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 442 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Prüfung der Daten erforderlich', '57', '20', 'inquiries_required', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 441 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Anfragen erforderlich', '57', '55', 'metadata_document_create_link', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 440 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('metadata_document_create_link', '57', '20', 'datenziele', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 439 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Datenziele', '57', '20', 'datenquellen', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 438 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Datenquellen', '57', '20', 'attribute', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 437 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Attribute', '57', '20', 'github', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 436 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Github', '57', '20', 'quellenvermerk', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 435 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Quellenvermerke', '57', '20', 'quelle', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 434 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Quelle', '57', '20', 'aktualitaet', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 433 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Aktualität', '57', '20', 'format_id', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 432 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Format', '57', '20', 'ansprechperson', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 431 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Ansprechperson', '57', '20', 'nutzungsbedingungen', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 430 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Nutzungsbedingungen', '57', '20', 'nutzungsberechtigte', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 429 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Nutzungsberechtigte', '57', '20', 'dateninhaber_id', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 428 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Dateninhaber', '57', '20', 'aenderungshinweise', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 427 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Änderungshinweise', '57', '20', 'beschreibung', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 426 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('am', '57', '20', 'created_from', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 491 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('geändert von', '57', '20', 'created_at', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 492 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('am', '57', '20', 'updated_from', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 493 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Metadaten', '57', '778', NULL, '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 494 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Rückfragen;collapsed', '57', '33', 'metadata_document_create_link', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 495 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Prozesse', '57', '33', 'inquiries_responses', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 496 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Visualisierung', '57', '33', 'update_packages_link', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 497 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Layerangaben;collapsed', '57', '33', 'layer_link', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 498 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Protokollierung;collapsed', '57', '33', 'terms_of_use_link', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 499 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Bearbeitungsvermerke;collapsed', '57', '33', 'pack_logs', '11', NULL, NULL, 'Helvetica-Bold.afm', '0', NULL)RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 500 zu DDL 11
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreirechtecke 
					("posx", "posy", "endposx", "endposy", "breite", "color", "offset_attribute_start", "offset_attribute_end", "type") 
				VALUES 
					('50', '792', '545', '10', '0.5', NULL, NULL, 'metadata_document_create_link', NULL)RETURNING id INTO vars_last_druckfreirechtecke_id;
-- Zuordnung Druckfreirechteck 67 zu DDL 11
INSERT INTO kvwmap.ddl2freirechtecke (ddl_id, rect_id) VALUES (vars_last_ddl_id, vars_last_druckfreirechtecke_id);

				INSERT INTO kvwmap.druckfreirechtecke 
					("posx", "posy", "endposx", "endposy", "breite", "color", "offset_attribute_start", "offset_attribute_end", "type") 
				VALUES 
					('50', '20', '545', '10', '0.5', NULL, 'metadata_document_create_link', 'inquiries_responses', NULL)RETURNING id INTO vars_last_druckfreirechtecke_id;
-- Zuordnung Druckfreirechteck 68 zu DDL 11
INSERT INTO kvwmap.ddl2freirechtecke (ddl_id, rect_id) VALUES (vars_last_ddl_id, vars_last_druckfreirechtecke_id);

				INSERT INTO kvwmap.druckfreirechtecke 
					("posx", "posy", "endposx", "endposy", "breite", "color", "offset_attribute_start", "offset_attribute_end", "type") 
				VALUES 
					('50', '20', '545', '10', '0.5', NULL, 'inquiries_responses', 'update_packages_link', NULL)RETURNING id INTO vars_last_druckfreirechtecke_id;
-- Zuordnung Druckfreirechteck 69 zu DDL 11
INSERT INTO kvwmap.ddl2freirechtecke (ddl_id, rect_id) VALUES (vars_last_ddl_id, vars_last_druckfreirechtecke_id);

				INSERT INTO kvwmap.druckfreirechtecke 
					("posx", "posy", "endposx", "endposy", "breite", "color", "offset_attribute_start", "offset_attribute_end", "type") 
				VALUES 
					('50', '20', '545', '10', '0.5', NULL, 'update_packages_link', 'layer_link', NULL)RETURNING id INTO vars_last_druckfreirechtecke_id;
-- Zuordnung Druckfreirechteck 70 zu DDL 11
INSERT INTO kvwmap.ddl2freirechtecke (ddl_id, rect_id) VALUES (vars_last_ddl_id, vars_last_druckfreirechtecke_id);

				INSERT INTO kvwmap.druckfreirechtecke 
					("posx", "posy", "endposx", "endposy", "breite", "color", "offset_attribute_start", "offset_attribute_end", "type") 
				VALUES 
					('50', '20', '545', '10', '0.5', NULL, 'layer_link', 'terms_of_use_link', NULL)RETURNING id INTO vars_last_druckfreirechtecke_id;
-- Zuordnung Druckfreirechteck 71 zu DDL 11
INSERT INTO kvwmap.ddl2freirechtecke (ddl_id, rect_id) VALUES (vars_last_ddl_id, vars_last_druckfreirechtecke_id);

				INSERT INTO kvwmap.druckfreirechtecke 
					("posx", "posy", "endposx", "endposy", "breite", "color", "offset_attribute_start", "offset_attribute_end", "type") 
				VALUES 
					('50', '20', '545', '10', '0.5', NULL, 'terms_of_use_link', 'pack_logs', NULL)RETURNING id INTO vars_last_druckfreirechtecke_id;
-- Zuordnung Druckfreirechteck 72 zu DDL 11
INSERT INTO kvwmap.ddl2freirechtecke (ddl_id, rect_id) VALUES (vars_last_ddl_id, vars_last_druckfreirechtecke_id);

				INSERT INTO kvwmap.druckfreirechtecke 
					("posx", "posy", "endposx", "endposy", "breite", "color", "offset_attribute_start", "offset_attribute_end", "type") 
				VALUES 
					('50', '20', '545', '10', '0.5', NULL, 'pack_logs', 'updated_at', NULL)RETURNING id INTO vars_last_druckfreirechtecke_id;
-- Zuordnung Druckfreirechteck 73 zu DDL 11
INSERT INTO kvwmap.ddl2freirechtecke (ddl_id, rect_id) VALUES (vars_last_ddl_id, vars_last_druckfreirechtecke_id);

				INSERT INTO kvwmap.druckfreirechtecke 
					("posx", "posy", "endposx", "endposy", "breite", "color", "offset_attribute_start", "offset_attribute_end", "type") 
				VALUES 
					('50', '792', '545', '773', '0.5', NULL, NULL, NULL, NULL)RETURNING id INTO vars_last_druckfreirechtecke_id;
-- Zuordnung Druckfreirechteck 74 zu DDL 11
INSERT INTO kvwmap.ddl2freirechtecke (ddl_id, rect_id) VALUES (vars_last_ddl_id, vars_last_druckfreirechtecke_id);

				INSERT INTO kvwmap.druckfreirechtecke 
					("posx", "posy", "endposx", "endposy", "breite", "color", "offset_attribute_start", "offset_attribute_end", "type") 
				VALUES 
					('50', '20', '545', '39', '0.5', NULL, 'metadata_document_create_link', 'metadata_document_create_link', NULL)RETURNING id INTO vars_last_druckfreirechtecke_id;
-- Zuordnung Druckfreirechteck 75 zu DDL 11
INSERT INTO kvwmap.ddl2freirechtecke (ddl_id, rect_id) VALUES (vars_last_ddl_id, vars_last_druckfreirechtecke_id);

				INSERT INTO kvwmap.druckfreirechtecke 
					("posx", "posy", "endposx", "endposy", "breite", "color", "offset_attribute_start", "offset_attribute_end", "type") 
				VALUES 
					('50', '20', '545', '39', '0.5', NULL, 'inquiries_responses', 'inquiries_responses', NULL)RETURNING id INTO vars_last_druckfreirechtecke_id;
-- Zuordnung Druckfreirechteck 76 zu DDL 11
INSERT INTO kvwmap.ddl2freirechtecke (ddl_id, rect_id) VALUES (vars_last_ddl_id, vars_last_druckfreirechtecke_id);

				INSERT INTO kvwmap.druckfreirechtecke 
					("posx", "posy", "endposx", "endposy", "breite", "color", "offset_attribute_start", "offset_attribute_end", "type") 
				VALUES 
					('50', '20', '545', '39', '0.5', NULL, 'update_packages_link', 'update_packages_link', NULL)RETURNING id INTO vars_last_druckfreirechtecke_id;
-- Zuordnung Druckfreirechteck 77 zu DDL 11
INSERT INTO kvwmap.ddl2freirechtecke (ddl_id, rect_id) VALUES (vars_last_ddl_id, vars_last_druckfreirechtecke_id);

				INSERT INTO kvwmap.druckfreirechtecke 
					("posx", "posy", "endposx", "endposy", "breite", "color", "offset_attribute_start", "offset_attribute_end", "type") 
				VALUES 
					('50', '20', '545', '39', '0.5', NULL, 'layer_link', 'layer_link', NULL)RETURNING id INTO vars_last_druckfreirechtecke_id;
-- Zuordnung Druckfreirechteck 78 zu DDL 11
INSERT INTO kvwmap.ddl2freirechtecke (ddl_id, rect_id) VALUES (vars_last_ddl_id, vars_last_druckfreirechtecke_id);

				INSERT INTO kvwmap.druckfreirechtecke 
					("posx", "posy", "endposx", "endposy", "breite", "color", "offset_attribute_start", "offset_attribute_end", "type") 
				VALUES 
					('50', '20', '545', '39', '0.5', NULL, 'terms_of_use_link', 'terms_of_use_link', NULL)RETURNING id INTO vars_last_druckfreirechtecke_id;
-- Zuordnung Druckfreirechteck 79 zu DDL 11
INSERT INTO kvwmap.ddl2freirechtecke (ddl_id, rect_id) VALUES (vars_last_ddl_id, vars_last_druckfreirechtecke_id);

				INSERT INTO kvwmap.druckfreirechtecke 
					("posx", "posy", "endposx", "endposy", "breite", "color", "offset_attribute_start", "offset_attribute_end", "type") 
				VALUES 
					('50', '20', '545', '39', '0.5', NULL, 'pack_logs', 'pack_logs', NULL)RETURNING id INTO vars_last_druckfreirechtecke_id;
-- Zuordnung Druckfreirechteck 80 zu DDL 11
INSERT INTO kvwmap.ddl2freirechtecke (ddl_id, rect_id) VALUES (vars_last_ddl_id, vars_last_druckfreirechtecke_id);

-- Datendrucklayout 4 des Layers 3

				INSERT INTO kvwmap.datendrucklayouts 
					("name", "layer_id", "format", "bgsrc", "bgposx", "bgposy", "bgwidth", "bgheight", "dateposx", "dateposy", "datesize", "userposx", "userposy", "usersize", "font_date", "font_user", "type", "margin_top", "margin_bottom", "margin_left", "margin_right", "gap", "no_record_splitting", "columns", "filename", "use_previews") 
				VALUES 
					('Metadaten', vars_last_layer_id3, 'A4 hoch', 'lena.2020_logo_v2_basis.100_4c.png', '400', '600', '30', '30', '180', '770', '9', NULL, NULL, NULL, 'Helvetica.afm', '', '0', '20', '20', '20', '20', '20', '0', '0', 'Metadaten_Ressource_${id}', '0')RETURNING id INTO vars_last_ddl_id;

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'url', '187', '20', 'aktualitaet', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'bemerkung_prioritaet', '57', '35', 'flaechendeckend', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'digital', '220', '20', 'relevanz', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'flaechendeckend', '220', '20', 'digital', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'relevanz', '220', '20', 'datenguete_id', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'datenguete_id', '187', '20', 'id', NULL, NULL, 'Helvetica.afm', '11');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'import_epsg', '30', '18', 'import_layer', '450', NULL, 'Helvetica.afm', '9');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'layer_id', '30', '18', 'id', '450', NULL, 'Helvetica.afm', '9');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'layer_datasources', '30', '18', 'dataowner_name', NULL, NULL, '6', NULL);

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'pfad', '30', '18', 'transform_command', '450', NULL, 'Helvetica.afm', '9');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'data', '30', '18', 'pfad', '450', NULL, 'Helvetica.afm', '9');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'dateninhaber_id', '30', '18', 'quellenvermerk', '450', NULL, 'Helvetica.afm', '9');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'last_updated_at', '233', '30', 'bezeichnung', '120', NULL, 'Helvetica-Bold.afm', '9');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'id', '30', '40', 'datenziele', '450', NULL, 'Helvetica.afm', '9');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'update_interval', '462', '30', 'bezeichnung', '100', NULL, 'Helvetica.afm', '9');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'bezeichnung', '30', '800', NULL, '450', NULL, 'Helvetica-Bold.afm', '14');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'hinweise_auf', '30', '18', 'beschreibung', '450', NULL, 'Helvetica.afm', '9');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'beschreibung', '30', '50', 'bezeichnung', '555', NULL, 'Helvetica.afm', '9');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'aenderungshinweise', '30', '18', 'hinweise_auf', '450', NULL, 'Helvetica.afm', '9');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'datasource_id', '30', '18', 'quellenvermerk', NULL, NULL, 'Helvetica.afm', '9');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'nutzungsberechtigte', '30', '18', 'aenderungshinweise', '450', NULL, 'Helvetica.afm', '9');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'nutzungsbedingungen', '30', '18', 'nutzungsberechtigte', '450', NULL, 'Helvetica.afm', '9');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'format_id', '30', '18', 'quelle', '450', NULL, 'Helvetica.afm', '9');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'aktualitaet', '373', '30', 'bezeichnung', '120', NULL, 'Helvetica.afm', '9');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'quelle', '30', '18', 'datasource_id', '450', NULL, 'Helvetica.afm', '9');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'quellenvermerk', '30', '18', 'nutzungsbedingungen', '450', NULL, 'Helvetica.afm', '9');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'datenquellen', '29', '1', 'import_epsg', NULL, NULL, '7', NULL);

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'datenziele', '29', '1', 'datenquellen', NULL, NULL, '8', NULL);

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'layer_link', '70', '750', NULL, NULL, NULL, NULL, '13');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'import_layer', '30', '18', 'format_id', '450', NULL, 'Helvetica.afm', '9');

				INSERT INTO kvwmap.ddl_elemente 
					("ddl_id", "name", "xpos", "ypos", "offset_attribute", "width", "border", "font", "fontsize") 
				VALUES 
					(vars_last_ddl_id, 'transform_command', '30', '18', 'layer_id', '450', NULL, 'Helvetica.afm', '9');

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Aktualitätsstand:', '30', '30', 'bezeichnung', '9', '140', NULL, 'Helvetica-Bold.afm', NULL, '0')RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 588 zu DDL 4
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreitexte 
					("text", "posx", "posy", "offset_attribute", "size", "width", "border", "font", "angle", "type") 
				VALUES 
					('Datenpaket:', '130', '30', 'bezeichnung', '9', '85', NULL, 'Helvetica.afm', NULL, '0')RETURNING id INTO vars_last_druckfreitexte_id;
-- Zuordnung Druckfreitext 587 zu DDL 4
INSERT INTO kvwmap.ddl2freitexte (ddl_id, freitext_id) VALUES (vars_last_ddl_id, vars_last_druckfreitexte_id);

				INSERT INTO kvwmap.druckfreirechtecke 
					("posx", "posy", "endposx", "endposy", "breite", "color", "offset_attribute_start", "offset_attribute_end", "type") 
				VALUES 
					('25', '5', '570', '30', '1', NULL, 'import_epsg', 'datenziele', '0')RETURNING id INTO vars_last_druckfreirechtecke_id;
-- Zuordnung Druckfreirechteck 102 zu DDL 4
INSERT INTO kvwmap.ddl2freirechtecke (ddl_id, rect_id) VALUES (vars_last_ddl_id, vars_last_druckfreirechtecke_id);

				INSERT INTO kvwmap.druckfreirechtecke 
					("posx", "posy", "endposx", "endposy", "breite", "color", "offset_attribute_start", "offset_attribute_end", "type") 
				VALUES 
					('25', '16', '570', '37', '1', '3', 'bezeichnung', 'bezeichnung', '0')RETURNING id INTO vars_last_druckfreirechtecke_id;
-- Zuordnung Druckfreirechteck 101 zu DDL 4
INSERT INTO kvwmap.ddl2freirechtecke (ddl_id, rect_id) VALUES (vars_last_ddl_id, vars_last_druckfreirechtecke_id);

-- Layer 7

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Ressourcengruppen', NULL, '', NULL, NULL, NULL, '5', vars_group_id_61, 'SELECT
  id,
  gruppe,
  beschreibung
FROM
  metadata.gruppen
WHERE
  1=1
ORDER bY gruppe', 'gruppen', 'id', '', '0', '', 'metadata', NULL, '', '', '', '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', '', '', NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '0', NULL, '500', '7', NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', '', '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id7;

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'beschreibung', 'beschreibung', 'gruppen', 'gruppen', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Beschreibung', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '3', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'gruppe', 'gruppe', 'gruppen', 'gruppen', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Gruppenname', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '2', '0', '0');

-- Attribut  des Layers 7

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id7, 'id', 'id', 'gruppen', 'gruppen', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''gruppen_id_seq''::regclass)', 'Text', '', 'ID', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '1', '0', '0');

-- Layer 6

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Ressourcenteilbereiche', NULL, '', NULL, NULL, NULL, '5', vars_group_id_61, 'SELECT
  id,
  subressource_id,
  name,
  von,
  bis,
  step
FROM
  metadata.subressourceranges
WHERE
  1=1', 'subressourceranges', 'id', '', '0', '', 'metadata', NULL, '', '', '', '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', '', '', NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '0', NULL, '500', '11', NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', '', '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id6;

-- Attribut  des Layers 6

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id6, 'bis', 'bis', 'subressourceranges', 'subressourceranges', 'int4', '', '', '1', '1', '32', '0', '', 'Zahl', '', 'bis', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '5', '0', '0');

-- Attribut  des Layers 6

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id6, 'id', 'id', 'subressourceranges', 'subressourceranges', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''subressourceranges_id_seq''::regclass)', 'Text', '', '', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '1', '0', '0');

-- Attribut  des Layers 6

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id6, 'name', 'name', 'subressourceranges', 'subressourceranges', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Name', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '3', '0', '0');

-- Attribut  des Layers 6

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id6, 'step', 'step', 'subressourceranges', 'subressourceranges', 'int4', '', '', '1', '1', '32', '0', '', 'Zahl', '', 'Schritt', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '6', '0', '0');

-- Attribut  des Layers 6

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id6, 'subressource_id', 'subressource_id', 'subressourceranges', 'subressourceranges', 'int4', '', '', '1', '0', '32', '0', '', 'Text', '', '', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '2', '0', '0');

-- Attribut  des Layers 6

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id6, 'von', 'von', 'subressourceranges', 'subressourceranges', 'int4', '', '', '1', '1', '32', '0', '', 'Zahl', '', 'von', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '4', '0', '0');

-- Layer 5

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Ressourcenteile', NULL, '', NULL, NULL, NULL, '5', vars_group_id_61, 'SELECT
  id,
  ressource_id,
  bezeichnung,
  download_url,
  '''' ressourceranges
FROM
  metadata.subressources ba
WHERE
  1=1', 'subressources', 'id', '', '0', '', 'metadata', NULL, '', '', '', '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', '', '', NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '0', NULL, '500', '16', NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', '', '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id5;

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'bezeichnung', 'bezeichnung', 'subressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Bezeichnung', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '3', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'download_url', 'download_url', 'subressources', 'ba', 'varchar', '', '', '1', '1', NULL, NULL, '', 'Link', '', 'Download-URL', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '4', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'id', 'id', 'subressources', 'ba', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''subressources_id_seq''::regclass)', 'Text', '', 'ID', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '1', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'ressourceranges', '', '', '', 'text', '', '', '0', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '6,id:subressource_id,name Beginn von Ende bis Schritt step;embedded', 'Bereiche', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '5', '0', '0');

-- Attribut  des Layers 5

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id5, 'ressource_id', 'ressource_id', 'subressources', 'ba', 'int4', '', '', '1', '0', '32', '0', '', 'Text', '', 'Datensatz', NULL, '', NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '2', '0', '0');

-- Layer 25

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('update_logs', NULL, NULL, NULL, NULL, 'Update Logs', '5', vars_group_id_61, 'SELECT
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
  1=1
ORDER BY
  update_at DESC', 'update_logs', 'id', '', '0', '', 'metadata', NULL, '', '', '', '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', '', '', NULL, '3', 'pixels', NULL, '25832', NULL, NULL, 't', '0', NULL, '500', '1', NULL, NULL, NULL, NULL, NULL, 'EPSG:4326', '', '', '1.0.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '1', NULL, '', '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id25;

-- Attribut  des Layers 25

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id25, 'abbruch_status_id', 'abbruch_status_id', 'update_logs', 'l', 'int4', '', '', '1', '1', '32', '0', '', 'Text', '', 'ID', NULL, NULL, NULL, NULL, '', '', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '3', '0', '0');

-- Attribut  des Layers 25

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id25, 'bezeichnung', 'bezeichnung', 'ressources', 'r', 'text', '', '', '1', '1', NULL, NULL, '', 'Text', '', 'Ressource', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '7', '0', '0');

-- Attribut  des Layers 25

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id25, 'id', 'id', 'update_logs', 'l', 'int4', '', 'PRIMARY KEY', '1', '1', '32', '0', 'nextval(''update_log_id_seq''::regclass)', 'Text', '', 'ID', NULL, NULL, NULL, NULL, '', '', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '1', '0', '0');

-- Attribut  des Layers 25

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id25, 'msg', 'msg', 'update_logs', 'l', 'text', '', '', '1', '1', NULL, NULL, '', 'Textfeld', '', 'Meldung', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '5', '0', '0');

-- Attribut  des Layers 25

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id25, 'ressource_id', 'ressource_id', 'update_logs', 'l', 'int4', '', '', '1', '0', '32', '0', '', 'Text', '', 'ID', NULL, NULL, NULL, NULL, '', '', '', '1', '0', NULL, NULL, NULL, NULL, '1', NULL, '6', '0', '0');

-- Attribut  des Layers 25

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id25, 'status', 'status', 'update_status', 's', 'varchar', '', '', '1', '0', NULL, NULL, '', 'Text', '', 'Abbruch Status', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '4', '0', '0');

-- Attribut  des Layers 25

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "visibility_rules", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id25, 'update_at', 'update_at', 'update_logs', 'l', 'timestamp', '', '', '1', '0', NULL, NULL, 'now()', 'Text', '', 'update am', NULL, NULL, NULL, NULL, '', '', '', '0', '0', NULL, NULL, NULL, NULL, '1', NULL, '2', '0', '0');

-- Replace attribute options for Layer 11
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=11', CONCAT('layer_id=', vars_last_layer_id11)) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=11( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '11,', CONCAT(vars_last_layer_id11, ',')) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '11,%';

-- Replace attribute options for Layer 9
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=9', CONCAT('layer_id=', vars_last_layer_id9)) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=9( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '9,', CONCAT(vars_last_layer_id9, ',')) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '9,%';

-- Replace attribute options for Layer 10
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=10', CONCAT('layer_id=', vars_last_layer_id10)) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=10( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '10,', CONCAT(vars_last_layer_id10, ',')) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '10,%';

-- Replace attribute options for Layer 145
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=145', CONCAT('layer_id=', vars_last_layer_id145)) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=145( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '145,', CONCAT(vars_last_layer_id145, ',')) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '145,%';

-- Replace attribute options for Layer 305
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=305', CONCAT('layer_id=', vars_last_layer_id305)) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=305( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '305,', CONCAT(vars_last_layer_id305, ',')) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '305,%';

-- Replace attribute options for Layer 26
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=26', CONCAT('layer_id=', vars_last_layer_id26)) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=26( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '26,', CONCAT(vars_last_layer_id26, ',')) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '26,%';

-- Replace attribute options for Layer 15
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=15', CONCAT('layer_id=', vars_last_layer_id15)) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=15( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '15,', CONCAT(vars_last_layer_id15, ',')) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '15,%';

-- Replace attribute options for Layer 140
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=140', CONCAT('layer_id=', vars_last_layer_id140)) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=140( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '140,', CONCAT(vars_last_layer_id140, ',')) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '140,%';

-- Replace attribute options for Layer 3
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=3', CONCAT('layer_id=', vars_last_layer_id3)) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=3( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '3,', CONCAT(vars_last_layer_id3, ',')) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '3,%';

-- Replace attribute options for Layer 7
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=7', CONCAT('layer_id=', vars_last_layer_id7)) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=7( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '7,', CONCAT(vars_last_layer_id7, ',')) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '7,%';

-- Replace attribute options for Layer 6
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=6', CONCAT('layer_id=', vars_last_layer_id6)) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=6( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '6,', CONCAT(vars_last_layer_id6, ',')) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '6,%';

-- Replace attribute options for Layer 5
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=5', CONCAT('layer_id=', vars_last_layer_id5)) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=5( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '5,', CONCAT(vars_last_layer_id5, ',')) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '5,%';

-- Replace attribute options for Layer 25
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=25', CONCAT('layer_id=', vars_last_layer_id25)) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=25( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '25,', CONCAT(vars_last_layer_id25, ',')) WHERE layer_id IN (vars_last_layer_id11, vars_last_layer_id9, vars_last_layer_id10, vars_last_layer_id145, vars_last_layer_id305, vars_last_layer_id26, vars_last_layer_id15, vars_last_layer_id140, vars_last_layer_id3, vars_last_layer_id7, vars_last_layer_id6, vars_last_layer_id5, vars_last_layer_id25) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '25,%';
END $$