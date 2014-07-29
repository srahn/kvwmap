START TRANSACTION;

###############################################################
# Konstanten
###############################################################
SET @pg_user='';
SET @pg_password='';
SET @pg_dbname='kvwmapsp172';
SET @user_id=1;
SET @stelle_id=1;
SET @group_id=42; ###### Gruppen_id wählen, z.B. die von Kataster
SET @drawingorder=14;
SET @epsg_code=2398;

#################################################################
# Layer Grenzpunke verhandelt/nichtverhandelt und Punktnummern  #
#################################################################
# Eintragen eines Layers zur Darstellung des Rechtsstatus verhandelt/nicht verhandelt der Grenzpunkte des Liegenschaftskatasters
INSERT INTO layer (Name, Datentyp, Gruppe, pfad, Data, labelitem, labelmaxscale,
 labelminscale,labelrequires, `connection`, connectiontype, classitem, filteritem, tolerance, toleranceunits, transparency)
 VALUES ("Rechtsstatus", 0, @group_id,"SELECT *,x(the_geom) AS x,y(the_geom) AS y FROM fp_punkte WHERE art IN (2)"
 ,"the_geom from fp_punkte", "pktnr", 10000, 0, "([Grenzpunkttexte] = 1)"
 ,CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname), 6, "art", "pkz", 3, "pixels", NULL);

# Abfragen des dabei erzeugten Autowertes für die Layer_id und Zuweisung zu einer Variable
SET @last_layer_id=LAST_INSERT_ID();

########### Eintragen der Klasse verhandelte Grenzpunkte
INSERT INTO classes (Name, Layer_ID, Expression,drawingorder) VALUES ('verhandelt', @last_layer_id, '([art]=2 AND [verhandelt]=1)',1);

# Abfragen des dabei erzeugten Autowertes für die Class_id und Zuweisung zu einer Variable
SET @last_class_id=LAST_INSERT_ID();
# Eintragen des Styles (Grüner Punkt)
INSERT INTO styles (symbol, symbolname, size, color, backgroundcolor, outlinecolor,minsize,maxsize)
 VALUES (26, 'punkt', 10, '50 150 50', NULL, '0 0 0',5,10);

# Abfragen des dabei erzeugten Autowertes für die Style_id und Zuweisung zu einer Variable
SET @last_style_id=LAST_INSERT_ID();
# Zuweisung des Styles zur Classe
INSERT INTO u_styles2classes (class_id,style_id,drawingorder) VALUES (@last_class_id,@last_style_id,1);

# Eintragen des Labels für die Beschriftung der Grenzpunkte
INSERT INTO labels (color,font,outlinecolor,size,minsize,maxsize,position,the_force)
 VALUES ('50 150 50','arial','255 255 255',10,5,10,4,0);

# Abfragen des dabei erzeugten Autowertes für das Label und Zuweisen zu einer Variable
SET @last_label_id=LAST_INSERT_ID();
# Zuweisen des Labels zur Klasse in der Tabelle u_labels2classes
INSERT INTO u_labels2classes (class_id,label_id) VALUES (@last_class_id,@last_label_id);

########### Eintragen der Klasse nicht verhandelte Grenzpunkte
INSERT INTO classes (Name, Layer_ID, Expression,drawingorder) VALUES ('nicht verhandelt', @last_layer_id, '([art]=2 AND [verhandelt]=0)',1);

# Abfragen des dabei erzeugten Autowertes für die Class_id und Zuweisung zu einer Variable
SET @last_class_id=LAST_INSERT_ID();

# Eintragen des Styles (Roter Punkt)
INSERT INTO styles (symbol, symbolname, size, color, backgroundcolor, outlinecolor,minsize,maxsize)
 VALUES (26, 'punkt', 10, '150 50 50', NULL, '0 0 0',5,10);

# Abfragen des dabei erzeugten Autowertes für die Style_id und Zuweisung zu einer Variable
SET @last_style_id=LAST_INSERT_ID();
# Zuweisung des Styles zur Classe
INSERT INTO u_styles2classes (class_id,style_id,drawingorder) VALUES (@last_class_id,@last_style_id,1);

# Eintragen des Labels für die Beschriftung der Grenzpunkte
INSERT INTO labels (color,font,outlinecolor,size,minsize,maxsize,position,the_force)
 VALUES ('150 50 50','arial','255 255 255',10,5,10,4,0);

# Abfragen des dabei erzeugten Autowertes für das Label und Zuweisen zu einer Variable
SET @last_label_id=LAST_INSERT_ID();
# Zuweisen des Labels zur Klasse in der Tabelle u_labels2classes
INSERT INTO u_labels2classes (class_id,label_id) VALUES (@last_class_id,@last_label_id);

########### Eintagen eines Layers, über den die Beschriftung der Grenzpunkte ein und aus geschaltet werden können
# In diesem Layer wird nichts gezeichnet, ist er jedoch an, wird die Beschriftung des Grenzpunktlayers dargestellt
# sonst nicht. Die Abhängigkeit steht in der Layerbeschreibung im Attribut LABELREQUIRES im Grenzpunktlayer
# Eintragen eines Layers für die Beschriftung der Grenzpunkte des Liegenschaftskatasters
INSERT INTO layer (Name, Datentyp, Gruppe, Data, `connection`, connectiontype, classitem)
 VALUES ("Grenzpunkttexte", 0, @group_id,"the_geom from fp_punkte"
 ,CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname), 6, "art");

###########################################
# Layer Grenzpunke vermarkt/nichtvermarkt #
###########################################

SET @drawingorder=14;

# Eintragen eines Layers zur Darstellung der Vermarkung der Grenzpunkte
INSERT INTO layer (Name, Datentyp, Gruppe, pfad, Data, tileindex, tileitem, labelitem
,labelmaxscale, labelminscale, labelrequires, `connection`, connectiontype, classitem, filteritem
, tolerance, toleranceunits, transparency, epsg_code, ows_srs, wms_name, wms_server_version, wms_format
, wms_connectiontimeout)
 VALUES ('Abmarkung', 0, @group_id
 ,"SELECT *,x(the_geom) AS x,y(the_geom) AS y FROM fp_punkte WHERE art IN (2)"
 ,"the_geom from fp_punkte"
 , NULL, NULL, 'pktnr', 10000, 0, '([Grenzpunkttexte] = 1)',CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname)
 , 6, 'art', 'pkz', 3, 'pixels', NULL, @epsg_code,CONCAT("EPSG:",@epsg_code), NULL, '1.1.0', 'image/png', 60);
# Abfragen des dabei erzeugten Autowertes für die Layer_id und Zuweisung zu einer Variable
SET @last_layer_id=LAST_INSERT_ID();

########### Eintragen der Klasse vermarkte Grenzpunkte
INSERT INTO classes (Name, Layer_ID, Expression,drawingorder)
 VALUES ('vermarkt', @last_layer_id,"(!('[vma]'='070' OR '[vma]'='071' OR '[vma]'='073' OR '[vma]'='088' OR '[vma]'='089' OR '[vma]'='090' OR '[vma]'='091' OR '[vma]'='093' OR '[vma]'='095'))",1);
# Abfragen des dabei erzeugten Autowertes für die Class_id und Zuweisung zu einer Variable
SET @last_class_id=LAST_INSERT_ID();
# Eintragen des Styles für abgemarkte Grenzpunkte
# Grüner Punkt
INSERT INTO styles (symbol, symbolname, size, color, backgroundcolor, outlinecolor,minsize,maxsize)
 VALUES (25,'Grenzpunkt_vermarkt', 8, '255 255 255', '0 255 0', '0 0 0',6,8);
# Abfragen des dabei erzeugten Autowertes für die Style_id und Zuweisung zu einer Variable
SET @last_style_id=LAST_INSERT_ID();
# Zuweisung des Styles zur Classe
INSERT INTO u_styles2classes (class_id,style_id) VALUES (@last_class_id,@last_style_id);

########### Eintragen der Klasse nicht vermarkter Grenzpunkte
INSERT INTO classes (Name, Layer_ID, Expression,drawingorder) VALUES ('nicht verhandelt', @last_layer_id
,"('[vma]'='070' OR '[vma]'='071' OR '[vma]'='073' OR '[vma]'='088' OR '[vma]'='089' OR '[vma]'='090' OR '[vma]'='091' OR '[vma]'='093' OR '[vma]'='095')",1);

# Abfragen des dabei erzeugten Autowertes für die Class_id und Zuweisung zu einer Variable
SET @last_class_id=LAST_INSERT_ID();
# Eintragen der Styles für nichtabgemarkte Grenzpunkte, Weisser Punkt zur Freistellung mit kleinerem schwarzen Punkt darauf
# weisser Punkt zur Punktfreistellung
INSERT INTO styles (symbol, size, color, backgroundcolor, outlinecolor,minsize,maxsize)
 VALUES (25, 8, '255 255 255', NULL, '255 255 255',4,8);

# Abfragen des dabei erzeugten Autowertes für die Style_id und Zuweisung zu einer Variable
SET @last_style_id=LAST_INSERT_ID();
# Zuweisung des Styles zur Classe
INSERT INTO u_styles2classes (class_id,style_id,drawingorder) VALUES (@last_class_id,@last_style_id,1);

# schwarzer Punkt darüber
INSERT INTO styles (symbol, symbolname,size, color, backgroundcolor, outlinecolor,minsize,maxsize)
 VALUES (25, 'Grenzpunkt_unvermarkt', 3, '0 0 0', '255 255 255', '0 0 0',1,3);

# Abfragen des dabei erzeugten Autowertes für die Style_id und Zuweisung zu einer Variable
SET @last_style_id=LAST_INSERT_ID();
# Zuweisung des Styles zur Classe
INSERT INTO u_styles2classes (class_id,style_id,drawingorder) VALUES (@last_class_id,@last_style_id,2);

##########################################################
# Einträge für die Festpunkte des Liegenschaftskatasters #
##########################################################

# drawingorder anpassen sollte am höchsten in der Stelle sein (on top)
SET @drawingorder=21;

# Eintragen eines Layers für die Festpunkte des Liegenschaftskatasters
INSERT INTO layer (Name, Datentyp, Gruppe, pfad, Data, labelitem, labelmaxscale,
 labelminscale,labelrequires, `connection`, connectiontype, classitem, filteritem, tolerance, toleranceunits, transparency)
 VALUES ("Festpunkte", 0, @group_id,"SELECT *,x(the_geom) AS x,y(the_geom) AS y FROM fp_punkte_temp WHERE art IN (0,1,5,6)"
 ,"the_geom from fp_punkte_temp", "pktnr", 25000, 0, "([Festpunkttexte] = 1)"
 ,CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname), 6, "art", "pkz", 3, "pixels", NULL);

# Abfragen des dabei erzeugten Autowertes für die Layer_id und Zuweisung zu einer Variable
SET @last_layer_id=LAST_INSERT_ID();

########### Eintragen der Klasse Topographischer Festpunkt TP
INSERT INTO classes (Name, Layer_ID, Expression,drawingorder) VALUES ('TP', @last_layer_id, '([art]=0)',1);

# Abfragen des dabei erzeugten Autowertes für die Class_id und Zuweisung zu einer Variable
SET @last_class_id=LAST_INSERT_ID();
# Eintragen der Styles für die Klasse Topographischer Festpunkte
# 1. Style ein weiss gefülltes gleichseitiges Dreieck, welches auf einer Kante steht.
#    OP´s mit einem auf dem Kopf stehenden Dreieck sind noch nicht berücksichtigt
#    verwendet wird das Symbol 'glseitstehdreieck' aus der Datei symbole.sym
#    Farbe selbst anpassen, hier schwarz
INSERT INTO styles (symbol, symbolname, size, color, backgroundcolor, outlinecolor,minsize,maxsize)
 VALUES (34, 'glseitstehdreieck', 10, '255 255 255', NULL, '0 50 150',5,10);
# Abfragen des dabei erzeugten Autowertes für die Style_id und Zuweisung zu einer Variable
SET @last_style_id=LAST_INSERT_ID();
# Zuweisung des Styles zur Classe
INSERT INTO u_styles2classes (class_id,style_id,drawingorder) VALUES (@last_class_id,@last_style_id,1);
# 2. Ein schwarzer Punkt in der Mitte des Dreiecks, Symbol 'punkt'
INSERT INTO styles (symbol, symbolname, size, color, backgroundcolor, outlinecolor,minsize,maxsize)
 VALUES (26, 'punkt', 1, '0 0 0', NULL, '0 50 150',1,2);
# Abfragen des dabei erzeugten Autowertes für die Style_id und Zuweisung zu einer Variable
SET @point_style_id=LAST_INSERT_ID();
# Zuweisung des Styles zur Classe
INSERT INTO u_styles2classes (class_id, style_id,drawingorder) VALUES (@last_class_id,@point_style_id,2);
# Eintragen eines neuen Labels für die Beschriftung der TP (doppelt unterstrichen)
INSERT INTO labels (color,font,outlinecolor,size,minsize,maxsize,position,the_force)
 VALUES ('0 50 150','arial_underline_2','255 255 255',10,5,10,4,1);
# Abfragen des dabei erzeugten Autowertes für das Label und Zuweisen zu einer Variable
SET @last_label_id=LAST_INSERT_ID();
# Zuweisen des Labels zur Klasse in der Tabelle u_labels2classes
INSERT INTO u_labels2classes (class_id,label_id) VALUES (@last_class_id,@last_label_id);

########### Eintragen der Klasse Topographischer Orientierungspunkt OP
INSERT INTO classes (Name, Layer_ID, Expression,drawingorder) VALUES ('OP', @last_layer_id, '([art]=6)',2);
# Abfragen des dabei erzeugten Autowertes für die Class_id und Zuweisung zu einer Variable
SET @last_class_id=LAST_INSERT_ID();
# Eintragen der Styles für die Klasse Topographischer Orientierungspunkt
# 1. Style ein weiss gefülltes gleichseitiges Dreieck, welches auf einer Spitze steht.
#    verwendet wird das Symbol 'glseitkopfdreieck' aus der Datei symbole.sym
#    Farbe selbst anpassen, hier schwarz
INSERT INTO styles (symbol, symbolname, size, color, backgroundcolor, outlinecolor,minsize,maxsize)
 VALUES (35, 'glseitkopfdreieck', 10, '255 255 255', NULL, '0 50 150',5,10);
# Abfragen des dabei erzeugten Autowertes für die Style_id und Zuweisung zu einer Variable
SET @last_style_id=LAST_INSERT_ID();
# Zuweisung des Styles zur Classe
INSERT INTO u_styles2classes (class_id,style_id,drawingorder) VALUES (@last_class_id,@last_style_id,1);
# 2. Ein Punkt in der Mitte des Dreiecks, Symbol 'punkt'
INSERT INTO styles (symbol, symbolname, size, color, backgroundcolor, outlinecolor,minsize,maxsize)
 VALUES (26, 'punkt', 1, '0 0 0', NULL, '0 50 150',1,2);
# Abfragen des dabei erzeugten Autowertes für die Style_id und Zuweisung zu einer Variable
SET @point_style_id=LAST_INSERT_ID();
# Zuweisung des Styles zur Classe
INSERT INTO u_styles2classes (class_id, style_id,drawingorder) VALUES (@last_class_id,@point_style_id,2);
# Zuweisen des Labels zur Klasse in der Tabelle u_labels2classes
INSERT INTO u_labels2classes (class_id,label_id) VALUES (@last_class_id,@last_label_id);

########### Eintragen der Klasse Aufnahmepunkt AP
INSERT INTO classes (Name, Layer_ID, Expression,drawingorder) VALUES ('AP', @last_layer_id, '([art]=1)',3);
# Abfragen des dabei erzeugten Autowertes für die Class_id und Zuweisung zu einer Variable
SET @last_class_id=LAST_INSERT_ID();
# Eintragen der Styles für die Klasse Aufnahmepunkt AP
# 1. Style ein weisser gefüllter Kreis.
#    verwendet wird das Symbol 'circle' aus der Datei symbole.sym
#    Farbe selbst anpassen, hier schwarz
INSERT INTO styles (symbol, symbolname, size, color, backgroundcolor, outlinecolor,minsize,maxsize)
 VALUES (32, 'filledcircle', 12, '255 255 255', NULL, '0 50 150',7,12);
# Abfragen des dabei erzeugten Autowertes für die Style_id und Zuweisung zu einer Variable
SET @last_style_id=LAST_INSERT_ID();
# Zuweisung des Styles zur Classe
INSERT INTO u_styles2classes (class_id, style_id,drawingorder) VALUES (@last_class_id,@last_style_id,1);
# 2. Ein schwarzer Punkt in der Mitte des Kreises, Symbol 'punkt'
#    Style ist dafür schon angelegt in @point_style_id
# Zuweisung des Styles zur Classe
INSERT INTO u_styles2classes (class_id, style_id,drawingorder) VALUES (@last_class_id,@point_style_id,2);
# Eintragen eines neuen Labels für die Beschriftung der TP (doppelt unterstrichen)
INSERT INTO labels (color,font,outlinecolor,size,minsize,maxsize,position,the_force)
 VALUES ('0 50 150','arial_underline_1','255 255 255',10,5,10,4,1);
# Abfragen des dabei erzeugten Autowertes für das Label und Zuweisen zu einer Variable
SET @last_label_id=LAST_INSERT_ID();
# Zuweisen des Labels zur Klasse in der Tabelle u_labels2classes
INSERT INTO u_labels2classes (class_id,label_id) VALUES (@last_class_id,@last_label_id);

########## Eintragen eines Layers für die Sicherungspunkte der AP´s (SiP)
INSERT INTO layer (Name, Datentyp, Gruppe, Data, labelmaxscale, labelminscale,labelrequires, `connection`, connectiontype, classitem)
 VALUES ("Sicherungspunkte", 0, @group_id,"the_geom from fp_punkte_temp", 25000, 0, "([Festpunkttexte] = 1)"
 ,CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname), 6, "art");
# Abfragen des dabei erzeugten Autowertes für die Layer_id und Zuweisung zu einer Variable
SET @last_layer_id=LAST_INSERT_ID();
# Zuweisung des Layers zu einer Stelle
INSERT INTO used_layer SET Stelle_ID=@stelle_id,Layer_ID=@last_layer_id,queryable='0',drawingorder=@drawingorder; 
# Zuweisung des Layers zur Rolle, der Benutzer darf den Layer innerhalb der Stelle sehen.
INSERT INTO u_rolle2used_layer (user_id,stelle_id,layer_id,aktivStatus,queryStatus) 
VALUES (@user_id,@stelle_id,@last_layer_id, '0', '0');
########### Eintragen der Klasse Sicherungspunkte der APs
INSERT INTO classes (Name, Layer_ID, Expression,drawingorder) VALUES ('SiP', @last_layer_id, '([art]=5)',1);
# Abfragen des dabei erzeugten Autowertes für die Class_id und Zuweisung zu einer Variable
SET @last_class_id=LAST_INSERT_ID();
# Eintragen der Styles für die Klasse Aufnahmepunkt AP
# 1. Style ein weisser gefüllter Kreis (etwas kleiner als die AP.
#    verwendet wird das Symbol 'circle' aus der Datei symbole.sym
#    Farbe selbst anpassen, hier schwarz
INSERT INTO styles (symbol, symbolname, size, color, backgroundcolor, outlinecolor,minsize,maxsize)
 VALUES (32, 'filledcircle', 8, '255 255 255', NULL, '0 50 150',5,8);
# Abfragen des dabei erzeugten Autowertes für die Style_id und Zuweisung zu einer Variable
SET @last_style_id=LAST_INSERT_ID();
# Zuweisung des Styles zur Classe
INSERT INTO u_styles2classes (class_id, style_id,drawingorder) VALUES (@last_class_id,@last_style_id,1);

########### Eintagen eines Layers, über den die Beschriftung der Festpunkte in und aus geschaltet werden können
# In diesem Layer wird nichts gezeichnet, ist er jedoch an, wird die Beschriftung des Festpunktlayers dargestellt
# sonst nicht. Die Abhängigkeit steht in der Layerbeschreibung im Attribut LABELREQUIRES im Festpunktlayer
# Eintragen eines Layers für die Beschriftung der Festpunkte des Liegenschaftskatasters
INSERT INTO layer (Name, Datentyp, Gruppe, Data, `connection`, connectiontype, classitem)
 VALUES ("Festpunkttexte", 0, @group_id,"the_geom from fp_punkte_temp"
 ,CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname), 6, "art");
# Abfragen des dabei erzeugten Autowertes für die Layer_id und Zuweisung zu einer Variable
SET @last_layer_id=LAST_INSERT_ID();
# Zuweisung des Layers zu einer Stelle
INSERT INTO used_layer SET Stelle_ID=@stelle_id,Layer_ID=@last_layer_id,queryable='0',drawingorder=@drawingorder; 
# Zuweisung des Layers zur Rolle, der Benutzer darf den Layer innerhalb der Stelle sehen.
INSERT INTO u_rolle2used_layer (user_id,stelle_id,layer_id,aktivStatus,queryStatus) 
VALUES (@user_id,@stelle_id,@last_layer_id, '0', '0');

############################################################
# Eintraege fuer die Festpunkte des Liegenschaftskatasters #
# entnommen aus den ALK-Tabellen der postgres Datenbank    #
############################################################
# drawingorder anpassen sollte am höhsten in der Stelle sein (on top)
SET @drawingorder=200;
SET @group_id=43;
SET @epsg_code=2398;

# Eintragen eines Layers fuer die Festpunkte des Liegenschaftskatasters als den ALK-Tabellen
INSERT INTO layer (Name, Datentyp, Gruppe, pfad, Data, labelitem, labelmaxscale,
 labelminscale,labelrequires, `connection`, connectiontype, classitem, filteritem, tolerance, toleranceunits, transparency)
 VALUES ("Festpunkte", 0, @group_id,CONCAT("SELECT p.objnr AS oid,p.objnr,p.nbz,p.pat AS art,p.pnr,p.nbz||'-'||p.pat||'-'||p.pnr AS pkz,p.nbz||'/'||p.nbz||p.pat||p.pnr||'.png' AS datei,x(o.the_geom) AS rw,y(o.the_geom) AS hw,o.the_geom,o.objart,o.bemerkung FROM alknpunkt AS p, alkobj_e_pkt AS o WHERE p.objnr=o.objnr AND p.pat IN (0,1,5,6) AND NOT Disjoint(o.the_geom,GeometryFromText('xxxx',",@epsg_code,"))"),
CONCAT("the_geom from (select p.objnr AS oid,trim(leading '0' from p.pnr) AS nr,p.pat AS art, o.objart,o.the_geom FROM alknpunkt AS p, alkobj_e_pkt AS o WHERE p.objnr=o.objnr) AS foo using unique oid using srid=",@epsg_code), "pkz", 50000, 0, "([Festpunkttexte] = 1)"
 ,CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname), 6, "objart", "pkz", 3, "pixels", NULL);
# Abfragen des dabei erzeugten Autowertes fr die Layer_id und Zuweisung zu einer Variable
SET @last_layer_id=LAST_INSERT_ID();

########### Eintragen der Klasse Topographischer Festpunkt TP
INSERT INTO classes (Name, Layer_ID, Expression,drawingorder) VALUES ('TP', @last_layer_id, '([art]=0)',1);
# Abfragen des dabei erzeugten Autowertes fuer die Class_id und Zuweisung zu einer Variable
SET @last_class_id=LAST_INSERT_ID();
# Eintragen der Styles fr die Klasse Topographischer Festpunkte
# 1. Style ein weiss geflltes gleichseitiges Dreieck, welches auf einer Kante steht.
#    OPs mit einem auf dem Kopf stehenden Dreieck sind noch nicht bercksichtigt
#    verwendet wird das Symbol 'glseitstehdreieck' aus der Datei symbole.sym
#    Farbe selbst anpassen, hier schwarz
INSERT INTO styles (symbol, symbolname, size, color, backgroundcolor, outlinecolor,minsize,maxsize)
 VALUES (34, 'glseitstehdreieck', 10, '255 255 255', NULL, '0 50 150',5,10);
# Abfragen des dabei erzeugten Autowertes fr die Style_id und Zuweisung zu einer Variable
SET @last_style_id=LAST_INSERT_ID();
# Zuweisung des Styles zur Classe
INSERT INTO u_styles2classes (class_id,style_id,drawingorder) VALUES (@last_class_id,@last_style_id,1);
# 2. Ein schwarzer Punkt in der Mitte des Dreiecks, Symbol 'punkt'
INSERT INTO styles (symbol, symbolname, size, color, backgroundcolor, outlinecolor,minsize,maxsize)
 VALUES (26, 'punkt', 1, '0 0 0', NULL, '0 50 150',1,2);
# Abfragen des dabei erzeugten Autowertes fr die Style_id und Zuweisung zu einer Variable
SET @point_style_id=LAST_INSERT_ID();
# Zuweisung des Styles zur Classe
INSERT INTO u_styles2classes (class_id, style_id,drawingorder) VALUES (@last_class_id,@point_style_id,2);
# Eintragen eines neuen Labels fr die Beschriftung der TP (doppelt unterstrichen)
INSERT INTO labels (color,font,outlinecolor,size,minsize,maxsize,position,the_force)
 VALUES ('0 50 150','arial_underline_2','255 255 255',10,5,10,4,1);
# Abfragen des dabei erzeugten Autowertes fr das Label und Zuweisen zu einer Variable
SET @last_label_id=LAST_INSERT_ID();
# Zuweisen des Labels zur Klasse in der Tabelle u_labels2classes
INSERT INTO u_labels2classes (class_id,label_id) VALUES (@last_class_id,@last_label_id);

########### Eintragen der Klasse Topographischer Orientierungspunkt OP
INSERT INTO classes (Name, Layer_ID, Expression,drawingorder) VALUES ('OP', @last_layer_id, '([art]=6)',2);
# Abfragen des dabei erzeugten Autowertes fr die Class_id und Zuweisung zu einer Variable
SET @last_class_id=LAST_INSERT_ID();
# Eintragen der Styles fr die Klasse Topographischer Orientierungspunkt
# 1. Style ein weiss geflltes gleichseitiges Dreieck, welches auf einer Spitze steht.
#    verwendet wird das Symbol 'glseitkopfdreieck' aus der Datei symbole.sym
#    Farbe selbst anpassen, hier schwarz
INSERT INTO styles (symbol, symbolname, size, color, backgroundcolor, outlinecolor,minsize,maxsize)
 VALUES (35, 'glseitkopfdreieck', 10, '255 255 255', NULL, '0 50 150',5,10);
# Abfragen des dabei erzeugten Autowertes fr die Style_id und Zuweisung zu einer Variable
SET @last_style_id=LAST_INSERT_ID();
# Zuweisung des Styles zur Classe
INSERT INTO u_styles2classes (class_id,style_id,drawingorder) VALUES (@last_class_id,@last_style_id,1);
# 2. Ein Punkt in der Mitte des Dreiecks, Symbol 'punkt'
INSERT INTO styles (symbol, symbolname, size, color, backgroundcolor, outlinecolor,minsize,maxsize)
 VALUES (26, 'punkt', 1, '0 0 0', NULL, '0 50 150',1,2);
# Abfragen des dabei erzeugten Autowertes fr die Style_id und Zuweisung zu einer Variable
SET @point_style_id=LAST_INSERT_ID();
# Zuweisung des Styles zur Classe
INSERT INTO u_styles2classes (class_id, style_id,drawingorder) VALUES (@last_class_id,@point_style_id,2);
# Zuweisen des Labels zur Klasse in der Tabelle u_labels2classes
INSERT INTO u_labelsSELECT p.objnr AS oid,p.objnr,p.nbz,p.pat AS art,p.pnr,p.nbz||'-'||p.pat||'-'||p.pnr AS pkz,p.nbz||p.pat||p.pnr||'.png' AS datei,x(o.the_geom) AS rw,y(o.the_geom) AS hw,o.the_geom,o.objart,o.bemerkung FROM alknpunkt AS p, alkobj_e_pkt AS o WHERE p.objnr=o.objnr AND o.pat IN (0,1,5,6) AND NOT Disjoint(o.the_geom,GeometryFromText('xxxx2classes (class_id,label_id) VALUES (@last_class_id,@last_label_id);

########### Eintragen der Klasse Aufnahmepunkt AP
INSERT INTO classes (Name, Layer_ID, Expression,drawingorder) VALUES ('AP', @last_layer_id, '([art]=1)',3);
# Abfragen des dabei erzeugten Autowertes fr die Class_id und Zuweisung zu einer Variable
SET @last_class_id=LAST_INSERT_ID();
# Eintragen der Styles fr die Klasse Aufnahmepunkt AP
# 1. Style ein weisser gefllter Kreis.
#    verwendet wird das Symbol 'circle' aus der Datei symbole.sym
#    Farbe selbst anpassen, hier schwarz
INSERT INTO styles (symbol, symbolname, size, color, backgroundcolor, outlinecolor,minsize,maxsize)
 VALUES (32, 'filledcircle', 12, '255 255 255', NULL, '0 50 150',7,12);
# Abfragen des dabei erzeugten Autowertes fr die Style_id und Zuweisung zu einer Variable
SET @last_style_id=LAST_INSERT_ID();
# Zuweisung des Styles zur Classe
INSERT INTO u_styles2classes (class_id, style_id,drawingorder) VALUES (@last_class_id,@last_style_id,1);
# 2. Ein schwarzer Punkt in der Mitte des Kreises, Symbol 'punkt'
#    Style ist dafr schon angelegt in @point_style_id
# Zuweisung des Styles zur Classe
INSERT INTO u_styles2classes (class_id, style_id,drawingorder) VALUES (@last_class_id,@point_style_id,2);
# Eintragen eines neuen Labels fr die Beschriftung der TP (doppelt unterstrichen)
INSERT INTO labels (color,font,outlinecolor,size,minsize,maxsize,position,the_force)
 VALUES ('0 50 150','arial_underline_1','255 255 255',10,5,10,4,1);
# Abfragen des dabei erzeugten Autowertes fr das Label und Zuweisen zu einer Variable
SET @last_label_id=LAST_INSERT_ID();
# Zuweisen des Labels zur Klasse in der Tabelle u_labels2classes
INSERT INTO u_labels2classes (class_id,label_id) VALUES (@last_class_id,@last_label_id);

########## Eintragen eines Layers fuer die Sicherungspunkte der APs (SiP)
INSERT INTO layer (Name, Datentyp, Gruppe, Data, labelitem, labelmaxscale,
 labelminscale,labelrequires, `connection`, connectiontype, classitem)
 VALUES ("Sicherungspunkte", 0, @group_id,CONCAT("o.the_geom from (select p.objnr AS oid,trim(leading '0' from p.pnr) AS nr,p.pat AS art, o.objart,o.the_geom FROM alknpunkt AS p, alkobj_e_pkt AS o WHERE p.objnr=o.objnr) AS foo using unique oid using srid=",@epsg_code), "pkz", 50000, 0, "([Festpunkttexte] = 1)"
 ,CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname), 6, "objart");
# Abfragen des dabei erzeugten Autowertes fuer die Layer_id und Zuweisung zu einer Variable
SET @last_layer_id=LAST_INSERT_ID();

########### Eintragen der Klasse Sicherungspunkte der APs
INSERT INTO classes (Name, Layer_ID, Expression,drawingorder) VALUES ('SiP', @last_layer_id, '([art]=5)',1);
# Abfragen des dabei erzeugten Autowertes fuer die Class_id und Zuweisung zu einer Variable
SET @last_class_id=LAST_INSERT_ID();
# Eintragen der Styles fuer die Klasse Sicherungspunkte
# 1. Style ein weisser gefuellter Kreis (etwas kleiner als die AP.
#    verwendet wird das Symbol 'circle' aus der Datei symbole.sym
#    Farbe selbst anpassen, hier schwarz
INSERT INTO styles (symbol, symbolname, size, color, backgroundcolor, outlinecolor,minsize,maxsize)
 VALUES (32, 'filledcircle', 8, '255 255 255', NULL, '0 50 150',5,8);
# Abfragen des dabei erzeugten Autowertes fr die Style_id und Zuweisung zu einer Variable
SET @last_style_id=LAST_INSERT_ID();
# Zuweisung des Styles zur Classe
INSERT INTO u_styles2classes (class_id, style_id,drawingorder) VALUES (@last_class_id,@last_style_id,1);

########### Eintagen eines Layers, ber den die Beschriftung der Festpunkte in und aus geschaltet werden k?nen
# In diesem Layer wird nichts gezeichnet, ist er jedoch an, wird die Beschriftung des Festpunktlayers dargestellt
# sonst nicht. Die Abhaengigkeit steht in der Layerbeschreibung im Attribut LABELREQUIRES im Festpunktlayer und im Sicherungspunktlayer
# Eintragen eines Layers fuer die Beschriftung der Fest- und Sicherungspunkte des Liegenschaftskatasters
INSERT INTO layer (Name, Datentyp, Gruppe, Data, `connection`, connectiontype, classitem)
 VALUES ("Festpunkttexte", 0, @group_id,'',CONCAT('user=',@pg_user,' password=',@pg_password,' dbname=',@pg_dbname), 6, "objart");
 
 COMMIT;