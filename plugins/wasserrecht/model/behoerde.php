<?php
#############################
# Klasse Konvertierung #
#############################

class Behoerde extends PgObject {

	static $schema = 'wasserrecht';
	static $tableName = 'behoerde';
	static $write_debug = true;

	function Behoerde($gui) {
	    parent::__construct($gui, Behoerde::$schema, Behoerde::$tableName);
	}

	public static function find_by_id($gui, $by, $id) {
	    $behoerde = new Behoerde($gui);
		$behoerde->find_by($by, $id);
		return $behoerde;
	}
	
	public function getName() {
	    return $this->data['name'];
	}
	
	public function getId() {
	    return $this->data['id'];
	}
	
	public function toString() {
	    return "id: " . $this->getId() . " name: " . $this->getName();
	}
}
?>