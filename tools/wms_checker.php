<?php

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);

########################################################################################################################################################################
#																																																																																			 #
#	Dieses Skript kann in einem Web-Verzeichnis wie z.B. .../kvwmap/tools plaziert werden.																																							 #
# Es sind 2 Einstellungen zu machen: - die Variable $config muss auf den Pfad zur config.php gesetzt werden.																													 #
#                                    - das Array $bbox muss gültige BBox-Werte im EPSG-Code 4326 enthalten; damit werden die Test-Requests gemacht										 #
# Wenn man das Skript aufruft, werden alle WMS-Layer aus der in der config.php definierten DB ausgelesen und mit einem getMap-Request getestet.									 #
# Das Ergebnis des Tests wird in die Spalte status der Tabelle layer geschrieben. Diese Spalte wird von kvwmap ausgewertet und der Status in der Legende visualisiert. #
# Ruft man das Skript im Browser auf, erhält man außerdem eine Übersicht über die getesteten Layer.																																		 #
# Um den Status regelmäßig zu überprüfen, muss man sich einen entsprechenden cron-job einrichten, der das Skript aufruft. 																																		 #
#																																																																																			 #
########################################################################################################################################################################

$credentials = '../credentials.php';		# Pfad zur credentials.php (von tools aus kann er so bleiben)
$config = '../config.php';		# Pfad zur config.php (von tools aus kann er so bleiben)
$bbox = array("left" => 11.85321, "bottom" => 53.96559, "right" => 11.93711, "top" => 54.01517);		# BBox, mit der die Test-Requests gemacht werden

define('DBWRITE',true);

/**
* Die Funktion liefert das erste Word, welches nach $word in $str gefunden wird.
* Über die optionalen Parameter $delim1 und $delim2 kann man die Trennzeichen vor und nach dem Wort angeben.
* Wenn der optionale Parameter $last true ist, wird das letzte Vorkommen des Wortes verwendet.
*/
function get_first_word_after($str, $word, $delim1 = ' ', $delim2 = ' ', $last = false){
	if($last)$word_pos = strripos($str, $word);
	else $word_pos = stripos($str, $word);
	if($word_pos !== false){
		$str_from_word_pos = substr($str, $word_pos+strlen($word));
		$parts = explode($delim2, trim($str_from_word_pos, $delim1));
		return $parts[0];
	}
}

function rectObj($minx, $miny, $maxx, $maxy, $imageunits = 0){
	if (MAPSERVERVERSION >= 800) {
		return new RectObj($minx, $miny, $maxx, $maxy, $imageunits);
	}
	else {
		$rect = new RectObj();
		$rect->setextent($minx, $miny, $maxx, $maxy);
		return $rect;
	}
}

/**
 * checkStatus
 * 
* @param string $request ein getMap Request von dem der Status geprüft werden soll 
* gibt einen array mit 2 elementen zurück das erste element ist entweder true
*(abfrage war erfolgreich) oder false(d.h. abfrage war nicht erfolgreich) und das zweite
* ist wenn der erste wert false ist eine kurze Info was falsch ist.
*/
function checkStatus($request, $username, $password){
	#echo '<p>Check Status of layer with request: ' . $request . '<p>'; 
  $info = null;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $request);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 7);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	
	if($username != '')curl_setopt($ch, CURLOPT_USERPWD, $username.':'.$password);	
	$response = curl_exec($ch);
	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$header = substr($response, 0, $header_size);
	$data = substr($response, $header_size);
  if(!$response){
    $status = false;
    $info = "timeout";
  }
  else{
    if(strpos($header, '404 Not Found') !== false){
      $status = false;
      $info = 404;
    }
		elseif (strpos($header, '301 Moved Permanently') !== false OR strpos($header, '307 Temporary Redirect') !== false) {
			$new_location = trim(get_first_word_after($header, 'Location:', ' ', chr(10)));
			$info = '<p>' . substr($header, 0, strpos($header, 'Location:'));
			$result = checkStatus($new_location, $username, $password);
			$result[1] = $info . '<br>' . (string)$result[1];
      $result[2] = '<br>neue Location: <a href="' . $new_location . '" target="_blank">' . $new_location . '</a>';
			return $result;
		}
    else{
      if(strpos($data, '<?xml') === 0){
        $status = false;      
        $info = getExceptionCode($data);  
      }
      else{
				if(substr(get_first_word_after($response, 'Content-Type: '), 0, 5) != 'image'){
					$status = false;
					$info = substr(strip_tags($data), 0, 255);
				}
        else{
					$status = true;  
				}
      }
    }
  }
  return array($status, $info);
}

function getExceptionCode($data){
  $parser = xml_parser_create();
  xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE,1);
  xml_parse_into_struct($parser, $data, $values, $index);
  xml_parser_free($parser);
  $exceptionIndexList = $index["SERVICEEXCEPTION"];
  if(count($exceptionIndexList) > 0){
		foreach($exceptionIndexList as $ExceptionIndex){
			$shortErrorMessage = $values[$ExceptionIndex]["value"];
			return $shortErrorMessage;  
		}
  }  
}

include($config);
#include($credentials);
include(CLASSPATH.'log.php');
include(CLASSPATH.'postgresql.php');
$debug=new Debugger(DEBUGFILE);	# öffnen der Debug-log-datei
$userDb = new pgdatabase();
$userDb->open();

$params = [];
$sql = "
	SELECT
		key, 
		default_value
	FROM
		kvwmap.layer_parameter
";
$ret = $userDb->execSQL($sql);
while ($line = pg_fetch_assoc($ret[1])) {
	$params[$line['key']] = $line['default_value'];
}

$query = "SELECT * FROM kvwmap.layer WHERE connectiontype = 7";
# nur bestimmte Layer einschließen
#$with_layer_id = '1,2,3,4';
$with_layer_id = '';
if ($with_layer_id != '') {
	$query .= '	AND layer_id IN (' . $with_layer_id . ')';
}
# bestimmte Layer ausschließen
#$without_layer_id = '1,2,3,4';
$without_layer_id = '';
if ($without_layer_id != '') {
	$query .= '	AND layer_id NOT IN (' . $without_layer_id . ')';
}
#echo '<br>get layer with sql: ' . $query;
$ret = $userDb->execSQL($query);

while ($line = pg_fetch_assoc($ret[1])){
	$extent = rectObj($bbox['left'], $bbox['bottom'], $bbox['right'], $bbox['top']);
	foreach ($params AS $key => $value) {
		$line["connection"] = str_replace('$' . $key, $value, $line["connection"]);
	}
	$wgsProjection = new projectionObj("init=epsg:4326");
  $userProjection = new projectionObj("init=epsg:".$line["epsg_code"]);
	$extent->project($wgsProjection, $userProjection);
	$bounding = implode(",", array($extent->minx, $extent->miny, $extent->maxx, $extent->maxy));
	$exceptions = 'application/vnd.ogc.se_xml';
	$url = $line["connection"] . "&SERVICE=WMS&REQUEST=GetMap&EXCEPTIONS=" . $exceptions .  "&SRS=EPSG:" . $line["epsg_code"] . "&WIDTH=400&HEIGHT=400&BBOX=" . $bounding;
	if (strpos(strtolower($line["connection"]), 'version=') === false) $url .= '&VERSION=' . $line["wms_server_version"];
	if (strpos(strtolower($line["connection"]), 'layers=' ) === false) $url .= '&LAYERS='  . $line["wms_name"];
	if (strpos(strtolower($line["connection"]), 'format=' ) === false) $url .= '&FORMAT='  . $line["wms_format"];
	if (strpos(strtolower($line["connection"]), 'styles=' ) === false) $url .= '&STYLES=';
  $status = checkStatus($url, $line['wms_auth_username'], $line['wms_auth_password']);
	if (!$status[0] AND strpos($status[1], 'application/vnd.ogc.se_xml') !== false) {
		$url = str_replace('application/vnd.ogc.se_xml', 'XML', $url);
		$status = checkStatus($url, $line['wms_auth_username'], $line['wms_auth_password']);
	}
	if (!$status[0]) {
		$color = '#db5a5a';
	}
	else {
		$color = '#36908a';
	}
	echo '<div style="border: 1px solid black;width: 100%;padding: 10px;background-color: ' . $color . '">';
	echo '<a href="' . $url . '"target="_blank">' . $line["Name"] . "</a><br/>";
	if (!$status[0]) {
		echo 'nicht ok<br>' . $status[1] . $status[2];
		$query = "
			UPDATE
				kvwmap.layer
			SET
				status = '" . addslashes($status[1]) . "'
			WHERE
				layer_id = " . $line["layer_id"] . "
		";
	}
	else {
		echo ($status[0] != '' ? 'info: ' . $status[1] : '');
		echo 'ok<br>';
		$query = "
			UPDATE
				kvwmap.layer
			SET
				status = ''
			WHERE
				layer_id = " . $line["layer_id"] . "
		";
	}
	$result2 = $userDb->execSQL($query);
	echo '</div>';
}
?>