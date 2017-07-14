<?php
#############################
# Klasse Konvertierung #
#############################

class Gewaesserbenutzungen extends PgObject {

	static $schema = 'wasserrecht';
	static $tableName = 'gewaesserbenutzungen';
	static $write_debug = true;

	function Gewaesserbenutzungen($gui) {
		parent::__construct($gui, Gewaesserbenutzungen::$schema, Gewaesserbenutzungen::$tableName);
	}

	public static function find_by_id($gui, $by, $id) {
		$gewaesserbenutzung = new Gewaesserbenutzungen($gui);
		$gewaesserbenutzung->find_by($by, $id);
		return $gewaesserbenutzung;
	}
}
?>
