
<script language="JavaScript">
<!--

function save_road(){
	if(document.GUI.newpathwkt.value == ''){
		if(document.GUI.newpath.value == ''){
			alert('Geben Sie ein Polygon an.');
		}
		else{
			document.GUI.newpathwkt.value = buildwktpolygonfromsvgpath(document.GUI.newpath.value);
			document.GUI.go_plus.value = 'strasse_speichern';
			document.GUI.submit();
		}
	}
	else{
		document.GUI.go_plus.value = 'strasse_speichern';
		document.GUI.submit();
	}
}

function save_buffer(){
	if(document.GUI.newpathwkt.value == ''){
		if(document.GUI.newpath.value == ''){
			alert('Geben Sie ein Polygon an.');
		}
		else{
			document.GUI.newpathwkt.value = buildwktpolygonfromsvgpath(document.GUI.newpath.value);
			document.GUI.go_plus.value = 'buffer_speichern';
			document.GUI.submit();
		}
	}
	else{
		document.GUI.go_plus.value = 'buffer_speichern';
		document.GUI.submit();
	}
}

function createbuffer(){
	top.document.GUI.secondpoly.value = true;
  if(top.document.GUI.newpathwkt.value != ""){
  	top.ahah("index.php", "go=spatial_processing&path1="+top.document.GUI.newpathwkt.value+"&width="+top.document.GUI.buffersize.value+"&operation=buffer_ring&resulttype=svgwkt", new Array(top.document.GUI.result, ""), new Array("setvalue", "execute_function"));
  }
  else{
  	if(top.document.GUI.newpath.value != ""){
  		newpath = buildwktpolygonfromsvgpath(top.document.GUI.newpath.value);
  		top.ahah("index.php", "go=spatial_processing&path1="+newpath+"&width="+top.document.GUI.buffersize.value+"&operation=buffer_ring&resulttype=svgwkt", new Array(top.document.GUI.result, ""), new Array("setvalue", "execute_function"));
  	}
  }
}

function buildwktpolygonfromsvgpath(svgpath){
	var koords;
	wkt = "POLYGON((";
	parts = svgpath.split("M");
	for(j = 1; j < parts.length; j++){
		if(j > 1){
			wkt = wkt + "),("
		}
		koords = ""+parts[j];
		coord = koords.split(" ");
		wkt = wkt+coord[1]+" "+coord[2];
		for(var i = 3; i < coord.length-1; i++){
			if(coord[i] != ""){
				wkt = wkt+","+coord[i]+" "+coord[i+1];
			}
			i++;
		}
	}
	wkt = wkt+"))";
	return wkt;
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
?>

<table style="border: 1px solid; border-color: #eeeeee; border-left: none; border-right: none" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr> 
    <td> <div align="center"></div></td>
    <td colspan="3"><div align="center"><h2><?php echo $this->titel; ?></h2> 
      </div></td>
  </tr>
  <tr> 
    <td rowspan="8">&nbsp;</td>
    <td colspan="2" rowspan="8"> 
      <?php
				include(LAYOUTPATH.'snippets/SVG_polygon_query_area.php')
			?>
    </td>
  </tr>
  
  <tr>
  	<td>Geometrieabfrage-Layer:<br>
  		<select name="layer_id" onchange="document.GUI.submit();">
  			<?
  				for($i = 0; $i < count($this->queryable_postgis_layers['ID']); $i++){
  					echo '<option';
  					if($this->formvars['layer_id'] == $this->queryable_postgis_layers['ID'][$i]){echo ' selected';}
  					echo ' value="'.$this->queryable_postgis_layers['ID'][$i].'">'.$this->queryable_postgis_layers['Bezeichnung'][$i].'</option>';
  				}
  			?>
  		</select> 
  	</td>
  </tr>
  
  <tr> 
    <td><hr align="center" noshade></td>
  </tr>
  <tr>  
  	<td align="center">Fläche:<br><input size="12" type="text" name="area" value="">&nbsp;m<sup>2</sup></td>
  </tr>
  <tr> 
    <td align="center"> <input type="button" name="senden" value="Straßenabschnitt speichern" onclick="save_road();"> </td>
  </tr>
  <tr> 
    <td align="center"> <input type="text" size="1" name="buffersize" value="50">m&nbsp;<input type="button" name="create_buffer" value="Randgeometrie erzeugen" onclick="createbuffer();"> </td>
  </tr>
  <tr> 
    <td align="center"> <input type="button" name="senden" value="Grundstücksbereiche speichern" onclick="save_buffer();"> </td>
  </tr>
  <tr> 
    <td><hr align="center" noshade></td>
  </tr>
  <tr>
  	<td></td>
  	<td align="right"><input type="checkbox" name="always_draw" value="1" <?if($always_draw == 1 OR $always_draw == 'true')echo 'checked'; ?>>&nbsp;weiterzeichnen&nbsp;&nbsp;</td>
  </tr>
</table>
<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="<? echo $this->formvars['columnname']; ?>">
<INPUT TYPE="HIDDEN" NAME="fromwhere" VALUE="<? echo $this->formvars['fromwhere']; ?>">
<INPUT TYPE="HIDDEN" NAME="minx" VALUE="<?php echo $this->map->extent->minx; ?>"> 
<INPUT TYPE="HIDDEN" NAME="miny" VALUE="<?php echo $this->map->extent->miny; ?>"> 
<INPUT TYPE="HIDDEN" NAME="maxx" VALUE="<?php echo $this->map->extent->maxx; ?>"> 
<INPUT TYPE="HIDDEN" NAME="maxy" VALUE="<?php echo $this->map->extent->maxy; ?>"> 
<INPUT TYPE="HIDDEN" NAME="scale" VALUE="<?php echo $scale; ?>">    
<INPUT TYPE="HIDDEN" NAME="go" VALUE="anliegerbeitraege" >
<INPUT TYPE="HIDDEN" NAME="go_plus" VALUE="" >
    	