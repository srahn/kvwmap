<?
  include(LAYOUTPATH.'languages/PolygonEditor_'.rolle::$language.'.php');
?>

<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<script type="text/javascript">
<!--

document.onkeydown = function(ev){
	var key;
	ev = ev || event;
	key = ev.keyCode;
	if (ev.target.className != 'quicksearch_field' && key == 13) {
		save();
	}
}

function save(){
	var dokument_art_selected = false;
	dokument_arten = document.getElementsByName('suchhauptart[]');
	[].forEach.call(dokument_arten, function (d){if(d.checked)dokument_art_selected = true;});
	if(!dokument_art_selected){
		alert('Bitte wählen Sie mindestens eine Dokumentart aus.');
		return;
	}
	art = document.getElementsByName('abfrageart');
	if(art[0].checked == true){
		if(document.GUI.suchgemarkung.value == '' && (document.GUI.suchflur.value != '' || document.GUI.suchrissnummer.value != '' || document.GUI.suchfortfuehrung.value != '')){
			alert('Bitte geben Sie die Gemarkung an.');
			return;
		}
		if(document.GUI.sVermStelle.value != '' && document.GUI.suchgemarkung.value == '' && document.GUI.suchstammnr.value == '' && document.GUI.suchrissnummer.value == '' && document.GUI.sdatum.value == '' && document.GUI.suchfortfuehrung.value == ''){
			alert('Bitte geben Sie eine Gemarkung, eine Antragsnummer, eine Rissnummer, ein Datum oder eine Fortführung an.');
			return;
		}
		if(document.GUI.suchgemarkung.value == '' && document.GUI.suchstammnr.value == '' && document.GUI.sdatum.value == '' && document.GUI.suchbemerkung.value == ''){
			alert('Bitte geben Sie Suchparameter an.');
			return;
		}
		if(document.GUI.sdatum.value != ''){
			date1 = datecheck(document.GUI.sdatum.value);
			if(!date1){
				alert('Das Datum muss im Format TT.MM.JJJJ angegeben werden.');
				return;
			}
			if(document.GUI.sdatum2.value != ''){
				date2 = datecheck(document.GUI.sdatum2.value);
				if(!date2){
					alert('Das Datum muss im Format TT.MM.JJJJ angegeben werden.');
					return;
				}
				if(date2 < date1){
					alert('Das zweite Datum muss zeitlich nach dem ersten liegen.');
					return;
				}
			}
		}
	}
	if(art[1].checked == true && document.GUI.newpathwkt.value == ''){
		if(document.GUI.newpath.value == ''){
			alert('Geben Sie ein Polygon an.');
		}
		else{
			document.GUI.newpathwkt.value = SVG.buildwktpolygonfromsvgpath(document.GUI.newpath.value);
		}
	}
	document.GUI.go_plus.value = 'Senden';
	overlay_submit(document.GUI, true);
}

function toggleBetweenSearch(toggle_button, secondfield){
	if(secondfield.style.display == ''){
		secondfield.style.display = 'none';
		secondfield.disabled = true;
		toggle_button.className = 'toggle_fa_off';
	}
	else{
		secondfield.style.display = '';
		secondfield.disabled = false;
		toggle_button.className = 'toggle_fa_on';
	}
}

function update_flur_thematisch(){
	var option = document.GUI.suchgemarkung.options[document.GUI.suchgemarkung.selectedIndex].text;
	if (option.substring(option.length - 1) == ')') {		// historische Gemarkungen nur thematisch suchen
		document.GUI.flur_thematisch.value = 1;
		document.getElementById('flur_raeumlich').style.display = 'none';
	}
	else {
		document.getElementById('flur_raeumlich').style.display = '';
	}
}

function updateGemarkungsauswahl(){
	if (document.GUI.gemschl2.value == '') {
		document.GUI.gemschl.value = '';
	}
	else {
		document.GUI.gemschl.value = document.GUI.gemschl1.value+document.GUI.gemschl2.value;
	}
	selectbyString(document.GUI.suchgemarkung, document.GUI.gemschl.value);	
	update_flur_thematisch();
}

function updateGemarkungsschluessel(value){
	document.GUI.gemschl1.value = value.substring(0, 2);
	document.GUI.gemschl2.value = value.substring(2);	
	update_flur_thematisch();
}

function clear(){
	document.GUI.gemschl2.value = '';
	document.GUI.suchgemarkung.value = '';
	document.GUI.suchflur.value = '';
	document.GUI.suchstammnr.value = '';
	document.GUI.suchstammnr2.value = '';
	document.GUI.suchrissnummer.value = '';
	document.GUI.suchrissnummer2.value = '';
	document.GUI.suchfortfuehrung.value = '';
	document.GUI.suchfortfuehrung2.value = '';
	document.GUI.sdatum.value = '';
	document.GUI.sdatum2.value = '';
	document.GUI.sVermStelle.value = '';
	document.GUI.suchbemerkung.value = '';
}

function show_dokauswahlen(){
	if(document.getElementById('dokauswahl2').style.display == 'none'){
		document.getElementById('dokauswahl1').style.borderTop="1px solid grey";
		document.getElementById('dokauswahl1').style.borderLeft="1px solid grey";
		document.getElementById('dokauswahl1').style.borderRight="1px solid grey";
		document.getElementById('dokauswahl2').style.display = '';
	}
	else{
		document.getElementById('dokauswahl1').style.border="none";
		document.getElementById('dokauswahl2').style.display = 'none';
	}
}

function save_dokauswahl(){
	if(document.GUI.dokauswahl_name.value != ''){
		document.GUI.go_plus.value = 'Dokumentauswahl_speichern';
		document.GUI.submit();
	}
	else{
		alert('Bitte geben Sie einen Namen für die Dokumentauswahl an.');
	}
}

function delete_dokauswahl(){
	if(document.GUI.dokauswahlen.value != ''){
		document.GUI.go_plus.value = 'Dokumentauswahl_löschen';
		document.GUI.submit();
	}
	else{
		alert('Es wurde keine Dokumentauswahl ausgewählt.');
	}
}

function set_all_hauptarten(checked){
	var dokument_arten = document.getElementsByName('suchhauptart[]');
	[].forEach.call(dokument_arten, function (d){
		d.checked = checked;
	});
}
  
//-->
</script>

<style>
	#attribut_form td{
		height: 27px;
	}

	#backlink{
		margin: 10px 0 -10 10px;
	}
</style>

<?php
if ($this->Meldung=='') {
  $bgcolor=BG_FORM;
}
else {
  $bgcolor=BG_FORMFAIL;
}
 ?>

<table style="border: 1px solid; border-color: #eeeeee; border-left: none; border-right: none" border="0" cellpadding="4" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr> 
    <td colspan="3" style="height: 30px">
			<? if ($this->formvars['lea_layer_id'] != '') { ?>
				<div id="backlink"><a href="index.php?go=Layer-Suche_Suchen&selected_layer_id=<? echo $this->formvars['lea_layer_id']; ?>&value_lea_id=<? echo $this->formvars['lea_id'] ?>&operator_lea_id==&opentab=3"><span class="fett">zurück zum Antrag</span></a></div>
			<? } ?>
			<div align="center"><h2><?php echo $this->titel; ?></h2></div>
		</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td valign="top" align="right" style="border-right: 1px solid #bbb"> 
      <?php
				include(LAYOUTPATH.'snippets/SVG_polygon_box_query_area.php')
			?>
			<br>
			<input type="checkbox" name="always_draw" value="1" <?if($always_draw == 1 OR $always_draw == 'true')echo 'checked'; ?>>&nbsp;weiterzeichnen&nbsp;&nbsp;
			<input type="checkbox" style="display:none" onclick="toggle_vertices()" name="punktfang" <? if($this->formvars['punktfang'] == 'on')echo 'checked="true"'; ?>>
    </td>
		<td valign="top">
			<table>
				<tr> 
					<td colspan="2">Recherche nach folgenden Dokumenten:</td>
				</tr>
				<tr>
					<td colspan="2">
						<table border="0" cellpadding="0" cellspacing="0" style="margin-left: -8px;">
					<?	$z_index = count($this->hauptdokumentarten)+1;
							foreach($this->hauptdokumentarten as $hauptdokumentart){	?>
								<tr> 
									<td>
										<div style="display: flex;">
											<div style="width: 219px;padding: 4px;display: flex;justify-content: flex-start">
												<div><input type="checkbox" name="suchhauptart[]" value="<? echo $hauptdokumentart['id']; ?>"<?php if(in_array($hauptdokumentart['id'], $this->formvars['suchhauptart'])) { ?> checked<?php }else{$art_unchecked = true;} ?>>&nbsp;</div>
												<div><? echo $hauptdokumentart['art'].'&nbsp;('.$hauptdokumentart['abkuerzung'].')'; ?></div>
											</div>
											<div style="width: 215px;">
							<?				if($this->dokumentarten[$hauptdokumentart['id']] != ''){	?>
												&nbsp;<select name="suchunterart[]" multiple="true" style="overflow: hidden;height: 24px;z-index:<? echo $z_index-=1; ?>;position: absolute;width: 210px" onmousedown="if(this.style.height=='24px'){this.style.height = (this.length * 22) + 6;preventDefault(event);}" onmouseleave="if(event.relatedTarget){this.style.height='24px';scrollToSelected(this);}">
													<option value="">alle</option>
													<? foreach($this->dokumentarten[$hauptdokumentart['id']] as $dokumentart){ ?>
														<option <? if(in_array($dokumentart['id'], $this->formvars['suchunterart'])){echo 'selected';} ?> value="<? echo $dokumentart['id']; ?>"><? echo $dokumentart['art']; ?></option>	
													<? } ?>
												</select>
												<? } ?>
											</div>
										</div>
									</td>
								</tr>
					<? } ?>
								<tr> 
									<td>
										<div style="display: flex;">
											<div style="width: 209px;padding: 4px;display: flex;justify-content: flex-start">
												<div><input type="checkbox" name="suchhauptart_alle" onclick="set_all_hauptarten(this.checked);" value="" <? if (!$art_unchecked) { ?> checked<?} ?>>&nbsp;</div>
												<div>- alle -</div>
											</div>
										</div>
									</td>
								</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<table cellpadding="2" cellspacing="0">
							<tr>
								<td id="dokauswahl1"><a href="javascript:show_dokauswahlen();">&nbsp;gespeicherte Auswahlen...</a></td>
							</tr>
							<tr id="dokauswahl2" style="display:none"> 
								<td style="border-bottom:1px solid grey;border-right:1px solid grey;border-left:1px solid grey">
									<table border="0" cellspacing="0" cellpadding="1">
										<tr align="center"> 
											<td colspan="2"  align="right">
												Name:&nbsp;<input type="text" name="dokauswahl_name" value="<? echo $this->formvars['dokauswahl_name']; ?>">
												<input type="button" style="width:80px" name="speichern" value="Speichern" onclick="save_dokauswahl();">
											</td>
										</tr>
										<tr>
											<td align="right"  colspan="2">
												<input type="button" style="width:80px" name="delete" value="Löschen" onclick="delete_dokauswahl();">
												<select name="dokauswahlen">
													<option value="">  -- Auswahl --  </option>
													<?
														for($i = 0; $i < count_or_0($this->dokauswahlset); $i++){
															echo '<option value="'.$this->dokauswahlset[$i]['id'].'" ';
															if($this->selected_dokauswahlset[0]['id'] == $this->dokauswahlset[$i]['id']){echo 'selected ';}
															echo '>'.$this->dokauswahlset[$i]['name'].'</option>';
														}
													?>
												</select>
												<input type="button" style="width:80px" name="laden" value="Laden" onclick="document.GUI.submit();">
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<? if (!$this->plugin_loaded('lenris')) { ?>
				<tr>
					<td>&nbsp;Gültigkeit:</td>
					<td>
						<select style="width: 156px" name="suchgueltigkeit">
							<option value="">--- Auswahl ---</option>
							<option value="1" <? if($this->formvars['suchgueltigkeit'] == 1)echo 'selected'; ?>>gültige Nachweise</option>
							<option value="0" <? if($this->formvars['suchgueltigkeit'] === '0')echo 'selected'; ?>>ungültige Nachweise</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>&nbsp;geprüft:</td>
					<td>
						<select style="width: 156px" name="suchgeprueft">
							<option value="">--- Auswahl ---</option>
							<option value="1">geprüfte Nachweise</option>
							<option value="0">ungeprüfte Nachweise</option>
						</select>
					</td>
				</tr>
				<? } ?>
				<tr> 
					<td colspan="2"><hr align="center" style="margin: 5px"></td>
				</tr>
				<tr> 
					<td colspan="2">Auswahlverfahren:</td>
				</tr>
				<tr> 
					<td colspan="2">
						<table border="0" cellspacing="0" cellpadding="2" id="attribut_form">
							<tr>
								<td rowspan="11" valign="top">
									<input type="radio" name="abfrageart" value="indiv_nr" <?php if ($this->formvars['abfrageart']=='indiv_nr') { ?> checked<?php } ?>>
								</td>
							</tr>
							<tr>
								<td colspan="3"><span class="fett">Auswahl über Attribute</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:clear();" title="Suchfelder leeren"><img style="vertical-align:top;" src="<? echo GRAPHICSPATH.'edit-clear.png'; ?>"></a></td>
							</tr>
							<tr>
								<td>
									Gemarkung:&nbsp;
								</td>
								<td colspan="2">
									<input name="gemschl1" type="text" value="13" style="width:23px" onkeyup="updateGemarkungsauswahl();">
									<input name="gemschl2" type="text" value="<? echo substr($this->formvars['suchgemarkung'], 2, 4); ?>" style="width:40px" onkeyup="updateGemarkungsauswahl();">
									<input name="gemschl" type="hidden" value="<? echo $this->formvars['suchgemarkung']; ?>">
									<?php 
										$this->GemkgFormObj->outputHTML();
										echo $this->GemkgFormObj->html;
									?>
								</td>
							</tr>
							<tr>
								<td>
									Flur:&nbsp;
								</td>
								<td colspan="2">
									<div style="position: relative; display: flex">
										<div>
											<input type="text" name="suchflur" value="<?php echo $this->formvars['suchflur']; ?>" size="3" maxlength="3">
										</div>
										<div id="flur_raeumlich">
											&nbsp;&nbsp;&nbsp;
											<input type="radio" name="flur_thematisch" <? if($this->formvars['flur_thematisch'] != '1')echo 'checked'; ?> value="0">räumlich
										</div>
										<div>
											&nbsp;&nbsp;&nbsp;
											<input type="radio" name="flur_thematisch" <? if($this->formvars['flur_thematisch'] == '1')echo 'checked'; ?> value="1">thematisch
											&nbsp;
										</div>
										<span style="--left: none" data-tooltip="Bei Auswahl von 'räumlich' erfolgt eine räumliche Suche über die aktuelle Flurgeometrie. Soll stattdessen über die in den Metainformationen gespeicherte Flur gesucht werden, muss 'thematisch' ausgewählt werden."></span>
									</div>
								</td>
							</tr>
							<? if(NACHWEIS_PRIMARY_ATTRIBUTE == 'rissnummer'){ ?>
							<tr>
								<td>
									Rissnummer:&nbsp;
								</td>
								<td colspan="2">
									<input type="text" name="suchrissnummer" value="<?php echo $this->formvars['suchrissnummer']; ?>" size="<?php echo RISSNUMMERMAXLENGTH; ?>" maxlength="<?php echo RISSNUMMERMAXLENGTH; ?>">
									<a href="#" class="toggle_fa_off" title="von-bis-Suche" onclick="toggleBetweenSearch(this, GUI.suchrissnummer2);"><i class="fa fa-step-backward"></i> <i class="fa fa-step-forward"></i></a>
									<input type="text" <? if($this->formvars['suchrissnummer2'] == '')echo 'style="display: none"'; ?> name="suchrissnummer2" value="<? echo $this->formvars['suchrissnummer2']; ?>" size="<? echo RISSNUMMERMAXLENGTH; ?>" maxlength="<? echo RISSNUMMERMAXLENGTH; ?>">
								</td>
							</tr>
							<? } ?>
							<tr>
								<td>
									Antragsnummer:&nbsp;
								</td>
								<td colspan="2">
									<input type="text" name="suchstammnr" value="<?php echo $this->formvars['suchstammnr']; ?>" size="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>" maxlength="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>">
									<a href="#" class="toggle_fa_off" title="von-bis-Suche" onclick="toggleBetweenSearch(this, GUI.suchstammnr2);"><i class="fa fa-step-backward"></i> <i class="fa fa-step-forward"></i></a>
									<input type="text" <? if($this->formvars['suchstammnr2'] == '')echo 'style="display: none"'; ?> name="suchstammnr2" value="<? echo $this->formvars['suchstammnr2']; ?>" size="<? echo ANTRAGSNUMMERMAXLENGTH; ?>" maxlength="<? echo ANTRAGSNUMMERMAXLENGTH; ?>">&nbsp;
									<span style="--left: none" data-tooltip="Zur nicht exakten Suche können die Platzhalter % (beliebig viele Zeichen) und _ (ein Zeichen) verwendet werden."></span>
								</td>
							</tr>
							<? if(NACHWEIS_PRIMARY_ATTRIBUTE == 'stammnr'){ ?>
							<tr>
								<td>
									Rissnummer:&nbsp;
								</td>
								<td colspan="2">
									<input type="text" name="suchrissnummer" value="<?php echo $this->formvars['suchrissnummer']; ?>" size="<?php echo RISSNUMMERMAXLENGTH; ?>" maxlength="<?php echo RISSNUMMERMAXLENGTH; ?>">
									<a href="#" class="toggle_fa_off" title="von-bis-Suche" onclick="toggleBetweenSearch(this, GUI.suchrissnummer2);"><i class="fa fa-step-backward"></i> <i class="fa fa-step-forward"></i></a>
									<input type="text" <? if($this->formvars['suchrissnummer2'] == '')echo 'style="display: none"'; ?> name="suchrissnummer2" value="<? echo $this->formvars['suchrissnummer2']; ?>" size="<? echo RISSNUMMERMAXLENGTH; ?>" maxlength="<? echo RISSNUMMERMAXLENGTH; ?>">
								</td>
							</tr>
							<? } ?>
							<tr>
								<td>
									Fortführungsjahr:&nbsp;
								</td>
								<td colspan="2" style="width: 80%">
									<input type="text" name="suchfortfuehrung" value="<?php echo $this->formvars['suchfortfuehrung']; ?>" size="4" maxlength="4">
									<a href="#" class="toggle_fa_off" title="von-bis-Suche" onclick="toggleBetweenSearch(this, GUI.suchfortfuehrung2);"><i class="fa fa-step-backward"></i> <i class="fa fa-step-forward"></i></a>
									<input type="text" <? if($this->formvars['suchfortfuehrung2'] == '')echo 'style="display: none"'; ?> name="suchfortfuehrung2" value="<?php echo $this->formvars['suchfortfuehrung2']; ?>" size="4" maxlength="4">
								</td>
							</tr>
							<tr> 
								<td style="position: relative">
									Datum:&nbsp;
									<a href="javascript:;" title=" (TT.MM.JJJJ) " onclick="new CalendarJS().init('sdatum', 'date', false)"><img src="<? echo GRAPHICSPATH; ?>calendarsheet.png" border="0"></a><div id="calendar_sdatum" class="calendar"></div>
								</td>
								<td colspan="2" style="position: relative">						
									<input id="sdatum" name="sdatum" type="text" value="<?php echo $this->formvars['sdatum']; ?>" size="10" maxlength="50">
									<a href="#" class="toggle_fa_off" title="von-bis-Suche" disabled="true" onclick="toggleBetweenSearch(this, GUI.sdatum2);toggleBetweenSearch(this, document.getElementById('caldatum2'), GUI.sdatum2);"><i class="fa fa-step-backward"></i> <i class="fa fa-step-forward"></i></a>
									<a href="javascript:;" title=" (TT.MM.JJJJ) " id="caldatum2" <? if($this->formvars['sdatum2'] == '')echo 'style="display: none"'; ?> onclick="new CalendarJS().init('sdatum2', 'date', false)"><img src="<? echo GRAPHICSPATH; ?>calendarsheet.png" border="0"></a><div id="calendar_sdatum2" class="calendar"></div>
									<input id="sdatum2" name="sdatum2" type="text" <? if($this->formvars['sdatum2'] == '')echo 'style="display: none"'; ?> onchange="" value="<?php echo $this->formvars['sdatum2']; ?>" size="10" maxlength="50">
								</td>
							</tr>
							<tr>
								<td>
									Vermessungsstelle:&nbsp;
								</td>
								<td colspan="2">
									<?php
											$this->FormObjVermStelle->outputHTML();
											echo $this->FormObjVermStelle->html;
									?>
								</td>
							</tr>
							<tr>
								<td>
									Bemerkung:&nbsp;
								</td>
								<td colspan="2">
									<input type="text" name="suchbemerkung" size="29" value="<? echo $this->formvars['suchbemerkung']; ?>">&nbsp;
									<span style="--left: none" data-tooltip="Zur nicht exakten Suche können die Platzhalter % (beliebig viele Zeichen) und _ (ein Zeichen) verwendet werden."></span>
								</td>
							</tr>
							<tr>
								<td>
									Format:&nbsp;
								</td>
								<td colspan="2"> <?
									$FormatWerte = array('','A4','A3','SF');               
									$FormatBez = array('','A4','A3','Sonderformat');
									$Blattformat = new FormObject('suchformat','select',$FormatWerte,array($this->formvars['suchformat']),$FormatBez,1,$maxlenght,$multiple,NULL);
									$Blattformat->OutputHTML();
									echo $Blattformat->html; ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr> 
					<td valign="top" colspan="3" style="height: 27px">
						<input type="radio" name="abfrageart" id="abfrageart_poly" value="poly" <?php if ($this->formvars['abfrageart']=='poly' OR $this->formvars['abfrageart']=='') { ?> checked<?php } ?>> 
						<span class="fett">Auswahl im Kartenausschnitt über Suchpolygon</span>
					</td>
				</tr>
				<? if ($this->formvars['lea_id'] == '') { ?>
				<tr> 
					<td valign="top" colspan="3" style="height: 27px">
						<input type="radio" name="abfrageart" value="antr_nr" <?php if ($this->formvars['abfrageart']=='antr_nr') { ?> checked<?php } ?>>
						<span class="fett">Vorbereitungsnummer:</span>
						<?php $this->FormObjAntr_nr->outputHTML();
							echo $this->FormObjAntr_nr->html;?>
					</td>
				</tr>
				<? } ?>
				<tr>
					<td colspan="2" style="height: 27px"><input type="checkbox" name="alle_der_messung" value="1" <? if($this->formvars['alle_der_messung'] == 1)echo 'checked'; ?>>&nbsp;alle der Messung</td>
				</tr>
				<tr> 
					<td colspan="2"><hr align="center"></td>
				</tr>
				<tr>
					<td colspan="2">
						Geometrie übernehmen von:<br>
						<select size="1" style="width: 250px" name="selected_group_id" onchange="ahah('index.php', 'go=getqueryableVectorLayers&group_id=' + this.value, [document.GUI.geom_from_layer], ['sethtml']);" <?php if(count($this->layergruppen['ID'])==0){ echo 'disabled';}?>>
							<option value="">  -- <?php echo $this->strGroup; ?> --  </option>
							<?
							for($i = 0; $i < count($this->layergruppen['ID']); $i++){         
								echo '<option';
								if($this->layergruppen['ID'][$i] == $this->formvars['selected_group_id']){
									echo ' selected';
								}
								echo ' value="'.$this->layergruppen['ID'][$i].'">'.$this->layergruppen['Bezeichnung'][$i].'</option>';
							}
						?>
						</select>
						<select name="geom_from_layer">
							<option value="">--- Auswahl ---</option>
							<?
								for($i = 0; $i < count($this->queryable_vector_layers['ID']); $i++){
									echo '<option';
									if($this->formvars['geom_from_layer'] == $this->queryable_vector_layers['ID'][$i]){echo ' selected';}
									echo ' value="'.$this->queryable_vector_layers['ID'][$i].'">'.substr($this->queryable_vector_layers['Bezeichnung'][$i], 0, 50).'</option>';
								}
							?>
						</select> 
						<input type="checkbox" name="singlegeom" value="true" <? if($this->formvars['singlegeom'])echo 'checked="true"'; ?>>Einzelgeometrien
					</td>
				</tr>
				<tr> 
					<td align="center" colspan="2" style="height: 55px"><input type="button" name="senden" value="Suchen" onclick="save();"> </td>
				</tr>
			</table>
		</td>
	</tr>
</table>
		
		<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="<?php echo $this->formvars['columnname']; ?>">
		<INPUT TYPE="HIDDEN" NAME="fromwhere" VALUE="<? echo $this->formvars['fromwhere']; ?>">
		<INPUT TYPE="HIDDEN" NAME="orderby" VALUE="<? echo $this->formvars['orderby']; ?>">
		<INPUT TYPE="HIDDEN" NAME="order" VALUE="<? echo $this->formvars['order']; ?>">
		<INPUT TYPE="HIDDEN" NAME="lea_id" VALUE="<? echo $this->formvars['lea_id']; ?>">
		<INPUT TYPE="HIDDEN" NAME="lea_layer_id" VALUE="<? echo $this->formvars['lea_layer_id']; ?>">
    <input type="hidden" name="imgxy" value="300 300"> 
    <input type="hidden" name="imgbox" value="-1 -1 -1 -1">
    <input type="hidden" name="art_markieren" value="111" >
    <input type="hidden" name="go" value="Nachweisrechercheformular" >
    <input type="hidden" name="go_plus" value="" >
		
<script type="text/javascript">
	var alle_unterarten = document.getElementsByName('suchunterart[]');
	[].forEach.call(alle_unterarten, function (unterarten){
    scrollToSelected(unterarten);
  });
</script>