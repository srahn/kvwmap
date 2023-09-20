<?php
include_once(CLASSPATH . 'MyObject.php');
class DataType extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'datatypes');
	}

	function get_generic_select($layer, $attr) {
		return array(
			'select' => ($attr['is_array' ] == 't' ? $layer->get_table_alias() . "." . $attr['att_name'] : "regexp_replace(" . $layer->get_table_alias() . "." . $attr['att_name'] . "::text, '^[\(\{\} ,\)]*$', '') AS " . $attr['att_name']) . ", CASE WHEN gdi_datatype_json_to_text(to_json(" . $layer->get_table_alias() . "." . $attr['att_name'] . "), " . ($attr['is_array'] == 't' ? "true" : "false") . ") = '' THEN NULL ELSE gdi_datatype_json_to_text(to_json(" . $layer->get_table_alias() . "." . $attr['att_name'] . "), " . ($attr['is_array'] == 't' ? "true" : "false") . ") END AS " . $attr['att_name'] . "_dt"
		);
	}
}
?>
