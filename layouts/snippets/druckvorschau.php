
<script type="text/javascript">
<!--

function print(){
	document.GUI.target = '_blank';
	document.GUI.go_plus.value = 'Drucken';
	document.GUI.submit();
}

function goback(){
	document.GUI.target = '';
	document.GUI.go_plus.value = '';
	document.GUI.submit();
}

function addfreetext(){
	newfreetext = document.getElementById('text').cloneNode(true);
	newfreetext.id = parseInt(document.GUI.last_freetext_id.value) + 1;
	document.GUI.last_freetext_id.value = newfreetext.id;
	newfreetext.style.display = '';
	newfreetext.childNodes[0].name = 'freetext' + newfreetext.id;
	newfreetext.childNodes[1].name = 'freetext_posx' + newfreetext.id;
	newfreetext.childNodes[2].name = 'freetext_posy' + newfreetext.id;
	newfreetext.childNodes[3].name = 'freetext_width' + newfreetext.id;
	newfreetext.childNodes[4].name = 'freetext_height' + newfreetext.id;
	newfreetext.childNodes[5].name = 'freetext_fontsize' + newfreetext.id;
	document.getElementById('main').appendChild(newfreetext);
}

function delete_freetext(object){
	document.getElementById('main').removeChild(object.parentNode.parentNode);
}

function reduce_fontsize(object){
	fontsize = parseInt(object.parentNode.parentNode.childNodes[0].style.fontSize);
	fontsize = fontsize - 1;
	object.parentNode.parentNode.childNodes[5].value = fontsize;
	object.parentNode.parentNode.childNodes[0].style.fontSize = fontsize + 'px';
}

function increase_fontsize(object){
	fontsize = parseInt(object.parentNode.parentNode.childNodes[0].style.fontSize);
	fontsize = fontsize + 1;
	object.parentNode.parentNode.childNodes[5].value = fontsize;
	object.parentNode.parentNode.childNodes[0].style.fontSize = fontsize + 'px';
}

function start_resize(evt){
	if(!evt)evt = window.event; // For IE
	id = (evt.target) ? evt.target.parentNode.parentNode.id : evt.srcElement.parentNode.parentNode.id;
	if(id){
		document.GUI.action.value = 'resize';
		document.GUI.active_freetext.value = id;
		freetext = document.getElementById(id);
		document.GUI.startx.value = evt.clientX;
		document.GUI.starty.value = evt.clientY;
		document.GUI.startheight.value = parseInt(freetext.firstChild.style.height);
		document.GUI.startwidth.value = parseInt(freetext.firstChild.style.width);
	}
}

function start_move(evt){
	if(!evt)evt = window.event; // For IE
	id = (evt.target) ? evt.target.id : evt.srcElement.id;
	if(id){
		document.GUI.action.value = 'move';
		document.GUI.active_freetext.value = id;
		freetext = document.getElementById(id);
		document.GUI.startx.value = (evt.clientX - document.getElementById('main').offsetLeft) - parseInt(freetext.style.left);
		document.GUI.starty.value = (evt.clientY - document.getElementById('main').offsetTop) - parseInt(freetext.style.top);
	}
}

function deactivate(){
	document.GUI.active_freetext.value = '';
}

function mousemove(evt){
	if(!evt)evt = window.event; // For IE
	if(document.GUI.active_freetext.value != ''){
		if(document.GUI.action.value == 'move'){
			//evt.preventDefault();
			freetext = document.getElementById(document.GUI.active_freetext.value);
			freetext.style.border='2px dashed grey';
			freetext.style.cursor='move';
			freetext.childNodes[1].value = (evt.clientX - document.getElementById('main').offsetLeft - document.GUI.startx.value);
			freetext.childNodes[2].value = (evt.clientY - document.getElementById('main').offsetTop - document.GUI.starty.value);
			freetext.style.left = freetext.childNodes[1].value + 'px';
			freetext.style.top =  freetext.childNodes[2].value + 'px';
		}
		if(document.GUI.action.value == 'resize'){
			//evt.preventDefault();
			freetext = document.getElementById(document.GUI.active_freetext.value).firstChild;
			freetext.parentNode.style.cursor='se-resize';
			freetext.parentNode.style.border='2px dashed grey';						
			freetext.parentNode.childNodes[3].value = parseInt(document.GUI.startwidth.value) + parseInt(evt.clientX) - parseInt(document.GUI.startx.value);			
			freetext.parentNode.childNodes[4].value = parseInt(document.GUI.startheight.value) + parseInt(evt.clientY) - parseInt(document.GUI.starty.value);
			freetext.style.width = freetext.parentNode.childNodes[3].value;
			freetext.style.height = freetext.parentNode.childNodes[4].value;
		}
	}
}

function preventflickering(evt){
	if(!evt)evt = window.event; // For IE
	if(document.GUI.active_freetext.value != ''){
		evt.preventDefault();
	}
}
  
//-->
</script>

<?
	if ($this->Meldung=='') {
	  $bgcolor=BG_FORM;
	}
	else {
	  $bgcolor=BG_FORMFAIL;
		showAlert('Fehler bei der Eingabe:\n'.$this->Meldung);
}
 ?> 
<br>
<table border="0" cellspacing="0" cellpadding="2">
  <tr align="center"> 
    <td colspan="2"><h2> 
      <?php echo $this->titel; ?><br><br></h2>
    </td>
  </tr>
</table>
<div id="main" style="width:595; position: relative; left:0px; top:0px;" onmousedown="preventflickering(event);" onmousemove="mousemove(event);">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left">
			<img width="595" style="border:1px solid black" src="<? echo $this->previewfile ?>">
		</td>
	</tr>
</table>

<? 
      	if(strpos($this->Docu->activeframe[0]['format'], 'quer') !== false)$height = 420;			# das ist die Höhe des Vorschaubildes
      	else $height = 842;																																		# das ist die Höhe des Vorschaubildes
      	$ratio = $height/$this->Docu->height;
      	$size = round(12*$ratio);
      	$posx = 200;
      	$posy = 200;
?>
	<!-- hier wird viel mit Childs und Parents anstatt mit ids gemacht, weil das nur die Vorlage für die nachher geclonten Freitexte ist -->
	<div onmouseover="this.style.border='2px dotted grey'; this.lastChild.style.display=''; this.style.cursor='move';" onmouseout="this.style.border='1px solid black'; this.lastChild.style.display='none';" onmousedown="start_move(event);" onmouseup="deactivate();" title="Freitext" id="text" style="display:none; background-color: white; border: 1px solid black; position: absolute; visibility: visible; left: <? echo $posx; ?>px; top: <? echo $posy; ?>px; padding:3px"><textarea wrap="off" name="freetext" style="overflow: hidden; resize: none; width: 150px; height: 70px; border: none; background-color:transparent; font-size: <? echo $size; ?>px; font-family: Helvetica;">hier Text eingegeben...</textarea><input type="hidden" name="freetext_posx" value="<? echo $posx; ?>"><input type="hidden" name="freetext_posy" value="<? echo $posy; ?>"><input type="hidden" name="freetext_width" value="150"><input type="hidden" name="freetext_height" value="70"><input type="hidden" name="freetext_fontsize" value="<? echo $size; ?>">
		<!-- obiges und unteres muss so hintereinander stehen, sonst gibt es zwischen den Elementen noch Textelemente -->
		<div style="display:none; width:100%;">
			<img title="Freitext löschen" onclick="delete_freetext(this);" onmouseover="this.style.cursor='pointer';" style="position: absolute; bottom:3px; left:0px" src="graphics/symbol_delete.gif">
			<img title="Schrift verkleinern" onclick="reduce_fontsize(this);" onmouseover="this.style.cursor='pointer';" style="position: absolute; bottom:4px; left:20px" src="graphics/minus.gif">
			<img title="Schrift vergrößern" onclick="increase_fontsize(this);" onmouseover="this.style.cursor='pointer';" style="position: absolute; bottom:4px; left:32px" src="graphics/plus.gif">
			<img title="Größe ändern" onmousedown="start_resize(event);" onmouseout="this.style.cursor='default';" onmouseup="deactivate();" onmouseover="this.style.cursor='se-resize';" style="position: absolute; bottom:0px; right:0px" src="graphics/resize.gif">
		</div></div>
</div>

<table>
<? if($this->Docu->activeframe[0]['variable_freetexts']){ ?>
	<tr>
  	<td>&nbsp;</td>
  </tr>
	<tr align="center"> 
    <td colspan="2"> 
      <input class="button" type="button" value="Freitext hinzufügen" onclick="addfreetext();">
    </td>
  </tr>
<? } ?>
	<tr>
  	<td>&nbsp;</td>
  </tr>
  <tr align="center"> 
    <td colspan="2"> 
      <input class="button" type="button" name="zurueck" value="zurück zum Druckausschnitt" onclick="goback();">
      <input class="button" type="button" name="drucken" value="Drucken" onclick="print();">
    </td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="action" value="">
<input type="hidden" name="last_freetext_id" value="0">
<input type="hidden" name="active_freetext" value="">
<input type="hidden" name="startx" value="">
<input type="hidden" name="starty" value="">
<input type="hidden" name="startheight" value="">
<input type="hidden" name="startwidth" value="">
<input type="hidden" name="vorschauzoom" value="<? echo $this->formvars['vorschauzoom']; ?>">
<input type="hidden" name="go_plus" value="">
<input type="hidden" name="worldprintwidth" value="<? echo $this->formvars['worldprintwidth'] ?>">
<input type="hidden" name="worldprintheight" value="<? echo $this->formvars['worldprintheight'] ?>">
<input type="hidden" name="center_x" value="<?php echo $this->formvars['center_x']; ?>">
<input type="hidden" name="center_y" value="<?php echo $this->formvars['center_y']; ?>">
<input type="hidden" name="refpoint_x" value="<?php echo $this->formvars['refpoint_x']; ?>">
<input type="hidden" name="refpoint_y" value="<?php echo $this->formvars['refpoint_y']; ?>">
<input type="hidden" name="format" value="<?php echo $this->formvars['format']; ?>">
<input type="hidden" name="printscale" value="<?php echo $this->formvars['printscale']; ?>">
<input type="hidden" name="angle" value="<?php echo $this->formvars['angle']; ?>">
<input type="hidden" name="referencemap" value="<?php echo $this->formvars['referencemap']; ?>">
<input type="hidden" name="legend_extra" value="<?php echo $this->formvars['legend_extra']; ?>">
<input type="hidden" name="minx" value="<?php echo $this->map->extent->minx; ?>">
<input type="hidden" name="miny" value="<?php echo $this->map->extent->miny; ?>">
<input type="hidden" name="maxx" value="<?php echo $this->map->extent->maxx; ?>">
<input type="hidden" name="maxy" value="<?php echo $this->map->extent->maxy; ?>">
<input type="hidden" name="mapwidth" value="<?php echo $this->map->width; ?>">
<input type="hidden" name="mapheight" value="<?php echo $this->map->height; ?>">
<input type="hidden" name="aktiverRahmen" value="<?php echo $this->formvars['aktiverRahmen']; ?>">

<input type="hidden" name="mapwidth" value="<?php echo $this->Document->activeframe[0]['mapwidth']; ?>">
<input type="hidden" name="mapheight" value="<?php echo $this->Document->activeframe[0]['mapheight']; ?>">

<? # Durchschleifen der vom Nutzer eingegebenen Freitexte 
	 for($j = 0; $j < count($this->Docu->activeframe[0]['texts']); $j++){		?>			
			<input type="hidden" name="freetext_<? echo $this->Docu->activeframe[0]['texts'][$j]['id']; ?>" value="<? echo $this->formvars['freetext_'.$this->Docu->activeframe[0]['texts'][$j]['id']]; ?>">
<? } ?>

<? if($this->formvars['loadmapsource'] == 'Post'){ ?>
	<input type="hidden" name="go" value="Externer_Druck">
	<input type="hidden" name="loadmapsource" value="<? echo $this->formvars['loadmapsource']; ?>">	
<? }else{ ?>
	<input type="hidden" name="go" value="Druckausschnittswahl">
	<input type="hidden" name="map_factor" value="<? echo $this->formvars['map_factor'] ?>">
<? } ?>

<!-- für den externen Druck -->
<input type="hidden" name="loadmapsource" value="<? echo $this->formvars['loadmapsource']; ?>">	
<input type="hidden" name="post_width" value="<? echo $this->formvars['post_width'] ?>">
<input type="hidden" name="post_height" value="<? echo $this->formvars['post_height'] ?>">
<input type="hidden" name="post_epsg" value="<? echo $this->formvars['post_epsg'] ?>">
<input type="hidden" name="post_map_factor" value="<? echo $this->formvars['post_map_factor'] ?>">

<? 
	$i = 0;
  while($this->formvars['layer'][$i]['name'] != '') { ?>
		<input type="hidden" name="layer[<? echo $i ?>][name]" value="<? echo $this->formvars['layer'][$i][name]; ?>">
		<input type="hidden" name="layer[<? echo $i ?>][epsg_code]" value="<? echo $this->formvars['layer'][$i][epsg_code]; ?>">
		<input type="hidden" name="layer[<? echo $i ?>][minscale]" value="<? echo $this->formvars['layer'][$i][minscale]; ?>">
		<input type="hidden" name="layer[<? echo $i ?>][maxscale]" value="<? echo $this->formvars['layer'][$i][maxscale]; ?>">
		<input type="hidden" name="layer[<? echo $i ?>][connection]" value="<? echo $this->formvars['layer'][$i][connection]; ?>">
		<input type="hidden" name="layer[<? echo $i ?>][transparency]" value="<? echo $this->formvars['layer'][$i][transparency]; ?>">
<?	$i++;
	}?>

