<?php
$SVGvars_defs = '
    <filter id="Schatten" width = "150%" height = "150%">
      <feGaussianBlur in="SourceAlpha" stdDeviation="1.5" result="blur"/>
      <feOffset in="blur" dx="2" dy="2" result="offsetBlur"/>
      <feSpecularLighting in="blur" surfaceScale="1.5" specularConstant="1" specularExponent="15" lighting-color="white" result="specOut">
        <fePointLight x="-5000" y="-10000" z="20000"/>
      </feSpecularLighting>
      <feComposite in="specOut" in2="SourceAlpha" operator="in" result="specOut"/>
      <feComposite in="SourceGraphic" in2="specOut" operator="arithmetic" k1="0" k2="0.7" k3="0.7" k4="0" result="litPaint"/>
      <feMerge>
        <feMergeNode in="offsetBlur"/>
        <feMergeNode in="litPaint"/>
      </feMerge>
    </filter>

    <g id="1move">
        <g id="12move" style="fill:black;stroke:black;stroke-width:1.5">
          <line x1="17" y1="26" x2="29" y2="26"/>
          <polyline points="17,23 12,26 17,29"/>
          <polyline points="29,23 34,26 29,29"/>
        </g>
        <use xlink:href="#12move" transform="rotate(90,23,26)"/> 
    </g>

    <g id="jump_coords">
        <g id="jump_coords0" transform="translate(23 26.5) scale(1.3)">
					<circle cx="0" cy="0" r="8.5" style="fill:black;fill-opacity:0.15;stroke:black;stroke-width:1.5" />
					<line x1="2" y1="0" x2="11.5" y2="0" style="fill:black;stroke:black;stroke-width:1.5"/>
					<line x1="-2" y1="0" x2="-11.5" y2="0" style="fill:black;stroke:black;stroke-width:1.5"/>
					<line x1="0" y1="2" x2="0" y2="11.5" style="fill:black;stroke:black;stroke-width:1.5"/>
					<line x1="0" y1="-2" x2="0" y2="-11.5" style="fill:black;stroke:black;stroke-width:1.5"/>
				</g> 
    </g>
    		
    <g id="crosshair_blue">
			<circle cx="0" cy="0" r="8.5" style="fill-opacity:0.5;fill:rgb(192,192,255);stroke:blue;stroke-width:1.2" />
			<line x1="2" y1="0" x2="11.5" y2="0" style="fill-opacity:0.5;fill:rgb(192,192,255);stroke:blue;stroke-width:1.2"/>
			<line x1="-2" y1="0" x2="-11.5" y2="0" style="fill-opacity:0.5;fill:rgb(192,192,255);stroke:blue;stroke-width:1.2"/>
			<line x1="0" y1="2" x2="0" y2="11.5" style="fill-opacity:0.5;fill:rgb(192,192,255);stroke:blue;stroke-width:1.2"/>
			<line x1="0" y1="-2" x2="0" y2="-11.5" style="fill-opacity:0.5;fill:rgb(192,192,255);stroke:blue;stroke-width:1.2"/>
			<polygon id="point_direction" transform="" visibility="'.(($this->angle_attribute != '') ? 'visible' : 'hidden').'" points="-5 15, 5 15, 5 35, 10 35, 0 50, -10 35, -5 35, -5 15" style="fill-opacity:0.5;fill:rgb(192,192,255);stroke:blue;stroke-width:1.2"/>
		</g>

		<g id="crosshair_red">
			<circle cx="0" cy="0" r="8.5" style="fill-opacity:0.5;fill:rgb(192,0,0);stroke:red;stroke-width:1.2" />
			<line x1="2" y1="0" x2="11.5" y2="0" style="fill-opacity:0.5;fill:rgb(192,0,0);stroke:red;stroke-width:1.2"/>
			<line x1="-2" y1="0" x2="-11.5" y2="0" style="fill-opacity:0.5;fill:rgb(192,0,0);stroke:red;stroke-width:1.2"/>
			<line x1="0" y1="2" x2="0" y2="11.5" style="fill-opacity:0.5;fill:rgb(192,0,0);stroke:red;stroke-width:1.2"/>
			<line x1="0" y1="-2" x2="0" y2="-11.5" style="fill-opacity:0.5;fill:rgb(192,0,0);stroke:red;stroke-width:1.2"/>
		</g>

	  <cursor id="MyMove" x="13" y="13">
	    <use xlink:href="#1move" transform="scale(0.6)"/> 
		</cursor>

		<polygon points="-5 -40, 5 -40, 5 -20, 15 -20, 0 0, -15 -20, -5 -20, -5 -40" id="free_arrow" style="opacity:1;fill:rgb(255,0,0);stroke:rgb(0,0,0);stroke-width:2;"/>
'
?>