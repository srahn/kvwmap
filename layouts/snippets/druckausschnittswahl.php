<?php
 # 2008-01-22 pkvvm
  include(LAYOUTPATH.'languages/druckausschnittswahl_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
 ?>
<script type="text/javascript">
<!--

function setprintextent(wert){
	document.GUI.printextent.value = wert;
}

function preview(){
	if(document.GUI.printextent.value == 'false'){
		alert("Bitte aktualisieren Sie den Druckausschnitt durch Klick in die Maßstabseingabe und dann [Enter].");
	}
	else{
		if(Math.abs(document.GUI.angle.value) > 90){
			alert("Bitte geben Sie einen Winkel zwischen -90° und 90° an.");
		}
		else{
			if(document.GUI.printscale.value != ''){
				if(document.GUI.center_x.value != ''){
					document.GUI.go.value = "Druckausschnittswahl_Vorschau";
					document.GUI.submit();
				}
				else{
					alert("Bitte wählen Sie einen Druckausschnitt aus.");
				}
			}
			else{
				alert("Bitte geben Sie einen Druckmassstab ein.");
			}
		}
	}
}

function save(){
	if(document.GUI.name.value == ''){
		alert("Bitte geben Sie einen Namen für den Druckausschnitt an.");
	}
	else{
		if(Math.abs(document.GUI.angle.value) > 90){
			alert("Bitte geben Sie einen Winkel zwischen -90° und 90° an.");
		}
		else{
			if(document.GUI.printscale.value != ''){
				if(document.GUI.center_x.value != ''){
					document.GUI.go.value = "Druckausschnitt_speichern";
					document.GUI.submit();
				}
				else{
					alert("Bitte wählen Sie einen Druckausschnitt aus.");
				}
			}
			else{
				alert("Bitte geben Sie einen Druckmassstab ein.");
			}
		}
	}
}

function load(){
	if(document.GUI.druckausschnitt.value == ''){
		alert("Bitte wählen Sie einen Druckausschnitt aus.");
	}
	else{
		document.GUI.submit();
	}
}

function remove(){
	if(document.GUI.druckausschnitt.value == ''){
		alert("Bitte wählen Sie einen Druckausschnitt aus.");
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
 ?> <strong><font size="+1"> </font></strong> 
<table border="0" cellspacing="2" cellpadding="2">
  <tr align="center"> 
    <td colspan="4"><strong><font size="+1"> 
      <?php echo $strTitle; ?><br><br>
      </font></strong>
    </td>
  </tr>
  <tr align="center"> 
    <td colspan="4"> 
      <input class="button" type="button" name="vorschau" value="<?php echo $strButtonPrintPreview; ?>" onclick="preview();">
      <br><br>
    </td>
  </tr>
	<tr align="center"> 
    <td valign="top" align="left">
    	<?php echo $strButtonPrintScale; ?><input type="text" size="7" name="printscale" onkeydown="setprintextent('false');" value="<?php echo $this->formvars['printscale']; ?>">
    </td>
    <td valign="top" align="right" colspan="3">
    	<?php echo $strPrintFrame; ?>
    	<select name="aktiverRahmen" onchange="document.GUI.submit()">
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
    <td align="center" colspan="4" style="border:1px solid #C3C7C3"> 
      <?php
      include(LAYOUTPATH.'snippets/SVG_druckausschnittswahl.php');
    ?>
    </td>
  </tr>

  <tr align="center"> 
    <td valign="top" align="left">
    	<?php echo $strRotationAngle; ?><input type="text" size="3" name="angle" value="<?php echo $this->formvars['angle']; ?>">&nbsp;°
    </td>
    <td colspan="2"  align="right"> 
      <?php echo $strPrintDetail; ?>

    	<input type="text" name="name" value="" style="width:120px" >&nbsp;<input class="button" type="button" style="width:84px" name="speichern" value="<?php echo $this->strSave; ?>" onclick="save();">
    </td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  	<td align="right"  colspan="2">
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
  	# Wenn der Druckrahmen Freitexte hat, die leer sind, werden dem Nutzer Textfelder angeboten um die Freitexte selber belegen
		for($j = 0; $j < count($this->Document->activeframe[0]['texts']); $j++){
      if($this->Document->activeframe[0]['texts'][$j]['text'] == ''){
      	# falls man von der Vorschau zurück gekommen ist
      	$this->formvars['freetext'.$this->Document->activeframe[0]['texts'][$j]['id']] = str_replace(';', chr(10), $this->formvars['freetext'.$this->Document->activeframe[0]['texts'][$j]['id']]);
	?>
					<tr>
				  	<td colspan="2">Freitext <? echo $j+1; ?>:</td>
				  </tr>
				  <tr>
				  	<td colspan="2">
				  		<textarea name="freetext<? echo $this->Document->activeframe[0]['texts'][$j]['id']; ?>" cols="40" rows="2"><? echo $this->formvars['freetext'.$this->Document->activeframe[0]['texts'][$j]['id']]; ?></textarea>
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



