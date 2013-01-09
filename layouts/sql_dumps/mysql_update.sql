# SQL-Statements zur Aktualisierung der MySQL-Datenbank von kvwmap
#
# Zur Aktualisierung der Datenbank die folgenden SQL-Statements von einer Version zur anderen 
# in einem SQL-Fenster z.B. in phpMyAdmin ausführen

###################################################
# Änderungen im Datenmodell der Kartenspeicherung #
###################################################


#------------------------------------------------------------------------------------
# Änderungen von 1.4.2 nach 1.4.3
# 14.06.2005
# Verlängerung des Datentypen varchar für das Attribut BlattNr in Baulastentabellen
ALTER TABLE f_Baulasten CHANGE BlattNr BlattNr VARCHAR( 10 ) NOT NULL; 
ALTER TABLE x_f_Baulasten CHANGE BlattNr BlattNr VARCHAR( 10 ) NOT NULL;

# 13.06.2005
# Hinzufügen von Spalten in der Tabelle used_layer für die Zuordnung von Templatedateien
# zur Stellen und Layerabhängigen Sachdatenanzeige
ALTER TABLE used_layer
ADD template VARCHAR(255),
ADD header VARCHAR(255 ),
ADD footer VARCHAR(255 );

#------------------------------------------------------------------------------------
# Aenderung von 1.4.3 nach 1.4.4
# Hinzufuegen von Spalten in der Tabelle 'Layer'
ALTER TABLE layer
ADD toleranceunits enum('pixels','feet','inches','kilometers','meters','miles','dd') NOT NULL default 'pixels',
Add transparency tinyint(3) unsigned default NULL;

#-----------------------------------------------------------------------------
# Änderungen von 1.4.4 nach 1.4.5

# Hinzufügen der Tabelle u_labels2classes
CREATE TABLE u_labels2classes (
  class_id int(11) NOT NULL default '0',
  label_id int(11) NOT NULL default '0',
  PRIMARY KEY  (class_id,label_id)
) TYPE=MyISAM;

# Übernahme der Zuordnung der Labels zu den Klassen von classes in u_labels2classes
INSERT IGNORE INTO u_labels2classes SELECT Class_ID, Label_ID FROM classes
WHERE Label_ID > 0 AND Label_ID IS NOT NULL;

# Löschen der Spalte Labels_ID in der Tabelle classes
ALTER TABLE classes DROP Label_ID;

# Übernahme der Zuordnung der Styles zu den Klassen von classes in u_styles2classes
INSERT IGNORE INTO u_styles2classes SELECT Class_ID, Style_ID FROM classes
WHERE Style_ID > 0 AND Style_ID IS NOT NULL;

# Löschen der Spalte Style_ID in der Tabelle classes
ALTER TABLE classes DROP Style_ID;

# Ändern des Datentyps für Spalte Data in Tabelle Layer

ALTER TABLE layer CHANGE Data Data TEXT DEFAULT NULL;

# 12.07.2005
# Hinzufügen der Tabelle u_rolle2used_layer für das Speicher der Einstellungen,
# die an die Rolle gebunden sein sollen.
CREATE TABLE u_rolle2used_layer (
  user_id int(11) NOT NULL default '0',
  stelle_id int(11) NOT NULL default '0',
  layer_id int(11) NOT NULL default '0',
  aktivStatus enum('0','1') NOT NULL default '0',
  queryStatus enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (user_id,stelle_id,layer_id)
) TYPE=MyISAM;

# Hinzufügen der Werte in die Tabelle u_rolle2used_layer, die vorher in der
# Tabelle used_layer standen und dort an bloß die Stelle gebunden waren.
INSERT IGNORE INTO u_rolle2used_layer
SELECT r.user_id,ul.Stelle_ID,ul.Layer_ID,ul.aktivStatus,ul.queryStatus
FROM used_layer AS ul, rolle AS r WHERE r.stelle_id=ul.Stelle_ID;

# Entfernen der Spalten aus der Tabelle used_layer
# neben den nach u_rolle2used_layer verschobenen Statusfeldern
# werden auch die rect Felder gelöscht, die ebenfalls nicht mehr benötigt werden
ALTER TABLE used_layer
DROP aktivStatus,
DROP queryStatus,
DROP rect_xmin,
DROP rect_ymin,
DROP rect_xmax,
DROP rect_ymax;

# 19.07.2005
# Hinzufügen einer Spalte drawingorder zur Tabelle u_styles2classes
# zur Festlegung der Reihenfolge der gezeichneten Symbole einer Klasse
ALTER TABLE u_styles2classes ADD drawingorder INT(11) UNSIGNED;

# Hinzufügen der Spalten MINSIZE und MAXSIZE zur Tabelle styles
ALTER TABLE styles ADD minsize INT(11) UNSIGNED;
ALTER TABLE styles ADD maxsize INT(11) UNSIGNED;

# Hinzufügen der Spalte SYMBOLSCALE zur Tabelle used_layer
ALTER TABLE used_layer ADD symbolscale INT( 11 ) UNSIGNED;

# Verlängern des Datentyps für Passwörter in der Tabelle user damit die Spalte
# mit MD5 Verschlüsselte Zeichenketten aufnehmen kann.
ALTER TABLE user CHANGE passwort passwort VARCHAR( 32 );

# 21.07.2005
# Hinzufügen einer Spalte drawingorder zur Tabelle classes
# zur Festlegung der Reihenfolge der gezeichneten Klassen der Layer
ALTER TABLE classes ADD drawingorder INT(11) UNSIGNED;

# 25.07.2005
# Änderung der Spalte Status aus der Tabelle used_layer in queryable In der neuen Spalte
# wird vermerkt ob ein Layer inerhalb einer Stelle abfragbar sein soll '1' oder nicht '0'
ALTER TABLE used_layer CHANGE Status queryable ENUM('0','1') DEFAULT '1' NOT NULL; 

# Alle Geometrie Layer (Punkte, Linien und Polygone) werden erst einmal auf abfragbar gesetzt.
# Kann später angepasst werden.
UPDATE used_layer AS ul,layer AS l SET ul.queryable='1'
 WHERE ul.Layer_ID=l.Layer_ID AND l.Datentyp IN (0,1,2);

# Hinzufügen der Spalte labelrequires zur Tabelle layer für die Einstellung
# wann ein Layer beschriftet werden soll
ALTER TABLE layer ADD labelrequires varchar(255) default NULL;

# 26.07.2005
# Übernahme der Spalte selectedButton aus der Tabelle stelle zur Tabelle rolle
# Hinzufügen der neuen Spalte in Tabelle rolle
ALTER TABLE rolle ADD selectedButton VARCHAR(20) DEFAULT 'zoomin' NOT NULL;
# Übernehmen der Einstellungen aus Tabelle Stelle in Tabelle rolle
UPDATE rolle AS r,stelle AS s SET r.selectedButton =s.selectedButton WHERE r.stelle_id=s.ID;
# Spalte in Tabelle stelle löschen
ALTER TABLE stelle DROP selectedButton;

# 01.08.2005
# Umbenennen der Spalte AnlFlstPrüfz in Tabelle f_Anlieger und x_f_Anlieger
ALTER TABLE f_Anlieger CHANGE AnlFlstPrüfz AnlFlstPrüfz CHAR(1) DEFAULT NULL;
ALTER TABLE x_f_Anlieger CHANGE AnlFlstPrüfz AnlFlstPrüfz CHAR(1) DEFAULT NULL;
# Umbenennen des Index der Tabelle f_Adressen und x_f_Adressen
ALTER TABLE f_Adressen DROP INDEX Straße, ADD INDEX Strasse (Strasse);
ALTER TABLE x_f_Adressen DROP INDEX Straße, ADD INDEX Strasse (Strasse);
# Umbenennen des Indexes der Tabelle v_Strassen und x_v_Strassen
ALTER TABLE v_Strassen DROP INDEX StraßenName, ADD INDEX StrassenName (StrassenName);
ALTER TABLE x_v_Strassen DROP INDEX StraßenName, ADD INDEX StrassenName (StrassenName);
# Umbenennen des Indexes in der Tabelle x_g_Eigentuemer
ALTER TABLE x_g_Eigentuemer DROP INDEX Eigentümerart, ADD INDEX Eigentuemerart (Eigentuemerart);

# 02.08.2005
# Erzeugen einer Tabelle für die Definition von Koordinatengitter
CREATE TABLE m_grids (
  id int(11) NOT NULL auto_increment,
  labelformat enum('DDMM','DDMMSS') NOT NULL default 'DDMM',
  minarcs double default NULL,
  maxarcs double NOT NULL default '10',
  mininterval double default NULL,
  maxinterval double NOT NULL default '10',
  minsubdivide double NOT NULL default '2',
  maxsubdivide double default NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM COMMENT='Definition von Koordinatengitter';

# Erzeugen einer Tabelle für die Zuordnung des Grids zu Layern in Stellen
CREATE TABLE m_grids2used_layer (
  grid_id int(11) NOT NULL default '0',
  stelle_id int(11) NOT NULL default '0',
  layer_id int(11) NOT NULL default '0',
  PRIMARY KEY  (grid_id,stelle_id,layer_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci COMMENT='Zuordnung von Grids zu Layern in Stellen';

#-----------------------------------------------------------------------------
# Änderungen von 1.4.5 nach 1.5 alpha
# Ändern des Defaultwertes für die Stelle eines Users
# Das ist die Stelle, in die der User geleitet wird, wenn er sich das erste mal anmeldet.
ALTER TABLE user CHANGE stelle_id stelle_id INT(11) DEFAULT '1' NOT NULL;

# Hinzufügen einer Spalte epsg_code zur Tabelle layer zur Speicherung der Projektion in der der
# Layer vorliegt. Default ist auf GK Krassowski Streifen 4 gesetzt.
ALTER TABLE layer ADD epsg_code VARCHAR(6) DEFAULT '2398';

# Hinzufügen einer Spalte epsg_code zur Tabelle rolle zur Speicherung der Projektion in der die
# Karte für den Benutzer ausgegeben werden soll. Default ist GK Krassowski Streifen 4.
# !! Die angegebene Projektion muss in dem System sein, in dem auch die Angaben zum Extent in der
# Stelle stehen
ALTER TABLE rolle ADD epsg_code VARCHAR(6) DEFAULT '2398';

# Hinzufügen von Spalten zur Tabelle Layer für die Konfiguration einer WMS Datenquelle als Layer
# Siehe http://mapserver.gis.umn.edu/doc46/wms-client-howto.html
ALTER TABLE layer
ADD wms_srs VARCHAR(255) DEFAULT 'EPSG:2398' NOT NULL,
ADD wms_name VARCHAR(255),
ADD wms_server_version VARCHAR(8) DEFAULT '1.1.0' NOT NULL,
ADD wms_format VARCHAR(50) DEFAULT 'image/png' NOT NULL,
ADD wms_connectiontimeout INT(11) DEFAULT 60 NOT NULL;

# 07.11.2005
# Änderung des Datentyps der Spalte pfad in Tabelle layer
ALTER TABLE `layer` CHANGE `pfad` `pfad` TEXT CHARACTER SET latin1 COLLATE latin1_german2_ci NULL DEFAULT NULL;

# 11.11.2005
# Erzeugen einer Tabelle für die Zuordnung der Menüpunkte, die den Stellen zugeordnet sind und den Rollen
CREATE TABLE `u_menue2rolle` (
`user_id` INT( 11 ) NOT NULL ,
`stelle_id` INT( 11 ) NOT NULL ,
`menue_id` INT( 11 ) NOT NULL ,
`status` TINYINT( 1 ) NOT NULL
);

# Einfügen der aktuellen Zuordnungen der Rollen zu den Menüs
INSERT INTO u_menue2rolle SELECT DISTINCT r.user_id,m2s.stelle_id,m2s.menue_id,'0'
 FROM u_menue2stelle AS m2s, rolle AS r WHERE r.stelle_id=m2s.stelle_id
 ORDER BY r.stelle_id,r.user_id,m2s.menue_id;


# Erzeugen einer Tabelle für die Dokumentenkopfverwaltung
CREATE TABLE `dokumentenkoepfe` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`Name` VARCHAR( 255 ) NOT NULL ,
`Hauptüberschrift` VARCHAR( 255 ) NOT NULL ,
`Untertitel` VARCHAR( 255 ) NOT NULL ,
`Adresse` VARCHAR( 255 ) NOT NULL ,
`Ort` VARCHAR( 255 ) NOT NULL ,
`Datum` VARCHAR( 20 ) NOT NULL ,
`Wappen` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `id` )
);

# Einfügen einer Spalte active_head in der Tabelle rolle für die Speicherung des aktuellen Druckkopfes
ALTER TABLE `rolle` ADD `active_head` INT( 11 ) NOT NULL ;

# Änderung des Feldes links bei allen Obermenuepunkten in der Tabelle u_menues
UPDATE `u_menues` SET `links` = 'index.php?go=changemenue' WHERE `obermenue` = 0;

#Außer bei Karte anzeigen und Gesamtansicht
UPDATE `u_menues` SET `links` = 'index.php?go=Full_Extent' WHERE `name` = 'Übersicht';
UPDATE `u_menues` SET `links` = 'index.php?go=default' WHERE `name` = 'Karte';

# Erzeugen einer neuen Tabelle groups für die Gruppen in denen die Layer gruppiert sind
CREATE TABLE `u_groups` (
  `id` int(11) NOT NULL auto_increment,
  `Gruppenname` varchar(255) collate latin1_german2_ci NOT NULL,
  PRIMARY KEY  (`id`)
);

# Übername aller bisherigen Gruppen aus der Tabelle Layer in die Tabelle groups
INSERT INTO `u_groups` (Gruppenname) SELECT DISTINCT `Gruppe` FROM `layer`;

# Ersetzten der Gruppennamen in der Tabelle layer durch dessen id´s aus der neuen Tabelle
# für die Gruppen u_groups
UPDATE `layer` AS l,`u_groups` AS g SET l.Gruppe=g.id WHERE l.Gruppe=g.Gruppenname;

# Erzeugt eine neue Tabelle für die Speicherung von Eigenschaften, die an der Beziehung
# zwischen der Rolle und der Gruppe gebunden sind 
CREATE TABLE `u_groups2rolle` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  KEY `user_id` (`user_id`, `stelle_id`, `id`)
);

# Eintragen aller bisherigen Gruppen zu Rollen-Beziehungen
INSERT INTO u_groups2rolle
 SELECT DISTINCT rul.user_id,rul.stelle_id,g.id,0
 FROM `layer` AS l,used_layer AS ul,u_rolle2used_layer rul,u_groups AS g
 WHERE l.Layer_ID=ul.Layer_ID AND ul.Layer_ID=rul.layer_id
 AND ul.Stelle_ID=rul.stelle_id AND g.id=l.Gruppe
 ORDER BY rul.user_id,rul.stelle_id,g.id;

# Löschen des bisherigen Schlüssels used_layer_id
ALTER TABLE `used_layer` DROP `used_layer_id`;

# Setzen von Primärschlüsseln
ALTER TABLE `used_layer` ADD PRIMARY KEY ( `Stelle_ID` , `Layer_ID` );
ALTER TABLE `u_menue2rolle` ADD PRIMARY KEY ( `user_id` , `stelle_id` , `menue_id` );

# 2005-12-02
# Verlängern der Variablen für phon und email
ALTER TABLE `user` CHANGE `phon` `phon` VARCHAR(25) DEFAULT NULL;
ALTER TABLE `user` CHANGE `email` `email` VARCHAR(100) DEFAULT NULL;

#-------------------------------------------------------------------------------------
# Änderungen von 1.5-beta zu 1.5
# 2005-12-15

##### Änderungen an der Tabelle polygon_used_layer
# Hinzufügen der Spalten layer_id und stelle_id als Ersatz für used_layer_id
# die schon oben gelöscht worden ist
ALTER TABLE `polygon_used_layer` ADD `layer_id` INT( 11 ) NOT NULL , ADD `stelle_id` INT( 11 ) NOT NULL;

# Wer keine Daten in der Tabelle polygon_used_layer hatte kann dies überspringen.
# Wer schon Daten in der Tabelle hatte und die used_layer_id gelöscht hat, muss die used_layer_id´s
# entsprechend seiner alten Tabelle wieder aufbauen und anschließend dieses Statement ausführen.
# Eintragen der layer_id und stelle_id, der used_layer_id in die Tabelle polygon_used_layer
# update `polygon_used_layer` AS p2ul,`used_layer` AS ul set p2ul.layer_id=ul.Layer_ID,p2ul.stelle_id=ul.Stelle_ID
# WHERE p2ul.used_layer_id=ul.used_layer_id;

# Löschen der Spalte used_layer_id
ALTER TABLE `polygon_used_layer` DROP `used_layer_id`;

# Umbenennen der Tabelle polygon_used_layer in u_polygon2used_layer
ALTER TABLE `polygon_used_layer` RENAME `u_polygon2used_layer`;

# Löschen des alten und Hinzufügen des neuen Primärschlüssels
ALTER TABLE `u_polygon2used_layer` DROP INDEX `polygon_id`;
ALTER TABLE `u_polygon2used_layer` ADD PRIMARY KEY (`polygon_id`,`layer_id`,`stelle_id`);

###### Änderungen an der Tabelle attribute_access
# Hinzufügen der Spalten layer_id und stelle_id, die die Spalte used_layer_id
# ersetzen sollen
ALTER TABLE `attribute_access` ADD `layer_id` INT( 11 ) NOT NULL ,
ADD `stelle_id` MEDIUMINT( 11 ) NOT NULL;

# Wer keine Daten in der Tabelle attribute_access stehen hat, kann dies überspringen
# Wer schon Daten in der Tabelle hatte und die used_layer_id gelöscht hat, muss die used_layer_id´s
# entsprechend seiner alten Tabelle wieder aufbauen und anschließend dieses Statement ausführen.
# Eintragen der layer_id und stelle_id, der used_layer_id in die Tabelle polygon_used_layer
# update `attribute_access` AS a2ul,`used_layer` AS ul set a2ul.layer_id=ul.Layer_ID,a2ul.stelle_id=ul.Stelle_ID
# WHERE a2ul.used_layer_id=ul.used_layer_id;

# Löschen der Spalte used_layer_id
ALTER TABLE `attribute_access` DROP `used_layer_id`;

# Umbenennen der Tabelle in u_attribute2used_layer
ALTER TABLE `attribute_access` RENAME `u_attribute2used_layer` ;

# Löschen des alten und Hinzufügen des neuen Primärschlüssels
ALTER TABLE `u_attribute2used_layer` DROP INDEX `attributename`;
ALTER TABLE `u_attribute2used_layer` ADD PRIMARY KEY (`attributename`,`layer_id`,`stelle_id`);

#### Löschen der Tabelle classdef_adds
# Wer diese Tabelle verwendet hat und noch nutzen möchte melde sich bitte bei den Entwicklern
DROP TABLE `classdef_adds`;

# Ändern der Felder aktivStatus und queryStatus von (0,1) auf (0,1,2)
ALTER TABLE `u_rolle2used_layer` CHANGE `aktivStatus` `aktivStatus` ENUM( '0', '1', '2' ) NOT NULL DEFAULT '0',
CHANGE `queryStatus` `queryStatus` ENUM( '0', '1', '2' ) NOT NULL DEFAULT '0'

#-------------------------------------------------------------------------------------
# Änderungen von 1.5 zu 1.5.7
# 2006-01-30

# Hinzufügen einer Spalte in der Tabelle layer
# enthält den Attributnamen in den Sachdaten des Layers, der den Winkel des Textes enthält.
ALTER TABLE `layer` ADD `labelangleitem` VARCHAR( 25 ) NULL AFTER `tileitem`

#-------------------------------------------------------------------------------------
# Änderungen von 1.5.7 zu 1.5.8
# 2006-02-11
# Neue Tabelle für die Speicherung der tatsächlichen Zugriffe
CREATE TABLE `u_consume` (
`user_id` INT NOT NULL ,
`stelle_id` INT NOT NULL ,
`time_id` DATETIME NOT NULL ,
`activity` VARCHAR( 255 ) ,
`nimagewidth` INT,
`nimageheight` INT,
`minx` DOUBLE,
`miny` DOUBLE,
`maxx` DOUBLE,
`maxy` DOUBLE,
PRIMARY KEY ( `user_id` , `stelle_id` , `time_id` ) 
) TYPE=MYISAM;

# Neue Tabelle für die Speicherung der tatsächlichen Zugriffe auf die Layer
CREATE TABLE `u_consume2layer` (
`user_id` INT NOT NULL ,
`stelle_id` INT NOT NULL ,
`time_id` DATETIME NOT NULL ,
`layer_id` INT NOT NULL,
PRIMARY KEY ( `user_id` , `stelle_id` , `time_id` , `layer_id`)
) TYPE=MYISAM;

# Hinzufügen einer Spalte für die Info ob generell alle Layer innerhalb der Stelle geloggt werden
# sollen in Tabelle stelle
ALTER TABLE `stelle` ADD `logconsume` ENUM( '0', '1' );

# Hinzufügen einer Spalte für die Info ob layer generell geloggt werden soll in Tabelle layer
ALTER TABLE `layer` ADD `logconsume` ENUM( '0', '1' );

# Hinzufügen einer Spalte für die Info ob layer innerhalb der Stelle generell geloggt werden soll
# in Tabelle used_layer
ALTER TABLE `used_layer` ADD `logconsume` ENUM( '0', '1' );

# Hinzufügen einer Spalte für die Info ob layer innerhalb der Stelle für entsprechenden user
# geloggt werden soll in Tabelle u_rolle2used_layer
ALTER TABLE `u_rolle2used_layer` ADD `logconsume` ENUM( '0', '1' );

# Hinzufügen einer Spalte für die Angabe um den Zusammenhang zum Anzeigen des Layers festzulegen
ALTER TABLE `used_layer` ADD `requires` VARCHAR( 255 ) NULL ;

# 2006-03-07
# Änderung der Struktur der Tabelle dokumentenkoepfe und Umbenennen in druckrahmen

ALTER TABLE `dokumentenkoepfe`
  DROP `Hauptüberschrift`,
  DROP `Untertitel`,
  DROP `Ort`,
  DROP `Datum`,
  DROP `Wappen`,
  DROP `Adresse`;

ALTER TABLE `dokumentenkoepfe` ADD `headsrc` VARCHAR( 255 ) NOT NULL ,
ADD `headposx` INT( 11 ) NOT NULL ,
ADD `headposy` INT( 11 ) NOT NULL ,
ADD `headwidth` INT( 11 ) NOT NULL ,
ADD `headheight` INT( 11 ) NOT NULL ,
ADD `mapposx` INT( 11 ) NOT NULL ,
ADD `mapposy` INT( 11 ) NOT NULL ,
ADD `mapwidth` INT( 11 ) NOT NULL ,
ADD `mapheight` INT( 11 ) NOT NULL,
ADD `dateposx` INT( 11 ) NULL,
ADD `dateposy` INT( 11 ) NULL,
ADD `datesize` INT( 11 ) NULL,
ADD `scaleposx` INT( 11 ) NULL,
ADD `scaleposy` INT( 11 ) NULL,
ADD `scalesize` INT( 11 ) NULL,
ADD `gemarkungposx` INT( 11 ) NULL,
ADD `gemarkungposy` INT( 11 ) NULL,
ADD `gemarkungsize` INT( 11 ) NULL,
ADD `flurposx` INT( 11 ) NULL,
ADD `flurposy` INT( 11 ) NULL,
ADD `flursize` INT( 11 ) NULL,
ADD `format` VARCHAR(10) NOT NULL,
ADD `preis` INT( 11 ) NULL ;

ALTER TABLE `dokumentenkoepfe` RENAME `druckrahmen` ;

# Änderung der Spaltenbezeichnung zur Speicherung des aktiven Druckrahmens in der Tabelle rolle
ALTER TABLE `rolle` CHANGE `active_head` `active_frame` INT( 11 ) NULL;

# Neue Tabelle für die Speicherung der ALK-PDF-Exporte
CREATE TABLE `u_consumeALK` (
`user_id` INT NOT NULL ,
`stelle_id` INT NOT NULL ,
`time_id` DATETIME NOT NULL ,
`druckrahmen_id` INT NOT NULL,
PRIMARY KEY ( `user_id` , `stelle_id` , `time_id` ) 
) TYPE=MYISAM;

# Ändern des Standardwertes der Spalte stelle_id in der Tabelle user
ALTER TABLE `user` CHANGE `stelle_id` `stelle_id` INT( 11 ) NULL DEFAULT NULL;

#-------------------------------------------------------------------------------------
# Änderungen von 1.5.8 zu 1.5.9
# 
ALTER TABLE `druckrahmen` 
ADD `refmapsrc` VARCHAR( 255 ) NULL AFTER `mapheight` ,
ADD `refmapposx` INT( 11 ) NULL AFTER `refmapsrc` ,
ADD `refmapposy` INT( 11 ) NULL AFTER `refmapposx` ,
ADD `refmapwidth` INT( 11 ) NULL AFTER `refmapposy`,
ADD `refmapheight` INT( 11 ) NULL AFTER `refmapwidth`,
ADD `oscaleposx` INT( 11 ) NULL AFTER `scalesize` ,
ADD `oscaleposy` INT( 11 ) NULL AFTER `oscaleposx` ,
ADD `oscalesize` INT( 11 ) NULL AFTER `oscaleposy` ,
ADD `refposx` INT( 11 ) NULL AFTER `refmapheight` ,
ADD `refposy` INT( 11 ) NULL AFTER `refposx` ,
ADD `refwidth` INT( 11 ) NULL AFTER `refposy` ,
ADD `refheight` INT( 11 ) NULL AFTER `refwidth` ,
ADD `refzoom` INT( 11 ) NULL AFTER `refheight` ,
ADD `text` VARCHAR( 255 ) NULL AFTER `flursize` ,
ADD `textposx` INT( 11 ) NULL AFTER `text` ,
ADD `textposy` INT( 11 ) NULL AFTER `textposx` ,
ADD `textsize` INT( 11 ) NULL AFTER `textposy` ;

# Umbenennen der Spalte wms_srs in ows_srs in der Tabelle layer
ALTER TABLE `layer` CHANGE `wms_srs` `ows_srs` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL DEFAULT 'EPSG:2398';

# Änderungen von 1.5.8 zu 1.5.9 (vergessen)

ALTER TABLE `u_consume`
ADD `prev` datetime default NULL,
ADD `next` datetime default NULL;

CREATE TABLE `u_consume2comments` (
 `user_id` int(11) NOT NULL,
 `stelle_id` int(11) NOT NULL,
 `time_id` datetime NOT NULL,
 `comment` text collate latin1_german2_ci,
 PRIMARY KEY  (`user_id`,`stelle_id`,`time_id`)
);

#-------------------------------------------------------------------------------------
# Änderungen von 1.5.9 zu 1.6.0
#

# Hinzufügen von stellenbezogenen Metadaten für OWS-Requests

ALTER TABLE `stelle`
ADD `ows_title` VARCHAR( 255 ) NULL,
ADD `wms_accessconstraints` VARCHAR( 255 ) NULL,
ADD `ows_abstract` VARCHAR( 255 ) NULL,
ADD `ows_contactperson` VARCHAR( 255 ) NULL,
ADD `ows_contactorganization` VARCHAR( 255 ) NULL,
ADD `ows_contactemailaddress` VARCHAR( 255 ) NULL,
ADD `ows_contactposition` VARCHAR( 255 ) NULL,
ADD `ows_fees` VARCHAR( 255 ) NULL,
ADD `ows_srs` VARCHAR( 255 ) NULL;

# Hinzufügen einer Tabelle u_attributfilter2used_layer zur Speicherung der Attribut-Filter der Layer einer Stelle

CREATE TABLE `u_attributfilter2used_layer` (
  `Stelle_ID` int(11) NOT NULL,
  `Layer_ID` int(11) NOT NULL,
  `attributname` varchar(255) collate latin1_german2_ci NOT NULL,
  `attributvalue` varchar(255) collate latin1_german2_ci NOT NULL,
  `operator` enum('=','>','<','like','Within','Intersects') collate latin1_german2_ci NOT NULL,
  `type` varchar(255) collate latin1_german2_ci NOT NULL,
  PRIMARY KEY  (`Stelle_ID`,`Layer_ID`,`attributname`)
);

# Hinzufügen von zwei Spalten angle und angleitem zur Speicherung des Winkels bzw. des Attributes welches den Winkel enthält

ALTER TABLE `styles` 
ADD `angle` INT( 11 ) NOT NULL ,
ADD `angleitem` VARCHAR( 255 ) NOT NULL ;

# Hinzufügen einer Spalte last_time_id in Tabelle rolle zur Speicherung des letzten aufgerufenen Kartenausschnitts

ALTER TABLE `rolle` ADD `last_time_id` DATETIME NOT NULL;

# Hinzufügen einer Tabelle zur Speicherung der ALB-Zugriffe

CREATE TABLE `u_consumeALB` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL,
  `format` int(11) NOT NULL,
  `log_number` varchar(255) collate latin1_german2_ci NOT NULL,
  PRIMARY KEY  (`user_id`,`stelle_id`,`time_id`)
);


#-------------------------------------------------------------------------------------
# Änderungen von 1.6.0 zu 1.6.1
#

## Änderung der Tabelle u_attributfilter2used_layer um zwei neue Operatoren "IS" und "IN"

ALTER TABLE `u_attributfilter2used_layer` CHANGE `operator` `operator` ENUM( '=', '>', '<', 'like', 'IS', 'IN', 'Within', 'Intersects' ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL

#-------------------------------------------------------------------------------------
# Änderungen von 1.6.1 zu 1.6.2
#

CREATE TABLE `druckrahmen2stelle` (
`stelle_id` INT( 11 ) NOT NULL ,
`druckrahmen_id` INT( 11 ) NOT NULL ,
PRIMARY KEY ( `stelle_id` , `druckrahmen_id` )
);


ALTER TABLE `druckrahmen` ADD `font_date` VARCHAR( 255 ) NULL ,
ADD `font_scale` VARCHAR( 255 ) NULL ,
ADD `font_gemarkung` VARCHAR( 255 ) NULL ,
ADD `font_flur` VARCHAR( 255 ) NULL ,
ADD `font_oscale` VARCHAR( 255 ) NULL ,
ADD `legendposx` INT( 11 ) NULL AFTER `textsize` ,
ADD `legendposy` INT( 11 ) NULL AFTER `legendposx` ,
ADD `legendwidth` INT( 11 ) NULL AFTER `legendposy` ,
ADD `font_legend` VARCHAR( 255 ) NULL ,
ADD `arrowposx` INT( 11 ) NULL AFTER `legendwidth` ,
ADD `arrowposy` INT( 11 ) NULL AFTER `arrowposx` ,
ADD `arrowlength` INT( 11 ) NULL AFTER `arrowposy` ,
ADD `watermarkposx` INT( 11 ) NULL AFTER `arrowlength` ,
ADD `watermarkposy` INT( 11 ) NULL AFTER `watermarkposx` ,
ADD `watermark` VARCHAR( 255 ) NULL AFTER `watermarkposy` ,
ADD `watermarksize` INT( 11 ) NULL AFTER `watermark` ,
ADD `watermarkangle` INT( 11 ) NULL AFTER `watermarksize`,
ADD `font_watermark` VARCHAR( 255 ) NULL ;

ALTER TABLE `druckrahmen` DROP `text` ,
DROP `textposx` ,
DROP `textposy` ,
DROP `textsize` ;

ALTER TABLE `layer` CHANGE `connection` `connection` TEXT CHARACTER SET latin1 COLLATE latin1_german1_ci;

CREATE TABLE `druckfreitexte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(255) collate latin1_german2_ci default NULL,
  `posx` int(11) NOT NULL,
  `posy` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `font` varchar(255) collate latin1_german2_ci NOT NULL,
  `angle` int(11) default NULL,
  PRIMARY KEY  (`id`)
);

CREATE TABLE `druckrahmen2freitexte` (
`druckrahmen_id` INT( 11 ) NOT NULL ,
`freitext_id` INT( 11 ) NOT NULL ,
PRIMARY KEY ( `druckrahmen_id` , `freitext_id` )
);

#-------------------------------------------------------------------------------------
# Änderungen von 1.6.2 zu 1.6.3
#
ALTER TABLE `stelle`
 ADD `pgdbhost` VARCHAR( 25 ) NOT NULL DEFAULT 'localhost' AFTER `logconsume` ,
 ADD `pgdbname` VARCHAR( 25 ) NULL AFTER `pgdbhost` ,
 ADD `pgdbuser` VARCHAR( 25 ) NULL AFTER `pgdbname` ,
 ADD `pgdbpasswd` VARCHAR( 25 ) NULL AFTER `pgdbuser` ;

ALTER TABLE `druckrahmen` CHANGE `legendwidth` `legendsize` INT( 11 ) NULL DEFAULT NULL;

CREATE TABLE `layer_attributes` (
`layer_id` INT( 11 ) NOT NULL ,
`name` VARCHAR( 255 ) NOT NULL ,
`form_element_type` ENUM( 'Text', 'Textfeld', 'Auswahlfeld', 'Geometrie' ) NOT NULL ,
`options` TEXT NOT NULL ,
PRIMARY KEY ( `layer_id` , `name` )
);

ALTER TABLE `u_consumeALB` DROP PRIMARY KEY ,
ADD PRIMARY KEY ( `user_id` , `stelle_id` , `time_id` , `log_number` ) ;

ALTER TABLE `layer` CHANGE `Name` `Name` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL;
ALTER TABLE `classes` CHANGE `Name` `Name` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL;
ALTER TABLE `classes` ADD `text` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NULL ;

ALTER TABLE `rolle` ADD `gui` varchar(25) collate latin1_german2_ci NOT NULL default 'gui.php';


#-------------------------------------------------------------------------------------
# Änderungen von 1.6.3 zu 1.6.4
#

CREATE TABLE `layer_attributes2stelle` (
`layer_id` INT( 11 ) NOT NULL ,
`attributename` VARCHAR( 255 ) NOT NULL ,
`stelle_id` INT( 11 ) NOT NULL ,
`privileg` BOOL NOT NULL ,
PRIMARY KEY ( `layer_id` , `attributename` , `stelle_id` )
);


CREATE TABLE `rollenlayer` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `aktivStatus` enum('0','1','2') collate latin1_german2_ci NOT NULL,
  `Name` varchar(255) collate latin1_german2_ci NOT NULL,
  `Gruppe` int(11) NOT NULL,
  `Datentyp` int(11) NOT NULL,
  `Data` text collate latin1_german2_ci NOT NULL,
  `connectiontype` int(11) NOT NULL,
  `connection` varchar(255) collate latin1_german2_ci NOT NULL,
  `epsg_code` int(11) NOT NULL,
  `transparency` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
);

ALTER TABLE `stelle` ADD `wasserzeichen` VARCHAR( 150 ) NULL AFTER `wappen` ;

ALTER TABLE `u_rolle2used_layer` ADD `showclasses` BOOL NOT NULL DEFAULT '1' AFTER `queryStatus` ;

ALTER TABLE `used_layer` ADD `transparency` TINYINT( 3 ) NULL AFTER `offsite` ;

ALTER TABLE `layer` DROP `transparency` ;

ALTER TABLE `druckfreitexte` CHANGE `text` `text` TEXT NULL DEFAULT NULL;

#-------------------------------------------------------------------------------------
# Änderungen von 1.6.4 zu 1.6.5
#

ALTER TABLE `stelle` ADD `epsg_code` INT(6) NOT NULL DEFAULT '2398' AFTER `maxymax` ;

ALTER TABLE `druckrahmen` ADD `watermarktransparency` INT( 11 ) NULL AFTER `watermarkangle` ;

ALTER TABLE `layer_attributes` ADD `alias` VARCHAR( 255 ) NULL ;

ALTER TABLE `styles` ADD `width` INT( 11 ) NULL ,
ADD `sizeitem` VARCHAR( 255 ) NULL ;

#-------------------------------------------------------------------------------------
# Änderungen von 1.6.5 zu 1.6.6
#

ALTER TABLE `layer_attributes` CHANGE `form_element_type` `form_element_type` ENUM( 'Text', 'Textfeld', 'Auswahlfeld', 'Geometrie', 'SubFormPK', 'SubFormFK' ) NOT NULL;

# Neue Tabelle für freie Bilder in Druckrahmen
CREATE TABLE `druckfreibilder` (
  `id` int(11) NOT NULL auto_increment,
  `src` varchar(255) collate latin1_german2_ci NOT NULL default '',
  PRIMARY KEY  (`id`)
);

# Neue Tabelle für die Zuordnung von freien Bildern und den Druckrahmen
CREATE TABLE `druckrahmen2freibilder` (
  `druckrahmen_id` int(11) NOT NULL,
  `freibild_id` int(11) NOT NULL,
  `posx` int(11) NOT NULL,
  `posy` int(11) NOT NULL,
  `width` int(11) default NULL,
  `height` int(11) default NULL,
  `angle` int(11) default NULL,
  PRIMARY KEY  (`druckrahmen_id`,`freibild_id`)
);

# neue Spalte tooltip in layer_attributes2stelle
ALTER TABLE `layer_attributes2stelle` ADD `tooltip` BOOL NULL DEFAULT '0';

# neue Tabelle zur rollenbezogenen Speicherung von Druckausschnitten
CREATE TABLE `druckausschnitte` (
  `stelle_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `id` int(11) NOT NULL auto_increment ,
  `name` varchar(255) NOT NULL,
  `center_x` float NOT NULL,
  `center_y` float NOT NULL,
  `print_scale` int(11) NOT NULL,
  `angle` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
);
ALTER TABLE `druckausschnitte` DROP PRIMARY KEY,
ADD PRIMARY KEY ( `id` , `stelle_id` , `user_id` );

# neue Spalte zur Speicherung der Layerzugriffsrechte

 ALTER TABLE `used_layer` ADD `privileg` ENUM( '0', '1', '2' ) NOT NULL DEFAULT '0';
 
 #-------------------------------------------------------------------------------------
 # Änderungen von 1.6.6 zu 1.6.7
#
 
 ALTER TABLE `druckausschnitte` ADD `frame_id` INT( 11 ) NOT NULL ;
 
 ALTER TABLE `layer` ADD `template` VARCHAR( 255 ) NULL AFTER `epsg_code` ,
 ADD `queryable` ENUM( '0', '1' ) NOT NULL DEFAULT '0' AFTER `template` ;
 
 ALTER TABLE `druckrahmen` ADD `refmapfile` VARCHAR( 255 ) NULL AFTER `refmapsrc` ;
 
 ALTER TABLE `layer_attributes` CHANGE `form_element_type` `form_element_type` ENUM( 'Text', 'Textfeld', 'Auswahlfeld', 'Geometrie', 'SubFormPK', 'SubFormFK', 'Time' ) NOT NULL;
 
 ALTER TABLE `used_layer` ADD `postlabelcache` ENUM( '0', '1' ) NOT NULL DEFAULT '0' AFTER `transparency` ;
 
 ALTER TABLE `u_consumeALB` ADD `wz` ENUM( '0', '1' ) NULL AFTER `log_number` ;
 
 ALTER TABLE `u_attributfilter2used_layer` CHANGE `attributvalue` `attributvalue` TEXT NOT NULL;

 ALTER TABLE `u_consumeALB` ADD `numpages` INT( 11 ) NULL ;
 
# ----------------------------------------------------------------------------------------
# Änderung von 1.6.7 zu 1.6.8
ALTER TABLE `layer_attributes` CHANGE `form_element_type` `form_element_type` ENUM( 'Text', 'Textfeld', 'Auswahlfeld', 'Geometrie', 'SubFormPK', 'SubFormFK', 'Time', 'href', 'Bild' ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT 'Text';

# Hinzufügen von Spalten für die Angabe einer Sprache und Character Set für die Rolle
ALTER TABLE `rolle` ADD `language` ENUM( 'german', 'english', 'vietnamese' ) NOT NULL DEFAULT 'german';
ALTER TABLE `rolle` ADD `charset` ENUM('windows-1252','utf-8','ISO-8859-1','ISO-8859-2','ISO-8859-15','TCVN','VISCII','VPS') NOT NULL DEFAULT 'windows-1252';

# Hinzufügen eines Defaultwertes für die last_time_id in der Tabelle rolle
ALTER TABLE `rolle` CHANGE `last_time_id` `last_time_id` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';

# Hinzufügen von Spalten für die Englische und Vietnamesische Bezeichnung der Stellen
ALTER TABLE `stelle` ADD `Bezeichnung_english_windows-1252` VARCHAR( 255 ) CHARACTER SET cp1250 COLLATE cp1250_general_ci NULL AFTER `Bezeichnung`;
ALTER TABLE `stelle` ADD `Bezeichnung_vietnamese_utf-8` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER `Bezeichnung_english_windows-1252`;

# Neue Spalten für Sprachen in der Tabelle u_menues
ALTER TABLE `u_menues` ADD `name_english_windows-1252` VARCHAR(100) CHARACTER SET cp1250 COLLATE cp1250_general_ci NULL AFTER `name`;
ALTER TABLE `u_menues` ADD `name_vietnamese_utf-8` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER `name_english_windows-1252`;

# Neue Spalte für das verstecken des Menüs, an die Rolle gebunden
ALTER TABLE `rolle` ADD `hidemenue` ENUM( '0', '1' ) NOT NULL DEFAULT '0';

ALTER TABLE `rollenlayer` CHANGE `Name` `Name` TEXT NOT NULL;

#------------------------------------------------------------------------------------------
# Änderung von 1.6.8 zu 1.6.9
# Hinzufügen einer Spalte für die Tabelle user, in der IP-Adressen der Client-Rechner des Nutzers eingetragen werden können
# oder Subnetze, z.B. 139.30.110.216 oder 139.30.111.0
# Mehrere werden durch ; voneinander getrennt
# Wird nur wirksam, wenn die neue Konstante CHECK_CLIENT_IP true ist und
# in der Stelle in der neuen Spalte check_client_ip eine 1 steht
ALTER TABLE `user` ADD `ips` TEXT NULL AFTER `passwort`;

# Hinzufügen einer neuen Spalte für die Tabelle stelle, in der angegeben werden kann ob die IP-Adressen der Benutzer gegen die
# vom Server ermittelte Remote_Addr getestet werden soll. Werte 0 oder 1
# Wird nur wirksam, wenn die neue Konstante CHECK_CLIENT_IP true ist
ALTER TABLE `stelle` ADD `check_client_ip` ENUM( '0', '1' ) NOT NULL DEFAULT '0';

# Hinzufügen von Spalten die Speicherung der Sucheparameter nach anderen Dokumentarten
ALTER TABLE `rolle_nachweise` ADD `showan` CHAR( 1 ) NOT NULL DEFAULT '0' AFTER `showgn` ;
ALTER TABLE `rolle_nachweise` ADD `suchan` CHAR( 1 ) NOT NULL DEFAULT '0' AFTER `suchgn` ;

# Hinzufügen einer neuen Spalte für die Sortierung der Menüs
ALTER TABLE `u_menues` ADD `order` INT( 11 ) NOT NULL DEFAULT '0';


#------------------------------------------------------------------------------------------
# Änderung von 1.6.8 zu 1.6.9

ALTER TABLE `layer_attributes` CHANGE `form_element_type` `form_element_type` ENUM( 'Text', 'Textfeld', 'Auswahlfeld', 'Geometrie', 'SubFormPK', 'SubFormFK', 'Time', 'Bild', 'Link' ) NOT NULL DEFAULT 'Text';


#------------------------------------------------------------------------------------------
# Änderung von 1.6.9 zu 1.7.0
ALTER TABLE `user` ADD `Namenszusatz` VARCHAR( 50 ) NULL AFTER `Vorname`;
ALTER TABLE `user` ADD `password_setting_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `passwort`;
UPDATE `user` SET `password_setting_time` = CURRENT_TIMESTAMP;
ALTER TABLE `stelle` ADD `check_password_age` ENUM( '0', '1' ) NOT NULL DEFAULT '0';  
ALTER TABLE `stelle` ADD `allowed_password_age` TINYINT NOT NULL DEFAULT '6';
UPDATE `stelle` SET `allowed_password_age` = 6;
ALTER TABLE `styles` ADD `antialias` TINYINT( 1 ) DEFAULT NULL AFTER `angleitem`;
ALTER TABLE `styles` ADD `minwidth` INT( 11 ) DEFAULT NULL AFTER `width`;
ALTER TABLE `styles` ADD `maxwidth` INT( 11 ) DEFAULT NULL AFTER `minwidth`;
ALTER TABLE `styles` CHANGE `symbolname` `symbolname` VARCHAR( 40 ) DEFAULT NULL;


#------------------------------------------------------------------------------------------
# Änderung von 1.7.0 zu 1.7.1

ALTER TABLE `layer_attributes` ADD `tooltip` VARCHAR( 255 ) NULL ;
ALTER TABLE `layer_attributes` CHANGE `form_element_type` `form_element_type` ENUM( 'Text', 'Textfeld', 'Auswahlfeld', 'Geometrie', 'SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Time', 'Bild', 'Link' ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL DEFAULT 'Text';
ALTER TABLE `layer` ADD `selectiontype` VARCHAR( 20 ) NULL AFTER `wms_connectiontimeout` ;
ALTER TABLE `layer` ADD `wfs_geom` VARCHAR( 100 ) NULL AFTER `wms_connectiontimeout` ;
ALTER TABLE `layer` ADD `transparency` INT( 3 ) NULL AFTER `queryable` ,
ADD `drawingorder` INT( 11 ) NULL AFTER `transparency` ,
ADD `minscale` INT( 11 ) NULL AFTER `drawingorder` ,
ADD `maxscale` INT( 11 ) NULL AFTER `minscale` ,
ADD `offsite` VARCHAR( 11 ) NULL AFTER `maxscale` ;
ALTER TABLE `rolle` ADD `fontsize_gle` INT( 2 ) NULL DEFAULT '13';
ALTER TABLE `rolle_nachweise` CHANGE `suchstammnr` `suchstammnr` VARCHAR( 9 )  NOT NULL;


#------------------------------------------------------------------------------------------
# Änderung von 1.7.1 zu 1.7.2

ALTER TABLE `styles` CHANGE `angle` `angle` INT( 11 ) NULL;
ALTER TABLE `rolle` ADD `highlighting` BOOL NOT NULL DEFAULT 0;
ALTER TABLE `layer` ADD `querymap` ENUM( '0', '1' ) NOT NULL DEFAULT '0' AFTER `selectiontype` ;
ALTER TABLE `rollenlayer` ADD `labelitem` VARCHAR( 100 ) NULL ;
ALTER TABLE `layer` ADD `printconnection` TEXT NULL AFTER `connection` ;
ALTER TABLE `layer` ADD `schema` VARCHAR( 50 ) NULL AFTER `Data` ;
ALTER TABLE `layer` ADD `wms_auth_username` VARCHAR( 50 ) NULL AFTER `wms_connectiontimeout` , ADD `wms_auth_password` VARCHAR( 50 ) NULL AFTER `wms_auth_username`;
ALTER TABLE `layer_attributes` CHANGE `form_element_type` `form_element_type` ENUM( 'Text', 'Textfeld', 'Auswahlfeld', 'Geometrie', 'SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Time', 'Dokument', 'Link', 'User' ) NOT NULL DEFAULT 'Text';


#------------------------------------------------------------------------------------------
# Änderung von 1.7.2 zu 1.7.3

# Hinzufügen von Plattdeutsch bei der Angabe einer Sprache und Character Set für die Rolle
ALTER TABLE `rolle` CHANGE `language` `language` ENUM( 'german', 'low-german', 'english', 'vietnamese' ) NOT NULL DEFAULT 'german';

# Hinzufügen einer Spalte für die plattdeutsche Bezeichnung der Stellen
ALTER TABLE `stelle` ADD `Bezeichnung_low-german_windows-1252` VARCHAR( 255 ) NULL AFTER `Bezeichnung`;

# Neue Spalte für Plattdeutsche Menübezeichnung in der Tabelle u_menues
ALTER TABLE `u_menues` ADD `name_low-german_windows-1252` VARCHAR(100) NULL AFTER `name`;

# Neue Spalten offsetx und offsety im Style
ALTER TABLE `styles` ADD `offsetx` INT( 11 ) NULL ,
ADD `offsety` INT( 11 ) NULL ;

# Neue Spalte für das zweite Koordinatensystem
ALTER TABLE `rolle` ADD `epsg_code2` VARCHAR( 5 ) NULL AFTER `epsg_code`;

# Neue Spalte zum rollenbezogenen Speichern der Buttons
ALTER TABLE `rolle` ADD `buttons` VARCHAR( 255 ) NULL DEFAULT 'back,forward,zoomin,zoomout,zoomall,recentre,jumpto,query,touchquery,queryradius,polyquery,measure';

ALTER TABLE `layer_attributes` CHANGE `form_element_type` `form_element_type` ENUM('Text','Textfeld','Auswahlfeld','Geometrie','SubFormPK','SubFormFK','SubFormEmbeddedPK','Time','Dokument','Link','User') NOT NULL DEFAULT 'Text';


#------------------------------------------------------------------------------------------
# Änderung von 1.7.3 zu 1.7.4

ALTER TABLE `layer_attributes` ADD `real_name` VARCHAR( 255 ) NULL AFTER `name` ,
ADD `tablename` VARCHAR( 100 ) NULL AFTER `real_name` ,
ADD `table_alias_name` VARCHAR( 100 ) NULL AFTER `tablename` ,
ADD `type` VARCHAR( 30 ) NULL AFTER `table_alias_name` ,
ADD `geometrytype` VARCHAR( 20 ) NULL AFTER `type` ,
ADD `constraints` VARCHAR( 255 ) NULL AFTER `geometrytype` ,
ADD `nullable` BOOL NULL AFTER `constraints` ,
ADD `length` INT( 11 ) NULL AFTER `nullable` ,
ADD `order` INT NULL ;

ALTER TABLE `layer_attributes` CHANGE `options` `options` TEXT NULL DEFAULT NULL;

ALTER TABLE `layer` ADD `document_path` TEXT NULL AFTER `schema` ;

ALTER TABLE `rolle` ADD `hidelegend` ENUM( '0', '1' ) NOT NULL DEFAULT '0' AFTER `hidemenue` ;

ALTER TABLE `rollenlayer` DROP `class_id`;


# neue Tabelle zur rollenbezogenen Speicherung von Suchabfragen
CREATE TABLE `search_attributes2rolle` (
  `name` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `layer_id` int(11) NOT NULL,
  `attribute` varchar(50)  NOT NULL,
  `operator` varchar(11)  NOT NULL,
  `value1` text ,
  `value2` text ,
  PRIMARY KEY  (`name`,`user_id`,`stelle_id`,`layer_id`,`attribute`)
);


#------------------------------------------------------------------------------------------
# Änderung von 1.7.4 zu 1.7.5

ALTER TABLE `user` CHANGE `Funktion` `Funktion` ENUM( 'admin', 'user', 'gast' ) NOT NULL DEFAULT 'user';
ALTER TABLE `used_layer` ADD `start_aktiv` ENUM( '0', '1' ) NOT NULL DEFAULT '0';
ALTER TABLE `rolle` ADD `scrollposition` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `rolle` ADD `result_color` INT( 11 ) NULL AFTER `scrollposition` ;
ALTER TABLE `layer_attributes` ADD `mandatory` BOOL NULL AFTER `tooltip`;
ALTER TABLE `layer_attributes` ADD `default` VARCHAR( 255 ) NULL AFTER `length`;

ALTER TABLE `druckrahmen` ADD `userposx` INT default NULL AFTER `arrowlength` ,
ADD `userposy` INT default NULL AFTER `userposx` ,
ADD `usersize` INT default NULL AFTER `userposy` ,
ADD `font_user` VARCHAR(255) default NULL AFTER `font_watermark` ;

CREATE TABLE `datendrucklayouts` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `layer_id` int(11) NOT NULL,
  `bgsrc` varchar(255) default NULL,
  `bgposx` int(11) default NULL,
  `bgposy` int(11) default NULL,
  `bgwidth` int(11) default NULL,
  `bgheight` int(11) default NULL,
  `dateposx` int(11) default NULL,
  `dateposy` int(11) default NULL,
  `datesize` int(11) default NULL,
  `userposx` int(11) default NULL,
  `userposy` int(11) default NULL,
  `usersize` int(11) default NULL,
  `font_date` varchar(255) default NULL,
  `font_user` varchar(255) default NULL,
  `type` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

CREATE TABLE `ddl_elemente` (
`ddl_id` INT( 11 ) NOT NULL ,
`name` VARCHAR( 255 ) NOT NULL ,
`xpos` INT( 11 ) NULL ,
`ypos` INT( 11 ) NULL ,
`width` INT( 11 ) NULL ,
`border` BOOL NULL ,
`font` VARCHAR( 255 ) NULL ,
`fontsize` INT( 11 ) NULL ,
PRIMARY KEY ( `ddl_id` , `name` )
);

CREATE TABLE `ddl2stelle` (
  `stelle_id` int(11) NOT NULL,
  `ddl_id` int(11) NOT NULL,
  PRIMARY KEY  (`stelle_id`,`ddl_id`)
);

CREATE TABLE `ddl2freitexte` (
  `ddl_id` int(11) NOT NULL,
  `freitext_id` int(11) NOT NULL,
  PRIMARY KEY  (`ddl_id`,`freitext_id`)
);

CREATE TABLE `colors` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 30 ) NULL ,
`red` SMALLINT( 3 ) NOT NULL DEFAULT '0',
`green` SMALLINT( 3 ) NOT NULL DEFAULT '0',
`blue` SMALLINT( 3 ) NOT NULL DEFAULT '0'
);
INSERT INTO `colors` VALUES (1, NULL, 166, 206, 227);
INSERT INTO `colors` VALUES (2, NULL, 31, 120, 180);
INSERT INTO `colors` VALUES (3, NULL, 178, 223, 138);
INSERT INTO `colors` VALUES (4, NULL, 51, 160, 44);
INSERT INTO `colors` VALUES (5, NULL, 251, 154, 153);
INSERT INTO `colors` VALUES (6, NULL, 227, 26, 28);
INSERT INTO `colors` VALUES (7, NULL, 253, 191, 111);
INSERT INTO `colors` VALUES (8, NULL, 255, 127, 0);
INSERT INTO `colors` VALUES (9, NULL, 202, 178, 214);
INSERT INTO `colors` VALUES (10, NULL, 106, 61, 154);
INSERT INTO `colors` VALUES (11, NULL, 0, 0, 0);
INSERT INTO `colors` VALUES (12, NULL, 122, 12, 45);


#------------------------------------------------------------------------------------------
# Änderung von 1.7.5 zu 1.7.6

ALTER TABLE `styles` CHANGE `size` `size` VARCHAR( 50 ) NULL DEFAULT NULL;

ALTER TABLE `layer` ADD `processing` VARCHAR( 255 ) NULL DEFAULT NULL;


#------------------------------------------------------------------------------------------
# Änderung von 1.7.6 zu 1.8.0

ALTER TABLE `druckfreitexte` ADD `type` BOOL NULL ;

ALTER TABLE `layer_attributes` CHANGE `form_element_type` `form_element_type` ENUM( 'Text', 'Textfeld', 'Auswahlfeld', 'Geometrie', 'SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Time', 'Dokument', 'Link', 'User', 'Fläche', 'dynamicLink' ) NOT NULL DEFAULT 'Text';

ALTER TABLE `rolle` CHANGE `result_color` `result_color` INT( 11 ) NULL DEFAULT '1';

CREATE TABLE `u_rolle2used_class` (
`user_id` int( 11 ) NOT NULL default '0',
`stelle_id` int( 11 ) NOT NULL default '0',
`class_id` int( 11 ) NOT NULL default '0',
PRIMARY KEY ( `user_id` , `stelle_id` , `class_id` )
);

ALTER TABLE `u_attributfilter2used_layer` CHANGE `operator` `operator` ENUM( '=', '!=', '>', '<', 'like', 'IS', 'IN', 'Within', 'Intersects' )  NOT NULL;

ALTER TABLE `u_menues` ADD `name_polish_utf-8` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER `name_english_windows-1252` ;

ALTER TABLE `stelle` ADD `Bezeichnung_polish_utf-8` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER `Bezeichnung_english_windows-1252` ;

ALTER TABLE `rolle` CHANGE `language` `language` ENUM( 'german', 'low-german', 'english', 'polish', 'vietnamese' ) NOT NULL DEFAULT 'german';

CREATE TABLE `u_consumeCSV` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL default '0000-00-00 00:00:00',
  `art` varchar(20) NOT NULL,
  `numdatasets` int(11) default NULL,
  PRIMARY KEY  (`user_id`,`stelle_id`,`time_id`)
) ;

ALTER TABLE `layer` ADD `kurzbeschreibung` TEXT NULL ,
ADD `datenherr` VARCHAR( 100 ) NULL ;


#------------------------------------------------------------------------------------------
# Änderung von 1.8.0 zu 1.9.0

ALTER TABLE `rolle` ADD `coordtype` ENUM( 'dec', 'dms' ) NOT NULL DEFAULT 'dec' AFTER `epsg_code2`;

CREATE TABLE `rolle_csv_attributes` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `attributes` text NOT NULL,
  PRIMARY KEY (`user_id`,`stelle_id`,`name`)
);

ALTER TABLE `layer_attributes` ADD `decimal_length` INT( 11 ) NULL AFTER `length`;


#------------------------------------------------------------------------------------------
# Änderung von 1.9.0 zu 1.10.0

ALTER TABLE `rolle` ADD `always_draw` BOOLEAN NULL;

ALTER TABLE `rolle_nachweise` CHANGE `suchstammnr` `suchstammnr` VARCHAR(15) NOT NULL;
ALTER TABLE `rolle_nachweise` ADD `suchrissnr` VARCHAR(20) NOT NULL AFTER `suchstammnr`,
 			      ADD `suchfortf` INT(4) NULL AFTER `suchrissnr`;
 			      
ALTER TABLE `layer_attributes` CHANGE `form_element_type` `form_element_type` ENUM('Text','Textfeld','Auswahlfeld','Geometrie','SubFormPK','SubFormFK','SubFormEmbeddedPK','Time','Dokument','Link','User','Stelle','Fläche','dynamicLink') NOT NULL DEFAULT 'Text';



#------------------------------------------------------------------------------------------
# Änderung von 1.10.0 zu 1.11.0

ALTER TABLE `layer_attributes` CHANGE `form_element_type` `form_element_type` ENUM('Text','Textfeld','Auswahlfeld','Geometrie','SubFormPK','SubFormFK','SubFormEmbeddedPK','Time','Dokument','Link','User','Stelle','Fläche','dynamicLink','Zahl') NOT NULL DEFAULT 'Text';

ALTER TABLE `rollenlayer` ADD `Typ` ENUM('search','import') NOT NULL DEFAULT 'search' AFTER `Gruppe`;

ALTER TABLE `styles` ADD `pattern` VARCHAR(255) NULL;