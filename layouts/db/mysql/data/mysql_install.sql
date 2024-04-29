-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Erstellungszeit: 14. Jan 2024 um 17:18
-- Server-Version: 10.7.1-MariaDB-1:10.7.1+maria~focal
-- PHP-Version: 7.4.27

--
-- Datenbank: `kvwmapdb`
--
BEGIN;
-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `belated_files`
--

CREATE TABLE `belated_files` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `layer_id` int(11) NOT NULL,
  `dataset_id` int(11) NOT NULL,
  `attribute_name` varchar(70) NOT NULL,
  `name` varchar(150) NOT NULL,
  `size` int(11) NOT NULL,
  `lastmodified` bigint(20) NOT NULL,
  `file` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `classes`
--

CREATE TABLE `classes` (
  `Class_ID` int(11) NOT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `Name_low-german` varchar(100) DEFAULT NULL,
  `Name_english` varchar(100) DEFAULT NULL,
  `Name_polish` varchar(100) DEFAULT NULL,
  `Name_vietnamese` varchar(100) DEFAULT NULL,
  `Layer_ID` int(11) NOT NULL DEFAULT 0,
  `Expression` mediumtext DEFAULT NULL,
  `drawingorder` int(11) UNSIGNED DEFAULT NULL,
  `legendorder` int(11) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `classification` varchar(50) DEFAULT NULL,
  `legendgraphic` varchar(255) DEFAULT NULL,
  `legendimagewidth` int(11) DEFAULT NULL,
  `legendimageheight` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 PACK_KEYS=1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `colors`
--

CREATE TABLE `colors` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `red` smallint(3) NOT NULL DEFAULT 0,
  `green` smallint(3) NOT NULL DEFAULT 0,
  `blue` smallint(3) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Daten für Tabelle `colors`
--

INSERT INTO `colors` (`id`, `name`, `red`, `green`, `blue`) VALUES
(1, NULL, 166, 206, 227),
(2, NULL, 31, 120, 180),
(3, NULL, 178, 223, 138),
(4, NULL, 51, 160, 44),
(5, NULL, 251, 154, 153),
(6, NULL, 227, 26, 28),
(7, NULL, 253, 191, 111),
(8, NULL, 255, 127, 0),
(9, NULL, 202, 178, 214),
(10, NULL, 106, 61, 154),
(11, NULL, 0, 0, 0),
(12, NULL, 122, 12, 45);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `prefix` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `description` text DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `group` varchar(50) NOT NULL,
  `plugin` varchar(50) DEFAULT NULL,
  `saved` tinyint(1) NOT NULL,
  `editable` int(11) DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Daten für Tabelle `config`
--

INSERT INTO `config` (`id`, `name`, `prefix`, `value`, `description`, `type`, `group`, `plugin`, `saved`, `editable`) VALUES
(10, 'HEADER', 'SNIPPETS', 'header.php', '', 'string', 'Layout', '', 1, 3),
(11, 'FOOTER', 'SNIPPETS', 'footer.php', '', 'string', 'Layout', '', 1, 3),
(12, 'LOGIN', 'SNIPPETS', 'login.php', 'login.php\r\n', 'string', 'Layout', '', 1, 3),
(13, 'LAYER_ERROR_PAGE', 'SNIPPETS', 'layer_error_page.php', 'Seite zur Fehlerbehandlung, die durch fehlerhafte Layer verursacht werden; unterhalb von /snippets\r\n', 'string', 'Layout', '', 1, 3),
(14, 'AGREEMENT_MESSAGE', 'CUSTOM_PATH', '', 'Seite mit der Datenschutzerklärung, die einmalig beim Login angezeigt wird\r\nz.B. custom/ds_gvo.htm', 'string', 'Layout', '', 1, 2),
(15, 'CUSTOM_STYLE', 'CUSTOM_PATH', '', 'hier kann eine eigene css-Datei angegeben werden\r\n', 'string', 'Layout', '', 1, 2),
(16, 'ZOOM2COORD_STYLE_ID', '', '3244', 'hier können eigene Styles für den Koordinatenzoom und Punktzoom definiert werden\r\n', 'string', 'Layout', '', 1, 2),
(17, 'ZOOM2POINT_STYLE_ID', '', '3244', '', 'string', 'Layout', '', 1, 2),
(18, 'GLEVIEW', '', '2', 'Schalter für eine zeilen- oder spaltenweise Darstellung der Attribute im generischen Layereditor  # Version 1.6.5\r\n', 'numeric', 'Layout', '', 1, 2),
(19, 'sizes', '', '{\r\n    \"layouts/gui.php\": {\r\n        \"margin\": {\r\n            \"width\": 10,\r\n            \"height\": 10\r\n        },\r\n        \"header\": {\r\n            \"height\": 25\r\n        },\r\n        \"scale_bar\": {\r\n            \"height\": 30\r\n        },\r\n        \"lagebezeichnung_bar\": {\r\n            \"height\": 30\r\n        },\r\n        \"map_functions_bar\": {\r\n            \"height\": 36\r\n        },\r\n        \"footer\": {\r\n            \"height\": 20\r\n        },\r\n        \"menue\": {\r\n            \"width\": 240,\r\n            \"hide_width\": 22\r\n        },\r\n        \"legend\": {\r\n            \"width\": 250,\r\n            \"hide_width\": 27\r\n        }\r\n    },\r\n    \"gui_button.php\": {\r\n        \"margin\": {\r\n            \"width\": 10,\r\n            \"height\": 22\r\n        },\r\n        \"header\": {\r\n            \"height\": 25\r\n        },\r\n        \"footer\": {\r\n            \"height\": 107\r\n        },\r\n        \"menue\": {\r\n            \"width\": 209\r\n        },\r\n        \"legend\": {\r\n            \"width\": 250\r\n        }\r\n    }\r\n}', 'Höhen und Breiten von Browser, Rand, Header, Footer, Menü und Legende																# Version 2.7\r\n', 'array', 'Layout', '', 1, 2),
(20, 'LEGEND_GRAPHIC_FILE', '', '', 'zusätzliche Legende; muss unterhalb von snippets liegen\r\n', 'string', 'Layout', '', 1, 2),
(21, 'legendicon_size', '', '{\r\n    \"width\": [\r\n        18,\r\n        18,\r\n        18,\r\n        18\r\n    ],\r\n    \"height\": [\r\n        18,\r\n        12,\r\n        12,\r\n        18\r\n    ]\r\n}', 'Höhe und Breite der generierten Legendenbilder für verschiedene Layertypen\r\n-> Punktlayer\r\n-> Linienlayer\r\n-> Flächenlayer\r\n-> Rasterlayer\r\n', 'array', 'Layout', '', 1, 2),
(22, 'PREVIEW_IMAGE_WIDTH', '', '800', 'Vorschaubildgröße\r\n', 'numeric', 'Layout', '', 1, 2),
(23, 'TITLE', '', 'WebGIS kvwmap', 'Titel, welcher im Browser angezeigt wird\r\n', 'string', 'Layout', '', 1, 2),
(24, 'MENU_WAPPEN', '', 'kein', 'Position des Wappens (oben/unten/kein)\r\n', 'string', 'Layout', '', 1, 2),
(25, 'MENU_REFMAP', '', 'unten', 'Position der Referenzkarte (oben/unten)                   # Version 1.6.4\r\n', 'string', 'Layout', '', 1, 2),
(26, 'BG_TR', '', 'lightsteelblue', 'Hintergrundfarbe Zeile bei Listen\r\n', 'string', 'Layout', '', 1, 2),
(27, 'BG_MENUETOP', '', '#DAE4EC', 'Hintergrundfarbe Top-Menüzeilen\r\n', 'string', 'Layout', '', 1, 2),
(28, 'BG_MENUESUB', '', '#EDEFEF', 'Hintergrundfarbe Sub-Menüzeilen\r\n', 'string', 'Layout', '', 1, 2),
(29, 'BG_DEFAULT', '', 'lightsteelblue', 'Hintergrundfarbe (Kopf-/Fusszeile)\r\n', 'string', 'Layout', '', 1, 2),
(30, 'BG_FORM', '', 'lightsteelblue', 'Hintergrundfarbe (Eingabeformulare)\r\n', 'string', 'Layout', '', 1, 2),
(31, 'BG_FORMFAIL', '', 'lightpink', 'Hintergrundfarbe (Formularfehler)\r\n', 'string', 'Layout', '', 1, 2),
(32, 'BG_GLEHEADER', '', 'lightsteelblue', 'Hintergrundfarbe GLE Datensatzheader\r\n', 'string', 'Layout', '', 1, 2),
(33, 'TXT_GLEHEADER', '', '#000000', 'Schriftfarbe GLE Datensatzheader\r\n', 'string', 'Layout', '', 1, 2),
(34, 'BG_GLEATTRIBUTE', '', '#DAE4EC', 'Hintergrundfarbe GLE Attributnamen\r\n', 'string', 'Layout', '', 1, 2),
(35, 'POSTGRESVERSION', '', '1520', 'PostgreSQL Server Version                         # Version 1.6.4\r\n', 'string', 'Administration', '', 1, 2),
(36, 'MYSQLVERSION', '', '550', 'MySQLSQL Server Version                         # Version 1.6.4\r\n', 'string', 'Administration', '', 1, 2),
(37, 'MAPSERVERVERSION', '', '761', 'Mapserver Version                             # Version 1.6.8\r\n', 'string', 'Administration', '', 1, 2),
(38, 'PHPVERSION', '', '730', 'PHP-Version\r\n', 'string', 'Administration', '', 1, 2),
(39, 'MYSQL_CHARSET', '', 'UTF8', 'Character Set der MySQL-Datenbank\r\n', 'string', 'Administration', '', 1, 2),
(40, 'POSTGRES_CHARSET', '', 'UTF8', '', 'string', 'Administration', '', 1, 2),
(41, 'PUBLISHERNAME', '', 'WebGIS INROS', 'Bezeichung des Datenproviders\r\n', 'string', 'Administration', '', 1, 2),
(42, 'CHECK_CLIENT_IP', '', 'true', 'Erweiterung der Authentifizierung um die IP Adresse des Nutzers\r\nTestet ob die IP des anfragenden Clientrechners dem Nutzer zugeordnet ist\r\n', 'boolean', 'Administration', '', 1, 2),
(43, 'PASSWORD_MAXLENGTH', '', '25', 'maximale Länge der Passwörter\r\n', 'numeric', 'Administration', '', 1, 2),
(44, 'PASSWORD_MINLENGTH', '', '12', 'minimale Länge der Passwörter\r\n', 'numeric', 'Administration', '', 1, 2),
(45, 'PASSWORD_CHECK', '', '01010', 'Prüfung neues Passwort\r\nAuskommentiert, wenn das Passwort vom Admin auf \"unendlichen\" Zeitraum vergeben wird\r\nerste Stelle  0 = Prüft die Stärke des Passworts (3 von 4 Kriterien müssen erfüllt sein) - die weiteren Stellen werden ignoriert\r\nerste Stelle  1 = Prüft statt Stärke die nachfolgenden Kriterien:\r\nzweite Stelle 1 = Es müssen Kleinbuchstaben enthalten sein\r\ndritte Stelle 1 = Es müssen Großbuchstaben enthalten sein\r\nvierte Stelle 1 = Es müssen Zahlen enthalten sein\r\nfünfte Stelle 1 = Es müssen Sonderzeichen enthalten sein\r\n', 'string', 'Administration', '', 1, 2),
(46, 'GIT_USER', '', 'gisadmin', 'Wenn das kvwmap-Verzeichnis ein git-Repository ist, kann diese Konstante auf den User gesetzt werden, der das Repository angelegt hat.\r\nDamit der Apache-User dann die git-Befehle als dieser User ausführen kann, muss man als root über den Befehl \"visudo\" die /etc/sudoers editieren.\r\nDort muss dann eine Zeile in dieser Form hinzugefügt werden: \r\nwww-data        ALL=(fgs) NOPASSWD: /usr/bin/git\r\nDann kann man die Aktualität des Quellcodes in der Administrationsoberfläche überprüfen und ihn aktualisieren.\r\n', 'string', 'Administration', '', 1, 2),
(47, 'MAXQUERYROWS', '', '100', 'maximale Anzahl der in einer Sachdatenabfrage zurückgelieferten Zeilen.\r\n', 'numeric', 'Administration', '', 1, 2),
(48, 'ALWAYS_DRAW', '', 'true', 'definiert, ob der Polygoneditor nach einem Neuladen\r\nder Seite immer in den Modus \"Polygon zeichnen\" wechselt\r\n', 'boolean', 'Administration', '', 1, 2),
(49, 'EARTH_RADIUS', '', '6384000', 'Parameter für die Strecken- und Flächenreduktion\r\n', 'numeric', 'Administration', '', 1, 2),
(50, 'admin_stellen', '', '[\r\n    1,54\r\n]', 'Adminstellen\r\n', 'array', 'Administration', '', 1, 2),
(51, 'gast_stellen', '', '[\r\n  \r\n]', 'Gast-Stellen\r\n', 'array', 'Administration', '', 1, 2),
(52, 'selectable_limits', '', '[\r\n    10,\r\n    25,\r\n    50,\r\n    100,\r\n    200\r\n]', 'auswählbare Treffermengen\r\n', 'array', 'Administration', '', 1, 2),
(53, 'selectable_scales', '', '[\r\n    500,\r\n    1000,\r\n    2500,\r\n    5000,\r\n    7500,\r\n    10000,\r\n    25000,\r\n    50000,\r\n    100000,\r\n    250000,\r\n    500000,\r\n    1000000\r\n]', 'auswählbare Maßstäbe\r\n', 'array', 'Administration', '', 1, 2),
(54, 'supportedSRIDs', '', '[\r\n    4326,\r\n    2397,\r\n    2398,\r\n    2399,\r\n    3857,\r\n    5650,\r\n    31466,\r\n    31467,\r\n    31468,\r\n    31469,\r\n    32648,\r\n    25832,\r\n    25833,\r\n    35833,\r\n    32633,\r\n    325833,\r\n    15833,\r\n    900913,\r\n    28992\r\n]', 'Unterstützte SRIDs, nur diese stehen zur Auswahl bei der Stellenwahl\r\n', 'array', 'Administration', '', 1, 2),
(55, 'supportedLanguages', '', '[\r\n    \"german\",\r\n  \"english\"\r\n]', 'Unterstützte Sprachen, nur diese stehen zur Auswahl bei der Stellenwahl (\'german\', \'low-german\', \'english\', \'polish\', \'vietnamese\')\r\n', 'array', 'Administration', '', 1, 2),
(56, 'supportedExportFormats', '', '[\r\n    \"Shape\",\r\n    \"GML\",\r\n    \"KML\",\r\n    \"GeoJSON\",\r\n    \"UKO\",\r\n    \"OVL\",\r\n    \"CSV\"\r\n]', 'Unterstützte Exportformate\r\n', 'array', 'Administration', '', 1, 2),
(57, 'MAPFACTOR', '', '3', 'Faktor für die Einstellung der Druckqualität (MAPFACTOR * 72 dpi)     # Version 1.6.0\r\n', 'numeric', 'Administration', '', 1, 2),
(58, 'DEFAULT_DRUCKRAHMEN_ID', '', '42', 'Standarddrucklayout für den schnellen Kartendruck						# Version 1.7.4\r\n', 'numeric', 'Administration', '', 1, 2),
(59, 'MAXUPLOADSIZE', '', '200', 'maximale Datenmenge in MB, die beim Datenimport hochgeladen werden darf\r\n', 'numeric', 'Administration', '', 1, 2),
(60, 'MINSCALE', '', '1', 'Minmale Maßstabszahl\r\n', 'numeric', 'Administration', '', 1, 2),
(61, 'COORD_ZOOM_SCALE', '', '50000', 'Maßstab ab dem bei einem Koordinatensprung auch gezoomt wird\r\n', 'numeric', 'Administration', '', 1, 2),
(62, 'ZOOMBUFFER', '', '100', 'Puffer in der Einheit (ZOOMUNIT) der beim Zoom auf ein Objekt hinzugegeben wird\r\n', 'numeric', 'Administration', '', 1, 2),
(63, 'ZOOMUNIT', '', 'meter', 'Einheit des Puffer der beim Zoom auf ein Objekt hinzugegeben wird\r\n\'meter\' oder \'scale\'\r\n', 'string', 'Administration', '', 1, 2),
(65, 'SHOW_MAP_IMAGE', '', 'true', 'Definiert, ob das aktuelle Kartenbild separat angezeigt werden darf oder nicht\r\n', 'boolean', 'Administration', '', 1, 2),
(66, 'kvwmap_plugins', '', '[\r\n]', '', 'array', 'Administration', '', 1, 2),
(67, 'INFO1', '', 'Prüfen Sie ob Ihr Datenbankmodell aktuell ist.', 'Festlegung von Fehlermeldungen und Hinweisen\r\n', 'string', 'Administration', '', 1, 2),
(68, 'APPLVERSION', '', 'kvwmap/', '', 'string', 'Pfadeinstellungen', '', 1, 2),
(69, 'INSTALLPATH', '', '/var/www/', 'Installationspfad\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
(70, 'WWWROOT', 'INSTALLPATH', 'apps/', '', 'string', 'Pfadeinstellungen', '', 1, 2),
(71, 'IMAGEPATH', 'INSTALLPATH', 'tmp/', 'Verzeichnis, in dem die temporären Bilder usw. abgelegt werden\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
(72, 'URL', '', 'https://dev.gdi-service.de/', '', 'string', 'Pfadeinstellungen', '', 1, 2),
(73, 'NBH_PATH', 'WWWROOT.APPLVERSION', 'tools/UTM33_NBH.lst', 'Datei mit den Nummerierungsbezirkshöhen\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
(74, 'MAPSERV_CGI_BIN', 'URL', 'cgi-bin/mapserv', '', 'string', 'Pfadeinstellungen', '', 1, 2),
(75, 'LOGPATH', 'INSTALLPATH', 'logs/kvwmap/', '', 'string', 'Pfadeinstellungen', '', 1, 2),
(76, 'SHAPEPATH', 'INSTALLPATH', 'data/', 'Shapepath [Pfad zum Shapefileverzeichnis]\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
(77, 'CUSTOM_SHAPE_SCHEMA', '', 'custom_shapes', 'ein extra Schema in der PG-DB, in der die Tabellen der Nutzer Shapes angelegt werden\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
(78, 'REFERENCEMAPPATH', 'SHAPEPATH', 'referencemaps/', '', 'string', 'Pfadeinstellungen', '', 1, 2),
(79, 'DRUCKRAHMEN_PATH', 'SHAPEPATH', 'druckrahmen/', 'Pfad zum Speichern der Kartendruck-Layouts\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
(80, 'THIRDPARTY_PATH', '', '../3rdparty/', '3rdparty Pfad\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
(81, 'FONTAWESOME_PATH', 'THIRDPARTY_PATH', 'font-awesome-4.6.3/', '', 'string', 'Pfadeinstellungen', '', 1, 2),
(82, 'JQUERY_PATH', 'THIRDPARTY_PATH', 'jQuery-3.6.0/', '', 'string', 'Pfadeinstellungen', '', 1, 2),
(83, 'BOOTSTRAP_PATH', 'THIRDPARTY_PATH', 'bootstrap-4.6.1/', '', 'string', 'Pfadeinstellungen', '', 1, 2),
(84, 'BOOTSTRAPTABLE_PATH', 'THIRDPARTY_PATH', 'bootstrap-table-1.20.2/', '', 'string', 'Pfadeinstellungen', '', 1, 2),
(85, 'PROJ4JS_PATH', 'THIRDPARTY_PATH', 'proj4js-2.4.3/', '', 'string', 'Pfadeinstellungen', '', 1, 2),
(86, 'POSTGRESBINPATH', '', '/usr/bin/', 'Bin-Pfad der Postgres-tools (shp2pgsql, pgsql2shp)\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
(87, 'OGR_BINPATH', '', '/usr/bin/', 'Bin-Pfad der OGR-tools (ogr2ogr, ogrinfo)\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
(88, 'ZIP_PATH', '', 'zip', 'Pfad zum Zip-Programm (unter Linux: \'zip -j\', unter Windows z.B. \'c:/programme/Zip/bin/zip.exe\')\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
(89, 'CUSTOM_IMAGE_PATH', 'SHAPEPATH', 'bilder/', 'Pfad für selbst gemachte Bilder\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
(90, 'CACHEPATH', 'INSTALLPATH', 'cache/', 'Cachespeicherort\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
(91, 'CACHETIME', '', '168', 'Cachezeit Nach welcher Zeit in Stunden sollen gecachte Dateien aktualisiert werden\r\nwird derzeit noch nicht berücksichtigt\r\n', 'numeric', 'Pfadeinstellungen', '', 1, 2),
(92, 'TEMPPATH_REL', '', '../tmp/', 'relative Pfadangabe zum Webverzeichnis mit temprären Dateien\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
(93, 'IMAGEURL', '', '/tmp/', 'Imageurl\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
(94, 'SYMBOLSET', 'WWWROOT.APPLVERSION', 'symbols/symbole.sym', 'Symbolset\r\n', 'string', 'Pfadeinstellungen', '', 1, 3),
(95, 'FONTSET', 'WWWROOT.APPLVERSION', 'fonts/fonts.txt', 'Fontset\r\n', 'string', 'Pfadeinstellungen', '', 1, 3),
(96, 'GRAPHICSPATH', '', 'graphics/', 'Graphics\r\n', 'string', 'Pfadeinstellungen', '', 1, 0),
(97, 'WAPPENPATH', 'CUSTOM_PATH', 'wappen/', 'Wappen\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
(98, 'LAYOUTPATH', 'WWWROOT.APPLVERSION', 'layouts/', 'Layouts\r\n', 'string', 'Pfadeinstellungen', '', 1, 0),
(99, 'SNIPPETS', 'LAYOUTPATH', 'snippets/', '', 'string', 'Pfadeinstellungen', '', 1, 0),
(100, 'CLASSPATH', 'WWWROOT.APPLVERSION', 'class/', '', 'string', 'Pfadeinstellungen', '', 1, 0),
(101, 'PLUGINS', 'WWWROOT.APPLVERSION', 'plugins/', '', 'string', 'Pfadeinstellungen', '', 1, 2),
(102, 'SYNC_PATH', 'SHAPEPATH', 'synchro/', 'Synchronisationsverzeichnis                         # Version 1.7.0\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
(103, 'IMAGEMAGICKPATH', '', '/usr/bin/', 'Pfad zum Imagemagick convert\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
(104, 'UPLOADPATH', 'SHAPEPATH', 'upload/', 'Pfad zum Ordner für Datei-Uploads\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
(105, 'DEFAULTMAPFILE', 'SHAPEPATH', 'mapfiles/defaultmapfile.map', 'Mapfile, mit dem das Mapobjekt gebildet wird\r\n', 'string', 'Pfadeinstellungen', '', 1, 2),
(106, 'REFMAPFILE', 'SHAPEPATH', 'mapfiles/refmapfile.map', '', 'string', 'Pfadeinstellungen', '', 1, 2),
(107, 'MAILMETHOD', '', 'sendmail', 'Methode zum Versenden von E-Mails. Mögliche Optionen:\r\nsendmail: E-Mails werden direkt mit sendmail versendet. (default)\r\nsendEmail async: E-Mails werden erst in einem temporären Verzeichnis MAILQUEUEPATH\r\nabgelegt und können später durch das Script tools/sendEmailAsync.sh\r\nversendet werden. Dort muss auch MAILQUEUEPATH eingestellt werden.\r\n', 'string', 'E-Mail Einstellungen', '', 1, 2),
(108, 'MAILSMTPSERVER', '', '', 'SMTP-Server, Muss nur angegeben werden, wenn Methode sendEmail async verwendet wird.\r\n', 'string', 'E-Mail Einstellungen', '', 1, 2),
(109, 'MAILSMTPPORT', '', '25', 'SMTP-Port, Muss nur angegeben werden, wenn Methode sendEmail async verwendet wird.\r\n', 'numeric', 'E-Mail Einstellungen', '', 1, 2),
(110, 'MAILQUEUEPATH', '', '/var/www/logs/kvwmap/mail_queue/', 'Verzeichnis für die JSON-Dateien mit denzu versendenen E-Mails.\r\nMuss nur angegeben werden, wenn Methode sendEmail async verwendet wird.\r\n', 'string', 'E-Mail Einstellungen', '', 1, 2),
(111, 'MAILARCHIVPATH', '', '/var/www/logs/kvwmap/mail_archiv/', '', 'string', 'E-Mail Einstellungen', '', 1, 2),
(112, 'LAYER_IDS_DOP', '', '', '', 'string', 'Layer-IDs', '', 1, 2),
(113, 'LAYER_ID_SCHNELLSPRUNG', '', 'NULL', '', 'numeric', 'Layer-IDs', '', 1, 2),
(114, 'quicksearch_layer_ids', '', '[\r\n \r\n]', '', 'array', 'Layer-IDs', '', 1, 2),
(115, 'DEBUGFILE', '', '_debug.htm', 'Ort der Datei, in der die Meldungen beim Debugen geschrieben werden\r\n', 'string', 'Logging', '', 1, 2),
(116, 'DEBUG_LEVEL', '', '1', 'Level der Fehlermeldungen beim debuggen\r\n3 nur Ausgaben die für Admin bestimmt sind\r\n2 nur Datenbankanfragen\r\n1 nur wichtige Fehlermeldungen\r\n5 keine Ausgaben\r\n', 'numeric', 'Logging', '', 1, 2),
(117, 'LOGFILE_MYSQL', 'LOGPATH', '_log_mysql.sql', 'mySQL-Log-Datei zur Speicherung der SQL-Statements              # Version 1.6.0\r\n', 'string', 'Logging', '', 1, 2),
(118, 'LOGFILE_POSTGRES', 'LOGPATH', '_log_postgres.sql', 'postgreSQL-Log-Datei zur Speicherung der SQL-Statements         # Version 1.6.0\r\n', 'string', 'Logging', '', 1, 2),
(119, 'LOGFILE_LOGIN', 'LOGPATH', 'login_fail.log', 'Log-Datei zur Speicherung der Login Vorgänge\r\n', 'string', 'Logging', '', 1, 2),
(120, 'LOG_LEVEL', '', '2', 'Log-Level zur Speicherung der SQL-Statements                    # Version 1.6.0\r\nLoglevel\r\n0 niemals loggen\r\n1 immer loggen\r\n2 nur loggen wenn loglevel in execSQL 1 ist.\r\n', 'numeric', 'Logging', '', 1, 2),
(121, 'SAVEMAPFILE', 'LOGPATH', 'save_mapfile.map', 'Wenn SAVEMAPFILE leer ist, wird sie nicht gespeichert.\r\nAchtung, wenn die cgi-bin/mapserv ohne Authentifizierung und der Pfad zu save_mapfile.map bekannt ist, kann jeder die Karten des letzten Aufrufs in kvwmap über mapserv?map=<pfad zu save_map.map abfragen. Und wenn wfs zugelassen ist auch die Sachdaten dazu runterladen. Diese Konstante sollte nur zu debug-Zwecken eingeschaltet bleiben.\r\n', 'string', 'Logging', '', 1, 2),
(122, 'DEFAULTDBWRITE', '', '1', 'Ermöglicht die Ausführung der SQL-Statements in der Datenbank zu unterdrücken.\r\nIn dem Fall werden die Statements nur in die Log-Datei geschrieben.\r\nDie Definition von DBWRITE ist umgezogen nach start.php, damit das Unterdrücken\r\ndes Schreiben in die Datenbank auch mit Formularwerten eingestellt werden kann.\r\ndas übernimmt in dem Falle die Formularvariable disableDbWrite.\r\nHier kann jedoch noch der Defaultwert gesetzt werden\r\n', 'numeric', 'Logging', '', 1, 2),
(123, 'LOG_CONSUME_ACTIVITY', '', '1', 'Einstellungen zur Speicherung der Zugriffe\r\n', 'numeric', 'Logging', '', 1, 2),
(124, 'POSTGRES_HOST', '', 'pgsql', '', 'string', 'Datenbanken', '', 1, 2),
(125, 'POSTGRES_USER', '', 'kvwmap', '', 'string', 'Datenbanken', '', 1, 2),
(126, 'POSTGRES_PASSWORD', '', '***********', '', 'password', 'Datenbanken', '', 1, 2),
(127, 'POSTGRES_DBNAME', '', 'kvwmapsp', '', 'string', 'Datenbanken', '', 1, 2),
(128, 'MAPFILENAME', '', 'kvwmap', '', 'string', 'OWS-METADATEN', '', 1, 2),
(129, 'WMS_MAPFILE_REL_PATH', '', 'ows/', 'Voreinstellungen für Metadaten zu Web Map Services (WMS-Server)\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
(130, 'WMS_MAPFILE_PATH', 'INSTALLPATH.WMS_MAPFILE_REL_PATH', 'mapfiles/', '', 'string', 'OWS-METADATEN', '', 1, 3),
(131, 'SUPORTED_WMS_VERSION', '', '1.3.0', '', 'string', 'OWS-METADATEN', '', 1, 2),
(132, 'OWS_SCHEMAS_LOCATION', '', 'http://schemas.opengeospatial.net', 'Metadaten zur Ausgabe im Capabilities Dokument gelten für WMS, WFS und WCS\r\nsets base URL for OGC Schemas so the root element in the\r\nCapabilities document points to the correct schema location\r\nto produce valid XML\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
(133, 'OWS_TITLE', '', 'MapServer kvwmap', 'An Stelle von WMS_TITLE\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
(134, 'OWS_ABSTRACT', '', 'Kartenserver', 'An Stelle von WMS_Abstract\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
(135, 'OWS_KEYWORDLIST', '', 'GIS,Landkreis,Kataster,Geoinformation', 'WMT_MS_Capabilities/Service/KeywordList/Keyword[]\r\nWFS_Capabilities/Service/Keywords\r\nWCS_Capabilities/Service/keywords/keyword[]\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
(136, 'OWS_SERVICE_ONLINERESOURCE', 'URL.APPLVERSION', 'index.php?go=OWS', 'WMT_MS_Capabilities/Service/OnlineResource\r\nWFS_Capabilities/Service/OnlineResource\r\nWCS_Capabilities/Service/responsibleParty/onlineResource/@xlink:href\r\n', 'string', 'OWS-METADATEN', '', 1, 3),
(137, 'OWS_FEES', '', 'zu Testzwecken frei', 'An Stelle WMS_FEES\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
(138, 'OWS_ACCESSCONSTRAINTS', '', 'keine', 'WMT_MS_Capabilities/Service/AccessConstraints\r\nWFS_Capabilities/Service/AccessConstraints\r\nWCS_Capabilities/Service/accessConstraints\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
(139, 'OWS_CONTACTPERSON', '', 'Stefan Rahn', 'An Stelle von WMS_CONTACTPERSON\r\nWMT_MS_Capabilities/Service/ContactInformation/ContactPersonPrimary/ContactPerson\r\nWCS_Capabilities/Service/responsibleParty/individualName\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
(140, 'OWS_CONTACTORGANIZATION', '', 'GDI-Service', 'An Stelle von WMS_CONTACTORGANIZATION\r\nWMT_MS_Capabilities/Service/ContactInformation/ContactPersonPrimary/ContactOrganization\r\nWCS_Capabilities/Service/responsibleParty/organisationName\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
(141, 'OWS_CONTACTPOSITION', '', 'Softwareentwickler', 'An Stelle von WMS_CONTACTPOSITION\r\nWMT_MS_Capabilities/Service/ContactInformation/ContactPosition\r\nWCS_Capabilities/Service/responsibleParty/positionName\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
(142, 'OWS_ADDRESSTYPE', '', 'postal', 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/AddressType\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
(143, 'OWS_ADDRESS', '', 'Friedrichstr. 16', 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/Address\r\nWCS_Capabilities/Service/contactInfo/address/deliveryPoint\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
(144, 'OWS_CITY', '', 'Rostock', 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/City\r\nWCS_Capabilities/Service/contactInfo/address/city\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
(145, 'OWS_STATEORPROVINCE', '', 'Mecklenburg-Vorpommern', 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/StateOrProvince\r\nWCS_Capabilities/Service/contactInfo/address/administrativeArea\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
(146, 'OWS_POSTCODE', '', '18059', 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/PostCode\r\nWCS_Capabilities/Service/contactInfo/address/postalCode\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
(147, 'OWS_COUNTRY', '', 'Deutschland', 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/Country\r\nWCS_Capabilities/Service/contactInfo/address/country\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
(148, 'OWS_CONTACTVOICETELEPHONE', '', '0049-381-403 44445', 'WMT_MS_Capabilities/Service/ContactInformation/ContactVoiceTelephone\r\nWCS_Capabilities/Service/contactInfo/phone/voice\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
(149, 'OWS_CONTACTFACSIMILETELEPHONE', '', '+49 381-3378-9527', 'WMT_MS_Capabilities/Service/ContactInformation/ContactFacsimileTelephone\r\nWCS_Capabilities/Service/contactInfo/phone/facsimile\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
(150, 'OWS_CONTACTELECTRONICMAILADDRESS', '', 'stefan.rahn@gdi-service.de', 'An Stelle von WMS_CONTACTELECTRONICMAILADDRESS\r\nWMT_MS_Capabilities/Service/ContactInformation/ContactElectronicMailAddress\r\nWCS_Capabilities/Service/contactInfo/address/eletronicMailAddress\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
(151, 'OWS_SRS', '', 'EPSG:25832 EPSG:25833 EPSG:5650 EPSG:4326', 'An Stelle von WMS_SRS\r\nWMT_MS_Capabilities/Capability/Layer/SRS\r\nWMT_MS_Capabilities/Capability/Layer/Layer[*]/SRS\r\nWFS_Capabilities/FeatureTypeList/FeatureType[*]/SRS\r\nunless differently defined in LAYER object\r\nif you are setting > 1 SRS for WMS, you need to define \"wms_srs\" and \"wfs_srs\"\r\nseperately because OGC:WFS only accepts one OUTPUT SRS\r\n', 'string', 'OWS-METADATEN', '', 1, 2),
(152, 'WFS_SRS', '', 'EPSG:25833', '', 'string', 'OWS-METADATEN', '', 1, 2),
(153, 'METADATA_AUTH_LINK', '', '', 'URL zum Authentifizieren am CSW-Metadatensystem\r\n', 'string', 'z CSW-Metadatensystem', '', 1, 2),
(154, 'METADATA_ONLINE_RESOURCE', '', '', 'URL zum CSW-Server\r\n', 'string', 'z CSW-Metadatensystem', '', 1, 2),
(155, 'METADATA_EDIT_LINK', '', '', 'URL zum Editieren von Metadaten im CSW-Metadatensystem\r\n', 'string', 'z CSW-Metadatensystem', '', 1, 2),
(156, 'METADATA_EDIT_LINK', '', '', 'URL zum Editieren von Metadaten im CSW-Metadatensystem\r\n', 'string', 'z CSW-Metadatensystem', '', 1, 2),
(157, 'LOGIN_AGREEMENT', 'SNIPPETS', 'login_agreement.php', 'PHP-Seite, welche die Agreement-Message anzeigt', 'string', 'Layout', NULL, 1, 3),
(158, 'LOGIN_NEW_PASSWORD', 'SNIPPETS', 'login_new_password.php', 'PHP-Seite, auf der man ein neues Passwort vergeben kann', 'string', 'Layout', NULL, 1, 3),
(159, 'LOGIN_REGISTRATION', 'SNIPPETS', 'login_registration.php', 'PHP-Seite, auf der man sich registrieren kann', 'string', 'Layout', NULL, 1, 3),
(160, 'LOGIN_ROUTINE', 'CUSTOM_PATH', 'layouts/snippets/login_routine.php', 'hier kann eine PHP-Datei angegeben werden, welche beim Login-Vorgang ausgeführt wird', 'string', 'Administration', NULL, 1, 2),
(161, 'LOGOUT_ROUTINE', 'CUSTOM_PATH', 'layouts/snippets/logout_routine.php', 'hier kann eine PHP-Datei angegeben werden, welche beim Logout-Vorgang ausgeführt wird', 'string', 'Administration', NULL, 1, 2),
(162, 'USE_EXISTING_SESSION', '', 'false', 'Wenn man auf einem Server mehrere kvwmap-Instanzen laufen hat und möchte, dass ein Nutzer sich nur einmal an einer Instanz anmelden muss, kann man diesen Parameter auf true setzen. Voraussetzung ist natürlich, dass die kvwmap-Instanzen die gleichen Nutzerdaten verwenden.', 'boolean', 'Administration', NULL, 1, 2),
(163, 'OWS_HOURSOFSERVICE', '', 'Wochentags 8:00 - 16:00 Uhr', 'Metadatenelement zur Beschreibung von Webdiensten im Anwendungsfall go=OWS', 'string', 'OWS-METADATEN', NULL, 1, 2),
(164, 'OWS_CONTACTINSTRUCTIONS', '', 'Telefon oder E-Mail', 'Metadatenelement zur Beschreibung von Webdiensten im Anwendungsfall go=OWS', 'string', 'OWS-METADATEN', NULL, 1, 2),
(165, 'OWS_ROLE', '', 'GIS-Administrator', 'Metadatenelement zur Beschreibung von Webdiensten im Anwendungsfall go=OWS', 'string', 'OWS-METADATEN', NULL, 1, 2),
(166, 'CUSTOM_RASTER', 'SHAPEPATH', 'custom_raster/', 'Das Verzeichnis, in dem die von den Nutzern hochgeladenen Rasterdateien abgelegt werden.', 'string', 'Pfadeinstellungen', NULL, 1, 2),
(167, 'OGR_BINPATH_GDAL', '', '/usr/local/gdal/bin/', 'Wenn man dem ogr oder gdal Befehl docker exec gdal voranstellt, wird das ogr bzw. gdal in dem gdal Container verwendet statt des ogr bzw. gdal im Web Container. Diese Konstante gibt an wo sich das Bin-Verzeichnis innerhalb des verwendeten GDAL-Containers befindet.', 'string', 'Pfadeinstellungen', NULL, 1, 2),
(168, 'PASSWORD_INFO', '', '', 'Hier kann ein Hinweistext eingetragen werden, welcher bei der Passwortvergabe erscheint.', 'string', 'Administration', NULL, 1, 2),
(169, 'GEO_NAME_SEARCH_URL', '', 'https://nominatim.openstreetmap.org/search.php?format=geojson&q=', 'URL eines Geo-Namen-Such-Dienstes. Der Dienst muss GeoJSON zurückliefern.', 'string', 'Administration', NULL, 1, 2),
(170, 'GEO_NAME_SEARCH_PROPERTY', '', 'display_name', 'Das Attribut welches als Suchergebnis bei der Geo-Namen-Suche angezeigt werden soll.', 'string', 'Administration', NULL, 1, 2),
(171, 'CUSTOM_PATH', '', 'custom/', 'Pfad in dem sich Dateien befinden, die nicht vom kvwmap Repository getrackt werden.', 'string', 'Pfadeinstellungen', NULL, 1, 0),
(172, 'BG_IMAGE', 'GRAPHICSPATH', 'bg.gif', 'Hintergrundbild für die Oberfläche', 'string', 'Layout', NULL, 1, 3),
(173, 'ROLLENFILTER', '', 'false', 'Legt fest, ob Nutzer eigene Filter für Layer erstellen können.', 'boolean', 'Administration', NULL, 1, 2),
(174, 'NORMALIZE_AREA_THRESHOLD', '', '0.5', 'Maximale Flächengröße von Dreiecken, die durch 3 benachbarte Stützpunkte gebildet werden mit dem Winkel im mittleren Stützpunkt kleiner als NORMALIZE_ANGLE_THRESHOLD verwendet in der Einheit des verwendeten Koordinatensystems im Client. In der Regel Quadatmeter. Zentralpunkte, deren Flächen kleiner sind, werden bei der Differenzfunktion gelöscht und die Ergebnisgeometrie dadurch normalisiert. Default 0.5. Verwendet wird der Parameter in der Funktion gdi_normalize_geometry übernommen von Gaspare Sganga, siehe https://gasparesganga.com/labs/postgis-normalize-geometry/', 'double precision', 'Administration', NULL, 1, 2),
(175, 'NORMALIZE_ANGLE_THRESHOLD', '', '0.5', 'Maximale Winkelgröße im mittleren Stützpunkt von 3 benachbarten Stützpunkten, deren Fläche kleiner als NORMALIZE_AREA_THRESHOLD ist. Zentralpunkte in denen der Winkel kleiner ist werden bei der Differenzfunktion gelöscht und die Ergebnisgeometrie dadurch normalisiert. Angegeben in Dezimalgrad. Default 0.5 Grad.  Verwendet wird der Parameter in der Funktion gdi_normalize_geometry übernommen von Gaspare Sganga, siehe https://gasparesganga.com/labs/postgis-normalize-geometry/', 'double precision', 'Administration', NULL, 1, 2),
(176, 'NORMALIZE_POINT_DISTANCE_THRESHOLD', '', '0.005', 'Maximaler Abstand von benachbarten Punkten in einem Dreieck welches kleiner ist als NORMALIZE_AREA_THRESHOLD unter Berücksichtigung von NORMALIZE_ANGLE_THRESHOLD verwendet in der Einheit des verwendeten Koordinatensystems im Client. In der Regel Meter. Ein Punkt bei dem der Abstand zum anderen kleiner wird bei der Differenzfunktion gelöscht und die Ergebnisgeometrie dadurch normalisiert. Default 0.005. Verwendet wird der Parameter in der Funktion gdi_normalize_geometry übernommen von Gaspare Sganga, siehe https://gasparesganga.com/labs/postgis-normalize-geometry/', 'double precision', 'Administration', NULL, 1, 2),
(177, 'NORMALIZE_NULL_AREA', '', '0.0001', 'Maximale Flächengröße von Dreiecken, die durch 3 benachbarte Stützpunkte gebildet werden unabhängig von den Winkeln verwendet in der Einheit des verwendeten Koordinatensystems im Client. In der Regel Quadatmeter. Zentralpunkte, deren Flächen kleiner sind, werden bei der Differenzfunktion gelöscht und die Ergebnisgeometrie dadurch normalisiert. Default 0.0001. Verwendet wird der Parameter in der Funktion gdi_normalize_geometry übernommen von Gaspare Sganga, siehe https://gasparesganga.com/labs/postgis-normalize-geometry/', 'double precision', 'Administration', NULL, 1, 2),
(178, 'POSTGRES_CONNECTION_ID', '', '1', 'ID der Postgresql-Datenbankverbindung aus Tabelle connections', 'numeric', 'Datenbanken', NULL, 1, 2),
(179, 'MS_DEBUG_LEVEL', '', '0', 'Legt den Debug-Level für MapServer fest. Werte von 0 bis 5 sind möglich.', 'integer', 'Logging', NULL, 1, 2),
(180, 'MAILSMTPUSER', '', 'kvwmap', 'Nutzername für den Zugang zum SMTP-Server.\r\n', 'string', 'E-Mail Einstellungen', '', 1, 2),
(181, 'MAILSMTPPASSWORD', '', 'secret', 'Passwort für den Zugang zum SMTP-Server.\r\n', 'password', 'E-Mail Einstellungen', '', 1, 2),
(182, 'MAILREPLYADDRESS', '', 'no-reply@kvwmap.de', 'E-Mail-Adresse, die als Absender in von kvwmap versandten E-Mails angegeben werden soll.\r\n', 'string', 'E-Mail Einstellungen', '', 1, 2),
(183, 'MAILCOPYATTACHMENT', '', 'true', 'Sollen Dateien in E-Mail-Anhängen beim Versenden in den Archiv-Ordner kopiert (true) oder verschoben (false) werden.\r\n', 'string', 'E-Mail Einstellungen', '', 1, 2),
(184, 'IMPORT_POINT_STYLE_ID', '', '3128', 'Hier kann ein eigener Style für den Datenimport von Punkt-Objekten eingetragen werden.', 'integer', 'Layout', NULL, 1, 2),
(185, 'NUTZER_ARCHIVIEREN', '', 'false', 'Ist dieser Parameter auf true gesetzt, werden Nutzer nicht gelöscht sondern archiviert.', 'boolean', 'Administration', NULL, 1, 2),
(208, 'QUERY_ONLY_ACTIVE_CLASSES', '', 'true', 'Wenn aktiviert, dann werden bei der Kartenabfrage nur aktive Klassen berücksichtigt.', 'boolean', 'Administration', NULL, 1, 2),
(209, 'OVERRIDE_LANGUAGE_VARS', '', 'false', 'Wenn mit true aktiviert, werden Variablen mit Texten der unterschiedlichen Sprachen durch Variablen in gleichnamigen custom-Dateien überschrieben falls vorhanden.', 'boolean', 'Layout', NULL, 1, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `connections`
--

CREATE TABLE `connections` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'Eindeutige Id der Datenbankverbindungen',
  `name` varchar(150) NOT NULL DEFAULT 'kvwmapsp' COMMENT 'Name der Datenbankverbindung. Kann frei gewählt werden, muss eindeutig sein und Wird in Auswahlliste für Layer angezeigt.',
  `host` varchar(50) DEFAULT 'pgsql' COMMENT 'Hostname der Datenbank. Default ist pgsql wenn der Zugriff aus dem Web-Container heraus erfolgt, sonst auch die IP-Adresse oder Hostname des Datenbankservers oder Docker-Containers in dem der Server läuft. Kann auch als Befehl aufgeführt werden, z.b. $(docker inspect --format ''{{ .NetworkSettings.IPAddress }}'' mysql-server). Wird ein leer-String eingetragen wird vom Postgres-Client localhost verwendet.',
  `port` int(11) DEFAULT 5432 COMMENT 'Die Portnummer mit der die Verbindung zur Datenbank hergestellt werden soll. Default ist 5432. Wird ein leerer Text angegeben, verwendet der Datenbankclient 5432.',
  `dbname` varchar(150) NOT NULL DEFAULT 'kvwmapsp' COMMENT 'Der Name der Datenbank zu der die Verbindung hergestellt werden soll.',
  `user` varchar(150) DEFAULT 'kvwmap' COMMENT 'Der Name des Nutzers mit dem die Verbindung zur Datenbank hergestellt werden soll. Default ist kvwmap. Wird ein leerer Text angegeben verwendet der Datenbankclient den Namen des Nutzers des Betriebssystems, welcher den Datenbankclient aufruft.',
  `password` varchar(150) DEFAULT 'KvwMapPW1' COMMENT 'Das Passwort des Datenbanknutzers. Wird hier ein leerer Text angegeben, wird die Option für das Passwort im Datenbankclient weggelassen. Der Datenbankclient versucht dadurch, wenn ein Passwort erforderlich ist das Passwort aus der Umgebungsvariable PGPASSWORD auszulesen. Steht dort nichts drin, versucht der Client das Passwort aus der Datei, die in der Umgebungsvariable PGPASSFILE angegeben ist auszulesen. Ist das Passwort auch dort nicht zu finden, versucht der Client das Passwort aus der Datei ~/.pgpass auszulesen. Ist auch dort nichts passendes zu Host, Datenbankname, Port und Nutzer zu finden, kann keine Verbindung hergestellt werden.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Daten für Tabelle `connections`
--

INSERT INTO `connections` (`id`, `name`, `host`, `port`, `dbname`, `user`, `password`) VALUES
(1, 'kvwmapsp', 'pgsql', 5432, 'kvwmapsp', 'kvwmap', '************');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cron_jobs`
--

CREATE TABLE `cron_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bezeichnung` varchar(255) NOT NULL,
  `beschreibung` text NOT NULL,
  `time` varchar(25) NOT NULL DEFAULT '0 6 1 * *',
  `query` text DEFAULT NULL,
  `function` varchar(255) DEFAULT NULL,
  `url` varchar(1000) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `aktiv` tinyint(1) NOT NULL DEFAULT 0,
  `dbname` varchar(68) DEFAULT NULL,
  `user` enum('root','gisadmin') NOT NULL DEFAULT 'gisadmin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Daten für Tabelle `cron_jobs`
--

INSERT INTO `cron_jobs` (`id`, `bezeichnung`, `beschreibung`, `time`, `query`, `function`, `url`, `user_id`, `stelle_id`, `aktiv`, `dbname`, `user`) VALUES
(1, 'Lösche MapServer tmp Dateien', 'Löscht jeden Tag Dateien die älter als 1 Tag sind aus Verzeichnis /var/www/tmp', '1 1 * * *', '', 'find /var/www/tmp -mtime +1 ! -path /var/www/tmp -exec rm -rf {} +', NULL, 0, 0, 1, '', 'gisadmin'),
(2, 'Lösche Gastnutzer', 'Jeden Tag 01:01', '1 1 * * *', NULL, '/var/www/apps/kvwmap_intern/tools/deleteGastUser.sh', NULL, 2, 54, 1, NULL, 'gisadmin');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `datasources`
--

CREATE TABLE `datasources` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `beschreibung` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `datatypes`
--

CREATE TABLE `datatypes` (
  `id` int(11) NOT NULL,
  `name` varchar(58) DEFAULT NULL,
  `schema` varchar(58) NOT NULL DEFAULT 'public',
  `connection_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `datatype_attributes`
--

CREATE TABLE `datatype_attributes` (
  `layer_id` int(11) NOT NULL,
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
  `form_element_type` enum('Text','Textfeld','Auswahlfeld','Checkbox','Geometrie','SubFormPK','SubFormFK','SubFormEmbeddedPK','Time','Dokument','Link','User','Stelle','Fl?che','dynamicLink','Zahl','UserID','L?nge','mailto') NOT NULL DEFAULT 'Text',
  `options` mediumtext DEFAULT NULL,
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
  `privileg` tinyint(1) DEFAULT 0,
  `query_tooltip` tinyint(1) DEFAULT 0,
  `visible` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Zeigt oder versteckt Attribut im Layereditor (default: Zeigen).',
  `vcheck_attribute` varchar(255) DEFAULT NULL,
  `vcheck_operator` varchar(4) DEFAULT NULL,
  `vcheck_value` mediumtext DEFAULT NULL,
  `arrangement` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Zeigt Attribut unter oder neben dem vorgehenden Attribut (default: darunter).',
  `labeling` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Zeigt Beschriftung gar nicht, über oder links neben dem Attributwert (default: links daneben).'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `datatype_attributes2stelle`
--

CREATE TABLE `datatype_attributes2stelle` (
  `datatype_id` int(11) NOT NULL,
  `attributename` varchar(255) COLLATE latin1_german2_ci NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `privileg` tinyint(1) NOT NULL,
  `tooltip` tinyint(1) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `datendrucklayouts`
--

CREATE TABLE `datendrucklayouts` (
  `id` int(11) NOT NULL,
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
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `margin_top` int(11) NOT NULL DEFAULT 40,
  `margin_bottom` int(11) NOT NULL DEFAULT 30,
  `margin_left` int(11) NOT NULL DEFAULT 0,
  `margin_right` int(11) NOT NULL DEFAULT 0,
  `dont_print_empty` tinyint(1) DEFAULT NULL,
  `gap` int(11) NOT NULL DEFAULT 20,
  `no_record_splitting` tinyint(1) NOT NULL DEFAULT 0,
  `columns` tinyint(4) NOT NULL DEFAULT 0,
  `filename` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ddl2freilinien`
--

CREATE TABLE `ddl2freilinien` (
  `ddl_id` int(11) NOT NULL,
  `line_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ddl2freirechtecke`
--

CREATE TABLE `ddl2freirechtecke` (
  `ddl_id` int(11) NOT NULL,
  `rect_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ddl2freitexte`
--

CREATE TABLE `ddl2freitexte` (
  `ddl_id` int(11) NOT NULL,
  `freitext_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ddl2stelle`
--

CREATE TABLE `ddl2stelle` (
  `stelle_id` int(11) NOT NULL,
  `ddl_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ddl_colors`
--

CREATE TABLE `ddl_colors` (
  `id` int(11) NOT NULL,
  `red` smallint(3) NOT NULL DEFAULT 0,
  `green` smallint(3) NOT NULL DEFAULT 0,
  `blue` smallint(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Daten für Tabelle `ddl_colors`
--

INSERT INTO `ddl_colors` (`id`, `red`, `green`, `blue`) VALUES
(1, 200, 200, 200),
(2, 215, 215, 215),
(3, 230, 230, 230),
(4, 181, 217, 255),
(5, 218, 255, 149),
(6, 255, 203, 172);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ddl_elemente`
--

CREATE TABLE `ddl_elemente` (
  `ddl_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `xpos` double DEFAULT NULL,
  `ypos` double DEFAULT NULL,
  `offset_attribute` varchar(255) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `border` tinyint(1) DEFAULT NULL,
  `font` varchar(255) DEFAULT NULL,
  `fontsize` int(11) DEFAULT NULL,
  `label` text DEFAULT NULL,
  `margin` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `druckausschnitte`
--

CREATE TABLE `druckausschnitte` (
  `stelle_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `epsg_code` int(6) DEFAULT NULL,
  `center_x` float NOT NULL,
  `center_y` float NOT NULL,
  `print_scale` int(11) NOT NULL,
  `angle` int(11) NOT NULL,
  `frame_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `druckfreibilder`
--

CREATE TABLE `druckfreibilder` (
  `id` int(11) NOT NULL,
  `src` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `druckfreilinien`
--

CREATE TABLE `druckfreilinien` (
  `id` int(11) NOT NULL,
  `posx` int(11) NOT NULL,
  `posy` int(11) NOT NULL,
  `endposx` int(11) NOT NULL,
  `endposy` int(11) NOT NULL,
  `breite` float NOT NULL,
  `offset_attribute_start` varchar(255) DEFAULT NULL,
  `offset_attribute_end` varchar(255) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `druckfreirechtecke`
--

CREATE TABLE `druckfreirechtecke` (
  `id` int(11) NOT NULL,
  `posx` int(11) NOT NULL,
  `posy` int(11) NOT NULL,
  `endposx` int(11) NOT NULL,
  `endposy` int(11) NOT NULL,
  `breite` float NOT NULL,
  `color` int(11) DEFAULT NULL,
  `offset_attribute_start` varchar(255) DEFAULT NULL,
  `offset_attribute_end` varchar(255) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `druckfreitexte`
--

CREATE TABLE `druckfreitexte` (
  `id` int(11) NOT NULL,
  `text` mediumtext DEFAULT NULL,
  `posx` int(11) NOT NULL,
  `posy` int(11) NOT NULL,
  `offset_attribute` varchar(255) DEFAULT NULL,
  `size` int(11) NOT NULL,
  `width` int(11) DEFAULT NULL,
  `border` tinyint(1) DEFAULT NULL,
  `font` varchar(255) NOT NULL,
  `angle` int(11) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `druckrahmen`
--

CREATE TABLE `druckrahmen` (
  `Name` varchar(255) NOT NULL,
  `id` int(11) NOT NULL,
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
  `font_user` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Daten für Tabelle `druckrahmen`
--

INSERT INTO `druckrahmen` (`Name`, `id`, `dhk_call`, `headsrc`, `headposx`, `headposy`, `headwidth`, `headheight`, `mapposx`, `mapposy`, `mapwidth`, `mapheight`, `refmapsrc`, `refmapfile`, `refmapposx`, `refmapposy`, `refmapwidth`, `refmapheight`, `refposx`, `refposy`, `refwidth`, `refheight`, `refzoom`, `dateposx`, `dateposy`, `datesize`, `scaleposx`, `scaleposy`, `scalesize`, `scalebarposx`, `scalebarposy`, `oscaleposx`, `oscaleposy`, `oscalesize`, `lageposx`, `lageposy`, `lagesize`, `gemeindeposx`, `gemeindeposy`, `gemeindesize`, `gemarkungposx`, `gemarkungposy`, `gemarkungsize`, `flurposx`, `flurposy`, `flursize`, `flurstposx`, `flurstposy`, `flurstsize`, `legendposx`, `legendposy`, `legendsize`, `arrowposx`, `arrowposy`, `arrowlength`, `userposx`, `userposy`, `usersize`, `watermarkposx`, `watermarkposy`, `watermark`, `watermarksize`, `watermarkangle`, `watermarktransparency`, `variable_freetexts`, `format`, `preis`, `font_date`, `font_scale`, `font_lage`, `font_gemeinde`, `font_gemarkung`, `font_flur`, `font_flurst`, `font_oscale`, `font_legend`, `font_watermark`, `font_user`) VALUES
('A4 hoch', 1, NULL, 'A4-hoch.jpg', 0, 0, 595, 842, 46, 50, 279, 400, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 303, 724, 0, 422, 74, 9, NULL, NULL, 422, 87, 9, NULL, NULL, NULL, NULL, NULL, NULL, 238, 54, 9, 238, 64, 9, NULL, NULL, NULL, 58, 50, 0, 530, 710, 75, 0, 0, 0, 155, 155, '', 120, 45, 77, NULL, 'A5hoch', 1050, 'Helvetica.afm', 'Helvetica.afm', NULL, NULL, 'Helvetica.afm', 'Helvetica.afm', NULL, NULL, 'Helvetica.afm', 'Times-Italic.afm', 'Helvetica.afm');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `druckrahmen2freibilder`
--

CREATE TABLE `druckrahmen2freibilder` (
  `druckrahmen_id` int(11) NOT NULL,
  `freibild_id` int(11) NOT NULL,
  `posx` int(11) NOT NULL,
  `posy` int(11) NOT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `angle` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `druckrahmen2freitexte`
--

CREATE TABLE `druckrahmen2freitexte` (
  `druckrahmen_id` int(11) NOT NULL,
  `freitext_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `druckrahmen2stelle`
--

CREATE TABLE `druckrahmen2stelle` (
  `stelle_id` int(11) NOT NULL,
  `druckrahmen_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `invitations`
--

CREATE TABLE `invitations` (
  `token` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `stelle_id` int(11) NOT NULL DEFAULT 0,
  `anrede` varchar(10) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `vorname` varchar(255) NOT NULL,
  `loginname` varchar(100) NOT NULL,
  `inviter_id` int(11) DEFAULT NULL,
  `completed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `labels`
--

CREATE TABLE `labels` (
  `Label_ID` int(11) NOT NULL,
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
  `minscale` int(11) UNSIGNED DEFAULT NULL,
  `maxscale` int(11) UNSIGNED DEFAULT NULL,
  `position` tinyint(1) DEFAULT NULL,
  `offsetx` varchar(50) DEFAULT NULL,
  `offsety` varchar(50) DEFAULT NULL,
  `angle` varchar(50) DEFAULT NULL,
  `anglemode` tinyint(1) DEFAULT NULL,
  `buffer` tinyint(3) DEFAULT NULL,
  `minfeaturesize` int(11) DEFAULT NULL,
  `maxfeaturesize` int(11) DEFAULT NULL,
  `partials` int(1) DEFAULT NULL,
  `maxlength` int(3) DEFAULT NULL,
  `repeatdistance` int(11) DEFAULT NULL,
  `wrap` tinyint(3) DEFAULT NULL,
  `the_force` int(1) DEFAULT NULL,
  `text` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `layer`
--

CREATE TABLE `layer` (
  `Layer_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Name_low-german` varchar(100) DEFAULT NULL,
  `Name_english` varchar(100) DEFAULT NULL,
  `Name_polish` varchar(100) DEFAULT NULL,
  `Name_vietnamese` varchar(100) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `Datentyp` tinyint(4) NOT NULL DEFAULT 2,
  `Gruppe` int(11) NOT NULL DEFAULT 0,
  `pfad` mediumtext DEFAULT NULL,
  `maintable` varchar(255) DEFAULT NULL,
  `oid` varchar(63) DEFAULT 'id',
  `identifier_text` varchar(50) DEFAULT NULL,
  `maintable_is_view` tinyint(1) NOT NULL DEFAULT 0,
  `Data` mediumtext DEFAULT NULL,
  `schema` varchar(50) DEFAULT NULL,
  `geom_column` varchar(68) DEFAULT NULL,
  `document_path` mediumtext DEFAULT NULL,
  `document_url` mediumtext DEFAULT NULL,
  `ddl_attribute` varchar(255) DEFAULT NULL,
  `tileindex` varchar(100) DEFAULT NULL,
  `tileitem` varchar(100) DEFAULT NULL,
  `labelangleitem` varchar(25) DEFAULT NULL,
  `labelitem` varchar(100) DEFAULT NULL,
  `labelmaxscale` int(11) DEFAULT NULL,
  `labelminscale` int(11) DEFAULT NULL,
  `labelrequires` varchar(255) DEFAULT NULL,
  `postlabelcache` tinyint(1) NOT NULL DEFAULT 0,
  `connection` mediumtext NOT NULL,
  `connection_id` bigint(20) UNSIGNED DEFAULT NULL,
  `printconnection` mediumtext DEFAULT NULL,
  `connectiontype` tinyint(4) NOT NULL DEFAULT 0,
  `classitem` varchar(100) DEFAULT NULL,
  `styleitem` varchar(100) DEFAULT NULL,
  `classification` varchar(50) DEFAULT NULL,
  `cluster_maxdistance` int(11) DEFAULT NULL,
  `tolerance` double NOT NULL DEFAULT 3,
  `toleranceunits` enum('pixels','feet','inches','kilometers','meters','miles','dd') NOT NULL DEFAULT 'pixels',
  `sizeunits` int(2) DEFAULT NULL,
  `epsg_code` varchar(6) DEFAULT '2398',
  `template` varchar(255) DEFAULT NULL,
  `max_query_rows` int(11) DEFAULT NULL,
  `queryable` enum('0','1') NOT NULL DEFAULT '0',
  `use_geom` tinyint(1) NOT NULL DEFAULT 1,
  `transparency` int(3) DEFAULT NULL,
  `drawingorder` int(11) NOT NULL DEFAULT 0,
  `legendorder` int(11) DEFAULT NULL,
  `minscale` int(11) DEFAULT NULL,
  `maxscale` int(11) DEFAULT NULL,
  `symbolscale` int(11) DEFAULT NULL,
  `offsite` varchar(11) DEFAULT NULL,
  `requires` int(11) DEFAULT NULL,
  `ows_srs` varchar(255) NOT NULL DEFAULT 'EPSG:2398',
  `wms_name` varchar(255) DEFAULT NULL,
  `wms_keywordlist` mediumtext DEFAULT NULL,
  `wms_server_version` varchar(8) NOT NULL DEFAULT '1.1.0',
  `wms_format` varchar(50) NOT NULL DEFAULT 'image/png',
  `wms_connectiontimeout` int(11) NOT NULL DEFAULT 60,
  `wms_auth_username` varchar(50) DEFAULT NULL,
  `wms_auth_password` varchar(50) DEFAULT NULL,
  `wfs_geom` varchar(100) DEFAULT NULL,
  `write_mapserver_templates` enum('data','generic') DEFAULT NULL,
  `selectiontype` varchar(20) DEFAULT NULL,
  `querymap` enum('0','1') NOT NULL DEFAULT '0',
  `logconsume` enum('0','1') NOT NULL DEFAULT '0',
  `processing` varchar(255) DEFAULT NULL,
  `kurzbeschreibung` text DEFAULT NULL COMMENT 'Freitext zur Beschreibung des Layerinhaltes',
  `datasource` int(11) DEFAULT NULL,
  `dataowner_name` text DEFAULT NULL COMMENT 'Name des Ansprechpartners',
  `dataowner_email` varchar(100) DEFAULT NULL COMMENT 'E-Mail Adresse der Ansprechperson.',
  `dataowner_tel` varchar(50) DEFAULT NULL COMMENT 'Telefonnummer der Ansprechperson.',
  `uptodateness` varchar(100) DEFAULT NULL COMMENT 'Aktualität der Daten des Layers.',
  `updatecycle` varchar(100) DEFAULT NULL COMMENT 'Aktualisierungszyklus der Daten des Layers.',
  `metalink` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `privileg` enum('0','1','2') NOT NULL DEFAULT '0',
  `export_privileg` tinyint(1) NOT NULL DEFAULT 1,
  `status` varchar(255) DEFAULT NULL,
  `trigger_function` varchar(255) DEFAULT NULL COMMENT 'Wie heist die Trigger Funktion, die ausgelöst werden soll.',
  `sync` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Wenn 1, werden Änderungen in maintable_delta gespeichert und stellt ein das Layer für Syncronisierung mit kvmobile verfügbar ist.',
  `editable` tinyint(1) NOT NULL DEFAULT 1,
  `listed` tinyint(1) NOT NULL DEFAULT 1,
  `duplicate_from_layer_id` int(11) DEFAULT NULL,
  `duplicate_criterion` varchar(255) DEFAULT NULL,
  `shared_from` int(11) DEFAULT NULL,
  `version` varchar(10) NOT NULL DEFAULT '1.0.0',
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 PACK_KEYS=1;

--
-- Daten für Tabelle `layer`
--

INSERT INTO `layer` (`Layer_ID`, `Name`, `Name_low-german`, `Name_english`, `Name_polish`, `Name_vietnamese`, `alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `oid`, `identifier_text`, `maintable_is_view`, `Data`, `schema`, `geom_column`, `document_path`, `document_url`, `ddl_attribute`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `postlabelcache`, `connection`, `connection_id`, `printconnection`, `connectiontype`, `classitem`, `styleitem`, `classification`, `cluster_maxdistance`, `tolerance`, `toleranceunits`, `sizeunits`, `epsg_code`, `template`, `max_query_rows`, `queryable`, `use_geom`, `transparency`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `symbolscale`, `offsite`, `requires`, `ows_srs`, `wms_name`, `wms_keywordlist`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `write_mapserver_templates`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datasource`, `dataowner_name`, `dataowner_email`, `dataowner_tel`, `uptodateness`, `updatecycle`, `metalink`, `icon`, `privileg`, `export_privileg`, `status`, `trigger_function`, `sync`, `editable`, `listed`, `duplicate_from_layer_id`, `duplicate_criterion`, `shared_from`, `version`, `comment`) VALUES
(1, 'BaseMap DE farbig', NULL, '', NULL, NULL, 'BaseMap DE farbig', 3, 32, '', '', '', '', 0, '', '', NULL, '', '', '', '', '', '', '', NULL, NULL, '', 0, 'https://sgx.geodatenzentrum.de/wms_basemapde?VERSION=1.1.0&FORMAT=image/png&STYLES=&LAYERS=de_basemapde_web_raster_farbe', NULL, '', 7, '', '', '', NULL, 3, 'pixels', NULL, '25833', NULL, NULL, '0', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'EPSG:25833 EPSG:25832 EPSG:4326', 'de_basemapde_web_raster_farbe', '', '1.1.1', 'image/png', 60, '', '', '', NULL, 'radio', '0', '0', '1.3.0', 'Der WMS DE basemap.de Web Raster hat als Datengrundlage die basemap.de Web Vektor. Die Darstellung dieser beruht auf einer bundesweit einheitlichen Definition des Webkarten-Signaturenkataloges (basemap.de Web-SK) der AdV. Es wird die basemap.de Web-SK Version in der jeweils aktuellen Fassung verwendet. Informationen zur Aktualität der Daten und zur jeweiligen Version können unter https://www.basemap.de/data/produkte/web_raster/meta/bm_web_raster_datenaktualitaet.html eingesehen werden. ', NULL, 'Dienstleistungszentrum des Bundes für Geoinformation und Geodäsie', 'dlz@bkg.bund.de', '+49 (0) 341 5634 333', NULL, NULL, 'https://sgx.geodatenzentrum.de/wms_basemapde?Service=WMS&Request=GetCapabilities', NULL, '0', 1, '', '', '0', 1, 1, NULL, NULL, NULL, '1.1.1', NULL),
(2, 'BaseMap DE grau', NULL, '', NULL, NULL, 'BaseMap DE grau', 3, 32, '', '', '', '', 0, '', '', NULL, '', '', '', '', '', '', '', NULL, NULL, '', 0, 'https://sgx.geodatenzentrum.de/wms_basemapde?VERSION=1.1.0&FORMAT=image/png&STYLES=&LAYERS=de_basemapde_web_raster_grau', NULL, '', 7, '', '', '', NULL, 3, 'pixels', NULL, '25833', NULL, NULL, '0', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'EPSG:25833 EPSG:25832 EPSG:4326', 'de_basemapde_web_raster_grau', '', '1.1.1', 'image/png', 60, '', '', '', NULL, 'radio', '0', '0', '1.3.0', 'Der WMS DE basemap.de Web Raster hat als Datengrundlage die basemap.de Web Vektor. Die Darstellung dieser beruht auf einer bundesweit einheitlichen Definition des Webkarten-Signaturenkataloges (basemap.de Web-SK) der AdV. Es wird die basemap.de Web-SK Version in der jeweils aktuellen Fassung verwendet. Informationen zur Aktualität der Daten und zur jeweiligen Version können unter https://www.basemap.de/data/produkte/web_raster/meta/bm_web_raster_datenaktualitaet.html eingesehen werden. ', NULL, 'Dienstleistungszentrum des Bundes für Geoinformation und Geodäsie', 'dlz@bkg.bund.de', '+49 (0) 341 5634 333', NULL, NULL, 'https://sgx.geodatenzentrum.de/wms_basemapde?Service=WMS&Request=GetCapabilities', NULL, '0', 1, '', '', '0', 1, 1, NULL, NULL, NULL, '1.1.1', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `layer_attributes`
--

CREATE TABLE `layer_attributes` (
  `layer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `real_name` varchar(255) DEFAULT NULL,
  `tablename` varchar(100) DEFAULT NULL,
  `table_alias_name` varchar(100) DEFAULT NULL,
  `schema` varchar(100) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `geometrytype` varchar(20) DEFAULT NULL,
  `constraints` mediumtext DEFAULT NULL,
  `saveable` tinyint(1) DEFAULT NULL,
  `nullable` tinyint(1) DEFAULT NULL,
  `length` int(11) DEFAULT NULL,
  `decimal_length` int(11) DEFAULT NULL,
  `default` varchar(255) DEFAULT NULL,
  `form_element_type` enum('Text','Textfeld','Auswahlfeld','Auswahlfeld_Bild','Autovervollständigungsfeld','Autovervollständigungsfeld_zweispaltig','Radiobutton','Checkbox','Geometrie','SubFormPK','SubFormFK','SubFormEmbeddedPK','Time','Dokument','Link','dynamicLink','User','UserID','Stelle','StelleID','Fläche','Länge','Zahl','mailto','Winkel','Style','Editiersperre','ExifLatLng','ExifRichtung','ExifErstellungszeit','Farbauswahl') NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `alias_low-german` varchar(100) DEFAULT NULL,
  `alias_english` varchar(100) DEFAULT NULL,
  `alias_polish` varchar(100) DEFAULT NULL,
  `alias_vietnamese` varchar(100) DEFAULT NULL,
  `tooltip` text DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `tab` varchar(255) DEFAULT NULL,
  `arrangement` tinyint(1) NOT NULL DEFAULT 0,
  `labeling` tinyint(1) NOT NULL DEFAULT 0,
  `raster_visibility` tinyint(1) DEFAULT NULL,
  `dont_use_for_new` tinyint(1) DEFAULT NULL,
  `mandatory` tinyint(1) DEFAULT NULL,
  `quicksearch` tinyint(1) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 1,
  `kvp` tinyint(1) NOT NULL DEFAULT 0,
  `vcheck_attribute` varchar(255) DEFAULT NULL,
  `vcheck_operator` varchar(4) DEFAULT NULL,
  `vcheck_value` mediumtext DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `privileg` tinyint(1) DEFAULT 0,
  `query_tooltip` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `layer_attributes2rolle`
--

CREATE TABLE `layer_attributes2rolle` (
  `layer_id` int(11) NOT NULL,
  `attributename` varchar(255) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `switchable` tinyint(1) NOT NULL DEFAULT 1,
  `switched_on` tinyint(1) NOT NULL DEFAULT 1,
  `sortable` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 1,
  `sort_direction` enum('asc','desc') NOT NULL DEFAULT 'asc'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `layer_attributes2stelle`
--

CREATE TABLE `layer_attributes2stelle` (
  `layer_id` int(11) NOT NULL,
  `attributename` varchar(255) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `privileg` tinyint(1) NOT NULL,
  `tooltip` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `layer_charts`
--

CREATE TABLE `layer_charts` (
  `id` int(11) NOT NULL,
  `layer_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `type` enum('bar','pie','doughnut') NOT NULL DEFAULT 'bar',
  `aggregate_function` enum('sum','average','min','max') DEFAULT NULL,
  `value_attribute_label` varchar(100) DEFAULT NULL,
  `value_attribute_name` varchar(65) DEFAULT NULL,
  `label_attribute_name` varchar(65) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `layer_parameter`
--

CREATE TABLE `layer_parameter` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `default_value` varchar(255) NOT NULL,
  `options_sql` mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `migrations`
--

CREATE TABLE `migrations` (
  `component` varchar(50) NOT NULL,
  `type` enum('mysql','postgresql') NOT NULL,
  `filename` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Daten für Tabelle `migrations`
--

INSERT INTO `migrations` (`component`, `type`, `filename`) VALUES
('kvwmap', 'mysql', '2014-09-12_16-33-22_Version2.0.sql'),
('kvwmap', 'mysql', '2014-11-07_11-37-59_layer_attributes_Autovervollstaendigungsfeld.sql'),
('kvwmap', 'mysql', '2014-11-24_10-29-21_druckrahmen_lage_gemeinde_flurst.sql'),
('kvwmap', 'mysql', '2014-11-27_11-16-24_druckrahmen_scalebar.sql'),
('kvwmap', 'mysql', '2014-12-03_10-25-40_zwischenablage.sql'),
('kvwmap', 'mysql', '2015-01-14_16-44-46_styles_initialgap_opacity.sql'),
('kvwmap', 'mysql', '2015-02-20_11-11-00_rolle_instant_reload_menu_auto_close.sql'),
('kvwmap', 'mysql', '2015-03-12_15-03-13_styles_colorrange_datarange.sql'),
('kvwmap', 'mysql', '2015-03-16_15-15-35_rollenlayer_query.sql'),
('kvwmap', 'mysql', '2015-03-17_10-29-08_rollenlayer_queryStatus.sql'),
('kvwmap', 'mysql', '2015-03-27_11-36-34_rollenlayer_Data_longtext.sql'),
('kvwmap', 'mysql', '2015-05-05_13-53-32_u_polygon2used_layer_loeschen.sql'),
('kvwmap', 'mysql', '2015-05-07_11-29-06_search_attributes2rolle_PK.sql'),
('kvwmap', 'mysql', '2015-05-08_14-03-33_rolle_last_query_sql_longtext.sql'),
('kvwmap', 'mysql', '2015-05-22_14-34-59_layer_postlabelcache.sql'),
('kvwmap', 'mysql', '2015-05-28_11-08-55_rolle_auto_map_resize.sql'),
('kvwmap', 'mysql', '2015-06-16_16-16-14_u_rolle2usedlayer_gle_view.sql'),
('kvwmap', 'mysql', '2015-06-23_16-29-07_layer_requires.sql'),
('kvwmap', 'mysql', '2015-08-05_14-02-01_rolle_saved_layers.sql'),
('kvwmap', 'mysql', '2015-09-09_15-10-25_rolle_saved_layers_query.sql'),
('kvwmap', 'mysql', '2015-10-07_09-19-25_layer_attributes_StelleID.sql'),
('kvwmap', 'mysql', '2015-10-29_13-50-26_u_consumeALB_format.sql'),
('kvwmap', 'mysql', '2015-12-08_12-01-43_user_loginname.sql'),
('kvwmap', 'mysql', '2016-01-07_11-26-53_datendrucklayouts_gap.sql'),
('kvwmap', 'mysql', '2016-01-18_11-43-24_u_consume_epsg_code.sql'),
('kvwmap', 'mysql', '2016-01-26_14-04-28_druckrahmen_dhk_call.sql'),
('kvwmap', 'mysql', '2016-02-09_15-34-14_layer_cluster_maxdistance.sql'),
('kvwmap', 'mysql', '2016-02-17_13-55-03_rolle_coord_query.sql'),
('kvwmap', 'mysql', '2016-04-21_10-42-03_stelle_gemeinden_gemarkung_flur.sql'),
('kvwmap', 'mysql', '2016-04-28_16-35-05_add_data_query_params_to_u_rolle2used.sql'),
('kvwmap', 'mysql', '2016-04-28_16-35-05_add_layer_params.sql'),
('kvwmap', 'mysql', '2016-05-31_11-22-56_stelle_hist_timestamp.sql'),
('kvwmap', 'mysql', '2016-05-31_13-18-54_stelle_wasserzeichen.sql'),
('kvwmap', 'mysql', '2016-06-22_16-24-48_layer_attributes_arrangement_labeling.sql'),
('kvwmap', 'mysql', '2016-06-29_10-50-56_layer_maintable_is_view.sql'),
('kvwmap', 'mysql', '2016-06-30_10-54-02_u_rolle2used_layer_transparency.sql'),
('kvwmap', 'mysql', '2016-07-12_15-46-25_used_layer_use_geom.sql'),
('kvwmap', 'mysql', '2016-07-29_14-24-25_add_visually_impaired_to_rolle.sql'),
('kvwmap', 'mysql', '2016-08-17_13-57-20_add_datatypes_and_datatype_attributes.sql'),
('kvwmap', 'mysql', '2016-08-29_11-45-00_datatypes_dbname_host_port.sql'),
('kvwmap', 'mysql', '2016-08-30_14-11-10_add_trigger_function_to_layer.sql'),
('kvwmap', 'mysql', '2016-10-05_11-09-37_styles_symbolname.sql'),
('kvwmap', 'mysql', '2016-10-05_12-11-57_styles_rangeitem.sql'),
('kvwmap', 'mysql', '2016-11-22_15-23-00_set_relative_fonts_pfade.sql'),
('kvwmap', 'mysql', '2016-11-23_11-47-01_layer_classification.sql'),
('kvwmap', 'mysql', '2016-11-24_10-56-43_styles_minscale_maxscale.sql'),
('kvwmap', 'mysql', '2017-01-10_13-46-46_cronjobs.sql'),
('kvwmap', 'mysql', '2017-01-12_13-19-18_datendrucklayouts_no_record_splitting.sql'),
('kvwmap', 'mysql', '2017-02-02_15-57-57_add_db_to_cronjobs.sql'),
('kvwmap', 'mysql', '2017-03-24_14-56-40_add_custom_legend_graphic_and_order.sql'),
('kvwmap', 'mysql', '2017-03-29_16-48-03_add_title_to_menues.sql'),
('kvwmap', 'mysql', '2017-04-05_16-14-15_add_default_rolle_to_stelle.sql'),
('kvwmap', 'mysql', '2017-04-06_09-59-50_add_showmapfunctions_to_rolle.sql'),
('kvwmap', 'mysql', '2017-04-07_12-17-10_add_showlayeroptions.sql'),
('kvwmap', 'mysql', '2017-04-13_17-00-45_add_onclick_on_menues.sql'),
('kvwmap', 'mysql', '2017-04-20_15-17-29_u_menues_button_class.sql'),
('kvwmap', 'mysql', '2017-04-20_15-59-03_rolle_menue_buttons.sql'),
('kvwmap', 'mysql', '2017-04-21_10-49-49_u_menues_logout.sql'),
('kvwmap', 'mysql', '2017-06-07_16-01-20_add_dont_use_for_new.sql'),
('kvwmap', 'mysql', '2017-07-04_14-37-35_rolle_gui_button.sql'),
('kvwmap', 'mysql', '2017-07-11_10-20-03_layer_attributes_radiobutton.sql'),
('kvwmap', 'mysql', '2017-07-11_14-57-05_druckfreilinien.sql'),
('kvwmap', 'mysql', '2017-07-13_10-37-26_layer_attributes_Winkel.sql'),
('kvwmap', 'mysql', '2017-07-14_13-51-46_classes_legendimageheight_width.sql'),
('kvwmap', 'mysql', '2017-07-17_09-31-55_label_maxlength.sql'),
('kvwmap', 'mysql', '2017-08-31_14-16-16_delete_legend_order_in_group_and_used_layer.sql'),
('kvwmap', 'mysql', '2017-09-11_15-40-24_u_menues_Druckausschnittswahl.sql'),
('kvwmap', 'mysql', '2017-09-13_10-39-04_add_stellen_hierarchie.sql'),
('kvwmap', 'mysql', '2017-09-19_12-04-37_styles_polaroffset.sql'),
('kvwmap', 'mysql', '2017-10-08_09-51-31_add_sync_attr_to_layers.sql'),
('kvwmap', 'mysql', '2017-11-02_12-14-38_rolle_legendtype.sql'),
('kvwmap', 'mysql', '2017-11-14_10-08-33_u_rolle2used_layer_drawingorder.sql'),
('kvwmap', 'mysql', '2017-11-16_14-01-29_datendrucklayouts_filename.sql'),
('kvwmap', 'mysql', '2018-01-04_14-11-01_layer_legendorder.sql'),
('kvwmap', 'mysql', '2018-01-08_14-09-22_add_style_attribute_type.sql'),
('kvwmap', 'mysql', '2018-02-01_16-14-00_layer_attributes_invisible.sql'),
('kvwmap', 'mysql', '2018-03-22_16-22-21_fk_for_users.sql'),
('kvwmap', 'mysql', '2018-03-26_12-48-46_improve_innodb_performance.sql'),
('kvwmap', 'mysql', '2018-04-03_17-01-06_add_num_login_failed_to_users.sql'),
('kvwmap', 'mysql', '2018-04-06_18-15-25_add_invitations.sql'),
('kvwmap', 'mysql', '2018-04-11_14-08-25_editable.sql'),
('kvwmap', 'mysql', '2018-04-16_11-37-08_layer_listed.sql'),
('kvwmap', 'mysql', '2018-05-16_11-23-39_user_agreement_accepted.sql'),
('kvwmap', 'mysql', '2018-05-25_16-26-55_layer_document_url.sql'),
('kvwmap', 'mysql', '2018-06-25_16-40-37_add_visible_and_arrangement_to_datatype_attributes.sql'),
('kvwmap', 'mysql', '2018-07-03_11-49-35_layer_attributes_vcheck.sql'),
('kvwmap', 'mysql', '2018-07-23_15-45-15_datatype_foreign_keys.sql'),
('kvwmap', 'mysql', '2018-08-23_16-47-03_fk_for_menues.sql'),
('kvwmap', 'mysql', '2018-08-24_11-14-04_rollenlayer_classitem.sql'),
('kvwmap', 'mysql', '2018-09-07_14-34-53_config.sql'),
('kvwmap', 'mysql', '2018-09-19_14-34-53_config.php'),
('kvwmap', 'mysql', '2018-10-01_12-35-08_login_constants.sql'),
('kvwmap', 'mysql', '2018-10-11_16-50-18_login-logout_routine.sql'),
('kvwmap', 'mysql', '2018-10-12_15-30-58_druckfreilinien_offset.sql'),
('kvwmap', 'mysql', '2018-10-15_10-01-29_druckfreilinien_breite.sql'),
('kvwmap', 'mysql', '2018-10-15_11-49-53_USE_EXISTING_SESSION.sql'),
('kvwmap', 'mysql', '2018-10-19_16-08-48_print_legend_separate.sql'),
('kvwmap', 'mysql', '2018-10-25_13-11-16_custom_labelitem.sql'),
('kvwmap', 'mysql', '2018-11-07_13-42-05_metadata_constants.sql'),
('kvwmap', 'mysql', '2018-11-09_11-30-36_sizeitem_entfernt.sql'),
('kvwmap', 'mysql', '2018-11-26_11-29-39_custom_raster.sql'),
('kvwmap', 'mysql', '2018-12-13_15-05-58_add_OGR_BINPATH_GDAL.sql'),
('kvwmap', 'mysql', '2018-12-18_15-31-29_layer_hist_timestamp.sql'),
('kvwmap', 'mysql', '2019-02-07_11-42-12_alb_raumbezug.sql'),
('kvwmap', 'mysql', '2019-02-08_09-16-51_u_rolle2used_layer_geom_from_layer.sql'),
('kvwmap', 'mysql', '2019-02-14_17-30-53_add_protected_to_stelle.sql'),
('kvwmap', 'mysql', '2019-02-28_10-35-41_config_PASSWORD_INFO.sql'),
('kvwmap', 'mysql', '2019-03-05_09-22-46_add_editiersperre_attribute_type.sql'),
('kvwmap', 'mysql', '2019-03-06_15-31-28_geo_name_search.sql'),
('kvwmap', 'mysql', '2019-03-07_14-13-59_Menue_Stelle_waehlen.sql'),
('kvwmap', 'mysql', '2019-03-11_10-06-54_rolle_print_scale.sql'),
('kvwmap', 'mysql', '2019-03-13_09-23-22_public_comments.sql'),
('kvwmap', 'mysql', '2019-03-18_10-33-37_rollenlayer_gle_view.sql'),
('kvwmap', 'mysql', '2019-04-01_11-20-10_add_kvp_to_layer_attrb.sql'),
('kvwmap', 'mysql', '2019-04-09_17-10-26_change_menue_link_length.sql'),
('kvwmap', 'mysql', '2019-04-14_13-09-22_add_rolle2used_layer_filter.sql'),
('kvwmap', 'mysql', '2019-04-25_13-11-02_change_user_phon_and_mail_length.sql'),
('kvwmap', 'mysql', '2019-04-28_16-44-58_add_further_attribute_table_to_layer.sql'),
('kvwmap', 'mysql', '2019-05-02_17-14-53_geom_buttons.sql'),
('kvwmap', 'mysql', '2019-05-03_11-36-09_change_rolle_default_values.sql'),
('kvwmap', 'mysql', '2019-05-11_10-29-24_add_zweispaltiges_autovervollstaendigungsfeld.sql'),
('kvwmap', 'mysql', '2019-05-14_13-02-51_wms_keywordlist.sql'),
('kvwmap', 'mysql', '2019-05-16_16-29-24_bug_zweispaltiges_autovervollstaendigungsfeld.sql'),
('kvwmap', 'mysql', '2019-05-17_11-41-14_drop_further_attributes.sql'),
('kvwmap', 'mysql', '2019-06-03_10-35-43_showrollenfilter.sql'),
('kvwmap', 'mysql', '2019-06-25_12-09-43_add_org_and_pos_to_user.sql'),
('kvwmap', 'mysql', '2019-07-25_14-31-16_set_rollenlayer_gle_view_default.sql'),
('kvwmap', 'mysql', '2019-07-29_11-38-48_u_menues_schnelle_Druckausgabe.sql'),
('kvwmap', 'mysql', '2019-07-31_08-59-09_gle_view_not_null.sql'),
('kvwmap', 'mysql', '2019-08-25_16-40-16_outsource_custom_files.php'),
('kvwmap', 'mysql', '2019-08-29_16-17-17_datendrucklayouts_margins.sql'),
('kvwmap', 'mysql', '2019-09-23_10-03-03_ddl_columns.sql'),
('kvwmap', 'mysql', '2019-10-01_14-16-57_sicherungen.sql'),
('kvwmap', 'mysql', '2019-10-04_15-35-07_add_ddl_attibute_to_layer.sql'),
('kvwmap', 'mysql', '2019-10-18_14-08-32_layer_identifier.sql'),
('kvwmap', 'mysql', '2019-11-01_13-04-10_config_name.sql'),
('kvwmap', 'mysql', '2019-11-14_13-45-06_add_stellen_style.sql'),
('kvwmap', 'mysql', '2019-11-19_14-25-41_rolle_gui_default.sql'),
('kvwmap', 'mysql', '2019-12-04_11-49-03_rolle_result_style.sql'),
('kvwmap', 'mysql', '2019-12-05_09-36-56_layer_attributes_constraints.sql'),
('kvwmap', 'mysql', '2019-12-06_09-48-49_SubformPK_privileg.sql'),
('kvwmap', 'mysql', '2019-12-06_10-01-59_config_bg_image.sql'),
('kvwmap', 'mysql', '2019-12-06_10-19-31_config_rollenfilter.sql'),
('kvwmap', 'mysql', '2020-01-07_14-51-45_ddl_format.sql'),
('kvwmap', 'mysql', '2020-01-29_16-10-32_styleitem.sql'),
('kvwmap', 'mysql', '2020-02-21_09-55-59_styles_width_attribute.sql'),
('kvwmap', 'mysql', '2020-02-28_15-26-23_layer_attributes_saveable.sql'),
('kvwmap', 'mysql', '2020-03-19_11-22-21_druckfreitexte_width_border.sql'),
('kvwmap', 'mysql', '2020-04-02_19-52-03_config_normalize_geometry.sql'),
('kvwmap', 'mysql', '2020-04-14_13-30-54_epsg_code_druckausschnitte.sql'),
('kvwmap', 'mysql', '2020-04-16_11-02-51_add_column_user_to_cron_jobs.sql'),
('kvwmap', 'mysql', '2020-04-21_10-25-24_add_duplicate_columns_to_layer.sql'),
('kvwmap', 'mysql', '2020-05-12_12-09-27_add_exif_attribute_types.sql'),
('kvwmap', 'mysql', '2020-05-25_10-41-36_druckfreirechtecke.sql'),
('kvwmap', 'mysql', '2020-05-28_10-57-58_ddl_colors.sql'),
('kvwmap', 'mysql', '2020-06-02_10-16-50_change_layer_attributes_tooltip_type.sql'),
('kvwmap', 'mysql', '2020-06-04_10-29-54_NORMALIZE_Parameter_verschieben.sql'),
('kvwmap', 'mysql', '2020-07-03_11-12-57_change_collations.sql'),
('kvwmap', 'mysql', '2020-07-03_11-12-58_add_layer_attributes2rolle.sql'),
('kvwmap', 'mysql', '2020-07-03_14-40-28_config_postgres_connection_id.sql'),
('kvwmap', 'mysql', '2020-07-08_15-32-53_add_connection_id_to_rollenlayer.sql'),
('kvwmap', 'mysql', '2020-07-15_12-59-27_label_REPEATDISTANCE.sql'),
('kvwmap', 'mysql', '2020-07-15_14-28-12_add_connection_id_to_datatyps.sql'),
('kvwmap', 'mysql', '2020-07-19_17-29-52_add_php_sql_parser.php'),
('kvwmap', 'mysql', '2020-08-18_09-10-17_layer_use_geom.sql'),
('kvwmap', 'mysql', '2020-08-19_09-20-04_layer_max_query_rows.sql'),
('kvwmap', 'mysql', '2020-08-25_12-13-32_drop_filteritem.sql'),
('kvwmap', 'mysql', '2020-10-02_13-56-39_Indizes.sql'),
('kvwmap', 'mysql', '2020-11-06_10-00-00_migrate_to_3.0.php'),
('kvwmap', 'mysql', '2020-11-06_11-42-05_Version3.0.sql'),
('kvwmap', 'mysql', '2020-11-24_11-11-29_add_const_ms_debug_level.sql'),
('kvwmap', 'mysql', '2020-12-10_23-01-33_add_rolle_attribut_immer_weiter_erfassen.sql'),
('kvwmap', 'mysql', '2021-01-20_16-15-36_add_mailsmtpuser_passwd_constants.sql'),
('kvwmap', 'mysql', '2021-01-25_13-07-32_add_rollenlayer_freigabe_attribute.sql'),
('kvwmap', 'mysql', '2021-02-16_10-50-18_rolle_export_settings.sql'),
('kvwmap', 'mysql', '2021-02-18_09-41-33_layer_attributes_schema.sql'),
('kvwmap', 'mysql', '2021-03-01_13-24-21_adapt_sicherung_schema.sql'),
('kvwmap', 'mysql', '2021-03-01_15-15-35_adapt_sicherung_schema2.sql'),
('kvwmap', 'mysql', '2021-03-03_13-51-07_sicherungen.sql'),
('kvwmap', 'mysql', '2021-03-08_13-14-45_drop_antialias.sql'),
('kvwmap', 'mysql', '2021-03-15_12-21-50_add_const_copy_mail_attachment.sql'),
('kvwmap', 'mysql', '2021-03-15_13-00-50_use_parent_privileges.sql'),
('kvwmap', 'mysql', '2021-03-26_13-52-17_add_wms_auth_to_rollenlayer.sql'),
('kvwmap', 'mysql', '2021-05-19_10-03-58_layer_attributes_Farbauswahl.sql'),
('kvwmap', 'mysql', '2021-05-20_12-04-49_layer_attributes_tab.sql'),
('kvwmap', 'mysql', '2021-05-25_09-38-14_config_IMPORT_POINT_STYLE_ID.sql'),
('kvwmap', 'mysql', '2021-06-28_20-41-13_belated_files.sql'),
('kvwmap', 'mysql', '2021-09-22_10-13-14_belated_files_lastmodified.sql'),
('kvwmap', 'mysql', '2021-09-22_11-34-48_drop_sicherungen.sql'),
('kvwmap', 'mysql', '2021-11-15_15-35-23_add_redline_options.sql'),
('kvwmap', 'mysql', '2021-11-23_11-00-05_layer_sizeunits.sql'),
('kvwmap', 'mysql', '2021-11-24_10-00-19_add_layer_metadata.sql'),
('kvwmap', 'mysql', '2021-12-21_11-11-21_invitations_login_name.sql'),
('kvwmap', 'mysql', '2021-12-23_14-49-19_change_enum_columns.sql'),
('kvwmap', 'mysql', '2022-01-11_15-13-08_zwischenablage_oid.sql'),
('kvwmap', 'mysql', '2022-01-16_11-38-45_add_icon_to_layer_groups.sql'),
('kvwmap', 'mysql', '2022-01-18_14-24-10_change_color_attribute.sql'),
('kvwmap', 'mysql', '2022-03-08_13-44-15_layer_oid_null.sql'),
('kvwmap', 'mysql', '2022-03-09_09-49-07_stelle_drop_pg_conn.sql'),
('kvwmap', 'mysql', '2022-03-15_13-57-47_postlabelcache.sql'),
('kvwmap', 'mysql', '2022-04-28_09-08-38_invitation_anrede.sql'),
('kvwmap', 'mysql', '2022-05-16_07-49-58_identifier_text.sql'),
('kvwmap', 'mysql', '2022-05-31_11-37-31_config_NUTZER_ARCHIVIEREN.sql'),
('kvwmap', 'mysql', '2022-05-31_11-48-07_user_archived.sql'),
('kvwmap', 'mysql', '2022-06-09_12-52-40_add_layer_version_and_comment_attribute.sql'),
('kvwmap', 'mysql', '2022-06-15_08-16-53_Auswahlfeld_Bild.sql'),
('kvwmap', 'mysql', '2022-06-16_08-28-51_layer_data_import_allowed.sql'),
('kvwmap', 'mysql', '2022-06-23_06-39-33_rollenlayer_connection.sql'),
('kvwmap', 'mysql', '2022-06-28_19-10-12_add_metadata_to_stelle.sql'),
('kvwmap', 'mysql', '2022-06-30_10-12-34_add_more_metadata_to_stelle.sql'),
('kvwmap', 'mysql', '2022-07-05_10-49-10_update_jquery_and_bootstrap.php'),
('kvwmap', 'mysql', '2022-07-19_14-25-29_add_ows_namespace_to_stelle.sql'),
('kvwmap', 'mysql', '2022-08-09_14-23-43_styles_angleitem_null.sql'),
('kvwmap', 'mysql', '2022-08-17_09-41-48_user_tokens.sql'),
('kvwmap', 'mysql', '2022-08-22_11-11-31_rolle_tooltipquery.sql'),
('kvwmap', 'mysql', '2022-09-02_07-28-07_user_tokens.sql'),
('kvwmap', 'mysql', '2022-09-04_22-24-27_sha1_user_password.sql'),
('kvwmap', 'mysql', '2022-11-10_14-14-48_add_login_locked_until.sql'),
('kvwmap', 'mysql', '2022-11-17_08-11-28_drop_DELETE_ROLLENLAYER.sql'),
('kvwmap', 'mysql', '2022-11-17_13-45-40_label_text.sql'),
('kvwmap', 'mysql', '2022-11-17_14-18-57_label_minscale_maxscale.sql'),
('kvwmap', 'mysql', '2022-11-21_13-11-42_rollenlayer_buffer.sql'),
('kvwmap', 'mysql', '2022-11-25_13-11-42_rename_Suchergebnis_to_eigene_Abfragen.sql'),
('kvwmap', 'mysql', '2022-12-14_00-19-18_add_font_size_factor_to_user_settings.sql'),
('kvwmap', 'mysql', '2022-12-19_14-43-11_add_multiple_notifications.sql'),
('kvwmap', 'mysql', '2022-22-29_14-13-17_change_layer_oid_default.sql'),
('kvwmap', 'mysql', '2023-01-02_13-35-17_add_write_mapserver_templates_to_layer.sql'),
('kvwmap', 'mysql', '2023-02-03_15-14-42_add_password_expired_to_users.sql'),
('kvwmap', 'mysql', '2023-02-23_15-18-47_anglemode.sql'),
('kvwmap', 'mysql', '2023-04-20_09-10-42_stelle_foreign_keys.sql'),
('kvwmap', 'mysql', '2023-05-03_15-02-27_default_ohne_select.sql'),
('kvwmap', 'mysql', '2023-05-09_13-50-46_layer_drawingorder.sql'),
('kvwmap', 'mysql', '2023-05-11_09-59-51_FKs_used_layer_layer_attributes2stelle.sql'),
('kvwmap', 'mysql', '2023-05-23_10-25-19_rollenlayer_autodelete.sql'),
('kvwmap', 'mysql', '2023-05-23_13-53-55_datasources.sql'),
('kvwmap', 'mysql', '2023-05-25_05-12-58_add_ows_updatesequence_to_stelle.sql'),
('kvwmap', 'mysql', '2023-06-01_15-52-14_add_dataset_operations_position.sql'),
('kvwmap', 'mysql', '2023-06-06_13-53-51_layercharts.sql'),
('kvwmap', 'mysql', '2023-06-12_10-59-37_user_userdata_checking_time.sql'),
('kvwmap', 'mysql', '2023-06-19_17-57-12_change_write_mapserver_templates_to_layer.sql'),
('kvwmap', 'mysql', '2023-06-27_12-46-51_datatypes_drop_dbname_host_port.sql'),
('kvwmap', 'mysql', '2023-06-30_15-26-14_datatype_attributes_layer_id.sql'),
('kvwmap', 'mysql', '2023-07-06_12-33-57_QUERY_ONLY_ACTIVE_CLASSES.sql'),
('kvwmap', 'mysql', '2023-07-06_15-05-03_add_geom_column.sql'),
('kvwmap', 'mysql', '2023-07-17_09-49-57_Change_OWS_SERVICE_ONLINERESOURCE_write_permission.sql'),
('kvwmap', 'mysql', '2023-07-17_14-10-12_change_wappen_default.sql'),
('kvwmap', 'mysql', '2023-09-04_19-48-12_add_chartjs_to_3rdparty.php'),
('kvwmap', 'mysql', '2023-10-05_21-57-32_add_user_to_notification.sql'),
('kvwmap', 'mysql', '2023-10-09_15-38-33_add_label_to_dll.sql'),
('kvwmap', 'mysql', '2023-10-30_09-28-37_add_menue_fk.sql'),
('kvwmap', 'mysql', '2023-11-24_11-29-11_offsetxy_text.sql'),
('kvwmap', 'mysql', '2023-11-27_12-59-19_reset_password_text.sql'),
('kvwmap', 'mysql', '2023-12-05_09-35-40_invitation_text.sql'),
('kvwmap', 'mysql', '2023-12-08_11-22-35_config_OWERRIDE_LANGUAGE_VARS.sql'),
('kvwmap', 'mysql', '2023-12-08_11-25-56_create_custom_language_and_ccs_readme_files.php'),
('kvwmap', 'mysql', '2024-01-01_19-39-23_add_used_layer_group_id.sql'),
('kvwmap', 'mysql', '2024-01-08_17-52-40_add_primary_key_user2notifications.sql');


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `notification` text COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `stellen_filter` text COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `user_filter` text COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `veroeffentlichungsdatum` date DEFAULT NULL,
  `ablaufdatum` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `referenzkarten`
--

CREATE TABLE `referenzkarten` (
  `ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL DEFAULT '',
  `Dateiname` varchar(100) NOT NULL DEFAULT '',
  `epsg_code` int(11) NOT NULL DEFAULT 2398,
  `xmin` double NOT NULL DEFAULT 0,
  `ymin` double NOT NULL DEFAULT 0,
  `xmax` double NOT NULL DEFAULT 0,
  `ymax` double NOT NULL DEFAULT 0,
  `width` int(4) UNSIGNED NOT NULL DEFAULT 0,
  `height` int(4) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Daten für Tabelle `referenzkarten`
--

INSERT INTO `referenzkarten` (`ID`, `Name`, `Dateiname`, `epsg_code`, `xmin`, `ymin`, `xmax`, `ymax`, `width`, `height`) VALUES
(1, 'Landkreis Doberan', 'uebersicht_kreise.png', 2398, 4463906, 5966941, 4543877, 6018614, 200, 135),
(2, 'Elmenhorst-Lichtenhagen', 'referenz13051019.png', 2398, 4498014, 6000026, 4503658.5, 6005670.5, 200, 200),
(3, 'Mecklenburg-Vorpommern', 'uebersicht_mv.png', 2398, 4405000, 5880000, 4662000, 6070000, 205, 146),
(4, 'Neubrandenbrug', 'uebersicht_ndbg.png', 2398, 4561155, 5912500, 4607345, 5960690, 180, 172),
(5, 'Thueringen', 'thueringen.png', 2398, 4316874, 5522397, 4564766, 5783646, 100, 87),
(6, 'Rendsburg-Eckernförde', 'uebersicht_rdeck.png', 2398, 3538560, 6013150, 3547680, 6024150, 200, 239),
(7, 'Mecklenburg-Strelitz', 'MecklenburgStrelitz.png', 2398, 4550300, 5893000, 4618800, 5959300, 200, 194),
(8, 'Brandenburg', 'brandenburg.jpg', 2398, 4438780, 5686966, 4702954, 5936743, 211, 200),
(9, 'Brandenburg', 'uebersicht-ahnatal_klein.png', 2398, 4312573, 5691992, 4324245, 5701082, 210, 202),
(10, 'Zentraleuropa', 'central_europe_200x244.png', 2398, 3259673, 4305062, 5504438, 7024297, 200, 244),
(11, 'Schleswig-Holstein', 'refmap-schleswig-holstein.png', 2398, 3425602, 5914214, 3657022, 6104734, 200, 165);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rolle`
--

CREATE TABLE `rolle` (
  `user_id` int(11) NOT NULL DEFAULT 0,
  `stelle_id` int(11) NOT NULL DEFAULT 0,
  `nImageWidth` int(3) NOT NULL DEFAULT 800,
  `nImageHeight` int(3) NOT NULL DEFAULT 600,
  `auto_map_resize` tinyint(1) NOT NULL DEFAULT 1,
  `minx` double NOT NULL DEFAULT 201165,
  `miny` double NOT NULL DEFAULT 5867815,
  `maxx` double NOT NULL DEFAULT 77900,
  `maxy` double NOT NULL DEFAULT 6081068,
  `nZoomFactor` int(11) NOT NULL DEFAULT 2,
  `selectedButton` varchar(20) NOT NULL DEFAULT 'zoomin',
  `epsg_code` varchar(6) DEFAULT '25833',
  `epsg_code2` varchar(6) DEFAULT NULL,
  `coordtype` enum('dec','dms','dmin') NOT NULL DEFAULT 'dec',
  `active_frame` int(11) DEFAULT NULL,
  `last_time_id` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `gui` varchar(100) NOT NULL DEFAULT 'layouts/gui.php',
  `language` enum('german','low-german','english','polish','vietnamese') NOT NULL DEFAULT 'german',
  `hidemenue` enum('0','1') NOT NULL DEFAULT '0',
  `hidelegend` enum('0','1') NOT NULL DEFAULT '0',
  `tooltipquery` tinyint(1) NOT NULL DEFAULT 0,
  `buttons` varchar(255) DEFAULT 'back,forward,zoomin,zoomout,zoomall,recentre,jumpto,coord_query,query,touchquery,queryradius,polyquery,measure',
  `geom_buttons` varchar(255) DEFAULT 'delete,polygon,flurstquery,polygon2,buffer,transform,vertex_edit,coord_input,ortho_point,measure',
  `scrollposition` int(11) NOT NULL DEFAULT 0,
  `result_color` int(11) DEFAULT 1,
  `result_hatching` tinyint(1) NOT NULL DEFAULT 0,
  `result_transparency` tinyint(4) NOT NULL DEFAULT 60,
  `always_draw` tinyint(1) DEFAULT NULL,
  `runningcoords` tinyint(1) NOT NULL DEFAULT 0,
  `showmapfunctions` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Schaltet die Menüleiste mit den Kartenfunktionen unter der Karte ein oder aus.',
  `showlayeroptions` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Schaltet die Layeroptionen in der Legende ein oder aus.',
  `showrollenfilter` tinyint(1) NOT NULL DEFAULT 0,
  `singlequery` tinyint(1) NOT NULL DEFAULT 1,
  `querymode` tinyint(1) NOT NULL DEFAULT 0,
  `geom_edit_first` tinyint(1) NOT NULL DEFAULT 0,
  `overlayx` int(11) NOT NULL DEFAULT 400,
  `overlayy` int(11) NOT NULL DEFAULT 150,
  `hist_timestamp` timestamp NULL DEFAULT NULL,
  `instant_reload` tinyint(1) NOT NULL DEFAULT 1,
  `menu_auto_close` tinyint(1) NOT NULL DEFAULT 0,
  `layer_params` mediumtext DEFAULT NULL,
  `menue_buttons` tinyint(1) NOT NULL DEFAULT 0,
  `legendtype` tinyint(1) NOT NULL DEFAULT 0,
  `visually_impaired` tinyint(1) NOT NULL DEFAULT 0,
  `font_size_factor` double NOT NULL DEFAULT 1,
  `print_legend_separate` tinyint(1) NOT NULL DEFAULT 0,
  `print_scale` varchar(11) NOT NULL DEFAULT 'auto',
  `immer_weiter_erfassen` tinyint(1) DEFAULT 0,
  `upload_only_file_metadata` tinyint(1) DEFAULT 0,
  `redline_text_color` varchar(7) NOT NULL DEFAULT '#ff0000',
  `redline_font_family` varchar(25) NOT NULL DEFAULT 'Arial',
  `redline_font_size` int(11) NOT NULL DEFAULT 16,
  `redline_font_weight` varchar(25) NOT NULL DEFAULT 'bold',
  `dataset_operations_position` enum('unten','oben','beide') NOT NULL DEFAULT 'unten'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Daten für Tabelle `rolle`
--

INSERT INTO `rolle` (`user_id`, `stelle_id`, `nImageWidth`, `nImageHeight`, `auto_map_resize`, `minx`, `miny`, `maxx`, `maxy`, `nZoomFactor`, `selectedButton`, `epsg_code`, `epsg_code2`, `coordtype`, `active_frame`, `last_time_id`, `gui`, `language`, `hidemenue`, `hidelegend`, `tooltipquery`, `buttons`, `geom_buttons`, `scrollposition`, `result_color`, `result_hatching`, `result_transparency`, `always_draw`, `runningcoords`, `showmapfunctions`, `showlayeroptions`, `showrollenfilter`, `singlequery`, `querymode`, `geom_edit_first`, `overlayx`, `overlayy`, `hist_timestamp`, `instant_reload`, `menu_auto_close`, `layer_params`, `menue_buttons`, `legendtype`, `visually_impaired`, `font_size_factor`, `print_legend_separate`, `print_scale`, `immer_weiter_erfassen`, `upload_only_file_metadata`, `redline_text_color`, `redline_font_family`, `redline_font_size`, `redline_font_weight`, `dataset_operations_position`) VALUES
(1, 1, 886, 580, 1, -536536.80569948, 5230803, 899287.80569948, 6170173, 2, 'zoomin', '25833', '4326', 'dec', 49, '2024-01-14 17:40:56', 'layouts/gui.php', 'german', '0', '0', 1, 'back,forward,zoomin,zoomout,zoomall,recentre,jumpto,coord_query,query,touchquery,queryradius,polyquery,measure,freepolygon,freearrow,freetext,', 'delete,polygon,flurstquery,polygon2,buffer,transform,vertex_edit,coord_input,ortho_point,measure', 2, 6, 0, 60, 0, 1, 1, 1, 0, 0, 0, 0, 400, 150, NULL, 1, 1, '\"jahr\":\"15\",\"geschlecht\":\"g\",\"datenreihe\":\"summe\",\"umzuege\":\"bw_zu\"', 0, 0, 0, 1, 0, 'auto', NULL, NULL, '#ff0000', 'Arial', 16, 'bold', 'unten');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rollenlayer`
--

CREATE TABLE `rollenlayer` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `aktivStatus` enum('0','1','2') NOT NULL,
  `queryStatus` enum('0','1','2') NOT NULL,
  `Name` mediumtext NOT NULL,
  `Gruppe` int(11) NOT NULL,
  `Typ` enum('search','import') NOT NULL DEFAULT 'search',
  `Datentyp` int(11) NOT NULL,
  `Data` longtext NOT NULL,
  `query` mediumtext DEFAULT NULL,
  `connectiontype` int(11) NOT NULL,
  `connection` varchar(255) DEFAULT NULL,
  `connection_id` bigint(20) UNSIGNED DEFAULT NULL,
  `epsg_code` int(11) NOT NULL,
  `transparency` int(11) NOT NULL,
  `buffer` int(11) DEFAULT NULL,
  `labelitem` varchar(100) DEFAULT NULL,
  `classitem` varchar(100) DEFAULT NULL,
  `gle_view` tinyint(1) NOT NULL DEFAULT 1,
  `rollenfilter` mediumtext DEFAULT NULL,
  `duplicate_from_layer_id` int(11) DEFAULT NULL,
  `duplicate_criterion` varchar(255) DEFAULT NULL,
  `wms_auth_username` varchar(100) DEFAULT NULL,
  `wms_auth_password` varchar(50) DEFAULT NULL,
  `autodelete` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rolle_csv_attributes`
--

CREATE TABLE `rolle_csv_attributes` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `attributes` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rolle_export_settings`
--

CREATE TABLE `rolle_export_settings` (
  `stelle_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `layer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `format` varchar(11) NOT NULL,
  `epsg` int(6) DEFAULT NULL,
  `attributes` text NOT NULL,
  `metadata` tinyint(1) DEFAULT NULL,
  `groupnames` tinyint(1) DEFAULT NULL,
  `documents` tinyint(1) DEFAULT NULL,
  `geom` longtext DEFAULT NULL,
  `within` tinyint(1) DEFAULT NULL,
  `singlegeom` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rolle_last_query`
--

CREATE TABLE `rolle_last_query` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `go` varchar(50) NOT NULL,
  `layer_id` int(11) NOT NULL,
  `sql` longtext NOT NULL,
  `orderby` mediumtext DEFAULT NULL,
  `limit` int(11) DEFAULT NULL,
  `offset` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rolle_nachweise`
--

CREATE TABLE `rolle_nachweise` (
  `user_id` int(11) NOT NULL DEFAULT 0,
  `stelle_id` int(11) NOT NULL DEFAULT 0,
  `suchffr` char(1) NOT NULL DEFAULT '0',
  `suchkvz` char(1) NOT NULL DEFAULT '0',
  `suchgn` char(1) NOT NULL DEFAULT '0',
  `suchan` char(1) NOT NULL DEFAULT '0',
  `abfrageart` varchar(10) NOT NULL DEFAULT '',
  `suchgemarkung` varchar(10) NOT NULL DEFAULT '',
  `suchflur` varchar(3) NOT NULL,
  `suchstammnr` varchar(15) NOT NULL,
  `suchrissnr` varchar(20) NOT NULL,
  `suchfortf` int(4) DEFAULT NULL,
  `suchpolygon` mediumtext NOT NULL,
  `suchantrnr` varchar(11) NOT NULL DEFAULT '',
  `showffr` char(1) NOT NULL DEFAULT '0',
  `showkvz` char(1) NOT NULL DEFAULT '0',
  `showgn` char(1) NOT NULL DEFAULT '0',
  `showan` char(1) NOT NULL DEFAULT '0',
  `markffr` char(1) NOT NULL DEFAULT '0',
  `markkvz` char(1) NOT NULL DEFAULT '0',
  `markgn` char(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rolle_saved_layers`
--

CREATE TABLE `rolle_saved_layers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `layers` mediumtext NOT NULL,
  `query` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `search_attributes2rolle`
--

CREATE TABLE `search_attributes2rolle` (
  `name` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `layer_id` int(11) NOT NULL,
  `attribute` varchar(50) NOT NULL,
  `operator` varchar(11) NOT NULL,
  `value1` mediumtext DEFAULT NULL,
  `value2` mediumtext DEFAULT NULL,
  `searchmask_number` int(11) NOT NULL DEFAULT 0,
  `searchmask_operator` enum('AND','OR') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stelle`
--

CREATE TABLE `stelle` (
  `ID` int(11) NOT NULL,
  `Bezeichnung` varchar(255) NOT NULL DEFAULT '',
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
  `minzoom` int(11) NOT NULL DEFAULT 8,
  `epsg_code` int(5) NOT NULL DEFAULT 2398,
  `Referenzkarte_ID` int(11) DEFAULT NULL,
  `Authentifizierung` enum('0','1') NOT NULL DEFAULT '1',
  `ALB_status` enum('30','35') NOT NULL DEFAULT '30',
  `wappen` varchar(255) DEFAULT NULL,
  `wappen_link` varchar(255) DEFAULT NULL,
  `logconsume` enum('0','1') DEFAULT NULL,
  `ows_namespace` varchar(100) DEFAULT NULL,
  `ows_title` varchar(255) DEFAULT NULL,
  `wms_accessconstraints` varchar(255) DEFAULT NULL,
  `ows_abstract` varchar(255) DEFAULT NULL,
  `ows_contactperson` varchar(255) DEFAULT NULL,
  `ows_contactorganization` varchar(255) DEFAULT NULL,
  `ows_contactemailaddress` varchar(255) DEFAULT NULL,
  `ows_contactposition` varchar(255) DEFAULT NULL,
  `ows_contactvoicephone` varchar(100) DEFAULT NULL,
  `ows_contactfacsimile` varchar(100) DEFAULT NULL,
  `ows_contactaddress` varchar(100) DEFAULT NULL,
  `ows_contactpostalcode` varchar(100) DEFAULT NULL,
  `ows_contactcity` varchar(100) DEFAULT NULL,
  `ows_contactadministrativearea` varchar(100) DEFAULT NULL,
  `ows_contentorganization` varchar(150) DEFAULT NULL,
  `ows_contentemailaddress` varchar(100) DEFAULT NULL,
  `ows_distributionperson` varchar(100) DEFAULT NULL,
  `ows_updatesequence` varchar(100) DEFAULT NULL,
  `ows_distributionposition` varchar(100) DEFAULT NULL,
  `ows_distributionvoicephone` varchar(100) DEFAULT NULL,
  `ows_distributionfacsimile` varchar(100) DEFAULT NULL,
  `ows_distributionaddress` varchar(100) DEFAULT NULL,
  `ows_distributionpostalcode` varchar(100) DEFAULT NULL,
  `ows_distributioncity` varchar(100) DEFAULT NULL,
  `ows_distributionadministrativearea` varchar(100) DEFAULT NULL,
  `ows_contentperson` varchar(100) DEFAULT NULL,
  `ows_contentposition` varchar(100) DEFAULT NULL,
  `ows_contentvoicephone` varchar(100) DEFAULT NULL,
  `ows_contentfacsimile` varchar(100) DEFAULT NULL,
  `ows_contentaddress` varchar(100) DEFAULT NULL,
  `ows_contentpostalcode` varchar(100) DEFAULT NULL,
  `ows_contentcity` varchar(100) DEFAULT NULL,
  `ows_contentadministrativearea` varchar(100) DEFAULT NULL,
  `ows_geographicdescription` varchar(100) DEFAULT NULL,
  `ows_distributionorganization` varchar(150) DEFAULT NULL,
  `ows_distributionemailaddress` varchar(100) DEFAULT NULL,
  `ows_fees` varchar(255) DEFAULT NULL,
  `ows_srs` varchar(255) DEFAULT NULL,
  `protected` enum('0','1') NOT NULL DEFAULT '0',
  `check_client_ip` enum('0','1') NOT NULL DEFAULT '0',
  `check_password_age` enum('0','1') NOT NULL DEFAULT '0',
  `allowed_password_age` tinyint(4) NOT NULL DEFAULT 6,
  `use_layer_aliases` enum('0','1') NOT NULL DEFAULT '0',
  `selectable_layer_params` mediumtext DEFAULT NULL,
  `hist_timestamp` tinyint(1) NOT NULL DEFAULT 0,
  `default_user_id` int(11) DEFAULT NULL COMMENT 'Nutzer Id der default Rolle. Die Einstellungen dieser Rolle werden für das Anlegen neuer Rollen für diese Stelle verwendet. Ist dieser Wert nicht angegeben oder die angegebene Rolle existiert nicht, werden die Defaultwerte der Rollenoptionen bei der Zuordnung eines Nutzers zu dieser Stelle verwendet. Die Angabe ist nützlich, wenn die Einstellungen in Gaststellen am Anfang immer gleich sein sollen.',
  `style` varchar(100) DEFAULT NULL,
  `show_shared_layers` tinyint(1) DEFAULT 0,
  `version` varchar(10) NOT NULL DEFAULT '1.0.0',
  `reset_password_text` text DEFAULT NULL,
  `invitation_text` text DEFAULT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 PACK_KEYS=0;

--
-- Daten für Tabelle `stelle`
--

INSERT INTO `stelle` (`ID`, `Bezeichnung`, `Bezeichnung_low-german`, `Bezeichnung_english`, `Bezeichnung_polish`, `Bezeichnung_vietnamese`, `start`, `stop`, `minxmax`, `minymax`, `maxxmax`, `maxymax`, `minzoom`, `epsg_code`, `Referenzkarte_ID`, `Authentifizierung`, `ALB_status`, `wappen`, `wappen_link`, `logconsume`, `ows_namespace`, `ows_title`, `wms_accessconstraints`, `ows_abstract`, `ows_contactperson`, `ows_contactorganization`, `ows_contactemailaddress`, `ows_contactposition`, `ows_contactvoicephone`, `ows_contactfacsimile`, `ows_contactaddress`, `ows_contactpostalcode`, `ows_contactcity`, `ows_contactadministrativearea`, `ows_contentorganization`, `ows_contentemailaddress`, `ows_distributionperson`, `ows_updatesequence`, `ows_distributionposition`, `ows_distributionvoicephone`, `ows_distributionfacsimile`, `ows_distributionaddress`, `ows_distributionpostalcode`, `ows_distributioncity`, `ows_distributionadministrativearea`, `ows_contentperson`, `ows_contentposition`, `ows_contentvoicephone`, `ows_contentfacsimile`, `ows_contentaddress`, `ows_contentpostalcode`, `ows_contentcity`, `ows_contentadministrativearea`, `ows_geographicdescription`, `ows_distributionorganization`, `ows_distributionemailaddress`, `ows_fees`, `ows_srs`, `protected`, `check_client_ip`, `check_password_age`, `allowed_password_age`, `use_layer_aliases`, `selectable_layer_params`, `hist_timestamp`, `default_user_id`, `style`, `show_shared_layers`, `version`, `reset_password_text`, `invitation_text`, `comment`) VALUES
(1, 'Administration', NULL, NULL, NULL, NULL, '0000-00-00', '0000-00-00', -174807, 5230803, 537558, 6170173, 8, 25833, 3  , '1', '30', 'Logo_GDI-Service_200x47.png', '', NULL, 'gdi', 'kvwmap-Demo Server', 'keine', 'Demoversion eines Web Service zur Bereitstellung von Geodaten aus den Bereichen des Katasters, der Landkreise', 'Peter Korduan', 'GDI-Service Rostock', 'peter.korduan@gdi-service.de', 'Geschäftsführer', '+49 381 403 44444', '+49 381 3378 9527', 'Friedrichstraße 16', '18057', 'Rostock', 'Mecklenburg-Vorpommern', '21', '22', '33', NULL, '34', '35', '36', '37', '38', '39', '3.10', '24', '25', '26', '27', '28', '29', '2.10', '2.11', '23', '31', '32', 'für Testzwecke frei', 'EPSG:25832 EPSG:25833 EPSG:4326 EPSG:2398', '0', '0', '1', 6, '1', '', 0, NULL, NULL, 0, '1.0.0', '', '', 'Test 2');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stellen_hierarchie`
--

CREATE TABLE `stellen_hierarchie` (
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `child_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stelle_gemeinden`
--

CREATE TABLE `stelle_gemeinden` (
  `Stelle_ID` int(11) NOT NULL DEFAULT 0,
  `Gemeinde_ID` int(8) NOT NULL DEFAULT 0,
  `Gemarkung` int(6) DEFAULT NULL,
  `Flur` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `styles`
--

CREATE TABLE `styles` (
  `Style_ID` int(11) NOT NULL,
  `symbol` int(3) DEFAULT NULL,
  `symbolname` mediumtext DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `backgroundcolor` varchar(11) DEFAULT NULL,
  `outlinecolor` varchar(11) DEFAULT NULL,
  `colorrange` varchar(23) DEFAULT NULL,
  `datarange` varchar(255) DEFAULT NULL,
  `rangeitem` varchar(50) DEFAULT NULL,
  `opacity` int(11) DEFAULT NULL,
  `minsize` varchar(50) DEFAULT NULL,
  `maxsize` varchar(50) DEFAULT NULL,
  `minscale` int(11) UNSIGNED DEFAULT NULL,
  `maxscale` int(11) UNSIGNED DEFAULT NULL,
  `angle` int(11) DEFAULT NULL,
  `angleitem` varchar(255) DEFAULT NULL,
  `width` varchar(50) DEFAULT NULL,
  `minwidth` int(11) DEFAULT NULL,
  `maxwidth` int(11) DEFAULT NULL,
  `offsetx` varchar(50) DEFAULT NULL,
  `offsety` varchar(50) DEFAULT NULL,
  `polaroffset` varchar(255) DEFAULT NULL,
  `pattern` varchar(255) DEFAULT NULL,
  `geomtransform` varchar(20) DEFAULT NULL,
  `gap` int(11) DEFAULT NULL,
  `initialgap` decimal(5,2) DEFAULT NULL,
  `linecap` varchar(8) DEFAULT NULL,
  `linejoin` varchar(5) DEFAULT NULL,
  `linejoinmaxsize` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 PACK_KEYS=1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `used_layer`
--

CREATE TABLE `used_layer` (
  `Stelle_ID` int(11) NOT NULL DEFAULT 0,
  `Layer_ID` int(11) NOT NULL DEFAULT 0,
  `group_id` int(11) DEFAULT NULL,
  `queryable` enum('0','1') NOT NULL DEFAULT '1',
  `drawingorder` int(11) NOT NULL DEFAULT 0,
  `legendorder` int(11) DEFAULT NULL,
  `minscale` int(11) DEFAULT NULL,
  `maxscale` int(11) DEFAULT NULL,
  `offsite` varchar(11) DEFAULT NULL,
  `transparency` tinyint(3) DEFAULT NULL,
  `postlabelcache` tinyint(1) NOT NULL DEFAULT 0,
  `Filter` longtext DEFAULT NULL,
  `template` varchar(255) DEFAULT NULL,
  `header` varchar(255) DEFAULT NULL,
  `footer` varchar(255) DEFAULT NULL,
  `symbolscale` int(11) UNSIGNED DEFAULT NULL,
  `logconsume` enum('0','1') DEFAULT NULL,
  `requires` varchar(255) DEFAULT NULL,
  `privileg` enum('0','1','2') NOT NULL DEFAULT '0',
  `export_privileg` tinyint(1) NOT NULL DEFAULT 1,
  `use_parent_privileges` tinyint(1) NOT NULL DEFAULT 1,
  `start_aktiv` enum('0','1') NOT NULL DEFAULT '0',
  `use_geom` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 PACK_KEYS=0;

--
-- Daten für Tabelle `used_layer`
--

INSERT INTO `used_layer` (`Stelle_ID`, `Layer_ID`, `group_id`, `queryable`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`, `template`, `header`, `footer`, `symbolscale`, `logconsume`, `requires`, `privileg`, `export_privileg`, `use_parent_privileges`, `start_aktiv`, `use_geom`) VALUES
(1, 1, NULL, '0', 0, NULL, NULL, NULL, NULL, NULL, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '0', 1, 1, '0', 0),
(1, 2, NULL, '0', 0, NULL, NULL, NULL, NULL, NULL, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '0', 1, 1, '0', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `ID` int(11) NOT NULL,
  `login_name` varchar(100) NOT NULL DEFAULT '',
  `Name` varchar(100) NOT NULL DEFAULT '',
  `Vorname` varchar(100) DEFAULT NULL,
  `Namenszusatz` varchar(50) DEFAULT NULL,
  `passwort` varchar(32) DEFAULT NULL,
  `password` varchar(40) DEFAULT NULL,
  `password_expired` tinyint(1) NOT NULL DEFAULT 0,
  `password_setting_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `userdata_checking_time` timestamp NULL DEFAULT NULL,
  `start` date NOT NULL DEFAULT '0000-00-00',
  `stop` date NOT NULL DEFAULT '0000-00-00',
  `ips` mediumtext DEFAULT NULL,
  `tokens` text DEFAULT NULL,
  `Funktion` enum('admin','user','gast') NOT NULL DEFAULT 'user',
  `stelle_id` int(11) DEFAULT NULL,
  `phon` varchar(25) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `agreement_accepted` tinyint(1) NOT NULL DEFAULT 0,
  `num_login_failed` int(11) NOT NULL DEFAULT 0 COMMENT 'Anzahl der nacheinander fehlgeschlagenen Loginversuche mit diesem login_namen',
  `login_locked_until` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `organisation` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `share_rollenlayer_allowed` tinyint(1) DEFAULT 0,
  `layer_data_import_allowed` tinyint(1) DEFAULT NULL,
  `archived` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 PACK_KEYS=0;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`ID`, `login_name`, `Name`, `Vorname`, `Namenszusatz`, `passwort`, `password`, `password_expired`, `password_setting_time`, `userdata_checking_time`, `start`, `stop`, `ips`, `tokens`, `Funktion`, `stelle_id`, `phon`, `email`, `agreement_accepted`, `num_login_failed`, `login_locked_until`, `organisation`, `position`, `share_rollenlayer_allowed`, `layer_data_import_allowed`, `archived`) VALUES
(1, 'kvwmap', 'Administrator', 'WebGIS', '', '', '10c55af15ef1a6d3caf1b3599eadd3d84e446916', 0, '2024-01-14 13:55:14', NULL, '0000-00-00', '0000-00-00', '', 'f7cef80b665dd06ed1bacb73fd973ea7,e30557ce861fef88148d8a60abad7b94,6e9f0247722341e4fe268ebc0682d276,401846380de8366c6cf5c053b9e0282e,f20033d55a768b5c92832b96f6baa111', 'admin', 1, '038140344445', 'stefan.rahn@gdi-service.de', 1, 0, '2024-01-14 15:24:29', 'GDI-Service', 'kvwmap Entwickler', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user2notifications`
--

CREATE TABLE `user2notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `u_attribute2used_layer`
--

CREATE TABLE `u_attribute2used_layer` (
  `attributename` varchar(25) COLLATE latin1_german2_ci NOT NULL DEFAULT '',
  `type` varchar(20) COLLATE latin1_german2_ci NOT NULL DEFAULT '',
  `layer_id` int(11) NOT NULL,
  `stelle_id` mediumint(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `u_attributfilter2used_layer`
--

CREATE TABLE `u_attributfilter2used_layer` (
  `Stelle_ID` int(11) NOT NULL,
  `Layer_ID` int(11) NOT NULL,
  `attributname` varchar(255) NOT NULL,
  `attributvalue` mediumtext NOT NULL,
  `operator` enum('=','!=','>','<','like','IS','IN','st_within','st_intersects') NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `u_consume`
--

CREATE TABLE `u_consume` (
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
  `next` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Daten für Tabelle `u_consume`
--

INSERT INTO `u_consume` (`user_id`, `stelle_id`, `time_id`, `activity`, `nimagewidth`, `nimageheight`, `epsg_code`, `minx`, `miny`, `maxx`, `maxy`, `prev`, `next`) VALUES
(1, 1, '2024-01-14 17:40:56', 'getMap', 886, 580, '25833', -536536.80569948, 5230803, 899287.80569948, 6170173, '2024-01-14 17:24:48', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `u_consume2comments`
--

CREATE TABLE `u_consume2comments` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL,
  `comment` mediumtext DEFAULT NULL,
  `public` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `u_consume2layer`
--

CREATE TABLE `u_consume2layer` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL,
  `layer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `u_consumeALB`
--

CREATE TABLE `u_consumeALB` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `format` varchar(50) NOT NULL,
  `log_number` varchar(255) NOT NULL,
  `wz` enum('0','1') DEFAULT NULL,
  `numpages` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `u_consumeALK`
--

CREATE TABLE `u_consumeALK` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL,
  `druckrahmen_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `u_consumeCSV`
--

CREATE TABLE `u_consumeCSV` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `art` varchar(20) NOT NULL,
  `numdatasets` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `u_consumeShape`
--

CREATE TABLE `u_consumeShape` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `time_id` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `layer_id` int(11) NOT NULL,
  `numdatasets` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `u_funktion2stelle`
--

CREATE TABLE `u_funktion2stelle` (
  `funktion_id` int(11) NOT NULL DEFAULT 0,
  `stelle_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Daten für Tabelle `u_funktion2stelle`
--

INSERT INTO `u_funktion2stelle` (`funktion_id`, `stelle_id`) VALUES
(67, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `u_funktionen`
--

CREATE TABLE `u_funktionen` (
  `id` int(11) NOT NULL,
  `bezeichnung` varchar(255) NOT NULL DEFAULT '',
  `link` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 PACK_KEYS=0;

--
-- Daten für Tabelle `u_funktionen`
--

INSERT INTO `u_funktionen` (`id`, `bezeichnung`, `link`) VALUES
(1, 'ALB-Auszug 35', NULL),
(68, 'BplanAenderungLoeschen', NULL),
(4, 'FestpunktDateiAktualisieren', NULL),
(5, 'FestpunktDateiUebernehmen', NULL),
(6, 'Antrag_loeschen', NULL),
(66, 'Haltestellen_Suche', NULL),
(8, 'Nachweisanzeige_zum_Auftrag_hinzufuegen', NULL),
(9, 'Antrag_Aendern', NULL),
(10, 'FestpunkteSkizzenZuordnung_Senden', NULL),
(12, 'Nachweisanzeige_aus_Auftrag_entfernen', NULL),
(13, 'ohneWasserzeichen', NULL),
(14, 'Flurstueck_Anzeigen', NULL),
(15, 'Bauakteneinsicht', NULL),
(16, 'Namensuche', NULL),
(69, 'migrationGewaesser', NULL),
(18, 'ALB-Auszug 40', NULL),
(67, 'Stelle_waehlen', NULL),
(21, 'Nachweisloeschen', NULL),
(22, 'ALB-Auszug 20', NULL),
(23, 'ALB-Auszug 25', NULL),
(24, 'Externer_Druck', NULL),
(26, 'Adressaenderungen', NULL),
(29, 'sendeFestpunktskizze', NULL),
(65, 'Jagdkataster', NULL),
(59, 'Nachweise_bearbeiten', NULL),
(60, 'ALB-Auszug 30', NULL),
(70, 'upload_temp_file', NULL),
(71, 'pack_and_mail', NULL),
(2, 'Daten_Export', NULL),
(72, 'cronjobs_anzeigen', NULL),
(73, 'mobile_delete_images', NULL),
(74, 'mobile_download_image', NULL),
(75, 'mobile_upload_image', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `u_groups`
--

CREATE TABLE `u_groups` (
  `id` int(11) NOT NULL,
  `Gruppenname` varchar(255) NOT NULL,
  `Gruppenname_low-german` varchar(100) DEFAULT NULL,
  `Gruppenname_english` varchar(100) DEFAULT NULL,
  `Gruppenname_polish` varchar(100) DEFAULT NULL,
  `Gruppenname_vietnamese` varchar(100) DEFAULT NULL,
  `obergruppe` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `selectable_for_shared_layers` tinyint(1) NOT NULL DEFAULT 0,
  `icon` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Daten für Tabelle `u_groups`
--

INSERT INTO `u_groups` (`id`, `Gruppenname`, `Gruppenname_low-german`, `Gruppenname_english`, `Gruppenname_polish`, `Gruppenname_vietnamese`, `obergruppe`, `order`, `selectable_for_shared_layers`, `icon`) VALUES
(5, 'Kataster', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(2, 'Test', '', '', '', '', NULL, NULL, 0, ''),
(13, 'Gebietskarten', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(14, 'Orthophotos', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(31, 'eigene Abfragen', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(32, 'Übersichtskarten', '', '', '', '', NULL, 100, 0, ''),
(45, 'Eigene Shapes', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(54, 'Freizeit', '', '', '', '', NULL, NULL, 0, ''),
(1, 'Umwelt', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(60, 'Eigene Importe', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL),
(61, 'Facilities', '', '', '', '', NULL, NULL, 0, ''),
(63, 'Pläne', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `u_groups2rolle`
--

CREATE TABLE `u_groups2rolle` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Daten für Tabelle `u_groups2rolle`
--

INSERT INTO `u_groups2rolle` (`user_id`, `stelle_id`, `id`, `status`) VALUES
(1, 1, 1, 0),
(1, 1, 2, 0),
(1, 1, 5, 1),
(1, 1, 10, 1),
(1, 1, 13, 0),
(1, 1, 14, 0),
(1, 1, 20, 0),
(1, 1, 31, 1),
(1, 1, 32, 1),
(1, 1, 54, 1),
(1, 1, 57, 0),
(1, 1, 59, 0),
(1, 1, 60, 1),
(1, 1, 61, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `u_labels2classes`
--

CREATE TABLE `u_labels2classes` (
  `class_id` int(11) NOT NULL DEFAULT 0,
  `label_id` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Daten für Tabelle `u_labels2classes`
--

INSERT INTO `u_labels2classes` (`class_id`, `label_id`) VALUES
(4517, 294),
(4518, 295);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `u_menue2rolle`
--

CREATE TABLE `u_menue2rolle` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `menue_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Daten für Tabelle `u_menue2rolle`
--

INSERT INTO `u_menue2rolle` (`user_id`, `stelle_id`, `menue_id`, `status`) VALUES
(1, 1, 4, 0),
(1, 1, 7, 0),
(1, 1, 17, 0),
(1, 1, 20, 0),
(1, 1, 21, 0),
(1, 1, 22, 0),
(1, 1, 25, 0),
(1, 1, 27, 0),
(1, 1, 28, 0),
(1, 1, 29, 0),
(1, 1, 30, 0),
(1, 1, 35, 0),
(1, 1, 39, 0),
(1, 1, 42, 0),
(1, 1, 45, 0),
(1, 1, 46, 0),
(1, 1, 47, 0),
(1, 1, 49, 0),
(1, 1, 50, 0),
(1, 1, 51, 0),
(1, 1, 63, 0),
(1, 1, 64, 0),
(1, 1, 65, 0),
(1, 1, 66, 0),
(1, 1, 72, 0),
(1, 1, 73, 0),
(1, 1, 74, 0),
(1, 1, 76, 0),
(1, 1, 77, 0),
(1, 1, 78, 0),
(1, 1, 79, 0),
(1, 1, 126, 0),
(1, 1, 141, 0),
(1, 1, 142, 0),
(1, 1, 143, 0),
(1, 1, 144, 0),
(1, 1, 147, 0),
(1, 1, 148, 0),
(1, 1, 149, 0),
(1, 1, 151, 0),
(1, 1, 152, 0),
(1, 1, 153, 0),
(1, 1, 154, 0),
(1, 1, 174, 0),
(1, 1, 186, 0),
(1, 1, 215, 0),
(1, 1, 216, 0),
(1, 1, 239, 0),
(1, 1, 241, 0),
(1, 1, 251, 0),
(1, 1, 269, 0),
(1, 1, 274, 0),
(1, 1, 301, 0),
(1, 1, 303, 0),
(1, 1, 305, 0),
(1, 1, 306, 0),
(1, 1, 312, 0),
(1, 1, 314, 0),
(1, 1, 315, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `u_menue2stelle`
--

CREATE TABLE `u_menue2stelle` (
  `stelle_id` int(11) NOT NULL DEFAULT 0,
  `menue_id` int(11) NOT NULL DEFAULT 0,
  `menue_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Daten für Tabelle `u_menue2stelle`
--

INSERT INTO `u_menue2stelle` (`stelle_id`, `menue_id`, `menue_order`) VALUES
(1, 4, 50),
(1, 7, 57),
(1, 17, 0),
(1, 20, 11),
(1, 21, 12),
(1, 22, 13),
(1, 25, 7),
(1, 27, 8),
(1, 28, 3),
(1, 29, 56),
(1, 30, 49),
(1, 35, 55),
(1, 39, 43),
(1, 42, 58),
(1, 45, 19),
(1, 46, 21),
(1, 47, 22),
(1, 49, 9),
(1, 50, 23),
(1, 51, 29),
(1, 63, 47),
(1, 64, 45),
(1, 65, 20),
(1, 66, 27),
(1, 72, 32),
(1, 73, 33),
(1, 74, 53),
(1, 76, 4),
(1, 77, 5),
(1, 78, 40),
(1, 79, 42),
(1, 126, 26),
(1, 141, 37),
(1, 142, 39),
(1, 143, 38),
(1, 144, 34),
(1, 147, 6),
(1, 148, 14),
(1, 149, 35),
(1, 151, 2),
(1, 152, 1),
(1, 153, 51),
(1, 154, 44),
(1, 174, 41),
(1, 186, 24),
(1, 215, 52),
(1, 216, 54),
(1, 239, 31),
(1, 241, 17),
(1, 251, 46),
(1, 269, 16),
(1, 274, 18),
(1, 301, 30),
(1, 303, 10),
(1, 305, 25),
(1, 306, 48),
(1, 312, 15),
(1, 314, 28),
(1, 315, 36);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `u_menues`
--

CREATE TABLE `u_menues` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `name_low-german` varchar(100) DEFAULT NULL,
  `name_english` varchar(100) DEFAULT NULL,
  `name_polish` varchar(100) DEFAULT NULL,
  `name_vietnamese` varchar(100) DEFAULT NULL,
  `links` varchar(2000) DEFAULT NULL,
  `onclick` mediumtext DEFAULT NULL COMMENT 'JavaScript welches beim Klick auf den Menüpunkt ausgeführt werden soll.',
  `obermenue` int(11) NOT NULL DEFAULT 0,
  `menueebene` tinyint(4) NOT NULL DEFAULT 1,
  `target` varchar(10) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `title` mediumtext DEFAULT NULL,
  `button_class` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 PACK_KEYS=0;

--
-- Daten für Tabelle `u_menues`
--

INSERT INTO `u_menues` (`id`, `name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `onclick`, `obermenue`, `menueebene`, `target`, `order`, `title`, `button_class`) VALUES
(1, 'Gesamtansicht', 'Samtansicht', 'Full Extent', '', 'Gesamtansicht', 'index.php?go=Full_Extent', '', 0, 1, '', 20, '', 'gesamtansicht'),
(2, 'Karte anzeigen', 'Koort ankieken', 'Show Map', '', 'Hiá»ƒn thá»‹ báº£n Ä‘á»“', 'index.php?', '', 0, 1, '', 1, '', 'karte'),
(4, 'Flurst&uuml;cke', 'Flurst&uuml;cke', 'Land&nbsp;Parcel', NULL, 'Flurst&uuml;cke', 'index.php?go=Flurstueck_Auswaehlen', NULL, 30, 2, NULL, 0, NULL, NULL),
(6, 'Dokumentenrecherche', 'Oorkunn-Sööke', 'document retrieval', NULL, NULL, 'index.php?go=Nachweisrechercheformular', NULL, 16, 2, NULL, 0, NULL, NULL),
(7, 'Hilfe', 'Hülp', 'Hilfe', '', 'Trá»£ giÃºp', 'index.php?go=changemenue', '', 0, 1, '', 1000, '', ''),
(8, 'Dokument&nbsp;einf&uuml;gen', 'Oorkunn&nbsp;inf&ouml;gen', 'Dokument&nbsp;einf&uuml;gen', NULL, 'Dokument&nbsp;einf&uuml;gen', 'index.php?go=Nachweisformular', NULL, 16, 2, NULL, 0, NULL, NULL),
(13, 'Fachschalen', 'Fachschaals', 'Application Modules', NULL, 'CÃ¡c modul khÃ¡c', 'index.php?go=changemenue', NULL, 0, 1, NULL, 90, NULL, NULL),
(16, 'Nachweisverwaltung', 'Nahwies-Verwalten', 'Nachweisverwaltung', NULL, 'Quáº£n lÃ½ tÃ i liá»‡u', 'index.php?go=changemenue', NULL, 0, 1, NULL, 40, NULL, NULL),
(17, 'Import/Export', 'Import/Export', 'Import/Export', NULL, 'Nháº­p/Xuáº¥t', 'index.php?go=changemenue', NULL, 0, 1, NULL, 60, NULL, NULL),
(20, 'Nutzerverwaltung', 'Nutzerverwaltung', 'User Management', NULL, 'Quáº£n trá»‹ ngÆ°á»i dÃ¹ng', 'index.php?go=changemenue', NULL, 0, 1, NULL, 70, NULL, NULL),
(21, 'Nutzer anlegen', 'Nutzer anlegen', 'Create new User', NULL, 'Nutzer anlegen', 'index.php?go=Benutzerdaten_Formular', NULL, 20, 2, NULL, 0, NULL, NULL),
(22, 'Nutzer anzeigen', 'Nutzer anzeigen', 'List all Users', NULL, 'Danh sÃ¡ch ngÆ°á»i dÃ¹ng', 'index.php?go=Benutzerdaten_Anzeigen&order=ID', NULL, 20, 2, NULL, 0, NULL, NULL),
(24, 'Aktualisieren', 'Aktualisieren', 'Aktualisieren', NULL, 'Aktualisieren', 'index.php?go=FestpunktDateiAktualisieren', NULL, 23, 2, NULL, 0, NULL, NULL),
(25, 'WMC-Export', 'WMC-Export', 'WMC-Export', NULL, 'WMC-Export', 'index.php?go=exportWMC', NULL, 17, 2, NULL, 0, NULL, NULL),
(27, 'WMS-Export', 'WMS-Export', 'WMS-Export', NULL, 'Xuáº¥t WMS', 'index.php?go=WMS_Export', NULL, 17, 2, NULL, 0, NULL, NULL),
(28, 'PDF-Export', 'PDF-Export', 'PDF-Export', NULL, 'Xuáº¥t PDF', 'index.php?go=ExportMapToPDF', NULL, 17, 2, '_blank', 0, NULL, NULL),
(29, 'Namen', 'Namen', 'Names', NULL, 'TÃªn', 'index.php?go=Namen_Auswaehlen', NULL, 30, 2, NULL, 0, NULL, NULL),
(30, 'Suche', 'Suche', 'Search', NULL, 'TÃ¬m kiáº¿m', 'index.php?go=changemenue', NULL, 0, 1, NULL, 150, NULL, NULL),
(34, 'Metadateneingabe', 'Metadateneingabe', 'Metadateneingabe', NULL, 'Metadateneingabe', 'index.php?go=Metadateneingabe', NULL, 13, 2, NULL, 0, NULL, NULL),
(35, 'Metadaten', 'Metadaten', 'Metadata', NULL, 'Metadaten', 'index.php?go=Metadaten_Auswaehlen', NULL, 30, 2, NULL, 0, NULL, NULL),
(37, 'Suche', 'Suche', 'Suche', NULL, 'TÃ¬m kiáº¿m', 'index.php?go=Bauauskunft_Suche', NULL, 36, 2, NULL, 0, NULL, NULL),
(39, 'Druckrahmeneditor', 'Druckrahmeneditor', 'Print Layout Editor', NULL, 'Druckrahmen', 'index.php?go=Druckrahmen', NULL, 78, 2, NULL, 0, NULL, NULL),
(42, 'Dokumentationen', 'Dokumentationen', 'Documentation', '', '', 'https://kvwmap.de/wiki/index.php/dokumentation', '', 7, 2, '_blank', 10, 'Dokumentation', ''),
(44, 'neue Notiz', 'neue Notiz', 'neue Notiz', NULL, 'neue Notiz', 'index.php?go=Notizenformular', NULL, 67, 2, NULL, 0, NULL, 'notiz'),
(45, 'Stellenverwaltung', 'Stellenverwaltung', 'Task&nbsp;Management', NULL, 'Quáº£n lÃ½ tÃ¡c vá»¥', 'index.php?go=changemenue', NULL, 0, 1, NULL, 80, NULL, NULL),
(46, 'Stelle anlegen', 'Stelle anlegen', 'Create new Task', NULL, 'Stelle anlegen', 'index.php?go=Stelleneditor', NULL, 45, 2, NULL, 0, NULL, NULL),
(47, 'Stellen anzeigen', 'Stellen anzeigen', 'List all Tasks', NULL, 'Danh sÃ¡ch tÃ¡c vá»¥', 'index.php?go=Stellen_Anzeigen', NULL, 45, 2, NULL, 0, NULL, NULL),
(49, 'WMS-Import', 'WMS-Import', 'WMS-Import', NULL, 'WMS-Import', 'index.php?go=WMS_Import', NULL, 17, 2, NULL, 0, NULL, NULL),
(50, 'Layerverwaltung', 'Layerverwaltung', 'Layer Management', NULL, 'Quáº£n lÃ½ cÃ¡c lá»›p thÃ´ng tin', 'index.php?go=changemenue', NULL, 0, 1, NULL, 82, NULL, NULL),
(51, 'Layer erstellen', 'Layer erstellen', 'Create new Layer', NULL, 'Layer erstellen', 'index.php?go=Layereditor', NULL, 50, 2, NULL, 12, NULL, NULL),
(63, 'Programmverwaltung', 'Programmverwaltung', 'Programmverwaltung', '', 'Programmverwaltung', 'index.php?go=Administratorfunktionen', '', 64, 2, '', 0, '', ''),
(64, 'Admin-Funktionen', 'Admin-Funktionen', 'Admin-Functions', '', '', 'index.php?go=changemenue', '', 0, 1, '', 120, '', ''),
(65, 'Filterverwaltung', 'Filterverwaltung', 'Filter&nbsp;Management', NULL, 'Quáº£n lÃ½ chiáº¿t lá»c thÃ´ng tin', 'index.php?go=Filterverwaltung', NULL, 45, 2, NULL, 0, NULL, NULL),
(66, 'Layer aus Mapdatei', 'Layer aus Mapdatei', 'Layer from Mapfile', NULL, 'Layer aus Mapdatei', 'index.php?go=layerfrommapfile', NULL, 50, 2, NULL, 7, NULL, NULL),
(67, 'Notizen', 'Notizen', 'Notizen', NULL, 'ThÃ´ng bÃ¡o', 'index.php?go=changemenue', NULL, 0, 1, NULL, 110, NULL, NULL),
(72, 'Layereditor', '', '', '', '', 'index.php?go=Layereditor', '', 50, 2, '', 16, '', ''),
(73, 'Attribut-Editor', 'Attribut-Editor', 'Attribute-Editor', '', 'Attribut-Editor', 'index.php?go=Attributeditor', '', 50, 2, '', 17, '', ''),
(74, 'Layer-Suche', 'Layer-Suche', 'Layer-Search', NULL, 'TÃ¬m lá»›p thÃ´ng tin', 'index.php?go=Layer-Suche&titel=Layer-Suche', NULL, 30, 2, NULL, 0, NULL, NULL),
(76, 'Shape-Export', 'Shape-Export', 'Shape-Export', NULL, 'Xuáº¥t file Shape', 'index.php?go=SHP_Export', NULL, 17, 2, NULL, 0, NULL, NULL),
(77, 'Shape-Import', 'Shape-Import', 'Shape-Import', NULL, 'Nháº­p file Shape', 'index.php?go=SHP_Import', NULL, 17, 2, NULL, 0, NULL, NULL),
(78, 'Drucken', 'Drucken', 'Print', NULL, 'In', 'index.php?go=changemenue', NULL, 0, 1, NULL, 100, NULL, NULL),
(79, 'Druckausschnittswahl', 'Druckausschnittswahl', 'Select Print Extent', NULL, 'Druckausschnittswahl', '#', 'printMap();', 78, 2, NULL, 0, NULL, 'drucken'),
(81, 'Neue Ausgabe', '', '', '', '', 'index.php?go=neuer_Layer_Datensatz&selected_layer_id=745', '', 295, 2, '', 42, '', ''),
(126, 'Datensatz hinzuf&uuml;gen', NULL, '', NULL, NULL, 'index.php?go=neuer_Layer_Datensatz', NULL, 50, 2, NULL, 6, NULL, NULL),
(128, 'Personen suchen', 'Personen suchen', 'Personen suchen', NULL, 'TÃ¬m kiáº¿m cÃ¡ nhÃ¢n', 'index.php?go=Layer-Suche&selected_layer_id=137', NULL, 127, 2, NULL, 0, NULL, NULL),
(133, 'neue Person', 'neue Person', 'neue Person', NULL, 'NgÆ°á»i má»›i', 'index.php?go=neuer_Layer_Datensatz&selected_layer_id=137', NULL, 127, 2, NULL, 0, NULL, NULL),
(138, 'Polygon zeichnen', 'Polygon zeichnen', 'Draw Polygon', NULL, 'Polygon zeichnen', 'index.php?go=neuer_Layer_Datensatz&selected_layer_id=159', NULL, 13, 2, NULL, 0, NULL, NULL),
(141, 'Funktionenverwaltung', 'Funktionenverwaltung', NULL, NULL, NULL, 'index.php?go=changemenue', NULL, 0, 1, NULL, 90, NULL, NULL),
(142, 'Funktionen anzeigen', 'Funktionen anzeigen', '', '', '', 'index.php?go=Funktionen_Anzeigen', '', 141, 2, '', 20, '', ''),
(143, 'Funktion anlegen', 'Funktion anlegen', '', '', '', 'index.php?go=Funktionen_Formular', '', 141, 2, '', 10, '', ''),
(144, 'Style-u.Labeleditor', 'Style-u.Labeleditor', 'Style&Label Editor', '', '', 'index.php?go=Style_Label_Editor', '', 50, 2, '', 18, '', ''),
(145, 'Synchronisation', 'Synchronisation', NULL, NULL, NULL, 'index.php?go=changemenue', NULL, 0, 1, NULL, 190, NULL, NULL),
(147, 'TIF-Export', 'TIF-Export', 'TIF-Export', NULL, NULL, 'index.php?go=TIF_Export', NULL, 17, 2, NULL, 0, NULL, NULL),
(148, 'Nutzer&uuml;bersicht', 'Nutzer&uuml;bersicht', NULL, NULL, NULL, 'index.php?go=BenutzerStellen_Anzeigen', NULL, 20, 2, NULL, 0, NULL, NULL),
(149, 'Layer-Export', 'Layer-Export', NULL, NULL, NULL, 'index.php?go=Layer_Export', NULL, 50, 2, NULL, 20, NULL, NULL),
(150, 'Optionen', 'Optionen', 'Options', '', 'Options', 'index.php?go=Stelle_waehlen', '', 0, 1, '', 1, '', 'optionen'),
(151, 'GPX-Import', NULL, 'GPX-Import', NULL, NULL, 'index.php?go=GPX_Import', NULL, 17, 2, NULL, 0, NULL, NULL),
(152, 'Daten Import', '', '', '', '', 'index.php?go=Daten_Import', '', 17, 2, '', 0, '', ''),
(153, 'Flurst&uuml;cke(ALK)', NULL, NULL, NULL, NULL, 'index.php?go=ALK-Flurstueck_Auswaehlen', NULL, 30, 2, NULL, 0, NULL, NULL),
(154, 'schnelle Druckausgabe', NULL, NULL, NULL, NULL, '#', 'printMapFast();', 78, 2, NULL, 0, NULL, 'schnelldruck'),
(163, 'neuer Datensatz', NULL, NULL, NULL, NULL, 'index.php?go=changemenue', NULL, 0, 1, NULL, 100, NULL, NULL),
(174, 'Datendruck-Layouteditor', NULL, NULL, NULL, NULL, 'index.php?go=sachdaten_druck_editor', NULL, 78, 2, NULL, 0, NULL, NULL),
(175, 'Metadaten', NULL, NULL, NULL, NULL, 'index.php?go=changemenue', NULL, 0, 1, NULL, 90, NULL, NULL),
(176, 'Metadatenerfassung', NULL, NULL, NULL, NULL, 'index.php?go=Metadaten_Uebersicht', NULL, 175, 2, NULL, 0, NULL, NULL),
(177, 'Metadatenrecherche', NULL, NULL, NULL, NULL, 'index.php?go=Metadaten_Recherche', NULL, 175, 2, NULL, 0, NULL, NULL),
(186, 'Themen&uuml;bersicht', NULL, NULL, NULL, NULL, 'index.php?go=Layer_Uebersicht', NULL, 50, 2, NULL, 1, NULL, NULL),
(209, 'letzte Abfrage aufrufen', NULL, NULL, NULL, NULL, 'javascript:void(0)', 'overlay_link(\'go=get_last_query\', true)', 0, 1, NULL, 25, NULL, NULL),
(212, 'Pflegeverträge', '', '', '', '', 'javascript:void(0)', 'overlay_link(\'go=Layer-Suche_Suchen&selected_layer_id=749&anzahl=100\', true)', 80, 2, '', 47, '', ''),
(215, 'Gespeicherte Suchanfragen', 'Gespeicherte Suchanfragen', 'Saved search requests', NULL, '', 'index.php?go=Suchabfragen_auflisten', NULL, 30, 2, NULL, 0, NULL, NULL),
(216, 'Letztes Suchergebnis', 'Letztes Suchergebnis', 'Last search result', NULL, '', 'javascript:void(0)', 'overlay_link(\'go=get_last_query\', true)', 30, 2, NULL, 0, NULL, NULL),
(221, 'Neue Reisekosten', '', '', '', '', 'index.php?go=neuer_Layer_Datensatz&selected_layer_id=800', '', 296, 2, '', 45, '', ''),
(239, 'Datentypen anzeigen', 'Datentypen anzeigen', 'List all Datatypes', 'Wyświetl typy danych', 'Hiển thị dữ liệu loại', 'index.php?go=Datentypen_Anzeigen', '', 50, 2, '', 14, '', ''),
(241, 'Menüverwaltung', 'Menüverwaltung', 'Menu Management', '', '', 'index.php?go=Menueverwaltung', '', 0, 1, '', 80, '', ''),
(251, 'Cron Jobs', NULL, NULL, NULL, NULL, 'index.php?go=cronjobs_anzeigen', NULL, 64, 2, NULL, 0, NULL, NULL),
(256, 'Logout', '', '', '', '', 'index.php?go=logout', '', 0, 1, '', -1, '', 'logout'),
(257, 'Home', NULL, NULL, NULL, NULL, '/', NULL, 0, 1, NULL, 0, NULL, ''),
(258, 'Dienstleistungen', NULL, NULL, NULL, NULL, '/dienstleistungen', NULL, 0, 1, NULL, 10, NULL, NULL),
(260, 'Kontakt', NULL, NULL, NULL, NULL, '/kontakt', NULL, 0, 1, NULL, 30, NULL, NULL),
(263, 'Dienstleistungen', NULL, NULL, NULL, NULL, 'javascript:void(0)', 'overlay_link(\'go=Layer-Suche_Suchen&selected_layer_id=789\', true)', 264, 2, NULL, 10, NULL, NULL),
(269, 'Benachrichtigungen anzeigen', '', '', '', '', 'index.php?go=notifications_anzeigen', '', 20, 2, '', 15, '', ''),
(274, 'Menüs anzeigen', 'Menüs anzeigen', 'List all Menues', '', '', 'index.php?go=Menues_Anzeigen', '', 241, 2, '', 0, '', ''),
(278, 'Neue Belege', '', '', '', '', 'index.php?go=changemenue', '', 0, 1, '', 3, '', ''),
(284, 'Neuer Handkassenbeleg', '', '', '', '', 'index.php?go=neuer_Layer_Datensatz&selected_layer_id=802', '', 295, 2, '', 0, '', ''),
(290, 'neuer Urlaub', '', '', '', '', 'index.php?go=neuer_Layer_Datensatz&selected_layer_id=807', '', 288, 2, '', 20, '', ''),
(292, 'neuer Krankentag', '', '', '', '', 'index.php?go=neuer_Layer_Datensatz&selected_layer_id=808', '', 288, 2, '', 60, '', ''),
(301, 'Layerparameter', 'Layerattribut-Rechte', 'Layer Parameter', '', '', 'index.php?go=Layer_Parameter', '', 50, 2, '', 13, '', ''),
(303, 'Daten Export', '', '', '', '', 'index.php?go=Daten_Export', '', 17, 2, '', 20, '', ''),
(305, 'Layergruppen', '', '', '', '', 'index.php?go=Layergruppen_Anzeigen', '', 50, 2, '', 4, '', ''),
(306, 'Dienstmetadaten', '', '', '', '', 'index.php?go=Dienstmetadaten', '', 64, 2, '', 10, '', ''),
(312, 'Nutzer einladen', NULL, NULL, NULL, NULL, 'index.php?go=Einladung_Editor', NULL, 20, 2, NULL, 10, NULL, NULL),
(314, 'Layer anzeigen', '', '', '', '', 'index.php?go=Layer_Anzeigen', '', 50, 2, '', 10, '', ''),
(315, 'Postgre-Datenbankverbindungen', NULL, NULL, NULL, NULL, 'index.php?go=connections_anzeigen', NULL, 50, 2, NULL, 100, 'Zeigt die Postgres-Datenbankverbindungen', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `u_rolle2used_class`
--

CREATE TABLE `u_rolle2used_class` (
  `user_id` int(11) NOT NULL DEFAULT 0,
  `stelle_id` int(11) NOT NULL DEFAULT 0,
  `class_id` int(11) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `u_rolle2used_layer`
--

CREATE TABLE `u_rolle2used_layer` (
  `user_id` int(11) NOT NULL DEFAULT 0,
  `stelle_id` int(11) NOT NULL DEFAULT 0,
  `layer_id` int(11) NOT NULL DEFAULT 0,
  `aktivStatus` enum('0','1','2') NOT NULL DEFAULT '0',
  `queryStatus` enum('0','1','2') NOT NULL DEFAULT '0',
  `gle_view` tinyint(1) NOT NULL DEFAULT 1,
  `showclasses` tinyint(1) NOT NULL DEFAULT 1,
  `logconsume` enum('0','1') DEFAULT NULL,
  `transparency` tinyint(3) DEFAULT NULL,
  `drawingorder` int(11) DEFAULT NULL,
  `labelitem` varchar(100) DEFAULT NULL,
  `geom_from_layer` int(11) NOT NULL,
  `rollenfilter` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Daten für Tabelle `u_rolle2used_layer`
--

INSERT INTO `u_rolle2used_layer` (`user_id`, `stelle_id`, `layer_id`, `aktivStatus`, `queryStatus`, `gle_view`, `showclasses`, `logconsume`, `transparency`, `drawingorder`, `labelitem`, `geom_from_layer`, `rollenfilter`) VALUES
(1, 1, 1, '1', '0', 1, 1, '0', NULL, NULL, NULL, 0, NULL),
(1, 1, 2, '0', '0', 1, 1, '0', NULL, NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `u_styles2classes`
--

CREATE TABLE `u_styles2classes` (
  `class_id` int(11) NOT NULL DEFAULT 0,
  `style_id` int(11) NOT NULL DEFAULT 0,
  `drawingorder` int(11) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zwischenablage`
--

CREATE TABLE `zwischenablage` (
  `user_id` int(11) NOT NULL,
  `stelle_id` int(11) NOT NULL,
  `layer_id` int(11) NOT NULL,
  `oid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `belated_files`
--
ALTER TABLE `belated_files`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`Class_ID`),
  ADD KEY `Layer_ID` (`Layer_ID`);

--
-- Indizes für die Tabelle `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `connections`
--
ALTER TABLE `connections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `cron_jobs`
--
ALTER TABLE `cron_jobs`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indizes für die Tabelle `datasources`
--
ALTER TABLE `datasources`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `datatypes`
--
ALTER TABLE `datatypes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_datatypes_connection_id` (`connection_id`);

--
-- Indizes für die Tabelle `datatype_attributes`
--
ALTER TABLE `datatype_attributes`
  ADD PRIMARY KEY (`layer_id`,`datatype_id`,`name`);

--
-- Indizes für die Tabelle `datatype_attributes2stelle`
--
ALTER TABLE `datatype_attributes2stelle`
  ADD PRIMARY KEY (`datatype_id`,`attributename`,`stelle_id`);

--
-- Indizes für die Tabelle `datendrucklayouts`
--
ALTER TABLE `datendrucklayouts`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `ddl2freilinien`
--
ALTER TABLE `ddl2freilinien`
  ADD PRIMARY KEY (`ddl_id`,`line_id`),
  ADD KEY `line_id` (`line_id`);

--
-- Indizes für die Tabelle `ddl2freirechtecke`
--
ALTER TABLE `ddl2freirechtecke`
  ADD KEY `rect_id` (`rect_id`);

--
-- Indizes für die Tabelle `ddl2freitexte`
--
ALTER TABLE `ddl2freitexte`
  ADD PRIMARY KEY (`ddl_id`,`freitext_id`),
  ADD KEY `freitext_id` (`freitext_id`);

--
-- Indizes für die Tabelle `ddl2stelle`
--
ALTER TABLE `ddl2stelle`
  ADD PRIMARY KEY (`stelle_id`,`ddl_id`);

--
-- Indizes für die Tabelle `ddl_colors`
--
ALTER TABLE `ddl_colors`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `ddl_elemente`
--
ALTER TABLE `ddl_elemente`
  ADD PRIMARY KEY (`ddl_id`,`name`);

--
-- Indizes für die Tabelle `druckausschnitte`
--
ALTER TABLE `druckausschnitte`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stelle_id` (`stelle_id`,`user_id`,`id`),
  ADD KEY `stelle_id_2` (`stelle_id`,`user_id`,`id`),
  ADD KEY `stelle_id_3` (`stelle_id`,`user_id`,`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indizes für die Tabelle `druckfreibilder`
--
ALTER TABLE `druckfreibilder`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `druckfreilinien`
--
ALTER TABLE `druckfreilinien`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `druckfreirechtecke`
--
ALTER TABLE `druckfreirechtecke`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `druckfreitexte`
--
ALTER TABLE `druckfreitexte`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `druckrahmen`
--
ALTER TABLE `druckrahmen`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `druckrahmen2freibilder`
--
ALTER TABLE `druckrahmen2freibilder`
  ADD PRIMARY KEY (`druckrahmen_id`,`freibild_id`);

--
-- Indizes für die Tabelle `druckrahmen2freitexte`
--
ALTER TABLE `druckrahmen2freitexte`
  ADD PRIMARY KEY (`druckrahmen_id`,`freitext_id`);

--
-- Indizes für die Tabelle `druckrahmen2stelle`
--
ALTER TABLE `druckrahmen2stelle`
  ADD PRIMARY KEY (`stelle_id`,`druckrahmen_id`);

--
-- Indizes für die Tabelle `invitations`
--
ALTER TABLE `invitations`
  ADD PRIMARY KEY (`token`,`email`,`stelle_id`),
  ADD KEY `invitations_ibfk_1` (`stelle_id`);

--
-- Indizes für die Tabelle `labels`
--
ALTER TABLE `labels`
  ADD PRIMARY KEY (`Label_ID`);

--
-- Indizes für die Tabelle `layer`
--
ALTER TABLE `layer`
  ADD PRIMARY KEY (`Layer_ID`),
  ADD KEY `Gruppe` (`Gruppe`),
  ADD KEY `fk_layer_connection_id` (`connection_id`);

--
-- Indizes für die Tabelle `layer_attributes`
--
ALTER TABLE `layer_attributes`
  ADD PRIMARY KEY (`layer_id`,`name`);

--
-- Indizes für die Tabelle `layer_attributes2rolle`
--
ALTER TABLE `layer_attributes2rolle`
  ADD PRIMARY KEY (`layer_id`,`attributename`,`stelle_id`,`user_id`),
  ADD KEY `user_id` (`user_id`,`stelle_id`);

--
-- Indizes für die Tabelle `layer_attributes2stelle`
--
ALTER TABLE `layer_attributes2stelle`
  ADD PRIMARY KEY (`layer_id`,`attributename`,`stelle_id`),
  ADD KEY `layer_attributes2stelle_ibfk_1` (`stelle_id`);

--
-- Indizes für die Tabelle `layer_charts`
--
ALTER TABLE `layer_charts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_layer_charts_value_attribute_name` (`layer_id`,`value_attribute_name`),
  ADD KEY `fk_layer_charts_label_attribute_name` (`layer_id`,`label_attribute_name`);

--
-- Indizes für die Tabelle `layer_parameter`
--
ALTER TABLE `layer_parameter`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indizes für die Tabelle `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`component`,`type`,`filename`);

--
-- Indizes für die Tabelle `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `referenzkarten`
--
ALTER TABLE `referenzkarten`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `rolle`
--
ALTER TABLE `rolle`
  ADD PRIMARY KEY (`user_id`,`stelle_id`),
  ADD KEY `user_id_idx` (`user_id`),
  ADD KEY `rolle_ibfk_2` (`stelle_id`);

--
-- Indizes für die Tabelle `rollenlayer`
--
ALTER TABLE `rollenlayer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`stelle_id`),
  ADD KEY `fk_rollen_layer_connection_id` (`connection_id`);

--
-- Indizes für die Tabelle `rolle_csv_attributes`
--
ALTER TABLE `rolle_csv_attributes`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`name`);

--
-- Indizes für die Tabelle `rolle_export_settings`
--
ALTER TABLE `rolle_export_settings`
  ADD PRIMARY KEY (`stelle_id`,`user_id`,`layer_id`,`name`),
  ADD KEY `rolle_export_settings_ibfk_1` (`user_id`,`stelle_id`);

--
-- Indizes für die Tabelle `rolle_last_query`
--
ALTER TABLE `rolle_last_query`
  ADD KEY `user_id` (`user_id`,`stelle_id`);

--
-- Indizes für die Tabelle `rolle_nachweise`
--
ALTER TABLE `rolle_nachweise`
  ADD PRIMARY KEY (`user_id`,`stelle_id`);

--
-- Indizes für die Tabelle `rolle_saved_layers`
--
ALTER TABLE `rolle_saved_layers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`stelle_id`);

--
-- Indizes für die Tabelle `search_attributes2rolle`
--
ALTER TABLE `search_attributes2rolle`
  ADD PRIMARY KEY (`name`,`user_id`,`stelle_id`,`layer_id`,`attribute`,`searchmask_number`),
  ADD KEY `user_id` (`user_id`,`stelle_id`);

--
-- Indizes für die Tabelle `stelle`
--
ALTER TABLE `stelle`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `stellen_hierarchie`
--
ALTER TABLE `stellen_hierarchie`
  ADD PRIMARY KEY (`parent_id`,`child_id`),
  ADD KEY `stellen_hierarchie_ibfk_2` (`child_id`);

--
-- Indizes für die Tabelle `stelle_gemeinden`
--
ALTER TABLE `stelle_gemeinden`
  ADD KEY `stelle_gemeinden_ibfk_1` (`Stelle_ID`);

--
-- Indizes für die Tabelle `styles`
--
ALTER TABLE `styles`
  ADD PRIMARY KEY (`Style_ID`);

--
-- Indizes für die Tabelle `used_layer`
--
ALTER TABLE `used_layer`
  ADD PRIMARY KEY (`Stelle_ID`,`Layer_ID`),
  ADD KEY `layer_id_idx` (`Layer_ID`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `user2notifications`
--
ALTER TABLE `user2notifications`
  ADD PRIMARY KEY (`notification_id`,`user_id`),
  ADD KEY `notification_user_id_fk` (`user_id`);

--
-- Indizes für die Tabelle `u_attribute2used_layer`
--
ALTER TABLE `u_attribute2used_layer`
  ADD PRIMARY KEY (`attributename`,`layer_id`,`stelle_id`);

--
-- Indizes für die Tabelle `u_attributfilter2used_layer`
--
ALTER TABLE `u_attributfilter2used_layer`
  ADD PRIMARY KEY (`Stelle_ID`,`Layer_ID`,`attributname`);

--
-- Indizes für die Tabelle `u_consume`
--
ALTER TABLE `u_consume`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`time_id`);

--
-- Indizes für die Tabelle `u_consume2comments`
--
ALTER TABLE `u_consume2comments`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`time_id`);

--
-- Indizes für die Tabelle `u_consume2layer`
--
ALTER TABLE `u_consume2layer`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`time_id`,`layer_id`);

--
-- Indizes für die Tabelle `u_consumeALB`
--
ALTER TABLE `u_consumeALB`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`time_id`,`log_number`);

--
-- Indizes für die Tabelle `u_consumeALK`
--
ALTER TABLE `u_consumeALK`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`time_id`);

--
-- Indizes für die Tabelle `u_consumeCSV`
--
ALTER TABLE `u_consumeCSV`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`time_id`);

--
-- Indizes für die Tabelle `u_consumeShape`
--
ALTER TABLE `u_consumeShape`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`time_id`);

--
-- Indizes für die Tabelle `u_funktion2stelle`
--
ALTER TABLE `u_funktion2stelle`
  ADD PRIMARY KEY (`funktion_id`,`stelle_id`),
  ADD KEY `u_funktion2stelle_ibfk_1` (`stelle_id`);

--
-- Indizes für die Tabelle `u_funktionen`
--
ALTER TABLE `u_funktionen`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `u_groups`
--
ALTER TABLE `u_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `u_groups2rolle`
--
ALTER TABLE `u_groups2rolle`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`id`);

--
-- Indizes für die Tabelle `u_labels2classes`
--
ALTER TABLE `u_labels2classes`
  ADD PRIMARY KEY (`class_id`,`label_id`);

--
-- Indizes für die Tabelle `u_menue2rolle`
--
ALTER TABLE `u_menue2rolle`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`menue_id`),
  ADD KEY `fk_menue2rolle_menue2stelle` (`menue_id`,`stelle_id`);

--
-- Indizes für die Tabelle `u_menue2stelle`
--
ALTER TABLE `u_menue2stelle`
  ADD PRIMARY KEY (`stelle_id`,`menue_id`),
  ADD KEY `menue_id` (`menue_id`);

--
-- Indizes für die Tabelle `u_menues`
--
ALTER TABLE `u_menues`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `u_rolle2used_class`
--
ALTER TABLE `u_rolle2used_class`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`class_id`);

--
-- Indizes für die Tabelle `u_rolle2used_layer`
--
ALTER TABLE `u_rolle2used_layer`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`layer_id`),
  ADD KEY `u_rolle2used_layer_ibfk_2` (`layer_id`);

--
-- Indizes für die Tabelle `u_styles2classes`
--
ALTER TABLE `u_styles2classes`
  ADD PRIMARY KEY (`class_id`,`style_id`);

--
-- Indizes für die Tabelle `zwischenablage`
--
ALTER TABLE `zwischenablage`
  ADD PRIMARY KEY (`user_id`,`stelle_id`,`layer_id`,`oid`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `belated_files`
--
ALTER TABLE `belated_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `classes`
--
ALTER TABLE `classes`
  MODIFY `Class_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `colors`
--
ALTER TABLE `colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT für Tabelle `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210;

--
-- AUTO_INCREMENT für Tabelle `connections`
--
ALTER TABLE `connections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Eindeutige Id der Datenbankverbindungen', AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT für Tabelle `cron_jobs`
--
ALTER TABLE `cron_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `datasources`
--
ALTER TABLE `datasources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `datatypes`
--
ALTER TABLE `datatypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `datendrucklayouts`
--
ALTER TABLE `datendrucklayouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `ddl_colors`
--
ALTER TABLE `ddl_colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `druckausschnitte`
--
ALTER TABLE `druckausschnitte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT für Tabelle `druckfreibilder`
--
ALTER TABLE `druckfreibilder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `druckfreilinien`
--
ALTER TABLE `druckfreilinien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `druckfreirechtecke`
--
ALTER TABLE `druckfreirechtecke`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `druckfreitexte`
--
ALTER TABLE `druckfreitexte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `druckrahmen`
--
ALTER TABLE `druckrahmen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `labels`
--
ALTER TABLE `labels`
  MODIFY `Label_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `layer`
--
ALTER TABLE `layer`
  MODIFY `Layer_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `layer_charts`
--
ALTER TABLE `layer_charts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `layer_parameter`
--
ALTER TABLE `layer_parameter`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `referenzkarten`
--
ALTER TABLE `referenzkarten`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT für Tabelle `rollenlayer`
--
ALTER TABLE `rollenlayer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `rolle_saved_layers`
--
ALTER TABLE `rolle_saved_layers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `stelle`
--
ALTER TABLE `stelle`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `styles`
--
ALTER TABLE `styles`
  MODIFY `Style_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `u_funktionen`
--
ALTER TABLE `u_funktionen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT für Tabelle `u_groups`
--
ALTER TABLE `u_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT für Tabelle `u_menues`
--
ALTER TABLE `u_menues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=316;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `ddl2freirechtecke`
--
ALTER TABLE `ddl2freirechtecke`
  ADD CONSTRAINT `ddl2freirechtecke_ibfk_1` FOREIGN KEY (`rect_id`) REFERENCES `druckfreirechtecke` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `ddl2stelle`
--
ALTER TABLE `ddl2stelle`
  ADD CONSTRAINT `ddl2stelle_ibfk_1` FOREIGN KEY (`stelle_id`) REFERENCES `stelle` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `druckausschnitte`
--
ALTER TABLE `druckausschnitte`
  ADD CONSTRAINT `druckausschnitte_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `druckrahmen2stelle`
--
ALTER TABLE `druckrahmen2stelle`
  ADD CONSTRAINT `druckrahmen2stelle_ibfk_1` FOREIGN KEY (`stelle_id`) REFERENCES `stelle` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `invitations`
--
ALTER TABLE `invitations`
  ADD CONSTRAINT `invitations_ibfk_1` FOREIGN KEY (`stelle_id`) REFERENCES `stelle` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `layer`
--
ALTER TABLE `layer`
  ADD CONSTRAINT `fk_layer_connection_id` FOREIGN KEY (`connection_id`) REFERENCES `connections` (`id`);

--
-- Constraints der Tabelle `layer_attributes2rolle`
--
ALTER TABLE `layer_attributes2rolle`
  ADD CONSTRAINT `layer_attributes2rolle_ibfk_1` FOREIGN KEY (`layer_id`,`attributename`,`stelle_id`) REFERENCES `layer_attributes2stelle` (`layer_id`, `attributename`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `layer_attributes2rolle_ibfk_2` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `layer_attributes2stelle`
--
ALTER TABLE `layer_attributes2stelle`
  ADD CONSTRAINT `layer_attributes2stelle_ibfk_1` FOREIGN KEY (`stelle_id`) REFERENCES `stelle` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `layer_attributes2stelle_ibfk_2` FOREIGN KEY (`layer_id`) REFERENCES `layer` (`Layer_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `layer_charts`
--
ALTER TABLE `layer_charts`
  ADD CONSTRAINT `fk_layer_charts_label_attribute_name` FOREIGN KEY (`layer_id`,`label_attribute_name`) REFERENCES `layer_attributes` (`layer_id`, `name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_layer_charts_value_attribute_name` FOREIGN KEY (`layer_id`,`value_attribute_name`) REFERENCES `layer_attributes` (`layer_id`, `name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `rolle`
--
ALTER TABLE `rolle`
  ADD CONSTRAINT `rolle_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rolle_ibfk_2` FOREIGN KEY (`stelle_id`) REFERENCES `stelle` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `rollenlayer`
--
ALTER TABLE `rollenlayer`
  ADD CONSTRAINT `fk_rollen_layer_connection_id` FOREIGN KEY (`connection_id`) REFERENCES `connections` (`id`),
  ADD CONSTRAINT `rollenlayer_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `rolle_csv_attributes`
--
ALTER TABLE `rolle_csv_attributes`
  ADD CONSTRAINT `rolle_csv_attributes_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `rolle_export_settings`
--
ALTER TABLE `rolle_export_settings`
  ADD CONSTRAINT `rolle_export_settings_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `rolle_last_query`
--
ALTER TABLE `rolle_last_query`
  ADD CONSTRAINT `rolle_last_query_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `rolle_nachweise`
--
ALTER TABLE `rolle_nachweise`
  ADD CONSTRAINT `rolle_nachweise_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `rolle_saved_layers`
--
ALTER TABLE `rolle_saved_layers`
  ADD CONSTRAINT `rolle_saved_layers_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `search_attributes2rolle`
--
ALTER TABLE `search_attributes2rolle`
  ADD CONSTRAINT `search_attributes2rolle_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `stellen_hierarchie`
--
ALTER TABLE `stellen_hierarchie`
  ADD CONSTRAINT `stellen_hierarchie_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `stelle` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stellen_hierarchie_ibfk_2` FOREIGN KEY (`child_id`) REFERENCES `stelle` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `stelle_gemeinden`
--
ALTER TABLE `stelle_gemeinden`
  ADD CONSTRAINT `stelle_gemeinden_ibfk_1` FOREIGN KEY (`Stelle_ID`) REFERENCES `stelle` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `used_layer`
--
ALTER TABLE `used_layer`
  ADD CONSTRAINT `used_layer_ibfk_1` FOREIGN KEY (`Stelle_ID`) REFERENCES `stelle` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `used_layer_ibfk_2` FOREIGN KEY (`Layer_ID`) REFERENCES `layer` (`Layer_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `user2notifications`
--
ALTER TABLE `user2notifications`
  ADD CONSTRAINT `notification_id_fk` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notification_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `u_attributfilter2used_layer`
--
ALTER TABLE `u_attributfilter2used_layer`
  ADD CONSTRAINT `u_attributfilter2used_layer_ibfk_1` FOREIGN KEY (`Stelle_ID`,`Layer_ID`) REFERENCES `used_layer` (`Stelle_ID`, `Layer_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `u_consume`
--
ALTER TABLE `u_consume`
  ADD CONSTRAINT `u_consume_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `u_consume2comments`
--
ALTER TABLE `u_consume2comments`
  ADD CONSTRAINT `u_consume2comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `u_consume2layer`
--
ALTER TABLE `u_consume2layer`
  ADD CONSTRAINT `u_consume2layer_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `u_consumeALB`
--
ALTER TABLE `u_consumeALB`
  ADD CONSTRAINT `u_consumeALB_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `u_consumeALK`
--
ALTER TABLE `u_consumeALK`
  ADD CONSTRAINT `u_consumeALK_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `u_consumeCSV`
--
ALTER TABLE `u_consumeCSV`
  ADD CONSTRAINT `u_consumeCSV_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `u_consumeShape`
--
ALTER TABLE `u_consumeShape`
  ADD CONSTRAINT `u_consumeShape_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `u_funktion2stelle`
--
ALTER TABLE `u_funktion2stelle`
  ADD CONSTRAINT `u_funktion2stelle_ibfk_1` FOREIGN KEY (`stelle_id`) REFERENCES `stelle` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `u_groups2rolle`
--
ALTER TABLE `u_groups2rolle`
  ADD CONSTRAINT `u_groups2rolle_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `u_menue2rolle`
--
ALTER TABLE `u_menue2rolle`
  ADD CONSTRAINT `fk_menue2rolle_menue2stelle` FOREIGN KEY (`menue_id`,`stelle_id`) REFERENCES `u_menue2stelle` (`menue_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_menue2rolle_rolle` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `u_menue2rolle_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `u_menue2stelle`
--
ALTER TABLE `u_menue2stelle`
  ADD CONSTRAINT `fk_menue2stelle_meune` FOREIGN KEY (`menue_id`) REFERENCES `u_menues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_menue2stelle_stelle` FOREIGN KEY (`stelle_id`) REFERENCES `stelle` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `u_menue2stelle_ibfk_1` FOREIGN KEY (`stelle_id`) REFERENCES `stelle` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `u_menue2stelle_ibfk_2` FOREIGN KEY (`menue_id`) REFERENCES `u_menues` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `u_rolle2used_class`
--
ALTER TABLE `u_rolle2used_class`
  ADD CONSTRAINT `u_rolle2used_class_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `u_rolle2used_layer`
--
ALTER TABLE `u_rolle2used_layer`
  ADD CONSTRAINT `u_rolle2used_layer_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `u_rolle2used_layer_ibfk_2` FOREIGN KEY (`layer_id`) REFERENCES `layer` (`Layer_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `zwischenablage`
--
ALTER TABLE `zwischenablage`
  ADD CONSTRAINT `zwischenablage_ibfk_1` FOREIGN KEY (`user_id`,`stelle_id`) REFERENCES `rolle` (`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;