<?php
include_once(CLASSPATH . 'MyObject.php');
class Sicherungsinhalt extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'sicherungsinhalte');
		$this->identifier = 'id';
		$this->setKeys(
			array(
				"id",
				"name",
				"beschreibung",
				"methode",
				"source",
				"connection_id",
				"target",
				"overwrite",
				"sicherung_id"
			)
		);
	}

	public function validate() {
		$results = array();
		$results[] = $this->validates('name', 'not_null', 'Es muss ein Name angegeben werden.');
		$results[] = $this->validates('methode', 'not_null', 'Wähle eine Methode aus.');
		$results[] = $this->validates('source', 'not_null', 'Wähle eine Quelle der Sicherung aus.');
		$results[] = $this->validates('target', 'not_null', 'Wähle ein Ziel der Sicherung aus.');
		$results[] = $this->validates('sicherung_id', 'not_null', 'Wähle eine Sicherung aus mit der der Inhalt gesichert werden soll.');

		$messages = array();
		foreach($results AS $result) {
			if (!empty($result)) {
				$messages[] = $result;
			}
		}
		return $messages;
	}

	public static function find_by_id($gui, $id) {
		$s = new Sicherungsinhalt($gui);
		return $s->find_by('id', $id);
	}

	public static	function find($gui, $where = '1=1') {
		$Sicherungsinhalt = new Sicherungsinhalt($gui);
		return $Sicherungsinhalt->find_where($where, 'name');
	}

}
?>
