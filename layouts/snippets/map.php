<?php
# 2007-12-30 pk
  include(LAYOUTPATH.'languages/map_'.$this->user->rolle->language.'.php');
	include(LAYOUTPATH.'snippets/ahah.php');
	global $selectable_scales;
	$selectable_scales = array_reverse($selectable_scales);
?>

<script type="text/javascript">

function zoomto(layer_id, oid, tablename, columnname){
  location.href="index.php?go=zoomtoPolygon&oid="+oid+"&layer_tablename="+tablename+"&layer_columnname="+columnname+"&layer_id="+layer_id+"&selektieren=zoomonly";
}

function toggle_vertices(){	
	document.getElementById("vertices").SVGtoggle_vertices();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
}

function show_vertices(){	
	document.getElementById("vertices").SVGshow_vertices();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
}

function startup(){
	document.getElementById("map").SVGstartup();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
}

function stopwaiting(){
	if(typeof document.getElementById("svghelp").SVGstopwaiting == 'function')
	document.getElementById("svghelp").SVGstopwaiting();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
}

function startwaiting(){
	if(typeof document.getElementById("svghelp").SVGstartwaiting == 'function')
	document.getElementById("svghelp").SVGstartwaiting();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
}

function showtooltip(result, showdata){
	document.getElementById("svghelp").SVGshowtooltip(result, showdata);			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
}

function resizemap2window() {
	startwaiting();
  if(typeof(window.innerWidth) == 'number'){
    width = window.innerWidth;
    height = window.innerHeight;
  }else if(document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)){
    width = document.documentElement.clientWidth;
    height = document.documentElement.clientHeight;
  }else if(document.body && (document.body.clientWidth || document.body.clientHeight)){
    width = document.body.clientWidth;
    height = document.body.clientHeight;
  }
	document.location.href='index.php?go=ResizeMap2Window&browserwidth='+width+'&browserheight='+height+'&nScale='+document.GUI.nScale.value;
}

function showMapImage(){ 
	svgdoc = document.SVG.getSVGDocument();	
	var svg = svgdoc.getElementById("moveGroup");
	try{
  	var serializer = new XMLSerializer();
  	document.GUI.svg_string.value = serializer.serializeToString(svg);
	}
	catch(e){
		document.GUI.svg_string.value = printNode(svg);
	}
  document.getElementById('MapImageLink').href='index.php?go=showMapImage&svg_string='+document.GUI.svg_string.value;
}

function slide_legend_in(evt){
	document.getElementById('legenddiv').className = 'slidinglegend_slidein';
}

function slide_legend_out(evt){
	if(window.outerWidth - evt.pageX > 100){
		document.getElementById('legenddiv').className = 'slidinglegend_slideout';
	}
}

<? if (!ie_check()){ ?>					// Firefox, Chrome

function switchlegend(){
	if(document.getElementById('legenddiv').className == 'normallegend'){
		document.getElementById('legenddiv').className = 'slidinglegend_slideout';
		ahah('index.php', 'go=changeLegendDisplay&hide=1', new Array('', ''), new Array("", "execute_function"));
		document.getElementById('LegendMinMax').src='<?php echo GRAPHICSPATH; ?>maximize_legend.png';
		document.getElementById('LegendMinMax').title="Legende zeigen";
	}
	else{
		document.getElementById('legenddiv').className = 'normallegend';
		ahah('index.php', 'go=changeLegendDisplay&hide=0', new Array('', ''), new Array("", "execute_function"));
		document.getElementById('LegendMinMax').src='<?php echo GRAPHICSPATH; ?>minimize_legend.png';
		document.getElementById('LegendMinMax').title="Legende verstecken";
	}
}

<? }else{ ?>						// IE

function switchlegend(){
	if(document.getElementById('legendTable').style.display == 'none'){
		document.getElementById('legendTable').style.display='';
		ahah('index.php', 'go=changeLegendDisplay&hide=0', new Array('', ''), new Array("", "execute_function"));
		document.getElementById('LegendMinMax').src='<?php echo GRAPHICSPATH; ?>maximize.png';
		document.getElementById('LegendMinMax').title="Legende verstecken";
	}
	else{
		document.getElementById('legendTable').style.display='none';
		ahah('index.php', 'go=changeLegendDisplay&hide=1', new Array('', ''), new Array("", "execute_function"));
		document.getElementById('LegendMinMax').src='<?php echo GRAPHICSPATH; ?>minimize.png';
		document.getElementById('LegendMinMax').title="Legende zeigen";
	}
}

<? } ?>

</script>

<?

  $res_x    = $this->map->width;
  $res_y    = $this->map->height;
  $legendheight = $this->map->height-52;
	if($this->user->rolle->runningcoords == 1)$legendheight = $legendheight + 22;
  $res_xm   = $this->map->width/2;
  $res_ym   = $this->map->height/2;
  $dx       = $this->map->extent->maxx-$this->map->extent->minx;
  $dy       = $this->map->extent->maxy-$this->map->extent->miny;
  $pixelsize    = ($dx/$res_x+$dy/$res_y)/2;

# 2006-02-17 pk den javascript teil, der hier drin war in SVGvars_coordscript verschoben.
if ($this->Fehlermeldung!='') {
       include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
if($this->formvars['gps_follow'] == ''){
	$this->formvars['gps_follow'] = 'off';
}
?>

  <table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="center" valign="top" class="map-right" height="100%">
      <div id="map">
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
						          <td width="25%" height="30">
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
						          <td width="50%" align="center">
						          	<? if($this->map->width > 700) {
						          		echo '<div id="lagebezeichnung">';
						          		if($this->Lagebezeichung!=''){
														echo '<span class="fett">Gemeinde:&nbsp;</span>'.$this->Lagebezeichung['gemeindename'].' <span class="fett">Gemarkung:</span>&nbsp;'.$this->Lagebezeichung['gemkgname'].' <span class="fett">Flur:</span>&nbsp;'.$this->Lagebezeichung['flur'];
						          		} ?>
						          	</div>
						          </td>
						          <? }else{ ?>
						          <td>&nbsp;</td>
						          <? } ?>
						          <td width="25%" align="right">
						            <img id="scalebar" style="margin-right:35px" alt="Maßstabsleiste" src="<? echo $this->img['scalebar']; ?>">
						          </td>
						        </tr>
						    </table>
						  	</td>
						  </tr>
						  <? if($this->map->width < 700) { ?>
						  <tr style="background-color: <? echo BG_MENUETOP; ?>;">
			          <td colspan="3" align="center">
			          	<div id="lagebezeichnung">
			          		<?php 
			          		if($this->Lagebezeichung!=''){
			          			echo '<span class="fett">Gemeinde:&nbsp;</span>'.$this->Lagebezeichung['gemeindename'].' <span class="fett">Gemarkung:</span>&nbsp;'.$this->Lagebezeichung['gemkgname'].' <span class="fett">Flur:</span>&nbsp;'.$this->Lagebezeichung['flur'];
			          		} ?>
			          	</div>
			          </td>
			        </tr>
			        <? } ?>
        			<tr>
        				<td width="100%" colspan="3" class="map-bottom">
        					<table width="100%" border="0" cellpadding="0" cellspacing="0">
			        			<tr style="background-color: <? echo BG_MENUETOP; if($this->user->rolle->runningcoords == '0'){echo ';display:none';} ?>">
			        				<td style="width:100px">
			          					<span class="fett">&nbsp;&nbsp;<?php echo $this->strCoordinates; ?></span>&nbsp;
			          				</td>
			        				<td colspan="2"><input type="text" style="width: 190px" class="transparent_input" name="runningcoords" value="">&nbsp;EPSG-Code:<?php echo $this->user->rolle->epsg_code; ?></td>
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
			          <td class="map-options" height="33" colspan="3">
			          	<table width="100%" border="0" cellpadding="0" cellspacing="0">
			          		<tr>
			          			<td>
						          	<div id="maptime">
						          		&nbsp;&nbsp;<a href="index.php?go=Kartenkommentar_Formular&consumetime=<?php echo $this->user->rolle->newtime; ?>&hauptkarte=<?php echo $this->img['hauptkarte']; ?>"><?php echo $strSaveExtent; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="index.php?go=Kartenkommentar_Waehlen&prevtime=<?php echo $this->user->rolle->newtime; ?>"><?php echo $strChoose ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<? if(SHOW_MAP_IMAGE == 'true'){ ?><a id="MapImageLink" target="_blank" href="" onmouseover="javascript:showMapImage();"><?php echo $strMapImageURL; ?></a></span><? } ?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:resizemap2window();" ><? echo $strMapSize; ?></a>
						            </div>
						          </td>
						          <td class="special-options">
												<input type="checkbox" onclick="toggle_vertices()" name="punktfang">&nbsp;Punktfang
												<div id="options"></div><!-- hier werden die Spezialoptionen eingefügt -->
											</td>
						        </tr>
						    	</table>
			          </td>
			        </tr>
			    	</table>
			  	</td>
			  </tr>
        <?php  }  ?>
        <tr>
        	<td>
        	</td>
        	<td width="200">
        	</td>
        	<td>
        	</td>
        </tr>
      </table>
      </div>
      </td>
      <td valign="top" class="legend-top">
				<div id="legenddiv" <? if (!ie_check() AND $this->user->rolle->hideLegend)echo 'onmouseenter="slide_legend_in(event);" onmouseleave="slide_legend_out(event);" class="slidinglegend_slideout"'; else echo 'class="normallegend"'; ?>>
					<table width="100%" border="0" cellpadding="0" cellspacing="0" class="legend-switch">
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
							?><input type="submit" name="neuladen" onclick="document.getElementById('sperrdiv').style.width = '100%';startwaiting();document.GUI.go.value='neu Laden';" value="<?php echo $strLoadNew; ?>" tabindex="1"></div>
							<br>
							<? if(defined('LAYER_ID_SCHNELLSPRUNG') AND LAYER_ID_SCHNELLSPRUNG != ''){
								include(SNIPPETS.'schnellsprung.php');
								} ?>
							&nbsp;
							<div id="legendcontrol">
								<a href="index.php?go=reset_querys"><img src="graphics/tool_info.png" border="0" alt="<? echo $strInfoQuery; ?>" title="<? echo $strInfoQuery.' | '.$strClearAllQuerys; ?>" width="17"></a>
								<a href="index.php?go=reset_layers"><img src="graphics/layer.png" border="0" alt="<? echo $strLayerControl; ?>" title="<? echo $strLayerControl.' | '.$strDeactivateAllLayer; ?>" width="20" height="20"></a><br>
							</div>
						<div id="scrolldiv" onscroll="document.GUI.scrollposition.value = this.scrollTop;" style="height:<?php echo $legendheight; ?>; overflow:auto; scrollbar-base-color:<?php echo BG_DEFAULT ?>">
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
