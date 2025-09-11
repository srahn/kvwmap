<?php
include_once(CLASSPATH . 'MyObject.php');
include_once(CLASSPATH . 'Layer.php');
class LayerGroup extends MyObject {

	static $write_debug = false;

	var $layers = [];

	function __construct($gui) {
		$this->has_many = array(
			"layers" => array(
				"alias" => 'Layer',
				"table" => 'layer',
				"vorschau" => 'Name',
				"pk" => 'Layer_ID',
				"fk" => 'Gruppe'
			)
		);
		parent::__construct($gui, 'u_groups');

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
			$group->find_where($where, $order, 'ASC')
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

	function get_next_order($obergruppe) {
		return ($this->find_by_sql(array(
			'select' => 'max(`order`) AS max_order',
			'from' => "`u_groups`",
			'where' => "`obergruppe` = " . $obergruppe
		))[0])->get('max_order') + 100;
	}

	function get_aktiv_status($stelle_id, $user_id, $group_id) {
		$result = $this->find_by_sql(array(
			'select' => "CASE WHEN sum(CASE WHEN r2l.aktivStatus = '1' THEN 1 ELSE 0 END) = 0 THEN 0 WHEN count(l.Layer_ID) > sum(CASE WHEN r2l.aktivStatus = '1' THEN 1 ELSE 0 END) THEN 1 ELSE 2 END AS aktiv_status",
			'from' => "`u_rolle2used_layer` r2l JOIN `layer` l ON r2l.layer_id = l.Layer_ID",
			'where' => "r2l.stelle_id = $stelle_id AND r2l.user_id = $user_id AND l.Gruppe = $group_id"
		));
		return $result[0]->get('aktiv_status');
	}

	public static function find_top_parents($gui, $stelle_id) {
		#echo '<br>find_top_parents for stelle_id: ' . $stelle_id;
		$group = new LayerGroup($gui);
		return $group->find_by_sql(array(
			'select' => 'id, Gruppenname, icon, `order`',
			'from' => "(
				SELECT DISTINCT
					COALESCE(g3.id, g2.id, g1.id) AS group_id,
					ul.Stelle_id
				FROM
					used_layer ul JOIN
					layer l ON ul.Layer_ID = l.Layer_ID JOIN
					u_groups g1 ON COALESCE(ul.group_id, l.Gruppe) = g1.id LEFT JOIN
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
