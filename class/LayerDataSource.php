<?php
include_once(CLASSPATH . 'PgObject.php');
class LayerDataSource extends PgObject {

	static $write_debug = false;
	static $title = array(
		'german' => 'Quellenangaben zum Layer',
		'english' => 'Datasource for Layer',
		'low_german' => 'Quellenangaben zum Layer'
	);
	static $table_name = 'datasources';
	static $attributes = array(
		[
			'attribute'	=> 'layer_id',
			'alias'			=> array(
				'german'		=> 'Layer-ID',
				'english' => 'Layer-ID',
				'low_german' => 'Layer-ID'
			),
			'type'			=> 'text',
			'privileg'	=> 'editable',
			'size'			=> 20
		],
		[
			'attribute'	=> 'datasource_id',
			'alias'			=> array(
				'german'		=> 'Datasource-ID',
				'english' => 'Datasource-ID',
				'low_german' => 'Datasource-ID'
			),
			'type'			=> 'text',
			'privileg'	=> 'editable',
			'size'			=> 20
		]
	);

	function __construct($gui) {
		parent::__construct($gui, 'kvwmap', 'layer_datasources', '');
		$this->identifiers = array(
			array(
				'key' => 'layer_id',
				'type' => 'integer'
			),
			array(
				'key' => 'datasource_id',
				'type' => 'integer'
			)
		);

		$this->validations = array(
			array(
				'attribute' => 'layer_id',
				'condition' => 'not_null',
				'description' => 'Ein Layer muss angegeben werden.',
				'option' => null
			),
			array(
				'attribute' => 'datasource_id',
				'condition' => 'not_null',
				'description' => 'Eine Datasource muss angegeben werden.',
				'option' => null
			)
		);

		$this->setKeys(
			array(
				"layer_id",
				"datasource_id",
				"sortorder"
			)
		);
	}

	public static function find_by_id($gui, $id) {
		$layer_datasource = new LayerDataSource($gui);
		return $layer_datasource->find_by('id', $id);
	}

	public static	function find($gui, $where, $order = '', $sort = '') {
		$layer_datasource = new LayerDataSource($gui);
		return $layer_datasource->find_where($where, $order, $sort);
	}
}
?>
