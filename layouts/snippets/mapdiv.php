<?
global $supportedLanguages;
include(LAYOUTPATH . 'languages/mapdiv_' . $this->user->rolle->language . '.php'); ?>
<div id="mapimage">
	<input type="hidden" name="go" value="neu Laden">
	<INPUT TYPE="hidden" NAME="minx" VALUE="<?php echo $this->map->extent->minx; ?>">
	<INPUT TYPE="hidden" NAME="miny" VALUE="<?php echo $this->map->extent->miny; ?>">
	<INPUT TYPE="hidden" NAME="maxx" VALUE="<?php echo $this->map->extent->maxx; ?>">
	<INPUT TYPE="hidden" NAME="maxy" VALUE="<?php echo $this->map->extent->maxy; ?>">
	<input type="hidden" name="refmap_x">
	<input type="hidden" name="refmap_y">
	<INPUT TYPE="hidden" NAME="pixelsize" VALUE="<?php echo $pixelsize; ?>">
	<INPUT TYPE="HIDDEN" NAME="CMD" VALUE="">
	<INPUT TYPE="HIDDEN" NAME="last_button" VALUE="">
	<INPUT TYPE="HIDDEN" NAME="INPUT_TYPE" VALUE="">
	<INPUT TYPE="HIDDEN" NAME="INPUT_COORD" VALUE="">
	<INPUT TYPE="HIDDEN" NAME="searchradius" VALUE="<?php echo $this->formvars['searchradius']; ?>">
	<input type="hidden" name="imgxy" value="300 300">
	<input type="hidden" name="imgbox" value="-1 -1 -1 -1">
	<input size="50" type="hidden" name="result">
	<input name="gps_posx" type="hidden" value="<? echo $this->formvars['gps_posx']; ?>">
	<input name="gps_posy" type="hidden" value="<? echo $this->formvars['gps_posy']; ?>">
	<input size="50" type="hidden" value="<? echo $this->formvars['gps_follow'] ?>" name="gps_follow">
	<input type="hidden" name="str_pathx" value="<? echo $this->formvars['str_pathx']; ?>">
	<input type="hidden" name="str_pathy" value="<? echo $this->formvars['str_pathy']; ?>">
	<input type="hidden" name="str_polypathx" value="<? echo $this->formvars['str_polypathx']; ?>">
	<input type="hidden" name="str_polypathy" value="<? echo $this->formvars['str_polypathy']; ?>">
	<input type="hidden" name="svg_string" value="">
	<input type="hidden" name="scrollposition" value="">
	<input type="hidden" name="vertices" id="vertices" value="">
	<input type="hidden" name="legendtouched" value="0">
	<input type="hidden" name="svghelp" id="svghelp">
	<input type="hidden" name="activated_vertex" value="0">
	<input type="hidden" name="measured_distance" value="<? echo $this->formvars['measured_distance']; ?>">
	<input type="hidden" name="layer_options_open" value="">
	<input type="hidden" name="group_options_open" value="">
	<input type="hidden" name="free_polygons" value="">
	<input type="hidden" name="free_texts" value="">
	<?php
		include(LAYOUTPATH.'snippets/SVG_map.php');
	?>
	
<div id="showcoords" style="display:none;">
	<i class="fa fa-close" style="cursor: pointer; float: right; margin: 0 5px 0 5px;" onclick="$('#showcoords').hide();"></i>
	<span class="fett"><?php echo $strShowCoordinates; ?></span>
	&nbsp;
	EPSG-Code <?php echo $this->user->rolle->epsg_code;?>:
	&nbsp;
	<input type="text" style="width: 150px" name="firstcoords" value=""><?
	if ($this->user->rolle->epsg_code2 != '') { ?>
		&nbsp;
		EPSG-Code <?php echo $this->user->rolle->epsg_code2; ?>:
		&nbsp;
		<input type="text" style="width: 150px" name="secondcoords" value=""><?
	} ?>
</div>

<div id="showmeasurement" style="display:none;">
	<i class="fa fa-close" style="cursor: pointer; float: right; margin: 0 5px 0 5px;" onclick="$('#showmeasurement').hide();clearMeasurement();"></i>
	<span class="fett"><?php echo $strMeasurement; ?>:&nbsp;</span>
	<input type="text" style="width: 150px" id="measurement" name="measurement" value="">
</div>
	
</div>

<div id="scale_bar">
	<div id="scale_selector_div" style="float: left; margin-top: 4px;">
		<div style="width:145px;" onmouseenter="document.getElementById('scales').style.display='inline-block';" onmouseleave="document.getElementById('scales').style.display='none';">
			<div valign="top" style="height:0px; position:relative;">
				<div id="scales" style="display:none; position:absolute; left:66px; bottom:-1px; width: 70px; vertical-align:top; overflow:hidden; border:solid grey 1px;">
					<select size="<? echo count($selectable_scales); ?>" style="padding:4px; margin:-2px -17px -4px -4px;" onmousedown="setScale(this);" onclick="setScale(this);">
						<? 
							foreach($selectable_scales as $scale){
								echo '<option onmouseover="this.selected = true;" value="'.$scale.'">1:&nbsp;&nbsp;'.$scale.'</option>';
							}
						?>
					</select>
				</div>
			</div>
			&nbsp;&nbsp;<span class="fett"><?php echo $this->strMapScale; ?>&nbsp;1:&nbsp;</span><input type="text" id="scale" onkeyup="if (event.keyCode == 13) { setScale(this); }" autocomplete="off" name="nScale" style="width:58px" value="<?php echo round($this->map_scaledenom); ?>">
		</div>
	</div>
	<div id="map_copyrights_div" style="float: right; margin-right: 5px; height: 30px;">
		<a href="javascript:void(0);" title="<? echo $strShowCopyrightsTitle; ?>" onclick="showCopyrights('<? echo $strShowCopyrightHeader; ?>')">
			<i class="fa fa-copyright fa-solid map-copyright-button" style="margin-top: 7px; font-size: 18px;"></i>
		</a>
	</div>	
	<div id="map_info_div" style="float: right; margin-right: 5px; height: 30px;">
		<a
			href="#"
			title="<? echo $strShowMapParameterTitle; ?>"
			onclick="showMapParameter(
				<? echo $this->user->rolle->epsg_code; ?>,
				<? echo $this->map->width; ?>,
				<? echo $this->map->height; ?>,
				<? echo "{
					'strShowMapParameterHeader' : '" . $strShowMapParameterHeader . "',
					'strCoordinateReferenceSystem' : '" . $this->strCoordinateReferenceSystem . "',
					'strLowerLeftCorner' : '" .  $strLowerLeftCorner . "',
					'strUpperRightCorner' : '" .  $strUpperRightCorner . "',
					'strMapExtent' : '" .  $strMapExtent . "',
					'strMapSize' : '" .  $strMapSize . "',
					'strPixelSize' : '" .  $strPixelSize . "'
				}"; ?>
			)"
		>
			<i class="fa fa-info-circle map-info-button"></i>
		</a>
	</div>
	<div id="scalebar_div" style="float: right; margin-top: 1px;">
		<div style="margin-left: 5px;">
			<img
				id="scalebar"
				valign="top"
				style="margin-top: 5px; padding-right:<? echo ($this->user->rolle->hideLegend ? '35' : '5'); ?>px"
				alt="Maßstabsleiste"
				src="<? echo $this->img['scalebar']; ?>"
			>
		</div>
	</div>
	<div id="running_coordinates_div" style="float: left; padding: 4 0 0 5; <? if($this->user->rolle->runningcoords == '0') { echo 'display:none';} ?>">
		<span class="fett"><?php echo $this->strCoordinates; ?></span>&nbsp;
		<input type="text" style="width: 200px" class="transparent_input" name="runningcoords" value="">
	</div>
	<div id="epsg_code_div" style="float: left; margin-top: 8px;<? if($this->user->rolle->runningcoords == '0') { echo 'display:none';} ?>">
		<span title="<? echo $this->epsg_codes[$this->user->rolle->epsg_code]['srtext']; ?>">EPSG:<?php echo $this->user->rolle->epsg_code; ?></span>
	</div>
</div>

<? if (is_array($this->Lagebezeichung) AND $this->Lagebezeichung['gemeindename'] != '') { ?>
<div id="lagebezeichnung_bar">
	<div id="lagebezeichnung">
		<span class="fett">Gemeinde:</span>&nbsp;<?php echo $this->Lagebezeichung['gemeindename']; ?>
		<span class="fett">Gemarkung:</span>&nbsp;<?php echo $this->Lagebezeichung['gemkgname']; ?>&nbsp;(<?php echo $this->Lagebezeichung['gemkgschl']; ?>)
		<span class="fett">Flur:</span>&nbsp;<?php echo $this->Lagebezeichung['flur']; ?>
	</div>
</div>
<? } 

if ($this->user->rolle->showmapfunctions) { ?>
	<div id="map_functions_bar">
		<div id="mapbuttons">
			<div style="float: left;">
				<a title="<? echo $strExtentURL ?>" href="javascript:showExtentURL(<? echo $this->user->rolle->epsg_code; ?>);">
					<div class="button url_extent">
						<img src="<? echo GRAPHICSPATH.'leer.gif'; ?>">
					</div>
				</a>
			</div>
			<div style="float: left;">
				<a title="<? echo $strSaveExtent; ?>" href="index.php?go=Kartenkommentar_Formular">
					<div class="button save_extent">
						<img src="<? echo GRAPHICSPATH.'leer.gif'; ?>">
					</div>
				</a>
			</div>				
			<div style="float: left;">
				<a title="<? echo $strChoose ?>" href="index.php?go=Kartenkommentar_Waehlen">
					<div class="button load_extent">
						<img src="<? echo GRAPHICSPATH.'leer.gif'; ?>">
					</div>
				</a>
			</div>
			<div style="float: left;">
				<a title="<? echo $strSaveLayers; ?>" href="javascript:document.GUI.go.value='Layerauswahl_Formular';document.GUI.submit();">
					<div class="button save_layers">
						<img src="<? echo GRAPHICSPATH.'leer.gif'; ?>">
					</div>
				</a>
			</div>
			<div style="float: left;">
				<a title="<? echo $strChooseLayers ?>" href="index.php?go=Layerauswahl_Waehlen">
					<div class="button load_layers">
						<img src="<? echo GRAPHICSPATH.'leer.gif'; ?>">
					</div>
				</a>
			</div>
			<div style="float: left;"><?
				if (SHOW_MAP_IMAGE == 'true') { ?>
					<a title="<? echo $strMapImageURL ?>" id="MapImageLink" href="javascript:showMapImage();">
						<div class="button save_image">
							<img src="<? echo GRAPHICSPATH.'leer.gif'; ?>">
						</div>
					</a><?
				} ?>
			</div>
			<div style="float: left;">
				<a title="<? echo $strMapSize; ?>" href="javascript:resizemap2window();" >
					<div class="button resize_map">
						<img src="<? echo GRAPHICSPATH.'leer.gif'; ?>">
					</div>
				</a>
			</div>
		</div>
		<div class="special-options" style="float: right; margin-top: 5px; margin-right: 5px">
			<span id="punktfang-map"><input id="punktfang" type="checkbox" onclick="toggle_vertices()" name="punktfang">&nbsp;<? echo $strPointSnap; ?></span>
			<div id="options" style="padding-left: 10px;float: right"></div><!-- hier werden die Spezialoptionen eingefügt -->
		</div>
	</div><?
}
else { ?>
	<div id="options" style="display: none;"></div>
	<input id="punktfang" class="punktfang-map" type="hidden" name="punktfang"><?php
} ?>