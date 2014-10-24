<?php
 
  $javascript ='
  	<script type="text/javascript">
	<!--
	
		function dec2dms(number){
			number = number+"";
			part1 = number.split(".");
			degrees = part1[0];
			minutes = parseFloat("0."+part1[1]) * 60;
			minutes = minutes+"";
			part2 = minutes.split(".");
			minutes = part2[0];
			if(part2[1] != undefined)seconds = Math.round(parseFloat("."+part2[1]) * 60);
			else seconds = "00";			
			return degrees+"°"+minutes+"\'"+seconds+\'"\';
		}
		
		function dms2dec(number){
			number = number+"";
			part1 = number.split("°");
			degrees = parseFloat(part1[0]);
			part2 = part1[1].split("\'");
			minutes = parseFloat(part2[0]);
			seconds = part2[1].replace(/"/g, "");
			seconds = parseFloat(seconds)/60;
			minutes = (minutes+seconds)/60;
			return Math.round((degrees + minutes)*10000)/10000;  
		}
		
		function format_number(number, convert, freehand){
			coordtype = \''.$this->user->rolle->coordtype.'\';
			epsgcode = \''.$this->user->rolle->epsg_code.'\';
			if(epsgcode == 4326){
				if(coordtype == "dms" && convert == true){
					return dec2dms(number);
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
			epsgcode = \''.$this->user->rolle->epsg_code.'\';
			coordtype = \''.$this->user->rolle->coordtype.'\';		
			var mittex  = '.$this->map->width.'/2*parseFloat(top.document.GUI.pixelsize.value) + parseFloat(top.document.GUI.minx.value);
			var mittey  = parseFloat(top.document.GUI.maxy.value) - '.$this->map->height.'/2*parseFloat(top.document.GUI.pixelsize.value);
			mittex = format_number(mittex, true, true);
			mittey = format_number(mittey, true, true);
			coords1 = prompt("Geben Sie die gewünschten Koordinaten ein \noder klicken Sie auf Abbrechen für die Koordinatenabfrage.",mittex+" "+mittey);
			if(coords1){
				coords2 = coords1.split(" ");
				if(epsgcode == 4326 && coordtype == "dms"){
					coords2[0] = dms2dec(coords2[0])+"";
					coords2[1] = dms2dec(coords2[1])+"";
				}
				if(!coords2[0] || !coords2[1] || coords2[0].search(/[^-\d.]/g) != -1 || coords2[1].search(/[^-\d.]/g) != -1){
					alert("Falsches Format");
					return;
				}
				document.GUI.INPUT_COORD.value = coords2[0]+","+coords2[1];
				document.GUI.CMD.value = "jump_coords";
				document.GUI.submit();
			}
		}

		function coords_anzeige(evt) {
			minx = '.$this->Stelle->MaxGeorefExt->minx.';
			miny = '.$this->Stelle->MaxGeorefExt->miny.';
			maxx = '.$this->Stelle->MaxGeorefExt->maxx.';
			maxy = '.$this->Stelle->MaxGeorefExt->maxy.';
		  coorx = evt.clientX*parseFloat(top.document.GUI.pixelsize.value) + parseFloat(top.document.GUI.minx.value);
		  coory = parseFloat(top.document.GUI.maxy.value) - evt.clientY*parseFloat(top.document.GUI.pixelsize.value);
		  					
		  coorxf = format_number(coorx, true, true);
		  cooryf = format_number(coory, true, true);
			
			if(coorx < minx || coorx > maxx)coorxf = "undefiniert";
			if(coory < miny || coory > maxy)cooryf = "undefiniert";
		  
			if(top.document.GUI.lastcoordx != undefined && top.document.GUI.lastcoordx.value != ""){
				vectorx = top.document.GUI.lastcoordx.value - coorxf;
				vectory = top.document.GUI.lastcoordy.value - cooryf;
				distance = format_number(Math.sqrt(Math.pow(vectorx, 2) + Math.pow(vectory, 2)), false, true);
				window.status = " R:" + coorxf + " / H:" + cooryf + "  Entfernung: " + distance + " m    EPSG: "+'.$this->user->rolle->epsg_code.';
				if(top.document.GUI.runningcoords != undefined)top.document.GUI.runningcoords.value = coorxf + " / " + cooryf + "   " + distance + " m"; 
			}
			else{
				if(top.document.GUI.activated_vertex == undefined || top.document.GUI.activated_vertex.value == 0){		// nur wenn kein Punkt ueber den Punktfang aktiviert wurde
					window.status = " R:" + coorxf + " / H:" + cooryf + "   EPSG: "+'.$this->user->rolle->epsg_code.';
					if(top.document.GUI.runningcoords != undefined)top.document.GUI.runningcoords.value = coorxf + " / " + cooryf; 
				}
			}			
		}

	//-->
	</script>';	
	

echo $javascript;
		
?>