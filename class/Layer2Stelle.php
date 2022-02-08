<?php
include_once(CLASSPATH . 'MyObject.php');
class Layer2Stelle extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'used_layer');
		$this->identifier = 'Layer_ID, Stelle_ID';
	}

	public static	function find($gui, $where) {
		$layer2stelle = new Layer2Stelle($gui);
		return $layer2stelle->find_where($where);
	}

	public static function find_base_layers($gui, $stelle_id) {
		$layer2stelle = new Layer2Stelle($gui);
		$layer2stelle->debug->show('<p>Find base layer with selectiontype = radio for stelle_id: ' . $stelle_id, MyObject::$write_debug);
		return $layer2stelle->find_by_sql(array(
			'select' => 'l.Layer_ID',
			'from' => 'used_layer ul JOIN layer l ON ul.Layer_ID = l.Layer_ID',
			'where' => 'ul.Stelle_ID = ' . $stelle_id . " AND l.selectiontype = 'radio'",
			'order' => 'ul.drawingorder'
		));
	}

	public static function find_overlay_layers($gui, $stelle_id) {
		$layer2stelle = new Layer2Stelle($gui);
		$layer2stelle->debug->show('<p>Find overlay layer with selectiontype != radio for stelle_id: ' . $stelle_id, MyObject::$write_debug);
		return $layer2stelle->find_by_sql(array(
			'select' => 'l.Layer_ID, ul.minscale, ul.maxscale',
			'from' => 'used_layer ul JOIN layer l ON ul.Layer_ID = l.Layer_ID JOIN u_groups g ON l.Gruppe = g.id',
			'where' => 'ul.Stelle_ID = ' . $stelle_id . " AND l.selectiontype != 'radio'",
			'order' => 'g.`order`, ul.`legendorder`'
		));
	}
}
?>
