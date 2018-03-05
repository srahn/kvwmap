<?php

$svg1 ='<?xml version="1.0"?>
	<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
	  "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
	<svg width="553" height="257" zoomAndPan="disable"
	  xmlns="http://www.w3.org/2000/svg" version="1.1"
	  xmlns:xlink="http://www.w3.org/1999/xlink">
	<script id="pscript1" type="text/ecmascript"><![CDATA[
	
	
// -------------------------mausinteraktionen auf canvas------------------------------

hue = 0;
adeg = 0;
sat = 1;
val = 1;
squarecolor = "#ffffff"; //starting hue
pickindex = 0;
var rgb1 = "";

var color = "#ffffff"; 


function mousedown(evt){
	top.document.getElementById("sample2").style.backgroundColor = color;
	top.document.GUI.rgb.value = rgb1[0]+" "+rgb1[1]+" "+rgb1[2];
	setSquare(adeg);
}

window.setInterval("updatecolor()", 1000);

function updatecolor(){
	newrgbstring = top.document.GUI.rgb.value + "";
	newrgb = newrgbstring.split(" ");
	if(newrgb.length == 3){
		c = rgb2hex(newrgb);
  	hexColorArray(c);
		top.document.getElementById("sample2").style.backgroundColor = color;
		hsv = rgb2hsv(newrgb[0], newrgb[1], newrgb[2]);
		setSquare(hsv[0]*360);
	}
}

function Numsort (a, b) {
  return a - b;
}

function min(sortarray){
	sortarray.sort(Numsort);
	return sortarray[0];
}

function max(sortarray){
	sortarray.sort(Numsort);
	sortarray.reverse();
	return sortarray[0];
}


function rgb2hsv(R,G,B) {
	var_R = R / 255;                     //RGB from 0 to 255
	var_G = G / 255;
	var_B = B / 255;
	var_Min = min(new Array(var_R,var_G,var_B));    //Min. value of RGB
	var_Max = max(new Array(var_R,var_G,var_B));    //Max. value of RGB
	del_Max = var_Max - var_Min;			              //Delta RGB value
	V = var_Max;
	if(del_Max == 0){                     //This is a gray, no chroma...
		H = 0;                                //HSV results from 0 to 1
		S = 0;
	}
	else{																	//Chromatic data...
		S = del_Max / var_Max;
		del_R = (((var_Max-var_R)/6)+(del_Max/2))/del_Max;
		del_G = (((var_Max-var_G)/6)+(del_Max/2))/del_Max;
		del_B = (((var_Max-var_B)/6)+(del_Max/2))/del_Max;
		if(var_R == var_Max){
			H = del_B - del_G;
		}
	  else{
			if(var_G == var_Max){
				H = (1/3)+del_R - del_B;
			}
	   	else{
				if(var_B == var_Max){
					H = (2/3) + del_G - del_R;
				}
			}
		}
		if(H < 0){
			H += 1;
		}
		if(H > 1){
			H -= 1;
		}
	}
	return new Array(H,S,V);
}


function hsv2rgb(Hdeg,S,V) {
  H = Hdeg/360;     // convert from degrees to 0 to 1
  if (S==0) {       // HSV values = From 0 to 1
    R = V*255;     // RGB results = From 0 to 255
    G = V*255;
    B = V*255;}
  else {
    var_h = H*6;
    var_i = Math.floor( var_h );     //Or ... var_i = floor( var_h )
    var_1 = V*(1-S);
    var_2 = V*(1-S*(var_h-var_i));
    var_3 = V*(1-S*(1-(var_h-var_i)));
    if (var_i==0)      {var_r=V ;    var_g=var_3; var_b=var_1}
    else if (var_i==1) {var_r=var_2; var_g=V;     var_b=var_1}
    else if (var_i==2) {var_r=var_1; var_g=V;     var_b=var_3}
    else if (var_i==3) {var_r=var_1; var_g=var_2; var_b=V}
    else if (var_i==4) {var_r=var_3; var_g=var_1; var_b=V}
    else               {var_r=V;     var_g=var_1; var_b=var_2}
    R = Math.round(var_r*255);   //RGB results = From 0 to 255
    G = Math.round(var_g*255);
    B = Math.round(var_b*255);
  }
  return new Array(R,G,B);
}

function rgb2hex(rgbary) {
  cary = new Array; 
  cary[3] = "#";
  for (i=0; i < 3; i++) {
    cary[i] = parseInt(rgbary[i]).toString(16);
    if (cary[i].length < 2) cary[i] = "0"+ cary[i];
    cary[3] = cary[3] + cary[i];
    cary[i+4] = rgbary[i]; //save dec values for later
  }
  // function returns hex color as an array of three two-digit strings
  // plus the full hex color and original decimal values
  return cary;
}



function hexColorArray(c) { //now takes string hex value with #
    color = c[3];
    return false;
}



function mouseMoved(e) {
  x = e.clientX;
  y = e.clientY;
	if(x >= 296){
		greyMoved(x,y);
		return false;
	}
 	if(y > 256){
		return false;
	}

    cartx = x - 128;
    carty = 128 - y;
    cartx2 = cartx * cartx;
    carty2 = carty * carty;
    cartxs = (cartx < 0)?-1:1;
    cartys = (carty < 0)?-1:1;
    cartxn = cartx/128;                      //normalize x
    rraw = Math.sqrt(cartx2 + carty2);       //raw radius
    rnorm = rraw/128;                        //normalized radius
    if (rraw == 0) {
      sat = 0;
      val = 0;
      rgb = new Array(0,0,0);
      }
    else {
      arad = Math.acos(cartx/rraw);            //angle in radians 
      aradc = (carty>=0)?arad:2*Math.PI - arad;  //correct below axis
      adeg = 360 * aradc/(2*Math.PI);  //convert to degrees
      if (rnorm > 1) {    // outside circle
            rgb = new Array(255,255,255);
            sat = 1;
            val = 1;            
            }
      //else rgb = hsv2rgb(adeg,1,1);
            else if (rnorm >= .5) {
	      sat = 1 - ((rnorm - .5) *2);
              val = 1;
	      rgb = hsv2rgb(adeg,sat,val);
	      }
              else {
                   sat = 1;
	      	   val = rnorm * 2;
	      	   rgb = hsv2rgb(adeg,sat,val);}
   }
	 rgb1 = rgb;
   c = rgb2hex(rgb);
   hexColorArray(c);
   hoverColor();
   return false;
}

function hoverColor() {
  top.document.getElementById("sample1").style.backgroundColor = color;
 	return false;
}

function greyMoved(x,y) {
    adeg = hue;
    xside = (x<=553)?x - 296:256;
    yside = (y<=256)?y:256;
    sat = xside/256;
    val = 1 - (yside/256);
		rgb1 = hsv2rgb(hue,sat,val);
    c = rgb2hex(rgb1);
    hexColorArray(c);
    hoverColor();
    return false;
}

function setSquare(deg) {
  hue = deg;
  adeg = deg;
  c = rgb2hex(hsv2rgb(hue,1,1));
  squarecolor = c[3];
  document.getElementById("greyback").setAttribute("style","fill: "+squarecolor);
}



]]></script>
	  <rect style="fill:#ffffff" id="greyback" width="100%" height="100%" visibility="visible"/>
		<image xlink:href="'.URL.APPLVERSION.GRAPHICSPATH.'hsvwheel.png" height="100%" width="100%" y="0" x="0"/>
	  <rect id="wheel" cursor="crosshair" onmousedown="mousedown(evt)" onmousemove="mouseMoved(evt);" width="100%" height="100%" opacity="0" visibility="visible"/>
	</svg>
';
$randomnumber = rand(0, 1000000);
$svgfile1  = $randomnumber.'SVG_colorchooser.svg';
$fpsvg1 = fopen(IMAGEPATH.$svgfile1,w) or die('fail: fopen('.$svgfile1.')');
chmod(IMAGEPATH.$svgfile1, 0666);
#
# erstellen der SVG
#
fputs($fpsvg1, $svg1);
fclose($fpsvg1);

#
# aufrufen der SVG
# 
# EMBED-Tag in externe Datei Embed.js ausgelagert, da man sonst im IE die SVG erst aktivieren (anklicken) muss (MS-Update vom 11.04.2006)
# Variablen die dann in Embed.js benutzt werden:
/*echo'
	
	<input name="sample1" type="text" style="background-color: rgb(255, 255, 255);" id="sample1">
	<input name="sample2" type="text" style="background-color: rgb(255, 255, 255);" id="sample2">
	RGB:&nbsp;<input size="10" name="rgb" type="text">
*/
echo'	
	
  <input type="hidden" name="srcpath2" value = "'.TEMPPATH_REL.$svgfile1.'">
  <input type="hidden" name="breite2" value = "553">
  <input type="hidden" name="hoehe2" value = "257">
';
#                  >>> object-tag: wmode="transparent" (hoehere anforderungen beim rendern!) <<<
//echo '<EMBED align="center" SRC="'.TEMPPATH_REL.$svgfile.'" TYPE="image/svg+xml" width="'.($res_x+1).'" height="'.($res_y+1).'" PLUGINSPAGE="http://www.adobe.com/svg/viewer/install/"/>';
# echo '<iframe src="'.TEMPPATH_REL.$svgfile.'" width="'.$res_x.'" height="'.$res_y.'" name="map"></iframe>';
echo '<script src="funktionen/Embed2.js" language="JavaScript" type="text/javascript"></script>';

?>
