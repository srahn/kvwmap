<?
  include(LAYOUTPATH.'languages/PolygonEditor_'.rolle::$language.'.php');
	global $selectable_scales;
	$selectable_scales = array_reverse($selectable_scales);
?>

<script type="text/javascript">
<!--

document.getElementById('container_paint').style.overflow = 'hidden';

function toggle_vertices(){	
	SVG.toggle_vertices();
}

function send(){	
	document.GUI.result2.value = '';
	var hauptart_value = '';
	for(var i = 0; i < document.GUI.hauptart.length; i++){
		if(document.GUI.hauptart[i].checked){
			 hauptart_value = document.GUI.hauptart[i].value;
		}
  }
	if(hauptart_value == '' || (document.getElementsByName('unterart_'+hauptart_value)[0] != undefined && document.getElementsByName('unterart_'+hauptart_value)[0].value == '')){
		alert('Keine Dokumentart ausgewählt.');
		return;
	}
	if(document.GUI.newpathwkt.value == ''){
		if(document.GUI.newpath.value == ''){
			alert('Geben Sie ein Polygon an.');
		}
		else{
			document.GUI.newpathwkt.value = SVG.buildwktpolygonfromsvgpath(document.GUI.newpath.value);
		}
	}
	if(document.GUI.fortfuehrung.value != '' && document.GUI.fortfuehrung.value < 1850){
		conf = confirm('Achtung, das Fortführungsjahr liegt vor 1850.\nTrotzdem Speichern?');
		if(conf != true){
			return;
		}
	}
	if(document.GUI.datum.value != ''){
		if(!datecheck(document.GUI.datum.value)){
			alert('Das Datum muss im Format TT.MM.JJJJ angegeben werden.');
			return;
		}
	}
	if(document.GUI.newpathwkt.value != ''){
		ahah("index.php", "go=check_nachweis_poly&umring="+document.GUI.newpathwkt.value+"&flur="+document.GUI.Flur.value+"&gemkgschl="+document.GUI.Gemarkung.value, new Array(top.document.GUI.result2, ""), new Array("setvalue", "execute_function"));
	}
}

function check_poly(){
	if(document.GUI.result2.value == 'invalid'){
		alert('Achtung! Das Polygon ist fehlerhaft. Bitte korrigieren.');
		document.GUI.check.value = '';
	}
	else{
		if(document.GUI.result2.value == 'f'){
			document.GUI.check.value = '';
			conf = confirm('Achtung! Das Polygon liegt nicht in der angegebenen Flur.\nTrotzdem Speichern?');
			if(conf == true){
				document.GUI.go_plus.value = 'Senden';
				document.GUI.submit();
			}
			else{
				return;
			}
		}
		if(document.GUI.result2.value == 't'){
			document.GUI.go_plus.value = 'Senden';
			document.GUI.submit();
		}
	}
}

function slide_legend_in(evt){
	if(document.getElementById('legenddiv').className == 'slidinglegend_slideout'){
		evt.stopPropagation();
		document.getElementById('legenddiv').className = 'slidinglegend_slidein';
	}
}

function slide_legend_out(evt){
	if(window.outerWidth - evt.pageX > 100){
		document.getElementById('legenddiv').className = 'slidinglegend_slideout';
	}
}

function showUnterArten(id){
	var unterarten = document.getElementsByName('unterart_'+id);
	var hauptart = document.getElementById('hauptart_'+id);
	var hauptart_name = document.getElementById('hauptart_name_'+id).innerHTML;
	var alle_unterarten = document.getElementsByClassName('dokein-dokart-select');
	[].forEach.call(alle_unterarten, function (unterarten){
    unterarten.style.display = 'none';
  });
	if(unterarten[0] != undefined){
		if(hauptart.checked)unterarten[0].style.display = '';
		if(unterarten[0].value == ''){
			for(i = 0; i < unterarten[0].options.length; i++){
				if(unterarten[0].options[i].text == hauptart_name)unterarten[0].options[i].selected = true;
			}
		}
	}
}
  
//-->
</script>

<?php

include(LAYOUTPATH.'languages/map_'.rolle::$language.'.php');

if ($this->Meldung=='Daten zum neuen Dokument erfolgreich eingetragen!' OR $this->Meldung=='') {
  $bgcolor=BG_FORM;
}
else {
  $bgcolor=BG_FORMFAIL;
}

$legendheight = $this->map->height + 20;

?>

<style>
	#dokein-head-attributes,  
	.dokein-dokmeta1-vermstelle-obj select, 
	.dokein-dokmeta2-child-tarea textarea, 
	.dokein-dokmeta3-format-obj select, 
	.dokein-geom-uebernehmen-obj select, 
	#dokein-doksend {
		width:calc(100% - 60px);
	}
	#dokein-side-attributes{
		width: 330px;
	}
	.dokein-dokart-select{
		margin-left: 20px;
	}
	.dokein-title{
		position: relative;
		margin-bottom: 3px;
	}
	#dokein-head-attributes {
		height: 64px;
		margin-bottom: 10px;
		display: flex;
		flex-wrap: wrap;
		justify-content: flex-end;
		align-items: flex-end;
	}
	#dokein-title {
		width:100%;
		text-align: center;
	}
	#dokein-allemessung {
		margin-left: 10px;
		flex-grow: 10;	
	}
	#dokein-allemessung ul{
		margin: 0;
		color: #236dbf;
		padding: 0px;
		padding-left: 5px;
		list-style: square inside none;
	}	
	#dokein-image, #dokein-gemkg {
		margin-right: 20px;
	}
	#dokein-image input {
		width: 400px;
	}
	#dokein-main {
		width:100%;
		display: flex;
		flex-flow: row nowrap;
	}
	#dokein-map-foot {
		margin-left: 5px;
	}
	#dokein-dokart  {
		margin-left: 2px;
		padding-top: 5px;
	}
	.dokein-dokart-item {
		width:100%;
		display: flex;
		flex-flow: row wrap;
		justify-content: flex-start;
	}
	.dokein-dokart-sub {
		width: 100%;
		margin: 3px 0px 5px 0px;
	}
	#dokein-dokmeta, #dokein-dokmeta2, #dokein-dokmeta3, #dokein-geom-uebernehmen {
		margin: 5px 0px 0px 8px;
	}
	.dokein-dokmeta1-child {
		width:100%;
		display: flex;
		flex-flow: row nowrap;
		margin-bottom: 5px;
	}
	.dokein-dokmeta1-child .dokein-title {
		width: 110px;
	}
	.dokein-dokmeta1-child-input {
		margin-right: 60px;
	}
	#dokein-dokmeta2, #dokein-dokmeta3, .dokein-geom-uebernehmen-child {
		margin-top: 10px;
	}
	#dokein-geom-uebernehmen {
		margin-top: 20px;
	}
	.dokein-dokmeta3-format-obj {
		margin-bottom: 10px;
	}
	.dokein-dokmeta3-geprueft {
		margin: 5px 0px 10px 0px;
	}
	#dokein-doksend {
		margin: 25px 0px 15px 6px;
		text-align: center;
	}
	#dokein-foot {
		margin: 10px 0px 10px 0px;
		display: flex;
		flex-wrap: wrap;
	}
	#dokein-scale-koord {
		width: 100%;
		display: flex;
		flex-wrap: nowrap;
	}
	.dokein-scale-koord-child:first-child {
		margin-right: 50px;
	}
	#dokein-draw {
		flex-grow: 10;
		display: flex;
		flex-wrap: nowrap;
		justify-content: flex-end;
	}
	.dokein-draw-child {
		margin-left: 10px;
	}
</style>


<table cellpadding="0" cellspacing="0">
	<tr>
		<td>   
			<div style="background-color: <?php echo $bgcolor; ?>">					
								
			<div id="dokein-head-attributes">
				<div id="dokein-title">
					<h2><?php echo $this->titel; ?></h2>
				</div>
				<div id="dokein-allemessung">
					<? if ($this->formvars[NACHWEIS_PRIMARY_ATTRIBUTE]!=''){ ?>
					<ul>
						<li><a href="index.php?go=Nachweisrechercheformular_Senden&abfrageart=indiv_nr&suchhauptart=&suchgemarkung=<? echo $this->formvars['Gemarkung']; ?>&suchflur=<? echo $this->formvars['Flur']; ?>&flur_thematisch=1&such<? echo NACHWEIS_PRIMARY_ATTRIBUTE.'='.$this->formvars[NACHWEIS_PRIMARY_ATTRIBUTE]; if(NACHWEIS_SECONDARY_ATTRIBUTE != '')echo '&such'.NACHWEIS_SECONDARY_ATTRIBUTE.'='.$this->formvars[NACHWEIS_SECONDARY_ATTRIBUTE]; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>">alle der Messung anzeigen</a></li>
						<li><a href="javascript:void(0);" onclick="overlay_link('go=Nachweisanzeige&suchgueltigkeit=<? echo $this->formvars['suchgueltigkeit']; ?>&suchgeprueft=<? echo $this->formvars['suchgeprueft']; ?>&order=<? echo $this->formvars['order']; ?>&richtung=<? echo $this->formvars['richtung'].'&selected_nachweis='.$this->formvars['id'].'&auswahl=1&time=' . time() . '#'.$this->formvars['id']; ?>', true)">zur&uuml;ck&nbsp;zum&nbsp;Rechercheergebnis</a></li>
					</ul>
					<? } ?>
				</div>
				<div id="dokein-image">
					<input name="Bilddatei" type="file" onchange="this.title=this.value;" value="<?php echo $this->formvars['Bilddatei']; ?>" size="22" accept="image/*.jpg">
				</div>
				<div id="dokein-gemkg">
					<div class="dokein-title">Gemarkung/Gemeinde</div>
					<div class="dokein-obj">
						<?
						$this->GemkgFormObj->outputHTML();
						echo $this->GemkgFormObj->html;
						?>
					</div>
				</div>
				<div id="dokein-flur">
					<div class="dokein-title">Flur</div>
					<div class="dokein-input">
						<input name="Flur" type="text" value="<?php echo $this->formvars['Flur']; ?>" size="3" maxlength="3">
					</div>
				</div>
			</div>

			<div id="dokein-main">
			<div id="dokein-map-foot">
			<div id="dokein-map">
				<?	include(LAYOUTPATH.'snippets/SVG_polygon_query_area.php');	?>
			</div>

			<div id="dokein-foot">
				<div id="dokein-scale-koord">
					<div class="dokein-scale-koord-child">
						<div style="width:150px;" onmouseover="document.getElementById('scales').style.display='inline-block';" onmouseout="document.getElementById('scales').style.display='none';">
							<div valign="top" style="height:0px; position:relative;">
								<div id="scales" style="display:none; position:absolute; left:66px; bottom:-1px; width: 78px; vertical-align:top; overflow:hidden; border:solid grey 1px;">
									<select size="<? echo count($selectable_scales); ?>" style="padding:4px; margin:-2px -17px -4px -4px;" onclick="setScale(this);">
										<? 
											foreach($selectable_scales as $scale){
												echo '<option onmouseover="this.selected = true;" value="'.$scale.'">1:&nbsp;&nbsp;'.$scale.'</option>';
											}
										?>
									</select>
								</div>
							</div>
							<span class="fett">Maßstab&nbsp;1:&nbsp;</span><input type="text" id="scale" name="nScale" onkeyup="if (event.keyCode == 13) { setScale(this); }" autocomplete="off" size="5" value="<?php echo round($this->map->scaledenom); ?>">
						</div>
					</div>
					<div class="dokein-scale-koord-child">
						<? if($this->user->rolle->runningcoords != '0'){ ?>
						<span class="fett">&nbsp;<?php echo $this->strCoordinates; ?>:</span>&nbsp;
						<? } ?>			
					</div>
					<div class="dokein-scale-koord-child">
						<? if($this->user->rolle->runningcoords != '0'){ ?>
						<input type="text" style="width: 150px;border:0px;background-color:transparent" name="runningcoords" value="">&nbsp;EPSG-Code:<?php echo $this->user->rolle->epsg_code; ?>
						<? } ?>		
					</div>
					<div id="dokein-draw">
						<div class="dokein-draw-child">
							<input type="checkbox" name="always_draw" onclick="saveDrawmode();" value="1" <?if($always_draw == 1 OR $always_draw == 'true')echo 'checked'; ?>>&nbsp;weiterzeichnen
						</div>
						<div class="dokein-draw-child">
							<input type="checkbox" onclick="toggle_vertices()" name="punktfang" <? if($this->formvars['punktfang'] == 'on')echo 'checked="true"'; ?>>&nbsp;Punktfang
						</div>
					</div>
				</div>
			</div>
			</div>

			<div id="dokein-side-attributes">
				<div id="dokein-dokart">
					<? foreach($this->hauptdokumentarten as $hauptdokumentart){	?>
						<div class="dokein-dokart-item">
							<div class="dokein-dokart-radio">
								<input type="radio" name="hauptart" id="hauptart_<? echo $hauptdokumentart['id']; ?>" onchange="showUnterArten(<? echo $hauptdokumentart['id']; ?>);" value="<? echo $hauptdokumentart['id']; ?>" <? if($this->formvars['hauptart'] == $hauptdokumentart['id']) { ?> checked<?php } ?>>
							</div>
							<div class="dokein-title" onclick="document.getElementById('hauptart_<? echo $hauptdokumentart['id']; ?>').checked=true;showUnterArten(<? echo $hauptdokumentart['id']; ?>);">
								<span id="hauptart_name_<? echo $hauptdokumentart['id']; ?>"><? echo $hauptdokumentart['art']; ?></span><? echo '&nbsp;('.$hauptdokumentart['abkuerzung'].')'; ?>
							</div>
							<? if($this->dokumentarten[$hauptdokumentart['id']] != ''){	?>
							<div class="dokein-dokart-sub">
								<select name="unterart_<? echo $hauptdokumentart['id']; ?>" class="dokein-dokart-select" style="<? if($this->formvars['hauptart'] != $hauptdokumentart['id'])echo 'display:none'; ?>">
									<option value="">-- Auswahl --</option>
									<? foreach($this->dokumentarten[$hauptdokumentart['id']] as $dokumentart){ ?>
									<option <? if($this->formvars['unterart'] == $dokumentart['id']){echo 'selected';} ?> value="<? echo $dokumentart['id']; ?>"><? echo $dokumentart['art']; ?></option>	
									<? } ?>
								</select>
							</div>
							<? } ?>
						</div>
					<? } ?>
				</div>
				<div id="dokein-dokmeta">
					<? if(NACHWEIS_PRIMARY_ATTRIBUTE != 'rissnummer'){ ?>
					<div class="dokein-dokmeta1-child">
						<div class="dokein-title">
							Antragsnummer
						</div>
						<div class="dokein-dokmeta1-child-input">
							<input name="stammnr" onkeyup="this.value = this.value.toUpperCase();" type="text" value="<?php echo $this->formvars['stammnr']; ?>" size="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>" maxlength="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>">
						</div>
					</div>
					<? } ?>
					<div class="dokein-dokmeta1-child">
						<div class="dokein-title">
							Rissnummer
						</div>
						<div class="dokein-dokmeta1-child-input">
							<input name="rissnummer" type="text" value="<?php echo $this->formvars['rissnummer']; ?>" size="<?php echo RISSNUMMERMAXLENGTH; ?>" maxlength="<? echo RISSNUMMERMAXLENGTH; ?>">			
						</div>
					</div>
					<? if(NACHWEIS_PRIMARY_ATTRIBUTE == 'rissnummer'){ ?>
					<div class="dokein-dokmeta1-child">
						<div class="dokein-title">
							Antragsnummer
						</div>
						<div class="dokein-dokmeta1-child-input">
							<input name="stammnr" onkeyup="this.value = this.value.toUpperCase();" type="text" value="<?php echo $this->formvars['stammnr']; ?>" size="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>" maxlength="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>">
						</div>		
					</div>
					<? } ?>
					<div class="dokein-dokmeta1-child">
						<div class="dokein-title">
							Blattnummer
						</div>
						<div class="dokein-dokmeta1-child-input">
							<input name="Blattnr" type="text" value="<?php echo $this->formvars['Blattnr']; ?>" size="<?php echo BLATTNUMMERMAXLENGTH; ?>" maxlength="<?php echo BLATTNUMMERMAXLENGTH; ?>">			
						</div>		
					</div>
					<div class="dokein-dokmeta1-child">
						<div class="dokein-title">
							Fortführung
						</div>
						<div class="dokein-dokmeta1-child-input">
							<input name="fortfuehrung" type="text" value="<?php echo $this->formvars['fortfuehrung']; ?>" maxlength="4" size="4">			
						</div>		
					</div>
					<div class="dokein-dokmeta1-child">
						<div class="dokein-title">
							Datum:&nbsp;&nbsp;<a href="javascript:;" title=" (TT.MM.JJJJ) " onclick="new CalendarJS().init('datum', 'date', false)"><img src="<? echo GRAPHICSPATH; ?>calendarsheet.png" border="0"></a><div id="calendar"></div><div id="calendar_datum" class="calendar"></div>
						</div>
						<div class="dokein-dokmeta1-child-input">
							<input name="datum" id="datum" type="text" onchange="" value="<?php echo $this->formvars['datum']; ?>" size="10" maxlength="50">			
						</div>		
					</div>	
					<div class="dokein-dokmeta1-vermstelle">
						<div class="dokein-title">
							Vermessungsstelle
						</div>
						<div class="dokein-dokmeta1-vermstelle-obj">
							<?php
							$this->FormObjVermStelle->outputHTML();
							echo $this->FormObjVermStelle->html;
							?>
						</div>		
					</div>
				</div>
				<div id="dokein-dokmeta2">
					<div class="dokein-dokmeta2-child">
						<div class="dokein-title">
							Bemerkungen
						</div>
						<div class="dokein-dokmeta2-child-tarea">
							<textarea name="bemerkungen"><?php echo $this->formvars['bemerkungen']; ?></textarea>
						</div>
					</div>
					<div class="dokein-dokmeta2-child">
						<div class="dokein-title">
							Bearbeitungshinweise
						</div>
						<div class="dokein-dokmeta2-child-tarea">
							<textarea name="bemerkungen_intern"><?php echo $this->formvars['bemerkungen_intern']; ?></textarea>
						</div>		
					</div>
				</div>
				<div id="dokein-dokmeta3">
					<div class="dokein-dokmeta3-child">
						<div class="dokein-title">
							Blattformat
						</div>
						<div class="dokein-dokmeta3-format-obj">
							<?php 
							$i=0;
							while ($i<3) {
								$BlattformatZahl[]=++$i;
							}
							$FormatWerte = array('A4','A3','SF');               
							$FormatBez = array('A4','A3','Sonderformat');
							$Blattformat = new FormObject('Blattformat','select',$FormatWerte,array($this->formvars['Blattformat']),$FormatBez,1,$maxlenght,$multiple,NULL);
							$Blattformat->OutputHTML();
							echo $Blattformat->html;
							?>
						</div>
					</div>
					<div class="dokein-dokmeta3-child">
						<div class="dokein-dokmeta3-gueltig" style="float: left;">
							<input type="radio" name="gueltigkeit" value="1" <?php if ($this->formvars['gueltigkeit']=='1' OR $this->formvars['gueltigkeit']=='') { ?> checked<?php } ?>>gültig
						</div>
						<div class="dokein-dokmeta3-gueltig">
							<input type="radio" name="gueltigkeit" value="0" <?php if ($this->formvars['gueltigkeit']=='0') { ?> checked<?php } ?>>ungültig
						</div>
						<div class="dokein-dokmeta3-geprueft" style="float: left;">
							<input type="radio" name="geprueft" value="1" <?php if ($this->formvars['geprueft']=='1') { ?> checked<?php } ?>>geprüft
						</div>
						<div class="dokein-dokmeta3-geprueft">
							<input type="radio" name="geprueft" value="0" <?php if ($this->formvars['geprueft']=='0' OR $this->formvars['geprueft']=='') { ?> checked<?php } ?>>ungeprüft
						</div>
					</div>
				</div>
				<div id="dokein-geom-uebernehmen">
					<div class="dokein-geom-uebernehmen-child">
						<div class="dokein-title">
							Geometrie übernehmen von
						</div>
						<div class="dokein-geom-uebernehmen-obj">
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
						</div>
					</div>
					<div class="dokein-geom-uebernehmen-child">
							<input type="checkbox" name="singlegeom" value="true" <? if($this->formvars['singlegeom'])echo 'checked="true"'; ?>><? echo $strSingleGeoms; ?>
					</div>
				</div>
				<div id="dokein-doksend">
					<input type="button" name="senden" value="Senden" onclick="send();">
				</div>
			</div>
			</div> <!-- Ende div dokein-main -->

			</div> 
		</td>
		<td valign="top">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td valign="top">
						<div id="legenddiv" onclick="slide_legend_in(event)" onmouseleave="slide_legend_out(event);" class="slidinglegend_slideout">
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td bgcolor="<?php echo BG_DEFAULT ?>" align="left">
										<img id="LegendMinMax" src="<?php  echo GRAPHICSPATH; ?>maximize_legend.png" border="0">
									</td>
								</tr>
							</table>
							<table class="table1" id="legendTable" style="display: <? echo $display; ?>" cellspacing=0 cellpadding=2 border=0>
								<tr align="left">
									<td>
										<div id="legend_layer" style="display: inline-block">
											<div id="legendcontrol">
											<? if ($this->user->rolle->singlequery < 2) { ?>
												<a href="index.php?go=reset_querys">
													<div class="button_background" style="width: 26px; height: 26px">
														<div class="button tool_info" style="width: 26px; height: 26px" title="<? echo $strClearAllQuerys; ?>"></div>
													</div>
												</a>
											<? } ?>
												<a href="index.php?go=reset_layers" style="padding: 0 0 0 6">
													<div class="button_background" style="width: 26px; height: 26px">
														<div class="button layer" style="width: 26px; height: 26px" title="<? echo $strDeactivateAllLayer; ?>"></div>
													</div>
												</a>
												<input type="button" name="neuladen_button" onclick="neuLaden();" value="<?php echo $strLoadNew; ?>" tabindex="1" style="height: 27px; vertical-align: top; margin-left: 30px">
											</div>
											<div id="scrolldiv" style="height:<?php echo $legendheight; ?>; overflow:auto; scrollbar-base-color:<?php echo BG_DEFAULT ?>">
												<input type="hidden" name="nurFremdeLayer" value="<? echo $this->formvars['nurFremdeLayer']; ?>">
												<div onclick="document.GUI.legendtouched.value = 1;" id="legend">
													<? echo $this->legende; ?>
												</div>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<input type="hidden" name="id" value="<?php echo $this->formvars['id']; ?>">
<input type="hidden" name="go" value="Nachweisformular">
<input type="hidden" name="go_plus" value="">
<input type="hidden" name="area" value="">
<INPUT TYPE="HIDDEN" NAME="oid" VALUE="<?php echo $this->formvars['oid']; ?>">
<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="<?php echo $this->formvars['columnname']; ?>">
<INPUT TYPE="HIDDEN" NAME="fromwhere" VALUE="<? echo $this->formvars['fromwhere']; ?>">
<INPUT TYPE="HIDDEN" NAME="orderby" VALUE="<? echo $this->formvars['orderby']; ?>">						
<INPUT TYPE="hidden" NAME="result2" VALUE="">
<INPUT TYPE="hidden" NAME="check" VALUE="">
<input type="hidden" name="order" value="<?php echo $this->formvars['order']; ?>">
<INPUT TYPE="HIDDEN" NAME="richtung" VALUE="<? echo $this->formvars['richtung']; ?>">
<input type="hidden" name="suchgueltigkeit" value="<? echo $this->formvars['suchgueltigkeit']; ?>">
<input type="hidden" name="suchgeprueft" value="<? echo $this->formvars['suchgeprueft']; ?>">
<INPUT TYPE="hidden" NAME="reset_layers" VALUE="">
<input type="hidden" name="layer_options_open" value="">
<input type="hidden" name="neuladen" value="">
