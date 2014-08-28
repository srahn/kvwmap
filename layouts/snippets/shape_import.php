<?php
 # 2008-01-22 pkvvm
  include(LAYOUTPATH.'languages/shape_import_'.$this->user->rolle->language.'.php');
 ?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

	function update_inputs(name){
		if(document.getElementsByName('check_'+name)[0].checked == true){
			document.getElementsByName('sql_name_'+name)[0].disabled = false;
			document.getElementsByName('sql_type_'+name)[0].disabled = false;
			document.getElementById('pkey_'+name).disabled = false;
		}
		else{
			document.getElementsByName('sql_name_'+name)[0].disabled = true;
			document.getElementsByName('sql_type_'+name)[0].disabled = true;
			document.getElementById('pkey_'+name).disabled = true;
		}
	}
	
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
    <td colspan="3"><h2><?php echo $strTitle; ?></h2></td>
  </tr>
  <? if($this->shape->formvars['zipfile'] == ''){ ?>
  <tr>
  	<td>&nbsp;</td>
  	<td><?php echo $strLoadZipArchieve; ?></td>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
		<td align="center" style="border-bottom:1px solid #C3C7C3"><span class="fett"><?php echo $strZipArchive; ?></span>
		<input class="button" type="file" name="zipfile" size="12">
		<input class="button" type="submit" name="goplus" value="<?php echo $strLoadData; ?>"></td>
		<td>&nbsp;</td>
	</tr>
	<? }
			else{ ?> 
  <tr>
  	<td>&nbsp;</td>
  	<td>  		
  		<table border="0">
  			<tr>
  				<td colspan="2" align="center"><span class="fett">dbf-Datei</span></td>
  				<td>&nbsp;</td>
  				<td colspan="4" align="center"><span class="fett">PostgreSQL-Tabelle</span></td>
  			</tr>
  			<tr>
  				<td colspan="2" align="center"><input name="dbffile" type="text" value="<? echo $this->shape->dbf->file; ?>" readonly></td>
  				<td>&nbsp;</td>
  				<td colspan="4" align="center" height="35"><input name="table_name" type="text" value="<Tabellenname>" size="15" class="input"></td>
  			</tr>
  			<? for($i = 0; $i < count($this->shape->dbf->header); $i++){ ?>
				<tr>
					<td><input name="dbf_name_<? echo $this->shape->dbf->header[$i][0]; ?>" type="text" value="<? echo $this->shape->dbf->header[$i][0]; ?>" readonly size="20"></td>
					<td><input name="dbf_type_<? echo $this->shape->dbf->header[$i][0]; ?>" type="text" value="<? echo $this->shape->dbf->header[$i]['type']; ?>" readonly size="10"></td>
					<td>==></td>
					<td><input name="check_<? echo $this->shape->dbf->header[$i][0] ?>" type="checkbox" onclick="update_inputs('<? echo $this->shape->dbf->header[$i][0]; ?>');" checked></td>
					<td><input name="sql_name_<? echo $this->shape->dbf->header[$i][0]; ?>" type="text" value="<? echo $this->shape->dbf->header[$i][0]; ?>" size="20"></td>
					<td><input name="sql_type_<? echo $this->shape->dbf->header[$i][0]; ?>" type="text" value="<? echo $this->shape->dbf->header[$i]['type']; ?>" size="10"></td>
					<td><input name="primary_key" id="pkey_<? echo $this->shape->dbf->header[$i][0]; ?>" title="Primärschlüssel" type="radio" value="<? echo $this->shape->dbf->header[$i][0]; ?>"></td>
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
  				<td height="30"><span class="fett">Optionen:</span></td>
  			</tr>
  			<tr>
  				<td><input type="radio" name="table_option" value="-c" checked>Tabelle neu anlegen</td>
  				<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
  				<td>srid:&nbsp;<input type="text" name="srid" size="5" value="<? echo EPSGCODE; ?>"></td>
  				<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
  				<td><input name="primary_key" type="radio" value="gid" checked>&nbsp;mit gid-Index</td>
  			</tr>
  			<tr>
  				<td><input type="radio" name="table_option" value="-d">Tabelle überschreiben</td>
  				<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
  				<td><input type="checkbox" name="gist">&nbsp;GiST-Index erzeugen</td>
  			</tr>
  			<tr>
  				<td><input type="radio" name="table_option" value="-a">Daten anhängen</td>
  				<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
  				<td><input type="checkbox" name="oids" checked>&nbsp;mit oids</td>
  			</tr>
  			<tr>
				  <td><input type="radio" name="table_option" value="-u">Daten aktualisieren</td>
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

<input type="hidden" name="go" value="SHP_Import">
<input type="hidden" name="go_plus" value="">


