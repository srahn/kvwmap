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

	function has_icon() {
		$map = new mapObj('');
		#echo '<br>symbolset: ' . SYMBOLSET;
		$map->setSymbolSet(SYMBOLSET);
		$map->setFontSet(FONTSET);
		$symbol_index = $map->getSymbolByName($this->get('symbolname'));
		if ($symbol_index == -1) {
			return false;
		}
		$symbol = $map->getSymbolObjectById($symbol_index);
		return ($symbol->type == '1006');
	}

	function get_icondef() {
		$map = new mapObj('');
		$map->setSymbolSet(SYMBOLSET);
		$map->setFontSet(FONTSET);
		$symbol = $map->getSymbolObjectById($map->getSymbolByName($this->get('symbolname')));
		#echo '<br>symbol: ' . $symbol->name . ' imagepath: ' . $symbol->imagepath;
		$iconSize = ($this->get('size') == null ? '12' : $this->get('size'));
		$icondef = (Object) array(
			'iconUrl' => URL . str_replace(WWWROOT, '', dirname(SYMBOLSET)) . '/' . $symbol->imagepath,
			'iconSize' => array(
				$iconSize,
				$iconSize
			),
			'iconAnchor' => array(
				$iconSize / 2,
				$iconSize / 2
			),
			"popupAnchor" => array(
				$iconSize / 2,
				$iconSize / 2
			)
		);
		return $icondef;
	}

	function get_styledef($datentyp = 0) {
		switch ($datentyp) {
			case 1 : {
				$layerdef = (Object) array(
					'weight'			=> ($this->get('width') == null ? 1 : $this->get('width')),
					'color'				=> 'rgb(' . $this->get('color') . ')',
				);
			} break;
			case 2 : {
				#echo '<br>color: ' . (($this->get('color') == '' OR $this->get('color') == '-1 -1 -1') ? '#0000ff' : 'rgb(' . $this->get('color') . ')');
				$layerdef = (Object) array(
					'symbolname'	=> ($this->get('symbolname') == null ? 'circle' : $this->get('symbolname')),
					'size'				=> ($this->get('size') == null ? '12' : $this->get('size')),
					'stroke'			=> ($this->get('outlinecolor') != '-1 -1 -1'),
					'weight'			=> ($this->get('width') == null ? 1 : $this->get('width')),
					'color'				=> 'rgb(' . $this->get('outlinecolor') . ')',
					'fill'				=> ($this->get('color') != '' AND $this->get('color') != '-1 -1 -1'),
					'fillColor'		=> (($this->get('color') == '' OR $this->get('color') == '-1 -1 -1') ? '#0000ff' : 'rgb(' . $this->get('color') . ')'),
					'fillOpacity'	=> ($this->get('opacity') == '' ? 0.6 : $this->get('opacity') / 100)
				);
			} break;
			default : {
				$layerdef = (Object) array(
					'symbolname'	=> ($this->get('symbolname') == null ? 'circle' : $this->get('symbolname')),
					'size'				=> ($this->get('size') == null ? '12' : $this->get('size')),
					'stroke'			=> ($this->get('outlinecolor') != '-1 -1 -1'),
					'weight'			=> ($this->get('width') == null ? 1 : $this->get('width')),
					'color'				=> 'rgb(' . $this->get('outlinecolor') . ')',
					'fill'				=> ($this->get('color') != '' AND $this->get('color') != '-1 -1 -1'),
					'fillColor'		=> (($this->get('color') == '' OR $this->get('color') == '-1 -1 -1') ? '#0000ff' : 'rgb(' . $this->get('color') . ')'),
					'fillOpacity'	=> ($this->get('opacity') == '' ? 0.6 : $this->get('opacity') / 100)
				);
			}
		}
		#echo '<br>get_layerdef color: rgb(' . $this->get('outlinecolor') . ')';
		return $layerdef;
	}
} ?>