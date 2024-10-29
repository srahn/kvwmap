<?php
// Use Cases
// metadata_cancel_data_package
// metadata_create_bundle_package
// metadata_create_data_package
// metadata_create_data_packages
// metadata_delete_bundle_package
// metadata_delete_data_package
// metadata_download_bundle_package
// metadata_download_data_package
// metadata_order_bundle_package
// metadata_order_data_package
// metadata_reorder_data_package
// metadata_show_data_packages
// Metadaten_Auswaehlen_Senden
// Metadaten_Recherche
// Metadateneingabe
// Metadaten_update_outdated

include_once(CLASSPATH . 'FormObject.php');
include_once(PLUGINS . 'metadata/model/kvwmap.php');
include_once(PLUGINS . 'metadata/model/metadaten.php');
include_once(PLUGINS . 'metadata/model/GeonetworkClient.php');
include_once(PLUGINS . 'metadata/model/MetaDataCreator.php');
include_once(PLUGINS . 'metadata/model/DataPackage.php');
include_once(PLUGINS . 'metadata/model/Ressource.php');
include_once(PLUGINS . 'metadata/model/SubRessource.php');
include_once(PLUGINS . 'metadata/model/SubRessourceRange.php');
include_once(PLUGINS . 'metadata/model/UpdateLog.php');
include_once(PLUGINS . 'metadata/model/PackLog.php');

function go_switch_metadata($go){
	global $GUI;
	switch($go) {
		case 'metadata_cancel_data_package': {
			$GUI->sanitize([
				'package_id' => 'integer'
			]);
			$response = $GUI->metadata_cancel_data_package($GUI->formvars['package_id']);
			echo json_encode($response);
		} break;

		case 'metadata_create_bundle_package': {
			$response = $GUI->metadata_create_bundle_package($GUI->Stelle->id);
			echo json_encode($response);
		} break;

		case 'metadata_create_data_package': {
			$GUI->sanitize([
				'package_id' => 'integer'
			]);

			$response = $GUI->metadata_create_data_package($GUI->formvars['package_id']);
			echo json_encode($response);
		} break;

		case 'metadata_delete_bundle_package' : {
			$response = $GUI->metadata_delete_bundle_package($GUI->Stelle->id);
			echo json_encode($response);
		} break;

		case 'metadata_delete_data_package': {
			$GUI->sanitize([
				'package_id' => 'integer'
			]);
			$response = $GUI->metadata_delete_data_package($GUI->formvars['package_id']);
			echo json_encode($response);
		} break;

		case 'metadata_download_bundle_package': {
			$result = $GUI->metadata_download_bundle_package();

			if (!$result['success']) {
				echo 'Fehler: ' . $result['msg'];
			}

			$downloadfile = $result['downloadfile'];

			header('Content-Description: File Transfer');
			header('Content-Type: application/x-download');
			header('Content-Disposition: attachment; filename="'.basename($downloadfile).'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($downloadfile));
			readfile($downloadfile);
		} break;

		case 'metadata_download_data_package': {
			$GUI->sanitize([
				'package_id' => 'integer'
			]);
			$result = $GUI->metadata_download_data_package($GUI->formvars['package_id']);

			if (!$result['success']) {
				echo 'Fehler: ' . $result['msg'];
			}

			$downloadfile = $result['downloadfile'];

			header('Content-Description: File Transfer');
			header('Content-Type: application/x-download');
			header('Content-Disposition: attachment; filename="'.basename($downloadfile).'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($downloadfile));
			readfile($downloadfile);
		} break;

		case 'metadata_order_bundle_package': {
			$response = $GUI->metadata_order_bundle_package();
			echo json_encode($response);
		} break;

		case 'metadata_order_data_package': {
			$GUI->sanitize([
				'ressource_id' => 'integer'
			]);
			$response = $GUI->metadata_order_data_package($GUI->formvars['ressource_id'], $GUI->Stelle->id);
			echo json_encode($response);
		} break;

		case 'metadata_reorder_data_packages' : {
			$GUI->sanitize([
				'ressource_id' => 'integer'
			]);
			$response = $GUI->metadata_reorder_data_packages($GUI->formvars['ressource_id']);
			echo json_encode($response);
		} break;

		case 'metadata_show_data_packages': {
			$GUI->metadata_show_data_packages();
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

		case 'Metadaten_Recherche' : {
			$GUI->metadaten_suche();
		} break;

		case 'Metadaten_update_outdated' : {
			$GUI->sanitize([
				'ressource_id' => 'integer'
			]);
			$GUI->checkCaseAllowed($go);
			$result = Ressource::update_outdated($GUI, $GUI->formvars['ressource_id'], $GUI->formvars['method_only']);
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