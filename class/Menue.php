<?php
include_once(CLASSPATH . 'MyObject.php');
class Menue extends MyObject {

	static $write_debug = false;

	function Menue($gui) {
		$this->MyObject($gui, 'menue');
		$this->identifier = 'id';
	}

	public static	function find($gui, $where) {
		$menue = new Menue($gui);
		return $menue->find_where($where);
	}

	public static function loadMenue($gui) {
		$menue = new Menue($gui);
		$menues = $menue->find_by_sql(
			array(
				'select' => "
					status,
					m.id,
					m.links,
					name as name_german," .
					($gui->user->rolle->language != 'german' ? "`name_" . $gui->user->rolle->language . "` AS" : "") . " name,
					m.menueebene,
					m.obermenue,
					m.target,
					m.title
				",
				'from' => "
					u_menue2rolle m2r JOIN
					u_menue2stelle AS m2s ON (m2r.stelle_id = m2s.stelle_id AND m2r.menue_id = m2s.menue_id) JOIN
					u_menues AS m ON (m2s.menue_id = m.id)
				",
				'where' => "
					m2s.stelle_id = " . $gui->Stelle->id . " AND
					m2r.user_id = " . $gui->user->id . "
				",
				'order' => "
					m2s.menue_order
				"
			), 'obermenue'
		);
		return $menues;
	}

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

	function get_class($untermenues) {
		# define menue class
		if (count($untermenues) > 0) {
			# Obermenue
			$class = 'obermenue	' . ($this->get('status') == 1 ? 'menue-auf' : 'menue-zu');
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


	function get_onclick($class, $target) {
		# define click events
		if(strpos($class, 'obermenu') !== false){
			$onclick .= "changemenue(".$this->get('id').", ".$this->gui->user->rolle->menu_auto_close.");";
		}
		else {
			# call a link
			if ($this->get('target') == 'confirm') {
				$onclick = "javascript:Bestaetigung('" . $this->get('links') . "', 'Diese Aktion wirklich ausführen?')";
			}
			else {
				if ($target != '') { # open link in target
					$onclick = "window.open('" . $this->get('links') . "', '" . $target . "')";
				}
				else { # open link in same window
					$onclick = "location.href='" . $this->get('links') . "'";
				}
			}
		}
		return $onclick;
	}

	function html() {
		$class  = $this->get_class($this->children_ids);
		$target = $this->get_target();
		$onclick = $this->get_onclick($class, $target);

		$html .= '<div id="menue_div_' . $this->get('id') . '">';
		$html .= '<div
			id="menue_div_name_' . $this->get('id') . '"
			title="' . $this->get('title') . '" 
			class="menu ' . $class . '"
			onclick="' . $onclick . '"
		>';
		$html .= '<img src="graphics/menue_top.gif" class="menue_before">';
		$html .= '<span style="vertical-align: top">'.$this->get('name').'</span>';
		$html .= '	</div>';

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
