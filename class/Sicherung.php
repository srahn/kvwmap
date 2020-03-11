<?php
include_once(CLASSPATH . 'MyObject.php');
class Sicherung extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		$this->MyObject($gui, 'sicherungen');
		$this->identifier = 'id';
		$this->setKeys(
			array(
				"id",
				"name",
				"beschreibung",
				"intervall",
				"target_dir"
			)
		);
	}

	public function validate() {
		$results = array();
		$results[] = $this->validates('name', 'not_null', 'Es muss ein Name angegeben werden.');
		$results[] = $this->validates('intervall', 'not_null', 'Es muss ein intervall angegeben werden im Crontab Format * * * *.');
		$results[] = $this->validates('target_dir', 'not_null', 'Es muss ein Zielverzeichnis angegeben sein.');
		$messages = array();
		foreach($results AS $result) {
			if (!empty($result)) {
				$messages[] = $result;
			}
		}
		return $messages;
	}

	public static function find_by_id($gui, $id) {
		$s = new Sicherung($gui);
		return $s->find_by('id', $id);
	}

	public static	function find($gui, $where = '1=1') {
		$Sicherung = new Sicherung($gui);
		$sicherungen = $Sicherung->find_where($where, 'name');
		foreach($sicherungen AS $sicherung) {
			$sicherung->inhalte = Sicherungsinhalt::find($gui, 'sicherung_id = ' . $sicherung->get('id'));
		}
		return $sicherungen;
	}
}
?>
