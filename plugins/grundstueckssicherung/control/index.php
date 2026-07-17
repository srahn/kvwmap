<?php
// Use Cases
// grstsich_create_flurstuecksrechte
// grstsich_create_nutzungsrechte
//grstsich_order_eigentuemerdaten

include_once(CLASSPATH . 'FormObject.php');
include_once(PLUGINS . 'grundstueckssicherung/model/kvwmap.php');
#include_once(PLUGINS . 'metadata/model/rechte.php');

function go_switch_grundstueckssicherung($go){
	global $GUI;
	switch($go) {
		case 'grstsich_create_flurstuecksrechte': {
			$GUI->sanitize([
				'vorhabensgebiet_id' => 'int'
			]);
			$GUI->checkCaseAllowed('grstsich_create_flurstuecksrechte');
			$result = $GUI->grstsich_create_flurstuecksrechte(
				$GUI->formvars['vorhabensgebiet_id']
			);
			if (!$result['success']) {
				$GUI->Fehlermeldung = $result['msg'];
			}
			$GUI->output();
		} break;

		case 'grstsich_create_nutzungsrechte': {
			$GUI->sanitize([
				'layer_id' => 'int',
				'feature_id' => 'int',
				'rechteart_id' => 'int',
				'buffer' => 'float'
			]);
			$GUI->checkCaseAllowed('grstsich_create_rechte');
			$result = $GUI->grstsich_create_nutzungsrechte(
				$GUI->formvars['layer_id'],
				$GUI->formvars['feature_id'],
				$GUI->formvars['rechteart_id'],
				$GUI->formvars['buffer']
			);
			if (!$result['success']) {
				$GUI->Fehlermeldung = $result['msg'];
			}
			$GUI->output();
		} break;

		case 'grstsich_order_eigentuemerdaten' : {
			$GUI->sanitize([
				'vorhabensgebiet_id' => 'int'
			]);
			$GUI->checkCaseAllowed('grstsich_order_eigentuemerdaten');
			$result = $GUI->grstsich_order_eigentuemerdaten(
				$GUI->formvars['vorhabensgebiet_id']
			);
			if (!$result['success']) {
				$GUI->Fehlermeldung = $result['msg'];
			}
			$GUI->output();
		} break;

		default : {
			$GUI->goNotExecutedInPlugins = true; // in diesem Plugin wurde go nicht ausgeführt
		}
	}
}

?>