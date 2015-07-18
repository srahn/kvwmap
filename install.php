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
output_header();
install();
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
  $mysqlRootDb->passwd = getenv('MYSQL_ENV_MYSQL_ROOT_PASSWORD');
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
    Es kann keine Verbindung zur Datenbank <?php echo $mysqlRootDb->dbName; ?> mit Nutzer <?php echo $mysqlRootDb->user; ?>@<?php echo $mysqlRootDb->host; ?> hergestellt werden.<br><?php
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
    Es kann keine Verbindung zur kvwmap Datenbank <?php echo $mysqlKvwmapDb->dbName; ?> mit Nutzer <?php echo $mysqlKvwmapDb->user; ?>@<?php echo $mysqlKvwmapDb->host; ?> hergestellt werden.<br>
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
  $pgsqlPostgresDb->passwd = getenv('PGSQL_ENV_POSTGRES_PASSWORD');                    
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
    Es kann keine Verbindung zur PostgreSQL-Datenbank <?php echo $pgsqlPostgresDb->dbName; ?> mit Nutzer: <?php echo $pgsqlPostgresDb->user; ?> host: <?php echo $pgsqlPostgresDb->host; ?> hergestellt werden.<br><?php
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
    "define('MYSQL_HOST', (getenv('MYSQL_PORT_3306_TCP_ADDR') == '') ? 'localhost' : getenv('MYSQL_PORT_3306_TCP_ADDR'));",
    $config
  );
  $config = str_replace("define('MYSQL_USER', '');", "define('MYSQL_USER', 'kvwmap');", $config);
  $config = str_replace("define('MYSQL_PASSWORD', '');", "define('MYSQL_PASSWORD', 'kvwmap');", $config);
  $config = str_replace(
    "define('MYSQLVERSION', '500');",
    "define('MYSQLVERSION', '" . versionFormatter(getenv('MYSQL_ENV_MYSQL_MAJOR')) . "');",
    $config
  );
  $config = str_replace(
    "define('POSTGRES_HOST', 'localhost');",
    "define('POSTGRES_HOST', (getenv('PGSQL_PORT_5432_TCP_ADDR') == '') ? 'localhost' : getenv('PGSQL_PORT_5432_TCP_ADDR'));",
    $config
  );
  $config = str_replace("define('POSTGRES_USER', '');", "define('POSTGRES_USER', 'kvwmap');", $config);
  $config = str_replace("define('POSTGRES_PASSWORD', '');", "define('POSTGRES_PASSWORD', 'kvwmap');", $config);
  $config = str_replace(
    "define('POSTGRESVERSION', '500');",
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
    "define('POSTGRESBINPATH', '/usr/bin/');",
    $config
  );

  $config = str_replace(
    "define('OGR_BINPATH', '/usr/local/bin/');",
    "define('OGR_BINPATH', '/usr/bin/');",
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
function install_kvwmapdb ($mysqlRootDb, $mysqlKvwmapDb) { ?>
  Erzeuge Nutzer: <?php echo $mysqlKvwmapDb->user; ?><br><?php
  $sql = "
    CREATE USER '" . $mysqlKvwmapDb->user . "'@'172.17.%'
    IDENTIFIED BY '" . $mysqlKvwmapDb->passwd . "'
  ";
  $mysqlRootDb->execSQL($sql, 0, 1); ?>
  
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
    TO '" . $mysqlKvwmapDb->user . "'@'172.17.%'
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
  return @$pgsqlPostgresDb->open();
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
  $success = false;
  $filepath = LAYOUTPATH . 'db/mysql/data/mysql_install_admin.sql';
  $queryret = $mysqlKvwmapDb->exec_file($filepath, NULL, NULL);
  if ($queryret[0]) { 
    echo $queryret[1]; ?>
    Fehler beim Ausführen der Datei: <?php echo $filepath; ?><br><?php
  }
  else { ?>
    Daten zur Einrichtung der Stelle Administration erfolgreich eingelesen.<br>
    Sie können sich jetzt mit folgenden Nutzerdaten bei kvwmap anmelden.<br>
    Nutzername: kvwmap<br>
    Passwort: kvwmap<br>
    <br>
    <a href="index.php">Login</a><?php
    $success = true;
  }
  return $success;
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
