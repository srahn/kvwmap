
<script language="JavaScript">
<!--

function send(){
	if(document.GUI.bodenrichtwert.value == ''){alert('Bitte geben Sie einen Bodenrichtwert an.');exit();}
	if(document.GUI.stichtag.value == ''){alert('Bitte geben Sie einen Stichtag an.');exit();}
	if(document.GUI.gemeinde.value == ''){alert('Bitte geben Sie eine Gemeinde an.');exit();}
	if(document.GUI.gemarkung.value == ''){alert('Bitte geben Sie eine Gemarkung an.');exit();}
	if(document.GUI.ortsteilname.value == ''){alert('Bitte geben Sie einen Ortsteilnamen an.');exit();}
	if(document.GUI.postleitzahl.value == ''){alert('Bitte geben Sie eine Postleitzahl an.');exit();}
	if(document.GUI.zonentyp.value == ''){alert('Bitte geben Sie einen Zonentyp an.');exit();}
	if(document.GUI.entwicklungszustand.value == ''){alert('Bitte geben Sie einen Entwicklungszustand an.');exit();}
	if(document.GUI.beitragszustand.value == ''){alert('Bitte geben Sie einen Beitragszustand an.');exit();}
	if(document.GUI.nutzungsart.value == ''){alert('Bitte geben Sie eine Nutzungsart an.');exit();}
	if(document.GUI.newpathwkt.value == ''){
		if(document.GUI.newpath.value == ''){
			alert('Geben Sie ein Polygon an.');
		}
		else{
			document.GUI.newpathwkt.value = buildwktpolygonfromsvgpath(document.GUI.newpath.value);
			document.GUI.go_plus.value = 'Senden';
			document.GUI.submit();
		}
	}
	else{
		document.GUI.go_plus.value = 'Senden';
		document.GUI.submit();
	}
}

function update_verfahren(){
	if(document.GUI.verfahrensgrund.value == 'SAN'){
		if(document.GUI.verfahrensgrund_zusatz.value == ''){
			document.GUI.verfahrensgrund_zusatz.value = 'A';
		}
	}
	else{
		document.GUI.verfahrensgrund_zusatz.value = '';
	}
}

function buildwktpolygonfromsvgpath(svgpath){
	var koords;
	wkt = "POLYGON((";
	parts = svgpath.split("M");
	for(j = 1; j < parts.length; j++){
		if(j > 1){
			wkt = wkt + "),("
		}
		koords = ""+parts[j];
		coord = koords.split(" ");
		wkt = wkt+coord[1]+" "+coord[2];
		for(var i = 3; i < coord.length-1; i++){
			if(coord[i] != ""){
				wkt = wkt+","+coord[i]+" "+coord[i+1];
			}
			i++;
		}
	}
	wkt = wkt+"))";
	return wkt;
}

function update_require_attribute(attributes, layer_id, value){
	// attributes ist eine Liste von zu aktualisierenden Attributen und value der ausgewaehlte Wert
	attribute = attributes.split(',');
	for(i = 0; i < attribute.length; i++){
		ahah("<? echo URL.APPLVERSION; ?>index.php", "go=get_select_list&layer_id="+layer_id+"&attribute="+attribute[i]+"&value="+value+"&type=select-one", new Array(document.getElementsByName(attribute[i])[0]), 'sethtml');
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
}
 ?>

	  
<table border="0" cellpadding="4" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="6"><strong><font size="+1"><?php echo $this->titel; ?></font></strong></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
    		<tr>
    			<td> 
				    <?php
					  # Wenn ein Polygon übergeben wird, wird es in SVG mit dargestellt.
				      include(LAYOUTPATH.'snippets/SVG_polygon_and_point.php');
				    ?>
				  </td>
				</tr>
				<tr>
					<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td><img src="<? echo GRAPHICSPATH; ?>leer.gif" height="5"></td>
							</tr>
							<tr>
								<td valign="top">
									<table border="0" cellpadding="0" cellspacing="0">
									  <tr>
									  	<td>
									  		<table border="0" style="border:1px steelblue solid" cellpadding="3" cellspacing="0">
									  			<tr> 
												    <td>
												    	Basiskarte:
												    </td>
												    <td colspan="2">
												      <?php 
												        $FormatWerte = array('ALKDOP', 'ALK', 'DOP', 'DTK10');
												        $FormatBez = array('ALKDOP', 'ALK', 'DOP', 'DTK10'); 
												        $basiskarte = new FormObject('basiskarte','select',$FormatWerte,array($this->formvars['basiskarte']),$FormatBez,1,$maxlenght,$multiple,146);
												        $basiskarte->OutputHTML();
												        echo $basiskarte->html;
												      ?>
												    </td>
												  </tr>
												  <tr> 
												    <td>
												    	Entwicklungszustand:
												    </td>
												    <td colspan="2"> 
												      <?php 
												        $FormatWerte = array('','R','E','B','L');
												        $FormatBez = array('-- Bitte wählen --','Rohbauland','Bauerwartungsland','baureifes Land','Land- und Forstwirtschaft'); 
												        $zustand = new FormObject('entwicklungszustand','select',$FormatWerte,array($this->formvars['entwicklungszustand']),$FormatBez,1,$maxlenght,$multiple,146);
												        $zustand->OutputHTML();
												        echo $zustand->html;
												      ?>
												    </td>
												  </tr>
												  <tr> 
												    <td>
												    	Beitragszustand:
												    </td>
												    <td colspan="2"> 
												      <?php 
												        $FormatWerte = array('', 'frei', 'pflichtig', 'ortsüblich erschlossen');
												        $FormatBez = array('-- Bitte wählen --', 'frei', 'pflichtig', 'ortsüblich erschlossen'); 
												        $zustand = new FormObject('beitragszustand','select',$FormatWerte,array($this->formvars['beitragszustand']),$FormatBez,1,$maxlenght,$multiple,146);
												        $zustand->OutputHTML();
												        echo $zustand->html;
												      ?>
												    </td>
												  </tr>
												  <tr> 
												    <td>
												    	Nutzungsart:
												    </td>
												    <td colspan="2"> 
												      <?php 
												        $FormatWerte = array('', 'A',' A/GR',' F',' G',' GE',' GE/GI',' GE/MI',' GI',' GR',' H',' L',' M',' MD',' MI',' MI/MK',' MI/W',' MK',' S',' SO',' SW',' W',' W/M',' WA',' WA/MD',' WA/MI',' WA/WB',' WA/WR',' WB',' WR',' WS');
												        $FormatBez = array('-- Bitte wählen --', 'A',' A/GR',' F',' G',' GE',' GE/GI',' GE/MI',' GI',' GR',' H',' L',' M',' MD',' MI',' MI/MK',' MI/W',' MK',' S',' SO',' SW',' W',' W/M',' WA',' WA/MD',' WA/MI',' WA/WB',' WA/WR',' WB',' WR',' WS'); 
												        $nutzung = new FormObject('nutzungsart','select',$FormatWerte,array($this->formvars['nutzungsart']),$FormatBez,1,$maxlenght,$multiple,146);
												        $nutzung->OutputHTML();
												        echo $nutzung->html;
												      ?>
												    </td>
												  </tr>
												  <tr> 
												    <td>
												    	Ergänz. Nutzung:
												    </td>
												    <td colspan="2" align="right">
												    	<input name="ergaenzende_nutzung" type="text" style="width:146px" id="ergaenzende_nutzung" value="<?php echo $this->formvars['ergaenzende_nutzung']; ?>">
												    </td>
												  </tr>
												  <tr> 
												    <td>
												    	Wegerschließung:
												    </td>
												    <td colspan="2" align="right"> 
												      <?php 
												        $FormatWerte = array('','ohne','mit');
												        $FormatBez = array('-- Bitte wählen --','ohne','mit'); 
												        $wegeerschliessung = new FormObject('wegeerschliessung','select',$FormatWerte,array($this->formvars['wegeerschliessung']),$FormatBez,1,$maxlenght,$multiple,146);
												        $wegeerschliessung->OutputHTML();
												        echo $wegeerschliessung->html;
												      ?>
												    </td>
												  </tr>
												</table>
									  	</td>
									  </tr>
									  <tr>
									  	<td><img src="<? echo GRAPHICSPATH; ?>leer.gif" height="5"></td>
									  </tr>
									  <tr>
									  	<td width="100%">
									  		<table width="100%" border="0" style="border:1px steelblue solid" cellpadding="3" cellspacing="0">
									  			<tr> 
												    <td>
												    	Ackerzahl:
												    </td>
												    <td colspan="2" align="right"> 
												      <input name="ackerzahl" type="text" style="width:146px" id="ackerzahl" value="<?php echo $this->formvars['ackerzahl']; ?>">
												    </td>
												  </tr>
												  <tr> 
												    <td>
												    	Grünlandzahl:
												    </td>
												    <td colspan="2" align="right"> 
												      <input name="gruenlandzahl" type="text" style="width:146px" id="gruenlandzahl" value="<?php echo $this->formvars['gruenlandzahl']; ?>">
												    </td>
												  </tr>
												  <tr> 
												    <td>
												    	Aufwuchs:
												    </td>
												    <td colspan="2" align="right"> 
												      <?php 
												        $FormatWerte = array('','ohne','mit');
												        $FormatBez = array('-- Bitte wählen --','ohne','mit'); 
												        $aufwuchs = new FormObject('aufwuchs','select',$FormatWerte,array($this->formvars['aufwuchs']),$FormatBez,1,$maxlenght,$multiple,146);
												        $aufwuchs->OutputHTML();
												        echo $aufwuchs->html;
												      ?>
												    </td>
												  </tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
								<td><img src="<? echo GRAPHICSPATH; ?>leer.gif" width="5"></td>
								<td valign="top">
									<table border="0" style="border:1px steelblue solid" cellpadding="3" cellspacing="0">
									  <tr> 
									    <td>
									    	Bauweise:
									    </td>
									    <td colspan="2"> 
									      <?php 
									        $FormatWerte = array('', 'DH', 'EH', 'g', 'o', 'REH', 'RH', 'RMH');
									        $FormatBez = array('-- Bitte wählen --', 'DH', 'EH', 'geschlossen', 'offen', 'REH', 'RH', 'RMH'); 
									        $bauweise = new FormObject('bauweise','select',$FormatWerte,array($this->formvars['bauweise']),$FormatBez,1,$maxlenght,$multiple,NULL);
									        $bauweise->OutputHTML();
									        echo $bauweise->html;
									      ?>
									    </td>
									  </tr>
						  			<tr> 
									    <td>
									    	Geschosszahl:
									    </td>
									    <td colspan="2">
									      <?php 
									        $FormatWerte = array('', 'I', 'I-II', 'II', 'II-III', 'III', 'III-IV', 'IV', 'IV-V', 'V', 'V-VI', 'VI');
									        $FormatBez = array('-- Bitte wählen --', 'I', 'I-II', 'II', 'II-III', 'III', 'III-IV', 'IV', 'IV-V', 'V', 'V-VI', 'VI'); 
									        $geschosszahl = new FormObject('geschosszahl','select',$FormatWerte,array($this->formvars['geschosszahl']),$FormatBez,1,$maxlenght,$multiple,NULL);
									        $geschosszahl->OutputHTML();
									        echo $geschosszahl->html;
									      ?>
									    </td>
									  </tr>
									  <tr> 
									    <td>
									    	Geschossflächenzahl:
									    </td>
									    <td colspan="2"> 
									      <input name="geschossflaechenzahl" type="text" size="13" id="geschossflaechenzahl" value="<?php echo $this->formvars['geschossflaechenzahl']; ?>">
									    </td>
									  </tr>
									  <tr> 
									    <td>
									    	Grundflächenzahl:
									    </td>
									    <td colspan="2"> 
									      <input name="grundflaechenzahl" type="text" size="13" id="grundflaechenzahl" value="<?php echo $this->formvars['grundflaechenzahl']; ?>">
									    </td>
									  </tr>
									  <tr> 
									    <td>
									    	Baumassenzahl:
									    </td>
									    <td colspan="2"> 
									      <input name="baumassenzahl" type="text" size="13" id="baumassenzahl" value="<?php echo $this->formvars['baumassenzahl']; ?>">
									    </td>
									  </tr>
									  <tr> 
									    <td>
									    	Fläche: [m<sup>2</sup>]
									    </td>
									    <td colspan="2"> 
									      <input name="flaeche" type="text" size="13" id="flaeche" value="<?php echo $this->formvars['flaeche']; ?>">
									    </td>
									  </tr>
									  <tr> 
									    <td>
									    	Tiefe: [m]
									    </td>
									    <td colspan="2"> 
									      <input name="tiefe" type="text" size="13" id="tiefe" value="<?php echo $this->formvars['tiefe']; ?>">
									    </td>
									  </tr>
									  <tr> 
									    <td>
									    	Breite: [m]
									    </td>
									    <td colspan="2"> 
									      <input name="breite" type="text" size="13" id="breite" value="<?php echo $this->formvars['breite']; ?>">
									    </td>
									  </tr>
									  <tr> 
									    <td>
									    	Verfahren:
									    </td>
									    <td colspan="2">
									    	<?php 
									        $FormatWerte = array('', 'SAN', 'ENT');
									        $FormatBez = array('kein','SAN', 'ENT'); 
									        $verfahren = new FormObject('verfahrensgrund','select',$FormatWerte,array($this->formvars['verfahrensgrund']),$FormatBez,1,$maxlenght,$multiple,NULL);
									        $verfahren->addJavaScript('onchange', "update_verfahren();");
									        $verfahren->OutputHTML();
									        echo $verfahren->html;
									       
									        $FormatWerte = array('','A','E');
									        $FormatBez = array('kein','A','E'); 
									        $verfahren = new FormObject('verfahrensgrund_zusatz','select',$FormatWerte,array($this->formvars['verfahrensgrund_zusatz']),$FormatBez,1,$maxlenght,$multiple,NULL);
									        $verfahren->addJavaScript('onchange', "update_verfahren();");
									        $verfahren->OutputHTML();
									        echo $verfahren->html;
									      ?>  
									    </td>
									  </tr>
						  		</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
	 		</table>
	 	</td>
	 	<td valign="top">
	 		<table cellpadding="0" cellspacing="0" border="0">
			  <tr>
			  	<td>
			  		<table border="0" cellpadding="4" cellspacing="0">
						  <tr> 
						    <td colspan="3">Zonennummer: 
						      <input name="zonennr" type="text" id="Zonennr" value="<?php echo $this->formvars['zonennr']; ?>" size="5" maxlength="5"> 
						    </td>
						  </tr>
						  <tr> 
						    <td colspan="3">Standort: 
						      <input name="standort" type="text" value="<?php echo $this->formvars['standort']; ?>" size="25" maxlength="255"> 
						    </td>
						  </tr>
						  <tr> 
						    <td colspan="3">Richtwertdef.: 
						      <input name="richtwertdefinition" type="text" id="richtwertdefinition" value="<?php echo $this->formvars['richtwertdefinition']; ?>" size="21" maxlength="50"> 
						    </td>
						  </tr>
						</table>
					</td>
				</tr>
				
				<tr>
			  	<td>
			  		<table border="0" style="border:1px steelblue solid" cellpadding="3" cellspacing="0">
			  			<tr> 
						    <td colspan="4">
						    	Land: 13 (Mecklenburg-Vorpommern)
						    </td>
						  </tr>
						  <tr> 
						    <td colspan="4">
						    	Gutachterausschuss:
						    	<?php 
						    		global $gutachterausschuesse;
						        $FormatWerte = array_merge(array(''), $gutachterausschuesse);
						        $FormatBez = array_merge(array('--- Bitte wählen ---'), $gutachterausschuesse);
						        $gutachterausschuss = new FormObject('gutachterausschuss','select',$FormatWerte,array($this->formvars['gutachterausschuss']),$FormatBez,1,$maxlenght,$multiple,120);
						        $gutachterausschuss->OutputHTML();
						        echo $gutachterausschuss->html;
						      ?>
						    </td>
						  </tr>	 
						  <tr> 
						    <td colspan="2">
						    	Bodenrichtwertnummer:
						    </td>
						    <td>
						    	<?php if ($this->formvars['bodenrichtwertnummer']=='') {  echo 'neue Nummer'; } else {  echo $this->formvars['bodenrichtwertnummer']; }?>
						    </td>
						  </tr>
						  <tr> 
						    <td colspan="2">
						    	Bodenrichtwert: [&euro;/m&sup2;]
						    </td>
						    <td> 
						      <input name="bodenrichtwert" type="text" id="bodenrichtwert" value="<?php echo $this->formvars['bodenrichtwert']; ?>" size="5" maxlength="5">
						    </td>
						  </tr>
						  <tr> 
						    <td>
						    	Stichtag:
						    </td>
						    <td align="right">
						    	31.12.
						    </td>
						    <td>
						    	<input name="stichtag" type="text" value="<?php echo array_pop(explode('.', $this->formvars['stichtag'])); ?>" size="5" maxlength="5">
						    </td>
						  </tr>
						  <tr> 
						    <td>
						    	Gemeinde:
						    </td>
						    <td colspan="3"> 
						  <?php
						    $this->GemFormObj->outputHTML();
						    echo $this->GemFormObj->html;
						  ?>
						    </td>
						  </tr>
						  <tr> 
						    <td>
						    	Gemarkung:
						   	</td>
						   	<td colspan="3"> 
						  <?php
						    $this->GemkgFormObj->outputHTML();
						    echo $this->GemkgFormObj->html;
						  ?>
						    </td>
						  </tr>
							<tr> 
						    <td>
						    	Ortsteil:
						    </td>
						    <td colspan="3"> 
						      <input name="ortsteilname" type="text" size="22" maxlength="100" id="ortsteilname" value="<?php echo $this->formvars['ortsteilname']; ?>">
						    </td>
						  </tr>
						  <tr> 
						    <td>
						    	Postleitzahl:
						    </td>
						    <td> 
						      <input name="postleitzahl" type="text" id="postleitzahl" size="4"  maxlength="5" value="<?php echo $this->formvars['postleitzahl']; ?>">
						    </td>
						  </tr>
						  <tr> 
						    <td>
						    	Zonentyp:
						    </td>
						    <td colspan="3"> 
						      <?php 
						        $FormatWerte = array('', 'Plangebiet', 'ortsüblich erschlossen', 'Gewerbegebiet', 'Sanierungsgebiet', 'Ackerland', 'Grünland');
						        $FormatBez = array('-- Bitte wählen --', 'Plangebiet', 'ortsüblich erschlossen', 'Gewerbegebiet', 'Sanierungsgebiet', 'Ackerland', 'Grünland'); 
						        $zonentyp = new FormObject('zonentyp','select',$FormatWerte,array($this->formvars['zonentyp']),$FormatBez,1,$maxlenght,$multiple,158);
						        $zonentyp->OutputHTML();
						        echo $zonentyp->html;
						      ?>
						    </td>
						  </tr>
						  <tr>
						  	<td colspan="4">
						  		Örtliche Bezeichnung:<br>
						  		<textarea cols="30" rows="2" name="oertliche_bezeichnung"><? echo $this->formvars['oertliche_bezeichnung']; ?></textarea>
						  	</td>
						  </tr>
						  <tr>
						  	<td colspan="4">
						  		Bemerkungen:<br>
						  		<textarea cols="30" rows="2" name="bemerkungen"><? echo $this->formvars['bemerkungen']; ?></textarea>
						  	</td>
						  </tr>
						</table>
					</td>
				</tr>
				
			  <tr>
			  	<td colspan="3">Geometrie übernehmen von:<br>
			  		<select name="layer_id" onchange="document.GUI.submit();">
			  			<option value="">--- Auswahl ---</option>
			  			<?
			  				for($i = 0; $i < count($this->queryable_vector_layers['ID']); $i++){
			  					echo '<option';
			  					if($this->formvars['layer_id'] == $this->queryable_vector_layers['ID'][$i]){echo ' selected';}
			  					echo ' value="'.$this->queryable_vector_layers['ID'][$i].'">'.$this->queryable_vector_layers['Bezeichnung'][$i].'</option>';
			  				}
			  			?>
			  		</select> 
			  	</td>
			  </tr>
			  <tr> 
			    <td colspan="3" align="left">
			    	<table border="0">
			        <tr> 
			          <td><input type="reset" name="reset" value="Zurücksetzen"></td>
			          <td><input type="button" name="senden" value="Senden" onclick="send();"></td>
			        </tr>
			      </table>
			    </td>
			  </tr>
			</table>
		</td>
	</tr>
</table>
<input type="hidden" name="oid" value="<? echo $this->formvars['oid']; ?>">
<input type="hidden" name="area" value="">
<input type="hidden" name="go" value="Bodenrichtwertformular">
<input type="hidden" name="go_plus" value="">
<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="<?php echo $this->formvars['columnname']; ?>">
<INPUT TYPE="HIDDEN" NAME="fromwhere" VALUE="<? echo $this->formvars['fromwhere']; ?>">

<?php
	if ($this->Meldung!='') {
		showAlert('Fehler bei der Eingabe:\n'.$this->Meldung);
}
 ?>

