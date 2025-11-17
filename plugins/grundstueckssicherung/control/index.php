<?php
// Use Cases
// grstsich_create_rechte

include_once(CLASSPATH . 'FormObject.php');
include_once(PLUGINS . 'grundstueckssicherung/model/kvwmap.php');
#include_once(PLUGINS . 'metadata/model/rechte.php');

function go_switch_grundstueckssicherung($go){
	global $GUI;
	switch($go) {
		case 'grstsich_create_rechte': {
			$GUI->sanitize([
				'layer_id' => 'int',
				'feature_id' => 'int',
				'rechteart_id' => 'int',
				'buffer' => 'float'
			]);
			$GUI->checkCaseAllowed('grstsich_create_rechte');
			$result = $GUI->grstsich_create_rechte(
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

		default : {
			$GUI->goNotExecutedInPlugins = true; // in diesem Plugin wurde go nicht ausgeführt
		}
	}
}

?>