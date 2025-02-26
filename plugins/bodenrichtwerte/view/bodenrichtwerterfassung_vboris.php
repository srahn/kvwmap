<?
include_once(CLASSPATH.'FormObject.php');
include(LAYOUTPATH.'languages/PolygonEditor_'.rolle::$language.'.php');
?>
<script language="JavaScript">
<!--

function toggle_vertices(){	
	document.getElementById("vertices").SVGtoggle_vertices();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
}

function send(){
	if(document.GUI.verfahrensgrund.value == 'San'){
		if(document.GUI.bodenrichtwert.value == '' && document.GUI.brwu.value == '' && document.GUI.brws.value == '' && document.GUI.brwb.value == ''){
			alert('Bitte geben Sie einen Bodenrichtwert oder San-Werte (SanU-, SanS-,SanB) an.');exit();
		}
		if(document.GUI.bodenrichtwert.value != '' && (document.GUI.brwu.value != '' || document.GUI.brws.value != '' || document.GUI.brwb.value != '')){
			alert('Sie haben einen San-Wert angegeben. In diesem Fall darf kein Bodenrichtwert angegeben werden.');exit();
		}
		if(document.GUI.brws.value != '' && document.GUI.brwu.value == ''){
			alert('Sie haben einen SanS-Wert angegeben. Bitte geben Sie auch einen SanU-Wert an.');exit();
		}
	}
	else{
		if(document.GUI.verfahrensgrund.value == 'Entw'){
			if(document.GUI.bodenrichtwert.value == '' && document.GUI.brwu.value == '' && document.GUI.brwb.value == ''){
				alert('Bitte geben Sie einen Bodenrichtwert, einen EU- oder EB-Wert an.');exit();
			}
			if(document.GUI.bodenrichtwert.value != '' && (document.GUI.brwu.value != '' || document.GUI.brwb.value != '')){
				alert('Sie haben einen E-Wert angegeben. In diesem Fall darf kein Bodenrichtwert angegeben werden.');exit();
			}
		}
		else{
			if(document.GUI.bodenrichtwert.value == ''){alert('Bitte geben Sie einen Bodenrichtwert an.');exit();}
		}
	}
	if(document.GUI.stichtag.value == ''){alert('Bitte geben Sie einen Stichtag an.');exit();}
	if(document.GUI.gemeinde.value == ''){alert('Bitte geben Sie eine Gemeinde an.');exit();}
	if(document.GUI.gemarkung.value == ''){alert('Bitte geben Sie eine Gemarkung an.');exit();}
	if (document.GUI.zonentyp.value == '') {
		alert('Bitte geben Sie einen Zonentyp an.');
		exit();
	}
//	if(document.GUI.zonentyp.value == 'Grünland' && document.GUI.gruenlandzahl.value == ''){alert('Bitte geben Sie eine Grünlandzahl an.');exit();}
	if (document.GUI.zonentyp.value == 'Ackerland' && document.GUI.ackerzahl.value == '') {
		alert('Bitte geben Sie eine Ackerzahl an.');
		exit();
	}
	if(document.GUI.entwicklungszustand.value == ''){alert('Bitte geben Sie einen Entwicklungszustand an.');exit();}
	if (document.GUI.entwicklungszustand.value == 'B ' && document.GUI.beitragszustand.value == '') {
		alert('Bitte geben Sie einen Beitragszustand an.');
		exit();
	}
	if(document.GUI.nutzungsart.value == ''){alert('Bitte geben Sie eine Nutzungsart an.');exit();}
	if(document.GUI.newpathwkt.value == ''){
		if(document.GUI.newpath.value == ''){
			alert('Geben Sie ein Polygon an.');exit();
		}
		else{
			document.GUI.newpathwkt.value = buildwktpolygonfromsvgpath(document.GUI.newpath.value);
		}
	}
	if(document.GUI.loc_x.value == '' && document.GUI.loc_y.value == ''){
		alert('Geben Sie die Position für die Textanzeige an.');exit();
	}
	document.GUI.go_plus.value = 'Senden';
	document.GUI.submit();
}

function add_options(select, options, firstoption){
	if(firstoption)select.options[select.length] = new Option(firstoption, '');
	for(i = 0; i < options.length; i++){
		select.options[select.length] = new Option(options[i], options[i]);
	}
}

function update_verfahren(){
	document.GUI.verfahrensgrund_zusatz.length = 0;
	document.getElementById('U').style.display = 'none';
	document.getElementById('B').style.display = 'none';
	document.getElementById('SanU').style.display = 'none';
	document.getElementById('SanS').style.display = 'none';
	document.getElementById('SanB').style.display = 'none';
	document.getElementById('EU').style.display = 'none';
	document.getElementById('EB').style.display = 'none';
	if(document.GUI.verfahrensgrund.value == 'Entw'){
		add_options(document.GUI.verfahrensgrund_zusatz, new Array('EU', 'EB'), 'kein');
		document.getElementById('U').style.display = '';
		document.getElementById('B').style.display = '';
		document.getElementById('EU').style.display = '';
		document.getElementById('EB').style.display = '';
	}
	if(document.GUI.verfahrensgrund.value == 'San'){
		add_options(document.GUI.verfahrensgrund_zusatz, new Array('SU', 'SB'), 'kein');
		document.getElementById('U').style.display = '';
		document.getElementById('B').style.display = '';
		document.getElementById('SanU').style.display = '';
		document.getElementById('SanS').style.display = '';
		document.getElementById('SanB').style.display = '';
	}
}

function update_nutzungsart() {
	document.GUI.nutzungsart.length = 0;
	if (document.GUI.entwicklungszustand.value in {'B ':'', 'R ':'','E ':''}) {
		add_options(document.GUI.nutzungsart, new Array('W', 'WS', 'WR', 'WA', 'WB', 'M', 'MD', 'MDW', 'MI', 'MK', 'MU', 'G', 'GE', 'GI', 'S', 'SE', 'SO', 'GB'), '-- Bitte wählen --');
	}
	if (document.GUI.entwicklungszustand.value == 'LF') {
		add_options(document.GUI.nutzungsart, new Array('L', 'A', 'GR', 'EGA', 'SK', 'WG', 'KUP', 'UN', 'F'), '-- Bitte wählen --');
	}
	if (document.GUI.entwicklungszustand.value == 'SF') {
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

update_require_attribute = function(attributes, layer_id, value){
	// attributes ist eine Liste von zu aktualisierenden Attributen, k die Nummer des Datensatzes und attributenamesarray ein Array aller Attribute im Formular
	var attributenames = 'gemeinde';
	var attributevalues = value;
	attribute = attributes.split(',');
	for(i = 0; i < attribute.length; i++){
		action = 'sethtml';
		ahah("index.php", "go=get_select_list&layer_id="+layer_id+"&attribute="+attribute[i]+"&attributenames="+attributenames+"&attributevalues="+attributevalues+"&type=select-one", new Array(document.getElementsByName(attribute[i])[0]), new Array(action));
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

	  
<table style="border: 1px solid; border-color: #eeeeee; border-left: none; border-right: none" border="0" cellpadding="4" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="6"><h2><?php echo $this->titel; ?></h2></td>
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
												        $FormatWerte = array('ALKISDOP', 'ALKIS', 'DOP', 'DTK10'); 
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
														<td colspan="2"><?php 
															$FormatWerte = array(
																'',
																'R ',
																'E ',
																'B ',
																'LF',
																'SF'
															);
															$FormatBez = array(
																'-- Bitte wählen --',
																'Rohbauland',
																'Bauerwartungsland',
																'Baureifes Land',
																'Fläche der Land- oder Forstwirtschaft',
																'Sonstige Fläche'
															);
															$zustand = new FormObject('entwicklungszustand', 'select', $FormatWerte, array($this->formvars['entwicklungszustand']), $FormatBez, 1, $maxlenght, $multiple, 146);
															$zustand->addJavaScript('onchange', "update_nutzungsart();");
															$zustand->OutputHTML();
															echo $zustand->html; ?>
												    </td>
												  </tr>
												  <tr> 
												    <td>
												    	Beitragszustand:
												    </td>
												    <td colspan="2"> 
												      <?php 
												        $FormatWerte = array('', '1', '2', '3');
												        $FormatBez = array('-- Bitte wählen --', 'EB-/KB-frei', 'EB-/KB-frei und abgabenpflichtig', 'EB-/KB-pflichtig und abgabenpflichtig');
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
											      	 	if(in_array($this->formvars['entwicklungszustand'], array('B ', 'R ', 'E '))){
																	$FormatWerte = array('W', 'WS', 'WR', 'WA', 'WB', 'M', 'MD', 'MDW', 'MI', 'MK', 'MU', 'G', 'GE', 'GI', 'S', 'SE', 'SO', 'GB', '');
																	$FormatBez = array('W', 'WS', 'WR', 'WA', 'WB', 'M', 'MD', 'MDW', 'MI', 'MK', 'MU', 'G', 'GE', 'GI', 'S', 'SE', 'SO', 'GB', '-- Bitte wählen --'); 
																}
																elseif($this->formvars['entwicklungszustand'] == 'LF'){
																	$FormatWerte = array('L', 'A', 'GR', 'EGA', 'SK', 'WG', 'KUP', 'UN', 'F', '');
																	$FormatBez = array('L', 'A', 'GR', 'EGA', 'SK', 'WG', 'KUP', 'UN', 'F', '-- Bitte wählen --');
																}
																elseif($this->formvars['entwicklungszustand'] == 'SF'){
																	$FormatWerte = array('PG', 'KGA', 'FGA', 'CA', 'SPO', 'SG', 'FH', 'WF', 'FP', 'PP', 'LG', 'AB', 'GF', 'SN', '');
																	$FormatBez = array('PG', 'KGA', 'FGA', 'CA', 'SPO', 'SG', 'FH', 'WF', 'FP', 'PP', 'LG', 'AB', 'GF', 'SN', '-- Bitte wählen --');
																}
																else{ 
												        	$FormatWerte = array('');
												        	$FormatBez = array('-- Bitte wählen --');
																} 
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
									<table border="0" style="border:1px steelblue solid; width: 310px;" cellpadding="3" cellspacing="0">
									  <tr> 
									    <td>
									    	Bauweise:
									    </td>
									    <td colspan="2"> 
									      <?php 
									        $FormatWerte = array('', 'o', 'g', 'a','eh', 'ed', 'dh', 'rh', 'rm', 're');
									        $FormatBez = array('-- Bitte wählen --', 'offen', 'geschlossen', 'abweichend','Einzelhaus', 'Einzel- und Doppelhaus', 'Doppelhaushälfte', 'Reihenhaus', 'Reihenmittelhaus', 'Reihenendhaus'); 
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
									        $FormatWerte = array('', 'I', 'I-II', 'I-III', 'II', 'II-III', 'III', 'III-IV', 'III-V', 'IV', 'IV-V', 'V', 'V-VI', 'VI','VI-VII','VII','VII-VIII','VIII','VIII-IX','IX','IX-X','X','X-XI','XI','XI-XII');
									        $FormatBez = array('-- Bitte wählen --', 'I', 'I-II', 'I-III', 'II', 'II-III', 'III', 'III-IV', 'III-V', 'IV', 'IV-V', 'V', 'V-VI', 'VI','VI-VII','VII','VII-VIII','VIII','VIII-IX','IX','IX-X','X','X-XI','XI','XI-XII');
									        $geschosszahl = new FormObject('geschosszahl','select',$FormatWerte,array($this->formvars['geschosszahl']),$FormatBez,1,$maxlenght,$multiple,146);
									        $geschosszahl->OutputHTML();
									        echo $geschosszahl->html;
									      ?>
									    </td>
									  </tr>
										<tr> 
									    <td>
									    	Zahl der oberirdischen Geschosse:
									    </td>
									    <td colspan="2">
									      <?php 
									        $FormatWerte = array('', 'I', 'I-II', 'I-III', 'II', 'II-III', 'III', 'III-IV', 'III-V', 'IV', 'IV-V', 'V', 'V-VI', 'VI','VI-VII','VII','VII-VIII','VIII','VIII-IX','IX','IX-X','X','X-XI','XI','XI-XII');
									        $FormatBez = array('-- Bitte wählen --', 'I', 'I-II', 'I-III', 'II', 'II-III', 'III', 'III-IV', 'III-V', 'IV', 'IV-V', 'V', 'V-VI', 'VI','VI-VII','VII','VII-VIII','VIII','VIII-IX','IX','IX-X','X','X-XI','XI','XI-XII');
									        $ogeschosszahl = new FormObject('ogeschosszahl','select',$FormatWerte,array($this->formvars['ogeschosszahl']),$FormatBez,1,$maxlenght,$multiple,146);
									        $ogeschosszahl->OutputHTML();
									        echo $ogeschosszahl->html;
									      ?>
									    </td>
									  </tr>
										<tr> 
									    <td>
									    	wertrelevante Geschossflächenzahl:
									    </td>
									    <td colspan="2"> 
									      <input name="wgeschossflaechenzahl" type="text" style="width:146px" maxlength="11" id="wgeschossflaechenzahl" value="<?php echo $this->formvars['wgeschossflaechenzahl']; ?>">
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
									       
									        if($this->formvars['verfahrensgrund'] == 'San'){
									        	$FormatWerte = array('', 'SU', 'SB');
									        	$FormatBez = array('kein', 'SU', 'SB');
									        }
									        elseif($this->formvars['verfahrensgrund'] == 'Entw'){
									        	$FormatWerte = array('', 'EU', 'EB');
									        	$FormatBez = array('kein', 'EU', 'EB');
									        }
									        else{
									        	$FormatWerte = array('');
									        	$FormatBez = array('kein');
									        } 
									        $verfahren = new FormObject('verfahrensgrund_zusatz','select',$FormatWerte,array($this->formvars['verfahrensgrund_zusatz']),$FormatBez,1,$maxlenght,$multiple,NULL);
									        $verfahren->OutputHTML();
									        echo $verfahren->html;
									      ?>  
									    </td>
									  </tr>
						  		</table>
								</td>
								<td width="50%" align="left" valign="top">&nbsp;<span class="fett">Maßstab&nbsp;1:&nbsp;</span><input type="text" id="scale" name="nScale" size="5" value="<?php echo round($this->map->scaledenom); ?>"></td>
								<td width="50%" align="right" valign="top">
									<? if($this->formvars['go'] != 'Bodenrichtwertformular_Anzeige'){ ?>
									<input type="checkbox" name="always_draw" onclick="saveDrawmode();" value="1" <?if($always_draw == 1 OR $always_draw == 'true')echo 'checked'; ?>>&nbsp;weiterzeichnen&nbsp;&nbsp;
									<input type="checkbox" onclick="toggle_vertices()" name="punktfang">&nbsp;Punktfang
									<? } ?>
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
						      <input name="bodenrichtwert" type="text" id="bodenrichtwert" value="<?php echo $this->formvars['bodenrichtwert']; ?>" size="9" maxlength="5">
						    </td>
						  </tr>
							<tr id="U" <? if($this->formvars['verfahrensgrund'] != 'San' AND $this->formvars['verfahrensgrund'] != 'Entw')echo 'style="display: none"'; ?>> 
						    <td id="EU" colspan="2" <? if($this->formvars['verfahrensgrund'] == 'San')echo 'style="display: none"'; ?>>
						    	EU [&euro;/m&sup2;]:
						    </td>
								<td id="SanU" colspan="2" <? if($this->formvars['verfahrensgrund'] == 'Entw')echo 'style="display: none"'; ?>>
						    	SanU [&euro;/m&sup2;]:
						    </td>
						    <td> 
						      <input name="brwu" type="text" id="brwu" value="<?php echo $this->formvars['brwu']; ?>" size="9" maxlength="5">
						    </td>
						  </tr>
							<tr id="SanS" <? if($this->formvars['verfahrensgrund'] != 'San')echo 'style="display: none"'; ?>> 
						    <td colspan="2">
						    	SanS [&euro;/m&sup2;]:
						    </td>
						    <td> 
						      <input name="brws" type="text" id="brws" value="<?php echo $this->formvars['brws']; ?>" size="9" maxlength="5">
						    </td>
						  </tr>
							<tr id="B" <? if($this->formvars['verfahrensgrund'] != 'San' AND $this->formvars['verfahrensgrund'] != 'Entw')echo 'style="display: none"'; ?>> 
						    <td id="EB" colspan="2" <? if($this->formvars['verfahrensgrund'] == 'San')echo 'style="display: none"'; ?>>
						    	EB [&euro;/m&sup2;]:
						    </td>
								<td id="SanB" colspan="2" <? if($this->formvars['verfahrensgrund'] == 'Entw')echo 'style="display: none"'; ?>>
						    	SanB [&euro;/m&sup2;]:
						    </td>
						    <td> 
						      <input name="brwb" type="text" id="brwb" value="<?php echo $this->formvars['brwb']; ?>" size="9" maxlength="5">
						    </td>
						  </tr>					
							
						  <tr> 
						    <td colspan="2">
						    	Stichtag:
						    </td>
						    <td>
						    	<input name="stichtag" type="text" value="<? echo $this->formvars['stichtag']; ?>" size="9" maxlength="10">
						    </td>
						  </tr>
						  <tr> 
						    <td colspan="2">
						    	Bedarfswert [&euro;/m&sup2;]:
						    </td>
						    <td> 
						      <input name="bedarfswert" type="text" id="bedarfswert" value="<?php echo $this->formvars['bedarfswert']; ?>" size="9" maxlength="5">
						    </td>
						  </tr>

							<tr> 
						    <td colspan="2">
						    	Qualitätsstichtag:
						    </td>
						    <td>
						    	<input name="qualitaetsstichtag" type="text" value="<? echo $this->formvars['qualitaetsstichtag']; ?>" size="9" maxlength="10">
						    </td>
						  </tr>
						  <tr> 
						    <td colspan="2">
						    	Bodenrichtwert Qualitätsstichtag [&euro;/m&sup2;]:
						    </td>
						    <td> 
						      <input name="bodenrichtwert_qualitaetsstichtag" type="text" id="bodenrichtwert_qualitaetsstichtag" value="<?php echo $this->formvars['bodenrichtwert_qualitaetsstichtag']; ?>" size="9" maxlength="5">
						    </td>
						  </tr>
							<tr>
								<td></td>
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
						    <td colspan="3"><?php 
									$FormatWerte = array(
										'',
										'Ackerland',
										'forstwirtschaftliche Flächen',
										'gemischte Bauflächen',
										'gewerbliche Bauflächen',
										'bebaute Flächen im Außenbereich',
										'Grünland',
										'Wohnbauflächen',
										'Sanierungsgebiet',
										'sonstige Flächen',
										'Entwicklungsbereich',
										'Soziale Stadt',
										'Sonderbauflächen'
									);
									$FormatBez = array(
										'-- Bitte wählen --',
										'Ackerland',
										'forstwirtschaftliche Flächen',
										'gemischte Bauflächen',
										'gewerbliche Bauflächen',
										'bebaute Flächen im Außenbereich',
										'Grünland',
										'Wohnbauflächen',
										'Sanierungsgebiet',
										'sonstige Flächen',
										'Entwicklungsbereich',
										'Soziale Stadt',
										'Sonderbauflächen'
									);
									$zonentyp = new FormObject('zonentyp', 'select', $FormatWerte, array($this->formvars['zonentyp']), $FormatBez, 1, $maxlenght, $multiple, 158);
									$zonentyp->OutputHTML();
									echo $zonentyp->html; ?>
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
			  		<select name="geom_from_layer">
			  			<option value="">--- Auswahl ---</option>
			  			<?
			  				for($i = 0; $i < count($this->queryable_vector_layers['ID']); $i++){
			  					echo '<option';
			  					if($this->formvars['geom_from_layer'] == $this->queryable_vector_layers['ID'][$i]){echo ' selected';}
			  					echo ' value="'.$this->queryable_vector_layers['ID'][$i].'">'.$this->queryable_vector_layers['Bezeichnung'][$i].'</option>';
			  				}
			  			?>
			  		</select> 
			  	</td>
			  </tr>
				<tr>
					<td style="padding-top: 5px">
						<input type="checkbox" name="singlegeom" value="true" <? if($this->formvars['singlegeom'])echo 'checked="true"'; ?>><? echo $strSingleGeoms; ?>
					</td>
				</tr>
			  <? if($this->formvars['go'] != 'Bodenrichtwertformular_Anzeige'){ ?>
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
			  <? } ?>
			</table>
		</td>
	</tr>
</table>
<input type="hidden" name="gid" value="<? echo $this->formvars['gid']; ?>">
<input type="hidden" name="area" value="">
<input type="hidden" name="go" value="Bodenrichtwertformular">
<input type="hidden" name="go_plus" value="">
<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="<?php echo $this->formvars['columnname']; ?>">
<INPUT TYPE="HIDDEN" NAME="fromwhere" VALUE="<? echo $this->formvars['fromwhere']; ?>">
<INPUT TYPE="HIDDEN" NAME="orderby" VALUE="<? echo $this->formvars['orderby']; ?>">

<?php
	if ($this->Meldung!='') {
		showAlert('Fehler bei der Eingabe:\n'.$this->Meldung);
}
 ?>

