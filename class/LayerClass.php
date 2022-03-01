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

	function get_first_style($datentyp = 0) {
		#echo '<br>LayerClass->get_first_style for Class id: ' . $this->get($this->identifier);
		include_once(CLASSPATH . 'Style2Class.php');
		include_once(CLASSPATH . 'LayerStyle.php');
		$styles2class = Style2Class::find($this->gui, 'class_id = ' . $this->get($this->identifier));
		if (count($styles2class) == 0) {
			return '';
		}
		#echo '<br>found ' . count($styles2class) . ' styles2class';
		#echo '<br>first style_id: ' . $styles2class[0]->get('style_id');
		$layer_style = LayerStyle::find_by_id($this->gui, 'Style_ID', $styles2class[0]->get('style_id'));
		#echo '<br>Class: ' . $this->get('Class_ID') . ' first style: ' . print_r($layer_style->data, true);
		return $layer_style;
	}

	function get_layerdef($classitem = null, $datentyp = 0) {
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
		$first_style = $this->get_first_style($datentyp);
		#echo '<br>class: ' . $this->get('Name');
		$layerdef = (Object) array(
			'def' => $def,
			'name' => $this->get('Name')
		);
		if ($first_style !== '') {
			if ($first_style->has_icon()) {
				$layerdef->icon = $first_style->get_icondef();
			}
			else {
				$layerdef->style = $first_style->get_styledef($datentyp);
			}
		}
		return $layerdef;
	}
} ?>