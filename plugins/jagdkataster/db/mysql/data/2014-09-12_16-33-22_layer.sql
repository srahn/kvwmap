###########################################################
#
# Jagdbezirke und Abrundungsverfahren
#
# 
# Die Fachschale "Jagdbezirke" besteht zum einen aus den
# Layern Gemeinschaftlicher Jagdbezirk, abgerundeter Eigen- 
# jagdbezirk, Sonderfläche und Teiljagdbezirk (= Pachfläche  
# innerhalb eines Eigenjagdbezirks).
#
# Die Fachschale "Jagdbezirke" besteht zum anderen auch
# aus den Layern, die für das sog. "Abrundungsverfahren"
# nötig sind, um Jagdbezirke nach LJagdG M-V zu
# definieren. Noch nicht abgerundete Eigenjagdbezirke werden
# hier in einem eigenen Layer geführt. Zur besseren Übersicht-
# lichkeit werden diese Layer in einer eigenen Gruppe
# zusammengefasst.
#
# Der Layer EJB-Verdachtsflächen greift auf eine Tabelle zu,
# die vorab gefüllt werden muss. Das entsprechende SQL ist
# in 'postgis_install_help_jagdbezirke.sql' zu finden.
#
# Die Layer Gemeinschaftlicher Jagdbezirk und abgerundeter 
# Eigenjagdbezirk sind so angelegt, dass eine Verknüpfung mit
# den Daten des Programms 'condition' möglich ist.
#
###########################################################




##########################
# Layer für Jagdbezirke  #
##########################
# Definitionen
INSERT INTO `u_groups` (`Gruppenname`) VALUES ('Jagdbezirke');

SET @group_id = LAST_INSERT_ID();
SET @connection = 'user=xxxx password=xxxx dbname=kvwmapsp';


########################################
# Layer Gemeinschaftliche Jagdbezirke

INSERT INTO layer (`Name`,`alias`,`Datentyp`,`Gruppe`,`pfad`,`maintable`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`,`metalink`,`privileg`) VALUES('Gemein. Jagdbezirke','','2',@group_id,'SELECT oid, * FROM jagdbezirke WHERE art = \'gjb\'','jagdbezirke','the_geom from (select the_geom, id, name from jagdkataster.jagdbezirke  where art = \'gjb\') as foo using unique id using srid=2398','jagdkataster','','','','','name','100002','400','',@connection,'','6','id','id','3','pixels','2398','jagdkataster/view/jagdbezirke.php','0',NULL,NULL,NULL,NULL,'','EPSG:2398','','1.1.0','image/png','60','','','','','0','0','','','','','0');
SET @last_layer_id213=LAST_INSERT_ID();
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id213,'art','art','jagdbezirke','jagdbezirke','varchar','','','1','15','Text','','','','2','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id213,'concode','concode','jagdbezirke','jagdbezirke','varchar','','','1','5','Text','','','','5','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id213,'conname','conname','jagdbezirke','jagdbezirke','varchar','','','1','40','Text','','','','6','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id213,'flaeche','flaeche','jagdbezirke','jagdbezirke','numeric','','','1',NULL,'Text','','','','3','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id213,'id','id','jagdbezirke','jagdbezirke','varchar','','','1','10','Text','','','','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id213,'jb_zuordnung','jb_zuordnung','jagdbezirke','jagdbezirke','varchar','','','1','10','Text','','','','7','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id213,'name','name','jagdbezirke','jagdbezirke','varchar','','','1','50','Text','','','','4','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id213,'oid','oid','','','oid','','',NULL,NULL,'Text','','','','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id213,'status','status','jagdbezirke','jagdbezirke','bool','','','1',NULL,'Text','','','','8','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id213,'the_geom','the_geom','jagdbezirke','jagdbezirke','geometry','POLYGON','','1',NULL,'Text','','','','10','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id213,'verzicht','verzicht','jagdbezirke','jagdbezirke','bool','','','0',NULL,'Text','','','','9','0');
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('GJB',@last_layer_id213,'','0','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'','','250 205 150','','-1 -1 -1',NULL,NULL,'0','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'punkt','4','-1 -1 -1','','0 65 0','2','5','0','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 1);
INSERT INTO labels (`font`,`type`,`color`,`outlinecolor`,`shadowcolor`,`shadowsizex`,`shadowsizey`,`backgroundcolor`,`backgroundshadowcolor`,`backgroundshadowsizex`,`backgroundshadowsizey`,`size`,`minsize`,`maxsize`,`position`,`offsetx`,`offsety`,`angle`,`autoangle`,`buffer`,`antialias`,`minfeaturesize`,`maxfeaturesize`,`partials`,`wrap`,`the_force`) VALUES('engravers',NULL,'160 110 10','255 200 160','',NULL,NULL,'','',NULL,NULL,'9','8','12','1',NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'1',NULL,NULL);
 SET @last_label_id=LAST_INSERT_ID();
INSERT INTO u_labels2classes (label_id, class_id) VALUES (@last_label_id, @last_class_id);


########################################
# Layer abgerundete Eigenjagdbezirke

INSERT INTO layer (`Name`,`alias`,`Datentyp`,`Gruppe`,`pfad`,`maintable`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`,`metalink`,`privileg`) VALUES('Abgerundete EJB','','2',@group_id,'SELECT oid, id, name, art, flaeche, bezirkid, concode, the_geom FROM jagdbezirk_paechter WHERE art = \'ajb\'','jagdbezirk_paechter','the_geom from (select oid, oid as id, name, art, the_geom from jagdkataster.jagdbezirk_paechter WHERE art = \'ajb\') as foo using unique oid using srid=2398','jagdkataster','','','','','name','100001','399','',@connection,'','6','art','id','3','pixels','2398','jagdkataster/view/jagdbezirke.php','1','50',NULL,'99','600001','','EPSG:2398','','1.1.0','image/png','60','','','','','1','0','','','untere Jagdbehörde','','0');
SET @last_layer_id766=LAST_INSERT_ID();
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id766,'art','art','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','15','Text','','','','3','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id766,'bezirkid','bezirkid','jagdbezirk_paechter','jagdbezirk_paechter','int4','','','1','32','Text','','','','5','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id766,'concode','concode','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','5','Text','','','','6','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id766,'flaeche','flaeche','jagdbezirk_paechter','jagdbezirk_paechter','numeric','','','1',NULL,'Text','','Fläche [ha]','','4','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id766,'id','id','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','Lfd. Nr. (Condition)','','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id766,'name','name','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','50','Text','','','','2','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id766,'oid','oid','jagdbezirk_paechter','jagdbezirk_paechter','oid','','','1',NULL,'Text','','','','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id766,'the_geom','the_geom','jagdbezirk_paechter','jagdbezirk_paechter','geometry','POLYGON','','1',NULL,'Text','','','','7','0');
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('nach Abrundung',@last_layer_id766,'','1','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'','1','221 255 113','','-1 -1 -1',NULL,NULL,'','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'punkt','4','-1 -1 -1','','0 65 0','2','5','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 1);
INSERT INTO labels (`font`,`type`,`color`,`outlinecolor`,`shadowcolor`,`shadowsizex`,`shadowsizey`,`backgroundcolor`,`backgroundshadowcolor`,`backgroundshadowsizex`,`backgroundshadowsizey`,`size`,`minsize`,`maxsize`,`position`,`offsetx`,`offsety`,`angle`,`autoangle`,`buffer`,`antialias`,`minfeaturesize`,`maxfeaturesize`,`partials`,`wrap`,`the_force`) VALUES('arial',NULL,'114 138 35','242 246 228','',NULL,NULL,'','',NULL,NULL,'9','8','12','1',NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'1',NULL,'1');
 SET @last_label_id=LAST_INSERT_ID();
INSERT INTO u_labels2classes (label_id, class_id) VALUES (@last_label_id, @last_class_id);


########################################
# Layer Teiljagdbezirke (Teiljagdbezirke können auch keine Geometrie haben!)

INSERT INTO layer (`Name`,`alias`,`Datentyp`,`Gruppe`,`pfad`,`maintable`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`,`metalink`,`privileg`) VALUES('Teiljagdbezirke','','2',@group_id,'SELECT oid, * FROM jagdbezirke WHERE art = \'tjb\'','jagdbezirke','the_geom from (select *, oid from jagdkataster.jagdbezirke where art = \'tjb\') as foo using unique id using srid=2398','jagdkataster','','','','','name','100002','400','',@connection,'','6','id','id','3','pixels','2398','jagdkataster/view/jagdbezirke.php','1',NULL,NULL,NULL,NULL,'','EPSG:2398','','1.1.0','image/png','60','','','','','0','0','','','','','0');
SET @last_layer_id215=LAST_INSERT_ID();
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id215,'art','art','jagdbezirke','jagdbezirke','varchar','','','1','15','Text','','','','2','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id215,'concode','concode','jagdbezirke','jagdbezirke','varchar','','','1','5','Text','','','','5','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id215,'conname','conname','jagdbezirke','jagdbezirke','varchar','','','1','40','Text','','','','6','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id215,'flaeche','flaeche','jagdbezirke','jagdbezirke','numeric','','','1',NULL,'Text','','','','3','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id215,'id','id','jagdbezirke','jagdbezirke','varchar','','','1','10','Text','','','','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id215,'jb_zuordnung','jb_zuordnung','jagdbezirke','jagdbezirke','varchar','','','1','10','Text','','','','7','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id215,'name','name','jagdbezirke','jagdbezirke','varchar','','','1','50','Text','','','','4','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id215,'oid','oid','','','oid','','',NULL,NULL,'Text','','','','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id215,'status','status','jagdbezirke','jagdbezirke','bool','','','1',NULL,'Text','','','','8','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id215,'the_geom','the_geom','jagdbezirke','jagdbezirke','geometry','POLYGON','','1',NULL,'Text','','','','10','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id215,'verzicht','verzicht','jagdbezirke','jagdbezirke','bool','','','0',NULL,'Text','','','','9','0');
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('TJB',@last_layer_id215,'','0','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'cross','15','90 90 90','','90 90 90','15','15','0','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO labels (`font`,`type`,`color`,`outlinecolor`,`shadowcolor`,`shadowsizex`,`shadowsizey`,`backgroundcolor`,`backgroundshadowcolor`,`backgroundshadowsizex`,`backgroundshadowsizey`,`size`,`minsize`,`maxsize`,`position`,`offsetx`,`offsety`,`angle`,`autoangle`,`buffer`,`antialias`,`minfeaturesize`,`maxfeaturesize`,`partials`,`wrap`,`the_force`) VALUES('arial',NULL,'90 90 90','220 220 220','',NULL,NULL,'','',NULL,NULL,'8','7','10','1',NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'1',NULL,NULL);
 SET @last_label_id=LAST_INSERT_ID();
INSERT INTO u_labels2classes (label_id, class_id) VALUES (@last_label_id, @last_class_id);


########################################
# Layer Sonderflächen

INSERT INTO layer (`Name`,`alias`,`Datentyp`,`Gruppe`,`pfad`,`maintable`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`,`metalink`,`privileg`) VALUES('Sonderflächen','','2',@group_id,'SELECT oid, * FROM jagdbezirke WHERE art = \'sf\'','jagdbezirke','the_geom from (select * from jagdkataster.jagdbezirke where art = \'sf\') as foo using unique id using srid=2398','jagdkataster','','','','','','100002','400','',@connection,'','6','id','id','3','pixels','2398','jagdkataster/view/jagdbezirke.php','1',NULL,NULL,NULL,NULL,'','EPSG:2398','','1.1.0','image/png','60','','','','','0','0','','','','','0');
SET @last_layer_id216=LAST_INSERT_ID();
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id216,'art','art','jagdbezirke','jagdbezirke','varchar','','','1','15','Text','','','','2','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id216,'concode','concode','jagdbezirke','jagdbezirke','varchar','','','1','5','Text','','','','5','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id216,'conname','conname','jagdbezirke','jagdbezirke','varchar','','','1','40','Text','','','','6','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id216,'flaeche','flaeche','jagdbezirke','jagdbezirke','numeric','','','1',NULL,'Text','','','','3','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id216,'id','id','jagdbezirke','jagdbezirke','varchar','','','1','10','Text','','','','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id216,'jb_zuordnung','jb_zuordnung','jagdbezirke','jagdbezirke','varchar','','','1','10','Text','','','','7','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id216,'name','name','jagdbezirke','jagdbezirke','varchar','','','1','50','Text','','','','4','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id216,'oid','oid','','','oid','','',NULL,NULL,'Text','','','','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id216,'status','status','jagdbezirke','jagdbezirke','bool','','','1',NULL,'Text','','','','8','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id216,'the_geom','the_geom','jagdbezirke','jagdbezirke','geometry','POLYGON','','1',NULL,'Text','','','','10','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id216,'verzicht','verzicht','jagdbezirke','jagdbezirke','bool','','','0',NULL,'Text','','','','9','0');
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('SF',@last_layer_id216,'','0','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'cross','5','240 0 120','','240 0 120','5','5','0','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);



##################################
# Layer für Abrundungsverfahren  #
##################################
# Definitionen
INSERT INTO `u_groups` (`id` ,`Gruppenname`) VALUES (NULL , 'Abrundungsverfahren');
SET @gruppe_id = LAST_INSERT_ID();
SET @connection = 'user=xxxx password=xxxx dbname=kvwmapsp';


########################################
# Layer Eigenjagdbezirk VOR Abrundung

# Layerdefinition


INSERT INTO layer (`Name`,`alias`,`Datentyp`,`Gruppe`,`pfad`,`maintable`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`,`metalink`,`privileg`) VALUES('EJB im Verfahren','','2',@group_id,'SELECT oid, id, name, art, flaeche, bezirkid, concode, the_geom FROM jagdbezirk_paechter WHERE art = \'ejb\'','jagdbezirk_paechter','the_geom from (select oid, oid as id, name, art, the_geom from jagdkataster.jagdbezirk_paechter where art = \'ejb\') as foo using unique oid using srid=2398','jagdkataster','','','','','name','100001','399','',@connection,'','6','art','id','3','pixels','2398','jagdkataster/view/jagdbezirke.php','1','50',NULL,'99','600001','','EPSG:2398','','1.1.0','image/png','60','','','','','1','0','','','untere Jagdbehörde','','0');
SET @last_layer_id767=LAST_INSERT_ID();
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id767,'art','art','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','15','Text','','Art','','3','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id767,'bezirkid','bezirkid','jagdbezirk_paechter','jagdbezirk_paechter','int4','','','1','32','Text','','','','5','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id767,'concode','concode','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','5','Text','','','','6','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id767,'flaeche','flaeche','jagdbezirk_paechter','jagdbezirk_paechter','numeric','','','1',NULL,'Text','','Fläche [ha]','','4','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id767,'id','id','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','Lfd. Nr. (Condition)','','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id767,'name','name','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','50','Text','','Name','','2','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id767,'oid','oid','jagdbezirk_paechter','jagdbezirk_paechter','oid','','','1',NULL,'Text','','','','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id767,'the_geom','the_geom','jagdbezirk_paechter','jagdbezirk_paechter','geometry','POLYGON','','1',NULL,'Geometrie','','','','7','0');
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('vor Abrundung',@last_layer_id767,'(\'[art]\' eq \'ejb\')','1','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'','5','240 240 120','','-1 -1 -1',NULL,NULL,'','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'punkt','4','-1 -1 -1','','0 235 0','2','7','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 1);
INSERT INTO labels (`font`,`type`,`color`,`outlinecolor`,`shadowcolor`,`shadowsizex`,`shadowsizey`,`backgroundcolor`,`backgroundshadowcolor`,`backgroundshadowsizex`,`backgroundshadowsizey`,`size`,`minsize`,`maxsize`,`position`,`offsetx`,`offsety`,`angle`,`autoangle`,`buffer`,`antialias`,`minfeaturesize`,`maxfeaturesize`,`partials`,`wrap`,`the_force`) VALUES('arial',NULL,'0 65 0','255 255 200','',NULL,NULL,'','',NULL,NULL,'8','7','10','1',NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'1',NULL,'1');
 SET @last_label_id=LAST_INSERT_ID();
INSERT INTO u_labels2classes (label_id, class_id) VALUES (@last_label_id, @last_class_id);


########################################
# Layer Abtrennungsflächen

INSERT INTO layer (`Name`,`alias`,`Datentyp`,`Gruppe`,`pfad`,`maintable`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`,`metalink`,`privileg`) VALUES('Abtrennungsflächen','','2',@group_id,'SELECT oid, id, name, art, flaeche,  bezirkid, concode, jb_zuordnung, status, the_geom FROM jagdbezirk_paechter WHERE art = \'atf\'','jagdbezirk_paechter','the_geom from (select oid, id, name, art, the_geom from jagdkataster.jagdbezirk_paechter where art = \'atf\') as foo using unique oid using srid=2398','jagdkataster','','','','','name','100001','399','',@connection,'','6','art','id','3','pixels','2398','jagdkataster/view/jagdbezirke.php','1','50',NULL,'99','600001','','EPSG:2398','','1.1.0','image/png','60','','','','','1','0','','','untere Jagdbehörde','','0');
SET @last_layer_id775=LAST_INSERT_ID();
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id775,'art','art','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','15','Text','','','','3','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id775,'bezirkid','bezirkid','jagdbezirk_paechter','jagdbezirk_paechter','int4','','','1','32','Text','','','','5','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id775,'concode','concode','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','5','Text','','','','6','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id775,'flaeche','flaeche','jagdbezirk_paechter','jagdbezirk_paechter','numeric','','','1',NULL,'Text','','','','4','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id775,'id','id','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id775,'jb_zuordnung','jb_zuordnung','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','7','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id775,'name','name','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','50','Text','','Name','','2','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id775,'oid','oid','jagdbezirk_paechter','jagdbezirk_paechter','oid','','','1',NULL,'Text','','','','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id775,'status','status','jagdbezirk_paechter','jagdbezirk_paechter','bool','','','1',NULL,'Text','','','','8','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id775,'the_geom','the_geom','jagdbezirk_paechter','jagdbezirk_paechter','geometry','POLYGON','','1',NULL,'Geometrie','','','','9','0');
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('Abtrennung',@last_layer_id775,'(\'[art]\' eq \'atf\')','1','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'cross','9','0 0 0','80 190 80','-1 -1 -1','30','30','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'punkt','3','-1 -1 -1','','130 0 0','3','5','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 1);


# Kein Label für Abtrennungsflächen


########################################
# Layer Angliederungsflächen


INSERT INTO layer (`Name`,`alias`,`Datentyp`,`Gruppe`,`pfad`,`maintable`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`,`metalink`,`privileg`) VALUES('Angliederungsflächen','','2',@group_id,'SELECT oid, id, name, art, flaeche,  bezirkid, concode, jb_zuordnung, status, the_geom FROM jagdbezirk_paechter WHERE art = \'agf\'','jagdbezirk_paechter','the_geom from (select oid, oid as id, name, art, the_geom from jagdkataster.jagdbezirk_paechter where art = \'agf\') as foo using unique oid using srid=2398','jagdkataster','','','','','name','100001','399','',@connection,'','6','art','id','3','pixels','2398','jagdkataster/view/jagdbezirke.php','1','50',NULL,'99','600001','','EPSG:2398','','1.1.0','image/png','60','','','','','1','0','','','untere Jagdbehörde','','0');
SET @last_layer_id771=LAST_INSERT_ID();
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id771,'art','art','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','15','Text','','Art','','3','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id771,'bezirkid','bezirkid','jagdbezirk_paechter','jagdbezirk_paechter','int4','','','1','32','Text','','','','5','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id771,'concode','concode','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','5','Text','','','','6','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id771,'flaeche','flaeche','jagdbezirk_paechter','jagdbezirk_paechter','numeric','','','1',NULL,'Text','','','','4','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id771,'id','id','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id771,'jb_zuordnung','jb_zuordnung','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','7','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id771,'name','name','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','50','Text','','Name','','2','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id771,'oid','oid','jagdbezirk_paechter','jagdbezirk_paechter','oid','','','1',NULL,'Text','','','','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id771,'status','status','jagdbezirk_paechter','jagdbezirk_paechter','bool','','','1',NULL,'Text','','','','8','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id771,'the_geom','the_geom','jagdbezirk_paechter','jagdbezirk_paechter','geometry','POLYGON','','1',NULL,'Geometrie','','','','9','0');
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('Angliederung',@last_layer_id771,'(\'[art]\' eq \'agf\')','1','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'cross','9','0 0 0','255 120 12','-1 -1 -1','30','30','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'punkt','3','-1 -1 -1','','130 0 0','3','5','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 1);


# Kein Label für Angliederungsflächen


########################################
# Layer Jagdbezirk-Enklaven

INSERT INTO layer (`Name`,`alias`,`Datentyp`,`Gruppe`,`pfad`,`maintable`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`,`metalink`,`privileg`) VALUES('Jagdbezirk Enklaven','','2',@group_id,'SELECT oid, id, name, art, flaeche,  bezirkid, concode, jb_zuordnung, status, the_geom FROM jagdbezirk_paechter WHERE art = \'jbe\'','jagdbezirk_paechter','the_geom from (select oid, oid as id, name, art, the_geom from jagdkataster.jagdbezirk_paechter where art = \'jbe\') as foo using unique oid using srid=2398','jagdkataster','','','','','name','100001','399','',@connection,'','6','art','id','3','pixels','2398','jagdkataster/view/jagdbezirke.php','1','50',NULL,'99','600001','','EPSG:2398','','1.1.0','image/png','60','','','','','1','0','','','untere Jagdbehörde','','0');
SET @last_layer_id772=LAST_INSERT_ID();
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id772,'art','art','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','15','Auswahlfeld','select bezeichnung as output, art as value from jagdbezirkart','Art','','3','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id772,'bezirkid','bezirkid','jagdbezirk_paechter','jagdbezirk_paechter','int4','','','1','32','Text','','','','5','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id772,'concode','concode','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','5','Text','','','','6','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id772,'flaeche','flaeche','jagdbezirk_paechter','jagdbezirk_paechter','numeric','','','1',NULL,'Text','','','','4','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id772,'id','id','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id772,'jb_zuordnung','jb_zuordnung','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','7','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id772,'name','name','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','50','Text','','Name','','2','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id772,'oid','oid','jagdbezirk_paechter','jagdbezirk_paechter','oid','','','1',NULL,'Text','','','','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id772,'status','status','jagdbezirk_paechter','jagdbezirk_paechter','bool','','','1',NULL,'Text','','','','8','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id772,'the_geom','the_geom','jagdbezirk_paechter','jagdbezirk_paechter','geometry','POLYGON','','1',NULL,'Geometrie','','','','9','0');
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('Enklave',@last_layer_id772,'(\'[art]\' eq \'jbe\')','1','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'cross','9','0 0 0','255 0 0','-1 -1 -1','30','30','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'punkt','3','-1 -1 -1','','130 0 0','3','5','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 1);


# Kein Label für Jagdbezirk-Enklaven


########################################
# Layer jadbezirksfreie Flächen


INSERT INTO layer (`Name`,`alias`,`Datentyp`,`Gruppe`,`pfad`,`maintable`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`,`metalink`,`privileg`) VALUES('jadbezirksfreie Flächen','','2',@group_id,'SELECT oid, id, name, art, flaeche,  bezirkid, concode, jb_zuordnung, status, the_geom FROM jagdbezirk_paechter WHERE art = \'jbf\'','jagdbezirk_paechter','the_geom from (select oid, oid as id, name, art, the_geom from jagdkataster.jagdbezirk_paechter where art = \'jbf\') as foo using unique oid using srid=2398','jagdkataster','','','','','name','100001','399','',@connection,'','6','art','id','3','pixels','2398','jagdkataster/view/jagdbezirke.php','1','50',NULL,'99','600001','','EPSG:2398','','1.1.0','image/png','60','','','','','1','0','','','untere Jagdbehörde','','0');
SET @last_layer_id773=LAST_INSERT_ID();
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id773,'art','art','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','15','Auswahlfeld','select bezeichnung as output, art as value from jagdbezirkart','Art','','3','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id773,'bezirkid','bezirkid','jagdbezirk_paechter','jagdbezirk_paechter','int4','','','1','32','Text','','','','5','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id773,'concode','concode','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','5','Text','','','','6','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id773,'flaeche','flaeche','jagdbezirk_paechter','jagdbezirk_paechter','numeric','','','1',NULL,'Text','','','','4','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id773,'id','id','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id773,'jb_zuordnung','jb_zuordnung','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','7','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id773,'name','name','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','50','Text','','Name','','2','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id773,'oid','oid','jagdbezirk_paechter','jagdbezirk_paechter','oid','','','1',NULL,'Text','','','','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id773,'status','status','jagdbezirk_paechter','jagdbezirk_paechter','bool','','','1',NULL,'Text','','','','8','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id773,'the_geom','the_geom','jagdbezirk_paechter','jagdbezirk_paechter','geometry','POLYGON','','1',NULL,'Geometrie','','','','9','0');
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('jagdbezirksfrei',@last_layer_id773,'(\'[art]\' eq \'jbf\')','1','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'cross','9','0 0 0','22 94 255','-1 -1 -1','30','30','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'punkt','3','-1 -1 -1','','130 0 0','3','5','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 1);


# Kein Label für jadbezirksfreie Flächen


########################################
# Layer Anpachtflächen


INSERT INTO layer (`Name`,`alias`,`Datentyp`,`Gruppe`,`pfad`,`maintable`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`,`metalink`,`privileg`) VALUES('Anpachtflächen','','2',@group_id,'SELECT oid, id, name, art, flaeche,  bezirkid, concode, jb_zuordnung, status, the_geom FROM jagdbezirk_paechter WHERE art = \'apf\'','jagdbezirk_paechter','the_geom from (select oid, the_geom, id, art, name from jagdkataster.jagdbezirk_paechter where art = \'apf\') as foo using unique oid using srid=2398','jagdkataster','','','','','name','100001','399','',@connection,'','6','art','id','1','pixels','2398','jagdkataster/view/jagdbezirke.php','1','50',NULL,'99','500001','','EPSG:2398','','1.1.0','image/png','60','','','','','1','0','','','untere Jagdbehörde','','0');
SET @last_layer_id774=LAST_INSERT_ID();
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id774,'art','art','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','15','Text','','','','3','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id774,'bezirkid','bezirkid','jagdbezirk_paechter','jagdbezirk_paechter','int4','','','1','32','Text','','','','5','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id774,'concode','concode','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','5','Text','','','','6','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id774,'flaeche','flaeche','jagdbezirk_paechter','jagdbezirk_paechter','numeric','','','1',NULL,'Text','','','','4','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id774,'id','id','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id774,'jb_zuordnung','jb_zuordnung','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','7','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id774,'name','name','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','50','Text','','','','2','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id774,'oid','oid','jagdbezirk_paechter','jagdbezirk_paechter','oid','','','1',NULL,'Text','','','','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id774,'status','status','jagdbezirk_paechter','jagdbezirk_paechter','bool','','','1',NULL,'Text','','','','8','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`,`privileg`) VALUES(@last_layer_id774,'the_geom','the_geom','jagdbezirk_paechter','jagdbezirk_paechter','geometry','POLYGON','','1',NULL,'Text','','','','9','0');
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES('Anpachtfläche',@last_layer_id774,'','1','');
SET @last_class_id=LAST_INSERT_ID();
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'cross','9','0 0 0','255 215 0','-1 -1 -1','30','30','0','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES(NULL,'punkt','3','-1 -1 -1','','130 0 0','3','5','','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 1);


# Kein Label für Anpachtflächen



