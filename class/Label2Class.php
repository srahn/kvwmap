<?php
class Label2Class extends PgObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'kvwmap', 'u_labels2classes', 'class_id, label_id');
	}

	public static	function find($gui, $where) {
		$label2class = new Label2Class($gui);
		return $label2class->find_where($where);
	}

	function copy($class_id) {
		$this->debug->show('Copy Label2Class mit class_id: ' . $class_id, Label2Class::$write_debug);
		$new_label2class = clone $this;
		$new_label2class->set('class_id', $class_id);
		$new_label2class->create();
	}
}
?>
