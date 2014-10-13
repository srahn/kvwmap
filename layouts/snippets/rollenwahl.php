<?php
  # 2007-12-30 pk
  include(LAYOUTPATH.'languages/rollenwahl_'.$this->user->rolle->language.'.php');
	global $supportedLanguages;
?>
<script type="text/javascript" src="funktionen/calendar.js"></script>
<br>
<h2><?php echo $this->titel.$strTitleRoleSelection; ?></h2><br>

<?php if ($this->Fehlermeldung!='') {
       include(LAYOUTPATH."snippets/Fehlermeldung.php");
} ?>
<div style="margin:10px;margin-bottom:20px;border: 1px solid #cccccc">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="2" style="background-color: #c7d9e6; padding: 2px;padding-left: 8px"><span class="fett"><? echo $strGeneralOptions; ?></span></td>
		</tr>
		<tr>
			<td align="left" valign="top" style="width: 230px;padding: 8px"><?php echo $strTask; ?>:&nbsp;</td>
			<td style="padding: 8px"><?php echo $this->StellenForm->html; ?></td>
		</tr>
		<tr align="center">
			<td align="left" style="width: 230px;padding: 8px; padding-top: 0px"><?php echo $strLanguage; ?>:&nbsp;</td>
			<td align="left" style="padding: 8px; padding-top: 0px">
				<select name="language">
					<? if(in_array('german', $supportedLanguages)){ ?><option value="german"<?php if($this->user->rolle->language == 'german') { echo ' selected'; }	?>><?php echo $strGerman; ?></option><? } ?>
					<? if(in_array('low-german', $supportedLanguages)){ ?><option value="low-german"<?php if($this->user->rolle->language == 'low-german') { echo ' selected'; }	?>><?php echo $strPlatt; ?></option><? } ?>
					<? if(in_array('english', $supportedLanguages)){ ?><option value="english"<?php if($this->user->rolle->language == 'english') { echo ' selected'; }	?>><?php echo $strEnglish; ?></option><? } ?>
					<? if(in_array('polish', $supportedLanguages)){ ?><option value="polish"<?php if($this->user->rolle->language == 'polish') { echo ' selected'; }	?>><?php echo $strPolish; ?></option><? } ?>
					<? if(in_array('vietnamese', $supportedLanguages)){ ?><option value="vietnamese"<?php if($this->user->rolle->language == 'vietnamese') { echo ' selected'; }	?>><?php echo $strVietnamese; ?></option><? } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="left" style="padding: 8px; padding-top: 0px"><?php echo $strGUI; ?>:&nbsp;</td>
			<td style="padding: 8px; padding-top: 0px">
				<select name="gui"><?
					# Anzeige der GUI´s, die kvwmap bereitstellt
					for($i = 0; $i < count($this->guifiles); $i++){ ?>
						<option value="<? echo basename($this->guifiles[$i]); ?>" <?php if ($this->user->rolle->gui == basename($this->guifiles[$i])) { echo "selected"; } ?>><?php echo basename($this->guifiles[$i]); ?></option><?php
					}
					# Anzeige der GUI´s, die Admins in ihren custom Verzeichnissen haben
					for($i = 0; $i < count($this->customguifiles); $i++){ ?>
						<option value="<? echo 'custom/'.basename($this->customguifiles[$i]); ?>" <?php if ($this->user->rolle->gui == 'custom/'.basename($this->customguifiles[$i])) { echo "selected"; } ?>><? echo 'custom/'.basename($this->customguifiles[$i]); ?></option><?php
					} ?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="left" valign="top" style="padding: 8px"><? echo $strButtons; ?>:</td>
			<td style="padding: 8px">
				<img src="<? echo GRAPHICSPATH.'back.png'; ?>"><input type="checkbox" name="back" value="1" <?php if($this->user->rolle->back){echo 'checked="true"';} ?>>&nbsp;
				<img src="<? echo GRAPHICSPATH.'frwd.png'; ?>"><input type="checkbox" name="forward" value="1" <?php if($this->user->rolle->forward){echo 'checked="true"';} ?>>&nbsp;
				<img src="<? echo GRAPHICSPATH.'zoomin.png'; ?>"><input type="checkbox" name="zoomin" value="1" <?php if($this->user->rolle->zoomin){echo 'checked="true"';} ?>>&nbsp;
				<img src="<? echo GRAPHICSPATH.'zoomout.png'; ?>"><input type="checkbox" name="zoomout" value="1" <?php if($this->user->rolle->zoomout){echo 'checked="true"';} ?>>&nbsp;
				<img src="<? echo GRAPHICSPATH.'zoomall.png'; ?>"><input type="checkbox" name="zoomall" value="1" <?php if($this->user->rolle->zoomall){echo 'checked="true"';} ?>>&nbsp;
				<img src="<? echo GRAPHICSPATH.'pan.png'; ?>"><input type="checkbox" name="recentre" value="1" <?php if($this->user->rolle->recentre){echo 'checked="true"';} ?>>
				<img src="<? echo GRAPHICSPATH.'freetext.png'; ?>"><input type="checkbox" name="freetext" value="1" <?php if($this->user->rolle->freetext){echo 'checked="true"';} ?>>
				<img src="<? echo GRAPHICSPATH.'arrow.png'; ?>"><input type="checkbox" name="freearrow" value="1" <?php if($this->user->rolle->freearrow){echo 'checked="true"';} ?>>  		
				<br>
				<img src="<? echo GRAPHICSPATH.'jumpto.png'; ?>"><input type="checkbox" name="jumpto" value="1" <?php if($this->user->rolle->jumpto){echo 'checked="true"';} ?>>&nbsp;
				<img src="<? echo GRAPHICSPATH.'query.png'; ?>"><input type="checkbox" name="query" value="1" <?php if($this->user->rolle->query){echo 'checked="true"';} ?>>&nbsp;
				<img src="<? echo GRAPHICSPATH.'touchquery.png'; ?>"><input type="checkbox" name="touchquery" value="1" <?php if($this->user->rolle->touchquery){echo 'checked="true"';} ?>>&nbsp;
				<img src="<? echo GRAPHICSPATH.'query-radius.png'; ?>"><input type="checkbox" name="queryradius" value="1" <?php if($this->user->rolle->queryradius){echo 'checked="true"';} ?>>&nbsp;
				<img src="<? echo GRAPHICSPATH.'polyquery.jpg'; ?>"><input type="checkbox" name="polyquery" value="1" <?php if($this->user->rolle->polyquery){echo 'checked="true"';} ?>>&nbsp;
				<img src="<? echo GRAPHICSPATH.'measure.png'; ?>"><input type="checkbox" name="measure" value="1" <?php if($this->user->rolle->measure){echo 'checked="true"';} ?>>
				<img src="<? echo GRAPHICSPATH.'freepolygon.png'; ?>"><input type="checkbox" name="freepolygon" value="1" <?php if($this->user->rolle->freepolygon){echo 'checked="true"';} ?>>
				
			</td>
		</tr>
		<tr align="center">
			<td align="left" style="padding: 8px; padding-top: 0px"><?php echo $strSearchColor; ?>:&nbsp;</td>
			<td align="left" style="padding: 8px; padding-top: 0px">
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
			</td>
		</tr>
	</table>
</div>
<div style="margin:10px;margin-bottom:20px;border: 1px solid #cccccc">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
			<td colspan="2" style="background-color: #c7d9e6; padding: 2px;padding-left: 8px"><span class="fett"><? echo $strMapOptions; ?></span></td>
		</tr>
		<tr>
			<td align="left" style="padding: 8px"><?php echo $strZoomFactor; ?>:&nbsp;</td>
			<td style="padding: 8px"><input name="nZoomFactor" type="text" value="<?php echo $this->user->rolle->nZoomFactor; ?>" size="2" maxlength="3"></td>
		</tr>
		<tr>
			<td align="left" style="padding: 8px; padding-top: 0px"><?php echo $strMapSize; ?>:&nbsp;</td>
			<td style="padding: 8px; padding-top: 0px">  
				<select name="mapsize">
					<? $selected = false; ?>
					<option value="300x300" <?php if ($this->user->rolle->mapsize=="300x300") { echo "selected"; $selected = true; } ?>>300x300</option>
					<option value="400x400" <?php if ($this->user->rolle->mapsize=="400x400") { echo "selected"; $selected = true;} ?>>400x400</option>
					<option value="500x500" <?php if ($this->user->rolle->mapsize=="500x500") { echo "selected"; $selected = true;} ?>>500x500</option>
					<option value="600x600" <?php if ($this->user->rolle->mapsize=="600x600") { echo "selected"; $selected = true;} ?>>600x600</option>
					<option value="800x600" <?php if ($this->user->rolle->mapsize=="800x600") { echo "selected"; $selected = true;} ?>>800x600</option>
					<option value="850x700" <?php if ($this->user->rolle->mapsize=="850x700") { echo "selected"; $selected = true;} ?>>850x700</option>
					<option value="1000x800" <?php if ($this->user->rolle->mapsize=="1000x800") { echo "selected"; $selected = true;} ?>>1000x800</option>
					<option value="1150x700" <?php if ($this->user->rolle->mapsize=="1150x700") { echo "selected"; $selected = true;} ?>>1150x700</option>
					<option value="1200x850" <?php if ($this->user->rolle->mapsize=="1200x850") { echo "selected"; $selected = true;} ?>>1200x850</option>
					<option value="1400x850" <?php if ($this->user->rolle->mapsize=="1400x850") { echo "selected"; $selected = true;} ?>>1400x850</option>
					<option value="1600x850" <?php if ($this->user->rolle->mapsize=="1600x850") { echo "selected"; $selected = true;} ?>>1600x850</option>
					<? if($selected == false){ ?>
					<option value="<? echo $this->user->rolle->mapsize; ?>" selected><? echo $this->user->rolle->mapsize; ?></option>              	
					<? } ?>
				</select>
			</td>
		</tr>
		<tr align="center">
			<td align="left" style="padding: 8px; padding-top: 0px"><?php echo $strMapExtent; ?>:&nbsp;</td>
			<td align="left" style="padding: 8px; padding-top: 0px"><?php
				$curExtentText=$this->user->rolle->oGeorefExt->minx.' '.$this->user->rolle->oGeorefExt->miny.', '.$this->user->rolle->oGeorefExt->maxx.' '.$this->user->rolle->oGeorefExt->maxy;
			 ?><input name="newExtent" id="newExtent" type="text" size="<?php echo strlen($curExtentText); ?>" value="<?php echo $curExtentText; ?>">
			</td>
		</tr>
		<tr align="center">
			<td align="left" style="padding: 8px; padding-top: 0px"><?php echo $strMapProjection; ?>:&nbsp;</td>
			<td align="left" style="padding: 8px; padding-top: 0px">
				<select name="epsg_code" onchange="ahah('index.php','go=spatial_processing&newSRID='+this.form.epsg_code.value+'&operation=transform&resulttype=wkt',new Array(newExtent), '');">
					<?
					foreach($this->epsg_codes as $epsg_code){
						echo '<option';
						if($this->user->rolle->epsg_code == $epsg_code['srid'])echo ' selected';
						echo ' value="'.$epsg_code['srid'].'">'.$epsg_code['srid'].': '.$epsg_code['srtext'].'</option>';
					}
					?>
				</select>
			</td>
		</tr>
		<tr align="center">
			<td align="left" style="padding: 8px; padding-top: 0px"><?php echo $strSecondMapProjection; ?>:&nbsp;</td>
			<td align="left" style="padding: 8px; padding-top: 0px">
				<select name="epsg_code2">
					<option value="">--<?php echo $this->strChoose; ?>--</option>
					<?
					foreach($this->epsg_codes as $epsg_code){
						echo '<option';
						if($this->user->rolle->epsg_code2 == $epsg_code['srid'])echo ' selected';
						echo ' value="'.$epsg_code['srid'].'">'.$epsg_code['srid'].': '.$epsg_code['srtext'].'</option>';
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="left" style="padding: 8px; padding-top: 0px"><?php echo $strCoordType; ?>:&nbsp;</td>
			<td style="padding: 8px; padding-top: 0px">
				<select name="coordtype">
					<option value="dec" <?php if ($this->user->rolle->coordtype=="dec") { echo "selected"; } ?>>Dezimal</option>
					<option value="dms" <?php if ($this->user->rolle->coordtype=="dms") { echo "selected"; } ?>>Grad,Minuten,Sekunden</option>
					<option value="dmin" <?php if ($this->user->rolle->coordtype=="dmin") { echo "selected"; } ?>>Grad Dezimalminuten</option>				
				</select>
			</td>
		</tr>
		<tr>
			<td align="left" style="padding: 8px; padding-top: 0px"><?php echo $strRunningCoords;; ?>:&nbsp;</td>
			<td style="padding: 8px; padding-top: 0px"><input name="runningcoords" type="checkbox" value="1" <? if($this->user->rolle->runningcoords == '1'){echo 'checked="true"';} ?> ></td>
		</tr>
	</table>
</div>
<div style="margin:10px;margin-bottom:0px;border: 1px solid #cccccc">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="2" style="background-color: #c7d9e6; padding: 2px;padding-left: 8px"><span class="fett"><? echo $strDataPresentation; ?></span></td>
		</tr>
		<tr>
			<td align="left" style="width: 230px;padding: 8px"><?php echo $strSingleQuery; ?>:&nbsp;</td>
			<td style="padding: 8px"><input name="singlequery" type="checkbox" value="1" <? if($this->user->rolle->singlequery == '1'){echo 'checked="true"';} ?> ></td>
		</tr>
		<tr>
			<td align="left" style="padding: 8px; padding-top: 0px"><?php echo $strQuerymode; ?>:&nbsp;</td>
			<td style="padding: 8px; padding-top: 0px"><input name="querymode" type="checkbox" value="1" <? if($this->user->rolle->querymode == '1'){echo 'checked="true"';} ?> ></td>
		</tr>
		<tr align="center">
			<td align="left" style="padding: 8px; padding-top: 0px"><?php echo $strNewDatasetOrder; ?>:&nbsp;</td>
			<td align="left" style="padding: 8px; padding-top: 0px">
				<select name="geom_edit_first">
					<option value="0"<?php if($this->user->rolle->geom_edit_first == '0') { echo ' selected'; }	?>><?php echo $strGeomSecond; ?></option>
					<option value="1"<?php if($this->user->rolle->geom_edit_first == '1') { echo ' selected'; }	?>><?php echo $strGeomFirst; ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td align="left" style="padding: 8px; padding-top: 0px"><?php echo $strFontSizeGLE; ?>:&nbsp;</td>
			<td style="padding: 8px; padding-top: 0px"><input name="fontsize_gle" type="text" value="<?php echo $this->user->rolle->fontsize_gle; ?>" size="2" maxlength="2"></td>
		</tr>
		<tr>
			<td align="left" style="padding: 8px; padding-top: 0px"><?php echo $strHighlight; ?>:&nbsp;</td>
			<td style="padding: 8px; padding-top: 0px"><input name="highlighting" type="checkbox" value="1" <? if($this->user->rolle->highlighting == '1'){echo 'checked="true"';} ?> ></td>
		</tr>
		<tr>		
			<td align="left" style="padding: 8px; padding-top: 0px"><?php echo $strhist_timestamp; ?>:&nbsp;<a href="javascript:;" onclick="new CalendarJS().init('hist_timestamp');"><img title="TT.MM.JJJJ hh:mm:ss" src="<? echo GRAPHICSPATH; ?>calendarsheet.png" border="0"></a><div id="calendar" style="bottom:100px"><a name="calendar_hist_timestamp"></div></td>
			<td style="padding: 8px; padding-top: 0px"><input onchange="if(this.value.length == 10)this.value = this.value + ' 06:00:00'" id="hist_timestamp" name="hist_timestamp" type="text" value="<?php echo $this->user->rolle->hist_timestamp; ?>" size="16"></td>
		</tr>
	</table>
</div>
<table>
  <tr>
    <td></td>
    <td><br><input type="submit" name="submit" value="<?php echo $this->strEnter; ?>"><br><br></td>
  </tr>
</table>
  <input type="hidden" name="go" value="">