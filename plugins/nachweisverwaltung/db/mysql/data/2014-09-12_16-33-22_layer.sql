INSERT INTO `u_groups` (`Gruppenname`, `Gruppenname_low-german`, `Gruppenname_english`, `Gruppenname_polish`, `Gruppenname_vietnamese`, `obergruppe`, `order`) VALUES
('Nachweisverwaltung', NULL, NULL, NULL, NULL, NULL, 5);

SET @group_id = LAST_INSERT_ID();
SET @connection = 'user=xxxx password=xxxx dbname=kvwmapsp';


#################################################################
# Layer Grenzpunke verhandelt/nichtverhandelt und Punktnummern  #
#################################################################
# Eintragen eines Layers zur Darstellung des Rechtsstatus verhandelt/nicht verhandelt der Grenzpunkte des Liegenschaftskatasters
INSERT INTO layer (`Name`,`alias`,`Datentyp`,`Gruppe`,`pfad`,`maintable`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`,`metalink`,`privileg`) VALUES('Rechtsstatus','','0',@group_id,'SELECT *,st_x(the_geom) AS x,st_y(the_geom) AS y FROM fp_punkte WHERE art IN (2)','fp_punkte','the_geom from (select * from nachweisverwaltung.fp_punkte WHERE art IN (2)) as foo using unique pkz using srid=2398','nachweisverwaltung','','','','','pktnr','10000','0','',@connection,'','6','art','pkz','3','pixels','2398','nachweisverwaltung/view/Festpunkte.php','1',NULL,NULL,NULL,NULL,'','EPSG:2398','','1.1.0','image/png','60','','','','','0','0','','','','','0');
SET @last_layer_id781=LAST_INSERT_ID();
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('verhandelt',@last_layer_id781,'([art]=2 AND [verhandelt]=1)','1','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES('26','punkt','10','50 150 50','','0 0 0','5','10','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO labels (`font`,`type`,`color`,`outlinecolor`,`shadowcolor`,`shadowsizex`,`shadowsizey`,`backgroundcolor`,`backgroundshadowcolor`,`backgroundshadowsizex`,`backgroundshadowsizey`,`size`,`minsize`,`maxsize`,`position`,`offsetx`,`offsety`,`angle`,`autoangle`,`buffer`,`antialias`,`minfeaturesize`,`maxfeaturesize`,`partials`,`wrap`,`the_force`) VALUES('arial',NULL,'50 150 50','255 255 255','',NULL,NULL,'','',NULL,NULL,'10','5','10','4',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0');
 SET @last_label_id=LAST_INSERT_ID();
INSERT INTO u_labels2classes (label_id, class_id) VALUES (@last_label_id, @last_class_id);
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('nicht verhandelt',@last_layer_id781,'([art]=2 AND [verhandelt]=0)','1','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES('26','punkt','10','150 50 50','','0 0 0','5','10','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO labels (`font`,`type`,`color`,`outlinecolor`,`shadowcolor`,`shadowsizex`,`shadowsizey`,`backgroundcolor`,`backgroundshadowcolor`,`backgroundshadowsizex`,`backgroundshadowsizey`,`size`,`minsize`,`maxsize`,`position`,`offsetx`,`offsety`,`angle`,`autoangle`,`buffer`,`antialias`,`minfeaturesize`,`maxfeaturesize`,`partials`,`wrap`,`the_force`) VALUES('arial',NULL,'150 50 50','255 255 255','',NULL,NULL,'','',NULL,NULL,'10','5','10','4',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0');
 SET @last_label_id=LAST_INSERT_ID();
INSERT INTO u_labels2classes (label_id, class_id) VALUES (@last_label_id, @last_class_id);


###########################################
# Layer Grenzpunke vermarkt/nichtvermarkt #
###########################################

# Eintragen eines Layers zur Darstellung der Vermarkung der Grenzpunkte
INSERT INTO layer (`Name`,`alias`,`Datentyp`,`Gruppe`,`pfad`,`maintable`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`,`metalink`,`privileg`) VALUES('Abmarkung','','0',@group_id,'SELECT *,st_x(the_geom) AS x,st_y(the_geom) AS y FROM fp_punkte WHERE art IN (2)','fp_punkte','the_geom from (select * from nachweisverwaltung.fp_punkte WHERE art IN (2)) as foo using unique pkz using srid=2398','nachweisverwaltung','','','','','pktnr','10000','0','',@connection,'','6','art','pkz','3','pixels','2398','nachweisverwaltung/view/Festpunkte.php','1',NULL,NULL,NULL,NULL,'','EPSG:2398','','1.1.0','image/png','60','','','','','0','0','','','','','0');
SET @last_layer_id783=LAST_INSERT_ID();
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('vermarkt',@last_layer_id783,'(!(\'[vma]\'=\'070\' OR \'[vma]\'=\'071\' OR \'[vma]\'=\'073\' OR \'[vma]\'=\'088\' OR \'[vma]\'=\'089\' OR \'[vma]\'=\'090\' OR \'[vma]\'=\'091\' OR \'[vma]\'=\'093\' OR \'[vma]\'=\'095\'))','1','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'circle','8','255 255 255','0 255 0','0 0 0','6','8','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('nicht verhandelt',@last_layer_id783,'(\'[vma]\'=\'070\' OR \'[vma]\'=\'071\' OR \'[vma]\'=\'073\' OR \'[vma]\'=\'088\' OR \'[vma]\'=\'089\' OR \'[vma]\'=\'090\' OR \'[vma]\'=\'091\' OR \'[vma]\'=\'093\' OR \'[vma]\'=\'095\')','1','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'circle','3','0 0 0','255 255 255','0 0 0','1','3','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);

##########################################################
# Einträge für die Festpunkte des Liegenschaftskatasters #
##########################################################

# Eintragen eines Layers für die Festpunkte des Liegenschaftskatasters
INSERT INTO layer (`Name`,`alias`,`Datentyp`,`Gruppe`,`pfad`,`maintable`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`,`metalink`,`privileg`) VALUES('Festpunkte','','0',@group_id,'SELECT *,st_x(the_geom) AS x,st_y(the_geom) AS y FROM fp_punkte WHERE art IN (0,1,5,6)','fp_punkte','the_geom from nachweisverwaltung.fp_punkte using unique pkz using srid=2398','nachweisverwaltung','','','','','pktnr','25000','0','',@connection,'','6','art','pkz','3','pixels','2398','nachweisverwaltung/view/Festpunkte.php','1',NULL,NULL,NULL,NULL,'','EPSG:2398','','1.1.0','image/png','60','','','','','0','0','','','','','0');
SET @last_layer_id784=LAST_INSERT_ID();
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('TP',@last_layer_id784,'([art]=0)','1','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES('34','glseitstehdreieck','10','255 255 255','','0 50 150','5','10','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES('26','punkt','1','0 0 0','','0 50 150','1','2','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 1);
INSERT INTO labels (`font`,`type`,`color`,`outlinecolor`,`shadowcolor`,`shadowsizex`,`shadowsizey`,`backgroundcolor`,`backgroundshadowcolor`,`backgroundshadowsizex`,`backgroundshadowsizey`,`size`,`minsize`,`maxsize`,`position`,`offsetx`,`offsety`,`angle`,`autoangle`,`buffer`,`antialias`,`minfeaturesize`,`maxfeaturesize`,`partials`,`wrap`,`the_force`) VALUES('arial_underline_2',NULL,'0 50 150','255 255 255','',NULL,NULL,'','',NULL,NULL,'10','5','10','4',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1');
 SET @last_label_id=LAST_INSERT_ID();
INSERT INTO u_labels2classes (label_id, class_id) VALUES (@last_label_id, @last_class_id);
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('OP',@last_layer_id784,'([art]=6)','2','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES('35','glseitkopfdreieck','10','255 255 255','','0 50 150','5','10','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES('26','punkt','1','0 0 0','','0 50 150','1','2','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 1);
INSERT INTO labels (`font`,`type`,`color`,`outlinecolor`,`shadowcolor`,`shadowsizex`,`shadowsizey`,`backgroundcolor`,`backgroundshadowcolor`,`backgroundshadowsizex`,`backgroundshadowsizey`,`size`,`minsize`,`maxsize`,`position`,`offsetx`,`offsety`,`angle`,`autoangle`,`buffer`,`antialias`,`minfeaturesize`,`maxfeaturesize`,`partials`,`wrap`,`the_force`) VALUES('arial_underline_2',NULL,'0 50 150','255 255 255','',NULL,NULL,'','',NULL,NULL,'10','5','10','4',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1');
 SET @last_label_id=LAST_INSERT_ID();
INSERT INTO u_labels2classes (label_id, class_id) VALUES (@last_label_id, @last_class_id);
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('AP',@last_layer_id784,'([art]=1)','3','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES('32','filledcircle','12','255 255 255','','0 50 150','7','12','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES('26','punkt','1','0 0 0','','0 50 150','1','2','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 1);
INSERT INTO labels (`font`,`type`,`color`,`outlinecolor`,`shadowcolor`,`shadowsizex`,`shadowsizey`,`backgroundcolor`,`backgroundshadowcolor`,`backgroundshadowsizex`,`backgroundshadowsizey`,`size`,`minsize`,`maxsize`,`position`,`offsetx`,`offsety`,`angle`,`autoangle`,`buffer`,`antialias`,`minfeaturesize`,`maxfeaturesize`,`partials`,`wrap`,`the_force`) VALUES('arial_underline_1',NULL,'0 50 150','255 255 255','',NULL,NULL,'','',NULL,NULL,'10','5','10','4',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1');
 SET @last_label_id=LAST_INSERT_ID();
INSERT INTO u_labels2classes (label_id, class_id) VALUES (@last_label_id, @last_class_id);


########## Eintragen eines Layers für die Sicherungspunkte der AP´s (SiP)
INSERT INTO layer (`Name`,`alias`,`Datentyp`,`Gruppe`,`pfad`,`maintable`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`,`metalink`,`privileg`) VALUES('Sicherungspunkte','','0',@group_id,'','','the_geom from nachweisverwaltung.fp_punkte using unique pkz using srid=2398','nachweisverwaltung','','','','','','25000','0','',@connection,'','6','art','ID','3','pixels','2398','','0',NULL,NULL,NULL,NULL,'','EPSG:2398','','1.1.0','image/png','60','','','','','0','0','','','','','0');
SET @last_layer_id785=LAST_INSERT_ID();
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('SiP',@last_layer_id785,'([art]=5)','1','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES('32','filledcircle','8','255 255 255','','0 50 150','5','8','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);


########### Layer Nachweise 
INSERT INTO layer (`Name`,`alias`,`Datentyp`,`Gruppe`,`pfad`,`maintable`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`,`metalink`,`privileg`) VALUES('Nachweise','','2',@group_id,'SELECT id, flurid, stammnr, rissnummer, blattnummer, datum, vermstelle, gueltigkeit, link_datei, art, format,  fortfuehrung, bemerkungen, bearbeiter, zeit, erstellungszeit, the_geom FROM n_nachweise WHERE (1=1)','n_nachweise','the_geom from nachweisverwaltung.n_nachweise using unique id using srid=2398','nachweisverwaltung','','','','','',NULL,'0','',@connection,'','6','art','gueltigkeit','3','pixels','2398','nachweisverwaltung/view/nachweisrisse2.php','1',NULL,NULL,NULL,NULL,'','EPSG:2398','','1.1.0','image/png','60','','','','','0','0','','','','','0');
SET @last_layer_id786=LAST_INSERT_ID();
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id786,'art','art','n_nachweise','n_nachweise','bpchar','','','1',NULL,NULL,'','Auswahlfeld','select \'100\' as value, \'FFR\' as output
UNION
select \'010\' as value, \'KVZ\' as output
UNION
select \'001\' as value, \'GN\' as output
UNION
select \'111\' as value, \'andere\' as output','Art','','','','','','',NULL,NULL,'9','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id786,'bearbeiter','bearbeiter','n_nachweise','n_nachweise','varchar','','','1','50',NULL,'','User','','Bearbeiter','','','','','','',NULL,NULL,'13','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id786,'bemerkungen','bemerkungen','n_nachweise','n_nachweise','text','','','1',NULL,NULL,'','Text','','Bemerkungen','','','','','','',NULL,NULL,'12','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id786,'blattnummer','blattnummer','n_nachweise','n_nachweise','varchar','','','0',NULL,NULL,'','Text','','Blattnummer','','','','','','',NULL,NULL,'4','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id786,'datum','datum','n_nachweise','n_nachweise','date','','','1',NULL,NULL,'','Text','','Datum','','','','','','',NULL,NULL,'5','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id786,'erstellungszeit','erstellungszeit','n_nachweise','n_nachweise','timestamp','','','1',NULL,NULL,'SELECT \'2015-06-01 00:00:00\'::timestamp without time zone','Text','','erstellt am','','','','','','',NULL,NULL,'15','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id786,'flurid','flurid','n_nachweise','n_nachweise','int4','','','0','32','0','','Text','','Flur-ID','','','','','','',NULL,NULL,'1','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id786,'format','format','n_nachweise','n_nachweise','bpchar','','','1',NULL,NULL,'','Auswahlfeld','select \'A4\' as value, \'A4\' as output
UNION
select \'A3\' as value, \'A3\' as output
UNION
select \'SF\' as value, \'Sonderformat\' as output','Format','','','','','','',NULL,NULL,'10','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id786,'fortfuehrung','fortfuehrung','n_nachweise','n_nachweise','int4','','','1','32','0','','Text','','Fortführung','','','','','','',NULL,NULL,'11','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id786,'gueltigkeit','gueltigkeit','n_nachweise','n_nachweise','int4','','','1','32','0','','Auswahlfeld','select 1 as value, \'gültig\' as output
UNION
select 0 as value, \'ungültig\' as output','Gültigkeit','','','','','','',NULL,NULL,'7','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id786,'id','id','n_nachweise','n_nachweise','int4','','PRIMARY KEY','1','32','0','','Text','','ID','','','','','','',NULL,NULL,'0','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id786,'link_datei','link_datei','n_nachweise','n_nachweise','varchar','','','1',NULL,NULL,'','dynamicLink','index.php?go=document_anzeigen&ohnesession=1&id=$id&file=1;Dokument anzeigen','Dokument','','','','','','',NULL,NULL,'8','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id786,'rissnummer','rissnummer','n_nachweise','n_nachweise','varchar','','','1','20',NULL,'','Text','','Rissnummer','','','','','','',NULL,NULL,'3','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id786,'stammnr','stammnr','n_nachweise','n_nachweise','varchar','','','1','15',NULL,'','Text','','Auftragsnummer','','','','','','',NULL,NULL,'2','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id786,'the_geom','the_geom','n_nachweise','n_nachweise','geometry','POLYGON','','1',NULL,NULL,'','Geometrie','','','','','','','','',NULL,NULL,'16','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id786,'vermstelle','vermstelle','n_nachweise','n_nachweise','varchar','','','1',NULL,NULL,'','Auswahlfeld','select id as value, name as output from n_vermstelle order by name','Vermessungsstelle','','','','','','',NULL,NULL,'6','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id786,'zeit','zeit','n_nachweise','n_nachweise','timestamp','','','1',NULL,NULL,'','Time','','letzte Änderung','','','','','','',NULL,NULL,'14','0','0');
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('FFR',@last_layer_id786,'(\'[art]\'eq\'100\')','1','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES('0','','0','255 0 0','','0 0 0',NULL,NULL,'','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('KVZ',@last_layer_id786,'(\'[art]\'eq\'010\')','2','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES('0','','0','255 85 0','','0 0 0',NULL,NULL,'','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('GN',@last_layer_id786,'(\'[art]\'eq\'001\')','3','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES('0','','0','255 190 0','','0 0 0',NULL,NULL,'','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('andere',@last_layer_id786,'','4','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'','1','155 203 0','','',NULL,'1','360','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
 
COMMIT;