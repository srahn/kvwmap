<div id="map">
<?php
 
#
# PHP-Variabeln der SVG
#
	$randomnumber = rand(0, 1000000);
  $svgfile  = $randomnumber.'SVG.svg';
	$bg_pic   = $this->img['hauptkarte'];

	global $last_x;$last_x = 0;
	global $events;$events = true;	
	
	include(LAYOUTPATH.'snippets/SVGvars_defs.php'); 					# zuweisen von: $SVGvars_defs
	include(LAYOUTPATH.'snippets/SVGvars_navbuttons.php'); 		# zuweisen von: $SVGvars_navbuttons
	include(LAYOUTPATH.'snippets/SVGvars_navscript.php'); 		# zuweisen von: $SVGvars_navscript
	include(LAYOUTPATH.'snippets/SVGvars_coordscript.php'); 	# zuweisen von: $SVGvars_coordscript
	include(LAYOUTPATH.'snippets/SVGvars_tooltipscript.php');	# zuweisen von: $SVGvars_tooltipscript 
	include(LAYOUTPATH.'snippets/SVGvars_tooltipblank.php');	# zuweisen von: $SVGvars_tooltipblank  
	$res_x    = $this->map->width;
	$res_y    = $this->map->height;
	$res_xm   = $this->map->width/2;
	$res_ym   = $this->map->height/2;
	$dx       = $this->map->extent->maxx-$this->map->extent->minx;
	$dy       = $this->map->extent->maxy-$this->map->extent->miny;
	$scale    = ($dx/$res_x+$dy/$res_y)/2;
			
	$worldprintwidth = $this->Document->activeframe[0]["mapwidth"] * $this->formvars['printscale']/$this->meter_pro_einheit * 0.00035277;
	$worldprintheight = $this->Document->activeframe[0]["mapheight"] * $this->formvars['printscale']/$this->meter_pro_einheit * 0.00035277;
	$printwidth = round($worldprintwidth/$scale);
	$printheight = round($worldprintheight/$scale);
	$halfprintheight = $printheight/2;
	$halfprintwidth = $printwidth/2;
		
#	echo('formvars[pathx]: '.$this->formvars["pathx"].', formvars[pathy]: '.$this->formvars["pathy"]);
#	echo('formvars[loc_x]: '.$this->formvars["loc_x"].', formvars[loc_y]: '.$this->formvars["loc_y"]);

#
# Positionsanzeigetext ausserhalb der Anzeigeflaeche bei Start
#
	$pixel_y=($this->formvars['center_y']-$this->map->extent->miny)/$scale;
	$pixel_x=($this->formvars['center_x']-$this->map->extent->minx)/$scale;
	$refpoint_y=($this->formvars['refpoint_y']-$this->map->extent->miny)/$scale;
	$refpoint_x=($this->formvars['refpoint_x']-$this->map->extent->minx)/$scale;
	$angle = $this->formvars['angle'];
	$pos_x = round($pixel_x-$printwidth/2);
	$pos_y = round($pixel_y-$printheight/2);
?>

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
	<input type="hidden" name="worldprintwidth" value="<? echo $worldprintwidth ?>">
	<input type="hidden" name="worldprintheight" value="<? echo $worldprintheight ?>">
	<input type="hidden" name="center_x" value="<?php echo $this->formvars['center_x']; ?>">
	<input type="hidden" name="center_y" value="<?php echo $this->formvars['center_y']; ?>">
	<input type="hidden" name="refpoint_x" value="<?php echo $this->formvars['refpoint_x']; ?>">
	<input type="hidden" name="refpoint_y" value="<?php echo $this->formvars['refpoint_y']; ?>">
	<input type="hidden" name="pathx" value="<?php echo $this->formvars['pathx']; ?>">
	<input type="hidden" name="pathy" value="<?php echo $this->formvars['pathy']; ?>">
	<input type="hidden" name="pathlength" value="<?php echo $this->formvars['pathlength']; ?>">
	<input type="hidden" name="scale" value="<?php echo $this->user->rolle->pixsize; ?>">
	<input type="hidden" name="layer_options_open" value="">

<SCRIPT type="text/ecmascript"><!--

	var scale = <? echo $scale; ?>;
	var minx = <? echo $this->map->extent->minx; ?>;
	var miny = <? echo $this->map->extent->miny; ?>;
  
  function sendcenterlocation(center_x,center_y) {
    document.GUI.center_x.value = (center_x*scale)+minx;
    document.GUI.center_y.value = (center_y*scale)+miny;
  }
  
  function sendworldprintvalues(worldprintwidth,worldprintheight){
  	document.GUI.worldprintwidth.value = worldprintwidth;
  	document.GUI.worldprintheight.value = worldprintheight;
  }
	
	function sendrefpointlocation(center_x,center_y) {
    document.GUI.refpoint_x.value = (center_x*scale)+minx;
    document.GUI.refpoint_y.value = (center_y*scale)+miny;
  }

  function sendBWpath(pathx,pathy) {
      document.GUI.pathlength.value   = pathx.length;
      document.GUI.pathx.value    = pathx;
      document.GUI.pathy.value    = pathy;
  }

  function Full_Extent()   {
      document.GUI.CMD.value  = "Full_Extent";
      document.GUI.submit();
  }
  function sendpath(cmd,navX,navY)   {
    // navX[0] enthält den Rechtswert des ersten gesetzte Punktes im Bild in Pixeln
    // von links nach rechts gerechnet
    // navY[0] enthält den Hochwert des ersten Punktes im Bild in Pixeln
    // allerdings von oben nach untern gerechnet
    // [2] jeweils den anderen Punkt wenn ein Rechteck übergeben wurde
    switch(cmd) {
     case "zoomin_point":
      document.GUI.INPUT_COORD.value  = navX[0]+","+navY[0];
      document.GUI.CMD.value          = "zoomin";
      document.GUI.submit();
     break;
     case "zoomout":
      document.GUI.INPUT_COORD.value  = navX[0]+","+navY[0];
      document.GUI.CMD.value          = cmd;
      document.GUI.submit();
     break;
     case "zoomin_box":
      document.GUI.INPUT_COORD.value  = navX[0]+","+navY[0]+";"+navX[2]+","+navY[2];
      document.GUI.CMD.value          = "zoomin";
      document.GUI.submit();
     break;
     case "recentre":
      document.GUI.INPUT_COORD.value  = navX[0]+","+navY[0];
      document.GUI.CMD.value = cmd;  		
      document.GUI.submit();
     break;
     default:
      alert("Keine Bearbeitung moeglich! \nUebergebene Daten: "+cmd+", "+navX[0]+","+navY[0]);
     break;
    }
  }

--></SCRIPT>
  
<?php
#
# zusammenstellen der SVG ###
#
$fpsvg = fopen(IMAGEPATH.$svgfile,w) or die('fail: fopen('.$svgfile.')');
chmod(IMAGEPATH.$svgfile, 0666);
$svg='<?xml version="1.0"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
  "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg width="'.$res_x.'" height="'.$res_y.'" zoomAndPan="disable" onload="startup()" 
  xmlns="http://www.w3.org/2000/svg" version="1.1"
  xmlns:xlink="http://www.w3.org/1999/xlink"
  xmlns:ev="http://www.w3.org/2001/xml-events">
	<script id="pscript" type="text/ecmascript"><![CDATA[ 
	
	var path  = "";
	';

	$svg.='var pathx = new Array('.$this->formvars["pathx"].');
	var pathy = new Array('.$this->formvars["pathy"].');

	var minx  = '.$this->user->rolle->oGeorefExt->minx.';
	var maxx  = '.$this->user->rolle->oGeorefExt->maxx.';
	var miny  = '.$this->user->rolle->oGeorefExt->miny.';
	var maxy  = '.$this->user->rolle->oGeorefExt->maxy.';
	var dx    = '.$dx.';
	var dy    = '.$dy.';
	var resx  = '.$res_x.';
	var resy  = '.$res_y.';
	var resx_m  = '.$res_xm.';
	var resy_m  = '.$res_ym.';
	var scale = '.$scale.';
	var refpoint_setting=true;
	var boxx 	= new Array();
	var boxy 	= new Array();
	var move_x 	= new Array();
	var move_y 	= new Array();
	var dragging  = false; 
	var dragdone  = false; 
	var moving  = false;
	var moved  = false;
	var doing = "'.$this->user->rolle->selectedButton.'";
	var cmd   = "";
	var width = '.$printwidth.';
	var height = '.$printheight.';
	var meter_pro_einheit = '.$this->meter_pro_einheit.';
	var root = document.documentElement;
	var mousewheelloop = 0;
	var stopnavigation = false;
	var refpoint_set_manually = false;

function startup() {
	if(window.addEventListener){
		if(navigator.userAgent.toLowerCase().indexOf(\'webkit\') >= 0)
			window.addEventListener(\'mousewheel\', mousewheelchange, false); // Chrome/Safari
		else
  		window.addEventListener(\'DOMMouseScroll\', mousewheelchange, false);
  }
  else{
		top.document.getElementById("map").onmousewheel = mousewheelchange;
	}
	focus_FS();
	set_printextent_on();
	document.getElementById("extent0").classList.add("active");
	alignbuttons(width, height);
}

// ------------------------------------------------------------------------------------
// --------------------------scripte fuer die NAVigation-------------------------------
// ------------------------------------------------------------------------------------
'.$SVGvars_navscript.'

 	function mousewheelzoom(){
		var g = document.getElementById("moveGroup");
		zx = g.getCTM().inverse();
		pathx[0] = Math.round(zx.e);
		pathy[0] = Math.round(zx.f);
		pathx[2] = Math.round(zx.e + resx*zx.a); 
		pathy[2] = Math.round(zx.f + resy*zx.a);
		top.sendpath("zoomin_box", pathx, pathy);
	}
	
	function mousewheelchange(evt){
		if(!evt)evt = window.event; // For IE
		if(top.document.GUI.stopnavigation.value == 0){
			window.clearTimeout(mousewheelloop);
			if(evt.preventDefault){
				evt.preventDefault();
			}else{ // IE fix
	    	evt.returnValue = false;
	    };
			if(evt.wheelDelta)
				delta = evt.wheelDelta / 3600; // Chrome/Safari
			else
				delta = evt.detail / -90; // Mozilla
			var z = 1 + delta*5;
			var g = document.getElementById("moveGroup");
			var p = getEventPoint(evt);
			if(p.x > 0 && p.y > 0){
				p = p.matrixTransform(g.getCTM().inverse());
				var k = root.createSVGMatrix().translate(p.x, p.y).scale(z).translate(-p.x, -p.y);
				setCTM(g, g.getCTM().multiply(k));
				mousewheelloop = window.setTimeout("mousewheelzoom()", 400);
			}
		}
	}
	
	function setCTM(element, matrix) {
		var s = "matrix(" + matrix.a + "," + matrix.b + "," + matrix.c + "," + matrix.d + "," + matrix.e + "," + matrix.f + ")";
		element.setAttribute("transform", s);
	}
	
	function getEventPoint(evt) {
		var p = root.createSVGPoint();
		p.x = evt.clientX;
		p.y = evt.clientY;
		if(top.navigator.userAgent.toLowerCase().indexOf("msie") >= 0){
			p.x = p.x - (top.document.body.clientWidth - resx)/2;
	    p.y = p.y - 30;
		}
		return p;
	}

function go_previous(){
  document.getElementById("canvas").setAttribute("cursor", "wait");
  top.document.GUI.CMD.value  = "previous";
  top.document.GUI.submit();
}

function go_next(){
  document.getElementById("canvas").setAttribute("cursor", "wait");
  top.document.GUI.CMD.value  = "next";
  top.document.GUI.submit();
}

function zoomin(){
	doing = "zoomin"; 
  document.getElementById("canvas").setAttribute("cursor", "crosshair");
}

function zoomout(){
	doing = "zoomout";
  document.getElementById("canvas").setAttribute("cursor", "crosshair");
}

function zoomall(){
  document.getElementById("canvas").setAttribute("cursor", "wait");
  top.Full_Extent();
}

function recentre(){
	doing = "recentre";
  document.getElementById("canvas").setAttribute("cursor", "grab");
}

function highlightbyid(id){
	document.querySelector(".active").classList.remove("active");
  document.getElementById(id).classList.add("active");
}

function focus_NAV(){
	// --------------- NAV-canvas aktivieren! ---------------------
  document.getElementById("canvas_FS").setAttribute("visibility", "hidden");
  document.getElementById("canvas").setAttribute("visibility", "visible");
}

function focus_FS(){
	// --------------- NAV-canvas deaktivieren! ---------------------
  document.getElementById("canvas").setAttribute("visibility", "hidden");
  document.getElementById("canvas_FS").setAttribute("visibility", "visible");
}

// -------------------------mausinteraktionen auf canvas------------------------------
function mousedown(evt){
  switch(doing){
   case "zoomin":
    startPoint(evt);
   break;
   case "zoomout":
    selectPoint(evt);
   break;
   case "recentre":
		document.getElementById("canvas").setAttribute("cursor", "grabbing");
    startMove(evt);
   break;
   default:
    alert("Fehlerhafte Eingabe! \nUebergebene Daten: "+cmd+", "+doing);
   break;
  }
}

function mousemove(evt){
	if (dragging){
		movePoint(evt);
	}
	if (moving){
		moveVector(evt);
	}
}

function mouseup(evt){
	if (dragging){
		endPoint(evt);
	}
	if (moving){
		endMove(evt);
		document.getElementById("canvas").setAttribute("cursor", "grab");
	}
}


function task(evt) {
	if(refpoint_setting){
		choose_refpoint(evt)
	}
	else {
		choose_print_extent(evt)
	}
}

function get_map_scale(){
	top.document.GUI.printscale.value = Math.round('.$this->map_scaledenom.');
}		
		
function set_printextent_on() {
  document.getElementById("canvas_FS").setAttribute("cursor", "crosshair");
	refpoint_setting=false;
  client_x = ""; client_y = "";
}

function set_refpoint_on(){
	document.getElementById("canvas_FS").setAttribute("cursor", "crosshair");
	refpoint_setting=true;
  client_x = ""; client_y = "";
}

function choose_print_extent(evt) {
  // neuen punkt abgreifen
  client_x = evt.clientX;
  client_y = resy - evt.clientY;
  		
  if(top.document.GUI.printscale.value != ""){
  	top.setprintextent("true");	  
  	worldprintwidth = '.$this->Document->activeframe[0]["mapwidth"].' * top.document.GUI.printscale.value/meter_pro_einheit * 0.00035277;
		worldprintheight = '.$this->Document->activeframe[0]["mapheight"].' * top.document.GUI.printscale.value/meter_pro_einheit * 0.00035277;
		width = worldprintwidth/scale;
		height = worldprintheight/scale;
		posx = Math.round(client_x-width/2);
		posy = Math.round(client_y-height/2);
		var obj3 = document.getElementById("auswahl");
		var obj2 = document.getElementById("auswahl2");
		var obj = document.getElementById("rechteck");
		obj2.setAttribute("transform", "translate("+posx+" "+posy+")");
		obj3.setAttribute("transform", "rotate("+top.document.GUI.angle.value+" "+client_x+" "+client_y+")");
		obj.setAttribute("width", Math.round(width));
		obj.setAttribute("height", Math.round(height));
		alignbuttons(width, height);
	  top.sendcenterlocation(client_x,client_y);
	  top.sendworldprintvalues(worldprintwidth, worldprintheight);
		if(refpoint_set_manually == false){
			refpoint = document.getElementById("pointposition");
			refpoint.setAttribute("x", client_x);
			refpoint.setAttribute("y", client_y);
			top.sendrefpointlocation(client_x,client_y);
		}
  }
  else{
  	alert("'.$strWarning4.'");
  }
}

function choose_refpoint(evt){
	refpoint_set_manually = true;
	client_x = evt.clientX;
  client_y = resy - evt.clientY;
	refpoint = document.getElementById("pointposition");
	refpoint.setAttribute("x", client_x);
	refpoint.setAttribute("y", client_y);
	top.sendrefpointlocation(client_x,client_y);
}

function alignbuttons(width, height){
	document.getElementById("poly_right").setAttribute("transform", "translate("+width+" "+height/2+")");
	document.getElementById("poly_left").setAttribute("transform", "translate(0 "+height/2+")");
	document.getElementById("poly_up").setAttribute("transform", "translate("+width/2+" "+height+")");
	document.getElementById("poly_down").setAttribute("transform", "translate("+width/2+" 0)");
}

function activate(evt){
	evt.target.setAttribute("style", "-moz-user-select: none;opacity:1;fill:rgb(192,192,255);stroke:black;stroke-width:2");
}

function deactivate(evt){
	evt.target.setAttribute("style", "-moz-user-select: none;opacity:0.01;fill:rgb(192,192,255);stroke:black;stroke-width:2");
}

function right(){
	var obj = document.getElementById("auswahl2");
	currenttranslate = obj.getAttributeNS(null, "transform").slice(10,-1).split(\' \');
	currenttranslate[0] = parseFloat(currenttranslate[0]) + width;
  newtranslate = "translate(" + currenttranslate.join(\' \') + ")";
  obj.setAttributeNS(null, "transform", newtranslate);
	refpoint = document.getElementById("pointposition");
	refpoint.setAttribute("x", parseFloat(refpoint.getAttribute("x")) + width);
	top.document.GUI.center_x.value = parseFloat(top.document.GUI.center_x.value) + parseFloat(top.document.GUI.worldprintwidth.value);
	document.getElementById("poly_right").setAttribute("style", "-moz-user-select: none;opacity:0.01;fill:rgb(192,192,255);stroke:black;stroke-width:2"); 
}

function left(){
	var obj = document.getElementById("auswahl2");
	currenttranslate = obj.getAttributeNS(null, "transform").slice(10,-1).split(\' \');
	currenttranslate[0] = parseFloat(currenttranslate[0]) - width;
  newtranslate = "translate(" + currenttranslate.join(\' \') + ")";
  obj.setAttributeNS(null, "transform", newtranslate);
	refpoint = document.getElementById("pointposition");
	refpoint.setAttribute("x", parseFloat(refpoint.getAttribute("x")) - width);
	top.document.GUI.center_x.value = parseFloat(top.document.GUI.center_x.value) - parseFloat(top.document.GUI.worldprintwidth.value);
	document.getElementById("poly_left").setAttribute("style", "-moz-user-select: none;opacity:0.01;fill:rgb(192,192,255);stroke:black;stroke-width:2"); 
}

function up(){
	var obj = document.getElementById("auswahl2");
	currenttranslate = obj.getAttributeNS(null, "transform").slice(10,-1).split(\' \');
	currenttranslate[1] = parseFloat(currenttranslate[1]) + height;
  newtranslate = "translate(" + currenttranslate.join(\' \') + ")";
  obj.setAttributeNS(null, "transform", newtranslate);
	refpoint = document.getElementById("pointposition");
	refpoint.setAttribute("y", parseFloat(refpoint.getAttribute("y")) + height);
	top.document.GUI.center_y.value = parseFloat(top.document.GUI.center_y.value) + parseFloat(top.document.GUI.worldprintheight.value);
	document.getElementById("poly_up").setAttribute("style", "-moz-user-select: none;opacity:0.01;fill:rgb(192,192,255);stroke:black;stroke-width:2"); 
}

function down(){
	var obj = document.getElementById("auswahl2");
	currenttranslate = obj.getAttributeNS(null, "transform").slice(10,-1).split(\' \');
	currenttranslate[1] = parseFloat(currenttranslate[1]) - height;
  newtranslate = "translate(" + currenttranslate.join(\' \') + ")";
  obj.setAttributeNS(null, "transform", newtranslate);
	refpoint = document.getElementById("pointposition");
	refpoint.setAttribute("y", parseFloat(refpoint.getAttribute("y")) - height);
	top.document.GUI.center_y.value = parseFloat(top.document.GUI.center_y.value) - parseFloat(top.document.GUI.worldprintheight.value);
	document.getElementById("poly_down").setAttribute("style", "-moz-user-select: none;opacity:0.01;fill:rgb(192,192,255);stroke:black;stroke-width:2"); 
}

// ----------------------------pgon zeichnen---------------------------------
function addpoint(evt) {
  // neuen punkt abgreifen
  client_x = evt.clientX;
  client_y = resy - evt.clientY;
  pathx.push(client_x);
  pathy.push(client_y);
  redrawBW();
}

function redrawBW() 
{
  // punktepfad erstellen
	path = "";
	var alle = pathx.length;
	for(var i = 0; i < alle; ++i)
	 {
	  path = path+" "+pathx[i]+","+pathy[i];
	 }
  
  // polygon um punktepfad erweitern
  var obj = document.getElementById("polygon");
  obj.setAttribute("points", path);

  // hiddenformvars aktualisieren
  //alert("path: "+path);
  top.sendBWpath(pathx,pathy);
}

function deletelast(evt) {
	pathx.pop();
	pathy.pop();

	//refpoint_setting=true;
	draw_pgon_on()
	
	redrawBW();
}
	
		
// ---------------------koordinatenausgabe in statuszeile--------------------------
'.$SVGvars_coordscript.'

// -------------------------tooltip-ausgabe fuer buttons------------------------------
'.$SVGvars_tooltipscript.'

]]></script>

  <defs>
'.$SVGvars_defs.'
	
  </defs>
  <rect id="background" style="fill:white" width="100%" height="100%"/>
	<g id="moveGroup" transform="translate(0 0)">
	  <image xlink:href="'.$bg_pic.'" height="100%" width="100%" y="0" x="0"/>
		<g id="cartesian" transform="translate(0,'.$res_y.') scale(1,-1)">
			<polygon points="" id="polygon" style="fill-opacity:0.5;fill:rgb(192,192,255);stroke:blue;stroke-width:2"/>
		</g>
		<rect id="canvas_FS" cursor="crosshair" onmousedown="task(evt)" onmousemove="hide_tooltip();" width="100%" height="100%" opacity="0" fill="cornflowerblue" visibility="visible"/>
		<rect id="canvas" cursor="crosshair" onmousedown="mousedown(evt)" onmousemove="mousemove(evt);hide_tooltip();" onmouseup="mouseup(evt);" fill="yellow" width="100%" height="100%" opacity="0" visibility="hidden"/>
		<g id="cartesian" transform="translate(0,'.$res_y.') scale(1,-1)">
			<use id="pointposition" xlink:href="#crosshair_blue" x="'.$refpoint_x.'" y="'.$refpoint_y.'"/>
			<g id="auswahl" transform="rotate('.$angle.' '.$pixel_x.' '.$pixel_y.')">
				<g id="auswahl2" transform="translate('.$pos_x.' '.$pos_y.')">			   		   				
					<rect id="rechteck" x="0" y="0" rx="0" ry="0" width="'.$printwidth.'" height="'.$printheight.'" style="fill:none;stroke:black;stroke-width:2;"></rect>
					<polygon points="0,15 15,0 0,-15 0,15" id="poly_right" onmouseover="activate(evt);" onmouseout="deactivate(evt);" onmouseup="right();" style="opacity:0.01;fill:rgb(192,192,255);stroke:blue;stroke-width:2"/>
					<polygon points="0,15 -15,0 0,-15 0,15" id="poly_left" onmouseover="activate(evt);" onmouseout="deactivate(evt);" onmouseup="left();" style="opacity:0.01;fill:rgb(192,192,255);stroke:blue;stroke-width:2"/>
					<polygon points="-15,0 0,15 15,0 -15,0" id="poly_up" onmouseover="activate(evt);" onmouseout="deactivate(evt);" onmouseup="up();" style="opacity:0.01;fill:rgb(192,192,255);stroke:blue;stroke-width:2"/>
					<polygon points="-15,0 0,-15 15,0 -15,0" id="poly_down" onmouseover="activate(evt);" onmouseout="deactivate(evt);" onmouseup="down();" style="opacity:0.01;fill:rgb(192,192,255);stroke:blue;stroke-width:2"/>
				</g>
			</g>
		</g>
	</g>
	<g id="buttons" filter="url(#Schatten)" cursor="pointer">
		<g id="buttons_NAV" cursor="pointer" onmouseout="hide_tooltip()" onmousedown="focus_NAV();hide_tooltip()">
			<rect x="0" y="0" rx="3" ry="3" width="216" height="36" class="navbutton_bg"/>
	'.$SVGvars_navbuttons.'
		</g>

    <g id="buttons_FS" cursor="pointer" onmouseout="hide_tooltip()" onmousedown="focus_FS();hide_tooltip()" transform="translate(0 36)">
			<rect x="0" y="0" rx="3" ry="3" width="108" height="36" class="navbutton_bg"/>

			<g id="extent" onmousedown="set_printextent_on();highlightbyid(\'extent0\');" transform="translate(0 0 )">
				<rect id="extent0" onmouseover="show_tooltip(\''.$strSetPrintExtent.'\',evt.clientX,evt.clientY)" x="0" y="0" rx="3" ry="3" width="36" height="36" class="navbutton_frame"/>
				<g class="navbutton" transform="translate(5 5) scale(0.8)">
					<path d="M3,1.0 L3,29 L27.0,29 L27,1 Z M6,4.0 L6,26 L24.0,26 L24,4 Z" style="fill-rule: evenodd;"/>
				</g>
	    </g>
	    		
	    <g id="mapscale" onmousedown="get_map_scale();" transform="translate(36 0 )">
        <rect id="mapscale0" onmouseover="show_tooltip(\''.$strUseMapscale.'\',evt.clientX,evt.clientY)" x="0" y="0" rx="3" ry="3" width="36" height="36" class="navbutton_frame"/>
				<g class="navbutton" transform="translate(5 5) scale(0.8)">
					<path d="M3,1.0 L3,29 L27.0,29 L27,1 Z M6,4.0 L6,26 L24.0,26 L24,4 Z" style="fill-rule: evenodd;"/>
					<path d="M19.4,14.2 C20.6,14.2 21.5,13.2 21.5,12.06 C21.5,10.9 20.6,10 19.4,10 C18.2,10 17.3,10.9 17.3,12.1 C17.3,13.2 18.2,14.2 19.4,14.2"/>
					<path d="M19.4,22.7 C20.6,22.7 21.5,21.8 21.5,20.6 C21.5,19.4 20.6,18.5 19.4,18.5 C18.2,18.5 17.3,19.4 17.3,20.6 C17.3,21.8 18.2,22.7 19.4,22.7"/>
					<path d="M8.5,10.7 L8.5,14.3 L8.7,14.3 L9,14.2 L9.3,14.1 L9.8,13.8 L10.3,13.5 L11.2,13 L11.2,21.7 C11.2,22.3 11.6,22.7 12.2,22.7 L13.7,22.7 C14.3,22.7 14.7,22.3 14.7,21.7 L14.7,7 L11.9,7 L11.5,7.7 L11.0,8.5 L10.2,9.5 L9.5,10.1 Z"/>
				</g>
	    </g>

			<g id="refpoint" onmousedown="set_refpoint_on();highlightbyid(\'refpoint0\');" transform="translate(72 0 )">
        <rect id="refpoint0" onmouseover="show_tooltip(\''.$strSetRefPoint.'\',evt.clientX,evt.clientY)" x="0" y="0" rx="3" ry="3" width="36" height="36" class="navbutton_frame"/>
				<g class="navbutton" transform="translate(5 5) scale(0.8)">
					<path d="M3,1.0 L3,29 L27.0,29 L27,1 Z M6,4.0 L6,26 L24.0,26 L24,4 Z" style="fill-rule: evenodd;"/>
					<path d="M10,12.5 C10,13.9 11.1,15 12.5,15 C13.9,15 15,13.9 15,12.5 C15,11.1 13.9,10 12.5,10 C11.1,10 10,11.1 10,12.5"/>
				</g>
	    </g>
	    
		</g>
		</g>
      
'.$SVGvars_tooltipblank.'
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
</div>