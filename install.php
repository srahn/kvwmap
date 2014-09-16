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

if(!file_exists('config.php')){		// wenn keine config.php vorhanden -> aus config-default.php auszeugen
	$config = file_get_contents('config-default.php');
	$cwd = getcwd();
	$applversion = basename($cwd);
	$rest = dirname($cwd);
	$www = basename($rest);
	$installpath = dirname($rest);
	$config = str_replace("define('APPLVERSION','kvwmap/');", "define('APPLVERSION','".$applversion."/');", $config);
	$config = str_replace("define('INSTALLPATH','/home/gisadmin/');", "define('INSTALLPATH','".$installpath."/');", $config);
	$config = str_replace("define('WWWROOT',INSTALLPATH.'apps/');", "define('WWWROOT',INSTALLPATH.'".$www."/');", $config);
	file_put_contents('config.php', $config);
	
}

include('config.php');
include(CLASSPATH.'log.php');
if(DEBUG_LEVEL>0) $debug=new debugfile(DEBUGFILE);	# öffnen der Debug-log-datei
include(CLASSPATH.'mysql.php');
$userDb = new database();
$userDb->host = MYSQL_HOST;
$userDb->user = MYSQL_USER;																			
$userDb->passwd = MYSQL_PASSWORD;															
$userDb->dbName = MYSQL_DBNAME;
if(!@$userDb->open()){
	$msg = 'Verbindungsaufbau zur MySQL-Datenbank nicht erfolgreich. Bitte legen Sie eine leere Datenbank an und setzen Sie in der config.php die Konstanten MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD und MYSQL_DBNAME.';
}
else{
	# Test ob Datenbank leer ist
	$sql = "SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA='".MYSQL_DBNAME."';";
	$ret = $userDb->execSQL($sql, 0, 0);
	$result = mysql_fetch_row($ret[1]);
	if($result[0] > 0){		// DB nicht leer -> Test ob es schon User gibt (also ob es ein gerade angelegtes Schema ist oder nicht)
		$sql = "SELECT COUNT(*) FROM user;";
		$ret = $userDb->execSQL($sql, 0, 0);
		$result = mysql_fetch_row($ret[1]);
		if($result[0] > 0){
			$msg = "Die MySQL-Datenbank ".MYSQL_DBNAME." ist nicht leer. Installation nicht möglich.";
		}
		else{		// User-Tabelle leer, also offenbar ein gerade angelegtes Schema -> Beispieldaten einlesen
			$msg = 'Um kvwmap benutzen zu können, können Sie jetzt einen ersten Nutzer und eine Admin-Stelle anlegen.';
			$ready_for_data_insert = true;
			if($_REQUEST['insert_data'] == 'Beispieldaten anlegen'){
				$filepath = LAYOUTPATH.'db/mysql/data/mysql_install_admin.sql';
				$queryret = $userDb->exec_file($filepath);
				if($queryret[0]){
					echo $queryret[1].'<br>Fehler beim Ausführen der Datei: '.$filepath.'<br>';
				}
				else{
					$msg = 'Beispieldaten erfolgreich eingelesen. Sie können kvwmap jetzt <a href="index.php">verwenden</a>.<br><br>Nutzername: kvwmap<br>Passwort: kvwmap';
					$ready_for_data_insert = false;
				}
			}
		}
	}
	else{		// DB ist leer
		include(CLASSPATH.'postgresql.php');
		$PostGISdb=new pgdatabase_alkis();											
		$PostGISdb->host = POSTGRES_HOST;												
		$PostGISdb->user = POSTGRES_USER;													
		$PostGISdb->passwd = POSTGRES_PASSWORD;										
		$PostGISdb->dbName = POSTGRES_DBNAME;	
		if(!@$PostGISdb->open()){
			$msg = 'Verbindungsaufbau zur PostgreSQL-Datenbank nicht erfolgreich. Bitte geben Sie eine Datenbank mit PostGIS-Unterstützung an und setzen Sie in der config.php die Konstanten POSTGRES_HOST, POSTGRES_USER, POSTGRES_PASSWORD und POSTGRES_DBNAME.';
		}
		else{
			if($_REQUEST['create_schemas'] == 'Datenbank-Schemata anlegen'){
				include(CLASSPATH.'administration.php');
				$administration = new administration($userDb, $PostGISdb);
				$administration->get_database_status();
        $administration->update_databases();
				$administration->get_database_status();
				if(count($administration->migrations_to_execute['mysql']) == 0 AND count($administration->migrations_to_execute['postgresql']) == 0){
					$msg = 'Anlegen der Datenbank-Schemata erfolgreich.<br><br>Um kvwmap benutzen zu können, können Sie jetzt einen ersten Nutzer und eine Admin-Stelle anlegen.';
					$ready_for_data_insert = true;
				}
				else{
					if(count($administration->migrations_to_execute['mysql']) > 0){
						$msg = 'Anlegen des MySQL-Schemas fehlgeschlagen.';
					}
					if(count($administration->migrations_to_execute['postgresql']) > 0){
						$msg .= 'Anlegen des PostgreSQL-Schemas fehlgeschlagen.';
					}
				}
			}
			else{
				$msg = 'Verbindungsaufbau zur MySQL- und PostgeSQL-Datenbank erfolgreich. Sie können die Datenbank-Schemata jetzt anlegen.';
				$ready_for_schema_creation = true;
			}
		}
	}
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