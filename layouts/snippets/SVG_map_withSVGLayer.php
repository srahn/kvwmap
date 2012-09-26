<?php
#
###################################################################
#                                                                 #
# SVG-Anwendungen & Fachschalen                                   #
#                                                                 #
###################################################################
# Kontakt:                                                        #
# kvwmap@svgxpert.de , hauke.christoph@uni-rostock.de             #
###################################################################
#

	#############################################
	#
	# Hier mal zum Testen das Einbinden eines SVG-Layers
	#
	# Wie stelle ich mir das in Zukunft vor?
	# Ein SVG-Layer wird als ganz normaler Layer in mysql eingetragen
	# mit folgenden Ausnahmen/Besonderheiten:
	#  - es wird eine neue Spalte eingeführt, die den Layer als SVG-Layer kennzeichnet
	#    beim einlesen der Layer kann dann unterschieden werden, welche Layer SVG-Layer sind
	#  - Wenn ein Layer ein SVG-Layer ist, wird das Data-feld im MapObjekt mit einem dummy Eintrag versehen
	#    der dafür sorgt, dass der Layer zwar gezeichnet wird, aber nicht zu sehen ist, damit
	#    der Layer in der Legende erscheint. Irgend eine leere Abfrage auf postgis, dabei muss der Datentyp
	#    Punkt, Linie und Fläche unterschieden werden. Also jeweils eine dummy-Abfrage 
	#    Der Layer muss für UMN also nur existieren, damit die Legende gezeichnet wird und er an
	#    und ausgeschaltet werden kann.
	#  - eine weitere neue Spalte definiert den SVG-Datentyp, weil die Typen von Mapserver ja nicht reichen
	#    hier kann dann nach belieben erweitert werden, z.B. Ellipse
	#  - in der Spalte Data steht die Abfrage, die für die Ausgabe in SVG benötigt wird.
	#  Beim laden der Layer in kvwmap.php in Funktion loadMap werden zusätzlich zum laden der normalen Layer
	#  Alle Informationen für die SVG-Layer abgefragt, und zwar
	# !! nur für den jeweils aktuellen Kartenausschnitt !!
	# Im Ergebnis dieser Abfrage gibt es dann nicht nur einen layerset, sondern auch noch einen SVGlayerset
	# dieser Layerset kann dann in seiner Reihenfolge in SVG gezeichnet werden
	#
	#####################################
	
	# zum testen des Zeichnens der SVG Elemente wird hier erstmal ein SVGlayerset generiert
	# es werden Fehlerellipsen gelesen
	$festpunkte=new Festpunkte($this->pgdatabase,'');
	$ret=$festpunkte=getFehlerellipsen($pkz,$minx,$maxx,$miny,$maxy,$minmfge,$maxmfge,$ls,$mina,$maxa,$minb,$maxb);
	if ($ret[0]) {
		echo 'Fehler beim lesen der Fehlerellipsen:<br>'.$ret[1];
	}
	else {
		# zuweisen der Zeichungselemente aus postgres
		$SVGlayerset[0]=$ret[1];
		# zuweisen des Datentyps
		$SVGlayerset[0]['Datentyp']='ellipse';
	}
	# hier liegt dann also ein layer für fehlerellipsen in Form eines Arrays bereit.
	# und kann gezeichnet werden
	# in den Feldern rw und hw liegen die Koordinaten, in phi der Winkel der Ellipse und in a und b die Halbachsen.
	# z.B. $SVGlayerset[0][0]['rw'] ist der Rechtswert der ersten Ellipse im ersten (hier gibt es nur einen) Layer

	$SVGmap="";
	for ($i = 0; $i < count($SVGlayerset[0]); $i++)	{	
		switch($SVGlayerset[0]['Datentyp']) {
			case 'ellipse' : {
				$SVGmap .= $SVGmap.include(LAYOUTPATH.'SVGmap_ellipse.php');
			}
			default : {
			}
		}
	}
?>
  <SCRIPT type="text/ecmascript"><!--

  function Full_Extent()   {
      document.GUI.CMD.value  = "Full_Extent";
      document.GUI.submit();
  }

  function sendpath(cmd,pathx,pathy)   {
    path  = "";
    switch(cmd) 
    {
     case "zoomin_point":
      path = pathx[0]+","+pathy[0];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "zoomin";
      document.GUI.submit();
     break;
     case "zoomout":
      path = pathx[0]+","+pathy[0];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = cmd;
      document.GUI.submit();
     break;
     case "zoomin_box":
      path = pathx[0]+","+pathy[0]+";"+pathx[2]+","+pathy[2];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "zoomin";
      document.GUI.submit();
     break;
     case "recentre":
      path = pathx[0]+","+pathy[0];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = cmd;
      document.GUI.submit();
     break;
     case "pquery_point":
      path = pathx[0]+","+pathy[0]+";"+pathx[0]+","+pathy[0];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "pquery";
      document.GUI.submit();
     break;
     case "pquery_box":
      path = pathx[0]+","+pathy[0]+";"+pathx[2]+","+pathy[2];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "pquery";
      document.GUI.submit();
     break;
     case "pquery_polygon":
      path = pathx[0]+","+pathy[0]+";"+pathx[2]+","+pathy[2];
      document.GUI.INPUT_COORD.value  = path;
      document.GUI.CMD.value          = "pquery";
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
	$svgfile  = 'SVG_map.svg';
	include(LAYOUTPATH.'snippets/SVGvars_mainnavbuttons.php'); 	# zuweisen von: $SVGvars_mainnavbuttons
	include(LAYOUTPATH.'snippets/SVGvars_defs.php'); 						# zuweisen von: $SVGvars_defs 
	include(LAYOUTPATH.'snippets/SVGvars_coordscript.php'); 		# zuweisen von: $SVGvars_coordscript
	include(LAYOUTPATH.'snippets/SVGvars_tooltipscript.php');		# zuweisen von: $SVGvars_tooltipscript 
	include(LAYOUTPATH.'snippets/SVGvars_tooltipblank.php');		# zuweisen von: $SVGvars_tooltipblank 
	$bg_pic   = $this->img['hauptkarte'];
	$res_x    = $this->map->width;
	$res_y    = $this->map->height;
	$res_xm   = $this->map->width/2;
	$res_ym   = $this->map->height/2;
#	$dx       = $this->user->rolle->oGeorefExt->maxx-$this->user->rolle->oGeorefExt->minx;
#	$dy       = $this->user->rolle->oGeorefExt->maxy-$this->user->rolle->oGeorefExt->miny;
	$dx       = $this->map->extent->maxx-$this->map->extent->minx;
	$dy       = $this->map->extent->maxy-$this->map->extent->miny;
	$scale    = ($dx/$res_x+$dy/$res_y)/2;
	
#
# Zusammenstellen der SVG  
#
$fpsvg = fopen(IMAGEPATH.$svgfile,w) or die('fail: fopen('.$svgfile.')');
chmod(IMAGEPATH.$svgfile, 0666);
$svg='<?xml version="1.0"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
  "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg zoomAndPan="disable" width="'.$res_x.'" height="'.$res_y.'" onload="startup()" onmousemove="coords(evt)" 
  xmlns="http://www.w3.org/2000/svg" version="1.1"
  xmlns:xlink="http://www.w3.org/1999/xlink">
 
<script type="text/ecmascript"><![CDATA[ 

	var minx  = '.$this->user->rolle->oGeorefExt->minx.';
	var maxx  = '.$this->user->rolle->oGeorefExt->maxx.';
	var miny  = '.$this->user->rolle->oGeorefExt->miny.';
	var maxy  = '.$this->user->rolle->oGeorefExt->maxy.';
	var resx  = '.$res_x.';
	var resy  = '.$res_y.';
	var resx_m  = '.$res_xm.';
	var resy_m  = '.$res_ym.';
	var scale = '.$scale.';
	var pathx = new Array();
	var pathy = new Array();
	var boxx 	= new Array();
	var boxy 	= new Array();
	var dragging  = false; 
	var dragdone  = false; 
	var measuring  = false; 
	moving  = false;
	moved  = false;
	var doing = "'.$this->user->rolle->getSelectedButton().'";
	var highlighted  = "yellow";
	var cmd   = ""; 
	var data="";
	var x_pos="";
	var y_pos="";

function startup(){
	redraw();
	'.$this->user->rolle->getSelectedButton().'();
	document.getElementById("'.$this->user->rolle->getSelectedButton().'0").style.setProperty("fill",highlighted,"");
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
  document.getElementById("canvas").setAttribute("cursor", "move"); //setAttribute("cursor", "url(#MyMove)");
}

function pquery(){
  doing = "pquery";
  document.getElementById("canvas").setAttribute("cursor", "help");
}

function noMeasuring(){
 	measuring = false;
 	restart();
}

function measure(){
  doing = "measure";
 	measuring = false;
 	restart();
  document.getElementById("canvas").setAttribute("cursor", "crosshair");
}

// -------------------------mausinteraktionen auf canvas------------------------------
// id="canvas" onmousedown="canvas(evt)" onmousemove="hide_tooltip();movePoint(evt);moveVector(evt)" onmouseup="endPoint(evt);endMove(evt)" width="100%" height="100%" opacity="0"/>
// function canvas(evt){

function mousedown(evt){
//	alert(doing);
  switch(doing) 
  {
   case "zoomin":
    startPoint(evt);
   break;
   case "zoomout":
    selectPoint(evt);
   break;
   case "recentre":
    startMove(evt);
   break;
   case "pquery":
    startPoint(evt);
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

function mousemove(evt){
  switch(doing) 
  {
   case "measure":
			if (measuring){
				showMeasurement(evt);
			}
			else {
			show_tooltip(\'Startpunkt setzen\',evt.clientX,evt.clientY)
			}
   break;
   default:
	 	hide_tooltip();
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
   default:
	 	hide_tooltip();
   	endPoint(evt);
   	endMove(evt);
   break;
  }
}

// ----------------------------strecke messen---------------------------------
function startMeasure(evt) {
	restart();
	measuring = true;
  // neuen punkt abgreifen
  pathx[0] = evt.clientX;
  pathy[0] = resy - evt.clientY;
}

function showMeasurement(evt){
	addpoint(evt);
	
  var track = 0,parts = 0, output = "";
		for(var j = 0; j < pathx.length-1; ++j)
 		{
	 		parts = parts + Math.sqrt(((pathx[j]-pathx[j+1])*(pathx[j]-pathx[j+1]))+((pathy[j]-pathy[j+1])*(pathy[j]-pathy[j+1])));
		}
	track	= Math.round((parts*scale)*100)/100;
	output = "Strecke: "+track+" m";
	show_tooltip(output,evt.clientX,evt.clientY);

	deletelast(evt);
}

//function coords(evt) {
//  coorx = evt.clientX*scale + minx;
//  coory = maxy - evt.clientY*scale;
//  window.status = "R:" + Math.round(coorx*100)/100 + " / H:" + Math.round(coory*100)/100;
//}


function addpoint(evt) {
  // neuen eckpunkt abgreifen
  client_x = evt.clientX;
  client_y = resy - evt.clientY;
  pathx.push(client_x);
  pathy.push(client_y);
	redrawPL();
}

function deletelast(evt) {
	pathx.pop();
	pathy.pop();

//	redrawPL();
}

function restart()
{
	var alle = pathx.length;
	for(var i = 0; i < alle; ++i)
	 {
	  pathx.pop();
	  pathy.pop();
	 }
	redrawPL();
}

function redrawPL() 
{
  // punktepfad erstellen
  path = "";
  for(var i = 0; i < pathx.length; ++i)
   {
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
  top.sendpath(cmd,pathx,pathy);
}

// ----------------------------box aufziehen---------------------------------
function startPoint(evt) {
  dragging  = true;
  var alle = pathx.length;
  for(var i = 0; i < alle; ++i)
   {
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
  if (!dragdone){ 
  	cmd  = cmd+"_point";}
  	else {
  	cmd  = cmd+"_box";

	  // Reihenfolge pruefen
	  checkOrder(cmd,boxx,boxy);
	}
  dragging  = false;
  dragdone  = false;
  // hiddenformvars aktualisieren
  top.sendpath(cmd,boxx,boxy);
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

function moveMap() 
{
  // transformation erstellen
  move_x = pathx[1]-pathx[0];
  move_y = pathy[1]-pathy[0];
  path = "translate("+move_x+" "+move_y+")";

  // kartenausschnitt verschieben
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
  top.sendpath(cmd,pathx,pathy);
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
function highlight(evt)
{
//  document.getElementById("zoomin0").style.removeProperty("fill");
//  document.getElementById("ppquery0").style.setProperty("fill","ghostwhite","");
  document.getElementById("dist0").style.setProperty("fill","ghostwhite","");
  document.getElementById("zoomin0").style.setProperty("fill","ghostwhite","");
  document.getElementById("zoomout0").style.setProperty("fill","ghostwhite","");
  document.getElementById("recentre0").style.setProperty("fill","ghostwhite","");
  document.getElementById("pquery0").style.setProperty("fill","ghostwhite","");
  evt.target.style.setProperty("fill",highlighted,"");
}

// ----------------------koordinatenausgabe in statuszeile---------------------------
'.$SVGvars_coordscript.'

// -------------------------tooltip-ausgabe fuer buttons------------------------------
'.$SVGvars_tooltipscript.'

]]></script>

  <defs>
'.$SVGvars_defs.'
  </defs> 
  <rect id="background" style="fill:white" width="100%" height="100%"/>
	<g id="moveGroup" transform="translate(0 0)">
		<text x="'.$res_xm.'" y="'.$res_ym.'" style="opacity:1;text-anchor:middle">Kartenausschnitt wird geladen...
			<animate attributeName="opacity" begin="0s" dur="4s" fill="freeze" keyTimes="0; 0.25; 0.5; 0.75; 1" repeatCount="indefinite" values="1;1;0;1;1"/>
		</text>
	  <image xlink:href="'.$bg_pic.'" height="100%" width="100%" y="0" x="0"/>
	  <g id="cartesian" transform="translate(0,'.$res_y.') scale(1,-1)">
<!-- --------------------- flexible SVG-objekte aus der DB ---------------------- -->
'.$SVGmap.'
<!-- --------------------- interaktive zeichenelemente ---------------------- -->
	    <polygon points="" id="polygon" style="fill-opacity:0.25;fill:yellow;stroke:grey;stroke-width:2"/>
	    <polyline points="" id="polyline" style="fill:none;stroke-dasharray:2,2;stroke:black;stroke-width:4"/>
	  </g>
	</g>
  <rect id="canvas" cursor="crosshair" onmousedown="mousedown(evt)" onmousemove="mousemove(evt);" onmouseup="mouseup(evt);" width="100%" height="100%" opacity="0"/>
  <a xlink:href="">
    <g id="buttons" onmouseout="hide_tooltip()" onmousedown="hide_tooltip()" cursor="pointer">
'.$SVGvars_mainnavbuttons.'
    </g>
  </a>

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
 echo '<EMBED align="center" SRC="'.TEMPPATH_REL.$svgfile.'" TYPE="image/svg+xml" width="'.$res_x.'" height="'.$res_y.'" PLUGINSPAGE="http://www.adobe.com/svg/viewer/install/"/>';
?>