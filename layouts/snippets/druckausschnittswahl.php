<?php
 # 2008-01-22 pkvvm
  include(LAYOUTPATH.'languages/druckausschnittswahl_'.$this->user->rolle->language.'.php');
 ?>
<script type="text/javascript">
<!--

function setprintextent(wert){
	document.GUI.printextent.value = wert;
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
 <br>
<table border="0" cellspacing="2" cellpadding="2">
  <tr align="center"> 
    <td colspan="5"><h2><? echo $strTitle; ?></h2><br>
    </td>
  </tr>
  <tr align="center"> 
    <td colspan="5"> 
      <input class="button" type="button" name="vorschau" value="<?php echo $strButtonPrintPreview; ?>" onclick="preview();">
      <input class="button" type="button" name="drucken" value="<?php echo $strButtonPrint; ?>" onclick="druck_pdf('<? echo $this->Document->activeframe[0]['Name']; ?>', '<? echo $this->Document->activeframe[0]['format']; ?>', <? echo $this->Document->activeframe[0]['preis']; ?>);">
      <br>
    </td>
  </tr>
	<tr align="center"> 
    <td valign="top" align="left">			
			<div style="width:350px;" onmouseover="document.getElementById('scales').style.display='inline-block';" onmouseout="document.getElementById('scales').style.display='none';">
				<?php echo $strButtonPrintScale; ?><input type="text" size="7" name="printscale" onkeydown="setprintextent('false');" autocomplete="off" value="<?php echo $this->formvars['printscale']; ?>">
				<div valign="top" style="height:0px; position:relative;">
					<div id="scales" style="display:none; position:absolute; left:95px; top:-1px; width: 78px; vertical-align:top; overflow:hidden; border:solid grey 1px;">
						<select size="<? echo count($this->selectable_scales); ?>" style="padding:4px; margin:-2px -17px -4px -4px;" onclick="document.GUI.printscale.value=this.value; document.getElementById('scales').style.display='none';setprintextent('false');">
							<? 
								foreach($this->selectable_scales as $scale){
									echo '<option onmouseover="this.selected = true;" value="'.$scale.'">1:&nbsp;&nbsp;'.$scale.'</option>';
								}
							?>
						</select>
					</div>
				</div>
			</div>
			
    </td>
    <td valign="top" align="right" colspan="4">
    	<?php echo $strPrintFrame; ?>
    	<select name="aktiverRahmen" onchange="document.GUI.go.value='Druckausschnittswahl';document.GUI.target = '';document.GUI.submit()">
            <?  
            for($i = 0; $i < count($this->Document->frames); $i++){            	
              echo ($this->Document->activeframe[0]['id']<>$this->Document->frames[$i]['id']) ? '<option value="'.$this->Document->frames[$i]['id'].'">'.$this->Document->frames[$i]['Name'].'</option>' : '<option value="'.$this->Document->frames[$i]['id'].'" selected>'.$this->Document->frames[$i]['Name'].'</option>';
            }
            ?>
      </select>
    	&nbsp;(<? echo $this->Document->activeframe[0]['format'].')'; ?>
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
          <td>Verfügbare Themen:</td>
        </tr>
        <tr align="left">
          <td>
          <div align="center"><input type="submit" class="button" name="neuladen" value="neu Laden"></div>
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

  <tr align="center"> 
    <td valign="top" align="left">
    	<?php echo $strRotationAngle; ?><input type="text" size="3" name="angle" value="<?php echo $this->formvars['angle']; ?>">&nbsp;°
    </td>
    <td align="left">
    	<? if($this->Document->activeframe[0]['refmapfile']){ 
    		if(!isset($this->formvars['referencemap']))$this->formvars['referencemap'] = 1;
    	echo $strReferenceMap; ?>&nbsp;<input type="checkbox" name="referencemap" value="1" <? if($this->formvars['referencemap']) echo 'checked="true"'; ?>">
    	<? } ?>
    </td>
		<td align="left">
    	<? if($this->Document->activeframe[0]['legendsize'] > 0){ 
    	echo $strLegendExtra; ?>&nbsp;<input type="checkbox" name="legend_extra" value="1" <? if($this->formvars['legend_extra']) echo 'checked="true"'; ?>">
    	<? } ?>
    </td>
		<td align="left">
    	<? echo $strNoMinMaxscaling; ?>&nbsp;<input type="checkbox" name="no_minmax_scaling" onclick="document.GUI.submit();" value="1" <? if($this->formvars['no_minmax_scaling']) echo 'checked="true"'; ?>">
    </td>
    <td align="right"> 
      <?php echo $strPrintDetail; ?>
    	<input type="text" name="name" value="" style="width:120px" >&nbsp;<input class="button" type="button" style="width:84px" name="speichern" value="<?php echo $this->strSave; ?>" onclick="save();">
    </td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  	<td align="right"  colspan="4">
  		<input class="button" type="button" style="width:84px" name="delete" value="<?php echo $this->strDelete; ?>" onclick="remove();">&nbsp;

  		<select name="druckausschnitt" style="width:120px">
  			<option value=""><?php echo $this->strPleaseSelect; ?></option>
  			<?
  				for($i = 0; $i < count($this->Document->ausschnitte); $i++){
  					echo '<option value="'.$this->Document->ausschnitte[$i]['id'].'">'.$this->Document->ausschnitte[$i]['name'].'</option>';
  				}
  			?>
  		</select>
  		<input class="button" type="button" style="width:84px" name="laden" value="<?php echo $strLoad; ?>" onclick="load();">
    </td>
  </tr>
  <?
  	# Wenn der Druckrahmen Freitexte hat, die leer sind, werden dem Nutzer Textfelder angeboten um die Freitexte selber zu belegen
		for($j = 0; $j < count($this->Document->activeframe[0]['texts']); $j++){
      if($this->Document->activeframe[0]['texts'][$j]['text'] == ''){
      	# falls man von der Vorschau zurück gekommen ist
      	$this->formvars['freetext_'.$this->Document->activeframe[0]['texts'][$j]['id']] = str_replace(';', chr(10), $this->formvars['freetext_'.$this->Document->activeframe[0]['texts'][$j]['id']]);
	?>
					<tr>
				  	<td colspan="2">Freitext <? echo $j+1; ?>:</td>
				  </tr>
				  <tr>
				  	<td colspan="2">
				  		<textarea name="freetext_<? echo $this->Document->activeframe[0]['texts'][$j]['id']; ?>" cols="40" rows="2"><? echo $this->formvars['freetext_'.$this->Document->activeframe[0]['texts'][$j]['id']]; ?></textarea>
				  	</td>
				  </tr>
	<?
      }
    }
    ?>
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
<input type="hidden" name="stopnavigation" value="0">

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



