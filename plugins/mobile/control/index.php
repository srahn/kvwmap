<?php

include(PLUGINS . 'mobile/model/kvwmap.php');
function go_switch_mobile($go){
	global $GUI;
	switch($GUI->go) {
		case 'mobile_get_stellen' : {
			$result = $GUI->mobile_get_stellen();
			echo json_encode($result);
		} break;

		case 'mobile_get_layers' : {
			$result = $GUI->mobile_get_layers();
			echo json_encode($result);
		} break;

		case 'mobile_sync' : {
			$result = $GUI->mobile_sync();
			echo json_encode($result);
		} break;

		case 'mobile_delete_images' : {
			#var_dump($GUI->formvars['images']);
			$GUI->checkCaseAllowed($GUI->go);
			$result = $GUI->mobile_delete_images($GUI->formvars['selected_layer_id'], $GUI->formvars['images']);
			echo json_encode($result);
		} break;

		case 'mobile_upload_image' : {
			# Prüfen was hier kommt wenn go nicht erlaubt ist und ob checkCaseAllowed false liefert.
			if (($GUI->Stelle->isMenueAllowed($go) OR $GUI->Stelle->isFunctionAllowed($go))) {
				$result = $GUI->mobile_upload_image($GUI->formvars['selected_layer_id'], $_FILES);
			}
			else {
				$result = array(
					'success' => false,
					'msg' => 'Anwendungsfall mobile_upload_image auf diesem Server für diese Stelle nicht erlaubt. Lassen Sie die Funktion oder einen entsprechenden Menüpunkt vom Administrator der Anwendung freigeben!'
				);
			}
			echo json_encode($result);
		} break;

		case 'mobile_download_image' : {
			$GUI->checkCaseAllowed($GUI->go);
			$file = $GUI->formvars['image'];
			header('Content-Type: image/jpeg');
			header('Content-Length: ' . filesize($file));
			readfile($file);
		} break;

		default : {
			$GUI->goNotExecutedInPlugins = true; // in diesem Plugin wurde go nicht ausgeführt
		}
	}
}

?>