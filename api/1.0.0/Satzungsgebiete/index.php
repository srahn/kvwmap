<?php
	$stelleId = 3; # wenn keine Authentifizierung sein soll, Layer zu Gaststelle zuweisen und hier die Gaststelle auswählen
	$layerId = 747; # Gemeinden
	$selectors = "id, name, type, erlaubter_durchmesser";
	
	# default Values (if not set, we set here the default)
	$_REQUEST['epsg_code'] == '' ? $epsg_code = 4326     : $epsg_code = $_REQUEST['epsg_code'];  # epsg_code of search point coordinates
	$_REQUEST['latitude'] == ''  ? $latitude = 53.71181  : $latitude = $_REQUEST['latitude']; # latitude of search point
	$_REQUEST['longitude'] == '' ? $longitude = 11.97404 : $longitude = $_REQUEST['longitude']; # longitude of search point
	$_REQUEST['anzahl'] == ''    ? $anzahl = 1           : $anzahl =  $_REQUEST['anzahl']; # limit for query result rows
	$_REQUEST['format'] == ''    ? $format = 'json'      : $format = $_REQUEST['format']; # output format, return result records as array, if anzahl > 1
	
	# examples for calls
	# http://www.gdi-service.de/kvwmap_intern/api/1.0.0/Satzungsgebiete/?latitude=53.71181&longitude=11.97404
	
  $url = "../../../index.php?Stelle_ID=".$stelleId."&go=Layer-Suche_Suchen&selected_layer_id=".$layerId."&newpathwkt=POINT(".$longitude." ".$latitude.")&epsg_code=".$epsg_code."&mime_type=formatter&format=".$format."&selectors=".$selectors;
	header("location: ".$url);
?>