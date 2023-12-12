<script type="text/javascript">

update_form = function(art){
	if(art == 'jbe' || art == 'jbf' || art == 'agf' || art == 'atf' || art == 'apf'){
		document.getElementById('zuordnung').style.display = '';
		document.getElementById('status').style.display = '';
		document.getElementById('lfdnr').style.display = 'none';
		}
		else{
		document.getElementById('zuordnung').style.display = 'none';
		document.getElementById('status').style.display = 'none';
		document.getElementById('lfdnr').style.display = '';
	}
}

paechter_listen = function(oid, name){
	currentform.go.value = 'jagdkatastereditor_Paechter_Listen';
	currentform.oid.value = oid;
	currentform.name.value = name;
	currentform.submit();
}


<? if($this->formvars['go'] == 'jagdbezirk_show_data'){ ?>

function save(){
	form_fieldstring = currentform.form_field_names.value+'';
	form_fields = form_fieldstring.split('|');
	for(i = 0; i < form_fields.length-1; i++){
		fieldstring = form_fields[i]+'';
		field = fieldstring.split(';');
		if(document.getElementsByName(fieldstring)[0] != undefined && field[4] != 'Dokument' && document.getElementsByName(fieldstring)[0].readOnly != true && field[5] == '0' && document.getElementsByName(fieldstring)[0].value == ''){
			alert('Das Feld '+document.getElementsByName(fieldstring)[0].title+' erfordert eine Eingabe.');
			return;
		}
		if(document.getElementsByName(fieldstring)[0] != undefined && field[6] == 'date' && field[4] != 'Time' && document.getElementsByName(fieldstring)[0].value != '' && !checkDate(document.getElementsByName(fieldstring)[0].value)){
			alert('Das Datumsfeld '+document.getElementsByName(fieldstring)[0].title+' hat nicht das Format TT.MM.JJJJ.');
			return;
		}
	}
	currentform.go.value = 'Sachdaten_speichern';
	<? if($this->formvars['close_after_saving']){ ?>
		currentform.close_window.value='true';
	<?}?>
	overlay_submit(currentform, false);
}

<? } ?>

</script>

<?php

	$this->qlayerset[$i]['Layer_ID'] = LAYER_ID_JAGDBEZIRKE;		// damit man alle Jagd-Layer abfragen und editieren kann, wird die Layer-ID vom Lagbezirks_Layer genommen

  $anzObj = @count($this->qlayerset[$i]['shape']);
  if ($anzObj>0) {
    ?>

<table>
	<tr>
		<td></td>
		<td>
<table border="0" cellspacing="0" cellpadding="2" width="690px">
  <tr align="center" height="100px">
    <td valign="middle"><h2>Jagdbezirke</h2></td>
  </tr>

<?php
	include_once (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
	$jagdkataster = new jagdkataster($this->pgdatabase, $this->qlayerset[$i]);
	###### Test auf Editierrecht ###################################
	$privileg_ = array();
	for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
		if($this->qlayerset[$i]['attributes']['privileg'][$this->qlayerset[$i]['attributes']['name'][$j]] == '1'){
			$privileg_[$this->qlayerset[$i]['attributes']['name'][$j]] = $this->qlayerset[$i]['attributes']['privileg'][$this->qlayerset[$i]['attributes']['name'][$j]];
			$editable = true;
		}
	}
	###### Abfrage der Flst.-Rechte (für Eigentümeranzeige) ########
	$flst_layer = $this->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
	$flst_privileges = $this->Stelle->get_attributes_privileges($flst_layer[0]['Layer_ID']);
	################################################################	
	for ($j=0;$j<$anzObj;$j++) {
		$paechterliste = $jagdkataster->get_paechter($this->qlayerset[$i]['shape'][$j][$this->qlayerset[$i]['maintable'] . '_oid']);
?>


  <tr><td>&nbsp;</td></tr>
  <tr>
    <td>
			<input type="hidden" value="1" name="changed_<? echo $this->qlayerset[$i]['Layer_ID'].'_'.$this->qlayerset[$i]['shape'][$j][$this->qlayerset[$i]['maintable'] . '_oid']; ?>">
      <table border="1" cellspacing="0" cellpadding="2" width="100%">
      <colgroup>
       <col width="30%">
       <col width="*">
      <colgroup>
        <tr width="100%">
          <td bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett">Name Jagdbezirk</span></td>
          <td valign="top">
          	<input type="text" <? if($privileg_['name'] == 0)echo 'readonly'; ?> name="<? echo $this->qlayerset[$i]['Layer_ID'].';name;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j][$this->qlayerset[$i]['maintable'] . '_oid'].';Text;1'; ?>" value="<? echo $this->qlayerset[$i]['shape'][$j]['name']; ?>">
          	<?
          	$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';name;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j][$this->qlayerset[$i]['maintable'] . '_oid'].';Text;1|';
          	?>
          </td>
        </tr>

        <tr width="100%">
          <td bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett">Art</span></td>
          <td valign="top">
          	<select onchange="update_form(this.value);" name="<? echo $this->qlayerset[$i]['Layer_ID'].';art;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j][$this->qlayerset[$i]['maintable'] . '_oid'].';Text;1'; ?>">
          		<option value="">--- Bitte wählen ---</option>
              <option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'ejb'){echo 'selected';} ?> value="ejb">Eigenjagdbezirk</option>
              <option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'ajb'){echo 'selected';} ?> value="ajb">Abgerundeter Eigenjagdbezirk</option>
			  			<option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'gjb'){echo 'selected';} ?> value="gjb">Gemeinschaftlicher Jagdbezirk</option>
			  			<option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'tjb'){echo 'selected';} ?> value="tjb">Teiljagdbezirk</option>
			  			<option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'sf'){echo 'selected';} ?> value="sf">Sonderfläche</option>
			  			<option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'jbe'){echo 'selected';} ?> value="jbe">Enklave</option>
							<option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'jex'){echo 'selected';} ?> value="jex">Exklave</option>
			  			<option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'jbf'){echo 'selected';} ?> value="jbf">Jagdbezirksfreie Fläche</option>
			  			<option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'agf'){echo 'selected';} ?> value="agf">Angliederungsfläche</option>
			  			<option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'atf'){echo 'selected';} ?> value="atf">Abtrennungsfläche</option>
			  			<option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'atv'){echo 'selected';} ?> value="atv">Abtrennungsfläche durch Verzicht</option>
			  			<option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'apf'){echo 'selected';} ?> value="apf">Anpachtfläche</option>
			  		</select>
			  		<?
			  		$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';art;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j][$this->qlayerset[$i]['maintable'] . '_oid'].';Text;1|';
			  		?>
          </td>
        </tr>

        <tr width="100%">
          <td bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett">Fl&auml;che&nbsp;[ha]</span></td>
          <td valign="top">
            <?php
            if($this->qlayerset[$i]['shape'][$j]['flaeche'] == '') {
              echo '&nbsp;';
            } else {
              echo str_replace('.',',',$this->qlayerset[$i]['shape'][$j]['flaeche']);
            }
            ?>
          </td>
        </tr>

        <tr id="lfdnr" width="100%" style="display:<? if(in_array($this->qlayerset[$i]['shape'][$j]['art'], array('jbe', 'jbf', 'agf', 'atf', 'apf', 'atv'))){ echo 'none';}else{echo '';} ?>">
          <td bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett">lfd. Nr. Condition</span></td>
          <td valign="top">
          	<input type="text" name="<? echo $this->qlayerset[$i]['Layer_ID'].';id;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j][$this->qlayerset[$i]['maintable'] . '_oid'].';Text;1'; ?>" value="<? echo $this->qlayerset[$i]['shape'][$j]['id']; ?>">
          	<?
			  		$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';id;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j][$this->qlayerset[$i]['maintable'] . '_oid'].';Text;1|';
			  		?>
          </td>
        </tr>

        <tr id="zuordnung" width="100%" style="display:<? if(in_array($this->qlayerset[$i]['shape'][$j]['art'], array('jbe', 'jbf', 'agf', 'atf', 'apf', 'atv'))){ echo '';}else{echo 'none';} ?>">
          <td bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett">Zuordnung (lfd. Nr. EJB)</span></td>
          <td valign="top">
          	<input type="text" name="<? echo $this->qlayerset[$i]['Layer_ID'].';jb_zuordnung;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j][$this->qlayerset[$i]['maintable'] . '_oid'].';Text;1'; ?>" value="<? echo $this->qlayerset[$i]['shape'][$j]['jb_zuordnung']; ?>">
          	<?
			  		$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';jb_zuordnung;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j][$this->qlayerset[$i]['maintable'] . '_oid'].';Text;1|';
			  		?>
          </td>
        </tr>
        <tr id="status" width="100%" style="display:<? if(in_array($this->qlayerset[$i]['shape'][$j]['art'], array('ejb', 'jbe', 'jbf', 'agf', 'atf', 'apf', 'atv'))){ echo '';}else{echo 'none';} ?>">
          <td bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett">Status</span></td>
          <td valign="top">
          	<select name="<? echo $this->qlayerset[$i]['Layer_ID'].';status;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j][$this->qlayerset[$i]['maintable'] . '_oid'].';Text;1'; ?>">
          		<option value="0" <? if($this->qlayerset[$i]['shape'][$j]['status'] == 'f'){echo 'selected="true"';} ?>>aktuell</option>
          		<option value="1" <? if($this->qlayerset[$i]['shape'][$j]['status'] == 't'){echo 'selected="true"';} ?>>historisch</option>
          	</select>
          	<?
			  		$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';status;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j][$this->qlayerset[$i]['maintable'] . '_oid'].';Text;1|';
			  		?>
          </td>
        </tr>
        
        <tr id="verzicht" width="100%" style="display:<? if(in_array($this->qlayerset[$i]['shape'][$j]['art'], array('ejb','ajb'))){ echo '';}else{echo 'none';} ?>">
          <td bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett">Verzicht gem. §3</span></td>
          <td valign="top">
          	<select name="<? echo $this->qlayerset[$i]['Layer_ID'].';verzicht;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j][$this->qlayerset[$i]['maintable'] . '_oid'].';Text;1'; ?>">
          		<option <? if($this->qlayerset[$i]['shape'][$j]['verzicht'] == 'f'){echo 'selected';} ?> value="0">nein</option>
  						<option <? if($this->qlayerset[$i]['shape'][$j]['verzicht'] == 't'){echo 'selected';} ?> value="1">ja</option>
          	</select>
          	<?
			  		$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';verzicht;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j][$this->qlayerset[$i]['maintable'] . '_oid'].';Text;1|';
			  		?>
          </td>
        </tr>

        <tr width="100%">
          <td valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
            <?php
            if(@count($paechterliste) == 0) {
            echo '<span class="fett">P&auml;chter</span>';
            } else {
            ?>
            <a href="javascript:paechter_listen(<?php echo $this->qlayerset[$i]['shape'][$j][$this->qlayerset[$i]['maintable'] . '_oid']; ?>, '<? echo $this->qlayerset[$i]['shape'][$j]['name'] ?>');"><span class="fett">P&auml;chter</span></a>&nbsp;
            <?php
            }
            ?>
          </td>
          <td valign="top">
        <?php
        if(@count($paechterliste) == 0){
        	echo 'keine P&auml;chterdaten';
        }
        for($p = 0; $p < @count($paechterliste); $p++){
        	echo $paechterliste[$p]['nachname'].',&nbsp;'.$paechterliste[$p]['vorname'].'<br>';
        }
        ?>
          </td>
        </tr>

        <tr width="100%">
          <td bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett">Abschussplanung</span></td>
          <td valign="top">
  &nbsp; <!-- hier muss noch die Anzeige der sowie der Link zur Abschussplanung hin! -->
          </td>
        </tr>

        <tr><td colspan="2">&nbsp;</td></tr>
        <tr>
          <td colspan="2" bgcolor="<?php echo BG_DEFAULT ?>">
            <a target="root" href="" onclick="this.href='index.php?go=zoomto_dataset&oid=<?php echo $this->qlayerset[$i]['shape'][$j][$this->qlayerset[$i]['maintable'] . '_oid']; ?>&layer_tablename=jagdbezirke&layer_columnname=the_geom&layer_id=<? echo $this->qlayerset[$i]['Layer_ID'];?>&selektieren='+currentform.selektieren<? echo $this->qlayerset[$i]['Layer_ID'].'_'.$j; ?>.checked;">Kartenausschnitt</a>&nbsp;&nbsp;<span>Selektieren</span><input type="checkbox" name="selektieren<? echo $this->qlayerset[$i]['Layer_ID'].'_'.$j; ?>" value="1">&nbsp;|&nbsp;
						<? if($flst_privileges['eigentuemer'] != ''){ ?>
            <a target="root" href="index.php?go=jagdkatastereditor_Flurstuecke_Listen&oid=<?php echo $this->qlayerset[$i]['shape'][$j][$this->qlayerset[$i]['maintable'] . '_oid']; ?>&name=<? echo $this->qlayerset[$i]['shape'][$j]['name'] ?>&search_nummer=<? echo $this->formvars['search_nummer']; ?>&search_name=<? echo $this->formvars['search_name']; ?>&search_art=<? echo $this->formvars['search_art']; ?>">enthaltene Flurstücke</a>&nbsp;|&nbsp;
            <a target="root" href="index.php?go=jagdkatastereditor_Eigentuemer_Listen&oid=<?php echo $this->qlayerset[$i]['shape'][$j][$this->qlayerset[$i]['maintable'] . '_oid']; ?>&name=<? echo $this->qlayerset[$i]['shape'][$j]['name'] ?>&search_nummer=<? echo $this->formvars['search_nummer']; ?>&search_name=<? echo $this->formvars['search_name']; ?>&search_art=<? echo $this->formvars['search_art']; ?>">Eigentümer auflisten</a>&nbsp;|&nbsp;
						<? }
							 if($editable){ ?>
            <a target="root" href="index.php?go=jagdkatastereditor&oid=<?php echo $this->qlayerset[$i]['shape'][$j][$this->qlayerset[$i]['maintable'] . '_oid']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>">bearbeiten</a>&nbsp;|&nbsp;
            <a target="root" href="javascript:Bestaetigung('index.php?go=jagdkatastereditor_Loeschen&oid=<?php echo $this->qlayerset[$i]['shape'][$j][$this->qlayerset[$i]['maintable'] . '_oid']; ?>', 'Wollen Sie diesen Jagdbezirk wirklich löschen?');">löschen</a>&nbsp;|&nbsp;
            <a target="root" href="index.php?go=jagdkatastereditor_kopieren&oid=<?php echo $this->qlayerset[$i]['shape'][$j][$this->qlayerset[$i]['maintable'] . '_oid']; ?>">kopieren</a>
						<? } ?>
          </td>
        </tr>
				<? if($editable){ ?>
        <tr>
        	<td colspan="2" height="40" align="center"><input type="button" name="speichernbutton" value="speichern" onclick="save();"></td>
        </tr>
				<tr>
					<td height="30" valign="bottom" align="center" colspan="5" id="loader" style="display:none"><img id="loaderimg" src="graphics/ajax-loader.gif"></td>
				</tr>
				<? } ?>
      </table>

    </td>
  </tr>

<?php
 }
?>

<?
  if ($this->formvars['value_oid']!='') {
?>
  <tr>
    <td align="center"><a href="javascript:currentform.go.value = 'jagdbezirke_auswaehlen_Suchen';javascript:currentform.submit()">zur&uuml;ck zur Trefferliste</a></td>
  </tr>
<?
  }
?>
  </table>
  	</td>
  	<td></td>
	</tr>
</table>

<?php
}
else {
    ?><br><strong><font color="#FF0000">
    Es wurden keine Objekte gefunden!</font></strong><br>
    Wählen Sie einen neuen Bereich oder prüfen Sie die Datenquellen.<br>
    <?php
}
?>

<? if($this->formvars['go'] == 'jagdbezirk_show_data'){ ?>
<input name="form_field_names" type="hidden" value="<?php echo $this->form_field_names; ?>">
<input name="go" type="hidden" value="jagdbezirke_auswaehlen_Suchen">
<input name="search" type="hidden" value="true">
<input name="selected_layer_id" type="hidden" value="<? echo $this->qlayerset[$i]['Layer_ID']; ?>">
<input name="operator_oid" type="hidden" value="=">
<input name="value_oid" type="hidden" value="<? echo $this->qlayerset[$i]['shape'][0][$this->qlayerset[$i]['maintable'] . '_oid']; ?>">
<input name="keinzurueck" type="hidden" value="1">
<? }
if(!$this->jagd_hiddenfields){
	$this->jagd_hiddenfields = true; ?>
<input name="name" type="hidden" value="<? echo $this->formvars['name']; ?>">
<input name="oid" type="hidden" value="<?php echo $this->formvars['oid']; ?>">
<input name="search_nummer" type="hidden" value="<?php echo $this->formvars['search_nummer']; ?>">
<input name="jagd_search_name" type="hidden" value="<?php echo $this->formvars['jagd_search_name']; ?>">
<input name="search_art" type="hidden" value="<?php echo $this->formvars['search_art']; ?>">
<input name="search_status" type="hidden" value="<?php echo $this->formvars['search_status']; ?>">
<input name="search_verzicht" type="hidden" value="<?php echo $this->formvars['search_verzicht']; ?>">
<? } ?>
