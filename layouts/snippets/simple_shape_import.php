<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--
	
	function save(){
		if(document.GUI.table_name.value == '' || document.GUI.table_name.value == '<Tabellenname>'){
			alert('Bitte geben Sie einen Tabellennamen an.')
		}
		else{
			document.GUI.go_plus.value = 'speichern';
			document.GUI.submit();
		}
	}
  
//-->
</script>

<table border="0" cellpadding="5" cellspacing="3" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="3"><strong><font size="+1"><?php echo $this->titel; ?></font></strong></td>
  </tr>
  <? if($this->shape->formvars['zipfile'] == ''){ ?>
  <tr>
  	<td>&nbsp;</td>
  	<td>Sie können hier ein zip-Archiv hochladen, welches <br>die 3 Dateien vom Typ dbf, shp und shx enthält.</td>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
		<td align="center" style="border-bottom:1px solid #C3C7C3"><b>Zip-Archiv:</b>
		<input class="button" type="file" name="zipfile" size="12">
		<input class="button" type="submit" name="goplus" value="Datei laden"></td>
		<td>&nbsp;</td>
	</tr>
	<? }
			else{ ?> 
  <tr>
  	<td>&nbsp;</td>
  	<td align="center">  		
  		<table border="0" width="400">
  			<tr>
  				<td colspan="2" align="center"><b>dbf-Datei</b></td>
  				<td>&nbsp;</td>
  				<td colspan="2" align="center"><b>PostgreSQL-Tabelle</b></td>
  			</tr>
  			<tr>
  				<td colspan="2" align="center"><input name="dbffile" type="text" value="<? echo $this->shape->dbf->file; ?>" readonly></td>
  				<td>==></td>
  				<td colspan="1" align="center" height="35"><input name="table_name" type="text" value="<Tabellenname>" size="15" class="input"></td>
  				<td colspan="1" align="center" height="35">
  					<select name="tabellen" onchange="document.GUI.table_name.value = document.GUI.tabellen.value;">
  						<option value="">--- Auswahl ---</option>
  						<?
  						for($i = 0; $i < count($this->shape->tables); $i++){
  							echo '<option value="'.$this->shape->tables[$i]['tabellenname'].'">'.$this->shape->tables[$i]['tabellenname'].'</option>';
  						}
  						?>
  					</select>
  				</td>
  			</tr>
  			<? for($i = 0; $i < count($this->shape->dbf->header); $i++){ ?>
				<tr>
					<td colspan="5" align="center">
						<input name="dbf_name_<? echo $this->shape->dbf->header[$i][0]; ?>" type="text" value="<? echo $this->shape->dbf->header[$i][0]; ?>" class="input" readonly size="20">
						<input name="dbf_type_<? echo $this->shape->dbf->header[$i][0]; ?>" type="text" value="<? echo $this->shape->dbf->header[$i]['type']; ?>" class="input" readonly size="10">
					</td>
				</tr>
  			<? } ?>
  		</table>
  	</td>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  	<td>
  		<table border="0">
  			<tr>
  				<td height="30"><b>Optionen:</b></td>
  			</tr>
  			<tr>
  				<td><input type="radio" name="table_option" value="-c" checked>Tabelle neu anlegen</td>
  				<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
  				<td>srid:&nbsp;<input type="text" name="srid" size="5" value="<? echo EPSGCODE; ?>"></td>
  			</tr>
  			<tr>
  				<td><input type="radio" name="table_option" value="-d">Tabelle überschreiben</td>
  				<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
  				<td><input type="checkbox" name="gist">&nbsp;GiST-Index erzeugen</td>
  			</tr>
  			<tr>
  				<td><input type="radio" name="table_option" value="-a">Daten anhängen</td>
  				<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
  			</tr>
  		</table>
  	</td>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td colspan="3" align="center"><input name="save1" value="importieren" type="button" class="button" onclick="save();"></td>
  </tr>
  <? } ?>
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
</table>

<input type="hidden" name="go" value="simple_SHP_Import">
<input type="hidden" name="go_plus" value="">


