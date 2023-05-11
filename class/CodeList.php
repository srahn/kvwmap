<?php
include_once(CLASSPATH . 'MyObject.php');
class CodeList extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'codelists');
	}

	function get_generic_select($layer, $attr) {
		return array(
			'select' => $layer->get('schema') . '.' . $layer->get('maintable') . '.' . $attr['att_name'],
			'from' => ''
		);
	}
}
?>
