<?php
class Funktion2Stelle extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'u_funktion2stelle', 'funktion_id, stelle_id');
	}

	public static	function find($gui, $where) {
		$funktion2stelle = new Funktion2Stelle($gui);
		return $funktion2stelle->find_where($where);
	}
}
?>
