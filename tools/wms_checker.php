<?

$config = '../config.php';		# Pfad zur config.php
$bbox = array("left" => 11.85321, "bottom" => 53.96559, "right" => 11.93711, "top" => 54.01517);		# BBox, mit der die Test-Requests gemacht werden

/*
* @params(string) $request ein getMap Request von dem der Status geprüft werden soll 
* gibt einen array mit 2 elementen zurück das erste element ist entweder true
*(abfrage war erfolgreich) oder false(d.h. abfrage war nicht erfolgreich) und das zweite
* ist wenn der erste wert false ist eine kurze Info was falsch ist.
*/
function checkStatus($request){
  $info = null;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $request);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,1);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	$response = curl_exec($ch);
	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$header = substr($response, 0, $header_size);
  if(!$response){
    $status = false;
    $info = "timeout";
  }
  else{
    $data = $response;
    if(strpos($header, '404 Not Found') !== false){
      $status = false;
      $info = 404;
    }
    else{
      if(strpos($data, '<?xml') === 0){
        $status = false;      
        $info = getExceptionCode($data);  
      }
      else{
        $status = true;  
      }
    }
  }
  return array($status,$info);
}

function getExceptionCode($data){
  $parser = xml_parser_create();
  xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE,1);
  xml_parse_into_struct($parser, $data, $values, $index);
  xml_parser_free($parser);
  $exceptionIndexList = $index["SERVICEEXCEPTION"];
  if(count($exceptionIndexList) > 1){
		foreach($exceptionIndexList as $ExceptionIndex){
			$shortErrorMessage = $values[$ExceptionIndex]["value"];
			return $shortErrorMessage;  
		}
  }  
}


include($config);

$userDb->open();
$query = "SELECT * FROM `layer` WHERE connectiontype = 7";
$result = mysql_query($query, $userDb->dbConn);

while($line = mysql_fetch_array($result)){
  try{
    $extent = ms_newRectObj();
  }
  catch(Exception $e) {
    $extent = new rectObj();
  }		
  $extent->setextent($bbox['left'],$bbox['bottom'],$bbox['right'],$bbox['top']);
  $wgsProjection = ms_newprojectionobj("init=epsg:4326");
  $userProjection = ms_newprojectionobj("init=epsg:".$line["epsg_code"]);
  $extent->project($wgsProjection, $userProjection);
  $bounding = implode(",", array($extent->minx, $extent->miny, $extent->maxx, $extent->maxy));
  
  $url = $line["connection"]."&REQUEST=GetMap&EXCEPTIONS=XML&SRS=EPSG:".$line["epsg_code"]."&WIDTH=400&HEIGHT=400&BBOX=".$bounding;
  $status = checkStatus($url);
	
	if(!$status[0])$color = '#db5a5a';
	else $color = '#36908a';
	
  echo '<div style="border: 1px solid black;width: 700px;padding: 10px;background-color: '.$color.'">';  
	echo '<a href="'.$url.'"target="_blank">'.$line["Name"]."</a><br/>";
  if(!$status[0]){
    echo 'nicht ok<br>'.$status[1];
  }
  else{
    echo 'ok<br>';
  }
	echo '</div>';
}

?>