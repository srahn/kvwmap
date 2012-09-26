<script language="JavaScript" type="text/javascript">
<!--

function show_selected_flurst(){
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
		document.GUI.go.value = 'Flurstueck_Anzeigen';
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
    <td><strong><font size="+1"><?php echo $this->titel; ?></font></strong></td>
  </tr>
  <tr> 
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="4">
      <tr>
      	<td>&nbsp;</td>
      	<? if(!$this->formvars['oid']){ ?><td class="bold" style="border-top:1px solid #C3C7C3; border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">Jagdbezirk</td><? } ?>
        <td class="bold" style="border-top:1px solid #C3C7C3; border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">Gemarkung</td>
        <td class="bold" style="border-top:1px solid #C3C7C3; border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">Flur</td>
        <td class="bold" style="border-top:1px solid #C3C7C3; border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">Zähler/Nenner</td>
        <td class="bold" style="border-top:1px solid #C3C7C3; border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">Eigentümer</td>
        <td class="bold" style="border-top:1px solid #C3C7C3; border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">Flurstücksfläche(ALB)</td>
        <td class="bold" style="border-top:1px solid #C3C7C3; border-left:1px solid #C3C7C3; border-right:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">Anteil am Jagdbezirk</td>
      </tr>
      <?php 
      for ($i = 0; $i < count($this->flurstuecke); $i++) { ?>
      <tr <? if($this->flurstuecke[$i]['anteil'] < 90){ echo 'bgcolor="#E6E6F0"';}else{ echo 'bgcolor="#B4C8D2"';} ?>>
      	<td><input type="checkbox" name="check_flurstueck" value="<? echo $this->flurstuecke[$i]['flurstkennz']; ?>" <? if($this->flurstuecke[$i]['anteil'] > 90){ echo 'checked="true"';} ?>></td>
      	<? if(!$this->formvars['oid']){ ?><td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><? echo $this->flurstuecke[$i]['name']; ?></td><? } ?>
        <td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><a href="index.php?go=Flurstueck_Anzeigen&jagdkataster=true&oid=<? echo $this->formvars['oid']; ?>&name=<? echo $this->formvars['name']?>&search_nummer=<? echo $this->formvars['search_nummer']; ?>&search_name=<? echo $this->formvars['search_name']; ?>&search_art=<? echo $this->formvars['search_art']; ?>&FlurstKennz=<? echo $this->flurstuecke[$i]['flurstkennz']; ?>"><? echo $this->flurstuecke[$i]['gemkgname']; ?></a></td>
        <td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><a href="index.php?go=Flurstueck_Anzeigen&jagdkataster=true&oid=<? echo $this->formvars['oid']; ?>&name=<? echo $this->formvars['name']?>&search_nummer=<? echo $this->formvars['search_nummer']; ?>&search_name=<? echo $this->formvars['search_name']; ?>&search_art=<? echo $this->formvars['search_art']; ?>&FlurstKennz=<? echo $this->flurstuecke[$i]['flurstkennz']; ?>"><? echo $this->flurstuecke[$i]['flur']; ?></a></td>
        <td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><a href="index.php?go=Flurstueck_Anzeigen&jagdkataster=true&oid=<? echo $this->formvars['oid']; ?>&name=<? echo $this->formvars['name']?>&search_nummer=<? echo $this->formvars['search_nummer']; ?>&search_name=<? echo $this->formvars['search_name']; ?>&search_art=<? echo $this->formvars['search_art']; ?>&FlurstKennz=<? echo $this->flurstuecke[$i]['flurstkennz']; ?>"><? echo $this->flurstuecke[$i]['zaehlernenner']; ?></a></td>
        <td width="25%" style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><? echo $this->flurstuecke[$i]['eigentuemer'][0]; ?>&nbsp;<? if(count($this->flurstuecke[$i]['eigentuemer']) > 1){ ?><a class="infobox" href=""><b>...</b><span><? for($j=0; $j < count($this->flurstuecke[$i]['eigentuemer']); $j++){echo $this->flurstuecke[$i]['eigentuemer'][$j].'<br>';} ?></span></a><? } ?></td>
        <td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><? echo $this->flurstuecke[$i]['albflaeche']; ?> m<sup>2</sup></td>
        <td style="border-left:1px solid #C3C7C3; border-right:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><? echo $this->flurstuecke[$i]['anteil']; ?> %</td>
      </tr>
      <?php  
      }
      ?>
      <tr>
      	<td valign="top" align="center"><img src="<? echo GRAPHICSPATH?>pfeil_unten-rechts.gif"></td>
      	<td height="29" valign="bottom" colspan="5"><a href="javascript:show_selected_flurst();">ausgewählte Flurstücke anzeigen</a> | <a href="javascript:show_all_flurst();">alle Flurstücke anzeigen</a></td>
      </tr>
    </table></td>
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
  	<td align="center"><a href="javascript:csv_export();">CSV-Export</a></td>
  </tr>
  <tr>
  	<td align="center"><a href="javascript:hideMenue();javascript:print();">Drucken</a></td>
  </tr>
  <tr> 
    <td align="right">&nbsp;</td>
  </tr>
</table>

<input name="go" type="hidden" value="jagdbezirk_show_data">
<input name="oid" type="hidden" value="<? echo $this->formvars['oid']; ?>">
<input name="name" type="hidden" value="<? echo $this->formvars['name']; ?>">
<input name="search_nummer" type="hidden" value="<?php echo $this->formvars['search_nummer']; ?>">
<input name="search_name" type="hidden" value="<?php echo $this->formvars['search_name']; ?>">
<input name="search_art" type="hidden" value="<?php echo $this->formvars['search_art']; ?>">
<input name="FlurstKennz" type="hidden" value="">
<input name="jagdkataster" type="hidden" value="true">

