<?php
$this->goNotExecutedInPlugins = false;
// include(PLUGINS . 'xplankonverter/model/kvwmap.php');
include_once(CLASSPATH . 'PgObject.php');
// include_once(CLASSPATH . 'MyObject.php');
include_once(CLASSPATH . 'Layer.php');
// include_once(CLASSPATH . 'LayerClass.php');
// include_once(CLASSPATH . 'LayerAttribute.php');
// include_once(CLASSPATH . 'Style2Class.php');
// include_once(CLASSPATH . 'Label2Class.php');
// #include_once(CLASSPATH . 'LayerGroup.php');
// include_once(CLASSPATH . 'data_import_export.php');
include(PLUGINS . 'wasserrecht/model/WrPgObject.php');
include(PLUGINS . 'wasserrecht/model/anlage.php');
include(PLUGINS . 'wasserrecht/model/personen.php');
include(PLUGINS . 'wasserrecht/model/behoerde.php');
include(PLUGINS . 'wasserrecht/model/gewaesserbenutzungen.php');
include(PLUGINS . 'wasserrecht/model/gewaesserbenutzungen_umfang.php');
include(PLUGINS . 'wasserrecht/model/wasserrechtliche_zulassungen.php');
include(PLUGINS . 'wasserrecht/model/wasserrechtliche_zulassungen_gueltigkeit.php');
include(PLUGINS . 'wasserrecht/model/WRZProGueltigkeitsJahr.php');
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

$this->actual_link = parse_url((isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", PHP_URL_PATH);

//$anlage = new Anlage($this);
//$anlagen = $anlage->find_where('true');

//$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
//$layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
//$this->debug->write(var_dump($layerdb), 4);

/*        $this->loadMap('DataBase');
 $layer_names = array();
 foreach($this->layerset['layer_ids'] AS $id => $layer) {
 $layer_names[$layer['Name']] = $id;
 }
 $this->layer_names = $layer_names;
 */

// 	    $layer_name = 'Wasserrechtliche_Zulassungen';
// 	    $this->layers = Layer::find($this, "Name = '" . $layer_name . "'");
$this->layers = Layer::find($this, "true");
// 	    var_dump(count($this->layers));
$layer_names = array();
for ($i = 0; $i <= count($this->layers); $i++) {
    if(isset($this->layers[$i]))
    {
        //echo $this->layers[$i]->get('Name');
        $layer_name = $this->layers[$i]->get('Name');
        $layer_id = $this->layers[$i]->get('Layer_ID');
        $layer_names[$layer_name] = $layer_id;
    }
}
$this->layer_names = $layer_names;
// 	    $this->layers = $layers;
// 	    echo $this->layers[0]->get('Name');

switch($this->go){

	case 'wasserentnahmebenutzer': {
	    $this->debug->write('wasserentnahmebenutzer called!', 4);
	    
	    $this->main = PLUGINS . 'wasserrecht/view/wasserentnahmebenutzer.php';
	    $this->output();
	}	break;
	default : {
		$this->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
	}
}
?>