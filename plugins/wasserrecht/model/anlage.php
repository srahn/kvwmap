<?php
#############################
# Klasse Konvertierung #
#############################

class Anlage extends PgObject {

	static $schema = 'wasserrecht';
	static $tableName = 'anlagen';
	static $write_debug = false;

	function Anlage($gui) {
		$this->PgObject($gui, Anlage::$schema, Anlage::$tableName);
	}

	public static	function find_by_id($gui, $by, $id) {
		#echo '<br>find konvertierung by ' . $by . ' = ' . $id;
		$anlage = new Anlage($gui);
		$anlage->find_by($by, $id);
		return $anlage;
	}

	function say_hello() {
		return 'hello';
	}
}
?>
