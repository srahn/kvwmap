<?php
$SVGvars_defs = '

	<style type="text/css"><![CDATA[
		#buttons{
		}
		#buttons:hover .navbutton_bg{
			transition: all 0.15s ease;
			opacity: 1;
		}
		#buttons:hover .navbutton_frame{
			opacity: 1;
		}		
    .navbutton{			
			fill: #5c88a8;
			pointer-events: none;
		}
    .navbutton_stroke{
			stroke: #5c88a8;
		}
    .navbutton_semifill{
			fill-opacity: 0.5;
		}
		.navbutton_nofill{
			fill: none;
		}
		.navbutton_whitefill{
			fill: white;
		}
		.navbutton_bg{
			filter: url(#Schatten);
			fill: url(#LinearGradient);
			opacity: 0.7;
			transition: all 1s cubic-bezier(0.09, 0.93, 0.76, 0.84);
		}
		.navbutton_frame{
			fill: url(#LinearGradient);
			opacity: 0.0;
		}
		.navbutton_frame:hover{
			filter: url(#innershadow);
		}                             
		.active{
			fill: #b0d4f2 !important;
			opacity: 1 !important;
			filter: url(#innershadow);
		}		
		.disabled{			
			fill: #ccc;
		}
		.redlining{
			fill: rgb(180,30,30);
			stroke:rgb(180,30,30);
		}
    ]]></style>
				
		<filter id="innershadow">
			<feOffset dx="-0.5" dy="0"/>
			<feGaussianBlur stdDeviation="1.3"  result="offset-blur"/>                           <!-- Shadow Blur -->
			<feComposite operator="out" in="SourceGraphic" in2="offset-blur" result="inverse"/> <!-- Invert the drop shadow to create an inner shadow -->
			<feFlood flood-color="black" flood-opacity="1" result="color"/>                     <!-- Color & Opacity -->
			<feComposite operator="in" in="color" in2="inverse" result="shadow"/>               <!-- Clip color inside shadow -->
			<feComponentTransfer in="shadow" result="shadow">                                   <!-- Shadow Opacity -->
				<feFuncA type="linear" slope=".7"/>
			</feComponentTransfer>
			<feComposite operator="over" in="shadow" in2="SourceGraphic"/>                       <!-- Put shadow over original object -->
		</filter>
		
    <filter id="Schatten" width = "150%" height = "150%">
      <feGaussianBlur in="SourceAlpha" stdDeviation="3" result="blur"/>
      <feOffset in="blur" dx="4" dy="4" result="offsetBlur"/>
			<feComponentTransfer>
				<feFuncA type="linear" slope="0.55"/>
			</feComponentTransfer>
      <feMerge>
        <feMergeNode/>
        <feMergeNode in="SourceGraphic"/>
      </feMerge>
    </filter>
		
		<linearGradient id="LinearGradient" x1="0%" y1="0%" x2="0%" y2="100%">
			<stop offset="0%" stop-color="#fdfdfd" stop-opacity="0%" />
			<stop offset="100%" stop-color="#DAE4EC" stop-opacity="100%" />
		</linearGradient>

    <g id="1move">
        <g id="12move" style="stroke:#5c88a8;stroke-width:1.5">
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
';

	$ret=$this->user->rolle->getConsume($this->user->rolle->newtime);
	$nexttime=$ret[1]['next'];
	$prevtime=$ret[1]['prev'];

	if($nexttime!=''){
		$mouseupfunction='go_next();';
		$next_disabled='';
	}
	else{
		$mouseupfunction='';
		$next_disabled='disabled';
	}
	if($prevtime!='' AND $this->prevtime!='0000-00-00 00:00:00'){
		$prevmouseupfunction='go_previous();';
		$prev_disabled='';
	}
	else{
		$prevmouseupfunction='';
		$prev_disabled='disabled';
	}
	
	function previous($prev_disabled, $strPreviousView, $prevmouseupfunction){
		global $last_x;global $events;
		$previous ='
      <g id="previous" transform="translate('.$last_x.' 0)">
				<rect id="previous0" '.(($events == true)? 'onmouseover="show_tooltip(\''.$strPreviousView.'\',evt.clientX,evt.clientY)" onmousedown="hide_tooltip();highlightbyid(this.id);noMeasuring();" onmouseup="'.$prevmouseupfunction.'"' : '').' x="0" y="0" rx="3" ry="3" width="36" height="36" class="navbutton_frame"/>
				<g class="navbutton '.$prev_disabled.'" transform="translate(5 5) scale(0.8)">
					<path d="M6.1,15.7 L18.7,28.3 L18.7,28.3 C18.9,28.5 19.1,28.6 19.4,28.6 C19.7,28.6 19.9,28.5 20.1,28.3 L21.7,26.7 L21.7,26.7 C21.9,26.5 22.0,26.3 22.0,26.0 C22.0,25.7 21.9,25.5 21.7,25.3 L12.1,15.7 L12.1,15.7 C11.9,15.5 11.8,15.3 11.8,15.0 C11.8,14.7 11.9,14.5 12.1,14.3 L21.7,4.7 L21.7,4.7 C21.9,4.5 22.0,4.3 22.0,4.0 C22.0,3.7 21.9,3.5 21.7,3.3 L20.1,1.7 L20.1,1.7 C19.9,1.5 19.7,1.4 19.4,1.4 C19.1,1.4 18.9,1.5 18.7,1.7 L6.1,14.3 L6.1,14.3 C5.9,14.5 5.8,14.7 5.8,15.0 C5.8,15.3 5.9,15.5 6.1,15.7"/>
				</g>        
      </g>';
    $last_x += 36;
  	return $previous;
	}

	function forward($next_disabled, $strNextView, $mouseupfunction){
		global $last_x;global $events;
		$next ='
      <g id="next" transform="translate('.$last_x.' 0)">
				<rect id="next0" '.(($events == true)? 'onmouseover="show_tooltip(\''.$strNextView.'\',evt.clientX,evt.clientY)" onmousedown="hide_tooltip();highlightbyid(this.id);noMeasuring();" onmouseup="'.$mouseupfunction.'"' : '').' x="0" y="0" rx="3" ry="3" width="36" height="36" class="navbutton_frame"/>
				<g class="navbutton '.$next_disabled.'" transform="translate(5 5) scale(0.8)">
					<path d="M23.9,14.3 L11.3,1.7 L11.3,1.7 C11.1,1.5 10.9,1.4 10.6,1.4 C10.3,1.4 10.1,1.5 9.9,1.7 L8.3,3.3 L8.3,3.3 C8.1,3.5 8,3.7 8,4 C8,4.3 8.1,4.5 8.3,4.7 L17.9,14.3 L17.9,14.3 C18.1,14.5 18.2,14.7 18.2,15 C18.2,15.3 18.1,15.5 17.9,15.7 L8.3,25.3 L8.3,25.3 C8.1,25.5 8.0,25.7 8.0,26.0 C8.0,26.3 8.1,26.5 8.3,26.7 L9.9,28.3 L9.9,28.3 C10.1,28.5 10.3,28.6 10.6,28.6 C10.9,28.6 11.1,28.5 11.3,28.3 L23.9,15.7 L23.9,15.7 C24.1,15.5 24.2,15.3 24.2,15 C24.2,14.7 24.1,14.5 23.9,14.3"/>
				</g>
      </g>';
    $last_x += 36;
  	return $next;
	}

	function zoomin($strZoomIn){
		global $last_x;global $events;
		$zoomin ='
      <g id="zoomin" transform="translate('.$last_x.' 0)">
				<rect id="zoomin0" '.(($events == true)? 'onmouseover="show_tooltip(\''.$strZoomIn.'\',evt.clientX,evt.clientY)" onmousedown="zoomin();highlightbyid(this.id);"' : '').' x="0" y="0" rx="3" ry="3" width="36" height="36" class="navbutton_frame"/>
				<g class="navbutton" transform="translate(5 5) scale(0.8)">
					<path d="M19.5,20 C24.7,20 29,15.7 29.0,10.5 C29,5.3 24.7,1 19.5,1 C14.3,1 10,5.3 10,10.5 C10,15.7 14.3,20 19.5,20 M12.9,10.6 L12.9,10.5 L12.9,10.5 C12.9,10.5 12.9,10.5 12.9,10.4 C12.9,6.8 15.8,3.9 19.4,3.9 C19.5,3.9 19.5,3.9 19.5,3.9 L19.6,3.9 L19.6,3.9 C23.1,4 26,6.9 26,10.4 L26,10.5 C26,14.1 23.0898508737,17 19.5,17 L19.4,17 C15.9,17 13,14.1 12.9,10.6" style="fill-rule: evenodd;"/>
					<path d="M13.9,18.1 L5.7,26.3 L5.7,26.3 C5.5,26.5 5.3,26.6 5.0,26.6 C4.7,26.6 4.5,26.5 4.3,26.3 L3.7,25.7 L3.7,25.7 C3.5,25.5 3.4,25.3 3.4,25 C3.4,24.7 3.5,24.5 3.7,24.3 L11.9,16.1 Z"/>
					<path d="M23,12 L16,12 C15.4,12 15,11.6 15,11 L15,10 C15,9.4 15.4,9 16,9 L23,9 C23.6,9 24,9.4 24,10 L24,11 C24,11.6 23.6,12 23,12"/>
					<path d="M18,14 L18,7 C18,6.4 18.4,6 19,6 L20,6 C20.6,6 21,6.4 21,7 L21,14 C21,14.6 20.6,15 20,15 L19.0,15 C18.4,15 18,14.6 18,14"/>
				</g>
      </g>';
    $last_x += 36;
  	return $zoomin;
	}

	function zoomout($strZoomOut){
		global $last_x;global $events;
		$zoomout ='
      <g id="zoomout" transform="translate('.$last_x.' 0)">
				<rect id="zoomout0" '.(($events == true)? 'onmouseover="show_tooltip(\''.$strZoomOut.'\',evt.clientX,evt.clientY)" onmousedown="zoomout();highlightbyid(this.id);"' : '').' x="0" y="0" rx="3" ry="3" width="36" height="36" class="navbutton_frame"/>
				<g class="navbutton" transform="translate(5 5) scale(0.8)">
					<path d="M19.5,20 C24.7,20 29,15.7 29.0,10.5 C29,5.3 24.7,1 19.5,1 C14.3,1 10,5.3 10,10.5 C10,15.7 14.3,20 19.5,20 M12.9,10.6 L12.9,10.5 L12.9,10.5 C12.9,10.5 12.9,10.5 12.9,10.4 C12.9,6.8 15.8,3.9 19.4,3.9 C19.5,3.9 19.5,3.9 19.5,3.9 L19.6,3.9 L19.6,3.9 C23.1,4 26,6.9 26,10.4 L26,10.5 C26,14.1 23.0898508737,17 19.5,17 L19.4,17 C15.9,17 13,14.1 12.9,10.6" style="fill-rule: evenodd;"/>
					<path d="M13.9,18.1 L5.7,26.3 L5.7,26.3 C5.5,26.5 5.3,26.6 5.0,26.6 C4.7,26.6 4.5,26.5 4.3,26.3 L3.7,25.7 L3.7,25.7 C3.5,25.5 3.4,25.3 3.4,25 C3.4,24.7 3.5,24.5 3.7,24.3 L11.9,16.1 Z"/>
					<path d="M23,12 L16,12 C15.4,12 15,11.6 15,11 L15,10 C15,9.4 15.4,9 16,9 L23,9 C23.6,9 24,9.4 24,10 L24,11 C24,11.6 23.6,12 23,12"/>
				</g>
      </g>';
    $last_x += 36;
  	return $zoomout;
	}
	
	function zoomall($strZoomToFullExtent){
		global $last_x;global $events;
		$zoomall ='
      <g id="zoomall" transform="translate('.$last_x.' 0)">
				<rect id="zoomall0" '.(($events == true)? 'onmouseover="show_tooltip(\''.$strZoomToFullExtent.'\',evt.clientX,evt.clientY)" onmousedown="zoomall();noMeasuring();"' : '').' x="0" y="0" rx="3" ry="3"   width="36" height="36" class="navbutton_frame"/>
				<g class="navbutton" transform="translate(5 5) scale(0.8)">
					<path d="M1,20 L1,29 L10,29 Z"/>
					<path d="M20,29 L29,29 L29,20 Z"/>
					<path d="M29,10 L29,1 L20,1 Z"/>
					<path d="M10,1 L1,1 L1,10 Z"/>
				</g>
      </g>';
    $last_x += 36;
  	return $zoomall;
	}

	function recentre($strPan){
		global $last_x;global $events;
		$recentre ='
      <g id="recentre" transform="translate('.$last_x.' 0)">
				<rect id="recentre0" '.(($events == true)? 'onmouseover="show_tooltip(\''.$strPan.'\',evt.clientX,evt.clientY)" onmousedown="recentre();highlightbyid(this.id);"' : '').' x="0" y="0" rx="3" ry="3"   width="36" height="36" class="navbutton_frame"/>
				<g class="navbutton" transform="translate(2 4) scale(0.058 0.052)">
					<path d="M 284.00,21.09
           C 293.71,25.03 301.73,33.56 306.00,43.00
             315.94,38.17 321.42,38.95 332.00,39.00
             345.71,39.07 356.65,45.12 363.74,57.00
             370.30,67.98 371.00,80.60 371.00,93.00
             379.30,89.47 384.26,89.90 393.00,90.00
             397.07,90.05 401.09,90.57 405.00,91.77
             428.73,99.09 432.97,125.68 433.00,147.00
             433.00,147.00 433.00,222.00 433.00,222.00
             433.00,222.00 433.00,273.00 433.00,273.00
             433.00,273.00 433.00,321.00 433.00,321.00
             433.00,369.76 430.93,411.85 397.42,451.00
             390.99,458.52 383.22,465.48 375.00,471.00
             365.16,477.60 354.36,483.09 343.00,486.57
             319.41,493.79 300.31,494.28 276.00,494.00
             276.00,494.00 266.00,493.09 266.00,493.09
             243.42,491.54 219.20,487.57 199.00,476.69
             171.59,461.93 154.28,440.42 137.33,415.00
             121.33,391.00 107.03,365.37 93.31,340.00
             93.31,340.00 74.75,305.00 74.75,305.00
             72.08,299.65 68.22,292.67 66.76,287.00
             62.83,271.76 66.52,253.30 78.09,242.17
             81.84,238.56 87.18,235.34 92.00,233.35
             98.48,230.66 105.14,230.92 112.00,231.00
             135.48,231.28 148.71,250.71 161.00,268.00
             161.00,268.00 161.00,96.00 161.00,96.00
             161.01,87.27 162.02,77.66 166.46,70.00
             176.02,53.50 189.99,48.79 208.00,49.00
             214.43,49.08 218.27,50.08 224.00,53.00
             226.55,24.71 259.60,11.19 284.00,21.09 Z
           M 248.00,66.00
           C 248.00,66.00 248.00,193.00 248.00,193.00
             248.00,193.00 247.43,228.00 247.43,228.00
             245.85,233.52 239.97,238.54 234.00,237.62
             224.69,236.18 223.01,227.73 223.00,220.00
             223.00,220.00 223.00,99.00 223.00,99.00
             222.99,92.26 222.66,85.31 217.79,80.10
             210.62,72.45 197.23,72.59 190.65,81.10
             187.13,85.64 187.01,90.56 187.00,96.00
             187.00,96.00 187.00,301.00 187.00,301.00
             186.99,305.22 187.13,309.19 184.91,312.98
             179.82,321.69 170.87,321.32 164.17,314.81
             164.17,314.81 132.87,274.00 132.87,274.00
             126.80,265.99 120.90,257.46 110.00,256.34
             95.35,254.84 88.18,269.62 92.55,282.00
             92.55,282.00 108.69,313.00 108.69,313.00
             108.69,313.00 137.00,364.00 137.00,364.00
             156.28,397.54 177.91,437.15 214.00,454.74
             229.47,462.28 254.78,467.79 272.00,468.00
             272.00,468.00 297.00,468.00 297.00,468.00
             319.80,467.74 344.52,461.25 363.00,447.52
             391.68,426.22 406.95,386.09 407.00,351.00
             407.00,351.00 407.00,142.00 407.00,142.00
             406.99,134.93 405.91,124.62 400.61,119.39
             394.28,113.14 378.31,112.17 373.36,127.00
             370.12,136.72 371.00,153.40 371.00,164.00
             371.00,164.00 371.00,230.00 371.00,230.00
             370.99,234.47 371.26,238.15 368.49,241.98
             362.24,250.63 351.42,249.30 347.31,241.00
             345.82,237.98 346.01,234.29 346.00,231.00
             346.00,231.00 346.00,96.00 346.00,96.00
             346.00,96.00 345.16,87.00 345.16,87.00
             343.98,75.88 342.91,65.06 329.00,64.10
             314.69,63.10 310.02,72.45 310.00,85.00
             310.00,85.00 310.00,219.00 310.00,219.00
             309.99,224.19 310.09,228.66 306.58,232.94
             300.75,240.08 290.48,238.84 285.93,231.00
             283.87,227.43 284.01,222.98 284.00,219.00
             284.00,219.00 284.00,66.00 284.00,66.00
             283.98,51.30 277.66,42.53 262.00,44.34
             251.37,47.21 248.12,55.83 248.00,66.00 Z" />
				</g>        
      </g>';
    $last_x += 36;
  	return $recentre;
	}

	function coords1($strCoordinatesZoom){
		global $last_x;global $events;
		$coords1 ='
      <g id="coords1" transform="translate('.$last_x.' 0)">
				<rect id="coords0" '.(($events == true)? 'onmouseover="show_tooltip(\''.$strCoordinatesZoom.'\',evt.clientX,evt.clientY)" onmousedown="hide_tooltip();highlightbyid(this.id);noMeasuring();" onmouseup="top.coords_input();"' : '').' x="0" y="0" rx="3" ry="3" width="36" height="36" class="navbutton_frame"/>
				<g class="navbutton" transform="translate(5 5) scale(0.8)">
					<path d="M15,26 C21.8,26 26,21.8 26,15 C26,8.9 21.8,4 15,4 C8.9,4 4,8.9 4,15 C4,21.8 8.9,26 15,26 M15,24 C20,24 24,20 24,15 C24,10.0 20,6 15,6 C10.0,6 6,10.0 6,15 C6,20 10.0,24 15,24" style="fill-rule: evenodd;"/>
					<path d="M20,16 L20,14 C20,13.4 20.4,13 21,13 L28,13 C28.6,13 29,13.4 29,14 L29,16 C29,16.6 28.6,17 28,17 L21,17 C20.4,17 20,16.6 20,16"/>
					<path d="M1,16 L1,14 C1,13.4 1.4,13 2,13 L9,13 C9.6,13 10,13.4 10,14 L10,16 C10,16.6 9.6,17 9,17 L2,17 C1.4,17 1,16.6 1,16"/>
					<path d="M14,10 L16,10 C16.6,10 17,9.6 17,9 L17,2 C17,1.4 16.6,1 16,1 L14,1 C13.4,1 13,1.4 13,2 L13,9 C13,9.6 13.4,10 14,10"/>
					<path d="M14,29 L16,29 C16.6,29 17,28.6 17,28 L17,21 C17,20.4 16.6,20 16,20 L14,20 C13.4,20 13,20.4 13,21 L13,28 C13,28.6 13.4,29 14,29"/>
        </g>        
      </g>';
    $last_x += 36;
  	return $coords1;
	}
	
	function coords2($strCoordinatesQuery){
		global $last_x;global $events;
		$coords2 ='
      <g id="coords2" transform="translate('.$last_x.' 0)">
				<rect id="coords02" '.(($events == true)? 'onmouseover="show_tooltip(\''.$strCoordinatesQuery.'\',evt.clientX,evt.clientY)" onmousedown="hide_tooltip();highlightbyid(this.id);noMeasuring();" onmouseup="showcoords();"' : '').' x="0" y="0" rx="3" ry="3" width="36" height="36" class="navbutton_frame"/>
				<g class="navbutton" transform="translate(5 5) scale(0.8)">
					<path d="M2,25 L28,25 C28.6,25 29,24.6 29,24 C29,23.4 28.6,23 28,23 L2,23 C1.4,23 1,23.4 1,24 C1,24.6 1.4,25 2,25"/>
					<path d="M7,28 L7,2 C7,1.4 6.6,1 6,1 C5.4,1 5,1.4 5,2 L5,28 C5,28.6 5.4,29 6,29 C6.6,29 7,28.6 7,28"/>
					<path d="M9,12 Z C9.1,11.8 9.4,11.7 9.7,11.7 C10,11.7 10.2,11.8 10.4,12 L13,14.6 L15.6,12 L15.6,12 C15.8,11.8 16,11.7 16.3,11.7 C16.6,11.7 16.8,11.8 17,12 L17,12 L17,12 C17.2,12.2 17.3,12.4 17.3,12.7 C17.3,13 17.2,13.2 17,13.4 L14.4,16 L17,18.6 L17,18.6 C17.2,18.8 17.3,19 17.3,19.34 C17.3,19.6 17.2,19.8 17,20 L17,20 L17,20 C16.8,20.2 16.6,20.34 16.34,20.34 C16,20.34 15.8,20.2 15.6,20 L13,17.4 L10.4,20 L10.4,20 C10.2,20.2 10,20.3 9.7,20.3 C9.4,20.3 9.2,20.2 9,20 L9,20 L9,20 C8.8,19.8 8.7,19.6 8.7,19.34 C8.7,19 8.8,18.8 9,18.6 L11.6,16 L9,13.4 L9,13.4 C8.8,13.2 8.7,13 8.7,12.7 C8.7,12.4 8.8,12.2 9,12"/>
					<path d="M25,20.7 C24.6,20.7 25.0,20.2 25.0,19.7 L25.0,16.4 L28,13.4 L28,13.4 C28.2,13.2 28.3,13 28.3,12.7 C28.3,12.4 28.2,12.2 28,12 L28,12 L28,12 C27.8,11.8 27.6,11.7 27.3,11.7 C27,11.7 26.8,11.8 26.6,12 L24,14.6 L21.4,12 L21.4,12 C21.2,11.8 21,11.7 20.7,11.7 C20.4,11.7 20.2,11.8 20,12 L20,12 L20,12 C19.8,12.2 19.7,12.4 19.7,12.7 C19.7,13 19.8,13.2 20,13.4 L23.0,16.4 L23.0,19.7 C23.0,20.2 23.4,20.7 25,20.7"/>
					<path d="M20.0,21.7 C20.6,21.7 21.0,21.2 21.0,20.7 L21.0,18 C21.0,17.4 20.6,17 20.0,17 C19.4,17 19.0,17.4 19.0,18 L19.0,20.7 C19.0,21.2 19.4,21.7 20.0,21.7"/>
        </g>
      </g>';
    $last_x += 36;
  	return $coords2;
	}
	
	function ppquery($strInfo){
		global $last_x;global $events;
		$ppquery ='
      <g id="ppquery" transform="translate('.$last_x.' 0)">
        <rect id="ppquery0" '.(($events == true)? 'onmouseover="show_tooltip(\''.$strInfo.'\',evt.clientX,evt.clientY)" onmousedown="ppquery();hide_tooltip();highlightbyid(this.id);noMeasuring();"' : '').' x="0" y="0" rx="3" ry="3"   width="36" height="36" class="navbutton_frame"/>
				<g class="navbutton" transform="translate(6 5) scale(0.8)">
					<text transform="scale(1.3 1)" x="11" y="25" style="text-anchor:middle;font-size:27px;font-family:Times;stroke: #5c88a8;">i</text>
				</g>
      </g>';
    $last_x += 36;
  	return $ppquery;
	}

	function touchquery($strTouchInfo){
		global $last_x;global $events;
		$touchquery ='
			<g id="touchquery" transform="translate('.$last_x.' 0)">
				<rect id="touchquery0" '.(($events == true)? 'onmouseover="show_tooltip(\''.$strTouchInfo.'\',evt.clientX,evt.clientY)" onmousedown="touchquery();hide_tooltip();highlightbyid(this.id);noMeasuring();"' : '').' x="0" y="0" rx="3" ry="3"   width="36" height="36" class="navbutton_frame"/>
				<g class="navbutton" transform="translate(5 5) scale(0.8)">
					<text transform="scale(1.3 1)" x="11" y="22" style="text-anchor:middle;font-size:21px;font-family:Times;stroke: #5c88a8;">i</text>
					<path d="M1,10 L1,1 L9,1 Z"/>
					<path d="M21,1 L29,10 L29,1 Z"/>
					<path d="M29,20 L29,29 L21,29 Z"/>
					<path d="M9,29 L1,20 L1,29 Z"/>
        </g>
      </g>';
    $last_x += 36;
  	return $touchquery;
	}
		
	function pquery($strInfoWithRadius){
		global $last_x;global $events;
		$pquery ='
      <g id="pquery" transform="translate('.$last_x.' 0)">
				<rect id="pquery0" '.(($events == true)? 'onmouseover="show_tooltip(\''.$strInfoWithRadius.'\',evt.clientX,evt.clientY)" onmousedown="pquery();pquery_prompt();highlightbyid(this.id);noMeasuring();"' : '').' x="0" y="0" rx="3" ry="3"   width="36" height="36" class="navbutton_frame"/>
        <g class="navbutton" style="stroke: #5c88a8;" transform="translate(5 5) scale(0.8)">
					<text transform="scale(1.3 1)" x="11" y="22" style="text-anchor:middle;font-size:21px;font-family:Times;">i</text>
					<circle cx="15" cy="16" r="13" style="fill:none;stroke-width: 2px"/>
				</g>
      </g>';
    $last_x += 36;
  	return $pquery;
	}
	
	function polygonquery($strInfoInPolygon){
		global $last_x;global $events;
		$polygonquery ='
			<g id="polygonquery" transform="translate('.$last_x.' 0)">
				<rect id="polygonquery0" '.(($events == true)? 'onmouseover="show_tooltip(\''.$strInfoInPolygon.'\',evt.clientX,evt.clientY)" onmousedown="polygonquery();highlightbyid(this.id);hidetooltip(evt);noMeasuring();"' : '').' x="0" y="0" rx="3" ry="3" width="36" height="36" class="navbutton_frame"/>
				<g class="navbutton" transform="translate(5 5) scale(0.8)">
					<text transform="scale(1.3 1)" x="11" y="22" style="text-anchor:middle;font-size:21px;font-family:Times;stroke: #5c88a8;">i</text>
					<path d="M16.25,29 C17.5,29 18.5,28 18.5,26.75 C18.5,25.5 17.5,24.5 16.25,24.5 C15.0,24.5 14.0,25.5 14,26.75 C14,28 15.0,29 16.25,29"/>
					<path d="M14.95,5.5 C16.2,5.5 17.2,4.5 17.2,3.25 C17.2,2.0 16.2,1 14.95,1 C13.7,1 12.7,2.0 12.7,3.2 C12.7,4.5 13.7,5.5 14.95,5.5"/>
					<path d="M8.3,29 C9.5,29 10.5,28 10.5,26.75 C10.5,25.5 9.5,24.5 8.3,24.5 C7.0,24.5 6,25.5 6,26.7 C6,28 7.0,29 8.25,29"/>
					<path d="M3.3,23.2 C4.5,23.2 5.5,22.2 5.5,20.95 C5.5,19.7 4.5,18.7 3.2,18.7 C2.0,18.7 1.0,19.7 1.0,20.9 C1,22.2 2.0,23.2 3.3,23.2"/>
					<path d="M5.3,11.2 C6.5,11.2 7.5,10.2 7.5,8.9 C7.5,7.7 6.5,6.7 5.3,6.7 C4.0,6.7 3,7.7 3,8.9 C3,10.2 4.0,11.2 5.3,11.2"/>
					<path d="M26.7,9.6 C27.9,9.6 28.9,8.6 28.9,7.4 C28.9,6.1 27.9,5.1 26.7,5.1 C25.4,5.1 24.4,6.1 24.4,7.4 C24.4,8.6 25.4,9.6 26.7,9.6"/>
					<path d="M24.6,23.9 C25.9,23.9 26.9,22.9 26.9,21.7 C26.9,20.4 25.9,19.4 24.6,19.4 C23.4,19.4 22.4,20.4 22.4,21.7 C22.4,22.9 23.4,23.9 24.6,23.9"/>
					<path d="M10.3,27.8 L14.2,27.8 L18.5,26.5 L23.4,23.5 L25.9,19.8 L27.3,9.5 L25.1,5.8 L17.2,3 L12.7,3.4 L6.5,7.1 L3.9,10.8 L2.6,18.8 L3.8,23.1 L6.2,25.9 Z M10.3,25.8 L14.2,25.8 L17.4,24.8 L22.4,21.8 L23.9,19.5 L25.4,9.2 L24.4,7.6 L16.5,4.8 L13.7,5.1 L7.5,8.8 L5.9,11.1 L4.6,19.1 L5.3,21.8 L7.7,24.6 Z" style="fill-rule: evenodd;"/>
					<path d="M10.3,25.8 L14.2,25.8 L17.4,24.8 L22.4,21.8 L23.9,19.5 L25.4,9.2 L24.4,7.6 L16.5,4.8 L13.7,5.1 L7.5,8.8 L5.9,11.1 L4.6,19.1 L5.3,21.8 L7.7,24.6 Z" style="opacity:0.15;"/>
        </g>
      </g>';
    $last_x += 36;
  	return $polygonquery;
	}
		
	function dist($strRuler){
		global $last_x;global $events;
		$dist ='
      <g id="dist" transform="translate('.$last_x.' 0)">
				<rect id="measure0" '.(($events == true)? 'onmouseover="show_tooltip(\''.$strRuler.'\',evt.clientX,evt.clientY)" onmousedown="hide_tooltip();highlightbyid(this.id);measure();"' : '').' x="0" y="0" rx="3" ry="3"   width="36" height="36" class="navbutton_frame"/>
				<g class="navbutton" transform="translate(5 5) scale(0.8)">
					<path d="M9.9,28.5 L28.5,9.9 L28.5,9.9 C28.7,9.8 28.8,9.5 28.8,9.2 C28.8,9 28.7,8.7 28.5,8.5 L21.5,1.5 L21.5,1.5 C21.3,1.3 21.0,1.2 20.8,1.2 C20.5,1.2 20.2,1.3 20.1,1.5 L1.5,20.1 L1.5,20.1 C1.3,20.2 1.2,20.5 1.2,20.8 C1.2,21.0 1.3,21.3 1.5,21.5 L8.5,28.5 L8.5,28.5 C8.7,28.7 9,28.8 9.2,28.8 C9.5,28.8 9.8,28.7 9.9,28.5 M9.2,25.7 L25.7,9.2 L20.8,4.3 L4.3,20.8 Z" style="fill-rule: evenodd;"/>
					<path d="M6.3,18.7 L8.3,20.7 L9.7,19.3 L7.7,17.3 Z"/>
					<path d="M11.8,13.2 L13.8,15.2 L15.2,13.8 L13.2,11.8 Z"/>
					<path d="M17.3,7.7 L19.3,9.7 L20.7,8.3 L18.7,6.3 Z"/>
					<path d="M9.1,16 L12.4,19.3 L13.8,17.9 L10.5,14.6 Z"/>
					<path d="M14.6,10.5 L17.9,13.8 L19.3,12.4 L16,9.1 Z"/>
        </g>        
      </g>';
    $last_x += 36;
  	return $dist;
	}
	
	function freepolygon($strFreePolygon){
		global $last_x;global $events;
		$freepolygon ='
			<g id="freepolygon" transform="translate('.$last_x.' 0)">
				<rect id="freepolygon0" '.(($events == true)? 'onmouseover="show_tooltip(\''.$strFreePolygon.'\',evt.clientX,evt.clientY)" onmousedown="drawpolygon();highlightbyid(this.id);hidetooltip(evt);noMeasuring();"' : '').' x="0" y="0" rx="3" ry="3"   width="36" height="36" class="navbutton_frame"/>
        <g class="navbutton redlining" style="stroke:none" transform="translate(5 5) scale(0.8)">
					<path d="M16.25,29 C17.5,29 18.5,28 18.5,26.75 C18.5,25.5 17.5,24.5 16.25,24.5 C15.0,24.5 14.0,25.5 14,26.75 C14,28 15.0,29 16.25,29"/>
					<path d="M14.95,5.5 C16.2,5.5 17.2,4.5 17.2,3.25 C17.2,2.0 16.2,1 14.95,1 C13.7,1 12.7,2.0 12.7,3.2 C12.7,4.5 13.7,5.5 14.95,5.5"/>
					<path d="M8.3,29 C9.5,29 10.5,28 10.5,26.75 C10.5,25.5 9.5,24.5 8.3,24.5 C7.0,24.5 6,25.5 6,26.7 C6,28 7.0,29 8.25,29"/>
					<path d="M3.3,23.2 C4.5,23.2 5.5,22.2 5.5,20.95 C5.5,19.7 4.5,18.7 3.2,18.7 C2.0,18.7 1.0,19.7 1.0,20.9 C1,22.2 2.0,23.2 3.3,23.2"/>
					<path d="M5.3,11.2 C6.5,11.2 7.5,10.2 7.5,8.9 C7.5,7.7 6.5,6.7 5.3,6.7 C4.0,6.7 3,7.7 3,8.9 C3,10.2 4.0,11.2 5.3,11.2"/>
					<path d="M26.7,9.6 C27.9,9.6 28.9,8.6 28.9,7.4 C28.9,6.1 27.9,5.1 26.7,5.1 C25.4,5.1 24.4,6.1 24.4,7.4 C24.4,8.6 25.4,9.6 26.7,9.6"/>
					<path d="M24.6,23.9 C25.9,23.9 26.9,22.9 26.9,21.7 C26.9,20.4 25.9,19.4 24.6,19.4 C23.4,19.4 22.4,20.4 22.4,21.7 C22.4,22.9 23.4,23.9 24.6,23.9"/>
					<path d="M10.3,27.8 L14.2,27.8 L18.5,26.5 L23.4,23.5 L25.9,19.8 L27.3,9.5 L25.1,5.8 L17.2,3 L12.7,3.4 L6.5,7.1 L3.9,10.8 L2.6,18.8 L3.8,23.1 L6.2,25.9 Z M10.3,25.8 L14.2,25.8 L17.4,24.8 L22.4,21.8 L23.9,19.5 L25.4,9.2 L24.4,7.6 L16.5,4.8 L13.7,5.1 L7.5,8.8 L5.9,11.1 L4.6,19.1 L5.3,21.8 L7.7,24.6 Z" style="fill-rule: evenodd;"/>
					<path d="M10.3,25.8 L14.2,25.8 L17.4,24.8 L22.4,21.8 L23.9,19.5 L25.4,9.2 L24.4,7.6 L16.5,4.8 L13.7,5.1 L7.5,8.8 L5.9,11.1 L4.6,19.1 L5.3,21.8 L7.7,24.6 Z" style="opacity:0.15;"/>
				</g>
      </g>';
    $last_x += 36;
  	return $freepolygon;
	}
	
	function freetext($strFreeText){
		global $last_x;global $events;
		$freetext ='
			<g id="freetext" transform="translate('.$last_x.' 0)">
				<rect id="freetext0" '.(($events == true)? 'onmouseover="show_tooltip(\''.$strFreeText.'\',evt.clientX,evt.clientY)" onmousedown="addfreetext();highlightbyid(this.id);hidetooltip(evt);noMeasuring();"' : '').' x="0" y="0" rx="3" ry="3"   width="36" height="36" class="navbutton_frame"/>
        <g class="navbutton redlining" transform="translate(5 5) scale(0.8)">
					<text transform="scale(1.3 1)" x="13" y="25" style="text-anchor:middle;font-size:28px;font-family:Times;">A</text>
				</g>				
      </g>';
    $last_x += 36;
  	return $freetext;
	}
	
	function freearrow($strFreeArrow){
		global $last_x;global $events;
		$freearrow ='
			<g id="freepolygon" transform="translate('.$last_x.' 0)">
				<rect id="freearrow0" '.(($events == true)? 'onmouseover="show_tooltip(\''.$strFreeArrow.'\',evt.clientX,evt.clientY)" onmousedown="drawarrow();highlightbyid(this.id);hidetooltip(evt);noMeasuring();"' : '').' x="0" y="0" rx="3" ry="3"   width="36" height="36" class="navbutton_frame"/>
        <g class="navbutton redlining" transform="translate(5 5) scale(0.8)">
					<polygon points="-5 -40, 5 -40, 5 -20, 15 -20, 0 0, -15 -20, -5 -20, -5 -40" transform="translate(6 6) scale(0.65) rotate(135)" style="fill-opacity:0.15;stroke-width:2"/>
				</g>        
      </g>';
    $last_x += 36;
  	return $freearrow;
	}

	function gps_follow($strGPS, $gps_follow){
		global $last_x;global $events;
		$mobile .= '
		<g id="gps" transform="translate('.$last_x.' 0)">
			<rect id="gps0" '.(($events == true)? 'onmouseover="show_tooltip(\''.$strGPS.'\',evt.clientX,evt.clientY)" onmousedown="hide_tooltip();switch_gps_follow();highlightbyid(this.id);noMeasuring();"' : '').' x="0" y="0" rx="3" ry="3" width="36" height="36" class="navbutton_frame"/>
			<g class="navbutton" transform="translate(5 5) scale(0.8)">
        <g transform="scale(0.9) translate(5 5)">
          <use xlink:href="#1move" transform="translate(2.1 -5.9) scale(0.9)"/> 
        </g>
				<text transform="scale(0.7 0.7)" x="20" y="14" style="text-anchor:middle;font-size:20;font-family:Arial;font-weight:bold;">GPS</text>
				<text id="gps_text" transform="scale(0.7 0.7)" x="14" y="47" style="text-anchor:middle;font-size:20;font-family:Arial;font-weight:bold;">'.$gps_follow.'</text>	
			</g>
    </g>';
   	$last_x += 36;
  	return $mobile;
	}

?>