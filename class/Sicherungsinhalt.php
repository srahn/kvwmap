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
				"sicherung_id",
				"tar_compress",
				"pgdump_insert",
				"pgdump_columninserts",
				"pgdump_in_exclude_schemas",
				"pgdump_schema_list",
				"pgdump_in_exclude_tables",
				"pgdump_table_list"
			)
		);
	}

	public function validate($on = '') {
		$results = array();
		$results[] = $this->validates('name', 'not_null', 'Es muss ein Name angegeben werden.');
		$results[] = $this->validates('methode', 'not_null', 'Wähle eine Methode aus.');
		$results[] = $this->validates('target', 'not_null', 'Wähle ein Ziel der Sicherung aus.');
		$results[] = $this->validates('sicherung_id', 'not_null', 'Wähle eine Sicherung aus mit der der Inhalt gesichert werden soll.');

		switch ($this->get('methode')) {
			case 'Postgres Dump':
				$results[] = $this->validates('connection_id', 'not_null', 'Wähle eine Quelle der Sicherung aus.');
				break;
			default:
				$results[] = $this->validates('source', 'not_null', 'Wähle eine Quelle der Sicherung aus.');
				break;
		}

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

	function get_mysql_database_names(){
		$sql = "SHOW DATABASES";
		$this->database->execSQL($sql);
		if ($this->database->success){
			while ($row = $this->database->result->fetch_assoc()){
				$result[] = $row;
			}
		}
		return $result;
	}

	function get_pgsql_database_names(){
		include_once(CLASSPATH . 'Connection.php');
		$connections = Connection::find($this->gui);
		foreach ($connections as $connection) {
			$results[] = array($connection->get('id'), $connection->get('name'));
		}
		return $results;
	}

	function disable_options($formvars){
		switch ($this->get('methode')) {
			case 'Postgres Dump':
				$this->set('pgdump_insert', isset($formvars['pgdump_insert']) );
				$this->set('pgdump_columninserts', isset($formvars['pgdump_columninserts']) );
				$this->set('tar_compress', null);
				$this->set('source', null);
				break;

			default:
				$this->set('connection_id', null);
				$this->set('pgdump_insert', null);
				$this->set('pgdump_columninserts', null );
				$this->set('pgdump_in_exclude_schemas', null);
				$this->set('pgdump_schema_list', null);
				$this->set('pgdump_in_exclude_tables', null);
				$this->set('pgdump_table_list', null);
				break;
		}
	}

}
?>
