<?php
class Menue2Stelle extends PgObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'kvwmap', 'u_menue2stelle', 'stelle_id, menue_id');
	}

	public static	function find($gui, $where, $select = '*') {
		$menue2stelle = new Menue2Stelle($gui);
		return $menue2stelle->find_by_sql(
			array(
				'select' => 'DISTINCT stelle_id',
				'where' => $where
			)
		);
	}
}
?>
