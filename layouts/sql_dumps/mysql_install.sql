-- Dieses Dokument steht unter der LGPL-Lizenz

-- Dieser Dump dient dem Anlegen einer leeren MySQL-Datenbank für die Nutzung in kvwmap
-- Sie enthält die Tabellen für die Benutzer und Kartendaten.

-- Voraussetzung: Es ist eine Datenbank mit dem Namen kvwmapdb_Version angelegt.
-- Versionsname anpassen!

-- letzte: Änderung: 07.11.2005

-- --------------------------------------------------------
-- Setzen des Datenbanknamens 
-- SET @mysql_dbname='kvwmapdb';

-- Setzen des Charactersets
-- ALTER DATABASE `@mysql_dbname` CHARACTER SET latin1 COLLATE latin1_german2_ci;

CREATE TABLE  `rolle_last_query` (
  `user_id` INT( 11 ) NOT NULL ,
  `stelle_id` INT( 11 ) NOT NULL ,
  `go` VARCHAR( 50 ) NOT NULL ,
  `layer_id` INT( 11 ) NOT NULL ,
  `sql` TEXT NOT NULL ,
  `orderby` TEXT NULL ,
  `limit` INT( 11 ) NULL ,
  `offset` INT( 11 ) NULL
);

CREATE TABLE `u_consumeShape` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL default '0000-00-00 00:00:00',
  `layer_id` int(11) NOT NULL,
  `numdatasets` int(11) default NULL,
  PRIMARY KEY  (`user_id`,`stelle_id`,`time_id`)
) ;

CREATE TABLE `rolle_csv_attributes` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `attributes` text NOT NULL,
  PRIMARY KEY (`user_id`,`stelle_id`,`name`)
);


CREATE TABLE `u_consumeCSV` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL default '0000-00-00 00:00:00',
  `art` varchar(20) NOT NULL,
  `numdatasets` int(11) default NULL,
  PRIMARY KEY  (`user_id`,`stelle_id`,`time_id`)
) ;

CREATE TABLE `u_rolle2used_class` (
`user_id` int( 11 ) NOT NULL default '0',
`stelle_id` int( 11 ) NOT NULL default '0',
`class_id` int( 11 ) NOT NULL default '0',
PRIMARY KEY ( `user_id` , `stelle_id` , `class_id` )
);

# Neue Tabellen zur Speicherung von Sachdaten-Drucklayouts
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
`xpos` REAL NULL ,
`ypos` REAL NULL ,
`offset_attribute` VARCHAR( 255 ) NULL,
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


# Neue Tabelle zur Festlegung von Style-Farben für die automatische Klassifizierung
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
  `frame_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
);
ALTER TABLE `druckausschnitte` DROP PRIMARY KEY,
ADD PRIMARY KEY ( `id` , `stelle_id` , `user_id` );


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

CREATE TABLE `rollenlayer` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `aktivStatus` enum('0','1','2') collate latin1_german2_ci NOT NULL,
  `Name` varchar(255) collate latin1_german2_ci NOT NULL,
  `Gruppe` int(11) NOT NULL,
  `Typ` ENUM('search','import') NOT NULL DEFAULT 'search',
  `Datentyp` int(11) NOT NULL,
  `Data` text,
  `connectiontype` int(11) NOT NULL,
  `connection` varchar(255) collate latin1_german2_ci NOT NULL,
  `epsg_code` int(11) NOT NULL,
  `transparency` int(11) NOT NULL,
  `labelitem` VARCHAR( 100 ) NULL,
  PRIMARY KEY  (`id`)
);


CREATE TABLE `layer_attributes2stelle` (
`layer_id` INT( 11 ) NOT NULL ,
`attributename` VARCHAR( 255 ) NOT NULL ,
`stelle_id` INT( 11 ) NOT NULL ,
`privileg` BOOL NOT NULL ,
`tooltip` BOOL NULL DEFAULT '0',
PRIMARY KEY ( `layer_id` , `attributename` , `stelle_id` )
);



CREATE TABLE layer_attributes (
  layer_id int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  real_name varchar(255) default NULL,
  tablename varchar(100) default NULL,
  table_alias_name varchar(100) default NULL,
  `type` varchar(30) default NULL,
  geometrytype varchar(20) default NULL,
  constraints varchar(255) default NULL,
  nullable tinyint(1) default NULL,
  length int(11) default NULL,
  `decimal_length` INT( 11 ) NULL,
  `default` VARCHAR( 255 ) NULL,
  form_element_type enum('Text','Textfeld','Auswahlfeld','Checkbox', 'Geometrie','SubFormPK','SubFormFK','SubFormEmbeddedPK','Time','Dokument','Link','User','Stelle','Fläche','dynamicLink','Zahl','UserID','Länge','mailto') NOT NULL default 'Text',
  options text,
  alias varchar(255) default NULL,
  tooltip varchar(255) default NULL,
  `group` VARCHAR( 255 ) DEFAULT NULL,
  `mandatory` BOOL NULL,
  `order` int(11) default NULL,
  `privileg` BOOLEAN NULL DEFAULT '0',
  `query_tooltip` BOOLEAN NULL DEFAULT '0',
  PRIMARY KEY  (layer_id,`name`)
);


-- Hinzufügen einer Tabelle u_attributfilter2used_layer zur Speicherung der Attribut-Filter der Layer einer Stelle

CREATE TABLE `u_attributfilter2used_layer` (
  `Stelle_ID` int(11) NOT NULL,
  `Layer_ID` int(11) NOT NULL,
  `attributname` varchar(255) NOT NULL,
  `attributvalue` text collate latin1_german2_ci NOT NULL,
  `operator` enum('=','!=','>','<','like','IS','IN','st_within','st_intersects') NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY  (`Stelle_ID`,`Layer_ID`,`attributname`)
);

--
-- Erzeugen einer neuen Tabelle groups für die Gruppen in denen die Layer gruppiert sind
--
CREATE TABLE `u_groups` (
  `id` int(11) NOT NULL auto_increment,
  `Gruppenname` varchar(255) NOT NULL,
  `obergruppe` INT( 11 ) NULL,
  `order` INT( 11 ) NULL,
  PRIMARY KEY  (`id`)
);

--
-- Erzeugt eine neue Tabelle für die Speicherung von Eigenschaften, die an der Beziehung
-- zwischen der Rolle und der Gruppe gebunden sind 
--
CREATE TABLE `u_groups2rolle` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `user_id_2` (`user_id`),
  KEY `user_id_3` (`user_id`),
  PRIMARY KEY ( `user_id` , `stelle_id` , `id` )
);

-- 
-- Table structure for table `druckrahmen`
-- 

CREATE TABLE `druckrahmen` (
  `Name` varchar(255) NOT NULL,
  `id` int(11) NOT NULL auto_increment,
  `headsrc` varchar(255) NOT NULL,
  `headposx` int(11) NOT NULL,
  `headposy` int(11) NOT NULL,
  `headwidth` int(11) NOT NULL,
  `headheight` int(11) NOT NULL,
  `mapposx` int(11) NOT NULL,
  `mapposy` int(11) NOT NULL,
  `mapwidth` int(11) NOT NULL,
  `mapheight` int(11) NOT NULL,
  `refmapsrc` varchar(255) default NULL,
  `refmapfile` varchar(255) default NULL,
  `refmapposx` int(11) default NULL,
  `refmapposy` int(11) default NULL,
  `refmapwidth` int(11) default NULL,
  `refmapheight` int(11) default NULL,
  `refposx` int(11) default NULL,
  `refposy` int(11) default NULL,
  `refwidth` int(11) default NULL,
  `refheight` int(11) default NULL,
  `refzoom` int(11) default NULL,
  `dateposx` int(11) default NULL,
  `dateposy` int(11) default NULL,
  `datesize` int(11) default NULL,
  `scaleposx` int(11) default NULL,
  `scaleposy` int(11) default NULL,
  `scalesize` int(11) default NULL,
  `oscaleposx` int(11) default NULL,
  `oscaleposy` int(11) default NULL,
  `oscalesize` int(11) default NULL,
  `gemarkungposx` int(11) default NULL,
  `gemarkungposy` int(11) default NULL,
  `gemarkungsize` int(11) default NULL,
  `flurposx` int(11) default NULL,
  `flurposy` int(11) default NULL,
  `flursize` int(11) default NULL,
  `legendposx` int(11) default NULL,
  `legendposy` int(11) default NULL,
  `legendsize` int(11) default NULL,
  `arrowposx` int(11) default NULL,
  `arrowposy` int(11) default NULL,
  `arrowlength` int(11) default NULL,
  `userposx` int(11) default NULL,
  `userposy` int(11) default NULL,
  `usersize` int(11) default NULL,
  `watermarkposx` int(11) default NULL,
  `watermarkposy` int(11) default NULL,
  `watermark` varchar(255) default '',
  `watermarksize` int(11) default NULL,
  `watermarkangle` int(11) default NULL,
  `watermarktransparency` int(11) default NULL,
  `variable_freetexts` BOOLEAN NULL DEFAULT NULL,
  `format` varchar(10) NOT NULL default 'A4hoch',
  `preis` int(11) default NULL,
  `font_date` varchar(255) default NULL,
  `font_scale` varchar(255) default NULL,
  `font_gemarkung` varchar(255) default NULL,
  `font_flur` varchar(255) default NULL,
  `font_oscale` varchar(255) default NULL,
  `font_legend` varchar(255) default NULL,
  `font_watermark` varchar(255) default NULL,
  `font_user` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
);



#

CREATE TABLE `druckrahmen2stelle` (
`stelle_id` INT( 11 ) NOT NULL ,
`druckrahmen_id` INT( 11 ) NOT NULL ,
PRIMARY KEY ( `stelle_id` , `druckrahmen_id` )
);


CREATE TABLE `druckfreitexte` (
  `id` int(11) NOT NULL AUTO_INCREMENT ,
  `text` text NULL,
  `posx` int(11) NOT NULL,
  `posy` int(11) NOT NULL,
	`offset_attribute` VARCHAR( 255 ) NULL,
  `size` int(11) NOT NULL,
  `font` varchar(255) NOT NULL,
  `angle` int(11) default NULL,
  `type` tinyint(1) default NULL,
  PRIMARY KEY  (`id`)
);

CREATE TABLE `druckrahmen2freitexte` (
`druckrahmen_id` INT( 11 ) NOT NULL ,
`freitext_id` INT( 11 ) NOT NULL ,
PRIMARY KEY ( `druckrahmen_id` , `freitext_id` )
);

-- 
-- Table structure for table `u_menue2rolle`
-- 

CREATE TABLE `u_menue2rolle` (
`user_id` INT( 11 ) NOT NULL ,
`stelle_id` INT( 11 ) NOT NULL ,
`menue_id` INT( 11 ) NOT NULL ,
`status` TINYINT( 1 ) NOT NULL,
PRIMARY KEY ( `user_id` , `stelle_id` , `menue_id` )
);


-- --------------------------------------------------------

-- 
-- Table structure for table `classes`
-- 

CREATE TABLE classes (
  Class_ID int(11) NOT NULL auto_increment,
  Name varchar(50) NOT NULL default '',
  Layer_ID int(11) NOT NULL default '0',
  Expression text NULL,
  drawingorder INT(11) UNSIGNED,
  text varchar(255) NULL,
  PRIMARY KEY  (Class_ID),
  KEY Layer_ID (Layer_ID)
);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `m_grids`
-- 

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

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `m_grid2used_layer`
-- 

CREATE TABLE m_grids2used_layer (
  grid_id int(11) NOT NULL default '0',
  stelle_id int(11) NOT NULL default '0',
  layer_id int(11) NOT NULL default '0',
  PRIMARY KEY  (grid_id,stelle_id,layer_id)
) ENGINE=MyISAM;


-- --------------------------------------------------------

-- 
-- Table structure for table `labels`
-- 

CREATE TABLE labels (
  Label_ID int(11) NOT NULL auto_increment,
  font varchar(25) NOT NULL default 'arial',
  type int(1) default NULL,
  color varchar(11) NOT NULL default '',
  outlinecolor varchar(11) default NULL,
  shadowcolor varchar(11) default NULL,
  shadowsizex int(3) default NULL,
  shadowsizey int(3) default NULL,
  backgroundcolor varchar(11) default NULL,
  backgroundshadowcolor varchar(11) default NULL,
  backgroundshadowsizex int(3) default NULL,
  backgroundshadowsizey int(3) default NULL,
  size int(2) default NULL,
  minsize tinyint(3) default NULL,
  maxsize tinyint(3) default NULL,
  position tinyint(1) default NULL,
  offsetx tinyint(3) default NULL,
  offsety tinyint(3) default NULL,
  angle double default NULL,
  autoangle tinyint(1) default NULL,
  buffer tinyint(3) default NULL,
  antialias tinyint(1) default NULL,
  minfeaturesize int(11) default NULL,
  maxfeaturesize int(11) default NULL,
  partials int(1) default NULL,
  wrap tinyint(3) default NULL,
  the_force int(1) default NULL,
  PRIMARY KEY  (Label_ID)
);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `u_labels2classes`
-- 

CREATE TABLE u_labels2classes (
  class_id int(11) NOT NULL default '0',
  label_id int(11) NOT NULL default '0',
  PRIMARY KEY  (class_id,label_id)
);

-- --------------------------------------------------------

-- 
-- Table structure for table `layer`
-- 


CREATE TABLE `layer` (
  `Layer_ID` int(11) NOT NULL auto_increment,
  `Name` varchar(255) NOT NULL,
  `alias` VARCHAR( 255 ) NULL DEFAULT NULL,
  `Datentyp` tinyint(4) NOT NULL default '2',
  `Gruppe` int(11) NOT NULL default '0',
  `pfad` text collate latin1_german2_ci,
  `maintable` VARCHAR( 255 ) NULL DEFAULT NULL,
  `Data` text collate latin1_german2_ci,
  `schema` varchar(50) collate latin1_german2_ci default NULL,
  `document_path` text collate latin1_german2_ci,
  `tileindex` varchar(100) collate latin1_german2_ci default NULL,
  `tileitem` varchar(100) collate latin1_german2_ci default NULL,
  `labelangleitem` varchar(25) collate latin1_german2_ci default NULL,
  `labelitem` varchar(100) collate latin1_german2_ci default NULL,
  `labelmaxscale` int(11) default NULL,
  `labelminscale` int(11) default NULL,
  `labelrequires` varchar(255) collate latin1_german2_ci default NULL,
  `connection` text collate latin1_german2_ci NOT NULL,
  `printconnection` text collate latin1_german2_ci,
  `connectiontype` tinyint(4) NOT NULL default '0',
  `classitem` varchar(100) collate latin1_german2_ci default NULL,
  `filteritem` varchar(100) collate latin1_german2_ci NOT NULL default 'ID',
  `tolerance` double NOT NULL default '3',
  `toleranceunits` enum('pixels','feet','inches','kilometers','meters','miles','dd') collate latin1_german2_ci NOT NULL default 'pixels',
  `epsg_code` varchar(6) collate latin1_german2_ci default '2398',
  `template` varchar(255) collate latin1_german2_ci default NULL,
  `queryable` enum('0','1') collate latin1_german2_ci NOT NULL default '0',
  `transparency` int(3) default NULL,
  `drawingorder` int(11) default NULL,
  `minscale` int(11) default NULL,
  `maxscale` int(11) default NULL,
  `offsite` varchar(11) collate latin1_german2_ci default NULL,
  `ows_srs` varchar(255) collate latin1_german2_ci NOT NULL default 'EPSG:2398',
  `wms_name` varchar(255) collate latin1_german2_ci default NULL,
  `wms_server_version` varchar(8) collate latin1_german2_ci NOT NULL default '1.1.0',
  `wms_format` varchar(50) collate latin1_german2_ci NOT NULL default 'image/png',
  `wms_connectiontimeout` int(11) NOT NULL default '60',
  `wms_auth_username` varchar(50) collate latin1_german2_ci default NULL,
  `wms_auth_password` varchar(50) collate latin1_german2_ci default NULL,
  `wfs_geom` varchar(100) collate latin1_german2_ci default NULL,
  `selectiontype` varchar(20) collate latin1_german2_ci default NULL,
  `querymap` enum('0','1') collate latin1_german2_ci NOT NULL default '0',
  `logconsume` enum('0','1') collate latin1_german2_ci NOT NULL default '0',
  `processing` varchar(255) collate latin1_german2_ci default NULL,
  `kurzbeschreibung` TEXT NULL,
  `datenherr` VARCHAR( 100 ) NULL,
  `metalink` VARCHAR( 255 ) NULL,
  `privileg` ENUM( '0', '1', '2' ) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`Layer_ID`),
  KEY `Gruppe` (`Gruppe`)
);

-- --------------------------------------------------------

-- 
-- Table structure for table `polygon`
-- 

CREATE TABLE polygon (
  polygon_id int(11) NOT NULL auto_increment,
  polygonname varchar(25) NOT NULL default '',
  datei varchar(30) NOT NULL default '',
  art varchar(25) NOT NULL default '',
  feldname varchar(25) NOT NULL default '',
  PRIMARY KEY  (polygon_id)
);

-- --------------------------------------------------------

-- 
-- Table structure for table `u_polygon2used_layer`
-- # 2005-12-15 pk

CREATE TABLE u_polygon2used_layer (
  polygon_id int(11) NOT NULL default '0',
  layer_id int(11) NOT NULL,
  stelle_id int(11) NOT NULL,
  PRIMARY KEY (polygon_id,layer_id,stelle_id)
);

-- --------------------------------------------------------

-- 
-- Table structure for table `referenzkarten`
-- 

CREATE TABLE referenzkarten (
  ID int(11) NOT NULL auto_increment,
  Name varchar(100) NOT NULL default '',
  Dateiname varchar(100) NOT NULL default '',
  xmin double NOT NULL default '0',
  ymin double NOT NULL default '0',
  xmax double NOT NULL default '0',
  ymax double NOT NULL default '0',
  width int(4) unsigned NOT NULL default '0',
  height int(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (ID)
);

-- --------------------------------------------------------

-- 
-- Table structure for table `rolle`
-- 

CREATE TABLE rolle (
  user_id int(11) NOT NULL default '0',
  stelle_id int(11) NOT NULL default '0',
  nImageWidth int(3) NOT NULL default '500',
  nImageHeight int(3) NOT NULL default '500',
  minx double NOT NULL default '4501025',
  miny double NOT NULL default '6001879',
  maxx double NOT NULL default '4502834',
  maxy double NOT NULL default '6003236',
  nZoomFactor int(11) NOT NULL default '2',
  selectedButton varchar(20) NOT NULL default 'zoomin',
  epsg_code varchar(6) default '2398',
  epsg_code2 varchar(6) NULL,
  coordtype ENUM( 'dec', 'dms', 'dmin' ) NOT NULL DEFAULT 'dec',
  active_frame int(11) NOT NULL default '0',  
  last_time_id DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  gui varchar(100) collate latin1_german2_ci NOT NULL default 'gui.php',
  `language` enum('german', 'low-german', 'english', 'polish', 'vietnamese') NOT NULL default 'german',
  `charset` enum('windows-1252','utf-8','ISO-8859-1','ISO-8859-2','ISO-8859-15','TCVN','VISCII','VPS') NOT NULL default 'windows-1252',
  `hidemenue` enum('0','1') NOT NULL default '0',
  `hidelegend` enum('0','1') NOT NULL default '0',
  `fontsize_gle` INT( 2 ) NULL DEFAULT '13',
  `highlighting` BOOL NOT NULL DEFAULT 0,
  `buttons` VARCHAR( 255 ) NULL DEFAULT 'back,forward,zoomin,zoomout,zoomall,recentre,jumpto,query,touchquery,queryradius,polyquery,measure',
  `scrollposition` INT( 11 ) NOT NULL DEFAULT '0',
  `result_color` INT( 11 ) NULL DEFAULT '1',
  `always_draw` BOOLEAN NULL,
  `runningcoords` BOOLEAN NOT NULL DEFAULT 0,
  `singlequery` BOOLEAN NOT NULL DEFAULT 0,
	`querymode` BOOLEAN NOT NULL DEFAULT  0,
  PRIMARY KEY  (user_id,stelle_id)
);

-- ---------------------------------------------------------

--
-- Table structure for table `u_rolle2used_layer`
--

CREATE TABLE u_rolle2used_layer (
  user_id int(11) NOT NULL default '0',
  stelle_id int(11) NOT NULL default '0',
  layer_id int(11) NOT NULL default '0',
  aktivStatus enum('0','1','2') NOT NULL default '0',
  queryStatus enum('0','1','2') NOT NULL default '0',
  `showclasses` BOOL NOT NULL DEFAULT '1',
  `logconsume` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (user_id,stelle_id,layer_id)
);

-- --------------------------------------------------------

-- 
-- Table structure for table `rolle_nachweise`
-- 

CREATE TABLE rolle_nachweise (
  user_id int(11) NOT NULL default '0',
  stelle_id int(11) NOT NULL default '0',
  suchffr char(1) NOT NULL default '0',
  suchkvz char(1) NOT NULL default '0',
  suchgn char(1) NOT NULL default '0',
  suchan CHAR(1) NOT NULL DEFAULT '0',
  abfrageart varchar(10) NOT NULL default '',
  suchgemarkung varchar(10) NOT NULL default '',
  suchflur varchar(3) NOT NULL,
  suchstammnr varchar(15) NOT NULL default '',
  suchrissnr varchar(20) NOT NULL,
  suchfortf int(4) NULL,
  suchpolygon text,
  suchantrnr varchar(11) NOT NULL default '',
	sdatum VARCHAR( 10 ) NULL,
	sdatum2 VARCHAR( 10 ) NULL,
	sVermStelle INT( 11 ) NULL,
  showffr char(1) NOT NULL default '0',
  showkvz char(1) NOT NULL default '0',
  showgn char(1) NOT NULL default '0',
  showan CHAR(1) NOT NULL DEFAULT '0',
  markffr char(1) NOT NULL default '0',
  markkvz char(1) NOT NULL default '0',
  markgn char(1) NOT NULL default '0',
  PRIMARY KEY  (user_id,stelle_id)
);

-- --------------------------------------------------------

-- 
-- Table structure for table `stelle`
-- 

CREATE TABLE stelle (
  ID int(11) NOT NULL auto_increment,
  `Bezeichnung` varchar(255)  CHARACTER SET latin1 COLLATE latin1_german2_ci NULL,
  `Bezeichnung_low-german_windows-1252` VARCHAR( 255 ) NULL,
  `Bezeichnung_english_windows-1252` VARCHAR(255) CHARACTER SET cp1250 COLLATE cp1250_general_ci NULL,
  `Bezeichnung_polish_utf-8` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `Bezeichnung_vietnamese_utf-8` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  start date NOT NULL default '0000-00-00',
  stop date NOT NULL default '0000-00-00',
  minxmax double default NULL,
  minymax double default NULL,
  maxxmax double default NULL,
  maxymax double default NULL,
  epsg_code VARCHAR(6) DEFAULT '2398',
  Referenzkarte_ID int(11) default NULL,
  Authentifizierung enum('0','1') NOT NULL default '1',
  ALB_status enum('30','35') NOT NULL default '30',
  wappen varchar(150) NOT NULL default 'stz.gif',
  wasserzeichen varchar(150) default NULL,
  alb_raumbezug set('','Kreis','Amtsverwaltung','Gemeinde') NOT NULL default '',
  alb_raumbezug_wert varchar(255) NOT NULL default '',
  logconsume enum('0','1') collate latin1_german2_ci default NULL,
  pgdbhost VARCHAR( 25 ) NOT NULL DEFAULT 'localhost',
  pgdbname VARCHAR( 25 ) NULL,
  pgdbuser VARCHAR( 25 ) NULL,
  pgdbpasswd VARCHAR( 25 ) NULL,
  ows_title VARCHAR( 255 ) NULL,
  wms_accessconstraints VARCHAR( 255 ) NULL,
  ows_abstract VARCHAR( 255 ) NULL,
  ows_contactperson VARCHAR( 255 ) NULL,
  ows_contactorganization VARCHAR( 255 ) NULL,
  ows_contactemailaddress VARCHAR( 255 ) NULL,
  ows_contactposition VARCHAR( 255 ) NULL,
  ows_fees VARCHAR( 255 ) NULL,
  ows_srs VARCHAR( 255 ) NULL,
  check_client_ip ENUM('0','1') NOT NULL DEFAULT '0',
  check_password_age ENUM('0','1') NOT NULL DEFAULT '0',
  allowed_password_age TINYINT NOT NULL DEFAULT '6',
  use_layer_aliases ENUM( '0', '1' ) NOT NULL DEFAULT '0',
  PRIMARY KEY  (ID)
);

-- --------------------------------------------------------

-- 
-- Table structure for table `stelle_gemeinden`
-- 

CREATE TABLE stelle_gemeinden (
  Stelle_ID int(11) NOT NULL default '0',
  Gemeinde_ID int(8) NOT NULL default '0',
  PRIMARY KEY  (Stelle_ID,Gemeinde_ID)
);

-- --------------------------------------------------------

-- 
-- Table structure for table `styles`
-- 

CREATE TABLE `styles` (
  `Style_ID` int(11) NOT NULL auto_increment,
  `symbol` int(3) default NULL,
  `symbolname` varchar(40) default NULL,
  `size` varchar(50) default NULL,
  `color` varchar(11) collate latin1_german2_ci default NULL,
  `backgroundcolor` varchar(11) collate latin1_german2_ci default NULL,
  `outlinecolor` varchar(11) collate latin1_german2_ci default NULL,
  `minsize` int(11) unsigned default NULL,
  `maxsize` int(11) unsigned default NULL,
  `angle` int(11) NULL,
  `angleitem` varchar(255) collate latin1_german2_ci NULL,
  `antialias` tinyint(1) default NULL,
  `width` int(11) default NULL,
  `minwidth` int(11) default NULL,
  `maxwidth` int(11) default NULL,
  `sizeitem` varchar(255) collate latin1_german2_ci default NULL,
  `offsetx` INT( 11 ) NULL ,
  `offsety` INT( 11 ) NULL,
  `pattern` VARCHAR(255) NULL,
  `geomtransform` VARCHAR(20) NULL,
	`gap` INT( 11 ) NULL ,
	`linecap` VARCHAR( 8 ) NULL ,
	`linejoin` VARCHAR( 5 ) NULL ,
	`linejoinmaxsize` INT( 11 ) NULL,
  PRIMARY KEY  (`Style_ID`)
);

-- --------------------------------------------------------

-- 
-- Table structure for table `u_funktion2stelle`
-- 

CREATE TABLE u_funktion2stelle (
  funktion_id int(11) NOT NULL default '0',
  stelle_id int(11) NOT NULL default '0',
  PRIMARY KEY  (funktion_id,stelle_id)
);

-- --------------------------------------------------------

-- 
-- Table structure for table `u_funktionen`
-- 

CREATE TABLE u_funktionen (
  id int(11) NOT NULL auto_increment,
  bezeichnung varchar(255) NOT NULL default '',
  link varchar(255) default NULL,
  PRIMARY KEY  (id)
);

-- --------------------------------------------------------

-- 
-- Table structure for table `u_menue2stelle`
-- 

CREATE TABLE u_menue2stelle (
  stelle_id int(11) NOT NULL default '0',
  menue_id int(11) NOT NULL default '0',
  menue_order int(11) NOT NULL default '0',
  PRIMARY KEY  (stelle_id,menue_id)
);

-- --------------------------------------------------------

-- 
-- Table structure for table `u_menues`
-- 

CREATE TABLE u_menues (
  id int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `name_low-german_windows-1252` VARCHAR(100) NULL,
  `name_english_windows-1252` VARCHAR(100) CHARACTER SET cp1250 COLLATE cp1250_general_ci NULL,
  `name_polish_utf-8` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `name_vietnamese_utf-8` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,  
  links varchar(255) NOT NULL default '',
  obermenue int(11) NOT NULL default '0',
  menueebene tinyint(4) NOT NULL default '1',
  target varchar(10) default NULL,
  `order` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (id)
);

-- --------------------------------------------------------

-- 
-- Table structure for table `u_styles2classes`
-- 

CREATE TABLE u_styles2classes (
  class_id int(11) NOT NULL default '0',
  style_id int(11) NOT NULL default '0',
  drawingorder int(11) unsigned default NULL,
  PRIMARY KEY  (class_id,style_id)
);

-- --------------------------------------------------------

-- 
-- Table structure for table `used_layer`
-- 

CREATE TABLE used_layer (
  Stelle_ID int(11) NOT NULL default '0',
  Layer_ID int(11) NOT NULL default '0',
  queryable enum('0','1') NOT NULL default '1',
  drawingorder int(11) NOT NULL default '0',
  minscale int(11) default NULL,
  maxscale int(11) default NULL,
  offsite varchar(11) default NULL,
  transparency TINYINT(3) NULL,
  postlabelcache ENUM( '0', '1' ) NOT NULL DEFAULT '0',
  Filter longtext,
  template varchar(255) default NULL,
  header varchar(255) default NULL,
  footer varchar(255) default NULL,
  symbolscale int(11) unsigned default NULL,
  requires varchar(255) default NULL,
  logconsume ENUM( '0', '1' ) NOT NULL default '0',
  privileg ENUM( '0', '1', '2' ) NOT NULL DEFAULT '0',
  start_aktiv ENUM( '0', '1' ) NOT NULL DEFAULT '0',
  PRIMARY KEY (Stelle_ID, Layer_ID)
);


-- --------------------------------------------------------

-- 
-- Table structure for table `user`
-- 

CREATE TABLE user (
  ID int(11) NOT NULL auto_increment,
  login_name varchar(15) NOT NULL default '',
  Name varchar(100) NOT NULL default '',
  Vorname varchar(100) default NULL,
  Namenszusatz VARCHAR( 50 ) NULL,
  passwort varchar(32) NOT NULL default '',
  password_setting_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `start` DATE NOT NULL DEFAULT '0000-00-00',
  `stop` DATE NOT NULL DEFAULT '0000-00-00',
  ips text NULL,
  Funktion enum('admin','user', 'gast') NOT NULL default 'user',
  stelle_id int(11) default NULL,
  phon varchar(15) default NULL,
  email varchar(50) default NULL,
  PRIMARY KEY  (ID)
);

-- ---------------------------------------

--
-- Table structure for table `u_consume`
--

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
`prev` datetime default NULL,
`next` datetime default NULL,
PRIMARY KEY ( `user_id` , `stelle_id` , `time_id` ) 
);

-- ---------------------------------------

--
-- Table structure for table `u_consume2layer`
--

CREATE TABLE `u_consume2layer` (
`user_id` INT NOT NULL ,
`stelle_id` INT NOT NULL ,
`time_id` DATETIME NOT NULL ,
`layer_id` INT NOT NULL,
PRIMARY KEY ( `user_id` , `stelle_id` , `time_id` , `layer_id`)
);  


--
-- Table structure for table `u_consumeALK`
--

CREATE TABLE `u_consumeALK` (
`user_id` INT NOT NULL ,
`stelle_id` INT NOT NULL ,
`time_id` DATETIME NOT NULL ,
`druckrahmen_id` INT NOT NULL,
PRIMARY KEY ( `user_id` , `stelle_id` , `time_id` ) 
);  


--
-- Table structure for table `u_consume2comments`
--

CREATE TABLE `u_consume2comments` (
 `user_id` int(11) NOT NULL,
 `stelle_id` int(11) NOT NULL,
 `time_id` datetime NOT NULL,
 `comment` text,
 PRIMARY KEY  (`user_id`,`stelle_id`,`time_id`)
);

# Hinzufügen einer Tabelle zur Speicherung der ALB-Zugriffe

CREATE TABLE `u_consumeALB` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL,
  `format` int(11) NOT NULL,
  `log_number` varchar(255) NOT NULL,
  `wz` ENUM( '0', '1' ) NULL,
  `numpages` INT( 11 ) NULL ,
  PRIMARY KEY  (`user_id`,`stelle_id`,`time_id`,`log_number`)
);