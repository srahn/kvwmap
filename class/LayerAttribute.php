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
}
?>