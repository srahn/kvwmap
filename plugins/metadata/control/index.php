<?php
include_once(CLASSPATH . 'FormObject.php');
include_once(PLUGINS . 'metadata/model/kvwmap.php');
include_once(PLUGINS . 'metadata/model/metadaten.php');
include_once(PLUGINS . 'metadata/model/GeonetworkClient.php');
include_once(PLUGINS . 'metadata/model/MetaDataCreator.php');
include_once(PLUGINS . 'metadata/model/Ressource.php');
include_once(PLUGINS . 'metadata/model/SubRessource.php');
include_once(PLUGINS . 'metadata/model/SubRessourceRange.php');
include_once(PLUGINS . 'metadata/model/UpdateLog.php');

function go_switch_metadata($go){
	global $GUI;
	switch($go) {
		case 'Metadaten_Recherche' : {
			$GUI->metadaten_suche();
		} break;

		case 'Metadaten_Auswaehlen_Senden' : {
			$GUI->sanitize([
				'was' => 'text',
				'wer' => 'text',
				'wo' => 'text',
				'vonwann' => 'text',
				'biswann' => 'text',
				'eastbl' => 'float',
				'southbl' => 'float',
				'westbl' => 'float',
				'northbl' => 'float'
			]);
			$GUI->metadatenSuchen();
		} break;

		case 'Metadaten_update_outdated' : {
			$GUI->sanitize([
				'ressource_id' => 'integer'
			]);
			$GUI->checkCaseAllowed($go);
			$result = Ressource::update_outdated($GUI, $GUI->formvars['ressource_id']);
			// header('Content-Type: application/json; charset=utf-8');
			// echo json_encode($result);
			echo $result['msg'];
		} break;

		case 'Metadateneingabe' : {
			$GUI->sanitize(['oid' => 'int', 'mdfileid' => 'int']);
			$GUI->metadateneingabe();
		} break;

		default : {
			$GUI->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
}

?>