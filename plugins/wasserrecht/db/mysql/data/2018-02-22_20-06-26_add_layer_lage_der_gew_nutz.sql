BEGIN;

SET @group_id = (SELECT id FROM `u_groups` WHERE `Gruppenname` LIKE 'Wasserwirtschaft' ORDER BY id LIMIT 1);
SET @connection = 'user=xxxx password=xxxx dbname=kvwmapsp';

-- Stelle Wasserrecht Dateneingeber (id=2)
SET @stelle_id_2 = $WASSERRECHT_STELLE_DATENEINGEBER;

-- Stelle Wasseerrecht Entscheider (id=4)
SET @stelle_id_4 = $WASSERRECHT_STELLE_ENTSCHEIDER;

-- Stelle Wasserrecht Administration (id=5)
SET @stelle_id_5 = $WASSERRECHT_STELLE_ADMINISTRATION;

-- Layer 38
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('FisWrV-WRe Lage der Gewaesserbenutzungen', '', '0', @group_id, 'SELECT COALESCE(e.name,\'\') AS anlage_klasse, d.name as anlage_link, d.id AS anlage_id, c.bezeichnung AS wrz_link, c.id AS wrz_id, b.bezeichnung AS gewaesserbenutzungen_link, a.id, a.name, a.gewaesserbenutzungen as gwb_id, a.freigegeben, a.the_geo, a.bearbeiter_name , a.bearbeiter_id, a.stelle_name, a.stelle_id, a.bearbeitungs_datum FROM fiswrv_gewaesserbenutzungen_lage a LEFT JOIN fiswrv_gewaesserbenutzungen_bezeichnung b ON b.id = a.gewaesserbenutzungen LEFT JOIN fiswrv_wasserrechtliche_zulassungen_bezeichnung c ON b.wasserrechtliche_zulassungen = c.id LEFT JOIN fiswrv_anlagen d ON c.anlage=d.id LEFT JOIN fiswrv_anlagen_klasse e ON d.klasse=e.id WHERE 1=1', 'fiswrv_gewaesserbenutzungen_lage', 'the_geo FROM (SELECT oid, * from wasserrecht.fiswrv_gewaesserbenutzungen_lage) as foo using unique oid using srid=35833', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', NULL, NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '2', '', '0');
SET @last_layer_id38=LAST_INSERT_ID();

-- Zuordnung Layer 38 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id38, @stelle_id_2, '1', '100', NULL, '0', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 38 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id38, @stelle_id_4, '1', '100', NULL, '0', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 38 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id38, @stelle_id_5, '1', '100', '0', '0', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Attribut anlage_id des Layers 38
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id38, 'anlage_id', 'id', 'fiswrv_anlagen', 'd', 'int4', '', '', '1', '32', '0', '', 'Text', '', 'Primärschlüssel ANL', '', '', '', '', '', 'Wasserrechtlich relevante Anlage', NULL, '-1', '2', '0', '0');

-- Attribut anlage_klasse des Layers 38
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id38, 'anlage_klasse', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'Text', '', 'Klasse ANL', '', '', '', '', 'Klasse aus der Anlagentabelle.', 'Wasserrechtlich relevante Anlage', NULL, '-1', '0', '0', '0');

-- Attribut anlage_link des Layers 38
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id38, 'anlage_link', 'name', 'fiswrv_anlagen', 'd', 'varchar', '', '', '0', '255', NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=2&value_anlage_id=$anlage_id&operator_anlage_id==;$anlage_link;no_new_window', 'Name ANL', '', '', '', '', '', 'Wasserrechtlich relevante Anlage', NULL, '-1', '1', '0', '0');

-- Attribut bearbeiter_id des Layers 38
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id38, 'bearbeiter_id', 'bearbeiter_id', 'fiswrv_gewaesserbenutzungen_lage', 'a', 'varchar', '', '', '1', '10', NULL, '', 'UserID', '', 'Bearbeiter ID', '', '', '', '', '', 'letzte Änderung', NULL, '0', '12', '0', '0');

-- Attribut bearbeiter_name des Layers 38
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id38, 'bearbeiter_name', 'bearbeiter_name', 'fiswrv_gewaesserbenutzungen_lage', 'a', 'varchar', '', '', '1', '255', NULL, '', 'User', '', 'Bearbeiter Name', '', '', '', '', 'Name des Benutzers, der als letztes eine Änderung gespeichert hat.', 'letzte Änderung', NULL, '0', '11', '0', '0');

-- Attribut bearbeitungs_datum des Layers 38
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id38, 'bearbeitungs_datum', 'bearbeitungs_datum', 'fiswrv_gewaesserbenutzungen_lage', 'a', 'date', '', '', '1', NULL, NULL, '', 'Time', '', 'Datum und Uhrzeit', '', '', '', '', '', 'letzte Änderung', NULL, '0', '15', '0', '0');

-- Attribut freigegeben des Layers 38
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id38, 'freigegeben', 'freigegeben', 'fiswrv_gewaesserbenutzungen_lage', 'a', 'bool', '', '', '1', NULL, NULL, 'SELECT false', 'Auswahlfeld', 'select \'ja\' as output, true as value
union
select \'nein\' as output, false as value', 'Freigegeben', '', '', '', '', 'Lage der Gewässerbenutzung freigegeben?', 'Historienverwaltung', NULL, '0', '9', '1', '0');

-- Attribut gewaesserbenutzungen_link des Layers 38
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id38, 'gewaesserbenutzungen_link', 'bezeichnung', 'fiswrv_gewaesserbenutzungen_bezeichnung', 'b', 'text', '', '', '1', NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=33&value_gwb_id=$gwb_id&operator_gwb_id==;$gewaesserbenutzungen_link;no_new_window', 'Zugelassene Benutzungen', '', '', '', '', '', 'Zugelassene Benutzungen', NULL, '-1', '5', '0', '0');

-- Attribut gwb_id des Layers 38
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id38, 'gwb_id', 'gewaesserbenutzungen', 'fiswrv_gewaesserbenutzungen_lage', 'a', 'int4', '', '', '0', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, kennnummer as output from wasserrecht.fiswrv_gewaesserbenutzungen;', '<strong>Gewässerbenutzung</strong>', '', '', '', '', 'Zugehörige Gewässerbenutzung', '<h1>Lage der Benutzung</h1>', NULL, '0', '8', '1', '0');

-- Attribut id des Layers 38
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id38, 'id', 'id', 'fiswrv_gewaesserbenutzungen_lage', 'a', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', 'Primärschlüssel Lage der Gewässerbenutzung', '', '', '', '', '', '<h1>Lage der Benutzung</h1>', NULL, '0', '6', '0', '0');

-- Attribut name des Layers 38
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id38, 'name', 'name', 'fiswrv_gewaesserbenutzungen_lage', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', 'Name', '', '', '', '', '', '<h1>Lage der Benutzung</h1>', NULL, '0', '7', '1', '0');

-- Attribut stelle_id des Layers 38
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id38, 'stelle_id', 'stelle_id', 'fiswrv_gewaesserbenutzungen_lage', 'a', 'varchar', '', '', '1', '10', NULL, '', 'StelleID', '', 'Stelle ID', '', '', '', '', '', 'letzte Änderung', NULL, '0', '14', '0', '0');

-- Attribut stelle_name des Layers 38
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id38, 'stelle_name', 'stelle_name', 'fiswrv_gewaesserbenutzungen_lage', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Stelle', '', 'Stelle', '', '', '', '', 'Stelle des Benutzers, der als letztes eine Änderung gespeichert hat.', 'letzte Änderung', NULL, '0', '13', '0', '0');

-- Attribut the_geo des Layers 38
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id38, 'the_geo', 'the_geo', 'fiswrv_gewaesserbenutzungen_lage', 'a', 'geometry', 'POINT', '', '1', NULL, NULL, '', 'Geometrie', '', 'Geometrie', '', '', '', '', '', 'Geometrie', NULL, '0', '10', '1', '0');

-- Attribut wrz_id des Layers 38
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id38, 'wrz_id', 'id', 'fiswrv_wasserrechtliche_zulassungen_bezeichnung', 'c', 'int4', '', '', '1', '32', '0', '', 'Text', '', 'Primärschlüssel WrZ', '', '', '', '', '', 'Wasserrechtliche Zulassungen', NULL, '-1', '4', '0', '0');

-- Attribut wrz_link des Layers 38
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id38, 'wrz_link', 'bezeichnung', 'fiswrv_wasserrechtliche_zulassungen_bezeichnung', 'c', 'text', '', '', '1', NULL, NULL, '', 'dynamicLink', 'index.php?go=Layer-Suche_Suchen&selected_layer_id=25&value_wrz_id=$wrz_id&operator_wrz_id==;$wrz_link;no_new_window', 'WrZ', '', '', '', '', '', 'Wasserrechtliche Zulassungen', NULL, '-1', '3', '0', '0');

-- Zuordnung der Layerattribute des Layers 38 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_2, 'anlage_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_2, 'anlage_klasse', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_2, 'anlage_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_2, 'bearbeiter_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_2, 'bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_2, 'bearbeitungs_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_2, 'freigegeben', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_2, 'gewaesserbenutzungen_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_2, 'gwb_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_2, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_2, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_2, 'stelle_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_2, 'stelle_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_2, 'the_geo', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_2, 'wrz_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_2, 'wrz_link', '0', '0');

-- Zuordnung der Layerattribute des Layers 38 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_4, 'anlage_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_4, 'anlage_klasse', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_4, 'anlage_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_4, 'bearbeiter_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_4, 'bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_4, 'bearbeitungs_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_4, 'freigegeben', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_4, 'gewaesserbenutzungen_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_4, 'gwb_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_4, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_4, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_4, 'stelle_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_4, 'stelle_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_4, 'the_geo', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_4, 'wrz_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_4, 'wrz_link', '0', '0');

-- Zuordnung der Layerattribute des Layers 38 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_5, 'anlage_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_5, 'anlage_klasse', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_5, 'anlage_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_5, 'bearbeiter_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_5, 'bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_5, 'bearbeitungs_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_5, 'freigegeben', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_5, 'gewaesserbenutzungen_link', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_5, 'gwb_id', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_5, 'id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_5, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_5, 'stelle_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_5, 'stelle_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_5, 'the_geo', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_5, 'wrz_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id38, @stelle_id_5, 'wrz_link', '0', '0');

-- Class 9 des Layers 38
INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`, `text`) VALUES ('alle', @last_layer_id38, '', '1', '');
SET @last_class_id=LAST_INSERT_ID();

-- Style 9 der Class 9
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`, `antialias`, `width`, `minwidth`, `maxwidth`, `sizeitem`) VALUES (NULL, 'circle', '6', '255 255 255', '', '0 0 0', NULL, '8', '360', '', NULL, NULL, NULL, NULL, '');
SET @last_style_id=LAST_INSERT_ID();
-- Zuordnung Style 9 zu Class 9
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);

-- Replace attribute options for Layer 38
UPDATE layer_attributes SET options = REPLACE(options, '38', @last_layer_id38) WHERE layer_id IN (@last_layer_id38) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

COMMIT;