      <table bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0">
        <tr bgcolor="#FFFFFF" align="center">
          <td>
            <input type="hidden" name="go" value="neu Laden">
            <INPUT TYPE="hidden" NAME="minx" VALUE="<?php echo $this->map->extent->minx; ?>">
            <INPUT TYPE="hidden" NAME="miny" VALUE="<?php echo $this->map->extent->miny; ?>">
            <INPUT TYPE="hidden" NAME="maxx" VALUE="<?php echo $this->map->extent->maxx; ?>">
            <INPUT TYPE="hidden" NAME="maxy" VALUE="<?php echo $this->map->extent->maxy; ?>">
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
            <input type="hidden" name="stopnavigation" value="0">
						<input type="hidden" name="svghelp" id="svghelp">
						<input type="hidden" name="activated_vertex" value="0">
						<input type="hidden" name="measured_distance" value="<? echo $this->formvars['measured_distance']; ?>">						
						<input type="hidden" name="layer_options_open" value="">
    <?php
        include(LAYOUTPATH.'snippets/SVG_map.php');
    ?>
          </td>
        </tr>
        <tr>
        	<td width="100%">
        		<table width="100%" border="0" cellpadding="0" cellspacing="0">
        			<tr>
        				<td width="100%" colspan="3" class="map-bottom">
        					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						        <tr style="background-color: <? echo BG_MENUETOP; ?>;">
						          <td style="width:150px;height:30px">
												<div style="width:150px;" onmouseover="document.getElementById('scales').style.display='inline-block';" onmouseout="document.getElementById('scales').style.display='none';">
													<div valign="top" style="height:0px; position:relative;">
														<div id="scales" style="display:none; position:absolute; left:60px; bottom:-1px; width: 78px; vertical-align:top; overflow:hidden; border:solid grey 1px;">
															<select size="<? echo count($selectable_scales); ?>" style="padding:4px; margin:-2px -17px -4px -4px;" onclick="document.GUI.nScale.value=this.value; document.getElementById('scales').style.display='none'; document.GUI.go.value='neu Laden'; document.GUI.submit();">
																<? 
																	foreach($selectable_scales as $scale){
																		echo '<option onmouseover="this.selected = true;" value="'.$scale.'">1:&nbsp;&nbsp;'.$scale.'</option>';
																	}
																?>
															</select>
														</div>
													</div>
													&nbsp;&nbsp;<span class="fett"><?php echo $this->strMapScale; ?>&nbsp;1:&nbsp;</span><input type="text" id="scale" autocomplete="off" name="nScale" style="width:58px" value="<?php echo round($this->map_scaledenom); ?>">
												</div>
						          </td>
			        				<td align="left" style="width:80%;<? if($this->user->rolle->runningcoords == '0'){echo ';display:none';} ?>">
			          				<span class="fett"><?php echo $this->strCoordinates; ?></span>&nbsp;
			          				<input type="text" style="width: 190px" class="transparent_input" name="runningcoords" value=""><span title="<? echo $this->epsg_codes[$this->user->rolle->epsg_code]['srtext']; ?>">EPSG-Code:<?php echo $this->user->rolle->epsg_code; ?></span>
											</td>
						          <td width="25%" align="right">
						            <img id="scalebar" style="padding-right:<? if($this->user->rolle->hideLegend)echo '35';else echo '5'; ?>px" alt="Maßstabsleiste" src="<? echo $this->img['scalebar']; ?>">
						          </td>
						        </tr>
						    </table>
						  	</td>
						  </tr>
        			<tr>
        				<td width="100%" colspan="3" class="map-bottom">
        					<table width="100%" border="0" cellpadding="0" cellspacing="0">
										<tr style="background-color: <? echo BG_MENUETOP; ?>;">
											<td colspan="3" style="height:22px" align="center">
						          	<div id="lagebezeichnung">
						          <?	if($this->Lagebezeichung!='')echo '<span class="fett">Gemeinde:&nbsp;</span>'.$this->Lagebezeichung['gemeindename'].' <span class="fett">Gemarkung:</span>&nbsp;'.$this->Lagebezeichung['gemkgname'].' ('.$this->Lagebezeichung['gemkgschl'].') <span class="fett">Flur:</span>&nbsp;'.$this->Lagebezeichung['flur']; ?>
						          	</div>
						          </td>
										</tr>
			        			<tr id="showcoords" style="background-color: <? echo BG_MENUETOP; ?>;display:none">
			        				<td style="width:150px">
			          					<span class="fett">&nbsp;&nbsp;<?php echo $strShowCoordinates; ?></span>&nbsp;
			          				</td>
			        				<td colspan="2">
			        					<input type="text" style="width: 150px" name="firstcoords" value="">&nbsp;EPSG-Code:<?php echo $this->user->rolle->epsg_code; ?>
			        					<? if($this->user->rolle->epsg_code2 != ''){ ?>
			        					<br><input type="text" style="width: 150px" name="secondcoords" value="">&nbsp;EPSG-Code:<?php echo $this->user->rolle->epsg_code2; ?>
			        					<? } ?>
			        				</td>
			        			</tr>
									</table>
								</td>
							</tr>
			        <?
			        # 2006-03-20 pk
			        if ($this->user->rolle->newtime!='') { ?>
			        <tr style="background-color: <? echo BG_MENUESUB; ?>;">
			          <td class="map-options" height="36" colspan="3">
			          	<table width="100%" border="0" cellpadding="0" cellspacing="0">
			          		<tr>
			          			<td>
						          	<div id="maptime" style="padding: 2px">
													<table cellpadding="0" cellspacing="0">
														<tr>
															<td style="padding: 0 0 0 5;"><a title="<? echo $strSaveExtent; ?>" href="index.php?go=Kartenkommentar_Formular"><div class="button_background"><div class="emboss save_extent"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
															<td style="padding: 0 0 0 10;"><a title="<? echo $strChoose ?>" href="index.php?go=Kartenkommentar_Waehlen"><div class="button_background"><div class="emboss load_extent"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
															<td style="padding: 0 0 0 10;"><a title="<? echo $strSaveLayers; ?>" href="javascript:document.GUI.go.value='Layerauswahl_Formular';document.GUI.submit();"><div class="button_background"><div class="emboss save_layers"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
															<td style="padding: 0 0 0 10;"><a title="<? echo $strChooseLayers ?>" href="index.php?go=Layerauswahl_Waehlen"><div class="button_background"><div class="emboss load_layers"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
															<td style="padding: 0 0 0 10;"><? if(SHOW_MAP_IMAGE == 'true'){ ?><a title="<? echo $strMapImageURL ?>" id="MapImageLink" target="_blank" href="" onmouseover="javascript:showMapImage();"><div class="button_background"><div class="emboss save_image"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></span><? } ?></td>
															<td style="padding: 0 0 0 10;"><a title="<? echo $strMapSize; ?>" href="javascript:resizemap2window();" ><div class="button_background"><div class="emboss resize_map"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
														</tr>
													</table>
						            </div>
						          </td>
						          <td width="120px" class="special-options">
												<input type="checkbox" onclick="toggle_vertices()" name="punktfang">&nbsp;Punktfang
												<div id="options"></div><!-- hier werden die Spezialoptionen eingefügt -->
											</td>
											<td>&nbsp;</td>
						        </tr>
						    	</table>
			          </td>
			        </tr>
			    	</table>
			  	</td>
			  </tr>
        <?php  }  ?>
      </table>