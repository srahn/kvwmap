
-- Layerdump aus kvwmap vom 28.05.2025 14:26:22
-- Achtung: Die Datenbank in die der Dump eingespielt wird, sollte die gleiche Migrationsversion haben,
-- wie die Datenbank aus der exportiert wurde! Anderenfalls kann es zu Fehlern bei der Ausführung des SQL kommen.

DO $$
	DECLARE 
		vars_connection_id integer;
		
		vars_group_id_98 integer;
		
		vars_last_layer_id20 integer;
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
					('ProBAUG', NULL, NULL, NULL, NULL, NULL, '6', 'f', NULL)RETURNING id INTO vars_group_id_98;

-- Layer 20

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Bauakten Geometrien', NULL, NULL, NULL, NULL, '', '0', vars_group_id_98, 'select *, '''' as bauakte from bau_geometrien where 1=1', 'bau_geometrien', 'id', NULL, '0', 'the_geom from (select oid, the_geom from probaug.bau_geometrien) as foo using unique oid using srid=25833', 'probaug', NULL, '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', '', NULL, NULL, NULL, '3', 'pixels', NULL, '25833', '', NULL, 't', '1', NULL, '10000', NULL, NULL, NULL, NULL, '', NULL, 'EPSG:25833', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 't', 'f', '', '', NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id20;

-- Attribut  des Layers 20

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id20, 'aktenzeichen', 'aktenzeichen', 'bau_geometrien', 'bau_geometrien', 'int4', '', '', NULL, '0', '32', '0', '', 'Text', '', 'Aktenzeichen', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 20

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id20, 'bauakte', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Baudatenanzeige&jahr=$jahr&nummer=$aktenzeichen;anzeigen;no_new_window', 'Bauakte', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 20

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id20, 'gid', 'gid', 'bau_geometrien', 'bau_geometrien', 'int4', '', 'PRIMARY KEY', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 20

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id20, 'jahr', 'jahr', 'bau_geometrien', 'bau_geometrien', 'int4', '', '', NULL, '0', '32', '0', '', 'Text', '', 'Jahr', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 20

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id20, 'status', 'status', 'bau_geometrien', 'bau_geometrien', 'bool', '', '', NULL, '0', NULL, NULL, 'SELECT false', 'Auswahlfeld', 'select ''ungeprüft'' as output, false as value
union
select ''geprüft'' as output, true as value', 'Status', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 20

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id20, 'the_geom', 'the_geom', 'bau_geometrien', 'bau_geometrien', 'geometry', 'GEOMETRY', '', NULL, '1', NULL, NULL, '', 'Geometrie', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, '0', NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Class 21 des Layers 20

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('alle', vars_last_layer_id20, '', '6', '')RETURNING class_id INTO vars_last_class_id;

-- Style 16 der Class 21

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'circle', '6', '241 211 0', '0 0 0', NULL, NULL, NULL, NULL, '6', '6', NULL, NULL, '0', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 16 zu Class 21
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Label 10 der Class 21

				INSERT INTO kvwmap.labels 
					("font", "type", "color", "outlinecolor", "shadowcolor", "shadowsizex", "shadowsizey", "backgroundcolor", "backgroundshadowcolor", "backgroundshadowsizex", "backgroundshadowsizey", "size", "minsize", "maxsize", "position", "offsetx", "offsety", "angle", "anglemode", "buffer", "minfeaturesize", "maxfeaturesize", "partials", "wrap", "the_force") 
				VALUES 
					('arial', NULL, '0 0 0', '255 255 255', '', NULL, NULL, '', '', NULL, NULL, '6', '5', '7', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING label_id INTO vars_last_label_id;
-- Zuordnung Label 10 zu Class 21
INSERT INTO kvwmap.u_labels2classes (label_id, class_id) VALUES (vars_last_label_id, vars_last_class_id);

-- Replace attribute options for Layer 20
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=20', CONCAT('layer_id=', vars_last_layer_id20)) WHERE layer_id IN (vars_last_layer_id20) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=20( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '20,', CONCAT(vars_last_layer_id20, ',')) WHERE layer_id IN (vars_last_layer_id20) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '20,%';

END $$