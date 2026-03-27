<?php
// Use Cases
// metadata_add_urls
// metadata_cancel_data_package
// metadata_create_bundle_package
// metadata_create_data_package
// metadata_create_metadata_document
// metadata_list_files
// metadata_delete_bundle_package
// metadata_delete_data_package
// metadata_download_bundle_package
// metadata_download_data_package
// metadata_download_metadata_document
// metadata_order_bundle_package
// metadata_order_data_package
// metadata_reorder_data_packages
// metadata_set_ressource_status
// metadata_show_data_packages
// metadata_show_ressources_status
// metadata_show_outdated
// metadata_update_outdated
// Metadaten_Auswaehlen_Senden
// Metadaten_Recherche
// Metadateneingabe

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
		case 'metadata_add_urls': {
			$GUI->sanitize([
				'ressource_id' => 'int'
			]);
			$GUI->checkCaseAllowed('metadata_add_urls');
			$result = $GUI->metadata_add_urls($GUI->formvars['ressource_id']);
			if (!$result['success']) {
				$GUI->Fehlermeldung = $result['msg'];
			}
			$GUI->output();
		} break;

		case 'metadata_cancel_data_package': {
			$GUI->sanitize([
				'package_id' => 'int'
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
				'package_id' => 'int'
			]);

			$response = $GUI->metadata_create_data_package($GUI->formvars['package_id']);
			echo json_encode($response);
		} break;

		case 'metadata_create_metadata_document' : {
			$GUI->sanitize([
				'layer_id' => 'int'
			]);
			$response = $GUI->metadata_create_metadata_document($GUI->formvars['layer_id']);
			echo json_encode($response);
		} break;

		case 'metadata_list_files' : {
			$GUI->checkCaseAllowed('metadata_list_files');
			$result = $GUI->metadata_list_files($GUI->formvars['dir']);
			if (!$result['success']) {
				$GUI->Fehlermeldung = $result['msg'];
			}
			$GUI->output();
		} break;

		case 'metadata_delete_bundle_package' : {
			$response = $GUI->metadata_delete_bundle_package($GUI->Stelle->id);
			echo json_encode($response);
		} break;

		case 'metadata_delete_data_package': {
			$GUI->sanitize([
				'package_id' => 'int'
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
				'package_id' => 'int'
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

		case 'metadata_download_metadata_document' : {
			$GUI->sanitize([
				'layer_id' => 'int'
			]);
			$response = $GUI->metadata_download_metadata_document($GUI->formvars['layer_id']);
			if (!$result['success']) {
				$GUI->Fehlermeldung = $response['msg'];
				$GUI->main = '../../plugins/metadata/view/download_error.php';
				$GUI->output();
			}
			header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
			header("Cache-Control: public"); // needed for internet explorer
			header("Content-Type: application/pdf");
			header("Content-Transfer-Encoding: Binary");
			header("Content-Length:" . filesize($response['downloadfile']));
			header('Content-Disposition: attachment; filename=' . $response['filename'] . '.pdf');
			readfile($response['downloadfile']);
		} break;

		case 'metadata_order_bundle_package': {
			$response = $GUI->metadata_order_bundle_package();
			echo json_encode($response);
		} break;

		case 'metadata_order_data_package': {
			$GUI->sanitize([
				'ressource_id' => 'int'
			]);
			$response = $GUI->metadata_order_data_package($GUI->formvars['ressource_id'], $GUI->Stelle->id);
			echo json_encode($response);
		} break;

		case 'metadata_reorder_data_packages' : {
			$GUI->sanitize([
				'ressource_id' => 'int'
			]);
			$response = $GUI->metadata_reorder_data_packages($GUI->formvars['ressource_id']);
			echo json_encode($response);
		} break;

		case 'metadata_set_ressource_status' : {
			$GUI->sanitize([
				'ressource_id' => 'int',
				'status_id' => 'int'
			]);
			$response = $GUI->metadata_set_ressource_status($GUI->formvars['ressource_id'], $GUI->formvars['status_id']);
			echo json_encode($response);
		} break;

		case 'metadata_show_data_packages': {
			$GUI->metadata_show_data_packages();
		} break;

		case 'metadata_show_ressources_status' : {
			$GUI->sanitize([
				'ressource_id' => 'int'
			]);
			$GUI->metadata_show_ressources_status($GUI->formvars['ressource_id']);
		} break;

		case 'metadata_show_outdated' : {
			$GUI->metadata_show_outdated();
		} break;

		case 'metadata_update_outdated' : {
			$GUI->sanitize([
				'ressource_id' => 'int',
			]);
			$GUI->checkCaseAllowed($go);
			$result = Ressource::update_outdated(
				$GUI, $GUI->formvars['ressource_id'],
				$GUI->formvars['method_only'],
				(array_key_exists('only_missing', $GUI->formvars) AND $GUI->formvars['only_missing'] != '') ? true : false,
				(array_key_exists('force', $GUI->formvars) AND $GUI->formvars['force'] != '')
			);
			// header('Content-Type: application/json; charset=utf-8');
			// echo json_encode($result);
			echo $result['msg'];
		} break;

		case 'metadata_test' : {
			// $ressource = Ressource::find_by_id($GUI, 'id', 166);
			// include_once(PLUGINS . 'metadata/model/Lineage.php');
			// $targets = Lineage::find_targets($GUI, 166);
			// echo '<br>targets: ' . count($targets);
			// $handle = fopen('/var/www/data/fdm/dom/dom_atom.xml', "r");
			// if ($handle) {
			// 	$atom_url = 'https://www.geodaten-mv.de/dienste/dom_download?index=4&amp;dataset=us214578-a1n5-4v12-v31c-5tg2az3a2164&amp;file=dom1_33_$x_$y_2_gtiff.tif';
			// 	$regex = '/' . str_replace('$x', '(.*?)', str_replace('$y', '(.*?)', str_replace('?', '\?', str_replace('/', '\/', $atom_url)))) . '/';
			// 	while (($line = fgets($handle)) !== false) {
			// 		if (preg_match($regex, $line, $match) == 1) {
			// 			echo '<br>' . $match[0];
			// 		}
			// 	}
			// 	fclose($handle);
			// }
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

		case 'Metadateneingabe' : {
			$GUI->sanitize(['oid' => 'int', 'mdfileid' => 'int']);
			$GUI->metadateneingabe();
		} break;


		default : {
			$GUI->goNotExecutedInPlugins = true; // in diesem Plugin wurde go nicht ausgeführt
		}
	}
}

?>