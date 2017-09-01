<?php
$this->goNotExecutedInPlugins = false;
include_once(PLUGINS . 'metadata/model/kvwmap.php'); # enthält Funktionen, die hier mit $this aufgerufen werden
include_once(PLUGINS . 'metadata/model/metadaten.php');
include_once(CLASSPATH.'FormObject.php');

/**
* Anwendungsfälle
*/
switch($this->go) {
	case 'Metadaten_Recherche' : {
		$this->metadaten_suche();
	} break;

	case 'Metadaten_Auswaehlen_Senden' : {
		$this->metadatenSuchen();
	} break;

	case 'Metadateneingabe' : {
		$this->metadateneingabe();
	} break;

	default : {
		$this->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
	}
}
?>