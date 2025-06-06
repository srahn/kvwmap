<?php
class LayerChart extends PgObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'kvwmap', 'layer_charts', 'id');
		$this->validations = array(
			array(
				'attribute' => 'breite',
				'condition' => 'not_null',
				'description' => 'Es muss eine Breite angegeben sein.',
				'options' => null
			)
		);
	}

	public static	function find_by_id($gui, $id) {
		$chart = new LayerChart($gui);
		return $chart->find_by($chart->identifier, $id);
	}

	public static	function find($gui, $where) {
		$layer_chart = new LayerChart($gui);
		return $layer_chart->find_where($where);
	}

}
?>
