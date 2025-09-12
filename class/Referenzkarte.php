<?php
include_once(CLASSPATH . 'MyObject.php');
class Referenzkarte extends MyObject {

	static $write_debug = true;

	function __construct($gui) {
		parent::__construct($gui, 'referenzkarten', 'ID');
		$this->validations = array();
	}

	public static	function find_by_id($gui, $id) {
		$referenzkarte = new Referenzkarte($gui);
		return $referenzkarte->find_by($referenzkarte->identifier, $id);
	}

	public static	function find($gui, $where, $order = '') {
		$referenzkarte = new Referenzkarte($gui);
		return $referenzkarte->find_where($where, $order);
	}
}
?>
