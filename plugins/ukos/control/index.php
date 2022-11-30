<?php
include(PLUGINS . 'ukos/model/kvwmap.php');

function go_switch_ukos($go){
	global $GUI;
	switch($go) {
		case 'ukos_show_doppikklassen' : {
			$GUI->ukos_show_doppikklassen();
		} break;

		case 'ukos_new_doppikobjekt' : {
			$GUI->sanitize(['schema_name' => 'text', 'table_name' => 'text', 'geometry_type' => 'int']);
			$GUI->ukos_new_doppikobjekt($GUI->formvars['schema_name'], $GUI->formvars['table_name'], $GUI->formvars['geometry_type']);
		} break;

		default : {
			$GUI->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
}
?>