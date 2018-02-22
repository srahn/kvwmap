BEGIN;

SET @group_id = (SELECT id FROM `u_groups` WHERE `Gruppenname` LIKE 'Wasserwirtschaft' ORDER BY id LIMIT 1);
SET @connection = 'user=xxxx password=xxxx dbname=kvwmapsp';

-- Stelle Wasserrecht Dateneingeber (id=2)
SET @stelle_id_Wasserrecht_Dateneingeber_2 = $WASSERRECHT_STELLE_DATENEINGEBER;

-- Stelle Wasseerrecht Entscheider (id=4)
SET @stelle_id_Wasseerrecht_Entscheider_4 = $WASSERRECHT_STELLE_ENTSCHEIDER;

-- Stelle Wasserrecht Administration (id=5)
SET @stelle_id_Wasserrecht_Administration_5 = $WASSERRECHT_STELLE_ADMINISTRATION;

-- Layer 12
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('Adresse', '', '5', @group_id, 'SELECT id as adress_id, strasse, hausnummer, plz, ort FROM fiswrv_adresse WHERE 1=1', 'fiswrv_adresse', '', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', '-1', NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '0', '', '0');
SET @last_layer_id12=LAST_INSERT_ID();

-- Attribut adress_id des Layers 12
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id12, 'adress_id', 'id', 'fiswrv_adresse', 'fiswrv_adresse', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', '', '', '', '', '', '', '', NULL, '0', '0', '0', '0');

-- Zuordnung der Layerattribute des Layers 12 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'adress_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'hausnummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'ort', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'plz', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'strasse', '1', '0');

-- Zuordnung der Layerattribute des Layers 12 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'adress_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'hausnummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'ort', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'plz', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'strasse', '1', '0');

-- Zuordnung der Layerattribute des Layers 12 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'adress_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'plz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'strasse', '0', '0');

-- Attribut hausnummer des Layers 12
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id12, 'hausnummer', 'hausnummer', 'fiswrv_adresse', 'fiswrv_adresse', 'varchar', '', '', '1', '10', NULL, '', 'Text', '', 'Hausnummer', '', '', '', '', '', '', NULL, '0', '2', '0', '0');

-- Zuordnung der Layerattribute des Layers 12 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'adress_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'hausnummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'ort', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'plz', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'strasse', '1', '0');

-- Zuordnung der Layerattribute des Layers 12 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'adress_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'hausnummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'ort', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'plz', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'strasse', '1', '0');

-- Zuordnung der Layerattribute des Layers 12 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'adress_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'plz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'strasse', '0', '0');

-- Attribut ort des Layers 12
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id12, 'ort', 'ort', 'fiswrv_adresse', 'fiswrv_adresse', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Ort', '', '', '', '', '', '', NULL, '0', '4', '0', '0');

-- Zuordnung der Layerattribute des Layers 12 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'adress_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'hausnummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'ort', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'plz', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'strasse', '1', '0');

-- Zuordnung der Layerattribute des Layers 12 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'adress_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'hausnummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'ort', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'plz', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'strasse', '1', '0');

-- Zuordnung der Layerattribute des Layers 12 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'adress_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'plz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'strasse', '0', '0');

-- Attribut plz des Layers 12
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id12, 'plz', 'plz', 'fiswrv_adresse', 'fiswrv_adresse', 'varchar', '', '', '1', '100', NULL, '', 'Zahl', '', 'Postleitzahl', '', '', '', '', '', '', NULL, '0', '3', '0', '0');

-- Zuordnung der Layerattribute des Layers 12 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'adress_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'hausnummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'ort', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'plz', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'strasse', '1', '0');

-- Zuordnung der Layerattribute des Layers 12 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'adress_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'hausnummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'ort', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'plz', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'strasse', '1', '0');

-- Zuordnung der Layerattribute des Layers 12 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'adress_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'plz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'strasse', '0', '0');

-- Attribut strasse des Layers 12
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id12, 'strasse', 'strasse', 'fiswrv_adresse', 'fiswrv_adresse', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Straße', '', '', '', '', '', '', NULL, '0', '1', '0', '0');

-- Zuordnung der Layerattribute des Layers 12 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'adress_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'hausnummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'ort', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'plz', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Dateneingeber_2, 'strasse', '1', '0');

-- Zuordnung der Layerattribute des Layers 12 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'adress_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'hausnummer', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'ort', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'plz', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasseerrecht Entscheider_4, 'strasse', '1', '0');

-- Zuordnung der Layerattribute des Layers 12 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'adress_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'hausnummer', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'ort', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'plz', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id12, @stelle_id_Wasserrecht Administration_5, 'strasse', '0', '0');

-- Replace attribute options for Layer 12
UPDATE layer_attributes SET options = REPLACE(options, '12', @last_layer_id12) WHERE layer_id IN (@last_layer_id12) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');