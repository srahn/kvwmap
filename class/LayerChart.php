<?php
class LayerChart extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'layer_charts', 'id');
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
