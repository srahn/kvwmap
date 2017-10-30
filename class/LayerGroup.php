<?php
include_once(CLASSPATH . 'MyObject.php');
include_once(CLASSPATH . 'Layer.php');
class LayerGroup extends MyObject {

	static $write_debug = false;
	static $identifier = 'id';

	function LayerGroup($gui) {
		$this->MyObject($gui, 'u_groups');
		$this->validations = array(
			array(
				'attribute' => 'Gruppenname',
				'condition' => 'not_null',
				'description' => 'Es muss ein Gruppenname angegeben werden.',
				'options' => null
			)
		);
	}

	public static	function find_by_id($gui, $id) {
		$group = new LayerGroup($gui);
		return $group->find_by(LayerGroup::$identifier, $id);
	}

	public static	function find($gui, $where, $order) {
		$group = new LayerGroup($gui);
		return array_map(
			function ($group) {
				$group->layers = $group->get_Layer();
				return $group;
			},
			$group->find_where($where, $order)
		);
	}

	function get_Layer() {
		$layer = new Layer($this->gui);
		$layers = $layer->find_where(
			'Gruppe = ' . $this->get('id'),
			'drawingorder'
		);
		return $layers;
	}

}
?>
