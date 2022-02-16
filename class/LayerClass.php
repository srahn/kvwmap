<?php
class LayerClass extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'classes');
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

	function ret($mm) {
		return $mm;
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

	function get_first_style_def() {
		#echo '<br>LayerClass->get_first_style for Class id: ' . $this->get($this->identifier);
		include_once(CLASSPATH . 'Style2Class.php');
		include_once(CLASSPATH . 'LayerStyle.php');
		$styles2class = Style2Class::find($this->gui, 'class_id = ' . $this->get($this->identifier));
		if (count($styles2class) == 0) {
			return '';
		}
		#echo '<br>found ' . count($styles2class) . ' styles2class';
		return LayerStyle::find_by_id($this->gui, 'Style_ID', $styles2class[0]->get('style_id'))->get_layerdef();
	}

	function get_layerdef($classitem = null) {
		#echo 'LayerClass->get_layerdef';

		if ($this->get('Expression') == '') {
			$def = '';
		}
		elseif (preg_match('/^\([^\[]*\[[^\]]*\][^\)]*\)$/', $this->get('Expression'))) {
			$def = trim($this->get('Expression'));
		}
		elseif ($classitem == '') {
			$def = '';
		}
		else {
			$def = '([' . $classitem . '] = ' . $this->get('Expression') . ')';
		}

		#echo '<br>getLayerdef vor class: ' . $this->get('Class_ID');
		$layerdef = (Object) array(
			'def' => $def,
			'name' => $this->get('Name'),
			'style' => $this->get_first_style_def()
		);
		return $layerdef;
	}
} ?>