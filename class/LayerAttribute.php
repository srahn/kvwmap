<?php
class LayerAttribute extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'layer_attributes');
	}

	public static	function find($gui, $where) {
		$layer_attribute = new LayerAttribute($gui);
		return $layer_attribute->find_where($where);
	}

	public static	function find_visible($gui, $stelle_id, $layer_id) {
		$myObj = new MyObject($gui, 'layer_attributes');
		return $myObj->find_by_sql(array(
			'select' => 'la.name, la.alias',
			'from' => '`layer_attributes` la JOIN `layer_attributes2stelle` AS ls ON la.layer_id = ls.layer_id AND la.name = ls.attributename',
			'where' => 'la.visible = 1 AND ls.stelle_id = ' . $stelle_id . ' AND la.layer_id = ' . $layer_id,
			'order' => 'la.`order`'
		));
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
