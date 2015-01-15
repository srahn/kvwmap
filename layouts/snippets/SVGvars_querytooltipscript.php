<?php
#$layerset=$this->user->rolle->getLayer('');

$layerset = $this->layerset;

$SVGvars_querytooltipscript = '

		var layerset = new Array();
		var layernumber = new Array();';
for($i = 0; $i < count($layerset); $i++){
	if($layerset[$i]['Layer_ID'] != ''){
		$SVGvars_querytooltipscript.= 'layerset['.$i.'] = top.document.GUI.qLayer'.$layerset[$i]['Layer_ID'].';
		';
		$SVGvars_querytooltipscript.= 'layernumber['.$i.'] = '.$layerset[$i]['Layer_ID'].';
		';
	}
}
$SVGvars_querytooltipscript .= '

		var oldmousey, oldmousex, mousey, mousex, tooltipstate = "ready_for_request", counter = 0, prevent;
		var maxwidth = 0;
		var xpos = 5;
		var ypos = 0;
		
		top.document.getElementById("svghelp").SVGshowtooltip = showtooltip;		// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
				
		function hidetooltip(evt){
			if(evt == undefined){
				cleartooltip();
				return;
			}
			mousex = evt.clientX;
			mousey = evt.clientY;
			if(mouse_down == false && (oldmousex == undefined || Math.abs(oldmousex-mousex) > 1 || Math.abs(oldmousey-mousey) > 1)){			// Maus bewegen
				if(prevent != 1 && (oldmousex == undefined || Math.abs(oldmousex-mousex) > 30 || Math.abs(oldmousey-mousey) > 30)){			// Maus bewegen
					cleartooltip();
				}
				tooltipstate = "ready_for_request";
			}
		}
		
		function cleartooltip(){
			var tooltipgroup = document.getElementById("tooltipgroup");
			var tooltipcontent = document.getElementById("tooltipcontent");
			var obj = document.getElementById("highlight")
			tooltipgroup.setAttribute(\'visibility\', \'hidden\');
			top.document.GUI.result.value = "";				
			while(tooltipcontent.childNodes.length > 0){
				tooltipcontent.removeChild(tooltipcontent.firstChild);
			}
			ypos = 0;
			maxwidth = 0;
			obj.setAttribute("d", "");
		}
			
		function showtooltip(result, showdata){
			var box = new Array();																					// array mit den BBoxen der Sachdatentexte
			var texts = new Array();																				// array mit den Sachdatentexten
			var pics = new Array;																						// array mit den Bildern	
			var tooltip = document.getElementById("querytooltip");
			var tooltipframe = document.getElementById("frame");
			var tooltipgroup = document.getElementById("tooltipgroup");
			var tooltipcontent = document.getElementById("tooltipcontent");
			cleartooltip();
			var res = result.split("||| ");
			// Highlighting-Geometrie
			if(res[1] != "" && res[1] != undefined){
				geom = res[1];
				geom = geom.replace(/-/g, "");
				geom = world2pixelsvg(geom);
				var obj = document.getElementById("highlight")
				obj.setAttribute("d", geom);
			}
			if(showdata){		// Daten nur anzeigen, wenn ueber die Karte abgefragt wurde
				var objects = res[0].split("|| ");
				var layername = settext(objects[0], xpos, ypos);									// Layername
				layername.setAttribute(\'visibility\', \'visible\');
				box[0] = layername.getBBox();																	// BBox berechnen
				ypos = ypos + box[0].height + 4;
				if(maxwidth < box[0].width){
					maxwidth = box[0].width + 6;
				}
				for(i = 1; i < objects.length; i++){
					if(objects[i] != ""){
						var elements = objects[i].split("| ");
						texts[i] = settext(elements[0], xpos, ypos);									// Sachdaten
						texts[i].setAttribute(\'visibility\', \'visible\');
						box[i] = texts[i].getBBox();																	// BBox berechnen
						ypos = ypos + box[i].height;
						if(maxwidth < box[i].width){
							maxwidth = box[i].width + 6;
						}
						var anzahl_bilder = elements.length-1;
						for(j = 1; j < elements.length; j++){
							pics[i] = new Array();
							pics[i][j] = document.createElementNS("http://www.w3.org/2000/svg", "image");
							pics[i][j].setAttributeNS(null, "id", "pic_"+i+j);
							pics[i][j].setAttributeNS(null, "height", "100");
							pics[i][j].setAttributeNS(null, "width", "140");
							pics[i][j].setAttributeNS(null, "preserveAspectRatio" , "xMinYMin meet");
							pics[i][j].setAttributeNS(null, "x", xpos);
							pics[i][j].setAttributeNS(null, "y", ypos);
							pics[i][j].setAttributeNS(null, "opacity", 1);
							pics[i][j].setAttributeNS("http://www.w3.org/1999/xlink", "xlink:href", elements[j]);
							tooltipcontent.appendChild(pics[i][j]);
							ypos = ypos + 110;
							if(maxwidth < 140){
								maxwidth = 140;
							}
						}
						ypos = ypos + 12;
					}
				}
				tooltipframe.setAttribute("x", xpos-8);
				tooltipframe.setAttribute("y", -20); 				
				tooltipframe.setAttribute("width", maxwidth + 8);
				tooltipframe.setAttribute("height", ypos + 6);
				updatetooltipposition(tooltipgroup);															// Tooltipposition updaten
				tooltipgroup.setAttribute(\'visibility\', \'visible\');
			}
		}				
			
		function mouse_move(evt){
			top.coords_anzeige(evt);
		 	if(doing == "ppquery"){
		 		hidetooltip(evt);
		 	}
		}		
				
		window.setInterval("tooltip_query()", 100);
		
		function cleartext(object){
			while(object.childNodes.length > 0){
				object.removeChild(object.firstChild);
			}
		}
		
		function updatetooltipposition(tooltipgroup){
			var overhead;		
			var x, y;	
		 	if(mousex > (resx/2)){
		 		overhead = mousex - (maxwidth + 16);
		 		if(overhead < 0){
		 			x = 5;
		 		}
		 		else{
		 			x = mousex - (maxwidth)-7;
		 		}
		 	}
		 	else{
		 		overhead = resx - (mousex + maxwidth + 16);
		 		if(overhead < 0){
		 			x = mousex + overhead + 10;	
		 		}
		 		else{
		 			x = mousex + 5;
		 		}
		 	}
		 	if(mousey > (resy/2)){
				overhead = mousey - (ypos + 20);
		 		if(overhead < 0){
		 			y = 20;
		 		}
		 		else{
		 			y = mousey - (ypos - 20);
				}
		 	}
		 	else{
				overhead = resy - (mousey + ypos + 20);
		 		if(overhead < 0){
		 			y = resy - (ypos - 14);	
		 		}
		 		else{
		 			y = mousey + 20;
				}
		 	}
			tooltipgroup.setAttribute("transform", "translate("+x+", "+y+")");
		}						

		function updatetextposition(tooltip){
		 	tooltip.setAttribute("x", xpos);
			tooltip.setAttribute("y", ypos+16);
		 	var tspan = tooltip.firstChild;
		 	while(tspan != null){ 
				tspan.setAttribute("x", xpos);
				tspan = tspan.nextSibling;
			}
		}
						
		function settext(text, x, y){
			var tooltipcontent = document.getElementById("tooltipcontent");
			var newtext = document.getElementById("querytooltip").cloneNode(true);
			newgroup = document.createElementNS("http://www.w3.org/2000/svg", "g");
			var link = 0;
			var links = new Array();
			newtext.setAttribute("id", "newtext"+ypos);
			newtext.setAttribute("x", x);
		  newtext.setAttribute("y", y);
			text = unescape(text);
			//cleartext(newtext);
			var tspan1;
			var offsety = 16;
			var offsetx = x;
			var lines = text.split("##");
			for(l = 0; l < lines.length; l++){
				if(lines[l].slice(0, 6) == "xlink:"){
					link = document.getElementById("link0").cloneNode(true);
					link.setAttribute("onclick", "window.parent.open(\'"+lines[l].slice(6)+"\', \'_blank\')");
					var nodText = document.createTextNode(basename(lines[l].slice(6)));
					link.appendChild(nodText);
					links.push(link);
					continue;
				}
				tspan1 = document.createElementNS("http://www.w3.org/2000/svg", "tspan");
		    if(l > 0){
		    	tspan1.setAttribute("dy", offsety);
		    	tspan1.setAttribute("x", offsetx);
		    }
		    tspan1.appendChild(document.createTextNode(lines[l]));
		    newtext.appendChild(tspan1);
			}
			newgroup.appendChild(newtext);
			for(li = 0; li < links.length; li++){
				var ypos = y+(l-1-links.length+li)*16;
				links[li].setAttribute("transform", "translate("+x+" "+ypos+")");
				newgroup.appendChild(links[li]);
			}
			tooltipcontent.appendChild(newgroup);
			return newgroup;
		}

		
		function basename(path) {
    	return path.replace( /.*\//, "" );
		}
		
		
		function cleartext(object){
			while(object.childNodes.length > 0){
				object.removeChild(object.firstChild);
			}
		}
						
		function tooltip_query(){
			var querylayer = "";
			var querylayer_id;
			if(doing == "ppquery" && mouse_down == false && tooltipstate == "ready_for_request" && prevent != 1){ 		// wenn bereit fuer Request
				if(Math.abs(oldmousex-mousex) < 1 && Math.abs(oldmousey-mousey) < 1){		// Maus stillhalten
					tooltipstate = "request_sent";
					for(i = 0; i < layerset.length; i++){
						if(layerset[i] != undefined && layerset[i].checked){
							querylayer += "&qLayer"+layernumber[i]+"=1";
						}
					}
					counter++;
					path = mousex+","+mousey+";"+mousex+","+mousey;
					top.ahah("index.php", "go=tooltip_query&INPUT_COORD="+path+"&CMD=ppquery"+querylayer+"&counter="+counter, new Array(top.document.GUI.result, \'\'), new Array(\'setvalue\', \'execute_function\'));
				}
				oldmousex = mousex;
				oldmousey = mousey;
			}
		}	
';

?>
