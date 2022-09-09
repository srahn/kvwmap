<?php
include(PLUGINS . 'portal/model/kvwmap.php');
function go_switch_portal($go){
	global $GUI;
	switch($GUI->go) {
		case 'Layer2Stelle_Reihenfolge_Layerdef' : {
			$GUI->sanitize(['selected_stelle_id' => 'int']);
			$GUI->checkCaseAllowed('Stellen_Anzeigen');
			$GUI->Layer2Stelle_Reihenfolge_Layerdef();
		} break;

		default : {
			$GUI->goNotExecutedInPlugins = true; // in diesem Plugin wurde go nicht ausgeführt
		}
	}
}
?>