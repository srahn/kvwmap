<?php
 # 2008-01-24 pkvvm
  include(LAYOUTPATH.'languages/SVG_Utilities_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
 ?>
 
<script language="JavaScript">

	function update_geometry(){
		document.getElementById("svghelp").SVGupdate_geometry();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
	}
	
	function show_foreign_vertices(){
		document.getElementById("svghelp").SVGshow_foreign_vertices();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
	}

</script>
 
<?php

	include(LAYOUTPATH.'snippets/SVGvars_navbuttons.php'); 		# zuweisen von: $SVGvars_navbuttons
	include(LAYOUTPATH.'snippets/SVGvars_defs.php'); 					# zuweisen von: $SVGvars_defs
	include(LAYOUTPATH.'snippets/SVGvars_coordscript.php'); 	# zuweisen von: $SVGvars_coordscript
	include(LAYOUTPATH.'snippets/SVGvars_tooltipscript.php');	# zuweisen von: $SVGvars_tooltipscript
	include(LAYOUTPATH.'snippets/SVGvars_tooltipblank.php');	# zuweisen von: $SVGvars_tooltipblank
	$bg_pic   = $this->img['hauptkarte'];
	$res_x    = $this->map->width;
	$res_y    = $this->map->height;
	$res_xm   = $this->map->width/2;
	$res_ym   = $this->map->height/2;
	$dx       = $this->map->extent->maxx-$this->map->extent->minx;
	$dy       = $this->map->extent->maxy-$this->map->extent->miny;
	$pixelsize    = ($dx/$res_x+$dy/$res_y)/2;
	
	if($this->user->rolle->always_draw == '')$this->user->rolle->always_draw = ALWAYS_DRAW; 
	$always_draw = $this->user->rolle->always_draw;
	
	if($this->geomload)$geomload = 'true';
	else $geomload = 'false';

	#
	# Positionsanzeigetext ausserhalb der Anzeigeflaeche bei Start
	#
	if($this->formvars['loc_y']==0) {
		$text_x=-1000000;
		$text_y=-1000000;
	}
	else {
		$text_y=$this->formvars['loc_y'];
		$text_x=$this->formvars['loc_x'];
	}

	$SVG_begin ='<?xml version="1.0"?>
	<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
	  "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
	<svg width="'.$res_x.'" height="'.$res_y.'" zoomAndPan="enable" onload="startup()" onmousemove="top.coords_anzeige(evt)"
	  xmlns="http://www.w3.org/2000/svg" version="1.1"
	  xmlns:xlink="http://www.w3.org/1999/xlink">
	';

	$SVG_end = '
		'.$SVGvars_tooltipblank.'
			</g>
	</svg>';

	$scriptdefinitions ='
	var transformfunctions = false;
	var coord_input_functions = false;
	var bufferfunctions = false;
	var polygonfunctions = false;
	var flurstuecksqueryfunctions = false;
	var boxfunctions = false;
	var pointfunctions = false;
	var linefunctions = false;
	var measurefunctions = false;
	var dragging  = false;
	var path  = "";
	var path_second  = "";
	var pathx_second = new Array();
	var pathy_second = new Array();
	var poly_pathx_second = new Array();
	var poly_pathy_second = new Array();
	var pathx = new Array();
	var pathy = new Array();
	var m_pathx = new Array();
	var m_pathy = new Array();
	if(top.currentform.newpath.value){
		pathx = getxcoordsfromsvgpath(top.currentform.newpath.value);
		pathy = getycoordsfromsvgpath(top.currentform.newpath.value);
	}
	if(top.currentform.pathy_second.value != ""){
		str = top.currentform.pathx_second.value;
		pathx_second = str.split(";");
		str = top.currentform.pathy_second.value;
		pathy_second = str.split(";");
	}
	var textx = '.$text_x.';
	var texty = '.$text_y.';
	var newpath_undo = new Array();
	var newpathwkt_undo = new Array();

	var minx  = '.$this->user->rolle->oGeorefExt->minx.';
	var maxx  = '.$this->user->rolle->oGeorefExt->maxx.';
	var miny  = '.$this->user->rolle->oGeorefExt->miny.';
	var maxy  = '.$this->user->rolle->oGeorefExt->maxy.';
	var resx  = '.$res_x.';
	var resy  = '.$res_y.';
	var resx_m  = '.$res_xm.';
	var resy_m  = '.$res_ym.';
	var scale = '.$pixelsize.';
	var boxx 	= new Array();
	var boxy 	= new Array();
	var move_x 	= new Array();
	var move_y 	= new Array();
	var move_dx;
	var move_dy;
	var dragging  = false;
	var dragdone  = false;
	var draggingFS  = false;
	var moving  = false;
	var movinggeom  = false;
	var moved  = false;
	var highlighted  = "yellow";
	var must_redraw = false;
	var mobile = '.$_SESSION['mobile'].';
	var gps_follow_cooldown = 0;
	var selected_vertex;
	var last_selected_vertex;
	var vertex_old_world_x = "";
	var vertex_old_world_y = "";
	var vertex_new_world_x;
	var vertex_new_world_y;
	var vertex_moved = false;
	var time_mouse_down;
	var mouse_coords_type = "image";
	var measuring  = false;
	var deactivated_foreign_vertex = 0;
	var geomload = '.$geomload.';		// Geometrie wird das erste Mal geladen, diese Variable verhindert den Weiterzeichnenmodus
	var root = document.documentElement;
	var mousewheelloop = 0;
	var stopnavigation = false;
	var currentTheta = 0;
  var thetaDelta = 6; // The amount to rotate the square about every 16.7 milliseconds, in degrees.
	var requestAnimationFrameID;
	';

	$polygonANDpoint = '
	var polygonXORpoint = false;
	';

	$polygonXORpoint = '
	var	polygonXORpoint = true;
	';

	$SVGvars_navscript = '

	function submit(){
		top.currentform.stopnavigation.value = 1;
		document.getElementById("waitingimage").style.setProperty("visibility","visible", "");
		requestAnimationFrameID = requestAnimationFrame(doAnim); // Start the loop.
		top.overlay_submit(top.currentform, false);
	}
	
	function doAnim() {
		if(currentTheta%30 == 0)document.getElementById("waitingimage").setAttribute("transform", "translate('.$res_xm.', '.$res_ym.') scale(0.3 0.3) rotate(" + currentTheta + ")"); 
		currentTheta += thetaDelta;  
		requestAnimationFrameID = requestAnimationFrame(doAnim); 
	}
	
	if (!window.requestAnimationFrame){ 
		window.requestAnimationFrame = ( function(){
			return window.webkitRequestAnimationFrame ||
			window.mozRequestAnimationFrame ||
			window.oRequestAnimationFrame ||
			window.msRequestAnimationFrame ||
			function( /* function FrameRequestCallback */ callback, /* DOMElement Element */ element ) {
				window.setTimeout( callback, 1000 / 60 );
			};	 
		})();
	}
	
	function go_previous(){
	  document.getElementById("canvas").setAttribute("cursor", "wait");
	  top.currentform.CMD.value  = "previous";
	  submit();
	}
	
	function go_next(){
	  document.getElementById("canvas").setAttribute("cursor", "wait");
	  top.currentform.CMD.value  = "next";
	  submit();
	}	

	function zoomin(){
		top.currentform.last_doing2.value = top.currentform.last_doing.value;
		top.currentform.last_doing.value = "zoomin"; 
	  document.getElementById("canvas").setAttribute("cursor", "crosshair");
	}

	function zoomout(){
		top.currentform.last_doing2.value = top.currentform.last_doing.value;
		top.currentform.last_doing.value = "zoomout";
	  document.getElementById("canvas").setAttribute("cursor", "crosshair");
	}

	function zoomall(){
	  document.getElementById("canvas").setAttribute("cursor", "wait");
	  Full_Extent();
	}

	function recentre(){
		top.currentform.last_doing2.value = top.currentform.last_doing.value;
		top.currentform.last_doing.value = "recentre";
	  document.getElementById("canvas").setAttribute("cursor", "move");
		if(measurefunctions == true){
			save_measure_path();
		}
	}

	function measure(){
	  top.currentform.last_doing.value = "measure";
		if(top.currentform.str_pathx.value != ""){
			measuring = true;	
			top.currentform.str_pathx.value = "";
			top.currentform.str_pathy.value = "";
		}
		else{
	  	measuring = false;
	  	restart_m();
		}
	  document.getElementById("canvas").setAttribute("cursor", "crosshair");
	}

	// ----------------------------punkt setzen---------------------------------
	function selectPoint(clientx, clienty) {
	  cmd = top.currentform.last_doing.value;
	  // neuen punkt abgreifen
	  boxx[0] = clientx;
	  boxy[0] = resy - clienty;
	  sendpath(cmd,boxx,boxy);
	}

	// --------------------------pkt. setzen / box aufziehen------------------------------
	function startPoint(clientx, clienty) {
	  dragging  = true;

	  // zoomin-fensterfarbe anpassen
	  document.getElementById("polygon").style.setProperty("fill","#FF6", "");
	  document.getElementById("polygon").style.setProperty("stroke","grey", "");

	  var alle = boxx.length;
	  for(var i = 0; i < alle; ++i)
	   {
	    boxx.pop();
	    boxy.pop();
	   }
	  // neuen punkt hinzufuegen
	  boxx.push(clientx);
	  boxy.push(resy - clienty);
	  redraw();
	}

	function movePoint(evt) {
	  // neuen punkt abgreifen

	  clientx = evt.clientX;
	  clienty = evt.clientY;
	  boxx[1]  = boxx[0];
	  boxy[1]  = clienty;
	  boxx[2]  = clientx;
	  boxy[2]  = clienty;
	  boxx[3]  = clientx;
	  boxy[3]  = boxy[0];
	  redraw();
	  dragdone = true;
	}

	function endPoint(evt) {
	  cmd = top.currentform.last_doing.value;
	  if (!dragdone){
	  		cmd  = cmd+"_point";}
	  	else {
	  		cmd  = cmd+"_box";

		  // Reihenfolge pruefen
		  checkOrder(cmd,boxx,boxy);
			}
	  dragging  = false;
	  dragdone  = false;
	  sendpath(cmd,boxx,boxy);
	}

	// ----------------------------vektor aufziehen---------------------------------
	function startMove(clientx, clienty) {
	  moving  = true;
	  // neuen punkt setzen
	  move_x[0] = clientx;
	  move_y[0] = resy - clienty;
	}

	function moveVector(evt) {
	  // neuen punkt abgreifen
	  move_x[1] = evt.clientX;
	  move_y[1] = evt.clientY;
	  moveMap();
	  moved = true;
	}

	function moveMap()
	{
	  // transformation erstellen
	  move_dx = move_x[1]-move_x[0];
	  move_dy = move_y[1]-move_y[0];
	  path = "translate("+move_dx+" "+move_dy+")";

	  // kartenausschnitt verschieben
	  document.getElementById("moveGroup").setAttribute("transform", path);
	}

	function endMove(evt) {
	  cmd = top.currentform.last_doing.value;
	  if (moved){
	  	move_x[0]=resx_m-move_dx;
	  	move_y[0]=resy_m-move_dy;
			}
	  moving  = false;
	  moved  = false;
	  // hiddenformvars aktualisieren
	  sendpath(cmd,move_x,move_y);
	}

	// -----------------------kl. koordinatenpaar zuerst---------------------------
	function checkOrder(cmd,boxx,boxy) {
		temp=0;
		if (boxx[2]<boxx[0]) {temp=boxx[0];boxx[0]=boxx[2];boxx[2]=temp;}
		if (boxy[2]<boxy[0]) {temp=boxy[0];boxy[0]=boxy[2];boxy[2]=temp;}
	}

	function redraw() {
	  // punktepfad erstellen
	  box = "";
	  for(var i = 0; i < boxx.length; ++i)
	   {y = resy-boxy[i];
	    box = box+" "+boxx[i]+","+y;
	   }
	  document.getElementById("polygon").setAttribute("points", box);
	}
	';

	$basicfunctions = '

	function redrawpoint(){
		if(document.getElementById("pointposition")){
			var obj = document.getElementById("pointposition");
			pixel_coordx = (textx - minx) / scale;
			pixel_coordy = (texty - miny) / scale;
			if(pixel_coordy < 0){				// im Firefox fuehrten grosse negative Zahlen zum Absturz
				pixel_coordy = -1000;
				pixel_coordx = -1000;
			}
		  obj.setAttribute("x", pixel_coordx);
		  obj.setAttribute("y", pixel_coordy);
		}
	}

	function sendBWlocation(loc_x,loc_y) {
      top.currentform.loc_x.value    = loc_x;
      top.currentform.loc_y.value    = loc_y;
  }

  function sendBWpath(pathx,pathy) {
      top.currentform.pathlength.value   = pathx.length;
      top.currentform.pathx.value    = pathx;
      top.currentform.pathy.value    = pathy;
  }

  function Full_Extent()   {
      top.currentform.CMD.value  = "Full_Extent";
      submit();
  }
  function sendpath(cmd,navX,navY){
    // navX[0] enthaelt den Rechtswert des ersten gesetzte Punktes im Bild in Pixeln
    // von links nach rechts gerechnet
    // navY[0] enthaelt den Hochwert des ersten Punktes im Bild in Pixeln
    // allerdings von oben nach untern gerechnet
    // [2] jeweils den anderen Punkt wenn ein Rechteck uebergeben wurde
		top.currentform.action = "index.php#geoedit_anchor";
    switch(cmd) {
     case "zoomin_point":
      top.currentform.INPUT_COORD.value  = navX[0]+","+navY[0];
      top.currentform.CMD.value          = "zoomin";
      submit();
     break;
     case "zoomout":
      top.currentform.INPUT_COORD.value  = navX[0]+","+navY[0];
      top.currentform.CMD.value          = cmd;
      submit();
     break;
     case "zoomin_box":
      top.currentform.INPUT_COORD.value  = navX[0]+","+navY[0]+";"+navX[2]+","+navY[2];
      top.currentform.CMD.value          = "zoomin";
      submit();
     break;
     case "recentre":
      top.currentform.INPUT_COORD.value  = navX[0]+","+navY[0];
      top.currentform.CMD.value = cmd;
      submit();
     break;
     case "add_geom_box":
      top.currentform.INPUT_COORD.value  = navX[0]+","+navY[0]+";"+navX[2]+","+navY[2];
      top.currentform.CMD.value = cmd;
     break;
		 case "subtract_geom_box":
      top.currentform.INPUT_COORD.value  = navX[0]+","+navY[0]+";"+navX[2]+","+navY[2];
      top.currentform.CMD.value = cmd;
     break;
     case "add_geom_point":
      top.currentform.INPUT_COORD.value  = navX[0]+","+navY[0]+";"+navX[0]+","+navY[0];
      top.currentform.CMD.value = cmd;
     break;
		 case "subtract_geom_point":
      top.currentform.INPUT_COORD.value  = navX[0]+","+navY[0]+";"+navX[0]+","+navY[0];
      top.currentform.CMD.value = cmd;
     break;
     default:
      alert("Keine Bearbeitung moeglich! \nUebergebene Daten: "+cmd+", "+navX[0]+","+navY[0]);
     break;
    }
  }

  function updatepaths(){
  	if(top.currentform.result.value != "" && top.currentform.result.value != " "){
	  	result = ""+top.currentform.result.value;
	  	paths = result.split("||");
	  	if(paths[1] == "GEOMETRYCOLLECTION EMPTY" || paths[1] == ""){
  			paths[0] = "";
  			paths[1] = "";
				if(polygonfunctions == true){
  				top.currentform.firstpoly.value = false;
  				top.currentform.secondpoly.value = false;
  				restart();
				}
				if(linefunctions == true){
  				top.currentform.firstline.value = false;
  				top.currentform.secondline.value = false;
  				restartline();
				}
	  	}
	  	top.currentform.newpath.value = paths[0];
	  	top.currentform.newpathwkt.value = paths[1];
	  	top.currentform.result.value = "";
			must_redraw = true;
	  	if(polygonfunctions == true){
	  		polygonarea();
	  	}
			if(linefunctions == true){
				linelength();
				if(paths[1].search(/MULTI.+/) != -1){
	  			top.currentform.split.style.visibility = "visible";
				}
				else{
					top.currentform.split.style.visibility = "hidden";
				}
				if(top.currentform.last_doing.value == "split_lines"){
					split_lines();
				}
	  	}
  	}
 	}

 	function mousewheelzoom(){
		top.currentform.last_doing2.value = top.currentform.last_doing.value;
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
		if(top.currentform.stopnavigation.value == 0){
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
	    		p.y = p.y - (top.document.body.clientHeight - resy)/2;
		}
		return p;
	}
 	
	function startup(){		
		if(window.addEventListener){
			window.addEventListener(\'mousewheel\', mousewheelchange, false); // Chrome/Safari//IE9
  		window.addEventListener(\'DOMMouseScroll\', mousewheelchange, false);		//Firefox
		}
		else{	
			top.document.getElementById("map").onmousewheel = mousewheelchange;		// <=IE8
		}
		if(measurefunctions == true){
			get_measure_path();
			redrawPL();
		}
		if(mobile == true){
			update_gps_position();
		}
		if(polygonfunctions == true){
			if(top.currentform.always_draw.checked && !geomload){		// "weiterzeichnen"
				top.currentform.last_button.value = "pgon0";
				if(top.currentform.secondpoly.value == "started" || top.currentform.secondpoly.value == "true"){	// am zweiten Polygon oder an einer gepufferten Linie wird weitergezeichnet
					if(top.currentform.last_doing2.value == "add_buffered_line")top.currentform.last_button.value = "buffer1";
					if(top.currentform.last_doing2.value == "add_parallel_polygon")top.currentform.last_button.value = "buffer2";
					if(top.currentform.last_doing2.value == "subtract_polygon")top.currentform.last_button.value = "pgon_subtr0";
					top.currentform.last_doing.value = top.currentform.last_doing2.value;
					if(pathx_second.length == 1){				// ersten Punkt darstellen
						document.getElementById("startvertex").setAttribute("cx", (pathx_second[0]-minx)/scale);
						document.getElementById("startvertex").setAttribute("cy", (pathy_second[0]-miny)/scale);
					}
				}
				else{																												// am ersten Polygon wird weitergezeichnet
					top.currentform.last_doing.value = "draw_polygon";
					if(pathx.length == 1){							// ersten Punkt darstellen
						document.getElementById("startvertex").setAttribute("cx", (pathx[0]-minx)/scale);
						document.getElementById("startvertex").setAttribute("cy", (pathy[0]-miny)/scale);
					}
				}
			}
			else{			// bei nicht "weiterzeichnen" wird alles vom zweiten Polygon geloescht
				var alles = pathx_second.length;
				for(var i = 0; i < alles; ++i){
					pathx_second.pop();
					pathy_second.pop();
				}
				top.currentform.pathx_second.value = "";
				top.currentform.pathy_second.value = "";
				if(top.currentform.firstpoly.value == "true" && top.currentform.last_doing.value == "draw_polygon"){
					top.currentform.last_doing.value = "draw_second_polygon";
				}
			}
		}
		if(linefunctions == true){
			if(top.currentform.always_draw.checked && !geomload){
				top.currentform.last_button.value = "line0";
				if(top.currentform.secondline.value == "true"){
					top.currentform.last_doing.value = "draw_second_line";
				}
				else{
					top.currentform.last_doing.value = "draw_line";
				}
			}
			else{
				var alles = pathx_second.length;
				for(var i = 0; i < alles; ++i){
					pathx_second.pop();
					pathy_second.pop();
				}
				top.currentform.pathx_second.value = "";
				top.currentform.pathy_second.value = "";
				if(top.currentform.firstline.value == "true" && top.currentform.last_doing.value == "draw_line"){
					top.currentform.last_doing.value = "draw_second_line";
				}
			}
		}
		fachschale();
		if(polygonfunctions == true){
			path = top.currentform.newpath.value;
			if(top.currentform.pathwkt.value != ""){
				top.currentform.firstpoly.value = true;
			}
			//top.currentform.secondpoly.value = false;
			redrawfirstpolygon();
			polygonarea();
		}
		if(linefunctions == true){
			redrawfirstline();
			linelength();
		}
		redrawpoint();
	}

	function focus_NAV(){
		// --------------- NAV-canvas aktivieren! ---------------------
	  document.getElementById("canvas_FS").setAttribute("visibility", "hidden");
	  document.getElementById("canvas").setAttribute("visibility", "visible");
		// --------------- FS-leiste ohne highlight ---------------------
	 		document.getElementById("text0").style.setProperty("fill","ghostwhite", "");
	//  document.getElementById("pgon0").style.setProperty("fill","ghostwhite", "");
	//  document.getElementById("pgon1").style.setProperty("fill","ghostwhite", "");
	//  document.getElementById("pgon2").style.setProperty("fill","ghostwhite", "");
	//	var obj = document.getElementById("text0");
	//	obj.setAttributeNS(null,"fill","none");
	//	var obj = document.getElementById("pgon0");
	//	obj.setAttributeNS(null,"fill","none");
	//	var obj = document.getElementById("pgon1");
	//	obj.setAttributeNS(null,"fill","none");
	//	var obj = document.getElementById("pgon2");
	//	obj.setAttributeNS(null,"fill","none");
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
	  if(top.currentform.stopnavigation.value == 0){
		if(mouse_coords_type == "image"){					// Bildkoordinaten (Standardfall)
			client_x = evt.clientX;
	  	client_y = resy - evt.clientY;
	  	world_x = (client_x * scale) + minx;
	  	world_y = (client_y * scale) + miny;
		}
		else{																		// Weltkoordinaten (bei GPS)
			world_x = evt.clientX;
			world_y = evt.clientY; 
		}

	  switch(top.currentform.last_doing.value){
			case "zoomin":
	  		startPoint(client_x, client_y);
			break;
			case "zoomout":
				selectPoint(client_x, client_y);
			break;
			case "recentre":
				startMove(client_x, client_y);
			break;
			case "pquery":
				startPoint(client_x, client_y);
			break;

			case "draw_point":
	 			choose(world_x, world_y);
	 			redrawpoint();
			break;
			case "draw_box":
	 			startpointFS(world_x, world_y);
			break;
			case "draw_line":
				addlinepoint_first(world_x, world_y);
				redrawfirstline();
			break;
			case "draw_second_line":
				addlinepoint_second(world_x, world_y);
				if(top.currentform.secondline.value == "true"){
					top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&path1="+top.currentform.pathwkt.value+"&path2="+path_second+"&operation=add&geotype=line&resulttype=svgwkt&layer_id="+top.currentform.layer_id.value, new Array(top.currentform.result, ""), new Array("setvalue", "execute_function"));
				}
				redrawsecondline();
			break;
			case "delete_lines":
				addpoint_second(world_x, world_y);
				if(top.currentform.secondpoly.value == "true"){
					top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&path1="+top.currentform.pathwkt.value+"&path2="+path_second+"&operation=subtract&resulttype=svgwkt&layer_id="+top.currentform.layer_id.value, new Array(top.currentform.result, ""), new Array("setvalue", "execute_function"));
				}
				redrawsecondline();
			break;
			case "split_lines":
				addlinepoint_second(world_x, world_y);
				if(top.currentform.secondline.value == "true"){
					top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&geotype=line&path1="+top.currentform.pathwkt.value+"&path2="+path_second+"&operation=subtract&resulttype=svgwkt&layer_id="+top.currentform.layer_id.value, new Array(top.currentform.result, ""), new Array("setvalue", "execute_function"));
				}
				redrawsecondline();
			break;

			case "draw_polygon":
				addpoint_first(world_x, world_y);
				redrawfirstpolygon();
			break;
			case "draw_second_polygon":
				addpoint_second(world_x, world_y);
				if(top.currentform.secondpoly.value == "true"){
					top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&path1="+top.currentform.pathwkt.value+"&path2="+path_second+"&operation=add&resulttype=svgwkt&layer_id="+top.currentform.layer_id.value, new Array(top.currentform.result, ""), new Array("setvalue", "execute_function"));
				}
			break;
			case "subtract_polygon":
				addpoint_second(world_x, world_y);
				if(top.currentform.secondpoly.value == "true"){
					top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&path1="+top.currentform.pathwkt.value+"&path2="+path_second+"&operation=subtract&resulttype=svgwkt&layer_id="+top.currentform.layer_id.value, new Array(top.currentform.result, ""), new Array("setvalue", "execute_function"));
				}
			break;
			case "add_geom":
				startPoint(client_x, client_y);
			break;
			case "subtract_geom":
				startPoint(client_x, client_y);
			break;
			case "vertex_edit":				// nix machen
			break;
			case "add_buffered_line":
				addlinepoint_second(world_x, world_y);
				top.currentform.firstpoly.value = "true";
				top.currentform.secondpoly.value = "true";
				top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&path1="+top.currentform.pathwkt.value+"&path2="+path_second+"&operation=add_buffered_line&width="+top.currentform.bufferwidth.value+"&geotype=line&resulttype=svgwkt&layer_id="+top.currentform.layer_id.value, new Array(top.currentform.result, ""), new Array("setvalue", "execute_function"));
			break;
			case "add_parallel_polygon":
				addlinepoint_second(world_x, world_y);
				if(pathx_second.length > 1){
					top.currentform.firstpoly.value = "true";
					top.currentform.secondpoly.value = true;
					top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&path1="+top.currentform.pathwkt.value+"&path2="+path_second+"&operation=add_parallel_polygon&width="+top.currentform.bufferwidth.value+"&geotype=line&resulttype=svgwkt&layer_id="+top.currentform.layer_id.value, new Array(top.currentform.result, ""), new Array("setvalue", "execute_function"));
				}				
			break;
			case "move_geometry":
				startMoveGeom(client_x, client_y);
			break;

			case "measure":
		    if (measuring){
		      addpoint(client_x, client_y);
		    }
		    else {
		      startMeasure(client_x, client_y);
		    }
			break;

			default:
				alert("Fehlerhafte Eingabe! \nUebergebene Daten: "+top.currentform.last_doing.value);
			break;
		}
		if(polygonfunctions){
			redrawsecondpolygon();
		}
	}
  }


function mousemove(evt){
	if(deactivated_foreign_vertex != 0){		// wenn es einen deaktivierten foreign vertex gibt, wird dieser jetzt wieder aktiviert
		document.getElementById(deactivated_foreign_vertex).setAttribute("pointer-events", "auto");
		deactivated_foreign_vertex = 0;
	}
	if(top.currentform.last_doing.value == "vertex_edit" && selected_vertex != undefined && selected_vertex != ""){
		move_vertex(evt, selected_vertex, "image");
	}
	if(top.currentform.last_doing.value == "split_lines" && pathx_second.length < 2){
		client_x = evt.clientX;
  	client_y = resy - evt.clientY;
  	world_x = (client_x * scale) + minx;
  	world_y = (client_y * scale) + miny;
		pathx_second.push(world_x);
	  pathy_second.push(world_y);
		path_second = buildsvglinepath(pathx_second, pathy_second);
		pixel_path_second = world2pixelsvg(path_second);
	  var obj = document.getElementById("line_second");
	  obj.setAttribute("d", pixel_path_second);
		pathx_second.pop();
		pathy_second.pop();
	} 
	else{
		if (dragging){
			movePoint(evt);
		}
		else{
			if (draggingFS){
	   		movepointFS(evt);
	  	}
			else{
				if (moving){
					moveVector(evt);
				}
				else{
					if(top.currentform.last_doing.value == "measure"){
			      if (measuring){
							client_x = evt.clientX;
	  					client_y = resy - evt.clientY;
			        showMeasurement(client_x, client_y);
			      }
			      else {
			      	show_tooltip(\'Startpunkt setzen\',evt.clientX,evt.clientY)
			      }
					}
					else{
						if(movinggeom){
							moveGeom(evt);
						}
					}
				}
			}
		}
	}
}

function mouseup(evt){
	if(dragging){
		endPoint(evt);
		top.currentform.secondpoly.value = "true";
		if(top.currentform.last_doing.value == "add_geom"){
			top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&path1="+top.currentform.pathwkt.value+"&input_coord="+top.currentform.INPUT_COORD.value+"&pixsize='.$pixelsize.'&operation=add_geometry&resulttype=svgwkt&fromwhere="+top.currentform.fromwhere.value+"&columnname="+top.currentform.columnname.value+"&layer_id="+top.currentform.layer_id.value,new Array(top.currentform.result, ""), new Array("setvalue", "execute_function"));
			if(polygonfunctions == true){
				top.currentform.firstpoly.value = "true";
			}
			else{
				top.currentform.firstline.value = "true";
			}
		}
		else{
			if(top.currentform.last_doing.value == "subtract_geom"){
				top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&path1="+top.currentform.pathwkt.value+"&input_coord="+top.currentform.INPUT_COORD.value+"&pixsize='.$pixelsize.'&operation=subtract_geometry&resulttype=svgwkt&fromwhere="+top.currentform.fromwhere.value+"&columnname="+top.currentform.columnname.value+"&layer_id="+top.currentform.layer_id.value, new Array(top.currentform.result, ""), new Array("setvalue", "execute_function"));
			}
		}
	}
	if(moving){
		endMove(evt);
	}
	if(draggingFS){
    endpointFS(evt);
  }
	if(movinggeom){
		endMoveGeom(evt);
	}
}

	// ----------------------ausgewaehlten button highlighten---------------------------

	function highlightbyid(id){
		if(id != ""){
			document.getElementById("previous0").style.setProperty("fill","ghostwhite", "");
			document.getElementById("next0").style.setProperty("fill","ghostwhite", "");
			document.getElementById("zoomin0").style.setProperty("fill","ghostwhite", "");
		  document.getElementById("zoomout0").style.setProperty("fill","ghostwhite", "");
		  document.getElementById("recentre0").style.setProperty("fill","ghostwhite", "");
		  if(polygonfunctions == true){
		  	document.getElementById("pgon0").style.setProperty("fill","ghostwhite", "");
		  	document.getElementById("undo0").style.setProperty("fill","ghostwhite", "");
		  	document.getElementById("new0").style.setProperty("fill","ghostwhite", "");
		  	document.getElementById("pgon_subtr0").style.setProperty("fill","ghostwhite", "");
				document.getElementById("vertex_edit1").style.setProperty("fill","ghostwhite", "");
				remove_vertices();
				remove_in_between_vertices();
		  }
			if(linefunctions == true){
				document.getElementById("undo0").style.setProperty("fill","ghostwhite", "");
		  	document.getElementById("new0").style.setProperty("fill","ghostwhite", "");
				document.getElementById("line0").style.setProperty("fill","ghostwhite", "");
				document.getElementById("del0").style.setProperty("fill","ghostwhite", "");
				document.getElementById("split0").style.setProperty("fill","ghostwhite", "");
				document.getElementById("vertex_edit1").style.setProperty("fill","ghostwhite", "");
				document.getElementById("ppquery0").style.setProperty("fill","ghostwhite", "");
		  	document.getElementById("ppquery1").style.setProperty("fill","ghostwhite", "");
				remove_vertices();
				remove_in_between_vertices();
			}
			if(coord_input_functions == true){
		  	document.getElementById("coord_input1").style.setProperty("fill","ghostwhite", "");
		  }
			if(transformfunctions == true){
		  	document.getElementById("move1").style.setProperty("fill","ghostwhite", "");
		  }
			if(bufferfunctions == true){
		  	document.getElementById("buffer0").style.setProperty("fill","ghostwhite", "");
				document.getElementById("buffer1").style.setProperty("fill","ghostwhite", "");
				document.getElementById("buffer2").style.setProperty("fill","ghostwhite", "");
		  }
		  if(flurstuecksqueryfunctions == true){
		  	document.getElementById("ppquery0").style.setProperty("fill","ghostwhite", "");
		  	document.getElementById("ppquery1").style.setProperty("fill","ghostwhite", "");
		  }
		  if(boxfunctions == true){
		  	document.getElementById("box0").style.setProperty("fill","ghostwhite", "");
		  }
		  if(pointfunctions == true){
		  	document.getElementById("text0").style.setProperty("fill","ghostwhite", "");
		  }
			if(measurefunctions == true){
				document.getElementById("measure0").style.setProperty("fill","ghostwhite", "");
			}
			document.getElementById(id).style.setProperty("fill",highlighted, "");
			top.currentform.last_button.value = id;
			if(id == "recentre0"){
				document.getElementById("canvas").setAttribute("cursor", "move");
			}
			else{
				document.getElementById("canvas").setAttribute("cursor", "crosshair");
			}
		}
	}

	function fachschale(){
		dragging  = false;
		dragdone  = false;
		moving  = false;
		moved  = false;
		highlightbyid(top.currentform.last_button.value);
		if(top.currentform.last_doing.value == "recentre"){
	  	document.getElementById("canvas").setAttribute("cursor", "move");
		}
		else{
	  	document.getElementById("canvas").setAttribute("cursor", "crosshair");
		}
	}
	
	function buildsvglinepath(pathx, pathy){
		svgpath = "M "+pathx[0]+" "+pathy[0];
		for(var i = 1; i < pathx.length; ++i){
	  	svgpath = svgpath+" "+pathx[i]+" "+pathy[i];
	 	}
	  return svgpath;
	}

	function buildsvgpath(pathx, pathy){
		svgpath = "M "+pathx[0]+" "+pathy[0];
		for(var i = 1; i < pathx.length; ++i){
	  	svgpath = svgpath+" "+pathx[i]+" "+pathy[i];
	 	}
	 	svgpath = svgpath+" "+pathx[0]+" "+pathy[0];
	  return svgpath;
	}
	
	function addlinepoint_second(worldx, worldy){
		// neuen punkt setzen
		top.currentform.lastcoordx.value = world_x;
		top.currentform.lastcoordy.value = world_y;
	  pathx_second.push(world_x);
	  pathy_second.push(world_y);
		if(top.currentform.pathx_second.value != ""){
			top.currentform.pathx_second.value = top.currentform.pathx_second.value+";"+world_x;
			top.currentform.pathy_second.value = top.currentform.pathy_second.value+";"+world_y;
		}
		else{
			top.currentform.pathx_second.value = world_x;
			top.currentform.pathy_second.value = world_y;
		}
		if(pathx_second.length == 1){
			document.getElementById("startvertex").setAttribute("cx", (world_x-minx)/scale);
			document.getElementById("startvertex").setAttribute("cy", (world_y-miny)/scale);
		}
		else{
			document.getElementById("startvertex").setAttribute("cx", -500);
			document.getElementById("startvertex").setAttribute("cy", -500);
		}
	  path_second = buildsvglinepath(pathx_second, pathy_second);
	  if(linefunctions && pathy_second.length > 1){
	  	top.currentform.secondline.value = true;
	  }
	}
	
	';


	$coord_input_functions = '

	coord_input_functions = true;

	function coord_input(){
		doing = top.currentform.last_doing.value;
		if(doing == "recentre" || doing == "zoomout" || doing == "zoomin"){
			if(polygonfunctions){
				add_polygon();
				highlightbyid(\'pgon0\');
			}
			else{
				if(linefunctions){
					add_line();
					highlightbyid(\'line0\');
				}
				else{
					draw_point();
					highlightbyid(\'text0\');
				}
			}
		}
		coord = prompt("Koordinateneingabe:", Math.round(minx+(maxx-minx)/2)+" "+Math.round(miny+(maxy-miny)/2))+"";
		coords1 = coord.split(" ");
		mouse_coords_type = "world";
		evt1 = new Object();
		evt1.clientX = coords1[0];
		evt1.clientY = coords1[1];
		mousedown(evt1);
		mouse_coords_type = "image";
		if(coords1[0] < minx || coords1[0] > maxx || coords1[1] < miny || coords1[1] > maxy){		// wenn Punkt ausserhalb des Kartenausschnittes -> hinzoomen
			pathx[0] = (coords1[0]-minx)/scale;
			pathy[0] = resy-((coords1[1]-miny)/scale);
			sendpath("recentre", pathx, pathy);
		}
	}

	';

	$pointfunctions = '

	pointfunctions = true;

	function draw_point() {
	  //document.getElementById("canvas_FS").setAttribute("cursor", "text");
	  document.getElementById("text0").style.setProperty("fill",highlighted, "");
	  if(polygonfunctions == true){
		 	if(top.currentform.secondpoly.value == "true"){
				applypolygons();
			}
	  }
	 	if(polygonXORpoint){
	 		restart();
	 	}
		top.currentform.last_doing.value = "draw_point";
	}

	// ------------------------texteinfuegepunkt setzen-----------------------------
	function choose(worldx, worldy) {
	  // neuen punkt setzen
		textx = worldx;
		texty = worldy;
	  sendBWlocation(worldx, worldy);
	}
	';

	$linefunctions = '

	linefunctions = true;

	top.document.getElementById("svghelp").SVGupdate_geometry = update_geometry;		// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen

	function update_geometry(){
		if(top.currentform.secondline.value == "true" || top.currentform.secondpoly.value == "true"){
			document.getElementById("cartesian").setAttribute("transform", "translate(0,'.$res_y.') scale(1,-1)");
			updatepaths();
			if(top.currentform.last_doing.value == "add_geom" || top.currentform.last_doing.value == "subtract_geom" || top.currentform.last_doing.value == "move_geometry"){
				if(top.currentform.pathwkt.value == ""){
					top.currentform.pathwkt.value = buildwktlinefromsvgpath(top.currentform.newpath.value);
				}
				else{
					top.currentform.pathwkt.value = top.currentform.newpathwkt.value;
				}
				if(top.currentform.secondline.value == "true" && must_redraw){
					applylines();
					must_redraw = false;
				}
				top.currentform.secondline.value = "true";
			}
			wktstring = top.currentform.newpathwkt.value + "";
			if(must_redraw){
				redrawsecondline();
				must_redraw = false;
			}
		}
	}

	function addlinepoint_first(worldx, worldy){
		// neuen punkt setzen
		top.currentform.lastcoordx.value = world_x;
		top.currentform.lastcoordy.value = world_y; 
	  pathx.push(world_x);
	  pathy.push(world_y);
		if(pathx.length == 1){
			document.getElementById("startvertex").setAttribute("cx", (world_x-minx)/scale);
			document.getElementById("startvertex").setAttribute("cy", (world_y-miny)/scale);
		}
		else{
			document.getElementById("startvertex").setAttribute("cx", -500);
			document.getElementById("startvertex").setAttribute("cy", -500);
		}
	  path = buildsvglinepath(pathx,pathy);
	  top.currentform.newpath.value = path;
	  if(pathy.length > 1){
	  	top.currentform.firstline.value = true;
	  	linelength();
	  }
	}

	function addpoint_second(worldx, worldy) {
	  // neuen punkt setzen
		top.currentform.lastcoordx.value = world_x;
		top.currentform.lastcoordy.value = world_y;
	  poly_pathx_second.push(world_x);
	  poly_pathy_second.push(world_y);
	  path_second = buildsvgpath(poly_pathx_second, poly_pathy_second);
	  if(poly_pathy_second.length > 2){
	  	top.currentform.secondpoly.value = true;
	  }
	}

	function redrawfirstline(){
		// Line um punktepfad erweitern
	  var obj = document.getElementById("line_first");
		pixel_path = world2pixelsvg(top.currentform.newpath.value);
	  obj.setAttribute("d", pixel_path);
	}
	
	function redrawsecondline(){
	 	// Line um punktepfad erweitern
	  var obj = document.getElementById("line_first");
	  pixel_path = world2pixelsvg(top.currentform.newpath.value);
	  obj.setAttribute("d", pixel_path);
	  pixel_path_second = world2pixelsvg(path_second);
	  var obj = document.getElementById("line_second");
	  obj.setAttribute("d", pixel_path_second);		
	}

	function add_line(){
		var alles = pathx_second.length;
		for(var i = 0; i < alles; ++i){
			pathx_second.pop();
			pathy_second.pop();
		}
		top.currentform.pathx_second.value = "";
		top.currentform.pathy_second.value = "";
		applylines();
		if(top.currentform.firstline.value == "true"){
			top.currentform.last_doing.value = "draw_second_line";
		}
		else{
			top.currentform.last_doing.value = "draw_line";
		}
	}
	
	function delete_lines(){
		var length = poly_pathx_second.length;
		for(i = 0; i < length; i++ ){
			poly_pathx_second.pop();
			poly_pathy_second.pop();
		}
		applylines();
		top.currentform.last_doing.value = "delete_lines";
	}

	function split_lines(){
		applylines();
		top.currentform.last_doing.value = "split_lines";
	}
	
	function applylines(){
		if(top.currentform.pathwkt.value == "" && top.currentform.newpath.value != ""){
			top.currentform.pathwkt.value = buildwktlinefromsvgpath(top.currentform.newpath.value);
		}
		else{
			top.currentform.pathwkt.value = top.currentform.newpathwkt.value;
		}
		if(top.currentform.secondpoly.value == "true"){
			var length = pathx_second.length;
			for(i = 0; i < length; i++ ){
				pathx_second.pop();
				pathy_second.pop();
			}
			var length = poly_pathx_second.length;
			for(i = 0; i < length; i++ ){
				poly_pathx_second.pop();
				poly_pathy_second.pop();
			}
			path_second = buildsvglinepath(pathx_second, pathy_second);
			redrawsecondline();
			top.currentform.secondline.value = false;
			top.currentform.secondpoly.value = false;
			top.currentform.pathx_second.value = "";
			top.currentform.pathy_second.value = "";
		}
	}

	function restartline(){
		top.currentform.last_doing.value = "draw_line";
		top.currentform.newpath.value = "";
		top.currentform.pathwkt.value = "";
		top.currentform.newpathwkt.value = "";
		top.currentform.result.value = "";
		top.currentform.linelength.value = "";
		path = "";
		top.currentform.firstline.value = false;
		top.currentform.secondline.value = false;
		var alle = pathx.length;
		for(var i = 0; i < alle; ++i){
		  pathx.pop();
		  pathy.pop();
		}
		var alles = pathx_second.length;
		for(var i = 0; i < alles; ++i){
			pathx_second.pop();
			pathy_second.pop();
		}
		top.currentform.pathx_second.value = "";
		top.currentform.pathy_second.value = "";
		var length = poly_pathx_second.length;
		for(i = 0; i < length; i++ ){
			poly_pathx_second.pop();
			poly_pathy_second.pop();
		}
		path_second = "";
		redrawsecondline();
		redraw();
		top.currentform.split.style.visibility = "hidden";
	}

	function deletelastline(evt){
		switch(top.currentform.last_doing.value){
			case "draw_line":
				if(pathx.length > 2){
					pathx.pop();
					pathy.pop();
					path = buildsvglinepath(pathx,pathy);
					top.currentform.newpath.value = path;
					redrawfirstline();
				}
			break;
			case "draw_second_line":
				if(pathx_second.length > 2){
					pathx_second.pop();
					pathy_second.pop();
					str = top.currentform.pathx_second.value;
					top.currentform.pathx_second.value = str.substring(0, str.lastIndexOf(";"));
					str = top.currentform.pathy_second.value;
					top.currentform.pathy_second.value = str.substring(0, str.lastIndexOf(";"));
					path_second = buildsvglinepath(pathx_second,pathy_second);
					if(top.currentform.secondline.value == "true"){
						top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&path1="+top.currentform.pathwkt.value+"&path2="+path_second+"&operation=add&geotype=line&resulttype=svgwkt&layer_id="+top.currentform.layer_id.value, new Array(top.currentform.result, ""), new Array("setvalue", "execute_function"));
					}
					redrawsecondline();
				}
			break;
			case "vertex_edit":
				undo_geometry_editing();
			breaK;
		}
	}
	
	function add_geometry(){
		applylines();
		top.currentform.last_doing.value = "add_geom";
	};

	function subtract_geometry(){
		applylines();
		top.currentform.last_doing.value = "subtract_geom";
	};

	function buildwktlinefromsvgpath(svgpath){
		if(svgpath != ""){
			var koords;
			wkt = "LINESTRING(";
			coord = svgpath.split(" ");
			wkt = wkt+coord[1]+" "+coord[2];	// ohne M
			for(var i = 3; i < coord.length-1; i++){
				if(coord[i] != ""){
					wkt = wkt+","+coord[i]+" "+coord[i+1];
				}
				i++;
			}
			wkt = wkt+")";
			return wkt;
		}
		else{
			return "";
		}
	}

	function world2pixelsvg(pathWelt) {
		explosion = pathWelt.split(" ");
		for(i = 0; i < explosion.length; i++){
			if(explosion[i] != "M" && explosion[i] != ""){
				explosion[i] = Math.round((explosion[i] - minx)/scale);
				explosion[i+1] = Math.round((explosion[i+1] - miny)/scale);
				i++;
			}
 		}
		pixelpath = "";
		for(i = 0; i < explosion.length; i++){
			pixelpath = pixelpath + explosion[i] + " ";
		}
		return pixelpath;
	}

	function getxcoordsfromsvgpath(path){
		xcoords = new Array();
		parts = path.split(" ");
		for(i = 1; i < parts.length; i=i+2){
			if(parts[i] != ""){
				xcoords.push(parts[i]);
			}
		}
		return xcoords;
	}

	function getycoordsfromsvgpath(path){
		ycoords = new Array();
		parts = path.split(" ");
		for(i = 2; i < parts.length; i=i+2){
			if(parts[i] != ""){
				ycoords.push(parts[i]);
			}
		}
		return ycoords;
	}

	function activate_vertex(evt){
		if(top.currentform.last_doing.value == "vertex_edit"){
			vertex_id_string = evt.target.getAttribute("id");
			vertex_id = vertex_id_string.split("_");
			if(vertex_id[1] == "new"){
				evt.target.setAttribute("style", "-moz-user-select: none;opacity: 1;fill: #00DD00");
			}
			else{
				evt.target.setAttribute("style", "-moz-user-select: none;opacity: 1;fill-opacity: 0.1;stroke: #FF0000;stroke-width:2");
			}
		}
	}
	
	function activate_line(evt){
		if(top.currentform.last_doing.value == "vertex_edit"){
			line = evt.target;
			vertex_id_string = line.getAttribute("id");
			vertex_id = vertex_id_string.split("_");
			// Lotfusspunkt berechnen
			p1x = parseInt(line.getAttribute("x1"));
			p1y = parseInt(line.getAttribute("y1"));
			p2x = parseInt(line.getAttribute("x2"));
			p2y = parseInt(line.getAttribute("y2"));
			ax = p2x - p1x;
			ay = p2y - p1y;
			bx = evt.clientX - p1x;
			by = resy - evt.clientY - p1y;
			c = ax*ax + ay*ay;
			d = bx*ax + by*ay;
			e = d/c;
			x = p1x + e*ax;
			y = p1y + e*ay;
			// Position des Punktes auf der Linie setzen
			vertex = document.getElementById("vertex_new_"+vertex_id[2]);
			vertex.setAttribute("cx", x);
			vertex.setAttribute("cy", y);
		}
	}

	function deactivate_vertex(evt){
		if(top.currentform.last_doing.value == "vertex_edit"){
			vertex_id_string = evt.target.getAttribute("id");
			vertex_id = vertex_id_string.split("_");
			if(vertex_id[1] == "new"){
				evt.target.setAttribute("style", "-moz-user-select: none;fill: #FF0000;opacity: 0.01");
			}
			else{
				evt.target.setAttribute("style", "-moz-user-select: none;fill: #FF0000;opacity: 0.3");
			}
		}
	}

	function select_vertex(evt){
		selected_vertex = evt.target;
		last_selected_vertex = selected_vertex;
		vertex_id_string = evt.target.getAttribute("id");
		vertex_id = vertex_id_string.split("_");
		if(vertex_id[1] != "new"){
			remove_in_between_vertices();		// die Zwischenpunkte entfernen, sonst stoeren die beim Verschieben
			jetzt = new Date();
	  	time = jetzt.getTime();
			if(time - time_mouse_down < 1000){
				delete_vertex(evt);
			}
			time_mouse_down = time;
		}
	}

	function move_vertex(evt, vertex, coordtype){
		if(vertex == undefined){
			vertex = evt.target;
		}
		vertex_id_string = vertex.getAttribute("id");
		vertex_id = vertex_id_string.split("_");
		if(vertex_id[1] != "new"){
			if(selected_vertex == vertex){
				if(deactivated_foreign_vertex != 0){		// wenn es einen deaktivierten foreign vertex gibt, wird dieser jetzt wieder aktiviert
					document.getElementById(deactivated_foreign_vertex).setAttribute("pointer-events", "auto");
					deactivated_foreign_vertex = 0;
				}
				if(coordtype == "world"){
					vertex_new_world_x = evt.clientX; 
					vertex_new_world_y = evt.clientY;
				}
				else{
					x = evt.clientX;
					y = evt.clientY;
					vertex_new_world_x = (x * scale) + minx;
					vertex_new_world_y = ((resy-y) * scale) + miny;
				}
				vertex.setAttribute("cx", x);
				vertex.setAttribute("cy", resy-y);
				svg_path = top.currentform.newpath.value+"";
				components = svg_path.split(" ");
				components[parseInt(vertex_id[1])] = vertex_new_world_x;
		  	components[parseInt(vertex_id[1])+1] = vertex_new_world_y;
				new_svg_path = components[0];
				for(i = 1; i < components.length; i++){
					new_svg_path = new_svg_path + " " + components[i];
				}
				top.currentform.newpath.value = new_svg_path;
				redrawsecondline();
				vertex_moved = true;
			}
		}
	}

	function insert_vertex(evt){
		vertex = evt.target;
		if(selected_vertex == vertex){
			vertex_id_string = vertex.getAttribute("id");
			vertex_id = vertex_id_string.split("_");
			x = vertex.getAttribute("cx");
			y = vertex.getAttribute("cy");
			x_world = (x * scale) + minx;
			y_world = (y * scale) + miny;
			svg_path = top.currentform.newpath.value+"";
			components = svg_path.split(" ");
			new_svg_path = "M";
			for(i = 1; i < components.length+1; i++){
				if(vertex_id[2] == i-2){
					new_svg_path = new_svg_path + " " + x_world + " " + y_world;
				}
				if(components[i] != undefined)new_svg_path = new_svg_path + " " + components[i];
			}
			top.currentform.newpath.value = new_svg_path;

			if(top.currentform.newpathwkt.value != ""){			// wenn ein WKT-String da ist, hier auch den Vertex einfuegen
				wktarray = get_array_from_wktstring(top.currentform.newpathwkt.value);
				wktstring = "";
				komma = 1;
				kommaset = 0;
				for(i = 0; i < wktarray.length; i++){
					if(vertex_id[2] == i-2){
						if(i > 0 && kommaset != 1 && wktarray[i-1].lastIndexOf("(") == -1 ){
							wktstring = wktstring + ",";
						}
						wktstring = wktstring + x_world + " " + y_world;
						if(i > 0 && wktarray[i].lastIndexOf(")") == -1 ){
							wktstring = wktstring + ",";
						}
					}
					if(wktarray[i] != ""){
						wktstring = wktstring + wktarray[i];
						if(i > 0 && wktarray[i].lastIndexOf(")") == -1 && wktarray[i+1].lastIndexOf(")") == -1){		// Kommas einfuegen
							if(komma == 2){
								wktstring = wktstring + ",";
								komma = 1;
								kommaset = 1;
							}
							else{
								if(komma == 1){
									wktstring = wktstring + " ";
									komma = 2;
								}
							}
						}
						else{
							komma = 1;
							kommaset = 0;
						}
					}
				}
				top.currentform.newpathwkt.value = wktstring;
			}
			remove_vertices();													// alle entfernen
			remove_in_between_vertices();
			pixel_path = world2pixelsvg(new_svg_path);
			add_vertices(pixel_path);										// und wieder hinzufuegen
			redrawsecondline();
		}
	}

	function delete_vertex(evt){
		vertex = evt.target;
		if(selected_vertex == vertex){
			vertex_id_string = vertex.getAttribute("id");
			vertex_id = vertex_id_string.split("_");
			svg_path = top.currentform.newpath.value+"";
			components = svg_path.split(" ");
			if(components.length > 6){			// nur loeschen, wenn mindestens 3 Eckpunkte uebrig
				components[parseInt(vertex_id[1])] = "";
		  	components[parseInt(vertex_id[1])+1] = "";
				if(components[parseInt(vertex_id[1])-1] == "M" && ( components[parseInt(vertex_id[1])+4] == "M" || components.length < parseInt(vertex_id[1])+7 )){
					components[parseInt(vertex_id[1])-1] = "";
					components[parseInt(vertex_id[1])+2] = "";
					components[parseInt(vertex_id[1])+3] = "";
				}
				new_svg_path = "M";
				for(i = 1; i < components.length; i++){
					if(components[i] != ""){
						new_svg_path = new_svg_path + " " + components[i];
					}
				}
				top.currentform.newpath.value = new_svg_path;
	
				if(top.currentform.newpathwkt.value != ""){			// wenn ein WKT-String da ist, hier auch den Vertex loeschen
					wktarray = get_array_from_wktstring(top.currentform.newpathwkt.value);
					wktarray.splice(parseInt(vertex_id[1]), 2);
					// wenn vertex nur noch einen Nachbarvertex hat, auch diesen und das "),(" loeschen
					if(( wktarray[parseInt(vertex_id[1])-3] == "),(" || wktarray[parseInt(vertex_id[1])-3] == "MULTILINESTRING((" ) && ( wktarray[parseInt(vertex_id[1])+2] == "),(" || wktarray[parseInt(vertex_id[1])+2] == "))")){
						if(wktarray[parseInt(vertex_id[1])-3] == "MULTILINESTRING(("){
							wktarray.splice(parseInt(vertex_id[1])-2, 5);
						}
						else{
							wktarray.splice(parseInt(vertex_id[1])-3, 5);
						}
					}
					if(( wktarray[parseInt(vertex_id[1])-1] == "),(" || wktarray[parseInt(vertex_id[1])-1] == "MULTILINESTRING((" ) && ( wktarray[parseInt(vertex_id[1])+4] == "),(" || wktarray[parseInt(vertex_id[1])+4] == "))" )){
						if(wktarray[parseInt(vertex_id[1])-1] == "MULTILINESTRING(("){
							wktarray.splice(parseInt(vertex_id[1]), 5);
						}
						else{
							wktarray.splice(parseInt(vertex_id[1])-1, 5);
						}
					}
					wktstring = "";
					komma = 1;
					for(i = 0; i < wktarray.length; i++){
						if(wktarray[i] != ""){
							wktstring = wktstring + wktarray[i];
							if(i > 0 && wktarray[i].lastIndexOf(")") == -1 && wktarray[i+1].lastIndexOf(")") == -1){		// Kommas einfuegen
								if(komma == 2){
									wktstring = wktstring + ",";
									komma = 1;
								}
								else{
									if(komma == 1){
										wktstring = wktstring + " ";
										komma = 2;
									}
								}
							}
							else{
								komma = 1;
							}
						}
					}
					top.currentform.newpathwkt.value = wktstring;
				}
				remove_vertices();													// alle entfernen
				remove_in_between_vertices();
				pixel_path = world2pixelsvg(new_svg_path);
				add_vertices(pixel_path);										// und wieder hinzufuegen, damit die Nummerierung wieder stimmt
				redrawsecondline();
				selected_vertex = "";
				last_selected_vertex = "";
				linelength();
			}
		}
	}

	function get_array_from_wktstring(wktstring){
		// zerlegt einen WKT-String in ein Array (ohne Kommas)
		if(wktstring.substr(0, 4) == "MULT"){
			subarray = new Array();
			subsubarray = new Array();
			wkt = wktstring.substr(17, wktstring.length-19);
			subwkt = wkt.split("),(");
			for(i = 0; i < subwkt.length; i++){
				subsubwkt = subwkt[i].split(",");
				count = subsubarray.length;
				for(k = 0; k < count; k++){
					subsubarray.pop();
				}
				for(k = 0; k < subsubwkt.length; k++){
					subsubsubwkt = subsubwkt[k].split(" ");
					subsubarray = subsubarray.concat(subsubsubwkt);
				}
				subarray = subarray.concat(subsubarray);
				if(i < subwkt.length-1){
					subarray.push("),(");
				}
			}
			helparray = new Array("MULTILINESTRING((");
			subarray = helparray.concat(subarray);
			subarray.push("))");
			return subarray;
		}
		if(wktstring.substr(0, 4) == "LINE"){
			subarray = new Array();
			subsubarray = new Array();
			wkt = wktstring.substr(11, wktstring.length-12);
			subwkt = wkt.split(",");
			for(k = 0; k < subwkt.length; k++){
				subsubwkt = subwkt[k].split(" ");
				subarray = subarray.concat(subsubwkt);
			}
			helparray = new Array("LINESTRING(");
			subarray = helparray.concat(subarray);
			subarray.push(")");
			return subarray;
		}
	}

	function end_vertex_move(evt){
		if(selected_vertex == evt.target){
			if(vertex_moved == true){
				if(top.currentform.newpathwkt.value != ""){
					vertex_id_string = selected_vertex.getAttribute("id");
					vertex_id = vertex_id_string.split("_");
					wktarray = get_array_from_wktstring(top.currentform.newpathwkt.value);
					wktarray[parseInt(vertex_id[1])] = vertex_new_world_x;
					wktarray[parseInt(vertex_id[1])+1] = vertex_new_world_y;
					wktstring = "";
					komma = 1;
					for(i = 0; i < wktarray.length; i++){
						if(wktarray[i] != ""){
							wktstring = wktstring + wktarray[i];
							if(i > 0 && String(wktarray[i]).lastIndexOf(")") == -1 && String(wktarray[i+1]).lastIndexOf(")") == -1){		// Kommas einfuegen
								if(komma == 2){
									wktstring = wktstring + ",";
									komma = 1;
								}
								else{
									if(komma == 1){
										wktstring = wktstring + " ";
										komma = 2;
									}
								}
							}
							else{
								komma = 1;
							}
						}
					}
					top.currentform.newpathwkt.value = wktstring;
				}
				remove_vertices();													// alle entfernen
				remove_in_between_vertices();
				pixel_path = world2pixelsvg(top.currentform.newpath.value);
				add_vertices(pixel_path);										// und wieder hinzufuegen
				linelength();
			}
			else{
				vertex_id_string = evt.target.getAttribute("id");
				vertex_id = vertex_id_string.split("_");
				if(vertex_id[1] == "new"){
					insert_vertex(evt);
				}
			}
			selected_vertex = "";
			vertex_moved = false;
		}
	}
	
	function remove_vertices(){
		var parent = document.getElementById("vertices");
		var count = parent.childNodes.length;
		for(i = 0; i < count; i++){
			parent.removeChild(parent.lastChild);
		}
	}
	
	function remove_in_between_vertices(){
		var parent = document.getElementById("in_between_vertices");
		var count = parent.childNodes.length;
		for(i = 0; i < count; i++){
			parent.removeChild(parent.lastChild);
		}
	}

	function normalize_vector(a, b){
		var length = Math.sqrt(a*a + b*b);
		a = a / length;
		b = b / length;
		return Array(a, b);
	}

	function add_vertices(pixel_path){
		pixel_path = pixel_path+"";
		components = pixel_path.split(" ");
		var parent = document.getElementById("vertices");
		var parent2 = document.getElementById("in_between_vertices");
		circle = new Array();
		circle2 = new Array();
		line = new Array();
		kreis1 = document.getElementById("kreis");
		linie1 = document.getElementById("linie");
		for(i = 1; i < components.length; i=i+2){
			if(components[i-1] == "M"){
				// den neuen Eckpunkt vorne
				i = i - 2;
				circle2[i] = kreis1.cloneNode(true);
				a = parseInt(components[i+2])-parseInt(components[i+4]);
				b = parseInt(components[i+3])-parseInt(components[i+5]);
				norm_vec = normalize_vector(a,b);
				circle2[i].setAttribute("cx", parseInt(components[i+2])+(norm_vec[0]*resx/20));
				circle2[i].setAttribute("cy", parseInt(components[i+3])+(norm_vec[1]*resx/20));
				circle2[i].setAttribute("style","fill: #000000");
				circle2[i].setAttribute("opacity", "0.01");
				circle2[i].setAttribute("id", "vertex_new_"+i);
				parent.appendChild(circle2[i]);
				i = i + 2;
			}
			// Eckpunkte
			circle[i] = kreis1.cloneNode(true);
			circle[i].setAttribute("cx", components[i]);
			circle[i].setAttribute("cy", components[i+1]);
			circle[i].setAttribute("style","fill: #FF0000");
			circle[i].setAttribute("id", "vertex_"+i);
			parent.appendChild(circle[i]);
			if(i+3 < components.length && components[i+2] != "M"){	// zwischen Linienzuegen keine Punkte und Linien setzen
				// Zwischenlinien
				line[i] = linie1.cloneNode(true);
				line[i].setAttribute("x1", components[i]);
				line[i].setAttribute("y1", components[i+1]);
				line[i].setAttribute("x2", components[i+2]);
				line[i].setAttribute("y2", components[i+3]);
				line[i].setAttribute("style","stroke: #FF0000");
				line[i].setAttribute("opacity", "0.01");
				line[i].setAttribute("id", "line_new_"+i);
				parent2.appendChild(line[i]);
				// Zwischenpunkte
				circle2[i] = kreis1.cloneNode(true);
				circle2[i].setAttribute("cx", parseInt(components[i])-(parseInt(components[i])-parseInt(components[i+2]))/2);
				circle2[i].setAttribute("cy", parseInt(components[i+1])-(parseInt(components[i+1])-parseInt(components[i+3]))/2);
				circle2[i].setAttribute("style","fill: #FF0000");
				circle2[i].setAttribute("opacity", "0.01");
				circle2[i].setAttribute("id", "vertex_new_"+i);
				parent2.appendChild(circle2[i]);
			}
			if(components[i+2] == "M" || components[i+2] == ""){
				// den neuen Eckpunkt hinten
				circle2[i] = kreis1.cloneNode(true);
				a = parseInt(components[i])-parseInt(components[i-2]);
				b = parseInt(components[i+1])-parseInt(components[i-1]);
				norm_vec = normalize_vector(a,b);
				circle2[i].setAttribute("cx", parseInt(components[i])+(norm_vec[0]*resx/20));
				circle2[i].setAttribute("cy", parseInt(components[i+1])+(norm_vec[1]*resx/20));
				circle2[i].setAttribute("style","fill: #000000");
				circle2[i].setAttribute("opacity", "0.01");
				circle2[i].setAttribute("id", "vertex_new_"+i);
				parent.appendChild(circle2[i]);
				i = i + 1;
			}
		}
	}

	function edit_vertices(){
		applylines();
		save_geometry_for_undo();
		top.currentform.last_doing.value = "vertex_edit";
		pixel_path = world2pixelsvg(top.currentform.newpath.value);
		add_vertices(pixel_path);
	}

	function save_geometry_for_undo(){
		newpath_undo = top.currentform.newpath.value;
		newpathwkt_undo = top.currentform.newpathwkt.value;
	}

	function undo_geometry_editing(){
		top.currentform.newpath.value = newpath_undo;
		top.currentform.newpathwkt.value = newpathwkt_undo;
		remove_vertices();													// alle entfernen
		remove_in_between_vertices();
		pixel_path = world2pixelsvg(top.currentform.newpath.value);
		add_vertices(pixel_path);										// und wieder hinzufuegen
		redrawfirstline();
	}
	
	function linelength(){
		length1 = top.document.getElementById("custom_length");
		if(length1 == undefined){						// wenn es ein Laenge-Attribut gibt, wird das verwendet, ansonsten die normale Laengenanzeige
			length1 = top.currentform.linelength;
		}
	  if(top.currentform.newpathwkt.value != ""){
	  	top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&geotype=line&path1="+top.currentform.newpathwkt.value+"&operation=length&layer_id="+top.currentform.layer_id.value, new Array(top.currentform.linelength, length1), "");
	  }
	  else{
	  	if(top.currentform.newpath.value != ""){
	  		top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&geotype=line&path2="+top.currentform.newpath.value+"&operation=length&layer_id="+top.currentform.layer_id.value, new Array(top.currentform.linelength, length1), "");
	  	}
	  	else{
	  		top.currentform.linelength.value = "0.0";
	  	}
	  }
	}

	';

	$boxfunctions = '

	boxfunctions = true;

	function draw_box_on() {
	  //document.getElementById("canvas_FS").setAttribute("cursor", "text");
	 	restart();
		top.currentform.last_doing.value = "draw_box";
	}

	// ----------------------------box aufziehen---------------------------------
	function startpointFS(worldx, worldy) {
	  draggingFS  = true;
	  restart();
	  // neuen punkt hinzufuegen
	  pathx.push(worldx);
	  pathy.push(worldy);
	  path = buildsvgpath(pathx,pathy);
	  top.currentform.newpath.value = path;
	}

	function movepointFS(evt) {
	if (!draggingFS) return;
	  // neuen punkt abgreifen
	  clientx = evt.clientX;
	  clienty = resy - evt.clientY;
	  world_x = (clientx * scale) + minx;
	  world_y = (clienty * scale) + miny;
	  pathx[1]  = pathx[0];
	  pathy[1]  = world_y;
	  pathx[2]  = world_x;
	  pathy[2]  = world_y;
	  pathx[3]  = world_x;
	  pathy[3]  = pathy[0];
	  path = buildsvgpath(pathx,pathy);
	  top.currentform.newpath.value = path;
	  top.currentform.firstpoly.value = true;
	  redrawfirstpolygon();
	}

	function endpointFS(evt) {
	  draggingFS  = false;
	}';
	
	$bufferfunctions ='

		bufferfunctions = true;

		function add_buffer(){
			buffer = prompt("Breite des Puffers in Metern:", "10");
			if(buffer){
				top.currentform.secondpoly.value = true;
				top.currentform.firstpoly.value = true;
				if(top.currentform.newpathwkt.value != ""){
					top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&path1="+top.currentform.newpathwkt.value+"&width="+buffer+"&operation=buffer&resulttype=svgwkt", new Array(top.currentform.result, ""), new Array("setvalue", "execute_function"));
				}
				else{
					if(top.currentform.newpath.value != ""){
						newpath = buildwktpolygonfromsvgpath(top.currentform.newpath.value);
						top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&path1="+newpath+"&width="+buffer+"&operation=buffer&resulttype=svgwkt", new Array(top.currentform.result, ""), new Array("setvalue", "execute_function"));
					}
				}
			}
		}
		
		function add_buffered_line(){
			top.currentform.last_doing.value = "add_buffered_line";
			top.currentform.bufferwidth.value = prompt("Breite des Puffers in Metern:", top.currentform.bufferwidth.value);
			if(top.currentform.pathwkt.value == "" && top.currentform.newpath.value != ""){
				top.currentform.pathwkt.value = buildwktpolygonfromsvgpath(top.currentform.newpath.value);
			}
			else{
				if(top.currentform.newpathwkt.value != ""){
					top.currentform.pathwkt.value = top.currentform.newpathwkt.value;
				}
			}
		  if(top.currentform.secondpoly.value == "true"){
				applypolygons();
			}
		}
		
		function add_parallel_polygon(){
			top.currentform.last_doing.value = "add_parallel_polygon";
			top.currentform.bufferwidth.value = prompt("Breite des Polygons in Metern:", top.currentform.bufferwidth.value);
			if(top.currentform.pathwkt.value == "" && top.currentform.newpath.value != ""){
				top.currentform.pathwkt.value = buildwktpolygonfromsvgpath(top.currentform.newpath.value);
			}
			else{
				if(top.currentform.newpathwkt.value != ""){
					top.currentform.pathwkt.value = top.currentform.newpathwkt.value;
				}
			}
		  if(top.currentform.secondpoly.value == "true"){
				applypolygons();
			}
		}

	';

	$flurstqueryfunctions ='

		flurstuecksqueryfunctions = true;

		function add_geometry(){
		 	if(top.currentform.pathwkt.value == "" && top.currentform.newpath.value != ""){
				top.currentform.pathwkt.value = buildwktpolygonfromsvgpath(top.currentform.newpath.value);
			}
			else{
				top.currentform.pathwkt.value = top.currentform.newpathwkt.value;
			}
			if(top.currentform.secondpoly.value == "true"){
				applypolygons();
			}
			top.currentform.last_doing.value = "add_geom";
		};

		function subtract_geometry(){
		 	if(top.currentform.pathwkt.value == "" && top.currentform.newpath.value != ""){
				top.currentform.pathwkt.value = buildwktpolygonfromsvgpath(top.currentform.newpath.value);
			}
			else{
				top.currentform.pathwkt.value = top.currentform.newpathwkt.value;
			}
			if(top.currentform.secondpoly.value == "true"){
				applypolygons();
			}
			top.currentform.last_doing.value = "subtract_geom";
		};
	';

	$polygonfunctions = '

	polygonfunctions = true;

	top.document.getElementById("svghelp").SVGupdate_geometry = update_geometry;		// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen

	function update_geometry(){
		if(top.currentform.secondpoly.value == "true"){
			document.getElementById("cartesian").setAttribute("transform", "translate(0,'.$res_y.') scale(1,-1)");
			updatepaths();
			if(top.currentform.last_doing.value == "add_geom" || top.currentform.last_doing.value == "subtract_geom" || top.currentform.last_doing.value == "move_geometry"){
				if(top.currentform.pathwkt.value == ""){
					top.currentform.pathwkt.value = buildwktpolygonfromsvgpath(top.currentform.newpath.value);
				}
				else{
					top.currentform.pathwkt.value = top.currentform.newpathwkt.value;
				}
				if(top.currentform.secondpoly.value == "true" && must_redraw){
					applypolygons();
					must_redraw = false;
				}
				top.currentform.secondpoly.value = "true";
			}
			if(must_redraw){
				redrawsecondpolygon();
				must_redraw = false;
			}
		}
	}

	function draw_pgon_on() {
		if(textx > 0){
			if(polygonXORpoint){
				document.getElementById("text0").style.setProperty("fill","ghostwhite", "");
				// formularfelder + position der pointposition loeschen
				textx = 100;
				texty = 100;
				sendBWlocation(textx,texty);
				redrawpoint();
			}
	  }
	}
			
	// ----------------------------pgon zeichnen---------------------------------
	function addpoint_first(worldx, worldy) {
	  // neuen punkt setzen
		top.currentform.lastcoordx.value = world_x;
		top.currentform.lastcoordy.value = world_y; 
	  pathx.push(world_x);
	  pathy.push(world_y);
		if(pathx.length == 1){
			document.getElementById("startvertex").setAttribute("cx", (world_x-minx)/scale);
			document.getElementById("startvertex").setAttribute("cy", (world_y-miny)/scale);
		}
		else{
			document.getElementById("startvertex").setAttribute("cx", -500);
			document.getElementById("startvertex").setAttribute("cy", -500);
		}
	  path = buildsvgpath(pathx,pathy);
	  top.currentform.newpath.value = path;
	  if(pathy.length > 2){
	  	top.currentform.firstpoly.value = true;
	  	polygonarea();
	  }
	}

	function addpoint_second(worldx, worldy) {
	  // neuen punkt setzen
		top.currentform.lastcoordx.value = world_x;
		top.currentform.lastcoordy.value = world_y;
	  pathx_second.push(world_x);
	  pathy_second.push(world_y);
		if(pathx_second.length == 1){
			document.getElementById("startvertex").setAttribute("cx", (world_x-minx)/scale);
			document.getElementById("startvertex").setAttribute("cy", (world_y-miny)/scale);
		}
		else{
			document.getElementById("startvertex").setAttribute("cx", -500);
			document.getElementById("startvertex").setAttribute("cy", -500);
		}
		if(top.currentform.pathx_second.value != ""){
			top.currentform.pathx_second.value = top.currentform.pathx_second.value+";"+world_x;
			top.currentform.pathy_second.value = top.currentform.pathy_second.value+";"+world_y;
		}
		else{
			top.currentform.pathx_second.value = world_x;
			top.currentform.pathy_second.value = world_y;
		} 
	  path_second = buildsvgpath(pathx_second, pathy_second);
	  if(pathy_second.length > 2){
	  	top.currentform.secondpoly.value = true;
	  }
	  else{
	  	top.currentform.secondpoly.value = "started";
	  }
	}

	function redrawfirstpolygon(){
	  // polygone um punktepfad erweitern
	  var obj = document.getElementById("polygon_first");
		pixel_path = world2pixelsvg(top.currentform.newpath.value);
	  obj.setAttribute("d", pixel_path);
	}

	function activate_vertex(evt){
		if(top.currentform.last_doing.value == "vertex_edit"){
			vertex_id_string = evt.target.getAttribute("id");
			vertex_id = vertex_id_string.split("_");
			if(vertex_id[1] == "new"){
				evt.target.setAttribute("style", "-moz-user-select: none;opacity: 1;fill: #00DD00");
			}
			else{
				evt.target.setAttribute("style", "-moz-user-select: none;opacity: 1;fill-opacity: 0.1;stroke: #FF0000;stroke-width:2");
			}
		}
	}
	
	function activate_line(evt){
		if(top.currentform.last_doing.value == "vertex_edit"){
			line = evt.target;
			vertex_id_string = line.getAttribute("id");
			vertex_id = vertex_id_string.split("_");
			// Lotfusspunkt berechnen
			p1x = parseInt(line.getAttribute("x1"));
			p1y = parseInt(line.getAttribute("y1"));
			p2x = parseInt(line.getAttribute("x2"));
			p2y = parseInt(line.getAttribute("y2"));
			ax = p2x - p1x;
			ay = p2y - p1y;
			bx = evt.clientX - p1x;
			by = resy - evt.clientY - p1y;
			c = ax*ax + ay*ay;
			d = bx*ax + by*ay;
			e = d/c;
			x = p1x + e*ax;
			y = p1y + e*ay;
			// Position des Punktes auf der Linie setzen
			vertex = document.getElementById("vertex_new_"+vertex_id[2]);
			vertex.setAttribute("cx", x);
			vertex.setAttribute("cy", y);
		}
	}

	function deactivate_vertex(evt){
		if(top.currentform.last_doing.value == "vertex_edit"){
			vertex_id_string = evt.target.getAttribute("id");
			vertex_id = vertex_id_string.split("_");
			if(vertex_id[1] == "new"){
				evt.target.setAttribute("style", "-moz-user-select: none;fill: #FF0000;opacity: 0.01");
			}
			else{
				evt.target.setAttribute("style", "-moz-user-select: none;fill: #FF0000;opacity: 0.3");
			}
		}
	}

	function select_vertex(evt){
		selected_vertex = evt.target;
		last_selected_vertex = selected_vertex;
		vertex_id_string = evt.target.getAttribute("id");
		vertex_id = vertex_id_string.split("_");
		if(vertex_id[1] != "new"){
			remove_in_between_vertices();			// die Zwischenpunkte entfernen, sonst stoeren die beim Verschieben
			jetzt = new Date();
	  	time = jetzt.getTime();
			if(time - time_mouse_down < 1000){
				delete_vertex(evt);
			}
			time_mouse_down = time;
		}
	}

	function move_vertex(evt, vertex, coordtype){
		if(vertex == undefined){
			vertex = evt.target;
		}
		vertex_id_string = vertex.getAttribute("id");
		vertex_id = vertex_id_string.split("_");
		if(vertex_id[1] != "new"){
			if(selected_vertex == vertex){
				if(deactivated_foreign_vertex != 0){		// wenn es einen deaktivierten foreign vertex gibt, wird dieser jetzt wieder aktiviert
					document.getElementById(deactivated_foreign_vertex).setAttribute("pointer-events", "auto");
					deactivated_foreign_vertex = 0;
				}
				if(coordtype == "world"){
					vertex_new_world_x = evt.clientX; 
					vertex_new_world_y = evt.clientY;
				}
				else{
					x = evt.clientX;
					y = evt.clientY;
					vertex_new_world_x = (x * scale) + minx;
					vertex_new_world_y = ((resy-y) * scale) + miny;
				}
				vertex.setAttribute("cx", x);
				vertex.setAttribute("cy", resy-y);
				svg_path = top.currentform.newpath.value+"";
				components = svg_path.split(" ");
				components[parseInt(vertex_id[1])] = vertex_new_world_x;
		  	components[parseInt(vertex_id[1])+1] = vertex_new_world_y;
				if(vertex_id[2] != ""){			// Anfangs und Endpunkt
					components[parseInt(vertex_id[2])] = vertex_new_world_x;
		  		components[parseInt(vertex_id[2])+1] = vertex_new_world_y;
				}
				new_svg_path = components[0];
				for(i = 1; i < components.length; i++){
					new_svg_path = new_svg_path + " " + components[i];
				}
				top.currentform.newpath.value = new_svg_path;
				redrawsecondpolygon();
				vertex_moved = true;
			}
		}
	}

	function insert_vertex(evt){
		vertex = evt.target;
		if(selected_vertex == vertex){
			vertex_id_string = vertex.getAttribute("id");
			vertex_id = vertex_id_string.split("_");
			x = vertex.getAttribute("cx");
			y = vertex.getAttribute("cy");
			x_world = (x * scale) + minx;
			y_world = (y * scale) + miny;
			svg_path = top.currentform.newpath.value+"";
			components = svg_path.split(" ");
			new_svg_path = "M";
			for(i = 1; i < components.length; i++){
				new_svg_path = new_svg_path + " " + components[i];
				if(vertex_id[2] == i-1){
					new_svg_path = new_svg_path + " " + x_world + " " + y_world;
				}
			}
			top.currentform.newpath.value = new_svg_path;

			if(top.currentform.newpathwkt.value != ""){			// wenn ein WKT-String da ist, hier auch den Vertex einfuegen
				wktarray = get_array_from_wktstring(top.currentform.newpathwkt.value);
				wktstring = "";
				komma = 1;
				for(i = 0; i < wktarray.length; i++){
					if(wktarray[i] != ""){
						wktstring = wktstring + wktarray[i];
						if(i > 0 && wktarray[i].lastIndexOf(")") == -1 && wktarray[i+1].lastIndexOf(")") == -1){		// Kommas einfuegen
							if(komma == 2){
								wktstring = wktstring + ",";
								komma = 1;
							}
							else{
								if(komma == 1){
									wktstring = wktstring + " ";
									komma = 2;
								}
							}
						}
						else{
							komma = 1;
						}
					}
					if(vertex_id[2] == i-1){
						wktstring = wktstring + x_world + " " + y_world + ",";
					}
				}
				top.currentform.newpathwkt.value = wktstring;
			}
			remove_vertices();													// alle entfernen
			remove_in_between_vertices();
			pixel_path = world2pixelsvg(new_svg_path);
			add_vertices(pixel_path);										// und wieder hinzufuegen
			redrawsecondpolygon();
		}
	}

	function delete_vertex(evt){
		vertex = evt.target;
		if(selected_vertex == vertex){
			vertex_id_string = vertex.getAttribute("id");
			vertex_id = vertex_id_string.split("_");
			svg_path = top.currentform.newpath.value+"";
			components = svg_path.split(" ");
			if(components.length > 10){			// nur loeschen, wenn mindestens 4 Eckpunkte uebrig
				components[parseInt(vertex_id[1])] = \'\';
		  	components[parseInt(vertex_id[1])+1] = \'\';
				if(vertex_id[2] != ""){			// Anfangs und Endpunkt
					components[parseInt(vertex_id[2])] = components[parseInt(vertex_id[1])+2];
		  		components[parseInt(vertex_id[2])+1] = components[parseInt(vertex_id[1])+3];
				}
				new_svg_path = "M";
				for(i = 1; i < components.length; i++){
					if(components[i] != \'\'){
						new_svg_path = new_svg_path + " " + components[i];
					}
				}
				top.currentform.newpath.value = new_svg_path;
	
				if(top.currentform.newpathwkt.value != ""){			// wenn ein WKT-String da ist, hier auch den Vertex loeschen
					wktarray = get_array_from_wktstring(top.currentform.newpathwkt.value);
					wktarray.splice(parseInt(vertex_id[1]), 2);
					if(vertex_id[2] != ""){			// Anfangs und Endpunkt
						wktarray[parseInt(vertex_id[2])] = wktarray[parseInt(vertex_id[1])+2];
						wktarray[parseInt(vertex_id[2])+1] = wktarray[parseInt(vertex_id[1])+3];
					}
					wktstring = "";
					komma = 1;
					for(i = 0; i < wktarray.length; i++){
						if(wktarray[i] != ""){
							wktstring = wktstring + wktarray[i];
							if(i > 0 && wktarray[i].lastIndexOf(")") == -1 && wktarray[i+1].lastIndexOf(")") == -1){		// Kommas einfuegen
								if(komma == 2){
									wktstring = wktstring + ",";
									komma = 1;
								}
								else{
									if(komma == 1){
										wktstring = wktstring + " ";
										komma = 2;
									}
								}
							}
							else{
								komma = 1;
							}
						}
					}
					top.currentform.newpathwkt.value = wktstring;
				}
	
				remove_vertices();													// alle entfernen
				remove_in_between_vertices();
				pixel_path = world2pixelsvg(new_svg_path);
				add_vertices(pixel_path);										// und wieder hinzufuegen, damit die Nummerierung wieder stimmt
				redrawsecondpolygon();
				selected_vertex = "";
				last_selected_vertex = "";
				polygonarea();
			}
		}
	}

	function get_array_from_wktstring(wktstring){
		// zerlegt einen WKT-String in ein Array (ohne Kommas)
		if(wktstring.substr(0, 4) == "MULT"){
			subsubsubarray = new Array();
			subsubarray = new Array();
			subarray = new Array();
			wkt = wktstring.substr(15, wktstring.length-18);
			subwkt = wkt.split(")),((");
			for(i = 0; i < subwkt.length; i++){
				subsubwkt = subwkt[i].split("),(");
				count = subsubarray.length;
				for(k = 0; k < count; k++){
					subsubarray.pop();
				}
				for(j = 0; j < subsubwkt.length; j++){
					subsubsubwkt = subsubwkt[j].split(",");
					count = subsubsubarray.length;
					for(k = 0; k < count; k++){
						subsubsubarray.pop();
					}
					for(k = 0; k < subsubsubwkt.length; k++){
						subsubsubsubwkt = subsubsubwkt[k].split(" ");
						subsubsubarray = subsubsubarray.concat(subsubsubsubwkt);
					}
					subsubarray = subsubarray.concat(subsubsubarray);
					if(j < subsubwkt.length-1){
						subsubarray.push("),(");
					}
				}
				subarray = subarray.concat(subsubarray);
				if(i < subwkt.length-1){
					subarray.push(")),((");
				}
			}
			helparray = new Array("MULTIPOLYGON(((");
			subarray = helparray.concat(subarray);
			subarray.push(")))");
			return subarray;
		}
		else{
			if(wktstring.substr(0, 4) == "POLY"){
				subarray = new Array();
				subsubarray = new Array();
				wkt = wktstring.substr(9, wktstring.length-11);
				subwkt = wkt.split("),(");
				for(i = 0; i < subwkt.length; i++){
					subsubwkt = subwkt[i].split(",");
					count = subsubarray.length;
					for(k = 0; k < count; k++){
						subsubarray.pop();
					}
					for(k = 0; k < subsubwkt.length; k++){
						subsubsubwkt = subsubwkt[k].split(" ");
						subsubarray = subsubarray.concat(subsubsubwkt);
					}
					subarray = subarray.concat(subsubarray);
					if(i < subwkt.length-1){
						subarray.push("),(");
					}
				}
				helparray = new Array("POLYGON((");
				subarray = helparray.concat(subarray);
				subarray.push("))");
				return subarray;
			}
		}
	}

	function end_vertex_move(evt){
		if(selected_vertex == evt.target){			
			if(vertex_moved == true){
				if(top.currentform.newpathwkt.value != ""){
					vertex_id_string = selected_vertex.getAttribute("id");
					vertex_id = vertex_id_string.split("_");
					wktarray = get_array_from_wktstring(top.currentform.newpathwkt.value);
					wktarray[parseInt(vertex_id[1])] = vertex_new_world_x;
					wktarray[parseInt(vertex_id[1])+1] = vertex_new_world_y;
					if(vertex_id[2] != ""){			// Anfangs und Endpunkt
						wktarray[parseInt(vertex_id[2])] = vertex_new_world_x;
						wktarray[parseInt(vertex_id[2])+1] =  vertex_new_world_y;
					}
					wktstring = "";
					komma = 1;
					for(i = 0; i < wktarray.length; i++){
						if(wktarray[i] != ""){
							wktstring = wktstring + wktarray[i];
							if(i > 0 && String(wktarray[i]).lastIndexOf(")") == -1 && String(wktarray[i+1]).lastIndexOf(")") == -1){		// Kommas einfuegen
								if(komma == 2){
									wktstring = wktstring + ",";
									komma = 1;
								}
								else{
									if(komma == 1){
										wktstring = wktstring + " ";
										komma = 2;
									}
								}
							}
							else{
								komma = 1;
							}
						}
					}
					top.currentform.newpathwkt.value = wktstring;
				}
				remove_vertices();													// alle entfernen
				remove_in_between_vertices();
				pixel_path = world2pixelsvg(top.currentform.newpath.value);
				add_vertices(pixel_path);										// und wieder hinzufuegen
				polygonarea();
			}
			else{
				vertex_id_string = evt.target.getAttribute("id");
				vertex_id = vertex_id_string.split("_");
				if(vertex_id[1] == "new"){
					insert_vertex(evt);
				}
			}
			selected_vertex = "";
			vertex_moved = false;
		}
	}

	function remove_vertices(){
		var parent = document.getElementById("vertices");
		var count = parent.childNodes.length;
		for(i = 0; i < count; i++){
			parent.removeChild(parent.lastChild);
		}
	}
	
	function remove_in_between_vertices(){
		var parent = document.getElementById("in_between_vertices");
		var count = parent.childNodes.length;
		for(i = 0; i < count; i++){
			parent.removeChild(parent.lastChild);
		}
	}
	
	function add_vertices(pixel_path){
		pixel_path = pixel_path+"";
		components = pixel_path.split(" ");
		var parent = document.getElementById("vertices");
		var parent2 = document.getElementById("in_between_vertices");
		circle = new Array();
		circle2 = new Array();
		line = new Array();
		kreis1 = document.getElementById("kreis");
		linie1 = document.getElementById("linie");
		start = 1;
		for(i = 1; i < components.length-3; i=i+2){
			// Zwischenlinien
			line[i] = linie1.cloneNode(true);
			line[i].setAttribute("x1", components[i]);
			line[i].setAttribute("y1", components[i+1]);
			line[i].setAttribute("x2", components[i+2]);
			line[i].setAttribute("y2", components[i+3]);
			line[i].setAttribute("style","stroke: #FF0000");
			line[i].setAttribute("opacity", "0.01");
			line[i].setAttribute("id", "line_new_"+i);
			parent2.appendChild(line[i]);
			// Zwischenpunkte
			circle2[i] = kreis1.cloneNode(true);
			circle2[i].setAttribute("cx", parseInt(components[i])-(parseInt(components[i])-parseInt(components[i+2]))/2);
			circle2[i].setAttribute("cy", parseInt(components[i+1])-(parseInt(components[i+1])-parseInt(components[i+3]))/2);
			circle2[i].setAttribute("style","fill: #FF0000");
			circle2[i].setAttribute("opacity", "0.01");
			circle2[i].setAttribute("id", "vertex_new_"+i);
			parent2.appendChild(circle2[i]);
			// Eckpunkte
			circle[i] = kreis1.cloneNode(true);
			circle[i].setAttribute("cx", components[i]);
			circle[i].setAttribute("cy", components[i+1]);
			circle[i].setAttribute("style","fill: #FF0000");
			circle[i].setAttribute("id", "vertex_"+i);
			parent.appendChild(circle[i]);
			// Start und Endpunkt
			if(components[i+4] == "M" || components[i+4] == ""){
				circle[start].setAttribute("id", "vertex_"+start+"_"+parseInt(i+2));
				start = i+5;
				i = i + 3;
			}
		}
	}

	function edit_vertices(){
		applypolygons();
		save_geometry_for_undo();
		top.currentform.last_doing.value = "vertex_edit";
		pixel_path = world2pixelsvg(top.currentform.newpath.value);
		add_vertices(pixel_path);
	}


	function save_geometry_for_undo(){
		newpath_undo = top.currentform.newpath.value;
		newpathwkt_undo = top.currentform.newpathwkt.value;
	}

	function undo_geometry_editing(){
		top.currentform.newpath.value = newpath_undo;
		top.currentform.newpathwkt.value = newpathwkt_undo;
		remove_vertices();													// alle entfernen
		remove_in_between_vertices();
		pixel_path = world2pixelsvg(top.currentform.newpath.value);
		add_vertices(pixel_path);										// und wieder hinzufuegen
		redrawfirstpolygon();
	}

	function redrawsecondpolygon(){
	  // polygone um punktepfad erweitern
	  var obj = document.getElementById("polygon_first");
	  pixel_path = world2pixelsvg(top.currentform.newpath.value);
	  obj.setAttribute("d", pixel_path);
	  pixel_path_second = world2pixelsvg(path_second);
	  var obj = document.getElementById("polygon_second");
	  obj.setAttribute("d", pixel_path_second);
	}

	function buildwktpolygonfromsvgpath(svgpath){
		if(svgpath != ""){
			var koords;
			wkt = "POLYGON((";
			parts = svgpath.split("M");
			for(j = 1; j < parts.length; j++){
				if(j > 1){
					wkt = wkt + "),("
				}
				koords = ""+parts[j];
				coord = koords.split(" ");
				wkt = wkt+coord[1]+" "+coord[2];
				for(var i = 3; i < coord.length-1; i++){
					if(coord[i] != ""){
						wkt = wkt+","+coord[i]+" "+coord[i+1];
					}
					i++;
				}
			}
			wkt = wkt+"))";
			return wkt;
		}
		else{
			return "";
		}
	}

	function getxcoordsfromsvgpath(path){
		xcoords = new Array();
		parts = path.split(" ");
		for(i = 1; i < parts.length; i=i+2){
			if(parts[i] != ""){
				xcoords.push(parts[i]);
			}
		}
		xcoords.pop();
		return xcoords;
	}

	function getycoordsfromsvgpath(path){
		ycoords = new Array();
		parts = path.split(" ");
		for(i = 2; i < parts.length; i=i+2){
			if(parts[i] != ""){
				ycoords.push(parts[i]);
			}
		}
		ycoords.pop();
		return ycoords;
	}

	function world2pixelsvg(pathWelt) {
		explosion = pathWelt.split(" ");
		for(i = 0; i < explosion.length; i++){
			if(explosion[i] != "M" && explosion[i] != ""){
				explosion[i] = Math.round((explosion[i] - minx)/scale);
				explosion[i+1] = Math.round((explosion[i+1] - miny)/scale);
				i++;
			}
 		}
		pixelpath = "";
		for(i = 0; i < explosion.length; i++){
			pixelpath = pixelpath + explosion[i] + " ";
		}
		return pixelpath;
	}

	function deletelast(evt){
		switch(top.currentform.last_doing.value){
			case "draw_polygon":
	  		if(pathx.length > 3){
					pathx.pop();
					pathy.pop();
					path = buildsvgpath(pathx,pathy);
					top.currentform.newpath.value = path;
					redrawfirstpolygon();
				}
			break;
			case "draw_second_polygon": case "subtract_polygon": 
				if(pathx_second.length > 3){
					pathx_second.pop();
					pathy_second.pop();
					str = top.currentform.pathx_second.value;
					top.currentform.pathx_second.value = str.substring(0, str.lastIndexOf(";"));
					str = top.currentform.pathy_second.value;
					top.currentform.pathy_second.value = str.substring(0, str.lastIndexOf(";"));
					path_second = buildsvgpath(pathx_second,pathy_second);
					if(top.currentform.last_doing.value == "draw_second_polygon"){
						top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&path1="+top.currentform.pathwkt.value+"&path2="+path_second+"&operation=add&resulttype=svgwkt&layer_id="+top.currentform.layer_id.value, new Array(top.currentform.result, ""), new Array("setvalue", "execute_function"));
					}
					else{
						if(top.currentform.last_doing.value == "subtract_polygon"){				
							top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&path1="+top.currentform.pathwkt.value+"&path2="+path_second+"&operation=subtract&resulttype=svgwkt&layer_id="+top.currentform.layer_id.value, new Array(top.currentform.result, ""), new Array("setvalue", "execute_function"));
						}
					}
					redrawsecondpolygon();
				}
			break;
			case "add_buffered_line":
				if(pathx_second.length > 1){
					pathx_second.pop();
					pathy_second.pop();
					str = top.currentform.pathx_second.value;
					top.currentform.pathx_second.value = str.substring(0, str.lastIndexOf(";"));
					str = top.currentform.pathy_second.value;
					top.currentform.pathy_second.value = str.substring(0, str.lastIndexOf(";"));
					path_second = buildsvglinepath(pathx_second, pathy_second);
					top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&path1="+top.currentform.pathwkt.value+"&path2="+path_second+"&operation=add_buffered_line&width="+top.currentform.bufferwidth.value+"&geotype=line&resulttype=svgwkt&layer_id="+top.currentform.layer_id.value, new Array(top.currentform.result, ""), new Array("setvalue", "execute_function"));
					redrawsecondpolygon();
				}
			break;
			case "add_parallel_polygon":
				if(pathx_second.length > 2){
					pathx_second.pop();
					pathy_second.pop();
					str = top.currentform.pathx_second.value;
					top.currentform.pathx_second.value = str.substring(0, str.lastIndexOf(";"));
					str = top.currentform.pathy_second.value;
					top.currentform.pathy_second.value = str.substring(0, str.lastIndexOf(";"));
					path_second = buildsvglinepath(pathx_second, pathy_second);
					top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&path1="+top.currentform.pathwkt.value+"&path2="+path_second+"&operation=add_parallel_polygon&width="+top.currentform.bufferwidth.value+"&geotype=line&resulttype=svgwkt&layer_id="+top.currentform.layer_id.value, new Array(top.currentform.result, ""), new Array("setvalue", "execute_function"));
					redrawsecondpolygon();
				}
			break;
			case "vertex_edit":
				undo_geometry_editing();
			break;
		}
	}

	function restart(){
		top.currentform.last_doing.value = "draw_polygon";
		top.currentform.last_doing2.value = "draw_polygon";
		textx = -1000000;
		texty = -1000000;
		redrawpoint();
		top.currentform.newpath.value = "";
		top.currentform.pathwkt.value = "";
		top.currentform.newpathwkt.value = "";
		top.currentform.result.value = "";
		top.currentform.INPUT_COORD.value = "";
		top.currentform.area.value = "";
		path = "";
		top.currentform.firstpoly.value = false;
		top.currentform.secondpoly.value = false;
		var alle = pathx.length;
		for(var i = 0; i < alle; ++i){
		  pathx.pop();
		  pathy.pop();
		}
		var alles = pathx_second.length;
		for(var i = 0; i < alles; ++i){
			pathx_second.pop();
			pathy_second.pop();
		}
		top.currentform.pathx_second.value = "";
		top.currentform.pathy_second.value = "";
		path_second = "";
		var alle = boxx.length;
		for(var i = 0; i < alle; ++i){
		  boxx.pop();
		  boxy.pop();
		}
		document.getElementById("startvertex").setAttribute("cx", -500);
		document.getElementById("startvertex").setAttribute("cy", -500);
		redrawsecondpolygon();
		redraw();
	}

	function applypolygons(){
		if(top.currentform.pathwkt.value == "" && top.currentform.newpath.value != ""){
			top.currentform.pathwkt.value = buildwktpolygonfromsvgpath(top.currentform.newpath.value);
		}
		else{
			if(top.currentform.newpathwkt.value != ""){
				top.currentform.pathwkt.value = top.currentform.newpathwkt.value;
			}
		}
		if(top.currentform.secondpoly.value == "true"){
			path = top.currentform.newpath.value;
			var length = pathx_second.length;
			for(i = 0; i < length; i++ ){
				pathx_second.pop();
				pathy_second.pop();
			}
			path_second = buildsvgpath(pathx_second, pathy_second);
			redrawsecondpolygon();
			top.currentform.secondpoly.value = false;
			top.currentform.pathx_second.value = "";
			top.currentform.pathy_second.value = "";
		}
	}

	function subtr_polygon(){
		applypolygons();
		top.currentform.last_doing.value = "subtract_polygon";
	}

	function add_polygon(){
		var alles = pathx_second.length;
		for(var i = 0; i < alles; ++i){
			pathx_second.pop();
			pathy_second.pop();
		}
		top.currentform.pathx_second.value = "";
		top.currentform.pathy_second.value = "";
		applypolygons();
		if(top.currentform.firstpoly.value == "true"){
			top.currentform.last_doing.value = "draw_second_polygon";
		}
		else{
			top.currentform.last_doing.value = "draw_polygon";
		}
	}

	function polygonarea(){
		area = top.document.getElementById("custom_area");
		if(area == undefined){						// wenn es ein Flaeche-Attribut gibt, wird das verwendet, ansonsten die normale Flaechenanzeige
			area = top.currentform.area;
		}
	  if(top.currentform.newpathwkt.value != ""){
	  	if(top.currentform.areaunit == undefined){
	  		top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&path1="+top.currentform.newpathwkt.value+"&operation=area&layer_id="+top.currentform.layer_id.value, new Array(top.currentform.area, area), "");
	  	}
	  	else{
	  		top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&path1="+top.currentform.newpathwkt.value+"&operation=area&unit="+top.currentform.areaunit.value+"&layer_id="+top.currentform.layer_id.value, new Array(top.currentform.area, area), "");
	  	}
	  }
	  else{
	  	if(top.currentform.newpath.value != ""){
	  		if(top.currentform.areaunit == undefined){
	  			top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&path2="+top.currentform.newpath.value+"&operation=area&layer_id="+top.currentform.layer_id.value, new Array(top.currentform.area, area), "");
	  		}
	  		else{
	  			top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&path2="+top.currentform.newpath.value+"&operation=area&unit="+top.currentform.areaunit.value+"&layer_id="+top.currentform.layer_id.value, new Array(top.currentform.area, area), "");
	  		}
	  	}
	  	else{
	  		top.currentform.area.value = "0.0";
	  	}
	  }
	}
	';

$transformfunctions = '

	transformfunctions = true;

//---------------------- Verschieben der Geometrie -------------

	function move_geometry(){
		document.getElementById("canvas").setAttribute("cursor", "move");
		top.currentform.last_doing.value = "move_geometry";
		if(polygonfunctions){
			applypolygons();
		}
		else{
			applylines();
		}
	}
	
	function startMoveGeom(clientx, clienty){
		movinggeom  = true;
	  move_x[0] = clientx;
	  move_y[0] = resy - clienty;
	}
	
	function moveGeom(evt){
		move_x[1] = evt.clientX;
	  move_y[1] = evt.clientY;
	  move_dx = move_x[1]-move_x[0];
	  move_dy = move_y[1]-move_y[0];
		dy = move_dy + resy;
	  path = "translate("+move_dx+" "+dy+") scale(1,-1)";
	  document.getElementById("cartesian").setAttribute("transform", path);
		moved = true;
	}

	function endMoveGeom(evt) {
	  if(moved){
			translate_x = (move_dx * scale);
	  	translate_y = (move_dy * scale * -1);
			top.currentform.secondpoly.value = true;
			top.currentform.secondline.value = true;
			must_redraw = true;
			top.ahah("'.URL.APPLVERSION.'index.php", "go=spatial_processing&path1="+top.currentform.pathwkt.value+"&translate_x="+translate_x+"&translate_y="+translate_y+"&operation=translate&resulttype=svgwkt", new Array(top.currentform.result, ""), new Array("setvalue", "execute_function"));
		}
	  movinggeom  = false;
	  moved  = false;
	}
	

//---------------------- Verschieben der Geometrie -------------

';

$vertex_catch_functions = '

	//-------------------- Punktfang -----------------------------

	top.document.getElementById("vertices").SVGtoggle_vertices = toggle_vertices;		// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen

	function toggle_vertices(){
		if(top.currentform.punktfang.checked){
			request_foreign_vertices();
		}
		else{
			remove_foreign_vertices();
		}
	}

	function request_foreign_vertices(){
		top.ahah("'.URL.APPLVERSION.'index.php", "go=getSVG_foreign_vertices&layer_id="+top.currentform.layer_id.value+"&oid="+top.currentform.oid.value, new Array(top.currentform.vertices, ""), new Array("setvalue", "execute_function"));
	}

	function remove_foreign_vertices(){
		var parent = document.getElementById("foreignvertices");
		var count = parent.childNodes.length;
		for(i = 0; i < count-2; i++){
			parent.removeChild(parent.lastChild);
		}
	}

	function activate_foreign_vertex(evt){
		if(top.currentform.last_doing.value == "vertex_edit" && (selected_vertex == undefined || selected_vertex == "")){
			// wenn man im Vertex-Edit Modus ist, die Events von diesem foreign-vertex ausschalten, damit die Geometrie-Vertices Vorrang haben 
			evt.target.setAttribute("pointer-events", "none");
			deactivated_foreign_vertex = evt.target.getAttribute("id");  
		}
		else{
			evt.target.setAttribute("opacity", "1");
		}
	}

	function deactivate_foreign_vertex(evt){
		evt.target.setAttribute("opacity", "0.3");
	}

	function add_foreign_vertex(evt){
		// punktobjekt bilden, welches die Koordinaten aufnimmt
    function point(x,y) {
      this.clientX = x;
      this.clientY = y;
    }
		// Aufrufen der Funktion mousedown() fuer die jeweilige Aktion
    position= new point(evt.target.getAttribute("x"), evt.target.getAttribute("y"));
		if(top.currentform.last_doing.value == "vertex_edit"){
			if(last_selected_vertex != ""){
				selected_vertex = last_selected_vertex;
				position.target = selected_vertex;  
				move_vertex(position, last_selected_vertex, "world");
				end_vertex_move(position);
			}
		}
		else{
			mouse_coords_type = "world";
	  	mousedown(position);
			mouse_coords_type = "image";
		}  
	}

	top.document.getElementById("svghelp").SVGshow_foreign_vertices = show_foreign_vertices;		// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen

	function show_foreign_vertices(){
		if(top.currentform.vertices.value != ""){
			var parent = document.getElementById("foreignvertices");
			circle = new Array();
			var kreis1 = document.getElementById("kreis3");
			vertex_string = top.currentform.vertices.value+"";
			top.currentform.vertices.value = "";
			vertices = vertex_string.split("|");
			for(i = 0; i < vertices.length-1; i++){
				circle[i] = kreis1.cloneNode(true);
				coords = vertices[i].split(" ");
				circle[i].setAttribute("x", coords[0]);
				circle[i].setAttribute("y", coords[1]);
				coords[0] = Math.round((coords[0] - parseFloat(top.currentform.minx.value))/parseFloat(top.currentform.pixelsize.value));
				coords[1] = Math.round((coords[1] - top.currentform.miny.value)/parseFloat(top.currentform.pixelsize.value));
				circle[i].setAttribute("cx", coords[0]);
				circle[i].setAttribute("cy", coords[1]);
				circle[i].setAttribute("style","fill: #00DD00");
				circle[i].setAttribute("id", "foreign_vertex_"+i);
				parent.appendChild(circle[i]);
			}
		}
	}

	//------------------------------------------------------------

';	

$gps_functions = '  
  function update_gps_position(){
		posx = top.currentform.gps_posx.value+"";
		posy = top.currentform.gps_posy.value+"";
		if(posx != "" && posy != ""){
			x = (posx - minx)/scale;
			y = (posy - miny)/scale;
			//alert(x+" "+y);
	  	var pos = document.getElementById("gps_position");
	  	pos.setAttribute("x", x);
	  	pos.setAttribute("y", y);
			if(top.currentform.gps_follow.value == "on"){
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
  	top.ahah("'.URL.APPLVERSION.'index.php", "go=get_gps_position&srs='.$this->user->rolle->epsg_code.'", new Array(top.currentform.gps_posx, top.currentform.gps_posy), "");
 	}

	function set_gps_position() {
    // punktobjekt bilden, welches die Koordinaten aufnimmt
    function point(x,y) {
      this.clientX = x;
      this.clientY = y;
    }
    // Abfragen der aktuellen GPS Position
		if(top.currentform.gps_posx.value != "" && top.currentform.gps_posy.value != ""){
			// Aufrufen der Funktion mousedown() fuer die jeweilige Aktion
	    position= new point(top.currentform.gps_posx.value, top.currentform.gps_posy.value);
			if(top.currentform.last_doing.value == "vertex_edit"){
				if(last_selected_vertex != ""){
					selected_vertex = last_selected_vertex;
					position.target = selected_vertex; 
					move_vertex(position, last_selected_vertex, "world");
					end_vertex_move(position);
				}
			}
			else{
				mouse_coords_type = "world";
		  	mousedown(position);
				mouse_coords_type = "image";
			}
		}    		
  }

	function switch_gps_follow(){
		if(top.currentform.gps_follow.value == "on"){
			top.currentform.gps_follow.value = "off";
			document.getElementById("gps_text").firstChild.data = "off";
		}
		else{
			top.currentform.gps_follow.value = "on";
			document.getElementById("gps_text").firstChild.data = "on";
		}
	}

 	window.setInterval("update_gps_position()", 2000);
	';

$measurefunctions = '

	measurefunctions = true;

	function save_measure_path(){
		var length = m_pathx.length;
		if(length > 0){
			var str_pathx = (m_pathx[0] * scale) + minx;
			var str_pathy = (m_pathy[0] * scale) + miny;
		  for(var i = 1; i < length; i++){
		    str_pathx = str_pathx + ";" + ((m_pathx[i] * scale) + minx);
				str_pathy = str_pathy + ";" + ((m_pathy[i] * scale) + miny);
			}
			top.currentform.str_pathx.value = str_pathx;
			top.currentform.str_pathy.value = str_pathy;
		}
	}
	
	function get_measure_path(){
		if(top.currentform.str_pathx.value != ""){
			doing = "measure";
			measuring = true;
			var str_pathx = top.currentform.str_pathx.value;
			var str_pathy = top.currentform.str_pathy.value;
			world_pathx = str_pathx.split(";");
			world_pathy = str_pathy.split(";");  
			m_pathx[0] = (world_pathx[0] - minx)/scale;
			m_pathy[0] = (world_pathy[0] - miny)/scale;
			var length = world_pathx.length; 
		  for(var i = 1; i < length; i++){
		    m_pathx[i] = (world_pathx[i] - minx)/scale;
				m_pathy[i] = (world_pathy[i] - miny)/scale;
			}
		}
	}

	function startMeasure(client_x, client_y) {
	  restart_m();
	  measuring = true;
	  m_pathx[0] = client_x;
	  m_pathy[0] = client_y;
	}
	
	function showMeasurement(client_x, client_y){
	  addpoint(client_x, client_y);
	  
	  var track = 0, track0 = 0, part0 = 0, parts = 0, output = "";
	    for(var j = 0; j < m_pathx.length-1; ++j)
	    {
	      part0 = parts;
	      parts = parts + Math.sqrt(((m_pathx[j]-m_pathx[j+1])*(m_pathx[j]-m_pathx[j+1]))+((m_pathy[j]-m_pathy[j+1])*(m_pathy[j]-m_pathy[j+1])));
	    }
	//  track = Math.round((parts*scale)*100)/100;
	  track0  = part0*scale;
	  track = parts*scale;
	  if(scale < 0.01){
	    stellen = 2;
	  }
	  else if(scale < 0.1){
	    stellen = 1;
	  }
	  else{
	    stellen = 0;
	  }
	  track0 = top.format_number(track0, false);
	  track = top.format_number(track, false);
	
	  output = "Strecke: "+track+" m ("+track0+" m)";
	  show_tooltip(output,client_x,resy-client_y);
	
	  deletelast_m();
	}
	
	function addpoint(client_x, client_y) {
	  m_pathx.push(client_x);
	  m_pathy.push(client_y);
	  redrawPL();
	}
	
	function deletelast_m() {
	  m_pathx.pop();
	  m_pathy.pop();
	}
	
	function restart_m()
	{
	  var alle = m_pathx.length;
	  for(var i = 0; i < alle; ++i)
	   {
	    m_pathx.pop();
	    m_pathy.pop();
	   }
	  redrawPL();
	}
	
	function redrawPL() 
	{
	  // punktepfad erstellen
	  path = "";
	  for(var i = 0; i < m_pathx.length; ++i)
	   {
	    path = path+" "+m_pathx[i]+","+m_pathy[i];
	   }
	  // polyline um punktepfad erweitern
	  document.getElementById("polyline").setAttribute("points", path);
	}

';

	$canvaswithall = '
	  <rect id="background" style="fill:white" width="100%" height="100%"/>
		<g id="moveGroup" transform="translate(0 0)">
		  <image xlink:href="'.$bg_pic.'" height="100%" width="100%" y="0" x="0"/>
		  <g id="cartesian" transform="translate(0,'.$res_y.') scale(1,-1)">
				<path d="" id="line_second" style="fill:none;stroke:red;stroke-width:2" />
		  	<path d="" id="line_first" style="fill:none;stroke:blue;stroke-width:2"/>
		    <path d="" id="polygon_second" style="fill:none;stroke:red;stroke-width:2"/>
		    <path d="" id="polygon_first" style="fill-opacity:0.5;fill:rgb(192,192,255);stroke:blue;stroke-width:1.5"/>
		    <polygon points="" id="polygon" style="fill-opacity:0.5;fill:rgb(192,192,255);stroke:blue;stroke-width:2"/>
				<polyline points="" id="polyline" style="fill:none;stroke-dasharray:2,2;stroke:black;stroke-width:4"/>
				<use id="gps_position" style="stroke:red;" xlink:href="#crosshair_red" x="-1000" y="-1000"/>
				<use id="pointposition" xlink:href="#crosshair_blue" x="-500" y="-500"/>
				<circle id="startvertex" cx="-500" cy="-500" r="2" style="fill:blue;stroke:blue;stroke-width:2"/>
			</g>
			<rect id="canvas" cursor="crosshair" onmousedown="mousedown(evt);" onmousemove="mousemove(evt);hide_tooltip();" onmouseup="mouseup(evt);" width="100%" height="100%" opacity="0" visibility="visible"/>
			<g id="in_between_vertices" transform="translate(0,'.$res_y.') scale(1,-1)">			
			</g>
			<g id="vertices" transform="translate(0,'.$res_y.') scale(1,-1)">			
			</g>
			<g id="foreignvertices" transform="translate(0,'.$res_y.') scale(1,-1)">
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
		    
		    <!--animateTransform id="waiting_animation" attributeName="transform" attributeType="XML" type="rotate" begin="indefinite" dur="1s" repeatCount="indefinite" calcMode="discrete"
		    keyTimes="0;.0833;.166;.25;.3333;.4166;.5;.5833;.6666;.75;.8333;.9166;1"
		    values="0,0,0;30,0,0;60,0,0;90,0,0;120,0,0;150,0,0;180,0,0;210,0,0;240,0,0;270,0,0;300,0,0;330,0,0;360,0,0"/-->
	    </g>
	  </g>
	  <g id="templates">
	  	<circle style="-moz-user-select: none;" id="kreis" cx="-5000" cy="-5000" r="7" opacity="0.3" onmouseover="activate_vertex(evt)" onmouseout="deactivate_vertex(evt)" onmousedown="select_vertex(evt)" onmousemove="move_vertex(evt)" onmouseup="end_vertex_move(evt)" />
			<line stroke="#111" stroke-width="14" id="linie" x1="-5000" y1="-5000" x2="-5001" y2="-5001" opacity="0.3" onmouseover="activate_line(evt)" onmousemove="activate_line(evt)" />
			<circle id="kreis3" cx="-5000" cy="-5000" r="7" opacity="0.3" onmouseover="activate_foreign_vertex(evt)" onmouseout="deactivate_foreign_vertex(evt)" onmouseup="add_foreign_vertex(evt)" />
	  </g>
	  

	  <g id="alleButtons" transform="translate(0 0)">
	  ';

	$navbuttons ='
	    <g id="buttons_NAV" cursor="pointer" onmousedown="hide_tooltip()" onmouseout="hide_tooltip()">
	'.$SVGvars_navbuttons.'
			</g>
	';

	$last_x = 0;
	function polygonbuttons($strUndo, $strDeletePolygon, $strDrawPolygon, $strCutByPolygon){
		global $last_x;
		$polygonbuttons = '
	      <g id="new" onmousedown="restart();highlightbyid(\'pgon0\');" transform="translate(0 0 )">
	        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
	        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
	        	<set attributeName="filter" begin="new0.mousedown" dur="0s" fill="freeze" to="none"/>
						<set attributeName="filter" begin="new0.mouseup;new0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
					</rect>
					<path
						d="M153.245 92.3923 C153.245 92.3923 198.593 158.983 242.478 202.712 C268.178 171.839
							355.521 99.5384 355.521 99.5384 C355.521 99.5384 359.726 95.6673 364.892 97.8494
							C370.059 100.031 369.612 108.695 369.612 108.695 C369.612 108.695 382.322
							102.528 382.138 102.36 C399.506 105.821 402.66 122.15 402.66 122.15 C402.66
							122.15 322.203 196.362 285.632 240.453 C314.809 273.344 401.918 327.398 408.194
							337.179 C409.657 339.459 396.571 356.768 409.721 364.549 C422.871 372.333
							402.255 381.996 395.718 384.985 C394.3 384.345 384.14 378.487 384.14 378.487
							C382.185 390.247 369.745 392.867 369.745 392.867 C369.745 392.867 279.597 315.208
							246.866 283.273 C218.575 317.649 164.421 399 164.421 399 C142.608 400.165
							135.679 389.138 135.679 389.138 C135.679 389.138 128.054 398.628 119.9 394.204
							C111.747 389.78 112.548 379.796 112.548 379.796 C112.548 379.796 98.637
							382.887 94 373.273 C114.297 334.079 197.862 244.808 197.862 244.808 C160.996
							217.608 96.482 122.942 96.482 122.942 C96.482 122.942 96.6355 104.726 106.055
							98.6784 C104.503 99.1631 119.583 106.53 123.756 104.003 C131.237 99.4715 127.702
							105.026 130.236 95.2056 C132.77 85.3855 153.245 92.3923 153.245 92.3923 z"
						transform="matrix(1 0 0 1 0 0) translate(2 2) scale(0.042)" style="fill:rgb(0,0,0)"/>
	        <rect id="new0" onmouseover="show_tooltip(\''.$strDeletePolygon.'\',evt.clientX,evt.clientY)" x="0" y="0" rx="1" ry="1" width="25" height="25" opacity="0.2"/>
	      </g>
	      
	      <g id="undo" onmousedown="deletelast(evt);" transform="translate(26 0)">
	        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
	        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
	        	<set attributeName="filter" begin="undo0.mousedown" dur="0s" fill="freeze" to="none"/>
						<set attributeName="filter" begin="undo0.mouseup;undo0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
					</rect>
					<g transform="translate(0 -4.5)">
						<polygon points="178.579,57.7353 164.258,51.2544 178.96,44.515 174.48,51.1628"
							 style="fill:rgb(0,0,0);stroke:rgb(0,0,0);stroke-width:2" transform="scale(0.7) translate(-5 0) rotate(320.992 13.3045 25.4374) rotate(2.66158 14.8833 25.1086) translate(-0.0187408 0.792412) translate(-0.846674 1.15063) translate(0.846674 -1.15063) translate(-8.46674 11.5063) translate(8.46674 -11.5063) rotate(1 14.8488 24.959) rotate(1 14.8322 24.9037) rotate(1 14.8138 24.849) rotate(1 14.7934 24.795) rotate(1 14.7712 24.7418) rotate(1 14.7471 24.6893) rotate(-1 14.7212 24.6378) rotate(-1 14.7471 24.6893) rotate(-1 14.7712 24.7418) rotate(-1 14.7934 24.795) rotate(-1 14.8138 24.849) rotate(-1 14.8322 24.9037) rotate(-1 14.8488 24.959) rotate(-1 14.8634 25.0148) rotate(-1 14.876 25.0711) translate(-0.205848 0.251079) rotate(-1 14.8867 25.1278) rotate(-1 14.8954 25.1849) rotate(-1 14.9021 25.2422) rotate(-1 14.9067 25.2997) rotate(-1 14.9094 25.3573) translate(0.318975 -0.00363643) matrix(-1 0 0 1 100 -25) scale(0.5) rotate(180) translate(-345 -152)"/>
						<path d="M137.5 355 C230.674 287.237 311.196 227.137 396.5 349"
							transform="matrix(1 0 0 1 0 0) scale(0.05)"
							 style="fill:none;stroke:rgb(0,0,0);stroke-width:30"/>
					</g>
	        <rect id="undo0" onmouseover="show_tooltip(\''.$strUndo.'\',evt.clientX,evt.clientY)" x="0" y="0" rx="1" ry="1" width="25" height="25" transform="translate(0 0)" opacity="0.2"/>
	      </g>

				<g id="pgon" onmousedown="draw_pgon_on();add_polygon();highlightbyid(\'pgon0\');" transform="translate(52 0 )">
		      <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
		      <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
		      	<set attributeName="filter" begin="pgon0.mousedown" dur="0s" fill="freeze" to="none"/>
						<set attributeName="filter" begin="pgon0.mouseup;pgon0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
					</rect>
					<polygon
						points="252.5,91 177.5,113 106.5,192 128.5,260 116.5,354 127.5,388 173.5,397 282.5,331 394.5,284
							379.5,218 378.5,139 357.5,138 260.5,91"
						transform="matrix(1 0 0 1 0 0) scale(0.05)"
						 style="fill:rgb(144,144,144);stroke:rgb(0,0,0);stroke-width:25"/>
					<rect id="pgon0" onmouseover="show_tooltip(\''.$strDrawPolygon.'\',evt.clientX,evt.clientY)" x="0" y="0" width="25" height="25" opacity="0.2"/>
				</g>

				<g id="pgon_subtr" onmousedown="draw_pgon_on();subtr_polygon();highlightbyid(\'pgon_subtr0\');" transform="translate(78 0 )">
		      <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
		      <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(111,111,111);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
		      	<set attributeName="filter" begin="pgon_subtr0.mousedown" dur="0s" fill="freeze" to="none"/>
						<set attributeName="filter" begin="pgon_subtr0.mouseup;pgon_subtr0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
					</rect>
					<polygon
						points="252.5,91 177.5,113 106.5,192 128.5,260 116.5,354 127.5,388 173.5,397 282.5,331 394.5,284
							379.5,218 378.5,139 357.5,138 260.5,91"
						transform="matrix(1 0 0 1 0 0) scale(0.05)"
						 style="fill:rgb(244,244,244);stroke:rgb(0,0,0);stroke-width:25"/>
					<rect id="pgon_subtr0" onmouseover="show_tooltip(\''.$strCutByPolygon.'\',evt.clientX,evt.clientY)" x="0" y="0" width="25" height="25" opacity="0.2"/>
				</g>
		';
		$last_x = 78;
		return $polygonbuttons;
	}
	function gpsbuttons($strSetGPSPosition, $gps_follow){
		global $last_x;
		$last_x += 26;
		$gpsbuttons = '
			<g id="gps" onmousedown="set_gps_position();" transform="translate('.$last_x.' 0 )">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
        	<set attributeName="filter" begin="gps1.mousedown" dur="0s" fill="freeze" to="none"/>
					<set attributeName="filter" begin="gps1.mouseup;gps1.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
				</rect>
				<g transform="scale(0.5) translate(2 8)">
					<text x="23" y="15" style="text-anchor:middle;fill:black;font-size:10;font-family:Arial;font-weight:bold">
					GPS</text>
					<circle cx="23" cy="21" r="3"/>
				</g>
				<rect id="gps1" onmouseover="show_tooltip(\'GPS-Position \'+unescape(\'%FC\')+\'bernehmen\',evt.clientX,evt.clientY)" x="0" y="0" rx="1" ry="1" width="25" height="25" fill="none" opacity="0.2"/>
	    </g>';
		$last_x += 26;
		$gpsbuttons.= '
			<g id="gps_f" transform="translate('.$last_x.' 0 )">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="gps0.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="gps0.mouseup;gps0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <g transform="scale(0.6) translate(8 8)">
          <use xlink:href="#1move" transform="translate(2.1 -5.9) scale(0.9)"/> 
        </g>
				<text transform="scale(0.45 0.45)" x="22" y="19" style="text-anchor:middle;fill:rgb(0,0,0);font-size:20;font-family:Arial;font-weight:bold;">GPS</text>
				<text id="gps_text" transform="scale(0.45 0.45)" x="16" y="50" style="text-anchor:middle;fill:rgb(0,0,0);font-size:20;font-family:Arial;font-weight:bold;">'.$gps_follow.'</text>	
        <rect id="gps0" onmouseover="show_tooltip(\'GPS-Verfolgungsmodus\',evt.clientX,evt.clientY)" onmousedown="hide_tooltip();switch_gps_follow();" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;fill-opacity:0.25"/>
      </g>
		';
		return $gpsbuttons;
	}

	function pointbuttons($strSetPosition){
		global $last_x;
		$last_x += 26;
		$pointbuttons = '
				<g id="text" onmousedown="draw_point();highlightbyid(\'text0\');" transform="translate('.$last_x.' 0 )">
	        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
	        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
	        	<set attributeName="filter" begin="text0.mousedown" dur="0s" fill="freeze" to="none"/>
						<set attributeName="filter" begin="text0.mouseup;text0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
					</rect>
					<g transform="scale(0.5) translate(2 8)">
						<text x="23" y="15" style="text-anchor:middle;fill:black;font-size:10;font-family:Arial;font-weight:bold">
						Punkt</text>
						<circle cx="23" cy="21" r="3"/>
					</g>
					<rect id="text0" onmouseover="show_tooltip(\''.$strSetPosition.'\',evt.clientX,evt.clientY)" x="0" y="0" rx="1" ry="1" width="25" height="25" fill="none" opacity="0.2"/>
		    </g>
		';
		return $pointbuttons;
	}

	function boxbuttons(){
		global $last_x;
		$last_x += 26;
		$boxbuttons = '
				<g id="box" onmousedown="draw_box_on();highlightbyid(\'box0\');" transform="translate('.$last_x.' 0)">
	        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
	        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
	          <set attributeName="filter" begin="box0.mousedown" dur="0s" fill="freeze" to="none"/>
	          <set attributeName="filter" begin="box0.mouseup;box0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
	        </rect>
	        <g transform="scale(0.7) translate(-5 0)">
	          <g transform="matrix(-1 0 0 1 118 0) scale(0.5)">
	            <rect x="170" y="30" width="40" height="14" style="fill:none;stroke:rgb(0,0,0);stroke-width:4"/>
	          </g>
	        </g>
	        <rect id="box0" onmouseover="show_tooltip(\'Fenster aufziehen\',evt.clientX,evt.clientY)" x="0" y="0" rx="1" ry="1" width="25" height="25" fill="none" opacity="0.2"/>
	      </g>
		';
		return $boxbuttons;
	}

	function linebuttons($strUndo, $strDeleteLine, $strDrawLine, $strDelLine, $strSplitLine){
		global $last_x;
		$linebuttons = '
				 <g id="undo" onmousedown="deletelastline(evt);" transform="translate(0 0)">
	        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
	        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
	        	<set attributeName="filter" begin="undo0.mousedown" dur="0s" fill="freeze" to="none"/>
						<set attributeName="filter" begin="undo0.mouseup;undo0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
					</rect>
					<g transform="translate(0 -4.5)">
						<polygon points="178.579,57.7353 164.258,51.2544 178.96,44.515 174.48,51.1628"
							 style="fill:rgb(0,0,0);stroke:rgb(0,0,0);stroke-width:2" transform="scale(0.7) translate(-5 0) rotate(320.992 13.3045 25.4374) rotate(2.66158 14.8833 25.1086) translate(-0.0187408 0.792412) translate(-0.846674 1.15063) translate(0.846674 -1.15063) translate(-8.46674 11.5063) translate(8.46674 -11.5063) rotate(1 14.8488 24.959) rotate(1 14.8322 24.9037) rotate(1 14.8138 24.849) rotate(1 14.7934 24.795) rotate(1 14.7712 24.7418) rotate(1 14.7471 24.6893) rotate(-1 14.7212 24.6378) rotate(-1 14.7471 24.6893) rotate(-1 14.7712 24.7418) rotate(-1 14.7934 24.795) rotate(-1 14.8138 24.849) rotate(-1 14.8322 24.9037) rotate(-1 14.8488 24.959) rotate(-1 14.8634 25.0148) rotate(-1 14.876 25.0711) translate(-0.205848 0.251079) rotate(-1 14.8867 25.1278) rotate(-1 14.8954 25.1849) rotate(-1 14.9021 25.2422) rotate(-1 14.9067 25.2997) rotate(-1 14.9094 25.3573) translate(0.318975 -0.00363643) matrix(-1 0 0 1 100 -25) scale(0.5) rotate(180) translate(-345 -152)"/>
						<path d="M137.5 355 C230.674 287.237 311.196 227.137 396.5 349"
							transform="matrix(1 0 0 1 0 0) scale(0.05)"
							 style="fill:none;stroke:rgb(0,0,0);stroke-width:30"/>
					</g>
	        <rect id="undo0" onmouseover="show_tooltip(\''.$strUndo.'\',evt.clientX,evt.clientY)" x="0" y="0" rx="1" ry="1" width="25" height="25" transform="translate(0 0)" style="fill:white;opacity:0.25"/>
	      </g>

	      <g id="new" onmousedown="restartline();highlightbyid(\'line0\');" transform="translate(26 0 )">
	        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
	        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
	        	<set attributeName="filter" begin="new0.mousedown" dur="0s" fill="freeze" to="none"/>
						<set attributeName="filter" begin="new0.mouseup;new0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
					</rect>
					<path
						d="M153.245 92.3923 C153.245 92.3923 198.593 158.983 242.478 202.712 C268.178 171.839
							355.521 99.5384 355.521 99.5384 C355.521 99.5384 359.726 95.6673 364.892 97.8494
							C370.059 100.031 369.612 108.695 369.612 108.695 C369.612 108.695 382.322
							102.528 382.138 102.36 C399.506 105.821 402.66 122.15 402.66 122.15 C402.66
							122.15 322.203 196.362 285.632 240.453 C314.809 273.344 401.918 327.398 408.194
							337.179 C409.657 339.459 396.571 356.768 409.721 364.549 C422.871 372.333
							402.255 381.996 395.718 384.985 C394.3 384.345 384.14 378.487 384.14 378.487
							C382.185 390.247 369.745 392.867 369.745 392.867 C369.745 392.867 279.597 315.208
							246.866 283.273 C218.575 317.649 164.421 399 164.421 399 C142.608 400.165
							135.679 389.138 135.679 389.138 C135.679 389.138 128.054 398.628 119.9 394.204
							C111.747 389.78 112.548 379.796 112.548 379.796 C112.548 379.796 98.637
							382.887 94 373.273 C114.297 334.079 197.862 244.808 197.862 244.808 C160.996
							217.608 96.482 122.942 96.482 122.942 C96.482 122.942 96.6355 104.726 106.055
							98.6784 C104.503 99.1631 119.583 106.53 123.756 104.003 C131.237 99.4715 127.702
							105.026 130.236 95.2056 C132.77 85.3855 153.245 92.3923 153.245 92.3923 z"
						transform="matrix(1 0 0 1 0 0) translate(2 2) scale(0.042)" style="fill:rgb(0,0,0)"/>
	        <rect id="new0" onmouseover="show_tooltip(\''.$strDeleteLine.'\',evt.clientX,evt.clientY)" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
	      </g>

				<g id="line" onmousedown="add_line();highlightbyid(\'line0\');" transform="translate(52 0 )">
		      <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
		      <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
		      	<set attributeName="filter" begin="line0.mousedown" dur="0s" fill="freeze" to="none"/>
						<set attributeName="filter" begin="line0.mouseup;line0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
					</rect>
					<line	x1="81.5" y1="391" x2="127.5" y2="250" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="fill:rgb(144,144,144);stroke:rgb(0,0,0);stroke-width:25"/>
					<line	x1="127.5" y1="250" x2="310.5" y2="243" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="fill:rgb(144,144,144);stroke:rgb(0,0,0);stroke-width:25"/>
					<line	x1="310.5" y1="243" x2="370.5" y2="103" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="fill:rgb(144,144,144);stroke:rgb(0,0,0);stroke-width:25"/>
					<rect id="line0" onmouseover="show_tooltip(\''.$strDrawLine.'\',evt.clientX,evt.clientY)" x="0" y="0" width="25" height="25" style="fill:white;opacity:0.25"/>
				</g>

				<g id="line" onmousedown="delete_lines();highlightbyid(\'del0\');" transform="translate(78 0 )">
		      <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
		      <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
		      	<set attributeName="filter" begin="del0.mousedown" dur="0s" fill="freeze" to="none"/>
						<set attributeName="filter" begin="del0.mouseup;del0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
					</rect>
					<line	x1="81.5" y1="391" x2="127.5" y2="250" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="fill:rgb(144,144,144);stroke:rgb(0,0,0);stroke-width:25"/>
					<line	x1="127.5" y1="250" x2="310.5" y2="243" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="fill:rgb(144,144,144);stroke:rgb(0,0,0);stroke-width:25"/>
					<line	x1="310.5" y1="243" x2="370.5" y2="103" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="fill:rgb(144,144,144);stroke:rgb(0,0,0);stroke-width:25"/>
					<polygon points="
										425.5,261 
										227.5,93 
										145.5,162  
										335.5,350" 
						transform="matrix(1 0 0 1 0 0) scale(0.05)"
						 style="fill:rgb(222,222,222);stroke:rgb(0,0,0);stroke-width:25"/>
					<rect id="del0" onmouseover="show_tooltip(\''.$strDelLine.'\',evt.clientX,evt.clientY)" x="0" y="0" width="25" height="25" style="fill:white;opacity:0.25"/>
				</g>
				
				<g id="query_add" transform="translate(104 0)">
	        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
	        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
	          <set attributeName="filter" begin="ppquery0.mousedown" dur="0s" fill="freeze" to="none"/>
	          <set attributeName="filter" begin="ppquery0.mouseup;ppquery0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
	        </rect>
	        <polygon
						points="252.5,91 177.5,113 106.5,192 128.5,260 116.5,354 127.5,388 173.5,397 282.5,331 394.5,284
							379.5,218 378.5,139 357.5,138 260.5,91"
						transform="matrix(1 0 0 1 0 0) scale(0.05)"
						 style="fill:rgb(144,144,144);stroke:rgb(0,0,0);stroke-width:25"/>
					<polygon points="178.579,57.7353 164.258,51.2544 178.96,44.515 176.48,49.1628 185.48,49.1628 185.48,53.1628 176.48,53.1628"
							 style="fill:rgb(255,255,255);stroke:rgb(0,0,0);stroke-width:1.7" transform="scale(0.7) translate(-46 -154) rotate(60.992 13.3045 25.4374)"/>
	        <rect id="ppquery0" onmouseover="show_tooltip(\'Geometrie hinzufuegen\',evt.clientX,evt.clientY)" onmousedown="add_geometry();hide_tooltip();highlightbyid(\'ppquery0\');" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
	      </g>
	      
	      <g id="query_subtract" transform="translate(130 0)">
	        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
	        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(111,111,111);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
	          <set attributeName="filter" begin="ppquery1.mousedown" dur="0s" fill="freeze" to="none"/>
	          <set attributeName="filter" begin="ppquery1.mouseup;ppquery1.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
	        </rect>
	        <polygon
						points="252.5,91 177.5,113 106.5,192 128.5,260 116.5,354 127.5,388 173.5,397 282.5,331 394.5,284
							379.5,218 378.5,139 357.5,138 260.5,91"
						transform="matrix(1 0 0 1 0 0) scale(0.05)"
						 style="fill:rgb(244,244,244);stroke:rgb(0,0,0);stroke-width:25"/>
					<polygon points="178.579,57.7353 164.258,51.2544 178.96,44.515 176.48,49.1628 185.48,49.1628 185.48,53.1628 176.48,53.1628"
							 style="fill:rgb(255,255,255);stroke:rgb(0,0,0);stroke-width:1.7" transform="scale(0.7) translate(-46 -154) rotate(60.992 13.3045 25.4374)"/>
	        <rect id="ppquery1" onmouseover="show_tooltip(\'Geometrie entfernen\',evt.clientX,evt.clientY)" onmousedown="subtract_geometry();hide_tooltip();highlightbyid(\'ppquery1\');" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
	      </g>

				<g id="line" onmousedown="split_lines();highlightbyid(\'split0\');" transform="translate(156 0 )">
		      <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
		      <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
		      	<set attributeName="filter" begin="del0.mousedown" dur="0s" fill="freeze" to="none"/>
						<set attributeName="filter" begin="del0.mouseup;del0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
					</rect>
						<line	x1="81.5" y1="391" x2="127.5" y2="250" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="fill:rgb(144,144,144);stroke:rgb(0,0,0);stroke-width:25"/>
						<line	x1="127.5" y1="250" x2="310.5" y2="243" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="fill:rgb(144,144,144);stroke:rgb(0,0,0);stroke-width:25"/>
						<line	x1="310.5" y1="243" x2="370.5" y2="103" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="fill:rgb(144,144,144);stroke:rgb(0,0,0);stroke-width:25"/>
						<line x1="300" y1="340" x2="150" y2="160" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="fill:rgb(222,222,222);stroke:rgb(111,111,111);stroke-width:35"/>
					<rect id="split0" onmouseover="show_tooltip(\''.$strSplitLine.'\',evt.clientX,evt.clientY)" x="0" y="0" width="25" height="25" style="fill:white;opacity:0.25"/>
				</g>
		';
		$last_x = 156;
		return $linebuttons;
	}

  function flurstquerybuttons(){
  	global $last_x;
  	$last_x += 26;
    $flurstquerybuttons = '
      <g id="query_add" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="ppquery0.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="ppquery0.mouseup;ppquery0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <polygon
					points="252.5,91 177.5,113 106.5,192 128.5,260 116.5,354 127.5,388 173.5,397 282.5,331 394.5,284
						379.5,218 378.5,139 357.5,138 260.5,91"
					transform="matrix(1 0 0 1 0 0) scale(0.05)"
					 style="fill:rgb(144,144,144);stroke:rgb(0,0,0);stroke-width:25"/>
				<polygon points="178.579,57.7353 164.258,51.2544 178.96,44.515 176.48,49.1628 185.48,49.1628 185.48,53.1628 176.48,53.1628"
						 style="fill:rgb(255,255,255);stroke:rgb(0,0,0);stroke-width:1.7" transform="scale(0.7) translate(-46 -154) rotate(60.992 13.3045 25.4374)"/>
        <rect id="ppquery0" onmouseover="show_tooltip(\'Geometrie hinzufuegen\',evt.clientX,evt.clientY)" onmousedown="add_geometry();hide_tooltip();highlightbyid(\'ppquery0\');" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>';
    $last_x += 26;
    $flurstquerybuttons .= '
			  <g id="query_subtract" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(111,111,111);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="ppquery1.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="ppquery1.mouseup;ppquery1.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <polygon
					points="252.5,91 177.5,113 106.5,192 128.5,260 116.5,354 127.5,388 173.5,397 282.5,331 394.5,284
						379.5,218 378.5,139 357.5,138 260.5,91"
					transform="matrix(1 0 0 1 0 0) scale(0.05)"
					 style="fill:rgb(244,244,244);stroke:rgb(0,0,0);stroke-width:25"/>
				<polygon points="178.579,57.7353 164.258,51.2544 178.96,44.515 176.48,49.1628 185.48,49.1628 185.48,53.1628 176.48,53.1628"
						 style="fill:rgb(255,255,255);stroke:rgb(0,0,0);stroke-width:1.7" transform="scale(0.7) translate(-46 -154) rotate(60.992 13.3045 25.4374)"/>
        <rect id="ppquery1" onmouseover="show_tooltip(\'Geometrie entfernen\',evt.clientX,evt.clientY)" onmousedown="subtract_geometry();hide_tooltip();highlightbyid(\'ppquery1\');" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>';
    return $flurstquerybuttons;
  }
  
  function bufferbuttons($strBuffer, $strBufferedLine, $strParallelPolygon){
  	global $last_x;
  	$last_x += 26;
    $bufferbuttons = '
      <g id="buffer_add" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="buffer0.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="buffer0.mouseup;buffer0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
				<polygon
					points="252.5,91 177.5,113 106.5,192 128.5,260 116.5,354 127.5,388 173.5,397 282.5,331 394.5,284
						379.5,218 378.5,139 357.5,138 260.5,91"
					transform="translate(-3 -3) scale(0.065)"
					 style="fill:rgb(222,222,222);stroke:rgb(0,0,0);stroke-width:15"/>
        <polygon
					points="252.5,91 177.5,113 106.5,192 128.5,260 116.5,354 127.5,388 173.5,397 282.5,331 394.5,284
						379.5,218 378.5,139 357.5,138 260.5,91"
					transform="translate(3 3) scale(0.04)"
					 style="fill:rgb(144,144,144);stroke:rgb(0,0,0);stroke-width:25"/>
        <rect id="buffer0" onmouseover="show_tooltip(\''.$strBuffer.'\',evt.clientX,evt.clientY)" onmousedown="add_buffer();hide_tooltip();highlightbyid(\'buffer0\');" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>';
		$last_x += 26;
		$bufferbuttons .= '
      <g id="buffer_add_line" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="buffer1.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="buffer1.mouseup;buffer1.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <polygon
					points="221 339 212 344 204 351 197 359 192 368 189 378 188 389 189 399 192 410 197 419 322 607 329 615 337 622 346 627 356 630 366 631 377 630 387 627 396 622 404 616 499 525 506 517 511 508 515 498 516 487 515 477 512 466 507 457 501 449 493 442 484 437 474 433 463 432 453 433 442 436 433 441 425 447 376 493 323 414 524 331 533 326 541 319 548 311 553 302 556 292 557 281 556 270 553 260 548 251 541 243 533 236 524 231 514 228 503 227 492 228 482 231 221 339"
					transform="translate(2.5 -17) scale(0.050) rotate(88 197 419)"
					 style="fill:rgb(164,164,164);stroke:rgb(0,0,0);stroke-width:21"/>
				<polyline points="503 281 242 389 367 577 462 486" transform="translate(2.5 -17) scale(0.050) rotate(88 197 419)" style="fill:none;stroke-dasharray:2,2;stroke:black;stroke-width:15"/>
        <rect id="buffer1" onmouseover="show_tooltip(\''.$strBufferedLine.'\',evt.clientX,evt.clientY)" onmousedown="add_buffered_line();hide_tooltip();highlightbyid(\'buffer1\');" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>';
			$last_x += 26;
		$bufferbuttons .= '
      <g id="parallel_polygon" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="buffer2.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="buffer2.mouseup;buffer2.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
				<polyline points="476 285 677 517" transform="translate(0 -41) scale(0.070) rotate(94 197 419)" style="fill:none;stroke:#4A4A4A;stroke-width:20"/>
        <polygon
					points="574 546 647 483 506 320 433 383 574 546"
					transform="translate(0 -41) scale(0.070) rotate(94 197 419)"
					 style="fill:rgb(164,164,164);stroke:rgb(0,0,0);stroke-width:20"/>
        <rect id="buffer2" onmouseover="show_tooltip(\''.$strParallelPolygon.'\',evt.clientX,evt.clientY)" onmousedown="add_parallel_polygon();hide_tooltip();highlightbyid(\'buffer2\');" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>';
    return $bufferbuttons;
  }

	function transform_buttons($strMoveGeometry){
		global $last_x;
		$last_x += 26;
		$transform_buttons ='
			<g id="vertex_edit" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="move1.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="move1.mouseup;move1.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
				<polygon points="252.5,91 177.5,113 106.5,192 128.5,260 116.5,354 127.5,388 173.5,397 282.5,331 394.5,284
						379.5,218 378.5,139 357.5,138 260.5,91"
					transform="translate(4 4) scale(0.045)"
					 style="fill:none;stroke:rgb(0,0,0);stroke-dasharray:23,23;stroke-width:25"/>
				<polygon points="252.5,91 177.5,113 106.5,192 128.5,260 116.5,354 127.5,388 173.5,397 282.5,331 394.5,284
						379.5,218 378.5,139 357.5,138 260.5,91"
					transform="translate(-2 0) scale(0.045)"
					 style="fill:rgb(144,144,144);stroke:rgb(0,0,0);stroke-width:25"/>
        <rect id="move1" onmouseover="show_tooltip(\''.$strMoveGeometry.'\',evt.clientX,evt.clientY)" onmousedown="highlightbyid(\'move1\');move_geometry();hide_tooltip();" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>
    ';
    return $transform_buttons;
	}
	
	function vertex_edit_buttons($strCornerPoint){
		global $last_x;
		$last_x += 26;
		$vertex_edit_buttons ='
			<g id="vertex_edit" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="vertex_edit1.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="vertex_edit1.mouseup;vertex_edit1.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <circle cx="178.579" cy="57.7353" r="3" transform="translate(-167 -47)"/>
				<polygon points="178.579,57.7353 164.258,51.2544 178.96,44.515 176.48,49.1628 185.48,49.1628 185.48,53.1628 176.48,53.1628"
						 style="fill:rgb(255,255,255);stroke:rgb(0,0,0);stroke-width:1.7" transform="scale(0.7) translate(-46 -154) rotate(60.992 13.3045 25.4374)"/>
        <rect id="vertex_edit1" onmouseover="show_tooltip(\''.$strCornerPoint.'\',evt.clientX,evt.clientY)" onmousedown="highlightbyid(\'vertex_edit1\');edit_vertices();hide_tooltip();" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>
    ';
    return $vertex_edit_buttons;
	}
	
	function coord_input_buttons(){
		global $last_x;
		$last_x += 26;
		$vertex_edit_buttons ='
			<g id="vertex_edit" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <!--set attributeName="filter" begin="coord_input1.mousedown" dur="0s" fill="freeze" to="none"/-->
          <!--set attributeName="filter" begin="coord_input1.mouseup;coord_input1.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/-->
        </rect>
        <circle cx="178.579" cy="57.7353" r="3" transform="translate(-166 -41)"/>
				<text transform="scale(0.7 0.7)" x="18" y="14" style="text-anchor:middle;fill:rgb(0,0,0);font-size:15;font-family:Arial;font-weight:bold">x,y</text>
        <rect id="coord_input1" onmouseover="show_tooltip(\'Koordinate eingeben\',evt.clientX,evt.clientY)" onmousedown="coord_input();hide_tooltip();" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>
    ';
    return $vertex_edit_buttons;
	}
	
	function measure_buttons($strRuler){
		global $last_x;
		$last_x += 26;
		$measure_buttons ='
			<g id="dist" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="measure0.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="measure0.mouseup;measure0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <g transform="scale(0.8) rotate(-30) translate(-20 -5)">
          <line x1="13" y1="28" x2="37" y2="28" style="fill:none;stroke:black;stroke-width:3"/>
          <line x1="13" y1="26" x2="33" y2="26" style="stroke-dasharray:1,5;fill:none;stroke:black;stroke-width:7"/>
          <line x1="13" y1="26" x2="35" y2="26" style="stroke-dasharray:1,2.0;fill:none;stroke:black;stroke-width:3"/>
        </g>
        <rect id="measure0" onmouseover="show_tooltip(\''.$strRuler.'\',evt.clientX,evt.clientY)" onmousedown="measure();highlightbyid(\'measure0\')" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;fill-opacity:0.25"/>
      </g>
		';
		return $measure_buttons;
	}
?>
