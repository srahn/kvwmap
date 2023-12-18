<?php
include_once(CLASSPATH . 'MyObject.php');
class CodeList extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'codelists');
	}

	function get_generic_select($layer, $attr) {
		return array(
			'select' => 'gdi_codelist_extract_ids(' . $layer->get_table_alias() . '.' . $attr['att_name'] . ', false) AS ' . $attr['att_name'] . ',
    gdi_codelist_extract_ids(' . $layer->get_table_alias() . '.' . $attr['att_name'] . ', true) AS ' . $attr['att_name'] . '_id,
    gdi_codelist_json_to_text(to_json(' . $layer->get_table_alias() . '.' . $attr['att_name'] . '), pg_typeof(' . $layer->get_table_alias() . '.' . $attr['att_name'] . ')::text) AS ' . $attr['att_name'] . '_text',
			'from' => ''
		);
	}
}
?>
