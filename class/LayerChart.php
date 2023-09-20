<?php
class LayerChart extends MyObject {

	static $write_debug = false;
	static $identifier = 'id';

	function __construct($gui) {
		parent::__construct($gui, 'layer_charts');
	}

	public static	function find_by_id($gui, $id) {
		$chart = new LayerChart($gui);
		return $chart->find_by(LayerChart::$identifier, $id);
	}

	public static	function find($gui, $where) {
		$layer_chart = new LayerChart($gui);
		return $layer_chart->find_where($where);
	}

	function create($data = array()) {
		$result = array(
			'success' => true,
			'msg' => 'LayerChart angelegt',
			'id' => 3
		);
		return $result;
	}

	function update($data = array(), $update_all_attributes = true) {
		$results = parent::update($data);
		if ($results[0]['success']) {
			$response = array(
				'success' => true,
				'msg' => 'Diagramm erfolgreich gespeichert.'
			);
		}
		else {
			$response = $results[0];
		}
		return $response;
	}

	function delete() {
		$result = array(
			'success' => true,
			'msg' => 'LayerChart gelöscht',
			'id' => 3
		);
		return $result;
	}
}
?>
