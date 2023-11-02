<?php
class Menue2Stelle extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'u_menue2stelle', 'stelle_id, menue_id');
	}

	public static	function find($gui, $where) {
		$menue2stelle = new Menue2Stelle($gui);
		return $menue2stelle->find_where($where);
	}
}
?>
