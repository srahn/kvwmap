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
* mobile_get_layer
*/

switch($this->go) {

	case 'mobile_get_layers' : {
		$result = $this->mobile_get_layers();
		echo json_encode($result);
	} break;

	case 'mobile_sync' : {
		$result = $this->mobile_sync();
		echo json_encode($result);
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