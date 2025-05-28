
-- Layerdump aus kvwmap vom 28.05.2025 13:35:14
-- Achtung: Die Datenbank in die der Dump eingespielt wird, sollte die gleiche Migrationsversion haben,
-- wie die Datenbank aus der exportiert wurde! Anderenfalls kann es zu Fehlern bei der Ausführung des SQL kommen.

DO $$
	DECLARE 
		vars_connection_id integer;
		
		vars_group_id_100 integer;
		
		vars_last_layer_id3 integer;
		vars_last_layer_id4 integer;
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
					('Anliegerbeiträge', NULL, NULL, NULL, NULL, NULL, '6', 'f', NULL)RETURNING id INTO vars_group_id_100;

-- Layer 3

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('AB_Bereiche', NULL, NULL, NULL, NULL, '', '2', vars_group_id_100, 'select * from anliegerbeitraege_bereiche where 1=1', 'anliegerbeitraege_bereiche', 'id', NULL, '0', 'the_geom from (select oid, id, flaeche, flaeche||'' m²'' as flaechenangabe, the_geom from anliegerbeitraege.anliegerbeitraege_bereiche) as foo using unique oid using srid=2398', 'anliegerbeitraege', NULL, '', NULL, NULL, '', '', '', 'flaechenangabe', '10000', '0', '', '0', '', vars_connection_id, '', '6', 'flaeche', NULL, NULL, NULL, '3', 'pixels', NULL, '2398', '', NULL, 'f', '1', NULL, '0', NULL, NULL, NULL, NULL, '', NULL, 'EPSG:2398', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 't', 'f', '', '', NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, '2', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id3;

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'flaeche', 'flaeche', 'anliegerbeitraege_bereiche', 'anliegerbeitraege_bereiche', 'float4', '', '', NULL, '1', '24', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '1', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'id', 'id', 'anliegerbeitraege_bereiche', 'anliegerbeitraege_bereiche', 'int4', '', 'PRIMARY KEY', NULL, '1', '32', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'kommentar', 'kommentar', 'anliegerbeitraege_bereiche', 'anliegerbeitraege_bereiche', 'varchar', '', '', NULL, '1', '255', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '1', '0');

-- Attribut  des Layers 3

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id3, 'the_geom', 'the_geom', 'anliegerbeitraege_bereiche', 'anliegerbeitraege_bereiche', 'geometry', 'POLYGON', '', NULL, '1', NULL, NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '1', '0');

-- Class 1 des Layers 3

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('alle', vars_last_layer_id3, '', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 1 der Class 1

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '1', '206 255 70', '0 0 0', NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, '1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 1 zu Class 1
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Label 1 der Class 1

				INSERT INTO kvwmap.labels 
					("font", "type", "color", "outlinecolor", "shadowcolor", "shadowsizex", "shadowsizey", "backgroundcolor", "backgroundshadowcolor", "backgroundshadowsizex", "backgroundshadowsizey", "size", "minsize", "maxsize", "position", "offsetx", "offsety", "angle", "anglemode", "buffer", "minfeaturesize", "maxfeaturesize", "partials", "wrap", "the_force") 
				VALUES 
					('arial', '0', '0 0 0', '255 255 255', '', NULL, NULL, '', '', NULL, NULL, '6', '5', '7', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING label_id INTO vars_last_label_id;
-- Zuordnung Label 1 zu Class 1
INSERT INTO kvwmap.u_labels2classes (label_id, class_id) VALUES (vars_last_label_id, vars_last_class_id);

-- Layer 4

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('AB_Strassen', NULL, NULL, NULL, NULL, '', '2', vars_group_id_100, 'select id, the_geom from anliegerbeitraege_strassen where 1=1', 'anliegerbeitraege_strassen', 'id', NULL, '0', 'the_geom from (select oid, id, the_geom from anliegerbeitraege.anliegerbeitraege_strassen) as foo using unique oid using srid=2398', 'anliegerbeitraege', NULL, '', NULL, NULL, '', '', '', '', '50000', '0', '', '0', '', vars_connection_id, '', '6', 'id', NULL, NULL, NULL, '3', 'pixels', NULL, '2398', '', NULL, 'f', '1', NULL, '0', NULL, NULL, NULL, NULL, '', NULL, 'EPSG:2398', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 'f', '', '', NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id4;

-- Attribut  des Layers 4

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id4, 'id', 'id', 'anliegerbeitraege_strassen', 'anliegerbeitraege_strassen', 'int4', '', 'PRIMARY KEY', NULL, '1', '32', NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 4

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id4, 'the_geom', 'the_geom', 'anliegerbeitraege_strassen', 'anliegerbeitraege_strassen', 'geometry', 'POLYGON', '', NULL, '1', NULL, NULL, NULL, 'Text', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Class 2 des Layers 4

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('Anschlussbeiträge', vars_last_layer_id4, '', '2', '')RETURNING class_id INTO vars_last_class_id;

-- Style 2 der Class 2

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '175 165 80', '000 000 000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 2 zu Class 2
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Replace attribute options for Layer 3
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=3', CONCAT('layer_id=', vars_last_layer_id3)) WHERE layer_id IN (vars_last_layer_id3, vars_last_layer_id4) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=3( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '3,', CONCAT(vars_last_layer_id3, ',')) WHERE layer_id IN (vars_last_layer_id3, vars_last_layer_id4) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '3,%';

-- Replace attribute options for Layer 4
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=4', CONCAT('layer_id=', vars_last_layer_id4)) WHERE layer_id IN (vars_last_layer_id3, vars_last_layer_id4) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=4( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '4,', CONCAT(vars_last_layer_id4, ',')) WHERE layer_id IN (vars_last_layer_id3, vars_last_layer_id4) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '4,%';

END $$