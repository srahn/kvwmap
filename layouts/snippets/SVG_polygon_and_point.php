<?php
	$randomnumber = rand(0, 1000000);
	$svgfile  = $randomnumber.'SVG_dokumentenformular.svg';
	include(LAYOUTPATH.'snippets/SVG_Utilities.php');
?>
<div id="map">
<!-- ----------------------- formular-variabeln fuer navigation ---------------------- -->
	<INPUT TYPE="HIDDEN" NAME="CMD" VALUE="">
	<INPUT TYPE="HIDDEN" NAME="INPUT_TYPE" VALUE="">
	<INPUT TYPE="HIDDEN" NAME="INPUT_COORD" VALUE="">            
	<input type="hidden" name="imgxy" value="300 300">
	<input type="hidden" name="imgbox" value="-1 -1 -1 -1">
	
<!-- ----------------------- formular-variabeln fuer fachschale ---------------------- -->
	<input type="HIDDEN" name="minx" value="<?php echo $this->map->extent->minx; ?>">
	<input type="HIDDEN" name="miny" value="<?php echo $this->map->extent->miny; ?>">
	<input type="HIDDEN" name="maxx" value="<?php echo $this->map->extent->maxx; ?>">
	<input type="HIDDEN" name="maxy" value="<?php echo $this->map->extent->maxy; ?>">
	<INPUT TYPE="hidden" NAME="pixelsize" VALUE="<?php echo $pixelsize; ?>">
	<input type="hidden" name="loc_x" value="<?php echo $this->formvars['loc_x']; ?>">
	<input type="hidden" name="loc_y" value="<?php echo $this->formvars['loc_y']; ?>">
	<input type="hidden" name="pathlength" value="<?php echo $this->formvars['pathlength']; ?>">
	<input name="newpath" type="hidden" value="<?php echo $this->formvars['newpath']; ?>">
	<input name="pathwkt" type="hidden" value="<?php echo $this->formvars['pathwkt']; ?>">
	<input name="newpathwkt" type="hidden" value="<?php echo $this->formvars['newpathwkt']; ?>">
	<input name="result" type="hidden" value="">
	<input name="firstpoly" type="hidden" value="<?php echo $this->formvars['firstpoly']; ?>">
	<input name="secondpoly" type="hidden" value="<?php echo $this->formvars['secondpoly']; ?>">
	<input name="pathx_second" type="hidden" value="<?php echo $this->formvars['pathx_second']; ?>">
	<input name="pathy_second" type="hidden" value="<?php echo $this->formvars['pathy_second']; ?>">
	<input type="hidden" name="svghelp" id="svghelp">
	<input type="hidden" name="measured_distance" value="<? echo $this->formvars['measured_distance']; ?>">
	<?
	if($this->formvars['last_button'] == '' or $this->formvars['last_doing'] == ''){
		$this->formvars['last_button'] = 'pgon0';
		$this->formvars['last_doing'] = 'draw_polygon';
	}
	?>
	<input name="gps_posx" type="hidden" value="<? echo $this->formvars['gps_posx']; ?>">
	<input name="gps_posy" type="hidden" value="<? echo $this->formvars['gps_posy']; ?>">
	<input name="gps_follow" type="hidden" value="<? echo $this->formvars['gps_follow'] ?>">
	<input name="last_button" type="hidden" value="<? echo $this->formvars['last_button']; ?>">
	<input name="last_doing" type="hidden" value="<? echo $this->formvars['last_doing']; ?>">
	<input name="last_doing2" type="hidden" value="<? echo $this->formvars['last_doing2']; ?>">
	<input name="lastcoordx" type="hidden" value="">
	<input name="lastcoordy" type="hidden" value="">
	<input type="hidden" name="str_pathx" value="<? echo $this->formvars['str_pathx']; ?>">
  <input type="hidden" name="str_pathy" value="<? echo $this->formvars['str_pathy']; ?>">
  <input type="hidden" name="vertices" id="vertices" value="">
	<input type="hidden" name="angle" value="">
  
<?php
#
# zusammenstellen der SVG ###
#
$fpsvg = fopen(IMAGEPATH.$svgfile,w) or die('fail: fopen('.$svgfile.')');
chmod(IMAGEPATH.$svgfile, 0666);
$svg = $SVG_begin;
$svg .= '<script id="pscript" type="text/ecmascript"><![CDATA['; 
$svg .= $scriptdefinitions;
$svg .= $polygonANDpoint;	
$svg .= $SVGvars_navscript;
$svg .= $basicfunctions;
$svg .= $pointfunctions;
$svg .= $polygonfunctions;
$svg .= $vertex_catch_functions;# Punktfangfunktionen
$svg .= $flurstqueryfunctions;
$svg .= $measurefunctions;
if($this->user->rolle->gps){
	$svg .= $gps_functions;
}
$svg .= $SVGvars_coordscript;
$svg .= $SVGvars_tooltipscript;
$svg .= ']]></script>';

$svg .='
	<defs>
'.$SVGvars_defs.'
'.$pointelement.'
  </defs>';
$svg .= $canvaswithall;
$svg .= $navbuttons;
$svg .= '<g id="buttons_FS" cursor="pointer" onmousedown="hide_tooltip()" onmouseout="hide_tooltip()" transform="translate(0 36)">';
$svg .= deletebuttons($strUndo, $strDelete);
$svg .= polygonbuttons($strDrawPolygon, $strCutByPolygon);
$svg .= flurstquerybuttons();
$svg .= vertex_edit_buttons($strCornerPoint);
$svg .= pointbuttons($strSetPosition);
$svg .= measure_buttons($strRuler);
$svg .= '</g>';
$svg .= $SVG_end;

#
# erstellen der SVG
#
fputs($fpsvg, $svg);
fclose($fpsvg);

#
# aufrufen der SVG
# 
# EMBED-Tag in externe Datei Embed.js ausgelagert, da man sonst im IE die SVG erst aktivieren (anklicken) muss (MS-Update vom 11.04.2006)
# Variablen die dann in Embed.js benutzt werden:
echo'
  <input type="hidden" name="srcpath1" value = "'.TEMPPATH_REL.$svgfile.'">
  <input type="hidden" name="breite1" value = "'.($res_x + 1).'">
  <input type="hidden" name="hoehe1" value = "'.($res_y + 1).'">
';
#                  >>> object-tag: wmode="transparent" (hoehere anforderungen beim rendern!) <<<
//echo '<EMBED align="center" SRC="'.TEMPPATH_REL.$svgfile.'" TYPE="image/svg+xml" width="'.($res_x+1).'" height="'.($res_y+1).'" PLUGINSPAGE="http://www.adobe.com/svg/viewer/install/"/>';
# echo '<iframe src="'.TEMPPATH_REL.$svgfile.'" width="'.$res_x.'" height="'.$res_y.'" name="map"></iframe>';
echo '<script src="funktionen/Embed.js" language="JavaScript" type="text/javascript"></script>';
?>
</div>