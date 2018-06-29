<?php
 # 2008-01-22 pkvvm
  include(LAYOUTPATH.'languages/druckausschnittswahl_'.$this->user->rolle->language.'.php');
	include(LAYOUTPATH.'languages/map_'.$this->user->rolle->language.'.php');
 ?>
<script src="funktionen/tooltip.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

Text_rollenlayer_legend=["<? echo $strHelp; ?>:","<? echo $strRollenlayerLegend ?>"];

function setprintextent(wert){
	document.GUI.printextent.value = wert;
}

function showLegendOptions(){
	if(document.getElementById('legendOptions2').style.display == 'none'){
		document.getElementById('legendOptions1').style.borderTop="1px solid #C3C7C3";
		document.getElementById('legendOptions1').style.borderLeft="1px solid #C3C7C3";
		document.getElementById('legendOptions1').style.borderRight="1px solid #C3C7C3";
		document.getElementById('legendOptions2').style.display = '';
	}
	else{
		document.getElementById('legendOptions1').style.border="none";
		document.getElementById('legendOptions2').style.display = 'none';
	}
}

function druck_pdf(name, format, preis){
	if(preis > 0){
		preis = preis/100;
		r = confirm("<? echo $strConfirm1; ?>"+name+", "+format+": "+preis+"<? echo $strConfirm2; ?>");
		if(r == false)return;
	}	
	if(document.GUI.printextent.value == 'false'){
		alert("<? echo $strWarning1; ?>");
	}
	else{
		if(Math.abs(document.GUI.angle.value) > 90){
			alert("<? echo $strWarning2; ?>");
		}
		else{
			if(document.GUI.printscale.value != ''){
				if(document.GUI.center_x.value != ''){
					document.GUI.target = '_blank';
					document.GUI.go.value = 'Druckausschnittswahl_Drucken';
					document.GUI.submit();
					document.GUI.go.value = 'Druckausschnittswahl';
					document.GUI.target = '';
				}
				else{
					alert("<? echo $strWarning3; ?>");
				}
			}
			else{
				alert("<? echo $strWarning4; ?>");
			}
		}
	}
}

function preview(){
	document.GUI.target = '';
	if(document.GUI.printextent.value == 'false'){
		alert("<? echo $strWarning1; ?>");
	}
	else{
		if(Math.abs(document.GUI.angle.value) > 90){
			alert("<? echo $strWarning2; ?>");
		}
		else{
			if(document.GUI.printscale.value != ''){
				if(document.GUI.center_x.value != ''){
					document.GUI.go.value = "Druckausschnittswahl_Vorschau";
					document.GUI.submit();
				}
				else{
					alert("<? echo $strWarning3; ?>");
				}
			}
			else{
				alert("<? echo $strWarning4; ?>");
			}
		}
	}
}

function save(){
	document.GUI.target = '';
	if(document.GUI.name.value == ''){
		alert("<? echo $strWarning5; ?>");
	}
	else{
		if(Math.abs(document.GUI.angle.value) > 90){
			alert("<? echo $strWarning2; ?>");
		}
		else{
			if(document.GUI.printscale.value != ''){
				if(document.GUI.center_x.value != ''){
					document.GUI.go.value = "Druckausschnitt_speichern";
					document.GUI.submit();
				}
				else{
					alert("<? echo $strWarning3; ?>");
				}
			}
			else{
				alert("<? echo $strWarning4; ?>");
			}
		}
	}
}

function load(){
	document.GUI.target = '';
	if(document.GUI.druckausschnitt.value == ''){
		alert("<? echo $strWarning3; ?>");
	}
	else{
		document.GUI.submit();
	}
}

function remove(){
	document.GUI.target = '';
	if(document.GUI.druckausschnitt.value == ''){
		alert("<? echo $strWarning3; ?>");
	}
	else{
		document.GUI.go.value = "Druckausschnitt_loeschen";
		document.GUI.submit();
	}
}

function rotate_print_extent(angle){
	svgdoc = document.SVG.getSVGDocument();
	extent = svgdoc.getElementById('auswahl');
	rotation = extent.getAttributeNS(null, "transform");
	rotation = rotation.substring(7);
	rot_parts = rotation.split(' ');
	rot_parts[0] = angle;
	rotation = rot_parts.join(' ');
	extent.setAttribute('transform', 'rotate('+rotation);
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
<table border="0" cellspacing="2" cellpadding="2">
  <tr align="center"> 
    <td colspan="5" style="height: 30px"><h2><? echo $strTitle; ?></h2>
    </td>
  </tr>

  <tr valign="top"> 
    <td align="center" colspan="5" style="border:1px solid #C3C7C3"> 
      <?php
      include(LAYOUTPATH.'snippets/SVG_druckausschnittswahl.php');
    ?>
    </td>
		
		
		
    <td valign="top">
      <table cellspacing=0 cellpadding=2 border=0 style="border:1px solid #C3C7C3;">
        <tr align="center">
          <td><?php echo $strAvailableLayer; ?>:</td>
        </tr>
        <tr align="left">
          <td>
          <div align="center"><input type="button" name="neuladen_button" onclick="neuLaden();" value="<?php echo $strLoadNew; ?>"></div>
          <br>
        	<div style="width:230; height:<?php echo $this->map->height-59; ?>; overflow:auto; scrollbar-base-color:<?php echo BG_DEFAULT ?>">
	          &nbsp;
	          <img src="graphics/tool_info_2.png" alt="Informationsabfrage" title="Informationsabfrage" width="17">&nbsp;
	          <img src="graphics/layer.png" alt="Themensteuerung" title="Themensteuerung" width="20" height="20"><br>
						<input type="hidden" name="nurFremdeLayer" value="<? echo $this->formvars['nurFremdeLayer']; ?>">
	          <div id="legend_div"><? echo $this->legende; ?></div>
	        </div>
          </td>
        </tr>
      </table>
     </td>
		 
		 
		 
  </tr>


</table>
<br>
<input type="hidden" name="loadmapsource" value="<? echo $this->formvars['loadmapsource']; ?>">
<? if($this->formvars['loadmapsource'] == 'Post'){ ?>
	<input type="hidden" name="go" value="Externer_Druck">	
<? }else{ ?>
	<input type="hidden" name="go" value="Druckausschnittswahl">
<? } ?>

<input type="hidden" name="mapwidth" value="<?php echo $this->Document->activeframe[0]['mapwidth']; ?>">
<input type="hidden" name="mapheight" value="<?php echo $this->Document->activeframe[0]['mapheight']; ?>">
<input type="hidden" name="printextent" value="">
<input type="hidden" name="map_factor" value="<? echo $this->formvars['map_factor'] ?>">
<input type="hidden" name="neuladen" value="">
<input type="hidden" name="free_polygons" value="<? echo $this->formvars['free_polygons'] ?>">
<input type="hidden" name="free_texts" value="<? echo $this->formvars['free_texts'] ?>">

<!-- für den externen Druck -->
<input type="hidden" name="post_width" value="<? echo $this->formvars['post_width'] ?>">
<input type="hidden" name="post_height" value="<? echo $this->formvars['post_height'] ?>">
<input type="hidden" name="post_epsg" value="<? echo $this->formvars['post_epsg'] ?>">
<input type="hidden" name="post_map_factor" value="<? echo $this->formvars['post_map_factor'] ?>">

<? 
	$i = 0;
  while($this->formvars['layer'][$i]['name'] != '') { ?>
		<input type="hidden" name="layer[<? echo $i ?>][name]" value="<? echo $this->formvars['layer'][$i][name]; ?>">
		<input type="hidden" name="layer[<? echo $i ?>][epsg_code]" value="<? echo $this->formvars['layer'][$i][epsg_code]; ?>">
		<input type="hidden" name="layer[<? echo $i ?>][minscale]" value="<? echo $this->formvars['layer'][$i][minscale]; ?>">
		<input type="hidden" name="layer[<? echo $i ?>][maxscale]" value="<? echo $this->formvars['layer'][$i][maxscale]; ?>">
		<input type="hidden" name="layer[<? echo $i ?>][connection]" value="<? echo $this->formvars['layer'][$i][connection]; ?>">
		<input type="hidden" name="layer[<? echo $i ?>][transparency]" value="<? echo $this->formvars['layer'][$i][transparency]; ?>">
<?	$i++;
	}?>



