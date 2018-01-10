<?php
#
###################################################################
#                                                                 #
# SVG-Anwendungen & Fachschalen                                   #
#                                                                 #
###################################################################
#
?>
	
	<script language="JavaScript">
		var hasSVGSupport = false;
		var useVBMethod = false;

		if(navigator.appName != 'Microsoft Internet Explorer'){
			hasSVGSupport = true;
		}	
//		if(navigator.mimeTypes != null && navigator.mimeTypes.length > 0){
//			if(navigator.mimeTypes["image/svg-xml"] != null)	hasSVGSupport = true;
//		}
		else{
		  useVBMethod = true;
		}
	</script>

	<script language="VBScript">
		' VB Script method to detect SVG viewer in IE
		' this will not be run by browsers with no support for VB Script
		On Error Resume Next
		If useVBMethod = true Then
    	hasSVGSupport = IsObject(CreateObject("Adobe.SVGCtl"))
		End If
	</script>
	
	<script language="JavaScript">
		if (hasSVGSupport == true) {
		// alles ok, nix machen
		}
		else{
			document.write('Ihr Browser hat keine SVG-Unterst&uuml;tzung. Bitte installieren Sie den <a target="_blank" href="http://www.adobe.com/devnet/svg/adobe-svg-viewer-download-area.html">Adobe SVG Viewer</a> oder verwenden Sie den Firefox Browser.');
		}
	</script>
		   
		   
  <SCRIPT type="text/ecmascript"><!--

	var nbh = new Array();
  function go_cmd(cmd)   {
      document.GUI.CMD.value  = cmd;
      document.GUI.submit();
  }
	
	if(navigator.userAgent.toLowerCase().indexOf('firefox') >= 0){
		var browser = 'firefox';
	}
	else{
		if(navigator.userAgent.toLowerCase().indexOf('chrome') >= 0) var browser = 'chrome';
		else var browser = 'other';
	}
	 
  function get_map_ajax(postdata, code2execute_before, code2execute_after){
		top.startwaiting();
		if(document.GUI.legendtouched.value == 0){
  		svgdoc = document.SVG.getSVGDocument();	
			// nix
			if(browser == 'firefox')var mapimg = svgdoc.getElementById("mapimg2");			
			else var mapimg = svgdoc.getElementById("mapimg");
			var scalebar = document.getElementById("scalebar");
			var refmap = document.getElementById("refmap");
			var scale = document.getElementById("scale");
			var lagebezeichnung = document.getElementById("lagebezeichnung");
			var minx = document.GUI.minx;
			var miny = document.GUI.miny;
			var maxx = document.GUI.maxx;
			var maxy = document.GUI.maxy;			
			var pixelsize = document.GUI.pixelsize;
			var polygon = svgdoc.getElementById("polygon");			
			// nix
			
			input_coord = document.GUI.INPUT_COORD.value;
      cmd = document.GUI.CMD.value;
			
			if(browser != 'firefox'){
				code2execute_before += 'moveback()';
				code2execute_after += 'startup()';
			}
			
  		ahah("index.php", postdata+"&mime_type=map_ajax&INPUT_COORD="+input_coord+"&CMD="+cmd+"&code2execute_before="+code2execute_before+"&code2execute_after="+code2execute_after, 
  		new Array(
				'',
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
  			polygon,
				''
  		), 			 
  		new Array("execute_function", "xlink:href", "src", "src", "setvalue", "sethtml", "setvalue", "setvalue", "setvalue", "setvalue", "setvalue", "points", "execute_function"));
						
  		document.GUI.INPUT_COORD.value = '';
  		document.GUI.CMD.value = '';
  	}
  	else{
  		document.GUI.submit();
  	}
  }
	
	function moveback(){	
		document.getElementById("svghelp").SVGmoveback();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
	}
	
	function checkQueryFields(){
		var selected = false;
		query_fields = document.getElementsByClassName('info-select-field');
		for(var i = 0; i < query_fields.length; i++){
			if(query_fields[i].checked){
				selected = true;
				break;
			}
		}
		if(selected == false)message([{ 'type': 'warning', 'msg': '<? echo $strNoLayer; ?>' }]);
		return selected;
	}
	
  function sendpath(cmd,pathx,pathy)   {
    path  = "";
    switch(cmd) 
    {
     case "zoomin_point":
      path = pathx[0]+","+pathy[0];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "zoomin";
			document.GUI.go.value = "neu Laden";
      get_map_ajax('go=navMap_ajax', '', '');
     break;
     case "zoomout":
      path = pathx[0]+","+pathy[0];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = cmd;
			document.GUI.go.value = "neu Laden";
      get_map_ajax('go=navMap_ajax', '', '');
     break;
     case "zoomin_box":
      path = pathx[0]+","+pathy[0]+";"+pathx[2]+","+pathy[2];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "zoomin";
			document.GUI.go.value = "neu Laden";
      get_map_ajax('go=navMap_ajax', '', '');
     break;
		 case "zoomin_wheel":
      path = pathx[0]+","+pathy[0]+";"+pathx[2]+","+pathy[2];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "zoomin_wheel";
			document.GUI.go.value = "neu Laden";
      get_map_ajax('go=navMap_ajax', '', '');
     break;
     case "recentre":
      path = pathx[0]+","+pathy[0];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = cmd;
			document.GUI.go.value = "neu Laden";
      get_map_ajax('go=navMap_ajax', '', '');
     break;
     case "pquery_point":
			if(!checkQueryFields() || !checkForUnsavedChanges())break;
      path = pathx[0]+","+pathy[0]+";"+pathx[0]+","+pathy[0];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "pquery";
			document.GUI.go.value = "Sachdaten";
      overlay_submit(document.GUI, true);
     break;
     case "pquery_box":
			if(!checkQueryFields() || !checkForUnsavedChanges())break;
      path = pathx[0]+","+pathy[0]+";"+pathx[0]+","+pathy[0];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "pquery";
			document.GUI.go.value = "Sachdaten";
      overlay_submit(document.GUI, true);
     break;
     case "touchquery_point":
			if(!checkQueryFields() || !checkForUnsavedChanges())break;
     	top.document.GUI.searchradius.value = "";
      path = pathx[0]+","+pathy[0]+";"+pathx[0]+","+pathy[0];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "touchquery";
			document.GUI.go.value = "Sachdaten";
      overlay_submit(document.GUI, true);
     break;
     case "touchquery_box":
			if(!checkQueryFields() || !checkForUnsavedChanges())break;
     	top.document.GUI.searchradius.value = "";
      path = pathx[0]+","+pathy[0]+";"+pathx[0]+","+pathy[0];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "touchquery";
			document.GUI.go.value = "Sachdaten";
      overlay_submit(document.GUI, true);
     break;
     case "ppquery_point":
			if(!checkQueryFields() || !checkForUnsavedChanges())break;
      document.GUI.searchradius.value = "";
      path = pathx[0]+","+pathy[0]+";"+pathx[0]+","+pathy[0];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "ppquery";
			document.GUI.go.value = "Sachdaten";
			overlay_submit(document.GUI, true);
     break;
     case "ppquery_box":
			if(!checkQueryFields() || !checkForUnsavedChanges())break;
      top.document.GUI.searchradius.value = "";
      path = pathx[0]+","+pathy[0]+";"+pathx[2]+","+pathy[2];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "ppquery";
			document.GUI.go.value = "Sachdaten";
      overlay_submit(document.GUI, true);
     break;
     case "pquery_polygon":
			if(!checkQueryFields() || !checkForUnsavedChanges())break;
      path = pathx[0]+","+pathy[0]+";"+pathx[2]+","+pathy[2];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "pquery";
			document.GUI.go.value = "Sachdaten";
      overlay_submit(document.GUI, true);
     break;
     case "polygonquery":
			if(!checkQueryFields() || !checkForUnsavedChanges())break;
     	for(i = 0; i < pathx.length-1; i++){
     		path = path+pathx[i]+","+pathy[i]+";";
     	}
     	path = path+pathx[i]+","+pathy[i];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "polygonquery";
			document.GUI.go.value = "Sachdaten";
      overlay_submit(document.GUI, true);
     break;
     default:
      path = pathx[0]+","+pathy[0];
      alert("Keine Bearbeitung moeglich! \nUebergebene Daten: "+cmd+", "+path);
     break;
    }
		document.GUI.go.value = "neu Laden";
		document.GUI.legendtouched.value = 0;		// nach dem Submit kann das legendtouched-flag wieder auf 0 gesetzt werden
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
$fpsvg = fopen(IMAGEPATH.$svgfile,'w') or die('fail: fopen('.$svgfile.')');
chmod(IMAGEPATH.$svgfile, 0666);
$svg='<?xml version="1.0"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
  "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg id="svgmap" zoomAndPan="disable" width="'.$res_x.'" height="'.$res_y.'" onload="init();" onmousemove="mouse_move(evt);top.drag(evt);" onmouseup="top.dragstop(evt)"
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
  var doing = "'.$this->user->rolle->selectedButton.'";
	mouse_down = false;
  var highlighted  = "yellow";
  var cmd   = ""; 
  var data="";
  var x_pos="";
  var y_pos="";
	var get_vertices_loop;
	var gps_follow_cooldown = 0;
	var root = document.documentElement;
	var mousewheelloop = 0;
	var touchx;
	var touchy;
	var start_ctm;
	var pinching = false;
	var pinch_distance = 0;
	var last_x = 0;
	freehand_measuring = false;
	var measured_distance = 0;
	var new_distance = 0,
			dragVectors = [{
					\'x0\': 0,
					\'y0\': 0,
					\'dx\': 0,
					\'dy\': 0,
				}, {
					\'x0\': 0,
					\'y0\': 0,
					\'dx\': 0,
					\'dy\': 0,
				}
			],
			touchPanZoomThreshold = 17 // differences of drag vectors, to distinguish between pan and zoom on touch gestures;
	';

if($this->user->rolle->gps){
	$svg.= '  
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
 	window.setInterval("update_gps_position()", 1000);';
}
$svg .='

function startup(){';
	if($this->user->rolle->gps){
		$svg .='update_gps_position();';
	}
	$svg .='
	if(get_measure_path()){
		redrawPL();
	}
	get_polygon_path();	
	redrawPolygon();
	if(doing == "polygonquery"){polygonarea()};
	set_suchkreis();
	eval(doing+"()");	
  document.getElementById(doing+"0").style.setProperty("fill",highlighted,"");
	pinching = false;
}

function sendpath(cmd, pathx, pathy){
	top.sendpath(cmd, pathx, pathy);
	if(cmd == "polygonquery")deletepolygon();
}

function prevent1(evt){
	if(evt.preventDefault){
		evt.preventDefault();
	}else{ // IE fix
		evt.returnValue = false;
	};
}

function applyZoom(){
	var g = document.getElementById("moveGroup");
	zx = g.getCTM().inverse();
	pathx[0] = Math.round(zx.e);
	pathy[0] = Math.round(zx.f);
	pathx[2] = Math.round(zx.e + resx*zx.a); 
	pathy[2] = Math.round(zx.f + resy*zx.a);
	sendpath("zoomin_wheel", pathx, pathy);
}

function mousewheelchange(evt){
	if(doing == "polygonquery"){
		save_polygon_path();
	}
	if(doing == "measure"){
		save_measure_path();
	}
	deactivate_vertices();
	if(!evt)evt = window.event; // For IE
	if(top.document.GUI.stopnavigation.value == 0){
		window.clearTimeout(mousewheelloop);
		prevent1(evt);
		if(evt.wheelDelta)
			delta = evt.wheelDelta / 3600; // Chrome/Safari
		else if(evt.detail)
			delta = evt.detail / -90; // Mozilla
		var z = 1 + delta*5;
		var p = getEventPoint(evt);
		if(p.x > 0 && p.y > 0){
			zoomTransform(p, null, z, null);
			mousewheelloop = window.setTimeout("applyZoom()", 400);
		}
	}
}

function zoomTransform(p, t, z, ctm) {
	var g = document.getElementById("moveGroup");

	if (t == null) t = { \'dx\': 0, \'dy\': 0 }

	if(ctm == null) ctm = g.getCTM();

	p = p.matrixTransform(ctm.inverse());
	var k = root.createSVGMatrix().translate(p.x, p.y).scale(z).translate(-p.x + t.dx, -p.y + t.dy);
	setCTM(g, ctm.multiply(k)); 
}

/*
* Returns the pinch distance between first and second touch position on page
* Returns 0 if only one touch exists 
*/
function getPinchDistance(evt) {
	return (evt.touches.length == 2 ? (Math.pow(evt.touches[0].pageX - evt.touches[1].pageX, 2) + Math.pow(evt.touches[0].pageY - evt.touches[1].pageY, 2)) : 0)
}

function getTouchPositions(evt) {
	var touchPositions = [{
			\'x\': evt.touches[0].pageX,
			\'y\': evt.touches[0].pageY
		}, {
			\'x\': evt.touches[1].pageX,
			\'y\': evt.touches[1].pageY
		}
	]
	return touchPositions
}

function startDragVectors(touches) {
	dragVectors[0].x0 = touches[0].pageX;
	dragVectors[0].y0 = touches[0].pageY;
	dragVectors[1].x0 = touches[1].pageX;
	dragVectors[1].y0 = touches[1].pageY;
}

function updateDragVectors(touches) {
	dragVectors[0].dx = touches[0].pageX - dragVectors[0].x0;
	dragVectors[0].dy = touches[0].pageY - dragVectors[0].y0;
	dragVectors[1].dx = touches[1].pageX - dragVectors[1].x0;
	dragVectors[1].dy = touches[1].pageY - dragVectors[1].y0;
	return dragVectors;
}

function touchstart(evt){
	prevent1(evt);
	if(top.document.GUI.stopnavigation.value == 0){
		if(evt.touches.length == 1){		// 1 Finger
			touchx = evt.clientX = evt.touches[0].pageX;
			touchy = evt.clientY = evt.touches[0].pageY;
			mousedown(evt);
		}
		else if(evt.touches.length == 2){		// 2 Finger
			var g = document.getElementById("moveGroup");
			pinching = true;
			pinch_distance = getPinchDistance(evt);
			startDragVectors(evt.touches);
			start_ctm = g.getCTM();
		}
	}
}

function touchmove(evt) {
	prevent1(evt);
	if(top.document.GUI.stopnavigation.value == 0){
		if(pinching == false && evt.touches.length == 1){		// 1 Finger
			touchx = evt.clientX = evt.touches[0].pageX;
			touchy = evt.clientY = evt.touches[0].pageY;
			mousemove(evt);
		}
		else if(pinching){
			z = getPinchDistance(evt) / pinch_distance;
			var p = getEventPoint(evt);
			
			if (evt.touches.length == 2) {
				var v = updateDragVectors(evt.touches),
						doing = ((Math.abs((v[1].dx - v[0].dx)) + Math.abs((v[1].dx - v[0].dx))) > touchPanZoomThreshold ? \' zoom\' : \'pan\');

/*				console.log(
					\'v1(\' + v[0].dx + \', \' + v[0].dy + \') \' +
					\'v2(\' + v[1].dx + \', \' + v[1].dy + \') \' +
					\'dv(\' + (v[1].dx - v[0].dx) + \', \' + (v[1].dy - v[0].dy) + \') \' +
					\'s(\' + (Math.abs((v[1].dx - v[0].dx)) + Math.abs((v[1].dy - v[0].dy))) + \')\' +
					\'doing: \' + doing
				);
*/
			}
			if(p.x > 0 && p.y > 0){
				if (doing == \'pan\') {
					t = v[0];
				}
				zoomTransform(p, t, z, start_ctm);
			}
		}
	}
}

function touchend(evt){
	prevent1(evt);
	if(pinching == false){		// 1 Finger
		evt.clientX = touchx;
		evt.clientY = touchy;
		mouseup(evt);
	}
	else if(evt.touches.length == 0 && pinching){
		applyZoom();
		//pinching = false;
	}
}

function setCTM(element, matrix) {
	var s = "matrix(" + matrix.a + "," + matrix.b + "," + matrix.c + "," + matrix.d + "," + matrix.e + "," + matrix.f + ")";
	element.setAttribute("transform", s);
}

function getEventPoint(evt) {
	var p = root.createSVGPoint();
	if (evt.clientX != undefined) {		// Maus: Mausposition
		p.x = evt.clientX;
		p.y = evt.clientY;
	}
	else if (evt.touches[0].pageX != undefined){		// Touch: Mitte zwischen beiden Fingern
		if (evt.touches.length == 2) {
			p.x = evt.touches[0].pageX - ((evt.touches[0].pageX-evt.touches[1].pageX)/2);
			p.y = evt.touches[0].pageY - ((evt.touches[0].pageY-evt.touches[1].pageY)/2);
		}
		else {
			p.x = 0;
			p.y = 0;
		}
	}
	if (top.navigator.userAgent.toLowerCase().indexOf("msie") >= 0){
		p.x = p.x - (top.document.body.clientWidth - resx)/2;
    p.y = p.y - 30;
	}
	return p;
}

function init(){
	startup();
	if (top.browser == "other"){
	}
	else {
		document.getElementById("mapimg2").addEventListener("load", function(evt) { moveback_ff(evt); }, true);
	}
	if (window.addEventListener) {
			window.addEventListener(\'mousewheel\', mousewheelchange, false); // Chrome/Safari//IE9
  		window.addEventListener(\'DOMMouseScroll\', mousewheelchange, false);		//Firefox
			document.getElementById(\'canvas\').addEventListener(\'touchstart\', touchstart, false);		//touchstart
			document.getElementById(\'canvas\').addEventListener(\'touchmove\', touchmove, false);		//touchmove
			document.getElementById(\'canvas\').addEventListener(\'touchend\', touchend, false);		//touchend
			document.getElementById(\'canvas\').addEventListener(\'touchcancel\', prevent, false);		//touchcancel
  }
  else {
		top.document.getElementById("map").onmousewheel = mousewheelchange;		// <=IE8
	}
}

top.document.getElementById("map").SVGstartup = startup;		// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen

top.document.getElementById("svghelp").SVGmoveback = moveback;

function moveback_ff(evt){
	// beim Firefox wird diese Funktion beim onload des Kartenbildes ausgefuehrt
	document.getElementById("mapimg2").setAttribute("style", "display:block");	
	window.setTimeout(\'document.getElementById("moveGroup").setAttribute("transform", "translate(0 0)");document.getElementById("mapimg").setAttribute("xlink:href", document.getElementById("mapimg2").getAttribute("xlink:href"));\', 0);
	// Redlining-Sachen loeschen
	while(child = document.getElementById("redlining").firstChild){
  	document.getElementById("redlining").removeChild(child);
	}
	// Tooltip refreshen
	oldmousex = undefined;
	hidetooltip(evt);
	// Navigation wieder erlauben
	top.stopwaiting();
	window.setTimeout(\'document.getElementById("mapimg2").setAttribute("xlink:href", "")\', 400);
	window.setTimeout(\'document.getElementById("mapimg2").setAttribute("style", "display:none")\', 400);
	startup();
}



function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}



function moveback(evt){
	// bei allen anderen Browsern gibt es kein onload für das Kartenbild, deswegen wird diese Funktion als erstes ausgefuehrt
	document.getElementById("mapimg").setAttribute("xlink:href", "'.dirname($_SERVER['SCRIPT_NAME']).'/'.GRAPHICSPATH.'leer.gif");
	document.getElementById("moveGroup").setAttribute("transform", "translate(0 0)");
	// Redlining-Sachen loeschen
	while(child = document.getElementById("redlining").firstChild){
  	document.getElementById("redlining").removeChild(child);
	}
	// Tooltip refreshen
	oldmousex = undefined;
	hidetooltip(evt);
	// Navigation wieder erlauben
	top.stopwaiting();
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
	top.document.GUI.last_button.value = doing = "recentre";
  document.getElementById("canvas").setAttribute("cursor", "move"); //setAttribute("cursor", "url(#MyMove)");
}

function zoomin(){
	if(doing == "polygonquery"){
		save_polygon_path();
	}
	if(doing == "measure"){
		save_measure_path();
	}
  doing = "zoomin";
	top.document.GUI.last_button.value = doing = "zoomin";
  document.getElementById("canvas").setAttribute("cursor", "crosshair");
}

function zoomout(){
	if(doing == "polygonquery"){
		save_polygon_path();
	}
	if(doing == "measure"){
		save_measure_path();
	}
  doing = "zoomout";
	top.document.GUI.last_button.value = doing = "zoomout";
  document.getElementById("canvas").setAttribute("cursor", "crosshair");
}

function showcoords(){
	doing = "showcoords";
  document.getElementById("canvas").setAttribute("cursor", "crosshair");
}

function ppquery(){
  top.document.GUI.last_button.value = doing = "ppquery";
  document.getElementById("canvas").setAttribute("cursor", "help");
}  

function touchquery(){
	doing = "touchquery";
	top.document.GUI.last_button.value = doing = "touchquery";
	document.getElementById("canvas").setAttribute("cursor", "help");
}

function pquery(){
  doing = "pquery";
	top.document.GUI.last_button.value = doing = "pquery";
  document.getElementById("canvas").setAttribute("cursor", "help");
}

// in pquery() und pquery_prompt() aufgeteilt, da der Promt sonst auch bei jedem reload erscheint   
function pquery_prompt(){     
  top.document.GUI.searchradius.value=prompt("Geben Sie den Suchradius in Meter ein.",top.document.GUI.searchradius.value);
  set_suchkreis();
}

function set_suchkreis(){
  radius = (top.document.GUI.searchradius.value / parseFloat(top.document.GUI.pixelsize.value));
  document.getElementById("suchkreis").setAttribute("r", radius);
}

function polygonquery(){
	if((measuring || polydrawing) && (top.document.GUI.punktfang.checked)){
		remove_vertices();
		request_vertices();
	}
	doing = "polygonquery";
	document.getElementById("canvas").setAttribute("cursor", "help");
	// Wenn im UTM-System gemessen wird, NBH-Datei laden
	if('.$this->user->rolle->epsg_code.' == '.EPSGCODE_ALKIS.')top.ahah("index.php", "go=getNBH", new Array(""), new Array("execute_function"));
	if(top.document.GUI.str_polypathx.value != ""){
		polydrawing = true;
		top.document.GUI.str_polypathx.value = "";
		top.document.GUI.str_polypathy.value = "";
	}
	else{
		deletepolygon();
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
		    
function noMeasuring(){
  measuring = false;
  restart();
}

function measure(){
	if((measuring || polydrawing) && (top.document.GUI.punktfang.checked || (top.document.GUI.orthofang != undefined && top.document.GUI.orthofang.checked))){
		remove_vertices();
		request_vertices();
	}
	options1 = top.document.getElementById("options");
	options1.innerHTML=\'<input type="checkbox" onclick="toggle_vertices()" name="orthofang">&nbsp;Ortho-Fang\';
	// Wenn im UTM-System gemessen wird, NBH-Datei laden
	if('.$this->user->rolle->epsg_code.' == '.EPSGCODE_ALKIS.')top.ahah("index.php", "go=getNBH", new Array(""), new Array("execute_function"));
  doing = "measure";
	if(top.document.GUI.str_pathx.value != ""){
		measuring = true;	
		top.document.GUI.str_pathx.value = "";
		top.document.GUI.str_pathy.value = "";
	}
	else{
		top.document.GUI.measured_distance.value = 0;
		measured_distance = 0;
		new_distance = 0;
		freehand_measuring = false;
  	measuring = false;
  	restart();
	}
  document.getElementById("canvas").setAttribute("cursor", "crosshair");
}

function save_measure_path(){
	var length = pathx.length;
	if(length > 0){
		var str_pathx = pathx_world.join(";");
		var str_pathy = pathy_world.join(";");
		top.document.GUI.str_pathx.value = str_pathx;
		top.document.GUI.str_pathy.value = str_pathy;
		top.document.GUI.measured_distance.value = measured_distance;
	}
}

function get_measure_path(){
	if(top.document.GUI.str_pathx.value != ""){
		pathx = new Array();
		pathy = new Array();
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
			document.getElementById("moveGroup").removeChild(document.getElementById("section"+i));
			showSectionMeasurement(i);
		}
		measured_distance = parseFloat(top.document.GUI.measured_distance.value);
		return true;
	}
	return false;
}

function save_polygon_path(){
	var length = polypathx.length;
	if(length > 0){
		str_polypathx = polypathx.join(";");
		str_polypathy = polypathy.join(";");
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
		polypathx = str_polypathx.split(";");
		polypathy = str_polypathy.split(";");  
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
			laststarty = Math.round((-1*explosion[i+2] - top.document.GUI.miny.value)/parseFloat(top.document.GUI.pixelsize.value));
		}
		if(explosion[i] != "M" && explosion[i] != "Z" && explosion[i] != ""){
			path.push(Math.round((explosion[i] - parseFloat(top.document.GUI.minx.value))/parseFloat(top.document.GUI.pixelsize.value)));
			path.push(Math.round((-1*explosion[i+1] - top.document.GUI.miny.value)/parseFloat(top.document.GUI.pixelsize.value)));
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

function mouse_move(evt){
	top.coords_anzeige(evt, null);
	if(doing == "ppquery"){
		hidetooltip(evt);
	}
}		

function mousedown(evt){
	mouse_down = true;
	tooltipstate = "tooltip_paused";
	cleartooltip();
	if(top.document.GUI.stopnavigation.value == 0){
	  switch(doing){
	   case "previous":
	   break;
	   case "next":
	   break;
	   case "zoomin":
			deactivate_vertices();
	    startPoint(evt);
	   break;
	   case "zoomout":
			deactivate_vertices();
	    selectPoint(evt);
	   break;
	   case "recentre":
			deactivate_vertices();
	    startMove(evt);
	   break;
		case "showcoords":
	    top.show_coords(evt, null);
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
			freehand_measuring = true;
	    if (measuring){
	    	client_x = evt.clientX;
			  client_y = resy - evt.clientY;
			  if(client_x == pathx[pathx.length-1] && client_y == pathy[pathy.length-1]){
					evt.preventDefault();
			  	recentre();		// Streckenmessung bei Doppelklick beenden
			  }
			  else{
	      	addpoint(evt);
					showSectionMeasurement(pathx.length-1);
					measured_distance = new_distance;
	      }
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
	      add_current_point(evt);
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
	mouse_down = false;
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
  newtext.setAttributeNS(null, "style", "fill:rgb(255,0,0);font-size:16px;font-family:Arial;font-weight:bold;");
	newtext.setAttributeNS(null, "transform", "scale(1,-1)");
	newtext.setAttributeNS(null, "x", x);
	newtext.setAttributeNS(null, "y", -y);
	newtext.setAttributeNS(null, "id", "free_text");
	document.documentElement.addEventListener("keypress", texttype, true);
	document.documentElement.addEventListener("keydown", trigger_keypress, true);
	tspan1 = document.createElementNS("http://www.w3.org/2000/svg", "tspan");
	tspan1.appendChild(document.createTextNode("Text"));
	newtext.appendChild(tspan1);
  document.getElementById("redlining").appendChild(newtext);
	return newtext;
}

function trigger_keypress(evt){		// Funktion verhindert in Chrome ein history-back bei backspace
	if(evt.keyCode == 8){		// backspace
		texttype(evt);
	}
}

function texttype(evt){
	if(doing == "addtext" && texttyping){
		var text = current_freetext.lastChild.firstChild.nodeValue;
		var offsetx = current_freetext.getAttribute("x");
		var offsety = 20;
		if(evt.type == "keypress" || evt.type == "keydown"){			
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
	newpoly.setAttributeNS(null, "style", "opacity:0.35;fill:rgb(255,0,0);stroke:rgb(0,0,0);stroke-width:2");
	newpoly.setAttributeNS(null, "id", "free_polygon");	
  document.getElementById("redlining").appendChild(newpoly);
	return newpoly;
}
		
function startpolydraw(evt){
	restart();
  polydrawing = true;
  // neuen punkt abgreifen
	polypathx[0] = evt.clientX*parseFloat(top.document.GUI.pixelsize.value) + parseFloat(top.document.GUI.minx.value);
	polypathy[0] = top.document.GUI.maxy.value - evt.clientY*parseFloat(top.document.GUI.pixelsize.value);
	if(doing == "drawpolygon"){
		current_freepolygon = create_new_freepolygon();
	}
}
		
function addpolypoint(evt){
	// neuen eckpunkt abgreifen	
	client_x = evt.clientX*parseFloat(top.document.GUI.pixelsize.value) + parseFloat(top.document.GUI.minx.value);
	client_y = top.document.GUI.maxy.value - evt.clientY*parseFloat(top.document.GUI.pixelsize.value);
  if(doing == "polygonquery" && client_x == polypathx[polypathx.length-1] && client_y == polypathy[polypathy.length-1]){
  	sendpath(doing,polypathx,polypathy);
  }
  else{
  	polypathx.push(client_x);
  	polypathy.push(client_y);
  }
  redrawPolygon();
  if(doing == "polygonquery"){polygonarea()};
}
	
function deletepolygon(){
	c = polypathx.length;
	for(i = 0; i < c; i++){
  	polypathx.pop();
  	polypathy.pop();
	}
	document.getElementById("polygon").setAttribute("points", "");
	document.getElementById("polygon_label").textContent = "";
}			
		
function redrawPolygon(){
	// punktepfad erstellen
  polypath = "";
	var image_polypathx = new Array();
	var image_polypathy = new Array();
	for(var i = 0; i < polypathx.length; i++){		// in Bild-Koordinaten umrechnen
		image_polypathx[i] = (polypathx[i] - parseFloat(top.document.GUI.minx.value))/parseFloat(top.document.GUI.pixelsize.value);
		image_polypathy[i] = (polypathy[i] - parseFloat(top.document.GUI.miny.value))/parseFloat(top.document.GUI.pixelsize.value);
	}
  for(var i = 0; i < image_polypathx.length; ++i){
  	polypath = polypath+" "+image_polypathx[i]+","+image_polypathy[i];
	}	
	if(image_polypathx.length > 0){
		polypath = polypath+" "+image_polypathx[0]+","+image_polypathy[0];
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

		
function polygonarea(){
  // Flaecheninhalt eines Polygons nach Gauss
  var area = 0,parts = 0;
	if(polypathx.length > 2){
		for(var j = 1; j < polypathx.length-1; ++j){
	 		parts 	= parts + (polypathx[j]*(polypathy[j+1]-polypathy[j-1]));
		}
		parts = parts + (polypathx[polypathx.length-1]*(polypathy[0]-polypathy[polypathx.length-2])) + (polypathx[0]*(polypathy[1]-polypathy[polypathx.length-1]));
		area	= 0.5 * Math.sqrt(parts*parts);
		polypathy2 = polypathy.slice(0);		// copy
		polypathy2.pop();										// remove last vertex
		k = calculate_reduction(polypathx, polypathy2);
		area = area / (k * k);	
		area = top.format_number(area, false, true, false);
		label = document.getElementById("polygon_label");
		var bbox = document.getElementById("polygon").getBBox();
		label.setAttribute("x", Math.floor(bbox.x + bbox.width/2.0) - 50);
		label.setAttribute("y", -1 * (Math.floor(bbox.y + bbox.height/2.0)));		
		label.textContent = "Fl"+unescape("%E4")+"cheninhalt: "+area+" m"+unescape("%B2")+" "+unescape("%A0");
		return;
	}
}

//-------------------------------------------------------
		

// ----------------------------strecke messen---------------------------------

top.document.getElementById("vertices").SVGtoggle_vertices = toggle_vertices;		// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen

top.document.getElementById("vertices").SVGshow_vertices = show_vertices;		// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen

function toggle_vertices(){
	remove_vertices();
	if(top.document.GUI.punktfang.checked || (top.document.GUI.orthofang != undefined && top.document.GUI.orthofang.checked)){
		request_vertices();
	}
}

function deactivate_vertices(){
	top.document.GUI.punktfang.checked = false;
	remove_vertices();
}

function request_vertices(){
	top.ahah("index.php", "go=getSVG_vertices&scale="+top.document.getElementById("scale").value, new Array(top.document.GUI.vertices, ""), new Array("setvalue", "execute_function"));
}

function show_vertices(){
	remove_vertices();
	if(top.document.GUI.vertices.value != ""){
		var parent = document.getElementById("vertices");
		var last_x;
		var last_y;
		circle = new Array();
		circle2 = new Array();
		line = new Array();
		var start_vertex = "";
		image_coords = new Array();
		kreis1 = document.getElementById("kreis");
		linie1 = document.getElementById("linie");
		vertex_string = top.document.GUI.vertices.value+"";
		top.document.GUI.vertices.value = "";
		vertices = vertex_string.split("|");
		for(i = 0; i < vertices.length-1; i++){
			if(vertices[i] == " "){		// das ist der Trenner zwischen Geometrien
				start_vertex = "";
			}
			else{
				coords = vertices[i].split(" ");
				image_coords[0] = Math.round((coords[0] - parseFloat(top.document.GUI.minx.value))/parseFloat(top.document.GUI.pixelsize.value));
				image_coords[1] = Math.round((coords[1] - top.document.GUI.miny.value)/parseFloat(top.document.GUI.pixelsize.value));
				if(top.document.GUI.punktfang.checked && start_vertex != vertices[i]){		// damit bei Polygonen der gleiche Anfangs- und Endpunkt nicht zweimal hinzugefuegt wird
					circ = kreis1.cloneNode(true);
					circ.setAttribute("x", coords[0]);
					circ.setAttribute("y", coords[1]);
					circ.setAttribute("cx", image_coords[0]);
					circ.setAttribute("cy", image_coords[1]);
					circ.setAttribute("style","fill: #FF0000");
					circ.setAttribute("id", "vertex_"+i);
					circle.push(circ);
				}
				if(top.document.GUI.orthofang != undefined && top.document.GUI.orthofang.checked && start_vertex != ""){
					// Zwischenlinien
					line[i] = linie1.cloneNode(true);
					line[i].setAttribute("wx1", coords[0]);
					line[i].setAttribute("wy1", coords[1]);
					line[i].setAttribute("wx2", last_x);
					line[i].setAttribute("wy2", last_y);
					line[i].setAttribute("x1", image_coords[0]);
					line[i].setAttribute("y1", image_coords[1]);
					line[i].setAttribute("x2", last_image_x);
					line[i].setAttribute("y2", last_image_y);
					line[i].setAttribute("style","stroke: #FFFFFF");
					line[i].setAttribute("opacity", "0.01");
					line[i].setAttribute("id", "line_new_"+i);
					parent.appendChild(line[i]);
					// Zwischenpunkte
					circle2[i] = kreis1.cloneNode(true);
					circle2[i].setAttribute("cx", -5000);
					circle2[i].setAttribute("cy", -5000);
					circle2[i].setAttribute("style","fill: #00FF00");
					circle2[i].setAttribute("id", "vertex_new_"+i);
					parent.appendChild(circle2[i]);
				}
				last_x = coords[0];
				last_y = coords[1];
				last_image_x = image_coords[0];
				last_image_y = image_coords[1];
				if(start_vertex == "")start_vertex = vertices[i];
			}
		}
		for(i = 0; i < circle.length; i++){
			parent.appendChild(circle[i]);		// erst jetzt, damit die Punkte ueber den Linien liegen
		}
	}
}

function remove_vertices(){
	var parent = document.getElementById("vertices");
	var count = parent.childNodes.length;
	for(i = 0; i < count-4; i++){
		parent.removeChild(parent.lastChild);
	}
}

function activate_vertex(evt){
	vertex = evt.target;
	vertex.setAttribute("opacity", "1");
	coordx = vertex.getAttribute("x");
	coordy = vertex.getAttribute("y");
	image_coordx = vertex.getAttribute("cx");
	image_coordy = vertex.getAttribute("cy");
	if(doing == "measure" && measuring){
		pathx.push(image_coordx);
		pathy.push(image_coordy);
		pathx_world.push(coordx);
		pathy_world.push(coordy);
		showMeasurement(evt);
		redrawPL();
		deletelast(evt);
	}
	if(doing == "pquery"){
		document.getElementById("suchkreis").setAttribute("cx", image_coordx);
		document.getElementById("suchkreis").setAttribute("cy", image_coordy);
	}
	//if(top.document.GUI.runningcoords != undefined)top.document.GUI.runningcoords.value = top.format_number(coordx, false, false, false) + " / " + top.format_number(coordy, false, false, false); 
	top.document.GUI.activated_vertex.value = vertex.getAttribute("id");
	top.coords_anzeige(evt, vertex);
}

function activate_line(evt){
	if(doing == "measure"){
		last_x = pathx_world[pathx_world.length-1];
		last_y = pathy_world[pathy_world.length-1];
		if(last_x != undefined){
			line = evt.target;
			vertex_id_string = line.getAttribute("id");
			vertex_id = vertex_id_string.split("_");
			// Lotfusspunkt berechnen
			p1x = parseFloat(line.getAttribute("wx1"));
			p1y = parseFloat(line.getAttribute("wy1"));
			p2x = parseFloat(line.getAttribute("wx2"));
			p2y = parseFloat(line.getAttribute("wy2"));
			ax = p2x - p1x;
			ay = p2y - p1y;
			bx = last_x - p1x;
			by = last_y - p1y;
			c = ax*ax + ay*ay;
			d = bx*ax + by*ay;
			e = d/c;
			x = p1x + e*ax;
			y = p1y + e*ay;
			image_x = Math.round((x - parseFloat(top.document.GUI.minx.value))/parseFloat(top.document.GUI.pixelsize.value));
			image_y = Math.round((y - top.document.GUI.miny.value)/parseFloat(top.document.GUI.pixelsize.value));
			// Position des Punktes auf der Linie setzen
			vertex = document.getElementById("vertex_new_"+vertex_id[2]);
			vertex.setAttribute("x", x);
			vertex.setAttribute("y", y);
			vertex.setAttribute("cx", image_x);
			vertex.setAttribute("cy", image_y);
			vertex.setAttribute("opacity", "0.3");
		}
	}
}

function deactivate_vertex(evt){
	vertex = evt.target;
	if(vertex.getAttribute("opacity") != "0.8")vertex.setAttribute("opacity", "0.1");
	top.document.GUI.activated_vertex.value = 0;
}

function add_vertex(evt){
	vertex = evt.target;
	var imgx = vertex.getAttribute("cx");
	var imgy = vertex.getAttribute("cy");
	var worldx = vertex.getAttribute("x");
	var worldy = vertex.getAttribute("y");
	if(doing == "measure"){
		if(!measuring){
			restart();	
			measuring = true;
		}
		pathx.push(imgx);
		pathy.push(imgy);
		pathx_world.push(parseFloat(worldx));
		pathy_world.push(parseFloat(worldy));		
		if(new_distance > 0){
			showSectionMeasurement(pathx.length-1);
			measured_distance = new_distance;
			showMeasurement(evt);
		}
	  redrawPL();
		vertex.setAttribute("opacity", "0.8");
	}
	if(doing == "polygonquery"){
		if(!polydrawing){
			restart();
			polydrawing = true;
		}
  	polypathx.push(parseFloat(worldx));
  	polypathy.push(parseFloat(worldy));
		redrawPolygon();
		polygonarea();
		vertex.setAttribute("opacity", "0.8");
	}
	if(doing == "pquery" || doing == "ppquery"){
		evt.clientX = imgx;
		evt.clientY = imgy;
		mousedown(evt);
		mouseup(evt);
	}
	if(doing == "showcoords"){
		top.show_coords(evt, vertex);
	}
}

function startMeasure(evt) {
  restart();
  measuring = true;
  // neuen punkt abgreifen
	pathx[0] = evt.clientX;
	pathy[0] = resy - evt.clientY;
	pathx_world[0] = top.format_number(evt.clientX*parseFloat(top.document.GUI.pixelsize.value) + parseFloat(top.document.GUI.minx.value), false, true, false);
	pathy_world[0] = top.format_number(top.document.GUI.maxy.value - evt.clientY*parseFloat(top.document.GUI.pixelsize.value), false, true, false);
}

function add_current_point(evt){
	addpoint(evt);
  showMeasurement(evt);
  deletelast(evt);
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

function showSectionMeasurement(j){
	section_distance = calculate_distance(pathx_world[j-1], pathy_world[j-1], pathx_world[j], pathy_world[j]);
	section_distance = top.format_number(section_distance, false, freehand_measuring, true);
  output = section_distance+" m";
	mittex = pathx[j-1] - ((pathx[j-1] - pathx[j]) / 2);
	mittey = pathy[j-1] - ((pathy[j-1] - pathy[j]) / 2);	
  show_tooltip(output, mittex-10, resy-mittey-10);
	section_box = document.getElementById("tooltip_group").cloneNode(true);
	section_box.setAttribute("id", "section"+j);
	section_box.setAttribute("visibility", "visible");
	section_box.setAttribute("opacity", "0.9");
	section_rect = section_box.childNodes[1];		// 1, weil zwischen den eigentlichen Nodes noch Text steht (wahrscheinlich die Zeilenumbrueche)
	section_text = section_box.childNodes[3];		// 3, weil zwischen den eigentlichen Nodes noch Text steht (wahrscheinlich die Zeilenumbrueche)
	section_rect.setAttribute("id", "");
	section_text.setAttribute("id", "");
	document.getElementById("moveGroup").appendChild(section_box);
}

function showMeasurement(evt){
  var track = 0, output = "";
	j = pathx_world.length-1;
  new_distance = measured_distance + calculate_distance(pathx_world[j-1], pathy_world[j-1], pathx_world[j], pathy_world[j]);
  track = top.format_number(new_distance, false, freehand_measuring, true);
  output = "gesamt: "+track+" m";
  show_tooltip(output, evt.clientX, evt.clientY);
}

function addpoint(evt){
  // neuen eckpunkt abgreifen
  client_x = evt.clientX;
	client_y = resy - evt.clientY;
	pathx.push(client_x);
	pathy.push(client_y);
	pathx_world.push(top.format_number(evt.clientX*parseFloat(top.document.GUI.pixelsize.value) + parseFloat(top.document.GUI.minx.value), false, true, false));
	pathy_world.push(top.format_number(top.document.GUI.maxy.value - evt.clientY*parseFloat(top.document.GUI.pixelsize.value), false, true, false));
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
		if(document.getElementById("section"+i) != undefined)document.getElementById("moveGroup").removeChild(document.getElementById("section"+i));
	}
	deletepolygon();
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
  for(var i = 0; i < alle; ++i){
    pathx.pop();
    pathy.pop();
  }
	alle = boxx.length;
  for(var i = 0; i < alle; ++i){
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
	deactivate_vertices();
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
	if(document.getElementById("coords02") != undefined){document.getElementById("coords02").style.setProperty("fill","ghostwhite","");}
  evt.target.style.setProperty("fill",highlighted,"");
  document.getElementById("suchkreis").setAttribute("cx", -10000);
	if(top.document.GUI.orthofang != undefined){
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
			<text x="-1000" y="-1000" id="polygon_label" transform="scale(1, -1)" style="text-anchor:start;fill:rgb(0,0,0);stroke:none;font-size:12px;font-family:Arial;font-weight:bold"></text>
			<path d="" id="highlight" style="fill:none;stroke:blue;stroke-width:2"/>
      <polyline points="" id="polyline" style="fill:none;stroke-dasharray:2,2;stroke:black;stroke-width:4"/>
      <circle id="suchkreis" cx="-100" cy="-100" r="'.$radius.'" style="fill-opacity:0.25;fill:yellow;stroke:grey;stroke-width:2"/>
			<g id="redlining">
			</g>';
if(true){
	 $svg.=' <use id="gps_position" xlink:href="#crosshair_red" x="-100" y="-100"/>';
}
$svg.='
    </g>
  </g>
	<g id="mapimg2_group">
  	<image id="mapimg2" xlink:href="" height="100%" width="100%" y="0" x="0" style="display:none"/>
  </g>
	
  <rect id="canvas" cursor="crosshair" onmousedown="mousedown(evt)" onmousemove="mousemove(evt);" onmouseup="mouseup(evt);" width="100%" height="100%" opacity="0"/>
		<g id="vertices" transform="translate(0,'.$res_y.') scale(1,-1)">
			<circle id="kreis" cx="-500" cy="-500" r="7" opacity="0.1" onmouseover="activate_vertex(evt)" onmouseout="deactivate_vertex(evt)" onmousedown="add_vertex(evt)" />
			<line stroke="#111" stroke-width="14" id="linie" x1="-5000" y1="-5000" x2="-5001" y2="-5001" opacity="0.8" onmouseover="activate_line(evt)" onmousemove="activate_line(evt)" />
		</g>
    <g id="buttons" onmouseout="hide_tooltip()" onmousemove="get_bbox();" onmousedown="hide_tooltip()" cursor="pointer" transform="scale(1.1)">
'.$SVGvars_mainnavbuttons.'
    </g>
		<g id="tooltipgroup" onmouseover="prevent=1;" onmouseout="prevent=0;">
    	<rect id="frame" width="0" height="20" rx="5" ry="5" style="fill-opacity:0.8;fill:rgb(255,255,215);stroke:rgb(0,0,0);stroke-width:1.5"/>
    	<text id="querytooltip" x="100" y="100" style="text-anchor:start;fill:rgb(0,0,0);stroke:none;font-size:10px;font-family:Arial;font-weight:bold"></text>
    	<text id="link0" cursor="pointer" onmousedown="top.document.body.style.cursor=\'pointer\';" onmousemove="top.document.body.style.cursor=\'pointer\';" style="text-anchor:start;fill:rgb(0,0,200);stroke:none;font-size:10px;font-family:Arial;font-weight:bold"></text>
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

		
