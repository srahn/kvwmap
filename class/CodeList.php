<?php
include_once(CLASSPATH . 'MyObject.php');
class CodeList extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'codelists');
	}

	function get_generic_select($layer, $attr) {
		return array(
			'select' => '(' . $layer->get_table_alias() . '.' . $attr['att_name'] . '' . ($attr['is_array'] == 't' ? '[1]' : '') . ').id AS ' . $attr['att_name'] . '_id,
    gdi_codelist_json_to_text(to_json(' . $layer->get_table_alias() . '.' . $attr['att_name'] . ')) AS ' . $attr['att_name'] . '_text',
			'from' => ''
		);
	}
}
?>
