-- Flaechenlayer "BORIS" für die Darstellung der BRWzonen

SET @group_id = 1;
SET @connection = 'user=xxxx password=xxxx dbname=kvwmapsp';
INSERT INTO layer (`Name`,`Datentyp`,`Gruppe`,`pfad`,`Data`,`schema`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`) VALUES('BORIS','2',@group_id,'SELECT oid, zonennr, standort, richtwertdefinition, gemeinde, gemarkung, ortsteilname, postleitzahl, zonentyp, gutachterausschuss, bodenrichtwertnummer, oertliche_bezeichnung, bodenrichtwert, stichtag, basiskarte, entwicklungszustand, beitragszustand, nutzungsart, ergaenzende_nutzung, bauweise, geschosszahl, grundflaechenzahl, geschossflaechenzahl, baumassenzahl, flaeche, tiefe, breite, wegeerschliessung, ackerzahl, gruenlandzahl, aufwuchs, verfahrensgrund, verfahrensgrund_zusatz, bemerkungen, the_geom FROM bw_zonen WHERE (1=1)','the_geom from (select * from bw_boris_view) as foo using unique oid using srid=25833','','','','','',NULL,NULL,'',@connection,NULL,'6','zonentyp','','3','pixels','25833','Bodenrichtwerte.php','1',NULL,NULL,NULL,NULL,'','EPSG:25833','BORIS MV','1.1.0','image/png','60','','','0','');
SET @last_layer_id=LAST_INSERT_ID();
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('alle',@last_layer_id,NULL,'1','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES('9','','2','-1 -1 -1','','224 92 14','2','4','360','',NULL,NULL,NULL,NULL,'');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id) VALUES (@last_style_id, @last_class_id);

INSERT INTO `layer_attributes` VALUES (@last_layer_id, 'gemarkung', 'Auswahlfeld', 'select gemkgschl as value, gemkgname as output from alb_v_gemarkungen as g WHERE g.gemeinde = <requires>gemeinde</requires>', '', '');
INSERT INTO `layer_attributes` VALUES (@last_layer_id, 'gemeinde', 'Auswahlfeld', 'select gemeinde as value, gemeindename as output from alb_v_gemeinden <required by>gemarkung</required by>', '', '');


-- Punktlayer "BORIS_T" für die Anzeige des Labels an der Textposition

SET @group_id = 1;
SET @connection = 'user=xxxx password=xxxx dbname=kvwmapsp';
INSERT INTO layer (`Name`,`Datentyp`,`Gruppe`,`pfad`,`Data`,`schema`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`) VALUES('BORIS_T','0',@group_id,'SELECT * FROM bw_boris_view WHERE (1=1)','textposition from (select * from bw_boris_view) as foo using unique oid using srid=25833','','','','','bw_darstellung','30000','1','',@connection,NULL,'6','oid','','3','pixels','25833','','0',NULL,NULL,NULL,NULL,'','EPSG:25833','BORIS MV','1.1.0','image/png','60','','','0','');
SET @last_layer_id=LAST_INSERT_ID();
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('alle',@last_layer_id,NULL,'1','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'','1','','','',NULL,'1','360','',NULL,NULL,NULL,NULL,'');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id) VALUES (@last_style_id, @last_class_id);
INSERT INTO labels (`font`,`type`,`color`,`outlinecolor`,`shadowcolor`,`shadowsizex`,`shadowsizey`,`backgroundcolor`,`backgroundshadowcolor`,`backgroundshadowsizex`,`backgroundshadowsizey`,`size`,`minsize`,`maxsize`,`position`,`offsetx`,`offsety`,`angle`,`autoangle`,`buffer`,`antialias`,`minfeaturesize`,`maxfeaturesize`,`partials`,`wrap`,`the_force`) VALUES('arial_bold',NULL,'224 92 14','255 255 255','',NULL,NULL,'255 255 255','',NULL,NULL,'10','10','15',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
SET @last_label_id=LAST_INSERT_ID();
INSERT INTO u_labels2classes (label_id, class_id) VALUES (@last_label_id, @last_class_id);