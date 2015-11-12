<?

###################################################################
# kvwmap - Kartenserver für die Verwaltung raumbezogener Daten.   #
# Lizenz                                                          #
#                                                                 #
# Copyright (C) 2008  Peter Korduan                               # 
#                                                                 #
# This program is free software; you can redistribute it and/or   #
# modify it under the terms of the GNU General Public License as  #
# published by the Free Software Foundation; either version 2 of  #
# the License, or (at your option) any later version.             #
#                                                                 #
# This program is distributed in the hope that it will be useful, #
# but WITHOUT ANY WARRANTY; without even the implied warranty of  #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the    #
# GNU General Public License for more details.                    #
#                                                                 #
# You should have received a copy of the GNU General Public       #
# License along with this program; if not, write to the Free      #
# Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,  #
# MA 02111-1307, USA.                                             #
#                                                                 #
# Eine deutsche Übersetzung zur Lizenz finden Sie unter:          #
# http://www.gnu.de/gpl-ger.html                                  #
#                                                                 #
# Kontakt:                                                        #
# peter.korduan@gdi-service.de                                    #
# stefan.rahn@gdi-service.de                                      #
###################################################################

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
$debug; $log_mysql; $log_postgres;
define('KVWMAP_INIT_PASSWORD', (getenv('KVWMAP_INIT_PASSWORD') == '') ? 'KvwMapPW1' : getenv('KVWMAP_INIT_PASSWORD'));

output_header();
if ($_REQUEST['go'] == 'Installation starten') {
  install();
}
else {
  settings();
}
output_footer();

function output_header() { ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
  <head>
    <title>kvmwap Install</title>
    <meta http-equiv=Content-Type content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="layouts/main.css">
  </head>
  <body style="font-family: Arial, Verdana, Helvetica, sans-serif"><?php
}

function output_footer() { ?>
  </body>
</html><?php
}

function install() {
  global $debug, $log_mysql, $log_postgres;
  if (!file_exists('config.php')) {
    install_config();
  }
  
  include('config.php');
  #show_constants();

  include(CLASSPATH.'log.php');
  if(DEBUG_LEVEL > 0) $debug = new debugfile(DEBUGFILE);
  if (LOG_LEVEL > 0) {
   $log_mysql = new LogFile(LOGFILE_MYSQL, 'text', 'Log-Datei MySQL' , '#------v: ' . date("Y:m:d H:i:s", time()));
   $log_postgres = new LogFile(LOGFILE_POSTGRES, 'text', 'Log-Datei-Postgres', '------v: ' . date("Y:m:d H:i:s", time()));
  } ?>
  <h1>Teste Verbindung zu MySQL mit Nutzer root</h1><?php
  
  #
  # Teste ob MySQL-Server läuft
  #
  include(CLASSPATH . 'mysql.php');
  $mysqlRootDb = new database;
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
    MySQL-Server läuft, Verbindung zur Datenbank <?php echo $mysqlRootDb->dbName; ?> kann mit Nutzer <?php echo $mysqlRootDb->user; ?>@<?php echo $mysqlRootDb->host; ?> hergestellt werden!<br><?php
  }
  else { ?>
    Es kann keine Verbindung zur MySQL Datenbank <?php echo $mysqlRootDb->dbName; ?> mit Nutzer <?php echo $mysqlRootDb->user; ?>@<?php echo $mysqlRootDb->host; ?> hergestellt werden.<br>
    Das kann folgende Gründe haben:
    <ul>
      <li><b>MySQL ist noch nicht installiert:</b> => Installieren sie MySQL</li>
      <li><b>Der MySQL server host ist nicht korrekt angegeben:</b> => Setzen Sie den richtigen hostnamen in der Datei config.php in der Konstante <b>MYSQL_HOST</b>. In Docker Containern muss der Name mysql oder mysql-server heißen, sonst in der Regel localhost oder 172.0.0.1. Nur wenn sich die Datenbank auf einem anderem Rechner befindet geben Sie hier die entsprechende IP oder den Rechnername an.</li>
      <li><b>Das Passwort des Datenbanknutzers root ist nicht richtig gesetzt:</b> => Das Passwort kann in der Konstante <b>MYSQL_ROOT_PASSWORD</b> in der Datei config.php eingestellt werden. Nach der erfolgreichen Installation können sie diese Konstante löschen oder das Passwort auf ein Leerzeichen setzen.</li>
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
    Die MySQL Datenbank <?php echo $mysqlKvwmapDb->dbName; ?> existiert nicht oder die Verbindung kann mit dem Nutzer <?php echo $mysqlKvwmapDb->user; ?>@<?php echo $mysqlKvwmapDb->host; ?> nicht hergestellt werden.<br>
    
    <h1>Installiere kvwmap Datenbank auf MySQL-Server</h1><?php
    $kvwmapdb_installed = install_kvwmapdb($mysqlRootDb, $mysqlKvwmapDb);
  } ?>

  <h1>Teste Verbindung zu PostgreSQL mit Nutzer postgres</h1><?php
  #
  # Teste PostgreSQL-Server läuft
  #
  include(CLASSPATH . 'postgresql.php');
  $pgsqlPostgresDb = new pgdatabase();
  $pgsqlPostgresDb->host = POSTGRES_HOST;                        
  $pgsqlPostgresDb->user = 'postgres';                          
  $pgsqlPostgresDb->passwd = POSTGRES_ROOT_PASSWORD;                    
  $pgsqlPostgresDb->dbName = 'postgres'; ?>
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
      <li><b>Das Passwort des Datenbanknutzers postgres ist nicht richtig gesetzt:</b> => Das Passwort kann in der Konstante <b>POSTGRES_ROOT_PASSWORD</b> in der Datei config.php eingestellt werden. Nach der erfolgreichen Installation können sie diese Konstante löschen oder das Passwort auf ein Leerzeichen setzen.</li>
    </ul>
    <input type="button" value="Script neu starten" onclick="window.location.reload()">
    <?php
    return false;
  } ?>

  <h1>Teste Verbindung zu PostgreSQL mit Nutzer <?php echo POSTGRES_USER; ?></h1><?php
  #
  # Teste ob kvwmap Datenbank auf PostgreSQL-Server läuft
  # und richte ggf. Nutzer und eine neue leere kvwmap Datenbank ein. 
  #
  $pgsqlKvwmapDb = new pgdatabase();
  $pgsqlKvwmapDb->host = POSTGRES_HOST;
  $pgsqlKvwmapDb->user = POSTGRES_USER;
  $pgsqlKvwmapDb->passwd = POSTGRES_PASSWORD;
  $pgsqlKvwmapDb->dbName = POSTGRES_DBNAME; ?>
  Verbindungsdaten für Zugang zu PostgreSQL kvwmap Nutzer wie folgt gesetzt:<br>
  Host: <?php echo $pgsqlKvwmapDb->host; ?><br>
  User: <?php echo $pgsqlKvwmapDb->user; ?><br>
  Password: <?php# echo $pgsqlKvwmapDb->passwd; ?><br>
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
    ";
    $pgsqlKvwmapDb->execSQL($sql, 0, 1); ?>
    
    <h1>Migrationen für kvwmap Schemas in MySQL und PostgreSQL ausführen</h1><?php
    #
    # Führe alle Migration aus und richte damit die aktuellen Datenbankschemas ein.
    #
    migrate_databases($mysqlKvwmapDb, $pgsqlKvwmapDb);

    #
    # Richte eine Stelle für einen Administrator ein, wenn noch keine existiert.
    #
    if (admin_stelle_exists($mysqlKvwmapDb)) { ?>
      Adminstelle ist schon eingerichtet.<br><?php
    }
    else {
      $success = install_admin_stelle($mysqlKvwmapDb);
    } ?>
    <br>
    <br>
    Schließe Verbindung zur Datenbank: <?php echo $mysqlRootDb->dbName; ?><br><?php
    $mysqlRootDb->close(); ?>
    Schließe Verbindung zur Datenbank: <?php echo $mysqlKvwmapDb->dbName; ?><br><?php
    $mysqlKvwmapDb->close(); ?>
    Schließe Verbindung zur Datenbank: <?php echo $pgsqlPostgresDb->dbName; ?><br><?php
    $pgsqlPostgresDb->close(); ?>
    Schließe Verbindung zur Datenbank: <?php echo $pgsqlKvwmapDb->dbName; ?><br><?php
    $pgsqlKvwmapDb->close();
  }
}

function install_config() {
  $_SESSION['login_name'] = 'kvwmap';
  $config = file_get_contents('config-default.php');
  $cwd = getcwd();
  $applversion = basename($cwd);
  $rest = dirname($cwd);
  $www = basename($rest);
  $installpath = dirname($rest);
  $config = str_replace("define('APPLVERSION','kvwmap/');", "define('APPLVERSION','".$applversion."/');", $config);
  $config = str_replace("define('INSTALLPATH','/home/gisadmin/');", "define('INSTALLPATH','".$installpath."/');", $config);
  $config = str_replace("define('WWWROOT',INSTALLPATH.'apps/');", "define('WWWROOT',INSTALLPATH.'".$www."/');", $config);
  $config = str_replace(
    "define('MYSQL_HOST', 'localhost');",
    "define('MYSQL_HOST', 'mysql');",
    $config
  );
  $config = str_replace("define('MYSQL_USER', '');", "define('MYSQL_USER', 'kvwmap');", $config);
  $config = str_replace("define('MYSQL_PASSWORD', '');", "define('MYSQL_PASSWORD', '" . KVWMAP_INIT_PASSWORD . "');", $config);
  $config = str_replace(
    "define('MYSQLVERSION', '500');",
    "define('MYSQLVERSION', '" . versionFormatter(getenv('MYSQL_ENV_MYSQL_MAJOR')) . "');",
    $config
  );
  $config = str_replace(
    "define('POSTGRES_HOST', 'localhost');",
    "define('POSTGRES_HOST', 'pgsql');",
    $config
  );
  $config = str_replace("define('POSTGRES_USER', '');", "define('POSTGRES_USER', 'kvwmap');", $config);
  $config = str_replace("define('POSTGRES_PASSWORD', '');", "define('POSTGRES_PASSWORD', '" . KVWMAP_INIT_PASSWORD . "');", $config);
  $config = str_replace(
    "define('POSTGRESVERSION', '804');",
    "define('POSTGRESVERSION', '" . versionFormatter(getenv('PGSQL_ENV_PG_MAJOR')) . "');",
    $config
  );
  $config = str_replace(
    "define('MAPSERVERVERSION', '620');",
    "define('MAPSERVERVERSION', '" . versionFormatter(getMapServerVersion()) . "');",
    $config
  );
  $config = str_replace(
    "define('PHPVERSION', '450');",
    "define('PHPVERSION', '" . versionFormatter(getPHPVersion()) . "');",
    $config
  );
  $config = str_replace(
    "define('URL','http://localhost/');",
    "define('URL', 'http://" . $_SERVER['HTTP_HOST'] . "/');",
    $config
  );
  
  $config = str_replace(
    "define('POSTGRESBINPATH', '/usr/lib/postgresql/9.1/bin/');",
    "define('POSTGRESBINPATH', '/usr/local/bin/');",
    $config
  );

  file_put_contents('config.php', $config);
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

/*
* Testet ob es schon eine mysql-Datenbank gibt
*/
function mysql_exists($mysqlKvwmapDb) { ?>
  Prüfe ob Datenbank mysql schon existiert<br><?php
  return $mysqlKvwmapDb->open();
}

/*
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
  return (mysql_num_rows($ret[1]) > 0);
}

/*
* Installiert kvwmap-Datenbank
*/
function install_kvwmapdb ($mysqlRootDb, $mysqlKvwmapDb) {
  # Abfragen ob user mysqlKvwmapDb->user existiert
  $sql = "
    USE mysql;

    SELECT
      User
    FROM
      user
    WHERE
      User = '" . $mysqlKvwmapDb->user . "' AND
      Host = '" . MYSQL_HOSTS_ALLOWED . "'
  ";
  $mysqlRootDb->execSQL($sql, 0, 1);
  if ($ret[0]) { ?>
    Fehler beim Abfragen ob User <?php echo $mysqlKvwmapDb; ?> mit Host <?php echo MYSQL_HOSTS_ALLOWED; ?> schon in Datenbank <?php echo $mysqlKvwmapDb->dbName; ?> existiert.<br><?php
    return false;
  }
  if (mysql_num_rows($ret[1]) > 0 ) { ?>
    User <?php echo $mysqlKvwmapDb; ?> mit Host <?php echo MYSQL_HOSTS_ALLOWED; ?> existiert schon in Datenbank <?php echo $mysqlKvwmapDb->dbName;
  }
  else  { ?>
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

/*
* Testet ob die postgre Datenbank auf PostgreSQL-Server läuft
*/
function postgres_exists($pgsqlPostgresDb) { ?>
  Prüfe ob Datenbank postgres schon existiert<br><?php
  echo $pgsqlPostgresDb->host;
  return $pgsqlPostgresDb->open();
}

/*
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

/*
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
      PASSWORD '" . $pgsqlKvwmapDb->passwd . "'
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

/*
* 
*/
function migrate_databases($mysqlKvwmapDb, $pgsqlKvwmapDb) {
  include(CLASSPATH . 'administration.php');
  $administration = new administration($mysqlKvwmapDb, $pgsqlKvwmapDb);
  $administration->get_database_status();
  $administration->update_databases();
  $administration->get_database_status();
  if (count($administration->migrations_to_execute['mysql']) == 0 AND count($administration->migrations_to_execute['postgresql']) == 0) { ?>
    Anlegen der Datenbank-Schemata erfolgreich.<br><?php
  }
  else{
    if (count($administration->migrations_to_execute['mysql']) > 0) { ?>
      Anlegen des MySQL-Schemas fehlgeschlagen.<br><?php
    }
    if (count($administration->migrations_to_execute['postgresql']) > 0) { ?>
      Anlegen des PostgreSQL-Schemas fehlgeschlagen.<br><?php
    }
  }
}

/*
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
  return (mysql_num_rows($ret[1]) > 0) ? true : false;
}

/*
* Trägt alle Einstellungen für eine Admin-Stelle in MySQL-Datenbank von kvwmap ein.
*/
function install_admin_stelle($mysqlKvwmapDb) {
  $filepath = LAYOUTPATH . 'db/mysql/data/mysql_install_admin.sql';
  $queryret = $mysqlKvwmapDb->exec_file($filepath, NULL, NULL);
  if ($queryret[0]) { 
    echo $queryret[1]; ?>
    Fehler beim Ausführen der Datei: <?php echo $filepath; ?><br><?php
    return false;
  }
  
  $sql = "
    UPDATE
      user
    SET
      passwort = MD5('" . KVWMAP_INIT_PASSWORD . "')
    WHERE
      login_name = 'kvwmap'
    ";
  $ret = $mysqlKvwmapDb->execSQL($sql, 0, 1);
  if ($ret[0]) { ?>
    Fehler beim Einstellen des Passwortes für user <?php echo $mysqlKvwmapDb->user; ?> in der Datenbank <?php echo $mysqlKvwmapDb->dbName; ?><br><?php
    return false;
  }
  
  ?>Daten zur Einrichtung der Stelle Administration erfolgreich eingelesen.<br>
  Sie können sich jetzt mit folgenden Nutzerdaten bei kvwmap anmelden.<br>
  Nutzername: kvwmap<br>
  Passwort: <?php echo KVWMAP_INIT_PASSWORD; ?><br>
  <br>
  <a href="index.php">Login</a><?php
  return true;
}

function getMySQLVersion() {
  return getVersionFromText(
    shell_exec('mysql -V')
  );
}

function getPostgreSQLVersion() { 
  return getVersionFromText(
    shell_exec('psql -h $PGSQL_PORT_5432_TCP_ADDR -V')
  );
}

function getMapServerVersion() {
  return getVersionFromText(
    shell_exec('/usr/lib/cgi-bin/mapserv -v')
  );
}

function getPHPVersion() {
  return getVersionFromText(
    shell_exec('php -v')
  );
}

function getVersionFromText($text) {
  preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $text, $version);
  return $version[0];
}

function settings() { ?>
  <h1>Installation von kvwmap</h1>
  Mit diesem Script wird die Konfigurationsdatei config.php, die Datenbanknutzer sowie die MySQL Nutzerdatenbank kvwmap und die PostgreSQL Geo-Datenbank kvwmapsp angelegt.<br>
  Die Einrichtung erfolgt mit den in config-default.php eingestellten Zugangsdaten. Die Passwörter können nachträglich in der Datei config.php geändert werden.<br><br>
  <form method="POST" target="install.php">
    <input type="submit" name="go" value="Installation starten">
  </form>
  <?php
}

function versionFormatter($version) {
  return substr(
    str_pad(
      str_replace(
        '.', 
        '',
        $version
      ),
      3,
      '0',
      STR_PAD_RIGHT
    ),
    0,
    3
  );
}
?>
