<?php
#############################
# Klasse Konvertierung #
#############################

class Anlage extends PgObject {

	static $schema = 'wasserrecht';
	static $tableName = 'anlagen';
	static $write_debug = true;

	function Anlage($gui) {
		$this->PgObject($gui, Anlage::$schema, Anlage::$tableName);
	}

	public static function find_by_id($gui, $by, $id) {
// 		echo '<br>find konvertierung by ' . $by . ' = ' . $id;
// 		echo '<br>find anlage by ' . $by . ' = ' . $id;
		$anlage = new Anlage($gui);
		$anlage->find_by($by, $id);
		return $anlage;
	}
	
// 	public static function find_where($gui, $where, $order = NULL, $select = '*') {
// 		$anlage = new Anlage($gui);
// 		$anlage->find_where($where, $order, $select);
// 		return $anlage;
// 	}

	function say_hello() {
		return 'hello';
	}
}
?>
