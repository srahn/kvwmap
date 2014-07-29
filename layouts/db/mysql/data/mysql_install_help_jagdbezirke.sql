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
INSERT INTO `kvwmapdb`.`u_groups` (`id` ,`Gruppenname`) VALUES (
NULL , 'Jagdbezirke');
SET @group_id = LAST_INSERT_ID();
SET @connection = 'user=xxxx password=xxxx dbname=kvwmapsp';


########################################
# Layer Gemeinschaftliche Jagdbezirke

# Layerdefinition
INSERT INTO layer (`Name`,`Datentyp`,`Gruppe`,`pfad`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`) VALUES 
('Gemein. Jagdbezirke','2',@group_id,'SELECT oid, id, name, art, flaeche,  bezirkid, concode, the_geom FROM jagdbezirk_paechter WHERE art = \'gjb\'','the_geom from (select oid, oid as id, name, art, the_geom from jagdbezirk_paechter) as foo using unique oid using srid=2398','','','','','','name','100001','399','',@connection,'','6','id','id','3','pixels','2398','jagdbezirke.php','1','50',NULL,'99','600001','','EPSG:2398','','1.1.0','image/png','60','','','','','1','0','','','untere Jagdbehörde');
SET @last_layer_id1=LAST_INSERT_ID();

# Layer-Attribute
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id1,'art','art','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','15','Text','','Art','','3');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id1,'bezirkid','bezirkid','jagdbezirk_paechter','jagdbezirk_paechter','int4','','','1','32','Text','','','','5');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id1,'concode','concode','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','5','Text','','','','6');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id1,'flaeche','flaeche','jagdbezirk_paechter','jagdbezirk_paechter','numeric','','','1',NULL,'Text','','Fläche [ha]','','4');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id1,'id','id','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','Lfd. Nr. (Condition)','','1');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id1,'name','name','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','50','Text','','Name','','2');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id1,'oid','oid','jagdbezirk_paechter','jagdbezirk_paechter','oid','','','1',NULL,'Text','','','','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id1,'the_geom','the_geom','jagdbezirk_paechter','jagdbezirk_paechter','geometry','MULTIPOLYGON','','1',NULL,'Text','','','','7');

UPDATE layer_attributes SET options = REPLACE(options, '1', @last_layer_id1) WHERE layer_id IN(@last_layer_id1) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK');

# Class-Definition
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES 
('GJB - Gem.Jagd',@last_layer_id144,'(\'[art]\' eq \'gjb\')','1','');
SET @last_class_id=LAST_INSERT_ID();

# Styledefinitionen
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES 
(NULL,'','1','250 205 150','','-1 -1 -1',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES 
(NULL,'punkt','4','-1 -1 -1','','0 65 0','2','5',NULL,'',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 1);

# Label-Definition
INSERT INTO labels (`font`,`type`,`color`,`outlinecolor`,`shadowcolor`,`shadowsizex`,`shadowsizey`,`backgroundcolor`,`backgroundshadowcolor`,`backgroundshadowsizex`,`backgroundshadowsizey`,`size`,`minsize`,`maxsize`,`position`,`offsetx`,`offsety`,`angle`,`autoangle`,`buffer`,`antialias`,`minfeaturesize`,`maxfeaturesize`,`partials`,`wrap`,`the_force`) VALUES 
('arial',NULL,'160 110 10','255 200 160','',NULL,NULL,'','',NULL,NULL,'9','8','12','1',NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'1',NULL,'1');
 SET @last_label_id=LAST_INSERT_ID();
INSERT INTO u_labels2classes (label_id, class_id) VALUES (@last_label_id, @last_class_id);


########################################
# Layer abgerundete Eigenjagdbezirke

# Layerdefinition
INSERT INTO layer (`Name`,`Datentyp`,`Gruppe`,`pfad`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`) VALUES 
('Abgerundete EJB','2',@group_id,'SELECT oid, id, name, art, flaeche, bezirkid, concode, the_geom FROM jagdbezirk_paechter WHERE art = \'ajb\'','the_geom from (select oid, oid as id, name, art, the_geom from jagdbezirk_paechter WHERE art = \'ajb\') as foo using unique oid using srid=2398','','','','','','name','100001','399','',@connection,'','6','art','id','3','pixels','2398','jagdbezirke.php','1','50',NULL,'99','600001','','EPSG:2398','','1.1.0','image/png','60','','','','','1','0','','','untere Jagdbehörde');
SET @last_layer_id2=LAST_INSERT_ID();

# Layer-Attribute
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id2,'art','art','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','15','Text','','','','3');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id2,'bezirkid','bezirkid','jagdbezirk_paechter','jagdbezirk_paechter','int4','','','1','32','Text','','','','5');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id2,'concode','concode','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','5','Text','','','','6');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id2,'flaeche','flaeche','jagdbezirk_paechter','jagdbezirk_paechter','numeric','','','1',NULL,'Text','','Fläche [ha]','','4');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id2,'id','id','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','Lfd. Nr. (Condition)','','1');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id2,'name','name','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','50','Text','','','','2');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id2,'oid','oid','jagdbezirk_paechter','jagdbezirk_paechter','oid','','','1',NULL,'Text','','','','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id2,'the_geom','the_geom','jagdbezirk_paechter','jagdbezirk_paechter','geometry','MULTIPOLYGON','','1',NULL,'Text','','','','7');

UPDATE layer_attributes SET options = REPLACE(options, '2', @last_layer_id2) WHERE layer_id IN(@last_layer_id2) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK');

# Class-Definition
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES 
('nach Abrundung',@last_layer_id2,'','1','');
SET @last_class_id=LAST_INSERT_ID();

# Styledefinitionen
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES 
(NULL,'','1','221 255 113','','-1 -1 -1',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,'');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES 
(NULL,'punkt','4','-1 -1 -1','','0 65 0','2','5',NULL,'',NULL,NULL,NULL,NULL,'');
SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 1);

# Label-Definition
INSERT INTO labels (`font`,`type`,`color`,`outlinecolor`,`shadowcolor`,`shadowsizex`,`shadowsizey`,`backgroundcolor`,`backgroundshadowcolor`,`backgroundshadowsizex`,`backgroundshadowsizey`,`size`,`minsize`,`maxsize`,`position`,`offsetx`,`offsety`,`angle`,`autoangle`,`buffer`,`antialias`,`minfeaturesize`,`maxfeaturesize`,`partials`,`wrap`,`the_force`) VALUES 
('arial',NULL,'114 138 35','242 246 228','',NULL,NULL,'','',NULL,NULL,'9','8','12','1',NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'1',NULL,'1');
SET @last_label_id=LAST_INSERT_ID();
INSERT INTO u_labels2classes (label_id, class_id) VALUES (@last_label_id, @last_class_id);


########################################
# Layer Teiljagdbezirke (Teiljagdbezirke können auch keine Geometrie haben!)

# Layerdefinition
INSERT INTO layer (`Name`,`Datentyp`,`Gruppe`,`pfad`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`) VALUES 
('Teiljagdbezirke','2',@group_id,'SELECT oid, id, name, art, flaeche,  bezirkid, concode, jb_zuordnung, status, the_geom FROM jagdbezirk_paechter WHERE art = \'tjb\'','the_geom from (select oid, oid as id, name, art, the_geom from jagdbezirk_paechter) as foo using unique oid using srid=2398','','','','','','name','100001','399','',@connection,'','6','art','id','3','pixels','2398','jagdbezirke.php','1','50',NULL,'99','600001','','EPSG:2398','','1.1.0','image/png','60','','','','','1','0','','','untere Jagdbehörde');
SET @last_layer_id3=LAST_INSERT_ID();

# Layer-Attribute
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id3,'art','art','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','15','Text','','Art','','3');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id3,'bezirkid','bezirkid','jagdbezirk_paechter','jagdbezirk_paechter','int4','','','1','32','Text','','','','5');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id3,'concode','concode','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','5','Text','','','','6');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id3,'flaeche','flaeche','jagdbezirk_paechter','jagdbezirk_paechter','numeric','','','1',NULL,'Text','','Fläche [ha]','','4');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id3,'id','id','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','Lfd. Nr. (Condition)','','1');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id3,'jb_zuordnung','jb_zuordnung','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','7');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id3,'name','name','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','50','Text','','Name','','2');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id3,'oid','oid','jagdbezirk_paechter','jagdbezirk_paechter','oid','','','1',NULL,'Text','','','','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id3,'status','status','jagdbezirk_paechter','jagdbezirk_paechter','bool','','','1',NULL,'Text','','','','8');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id3,'the_geom','the_geom','jagdbezirk_paechter','jagdbezirk_paechter','geometry','MULTIPOLYGON','','1',NULL,'Text','','','','9');

UPDATE layer_attributes SET options = REPLACE(options, '3', @last_layer_id3) WHERE layer_id IN(@last_layer_id3) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK');

# Class-Definition
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES 
('Teiljagdbezirk',@last_layer_id3,'(\'[art]\' eq \'tjb\')','1','');
SET @last_class_id=LAST_INSERT_ID();

# Styledefinitionen
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES 
(NULL,'cross','15','90 90 90','','90 90 90','15','15',NULL,'',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);

# Label-Definition
INSERT INTO labels (`font`,`type`,`color`,`outlinecolor`,`shadowcolor`,`shadowsizex`,`shadowsizey`,`backgroundcolor`,`backgroundshadowcolor`,`backgroundshadowsizex`,`backgroundshadowsizey`,`size`,`minsize`,`maxsize`,`position`,`offsetx`,`offsety`,`angle`,`autoangle`,`buffer`,`antialias`,`minfeaturesize`,`maxfeaturesize`,`partials`,`wrap`,`the_force`) VALUES 
('arial',NULL,'90 90 90','220 220 220','',NULL,NULL,'','',NULL,NULL,'8','7','10','1',NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'1',NULL,'1');
 SET @last_label_id=LAST_INSERT_ID();
INSERT INTO u_labels2classes (label_id, class_id) VALUES (@last_label_id, @last_class_id);


########################################
# Layer Sonderflächen

# Layerdefinition
INSERT INTO layer (`Name`,`Datentyp`,`Gruppe`,`pfad`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`) VALUES 
('Sonderflächen','2',@group_id,'SELECT oid, id, name, art, flaeche,  bezirkid, concode, jb_zuordnung, status, the_geom FROM jagdbezirk_paechter WHERE art = \'sf\'','the_geom from (select oid, oid as id, name, art, the_geom from jagdbezirk_paechter) as foo using unique oid using srid=2398','','','','','','name','100001','399','',@connection,'','6','id','id','3','pixels','2398','jagdbezirke.php','1','50',NULL,'99','600001','','EPSG:2398','','1.1.0','image/png','60','','','','','1','0','','','untere Jagdbehörde');
SET @last_layer_id4=LAST_INSERT_ID();

# Layer-Attribute
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id4,'art','art','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','15','Text','','Art','','3');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id4,'bezirkid','bezirkid','jagdbezirk_paechter','jagdbezirk_paechter','int4','','','1','32','Text','','','','5');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id4,'concode','concode','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','5','Text','','','','6');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id4,'flaeche','flaeche','jagdbezirk_paechter','jagdbezirk_paechter','numeric','','','1',NULL,'Text','','Fläche [ha]','','4');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id4,'id','id','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','Lfd. Nr. (Condition)','','1');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id4,'jb_zuordnung','jb_zuordnung','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','7');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id4,'name','name','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','50','Text','','Name','','2');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id4,'oid','oid','jagdbezirk_paechter','jagdbezirk_paechter','oid','','','1',NULL,'Text','','','','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id4,'status','status','jagdbezirk_paechter','jagdbezirk_paechter','bool','','','1',NULL,'Text','','','','8');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id4,'the_geom','the_geom','jagdbezirk_paechter','jagdbezirk_paechter','geometry','MULTIPOLYGON','','1',NULL,'Geometrie','','','','9');

UPDATE layer_attributes SET options = REPLACE(options, '4', @last_layer_id4) WHERE layer_id IN(@last_layer_id4) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK');

# Class-Definition
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES 
('JB - Sonderfläche',@last_layer_id4,'(\'[art]\' eq \'sf\')','1','');
SET @last_class_id=LAST_INSERT_ID();


# Styledefinitionen
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES 
(NULL,'cross','5','240 0 120','','240 0 120','5','5',NULL,'',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);

# Kein Label für Sonderflächen




##################################
# Layer für Abrundungsverfahren  #
##################################
# Definitionen
INSERT INTO `kvwmapdb`.`u_groups` (`id` ,`Gruppenname`) VALUES (
NULL , 'Abrundungsverfahren');
SET @gruppe_id = LAST_INSERT_ID();
SET @connection = 'user=xxxx password=xxxx dbname=kvwmapsp';


########################################
# Layer Eigenjagdbezirk VOR Abrundung

# Layerdefinition

INSERT INTO layer (`Name`,`Datentyp`,`Gruppe`,`pfad`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`) VALUES 
('EJB im Verfahren','2',@group_id,'SELECT oid, id, name, art, flaeche, bezirkid, concode, the_geom FROM jagdbezirk_paechter WHERE art = \'ejb\'','the_geom from (select oid, oid as id, name, art, the_geom from jagdbezirk_paechter where art = \'ejb\') as foo using unique oid using srid=2398','','','','','','name','100001','399','',@connection,'','6','art','id','3','pixels','2398','jagdbezirke.php','1','50',NULL,'99','600001','','EPSG:2398','','1.1.0','image/png','60','','','','','1','0','','','untere Jagdbehörde');
SET @last_layer_id5=LAST_INSERT_ID();

# Layer-Attribute
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id5,'art','art','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','15','Text','','Art','','3');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id5,'bezirkid','bezirkid','jagdbezirk_paechter','jagdbezirk_paechter','int4','','','1','32','Text','','','','5');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id5,'concode','concode','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','5','Text','','','','6');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id5,'flaeche','flaeche','jagdbezirk_paechter','jagdbezirk_paechter','numeric','','','1',NULL,'Text','','Fläche [ha]','','4');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id5,'id','id','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','Lfd. Nr. (Condition)','','1');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id5,'name','name','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','50','Text','','Name','','2');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id5,'oid','oid','jagdbezirk_paechter','jagdbezirk_paechter','oid','','','1',NULL,'Text','','','','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id5,'the_geom','the_geom','jagdbezirk_paechter','jagdbezirk_paechter','geometry','MULTIPOLYGON','','1',NULL,'Geometrie','','','','7');

UPDATE layer_attributes SET options = REPLACE(options, '5', @last_layer_id5) WHERE layer_id IN(@last_layer_id5) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK');

# Class-Definition
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES 
('vor Abrundung',@last_layer_id5,'(\'[art]\' eq \'ejb\')','1','');
SET @last_class_id=LAST_INSERT_ID();


# Styledefinitionen
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES 
(NULL,'','5','240 240 120','','-1 -1 -1',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES 
(NULL,'punkt','4','-1 -1 -1','','0 235 0','2','7',NULL,'',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 1);


# Label-Definition
INSERT INTO labels (`font`,`type`,`color`,`outlinecolor`,`shadowcolor`,`shadowsizex`,`shadowsizey`,`backgroundcolor`,`backgroundshadowcolor`,`backgroundshadowsizex`,`backgroundshadowsizey`,`size`,`minsize`,`maxsize`,`position`,`offsetx`,`offsety`,`angle`,`autoangle`,`buffer`,`antialias`,`minfeaturesize`,`maxfeaturesize`,`partials`,`wrap`,`the_force`) VALUES 
('arial',NULL,'0 65 0','255 255 200','',NULL,NULL,'','',NULL,NULL,'8','7','10','1',NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'1',NULL,'1');
 SET @last_label_id=LAST_INSERT_ID();
INSERT INTO u_labels2classes (label_id, class_id) VALUES (@last_label_id, @last_class_id);


########################################
# Layer Abtrennungsflächen

# Layerdefinition
INSERT INTO layer (`Name`,`Datentyp`,`Gruppe`,`pfad`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`) VALUES 
('Abtrennungsflächen','2',@group_id,'SELECT oid, id, name, art, flaeche,  bezirkid, concode, jb_zuordnung, status, the_geom FROM jagdbezirk_paechter WHERE art = \'atf\'','the_geom from (select oid, id, name, art, the_geom from jagdbezirk_paechter where art = \'atf\') as foo using unique oid using srid=2398','','','','','','name','100001','399','',@connection,'','6','art','id','3','pixels','2398','jagdbezirke.php','1','50',NULL,'99','600001','','EPSG:2398','','1.1.0','image/png','60','','','','','1','0','','','untere Jagdbehörde');
SET @last_layer_id6=LAST_INSERT_ID();

# Layer-Attribute
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id6,'art','art','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','15','Text','','','','3');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id6,'bezirkid','bezirkid','jagdbezirk_paechter','jagdbezirk_paechter','int4','','','1','32','Text','','','','5');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id6,'concode','concode','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','5','Text','','','','6');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id6,'flaeche','flaeche','jagdbezirk_paechter','jagdbezirk_paechter','numeric','','','1',NULL,'Text','','','','4');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id6,'id','id','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','1');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id6,'jb_zuordnung','jb_zuordnung','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','7');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id6,'name','name','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','50','Text','','Name','','2');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id6,'oid','oid','jagdbezirk_paechter','jagdbezirk_paechter','oid','','','1',NULL,'Text','','','','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id6,'status','status','jagdbezirk_paechter','jagdbezirk_paechter','bool','','','1',NULL,'Text','','','','8');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id6,'the_geom','the_geom','jagdbezirk_paechter','jagdbezirk_paechter','geometry','MULTIPOLYGON','','1',NULL,'Geometrie','','','','9');

UPDATE layer_attributes SET options = REPLACE(options, '6', @last_layer_id6) WHERE layer_id IN(@last_layer_id6) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK');

# Class-Definition
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES 
('Abtrennung',@last_layer_id147,'(\'[art]\' eq \'atf\')','1','');
SET @last_class_id=LAST_INSERT_ID();

# Styledefinitionen
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES 
(NULL,'cross','9','0 0 0','80 190 80','-1 -1 -1','30','30',NULL,'',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES 
(NULL,'punkt','3','-1 -1 -1','','130 0 0','3','5',NULL,'',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 1);


# Kein Label für Abtrennungsflächen


########################################
# Layer Angliederungsflächen

# Layerdefinition
INSERT INTO layer (`Name`,`Datentyp`,`Gruppe`,`pfad`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`) VALUES 
('Angliederungsflächen','2',@group_id,'SELECT oid, id, name, art, flaeche,  bezirkid, concode, jb_zuordnung, status, the_geom FROM jagdbezirk_paechter WHERE art = \'agf\'','the_geom from (select oid, oid as id, name, art, the_geom from jagdbezirk_paechter where art = \'agf\') as foo using unique oid using srid=2398','','','','','','name','100001','399','',@connection,'','6','art','id','3','pixels','2398','jagdbezirke.php','1','50',NULL,'99','600001','','EPSG:2398','','1.1.0','image/png','60','','','','','1','0','','','untere Jagdbehörde');
SET @last_layer_id7=LAST_INSERT_ID();

# Layer-Attribute
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id7,'art','art','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','15','Text','','Art','','3');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id7,'bezirkid','bezirkid','jagdbezirk_paechter','jagdbezirk_paechter','int4','','','1','32','Text','','','','5');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id7,'concode','concode','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','5','Text','','','','6');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id7,'flaeche','flaeche','jagdbezirk_paechter','jagdbezirk_paechter','numeric','','','1',NULL,'Text','','','','4');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id7,'id','id','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','1');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id7,'jb_zuordnung','jb_zuordnung','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','7');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id7,'name','name','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','50','Text','','Name','','2');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id7,'oid','oid','jagdbezirk_paechter','jagdbezirk_paechter','oid','','','1',NULL,'Text','','','','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id7,'status','status','jagdbezirk_paechter','jagdbezirk_paechter','bool','','','1',NULL,'Text','','','','8');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id7,'the_geom','the_geom','jagdbezirk_paechter','jagdbezirk_paechter','geometry','MULTIPOLYGON','','1',NULL,'Geometrie','','','','9');

UPDATE layer_attributes SET options = REPLACE(options, '7', @last_layer_id7) WHERE layer_id IN(@last_layer_id7) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK');

# Class-Definition
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES 
('Angliederung',@last_layer_id7,'(\'[art]\' eq \'agf\')','1','');
SET @last_class_id=LAST_INSERT_ID();

# Styledefinitionen
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES 
(NULL,'cross','9','0 0 0','255 120 12','-1 -1 -1','30','30',NULL,'',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES 
(NULL,'punkt','3','-1 -1 -1','','130 0 0','3','5',NULL,'',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 1);


# Kein Label für Angliederungsflächen


########################################
# Layer Jagdbezirk-Enklaven

# Layerdefinition
INSERT INTO layer (`Name`,`Datentyp`,`Gruppe`,`pfad`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`) VALUES 
('Jagdbezirk Enklaven','2',@group_id,'SELECT oid, id, name, art, flaeche,  bezirkid, concode, jb_zuordnung, status, the_geom FROM jagdbezirk_paechter WHERE art = \'jbe\'','the_geom from (select oid, oid as id, name, art, the_geom from jagdbezirk_paechter where art = \'jbe\') as foo using unique oid using srid=2398','','','','','','name','100001','399','',@connection,'','6','art','id','3','pixels','2398','jagdbezirke.php','1','50',NULL,'99','600001','','EPSG:2398','','1.1.0','image/png','60','','','','','1','0','','','untere Jagdbehörde');
SET @last_layer_id8=LAST_INSERT_ID();

# Layer-Attribute
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id8,'art','art','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','15','Auswahlfeld','select bezeichnung as output, art as value from jagdbezirkart','Art','','3');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id8,'bezirkid','bezirkid','jagdbezirk_paechter','jagdbezirk_paechter','int4','','','1','32','Text','','','','5');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id8,'concode','concode','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','5','Text','','','','6');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id8,'flaeche','flaeche','jagdbezirk_paechter','jagdbezirk_paechter','numeric','','','1',NULL,'Text','','','','4');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id8,'id','id','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','1');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id8,'jb_zuordnung','jb_zuordnung','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','7');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id8,'name','name','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','50','Text','','Name','','2');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id8,'oid','oid','jagdbezirk_paechter','jagdbezirk_paechter','oid','','','1',NULL,'Text','','','','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id8,'status','status','jagdbezirk_paechter','jagdbezirk_paechter','bool','','','1',NULL,'Text','','','','8');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id8,'the_geom','the_geom','jagdbezirk_paechter','jagdbezirk_paechter','geometry','MULTIPOLYGON','','1',NULL,'Geometrie','','','','9');

UPDATE layer_attributes SET options = REPLACE(options, '8', @last_layer_id8) WHERE layer_id IN(@last_layer_id8) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK');

# Class-Definition
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES 
('Enklave',@last_layer_id8,'(\'[art]\' eq \'jbe\')','1','');
SET @last_class_id=LAST_INSERT_ID();

# Styledefinitionen
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES 
(NULL,'cross','9','0 0 0','255 0 0','-1 -1 -1','30','30',NULL,'',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES 
(NULL,'punkt','3','-1 -1 -1','','130 0 0','3','5',NULL,'',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 1);


# Kein Label für Jagdbezirk-Enklaven


########################################
# Layer jadbezirksfreie Flächen

# Layerdefinition
INSERT INTO layer (`Name`,`Datentyp`,`Gruppe`,`pfad`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`) VALUES
('jadbezirksfreie Flächen','2',@group_id,'SELECT oid, id, name, art, flaeche,  bezirkid, concode, jb_zuordnung, status, the_geom FROM jagdbezirk_paechter WHERE art = \'jbf\'','the_geom from (select oid, oid as id, name, art, the_geom from jagdbezirk_paechter where art = \'jbf\') as foo using unique oid using srid=2398','','','','','','name','100001','399','',@connection,'','6','art','id','3','pixels','2398','jagdbezirke.php','1','50',NULL,'99','600001','','EPSG:2398','','1.1.0','image/png','60','','','','','1','0','','','untere Jagdbehörde');
SET @last_layer_id9=LAST_INSERT_ID();

# Layer-Attribute
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id9,'art','art','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','15','Auswahlfeld','select bezeichnung as output, art as value from jagdbezirkart','Art','','3');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id9,'bezirkid','bezirkid','jagdbezirk_paechter','jagdbezirk_paechter','int4','','','1','32','Text','','','','5');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id9,'concode','concode','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','5','Text','','','','6');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id9,'flaeche','flaeche','jagdbezirk_paechter','jagdbezirk_paechter','numeric','','','1',NULL,'Text','','','','4');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id9,'id','id','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','1');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id9,'jb_zuordnung','jb_zuordnung','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','7');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id9,'name','name','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','50','Text','','Name','','2');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id9,'oid','oid','jagdbezirk_paechter','jagdbezirk_paechter','oid','','','1',NULL,'Text','','','','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id9,'status','status','jagdbezirk_paechter','jagdbezirk_paechter','bool','','','1',NULL,'Text','','','','8');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id9,'the_geom','the_geom','jagdbezirk_paechter','jagdbezirk_paechter','geometry','MULTIPOLYGON','','1',NULL,'Geometrie','','','','9');

UPDATE layer_attributes SET options = REPLACE(options, '9', @last_layer_id9) WHERE layer_id IN(@last_layer_id9) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK');

# Class-Definition
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES
('jagdbezirksfrei',@last_layer_id9,'(\'[art]\' eq \'jbf\')','1','');
SET @last_class_id=LAST_INSERT_ID();

# Styledefinitionen
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES
(NULL,'cross','9','0 0 0','22 94 255','-1 -1 -1','30','30',NULL,'',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES
(NULL,'punkt','3','-1 -1 -1','','130 0 0','3','5',NULL,'',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 1);


# Kein Label für jadbezirksfreie Flächen


########################################
# Layer Anpachtflächen

# Layerdefinition
INSERT INTO layer (`Name`,`Datentyp`,`Gruppe`,`pfad`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`) VALUES
('Anpachtflächen','2',@group_id,'SELECT oid, id, name, art, flaeche,  bezirkid, concode, jb_zuordnung, status, the_geom FROM jagdbezirk_paechter WHERE art = \'apf\'','the_geom from (select oid, the_geom, id, art, name from jagdbezirk_paechter where art = \'apf\') as foo using unique oid using srid=2398','','','','','','name','100001','399','',@connection,'','6','art','id','1','pixels','2398','jagdbezirke.php','1','50',NULL,'99','500001','','EPSG:2398','','1.1.0','image/png','60','','','','','1','0','','','untere Jagdbehörde');
SET @last_layer_id10=LAST_INSERT_ID();

# Layer-Attribute
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id10,'art','art','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','15','Text','','','','3');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id10,'bezirkid','bezirkid','jagdbezirk_paechter','jagdbezirk_paechter','int4','','','1','32','Text','','','','5');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id10,'concode','concode','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','5','Text','','','','6');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id10,'flaeche','flaeche','jagdbezirk_paechter','jagdbezirk_paechter','numeric','','','1',NULL,'Text','','','','4');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id10,'id','id','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','1');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id10,'jb_zuordnung','jb_zuordnung','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','10','Text','','','','7');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id10,'name','name','jagdbezirk_paechter','jagdbezirk_paechter','varchar','','','1','50','Text','','','','2');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id10,'oid','oid','jagdbezirk_paechter','jagdbezirk_paechter','oid','','','1',NULL,'Text','','','','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id10,'status','status','jagdbezirk_paechter','jagdbezirk_paechter','bool','','','1',NULL,'Text','','','','8');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id10,'the_geom','the_geom','jagdbezirk_paechter','jagdbezirk_paechter','geometry','MULTIPOLYGON','','1',NULL,'Text','','','','9');

UPDATE layer_attributes SET options = REPLACE(options, '10', @last_layer_id10) WHERE layer_id IN(@last_layer_id10) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK');

# Class-Definition
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES
('Anpachtfläche',@last_layer_id10,'','1','');
SET @last_class_id=LAST_INSERT_ID();

# Styledefinitionen
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES
(NULL,'cross','9','0 0 0','255 215 0','-1 -1 -1','30','30','0','',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES
(NULL,'punkt','3','-1 -1 -1','','130 0 0','3','5',NULL,'',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 1);


# Kein Label für Anpachtflächen


########################################
# Layer EJB - Umringe 
# (dient nur der besseren Kenntlichmachung
# eines zu bearbeitenden EJB in der Karte,
# zeichnet einen Umring um den EJB in Bearbeitung
# und alle zugehörigen Angliederungs-, Abtrennungsflächen etc.)

# Layerdefinition
INSERT INTO layer (`Name`,`Datentyp`,`Gruppe`,`pfad`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`) VALUES
('EJB Umringe','2',@group_id,'','the_geom from (select a.oid, a.oid as id, case when b.the_geom is null then a.the_geom else geomunion(a.the_geom,b.the_geom) end as the_geom from jagdbezirke a left join jagdbezirke b on a.id=b.jb_zuordnung where isvalid(a.the_geom)=true and a.art=\'ejb\') as foo using unique oid using srid=2398','','','','','','','100001','399','',@connection,'','6','id','id','1','pixels','2398','','0','100',NULL,'99','500001','','EPSG:2398','','1.1.0','image/png','60','','','','','0','0','','','untere Jagdbehörde');
SET @last_layer_id11=LAST_INSERT_ID();


# Keine Layer-Attribute, nicht abfragbar


# Class-Definition
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES
(' ',@last_layer_id11,'','1','');
SET @last_class_id=LAST_INSERT_ID();

# Styledefinitionen
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES
(NULL,'punkt','3','- 1 -1 -1','','0 0 0','2','6',NULL,'',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);


# Kein Label für EJB-Umringe


########################################
# Layer EJB Verdachtsflächen
# (dient nur der Identifizierung möglicher
# EJB-Flächen - die angezeigte Fläche
# ist KEIN EJB!)

# Layerdefinition
INSERT INTO layer (`Name`,`Datentyp`,`Gruppe`,`pfad`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`) VALUES
('EJB Verdachtsflächen','2',@group_id,'SELECT oid, eigentuemer, flaeche, the_geom FROM ejb_verdachtsflaechen WHERE 1=1','the_geom from ejb_verdachtsflaechen using unique oid using srid=2398','','','','','','','100001','399','',@connection,'','6','oid','oid','3','pixels','2398','','1','70',NULL,'99','600001','','EPSG:2398','','1.1.0','image/png','60','','','','','1','0','','','untere Jagdbehörde');
SET @last_layer_id12=LAST_INSERT_ID();

# Layer-Attribute
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id12,'eigentuemer','eigentuemer','ejb_verdachtsflaechen','ejb_verdachtsflaechen','varchar','','','1',NULL,'Textfeld','','Eigentümer','','1');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id12,'flaeche','flaeche','ejb_verdachtsflaechen','ejb_verdachtsflaechen','int4','','','1','32','Text','','Fläche (ca.) [m²]','','2');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id12,'oid','oid','','','oid','','',NULL,NULL,'Text','','','','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`form_element_type`,`options`,`alias`,`tooltip`,`order`) VALUES(@last_layer_id12,'the_geom','the_geom','ejb_verdachtsflaechen','ejb_verdachtsflaechen','geometry','POLYGON','','1',NULL,'Geometrie','','','','3');

UPDATE layer_attributes SET options = REPLACE(options, '12', @last_layer_id12) WHERE layer_id IN(@last_layer_id12) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK');

# Class-Definition
INSERT INTO classes (`Name`,`Layer_ID`,`Expression`,`drawingorder`,`text`) VALUES
(' ',@last_layer_id12,'','1','');
SET @last_class_id=LAST_INSERT_ID();

# Styledefinitionen
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES
(NULL,'','5','196 215 241','','-1 -1 -1',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 0);
INSERT INTO styles (`symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem`) VALUES
(NULL,'punkt','4','-1 -1 -1','','255 73 46','1','6',NULL,'',NULL,NULL,NULL,NULL,'');
 SET @last_style_id=LAST_INSERT_ID();
INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, 1);


# Kein Label für EJB-Verdachtsflächen




########################################
# SQL für die Erzeugung von EJB-Verdachtsflächen
# in der POSTGIS-DB


# Tabellendefinition
####################
#
# CREATE TABLE ejb_verdachtsflaechen
# (
#   eigentuemer character varying,
#   flaeche integer
# )
# WITH (
#   OIDS=TRUE
# );
# SELECT AddGeometryColumn('public', 'ejb_verdachtsflaechen','the_geom',2398,'POLYGON', 2);
# ALTER TABLE ejb_verdachtsflaechen DROP CONSTRAINT enforce_geotype_the_geom;
# ALTER TABLE ejb_verdachtsflaechen ADD CONSTRAINT enforce_geotype_the_geom CHECK (geometrytype(the_geom) = 'POLYGON'::text OR geometrytype(the_geom) = 'MULTIPOLYGON'::text OR the_geom IS NULL);
# 
# CREATE INDEX ixejbverd_the_geom_gist
#   ON ejb_verdachtsflaechen
#   USING gist
#   (the_geom);
# 
# 
# 
# SQL-Abfrage 
####################
#
# DROP INDEX ixejbverd_the_geom_gist;
# 
# TRUNCATE ejb_verdachtsflaechen;
# 
# INSERT INTO ejb_verdachtsflaechen 
# SELECT 
#  eigentuemer, round(area(st_buffer(the_geom, -10))) as flaeche, st_buffer(the_geom, -10) as the_geom 
# FROM (
#   select 
#    (st_dump(st_memunion(st_buffer(o.the_geom,10)))).geom as the_geom, 
#    array_to_string(array(
#      select rtrim(name1,',') 
#      from alb_g_eigentuemer ee, alb_g_namen nn 
#      where ee.lfd_nr_name=nn.lfd_nr_name 
#      and ee.bezirk=e.bezirk 
#      and ee.blatt=e.blatt order by rtrim(name1,',')),' || '
#    ) as eigentuemer 
#   from alb_g_namen n, alb_g_eigentuemer e, alb_g_buchungen b, alknflst f, alkobj_e_fla o 
#   where e.lfd_nr_name=n.lfd_nr_name 
#   and e.bezirk=b.bezirk 
#   and e.blatt=b.blatt 
#   and b.flurstkennz=f.flurstkennz 
#   and f.objnr=o.objnr 
#   group by e.bezirk, e.blatt
#  ) as foo 
# WHERE area(st_buffer(the_geom, -10))>750000
# 
# VACUUM ANALYZE ejb_verdachtsflaechen;
# 
# CREATE INDEX ixejbverd_the_geom_gist
# ON ejb_verdachtsflaechen
# USING gist
# (the_geom );
