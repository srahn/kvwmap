<?php
class User2Stelle extends PgObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'kvwmap', 'rolle');
		$this->identifiers = array(
			array(
				'column' => 'user_id',
				'type' => 'integer'
			),
			array(
				'column' => 'stelle_id',
				'type' => 'integer'
			)
		);
	}

	public static	function find($gui, $where) {
		$rolle = new User2Stelle($gui);
		return $rolle->find_where($where);
	}
}
?>