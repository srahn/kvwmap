
<script type="text/javascript">
<!--

function print(){
	document.GUI.target = '_blank';
	document.GUI.go_plus.value = 'Drucken';
	document.GUI.submit();
}

function goback(){
	document.GUI.target = '';
	document.GUI.go_plus.value = '';
	document.GUI.submit();
}

function zoom(zoomdirection){
	document.GUI.target = '';
	if(zoomdirection == 'in'){
		document.GUI.vorschauzoom.value = document.GUI.vorschauzoom.value * 1.3;  
	}
	else{
		document.GUI.vorschauzoom.value = document.GUI.vorschauzoom.value / 1.3;
	}
	document.GUI.go_plus.value = 'Vorschau';
	document.GUI.submit(); 
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
<table border="0" cellspacing="0" cellpadding="2">
  <tr align="center"> 
    <td colspan="2"><h2> 
      <?php echo $this->titel; ?><br><br></h2>
    </td>
  </tr>
</table>

<table border="0" width="<? echo $this->Document->width; ?>" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left">
			<div id="main" style="position: relative; left:0px; top:0px;">
				<table bgcolor="white" width="595" height="<? echo $this->Document->height; ?>" border="0" cellspacing="0" cellpadding="0">
				  <tr>
				  	<td>&nbsp;</td>
				  </tr>
				</table>
				<div id="head" class="" style="position: absolute; visibility: visible; left: <? echo $this->Document->headposx; ?>px; bottom: <? echo $this->Document->headposy; ?>px">
					<img src="<? echo copy_file_to_tmp(DRUCKRAHMEN_PATH.$this->Document->activeframe[0]['headsrc']); ?>" width="<? echo $this->Document->headwidth; ?>">
				</div>
			  <div id="map" class="" style="position: absolute; visibility: visible; left: <? echo $this->Document->mapposx; ?>px; bottom: <? echo $this->Document->mapposy; ?>px">
					<img src="<? echo $this->img['hauptkarte']; ?>" width="<? echo $this->Document->mapwidth; ?>">
				</div>
				<div id="date" class="" style="font-family:helvetica; font-size: <? echo $this->Document->datesize; ?>px; font-weight:bold; position: absolute; visibility: visible; left: <? echo $this->Document->dateposx; ?>px; bottom: <? echo $this->Document->dateposy; ?>px">
					<? echo date("d.m.Y"); ?>
				</div>
				<div id="scale" class="" style="font-size: <? echo $this->Document->scalesize; ?>px; font-weight:bold; position: absolute; visibility: visible; left: <? echo $this->Document->scaleposx; ?>px; bottom: <? echo $this->Document->scaleposy; ?>px">
					<? echo '1:'.$this->formvars['printscale']; ?>
				</div>
				<div id="gemarkung" class="" style="font-size: <? echo $this->Document->gemarkungsize; ?>px; font-weight:bold; position: absolute; visibility: visible; left: <? echo $this->Document->gemarkungposx; ?>px; bottom: <? echo $this->Document->gemarkungposy; ?>px">
					<? echo 'Gemarkung:&nbsp;'.$this->lagebezeichnung[1]['gemkgschl'].'&nbsp;/&nbsp;'.$this->lagebezeichnung[1]['gemkgname']; ?>
				</div>
				<div id="flur" class="" style="font-size: <? echo $this->Document->flursize; ?>px; font-weight:bold; position: absolute; visibility: visible; left: <? echo $this->Document->flurposx; ?>px; bottom: <? echo $this->Document->flurposy; ?>px">
					<? echo 'Flur: '.$this->lagebezeichnung[1]['flur']; ?>
				</div>
				
				<div title="Nutzer" id="user" class="" style="font-size: <? echo $this->Document->usersize; ?>px; font-weight:bold; position: absolute; visibility: visible; left: <? echo $this->Document->userposx; ?>px; bottom: <? echo $this->Document->userposy; ?>px">
					<? echo 'Stelle: '.$this->Stelle->Bezeichnung.', Nutzer: '.$this->user->Name; ?>
				</div>
				
				<? if($this->Document->activeframe[0]['refmapsrc'] != ''){ ?>
				<div id="refmap" class="" style="position: absolute; visibility: visible; left: <? echo $this->Document->refmapposx; ?>px; bottom: <? echo $this->Document->refmapposy; ?>px">
					<img src="<? echo copy_file_to_tmp(DRUCKRAHMEN_PATH.$this->Document->activeframe[0]['refmapsrc']); ?>" width="<? echo $this->Document->refmapwidth; ?>">
				</div>
				<div id="ref" class="" style="position: absolute; visibility: visible; left: <? echo $this->Document->refposx; ?>px; bottom: <? echo $this->Document->refposy; ?>px">
					<img src="<? echo $this->Document->referencemap; ?>" width="<? echo $this->Document->refwidth; ?>">
				</div>
				<? } ?>
				<? if($this->Document->activeframe[0]['legendsize'] != ''){ ?>
				<div id="legend" class="" style="position: absolute; visibility: visible; left: <? echo $this->Document->legendposx; ?>px; bottom: <? echo $this->Document->legendposy; ?>px">
					<img src="<? echo $this->Document->legend; ?>" width="<? echo $this->Document->legendwidth/$this->map_factor; ?>">
				</div>
				<? } ?>
				<div id="oscale" class="" style="font-size: <? echo $this->Document->oscalesize; ?>px; font-weight:bold; position: absolute; visibility: visible; left: <? echo $this->Document->oscaleposx; ?>px; bottom: <? echo $this->Document->oscaleposy; ?>px">
					<? echo '1: xxxx'; ?>
				</div>
				<div title="Freitext" id="text" class="" style="font-size: <? echo $this->Document->textsize; ?>px; font-weight:bold; position: absolute; visibility: visible; left: <? echo $this->Document->textposx; ?>px; bottom: <? echo $this->Document->textposy; ?>px">
					<? echo $this->Document->text; ?>
				</div>
			</div>
		</td>
	</tr>
</table>
<table>
	<tr>
  	<td>&nbsp;</td>
  </tr>
  <tr align="center"> 
    <td colspan="2"> 
      <input type="button" name="zurueck" value="zurück" onclick="goback();">
      <input type="button" name="drucken" value="Drucken" onclick="print();">
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="vorschauzoomin" value=" + " onclick="zoom('in');">
      zoom
      <input type="button" name="vorschauzoomout" value=" - " onclick="zoom('out');">
    </td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="map_factor" value="<? echo $this->formvars['map_factor'] ?>">
<input type="hidden" name="vorschauzoom" value="<? echo $this->formvars['vorschauzoom']; ?>">
<input type="hidden" name="go" value="Druckausschnittswahl">
<input type="hidden" name="go_plus" value="">
<input type="hidden" name="worldprintwidth" value="<? echo $this->formvars['worldprintwidth'] ?>">
<input type="hidden" name="worldprintheight" value="<? echo $this->formvars['worldprintheight'] ?>">
<input type="hidden" name="center_x" value="<?php echo $this->formvars['center_x']; ?>">
<input type="hidden" name="center_y" value="<?php echo $this->formvars['center_y']; ?>">
<input type="hidden" name="format" value="<?php echo $this->formvars['format']; ?>">
<input type="hidden" name="printscale" value="<?php echo $this->formvars['printscale']; ?>">
<input type="hidden" name="angle" value="<?php echo $this->formvars['angle']; ?>">
<input type="hidden" name="referencemap" value="<?php echo $this->formvars['referencemap']; ?>">
<input type="hidden" name="minx" value="<?php echo $this->map->extent->minx; ?>">
<input type="hidden" name="miny" value="<?php echo $this->map->extent->miny; ?>">
<input type="hidden" name="maxx" value="<?php echo $this->map->extent->maxx; ?>">
<input type="hidden" name="maxy" value="<?php echo $this->map->extent->maxy; ?>">
<input type="hidden" name="mapwidth" value="<?php echo $this->map->width; ?>">
<input type="hidden" name="mapheight" value="<?php echo $this->map->height; ?>">
<input type="hidden" name="aktiverRahmen" value="<?php echo $this->formvars['aktiverRahmen']; ?>">

<input type="hidden" name="gemkgschl" value="<?php echo $this->lagebezeichnung[1]['gemkgschl']; ?>">
<input type="hidden" name="gemkgname" value="<?php echo $this->lagebezeichnung[1]['gemkgname']; ?>">
<input type="hidden" name="flur" value="<?php echo $this->lagebezeichnung[1]['flur']; ?>">

<? # Durchschleifen der vom Nutzer eingegebenen Freitexte 
	 for($j = 0; $j < count($this->Document->activeframe[0]['texts']); $j++){		?>			
			<input type="hidden" name="freetext<? echo $this->Document->activeframe[0]['texts'][$j]['id']; ?>" value="<? echo $this->formvars['freetext'.$this->Document->activeframe[0]['texts'][$j]['id']]; ?>">
<? } ?>
