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

	public static function find_all_obermenues($gui) {
		$menue = new Menue($gui);
		$menues = $menue->find_by_sql(
			array(
				'select' => "
					m.id,
					m.name" . ($gui->user->rolle->language != 'german' ? '_' . $gui->user->rolle->language . ' AS name' : '') . ",
					m.title,
					m.target,
					m.order,
					m.links,
					m.menueebene,
					m2r.status
				",
				'from' => "
					u_menues m join
					u_menue2rolle m2r ON (m.id = m2r.menue_id AND m2r.stelle_id = " . $gui->Stelle->id . " AND m2r.user_id = " . $gui->user->id . ") 
				",
				'where' => "
					m.menueebene = 1
				",
				'order' => "
					m.order
				"
			)
		);
		return $menues;
	}

	function get_untermenues($obermenue_id) {
		$menue = new Menue($this->gui);
		$menues = $menue->find_by_sql(
			array(
				'select' => "
					m.id,
					m.name" . ($this->gui->user->rolle->language != 'german' ? '_' . $this->gui->user->rolle->language . ' AS name' : '') . ",
					m.title,
					m.target,
					m.links,
					m.order,
					m.menueebene,
					m2r.status
				",
				'from' => "
					u_menues m join
					u_menue2rolle m2r ON (m.id = m2r.menue_id AND m2r.stelle_id = " . $this->gui->Stelle->id . " AND m2r.user_id = " . $this->gui->user->id . ") 
				",
				'where' => "
					m.obermenue = " . $obermenue_id . "
				",
				'order' => "
					m.order
				"
			)
		);
		return $menues;
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
		if (in_array($class, array('untermenue', 'hauptmenue'))) {
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
		else {
			# only toggle menues
			$onclick .= "ahah('index.php', 'go=changemenue_with_ajax&id=" . $this->get('id') . "&status=' + ($('#menue_div_name_" . $this->get('id') . "').hasClass('menue-auf') ? 'off' : 'on'), new Array(''), '');";
			if ($this->gui->user->rolle->menu_auto_close == 1) {
				# close all untermenues if menu_auto_close option is 1
				$onclick .= "var was_closed = $('#menue_div_name_" . $this->get('id') . "').hasClass('menue-zu');";
				$onclick .= "$('.untermenues').hide();";
				$onclick .= "$('.menue-auf').toggleClass('menue-auf menue-zu');";
				$onclick .= "if (was_closed) {
					$('#menue_div_untermenues_" . $this->get('id') . "').slideToggle();
					$('#menue_div_name_" . $this->get('id') . "').toggleClass('menue-auf menue-zu');
				}";
			}
			else {
				$onclick .= "$('#menue_div_untermenues_" . $this->get('id') . "').slideToggle();";
				$onclick .= "$('#menue_div_name_" . $this->get('id') . "').toggleClass('menue-auf menue-zu');";
			}
		}
		return $onclick;
	}

	function html() {
		$untermenues = $this->get_untermenues($this->get('id'));
		$class = $this->get_class($untermenues);
		$target = $this->get_target();
		$onclick = $this->get_onclick($class, $target);
		if (extract_go($this->get('links')) == $this->gui->formvars['go']) {
			$class .= ' ausgewaehltes-menue';
		};

		$html .= '<div id="menue_div_' . $this->get('id') . '">';
		$html .= '<div
			id="menue_div_name_' . $this->get('id') . '"
			title="' . $this->get('title') . '" 
			class="menu ' . $class . '"
			onclick="' . $onclick . '"
		>';
		$html .= $this->get('name');
		$html .= '	</div>';

		if (count($untermenues) > 0) {
			$html .= '	<div id="menue_div_untermenues_' . $this->get('id') . '" class="untermenues" style="' . ($this->get('status') == 1 ? '' : 'display: none;') . '"">';
			foreach($untermenues AS $untermenue) {
				$html .= $untermenue->html();
			}
			$html .= '	</div>';
		}
		else {
			
		}

		$html .= '</div>';
#		echo $html;
#		$html = $this->get('name');
		return $html;
	}
}
?>
