BEGIN;

SET @group_id = (SELECT id FROM `u_groups` WHERE `Gruppenname` LIKE 'Wasserwirtschaft' ORDER BY id LIMIT 1);
SET @connection = 'user=xxxx password=xxxx dbname=kvwmapsp';

-- Stelle Wasserrecht Dateneingeber (id=2)
SET @stelle_id_2 = $WASSERRECHT_STELLE_DATENEINGEBER;

-- Stelle Wasseerrecht Entscheider (id=4)
SET @stelle_id_4 = $WASSERRECHT_STELLE_ENTSCHEIDER;

-- Stelle Wasserrecht Administration (id=5)
SET @stelle_id_5 = $WASSERRECHT_STELLE_ADMINISTRATION;

-- Layer 3
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Anlagenklasse', '', '5', @group_id, 'SELECT id, name FROM fiswrv_anlagen_klasse WHERE 1=1', 'fiswrv_anlagen_klasse', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id3=LAST_INSERT_ID();

-- Zuordnung Layer 3 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id3, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 3 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id3, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 3 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id3, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut id des Layers 3
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id3, 'id', 'id', 'fiswrv_anlagen_klasse', 'fiswrv_anlagen_klasse', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 3
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id3, 'name', 'name', 'fiswrv_anlagen_klasse', 'fiswrv_anlagen_klasse', 'varchar', '', '', '1', '100', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 3 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id3, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id3, @stelle_id_2, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 3 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id3, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id3, @stelle_id_4, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 3 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id3, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id3, @stelle_id_5, 'name', '0', '0');

-- Class 2 des Layers 3
INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`, `text`) VALUES ('alle', @last_layer_id3, '', '1', '');
SET @last_class_id=LAST_INSERT_ID();

-- Style 2 der Class 2
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`, `antialias`, `width`, `minwidth`, `maxwidth`, `sizeitem`) VALUES (NULL, 'circle', '8', '30 149 255', '', '0 0 0', NULL, '10', '360', '', NULL, NULL, NULL, NULL, '');
SET @last_style_id=LAST_INSERT_ID();
-- Zuordnung Style 2 zu Class 2
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);

-- Layer 41
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Archivnummer', '', '5', @group_id, 'SELECT id, nummer FROM fiswrv_archivnummer WHERE 1=1', 'fiswrv_archivnummer', '', 'wasserrecht', '', '', '', '', 'nummer', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, NULL, '-1', NULL, '', '', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id41=LAST_INSERT_ID();

-- Zuordnung Layer 41 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id41, @stelle_id_2, '1', '0', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 41 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id41, @stelle_id_4, '1', '0', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 41 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id41, @stelle_id_5, '1', '0', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut id des Layers 41
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id41, 'id', 'id', 'fiswrv_archivnummer', 'fiswrv_archivnummer', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut nummer des Layers 41
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id41, 'nummer', 'nummer', 'fiswrv_archivnummer', 'fiswrv_archivnummer', 'int4', '', '', '1', '32', '0', '', 'Text', '', 'Nummer', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 41 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id41, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id41, @stelle_id_2, 'nummer', '1', '0');

-- Zuordnung der Layerattribute des Layers 41 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id41, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id41, @stelle_id_4, 'nummer', '1', '0');

-- Zuordnung der Layerattribute des Layers 41 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id41, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id41, @stelle_id_5, 'nummer', '0', '0');

-- Layer 39
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Betriebszustand', '', '5', @group_id, 'SELECT id, name FROM fiswrv_betriebszustand WHERE 1=1', 'fiswrv_betriebszustand', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, NULL, '-1', NULL, '', '', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id39=LAST_INSERT_ID();

-- Zuordnung Layer 39 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id39, @stelle_id_2, '1', '0', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 39 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id39, @stelle_id_4, '1', '0', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 39 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id39, @stelle_id_5, '1', '0', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut id des Layers 39
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id39, 'id', 'id', 'fiswrv_betriebszustand', 'fiswrv_betriebszustand', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 39
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id39, 'name', 'name', 'fiswrv_betriebszustand', 'fiswrv_betriebszustand', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 39 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id39, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id39, @stelle_id_2, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 39 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id39, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id39, @stelle_id_4, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 39 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id39, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id39, @stelle_id_5, 'name', '0', '0');

-- Layer 24
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Dokument', '', '5', @group_id, 'SELECT id, name, pfad FROM fiswrv_dokument WHERE 1=1', 'fiswrv_dokument', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id24=LAST_INSERT_ID();

-- Zuordnung Layer 24 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id24, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 24 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id24, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 24 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id24, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut id des Layers 24
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id24, 'id', 'id', 'fiswrv_dokument', 'fiswrv_dokument', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 24
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id24, 'name', 'name', 'fiswrv_dokument', 'fiswrv_dokument', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Attribut pfad des Layers 24
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id24, 'pfad', 'pfad', 'fiswrv_dokument', 'fiswrv_dokument', 'text', '', '', '1', NULL, NULL, '', 'Text', '', 'Pfad', '', '', '', '', '', '', NULL, '0', '2', '0', '0');

-- Zuordnung der Layerattribute des Layers 24 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id24, @stelle_id_2, 'document', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id24, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id24, @stelle_id_2, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id24, @stelle_id_2, 'pfad', '1', '0');

-- Zuordnung der Layerattribute des Layers 24 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id24, @stelle_id_4, 'document', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id24, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id24, @stelle_id_4, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id24, @stelle_id_4, 'pfad', '1', '0');

-- Zuordnung der Layerattribute des Layers 24 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id24, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id24, @stelle_id_5, 'name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id24, @stelle_id_5, 'pfad', '0', '0');

-- Layer 33
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('FisWrV-WRe Gewässerbenutzungen', '', '5', @group_id, 'SELECT COALESCE(g.name,\'\') AS anlage_klasse, f.name AS anlage_anzeige, a.anlage AS anlage_id, b.wasserrechtliche_zulassungen as wrz_id, \'\' AS wasserrechtliche_zulassungen_link, a.ausstellbehoerde, \'\' AS ausstellbehoerde_link,  a.zustaendige_behoerde,  \'\' AS zustaendige_behoerde_link, a.adressat AS personen_id, \'\' AS adressat_link, COALESCE(e.name,\'\') AS adressat_name, COALESCE(e.namenszusatz,\'\') AS adressat_namenszusatz, COALESCE(h.strasse,\'\') ||\'  \'|| COALESCE(h.hausnummer,\'\') AS adressat_strasse_hausnummer, COALESCE(h.plz::text,\'\') ||\'  \'|| COALESCE(h.ort,\'\') AS adressat_plz_ort, a.bearbeiter, \'\' AS bearbeiter_link, COALESCE(e.name,\'\') AS bearbeiter_name, COALESCE(e.namenszusatz,\'\') AS bearbeiter_namenszusatz, COALESCE(h.strasse,\'\') ||\'  \'|| COALESCE(h.hausnummer,\'\') AS bearbeiter_strasse_hausnummer, COALESCE(h.plz::text,\'\') ||\'  \'|| COALESCE(h.ort,\'\') AS bearbeiter_plz_ort, COALESCE(e.zimmer,\'\') AS bearbeiter_zimmer, COALESCE(e.telefon,\'\') AS bearbeiter_telefon, COALESCE(e.fax,\'\') AS bearbeiter_fax, COALESCE(e.email,\'\') AS bearbeiter_email,  i.bezeichnung as wrz_bezeichnung, a.typus, a.bearbeiterzeichen, a.aktenzeichen, a.regnummer, a.bergamt_aktenzeichen, a.ort,  a.datum, a.fassung_auswahl, a.fassung_nummer, a.fassung_typus, a.fassung_bearbeiterzeichen, a.fassung_aktenzeichen, a.fassung_datum, a.gueltig_seit, a.befristet_bis, a.status, a.aktuell, CASE WHEN a.befristet_bis < current_date THEN \'nein\' ELSE \'ja\' END AS wirksam, a.ungueltig_seit, a.ungueltig_aufgrund, a.datum_postausgang,a.datum_bestand_mat, a.datum_bestand_form, a.dokument AS dokument, a.nachfolger AS nachfolger, a.vorgaenger AS vorgaenger, a.freigegeben as wrz_freigegeben, b.id as gwb_id, b.kennnummer, b.wasserbuchnummer, c.bezeichnung, b.freitext_art, b.art, b.freitext_zweck, b.zweck, b.ent_wb_alt, b.ent_datum_von, b.ent_datum_bis, b.max_ent_wee, b.max_ent_wee_beschreib, b.max_ent_wee_reduziert, b.max_ent_wb, b.max_ent_wb_beschreib, b.freigegeben, b.bearbeiter_name AS gewaesserbenutzung_bearbeiter_name, b.bearbeiter_id AS gewaesserbenutzung_bearbeiter_id, b.stelle_name, b.stelle_id, b.bearbeitungs_datum, \'\' AS gewaesserbenutzungen_lage, \'\' AS gewaesserbenutzungen_umfang FROM fiswrv_gewaesserbenutzungen b LEFT JOIN fiswrv_gewaesserbenutzungen_bezeichnung c ON c.id = b.id LEFT JOIN fiswrv_wasserrechtliche_zulassungen a ON b.wasserrechtliche_zulassungen = a.id LEFT JOIN fiswrv_wasserrechtliche_zulassungen_bezeichnung i ON a.id = i.id LEFT JOIN fiswrv_anlagen f ON a.anlage=f.id  LEFT JOIN fiswrv_anlagen_klasse g ON f.klasse=g.id LEFT JOIN fiswrv_personen e ON a.adressat=e.id LEFT JOIN fiswrv_adresse h ON e.adresse=h.id  WHERE 1=1', 'fiswrv_gewaesserbenutzungen', '', 'wasserrecht', '', '', '', '', 'kennnummer', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '2', '', '0');
SET @last_layer_id33=LAST_INSERT_ID();

-- Zuordnung Layer 33 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id33, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 33 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id33, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 33 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id33, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Attribut adressat_link des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'adressat_link', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=9&value_personen_id=$personen_id&operator_personen_id==;Adressaten anzeigen', 'Adressat [Link]', '', '', '', '', '', 'Adressat', NULL, '-1', '10', '0', '0');

-- Attribut adressat_name des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'adressat_name', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Adressat, Name', '', '', '', '', 'Name der Adressaten-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Adressat', NULL, '-1', '11', '0', '0');

-- Attribut adressat_namenszusatz des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'adressat_namenszusatz', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Adressat, Namenszusatz', '', '', '', '', 'Namenszusatz der Adressaten-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Adressat', NULL, '-1', '12', '0', '0');

-- Attribut adressat_plz_ort des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'adressat_plz_ort', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Adressat, PLZ und Ort', '', '', '', '', 'Postleitzahl und Ort aus der Adresse der Adressaten-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Adressat', NULL, '-1', '14', '0', '0');

-- Attribut adressat_strasse_hausnummer des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'adressat_strasse_hausnummer', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Adressat, Straße und Hausnummer', '', '', '', '', 'Straße und Hausnummer aus der Adresse der Adressaten-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Adressat', NULL, '-1', '13', '0', '0');

-- Attribut aktenzeichen des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'aktenzeichen', 'aktenzeichen', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '0', '255', NULL, '', 'Text', '', 'Aktenzeichen  WrZ', '', '', '', '', 'PFLICHTFELD! Aktenzeichen der WrZ ohne unnötige Leerzeichen. [Unnötig sind dabei alle Leerzeichen in Verbindung mit anderen Trennzeichen wie', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '-1', '28', '0', '0');

-- Attribut aktuell des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'aktuell', 'aktuell', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'bool', '', '', '1', NULL, NULL, '', 'Auswahlfeld', 'select \'aktuell\' as output, true as value
union
select \'historisch\' as output, false as value', 'Aktualität', '', '', '', '', 'Gibt an ob die Wasserrechtliche Zulassung aktuell ist. Sie gilt dabei so lange als \'aktuell\' solange sie nicht aufgehoben, widerrufen etc. ist. Keine Wasserechtliche Zulassung darf sowohl \'aktuell\' wie auch \'historisch\' sein.', 'Status der Wasserrechtliche Zulassung', NULL, '-1', '42', '0', '0');

-- Attribut anlage_anzeige des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'anlage_anzeige', 'name', 'fiswrv_anlagen', 'f', 'varchar', '', '', '0', '255', NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=2&value_anlage_id=$anlage_id&operator_anlage_id==;Name: $anlage_anzeige;no_new_window', 'Name ANL', '', '', '', '', 'Name aus der Anlagentabelle.', 'Wasserrechtlich relevante Anlage', NULL, '-1', '1', '0', '0');

-- Attribut anlage_id des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'anlage_id', 'anlage', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '0', '32', '0', '', 'Text', '', 'Anlage ID', '', '', '', '', '', 'Wasserrechtlich relevante Anlage', NULL, '-1', '2', '0', '0');

-- Attribut anlage_klasse des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'anlage_klasse', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Klasse ANL', '', '', '', '', 'Klasse aus der Anlagentabelle.', 'Wasserrechtlich relevante Anlage', NULL, '-1', '0', '0', '0');

-- Attribut art des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'art', 'art', 'fiswrv_gewaesserbenutzungen', 'b', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_gewaesserbenutzungen_art;', 'Art nach WHG', '', '', '', '', '', 'Art', NULL, '0', '58', '1', '0');

-- Attribut ausstellbehoerde des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'ausstellbehoerde', 'ausstellbehoerde', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_behoerde;', 'Ausstellbehörde [Auswahlfeld]', '', '', '', '', 'Die Behörde, die die Wasserrechtliche Zulassung erteilt hat. [Neue Behörden müssen beim Admin beantragt werden.]', '<h1>Abschrift der Wasserrechtlichen Zulassung</h1>', NULL, '-1', '5', '0', '0');

-- Attribut ausstellbehoerde_link des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'ausstellbehoerde_link', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=17&value_id=$ausstellbehoerde&operator_id==;Behörde anzeigen', 'Ausstellbehörde [Link]', '', '', '', '', 'Link auf die ausgeählte Behörde, die die Wasserrechtliche Zulassung erteilt hat.', '<h1>Abschrift der Wasserrechtlichen Zulassung</h1>', NULL, '-1', '6', '0', '0');

-- Attribut bearbeiter des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'bearbeiter', 'bearbeiter', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
	z.id as value, z.name || \' (\' || coalesce(string_agg(b.name, \', \'),  \'keiner Behörde zugeordnet\') || \')\' AS output FROM
  fiswrv_personen z LEFT JOIN
  fiswrv_behoerde b ON z.behoerde = b.id WHERE NOT (behoerde IS NULL)
GROUP BY
  z.id, z.name ;layer_id=9 embedded', 'Bearbeiter [Auswahlfeld]', '', '', '', '', 'Das Feld dient der Auswahl der Bearbeiter-Person der Wasserrechtlichen Zulassung. % und _ entsprechen * und ? als Platzhalter. Nicht vorhandene Bearbeiter-Personen müssen neu angelegt werden.', 'Bearbeiter', NULL, '-1', '15', '0', '0');

-- Attribut bearbeiterzeichen des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'bearbeiterzeichen', 'bearbeiterzeichen', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Bearbeiterzeichen WrZ', '', '', '', '', 'Bearbeiterzeichen der Wasserrechtlichen Zulassung ohne unnötige Lesezeichen. [Bearbeiterzeichen werden vor die Aktenzeichen geschrieben und sollen zu besseren Identifizierbarkeit der WrZ getrennt vom Aktenzeichen gespeichert werden. Unnötig sind dabei all', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '-1', '27', '0', '0');

-- Attribut bearbeiter_email des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'bearbeiter_email', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Email', '', '', '', '', 'Email der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '-1', '24', '0', '0');

-- Attribut bearbeiter_fax des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'bearbeiter_fax', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Fax', '', '', '', '', 'Fax der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '-1', '23', '0', '0');

-- Attribut bearbeiter_link des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'bearbeiter_link', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=9&value_personen_id=$bearbeiter&operator_personen_id==;Bearbeiter anzeigen', 'Bearbeiter [Link]', '', '', '', '', 'Link auf die ausgewählte Bearbeiter-Person im FisWrV-Per (Personenmodul) der Wasserrechtlichen Zulassung.', 'Bearbeiter', NULL, '-1', '16', '0', '0');

-- Attribut bearbeiter_name des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'bearbeiter_name', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Name', '', '', '', '', 'Name der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '-1', '17', '0', '0');

-- Attribut bearbeiter_namenszusatz des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'bearbeiter_namenszusatz', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Namenszusatz', '', '', '', '', 'Namenszusatz der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '-1', '18', '0', '0');

-- Attribut bearbeiter_plz_ort des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'bearbeiter_plz_ort', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, PLZ und Ort', '', '', '', '', 'Postleitzahl und Ort aus der Adresse der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '-1', '20', '0', '0');

-- Attribut bearbeiter_strasse_hausnummer des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'bearbeiter_strasse_hausnummer', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Straße und Hausnummer', '', '', '', '', 'Straße und Hausnummer aus der Adresse der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '-1', '19', '0', '0');

-- Attribut bearbeiter_telefon des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'bearbeiter_telefon', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Telefon', '', '', '', '', 'Telefon der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '-1', '22', '0', '0');

-- Attribut bearbeiter_zimmer des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'bearbeiter_zimmer', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Zimmer', '', '', '', '', 'Zimmer der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '-1', '21', '0', '0');

-- Attribut bearbeitungs_datum des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'bearbeitungs_datum', 'bearbeitungs_datum', 'fiswrv_gewaesserbenutzungen', 'b', 'date', '', '', '1', NULL, NULL, '', 'Time', '', 'Datum und Uhrzeit', '', '', '', '', '', 'letzte Änderung', NULL, '0', '74', '0', '0');

-- Attribut befristet_bis des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'befristet_bis', 'befristet_bis', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Text', '', 'Befristet bis', '', '', '', '', 'Datum bis zu dem die Wrz gültig ist. [Ist das Feld leer, wird angenommen, dass die WrZ unbegrenzt gültig ist.]', 'Materielle Bestandskraft', NULL, '-1', '40', '0', '0');

-- Attribut bergamt_aktenzeichen des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'bergamt_aktenzeichen', 'bergamt_aktenzeichen', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Bergamt Aktenzeichen', '', '', '', '', 'Alternatives Aktenzeichen des Bergamtes. Erscheint nur auf den dem LUNG übersendeten Tabellen.', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '-1', '30', '0', '0');

-- Attribut bezeichnung des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'bezeichnung', 'bezeichnung', 'fiswrv_gewaesserbenutzungen_bezeichnung', 'c', 'text', '', '', '1', NULL, NULL, '', 'Text', '', 'Bezeichnung', '', '', '', '', '', '<h1>Benutzung</h1>', NULL, '0', '56', '0', '0');

-- Attribut datum des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'datum', 'datum', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '0', NULL, NULL, '', 'Text', '', 'Datum WrZ', '', '', '', '', 'PFLICHTFELD! Datum auf das die WrZ datiert wurde.', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '-1', '32', '0', '0');

-- Attribut datum_bestand_form des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'datum_bestand_form', 'datum_bestand_form', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Text', '', 'Unanfechtbar', '', '', '', '', 'Tag an dem der Bescheid als unanfechtbar gilt und formell bestandskräftig ist (Dreitagesfiktion §41 Satz 2 VwVfG + 1 Monat)', 'Status der Wasserrechtliche Zulassung', NULL, '-1', '48', '0', '0');

-- Attribut datum_bestand_mat des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'datum_bestand_mat', 'datum_bestand_mat', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Text', '', 'Bekanntgegeben', '', '', '', '', 'Tag an dem der Bescheid als bekanntgeben gilt und materiell bestandskräftig ist (Dreitagesfiktion §41 Satz 2 VwVfG)', 'Status der Wasserrechtliche Zulassung', NULL, '-1', '47', '0', '0');

-- Attribut datum_postausgang des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'datum_postausgang', 'datum_postausgang', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Text', '', 'Postausgang', '', '', '', '', '', 'Status der Wasserrechtliche Zulassung', NULL, '-1', '46', '0', '0');

-- Attribut dokument des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'dokument', 'dokument', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Dokument', '', '', '', '', 'Eingescanntes Dokument der Wasserrechtluchen Zulassung', 'Dokument', NULL, '-1', '49', '0', '0');

-- Attribut ent_datum_bis des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'ent_datum_bis', 'ent_datum_bis', 'fiswrv_gewaesserbenutzungen', 'b', 'date', '', '', '1', NULL, NULL, '', 'Text', '', 'Entnahme bis', '', '', '', '', '', 'Entnahme', NULL, '0', '63', '1', '0');

-- Attribut ent_datum_von des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'ent_datum_von', 'ent_datum_von', 'fiswrv_gewaesserbenutzungen', 'b', 'date', '', '', '1', NULL, NULL, '', 'Text', '', 'Entnahme von', '', '', '', '', '', 'Entnahme', NULL, '0', '62', '1', '0');

-- Attribut ent_wb_alt des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'ent_wb_alt', 'ent_wb_alt', 'fiswrv_gewaesserbenutzungen', 'b', 'text', '', '', '1', NULL, NULL, '', 'Text', '', 'Umfang Wasserbuch (alt)', '', '', '', '', '', 'Entnahme', NULL, '0', '61', '1', '0');

-- Attribut fassung_aktenzeichen des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'fassung_aktenzeichen', 'fassung_aktenzeichen', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Aktenzeichen oder Registriernummer der Änderung', '', '', '', '', 'Abweichendes  Aktenzeichen eines Änderungsbescheides (o. ä.) zu einer Wasserrechtlichen Zulassung. [Unnötig sind dabei alle Leerzeichen in Verbindung mit anderen Trennzeichen wie', 'Fassung', NULL, '-1', '37', '0', '0');

-- Attribut fassung_auswahl des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'fassung_auswahl', 'fassung_auswahl', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_wasserrechtliche_zulassungen_fassung_auswahl;', 'Fassung WrZ', '', '', '', '', 'Das Feld dient  der korrekten Bezeichnung der eventuellen Fassung einer Wasserrechtlichen Zulassung.', 'Fassung', NULL, '-1', '33', '0', '0');

-- Attribut fassung_bearbeiterzeichen des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'fassung_bearbeiterzeichen', 'fassung_bearbeiterzeichen', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Bearbeiterzeichen der Änderung (LUNG, StALU und StAUN)', '', '', '', '', 'Abweichendes Bearbeiterzeichen eines Änderungsbescheides (o. ä.) zu einer Wasserrechtlichen Zulassung ohne unnötige Leerzeichen. [Bearbeiterzeichen werden vom LUNG und den StÄLU (historisch auch StÄUN) vor die Aktenzeichen geschrieben und sollen zu besser', 'Fassung', NULL, '-1', '36', '0', '0');

-- Attribut fassung_datum des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'fassung_datum', 'fassung_datum', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Text', '', 'Datum Fassung WrZ', '', '', '', '', 'Datum auf das die Fassung der WrZ datiert wurde.', 'Fassung', NULL, '-1', '38', '0', '0');

-- Attribut fassung_nummer des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'fassung_nummer', 'fassung_nummer', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Text', '', 'Nummer Fassung WrZ', '', '', '', '', 'Das Feld dient  der korrekten Nummerierung der eventuellen Fassung einer Wasserrechtlichen Zulassung.', 'Fassung', NULL, '-1', '34', '0', '0');

-- Attribut fassung_typus des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'fassung_typus', 'fassung_typus', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_wasserrechtliche_zulassungen_fassung_typus;', 'Typus Fassung WrZ', '', '', '', '', 'Typus der eventuellen Fassung einer Wasserrechtlichen Zulassung (z.B. Änderungsbescheid, Anpassungsbescheid, etc.) [Neue Typen müssen über den Admin beantragt werden]', 'Fassung', NULL, '-1', '35', '0', '0');

-- Attribut freigegeben des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'freigegeben', 'freigegeben', 'fiswrv_gewaesserbenutzungen', 'b', 'bool', '', '', '1', NULL, NULL, 'SELECT false', 'Auswahlfeld', 'select \'ja\' as output, true as value
union
select \'nein\' as output, false as value', 'Freigegeben', '', '', '', '', 'Gewässerbenutzung freigegeben?', 'Historienverwaltung', NULL, '0', '69', '1', '0');

-- Attribut freitext_art des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'freitext_art', 'freitext_art', 'fiswrv_gewaesserbenutzungen', 'b', 'text', '', '', '1', NULL, NULL, '', 'Text', '', 'Freitext: Art der Gewässerbenutzung', '', '', '', '', '', 'Art', NULL, '0', '57', '1', '0');

-- Attribut freitext_zweck des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'freitext_zweck', 'freitext_zweck', 'fiswrv_gewaesserbenutzungen', 'b', 'text', '', '', '1', NULL, NULL, '', 'Text', '', 'Freitext: Zweck der Gewässerbenutzung', '', '', '', '', '', 'Zweck', NULL, '0', '59', '1', '0');

-- Attribut gewaesserbenutzungen_lage des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'gewaesserbenutzungen_lage', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '38,gwb_id,name;no_new_window', 'Lage', '', '', '', '', '', 'Lage', NULL, '-1', '75', '0', '0');

-- Attribut gewaesserbenutzungen_umfang des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'gewaesserbenutzungen_umfang', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '37,gwb_id,name wert einheit;no_new_window', 'Gwässerbenutzungen Umfang', '', '', '', '', '', 'Gwässerbenutzungen Umfang', NULL, '0', '76', '0', '0');

-- Attribut gewaesserbenutzung_bearbeiter_id des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'gewaesserbenutzung_bearbeiter_id', 'bearbeiter_id', 'fiswrv_gewaesserbenutzungen', 'b', 'varchar', '', '', '1', '10', NULL, '', 'UserID', '', 'Gewässerbenutzung Bearbeiter ID', '', '', '', '', '', 'letzte Änderung', NULL, '0', '71', '0', '0');

-- Attribut gewaesserbenutzung_bearbeiter_name des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'gewaesserbenutzung_bearbeiter_name', 'bearbeiter_name', 'fiswrv_gewaesserbenutzungen', 'b', 'varchar', '', '', '1', '255', NULL, '', 'User', '', 'Gewässerbenutzung Bearbeiter Name', '', '', '', '', 'Name des Benutzers, der als letztes eine Änderung gespeichert hat.', 'letzte Änderung', NULL, '0', '70', '0', '0');

-- Attribut gueltig_seit des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'gueltig_seit', 'gueltig_seit', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Text', '', 'Gültig seit', '', '', '', '', 'Datum seit dem die Wrz gültig ist. [Ist das Feld leer, wird angenommen, dass die WrZ ab dem Ausstellungsdatum gültig ist.]', 'Materielle Bestandskraft', NULL, '-1', '39', '0', '0');

-- Attribut gwb_id des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'gwb_id', 'id', 'fiswrv_gewaesserbenutzungen', 'b', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', 'Primärschlüssel Gewässerbeutzung', '', '', '', '', '', '<h1>Benutzung</h1>', NULL, '-1', '53', '0', '0');

-- Attribut kennnummer des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'kennnummer', 'kennnummer', 'fiswrv_gewaesserbenutzungen', 'b', 'varchar', '', '', '1', '255', NULL, '', 'Text', 'SELECT case when \'$wrz_id\' = \'\' then \'Bitte erst eine Wasserrechtliche Zulassung auswählen!\' else (select a.id ||\'-\'|| c.id ||\'-\'|| d.id ||\'-\'|| b.id ||\'-\'|| (select CASE WHEN \'$gwb_id\' = \'\' THEN (last_value + 1)::text ELSE \'$gwb_id\' END as id from wasserrecht.fiswrv_gewaesserbenutzungen_id_seq) AS output FROM wasserrecht.fiswrv_wasserrechtliche_zulassungen b INNER JOIN wasserrecht.fiswrv_behoerde a ON a.id = b.ausstellbehoerde INNER JOIN wasserrecht.fiswrv_personen c ON c.id = b.adressat INNER JOIN wasserrecht.fiswrv_anlagen d ON d.id = b.anlage WHERE b.id::text = \'$wrz_id\') end', 'Benutzungsnummer', '', '', '', '', '', '<h1>Benutzung</h1>', NULL, '0', '54', '1', '0');

-- Attribut max_ent_wb des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'max_ent_wb', 'max_ent_wb', 'fiswrv_gewaesserbenutzungen', 'b', 'numeric', '', '', '1', NULL, NULL, '', 'Text', '', 'WB-Menge m³/Tag', '', '', '', '', '', 'Entnahme', NULL, '0', '67', '1', '0');

-- Attribut max_ent_wb_beschreib des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'max_ent_wb_beschreib', 'max_ent_wb_beschreib', 'fiswrv_gewaesserbenutzungen', 'b', 'text', '', '', '1', NULL, NULL, '', 'Text', '', 'Beschreibung WB', '', '', '', '', '', 'Entnahme', NULL, '0', '68', '1', '0');

-- Attribut max_ent_wee des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'max_ent_wee', 'max_ent_wee', 'fiswrv_gewaesserbenutzungen', 'b', 'numeric', '', '', '1', NULL, NULL, '', 'Text', '', 'WEE-Menge m³/Jahr', '', '', '', '', '', 'Entnahme', NULL, '0', '64', '1', '0');

-- Attribut max_ent_wee_beschreib des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'max_ent_wee_beschreib', 'max_ent_wee_beschreib', 'fiswrv_gewaesserbenutzungen', 'b', 'text', '', '', '1', NULL, NULL, '', 'Text', '', 'Beschreibung WEE', '', '', '', '', '', 'Entnahme', NULL, '0', '65', '1', '0');

-- Attribut max_ent_wee_reduziert des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'max_ent_wee_reduziert', 'max_ent_wee_reduziert', 'fiswrv_gewaesserbenutzungen', 'b', 'numeric', '', '', '1', NULL, NULL, '', 'Text', '', 'WEE-Menge m³/Jahr [reduziert]', '', '', '', '', 'Die Unteren Wasserbehörden sollen hier einen Wert eingeben können, falls in Vorzeiträumen schon Teilmengen der Jahresmenge entnommen worden sind.', 'Entnahme', NULL, '0', '66', '1', '0');

-- Attribut nachfolger des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'nachfolger', 'nachfolger', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT a.id as value, a.bezeichnung AS output FROM fiswrv_wasserrechtliche_zulassungen_bezeichnung a WHERE a.anlage = <requires>anlage_id</requires> AND a.id !=<requires>wrz_id</requires> ;', 'Nachfolger', '', '', '', '', 'Nachfolge WrZ', 'Historienverwaltung', NULL, '-1', '50', '0', '0');

-- Attribut ort des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'ort', 'ort', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Autovervollständigungsfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_ort;', 'Ort', '', '', '', '', 'Ort an dem der Bescheid ausgestellt wurde.', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '-1', '31', '0', '0');

-- Attribut personen_id des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'personen_id', 'adressat', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Autovervollständigungsfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_personen a WHERE a.wrzadressat IS NOT NULL AND a.wrzadressat = \'ja\';', 'Adressat [Auswahlfeld]', '', '', '', '', '', 'Adressat', NULL, '-1', '9', '0', '0');

-- Attribut regnummer des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'regnummer', 'regnummer', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Registriernummer WrZ', '', '', '', '', 'Registriernummer der WrZ ohne unnötige Leerzeichen. [Unnötig sind dabei alle Leerzeichen in Verbindung mit anderen Trennzeichen wie', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '-1', '29', '0', '0');

-- Attribut status des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'status', 'status', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_wasserrechtliche_zulassungen_status;', 'Status des Dokuments', '', '', '', '', 'Gibt an ob es sich bei dem Datensatz um eine geprüft Abschrift einer Wasserrechtlichen Zulassung (geringste Fehlerrate), eine Übertragung aus einer geprüften LUNG-Datenbank (mittlere Fehlerrate) oder um Erstbefüllungsdaten (höchste Fehlerrate) handelt.', 'Status der Wasserrechtliche Zulassung', NULL, '-1', '41', '0', '0');

-- Attribut stelle_id des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'stelle_id', 'stelle_id', 'fiswrv_gewaesserbenutzungen', 'b', 'varchar', '', '', '1', '10', NULL, '', 'StelleID', '', 'Stelle ID', '', '', '', '', '', 'letzte Änderung', NULL, '0', '73', '0', '0');

-- Attribut stelle_name des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'stelle_name', 'stelle_name', 'fiswrv_gewaesserbenutzungen', 'b', 'varchar', '', '', '1', '255', NULL, '', 'Stelle', '', 'Stelle', '', '', '', '', 'Stelle des Benutzers, der als letztes eine Änderung gespeichert hat.', 'letzte Änderung', NULL, '0', '72', '0', '0');

-- Attribut typus des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'typus', 'typus', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '0', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_wasserrechtliche_zulassungen_typus;', 'Typus Wrz', '', '', '', '', 'PFLICHTFELD! Typus der Wasserrechtlichen Zulassung (z.B. Wasserrechtliche Erlaubnis, Wasserrechtliche Nutzungsgenehmigung, etc.) [Neue Typen müssen über den Admin beantragt werden]', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '-1', '26', '0', '0');

-- Attribut ungueltig_aufgrund des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'ungueltig_aufgrund', 'ungueltig_aufgrund', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund;', 'Ungültig aufgrund', '', '', '', '', 'Grund warum die WrZ ungültig ist.', 'Status der Wasserrechtliche Zulassung', NULL, '-1', '45', '0', '0');

-- Attribut ungueltig_seit des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'ungueltig_seit', 'ungueltig_seit', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Text', '', 'Ungültig seit', '', '', '', '', 'Datum seit dem die WrZ ungültig ist. [Ist das Feld leer, wird angenommen, dass die WrZ gültig ist.]', 'Status der Wasserrechtliche Zulassung', NULL, '-1', '44', '0', '0');

-- Attribut vorgaenger des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'vorgaenger', 'vorgaenger', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT a.id as value, a.bezeichnung AS output FROM fiswrv_wasserrechtliche_zulassungen_bezeichnung a WHERE a.anlage = <requires>anlage_id</requires> AND a.id !=<requires>wrz_id</requires> ;', 'Vorgänger', '', '', '', '', 'Vorgänger WrZ', 'Historienverwaltung', NULL, '-1', '51', '0', '0');

-- Attribut wasserbuchnummer des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'wasserbuchnummer', 'wasserbuchnummer', 'fiswrv_gewaesserbenutzungen', 'b', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Wasserbuchnummer', '', '', '', '', '', '<h1>Benutzung</h1>', NULL, '0', '55', '1', '0');

-- Attribut wasserrechtliche_zulassungen_link des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'wasserrechtliche_zulassungen_link', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=25&value_wrz_id=$wrz_id&operator_wrz_id==;Wasserrechtliche Zulassung anzeigen;no_new_window', 'Wasserrechtliche Zulassung [Link]', '', '', '', '', '', 'Wasserrechtliche Zulassungen', NULL, '-1', '4', '0', '0');

-- Attribut wirksam des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'wirksam', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Wirksam', '', '', '', '', '', 'Status der Wasserrechtliche Zulassung', NULL, '-1', '43', '0', '0');

-- Attribut wrz_bezeichnung des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'wrz_bezeichnung', 'bezeichnung', 'fiswrv_wasserrechtliche_zulassungen_bezeichnung', 'i', 'text', '', '', '1', NULL, NULL, '', 'Text', '', 'Bezeichnung Wrz', '', '', '', '', 'Standartisierte eindeutige Bezeichnung der Wasserrechtlichen Zulassung zur Identifikation in anderen Tabellen', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '-1', '25', '0', '0');

-- Attribut wrz_freigegeben des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'wrz_freigegeben', 'freigegeben', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'bool', '', '', '1', NULL, NULL, 'SELECT false', 'Auswahlfeld', 'select \'ja\' as output, true as value
union
select \'nein\' as output, false as value', 'Freigegeben', '', '', '', '', 'WrZ freigegeben?', 'Historienverwaltung', NULL, '-1', '52', '0', '0');

-- Attribut wrz_id des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'wrz_id', 'wasserrechtliche_zulassungen', 'fiswrv_gewaesserbenutzungen', 'b', 'int4', '', '', '0', '32', '0', '', 'Autovervollständigungsfeld', 'SELECT  a.id as value, a.bezeichnung AS output FROM fiswrv_wasserrechtliche_zulassungen_bezeichnung a', 'Wasserrechtliche Zulassung [Auswahlfeld]', '', '', '', '', '', 'Wasserrechtliche Zulassungen', NULL, '0', '3', '1', '0');

-- Attribut zustaendige_behoerde des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'zustaendige_behoerde', 'zustaendige_behoerde', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_behoerde;layer_id=17 embedded', 'Zutändige Behörde WrZ [Auswahlfeld]', '', '', '', '', 'Die für die Wasserrechtliche Zulassung zuständige Behörde. [Neue Behörden müssen beim Admin beantragt werden.]', '<h1>Abschrift der Wasserrechtlichen Zulassung</h1>', NULL, '0', '7', '0', '0');

-- Attribut zustaendige_behoerde_link des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'zustaendige_behoerde_link', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=17&value_id=$zustaendige_behoerde&operator_id==;Zuständige Behörde anzeigen', 'Zutändige Behörde WrZ [Link]', '', '', '', '', 'Link auf die für die Wasserrechtliche Zulassung zuständige Behörde.', '<h1>Abschrift der Wasserrechtlichen Zulassung</h1>', NULL, '0', '8', '0', '0');

-- Attribut zweck des Layers 33
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id33, 'zweck', 'zweck', 'fiswrv_gewaesserbenutzungen', 'b', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, coalesce(nummer) || \') \' || name AS output from wasserrecht.fiswrv_gewaesserbenutzungen_zweck;', 'Zweck', '', '', '', '', '', 'Zweck', NULL, '0', '60', '1', '0');

-- Zuordnung der Layerattribute des Layers 33 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'adressat_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'adressat_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'adressat_namenszusatz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'adressat_plz_ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'adressat_strasse_hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'aktenzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'aktuell', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'anlage_anzeige', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'anlage_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'anlage_klasse', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'art', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'ausstellbehoerde', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'ausstellbehoerde_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'bearbeiter', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'bearbeiterzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'bearbeiter_email', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'bearbeiter_fax', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'bearbeiter_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'bearbeiter_namenszusatz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'bearbeiter_plz_ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'bearbeiter_strasse_hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'bearbeiter_telefon', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'bearbeiter_zimmer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'bearbeitungs_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'befristet_bis', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'bergamt_aktenzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'bezeichnung', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'datum_bestand_form', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'datum_bestand_mat', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'datum_postausgang', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'dokument', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'ent_datum_bis', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'ent_datum_von', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'ent_wb_alt', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'fassung_aktenzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'fassung_auswahl', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'fassung_bearbeiterzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'fassung_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'fassung_nummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'fassung_typus', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'freigegeben', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'freitext_art', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'freitext_zweck', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'gewaesserbenutzungen_lage', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'gewaesserbenutzungen_umfang', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'gewaesserbenutzung_bearbeiter_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'gewaesserbenutzung_bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'gueltig_seit', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'gwb_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'kennnummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'max_ent_wb', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'max_ent_wb_beschreib', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'max_ent_wee', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'max_ent_wee_beschreib', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'max_ent_wee_reduziert', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'nachfolger', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'personen_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'regnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'status', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'stelle_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'stelle_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'typus', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'ungueltig_aufgrund', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'ungueltig_seit', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'vorgaenger', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'wasserbuchnummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'wasserrechtliche_zulassungen_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'wirksam', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'wrz_bezeichnung', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'wrz_freigegeben', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'wrz_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'zustaendige_behoerde', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'zustaendige_behoerde_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_2, 'zweck', '1', '0');

-- Zuordnung der Layerattribute des Layers 33 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'adressat_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'adressat_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'adressat_namenszusatz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'adressat_plz_ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'adressat_strasse_hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'aktenzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'aktuell', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'anlage_anzeige', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'anlage_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'anlage_klasse', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'art', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'ausstellbehoerde', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'ausstellbehoerde_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'bearbeiter', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'bearbeiterzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'bearbeiter_email', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'bearbeiter_fax', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'bearbeiter_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'bearbeiter_namenszusatz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'bearbeiter_plz_ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'bearbeiter_strasse_hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'bearbeiter_telefon', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'bearbeiter_zimmer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'bearbeitungs_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'befristet_bis', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'bergamt_aktenzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'bezeichnung', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'datum_bestand_form', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'datum_bestand_mat', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'datum_postausgang', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'dokument', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'ent_datum_bis', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'ent_datum_von', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'ent_wb_alt', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'fassung_aktenzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'fassung_auswahl', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'fassung_bearbeiterzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'fassung_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'fassung_nummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'fassung_typus', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'freigegeben', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'freitext_art', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'freitext_zweck', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'gewaesserbenutzungen_lage', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'gewaesserbenutzungen_umfang', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'gewaesserbenutzung_bearbeiter_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'gewaesserbenutzung_bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'gueltig_seit', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'gwb_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'kennnummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'max_ent_wb', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'max_ent_wb_beschreib', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'max_ent_wee', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'max_ent_wee_beschreib', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'max_ent_wee_reduziert', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'nachfolger', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'personen_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'regnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'status', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'stelle_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'stelle_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'typus', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'ungueltig_aufgrund', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'ungueltig_seit', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'vorgaenger', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'wasserbuchnummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'wasserrechtliche_zulassungen_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'wirksam', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'wrz_bezeichnung', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'wrz_freigegeben', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'wrz_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'zustaendige_behoerde', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'zustaendige_behoerde_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_4, 'zweck', '1', '0');

-- Zuordnung der Layerattribute des Layers 33 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'adressat_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'adressat_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'adressat_namenszusatz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'adressat_plz_ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'adressat_strasse_hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'aktenzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'aktuell', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'anlage_anzeige', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'anlage_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'anlage_klasse', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'art', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'ausstellbehoerde', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'ausstellbehoerde_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'bearbeiter', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'bearbeiterzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'bearbeiter_email', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'bearbeiter_fax', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'bearbeiter_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'bearbeiter_namenszusatz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'bearbeiter_plz_ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'bearbeiter_strasse_hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'bearbeiter_telefon', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'bearbeiter_zimmer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'bearbeitungs_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'befristet_bis', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'bergamt_aktenzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'bezeichnung', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'datum_bestand_form', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'datum_bestand_mat', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'datum_postausgang', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'dokument', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'ent_datum_bis', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'ent_datum_von', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'ent_wb_alt', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'fassung_aktenzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'fassung_auswahl', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'fassung_bearbeiterzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'fassung_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'fassung_nummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'fassung_typus', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'freigegeben', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'freitext_art', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'freitext_zweck', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'gewaesserbenutzungen_lage', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'gewaesserbenutzungen_umfang', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'gewaesserbenutzung_bearbeiter_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'gewaesserbenutzung_bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'gueltig_seit', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'gwb_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'kennnummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'max_ent_wb', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'max_ent_wb_beschreib', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'max_ent_wee', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'max_ent_wee_beschreib', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'max_ent_wee_reduziert', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'nachfolger', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'personen_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'regnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'status', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'stelle_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'stelle_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'typus', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'ungueltig_aufgrund', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'ungueltig_seit', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'vorgaenger', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'wasserbuchnummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'wasserrechtliche_zulassungen_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'wirksam', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'wrz_bezeichnung', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'wrz_freigegeben', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'wrz_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'zustaendige_behoerde', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'zustaendige_behoerde_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id33, @stelle_id_5, 'zweck', '1', '0');

-- Layer 9
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('FisWrV-WRe Personen', '', '5', @group_id, 'SELECT a.id AS personen_id, a.typ, a.klasse, a.status, d.bezeichnung, a.name, a.abkuerzung, a.namenszusatz, a.adresse as adress_id, COALESCE(b.strasse,\'\') ||\'  \'|| COALESCE(b.hausnummer,\'\') AS strasse_hausnummer, COALESCE(b.plz::text,\'\') ||\'  \'|| COALESCE(b.ort,\'\') AS plz_ort,  a.register_amtsgericht, a.register_nummer,  a.telefon, a.fax, a.email, a.zimmer, a.verwendungszweck_wee, a.konto as konto_id, COALESCE(c.name,\'\') AS kontoname, COALESCE(c.iban,\'\') AS iban, COALESCE(c.bic,\'\') AS bic, COALESCE(c.verwendungszweck,\'\') AS verwendungszweck, COALESCE(c.personenkonto,\'\') AS personenkonto, COALESCE(c.kassenzeichen,\'\') AS kassenzeichen, a.behoerde, a.wrzaussteller, a.wrzadressat, a.wrzrechtsnachfolger, CASE when a.betreiber = \'ja\' then \'Betreiber\' ELSE \'false\' end AS betreiber, a.betreiber AS betreiber_id, CASE when a.bearbeiter  = \'ja\' then \'Bearbeiter\' ELSE \'false\' end AS bearbeiter, a.bearbeiter AS bearbeiter_id,  a.abwasser_koerperschaft, a.trinkwasser_koerperschaft, a.weeerklaerer, a.kommentar, a.bearbeiter_name , a.bearbeiter_id, a.stelle_name, a.stelle_id, a.bearbeitungs_datum,  true AS aktuell, \'\' AS per_wrz, \'\' AS per_wrz_ben FROM fiswrv_personen a LEFT JOIN fiswrv_personen_bezeichnung d ON a.id=d.id LEFT JOIN fiswrv_adresse b ON a.adresse=b.id LEFT JOIN fiswrv_konto c ON a.konto=c.id WHERE 1=1', 'fiswrv_personen', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '3', 'pixels', '35833', '', '1', NULL, NULL, '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id9=LAST_INSERT_ID();

-- Zuordnung Layer 9 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id9, @stelle_id_2, '1', '0', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 9 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id9, @stelle_id_4, '1', '0', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 9 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id9, @stelle_id_5, '1', '0', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut abkuerzung des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'abkuerzung', 'abkuerzung', 'fiswrv_personen', 'a', 'varchar', '', '', '0', '100', NULL, '', 'Text', '', '<strong>Abkürzung</strong>', '', '', '', '', 'PFLICHTFELD!  Gängie und möglichst eindeutige Abkürzung oder Kurzbezeichnung des Namens der Person (z.B. LUNG, StALU MM, EURAWASSER, REWA etc.)', 'Adressdaten', NULL, '0', '6', '1', '0');

-- Attribut abwasser_koerperschaft des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'abwasser_koerperschaft', 'abwasser_koerperschaft', 'fiswrv_personen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
	z.id as value, z.name || \' (\' || coalesce(string_agg(z2b.name, \', \'),  \'keiner Körperschaftsart zugeordnet\') || \')\' AS output FROM
  wasserrecht.fiswrv_koerperschaft z LEFT JOIN	
  wasserrecht.fiswrv_koerperschaft_art z2b ON z.art = z2b.id WHERE z2b.id = 2
GROUP BY
  z.id, z.name ;layer_id=6 embedded', 'Abwasserbeseitigungspflichtige Körperschaft', '', '', '', '', 'Die Person ist eine abwasserbeseitigungspflichtige Körperschaft (nach §40 Satz 1 LWaG M-V).', 'Gruppen', NULL, '0', '33', '1', '0');

-- Attribut adress_id des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'adress_id', 'adresse', 'fiswrv_personen', 'a', 'int4', '', '', '1', '32', '0', '', 'Autovervollständigungsfeld', 'SELECT id as value, ort||\' \'||plz||\' \'||strasse||\' \'||hausnummer as output from wasserrecht.fiswrv_adresse where 1=1;layer_id=12 embedded', 'Adresse', '', '', '', '', '', 'Adressdaten', NULL, '0', '8', '1', '0');

-- Attribut aktuell des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'aktuell', 'true', '', '', '', '', '', NULL, NULL, NULL, '', 'Autovervollständigungsfeld', 'select \'aktuelle:\' as output, true as value', '', '', '', '', '', '', 'Adressat von', NULL, '-1', '42', '0', '0');

-- Attribut bearbeiter des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'bearbeiter', '', 'fiswrv_personen', 'a', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', '', '', '', '', '', '', 'Gruppen', NULL, '-1', '31', '0', '0');

-- Attribut bearbeiter_id des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'bearbeiter_id', 'bearbeiter_id', 'fiswrv_personen', 'a', 'varchar', '', '', '1', '10', NULL, '', 'UserID', '', 'Bearbeiter ID', '', '', '', '', '', 'letzte Änderung', NULL, '0', '38', '0', '0');

-- Attribut bearbeiter_name des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'bearbeiter_name', 'bearbeiter_name', 'fiswrv_personen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'User', '', 'Bearbeiter Name', '', '', '', '', 'Name des Benutzers, der als letztes eine Änderung gespeichert hat.', 'letzte Änderung', NULL, '0', '37', '0', '0');

-- Attribut bearbeitungs_datum des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'bearbeitungs_datum', 'bearbeitungs_datum', 'fiswrv_personen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Time', '', 'Datum und Uhrzeit', '', '', '', '', '', 'letzte Änderung', NULL, '0', '41', '0', '0');

-- Attribut behoerde des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'behoerde', 'behoerde', 'fiswrv_personen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, abkuerzung as output from wasserrecht.fiswrv_behoerde;layer_id=17 embedded', 'Behörde', '', '', '', '', 'Behörde deren Mitarbeiter die betreffende Person ist.', 'Gruppen', NULL, '0', '25', '1', '0');

-- Attribut betreiber des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'betreiber', '', 'fiswrv_personen', 'a', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', '', '', '', '', '', '', 'Gruppen', NULL, '-1', '29', '0', '0');

-- Attribut betreiber_id des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'betreiber_id', 'betreiber', 'fiswrv_personen', 'a', 'varchar', '', '', '1', '10', NULL, '', 'Auswahlfeld', '\'ja\',\'nein\'', 'Betreiber ANL', '', '', '', '', 'Die Person ist Betreiber einer wasserrechtsrelevanten Anlage.', 'Gruppen', NULL, '0', '30', '1', '0');

-- Attribut bezeichnung des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'bezeichnung', 'bezeichnung', 'fiswrv_personen_bezeichnung', 'd', 'text', '', '', '1', NULL, NULL, '', 'Text', '', 'Bezeichnung', '', '', '', '', '', 'Systemdaten', NULL, '0', '4', '0', '0');

-- Attribut bic des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'bic', '', 'fiswrv_konto', 'c', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'BIC', '', '', '', '', '', 'Konto', NULL, '0', '21', '0', '0');

-- Attribut email des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'email', 'email', 'fiswrv_personen', 'a', 'varchar', '', '', '1', '50', NULL, '', 'Text', '', 'E-Mail-Adresse', '', '', '', '', '', 'Adressdaten', NULL, '0', '15', '1', '0');

-- Attribut fax des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'fax', 'fax', 'fiswrv_personen', 'a', 'varchar', '', '', '1', '50', NULL, '', 'Text', '', 'Faxnummer', '', '', '', '', '', 'Adressdaten', NULL, '0', '14', '1', '0');

-- Attribut iban des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'iban', '', 'fiswrv_konto', 'c', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'IBAN', '', '', '', '', '', 'Konto', NULL, '0', '20', '0', '0');

-- Attribut kassenzeichen des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'kassenzeichen', '', 'fiswrv_konto', 'c', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Kassenzeichen', '', '', '', '', '', 'Konto', NULL, '0', '24', '0', '0');

-- Attribut klasse des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'klasse', 'klasse', 'fiswrv_personen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_personen_klasse;layer_id=10 embedded', 'Klasse', '', '', '', '', '', 'Systemdaten', NULL, '0', '2', '1', '0');

-- Attribut kommentar des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'kommentar', 'kommentar', 'fiswrv_personen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Textfeld', '', 'Kommentar ans LUNG', '', '', '', '', 'KOMMENTARFELD!', 'Sonstiges', NULL, '0', '36', '1', '0');

-- Attribut kontoname des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'kontoname', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Kontoname', '', '', '', '', '', 'Konto', NULL, '0', '19', '0', '0');

-- Attribut konto_id des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'konto_id', 'konto', 'fiswrv_personen', 'a', 'int4', '', '', '1', '32', '0', '', 'Autovervollständigungsfeld', 'SELECT id as value, name||\' \'||iban||\' \'||bic||\' \'||verwendungszweck||\' \'||personenkonto||\' \'||kassenzeichen as output from wasserrecht.fiswrv_konto where 1=1;layer_id=16 embedded', 'Konto', '', '', '', '', '', 'Konto', NULL, '0', '18', '1', '0');

-- Attribut name des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'name', 'name', 'fiswrv_personen', 'a', 'varchar', '', '', '0', '255', NULL, '', 'Text', '', '<strong>Name</strong>', '', '', '', '', 'PFLICHTFELD! Offizieller vollständiger Name der Person.', 'Adressdaten', NULL, '0', '5', '1', '0');

-- Attribut namenszusatz des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'namenszusatz', 'namenszusatz', 'fiswrv_personen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Namenszusatz', '', '', '', '', '', 'Adressdaten', NULL, '0', '7', '1', '0');

-- Attribut personenkonto des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'personenkonto', '', 'fiswrv_konto', 'c', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Personenkonto', '', '', '', '', '', 'Konto', NULL, '0', '23', '0', '0');

-- Attribut personen_id des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'personen_id', 'id', 'fiswrv_personen', 'a', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', 'Primärschlüssel Personen', '', '', '', '', '', 'Stammdaten', NULL, '0', '0', '0', '0');

-- Attribut per_wrz des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'per_wrz', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '25,personen_id,aktuell,<b>Wasserrechtliche Zulassung:</b> bezeichnung;no_new_window', 'Wasserechtliche Zulassungen', '', '', '', '', 'Die Person ist Adressat der aufgeführten Wasserrechtlichen Zulassungen.', 'Adressat von', NULL, '0', '43', '1', '0');

-- Attribut per_wrz_ben des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'per_wrz_ben', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '33,personen_id,aktuell,<b>Gewässerbenutzungen:</b> bezeichnung;no_new_window', 'Gewässerbenutzungen', '', '', '', '', 'Die Person ist Benutzer der aufgeführten Benutzungen. Da sie Adressat der dazugehörigen Wasserrechtlichen Zulassungen ist.', 'Adressat von', NULL, '0', '44', '1', '0');

-- Attribut plz_ort des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'plz_ort', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Postleitzahl Ort', '', '', '', '', '', 'Adressdaten', NULL, '0', '10', '0', '0');

-- Attribut register_amtsgericht des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'register_amtsgericht', 'register_amtsgericht', 'fiswrv_personen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Registergericht', '', '', '', '', '', 'Adressdaten', NULL, '0', '11', '1', '0');

-- Attribut register_nummer des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'register_nummer', 'register_nummer', 'fiswrv_personen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Register + Nummer', '', '', '', '', '', 'Adressdaten', NULL, '0', '12', '1', '0');

-- Attribut status des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'status', 'status', 'fiswrv_personen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_personen_status;layer_id=11 embedded', 'Status', '', '', '', '', '', 'Systemdaten', NULL, '0', '3', '1', '0');

-- Attribut stelle_id des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'stelle_id', 'stelle_id', 'fiswrv_personen', 'a', 'varchar', '', '', '1', '10', NULL, '', 'StelleID', '', 'Stelle ID', '', '', '', '', '', 'letzte Änderung', NULL, '0', '40', '0', '0');

-- Attribut stelle_name des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'stelle_name', 'stelle_name', 'fiswrv_personen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Stelle', '', 'Stelle', '', '', '', '', 'Stelle des Benutzers, der als letztes eine Änderung gespeichert hat.', 'letzte Änderung', NULL, '0', '39', '0', '0');

-- Attribut strasse_hausnummer des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'strasse_hausnummer', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Straße Hausnummer', '', '', '', '', '', 'Adressdaten', NULL, '0', '9', '0', '0');

-- Attribut telefon des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'telefon', 'telefon', 'fiswrv_personen', 'a', 'varchar', '', '', '1', '50', NULL, '', 'Text', '', 'Telefonnummer', '', '', '', '', '', 'Adressdaten', NULL, '0', '13', '1', '0');

-- Attribut trinkwasser_koerperschaft des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'trinkwasser_koerperschaft', 'trinkwasser_koerperschaft', 'fiswrv_personen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
	z.id as value, z.name || \' (\' || coalesce(string_agg(z2b.name, \', \'),  \'keiner Körperschaftsart zugeordnet\') || \')\' AS output FROM
  wasserrecht.fiswrv_koerperschaft z LEFT JOIN	
  wasserrecht.fiswrv_koerperschaft_art z2b ON z.art = z2b.id WHERE z2b.id = 1
GROUP BY
  z.id, z.name ;layer_id=6 embedded', 'Träger der öffentlichen Wasserversorgung', '', '', '', '', 'Die Person ist Träger der öffentlichen Wasserversorgung (nach § 43 Satz 1 LWaG M-V).', 'Gruppen', NULL, '0', '34', '1', '0');

-- Attribut typ des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'typ', 'typ', 'fiswrv_personen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_personen_typ;layer_id=13 embedded', 'Körperschaft', '', '', '', '', 'Auswahlfeld. Handelt es sich bei der Person um eine Körperschaft des öffentlichen Rechts oder des Privatrechts.', 'Systemdaten', NULL, '0', '1', '1', '0');

-- Attribut verwendungszweck des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'verwendungszweck', '', 'fiswrv_konto', 'c', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Verwendungszweck', '', '', '', '', '', 'Konto', NULL, '0', '22', '0', '0');

-- Attribut verwendungszweck_wee des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'verwendungszweck_wee', 'verwendungszweck_wee', 'fiswrv_personen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Verwendungszweck WEE', '', '', '', '', 'Personenbezogeneer Verwendungszweck für die Überweisung des Wasserentnahmeentgelts.', 'Adressdaten', NULL, '0', '17', '1', '0');

-- Attribut weeerklaerer des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'weeerklaerer', 'weeerklaerer', 'fiswrv_personen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_weeerklaerer;layer_id=14 embedded', 'Erklärer WEE', '', '', '', '', 'Die Person hat eine Erklärung zum Wassernahmeentgelt abgegeben.', 'Gruppen', NULL, '0', '35', '1', '0');

-- Attribut wrzadressat des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'wrzadressat', 'wrzadressat', 'fiswrv_personen', 'a', 'varchar', '', '', '1', '10', NULL, '', 'Auswahlfeld', '\'ja\',\'nein\'', 'Adressat WrZ', '', '', '', '', 'Die Person ist Adressat einer Wasserrechtlichen Zulassung.', 'Gruppen', NULL, '0', '27', '1', '0');

-- Attribut wrzaussteller des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'wrzaussteller', 'wrzaussteller', 'fiswrv_personen', 'a', 'varchar', '', '', '1', '10', NULL, '', 'Auswahlfeld', '\'ja\',\'nein\'', 'Aussteller WrZ', '', '', '', '', 'Die Person kann Wasserrechtliche Zulassungen erteilen. [Kann nur vom Admin bearbeitet werden]', 'Gruppen', NULL, '0', '26', '1', '0');

-- Attribut wrzrechtsnachfolger des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'wrzrechtsnachfolger', 'wrzrechtsnachfolger', 'fiswrv_personen', 'a', 'varchar', '', '', '1', '10', NULL, '', 'Auswahlfeld', '\'ja\',\'nein\'', 'Rechtsnachfolger WrZ', '', '', '', '', 'Die Person ist Rechtsnachfolger eines Adressaten einer Wasserrechtlichen Zulassung.', 'Gruppen', NULL, '0', '28', '1', '0');

-- Attribut zimmer des Layers 9
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id9, 'zimmer', 'zimmer', 'fiswrv_personen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Zimmer', '', '', '', '', '', 'Adressdaten', NULL, '0', '16', '1', '0');

-- Zuordnung der Layerattribute des Layers 9 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'abkuerzung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'abwasser_koerperschaft', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'adress_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'aktuell', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'bearbeiter', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'bearbeiter_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'bearbeitungs_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'behoerde', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'betreiber', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'betreiber_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'bezeichnung', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'bic', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'email', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'fax', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'iban', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'kassenzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'klasse', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'kommentar', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'kontoname', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'konto_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'namenszusatz', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'personenkonto', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'personen_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'per_wrz', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'per_wrz_ben', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'plz_ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'register_amtsgericht', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'register_nummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'status', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'stelle_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'stelle_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'strasse_hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'telefon', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'trinkwasser_koerperschaft', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'typ', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'verwendungszweck', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'verwendungszweck_wee', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'weeerklaerer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'wrzadressat', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'wrzaussteller', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'wrzrechtsnachfolger', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_2, 'zimmer', '1', '0');

-- Zuordnung der Layerattribute des Layers 9 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'abkuerzung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'abwasser_koerperschaft', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'adress_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'aktuell', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'bearbeiter', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'bearbeiter_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'bearbeitungs_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'behoerde', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'betreiber', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'betreiber_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'bezeichnung', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'bic', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'email', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'fax', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'iban', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'kassenzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'klasse', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'kommentar', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'kontoname', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'konto_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'namenszusatz', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'personenkonto', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'personen_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'per_wrz', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'per_wrz_ben', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'plz_ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'register_amtsgericht', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'register_nummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'status', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'stelle_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'stelle_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'strasse_hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'telefon', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'trinkwasser_koerperschaft', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'typ', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'verwendungszweck', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'verwendungszweck_wee', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'weeerklaerer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'wrzadressat', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'wrzaussteller', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'wrzrechtsnachfolger', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_4, 'zimmer', '1', '0');

-- Zuordnung der Layerattribute des Layers 9 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'abkuerzung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'abwasser_koerperschaft', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'adress_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'aktuell', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'bearbeiter', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'bearbeiter_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'bearbeitungs_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'behoerde', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'betreiber', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'betreiber_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'bezeichnung', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'bic', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'email', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'fax', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'iban', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'kassenzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'klasse', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'kommentar', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'kontoname', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'konto_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'namenszusatz', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'personenkonto', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'personen_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'per_wrz', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'per_wrz_ben', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'plz_ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'register_amtsgericht', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'register_nummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'status', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'stelle_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'stelle_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'strasse_hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'telefon', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'trinkwasser_koerperschaft', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'typ', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'verwendungszweck', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'verwendungszweck_wee', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'weeerklaerer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'wrzadressat', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'wrzaussteller', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'wrzrechtsnachfolger', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id9, @stelle_id_5, 'zimmer', '1', '0');

-- Layer 25
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('FisWrV-WRe WrZ', '', '5', @group_id, 'SELECT COALESCE(d.name,\'\') AS anlage_klasse, a.anlage AS anlage_id, b.name AS anlage_anzeige, a.id as wrz_id, COALESCE(b.id::text,\'\') ||\' -\'|| COALESCE(a.id::text,\'\') AS wrz_wid, a.ausstellbehoerde, \'\' AS ausstellbehoerde_link, a.zustaendige_behoerde,  \'\' AS zustaendige_behoerde_link, a.adressat AS personen_id, \'\' AS adressat_link, COALESCE(e.name,\'\') AS adressat_name, COALESCE(e.namenszusatz,\'\') AS adressat_namenszusatz, COALESCE(f.strasse,\'\') ||\'  \'|| COALESCE(f.hausnummer,\'\') AS adressat_strasse_hausnummer, COALESCE(f.plz::text,\'\') ||\'  \'|| COALESCE(f.ort,\'\') AS adressat_plz_ort, a.bearbeiter, \'\' AS bearbeiter_link, COALESCE(g.name,\'\') AS bearbeiter_name, COALESCE(g.namenszusatz,\'\') AS bearbeiter_namenszusatz, COALESCE(h.strasse,\'\') ||\'  \'|| COALESCE(h.hausnummer,\'\') AS bearbeiter_strasse_hausnummer, COALESCE(h.plz::text,\'\') ||\'  \'|| COALESCE(h.ort,\'\') AS bearbeiter_plz_ort, COALESCE(g.zimmer,\'\') AS bearbeiter_zimmer, COALESCE(g.telefon,\'\') AS bearbeiter_telefon, COALESCE(g.fax,\'\') AS bearbeiter_fax, COALESCE(g.email,\'\') AS bearbeiter_email,  c.bezeichnung, a.typus, a.bearbeiterzeichen, a.aktenzeichen, a.regnummer, a.bergamt_aktenzeichen, a.ort,  a.datum, a.fassung_auswahl, a.fassung_nummer, a.fassung_typus, a.fassung_bearbeiterzeichen, a.fassung_aktenzeichen, a.fassung_datum, a.gueltig_seit, a.befristet_bis, a.status, a.aktuell, CASE WHEN a.ungueltig_seit <= current_date THEN \'nein\' ELSE CASE WHEN a.befristet_bis < current_date THEN \'nein\' ELSE \'ja\' END END AS wirksam, a.ungueltig_seit, a.ungueltig_aufgrund, a.datum_postausgang,a.datum_bestand_mat, a.datum_bestand_form, a.dokument AS dokument, a.nachfolger AS nachfolger, a.vorgaenger AS vorgaenger, a.freigegeben, a.bearbeiter_name AS wrz_bearbeiter_name, a.bearbeiter_id AS wrz_bearbeiter_id, a.stelle_name, a.stelle_id, a.bearbeitungs_datum, \'\' AS wrz_ben  FROM fiswrv_wasserrechtliche_zulassungen a LEFT JOIN fiswrv_wasserrechtliche_zulassungen_bezeichnung c ON a.id = c.id LEFT JOIN fiswrv_anlagen b ON a.anlage=b.id LEFT JOIN fiswrv_anlagen_klasse d ON b.klasse=d.id LEFT JOIN fiswrv_personen e ON a.adressat=e.id LEFT JOIN fiswrv_adresse f ON e.adresse=f.id LEFT JOIN fiswrv_personen g ON a.bearbeiter=g.id LEFT JOIN fiswrv_adresse h ON g.adresse=h.id WHERE 1=1', 'fiswrv_wasserrechtliche_zulassungen', '', 'wasserrecht', '/var/www/data/wasserrecht/wrz/', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '2', '', '0');
SET @last_layer_id25=LAST_INSERT_ID();

-- Zuordnung Layer 25 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id25, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 25 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id25, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 25 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id25, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Attribut adressat_link des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'adressat_link', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=9&value_personen_id=$personen_id&operator_personen_id==;Adressaten anzeigen', 'Adressat [Link]', '', '', '', '', '', 'Adressat', NULL, '0', '10', '0', '0');

-- Attribut adressat_name des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'adressat_name', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Adressat, Name', '', '', '', '', 'Name der Adressaten-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Adressat', NULL, '0', '11', '0', '0');

-- Attribut adressat_namenszusatz des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'adressat_namenszusatz', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Adressat, Namenszusatz', '', '', '', '', 'Namenszusatz der Adressaten-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Adressat', NULL, '0', '12', '0', '0');

-- Attribut adressat_plz_ort des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'adressat_plz_ort', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Adressat, PLZ und Ort', '', '', '', '', 'Postleitzahl und Ort aus der Adresse der Adressaten-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Adressat', NULL, '0', '14', '0', '0');

-- Attribut adressat_strasse_hausnummer des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'adressat_strasse_hausnummer', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Adressat, Straße und Hausnummer', '', '', '', '', 'Straße und Hausnummer aus der Adresse der Adressaten-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Adressat', NULL, '0', '13', '0', '0');

-- Attribut aktenzeichen des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'aktenzeichen', 'aktenzeichen', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '0', '255', NULL, '', 'Text', '', '<strong>Aktenzeichen  WrZ</strong>', '', '', '', '', 'PFLICHTFELD! Aktenzeichen der WrZ ohne unnötige Leerzeichen. [Unnötig sind dabei alle Leerzeichen in Verbindung mit anderen Trennzeichen wie', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '0', '28', '1', '0');

-- Attribut aktuell des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'aktuell', 'aktuell', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'bool', '', '', '1', NULL, NULL, '', 'Auswahlfeld', 'select \'aktuell\' as output, true as value
union
select \'historisch\' as output, false as value', 'Aktualität', '', '', '', '', 'Gibt an ob die Wasserrechtliche Zulassung aktuell ist. Sie gilt dabei so lange als \'aktuell\' solange sie nicht aufgehoben, widerrufen etc. ist. Keine Wasserechtliche Zulassung darf sowohl \'aktuell\' wie auch \'historisch\' sein.', 'Status der Wasserrechtliche Zulassung', NULL, '0', '42', '1', '0');

-- Attribut anlage_anzeige des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'anlage_anzeige', 'name', 'fiswrv_anlagen', 'b', 'varchar', '', '', '0', '255', NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=2&value_anlage_id=$anlage_id&operator_anlage_id==;Name: $anlage_anzeige;no_new_window', 'Anlage', '', '', '', '', '', 'Wasserrechtlich relevante Anlage', NULL, '0', '2', '1', '0');

-- Attribut anlage_id des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'anlage_id', 'anlage', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '0', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_anlagen', '<strong>Name ANL</strong>', '', '', '', '', 'Name aus der Anlagentabelle.', 'Wasserrechtlich relevante Anlage', NULL, '0', '1', '1', '0');

-- Attribut anlage_klasse des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'anlage_klasse', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Klasse ANL', '', '', '', '', 'Klasse aus der Anlagentabelle.', 'Wasserrechtlich relevante Anlage', NULL, '0', '0', '0', '0');

-- Attribut ausstellbehoerde des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'ausstellbehoerde', 'ausstellbehoerde', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_behoerde;layer_id=17 embedded', 'Ausstellbehörde [Auswahlfeld]', '', '', '', '', 'Die Behörde, die die Wasserrechtliche Zulassung erteilt hat. [Neue Behörden müssen beim Admin beantragt werden.]', '<h1>Abschrift der Wasserrechtlichen Zulassung</h1>', NULL, '0', '5', '1', '0');

-- Attribut ausstellbehoerde_link des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'ausstellbehoerde_link', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=17&value_id=$ausstellbehoerde&operator_id==;Ausstellbehörde anzeigen', 'Ausstellbehörde [Link]', '', '', '', '', 'Link auf die ausgewählte Behörde, die die Wasserrechtliche Zulassung erteilt hat. ', '<h1>Abschrift der Wasserrechtlichen Zulassung</h1>', NULL, '0', '6', '0', '0');

-- Attribut bearbeiter des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'bearbeiter', 'bearbeiter', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
	z.id as value, z.name || \' (\' || coalesce(string_agg(b.name, \', \'),  \'keiner Behörde zugeordnet\') || \')\' AS output FROM
  fiswrv_personen z LEFT JOIN
  fiswrv_behoerde b ON z.behoerde = b.id WHERE NOT (behoerde IS NULL)
GROUP BY
  z.id, z.name ;layer_id=9 embedded', 'Bearbeiter [Auswahlfeld]', '', '', '', '', 'Das Feld dient der Auswahl der Bearbeiter-Person der Wasserrechtlichen Zulassung. % und _ entsprechen * und ? als Platzhalter. Nicht vorhandene Bearbeiter-Personen müssen neu angelegt werden.', 'Bearbeiter', NULL, '0', '15', '1', '0');

-- Attribut bearbeiterzeichen des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'bearbeiterzeichen', 'bearbeiterzeichen', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Bearbeiterzeichen WrZ', '', '', '', '', 'Bearbeiterzeichen der Wasserrechtlichen Zulassung ohne unnötige Lesezeichen. [Bearbeiterzeichen werden vor die Aktenzeichen geschrieben und sollen zu besseren Identifizierbarkeit der WrZ getrennt vom Aktenzeichen gespeichert werden. Unnötig sind dabei all', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '0', '27', '1', '0');

-- Attribut bearbeiter_email des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'bearbeiter_email', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Email', '', '', '', '', 'Email der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '0', '24', '0', '0');

-- Attribut bearbeiter_fax des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'bearbeiter_fax', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Fax', '', '', '', '', 'Fax der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '0', '23', '0', '0');

-- Attribut bearbeiter_link des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'bearbeiter_link', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=9&value_personen_id=$bearbeiter&operator_personen_id==;Bearbeiter anzeigen', 'Bearbeiter [Link]', '', '', '', '', 'Link auf die ausgewählte Bearbeiter-Person im FisWrV-Per (Personenmodul) der Wasserrechtlichen Zulassung.', 'Bearbeiter', NULL, '0', '16', '0', '0');

-- Attribut bearbeiter_name des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'bearbeiter_name', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Name', '', '', '', '', 'Name der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '0', '17', '0', '0');

-- Attribut bearbeiter_namenszusatz des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'bearbeiter_namenszusatz', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Namenszusatz', '', '', '', '', 'Namenszusatz der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '0', '18', '0', '0');

-- Attribut bearbeiter_plz_ort des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'bearbeiter_plz_ort', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, PLZ und Ort', '', '', '', '', 'Postleitzahl und Ort aus der Adresse der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '0', '20', '0', '0');

-- Attribut bearbeiter_strasse_hausnummer des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'bearbeiter_strasse_hausnummer', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Straße und Hausnummer', '', '', '', '', 'Straße und Hausnummer aus der Adresse der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '0', '19', '0', '0');

-- Attribut bearbeiter_telefon des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'bearbeiter_telefon', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Telefon', '', '', '', '', 'Telefon der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '0', '22', '0', '0');

-- Attribut bearbeiter_zimmer des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'bearbeiter_zimmer', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Zimmer', '', '', '', '', 'Zimmer der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '0', '21', '0', '0');

-- Attribut bearbeitungs_datum des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'bearbeitungs_datum', 'bearbeitungs_datum', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Time', '', 'Datum und Uhrzeit', '', '', '', '', '', 'letzte Änderung', NULL, '0', '57', '0', '0');

-- Attribut befristet_bis des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'befristet_bis', 'befristet_bis', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Text', '', 'Befristet bis', '', '', '', '', 'Datum bis zu dem die Wrz gültig ist. [Ist das Feld leer, wird angenommen, dass die WrZ unbegrenzt gültig ist.]', 'Materielle Bestandskraft', NULL, '0', '40', '1', '0');

-- Attribut bergamt_aktenzeichen des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'bergamt_aktenzeichen', 'bergamt_aktenzeichen', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Bergamt Aktenzeichen', '', '', '', '', 'Alternatives Aktenzeichen des Bergamtes. Erscheint nur auf den dem LUNG übersendeten Tabellen.', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '0', '30', '1', '0');

-- Attribut bezeichnung des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'bezeichnung', 'bezeichnung', 'fiswrv_wasserrechtliche_zulassungen_bezeichnung', 'c', 'text', '', '', '1', NULL, NULL, '', 'Text', '', 'Bezeichnung Wrz', '', '', '', '', 'Standartisierte eindeutige Bezeichnung der Wasserrechtlichen Zulassung zur Identifikation in anderen Tabellen', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '0', '25', '0', '0');

-- Attribut datum des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'datum', 'datum', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '0', NULL, NULL, '', 'Text', '', '<strong>Datum WrZ</strong>', '', '', '', '', 'PFLICHTFELD! Datum auf das die WrZ datiert wurde.', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '0', '32', '1', '0');

-- Attribut datum_bestand_form des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'datum_bestand_form', 'datum_bestand_form', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Text', 'SELECT case when \'$fassung_datum\' != \'\' then ((nullif(\'$fassung_datum\', \'\')::date +  integer \'4\' + interval \'1 month\')::date)::text else case when \'$datum\' != \'\' then ((nullif(\'$datum\', \'\')::date + integer \'4\' + interval \'1 month\')::date)::text else \'Bitte zuerst ein Datum angeben!\' end end', 'Unanfechtbar', '', '', '', '', 'Tag an dem der Bescheid als unanfechtbar gilt und formell bestandskräftig ist (Dreitagesfiktion §41 Satz 2 VwVfG + 1 Monat)', 'Status der Wasserrechtliche Zulassung', NULL, '0', '48', '1', '0');

-- Attribut datum_bestand_mat des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'datum_bestand_mat', 'datum_bestand_mat', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Text', 'SELECT case when \'$fassung_datum\' != \'\' then (nullif(\'$fassung_datum\', \'\')::date + 4)::text else case when \'$datum\' != \'\' then (nullif(\'$datum\', \'\')::date + 4)::text else \'Bitte zuerst ein Datum angeben!\' end end', 'Bekanntgegeben', '', '', '', '', 'Tag an dem der Bescheid als bekanntgeben gilt und materiell bestandskräftig ist (Dreitagesfiktion §41 Satz 2 VwVfG)', 'Status der Wasserrechtliche Zulassung', NULL, '0', '47', '1', '0');

-- Attribut datum_postausgang des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'datum_postausgang', 'datum_postausgang', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Text', 'SELECT case when \'$fassung_datum\' != \'\' then (nullif(\'$fassung_datum\', \'\')::date + 1)::text else case when \'$datum\' != \'\' then (nullif(\'$datum\', \'\')::date + 1)::text else \'Bitte zuerst ein Datum angeben!\' end end', 'Postausgang', '', '', '', '', '', 'Status der Wasserrechtliche Zulassung', NULL, '0', '46', '1', '0');

-- Attribut dokument des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'dokument', 'dokument', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Dokument', '', 'Dokument', '', '', '', '', 'Eingescanntes Dokument der Wasserrechtluchen Zulassung', 'Dokument', NULL, '0', '49', '1', '0');

-- Attribut fassung_aktenzeichen des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'fassung_aktenzeichen', 'fassung_aktenzeichen', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Aktenzeichen oder Registriernummer der Änderung', '', '', '', '', 'Abweichendes  Aktenzeichen eines Änderungsbescheides (o. ä.) zu einer Wasserrechtlichen Zulassung. [Unnötig sind dabei alle Leerzeichen in Verbindung mit anderen Trennzeichen wie', 'Fassung', NULL, '0', '37', '1', '0');

-- Attribut fassung_auswahl des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'fassung_auswahl', 'fassung_auswahl', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_wasserrechtliche_zulassungen_fassung_auswahl;layer_id=31 embedded', 'Fassung WrZ', '', '', '', '', 'Das Feld dient  der korrekten Bezeichnung der eventuellen Fassung einer Wasserrechtlichen Zulassung.', 'Fassung', NULL, '0', '33', '1', '0');

-- Attribut fassung_bearbeiterzeichen des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'fassung_bearbeiterzeichen', 'fassung_bearbeiterzeichen', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Bearbeiterzeichen der Änderung (LUNG, StALU und StAUN)', '', '', '', '', 'Abweichendes Bearbeiterzeichen eines Änderungsbescheides (o. ä.) zu einer Wasserrechtlichen Zulassung ohne unnötige Leerzeichen. [Bearbeiterzeichen werden vom LUNG und den StÄLU (historisch auch StÄUN) vor die Aktenzeichen geschrieben und sollen zu besser', 'Fassung', NULL, '0', '36', '1', '0');

-- Attribut fassung_datum des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'fassung_datum', 'fassung_datum', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Text', '', 'Datum Fassung WrZ', '', '', '', '', 'Datum auf das die Fassung der WrZ datiert wurde.', 'Fassung', NULL, '0', '38', '1', '0');

-- Attribut fassung_nummer des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'fassung_nummer', 'fassung_nummer', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Text', '', 'Nummer Fassung WrZ', '', '', '', '', 'Das Feld dient  der korrekten Nummerierung der eventuellen Fassung einer Wasserrechtlichen Zulassung.', 'Fassung', NULL, '0', '34', '1', '0');

-- Attribut fassung_typus des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'fassung_typus', 'fassung_typus', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_wasserrechtliche_zulassungen_fassung_typus;layer_id=48 embedded', 'Typus Fassung WrZ', '', '', '', '', 'Typus der eventuellen Fassung einer Wasserrechtlichen Zulassung (z.B. Änderungsbescheid, Anpassungsbescheid, etc.) [Neue Typen müssen über den Admin beantragt werden]', 'Fassung', NULL, '0', '35', '1', '0');

-- Attribut freigegeben des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'freigegeben', 'freigegeben', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'bool', '', '', '1', NULL, NULL, 'SELECT false', 'Auswahlfeld', 'select \'ja\' as output, true as value
union
select \'nein\' as output, false as value', 'Freigegeben', '', '', '', '', 'WrZ freigegeben?', 'Historienverwaltung', NULL, '0', '52', '1', '0');

-- Attribut gueltig_seit des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'gueltig_seit', 'gueltig_seit', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Text', '', 'Gültig seit', '', '', '', '', 'Datum seit dem die Wrz gültig ist. [Ist das Feld leer, wird angenommen, dass die WrZ ab dem Ausstellungsdatum gültig ist.]', 'Materielle Bestandskraft', NULL, '0', '39', '1', '0');

-- Attribut nachfolger des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'nachfolger', 'nachfolger', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT a.id as value, a.bezeichnung AS output FROM fiswrv_wasserrechtliche_zulassungen_bezeichnung a WHERE a.anlage = <requires>anlage_id</requires> AND a.id !=<requires>wrz_id</requires> ;', 'Nachfolger', '', '', '', '', 'Nachfolge WrZ', 'Historienverwaltung', NULL, '0', '50', '1', '0');

-- Attribut ort des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'ort', 'ort', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Autovervollständigungsfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_ort;layer_id=30 embedded anywhere', '<strong>Ort</strong>', '', '', '', '', 'Ort an dem der Bescheid ausgestellt wurde.', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '0', '31', '1', '0');

-- Attribut personen_id des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'personen_id', 'adressat', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Autovervollständigungsfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_personen a WHERE a.wrzadressat IS NOT NULL AND a.wrzadressat = \'ja\';layer_id=9 embedded', 'Adressat [Auswahlfeld]', '', '', '', '', '', 'Adressat', NULL, '0', '9', '1', '0');

-- Attribut regnummer des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'regnummer', 'regnummer', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Registriernummer WrZ', '', '', '', '', 'Registriernummer der WrZ ohne unnötige Leerzeichen. [Unnötig sind dabei alle Leerzeichen in Verbindung mit anderen Trennzeichen wie', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '0', '29', '1', '0');

-- Attribut status des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'status', 'status', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_wasserrechtliche_zulassungen_status;layer_id=20 embedded', 'Status des Dokuments', '', '', '', '', 'Gibt an ob es sich bei dem Datensatz um eine geprüft Abschrift einer Wasserrechtlichen Zulassung (geringste Fehlerrate), eine Übertragung aus einer geprüften LUNG-Datenbank (mittlere Fehlerrate) oder um Erstbefüllungsdaten (höchste Fehlerrate) handelt.', 'Status der Wasserrechtliche Zulassung', NULL, '0', '41', '1', '0');

-- Attribut stelle_id des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'stelle_id', 'stelle_id', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '10', NULL, '', 'StelleID', '', 'Stelle ID', '', '', '', '', '', 'letzte Änderung', NULL, '0', '56', '0', '0');

-- Attribut stelle_name des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'stelle_name', 'stelle_name', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Stelle', '', 'Stelle', '', '', '', '', 'Stelle des Benutzers, der als letztes eine Änderung gespeichert hat.', 'letzte Änderung', NULL, '0', '55', '0', '0');

-- Attribut typus des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'typus', 'typus', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '0', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_wasserrechtliche_zulassungen_typus;layer_id=26 embedded', '<strong>Typus Wrz</strong>', '', '', '', '', 'PFLICHTFELD! Typus der Wasserrechtlichen Zulassung (z.B. Wasserrechtliche Erlaubnis, Wasserrechtliche Nutzungsgenehmigung, etc.) [Neue Typen müssen über den Admin beantragt werden]', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '0', '26', '1', '0');

-- Attribut ungueltig_aufgrund des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'ungueltig_aufgrund', 'ungueltig_aufgrund', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund;layer_id=32 embedded', 'Ungültig aufgrund', '', '', '', '', 'Grund warum die WrZ ungültig ist.', 'Status der Wasserrechtliche Zulassung', NULL, '0', '45', '1', '0');

-- Attribut ungueltig_seit des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'ungueltig_seit', 'ungueltig_seit', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Text', '', 'Ungültig seit', '', '', '', '', 'Datum seit dem die WrZ ungültig ist. [Ist das Feld leer, wird angenommen, dass die WrZ gültig ist.]', 'Status der Wasserrechtliche Zulassung', NULL, '0', '44', '1', '0');

-- Attribut vorgaenger des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'vorgaenger', 'vorgaenger', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT a.id as value, a.bezeichnung AS output FROM fiswrv_wasserrechtliche_zulassungen_bezeichnung a WHERE a.anlage = <requires>anlage_id</requires> AND a.id !=<requires>wrz_id</requires> ;', 'Vorgänger', '', '', '', '', 'Vorgänger WrZ', 'Historienverwaltung', NULL, '0', '51', '1', '0');

-- Attribut wirksam des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'wirksam', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Wirksam', '', '', '', '', '', 'Status der Wasserrechtliche Zulassung', NULL, '0', '43', '0', '0');

-- Attribut wrz_bearbeiter_id des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'wrz_bearbeiter_id', 'bearbeiter_id', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '10', NULL, '', 'UserID', '', 'WrZ Bearbeiter ID', '', '', '', '', '', 'letzte Änderung', NULL, '0', '54', '0', '0');

-- Attribut wrz_bearbeiter_name des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'wrz_bearbeiter_name', 'bearbeiter_name', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'User', '', 'WrZ Bearbeiter Name', '', '', '', '', 'Name des Benutzers, der als letztes eine Änderung gespeichert hat.', 'letzte Änderung', NULL, '0', '53', '0', '0');

-- Attribut wrz_ben des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'wrz_ben', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '33,wrz_id,bezeichnung;no_new_window', 'Zugelassene Benutzungen', '', '', '', '', '', '<h3>Zugelassene Benutzungen</h3>', NULL, '0', '58', '0', '0');

-- Attribut wrz_id des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'wrz_id', 'id', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', 'Primärschlüssel WrZ', '', '', '', '', 'Primärschlüssel der Wasserrechtlichen Zulassungen.', 'Systemfeld', NULL, '0', '3', '0', '0');

-- Attribut wrz_wid des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'wrz_wid', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Metaschlüssel WrZ', '', '', '', '', 'Zusammengesetzter Schlüssel aus dem Primärschlüssel der wasserrechtlich relevanten Anlagen und dem Primärschlüssel der Wasserrechtlichen Zulassungen, getrennt durch ein Minuszeichen.', 'Systemfeld', NULL, '0', '4', '0', '0');

-- Attribut zustaendige_behoerde des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'zustaendige_behoerde', 'zustaendige_behoerde', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_behoerde WHERE aktuell=true;layer_id=17 embedded', 'Zutändige Behörde WrZ [Auswahlfeld]', '', '', '', '', 'Die für die Wasserrechtliche Zulassung zuständige Behörde. [Neue Behörden müssen beim Admin beantragt werden.]', '<h1>Abschrift der Wasserrechtlichen Zulassung</h1>', NULL, '0', '7', '1', '0');

-- Attribut zustaendige_behoerde_link des Layers 25
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id25, 'zustaendige_behoerde_link', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=17&value_id=$zustaendige_behoerde&operator_id==;Zuständige Behörde anzeigen', 'Zutändige Behörde WrZ [Link]', '', '', '', '', 'Link auf die für die Wasserrechtliche Zulassung zuständige Behörde.', '<h1>Abschrift der Wasserrechtlichen Zulassung</h1>', NULL, '0', '8', '0', '0');

-- Zuordnung der Layerattribute des Layers 25 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'adressat_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'adressat_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'adressat_namenszusatz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'adressat_plz_ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'adressat_strasse_hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'aktenzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'aktuell', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'anlage_anzeige', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'anlage_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'anlage_klasse', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'ausstellbehoerde', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'ausstellbehoerde_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'bearbeiter', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'bearbeiterzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'bearbeiter_email', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'bearbeiter_fax', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'bearbeiter_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'bearbeiter_namenszusatz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'bearbeiter_plz_ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'bearbeiter_strasse_hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'bearbeiter_telefon', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'bearbeiter_zimmer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'bearbeitungs_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'befristet_bis', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'bergamt_aktenzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'bezeichnung', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'datum_bestand_form', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'datum_bestand_mat', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'datum_postausgang', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'dokument', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'fassung_aktenzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'fassung_auswahl', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'fassung_bearbeiterzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'fassung_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'fassung_nummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'fassung_typus', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'freigegeben', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'gueltig_seit', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'nachfolger', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'personen_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'regnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'status', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'stelle_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'stelle_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'typus', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'ungueltig_aufgrund', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'ungueltig_seit', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'vorgaenger', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'wirksam', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'wrz_bearbeiter_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'wrz_bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'wrz_ben', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'wrz_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'wrz_wid', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'zustaendige_behoerde', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_2, 'zustaendige_behoerde_link', '0', '0');

-- Zuordnung der Layerattribute des Layers 25 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'adressat_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'adressat_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'adressat_namenszusatz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'adressat_plz_ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'adressat_strasse_hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'aktenzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'aktuell', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'anlage_anzeige', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'anlage_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'anlage_klasse', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'ausstellbehoerde', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'ausstellbehoerde_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'bearbeiter', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'bearbeiterzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'bearbeiter_email', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'bearbeiter_fax', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'bearbeiter_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'bearbeiter_namenszusatz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'bearbeiter_plz_ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'bearbeiter_strasse_hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'bearbeiter_telefon', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'bearbeiter_zimmer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'bearbeitungs_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'befristet_bis', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'bergamt_aktenzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'bezeichnung', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'datum_bestand_form', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'datum_bestand_mat', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'datum_postausgang', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'dokument', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'fassung_aktenzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'fassung_auswahl', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'fassung_bearbeiterzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'fassung_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'fassung_nummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'fassung_typus', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'freigegeben', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'gueltig_seit', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'nachfolger', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'personen_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'regnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'status', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'stelle_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'stelle_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'typus', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'ungueltig_aufgrund', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'ungueltig_seit', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'vorgaenger', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'wirksam', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'wrz_bearbeiter_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'wrz_bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'wrz_ben', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'wrz_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'wrz_wid', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'zustaendige_behoerde', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_4, 'zustaendige_behoerde_link', '0', '0');

-- Zuordnung der Layerattribute des Layers 25 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'adressat_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'adressat_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'adressat_namenszusatz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'adressat_plz_ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'adressat_strasse_hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'aktenzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'aktuell', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'anlage_anzeige', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'anlage_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'anlage_klasse', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'ausstellbehoerde', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'ausstellbehoerde_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'bearbeiter', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'bearbeiterzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'bearbeiter_email', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'bearbeiter_fax', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'bearbeiter_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'bearbeiter_namenszusatz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'bearbeiter_plz_ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'bearbeiter_strasse_hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'bearbeiter_telefon', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'bearbeiter_zimmer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'bearbeitungs_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'befristet_bis', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'bergamt_aktenzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'bezeichnung', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'datum', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'datum_bestand_form', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'datum_bestand_mat', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'datum_postausgang', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'dokument', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'fassung_aktenzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'fassung_auswahl', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'fassung_bearbeiterzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'fassung_datum', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'fassung_nummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'fassung_typus', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'freigegeben', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'gueltig_seit', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'nachfolger', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'ort', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'personen_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'regnummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'status', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'stelle_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'stelle_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'typus', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'ungueltig_aufgrund', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'ungueltig_seit', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'vorgaenger', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'wirksam', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'wrz_bearbeiter_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'wrz_bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'wrz_ben', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'wrz_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'wrz_wid', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'zustaendige_behoerde', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id25, @stelle_id_5, 'zustaendige_behoerde_link', '0', '0');

-- Layer 52
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('FisWrV-WRe WrZ [nicht freigegeben]', '', '5', @group_id, 'SELECT COALESCE(d.name,\'\') AS anlage_klasse, a.anlage AS anlage_id, b.name AS anlage_anzeige, a.id as wrz_id, COALESCE(b.id::text,\'\') ||\' -\'|| COALESCE(a.id::text,\'\') AS wrz_wid, a.ausstellbehoerde, \'\' AS ausstellbehoerde_link, a.zustaendige_behoerde,  \'\' AS zustaendige_behoerde_link, a.adressat AS personen_id, \'\' AS adressat_link, COALESCE(e.name,\'\') AS adressat_name, COALESCE(e.namenszusatz,\'\') AS adressat_namenszusatz, COALESCE(f.strasse,\'\') ||\'  \'|| COALESCE(f.hausnummer,\'\') AS adressat_strasse_hausnummer, COALESCE(f.plz::text,\'\') ||\'  \'|| COALESCE(f.ort,\'\') AS adressat_plz_ort, a.bearbeiter, \'\' AS bearbeiter_link, COALESCE(g.name,\'\') AS bearbeiter_name, COALESCE(g.namenszusatz,\'\') AS bearbeiter_namenszusatz, COALESCE(h.strasse,\'\') ||\'  \'|| COALESCE(h.hausnummer,\'\') AS bearbeiter_strasse_hausnummer, COALESCE(h.plz::text,\'\') ||\'  \'|| COALESCE(h.ort,\'\') AS bearbeiter_plz_ort, COALESCE(g.zimmer,\'\') AS bearbeiter_zimmer, COALESCE(g.telefon,\'\') AS bearbeiter_telefon, COALESCE(g.fax,\'\') AS bearbeiter_fax, COALESCE(g.email,\'\') AS bearbeiter_email,  c.bezeichnung, a.typus, a.bearbeiterzeichen, a.aktenzeichen, a.regnummer, a.bergamt_aktenzeichen, a.ort,  a.datum, a.fassung_auswahl, a.fassung_nummer, a.fassung_typus, a.fassung_bearbeiterzeichen, a.fassung_aktenzeichen, a.fassung_datum, a.gueltig_seit, a.befristet_bis, a.status, a.aktuell, CASE WHEN a.ungueltig_seit <= current_date THEN \'nein\' ELSE CASE WHEN a.befristet_bis < current_date THEN \'nein\' ELSE \'ja\' END END AS wirksam, a.ungueltig_seit, a.ungueltig_aufgrund, a.datum_postausgang,a.datum_bestand_mat, a.datum_bestand_form, a.dokument AS dokument, a.nachfolger AS nachfolger, a.vorgaenger AS vorgaenger, a.freigegeben, a.bearbeiter_name AS wrz_bearbeiter_name, a.bearbeiter_id AS wrz_bearbeiter_id, a.stelle_name, a.stelle_id, a.bearbeitungs_datum, \'\' AS wrz_ben  FROM fiswrv_wasserrechtliche_zulassungen a LEFT JOIN fiswrv_wasserrechtliche_zulassungen_bezeichnung c ON a.id = c.id LEFT JOIN fiswrv_anlagen b ON a.anlage=b.id LEFT JOIN fiswrv_anlagen_klasse d ON b.klasse=d.id LEFT JOIN fiswrv_personen e ON a.adressat=e.id LEFT JOIN fiswrv_adresse f ON e.adresse=f.id LEFT JOIN fiswrv_personen g ON a.bearbeiter=g.id LEFT JOIN fiswrv_adresse h ON g.adresse=h.id WHERE a.freigegeben = false', 'fiswrv_wasserrechtliche_zulassungen', '', 'wasserrecht', '/var/www/data/wasserrecht/wrz/', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '2', '', '0');
SET @last_layer_id52=LAST_INSERT_ID();

-- Zuordnung Layer 52 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id52, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 52 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id52, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 52 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id52, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Attribut adressat_link des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'adressat_link', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=9&value_personen_id=$personen_id&operator_personen_id==;Adressaten anzeigen', 'Adressat [Link]', '', '', '', '', '', 'Adressat', NULL, '0', '10', '0', '0');

-- Attribut adressat_name des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'adressat_name', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Adressat, Name', '', '', '', '', 'Name der Adressaten-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Adressat', NULL, '0', '11', '0', '0');

-- Attribut adressat_namenszusatz des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'adressat_namenszusatz', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Adressat, Namenszusatz', '', '', '', '', 'Namenszusatz der Adressaten-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Adressat', NULL, '0', '12', '0', '0');

-- Attribut adressat_plz_ort des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'adressat_plz_ort', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Adressat, PLZ und Ort', '', '', '', '', 'Postleitzahl und Ort aus der Adresse der Adressaten-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Adressat', NULL, '0', '14', '0', '0');

-- Attribut adressat_strasse_hausnummer des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'adressat_strasse_hausnummer', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Adressat, Straße und Hausnummer', '', '', '', '', 'Straße und Hausnummer aus der Adresse der Adressaten-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Adressat', NULL, '0', '13', '0', '0');

-- Attribut aktenzeichen des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'aktenzeichen', 'aktenzeichen', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '0', '255', NULL, '', 'Text', '', '<strong>Aktenzeichen  WrZ</strong>', '', '', '', '', 'PFLICHTFELD! Aktenzeichen der WrZ ohne unnötige Leerzeichen. [Unnötig sind dabei alle Leerzeichen in Verbindung mit anderen Trennzeichen wie', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '0', '28', '1', '0');

-- Attribut aktuell des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'aktuell', 'aktuell', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'bool', '', '', '1', NULL, NULL, '', 'Auswahlfeld', 'select \'aktuell\' as output, true as value
union
select \'historisch\' as output, false as value', 'Aktualität', '', '', '', '', 'Gibt an ob die Wasserrechtliche Zulassung aktuell ist. Sie gilt dabei so lange als \'aktuell\' solange sie nicht aufgehoben, widerrufen etc. ist. Keine Wasserechtliche Zulassung darf sowohl \'aktuell\' wie auch \'historisch\' sein.', 'Status der Wasserrechtliche Zulassung', NULL, '0', '42', '1', '0');

-- Attribut anlage_anzeige des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'anlage_anzeige', 'name', 'fiswrv_anlagen', 'b', 'varchar', '', '', '0', '255', NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=2&value_anlage_id=$anlage_id&operator_anlage_id==;Name: $anlage_anzeige;no_new_window', 'Anlage', '', '', '', '', '', 'Wasserrechtlich relevante Anlage', NULL, '0', '2', '1', '0');

-- Attribut anlage_id des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'anlage_id', 'anlage', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '0', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_anlagen', '<strong>Name ANL</strong>', '', '', '', '', 'Name aus der Anlagentabelle.', 'Wasserrechtlich relevante Anlage', NULL, '0', '1', '1', '0');

-- Attribut anlage_klasse des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'anlage_klasse', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Klasse ANL', '', '', '', '', 'Klasse aus der Anlagentabelle.', 'Wasserrechtlich relevante Anlage', NULL, '0', '0', '0', '0');

-- Attribut ausstellbehoerde des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'ausstellbehoerde', 'ausstellbehoerde', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_behoerde;layer_id=17 embedded', 'Ausstellbehörde [Auswahlfeld]', '', '', '', '', 'Die Behörde, die die Wasserrechtliche Zulassung erteilt hat. [Neue Behörden müssen beim Admin beantragt werden.]', '<h1>Abschrift der Wasserrechtlichen Zulassung</h1>', NULL, '0', '5', '1', '0');

-- Attribut ausstellbehoerde_link des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'ausstellbehoerde_link', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=17&value_id=$ausstellbehoerde&operator_id==;Ausstellbehörde anzeigen', 'Ausstellbehörde [Link]', '', '', '', '', 'Link auf die ausgewählte Behörde, die die Wasserrechtliche Zulassung erteilt hat. ', '<h1>Abschrift der Wasserrechtlichen Zulassung</h1>', NULL, '0', '6', '0', '0');

-- Attribut bearbeiter des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'bearbeiter', 'bearbeiter', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
	z.id as value, z.name || \' (\' || coalesce(string_agg(b.name, \', \'),  \'keiner Behörde zugeordnet\') || \')\' AS output FROM
  fiswrv_personen z LEFT JOIN
  fiswrv_behoerde b ON z.behoerde = b.id WHERE NOT (behoerde IS NULL)
GROUP BY
  z.id, z.name ;layer_id=9 embedded', 'Bearbeiter [Auswahlfeld]', '', '', '', '', 'Das Feld dient der Auswahl der Bearbeiter-Person der Wasserrechtlichen Zulassung. % und _ entsprechen * und ? als Platzhalter. Nicht vorhandene Bearbeiter-Personen müssen neu angelegt werden.', 'Bearbeiter', NULL, '0', '15', '1', '0');

-- Attribut bearbeiterzeichen des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'bearbeiterzeichen', 'bearbeiterzeichen', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Bearbeiterzeichen WrZ', '', '', '', '', 'Bearbeiterzeichen der Wasserrechtlichen Zulassung ohne unnötige Lesezeichen. [Bearbeiterzeichen werden vor die Aktenzeichen geschrieben und sollen zu besseren Identifizierbarkeit der WrZ getrennt vom Aktenzeichen gespeichert werden. Unnötig sind dabei all', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '0', '27', '1', '0');

-- Attribut bearbeiter_email des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'bearbeiter_email', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Email', '', '', '', '', 'Email der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '0', '24', '0', '0');

-- Attribut bearbeiter_fax des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'bearbeiter_fax', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Fax', '', '', '', '', 'Fax der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '0', '23', '0', '0');

-- Attribut bearbeiter_link des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'bearbeiter_link', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=9&value_personen_id=$bearbeiter&operator_personen_id==;Bearbeiter anzeigen', 'Bearbeiter [Link]', '', '', '', '', 'Link auf die ausgewählte Bearbeiter-Person im FisWrV-Per (Personenmodul) der Wasserrechtlichen Zulassung.', 'Bearbeiter', NULL, '0', '16', '0', '0');

-- Attribut bearbeiter_name des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'bearbeiter_name', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Name', '', '', '', '', 'Name der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '0', '17', '0', '0');

-- Attribut bearbeiter_namenszusatz des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'bearbeiter_namenszusatz', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Namenszusatz', '', '', '', '', 'Namenszusatz der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '0', '18', '0', '0');

-- Attribut bearbeiter_plz_ort des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'bearbeiter_plz_ort', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, PLZ und Ort', '', '', '', '', 'Postleitzahl und Ort aus der Adresse der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '0', '20', '0', '0');

-- Attribut bearbeiter_strasse_hausnummer des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'bearbeiter_strasse_hausnummer', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Straße und Hausnummer', '', '', '', '', 'Straße und Hausnummer aus der Adresse der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '0', '19', '0', '0');

-- Attribut bearbeiter_telefon des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'bearbeiter_telefon', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Telefon', '', '', '', '', 'Telefon der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '0', '22', '0', '0');

-- Attribut bearbeiter_zimmer des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'bearbeiter_zimmer', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Bearbeiter, Zimmer', '', '', '', '', 'Zimmer der Bearbeiter-Person der Wasserrechtlichen Zulassung. [Wird automatisch aus dem FisWrV-Per (Personenmodul) ergänzt.]', 'Bearbeiter', NULL, '0', '21', '0', '0');

-- Attribut bearbeitungs_datum des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'bearbeitungs_datum', 'bearbeitungs_datum', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Time', '', 'Datum und Uhrzeit', '', '', '', '', '', 'letzte Änderung', NULL, '0', '57', '0', '0');

-- Attribut befristet_bis des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'befristet_bis', 'befristet_bis', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Text', '', 'Befristet bis', '', '', '', '', 'Datum bis zu dem die Wrz gültig ist. [Ist das Feld leer, wird angenommen, dass die WrZ unbegrenzt gültig ist.]', 'Materielle Bestandskraft', NULL, '0', '40', '1', '0');

-- Attribut bergamt_aktenzeichen des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'bergamt_aktenzeichen', 'bergamt_aktenzeichen', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Bergamt Aktenzeichen', '', '', '', '', 'Registriernummer der WrZ ohne unnötige Leerzeichen. [Unnötig sind dabei alle Leerzeichen in Verbindung mit anderen Trennzeichen wie', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '0', '30', '1', '0');

-- Attribut bezeichnung des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'bezeichnung', 'bezeichnung', 'fiswrv_wasserrechtliche_zulassungen_bezeichnung', 'c', 'text', '', '', '1', NULL, NULL, '', 'Text', '', 'Bezeichnung Wrz', '', '', '', '', 'Standartisierte eindeutige Bezeichnung der Wasserrechtlichen Zulassung zur Identifikation in anderen Tabellen', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '0', '25', '0', '0');

-- Attribut datum des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'datum', 'datum', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '0', NULL, NULL, '', 'Text', '', '<strong>Datum WrZ</strong>', '', '', '', '', 'PFLICHTFELD! Datum auf das die WrZ datiert wurde.', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '0', '32', '1', '0');

-- Attribut datum_bestand_form des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'datum_bestand_form', 'datum_bestand_form', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Text', 'SELECT case when \'$fassung_datum\' != \'\' then ((nullif(\'$fassung_datum\', \'\')::date +  integer \'4\' + interval \'1 month\')::date)::text else case when \'$datum\' != \'\' then ((nullif(\'$datum\', \'\')::date + integer \'4\' + interval \'1 month\')::date)::text else \'Bitte zuerst ein Datum angeben!\' end end', 'Unanfechtbar', '', '', '', '', 'Tag an dem der Bescheid als unanfechtbar gilt und formell bestandskräftig ist (Dreitagesfiktion §41 Satz 2 VwVfG + 1 Monat)', 'Status der Wasserrechtliche Zulassung', NULL, '0', '48', '1', '0');

-- Attribut datum_bestand_mat des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'datum_bestand_mat', 'datum_bestand_mat', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Text', 'SELECT case when \'$fassung_datum\' != \'\' then (nullif(\'$fassung_datum\', \'\')::date + 4)::text else case when \'$datum\' != \'\' then (nullif(\'$datum\', \'\')::date + 4)::text else \'Bitte zuerst ein Datum angeben!\' end end', 'Bekanntgegeben', '', '', '', '', 'Tag an dem der Bescheid als bekanntgeben gilt und materiell bestandskräftig ist (Dreitagesfiktion §41 Satz 2 VwVfG)', 'Status der Wasserrechtliche Zulassung', NULL, '0', '47', '1', '0');

-- Attribut datum_postausgang des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'datum_postausgang', 'datum_postausgang', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Text', 'SELECT case when \'$fassung_datum\' != \'\' then (nullif(\'$fassung_datum\', \'\')::date + 1)::text else case when \'$datum\' != \'\' then (nullif(\'$datum\', \'\')::date + 1)::text else \'Bitte zuerst ein Datum angeben!\' end end', 'Postausgang', '', '', '', '', '', 'Status der Wasserrechtliche Zulassung', NULL, '0', '46', '1', '0');

-- Attribut dokument des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'dokument', 'dokument', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Dokument', '', 'Dokument', '', '', '', '', 'Eingescanntes Dokument der Wasserrechtluchen Zulassung', 'Dokument', NULL, '0', '49', '1', '0');

-- Attribut fassung_aktenzeichen des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'fassung_aktenzeichen', 'fassung_aktenzeichen', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Aktenzeichen oder Registriernummer der Änderung', '', '', '', '', 'Abweichendes  Aktenzeichen eines Änderungsbescheides (o. ä.) zu einer Wasserrechtlichen Zulassung. [Unnötig sind dabei alle Leerzeichen in Verbindung mit anderen Trennzeichen wie', 'Fassung', NULL, '0', '37', '1', '0');

-- Attribut fassung_auswahl des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'fassung_auswahl', 'fassung_auswahl', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Text', '', 'Fassung WrZ', '', '', '', '', 'Das Feld dient  der korrekten Bezeichnung der eventuellen Fassung einer Wasserrechtlichen Zulassung.', 'Fassung', NULL, '0', '33', '1', '0');

-- Attribut fassung_bearbeiterzeichen des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'fassung_bearbeiterzeichen', 'fassung_bearbeiterzeichen', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Bearbeiterzeichen der Änderung (LUNG, StALU und StAUN)', '', '', '', '', 'Abweichendes Bearbeiterzeichen eines Änderungsbescheides (o. ä.) zu einer Wasserrechtlichen Zulassung ohne unnötige Leerzeichen. [Bearbeiterzeichen werden vom LUNG und den StÄLU (historisch auch StÄUN) vor die Aktenzeichen geschrieben und sollen zu besser', 'Fassung', NULL, '0', '36', '1', '0');

-- Attribut fassung_datum des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'fassung_datum', 'fassung_datum', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Text', '', 'Datum Fassung WrZ', '', '', '', '', 'Datum auf das die Fassung der WrZ datiert wurde.', 'Fassung', NULL, '0', '38', '1', '0');

-- Attribut fassung_nummer des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'fassung_nummer', 'fassung_nummer', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Text', '', 'Nummer Fassung WrZ', '', '', '', '', 'Das Feld dient  der korrekten Nummerierung der eventuellen Fassung einer Wasserrechtlichen Zulassung.', 'Fassung', NULL, '0', '34', '1', '0');

-- Attribut fassung_typus des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'fassung_typus', 'fassung_typus', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Text', '', 'Typus Fassung WrZ', '', '', '', '', 'Typus der eventuellen Fassung einer Wasserrechtlichen Zulassung (z.B. Änderungsbescheid, Anpassungsbescheid, etc.) [Neue Typen müssen über den Admin beantragt werden]', 'Fassung', NULL, '0', '35', '1', '0');

-- Attribut freigegeben des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'freigegeben', 'freigegeben', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'bool', '', '', '1', NULL, NULL, 'SELECT false', 'Auswahlfeld', 'select \'ja\' as output, true as value
union
select \'nein\' as output, false as value', 'Freigegeben', '', '', '', '', 'WrZ freigegeben?', 'Historienverwaltung', NULL, '0', '52', '1', '0');

-- Attribut gueltig_seit des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'gueltig_seit', 'gueltig_seit', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Text', '', 'Gültig seit', '', '', '', '', 'Datum seit dem die Wrz gültig ist. [Ist das Feld leer, wird angenommen, dass die WrZ ab dem Ausstellungsdatum gültig ist.]', 'Materielle Bestandskraft', NULL, '0', '39', '1', '0');

-- Attribut nachfolger des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'nachfolger', 'nachfolger', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT a.id as value, a.bezeichnung AS output FROM fiswrv_wasserrechtliche_zulassungen_bezeichnung a WHERE a.anlage = <requires>anlage_id</requires> AND a.id !=<requires>wrz_id</requires> ;', 'Nachfolger', '', '', '', '', 'Nachfolge WrZ', 'Historienverwaltung', NULL, '0', '50', '1', '0');

-- Attribut ort des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'ort', 'ort', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Autovervollständigungsfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_ort;layer_id=30 embedded anywhere', '<strong>Ort</strong>', '', '', '', '', 'Ort an dem der Bescheid ausgestellt wurde.', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '0', '31', '1', '0');

-- Attribut personen_id des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'personen_id', 'adressat', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Autovervollständigungsfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_personen a WHERE a.wrzadressat IS NOT NULL AND a.wrzadressat = \'ja\';layer_id=9 embedded', 'Adressat [Auswahlfeld]', '', '', '', '', '', 'Adressat', NULL, '0', '9', '1', '0');

-- Attribut regnummer des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'regnummer', 'regnummer', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Registriernummer WrZ', '', '', '', '', 'Registriernummer der WrZ ohne unnötige Leerzeichen. [Unnötig sind dabei alle Leerzeichen in Verbindung mit anderen Trennzeichen wie', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '0', '29', '1', '0');

-- Attribut status des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'status', 'status', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_wasserrechtliche_zulassungen_status;layer_id=20 embedded', 'Status des Dokuments', '', '', '', '', 'Gibt an ob es sich bei dem Datensatz um eine geprüft Abschrift einer Wasserrechtlichen Zulassung (geringste Fehlerrate), eine Übertragung aus einer geprüften LUNG-Datenbank (mittlere Fehlerrate) oder um Erstbefüllungsdaten (höchste Fehlerrate) handelt.', 'Status der Wasserrechtliche Zulassung', NULL, '0', '41', '1', '0');

-- Attribut stelle_id des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'stelle_id', 'stelle_id', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '10', NULL, '', 'StelleID', '', 'Stelle ID', '', '', '', '', '', 'letzte Änderung', NULL, '0', '56', '0', '0');

-- Attribut stelle_name des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'stelle_name', 'stelle_name', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Stelle', '', 'Stelle', '', '', '', '', 'Stelle des Benutzers, der als letztes eine Änderung gespeichert hat.', 'letzte Änderung', NULL, '0', '55', '0', '0');

-- Attribut typus des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'typus', 'typus', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '0', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_wasserrechtliche_zulassungen_typus;layer_id=26 embedded', '<strong>Typus Wrz</strong>', '', '', '', '', 'PFLICHTFELD! Typus der Wasserrechtlichen Zulassung (z.B. Wasserrechtliche Erlaubnis, Wasserrechtliche Nutzungsgenehmigung, etc.) [Neue Typen müssen über den Admin beantragt werden]', 'Typus, Aktenzeichen o.ä., Ort, Datum', NULL, '0', '26', '1', '0');

-- Attribut ungueltig_aufgrund des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'ungueltig_aufgrund', 'ungueltig_aufgrund', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund;layer_id=32 embedded', 'Ungültig aufgrund', '', '', '', '', 'Grund warum die WrZ ungültig ist.', 'Status der Wasserrechtliche Zulassung', NULL, '0', '45', '1', '0');

-- Attribut ungueltig_seit des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'ungueltig_seit', 'ungueltig_seit', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Text', '', 'Ungültig seit', '', '', '', '', 'Datum seit dem die WrZ ungültig ist. [Ist das Feld leer, wird angenommen, dass die WrZ gültig ist.]', 'Status der Wasserrechtliche Zulassung', NULL, '0', '44', '1', '0');

-- Attribut vorgaenger des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'vorgaenger', 'vorgaenger', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT a.id as value, a.bezeichnung AS output FROM fiswrv_wasserrechtliche_zulassungen_bezeichnung a WHERE a.anlage = <requires>anlage_id</requires> AND a.id !=<requires>wrz_id</requires> ;', 'Vorgänger', '', '', '', '', 'Vorgänger WrZ', 'Historienverwaltung', NULL, '0', '51', '1', '0');

-- Attribut wirksam des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'wirksam', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Wirksam', '', '', '', '', '', 'Status der Wasserrechtliche Zulassung', NULL, '0', '43', '0', '0');

-- Attribut wrz_bearbeiter_id des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'wrz_bearbeiter_id', 'bearbeiter_id', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '10', NULL, '', 'UserID', '', 'WrZ Bearbeiter ID', '', '', '', '', '', 'letzte Änderung', NULL, '0', '54', '0', '0');

-- Attribut wrz_bearbeiter_name des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'wrz_bearbeiter_name', 'bearbeiter_name', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'User', '', 'WrZ Bearbeiter Name', '', '', '', '', 'Name des Benutzers, der als letztes eine Änderung gespeichert hat.', 'letzte Änderung', NULL, '0', '53', '0', '0');

-- Attribut wrz_ben des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'wrz_ben', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '33,wrz_id,bezeichnung;no_new_window', 'Zugelassene Benutzungen', '', '', '', '', '', '<h3>Zugelassene Benutzungen</h3>', NULL, '0', '58', '0', '0');

-- Attribut wrz_id des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'wrz_id', 'id', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', 'Primärschlüssel WrZ', '', '', '', '', 'Primärschlüssel der Wasserrechtlichen Zulassungen.', 'Systemfeld', NULL, '0', '3', '0', '0');

-- Attribut wrz_wid des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'wrz_wid', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Metaschlüssel WrZ', '', '', '', '', 'Zusammengesetzter Schlüssel aus dem Primärschlüssel der wasserrechtlich relevanten Anlagen und dem Primärschlüssel der Wasserrechtlichen Zulassungen, getrennt durch ein Minuszeichen.', 'Systemfeld', NULL, '0', '4', '0', '0');

-- Attribut zustaendige_behoerde des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'zustaendige_behoerde', 'zustaendige_behoerde', 'fiswrv_wasserrechtliche_zulassungen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_behoerde WHERE aktuell=true;layer_id=17 embedded', 'Zutändige Behörde WrZ [Auswahlfeld]', '', '', '', '', 'Die für die Wasserrechtliche Zulassung zuständige Behörde. [Neue Behörden müssen beim Admin beantragt werden.]', '<h1>Abschrift der Wasserrechtlichen Zulassung</h1>', NULL, '0', '7', '1', '0');

-- Attribut zustaendige_behoerde_link des Layers 52
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id52, 'zustaendige_behoerde_link', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'dynamicLink', '', 'Zutändige Behörde WrZ [Link]', '', '', '', '', 'Link auf die für die Wasserrechtliche Zulassung zuständige Behörde.', '<h1>Abschrift der Wasserrechtlichen Zulassung</h1>', NULL, '0', '8', '0', '0');

-- Zuordnung der Layerattribute des Layers 52 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'adressat_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'adressat_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'adressat_namenszusatz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'adressat_plz_ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'adressat_strasse_hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'aktenzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'aktuell', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'anlage_anzeige', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'anlage_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'anlage_klasse', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'ausstellbehoerde', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'ausstellbehoerde_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'bearbeiter', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'bearbeiterzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'bearbeiter_email', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'bearbeiter_fax', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'bearbeiter_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'bearbeiter_namenszusatz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'bearbeiter_plz_ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'bearbeiter_strasse_hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'bearbeiter_telefon', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'bearbeiter_zimmer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'bearbeitungs_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'befristet_bis', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'bergamt_aktenzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'bezeichnung', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'datum', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'datum_bestand_form', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'datum_bestand_mat', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'datum_postausgang', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'dokument', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'fassung_aktenzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'fassung_auswahl', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'fassung_bearbeiterzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'fassung_datum', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'fassung_nummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'fassung_typus', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'freigegeben', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'gueltig_seit', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'nachfolger', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'ort', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'personen_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'regnummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'status', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'stelle_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'stelle_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'typus', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'ungueltig_aufgrund', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'ungueltig_seit', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'vorgaenger', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'wirksam', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'wrz_bearbeiter_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'wrz_bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'wrz_ben', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'wrz_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'wrz_wid', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'zustaendige_behoerde', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_2, 'zustaendige_behoerde_link', '0', '0');

-- Zuordnung der Layerattribute des Layers 52 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'adressat_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'adressat_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'adressat_namenszusatz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'adressat_plz_ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'adressat_strasse_hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'aktenzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'aktuell', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'anlage_anzeige', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'anlage_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'anlage_klasse', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'ausstellbehoerde', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'ausstellbehoerde_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'bearbeiter', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'bearbeiterzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'bearbeiter_email', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'bearbeiter_fax', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'bearbeiter_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'bearbeiter_namenszusatz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'bearbeiter_plz_ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'bearbeiter_strasse_hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'bearbeiter_telefon', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'bearbeiter_zimmer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'bearbeitungs_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'befristet_bis', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'bergamt_aktenzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'bezeichnung', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'datum', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'datum_bestand_form', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'datum_bestand_mat', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'datum_postausgang', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'dokument', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'fassung_aktenzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'fassung_auswahl', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'fassung_bearbeiterzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'fassung_datum', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'fassung_nummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'fassung_typus', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'freigegeben', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'gueltig_seit', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'nachfolger', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'ort', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'personen_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'regnummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'status', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'stelle_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'stelle_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'typus', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'ungueltig_aufgrund', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'ungueltig_seit', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'vorgaenger', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'wirksam', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'wrz_bearbeiter_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'wrz_bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'wrz_ben', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'wrz_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'wrz_wid', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'zustaendige_behoerde', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_4, 'zustaendige_behoerde_link', '0', '0');

-- Zuordnung der Layerattribute des Layers 52 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'adressat_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'adressat_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'adressat_namenszusatz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'adressat_plz_ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'adressat_strasse_hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'aktenzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'aktuell', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'anlage_anzeige', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'anlage_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'anlage_klasse', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'ausstellbehoerde', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'ausstellbehoerde_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'bearbeiter', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'bearbeiterzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'bearbeiter_email', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'bearbeiter_fax', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'bearbeiter_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'bearbeiter_namenszusatz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'bearbeiter_plz_ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'bearbeiter_strasse_hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'bearbeiter_telefon', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'bearbeiter_zimmer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'bearbeitungs_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'befristet_bis', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'bergamt_aktenzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'bezeichnung', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'datum', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'datum_bestand_form', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'datum_bestand_mat', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'datum_postausgang', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'dokument', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'fassung_aktenzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'fassung_auswahl', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'fassung_bearbeiterzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'fassung_datum', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'fassung_nummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'fassung_typus', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'freigegeben', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'gueltig_seit', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'nachfolger', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'ort', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'personen_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'regnummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'status', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'stelle_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'stelle_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'typus', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'ungueltig_aufgrund', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'ungueltig_seit', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'vorgaenger', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'wirksam', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'wrz_bearbeiter_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'wrz_bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'wrz_ben', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'wrz_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'wrz_wid', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'zustaendige_behoerde', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id52, @stelle_id_5, 'zustaendige_behoerde_link', '0', '0');

-- Layer 34
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Gewaesserbenutzungen_Art', '', '5', @group_id, 'SELECT id, name FROM fiswrv_gewaesserbenutzungen_art WHERE 1=1', 'fiswrv_gewaesserbenutzungen_art', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id34=LAST_INSERT_ID();

-- Zuordnung Layer 34 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id34, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 34 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id34, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 34 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id34, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut id des Layers 34
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id34, 'id', 'id', 'fiswrv_gewaesserbenutzungen_art', 'fiswrv_gewaesserbenutzungen_art', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', 'ID', '', '', '', '', '', '', NULL, '-1', '0', '0', '0');

-- Attribut name des Layers 34
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id34, 'name', 'name', 'fiswrv_gewaesserbenutzungen_art', 'fiswrv_gewaesserbenutzungen_art', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 34 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id34, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id34, @stelle_id_2, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 34 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id34, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id34, @stelle_id_4, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 34 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id34, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id34, @stelle_id_5, 'name', '0', '0');

-- Layer 45
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Gewaesserbenutzungen_Art_Benutzung', '', '5', @group_id, 'SELECT id, name, abkuerzung FROM fiswrv_gewaesserbenutzungen_art_benutzung WHERE 1=1', 'fiswrv_gewaesserbenutzungen_art_benutzung', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id45=LAST_INSERT_ID();

-- Zuordnung Layer 45 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id45, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 45 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id45, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 45 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id45, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut abkuerzung des Layers 45
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id45, 'abkuerzung', 'abkuerzung', 'fiswrv_gewaesserbenutzungen_art_benutzung', 'fiswrv_gewaesserbenutzungen_art_benutzung', 'varchar', '', '', '1', '100', NULL, '', 'Text', '', 'Abkürzung', '', '', '', '', '', '', NULL, '0', '2', '0', '0');

-- Attribut id des Layers 45
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id45, 'id', 'id', 'fiswrv_gewaesserbenutzungen_art_benutzung', 'fiswrv_gewaesserbenutzungen_art_benutzung', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 45
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id45, 'name', 'name', 'fiswrv_gewaesserbenutzungen_art_benutzung', 'fiswrv_gewaesserbenutzungen_art_benutzung', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 45 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id45, @stelle_id_2, 'abkuerzung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id45, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id45, @stelle_id_2, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 45 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id45, @stelle_id_4, 'abkuerzung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id45, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id45, @stelle_id_4, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 45 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id45, @stelle_id_5, 'abkuerzung', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id45, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id45, @stelle_id_5, 'name', '0', '0');

-- Layer 37
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Gewaesserbenutzungen_Umfang', '', '5', @group_id, 'SELECT a.id, a.name, a.wert, a.einheit, a.gewaesserbenutzungen as gwb_id,b.bezeichnung AS gewaesserbenutzung_link FROM fiswrv_gewaesserbenutzungen_umfang a  LEFT JOIN fiswrv_gewaesserbenutzungen_bezeichnung b ON a.gewaesserbenutzungen = b.id WHERE 1=1', 'fiswrv_gewaesserbenutzungen_umfang', '', 'wasserrecht', '', '', '', '', 'id', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '2', '', '0');
SET @last_layer_id37=LAST_INSERT_ID();

-- Zuordnung Layer 37 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id37, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 37 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id37, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 37 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id37, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Attribut einheit des Layers 37
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id37, 'einheit', 'einheit', 'fiswrv_gewaesserbenutzungen_umfang', 'a', 'int4', '', '', '0', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, abkuerzung as output from wasserrecht.fiswrv_gewaesserbenutzungen_umfang_einheiten;layer_id=50 embedded', 'Einheit', '', '', '', '', '', '', NULL, '0', '3', '1', '0');

-- Attribut gewaesserbenutzung_link des Layers 37
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id37, 'gewaesserbenutzung_link', 'bezeichnung', 'fiswrv_gewaesserbenutzungen_bezeichnung', 'b', 'text', '', '', '1', NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=33&value_gwb_id=$gwb_id&operator_gwb_id==;<b>Benutzung:</b> $gewaesserbenutzung_link;no_new_window', 'Gewässerbenutzung [Link]', '', '', '', '', '', '', NULL, '-1', '5', '0', '0');

-- Attribut gwb_id des Layers 37
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id37, 'gwb_id', 'gewaesserbenutzungen', 'fiswrv_gewaesserbenutzungen_umfang', 'a', 'int4', '', '', '0', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, kennnummer as output from wasserrecht.fiswrv_gewaesserbenutzungen;', 'Gewässerbenutzung', '', '', '', '', '', '', NULL, '0', '4', '1', '0');

-- Attribut id des Layers 37
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id37, 'id', 'id', 'fiswrv_gewaesserbenutzungen_umfang', 'a', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', 'Primärschlüssel', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 37
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id37, 'name', 'name', 'fiswrv_gewaesserbenutzungen_umfang', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_gewaesserbenutzungen_umfang_name;layer_id=51 embedded', 'Name', '', '', '', '', '', '', NULL, '0', '1', '1', '0');

-- Attribut wert des Layers 37
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id37, 'wert', 'wert', 'fiswrv_gewaesserbenutzungen_umfang', 'a', 'numeric', '', '', '1', NULL, NULL, '', 'Text', '', 'Wert', '', '', '', '', '', '', NULL, '0', '2', '1', '0');

-- Zuordnung der Layerattribute des Layers 37 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id37, @stelle_id_2, 'einheit', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id37, @stelle_id_2, 'gewaesserbenutzung_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id37, @stelle_id_2, 'gwb_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id37, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id37, @stelle_id_2, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id37, @stelle_id_2, 'wert', '1', '0');

-- Zuordnung der Layerattribute des Layers 37 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id37, @stelle_id_4, 'einheit', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id37, @stelle_id_4, 'gewaesserbenutzung_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id37, @stelle_id_4, 'gwb_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id37, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id37, @stelle_id_4, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id37, @stelle_id_4, 'wert', '1', '0');

-- Zuordnung der Layerattribute des Layers 37 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id37, @stelle_id_5, 'einheit', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id37, @stelle_id_5, 'gewaesserbenutzung_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id37, @stelle_id_5, 'gwb_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id37, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id37, @stelle_id_5, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id37, @stelle_id_5, 'wert', '1', '0');

-- Layer 50
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Gewaesserbenutzungen_Umfang_Einheiten', '', '5', @group_id, 'SELECT id, name, abkuerzung  FROM fiswrv_gewaesserbenutzungen_umfang_einheiten WHERE 1=1', 'fiswrv_gewaesserbenutzungen_umfang_einheiten', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '2', '', '0');
SET @last_layer_id50=LAST_INSERT_ID();

-- Zuordnung Layer 50 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id50, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 50 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id50, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 50 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id50, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Attribut abkuerzung des Layers 50
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id50, 'abkuerzung', 'abkuerzung', 'fiswrv_gewaesserbenutzungen_umfang_einheiten', 'fiswrv_gewaesserbenutzungen_umfang_einheiten', 'varchar', '', '', '0', '100', NULL, '', 'Text', '', 'Abkürzung', '', '', '', '', '', '', NULL, '0', '2', '1', '0');

-- Attribut id des Layers 50
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id50, 'id', 'id', 'fiswrv_gewaesserbenutzungen_umfang_einheiten', 'fiswrv_gewaesserbenutzungen_umfang_einheiten', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 50
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id50, 'name', 'name', 'fiswrv_gewaesserbenutzungen_umfang_einheiten', 'fiswrv_gewaesserbenutzungen_umfang_einheiten', 'varchar', '', '', '0', '255', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '1', '0');

-- Zuordnung der Layerattribute des Layers 50 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id50, @stelle_id_2, 'abkuerzung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id50, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id50, @stelle_id_2, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 50 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id50, @stelle_id_4, 'abkuerzung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id50, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id50, @stelle_id_4, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 50 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id50, @stelle_id_5, 'abkuerzung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id50, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id50, @stelle_id_5, 'name', '1', '0');

-- Layer 51
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Gewaesserbenutzungen_Umfang_Name', '', '5', @group_id, 'SELECT id, name, abkuerzung, beschreibung  FROM fiswrv_gewaesserbenutzungen_umfang_name WHERE 1=1', 'fiswrv_gewaesserbenutzungen_umfang_name', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '2', '', '0');
SET @last_layer_id51=LAST_INSERT_ID();

-- Zuordnung Layer 51 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id51, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 51 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id51, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 51 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id51, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Attribut abkuerzung des Layers 51
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id51, 'abkuerzung', 'abkuerzung', 'fiswrv_gewaesserbenutzungen_umfang_name', 'fiswrv_gewaesserbenutzungen_umfang_name', 'varchar', '', '', '0', '100', NULL, '', 'Text', '', 'Abkürzung', '', '', '', '', '', '', NULL, '0', '2', '1', '0');

-- Attribut beschreibung des Layers 51
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id51, 'beschreibung', 'beschreibung', 'fiswrv_gewaesserbenutzungen_umfang_name', 'fiswrv_gewaesserbenutzungen_umfang_name', 'text', '', '', '1', NULL, NULL, '', 'Text', '', 'Beschreibung', '', '', '', '', '', '', NULL, '0', '3', '1', '0');

-- Attribut id des Layers 51
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id51, 'id', 'id', 'fiswrv_gewaesserbenutzungen_umfang_name', 'fiswrv_gewaesserbenutzungen_umfang_name', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 51
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id51, 'name', 'name', 'fiswrv_gewaesserbenutzungen_umfang_name', 'fiswrv_gewaesserbenutzungen_umfang_name', 'varchar', '', '', '0', '255', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '1', '0');

-- Zuordnung der Layerattribute des Layers 51 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id51, @stelle_id_2, 'abkuerzung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id51, @stelle_id_2, 'beschreibung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id51, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id51, @stelle_id_2, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 51 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id51, @stelle_id_4, 'abkuerzung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id51, @stelle_id_4, 'beschreibung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id51, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id51, @stelle_id_4, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 51 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id51, @stelle_id_5, 'abkuerzung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id51, @stelle_id_5, 'beschreibung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id51, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id51, @stelle_id_5, 'name', '1', '0');

-- Layer 47
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Gewaesserbenutzungen_WEE_Satz', '', '5', @group_id, 'SELECT id, name, jahr, satz_gw_befreit, satz_gw_zugelassen, satz_gw_nicht_zugelassen,        satz_gw_zugelassen_ermaessigt, satz_gw_nicht_zugelassen_ermaessigt,        satz_ow_befreit, satz_ow_zugelassen, satz_ow_nicht_zugelassen,        satz_ow_zugelassen_ermaessigt, satz_ow_nicht_zugelassen_ermaessigt FROM fiswrv_gewaesserbenutzungen_wee_satz WHERE 1=1', 'fiswrv_gewaesserbenutzungen_wee_satz', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id47=LAST_INSERT_ID();

-- Zuordnung Layer 47 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id47, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 47 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id47, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 47 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id47, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut id des Layers 47
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id47, 'id', 'id', 'fiswrv_gewaesserbenutzungen_wee_satz', 'fiswrv_gewaesserbenutzungen_wee_satz', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut jahr des Layers 47
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id47, 'jahr', 'jahr', 'fiswrv_gewaesserbenutzungen_wee_satz', 'fiswrv_gewaesserbenutzungen_wee_satz', 'date', '', '', '1', NULL, NULL, '', 'Text', '', 'Jahr', '', '', '', '', '', '', NULL, '0', '2', '0', '0');

-- Attribut name des Layers 47
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id47, 'name', 'name', 'fiswrv_gewaesserbenutzungen_wee_satz', 'fiswrv_gewaesserbenutzungen_wee_satz', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Attribut satz_gw_befreit des Layers 47
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id47, 'satz_gw_befreit', 'satz_gw_befreit', 'fiswrv_gewaesserbenutzungen_wee_satz', 'fiswrv_gewaesserbenutzungen_wee_satz', 'numeric', '', '', '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, NULL, '3', '0', '0');

-- Attribut satz_gw_nicht_zugelassen des Layers 47
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id47, 'satz_gw_nicht_zugelassen', 'satz_gw_nicht_zugelassen', 'fiswrv_gewaesserbenutzungen_wee_satz', 'fiswrv_gewaesserbenutzungen_wee_satz', 'numeric', '', '', '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, NULL, '5', '0', '0');

-- Attribut satz_gw_nicht_zugelassen_ermaessigt des Layers 47
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id47, 'satz_gw_nicht_zugelassen_ermaessigt', 'satz_gw_nicht_zugelassen_ermaessigt', 'fiswrv_gewaesserbenutzungen_wee_satz', 'fiswrv_gewaesserbenutzungen_wee_satz', 'numeric', '', '', '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, NULL, '7', '0', '0');

-- Attribut satz_gw_zugelassen des Layers 47
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id47, 'satz_gw_zugelassen', 'satz_gw_zugelassen', 'fiswrv_gewaesserbenutzungen_wee_satz', 'fiswrv_gewaesserbenutzungen_wee_satz', 'numeric', '', '', '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, NULL, '4', '0', '0');

-- Attribut satz_gw_zugelassen_ermaessigt des Layers 47
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id47, 'satz_gw_zugelassen_ermaessigt', 'satz_gw_zugelassen_ermaessigt', 'fiswrv_gewaesserbenutzungen_wee_satz', 'fiswrv_gewaesserbenutzungen_wee_satz', 'numeric', '', '', '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, NULL, '6', '0', '0');

-- Attribut satz_ow_befreit des Layers 47
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id47, 'satz_ow_befreit', 'satz_ow_befreit', 'fiswrv_gewaesserbenutzungen_wee_satz', 'fiswrv_gewaesserbenutzungen_wee_satz', 'numeric', '', '', '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, NULL, '8', '0', '0');

-- Attribut satz_ow_nicht_zugelassen des Layers 47
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id47, 'satz_ow_nicht_zugelassen', 'satz_ow_nicht_zugelassen', 'fiswrv_gewaesserbenutzungen_wee_satz', 'fiswrv_gewaesserbenutzungen_wee_satz', 'numeric', '', '', '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, NULL, '10', '0', '0');

-- Attribut satz_ow_nicht_zugelassen_ermaessigt des Layers 47
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id47, 'satz_ow_nicht_zugelassen_ermaessigt', 'satz_ow_nicht_zugelassen_ermaessigt', 'fiswrv_gewaesserbenutzungen_wee_satz', 'fiswrv_gewaesserbenutzungen_wee_satz', 'numeric', '', '', '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, NULL, '12', '0', '0');

-- Attribut satz_ow_zugelassen des Layers 47
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id47, 'satz_ow_zugelassen', 'satz_ow_zugelassen', 'fiswrv_gewaesserbenutzungen_wee_satz', 'fiswrv_gewaesserbenutzungen_wee_satz', 'numeric', '', '', '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, NULL, '9', '0', '0');

-- Attribut satz_ow_zugelassen_ermaessigt des Layers 47
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id47, 'satz_ow_zugelassen_ermaessigt', 'satz_ow_zugelassen_ermaessigt', 'fiswrv_gewaesserbenutzungen_wee_satz', 'fiswrv_gewaesserbenutzungen_wee_satz', 'numeric', '', '', '1', NULL, NULL, '', 'Text', '', '', '', '', '', '', '', '', NULL, NULL, '11', '0', '0');

-- Zuordnung der Layerattribute des Layers 47 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_2, 'jahr', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_2, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_2, 'satz_gw', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_2, 'satz_ow', '1', '0');

-- Zuordnung der Layerattribute des Layers 47 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_4, 'jahr', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_4, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_4, 'satz_gw', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_4, 'satz_ow', '1', '0');

-- Zuordnung der Layerattribute des Layers 47 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_5, 'jahr', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_5, 'name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_5, 'satz_gw_befreit', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_5, 'satz_gw_nicht_zugelassen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_5, 'satz_gw_nicht_zugelassen_ermaessigt', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_5, 'satz_gw_zugelassen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_5, 'satz_gw_zugelassen_ermaessigt', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_5, 'satz_ow_befreit', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_5, 'satz_ow_nicht_zugelassen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_5, 'satz_ow_nicht_zugelassen_ermaessigt', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_5, 'satz_ow_zugelassen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id47, @stelle_id_5, 'satz_ow_zugelassen_ermaessigt', '0', '0');

-- Layer 36
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Gewaesserbenutzungen_Zweck', '', '5', @group_id, 'SELECT id, nummer, name FROM fiswrv_gewaesserbenutzungen_zweck WHERE 1=1', 'fiswrv_gewaesserbenutzungen_zweck', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id36=LAST_INSERT_ID();

-- Zuordnung Layer 36 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id36, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 36 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id36, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 36 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id36, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut id des Layers 36
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id36, 'id', 'id', 'fiswrv_gewaesserbenutzungen_zweck', 'fiswrv_gewaesserbenutzungen_zweck', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', 'ID', '', '', '', '', '', '', NULL, '-1', '0', '0', '0');

-- Attribut name des Layers 36
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id36, 'name', 'name', 'fiswrv_gewaesserbenutzungen_zweck', 'fiswrv_gewaesserbenutzungen_zweck', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '2', '0', '0');

-- Attribut nummer des Layers 36
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id36, 'nummer', 'nummer', 'fiswrv_gewaesserbenutzungen_zweck', 'fiswrv_gewaesserbenutzungen_zweck', 'int4', '', '', '1', '32', '0', '', 'Text', '', 'Nummer', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 36 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id36, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id36, @stelle_id_2, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id36, @stelle_id_2, 'nummer', '1', '0');

-- Zuordnung der Layerattribute des Layers 36 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id36, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id36, @stelle_id_4, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id36, @stelle_id_4, 'nummer', '1', '0');

-- Zuordnung der Layerattribute des Layers 36 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id36, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id36, @stelle_id_5, 'name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id36, @stelle_id_5, 'nummer', '0', '0');

-- Layer 16
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Konto', '', '5', @group_id, 'SELECT id as konto_id, name, iban, bic, bankname, verwendungszweck, personenkonto, kassenzeichen FROM fiswrv_konto WHERE 1=1', 'fiswrv_konto', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id16=LAST_INSERT_ID();

-- Zuordnung Layer 16 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id16, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 16 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id16, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 16 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id16, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut bankname des Layers 16
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id16, 'bankname', 'bankname', 'fiswrv_konto', 'fiswrv_konto', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Bankname', '', '', '', '', '', '', NULL, '0', '4', '0', '0');

-- Attribut bic des Layers 16
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id16, 'bic', 'bic', 'fiswrv_konto', 'fiswrv_konto', 'varchar', '', '', '1', '11', NULL, '', 'Text', '', 'BIC', '', '', '', '', '', '', NULL, '0', '3', '0', '0');

-- Attribut iban des Layers 16
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id16, 'iban', 'iban', 'fiswrv_konto', 'fiswrv_konto', 'varchar', '', '', '1', '22', NULL, '', 'Text', '', 'IBAN', '', '', '', '', '', '', NULL, '0', '2', '0', '0');

-- Attribut kassenzeichen des Layers 16
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id16, 'kassenzeichen', 'kassenzeichen', 'fiswrv_konto', 'fiswrv_konto', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Kassenzeichen', '', '', '', '', '', '', NULL, '0', '7', '0', '0');

-- Attribut konto_id des Layers 16
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id16, 'konto_id', 'id', 'fiswrv_konto', 'fiswrv_konto', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 16
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id16, 'name', 'name', 'fiswrv_konto', 'fiswrv_konto', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Attribut personenkonto des Layers 16
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id16, 'personenkonto', 'personenkonto', 'fiswrv_konto', 'fiswrv_konto', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Personenkonto', '', '', '', '', '', '', NULL, '0', '6', '0', '0');

-- Attribut verwendungszweck des Layers 16
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id16, 'verwendungszweck', 'verwendungszweck', 'fiswrv_konto', 'fiswrv_konto', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Verwendungszweck', '', '', '', '', '', '', NULL, '0', '5', '0', '0');

-- Zuordnung der Layerattribute des Layers 16 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_2, 'bankname', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_2, 'bic', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_2, 'iban', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_2, 'kassenzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_2, 'konto_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_2, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_2, 'personenkonto', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_2, 'verwendungszweck', '1', '0');

-- Zuordnung der Layerattribute des Layers 16 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_4, 'bankname', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_4, 'bic', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_4, 'iban', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_4, 'kassenzeichen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_4, 'konto_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_4, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_4, 'personenkonto', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_4, 'verwendungszweck', '1', '0');

-- Zuordnung der Layerattribute des Layers 16 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_5, 'bankname', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_5, 'bic', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_5, 'iban', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_5, 'kassenzeichen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_5, 'konto_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_5, 'name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_5, 'personenkonto', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id16, @stelle_id_5, 'verwendungszweck', '0', '0');

-- Layer 6
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Körperschaft', '', '5', @group_id, 'SELECT id, name,art FROM fiswrv_koerperschaft WHERE 1=1', 'fiswrv_koerperschaft', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id6=LAST_INSERT_ID();

-- Zuordnung Layer 6 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id6, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 6 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id6, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 6 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id6, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut art des Layers 6
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id6, 'art', 'art', 'fiswrv_koerperschaft', 'fiswrv_koerperschaft', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_koerperschaft_art;layer_id=42 embedded', 'Körperschaftsart', '', '', '', '', '', '', NULL, '0', '2', '0', '0');

-- Attribut id des Layers 6
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id6, 'id', 'id', 'fiswrv_koerperschaft', 'fiswrv_koerperschaft', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 6
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id6, 'name', 'name', 'fiswrv_koerperschaft', 'fiswrv_koerperschaft', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 6 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id6, @stelle_id_2, 'art', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id6, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id6, @stelle_id_2, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 6 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id6, @stelle_id_4, 'art', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id6, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id6, @stelle_id_4, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 6 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id6, @stelle_id_5, 'art', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id6, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id6, @stelle_id_5, 'name', '0', '0');

-- Class 8 des Layers 6
INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`, `text`) VALUES ('alle', @last_layer_id6, '', '1', '');
SET @last_class_id=LAST_INSERT_ID();

-- Style 8 der Class 8
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`, `antialias`, `width`, `minwidth`, `maxwidth`, `sizeitem`) VALUES (NULL, 'circle', '8', '30 149 255', '', '0 0 0', NULL, '10', '360', '', NULL, NULL, NULL, NULL, '');
SET @last_style_id=LAST_INSERT_ID();
-- Zuordnung Style 8 zu Class 8
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);

-- Layer 42
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Körperschaftsart', '', '5', @group_id, 'SELECT id, name FROM fiswrv_koerperschaft_art WHERE 1=1', 'fiswrv_koerperschaft_art', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id42=LAST_INSERT_ID();

-- Zuordnung Layer 42 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id42, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 42 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id42, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 42 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id42, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut id des Layers 42
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id42, 'id', 'id', 'fiswrv_koerperschaft_art', 'fiswrv_koerperschaft_art', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 42
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id42, 'name', 'name', 'fiswrv_koerperschaft_art', 'fiswrv_koerperschaft_art', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 42 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id42, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id42, @stelle_id_2, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 42 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id42, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id42, @stelle_id_4, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 42 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id42, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id42, @stelle_id_5, 'name', '0', '0');

-- Layer 43
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Mengenbestimmung', '', '5', @group_id, 'SELECT id, name FROM fiswrv_mengenbestimmung WHERE 1=1', 'fiswrv_mengenbestimmung ', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id43=LAST_INSERT_ID();

-- Zuordnung Layer 43 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id43, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 43 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id43, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 43 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id43, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut id des Layers 43
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id43, 'id', 'id', 'fiswrv_mengenbestimmung', 'fiswrv_mengenbestimmung', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 43
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id43, 'name', 'name', 'fiswrv_mengenbestimmung', 'fiswrv_mengenbestimmung', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 43 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id43, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id43, @stelle_id_2, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 43 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id43, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id43, @stelle_id_4, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 43 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id43, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id43, @stelle_id_5, 'name', '0', '0');

-- Layer 40
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Messtischblatt', '', '5', @group_id, 'SELECT id, nummer FROM fiswrv_messtischblatt WHERE 1=1', 'fiswrv_messtischblatt ', '', 'wasserrecht', '', '', '', '', 'nummer', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, NULL, '-1', NULL, '', '', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id40=LAST_INSERT_ID();

-- Zuordnung Layer 40 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id40, @stelle_id_2, '1', '0', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 40 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id40, @stelle_id_4, '1', '0', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 40 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id40, @stelle_id_5, '1', '0', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut id des Layers 40
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id40, 'id', 'id', 'fiswrv_messtischblatt', 'fiswrv_messtischblatt', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut nummer des Layers 40
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id40, 'nummer', 'nummer', 'fiswrv_messtischblatt', 'fiswrv_messtischblatt', 'int4', '', '', '1', '32', '0', '', 'Text', '', 'Nummer', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 40 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id40, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id40, @stelle_id_2, 'nummer', '1', '0');

-- Zuordnung der Layerattribute des Layers 40 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id40, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id40, @stelle_id_4, 'nummer', '1', '0');

-- Zuordnung der Layerattribute des Layers 40 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id40, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id40, @stelle_id_5, 'nummer', '0', '0');

-- Layer 30
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Ort', '', '5', @group_id, 'SELECT id, name FROM fiswrv_ort WHERE 1=1', 'fiswrv_ort', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id30=LAST_INSERT_ID();

-- Zuordnung Layer 30 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id30, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 30 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id30, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 30 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id30, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut id des Layers 30
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id30, 'id', 'id', 'fiswrv_ort', 'fiswrv_ort', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 30
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id30, 'name', 'name', 'fiswrv_ort', 'fiswrv_ort', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 30 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id30, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id30, @stelle_id_2, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id30, @stelle_id_2, 'the_geo', '1', '0');

-- Zuordnung der Layerattribute des Layers 30 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id30, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id30, @stelle_id_4, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id30, @stelle_id_4, 'the_geo', '1', '0');

-- Zuordnung der Layerattribute des Layers 30 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id30, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id30, @stelle_id_5, 'name', '0', '0');

-- Layer 10
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Personen_Klasse', '', '5', @group_id, 'SELECT id, name FROM fiswrv_personen_klasse WHERE 1=1', 'fiswrv_personen_klasse', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id10=LAST_INSERT_ID();

-- Zuordnung Layer 10 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id10, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 10 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id10, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 10 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id10, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut id des Layers 10
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id10, 'id', 'id', 'fiswrv_personen_klasse', 'fiswrv_personen_klasse', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 10
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id10, 'name', 'name', 'fiswrv_personen_klasse', 'fiswrv_personen_klasse', 'varchar', '', '', '1', '100', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 10 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id10, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id10, @stelle_id_2, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 10 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id10, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id10, @stelle_id_4, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 10 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id10, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id10, @stelle_id_5, 'name', '0', '0');

-- Layer 11
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Personen_Status', '', '5', @group_id, 'SELECT id, name FROM fiswrv_personen_status WHERE 1=1', 'fiswrv_personen_status', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id11=LAST_INSERT_ID();

-- Zuordnung Layer 11 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id11, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 11 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id11, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 11 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id11, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut id des Layers 11
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id11, 'id', 'id', 'fiswrv_personen_status', 'fiswrv_personen_status', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 11
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id11, 'name', 'name', 'fiswrv_personen_status', 'fiswrv_personen_status', 'varchar', '', '', '1', '100', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 11 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id11, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id11, @stelle_id_2, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 11 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id11, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id11, @stelle_id_4, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 11 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id11, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id11, @stelle_id_5, 'name', '0', '0');

-- Layer 13
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Personen_Typ', '', '5', @group_id, 'SELECT id, name FROM fiswrv_personen_typ WHERE 1=1', 'fiswrv_personen_typ', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id13=LAST_INSERT_ID();

-- Zuordnung Layer 13 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id13, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 13 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id13, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 13 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id13, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut id des Layers 13
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id13, 'id', 'id', 'fiswrv_personen_typ', 'fiswrv_personen_typ', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 13
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id13, 'name', 'name', 'fiswrv_personen_typ', 'fiswrv_personen_typ', 'varchar', '', '', '1', '100', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 13 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id13, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id13, @stelle_id_2, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 13 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id13, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id13, @stelle_id_4, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 13 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id13, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id13, @stelle_id_5, 'name', '0', '0');

-- Layer 46
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Teilgewaesserbenutzungen', '', '5', @group_id, 'SELECT id, art, zweck, umfang, wiedereinleitung_nutzer, wiedereinleitung_bearbeiter,        mengenbestimmung, art_benutzung, befreiungstatbestaende, entgeltsatz,        teilgewaesserbenutzungen_art, gewaesserbenutzungen FROM fiswrv_teilgewaesserbenutzungen WHERE 1=1', 'fiswrv_teilgewaesserbenutzungen', '', 'wasserrecht', '', '', '', '', 'id', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id46=LAST_INSERT_ID();

-- Zuordnung Layer 46 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id46, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 46 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id46, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 46 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id46, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut art des Layers 46
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id46, 'art', 'art', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_gewaesserbenutzungen_art;layer_id=34 embedded', 'Art', '', '', '', '', '', 'Stammdaten', NULL, '0', '1', '0', '0');

-- Attribut art_benutzung des Layers 46
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id46, 'art_benutzung', 'art_benutzung', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_gewaesserbenutzungen_art_benutzung;layer_id=45 embedded', 'Art der Benutzung', '', '', '', '', '', 'Stammdaten', NULL, '0', '7', '0', '0');

-- Attribut befreiungstatbestaende des Layers 46
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id46, 'befreiungstatbestaende', 'befreiungstatbestaende', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'bool', '', '', '1', NULL, NULL, '', 'Checkbox', '', 'Befreiungstatbestände nach § 16 LWaG', '', '', '', '', '', 'Stammdaten', NULL, '0', '8', '0', '0');

-- Attribut entgeltsatz des Layers 46
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id46, 'entgeltsatz', 'entgeltsatz', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, jahr as output from wasserrecht.fiswrv_gewaesserbenutzungen_wee_satz;layer_id=47 embedded', 'Entgeltsatz', '', '', '', '', '', 'Stammdaten', NULL, '0', '9', '0', '0');

-- Attribut gewaesserbenutzungen des Layers 46
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id46, 'gewaesserbenutzungen', 'gewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'int4', '', '', '0', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, kennnummer as output from wasserrecht.fiswrv_gewaesserbenutzungen;layer_id=33 embedded', 'Gewässerbenutzungen', '', '', '', '', '', 'Stammdaten', NULL, '0', '11', '0', '0');

-- Attribut id des Layers 46
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id46, 'id', 'id', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut mengenbestimmung des Layers 46
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id46, 'mengenbestimmung', 'mengenbestimmung', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_mengenbestimmung;layer_id=43 embedded', 'Mengenbestimmung', '', '', '', '', '', 'Stammdaten', NULL, '0', '6', '0', '0');

-- Attribut teilgewaesserbenutzungen_art des Layers 46
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id46, 'teilgewaesserbenutzungen_art', 'teilgewaesserbenutzungen_art', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_teilgewaesserbenutzungen_art;layer_id=44 embedded', 'Teilgewässerbenutzungen', '', '', '', '', '', 'Stammdaten', NULL, '0', '10', '0', '0');

-- Attribut umfang des Layers 46
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id46, 'umfang', 'umfang', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'numeric', '', '', '1', NULL, NULL, '', 'Text', '', 'Umfang', '', '', '', '', '', 'Stammdaten', NULL, '0', '3', '0', '0');

-- Attribut wiedereinleitung_bearbeiter des Layers 46
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id46, 'wiedereinleitung_bearbeiter', 'wiedereinleitung_bearbeiter', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'bool', '', '', '1', NULL, NULL, '', 'Checkbox', '', 'Wiedereinleitung nach Bearbeiter', '', '', '', '', '', 'Stammdaten', NULL, '0', '5', '0', '0');

-- Attribut wiedereinleitung_nutzer des Layers 46
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id46, 'wiedereinleitung_nutzer', 'wiedereinleitung_nutzer', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'bool', '', '', '1', NULL, NULL, '', 'Checkbox', '', 'Wiedereinleitung nach Benutzer', '', '', '', '', '', 'Stammdaten', NULL, '0', '4', '0', '0');

-- Attribut zweck des Layers 46
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id46, 'zweck', 'zweck', 'fiswrv_teilgewaesserbenutzungen', 'fiswrv_teilgewaesserbenutzungen', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_gewaesserbenutzungen_zweck;layer_id=36 embedded', 'Zweck', '', '', '', '', '', 'Stammdaten', NULL, '0', '2', '0', '0');

-- Zuordnung der Layerattribute des Layers 46 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_2, 'art', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_2, 'art_benutzung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_2, 'befreiungstatbestaende', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_2, 'entgeltsatz', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_2, 'gewaesserbenutzungen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_2, 'mengenbestimmung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_2, 'teilgewaesserbenutzungen_art', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_2, 'umfang', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_2, 'wiedereinleitung_bearbeiter', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_2, 'wiedereinleitung_nutzer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_2, 'zweck', '1', '0');

-- Zuordnung der Layerattribute des Layers 46 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_4, 'art', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_4, 'art_benutzung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_4, 'befreiungstatbestaende', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_4, 'entgeltsatz', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_4, 'gewaesserbenutzungen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_4, 'mengenbestimmung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_4, 'teilgewaesserbenutzungen_art', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_4, 'umfang', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_4, 'wiedereinleitung_bearbeiter', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_4, 'wiedereinleitung_nutzer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_4, 'zweck', '1', '0');

-- Zuordnung der Layerattribute des Layers 46 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_5, 'art', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_5, 'art_benutzung', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_5, 'befreiungstatbestaende', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_5, 'entgeltsatz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_5, 'gewaesserbenutzungen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_5, 'mengenbestimmung', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_5, 'teilgewaesserbenutzungen_art', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_5, 'umfang', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_5, 'wiedereinleitung_bearbeiter', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_5, 'wiedereinleitung_nutzer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id46, @stelle_id_5, 'zweck', '0', '0');

-- Layer 44
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Teilgewaesserbenutzungen_Art', '', '5', @group_id, 'SELECT id, name FROM fiswrv_teilgewaesserbenutzungen_art WHERE 1=1', 'fiswrv_teilgewaesserbenutzungen_art', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id44=LAST_INSERT_ID();

-- Zuordnung Layer 44 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id44, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 44 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id44, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 44 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id44, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut id des Layers 44
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id44, 'id', 'id', 'fiswrv_teilgewaesserbenutzungen_art', 'fiswrv_teilgewaesserbenutzungen_art', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 44
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id44, 'name', 'name', 'fiswrv_teilgewaesserbenutzungen_art', 'fiswrv_teilgewaesserbenutzungen_art', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 44 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id44, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id44, @stelle_id_2, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 44 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id44, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id44, @stelle_id_4, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 44 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id44, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id44, @stelle_id_5, 'name', '0', '0');

-- Layer 31
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Wasserrechtliche_Zulassungen_Fassung_Auswahl', '', '5', @group_id, 'SELECT id, name FROM fiswrv_wasserrechtliche_zulassungen_fassung_auswahl WHERE 1=1', 'fiswrv_wasserrechtliche_zulassungen_fassung_auswahl', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id31=LAST_INSERT_ID();

-- Zuordnung Layer 31 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id31, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 31 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id31, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 31 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id31, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut id des Layers 31
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id31, 'id', 'id', 'fiswrv_wasserrechtliche_zulassungen_fassung_auswahl', 'fiswrv_wasserrechtliche_zulassungen_fassung_auswahl', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 31
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id31, 'name', 'name', 'fiswrv_wasserrechtliche_zulassungen_fassung_auswahl', 'fiswrv_wasserrechtliche_zulassungen_fassung_auswahl', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 31 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id31, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id31, @stelle_id_2, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 31 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id31, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id31, @stelle_id_4, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 31 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id31, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id31, @stelle_id_5, 'name', '0', '0');

-- Layer 48
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Wasserrechtliche_Zulassungen_Fassung_Typus', '', '5', @group_id, 'SELECT id, name FROM fiswrv_wasserrechtliche_zulassungen_fassung_typus WHERE 1=1', 'fiswrv_wasserrechtliche_zulassungen_fassung_typus', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '3', 'pixels', '35833', '', '1', NULL, NULL, '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id48=LAST_INSERT_ID();

-- Zuordnung Layer 48 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id48, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 48 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id48, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 48 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id48, @stelle_id_5, '1', '0', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut id des Layers 48
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id48, 'id', 'id', 'fiswrv_wasserrechtliche_zulassungen_fassung_typus', 'fiswrv_wasserrechtliche_zulassungen_fassung_typus', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 48
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id48, 'name', 'name', 'fiswrv_wasserrechtliche_zulassungen_fassung_typus', 'fiswrv_wasserrechtliche_zulassungen_fassung_typus', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 48 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id48, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id48, @stelle_id_2, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 48 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id48, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id48, @stelle_id_4, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 48 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id48, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id48, @stelle_id_5, 'name', '0', '0');

-- Layer 20
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Wasserrechtliche_Zulassungen_Status', '', '5', @group_id, 'SELECT id, name FROM fiswrv_wasserrechtliche_zulassungen_status WHERE 1=1', 'fiswrv_wasserrechtliche_zulassungen_status', '', 'wasserrecht', '', '', '', '', 'id', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id20=LAST_INSERT_ID();

-- Zuordnung Layer 20 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id20, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 20 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id20, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 20 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id20, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut id des Layers 20
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id20, 'id', 'id', 'fiswrv_wasserrechtliche_zulassungen_status', 'fiswrv_wasserrechtliche_zulassungen_status', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 20
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id20, 'name', 'name', 'fiswrv_wasserrechtliche_zulassungen_status', 'fiswrv_wasserrechtliche_zulassungen_status', 'varchar', '', '', '1', '100', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 20 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id20, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id20, @stelle_id_2, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 20 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id20, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id20, @stelle_id_4, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 20 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id20, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id20, @stelle_id_5, 'name', '0', '0');

-- Layer 26
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Wasserrechtliche_Zulassungen_Typus', '', '5', @group_id, 'SELECT id, name FROM fiswrv_wasserrechtliche_zulassungen_typus WHERE 1=1', 'fiswrv_wasserrechtliche_zulassungen_typus', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id26=LAST_INSERT_ID();

-- Zuordnung Layer 26 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id26, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 26 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id26, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 26 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id26, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut id des Layers 26
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id26, 'id', 'id', 'fiswrv_wasserrechtliche_zulassungen_typus', 'fiswrv_wasserrechtliche_zulassungen_typus', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 26
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id26, 'name', 'name', 'fiswrv_wasserrechtliche_zulassungen_typus', 'fiswrv_wasserrechtliche_zulassungen_typus', 'varchar', '', '', '1', '100', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 26 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id26, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id26, @stelle_id_2, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 26 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id26, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id26, @stelle_id_4, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 26 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id26, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id26, @stelle_id_5, 'name', '0', '0');

-- Layer 32
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Wasserrechtliche_Zulassungen_Ungueltigkeit_Aufgrund', '', '5', @group_id, 'SELECT id, name FROM fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund WHERE 1=1', 'fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id32=LAST_INSERT_ID();

-- Zuordnung Layer 32 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id32, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 32 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id32, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 32 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id32, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut id des Layers 32
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id32, 'id', 'id', 'fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund', 'fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 32
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id32, 'name', 'name', 'fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund', 'fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 32 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id32, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id32, @stelle_id_2, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 32 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id32, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id32, @stelle_id_4, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 32 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id32, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id32, @stelle_id_5, 'name', '0', '0');

-- Layer 14
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Weeerklaerer', '', '5', @group_id, 'SELECT id, name FROM fiswrv_weeerklaerer WHERE 1=1', 'fiswrv_weeerklaerer ', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, NULL, '-1', NULL, '', '', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id14=LAST_INSERT_ID();

-- Zuordnung Layer 14 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id14, @stelle_id_2, '1', '0', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 14 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id14, @stelle_id_4, '1', '0', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 14 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id14, @stelle_id_5, '1', '0', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut id des Layers 14
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id14, 'id', 'id', 'fiswrv_weeerklaerer', 'fiswrv_weeerklaerer', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 14
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id14, 'name', 'name', 'fiswrv_weeerklaerer', 'fiswrv_weeerklaerer', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 14 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id14, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id14, @stelle_id_2, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 14 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id14, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id14, @stelle_id_4, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 14 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id14, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id14, @stelle_id_5, 'name', '0', '0');

-- Replace attribute options for Layer 3
UPDATE layer_attributes SET options = REPLACE(options, '3', @last_layer_id3) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 41
UPDATE layer_attributes SET options = REPLACE(options, '41', @last_layer_id41) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 39
UPDATE layer_attributes SET options = REPLACE(options, '39', @last_layer_id39) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 24
UPDATE layer_attributes SET options = REPLACE(options, '24', @last_layer_id24) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 33
UPDATE layer_attributes SET options = REPLACE(options, '33', @last_layer_id33) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 9
UPDATE layer_attributes SET options = REPLACE(options, '9', @last_layer_id9) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 25
UPDATE layer_attributes SET options = REPLACE(options, '25', @last_layer_id25) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 52
UPDATE layer_attributes SET options = REPLACE(options, '52', @last_layer_id52) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 34
UPDATE layer_attributes SET options = REPLACE(options, '34', @last_layer_id34) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 45
UPDATE layer_attributes SET options = REPLACE(options, '45', @last_layer_id45) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 37
UPDATE layer_attributes SET options = REPLACE(options, '37', @last_layer_id37) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 50
UPDATE layer_attributes SET options = REPLACE(options, '50', @last_layer_id50) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 51
UPDATE layer_attributes SET options = REPLACE(options, '51', @last_layer_id51) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 47
UPDATE layer_attributes SET options = REPLACE(options, '47', @last_layer_id47) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 36
UPDATE layer_attributes SET options = REPLACE(options, '36', @last_layer_id36) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 16
UPDATE layer_attributes SET options = REPLACE(options, '16', @last_layer_id16) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 6
UPDATE layer_attributes SET options = REPLACE(options, '6', @last_layer_id6) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 42
UPDATE layer_attributes SET options = REPLACE(options, '42', @last_layer_id42) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 43
UPDATE layer_attributes SET options = REPLACE(options, '43', @last_layer_id43) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 40
UPDATE layer_attributes SET options = REPLACE(options, '40', @last_layer_id40) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 30
UPDATE layer_attributes SET options = REPLACE(options, '30', @last_layer_id30) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 10
UPDATE layer_attributes SET options = REPLACE(options, '10', @last_layer_id10) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 11
UPDATE layer_attributes SET options = REPLACE(options, '11', @last_layer_id11) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 13
UPDATE layer_attributes SET options = REPLACE(options, '13', @last_layer_id13) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 46
UPDATE layer_attributes SET options = REPLACE(options, '46', @last_layer_id46) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 44
UPDATE layer_attributes SET options = REPLACE(options, '44', @last_layer_id44) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 31
UPDATE layer_attributes SET options = REPLACE(options, '31', @last_layer_id31) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 48
UPDATE layer_attributes SET options = REPLACE(options, '48', @last_layer_id48) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 20
UPDATE layer_attributes SET options = REPLACE(options, '20', @last_layer_id20) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 26
UPDATE layer_attributes SET options = REPLACE(options, '26', @last_layer_id26) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 32
UPDATE layer_attributes SET options = REPLACE(options, '32', @last_layer_id32) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 14
UPDATE layer_attributes SET options = REPLACE(options, '14', @last_layer_id14) WHERE layer_id IN (@last_layer_id3, @last_layer_id41, @last_layer_id39, @last_layer_id24, @last_layer_id33, @last_layer_id9, @last_layer_id25, @last_layer_id52, @last_layer_id34, @last_layer_id45, @last_layer_id37, @last_layer_id50, @last_layer_id51, @last_layer_id47, @last_layer_id36, @last_layer_id16, @last_layer_id6, @last_layer_id42, @last_layer_id43, @last_layer_id40, @last_layer_id30, @last_layer_id10, @last_layer_id11, @last_layer_id13, @last_layer_id46, @last_layer_id44, @last_layer_id31, @last_layer_id48, @last_layer_id20, @last_layer_id26, @last_layer_id32, @last_layer_id14) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

COMMIT;
