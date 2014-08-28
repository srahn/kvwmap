<?php
# 2007-12-30 pk
  include(LAYOUTPATH.'languages/map_'.$this->user->rolle->language.'.php');
	
$userProjection = ms_newprojectionobj("init=epsg:".$this->user->rolle->epsg_code);
$wgsProjection = ms_newprojectionobj("init=epsg:4326");
$userExtent = $this->map->extent;
$userExtent->project($userProjection, $wgsProjection);
?>

<script src="../openlayers/OpenLayers.js"></script>
<script type="text/javascript" src="funktionen/prototype.js"></script>
<script type="text/javascript">
var map;
var old_extent;
var new_extent;
function init(){

    map = new OpenLayers.Map('map');

    var ol_wms = new OpenLayers.Layer.WMS( "OpenLayers WMS",
        "http://vmap0.tiles.osgeo.org/wms/vmap0",
        {layers: 'basic'} );
        var ol_wms_nobuffer = new OpenLayers.Layer.WMS( "OpenLayers WMS (no tile buffer)",
        "http://vmap0.tiles.osgeo.org/wms/vmap0",
        {layers: 'basic'}, {buffer: 0});

    map.addLayers([ol_wms, ol_wms_nobuffer]);
    map.addControl(new OpenLayers.Control.LayerSwitcher());
		
		var bounds = new OpenLayers.Bounds();
		bounds.extend(new OpenLayers.LonLat(<?php echo $userExtent->minx; ?>,<?php echo $userExtent->miny; ?>));
		bounds.extend(new OpenLayers.LonLat(<?php echo $userExtent->maxx; ?>,<?php echo $userExtent->maxy; ?>));
		map.zoomToExtent(bounds);
		old_extent = map.getExtent();
		saveMapSettings();
}

function saveMapSettings() {
	new PeriodicalExecuter(function(pe) {
		var extent = map.getExtent();
		if (extent.left != old_extent.left || extent.bottom != old_extent.bottom || extent.right != old_extent.right || extent.top != old_extent.top) { 
			new Ajax.Request("index.php?go=setMapExtent&left="+extent.left+"&bottom="+extent.bottom+"&right="+extent.right+"&top="+extent.top, {
				onSuccess: function(response) {
					new_extent = response.responseText.evalJSON();
					$('minx').value = new_extent.minx;
					$('miny').value = new_extent.miny;
					$('maxx').value = new_extent.maxx;
					$('maxy').value = new_extent.maxy;
				}
			});
			old_extent = extent;
		}	
	}, 2);
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

function switchlegend(){
	if(document.getElementById('legendTable').style.display == 'none'){
		document.getElementById('legendTable').style.display='';
		ahah('index.php', 'go=changeLegendDisplay&hide=0', new Array(), "");
		document.getElementById('LegendMinMax').src='<?php echo GRAPHICSPATH; ?>maximize.png';
		document.getElementById('LegendMinMax').title="Legende verstecken";
	}
	else{
		document.getElementById('legendTable').style.display='none';
		ahah('index.php', 'go=changeLegendDisplay&hide=1', new Array(), "");
		document.getElementById('LegendMinMax').src='<?php echo GRAPHICSPATH; ?>minimize.png';
		document.getElementById('LegendMinMax').title="Legende zeigen";
	}
}

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
  <table border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td align="left" valign="top" style="border-width:1px; border-right:1px; border-right-color:#CCCCCC; border-right-style:solid;" height="100%">
      <table width="<?php echo $this->map->width; ?>px" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0">
        <tr bgcolor="#FFFFFF" align="center">
          <td colspan="3" height="<?php echo $this->map->height; ?>px">
            <input type="hidden" name="go" value="neu Laden">
            <INPUT id="minx" TYPE="hidden" NAME="minx" VALUE="<?php echo $this->map->extent->minx; ?>">
            <INPUT id="miny" TYPE="hidden" NAME="miny" VALUE="<?php echo $this->map->extent->miny; ?>">
            <INPUT id="maxx" TYPE="hidden" NAME="maxx" VALUE="<?php echo $this->map->extent->maxx; ?>">
            <INPUT id="maxy" TYPE="hidden" NAME="maxy" VALUE="<?php echo $this->map->extent->maxy; ?>">
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
						
						<div id="map"></div>

						</td>
        </tr>
        <tr>
        	<td>
        		<table width="<?php echo $this->map->width; ?>px" border="0" cellpadding="0" cellspacing="1">
        			<tr>
        				<td colspan="3" style="border-style:solid; border-width:1px; border-color:#aaaaaa;">
        					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						        <tr style="background-color: <? echo BG_MENUETOP; ?>;">
						          <td width="140px" height="30">
						          	&nbsp;<span class="fett"><?php echo $strMapScale; ?>&nbsp;1:&nbsp;</span><input type="text" id="scale" name="nScale" size="5" value="<?php echo round($this->map_scaledenom); ?>">
						          </td>
						          <td align="center">
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
						          <td width="210px" align="right">
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
			          			echo '<span class="fett">Gemeinde:&nbsp;</span>'.$this->Lagebezeichung['gemeindename'].' <span class="fett">Gemarkung:</span>&nbsp;'.$this->Lagebezeichung['gemkgname'].' <span class="fett">Flur:</span>&nbsp;'.$this->Lagebezeichung['flur'];
			          		} ?>
			          	</div>
			          </td>
			        </tr>
			        <? } ?>
			        <tr id="showcoords" style="display:none">
			        	<td width="100px">
			          	<span class="fett">&nbsp;<?php echo $strShowCoordinates; ?></span>&nbsp;
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
						          		&nbsp;<a href="index.php?go=Kartenkommentar_Formular&consumetime=<?php echo $this->user->rolle->newtime; ?>&hauptkarte=<?php echo $this->img['hauptkarte']; ?>"><?php echo $strSave; ?></a>&nbsp;|&nbsp;<a href="index.php?go=Kartenkommentar_Waehlen&prevtime=<?php echo $this->user->rolle->newtime; ?>"><?php echo $strChoose ?></a>&nbsp;|&nbsp;<? if(SHOW_MAP_IMAGE == 'true'){ ?><a id="MapImageLink" target="_blank" href="" onmouseover="javascript:showMapImage();"><?php echo $strMapImageURL; ?></a></span><? } ?>&nbsp;|&nbsp;<a href="javascript:resizemap2window();" ><? echo $strMapSize; ?></a>
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
			
			</td>
      <td valign="top">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0">
			    <tr>
			      <td bgcolor="<?php echo BG_DEFAULT ?>" align="left"><?php
			        if ($this->user->rolle->hideLegend) {
			        	$display = 'none';
			          ?><a id="linkLegend" href="javascript:switchlegend()"><img title="Legende zeigen" id="LegendMinMax" src="<?php  echo GRAPHICSPATH; ?>minimize.png" border="0"></a><?php
			        }
			        else {
			        	?><a id="linkLegend" href="javascript:switchlegend()"><img title="Legende verstecken" id="LegendMinMax" src="<?php  echo GRAPHICSPATH; ?>maximize.png" border="0"></a><?php
			        }
			      ?></td>
			    </tr>
				</table>
        <table id="legendTable" style="display:<? echo $display; ?>" cellspacing=0 cellpadding=2 border=0>
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
            <a href="index.php?go=reset_layers"><img src="graphics/layer.png" border="0" alt="Themensteuerung." title="Themensteuerung | Hier klicken, um alle Layer zu deaktivieren" width="20" height="20"></a><br>
          <div id="scrolldiv" onscroll="document.GUI.scrollposition.value = this.scrollTop;" style="width:230; height:<?php echo $legendheight; ?>; overflow:auto; scrollbar-base-color:<?php echo BG_DEFAULT ?>">
						<input type="hidden" name="nurFremdeLayer" value="<? echo $this->formvars['nurFremdeLayer']; ?>">
            <div onclick="document.GUI.legendtouched.value = 1;" id="legend"><? echo $this->legende; ?></div>
          </div>
            </td>
          </tr>
        </table>


        </td>
    </tr>
  </table>
