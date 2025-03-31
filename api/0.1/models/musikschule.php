<?php
include('databaserelation.php');

class Musikschule extends Databaserelation {

  function findByRadius($lat, $lng, $radius, $limit) {
    $radius_in_meter = $radius * 1000;
	if ($limit == '') $limit = 10;
	$sql  = "SELECT name, abkuerzung, Y(the_geom) AS latitude, X(the_geom) AS longitude, ST_distance(st_transform(GeometryFromText('POINT(".$lng." ".$lat.")',4326),25833),transform(the_geom,25833)) AS distance FROM ".$this->dbschema.".musikschulstandorte WHERE ST_distance(st_transform(GeometryFromText('POINT(".$lng." ".$lat.")',4326),25833),transform(the_geom,25833)) < ".$radius_in_meter;
	$sql .= " ORDER BY distance LIMIT ".$limit;
	#echo $sql;
	$data = $this->findBySQL($sql);
	return $data;
  } # end of function findByRadius
  
  function output($musikschulen, $radius, $format) {
	switch (strtolower($format)) {
		case 'html' : {
			header('Content-type: text/html; charset=windows-1252');
			$outputStr = '<h2>Musikschulen in der Nähe</h2><br>';
			$outputStr .= '<table cellspacing="0" cellpadding="2">';
			$outputStr .= '<tr>
			                 <th>Entfernung</th>
							 <th>Name</th>
							 <th>Abk&uuml;rzung</th>
							 <th>Koordinaten</th>
						   </tr>';
			for ($i=0; $i < count($musikschulen); $i++) {
				$outputStr .='<tr>';
				$outputStr .='<td>'.round($musikschulen[$i]->distance/1000,1).'</td>';
				$outputStr .='<td>'.$musikschulen[$i]->name.'</td>';
				$outputStr .='<td>'.$musikschulen[$i]->abkuerzung.'</td>';
				$outputStr .='<td>'.round($musikschulen[$i]->latitude,4).' '.round($musikschulen[$i]->longitude,4).'</td>';
				$outputStr .='</tr>';
			}
			$outputStr .= '</table>';
		} break;
		
		default : {
			$outputStr = var_dump($musikschulen[0]);
		}
	}
	echo $outputStr;
  } # end of function output
}
?>