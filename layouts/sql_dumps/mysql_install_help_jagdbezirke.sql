##########################
# Layer für Jagdbezirke  #
##########################
# Benutzer für den Zugriff auf die PostGIS-Datenbank
SET @pg_user='kvwmap';
SET @pg_password='kvwmap';
SET @pg_dbname='kvwmapsp';
# Layer in mysql-Datenbank
SET @gruppe_id=5;
SET @epsg_code=2398;


########################################
# Layer Gemeinschaftliche Jagdbezirke

INSERT INTO `layer` SET
`Name`='Gemein.&nbsp;Jagdbezirke',  
`Datentyp`='2', 
`Gruppe`=@gruppe_id,  
`pfad`=CONCAT("SELECT oid, id, name, art, flaeche, anzahl_paechter, bezirkid, concode, the_geom FROM jagdbezirk_paechter WHERE art = 'gjb'"),  
`Data`=CONCAT("the_geom from (select the_geom, id, name from jagdbezirk_paechter where art = 'gjb') as foo using unique id using srid=",@epsg_code),  
`labelitem`='name',  
`labelmaxscale`='100002',  
`labelminscale`='400',  
`connection`=CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname), 
`connectiontype`='6',  
`classitem`='id',  
`filteritem`='id',  
`tolerance`='3',  
`toleranceunits`='pixels',  
`epsg_code`=@epsg_code,  
`queryable`='0',  
`ows_srs`= CONCAT('EPSG:', @epsg_code),  
`wms_server_version`='1.1.0',  
`wms_format`='image/png',  
`wms_connectiontimeout`='60',  
`logconsume`='0';  

# Abfragen des dabei erzeugten Autowertes für die Layer_id
SET @last_layer_id=LAST_INSERT_ID();

# Anlegen einer Klasse für die Darstellung des Layers

INSERT INTO `classes` SET 
`Name`='GJB',
`Layer_ID`=@last_layer_id;

# Abfragen des dabei erzeugten Autowertes für die Class_id und Zuweisung zu einer Variable
SET @last_class_id=LAST_INSERT_ID();


# Anlegen eines Styles für die Darstellung der Klasse
INSERT INTO `styles` SET 
`color`='250 205 150',  
`outlinecolor`='-1 -1 -1'; 

# Abfrage der dabei erzeugten Style_ID
SET @last_style_id=LAST_INSERT_ID();

# Zuweisung des Styles zur Classe in Tabelle u_styles2classes
INSERT INTO u_styles2classes (`class_id`, `style_id`,`drawingorder`) VALUES (@last_class_id,@last_style_id,10);

# Anlegen eines Styles für die Darstellung der Klasse

INSERT INTO `styles` SET
`symbolname`='punkt',
`size`='4',
`color`='-1 -1 -1',
`outlinecolor`='0 65 0',
`minsize`='2',
`maxsize`='5';
 
# Abfrage der dabei erzeugten Style_ID
SET @last_style_id=LAST_INSERT_ID();

# Zuweisung des Styles zur Classe in Tabelle u_styles2classes
INSERT INTO u_styles2classes (`class_id`, `style_id`,`drawingorder`) VALUES (@last_class_id,@last_style_id,20);

#Anlegen eines Labels für die Darstellung des Textes der Klasse

INSERT INTO `labels` SET 
`font`='arial',  
`color`='160 110 10',  
`outlinecolor`='255 200 160',  
`size`='9',  
`minsize`='8',  
`maxsize`='12',  
`position`='1',  
`antialias`='1',  
`partials`='1';  

# Abfragen des dabei erzeugten Autowertes für das Label und Zuweisen zu einer Variable
SET @last_label_id=LAST_INSERT_ID();

# Zuweisen des Labels zur Klasse in der Tabelle u_labels2classes
INSERT INTO `u_labels2classes` (`class_id`,`label_id`) VALUES (@last_class_id,@last_label_id);


########################################
# Layer Eigenjagdbezirke

INSERT INTO `layer` SET
`Name`='Eigenjagdbezirke',  
`Datentyp`='2', 
`Gruppe`=@gruppe_id,  
`pfad`=CONCAT("SELECT oid, id, name, art, flaeche, anzahl_paechter, bezirkid, concode, the_geom FROM jagdbezirk_paechter WHERE art = 'ejb'"),  
`Data`=CONCAT("the_geom from (select the_geom, id, name from jagdbezirk_paechter where art = 'ejb') as foo using unique id using srid=",@epsg_code),  
`labelitem`='name',  
`labelmaxscale`='100002',  
`labelminscale`='400',  
`connection`=CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname), 
`connectiontype`='6',  
`classitem`='id',  
`filteritem`='id',  
`tolerance`='3',  
`toleranceunits`='pixels',  
`epsg_code`=@epsg_code,  
`queryable`='0',  
`ows_srs`= CONCAT('EPSG:', @epsg_code),  
`wms_server_version`='1.1.0',  
`wms_format`='image/png',  
`wms_connectiontimeout`='60',  
`logconsume`='0';

# Abfragen des dabei erzeugten Autowertes für die Layer_id
SET @last_layer_id=LAST_INSERT_ID();

# Anlegen einer Klasse für die Darstellung des Layers

INSERT INTO `classes` SET 
`Name`='EJB',
`Layer_ID`=@last_layer_id;

# Abfragen des dabei erzeugten Autowertes für die Class_id und Zuweisung zu einer Variable
SET @last_class_id=LAST_INSERT_ID();

# Anlegen eines Styles für die Darstellung der Klasse
INSERT INTO `styles` SET 
`color`='240 240 120', 
`outlinecolor`='-1 -1 -1'; 


# Abfrage der dabei erzeugten Style_ID
SET @last_style_id=LAST_INSERT_ID();

# Zuweisung des Styles zur Classe in Tabelle u_styles2classes
INSERT INTO u_styles2classes (`class_id`, `style_id`,`drawingorder`) VALUES (@last_class_id,@last_style_id,10);

# Anlegen eines Styles für die Darstellung der Klasse

INSERT INTO `styles` SET
`symbolname`='punkt',
`size`='4',
`color`='-1 -1 -1',
`outlinecolor`='0 65 0',
`minsize`='2',
`maxsize`='5';

# Abfrage der dabei erzeugten Style_ID
SET @last_style_id=LAST_INSERT_ID();

# Zuweisung des Styles zur Classe in Tabelle u_styles2classes
INSERT INTO u_styles2classes (`class_id`, `style_id`,`drawingorder`) VALUES (@last_class_id,@last_style_id,20);

#Anlegen eines Labels für die Darstellung des Textes der Klasse

INSERT INTO `labels` SET 
`font`='arial',  
`color`='0 65 0',  
`outlinecolor`='255 255 200',  
`size`='8',  
`minsize`='7',  
`maxsize`='10',  
`position`='1',  
`antialias`='1',  
`partials`='1';  

# Abfragen des dabei erzeugten Autowertes für das Label und Zuweisen zu einer Variable
SET @last_label_id=LAST_INSERT_ID();

# Zuweisen des Labels zur Klasse in der Tabelle u_labels2classes
INSERT INTO `u_labels2classes` (`class_id`,`label_id`) VALUES (@last_class_id,@last_label_id);


########################################
# Layer Teiljagdbezirke (Teiljagdbezirke können auch keine Geometrie haben!)

INSERT INTO `layer` SET
`Name`='Teiljagdbezirke',  
`Datentyp`='2', 
`Gruppe`=@gruppe_id,  
`pfad`=CONCAT("SELECT oid, id, name, art, flaeche, anzahl_paechter, bezirkid, concode, the_geom FROM jagdbezirk_paechter WHERE art = 'tjb'"),  
`Data`=CONCAT("the_geom from (select the_geom, name, id from jagdbezirk_paechter where art = 'tjb') as foo using unique id using srid=",@epsg_code),  
`labelitem`='name',  
`labelmaxscale`='100002',  
`labelminscale`='400',  
`connection`=CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname), 
`connectiontype`='6',  
`classitem`='id',  
`filteritem`='id',  
`tolerance`='3',  
`toleranceunits`='pixels',  
`epsg_code`=@epsg_code,  
`queryable`='0',  
`ows_srs`= CONCAT('EPSG:', @epsg_code),    
`wms_server_version`='1.1.0',  
`wms_format`='image/png',  
`wms_connectiontimeout`='60',  
`logconsume`='0';

# Abfragen des dabei erzeugten Autowertes für die Layer_id
SET @last_layer_id=LAST_INSERT_ID();

# Anlegen einer Klasse für die Darstellung des Layers

INSERT INTO `classes` SET 
`Name`='TJB',
`Layer_ID`=@last_layer_id;

# Abfragen des dabei erzeugten Autowertes für die Class_id und Zuweisung zu einer Variable
SET @last_class_id=LAST_INSERT_ID();

# Anlegen eines Styles für die Darstellung der Klasse

INSERT INTO `styles` SET
`symbolname`='cross',
`size`='15',
`color`='90 90 90',
`outlinecolor`='90 90 90',
`minsize`='15',
`maxsize`='15';

# Abfrage der dabei erzeugten Style_ID
SET @last_style_id=LAST_INSERT_ID();

# Zuweisung des Styles zur Classe in Tabelle u_styles2classes
INSERT INTO u_styles2classes (`class_id`, `style_id`,`drawingorder`) VALUES (@last_class_id,@last_style_id,20);

#Anlegen eines Labels für die Darstellung des Textes der Klasse

INSERT INTO `labels` SET 
`font`='arial',  
`color`='90 90 90',  
`outlinecolor`='220 220 220',  
`size`='8',  
`minsize`='7',  
`maxsize`='10',  
`position`='1',  
`antialias`='1',  
`partials`='1';  

# Abfragen des dabei erzeugten Autowertes für das Label und Zuweisen zu einer Variable
SET @last_label_id=LAST_INSERT_ID();

# Zuweisen des Labels zur Klasse in der Tabelle u_labels2classes
INSERT INTO `u_labels2classes` (`class_id`,`label_id`) VALUES (@last_class_id,@last_label_id);


########################################
# Layer Sonderflächen

INSERT INTO `layer` SET
`Name`='Sonderfl&auml;chen',  
`Datentyp`='2', 
`Gruppe`=@gruppe_id,  
`pfad`=CONCAT("SELECT oid, id, name, art, flaeche, anzahl_paechter, bezirkid, concode, the_geom FROM jagdbezirk_paechter WHERE art = 'sf'"),  
`Data`=CONCAT("the_geom from (select the_geom, id from jagdbezirk_paechter where art = 'sf') as foo using unique id using srid=",@epsg_code),  
`labelmaxscale`='100002',  
`labelminscale`='400',  
`connection`=CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname), 
`connectiontype`='6',  
`classitem`='id',  
`filteritem`='id',  
`tolerance`='3',  
`toleranceunits`='pixels',  
`epsg_code`=@epsg_code,  
`queryable`='0',  
`ows_srs`= CONCAT('EPSG:', @epsg_code),  
`wms_server_version`='1.1.0',  
`wms_format`='image/png',  
`wms_connectiontimeout`='60',  
`logconsume`='0';

# Abfragen des dabei erzeugten Autowertes für die Layer_id
SET @last_layer_id=LAST_INSERT_ID();

# Anlegen einer Klasse für die Darstellung des Layers

INSERT INTO `classes` SET 
`Name`='SF',
`Layer_ID`=@last_layer_id;

# Abfragen des dabei erzeugten Autowertes für die Class_id und Zuweisung zu einer Variable
SET @last_class_id=LAST_INSERT_ID();

# Anlegen eines Styles für die Darstellung der Klasse

INSERT INTO `styles` SET
`symbolname`='cross',
`size`='5',
`color`='240 0 120',
`outlinecolor`='240 0 120',
`minsize`='5',
`maxsize`='5';

# Abfrage der dabei erzeugten Style_ID
SET @last_style_id=LAST_INSERT_ID();

# Zuweisung des Styles zur Classe in Tabelle u_styles2classes
INSERT INTO u_styles2classes (`class_id`, `style_id`,`drawingorder`) VALUES (@last_class_id,@last_style_id,20);

# Kein Label für Sonderflächen