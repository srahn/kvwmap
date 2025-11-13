<?php
include_once(CLASSPATH . 'MyObject.php');
class Role extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct(
			$gui,
			'rolle',
			array(
				array(
					'key' => 'user_id',
					'type' => 'integer'
				),
				array(
					'key' => 'stelle_id',
					'type' => 'integer'
				)
			),
			'array'
		);
	}

	public static	function find($gui, $where, $order = '', $find_direction = '') {
		$role = new Role($gui);
		$roles = $role->find_where($where, $order, $find_direction);
		foreach ($roles AS $role) {
			$role->user = Nutzer::find_by_id($gui, $role->get('user_id'));
			$stelle = new MyObject($gui, 'stelle', 'ID');
			$role->stelle = $stelle->find_by('ID', $role->get('stelle_id'));
		}
		return $roles;
	}

	public static	function find_by_id($gui, $user_id, $stelle_id) {
		$role_obj = new Role($gui);
		$role = $role_obj->find_by_ids(array(
			'user_id' => $user_id,
			'stelle_id' => $stelle_id
		));
		$role->user = Nutzer::find_by_id($gui, $user_id);
		$stelle = new MyObject($gui, 'stelle', 'ID');
		$role->stelle = $stelle->find_by('ID', $stelle_id);
		return $role;
	}

	/**
	 * Insert a rolle for a user by copying the settings of a default user of this stelle or if given the parent_stelle.
	 * Ignore if role already exists.
	 * @param int $user_id
	 * @param int $stelle_id
	 * @param int $default_user_id
	 * @param int $parent_stelle_id
	 * @return int 1 | 0 Wenn success 1 else 0
	 */
	public function set_rolle_from_default_user_or_parent_stelle($user_id, $stelle_id, $default_user_id, $parent_stelle_id) {
		$sql = "
			INSERT IGNORE INTO `rolle` (
				`user_id`,
				`stelle_id`,
				`nImageWidth`, `nImageHeight`,
				`auto_map_resize`,
				`minx`, `miny`, `maxx`, `maxy`,
				`nZoomFactor`,
				`selectedButton`,
				`epsg_code`,
				`epsg_code2`,
				`coordtype`,
				`active_frame`,
				`gui`,
				`language`,
				`hidemenue`,
				`hidelegend`,
				`tooltipquery`,
				`buttons`,
				`scrollposition`,
				`result_color`,
				`result_hatching`,
				`result_transparency`,
				`always_draw`,
				`runningcoords`,
				`showmapfunctions`,
				`showlayeroptions`,
				`showrollenfilter`,
				`singlequery`,
				`querymode`,
				`geom_edit_first`,
				`dataset_operations_position`,
				`immer_weiter_erfassen`,
				`upload_only_file_metadata`,
				`overlayx`, `overlayy`,
				`instant_reload`,
				`menu_auto_close`,
				`layer_params`,
				`visually_impaired`,
				`font_size_factor`,
				`menue_buttons`,
				`redline_text_color`,
				`redline_font_family`,
				`redline_font_size`,
				`redline_font_weight`
			)
			SELECT " .
				$user_id . ", " .
				$stelle_id . ",
				`nImageWidth`, `nImageHeight`,
				`auto_map_resize`,
				`minx`, `miny`, `maxx`, `maxy`,
				`nZoomFactor`,
				`selectedButton`,
				`epsg_code`,
				`epsg_code2`,
				`coordtype`,
				`active_frame`,
				`gui`,
				`language`,
				`hidemenue`,
				`hidelegend`,
				`tooltipquery`,
				`buttons`,
				`scrollposition`,
				`result_color`,
				`result_hatching`,
				`result_transparency`,
				`always_draw`,
				`runningcoords`,
				`showmapfunctions`,
				`showlayeroptions`,
				`showrollenfilter`,
				`singlequery`,
				`querymode`,
				`geom_edit_first`,
				`dataset_operations_position`,
				`immer_weiter_erfassen`,
				`upload_only_file_metadata`,
				`overlayx`, `overlayy`,
				`instant_reload`,
				`menu_auto_close`,
				`layer_params`,
				`visually_impaired`,
				`font_size_factor`,
				`menue_buttons`,
				`redline_text_color`,
				`redline_font_family`,
				`redline_font_size`,
				`redline_font_weight`
			FROM
				`rolle`
			WHERE
				`user_id` = " . $default_user_id . " AND
				`stelle_id` = " . ($parent_stelle_id ?? $stelle_id) . "
		";
		$ret = $this->database->execSQL($sql);
		if (!$ret['success']) {
			echo '<br>Fehler in set_rolle_from_default_user_or_parent_stelle: ' . $ret['err_msg'];
			return array(
				'success' => false,
				'msg' => $ret['err_msg'],
				'sql' => $sql
			);
		}
		return array(
			'success' => true,
			'msg' => 'Rolle erfolgriech eingetragen.',
			'sql' => $sql
		);
	}

	/**
	 * Insert a rolle for a user by copying the settings of the stelle.
	 * Ignore if role already exists.
	 * @param int $user_id
	 * @param int $stelle_id
	 * @return int 1 | 0 Wenn success 1 else 0
	 */
	function set_rolle_from_stelle_default($user_id, $stelle_id) {
		# Default - Rolleneinstellungen verwenden
		$sql = "
			INSERT IGNORE INTO rolle (user_id, stelle_id, epsg_code, minx, miny, maxx, maxy)
			SELECT " .
				$user_id . ",
				ID,
				epsg_code,
				minxmax,
				minymax,
				maxxmax,
				maxymax
			FROM
				stelle
			WHERE
				ID = " . $stelle_id . "
		";
		$ret = $this->database->execSQL($sql);
		if (!$ret['success']) {
			echo '<br>Fehler in set_rolle_from_default_user_or_parent_stelle: ' . $ret['err_msg'];
			return array(
				'success' => false,
				'msg' => $ret['err_msg'],
				'sql' => $sql
			);
		}
		return array(
			'success' => true,
			'msg' => 'Rolle erfolgriech eingetragen.',
			'sql' => $sql
		);
	}

	/**
	 * Function rectify layer parameter of this rolle object with the given $layer_params.
	 * If a parameter value is not available for this rolle in the options of the LayerParam,
	 * the first value of the options will be set.
	 * The function changes the layer_params attribut of this rolle object in the database and
	 * set it in static var rolle::$layer_params
	 * @param MyObject The MyObject of the rolle.
	 * @return Array with success and msg
	 */
	function rectify_layer_params($layer_params) {
		include_once(CLASSPATH . 'LayerParam.php');
		$old_layer_params = (array)json_decode('{' . $layer_params . '}');
		$new_layer_params = array();
		foreach (array_keys($old_layer_params) AS $key) {
			$layer_param = LayerParam::find_by_key($this->gui, $key);
			if (strpos($layer_param->get('options_sql'), '$') === false) {	# Layer-Parameter mit dynamischem SQL nicht anpassen
				$result = $layer_param->get_options($this->get('user_id'), $this->get('stelle_id'));
				if (!$result['success']) {
					return $result;
				}
				if (!in_array(
					$old_layer_params[$key],
					array_map(
						function($option) {
							return $option['value'];
						},
						$result['options']
					)
				)) {
					$old_layer_params[$key] = $result['options']['value'][0];
				};
			}
		}
		foreach ($old_layer_params AS $param_key => $value) {
			$new_layer_params[] = '"' . $param_key . '":"' . $value . '"';
		}
		$this->update(array('layer_params' => implode(',', $new_layer_params)), false);
		return array(
			'success' => true,
			'msg' => 'Layerparameter erfolgreich für Rolle angepasst.'
		);
	}

	function get_rolle_id() {
		return $this->get('user_id') . '_' . $this->get('stelle_id');
	}

}
?>
