<?php
include_once(CLASSPATH . 'MyObject.php');
class Referenzkarte extends MyObject {

	static $write_debug = true;
	static $identifier = 'ID';

	function __construct($gui) {
		parent::__construct($gui, 'referenzkarten');
		$this->validations = array();
	}

	public static	function find_by_id($gui, $id) {
		$referenzkarte = new Referenzkarte($gui);
		return $referenzkarte->find_by(Referenzkarte::$identifier, $id);
	}

	public static	function find($gui, $where, $order) {
		$referenzkarte = new Referenzkarte($gui);
		return $referenzkarte->find_where($where, $order);
	}
}
?>
