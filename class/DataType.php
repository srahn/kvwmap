<?php
include_once(CLASSPATH . 'MyObject.php');
class DataType extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'datatypes');
	}

	public static	function find_by_id($gui, $id) {
		$datatype = new DataType($gui);
		return $datatype->find_by('id', $id);
	}

	// function get_generic_select($layer, $attr) {
	// 	// echo '<p>' . print_r($layer, true);
	// 	 //echo '<br>' . print_r($attr, true);

	// 	 return array(
	// 		'select' => ($attr['is_array' ] == 't' ? $layer->get_table_alias() . "." . $attr['att_name'] : "regexp_replace(" . $layer->get_table_alias() . "." . $attr['att_name'] . "::text, '^[\(\{\} ,\)]*$', '') AS " . $attr['att_name']) . ",
  //   CASE WHEN gdi_datatype_json_to_text(to_json(" . $layer->get_table_alias() . "." . $attr['att_name'] . "), " . ($attr['is_array'] == 't' ? "true" : "false") . ") = '' THEN NULL ELSE gdi_datatype_json_to_text(to_json(" . $layer->get_table_alias() . "." . $attr['att_name'] . "), " . ($attr['is_array'] == 't' ? "true" : "false") . ") END AS " . $attr['att_name'] . "_dt"
	// 	);
	// }

	function get_generic_select($table_alias, $attr) {
		$selects = array(
			array(
				'att_name' => $attr['att_name'],
				'sql' => ($attr['is_array' ] == 't' ? "CASE WHEN array_length(" . $table_alias . "." . $attr['att_name'] . ", 1) = 0 THEN NULL ELSE " . $table_alias . "." . $attr['att_name'] . " END AS " . $attr['att_name'] : "regexp_replace(" . $table_alias . "." . $attr['att_name'] . "::text, '^[\(\{\} ,\)]*$', '') AS " . $attr['att_name'])
			),
			array(
				'att_name' => $attr['att_name'] . '_dt',
				'sql' => "CASE WHEN gdi_datatype_json_to_text(to_json(" . $table_alias . "." . $attr['att_name'] . "), " . ($attr['is_array'] == 't' ? "true" : "false") . ") = '' THEN NULL ELSE gdi_datatype_json_to_text(to_json(" . $table_alias . "." . $attr['att_name'] . "), " . ($attr['is_array'] == 't' ? "true" : "false") . ") END AS " . $attr['att_name'] . "_dt"
			)
		);
		return $selects;
	}

}
?>
