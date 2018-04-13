<?php
include_once(CLASSPATH . 'MyObject.php');
include_once(CLASSPATH . 'MyAttribute.php');
class Menue extends MyObject {

	static $write_debug = false;

	function Menue($gui) {
		$this->MyObject($gui, 'u_menues');
		$this->identifier = 'id';
		$this->validations = array(
			array(
				'attribute' => 'name',
				'condition' => 'not_null',
				'description' => 'Es muss ein Menüname angegeben werden.',
				'options' => null
			),
			array(
				'attribute' => 'links',
				'condition' => 'not_null',
				'description' => 'Ein Link muss angegeben werden. Für Obermenüpunkte in der Regel index.php?go=changemenue',
				'options' => null
			),
			array(
				'attribute' => 'menueebene',
				'condition' => 'not_null',
				'description' => 'Es muss eine Menüebene angegeben werden.',
				'options' => array(1, 2)
			),
			array(
				'attribute' => 'menueebene',
				'condition' => 'validate_value_is_one_off',
				'description' => 'Es muss Menüebene 1=Obermenü oder 2=Untermenü angegeben werden.',
				'options' => array(1, 2)
			)
		);
	}

	public static	function find($gui, $where, $order = '') {
		$menue = new Menue($gui);
		return $menue->find_where($where, $order);
	}

	public static function loadMenue($gui, $type) {
		switch ($type){
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
					($gui->user->rolle->language != 'german' ? "`name_" . $gui->user->rolle->language . "` AS" : "") . " name,
					m.menueebene,
					m.obermenue,
					m.target,
					m.title,
					".($type == 'button'? "m.button_class" : "'' as button_class")."
				",
				'from' => "
					u_menue2rolle m2r JOIN
					u_menue2stelle AS m2s ON (m2r.stelle_id = m2s.stelle_id AND m2r.menue_id = m2s.menue_id) JOIN
					u_menues AS m ON (m2s.menue_id = m.id)
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
		$menue = new Menue($gui);
		$menues = $menue->find_by_sql(
			array(
				'select' => "
					id,".
					($gui->user->rolle->language != 'german' ? "`name_" . $gui->user->rolle->language . "` AS" : "") . " name,
					`order`,
					menueebene
				",
				'from' => "
					u_menues
				",
				'where' => "
					menueebene = 1
				",
				'order' => "
					`order`
				"
			)
		);
    return $menues;
	}
	
	public static function getsubmenues($gui, $menue_id){
		$menue = new Menue($gui);
		$menues = $menue->find_by_sql(
			array(
				'select' => "
					id,".
					($gui->user->rolle->language != 'german' ? "`name_" . $gui->user->rolle->language . "` AS" : "") . " name,
					`order`,
					menueebene
				",
				'from' => "
					u_menues
				",
				'where' => "
					obermenue = ".$menue_id." AND menueebene = 2
				",
				'order' => "
					`order`, name
				"
			)
		);
    return $menues;
	}
	/*
	public function validate() {
		$results = array();
		foreach($this->validations AS $validation) {
			$results[] = $this->validates($validation['attribute'], $validation['condition'], $validation['description'], $validation['options']);
		}

		$messages = array();
		foreach($results AS $result) {
			if (!empty($result)) {
				$messages[] = $result;
			}
		}
		return $messages;
	}
*/
	function is_selected() {
		$is_selected = true;
		$formvars = $_REQUEST;
		$link = parse_url($this->get('links'));
		if ($link['query'] == '') {
			$is_selected = false;
		}
		else {
			parse_str($link['query'], $link_params);
			foreach($link_params AS $key => $value) {
				if ($formvars[$key] != $value) {
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


	function get_href($class, $target) {
		# define click events
		if($this->obermenue){
			$href .= "javascript:changemenue(".$this->get('id').", ".$this->gui->user->rolle->menu_auto_close.");";
		}
		else {
			# call a link
			if ($this->get('target') == 'confirm') {
				$href = "javascript:Bestaetigung('" . $this->get('links') . "', 'Diese Aktion wirklich ausführen?')";
			}
			else $href = $this->get('links');
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
		$class  = $this->get_class();
		$target = $this->get_target();
		$href = $this->get_href($class, $target);
		$onclick = $this->get('onclick');
		if(!$this->obermenue)$onclick = 'checkForUnsavedChanges(event);'.$onclick;
		$style = $this->get_style();

		$html .= '<div class="'.$style.'-menue" id="menue_div_'.$this->get('id').'">';
		$html .= '<a href="'.$href.'" target="'.$target.'" onclick="'.$onclick.'">';
		if($style == 'button'){		# Button-Menüpunkt
			$html .= '<div class="button_background">';
			$html .= '	<div class="button '.$this->get('button_class').'" title="'.$this->get('name').'"></div>';
			$html .= '</div>';
		}
		else{				# textueller Menüpunkt
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
}
?>
