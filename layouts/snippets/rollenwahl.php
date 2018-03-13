<?
  # 2007-12-30 pk
  include(LAYOUTPATH.'languages/rollenwahl_'.$this->user->rolle->language.'.php');
	include(LAYOUTPATH.'languages/map_'.$this->user->rolle->language.'.php');
	include(LAYOUTPATH.'snippets/SVGvars_defs.php');
	global $supportedLanguages;
	global $last_x;
?>

<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15">
	<defs>
		<? echo $SVGvars_defs; ?>
	</defs>
</svg>

<script type="text/javascript" src="funktionen/calendar.js"></script>
<script src="funktionen/tooltip.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">

Text_task=["<? echo $strHelp; ?>:","<? echo $strHintTask; ?>"];
Text_language=["<? echo $strHelp; ?>:","<? echo $strHintLanguage; ?>"];
Text_gui=["<? echo $strHelp; ?>:","<? echo $strHintGUI; ?>"];
Text_buttons=["<? echo $strHelp; ?>:","<? echo $strHintButtons; ?>"];
Text_color=["<? echo $strHelp; ?>:","<? echo $strHintColor; ?>"];
Text_instantreload=["<? echo $strHelp; ?>:","<? echo $strHintInstantReload; ?>"];
Text_menueautoclose=["<? echo $strHelp; ?>:","<? echo $strHintMenuAutoClose; ?>"];
Text_visuallyimpaired=["<? echo $strHelp; ?>:","<? echo $strHintVisuallyImpaired; ?>"];
Text_zoomfactor=["<? echo $strHelp; ?>:","<? echo $strHintZoomFactor; ?>"];
Text_mapsize=["<? echo $strHelp; ?>:","<? echo $strHintMapSize; ?>"];
Text_mapextent=["<? echo $strHelp; ?>:","<? echo $strHintMapExtent; ?>"];
Text_mapprojection=["<? echo $strHelp; ?>:","<? echo $strHintMapProjection; ?>"];
Text_secondmapprojection=["<? echo $strHelp; ?>:","<? echo $strHintSecondMapProjection; ?>"];
Text_coordtype=["<? echo $strHelp; ?>:","<? echo $strHintCoordType; ?>"];
Text_runningcoords=["<? echo $strHelp; ?>:","<? echo $strHintRunningCoords; ?>"];
Text_showmapfunctions=["<? echo $strHelp; ?>:","<? echo $strHintShowMapFunctions; ?>"];
Text_singlequery=["<? echo $strHelp; ?>:","<? echo $strHintSingleQuery; ?>"];
Text_querymode=["<? echo $strHelp; ?>:","<? echo $strHintQuerymode; ?>"];
Text_newdatasetorder=["<? echo $strHelp; ?>:","<? echo $strHintNewDatasetOrder; ?>"];
Text_fontsizegle=["<? echo $strHelp; ?>:","<? echo $strHintFontSizeGLE; ?>"];
Text_highlight=["<? echo $strHelp; ?>:","<? echo $strHintHighlight; ?>"];
Text_histtimestamp=["<? echo $strHelp; ?>:","<? echo $strHinthist_timestamp; ?>"];
Text_showlayeroptions=["<? echo $strHelp; ?>:","<? echo $strHintShowLayerOptions; ?>"];
Text_menue_buttons=["<? echo $strHelp; ?>:","<? echo $strHintMenueButtons; ?>"];

	function start1(){
		document.GUI.submit();
	}
	
</script>
<br>
<h2><? echo $this->titel.$strTitleRoleSelection; ?></h2>

<? if ($this->Fehlermeldung!='') {
       include(LAYOUTPATH."snippets/Fehlermeldung.php");
} ?>

<div class="rollenwahl-gruppe">
	<table class="rollenwahl-table" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="2" class="rollenwahl-gruppen-header"><span class="fett"><? echo $strGeneralOptions; ?></span></td>
		</tr>
		<tr>
			<td class="rollenwahl-gruppen-options">
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>						
						<td valign="top" class="rollenwahl-option-header">
							<? echo $strTask; ?>:
						</td>
						<td class="rollenwahl-option-data">
							<div style="display: flex;">
								<div style="float: left;">
									<? echo $this->StellenForm->html; ?>
								</div>
								<div style="float: left; margin-left: 5px;">
									<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_task, Style[0], document.getElementById('Tip1'))" onmouseout="htm()">
									<div id="Tip1" style="visibility:hidden;position:absolute;z-index:1000;"></div>
								</div>
								<div style="width: 80px; text-align: center;">
									<i id="sign_in_stelle" title="<? echo $this->strEnter; ?>" class="fa fa-sign-out fa-2x" onclick="document.GUI.submit();" style="cursor: pointer;display: none;"></i>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td class="rollenwahl-option-header">
							<? echo $strLanguage; ?>:
						</td>
						<td class="rollenwahl-option-data">
							<select name="language">
								<? if(in_array('german', $supportedLanguages)){ ?><option value="german"<? if($this->user->rolle->language == 'german') { echo ' selected'; }	?>><? echo $strGerman; ?></option><? } ?>
								<? if(in_array('low-german', $supportedLanguages)){ ?><option value="low-german"<? if($this->user->rolle->language == 'low-german') { echo ' selected'; }	?>><? echo $strPlatt; ?></option><? } ?>
								<? if(in_array('english', $supportedLanguages)){ ?><option value="english"<? if($this->user->rolle->language == 'english') { echo ' selected'; }	?>><? echo $strEnglish; ?></option><? } ?>
								<? if(in_array('polish', $supportedLanguages)){ ?><option value="polish"<? if($this->user->rolle->language == 'polish') { echo ' selected'; }	?>><? echo $strPolish; ?></option><? } ?>
								<? if(in_array('vietnamese', $supportedLanguages)){ ?><option value="vietnamese"<? if($this->user->rolle->language == 'vietnamese') { echo ' selected'; }	?>><? echo $strVietnamese; ?></option><? } ?>
							</select>
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_language, Style[0], document.getElementById('Tip2'))" onmouseout="htm()">
							<div id="Tip2" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<td class="rollenwahl-option-header">
							<? echo $strGUI; ?>:
						</td>
						<td class="rollenwahl-option-data">
							<select name="gui"><?
								# Anzeige der GUI´s, die kvwmap bereitstellt
								for($i = 0; $i < count($this->guifiles); $i++){ ?>
									<option value="<? echo basename($this->guifiles[$i]); ?>" <? if ($this->user->rolle->gui == basename($this->guifiles[$i])) { echo "selected"; } ?>><? echo basename($this->guifiles[$i]); ?></option><?
								}
								# Anzeige der GUI´s, die Admins in ihren custom Verzeichnissen haben
								for($i = 0; $i < count($this->customguifiles); $i++){ ?>
									<option value="<? echo 'custom/'.basename($this->customguifiles[$i]); ?>" <? if ($this->user->rolle->gui == 'custom/'.basename($this->customguifiles[$i])) { echo "selected"; } ?>><? echo 'custom/'.basename($this->customguifiles[$i]); ?></option><?
								} ?>
							</select>
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_gui, Style[0], document.getElementById('Tip3'))" onmouseout="htm()">
							<div id="Tip3" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<td class="rollenwahl-option-header">
							<? echo $strVisuallyImpaired; ?>:
						</td>
						<td class="rollenwahl-option-data">
							<input name="visually_impaired" type="checkbox" value="1" <? if($this->user->rolle->visually_impaired == '1') { echo 'checked="true"';} ?> >
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_visuallyimpaired, Style[0], document.getElementById('Tip24'))" onmouseout="htm()">
							<div id="Tip24" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>

<div class="rollenwahl-gruppe">
	<table class="rollenwahl-table" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="2" class="rollenwahl-gruppen-header"><span class="fett"><? echo $strButtons; ?></span></td>
		</tr>
		<tr>
			<td class="rollenwahl-gruppen-options">
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" class="rollenwahl-option-header">
							<? echo $strMapTools; ?>:
						</td>
						<td class="rollenwahl-option-data">
							<div class="button_selection">
								<div title="<? echo $strPreviousView; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? echo previous('', $strPreviousView, ''); ?></svg></div><input type="checkbox" name="back" value="1" <? if($this->user->rolle->back){echo 'checked="true"';} ?>>&nbsp;
								<div title="<? echo $strNextView; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo forward('', $strNextView, ''); ?></svg></div><input type="checkbox" name="forward" value="1" <? if($this->user->rolle->forward){echo 'checked="true"';} ?>>&nbsp;
								<div title="<? echo $strZoomIn; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo zoomin($strZoomIn); ?></svg></div><input type="checkbox" name="zoomin" value="1" <? if($this->user->rolle->zoomin){echo 'checked="true"';} ?>>&nbsp;
								<div title="<? echo $strZoomOut; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo zoomout($strZoomOut); ?></svg></div><input type="checkbox" name="zoomout" value="1" <? if($this->user->rolle->zoomout){echo 'checked="true"';} ?>>&nbsp;
								<div title="<? echo $strZoomToFullExtent; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo zoomall($strZoomToFullExtent); ?></svg></div><input type="checkbox" name="zoomall" value="1" <? if($this->user->rolle->zoomall){echo 'checked="true"';} ?>>&nbsp;
								<div title="<? echo $strPan; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo recentre($strPan); ?></svg></div><input type="checkbox" name="recentre" value="1" <? if($this->user->rolle->recentre){echo 'checked="true"';} ?>>
								<br>
								<div title="<? echo $strCoordinatesZoom; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo coords1($strCoordinatesZoom); ?></svg></div><input type="checkbox" name="jumpto" value="1" <? if($this->user->rolle->jumpto){echo 'checked="true"';} ?>>&nbsp;
								<div title="<? echo $strCoordinatesQuery; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo coords2($strCoordinatesQuery); ?></svg></div><input type="checkbox" name="coord_query" value="1" <? if($this->user->rolle->coord_query){echo 'checked="true"';} ?>>&nbsp;
								<div title="<? echo $strInfo; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo ppquery($strInfo); ?></svg></div><input type="checkbox" name="query" value="1" <? if($this->user->rolle->query){echo 'checked="true"';} ?>>&nbsp;
								<div title="<? echo $strTouchInfo; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo touchquery($strTouchInfo); ?></svg></div><input type="checkbox" name="touchquery" value="1" <? if($this->user->rolle->touchquery){echo 'checked="true"';} ?>>&nbsp;
								<div title="<? echo $strInfoWithRadius; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo pquery($strInfoWithRadius); ?></svg></div><input type="checkbox" name="queryradius" value="1" <? if($this->user->rolle->queryradius){echo 'checked="true"';} ?>>&nbsp;
								<div title="<? echo $strInfoInPolygon; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo polygonquery($strInfoInPolygon); ?></svg></div><input type="checkbox" name="polyquery" value="1" <? if($this->user->rolle->polyquery){echo 'checked="true"';} ?>>&nbsp;
								<br>
								<div title="<? echo $strRuler; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo dist($strRuler); ?></svg></div><input type="checkbox" name="measure" value="1" <? if($this->user->rolle->measure){echo 'checked="true"';} ?>>&nbsp;
								<div title="<? echo $strFreePolygon; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo freepolygon($strFreePolygon); ?></svg></div><input type="checkbox" name="freetext" value="1" <? if($this->user->rolle->freetext){echo 'checked="true"';} ?>>&nbsp;
								<div title="<? echo $strFreeText; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo freetext($strFreeText); ?></svg></div><input type="checkbox" name="freearrow" value="1" <? if($this->user->rolle->freearrow){echo 'checked="true"';} ?>>&nbsp;
								<div title="<? echo $strFreeArrow; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo freearrow($strFreeArrow); ?></svg></div><input type="checkbox" name="freepolygon" value="1" <? if($this->user->rolle->freepolygon){echo 'checked="true"';} ?>>&nbsp;
								<div title="<? echo $strGPS; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><? $last_x = 0; echo gps_follow($strGPS, 'on'); ?></svg></div><input type="checkbox" name="gps" value="1" <? if($this->user->rolle->gps){echo 'checked="true"';} ?>>&nbsp;
								<div style="width: 30px;position: relative"><img style="position: absolute; right: 0px; bottom: 0px" src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_buttons, Style[0], document.getElementById('Tip4'))" onmouseout="htm()"></div>
								<div id="Tip4" style="visibility:hidden;position:absolute;z-index:1000;"></div>
							</div>
						</td>
					</tr>
					<tr>
						<td class="rollenwahl-option-header">
							<? echo $strMapFunctions; ?>:
						</td>
						<td class="rollenwahl-option-data">
							<input name="showmapfunctions" type="checkbox" value="1" <? if($this->user->rolle->showmapfunctions == '1') { echo 'checked="true"'; } ?> >
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_showmapfunctions, Style[0], document.getElementById('Tip21'))" onmouseout="htm()">
							<div id="Tip21" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<td class="rollenwahl-option-header">
							<? echo $strShowLayerOptions; ?>:
						</td>
						<td class="rollenwahl-option-data">
							<input name="showlayeroptions" type="checkbox" value="1" <? if($this->user->rolle->showlayeroptions == '1') { echo 'checked="true"'; } ?> >
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_showlayeroptions, Style[0], document.getElementById('Tip22'))" onmouseout="htm()">
							<div id="Tip22" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<td class="rollenwahl-option-header">
							<? echo $strMenuAutoClose; ?>:
						</td>
						<td class="rollenwahl-option-data">
							<input name="menu_auto_close" type="checkbox" value="1" <? if($this->user->rolle->menu_auto_close == '1'){echo 'checked="true"';} ?> >
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_menueautoclose, Style[0], document.getElementById('Tip7'))" onmouseout="htm()">
							<div id="Tip7" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<td class="rollenwahl-option-header">
							<? echo $strMenueButtons; ?>:
						</td>
						<td class="rollenwahl-option-data">
							<input name="menue_buttons" type="checkbox" value="1" <? if($this->user->rolle->menue_buttons == '1'){echo 'checked="true"';} ?> >
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_menue_buttons, Style[0], document.getElementById('Tip23'))" onmouseout="htm()">
							<div id="Tip23" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>

<div class="rollenwahl-gruppe">
	<table class="rollenwahl-table" border="0" cellpadding="0" cellspacing="0">
			<tr>
			<td colspan="2" class="rollenwahl-gruppen-header"><span class="fett"><? echo $strMapOptions; ?></span></td>
		</tr>
		<tr>
			<td class="rollenwahl-gruppen-options">
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td class="rollenwahl-option-header">
							<? echo $strZoomFactor; ?>:
						</td>
						<td class="rollenwahl-option-data">
							<input name="nZoomFactor" type="text" value="<? echo $this->user->rolle->nZoomFactor; ?>" size="2" maxlength="3">
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_zoomfactor, Style[0], document.getElementById('Tip8'))" onmouseout="htm()">
							<div id="Tip8" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<td class="rollenwahl-option-header">
							<? echo $strMapSize; ?>:
						</td>
						<td class="rollenwahl-option-data">  
							<select name="mapsize">
								<? $selected = false; ?>
								<option value="<? echo $this->user->rolle->mapsize; ?>xauto" <? if($this->user->rolle->auto_map_resize){ echo "selected"; $selected = true;}?>><? echo $strAutoResize; ?></option>              	
								<option value="300x300" <?php if ($this->user->rolle->mapsize=="300x300"){ echo "selected"; $selected = true;} ?>>300x300</option>
								<option value="400x400" <?php if ($this->user->rolle->mapsize=="400x400"){ echo "selected"; $selected = true;} ?>>400x400</option>
								<option value="500x500" <?php if ($this->user->rolle->mapsize=="500x500"){ echo "selected"; $selected = true;} ?>>500x500</option>
								<option value="600x600" <?php if ($this->user->rolle->mapsize=="600x600"){ echo "selected"; $selected = true;} ?>>600x600</option>
								<option value="800x600" <?php if ($this->user->rolle->mapsize=="800x600"){ echo "selected"; $selected = true;} ?>>800x600</option>
								<option value="850x700" <?php if ($this->user->rolle->mapsize=="850x700"){ echo "selected"; $selected = true;} ?>>850x700</option>
								<option value="1000x800" <?php if ($this->user->rolle->mapsize=="1000x800"){ echo "selected"; $selected = true;} ?>>1000x800</option>
								<option value="1150x700" <?php if ($this->user->rolle->mapsize=="1150x700"){ echo "selected"; $selected = true;} ?>>1150x700</option>
								<option value="1200x850" <?php if ($this->user->rolle->mapsize=="1200x850"){ echo "selected"; $selected = true;} ?>>1200x850</option>
								<option value="1400x850" <?php if ($this->user->rolle->mapsize=="1400x850"){ echo "selected"; $selected = true;} ?>>1400x850</option>
								<option value="1600x850" <?php if ($this->user->rolle->mapsize=="1600x850"){ echo "selected"; $selected = true;} ?>>1600x850</option>
								<? if($selected == false){ ?>
								<option value="<? echo $this->user->rolle->mapsize; ?>" selected><? echo $this->user->rolle->mapsize; ?></option>              	
								<? } ?>
							</select>
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_mapsize, Style[0], document.getElementById('Tip9'))" onmouseout="htm()">
							<div id="Tip9" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<td class="rollenwahl-option-header">
							<? echo $strMapExtent; ?>:
						</td>
						<td class="rollenwahl-option-data"><?
							$curExtentText=round($this->user->rolle->oGeorefExt->minx, 3).' '.round($this->user->rolle->oGeorefExt->miny, 3).', '.round($this->user->rolle->oGeorefExt->maxx, 3).' '.round($this->user->rolle->oGeorefExt->maxy, 3);
						 ?><input name="newExtent" id="newExtent" type="text" size="<? echo strlen($curExtentText); ?>" value="<? echo $curExtentText; ?>">
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_mapextent, Style[0], document.getElementById('Tip10'))" onmouseout="htm()">
							<div id="Tip10" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<td class="rollenwahl-option-header">
							<? echo $strMapProjection; ?>:
						</td>
						<td class="rollenwahl-option-data">
							<select name="epsg_code" onchange="ahah('index.php','go=spatial_processing&newSRID='+this.form.epsg_code.value+'&operation=transform&resulttype=wkt',new Array(newExtent), '');">
								<?
								foreach($this->epsg_codes as $epsg_code){
									echo '<option';
									if($this->user->rolle->epsg_code == $epsg_code['srid'])echo ' selected';
									echo ' value="'.$epsg_code['srid'].'">'.$epsg_code['srid'].': '.$epsg_code['srtext'].'</option>';
								}
								?>
							</select>
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_mapprojection, Style[0], document.getElementById('Tip11'))" onmouseout="htm()">
							<div id="Tip11" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<td class="rollenwahl-option-header">
							<? echo $strSecondMapProjection; ?>:
						</td>
						<td class="rollenwahl-option-data">
							<select name="epsg_code2">
								<option value="">--<? echo $this->strChoose; ?>--</option>
								<?
								foreach($this->epsg_codes as $epsg_code){
									echo '<option';
									if($this->user->rolle->epsg_code2 == $epsg_code['srid'])echo ' selected';
									echo ' value="'.$epsg_code['srid'].'">'.$epsg_code['srid'].': '.$epsg_code['srtext'].'</option>';
								}
								?>
							</select>
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_secondmapprojection, Style[0], document.getElementById('Tip12'))" onmouseout="htm()">
							<div id="Tip12" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<td class="rollenwahl-option-header">
							<? echo $strCoordType; ?>:
						</td>
						<td  class="rollenwahl-option-data">
							<select name="coordtype">
								<option value="dec" <? if ($this->user->rolle->coordtype=="dec") { echo "selected"; } ?>><? echo $strdecimal; ?></option>
								<option value="dms" <? if ($this->user->rolle->coordtype=="dms") { echo "selected"; } ?>><? echo $strgrad1; ?></option>
								<option value="dmin" <? if ($this->user->rolle->coordtype=="dmin") { echo "selected"; } ?>><? echo $strgrad2; ?></option>				
							</select>
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_coordtype, Style[0], document.getElementById('Tip13'))" onmouseout="htm()">
							<div id="Tip13" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<td class="rollenwahl-option-header">
							<? echo $strRunningCoords; ?>:
						</td>
						<td class="rollenwahl-option-data">
							<input name="runningcoords" type="checkbox" value="1" <? if($this->user->rolle->runningcoords == '1'){echo 'checked="true"';} ?> >
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_runningcoords, Style[0], document.getElementById('Tip14'))" onmouseout="htm()">
							<div id="Tip14" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<td class="rollenwahl-option-header">
							<? echo $strInstantReload; ?>:
						</td>
						<td class="rollenwahl-option-data">
							<input name="instant_reload" type="checkbox" value="1" <? if($this->user->rolle->instant_reload == '1'){echo 'checked="true"';} ?> >
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_instantreload, Style[0], document.getElementById('Tip6'))" onmouseout="htm()">
							<div id="Tip6" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<td class="rollenwahl-option-header">
							<? echo $strSearchColor; ?>:
						</td>
						<td class="rollenwahl-option-data">
						<?
						
						for($i = 0; $i < count($this->result_colors); $i++){
							if($this->user->rolle->result_color == $this->result_colors[$i]['id']){
								$bgcolor = str_pad(dechex($this->result_colors[$i]['red']), 2, '0').str_pad(dechex($this->result_colors[$i]['green']), 2, '0').str_pad(dechex($this->result_colors[$i]['blue']), 2, '0');
							}
						}
						
						?>
							<select name="result_color" style="background-color:#<? echo $bgcolor; ?>" onchange="this.setAttribute('style', this.options[this.selectedIndex].getAttribute('style'));">
								<?
								for($i = 0; $i < count($this->result_colors); $i++){
									echo '<option ';
									if($this->user->rolle->result_color == $this->result_colors[$i]['id']){
										echo ' selected';
									}
									echo ' style="background-color: rgb('.$this->result_colors[$i]['red'].', '.$this->result_colors[$i]['green'].', '.$this->result_colors[$i]['blue'].')"';
									echo ' value="'.$this->result_colors[$i]['id'].'">';
									echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
									echo "</option>\n";
								}
								?>
							</select>
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_color, Style[0], document.getElementById('Tip5'))" onmouseout="htm()">
							<div id="Tip5" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>

<div class="rollenwahl-gruppe">
	<table class="rollenwahl-table" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="2" class="rollenwahl-gruppen-header"><span class="fett"><? echo $strDataPresentation; ?></span></td>
		</tr>
		<tr>
			<td class="rollenwahl-gruppen-options">
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td class="rollenwahl-option-header">
							<? echo $strSingleQuery; ?>:
						</td>
						<td  class="rollenwahl-option-data">
							<input name="singlequery" type="checkbox" value="1" <? if($this->user->rolle->singlequery == '1'){echo 'checked="true"';} ?> >
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_singlequery, Style[0], document.getElementById('Tip15'))" onmouseout="htm()">
							<div id="Tip15" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<td class="rollenwahl-option-header">
							<? echo $strQuerymode; ?>:
						</td>
						<td class="rollenwahl-option-data">
							<input name="querymode" type="checkbox" value="1" <? if($this->user->rolle->querymode == '1'){echo 'checked="true"';} ?> >
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_querymode, Style[0], document.getElementById('Tip16'))" onmouseout="htm()">
							<div id="Tip16" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<td class="rollenwahl-option-header">
							<? echo $strNewDatasetOrder; ?>:
						</td>
						<td class="rollenwahl-option-data">
							<select name="geom_edit_first">
								<option value="0"<? if($this->user->rolle->geom_edit_first == '0') { echo ' selected'; }	?>><? echo $strGeomSecond; ?></option>
								<option value="1"<? if($this->user->rolle->geom_edit_first == '1') { echo ' selected'; }	?>><? echo $strGeomFirst; ?></option>
							</select>
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_newdatasetorder, Style[0], document.getElementById('Tip17'))" onmouseout="htm()">
							<div id="Tip17" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<td class="rollenwahl-option-header">
							<? echo $strFontSizeGLE; ?>:
						</td>
						<td class="rollenwahl-option-data">
							<input name="fontsize_gle" type="text" value="<? echo $this->user->rolle->fontsize_gle; ?>" size="2" maxlength="2">
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_fontsizegle, Style[0], document.getElementById('Tip18'))" onmouseout="htm()">
							<div id="Tip18" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<td class="rollenwahl-option-header">
							<? echo $strHighlight; ?>:
						</td>
						<td  class="rollenwahl-option-data">
							<input name="highlighting" type="checkbox" value="1" <? if($this->user->rolle->highlighting == '1'){echo 'checked="true"';} ?> >
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_highlight, Style[0], document.getElementById('Tip19'))" onmouseout="htm()">
							<div id="Tip19" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>		
					<tr <? if(!$this->Stelle->hist_timestamp)echo 'style="display:none"'; ?> >		
						<td class="rollenwahl-option-header">
							<? echo $strhist_timestamp; ?>:&nbsp;<a href="javascript:;" onclick="new CalendarJS().init('hist_timestamp', 'timestamp');"><img title="TT.MM.JJJJ hh:mm:ss" src="<? echo GRAPHICSPATH; ?>calendarsheet.png" border="0"></a><div id="calendar_hist_timestamp" class="calendar" style="bottom:40px"></div></td>
						<td class="rollenwahl-option-data">
							<input onchange="if(this.value.length == 10)this.value = this.value + ' 06:00:00'" id="hist_timestamp" name="hist_timestamp" type="text" value="<? echo $this->user->rolle->hist_timestamp; ?>" size="16">
							<img src="<? echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text_histtimestamp, Style[0], document.getElementById('Tip20'))" onmouseout="htm()">
							<div id="Tip20" style="visibility:hidden;position:absolute;bottom:40px;z-index:1000;"></div>
						</td>			
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
<table>
  <tr>
    <td></td>
    <td><input type="button" name="starten" onclick="start1();" value="<? echo $this->strEnter; ?>" style="margin-bottom: 10px"></td>
  </tr>
</table>
  <input type="hidden" name="go" value="">