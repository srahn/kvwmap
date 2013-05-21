<script language="JavaScript">
<!--

function search(order){
	document.GUI.order.value = order;
	document.GUI.go_plus.value = 'Suchen';
	document.GUI.submit();
}

function csv_export(){
	document.GUI.go.value = 'jagdbezirke_auswaehlen_Suchen_csv';
	document.GUI.submit();
}

function update_form(art){
	if(art == 'jbe' || art == 'jbf' || art == 'agf' || art == 'atf'){
		document.getElementById('status').style.display = '';
	}
	else{
		document.getElementById('status').style.display = 'none';
	}
	if(art == 'ejb' || art == 'ajb'){
		document.getElementById('verzicht').style.display = '';
	}
	else{
		document.getElementById('verzicht').style.display = 'none';
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
  			<option <? if($this->formvars['search_art'] == 'ejb'){echo 'selected';} ?> value="ejb">EJB im Verfahren</option>
  			<option <? if($this->formvars['search_art'] == 'ajb'){echo 'selected';} ?> value="ajb">Abgerundeter Eigenjagdbezirk</option>
  			<option <? if($this->formvars['search_art'] == 'gjb'){echo 'selected';} ?> value="gjb">Gemeinschaftlicher Jagdbezirk</option>
  			<option <? if($this->formvars['search_art'] == 'tjb'){echo 'selected';} ?> value="tjb">Teiljagdbezirk</option>
  			<option <? if($this->formvars['search_art'] == 'sf'){echo 'selected';} ?> value="sf">Sonderfläche</option>
  			<option <? if($this->formvars['search_art'] == 'jbe'){echo 'selected';} ?> value="jbe">Enklave</option>
  			<option <? if($this->formvars['search_art'] == 'jbf'){echo 'selected';} ?> value="jbf">Jagdbezirksfreie Fläche</option>
  			<option <? if($this->formvars['search_art'] == 'agf'){echo 'selected';} ?> value="agf">Angliederungsfläche</option>
  			<option <? if($this->formvars['search_art'] == 'atf'){echo 'selected';} ?> value="atf">Abtrennungsfläche</option>
  			<option <? if($this->formvars['search_art'] == 'atv'){echo 'selected';} ?> value="atv">Abtrennungsfläche durch Verzicht</option>
  			<option <? if($this->formvars['search_art'] == 'apf'){echo 'selected';} ?> value="apf">Anpachtfläche</option>
  		</select>
		</td>
  </tr>
  <tr id="status" style="display:<? if(in_array($this->formvars['search_art'], array('jbe', 'jbf', 'agf', 'atf'))){ echo '';}else{echo 'none';} ?>" >
    <td align="right"><strong>Status:</strong></td>
    <td>
    	<select name="search_status">
  			<option <? if($this->formvars['search_status'] == 'false'){echo 'selected';} ?> value="false">aktuell</option>
  			<option <? if($this->formvars['search_status'] == 'true'){echo 'selected';} ?> value="true">historisch</option>
  			<option <? if($this->formvars['search_status'] == 'both'){echo 'selected';} ?> value="both">alle</option>
  		</select>
		</td>
  </tr>
  <tr id="verzicht" style="display:<? if(in_array($this->formvars['search_art'], array('ejb', 'ajb'))){ echo '';}else{echo 'none';} ?>" >
    <td align="right"><strong>Verzicht gem. §3:</strong></td>
    <td>
    	<select name="search_verzicht">
  			<option <? if($this->formvars['search_verzicht'] == 'false'){echo 'selected';} ?> value="false">nein</option>
  			<option <? if($this->formvars['search_verzicht'] == 'true'){echo 'selected';} ?> value="true">ja</option>
  			<option <? if($this->formvars['search_verzicht'] == 'both'){echo 'selected';} ?> value="both">alle</option>
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
			<input type="hidden" name="order" value="">
			<input type="submit" onclick="search('');" style="width: 0px;height: 0px;border: none">
			<input type="button" name="suchen" value="Suchen" onclick="javascript:search('');" tabindex="6">
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
        <td align="center"><strong><a href="javascript:search('id');">lfd. Nummer</a></strong></td>
        <td align="center"><strong><a href="javascript:search('name');">Name</a></strong></td>
        <td align="center"><strong><a href="javascript:search('flaeche');">Fläche</a></strong></td>
        <td align="center"><strong><a href="javascript:search('art');">Typ</a></strong></td>
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
        <td align="center"><?php if ($this->jagdbezirke[$i]['art']=='ejb' OR $this->jagdbezirke[$i]['art']=='ajb' OR $this->jagdbezirke[$i]['art']=='gjb') { echo $this->jagdbezirke[$i]['id']; } else { echo $this->jagdbezirke[$i]['jb_zuordnung']; } ?></td>
        <td align="center"><?php echo $this->jagdbezirke[$i]['name']; ?></td>
      	<td align="center"><?php echo $this->jagdbezirke[$i]['flaeche']; ?></td>
        <td align="center">
        <?php 
  	if($this->jagdbezirke[$i]['art'] == 'ejb'){echo 'EJB im Verfahren';}
  	if($this->jagdbezirke[$i]['art'] == 'ajb'){echo 'Abgerundeter Eigenjagdbezirk';}
  	if($this->jagdbezirke[$i]['art'] == 'gjb'){echo 'Gemeinschaftlicher Jagdbezirk';}
  	if($this->jagdbezirke[$i]['art'] == 'tjb'){echo 'Teiljagdbezirk';}
  	if($this->jagdbezirke[$i]['art'] == 'sf'){echo 'Sonderfläche';}
  	if($this->jagdbezirke[$i]['art'] == 'jbe'){echo 'Enklave';}
  	if($this->jagdbezirke[$i]['art'] == 'jbf'){echo 'Jagdbezirksfreie Fläche';}
  	if($this->jagdbezirke[$i]['art'] == 'agf'){echo 'Angliederungsfläche';}
  	if($this->jagdbezirke[$i]['art'] == 'atf'){echo 'Abtrennungsfläche';}
  	if($this->jagdbezirke[$i]['art'] == 'atv'){echo 'Abtrennungsfläche durch Verzicht';}
  	if($this->jagdbezirke[$i]['art'] == 'apf'){echo 'Anpachtfläche';}
        ?>
        </td>
        <td align="center"><a href="index.php?go=jagdbezirk_show_data&oid=<? echo $this->jagdbezirke[$i]['oid'] ?>&search_nummer=<? echo $this->formvars['search_nummer']; ?>&search_name=<? echo $this->formvars['search_name']; ?>&search_art=<? echo $this->formvars['search_art']; ?>&search_status=<? echo $this->formvars['search_status']; ?>&search_verzicht=<? echo $this->formvars['search_verzicht']; ?>">Sachdatenanzeige</a></td>
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
	  <table width="100%" border="0" cellpadding="3" cellspacing="0">
	  	<tr>
      	<td width="30" valign="top" align="center" valign="bottom"><img src="<? echo GRAPHICSPATH?>pfeil_unten-rechts.gif"></td>
      	<td height="29" valign="bottom" colspan="5"><a href="javascript:intersect_flurst();">enthaltene Flurstücke</a>&nbsp;&nbsp;<b>! Achtung dies kann u.U. sehr lange dauern !</b>&nbsp;&nbsp;</td>
      </tr>
      <tr>
      	<td colspan="6" align="center"><a href="javascript:csv_export();">CSV-Export</a></td>
      </tr>
    </table></td>
  </tr><?php
  }
  ?>

</table>

<input type="hidden" name="checkbox_names" value="<? echo $checkbox_names; ?>">

