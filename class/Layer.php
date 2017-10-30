<?php
class Layer extends MyObject {

	static $write_debug = false;

	function Layer($gui) {
		$this->MyObject($gui, 'layer');
		$this->identifier = 'Layer_ID';
	}

	public static	function find($gui, $where) {
		$layer = new Layer($gui);
		return $layer->find_where($where);
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
}
?>
