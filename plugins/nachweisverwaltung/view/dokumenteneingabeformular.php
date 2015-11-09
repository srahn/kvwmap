<script type="text/javascript" src="funktionen/calendar.js"></script>
<script type="text/javascript">
<!--

function save(){
	document.GUI.result2.value = '';
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
	document.getElementById('legenddiv').className = 'slidinglegend_slidein';
}

function slide_legend_out(evt){
	if(window.outerWidth - evt.pageX > 100){
		document.getElementById('legenddiv').className = 'slidinglegend_slideout';
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
								<td>
								<? if ($this->formvars['id']!='') { ?>
									Dokument auch ändern:<input type="checkbox" name="changeDocument" value="1" <? if ($this->formvars['changeDocument']){ ?> checked<? } ?>>
								<? }
									else { ?>
									<input type="hidden" name="changeDocument" value="1">
								<? } ?>
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
					<td colspan="2"><input type="radio" name="art" value="100"<?php if ($this->formvars['art']=='100') { ?> checked<?php } ?>>
						Fortführungsriss&nbsp;(FFR)
					</td>
				</tr>
				<tr> 
					<td colspan="2"><input type="radio" name="art" value="010"<?php if ($this->formvars['art']=='010') { ?> checked<?php } ?>>
						Koordinatenverzeichnis&nbsp;(KVZ)
					</td>
				</tr>
				<tr> 
					<td colspan="2"><input type="radio" name="art" value="001"<?php if ($this->formvars['art']=='001') { ?> checked<?php } ?>>
						Grenzniederschrift&nbsp;(GN)
					</td>
				</tr>
				<tr>
					<td colspan="2"><input type="radio" name="art" value="111"<?php if ($this->formvars['art']=='111') { ?> checked<?php } ?>>
						andere:
						<select name="andere_art" style="width: 185px" onchange="document.getElementsByName('art')[3].checked=true;">
							<option value="">-- Auswahl --</option>
							<? for($i = 0; $i < count($this->dokumentarten['id']); $i++){?>
							<option <? if($this->formvars['andere_art'] == $this->dokumentarten['id'][$i]){echo 'selected';} ?> value="<? echo $this->dokumentarten['id'][$i]; ?>"><? echo $this->dokumentarten['art'][$i]; ?></option>	
							<? } ?>
						</select>
					</td>
				</tr>
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
					<td>Datum:&nbsp;&nbsp;<a href="javascript:;" title=" (TT.MM.JJJJ) " onclick="new CalendarJS().init('datum')"><img src="<? echo GRAPHICSPATH; ?>calendarsheet.png" border="0"></a><div id="calendar" style="right: 70px"><input type="hidden" id="calendar_datum"></div></td>
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
					<td colspan="2"><table border="0" cellspacing="0" cellpadding="5">
							<tr> 
								<td>g&uuml;ltig 
									<input type="radio" name="gueltigkeit" value="1" <?php if ($this->formvars['gueltigkeit']=='1' OR $this->formvars['gueltigkeit']=='') { ?> checked<?php } ?>> 
								</td>
								<td> ung&uuml;ltig 
									<input type="radio" name="gueltigkeit" value="0" <?php if ($this->formvars['gueltigkeit']=='0') { ?> checked<?php } ?>> 
								</td>
							</tr>
						</table></td>
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
					<td colspan="2" style="border-top:1px solid #999999"><img width="290px" height="1px" src="<? echo GRAPHICSPATH; ?>leer.gif"></td>
				</tr>
				<tr>
					<td>&nbsp;</td> 
					<td><?php if ($this->formvars[NACHWEIS_PRIMARY_ATTRIBUTE]!='') { ?><a href="index.php?go=Nachweisanzeige&order=<? echo $this->formvars['order']; ?>&flur_thematisch=<? echo $this->formvars['flur_thematisch']; ?>">&lt;&lt;&nbsp;zur&uuml;ck&nbsp;zum&nbsp;Rechercheergebnis</a><?php } ?></td>
					<td>&nbsp;<span class="fett">Maßstab&nbsp;1:&nbsp;</span><input type="text" id="scale" name="nScale" size="5" value="<?php echo round($this->map->scaledenom); ?>"></td>
				<? if($this->user->rolle->runningcoords != '0'){ ?>
				<td width="100px"><span class="fett">&nbsp;<?php echo $this->strCoordinates; ?>:</span>&nbsp;</td>
				<td><input type="text" style="border:0px;background-color:transparent" name="runningcoords" value="">&nbsp;EPSG-Code:<?php echo $this->user->rolle->epsg_code; ?></td>
				<? }else{ ?>
				<td colspan="2"></td>
				<? } ?>
					<td align="right"><input type="checkbox" name="always_draw" value="1" <?if($always_draw == 1 OR $always_draw == 'true')echo 'checked'; ?>>&nbsp;weiterzeichnen&nbsp;&nbsp;</td>
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
						<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="<?php echo $this->formvars['columnname']; ?>">
						<INPUT TYPE="HIDDEN" NAME="fromwhere" VALUE="<? echo $this->formvars['fromwhere']; ?>">
						<INPUT TYPE="HIDDEN" NAME="orderby" VALUE="<? echo $this->formvars['orderby']; ?>">
						<INPUT TYPE="hidden" NAME="result2" VALUE="">
						<INPUT TYPE="hidden" NAME="check" VALUE="">
						<input type="hidden" name="order" value="<?php echo $this->formvars['order']; ?>">
						<input type="hidden" name="flur_thematisch" value="<? echo $this->formvars['flur_thematisch']; ?>">
						<INPUT TYPE="hidden" NAME="reset_layers" VALUE="">
					</td>
				</tr>
			</table>
		</td>
		<td valign="top">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td valign="top">
						<div id="legenddiv" onmouseenter="slide_legend_in(event);" onmouseleave="slide_legend_out(event);" class="slidinglegend_slideout">
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td bgcolor="<?php echo BG_DEFAULT ?>" align="left"><?php
										if ($this->user->rolle->hideLegend) {
											if (ie_check()){$display = 'none';}
											?><a id="linkLegend" href="javascript:switchlegend()"><img title="Legende zeigen" id="LegendMinMax" src="<?php  echo GRAPHICSPATH; ?>maximize_legend.png" border="0"></a><?php
										}
										else {
											?><a id="linkLegend" href="javascript:switchlegend()"><img title="Legende verstecken" id="LegendMinMax" src="<?php  echo GRAPHICSPATH; ?>minimize_legend.png" border="0"></a><?php
										}
									?></td>
								</tr>
							</table>
							<table class="table1" id="legendTable" style="display: <? echo $display; ?>" cellspacing=0 cellpadding=2 border=0>
								<tr align="center">
									<td><?php echo $strAvailableLayer; ?>:</td>
								</tr>
								<tr align="left">
									<td><!-- bgcolor=#e3e3e6 -->
									<div align="center"><?php # 2007-12-30 pk
									?><input type="submit" name="neuladen" value="<?php echo $strLoadNew; ?>" tabindex="1"></div>
									<div id="legendcontrol">
										<a href="index.php?go=reset_querys"><img src="graphics/tool_info.png" border="0" alt="Informationsabfrage." title="Informationsabfrage | Hier klicken, um alle Abfragehaken zu entfernen" width="17"></a>
										<a href="javascript:document.GUI.reset_layers.value=1;document.GUI.submit();"><img src="graphics/layer.png" border="0" alt="Themensteuerung." title="Themensteuerung | Hier klicken, um alle Themen zu deaktivieren" width="20" height="20"></a><br>
									</div>
								<div id="scrolldiv" style="height:<?php echo $legendheight; ?>; overflow:auto; scrollbar-base-color:<?php echo BG_DEFAULT ?>">
								<input type="hidden" name="nurFremdeLayer" value="<? echo $this->formvars['nurFremdeLayer']; ?>">
								<div onclick="document.GUI.legendtouched.value = 1;" id="legend">
									<? echo $this->legende; ?>
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
