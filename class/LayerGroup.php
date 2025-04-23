<?php
include_once(CLASSPATH . 'PgObject.php');
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
				'attribute' => 'Gruppenname',
				'condition' => 'not_null',
				'description' => 'Es muss ein Gruppenname angegeben werden.',
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
			'Gruppe = ' . $this->get('id'),
			'drawingorder'
		);
		return $layers;
	}

	public static function find_top_parents($gui, $stelle_id) {
		#echo '<br>find_top_parents for stelle_id: ' . $stelle_id;
		$group = new LayerGroup($gui);
		return $group->find_by_sql(array(
			'select' => 'id, Gruppenname, icon, `order`',
			'from' => "(
				SELECT DISTINCT
					CASE WHEN g3.id IS NULL THEN CASE WHEN g2.id IS NULL THEN g1.id ELSE g2.id END ELSE g3.id END AS group_id,
					ul.Stelle_id
				FROM
					used_layer ul JOIN
					layer l ON ul.Layer_ID = l.Layer_ID JOIN
					u_groups g1 ON l.Gruppe = g1.id LEFT JOIN
					u_groups g2 ON g1.obergruppe = g2.id LEFT JOIN
					u_groups g3 ON g2.obergruppe = g3.id
				WHERE
					l.selectiontype != 'radio'
			) AS sub JOIN
			u_groups g ON sub.group_id = g.id",
			'where' => 'sub.Stelle_ID = ' . $stelle_id,
			'order' => '`order`'
		));
	}

	public function find_sub_groups($stelle_id = null) {
		if ($stelle_id == null) {
			return $this->find_by_sql(array(
				'select' => "child.`id`, child.`Gruppenname`, child.`icon`, child.`order`",
				'from' => "`u_groups` parent JOIN `u_groups` child ON parent.`id` = child.`obergruppe`",
				'where' => "parent.`id` = " . $this->get('id'),
				'order' => "child.`order`"
			));
		}
		else {
			// z.B. SELECT DISTINCT child.`id`, child.`Gruppenname`, child.`icon`, child.`order` FROM `u_groups` parent JOIN `u_groups` child ON parent.`id` = child.`obergruppe` JOIN `layer` l ON child.`id` = l.`Gruppe` JOIN `used_layer` ul ON l.`Layer_ID` = ul.`Layer_ID` WHERE parent.`id` = 7 AND ul.Stelle_ID = 7 ORDER BY child.`order` 

			return $this->find_by_sql(array(
				'select' => "DISTINCT child.`id`, child.`Gruppenname`, child.`icon`, child.`order`",
				'from' => "`u_groups` parent JOIN `u_groups` child ON parent.`id` = child.`obergruppe`" . ($stelle_id != null ? " JOIN `layer` l ON child.`id` = l.`Gruppe` JOIN `used_layer` ul ON l.`Layer_ID` = ul.`Layer_ID`" : ''),
				'where' => "parent.`id` = " . $this->get('id') . ($stelle_id != null ? " AND ul.Stelle_ID =" . $stelle_id : ''),
				'order' => "child.`order`"
			));
		}
	}

	function get_layerdef($thema, $stelle_id = null) {
		$thema = ($thema != '' ? $thema . '|' : '') . $this->get('Gruppenname');
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
}
?>
