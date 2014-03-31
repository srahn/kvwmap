<?php
# 2006-06-20 sr

$ret=$this->user->rolle->getConsume($this->user->rolle->newtime);
$nexttime=$ret[1]['next'];
$prevtime=$ret[1]['prev'];

if ($nexttime!='') {
  # eine schonmal geladene Karte wird angezeigt.
  # die nextfunktion kann aktiviert werden
  $mouseupfunction='go_next();';
  $nextbuttoncolor='0, 0, 0';
}
else {
  # deaktivieren der nextfunktion und ausgrauen des Button
  $mouseupfunction='';
  $nextbuttoncolor='190, 190, 190';
}
if ($prevtime!='' AND $this->prevtime!='0000-00-00 00:00:00') {
  # eine schonmal geladene Karte wird angezeigt.
  # die nextfunktion kann aktiviert werden
  $prevmouseupfunction='go_previous();';
  $brevbuttoncolor='0, 0, 0';
}
else {
  # deaktivieren der nextfunktion und ausgrauen des Button
  $prevmouseupfunction='';
  $prevbuttoncolor='200, 200, 200';
}

	$last_x = 0;
	
	function previous($prevbuttoncolor, $strPreviousView, $prevmouseupfunction){
		global $last_x;
		$previous ='
      <g id="previous" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="previous0.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="previous0.mouseup;previous0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <rect x="10" y="8.5" width="10" height="8.25" style="fill:rgb('.$prevbuttoncolor.')"/>
        <polygon points="4,12.5 11,5 11,20" style="fill:rgb('.$prevbuttoncolor.');stroke:rgb('.$prevbuttoncolor.');stroke-width:1"/>
        <rect id="previous0" onmouseover="show_tooltip(\''.$strPreviousView.'\',evt.clientX,evt.clientY)" onmousedown="hide_tooltip();highlight(evt);noMeasuring();" onmouseup="'.$prevmouseupfunction.'" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>';
    $last_x += 26;
  	return $previous;
	}

	function forward($nextbuttoncolor, $strNextView, $mouseupfunction){
		global $last_x;
		$next ='
      <g id="next" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="next0.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="next0.mouseup;next0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <g transform="rotate(180 12.5 12.7)">
          <rect x="10" y="8.5" width="10" height="8.25" style="fill:rgb('.$nextbuttoncolor.')"/>
          <polygon points="4,12.5 11,5 11,20" style="fill:rgb('.$nextbuttoncolor.');stroke:rgb('.$nextbuttoncolor.');stroke-width:1"/>
        </g>
        <rect id="next0" onmouseover="show_tooltip(\''.$strNextView.'\',evt.clientX,evt.clientY)" onmousedown="hide_tooltip();highlight(evt);noMeasuring();" onmouseup="'.$mouseupfunction.'" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>';
    $last_x += 26;
  	return $next;
	}

	function zoomin($strZoomIn){
		global $last_x;
		$zoomin ='
      <g id="zoomin" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="zoomin0.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="zoomin0.mouseup;zoomin0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <g transform="translate(-4 -9) matrix(0.7 0 0 0.7 -3.5 0)">
          <line x1="31.25" y1="34" x2="40" y2="42"
             style="stroke-linecap:round;fill:none;stroke:rgb(0,0,0);stroke-width:5"/>
          <line x1="27" y1="30" x2="40" y2="42"
             style="fill:none;stroke:rgb(0,0,0);stroke-width:2"/>
          <line x1="20" y1="26" x2="26" y2="26"
             style="stroke-linecap:round;fill:none;stroke:rgb(0,0,0);stroke-width:2"/>
          <line x1="23" y1="23" x2="23" y2="29"
             style="stroke-linecap:round;fill:none;stroke:rgb(0,0,0);stroke-width:2"/>
          <circle cx="23" cy="26" r="6" style="fill:none;stroke:rgb(0,0,0);stroke-width:2"/>
        </g>
        <rect id="zoomin0" onmouseover="show_tooltip(\''.$strZoomIn.'\',evt.clientX,evt.clientY)" onmousedown="zoomin();highlight(evt);noMeasuring();" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>';
    $last_x += 26;
  	return $zoomin;
	}

	function zoomout($strZoomOut){
		global $last_x;
		$zoomout ='
      <g id="zoomout" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="zoomout0.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="zoomout0.mouseup;zoomout0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <g transform="translate(-4 -9) matrix(0.7 0 0 0.7 -3.5 0)">
          <line x1="31.25" y1="34" x2="40" y2="42"
             style="stroke-linecap:round;fill:none;stroke:rgb(0,0,0);stroke-width:5"/>
          <line x1="27" y1="30" x2="40" y2="42"
             style="fill:none;stroke:rgb(0,0,0);stroke-width:2"/>
          <line x1="20" y1="26" x2="26" y2="26"
             style="stroke-linecap:round;fill:none;stroke:rgb(0,0,0);stroke-width:2"/>
          <circle cx="23" cy="26" r="6" style="fill:none;stroke:rgb(0,0,0);stroke-width:2"/>
        </g>
        <rect id="zoomout0" onmouseover="show_tooltip(\''.$strZoomOut.'\',evt.clientX,evt.clientY)" onmousedown="zoomout();highlight(evt);noMeasuring();" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>';
    $last_x += 26;
  	return $zoomout;
	}
	
	function zoomall($strZoomToFullExtent){
		global $last_x;
		$zoomall ='
      <g id="zoomall" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="zoomall0.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="zoomall0.mouseup;zoomall0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <g transform="translate(-4.7 -12) scale(1.35 1.35) matrix(0.7 0 0 0.7 -3.5 0)">
          <rect x="14" y="20" width="18" height="12"
             style="fill:none;stroke:rgb(0,0,0);stroke-width:1.5"/>
          <g id="za_2o">
            <polygon points="16.5,24.5 16.5,22.5 18.5,22.5"
               style="fill:rgb(0,0,0);stroke:rgb(0,0,0);stroke-width:1"/>
            <polygon points="27.5,22.5 29.5,22.5 29.5,24.5"
               style="fill:rgb(0,0,0);stroke:rgb(0,0,0);stroke-width:1"/>
          </g>
          <use xlink:href="#za_2o" transform="translate(0 52) scale(1 -1)"/>
        </g>
          <rect id="zoomall0" onmouseover="show_tooltip(\''.$strZoomToFullExtent.'\',evt.clientX,evt.clientY)" onmousedown="zoomall();noMeasuring();" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;fill-opacity:0.25"/>
      </g>';
    $last_x += 26;
  	return $zoomall;
	}

	function recentre($strPan){
		global $last_x;
		$recentre ='
      <g id="recentre" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="recentre0.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="recentre0.mouseup;recentre0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <g transform="scale(0.7) translate(-5 0)">
          <use xlink:href="#1move" transform="translate(2.1 -5.9) scale(0.9)"/> 
        </g>
        <rect id="recentre0" onmouseover="show_tooltip(\''.$strPan.'\',evt.clientX,evt.clientY)" onmousedown="recentre();highlight(evt)" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>';
    $last_x += 26;
  	return $recentre;
	}

	function coords1($strCoordinatesZoom){
		global $last_x;
		$coords1 ='
      <g id="coords1" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="coords1.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="coords1.mouseup;coords1.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <g transform="scale(0.7) translate(-5 0)">
          <use xlink:href="#jump_coords" transform="translate(2.1 -5.9) scale(0.9)"/> 
        </g>
        <rect id="coords0" onmouseover="show_tooltip(\''.$strCoordinatesZoom.'\',evt.clientX,evt.clientY)" onmousedown="hide_tooltip();highlight(evt);noMeasuring();" onmouseup="top.coords_input();showcoords();" x="0" y="0" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>';
    $last_x += 26;
  	return $coords1;
	}
	
	function ppquery($strInfo){
		global $last_x;
		$ppquery ='
      <g id="ppquery" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="ppquery0.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="ppquery0.mouseup;ppquery0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <text transform="scale(1.3 0.7)" x="9.75" y="24" style="text-anchor:middle;fill:rgb(0,0,0);font-size:20;font-family:Times;font-weight:bold;">
         i </text>
        <rect id="ppquery0" onmouseover="show_tooltip(\''.$strInfo.'\',evt.clientX,evt.clientY)" onmousedown="ppquery();hide_tooltip();highlight(evt);noMeasuring();" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>';
    $last_x += 26;
  	return $ppquery;
	}

	function touchquery($strTouchInfo){
		global $last_x;
		$touchquery ='
			<g id="touchquery" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="touchquery0.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="touchquery0.mouseup;touchquery0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <text transform="scale(1.3 0.7)" x="9.75" y="24" style="text-anchor:middle;fill:rgb(0,0,0);font-size:20;font-family:Times;font-weight:bold;">
         i </text>
				<defs>
				<g id="arrow">
					<polyline points="4,12.5 -1,15.5 4,18.5"/>
					<rect x="4" y="14.75" width="4" height="1.5" style="fill:rgb(100, 100, 100)"/>
				</g>
				</defs>
				<!--use xlink:href="#arrow" transform="translate(1 0) scale(0.7 0.7)" />
				<use xlink:href="#arrow" transform="translate(25 0) scale(0.7 0.7) rotate(180 -1 15.5)" />
				<use xlink:href="#arrow" transform="translate(13 -12) scale(0.7 0.7) rotate(90 -1 15.5)" />
				<use xlink:href="#arrow" transform="translate(13 12) scale(0.7 0.7) rotate(270 -1 15.5)" /-->

				<use xlink:href="#arrow" transform="translate(3 -9) scale(0.8 0.8) rotate(45 -1 15.5)" />
				<use xlink:href="#arrow" transform="translate(3 9) scale(0.8 0.8) rotate(-45 -1 15.5)" />
				<use xlink:href="#arrow" transform="translate(22.5 -9) scale(0.8 0.8) rotate(135 -1 15.5)" />
				<use xlink:href="#arrow" transform="translate(22.5 9) scale(0.8 0.8) rotate(-135 -1 15.5)" />
        <rect id="touchquery0" onmouseover="show_tooltip(\''.$strTouchInfo.'\',evt.clientX,evt.clientY)" onmousedown="touchquery();hide_tooltip();highlight(evt);noMeasuring();" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>';
    $last_x += 26;
  	return $touchquery;
	}
		
	function pquery($strInfoWithRadius){
		global $last_x;
		$pquery ='
      <g id="pquery" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="pquery0.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="pquery0.mouseup;pquery0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <circle cx="13" cy="13" r="8" style="fill:none;stroke:rgb(0,0,0);stroke-width:1"/>
        <text transform="scale(1.3 0.7)" x="9.75" y="24" style="text-anchor:middle;fill:rgb(0,0,0);font-size:20;font-family:Times;font-weight:bold;">
         i </text>
        <rect id="pquery0" onmouseover="show_tooltip(\''.$strInfoWithRadius.'\',evt.clientX,evt.clientY)" onmousedown="pquery();pquery_prompt();highlight(evt);noMeasuring();" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>';
    $last_x += 26;
  	return $pquery;
	}
	
	function polygonquery($strInfoInPolygon){
		global $last_x;
		$polygonquery ='
			<g id="polygonquery" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="polygonquery0.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="polygonquery0.mouseup;polygonquery0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <polygon
					points="252.5,91 177.5,113 106.5,192 128.5,360 116.5,384 127.5,408 173.5,417 282.5,351 394.5,284
						379.5,218 378.5,239 357.5,138 260.5,91"
					transform="matrix(1 0 0 0.95 0 0) scale(0.055)"
					 style="fill:rgb(222,222,222);stroke:rgb(0,0,0);stroke-width:18"/>
        <text transform="scale(1.3 0.7)" x="9.75" y="24" style="text-anchor:middle;fill:rgb(0,0,0);font-size:20;font-family:Times;font-weight:bold;">
         i </text>
        <rect id="polygonquery0" onmouseover="show_tooltip(\''.$strInfoInPolygon.'\',evt.clientX,evt.clientY)" onmousedown="polygonquery();highlight(evt);hidetooltip(evt);noMeasuring();" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>';
    $last_x += 26;
  	return $polygonquery;
	}
		
	function dist($strRuler){
		global $last_x;
		$dist ='
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
        <rect id="measure0" onmouseover="show_tooltip(\''.$strRuler.'\',evt.clientX,evt.clientY)" onmousedown="hide_tooltip();highlight(evt);measure();" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;fill-opacity:0.25"/>
      </g>';
    $last_x += 26;
  	return $dist;
	}
	
	function freepolygon($strFreePolygon){
		global $last_x;
		$freepolygon ='
			<g id="freepolygon" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="freepolygon0.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="freepolygon0.mouseup;freepolygon0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <polygon
					points="252.5,91 177.5,113 106.5,192 128.5,360 116.5,384 127.5,408 173.5,417 282.5,351 394.5,284
						379.5,218 378.5,239 357.5,138 260.5,91"
					transform="matrix(1 0 0 0.95 0 0) scale(0.055)"
					 style="fill:rgb(222,222,222);stroke:rgb(0,0,0);stroke-width:18"/>
        <rect id="freepolygon0" onmouseover="show_tooltip(\''.$strFreePolygon.'\',evt.clientX,evt.clientY)" onmousedown="drawpolygon();highlight(evt);hidetooltip(evt);noMeasuring();" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>';
    $last_x += 26;
  	return $freepolygon;
	}
	
	function freetext($strFreeText){
		global $last_x;
		$freetext ='
			<g id="freetext" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="freetext0.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="freetext0.mouseup;freetext0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <text transform="scale(1.3 1)" x="10" y="19" style="text-anchor:middle;fill:rgb(0,0,0);font-size:20;font-family:Times;font-weight:bold;">
         T </text>
        <rect id="freetext0" onmouseover="show_tooltip(\''.$strFreeText.'\',evt.clientX,evt.clientY)" onmousedown="addfreetext();highlight(evt);hidetooltip(evt);noMeasuring();" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>';
    $last_x += 26;
  	return $freetext;
	}
	
	function freearrow($strFreeArrow){
		global $last_x;
		$freearrow ='
			<g id="freepolygon" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="freearrow0.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="freearrow0.mouseup;freearrow0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <polygon points="-5 -40, 5 -40, 5 -20, 15 -20, 0 0, -15 -20, -5 -20, -5 -40"
					transform="translate(6 6) scale(0.45) rotate(135)"
					 style="fill:rgb(222,222,222);stroke:rgb(0,0,0);stroke-width:2"/>
        <rect id="freearrow0" onmouseover="show_tooltip(\''.$strFreeArrow.'\',evt.clientX,evt.clientY)" onmousedown="drawarrow();highlight(evt);hidetooltip(evt);noMeasuring();" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>';
    $last_x += 26;
  	return $freearrow;
	}

	function mobile($gps_follow){
		global $last_x;
		$mobile .= '
		<g id="gps" transform="translate('.$last_x.' 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(233,233,233);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="gps0.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="gps0.mouseup;gps0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <g transform="scale(0.6) translate(8 8)">
          <use xlink:href="#1move" transform="translate(2.1 -5.9) scale(0.9)"/> 
        </g>
				<text transform="scale(0.45 0.45)" x="22" y="19" style="text-anchor:middle;fill:rgb(0,0,0);font-size:20;font-family:Arial;font-weight:bold;">GPS</text>
				<text id="gps_text" transform="scale(0.45 0.45)" x="16" y="50" style="text-anchor:middle;fill:rgb(0,0,0);font-size:20;font-family:Arial;font-weight:bold;">'.$gps_follow.'</text>	
        <rect id="gps0" onmouseover="show_tooltip(\'GPS-Verfolgungsmodus\',evt.clientX,evt.clientY)" onmousedown="hide_tooltip();switch_gps_follow();highlight(evt);noMeasuring();" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;fill-opacity:0.25"/>
      </g>';
   	$last_x += 26;
  	return $mobile;
	}
    
if($this->user->rolle->back){$SVGvars_mainnavbuttons .= previous($prevbuttoncolor, $strPreviousView, $prevmouseupfunction);}
if($this->user->rolle->forward){$SVGvars_mainnavbuttons .= forward($nextbuttoncolor, $strNextView, $mouseupfunction);}
if($this->user->rolle->zoomin){$SVGvars_mainnavbuttons .= zoomin($strZoomIn);}
if($this->user->rolle->zoomout){$SVGvars_mainnavbuttons .= zoomout($strZoomOut);}
if($this->user->rolle->zoomall){$SVGvars_mainnavbuttons .= zoomall($strZoomToFullExtent);}
if($this->user->rolle->recentre){$SVGvars_mainnavbuttons .= recentre($strPan);}
if($this->user->rolle->jumpto){$SVGvars_mainnavbuttons .= coords1($strCoordinatesZoom);}
if($this->user->rolle->query){$SVGvars_mainnavbuttons .= ppquery($strInfo);}
if($this->user->rolle->touchquery){$SVGvars_mainnavbuttons .= touchquery($strTouchInfo);}
if($this->user->rolle->queryradius){$SVGvars_mainnavbuttons .= pquery($strInfoWithRadius);}
if($this->user->rolle->polyquery){$SVGvars_mainnavbuttons .= polygonquery($strInfoInPolygon);}
if($this->user->rolle->measure){$SVGvars_mainnavbuttons .= dist($strRuler);}
if($this->user->rolle->freepolygon){$SVGvars_mainnavbuttons .= freepolygon($strFreePolygon);}
if($this->user->rolle->freetext){$SVGvars_mainnavbuttons .= freetext($strFreeText);}
if($this->user->rolle->freearrow){$SVGvars_mainnavbuttons .= freearrow($strFreeArrow);}

if($_SESSION['mobile'] == 'true'){$SVGvars_mainnavbuttons .= mobile($this->formvars['gps_follow']);}

?>