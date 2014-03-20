<?php
  include(LAYOUTPATH.'snippets/ahah.php');
  echo $ahah;
?>
<script language="javascript" type="text/javascript">

function ImageLoadFailed(id) {
  document.getElementById(id).innerHTML = '';
}

var currentform;

function onload_functions(){
	if(document.getElementById('scrolldiv') != undefined){
		document.getElementById('scrolldiv').scrollTop = <? echo $this->user->rolle->scrollposition; ?>;
	}
	document.onmousemove = drag;
  document.onmouseup = dragstop;
	document.onmousedown = stop;	
}

var dragobjekt = null;
var resizeobjekt = null;
var resizetype = null;

// Position, an der das Objekt angeklickt wurde.
var dragx = 0;
var dragy = 0;
var resizex = 0;
var resizey = 0;
// Breite und Hoehe
var width = 0;
var height = 0;

// Mausposition
var posx = 0;
var posy = 0;

function stop(event){
	if(dragobjekt != null || resizeobjekt != null){		// markieren von Elementen verhindern, falls Mauszeiger aus Overlay gezogen wird
		if(event.preventDefault){
			event.preventDefault();
		}else{ // IE fix
			event.returnValue = false;
		};
	}
}

function dragstart(element){
  dragobjekt = element;
  dragx = posx - dragobjekt.offsetLeft;
  dragy = posy - dragobjekt.offsetTop;
}

function resizestart(element, type){
	resizeobjekt = element;
	resizetype = type;
	dragx = posx - resizeobjekt.parentNode.offsetLeft;
  dragy = posy - resizeobjekt.parentNode.offsetTop;
  resizex = posx;
  resizey = posy;
	width = parseInt(resizeobjekt.offsetWidth);		// da style.width auf 100% steht
	height = parseInt(resizeobjekt.style.height);
}


function dragstop(){
  dragobjekt = null;
	resizeobjekt = null;
}


function drag(event) {
	if(!event)event = window.event; // IE sucks
  posx =  event.screenX;
  posy = event.screenY;
  if(dragobjekt != null){				
    dragobjekt.style.left = (posx - dragx) + "px";
    dragobjekt.style.top = (posy - dragy) + "px";
  }
	if(resizeobjekt != null){				
		switch(resizetype) {
			case "se":
				resizeobjekt.style.width = width + (posx - resizex) + "px";
				resizeobjekt.style.height = height + (posy - resizey) + "px";
			break;
			case "ne":
				resizeobjekt.style.width = width + (posx - resizex) + "px";
				resizeobjekt.style.height = height - (posy - resizey) + "px";
				resizeobjekt.parentNode.style.top = (posy - dragy) + "px";
			break;
			case "nw":
				resizeobjekt.style.width = width - (posx - resizex) + "px";
				resizeobjekt.style.height = height - (posy - resizey) + "px";
				resizeobjekt.parentNode.style.left = (posx - dragx) + "px";
				resizeobjekt.parentNode.style.top = (posy - dragy) + "px";
			break;
			case "sw":
				resizeobjekt.style.width = width - (posx - resizex) + "px";
				resizeobjekt.style.height = height + (posy - resizey) + "px";
				resizeobjekt.parentNode.style.left = (posx - dragx) + "px";
			break;
			case "s":
				resizeobjekt.style.height = height + (posy - resizey) + "px";
			break;
			case "n":
				resizeobjekt.style.height = height - (posy - resizey) + "px";
				resizeobjekt.parentNode.style.top = (posy - dragy) + "px";
			break;
			case "w":
				resizeobjekt.style.width = width - (posx - resizex) + "px";
				resizeobjekt.parentNode.style.left = (posx - dragx) + "px";
			break;
			case "e":
				resizeobjekt.style.width = width + (posx - resizex) + "px";
			break;
		}
  }
}

function activate_overlay(){
	document.getElementById('overlaydiv').style.display='';
	svgdoc = document.SVG.getSVGDocument();	
	svgdoc.getElementById('polygon').setAttribute("points", "");
}

function deactivate_overlay(){
	document.getElementById('contentdiv').scrollTop = 0;
	document.getElementById('overlaydiv').style.display='none';
}

function overlay_submit(gui, start){
	// diese Funktion macht beim Fenstermodus und einer Kartenabfrage oder einem Aufruf aus dem Overlay-Fenster einen ajax-Request mit den Formulardaten des uebergebenen Formularobjektes, ansonsten einen normalen Submit
	if(1 == <? echo $this->user->rolle->querymode; ?> && start || gui.name == 'GUI2'){
		formdata = formSerialize(gui);
		ahah("<? echo URL.APPLVERSION.'index.php'; ?>", formdata+"&mime_type=overlay_html", new Array(document.getElementById('contentdiv'), '', ''), new Array("sethtml", "execute_function", "execute_function"));	
		document.GUI.CMD.value = "";
	}else{
		document.GUI.submit();
	}
}

function overlay_link(data){
	// diese Funktion macht bei Aufruf aus dem Overlay-Fenster einen ajax-Request mit den übergebenen Daten, ansonsten wird das Ganze wie ein normaler Link aufgerufen
	if(currentform.name == 'GUI2'){
		ahah("<? echo URL.APPLVERSION.'index.php'; ?>", data+"&mime_type=overlay_html", new Array(document.getElementById('contentdiv'), '', ''), "sethtml~execute_function~execute_function");	
		document.GUI.CMD.value = "";
	}else{
		window.location.href = 'index.php?'+data;
	}
}

function datecheck(value){
	dateElements = value.split('.');
	var date1 = new Date(dateElements[2],dateElements[1]-1,dateElements[0]);
	if(date1 == 'Invalid Date')return false;
	else return date1;
}

function update_legend(layerhiddenstring){
	parts = layerhiddenstring.split(' ');
	for(j = 0; j < parts.length-1; j=j+2){
		if((document.getElementById('thema_'+parts[j]) != undefined && document.getElementById('thema_'+parts[j]).disabled && parts[j+1] == 0) || 	// wenn Layer nicht sichtbar war und jetzt sichtbar ist
			(document.getElementById('thema_'+parts[j]) != undefined && !document.getElementById('thema_'+parts[j]).disabled && parts[j+1] == 1)){		// oder andersrum
			legende = document.getElementById('legend');
			ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=get_legend', new Array(legende), "");
			break;
		}
	}
}

function getlegend(groupid, layerid, fremde){
	groupdiv = document.getElementById('groupdiv_'+groupid);
	if(layerid == ''){														// eine Gruppe wurde auf- oder zugeklappt
		group = document.getElementById('group_'+groupid);
		if(group.value == 0){												// eine Gruppe wurde aufgeklappt -> Layerstruktur per Ajax holen
			group.value = 1;
			ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=get_group_legend&'+group.name+'='+group.value+'&group='+groupid+'&nurFremdeLayer='+fremde, new Array(groupdiv), "");
		}
		else{																// eine Gruppe wurde zugeklappt -> Layerstruktur nur verstecken
			group.value = 0;
			layergroupdiv = document.getElementById('layergroupdiv_'+groupid);
			groupimg = document.getElementById('groupimg_'+groupid);
			layergroupdiv.style.display = 'none';			
			groupimg.src = 'graphics/plus.gif';
		}
	}
	else{																	// eine Klasse wurde auf- oder zugeklappt
		layer = document.getElementById('classes_'+layerid);
		if(layer.value == 0){
			layer.value = 1;
		}
		else{
			layer.value = 0;
		}
		ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=get_group_legend&'+layer.name+'='+layer.value+'&group='+groupid+'&nurFremdeLayer='+fremde, new Array(groupdiv), "");
	}
}

function updateThema(event, thema, query, groupradiolayers, queryradiolayers){
	var status = query.checked;
  if(status == true){
    thema.checked = true;
  }
  if(groupradiolayers != '' && groupradiolayers.value != ''){
    if(event.preventDefault){
			event.preventDefault();
		}else{ // IE fix
			event.returnValue = false;
		};
		groupradiolayerstring = groupradiolayers.value+'';			// die Radiolayer innerhalb einer Gruppe
		radiolayer = groupradiolayerstring.split('|');
		for(i = 0; i < radiolayer.length-1; i++){
			if(document.getElementById('thema_'+radiolayer[i]) != undefined){
				if(document.getElementById('thema_'+radiolayer[i]) != thema){
					document.getElementById('thema_'+radiolayer[i]).checked = false;
					if(document.getElementById('qLayer'+radiolayer[i]) != undefined)document.getElementById('qLayer'+radiolayer[i]).checked = false;
				}
				else{
					query.checked = !status;
					if(query.checked == true){
						thema.checked = true;
					}
				}
			}
		}
	}
	if(queryradiolayers != '' && queryradiolayers.value != ''){
    if(event.preventDefault){
			event.preventDefault();
		}else{ // IE fix
			event.returnValue = false;
		};
		queryradiolayerstring = queryradiolayers.value+'';			// die Radiobuttons für die Abfrage, wenn singlequery-Modus aktiviert
		radiolayer = queryradiolayerstring.split('|');
		for(i = 0; i < radiolayer.length-1; i++){
			if(document.getElementById('thema_'+radiolayer[i]) != undefined){
				if(document.getElementById('thema_'+radiolayer[i]) != thema){
					if(document.getElementById('qLayer'+radiolayer[i]) != undefined)document.getElementById('qLayer'+radiolayer[i]).checked = false;
				}
				else{
					query.checked = !status;
					if(query.checked == true){
						thema.checked = true;
					}
				}
			}
		}
  }  
}

function updateQuery(event, thema, query, radiolayers){
  if(query){
    if(thema.checked == false){
      query.checked = false;
    }
  }
  if(radiolayers != '' && radiolayers.value != ''){  
  	if(event.preventDefault){
			event.preventDefault();
		}else{ // IE fix
			event.returnValue = false;
		};
  	radiolayerstring = radiolayers.value+'';
  	radiolayer = radiolayerstring.split('|');
  	for(i = 0; i < radiolayer.length-1; i++){
  		if(document.getElementById('thema_'+radiolayer[i]) != thema){
  			document.getElementById('thema_'+radiolayer[i]).checked = false;
				document.getElementById('thema'+radiolayer[i]).value = 0;		// damit nicht sichtbare Radiolayers ausgeschaltet werden
  		}
  		else{
  			thema.checked = !thema.checked;
  		}
  		if(document.getElementById('qLayer'+radiolayer[i]) != undefined){
  			document.getElementById('qLayer'+radiolayer[i]).checked = false;
  		}
  	}
  }
}

function selectgroupquery(group){
  value = group.value+"";
  layers = value.split(",");
  i = 0;
  test = null;
  while(test == null){
    test = document.getElementById("qLayer"+layers[i]);
    i++;
    if(i > layers.length){
      return;
    }
  }
  check = !test.checked;
  for(i = 0; i < layers.length; i++){
    query = document.getElementById("qLayer"+layers[i]);
    if(query){
      query.checked = check;
      thema = document.getElementById("thema_"+layers[i]);
      updateThema('', thema, query, '', '');
    }
  }
}

function selectgroupthema(group){
  value = group.value+"";
  layers = value.split(",");
  test = document.getElementById("thema_"+layers[0]);
  check = !test.checked;
  for(i = 0; i < layers.length; i++){
    thema = document.getElementById("thema_"+layers[i]);
    if(thema && thema.type == 'checkbox'){
      thema.checked = check;
      query = document.getElementById("qLayer"+layers[i]);
      updateQuery('', thema, query, '');
    }
  }
}

/*Anne*/
function changeClassStatus(classid,imgsrc){
	selClass = document.getElementsByName("class"+classid)[0];
	selImg   = document.getElementsByName("imgclass"+classid)[0];
	if(selClass.value=='0'){
		selClass.value='1';
		selImg.src=imgsrc;
		
	} else if (selClass.value=='1'){
		selClass.value='0';
		selImg.src="graphics/inactive.jpg";
	}
}

/*Anne*/
function mouseOverClassStatus(classid,imgsrc){
	selClass = document.getElementsByName("class"+classid)[0];
	selImg   = document.getElementsByName("imgclass"+classid)[0];
	if(selClass.value=='0'){
		selImg.src=imgsrc;	
	} else if (selClass.value=='1'){
		selImg.src="graphics/inactive.jpg";
	}
}

/*Anne*/
function mouseOutClassStatus(classid,imgsrc){
	selClass = document.getElementsByName("class"+classid)[0];
	selImg   = document.getElementsByName("imgclass"+classid)[0];
	if(selClass.value=='0'){
		selImg.src="graphics/inactive.jpg";	
	} else if (selClass.value=='1'){
		selImg.src=imgsrc;
	}
}

</script>
