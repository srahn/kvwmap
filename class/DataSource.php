<?php
include_once(CLASSPATH . 'MyObject.php');
include_once(CLASSPATH . 'LayerDataSource.php');
class DataSource extends MyObject
{

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
				'german'		=> 'name',
				'english' => 'name',
				'low_german' => 'name'
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

	function __construct($gui)
	{
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

	public static	function find($gui, $where, $order = '', $sort = '') {
		$datasource = new DataSource($gui);
		$datasources = $datasource->find_where($where, ($order == '' ? 'name' : $order), $sort);
		usort(
			$datasources,
			function($a, $b) {
				if (($a->get('name') ?? $a->get('beschreibung')) < ($b->get('name') ?? $b->get('beschreibung'))) {
					return -1;
				}
				if (($a->get('name') ?? $a->get('beschreibung')) > ($b->get('name') ?? $b->get('beschreibung'))) {
					return 1;
				}
				return 0;
			}
		);
		return $datasources;
	}

	public static function find_by_layer_id($gui, $layer_id) {
		$datasource = new DataSource($gui);
		$layer_datasources = LayerDataSource::find($gui, '`layer_id` = ' . $layer_id);
		if (count($layer_datasources) > 0) {
			$datasources = $datasource->find_where('`id` IN (' . implode(
				', ',
				array_map(
					function ($layer_datasource) {
						return $layer_datasource->get('datasource_id');
					},
					$layer_datasources
				)
			) . ')');
			usort(
				$datasources,
				function($a, $b) {
					if ($a->get('name') ?? $a->get('beschreibung') < $b->get('name') ?? $b->get('beschreibung')) {
						return -1;
					}
					if ($a->get('name') ?? $a->get('beschreibung') > $b->get('name') ?? $b->get('beschreibung')) {
						return 1;
					}
					return 0;
				}
			);
			return $datasources;
		} else {
			return array();
		}
	}
}
