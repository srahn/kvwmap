<?php
# 2007-12-30 pk
  include(LAYOUTPATH.'languages/map_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
	include(LAYOUTPATH.'snippets/ahah.php');
  echo $ahah;
?>

<script type="text/javascript">

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
	document.getElementById("svghelp").SVGstopwaiting();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
}

function showtooltip(result, showdata){
	document.getElementById("svghelp").SVGshowtooltip(result, showdata);			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
}

function resizemap2window() {
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
	document.location.href='index.php?go=ResizeMap2Window&width='+width+'&height='+height;
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

<? if (!ie_check()){ ?>					// Firefox, Chrome

function switchlegend(){
	if(document.getElementById('legenddiv').className == 'slidinglegend'){
		document.getElementById('legenddiv').className = 'normallegend';
		ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=changeLegendDisplay&hide=0', new Array(), "");
		document.getElementById('LegendMinMax').src='<?php echo GRAPHICSPATH; ?>maximize.png';
		document.getElementById('LegendMinMax').title="Legende verstecken";
	}
	else{
		document.getElementById('legenddiv').className = 'slidinglegend';
		ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=changeLegendDisplay&hide=1', new Array(), "");
		document.getElementById('LegendMinMax').src='<?php echo GRAPHICSPATH; ?>minimize.png';
		document.getElementById('LegendMinMax').title="Legende zeigen";
	}
}

<? }else{ ?>						// IE

function switchlegend(){
	if(document.getElementById('legendTable').style.display == 'none'){
		document.getElementById('legendTable').style.display='';
		ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=changeLegendDisplay&hide=0', new Array(), "");
		document.getElementById('LegendMinMax').src='<?php echo GRAPHICSPATH; ?>maximize.png';
		document.getElementById('LegendMinMax').title="Legende verstecken";
	}
	else{
		document.getElementById('legendTable').style.display='none';
		ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=changeLegendDisplay&hide=1', new Array(), "");
		document.getElementById('LegendMinMax').src='<?php echo GRAPHICSPATH; ?>minimize.png';
		document.getElementById('LegendMinMax').title="Legende zeigen";
	}
}

<? } ?>

</script>

<?

  $res_x    = $this->map->width;
  $res_y    = $this->map->height;
  $legendheight = $this->map->height-30;
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
      <td align="center" valign="top" style="border-width:0px; border-right:1px; border-right-color:#CCCCCC; border-right-style:solid;" height="100%">
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
    <?php
        include(LAYOUTPATH.'snippets/SVG_map.php');
    ?>
          </td>
        </tr>
        <tr>
        	<td width="100%">
        		<table width="100%" border="0" cellpadding="0" cellspacing="1">
        			<tr>
        				<td width="100%" colspan="3" style="border-style:solid; border-width:1px; border-color:#aaaaaa;">
        					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						        <tr style="background-color: <? echo BG_MENUETOP; ?>;">
						          <td width="30%" height="30">
									<div style="width:150px; position:relative" onmouseover="document.getElementById('scales').style.display='inline-block';" onmouseout="document.getElementById('scales').style.display='none';">
										&nbsp;<b><?php echo $strMapScale; ?>&nbsp;1:&nbsp;</b><input type="text" id="scale" autocomplete="off" name="nScale" style="width:58px" value="<?php echo round($this->map_scaledenom); ?>">
										<div id="scales" style="display:none; position:absolute; left:56px; bottom:21px; width: 78px; vertical-align:top; overflow:hidden; border:solid grey 1px;">
											<select size="8" style="padding:4px; margin:-2px -17px -4px -4px;" onclick="document.GUI.nScale.value=this.value; document.getElementById('scales').style.display='none'; document.GUI.submit();">
												<option onmouseover="this.selected = true;" value="1000000">1:&nbsp;&nbsp;1000000</option>
												<option onmouseover="this.selected = true;" value="250000">1:&nbsp;&nbsp;250000</option>
												<option onmouseover="this.selected = true;" value="100000">1:&nbsp;&nbsp;100000</option>
												<option onmouseover="this.selected = true;" value="50000">1:&nbsp;&nbsp;50000</option>
												<option onmouseover="this.selected = true;" value="10000">1:&nbsp;&nbsp;10000</option>
												<option onmouseover="this.selected = true;" value="5000">1:&nbsp;&nbsp;5000</option>
												<option onmouseover="this.selected = true;" value="1000">1:&nbsp;&nbsp;1000</option>
												<option onmouseover="this.selected = true;" value="500">1:&nbsp;&nbsp;500</option>
											</select>
										</div>
									</div>
						          </td>
						          <td width="40%" align="center">
						          	<? if($this->map->width > 700) {
						          		echo '<div id="lagebezeichnung">';
						          		if($this->Lagebezeichung!=''){
						          			echo '<b>Gemeinde:&nbsp;</b>'.$this->Lagebezeichung['gemeindename'].' <b>Gemarkung:</b>&nbsp;'.$this->Lagebezeichung['gemkgname'].' <b>Flur:</b>&nbsp;'.$this->Lagebezeichung['flur'];
						          		} ?>
						          	</div>
						          </td>
						          <? }else{ ?>
						          <td>&nbsp;</td>
						          <? } ?>
						          <td width="30%" align="right">
						            <img id="scalebar" alt="Maßstabsleiste" src="<? echo $this->img['scalebar']; ?>">
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
			          			echo '<b>Gemeinde:&nbsp;</b>'.$this->Lagebezeichung['gemeindename'].' <b>Gemarkung:</b>&nbsp;'.$this->Lagebezeichung['gemkgname'].' <b>Flur:</b>&nbsp;'.$this->Lagebezeichung['flur'];
			          		} ?>
			          	</div>
			          </td>
			        </tr>
			        <? } ?>
			        <tr style="background-color: <? echo BG_MENUETOP; if($this->user->rolle->runningcoords == '0'){echo ';display:none';} ?>">
			        	<td width="100px">
			          	<b>&nbsp;<?php echo $this->strCoordinates; ?></b>&nbsp;
			          </td>
			        	<td colspan="2" width="80%"><input type="text" style="border:0px;background-color:transparent" name="runningcoords" value="">&nbsp;EPSG-Code:<?php echo $this->user->rolle->epsg_code; ?></td>
			        </tr>
			        <tr id="showcoords" style="display:none">
			        	<td width="100px">
			          	<b>&nbsp;<?php echo $strShowCoordinates; ?></b>&nbsp;
			          </td>
			        	<td colspan="2" width="80%">
			        		<input type="text" name="firstcoords" value="">&nbsp;EPSG-Code:<?php echo $this->user->rolle->epsg_code; ?>
			        		<? if($this->user->rolle->epsg_code2 != ''){ ?>
			        		<br><input type="text" name="secondcoords" value="">&nbsp;EPSG-Code:<?php echo $this->user->rolle->epsg_code2; ?>
			        		<? } ?>
			        	</td>
			        </tr>
			        <?
			        # 2006-03-20 pk
			        if ($this->user->rolle->newtime!='') { ?>
			        <tr style="background-color: <? echo BG_MENUESUB; ?>;">
			          <td style="border-style:solid; border-width:1px; border-color:#aaaaaa;" height="33" colspan="3">
			          	<table width="100%" border="0" cellpadding="0" cellspacing="0">
			          		<tr>
			          			<td>
						          	<div id="maptime">
						          		&nbsp;<a href="index.php?go=Kartenkommentar_Formular&consumetime=<?php echo $this->user->rolle->newtime; ?>&hauptkarte=<?php echo $this->img['hauptkarte']; ?>"><?php echo $strSave; ?></a>&nbsp;|&nbsp;<a href="index.php?go=Kartenkommentar_Waehlen&prevtime=<?php echo $this->user->rolle->newtime; ?>"><?php echo $strChoose ?></a>&nbsp;|&nbsp;<? if(SHOW_MAP_IMAGE == 'true'){ ?><a id="MapImageLink" target="_blank" href="" onmouseover="javascript:showMapImage();"><?php echo $strMapImageURL; ?></a></b><? } ?>&nbsp;|&nbsp;<a href="javascript:resizemap2window();" ><? echo $strMapSize; ?></a>
						            </div>
						          </td>
						          <td id="options"></td><!-- hier werden die Spezialoptionen eingefügt -->
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
      <td valign="top">
				<div id="legenddiv" <? if (!ie_check() AND $this->user->rolle->hideLegend)echo 'class="slidinglegend"'; else echo 'class="normallegend"'; ?>>
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td bgcolor="<?php echo BG_DEFAULT ?>" align="left"><?php
								if ($this->user->rolle->hideLegend) {
									if (ie_check()){$display = 'none';}
									?><a id="linkLegend" href="javascript:switchlegend()"><img title="Legende zeigen" id="LegendMinMax" src="<?php  echo GRAPHICSPATH; ?>minimize.png" border="0"></a><?php
								}
								else {
									?><a id="linkLegend" href="javascript:switchlegend()"><img title="Legende verstecken" id="LegendMinMax" src="<?php  echo GRAPHICSPATH; ?>maximize.png" border="0"></a><?php
								}
							?></td>
						</tr>
					</table>
					<table id="legendTable" style="display: <? echo $display; ?>" cellspacing=0 cellpadding=2 border=0>
						<tr align="center">
							<td><?php echo $strAvailableLayer; ?>:</td>
						</tr>
						<tr align="left">
							<td><!-- bgcolor=#e3e3e6 -->
							<div align="center"><?php # 2007-12-30 pk
							?><input type="submit" class="button" name="senden" value="<?php echo $strLoadNew; ?>" class="send" tabindex="1"></div>
							<br>
							&nbsp;
							<a href="index.php?go=reset_querys"><img src="graphics/tool_info.png" border="0" alt="Informationsabfrage." title="Informationsabfrage | Hier klicken, um alle Abfragehaken zu entfernen" width="17"></a>
							<a href="index.php?go=reset_layers"><img src="graphics/layer.png" border="0" alt="Themensteuerung." title="Themensteuerung | Hier klicken, um alle Themen zu deaktivieren" width="20" height="20"></a><br>
						<div id="scrolldiv" onscroll="document.GUI.scrollposition.value = this.scrollTop;" style="width:240; height:<?php echo $legendheight; ?>; overflow:auto; scrollbar-base-color:<?php echo BG_DEFAULT ?>">
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
