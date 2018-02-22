BEGIN;

SET @group_id = (SELECT id FROM `u_groups` WHERE `Gruppenname` LIKE 'Wasserwirtschaft' ORDER BY id LIMIT 1);
SET @connection = 'user=xxxx password=xxxx dbname=kvwmapsp';

-- Stelle Wasserrecht Dateneingeber (id=2)
SET @stelle_id_2 = $WASSERRECHT_STELLE_DATENEINGEBER;

-- Stelle Wasseerrecht Entscheider (id=4)
SET @stelle_id_4 = $WASSERRECHT_STELLE_ENTSCHEIDER;

-- Stelle Wasserrecht Administration (id=5)
SET @stelle_id_5 = $WASSERRECHT_STELLE_ADMINISTRATION;

-- Layer 2
INSERT INTO layer (`Name`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync`) VALUES ('FisWrV-WRe Anlagen', '', '0', @group_id, 'SELECT a.id AS anlage_id, a.name, a.klasse, a.zustaend_stalu, a.zustaend_uwb, a.betreiber, a.abwasser_koerperschaft, a.trinkwasser_koerperschaft, \'\' AS wasserrechtliche_zulassungen, true AS aktuell, \'\' AS gewaesserbenutzungen,  a.bearbeiter_name , a.bearbeiter_id, a.stelle_name, a.stelle_id, a.bearbeitungs_datum, a.kommentar, a.objektid_geodin, a.the_geom FROM fiswrv_anlagen a WHERE 1=1', 'fiswrv_anlagen', 'the_geom from (select oid, * from wasserrecht.fiswrv_anlagen) as foo using unique oid using srid=35833', 'wasserrecht', '', '', '', '', 'name', NULL, NULL, '', @connection, '', '6', '', 'id', '10', 'pixels', '35833', '', '1', NULL, '100', NULL, NULL, '', 'epsg:35833', '', '', '', '60', '', '', '', '', '0', '0', '', '', '', '', '2', '', '0');
SET @last_layer_id2=LAST_INSERT_ID();

-- Zuordnung Layer 2 zu Stelle 2
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id2, @stelle_id_2, '1', '100', NULL, '0', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 2 zu Stelle 4
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id2, @stelle_id_4, '1', '100', NULL, '0', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Zuordnung Layer 2 zu Stelle 5
INSERT INTO used_layer (`Layer_ID`, `Stelle_ID`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`, `start_aktiv`, `use_geom`) VALUES (@last_layer_id2, @stelle_id_5, '1', '100', '0', '0', '0', '', NULL, '0', '', '', '', '', '0', NULL, '0', '2', '1', '0', '1');

-- Attribut abwasser_koerperschaft des Layers 2
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id2, 'abwasser_koerperschaft', 'abwasser_koerperschaft', 'fiswrv_anlagen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
	z.id as value, z.name || \' (\' || coalesce(string_agg(z2b.name, \', \'),  \'keiner Körperschaftsart zugeordnet\') || \')\' AS output FROM
  fiswrv_koerperschaft z LEFT JOIN	
  fiswrv_koerperschaft_art z2b ON z.art = z2b.id WHERE z2b.id=2
GROUP BY
  z.id, z.name ;layer_id=6 embedded', 'Abwasserbeseitigungspflichtige Körperschaft', '', '', '', '', '', 'Zuständigkeiten', NULL, '0', '6', '1', '0');

-- Attribut aktuell des Layers 2
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id2, 'aktuell', 'true', '', '', '', '', '', NULL, NULL, NULL, '', 'Autovervollständigungsfeld', 'select \'Aktuelle:\' as output, true as value', 'Aktuell', '', '', '', '', '', '<h2>Wasserrechtliche Zulassungen</h2>', NULL, '-1', '9', '0', '0');

-- Attribut anlage_id des Layers 2
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id2, 'anlage_id', 'id', 'fiswrv_anlagen', 'a', 'int4', '', 'PRIMARY KEY', '1', '32', '0', '', 'Text', '', 'Primärschlüssel ANL', '', '', '', '', '', 'Stammdaten Anlage', NULL, '0', '0', '0', '0');

-- Attribut bearbeiter_id des Layers 2
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id2, 'bearbeiter_id', 'bearbeiter_id', 'fiswrv_anlagen', 'a', 'varchar', '', '', '1', '10', NULL, '', 'UserID', '', 'Bearbeiter ID', '', '', '', '', '', 'letzte Änderung', NULL, '0', '12', '0', '0');

-- Attribut bearbeiter_name des Layers 2
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id2, 'bearbeiter_name', 'bearbeiter_name', 'fiswrv_anlagen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'User', '', 'Bearbeiter Name', '', '', '', '', 'Name des Benutzers, der als letztes eine Änderung gespeichert hat.', 'letzte Änderung', NULL, '0', '11', '0', '0');

-- Attribut bearbeitungs_datum des Layers 2
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id2, 'bearbeitungs_datum', 'bearbeitungs_datum', 'fiswrv_anlagen', 'a', 'date', '', '', '1', NULL, NULL, '', 'Time', '', 'Datum und Uhrzeit', '', '', '', '', '', 'letzte Änderung', NULL, '0', '15', '0', '0');

-- Attribut betreiber des Layers 2
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id2, 'betreiber', 'betreiber', 'fiswrv_anlagen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from wasserrecht.fiswrv_personen WHERE betreiber IS NOT NULL;layer_id=9 embedded', 'Betreiber', '', '', '', '', '', 'Zuständigkeiten', NULL, '0', '5', '1', '0');

-- Attribut gewaesserbenutzungen des Layers 2
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id2, 'gewaesserbenutzungen', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '33,anlage_id,aktuell,<b>Benutzung:</b> bezeichnung;no_new_window', 'Benutzungen', '', '', '', '', '', '<h2>Wasserrechtliche Zulassungen</h2>', NULL, '0', '10', '1', '0');

-- Attribut klasse des Layers 2
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id2, 'klasse', 'klasse', 'fiswrv_anlagen', 'a', 'int4', '', '', '0', '32', '0', '', 'Auswahlfeld', 'SELECT id as value, name as output from fiswrv_anlagen_klasse;layer_id=3 embedded', '<strong>Klasse ANL</strong>', '', '', '', '', 'PFLICHTFELD! Auswahlfeld für die Klasse der wasserrechtlich relevanten Anlage.  [Neue Klassen müssen beim Admin beantragt werden]', 'Stammdaten Anlage', NULL, '0', '2', '1', '0');

-- Attribut kommentar des Layers 2
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id2, 'kommentar', 'kommentar', 'fiswrv_anlagen', 'a', 'text', '', '', '1', NULL, NULL, '', 'Textfeld', '', 'Kommentar ans LUNG', '', '', '', '', '', 'letzte Änderung', NULL, '0', '16', '1', '0');

-- Attribut name des Layers 2
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id2, 'name', 'name', 'fiswrv_anlagen', 'a', 'varchar', '', '', '0', '255', NULL, '', 'Text', '', '<strong>Name ANL</strong>', '', '', '', '', 'PFLICHTFELD! Name der wasserrechtlich relevanten Anlage. (Beispiel: Kläranlage Musterow, Wasserwerk Musterin, Holzwerk Musterstadt)', 'Stammdaten Anlage', NULL, '0', '1', '1', '0');

-- Attribut objektid_geodin des Layers 2
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id2, 'objektid_geodin', 'objektid_geodin', 'fiswrv_anlagen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Text', '', '', '', '', '', '', '', 'Geometrie', NULL, '-1', '17', '0', '0');

-- Attribut stelle_id des Layers 2
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id2, 'stelle_id', 'stelle_id', 'fiswrv_anlagen', 'a', 'varchar', '', '', '1', '10', NULL, '', 'StelleID', '', 'Stelle ID', '', '', '', '', '', 'letzte Änderung', NULL, '0', '14', '0', '0');

-- Attribut stelle_name des Layers 2
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id2, 'stelle_name', 'stelle_name', 'fiswrv_anlagen', 'a', 'varchar', '', '', '1', '255', NULL, '', 'Stelle', '', 'Stelle', '', '', '', '', 'Stelle des Benutzers, der als letztes eine Änderung gespeichert hat.', 'letzte Änderung', NULL, '0', '13', '0', '0');

-- Attribut the_geom des Layers 2
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id2, 'the_geom', 'the_geom', 'fiswrv_anlagen', 'a', 'geometry', 'POINT', '', '1', NULL, NULL, '', 'Geometrie', '', 'Geometrie', '', '', '', '', '', 'Geometrie', NULL, '0', '18', '1', '0');

-- Attribut trinkwasser_koerperschaft des Layers 2
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id2, 'trinkwasser_koerperschaft', 'trinkwasser_koerperschaft', 'fiswrv_anlagen', 'a', 'int4', '', '', '1', '32', '0', '', 'Auswahlfeld', 'SELECT
	z.id as value, z.name || \' (\' || coalesce(string_agg(z2b.name, \', \'),  \'keiner Körperschaftsart zugeordnet\') || \')\' AS output FROM
  fiswrv_koerperschaft z LEFT JOIN	
  fiswrv_koerperschaft_art z2b ON z.art = z2b.id WHERE z2b.id=1
GROUP BY
  z.id, z.name ;layer_id=6 embedded', 'Träger der öffentlichen Wasserversorgung', '', '', '', '', '', 'Zuständigkeiten', NULL, '0', '7', '1', '0');

-- Attribut wasserrechtliche_zulassungen des Layers 2
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id2, 'wasserrechtliche_zulassungen', '', '', '', 'not_saveable', '', '', NULL, NULL, NULL, '', 'SubFormEmbeddedPK', '25,anlage_id, <b>Wasserrechtliche Zulassung ( aktuell ):</b> bezeichnung;no_new_window', 'WrZ', '', '', '', '', '', '<h2>Wasserrechtliche Zulassungen</h2>', NULL, '0', '8', '0', '0');

-- Attribut zustaend_stalu des Layers 2
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id2, 'zustaend_stalu', 'zustaend_stalu', 'fiswrv_anlagen', 'a', 'int4', '', '', '0', '32', '0', '', 'Autovervollständigungsfeld', 'SELECT id as value, abkuerzung AS output FROM fiswrv_behoerde a WHERE a.art=2;layer_id=17 embedded', '<strong>Amtsbereich StALU</strong>', '', '', '', '', 'PFLICHTFELD! AUTOVERVOLLSTÄNDIGKEITSFELD für das Staatliche Amt für Landwirtschaft und Umwelt in dessen Amtsbereich die Anlage liegt.

HILFE: % und _ entsprechen * und ? als Platzhalter bei der Namenssuche.', 'Zuständigkeiten', NULL, '0', '3', '1', '0');

-- Attribut zustaend_uwb des Layers 2
INSERT INTO layer_attributes (`layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, `query_tooltip`) VALUES (@last_layer_id2, 'zustaend_uwb', 'zustaend_uwb', 'fiswrv_anlagen', 'a', 'int4', '', '', '0', '32', '0', '', 'Autovervollständigungsfeld', 'SELECT id as value, abkuerzung AS output FROM fiswrv_behoerde a WHERE a.art=1;layer_id=17 embedded', '<strong>Amtsbereich UWB</strong>', '', '', '', '', 'PFLICHTFELD! AUTOVERVOLLSTÄNDIGKEITSFELD für fir Untere Wasserbehörde in deren Amtsbereich die Anlage liegt.

HILFE: % und _ entsprechen * und ? als Platzhalter bei der Namenssuche.', 'Zuständigkeiten', NULL, '0', '4', '1', '0');

-- Zuordnung der Layerattribute des Layers 2 zur Stelle 2
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_2, 'abwasser_koerperschaft', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_2, 'aktuell', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_2, 'anlage_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_2, 'bearbeiter_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_2, 'bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_2, 'bearbeitungs_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_2, 'betreiber', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_2, 'gewaesserbenutzungen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_2, 'klasse', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_2, 'kommentar', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_2, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_2, 'objektid_geodin', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_2, 'stelle_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_2, 'stelle_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_2, 'the_geom', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_2, 'trinkwasser_koerperschaft', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_2, 'wasserrechtliche_zulassungen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_2, 'zustaend_stalu', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_2, 'zustaend_uwb', '1', '0');

-- Zuordnung der Layerattribute des Layers 2 zur Stelle 4
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_4, 'abwasser_koerperschaft', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_4, 'aktuell', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_4, 'anlage_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_4, 'bearbeiter_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_4, 'bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_4, 'bearbeitungs_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_4, 'betreiber', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_4, 'gewaesserbenutzungen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_4, 'klasse', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_4, 'kommentar', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_4, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_4, 'objektid_geodin', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_4, 'stelle_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_4, 'stelle_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_4, 'the_geom', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_4, 'trinkwasser_koerperschaft', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_4, 'wasserrechtliche_zulassungen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_4, 'zustaend_stalu', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_4, 'zustaend_uwb', '1', '0');

-- Zuordnung der Layerattribute des Layers 2 zur Stelle 5
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_5, 'abwasser_koerperschaft', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_5, 'aktuell', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_5, 'anlage_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_5, 'bearbeiter_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_5, 'bearbeiter_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_5, 'bearbeitungs_datum', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_5, 'betreiber', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_5, 'gewaesserbenutzungen', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_5, 'klasse', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_5, 'kommentar', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_5, 'name', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_5, 'objektid_geodin', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_5, 'stelle_id', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_5, 'stelle_name', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_5, 'the_geom', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_5, 'trinkwasser_koerperschaft', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_5, 'wasserrechtliche_zulassungen', '0', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_5, 'zustaend_stalu', '1', '0');
INSERT INTO layer_attributes2stelle (`layer_id`, `stelle_id`, `attributename`, `privileg`, `tooltip`) VALUES (@last_layer_id2, @stelle_id_5, 'zustaend_uwb', '1', '0');

-- Class 1 des Layers 2
INSERT INTO classes (`Name`, `Layer_ID`, `Expression`, `drawingorder`, `text`) VALUES ('alle', @last_layer_id2, '', '1', '');
SET @last_class_id=LAST_INSERT_ID();

-- Style 1 der Class 1
INSERT INTO styles (`symbol`, `symbolname`, `size`, `color`, `backgroundcolor`, `outlinecolor`, `minsize`, `maxsize`, `angle`, `angleitem`, `antialias`, `width`, `minwidth`, `maxwidth`, `sizeitem`) VALUES (NULL, 'circle', '8', '30 149 255', '', '0 0 0', NULL, '10', '360', '', NULL, NULL, NULL, NULL, '');
SET @last_style_id=LAST_INSERT_ID();
-- Zuordnung Style 1 zu Class 1
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);

-- Replace attribute options for Layer 2
UPDATE layer_attributes SET options = REPLACE(options, '2', @last_layer_id2) WHERE layer_id IN (@last_layer_id2) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');

COMMIT;
