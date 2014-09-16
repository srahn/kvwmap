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

if(!file_exists('config.php')){
	copy('config-default.php', 'config.php');
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
if(!$userDb->open()){
	$msg = 'Verbindungsaufbau zur MySQL-Datenbank nicht erfolgreich. Bitte legen Sie eine leere Datenbank an und setzen Sie in der config.php die Konstanten MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD und MYSQL_DBNAME.';
}
else{
	# Test ob Datenbank leer ist
	$sql = "SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA='".MYSQL_DBNAME."';";
	$ret = $userDb->execSQL($sql, 4, 0);
	$result = mysql_fetch_row($ret[1]);
	if($result[0] > 0){
		$msg = "Die MySQL-Datenbank ".MYSQL_DBNAME." ist nicht leer. Installation nicht möglich.";
	}
	else{		// DB ist leer
		include(CLASSPATH.'postgresql.php');
		$PostGISdb=new pgdatabase();											
		$PostGISdb->host = POSTGRES_HOST;												
		$PostGISdb->user = POSTGRES_USER;													
		$PostGISdb->passwd = POSTGRES_PASSWORD;										
		$PostGISdb->dbName = POSTGRES_DBNAME;	
		if(!$PostGISdb->open()){
			$msg = 'Verbindungsaufbau zur PostgreSQL-Datenbank nicht erfolgreich. Bitte geben Sie eine Datenbank mit PostGIS-Unterstützung an und setzen Sie in der config.php die Konstanten POSTGRES_HOST, POSTGRES_USER, POSTGRES_PASSWORD und POSTGRES_DBNAME.';
		}
		else{
			if($_REQUEST['create_schemas'] == 'Datenbank-Schemata anlegen'){
				
			}
			else{
				$msg = 'Verbindungsaufbau zur MySQL- und PostgeSQL-Datenbank erfolgreich. Sie können das Datenbank-Schemata jetzt anlegen.';
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
							<td width="400" colspan="2"><span class="red"><?php echo urldecode($msg); ?></span></td>
						</tr>
						<? if($ready_for_schema_creation){ ?>
						<tr>
							<td colspan="2" align="center"><input type="submit" name="create_schemas" value="Datenbank-Schemata anlegen"/></td>
						</tr>
						<? } ?>
					</table>
				</td>
			</tr>
		</table>
	</form>
 </body>
</html>