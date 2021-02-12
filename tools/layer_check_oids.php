<?php

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);

########################################################################################################################################################################
#																																																																																			 #
#	Dieses Skript überprüft ob in Layerdefinitionen oids verwendet werden und welche ID-Spalte man stattdessen verwenden kann.																					 #
# Wenn man das Skript im Browser aufruft, werden alle PostGIS-Layer aus der in der credentials.php definierten MySQL-DB ausgelesen und überprüft.									 		 #
#																																																																																			 #
########################################################################################################################################################################

$credentials = '../credentials.php';		# Pfad zur credentials.php (von tools aus kann er so bleiben)
$config = '../config.php';		# Pfad zur config.php (von tools aus kann er so bleiben)

define('DBWRITE', 1);
define('EPSGCODE_ALKIS', -1);

include($config);
include($credentials);
include(CLASSPATH.'log.php');
include(CLASSPATH.'mysql.php');
include(CLASSPATH.'postgresql.php');
$debug=new Debugger(DEBUGFILE);	# öffnen der Debug-log-datei
if (LOG_LEVEL > 0) {
 $log_mysql = new LogFile(LOGFILE_MYSQL,'text','Log-Datei MySQL', '#------v: ' . date("Y:m:d H:i:s", time()));
 $log_postgres = new LogFile(LOGFILE_POSTGRES, 'text', 'Log-Datei Postgres', '------v: ' . date("Y:m:d H:i:s", time()));
}
$userDb = new database();
$userDb->host = MYSQL_HOST;
$userDb->user = MYSQL_USER;																			
$userDb->passwd = MYSQL_PASSWORD;															
$userDb->dbName = MYSQL_DBNAME;
$userDb->open();
$pgdatabase = new pgdatabase();
$pgdatabase->open(0);

function checkStatus($layer){
	$status['oid'] = ($layer['oid'] == 'oid' ? false : true);
	$status['query'] = ((strpos($layer['pfad'], ' oid') !== false OR strpos($layer['pfad'], ',oid') !== false)? false : true);
	$status['data'] = (strpos($layer['Data'], 'oid') !== false ? false : true);
	return $status;
}

function get_oid_alternative($layer){
	global $pgdatabase;
	if ($layer['maintable'] == ''){
		$result['error'] = 'Haupttabelle ist nicht gesetzt.';
	}
	else {
		$sql = "
			SELECT 
				a.attname as pk
			FROM 
				pg_attribute a 
				LEFT JOIN pg_index i ON a.attrelid = i.indrelid AND a.attnum = ANY(i.indkey) AND i.indnatts = 1
			WHERE  
				a.attrelid = '" . ($layer['schema'] ?: 'public'). "." . $layer['maintable'] . "'::regclass and 
				attnum > 0 and 
				attisdropped is false and 
				(pg_get_serial_sequence('" . ($layer['schema'] ?: 'public'). "." . $layer['maintable'] . "', attname) IS NOT NULL OR i.indisunique)
		";
		$ret = @pg_query($pgdatabase->dbConn, $sql);
		if($ret == false){
			$result['error'] = pg_last_error($pgdatabase->dbConn);
		}
		else{
			$rs=pg_fetch_assoc($ret);
			$result['oid_alternative'] = $rs['pk'];
		}
	}
	return $result;
}

$color[false] = '#db5a5a';
$color[true] = '#36908a';

$query = "SELECT * FROM `layer` WHERE connectiontype = 6 ORDER BY name";

# nur bestimmte Layer einschließen
#$with_layer_id = '1,2,3,4';
$with_layer_id = '';
if ($with_layer_id != '') {
	$query .= '	AND Layer_ID IN (' . $with_layer_id . ')';
}
# bestimmte Layer ausschließen
#$without_layer_id = '1,2,3,4';
$without_layer_id = '';
if ($without_layer_id != '') {
	$query .= '	AND Layer_ID NOT IN (' . $without_layer_id . ')';
}

#echo '<br>get layer with sql: ' . $query;
$result = $userDb->execSQL($query);

?>

<style>
	td{
		border: 1px solid black;
		padding: 5px;
	}
	
	textarea{
		height: 38px;
	}
	
	#main{
		width: 100%
	}
</style>

<table id="main">
	<tr>
		<td>
			Layer
		</td>
		<td>
			ID-Spalte
		</td>
		<td>
			Query
		</td>
		<td>
			Data
		</td>
		<td>
			Haupttabelle
		</td>
		<td>
			Fehlermeldung
		</td>
		<td>
			oid-Alternative
		</td>
	</tr>

<?
while($layer = $userDb->result->fetch_assoc()){
  $status = checkStatus($layer);
	$result = array();
	if (!$status['oid']) {
		$result = get_oid_alternative($layer);
	}
		
  echo '
		<tr>
			<td>
				<a target="_blank" href="../index.php?go=Layereditor&selected_layer_id='.$layer["Layer_ID"].'"target="_blank">'.$layer["Name"].'</a>
			</td>
			<td style="background-color: '.$color[$status['oid']].'">
				' . $layer['oid'] . '
			</td>
			<td style="background-color: '.$color[$status['query']].'">
				<textarea>' . $layer['pfad'] . '</textarea>
			</td>
			<td style="background-color: '.$color[$status['data']].'">
				<textarea>' . $layer['Data'] . '</textarea>
			</td>
			<td>
				' . $layer['maintable'] . '
			</td>
			<td>
				' . $result['error'] . '
			</td>
			<td>
				' . $result['oid_alternative'] . '
			</td<
		</tr>';
}
echo '</table>';

?>