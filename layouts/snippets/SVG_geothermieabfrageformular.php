
  <SCRIPT type="text/ecmascript"><!--

  function sendlocation(loc_x,loc_y) {
      document.GUI.loc_x.value    = loc_x;
      document.GUI.loc_y.value    = loc_y;
  }

  //--></SCRIPT>
  
      <input type="hidden" name="loc_x" value="">
      <input type="hidden" name="loc_y" value="">
<!-- ----------------------- formular-variabeln fuer navigation ---------------------- -->
            <INPUT TYPE="HIDDEN" NAME="CMD" VALUE="">
            <INPUT TYPE="HIDDEN" NAME="INPUT_TYPE" VALUE="">
            <INPUT TYPE="HIDDEN" NAME="INPUT_COORD" VALUE="">            
            <input type="hidden" name="imgxy" value="300 300">
            <input type="hidden" name="imgbox" value="-1 -1 -1 -1">   
<?php
#
# PHP-variabeln der SVG
#
	$svgfile  = 'SVG_geothermieabfrage.svg';
	include(LAYOUTPATH.'snippets/SVGvars_navbuttons.php'); 			# zuweisen von: $SVGvars_navbuttons
	include(LAYOUTPATH.'snippets/SVGvars_defs.php'); 						# zuweisen von: $SVGvars_defs
	include(LAYOUTPATH.'snippets/SVGvars_coordscript.php'); 		# zuweisen von: $SVGvars_coordscript
	$bg_pic   = $this->img['hauptkarte'];
	$res_x    = $this->map->width;
	$res_y    = $this->map->height;
	$res_xm   = $this->map->width/2;
	$res_ym   = $this->map->height/2;
	$dx       = $this->map->extent->maxx-$this->map->extent->minx;
	$dy       = $this->map->extent->maxy-$this->map->extent->miny;
	$scale    = ($dx/$res_x+$dy/$res_y)/2;

#
# zusammenstellen der SVG 
#
$fpsvg = fopen(IMAGEPATH.$svgfile,w) or die('fail: fopen('.$svgfile.')');
chmod(IMAGEPATH.$svgfile, 0666);
$svg='<?xml version="1.0"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
  "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg width="'.$res_x.'" height="'.$res_y.'" zoomAndPan="disable" onmousemove="coords(evt)"
  xmlns="http://www.w3.org/2000/svg" version="1.1"
  xmlns:xlink="http://www.w3.org/1999/xlink"> 
<script id="pscript" type="text/ecmascript"><![CDATA[ 

var dragging  = false; 
var minx  = '.$this->user->rolle->oGeorefExt->minx.';
var maxx  = '.$this->user->rolle->oGeorefExt->maxx.';
var miny  = '.$this->user->rolle->oGeorefExt->miny.';
var maxy  = '.$this->user->rolle->oGeorefExt->maxy.';
var dx    = '.$dx.';
var dy    = '.$dy.';
var resx  = '.$res_x.';
var resy  = '.$res_y.';
var scale = '.$scale.';
var gt_x	= 0;
var gt_y	= 0;

function choose(evt) {
  // neuen punkt abgreifen
  clientx = evt.clientX;
  clienty = resy - evt.clientY;
  
	// polygon um punktepfad erweitern
	var obj = document.getElementById("flurstk");
	obj.setAttribute("x", clientx);
	obj.setAttribute("y", clienty);
  
	// auswahl an formular uebergeben
  loc_x = evt.clientX*scale + minx;
  loc_y = maxy - evt.clientY*scale;
  top.sendlocation(loc_x,loc_y);
}

// ---------------------koordinatenausgabe in statuszeile--------------------------
'.$SVGvars_coordscript.'

]]></script>

  <defs>
'.$SVGvars_defs.'
		<g id="auswahl">
			<circle cx="0" cy="0" r="10" style="fill:rgb(0,0,255);stroke:rgb(0,0,255);stroke-width:2;fill-opacity:0.2">
				<animate attributeName="r" begin="0s" dur="3s" fill="freeze"
					values="10;10;0.1;10" keyTimes="0; 0.333333; 0.666667; 1"
					repeatCount="indefinite">
				</animate>
				<animate attributeName="stroke" begin="0s" dur="3s" fill="freeze"
					values="rgb(0,0,255);rgb(0,0,255);rgb(255,0,0);rgb(0,0,255)"
					keyTimes="0; 0.333333; 0.666667; 1" repeatCount="indefinite">
				</animate>
			</circle>
		</g>
  </defs>
	<g id="moveGroup" transform="translate(0 0)">
		<text x="'.$res_xm.'" y="'.$res_ym.'" style="opacity:1;text-anchor:middle">Kartenausschnitt wird geladen...
			<animate attributeName="opacity" begin="0s" dur="4s" fill="freeze" keyTimes="0; 0.25; 0.5; 0.75; 1" repeatCount="indefinite" values="1;1;0;1;1"/>
		</text>
	  <image xlink:href="'.$bg_pic.'" height="100%" width="100%" y="0" x="0"/>
	  <g id="cartesian" transform="translate(0,'.$res_y.') scale(1,-1)">
	  	<use id="flurstk" xlink:href="#auswahl" x="-10" y="-10"/>
	  </g>
  </g>
  <rect id="canvas" cursor="crosshair" onmousedown="choose(evt)" width="100%" height="100%" opacity="0"/>
<!--
  <a xlink:href="">
    <g id="buttons_NAV" cursor="pointer" onmousedown="focus_NAV()">
'.$SVGvars_navbuttons.'
		</g>
	</a>
  <rect id="canvas" cursor="crosshair" onmousedown="choose(evt)" width="100%" height="100%" opacity="0"/>
-->
</svg>';

#
# erstellen der SVG
#
fputs($fpsvg, $svg);
fclose($fpsvg);

#
# aufrufen der SVG
#
 echo '<EMBED align="center" SRC="'.TEMPPATH_REL.$svgfile.'" TYPE="image/svg+xml" width="'.($res_x+1).'" height="'.($res_y+1).'" PLUGINSPAGE="http://www.adobe.com/svg/viewer/install/"/>';
?>