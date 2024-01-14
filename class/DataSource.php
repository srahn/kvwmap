<?php
include_once(CLASSPATH . 'MyObject.php');
class DataSource extends MyObject {

	static $write_debug = false;
	static $title = array(
		'german' => 'Quellenangabe',
		'english' => 'Datasource',
		'low_german' => 'Quellenangaben'
	);
	static $table_name = 'datasources';
	static $attributes = array(
		[
			'attribute'	=> 'id',
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
			'attribute'	=> 'name',
			'alias'			=> array(
				'german'		=> 'Name',
				'english' => 'Name',
				'low_german' => 'Name'
			),
			'type'			=> 'text',
			'privileg'	=> 'editable',
			'size'			=> 20
		],
		[
			'attribute'	=> 'beschreibung',
			'alias'			=> array(
				'german'		=> 'Beschreibung',
				'english' => 'Description',
				'low_german' => 'Beschreibung'
			),
			'type'			=> 'text',
			'privileg'	=> 'editable',
			'size'			=> 40
		]
	);

	function __construct($gui) {
		parent::__construct($gui, 'datasources');
		$this->validations = array(
			array(
				'attribute' => 'beschreibung',
				'condition' => 'not_null',
				'description' => 'Es muss eine Beschreibung angegeben werden.',
				'option' => null
			)
		);

		$this->setKeys(
			array(
				"id",
				"name",
				"beschreibung"
			)
		);
	}

	public static function find_by_id($gui, $id) {
		$datasource = new DataSource($gui);
		return $datasource->find_by('id', $id);
	}

	public static	function find($gui, $order = '', $sort = '') {
		$datasource = new DataSource($gui);
		return $datasource->find_where('', ($order == '' ? 'name' : $order), $sort);
	}
}
?>
