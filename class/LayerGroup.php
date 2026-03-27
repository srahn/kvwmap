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
}
?>
