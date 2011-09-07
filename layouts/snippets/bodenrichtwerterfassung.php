
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
  <tr align="center"> 
    <td colspan="6"><strong><font size="+1"><?php echo $this->titel; ?></font></strong></td>
  </tr>
  <tr> 
    <td rowspan="13">&nbsp; </td>
    <td rowspan="13"> 
    <?php
	  # Wenn ein Polygon übergeben wird, wird es in SVG mit dargestellt.
      include(LAYOUTPATH.'snippets/SVG_polygon_and_point.php');
    ?>
    </td>
    <td colspan="3"><hr align="center" noshade></td>
  <tr> 
    <td colspan="3">Gemeinde/ID:<br /> 
  <?php
    $this->GemFormObj->outputHTML();
    echo $this->GemFormObj->html;
  ?>
    </td>
  <? if(BODENRICHTWERTTYP != 'punkt'){ ?>
  <tr> 
    <td colspan="3">Zonennummer: 
      <input name="zonennr" type="text" id="Zonennr" value="<?php echo $this->formvars['zonennr']; ?>" size="5" maxlength="5"> 
    </td>
  </tr>
  <? } ?>
  <tr> 
    <td colspan="3">Standort: 
      <input name="standort" type="text" value="<?php echo $this->formvars['standort']; ?>" size="25" maxlength="255"> 
    </td>
  <tr> 
    <td colspan="3">Richtwertdef.: 
      <input name="richtwertdefinition" type="text" id="richtwertdefinition" value="<?php echo $this->formvars['richtwertdefinition']; ?>" size="21" maxlength="50"> 
    </td>
  <tr> 
    <td colspan="3">Bodenwert: 
      <input name="bodenwert" type="text" id="Bodenwert" value="<?php echo $this->formvars['bodenwert']; ?>" size="4" maxlength="4">
      [&euro;/m&sup2;] </td>
  <tr> 
    <td colspan="3">Art der Erschlie&szlig;ung: 
      <?php 
                $FormatWerte = array('ohne','[ortsuebliche Erschl.]','(vollerschlossen)');
                $FormatBez = array('ohne','[ortsübliche Erschl.]','(vollerschlossen)'); 
                $Blattformat = new FormObject('erschliessungsart','select',$FormatWerte,array($this->formvars['erschliessungsart']),$FormatBez,1,$maxlenght,$multiple,NULL);
                $Blattformat->OutputHTML();
                echo $Blattformat->html;
              ?>
    </td>
  <tr> 
    <td colspan="3">Sanierungsgebiet: 
      <?php 
                $FormatWerte = array('ohne','Sanierungsanfangswert','Sanierungsendwert');               
                $FormatBez = array('ohne','Sanierungsanfangswert','Sanierungsendwert'); 
                $Blattformat = new FormObject('sanierungsgebiete','select',$FormatWerte,array($this->formvars['sanierungsgebiete']),$FormatBez,1,$maxlenght,$multiple,NULL);
                $Blattformat->OutputHTML();
                echo $Blattformat->html;
              ?>
    </td>
  <tr> 
    <td colspan="3"><input type="checkbox" name="sichtbarkeit" value="1" <?php if ($this->formvars['sichtbarkeit']!='') { ?> checked<?php } ?>>
      sichtbar</td>
  <tr> 
    <td>Stichtag:</td>
    <td>
<div align="right">31.12.</div></td>
    <td>
    <div align="left">
    <input name="datum" type="text" value="<?php echo $this->formvars['datum']; ?>" size="4" maxlength="4">
    </div></td>
  <tr>	
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td colspan="3">Geometrie übernehmen von:<br>
  		<select name="layer_id" onchange="document.GUI.submit();">
  			<option value="">--- Auswahl ---</option>
  			<?
  				for($i = 0; $i < count($this->queryable_vector_layers['ID']); $i++){
  					echo '<option';
  					if($this->formvars['layer_id'] == $this->queryable_vector_layers['ID'][$i]){echo ' selected';}
  					echo ' value="'.$this->queryable_vector_layers['ID'][$i].'">'.$this->queryable_vector_layers['Bezeichnung'][$i].'</option>';
  				}
  			?>
  		</select> 
  	</td>
  </tr>
  <tr> 
    <td colspan="3"><hr align="center" noshade></td>
  <tr> 
    <td width="25" colspan="2">&nbsp; </td>
    <td colspan="3"><table border="0">
        <tr> 
          <td><input type="reset" name="reset" value="Zurücksetzen"></td>
          <td><input type="button" name="senden" value="Senden" onclick="send();"></td>
        </tr>
      </table></td>
  </tr>
</table>
<input type="hidden" name="oid" value="<? echo $this->formvars['oid']; ?>">
<input type="hidden" name="go" value="Bodenrichtwertformular">
<input type="hidden" name="go_plus" value="">
<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="<?php echo $this->formvars['columnname']; ?>">
<INPUT TYPE="HIDDEN" NAME="fromwhere" VALUE="<? echo $this->formvars['fromwhere']; ?>">

