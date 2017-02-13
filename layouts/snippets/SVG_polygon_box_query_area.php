
<?
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
	<input type="hidden" name="oid" value="<?php echo $this->formvars['oid']; ?>">
	<input type="HIDDEN" name="minx" value="<?php echo $this->map->extent->minx; ?>">
	<input type="HIDDEN" name="miny" value="<?php echo $this->map->extent->miny; ?>">
	<input type="HIDDEN" name="maxx" value="<?php echo $this->map->extent->maxx; ?>">
	<input type="HIDDEN" name="maxy" value="<?php echo $this->map->extent->maxy; ?>">
	<INPUT TYPE="hidden" NAME="pixelsize" VALUE="<?php echo $pixelsize; ?>">
	<input type="hidden" name="pathlength" value="<?php echo $this->formvars['pathlength']; ?>">
	
	<input name="newpath" type="hidden" value="<?php echo $this->formvars['newpath']; ?>">
	<input name="pathwkt" type="hidden" value="<?php echo $this->formvars['pathwkt']; ?>">
	<input name="newpathwkt" type="hidden" value="<?php echo $this->formvars['newpathwkt']; ?>">
	<input name="result" type="hidden" value="">
	<input name="area" type="hidden" value="">
	<input name="firstpoly" type="hidden" value="<?php echo $this->formvars['firstpoly']; ?>" onchange="document.getElementById('abfrageart_poly').checked = true;">
	<input name="secondpoly" type="hidden" value="<?php echo $this->formvars['secondpoly']; ?>">
	<input name="pathx_second" type="hidden" value="<?php echo $this->formvars['pathx_second']; ?>">
	<input name="pathy_second" type="hidden" value="<?php echo $this->formvars['pathy_second']; ?>">
	<input type="hidden" name="svghelp" id="svghelp">
	<input type="hidden" name="bufferwidth" value="<? if($this->formvars['bufferwidth'])echo $this->formvars['bufferwidth']; else echo '10'; ?>">
	<input type="hidden" name="measured_distance" value="<? echo $this->formvars['measured_distance']; ?>">
	<?
	if($this->formvars['last_button'] == '' or $this->formvars['last_doing'] == ''){
		$this->formvars['last_button'] = 'pgon0';
		$this->formvars['last_doing'] = 'draw_polygon';
	}
	?>
	<input name="last_button" type="hidden" value="<? echo $this->formvars['last_button']; ?>">
	<input name="last_doing" type="hidden" value="<? echo $this->formvars['last_doing']; ?>">
	<input name="last_doing2" type="hidden" value="<? echo $this->formvars['last_doing2']; ?>">
	<input name="lastcoordx" type="hidden" value="">
	<input name="lastcoordy" type="hidden" value="">	

<?php
#
# PHP-variabeln der SVG
#
	$randomnumber = rand(0, 1000000);
	$svgfile  = $randomnumber.'SVG_dokumentenformular.svg';

#
# zusammenstellen der SVG
#
$fpsvg = fopen(IMAGEPATH.$svgfile,w) or die('fail: fopen('.$svgfile.')');
chmod(IMAGEPATH.$svgfile, 0666);

$svg = $SVG_begin;
$svg .= '<script id="pscript" type="text/ecmascript"><![CDATA[';
$svg .= $scriptdefinitions;
$svg .= $basicfunctions;				# Basisfunktionen
$svg .= $SVGvars_navscript;			# Funktionen zur Navigation
$svg .= $polygonfunctions;			# Funktionen zum Zeichnen eines Polygons
$svg .= $boxfunctions;
$svg .= $flurstqueryfunctions;
$svg .= $bufferfunctions;				# Funktionen zum Erzeugen eines Puffers
$svg .= $SVGvars_coordscript;
$svg .= $SVGvars_tooltipscript;
$svg .= ']]></script>';

$svg .='
	<defs>
'.$SVGvars_defs.'
  </defs>';
$svg .= $canvaswithall;
$svg .= '<g id="buttons" cursor="pointer" transform="scale(1.1)">';
$svg .= $navbuttons;
$svg .= '<g id="buttons_FS" cursor="pointer" onmousedown="hide_tooltip()" onmouseout="hide_tooltip()" transform="translate(0 26)">';
$svg .= polygonbuttons($strUndo, $strDeletePolygon, $strDrawPolygon, $strCutByPolygon);
$svg .= boxbuttons();
$svg .= flurstquerybuttons();
$svg .= bufferbuttons($strBuffer, $strBufferedLine, $strParallelPolygon);
$svg .= vertex_edit_buttons($strCornerPoint);
$svg .= '</g>';
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
  <input type="hidden" name="breite1" value = "'.$res_x.'">
  <input type="hidden" name="hoehe1" value = "'.$res_y.'">
';
#                  >>> object-tag: wmode="transparent" (hoehere anforderungen beim rendern!) <<<
//echo '<EMBED align="center" SRC="'.TEMPPATH_REL.$svgfile.'" TYPE="image/svg+xml" width="'.$res_x.'" height="'.$res_y.'" PLUGINSPAGE="http://www.adobe.com/svg/viewer/install/"/>';
# echo '<iframe src="'.TEMPPATH_REL.$svgfile.'" width="'.$res_x.'" height="'.$res_y.'" name="map"></iframe>';
echo '<script src="funktionen/Embed.js" language="JavaScript" type="text/javascript"></script>';
?>
</div>