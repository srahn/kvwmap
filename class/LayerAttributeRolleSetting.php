<?php
class LayerAttributeRolleSetting extends MyObject {

	static $write_debug = true;

	function __construct($gui) {
		parent::__construct($gui, 'layer_attributes2rolle');
		$this->identifier_type = 'array';
		$this->identifier = array(
			array('type' => 'int(11)', 'key' => 'layer_id'),
			array('type' => 'varchar(256)', 'key' => 'attributename'),
			array('type' => 'int(11)', 'key' => 'stelle_id'),
			array('type' => 'int(11)', 'key' => 'user_id')
		);
	}

	public static	function find_by_layer_rolle($layer_id, $stelle_id, $user_id) {
		return $user->find_where('`layer_id` = ' . $layer_id . ' AND `stelle_id` = ' . $stelle_id . ' `user_id` = ' . $user_id);
	}

	function resetSortOrder($layer_id, $stelle_id, $user_id) {
		$sql = "
			UPDATE
				layer_attributes2rolle
			SET
				sort_order = 0
			WHERE
				layer_id = " . $layer_id . " AND
				stelle_id = " . $stelle_id . " AND
				user_id = " . $user_id . "
		";
		#echo '<p>SQL zum Zurücksetzen der Sortierreihenfolge ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_layer_attributes2rolle - Zurücksetzen der Sortierreihenfolge:<br>" . $sql , 4);
		$this->database->execSQL($sql);
	}

	function delete_by_layer_rolle($layer_id, $stelle_id, $user_id) {
		$this->find_by_layer_rolle($layer_id);
	}

	function read_layer_attributes2rolle($layer_id, $stelle_id, $user_id) {
		$rolle_attribute_settings = array();
		$sql = "
			SELECT
				*
			FROM
				layer_attributes2rolle
			WHERE
				layer_id = " . $layer_id . " AND
				user_id = " . $user_id . " AND
				stelle_id = " . $stelle_id . "
		";
		#echo '<p>SQL zum Abfragen der rollenbezogenen Layerattributeinstellungen: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_layer_attributes2rolle - Lesen der rollenbezogenen Layerattributeinstellungen:<br>" . $sql , 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			echo err_msg($this->script_name, __LINE__, $sql); return 0;
		}
		while ($rs = $this->database->result->fetch_assoc()) {
			$rolle_attribute_settings[$rs['attributename']] = $rs;
		}
		return $rolle_attribute_settings;
	}
}
?>
