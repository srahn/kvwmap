<?php

include_once(PLUGINS . 'metadata/model/kvwmap.php');
include_once(PLUGINS . 'metadata/model/metadaten.php');
include_once(CLASSPATH.'FormObject.php');

function go_switch_metadata($go){
	global $GUI;
	switch($go) {
		case 'Metadaten_Recherche' : {
			$GUI->metadaten_suche();
		} break;

		case 'Metadaten_Auswaehlen_Senden' : {
			$GUI->metadatenSuchen();
		} break;

		case 'Metadateneingabe' : {
			$GUI->metadateneingabe();
		} break;

		default : {
			$GUI->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
}

?>