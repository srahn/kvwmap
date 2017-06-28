<?php
$this->goNotExecutedInPlugins = false;
/*include(PLUGINS . 'mobile/model/kvwmap.php');
include_once(CLASSPATH . 'PgObject.php');
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

	/*
	* Return layer, attributs and data in json
	*/
	/*
		{
			"layers": [{
				"id": 100,
				"title": "Haltestellen",
				"id_attribute": "id",
				"geometry_attribute" : "position",
				"geometry_type": "Point",
				"get_data_title": function(row) {
					return row.bezeichnung + '(' + row.id ')';
				},
				"classes": [{
					"name": "alle",
					"expression": "",
					"icon": "haltestelle.png"
				}],
				"attributes": [{
					"name": "id",
					"type": "integer",
					"privilege": 1
				}, {
					"name": "bezeichnung",
					"type": "character varying",
					"form_type": "text",
					"max_length": 255,
					"privilege": 2
				}, {
					"name": "linien",
					"type": "integer[]",
					"privilege": 1
				}, {
					"name": "gebaeude_type",
					"type": "character varying",
					"form_type": "select",
					"options": [{
						"value": "1",
						"output": "Glashaus"
					}, {
						"value": "2",
						"output": "keine"
					}],
					"max_length": 255,
					"privilege": 2
				}, {
					"name": "behindertengerecht",
					"type": "boolean",
					"form_type": "checkbox"
				}, {
					"name": "position",
					"type": "geometry",
					"form_type": "gps_select"
				}],
				"data" : [{
					"id": 1,
					"bezeichnung": "Lessingallee",
					"linien": [1, 2, 4],
					"gebaeude_type": "Glashaus",
					"position": {
						"lat": 54.12334,
						"lng": 12.45332
					}
				}, {
					"id": 2,
					"bezeichnung": "Hansastraße",
					"linien": [1, 2, 4],
					"gebaeude_type": "keine"
					"position": {
						"lat": 54.12334,
						"lng": 12.45332
					}
				}]
			}]
		}
	*/
	case 'mobile_get_layer' : {
		echo json_encode($this->mobile_get_layer());	
	} break;

	case 'mobile_update_data' : {
		
		$success = $this->mobile_update_data();
		if ($success) {
			$result = '{ "success": false,
			"layer": ' . $this->mobile_get_layer() . '}';
		}
		else {
			$result = '{ "success": false }';
		}
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
function mobile_update_data() {
	# decode formvars['data'];
	$data = json_decode($this->formvars['data']);
	# for each of data do update or insert or delete
	$success = true;
	return $succes;
}

function mobile_get_layer() {
	# Abfragen der Layerdefinition
	$layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
	$layer = $layerset[0];

	# Abfragen der Privilegien der Attribute
	$privileges = $this->Stelle->get_attributes_privileges($this->formvars['selected_layer_id']);

	# Abfragen der Attribute des Layers mit selected_layer_id
	$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
	$layerdb = $mapDB->getlayerdatabase(
		$this->formvars['selected_layer_id'],
		$this->Stelle->pgdbhost
	);
	$layerdb->setClientEncoding();
	$attributes = $mapDB->read_layer_attributes(
		$this->formvars['selected_layer_id'],
		$layerdb,
		$privileges['attributenames'],
		false,
		true
	);

	# Zuordnen der Privilegien zu den Attributen
	for ($j = 0; $j < count($attributes['name']); $j++) {
		$attributes['privileg'][$j] = $attributes['privileg'][$attributes['name'][$j]] = ($privileges == NULL ? 0 : $privileges[$attributes['name'][$j]]);
	}

	return array(
		"layer" => $layer,
		"attributes" => $attributes
	);
}
?>