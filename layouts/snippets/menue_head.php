<script type="text/javascript">
function Bestaetigung(link,text) {
  Check = confirm(text);
  if (Check == true)
  window.location.href = link;
}

function popup(id){
  document.getElementById(id).style.backgroundImage ="none";
  document.getElementById(id).style.backgroundColor="#CBD8E9";
  id = id+'subpop';
  document.getElementById(id).style.visibility="visible";
}

function popdown(id){
  document.getElementById(id).style.backgroundColor="#DAE4EC";
  id = id+'subpop';
  document.getElementById(id).style.visibility="hidden";
}

function changemenue(id){
	main = document.getElementById('menue'+id);
	image = document.getElementById('image_'+id);
	sub = document.getElementById('menue'+id+'sub');
	if(sub.style.display == 'none'){
		<? if($this->user->rolle->menu_auto_close == 1){ ?>
		// alle anderen Obermenuepunkte schliessen
		obermenues = document.getElementsByName('obermenu');
		for(i = 0; i < obermenues.length; i++){
			sub1 = document.getElementById(obermenues[i].id+'sub');
			if(sub1.style.display != 'none'){
				sub1.style.display = 'none';
				image1 = document.getElementById('image_'+obermenues[i].id.substr(5));
				image1.src = '<? echo GRAPHICSPATH; ?>menue_top.gif';
			}
		}
		<? } ?>
		// Untermenue oeffnen
		ahah('index.php', 'go=changemenue_with_ajax&id='+id+'&status=on', new Array(""), "");
  	image.src = '<? echo GRAPHICSPATH; ?>menue_top_open.gif';
		sub.style.display = '';
	}
	else{
		ahah('index.php', 'go=changemenue_with_ajax&id='+id+'&status=off', new Array(""), "");
		sub.style.display = 'none';
		image.src = '<? echo GRAPHICSPATH; ?>menue_top.gif';
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