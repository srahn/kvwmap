<?php
include_once(CLASSPATH . 'PgObject.php');
include_once(CLASSPATH . 'Layer.php');
class LayerParam extends PgObject {

	static $write_debug = false;

	var $layers = [];

	function __construct($gui) {
		parent::__construct($gui, 'kvwmap', 'layer_parameter');
		$this->gui = $gui;
		$this->has_many = array(
			"layers" => array(
				"alias" => 'Layer',
				"table" => 'layer',
				"vorschau" => 'name',
				"pk" => 'layer_id',
				"fk" => 'gruppe'
			)
		);

		$this->validations = array(
			array(
				'attribute' => 'key',
				'condition' => 'not_null',
				'description' => 'Es muss ein Parametername angegeben werden.',
				'options' => null
			)
		);
	}

	public static	function find_by_key($gui, $key) {
		$obj = new LayerParam($gui);
		$layer_param = $obj->find_by('key', $key);
		return $layer_param;
	}

	function get_options($user_id, $stelle_id) {
		$sql = $this->get('options_sql');
		$sql = str_replace('$USER_ID', $user_id, $sql);
		$sql = str_replace('$STELLE_ID', $stelle_id, $sql);
		if ($this->gui->user->id == 1) {
			// echo '<br>SQL zur Abfrage der Optionen des Layerparameter ' . $param['key'] . ': ' . $sql;
		}
		$result = $this->gui->pgdatabase->execSQL($sql, 4, 0, false);
		if (!$result['success']) {
			return array(
				'success' => false,
				'msg' => 'Fehler bei der Abfrage der Parameteroptionen: ' . $options_result[1]
			);
		}
		else {
			return array(
				'success' => true,
				'options' => pg_fetch_all($result[1])
			);
		}
	}
}
?>
