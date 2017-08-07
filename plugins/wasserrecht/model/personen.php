<?php
#############################
# Klasse Konvertierung #
#############################

class Personen extends PgObject {

	static $schema = 'wasserrecht';
	static $tableName = 'personen';
	static $write_debug = true;

	function Personen($gui) {
		parent::__construct($gui, Personen::$schema, Personen::$tableName);
	}

	public static function find_by_id($gui, $by, $id) {
		$personen = new Personen($gui);
		$personen->find_by($by, $id);
		return $personen;
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