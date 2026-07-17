<?php
class RolleSavedLayers extends PgObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'kvwmap', 'rolle_saved_layers', 'id');
	}

	public static	function find_by_name($gui, $name) {
		$rolle_saved_layers = new RolleSavedLayers($gui);
    $rolle_saved_layers->show = true;
		$result = $rolle_saved_layers->find_by('name', '%' . $name . '%', 'LIKE');
    return ($result->data ? $result : false);
	}
}
?>
