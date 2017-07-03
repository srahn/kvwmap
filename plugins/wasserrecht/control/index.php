<?php
$this->goNotExecutedInPlugins = false;
// include(PLUGINS . 'xplankonverter/model/kvwmap.php');
include_once(CLASSPATH . 'PgObject.php');
// include_once(CLASSPATH . 'MyObject.php');
// include_once(CLASSPATH . 'Layer.php');
// include_once(CLASSPATH . 'LayerClass.php');
// include_once(CLASSPATH . 'LayerAttribute.php');
// include_once(CLASSPATH . 'Style2Class.php');
// include_once(CLASSPATH . 'Label2Class.php');
// #include_once(CLASSPATH . 'LayerGroup.php');
// include_once(CLASSPATH . 'data_import_export.php');
include(PLUGINS . 'wasserrecht/model/anlage.php');
// include(PLUGINS . 'xplankonverter/model/RP_Plan.php');
// include(PLUGINS . 'xplankonverter/model/RP_Bereich.php');
// include(PLUGINS . 'xplankonverter/model/RP_Object.php');
// include(PLUGINS . 'xplankonverter/model/konvertierung.php');
// include(PLUGINS . 'xplankonverter/model/regel.php');
// include(PLUGINS . 'xplankonverter/model/shapefiles.php');
// include(PLUGINS . 'xplankonverter/model/validierung.php');
// include(PLUGINS . 'xplankonverter/model/validierungsergebnis.php');
// include(PLUGINS . 'xplankonverter/model/xplan.php');
// include(PLUGINS . 'xplankonverter/model/converter.php');

/**
* Anwendungsfälle
* show_elements
* show_simple_types
* show_uml
* xplankonverter_konvertierungen_index
* xplankonverter_shapefiles_index
* xplankonverter_shapefiles_delete
* xplankonverter_konvertierung_status
* xplankonverter_konvertierung
* xplankonverter_validierungsergebnisse
* xplankonverter_gml_generieren
* xplankonverter_konvertierung_loeschen
* xplankonverter_inspire_gml_generieren
* xplankonverter_regeleditor
* xplankonverter_regeleditor_getxplanattributes
* xplankonverter_regeleditor_getshapeattributes
* xplankonverter_download_uploaded_shapes
* xplankonverter_download_edited_shapes
* xplankonverter_download_xplan_shapes
* xplankonverter_download_xplan_gml
* xplankonverter_download_inspire_gml
*/

switch($this->go){

	case 'wasserrecht_test': {
		$this->debug->write('wasserrecht_test called', 4);
		$anlage = new Anlage($this);
		$anlagen = $anlage->find_where('true');
		$this->main = PLUGINS . 'wasserrecht/view/test.php';
		$this->output();
	}	break;
	default : {
		$this->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
	}
}
?>