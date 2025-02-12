<?
	global $sizes;
	$size =	$sizes[$this->user->rolle->gui];
	$menue_height = $this->user->rolle->nImageHeight
									+ $size['scale_bar']['height'] +
									+ ((defined('LAGEBEZEICHNUNGSART') AND LAGEBEZEICHNUNGSART != '') ? $size['lagebezeichnung_bar']['height'] : 0)
									+ ($this->user->rolle->showmapfunctions == 1 ? $size['map_functions_bar']['height'] : 0)
									- $this->reference_map->reference->height
									- 22;
?>

<script type="text/javascript">
function changemenue(id, auto_close){
	if(auto_close == 1){
		var was_closed = $('#menue_div_name_'+id).hasClass('menue-zu');
		$('.untermenues').hide();
		$('.menue-auf').toggleClass('menue-auf menue-zu');
		if(was_closed){
			$('#menue_div_untermenues_'+id).toggle();
			$('#menue_div_name_'+id).toggleClass('menue-auf menue-zu');
		}
	}
	else {
		$('#menue_div_untermenues_'+id).toggle();
		$('#menue_div_name_'+id).toggleClass('menue-auf menue-zu');
	}
	ahah('index.php', 'go=changemenue_with_ajax&id='+id+'&status='+($('#menue_div_name_'+id).hasClass("menue-auf") ? "on" : "off"), new Array(''), '');
}

function hideMenue() {
	if(document.getElementById('menue_switch').style.display != 'none'){
		ahah('index.php', 'go=hideMenueWithAjax', new Array("", ""), new Array("", "execute_function"));
		document.all.menue_options.innerHTML='';
		document.all.imgMinMax.src='<?php echo GRAPHICSPATH; ?>maximize_menue.png';
		document.all.linkMinMax.onclick="showMenue()";
		document.all.linkMinMax.title="Menü zeigen";
	}
}

function showMenue() {
  // läd den Content der Menütabelle über AJAX vom Server nach,
  // löscht die aktuelle Tabelle mit dem Link auf das Nachladen des Menüs und
  // fügt das Menü in die Spalte der GUI wieder ein.
  ahah('index.php', 'go=getMenueWithAjax', new Array(document.all.menuebar, ""), new Array("", "execute_function"));
  document.all.linkMinMax.onclick="hideMenue()";
  document.all.imgMinMax.src='<?php echo GRAPHICSPATH; ?>minimize_menue.png';
  document.all.linkMinMax.title="Menü verstecken";
}
</script>
<table id="menue_switch" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td align="right"><?php
        if ($this->user->rolle->hideMenue) {
          ?><a id="linkMinMax" title="Menü zeigen" href="javascript:void(0);" onclick="showMenue()"><img id="imgMinMax" src="<?php  echo GRAPHICSPATH; ?>maximize_menue.png" border="0"></a><?php
        }
        else {
        	?><a id="linkMinMax" title="Menü verstecken" href="javascript:void(0);" onclick="hideMenue()"><img id="imgMinMax" src="<?php  echo GRAPHICSPATH; ?>minimize_menue.png" border="0"></a><?php
        }
      ?></td>
    </tr>
</table>
<input type="hidden" name="refmap_x">
<input type="hidden" name="refmap_y">

<div id="menue_options">
<?	
  if (!$this->user->rolle->hideMenue) {
		include(LAYOUTPATH.'languages/menue_body_'.$this->user->rolle->language.'.php');

		if (MENU_WAPPEN != 'kein') {
			$wappen = $this->Stelle->getWappen();
			$wappen_size = getimagesize(WAPPENPATH . $wappen['wappen']);
			$menue_height = $menue_height - $wappen_size[1];
			$wappen_html = '<img src="' . WAPPENPATH . $wappen['wappen'] . '" alt="Wappen" align="middle" border="0">';
			if ($wappen['wappen_link'] != '') {
				$wappen_html = '<a href="' . $wappen['wappen_link'] . '" target="_blank">' . $wappen_html . '</a>';
			}
		}
		$refmap_html = '
			<img
				id="refmap"
				name="refmap"
				src="' . $this->img['referenzkarte'] . '"
				alt=""
				hspace="0"
				style="cursor: pointer"
			>
			<script>
				function click(e) {
					let refmap = document.getElementById("refmap").getBoundingClientRect();
					document.GUI.refmap_x.value = e.clientX - refmap.x;
					document.GUI.refmap_y.value = e.clientY - refmap.y;
					neuLaden();
					document.GUI.refmap_x.value = "";
					document.GUI.refmap_y.value = "";
				}
				let img = document.getElementById("refmap");
				img.addEventListener("click", click);
			</script>
			';
		if ($wappen['wappen'] != '') {
			$wappen_html = '
				<div id="wappen_div" style="position: relative; visibility: visible; left: 0px; top: 0px">' .
					$wappen_html . '
				</div>
			';
		}
		else {
			$wappen_html = '';
		}

		if (MENU_WAPPEN=="oben") {
			echo $wappen_html;
		}

		if (MENU_REFMAP == "oben") {
			echo $refmap_html;
		} ?>

		<div id="menueTable"><?
			if ($this->user->rolle->menue_buttons) {
				$button_menues = Menue::loadMenue($this, 'button');		# erst nur die Button-Menüpunkte
				foreach($button_menues as $menue){
					echo $menue->html();
				}
				$this->menues = Menue::loadMenue($this, 'all-buttons');		# dann alle Menüpunkte, wobei Obermenüpunkte, die Buttons sind, weggelassen werden
				$menue_height = $menue_height - 38;
			}
			else {
				$this->menues = Menue::loadMenue($this, 'all-no_buttons');		# ansonsten alle Menüpunkte, keine Buttons
			}
			?><div id="menueScrollTable" style="max-height: <? echo $menue_height; ?>"><?
			foreach($this->menues as $menue){
				if($menue->get('menueebene') == 1) echo $menue->html();
			} ?>
			</div>
		</div>

		<div id="menuefooter"><?
			if (MENU_REFMAP !="oben") {
				echo $refmap_html;
			}
			if (MENU_WAPPEN == "unten") {
				echo $wappen_html;
			} ?>
		</div><?
	} ?>
</div>