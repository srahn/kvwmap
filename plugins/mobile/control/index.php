<?php
$this->goNotExecutedInPlugins = false;
include(PLUGINS . 'mobile/model/kvwmap.php');
/*include_once(CLASSPATH . 'PgObject.php');
include_once(CLASSPATH . 'MyObject.php');
include_once(CLASSPATH . 'Layer.php');
include_once(CLASSPATH . 'LayerClass.php');
include_once(CLASSPATH . 'LayerAttribute.php');
include_once(CLASSPATH . 'Style2Class.php');
include_once(CLASSPATH . 'Label2Class.php');
#include_once(CLASSPATH . 'LayerGroup.php');
include_once(CLASSPATH . 'data_import_export.php');

*/

/**
* Anwendungsfälle
* mobile_get_stellen
* mobile_get_layers
* mobile_sync
* mobile_delete_images
* mobile_upload_images
*/

#echo '<br>go: ' . $this->go;

switch($this->go) {
	case 'mobile_get_stellen' : {
		$result = $this->mobile_get_stellen();
		echo json_encode($result);
	} break;

	case 'mobile_get_layers' : {
		$result = $this->mobile_get_layers();
		echo json_encode($result);
	} break;

	case 'mobile_sync' : {
		$result = $this->mobile_sync();
		echo json_encode($result);
	} break;

	case 'mobile_delete_images' : {
		#var_dump($this->formvars['images']);
		$this->checkCaseAllowed($this->go);
		$result = $this->mobile_delete_images($this->formvars['selected_layer_id'], $this->formvars['images']);
		echo json_encode($result);
	} break;

	case 'mobile_upload_image' : {
		$this->checkCaseAllowed($this->go);
		$result = $this->mobile_upload_image($this->formvars['selected_layer_id'], $_FILES);
		echo json_encode($result);
	} break;

	case 'mobile_download_image' : {
		$this->checkCaseAllowed($this->go);
		$file = $this->formvars['image'];
		header('Content-Type: image/jpeg');
		header('Content-Length: ' . filesize($file));
		readfile($file);
	} break;

	default : {
		$this->goNotExecutedInPlugins = true; // in diesem Plugin wurde go nicht ausgeführt
	}
}
/*
function isInStelleAllowed($stelle, $requestStelleId) {
	if ($stelle->id == $requestStelleId)
		return true;
	else {
		echo '<br>(Diese Aktion kann nur von der Stelle ' . $stelle->Bezeichnung . ' aus aufgerufen werden.)';
		return false;
	}
}
*/
?>