<?
  include(LAYOUTPATH.'languages/PolygonEditor_'.$this->user->rolle->language.'.php');
?>

<script type="text/javascript" src="funktionen/calendar.js"></script>
<script type="text/javascript">
<!--

function toggle_vertices(){	
	document.getElementById("vertices").SVGtoggle_vertices();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
}

function save(){	
	document.GUI.result2.value = '';
	if(document.getElementsByName('unterart_'+document.GUI.hauptart.value)[0] != undefined && document.getElementsByName('unterart_'+document.GUI.hauptart.value)[0].value == ''){
		alert('Keine Dokumentart ausgewählt.');
		return;
	}
	if(document.GUI.newpathwkt.value == ''){
		if(document.GUI.newpath.value == ''){
			alert('Geben Sie ein Polygon an.');
		}
		else{
			document.GUI.newpathwkt.value = buildwktpolygonfromsvgpath(document.GUI.newpath.value);
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
	var alle_unterarten = document.getElementsByClassName('nachweise_unterart');
	[].forEach.call(alle_unterarten, function (unterarten){
    unterarten.style.display = 'none';
  });
	if(unterarten[0] != undefined){
		if(hauptart.checked)unterarten[0].style.display = '';
	}
}
  
//-->
</script>

<?php

include(LAYOUTPATH.'languages/map_'.$this->user->rolle->language.'.php');

if ($this->Meldung=='Daten zum neuen Dokument erfolgreich eingetragen!' OR $this->Meldung=='') {
  $bgcolor=BG_FORM;
}
else {
  $bgcolor=BG_FORMFAIL;
}

$legendheight = $this->map->height + 20;

?>

<table cellpadding="0" cellspacing="0">
	<tr>
		<td>   
			<table style="border: 1px solid; border-color: #eeeeee; border-left: none; border-right: none" border="0" cellpadding="0" cellspacing="8" bgcolor="<?php echo $bgcolor; ?>">
				<tr align="center"> 
					<td colspan="8"><h2><?php echo $this->titel; ?></h2></td>
				</tr>
				<tr> 
					<td colspan="8">
						<table border="0" align="right" cellpadding="5" cellspacing="0">
							<tr> 
								<td>
									<input name="Bilddatei" type="file" onchange="this.title=this.value;" value="<?php echo $this->formvars['Bilddatei']; ?>" size="22" accept="image/*.jpg"> 
								</td>
								<td colspan="3">Gemarkung/Gemeinde: 
									<?
									$this->GemkgFormObj->outputHTML();
									echo $this->GemkgFormObj->html;
									?>
								</td>
								<td>Flur:&nbsp; 
									<input name="Flur" type="text" value="<?php echo $this->formvars['Flur']; ?>" size="3" maxlength="3">
								</td>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							</tr>
						</table></td>
				</tr>
				<tr> 
					<td rowspan="20">&nbsp; </td>
					<td rowspan="20" colspan="5"> 
						<?php
							include(LAYOUTPATH.'snippets/SVG_polygon_query_area.php')
						?>
					</td>
					<td colspan="2" style="border-top:1px solid #999999"><img width="290px" height="1px" src="<? echo GRAPHICSPATH; ?>leer.gif"></td>
				</tr>
				<tr>
					<td colspan="2">
						<table cellspacing="0" cellpadding="0" style="margin: 0 0 0 -5px">
						
			<?		foreach($this->hauptdokumentarten as $hauptdokumentart){	?>
							<tr>
								<td style="vertical-align: top;padding: 0 5px 10px 0;">
									<input type="radio" name="hauptart" id="hauptart_<? echo $hauptdokumentart['id']; ?>" onchange="showUnterArten(<? echo $hauptdokumentart['id']; ?>);" value="<? echo $hauptdokumentart['id']; ?>" <? if($this->formvars['hauptart'] == $hauptdokumentart['id']) { ?> checked<?php } ?>>
								</td>
								<td style="vertical-align: top;padding: 2px 0 10px 0;">
									<? echo $hauptdokumentart['art'].'&nbsp;('.$hauptdokumentart['abkuerzung'].')';
								if($this->dokumentarten[$hauptdokumentart['id']] != ''){	?>
									:<select name="unterart_<? echo $hauptdokumentart['id']; ?>" class="nachweise_unterart" style="width: 185px;<? if($this->formvars['hauptart'] != $hauptdokumentart['id'])echo 'display:none'; ?>">
										<option value="">-- Auswahl --</option>
										<? for($i = 0; $i < count($this->dokumentarten[$hauptdokumentart['id']]); $i++){?>
										<option <? if($this->formvars['unterart'] == $this->dokumentarten[$hauptdokumentart['id']][$i]['id']){echo 'selected';} ?> value="<? echo $this->dokumentarten['id'][$i]; ?>"><? echo $this->dokumentarten[$hauptdokumentart['id']][$i]['art']; ?></option>	
										<? } ?>
									</select>
			<?				} ?>						
								</td>
							</tr>
			<?		}	?>
			
						</table>
					</td>
				<tr> 
					<td colspan="2" style="border-top:1px solid #999999"><img width="290px" height="1px" src="<? echo GRAPHICSPATH; ?>leer.gif"></td>
				</tr>
				<? if(NACHWEIS_PRIMARY_ATTRIBUTE != 'rissnummer'){ ?>
				<tr>
					<td>Antragsnr:</td>
					<td>
						<input name="stammnr" onkeyup="this.value = this.value.toUpperCase();" type="text" value="<?php echo $this->formvars['stammnr']; ?>" size="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>" maxlength="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>">
					</td>
					</tr>
					<? } ?>
				<tr>
					<td>Rissnummer:</td>
					<td>
						<input name="rissnummer" type="text" value="<?php echo $this->formvars['rissnummer']; ?>" size="<?php echo RISSNUMMERMAXLENGTH; ?>" maxlength="<? echo RISSNUMMERMAXLENGTH; ?>">
					</td>
				</tr>
					<? if(NACHWEIS_PRIMARY_ATTRIBUTE == 'rissnummer'){ ?>
				<tr>
					<td>Antragsnummer:</td>
					<td>
						<input name="stammnr" onkeyup="this.value = this.value.toUpperCase();" type="text" value="<?php echo $this->formvars['stammnr']; ?>" size="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>" maxlength="<?php echo ANTRAGSNUMMERMAXLENGTH; ?>">
					</td>
				</tr>
				<? } ?>				
				<tr> 
					<td width="80px">Blattnummer:</td>
					<td>
						<input name="Blattnr" type="text" value="<?php echo $this->formvars['Blattnr']; ?>" size="<?php echo BLATTNUMMERMAXLENGTH; ?>" maxlength="<?php echo BLATTNUMMERMAXLENGTH; ?>">
					</td>
				</tr>
				<tr> 
					<td>Fortführung:</td>
					<td>
						<input name="fortfuehrung" type="text" value="<?php echo $this->formvars['fortfuehrung']; ?>" maxlength="4" size="4">
					</td>
				</tr>
				<tr> 
					<td>Datum:&nbsp;&nbsp;<a href="javascript:;" title=" (TT.MM.JJJJ) " onclick="new CalendarJS().init('datum', 'date', false)"><img src="<? echo GRAPHICSPATH; ?>calendarsheet.png" border="0"></a><div id="calendar" style="right: 70px"><div id="calendar_datum" class="calendar"></div></td>
					<td>
						<input name="datum" id="datum" type="text" onchange="" value="<?php echo $this->formvars['datum']; ?>" size="10" maxlength="50">
					</td>
				</tr>
				<tr> 
					<td colspan="2">Bemerkungen:
						<textarea style="width:260px" name="bemerkungen"><?php echo $this->formvars['bemerkungen']; ?></textarea>
					</td>
				</tr>
				<tr> 
					<td colspan="2">Bearbeitungshinweise:
						<textarea style="width:260px" name="bemerkungen_intern"><?php echo $this->formvars['bemerkungen_intern']; ?></textarea>
					</td>
				</tr>
				<tr> 
					<td colspan="2">Vermessungsstelle:<br> 
						<?php
										$this->FormObjVermStelle->outputHTML();
										echo $this->FormObjVermStelle->html;
								?>
					</td>
				</tr>
				<tr> 
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr> 
					<td colspan="2">Blattformat: 
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
					</td>
				</tr>
				<tr> 
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr> 
					<td> 
						<input type="radio" name="gueltigkeit" value="1" <?php if ($this->formvars['gueltigkeit']=='1' OR $this->formvars['gueltigkeit']=='') { ?> checked<?php } ?>> 
						g&uuml;ltig
					</td>
					<td>
						<input type="radio" name="gueltigkeit" value="0" <?php if ($this->formvars['gueltigkeit']=='0') { ?> checked<?php } ?>> 
						ung&uuml;ltig
					</td>
				</tr>
				<tr> 
					<td>
						<input type="radio" name="geprueft" value="1" <?php if ($this->formvars['geprueft']=='1' OR $this->formvars['geprueft']=='') { ?> checked<?php } ?>> 
						geprüft
					</td>
					<td>
						<input type="radio" name="geprueft" value="0" <?php if ($this->formvars['geprueft']=='0') { ?> checked<?php } ?>> 
						ungeprüft
					</td>
				</tr>				
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">Geometrie übernehmen von:<br>
						<select name="layer_id" style="width: 260px" onchange="document.GUI.submit();">
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
					<td colspan="2" style="padding-top: 5px">
						<input type="checkbox" name="singlegeom" value="true" <? if($this->formvars['singlegeom'])echo 'checked="true"'; ?>><? echo $strSingleGeoms; ?>
					</td>
				</tr>	
				<tr> 
					<td colspan="2" style="border-top:1px solid #999999"><img width="290px" height="1px" src="<? echo GRAPHICSPATH; ?>leer.gif"></td>
				</tr>
				<tr>
					<td>&nbsp;</td> 
					<td><?php if ($this->formvars[NACHWEIS_PRIMARY_ATTRIBUTE]!='') { ?><a href="index.php?go=Nachweisanzeige&order=<? echo $this->formvars['order']; ?>&richtung=<? echo $this->formvars['richtung']; ?>&flur_thematisch=<? echo $this->formvars['flur_thematisch']; ?>&such_andere_art=<? echo $this->formvars['such_andere_art'].'#'.$this->formvars['id']; ?>">&lt;&lt;&nbsp;zur&uuml;ck&nbsp;zum&nbsp;Rechercheergebnis</a><?php } ?></td>
					<td>&nbsp;<span class="fett">Maßstab&nbsp;1:&nbsp;</span><input type="text" id="scale" name="nScale" size="5" value="<?php echo round($this->map->scaledenom); ?>"></td>
				<? if($this->user->rolle->runningcoords != '0'){ ?>
				<td width="100px"><span class="fett">&nbsp;<?php echo $this->strCoordinates; ?>:</span>&nbsp;</td>
				<td><input type="text" style="width: 200px;border:0px;background-color:transparent" name="runningcoords" value="">&nbsp;EPSG-Code:<?php echo $this->user->rolle->epsg_code; ?></td>
				<? }else{ ?>
				<td colspan="2"></td>
				<? } ?>
					<td align="right">
						<input type="checkbox" name="always_draw" value="1" <?if($always_draw == 1 OR $always_draw == 'true')echo 'checked'; ?>>&nbsp;weiterzeichnen&nbsp;&nbsp;
						<input type="checkbox" onclick="toggle_vertices()" name="punktfang" <? if($this->formvars['punktfang'] == 'on')echo 'checked="true"'; ?>>&nbsp;Punktfang
					</td>
					<td colspan="2" align="center">
						<table border="0">
							<tr> 
								<td><input type="reset" name="go_plus2" value="Zurücksetzen"></td>
								<td><input type="button" name="senden" value="Senden" onclick="save();"></td>
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
						<input type="hidden" name="flur_thematisch" value="<? echo $this->formvars['flur_thematisch']; ?>">
						<input type="hidden" name="such_andere_art" value="<? echo $this->formvars['such_andere_art']; ?>">						
						<INPUT TYPE="hidden" NAME="reset_layers" VALUE="">
						<input type="hidden" name="layer_options_open" value="">
						<input type="hidden" name="neuladen" value="">
					</td>
				</tr>
			</table>
		</td>
		<td valign="top">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td valign="top">
						<div id="legenddiv" onmouseleave="slide_legend_out(event);" class="slidinglegend_slideout">
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td bgcolor="<?php echo BG_DEFAULT ?>" align="left">
										<img id="LegendMinMax" src="<?php  echo GRAPHICSPATH; ?>maximize_legend.png" border="0">
									</td>
								</tr>
							</table>
							<table class="table1" id="legendTable" onclick="slide_legend_in(event)" style="display: <? echo $display; ?>" cellspacing=0 cellpadding=2 border=0>
								<tr align="left">
									<td>
										<div id="legend_layer" style="display: inline-block">
											<div id="legendcontrol">
												<a href="index.php?go=reset_querys">
													<div class="button_background" style="width: 26px; height: 26px">
														<div class="button tool_info" style="width: 26px; height: 26px" title="<? echo $strClearAllQuerys; ?>"></div>
													</div>
												</a>
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
