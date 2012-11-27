<?php
#
###################################################################
#                                                                 #
# SVG-Anwendungen & Fachschalen                                   #
#                                                                 #
###################################################################
#
?>
  <SCRIPT type="text/ecmascript"><!--

  function go_cmd(cmd)   {
      document.GUI.CMD.value  = cmd;
      document.GUI.submit();
  }
   
  function submit(input_coord, cmd){
  	if((navigator.userAgent.toLowerCase().indexOf('firefox') >= 0) && document.GUI.legendtouched.value == 0){
  		svgdoc = document.SVG.getSVGDocument();	
			var mapimg = svgdoc.getElementById("mapimg2");
			var scalebar = document.getElementById("scalebar");
			var refmap = document.getElementById("refmap");
			var scale = document.getElementById("scale");
			var lagebezeichnung = document.getElementById("lagebezeichnung");
			var minx = document.GUI.minx;
			var miny = document.GUI.miny;
			var maxx = document.GUI.maxx;
			var maxy = document.GUI.maxy;			
			var pixelsize = document.GUI.pixelsize;
			var maptime = document.getElementById("maptime");
			var polygon = svgdoc.getElementById("polygon");
			// nix
			// nix
  		ahah("<? echo URL.APPLVERSION.'index.php'; ?>", "go=getMap_ajax&INPUT_COORD="+input_coord+"&CMD="+cmd, 
  		new Array(
  			mapimg, 
  			scalebar,
  			refmap, 
  			scale,
  			lagebezeichnung,
  			minx,
  			miny,
  			maxx,
  			maxy,
  			pixelsize,
  			maptime, 			
  			polygon,
  			'',
  			''
  		), 			 
  		"xlink:href^src^src^setvalue^sethtml^setvalue^setvalue^setvalue^setvalue^setvalue^sethtml^points^execute_function^execute_function");
  		document.GUI.INPUT_COORD.value = '';
  		document.GUI.CMD.value = '';
  	}
  	else{
  		document.GUI.submit();
  	}
  }

  function sendpath(cmd,pathx,pathy)   {;
		document.GUI.stopnavigation.value = 1;
    path  = "";
    switch(cmd) 
    {
     case "zoomin_point":
      path = pathx[0]+","+pathy[0];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "zoomin";
      submit(path, document.GUI.CMD.value);
     break;
     case "zoomout":
      path = pathx[0]+","+pathy[0];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = cmd;
      submit(path, cmd);
     break;
     case "zoomin_box":
      path = pathx[0]+","+pathy[0]+";"+pathx[2]+","+pathy[2];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "zoomin";
      submit(path, document.GUI.CMD.value);
     break;
     case "recentre":
      path = pathx[0]+","+pathy[0];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = cmd;
      submit(path, cmd);
     break;
     case "pquery_point":
      path = pathx[0]+","+pathy[0]+";"+pathx[0]+","+pathy[0];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "pquery";
      document.GUI.submit();
     break;
     case "pquery_box":
      path = pathx[0]+","+pathy[0]+";"+pathx[0]+","+pathy[0];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "pquery";
      document.GUI.submit();
     break;
     case "touchquery_point":
     	top.document.GUI.searchradius.value = "";
      path = pathx[0]+","+pathy[0]+";"+pathx[0]+","+pathy[0];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "touchquery";
      document.GUI.submit();
     break;
     case "touchquery_box":
     	top.document.GUI.searchradius.value = "";
      path = pathx[0]+","+pathy[0]+";"+pathx[0]+","+pathy[0];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "touchquery";
      document.GUI.submit();
     break;
     case "ppquery_point":
      top.document.GUI.searchradius.value = "";
      path = pathx[0]+","+pathy[0]+";"+pathx[0]+","+pathy[0];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "ppquery";
      document.GUI.submit();
     break;
     case "ppquery_box":
      top.document.GUI.searchradius.value = "";
      path = pathx[0]+","+pathy[0]+";"+pathx[2]+","+pathy[2];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "ppquery";
      document.GUI.submit();
     break;
     case "pquery_polygon":
      path = pathx[0]+","+pathy[0]+";"+pathx[2]+","+pathy[2];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "pquery";
      document.GUI.submit();
     break;
     case "polygonquery":
     	for(i = 0; i < pathx.length-1; i++){
     		path = path+pathx[i]+","+pathy[i]+";";
     	}
     	path = path+pathx[i]+","+pathy[i];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "polygonquery";
      document.GUI.submit();
     break;
     default:
      path = pathx[0]+","+pathy[0];
      alert("Keine Bearbeitung moeglich! \nUebergebene Daten: "+cmd+", "+path);
     break;
    }
  }
  
  --></SCRIPT>

<?php
#
# PHP-Variabeln der SVG 
#
  $randomnumber = rand(0, 1000000);
  $svgfile  = $randomnumber.'SVG_map.svg';
  include(LAYOUTPATH.'snippets/SVGvars_mainnavbuttons.php');  # zuweisen von: $SVGvars_mainnavbuttons
  include(LAYOUTPATH.'snippets/SVGvars_defs.php');            # zuweisen von: $SVGvars_defs 
  include(LAYOUTPATH.'snippets/SVGvars_coordscript.php');     # zuweisen von: $SVGvars_coordscript
  include(LAYOUTPATH.'snippets/SVGvars_querytooltipscript.php');   # zuweisen von: $SVGvars_tooltipscript
  include(LAYOUTPATH.'snippets/SVGvars_tooltipscript.php');   # zuweisen von: $SVGvars_tooltipscript 
  include(LAYOUTPATH.'snippets/SVGvars_tooltipblank.php');    # zuweisen von: $SVGvars_tooltipblank
 	include(LAYOUTPATH.'snippets/ahah.php');
  $bg_pic   = $this->img['hauptkarte'];
  $res_x    = $this->map->width;
  $res_y    = $this->map->height;
  $res_xm   = $this->map->width/2;
  $res_ym   = $this->map->height/2;
  $dx       = $this->map->extent->maxx-$this->map->extent->minx;
  $dy       = $this->map->extent->maxy-$this->map->extent->miny;
  $scale    = ($dx/$res_x+$dy/$res_y)/2;
  $radius = $this->formvars['searchradius'] / $scale;

#
# Zusammenstellen der SVG  
#
# 2006-02-16 pk
# in function highlight(evt) Zeilen für previous und next eingefügt
$fpsvg = fopen(IMAGEPATH.$svgfile,w) or die('fail: fopen('.$svgfile.')');
chmod(IMAGEPATH.$svgfile, 0666);
$svg='<?xml version="1.0"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
  "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg id="svgmap" zoomAndPan="" width="'.$res_x.'" height="'.$res_y.'" onload="init();" onmousemove="mouse_move(evt);" 
  xmlns="http://www.w3.org/2000/svg" version="1.1"
  xmlns:xlink="http://www.w3.org/1999/xlink">
<title> kvwmap </title><desc> kvwmap - WebGIS application - kvwmap.sourceforge.net </desc>
 
<script type="text/ecmascript"><![CDATA[ 

	try{
		top.printNode = printNode;
	}
	catch(e){
	} 

  var resx  = '.$res_x.';
  var resy  = '.$res_y.';
  var resx_m  = '.$res_xm.';
  var resy_m  = '.$res_ym.';
  var pathx = new Array();
  var pathy = new Array();
	var pathx_world = new Array();
  var pathy_world = new Array();
  var polypathx = new Array();
  var polypathy = new Array();
  var boxx  = new Array();
  var boxy  = new Array();
  var dragging  = false; 
  var dragdone  = false; 
  var measuring  = false;
  var polydrawing  = false; 
	var texttyping = false;
	var arrowdrawing = false;
	var current_freepolygon;
	var current_freetext;
	var current_freearrow;
  moving  = false;
  moved  = false;
  var doing = "'.$this->user->rolle->getSelectedButton().'";
  var highlighted  = "yellow";
  var cmd   = ""; 
  var data="";
  var x_pos="";
  var y_pos="";
	var get_vertices_loop;
	var gps_follow_cooldown = 0;
	var root = document.documentElement;
	var mousewheelloop = 0;
	var stopnavigation = false;
	var last_x = 0;
  		
  ';

if($_SESSION['mobile'] == 'true'){
	$svg.= '  
  function update_gps_position(){
		posx = top.document.GUI.gps_posx.value+"";
		posy = top.document.GUI.gps_posy.value+"";
		if(posx != "" && posy != ""){
			x = Math.round((posx - parseFloat(top.document.GUI.minx.value))/parseFloat(top.document.GUI.pixelsize.value));
			y = Math.round((posy - top.document.GUI.miny.value)/parseFloat(top.document.GUI.pixelsize.value));
			//alert(x+" "+y);
	  	var pos = document.getElementById("gps_position");
	  	pos.setAttribute("x", x);
	  	pos.setAttribute("y", y);
			if(top.document.GUI.gps_follow.value == "on"){
				if(gps_follow_cooldown == 0 && (x < 50 || x > resx-50 || y < 50 || y > resy-50)){
					gps_follow_cooldown = 3;
					pathx[0] = x;
					pathy[0] = resy-y;
					sendpath("recentre", pathx, pathy);
				}
				if(gps_follow_cooldown > 0){
					gps_follow_cooldown--;
				}
			}
		}
  	top.ahah("'.URL.APPLVERSION.'index.php", "go=get_gps_position&srs='.$this->user->rolle->epsg_code.'", new Array(top.document.GUI.gps_posx, top.document.GUI.gps_posy), "");
 	}
 	
 	window.setInterval("update_gps_position()", 2000);';
}
$svg .='

function startup(){';
	if($_SESSION['mobile'] == 'true'){
		$svg .='update_gps_position();';
	}
	$svg .='
	if(get_measure_path()){
		redrawPL();
	}
	get_polygon_path();	
	redrawPolygon();
	eval(doing+"()");	
  document.getElementById(doing+"0").style.setProperty("fill",highlighted,"");
}

function sendpath(cmd, pathx, pathy){
	document.getElementById("waitingimage").style.setProperty("visibility","visible", "");
	top.sendpath(cmd, pathx, pathy);
}

function mousewheelzoom(){
	var g = document.getElementById("moveGroup");
	zx = g.getCTM().inverse();
	pathx[0] = Math.round(zx.e);
	pathy[0] = Math.round(zx.f);
	pathx[2] = Math.round(zx.e + resx*zx.a); 
	pathy[2] = Math.round(zx.f + resy*zx.a);
	sendpath("zoomin_box", pathx, pathy);
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

function init(){
	startup();
	if(navigator.appName == "Adobe SVG Viewer"){
		//document.getElementById("mapimg2").addEventListener("load", function(evt) { moveback(); }, false);
	}
	else{
		document.getElementById("mapimg2").addEventListener("load", function(evt) { moveback(evt); }, false);
	}
	if(window.addEventListener){
		if(navigator.userAgent.toLowerCase().indexOf(\'webkit\') >= 0)
			window.addEventListener(\'mousewheel\', mousewheelchange, false); // Chrome/Safari
		else
  		window.addEventListener(\'DOMMouseScroll\', mousewheelchange, false);
  }
  else{
		top.document.getElementById("map").onmousewheel = mousewheelchange;
	}
}

top.document.getElementById("map").SVGstartup = startup;		// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen

function moveback(evt){
	document.getElementById("moveGroup").setAttribute("transform", "translate(0 0)");
	document.getElementById("mapimg").setAttribute("xlink:href", document.getElementById("mapimg2").getAttribute("xlink:href"));
	// Redlining-Sachen loeschen
	while(child = document.getElementById("redlining").firstChild){
  	document.getElementById("redlining").removeChild(child);
	}
	// Tooltip refreshen
	oldmousex = undefined;
	hidetooltip(evt);
	// Navigation wieder erlauben
	top.document.GUI.stopnavigation.value = 0;
	document.getElementById("waitingimage").style.visibility = "hidden";
	window.setTimeout(\'document.getElementById("mapimg2").setAttribute("xlink:href", "")\', 200);		// Firefox 4 
}

function go_previous(){
  document.getElementById("canvas").setAttribute("cursor", "wait");
  cmd="previous";
  top.document.GUI.go.value = "history_move";
  top.go_cmd(cmd);
}

function go_next(){
  document.getElementById("canvas").setAttribute("cursor", "wait");
  cmd="next";
  top.document.GUI.go.value = "history_move";
  top.go_cmd(cmd);
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
  cmd="Full_Extent";
  top.go_cmd(cmd);
}

function recentre(){
	if(doing == "polygonquery"){
		save_polygon_path();
	}
	if(doing == "measure"){
		save_measure_path();
	}
  doing = "recentre";
  document.getElementById("canvas").setAttribute("cursor", "move"); //setAttribute("cursor", "url(#MyMove)");
}

function showcoords(){
	doing = "showcoords";
  document.getElementById("canvas").setAttribute("cursor", "crosshair");
}

function pquery(){
  doing = "pquery";
  document.getElementById("canvas").setAttribute("cursor", "help");
}

function touchquery(){
	doing = "touchquery";
	document.getElementById("canvas").setAttribute("cursor", "help");
}

function polygonquery(){
	doing = "polygonquery";
	document.getElementById("canvas").setAttribute("cursor", "help");
	if(top.document.GUI.str_polypathx.value != ""){
		polydrawing = true;
		top.document.GUI.str_polypathx.value = "";
		top.document.GUI.str_polypathy.value = "";
	}
	else{
		deletepolygon();
		redrawPolygon();
	}
}

function drawarrow(){
	document.getElementById("canvas").setAttribute("cursor", "crosshair");
	doing = "drawarrow";
}

function drawpolygon(){
	document.getElementById("canvas").setAttribute("cursor", "crosshair");
	doing = "drawpolygon";
	polydrawing = false;
	top.document.GUI.str_polypathx.value = "";
	top.document.GUI.str_polypathy.value = "";
	deletepolygon();
}

function addfreetext(){
	document.getElementById("canvas").setAttribute("cursor", "crosshair");
	doing = "addtext";
	texttyping = false;
}
		    
function ppquery(){
  doing = "ppquery";
  document.getElementById("canvas").setAttribute("cursor", "help");
}   
  
// in pquery() und pquery_prompt() aufgeteilt, da der Promt sonst auch bei jedem reload erscheint   
function pquery_prompt(){     
  top.document.GUI.searchradius.value=prompt("Geben Sie den Suchradius in Meter ein.",top.document.GUI.searchradius.value);
  radius = (top.document.GUI.searchradius.value / parseFloat(top.document.GUI.pixelsize.value));
  document.getElementById("suchkreis").setAttribute("r", radius);
}

function noMeasuring(){
  measuring = false;
  restart();
}

function measure(){
	options1 = top.document.getElementById("options");
	options1.innerHTML=\'<input type="checkbox" onclick="toggle_vertices()" name="punktfang">&nbsp;Punktfang\';
  doing = "measure";
	if(top.document.GUI.str_pathx.value != ""){
		measuring = true;	
		top.document.GUI.str_pathx.value = "";
		top.document.GUI.str_pathy.value = "";
	}
	else{
  	measuring = false;
  	restart();
	}
  document.getElementById("canvas").setAttribute("cursor", "crosshair");
}

function save_measure_path(){
	var length = pathx.length;
	if(length > 0){
		var str_pathx = pathx_world[0];
		var str_pathy = pathy_world[0];
	  for(var i = 1; i < length; i++){
	    str_pathx = str_pathx + ";" + pathx_world[i];
			str_pathy = str_pathy + ";" + pathy_world[i];
		}
		top.document.GUI.str_pathx.value = str_pathx;
		top.document.GUI.str_pathy.value = str_pathy;
	}
}

function get_measure_path(){
	if(top.document.GUI.str_pathx.value != ""){
		document.getElementById(doing+"0").style.setProperty("fill", "ghostwhite","");
		doing = "measure";
		measuring = true;
		var str_pathx = top.document.GUI.str_pathx.value;
		var str_pathy = top.document.GUI.str_pathy.value;
		pathx_world = str_pathx.split(";");
		pathy_world = str_pathy.split(";");  
		pathx[0] = (pathx_world[0] - parseFloat(top.document.GUI.minx.value))/parseFloat(top.document.GUI.pixelsize.value);
		pathy[0] = (pathy_world[0] - parseFloat(top.document.GUI.miny.value))/parseFloat(top.document.GUI.pixelsize.value);
		var length = pathx_world.length; 
	  for(var i = 1; i < length; i++){
	    pathx[i] = (pathx_world[i] - parseFloat(top.document.GUI.minx.value))/parseFloat(top.document.GUI.pixelsize.value);
			pathy[i] = (pathy_world[i] - parseFloat(top.document.GUI.miny.value))/parseFloat(top.document.GUI.pixelsize.value);
		}
		return true;
	}
	return false;
}

function save_polygon_path(){
	var length = polypathx.length;
	if(length > 0){
		var str_polypathx = (polypathx[0] * parseFloat(top.document.GUI.pixelsize.value)) + parseFloat(top.document.GUI.minx.value);
		var str_polypathy = (polypathy[0] * parseFloat(top.document.GUI.pixelsize.value)) + parseFloat(top.document.GUI.miny.value);
	  for(var i = 1; i < length; i++){
	    str_polypathx = str_polypathx + ";" + ((polypathx[i] * parseFloat(top.document.GUI.pixelsize.value)) + parseFloat(top.document.GUI.minx.value));
			str_polypathy = str_polypathy + ";" + ((polypathy[i] * parseFloat(top.document.GUI.pixelsize.value)) + parseFloat(top.document.GUI.miny.value));
		}
		top.document.GUI.str_polypathx.value = str_polypathx;
		top.document.GUI.str_polypathy.value = str_polypathy;
	}
}

function get_polygon_path(){
	if(top.document.GUI.str_polypathx.value != ""){
		document.getElementById(doing+"0").style.setProperty("fill", "ghostwhite","");
		doing = "polygonquery";
		var str_polypathx = top.document.GUI.str_polypathx.value;
		var str_polypathy = top.document.GUI.str_polypathy.value;
		world_polypathx = str_polypathx.split(";");
		world_polypathy = str_polypathy.split(";");  
		polypathx[0] = (world_polypathx[0] - parseFloat(top.document.GUI.minx.value))/parseFloat(top.document.GUI.pixelsize.value);
		polypathy[0] = (world_polypathy[0] - parseFloat(top.document.GUI.miny.value))/parseFloat(top.document.GUI.pixelsize.value);
		var length = world_polypathx.length; 
	  for(var i = 1; i < length; i++){
	    polypathx[i] = (world_polypathx[i] - parseFloat(top.document.GUI.minx.value))/parseFloat(top.document.GUI.pixelsize.value);
			polypathy[i] = (world_polypathy[i] - parseFloat(top.document.GUI.miny.value))/parseFloat(top.document.GUI.pixelsize.value);
		}
	}
}

function switch_gps_follow(){
	if(top.document.GUI.gps_follow.value == "on"){
		top.document.GUI.gps_follow.value = "off";
	}
	else{
		top.document.GUI.gps_follow.value = "on";
	}
	top.document.GUI.submit();
}


function world2pixelsvg(pathWelt){
	var path  = new Array();
	pathWelt = pathWelt.replace(/L /g, "");		// neuere Postgis-Versionen haben ein L mit drin
	explosion = pathWelt.split(" ");
	for(i = 0; i < explosion.length; i++){
		if(explosion[i] == "M"){
			path.push("M");
			laststartx = Math.round((explosion[i+1] - parseFloat(top.document.GUI.minx.value))/parseFloat(top.document.GUI.pixelsize.value));
			laststarty = Math.round((explosion[i+2] - top.document.GUI.miny.value)/parseFloat(top.document.GUI.pixelsize.value));
		}
		if(explosion[i] != "M" && explosion[i] != "Z" && explosion[i] != ""){
			path.push(Math.round((explosion[i] - parseFloat(top.document.GUI.minx.value))/parseFloat(top.document.GUI.pixelsize.value)));
			path.push(Math.round((explosion[i+1] - top.document.GUI.miny.value)/parseFloat(top.document.GUI.pixelsize.value)));
			i++;
		}
		if(explosion[i] == "Z"){			// neuere Postgis-Versionen liefern bei asSVG ein Z zum Schliessen des Rings anstatt der Startkoordinate
    	path.push(laststartx);
    	path.push(laststarty);
    }
	}
	pixelpath = "";
	for(i = 0; i < path.length; i++){
		pixelpath = pixelpath + path[i] + " ";
	}
	return pixelpath;
}


// -------------------------mausinteraktionen auf canvas------------------------------
// id="canvas" onmousedown="canvas(evt)" onmousemove="hide_tooltip();movePoint(evt);moveVector(evt)" onmouseup="endPoint(evt);endMove(evt)" width="100%" height="100%" opacity="0"/>
// function canvas(evt){


function mousedown(evt){
	if(top.document.GUI.stopnavigation.value == 0){
	  switch(doing){
	   case "previous":
	   break;
	   case "next":
	   break;
	   case "zoomin":
	    startPoint(evt);
	   break;
	   case "zoomout":
	    selectPoint(evt);
	   break;
	   case "recentre":
	    startMove(evt);
	   break;
		case "showcoords":
	    show_coords(evt);
	   break;
	   case "pquery":
	    startPoint(evt);
	   break;
		 case "touchquery":
	    startPoint(evt);
	   break;
	   case "ppquery":
	    startPoint(evt);
	   break;
	   case "polygonquery":
	 		if (polydrawing){
	      addpolypoint(evt);
	    }
	    else {
	      startpolydraw(evt);
	    }
	   break;
		 case "drawpolygon":
	 		if (polydrawing){
	      addpolypoint(evt);
	    }
	    else {
	      startpolydraw(evt);
	    }
	   break;
		 case "addtext":
	     addnewtext(evt);
	   break;
		 case "drawarrow":
		   startarrowdraw(evt);
	   break;
	   case "measure":
	    if (measuring){
	      addpoint(evt);
	    }
	    else {
	      startMeasure(evt);
	    }
	   break;
	   default:
	    alert("Fehlerhafte Eingabe! \nUebergebene Daten: "+cmd+", "+doing);
	   break;
	  }
	}
}

function mousemove(evt){
  switch(doing) {
	 case "measure":
	    if (measuring){
	      showMeasurement(evt);
	    }
	    else {
	    show_tooltip(\'Startpunkt setzen\',evt.clientX,evt.clientY)
	    }
	 break;
	 		
	 case "pquery":
	 	clientx = evt.clientX;
		clienty = resy - evt.clientY;
		document.getElementById("suchkreis").setAttribute("cx", clientx);
		document.getElementById("suchkreis").setAttribute("cy", clienty);
	 break;
	
	 case "touchquery":
	 break;
	
	 case "drawarrow":
		 if(arrowdrawing){
	     dragarrow(evt);
		 }
	 break;
			
	 default:
	 	if(Math.abs(last_x - evt.clientX) > 10){
	  	hide_tooltip();
	  	last_x = evt.clientX;
	  }
	  movePoint(evt);
	  moveVector(evt);
	 break;
  }
}

function mouseup(evt){
  switch(doing) 
  {
   case "measure":
   break;
	 case "drawarrow":
	   finisharrowdraw();
	 break;
   default:
    hide_tooltip();
    endPoint(evt);
    endMove(evt);
   break;
  }
}


//--------------- Text setzen -----------------------

function addnewtext(evt){
  texttyping = true;
  // neues Textelement erzeugen
	current_freetext = create_new_freetext(evt.clientX, resy - evt.clientY);
}

function create_new_freetext(x, y){
	var newtext = document.createElementNS("http://www.w3.org/2000/svg","text");
  newtext.setAttributeNS(null, "style", "fill:rgb(255,0,0);font-size:15px;font-family:Arial;font-weight:bold;");
	newtext.setAttributeNS(null, "transform", "scale(1,-1)");
	newtext.setAttributeNS(null, "x", x);
	newtext.setAttributeNS(null, "y", -y);
	document.documentElement.addEventListener("keypress", texttype, false);
	tspan1 = document.createElementNS("http://www.w3.org/2000/svg", "tspan");
	tspan1.appendChild(document.createTextNode("Text"));
	newtext.appendChild(tspan1);
  document.getElementById("redlining").appendChild(newtext);
	return newtext;
}


function texttype(evt){
	if(doing == "addtext" && texttyping){
		var text = current_freetext.lastChild.firstChild.nodeValue;
		var offsetx = current_freetext.getAttribute("x");
		var offsety = 20;
		if(evt.type == "keypress"){
			if(evt.charCode){
	      var charCode = evt.charCode;
			}
			else{
				var charCode = evt.keyCode;
			}
			if(charCode > 31 && charCode != 127 && charCode < 65535){		//all real characters
	    	text += String.fromCharCode(charCode);
			}
			if(charCode == 8){	//backspace key
				if(text.length == 0 && current_freetext.childNodes.length > 1){
					current_freetext.removeChild(current_freetext.lastChild);
					text = current_freetext.lastChild.firstChild.nodeValue;
				}
				else{
	    		text = text.substring(0,text.length-1);
				}
			}
	    else if(charCode == 10 || charCode == 13){	//enter key
	    	tspan1 = document.createElementNS("http://www.w3.org/2000/svg", "tspan");
				tspan1.appendChild(document.createTextNode(""));
				tspan1.setAttribute("x", offsetx);
				tspan1.setAttribute("dy", offsety);
				current_freetext.appendChild(tspan1);
				text = "";
			}
		}
		current_freetext.lastChild.firstChild.nodeValue = text;
		evt.preventDefault();
	}
}

//--------------- Text setzen -----------------------

//--------------- Pfeil zeichnen --------------------

function startarrowdraw(evt){
  arrowdrawing = true;
  // neuen punkt abgreifen
  x = evt.clientX;
  y = resy - evt.clientY;
	if(doing == "drawarrow"){
		current_freearrow = create_new_freearrow(x, y);
	}
}

function dragarrow(evt){
	x = evt.clientX;
  y = resy - evt.clientY;
	distance = Math.sqrt(Math.pow(current_freearrow.posy-y, 2) + Math.pow(current_freearrow.posx-x, 2));
	angle = Math.acos((current_freearrow.posy-y)/distance)*180/Math.PI;
	if(current_freearrow.posx-x > 0) angle = -1*angle;
	current_freearrow.setAttribute("transform", current_freearrow.translate+" rotate("+angle+", 0, 0) scale("+distance/40+" "+distance/40+")");  
}

function finisharrowdraw(evt){
  arrowdrawing = false;
}

function create_new_freearrow(x, y){
	var newarrow = document.getElementById("free_arrow").cloneNode(true);
	newarrow.setAttribute("transform", "translate("+x+" "+y+") rotate(0, 0, 0) scale(0 0)");
	newarrow.posx = x;
	newarrow.posy = y;
	newarrow.translate = "translate("+x+" "+y+")";
  document.getElementById("redlining").appendChild(newarrow);
	return newarrow;
}

//--------------- Pfeil zeichnen --------------------

//--------------- Polygon zeichnen ------------------

function create_new_freepolygon(){
	var newpoly = document.createElementNS("http://www.w3.org/2000/svg","polygon");
	newpoly.setAttributeNS(null, "style", "fill:red;stroke:black;stroke-width:2");
  newpoly.setAttributeNS(null, "opacity", "0.35");	
  document.getElementById("redlining").appendChild(newpoly);
	return newpoly;
}
		
function startpolydraw(evt){
	restart();
  polydrawing = true;
  // neuen punkt abgreifen
  polypathx[0] = evt.clientX;
  polypathy[0] = resy - evt.clientY;
	if(doing == "drawpolygon"){
		current_freepolygon = create_new_freepolygon();
	}
}
		
function addpolypoint(evt){
	// neuen eckpunkt abgreifen
  client_x = evt.clientX;
  client_y = resy - evt.clientY;
  if(doing == "polygonquery" && client_x == polypathx[polypathx.length-1] && client_y == polypathy[polypathy.length-1]){
  	sendpath(doing,polypathx,polypathy);
  }
  else{
  	polypathx.push(client_x);
  	polypathy.push(client_y);
  }
  redrawPolygon();
  if(doing == "polygonquery"){polygonarea(evt)};
}
	
function deletepolygon(){
	c = polypathx.length;
	for(i = 0; i < c; i++){
  	polypathx.pop();
  	polypathy.pop();
	}	
}			
		
function redrawPolygon(){
	// punktepfad erstellen
  polypath = "";
  for(var i = 0; i < polypathx.length; ++i){
  	polypath = polypath+" "+polypathx[i]+","+polypathy[i];
	}	
	if(polypathx.length > 0){
		polypath = polypath+" "+polypathx[0]+","+polypathy[0];
	}
  // polygon um punktepfad erweitern
	if(doing == "polygonquery"){
  	document.getElementById("polygon").setAttribute("points", polypath);
	}
	if(doing == "drawpolygon"){
		current_freepolygon.setAttribute("points", polypath);
	}
}		
		
//--------------- Polygon zeichnen ------------------

//---------------- Flaeche messen --------------------

		
function polygonarea(evt){
  // Flaecheninhalt eines Polygons nach Gauss
  var parea = 0,parts = 0;
	if(polypathx.length > 2){
		for(var j = 1; j < polypathx.length-1; ++j){
	 		parts 	= parts + (polypathx[j]*(polypathy[j+1]-polypathy[j-1]));
		}
		parts = parts + (polypathx[polypathx.length-1]*(polypathy[0]-polypathy[polypathx.length-2])) + (polypathx[0]*(polypathy[1]-polypathy[polypathx.length-1]));
		parea	= 0.5 * Math.sqrt(parts*parts);
		hidetooltip(evt);
		area = parea*parseFloat(top.document.GUI.pixelsize.value)*parseFloat(top.document.GUI.pixelsize.value);			
		area = top.format_number(area, false);
		show_tooltip("Fl"+unescape("%E4")+"cheninhalt: "+area+" m"+unescape("%B2")+" "+unescape("%A0"),  evt.clientX, evt.clientY);
		return;
	}
}

//-------------------------------------------------------
		

// ----------------------------strecke messen---------------------------------

top.document.getElementById("vertices").SVGtoggle_vertices = toggle_vertices;		// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen

function toggle_vertices(){
	if(top.document.GUI.punktfang.checked){
		add_vertices();
	}
	else{
		remove_vertices();
	}
}

function add_vertices(){
	get_vertices_loop = window.setInterval("get_vertices()", 200);
	top.ahah("'.URL.APPLVERSION.'index.php", "go=getSVG_vertices&scale="+top.document.getElementById("scale").value, new Array(top.document.GUI.vertices), "setvalue");
}

function get_vertices(){
	if(top.document.GUI.vertices.value != ""){
		window.clearInterval(get_vertices_loop);
		var parent = document.getElementById("vertices");
		circle = new Array();
		kreis1 = document.getElementById("kreis");
		vertex_string = top.document.GUI.vertices.value+"";
		top.document.GUI.vertices.value = "";
		vertices = vertex_string.split("|");
		for(i = 0; i < vertices.length-1; i++){
			circle[i] = kreis1.cloneNode(true);
			coords = vertices[i].split(" ");
			circle[i].setAttribute("x", coords[0]);
			circle[i].setAttribute("y", coords[1]);
			coords[0] = Math.round((coords[0] - parseFloat(top.document.GUI.minx.value))/parseFloat(top.document.GUI.pixelsize.value));
			coords[1] = Math.round((coords[1] - top.document.GUI.miny.value)/parseFloat(top.document.GUI.pixelsize.value));
			circle[i].setAttribute("cx", coords[0]);
			circle[i].setAttribute("cy", coords[1]);
			circle[i].setAttribute("style","fill: #FF0000");
			circle[i].setAttribute("id", "vertex_"+i);
			parent.appendChild(circle[i]);
		}
	}
}

function remove_vertices(){
	var parent = document.getElementById("vertices");
	var count = parent.childNodes.length;
	for(i = 0; i < count-2; i++){
		parent.removeChild(parent.lastChild);
	}
}

function activate_vertex(evt){
	if(doing == "measure"){
		evt.target.setAttribute("opacity", "1");
	}
}

function deactivate_vertex(evt){
	if(doing == "measure"){
		evt.target.setAttribute("opacity", "0.1");
	}
}

function add_vertex(evt){
	if(doing == "measure"){
		if(!measuring){
			restart();	
			measuring = true;
		}
		pathx.push(evt.target.getAttribute("cx"));
		pathy.push(evt.target.getAttribute("cy"));
		pathx_world.push(top.format_number(evt.target.getAttribute("x"), false));
		pathy_world.push(top.format_number(evt.target.getAttribute("y"), false));
	  redrawPL();
	}
}

function startMeasure(evt) {
  restart();
  measuring = true;
  // neuen punkt abgreifen
	pathx[0] = evt.clientX;
	pathy[0] = resy - evt.clientY;
	pathx_world[0] = top.format_number(evt.clientX*parseFloat(top.document.GUI.pixelsize.value) + parseFloat(top.document.GUI.minx.value), false);
	pathy_world[0] = top.format_number(top.document.GUI.maxy.value - evt.clientY*parseFloat(top.document.GUI.pixelsize.value), false);
}

function showMeasurement(evt){
  addpoint(evt);
  var track = 0, track0 = 0, part0 = 0, parts = 0, output = "";
  for(var j = 0; j < pathx_world.length-1; ++j){
    part0 = parts;
    parts = parts + Math.sqrt(((pathx_world[j]-pathx_world[j+1])*(pathx_world[j]-pathx_world[j+1]))+((pathy_world[j]-pathy_world[j+1])*(pathy_world[j]-pathy_world[j+1])));
  }
  track0 = top.format_number(part0, false);
  track = top.format_number(parts, false);
  output = "Strecke: "+track+" m ("+track0+" m)";
  show_tooltip(output, evt.clientX, evt.clientY);
  deletelast(evt);
}

function addpoint(evt){
  // neuen eckpunkt abgreifen
	pathx.push(evt.clientX);
	pathy.push(resy - evt.clientY);
	pathx_world.push(top.format_number(evt.clientX*parseFloat(top.document.GUI.pixelsize.value) + parseFloat(top.document.GUI.minx.value), false));
	pathy_world.push(top.format_number(top.document.GUI.maxy.value - evt.clientY*parseFloat(top.document.GUI.pixelsize.value), false));
  redrawPL();
}

function deletelast(evt) {
  pathx.pop();
  pathy.pop();
	pathx_world.pop();
  pathy_world.pop();
}

function restart(){
	var alle = pathx.length;
	for(var i = 0; i < alle; ++i){
    pathx.pop();
    pathy.pop();
		pathx_world.pop();
  	pathy_world.pop();
	}
  redrawPL();
}

function redrawPL(){
  // punktepfad erstellen
  path = "";
  for(var i = 0; i < pathx.length; ++i){
  	path = path+" "+pathx[i]+","+pathy[i];
	}
  // polygon um punktepfad erweitern
  document.getElementById("polyline").setAttribute("points", path);
}

function show_coords(evt){
	coorx = evt.clientX*parseFloat(top.document.GUI.pixelsize.value) + parseFloat(top.document.GUI.minx.value);
	coory = top.document.GUI.maxy.value - evt.clientY*parseFloat(top.document.GUI.pixelsize.value);
	if(top.document.GUI.secondcoords != undefined)top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&curSRID='.$this->user->rolle->epsg_code.'&newSRID='.$this->user->rolle->epsg_code2.'&point="+coorx+" "+coory+"&operation=transformPoint&resulttype=wkt&coordtype='.$this->user->rolle->coordtype.'", new Array(top.document.GUI.secondcoords), "");
	coorx = top.format_number(coorx, true);
	coory = top.format_number(coory, true);
	top.document.GUI.firstcoords.value = coorx+" "+coory; 
	top.document.getElementById("showcoords").style.display="";
}

// ----------------------------punkt setzen---------------------------------
function selectPoint(evt) {
  cmd = doing;
  // neuen punkt abgreifen
  pathx[0] = evt.clientX;
  pathy[0] = evt.clientY;
  sendpath(cmd,pathx,pathy);
}

// ----------------------------box aufziehen---------------------------------
function startPoint(evt) {
  dragging  = true;
  var alle = pathx.length;
	top.document.GUI.INPUT_COORD.value  = boxx+" "+alle;
  for(var i = 0; i < alle; ++i){
    pathx.pop();
    pathy.pop();
    boxx.pop();
    boxy.pop();
  }
  // neuen punkt abgreifen
  clientx = evt.clientX;
  clienty = resy - evt.clientY;
  pathx.push(clientx);
  pathy.push(clienty);
  redraw();
  
  clientx = evt.clientX;
  clienty = evt.clientY;
  boxx.push(clientx);
  boxy.push(clienty);
}

function movePoint(evt) {
if (!dragging) return;
  // neuen punkt abgreifen
  clientx = evt.clientX;
  clienty = resy - evt.clientY;
  pathx[1]  = pathx[0];
  pathy[1]  = clienty;
  pathx[2]  = clientx;
  pathy[2]  = clienty;
  pathx[3]  = clientx;
  pathy[3]  = pathy[0];
  redraw();
  
  clientx = evt.clientX;
  clienty = evt.clientY;
  boxx[1]  = boxx[0];
  boxy[1]  = clienty;
  boxx[2]  = clientx;
  boxy[2]  = clienty;
  boxx[3]  = clientx;
  boxy[3]  = boxy[0];
  dragdone = true;
}

function endPoint(evt) {
  if (!dragging) return;
  cmd = doing;
  if(!dragdone){ 
    cmd  = cmd+"_point";
	}
  else{
    cmd  = cmd+"_box";
    // Reihenfolge pruefen
    checkOrder(cmd,boxx,boxy);
  }
  dragging  = false;
  dragdone  = false;
  // hiddenformvars aktualisieren
  sendpath(cmd,boxx,boxy);
}

// ----------------------------vektor aufziehen---------------------------------
function startMove(evt) {
  moving  = true;
  var alle = pathx.length;
  for(var i = 0; i < alle; ++i)
   {
    pathx.pop();
    pathy.pop();
    boxx.pop();
    boxy.pop();
   }
  // neuen punkt abgreifen
  pathx[0] = evt.clientX;
  pathy[0] = evt.clientY;
}

function moveVector(evt) {
if (!moving) return;
  // neuen punkt abgreifen
  pathx[1] = evt.clientX;
  pathy[1] = evt.clientY;
  moveMap();
  moved = true;
}

function moveMap(){
  //kartenausschnitt verschieben
  move_x = pathx[1]-pathx[0];
  move_y = pathy[1]-pathy[0];
  path = "translate("+move_x+" "+move_y+")";
  document.getElementById("moveGroup").setAttribute("transform", path);
}

function endMove(evt) {
  if (!moving) return;
  cmd = doing;
  if (moved){ 
    pathx[0]=resx_m-move_x;
    pathy[0]=resy_m-move_y;
    // alert("moving="+moving+" pathx="+pathx[0]+" pathy="+pathy[0]);
  } 
  moving  = false;
  moved  = false;
  // hiddenformvars aktualisieren
  sendpath(cmd,pathx,pathy);
}

// -----------------------kl. koordinatenpaar zuerst---------------------------
function checkOrder(cmd,boxx,boxy) {
  temp=0;
  if (boxx[2]<boxx[0]) {temp=boxx[0];boxx[0]=boxx[2];boxx[2]=temp;}
  if (boxy[2]<boxy[0]) {temp=boxy[0];boxy[0]=boxy[2];boxy[2]=temp;}
}

function redraw() 
{
  // punktepfad erstellen
  path = "";
  for(var i = 0; i < pathx.length; ++i)
   {
    path = path+" "+pathx[i]+","+pathy[i];
   }
  // polygon um punktepfad erweitern
  document.getElementById("polygon").setAttribute("points", path);
}

// ----------------------ausgewaehlten button highlighten---------------------------
function highlight(evt){
  if(document.getElementById("ppquery0") != undefined){document.getElementById("ppquery0").style.setProperty("fill","ghostwhite","");}
  if(document.getElementById("previous0") != undefined){document.getElementById("previous0").style.setProperty("fill","ghostwhite","");}
  if(document.getElementById("next0") != undefined){document.getElementById("next0").style.setProperty("fill","ghostwhite","");}
  if(document.getElementById("measure0") != undefined){document.getElementById("measure0").style.setProperty("fill","ghostwhite","");}
  if(document.getElementById("zoomin0") != undefined){document.getElementById("zoomin0").style.setProperty("fill","ghostwhite","");}
  if(document.getElementById("zoomout0") != undefined){document.getElementById("zoomout0").style.setProperty("fill","ghostwhite","");}
  if(document.getElementById("recentre0") != undefined){document.getElementById("recentre0").style.setProperty("fill","ghostwhite","");}
  if(document.getElementById("pquery0") != undefined){document.getElementById("pquery0").style.setProperty("fill","ghostwhite","");}
	if(document.getElementById("polygonquery0") != undefined){document.getElementById("polygonquery0").style.setProperty("fill","ghostwhite","");}
	if(document.getElementById("touchquery0") != undefined){document.getElementById("touchquery0").style.setProperty("fill","ghostwhite","");}
	if(document.getElementById("freepolygon0") != undefined){document.getElementById("freepolygon0").style.setProperty("fill","ghostwhite","");}
	if(document.getElementById("freetext0") != undefined){document.getElementById("freetext0").style.setProperty("fill","ghostwhite","");}
	if(document.getElementById("freearrow0") != undefined){document.getElementById("freearrow0").style.setProperty("fill","ghostwhite","");}
	if(document.getElementById("coords0") != undefined){document.getElementById("coords0").style.setProperty("fill","ghostwhite","");}
  evt.target.style.setProperty("fill",highlighted,"");
  document.getElementById("suchkreis").setAttribute("cx", -10000);
	if(top.document.GUI.punktfang != undefined){
		remove_vertices();
		options1 = top.document.getElementById("options").innerHTML="";
	}
}



// ----------------------koordinatenausgabe in statuszeile---------------------------
'.$SVGvars_coordscript.'

// -------------------------tooltip-ausgabe fuer buttons------------------------------
'.$SVGvars_tooltipscript.'

// -------------------------querytooltip------------------------------
'.$SVGvars_querytooltipscript.'

]]></script>

  <defs>
'.$SVGvars_defs.'	
  </defs> 
  <rect id="background" style="fill:white" width="100%" height="100%"/>
  <g id="moveGroup" transform="translate(0 0)">
    <image id="mapimg" xlink:href="'.$bg_pic.'" height="100%" width="100%" y="0" x="0"/>
    <g id="cartesian" transform="translate(0,'.$res_y.') scale(1,-1)">
      <polygon points="" id="polygon" style="opacity:0.25;fill:yellow;stroke:black;stroke-width:2"/>
			<path d="" id="highlight" style="fill:none;stroke:blue;stroke-width:2"/>
      <polyline points="" id="polyline" style="fill:none;stroke-dasharray:2,2;stroke:black;stroke-width:4"/>
      <circle id="suchkreis" cx="-100" cy="-100" r="'.$radius.'" style="fill-opacity:0.25;fill:yellow;stroke:grey;stroke-width:2"/>
			<g id="redlining">
			</g>';
if($_SESSION['mobile'] == 'true'){
	 $svg.=' <use id="gps_position" xlink:href="#crosshair_red" x="-100" y="-100"/>';
}
$svg.='
    </g>
  </g>
	<g id="waitingimage" style="visibility:hidden" transform="translate('.$res_xm.', '.$res_ym.') scale(0.3 0.3)">
		<g>
	    <line id="line" x1="-165" y1="0" x2="-115" y2="0" stroke="#111" stroke-width="30" style="stroke-linecap:round"/>
	    <use xlink:href="#line" transform="rotate(30,0,0)" style="opacity:.0833"/>
	    <use xlink:href="#line" transform="rotate(60,0,0)" style="opacity:.166"/>
	    <use xlink:href="#line" transform="rotate(90,0,0)" style="opacity:.25"/>
	    <use xlink:href="#line" transform="rotate(120,0,0)" style="opacity:.3333"/>
	    <use xlink:href="#line" transform="rotate(150,0,0)" style="opacity:.4166"/>
	    <use xlink:href="#line" transform="rotate(180,0,0)" style="opacity:.5"/>
	    <use xlink:href="#line" transform="rotate(210,0,0)" style="opacity:.5833"/>
	    <use xlink:href="#line" transform="rotate(240,0,0)" style="opacity:.6666"/>
	    <use xlink:href="#line" transform="rotate(270,0,0)" style="opacity:.75"/>
	    <use xlink:href="#line" transform="rotate(300,0,0)" style="opacity:.8333"/>
	    <use xlink:href="#line" transform="rotate(330,0,0)" style="opacity:.9166"/>
	    
	    <animateTransform attributeName="transform" attributeType="XML" type="rotate" begin="0s" dur="1s" repeatCount="indefinite" calcMode="discrete"
	    keyTimes="0;.0833;.166;.25;.3333;.4166;.5;.5833;.6666;.75;.8333;.9166;1"
	    values="0,0,0;30,0,0;60,0,0;90,0,0;120,0,0;150,0,0;180,0,0;210,0,0;240,0,0;270,0,0;300,0,0;330,0,0;360,0,0"/>
    </g>
  </g>
  <g id="mapimg2_group">
  	<image id="mapimg2" xlink:href="" height="100%" width="100%" y="0" x="0"/>
  </g>
	
  <rect id="canvas" cursor="crosshair" onmousedown="mousedown(evt)" onmousemove="mousemove(evt);" onmouseup="mouseup(evt);" width="100%" height="100%" opacity="0"/>
		<g id="vertices" transform="translate(0,'.$res_y.') scale(1,-1)">
			<circle id="kreis" cx="-500" cy="-500" r="7" opacity="0.1" onmouseover="activate_vertex(evt)" onmouseout="deactivate_vertex(evt)" onmousedown="add_vertex(evt)" />
		</g>
    <g id="buttons" onmouseout="hide_tooltip()" onmousemove="get_bbox();" onmousedown="hide_tooltip()" cursor="pointer">
'.$SVGvars_mainnavbuttons.'
    </g>
		<g id="tooltipgroup">
    	<rect id="frame" width="0" height="20" rx="5" ry="5" style="fill-opacity:0.8;fill:rgb(255,255,215);stroke:rgb(0,0,0);stroke-width:1.5"/>
    	<text id="querytooltip" x="100" y="100" style="text-anchor:start;fill:rgb(0,0,0);stroke:none;font-size:10px;font-family:Arial;font-weight:bold"></text>
			<g id="tooltipcontent">
			</g>	
    </g>

'.$SVGvars_tooltipblank.'
</svg>';

#
# erstellen der SVG
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
#echo '<EMBED align="center" SRC="'.TEMPPATH_REL.$svgfile.'" TYPE="image/svg+xml" width="'.$res_x.'" height="'.$res_y.'" PLUGINSPAGE="http://www.adobe.com/svg/viewer/install/"/>';
# echo '<iframe src="'.TEMPPATH_REL.$svgfile.'" width="'.$res_x.'" height="'.$res_y.'" name="map"></iframe>';
echo '<script src="funktionen/Embed.js" language="JavaScript" type="text/javascript"></script>';
?>