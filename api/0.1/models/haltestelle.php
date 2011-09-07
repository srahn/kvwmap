<?php
include('databaserelation.php');

class Haltestelle extends Databaserelation {

  function findByRadius($lat, $lng, $radius) {
    $radius_in_meter = $radius * 1000;
	$sql = "SELECT oid, standort, buslinien, Y(the_geom) AS latitude, X(the_geom) AS longitude, ST_distance(transform(GeometryFromText('POINT(".$lng." ".$lat.")',4326),25833),transform(the_geom,25833)) AS distance FROM ".$this->dbschema.".bushaltestellen WHERE ST_distance(transform(GeometryFromText('POINT(".$lng." ".$lat.")',4326),25833),transform(the_geom,25833)) < ".$radius_in_meter." ORDER BY distance LIMIT 10";
	$data = $this->findBySQL($sql);
	return $data;
  } # end of function findByRadius
  
  function output($haltestellen, $radius, $format) {
	switch (strtolower($format)) {
		case 'json' : {
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: text/json; charset=windows-1252');
			$outputStr = '{ "numResults": '.count($haltestellen).',';
			$outputStr .= '  "radius": '.$radius.',';
			$outputStr .= '   "haltestellen": [';
			for ($i=0; $i < count($haltestellen); $i++) {
				if 	($i>0) { $outputStr .= ','; }
				$outputStr .=' { "oid": '.$haltestellen[$i]->oid.',';
				$outputStr .='	 "name": "'.$haltestellen[$i]->standort.'",';
				$outputStr .='	 "buslines": "'.$haltestellen[$i]->buslinien.'",';
				$outputStr .='	 "lat": '.$haltestellen[$i]->latitude.',';
				$outputStr .='	 "lng": '.$haltestellen[$i]->longitude.',';
				$outputStr .='	 "distance": '.round($haltestellen[$i]->distance/1000,1);
				$outputStr .=' }';
			}
			$outputStr .= ']}';
		} break;
		
		default : {
			$outputStr = var_dump($haltestellen[0]);
		}
	}
	echo $outputStr;
  } # end of function output
}
?>