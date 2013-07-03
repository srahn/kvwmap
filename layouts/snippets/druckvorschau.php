
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

function activate(evt){
	if(!evt)evt = window.event; // For IE
	id = (evt.target) ? evt.target.id : evt.srcElement.id;
	if(id){
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
		//evt.preventDefault();
		freetext = document.getElementById(document.GUI.active_freetext.value);
		freetext.style.border='2px dashed grey';
		freetext.style.cursor='move';
		freetext.style.left = (evt.clientX - document.getElementById('main').offsetLeft - document.GUI.startx.value) + 'px';
		freetext.style.top = (evt.clientY - document.getElementById('main').offsetTop - document.GUI.starty.value) + 'px';
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

<?php
	if ($this->Meldung=='') {
	  $bgcolor=BG_FORM;
	}
	else {
	  $bgcolor=BG_FORMFAIL;
		showAlert('Fehler bei der Eingabe:\n'.$this->Meldung);
}
 ?> <strong><font size="+1"> </font></strong> 
<table border="0" cellspacing="0" cellpadding="2">
  <tr align="center"> 
    <td colspan="2"><strong><font size="+1"> 
      <?php echo $this->titel; ?><br><br></font></strong>
    </td>
  </tr>
</table>
<div id="main" style="position: relative; left:0px; top:0px;" onmousedown="preventflickering(event);" onmousemove="mousemove(event);">
<table border="0" width="<? echo $this->Document->width; ?>" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left">
			<img width="595" src="<? echo $this->previewfile ?>">
		</td>
	</tr>
</table>


<div onmouseover="this.style.border='2px dotted grey'; this.style.cursor='move';" onmouseout="this.style.border='none';" onmousedown="activate(event);" onmouseup="deactivate();" title="Freitext" id="text" class="" style="background: url(graphics/leer.gif) repeat;; position: absolute; visibility: visible; left: 111px; top: 111px; padding:3px">
	<textarea style="border: none; background-color:transparent; font-size: 8px; font-family: Arial, Helvetica;">Hallo hallo test test</textarea>
</div>

</div>

<table>
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
<input type="text" name="active_freetext" value="">
<input type="text" name="startx" value="">
<input type="text" name="starty" value="">
<input type="hidden" name="vorschauzoom" value="<? echo $this->formvars['vorschauzoom']; ?>">
<input type="hidden" name="go_plus" value="">
<input type="hidden" name="worldprintwidth" value="<? echo $this->formvars['worldprintwidth'] ?>">
<input type="hidden" name="worldprintheight" value="<? echo $this->formvars['worldprintheight'] ?>">
<input type="hidden" name="center_x" value="<?php echo $this->formvars['center_x']; ?>">
<input type="hidden" name="center_y" value="<?php echo $this->formvars['center_y']; ?>">
<input type="hidden" name="format" value="<?php echo $this->formvars['format']; ?>">
<input type="hidden" name="printscale" value="<?php echo $this->formvars['printscale']; ?>">
<input type="hidden" name="angle" value="<?php echo $this->formvars['angle']; ?>">
<input type="hidden" name="referencemap" value="<?php echo $this->formvars['referencemap']; ?>">
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
			<input type="hidden" name="freetext<? echo $this->Docu->activeframe[0]['texts'][$j]['id']; ?>" value="<? echo $this->formvars['freetext'.$this->Docu->activeframe[0]['texts'][$j]['id']]; ?>">
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

