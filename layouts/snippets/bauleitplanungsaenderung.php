
<script language="JavaScript">
<!--

function send(){
	if(document.GUI.newpathwkt.value == ''){
		if(document.GUI.newpath.value == ''){
			alert('Geben Sie ein Polygon an.');
		}
		else{
			document.GUI.newpathwkt.value = buildwktpolygonfromsvgpath(document.GUI.newpath.value);
			document.GUI.go_plus.value = 'Senden';
			document.GUI.submit();
		}
	}
	else{
		document.GUI.go_plus.value = 'Senden';
		document.GUI.submit();
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
    <td rowspan="11">&nbsp;</td>
    <td colspan="2" rowspan="11"> 
      <?php
				include(LAYOUTPATH.'snippets/SVG_polygon_query_area.php')
			?>
    </td>
  </tr>
  <tr> 
    <td><p><span class="fett">Schritt 1: </span></p>
      <p>Umfahren Sie die betreffende Fl&auml;che per Mausklick!</p>
      <p>(<i>Es wird ein Polygon beschrieben!</i>)</p></td>
  </tr>
  <tr> 
    <td><hr align="center" noshade></td>
  </tr>
  <tr> 
    <td><p><span class="fett">Schritt 2:</span></p></td>
  </tr>
  <tr>  
  	<td><p>E-mail:</p><input type="text" name="email" value=""></td>
  </tr>
  <tr>  
  	<td><p>B-Plan-Nummer:</p><input type="text" name="bplannumber" value=""></td>
  </tr>
  <tr>  
  	<td><p>Nutzer:</p><input type="text" name="user" value="<? echo $this->user->Name; ?>" readonly="true"></td>
  </tr>
  <tr>  
  	<td><p>Hinweis:</p><textarea name="hinweis" ></textarea></td>
  </tr>
  <tr>  
  	<td><p>Bemerkung:</p><textarea name="bemerkung" ></textarea></td>
  </tr>
  <tr> 
    <td><hr align="center" noshade></td>
  </tr>
  <tr> 
    <td align="center"> <input type="button" name="senden" value="Senden" onclick="send();"> </td>
  </tr>
  <tr>
  	<td></td>
  	<td align="right"><input type="checkbox" name="always_draw" value="1" <?if($always_draw == 1 OR $always_draw == 'true')echo 'checked'; ?>>&nbsp;weiterzeichnen&nbsp;&nbsp;</td>
  </tr>
</table>
<INPUT TYPE="HIDDEN" NAME="layer_id" VALUE="">
<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="">
<INPUT TYPE="hidden" NAME="fromwhere" VALUE="">
<INPUT TYPE="hidden" NAME="area" VALUE="">
<INPUT TYPE="HIDDEN" NAME="minx" VALUE="<?php echo $this->map->extent->minx; ?>"> 
<INPUT TYPE="HIDDEN" NAME="miny" VALUE="<?php echo $this->map->extent->miny; ?>"> 
<INPUT TYPE="HIDDEN" NAME="maxx" VALUE="<?php echo $this->map->extent->maxx; ?>"> 
<INPUT TYPE="HIDDEN" NAME="maxy" VALUE="<?php echo $this->map->extent->maxy; ?>"> 
<INPUT TYPE="HIDDEN" NAME="scale" VALUE="<?php echo $scale; ?>">    
<INPUT TYPE="HIDDEN" NAME="go" VALUE="bauleitplanung" >
<INPUT TYPE="HIDDEN" NAME="go_plus" VALUE="" >
