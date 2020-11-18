BEGIN;


--
-- Table structure for table `classes`
--

CREATE TABLE IF NOT EXISTS `classes` (
  `Class_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL DEFAULT '',
  `Name_low-german` varchar(100) DEFAULT NULL,
  `Name_english` varchar(100) DEFAULT NULL,
  `Name_polish` varchar(100) DEFAULT NULL,
  `Name_vietnamese` varchar(100) DEFAULT NULL,
  `Layer_ID` int(11) NOT NULL DEFAULT '0',
  `Expression` text,
  `drawingorder` int(11) UNSIGNED DEFAULT NULL,
  `legendorder` int(11) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `classification` varchar(50) DEFAULT NULL,
  `legendgraphic` varchar(255) DEFAULT NULL,
  `legendimagewidth` int(11) DEFAULT NULL,
  `legendimageheight` int(11) DEFAULT NULL,
  PRIMARY KEY (`Class_ID`),
  KEY `Layer_ID` (`Layer_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE IF NOT EXISTS `colors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `red` smallint(3) NOT NULL DEFAULT '0',
  `green` smallint(3) NOT NULL DEFAULT '0',
  `blue` smallint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `prefix` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `description` text,
  `type` varchar(20) NOT NULL,
  `group` varchar(50) NOT NULL,
  `plugin` varchar(50) DEFAULT NULL,
  `saved` tinyint(1) NOT NULL,
  `editable` int(11) DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `connections`
--

CREATE TABLE IF NOT EXISTS `connections` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Eindeutige Id der Datenbankverbindungen',
  `name` varchar(150) NOT NULL DEFAULT 'kvwmapsp' COMMENT 'Name der Datenbankverbindung. Kann frei gewählt werden, muss eindeutig sein und Wird in Auswahlliste für Layer angezeigt.',
  `host` varchar(50) DEFAULT 'pgsql' COMMENT 'Hostname der Datenbank. Default ist pgsql wenn der Zugriff aus dem Web-Container heraus erfolgt, sonst auch die IP-Adresse oder Hostname des Datenbankservers oder Docker-Containers in dem der Server läuft. Kann auch als Befehl aufgeführt werden, z.b. $(docker inspect --format ''{{ .NetworkSettings.IPAddress }}'' mysql-server). Wird ein leer-String eingetragen wird vom Postgres-Client localhost verwendet.',
  `port` int(11) DEFAULT '5432' COMMENT 'Die Portnummer mit der die Verbindung zur Datenbank hergestellt werden soll. Default ist 5432. Wird ein leerer Text angegeben, verwendet der Datenbankclient 5432.',
  `dbname` varchar(150) NOT NULL DEFAULT 'kvwmapsp' COMMENT 'Der Name der Datenbank zu der die Verbindung hergestellt werden soll.',
  `user` varchar(150) DEFAULT 'kvwmap' COMMENT 'Der Name des Nutzers mit dem die Verbindung zur Datenbank hergestellt werden soll. Default ist kvwmap. Wird ein leerer Text angegeben verwendet der Datenbankclient den Namen des Nutzers des Betriebssystems, welcher den Datenbankclient aufruft.',
  `password` varchar(150) DEFAULT 'KvwMapPW1' COMMENT 'Das Passwort des Datenbanknutzers. Wird hier ein leerer Text angegeben, wird die Option für das Passwort im Datenbankclient weggelassen. Der Datenbankclient versucht dadurch, wenn ein Passwort erforderlich ist das Passwort aus der Umgebungsvariable PGPASSWORD auszulesen. Steht dort nichts drin, versucht der Client das Passwort aus der Datei, die in der Umgebungsvariable PGPASSFILE angegeben ist auszulesen. Ist das Passwort auch dort nicht zu finden, versucht der Client das Passwort aus der Datei ~/.pgpass auszulesen. Ist auch dort nichts passendes zu Host, Datenbankname, Port und Nutzer zu finden, kann keine Verbindung hergestellt werden.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `connections`
--



-- --------------------------------------------------------

--
-- Table structure for table `cron_jobs`
--

CREATE TABLE IF NOT EXISTS `cron_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `bezeichnung` varchar(255) NOT NULL,
  `beschreibung` text,
  `time` varchar(25) NOT NULL DEFAULT '0 6 1 * *',
  `query` text,
  `function` varchar(255) DEFAULT NULL,
  `url` varchar(1000) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `stelle_id` int(11) DEFAULT NULL,
  `aktiv` tinyint(1) NOT NULL DEFAULT '0',
  `dbname` varchar(68) DEFAULT NULL,
  `user` enum('root','gisadmin') NOT NULL DEFAULT 'gisadmin',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `datatypes`
--

CREATE TABLE IF NOT EXISTS `datatypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(58) DEFAULT NULL,
  `schema` varchar(58) NOT NULL DEFAULT 'public',
  `dbname` varchar(50) NOT NULL,
  `host` varchar(50) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `connection_id` bigint(20) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_datatypes_connection_id` (`connection_id`),
	CONSTRAINT `fk_datatypes_connection_id` FOREIGN KEY (`connection_id`) REFERENCES `connections` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `datatype_attributes`
--

CREATE TABLE IF NOT EXISTS `datatype_attributes` (
  `datatype_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `real_name` varchar(255) DEFAULT NULL,
  `tablename` varchar(100) DEFAULT NULL,
  `table_alias_name` varchar(100) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `geometrytype` varchar(20) DEFAULT NULL,
  `constraints` varchar(255) DEFAULT NULL,
  `nullable` tinyint(1) DEFAULT NULL,
  `length` int(11) DEFAULT NULL,
  `decimal_length` int(11) DEFAULT NULL,
  `default` varchar(255) DEFAULT NULL,
  `form_element_type` enum('Text','Textfeld','Auswahlfeld','Checkbox','Geometrie','SubFormPK','SubFormFK','SubFormEmbeddedPK','Time','Dokument','Link','User','Stelle','Fläche','dynamicLink','Zahl','UserID','Länge','mailto') NOT NULL DEFAULT 'Text',
  `options` text,
  `alias` varchar(255) DEFAULT NULL,
  `alias_low-german` varchar(100) DEFAULT NULL,
  `alias_english` varchar(100) DEFAULT NULL,
  `alias_polish` varchar(100) DEFAULT NULL,
  `alias_vietnamese` varchar(100) DEFAULT NULL,
  `tooltip` varchar(255) DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `raster_visibility` tinyint(1) DEFAULT NULL,
  `mandatory` tinyint(1) DEFAULT NULL,
  `quicksearch` tinyint(1) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `privileg` tinyint(1) DEFAULT '0',
  `query_tooltip` tinyint(1) DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Zeigt oder versteckt Attribut im Layereditor (default: Zeigen).',
  `vcheck_attribute` varchar(255) DEFAULT NULL,
  `vcheck_operator` varchar(4) DEFAULT NULL,
  `vcheck_value` text,
  `arrangement` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Zeigt Attribut unter oder neben dem vorgehenden Attribut (default: darunter).',
  `labeling` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Zeigt Beschriftung gar nicht, über oder links neben dem Attributwert (default: links daneben).',
  PRIMARY KEY (`datatype_id`,`name`),
	CONSTRAINT `datatype_attributes_ibfk_1` FOREIGN KEY (`datatype_id`) REFERENCES `datatypes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `datendrucklayouts`
--

CREATE TABLE IF NOT EXISTS `datendrucklayouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `layer_id` int(11) NOT NULL,
  `format` varchar(10) NOT NULL DEFAULT 'A4 hoch',
  `bgsrc` varchar(255) DEFAULT NULL,
  `bgposx` int(11) DEFAULT NULL,
  `bgposy` int(11) DEFAULT NULL,
  `bgwidth` int(11) DEFAULT NULL,
  `bgheight` int(11) DEFAULT NULL,
  `dateposx` int(11) DEFAULT NULL,
  `dateposy` int(11) DEFAULT NULL,
  `datesize` int(11) DEFAULT NULL,
  `userposx` int(11) DEFAULT NULL,
  `userposy` int(11) DEFAULT NULL,
  `usersize` int(11) DEFAULT NULL,
  `font_date` varchar(255) DEFAULT NULL,
  `font_user` varchar(255) DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `margin_top` int(11) NOT NULL DEFAULT '40',
  `margin_bottom` int(11) NOT NULL DEFAULT '30',
  `margin_left` int(11) NOT NULL DEFAULT '0',
  `margin_right` int(11) NOT NULL DEFAULT '0',
  `gap` int(11) NOT NULL DEFAULT '20',
  `no_record_splitting` tinyint(1) NOT NULL DEFAULT '0',
  `columns` tinyint(4) NOT NULL DEFAULT '0',
  `filename` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ddl2freilinien`
--

CREATE TABLE IF NOT EXISTS `ddl2freilinien` (
  `ddl_id` int(11) NOT NULL,
  `line_id` int(11) NOT NULL,
  PRIMARY KEY (`ddl_id`,`line_id`),
  KEY `line_id` (`line_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `druckfreirechtecke`
--

CREATE TABLE IF NOT EXISTS `druckfreirechtecke` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `posx` int(11) NOT NULL,
  `posy` int(11) NOT NULL,
  `endposx` int(11) NOT NULL,
  `endposy` int(11) NOT NULL,
  `breite` float NOT NULL,
  `color` int(11) DEFAULT NULL,
  `offset_attribute_start` varchar(255) DEFAULT NULL,
  `offset_attribute_end` varchar(255) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `ddl2freirechtecke`
--

CREATE TABLE IF NOT EXISTS `ddl2freirechtecke` (
  `ddl_id` int(11) NOT NULL,
  `rect_id` int(11) NOT NULL,
  KEY `rect_id` (`rect_id`),
	CONSTRAINT `ddl2freirechtecke_ibfk_1` FOREIGN KEY (`rect_id`) REFERENCES `druckfreirechtecke` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ddl2freitexte`
--

CREATE TABLE IF NOT EXISTS `ddl2freitexte` (
  `ddl_id` int(11) NOT NULL,
  `freitext_id` int(11) NOT NULL,
  PRIMARY KEY (`ddl_id`,`freitext_id`),
  KEY `freitext_id` (`freitext_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ddl2stelle`
--

CREATE TABLE IF NOT EXISTS `ddl2stelle` (
  `stelle_id` int(11) NOT NULL,
  `ddl_id` int(11) NOT NULL,
  PRIMARY KEY (`stelle_id`,`ddl_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ddl_colors`
--

CREATE TABLE IF NOT EXISTS `ddl_colors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `red` smallint(3) NOT NULL DEFAULT '0',
  `green` smallint(3) NOT NULL DEFAULT '0',
  `blue` smallint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ddl_colors`
--



-- --------------------------------------------------------

--
-- Table structure for table `ddl_elemente`
--

CREATE TABLE IF NOT EXISTS `ddl_elemente` (
  `ddl_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `xpos` double DEFAULT NULL,
  `ypos` double DEFAULT NULL,
  `offset_attribute` varchar(255) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `border` tinyint(1) DEFAULT NULL,
  `font` varchar(255) DEFAULT NULL,
  `fontsize` int(11) DEFAULT NULL,
  PRIMARY KEY (`ddl_id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `login_name` varchar(100) NOT NULL DEFAULT '',
  `Name` varchar(100) NOT NULL DEFAULT '',
  `Vorname` varchar(100) DEFAULT NULL,
  `Namenszusatz` varchar(50) DEFAULT NULL,
  `passwort` varchar(32) NOT NULL DEFAULT '',
  `password_setting_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `start` date NOT NULL DEFAULT '0000-00-00',
  `stop` date NOT NULL DEFAULT '0000-00-00',
  `ips` text,
  `Funktion` enum('admin','user','gast') NOT NULL DEFAULT 'user',
  `stelle_id` int(11) DEFAULT NULL,
  `phon` varchar(25) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `agreement_accepted` tinyint(1) NOT NULL DEFAULT '0',
  `num_login_failed` int(11) NOT NULL DEFAULT '0' COMMENT 'Anzahl der nacheinander fehlgeschlagenen Loginversuche mit diesem login_namen',
  `organisation` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Table structure for table `druckausschnitte`
--

CREATE TABLE IF NOT EXISTS `druckausschnitte` (
  `stelle_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `epsg_code` int(6) DEFAULT NULL,
  `center_x` float NOT NULL,
  `center_y` float NOT NULL,
  `print_scale` int(11) NOT NULL,
  `angle` int(11) NOT NULL,
  `frame_id` int(11) NOT NULL,
  PRIMARY KEY (`stelle_id`,`user_id`,`id`),
  KEY `user_id` (`user_id`),
	CONSTRAINT `druckausschnitte_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `druckfreibilder`
--

CREATE TABLE IF NOT EXISTS `druckfreibilder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `src` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `druckfreilinien`
--

CREATE TABLE IF NOT EXISTS `druckfreilinien` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `posx` int(11) NOT NULL,
  `posy` int(11) NOT NULL,
  `endposx` int(11) NOT NULL,
  `endposy` int(11) NOT NULL,
  `breite` float NOT NULL,
  `offset_attribute_start` varchar(255) DEFAULT NULL,
  `offset_attribute_end` varchar(255) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------



--
-- Table structure for table `druckfreitexte`
--

CREATE TABLE IF NOT EXISTS `druckfreitexte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text,
  `posx` int(11) NOT NULL,
  `posy` int(11) NOT NULL,
  `offset_attribute` varchar(255) DEFAULT NULL,
  `size` int(11) NOT NULL,
  `width` int(11) DEFAULT NULL,
  `border` tinyint(1) DEFAULT NULL,
  `font` varchar(255) NOT NULL,
  `angle` int(11) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `druckrahmen`
--

CREATE TABLE IF NOT EXISTS `druckrahmen` (
  `Name` varchar(255) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dhk_call` varchar(10) DEFAULT NULL,
  `headsrc` varchar(255) NOT NULL,
  `headposx` int(11) NOT NULL,
  `headposy` int(11) NOT NULL,
  `headwidth` int(11) NOT NULL,
  `headheight` int(11) NOT NULL,
  `mapposx` int(11) NOT NULL,
  `mapposy` int(11) NOT NULL,
  `mapwidth` int(11) NOT NULL,
  `mapheight` int(11) NOT NULL,
  `refmapsrc` varchar(255) DEFAULT NULL,
  `refmapfile` varchar(255) DEFAULT NULL,
  `refmapposx` int(11) DEFAULT NULL,
  `refmapposy` int(11) DEFAULT NULL,
  `refmapwidth` int(11) DEFAULT NULL,
  `refmapheight` int(11) DEFAULT NULL,
  `refposx` int(11) DEFAULT NULL,
  `refposy` int(11) DEFAULT NULL,
  `refwidth` int(11) DEFAULT NULL,
  `refheight` int(11) DEFAULT NULL,
  `refzoom` int(11) DEFAULT NULL,
  `dateposx` int(11) DEFAULT NULL,
  `dateposy` int(11) DEFAULT NULL,
  `datesize` int(11) DEFAULT NULL,
  `scaleposx` int(11) DEFAULT NULL,
  `scaleposy` int(11) DEFAULT NULL,
  `scalesize` int(11) DEFAULT NULL,
  `scalebarposx` int(11) DEFAULT NULL,
  `scalebarposy` int(11) DEFAULT NULL,
  `oscaleposx` int(11) DEFAULT NULL,
  `oscaleposy` int(11) DEFAULT NULL,
  `oscalesize` int(11) DEFAULT NULL,
  `lageposx` int(11) DEFAULT NULL,
  `lageposy` int(11) DEFAULT NULL,
  `lagesize` int(11) DEFAULT NULL,
  `gemeindeposx` int(11) DEFAULT NULL,
  `gemeindeposy` int(11) DEFAULT NULL,
  `gemeindesize` int(11) DEFAULT NULL,
  `gemarkungposx` int(11) DEFAULT NULL,
  `gemarkungposy` int(11) DEFAULT NULL,
  `gemarkungsize` int(11) DEFAULT NULL,
  `flurposx` int(11) DEFAULT NULL,
  `flurposy` int(11) DEFAULT NULL,
  `flursize` int(11) DEFAULT NULL,
  `flurstposx` int(11) DEFAULT NULL,
  `flurstposy` int(11) DEFAULT NULL,
  `flurstsize` int(11) DEFAULT NULL,
  `legendposx` int(11) DEFAULT NULL,
  `legendposy` int(11) DEFAULT NULL,
  `legendsize` int(11) DEFAULT NULL,
  `arrowposx` int(11) DEFAULT NULL,
  `arrowposy` int(11) DEFAULT NULL,
  `arrowlength` int(11) DEFAULT NULL,
  `userposx` int(11) DEFAULT NULL,
  `userposy` int(11) DEFAULT NULL,
  `usersize` int(11) DEFAULT NULL,
  `watermarkposx` int(11) DEFAULT NULL,
  `watermarkposy` int(11) DEFAULT NULL,
  `watermark` varchar(255) DEFAULT '',
  `watermarksize` int(11) DEFAULT NULL,
  `watermarkangle` int(11) DEFAULT NULL,
  `watermarktransparency` int(11) DEFAULT NULL,
  `variable_freetexts` tinyint(1) DEFAULT NULL,
  `format` varchar(10) NOT NULL DEFAULT 'A4hoch',
  `preis` int(11) DEFAULT NULL,
  `font_date` varchar(255) DEFAULT NULL,
  `font_scale` varchar(255) DEFAULT NULL,
  `font_lage` varchar(255) DEFAULT NULL,
  `font_gemeinde` varchar(255) DEFAULT NULL,
  `font_gemarkung` varchar(255) DEFAULT NULL,
  `font_flur` varchar(255) DEFAULT NULL,
  `font_flurst` varchar(255) DEFAULT NULL,
  `font_oscale` varchar(255) DEFAULT NULL,
  `font_legend` varchar(255) DEFAULT NULL,
  `font_watermark` varchar(255) DEFAULT NULL,
  `font_user` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `druckrahmen2freibilder`
--

CREATE TABLE IF NOT EXISTS `druckrahmen2freibilder` (
  `druckrahmen_id` int(11) NOT NULL,
  `freibild_id` int(11) NOT NULL,
  `posx` int(11) NOT NULL,
  `posy` int(11) NOT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `angle` int(11) DEFAULT NULL,
  PRIMARY KEY (`druckrahmen_id`,`freibild_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `druckrahmen2freitexte`
--

CREATE TABLE IF NOT EXISTS `druckrahmen2freitexte` (
  `druckrahmen_id` int(11) NOT NULL,
  `freitext_id` int(11) NOT NULL,
  PRIMARY KEY (`druckrahmen_id`,`freitext_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `druckrahmen2stelle`
--

CREATE TABLE IF NOT EXISTS `druckrahmen2stelle` (
  `stelle_id` int(11) NOT NULL,
  `druckrahmen_id` int(11) NOT NULL,
  PRIMARY KEY (`stelle_id`,`druckrahmen_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `invitations`
--

CREATE TABLE IF NOT EXISTS `invitations` (
  `token` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `stelle_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `vorname` varchar(255) NOT NULL,
  `inviter_id` int(11) DEFAULT NULL,
  `completed` datetime DEFAULT NULL,
  PRIMARY KEY (`token`,`email`,`stelle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `labels`
--

CREATE TABLE IF NOT EXISTS `labels` (
  `Label_ID` int(11) NOT NULL AUTO_INCREMENT,
  `font` varchar(25) NOT NULL DEFAULT 'arial',
  `type` int(1) DEFAULT NULL,
  `color` varchar(11) NOT NULL DEFAULT '',
  `outlinecolor` varchar(11) DEFAULT NULL,
  `shadowcolor` varchar(11) DEFAULT NULL,
  `shadowsizex` int(3) DEFAULT NULL,
  `shadowsizey` int(3) DEFAULT NULL,
  `backgroundcolor` varchar(11) DEFAULT NULL,
  `backgroundshadowcolor` varchar(11) DEFAULT NULL,
  `backgroundshadowsizex` int(3) DEFAULT NULL,
  `backgroundshadowsizey` int(3) DEFAULT NULL,
  `size` int(2) DEFAULT NULL,
  `minsize` tinyint(3) DEFAULT NULL,
  `maxsize` tinyint(3) DEFAULT NULL,
  `position` tinyint(1) DEFAULT NULL,
  `offsetx` tinyint(3) DEFAULT NULL,
  `offsety` tinyint(3) DEFAULT NULL,
  `angle` double DEFAULT NULL,
  `autoangle` tinyint(1) DEFAULT NULL,
  `buffer` tinyint(3) DEFAULT NULL,
  `antialias` tinyint(1) DEFAULT NULL,
  `minfeaturesize` int(11) DEFAULT NULL,
  `maxfeaturesize` int(11) DEFAULT NULL,
  `partials` int(1) DEFAULT NULL,
  `maxlength` int(3) DEFAULT NULL,
  `repeatdistance` int(11) DEFAULT NULL,
  `wrap` tinyint(3) DEFAULT NULL,
  `the_force` int(1) DEFAULT NULL,
  PRIMARY KEY (`Label_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `layer`
--

CREATE TABLE IF NOT EXISTS `layer` (
  `Layer_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Name_low-german` varchar(100) DEFAULT NULL,
  `Name_english` varchar(100) DEFAULT NULL,
  `Name_polish` varchar(100) DEFAULT NULL,
  `Name_vietnamese` varchar(100) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `Datentyp` tinyint(4) NOT NULL DEFAULT '2',
  `Gruppe` int(11) NOT NULL DEFAULT '0',
  `pfad` mediumtext,
  `maintable` varchar(255) DEFAULT NULL,
  `oid` varchar(63) NOT NULL DEFAULT 'oid',
  `maintable_is_view` tinyint(1) NOT NULL DEFAULT '0',
  `Data` mediumtext,
  `schema` varchar(50) DEFAULT NULL,
  `document_path` mediumtext,
  `document_url` text,
  `ddl_attribute` varchar(255) DEFAULT NULL,
  `tileindex` varchar(100) DEFAULT NULL,
  `tileitem` varchar(100) DEFAULT NULL,
  `labelangleitem` varchar(25) DEFAULT NULL,
  `labelitem` varchar(100) DEFAULT NULL,
  `labelmaxscale` int(11) DEFAULT NULL,
  `labelminscale` int(11) DEFAULT NULL,
  `labelrequires` varchar(255) DEFAULT NULL,
  `postlabelcache` tinyint(1) DEFAULT '0',
  `connection` mediumtext NOT NULL,
  `connection_id` bigint(20) UNSIGNED DEFAULT NULL,
  `printconnection` mediumtext,
  `connectiontype` tinyint(4) NOT NULL DEFAULT '0',
  `classitem` varchar(100) DEFAULT NULL,
  `styleitem` varchar(100) DEFAULT NULL,
  `classification` varchar(50) DEFAULT NULL,
  `cluster_maxdistance` int(11) DEFAULT NULL,
  `tolerance` double NOT NULL DEFAULT '3',
  `toleranceunits` enum('pixels','feet','inches','kilometers','meters','miles','dd') NOT NULL DEFAULT 'pixels',
  `epsg_code` varchar(6) DEFAULT '2398',
  `template` varchar(255) DEFAULT NULL,
  `max_query_rows` int(11) DEFAULT NULL,
  `queryable` enum('0','1') NOT NULL DEFAULT '0',
  `use_geom` tinyint(1) NOT NULL DEFAULT '1',
  `transparency` int(3) DEFAULT NULL,
  `drawingorder` int(11) DEFAULT NULL,
  `legendorder` int(11) DEFAULT NULL,
  `minscale` int(11) DEFAULT NULL,
  `maxscale` int(11) DEFAULT NULL,
  `symbolscale` int(11) DEFAULT NULL,
  `offsite` varchar(11) DEFAULT NULL,
  `requires` int(11) DEFAULT NULL,
  `ows_srs` varchar(255) NOT NULL DEFAULT 'EPSG:2398',
  `wms_name` varchar(255) DEFAULT NULL,
  `wms_keywordlist` text,
  `wms_server_version` varchar(8) NOT NULL DEFAULT '1.1.0',
  `wms_format` varchar(50) NOT NULL DEFAULT 'image/png',
  `wms_connectiontimeout` int(11) NOT NULL DEFAULT '60',
  `wms_auth_username` varchar(50) DEFAULT NULL,
  `wms_auth_password` varchar(50) DEFAULT NULL,
  `wfs_geom` varchar(100) DEFAULT NULL,
  `selectiontype` varchar(20) DEFAULT NULL,
  `querymap` enum('0','1') NOT NULL DEFAULT '0',
  `logconsume` enum('0','1') NOT NULL DEFAULT '0',
  `processing` varchar(255) DEFAULT NULL,
  `kurzbeschreibung` text,
  `datenherr` varchar(100) DEFAULT NULL,
  `metalink` varchar(255) DEFAULT NULL,
  `privileg` enum('0','1','2') NOT NULL DEFAULT '0',
  `export_privileg` tinyint(1) NOT NULL DEFAULT '1',
  `status` varchar(255) DEFAULT NULL,
  `trigger_function` varchar(255) DEFAULT NULL COMMENT 'Wie heist die Trigger Funktion, die ausgelöst werden soll.',
  `sync` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Wenn 1, werden Änderungen in maintable_delta gespeichert und stellt ein das Layer für Syncronisierung mit kvmobile verfügbar ist.',
  `editable` tinyint(1) NOT NULL DEFAULT '1',
  `listed` tinyint(1) NOT NULL DEFAULT '1',
  `duplicate_from_layer_id` int(11) DEFAULT NULL,
  `duplicate_criterion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Layer_ID`),
  KEY `Gruppe` (`Gruppe`),
  KEY `fk_layer_connection_id` (`connection_id`),
	CONSTRAINT `fk_layer_connection_id` FOREIGN KEY (`connection_id`) REFERENCES `connections` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `layer_attributes`
--

CREATE TABLE IF NOT EXISTS `layer_attributes` (
  `layer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `real_name` varchar(255) DEFAULT NULL,
  `tablename` varchar(100) DEFAULT NULL,
  `table_alias_name` varchar(100) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `geometrytype` varchar(20) DEFAULT NULL,
  `constraints` text,
  `saveable` tinyint(1) NOT NULL,
  `nullable` tinyint(1) DEFAULT NULL,
  `length` int(11) DEFAULT NULL,
  `decimal_length` int(11) DEFAULT NULL,
  `default` varchar(255) DEFAULT NULL,
  `form_element_type` enum('Text','Textfeld','Auswahlfeld','Autovervollständigungsfeld','Autovervollständigungsfeld_zweispaltig','Radiobutton','Checkbox','Geometrie','SubFormPK','SubFormFK','SubFormEmbeddedPK','Time','Dokument','Link','dynamicLink','User','UserID','Stelle','StelleID','Fläche','Länge','Zahl','mailto','Winkel','Style','Editiersperre','ExifLatLng','ExifRichtung','ExifErstellungszeit') NOT NULL DEFAULT 'Text',
  `options` text,
  `alias` varchar(255) DEFAULT NULL,
  `alias_low-german` varchar(100) DEFAULT NULL,
  `alias_english` varchar(100) DEFAULT NULL,
  `alias_polish` varchar(100) DEFAULT NULL,
  `alias_vietnamese` varchar(100) DEFAULT NULL,
  `tooltip` text,
  `group` varchar(255) DEFAULT NULL,
  `arrangement` tinyint(1) NOT NULL DEFAULT '0',
  `labeling` tinyint(1) NOT NULL DEFAULT '0',
  `raster_visibility` tinyint(1) DEFAULT NULL,
  `dont_use_for_new` tinyint(1) DEFAULT NULL,
  `mandatory` tinyint(1) DEFAULT NULL,
  `quicksearch` tinyint(1) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `kvp` tinyint(1) NOT NULL DEFAULT '0',
  `vcheck_attribute` varchar(255) DEFAULT NULL,
  `vcheck_operator` varchar(4) DEFAULT NULL,
  `vcheck_value` text,
  `order` int(11) DEFAULT NULL,
  `privileg` tinyint(1) DEFAULT '0',
  `query_tooltip` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`layer_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rolle`
--

CREATE TABLE IF NOT EXISTS `rolle` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `stelle_id` int(11) NOT NULL DEFAULT '0',
  `nImageWidth` int(3) NOT NULL DEFAULT '800',
  `nImageHeight` int(3) NOT NULL DEFAULT '600',
  `auto_map_resize` tinyint(1) NOT NULL DEFAULT '1',
  `minx` double NOT NULL DEFAULT '201165',
  `miny` double NOT NULL DEFAULT '5867815',
  `maxx` double NOT NULL DEFAULT '77900',
  `maxy` double NOT NULL DEFAULT '6081068',
  `nZoomFactor` int(11) NOT NULL DEFAULT '2',
  `selectedButton` varchar(20) NOT NULL DEFAULT 'zoomin',
  `epsg_code` varchar(6) DEFAULT '25833',
  `epsg_code2` varchar(6) DEFAULT NULL,
  `coordtype` enum('dec','dms','dmin') NOT NULL DEFAULT 'dec',
  `active_frame` int(11) NOT NULL DEFAULT '0',
  `last_time_id` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `gui` varchar(100) NOT NULL DEFAULT 'layouts/gui.php',
  `language` enum('german','low-german','english','polish','vietnamese') NOT NULL DEFAULT 'german',
  `hidemenue` enum('0','1') NOT NULL DEFAULT '0',
  `hidelegend` enum('0','1') NOT NULL DEFAULT '0',
  `fontsize_gle` int(2) DEFAULT '15',
  `highlighting` tinyint(1) NOT NULL DEFAULT '0',
  `buttons` varchar(255) DEFAULT 'back,forward,zoomin,zoomout,zoomall,recentre,jumpto,coord_query,query,touchquery,queryradius,polyquery,measure',
  `geom_buttons` varchar(255) DEFAULT 'delete,polygon,flurstquery,polygon2,buffer,transform,vertex_edit,coord_input,ortho_point,measure',
  `scrollposition` int(11) NOT NULL DEFAULT '0',
  `result_color` int(11) DEFAULT '1',
  `result_hatching` tinyint(1) NOT NULL DEFAULT '0',
  `result_transparency` tinyint(4) NOT NULL DEFAULT '60',
  `always_draw` tinyint(1) DEFAULT NULL,
  `runningcoords` tinyint(1) NOT NULL DEFAULT '0',
  `showmapfunctions` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Schaltet die Menüleiste mit den Kartenfunktionen unter der Karte ein oder aus.',
  `showlayeroptions` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Schaltet die Layeroptionen in der Legende ein oder aus.',
  `showrollenfilter` tinyint(1) NOT NULL DEFAULT '0',
  `singlequery` tinyint(1) NOT NULL DEFAULT '1',
  `querymode` tinyint(1) NOT NULL DEFAULT '0',
  `geom_edit_first` tinyint(1) NOT NULL DEFAULT '0',
  `overlayx` int(11) NOT NULL DEFAULT '400',
  `overlayy` int(11) NOT NULL DEFAULT '150',
  `hist_timestamp` timestamp NULL DEFAULT NULL,
  `instant_reload` tinyint(1) NOT NULL DEFAULT '1',
  `menu_auto_close` tinyint(1) NOT NULL DEFAULT '0',
  `visually_impaired` tinyint(1) NOT NULL DEFAULT '0',
  `layer_params` text,
  `menue_buttons` tinyint(1) NOT NULL DEFAULT '0',
  `legendtype` tinyint(1) NOT NULL DEFAULT '0',
  `print_legend_separate` tinyint(1) NOT NULL DEFAULT '0',
  `print_scale` varchar(11) NOT NULL DEFAULT 'auto',
  PRIMARY KEY (`user_id`,`stelle_id`),
  KEY `user_id_idx` (`user_id`),
	CONSTRAINT `rolle_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Table structure for table `layer_attributes2stelle`
--

CREATE TABLE IF NOT EXISTS `layer_attributes2stelle` (
  `layer_id` int(11) NOT NULL,
  `attributename` varchar(255) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `privileg` tinyint(1) NOT NULL,
  `tooltip` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`layer_id`,`attributename`,`stelle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Table structure for table `layer_attributes2rolle`
--

CREATE TABLE IF NOT EXISTS `layer_attributes2rolle` (
  `layer_id` int(11) NOT NULL,
  `attributename` varchar(255) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `switchable` tinyint(1) NOT NULL DEFAULT '1',
  `switched_on` tinyint(1) NOT NULL DEFAULT '1',
  `sortable` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int(11) NOT NULL DEFAULT '1',
  `sort_direction` enum('asc','desc') NOT NULL DEFAULT 'asc',
  PRIMARY KEY (`layer_id`,`attributename`,`stelle_id`,`user_id`),
  KEY `user_id` (`user_id`,`stelle_id`),
	CONSTRAINT `layer_attributes2rolle_ibfk_2` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE,
  CONSTRAINT `layer_attributes2rolle_ibfk_1` FOREIGN KEY (`layer_id`,`attributename`,`stelle_id`) REFERENCES `layer_attributes2stelle` (`layer_id`, `attributename`, `stelle_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------



-- --------------------------------------------------------

--
-- Table structure for table `layer_parameter`
--

CREATE TABLE IF NOT EXISTS `layer_parameter` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `default_value` varchar(255) NOT NULL,
  `options_sql` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `component` varchar(50) NOT NULL,
  `type` enum('mysql','postgresql') NOT NULL,
  `filename` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `referenzkarten`
--

CREATE TABLE IF NOT EXISTS `referenzkarten` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL DEFAULT '',
  `Dateiname` varchar(100) NOT NULL DEFAULT '',
  `epsg_code` int(11) NOT NULL DEFAULT '2398',
  `xmin` double NOT NULL DEFAULT '0',
  `ymin` double NOT NULL DEFAULT '0',
  `xmax` double NOT NULL DEFAULT '0',
  `ymax` double NOT NULL DEFAULT '0',
  `width` int(4) UNSIGNED NOT NULL DEFAULT '0',
  `height` int(4) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------



-- --------------------------------------------------------

--
-- Table structure for table `rollenlayer`
--

CREATE TABLE IF NOT EXISTS `rollenlayer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `aktivStatus` enum('0','1','2') NOT NULL,
  `queryStatus` enum('0','1','2') NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Gruppe` int(11) NOT NULL,
  `Typ` enum('search','import') NOT NULL DEFAULT 'search',
  `Datentyp` int(11) NOT NULL,
  `Data` longtext NOT NULL,
  `query` text,
  `connectiontype` int(11) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `connection_id` bigint(20) UNSIGNED DEFAULT NULL,
  `epsg_code` int(11) NOT NULL,
  `transparency` int(11) NOT NULL,
  `labelitem` varchar(100) DEFAULT NULL,
  `classitem` varchar(100) DEFAULT NULL,
  `gle_view` tinyint(1) NOT NULL DEFAULT '1',
  `rollenfilter` text,
  `duplicate_from_layer_id` int(11) DEFAULT NULL,
  `duplicate_criterion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`stelle_id`),
  KEY `fk_rollen_layer_connection_id` (`connection_id`),
	CONSTRAINT `fk_rollen_layer_connection_id` FOREIGN KEY (`connection_id`) REFERENCES `connections` (`id`),
  CONSTRAINT `rollenlayer_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rolle_csv_attributes`
--

CREATE TABLE IF NOT EXISTS `rolle_csv_attributes` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `attributes` text NOT NULL,
  PRIMARY KEY (`user_id`,`stelle_id`,`name`),
	CONSTRAINT `rolle_csv_attributes_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rolle_last_query`
--

CREATE TABLE IF NOT EXISTS `rolle_last_query` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `go` varchar(50) NOT NULL,
  `layer_id` int(11) NOT NULL,
  `sql` longtext NOT NULL,
  `orderby` text,
  `limit` int(11) DEFAULT NULL,
  `offset` int(11) DEFAULT NULL,
  KEY `user_id` (`user_id`,`stelle_id`),
	CONSTRAINT `rolle_last_query_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rolle_saved_layers`
--

CREATE TABLE IF NOT EXISTS `rolle_saved_layers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `layers` text NOT NULL,
  `query` text,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`stelle_id`),
	CONSTRAINT `rolle_saved_layers_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `search_attributes2rolle`
--

CREATE TABLE IF NOT EXISTS `search_attributes2rolle` (
  `name` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `layer_id` int(11) NOT NULL,
  `attribute` varchar(50) NOT NULL,
  `operator` varchar(11) NOT NULL,
  `value1` text,
  `value2` text,
  `searchmask_number` int(11) NOT NULL DEFAULT '0',
  `searchmask_operator` enum('AND','OR') DEFAULT NULL,
  PRIMARY KEY (`name`,`user_id`,`stelle_id`,`layer_id`,`attribute`,`searchmask_number`),
  KEY `user_id` (`user_id`,`stelle_id`),
	CONSTRAINT `search_attributes2rolle_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sicherungen`
--

CREATE TABLE IF NOT EXISTS `sicherungen` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Eindeutige Id der Sicherungen',
  `name` varchar(150) NOT NULL DEFAULT 'Tagessicherung' COMMENT 'Name der Sicherung. Wird verwendet als Name des Sicherungsscrptes. Darf keine Leer- und Sonderzeichen beinhalten. Muss sich unterscheiden von anderen.',
  `beschreibung` text NOT NULL,
  `intervall` varchar(25) NOT NULL DEFAULT '0 1 * * *' COMMENT 'Wann die Sicherung ausgeführt werden soll.',
  `target_dir` varchar(255) NOT NULL DEFAULT '/var/www/backups/$day',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Durchzuführende Sicherungen';

-- --------------------------------------------------------

--
-- Table structure for table `sicherungsinhalte`
--

CREATE TABLE IF NOT EXISTS `sicherungsinhalte` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Eindeutige Id der Sicherungsinhalte',
  `name` varchar(150) NOT NULL COMMENT 'Name der Sicherungsinhalte',
  `beschreibung` text COMMENT 'Beschreibung der Sicherungsinhalte',
  `methode` enum('Verzeichnissicherung','Verzeichnisinhalte kopieren','Datei kopieren','Postgres Dump','Mysql Dump') NOT NULL DEFAULT 'Verzeichnissicherung' COMMENT 'Methode der Sicherung',
  `source` varchar(255) NOT NULL DEFAULT '/var/www/apps/kvwmap' COMMENT 'Quelle des Sicherungsinhaltes. Bei Datenbanksicherungen der Name der Datenbank, sonst das Verzeichnis oder Dateiname mit Verzeichnisangabe.',
  `connection_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Die ID der Datenbankverbindung, die für den Zugriff auf die Datenbank beim Dump verwendet werden soll.',
  `target` varchar(255) NOT NULL DEFAULT 'kvwmap' COMMENT 'Ziel der Sicherung. Ist immer ein Dateiname mit Verzeichnisangabe.',
  `overwrite` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Ob das Ziel überschrieben werden soll wenn es existiert oder nicht.',
  `sicherung_id` bigint(20) UNSIGNED NOT NULL COMMENT 'ID der Sicherung in der der Inhalt gesichert werden soll.',
  PRIMARY KEY (`id`),
  KEY `fk_sicherung_id` (`sicherung_id`),
	CONSTRAINT `sicherungsinhalte_ibfk_1` FOREIGN KEY (`sicherung_id`) REFERENCES `sicherungen` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stelle`
--

CREATE TABLE IF NOT EXISTS `stelle` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Bezeichnung` varchar(255) DEFAULT NULL,
  `Bezeichnung_low-german` varchar(255) DEFAULT NULL,
  `Bezeichnung_english` varchar(255) DEFAULT NULL,
  `Bezeichnung_polish` varchar(255) DEFAULT NULL,
  `Bezeichnung_vietnamese` varchar(255) DEFAULT NULL,
  `start` date NOT NULL DEFAULT '0000-00-00',
  `stop` date NOT NULL DEFAULT '0000-00-00',
  `minxmax` double DEFAULT NULL,
  `minymax` double DEFAULT NULL,
  `maxxmax` double DEFAULT NULL,
  `maxymax` double DEFAULT NULL,
  `epsg_code` varchar(6) DEFAULT '2398',
  `Referenzkarte_ID` int(11) DEFAULT NULL,
  `Authentifizierung` enum('0','1') NOT NULL DEFAULT '1',
  `ALB_status` enum('30','35') NOT NULL DEFAULT '30',
  `wappen` varchar(150) NOT NULL DEFAULT 'stz.gif',
  `wappen_link` varchar(255) DEFAULT NULL,
  `logconsume` enum('0','1') DEFAULT NULL,
  `pgdbhost` varchar(25) NOT NULL DEFAULT 'localhost',
  `pgdbname` varchar(25) DEFAULT NULL,
  `pgdbuser` varchar(25) DEFAULT NULL,
  `pgdbpasswd` varchar(25) DEFAULT NULL,
  `ows_title` varchar(255) DEFAULT NULL,
  `wms_accessconstraints` varchar(255) DEFAULT NULL,
  `ows_abstract` varchar(255) DEFAULT NULL,
  `ows_contactperson` varchar(255) DEFAULT NULL,
  `ows_contactorganization` varchar(255) DEFAULT NULL,
  `ows_contactemailaddress` varchar(255) DEFAULT NULL,
  `ows_contactposition` varchar(255) DEFAULT NULL,
  `ows_fees` varchar(255) DEFAULT NULL,
  `ows_srs` varchar(255) DEFAULT NULL,
  `protected` enum('0','1') NOT NULL DEFAULT '0',
  `check_client_ip` enum('0','1') NOT NULL DEFAULT '0',
  `check_password_age` enum('0','1') NOT NULL DEFAULT '0',
  `allowed_password_age` tinyint(4) NOT NULL DEFAULT '6',
  `use_layer_aliases` enum('0','1') NOT NULL DEFAULT '0',
  `hist_timestamp` tinyint(1) NOT NULL DEFAULT '0',
  `selectable_layer_params` text,
  `default_user_id` int(11) DEFAULT NULL COMMENT 'Nutzer Id der default Rolle. Die Einstellungen dieser Rolle werden für das Anlegen neuer Rollen für diese Stelle verwendet. Ist dieser Wert nicht angegeben oder die angegebene Rolle existiert nicht, werden die Defaultwerte der Rollenoptionen bei der Zuordnung eines Nutzers zu dieser Stelle verwendet. Die Angabe ist nützlich, wenn die Einstellungen in Gaststellen am Anfang immer gleich sein sollen.',
  `style` varchar(100) DEFAULT NULL,
  `postgres_connection_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stellen_hierarchie`
--

CREATE TABLE IF NOT EXISTS `stellen_hierarchie` (
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `child_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`parent_id`,`child_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stelle_gemeinden`
--

CREATE TABLE IF NOT EXISTS `stelle_gemeinden` (
  `Stelle_ID` int(11) NOT NULL DEFAULT '0',
  `Gemeinde_ID` int(8) NOT NULL DEFAULT '0',
  `Gemarkung` int(6) DEFAULT NULL,
  `Flur` int(3) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `styles`
--

CREATE TABLE IF NOT EXISTS `styles` (
  `Style_ID` int(11) NOT NULL AUTO_INCREMENT,
  `symbol` int(3) DEFAULT NULL,
  `symbolname` text,
  `size` varchar(50) DEFAULT NULL,
  `color` varchar(11) DEFAULT NULL,
  `backgroundcolor` varchar(11) DEFAULT NULL,
  `outlinecolor` varchar(11) DEFAULT NULL,
  `colorrange` varchar(23) DEFAULT NULL,
  `datarange` varchar(255) DEFAULT NULL,
  `rangeitem` varchar(50) DEFAULT NULL,
  `opacity` int(11) DEFAULT NULL,
  `minsize` int(11) UNSIGNED DEFAULT NULL,
  `maxsize` int(11) UNSIGNED DEFAULT NULL,
  `minscale` int(11) UNSIGNED DEFAULT NULL,
  `maxscale` int(11) UNSIGNED DEFAULT NULL,
  `angle` varchar(11) DEFAULT NULL,
  `angleitem` varchar(255) DEFAULT NULL,
  `antialias` tinyint(1) DEFAULT NULL,
  `width` varchar(50) DEFAULT NULL,
  `minwidth` int(11) DEFAULT NULL,
  `maxwidth` int(11) DEFAULT NULL,
  `offsetx` int(11) DEFAULT NULL,
  `offsety` int(11) DEFAULT NULL,
  `polaroffset` varchar(255) DEFAULT NULL,
  `pattern` varchar(255) DEFAULT NULL,
  `geomtransform` varchar(20) DEFAULT NULL,
  `gap` int(11) DEFAULT NULL,
  `initialgap` decimal(5,2) DEFAULT NULL,
  `linecap` varchar(8) DEFAULT NULL,
  `linejoin` varchar(5) DEFAULT NULL,
  `linejoinmaxsize` int(11) DEFAULT NULL,
  PRIMARY KEY (`Style_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `used_layer`
--

CREATE TABLE IF NOT EXISTS `used_layer` (
  `Stelle_ID` int(11) NOT NULL DEFAULT '0',
  `Layer_ID` int(11) NOT NULL DEFAULT '0',
  `queryable` enum('0','1') NOT NULL DEFAULT '1',
  `drawingorder` int(11) NOT NULL DEFAULT '0',
  `legendorder` int(11) DEFAULT NULL,
  `minscale` int(11) DEFAULT NULL,
  `maxscale` int(11) DEFAULT NULL,
  `offsite` varchar(11) DEFAULT NULL,
  `transparency` tinyint(3) DEFAULT NULL,
  `postlabelcache` enum('0','1') NOT NULL DEFAULT '0',
  `Filter` longtext,
  `template` varchar(255) DEFAULT NULL,
  `header` varchar(255) DEFAULT NULL,
  `footer` varchar(255) DEFAULT NULL,
  `symbolscale` int(11) UNSIGNED DEFAULT NULL,
  `requires` int(11) DEFAULT NULL,
  `logconsume` enum('0','1') NOT NULL DEFAULT '0',
  `privileg` enum('0','1','2') NOT NULL DEFAULT '0',
  `export_privileg` tinyint(1) NOT NULL DEFAULT '1',
  `start_aktiv` enum('0','1') NOT NULL DEFAULT '0',
  `use_geom` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Stelle_ID`,`Layer_ID`),
  KEY `layer_id_idx` (`Layer_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `login_name` varchar(100) NOT NULL DEFAULT '',
  `Name` varchar(100) NOT NULL DEFAULT '',
  `Vorname` varchar(100) DEFAULT NULL,
  `Namenszusatz` varchar(50) DEFAULT NULL,
  `passwort` varchar(32) NOT NULL DEFAULT '',
  `password_setting_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `start` date NOT NULL DEFAULT '0000-00-00',
  `stop` date NOT NULL DEFAULT '0000-00-00',
  `ips` text,
  `Funktion` enum('admin','user','gast') NOT NULL DEFAULT 'user',
  `stelle_id` int(11) DEFAULT NULL,
  `phon` varchar(25) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `agreement_accepted` tinyint(1) NOT NULL DEFAULT '0',
  `num_login_failed` int(11) NOT NULL DEFAULT '0' COMMENT 'Anzahl der nacheinander fehlgeschlagenen Loginversuche mit diesem login_namen',
  `organisation` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Table structure for table `u_attributfilter2used_layer`
--

CREATE TABLE IF NOT EXISTS `u_attributfilter2used_layer` (
  `Stelle_ID` int(11) NOT NULL,
  `Layer_ID` int(11) NOT NULL,
  `attributname` varchar(255) NOT NULL,
  `attributvalue` mediumtext NOT NULL,
  `operator` enum('=','!=','>','<','like','IS','IN','st_within','st_intersects') NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`Stelle_ID`,`Layer_ID`,`attributname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_consume`
--

CREATE TABLE IF NOT EXISTS `u_consume` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL,
  `activity` varchar(255) DEFAULT NULL,
  `nimagewidth` int(11) DEFAULT NULL,
  `nimageheight` int(11) DEFAULT NULL,
  `epsg_code` varchar(6) DEFAULT NULL,
  `minx` double DEFAULT NULL,
  `miny` double DEFAULT NULL,
  `maxx` double DEFAULT NULL,
  `maxy` double DEFAULT NULL,
  `prev` datetime DEFAULT NULL,
  `next` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`,`stelle_id`,`time_id`),
	CONSTRAINT `u_consume_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_consume2comments`
--

CREATE TABLE IF NOT EXISTS `u_consume2comments` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL,
  `comment` text,
  `public` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`stelle_id`,`time_id`),
	CONSTRAINT `u_consume2comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_consume2layer`
--

CREATE TABLE IF NOT EXISTS `u_consume2layer` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL,
  `layer_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`stelle_id`,`time_id`,`layer_id`),
	CONSTRAINT `u_consume2layer_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_consumeALB`
--

CREATE TABLE IF NOT EXISTS `u_consumeALB` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL,
  `format` varchar(50) NOT NULL,
  `log_number` varchar(255) NOT NULL,
  `wz` enum('0','1') DEFAULT NULL,
  `numpages` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`stelle_id`,`time_id`,`log_number`),
	CONSTRAINT `u_consumeALB_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_consumeALK`
--

CREATE TABLE IF NOT EXISTS `u_consumeALK` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL,
  `druckrahmen_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`stelle_id`,`time_id`),
	CONSTRAINT `u_consumeALK_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_consumeCSV`
--

CREATE TABLE IF NOT EXISTS `u_consumeCSV` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `art` varchar(20) NOT NULL,
  `numdatasets` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`stelle_id`,`time_id`),
	CONSTRAINT `u_consumeCSV_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_consumeShape`
--

CREATE TABLE IF NOT EXISTS `u_consumeShape` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `layer_id` int(11) NOT NULL,
  `numdatasets` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`stelle_id`,`time_id`),
	CONSTRAINT `u_consumeShape_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_funktion2stelle`
--

CREATE TABLE IF NOT EXISTS `u_funktion2stelle` (
  `funktion_id` int(11) NOT NULL DEFAULT '0',
  `stelle_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`funktion_id`,`stelle_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_funktionen`
--

CREATE TABLE IF NOT EXISTS `u_funktionen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bezeichnung` varchar(255) NOT NULL DEFAULT '',
  `link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_groups`
--

CREATE TABLE IF NOT EXISTS `u_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Gruppenname` varchar(255) NOT NULL,
  `Gruppenname_low-german` varchar(100) DEFAULT NULL,
  `Gruppenname_english` varchar(100) DEFAULT NULL,
  `Gruppenname_polish` varchar(100) DEFAULT NULL,
  `Gruppenname_vietnamese` varchar(100) DEFAULT NULL,
  `obergruppe` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_groups2rolle`
--

CREATE TABLE IF NOT EXISTS `u_groups2rolle` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`user_id`,`stelle_id`,`id`),
  KEY `user_id` (`user_id`),
  KEY `user_id_2` (`user_id`),
  KEY `user_id_3` (`user_id`),
	CONSTRAINT `u_groups2rolle_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_labels2classes`
--

CREATE TABLE IF NOT EXISTS `u_labels2classes` (
  `class_id` int(11) NOT NULL DEFAULT '0',
  `label_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`class_id`,`label_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_menue2rolle`
--

CREATE TABLE IF NOT EXISTS `u_menue2rolle` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `menue_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`user_id`,`stelle_id`,`menue_id`),
	CONSTRAINT `u_menue2rolle_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_menues`
--

CREATE TABLE IF NOT EXISTS `u_menues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `name_low-german` varchar(100) DEFAULT NULL,
  `name_english` varchar(100) DEFAULT NULL,
  `name_polish` varchar(100) DEFAULT NULL,
  `name_vietnamese` varchar(100) DEFAULT NULL,
  `links` varchar(2000) DEFAULT NULL,
  `onclick` text COMMENT 'JavaScript welches beim Klick auf den Menüpunkt ausgeführt werden soll.',
  `obermenue` int(11) NOT NULL DEFAULT '0',
  `menueebene` tinyint(4) NOT NULL DEFAULT '1',
  `target` varchar(10) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `title` text,
  `button_class` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `u_menue2stelle`
--

CREATE TABLE IF NOT EXISTS `u_menue2stelle` (
  `stelle_id` int(11) NOT NULL DEFAULT '0',
  `menue_id` int(11) NOT NULL DEFAULT '0',
  `menue_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`stelle_id`,`menue_id`),
  KEY `menue_id` (`menue_id`),
	CONSTRAINT `u_menue2stelle_ibfk_1` FOREIGN KEY (`stelle_id`) REFERENCES `stelle` (`ID`) ON DELETE CASCADE,
  CONSTRAINT `u_menue2stelle_ibfk_2` FOREIGN KEY (`menue_id`) REFERENCES `u_menues` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


-- --------------------------------------------------------

--
-- Table structure for table `u_rolle2used_class`
--

CREATE TABLE IF NOT EXISTS `u_rolle2used_class` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `stelle_id` int(11) NOT NULL DEFAULT '0',
  `class_id` int(11) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`stelle_id`,`class_id`),
	CONSTRAINT `u_rolle2used_class_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_rolle2used_layer`
--

CREATE TABLE IF NOT EXISTS `u_rolle2used_layer` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `stelle_id` int(11) NOT NULL DEFAULT '0',
  `layer_id` int(11) NOT NULL DEFAULT '0',
  `aktivStatus` enum('0','1','2') NOT NULL DEFAULT '0',
  `queryStatus` enum('0','1','2') NOT NULL DEFAULT '0',
  `gle_view` tinyint(1) NOT NULL DEFAULT '1',
  `showclasses` tinyint(1) NOT NULL DEFAULT '1',
  `logconsume` enum('0','1') NOT NULL DEFAULT '0',
  `transparency` tinyint(3) DEFAULT NULL,
  `drawingorder` int(11) DEFAULT NULL,
  `labelitem` varchar(100) DEFAULT NULL,
  `geom_from_layer` int(11) NOT NULL,
  `rollenfilter` text,
  PRIMARY KEY (`user_id`,`stelle_id`,`layer_id`),
	CONSTRAINT `u_rolle2used_layer_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `u_styles2classes`
--

CREATE TABLE IF NOT EXISTS `u_styles2classes` (
  `class_id` int(11) NOT NULL DEFAULT '0',
  `style_id` int(11) NOT NULL DEFAULT '0',
  `drawingorder` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`class_id`,`style_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `zwischenablage`
--

CREATE TABLE IF NOT EXISTS `zwischenablage` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `layer_id` int(11) NOT NULL,
  `oid` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`,`stelle_id`,`layer_id`,`oid`),
	CONSTRAINT `zwischenablage_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



COMMIT;
