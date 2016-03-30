<?php
  include(LAYOUTPATH.'snippets/ahah.php');
  echo $ahah;
?>
<script language="javascript" type="text/javascript">

function ImageLoadFailed(id) {
  document.getElementById(id).innerHTML = '';
}

var currentform;
var doit;

function getBrowserSize(){
	if(typeof(window.innerWidth) == 'number'){
		width = window.innerWidth;
		height = window.innerHeight;
	}else if(document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)){
		width = document.documentElement.clientWidth;
		height = document.documentElement.clientHeight;
	}else if(document.body && (document.body.clientWidth || document.body.clientHeight)){
		width = document.body.clientWidth;
		height = document.body.clientHeight;
	}
	document.GUI.browserwidth.value = width;
	document.GUI.browserheight.value = height;
}

function resizemap2window(){
	getBrowserSize();
	params = 'go=ResizeMap2Window&browserwidth='+document.GUI.browserwidth.value+'&browserheight='+document.GUI.browserheight.value;
<? if($this->main == 'map.php'){ ?>
	startwaiting();
	document.location.href='index.php?'+params+'&nScale='+document.GUI.nScale.value+'&reloadmap=true';			// in der Hauptkarte neuladen
<? }else{ ?>
	ahah('index.php', params, new Array(''), new Array(''));																								// ansonsten nur die neue Mapsize setzen
<? } ?>
}

function message(text){
	var Msg = document.getElementById("message_box");
	if(Msg == undefined){
		document.write('<div id="message_box" class="message_box_hidden"></div>');
		var Msg = document.getElementById("message_box");
	}
	Msg.className = 'message_box_visible';
	Msg.innerHTML = text;
	setTimeout(function() {Msg.className = 'message_box_hide';},500);
	setTimeout(function() {Msg.className = 'message_box_hidden';},2500);
}

function onload_functions(){
	<? if($this->scrolldown){ ?>
	window.scrollTo(0,document.body.scrollHeight);	
	<? } ?>
	if(document.getElementById('scrolldiv') != undefined){
		document.getElementById('scrolldiv').scrollTop = <? echo $this->user->rolle->scrollposition; ?>;
	}
	document.onmousemove = drag;
  document.onmouseup = dragstop;
	document.onmousedown = stop;
	getBrowserSize();
	<? if($this->user->rolle->auto_map_resize){ ?>
	window.onresize = function(){clearTimeout(doit);doit = setTimeout(resizemap2window, 200);};
	<? } ?>
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
		preventDefault(event);
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
	height = parseInt(resizeobjekt.offsetHeight);	// da style.height auf 100% steht
}


function dragstop(){
	if(dragobjekt){
		document.GUI.overlayx.value = parseInt(dragobjekt.style.left);
		document.GUI.overlayy.value = parseInt(dragobjekt.style.top);
		ahah('index.php', 'go=saveOverlayPosition&overlayx='+document.GUI.overlayx.value+'&overlayy='+document.GUI.overlayy.value, new Array(''), new Array(""));
	}
  dragobjekt = null;
	resizeobjekt = null;
}


function drag(event) {
	if(!event)event = window.event; // IE sucks
  posx =  event.screenX;
  posy = event.screenY;
  if(dragobjekt != null){
    dragobjekt.style.left = (posx - dragx) + "px";
    if(posy - dragy > 0)dragobjekt.style.top = (posy - dragy) + "px";
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
	document.getElementById('contentdiv').scrollTop = 0;
	overlay = document.getElementById('overlaydiv');
	overlay.style.left = document.GUI.overlayx.value+'px';
	overlay.style.top = document.GUI.overlayy.value+'px';
	overlay.style.display='';
	if(document.SVG != undefined){
		svgdoc = document.SVG.getSVGDocument();	
		if(svgdoc != undefined)svgdoc.getElementById('polygon').setAttribute("points", "");
	}
}

function deactivate_overlay(){
	document.getElementById('contentdiv').scrollTop = 0;
	document.getElementById('overlaydiv').style.display='none';
}

function urlstring2formdata(formdata, string){
	kvpairs = string.split('&');
	for(i = 0; i < kvpairs.length; i++) {
    el = kvpairs[i].split(/=(.+)/);		// nur das erste "=" zum splitten nehmen
		formdata.append(el[0], el[1]);	
	}
	return formdata;
}

function overlay_submit(gui, start){
	// diese Funktion macht beim Fenstermodus und einer Kartenabfrage oder einem Aufruf aus dem Overlay-Fenster einen ajax-Request mit den Formulardaten des uebergebenen Formularobjektes, ansonsten einen normalen Submit
	if(typeof FormData !== 'undefined' && (1 == <? echo $this->user->rolle->querymode; ?> && start || gui.id == 'GUI2')){	
		formdata = new FormData(gui);
		formdata.append("mime_type", "overlay_html");	
		ahah("index.php", formdata, new Array(document.getElementById('contentdiv')), new Array("sethtml"));	
		if(document.GUI.CMD != undefined)document.GUI.CMD.value = "";
	}else{
		document.GUI.submit();
	}
}

function overlay_link(data){
	// diese Funktion macht bei Aufruf aus dem Overlay-Fenster einen ajax-Request mit den übergebenen Daten, ansonsten wird das Ganze wie ein normaler Link aufgerufen
	if(currentform.name == 'GUI2'){
		ahah("index.php", data+"&mime_type=overlay_html", new Array(document.getElementById('contentdiv')), new Array("sethtml"));	
		if(document.GUI.CMD != undefined)document.GUI.CMD.value = "";
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
		if((parts[j] == 'reload')||																																																								// wenn Legenden-Reload erzwungen wird oder
			(document.getElementById('thema_'+parts[j]) != undefined && document.getElementById('thema_'+parts[j]).disabled && parts[j+1] == 0) || 	// wenn Layer nicht sichtbar war und jetzt sichtbar ist
			(document.getElementById('thema_'+parts[j]) != undefined && !document.getElementById('thema_'+parts[j]).disabled && parts[j+1] == 1)){	// oder andersrum
			legende = document.getElementById('legend');
			ahah('index.php', 'go=get_legend', new Array(legende), "");
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
			ahah('index.php', 'go=get_group_legend&'+group.name+'='+group.value+'&group='+groupid+'&nurFremdeLayer='+fremde, new Array(groupdiv), "");
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
		ahah('index.php', 'go=get_group_legend&layer_id='+layerid+'&show_classes='+layer.value+'&group='+groupid+'&nurFremdeLayer='+fremde, new Array(groupdiv), "");
	}
}

function updateThema(event, thema, query, groupradiolayers, queryradiolayers, instantreload){
	var status = query.checked;
	var reload = false;
  if(status == true){
    if(thema.checked == false){
			thema.checked = true;
			thema.title="<? echo $this->deactivatelayer; ?>";	
			if(instantreload)reload = true;
		}
		query.title="<? echo $this->deactivatequery; ?>";		
  }
	else{
		query.title="<? echo $this->activatequery; ?>";
	}
  if(groupradiolayers != '' && groupradiolayers.value != ''){
    preventDefault(event);
		groupradiolayerstring = groupradiolayers.value+'';			// die Radiolayer innerhalb einer Gruppe
		radiolayer = groupradiolayerstring.split('|');
		for(i = 0; i < radiolayer.length-1; i++){
			if(document.getElementById('thema_'+radiolayer[i]) != undefined){
				if(document.getElementById('thema_'+radiolayer[i]) != thema){
					document.getElementById('thema_'+radiolayer[i]).checked = false;
					if(document.getElementById('qLayer'+radiolayer[i]) != undefined){
						document.getElementById('qLayer'+radiolayer[i]).checked = false;
					}
				}
				else{
					query.checked = !status;
					query.checked2 = query.checked;		// den check-Status hier nochmal merken, damit man ihn bei allen Click-Events setzen kann, sonst setzt z.B. Chrome den immer wieder zurueck
					if(query.checked == true){
						if(thema.checked == false){
							thema.checked = true;
							thema.title="<? echo $this->deactivatelayer; ?>";	
							if(instantreload)reload = true;
						}
					}
				}
			}
		}
	}
	if(queryradiolayers != '' && queryradiolayers.value != ''){
    preventDefault(event);
		queryradiolayerstring = queryradiolayers.value+'';			// die Radiobuttons für die Abfrage, wenn singlequery-Modus aktiviert
		radiolayer = queryradiolayerstring.split('|');
		for(i = 0; i < radiolayer.length-1; i++){
			if(document.getElementById('thema_'+radiolayer[i]) != undefined){
				if(document.getElementById('thema_'+radiolayer[i]) != thema){
					if(document.getElementById('qLayer'+radiolayer[i]) != undefined)document.getElementById('qLayer'+radiolayer[i]).checked = false;
				}
				else{
					query.checked = !status;
					query.checked2 = query.checked;		// den check-Status hier nochmal merken, damit man ihn bei allen Click-Events setzen kann, sonst setzt z.B. Chrome den immer wieder zurueck
					if(query.checked == true){
						if(thema.checked == false){
							thema.checked = true;
							thema.title="<? echo $this->deactivatelayer; ?>";	
							if(instantreload)reload = true;
						}
					}
				}
			}
		}
  }
	if(reload)document.GUI.neuladen.click();
}

function updateQuery(event, thema, query, radiolayers, instantreload){
  if(query){
    if(thema.checked == false){
      query.checked = false;
			thema.title="<? echo $this->activatelayer; ?>";
			query.title="<? echo $this->activatequery; ?>";
    }
		else{
			thema.title="<? echo $this->deactivatelayer; ?>";			
		}
  }
  if(radiolayers != '' && radiolayers.value != ''){  
  	preventDefault(event);
  	radiolayerstring = radiolayers.value+'';
  	radiolayer = radiolayerstring.split('|');
  	for(i = 0; i < radiolayer.length-1; i++){
  		if(document.getElementById('thema_'+radiolayer[i]) != thema){
  			document.getElementById('thema_'+radiolayer[i]).checked = false;
				document.getElementById('thema'+radiolayer[i]).value = 0;		// damit nicht sichtbare Radiolayers ausgeschaltet werden
  		}
  		else{
  			thema.checked = !thema.checked;
				thema.checked2 = thema.checked;		// den check-Status hier nochmal merken, damit man ihn bei allen Click-Events setzen kann, sonst setzt z.B. Chrome den immer wieder zurueck
  		}
  		if(document.getElementById('qLayer'+radiolayer[i]) != undefined){
  			document.getElementById('qLayer'+radiolayer[i]).checked = false;
  		}
  	}
  }
	if(instantreload)document.GUI.neuladen.click();
}

function preventDefault(e){
	if(e.preventDefault){
		e.preventDefault();
	}else{ // IE fix
		e.returnValue = false;
	};
	return false;
}

function selectgroupquery(group, instantreload){
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
      updateThema('', thema, query, '', '', 0);
    }
  }
	if(instantreload)document.GUI.neuladen.click();
}

function selectgroupthema(group, instantreload){
  var value = group.value+"";
  var layers = value.split(",");
	var check;
  for(i = 0; i < layers.length; i++){			// erst den ersten checkbox-Layer suchen und den check-Status merken
    thema = document.getElementById("thema_"+layers[i]);
    if(thema && thema.type == 'checkbox'){
			check = !thema.checked;
			break;
    }
  }
	for(i = 0; i < layers.length; i++){
    thema = document.getElementById("thema_"+layers[i]);
    if(thema && (!check || thema.type == 'checkbox')){		// entweder alle Layer sollen ausgeschaltet werden oder es ist ein checkbox-Layer
      thema.checked = check;
      query = document.getElementById("qLayer"+layers[i]);
      updateQuery('', thema, query, '', 0);
    }
  }
	if(instantreload)document.GUI.neuladen.click();
}

function zoomToMaxLayerExtent(zoom_layer_id){
	console.log(currentform.go.value);
	currentform.zoom_layer_id.value = zoom_layer_id;
	overlay_submit(currentform);
}

/*Anne*/
function changeClassStatus(classid,imgsrc,instantreload){
	selClass = document.getElementsByName("class"+classid)[0];
	selImg   = document.getElementsByName("imgclass"+classid)[0];
	if(selClass.value=='0'){
		selClass.value='1';
		selImg.src=imgsrc;
	}else if(selClass.value=='1'){
		selClass.value='2';
		selImg.src="graphics/outline.jpg";
	}else if(selClass.value=='2'){
		selClass.value='0';
		selImg.src="graphics/inactive.jpg";
	}
	if(instantreload)document.GUI.neuladen.click();
}

/*Anne*/
function mouseOverClassStatus(classid,imgsrc){
	selClass = document.getElementsByName("class"+classid)[0];
	selImg   = document.getElementsByName("imgclass"+classid)[0];
	if(selClass.value=='0'){
		selImg.src=imgsrc;	
	}else if(selClass.value=='1'){
		selImg.src="graphics/outline.jpg";
	}else if(selClass.value=='2'){
		selImg.src="graphics/inactive.jpg";
	}
}

/*Anne*/
function mouseOutClassStatus(classid,imgsrc){
	selClass = document.getElementsByName("class"+classid)[0];
	selImg   = document.getElementsByName("imgclass"+classid)[0];
	if(selClass.value=='0'){
		selImg.src="graphics/inactive.jpg";	
	}else if(selClass.value=='1'){
		selImg.src=imgsrc;
	}else if(selClass.value=='2'){
		selImg.src="graphics/outline.jpg";
	}
}

</script>
