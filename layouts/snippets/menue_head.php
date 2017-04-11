<script type="text/javascript">
function Bestaetigung(link,text) {
  Check = confirm(text);
  if (Check == true)
  window.location.href = link;
}

function changemenue(id, auto_close){
	ahah('index.php', 'go=changemenue_with_ajax&id='+id+'&status='+($('#menue_div_name_'+id).hasClass("menue-auf") ? "off" : "on"), new Array(''), '');
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
}

function hideMenue() {
//	alert("html"+document.all.menueTable.innerHTML);
	// löscht den HTML-Inhalt der Menütabelle,
	// schiebt dadurch die Spalte der GUI auf minimale Breite zusammen und
	// hinterläßt einen Link zum wieder einblenden des Menüs auf showMenue()
  ahah('index.php', 'go=hideMenueWithAjax', new Array("", ""), new Array("", "execute_function"));
	document.all.menueTable.innerHTML='';
	document.all.imgMinMax.src='<?php echo GRAPHICSPATH; ?>maximize_menue.png';
	document.all.linkMinMax.href="javascript:showMenue()";
	document.all.linkMinMax.title="Menü zeigen";
	
}

function showMenue() {
  // läd den Content der Menütabelle über AJAX vom Server nach,
  // löscht die aktuelle Tabelle mit dem Link auf das Nachladen des Menüs und
  // fügt das Menü in die Spalte der GUI wieder ein.
  ahah('index.php', 'go=getMenueWithAjax&menuebodyfile=<? echo $this->menuebodyfile; ?>', new Array(document.all.menueTable, ""), new Array("", "execute_function"));
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