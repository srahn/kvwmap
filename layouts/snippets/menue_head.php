<?php
  # 2008-01-11 pk
  include(LAYOUTPATH.'snippets/ahah.php');
  echo $ahah;    
?>

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
	if(sub == undefined){
		var sub = document.createElement("div");
		sub.id = 'menue'+id+'sub';
		sub.style.background = '<? echo BG_MENUESUB; ?>';
		ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=changemenue_with_ajax&id='+id+'&status=on', new Array(sub), "");
  	main.appendChild(sub);
  	image.src = '<? echo GRAPHICSPATH; ?>menue_top_open.gif';
	}
	else{
		ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=changemenue_with_ajax&id='+id+'&status=off', new Array(sub), "");
		main.removeChild(sub);
		image.src = '<? echo GRAPHICSPATH; ?>menue_top.gif';
	}
}

function hideMenue() {
//	alert("html"+document.all.menueTable.innerHTML);
	// löscht den HTML-Inhalt der Menütabelle,
	// schiebt dadurch die Spalte der GUI auf minimale Breite zusammen und
	// hinterläßt einen Link zum wieder einblenden des Menüs auf showMenue()
  ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=hideMenueWithAjax', new Array(), "");
	document.all.menueTable.innerHTML='';
	document.all.imgMinMax.src='<?php echo GRAPHICSPATH; ?>maximize.png';
	document.all.linkMinMax.href="javascript:showMenue()";
	document.all.linkMinMax.title="Menü zeigen";
	
}

function showMenue() {
  // läd den Content der Menütabelle über AJAX vom Server nach,
  // löscht die aktuelle Tabelle mit dem Link auf das Nachladen des Menüs und
  // fügt das Menü in die Spalte der GUI wieder ein.
  ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=getMenueWithAjax&menuebodyfile=<? echo $this->menuebodyfile; ?>', new Array(document.all.menueTable), "");
  document.all.linkMinMax.href="javascript:hideMenue()";
  document.all.imgMinMax.src='<?php echo GRAPHICSPATH; ?>minimize.png';
  document.all.linkMinMax.title="Menü verstecken";
}
</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td bgcolor="<?php echo BG_DEFAULT ?>" align="right"><?php
        if ($this->user->rolle->hideMenue) {
          ?><a id="linkMinMax" title="Menü zeigen" href="javascript:showMenue()"><img id="imgMinMax" src="<?php  echo GRAPHICSPATH; ?>maximize.png" border="0"></a><?php
        }
        else {
        	?><a id="linkMinMax" title="Menü verstecken" href="javascript:hideMenue()"><img id="imgMinMax" src="<?php  echo GRAPHICSPATH; ?>minimize.png" border="0"></a><?php
        }
      ?></td>
    </tr>
</table>