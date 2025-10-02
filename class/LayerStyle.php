<?php
class LayerStyle extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'styles', 'Style_ID');
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

	/*
	* Function return true if the layer style has a symbolname
	* the symbolname is in symbolset and the symbol is of type 1003 (png) or 1006 (svg)
	*/
	function has_icon() {
		$map = new mapObj('');
		#echo '<br>symbolset: ' . SYMBOLSET;
		$map->setSymbolSet(SYMBOLSET);
		$map->setFontSet(FONTSET);
		$symbol_index = $map->getSymbolByName($this->get('symbolname'));
		if ($symbol_index == -1) {
			return false;
		}
		if (MAPSERVERVERSION >= 800) {
			$symbol = $map->symbolset->getSymbol($symbol_index);
		}
		else {
			$symbol = $map->getSymbolObjectById($symbol_index);
		}
		return in_array($symbol->type, array('1003', '1006'));
	}

	function get_icondef() {
		$map = new mapObj('');
		$map->setSymbolSet(SYMBOLSET);
		$map->setFontSet(FONTSET);
		if (MAPSERVERVERSION >= 800) {
			$symbol = $map->symbolset->getSymbol($map->getSymbolByName($this->get('symbolname')));
		}
		else {
			$symbol = $map->getSymbolObjectById($map->getSymbolByName($this->get('symbolname')));
		}
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

		if ($this->get('minsize')) {
			$icondef->iconMinSize = array($this->get('minsize'), $this->get('minsize'));
			if ($this->get('maxsize') == '') {
				$icondef->iconMaxSize = $icondef->iconSize;
			}
		}
		if ($this->get('maxsize')) {
			$icondef->iconMaxSize = array($this->get('maxsize'), $this->get('maxsize'));
			if ($this->get('minsize') == '') {
				$icondef->iconMinSize = $icondef->iconSize;
			}
		}

		return $icondef;
	}

	function get_styledef($datentyp = 0, $layer_opacity = 1) {
		$color = trim($this->get('color'));
		switch ($datentyp) {
			case 1 : {
				$layerdef = (Object) array(
					'weight'			=> ($this->get('width') == null ? 1 : $this->get('width')),
					'color'				=> (($color == '' OR $color == '-1 -1 -1') ? '#000000' : 'rgb(' . $color . ')')
				);
			} break;
			case 2 : {
				#echo '<br>color: ' . (($color == '' OR $color == '-1 -1 -1') ? '#0000ff' : 'rgb(' . $color . ')');
				$layerdef = (Object) array(
					'symbolname'	=> ($this->get('symbolname') == null ? 'circle' : $this->get('symbolname')),
					'size'				=> ($this->get('size') == null ? '12' : $this->get('size')),
					'stroke'			=> ($this->get('outlinecolor') != '-1 -1 -1'),
					'weight'			=> ($this->get('width') == null ? 1 : $this->get('width')),
					'color'				=> 'rgb(' . $this->get('outlinecolor') . ')',
					'fill'				=> ($color != '' AND $color != '-1 -1 -1'),
					'fillColor'		=> (($color == '' OR $color == '-1 -1 -1') ? '#0000ff' : 'rgb(' . $color . ')'),
					'fillOpacity'	=> ($this->get('opacity') == '' ? $layer_opacity / 100 : $this->get('opacity') / 100)
				);
			} break;
			default : {
				// auch point (datentyp=0)
				$layerdef = (Object) array(
					'symbolname'	=> ($this->get('symbolname') == null ? 'circle' : $this->get('symbolname')),
					'size'				=> ($this->get('size') == null ? '12' : $this->get('size')),
					'stroke'			=> ($this->get('outlinecolor') != '-1 -1 -1'),
					'weight'			=> ($this->get('width') == null ? 1 : $this->get('width')),
					'color'				=> 'rgb(' . $this->get('outlinecolor') . ')',
					'fill'				=> ($color != '' AND $color != '-1 -1 -1'),
					'fillColor'		=> (($color == '' OR $color == '-1 -1 -1') ? '#0000ff' : 'rgb(' . $color . ')'),
					'fillOpacity'	=> ($this->get('opacity') == '' ? $layer_opacity / 100 : $this->get('opacity') / 100)
				);
				if ($this->get('minsize')) {
					$layerdef->minsize = $this->get('minsize');
					if ($this->get('maxsize') == '') {
						$layerdef->maxsize = $layerdef->size;
					}
				}
				if ($this->get('maxsize')) {
					$layerdef->maxsize = $this->get('maxsize');
					if ($this->get('minsize') == '') {
						$layerdef->minsize = $layerdef->size;
					}
				}
			}
		}
		#echo '<br>get_layerdef color: rgb(' . $this->get('outlinecolor') . ')';
		return $layerdef;
	}
} ?>