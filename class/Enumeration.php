<?php
include_once(CLASSPATH . 'MyObject.php');
class Enumeration extends MyObject {

	static $write_debug = false;

	function __construct($gui, $enum_table = '') {
		parent::__construct($gui, 'enumerations');
		$this->enum_table = $enum_table;
	}

	function get_generic_select($layer, $attr) {
		# select z.B.:  $attr['typname'] . "_" . $attr['att_name'] . ".wert (" .  $attr['typname'] . "_" . $attr['att_name'] . ".beschreibung) AS " . $attr['att_name']
		# from z.B.: "LEFT JOIN " . $this->get('schema') . ".enum_" . $attr['typname'] . " AS " . $attr['typname'] . "_" . $attr['att_name'] . " ON " . $this->get('schema') . '.' $this->get('maintable') . "." . $attr['att_name'] . " = " . $attr['typname'] . "_" . $attr['att_name'] . ".wert"

		return array(
			'select' => $layer->get('schema') . '.' . $layer->get('maintable') . '.' . $attr['att_name'],
			'from' => ''
		);
	}
}
?>
