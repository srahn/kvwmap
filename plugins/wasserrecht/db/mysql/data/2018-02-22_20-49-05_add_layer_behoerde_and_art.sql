BEGIN;

SET @group_id = (SELECT id FROM `u_groups` WHERE `Gruppenname` LIKE 'Wasserwirtschaft' ORDER BY id LIMIT 1);
SET @connection = 'user=xxxx password=xxxx dbname=kvwmapsp';

-- Stelle Wasserrecht Dateneingeber (id=2)
SET @stelle_id_2 = $WASSERRECHT_STELLE_DATENEINGEBER;

-- Stelle Wasseerrecht Entscheider (id=4)
SET @stelle_id_4 = $WASSERRECHT_STELLE_ENTSCHEIDER;

-- Stelle Wasserrecht Administration (id=5)
SET @stelle_id_5 = $WASSERRECHT_STELLE_ADMINISTRATION;

-- Layer 17
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Behoerde', '', '5', @group_id, 'SELECT id, name, abkuerzung, aktuell, adresse as adress_id, art, konto as konto_id FROM fiswrv_behoerde WHERE 1=1', 'fiswrv_behoerde', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '2', '', '0');
SET @last_layer_id17=LAST_INSERT_ID();

-- Zuordnung Layer 17 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id17, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 17 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id17, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 17 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id17, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Attribut abkuerzung des Layers 17
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id17, 'abkuerzung', 'abkuerzung', 'fiswrv_behoerde', 'fiswrv_behoerde', 'varchar', '', '', '1', '100', NULL, '', 'Text', '', 'Abkürzung', '', '', '', '', '', '', NULL, '0', '2', '1', '0');

-- Attribut adress_id des Layers 17
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id17, 'adress_id', 'adresse', 'fiswrv_behoerde', 'fiswrv_behoerde', 'int4', '', '', '1', '32', '0', '', 'Autovervollständigungsfeld', 'SELECT id as value, ort||\' \'||plz||\' \'||strasse||\' \'||hausnummer as output from wasserrecht.fiswrv_adresse where 1=1;layer_id=12 embedded', 'Adresse', '', '', '', '', '', '', NULL, '0', '4', '1', '0');

-- Attribut aktuell des Layers 17
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id17, 'aktuell', 'aktuell', 'fiswrv_behoerde', 'fiswrv_behoerde', 'bool', '', '', '1', NULL, NULL, 'SELECT true', 'Auswahlfeld', 'select \'ja\' as output, true as value
union
select \'nein\' as output, false as value', 'Aktuell', '', '', '', '', 'Behörde aktuell?', '', NULL, '0', '3', '1', '0');

-- Attribut art des Layers 17
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id17, 'art', 'art', 'fiswrv_behoerde', 'fiswrv_behoerde', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_behoerde_art;layer_id=49 embedded', 'Art', '', '', '', '', '', '', NULL, '0', '5', '1', '0');

-- Attribut id des Layers 17
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id17, 'id', 'id', 'fiswrv_behoerde', 'fiswrv_behoerde', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', 'ID', '', '', '', '', '', '', NULL, '-1', '0', '0', '0');

-- Attribut konto_id des Layers 17
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id17, 'konto_id', 'konto', 'fiswrv_behoerde', 'fiswrv_behoerde', 'int4', '', '', '1', '32', '0', '', 'Autovervollständigungsfeld', 'SELECT id as value, name||\' \'||iban||\' \'||bic||\' \'||verwendungszweck||\' \'||personenkonto||\' \'||kassenzeichen as output from wasserrecht.fiswrv_konto where 1=1;layer_id=16 embedded', 'Konto', '', '', '', '', '', '', NULL, '0', '6', '1', '0');

-- Attribut name des Layers 17
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id17, 'name', 'name', 'fiswrv_behoerde', 'fiswrv_behoerde', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '1', '0');

-- Zuordnung der Layerattribute des Layers 17 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id17, @stelle_id_2, 'abkuerzung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id17, @stelle_id_2, 'adress_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id17, @stelle_id_2, 'aktuell', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id17, @stelle_id_2, 'art', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id17, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id17, @stelle_id_2, 'konto_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id17, @stelle_id_2, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 17 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id17, @stelle_id_4, 'abkuerzung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id17, @stelle_id_4, 'adress_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id17, @stelle_id_4, 'aktuell', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id17, @stelle_id_4, 'art', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id17, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id17, @stelle_id_4, 'konto_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id17, @stelle_id_4, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 17 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id17, @stelle_id_5, 'abkuerzung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id17, @stelle_id_5, 'adress_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id17, @stelle_id_5, 'aktuell', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id17, @stelle_id_5, 'art', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id17, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id17, @stelle_id_5, 'konto_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id17, @stelle_id_5, 'name', '1', '0');

-- Layer 49
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Behoerde_Art', '', '5', @group_id, 'SELECT id, name, abkuerzung FROM fiswrv_behoerde_art WHERE 1=1', 'fiswrv_behoerde_art', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id49=LAST_INSERT_ID();

-- Zuordnung Layer 49 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id49, @stelle_id_2, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 49 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id49, @stelle_id_4, '1', '100', NULL, '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 49 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id49, @stelle_id_5, '1', '100', '0', '-1', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '0', '1', '0', '1');

-- Attribut abkuerzung des Layers 49
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id49, 'abkuerzung', 'abkuerzung', 'fiswrv_behoerde_art', 'fiswrv_behoerde_art', 'varchar', '', '', '1', '100', NULL, '', 'Text', '', 'Abkürzung', '', '', '', '', '', '', NULL, '0', '2', '0', '0');

-- Attribut id des Layers 49
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id49, 'id', 'id', 'fiswrv_behoerde_art', 'fiswrv_behoerde_art', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', 'ID', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Attribut name des Layers 49
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id49, 'name', 'name', 'fiswrv_behoerde_art', 'fiswrv_behoerde_art', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 49 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id49, @stelle_id_2, 'abkuerzung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id49, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id49, @stelle_id_2, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 49 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id49, @stelle_id_4, 'abkuerzung', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id49, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id49, @stelle_id_4, 'name', '1', '0');

-- Zuordnung der Layerattribute des Layers 49 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id49, @stelle_id_5, 'abkuerzung', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id49, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id49, @stelle_id_5, 'name', '0', '0');

-- Replace attribute options for Layer 17
UPDATE layer_attributes SET options = REPLACE(options, '17', @last_layer_id17) WHERE layer_id IN (@last_layer_id17, @last_layer_id49) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

-- Replace attribute options for Layer 49
UPDATE layer_attributes SET options = REPLACE(options, '49', @last_layer_id49) WHERE layer_id IN (@last_layer_id17, @last_layer_id49) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

COMMIT;
