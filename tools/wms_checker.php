<?
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
  if(!curl_exec($ch)){
    $status = false;
    $info = "timeout";
  }
  else{
    $data = @file_get_contents($request);
    #print_r($http_response_header);
    if($http_response_header[0] == "HTTP/1.1 404 Not Found" OR $http_response_header[0] == "404 Not Found"){
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
  foreach($exceptionIndexList as $ExceptionIndex){
    $shortErrorMessage = $values[$ExceptionIndex]["value"];
    return $shortErrorMessage;  
  }  
}

$bbox = array("left" => 12.46746, "bottom" => 53.771865, "right" => 12.52526, "top" => 53.829665);
$con = mysql_connect("localhost", "kvwmap", "kv_Map-12:)");
mysql_select_db("kvwmapdb_dev");

$query = "SELECT * FROM `layer` WHERE connectiontype = 7";
$result = mysql_query($query, $con);

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
  
  #$epsg = $line["epsg_code"];
  echo '<a href="'.$url.'"target="_blank">'.$line["Name"]."</a><br/>";
  $status = checkStatus($url);
  if(!$status[0]){
    echo "nicht ok<br/>".$status[1]."<hr/>";
  }
  else{
    echo "ok<br/><hr/>";
  }
  
}

?>