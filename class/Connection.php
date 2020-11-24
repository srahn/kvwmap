<?php
include_once(CLASSPATH . 'MyObject.php');
class Connection extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'connections');
		$this->identifier = 'id';
		$this->setKeys(
			array(
				"id",
				"name",
				"host",
				"port",
				"dbname",
				"user",
				"password"
			)
		);
	}

	public function validate($on = '') {
		$results = array();
		$results[] = $this->validates('name', 'not_null', 'Es muss ein Name für die Auswahl angegeben werden.');
		$results[] = $this->validates('name', 'unique', 'Falscher Name.');
		$results[] = $this->validates('host', 'not_null', 'Es muss ein Host angegeben werden.');
		$results[] = $this->validates('dbname', 'not_null', 'Es muss eine Datenbank angegeben werden.');
		$results[] = $this->validates('user', 'not_null', 'Es muss ein Datenbanknutzer angegeben werden.');
		$results[] = $this->validates('password', 'not_null', 'Es muss ein Password angegeben werden.');

		$messages = array();
		foreach($results AS $result) {
			if (!empty($result)) {
				$messages[] = $result;
			}
		}
		return $messages;
	}

	public static function find_by_id($gui, $id) {
		$connection = new Connection($gui);
		return $connection->find_by('id', $id);
	}

	public static	function find($gui, $order = '', $sort = '') {
		$connection = new Connection($gui);
		return $connection->find_where('', ($order == '' ? 'name' : $order), $sort);
	}
}
?>
