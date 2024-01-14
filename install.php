<?
###################################################################
# kvwmap - Kartenserver für die Verwaltung raumbezogener Daten.	 #
# Lizenz																													#
#																																 #
# Copyright (C) 2008	Peter Korduan															 # 
#																																 #
# This program is free software; you can redistribute it and/or	 #
# modify it under the terms of the GNU General Public License as	#
# published by the Free Software Foundation; either version 2 of	#
# the License, or (at your option) any later version.						 #
#																																 #
# This program is distributed in the hope that it will be useful, #
# but WITHOUT ANY WARRANTY; without even the implied warranty of	#
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the		#
# GNU General Public License for more details.										#
#																																 #
# You should have received a copy of the GNU General Public			 #
# License along with this program; if not, write to the Free			#
# Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,	#
# MA 02111-1307, USA.																						 #
#																																 #
# Eine deutsche Übersetzung zur Lizenz finden Sie unter:					#
# http://www.gnu.de/gpl-ger.html																	#
#																																 #
# Kontakt:																												#
# peter.korduan@gdi-service.de																		#
# stefan.rahn@gdi-service.de																			#
###################################################################

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
$debug; $log_mysql; $log_postgres;
define('KVWMAP_INIT_PASSWORD', (getenv('KVWMAP_INIT_PASSWORD') == '') ? 'KvwMapPW1' : getenv('KVWMAP_INIT_PASSWORD'));

class GUI {
	function __construct($main, $style, $mime_type) {
	}

	function add_message($type, $msg) {
		echo '<p>Meldung: ' . $type;
		echo '<br>' . $msg;
	}
}
$GUI = new GUI(NULL, NULL, NULL);

output_header();

if (!file_exists('config.php')) {
	# Lade default Konfigurationsparameter
	init_config();
	include(CLASSPATH . 'administration.php');
	$kvwmap_plugins = array();
	include(WWWROOT.APPLVERSION.'funktionen/allg_funktionen.php');
	if ($_REQUEST['go'] == 'Installation starten') {
		install();
	}
	else {
		settings();
	}
	output_footer();
}

function output_header() { ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
	<head>
		<title>kvmwap Install</title>
		<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="layouts/css/main.css.php">
		<link rel="stylesheet" href="../3rdparty/font-awesome-4.6.3/css/font-awesome.min.css">
		<script src="../3rdparty/jQuery-3.6.0/jquery.min.js"></script>
	</head>
	<body style="font-family: Arial, Verdana, Helvetica, sans-serif"><?php
}

function output_footer() { ?>
	</body>
</html><?php
}

function install() {
	global $debug, $log_mysql, $log_postgres;

	include(CLASSPATH . 'log.php');
	if (DEBUG_LEVEL > 0) $debug = new Debugger(DEBUGFILE);
	if (LOG_LEVEL > 0) {
	 $log_mysql = new LogFile(LOGFILE_MYSQL, 'text', 'Log-Datei MySQL' , '#------v: ' . date("Y:m:d H:i:s", time()));
	 $log_postgres = new LogFile(LOGFILE_POSTGRES, 'text', 'Log-Datei-Postgres', '------v: ' . date("Y:m:d H:i:s", time()));
	} ?>
	<h1>Teste Verbindung zu MySQL mit Nutzer root</h1><?php
	#
	# Teste ob MySQL-Server läuft
	#

	include(CLASSPATH . 'mysql.php');
	$mysqlRootDb = new database();
	$mysqlRootDb->host = MYSQL_HOST;
	$mysqlRootDb->user = 'root';
	$mysqlRootDb->passwd = MYSQL_ROOT_PASSWORD;
	$mysqlRootDb->dbName = 'mysql'; ?>
	Verbindungsdaten für Zugang zu MySQL root Nutzer wie folgt gesetzt:<br>
	Host: <?php echo $mysqlRootDb->host; ?><br>
	User: <?php echo $mysqlRootDb->user; ?><br>
	Password: <?php #echo $mysqlRootDb->passwd; ?><br>
	Datenbankname: <?php echo $mysqlRootDb->dbName; ?><br><?php

	if (mysql_exists($mysqlRootDb)) { ?>
		MySQL-Server läuft, Verbindung hergestellt zu Host: <?php echo $mysqlRootDb->host; ?> Datenbank: <?php echo $mysqlRootDb->dbName; ?> mit Nutzer: <?php echo $mysqlRootDb->user; ?>!<br><?php
	}
	else { ?>
		Es kann keine Verbindung zu Host: <?php echo $mysqlRootDb->host; ?> MySQL Datenbank: <?php echo $mysqlRootDb->dbName; ?> mit Nutzer: <?php echo $mysqlRootDb->user; ?> hergestellt werden!<br>
		Fehlermeldung: <?php echo $mysql_error; ?><br>
		Das kann folgende Gründe haben:
		<ul>
			<li><b>MySQL ist noch nicht installiert:</b> => Installieren sie MySQL</li>
			<li><b>Der MySQL server host ist nicht korrekt angegeben:</b> => Setzen Sie den richtigen hostnamen in der Datei config.php in der Konstante <b>MYSQL_HOST</b>. In Docker Containern muss der Name mysql oder mysql-server heißen, sonst in der Regel localhost oder 172.0.0.1. Nur wenn sich die Datenbank auf einem anderem Rechner befindet geben Sie hier die entsprechende IP oder den Rechnername an.</li>
			<li><b>Das Passwort des Datenbanknutzers root ist nicht richtig gesetzt:</b> => standardmäßig wird es aus der Umgebungsvariablen MYSQL_ROOT_PASSWORD genommen.</li>
		</ul>
		<input type="button" value="Script neu starten" onclick="window.location.reload()">
		<?php
		return false;
	} ?>

	<h1>Teste Verbindung zu MySQL mit Nutzer <?php echo MYSQL_USER; ?></h1><?php
	#
	# Teste ob kvwmap Datenbank auf MySQL-Server läuft
	# und richte ggf. Nutzer und eine neue leere kvwmap Datenbank ein.
	#
	$mysqlKvwmapDb = new database();
	$mysqlKvwmapDb->host = MYSQL_HOST;
	$mysqlKvwmapDb->user = MYSQL_USER;
	$mysqlKvwmapDb->passwd = MYSQL_PASSWORD;
	$mysqlKvwmapDb->dbName = MYSQL_DBNAME; ?>
	Verbindungsdaten für Zugang zu MySQL kvwmap Nutzer wie folgt gesetzt:<br>
	Host: <?php echo $mysqlKvwmapDb->host; ?><br>
	User: <?php echo $mysqlKvwmapDb->user; ?><br>
	Password: <?php #echo $mysqlKvwmapDb->passwd; ?><br>
	Datenbankname: <?php echo $mysqlKvwmapDb->dbName; ?><br>
	Debugfilename: <?php echo $mysqlKvwmapDb->debug->filename; ?><br>
	Logfilename: <?php echo $mysqlKvwmapDb->logfile->name; ?><br><?php
	if (kvwmapdb_exists($mysqlRootDb, $mysqlKvwmapDb)) { ?>
		kvwmap Datenbank <?php echo $mysqlKvwmapDb->dbName; ?> existiert schon auf MySQL-Server.<br><?php
		$kvwmapdb_installed = true;
	}
	else { ?>
		Die MySQL Datenbank <?php echo $mysqlKvwmapDb->dbName; ?> existiert nicht oder die Verbindung kann mit dem Nutzer <?php echo $mysqlRootDb->user; ?> nicht hergestellt werden.<br>
		<h1>Installiere kvwmap Datenbank auf MySQL-Server</h1><?php
		$kvwmapdb_installed = install_kvwmapdb($mysqlRootDb, $mysqlKvwmapDb);
	} ?>

	<h1>Teste Verbindung zu PostgreSQL mit Nutzer postgres</h1><?php
	#
	# Teste PostgreSQL-Server läuft
	#
	include(CLASSPATH . 'postgresql.php');
	$pgsqlPostgresDb = new pgdatabase();
	$pgsqlPostgresDb->set_object_credentials(array(
		'host' =>		 POSTGRES_HOST,
		'port' =>		 '5432',
		'dbname' =>	 'postgres',
		'user' =>		 'postgres',
		'password' => POSTGRES_ROOT_PASSWORD
	)); ?>
	Verbindungsdaten für Zugang zu PostgreSQL postgres Nutzer wie folgt gesetzt:<br>
	Host: <?php echo $pgsqlPostgresDb->host; ?><br>
	User: <?php echo $pgsqlPostgresDb->user; ?><br>
	Password: <?php #echo $pgsqlPostgresDb->passwd; ?><br>
	Datenbankname: <?php echo $pgsqlPostgresDb->dbName; ?><br><?php
	if (postgres_exists($pgsqlPostgresDb)) { ?>
		PostgreSQL-Server läuft, Verbindung zur Datenbank <?php echo $pgsqlPostgresDb->dbName; ?> kann mit Nutzer: <?php echo $pgsqlPostgresDb->user; ?> host: <?php echo $pgsqlPostgresDb->host; ?> hergestellt werden!<br><?php
	}
	else { ?>
		Es kann keine Verbindung zur PostgreSQL Datenbank <?php echo $pgsqlPostgresDb->dbName; ?> mit Nutzer <?php echo $pgsqlPostgresDb->user; ?>@<?php echo $pgsqlPostgresDb->host; ?> hergestellt werden.<br>
		Das kann folgende Gründe haben:
		<ul>
			<li><b>PostgreSQL ist noch nicht installiert:</b> => Installieren sie PostgreSQL</li>
			<li><b>Der PostgreSQL server host ist nicht korrekt angegeben:</b> => Setzen Sie den richtigen hostnamen in der Datei config.php in der Konstante <b>POSTGRES_HOST</b>. In Docker Containern muss der Name pgsql oder pgsql-server heißen, sonst in der Regel localhost oder 172.0.0.1. Nur wenn sich die Datenbank auf einem anderem Rechner befindet geben Sie hier die entsprechende IP oder den Rechnername an.</li>
			<li><b>Das Passwort des Datenbanknutzers postgres ist nicht richtig gesetzt:</b> => Das Passwort kann in der Umgebungsvariable <b>POSTGRES_ROOT_PASSWORD</b> in der env_and_volumes des web Containers	eingestellt werden. Normalerweise wird die Konstante beim Erzeugen des pgsql Containers abgefragt und steht in env_and_volumes des web Containers zur Verfügung.</li>
		</ul>
		<input type="button" value="Script neu starten" onclick="window.location.reload()">
		<?php
		return false;
	}
	?>

	<h1>Teste Verbindung zu PostgreSQL mit Nutzer <?php echo POSTGRES_USER; ?></h1><?php
	#
	# Teste ob kvwmap Datenbank auf PostgreSQL-Server läuft
	# und richte ggf. Nutzer und eine neue leere kvwmap Datenbank ein. 
	#
	$pgsqlKvwmapDb = new pgdatabase();
	$pgsqlKvwmapDb->set_object_credentials(array(
		'host' =>		 POSTGRES_HOST,
		'port' =>		 '5432',
		'dbname' =>	 POSTGRES_DBNAME,
		'user' =>		 POSTGRES_USER,
		'password' => POSTGRES_PASSWORD
	)); ?>
	Verbindungsdaten für Zugang zu PostgreSQL kvwmap Nutzer wie folgt gesetzt:<br>
	Host: <?php echo $pgsqlKvwmapDb->host; ?><br>
	User: <?php echo $pgsqlKvwmapDb->user; ?><br>
	Password: <?php # echo $pgsqlKvwmapDb->passwd; ?><br>
	Datenbankname: <?php echo $pgsqlKvwmapDb->dbName; ?><br><?php

	if (kvwmapsp_exists($pgsqlPostgresDb, $pgsqlKvwmapDb)) { ?>
		kvwmaps PostGIS Datenbank <?php echo $pgsqlKvwmapDb->dbName; ?> existiert schon auf PostgreSQL-Server.<br><?php
		$kvwmapsp_installed = true;
	}
	else { ?>
		Es kann keine Verbindung zur Postgres Datenbank <?php echo $pgsqlKvwmapDb->dbName; ?> mit Nutzer <?php echo $pgsqlKvwmapDb->user; ?> und host: <?php echo $pgsqlKvwmapDb->host; ?> hergestellt werden.<br>
		<h1>Installiere kvwmap Datenbank auf PostgreSQL-Server</h1><?php
		$kvwmapsp_installed = install_kvwmapsp($pgsqlPostgresDb, $pgsqlKvwmapDb);
	}

	if ($kvwmapdb_installed and $kvwmapsp_installed) { ?>
		<h1>Datenbanken kvwmapdb und kvwmapsp stehen jetzt zur Verfügung</h1>
		Verbindungen zu MySQL und PostgreSQL Datenbanken von kvwmap herstellen und Migration ausführen.<br> <?php
		$mysqlKvwmapDb->open();
		$pgsqlKvwmapDb->open(); ?>

		Erzeuge die PostGIS Erweiterung in der kvwmapsp Datenbank falls noch nicht vorhanden.<br><?php
		$sql = "
			CREATE EXTENSION IF NOT EXISTS postgis
		";
		$pgsqlKvwmapDb->execSQL($sql, 0, 1); ?>
		
		Entferne Superuser Recht<br>		<?php
		$sql = "
			ALTER USER " . $pgsqlKvwmapDb->user . " WITH NOSUPERUSER;
		";
		$pgsqlKvwmapDb->execSQL($sql, 0, 1); ?>
		
		Ergänze bzw. korrigiere EPSG-Codes für MV<br><?php
		$sql = "
			UPDATE
				spatial_ref_sys
			SET
				proj4text = '+proj=longlat +ellps=bessel +datum=potsdam +nadgrids=MVTR2010.gsb+no_defs'
			WHERE
				srid = 4314;

			UPDATE
				spatial_ref_sys
			SET
				proj4text = '+proj=longlat +ellps=krass +nadgrids=MVTRS4283.gsb +no_defs '
			WHERE
				srid = 4178;

			UPDATE
				spatial_ref_sys
			SET
				proj4text = '+proj=tmerc +lat_0=0 +lon_0=12 +k=1.000000 +x_0=4500000 +y_0=0 +ellps=krass +nadgrids=MVTRS4283.gsb +units=m +no_defs'
			WHERE
				srid = 2398;

			UPDATE
				spatial_ref_sys
			SET
				proj4text = '+proj=tmerc +lat_0=0 +lon_0=9 +k=1.000000 +x_0=3500000 +y_0=0 +ellps=bessel +datum=potsdam +nadgrids=MVTR2010.gsb +units=m +no_defs'
			WHERE
				srid = 31967;

			UPDATE
				spatial_ref_sys
			SET
				proj4text = '+proj=tmerc +lat_0=0 +lon_0=12 +k=1.000000 +x_0=4500000 +y_0=0 +ellps=bessel +datum=potsdam +nadgrids=MVTR2010.gsb +units=m +no_defs'
			WHERE
				srid = 31968;

			UPDATE
				spatial_ref_sys
			SET
				proj4text = '+proj=tmerc +lat_0=0 +lon_0=15 +k=1.000000 +x_0=5500000 +y_0=0 +ellps=bessel +datum=potsdam +nadgrids=MVTR2010.gsb +units=m +no_defs'
			WHERE
				srid = 31969;

			/*
			INSERT INTO
				spatial_ref_sys (srid, auth_name, auth_srid, srtext, proj4text)
			VALUES (
				35833,
				'EPSG',
				35833,
				'PROJCS[\"ETRS89/UTM 33N RW+33500000 MV\",GEOGCS[\"ETRS89\",DATUM[\"European_Terrestrial_Reference_System_1989\",SPHEROID[\"GRS 1980\",6378137,298.257222101,AUTHORITY[\"EPSG\",\"7019\"]],AUTHORITY[\"EPSG\",\"6258\"]],PRIMEM[\"Greenwich\",0,AUTHORITY[\"EPSG\",\"8901\"]],UNIT[\"degree\",0.01745329251994328,AUTHORITY[\"EPSG\",\"9122\"]],AUTHORITY[\"EPSG\",\"4258\"]],PROJECTION[\"Transverse_Mercator\"],PARAMETER[\"latitude_of_origin\",0],PARAMETER[\"central_meridian\",15],PARAMETER[\"scale_factor\",0.9996],PARAMETER[\"false_easting\",33500000],PARAMETER[\"false_northing\",0],UNIT[\"metre\",1,AUTHORITY[\"EPSG\",\"9001\"]],AUTHORITY[\"EPSG\",\"25833\"]]',
				'+proj=tmerc +towgs84=0,0,0 +lat_0=0 +lon_0=15 +k=0.9996 +x_0=33500000 +y_0=0 +ellps=GRS80 +units=m +no_defs <>'
				), (
				325833,
				'EPSG',
				325833,
				'PROJCS[\"ETRS89/UTM 33N RW+3500000 Brandenburg\",GEOGCS[\"ETRS89\",DATUM[\"European_Terrestrial_Reference_System_1989\",SPHEROID[\"GRS 1980\",6378137,298.257222101,AUTHORITY[\"EPSG\",\"7019\"]],AUTHORITY[\"EPSG\",\"6258\"]],PRIMEM[\"Greenwich\",0,AUTHORITY[\"EPSG\",\"8901\"]],UNIT[\"degree\",0.01745329251994328,AUTHORITY[\"EPSG\",\"9122\"]],AUTHORITY[\"EPSG\",\"4258\"]],PROJECTION[\"Transverse_Mercator\"],PARAMETER[\"latitude_of_origin\",0],PARAMETER[\"central_meridian\",15],PARAMETER[\"scale_factor\",0.9996],PARAMETER[\"false_easting\",3500000],PARAMETER[\"false_northing\",0],UNIT[\"metre\",1,AUTHORITY[\"EPSG\",\"9001\"]],AUTHORITY[\"EPSG\",\"325833\"]]',
				'+proj=tmerc +towgs84=0,0,0 +lat_0=0 +lon_0=15 +k=0.9996 +x_0=3500000 +y_0=0 +ellps=GRS80 +units=m +no_defs <>'
				)
			*/
		";
		$pgsqlKvwmapDb->execSQL($sql, 0, 1); ?>

		<h1>Lege MariaDB Tabellen und Inhalte für die Administration an.</h1><?
		$mysql_install_file = LAYOUTPATH . 'db/mysql/data/mysql_install.sql';
		$sql = file_get_contents($mysql_install_file);
		$result = $mysqlKvwmapDb->exec_commands($sql, NULL, NULL, false, true);
		if (!$result['success']) {
			?>Fehler beim Ausführen der Datei <?php echo $mysql_install_file; ?><br><?
			echo $result[1];
			exit;
		}
		else { ?>
			Datei <? echo $mysql_install_file; ?> erfolgreich ausgeführt.<?php
		} ?>

		<h1>Migrationen für kvwmap Schemas in MySQL und PostgreSQL ausführen</h1><?php
		#
		# Führe alle Migration aus und richte damit die aktuellen Datenbankschemas ein.
		#
		migrate_databases($mysqlKvwmapDb, $pgsqlKvwmapDb);

		if (file_exists('credentials.php')) { ?>
			credentials.php existiert schon.<?php
		}
		else { ?>
			Lege credentials.php Datei an ...<?php
			file_put_contents('credentials.php', "<?php
			define('MYSQL_HOST', 'mysql');
			define('MYSQL_USER', 'kvwmap');
			define('MYSQL_PASSWORD', '" . MYSQL_PASSWORD . "');
			define('MYSQL_DBNAME', 'kvwmapdb');
			define('MYSQL_HOSTS_ALLOWED', '" . MYSQL_HOSTS_ALLOWED . "');?>"); ?><br>
			... fertig<p><?php
		}

		?>Setze Password für Postgres Nutzer "kvwmap" in MariaDB connections table...<?php
		$sql = "
			UPDATE connections
			SET password = '" . POSTGRES_PASSWORD . "'
			WHERE id = 1
		";
		$ret = $mysqlKvwmapDb->execSQL($sql, 0, 1);
		if ($ret['success']) { ?>
			... fertig<?
		}
		else {?>
			<br>Fehler beim Einstellen des Passwortes für Nutzer <?php echo $mysqlKvwmapDb->user; ?> in der Datenbank <?php echo $mysqlKvwmapDb->dbName; ?><br><?php
			$error = true;
			echo $mysqlKvwmapDb->errormessage;
		}

		?>Setze Password für kvwmap Nutzer "kvwmap" in MariaDB Tabelle user...<?php
		$sql = "
			UPDATE
				user
			SET
				passwort = MD5('" . KVWMAP_INIT_PASSWORD . "')
			WHERE
				login_name = 'kvwmap'
		";
		$ret = $mysqlKvwmapDb->execSQL($sql, 0, 1);
		if ($ret['success']) { ?>
			... fertig<?
		}
		else { ?>
			Fehler beim Einstellen des Passwortes für user <?php echo $mysqlKvwmapDb->user; ?> in der Datenbank <?php echo $mysqlKvwmapDb->dbName; ?><br><?php
			$error = true;
			echo $mysqlKvwmapDb->errormessage;
		}

		if (file_exists('config.php')) { ?>
			config.php existiert schon.<?php
		}
		else { ?>
			Lege config.php Datei an ...<?php
			$administration = new administration($mysqlKvwmapDb, $pgsqlKvwmapDb);
			$administration->write_config_file(''); ?><br>
			...fertig<p><?php
		} ?>

		Schließe Verbindung zur Datenbank: <?php echo $mysqlRootDb->dbName; ?><br><?php
		$mysqlRootDb->close(); ?>
		Schließe Verbindung zur Datenbank: <?php echo $mysqlKvwmapDb->dbName; ?><br><?php
		$mysqlKvwmapDb->close(); ?>
		Schließe Verbindung zur Datenbank: <?php echo $pgsqlPostgresDb->dbName; ?><br><?php
		$pgsqlPostgresDb->close(); ?>
		Schließe Verbindung zur Datenbank: <?php echo $pgsqlKvwmapDb->dbName; ?><br><?php
		$pgsqlKvwmapDb->close(); ?>

		<h1>Installation abgeschlossen</h1><?
		if ($error) { ?>
			Während der Installation sind Fehler aufgetreten. Vor dem Anmelden bei kvwmap müssen diese behoben werden.<?php
		}
		else { ?>
			Sie können sich jetzt mit folgenden Nutzerdaten bei kvwmap anmelden.<br>
			Nutzername: <? echo $mysqlKvwmapDb->user; ?><br>
			Passwort: <?php echo KVWMAP_INIT_PASSWORD; ?><br>
			<br>
			<a href="index.php">Login</a><?php
		}
	}
}

function init_config() {
	$cwd = getcwd();
	$applversion = basename($cwd);
	$rest = dirname($cwd);
	$wwwpath = basename($rest) . '/';
	$installpath = dirname($rest) . '/';
	$formvars = $_REQUEST;

	define('MYSQL_HOST', ($formvars['MYSQL_HOST'] != '' ? $formvars['MYSQL_HOST'] : 'mysql'));
	define('MYSQL_USER', ($formvars['MYSQL_USER'] != '' ? $formvars['MYSQL_USER'] : 'kvwmap'));
	define('MYSQL_PASSWORD', ($formvars['MYSQL_PASSWORD'] != '' ? $formvars['MYSQL_PASSWORD'] : (getenv('KVWMAP_INIT_PASSWORD') == '' ? 'KvwMapPW1' : getenv('KVWMAP_INIT_PASSWORD'))));
	define('MYSQL_DBNAME', ($formvars['MYSQL_DBNAME'] != '' ? $formvars['MYSQL_DBNAME'] : 'kvwmapdb'));
	define('MYSQL_ROOT_PASSWORD', ($formvars['MYSQL_ROOT_PASSWORD'] != '' ? $formvars['MYSQL_ROOT_PASSWORD'] : getenv('MYSQL_ROOT_PASSWORD')));
	define('MYSQL_HOSTS_ALLOWED', getenv('MYSQL_HOSTS_ALLOWED'));
	define('MYSQL_CHARSET', 'UTF8');
	define('DEFAULTDBWRITE', 1);
	define('DBWRITE', 1);
	define('POSTGRES_HOST', ($formvars['POSTGRES_HOST'] != '' ? $formvars['POSTGRES_HOST'] : 'pgsql'));
	define('POSTGRES_USER', ($formvars['POSTGRES_USER'] != '' ? $formvars['POSTGRES_USER'] : 'kvwmap'));
	define('POSTGRES_PASSWORD', ($formvars['POSTGRES_PASSWORD'] != '' ? $formvars['POSTGRES_PASSWORD'] : (getenv('KVWMAP_INIT_PASSWORD') == '' ? 'KvwMapPW1' : getenv('KVWMAP_INIT_PASSWORD'))));
	define('POSTGRES_ROOT_PASSWORD', ($formvars['POSTGRES_ROOT_PASSWORD'] != '' ? $formvars['POSTGRES_ROOT_PASSWORD'] : getenv('POSTGRES_ROOT_PASSWORD')));
	define('POSTGRES_DBNAME', ($formvars['POSTGRES_DBNAME'] != '' ? $formvars['POSTGRES_DBNAME'] : 'kvwmapsp'));
	define('POSTGRESVERSION', getenv('PGSQL_ENV_POSTGRES_MAJOR'));
	define('POSTGRES_CHARSET', 'UTF8');
	define('EPSGCODE_ALKIS', 25833);
	define('EARTH_RADIUS', 6384000);
	define('CLASSPATH', 'class/');
	define('LAYOUTPATH', 'layouts/');
	define('LOG_LEVEL', 4);
	define('LOGPATH', $installpath . 'logs/kvwmap/');
	define('DEBUG_LEVEL', 1);
	define('DEBUGFILE', 'install.log');
	define('LOGFILE_MYSQL', LOGPATH . 'install.log');
	define('LOGFILE_POSTGRES', LOGPATH . 'install.log');
	define('WWWROOT', $installpath.$wwwpath);
	define('APPLVERSION', $applversion . '/');
	define('WAPPENPATH', 'graphics/wappen/');
	define('PHPVERSION', 739);
	define('THIRDPARTY_PATH', '../3rdparty/');
}

function show_constants() { ?>
	<h1>config.php</h1>
	kvwmap Konfigurationsdatei config.php von config-default.php übernommen und mit folgenden Werte gesetzt:<br>
	<table>
		<tr>
			<th>Konstante</th>
			<th>Wert</th>
		</tr><?php
		$constants = get_defined_constants(true);
		$user_constants = $constants['user'];
		foreach ($user_constants AS $user_constant => $value) { ?>
			<tr>
				<td><?php echo $user_constant; ?></td>
				<td><?php echo $value; ?></td>
			</tr><?php
		} ?>
	</table><?php
}

/**
* Testet ob es schon eine mysql-Datenbank gibt
*/
function mysql_exists($mysqlKvwmapDb) { ?>
	Prüfe ob Datenbank mysql schon existiert<br><?php
	return $mysqlKvwmapDb->open();
}

/**
* Testet ob es schon eine kvwmapdb gibt
*/
function kvwmapdb_exists($mysqlRootDb, $mysqlKvwmapDb) { ?>
	Prüfe ob kvwmapdb schon existiert<br><?php
	$sql = "
		SELECT
			SCHEMA_NAME
		FROM
			INFORMATION_SCHEMA.SCHEMATA
		WHERE
			SCHEMA_NAME = '" . $mysqlKvwmapDb->dbName . "'
	";
	$ret = $mysqlRootDb->execSQL($sql, 0, 1);
	return (mysqli_num_rows($mysqlRootDb->result) > 0);
}

/**
* Installiert kvwmap-Datenbank
*/
function install_kvwmapdb($mysqlRootDb, $mysqlKvwmapDb) {
	# Abfragen ob user mysqlKvwmapDb->user existiert
	$sql = "
		SELECT
			User
		FROM
			user
		WHERE
			User = '" . $mysqlKvwmapDb->user . "' AND
			Host = '" . MYSQL_HOSTS_ALLOWED . "'
	";
	$ret = $mysqlRootDb->execSQL($sql, 0, 1);
	if ($ret[0]) { ?>
		Fehler beim Abfragen ob User <?php echo $mysqlKvwmapDb->user; ?> mit Host <?php echo MYSQL_HOSTS_ALLOWED; ?> schon in MySQL existiert.<br><?php
		return false;
	}
	if (mysqli_num_rows($mysqlRootDb->result) > 0 ) { ?>
		User <?php echo $mysqlKvwmapDb->user; ?> mit Host <?php echo MYSQL_HOSTS_ALLOWED; ?> existiert schon in Datenbank. <?php
	}
	else	{ ?>
		Erzeuge Nutzer: <?php echo $mysqlKvwmapDb->user; ?><br><?php
		$sql = "
			CREATE USER '" . $mysqlKvwmapDb->user . "'@'" . MYSQL_HOSTS_ALLOWED . "'
			IDENTIFIED BY '" . $mysqlKvwmapDb->passwd . "'
		";
		$mysqlRootDb->execSQL($sql, 0, 1);
	} ?>
	
	Erzeuge Datenbank: <?php echo $mysqlKvwmapDb->dbName; ?><br><?php
	$sql = "
		CREATE DATABASE " . $mysqlKvwmapDb->dbName . "
		CHARACTER SET utf8
		COLLATE utf8_general_ci
	";
	$mysqlRootDb->execSQL($sql, 0, 1); ?>

	Setze Rechte für Nutzer: <?php echo $mysqlKvwmapDb->user; ?><br><?php	
	$sql = "	
		GRANT ALL PRIVILEGES
		ON *.*
		TO '" . $mysqlKvwmapDb->user . "'@'" . MYSQL_HOSTS_ALLOWED . "'
		IDENTIFIED BY '" . $mysqlKvwmapDb->passwd . "';
	";
	$ret = $mysqlRootDb->execSQL($sql, 0, 1);
	if ($ret[0]) { ?>
		Fehler beim installieren der Datenbank: <?php echo $mysqlKvwmapDb->dbName; ?><br><?php
		return false;
	}
	else { ?>
		Anlegen der Datenbank: <?php echo $mysqlKvwmapDb->dbName; ?> erfolgreich.<br><?php
		return true;
	}
}

/**
* Testet ob die postgre Datenbank auf PostgreSQL-Server läuft
*/
function postgres_exists($pgsqlPostgresDb) { ?>
	Prüfe ob Datenbank postgres schon existiert auf Server: <?php echo $pgsqlPostgresDb->host; 
	return $pgsqlPostgresDb->open();
}

/**
* Testet ob die kvwmap Datenbank auf PostgreSQL-Server läuft
*/

function kvwmapsp_exists($pgsqlPostgresDb, $pgsqlKvwmapDb) { ?>
	Prüfe ob Datenbank kvwmapsp schon existiert<br><?php
	$sql = "
		SELECT
			datname
		FROM
			pg_catalog.pg_database
		WHERE
			datname = '" . $pgsqlKvwmapDb->dbName . "'
	";
	$ret = $pgsqlPostgresDb->execSQL($sql, 0, 1);
	return (pg_num_rows($ret[1]) > 0);
}

/**
* Installiert PostGIS-Datenbank
*/
function install_kvwmapsp($pgsqlPostgresDb, $pgsqlKvwmapDb) { ?>
	Erzeuge Nutzer: <?php echo $pgsqlKvwmapDb->user; ?><br><?php
	$sql = "
		DROP ROLE IF EXISTS
			" . $pgsqlKvwmapDb->user . ";

		CREATE ROLE
			" . $pgsqlKvwmapDb->user . "
		WITH
			SUPERUSER
			LOGIN
			PASSWORD '" . $pgsqlKvwmapDb->passwd . "';
			
		GRANT SET ON PARAMETER log_min_messages TO " . $pgsqlKvwmapDb->user . ";
	";
	$pgsqlPostgresDb->execSQL($sql, 0, 1); ?>
	
	Erzeuge Datenbank: <?php echo $pgsqlKvwmapDb->dbName; ?><br><?php
	$sql = "
		CREATE DATABASE
			" . $pgsqlKvwmapDb->dbName . "
		WITH
			OWNER " . $pgsqlKvwmapDb->user . ";
	";
	$query = pg_query($pgsqlPostgresDb->dbConn, $sql);
	if ($query==0) {
		$err_msg = "Fehler bei SQL Anweisung:<br>" . $sql . "<br>" . pg_result_error($query);
		echo "<br><b>" . $err_msg . "</b>";
		return false;
	}
	else { ?>
		Anlegen der Datenbank: <?php echo $pgsqlKvwmapDb->dbName; ?> erfolgreich.<br><?php
		return true;
	}
}

/**
* 
*/
function migrate_databases($mysqlKvwmapDb, $pgsqlKvwmapDb) {
	$mysqlKvwmapDb->execSQL("SET NAMES 'UTF8'",0,0);
	$administration = new administration($mysqlKvwmapDb, $pgsqlKvwmapDb);
	echo '<br>Frage Datenbankstati ab.';
	$administration->get_database_status();
	echo '<br>Aktualisiere Datenbanken.';
	$err_msgs = $administration->update_databases();
	echo '<br>Datenbanken aktualisiert:<br>' . implode('<br>', $err_msgs);
	$administration->get_database_status();
	if (count($administration->migrations_to_execute['mysql']) == 0 AND count($administration->migrations_to_execute['postgresql']) == 0) { ?>
		Anlegen der Datenbank-Schemata erfolgreich.<br><?php
	}
	else{
		if (count($administration->migrations_to_execute['mysql']) > 0) { ?>
			<br>Es konnten nicht alle MySQL-Migrationen ausgeführt werden.<br><?php
			echo '<br>Folgende wurden noch nicht ausgeführt: <ul><li>' . implode('</li><li>', $administration->migrations_to_execute['mysql']['kvwmap']) . '</li></ul>';
		}
		if (count($administration->migrations_to_execute['postgresql']) > 0) { ?>
			<br>Anlegen des PostgreSQL-Schemas fehlgeschlagen.<br><?php
			echo '<br>Folgende wurden noch nicht ausgeführt: <ul><li>' . implode('</li><li>', $administration->migrations_to_execute['postgresql']['kvwmap']) . '</li></ul>';
		}
	}
}

/**
* Prüft ob schon eine Admin stelle in kvwmapdb existiert
*/
function admin_stelle_exists($mysqlKvwmapDb) {
	$sql = "
		SELECT
			1
		FROM
			`stelle`
		WHERE
			`Bezeichnung` = 'Administration'
	";
	$ret = $mysqlKvwmapDb->execSQL($sql, 0, 1);
	return (mysqli_num_rows($mysqlKvwmapDb->result) > 0) ? true : false;
}

function settings() { ?>
	<h1>Installation von kvwmap</h1>
	Mit diesem Script wird der Datenbanknutzer sowie die MySQL Nutzerdatenbank kvwmap und die PostgreSQL Geo-Datenbank kvwmapsp angelegt.<br>
	Anschließend werden alle Migrationen ausgeführt.<br>
	Die MySQL-Zugangsdaten können nachträglich in der Datei credentials.php geändert werden, und alle anderen Einstellungen in der Adminoberfläche bzw. der MySQL Nutzerdatenbank in der Tabelle config.<br><br>
	<form method="POST" target="install.php">
		<table>
			<tr>
				<td>MYSQL_HOST:</td>
				<td><input type="text" name="MYSQL_HOST" value="<?php echo MYSQL_HOST; ?>" size="35"></td>
			</tr>
			<tr>
				<td>MYSQL_DBNAME:</td>
				<td><input type="text" name="MYSQL_DBNAME" value="<?php echo MYSQL_DBNAME; ?>" size="35"></td>
			</tr>
			<tr>
				<td>MYSQL_USER:</td>
				<td><input type="text" name="MYSQL_USER" value="<?php echo MYSQL_USER; ?>" size="35"></td>
			</tr>
			<tr>
				<td>MYSQL_PASSWORD:</td>
				<td><input id="mysql_password" type="password" name="MYSQL_PASSWORD" value="<?php echo MYSQL_PASSWORD; ?>" size="35"><i style="margin-left: 5px" class="fa fa-eye" aria-hidden="true" onclick="$(this).toggleClass('fa-eye fa-eye-slash'); if ($('#mysql_password').attr('type') == 'text') { $('#mysql_password').attr('type', 'password') } else { $('#mysql_password').attr('type', 'text'); }"></i></td>
			</tr>
			<tr>
				<td>MYSQL_ROOT_PASSWORD:</td>
				<td><input id="mysql_root_password" type="password" name="MYSQL_ROOT_PASSWORD" value="<?php echo MYSQL_ROOT_PASSWORD; ?>" size="35"><i style="margin-left: 5px" class="fa fa-eye" aria-hidden="true" onclick="$(this).toggleClass('fa-eye fa-eye-slash'); if ($('#mysql_root_password').attr('type') == 'text') { $('#mysql_root_password').attr('type', 'password') } else { $('#mysql_root_password').attr('type', 'text'); }"></i></td>
			</tr>
			<tr>
				<td>POSTGRES_HOST:</td>
				<td><input type="text" name="POSTGRES_HOST" value="<?php echo POSTGRES_HOST; ?>" size="35"></td>
			</tr>
			<tr>
				<td>POSTGRES_DBNAME:</td>
				<td><input type="text" name="POSTGRES_DBNAME" value="<?php echo POSTGRES_DBNAME; ?>" size="35"></td>
			</tr>
			<tr>
				<td>POSTGRES_USER:</td>
				<td><input type="text" name="POSTGRES_USER" value="<?php echo POSTGRES_USER; ?>" size="35"></td>
			</tr>
			<tr>
				<td>POSTGRES_PASSWORD:</td>
				<td><input id="postgres_password" type="password" name="POSTGRES_PASSWORD" value="<?php echo POSTGRES_PASSWORD; ?>" size="35"><i style="margin-left: 5px" class="fa fa-eye" aria-hidden="true" onclick="$(this).toggleClass('fa-eye fa-eye-slash'); if ($('#postgres_password').attr('type') == 'text') { $('#postgres_password').attr('type', 'password') } else { $('#postgres_password').attr('type', 'text'); }"></i></td>
			</tr>
			<tr>
				<td>POSTGRES_ROOT_PASSWORD:</td>
				<td><input id="postgres_root_password" type="password" name="POSTGRES_ROOT_PASSWORD" value="<?php echo POSTGRES_ROOT_PASSWORD; ?>" size="35"><i style="margin-left: 5px" class="fa fa-eye" aria-hidden="true" onclick="$(this).toggleClass('fa-eye fa-eye-slash'); if ($('#postgres_root_password').attr('type') == 'text') { $('#postgres_root_password').attr('type', 'password') } else { $('#postgres_root_password').attr('type', 'text'); }"></i></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" name="go" value="Installation starten"></td>
			</tr>
		</table>
	</form>
	<?php
} ?>