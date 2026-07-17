<?php
include_once(CLASSPATH . 'PgObject.php');
class CodeList extends PgObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'kvwmap', 'codelists');
	}

	// function get_generic_select($layer, $attr) {
	// 	return array(
	// 		'select' => 'gdi_codelist_extract_ids(' . $layer->get_table_alias() . '.' . $attr['att_name'] . ', false) AS ' . $attr['att_name'] . ',
  //   gdi_codelist_extract_ids(' . $layer->get_table_alias() . '.' . $attr['att_name'] . ', true) AS ' . $attr['att_name'] . '_id,
  //   gdi_codelist_json_to_text(to_json(' . $layer->get_table_alias() . '.' . $attr['att_name'] . '), pg_typeof(' . $layer->get_table_alias() . '.' . $attr['att_name'] . ')::text) AS ' . $attr['att_name'] . '_text',
	// 		'from' => ''
	// 	);
	// }

	function get_generic_select($table_alias, $attr, $existing_attr_names = array()) {
		$selects = array(
			array(
				'att_name' => $attr['att_name'],
				'sql' => 'gdi_codelist_extract_ids(' . $table_alias . '.' . $attr['att_name'] . ', false) AS ' . $attr['att_name']
			),
			array(
				'att_name' => $attr['att_name'] . '_id',
				'sql' => 'gdi_codelist_extract_ids(' . $table_alias . '.' . $attr['att_name'] . ', true) AS ' . $attr['att_name'] . '_id'
			),
			array(
				'att_name' => $attr['att_name'] . '_text',
				'sql' => 'gdi_codelist_json_to_text(to_json(' . $table_alias . '.' . $attr['att_name'] . '), pg_typeof(' . $table_alias . '.' . $attr['att_name'] . ')::text) AS ' . $attr['att_name'] . '_text'
			)
		);
		return $selects;
	}
}
?>
