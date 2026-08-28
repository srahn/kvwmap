<?php
include_once(CLASSPATH . 'PgObject.php');
include_once(CLASSPATH . 'PgAttribute.php');
include_once(CLASSPATH . 'Layer.php');
class LayerGroup extends PgObject {

	static $write_debug = false;

	var $layers = [];

	function __construct($gui) {
		$this->has_many = array(
			"layers" => array(
				"alias" => 'Layer',
				"table" => 'layer',
				"vorschau" => 'name',
				"pk" => 'layer_id',
				"fk" => 'gruppe'
			)
		);
		parent::__construct($gui, 'kvwmap', 'u_groups');

		$this->validations = array(
			array(
				'attribute' => 'gruppenname',
				'condition' => 'not_null',
				'description' => 'Es muss ein Gruppenname angegeben werden.',
				'options' => null
			),
			array(
				'attribute' => 'selectable_for_shared_layers',
				'condition' => 'not_null',
				'description' => 'Es muss angegeben werden ob die Layergruppe für das Teilen von importierten Layern ausgewählt werden darf.',
				'options' => null
			),
			array(
				'attribute' => 'checkbox',
				'condition' => 'not_null',
				'description' => 'Es muss angegeben werden ob die Layergruppe in der Legende eine Checkbox zum Ein- und Ausschalten von Untergruppen haben soll.',
				'options' => null
			)
		);
	}

	public static	function find_by_id($gui, $id) {
		$obj = new LayerGroup($gui);
		$group = $obj->find_by($obj->identifier, $id);
		$group->layers = $group->get_Layer();
		return $group;
	}

	public static	function find($gui, $where, $order) {
		$group = new LayerGroup($gui);
		return array_map(
			function ($group) {
				$group->layers = $group->get_Layer();
				return $group;
			},
			$group->find_where($where, $order)
		);
	}

	function get_Layer() {
		$layer = new Layer($this->gui);
		$layers = $layer->find_where(
			'gruppe = ' . $this->get('id'),
			'drawingorder'
		);
		return $layers;
	}

	function get_layers_recursive($group_id = null) {
		$group_id = $group_id ?: $this->get_id();
		$sql = "
			WITH RECURSIVE cte (group_id) AS (
				SELECT
					" . $this->get_id() . "
				UNION ALL
				SELECT 
					u_groups.id
				FROM 
					cte,
					kvwmap.u_groups
				WHERE 
					cte.group_id = u_groups.obergruppe AND 
					obergruppe IS NOT NULL
			)
			SELECT DISTINCT 
				layer.*
			FROM
				cte,
				kvwmap.layer
			WHERE
				gruppe = cte.group_id
		";
		$query = $this->execSQL($sql);
		$pg_last_error = pg_last_error();
		if ($pg_last_error != '') {
			return array(
				'success' => false,
				'msg' => $pg_last_error
			);
		}
		return array(
			'success' => true,
			'layers' => pg_fetch_all($query, PGSQL_ASSOC)
		);
	}

	/**
	 * Function determine the layers that have features when the filter is applied on pfad or data query pending on its datatype.
	 */
	function get_layers_with_content($layers, $filter = '') {
		$filter = $filter ?: 'true';
		$layers_with_content = array();
		foreach ($layers AS $layer) {
			$sql = "";
			if ($layer['data'] != '' AND in_array($layer['datentyp'], array(0, 1, 2))) {
				if ($layer['geom_column'] != '') {
					return array(
						'success' => false,
						'msg' => 'In der Layerdefinition des Geometrie-Layers ' . $layer['name'] . ' (id: ' . $layer['layer_id'] . ') ist keine geom_column angegeben.'
					);
				}
				$data_query = replace_params_rolle(
					$layer['data'],
					['duplicate_criterion' => $layer['duplicate_criterion']]
				);
				$sql = "
					SELECT
						count(*) AS num_total,
						sum(CASE WHEN LOWER(ST_GeometryType(" . $layer['geom_column'] . ")) LIKE '%point%' THEN 1 ELSE 0 END) AS num_points,
						sum(CASE WHEN LOWER(ST_GeometryType(" . $layer['geom_column'] . ")) LIKE '%linestring%' THEN 1 ELSE 0 END) AS num_lines,
						sum(CASE WHEN LOWER(ST_GeometryType(" . $layer['geom_column'] . ")) LIKE '%polygon%' THEN 1 ELSE 0 END) AS num_polygons
					FROM
						(" . $data_query . ") AS layer_data_query
					WHERE
						" . ($filter ?: 'true') . "
				";
			}
			if ($layer['datentyp'] == 5) {
				// Query-Layer
				$pfad_query = replace_params_rolle(
					$layer['pfad'],
					['duplicate_criterion' => $layer['duplicate_criterion']]
				);
				$sql = "
					SELECT
						count(*) AS num_total,
						0 AS num_points,
						0 AS num_lines,
						0 AS num_polygons
					FROM
						(" . $pfad_query . ") AS layer_data_query
					WHERE
						" . ($filter ?: 'true') . "
				";
			}
			if ($sql != '') {
				// echo '<br>' . $sql;
				$result = $this->database->execSQL($sql, 4, 0, true);
				if (! $result['success']) {
					return $result;
				}
				$content = pg_fetch_assoc($result[1]);
				if (
					($layer['datentyp'] == 0 AND $content['num_points'] > 0) OR
					($layer['datentyp'] == 1 AND $content['num_lines'] > 0) OR
					($layer['datentyp'] == 2 AND $content['num_polygons'] > 0) OR
					($layer['datentyp'] == 5 AND $content['num_total'] > 0)
				) {
					$layers_with_content[$layer['name']] = $layer;
				}
			}
		}

		return array(
			'success' => true,
			'layers_with_content' => $layers_with_content
		);
	}

	function get_next_order($obergruppe) {
		return ($this->find_by_sql(array(
			'select' => 'max(order) AS max_order',
			'from' => "kvwmap.u_groups",
			'where' => "obergruppe = " . $obergruppe
		))[0])->get('max_order') + 100;
	}

	public static function find_top_parents($gui, $stelle_id) {
		#echo '<br>find_top_parents for stelle_id: ' . $stelle_id;
		$group = new LayerGroup($gui);
		return $group->find_by_sql(array(
			'select' => 'id, gruppenname, icon, "order"',
			'from' => "(
				SELECT DISTINCT
					COALESCE(g3.id, g2.id, g1.id) AS group_id,
					ul.Stelle_id
				FROM
					kvwmap.used_layer ul JOIN
					kvwmap.layer l ON ul.layer_id = l.layer_id JOIN
					kvwmap.u_groups g1 ON COALESCE(ul.group_id, l.gruppe) = g1.id LEFT JOIN
					kvwmap.u_groups g2 ON g1.obergruppe = g2.id LEFT JOIN
					kvwmap.u_groups g3 ON g2.obergruppe = g3.id
				WHERE
					l.selectiontype != 'radio'
			) AS sub JOIN
			kvwmap.u_groups g ON sub.group_id = g.id",
			'where' => 'sub.stelle_id = ' . $stelle_id,
			'order' => '"order"'
		));
	}

	public function find_sub_groups($stelle_id = null) {
		if ($stelle_id == null) {
			return $this->find_by_sql(array(
				'select' => "child.id, child.gruppenname, child.icon, child.order",
				'from' => "kvwmap.u_groups parent JOIN kvwmap.u_groups child ON parent.id = child.obergruppe",
				'where' => "parent.id = " . $this->get('id'),
				'order' => 'child."order"'
			));
		}
		else {
			// z.B. SELECT DISTINCT child.id, child.gruppenname, child.icon, child.order FROM u_groups parent JOIN u_groups child ON parent.id = child.obergruppe JOIN layer l ON child.id = l.Gruppe JOIN used_layer ul ON l.layer_id = ul.layer_id WHERE parent.id = 7 AND ul.stelle_id = 7 ORDER BY child.order 

			return $this->find_by_sql(array(
				'select' => 'DISTINCT child.id, child.gruppenname, child.icon, child."order"',
				'from' => "kvwmap.u_groups parent JOIN kvwmap.u_groups child ON parent.id = child.obergruppe" . ($stelle_id != null ? " JOIN kvwmap.layer l ON child.id = l.gruppe JOIN kvwmap.used_layer ul ON l.layer_id = ul.layer_id" : ''),
				'where' => "parent.id = " . $this->get('id') . ($stelle_id != null ? " AND ul.stelle_id =" . $stelle_id : ''),
				'order' => 'child."order"'
			));
		}
	}

	function get_layerdef($thema, $stelle_id = null) {
		$thema = ($thema != '' ? $thema . '|' : '') . $this->get('gruppenname');
		#echo '<br>thema: ' . $thema;
		$layerdef = (Object) array(
			'thema' => $thema,
			'icon' => $this->get('icon')
		);
		$sub_groups = $this->find_sub_groups($stelle_id);
		if (count($sub_groups) > 0) {
			#echo '<br>loop through sub_groups with thema: ' . $thema . ' in stelle_id: ' . $stelle_id;
			$layerdef->themes = array_map(
				function($sub_group) use ($thema, $stelle_id) {
					#echo '<br>call get_layerdef of sub_group id: ' . $sub_group->get('id') . ' with thema: ' . $thema . ' in stelle_id: ' . $stelle_id;
					return $sub_group->get_layerdef($thema, $stelle_id);
				},
				$sub_groups
			);
		}
		return $layerdef;
	}

	public static function merge_groups(...$arrays) {
		$result = [];
		foreach ($arrays as $array) {
			foreach ($array as $id => $data) {
				if (!isset($result[$id])) {
					$result[$id] = $data;
					continue;
				}

				$result[$id]['untergruppen'] = array_merge(
					$result[$id]['untergruppen'] ?? [],
					$data['untergruppen'] ?? []
				);
			}
		}
		return $result;
	}
}
?>
