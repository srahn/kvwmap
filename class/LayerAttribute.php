<?php
class LayerAttribute extends MyObject {

	static $write_debug = false;

	function LayerAttribute($gui) {
		$this->MyObject($gui, 'layer_attributes');
	}

	public static	function find($gui, $where) {
		$layer_attribute = new LayerAttribute($gui);
		return $layer_attribute->find_where($where);
	}

	function copy($layer_id) {
		$this->debug->show('Copy LayerAttribute mit layer_id: ' . $layer_id, LayerAttribute::$write_debug);
		$new_attribute = clone $this;
		unset($new_attribute->data['layer_id']);
		$new_attribute->set('layer_id', $layer_id);
		$new_attribute->create();
	}
}
?>
