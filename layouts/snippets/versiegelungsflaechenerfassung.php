
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

<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr> 
    <td> <div align="center"></div></td>
    <td colspan="3"><div align="center"><strong><font size="+1"><?php echo $this->titel; ?></font></strong> 
      </div></td>
  </tr>
  <tr> 
    <td rowspan="5">&nbsp;</td>
    <td colspan="2" rowspan="5"> 
      <?php
				include(LAYOUTPATH.'snippets/SVG_polygon_query_area.php')
			?>
    </td>
    <td><hr align="center" noshade></td>
  </tr>
  <tr> 
    <td><p><strong>Schritt 1: </strong></p>
      <p>Umfahren Sie die betreffende Fl&auml;che per Mausklick!</p>
      <p>(<i>Es wird ein Polygon beschrieben!</i>)</p></td>
  </tr>
  <tr>
  	<td><p>Fläche:</p><input size="12" type="text" name="area" value="<?echo $this->formvars['area']?>">&nbsp;m<SUP>2</SUP></td>
  </tr>
  <tr> 
    <td><hr align="center" noshade></td>
  </tr>
  <tr> 
    <td><p><strong>Schritt 2:</strong></p>
      <p>Angabe des auszuweisenden Versiegelungsgrades:</p>
      <select name="versiegelungsgrad">
        <option value="1" <?php if ($this->formvars['versiegelungsgrad']=='0' || $this->formvars['versiegelungsgrad']=='1') { ?>selected<?php } ?>>Bauwerke</option>
        <option value="2" <?php if ($this->formvars['versiegelungsgrad']=='2') { ?>selected<?php } ?>>Asphalt</option>
        <option value="3" <?php if ($this->formvars['versiegelungsgrad']=='3') { ?>selected<?php } ?>>Beton</option>
        <option value="4" <?php if ($this->formvars['versiegelungsgrad']=='4') { ?>selected<?php } ?>>Pflaster</option>
        <option value="5" <?php if ($this->formvars['versiegelungsgrad']=='5') { ?>selected<?php } ?>>Rasengitter</option>
        <option value="6" <?php if ($this->formvars['versiegelungsgrad']=='6') { ?>selected<?php } ?>>Wassergebunden</option>
      </select> <p> 
        <?php
#				$this->GemFormObj->outputHTML();
#				echo $this->GemFormObj->html;
			?>
      </p></td>
  </tr>
  <tr> 
    <td><hr align="center" noshade></td>
  </tr>
  <tr> 
    <td colspan="3"> <div align="left"> </div></td>
    <td align="left"> <input type="button" name="senden" value="Senden" onclick="send();"> </td>
  </tr>
</table>
<INPUT TYPE="HIDDEN" NAME="layer_id" VALUE="">
<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="">
<INPUT TYPE="hidden" NAME="fromwhere" VALUE="">
<INPUT TYPE="HIDDEN" NAME="minx" VALUE="<?php echo $this->map->extent->minx; ?>"> 
<INPUT TYPE="HIDDEN" NAME="miny" VALUE="<?php echo $this->map->extent->miny; ?>"> 
<INPUT TYPE="HIDDEN" NAME="maxx" VALUE="<?php echo $this->map->extent->maxx; ?>"> 
<INPUT TYPE="HIDDEN" NAME="maxy" VALUE="<?php echo $this->map->extent->maxy; ?>"> 
<INPUT TYPE="HIDDEN" NAME="scale" VALUE="<?php echo $scale; ?>">    
<INPUT TYPE="HIDDEN" NAME="go" VALUE="Versiegelung" >
<INPUT TYPE="HIDDEN" NAME="go_plus" VALUE="" >
    	