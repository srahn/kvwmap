<?php
# examples for calls
# http://www.gdi-service.de/kvwmap_intern/api/1.0.0/flurstuecke/?latitude=53.71181&longitude=11.97404&format=json
# example for response
# {"landId":"13","kreisId":"76","gemeindId":"15","gemarkungId":"530","gemarkungName":"Rothen","flurId":"1","flurstueckId":"130530-001-00034\/001.00","flurstueckNummer":"34\/1"}

	$kvwmap_path = "kvwmap_intern";
	$stelleId = 50; # wenn keine Authentifizierung sein soll, Layer zu Gaststelle zuweisen und hier die Gaststelle auswählen
	$layerId = 751; # Flurstuecke
	$selectors = "landId, kreisId, gemeindeId, gemeindeName, gemarkungId, gemarkungName, flurId, flurstueckId, flurstueckNummer";
	$username = $_REQUEST['username'];
	$passwort = $_REQUEST['passwort'];
	# uncommend these two lines to give users access without mandatory username and passwort
	#$username = 'lumberjack';
	#$passwort = 'lumbertest';
	
	# default Values (if not set, we set here the default)
	$_REQUEST['epsg_code'] == '' ? $epsg_code = 4326     : $epsg_code = $_REQUEST['epsg_code'];  # epsg_code of search point coordinates
	$_REQUEST['latitude'] == ''  ? $latitude = 53.71181  : $latitude = $_REQUEST['latitude']; # latitude of search point
	$_REQUEST['longitude'] == '' ? $longitude = 11.97404 : $longitude = $_REQUEST['longitude']; # longitude of search point
	$_REQUEST['anzahl'] == ''    ? $anzahl = 1           : $anzahl =  $_REQUEST['anzahl']; # limit for query result rows
	$_REQUEST['format'] == ''    ? $format = 'json'      : $format = $_REQUEST['format']; # output format, return result records as array, if anzahl > 1

  $url = "http://" . $_SERVER['HTTP_HOST'] . "/" . $kvwmap_path . "/index.php?Stelle_ID=" . $stelleId."&username=" . $username . "&passwort=" . $passwort . "&go=Layer-Suche_Suchen&selected_layer_id=" . $layerId . "&newpathwkt=" . urlencode("POINT(" . $longitude . " " . $latitude . ")") . "&epsg_code=" . $epsg_code . "&mime_type=formatter&format=" . $format . "&selectors=" . urlencode($selectors);
	
	if ($format == 'jsonp' && $_REQUEST['callback'] != '') {
		$url = $url . "&callback=" . $_REQUEST['callback']; # name of the callback function in jsonp format
	}

  #echo $url.'</br></br>';
	echo file_get_contents($url);
?>