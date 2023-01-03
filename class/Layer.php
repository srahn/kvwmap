<?php
class Layer extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'layer');
		$this->stelle_id = $gui->stelle->id;
		$this->identifier = 'Layer_ID';
	}

	public static	function find($gui, $where, $order = '') {
		$layer = new Layer($gui);
		return $layer->find_where($where, $order);
	}

	public static	function find_by_id($gui, $id) {
		$layer = new Layer($gui);
		return $layer->find_by('Layer_ID', $id);
	}

	public static	function find_by_name($gui, $name) {
		$layer = new Layer($gui);
		$layers = $layer->find_where("Name LIKE '" . $name . "'");
		return $layers[0];
	}

	public static function find_by_obergruppe_und_name($gui, $obergruppe_id, $layer_name) {
		$layer = new Layer($gui);
		$result = $layer->find_by_sql(
			array(
				'select' => 'l.*',
				'from' => "
					u_groups g JOIN
					layer l ON (g.id = l.Gruppe)",
				'where' => "
					g.obergruppe = " . $obergruppe_id . " AND
					l.Name = '" . $layer_name . "'"
			)
		);
		return $result[0];
	}

	/**
	* This function return the layer id's of the duplicates of a layer
	* @param mysql_connection object
	* @param integer $duplicate_from_layer_id The layer id from witch the others are duplicates
	* @param array(integer) The layer_ids of the duplicates
	*/
	public static function find_by_duplicate_from_layer_id($database, $duplicate_from_layer_id) {
		$duplicate_layer_ids = array();
		$sql =  "
			SELECT
				`Layer_ID`
			FROM
				`layer`
			WHERE
				`duplicate_from_layer_id` = " . $duplicate_from_layer_id . "
				AND `Layer_ID` != `duplicate_from_layer_id`
		";
		# letzte Where Bedinung, damit keine Entlosschleifen entstehen beim Aufruf von update_layer falls
		# Layer_ID fälschlicherweise identisch sein sollte mit duplicate_layer_id was nicht passieren sollte
		# wenn das Layerformular genutzt wurde.
		#echo  MyObject::$write_debug ? 'Layer find_by_duplicate_from_layer_id sql:<br> ' . $sql : '';
		$ret = $database->execSQL($sql, 4, 1, true);
		if (!$ret['success']) {
			$database->gui->add_message('error', $ret[1]);
		}
		else {
			while ($rs = $database->result->fetch_assoc()) {
				$duplicate_layer_ids[] = $rs['Layer_ID'];
			}
		}
		return $duplicate_layer_ids;
	}

	/*
	* Diese Funktion legt vom aktuellen layer Objekt einen neuen Layer an
	* mit der übergebenen Layergruppe sowie alle seine zugehörigen Klassen und layer_attributes.
	* Vom Layer verwendete Styles und Labels werden wiederverwendet.
	* @return Layer Das kopierte Layerobjekt
	*/
	function copy($attributes) {
		$success = true;
		$this->debug->show('<p>Clone Templatelayer: ' . $this->get($this->identifier), Layer::$write_debug);
		$new_layer = clone $this;
		unset($new_layer->data['Layer_ID']);
		foreach ($attributes AS $key => $value) {
			$new_layer->set($key, $value);
		}
		$new_layer->create();
		$new_layer_id = $new_layer->get($new_layer->identifier);

		if (!empty($new_layer_id)) {
			$this->debug->show('<p>Copiere die Klassen des Template layers für neuen Layer id: ' . $new_layer_id, Layer::$write_debug);
			$this->copy_classes($new_layer_id);
			$this->debug->show('<p>Copiere die layer_attributes des Template layers für neuen Layer id: ' . $new_layer_id, Layer::$write_debug);
			$this->copy_layer_attributes($new_layer_id);
		}
		return $new_layer;
	}

	/*
	* Kopiere die Klassen des Layers mit anderer Layer_id
	*/
	function copy_classes($new_layer_id) {
		foreach(LayerClass::find($this->gui, 'Layer_id = ' . $this->get('Layer_ID')) AS $layer_class) {
			$this->debug->show('Copy class: ' . $layer_class->get('Name') . ' mit layer id: ' . $this->get('Layer_ID') . ' => ' . $new_layer_id, Layer::$write_debug);
			$layer_class->copy($new_layer_id);
		}
	}

	function copy_layer_attributes($new_layer_id) {
		foreach(LayerAttribute::find($this->gui, 'Layer_id = ' . $this->get('Layer_ID')) AS $attribute) {
			$this->debug->show('Copy Attribute: ' . $attribute->get('name') . ' mit neuer layer id: ' . $this->get('Layer_ID') . ' => ' . $new_layer_id, Layer::$write_debug);
			$attribute->copy($new_layer_id);
		}
	}

	/*
	* Function return true, if table of this layer is used at least in on other layer
	* It searches for layers with same maintable and schema
	* or schema.maintable used in Data exclude it self
	* @return boolean true if at least one other layer uses the table els false
	*/
	function tableUsedFromOtherLayers() {
		$data = $this->data;
		$layers = $this->find_where("
			(
				(
					`maintable` = '" . $this->get('maintable') . "' AND
					`schema` = '" . $this->get('schema') . "'
				) OR
				`Data` LIKE '%" . $this->get('schema') . "." . $this->get('maintable') . "%'
			) AND
			`Layer_ID` != " . $this->get($this->identifier) . "
		");
		$this->data = $data;
		return (count($layers) > 0);
	}

	function delete() {
		#echo '<br>Class Layer Method delete';
		$ret = parent::delete();
		if (MYSQLVERSION > 412) {
			parent::reset_auto_increment();
		}
		return $ret;
	}

	function get_subform_layers() {
		include_once(CLASSPATH . 'LayerAttribute.php');
		$subform_layer_ids = array_unique(
			array_map(
				function($attribute) {
					return explode(',', $attribute->get('options'))[0];
				},
				LayerAttribute::find($this->gui, "Layer_ID = " . $this->get('Layer_ID') . " AND form_element_type LIKE 'SubForm%PK'")
			)
		);
		if (count($subform_layer_ids) > 0) {
			return Layer::find(
				$this->gui,
				"Layer_ID IN (" . implode(', ', $subform_layer_ids) . ')'
			);
		}
		else {
			return array();
		}
	}

	function get_parentform_layers() {
		include_once(CLASSPATH . 'LayerAttribute.php');
		$parentform_layer_ids = array_unique(
			array_map(
				function($attribute) {
					return $attribute->get('layer_id');
				},
				LayerAttribute::find($this->gui, "Layer_ID != " . $this->get('Layer_ID') . " AND options LIKE '" . $this->get('Layer_ID') . ",%' AND form_element_type LIKE 'SubForm%PK'")
			)
		);
		if (count($parentform_layer_ids) > 0) {
			return Layer::find(
				$this->gui,
				"Layer_ID IN (" . implode(', ', $parentform_layer_ids) . ')'
			);
		}
		else {
			return array();
		}
	}

	function get_edit_link_list($layers, $anchor = '') {
		return '<ul><li>' . implode(
			'</li><li>',
			array_map(
				function($layer) use ($anchor) {
					return '<a title="Layereditor anzeigen" href="index.php?go=Layereditor&selected_layer_id=' . $layer->get('Layer_ID') . '#' . $anchor . '" target="_blank">' . $layer->get('Name') . ' (ID: ' . $layer->get('Layer_ID') . ')</a>';
				},
				$layers
			)
		) . '</li></ul>';
	}

	function get_group_name() {
		include_once(CLASSPATH . 'LayerGroup.php');
		$group = LayerGroup::find_by_id($this->gui, $this->get('Gruppe'));
		if ($group->get('obergruppe') != '') {
			$obergroup = LayerGroup::find_by_id($this->gui, $group->get('obergruppe'));
			return $obergroup->get('Gruppenname') . '|' . $group->get('Gruppenname');
		}
		else {
			return $group->get('Gruppenname');
		}
	}

	function get_baselayers_def($stelle_id) {
		$this->debug->show('<p>Layer->get_baselayers_def for stelle_id: ' . $stelle_id, MyObject::$write_debug);
		#echo '<p>get_baselayer_def for Layer: ' . $this->get('Name');

		include_once(CLASSPATH . 'LayerClass.php');
		include_once(CLASSPATH . 'LayerAttribute.php');

		$layerAttributes = new stdClass();
		foreach (LayerAttribute::find_visible($this->gui, $stelle_id, $this->get('Layer_ID')) AS $attr) {
			$key = $attr->get('name');
			$value = ($attr->get('alias') == '' ? $attr->get('name') : $attr->get('alias'));
			$layerAttributes->$key = $value;
		}
		$classes = LayerClass::find($this->gui, 'Layer_ID = ' . $this->get('Layer_ID'));
		$legendgraphic = URL . APPLVERSION . (count($classes) > 0 ? $classes[0]->get('legendgraphic') : 'graphics/leer.gif');
		$layerdef = (Object) array(
			'img' => $this->get('icon'),
			'label' => ($this->get('alias') != '' ? $this->get('alias') : $this->get('Name')),
			'options' => $this->get_baselayer_options(),
			'shortLabel' => $this->get('Name'),
			'img' => $legendgraphic,
			'url' => $this->get_baselayer_url()
		);
		return $layerdef;
	}

	function get_baselayer_options() {
		if (strpos($this->get('Data'), '{') === 0) {
			$data = json_decode($this->get('Data'));
			$data->options->attribution = $this->get('datasource');
			return $data->options;
		}
		else {
			return (Object) array(
				'attribution' => $this->get('datasource')
			);
		}
	}

	function get_baselayer_url() {
		if ($this->get('Data') == '') {
			return $this->get('connection');
		}
		if (strpos($this->get('Data'), '{') === 0) {
			$data = json_decode($this->get('Data'));
			return $data->url;
		}
		else {
			return $this->get('Data');
		}
	}

	function get_overlays_def($stelle_id) {
		$this->debug->show('<p>Layer->get_overlays_def for stelle_id: ' . $stelle_id, MyObject::$write_debug);
		#echo '<p>get_overlays_def for Layer: ' . $this->get('Name');
		include_once(CLASSPATH . 'LayerClass.php');
		include_once(CLASSPATH . 'LayerAttribute.php');

		$layerAttributes = new stdClass();
		foreach (LayerAttribute::find_visible($this->gui, $stelle_id, $this->get('Layer_ID')) AS $attr) {
			$key = $attr->get('name');
			$value = ($attr->get('alias') == '' ? $attr->get('name') : $attr->get('alias'));
			$layerAttributes->$key = $value;
		}

		switch ($this->get('connectiontype')) {
			case 6 : { # WFS-Layer werden exportiert wie PostGIS Layer
				$type = 'GeoJSON';
				$url = URL . APPLVERSION . 'index.php';
				$params = (Object) array(
					'gast' =>(int)$stelle_id,
					'go' => 'Daten_Export_Exportieren',
					'Stelle_ID' => (int)$stelle_id,
					'selected_layer_id' => (int)$this->get('Layer_ID'),
					'export_format' =>  'GeoJSON',
					'browserwidth' => 800,
					'browserheight' => 600,
					'epsg' => 4326,
					'all' => 1
				);
				$options = (Object) array(
					'transparent' => true,
					'attribution' => $this->get('dataowner_name')
				);
			} break;
			case 7 : { # WMS-Layer
				$type = 'WMS';
				$url = explode('?', $this->get('connection'))[0];
				$params = '';
				$options = (Object) array(
					'crs' => 'EPSG4326',
					'version' => get_first_word_after($this->get('connection'), 'version=', ' ', '&'),
					'layers' => get_first_word_after($this->get('connection'), 'layers=', ' ', '&'),
					'format' => 'image/png',
					'transparent' => true,
					'attribution' => $this->get('dataowner_name'),
					'opacity' => $this->opacity / 100
				);
			} break;
			case 9 : { # PostGIS-Layer
				$type = 'GeoJSON';
				$url = URL . APPLVERSION . 'index.php';
				$params = (Object) array(
					'gast' => (int)$stelle_id,
					'go' => 'Daten_Export_Exportieren',
					'Stelle_ID' => (int)$stelle_id,
					'selected_layer_id' => (int)$this->get('Layer_ID'),
					'export_format' =>  'GeoJSON',
					'browserwidth' => 800,
					'browserheight' => 600,
					'epsg' => 4326,
					'all' => 1
				);
				$options = (Object) array(
					'transparent' => true,
					'attribution' => $this->get('dataowner_name')
				);
			} break;
			default : { # currently same as PostGIS-Layer
				$type = 'GeoJSON';
				$url = URL . APPLVERSION . 'index.php';
				$params = (Object) array(
					'gast' => (int)$stelle_id,
					'go' => 'Daten_Export_Exportieren',
					'Stelle_ID' => (int)$stelle_id,
					'selected_layer_id' => (int)$this->get('Layer_ID'),
					'export_format' =>  'GeoJSON',
					'browserwidth' => 800,
					'browserheight' => 600,
					'epsg' => 4326,
					'all' => 1
				);
				$options = (Object) array(
					'transparent' => true,
					'attribution' => $this->get('dataowner_name')
				);
			}
		}

		$classitem = $this->get('classitem');
		$datentyp = $this->get('Datentyp');

		$layerdef = (Object) array(
			'thema' => $this->get_group_name(),
			'label' => ($this->get('alias') != '' ? $this->get('alias') : $this->get('Name')),
			'abstract' => $this->get('kurzbeschreibung'),
			'contactOrganisation' => $this->get('datasource'),
			'contactPersonName' => $this->get('dataowner_name'),
			'contactEMail' => $this->get('dataowner_email'),
			'contactPhon' => $this->get('dataowner_tel'),
			'actuality' => $this->get('uptodateness'),
			'actualityCircle' => $this->get('updatecycle'),
			'type' => $type,
			'geomType' => array('Point', 'Linestring', 'Polygon', 'Raster', 'Annotation', 'Query', 'Circle', 'Tileindex', 'Chart')[$this->get('Datentyp')],
			'backgroundColor' => '#c1ffd8',
			'infoAttribute' => ($this->get('labelitem') != '' ? $this->get('labelitem') : $this->get('oid')),
			'url' => $url,
			'params' => $params,
			'options' => $options,
			'classes' => array_map(
				function($class) use ($classitem, $datentyp) {
					return $class->get_layerdef($classitem, $datentyp);
				},
				LayerClass::find($this->gui, 'Layer_ID = ' . $this->get('Layer_ID'), 'legendorder')
			),
#			'icon' => (Object) array(
#				'iconUrl' => 'images/Haus.svg',
#				'iconSize' => array(30, 30),
#				'iconAnchor' => array(7, 0),
#				'popupAnchor' => array(0, 0)
#			),
			'hideEmptyLayerAttributes' => true,
			'layerAttributes' => $layerAttributes
		);

		if ($this->get('processing') != '') {
			$processing = explode(';', $this->get('processing'));
			if (count($processing) > 0) {
				$layerdef->processing = (Object) array();
				foreach ($processing AS $process) {
					$parts = explode('=', $process);
					switch ($parts[0]) {
						case ('CHART_TYPE') : {
							$layerdef->processing->chart_type = $parts[1];
						} break;
						case ('CHART_SIZE') : {
							$layerdef->processing->style = (Object) array(
								'radius' => $parts[1],
								'fillOpacity' => 0.6,
								"strokeOpacity" => 0.2,
								"strokeWeight" => 3
							);
						} break;
					}
				}
			}
		}
		if ($this->minScale != '') {
			$layerdef->minScale = (int)$this->minScale;
		}
		if ($this->maxScale != '') {
			$layerdef->maxScale = (int)$this->maxScale;
		}
		return $layerdef;
	}
}
?>
