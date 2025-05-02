<?

class routing {
  
  function __construct($rolle, $pgdatabase) {
    global $debug;
    $this->debug = $debug;
    $this->pgdatabase = $pgdatabase;
		$this->rolle = $rolle;
  }

  function getRoute($formvars){
    include_(CLASSPATH.'spatial_processor.php');
		$spatial_processor = new spatial_processor($this->rolle, $this->pgdatabase);
		$projFROM = new projectionObj("init=epsg:".$this->rolle->epsg_code);
		$projTO = new projectionObj("init=epsg:4326");
		$start = explode(',', $formvars['start']);
		$end = explode(',', $formvars['end']);
		$startpoint = new PointObj();
		$endpoint = new PointObj();
	  $startpoint->setXY($start[0], $start[1]);
		$endpoint->setXY($end[0], $end[1]);
		$startpoint->project($projFROM, $projTO);
		$endpoint->project($projFROM, $projTO);
		# driving-car
    # cycling-road
		$request = str_replace(['$start', '$end'], [$startpoint->x . ',' . $startpoint->y, $endpoint->x . ',' . $endpoint->y], ROUTING_URL);
		$res = url_get_contents($request);
		$json = json_decode($res);
		$geom = $spatial_processor->getGeomFromGeoJSON(json_encode($json->features[0]->geometry), $this->rolle->epsg_code);
    return $geom;
  }

}

?>