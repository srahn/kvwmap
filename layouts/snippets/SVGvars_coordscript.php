<?php
 
  $javascript ='
  	<script type="text/javascript">
	<!--
	
		function dec2dms(number){
			number = number+"";
			part1 = number.split('.');
			degrees = part1[0];
			minutes = parseFloat("0."+part1[1]) * 60;
			minutes = minutes+"";
			part2 = minutes.split(".");
			minutes = part2[0];
			seconds = Math.round(parseFloat("."+part2[1]) * 60);
			return degrees+"°"+minutes+"\'"+seconds+\'"\';
		}
		
		function format_number(number){
			coordtype = \''.$this->user->rolle->coordtype.'\';
			if(coordtype == "dms" && parseFloat(number) < 361){
				return dec2dms(number);
			}
			else{
				if(parseFloat(document.GUI.pixelsize.value) < 0.01){
			    stellen = 2;
			  }
			  else if(parseFloat(document.GUI.pixelsize.value) < 0.1){
			    stellen = 1;
			  }
			  else{
			    stellen = 0;
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
		}					
	
		function coords_input(){			
			var mittex  = '.$this->map->width.'/2*parseFloat(top.document.GUI.pixelsize.value) + parseFloat(top.document.GUI.minx.value);
			var mittey  = parseFloat(top.document.GUI.maxy.value) - '.$this->map->height.'/2*parseFloat(top.document.GUI.pixelsize.value);
			mittex = format_number(mittex);
			mittey = format_number(mittey);
			coords1 = prompt("Geben Sie die gewünschten Koordinaten ein \noder klicken Sie auf Abbrechen für die Koordinatenabfrage.",mittex+" "+mittey);
			if(coords1){
				coords2 = coords1.split(" ");
				if(!coords2[0] || !coords2[1]){
					alert("Falsches Format");
					return;
				}
				document.GUI.INPUT_COORD.value = coords2[0]+","+coords2[1];
				document.GUI.CMD.value = "jump_coords";
				document.GUI.submit();
			}
		}

		function coords2(evt) {
		  coorx = evt.clientX*parseFloat(top.document.GUI.pixelsize.value) + parseFloat(top.document.GUI.minx.value);
		  coory = parseFloat(top.document.GUI.maxy.value) - evt.clientY*parseFloat(top.document.GUI.pixelsize.value);
		  	
		  coorx = format_number(coorx);
		  coory = format_number(coory);
		  		
		  if(top.document.GUI.coordx){
		  	top.document.GUI.coordx.value = coorx;
		  }
			if(top.document.GUI.coordy){
		  	top.document.GUI.coordy.value = coory;
		  }
			if(top.document.GUI.lastcoordx != undefined && top.document.GUI.lastcoordx.value != ""){
				vectorx = top.document.GUI.lastcoordx.value - coorx;
				vectory = top.document.GUI.lastcoordy.value - coory;
				distance = format_number(Math.sqrt(Math.pow(vectorx, 2) + Math.pow(vectory, 2)), stellen);
				window.status = " R:" + coorx + " / H:" + coory + "  Entfernung: " + distance + " m    EPSG: "+'.$this->user->rolle->epsg_code.';
			}
			else{
				window.status = " R:" + coorx + " / H:" + coory + "   EPSG: "+'.$this->user->rolle->epsg_code.';
			}
		}

	//-->
	</script>';	
	
echo $javascript;
		
?>