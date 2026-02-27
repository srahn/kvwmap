<?php
include_once(CLASSPATH . 'PgObject.php');
include_once(CLASSPATH . 'PgAttribute.php');
include_once(CLASSPATH . 'Menue2Stelle.php');
class Menue extends PgObject {
	var $obermenue;
	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'kvwmap', 'u_menues', 'id');
		$this->validations = array(
			array(
				'attribute' => 'id',
				'condition' => 'unique',
				'description' => 'Die id darf nur ein Mal vorkommen.',
				'option' => 'on Insert'
			),
			array(
				'attribute' => 'name',
				'condition' => 'not_null',
				'description' => 'Es muss ein Menüname angegeben werden.',
				'option' => null
			),
			array(
				'attribute' => 'obermenue',
				'condition' => 'not_null',
				'description' => 'Es muss das Obermenue angegeben werden (oder 0 falls es keins gibt).',
				'option' => array(1, 2)
			),				
			array(
				'attribute' => 'menueebene',
				'condition' => 'not_null',
				'description' => 'Es muss eine Menüebene angegeben werden.',
				'option' => array(1, 2)
			),
			array(
				'attribute' => 'menueebene',
				'condition' => 'validate_value_is_one_off',
				'description' => 'Es muss Menüebene 1=Obermenü oder 2=Untermenü angegeben werden.',
				'option' => array(1, 2)
			),
			array(
				'attribute' => 'order',
				'condition' => 'not_null',
				'description' => 'Es wurde keine Reihenfolge angegeben.',
				'option' => null
			)
		);
	}

	public static	function find($gui, $where, $order = '', $sort_direction = '') {
		$menue = new Menue($gui);
		return $menue->find_where($where, $order);
	}

	public static function loadMenue($gui, $type) {
		switch ($type) {
			case 'button' : {		# es sollen nur die Button-Menüpunkte abgefragt werden
				$button_where = " AND m.button_class != ''";
			}break;
			
			case 'all-no_buttons' : {		# es sollen alle Menüpunkte abgefragt werden und es gibt keine Button-Menüpunkte
				$button_where = " ";
			}break;
			
			case 'all-buttons' : {			# es sollen alle Menüpunkte abgefragt werden und es gibt Button-Menüpunkte -> dann Obermenüpunkte mit button_class weglassen
				$button_where = " AND (m.button_class = '' OR m.button_class IS NULL OR m.menueebene = 2)";
			}break;
		}
		$menue = new Menue($gui);
		$menues = $menue->find_by_sql(
			array(
				'select' => "
					status,
					m.id,
					m.links,
					m.onclick,
					name as name_german," .
					(rolle::$language != 'german' ? "name_" . rolle::$language . " AS" : "") . " name,
					m.menueebene,
					m.obermenue,
					m.target,
					m.title,
					".($type == 'button'? "m.button_class" : "'' as button_class")."
				",
				'from' => "
					kvwmap.u_menue2rolle m2r JOIN
					kvwmap.u_menue2stelle AS m2s ON (m2r.stelle_id = m2s.stelle_id AND m2r.menue_id = m2s.menue_id) JOIN
					kvwmap.u_menues AS m ON (m2s.menue_id = m.id)
				",
				'where' => "
					m2s.stelle_id = " . $gui->Stelle->id . " AND
					m2r.user_id = " . $gui->user->id . " 
					".$button_where."
				",
				'order' => "
					m2s.menue_order
				"
			), 'obermenue'
		);
		return $menues;
	}
	
	public static function get_all_ober_menues($gui){
		global $admin_stellen;
		$more_from = '';
		$more_where = '';

		if ($gui->user->id > 0 AND !in_array($gui->Stelle->id, $admin_stellen)) {
			# Frage nur die Nutzer ab, die der aktuellen Stelle zugeordnet sind
			$more_from = "
				JOIN kvwmap.u_menue2stelle ms ON m.id = ms.menue_id
				JOIN kvwmap.rolle ra ON ms.stelle_id = ra.stelle_id
				JOIN kvwmap.rolle rb ON ra.stelle_id = rb.stelle_id
			";
			$more_where = " AND rb.user_id = " . $gui->user->id;
		}

		$menue = new Menue($gui);
		$menues = $menue->find_by_sql(
			array(
				'select' => " DISTINCT
					m.id,".
					(rolle::$language != 'german' ? "m.name_" . rolle::$language . " AS" : "") . " name,
					m.order,
					m.menueebene
				",
				'from' => "
					kvwmap.u_menues m" .
					$more_from . "
				",
				'where' => "
					m.menueebene = 1" .
					$more_where . "
				",
				'order' => "
					m.order
				"
			)
		);
    return $menues;
	}
	
	public static function getsubmenues($gui, $menue_id){
		global $admin_stellen;
		$more_from = '';
		$more_where = '';

		if ($gui->user->id > 0 AND !in_array($gui->Stelle->id, $admin_stellen)) {
			# Frage nur die Nutzer ab, die der aktuellen Stelle zugeordnet sind
			$more_from = "
				JOIN kvwmap.u_menue2stelle ms ON m.id = ms.menue_id
				JOIN kvwmap.rolle rall ON ms.stelle_id = rall.stelle_id
				JOIN kvwmap.rolle radm ON rall.stelle_id = radm.stelle_id
			";
			$more_where = " AND radm.user_id = " . $gui->user->id;
		}

		$menue = new Menue($gui);

		$menues = $menue->find_by_sql(
			array(
				'select' => " DISTINCT
					m.id,
					m.name" . (rolle::$language != 'german' ? "_" . rolle::$language : "") . " AS name,
					m.order,
					m.menueebene
				",
				'from' => "
					kvwmap.u_menues m" .
					$more_from . "
				",
				'where' => "
					m.obermenue = " . $menue_id . " AND
					m.menueebene = 2" .
					$more_where . "
				",
				'order' => "
					m.order, name
				"
			)
		);
		return $menues;
	}

	function get_stellen() {
		$this->stellen = Menue2Stelle::find($this->gui, 'menue_id = ' . $this->get('id'), ' DISTINCT stelle_id');
		return $this->stellen;
	}

	function is_selected() {
		$is_selected = true;
		$formvars = $_REQUEST;
		$link = parse_url(
			replace_params_rolle($this->get('links'))
		);
		if (value_of($link, 'query') == '') {
			$is_selected = false;
		}
		else {
			parse_str($link['query'], $link_params);
			foreach($link_params AS $key => $value) {
				if (value_of($formvars, $key) != $value) {
					$is_selected = false;
				}
			}
		}
		return $is_selected;
	}

	function get_class() {
		if (count($this->children_ids) > 0) {
			# Obermenue
			$class = 'obermenue	' . ($this->get('status') == 1 ? 'menue-auf' : 'menue-zu');
			$this->obermenue = true;
		}
		else {
			if ($this->get('menueebene') == 1) {
				# Obermenue ohne Untermenüpunkte (Hauptmenue)
				$class = 'hauptmenue';
			}
			else {
				# Untermenue
				$class = 'untermenue';
			}
		}

		$class .= ($this->is_selected() ? ' ausgewaehltes-menue' : '');
		$this->class = $class;
		return $class;
	}

	function get_target() {
		if ($this->get('target') == '' OR $this->get('target') == 'confirm') {
			$target = '';
		}
		else {
			$target = $this->get('target');
		}
		$this->target = $target;
		return $target;
	}

	function get_onclick(){
		$onclick = replace_params_rolle($this->get('onclick'));
		return $onclick;
	}

	function get_href() {
		$href = '';
		$link = replace_params_rolle($this->get('links'));
		$link = add_csrf($link);
		# define click events
		if ($this->obermenue){
			$href .= "javascript:changemenue(".$this->get('id').", ".$this->gui->user->rolle->menu_auto_close.");";
		}
		else {
			# call a link
			if ($this->get('target') == 'confirm') {
				$href = "javascript:Bestaetigung('" . $link . "', 'Diese Aktion wirklich ausführen?')";
			}
			elseif($link == ''){
				$href = 'javascript:void(0);';
			}
			else{
				$href = $link;
			}
		}
		return $href;
	}
	
	function get_style(){
		if($this->gui->user->rolle->menue_buttons AND $this->get('button_class') != ''){		# Button-Menüpunkt
			return 'button';
		}
		else{
			return 'text';
		}
	}

	function html() {
		$html = '';
		$class  = $this->get_class();
		$target = $this->get_target();
		$href = $this->get_href();
		$onclick = $this->get_onclick();
		if(!$this->obermenue)$onclick = 'checkForUnsavedChanges(event);'.$onclick;
		$style = $this->get_style();

		$html .= '<div class="'.$style.'-menue" id="menue_div_'.$this->get('id').'">';
		$html .= '<a href="' .  htmlspecialchars($href) . '" target="' . $target . '" onclick="' . $onclick . '">';
		if ($style == 'button') { # Button-Menüpunkt
			$html .= '<div class="button_background">';
			$html .= '	<div class="button ' .  htmlspecialchars($this->get('button_class')) . '" title="' .  htmlspecialchars($this->get('name')) . '"></div>';
			$html .= '</div>';
		}
		else { # textueller Menüpunkt
			$html .= '<div id="menue_div_name_'.$this->get('id').'" title="'.$this->get('title').'" class="menu '.$class.'">';
			$html .= '	<img src="graphics/menue_top.gif" class="menue_before">';
			$html .= '	<span style="vertical-align: top">'.$this->get('name').'</span>';
			$html .= '</div>';
		}
		
		$html .= '</a>';

		if (count($this->children_ids) > 0) {
			$html .= '	<div id="menue_div_untermenues_' . $this->get('id') . '" class="untermenues" style="' . ($this->get('status') == 1 ? '' : 'display: none;') . '"">';
			foreach($this->children_ids AS $untermenue) {
				$html .= $this->gui->menues[$untermenue]->html();
			}
			$html .= '	</div>';
		}

		$html .= '</div>';
		return $html;
	}

	/**
	 * Speichert die Zuordnung des Menüs zu den Stellen und Rollen der Nutzer in der Stellen.
	 * @param array $stelle_ids Array mit den Stellen-IDs, die dem Menü zugeordnet werden sollen
	 * @return void
	 */
	function update_stellen($stelle_ids) {
		$with_new_values = "
			WITH nv(menue_id, stelle_id) AS (
			VALUES
				" . implode(
					', ',
					array_map(
						function($stelle_id) {
							return '(' . $this->get('id') . ', ' . $stelle_id . ', 0)';
						},
						$stelle_ids
					)
				) . "
			)
		";
		$sql ="
			DELETE FROM kvwmap.u_menue2rolle m2r WHERE m2r.menue_id = " . $this->get('id') . " AND m2r.stelle_id NOT IN (" . implode(', ', $stelle_ids) . ");
			DELETE FROM kvwmap.u_menue2stelle m2s WHERE m2s.menue_id = " . $this->get('id') . " AND m2s.stelle_id NOT IN (" . implode(', ', $stelle_ids) . ");
			" . $with_new_values . "
			INSERT INTO kvwmap.u_menue2stelle (menue_id, stelle_id, menue_order)
			SELECT
				nv.menue_id, 
				nv.stelle_id,
				0
			FROM 
				nv LEFT JOIN
				kvwmap.u_menue2stelle ms ON nv.menue_id = ms.menue_id AND nv.stelle_id = ms.stelle_id
			WHERE
				ms.stelle_id IS NULL;
			" . $with_new_values . "
			INSERT INTO kvwmap.u_menue2rolle (menue_id, stelle_id, user_id, status)
			SELECT
				nv.menue_id, 
				nv.stelle_id,
				r.user_id,
				0
			FROM
				nv JOIN
				kvwmap.rolle r ON nv.stelle_id = r.stelle_id LEFT JOIN
				kvwmap.u_menue2stelle ms ON nv.menue_id = ms.menue_id AND nv.stelle_id = ms.stelle_id
			WHERE
				ms.stelle_id IS NULL;
		";
		// echo "Update relation of menues to stellen and rollen with SQL: " . $sql;
		$ret = $this->database->execSQL($sql);
		// $this->execSQL($sql);
		return $ret;
	}
}
?>
