
  <SCRIPT type="text/ecmascript"><!--

  function send_ews(gt_x,gt_y) {
      document.GUI.gt_x.value    = gt_x;
      document.GUI.gt_y.value    = gt_y;
  }

  //--></SCRIPT>
  
      <input type="hidden" name="gt_x" value="">
      <input type="hidden" name="gt_y" value="">

<?php
#
# PHP-variabeln der SVG			# Input-Werte:			#
#
	$svgfile	= 'SVG_geothermieeingabe.svg';
	include(LAYOUTPATH.'snippets/SVGvars_navbuttons.php'); 			# zuweisen von: $SVGvars_navbuttons
	include(LAYOUTPATH.'snippets/SVGvars_defs.php'); 						# zuweisen von: $SVGvars_defs
	include(LAYOUTPATH.'snippets/SVGvars_coordscript.php'); 		# zuweisen von: $SVGvars_coordscript
	include(LAYOUTPATH.'snippets/SVGvars_tooltipscript.php');		# zuweisen von: $SVGvars_tooltipscript 
	include(LAYOUTPATH.'snippets/SVGvars_tooltipblank.php');		# zuweisen von: $SVGvars_tooltipblank 
	$gw_ri	= 70;						# GW-Fliessrichtung		#
	$gw_ir	= 360-$gw_ri;
	$gw_gs	= 0.9;					# GW-Fliessgeschwindigkeit	#
	$bg_pic = $this->img['hauptkarte'];	# '1100510352151625.PNG'; 	# 
	$res_x 	= $this->map->width;
	$res_y 	= $this->map->height;
	$res_xm   = $this->map->width/2;
	$res_ym   = $this->map->height/2;
	$anzahl	= 3;						# Anzahl der Bohrungen		#
	$usefunc = ""; $ecmafunc = "";
	$watt 	= 15;						# Zu erzielende Leistung	#
	$dx       = $this->map->extent->maxx-$this->map->extent->minx;
	$dy       = $this->map->extent->maxy-$this->map->extent->miny;
	$scale    = ($dx/$res_x+$dy/$res_y)/2;
	$rx 	= 2*$watt*(1+$gw_gs);
	$ry 	= 2*$watt;
	$bohrung= 4*$watt*(1-($ry/$rx));

#
# script-vorbereitung pro anzahl der bohrloecher
#
for ($i = 1; $i < $anzahl+1; $i++)
{	$usex = $res_xm-10*$i; 	$usey = $res_ym-10*$i; 	
	$usefunc 	= $usefunc.'
		<use id="use'.$i.'" x="'.$usex.'" y="'.$usey.'" xlink:href="#ellipse" cursor="move" onmousedown="StartDrag'.$i.'(evt)" onmousemove="DoDrag'.$i.'(evt)" onmouseup="EndDrag(evt)" onmouseout="EndDrag(evt)"/>';
	$output 	= $output.'
	var leer = " , "; var br = "\n   ";
	var obj'.$i.' = document.getElementById("use'.$i.'");
	var usex0 = obj'.$i.'.getAttribute("x");
	var usey0 = obj'.$i.'.getAttribute("y");
	var usex = Math.round((usex0*scale + minx)*100)/100;
	var usey = Math.round((maxy - usey0*scale)*100)/100;
	usexy = usexy+usex+leer+usey+br;
	';
	$ecmafunc 	= $ecmafunc.'
function StartDrag'.$i.'(evt)
{
dragging = true;
var obj = document.getElementById("use'.$i.'");
dragdx 	= parseFloat(obj.getAttribute("x")) - evt.clientX;
dragdy 	= parseFloat(obj.getAttribute("y")) - evt.clientY;
}

function DoDrag'.$i.'(evt)
{
if (!dragging) return;
var x 	= evt.clientX + dragdx;
var y 	= evt.clientY + dragdy;
var obj = document.getElementById("use'.$i.'");
obj.setAttribute("x", x);
obj.setAttribute("y", y);
//
// Koordinatenausgabe in der Statuszeile (while dragging!):
//
	var wx = Math.round((x*scale + minx)*100)/100;
	var wy = Math.round((maxy - y*scale)*100)/100;
	window.status = "R:" + wx + " / H:" + wy;
//window.status = "x:" + x + " / y:" + y + " [dragdx:" + dragdx + " / dragdy:" + dragdy + "]";
}

';
}

#
# erstellen der SVG
#
$svg = fopen(IMAGEPATH.$svgfile, w) or die("fail: fopen(".$svgfile.")");
chmod(IMAGEPATH.$svgfile, 0666);
fputs($svg, '<?xml version="1.0"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
  "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg width="'.$res_x.'" height="'.$res_y.'" zoomAndPan="disable"
  xmlns="http://www.w3.org/2000/svg" version="1.1"
  xmlns:xlink="http://www.w3.org/1999/xlink"> 
<script type="text/ecmascript">
<![CDATA[
var dragging = false;
var dragdx = 0, dragdy = 0;
var usexy ="   ";
var minx 	= '.$this->user->rolle->oGeorefExt->minx.';
var maxx 	= '.$this->user->rolle->oGeorefExt->maxx.';
var miny 	= '.$this->user->rolle->oGeorefExt->miny.';
var maxy 	= '.$this->user->rolle->oGeorefExt->maxy.';
var dx 		= maxx-minx;
var dy 		= maxy-miny;
var resx 	= '.$res_x.';
var resy 	= '.$res_y.';
var f 		= (dx/resx+dy/resy)/2;
var scale = '.$scale.'; 
function EndDrag(evt)
{
dragging = false;
}

'.$ecmafunc.'

function punktinfo()
{
'.$output.'
	alert('.chr(39).'Die Koordinaten von '.$anzahl.' Bohrpunkt(en): \n\n'.chr(39).'+usexy+'.chr(39).'\nwurde(n) festgelegt!'.chr(39).');
	usexy 	= "   ";
}

// ---------------------koordinatenausgabe in statuszeile--------------------------
'.$SVGvars_coordscript.'

// -------------------------tooltip-ausgabe fuer buttons------------------------------
'.$SVGvars_tooltipscript.'

]]></script>
	<defs>
'.$SVGvars_defs.'
		<g id="ellipse" transform="rotate('.$gw_ri.' 0 0) translate(28.421052631579 0)">
			<g transform="rotate('.$gw_ir.' 0 0)">
			<g transform="rotate('.$gw_ri.' 0 0)">
				<ellipse id="entzug" cx="0" cy="0" rx="'.$rx.'" ry="'.$ry.'" style="fill-opacity:0.5;fill:rgb(192,192,255);stroke:rgb(0,0,128);stroke-width:1.5"/>
				<circle id="bohrung" x="0" y="0" r="2" transform="translate(-'.$bohrung.' 0)" style="fill:rgb(0,0,128)"/>
			</g>
			<text x="0" y="4" style="fill:white; text-anchor:middle">
				<animate attributeName="opacity" begin="0s" dur="2s" fill="freeze" values="1;0.1;1;1" keyTimes="0; 0.25; 0.5; 1" repeatCount="indefinite"/>
			'.$watt.'W/m</text>
			<circle id="klickflaeche" r="'.$ry.'" opacity="0"/>
		</g></g>
	</defs>
  <rect id="background" style="fill:white" width="100%" height="100%"/>
		<text x="'.$res_xm.'" y="'.$res_ym.'" style="opacity:1;text-anchor:middle">Kartenausschnitt wird geladen...
			<animate attributeName="opacity" begin="0s" dur="4s" fill="freeze" keyTimes="0; 0.25; 0.5; 0.75; 1" repeatCount="indefinite" values="1;1;0;1;1"/>
		</text>
	<image xlink:href="'.$bg_pic.'" height="100%" width="100%" y="0" x="0" id="Image"/>
	<rect id="canvas" cursor="crosshair" onmousemove="hide_tooltip();" width="100%" height="100%" opacity="0"/>
	<g id="usegroup">
'.$usefunc.'
	</g>
	<g id="buttons" cursor="pointer" onmouseout="hide_tooltip()" onmousedown="hide_tooltip()">

		<g id="info" onclick="punktinfo()" transform="translate(0 0)">
      <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
      <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(222,222,222);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
      	<set attributeName="filter" begin="info.mousedown" dur="0s" fill="freeze" to="none"/>
				<set attributeName="filter" begin="info.mouseup;info.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
			</rect>
			<g transform="scale(0.7) translate(-5 0)">
				<text x="23" y="15" font-weight="fett" text-anchor="middle">
				info</text>
				<text x="23" y="30" font-weight="fett" text-anchor="middle">
				x/y</text>
			</g>
			<rect onmouseover="show_tooltip(\'Koordinatenausgabe\',evt.clientX,evt.clientY)" x="0" y="0" width="25" height="25" opacity="0"/>
		</g>

	</g>
'.$SVGvars_tooltipblank.'
</svg>');
fclose($svg);

#
# aufrufen der SVG
#
 echo '<EMBED align="center" SRC="'.TEMPPATH_REL.$svgfile.'" TYPE="image/svg+xml" width="'.($res_x+1).'" height="'.($res_y+1).'" PLUGINSPAGE="http://www.adobe.com/svg/viewer/install/"/>';
?>