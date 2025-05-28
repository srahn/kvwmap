
-- Layerdump aus kvwmap vom 28.05.2025 13:48:54
-- Achtung: Die Datenbank in die der Dump eingespielt wird, sollte die gleiche Migrationsversion haben,
-- wie die Datenbank aus der exportiert wurde! Anderenfalls kann es zu Fehlern bei der Ausführung des SQL kommen.

DO $$
	DECLARE 
		vars_connection_id integer;
		
		vars_group_id_96 integer;
		vars_group_id_95 integer;
		
		vars_last_layer_id21 integer;
		vars_last_layer_id22 integer;
		vars_last_layer_id29 integer;
		vars_last_layer_id30 integer;
		vars_last_layer_id31 integer;
		vars_last_layer_id32 integer;
		vars_last_layer_id33 integer;
		vars_last_layer_id34 integer;
		vars_last_layer_id35 integer;
		vars_last_layer_id36 integer;
		vars_last_layer_id37 integer;
		vars_last_layer_id38 integer;
		vars_last_layer_id42 integer;
		vars_last_layer_id43 integer;
		vars_last_layer_id51 integer;
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
					('Jagdkataster-Abrundung', NULL, NULL, NULL, NULL, NULL, NULL, 'f', NULL)RETURNING id INTO vars_group_id_96;

				INSERT INTO kvwmap.u_groups 
					("gruppenname", "gruppenname_low_german", "gruppenname_english", "gruppenname_polish", "gruppenname_vietnamese", "obergruppe", "order", "selectable_for_shared_layers", "icon") 
				VALUES 
					('Jagdbezirke', NULL, NULL, NULL, NULL, NULL, NULL, 'f', NULL)RETURNING id INTO vars_group_id_95;

-- Layer 21

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Befriedete Bezirke', NULL, NULL, NULL, NULL, '', '2', vars_group_id_95, 'SELECT id, name, zu_gjb, nutzungen, the_geom FROM view_befriedete_bezirke bf WHERE 1=1', 'view_befriedete_bezirke', 'id', NULL, '0', 'the_geom from jagdkataster.befriedete_bezirke as foo using unique id using srid=25833', 'jagdkataster', NULL, '', NULL, NULL, '', '', '', 'name', '20000', '100', '', '0', '', vars_connection_id, '', '6', 'id', NULL, NULL, NULL, '3', 'pixels', NULL, '25833', '', NULL, 't', '1', '80', '100', NULL, '100', '50000', NULL, '', NULL, 'EPSG:25833', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 't', 't', '', '', NULL, 'untere Jagdbehörde', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id21;

-- Attribut  des Layers 21

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id21, 'id', 'id', 'view_befriedete_bezirke', 'bf', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', 'ID Bezirk', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 21

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id21, 'name', 'name', 'view_befriedete_bezirke', 'bf', 'varchar', '', '', NULL, '1', '50', NULL, '', 'Text', '', 'name', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '1');

-- Attribut  des Layers 21

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id21, 'nutzungen', 'nutzungen', 'view_befriedete_bezirke', 'bf', 'text', '', '', NULL, '1', NULL, NULL, '', 'SubFormPK', '22,id;no_new_window', 'enthaltene Nutzungen', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 21

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id21, 'the_geom', 'the_geom', 'view_befriedete_bezirke', 'bf', 'geometry', 'MULTIPOLYGON', '', NULL, '1', NULL, NULL, '', 'Geometrie', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 21

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id21, 'zu_gjb', 'zu_gjb', 'view_befriedete_bezirke', 'bf', 'varchar', '', '', NULL, '1', '50', NULL, '', 'Text', '', 'zu gemeinschaftl. Jagdbezirk', '', '', '', '', 'Zuständigkeit', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Class 22 des Layers 21

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					(' ', vars_last_layer_id21, '', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 17 der Class 22

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '214 238 244', '-1 -1 -1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 17 zu Class 22
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Style 18 der Class 22

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '', '124 144 152', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', '3', '1.00', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 18 zu Class 22
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 1);

-- Label 11 der Class 22

				INSERT INTO kvwmap.labels 
					("font", "type", "color", "outlinecolor", "shadowcolor", "shadowsizex", "shadowsizey", "backgroundcolor", "backgroundshadowcolor", "backgroundshadowsizex", "backgroundshadowsizey", "size", "minsize", "maxsize", "position", "offsetx", "offsety", "angle", "anglemode", "buffer", "minfeaturesize", "maxfeaturesize", "partials", "wrap", "the_force") 
				VALUES 
					('arial', NULL, '81 97 104', '255 255 255', '', NULL, NULL, '', '', NULL, NULL, '8', '6', '10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL)RETURNING label_id INTO vars_last_label_id;
-- Zuordnung Label 11 zu Class 22
INSERT INTO kvwmap.u_labels2classes (label_id, class_id) VALUES (vars_last_label_id, vars_last_class_id);

-- Layer 22

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Befriedete Bezirke - Nutzungen', NULL, NULL, NULL, NULL, '', '5', vars_group_id_95, 'select * from befriedete_bezirke_flurstuecke WHERE 1=1', 'befriedete_bezirke_flurstuecke', 'id', NULL, '0', '', 'jagdkataster', NULL, '', NULL, NULL, '', '', '', '', NULL, NULL, '', '0', '', vars_connection_id, '', '6', 'id', NULL, NULL, NULL, '3', 'pixels', NULL, '25833', '', NULL, 't', '1', NULL, '100', NULL, '-1', '-1', NULL, '', NULL, 'EPSG:25833', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 't', '', '', NULL, 'untere Jagdbehörde', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id22;

-- Attribut  des Layers 22

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id22, 'amtlicheflaeche', 'amtlicheflaeche', 'befriedete_bezirke_flurstuecke', 'befriedete_bezirke_flurstuecke', 'float8', '', '', NULL, '1', '53', NULL, '', 'Text', '', 'Amtliche Fläche [m²]', '', '', '', '', '', 'Flurstück', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 22

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id22, 'flurstueckskennzeichen', 'flurstueckskennzeichen', 'befriedete_bezirke_flurstuecke', 'befriedete_bezirke_flurstuecke', 'bpchar', '', '', NULL, '1', '20', NULL, '', 'Text', '', 'Flurstückskennzeichen', '', '', '', '', '', 'Flurstück', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 22

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id22, 'fst_teilflaeche_abs', 'fst_teilflaeche_abs', 'befriedete_bezirke_flurstuecke', 'befriedete_bezirke_flurstuecke', 'numeric', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'Fläche im Bezirk [m²]', '', '', '', '', '', 'Flurstück', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 22

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id22, 'fst_teilflaeche_proz', 'fst_teilflaeche_proz', 'befriedete_bezirke_flurstuecke', 'befriedete_bezirke_flurstuecke', 'numeric', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'Fläche im Bezirk [%]', '', '', '', '', '', 'Flurstück', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 22

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id22, 'id', 'id', 'befriedete_bezirke_flurstuecke', 'befriedete_bezirke_flurstuecke', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', 'ID Bezirk', '', '', '', '', '', 'Flurstück', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 22

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id22, 'naschluessel', 'naschluessel', 'befriedete_bezirke_flurstuecke', 'befriedete_bezirke_flurstuecke', 'text', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'Nutzungsartenschlüssel', '', '', '', '', '', 'Nutzung', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 22

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id22, 'na_teilflaeche_abs', 'na_teilflaeche_abs', 'befriedete_bezirke_flurstuecke', 'befriedete_bezirke_flurstuecke', 'numeric', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'NA Fläche im Bezirk [m²]', '', '', '', '', '', 'Nutzung', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 22

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id22, 'na_teilflaeche_proz', 'na_teilflaeche_proz', 'befriedete_bezirke_flurstuecke', 'befriedete_bezirke_flurstuecke', 'numeric', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'NA Fläche im Bezirk [%]', '', '', '', '', '', 'Nutzung', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 22

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id22, 'nutzung', 'nutzung', 'befriedete_bezirke_flurstuecke', 'befriedete_bezirke_flurstuecke', 'varchar', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'Nutzungsbezeichnung', '', '', '', '', '', 'Nutzung', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Layer 29

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Eigenjagdbezirk abgerundet', NULL, NULL, NULL, NULL, '', '2', vars_group_id_95, 'SELECT id, name, status_abrundung, flaeche, concode, verzicht, datum_beschluss, datum_bestandskraft, datum_erfassung, the_geom FROM jagdbezirke_abgerundet WHERE 1=1', 'jagdbezirke_abgerundet', 'id', NULL, '0', 'the_geom from jagdkataster.jagdbezirke_abgerundet as foo using unique gid using srid=25833', 'jagdkataster', NULL, '', NULL, NULL, '', '', '', 'name', '100000', '100', '', '0', '', vars_connection_id, '', '6', '', NULL, NULL, NULL, '3', 'pixels', NULL, '25833', '', NULL, 't', '1', '40', '30092', NULL, '100', '500000', NULL, '', NULL, 'EPSG:25833', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 't', 't', '', '', NULL, 'Hentschel', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id29;

-- Attribut  des Layers 29

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id29, 'concode', 'concode', 'jagdbezirke_abgerundet', 'jagdbezirke_abgerundet', 'varchar', '', '', NULL, '1', '5', NULL, '', 'Text', '', 'Condition', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', NULL, '0');

-- Attribut  des Layers 29

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id29, 'datum_beschluss', 'datum_beschluss', 'jagdbezirke_abgerundet', 'jagdbezirke_abgerundet', 'date', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'Datum Beschluss', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 29

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id29, 'datum_bestandskraft', 'datum_bestandskraft', 'jagdbezirke_abgerundet', 'jagdbezirke_abgerundet', 'date', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'Datum Bestandskraft', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 29

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id29, 'datum_erfassung', 'datum_erfassung', 'jagdbezirke_abgerundet', 'jagdbezirke_abgerundet', 'date', '', '', NULL, '1', NULL, NULL, '', 'Text', '', 'Datum der Erfassung', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', NULL, '0');

-- Attribut  des Layers 29

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id29, 'flaeche', 'flaeche', 'jagdbezirke_abgerundet', 'jagdbezirke_abgerundet', 'numeric', '', '', NULL, '1', NULL, NULL, '', 'Fläche', '', 'Fläche [ha]', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 29

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id29, 'id', 'id', 'jagdbezirke_abgerundet', 'jagdbezirke_abgerundet', 'varchar', '', '', NULL, '0', '10', NULL, '', 'Text', '', 'lfd. Nr.', '', '', '', '', 'lfd. Nr. der unteren Jagdbehörde', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 29

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id29, 'name', 'name', 'jagdbezirke_abgerundet', 'jagdbezirke_abgerundet', 'varchar', '', '', NULL, '1', '50', NULL, '', 'Text', '', 'name', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 29

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id29, 'status_abrundung', 'status_abrundung', 'jagdbezirke_abgerundet', 'jagdbezirke_abgerundet', 'varchar', '', '', NULL, '1', '20', NULL, '', 'Auswahlfeld', '''vor Bestandskraft'',''Bestandskraft''', 'Status der Abrundung', '', '', '', '', 'Der EJB ist noch nicht bestandskräftig, wenn Bescheide erlassen wurden, Widerspruchsfristen aber noch laufen oder Verfahren noch anhängig sind.', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 29

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id29, 'the_geom', 'the_geom', 'jagdbezirke_abgerundet', 'jagdbezirke_abgerundet', 'geometry', 'MULTIPOLYGON', '', NULL, '1', NULL, NULL, '', 'Geometrie', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Attribut  des Layers 29

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id29, 'verzicht', 'verzicht', 'jagdbezirke_abgerundet', 'jagdbezirke_abgerundet', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Checkbox', '', 'Verzicht', '', '', '', '', 'Verzicht gemäß §3 LJagdG', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Class 25 des Layers 29

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('vor Bestandskraft', vars_last_layer_id29, '(''[verzicht]'' eq ''f'' AND ''[status_abrundung]'' eq ''vor Bestandskraft'')', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 21 der Class 25

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '164 173 135', '-1 -1 -1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 21 zu Class 25
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Style 22 der Class 25

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '-1 -1 -1', '0 65 0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', '4', '2.00', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 22 zu Class 25
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 1);

-- Label 13 der Class 25

				INSERT INTO kvwmap.labels 
					("font", "type", "color", "outlinecolor", "shadowcolor", "shadowsizex", "shadowsizey", "backgroundcolor", "backgroundshadowcolor", "backgroundshadowsizex", "backgroundshadowsizey", "size", "minsize", "maxsize", "position", "offsetx", "offsety", "angle", "anglemode", "buffer", "minfeaturesize", "maxfeaturesize", "partials", "wrap", "the_force") 
				VALUES 
					('arial', NULL, '114 138 35', '242 246 228', '', NULL, NULL, '242 246 228', '', NULL, NULL, '9', '8', '12', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, '1')RETURNING label_id INTO vars_last_label_id;
-- Zuordnung Label 13 zu Class 25
INSERT INTO kvwmap.u_labels2classes (label_id, class_id) VALUES (vars_last_label_id, vars_last_class_id);

-- Class 26 des Layers 29

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('Bestandskraft', vars_last_layer_id29, '(''[verzicht]'' eq ''f'' AND ''[status_abrundung]'' eq ''Bestandskraft'')', '2', '')RETURNING class_id INTO vars_last_class_id;

-- Style 23 der Class 26

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '221 255 113', '-1 -1 -1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 23 zu Class 26
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Style 24 der Class 26

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '-1 -1 -1', '0 65 0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', '4', '2.00', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 24 zu Class 26
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 1);

-- Label 14 der Class 26

				INSERT INTO kvwmap.labels 
					("font", "type", "color", "outlinecolor", "shadowcolor", "shadowsizex", "shadowsizey", "backgroundcolor", "backgroundshadowcolor", "backgroundshadowsizex", "backgroundshadowsizey", "size", "minsize", "maxsize", "position", "offsetx", "offsety", "angle", "anglemode", "buffer", "minfeaturesize", "maxfeaturesize", "partials", "wrap", "the_force") 
				VALUES 
					('arial', NULL, '114 138 35', '242 246 228', '', NULL, NULL, '242 246 228', '', NULL, NULL, '9', '8', '12', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, '1')RETURNING label_id INTO vars_last_label_id;
-- Zuordnung Label 14 zu Class 26
INSERT INTO kvwmap.u_labels2classes (label_id, class_id) VALUES (vars_last_label_id, vars_last_class_id);

-- Class 27 des Layers 29

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('Verzicht gem. §3 LJagdG', vars_last_layer_id29, '(''[verzicht]'' eq ''t'')', '3', '')RETURNING class_id INTO vars_last_class_id;

-- Style 25 der Class 27

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'cross', '15', '0 65 0', '-1 -1 -1', NULL, NULL, NULL, NULL, '15', '15', NULL, NULL, '180', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 25 zu Class 27
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Style 26 der Class 27

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '-1 -1 -1', '0 65 0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', '4', '2.00', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 26 zu Class 27
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 1);

-- Label 15 der Class 27

				INSERT INTO kvwmap.labels 
					("font", "type", "color", "outlinecolor", "shadowcolor", "shadowsizex", "shadowsizey", "backgroundcolor", "backgroundshadowcolor", "backgroundshadowsizex", "backgroundshadowsizey", "size", "minsize", "maxsize", "position", "offsetx", "offsety", "angle", "anglemode", "buffer", "minfeaturesize", "maxfeaturesize", "partials", "wrap", "the_force") 
				VALUES 
					('arial', NULL, '114 138 35', '242 246 228', '', NULL, NULL, '', '', NULL, NULL, '6', '5', '9', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, '1')RETURNING label_id INTO vars_last_label_id;
-- Zuordnung Label 15 zu Class 27
INSERT INTO kvwmap.u_labels2classes (label_id, class_id) VALUES (vars_last_label_id, vars_last_class_id);

-- Layer 30

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('EJB Abtrennungsflächen', NULL, NULL, NULL, NULL, '', '2', vars_group_id_96, 'SELECT gid, id, name, art, flaeche,  bezirkid, concode, jb_zuordnung, status, the_geom FROM jagdbezirk_paechter WHERE art = ''atf'' and status = ''f''', 'jagdbezirk_paechter', 'id', NULL, '0', 'the_geom from (select gid, id, name, art, the_geom from jagdkataster.jagdbezirk_paechter where art = ''atf'' and status = ''f'') as foo using unique gid using srid=2398', 'jagdkataster', NULL, '', NULL, NULL, '', '', '', 'name', '100000', '100', '', '0', '', vars_connection_id, '', '6', 'art', NULL, NULL, NULL, '3', 'pixels', NULL, '2398', 'jagdkataster/view/jagdbezirke.php', NULL, 't', '1', '50', '100', NULL, '100', '500000', NULL, '', NULL, 'EPSG:2398', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 't', 't', '', '', NULL, 'untere Jagdbehörde', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id30;

-- Attribut  des Layers 30

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id30, 'art', 'art', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '15', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 30

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id30, 'bezirkid', 'bezirkid', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 30

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id30, 'concode', 'concode', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '5', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 30

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id30, 'flaeche', 'flaeche', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'numeric', '', '', NULL, '1', NULL, NULL, '', 'Fläche', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 30

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id30, 'gid', 'gid', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'int4', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 30

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id30, 'id', 'id', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '10', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 30

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id30, 'jb_zuordnung', 'jb_zuordnung', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '10', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 30

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id30, 'name', 'name', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '50', NULL, '', 'Text', '', 'name', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 30

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id30, 'status', 'status', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 30

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id30, 'the_geom', 'the_geom', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'geometry', 'MULTIPOLYGON', '', NULL, '1', NULL, NULL, '', 'Geometrie', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Class 28 des Layers 30

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					(' ', vars_last_layer_id30, '', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 27 der Class 28

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'cross', '9', '0 0 0', '-1 -1 -1', NULL, NULL, NULL, NULL, '30', '30', NULL, NULL, '0', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 27 zu Class 28
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Style 28 der Class 28

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '-1 -1 -1', '130 0 0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', '3', '2.00', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 28 zu Class 28
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 1);

-- Layer 31

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('EJB Angliederungsflächen', NULL, NULL, NULL, NULL, '', '2', vars_group_id_96, 'SELECT gid, id, name, art, flaeche,  bezirkid, concode, jb_zuordnung, status, the_geom FROM jagdbezirk_paechter WHERE art = ''agf'' and status = ''f''', 'jagdbezirk_paechter', 'id', NULL, '0', 'the_geom from (select gid, name, art, the_geom from jagdkataster.jagdbezirk_paechter where art = ''agf'' and status = ''f'') as foo using unique gid using srid=2398', 'jagdkataster', NULL, '', NULL, NULL, '', '', '', 'name', '100000', '100', '', '0', '', vars_connection_id, '', '6', 'art', NULL, NULL, NULL, '3', 'pixels', NULL, '2398', 'jagdkataster/view/jagdbezirke.php', NULL, 't', '1', '50', '100', NULL, '100', '500000', NULL, '', NULL, 'EPSG:2398', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 't', 't', '', '', NULL, 'untere Jagdbehörde', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id31;

-- Attribut  des Layers 31

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id31, 'art', 'art', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '15', NULL, '', 'Text', '', 'Art', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 31

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id31, 'bezirkid', 'bezirkid', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 31

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id31, 'concode', 'concode', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '5', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 31

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id31, 'flaeche', 'flaeche', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'numeric', '', '', NULL, '1', NULL, NULL, '', 'Fläche', '', 'Fläche [ha]', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 31

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id31, 'gid', 'gid', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'int4', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 31

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id31, 'id', 'id', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '10', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 31

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id31, 'jb_zuordnung', 'jb_zuordnung', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '10', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 31

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id31, 'name', 'name', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '50', NULL, '', 'Text', '', 'name', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 31

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id31, 'status', 'status', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 31

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id31, 'the_geom', 'the_geom', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'geometry', 'MULTIPOLYGON', '', NULL, '1', NULL, NULL, '', 'Geometrie', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Class 29 des Layers 31

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					(' ', vars_last_layer_id31, '', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 29 der Class 29

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'cross', '9', '0 0 0', '-1 -1 -1', NULL, NULL, NULL, NULL, '30', '30', NULL, NULL, '0', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 29 zu Class 29
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Style 30 der Class 29

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '-1 -1 -1', '130 0 0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', '3', '2.00', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 30 zu Class 29
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 1);

-- Layer 32

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('EJB Anpachtflächen', NULL, NULL, NULL, NULL, '', '2', vars_group_id_96, 'SELECT gid, id, name, art, flaeche,  bezirkid, concode, jb_zuordnung, status, the_geom FROM jagdbezirk_paechter WHERE art = ''apf'' and status = ''f''', 'jagdbezirk_paechter', 'id', NULL, '0', 'the_geom from (select gid, the_geom, id, art, name from jagdkataster.jagdbezirk_paechter where art = ''apf'' and status = ''f'') as foo using unique gid using srid=2398', 'jagdkataster', NULL, '', NULL, NULL, '', '', '', 'name', '100000', '100', '', '0', '', vars_connection_id, '', '6', 'art', NULL, NULL, NULL, '1', 'pixels', NULL, '2398', 'jagdkataster/view/jagdbezirke.php', NULL, 't', '1', '50', '100', NULL, '100', '500000', NULL, '', NULL, 'EPSG:2398', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 't', 't', '', '', NULL, 'untere Jagdbehörde', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id32;

-- Attribut  des Layers 32

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id32, 'art', 'art', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '15', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 32

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id32, 'bezirkid', 'bezirkid', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 32

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id32, 'concode', 'concode', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '5', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 32

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id32, 'flaeche', 'flaeche', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'numeric', '', '', NULL, '1', NULL, NULL, '', 'Fläche', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 32

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id32, 'gid', 'gid', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'int4', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 32

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id32, 'id', 'id', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '10', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 32

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id32, 'jb_zuordnung', 'jb_zuordnung', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '10', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 32

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id32, 'name', 'name', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '50', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 32

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id32, 'status', 'status', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 32

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id32, 'the_geom', 'the_geom', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'geometry', 'MULTIPOLYGON', '', NULL, '1', NULL, NULL, '', 'Geometrie', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Class 30 des Layers 32

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					(' ', vars_last_layer_id32, '', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 31 der Class 30

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'cross', '9', '0 0 0', '-1 -1 -1', NULL, NULL, NULL, NULL, '30', '30', NULL, NULL, '0', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 31 zu Class 30
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Style 32 der Class 30

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '-1 -1 -1', '130 0 0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', '3', '2.00', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 32 zu Class 30
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 1);

-- Layer 33

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('EJB Enklaven', NULL, NULL, NULL, NULL, '', '2', vars_group_id_96, 'SELECT gid, id, name, art, flaeche,  bezirkid, concode, jb_zuordnung, status, the_geom FROM jagdbezirk_paechter WHERE art = ''jbe'' and status = ''f''', 'jagdbezirk_paechter', 'id', NULL, '0', 'the_geom from (select gid, name, art, the_geom from jagdkataster.jagdbezirk_paechter where art = ''jbe'' and status = ''f'') as foo using unique gid using srid=2398', 'jagdkataster', NULL, '', NULL, NULL, '', '', '', 'name', '100000', '100', '', '0', '', vars_connection_id, '', '6', 'art', NULL, NULL, NULL, '3', 'pixels', NULL, '2398', 'jagdkataster/view/jagdbezirke.php', NULL, 't', '1', '50', '100', NULL, '100', '500000', NULL, '', NULL, 'EPSG:2398', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 't', 't', '', '', NULL, 'untere Jagdbehörde', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id33;

-- Attribut  des Layers 33

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id33, 'art', 'art', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '15', NULL, '', 'Auswahlfeld', 'select bezeichnung as output, art as value from jagdbezirkart', 'Art', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 33

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id33, 'bezirkid', 'bezirkid', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 33

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id33, 'concode', 'concode', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '5', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 33

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id33, 'flaeche', 'flaeche', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'numeric', '', '', NULL, '1', NULL, NULL, '', 'Zahl', '', 'Fläche [ha]', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 33

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id33, 'gid', 'gid', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'int4', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 33

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id33, 'id', 'id', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '10', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 33

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id33, 'jb_zuordnung', 'jb_zuordnung', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '10', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 33

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id33, 'name', 'name', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '50', NULL, '', 'Text', '', 'name', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 33

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id33, 'status', 'status', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 33

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id33, 'the_geom', 'the_geom', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'geometry', 'MULTIPOLYGON', '', NULL, '1', NULL, NULL, '', 'Geometrie', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Class 31 des Layers 33

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					(' ', vars_last_layer_id33, '', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 33 der Class 31

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'cross', '9', '0 0 0', '-1 -1 -1', NULL, NULL, NULL, NULL, '30', '30', NULL, NULL, '0', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 33 zu Class 31
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Style 34 der Class 31

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '-1 -1 -1', '130 0 0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', '3', '2.00', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 34 zu Class 31
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 1);

-- Layer 34

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('EJB Exklaven', NULL, NULL, NULL, NULL, '', '2', vars_group_id_96, 'SELECT gid, id, name, art, flaeche,  bezirkid, concode, jb_zuordnung, status, the_geom FROM jagdbezirk_paechter WHERE art = ''jex'' and status = ''f''', 'jagdbezirk_paechter', 'id', NULL, '0', 'the_geom from (select gid, name, art, the_geom from jagdkataster.jagdbezirk_paechter where art = ''jex'' and status = ''f'') as foo using unique gid using srid=2398', 'jagdkataster', NULL, '', NULL, NULL, '', '', '', 'name', '100000', '100', '', '0', '', vars_connection_id, '', '6', 'art', NULL, NULL, NULL, '3', 'pixels', NULL, '2398', 'jagdkataster/view/jagdbezirke.php', NULL, 't', '1', '50', '100', NULL, '100', '500000', NULL, '', NULL, 'EPSG:2398', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 't', 'f', '', '', NULL, 'untere Jagdbehörde', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id34;

-- Attribut  des Layers 34

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id34, 'art', 'art', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '15', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 34

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id34, 'bezirkid', 'bezirkid', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 34

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id34, 'concode', 'concode', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '5', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 34

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id34, 'flaeche', 'flaeche', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'numeric', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 34

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id34, 'gid', 'gid', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'int4', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 34

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id34, 'id', 'id', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '10', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 34

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id34, 'jb_zuordnung', 'jb_zuordnung', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '10', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 34

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id34, 'name', 'name', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '50', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 34

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id34, 'status', 'status', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 34

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id34, 'the_geom', 'the_geom', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'geometry', 'MULTIPOLYGON', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Class 32 des Layers 34

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					(' ', vars_last_layer_id34, '', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 35 der Class 32

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'cross', '9', '0 0 0', '-1 -1 -1', NULL, NULL, NULL, NULL, '30', '30', NULL, NULL, '0', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 35 zu Class 32
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Style 36 der Class 32

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '-1 -1 -1', '130 0 0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', '3', '2.00', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 36 zu Class 32
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 1);

-- Layer 35

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('EJB im Verfahren', NULL, NULL, NULL, NULL, '', '2', vars_group_id_96, 'SELECT gid, id, name, art, flaeche, bezirkid, concode, verzicht, the_geom FROM jagdbezirk_paechter WHERE art = ''ejb'' and status = ''f''', 'jagdbezirk_paechter', 'id', NULL, '0', 'the_geom from (select gid, name, art, verzicht, the_geom from jagdkataster.jagdbezirk_paechter where art = ''ejb'' and status = ''f'') as foo using unique gid using srid=2398', 'jagdkataster', NULL, '', NULL, NULL, '', '', '', 'name', '100000', '100', '', '0', '', vars_connection_id, '', '6', 'art', NULL, NULL, NULL, '3', 'pixels', NULL, '2398', 'jagdkataster/view/jagdbezirke.php', NULL, 't', '1', '40', '100', NULL, '100', '500000', NULL, '', NULL, 'EPSG:2398', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 't', 't', '', '', NULL, 'untere Jagdbehörde', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id35;

-- Attribut  des Layers 35

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id35, 'art', 'art', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '15', NULL, '', 'Text', '', 'Art', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 35

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id35, 'bezirkid', 'bezirkid', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'int4', '', '', NULL, '1', '32', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 35

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id35, 'concode', 'concode', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '5', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 35

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id35, 'flaeche', 'flaeche', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'numeric', '', '', NULL, '1', NULL, NULL, '', 'Zahl', '', 'Fläche [ha]', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 35

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id35, 'gid', 'gid', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'int4', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 35

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id35, 'id', 'id', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '10', NULL, '', 'Text', '', 'Lfd. Nr. (Condition)', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 35

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id35, 'name', 'name', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '50', NULL, '', 'Text', '', 'name', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 35

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id35, 'the_geom', 'the_geom', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'geometry', 'MULTIPOLYGON', '', NULL, '1', NULL, NULL, '', 'Geometrie', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 35

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id35, 'verzicht', 'verzicht', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Class 33 des Layers 35

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('vor Abrundung', vars_last_layer_id35, '(''[verzicht]'' eq ''f'')', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 37 der Class 33

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '240 240 120', '-1 -1 -1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', '5', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 37 zu Class 33
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Style 38 der Class 33

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '-1 -1 -1', '0 235 0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', '4', '2.00', '7.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 38 zu Class 33
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 1);

-- Label 16 der Class 33

				INSERT INTO kvwmap.labels 
					("font", "type", "color", "outlinecolor", "shadowcolor", "shadowsizex", "shadowsizey", "backgroundcolor", "backgroundshadowcolor", "backgroundshadowsizex", "backgroundshadowsizey", "size", "minsize", "maxsize", "position", "offsetx", "offsety", "angle", "anglemode", "buffer", "minfeaturesize", "maxfeaturesize", "partials", "wrap", "the_force") 
				VALUES 
					('arial', NULL, '0 65 0', '255 255 200', '', NULL, NULL, '', '', NULL, NULL, '8', '7', '10', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, '1')RETURNING label_id INTO vars_last_label_id;
-- Zuordnung Label 16 zu Class 33
INSERT INTO kvwmap.u_labels2classes (label_id, class_id) VALUES (vars_last_label_id, vars_last_class_id);

-- Class 34 des Layers 35

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('mit vollständigem Verzicht', vars_last_layer_id35, '(''[verzicht]'' eq ''t'')', '2', '')RETURNING class_id INTO vars_last_class_id;

-- Style 39 der Class 34

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'cross', '15', '240 240 120', '-1 -1 -1', NULL, NULL, NULL, NULL, '15', '15', NULL, NULL, '0', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 39 zu Class 34
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Style 40 der Class 34

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '-1 -1 -1', '0 235 0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', '4', '2.00', '7.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 40 zu Class 34
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 1);

-- Layer 36

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('EJB jagdbezirksfreie Flächen', NULL, NULL, NULL, NULL, '', '2', vars_group_id_96, 'SELECT gid, id, name, art, flaeche,  bezirkid, concode, jb_zuordnung, status, the_geom FROM jagdbezirk_paechter WHERE art = ''jbf'' and status = ''f''', 'jagdbezirk_paechter', 'id', NULL, '0', 'the_geom from (select gid, name, art, the_geom from jagdkataster.jagdbezirk_paechter WHERE art = ''jbf'' and status = ''f'') as foo using unique gid using srid=2398', 'jagdkataster', NULL, '', NULL, NULL, '', '', '', 'name', '100000', '100', '', '0', '', vars_connection_id, '', '6', 'art', NULL, NULL, NULL, '3', 'pixels', NULL, '2398', 'jagdkataster/view/jagdbezirke.php', NULL, 't', '1', '50', '100', NULL, '100', '500000', NULL, '', NULL, 'EPSG:2398', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 't', 't', '', '', NULL, 'untere Jagdbehörde', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id36;

-- Attribut  des Layers 36

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id36, 'art', 'art', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '15', NULL, '', 'Auswahlfeld', 'select bezeichnung as output, art as value from jagdbezirkart', 'Art', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 36

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id36, 'bezirkid', 'bezirkid', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 36

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id36, 'concode', 'concode', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '5', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 36

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id36, 'flaeche', 'flaeche', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'numeric', '', '', NULL, '1', NULL, NULL, '', 'Zahl', '', 'Fläche [ha]', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 36

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id36, 'gid', 'gid', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'int4', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 36

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id36, 'id', 'id', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '10', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 36

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id36, 'jb_zuordnung', 'jb_zuordnung', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '10', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 36

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id36, 'name', 'name', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '50', NULL, '', 'Text', '', 'name', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 36

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id36, 'status', 'status', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 36

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id36, 'the_geom', 'the_geom', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'geometry', 'MULTIPOLYGON', '', NULL, '1', NULL, NULL, '', 'Geometrie', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Class 35 des Layers 36

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					(' ', vars_last_layer_id36, '', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 41 der Class 35

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'cross', '9', '0 0 0', '-1 -1 -1', NULL, NULL, NULL, NULL, '30', '30', NULL, NULL, '0', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 41 zu Class 35
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Style 42 der Class 35

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '-1 -1 -1', '130 0 0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', '3', '2.00', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 42 zu Class 35
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 1);

-- Layer 37

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('EJB Verdachtsflächen', NULL, NULL, NULL, NULL, '', '2', vars_group_id_96, 'SELECT eigentuemer, flaeche, the_geom FROM lk_ejb_verdachtsflaechen WHERE 1=1', 'lk_ejb_verdachtsflaechen', 'id', NULL, '0', 'the_geom from jagdkataster.lk_ejb_verdachtsflaechen using unique gid using srid=25833', 'jagdkataster', NULL, '', NULL, NULL, '', '', '', '', '100000', '100', '', '0', '', vars_connection_id, '', '6', 'gid', NULL, NULL, NULL, '3', 'pixels', NULL, '25833', '', NULL, 't', '1', '70', '30091', NULL, '100', '500000', NULL, '', NULL, 'EPSG:25833', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 't', 't', '', '', NULL, 'untere Jagdbehörde', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id37;

-- Attribut  des Layers 37

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id37, 'eigentuemer', 'eigentuemer', 'lk_ejb_verdachtsflaechen', 'lk_ejb_verdachtsflaechen', 'text', '', '', NULL, '1', NULL, NULL, '', 'Textfeld', '', 'Eigentümer', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '1');

-- Attribut  des Layers 37

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id37, 'flaeche', 'flaeche', 'lk_ejb_verdachtsflaechen', 'lk_ejb_verdachtsflaechen', 'float8', '', '', NULL, '1', '53', NULL, '', 'Zahl', '', 'Fläche (ca.) [m²]', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '1');

-- Attribut  des Layers 37

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id37, 'the_geom', 'the_geom', 'lk_ejb_verdachtsflaechen', 'lk_ejb_verdachtsflaechen', 'geometry', 'POLYGON', '', NULL, '1', NULL, NULL, '', 'Geometrie', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Class 36 des Layers 37

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('', vars_last_layer_id37, '', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 43 der Class 36

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '196 215 241', '-1 -1 -1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 43 zu Class 36
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Style 44 der Class 36

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '-1 -1 -1', '255 73 46', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', '4', '1.00', '6.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 44 zu Class 36
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 1);

-- Layer 38

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('EJB Verzicht', NULL, NULL, NULL, NULL, '', '2', vars_group_id_96, 'SELECT gid, id, name, art, flaeche,  bezirkid, concode, jb_zuordnung, status, the_geom FROM jagdbezirk_paechter WHERE art = ''atv'' and status = ''f''', 'jagdbezirk_paechter', 'id', NULL, '0', 'the_geom from (select gid, id, name, art, the_geom from jagdkataster.jagdbezirk_paechter where art = ''atv'' and status = ''f'') as foo using unique gid using srid=2398', 'jagdkataster', NULL, '', NULL, NULL, '', '', '', 'name', '100000', '100', '', '0', '', vars_connection_id, '', '6', 'art', NULL, NULL, NULL, '3', 'pixels', NULL, '2398', 'jagdkataster/view/jagdbezirke.php', NULL, 't', '1', '40', '30101', NULL, '100', '500000', NULL, '', NULL, 'EPSG:2398', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 't', 't', '', '', NULL, 'untere Jagdbehörde', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id38;

-- Attribut  des Layers 38

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id38, 'art', 'art', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '15', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 38

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id38, 'bezirkid', 'bezirkid', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'int4', '', '', NULL, '1', '32', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 38

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id38, 'concode', 'concode', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '5', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 38

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id38, 'flaeche', 'flaeche', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'numeric', '', '', NULL, '1', NULL, NULL, '', 'Fläche', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 38

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id38, 'gid', 'gid', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'int4', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 38

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id38, 'id', 'id', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '10', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 38

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id38, 'jb_zuordnung', 'jb_zuordnung', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '10', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 38

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id38, 'name', 'name', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '50', NULL, '', 'Text', '', 'name', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 38

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id38, 'status', 'status', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 38

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id38, 'the_geom', 'the_geom', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'geometry', 'POLYGON', '', NULL, '1', NULL, NULL, '', 'Geometrie', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Class 37 des Layers 38

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('Abtrennung nach §3', vars_last_layer_id38, '(''[art]'' eq ''atv'')', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 45 der Class 37

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'cross', '15', '0 65 0', '-1 -1 -1', NULL, NULL, NULL, NULL, '15', '15', NULL, NULL, '180', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 45 zu Class 37
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Style 46 der Class 37

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '-1 -1 -1', '0 65 0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', '4', '2.00', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 46 zu Class 37
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 1);

-- Layer 42

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Gemeinschaftliche Jagdbezirke', NULL, NULL, NULL, NULL, '', '2', vars_group_id_95, 'SELECT id, name, art, flaeche,  bezirkid, concode, the_geom FROM jagdbezirk_paechter WHERE art = ''gjb''', 'jagdbezirk_paechter', 'id', NULL, '0', 'the_geom from (select gid, name, art, the_geom from jagdkataster.jagdbezirk_paechter) as foo using unique gid using srid=2398', 'jagdkataster', NULL, '', NULL, NULL, '', '', '', 'name', '100000', '100', '', '0', '', vars_connection_id, '', '6', 'id', NULL, NULL, NULL, '3', 'pixels', NULL, '2398', 'jagdkataster/view/jagdbezirke.php', NULL, 't', '1', '50', '100', NULL, '100', '500000', NULL, '', NULL, 'EPSG:2398', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 't', 't', '', '', NULL, 'untere Jagdbehörde', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id42;

-- Attribut  des Layers 42

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id42, 'art', 'art', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '15', NULL, '', 'Text', '', 'Art', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 42

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id42, 'bezirkid', 'bezirkid', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 42

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id42, 'concode', 'concode', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '5', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 42

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id42, 'flaeche', 'flaeche', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'numeric', '', '', NULL, '1', NULL, NULL, '', 'Zahl', '', 'Fläche [ha]', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 42

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id42, 'gid', 'gid', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'int4', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 42

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id42, 'id', 'id', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '10', NULL, '', 'Text', '', 'Lfd. Nr. (Condition)', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 42

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id42, 'name', 'name', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '50', NULL, '', 'Text', '', 'name', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 42

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id42, 'the_geom', 'the_geom', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'geometry', 'MULTIPOLYGON', '', NULL, '1', NULL, NULL, '', 'Geometrie', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Class 38 des Layers 42

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('GJB - Gem.Jagd', vars_last_layer_id42, '(''[art]'' eq ''gjb'')', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 47 der Class 38

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '250 205 150', '-1 -1 -1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 47 zu Class 38
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Style 48 der Class 38

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '-1 -1 -1', '0 65 0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', '4', '2.00', '5.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 48 zu Class 38
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 1);

-- Label 17 der Class 38

				INSERT INTO kvwmap.labels 
					("font", "type", "color", "outlinecolor", "shadowcolor", "shadowsizex", "shadowsizey", "backgroundcolor", "backgroundshadowcolor", "backgroundshadowsizex", "backgroundshadowsizey", "size", "minsize", "maxsize", "position", "offsetx", "offsety", "angle", "anglemode", "buffer", "minfeaturesize", "maxfeaturesize", "partials", "wrap", "the_force") 
				VALUES 
					('arial', NULL, '160 110 10', '255 200 160', '', NULL, NULL, '', '', NULL, NULL, '9', '8', '12', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, '1')RETURNING label_id INTO vars_last_label_id;
-- Zuordnung Label 17 zu Class 38
INSERT INTO kvwmap.u_labels2classes (label_id, class_id) VALUES (vars_last_label_id, vars_last_class_id);

-- Layer 43

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Jagdbezirke - Einfärbung', NULL, NULL, NULL, NULL, '', '2', vars_group_id_96, 'SELECT id, name, farbid, the_geom FROM jagdbezirke_anzeige WHERE 1=1', 'jagdbezirke_anzeige', 'id', NULL, '0', 'the_geom from (select a.*, f.wert from jagdkataster.jagdbezirke_anzeige a, jagdkataster.jagdbezirke_anzeigefarben f where a.farbid=f.farbid) as foo using unique gid using srid=25833', 'jagdkataster', NULL, '', NULL, NULL, '', '', '', '', '100000', '100', '', '0', '', vars_connection_id, '', '6', 'id', NULL, NULL, NULL, '1', 'pixels', NULL, '25833', '', NULL, 't', '1', '90', '30110', NULL, '100', '500000', NULL, '', NULL, 'EPSG:2398', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 'f', 't', '', '', NULL, 'untere Jagdbehörde', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id43;

-- Attribut  des Layers 43

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id43, 'farbid', 'farbid', 'jagdbezirke_anzeige', 'jagdbezirke_anzeige', 'int2', '', '', NULL, '0', '16', '0', 'SELECT 1', 'Auswahlfeld', 'select farbid as value, bezeichnung as output from jagdbezirke_anzeigefarben order by bezeichnung', 'Farbe', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '1', '0');

-- Attribut  des Layers 43

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id43, 'id', 'id', 'jagdbezirke_anzeige', 'jagdbezirke_anzeige', 'varchar', '', '', NULL, '0', NULL, NULL, '', 'Text', '', 'ID Eigenjagdbezirk', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 43

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id43, 'name', 'name', 'jagdbezirke_anzeige', 'jagdbezirke_anzeige', 'varchar', '', '', NULL, '0', NULL, NULL, '', 'Text', '', 'name', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 43

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id43, 'the_geom', 'the_geom', 'jagdbezirke_anzeige', 'jagdbezirke_anzeige', 'geometry', 'MULTIPOLYGON', '', NULL, '1', NULL, NULL, '', 'Geometrie', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Class 39 des Layers 43

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('', vars_last_layer_id43, '', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 49 der Class 39

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', 'wert', '-1 -1 -1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 49 zu Class 39
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Style 50 der Class 39

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, '', '', '-1 -1 -1', '120 120 120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '', '4', '2.00', '7.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 50 zu Class 39
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 1);

-- Layer 51

				INSERT INTO kvwmap.layer 
					("name", "name_low_german", "name_english", "name_polish", "name_vietnamese", "alias", "datentyp", "gruppe", "pfad", "maintable", "oid", "identifier_text", "maintable_is_view", "data", "schema", "geom_column", "document_path", "document_url", "ddl_attribute", "tileindex", "tileitem", "labelangleitem", "labelitem", "labelmaxscale", "labelminscale", "labelrequires", "postlabelcache", "connection", "connection_id", "printconnection", "connectiontype", "classitem", "styleitem", "classification", "cluster_maxdistance", "tolerance", "toleranceunits", "sizeunits", "epsg_code", "template", "max_query_rows", "queryable", "use_geom", "transparency", "drawingorder", "legendorder", "minscale", "maxscale", "symbolscale", "offsite", "requires", "ows_srs", "wms_name", "wms_keywordlist", "wms_server_version", "wms_format", "wms_connectiontimeout", "wms_auth_username", "wms_auth_password", "wfs_geom", "write_mapserver_templates", "selectiontype", "querymap", "logconsume", "processing", "kurzbeschreibung", "datasource", "dataowner_name", "dataowner_email", "dataowner_tel", "uptodateness", "updatecycle", "metalink", "terms_of_use_link", "icon", "privileg", "export_privileg", "status", "trigger_function", "editable", "listed", "duplicate_from_layer_id", "duplicate_criterion", "shared_from", "version", "comment") 
				VALUES 
					('Teiljagdbezirke', NULL, NULL, NULL, NULL, '', '2', vars_group_id_95, 'SELECT gid, id, name, art, flaeche,  bezirkid, concode, jb_zuordnung, status, the_geom FROM jagdbezirk_paechter WHERE art = ''tjb''', 'jagdbezirk_paechter', 'id', NULL, '0', 'the_geom from (select gid, name, art, the_geom from jagdkataster.jagdbezirk_paechter) as foo using unique gid using srid=2398', 'jagdkataster', NULL, '', NULL, NULL, '', '', '', 'name', '100000', '100', '', '0', '', vars_connection_id, '', '6', 'art', NULL, NULL, NULL, '3', 'pixels', NULL, '2398', 'jagdkataster/view/jagdbezirke.php', NULL, 't', '1', '50', '100', NULL, '100', '500000', NULL, '', NULL, 'EPSG:2398', '', NULL, '1.1.0', 'image/png', '60', '', '', '', NULL, '', 't', 't', '', '', NULL, 'untere Jagdbehörde', NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '1', NULL, NULL, '1', '1', NULL, NULL, NULL, '1.0.0', NULL)RETURNING layer_id INTO vars_last_layer_id51;

-- Attribut  des Layers 51

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id51, 'art', 'art', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '15', NULL, '', 'Text', '', 'Art', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '3', '0', '0');

-- Attribut  des Layers 51

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id51, 'bezirkid', 'bezirkid', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'int4', '', '', NULL, '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '5', '0', '0');

-- Attribut  des Layers 51

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id51, 'concode', 'concode', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '5', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '6', '0', '0');

-- Attribut  des Layers 51

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id51, 'flaeche', 'flaeche', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'numeric', '', '', NULL, '1', NULL, NULL, '', 'Fläche', '', 'Fläche [ha]', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '4', '0', '0');

-- Attribut  des Layers 51

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id51, 'gid', 'gid', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'int4', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '0', '0', '0');

-- Attribut  des Layers 51

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id51, 'id', 'id', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '10', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '1', '0', '0');

-- Attribut  des Layers 51

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id51, 'jb_zuordnung', 'jb_zuordnung', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '10', NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '7', '0', '0');

-- Attribut  des Layers 51

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id51, 'name', 'name', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'varchar', '', '', NULL, '1', '50', NULL, '', 'Text', '', 'name', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '2', '0', '0');

-- Attribut  des Layers 51

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id51, 'status', 'status', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'bool', '', '', NULL, '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '8', '0', '0');

-- Attribut  des Layers 51

				INSERT INTO kvwmap.layer_attributes 
					("layer_id", "name", "real_name", "tablename", "table_alias_name", "type", "geometrytype", "constraints", "saveable", "nullable", "length", "decimal_length", "default", "form_element_type", "options", "alias", "alias_low_german", "alias_english", "alias_polish", "alias_vietnamese", "tooltip", "group", "tab", "arrangement", "labeling", "raster_visibility", "dont_use_for_new", "mandatory", "quicksearch", "visible", "vcheck_attribute", "vcheck_operator", "vcheck_value", "order", "privileg", "query_tooltip") 
				VALUES 
					(vars_last_layer_id51, 'the_geom', 'the_geom', 'jagdbezirk_paechter', 'jagdbezirk_paechter', 'geometry', 'MULTIPOLYGON', '', NULL, '1', NULL, NULL, '', 'Geometrie', '', '', '', '', '', '', '', '', NULL, '0', '0', NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, '9', '0', '0');

-- Class 44 des Layers 51

				INSERT INTO kvwmap.classes 
					("name", "layer_id", "expression", "drawingorder", "text") 
				VALUES 
					('Teiljagdbezirk', vars_last_layer_id51, '(''[art]'' eq ''tjb'')', '1', '')RETURNING class_id INTO vars_last_class_id;

-- Style 55 der Class 44

				INSERT INTO kvwmap.styles 
					("symbol", "symbolname", "size", "color", "outlinecolor", "colorrange", "datarange", "rangeitem", "opacity", "minsize", "maxsize", "minscale", "maxscale", "angle", "angleitem", "width", "minwidth", "maxwidth", "offsetx", "offsety", "polaroffset", "pattern", "geomtransform", "gap", "initialgap", "linecap", "linejoin", "linejoinmaxsize") 
				VALUES 
					(NULL, 'cross', '15', '90 90 90', '90 90 90', NULL, NULL, NULL, NULL, '15', '15', NULL, NULL, '0', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)RETURNING style_id INTO vars_last_style_id;
-- Zuordnung Style 55 zu Class 44
INSERT INTO kvwmap.u_styles2classes (style_id, class_id, drawingorder) VALUES (vars_last_style_id, vars_last_class_id, 0);

-- Label 18 der Class 44

				INSERT INTO kvwmap.labels 
					("font", "type", "color", "outlinecolor", "shadowcolor", "shadowsizex", "shadowsizey", "backgroundcolor", "backgroundshadowcolor", "backgroundshadowsizex", "backgroundshadowsizey", "size", "minsize", "maxsize", "position", "offsetx", "offsety", "angle", "anglemode", "buffer", "minfeaturesize", "maxfeaturesize", "partials", "wrap", "the_force") 
				VALUES 
					('arial', NULL, '90 90 90', '220 220 220', '', NULL, NULL, '', '', NULL, NULL, '8', '7', '10', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, '1')RETURNING label_id INTO vars_last_label_id;
-- Zuordnung Label 18 zu Class 44
INSERT INTO kvwmap.u_labels2classes (label_id, class_id) VALUES (vars_last_label_id, vars_last_class_id);

-- Replace attribute options for Layer 21
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=21', CONCAT('layer_id=', vars_last_layer_id21)) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=21( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '21,', CONCAT(vars_last_layer_id21, ',')) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '21,%';

-- Replace attribute options for Layer 22
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=22', CONCAT('layer_id=', vars_last_layer_id22)) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=22( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '22,', CONCAT(vars_last_layer_id22, ',')) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '22,%';

-- Replace attribute options for Layer 29
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=29', CONCAT('layer_id=', vars_last_layer_id29)) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=29( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '29,', CONCAT(vars_last_layer_id29, ',')) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '29,%';

-- Replace attribute options for Layer 30
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=30', CONCAT('layer_id=', vars_last_layer_id30)) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=30( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '30,', CONCAT(vars_last_layer_id30, ',')) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '30,%';

-- Replace attribute options for Layer 31
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=31', CONCAT('layer_id=', vars_last_layer_id31)) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=31( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '31,', CONCAT(vars_last_layer_id31, ',')) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '31,%';

-- Replace attribute options for Layer 32
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=32', CONCAT('layer_id=', vars_last_layer_id32)) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=32( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '32,', CONCAT(vars_last_layer_id32, ',')) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '32,%';

-- Replace attribute options for Layer 33
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=33', CONCAT('layer_id=', vars_last_layer_id33)) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=33( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '33,', CONCAT(vars_last_layer_id33, ',')) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '33,%';

-- Replace attribute options for Layer 34
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=34', CONCAT('layer_id=', vars_last_layer_id34)) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=34( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '34,', CONCAT(vars_last_layer_id34, ',')) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '34,%';

-- Replace attribute options for Layer 35
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=35', CONCAT('layer_id=', vars_last_layer_id35)) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=35( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '35,', CONCAT(vars_last_layer_id35, ',')) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '35,%';

-- Replace attribute options for Layer 36
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=36', CONCAT('layer_id=', vars_last_layer_id36)) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=36( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '36,', CONCAT(vars_last_layer_id36, ',')) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '36,%';

-- Replace attribute options for Layer 37
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=37', CONCAT('layer_id=', vars_last_layer_id37)) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=37( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '37,', CONCAT(vars_last_layer_id37, ',')) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '37,%';

-- Replace attribute options for Layer 38
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=38', CONCAT('layer_id=', vars_last_layer_id38)) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=38( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '38,', CONCAT(vars_last_layer_id38, ',')) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '38,%';

-- Replace attribute options for Layer 42
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=42', CONCAT('layer_id=', vars_last_layer_id42)) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=42( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '42,', CONCAT(vars_last_layer_id42, ',')) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '42,%';

-- Replace attribute options for Layer 43
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=43', CONCAT('layer_id=', vars_last_layer_id43)) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=43( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '43,', CONCAT(vars_last_layer_id43, ',')) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '43,%';

-- Replace attribute options for Layer 51
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, 'layer_id=51', CONCAT('layer_id=', vars_last_layer_id51)) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options ~ 'layer_id=51( |$)';
UPDATE kvwmap.layer_attributes SET options = REPLACE(options, '51,', CONCAT(vars_last_layer_id51, ',')) WHERE layer_id IN (vars_last_layer_id21, vars_last_layer_id22, vars_last_layer_id29, vars_last_layer_id30, vars_last_layer_id31, vars_last_layer_id32, vars_last_layer_id33, vars_last_layer_id34, vars_last_layer_id35, vars_last_layer_id36, vars_last_layer_id37, vars_last_layer_id38, vars_last_layer_id42, vars_last_layer_id43, vars_last_layer_id51) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '51,%';

END $$