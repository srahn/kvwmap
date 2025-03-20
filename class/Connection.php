<?php
include_once(CLASSPATH . 'PgObject.php');
class Connection extends PgObject {
	static $write_debug = false;
	static $title = array(
		'german' => 'Datenbankverbindungen',
		'english' => 'Database connections',
		'low_german' => 'Datenbankvebindungen'
	);
	static $table_name = 'connections';
	static $attributes = array(
		[
			'attribute' => 'id',
			'alias'			=> array(
				'german'		=> 'ID',
				'english' => 'ID',
				'low_german' => 'ID'
			),
			'type'			=> 'text',
			'privileg'	=> '',
			'size'			=> 2
		],
		[
			'attribute' => 'name',
			'alias'			=> array(
				'german'		=> 'Bezeichnung',
				'english' => 'Name',
				'low_german' => 'Bezeichnung'
			),
			'type'			=> 'text',
			'privileg'	=> 'editable',
			'size'			=> 40
		],
		[
			'attribute' => 'host',
			'alias'			=> array(
				'german' => 'Host',
				'english' => 'Host'
			),
			'type'			=> 'text',
			'privileg'	=> 'editable',
			'size'			=> 8
		],
		[
			'attribute' => 'port',
			'alias'			=> array(
				'german' => 'Port',
				'english' => 'Port'
			),
			'type'			=> 'text',
			'privileg'	=> 'editable',
			'size'			=> 5
		],
		[
			'attribute' => 'dbname',
			'alias'			=> array(
				'german' => 'Datenbankname'
			),
			'type'			=> 'text',
			'privileg'	=> 'editable',
			'size'			=> 10
		],
		[
			'attribute' => 'user',
			'alias'			=> array(
				'german' => 'Nutzername'
			),
			'type'			=> 'text',
			'privileg'	=> 'editable',
			'size'			=> 15
		],
		[
			'attribute' => 'password',
			'alias'			=> array(
				'Passwort'
			),
			'type'			=> 'password',
			'privileg'	=> 'editable',
			'size'			=> 12
		],
	);

	function __construct($gui) {
		parent::__construct($gui, 'kvwmap', 'connections');
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

	function get_connection_string() {
		return 'host: ' . $this->get('host') . 'port: ' . $this->get('port') . ' dbname: ' . $this->get('dbname') . ' user: ' . $this->get('user');
	}

	public static function find_by_id($gui, $id) {
		if ($id) {
			$connection = new Connection($gui);
			return $connection->find_by('id', $id);
		}
		else {
			return null;
		}
	}

	public static	function find($gui, $order = '', $sort = '') {
		$connection = new Connection($gui);
		return $connection->find_where('', ($order == '' ? 'name' : $order), $sort);
	}

	function get_tables() {
		$pgdatabase = new pgdatabase();
		if ($pgdatabase->open($this->get('id'))) {
			$tables = array_merge(...(array_map(
				function($schema) use ($pgdatabase) {
					return $pgdatabase->get_tables($schema);
				},
				$pgdatabase->get_schemata($this->get('user'))
			)));
			if ($this->gui->pgdatabase->connection_id != $this->get('id')) {
				$pgdatabase->close();
			}
			return $tables;
		}
		return array();
	}
}
?>
