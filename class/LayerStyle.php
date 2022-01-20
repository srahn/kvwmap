<?php
class LayerStyle extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'styles');
		$this->identifier = 'Style_ID';
	}

	public static	function find_by_id($gui, $by, $id) {
		$style = new LayerStyle($gui);
		return $style->find_by($by, $id);
	}

	public static	function find($gui, $where) {
		$style = new LayerStyle($gui);
		return $style->find_where($where);
	}

	function get_layerdef() {
		$layerdef = (Object) array(
			'color' => $this->get('outlinecolor'),
			'weight' => ($this->get('width') == null ? 1 : $this->get('width')),
			'fill' => ($this->get('color') != '-1 -1 -1'),
			'fillColor' => ($this->get('color') == '-1 -1 -1' ? '#0000ff' : $this->get('color')),
			'fillOpacity' => ($this->get('opacity') == null ? 1.0 : $this->get('opacity'))
		);
		return $layerdef;
	}
} ?>