<?php
include_once(CLASSPATH . 'PgObject.php');
class Layer2Stelle extends PgObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'kvwmap', 'used_layer');
		$this->identifier = array('layer_id', 'stelle_id');
	}

	public static	function find($gui, $where) {
		$layer2stelle = new Layer2Stelle($gui);
		return $layer2stelle->find_where($where);
	}

	/**
	 * Function find layers that belongs to $stelle_id and are sync and editable
	 * @param GUI $gui - The current GUI-Objekt.
	 * @param int $stelle_id - The id of the stelle.
	 * @return Layer2Stelle[] $layer2stelle - The sync layers of the stelle.
	 */
	public static function find_sync_layers($gui, $stelle_id) {
		$layer2stelle = new Layer2Stelle($gui);
		$layer2stelle->debug->show('<p>Find layers with sync = true for stelle_id: ' . $stelle_id, PgObject::$write_debug);
		return $layer2stelle->find_by_sql(array(
			'select' => 'l.layer_id, l.schema, l.maintable, ul.privileg',
			'from' => 'kvwmap.used_layer ul JOIN kvwmap.layer l ON ul.layer_id = l.layer_id',
			'where' => 'ul.stelle_id = ' . $stelle_id . " AND l.editable = 1 and l.sync = '1'",
			'order' => 'l.layer_id'
		));
	}

	public static function find_base_layers($gui, $stelle_id) {
		$layer2stelle = new Layer2Stelle($gui);
		$layer2stelle->debug->show('<p>Find base layer with selectiontype = radio for stelle_id: ' . $stelle_id, PgObject::$write_debug);
		return $layer2stelle->find_by_sql(array(
			'select' => 'l.layer_id',
			'from' => 'kvwmap.used_layer ul JOIN kvwmap.layer l ON ul.layer_id = l.layer_id',
			'where' => 'ul.stelle_id = ' . $stelle_id . " AND l.selectiontype = 'radio'",
			'order' => 'ul.legendorder'
		));
	}

	public static function find_overlay_layers($gui, $stelle_id) {
		#echo '<br>Find overlay layers in stelle_id: ' . $stelle_id;
		$layer2stelle = new Layer2Stelle($gui);
		$layer2stelle->debug->show('<p>Find overlay layer with selectiontype != radio for stelle_id: ' . $stelle_id, PgObject::$write_debug);
		return $layer2stelle->find_by_sql(array(
			'select' => 'l.layer_id, ul.minscale, ul.maxscale, ul.transparency, ul.group_id',
			'from' => 'kvwmap.used_layer ul JOIN kvwmap.layer l ON ul.layer_id = l.layer_id JOIN kvwmap.u_groups g ON COALESCE(ul.group_id, l.gruppe) = g.id',
			'where' => 'ul.stelle_id = ' . $stelle_id . " AND l.selectiontype != 'radio'",
			'order' => 'g."order", ul.legendorder'
		));
	}
}
?>
