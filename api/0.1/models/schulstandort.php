<?php
include('databaserelation.php');

class Schulstandort extends Databaserelation {

  function findByRadius($lat, $lng, $radius, $schulform, $limit) {
    $radius_in_meter = $radius * 1000;
    if ($limit == '') $limit = 10;
    $sql  = "SELECT id, nr_nach_kultusmin_mv, schulname, strasse_und_hausnummer, plz||' '||ort AS adresse, Y(the_geom) AS latitude, X(the_geom) AS longitude, schulform, ST_distance(st_transform(GeometryFromText('POINT(".$lng." ".$lat.")',4326),25833),transform(the_geom,25833)) AS distance FROM ".$this->dbschema.".schulstandorte WHERE ST_distance(st_transform(GeometryFromText('POINT(".$lng." ".$lat.")',4326),25833),transform(the_geom,25833)) < ".$radius_in_meter;
    if ($schulform != '') {
      $sql .= "AND schulform = '".$schulform."'";
    }
    $sql .= " ORDER BY distance LIMIT ".$limit;
    #echo $sql;
    $data = $this->findBySQL($sql);
    return $data;
  } # end of function findByRadius
  
  function output($schulstandorte, $radius, $format) {
    switch (strtolower($format)) {
      case 'html' : {
        header('Content-type: text/html; charset=windows-1252');
        $outputStr = '<h2>Schulstandorte in der Nähe</h2><br>';
        $outputStr .= '<table cellspacing="0" cellpadding="2">';
        $outputStr .= '<tr>
                         <th>Entfernung</th>
                 <th>Name</th>
                 <th>Adresse</th>
                 </tr>';
        for ($i=0; $i < count($schulstandorte); $i++) {
          $outputStr .='<tr>';
          $outputStr .='<td>'.round($schulstandorte[$i]->distance/1000,1).'</td>';
          $outputStr .='<td>'.$schulstandorte[$i]->schulname.'</td>';
          $outputStr .='<td>'.$schulstandorte[$i]->strasse_und_hausnummer.'<br>'.$schulstandorte[$i]->adresse.'<br>('.round($schulstandorte[$i]->latitude,4).' '.round($schulstandorte[$i]->longitude,4).')</td>';
          $outputStr .='</tr>';
        }
        $outputStr .= '</table>';
      } break;
      
      default : {
        $outputStr = var_dump($schulstandorte[0]);
      }
    }
    echo $outputStr;
  } # end of function output
}
?>