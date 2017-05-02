<script type="text/javascript">
function Bestaetigung(link,text) {
  Check = confirm(text);
  if (Check == true)
  window.location.href = link;
}

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
  ahah('index.php', 'go=hideMenueWithAjax', new Array("", ""), new Array("", "execute_function"));
	document.all.menue_options.innerHTML='';
	document.all.imgMinMax.src='<?php echo GRAPHICSPATH; ?>maximize_menue.png';
	document.all.linkMinMax.href="javascript:showMenue()";
	document.all.linkMinMax.title="Menü zeigen";
	
}

function showMenue() {
  // läd den Content der Menütabelle über AJAX vom Server nach,
  // löscht die aktuelle Tabelle mit dem Link auf das Nachladen des Menüs und
  // fügt das Menü in die Spalte der GUI wieder ein.
  ahah('index.php', 'go=getMenueWithAjax', new Array(document.all.menuebar, ""), new Array("", "execute_function"));
  document.all.linkMinMax.href="javascript:hideMenue()";
  document.all.imgMinMax.src='<?php echo GRAPHICSPATH; ?>minimize_menue.png';
  document.all.linkMinMax.title="Menü verstecken";
}
</script>
<table class="menue-switch" width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="<?php echo BG_DEFAULT ?>">
    <tr>
      <td bgcolor="<?php echo BG_DEFAULT ?>" align="right"><?php
        if ($this->user->rolle->hideMenue) {
          ?><a id="linkMinMax" title="Menü zeigen" href="javascript:showMenue()"><img id="imgMinMax" src="<?php  echo GRAPHICSPATH; ?>maximize_menue.png" border="0"></a><?php
        }
        else {
        	?><a id="linkMinMax" title="Menü verstecken" href="javascript:hideMenue()"><img id="imgMinMax" src="<?php  echo GRAPHICSPATH; ?>minimize_menue.png" border="0"></a><?php
        }
      ?></td>
    </tr>
</table>	

<div id="menue_options">
<?	
  if (!$this->user->rolle->hideMenue){
		include(LAYOUTPATH.'languages/menue_body_'.$this->user->rolle->language.'.php');
		$wappen_html = '<img src="' . WAPPENPATH . $this->Stelle->getWappen() . '" alt="Wappen" align="middle" border="0">';
		$wappen_link = $this->Stelle->getWappenLink();
		if ($wappen_link != '') {
			$wappen_html = '<a href="' . $wappen_link . '" target="_blank">' . $wappen_html . '</a>';
		}
		$refmap_html = '
			<input
				style="margin: 2px;border: 1px solid #cccccc;"
				type="image"
				id="refmap"
				onmousedown="document.GUI.go.value=\'neu Laden\';"
				name="refmap"
				src="' . $this->img['referenzkarte'] . '"
				alt="Referenzkarte"
				hspace="0"
			>';

		if(MENU_WAPPEN=="oben") { ?>
		<div style="position: relative; visibility: visible; left: 0px; top: 0px"><?
			echo $wappen_html; ?>
		</div>		<? }
		
		if($this->img['referenzkarte'] != '' AND MENU_REFMAP == "oben")echo $refmap_html; ?>
		
		<div id="menueTable">
		<?
		$this->menues = Menue::loadMenue($this);
		foreach($this->menues as $menue){
			if($menue->get('menueebene') == 1 OR ($this->user->rolle->menue_buttons AND $menue->get('button_class') != '')) echo $menue->html();
		}
		?>
		</div>

		<?
		if($this->img['referenzkarte']!='' AND MENU_REFMAP !="oben")echo $refmap_html;
		
		if(MENU_WAPPEN=="unten"){ ?>
		<div style="position: relative; visibility: visible; left: 0px; top: 0px"><?
			echo $wappen_html; ?>
		</div>
		<? }
  } ?>
</div>