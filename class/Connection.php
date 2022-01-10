<?php
include_once(CLASSPATH . 'MyObject.php');
class Connection extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'connections');
		$this->identifier = 'id';
		$this->validations = array(
			array(
				'attribute' => 'name',
				'condition' => 'not_null',
				'description' => 'Es muss ein Name für die Auswahl angegeben werden.',
				'option' => null
			),
			array(
				'attribute' => 'name',
				'condition' => 'unique',
				'description' => 'Falscher Name.',
				'option' => null
			),
			array(
				'attribute' => 'host',
				'condition' => 'not_null',
				'description' => 'Es muss ein Host angegeben werden.',
				'option' => null
			),
			array(
				'attribute' => 'dbname',
				'condition' => 'not_null',
				'description' => 'Es muss eine Datenbank angegeben werden.',
				'option' => null
			),
			array(
				'attribute' => 'user',
				'condition' => 'not_null',
				'description' => 'Es muss eine Datenbanknutzer angegeben werden.',
				'option' => null
			),
			array(
				'attribute' => 'password',
				'condition' => 'not_null',
				'description' => 'Es muss eine Passwort angegeben werden.',
				'option' => null
			)
		);

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
