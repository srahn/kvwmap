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

error_reporting(E_ALL & ~E_NOTICE);
output_header();
install();
output_footer();

function output_header() {
  ?><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
   <head>
    <title>kvmwap Install</title>
    <META http-equiv=Content-Type content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="layouts/main.css">
   </head>
   <body><?php
}

function output_footer() {
  ?></body>
  </html><?php
}

function install() {
  if (!file_exists('config.php')) {
    install_config();
  }
  
  include('config.php');
  show_constants();

  include(CLASSPATH.'log.php');
  if(DEBUG_LEVEL > 0) $debug = new debugfile(DEBUGFILE);
  ?>
  <h1>Teste MySQL-Server Verbindung</h1><?php
  
  #
  # Teste ob MySQL-Server läuft
  #
  include(CLASSPATH . 'mysql.php');
  $mysqlRootDb = new database;
  $mysqlRootDb->host = MYSQL_HOST;
  $mysqlRootDb->user = 'root';
  $mysqlRootDb->passwd = getenv('MYSQL_ENV_MYSQL_ROOT_PASSWORD');
  $mysqlRootDb->dbName = 'mysql'; ?>
  Verbindungsdaten für root-Zugang wie folgt gesetzt:<br>
  Host: <?php echo $mysqlRootDb->host; ?><br>
  User: <?php echo $mysqlRootDb->user; ?><br>
  Password: <?php echo $mysqlRootDb->passwd; ?><br>
  Datenbankname: <?php echo $mysqlRootDb->dbName; ?><br><?php
  
  if (mysql_exists($mysqlRootDb)) { ?>
    MySQL-Server läuft, Verbindung zur Datenbank <?php echo $mysqlRootDb->dbName; ?> kann mit Nutzer <?php echo $mysqlRootDb->user; ?>@<?php echo $mysqlRootDb->host; ?> hergestellt werden!<br><?php
  }
  else { ?>
    Es kann keine Verbindung zur Datenbank <?php echo $mysqlRootDb->dbName; ?> mit Nutzer <?php echo $mysqlRootDb->user; ?>@<?php echo $mysqlRootDb->host; ?> hergestellt werden.<br><?php
    return false;
  }

  #
  # Teste ob kvwmap Datenbank auf MySQL-Server läuft
  # und richte ggf. Nutzer und eine neue leere kvwmap Datenbank ein.
  #
  $mysqlKvwmapDb = new database();
  $mysqlKvwmapDb->host = MYSQL_HOST;
  $mysqlKvwmapDb->user = MYSQL_USER;
  $mysqlKvwmapDb->passwd = MYSQL_PASSWORD;
  $mysqlKvwmapDb->dbName = MYSQL_DBNAME;
  if (kvwmapdb_exists($mysqlKvwmapDb)) { ?>
    kvwmap Datenbank <?php echo $mysqlKvwmapDb->dbName; ?> existiert schon auf MySQL-Server.<br><?php
    $mysqlKvwmapDb->close();
  }
  else { ?>
    Es kann keine Verbindung zur kvwmap Datenbank <?php echo $mysqlKvwmapDb->dbName; ?> mit Nutzer <?php echo $mysqlKvwmapDb->user; ?>@<?php echo $mysqlKvwmapDb->host; ?> hergestellt werden.<br>
    Installiere kvwmap Datenbank auf MySQL-Server.<br><?php
    install_kvwmapdb($mysqlRootDb, $mysqlKvwmapDb);
    $mysqlRootDb->close();
    $mysqlKvwmapDb->close();
  }
  
  #
  # Teste PostgreSQL-Server läuft
  #
  include(CLASSPATH . 'posgresql.php');
  $pgsqlPostgresDb = new pgdatabase();
  $pgsqlPostgresDb->host = POSTGRES_HOST;                        
  $pgsqlPostgresDb->user = 'postgres';                          
  $pgsqlPostgresDb->passwd = getenv('PGSQL_ENV_PGSQL_ROOT_PASSWORD');;                    
  $pgsqlPostgresDb->dbName = 'postgres';
  if (postgresql_exists($pgsqlPostgresDb)) { ?>
    PostgreSQL-Server läuft, Verbindung zur Datenbank <?php echo $pgsqlPostgresDb; ?> kann mit Nutzer: <?php echo $pgsqlPostgresDb->user; ?> host: <?php echo $pgsqlPostgresDb->host; ?> hergestellt werden!<br><?php
  }
  else { ?>
    Es kann keine Verbindung zur PostgreSQL-Datenbank <?php echo $pgsqlPostgresDb->dbName; ?> mit Nutzer: <?php echo $pgsqlPostgresDb->user; ?> host: <?php echo $pgsqlPostgresDb->host; ?> hergestellt werden.<br><?php
    return false;
  }
  
  #
  # Teste ob kvwmap Datenbank auf PostgreSQL-Server läuft
  # und richte ggf. Nutzer und eine neue leere kvwmap Datenbank ein. 
  #
  $pgsqlKvwmapDb = new pgdatabase();
  $pgsqlKvwmapDb->host = POSTGRES_HOST;                        
  $pgsqlKvwmapDb->user = POSTGRES_USER;                          
  $pgsqlKvwmapDb->passwd = POSTGRES_PASSWORD;                   
  $pgsqlKvwmapDb->dbName = POSTGRES_DBNAME;
  if (kvwmapsp_exists($pgsqlKvwmapDb)) { ?>
    kvwmaps PostGIS Datenbank <?php echo $pgsqlKvwmapDb->dbName; ?> existiert schon auf PostgreSQL-Server.<br><?php
    $pgsqlKvwmapDb->close();
  }
  else { ?>
    Es kann keine Verbindung zur Postgres Datenbank <?php echo $pgsqlKvwmapDb->dbName; ?> mit Nutzer <?php echo $pgsqlKvwmapDb->user; ?> und host: <?php echo $pgsqlKvwmapDb->host; ?> hergestellt werden.<br>
    Installiere kvwmap Datenbank auf PostgreSQL-Server.<br><?php
    install_kvwmapsp($pgsqlPostgresDb, $pgsqlKvwmapDb);
    $pgsqlPostgresDb->close();
    $pgsqlKvwmapDb->close();
  }
  
  #
  # Führe alle Migration aus und richte damit die aktuellen Datenbankschemas ein.
  #
  migragte_databases($mysqlKvwmapDb, $pgsqlKvwmapDb);

  #
  # Richte eine Stelle für einen Administrator ein.
  #
  install_admin_stelle($mysqlKvwmapDb);
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
    "define(MYSQL_HOST, 'localhost');",
    "define(MYSQL_HOST, (getenv('MYSQL_PORT_3306_TCP_ADDR') == '') ? 'localhost' : getenv('MYSQL_PORT_3306_TCP_ADDR'));",
    $config
  );
  $config = str_replace("define(MYSQL_USER, '');", "define(MYSQL_USER, 'kvwmap');", $config);
  $config = str_replace("define(MYSQL_PASSWORD, '');", "define(MYSQL_PASSWORD, 'kvwmap');", $config);
  $config = str_replace(
    "define(MYSQLVERSION, '500');",
    "define(MYSQLVERSION, '" . versionFormatter(getenv('MYSQL_ENV_MYSQL_MAJOR')) . "');",
    $config
  );
  $config = str_replace(
    "define(POSTGRES_HOST, 'localhost');",
    "define(POSTGRES_HOST, (getenv('PGSQL_PORT_5432_TCP_ADDR') == '') ? 'localhost' : getenv('PGSQL_PORT_5432_TCP_ADDR'));",
    $config
  );
  $config = str_replace("define(POSTGRES_USER, '');", "define(POSTGRES_USER, 'kvwmap');", $config);
  $config = str_replace("define(POSTGRES_PASSWORD, '');", "define(POSTGRES_PASSWORD, 'kvwmap');", $config);
  $config = str_replace(
    "define(POSTGRESVERSION, '500');",
    "define(POSTGRESVERSION, '" . versionFormatter(getenv('PGSQL_ENV_PG_MAJOR')) . "');",
    $config
  );
  $config = str_replace(
    "define('MAPSERVERVERSION', '620');",
    "define('MAPSERVERVERSION', '" . versionFormatter(getMapServerVersion()) . "');",
    $config
  );
  $config = str_replace(
    "define('MAPSERVERVERSION', '450');",
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
    "define('POSTGRESBINPATH', '/usr/lib/postgresql/". getPostgreSQLVersion() . "/bin/');",
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
function mysql_exists($mysqlKvwmapDb) {
  return $mysqlKvwmapDb->open();
}

/*
* Testet ob es schon eine kvwmapdb gibt
*/
function kvwmapdb_exists($mysqlKvwmapDb) {
  return $mysqlKvwmapDb->open();
}

/*
* Installiert kvwmap-Datenbank
*/
function install_kvwmapdb ($mysqlRootDb, $mysqlKvwmapDb) {
  $sql = "
    CREATE USER '" . $mysqlKvwmapDb->user . "'@'" . $mysqlKvwmapDb->host . "' IDENTIFIED BY '" . $mysqlKvwmapDb->passwd . "';
    GRANT ALL PRIVILEGES ON * . * TO '" . $mysqlKvwmapDb->user . "'@'" . $mysqlKvwmapDb->host . "';
    CREATE DATABASE " . $mysqlKvwmapDb->dbName . " CHARACTER SET utf8 COLLATE utf8_general_ci;
    GRANT ALL PRIVILEGES ON " . $mysqlKvwmapDb->dbnName . ".* TO '" . $mysqlKvwmapDb->user . "'@'" . $mysqlKvwmapDb->host . "' IDENTIFIED BY '" . $mysqlKvwmapDb->passwd . "';
  ";
  $mysqlRootDb->execSQL($sql, 0, 0);  
  $mysqlRootDb->close();
  
  # Test ob kvwmap Datenbank leer ist
  $sql = "
    SELECT
      COUNT(*)
    FROM
      information_schema.TABLES
    WHERE
      TABLE_SCHEMA='" . $mysqlKvwmapDb->dbName . "'
  ";
  $ret = $mysqlKvwmapDb->execSQL($sql, 0, 0);
  $result = mysql_fetch_row($ret[1]);
  if($result[0] > 0) { ?>
    kvwmap Datenbank nicht leer. Test ob es schon User gibt (also ob es ein gerade angelegtes Schema ist oder nicht).<br><?php
    $sql = "
      SELECT
        COUNT(*)
      FROM
        user
    ";
    $ret = $mysqlKvwmapDb->execSQL($sql, 0, 0);
    $result = mysql_fetch_row($ret[1]);
    if($result[0] > 0) { ?>
      Die MySQL-Datenbank <?php echo $mysqlKvwmapDb->dbName; ?> ist nicht leer. Installation nicht möglich.<br><?php
    }
    else { ?>
      User-Tabelle leer, also offenbar ein gerade angelegtes Schema -> Beispieldaten einlesen.<br>
      Um kvwmap benutzen zu können, können Sie jetzt einen ersten Nutzer und eine Admin-Stelle anlegen.<br><?php
    }
  }
}

/*
* Testet ob die postgre Datenbank auf PostgreSQL-Server läuft
*/
function postgresql_exists($pgsqlPostgresDb) {
  return @$pgsqlPostgresDb->open();
}

/*
* Testet ob die kvwmap Datenbank auf PostgreSQL-Server läuft
*/

function kvwmapsp_exists($pgsqlKvwmapDb) {
  return @$pgsqlKvwmapDb->open();
}

/*
* Installiert PostGIS-Datenbank
*/
function install_kvwmapsp($pgsqlPostgresDb, $pgsqlKvwmapDb) {
  $sql = "
    CREATE ROLE
      '" . $pgsqlKvwmapDb->user . "'
    WITH
      SUPERUSER
      PASSWORD '" . $pgsqlKvwmapDb->passwd . "'
  ";
  $pgsqlPostgresDb->execSQL($sql, 0, 0);  
  $pgsqlPostgresDb->close();
}

/*
* 
*/
function migrate_databases($mysqlKvwmapDb, $pgsqlKvwmapDb) {
  include(CLASSPATH.'administration.php');
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
* Trägt alle Einstellungen für eine Admin-Stelle in MySQL-Datenbank von kvwmap ein.
*/
function install_admin_stelle($mysqlKvwmapDb) {
  $filepath = LAYOUTPATH . 'db/mysql/data/mysql_install_admin.sql';
  $queryret = $mysqlKvwmapDb->exec_file($filepath);
  if ($queryret[0]) { 
    echo $queryret[1]; ?><br>
    Fehler beim Ausführen der Datei: <?php echo $filepath; ?><br><?php
  }
  else { ?>
    Daten zur Einrichtung der Stelle Administration erfolgreich eingelesen.<br>
    Sie können sich jetzt mit folgenden Nutzerdaten bei kvwmap anmelden.<br>
    Nutzername: kvwmap<br>
    Passwort: kvwmap<br>
    <br>
    <a href="index.php">Login</a><?php
  }
}

function getMySQLVersion() {
  return getVersionFromText(
    shell_exec('mysql -V')
  );
}

function getPostgreSQLVersion() { 
  return getVersionFromText(
    shell_exec('psql -h $MYSQL_PORT_3306_TCP_ADDR -V')
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

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
 <head>
  <title><?php echo TITLE; ?></title>
  <META http-equiv=Content-Type content="text/html; charset=UTF-8">
  <link rel="stylesheet" href="layouts/main.css">
 </head>
 <body style="font-family: Arial, Verdana, Helvetica, sans-serif" onload="document.login.username.focus();">
  <form name="install" action="install.php" method="post">
    <input type="hidden" name="go" value="login">
    <br>
    <table align="center" cellspacing="4" cellpadding="12" bgcolor="<? echo BG_DEFAULT; ?>" border="0" style="background-color: <? echo BG_DEFAULT; ?>; box-shadow: 12px 10px 14px #777; border: 1px solid #bbbbbb; background: linear-gradient(<? echo BG_GLEATTRIBUTE; ?> 0%, <? echo BG_DEFAULT ?> 100%);">
      <tr>
        <td align="center">
          <table cellspacing="0" cellpadding="2" border="0">
            <tr>
              <td align="center" colspan="2"><h1>kvwmap&nbsp;Installation</h1></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td width="400" colspan="2"><span><?php echo urldecode($msg); ?></span></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <? if($ready_for_schema_creation){ ?>
            <tr>
              <td colspan="2" align="center"><input type="submit" name="create_schemas" value="Datenbank-Schemata anlegen"/></td>
            </tr>
            <? } ?>
            <? if($ready_for_data_insert){ ?>
            <tr>
              <td colspan="2" align="center"><input type="submit" name="insert_data" value="Beispieldaten anlegen"/></td>
            </tr>
            <? } ?>
          </table>
        </td>
      </tr>
    </table>
  </form>
 </body>
</html>