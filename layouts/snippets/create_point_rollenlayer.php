<?php
  include(LAYOUTPATH.'languages/punktliste_anzeigen_'.$this->user->rolle->language.'.php');
 ?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

function load(){
	if(document.GUI.epsg.value == ''){
		alert('Bitte geben Sie ein Koordinatensystem (EPSG-Code) an.');
	}else{
		document.GUI.go_plus.value = 'Datei laden';
		document.GUI.submit();
	}
}

function show(){
	var x, y, label;
	for(i = 0; i < <? echo count($this->data_import_export->columns); ?>; i++){
		if(document.getElementById('column'+i).value == 'x')x = true;
		if(document.getElementById('column'+i).value == 'y')y = true;
	}
	if(x && y){
		document.GUI.go_plus.value = 'Anzeigen';
		document.GUI.submit();
	}
	else alert('Bitte wählen Sie aus, welche Spalte als Rechtswert und welche als Hochwert verwendet werden soll.');
}
  
//-->
</script>

<table border="0" cellpadding="5" cellspacing="3" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="3"><h2><?php echo $strTitle; ?></h2></td>
  </tr>
  <? if($this->data_import_export->pointfile == ''){ ?>
  <tr>
  	<td>&nbsp;</td>
  	<td colspan="2"><?php echo $strHint; ?></td>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
		<td align="right"><span class="fett"><?php echo $strFile; ?></span></td>
		<td><input type="file" name="pointfile"></td>
		<td>&nbsp;</td>
	</tr>
	<tr align="center">
		<td>&nbsp;</td>
    <td align="right" style="border-bottom:1px solid #C3C7C3"><span class="fett"><?php echo $strMapProjection; ?>:&nbsp;</span></td>
    <td align="left" style="border-bottom:1px solid #C3C7C3">
      <select name="epsg">
		    <option value="">--<?php echo $this->strChoose; ?>--</option>
		    <?
  			foreach($this->epsg_codes as $epsg_code){
  				echo '<option value="'.$epsg_code['srid'].'">';
  				echo $epsg_code['srid'].': '.$epsg_code['srtext'];
  				echo "</option>\n";
  			}
  			?>
		  </select>
    </td>
    <td>&nbsp;</td>
  </tr>
	
	<tr>
		<td colspan="3" align="center"><input class="button" type="button" name="save1" value="Datei laden" onclick="load();"></td>
	</tr>
	<? }
	else{ ?> 
  <tr>
		<td>
	<? if(count($this->data_import_export->columns) > 2){ ?>
			<table width="100%">
				<tr>
					<td>Es wurden <? echo count($this->data_import_export->columns); ?> Spalten erkannt.<br>Sie können nun festlegen, welche Spalten als Koordinaten und welche als Beschriftung verwendet werden sollen.</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
			</table>
			<table cellpadding="5" cellspacing="3" border="1" style="border-collapse:collapse">
				<tr>
					<? for($i = 0; $i < count($this->data_import_export->columns); $i++){ ?>
					<td><? echo $this->data_import_export->columns[$i]; ?></td>
					<? } ?>
				</tr>
				<tr>
		<? for($i = 0; $i < count($this->data_import_export->columns); $i++){ ?>
					<td>
						<select id="column<? echo $i; ?>" name="column<? echo $i; ?>">
							<option value="">-- Auswahl --</option>
							<option value="x">Rechtswert</option>
							<option value="y">Hochwert</option>
							<option value="label">Beschriftung</option>
						</select>
					</td>
		<? 	} ?>
				</tr>
			</table>
			<br>
			<table width="500px">
				<tr>
					<td colspan="3" align="center"><input class="button" type="button" name="save2" value="Anzeigen" onclick="show();"></td>
				</tr>
			</table>
			<? }
					else echo 'Kein geeignetes Trennzeichen gefunden. Erlaubte Trennzeichen: ";" "," und Leerzeichen' ?>
			<input type="hidden" name="epsg" value="<? echo $this->formvars['epsg']; ?>">
			<input type="hidden" name="pointfile" value="<? echo $this->data_import_export->pointfile; ?>">
			<input type="hidden" name="delimiter" value="<? echo $this->data_import_export->delimiter; ?>">
		</td>
  </tr>
  <? } ?>
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
</table>

<input type="hidden" name="go" value="Punktliste_Anzeigen">
<input type="hidden" name="go_plus" value="">


