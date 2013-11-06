<script language="JavaScript">
<!--


function update_form(art){
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

function paechter_listen(oid, name){
	document.GUI.go.value = 'jagdkatastereditor_Paechter_Listen';
	document.GUI.oid.value = oid;
	document.GUI.name.value = name;
	document.GUI.submit();
}


<? if($this->formvars['go'] == 'jagdbezirk_show_data'){ ?>

function save(){
	form_fieldstring = document.GUI.form_field_names.value+'';
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
	document.GUI.go.value = 'Sachdaten_speichern';
	<? if($this->formvars['close_after_saving']){ ?>
		document.GUI.close_window.value='true';
	<?}?>
	document.GUI.submit();
}

<? } ?>

//-->
</script>

<?php

  $anzObj=count($this->qlayerset[$i]['shape']);
  if ($anzObj>0) {
    ?>

<table>
	<tr>
		<td></td>
		<td>
<table border="0" cellspacing="0" cellpadding="2" width="690px">
  <tr align="center" height="100px">
    <td valign="middle"><strong><font size="+1">Jagdbezirke</font></strong></td>
  </tr>

<?php
 $jagdkataster = new jagdkataster($this->pgdatabase);
 for ($j=0;$j<$anzObj;$j++) {
   $paechterliste = $jagdkataster->get_paechter($this->qlayerset[$i]['shape'][$j]['oid']);
?>


  <tr><td>&nbsp;</td></tr>
  <tr>
    <td>
			<input type="hidden" value="1" name="changed_<? echo $this->qlayerset[$i]['shape'][$j]['oid']; ?>">
      <table border="1" cellspacing="0" cellpadding="2" width="100%">
      <colgroup>
       <col width="30%">
       <col width="*">
      <colgroup>

        <tr width="100%">
          <td bgcolor="<?php echo BG_DEFAULT ?>"><b>Name Jagdbezirk</b></td>
          <td valign="top">
          	<input type="text" name="<? echo $this->qlayerset[$i]['Layer_ID'].';name;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j]['oid'].';Text;1'; ?>" value="<? echo $this->qlayerset[$i]['shape'][$j]['name']; ?>">
          	<?
          	$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';name;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j]['oid'].';Text;1|';
          	?>
          </td>
        </tr>

        <tr width="100%">
          <td bgcolor="<?php echo BG_DEFAULT ?>"><b>Art</b></td>
          <td valign="top">
          	<select onchange="update_form(this.value);" name="<? echo $this->qlayerset[$i]['Layer_ID'].';art;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j]['oid'].';Text;1'; ?>">
          		<option value="">--- Bitte wählen ---</option>
              <option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'ejb'){echo 'selected';} ?> value="ejb">Eigenjagdbezirk</option>
              <option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'ajb'){echo 'selected';} ?> value="ajb">Abgerundeter Eigenjagdbezirk</option>
			  			<option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'gjb'){echo 'selected';} ?> value="gjb">Gemeinschaftlicher Jagdbezirk</option>
			  			<option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'tjb'){echo 'selected';} ?> value="tjb">Teiljagdbezirk</option>
			  			<option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'sf'){echo 'selected';} ?> value="sf">Sonderfläche</option>
			  			<option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'jbe'){echo 'selected';} ?> value="jbe">Enklave</option>
			  			<option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'jbf'){echo 'selected';} ?> value="jbf">Jagdbezirksfreie Fläche</option>
			  			<option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'agf'){echo 'selected';} ?> value="agf">Angliederungsfläche</option>
			  			<option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'atf'){echo 'selected';} ?> value="atf">Abtrennungsfläche</option>
			  			<option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'atv'){echo 'selected';} ?> value="atv">Abtrennungsfläche durch Verzicht</option>
			  			<option <? if($this->qlayerset[$i]['shape'][$j]['art'] == 'apf'){echo 'selected';} ?> value="apf">Anpachtfläche</option>
			  		</select>
			  		<?
			  		$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';art;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j]['oid'].';Text;1|';
			  		?>
          </td>
        </tr>

        <tr width="100%">
          <td bgcolor="<?php echo BG_DEFAULT ?>"><b>Fl&auml;che&nbsp;[ha]</b></td>
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
          <td bgcolor="<?php echo BG_DEFAULT ?>"><b>lfd. Nr. Condition</b></td>
          <td valign="top">
          	<input type="text" name="<? echo $this->qlayerset[$i]['Layer_ID'].';id;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j]['oid'].';Text;1'; ?>" value="<? echo $this->qlayerset[$i]['shape'][$j]['id']; ?>">
          	<?
			  		$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';id;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j]['oid'].';Text;1|';
			  		?>
          </td>
        </tr>

        <tr id="zuordnung" width="100%" style="display:<? if(in_array($this->qlayerset[$i]['shape'][$j]['art'], array('jbe', 'jbf', 'agf', 'atf', 'apf', 'atv'))){ echo '';}else{echo 'none';} ?>">
          <td bgcolor="<?php echo BG_DEFAULT ?>"><b>Zuordnung (lfd. Nr. EJB)</b></td>
          <td valign="top">
          	<input type="text" name="<? echo $this->qlayerset[$i]['Layer_ID'].';jb_zuordnung;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j]['oid'].';Text;1'; ?>" value="<? echo $this->qlayerset[$i]['shape'][$j]['jb_zuordnung']; ?>">
          	<?
			  		$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';jb_zuordnung;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j]['oid'].';Text;1|';
			  		?>
          </td>
        </tr>
        <tr id="status" width="100%" style="display:<? if(in_array($this->qlayerset[$i]['shape'][$j]['art'], array('ejb', 'jbe', 'jbf', 'agf', 'atf', 'apf', 'atv'))){ echo '';}else{echo 'none';} ?>">
          <td bgcolor="<?php echo BG_DEFAULT ?>"><b>Status</b></td>
          <td valign="top">
          	<select name="<? echo $this->qlayerset[$i]['Layer_ID'].';status;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j]['oid'].';Text;1'; ?>">
          		<option value="0" <? if($this->qlayerset[$i]['shape'][$j]['status'] == 'f'){echo 'selected="true"';} ?>>aktuell</option>
          		<option value="1" <? if($this->qlayerset[$i]['shape'][$j]['status'] == 't'){echo 'selected="true"';} ?>>historisch</option>
          	</select>
          	<?
			  		$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';status;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j]['oid'].';Text;1|';
			  		?>
          </td>
        </tr>
        
        <tr id="verzicht" width="100%" style="display:<? if(in_array($this->qlayerset[$i]['shape'][$j]['art'], array('ejb','ajb'))){ echo '';}else{echo 'none';} ?>">
          <td bgcolor="<?php echo BG_DEFAULT ?>"><b>Verzicht gem. §3</b></td>
          <td valign="top">
          	<select name="<? echo $this->qlayerset[$i]['Layer_ID'].';verzicht;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j]['oid'].';Text;1'; ?>">
          		<option <? if($this->qlayerset[$i]['shape'][$j]['verzicht'] == 'f'){echo 'selected';} ?> value="0">nein</option>
  						<option <? if($this->qlayerset[$i]['shape'][$j]['verzicht'] == 't'){echo 'selected';} ?> value="1">ja</option>
          	</select>
          	<?
			  		$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';verzicht;jagdbezirke;'.$this->qlayerset[$i]['shape'][$j]['oid'].';Text;1|';
			  		?>
          </td>
        </tr>

        <tr width="100%">
          <td valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
            <?php
            if(count($paechterliste) == 0) {
            echo "<b>P&auml;chter</b>";
            } else {
            ?>
            <a href="javascript:paechter_listen(<?php echo $this->qlayerset[$i]['shape'][$j]['oid']; ?>, '<? echo $this->qlayerset[$i]['shape'][$j]['name'] ?>');"><b>P&auml;chter</b></a>&nbsp;
            <?php
            }
            ?>
          </td>
          <td valign="top">
        <?php
        if(count($paechterliste) == 0){
        	echo 'keine P&auml;chterdaten';
        }
        for($p = 0; $p < count($paechterliste); $p++){
        	echo $paechterliste[$p]['nachname'].',&nbsp;'.$paechterliste[$p]['vorname'].'<br>';
        }
        ?>
          </td>
        </tr>

        <tr width="100%">
          <td bgcolor="<?php echo BG_DEFAULT ?>"><b>Abschussplanung</b></td>
          <td valign="top">
  &nbsp; <!-- hier muss noch die Anzeige der sowie der Link zur Abschussplanung hin! -->
          </td>
        </tr>

        <tr><td colspan="2">&nbsp;</td></tr>
        <tr>
          <td colspan="2" bgcolor="<?php echo BG_DEFAULT ?>">
            <a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="" onclick="this.href='index.php?go=zoomtoPolygon&oid=<?php echo $this->qlayerset[$i]['shape'][$j]['oid']; ?>&layer_tablename=jagdbezirke&layer_columnname=the_geom&layer_id=<? echo $this->qlayerset[$i]['Layer_ID'];?>&selektieren='+document.GUI.selektieren<? echo $this->qlayerset[$i]['Layer_ID'].'_'.$j; ?>.checked;">Kartenausschnitt</a>&nbsp;&nbsp;<span style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px">Selektieren</span><input type="checkbox" name="selektieren<? echo $this->qlayerset[$i]['Layer_ID'].'_'.$j; ?>" value="1">&nbsp;|&nbsp;
            <a href="index.php?go=jagdkatastereditor_Flurstuecke_Listen&oid=<?php echo $this->qlayerset[$i]['shape'][$j]['oid']; ?>&name=<? echo $this->qlayerset[$i]['shape'][$j]['name'] ?>&search_nummer=<? echo $this->formvars['search_nummer']; ?>&search_name=<? echo $this->formvars['search_name']; ?>&search_art=<? echo $this->formvars['search_art']; ?>">enthaltene Flurstücke</a>&nbsp;|&nbsp;
            <a href="index.php?go=jagdkatastereditor_Eigentuemer_Listen&oid=<?php echo $this->qlayerset[$i]['shape'][$j]['oid']; ?>&name=<? echo $this->qlayerset[$i]['shape'][$j]['name'] ?>&search_nummer=<? echo $this->formvars['search_nummer']; ?>&search_name=<? echo $this->formvars['search_name']; ?>&search_art=<? echo $this->formvars['search_art']; ?>">Eigentümer auflisten</a>&nbsp;|&nbsp;
            <a href="index.php?go=jagdkatastereditor&oid=<?php echo $this->qlayerset[$i]['shape'][$j]['oid']; ?>">bearbeiten</a>&nbsp;|&nbsp;
            <a href="javascript:Bestaetigung('index.php?go=jagdkatastereditor_Loeschen&oid=<?php echo $this->qlayerset[$i]['shape'][$j]['oid']; ?>', 'Wollen Sie diesen Jagdbezirk wirklich löschen?');">löschen</a>&nbsp;|&nbsp;
            <a href="index.php?go=jagdkatastereditor_kopieren&oid=<?php echo $this->qlayerset[$i]['shape'][$j]['oid']; ?>">kopieren</a>
          </td>
        </tr>
        <tr>
        	<td colspan="2" height="40" align="center"><input type="button" name="speichernbutton" value="speichern" onclick="save();"></td>
        </tr>
      </table>

    </td>
  </tr>

<?php
 }
?>

<?
  if ($this->formvars['oid']!='' OR $this->formvars['value_oid']!='') {
?>
  <tr>
    <td align="center"><a href="javascript:document.GUI.go.value = 'jagdbezirke_auswaehlen_Suchen';javascript:document.GUI.submit()">zur&uuml;ck zur Trefferliste</a></td>
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
<input name="value_oid" type="hidden" value="<? echo $this->qlayerset[$i]['shape'][0]['oid']; ?>">
<input name="keinzurueck" type="hidden" value="1">
<? }
if(!$this->jagd_hiddenfields){
	$this->jagd_hiddenfields = true; ?>
<input name="name" type="hidden" value="<? echo $this->formvars['name']; ?>">
<input name="oid" type="hidden" value="<?php echo $this->formvars['oid']; ?>">
<input name="search_nummer" type="hidden" value="<?php echo $this->formvars['search_nummer']; ?>">
<input name="search_name" type="hidden" value="<?php echo $this->formvars['search_name']; ?>">
<input name="search_art" type="hidden" value="<?php echo $this->formvars['search_art']; ?>">
<input name="search_status" type="hidden" value="<?php echo $this->formvars['search_status']; ?>">
<input name="search_verzicht" type="hidden" value="<?php echo $this->formvars['search_verzicht']; ?>">
<? } ?>
