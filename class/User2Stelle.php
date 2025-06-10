<?php
class User2Stelle extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'rolle');
		$this->identifiers = array(
			array(
				'key' => 'user_id',
				'type' => 'integer'
			),
			array(
				'key' => 'stelle_id',
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