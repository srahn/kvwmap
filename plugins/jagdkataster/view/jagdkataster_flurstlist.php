<script language="JavaScript" type="text/javascript">
<!--

function select_all(){
	var flurstarray = document.getElementsByName("check_flurstueck");
	var status = !flurstarray[0].checked;
	for(i = 0; i < flurstarray.length; i++){
		flurstarray[i].checked = status;      
	}
}

function send_selected_flurst(go){
	var semi = false;
	var flurstkennz = "";
	var flurstarray = document.getElementsByName("check_flurstueck");
	for(i = 0; i < flurstarray.length; i++){
		if(flurstarray[i].checked == true){
      if(semi == true){
        flurstkennz += ';';
      }
      flurstkennz += flurstarray[i].value;
      semi = true;
    }
	}
	if(semi == true){
		document.GUI.go.value = go;
    document.GUI.FlurstKennz.value = flurstkennz;
    document.GUI.submit();
  }
  else{
  	alert('Sie haben kein Flurstueck ausgewählt.');
  }
}

function show_all_flurst(){
	var semi = false;
	var flurstkennz = "";
	var flurstarray = document.getElementsByName("check_flurstueck");
	for(i = 0; i < flurstarray.length; i++){
    if(semi == true){
      flurstkennz += ';';
    }
    flurstkennz += flurstarray[i].value;
    semi = true;
	}
	if(semi == true){
		document.GUI.go.value = 'Flurstueck_Anzeigen';
    document.GUI.FlurstKennz.value = flurstkennz;
    document.GUI.submit();
  }
  else{
  	alert('Sie haben kein Flurstueck ausgewählt.');
  }
}

function csv_export(){
	document.GUI.FlurstKennz.value = '';
	document.GUI.go.value = 'jagdkatastereditor_Flurstuecke_Listen_csv';
	document.GUI.submit();
}

-->
</script>

<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
	<tr> 
    <td align="right">&nbsp;</td>
  </tr>
  <tr align="center"> 
    <td><h2><?php echo $this->titel; ?></h2></td>
  </tr>
  <tr> 
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="4">
      <tr>
      	<td>&nbsp;</td>
      	<? if(!$this->formvars['oid']){ ?><td class="fett" style="border-top:1px solid #C3C7C3; border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">Jagdbezirk</td><? } ?>
        <td class="fett" style="border-top:1px solid #C3C7C3; border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">Gemarkung</td>
        <td class="fett" style="border-top:1px solid #C3C7C3; border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">Flur</td>
        <td class="fett" style="border-top:1px solid #C3C7C3; border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">Zähler/Nenner</td>
        <td class="fett" style="border-top:1px solid #C3C7C3; border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">Eigentümer</td>
        <td class="fett" style="border-top:1px solid #C3C7C3; border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">amtl.&nbsp;Fläche</td>
        <td class="fett" style="border-top:1px solid #C3C7C3; border-left:1px solid #C3C7C3; border-right:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">Anteil am Jagdbezirk</td>
      </tr>
      <?php 
      for ($i = 0; $i < count($this->flurstuecke); $i++) { ?>
      <tr <? if($this->flurstuecke[$i]['anteil'] < 90){ echo 'bgcolor="#E6E6F0"';}else{ echo 'bgcolor="#B4C8D2"';} ?>>
      	<td><input type="checkbox" name="check_flurstueck" value="<? echo $this->flurstuecke[$i]['flurstkennz']; ?>" <? if($this->flurstuecke[$i]['anteil'] > 90){ echo 'checked="true"';} ?>></td>
      	<? if(!$this->formvars['oid']){ ?><td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><? echo $this->flurstuecke[$i]['name']; ?></td><? } ?>
        <td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><a href="index.php?go=Flurstueck_Anzeigen&jagdkataster=true&oid=<? echo $this->formvars['oid']; ?>&name=<? echo $this->formvars['name']?>&search_nummer=<? echo $this->formvars['search_nummer']; ?>&search_name=<? echo $this->formvars['search_name']; ?>&search_art=<? echo $this->formvars['search_art']; ?>&FlurstKennz=<? echo $this->flurstuecke[$i]['flurstkennz']; ?>"><? echo $this->flurstuecke[$i]['gemkgname']; ?></a></td>
        <td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><a href="index.php?go=Flurstueck_Anzeigen&jagdkataster=true&oid=<? echo $this->formvars['oid']; ?>&name=<? echo $this->formvars['name']?>&search_nummer=<? echo $this->formvars['search_nummer']; ?>&search_name=<? echo $this->formvars['search_name']; ?>&search_art=<? echo $this->formvars['search_art']; ?>&FlurstKennz=<? echo $this->flurstuecke[$i]['flurstkennz']; ?>"><? echo $this->flurstuecke[$i]['flur']; ?></a></td>
        <td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><a href="index.php?go=Flurstueck_Anzeigen&jagdkataster=true&oid=<? echo $this->formvars['oid']; ?>&name=<? echo $this->formvars['name']?>&search_nummer=<? echo $this->formvars['search_nummer']; ?>&search_name=<? echo $this->formvars['search_name']; ?>&search_art=<? echo $this->formvars['search_art']; ?>&FlurstKennz=<? echo $this->flurstuecke[$i]['flurstkennz']; ?>"><? echo $this->flurstuecke[$i]['zaehlernenner']; ?></a></td>
        <td width="25%" style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><? echo $this->flurstuecke[$i]['eigentuemer'][0]; ?>&nbsp;<? if(@count($this->flurstuecke[$i]['eigentuemer']) > 1){ ?><div class="infobox"><a class="fetter" href="">...</a><div class="infotext"><? for($j=0; $j < @count($this->flurstuecke[$i]['eigentuemer']); $j++){echo $this->flurstuecke[$i]['eigentuemer_nr'][$j].' '.$this->flurstuecke[$i]['eigentuemer'][$j].'<br>';} ?></div></div><? } ?></td>
        <td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><? echo $this->flurstuecke[$i]['albflaeche']; ?> m<sup>2</sup></td>
        <td style="border-left:1px solid #C3C7C3; border-right:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><? echo $this->flurstuecke[$i]['anteil']; ?> %</td>
      </tr>
      <?php  
      }
      ?>
			<tr>
				<td colspan="3">&nbsp;<a href="javascript:select_all();">alle auswählen</a></td>
			</tr>
      <tr>
      	<td valign="top" align="center"><img src="<? echo GRAPHICSPATH?>pfeil_unten-rechts.gif"></td>
      	<td height="29" valign="bottom" colspan="6"><a href="javascript:send_selected_flurst('Flurstueck_Anzeigen');">ausgewählte Flurstücke anzeigen</a> | <a href="javascript:send_selected_flurst('jagdkatastereditor_Flurstuecke_Listen_csv');">ausgewählte Flurstücke als CSV exportieren</a></td>
      </tr>
    </table></td>
  </tr>
	<tr>
  	<td align="center"><a href="javascript:show_all_flurst();">alle Flurstücke anzeigen</a> | <a href="javascript:csv_export();">alle Flurstücke als CSV exportieren</a></td>
  </tr>	
  <? if($this->formvars['oid']){ ?>
  <tr>
  	<td align="center"><a href="javascript:document.GUI.go.value = 'jagdbezirk_show_data';javascript:document.GUI.submit()">zurück zum Jagdbezirk</a></td>
  </tr>
  <? }else{ ?>
  <tr>
    <td align="center"><a href="javascript:document.GUI.go.value = 'jagdbezirke_auswaehlen_Suchen';javascript:document.GUI.submit()">zur&uuml;ck zur Trefferliste</a></td>
  </tr>
  <? } ?>
  <tr>
  	<td align="center"><a href="javascript:hideMenue();javascript:print();">Drucken</a></td>
  </tr>
  <tr> 
    <td align="right">&nbsp;</td>
  </tr>
</table>

<input name="checkbox_names_<? echo LAYER_ID_JAGDBEZIRKE; ?>" type="hidden" value="<? echo $this->formvars['checkbox_names_'.LAYER_ID_JAGDBEZIRKE]; ?>">
<?
	// Durchschleifen der angehakten Checkboxen der Jagdbezirke
	$checkbox_names = explode('|', $this->formvars['checkbox_names_'.LAYER_ID_JAGDBEZIRKE]);
	for($i = 0; $i < count($checkbox_names); $i++){
		if($this->formvars[$checkbox_names[$i]] == 'on'){
			echo '<input name="'.$checkbox_names[$i].'" type="hidden" value="on">';
		}
	}

?>

<input name="go" type="hidden" value="jagdbezirk_show_data">
<input name="oid" type="hidden" value="<? echo $this->formvars['oid']; ?>">
<input name="name" type="hidden" value="<? echo $this->formvars['name']; ?>">
<input name="search_nummer" type="hidden" value="<?php echo $this->formvars['search_nummer']; ?>">
<input name="search_name" type="hidden" value="<?php echo $this->formvars['search_name']; ?>">
<input name="search_art" type="hidden" value="<?php echo $this->formvars['search_art']; ?>">
<input name="FlurstKennz" type="hidden" value="">
<input name="jagdkataster" type="hidden" value="true">
<input type="hidden" name="chosen_layer_id" value="<? echo LAYER_ID_JAGDBEZIRKE; ?>">

