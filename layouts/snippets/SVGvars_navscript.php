<?php
$SVGvars_navscript = '

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

function measure(){
  doing = "measure";
  document.getElementById("canvas").setAttribute("cursor", "help");
}

// ----------------------------punkt setzen---------------------------------
function selectPoint(evt) {
  cmd = doing;
  // neuen punkt abgreifen
  pathx[0] = evt.clientX;
  pathy[0] = evt.clientY;
  top.sendpath(cmd,pathx,pathy);
}

// --------------------------pkt. setzen / box aufziehen------------------------------
function startPoint(evt) {
  dragging  = true;
  
  // zoomin-fensterfarbe anpassen
  document.getElementById("polygon").style.setProperty("fill","#FF6", "");
  document.getElementById("polygon").style.setProperty("stroke","grey", "");
  
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
//  alert("cmd: "+cmd);
  top.sendpath(cmd,boxx,boxy);
}

// ----------------------------vektor aufziehen---------------------------------
function startMove(evt) {
  moving  = true;

  // neuen punkt abgreifen
  move_x[0] = evt.clientX;
  move_y[0] = evt.clientY;
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
  cmd = doing;
  if (moved){ 
  	move_x[0]=resx_m-move_dx;
  	move_y[0]=resy_m-move_dy;
		} 
  moving  = false;
  moved  = false;
  // hiddenformvars aktualisieren
  top.sendpath(cmd,move_x,move_y);
}

// -----------------------kl. koordinatenpaar zuerst---------------------------
function checkOrder(cmd,boxx,boxy) {
	temp=0;
	if (boxx[2]<boxx[0]) {temp=boxx[0];boxx[0]=boxx[2];boxx[2]=temp;}
	if (boxy[2]<boxy[0]) {temp=boxy[0];boxy[0]=boxy[2];boxy[2]=temp;}
}

function redraw() {
  // punktepfad erstellen
  path = "";
  for(var i = 0; i < pathx.length; ++i)
   {
    path = path+" "+pathx[i]+","+pathy[i];
   }
  
  // polygon um punktepfad erweitern
  // alert("SVGvars_navscript.svg 163 path: "+path);
  document.getElementById("polygon").setAttribute("points", path);
}

'
?>