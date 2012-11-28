
<script language="JavaScript">
<!--

function toggle_vertices(){	
	document.getElementById("vertices").SVGtoggle_vertices();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
}

function send(){
	if(document.GUI.bodenrichtwert.value == ''){alert('Bitte geben Sie einen Bodenrichtwert an.');exit();}
	if(document.GUI.stichtag.value == ''){alert('Bitte geben Sie einen Stichtag an.');exit();}
	if(document.GUI.gemeinde.value == ''){alert('Bitte geben Sie eine Gemeinde an.');exit();}
	if(document.GUI.gemarkung.value == ''){alert('Bitte geben Sie eine Gemarkung an.');exit();}
	if(document.GUI.zonentyp.value == ''){alert('Bitte geben Sie einen Zonentyp an.');exit();}
	if(document.GUI.zonentyp.value == 'Grünland' && document.GUI.gruenlandzahl.value == ''){alert('Bitte geben Sie eine Grünlandzahl an.');exit();}
	if(document.GUI.zonentyp.value == 'Ackerland' && document.GUI.ackerzahl.value == ''){alert('Bitte geben Sie eine Ackerzahl an.');exit();}
	if(document.GUI.entwicklungszustand.value == ''){alert('Bitte geben Sie einen Entwicklungszustand an.');exit();}
	if(document.GUI.entwicklungszustand.value == 'B' && document.GUI.beitragszustand.value == ''){alert('Bitte geben Sie einen Beitragszustand an.');exit();}
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

function add_options(select, options, firstoption){
	if(firstoption)select.options[select.length] = new Option(firstoption, '');
	for(i = 0; i < options.length; i++){
		select.options[select.length] = new Option(options[i], options[i]);
	}
}

function update_verfahren(){
	document.GUI.verfahrensgrund_zusatz.length = 0;
	if(document.GUI.verfahrensgrund.value == 'Entw'){
		add_options(document.GUI.verfahrensgrund_zusatz, new Array('EU', 'EB'), 'kein');
	}
	if(document.GUI.verfahrensgrund.value == 'San'){
		add_options(document.GUI.verfahrensgrund_zusatz, new Array('SU', 'SB'), 'kein');
	}
}

function update_nutzungsart(){
	document.GUI.nutzungsart.length = 0;
	if(document.GUI.entwicklungszustand.value in {'B':'', 'R':'','E':''}){
		add_options(document.GUI.nutzungsart, new Array('W', 'WS', 'WR', 'WA', 'WB', 'M', 'MD', 'MI', 'MK', 'G', 'GE', 'GI', 'S', 'SE', 'SO', 'GB'), '-- Bitte wählen --');
	}
	if(document.GUI.entwicklungszustand.value == 'LF'){
		add_options(document.GUI.nutzungsart, new Array('LW', 'A', 'GR', 'EGA', 'SK', 'WG', 'KUP', 'UN', 'F'), '-- Bitte wählen --');
	}
	if(document.GUI.entwicklungszustand.value == 'SF'){
		add_options(document.GUI.nutzungsart, new Array('PG', 'KGA', 'FGA', 'CA', 'SPO', 'SG', 'FH', 'WF', 'FP', 'PP', 'LG', 'AB', 'GF', 'SN'), '-- Bitte wählen --');
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
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
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
												        $basiskarte = new FormObject('basiskarte','select',$FormatWerte,array($this->formvars['basiskarte']),$FormatWerte,1,$maxlenght,$multiple,146);
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
												        $FormatWerte = array('','R','E','B','LF','SF');
												        $FormatBez = array('-- Bitte wählen --','Rohbauland','Bauerwartungsland','baureifes Land','Land- und Forstwirtschaft','sonstige Fläche'); 
												        $zustand = new FormObject('entwicklungszustand','select',$FormatWerte,array($this->formvars['entwicklungszustand']),$FormatBez,1,$maxlenght,$multiple,146);
												        $zustand->addJavaScript('onchange', "update_nutzungsart();");
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
												        $FormatWerte = array('', '1', '3', '2');
												        $FormatBez = array('-- Bitte wählen --', 'EB-/KB-frei', 'EB-/KB-pflichtig und abgabenpflichtig', 'EB-/KB-frei und abgabenpflichtig'); 
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
												        $FormatWerte = array('');
												        $FormatBez = array('-- Bitte wählen --'); 
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
												    	Wegeerschließung:
												    </td>
												    <td colspan="2" align="right"> 
												      <?php 
												        $FormatWerte = array('','0','1');
												        $FormatBez = array('-- Bitte wählen --','nicht erschlossen','erschlossen'); 
												        $wegeerschliessung = new FormObject('wegeerschliessung','select',$FormatWerte,array($this->formvars['wegeerschliessung']),$FormatBez,1,$maxlenght,$multiple,146);
												        $wegeerschliessung->OutputHTML();
												        echo $wegeerschliessung->html;
												      ?>
												    </td>
												  </tr>
												  <tr> 
												    <td>
												    	Erschließungsverh.:
												    </td>
												    <td colspan="2" align="right"> 
												      <?php 
												        $FormatWerte = array('','1','2');
												        $FormatBez = array('-- Bitte wählen --','sehr gut','schlecht'); 
												        $erschliessung = new FormObject('erschliessung','select',$FormatWerte,array($this->formvars['erschliessung']),$FormatBez,1,$maxlenght,$multiple,146);
												        $erschliessung->OutputHTML();
												        echo $erschliessung->html;
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
												    	Bodenart:
												    </td>
												    <td colspan="2" align="right"> 
												      <?php 
												        $FormatWerte = array('','L','lS','LT','Mo','S','SL','Sl','sL','T');
												        $FormatBez = array('-- Bitte wählen --','L','lS','LT','Mo','S','SL','Sl','sL','T'); 
												        $bodenart = new FormObject('bodenart','select',$FormatWerte,array($this->formvars['bodenart']),$FormatBez,1,$maxlenght,$multiple,146);
												        $bodenart->OutputHTML();
												        echo $bodenart->html;
												      ?>
												    </td>
												  </tr>
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
												        $FormatWerte = array('', 'mA');
												        $FormatBez = array('-- Bitte wählen --','mit'); 
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
									        $FormatWerte = array('', 'o', 'g', 'a','eh', 'ed', 'dh', 'rh', 'rm');
									        $FormatBez = array('-- Bitte wählen --', 'offen', 'geschlossen', 'abweichend','Einzelhaus', 'Einzel- und Doppelhaus', 'Doppelhaushälfte', 'Reihenhaus', 'Reihenmittelhaus'); 
									        $bauweise = new FormObject('bauweise','select',$FormatWerte,array($this->formvars['bauweise']),$FormatBez,1,$maxlenght,$multiple,146);
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
									        $FormatWerte = array('', 'I', 'I-II', 'II', 'II-III', 'III', 'III-IV', 'IV', 'IV-V', 'V', 'V-VI', 'VI','VI-VII','VII','VII-VIII','VIII','VIII-IX','IX','IX-X','X','X-XI','XI','XI-XII');
									        $FormatBez = array('-- Bitte wählen --', 'I', 'I-II', 'II', 'II-III', 'III', 'III-IV', 'IV', 'IV-V', 'V', 'V-VI', 'VI','VI-VII','VII','VII-VIII','VIII','VIII-IX','IX','IX-X','X','X-XI','XI','XI-XII');  
									        $geschosszahl = new FormObject('geschosszahl','select',$FormatWerte,array($this->formvars['geschosszahl']),$FormatBez,1,$maxlenght,$multiple,146);
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
									      <input name="geschossflaechenzahl" type="text" style="width:146px" maxlength="11" id="geschossflaechenzahl" value="<?php echo $this->formvars['geschossflaechenzahl']; ?>">
									    </td>
									  </tr>
									  <tr> 
									    <td>
									    	Grundflächenzahl:
									    </td>
									    <td colspan="2"> 
									      <input name="grundflaechenzahl" type="text" style="width:146px" maxlength="9" id="grundflaechenzahl" value="<?php echo $this->formvars['grundflaechenzahl']; ?>">
									    </td>
									  </tr>
									  <tr> 
									    <td>
									    	Baumassenzahl:
									    </td>
									    <td colspan="2"> 
									      <input name="baumassenzahl" type="text" style="width:146px" maxlength="9" id="baumassenzahl" value="<?php echo $this->formvars['baumassenzahl']; ?>">
									    </td>
									  </tr>
									  <tr> 
									    <td>
									    	Fläche: [m<sup>2</sup>]
									    </td>
									    <td colspan="2"> 
									      <input name="flaeche" type="text" maxlength="12" style="width:146px" id="flaeche" value="<?php echo $this->formvars['flaeche']; ?>">
									    </td>
									  </tr>
									  <tr> 
									    <td>
									    	Tiefe: [m]
									    </td>
									    <td colspan="2"> 
									      <input name="tiefe" type="text" maxlength="8" style="width:146px" id="tiefe" value="<?php echo $this->formvars['tiefe']; ?>">
									    </td>
									  </tr>
									  <tr> 
									    <td>
									    	Breite: [m]
									    </td>
									    <td colspan="2"> 
									      <input name="breite" type="text" maxlength="8" style="width:146px" id="breite" value="<?php echo $this->formvars['breite']; ?>">
									    </td>
									  </tr>
									  <tr> 
									    <td>
									    	Verfahren:
									    </td>
									    <td colspan="2">
									    	<?php 
									        $FormatWerte = array('', 'San', 'Entw', 'StUb', 'SoSt');
									        $FormatBez = array('kein','San', 'Entw', 'StUb', 'SoSt'); 
									        $verfahren = new FormObject('verfahrensgrund','select',$FormatWerte,array($this->formvars['verfahrensgrund']),$FormatBez,1,$maxlenght,$multiple,NULL);
									        $verfahren->addJavaScript('onchange', "update_verfahren();");
									        $verfahren->OutputHTML();
									        echo $verfahren->html;
									       
									        $FormatWerte = array('');
									        $FormatBez = array('kein'); 
									        $verfahren = new FormObject('verfahrensgrund_zusatz','select',$FormatWerte,array($this->formvars['verfahrensgrund_zusatz']),$FormatBez,1,$maxlenght,$multiple,NULL);
									        $verfahren->OutputHTML();
									        echo $verfahren->html;
									      ?>  
									    </td>
									  </tr>
						  		</table>
								</td>
								<td width="100%" align="right" valign="top">
									<input type="checkbox" name="always_draw" value="1" <?if($always_draw == 1 OR $always_draw == 'true')echo 'checked'; ?>>&nbsp;weiterzeichnen&nbsp;&nbsp;
									<input type="checkbox" onclick="toggle_vertices()" name="punktfang">&nbsp;Punktfang
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
			  		<table border="0" style="border:1px steelblue solid" cellpadding="3" cellspacing="0">
			  			<tr> 
						    <td colspan="4">
						    	Land: 13 (Mecklenburg-Vorpommern)
						    </td>
						  </tr>
						  <tr> 
						    <td colspan="4">
						    	Gutachterausschuss:
						      <input name="gutachterausschuss" style="border: 0px;background-color: transparent" type="text" id="gutachterausschuss" readonly="true" value="<?php echo GUTACHTERAUSSCHUSS; ?>" size="15" maxlength="15">
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
						    	Bodenrichtwert [&euro;/m&sup2;]:
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
						    <td colspan="2">
						    	Bedarfswert [&euro;/m&sup2;]:
						    </td>
						    <td> 
						      <input name="bedarfswert" type="text" id="bedarfswert" value="<?php echo $this->formvars['bedarfswert']; ?>" size="5" maxlength="5">
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
						  		Bodenrichtwert Zonenname:<br>
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
					<td>&nbsp;</td>
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
			    <td height="40" colspan="3" align="left">
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

