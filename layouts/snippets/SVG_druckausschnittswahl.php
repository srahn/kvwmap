<div id="map">
<?php
 
#
# PHP-Variabeln der SVG
#
	$randomnumber = rand(0, 1000000);
  $svgfile  = $randomnumber.'SVG.svg';
	$bg_pic   = $this->img['hauptkarte'];
	include(LAYOUTPATH.'snippets/SVGvars_navscript.php'); 		# zuweisen von: $SVGvars_navscript
	include(LAYOUTPATH.'snippets/SVGvars_navbuttons.php'); 		# zuweisen von: $SVGvars_navbuttons
	include(LAYOUTPATH.'snippets/SVGvars_defs.php'); 					# zuweisen von: $SVGvars_defs
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
	
	if($this->user->rolle->epsg_code == 4326){
		$center_y = ($this->user->rolle->oGeorefExt->maxy + $this->user->rolle->oGeorefExt->miny) / 2;
		$zoll_pro_einheit = InchesPerUnit(MS_DD, $center_y);
		$meter_pro_einheit = $zoll_pro_einheit / 39.3701;
	}
	else{
		$meter_pro_einheit = 1;
	}
	
	$worldprintwidth = $this->Document->activeframe[0]["mapwidth"] * $this->formvars['printscale']/$meter_pro_einheit * 0.00035277;
	$worldprintheight = $this->Document->activeframe[0]["mapheight"] * $this->formvars['printscale']/$meter_pro_einheit * 0.00035277;
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
	var doing = "'.$this->user->rolle->getSelectedButton().'";
	var highlighted  = "yellow";
	var cmd   = "";
	var width = '.$printwidth.';
	var height = '.$printheight.';
	var meter_pro_einheit = '.$meter_pro_einheit.';
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
  document.getElementById("canvas").setAttribute("cursor", "move");
}

function highlightbyid(id){
	document.getElementById("zoomin0").style.setProperty("fill","ghostwhite", "");
  document.getElementById("zoomout0").style.setProperty("fill","ghostwhite", "");
  document.getElementById("recentre0").style.setProperty("fill","ghostwhite", "");
	document.getElementById(id).style.setProperty("fill",highlighted, "");
}

function focus_NAV(){
	// --------------- NAV-canvas aktivieren! ---------------------
  document.getElementById("canvas_FS").setAttribute("visibility", "hidden");
  document.getElementById("canvas").setAttribute("visibility", "visible");
	// --------------- FS-leiste ohne highlight ---------------------
  document.getElementById("text0").style.setProperty("fill","ghostwhite", "");
	document.getElementById("refpoint0").style.setProperty("fill","ghostwhite", "");
}

function focus_FS(){
	// --------------- NAV-canvas deaktivieren! ---------------------
  document.getElementById("canvas").setAttribute("visibility", "hidden");
  document.getElementById("canvas_FS").setAttribute("visibility", "visible");
	// --------------- NAV-leiste ohne highlight ---------------------
  document.getElementById("zoomin0").style.setProperty("fill","ghostwhite", "");
  document.getElementById("zoomout0").style.setProperty("fill","ghostwhite", "");
  document.getElementById("recentre0").style.setProperty("fill","ghostwhite", "");
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
	}
}

// ----------------------ausgewaehlten button highlighten---------------------------
function highlight(evt){
  document.getElementById("zoomin0").style.setProperty("fill","ghostwhite", "");
  document.getElementById("zoomout0").style.setProperty("fill","ghostwhite", "");
  document.getElementById("recentre0").style.setProperty("fill","ghostwhite", "");
  evt.target.style.setProperty("fill",highlighted, "");
}

// ------------------------------------------------------------------------------------
// --------------------------scripte fuer die FS bodenrichtwerte-------------------------------
// ------------------------------------------------------------------------------------
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
	document.getElementById("refpoint0").style.setProperty("fill","ghostwhite", "");
  document.getElementById("text0").style.setProperty("fill",highlighted, "");
	refpoint_setting=false;
  client_x = ""; client_y = "";
}

function set_refpoint_on(){
	document.getElementById("canvas_FS").setAttribute("cursor", "crosshair");
	document.getElementById("text0").style.setProperty("fill","ghostwhite", "");
  document.getElementById("refpoint0").style.setProperty("fill",highlighted, "");
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
  	alert("Bitte geben Sie einen Druckmassstab ein");
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
		<rect id="canvas_FS" cursor="crosshair" onmousedown="task(evt)" onmousemove="hide_tooltip();" width="100%" height="100%" opacity="0" fill="cornflowerblue" visibility="visible"/>
		<rect id="canvas" cursor="crosshair" onmousedown="mousedown(evt)" onmousemove="mousemove(evt);hide_tooltip();" onmouseup="mouseup(evt);" fill="yellow" width="100%" height="100%" opacity="0" visibility="hidden"/>
		<g id="cartesian" transform="translate(0,'.$res_y.') scale(1,-1)">
			<use id="pointposition" xlink:href="#crosshair_blue" x="'.$refpoint_x.'" y="'.$refpoint_y.'"/>
			<polygon points="" id="polygon" style="fill-opacity:0.5;fill:rgb(192,192,255);stroke:blue;stroke-width:2"/>
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
  <g id="buttons_NAV" cursor="pointer" onmouseout="hide_tooltip()" onmousedown="focus_NAV();hide_tooltip()">
'.$SVGvars_navbuttons.'
		</g>

    <g id="buttons_FS" cursor="pointer" onmouseout="hide_tooltip()" onmousedown="focus_FS();hide_tooltip()" transform="translate(0 26)">

			<g id="text" onmousedown="set_printextent_on();" transform="translate(0 0 )">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
        	<set attributeName="filter" begin="text.mousedown" fill="freeze" to="none"/>
					<set attributeName="filter" begin="text.mouseup;text.mouseout" fill="freeze" to="url(#Schatten)"/>
				</rect>
				<g transform="translate(-4.7 -12) scale(1.35 1.35) matrix(0.7 0 0 0.7 -3.5 0)">
					<rect x="14" y="20" width="18" height="12" style="fill:none;stroke:rgb(0,0,0);stroke-width:1.5"/>
				</g>
				<rect id="text0" onmouseover="show_tooltip(\'Druckausschnitt setzen\',evt.clientX,evt.clientY)" x="0" y="0" rx="1" ry="1" width="25" height="25" fill="none" opacity="0.2"/>
	    </g>
	    		
	    <g id="mapscale" onmousedown="get_map_scale();" transform="translate(26 0 )">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
        	<set attributeName="filter" begin="mapscale.mousedown" fill="freeze" to="none"/>
					<set attributeName="filter" begin="mapscale.mouseup;mapscale.mouseout" fill="freeze" to="url(#Schatten)"/>
				</rect>
				<g transform="translate(-4.4 -12) scale(1.35 1.35) matrix(0.7 0 0 0.7 -3.5 0)">
					<rect x="14" y="17" width="17" height="17" style="fill:none;stroke:rgb(0,0,0);stroke-width:1.5;stroke-dasharray:4,3,3,3,8,3,3,3,8,3,3,3,8,3,3,3,4"/>
				</g>
				<text transform="scale(0.6 0.6)" x="20" y="27" style="text-anchor:middle;fill:rgb(0,0,0);font-size:18;font-family:Arial;">M</text>
				<rect id="mapscale0" onmouseover="show_tooltip(\'Kartenmassstab uebernehmen\',evt.clientX,evt.clientY)" x="0" y="0" rx="1" ry="1" width="25" height="25" fill="white" opacity="0.0"/>
	    </g>

			<g id="refpoint" onmousedown="set_refpoint_on();" transform="translate(52 0 )">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
        	<set attributeName="filter" begin="mapscale.mousedown" fill="freeze" to="none"/>
					<set attributeName="filter" begin="mapscale.mouseup;mapscale.mouseout" fill="freeze" to="url(#Schatten)"/>
				</rect>
				<g transform="scale(0.5) translate(2 8)">
					<text x="23" y="15" style="text-anchor:middle;fill:black;font-size:10;font-family:Arial;font-weight:bold">Punkt</text>
					<circle cx="23" cy="21" r="3"/>
				</g>
				<rect id="refpoint0" onmouseover="show_tooltip(\'Bezugspunkt setzen\',evt.clientX,evt.clientY)" x="0" y="0" rx="1" ry="1" width="25" height="25" fill="white" opacity="0.2"/>
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