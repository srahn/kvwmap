<?php
class Layer extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'layer');
		$this->identifier = 'Layer_ID';
	}

	public static	function find($gui, $where) {
		$layer = new Layer($gui);
		return $layer->find_where($where);
	}

	public static	function find_by_name($gui, $name) {
		$layer = new Layer($gui);
		$layers = $layer->find_where("Name LIKE '" . $name . "'");
		return $layers[0];
	}

	public static function find_by_obergruppe_und_name($gui, $obergruppe_id, $layer_name) {
		$layer = new Layer($gui);
		$result = $layer->find_by_sql(
			array(
				'select' => 'l.*',
				'from' => "
					u_groups g JOIN
					layer l ON (g.id = l.Gruppe)",
				'where' => "
					g.obergruppe = " . $obergruppe_id . " AND
					l.Name = '" . $layer_name . "'"
			)
		);
		return $result[0];
	}

	/**
	* This function return the layer id's of the duplicates of a layer
	* @param mysql_connection object
	* @param integer $duplicate_from_layer_id The layer id from witch the others are duplicates
	* @param array(integer) The layer_ids of the duplicates
	*/
	public static function find_by_duplicate_from_layer_id($database, $duplicate_from_layer_id) {
		$duplicate_layer_ids = array();
		$sql =  "
			SELECT
				`Layer_ID`
			FROM
				`layer`
			WHERE
				`duplicate_from_layer_id` = " . $duplicate_from_layer_id . "
				AND `Layer_ID` != `duplicate_from_layer_id`
		";
		# letzte Where Bedinung, damit keine Entlosschleifen entstehen beim Aufruf von update_layer falls
		# Layer_ID fälschlicherweise identisch sein sollte mit duplicate_layer_id was nicht passieren sollte
		# wenn das Layerformular genutzt wurde.
		#echo  MyObject::$write_debug ? 'Layer find_by_duplicate_from_layer_id sql:<br> ' . $sql : '';
		$ret = $database->execSQL($sql, 4, 1, true);
		if (!$ret['success']) {
			$database->gui->add_message('error', $ret[1]);
		}
		else {
			while ($rs = $database->result->fetch_assoc()) {
				$duplicate_layer_ids[] = $rs['Layer_ID'];
			}
		}
		return $duplicate_layer_ids;
	}

	/*
	* Diese Funktion legt vom aktuellen layer Objekt einen neuen Layer an
	* mit der übergebenen Layergruppe sowie alle seine zugehörigen Klassen und layer_attributes.
	* Vom Layer verwendete Styles und Labels werden wiederverwendet.
	* @return Layer Das kopierte Layerobjekt
	*/
	function copy($attributes) {
		$success = true;
		$this->debug->show('<p>Clone Templatelayer: ' . $this->get($this->identifier), Layer::$write_debug);
		$new_layer = clone $this;
		unset($new_layer->data['Layer_ID']);
		foreach ($attributes AS $key => $value) {
			$new_layer->set($key, $value);
		}
		$new_layer->create();
		$new_layer_id = $new_layer->get($new_layer->identifier);

		if (!empty($new_layer_id)) {
			$this->debug->show('<p>Copiere die Klassen des Template layers für neuen Layer id: ' . $new_layer_id, Layer::$write_debug);
			$this->copy_classes($new_layer_id);
			$this->debug->show('<p>Copiere die layer_attributes des Template layers für neuen Layer id: ' . $new_layer_id, Layer::$write_debug);
			$this->copy_layer_attributes($new_layer_id);
		}
		return $new_layer;
	}

	/*
	* Kopiere die Klassen des Layers mit anderer Layer_id
	*/
	function copy_classes($new_layer_id) {
		foreach(LayerClass::find($this->gui, 'Layer_id = ' . $this->get('Layer_ID')) AS $layer_class) {
			$this->debug->show('Copy class: ' . $layer_class->get('Name') . ' mit layer id: ' . $this->get('Layer_ID') . ' => ' . $new_layer_id, Layer::$write_debug);
			$layer_class->copy($new_layer_id);
		}
	}

	function copy_layer_attributes($new_layer_id) {
		foreach(LayerAttribute::find($this->gui, 'Layer_id = ' . $this->get('Layer_ID')) AS $attribute) {
			$this->debug->show('Copy Attribute: ' . $attribute->get('name') . ' mit neuer layer id: ' . $this->get('Layer_ID') . ' => ' . $new_layer_id, Layer::$write_debug);
			$attribute->copy($new_layer_id);
		}
	}

	

	function get_subform_layers() {
		include_once(CLASSPATH . 'LayerAttribute.php');
		$subform_layer_ids = array_unique(
			array_map(
				function($attribute) {
					return explode(',', $attribute->get('options'))[0];
				},
				LayerAttribute::find($this->gui, "Layer_ID = " . $this->get('Layer_ID') . " AND form_element_type LIKE 'SubForm%PK'")
			)
		);
		if (count($subform_layer_ids) > 0) {
			return Layer::find(
				$this->gui,
				"Layer_ID IN (" . implode(', ', $subform_layer_ids) . ')'
			);
		}
		else {
			return array();
		}
	}

	function get_parentform_layers() {
		include_once(CLASSPATH . 'LayerAttribute.php');
		$parentform_layer_ids = array_unique(
			array_map(
				function($attribute) {
					return $attribute->get('layer_id');
				},
				LayerAttribute::find($this->gui, "Layer_ID != " . $this->get('Layer_ID') . " AND options LIKE '" . $this->get('Layer_ID') . ",%' AND form_element_type LIKE 'SubForm%PK'")
			)
		);
		if (count($parentform_layer_ids) > 0) {
			return Layer::find(
				$this->gui,
				"Layer_ID IN (" . implode(', ', $parentform_layer_ids) . ')'
			);
		}
		else {
			return array();
		}
	}

	function get_edit_link_list($layers, $anchor = '') {
		return '<ul><li>' . implode(
			'</li><li>',
			array_map(
				function($layer) use ($anchor) {
					return '<a title="Layereditor anzeigen" href="index.php?go=Layereditor&selected_layer_id=' . $layer->get('Layer_ID') . '#' . $anchor . '" target="_blank">' . $layer->get('Name') . ' (ID: ' . $layer->get('Layer_ID') . ')</a>';
				},
				$layers
			)
		) . '</li></ul>';
	}
}
?>
