<?php
class Style2Class extends MyObject {

	static $write_debug = false;

	function Style2Class($gui) {
		$this->MyObject($gui, 'u_styles2classes');
	}

	public static	function find($gui, $where) {
		$style2class = new Style2Class($gui);
		return $style2class->find_where($where);
	}

	function copy($class_id) {
		$this->debug->show('Copy Style2Class mit class_id: ' . $class_id, Style2Class::$write_debug);
		$new_style2class = clone $this;
		$new_style2class->set('class_id', $class_id);
		$new_style2class->create();
	}
}
?>
