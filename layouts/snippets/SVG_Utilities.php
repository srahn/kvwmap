<?php
 # 2008-01-24 pkvvm
  include(LAYOUTPATH.'languages/SVG_Utilities_'.rolle::$language.'.php');

	$map_width = $this->user->rolle->nImageWidth;
	$map_height = $this->user->rolle->nImageHeight;
 ?>

<script type="text/javascript" src="funktionen/helmert_trafo.js"></script>

<script language="JavaScript">

	var enclosingForm = <? echo $this->currentform; ?>;
	
	function saveDrawmode(){
		ahah("index.php", 'go=saveDrawmode&always_draw=' + +document.GUI.always_draw.checked, new Array(""), new Array(""));
	}

	function geom_from_layer_change(selected_layer_id){
		// selected_layer_id ist die Layer-ID des Layers, der gerade im Geometrie-Editor bearbeitet wird
		// wenn sie gesetzt ist, wird der Geometrieübernahmelayer für diesen Layer gespeichert
		var data;
		if (typeof selected_layer_id  !== 'undefined') {
			data = 'go=saveGeomFromLayer&selected_layer_id=' + selected_layer_id + '&geom_from_layer=' + enclosingForm.geom_from_layer.value;
			ahah("index.php", data, new Array(""), new Array(""));
		}
		if (enclosingForm.geom_from_layer.value < 0 && enclosingForm.newpath.value == '') {
			// Rollenlayer sofort selektieren, wenn noch keine Geometrie vorhanden
			var last_doing = enclosingForm.last_doing.value;
			enclosingForm.last_doing.value = 'add_geom';
			if (enclosingForm.firstpoly) {
				enclosingForm.firstpoly.value = 'true';
			}
			enclosingForm.secondpoly.value = 'true';
			data = 'go=spatial_processing&operation=add_geometry&code2execute=enclosingForm.last_doing.value=\'' + last_doing + '\';&resulttype=svgwkt&singlegeom=false&geom_from_layer=' + enclosingForm.geom_from_layer.value;
			ahah("index.php", data, new Array(enclosingForm.result, "", ""), new Array("setvalue", "execute_function", "execute_function"));
		}
	}

	function show_vertices(){
		SVG.show_vertices();
	}

	function update_geometry(){
		SVG.update_geometry();
	}
		
	function coord_input_submit(){
		SVG.coord_input_submit();
	}
	
	function add_buffer_submit(){
		SVG.add_buffer_submit();
	}	
	
	function add_parallel_polygon_submit(){
		SVG.add_parallel_polygon_submit();
	}	

	function add_parallel_line_submit(){
		SVG.add_parallel_line_submit();
	}		
	
	function add_ortho_point(world_x, world_y, local_x, local_y, deactivate){
		SVG.add_ortho_point(world_x, world_y, local_x, local_y, deactivate);
	}
	
	function remove_ortho_points(){
		SVG.remove_ortho_points();
	}
	
	function moveback(){	
		SVG.moveback();
	}
	
	function startup(){
		SVG.startup();
	}
	
	function showtooltip(result, showdata){
		SVG.showtooltip(result, showdata);
	}	

	function change_box_width_height(){
		SVG.change_box_width_height();
	}	

	var nbh = new Array();
		
</script>
 
<?php

	global $last_x;$last_x = 0;
	global $events;$events = true;

	include(LAYOUTPATH.'snippets/SVGvars_navbuttons.php'); 		# zuweisen von: $SVGvars_navbuttons	
	include(LAYOUTPATH.'snippets/SVGvars_coordscript.php'); 	# zuweisen von: $SVGvars_coordscript
	include(LAYOUTPATH.'snippets/SVGvars_querytooltipscript.php');   # zuweisen von: $SVGvars_tooltipscript
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
	if($this->formvars['loc_y'] == '') {
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
	<svg width="'.$res_x.'" height="'.$res_y.'" zoomAndPan="disable" onload="init()" onmousemove="top.coords_anzeige(evt)"
	  xmlns="http://www.w3.org/2000/svg" version="1.1"
	  xmlns:xlink="http://www.w3.org/1999/xlink">
	';

	$SVG_end = '
		'.$SVGvars_tooltipblank.'
			</g>
			<g id="tooltipgroup" onmouseover="prevent=1;" onmouseout="prevent=0;">
				<rect id="frame" width="0" height="20" rx="5" ry="5" style="fill-opacity:0.8;fill:rgb(255, 250, 240);stroke:rgb(140, 140, 140);stroke-width:1.5"/>
				<text id="querytooltip" x="100" y="100" style="text-anchor:start;fill:rgb(0,0,0);stroke:none;font-size:10px;font-family:Arial;font-weight:bold"></text>
				<text id="link0" cursor="pointer" onmousedown="top.document.body.style.cursor=\'pointer\';" onmousemove="top.document.body.style.cursor=\'pointer\';" style="text-anchor:start;fill:rgb(0,0,200);stroke:none;font-size:10px;font-family:Arial;font-weight:bold"></text>
				<g id="tooltipcontent">
				</g>	
			</g>
	</svg>';

	$ortho_points_x = implode(',', $this->formvars['ortho_point_x'] ?: []);
	$ortho_points_y = implode(',', $this->formvars['ortho_point_y'] ?: []);
	$rolle_gps = ($this->user->rolle->gps ? 1 : 0);
	$scriptdefinitions = <<<SCRIPTDEFINITIONS
	let enclosingForm = top.currentform;
	let transformfunctions = false;
	let coord_input_functions = false;
	let ortho_point_functions = false;
	let bufferfunctions = false;
	let special_bufferfunctions = false;
	let polygonfunctions = false;
	let polygonfunctions2 = false;
	let flurstuecksqueryfunctions = false;
	let boxfunctions = false;
	let pointfunctions = false;
	let multipointfunctions = false;
	let linefunctions = false;
	let measurefunctions = false;
	let path  = "";
	let path_second  = "";
	let pathx_second = new Array();
	let pathy_second = new Array();
	let poly_pathx_second = new Array();
	let poly_pathy_second = new Array();
	let pathx = new Array();
	let pathy = new Array();
	let m_pathx = new Array();
	let m_pathy = new Array();
	if(enclosingForm.newpath.value){
		pathx = getxcoordsfromsvgpath(enclosingForm.newpath.value);
		pathy = getycoordsfromsvgpath(enclosingForm.newpath.value);
	}
	if(enclosingForm.pathy_second.value != ""){
		str = enclosingForm.pathx_second.value;
		pathx_second = str.split(";");
		str = enclosingForm.pathy_second.value;
		pathy_second = str.split(";");
	}
	if(enclosingForm.ortho_point_vertices != undefined){
		var ortho_point_vertices = new Array();
		var o_p_local_x = [{$ortho_points_x}];
		var o_p_local_y = [{$ortho_points_y}];
	}
	let helmert;
	let textx = {$text_x};
	let texty = {$text_y};
	let newpath_undo = new Array();
	let newpathwkt_undo = new Array();

	let minx  = {$this->user->rolle->oGeorefExt->minx};
	let maxx  = {$this->user->rolle->oGeorefExt->maxx};
	let miny  = {$this->user->rolle->oGeorefExt->miny};
	let maxy  = {$this->user->rolle->oGeorefExt->maxy};
	let resx  = {$res_x};
	let resy  = {$res_y};
	let resx_m  = {$res_xm};
	let resy_m  = {$res_ym};
	let scale = {$pixelsize};
	let boxx 	= new Array();
	let boxy 	= new Array();
	let move_x 	= new Array();
	let move_y 	= new Array();
	let move_dx;
	let move_dy;
	let dragging  = false;
	let dragdone  = false;
	let draggingFS  = false;
	let moving  = false;
	let movinggeom  = false;
	let rotatinggeom = false;
	let rotated = false;
	let moved  = false;
	let must_redraw = false;
	let mobile = {$rolle_gps};
	let gps_follow_cooldown = 0;
	let selected_vertex;
	let last_selected_vertex;
	let vertex_old_world_x = "";
	let vertex_old_world_y = "";
	let vertex_new_world_x;
	let vertex_new_world_y;
	let vertex_moved = false;
	let mouse_down = false;
	let time_mouse_down;
	let mouse_coords_type = "image";
	let measuring  = false;
	let deactivated_foreign_vertex = 0;
	let geomload = {$geomload};		// Geometrie wird das erste Mal geladen, diese Variable verhindert den Weiterzeichnenmodus
	let root = document.documentElement;
	let mousewheelloop = 0;
	let measured_distance = 0;
	let new_distance = 0;
	let doing;
	let doing_save;
	let suppresszoom = false;
	let centroid = new Array();
	let angle;
	let startangle;
SCRIPTDEFINITIONS;

	$polygonANDpoint = '
	var polygonXORpoint = false;
	';

	$polygonXORpoint = '
	var	polygonXORpoint = true;
	';

	$SVGvars_navscript = '
	
	top.document.getElementById("svghelp").SVGmoveback = moveback;
	
	top.document.getElementById("svghelp").SVGstartup = startup;
		
	function moveback(evt){
		// beim Firefox wird diese Funktion beim onload des Kartenbildes ausgefuehrt
		document.getElementById("mapimg2").setAttribute("style", "display:block");	
		window.setTimeout(\'document.getElementById("moveGroup").setAttribute("transform", "translate(0 0)");document.getElementById("mapimg").setAttribute("href", document.getElementById("mapimg2").getAttribute("href"));startup();\', 200);
		// Tooltip refreshen
		oldmousex = undefined;
		// Navigation wieder erlauben
		top.stopwaiting();
		window.setTimeout(\'document.getElementById("mapimg2").setAttribute("href", "")\', 400);
		window.setTimeout(\'document.getElementById("mapimg2").setAttribute("style", "display:none")\', 400);	
	}

	function get_map_ajax(postdata){
		geomload = false;
		if(polygonfunctions == true || linefunctions == true){
			remove_vertices();
			remove_in_between_vertices();
		}
		top.get_map_ajax(postdata, \'\', \'if(document.GUI.oldscale != undefined){document.GUI.oldscale.value=document.GUI.nScale.value;}\');
	}
	
	function submit(){
		top.startwaiting();
		top.overlay_submit(enclosingForm, false);
	}
		
	function go_previous(){
	  document.getElementById("canvas").setAttribute("cursor", "wait");
	  enclosingForm.CMD.value  = "previous";
    get_map_ajax(\'go=navMap_ajax\');
	}
	
	function go_next(){
	  document.getElementById("canvas").setAttribute("cursor", "wait");
	  enclosingForm.CMD.value  = "next";
	  get_map_ajax(\'go=navMap_ajax\');
	}	

	function zoomin(){
		enclosingForm.last_doing2.value = enclosingForm.last_doing.value;
		enclosingForm.last_doing.value = "zoomin"; 
	  document.getElementById("canvas").setAttribute("cursor", "crosshair");
		if(measurefunctions == true){
			save_measure_path();
		}
	}

	function zoomout(){
		enclosingForm.last_doing2.value = enclosingForm.last_doing.value;
		enclosingForm.last_doing.value = "zoomout";
	  document.getElementById("canvas").setAttribute("cursor", "crosshair");
		if(measurefunctions == true){
			save_measure_path();
		}
	}

	function zoomall(){
	  document.getElementById("canvas").setAttribute("cursor", "wait");
	  Full_Extent();
	}

	function recentre(){
		enclosingForm.last_doing2.value = enclosingForm.last_doing.value;
		enclosingForm.last_doing.value = "recentre";
		document.getElementById("canvas").setAttribute("cursor", "move");
	  document.getElementById("canvas").setAttribute("cursor", "grab");
		if(measurefunctions == true){
			save_measure_path();
		}
	}
	
	function ppquery(){
		enclosingForm.last_doing2.value = enclosingForm.last_doing.value;
		enclosingForm.last_doing.value = "ppquery";
		document.getElementById("canvas").setAttribute("cursor", "pointer");
	}
	
	function edit_other_object(){
		enclosingForm.last_doing2.value = enclosingForm.last_doing.value;
		enclosingForm.last_doing.value = "edit_other_object";
		doing = "edit_other_object";
		document.getElementById("canvas").setAttribute("cursor", "pointer");
	}
	
	function noMeasuring(){
		measuring = false;
	}	

	function measure(){
	  enclosingForm.last_doing.value = "measure";
		// Wenn im UTM-System gemessen wird, NBH-Datei laden
		if('.$this->user->rolle->epsg_code.' == '.EPSGCODE_ALKIS.')top.ahah("index.php", "go=getNBH", new Array(""), new Array("execute_function"));
		if(enclosingForm.str_pathx.value != ""){
			measuring = true;	
			enclosingForm.str_pathx.value = "";
			enclosingForm.str_pathy.value = "";
		}
		else{
			measured_distance = 0;
	  	measuring = false;
	  	restart_m();
		}
	  document.getElementById("canvas").setAttribute("cursor", "crosshair");
	}

	// ----------------------------punkt setzen---------------------------------
	function selectPoint(clientx, clienty) {
	  cmd = enclosingForm.last_doing.value;
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
	  cmd = enclosingForm.last_doing.value;
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
	function startMove(clientx, clienty){
		document.getElementById("canvas").setAttribute("cursor", "move");
		document.getElementById("canvas").setAttribute("cursor", "grabbing");
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

	function endMove(cmd) {
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

	$basicfunctions = "
	function world2pixelsvg(pathWelt){
		var path  = new Array();
		pathWelt = pathWelt.replace(/L /g, '');		// neuere Postgis-Versionen haben ein L mit drin
		explosion = pathWelt.split(' ');
		for(i = 0; i < explosion.length; i++){
			if(explosion[i] == 'M'){
				path.push('M');
				laststartx = Math.round((explosion[i+1] - minx)/scale);
				laststarty = Math.round((Math.abs(explosion[i+2]) - top.document.GUI.miny.value)/scale);
			}
			if(explosion[i] != 'M' && explosion[i] != 'Z' && explosion[i] != ''){
				path.push(Math.round((explosion[i] - minx)/scale));
				path.push(Math.round((Math.abs(explosion[i+1]) - miny)/scale));
				i++;
			}
			if(explosion[i] == 'Z'){			// neuere Postgis-Versionen liefern bei asSVG ein Z zum Schliessen des Rings anstatt der Startkoordinate
				path.push(laststartx);
				path.push(laststarty);
			}
		}
		pixelpath = path.join(' ');
		return pixelpath;
	}

	function redrawpoint(){
		if(document.getElementById('pointposition')){
			var obj = document.getElementById('pointposition');
			pixel_coordx = (textx - minx) / scale;
			pixel_coordy = (texty - miny) / scale;
			if(pixel_coordy < 0){				// im Firefox fuehrten grosse negative Zahlen zum Absturz
				pixel_coordy = -1000;
				pixel_coordx = -1000;
			}
		  obj.setAttribute('x', pixel_coordx);
		  obj.setAttribute('y', pixel_coordy);
			if(pointfunctions == true && enclosingForm.angle != undefined)rotate_point_direction();
		}
	}

	function sendBWlocation(loc_x,loc_y) {
      enclosingForm.loc_x.value    = loc_x;
      enclosingForm.loc_y.value    = loc_y;
  }

  function sendBWpath(pathx,pathy) {
      enclosingForm.pathlength.value   = pathx.length;
      enclosingForm.pathx.value    = pathx;
      enclosingForm.pathy.value    = pathy;
  }

  function Full_Extent(){
    enclosingForm.CMD.value = 'Full_Extent';
    get_map_ajax('go=navMap_ajax');
  }

	function checkQueryFields(){
		" . ($this->user->rolle->singlequery == 2 ? 'return true;' : '') . "
		var selected = false;
		query_fields = top.document.getElementsByClassName('info-select-field');
		for(var i = 0; i < query_fields.length; i++){
			if(query_fields[i].checked){
				selected = true;
				break;
			}
		}
		if(selected == false)top.message([{ 'type': 'warning', 'msg': '{$strNoLayer}' }]);
		return selected;
	}	
	
  function sendpath(cmd,navX,navY){
    // navX[0] enthaelt den Rechtswert des ersten gesetzte Punktes im Bild in Pixeln
    // von links nach rechts gerechnet
    // navY[0] enthaelt den Hochwert des ersten Punktes im Bild in Pixeln
    // allerdings von oben nach untern gerechnet
    // [2] jeweils den anderen Punkt wenn ein Rechteck uebergeben wurde
		enclosingForm.action = 'index.php';
    switch(cmd) {
     case 'zoomin_point':
      enclosingForm.INPUT_COORD.value  = navX[0]+','+navY[0];
      enclosingForm.CMD.value          = 'zoomin';
      get_map_ajax('go=navMap_ajax');
     break;
     case 'zoomout':
      enclosingForm.INPUT_COORD.value  = navX[0]+','+navY[0];
      enclosingForm.CMD.value          = cmd;
      get_map_ajax('go=navMap_ajax');
     break;
     case 'zoomin_box':
      enclosingForm.INPUT_COORD.value  = navX[0]+','+navY[0]+';'+navX[2]+','+navY[2];
      enclosingForm.CMD.value          = 'zoomin';
      get_map_ajax('go=navMap_ajax');
     break;
     case 'recentre':
      enclosingForm.INPUT_COORD.value  = navX[0]+','+navY[0];
      enclosingForm.CMD.value = cmd;
			get_map_ajax('go=navMap_ajax');
     break;
     case 'ppquery_point':
			if(!checkQueryFields())break;
      path = navX[0]+','+navY[0]+';'+navX[0]+','+navY[0];
      enclosingForm.INPUT_COORD.value  = path;
      enclosingForm.CMD.value          = 'ppquery';
			go_backup = enclosingForm.go.value;
			enclosingForm.go.value = 'Sachdaten';
			top.overlay_submit(enclosingForm, true);
			enclosingForm.go.value = go_backup;
     break;
     case 'ppquery_box':
			if(!checkQueryFields())break;
      path = navX[0]+','+navY[0]+';'+navX[2]+','+navY[2];
      enclosingForm.INPUT_COORD.value  = path;
      enclosingForm.CMD.value          = 'ppquery';
			go_backup = enclosingForm.go.value;
			enclosingForm.go.value = 'Sachdaten';
      top.overlay_submit(enclosingForm, true);
			enclosingForm.go.value = go_backup;
     break;
		 case 'edit_other_object_point':
			if(!checkQueryFields())break;
      path = navX[0]+','+navY[0]+';'+navX[0]+','+navY[0];
      enclosingForm.INPUT_COORD.value  = path;
      enclosingForm.CMD.value          = 'ppquery';
			enclosingForm.go.value = 'Sachdaten';
			enclosingForm.last_doing.value = '';
			enclosingForm.geom_from_layer.value = '';
			enclosingForm.edit_other_object.value = 1;
			enclosingForm.submit();
     break;
     case 'edit_other_object_box':
			if(!checkQueryFields())break;
      path = navX[0]+','+navY[0]+';'+navX[2]+','+navY[2];
      enclosingForm.INPUT_COORD.value  = path;
      enclosingForm.CMD.value          = 'ppquery';
			enclosingForm.go.value = 'Sachdaten';
			enclosingForm.last_doing.value = '';
			enclosingForm.geom_from_layer.value = '';
			enclosingForm.edit_other_object.value = 1;
      enclosingForm.submit();
     break;
     case 'add_geom_box':
      enclosingForm.INPUT_COORD.value  = navX[0]+','+navY[0]+';'+navX[2]+','+navY[2];
      enclosingForm.CMD.value = cmd;
     break;
		 case 'subtract_geom_box':
      enclosingForm.INPUT_COORD.value  = navX[0]+','+navY[0]+';'+navX[2]+','+navY[2];
      enclosingForm.CMD.value = cmd;
     break;
     case 'add_circle_box':
      enclosingForm.INPUT_COORD.value  = navX[0]+','+navY[0]+';'+navX[2]+','+navY[2];
      enclosingForm.CMD.value = cmd;
     break;
     case 'add_circle_point':
      enclosingForm.CMD.value = cmd;
     break;		  
     case 'add_geom_point':
      enclosingForm.INPUT_COORD.value  = navX[0]+','+navY[0]+';'+navX[0]+','+navY[0];
      enclosingForm.CMD.value = cmd;
     break;
		 case 'subtract_geom_point':
      enclosingForm.INPUT_COORD.value  = navX[0]+','+navY[0]+';'+navX[0]+','+navY[0];
      enclosingForm.CMD.value = cmd;
     break;
     default:
      alert('Keine Bearbeitung moeglich! Übergebene Daten: '+cmd+', '+navX[0]+','+navY[0]);
     break;
    }
  }

  function updatepaths(){
  	if(enclosingForm.result.value != '' && enclosingForm.result.value != ' '){
	  	result = ''+enclosingForm.result.value;
	  	paths = result.split('||');
	  	if(paths[1] == 'GEOMETRYCOLLECTION EMPTY' || paths[1] == ''){
  			paths[0] = '';
  			paths[1] = '';
				if(polygonfunctions == true){
  				enclosingForm.firstpoly.value = false;
  				enclosingForm.secondpoly.value = false;
  				restart();
				}
				if(linefunctions == true){
  				enclosingForm.firstline.value = false;
  				enclosingForm.secondline.value = false;
  				restart();
				}
	  	}
	  	enclosingForm.newpath.value = paths[0];
	  	enclosingForm.newpathwkt.value = paths[1];
	  	enclosingForm.result.value = '';
			must_redraw = true;
	  	if(polygonfunctions == true){
	  		polygonarea();				
	  	}
			if(linefunctions == true){
				linelength();
	  	}
			if(enclosingForm.last_doing.value == 'split_geometry'){
				split_geometry();
			}
			if(enclosingForm.split != undefined){
				if(paths[1].search(/MULTI.+/) != -1){
					enclosingForm.split.style.visibility = 'visible';
				}
				else{
					enclosingForm.split.style.visibility = 'hidden';
				}
			}
  	}
 	}

 	function mousewheelzoom() {
		cleartooltip();
		enclosingForm.last_doing2.value = enclosingForm.last_doing.value;
		var g = document.getElementById('moveGroup');
		zx = g.getCTM().inverse();
		navX = new Array();
		navY = new Array();
		navX[0] = Math.round(zx.e);
		navY[0] = Math.round(zx.f);
		navX[2] = Math.round(zx.e + resx*zx.a); 
		navY[2] = Math.round(zx.f + resy*zx.a);
		if (enclosingForm.last_doing.value != 'vertex_edit' && enclosingForm.always_draw != undefined){
			enclosingForm.always_draw.checked = true;
		}
		sendpath('zoomin_box', navX, navY);
	}
	
	function suppressZoom(evt){
		if (evt.keyCode == 17) {
			suppresszoom = true;
		}
	}
	
	function unSuppressZoom(evt){
		if (evt.keyCode == 17) {
			suppresszoom = false;
			document.getElementById('moveGroup').setAttribute('transform', 'translate(0 0)');
			resizeElementsForSuppressedZoom(1);
		}
	}
	
	function resizeElementsForSuppressedZoom(z){
		if (z == 1) {
			for (circle of document.getElementById('foreignvertices').childNodes) {
				circle.setAttribute('r', 7);
			}
			document.getElementById('polygon_first').style.strokeWidth = '1.5px';
		}
		else {
			for (circle of document.getElementById('foreignvertices').childNodes) {
				circle.setAttribute('r', circle.getAttribute('r') / z);
			}
			document.getElementById('polygon_first').style.strokeWidth = (parseFloat(document.getElementById('polygon_first').style.strokeWidth) / z) + 'px';
		}
	}
	
	function mousewheelchange(evt) {
		if(measurefunctions == true){
			save_measure_path();
		}
		if (!evt) {
			evt = window.event; // For IE
		}
		if (top.document.GUI.stopnavigation.value == 0) {
			window.clearTimeout(mousewheelloop);
			if (evt.preventDefault) {
				evt.preventDefault();
			}
			else {
				evt.returnValue = false; // IE fix
			}
			if (evt.wheelDelta) {
				delta = evt.wheelDelta / 3600; // Chrome/Safari
			}
			else {
				delta = evt.detail / -90; // Mozilla
			}
			let z = 1 + delta * 5;
			let g = document.getElementById('moveGroup');
			let p = getEventPoint(evt);
			if (p.x > 0 && p.y > 0) {
				p = p.matrixTransform(g.getCTM().inverse());
				let k = root.createSVGMatrix().translate(p.x, p.y).scale(z).translate(-p.x, -p.y);
				setCTM(g, g.getCTM().multiply(k));
				if (!suppresszoom) {
					mousewheelloop = window.setTimeout('mousewheelzoom()', 400);
				}
				else {
					resizeElementsForSuppressedZoom(z);
				}
			}
		}
	}
	
	function setCTM(element, matrix) {
		var s = 'matrix(' + matrix.a + ',' + matrix.b + ',' + matrix.c + ',' + matrix.d + ',' + matrix.e + ',' + matrix.f + ')';
		element.setAttribute('transform', s);
	}
	
	function getEventPoint(evt) {
		var p = root.createSVGPoint();
		p.x = evt.clientX;
		p.y = evt.clientY;
		if(top.navigator.userAgent.toLowerCase().indexOf('msie') >= 0){
			p.x = p.x - (top.document.body.clientWidth - resx)/2;
	    		p.y = p.y - (top.document.body.clientHeight - resy)/2;
		}
		return p;
	}
 	
	function init(){
		// Bug Workaround fuer Firefox
		var nav_button_bgs = document.querySelectorAll('.navbutton_bg');
		[].forEach.call(nav_button_bgs, function (nav_button_bg){
			nav_button_bg.setAttribute('width', parseInt(nav_button_bg.getAttribute('width')) + 0.01);
		});
		startup();
		if(window.addEventListener){
			if(top.browser != 'other'){
				document.getElementById('mapimg2').addEventListener('load', function(evt) { moveback(evt); }, true);
			}
			window.addEventListener('mousewheel', mousewheelchange, {passive: false}); // Chrome/Safari//IE9
			window.addEventListener('DOMMouseScroll', mousewheelchange, {passive: false});		//Firefox
			window.addEventListener('keydown', suppressZoom, {passive: false});
			window.addEventListener('keyup', unSuppressZoom, {passive: false});
		}
		else {
			top.document.getElementById('map').onmousewheel = mousewheelchange;		// <=IE8
		}
	}
	
	function startup(){
		minx = parseFloat(enclosingForm.minx.value);
		miny = parseFloat(enclosingForm.miny.value);
		maxx = parseFloat(enclosingForm.maxx.value);
		maxy = parseFloat(enclosingForm.maxy.value);
		scale = parseFloat(enclosingForm.pixelsize.value);
		if(measurefunctions == true){
			get_measure_path();
			redrawPL();
		}
		if(mobile == true){
			update_gps_position();
		}
		if(polygonfunctions == true){
			if(enclosingForm.always_draw.checked && !geomload){		// 'weiterzeichnen'
				if(enclosingForm.last_doing2.value == 'draw_polygon' || enclosingForm.last_doing2.value == 'draw_second_polygon')enclosingForm.last_button.value = 'pgon0';
				if(enclosingForm.last_doing2.value != '')enclosingForm.last_doing.value = enclosingForm.last_doing2.value;
				if(enclosingForm.last_doing2.value == 'add_geom')enclosingForm.last_button.value = 'ppquery1';
				if(enclosingForm.last_doing2.value == 'subtract_geom')enclosingForm.last_button.value = 'ppquery2';
				if(enclosingForm.secondpoly.value == 'started' || enclosingForm.secondpoly.value == 'true'){	// am zweiten Polygon oder an einer gepufferten Linie wird weitergezeichnet
					if(enclosingForm.last_doing2.value == 'add_buffered_line')enclosingForm.last_button.value = 'buffer1';
					if(enclosingForm.last_doing2.value == 'add_circle')enclosingForm.last_button.value = 'buffer3';
					if(enclosingForm.last_doing2.value == 'add_parallel_polygon')enclosingForm.last_button.value = 'buffer2';
					if(enclosingForm.last_doing2.value == 'subtract_polygon')enclosingForm.last_button.value = 'pgon_subtr0';
					if(pathx_second.length == 1){				// ersten Punkt darstellen
						document.getElementById('startvertex').setAttribute('cx', (pathx_second[0]-minx)/scale);
						document.getElementById('startvertex').setAttribute('cy', (pathy_second[0]-miny)/scale);
					}
				}
				else{																											// am ersten Polygon wird weitergezeichnet
					if(pathx.length == 1){							// ersten Punkt darstellen
						document.getElementById('startvertex').setAttribute('cx', (pathx[0]-minx)/scale);
						document.getElementById('startvertex').setAttribute('cy', (pathy[0]-miny)/scale);
					}
				}
			}
			else{			// bei nicht 'weiterzeichnen' wird alles vom zweiten Polygon geloescht
				var alles = pathx_second.length;
				for(var i = 0; i < alles; ++i){
					pathx_second.pop();
					pathy_second.pop();
				}
				enclosingForm.pathx_second.value = '';
				enclosingForm.pathy_second.value = '';
				if(enclosingForm.firstpoly.value == 'true' && enclosingForm.last_doing.value == 'draw_polygon'){
					enclosingForm.last_doing.value = 'draw_second_polygon';
				}
			}
		}
		if(linefunctions == true){
			if(enclosingForm.always_draw.checked && !geomload){
				if(enclosingForm.last_doing2.value != '')enclosingForm.last_doing.value = enclosingForm.last_doing2.value;
				if(enclosingForm.secondline.value == 'true'){
					if(enclosingForm.last_doing2.value == 'add_parallel_line')enclosingForm.last_button.value = 'parallel_line0';
					if(pathx_second.length == 1){				// ersten Punkt darstellen
						document.getElementById('startvertex').setAttribute('cx', (pathx_second[0]-minx)/scale);
						document.getElementById('startvertex').setAttribute('cy', (pathy_second[0]-miny)/scale);
					}
				}
				else{
					enclosingForm.last_doing.value = 'draw_line';
					if(pathx.length == 1){							// ersten Punkt darstellen
						document.getElementById('startvertex').setAttribute('cx', (pathx[0]-minx)/scale);
						document.getElementById('startvertex').setAttribute('cy', (pathy[0]-miny)/scale);
					}
				}
			}
			else{
				var alles = pathx_second.length;
				for(var i = 0; i < alles; ++i){
					pathx_second.pop();
					pathy_second.pop();
				}
				enclosingForm.pathx_second.value = '';
				enclosingForm.pathy_second.value = '';
				if(enclosingForm.firstline.value == 'true' && enclosingForm.last_doing.value == 'draw_line'){
					enclosingForm.last_doing.value = 'draw_second_line';
				}
			}
		}
		if(enclosingForm.punktfang != undefined && enclosingForm.punktfang.checked)toggle_vertices();
		if(ortho_point_functions == true && enclosingForm.ortho_point_vertices.value != ''){
			ortho_point();
		}
		fachschale();
		if(polygonfunctions == true){
			path = enclosingForm.newpath.value;
			if(enclosingForm.pathwkt.value != ''){
				enclosingForm.firstpoly.value = true;
			}
			//enclosingForm.secondpoly.value = false;
			redrawsecondpolygon();
			if(enclosingForm.firstpoly.value == 'true')polygonarea();
		}
		if(linefunctions == true){
			redrawfirstline();
			redrawsecondline();
			if(enclosingForm.firstline.value == 'true')linelength();
		}
		if(multipointfunctions == true){
			redrawmultipoint();
		}		
		if((enclosingForm.always_draw != undefined && enclosingForm.always_draw.checked && enclosingForm.last_doing2.value == 'vertex_edit') || enclosingForm.last_button.value == 'vertex_edit1'){
			edit_vertices();
		}
		redrawpoint();
	}

	function focus_NAV(){
		// --------------- NAV-canvas aktivieren! ---------------------
	  document.getElementById('canvas_FS').setAttribute('visibility', 'hidden');
	  document.getElementById('canvas').setAttribute('visibility', 'visible');
		// --------------- FS-leiste ohne highlight ---------------------
	 		document.getElementById('text0').style.setProperty('fill','ghostwhite', '');
	}

	function focus_FS(){
		// --------------- NAV-canvas deaktivieren! ---------------------
	  document.getElementById('canvas').setAttribute('visibility', 'hidden');
	  document.getElementById('canvas_FS').setAttribute('visibility', 'visible');
		// --------------- NAV-leiste ohne highlight ---------------------
	  document.getElementById('zoomin0').style.setProperty('fill','ghostwhite', '');
	  document.getElementById('zoomout0').style.setProperty('fill','ghostwhite', '');
	  document.getElementById('recentre0').style.setProperty('fill','ghostwhite', '');
	}

	// -------------------------mausinteraktionen auf canvas------------------------------
	function mousedown(evt){
		cleartooltip();
	  if(top.document.GUI.stopnavigation.value == 0){
		if(mouse_coords_type == 'image'){					// Bildkoordinaten (Standardfall)		
			var g = document.getElementById('moveGroup');
			zx = g.getCTM().inverse();
			client_x = (evt.clientX * zx.a) + zx.e;
			client_y = resy - ((evt.clientY * zx.a) + zx.f);
	  	world_x = (client_x * scale) + minx;
	  	world_y = (client_y * scale) + miny;
		}
		else{																		// Weltkoordinaten (bei GPS oder Punktfang)
			world_x = evt.clientX;
			world_y = evt.clientY; 
	  	client_x = (world_x - minx)/scale;
	  	client_y = (world_y - miny)/scale;
		}
		if(evt.button == 1){			// mittlere Maustaste -> Pan
			if(evt.preventDefault)evt.preventDefault();
			else evt.returnValue = false; // IE fix
			if(enclosingForm.last_doing.value == 'measure'){
				save_measure_path();
			}
			if(enclosingForm.last_doing.value != 'vertex_edit' && enclosingForm.always_draw != undefined){
				enclosingForm.always_draw.checked = true;
			}
			enclosingForm.last_doing2.value = enclosingForm.last_doing.value;
			enclosingForm.last_doing.value = 'recentre';
		}

	  switch(enclosingForm.last_doing.value){
			case 'zoomin':
	  		startPoint(client_x, client_y);
			break;
			case 'zoomout':
				selectPoint(client_x, client_y);
			break;
			case 'recentre':				
				startMove(client_x, client_y);
			break;
			case 'ppquery':
				startPoint(client_x, client_y);
			break;
			case 'edit_other_object':
				startPoint(client_x, client_y);
			break;

			case 'draw_point':
	 			choose(world_x, world_y);
	 			redrawpoint();
			break;
			case 'draw_multipoint':
				addmultipoint(world_x, world_y);
				redrawmultipoint();
			break;			
			case 'draw_box':
	 			startpointFS(world_x, world_y);
			break;
			case 'draw_second_box':
				startpointFS_second(world_x, world_y);
		 break;
			case 'draw_line':
				addlinepoint_first(world_x, world_y);
				redrawfirstline();
			break;
			case 'draw_second_line':
				addlinepoint_second(world_x, world_y);
				if(enclosingForm.secondline.value == 'true'){
					top.ahah('index.php', 'go=spatial_processing&path1='+enclosingForm.pathwkt.value+'&path2='+path_second+'&operation=add&geotype=line&resulttype=svgwkt', new Array(enclosingForm.result, ''), new Array('setvalue', 'execute_function'));
				}
				redrawsecondline();
			break;
			case 'delete_lines':
				addpoint_second(world_x, world_y);
				if(enclosingForm.secondpoly.value == 'true'){
					top.ahah('index.php', 'go=spatial_processing&path1='+enclosingForm.pathwkt.value+'&path2='+path_second+'&operation=subtract&resulttype=svgwkt', new Array(enclosingForm.result, ''), new Array('setvalue', 'execute_function'));
				}
				redrawsecondline();
			break;
			
			case 'split_geometry':
				addlinepoint_second(world_x, world_y);
				if(enclosingForm.secondline.value == 'true'){
					top.ahah('index.php', 'go=spatial_processing&geotype=line&path1='+enclosingForm.pathwkt.value+'&path2='+path_second+'&operation=split&resulttype=svgwkt', new Array(enclosingForm.result, ''), new Array('setvalue', 'execute_function'));
				}
				//redrawsecondline();
			break;

			case 'draw_polygon':
				addpoint_first(world_x, world_y);
				redrawfirstpolygon();
			break;
			case 'draw_second_polygon':
				addpoint_second(world_x, world_y);
				if(enclosingForm.secondpoly.value == 'true'){
					top.ahah('index.php', 'go=spatial_processing&path1='+enclosingForm.pathwkt.value+'&path2='+path_second+'&operation=add&resulttype=svgwkt', new Array(enclosingForm.result, ''), new Array('setvalue', 'execute_function'));
				}
			break;
			case 'subtract_polygon':
				addpoint_second(world_x, world_y);
				if(enclosingForm.secondpoly.value == 'true'){
					top.ahah('index.php', 'go=spatial_processing&path1='+enclosingForm.pathwkt.value+'&path2='+path_second+'&operation=subtract&resulttype=svgwkt', new Array(enclosingForm.result, ''), new Array('setvalue', 'execute_function'));
				}
			break;
			case 'add_geom':
				startPoint(client_x, client_y);
			break;
			case 'subtract_geom':
				startPoint(client_x, client_y);
			break;		
			case 'vertex_edit':				// nix machen
			break;
			case 'add_buffered_line':
				addlinepoint_second(world_x, world_y);
				enclosingForm.firstpoly.value = 'true';
				enclosingForm.secondpoly.value = 'true';
				top.ahah('index.php', 'go=spatial_processing&path1='+enclosingForm.pathwkt.value+'&path2='+path_second+'&operation=add_buffered_line&width='+enclosingForm.bufferwidth.value+'&geotype=line&resulttype=svgwkt', new Array(enclosingForm.result, ''), new Array('setvalue', 'execute_function'));
			break;
			case 'add_circle':
				applypolygons();
				startPoint(client_x, client_y);
			break;			
			case 'add_parallel_polygon':
				addlinepoint_second(world_x, world_y);
				if(pathx_second.length > 1){
					enclosingForm.firstpoly.value = 'true';
					enclosingForm.secondpoly.value = true;
					top.ahah('index.php', 'go=spatial_processing&path1='+enclosingForm.pathwkt.value+'&path2='+path_second+'&operation=add_parallel_polygon&width='+enclosingForm.bufferwidth.value+'&side='+enclosingForm.bufferside.value+'&subtract='+enclosingForm.buffersubtract.value+'&geotype=line&resulttype=svgwkt', new Array(enclosingForm.result, ''), new Array('setvalue', 'execute_function'));
				}				
			break;
			case 'add_parallel_line':
				addlinepoint_second(world_x, world_y);
				if(pathx_second.length > 1){
					enclosingForm.firstline.value = 'true';
					enclosingForm.secondline.value = true;
					top.ahah('index.php', 'go=spatial_processing&path1='+enclosingForm.pathwkt.value+'&path2='+path_second+'&operation=add_parallel_line&width='+enclosingForm.bufferwidth.value+'&side='+enclosingForm.bufferside.value+'&geotype=line&resulttype=svgwkt', new Array(enclosingForm.result, ''), new Array('setvalue', 'execute_function'));
				}				
			break;			
			case 'add_buffer_within_polygon':
				pathx_second.push(world_x);
				pathy_second.push(world_y);
				path_second = buildsvglinepath(pathx_second, pathy_second);
				pathx_second.pop();
				pathy_second.pop();
				client_y = resy - client_y;
				enclosingForm.INPUT_COORD.value  = client_x+','+client_y+';'+client_x+','+client_y;
				enclosingForm.firstpoly.value = 'true';
				enclosingForm.secondpoly.value = 'true';
				buffer_geom = enclosingForm.buffer_geom.value;		// die gesicherte Geometrie, um die gepuffert werden soll
				top.ahah('index.php', 'go=spatial_processing&path1='+enclosingForm.pathwkt.value+'&path2='+path_second+'&path3='+buffer_geom+'&operation=add_buffer_within_polygon&input_coord='+enclosingForm.INPUT_COORD.value+'&pixsize='+scale+'&resulttype=svgwkt&geom_from_layer='+enclosingForm.geom_from_layer.value+'&geotype=line&resulttype=svgwkt', new Array(enclosingForm.result, ''), new Array('setvalue', 'execute_function'));				
			break;
			case 'move_geometry':
				startMoveGeom(client_x, client_y);
			break;
			case 'rotate_geometry':
				startRotateGeom(world_x, world_y);
			break;
			
			case 'ortho_point':
				add_ortho_point(world_x, world_y, null, null, true);
			break;

			case 'measure':
		    if (measuring){
		      addpoint(client_x, client_y);
					measured_distance = new_distance;
		    }
		    else {
		      startMeasure(client_x, client_y);
		    }
			break;

			default:
				alert('Fehlerhafte Eingabe! Übergebene Daten: '+enclosingForm.last_doing.value);
			break;
		}
		if(polygonfunctions){
			redrawsecondpolygon();
		}
	}
  }
	
function mousemove(evt){
	if(deactivated_foreign_vertex != 0){		// wenn es einen deaktivierten foreign vertex gibt, wird dieser jetzt wieder aktiviert
		document.getElementById(deactivated_foreign_vertex).setAttribute('pointer-events', 'auto');
		deactivated_foreign_vertex = 0;
	}
	if(enclosingForm.last_doing.value == 'vertex_edit' && selected_vertex != undefined && selected_vertex != ''){
		move_vertex(evt, selected_vertex, 'image');
	}
	if(enclosingForm.last_doing.value == 'split_geometry' && pathx_second.length < 2){
		client_x = evt.clientX;
  	client_y = resy - evt.clientY;
  	world_x = (client_x * scale) + minx;
  	world_y = (client_y * scale) + miny;
		pathx_second.push(world_x);
	  pathy_second.push(world_y);
		path_second = buildsvglinepath(pathx_second, pathy_second);
		pixel_path_second = world2pixelsvg(path_second);
	  var obj = document.getElementById('line_second');
	  obj.setAttribute('d', pixel_path_second);
		pathx_second.pop();
		pathy_second.pop();
	} 
	else{
		if (dragging){
			movePoint(evt);
		}
		else{
			if (draggingFS){
				if (enclosingForm.firstpoly.value == 'true') {
					movepointFS_second(evt);
				}
				else{
					movepointFS(evt);
				}
	  	}
			else{
				if (moving){
					moveVector(evt);
				}
				else{
					if(enclosingForm.last_doing.value == 'measure'){
			      if (measuring){
							client_x = evt.clientX;
	  					client_y = resy - evt.clientY;
			        showMeasurement(client_x, client_y);
			      }
			      else {
			      	show_tooltip('Startpunkt setzen',evt.clientX,evt.clientY)
			      }
					}
					else{
						if(movinggeom){
							moveGeom(evt);
						}
						else {
							if(rotatinggeom){
								rotateGeom(evt);
							}
						}
					}
				}
			}
		}
	}
	if (enclosingForm.last_doing.value == 'edit_other_object') {
		hidetooltip(evt);
	}
}

function mouseup(evt){
	if(dragging){
		endPoint(evt);
		enclosingForm.secondpoly.value = 'true';
		switch (enclosingForm.last_doing.value) {

			case 'add_geom': {
				top.ahah('index.php', 'go=spatial_processing&path1='+enclosingForm.pathwkt.value+'&input_coord='+enclosingForm.INPUT_COORD.value+'&pixsize='+scale+'&operation=add_geometry&resulttype=svgwkt&singlegeom='+enclosingForm.singlegeom.checked+'&geom_from_layer='+enclosingForm.geom_from_layer.value,new Array(enclosingForm.result, ''), new Array('setvalue', 'execute_function'));
				if(polygonfunctions == true){
					enclosingForm.firstpoly.value = 'true';
				}
				else{
					enclosingForm.firstline.value = 'true';
				}
			}
			break;

			case 'subtract_geom': {
				top.ahah('index.php', 'go=spatial_processing&path1='+enclosingForm.pathwkt.value+'&input_coord='+enclosingForm.INPUT_COORD.value+'&pixsize='+scale+'&operation=subtract_geometry&resulttype=svgwkt&singlegeom='+enclosingForm.singlegeom.checked+'&geom_from_layer='+enclosingForm.geom_from_layer.value, new Array(enclosingForm.result, ''), new Array('setvalue', 'execute_function'));
			}
			break;
			
			case 'add_circle': {		
				if (enclosingForm.CMD.value == 'add_circle_box') {
					// Rechteck aufgezogen => an allen Stützpunkten der abgefragten Geometrie Kreise erzeugen
					enclosingForm.firstpoly.value = 'true';
					enclosingForm.secondpoly.value = 'true';
					top.ahah('index.php', 'go=spatial_processing&path1='+enclosingForm.pathwkt.value+'&input_coord='+enclosingForm.INPUT_COORD.value+'&pixsize='+scale+'&operation=add_buffered_vertices&width='+enclosingForm.bufferwidth.value+'&resulttype=svgwkt&singlegeom='+enclosingForm.singlegeom.checked+'&geom_from_layer='+enclosingForm.geom_from_layer.value, new Array(enclosingForm.result, ''), new Array('setvalue', 'execute_function'));
				}
				else {
					// einfacher Klick => einen Kreis erzeugen
					addlinepoint_second(world_x, world_y);
					enclosingForm.firstpoly.value = 'true';
					enclosingForm.secondpoly.value = 'true';
					top.ahah('index.php', 'go=spatial_processing&path1='+enclosingForm.pathwkt.value+'&path2='+path_second+'&operation=add_buffered_line&width='+enclosingForm.bufferwidth.value+'&geotype=line&resulttype=svgwkt', new Array(enclosingForm.result, ''), new Array('setvalue', 'execute_function'));
				}
			}
			break;
		}
	}
	if(moving){
		endMove(enclosingForm.last_doing.value);
	}
	if(draggingFS){
    endpointFS(evt);
  }
	if(movinggeom){
		endMoveGeom(evt);
	}
	if(rotatinggeom){
		endRotateGeom(evt);
	}
}

	// ----------------------ausgewaehlten button highlighten---------------------------

	function highlightbyid(id){
		if(id != ''){			
			if(document.querySelector('.active_navbutton')){
				//document.querySelector('.active_navbutton').classList.remove('active_navbutton');		// kann der IE nicht
				document.querySelector('.active_navbutton').className.baseVal = 'navbutton_frame';	// deswegen dieser workaround
			}
			//document.getElementById(id).classList.add('active_navbutton');						// kann der IE nicht
			document.getElementById(id).className.baseVal += ' active_navbutton';				// deswegen dieser workaround
		  if(polygonfunctions == true){
				remove_vertices();
				remove_in_between_vertices();
		  }
			if(linefunctions == true){
				remove_vertices();
				remove_in_between_vertices();
			}
			if(multipointfunctions == true){
				remove_vertices();
			}			
			enclosingForm.last_button.value = id;
			if(id == 'recentre0'){
				document.getElementById('canvas').setAttribute('cursor', 'move');
				document.getElementById('canvas').setAttribute('cursor', 'grab');
			}
			else if(id == 'ppquery0' || id == 'edit_other_object0'){
				document.getElementById('canvas').setAttribute('cursor', 'help');
			}
			else{
				document.getElementById('canvas').setAttribute('cursor', 'crosshair');
			}
		}
	}

	function fachschale(){
		dragging  = false;
		dragdone  = false;
		moving  = false;
		moved  = false;
		highlightbyid(enclosingForm.last_button.value);
		if(enclosingForm.last_doing.value == 'recentre'){
			document.getElementById('canvas').setAttribute('cursor', 'move');
	  	document.getElementById('canvas').setAttribute('cursor', 'grab');
		}
		else{
	  	document.getElementById('canvas').setAttribute('cursor', 'crosshair');
		}
	}
	
	function buildsvglinepath(pathx, pathy){
		svgpath = 'M '+pathx[0]+' '+pathy[0];
		for(var i = 1; i < pathx.length; ++i){
	  	svgpath = svgpath+' '+pathx[i]+' '+pathy[i];
	 	}
	  return svgpath;
	}

	function buildsvgpath(pathx, pathy){
		svgpath = 'M '+pathx[0]+' '+pathy[0];
		for(var i = 1; i < pathx.length; ++i){
	  	svgpath = svgpath+' '+pathx[i]+' '+pathy[i];
	 	}
	 	svgpath = svgpath+' '+pathx[0]+' '+pathy[0];
	  return svgpath;
	}
	
	function addlinepoint_second(worldx, worldy){
		// neuen punkt setzen
		enclosingForm.lastcoordx.value = world_x;
		enclosingForm.lastcoordy.value = world_y;
	  pathx_second.push(world_x);
	  pathy_second.push(world_y);
		if(enclosingForm.pathx_second.value != ''){
			enclosingForm.pathx_second.value = enclosingForm.pathx_second.value+';'+world_x;
			enclosingForm.pathy_second.value = enclosingForm.pathy_second.value+';'+world_y;
		}
		else{
			enclosingForm.pathx_second.value = world_x;
			enclosingForm.pathy_second.value = world_y;
		}
		if(pathx_second.length == 1){
			document.getElementById('startvertex').setAttribute('cx', (world_x-minx)/scale);
			document.getElementById('startvertex').setAttribute('cy', (world_y-miny)/scale);
		}
		else{
			document.getElementById('startvertex').setAttribute('cx', -500);
			document.getElementById('startvertex').setAttribute('cy', -500);
		}
	  path_second = buildsvglinepath(pathx_second, pathy_second);
	  if(pathy_second.length > 1){
	  	enclosingForm.secondline.value = true;
	  }
	}
	
	function redrawsecondline(){
	 	// Line um punktepfad erweitern
	  var obj = document.getElementById('line_first');
	  pixel_path = world2pixelsvg(enclosingForm.newpath.value);
	  obj.setAttribute('d', pixel_path);
	  pixel_path_second = world2pixelsvg(path_second);
	  var obj = document.getElementById('line_second');
	  obj.setAttribute('d', pixel_path_second);		
	}
	
	function clear_first_line(){
		var obj = document.getElementById('line_first');
	  obj.setAttribute('d', '');
	}
	
	function remove_second_line(){
		if(enclosingForm.secondline.value == 'true' || enclosingForm.secondpoly.value == 'true'){
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
			enclosingForm.secondline.value = false;
			enclosingForm.secondpoly.value = false;
			enclosingForm.pathx_second.value = '';
			enclosingForm.pathy_second.value = '';
		}
	}
";


	$coord_input_functions = '

	coord_input_functions = true;
	
	function dec2dms(number, coordtype){
		number = number+"";
		part1 = number.split(".");
		degrees = part1[0];
		minutes = parseFloat("0."+part1[1]) * 60;
		if(coordtype == "dmin"){
			minutes = Math.round(minutes*1000)/1000;
			minutes = minutes+"";
			return degrees+"°"+minutes;
		}
		else{
			minutes = minutes+"";
			part2 = minutes.split(".");
			minutes = part2[0];
			if(part2[1] != undefined)seconds = Math.round(parseFloat("."+part2[1]) * 60);
			else seconds = "00";
			return degrees+"°"+minutes+"\'"+seconds+"\'\'";
		}			
	}
	
	function dms2dec(number, coordtype){
		var seconds = 0;
		number = number+"";
		part1 = number.split("°");
		degrees = parseFloat(part1[0]);
		part2 = part1[1].split("\'");
		minutes = parseFloat(part2[0]);
		if(coordtype == "dms"){
			seconds = part2[1].replace(/\'\'/g, "");
			seconds = parseFloat(seconds)/60;
		}
		minutes = (minutes+seconds)/60;
		return Math.round((degrees + minutes)*10000)/10000;  
	}

	function coord_input(){
		coordtype = \''.$this->user->rolle->coordtype.'\';
		viewer_epsg = \''.$this->user->rolle->epsg_code.'\';
		doing = enclosingForm.last_doing.value;
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
		mittex = Math.round(minx+(maxx-minx)/2);
		mittey = Math.round(miny+(maxy-miny)/2);
		if(viewer_epsg == 4326 && coordtype != "dec"){
			mittex = dec2dms(mittex);
			mittey = dec2dms(mittey);
		}
		var Msg = top.$("#message_box");
		Msg.show();
		content = \'<div style="position: absolute;top: 0px;right: 0px"><a href="javascript:void(0)" onclick="top.$(\\\'#message_box\\\').hide();" title="Schlie&szlig;en"><img style="border:none" src="'.GRAPHICSPATH.'exit2.png"></img></a></div>\';
		content+= \'<div style="height: 30px">Koordinateneingabe</div>\';
		content+= \'<table style="padding: 5px"><tr><td align="left" style="width: 300px" class="px15">Koordinate</td></tr>\';
		content+= \'<tr><td><input style="width: 310px" type="text" id="input_coords" name="input_coords" value="\'+mittex+\' \'+mittey+\'"></td></tr>\';
		content+= \'<tr><td>Koordinatensystem:&nbsp;<select name="epsg_code" id="epsg_code" style="width: 310px">'.$epsg_codes.'</select></td></tr></table>\';
		content+= \'<br><input type="button" value="OK" onclick="coord_input_submit()">\';
		Msg.html(content);
	}
		
	function coord_input_submit(){
		coordtype = \''.$this->user->rolle->coordtype.'\';
		viewer_epsg = \''.$this->user->rolle->epsg_code.'\';
		coords1 = top.document.getElementById(\'input_coords\').value;
		epsgcode = top.document.getElementById(\'epsg_code\').value;
		if(coords1){
			coords2 = coords1.split(" ");
			if(epsgcode == 4326 && coordtype != "dec"){
				coords2[0] = dms2dec(coords2[0], coordtype)+"";
				coords2[1] = dms2dec(coords2[1], coordtype)+"";
			}
			if(!coords2[0] || !coords2[1] || coords2[0].search(/[^-\d.]/g) != -1 || coords2[1].search(/[^-\d.]/g) != -1){
				alert("Falsches Format");
				return;
			}
			top.$(\'#message_box\').hide();
			if(viewer_epsg == epsgcode){
				set_coord(coords2[0], coords2[1]);
			}
			else{
				top.document.getElementById(\'epsg_code\').value = viewer_epsg;
				top.ahah("index.php", "go=spatial_processing&operation=transformPoint&point="+coords2.join(\' \')+"&newSRID="+viewer_epsg+"&curSRID="+epsgcode+"&coordtype="+coordtype+"&code2execute=coord_input_submit();&resulttype=svgwkt", new Array(top.document.getElementById(\'input_coords\'), ""), new Array("setvalue", "execute_function"));
			}
		}
	}
			
	function set_coord(coordx, coordy){
		mouse_coords_type = "world";
		evt1 = new Object();
		evt1.clientX = coordx;
		evt1.clientY = coordy;
		mousedown(evt1);
		mouse_coords_type = "image";
		// if(coordx < minx || coordx > maxx || coordy < miny || coordy > maxy){		// wenn Punkt ausserhalb des Kartenausschnittes -> hinzoomen (erstmal rausgenommen, da es bei "gepufferte Linie" nicht funktioniert)
			// pathx[0] = (coordx-minx)/scale;
			// pathy[0] = resy-((coordy-miny)/scale);
			// sendpath("recentre", pathx, pathy);
		// }
	}

	';
	
	$ortho_point_functions = '

	ortho_point_functions = true;
	
	function ortho_point(){
		enclosingForm.last_doing.value = "ortho_point";
		if(enclosingForm.ortho_point_vertices.value == "")ortho_point_vertices = new Array();
		helmert = new top.HelmertTransformation4Js(0, 0, 0, 0);
		mittex = Math.round(minx+(maxx-minx)/2);
		mittey = Math.round(miny+(maxy-miny)/2);
		var Msg = top.$("#message_box");
		Msg[0].style.left = "70%";
		Msg.show();
		content = \'<div style="height: 30px">Orthogonalpunktberechnung</div>\';
		content+= \'<span class="px15">1. Setzen Sie in der Karte durch 2 Klicks die beiden Punkte für die Bezugslinie.</span>\';
		content+= \'<div id="ortho_points"></div>\';
		content+= \'<br><input id="ortho_point_button" type="button" style="display:none;margin-right: 10px" value="neuer Punkt" onclick="add_ortho_point(null, null, 0, 0, false)"><input type="button" value="Beenden" onclick="remove_ortho_points();$(\\\'#message_box\\\').hide();">\';
		Msg.html(content);
		create_all_ortho_points();
	}
	
	function create_all_ortho_points(){
		if(enclosingForm.ortho_point_vertices.value != ""){
			var o_p_local_x2 = o_p_local_x;
			var o_p_local_y2 = o_p_local_y;
			enclosingForm.last_button.value = "ortho_point1";
			enclosingForm.always_draw.checked = false;
			var o_p_vertices = enclosingForm.ortho_point_vertices.value.split("|");
			remove_ortho_points();
			for(var o = 0; o < o_p_vertices.length; o++){
				if(o < 2){
					var a = o_p_vertices[o].split(" ");
					add_ortho_point(a[0], a[1], o_p_local_x2[o], o_p_local_y2[o], false);
				}
				else add_ortho_point(null, null, o_p_local_x2[o], o_p_local_y2[o], false);
			}
		}
	}
	
	function remove_ortho_points(){
		o_p_local_x = new Array();
		o_p_local_y = new Array();
		ortho_point_vertices = new Array();
		enclosingForm.ortho_point_vertices.value = "";
		var ortho_point_div = top.document.getElementById("ortho_points");
		while(ortho_point_div.firstChild){
			ortho_point_div.removeChild(ortho_point_div.firstChild);
		}
		var ortho_point_vertices_group = document.getElementById("ortho_point_vertices");
		while(ortho_point_vertices_group.firstChild){
			ortho_point_vertices_group.removeChild(ortho_point_vertices_group.firstChild);
		}
	}
	
	function add_ortho_point(world_x, world_y, local_x, local_y, deactivate){
	  var vertex;
		var point_number = ortho_point_vertices.length;
		var id = "ortho_point_vertex_"+point_number;
		if(world_x == null && local_x != null){
			world = get_world_ortho_point_coord(local_x, local_y);
			world_x = world[0];
			world_y = world[1];
		}
		if(local_x == null){
			local_coord = get_local_ortho_point_coord(world_x, world_y, point_number);
			local_x = local_coord[0];
			local_y = local_coord[1];
		}		
		enclosingForm.lastcoordx.value = world_x;
		enclosingForm.lastcoordy.value = world_y;
		ortho_point_vertices.push(world_x+" "+world_y);
		enclosingForm.ortho_point_vertices.value = ortho_point_vertices.join("|");
		vertex = create_catch_vertex(document.getElementById("kreis3"), id, world_x, world_y);
		vertex.setAttribute("style","fill: #1481F5");
		if(deactivate){
			vertex.setAttribute("pointer-events", "none");		// Events bei diesem Vertex deaktivieren, sonst wird durch den Mouseup gleich noch einer angelegt
			deactivated_foreign_vertex = id;
		}
		document.getElementById("ortho_point_vertices").appendChild(vertex);
		point_div = top.document.createElement("div");
		point_div.className = "ortho_point_div";
		point_x = top.document.createElement("input");
		point_y = top.document.createElement("input");
		point_x.value = local_x;
		o_p_local_x.push(local_x);
		point_y.value = local_y;
		o_p_local_y.push(local_y);
		point_x.name="ortho_point_x[]";
		point_y.name="ortho_point_y[]";
		point_x.type = point_y.type = "text";
		point_x.oninput = point_y.oninput = function(){change_ortho_point(point_number)};
		point_y.onkeyup = function(evt){if (evt.keyCode === 13) {add_ortho_point(null, null, 0, 0, false);}};
		point_x.autocomplete = point_y.autocomplete = "off";
		point_div.appendChild(point_x);
		point_div.appendChild(point_y);
		top.document.getElementById("ortho_points").appendChild(point_div);
		point_x.focus();
		if(point_number == 1){
			calculate_transformation_parameters();
			top.document.getElementById("ortho_points").appendChild(top.document.createTextNode("2. Sie können nun weitere Punkte hinzufügen."));
			top.document.getElementById("ortho_point_button").style.display = "";
		}
	}
	
	function change_ortho_point(point_number){
		var id = "ortho_point_vertex_"+point_number;
		var vertex = document.getElementById(id);
		var local_x = top.document.getElementsByName("ortho_point_x[]")[point_number].value;
		var local_y = top.document.getElementsByName("ortho_point_y[]")[point_number].value;
		o_p_local_x[point_number] = local_x;
		o_p_local_y[point_number] = local_y;
		if(point_number > 1){																																			// ein Kleinpunkt wird veraendert
			world = get_world_ortho_point_coord(local_x, local_y);
			vertex.setAttribute("x", world[0]);
			vertex.setAttribute("y", world[1]);
			x = Math.round((world[0] - parseFloat(enclosingForm.minx.value))/parseFloat(enclosingForm.pixelsize.value));
			y = Math.round((world[1] - enclosingForm.miny.value)/parseFloat(enclosingForm.pixelsize.value));
			vertex.setAttribute("cx", x);
			vertex.setAttribute("cy", y);
			ortho_point_vertices[point_number] = world[0]+" "+world[1];
		}
		else{																																											// ein Bezugspunkt wird veraendert
			calculate_transformation_parameters();
			ops = top.document.getElementsByName("ortho_point_x[]");																// alle Kleinpunkte neu berechnen
			for(var o = 0; o < ops.length; o++){
				if(o > 1)change_ortho_point(o);
			};
		}
		enclosingForm.ortho_point_vertices.value = ortho_point_vertices.join("|");
	}
	
	function get_world_ortho_point_coord(local_x, local_y){
		return helmert.transformToWorld(local_y, local_x, 3);
	}
	
	function get_local_ortho_point_coord(world_x, world_y, point_number){
		var coord = new Array();
		if(point_number == 0){																// lokale Koordinaten von Punkt a
			coord[0] = 0;
			coord[1] = 0;
		}
		else if(point_number == 1){														// lokale Koordinaten von Punkt b
			var a = ortho_point_vertices[0].split(" ");					// Welt-Koordinaten von Punkt a
			var m = new Array();
			m[0] = world_x - a[0];
			m[1] = world_y - a[1];
			coord[0] = Math.sqrt((m[0]*m[0])+(m[1]*m[1]));
			coord[1] = 0;
		}
		else{																									// lokale Koordinaten der Kleinpunkte
			coord = helmert.transformToLocal(world_x, world_y, 3);
		}
		return coord;
	}
	
	function calculate_transformation_parameters(){
		var a = ortho_point_vertices[0].split(" ");			// Welt-Koordinaten von Punkt a
		var b = ortho_point_vertices[1].split(" ");			// Welt-Koordinaten von Punkt b
		var x0 = top.document.getElementsByName("ortho_point_x[]")[0].value;
		var y0 = top.document.getElementsByName("ortho_point_y[]")[0].value;
		var x1 = top.document.getElementsByName("ortho_point_x[]")[1].value;
		var y1 = top.document.getElementsByName("ortho_point_y[]")[1].value;
		helmert.calcTransformationParameter([{
      "local" : {
      	"y": y0,
       	"x": x0
      },
      "world" : {
       	"y": parseFloat(a[0]),
       	"x": parseFloat(a[1])
      }
    }, {
      "local" : {
       	"y": y1,
       	"x": x1
      },
      "world" : {
     		"y": parseFloat(b[0]),
       	"x": parseFloat(b[1])
      }}]);
	}
	
	';	

	$pointfunctions = '

	pointfunctions = true;
	
	function rotate_point_direction(){
		angle = 360 - enclosingForm.angle.value.replace(",", ".");
		custom_angle = top.document.getElementById("custom_angle");
		if(custom_angle != undefined)custom_angle.value = angle;
		dir_arrow = document.getElementById("point_direction");
		dir_arrow.setAttribute("transform", "rotate("+angle+", 0 0)");
	}
	
	function draw_point(){
	  //document.getElementById("canvas_FS").setAttribute("cursor", "text");
	  if(polygonfunctions == true){
		 	if(enclosingForm.secondpoly.value == "true"){
				applypolygons();
			}
	  }
	 	if(polygonXORpoint){
	 		restart();
	 	}
		enclosingForm.last_doing.value = "draw_point";
	}

	// ------------------------texteinfuegepunkt setzen-----------------------------
	function choose(worldx, worldy){
	  // neuen punkt setzen
		textx = worldx;
		texty = worldy;
	  sendBWlocation(worldx, worldy);
	}
	
	function restart(){
		textx = null;
		texty = null;
	  sendBWlocation(null, null);
		redrawpoint();
	}
	';

	$multipointfunctions = '

	multipointfunctions = true;

	function update_geometry(){
		document.getElementById("cartesian").setAttribute("transform", "translate(0,'.$res_y.') scale(1,-1)");
		updatepaths();
		if (["move_geometry", "rotate_geometry"].includes(enclosingForm.last_doing.value)){
			enclosingForm.pathwkt.value = enclosingForm.newpathwkt.value;
		}
		redrawmultipoint();
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
	
	function draw_multipoint(){
		applymultipoint();
		enclosingForm.last_doing.value = "draw_multipoint";
	}

	// ------------------------Punkt setzen-----------------------------
	function addmultipoint(worldx, worldy){
		// neuen punkt setzen
		enclosingForm.lastcoordx.value = world_x;
		enclosingForm.lastcoordy.value = world_y; 
	  pathx.push(world_x);
	  pathy.push(world_y);
	  path = buildsvglinepath(pathx,pathy);
	  enclosingForm.newpath.value = path;
		applymultipoint();
	}

	function redrawmultipoint(){
	  var obj = document.getElementById("multipoint");
		pixel_path = world2pixelsvg(enclosingForm.newpath.value);
	  obj.setAttribute("d", pixel_path);
	}

	function buildwktmultipointfromsvgpath(svgpath){
		if(svgpath != ""){		
			var wkt = "";
			svgpath = svgpath.substring(2);		// "M " abschneiden
			coord = svgpath.split(" ");
			wkt = wkt+coord[0]+" "+coord[1];
			for(var i = 2; i < coord.length; i=i+2){
				if(coord[i] != ""){
					wkt = wkt+"),("+coord[i]+" "+coord[i+1];
				}
			}
			wkt = "MULTIPOINT(("+wkt+"))";
			return wkt;
		}
		else{
			return "";
		}
	}	

	function applymultipoint(){
		enclosingForm.pathwkt.value = enclosingForm.newpathwkt.value = buildwktmultipointfromsvgpath(enclosingForm.newpath.value);
	}	

	function activate_vertex(evt){
		if(enclosingForm.last_doing.value == "vertex_edit"){
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
	
	function deactivate_vertex(evt){
		if(enclosingForm.last_doing.value == "vertex_edit"){
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
				svg_path = enclosingForm.newpath.value+"";
				components = svg_path.split(" ");
				components[parseInt(vertex_id[1])] = vertex_new_world_x;
		  	components[parseInt(vertex_id[1])+1] = vertex_new_world_y;
				pathx[Math.floor(parseInt(vertex_id[1])/2)] = vertex_new_world_x;
		  	pathy[Math.floor(parseInt(vertex_id[1])/2)] = vertex_new_world_y;
				new_svg_path = components[0];
				for(i = 1; i < components.length; i++){
					new_svg_path = new_svg_path + " " + components[i];
				}
				enclosingForm.newpath.value = new_svg_path;
				redrawmultipoint();
				vertex_moved = true;
			}
		}
	}

	function delete_vertex(evt){
		vertex = evt.target;
		if(selected_vertex == vertex){
			vertex_id_string = vertex.getAttribute("id");
			vertex_id = vertex_id_string.split("_");
			svg_path = enclosingForm.newpath.value+"";
			components = svg_path.split(" ");
			if(components.length > 2){			// nur loeschen, wenn mindestens 1 Eckpunkt uebrig
				components.splice(parseInt(vertex_id[1]), 2);
				pathx.splice(Math.floor(parseInt(vertex_id[1])/2), 1);
				pathy.splice(Math.floor(parseInt(vertex_id[1])/2), 1);
				new_svg_path = components.join(" ");
				enclosingForm.newpath.value = new_svg_path;
	
				if(enclosingForm.newpathwkt.value != ""){			// wenn ein WKT-String da ist, diesen neu aus dem SVG erstellen
					applymultipoint();
				}
				remove_vertices();													// alle entfernen
				pixel_path = world2pixelsvg(new_svg_path);
				add_vertices(pixel_path);										// und wieder hinzufuegen, damit die Nummerierung wieder stimmt
				redrawmultipoint();
				selected_vertex = "";
				last_selected_vertex = "";
			}
		}
	}

	function end_vertex_move(evt){
		if(selected_vertex == evt.target){
			if(vertex_moved == true){
				if(enclosingForm.newpathwkt.value != ""){
					applymultipoint();
				}
				remove_vertices();													// alle entfernen
				pixel_path = world2pixelsvg(enclosingForm.newpath.value);
				add_vertices(pixel_path);										// und wieder hinzufuegen
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
	
	function add_vertices(pixel_path){
		pixel_path = pixel_path+"";
		components = pixel_path.split(" ");
		var parent = document.getElementById("vertices");
		circle = new Array();
		kreis1 = document.getElementById("kreis");
		for(i = 1; i < components.length; i=i+2){
			// Eckpunkte
			circle[i] = kreis1.cloneNode(true);
			circle[i].setAttribute("cx", components[i]);
			circle[i].setAttribute("cy", components[i+1]);
			circle[i].setAttribute("style","fill: #FF0000");
			circle[i].setAttribute("id", "vertex_"+i);
			parent.appendChild(circle[i]);
		}
	}

	function edit_vertices(){
		highlightbyid("vertex_edit1");
		save_geometry_for_undo();
		enclosingForm.last_doing.value = "vertex_edit";
		pixel_path = world2pixelsvg(enclosingForm.newpath.value);
		add_vertices(pixel_path);
	}

	function save_geometry_for_undo(){
		newpath_undo = enclosingForm.newpath.value;
		newpathwkt_undo = enclosingForm.newpathwkt.value;
	}

	function undo_geometry_editing(){
		enclosingForm.newpath.value = newpath_undo;
		enclosingForm.newpathwkt.value = newpathwkt_undo;
		remove_vertices();													// alle entfernen
		pixel_path = world2pixelsvg(enclosingForm.newpath.value);
		add_vertices(pixel_path);										// und wieder hinzufuegen
		redrawmultipoint();
	}
	
	function restart(){
		highlightbyid(\'text0\');
		enclosingForm.last_doing.value = "draw_multipoint";
		enclosingForm.newpath.value = "";
		enclosingForm.pathwkt.value = "";
		enclosingForm.newpathwkt.value = "";
		enclosingForm.result.value = "";
		path = "";
		var alle = pathx.length;
		for(var i = 0; i < alle; ++i){
		  pathx.pop();
		  pathy.pop();
		}
		redrawmultipoint();
	}
	';

	$linefunctions = '

	linefunctions = true;

	function update_geometry(){
		if(enclosingForm.secondline.value == "true" || enclosingForm.secondpoly.value == "true"){
			document.getElementById("cartesian").setAttribute("transform", "translate(0,'.$res_y.') scale(1,-1)");
			updatepaths();
			if (["add_geom", "subtract_geom", "move_geometry", "rotate_geometry"].includes(enclosingForm.last_doing.value)){
				enclosingForm.pathwkt.value = enclosingForm.newpathwkt.value;
				if(enclosingForm.secondline.value == "true" && must_redraw){
					applylines();
					must_redraw = false;
				}
				enclosingForm.secondline.value = "true";
			}
			wktstring = enclosingForm.newpathwkt.value + "";
			if(must_redraw){
				redrawsecondline();
				must_redraw = false;
			}
		}
	}

	function addlinepoint_first(worldx, worldy){
		// neuen punkt setzen
		enclosingForm.lastcoordx.value = world_x;
		enclosingForm.lastcoordy.value = world_y; 
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
	  enclosingForm.newpath.value = path;
	  if(pathy.length > 1){
	  	enclosingForm.firstline.value = true;
	  	linelength();
	  }
	}

	function addpoint_second(worldx, worldy) {
	  // neuen punkt setzen
		enclosingForm.lastcoordx.value = world_x;
		enclosingForm.lastcoordy.value = world_y;
	  poly_pathx_second.push(world_x);
	  poly_pathy_second.push(world_y);
	  path_second = buildsvgpath(poly_pathx_second, poly_pathy_second);
	  if(poly_pathy_second.length > 2){
	  	enclosingForm.secondpoly.value = true;
	  }
	}

	function redrawfirstline(){
		// Line um punktepfad erweitern
	  var obj = document.getElementById("line_first");
		pixel_path = world2pixelsvg(enclosingForm.newpath.value);
	  obj.setAttribute("d", pixel_path);
	}
	
	function add_line(){
		var alles = pathx_second.length;
		for(var i = 0; i < alles; ++i){
			pathx_second.pop();
			pathy_second.pop();
		}
		enclosingForm.pathx_second.value = "";
		enclosingForm.pathy_second.value = "";
		applylines();
		if(enclosingForm.firstline.value == "true"){
			enclosingForm.last_doing.value = "draw_second_line";
		}
		else{
			enclosingForm.last_doing.value = "draw_line";
		}
	}
	
	function delete_lines(){
		remove_second_line();
		applylines();
		enclosingForm.last_doing.value = "delete_lines";
	}

	function split_geometry(){
		applylines();
		enclosingForm.last_doing.value = "split_geometry";
	}
	
	function reverse_geom(){
		applylines();
		enclosingForm.secondline.value = true;
		must_redraw = true;
		top.ahah("index.php", "go=spatial_processing&path1="+enclosingForm.pathwkt.value+"&operation=reverse&resulttype=svgwkt", new Array(enclosingForm.result, ""), new Array("setvalue", "execute_function"));
	}
	
	function applylines(){
		if(enclosingForm.pathwkt.value == "" && enclosingForm.newpath.value != ""){
			enclosingForm.pathwkt.value = buildwktlinefromsvgpath(enclosingForm.newpath.value);
		}
		else{
			enclosingForm.pathwkt.value = enclosingForm.newpathwkt.value;
		}
		remove_second_line();
	}
	
	function restart(){
		highlightbyid(\'line0\');
		enclosingForm.last_doing.value = "draw_line";
		enclosingForm.newpath.value = "";
		enclosingForm.pathwkt.value = "";
		enclosingForm.newpathwkt.value = "";
		enclosingForm.result.value = "";
		enclosingForm.linelength.value = "";
		path = "";
		enclosingForm.firstline.value = false;
		enclosingForm.secondline.value = false;
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
		enclosingForm.pathx_second.value = "";
		enclosingForm.pathy_second.value = "";
		var length = poly_pathx_second.length;
		for(i = 0; i < length; i++ ){
			poly_pathx_second.pop();
			poly_pathy_second.pop();
		}
		path_second = "";
		redrawsecondline();
		redraw();
		if(enclosingForm.split != undefined)enclosingForm.split.style.visibility = "hidden";
	}

	function deletelast(evt){
		switch(enclosingForm.last_doing.value){
			case "draw_line":
				if(pathx.length > 2){
					pathx.pop();
					pathy.pop();
					path = buildsvglinepath(pathx,pathy);
					enclosingForm.newpath.value = path;
					redrawfirstline();
				}
			break;
			case "draw_second_line":
				if(pathx_second.length > 2){
					pathx_second.pop();
					pathy_second.pop();
					str = enclosingForm.pathx_second.value;
					enclosingForm.pathx_second.value = str.substring(0, str.lastIndexOf(";"));
					str = enclosingForm.pathy_second.value;
					enclosingForm.pathy_second.value = str.substring(0, str.lastIndexOf(";"));
					path_second = buildsvglinepath(pathx_second,pathy_second);
					if(enclosingForm.secondline.value == "true"){
						top.ahah("index.php", "go=spatial_processing&path1="+enclosingForm.pathwkt.value+"&path2="+path_second+"&operation=add&geotype=line&resulttype=svgwkt", new Array(enclosingForm.result, ""), new Array("setvalue", "execute_function"));
					}
					redrawsecondline();
				}
			break;
			case "vertex_edit":
				undo_geometry_editing();
			breaK;
		}
	}
		
	function buildwktlinefromsvgpath(svgpath){
		if(svgpath != ""){
			var koords;			
			var wkt = "";
			linestrings = svgpath.split("M ");
			for(var k = 1; k < linestrings.length; k++){
				if(k > 1)wkt = wkt+"),(";
				coord = linestrings[k].split(" ");
				wkt = wkt+coord[0]+" "+coord[1];
				for(var i = 2; i < coord.length; i=i+2){
					if(coord[i] != ""){
						wkt = wkt+","+coord[i]+" "+coord[i+1];
					}
				}
			}
			if(linestrings.length > 2)wkt = "MULTILINESTRING(("+wkt+"))";
			else wkt = "LINESTRING("+wkt+")";
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
		if(enclosingForm.last_doing.value == "vertex_edit"){
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
		if(enclosingForm.last_doing.value == "vertex_edit"){
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
		if(enclosingForm.last_doing.value == "vertex_edit"){
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
				svg_path = enclosingForm.newpath.value+"";
				components = svg_path.split(" ");
				components[parseInt(vertex_id[1])] = vertex_new_world_x;
		  	components[parseInt(vertex_id[1])+1] = vertex_new_world_y;
				new_svg_path = components[0];
				for(i = 1; i < components.length; i++){
					new_svg_path = new_svg_path + " " + components[i];
				}
				enclosingForm.newpath.value = new_svg_path;
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
			svg_path = enclosingForm.newpath.value+"";
			components = svg_path.split(" ");
			new_svg_path = "M";
			for(i = 1; i < components.length+1; i++){
				if(vertex_id[2] == i-2){
					new_svg_path = new_svg_path + " " + x_world + " " + y_world;
				}
				if(components[i] != undefined)new_svg_path = new_svg_path + " " + components[i];
			}
			enclosingForm.newpath.value = new_svg_path;

			if(enclosingForm.newpathwkt.value != ""){			// wenn ein WKT-String da ist, hier auch den Vertex einfuegen
				wktarray = get_array_from_wktstring(enclosingForm.newpathwkt.value);
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
				enclosingForm.newpathwkt.value = wktstring;
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
			svg_path = enclosingForm.newpath.value+"";
			components = svg_path.split(" ");
			if(components.length > 6){			// nur loeschen, wenn mindestens 3 Eckpunkte uebrig
				components.splice(parseInt(vertex_id[1]), 2);
				if(components[parseInt(vertex_id[1])-1] == "M" && ( components[parseInt(vertex_id[1])+2] == "M" || components[parseInt(vertex_id[1])+2] == undefined)){
					components.splice(parseInt(vertex_id[1]-1), 3);			// in diesem Fall hat der Teil-Linestring nur 2 Eckpunkte und wird komplett entfernt
				}
				if(components[parseInt(vertex_id[1])-3] == "M" && ( components[parseInt(vertex_id[1])] == "M" || components[parseInt(vertex_id[1])] == undefined)){
					components.splice(parseInt(vertex_id[1]-3), 3);			// in diesem Fall hat der Teil-Linestring nur 2 Eckpunkte und wird komplett entfernt
				}
				new_svg_path = components.join(" ");
				enclosingForm.newpath.value = new_svg_path;
	
				if(enclosingForm.newpathwkt.value != ""){			// wenn ein WKT-String da ist, diesen neu aus dem SVG erstellen
					enclosingForm.newpathwkt.value = buildwktlinefromsvgpath(new_svg_path);
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
				if(enclosingForm.newpathwkt.value != ""){
					vertex_id_string = selected_vertex.getAttribute("id");
					vertex_id = vertex_id_string.split("_");
					wktarray = get_array_from_wktstring(enclosingForm.newpathwkt.value);
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
					enclosingForm.newpathwkt.value = wktstring;
				}
				remove_vertices();													// alle entfernen
				remove_in_between_vertices();
				pixel_path = world2pixelsvg(enclosingForm.newpath.value);
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
		highlightbyid("vertex_edit1");
		remove_second_line();
		save_geometry_for_undo();
		enclosingForm.last_doing.value = "vertex_edit";
		pixel_path = world2pixelsvg(enclosingForm.newpath.value);
		add_vertices(pixel_path);
	}

	function save_geometry_for_undo(){
		newpath_undo = enclosingForm.newpath.value;
		newpathwkt_undo = enclosingForm.newpathwkt.value;
	}

	function undo_geometry_editing(){
		enclosingForm.newpath.value = newpath_undo;
		enclosingForm.newpathwkt.value = newpathwkt_undo;
		remove_vertices();													// alle entfernen
		remove_in_between_vertices();
		pixel_path = world2pixelsvg(enclosingForm.newpath.value);
		add_vertices(pixel_path);										// und wieder hinzufuegen
		redrawfirstline();
	}
	
	function linelength(){
		length1 = top.document.getElementById("custom_length");
		if(length1 == undefined){						// wenn es ein Laenge-Attribut gibt, wird das verwendet, ansonsten die normale Laengenanzeige
			length1 = enclosingForm.linelength;
		}
	  if(enclosingForm.newpathwkt.value != ""){
	  	top.ahah("index.php", "go=spatial_processing&geotype=line&path1="+enclosingForm.newpathwkt.value+"&operation=length&", new Array(enclosingForm.linelength, length1), "");
	  }
	  else{
	  	if(enclosingForm.newpath.value != ""){
	  		top.ahah("index.php", "go=spatial_processing&geotype=line&path2="+enclosingForm.newpath.value+"&operation=length", new Array(enclosingForm.linelength, length1), "");
	  	}
	  	else{
	  		enclosingForm.linelength.value = "0.0";
	  	}
	  }
	}

	function add_parallel_line(){
		enclosingForm.last_doing.value = "add_parallel_line";			
		var Msg = top.$("#message_box");
		Msg.show();
		content = \'<div style="position: absolute;top: 0px;right: 0px"><a href="javascript:void(0)" onclick="top.$(\\\'#message_box\\\').hide();" title="Schlie&szlig;en"><img style="border:none" src="'.GRAPHICSPATH.'exit2.png"></img></a></div>\';
		content+= \'<div style="width:320px;height: 30px">parallele Linie erzeugen</div>\';
		content+= \'<table style="padding: 5px;width: 100%"><tr><td align="right" class="px15">Abstand:</td><td><input style="width: 110px" type="text" id="buffer_width" name="buffer_width" value="\'+enclosingForm.bufferwidth.value+\'">&nbsp;m</td></tr>\';
		content+= \'<tr><td align="right">Seite:&nbsp;</td><td><select name="buffer_side" id="buffer_side" style="width: 110px"><option value="left">links</option><option value="right">rechts</option></select></td></tr></table>\';
		content+= \'<br><input type="button" value="OK" onclick="add_parallel_line_submit()">\';
		Msg.html(content);
		if(enclosingForm.pathwkt.value == "" && enclosingForm.newpath.value != ""){
			enclosingForm.pathwkt.value = buildwktlinefromsvgpath(enclosingForm.newpath.value);
		}
		else{
			if(enclosingForm.newpathwkt.value != ""){
				enclosingForm.pathwkt.value = enclosingForm.newpathwkt.value;
			}
		}
		applylines();
	}
	
	function add_parallel_line_submit(){
		enclosingForm.bufferwidth.value = enclosingForm.buffer_width.value;
		enclosingForm.bufferside.value = enclosingForm.buffer_side.value;
		top.$(\'#message_box\').hide();
	}

	';

	$boxfunctions = '

	boxfunctions = true;

	function draw_box_on() {
		applypolygons();
		if (enclosingForm.firstpoly.value == "true") {
			enclosingForm.last_doing.value = "draw_second_box";
		}
		else{
			enclosingForm.last_doing.value = "draw_box";
		}
	}

	// ----------------------------box aufziehen---------------------------------
	function startpointFS(worldx, worldy) {
		enclosingForm.firstpoly.value = false;
		var alles = pathx.length;
		for(var i = 0; i < alles; ++i){
			pathx.pop();
			pathy.pop();
		}
	  draggingFS  = true;
	  pathx.push(worldx);
	  pathy.push(worldy);
	  path = buildsvgpath(pathx, pathy);
	  enclosingForm.newpath.value = path;
	}

	function startpointFS_second(worldx, worldy) {
		var alles = poly_pathx_second.length;
		for(var i = 0; i < alles; ++i){
			poly_pathx_second.pop();
			poly_pathy_second.pop();
		}
		draggingFS  = true;
	  poly_pathx_second.push(world_x);
	  poly_pathy_second.push(world_y);
	  path_second = buildsvgpath(poly_pathx_second, poly_pathy_second);
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
	  enclosingForm.newpath.value = path;
	  redrawfirstpolygon();
	}

	function movepointFS_second(evt) {
		if (!draggingFS) return;
	  // neuen punkt abgreifen
	  clientx = evt.clientX;
	  clienty = resy - evt.clientY;
	  world_x = (clientx * scale) + minx;
	  world_y = (clienty * scale) + miny;
	  poly_pathx_second[1]  = poly_pathx_second[0];
	  poly_pathy_second[1]  = world_y;
	  poly_pathx_second[2]  = world_x;
	  poly_pathy_second[2]  = world_y;
	  poly_pathx_second[3]  = world_x;
	  poly_pathy_second[3]  = poly_pathy_second[0];
	  path_second = buildsvgpath(poly_pathx_second, poly_pathy_second);
	  enclosingForm.secondpoly.value = true;
	  redrawsecondpolygon();
	}

	function endpointFS(evt) {
	  draggingFS  = false;
		enclosingForm.firstpoly.value = true;
		if (enclosingForm.secondpoly.value == "true") {
			var width = Math.abs(Math.round((poly_pathx_second[2] - poly_pathx_second[1]) * 1000) / 1000);
			var height = Math.abs(Math.round((poly_pathy_second[0] - poly_pathy_second[2]) * 1000) / 1000);
		}
		else {
			var width = Math.abs(Math.round((pathx[2] - pathx[1]) * 1000) / 1000);
			var height = Math.abs(Math.round((pathy[0] - pathy[2]) * 1000) / 1000);
		}
		var Msg = top.$("#message_box");
		Msg[0].style.left = "75%";
		Msg.show();
		content = \'<div style="position: absolute;top: 0px;right: 0px"><a href="javascript:void(0)" onclick="top.$(\\\'#message_box\\\').hide();" title="Schlie&szlig;en"><img style="border:none" src="'.GRAPHICSPATH.'exit2.png"></img></a></div>\';
		content+= \'<div style="width:320px;height: 30px">Rechteck</div>\';
		content+= \'<table style="padding: 5px;width: 100%"><tr><td align="right" class="px15">Breite:</td><td><input style="width: 110px" type="text" id="rect_width" name="rect_width" value="\'+width+\'">&nbsp;m</td></tr>\';
		content+= \'<tr><td align="right">Höhe:&nbsp;</td><td><input style="width: 110px" type="text" id="rect_height" name="rect_height" value="\'+height+\'">&nbsp;m</td></tr></table>\';
		content+= \'<br><input type="button" value="OK" onclick="change_box_width_height()">\';
		Msg.html(content);
		if (enclosingForm.secondpoly.value == "true"){
			top.ahah("index.php", "go=spatial_processing&path1="+enclosingForm.pathwkt.value+"&path2="+path_second+"&operation=add&resulttype=svgwkt", new Array(enclosingForm.result, ""), new Array("setvalue", "execute_function"));
		}
	}
	
	function change_box_width_height() {
		if (enclosingForm.rect_width.value && enclosingForm.rect_height.value) {
			var width = parseFloat(enclosingForm.rect_width.value);
			var height = parseFloat(enclosingForm.rect_height.value);
			if (enclosingForm.secondpoly.value == "true") {
				if (poly_pathy_second[0] > poly_pathy_second[2]) {
					height = -1 * height;
				}
				poly_pathy_second[1] = poly_pathy_second[2] = (poly_pathy_second[0] + height);
				poly_pathx_second[2] = poly_pathx_second[3] = (poly_pathx_second[0] + width);
				path_second = buildsvgpath(poly_pathx_second, poly_pathy_second);
				enclosingForm.secondpoly.value = true;
				redrawsecondpolygon();
				top.ahah("index.php", "go=spatial_processing&path1="+enclosingForm.pathwkt.value+"&path2="+path_second+"&operation=add&resulttype=svgwkt", new Array(enclosingForm.result, ""), new Array("setvalue", "execute_function"));
			}
			else {
				if (pathy[0] > pathy[2]) {
					height = -1 * height;
				}
				pathy[1] = pathy[2] = (pathy[0] + height);
				pathx[2] = pathx[3] = (pathx[0] + width);
				path = buildsvgpath(pathx,pathy);
				enclosingForm.newpath.value = path;
				redrawfirstpolygon();
			}
		}
	}
	
	';
	
	$bufferfunctions ='

		bufferfunctions = true;
		
		function add_buffer(){		
			var Msg = top.$("#message_box");
			Msg.show();
			content = \'<div style="position: absolute;top: 0px;right: 0px"><a href="javascript:void(0)" onclick="top.$(\\\'#message_box\\\').hide();" title="Schlie&szlig;en"><img style="border:none" src="'.GRAPHICSPATH.'exit2.png"></img></a></div>\';
			content+= \'<div style="width:320px;height: 30px">Puffer hinzufügen</div>\';
			content+= \'<table style="padding: 5px;width: 100%"><tr><td align="right" class="px15">Breite:</td><td><input style="width: 110px" type="text" id="buffer_width" name="buffer_width" value="\'+enclosingForm.bufferwidth.value+\'">&nbsp;m</td></tr>\';
			content+= \'<tr><td align="right">Segmente im Viertelkreis:&nbsp;</td><td><input style="width: 110px" type="text" id="segment_count" name="segment_count" value="8"></td></tr></table>\';
			content+= \'<br><input type="button" value="OK" onclick="add_buffer_submit()">\';
			Msg.html(content);
		}
			
		function add_buffer_submit(){
			top.$(\'#message_box\').hide();
			if (enclosingForm.buffer_width.value) {
				enclosingForm.secondpoly.value = true;
				enclosingForm.firstpoly.value = true;
				if (enclosingForm.newpathwkt.value != "") {
					newpathwkt = enclosingForm.newpathwkt.value;
				}
				else {
					if (enclosingForm.newpath.value != "") {
						newpathwkt = buildwktpolygonfromsvgpath(enclosingForm.newpath.value);
					}
				}
				top.ahah("index.php", "go=spatial_processing&path1="+newpathwkt+"&width="+enclosingForm.buffer_width.value+"&segment_count="+enclosingForm.segment_count.value+"&operation=buffer&resulttype=svgwkt", new Array(enclosingForm.result, ""), new Array("setvalue", "execute_function"));
			}
		}
		
		function add_buffered_line(){
			enclosingForm.last_doing.value = "add_buffered_line";
			enclosingForm.bufferwidth.value = prompt("Breite des Puffers in Metern:", enclosingForm.bufferwidth.value);
			if(enclosingForm.pathwkt.value == "" && enclosingForm.newpath.value != ""){
				enclosingForm.pathwkt.value = buildwktpolygonfromsvgpath(enclosingForm.newpath.value);
			}
			else{
				if(enclosingForm.newpathwkt.value != ""){
					enclosingForm.pathwkt.value = enclosingForm.newpathwkt.value;
				}
			}
		  if(enclosingForm.secondpoly.value == "true"){
				applypolygons();
			}
		}

		function add_circle(){
			enclosingForm.last_doing.value = "add_circle";
			enclosingForm.bufferwidth.value = prompt("Kreisradius in Metern:", enclosingForm.bufferwidth.value);
			if(enclosingForm.pathwkt.value == "" && enclosingForm.newpath.value != ""){
				enclosingForm.pathwkt.value = buildwktpolygonfromsvgpath(enclosingForm.newpath.value);
			}
			else{
				if(enclosingForm.newpathwkt.value != ""){
					enclosingForm.pathwkt.value = enclosingForm.newpathwkt.value;
				}
			}
		  if(enclosingForm.secondpoly.value == "true"){
				applypolygons();
			}
		}		
		
		function add_parallel_polygon(){
			enclosingForm.last_doing.value = "add_parallel_polygon";			
			var Msg = top.$("#message_box");
			Msg.show();
			content = \'<div style="position: absolute;top: 0px;right: 0px"><a href="javascript:void(0)" onclick="top.$(\\\'#message_box\\\').hide();" title="Schlie&szlig;en"><img style="border:none" src="'.GRAPHICSPATH.'exit2.png"></img></a></div>\';
			content+= \'<div style="width:320px;height: 30px">einseitig gepufferte Linie erzeugen</div>\';
			content+= \'<table style="padding: 5px;width: 100%"><tr><td align="right" class="px15">Breite:</td><td><input style="width: 110px" type="text" id="buffer_width" name="buffer_width" value="\'+enclosingForm.bufferwidth.value+\'">&nbsp;m</td></tr>\';
			content+= \'<tr><td align="right">Seite:&nbsp;</td><td><select name="buffer_side" id="buffer_side" style="width: 110px"><option value="left">links</option><option value="right">rechts</option></select></td></tr>\';
			content+= \'<tr><td align="right">Aktion:&nbsp;</td><td><select name="buffer_subtract" id="buffer_subtract" style="width: 110px"><option value="0">hinzufügen</option><option value="1">abziehen</option></select></td></tr></table>\';
			content+= \'<br><input type="button" value="OK" onclick="add_parallel_polygon_submit()">\';
			Msg.html(content);
			if(enclosingForm.pathwkt.value == "" && enclosingForm.newpath.value != ""){
				enclosingForm.pathwkt.value = buildwktpolygonfromsvgpath(enclosingForm.newpath.value);
			}
			else{
				if(enclosingForm.newpathwkt.value != ""){
					enclosingForm.pathwkt.value = enclosingForm.newpathwkt.value;
				}
			}
		  if(enclosingForm.secondpoly.value == "true"){
				applypolygons();
			}
		}
		
		function add_parallel_polygon_submit(){
			enclosingForm.bufferwidth.value = enclosingForm.buffer_width.value;
			enclosingForm.bufferside.value = enclosingForm.buffer_side.value;
			enclosingForm.buffersubtract.value = enclosingForm.buffer_subtract.value;
			top.$(\'#message_box\').hide();
		}

	';
	
	$special_buffer_functions ='
	
		special_bufferfunctions = true;
		
		function add_buffer_within_polygon(){
			enclosingForm.last_doing.value = "add_buffer_within_polygon";
			if(enclosingForm.pathwkt.value == "" && enclosingForm.newpath.value != ""){
				enclosingForm.pathwkt.value = buildwktpolygonfromsvgpath(enclosingForm.newpath.value);
			}
			else{
				if(enclosingForm.newpathwkt.value != ""){
					enclosingForm.pathwkt.value = enclosingForm.newpathwkt.value;
				}
			}
		  if(enclosingForm.secondpoly.value == "true"){
				applypolygons();
			}
		}
	
	';

	$flurstqueryfunctions ='

		flurstuecksqueryfunctions = true;

		function add_geometry(){
			if(polygonfunctions){
				if(enclosingForm.pathwkt.value == "" && enclosingForm.newpath.value != ""){
					enclosingForm.pathwkt.value = buildwktpolygonfromsvgpath(enclosingForm.newpath.value);
				}
				else{
					enclosingForm.pathwkt.value = enclosingForm.newpathwkt.value;
				}
				if(enclosingForm.secondpoly.value == "true"){
					applypolygons();
				}
			}
			else applylines();
			enclosingForm.last_doing.value = "add_geom";
		};

		function subtract_geometry(){
			if(polygonfunctions){
				if(enclosingForm.pathwkt.value == "" && enclosingForm.newpath.value != ""){
					enclosingForm.pathwkt.value = buildwktpolygonfromsvgpath(enclosingForm.newpath.value);
				}
				else{
					enclosingForm.pathwkt.value = enclosingForm.newpathwkt.value;
				}
				if(enclosingForm.secondpoly.value == "true"){
					applypolygons();
				}
			}
			else applylines();
			enclosingForm.last_doing.value = "subtract_geom";
		};
	';

	$polygonfunctions = '

	polygonfunctions = true;

	function update_geometry(){
		if(enclosingForm.secondline != undefined && enclosingForm.secondline.value == "true" || enclosingForm.secondpoly.value == "true"){
			document.getElementById("cartesian").setAttribute("transform", "translate(0,'.$res_y.') scale(1,-1)");
			updatepaths();
			if (["add_geom", "subtract_geom", "add_circle", "move_geometry", "rotate_geometry"].includes(enclosingForm.last_doing.value)){
				enclosingForm.pathwkt.value = enclosingForm.newpathwkt.value;
				if(enclosingForm.secondpoly.value == "true" && must_redraw){
					applypolygons();
					must_redraw = false;
				}
			}
			if(must_redraw){
				redrawsecondpolygon();
				must_redraw = false;
			}
		}
	}
	
	function split_geometry(){
		applypolygons(false);
		remove_second_line();
		clear_first_line();
		enclosingForm.last_doing.value = "split_geometry";
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
		enclosingForm.lastcoordx.value = world_x;
		enclosingForm.lastcoordy.value = world_y; 
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
	  enclosingForm.newpath.value = path;
	  if(pathy.length > 2){
	  	enclosingForm.firstpoly.value = true;
			if(enclosingForm.firstpoly.onchange)enclosingForm.firstpoly.onchange();
	  	polygonarea();
	  }
	}

	function addpoint_second(worldx, worldy) {
	  // neuen punkt setzen
		enclosingForm.lastcoordx.value = world_x;
		enclosingForm.lastcoordy.value = world_y;
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
		if(enclosingForm.pathx_second.value != ""){
			enclosingForm.pathx_second.value = enclosingForm.pathx_second.value+";"+world_x;
			enclosingForm.pathy_second.value = enclosingForm.pathy_second.value+";"+world_y;
		}
		else{
			enclosingForm.pathx_second.value = world_x;
			enclosingForm.pathy_second.value = world_y;
		} 
	  path_second = buildsvgpath(pathx_second, pathy_second);
	  if(pathy_second.length > 2){
	  	enclosingForm.secondpoly.value = true;
	  }
	  else{
	  	enclosingForm.secondpoly.value = "started";
	  }
	}

	function redrawfirstpolygon(){
	  // polygone um punktepfad erweitern
	  var obj = document.getElementById("polygon_first");
		if(enclosingForm.newpath.value == "")enclosingForm.newpath.value = buildsvgpolygonfromwkt(enclosingForm.newpathwkt.value);
		pixel_path = world2pixelsvg(enclosingForm.newpath.value);
	  obj.setAttribute("d", pixel_path);
	}

	function activate_vertex(evt){
		if(enclosingForm.last_doing.value == "vertex_edit"){
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
		if(enclosingForm.last_doing.value == "vertex_edit"){
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
		if(enclosingForm.last_doing.value == "vertex_edit"){
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
				svg_path = enclosingForm.newpath.value+"";
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
				enclosingForm.newpath.value = new_svg_path;
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
			svg_path = enclosingForm.newpath.value+"";
			components = svg_path.split(" ");
			new_svg_path = "M";
			for(i = 1; i < components.length; i++){
				new_svg_path = new_svg_path + " " + components[i];
				if(vertex_id[2] == i-1){
					new_svg_path = new_svg_path + " " + x_world + " " + y_world;
				}
			}
			enclosingForm.newpath.value = new_svg_path;

			if(enclosingForm.newpathwkt.value != ""){			// wenn ein WKT-String da ist, hier auch den Vertex einfuegen
				wktarray = get_array_from_wktstring(enclosingForm.newpathwkt.value);
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
				enclosingForm.newpathwkt.value = wktstring;
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
			svg_path = String(enclosingForm.newpath.value);
			var components = svg_path.split(" ");
			if(components.length > 10){			// nur loeschen, wenn mindestens 4 Eckpunkte uebrig			
				components.splice(parseInt(vertex_id[1]), 2);				
				if(components[parseInt(vertex_id[1])-1] == "M" && ( components[parseInt(vertex_id[1])+2] == "M" || components[parseInt(vertex_id[1])+2] == undefined)){
					components.splice(parseInt(vertex_id[1]-1), 3);			// in diesem Fall hat das Teilpolygon nur 2 Eckpunkte und wird komplett entfernt
				}
				if(components[parseInt(vertex_id[1])-3] == "M" && ( components[parseInt(vertex_id[1])] == "M" || components[parseInt(vertex_id[1])] == undefined)){
					components.splice(parseInt(vertex_id[1]-3), 3);			// in diesem Fall hat das Teilpolygon nur 2 Eckpunkte und wird komplett entfernt
				}
				if(vertex_id[2] != ""){			// Anfangs und Endpunkt
					components[parseInt(vertex_id[2])-2] = components[parseInt(vertex_id[1])];
		  		components[parseInt(vertex_id[2])-1] = components[parseInt(vertex_id[1])+1];
				}
				new_svg_path = "M";
				for(i = 1; i < components.length; i++){
					if(components[i] != \'\' && components[i] != undefined){
						new_svg_path = new_svg_path + " " + components[i];
					}
				}
				enclosingForm.newpath.value = new_svg_path;
	
				if(enclosingForm.newpathwkt.value != ""){			// wenn ein WKT-String da ist, hier auch den Vertex loeschen
					wktarray = get_array_from_wktstring(enclosingForm.newpathwkt.value);
					wktarray.splice(parseInt(vertex_id[1]), 2);
					
					if(isNaN(wktarray[parseInt(vertex_id[1])-1]) && ( isNaN(wktarray[parseInt(vertex_id[1])+2]) || wktarray[parseInt(vertex_id[1])+2] == undefined)){
						wktarray.splice(parseInt(vertex_id[1]-1), 3);			// in diesem Fall hat das Teilpolygon nur 2 Eckpunkte und wird komplett entfernt
					}
					if(isNaN(wktarray[parseInt(vertex_id[1])-3]) && ( isNaN(wktarray[parseInt(vertex_id[1])]) || wktarray[parseInt(vertex_id[1])] == undefined)){
						wktarray.splice(parseInt(vertex_id[1]-3), 3);			// in diesem Fall hat das Teilpolygon nur 2 Eckpunkte und wird komplett entfernt
					}
					
					if(vertex_id[2] != ""){			// Anfangs und Endpunkt
						wktarray[parseInt(vertex_id[2])-2] = wktarray[parseInt(vertex_id[1])];
						wktarray[parseInt(vertex_id[2])-1] = wktarray[parseInt(vertex_id[1])+1];
					}
					wktstring = "";
					komma = 1;
					for(i = 0; i < wktarray.length; i++){
						if(wktarray[i] != "" && wktarray[i] != undefined){
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
					enclosingForm.newpathwkt.value = wktstring;
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
				if(enclosingForm.newpathwkt.value != ""){
					vertex_id_string = selected_vertex.getAttribute("id");
					vertex_id = vertex_id_string.split("_");
					wktarray = get_array_from_wktstring(enclosingForm.newpathwkt.value);
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
					enclosingForm.newpathwkt.value = wktstring;
				}
				remove_vertices();													// alle entfernen
				remove_in_between_vertices();
				pixel_path = world2pixelsvg(enclosingForm.newpath.value);
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
			if(components[i+4] == "M" || components[i+4] == undefined){
				circle[start].setAttribute("id", "vertex_"+start+"_"+parseInt(i+2));
				start = i+5;
				i = i + 3;
			}
		}
	}

	function edit_vertices(){
		highlightbyid("vertex_edit1");
		remove_second_poly()
		save_geometry_for_undo();
		enclosingForm.last_doing.value = "vertex_edit";
		pixel_path = world2pixelsvg(enclosingForm.newpath.value);
		add_vertices(pixel_path);
	}


	function save_geometry_for_undo(){
		newpath_undo = enclosingForm.newpath.value;
		newpathwkt_undo = enclosingForm.newpathwkt.value;
	}

	function undo_geometry_editing(){
		enclosingForm.newpath.value = newpath_undo;
		enclosingForm.newpathwkt.value = newpathwkt_undo;
		remove_vertices();													// alle entfernen
		remove_in_between_vertices();
		pixel_path = world2pixelsvg(enclosingForm.newpath.value);
		add_vertices(pixel_path);										// und wieder hinzufuegen
		redrawfirstpolygon();
	}

	function redrawsecondpolygon(){
	  // polygone um punktepfad erweitern
	  var obj = document.getElementById("polygon_first");
		if(enclosingForm.newpath.value == "")enclosingForm.newpath.value = buildsvgpolygonfromwkt(enclosingForm.newpathwkt.value);
	  pixel_path = world2pixelsvg(enclosingForm.newpath.value);
	  obj.setAttribute("d", pixel_path);
	  pixel_path_second = world2pixelsvg(path_second);
	  var obj = document.getElementById("polygon_second");
	  obj.setAttribute("d", pixel_path_second);
	}

	function buildsvgpolygonfromwkt(wkt){
		if(wkt != ""){
			var type = wkt.substring(0, 9);
			if(type == "MULTIPOLY"){
				var start = 15;
				var end = wkt.length-3;
				var delim = ")),((";
			}
			else{			// POLYGON
				var start = 9;
				var end = wkt.length-2;
				var delim = "),(";
			}
			wkt = wkt.substring(start, end);
			var koords;
			parts = wkt.split(delim);
			for(j = 0; j < parts.length; j++){
				parts[j] = parts[j].replace(/,/g, " ");
			}
			svg = "M "+parts.join(" M ");
			return svg;
		}
		else{
			return "";
		}
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

	function deletelast(evt){
		switch(enclosingForm.last_doing.value){
			case "draw_polygon":
	  		if(pathx.length > 3){
					pathx.pop();
					pathy.pop();
					path = buildsvgpath(pathx,pathy);
					enclosingForm.newpath.value = path;
					redrawfirstpolygon();
				}
			break;
			case "draw_second_polygon": case "subtract_polygon": 
				if(pathx_second.length > 3){
					pathx_second.pop();
					pathy_second.pop();
					str = enclosingForm.pathx_second.value;
					enclosingForm.pathx_second.value = str.substring(0, str.lastIndexOf(";"));
					str = enclosingForm.pathy_second.value;
					enclosingForm.pathy_second.value = str.substring(0, str.lastIndexOf(";"));
					path_second = buildsvgpath(pathx_second,pathy_second);
					if(enclosingForm.last_doing.value == "draw_second_polygon"){
						top.ahah("index.php", "go=spatial_processing&path1="+enclosingForm.pathwkt.value+"&path2="+path_second+"&operation=add&resulttype=svgwkt", new Array(enclosingForm.result, ""), new Array("setvalue", "execute_function"));
					}
					else{
						if(enclosingForm.last_doing.value == "subtract_polygon"){				
							top.ahah("index.php", "go=spatial_processing&path1="+enclosingForm.pathwkt.value+"&path2="+path_second+"&operation=subtract&resulttype=svgwkt", new Array(enclosingForm.result, ""), new Array("setvalue", "execute_function"));
						}
					}
					redrawsecondpolygon();
				}
			break;
			case "add_buffered_line":
				if(pathx_second.length > 1){
					pathx_second.pop();
					pathy_second.pop();
					str = enclosingForm.pathx_second.value;
					enclosingForm.pathx_second.value = str.substring(0, str.lastIndexOf(";"));
					str = enclosingForm.pathy_second.value;
					enclosingForm.pathy_second.value = str.substring(0, str.lastIndexOf(";"));
					path_second = buildsvglinepath(pathx_second, pathy_second);
					top.ahah("index.php", "go=spatial_processing&path1="+enclosingForm.pathwkt.value+"&path2="+path_second+"&operation=add_buffered_line&width="+enclosingForm.bufferwidth.value+"&geotype=line&resulttype=svgwkt", new Array(enclosingForm.result, ""), new Array("setvalue", "execute_function"));
					redrawsecondpolygon();
				}
			break;
			case "add_parallel_polygon":
				if(pathx_second.length > 2){
					pathx_second.pop();
					pathy_second.pop();
					str = enclosingForm.pathx_second.value;
					enclosingForm.pathx_second.value = str.substring(0, str.lastIndexOf(";"));
					str = enclosingForm.pathy_second.value;
					enclosingForm.pathy_second.value = str.substring(0, str.lastIndexOf(";"));
					path_second = buildsvglinepath(pathx_second, pathy_second);
					top.ahah("index.php", "go=spatial_processing&path1="+enclosingForm.pathwkt.value+"&path2="+path_second+"&operation=add_parallel_polygon&width="+enclosingForm.bufferwidth.value+"&side="+enclosingForm.bufferside.value+"&subtract="+enclosingForm.buffersubtract.value+"&geotype=line&resulttype=svgwkt", new Array(enclosingForm.result, ""), new Array("setvalue", "execute_function"));
					redrawsecondpolygon();
				}
			break;
			case "vertex_edit":
				undo_geometry_editing();
			break;
		}
	}

	function clear_geometry(){
		textx = -1000000;
		texty = -1000000;
		redrawpoint();
		enclosingForm.newpath.value = "";
		enclosingForm.pathwkt.value = "";
		enclosingForm.newpathwkt.value = "";
		enclosingForm.result.value = "";
		enclosingForm.INPUT_COORD.value = "";
		enclosingForm.area.value = "";
		path = "";
		enclosingForm.firstpoly.value = false;
		enclosingForm.secondpoly.value = false;
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
		enclosingForm.pathx_second.value = "";
		enclosingForm.pathy_second.value = "";
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

	function restart(){
		highlightbyid(\'pgon0\');
		enclosingForm.last_doing.value = "draw_polygon";
		enclosingForm.last_doing2.value = "draw_polygon";
		clear_geometry();
	}

	function applypolygons(isvalid_check = true){
		var Msg = top.$("#message_box");
		Msg.hide();
		Msg.html("");
		if(enclosingForm.pathwkt.value == "" && enclosingForm.newpath.value != ""){
			enclosingForm.pathwkt.value = buildwktpolygonfromsvgpath(enclosingForm.newpath.value);
		}
		else{
			if(enclosingForm.newpathwkt.value != ""){
				enclosingForm.pathwkt.value = enclosingForm.newpathwkt.value;
			}
		}
		if (isvalid_check) {
			top.ahah("index.php", "go=spatial_processing&path1="+enclosingForm.pathwkt.value+"&operation=isvalid", new Array(""), new Array("execute_function"));
		}
		remove_second_poly();
	}
	
	function remove_second_poly(){
		if(enclosingForm.secondpoly.value == "true"){
			path = enclosingForm.newpath.value;
			var length = pathx_second.length;
			for(i = 0; i < length; i++ ){
				pathx_second.pop();
				pathy_second.pop();
			}
			path_second = "";
			redrawsecondpolygon();
			enclosingForm.secondpoly.value = false;
			enclosingForm.pathx_second.value = "";
			enclosingForm.pathy_second.value = "";
		}
	}
	
	function subtr_polygon(){
		applypolygons();
		enclosingForm.last_doing.value = "subtract_polygon";
	}

	function add_polygon(){		
		var alles = pathx_second.length;
		for(var i = 0; i < alles; ++i){
			pathx_second.pop();
			pathy_second.pop();
		}
		enclosingForm.pathx_second.value = "";
		enclosingForm.pathy_second.value = "";
		applypolygons();
		if(enclosingForm.firstpoly.value == "true"){
			enclosingForm.last_doing.value = "draw_second_polygon";
		}
		else{
			enclosingForm.last_doing.value = "draw_polygon";
		}
	}

	function polygonarea(){
		area = top.document.querySelector(".custom_area");
		if(area == undefined){						// wenn es ein Flaeche-Attribut gibt, wird das verwendet, ansonsten die normale Flaechenanzeige
			area = enclosingForm.area;
		}
	  if(enclosingForm.newpathwkt.value != ""){
	  	if(enclosingForm.areaunit == undefined){
	  		top.ahah("index.php", "go=spatial_processing&path1="+enclosingForm.newpathwkt.value+"&operation=area", new Array(enclosingForm.area, area), "");
	  	}
	  	else{
	  		top.ahah("index.php", "go=spatial_processing&path1="+enclosingForm.newpathwkt.value+"&operation=area&unit="+enclosingForm.areaunit.value, new Array(enclosingForm.area, area), "");
	  	}
	  }
	  else{
	  	if(enclosingForm.newpath.value != ""){
	  		if(enclosingForm.areaunit == undefined){
	  			top.ahah("index.php", "go=spatial_processing&path2="+enclosingForm.newpath.value+"&operation=area", new Array(enclosingForm.area, area), "");
	  		}
	  		else{
	  			top.ahah("index.php", "go=spatial_processing&path2="+enclosingForm.newpath.value+"&operation=area&unit="+enclosingForm.areaunit.value, new Array(enclosingForm.area, area), "");
	  		}
	  	}
	  	else{
	  		enclosingForm.area.value = "0.0";
	  	}
	  }
	}
	';

$polygonfunctions2 = '
	polygonfunctions2 = true;	
';

$transformfunctions = '

	transformfunctions = true;

//---------------------- Verschieben der Geometrie -------------

	function move_geometry(){
		document.getElementById("canvas").setAttribute("cursor", "move");
		enclosingForm.last_doing.value = "move_geometry";
		if(polygonfunctions){
			applypolygons();
		}
		else if(linefunctions){
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
			enclosingForm.secondpoly.value = true;
			enclosingForm.secondline.value = true;
			must_redraw = true;
			top.ahah("index.php", "go=spatial_processing&path1="+enclosingForm.pathwkt.value+"&translate_x="+translate_x+"&translate_y="+translate_y+"&operation=translate&resulttype=svgwkt", new Array(enclosingForm.result, ""), new Array("setvalue", "execute_function"));
		}
	  movinggeom  = false;
	  moved  = false;
	}
	

//---------------------- Verschieben der Geometrie -------------

//---------------------- Drehen der Geometrie -------------

	function rotate_geometry(){
		document.getElementById("canvas").setAttribute("cursor", "se-resize");
		enclosingForm.last_doing.value = "rotate_geometry";
		if(polygonfunctions){
			applypolygons();
		}
		else if(linefunctions){
			applylines();
		}
	}
	
	function startRotateGeom(world_x, world_y){
		// centroid und zu drehende Geometrie berechnen
		top.ahah("index.php", "go=spatial_processing&path1="+enclosingForm.pathwkt.value+"&operation=centroid&mousex="+world_x+"&mousey="+world_y, new Array(enclosingForm.result), new Array("setvalue"));
		rotatinggeom  = true;
		startangle = null;
		mousex = world_x;
		mousey = world_y;
	}

	function getAngle(a, b) {
		var angle = Math.atan(b / a) * (180/Math.PI);
		if (angle < 0 && a > 0 && b < 0) {
			angle = 360 + angle;
		}
		else {
			if ((angle < 0 && a < 0) || b < 0) {
				angle = 180 + angle;
			}
		}
		return angle;
	}
	
	function rotateGeom(evt){
		var obj = document.getElementById("polygon_second");
		if (enclosingForm.result.value != "") {		// wenn centroid da ist, startangle berechnen
			var centroid_result = enclosingForm.result.value.split("||");
			var centroid_world = centroid_result[0].split(" ");
			centroid[0] = (centroid_world[0] - minx) / scale;
			centroid[1] = (centroid_world[1] - miny) / scale;
			move_x[0] = evt.clientX;
			move_y[0] = resy - evt.clientY;
			move_dx = move_x[0] - centroid[0];
			move_dy = move_y[0] - centroid[1];
			startangle = getAngle(move_dx, move_dy);
	  	obj.setAttribute("d", world2pixelsvg(centroid_result[1]));
			enclosingForm.result.value = "";
		}
		if (startangle != null) {		// wenn startangle da ist, rotieren
			move_x[1] = evt.clientX;
			move_y[1] = resy - evt.clientY;
			move_dx = move_x[1] - centroid[0];
			move_dy = move_y[1] - centroid[1];
			angle = getAngle(move_dx, move_dy);
			angle = angle - startangle;
			path = "rotate(" + angle + ", " + centroid[0] + " " + centroid[1] + ")";
			obj.setAttribute("transform", path);
			rotated = true;
		}
	}

	function endRotateGeom(evt) {
		var obj = document.getElementById("polygon_second");
	  if(rotated){
			enclosingForm.secondpoly.value = true;
			enclosingForm.secondline.value = true;
			must_redraw = true;
			top.ahah("index.php", "go=spatial_processing&path1="+enclosingForm.pathwkt.value+"&angle="+angle+"&operation=rotate&mousex="+mousex+"&mousey="+mousey+"&resulttype=svgwkt", new Array(enclosingForm.result, ""), new Array("setvalue", "execute_function"));
			obj.setAttribute("transform", "");
		}
	  rotatinggeom  = false;
	  rotated  = false;
	}
	

//---------------------- Drehen der Geometrie -------------

';

$vertex_catch_functions = '

	//-------------------- Punktfang -----------------------------

	function toggle_vertices(){
		remove_foreign_vertices();
		if(enclosingForm.punktfang.checked){
			request_foreign_vertices();
		}
	}

	function request_foreign_vertices(){
		top.ahah("index.php", "go=getSVG_vertices&geom_from_layer="+enclosingForm.geom_from_layer.value, new Array(enclosingForm.vertices, ""), new Array("setvalue", "execute_function"));
	}

	function remove_foreign_vertices(){
		var parent = document.getElementById("foreignvertices");
		var count = parent.childNodes.length;
		for(i = 0; i < count; i++){
			parent.removeChild(parent.lastChild);
		}
	}

	function activate_foreign_vertex(evt){
		if(enclosingForm.last_doing.value == "vertex_edit" && (selected_vertex == undefined || selected_vertex == "")){
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

	function add_foreign_vertex_mousedown(evt){
		// punktobjekt bilden, welches die Koordinaten aufnimmt
    function point(x,y) {
      this.clientX = x;
      this.clientY = y;
    }
		// Aufrufen der Funktion mousedown() fuer die jeweilige Aktion
    position = new point(evt.target.getAttribute("x"), evt.target.getAttribute("y"));
		mouse_coords_type = "world";
		mousedown(position);
		mouse_coords_type = "image";
	}

	function add_foreign_vertex_mouseup(evt){
		// punktobjekt bilden, welches die Koordinaten aufnimmt
    function point(x,y) {
      this.clientX = x;
      this.clientY = y;
    }
		// Aufrufen der Funktion mousedown() fuer die jeweilige Aktion
    position= new point(evt.target.getAttribute("x"), evt.target.getAttribute("y"));
		if(enclosingForm.last_doing.value == "vertex_edit"){
			if(last_selected_vertex != ""){
				selected_vertex = last_selected_vertex;
				position.target = selected_vertex;  
				move_vertex(position, last_selected_vertex, "world");
				end_vertex_move(position);
			}
		}
		else {
			mouseup(position);
		}
	}

	function show_vertices(){
		if(enclosingForm.vertices.value != ""){
			var parent = document.getElementById("foreignvertices");
			circle = new Array();
			var kreis1 = document.getElementById("kreis3");
			vertex_string = enclosingForm.vertices.value+"";
			enclosingForm.vertices.value = "";
			vertices = vertex_string.split("|");
			for(i = 0; i < vertices.length-1; i++){
				coords = vertices[i].split(" ");				
				circle[i] = create_catch_vertex(kreis1, "foreign_vertex_"+i, coords[0], coords[1]);
				parent.appendChild(circle[i]);
			}
		}
	}
	
	function create_catch_vertex(template, id, world_x, world_y){
		var circle = template.cloneNode(true);
		circle.setAttribute("x", world_x);
		circle.setAttribute("y", world_y);
		x = Math.round((world_x - parseFloat(enclosingForm.minx.value))/parseFloat(enclosingForm.pixelsize.value));
		y = Math.round((world_y - enclosingForm.miny.value)/parseFloat(enclosingForm.pixelsize.value));
		circle.setAttribute("cx", x);
		circle.setAttribute("cy", y);
		circle.setAttribute("style","fill: #00DD00");
		circle.setAttribute("id", id);
		return circle;
	}

	//------------------------------------------------------------

';	

$gps_functions = '  
  function update_gps_position(){
		navigator.geolocation.getCurrentPosition(
			function(position){		//success
				var Projection = "'.$this->epsg_codes[$this->user->rolle->epsg_code]['proj4text'].'";
				pos = top.proj4(Projection,[position.coords.longitude,position.coords.latitude]);
				top.document.GUI.gps_posx.value = pos[0];
				top.document.GUI.gps_posy.value = pos[1];
			},
			function(){}		// error
		)
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
 	}

	function set_gps_position() {
    // punktobjekt bilden, welches die Koordinaten aufnimmt
    function point(x,y) {
      this.clientX = x;
      this.clientY = y;
    }
    // Abfragen der aktuellen GPS Position
		if(enclosingForm.gps_posx.value != "" && enclosingForm.gps_posy.value != ""){
			// Aufrufen der Funktion mousedown() fuer die jeweilige Aktion
	    position= new point(enclosingForm.gps_posx.value, enclosingForm.gps_posy.value);
			if(enclosingForm.last_doing.value == "vertex_edit"){
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
		if(enclosingForm.gps_follow.value == "on"){
			enclosingForm.gps_follow.value = "off";
			document.getElementById("gps_text").firstChild.data = "off";
		}
		else{
			enclosingForm.gps_follow.value = "on";
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
			enclosingForm.str_pathx.value = str_pathx;
			enclosingForm.str_pathy.value = str_pathy;
			console.log(enclosingForm.str_pathx.value);
			top.document.GUI.measured_distance.value = measured_distance;
		}
	}
	
	function get_measure_path(){
		if(enclosingForm.str_pathx.value != ""){
			doing = "measure";
			measuring = true;
			var str_pathx = enclosingForm.str_pathx.value;
			var str_pathy = enclosingForm.str_pathy.value;
			world_pathx = str_pathx.split(";");
			world_pathy = str_pathy.split(";");  
			m_pathx[0] = (world_pathx[0] - minx)/scale;
			m_pathy[0] = (world_pathy[0] - miny)/scale;
			var length = world_pathx.length; 
		  for(var i = 1; i < length; i++){
		    m_pathx[i] = (world_pathx[i] - minx)/scale;
				m_pathy[i] = (world_pathy[i] - miny)/scale;
			}
			measured_distance = parseFloat(top.document.GUI.measured_distance.value);
		}
	}

	function startMeasure(client_x, client_y) {
	  restart_m();
	  measuring = true;
	  m_pathx[0] = client_x;
	  m_pathy[0] = client_y;
	}
	
	function calculate_reduction(pathx, pathy){
		k = 1;
		em = 0;
		hell = 0;
		r = '.EARTH_RADIUS.';
		used_nbs = new Array();
		if(r > 0 && top.nbh.length > 0){
			for(i = 0; i < pathx.length; i++){
				x = pathx[i] + "";
				y = pathy[i] + "";
				x_1 = x.substring(2,3);
				x_10 = x.substring(1,2);
				x_100 = x.substring(0,1);
				y_1 = y.substring(3,4);
				y_10 = y.substring(2,3);
				y_100 = y.substring(1,2);
				y_1000 = y.substring(0,1);
				nhn = 33+x_100+y_1000+y_100+x_10+x_1+y_10+y_1;
				if(top.nbh[nhn] == null)return 1;
				if(used_nbs[nhn] == null){				// wenn NB nicht schon durch einen anderen Stuetzpunkt verwendet wird
					used_nbs[nhn] = top.nbh[nhn];
					hell = hell + top.nbh[nhn];
				}
				em = em + parseInt(pathx[i]);
				em = em / pathx.length;
				hell = hell / used_nbs.length;
				k = (1 - (hell / r)) * (1 + (((em - 500000)*(em - 500000))/(2 * r * r))) * 0.9996;
			}
		}
		return k;
	}
	
	function calculate_distance(x1, y1, x2, y2){
		if('.$this->user->rolle->epsg_code.' == 4326){
			distance = '.EARTH_RADIUS.' * Math.acos(Math.sin(y1*Math.PI/180) * Math.sin(y2*Math.PI/180) + Math.cos(y1*Math.PI/180) * Math.cos(y2*Math.PI/180) * Math.cos((x2 - x1)*Math.PI/180))
		}
		else{
			distance = Math.sqrt(((x1-x2)*(x1-x2))+((y1-y2)*(y1-y2)));
		}
		var pathx = new Array(x1, x2);
		var pathy = new Array(y1, y2);
		k = calculate_reduction(pathx, pathy);
		distance = distance / k;
		return distance;
	}
	
	function showMeasurement(client_x, client_y){
	  addpoint(client_x, client_y);
	  
	  var track = 0, track0 = 0, output = "";
		j = m_pathx.length-1;
		
		x1 = (m_pathx[j-1] * scale) + minx;
		y1 = (m_pathy[j-1] * scale) + miny;
		x2 = (m_pathx[j] * scale) + minx;
		y2 = (m_pathy[j] * scale) + miny;
		
		new_distance = measured_distance + calculate_distance(x1, y1, x2, y2);	
		track0 = top.format_number(measured_distance, false, true, true);
		track = top.format_number(new_distance, false, true, true);
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
			<image id="mapimg" href="'.$bg_pic.'" height="100%" width="100%" y="0" x="0"/>
		  <g id="cartesian" transform="translate(0,'.$res_y.') scale(1,-1)">
				<path d="" id="line_second" style="fill:none;stroke:red;stroke-width:2" />
		  	<path d="" id="line_first" style="fill:none;stroke:blue;stroke-width:2"/>
		    <path d="" id="polygon_second" style="fill:none;stroke:red;stroke-width:2"/>
		    <path d="" id="polygon_first" style="fill-opacity:0.5;fill:rgb(192,192,255);stroke:blue;stroke-width:1.5"/>
		    <polygon points="" id="polygon" style="fill-opacity:0.5;fill:rgb(192,192,255);stroke:blue;stroke-width:2"/>
				<polyline points="" id="polyline" style="fill:none;stroke-dasharray:2,2;stroke:black;stroke-width:4"/>
				<use id="gps_position" style="stroke:red;" xlink:href="#crosshair_red" x="-1000" y="-1000"/>
				<use id="pointposition" xlink:href="#crosshair_blue" x="-500" y="-500"/>
				<path d="" id="multipoint" marker-start="url(#point)" marker-mid="url(#point)" marker-end="url(#point)" style="fill:none;stroke:blue;stroke-width:0"/>
				<circle id="startvertex" cx="-500" cy="-500" r="2" style="fill:blue;stroke:blue;stroke-width:2"/>
				<path d="" id="highlight" style="fill:none;stroke:blue;stroke-width:2"/>
			</g>
			<rect id="canvas" cursor="crosshair" onmousedown="mousedown(evt);" onmousemove="mousemove(evt);" onmouseup="mouseup(evt);" width="100%" height="100%" opacity="0" visibility="visible"/>
			<g id="in_between_vertices" transform="translate(0,'.$res_y.') scale(1,-1)"></g>
			<g id="vertices" transform="translate(0,'.$res_y.') scale(1,-1)"></g>
			<g id="ortho_point_vertices" transform="translate(0,'.$res_y.') scale(1,-1)"></g>
			<g id="foreignvertices" transform="translate(0,'.$res_y.') scale(1,-1)"></g>
	  </g>
		<g id="mapimg2_group">
			<image id="mapimg2" href="" height="100%" width="100%" y="0" x="0" style="display:none"/>
		</g>
	  <g id="templates">
	  	<circle style="-moz-user-select: none;" id="kreis" cx="-5000" cy="-5000" r="7" opacity="0.3" onmouseover="activate_vertex(evt)" onmouseout="deactivate_vertex(evt)" onmousedown="select_vertex(evt)" onmousemove="move_vertex(evt)" onmouseup="end_vertex_move(evt)" />
			<line stroke="#111" stroke-width="14" id="linie" x1="-5000" y1="-5000" x2="-5001" y2="-5001" opacity="0.3" onmouseover="activate_line(evt)" onmousemove="activate_line(evt)" />
			<circle id="kreis3" cx="-5000" cy="-5000" r="7" opacity="0.3" onmouseover="activate_foreign_vertex(evt)" onmouseout="deactivate_foreign_vertex(evt)" onmousedown="add_foreign_vertex_mousedown(evt)" onmouseup="add_foreign_vertex_mouseup(evt)" />
	  </g>
	  

	  <g id="buttons" onmouseout="hide_tooltip()" transform="translate(0 0)">
	  ';	
				
	function deletebuttons($strUndo, $strDelete){
		global $last_x;
		$deletebuttons = removebuttons($strDelete);
		$deletebuttons.= undobuttons($strUndo);
		return $deletebuttons;
	}
	
	function removebuttons($strDelete){
		global $last_x;
		$removebuttons = '
	      <g id="new" onmousedown="restart();" transform="translate('.$last_x.' 0 )">
					<rect id="new0" onmouseover="show_tooltip(\''.$strDelete.'\',evt.clientX,evt.clientY)" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
					<g class="navbutton" transform="translate(4 5) scale(0.8)">
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
							transform="matrix(1 0 0 1 0 0) translate(2 2) scale(0.06)"/>
					</g>
	      </g>';
		$last_x += 36;
		return $removebuttons;
	}
	
	function undobuttons($strUndo){
		global $last_x;
		$undobuttons = '
	      <g id="undo" onmousedown="deletelast(evt);" transform="translate('.$last_x.' 0)">
					<rect id="undo0" onmouseover="show_tooltip(\''.$strUndo.'\',evt.clientX,evt.clientY)" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
					<g class="navbutton" transform="translate(5 5) scale(1)">
						<g class="navbutton_stroke" transform="translate(0 -4.5)">
							<polygon points="178.579,57.7353 164.258,51.2544 178.96,44.515 174.48,51.1628"
								 style="stroke-width:4" transform="scale(0.36) translate(-139 136) rotate(-45 0 0)"/>
							<path d="M137.5 355 C230.674 287.237 311.196 227.137 396.5 349"
								transform="matrix(1 0 0 1 0 0) scale(0.05)"
								 style="fill:none;stroke-width:40"/>
						</g>
					</g>
	      </g>
		';
		$last_x += 36;
		return $undobuttons;
	}	
	
	function polygonbuttons($strDrawPolygon, $strCutByPolygon){
		global $last_x;
		$polygonbuttons = '
				<g id="pgon" onmousedown="draw_pgon_on();add_polygon();highlightbyid(\'pgon0\');" transform="translate('.$last_x.' 0 )">
		      <rect id="pgon0" onmouseover="show_tooltip(\''.$strDrawPolygon.'\',evt.clientX,evt.clientY)" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
					<g class="navbutton" transform="translate(5 5) scale(0.8)">
						<g transform="translate(9 -8)">
							<path d="M23,12 L16,12 C15.4,12 15,11.6 15,11 L15,10 C15,9.4 15.4,9 16,9 L23,9 C23.6,9 24,9.4 24,10 L24,11 C24,11.6 23.6,12 23,12"/>
							<path d="M18,14 L18,7 C18,6.4 18.4,6 19,6 L20,6 C20.6,6 21,6.4 21,7 L21,14 C21,14.6 20.6,15 20,15 L19.0,15 C18.4,15 18,14.6 18,14"/>
						</g>
						<g transform="translate(-2 2)">
							<path d="M16.25,29 C17.5,29 18.5,28 18.5,26.75 C18.5,25.5 17.5,24.5 16.25,24.5 C15.0,24.5 14.0,25.5 14,26.75 C14,28 15.0,29 16.25,29"/>
							<path d="M14.95,5.5 C16.2,5.5 17.2,4.5 17.2,3.25 C17.2,2.0 16.2,1 14.95,1 C13.7,1 12.7,2.0 12.7,3.2 C12.7,4.5 13.7,5.5 14.95,5.5"/>
							<path d="M8.3,29 C9.5,29 10.5,28 10.5,26.75 C10.5,25.5 9.5,24.5 8.3,24.5 C7.0,24.5 6,25.5 6,26.7 C6,28 7.0,29 8.25,29"/>
							<path d="M3.3,23.2 C4.5,23.2 5.5,22.2 5.5,20.95 C5.5,19.7 4.5,18.7 3.2,18.7 C2.0,18.7 1.0,19.7 1.0,20.9 C1,22.2 2.0,23.2 3.3,23.2"/>
							<path d="M5.3,11.2 C6.5,11.2 7.5,10.2 7.5,8.9 C7.5,7.7 6.5,6.7 5.3,6.7 C4.0,6.7 3,7.7 3,8.9 C3,10.2 4.0,11.2 5.3,11.2"/>
							<path d="M26.7,9.6 C27.9,9.6 28.9,8.6 28.9,7.4 C28.9,6.1 27.9,5.1 26.7,5.1 C25.4,5.1 24.4,6.1 24.4,7.4 C24.4,8.6 25.4,9.6 26.7,9.6"/>
							<path d="M24.6,23.9 C25.9,23.9 26.9,22.9 26.9,21.7 C26.9,20.4 25.9,19.4 24.6,19.4 C23.4,19.4 22.4,20.4 22.4,21.7 C22.4,22.9 23.4,23.9 24.6,23.9"/>
							<path d="M10.3,27.8 L14.2,27.8 L18.5,26.5 L23.4,23.5 L25.9,19.8 L27.3,9.5 L25.1,5.8 L17.2,3 L12.7,3.4 L6.5,7.1 L3.9,10.8 L2.6,18.8 L3.8,23.1 L6.2,25.9 Z M10.3,25.8 L14.2,25.8 L17.4,24.8 L22.4,21.8 L23.9,19.5 L25.4,9.2 L24.4,7.6 L16.5,4.8 L13.7,5.1 L7.5,8.8 L5.9,11.1 L4.6,19.1 L5.3,21.8 L7.7,24.6 Z" style="fill-rule: evenodd;"/>
							<path d="M10.3,25.8 L14.2,25.8 L17.4,24.8 L22.4,21.8 L23.9,19.5 L25.4,9.2 L24.4,7.6 L16.5,4.8 L13.7,5.1 L7.5,8.8 L5.9,11.1 L4.6,19.1 L5.3,21.8 L7.7,24.6 Z" class="navbutton_semifill"/>
						</g>
					</g>
				</g>';
		$last_x += 36;
		$polygonbuttons.= '
				<g id="pgon_subtr" onmousedown="draw_pgon_on();subtr_polygon();highlightbyid(\'pgon_subtr0\');" transform="translate('.$last_x.' 0 )">
		      <rect id="pgon_subtr0" onmouseover="show_tooltip(\''.$strCutByPolygon.'\',evt.clientX,evt.clientY)" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
					<g class="navbutton" transform="translate(5 5) scale(0.8)">
						<g transform="translate(9 -8)">
							<path d="M23,12 L16,12 C15.4,12 15,11.6 15,11 L15,10 C15,9.4 15.4,9 16,9 L23,9 C23.6,9 24,9.4 24,10 L24,11 C24,11.6 23.6,12 23,12"/>
						</g>
						<g transform="translate(-2 2)">
							<path d="M16.25,29 C17.5,29 18.5,28 18.5,26.75 C18.5,25.5 17.5,24.5 16.25,24.5 C15.0,24.5 14.0,25.5 14,26.75 C14,28 15.0,29 16.25,29"/>
							<path d="M14.95,5.5 C16.2,5.5 17.2,4.5 17.2,3.25 C17.2,2.0 16.2,1 14.95,1 C13.7,1 12.7,2.0 12.7,3.2 C12.7,4.5 13.7,5.5 14.95,5.5"/>
							<path d="M8.3,29 C9.5,29 10.5,28 10.5,26.75 C10.5,25.5 9.5,24.5 8.3,24.5 C7.0,24.5 6,25.5 6,26.7 C6,28 7.0,29 8.25,29"/>
							<path d="M3.3,23.2 C4.5,23.2 5.5,22.2 5.5,20.95 C5.5,19.7 4.5,18.7 3.2,18.7 C2.0,18.7 1.0,19.7 1.0,20.9 C1,22.2 2.0,23.2 3.3,23.2"/>
							<path d="M5.3,11.2 C6.5,11.2 7.5,10.2 7.5,8.9 C7.5,7.7 6.5,6.7 5.3,6.7 C4.0,6.7 3,7.7 3,8.9 C3,10.2 4.0,11.2 5.3,11.2"/>
							<path d="M26.7,9.6 C27.9,9.6 28.9,8.6 28.9,7.4 C28.9,6.1 27.9,5.1 26.7,5.1 C25.4,5.1 24.4,6.1 24.4,7.4 C24.4,8.6 25.4,9.6 26.7,9.6"/>
							<path d="M24.6,23.9 C25.9,23.9 26.9,22.9 26.9,21.7 C26.9,20.4 25.9,19.4 24.6,19.4 C23.4,19.4 22.4,20.4 22.4,21.7 C22.4,22.9 23.4,23.9 24.6,23.9"/>
							<path d="M10.3,27.8 L14.2,27.8 L18.5,26.5 L23.4,23.5 L25.9,19.8 L27.3,9.5 L25.1,5.8 L17.2,3 L12.7,3.4 L6.5,7.1 L3.9,10.8 L2.6,18.8 L3.8,23.1 L6.2,25.9 Z M10.3,25.8 L14.2,25.8 L17.4,24.8 L22.4,21.8 L23.9,19.5 L25.4,9.2 L24.4,7.6 L16.5,4.8 L13.7,5.1 L7.5,8.8 L5.9,11.1 L4.6,19.1 L5.3,21.8 L7.7,24.6 Z" style="fill-rule: evenodd;"/>
						</g>
					</g>
				</g>
		';
		$last_x += 36;
		return $polygonbuttons;
	}	
	
	function polygonbuttons2($strSplitPolygon){
		global $last_x;
		$polygonbuttons = '				
				<g id="line" onmousedown="split_geometry();highlightbyid(\'split0\');" transform="translate('.$last_x.' 0 )">
					<rect id="split0" onmouseover="show_tooltip(\''.$strSplitPolygon.'\',evt.clientX,evt.clientY)" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
					<g class="navbutton" transform="translate(9 5) scale(0.8)">
						<g class="navbutton_stroke" transform="translate(-5 -2) scale(1.4)">
							<polygon class="navbutton_semifill" points="252.5,91 177.5,113 106.5,192 128.5,260 116.5,354 127.5,388 173.5,397 282.5,331 394.5,284	379.5,218 378.5,139 357.5,138 260.5,91"
							transform="matrix(1 0 0 1 0 0) scale(0.05)"
							 style="stroke-width:25"/>
							<line x1="380" y1="420" x2="70" y2="80" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="stroke-width:30"/>
						</g>
					</g>
				</g>
				';
		$last_x += 36;
		return $polygonbuttons;
	}	
	
	function gpsbuttons($strSetGPSPosition, $strGPSFollow, $gps_follow){
		global $last_x;
		$gpsbuttons = '
			<g id="gps" onmousedown="set_gps_position();" transform="translate('.$last_x.' 0 )">
        <rect id="gps1" onmouseover="show_tooltip(\''.$strSetGPSPosition.'\',evt.clientX,evt.clientY)" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
				<g class="navbutton" transform="translate(4 8) scale(0.6)">
					<text x="23" y="15" style="text-anchor:middle;font-size:10;font-family:Arial;font-weight:bold">
					GPS</text>
					<circle cx="23" cy="21" r="3"/>
				</g>				
	    </g>';
		$last_x += 36;
		$gpsbuttons.= gps_follow($strGPSFollow, $gps_follow);
		return $gpsbuttons;
	}

	function pointbuttons($strSetPosition){
		global $last_x;
		$pointbuttons = '
				<g id="text" onmousedown="draw_point();highlightbyid(\'text0\');" transform="translate('.$last_x.' 0 )">
	        <rect id="text0" onmouseover="show_tooltip(\''.$strSetPosition.'\',evt.clientX,evt.clientY)" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
					<g class="navbutton" transform="translate(4 4) scale(1)">
						<circle cx="14" cy="12" r="3"/>
						<g transform="scale(1.12)">
							<polygon class="navbutton_stroke navbutton_whitefill" points="178.579,57.7353 164.258,51.2544 178.96,44.515 176.48,49.1628 185.48,49.1628 185.48,53.1628 176.48,53.1628"
								 style="stroke-width:1.7" transform="scale(0.7) translate(-46 -154) rotate(60.992 13.3045 25.4374)"/>
						</g>
					</g>
		    </g>
		';
		$last_x += 36;
		return $pointbuttons;
	}

	function multipointbuttons($strSetPosition){
		global $last_x;
		$pointbuttons = '
				<g id="text" onmousedown="draw_multipoint();highlightbyid(\'text0\');" transform="translate('.$last_x.' 0 )">
	        <rect id="text0" onmouseover="show_tooltip(\''.$strSetPosition.'\',evt.clientX,evt.clientY)" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
					<g class="navbutton" transform="translate(4 4) scale(1)">
						<circle cx="14" cy="12" r="3"/>
						<g transform="scale(1.12)">
							<polygon class="navbutton_stroke navbutton_whitefill" points="178.579,57.7353 164.258,51.2544 178.96,44.515 176.48,49.1628 185.48,49.1628 185.48,53.1628 176.48,53.1628"
								 style="stroke-width:1.7" transform="scale(0.7) translate(-46 -154) rotate(60.992 13.3045 25.4374)"/>
						</g>
					</g>
		    </g>
		';
		$last_x += 36;
		return $pointbuttons;
	}	

	function boxbuttons($strCreateRectangle){
		global $last_x;
		$boxbuttons = '
				<g id="box" onmousedown="draw_box_on();highlightbyid(\'box0\');" transform="translate('.$last_x.' 0)">
	        <rect id="box0" onmouseover="show_tooltip(\'' . $strCreateRectangle . '\',evt.clientX,evt.clientY)" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
					<g class="navbutton navbutton_nofill navbutton_stroke" transform="translate(-3 2) scale(0.9)">
						<g transform="matrix(-1 0 0 1 118 0) scale(0.5)">
							<rect x="170" y="30" width="40" height="14" style="stroke-width:4"/>
						</g>
					</g>
	      </g>
		';
		$last_x += 36;
		return $boxbuttons;
	}

	function linebuttons($strDrawLine, $strDelLine){
		global $last_x;
		$linebuttons = '
				<g id="line" onmousedown="add_line();highlightbyid(\'line0\');" transform="translate('.$last_x.' 0 )">
		      <rect id="line0" onmouseover="show_tooltip(\''.$strDrawLine.'\',evt.clientX,evt.clientY)" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
					<g class="navbutton navbutton_stroke" transform="translate(4 4) scale(1.1)">
						<line	x1="81.5" y1="391" x2="127.5" y2="250" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="stroke-width:35"/>
						<line	x1="127.5" y1="250" x2="310.5" y2="243" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="stroke-width:35"/>
						<line	x1="310.5" y1="243" x2="370.5" y2="103" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="stroke-width:35"/>
					</g>
				</g>';
		$last_x += 36;
		$linebuttons.= '
				<g id="line" onmousedown="delete_lines();highlightbyid(\'del0\');" transform="translate('.$last_x.' 0 )">
		      <rect id="del0" onmouseover="show_tooltip(\''.$strDelLine.'\',evt.clientX,evt.clientY)" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
					<g class="navbutton navbutton_stroke" transform="translate(4 4) scale(1.1)">
						<line	x1="81.5" y1="391" x2="127.5" y2="250" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="stroke-width:35"/>
						<line	x1="127.5" y1="250" x2="310.5" y2="243" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="stroke-width:35"/>
						<line	x1="310.5" y1="243" x2="370.5" y2="103" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="stroke-width:35"/>
						<polygon points="
											425.5,261 
											227.5,93 
											145.5,162  
											335.5,350" 
							transform="matrix(1 0 0 1 0 0) scale(0.05)"
							 style="fill:white;stroke-width:25"/>					
					</g>
				</g>
		';
		$last_x += 36;
		return $linebuttons;
	}
		
	function linebuttons2($strSplitLine, $strReverse, $strParallelLine){
		global $last_x;
		$linebuttons = '				
				<g id="line" onmousedown="split_geometry();highlightbyid(\'split0\');" transform="translate('.$last_x.' 0 )">
		      <rect id="split0" onmouseover="show_tooltip(\''.$strSplitLine.'\',evt.clientX,evt.clientY)" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
					<g class="navbutton navbutton_stroke" transform="translate(4 4) scale(1.1)">
						<line	x1="81.5" y1="391" x2="127.5" y2="250" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="stroke-width:35"/>
						<line	x1="127.5" y1="250" x2="310.5" y2="243" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="stroke-width:35"/>
						<line	x1="310.5" y1="243" x2="370.5" y2="103" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="stroke-width:35"/>
						<line x1="300" y1="340" x2="150" y2="160" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="stroke-width:45"/>
					</g>
				</g>
				';
		$last_x += 36;
		$linebuttons.= '
				<g id="line" onmousedown="reverse_geom();highlightbyid(\'reverse0\');" transform="translate('.$last_x.' 0 )">
		      <rect id="reverse0" onmouseover="show_tooltip(\''.$strReverse.'\',evt.clientX,evt.clientY)" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
					<g class="navbutton navbutton_stroke" transform="translate(4 4) scale(1.1)">
						<g transform="scale(0.9 0.9) translate(8 3) rotate(20 0 0)">
							<line	x1="81.5" y1="391" x2="127.5" y2="250" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="stroke-width:35"/>
							<line	x1="127.5" y1="250" x2="310.5" y2="243" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="stroke-width:35"/>
							<line	x1="310.5" y1="243" x2="370.5" y2="103" transform="matrix(1 0 0 1 0 0) scale(0.05)" style="stroke-width:35"/>
						</g>
						<g transform="translate(-2 -6) rotate(-5 0 0)">
							<polygon points="178.579,57.7353 164.258,51.2544 178.96,44.515 174.48,51.1628"
								 style="stroke-width:2" transform="scale(0.36) translate(-139 136) rotate(-45 0 0)"/>
							<path class="navbutton_nofill" d="M137.5 355 C230.674 287.237 311.196 227.137 430 349"
								transform="matrix(1 0 0 1 0 0) scale(0.05)"
								 style="stroke-width:30"/>
						</g>
					</g>
				</g>
		';
		$last_x += 36;
		$linebuttons.= '
			<g id="parallel_line" transform="translate('.$last_x.' 0)">
				<rect id="parallel_line0" onmouseover="show_tooltip(\'' . $strParallelLine . '\',evt.clientX,evt.clientY)" onmousedown="highlightbyid(\'parallel_line0\');add_parallel_line();hide_tooltip();" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
				<g class="navbutton" transform="translate(4 4) scale(1)">
					<g transform="translate(0 0)">
						<polyline class="navbutton_stroke navbutton_nofill" style="stroke-width:1.7;stroke-dasharray:2,1.5;" points="5.3 24 14 12 27 12" />
					</g>
					<g transform="translate(-2.5 -6)">
						<polyline class="navbutton_stroke navbutton_nofill" style="stroke-width:1.7;" points="3 27 14 12 29 12" />
					</g>
				</g>
			</g>
		';
		$last_x += 36;
		return $linebuttons;
	}

  function flurstquerybuttons(){
  	global $last_x;
    $flurstquerybuttons = '
      <g id="query_add" transform="translate('.$last_x.' 0)">
        <rect id="ppquery1" onmouseover="show_tooltip(\'vorhandene Geometrie hinzuf\u00fcgen\',evt.clientX,evt.clientY)" onmousedown="add_geometry();hide_tooltip();highlightbyid(\'ppquery1\');" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
				<g class="navbutton" transform="translate(5 5) scale(0.8)">
					<g transform="translate(9 -8)">
						<path d="M23,12 L16,12 C15.4,12 15,11.6 15,11 L15,10 C15,9.4 15.4,9 16,9 L23,9 C23.6,9 24,9.4 24,10 L24,11 C24,11.6 23.6,12 23,12"/>
						<path d="M18,14 L18,7 C18,6.4 18.4,6 19,6 L20,6 C20.6,6 21,6.4 21,7 L21,14 C21,14.6 20.6,15 20,15 L19.0,15 C18.4,15 18,14.6 18,14"/>
					</g>
					<g transform="translate(-5 -2) scale(1.4)">
						<polygon class="navbutton_stroke navbutton_semifill" points="252.5,91 177.5,113 106.5,192 128.5,260 116.5,354 127.5,388 173.5,397 282.5,331 394.5,284	379.5,218 378.5,139 357.5,138 260.5,91"
							transform="matrix(1 0 0 1 0 0) scale(0.05)"
							 style="stroke-width:25"/>
						<polygon class="navbutton_stroke navbutton_whitefill" points="178.579,57.7353 164.258,51.2544 178.96,44.515 176.48,49.1628 185.48,49.1628 185.48,53.1628 176.48,53.1628"
								 style="stroke-width:1.7" transform="scale(0.7) translate(-46 -154) rotate(60.992 13.3045 25.4374)"/>
					</g>
				</g>
      </g>';
    $last_x += 36;
    $flurstquerybuttons .= '
		  <g id="query_subtract" transform="translate('.$last_x.' 0)">
        <rect id="ppquery2" onmouseover="show_tooltip(\'mit vorhandener Geometrie ausschneiden\',evt.clientX,evt.clientY)" onmousedown="subtract_geometry();hide_tooltip();highlightbyid(\'ppquery2\');" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
        <g class="navbutton" transform="translate(5 5) scale(0.8)">
					<g transform="translate(9 -8)">
						<path d="M23,12 L16,12 C15.4,12 15,11.6 15,11 L15,10 C15,9.4 15.4,9 16,9 L23,9 C23.6,9 24,9.4 24,10 L24,11 C24,11.6 23.6,12 23,12"/>
					</g>
					<g transform="translate(-5 -2) scale(1.4)">
						<polygon class="navbutton_stroke navbutton_nofill" points="252.5,91 177.5,113 106.5,192 128.5,260 116.5,354 127.5,388 173.5,397 282.5,331 394.5,284	379.5,218 378.5,139 357.5,138 260.5,91"
							transform="matrix(1 0 0 1 0 0) scale(0.05)"
							 style="stroke-width:25"/>
						<polygon class="navbutton_stroke navbutton_whitefill" points="178.579,57.7353 164.258,51.2544 178.96,44.515 176.48,49.1628 185.48,49.1628 185.48,53.1628 176.48,53.1628"
								 style="stroke-width:1.7" transform="scale(0.7) translate(-46 -154) rotate(60.992 13.3045 25.4374)"/>
					</g>
				</g>
      </g>';
		$last_x += 36;
    return $flurstquerybuttons;
  }
  
  function bufferbuttons($strBuffer, $strBufferedLine, $strCircle, $strParallelPolygon){
  	global $last_x;
    $bufferbuttons = '
      <g id="buffer_add" transform="translate('.$last_x.' 0)">
				<rect id="buffer0" onmouseover="show_tooltip(\''.$strBuffer.'\',evt.clientX,evt.clientY)" onmousedown="add_buffer();hide_tooltip();highlightbyid(\'buffer0\');" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
        <g class="navbutton navbutton_stroke" transform="translate(3.5 3) scale(1.1)">
					<polygon class="navbutton_nofill" points="252.5,91 177.5,113 106.5,192 128.5,260 116.5,354 127.5,388 173.5,397 282.5,331 394.5,284	379.5,218 378.5,139 357.5,138 260.5,91"
						transform="translate(-4 -4) scale(0.07)"
						 style="stroke-width:18"/>
					<polygon class="navbutton_semifill" points="252.5,91 177.5,113 106.5,192 128.5,260 116.5,354 127.5,388 173.5,397 282.5,331 394.5,284 379.5,218 378.5,139 357.5,138 260.5,91"
						transform="translate(3 3) scale(0.04)"
						 style="stroke-width:25"/>        
				</g>
      </g>';
		$last_x += 36;
		$bufferbuttons .= '
      <g id="buffer_add_line" transform="translate('.$last_x.' 0)">
        <rect id="buffer1" onmouseover="show_tooltip(\''.$strBufferedLine.'\',evt.clientX,evt.clientY)" onmousedown="add_buffered_line();hide_tooltip();highlightbyid(\'buffer1\');" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
				<g class="navbutton navbutton_semifill navbutton_stroke" transform="translate(5 3) scale(1.1)">
					<polygon points="221 339 212 344 204 351 197 359 192 368 189 378 188 389 189 399 192 410 197 419 322 607 329 615 337 622 346 627 356 630 366 631 377 630 387 627 396 622 404 616 499 525 506 517 511 508 515 498 516 487 515 477 512 466 507 457 501 449 493 442 484 437 474 433 463 432 453 433 442 436 433 441 425 447 376 493 323 414 524 331 533 326 541 319 548 311 553 302 556 292 557 281 556 270 553 260 548 251 541 243 533 236 524 231 514 228 503 227 492 228 482 231 221 339"
						transform="translate(2.5 -17) scale(0.050) rotate(88 197 419)"
						 style="stroke-width:21"/>
					<polyline class="navbutton_nofill" points="503 281 242 389 367 577 462 486" transform="translate(2.5 -17) scale(0.050) rotate(88 197 419)" style="stroke-dasharray:2,2;stroke-width:15"/>
				</g>
      </g>';
			$last_x += 36;			
		$bufferbuttons .= '
      <g id="parallel_polygon" transform="translate('.$last_x.' 0)">
        <rect id="buffer2" onmouseover="show_tooltip(\''.$strParallelPolygon.'\',evt.clientX,evt.clientY)" onmousedown="add_parallel_polygon();hide_tooltip();highlightbyid(\'buffer2\');" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
				<g class="navbutton navbutton_semifill navbutton_stroke" transform="translate(5 3) scale(1.1)">
					<polyline class="navbutton_nofill" points="461 270 692 532" transform="translate(0 -41) scale(0.070) rotate(94 197 419)" style="stroke-dasharray:2,2;stroke-width:20"/>
					<polygon points="574 546 647 483 506 320 433 383 574 546"
						transform="translate(0 -41) scale(0.070) rotate(94 197 419)"
						 style="stroke-width:20"/>
				</g>
      </g>';
		$last_x += 36;
		$bufferbuttons .= '
      <g id="add_circle" transform="translate('.$last_x.' 0)">
        <rect id="buffer3" onmouseover="show_tooltip(\''.$strCircle.'\',evt.clientX,evt.clientY)" onmousedown="add_circle();hide_tooltip();highlightbyid(\'buffer3\');" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
				<g class="navbutton navbutton_semifill navbutton_stroke" transform="translate(5 3) scale(1.1)">
					<circle cx="503" cy="281"	r="53" transform="translate(2.5 -17) scale(0.050) rotate(88 197 419)" style="stroke-width:21"/>
					<circle cx="242" cy="389"	r="53" transform="translate(2.5 -17) scale(0.050) rotate(88 197 419)" style="stroke-width:21"/>
					<circle cx="337" cy="577"	r="53" transform="translate(2.5 -17) scale(0.050) rotate(88 197 419)" style="stroke-width:21"/>
					<circle cx="462" cy="486"	r="53" transform="translate(2.5 -17) scale(0.050) rotate(88 197 419)" style="stroke-width:21"/>
				</g>
      </g>';
			$last_x += 36;
    return $bufferbuttons;
  }
	
	 function special_bufferbuttons($strSpecialBuffer){
  	global $last_x;
    $special_bufferbuttons = '
      <g id="buffer_add" transform="translate('.$last_x.' 0)">
				<rect id="buffer0" onmouseover="show_tooltip(\''.$strSpecialBuffer.'\',evt.clientX,evt.clientY)" onmousedown="add_buffer();hide_tooltip();highlightbyid(\'buffer0\');" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
        <g class="navbutton navbutton_stroke" transform="translate(5 3) scale(1.1)">
					<polygon class="navbutton_nofill" points="252.5,91 177.5,113 106.5,192 128.5,260 116.5,354 127.5,388 173.5,397 282.5,331 394.5,284	379.5,218 378.5,139 357.5,138 260.5,91"
						transform="translate(-4 -4) scale(0.07)"
						 style="stroke-width:18"/>
					<polygon class="navbutton_semifill" points="252.5,91 177.5,113 106.5,192 128.5,260 116.5,354 127.5,388 173.5,397 282.5,331 394.5,284 379.5,218 378.5,139 357.5,138 260.5,91"
						transform="translate(3 3) scale(0.04)"
						 style="stroke-width:25"/>        
				</g>
      </g>';
		$last_x += 36;
    return $special_bufferbuttons;
  }

	function transform_buttons($strMoveGeometry, $strRotateGeometry){
		global $last_x;
		$transform_buttons ='
			<g id="vertex_edit" transform="translate('.$last_x.' 0)">
        <rect id="move1" onmouseover="show_tooltip(\''.$strMoveGeometry.'\',evt.clientX,evt.clientY)" onmousedown="highlightbyid(\'move1\');move_geometry();hide_tooltip();" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
				<g class="navbutton navbutton_stroke" transform="translate(5 5) scale(1.1)">
					<polygon class="navbutton_nofill" points="252.5,91 177.5,113 106.5,192 128.5,260 116.5,354 127.5,388 173.5,397 282.5,331 394.5,284	379.5,218 378.5,139 357.5,138 260.5,91"
						transform="translate(4 4) scale(0.045)"
						 style="stroke-dasharray:23,23;stroke-width:25"/>
					<polygon class="navbutton_semifill" points="252.5,91 177.5,113 106.5,192 128.5,260 116.5,354 127.5,388 173.5,397 282.5,331 394.5,284	379.5,218 378.5,139 357.5,138 260.5,91"
						transform="translate(-2 0) scale(0.045)"
						 style="stroke-width:25"/>
				</g>
      </g>
    ';
		$last_x += 36;
		$transform_buttons .='
			<g id="vertex_edit" transform="translate('.$last_x.' 0)">
        <rect id="rotate1" onmouseover="show_tooltip(\''.$strRotateGeometry.'\',evt.clientX,evt.clientY)" onmousedown="highlightbyid(\'rotate1\');rotate_geometry();hide_tooltip();" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
				<g class="navbutton navbutton_stroke" transform="translate(5 5) scale(1.1)">
					<polygon class="navbutton_semifill" points="252.5,91 177.5,113 106.5,192 128.5,260 116.5,354 127.5,388 173.5,397 282.5,331 394.5,284	379.5,218 378.5,139 357.5,138 260.5,91"
						transform="translate(-2 2) scale(0.045)"
						 style="stroke-width:25"/>
					<g transform="translate(23 -14) rotate(55 0 0)">
						<polygon points="178.579,57.7353 164.258,51.2544 178.96,44.515 174.48,51.1628"
							style="stroke-width:4" transform="scale(0.36) translate(-139 136) rotate(-45 0 0)"/>
						<path d="M137.5 355 C230.674 287.237 311.196 227.137 396.5 349"
								transform="matrix(1 0 0 1 0 0) scale(0.05)"
								style="fill:none;stroke-width:40"/>
					</g>
				</g>
      </g>
    ';
		$last_x += 36;		
    return $transform_buttons;
	}
	
	function vertex_edit_buttons($strCornerPoint){
		global $last_x;
		$vertex_edit_buttons ='
			<g id="vertex_edit" transform="translate('.$last_x.' 0)">
				<rect id="vertex_edit1" onmouseover="show_tooltip(\''.$strCornerPoint.'\',evt.clientX,evt.clientY)" onmousedown="edit_vertices();hide_tooltip();" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
				<g class="navbutton" transform="translate(4 4) scale(1)">
					<g transform="translate(-10.8 -9.5)">
						<path d="M16.25,29 C17.5,29 18.5,28 18.5,26.75 C18.5,25.5 17.5,24.5 16.25,24.5 C15.0,24.5 14.0,25.5 14,26.75 C14,28 15.0,29 16.25,29"/>
						<path d="M26.7,15.6 C27.9,15.6 28.9,14.6 28.9,13.4 C28.9,12.1 27.9,11.1 26.7,11.1 C25.4,11.1 24.4,12.1 24.4,13.4 C24.4,14.6 25.4,15.6 26.7,15.6"/>
						<path d="M24.6,23.9 C25.9,23.9 26.9,22.9 26.9,21.7 C26.9,20.4 25.9,19.4 24.6,19.4 C23.4,19.4 22.4,20.4 22.4,21.7 C22.4,22.9 23.4,23.9 24.6,23.9"/>
						<polyline class="navbutton_stroke navbutton_nofill" style="stroke-width:1.7" points="15 27.5 25 21.5 26.7 13" />
					</g>
					<g transform="scale(1.12)">
						<polygon class="navbutton_stroke navbutton_whitefill" points="178.579,57.7353 164.258,51.2544 178.96,44.515 176.48,49.1628 185.48,49.1628 185.48,53.1628 176.48,53.1628"
							 style="stroke-width:1.7" transform="scale(0.7) translate(-46 -154) rotate(60.992 13.3045 25.4374)"/>
					</g>
				</g>
      </g>
    ';
		$last_x += 36;
    return $vertex_edit_buttons;
	}
	
	function coord_input_buttons(){
		global $last_x;
		$vertex_edit_buttons ='
			<g id="vertex_edit" transform="translate('.$last_x.' 0)">
				<rect id="coord_input1" onmouseover="show_tooltip(\'Koordinate eingeben\',evt.clientX,evt.clientY)" onmousedown="coord_input();hide_tooltip();" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
				<g class="navbutton" transform="translate(4 4) scale(1)">
					<circle cx="178.579" cy="57.7353" r="3" transform="translate(-166 -41)"/>
					<text transform="scale(0.7 0.7)" x="18" y="14" style="text-anchor:middle;font-size:15;font-family:Arial;font-weight:bold">x,y</text>
				</g>
      </g>
    ';
		$last_x += 36;
    return $vertex_edit_buttons;
	}
	
	function ortho_point_buttons(){
		global $last_x;
		$ortho_point_buttons ='
			<g id="ortho_point" transform="translate('.$last_x.' 0)">
				<rect id="ortho_point1" onmouseover="show_tooltip(\'Orthogonalpunktberechnung\',evt.clientX,evt.clientY)" onmousedown="highlightbyid(\'ortho_point1\');ortho_point();hide_tooltip();" x="0" y="0" rx="3" ry="3" fill="url(#LinearGradient)" width="36.5" height="36" class="navbutton_frame"/>
				<g class="navbutton" transform="translate(4 4) scale(1)">
					<g transform="translate(-10.8 -6.5)">
						<circle cx="13" cy="18" r="2.4"/>
						<circle cx="35" cy="20" r="2.4"/>
						<polyline class="navbutton_stroke navbutton_nofill" style="stroke-width:1.7;stroke-dasharray:2,2;" points="12 32 37 8" />
						<polyline class="navbutton_stroke navbutton_nofill" style="stroke-width:1;" points="13 18 19 25" />
						<polyline class="navbutton_stroke navbutton_nofill" style="stroke-width:1;" points="35 20 30.5 14.5" />
					</g>
				</g>
      </g>
    ';
		$last_x += 36;
    return $ortho_point_buttons;
	}	
	
	function measure_buttons($strRuler){
		global $last_x;
		$measure_buttons .= dist($strRuler);
		return $measure_buttons;
	}
?>

<INPUT TYPE="HIDDEN" NAME="CMD" VALUE="">
<INPUT TYPE="HIDDEN" NAME="INPUT_TYPE" VALUE="">
<INPUT TYPE="HIDDEN" NAME="INPUT_COORD" VALUE="">
<input type="hidden" name="imgxy" value="300 300">
<input type="hidden" name="imgbox" value="-1 -1 -1 -1">
<input type="hidden" name="legendtouched" value="0">
<input type="HIDDEN" name="minx" value="<?php echo $this->map->extent->minx; ?>">
<input type="HIDDEN" name="miny" value="<?php echo $this->map->extent->miny; ?>">
<input type="HIDDEN" name="maxx" value="<?php echo $this->map->extent->maxx; ?>">
<input type="HIDDEN" name="maxy" value="<?php echo $this->map->extent->maxy; ?>">
<INPUT TYPE="hidden" NAME="pixelsize" VALUE="<?php echo $pixelsize; ?>">
<input type="hidden" name="pathlength" value="<?php echo $this->formvars['pathlength']; ?>">
<input type="hidden" name="svghelp" id="svghelp">
<input type="hidden" name="width_reduction" value="<? echo $this->formvars['width_reduction']; ?>">
<input type="hidden" name="height_reduction" value="<? echo $this->formvars['height_reduction']; ?>">
<input type="hidden" name="edit_other_object" value="">
