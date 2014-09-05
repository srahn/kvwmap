<?php
# examples for calls
# http://www.meine-domain.de/kvwmap_intern/api/1.0.0/zustaendigeStelle/?latitude=53.71181&longitude=11.97404&format=json
	$kvwmap_path = "kvwmap_intern";
	$stelleId = 50; # wenn keine Authentifizierung sein soll, Layer zu Gaststelle zuweisen und hier die Gaststelle auswählen
	$username = lumberjack;
	$passwort = lumbertest;
	$layerId = 455;
	$selectors = "gemnr, name, lk_nr, email, ansprechpartner, bearbeitungszeit, npa_allowed, www";
	
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

  #echo $url;
	echo file_get_contents($url);
?>