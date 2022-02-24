<?php
class LayerStyle extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'styles');
		$this->identifier = 'Style_ID';
	}

	public static	function find_by_id($gui, $by, $id) {
		#echo '<br>search for layerstyle with ' . $by . ' = ' . $id;
		$style = new LayerStyle($gui);
		return $style->find_by($by, $id);
	}

	public static	function find($gui, $where) {
		$style = new LayerStyle($gui);
		return $style->find_where($where);
	}

	function get_layerdef() {
		#echo '<br>get_layerdef color: rgb(' . $this->get('outlinecolor') . ')';
		$layerdef = (Object) array(
			'symbolname' => ($this->get('symbolname') == null ? 'circle' : $this->get('symbolname')),
			'size' => ($this->get('size') == null ? '12' : $this->get('size')),
			'stroke' => ($this->get('outlinecolor') != '-1 -1 -1'),
			'weight' => ($this->get('width') == null ? 1 : $this->get('width')),
			'color' => 'rgb(' . $this->get('outlinecolor') . ')',
			'fill' => ($this->get('color') != '-1 -1 -1'),
			'fillColor' => ($this->get('color') == '-1 -1 -1' ? '#0000ff' : 'rgb(' . $this->get('color') . ')'),
			'fillOpacity' => ($this->get('opacity') == '' ? 0.6 : $this->get('opacity') / 100)
		);
		return $layerdef;
	}
} ?>