<?php
class Layer extends MyObject {

	static $write_debug = true;

	function Layer($gui) {
		$this->MyObject($gui, 'layer');
	}

	public static	function find($gui, $where) {
		$layer = new Layer($gui);
		return $layer->find_where($where);
	}

	/*
	* Diese Funktion legt vom aktuellen layer Objekt einen neuen Layer an
	* mit der übergebenen Layergruppe sowie alle seine zugehörigen Klassen und layer_attributes.
	* Vom Layer verwendete Styles und Labels werden wiederverwendet.
	* @return Layer Das kopierte Layerobjekt
	*/
	function copy($layer_group_id) {
		$this->debug->show('Copy Layer mit group_id: ' . $layer_group_id, Layer::write_debug);
		$new_layer = clone $this;
		unset($new_layer->data['Layer_ID']);
		$new_layer->set('Gruppe', $layer_group_id);
		$new_layer->create($this->data);

		$this->copy_classes();
		$this->copy_layer_attributes();
	}

	/*
	* Kopiere die Klassen des Layers
	*/
	function copy_classes() {
		foreach(LayerClass::find($this->gui, 'Layer_id = ' . $this->get('id')) AS $class) {
			$class->copy($this->get('id'));
		}
	}

	function copy_attributes() {
		foreach(LayerAttribute::find($this->gui, 'Layer_id = ' . $this->get('id')) AS $attribute) {
			$attribute->copy($this->get('id'));
		}
	}
}
?>
