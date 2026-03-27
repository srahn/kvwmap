<?php
class LayerAttribute extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'layer_attributes');
	}

	public static	function find($gui, $where) {
		$layer_attribute = new LayerAttribute($gui);
		return $layer_attribute->find_where($where);
	}

	public static	function find_visible($gui, $stelle_id, $layer_id) {
		$myObj = new MyObject($gui, 'layer_attributes');
		return $myObj->find_by_sql(array(
			'select' => 'la.name, la.alias',
			'from' => '`layer_attributes` la JOIN `layer_attributes2stelle` AS ls ON la.layer_id = ls.layer_id AND la.name = ls.attributename',
			'where' => 'la.visible = 1 AND ls.stelle_id = ' . $stelle_id . ' AND la.layer_id = ' . $layer_id,
			'order' => 'la.`order`'
		));
	}

	function copy($layer_id) {
		$this->debug->show('Copy LayerAttribute mit layer_id: ' . $layer_id, LayerAttribute::$write_debug);
		$new_attribute = clone $this;
		unset($new_attribute->data['layer_id']);
		$new_attribute->set('layer_id', $layer_id);
		$new_attribute->create();
	}

	function get_datatype_id() {
		$datatype_id = null;
		if ($this->is_datatype_attribute()) {
			return (int)ltrim($this->get('type'), '_');
		}
		return $datatype_id;
	}

	function get_datatype() {
		include_once(CLASSPATH . 'DataType.php');
		$datatype = null;
		if ($this->is_datatype_attribute()) {
			$datatype = DataType::find_by_id($this->gui, $this->get_datatype_id());
		}
		return $datatype;
	}

	function get_datatype_attributes() {
		include_once(CLASSPATH . 'DataTypeAttribute.php');
		$datatype_attributes = DataTypeAttribute::find($this->gui, "layer_id = " . $this->get('layer_id') . " AND datatype_id = " . $this->get_datatype_id());
		return $datatype_attributes;
	}

	/**
	 *	function return the name of the first attribut in $attributes array that has PRIMARY KEY as constraint
	 *	@param array $attributes An array of attributes as returned by function mapdb->read_layer_attributes
	 *	@return string The name of the attribute
	 */
	function get_oid($attributes) {
		if (
			array_key_exists('constraints', $attributes) AND
			array_key_exists('name', $attributes)
		) {
			$key = array_search('PRIMARY KEY', $attributes['constraints']);
			if (
				$key !== false AND
				array_key_exists($key, $attributes['name'])
			) {
				return $attributes['name'][$key];
			}
		}
		return '';
	}

	// function get_generic_select($layer, $attr) {
	// 	# Wenn $attr['is_array'] true und keine Elemente enthalten sind nicht {} ausgeben sonder NULL
	// 	if ($attr['is_array'] == 't') {
	// 		# Das Attribut ist vom Typ array und wird wenn das Array leer ist als NULL sonst Kommasepariert ausgegeben.
	// 		$select = "CASE WHEN array_length(" . $layer->get_table_alias() . '.' . $attr['att_name'] . ", 1) = 0 THEN NULL ELSE array_to_string(" . $layer->get_table_alias() . '.' . $attr['att_name'] . ", ',', '') END AS " . $attr['att_name'];
	// 	}
	// 	else {
	// 		$select = $layer->get_table_alias() . '.' . $attr['att_name'];
	// 	}
	// 	return array(
	// 		'select' => $select
	// 	);
	// }

	function get_generic_select($table_alias, $attr) {
		# Wenn $attr['is_array'] true und keine Elemente enthalten sind nicht {} ausgeben sonder NULL
		$selects = array(
			array(
				'att_name' => $attr['att_name'],
				'sql' => ($attr['is_array'] == 't' ? "CASE WHEN array_length(" . $table_alias . '.' . $attr['att_name'] . ", 1) = 0 THEN NULL ELSE array_to_string(" . $table_alias . '.' . $attr['att_name'] . ", ',', '') END AS " . $attr['att_name'] : $table_alias . '.' . $attr['att_name'])
			)
		);
		return $selects;
	}

	function get_options($settings, $type) {
		switch ($type) {
			case 'SubFormFK':
				$options = $this->get_SubFormFK_options($settings);
				break;
			// case 'checkbox':
			// 	$options = $this->get_checkbox_options($settings);
			// 	break;
			// case 'radio':
			// 	$options = $this->get_radio_options($settings);
			// 	break;
			default:
				$options = array();
				break;
		}
		return $options;
	}

	function get_SubFormFK_options($settings) { // get_options
		$options = array();
		if (strpos($settings, '{') === 0) {
			$json = json_decode($settings, true);
			$options['parent_layer_id'] = $json['ref_layer_id'];
			$options['fk_name'] = $json['ref_keys'][0]['fkey'];
			$options['pk_name'] = $json['ref_keys'][0]['pkey'];
			$options['window_option'] = $json['window_type'] ?? '';
			$options['ref_constraint'] = $json['ref_constraint'] ?? '';
		}
		else {
			$semicolon_parts = explode(';', $settings);
			$comma_parts = explode(',', $semicolon_parts[0]);
			$colon_parts = explode(':', $comma_parts[1]);
			$options['parent_layer_id'] = $comma_parts[0];
			$options['fk_name'] = $colon_parts[0];
			$options['pk_name'] = $colon_parts[1];
			$options['window_option'] = $semicolon_parts[1] ?? '';
		}
		return $options;
	}

	/**
	 * Returns the WKB geometry for the given layerset and form variables.
	 *
	 * @param object $layerdb The database for the layer.
	 * @param array $layerset The layerset containing EPSG code and OID.
	 * @param string $epsg_code The EPSG code for the geometry.
	 * @param array $formvars The form variables containing geometry type, coordinates and dimension.
	 * @return array An array containing success status and WKB geometry.
	 */
	function get_wkb_geometry($layerdb, $layerset, $epsg_code, $formvars) {
		if ($formvars['geomtype'] == 'POINT') {
			include_once (CLASSPATH . 'pointeditor.php');
			$pointeditor = new pointeditor($layerdb, $layerset['epsg_code'], $epsg_code, $layerset['oid']);
			return $pointeditor->get_wkb_geometry(array(
				'loc_x' => $formvars['loc_x'],
				'loc_y' => $formvars['loc_y'],
				'dimension' => $formvars['dimension']
			));
		}
		else {
			include_once (CLASSPATH . 'multigeomeditor.php');
			$multigeomeditor = new multigeomeditor($layerdb, $layerset['epsg_code'], $epsg_code, $layerset['oid']);
			return $multigeomeditor->get_wkb_geometry(array(
				'geomtype' => $formvars['geomtype'],
				'geom' => $formvars['newpathwkt']
			));
		}
	}

	function is_datatype_attribute() {
		return is_numeric(ltrim($this->get('type'), '_'));
	}

	function is_array_type() {
		return substr(trim($this->get('type')), 0, 1) === '_';
	}

	function is_document_attribute() {
		return $this->get('form_element_type') == 'Dokument';
	}
}
?>