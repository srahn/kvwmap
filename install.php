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
$debug; $log_postgres;
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
<!DOCTYPE html>
<html>
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
	global $debug, $log_postgres;

	include(CLASSPATH . 'log.php');
	if (DEBUG_LEVEL > 0) $debug = new Debugger(DEBUGFILE);
	if (LOG_LEVEL > 0) {
	 $log_postgres = new LogFile(LOGFILE_POSTGRES, 'text', 'Log-Datei-Postgres', '------v: ' . date("Y:m:d H:i:s", time()));
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
		'password' => POSTGRES_POSTGRES_PASSWORD
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
			<li><b>Das Passwort des Datenbanknutzers postgres ist nicht richtig gesetzt:</b> => Das Passwort kann in der Umgebungsvariable <b>POSTGRES_POSTGRES_PASSWORD</b> in der env_and_volumes des web Containers	eingestellt werden. Normalerweise wird die Konstante beim Erzeugen des pgsql Containers abgefragt und steht in env_and_volumes des web Containers zur Verfügung.</li>
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
		<!--h1>Installiere kvwmap Datenbank auf PostgreSQL-Server</h1--><?php
		#$kvwmapsp_installed = install_kvwmapsp($pgsqlPostgresDb, $pgsqlKvwmapDb);		# passiert ab PG16 Container schon vorher
	}

	if ($kvwmapsp_installed) { ?>
		<h1>Datenbank kvwmapsp steht jetzt zur Verfügung</h1>
		Verbindung zu PostgreSQL Datenbank von kvwmap herstellen und Migration ausführen.<br> <?php
		$pgsqlKvwmapDb->open(); ?>

		<?php
		# folgendes passiert ab PG16 Container schon vorher
		/*
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
			/*
		";
		$pgsqlKvwmapDb->execSQL($sql, 0, 1); */
		
		?>
		
		<h1>Lege Tabellen und Inhalte für die Administration an.</h1><?
		$kvwmap_install_file = $installpath . LAYOUTPATH . 'db/postgresql/data/kvwmap_install.sql';
		$sql = file_get_contents($kvwmap_install_file);
		$result = $pgsqlKvwmapDb->execSQL($sql, 0, 1);
		if (!$result['success']) {
			?>Fehler beim Ausführen der Datei <?php echo $kvwmap_install_file; ?><br><?
			echo '<br>result: ' . print_r($result, true);
			$error = true;
		}
		else { ?>
			Datei <? echo $kvwmap_install_file; ?> erfolgreich ausgeführt!

			<h1>Migrationen für kvwmap Schema in PostgreSQL ausführen</h1><?php
			#
			# Führe alle Migration aus und richte damit die aktuellen Datenbankschemas ein.
			#
			migrate_database($pgsqlKvwmapDb);

			if (file_exists('credentials.php')) { ?>
				credentials.php existiert schon.<?php
			}
			else { ?>
				Lege credentials.php Datei an ...<?php
				file_put_contents('credentials.php', "<?php
				define('POSTGRES_HOST', 'pgsql');
				define('POSTGRES_USER', 'kvwmap');
				define('POSTGRES_PASSWORD', '" . POSTGRES_PASSWORD . "');
				define('POSTGRES_DBNAME', 'kvwmapsp');
				?>"); ?><br>
				... fertig<p><?php
			}

			?>Setze URL Konstante ...<?php
			$sql = "
				UPDATE kvwmap.config
				SET value = '" . $_SERVER['HTTP_HOST'] . "'
				WHERE name = 'URL'
			";
			$ret = $pgsqlKvwmapDb->execSQL($sql, 0, 1);
			if ($ret['success']) { ?>
				... fertig<?
			}
			else {?>
				<br>Fehler beim Einstellen der URL des Servers in der Datenbank <?php echo $pgsqlKvwmapDb->dbName; ?><br><?php
				$error = true;
				echo $pgsqlKvwmapDb->errormessage;
			}

			?>Setze Password für Postgres Nutzer "kvwmap" in connections table...<?php
			$sql = "
				UPDATE kvwmap.connections
				SET password = '" . POSTGRES_PASSWORD . "'
				WHERE id = 1
			";
			$ret = $pgsqlKvwmapDb->execSQL($sql, 0, 1);
			if ($ret['success']) { ?>
				... fertig<?
			}
			else {?>
				<br>Fehler beim Einstellen des Passwortes für Nutzer <?php echo $pgsqlKvwmapDb->user; ?> in der Datenbank <?php echo $pgsqlKvwmapDb->dbName; ?><br><?php
				$error = true;
				echo $pgsqlKvwmapDb->errormessage;
			}

			?>Setze Password für kvwmap Nutzer "kvwmap" in Tabelle user...<?php
			$sql = "
				UPDATE
					kvwmap.user
				SET
					password = kvwmap.sha1('" . KVWMAP_INIT_PASSWORD . "')
				WHERE
					login_name = 'kvwmap'
			";
			$ret = $pgsqlKvwmapDb->execSQL($sql, 0, 1);
			if ($ret['success']) { ?>
				... fertig<?
			}
			else { ?>
				Fehler beim Einstellen des Passwortes für user <?php echo $pgsqlKvwmapDb->user; ?> in der Datenbank <?php echo $pgsqlKvwmapDb->dbName; ?><br><?php
				$error = true;
				echo $pgsqlKvwmapDb->errormessage;
			}

			if (file_exists('config.php')) { ?>
				config.php existiert schon.<?php
			}
			else { ?>
				Lege config.php Datei an ...<?php
				$administration = new administration($pgsqlKvwmapDb);
				$administration->write_config_file(''); ?><br>
				...fertig<p><?php
			}
		} ?>

		Schließe Verbindung zur Datenbank: <?php echo $pgsqlPostgresDb->dbName; ?><br><?php
		$pgsqlPostgresDb->close(); ?>
		Schließe Verbindung zur Datenbank: <?php echo $pgsqlKvwmapDb->dbName; ?><br><?php
		$pgsqlKvwmapDb->close(); ?>

		<h1>Installation abgeschlossen</h1><?
		if ($error) { ?>
			Während der Installation sind Fehler aufgetreten. Vor dem Anmelden bei kvwmap müssen diese behoben werden.<?php
		}
		else { ?>
			Die Anmeldung bei kvwmap kann jetzt mit folgenden Zugangsdaten erfolgen:<br>
			Nutzername: <? echo $pgsqlKvwmapDb->user; ?><br>
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

	define('DEFAULTDBWRITE', 1);
	define('DBWRITE', 1);
	define('POSTGRES_HOST', ($formvars['POSTGRES_HOST'] != '' ? $formvars['POSTGRES_HOST'] : 'pgsql'));
	define('POSTGRES_USER', ($formvars['POSTGRES_USER'] != '' ? $formvars['POSTGRES_USER'] : 'kvwmap'));
	define('POSTGRES_PASSWORD', ($formvars['POSTGRES_PASSWORD'] != '' ? $formvars['POSTGRES_PASSWORD'] : (getenv('KVWMAP_INIT_PASSWORD') == '' ? 'KvwMapPW1' : getenv('KVWMAP_INIT_PASSWORD'))));
	define('POSTGRES_POSTGRES_PASSWORD', ($formvars['POSTGRES_POSTGRES_PASSWORD'] != '' ? $formvars['POSTGRES_POSTGRES_PASSWORD'] : getenv('POSTGRES_POSTGRES_PASSWORD')));
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
	define('LOGFILE_POSTGRES', LOGPATH . 'install.log');
	define('WWWROOT', $installpath.$wwwpath);
	define('APPLVERSION', $applversion . '/');
	define('WAPPENPATH', 'graphics/wappen/');
	define('PHPVERSION', 739);
	define('THIRDPARTY_PATH', '../3rdparty/');
	define('MAPSERVERVERSION', 800);
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
function install_kvwmapsp($pgsqlPostgresDb, $pgsqlKvwmapDb) {
	$sql = "
		SELECT 1 FROM pg_roles WHERE rolname='kvwmap'
	";
	$query = pg_query($pgsqlPostgresDb->dbConn, $sql);
	if ($query == 0) {
		$err_msg = "Fehler bei SQL Anweisung:<br>" . $sql . "<br>" . pg_result_error($query);
		echo "<br><b>" . $err_msg . "</b>";
		return false;
	}
	if (pg_num_rows($query) == 0) { ?>
		Erzeuge Nutzer: <?php echo $pgsqlKvwmapDb->user; ?><br><?php
		$sql = "
			CREATE ROLE
				" . $pgsqlKvwmapDb->user . "
			WITH
				SUPERUSER
				LOGIN
				PASSWORD '" . $pgsqlKvwmapDb->passwd . "';
				
			GRANT SET ON PARAMETER log_min_messages TO " . $pgsqlKvwmapDb->user . ";
		";
		$query = $pgsqlPostgresDb->execSQL($sql, 0, 1);
		if ($query == 0) {
			$err_msg = "Fehler bei SQL Anweisung:<br>" . $sql . "<br>" . pg_result_error($query);
			echo "<br><b>" . $err_msg . "</b>";
			return false;
		}
	}
	else {
		$sql = "
			ALTER USER " . $pgsqlKvwmapDb->user . " WITH SUPERUSER;
		";
		$query = $pgsqlPostgresDb->execSQL($sql, 0, 1);
		if ($query == 0) {
			$err_msg = "Fehler bei SQL Anweisung:<br>" . $sql . "<br>" . pg_result_error($query);
			echo "<br><b>" . $err_msg . "</b>";
			return false;
		}
	}?>

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
function migrate_database($pgsqlKvwmapDb) {
	$administration = new administration($pgsqlKvwmapDb);
	echo '<br>Frage Datenbankstati ab.';
	$administration->get_database_status();
	echo '<br>Aktualisiere Datenbank.';
	$err_msgs = $administration->update_databases();
	echo '<br>Datenbank aktualisiert:<br>' . implode('<br>', $err_msgs);
	$administration->get_database_status();
	if (count($administration->migrations_to_execute['postgresql']) == 0) { ?>
		Anlegen der Datenbank-Schemata erfolgreich.<br><?php
	}
	else {
		if (count($administration->migrations_to_execute['postgresql']) > 0) { ?>
			<br>Anlegen des PostgreSQL-Schemas fehlgeschlagen.<br><?php
			echo '<br>Folgende wurden noch nicht ausgeführt: <ul><li>' . implode('</li><li>', $administration->migrations_to_execute['postgresql']['kvwmap']) . '</li></ul>';
		}
	}
}


function settings() { ?>
	<h1>Installation von kvwmap</h1>
	Mit diesem Script wird der Datenbanknutzer die PostgreSQL Geo-Datenbank kvwmapsp angelegt.<br>
	Anschließend werden alle Migrationen ausgeführt.<br>
	Die Zugangsdaten können nachträglich in der Datei credentials.php geändert werden, und alle anderen Einstellungen in der Adminoberfläche bzw. der in der Tabelle config.<br><br>
	<form method="POST" target="install.php">
		<table>
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
				<td>POSTGRES_POSTGRES_PASSWORD:</td>
				<td><input id="postgres_postgres_password" type="password" name="POSTGRES_POSTGRES_PASSWORD" value="<?php echo POSTGRES_POSTGRES_PASSWORD; ?>" size="35"><i style="margin-left: 5px" class="fa fa-eye" aria-hidden="true" onclick="$(this).toggleClass('fa-eye fa-eye-slash'); if ($('#postgres_postgres_password').attr('type') == 'text') { $('#postgres_postgres_password').attr('type', 'password') } else { $('#postgres_postgres_password').attr('type', 'text'); }"></i></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" name="go" value="Installation starten"></td>
			</tr>
		</table>
	</form>
	<?php
} ?>