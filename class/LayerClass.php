<?php
class LayerClass extends PgObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'kvwmap', 'classes');
		$this->identifier = 'class_id';
	}

	public static	function find_by_id($gui, $by, $id) {
		$class = new LayerClass($gui);
		return $class->find_by($by, $id);
	}

	public static	function find($gui, $where, $order = '') {
		$layer_class = new LayerClass($gui);
		return $layer_class->find_where($where, $order);
	}

	function ret($mm) {
		return $mm;
	}

	function copy($layer_id) {
		$this->debug->show('Copy LayerClass id: ' . $this->get($this->identifier) . ' mit neuer layer_id: ' . $layer_id, LayerClass::$write_debug);
		$new_class = clone $this;
		unset($new_class->data[$this->identifier]);
		$new_class->set('layer_id', $layer_id);
		$new_class->create();

		$this->copy_styles2classes($new_class);
		$this->copy_labels2classes($new_class);
	}

	function copy_styles2classes($new_class) {
		include_once(CLASSPATH . 'Style2Class.php');
		foreach(Style2Class::find($this->gui, 'class_id = ' . $this->get($this->identifier)) AS $style2class) {
			$style2class->copy($new_class->get($new_class->identifier));
		}
	}

	function copy_labels2classes($new_class) {
		include_once(CLASSPATH . 'Label2Class.php');
		foreach(Label2Class::find($this->gui, 'class_id = ' . $this->get($this->identifier)) AS $label2class) {
			$label2class->copy($this->get('id'));
		}
	}

	function get_first_style($datentyp = 0) {
		#echo '<br>LayerClass->get_first_style for Class id: ' . $this->get($this->identifier);
		include_once(CLASSPATH . 'Style2Class.php');
		include_once(CLASSPATH . 'LayerStyle.php');
		$styles2class = Style2Class::find($this->gui, 'class_id = ' . $this->get($this->identifier) . ' AND style_id > 0');
		if (count($styles2class) == 0) {
			return '';
		}
		#echo '<br>found ' . count($styles2class) . ' styles2class';
		#echo '<br>first style_id: ' . $styles2class[0]->get('style_id');
		$layer_style = LayerStyle::find_by_id($this->gui, 'style_id', $styles2class[0]->get('style_id'));

		return $layer_style;
	}

	function get_layerdef($classitem = null, $datentyp = 0, $layer_opacity = 1) {
		#echo 'get_layerdef for Class: ' . $this->get('name') . '(' . $this->get('class_id') . ')';

		if ($this->get('expression') == '') {
			$def = '';
		}
		elseif (preg_match("/'\\[[^\\]]+\\]'\\s+in\\s+\\('[^']*'\\)/", $this->get('expression'))) {
			$def = trim($this->get('expression'));
		}
		elseif ($classitem == '') {
			$def = '';
		}
		else {
			$def = "([" . $classitem . "] = '" . $this->get('expression') . "')";
		}

		$first_style = $this->get_first_style($datentyp);

		$layerdef = (Object) array(
			'def' => $def,
			'name' => $this->get('name')
		);

		if (property_exists($first_style, 'data') AND count($first_style->data) > 0) {
			if ($first_style->has_icon()) {
				$layerdef->icon = $first_style->get_icondef();
			}
			else {
				$layerdef->style = $first_style->get_styledef($datentyp, $layer_opacity);
			}
		}
		return $layerdef;
	}
} ?>