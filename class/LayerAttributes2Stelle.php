<?php
class LayerAttributes2Stelle extends PgObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'kvwmap', 'layer_attributes2stelle');
	}

	public static	function find($gui, $where) {
		$layer_attributes2stelle = new LayerAttributes2Stelle($gui);
		return $layer_attributes2stelle->find_where($where);
	}
} ?>