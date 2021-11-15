<?php
  include(LAYOUTPATH . 'languages/SVGvars_coordscript_'.$this->user->rolle->language.'.php');

	foreach($this->epsg_codes as $epsg_code){
		$epsg_codes .= '<option';
		if($this->user->rolle->epsg_code == $epsg_code['srid'])$epsg_codes .= ' selected';
		$epsg_codes .= ' value="'.$epsg_code['srid'].'">'.$epsg_code['srid'].': '.$epsg_code['srtext'].'</option>';
	}
 
  $javascript ='
  	<script type="text/javascript">
	<!--
	
		form = '.$this->currentform.';		// das Formular in dem das Kartenfenster eingebettet ist
		
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
			number = number.trim() + "";
			part1 = number.split("°");
			degrees = parseFloat(part1[0]);
			part2 = part1[1].split("\'");
			minutes = parseFloat(part2[0]);
			if (coordtype == "dms") {
				seconds = part2[1].replace(/\'\'/g, "");
				seconds = parseFloat(seconds)/60;
			}
			minutes = (minutes+seconds) / 60;
			return Math.round((degrees + minutes) * 10000) / 10000;
		}

		function format_number(number, convert, freehand, meters){
			coordtype = \''.$this->user->rolle->coordtype.'\';
			epsgcode = \''.$this->user->rolle->epsg_code.'\';
			if(meters == false && epsgcode == 4326){
				if(coordtype != "dec" && convert == true){
					return dec2dms(number, coordtype);
				}
				else{
					stellen = 5;
				}
			}
			else{
				if(freehand == false){
					stellen = 3;
				}
				else if(parseFloat(document.GUI.pixelsize.value) < 0.01){
			    stellen = 2;
			  }
			  else if(parseFloat(document.GUI.pixelsize.value) < 0.1){
			    stellen = 1;
			  }
			  else{
			    stellen = 0;
			  }
			}
			number = Math.round( number * Math.pow(10, stellen) ) / Math.pow(10, stellen);
			str_number = number+"";
			str_split = str_number.split(".");
			if(!str_split[1]) str_split[1] = "";
			if(str_split[1].length < stellen){
				nachkomma = str_split[1];
				for(i=str_split[1].length+1; i <= stellen; i++){
				 	nachkomma += "0";
				}
				str_split[1] = nachkomma;
			}
			if(stellen == 0){sep = "";}
			else{sep = ".";	}
			return str_split[0]+sep+str_split[1];
		}					
			
		function coords_input(){
			var mittex  = '.$this->map->width.'/2*parseFloat(form.pixelsize.value) + parseFloat(form.minx.value);
			var mittey  = parseFloat(form.maxy.value) - '.$this->map->height.'/2*parseFloat(form.pixelsize.value);
			mittex = format_number(mittex, true, true, false);
			mittey = format_number(mittey, true, true, false);
			var Msg = $("#message_box");
			Msg.show();
			content = \'<div style="position: absolute;top: 0px;right: 0px"><a href="#" onclick="$(\\\'#message_box\\\').hide();" title="Schlie&szlig;en"><img style="border:none" src="'.GRAPHICSPATH.'exit2.png"></img></a></div>\';
			content+= \'<div style="height: 30px">' . $strCoordZoom . '</div>\';
			content+= \'<table style="padding: 5px"><tr><td align="left" style="width: 300px" class="px15">Koordinate (Rechtswert Hochwert):</td></tr>\';
			content+= \'<tr><td><input style="width: 310px" type="text" id="input_coords" name="input_coords" value="\'+mittex+\' \'+mittey+\'"></td></tr>\';
			content+= \'<tr><td>Koordinatenreferenzsystem:</td></tr>\';
			content+= \'<tr><td><select name="epsg_code" id="epsg_code" style="width: 310px">'.$epsg_codes.'</select></td></tr></table>\';
			content+= \'<br><input type="button" value="OK" onclick="coords_input_submit()">\';
			Msg.html(content);
			document.getElementById(\'input_coords\').select();
			document.onkeydown = function(ev){
				var key;
				ev = ev || event;
				key = ev.keyCode;
				if(key == 13)coords_input_submit();
			}
		}

		function coords_input_submit() {
			var delimiter = " ",
					rw,
					hw;
			coordtype = \'' . $this->user->rolle->coordtype . '\';
			coordsTxt = document.getElementById(\'input_coords\').value.replace(/\s\s+/g, \' \');
			epsgcode = document.getElementById(\'epsg_code\').value.trim();
			if (coordsTxt) {
				if (coordsTxt.includes(",") && (coordsTxt.match(/,/g) || []).length == 1) {
					delimiter = ",";
				}
				coords = coordsTxt.split(delimiter);
				rw = coords[0].trim();
				hw = coords[1].trim();
				if (epsgcode == 4326) {
					if (coordtype != "dec") {
						rw = dms2dec(rw, coordtype) + "";
						hw = dms2dec(hw, coordtype) + "";
					}
					if (rw > hw) {
						var kleiner = hw;
						hw = rw;
						rw = kleiner;
					}
				}
				if (!rw || !hw || rw.search(/[^-\d.]/g) != -1 || hw.search(/[^-\d.]/g) != -1) {
					alert("Falsches Format");
					return;
				}
				document.getElementById(\'message_box\').className = \'message_box_hidden\';
				startwaiting();
				document.GUI.INPUT_COORD.value = rw + "," + hw;
				document.GUI.go.value = "zoom2coord";
				document.GUI.CMD.value = "jump_coords";
				document.GUI.submit();
			}
		}

		function coords_anzeige(evt, vertex) {
			if(form.activated_vertex != undefined && form.activated_vertex.value != 0 && vertex == null)return;
			minx = '.$this->Stelle->MaxGeorefExt->minx.';
			miny = '.$this->Stelle->MaxGeorefExt->miny.';
			maxx = '.$this->Stelle->MaxGeorefExt->maxx.';
			maxy = '.$this->Stelle->MaxGeorefExt->maxy.';
			
			if(vertex != null){
				coorxf = top.format_number(vertex.getAttribute("x"), false, false, false);
				cooryf = top.format_number(vertex.getAttribute("y"), false, false, false);
			}
			else{
				coorxf = top.format_number(evt.clientX*parseFloat(form.pixelsize.value) + parseFloat(form.minx.value), true, true, false);
				cooryf = top.format_number(form.maxy.value - evt.clientY*parseFloat(form.pixelsize.value), true, true, false);
			}
			
			if(coorxf < minx || coorxf > maxx)coorxf = "undefiniert";
			if(cooryf < miny || cooryf > maxy)cooryf = "undefiniert";
		  
			if(form.lastcoordx != undefined && form.lastcoordx.value != ""){
				vectorx = form.lastcoordx.value - coorxf;
				vectory = form.lastcoordy.value - cooryf;
				distance = format_number(Math.sqrt(Math.pow(vectorx, 2) + Math.pow(vectory, 2)), false, true, false);
				if(form.runningcoords != undefined)form.runningcoords.value = coorxf + " / " + cooryf + "   " + distance + " m"; 
			}
			else{
				if(form.runningcoords != undefined)form.runningcoords.value = coorxf + " / " + cooryf; 
			}			
		}
		
		function show_coords(evt, vertex){
			if(vertex != null){
				coorx = top.format_number(vertex.getAttribute("x"), false, false, false);
				coory = top.format_number(vertex.getAttribute("y"), false, false, false);
			}
			else{
				coorx = top.format_number(evt.clientX*parseFloat(form.pixelsize.value) + parseFloat(form.minx.value), true, true, false);
				coory = top.format_number(form.maxy.value - evt.clientY*parseFloat(form.pixelsize.value), true, true, false);
			}
			if(form.secondcoords != undefined)top.ahah("index.php", "go=spatial_processing&curSRID='.$this->user->rolle->epsg_code.'&newSRID='.$this->user->rolle->epsg_code2.'&point="+coorx+" "+coory+"&operation=transformPoint&resulttype=wkt&coordtype='.$this->user->rolle->coordtype.'", new Array(form.secondcoords), "");
			form.firstcoords.value = coorx+" "+coory; 
			top.document.getElementById("showcoords").style.display="";
		}

	//-->
	</script>';	
	

echo $javascript;
		
?>