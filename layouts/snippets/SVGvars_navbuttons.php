<?php
 # 2008-01-24 pkvvm
  include(LAYOUTPATH.'languages/SVGvars_navbuttons_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
 ?>
<?php

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
  $nextbuttoncolor='200, 200, 200';
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

$SVGvars_navbuttons = '

			<g id="previous" transform="translate(0 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(222,222,222);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="previous0.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="previous0.mouseup;previous0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <rect x="10" y="8.5" width="10" height="8.25" style="fill:rgb('.$prevbuttoncolor.')"/>
        <polygon points="4,12.5 11,5 11,20" style="fill:rgb('.$prevbuttoncolor.');stroke:rgb('.$prevbuttoncolor.');stroke-width:1"/>
        <rect id="previous0" onmouseover="show_tooltip(\''.$strPreviousView.'\',evt.clientX,evt.clientY)" onmousedown="highlightbyid(\'previous0\');" onmouseup="'.$prevmouseupfunction.'" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>

      <g id="next" transform="translate(26 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(222,222,222);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
          <set attributeName="filter" begin="next0.mousedown" dur="0s" fill="freeze" to="none"/>
          <set attributeName="filter" begin="next0.mouseup;next0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
        </rect>
        <g transform="rotate(180 12.5 12.7)">
          <rect x="10" y="8.5" width="10" height="8.25" style="fill:rgb('.$nextbuttoncolor.')"/>
          <polygon points="4,12.5 11,5 11,20" style="fill:rgb('.$nextbuttoncolor.');stroke:rgb('.$nextbuttoncolor.');stroke-width:1"/>
        </g>
        <rect id="next0" onmouseover="show_tooltip(\''.$strNextView.'\',evt.clientX,evt.clientY)" onmousedown="highlightbyid(\'next0\');" onmouseup="'.$mouseupfunction.'" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>

      <g id="zoomin" transform="translate(52 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(222,222,222);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
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
        <rect id="zoomin0" onmouseover="show_tooltip(\''.$strZoomIn.'\',evt.clientX,evt.clientY)" onmousedown="highlightbyid(\'zoomin0\');zoomin();" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>

      <g id="zoomout" transform="translate(78 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(222,222,222);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
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
        <rect id="zoomout0" onmouseover="show_tooltip(\''.$strZoomOut.'\',evt.clientX,evt.clientY)" onmousedown="highlightbyid(\'zoomout0\');zoomout();" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>

      <g id="zoomall" transform="translate(104 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(222,222,222);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
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
          <rect id="zoomall0" onmouseover="show_tooltip(\''.$strZoomToFullExtent.'\',evt.clientX,evt.clientY)" onmousedown="zoomall();" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;fill-opacity:0.25"/>
      </g>

      <g id="recentre" transform="translate(130 0)">
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;stroke:none;"/>
        <rect x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:rgb(222,222,222);stroke:#4A4A4A;stroke-width:0.2;filter:url(#Schatten)">
        	<set attributeName="filter" begin="recentre0.mousedown" dur="0s" fill="freeze" to="none"/>
					<set attributeName="filter" begin="recentre0.mouseup;recentre0.mouseout" dur="0s" fill="freeze" to="url(#Schatten)"/>
				</rect>
        <g transform="scale(0.7) translate(-5 0)">
					<use xlink:href="#1move" transform="translate(2.1 -5.9) scale(0.9)"/> 
        </g>
        <rect id="recentre0" onmouseover="show_tooltip(\''.$strPan.'\',evt.clientX,evt.clientY)" onmousedown="highlightbyid(\'recentre0\');recentre();" x="0" y="0" rx="1" ry="1" width="25" height="25" style="fill:white;opacity:0.25"/>
      </g>
'
?>