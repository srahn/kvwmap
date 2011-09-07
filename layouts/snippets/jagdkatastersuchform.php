<script language="JavaScript">
<!--

function save(){
	if(document.GUI.search_name.value == '' && document.GUI.search_nummer.value == ''){
		alert("Geben Sie entweder einen Namen oder eine laufende Nummer an.");
	}
	else{
		document.GUI.go_plus.value = 'Suchen';
		document.GUI.submit();
	}
}

function update_form(art){
	if(art == 'jbe' || art == 'jbf' || art == 'agf' || art == 'atf' || art == 'slf'){
		document.getElementById('status').style.display = '';
	}
	else{
		document.getElementById('status').style.display = 'none';
	}
}

function intersect_flurst(){
	go = 'false';
	checkbox_name_obj = document.getElementsByName('checkbox_names');
	checkbox_name_string = checkbox_name_obj[0].value;
	checkbox_names = checkbox_name_string.split('|');
	for(i = 0; i < checkbox_names.length; i++){
		if(document.getElementsByName(checkbox_names[i])[0] != undefined && document.getElementsByName(checkbox_names[i])[0].checked == true){
			go = 'true';
		}
	}
	if(go == 'false'){
		alert('Es wurde kein Datensatz ausgewählt.');
	}
	else{
		document.GUI.go.value = 'jagdkatastereditor_Flurstuecke_Listen';
		document.GUI.submit();
	}
}

//-->
</script>

<br><h2><?php echo $this->titel; ?></h2>
<?php if ($this->Fehlermeldung!='') {
include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
?><p>
<table border="0" cellpadding="0" cellspacing="2">
  <tr>
    <td align="right"><strong>laufende Nummer:</strong>
    </td>
    <td><input name="search_nummer" type="text" value="<?php echo $this->formvars['search_nummer']; ?>" size="25" tabindex="1"></td>
  </tr>
  <tr>
    <td align="right"><strong>Name:</strong>
      </td>
    <td><input name="search_name" type="text" value="<?php echo $this->formvars['search_name']; ?>" size="25" tabindex="2"></td>
  </tr>
  <tr>
    <td align="right"><strong>Art:</strong>
      </td>
    <td>
    	<select name="search_art" onchange="update_form(this.value);">
    		<option value="">Alle</option>
  			<option <? if($this->formvars['search_art'] == 'ejb'){echo 'selected';} ?> value="ejb">Eigenjagdbezirk</option>
  			<option <? if($this->formvars['search_art'] == 'gjb'){echo 'selected';} ?> value="gjb">gem. Jagdbezirk</option>
  			<option <? if($this->formvars['search_art'] == 'tjb'){echo 'selected';} ?> value="tjb">Teiljagdbezirk</option>
  			<option <? if($this->formvars['search_art'] == 'sf'){echo 'selected';} ?> value="sf">Sonderfläche</option>
  			<option <? if($this->formvars['search_art'] == 'jbe'){echo 'selected';} ?> value="jbe">Enklave</option>
  			<option <? if($this->formvars['search_art'] == 'jbf'){echo 'selected';} ?> value="jbf">jagdbezirksfreie Fläche</option>
  			<option <? if($this->formvars['search_art'] == 'agf'){echo 'selected';} ?> value="agf">Angliederungsfläche</option>
  			<option <? if($this->formvars['search_art'] == 'atf'){echo 'selected';} ?> value="atf">Abtrennungsfläche</option>
  			<option <? if($this->formvars['search_art'] == 'slf'){echo 'selected';} ?> value="slf">Schmalfläche</option>
  		</select>
		</td>
  </tr>
  <tr id="status" style="display:<? if(in_array($this->formvars['search_art'], array('jbe', 'jbf', 'agf', 'atf', 'slf'))){ echo '';}else{echo 'none';} ?>" >
    <td align="right"><strong>Status:</strong></td>
    <td>
    	<select name="search_status">
  			<option <? if($this->formvars['search_status'] == 'false'){echo 'selected';} ?> value="false">aktuell</option>
  			<option <? if($this->formvars['search_status'] == 'true'){echo 'selected';} ?> value="true">historisch</option>
  			<option <? if($this->formvars['search_status'] == 'both'){echo 'selected';} ?> value="both">alle</option>
  		</select>
	</td>
  </tr>
  <tr>
  	<td colspan="2"><em>Zur nicht exakten Suche geben Sie den Platzhalter % ein.</em></td>
  </tr>
  <!--
  <tr>
    <td><strong>Gro&szlig; und Kleinschreibung beachten</strong>&nbsp;
    <input name="caseSensitive" type="checkbox" value="1"<?php if ($this->formvars['caseSensitive']) { ?> checked<?php } ?>><tr><td colspan="2"></td>
  <tr><td colspan="2"></tr>//-->
  <tr>
  	<td colspan="3" align="center">
			<br>
			<input type="hidden" name="go" value="jagdbezirke_auswaehlen">
			<input type="hidden" name="go_plus" value="">
			<input type="submit" onclick="save();" style="width: 0px;height: 0px;border: none">
			<input type="button" name="suchen" value="Suchen" onclick="javascript:save();" tabindex="6">
			<!-- &nbsp;<input type="submit" name="abbrechen" value="Abbrechen">&nbsp;<input type="reset" name="reset" value="Zur&uuml;cksetzen"> -->
			<br>
   	</td>
  </tr>
  <?php
  $anz = count($this->jagdbezirke);
  if ($anz > 0) {
   ?>
  <tr>
  	<td>&nbsp;</td>
  </tr>
	<tr>
    <td colspan="3" align="left">
		<table border="1" cellpadding="3" cellspacing="0">
      <tr bgcolor="<?php echo BG_DEFAULT ?>">
      	<td></td>
        <td align="center"><strong>lfd. Nummer</strong></td>
        <td align="center"><strong>Name</strong></td>
        <td align="center"><strong>Fläche</strong></td>
        <td align="center"><strong>Typ</strong></td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
      </tr>
  <?php
  for($i = 0; $i < $anz; $i++) {
  	$checkbox_names .= 'check_'.$this->jagdbezirke[$i]['oid'].'|';
  ?>
      <tr>
      	<td align="center"><input type="checkbox" name="check_<? echo $this->jagdbezirke[$i]['oid'] ?>"></td>
        <td align="center"><?php if ($this->jagdbezirke[$i]['art']=='ejb' OR $this->jagdbezirke[$i]['art']=='gjb') { echo $this->jagdbezirke[$i]['id']; } else { echo $this->jagdbezirke[$i]['jb_zuordnung']; } ?></td>
        <td align="center"><?php echo $this->jagdbezirke[$i]['name']; ?></td>
      	<td align="center"><?php echo $this->jagdbezirke[$i]['flaeche']; ?></td>
        <td align="center"><?php echo $this->jagdbezirke[$i]['art']; ?></td>
        <td align="center"><a href="index.php?go=jagdbezirk_show_data&oid=<? echo $this->jagdbezirke[$i]['oid'] ?>&search_nummer=<? echo $this->formvars['search_nummer']; ?>&search_name=<? echo $this->formvars['search_name']; ?>&search_art=<? echo $this->formvars['search_art']; ?>&search_status=<? echo $this->formvars['search_status']; ?>">Sachdatenanzeige</a></td>
        <td align="center"><a href="index.php?go=zoomtojagdbezirk&oid=<? echo $this->jagdbezirke[$i]['oid'] ?>&nummer=<? echo $this->jagdbezirke[$i]['id'] ?>">zur Karte</a></td>
        <td align="center">
        	<? if($this->Stelle->isFunctionAllowed('Jagdkataster')){ ?>
					<a href="index.php?go=jagdkatastereditor&oid=<? echo $this->jagdbezirke[$i]['oid']; ?>">Geometrie bearbeiten</a>
					<? } ?>
				</td>
      </tr>
	  <?php
	  }
	  ?>
	  </table>
	  <table cellpadding="3" cellspacing="0">
	  	<tr>
      	<td width="30" valign="top" align="center" valign="bottom"><img src="<? echo GRAPHICSPATH?>pfeil_unten-rechts.gif"></td>
      	<td height="29" valign="bottom" colspan="5"><a href="javascript:intersect_flurst();">enthaltene Flurstücke</a>&nbsp;&nbsp;<b>! Achtung dies kann u.U. sehr lange dauern !</b>&nbsp;&nbsp;</td>
      </tr>
    </table></td>
  </tr><?php
  }
  ?>

</table>

<input type="hidden" name="checkbox_names" value="<? echo $checkbox_names; ?>">

