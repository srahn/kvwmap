
  <SCRIPT id="phtml" type="text/ecmascript"><!--

  function sendpath(pathx,pathy) {
      document.GUI.pathx.value    = pathx;
      document.GUI.pathy.value    = pathy;
//      document.GUI.abfrage_art.value    = "checked";
  }
  --></SCRIPT>
  
      <input type="hidden" name="pathx" value="<?php echo $this->formvars['pathx']; ?>">
      <input type="hidden" name="pathy" value="<?php echo $this->formvars['pathy']; ?>">

<?php
#
# PHP-variabeln der SVG
#
  $svgfile  = 'SVG_metadatenformular.svg';
  include(LAYOUTPATH.'snippets/SVGvars_navbuttons.php');  		# zuweisen von: $SVGvars_navbuttons
  include(LAYOUTPATH.'snippets/SVGvars_defs.php');      			# zuweisen von: $SVGvars_defs
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
# zusammenstellen der SVG    onload="...;draw_pgon_off()"
#
$fpsvg = fopen(IMAGEPATH.$svgfile,w) or die('fail: fopen('.$svgfile.')');
chmod(IMAGEPATH.$svgfile, 0666);
$svg='<?xml version="1.0"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
  "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg width="'.$res_x.'" height="'.$res_y.'" zoomAndPan="disable" onload="redraw()" onmousemove="coords(evt)"
  xmlns="http://www.w3.org/2000/svg" version="1.1"
  xmlns:xlink="http://www.w3.org/1999/xlink"> 
<script id="pscript" type="text/ecmascript"><![CDATA[ 

var minx  = '.$this->user->rolle->oGeorefExt->minx.';
var maxx  = '.$this->user->rolle->oGeorefExt->maxx.';
var miny  = '.$this->user->rolle->oGeorefExt->miny.';
var maxy  = '.$this->user->rolle->oGeorefExt->maxy.';
var dx    = '.$dx.';
var dy    = '.$dy.';
var resx  = '.$res_x.';
var resy  = '.$res_y.';
var scale = '.$scale.';
var pathx = new Array();
var pathy = new Array();
var dragging  = false; 
var polygon_draw=false;
var	marked	= "yellow";

function task(evt) {
	if (polygon_draw){
		addpoint(evt);
	}
	else {
		restart();
		startpoint(evt);
	}
}

function draw_pgon_on() {
	var obj = document.getElementById("box0");
	obj.setAttributeNS(null,"fill","none");
	var obj = document.getElementById("pgon0");
	obj.setAttributeNS(null,"fill",marked);
	var obj = document.getElementById("pgon1");
	obj.setAttributeNS(null,"fill",marked);
	var obj = document.getElementById("pgon2");
	obj.setAttributeNS(null,"fill",marked);
	polygon_draw=true;
}

function draw_pgon_off() {
	var obj = document.getElementById("box0");
	obj.setAttributeNS(null,"fill",marked);
	var obj = document.getElementById("pgon0");
	obj.setAttributeNS(null,"fill","none");
	var obj = document.getElementById("pgon1");
	obj.setAttributeNS(null,"fill","none");
	var obj = document.getElementById("pgon2");
	obj.setAttributeNS(null,"fill","none");
	polygon_draw=false;
}

// ----------------------------box aufziehen---------------------------------
function startpoint(evt) {
	dragging  = true;
  var alle = pathx.length;
  for(var i = 0; i < alle; ++i)
   {
    pathx.pop();
    pathy.pop();
   }
  // neuen punkt abgreifen
  clientx = evt.clientX;
  clienty = resy - evt.clientY;
  pathx.push(clientx);
  pathy.push(clienty);
  redraw();
}

function movepoint(evt) {
if (!dragging) return;
  // neuen punkt abgreifen
  clientx = evt.clientX;
  clienty = resy - evt.clientY;
	pathx[1]	= pathx[0];
	pathy[1]	= clienty;
	pathx[2]	= clientx;
	pathy[2]	= clienty;
	pathx[3]	= clientx;
	pathy[3]	= pathy[0];
  redraw();
}

function endpoint(evt) {
	dragging  = false;
}

// ----------------------------polygon setzen---------------------------------
function addpoint(evt) {
  // neuen punkt abgreifen
  clientx = evt.clientX;
  clienty = resy - evt.clientY;
  pathx.push(clientx);
  pathy.push(clienty);
  redraw();
}

// ----------------------------neuzeichnen---------------------------------
function redraw() 
{
  // punktepfad erstellen
  pointpath();
  
  // polygon um punktepfad erweitern
  var obj = document.getElementById("polygon");
  obj.setAttribute("points", path);

  // hiddenformvars aktualisieren
  top.sendpath(pathx,pathy);
}

function pointpath() 
{
  path = "";
  for(var i = 0; i < pathx.length; ++i)
   {
    path = path+" "+pathx[i]+","+pathy[i];
   }
}

function deletelast(evt) {
	//polygon_draw=true;
	draw_pgon_on()
	
	pathx.pop();
	pathy.pop();
	redraw();
}

function restart()
{
	//polygon_draw=true;
	//draw_pgon_on()
	
	var alle = pathx.length;
	for(var i = 0; i < alle; ++i)
	 {
	  pathx.pop();
	  pathy.pop();
	 }
redraw();
}

// ---------------------koordinatenausgabe in statuszeile--------------------------
'.$SVGvars_coordscript.'

]]></script>

  <defs>
'.$SVGvars_defs.'
  </defs>
		<text x="'.$res_xm.'" y="'.$res_ym.'" style="opacity:1;text-anchor:middle">Kartenausschnitt wird geladen...
			<animate attributeName="opacity" begin="0s" dur="4s" fill="freeze" keyTimes="0; 0.25; 0.5; 0.75; 1" repeatCount="indefinite" values="1;1;0;1;1"/>
		</text>
  <image xlink:href="'.$bg_pic.'" height="100%" width="100%" y="0" x="0"/>
  <g id="cartesian" transform="translate(0,'.$res_y.') scale(1,-1)">
    <polygon points="" id="polygon" style="stroke-dasharray:8,2;fill-opacity:0.25;fill:yellow;stroke:orange;stroke-width:2"/>
  </g>
  <rect id="canvas" cursor="crosshair" onmousedown="task(evt)" onmousemove="movepoint(evt)" onmouseup="endpoint(evt)" width="100%" height="100%" opacity="0"/>
  <a xlink:href="">
<!--
    <g id="buttons" cursor="pointer">
    
      <g id="undo" onmousedown="deletelast(evt)" transform="translate(0 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
	      <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(222,222,222);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
	      	<set attributeName="filter" begin="undo.mousedown" dur="0s" fill="freeze" to="none"/>
					<set attributeName="filter" begin="undo.mouseup;undo.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
				</rect>
        <g transform="scale(0.7) translate(-5 0)">
          <text x="9" y="15" font-weight="fett"> 
          undo</text>
          <g id="pfeil_links" transform="matrix(-1 0 0 1 100 -25) scale(0.5) rotate(180) translate(-345 -152)">
            <polygon points="178.579,57.7353 164.258,51.2544 178.96,44.515 174.48,51.1628" style="fill:rgb(0,0,0);stroke-width:2"/>
            <line x1="215" y1="51" x2="170" y2="51" style="fill:none;stroke:rgb(0,0,0);stroke-width:4"/>
          </g>
        </g>
        <rect id="pgon2" x="0" y="0" rx="1" ry="1" width="25" height="25" fill="none" opacity="0.2"/>
      </g>

      <g id="new" onmousedown="restart();draw_pgon_on()" transform="translate(26 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
	      <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(222,222,222);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
	      	<set attributeName="filter" begin="new.mousedown" dur="0s" fill="freeze" to="none"/>
					<set attributeName="filter" begin="new.mouseup;new.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
				</rect>
        <g transform="scale(0.7) translate(-5 0)">
				<text x="23" y="15" style="text-anchor:middle;fill:black;font-size:12;font-family:Arial;font-weight:bold">
          new
        </text>
				<text x="23" y="35" style="text-anchor:middle;fill:black;font-size:20;font-family:Arial;font-weight:bold">
          *
        </text>
        </g>
        <rect id="pgon1" x="0" y="0" rx="1" ry="1" width="25" height="25" fill="none" opacity="0.2"/>
      </g>

    </g>

		<g id="pgon" onmousedown="draw_pgon_on()" transform="translate(52 0)">
      <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
      <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(222,222,222);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
      	<set attributeName="filter" begin="pgon.mousedown" dur="0s" fill="freeze" to="none"/>
				<set attributeName="filter" begin="pgon.mouseup;pgon.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
			</rect>
			<g transform="scale(0.7) translate(-5 0)">
				<text x="23" y="15" font-weight="fett" text-anchor="middle">
				pgon</text>
				<g transform="matrix(-1 0 0 1 118 0) scale(0.5)">
					<rect x="170" y="45" rx="5" ry="5" width="40" height="14" style="fill:none;stroke:rgb(0,0,0);stroke-width:4"/>
				</g>
			</g>
			<rect id="pgon0" x="0" y="0" width="25" height="25" fill="none" opacity="0.2"/>
		</g>

		<g id="box" onmousedown="draw_pgon_off();restart()" transform="translate(83 0)">
      <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
      <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(222,222,222);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
      	<set attributeName="filter" begin="box.mousedown" dur="0s" fill="freeze" to="none"/>
				<set attributeName="filter" begin="box.mouseup;box.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
			</rect>
			<g transform="scale(0.7) translate(-5 0)">
				<text x="23" y="15" style="text-anchor:middle;fill:black;font-size:12;font-family:Arial;font-weight:bold">
				box</text>
				<g transform="matrix(-1 0 0 1 118 0) scale(0.5)">
					<rect x="170" y="45" width="40" height="14" style="fill:none;stroke:rgb(0,0,0);stroke-width:4"/>
				</g>
			</g>
			<rect id="box0" x="0" y="0" rx="1" ry="1" width="25" height="25" fill="none" opacity="0.2"/>
		</g>
-->

  </a>
</svg>';
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