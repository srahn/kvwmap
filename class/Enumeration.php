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
		# xplankonverter.enum_json_to_text(to_json(rechtscharakter), 'fp_rechtscharakter', false)

		return array(
			'select' => ($attr['is_array'] == 't' ? "CASE WHEN array_length(" . $layer->get_table_alias() . "." . $attr['att_name'] . ", 1) = 0 THEN NULL ELSE array_to_string(" . $layer->get_table_alias() . "." . $attr['att_name'] . ", ',', '') END AS " . $attr['att_name'] : $layer->get_table_alias() . "." . $attr['att_name']) . ", gdi_enum_json_to_text(to_json(" . $layer->get_table_alias() . "." . $attr['att_name'] . "), 'xplan_gml', '" .  $attr['typname'] . "', " . ($attr['is_array'] == 't' ? "true" : "false") . ") AS " . $attr['att_name'] . "_text"
		);
	}
}
?>