<?php
class LayerClass extends MyObject {

	static $write_debug = false;

	function LayerClass($gui) {
		$this->MyObject($gui, 'classes');
		$this->identifier = 'Class_ID';
	}

	public static	function find_by_id($gui, $by, $id) {
		$class = new LayerClass($gui);
		return $class->find_by($by, $id);
	}

	public static	function find($gui, $where) {
		$layer_class = new LayerClass($gui);
		return $layer_class->find_where($where);
	}

	function copy($layer_id) {
		$this->debug->show('Copy LayerClass id: ' . $this->get($this->identifier) . ' mit neuer layer_id: ' . $layer_id, LayerClass::$write_debug);
		$new_class = clone $this;
		unset($new_class->data[$this->identifier]);
		$new_class->set('Layer_ID', $layer_id);
		$new_class->create();

		$this->copy_styles2classes($new_class);
		$this->copy_labels2classes($new_class);
	}

	function copy_styles2classes($new_class) {
		foreach(Style2Class::find($this->gui, 'class_id = ' . $this->get($this->identifier)) AS $style2class) {
			$style2class->copy($new_class->get($new_class->identifier));
		}
	}

	function copy_labels2classes($new_class) {
		foreach(Label2Class::find($this->gui, 'class_id = ' . $this->get($this->identifier)) AS $label2class) {
			$label2class->copy($this->get('id'));
		}
	}
}
?>
