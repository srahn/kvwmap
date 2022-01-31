<?php
include_once(CLASSPATH . 'MyObject.php');
include_once(CLASSPATH . 'Layer.php');
class LayerGroup extends MyObject {

	static $write_debug = false;
	static $identifier = 'id';

	function __construct($gui) {
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
		$group = new LayerGroup($gui);
		return $group->find_by(LayerGroup::$identifier, $id);
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

	public static function find_top_parents($gui, $stelle_id) {
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

	public function find_sub_groups() {
		return $this->find_by_sql(array(
			'select' => "child.`id`, child.`Gruppenname`, child.`icon`, child.`order`",
			'from' => "`u_groups` parent JOIN `u_groups` child ON parent.`id` = child.`obergruppe`",
			'where' => "parent.`id` = " . $this->get('id'),
			'order' => "child.`order`"
		));
	}

	function get_layerdef($thema) {
		$thema = ($thema != '' ? $thema . '|' : '') . $this->get('Gruppenname');
		#echo '<br>thema: ' . $thema;
		$layerdef = (Object) array(
			'thema' => $thema,
			'icon' => $this->get('icon')
		);
		$sub_groups = $this->find_sub_groups();
		if (count($sub_groups) > 0) {
			$layerdef->themes = array_map(
				function($sub_group, $parent_thema) {
					return $sub_group->get_layerdef($parent_thema);
				},
				$sub_groups,
				array_fill(0, count($sub_groups), $thema)
			);
		}
		return $layerdef;
	}
}
?>
