<?php
include_once(CLASSPATH . 'MyObject.php');
class DataType extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'datatypes');
	}

	function get_generic_select($layer, $attr) {
		return array(
			'select' => $layer->get_table_alias() . "." . $attr['att_name'] . ", gdi_datatype_json_to_text(to_json(" . $layer->get_table_alias() . "." . $attr['att_name'] . "), " . ($attr['is_array'] == 't' ? "true" : "false") . ") AS " . $attr['att_name'] . "_dt"
		);
	}
}
?>
