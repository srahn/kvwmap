<?php
  include(LAYOUTPATH.'snippets/ahah.php');
  echo $ahah;
?>
<script language="javascript" type="text/javascript">

function Bestaetigung(link,text) {
	Check = confirm(text);
	if (Check == true) {
		window.location.href = link;
	}
}

function ImageLoadFailed(id) {
  document.getElementById(id).innerHTML = '';
}

var currentform;
var doit;

function preventSubmit(){
	document.GUI.onsubmit = function(){return false;};
}

function allowSubmit(){
	document.GUI.onsubmit = function(){};
}

function printMap(){
	if(typeof addRedlining != 'undefined'){
		addRedlining();
	}
	document.GUI.go.value = 'Druckausschnittswahl';
	document.GUI.submit();
}

function checkForUnsavedChanges(event){
	var sure = true;
	if(document.GUI.gle_changed.value == 1){
		sure = confirm('Es gibt noch ungespeicherte Datensätze. Wollen Sie dennoch fortfahren?');
	}
	if(!sure){
		if(event != undefined)event.preventDefault();
		preventSubmit();
	}
	else{
		document.GUI.gle_changed.value = 0;
		allowSubmit();
	}
	return sure;
}

function startwaiting(lock) {
	var lock = lock || false;
	document.GUI.stopnavigation.value = 1;
	waitingdiv = document.getElementById('waitingdiv');
	waitingdiv.style.display='';
	if(lock)waitingdiv.className='waitingdiv_spinner_lock';
	else waitingdiv.className='waitingdiv_spinner';
}

function stopwaiting() {
	document.GUI.stopnavigation.value = 0;
	waitingdiv = document.getElementById('waitingdiv');
	waitingdiv.style.display='none';
}

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

/*
* Function create content to show messages of different types
* in div message_box
* @param array or string messages contain the messages as array
* or as a single string
*/
function message(messages, t_hide, t_hidden) {
	if (typeof(t_hide) === 'undefined') t_hide = 3000;
	if (typeof(t_hidden) === 'undefined') t_hidden = 3000;
	var msgDiv = $("#message_box");
	types = {
		'notice': {
			'description': 'Erfolg',
			'icon': 'fa-check',
			'color': 'green',
			'confirm': false
		},
		'info': {
			'description': 'Info',
			'icon': 'fa-info-circle',
			'color': '#ff6200',
			'confirm': true
		},
		'warning': {
			'description': 'Warnung',
			'icon': 'fa-exclamation',
			'color': 'firebrick',
			'confirm': true
		},
		'error': {
			'description': 'Fehler',
			'icon': 'fa-ban',
			'color': 'red',
			'confirm': true
		}
	},
	confirmMsgDiv = false;

	if (!$.isArray(messages)) {
		messages = [{
			'type': 'warning',
			'msg': messages
		}];
	}

	msgDiv.html('');

	$.each(messages, function (index, msg) {
		msg.type = (['notice', 'info', 'error'].indexOf(msg.type) > -1 ? msg.type : 'warning');
		msgDiv.append('<div class="message-box-' + msg.type + '">' + (types[msg.type].icon ? '<div class="message-box-type"><i class="fa ' + types[msg.type].icon + '" style="color: ' + types[msg.type].color + '; cursor: default;"></i></div>' : '') + '<div class="message-box-msg">' + msg.msg + '</div><div style="clear: both"></div></div>');
		if (types[msg.type].confirm) {
			confirmMsgDiv = true;
		}
	});

	msgDiv.attr('class', 'message_box');

	if (!confirmMsgDiv) {
		msgDiv.fadeOut(t_hide);
	}
	else {
		msgDiv.append('<input type="button" onclick="$(\'#message_box\').addClass(\'message_box_hidden\');" value="ok" style="margin-top: 10px;">');
	}
}

function onload_functions(){
	<? if($this->scrolldown){ ?>
	window.scrollTo(0,document.body.scrollHeight);	
	<? } ?>
	document.onmousemove = drag;
  document.onmouseup = dragstop;
	document.onmousedown = stop;
	getBrowserSize();
	<? if($this->user->rolle->auto_map_resize){ ?>
	window.onresize = function(){clearTimeout(doit);doit = setTimeout(resizemap2window, 200);};
	<? } ?>
	document.fullyLoaded = true;
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
	if(document.fullyLoaded){
		dragobjekt = element;
		dragx = posx - dragobjekt.offsetLeft;
		dragy = posy - dragobjekt.offsetTop;
	}
}

function resizestart(element, type){
	if(document.fullyLoaded){
		resizeobjekt = element;
		resizetype = type;
		dragx = posx - resizeobjekt.parentNode.offsetLeft;
		dragy = posy - resizeobjekt.parentNode.offsetTop;
		resizex = posx;
		resizey = posy;
		info = resizeobjekt.getBoundingClientRect();
		width = parseInt(info.width);
		height = parseInt(info.height);
	}
}


function dragstop(){
	if(dragobjekt){
		document.GUI.overlayx.value = parseInt(dragobjekt.style.left);
		document.GUI.overlayy.value = parseInt(dragobjekt.style.top);
		if(document.GUI.overlayx.value < 0)document.GUI.overlayx.value = 10;
		if(window.innerHeight - 20 - document.GUI.overlayy.value < 0)document.GUI.overlayy.value = window.innerHeight - 20;
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
			case "col_resize":
				resizeobjekt.style.minWidth = width + (posx - resizex) + "px";
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
	if(checkForUnsavedChanges()){
		document.getElementById('contentdiv').scrollTop = 0;
		document.getElementById('overlaydiv').style.display='none';
	}
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
	startwaiting();
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

function getlegend(groupid, layerid, fremde) {
	groupdiv = document.getElementById('groupdiv_' + groupid);
	if (layerid == '') {														// eine Gruppe wurde auf- oder zugeklappt
		group = document.getElementById('group_' + groupid);
		if (group.value == 0) {												// eine Gruppe wurde aufgeklappt -> Layerstruktur per Ajax holen
			group.value = 1;
			ahah('index.php', 'go=get_group_legend&' + group.name + '=' + group.value + '&group=' + groupid + '&nurFremdeLayer=' + fremde, new Array(groupdiv), "");
		}
		else {																// eine Gruppe wurde zugeklappt -> Layerstruktur verstecken und Einstellung per Ajax senden
			group.value = 0;
			layergroupdiv = document.getElementById('layergroupdiv_' + groupid);
			groupimg = document.getElementById('groupimg_' + groupid);
			layergroupdiv.style.display = 'none';
			groupimg.src = 'graphics/plus.gif';
			ahah('index.php', 'go=close_group_legend&' + group.name + '=' + group.value, '', '');
		}
	}
	else {																	// eine Klasse wurde auf- oder zugeklappt
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
	if(reload)neuLaden();
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
	if(instantreload)neuLaden();
}

function neuLaden(){
	currentform.neuladen.value='true';
	overlay_submit(currentform);
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
	if(instantreload)neuLaden();
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
	if(instantreload)neuLaden();
}

function zoomToMaxLayerExtent(zoom_layer_id){
	console.log(currentform.go.value);
	currentform.zoom_layer_id.value = zoom_layer_id;
	overlay_submit(currentform);
}

function getLayerOptions(layer_id){
	if(document.GUI.layer_options_open.value != '')closeLayerOptions(document.GUI.layer_options_open.value);
	ahah('index.php', 'go=getLayerOptions&layer_id=' + layer_id, new Array(document.getElementById('options_'+layer_id), ''), new Array('sethtml', 'execute_function'));
	document.GUI.layer_options_open.value = layer_id;
}

function getGroupOptions(group_id) {
	if (document.GUI.group_options_open.value != '') closeGroupOptions(document.GUI.group_options_open.value);
	ahah('index.php', 'go=getGroupOptions&group_id=' + group_id, new Array(document.getElementById('group_options_' + group_id), ''), new Array('sethtml', 'execute_function'));
	document.GUI.group_options_open.value = group_id;
}

function closeLayerOptions(layer_id){
	document.GUI.layer_options_open.value = '';
	document.getElementById('options_'+layer_id).innerHTML=' ';
}

function closeGroupOptions(group_id) {
	document.GUI.group_options_open.value = '';
	document.getElementById('group_options_' + group_id).innerHTML = ' ';
}

function saveLayerOptions(layer_id){	
	document.GUI.go.value = 'saveLayerOptions';
	document.GUI.submit();
}

function resetLayerOptions(layer_id){	
	document.GUI.go.value = 'resetLayerOptions';
	document.GUI.submit();
}

function openLegendOptions(){
	document.getElementById('legendOptions').style.display = 'inline-block';
}

function closeLegendOptions(){
	document.getElementById('legendOptions').style.display = 'none';
}

function saveLegendOptions(){
	document.GUI.go.value = 'saveLegendOptions';
	document.GUI.submit();
}

function resetLegendOptions(){
	document.GUI.go.value = 'resetLegendOptions';
	document.GUI.submit();
}

function toggleDrawingOrderForm(){
	drawingOrderForm = document.getElementById('drawingOrderForm');
	if(drawingOrderForm.innerHTML == ''){
		ahah('index.php', 'go=loadDrawingOrderForm', new Array(drawingOrderForm), new Array('sethtml'));
	}
	else{
		drawingOrderForm.innerHTML = '';
	}
}


// --- html5 Drag and Drop der Layer im drawingOrderForm --- //
 
var dragSrcEl = null;

function handleDragStart(e){
	var dropzones = document.querySelectorAll('#drawingOrderForm .drawingOrderFormDropZone');
	[].forEach.call(dropzones, function (dropzone){		// DropZones groesser machen
    dropzone.classList.add('ready');
  });
	dragSrcEl = e.target;
  if(browser == 'firefox')e.dataTransfer.setData('text/html', null);	
	dragSrcEl.classList.add('dragging');
	setTimeout(function(){dragSrcEl.classList.add('picked');}, 1);
}

function handleDragOver(e){
  if(e.preventDefault)e.preventDefault();
  e.dataTransfer.dropEffect = 'move';
  return false;
}

function handleDragEnter(e){
  e.target.classList.add('over');
}

function handleDragLeave(e){
  e.target.classList.remove('over');
}

function handleDrop(e){
  if (e.stopPropagation)e.stopPropagation();
	dstDropZone = e.target;
	srcDropZone = dragSrcEl.nextElementSibling;
	dstDropZone.classList.remove('over');
	dragSrcEl.classList.remove('dragging');
	dragSrcEl.classList.remove('picked');
	if(srcDropZone != dstDropZone){
		dragSrcEl.parentNode.insertBefore(dragSrcEl, dstDropZone);		// layer verschieben
		dragSrcEl.parentNode.insertBefore(srcDropZone, dragSrcEl);		// dropzone verschieben
	}
  return false;
}

function handleDragEnd(e){
	dragSrcEl.classList.remove('dragging');
	dragSrcEl.classList.remove('picked');
	var dropzones = document.querySelectorAll('#drawingOrderForm .drawingOrderFormDropZone');
	[].forEach.call(dropzones, function (dropzone){		// DropZones kleiner machen
    dropzone.classList.remove('ready');
  });
}

// --- html5 Drag and Drop der Layer im drawingOrderForm --- //
 

<?
	if($this->user->rolle->legendtype == 1){ # alphabetisch sortierte Legende
		echo 'layernames = new Array();';
		$layercount = count($this->sorted_layerset);
		for($j = 0; $j < $layercount; $j++){
			echo 'layernames['.$j.'] = \''.$this->sorted_layerset[$j]['alias'].'\';';
		}
?>
		function jumpToLayer(searchtext){
			if(searchtext.length > 1){
				found = false;
				legend_top = document.getElementById('scrolldiv').getBoundingClientRect().top;
				for(var i = 0; i < layernames.length; i++){
					if(layernames[i].toLowerCase().search(searchtext.toLowerCase()) != -1){
						layer = document.getElementById(layernames[i].replace('-', '_'));
						layer.classList.remove('legend_layer_highlight');
						void layer.offsetWidth;
						layer.classList.add('legend_layer_highlight');
						if(!found){
							document.getElementById('scrolldiv').style.scrollBehavior = 'smooth';		// erst hier und nicht im css, damit das Scrollen beim Laden nicht animiert wird
							document.getElementById('scrolldiv').scrollTop = document.getElementById('scrolldiv').scrollTop + (layer.getBoundingClientRect().top - legend_top);
						}
						found = true;
					}
				}
			}
		}
<?
	}
?>

function slide_legend_in(evt) {
	document.getElementById('legenddiv').className = 'slidinglegend_slidein';
}

function slide_legend_out(evt) {
	if(window.outerWidth - evt.pageX > 100) {
		document.getElementById('legenddiv').className = 'slidinglegend_slideout';
	}
}

function switchlegend(){
	if (document.getElementById('legenddiv').className == 'normallegend') {
		document.getElementById('legenddiv').className = 'slidinglegend_slideout';
		ahah('index.php', 'go=changeLegendDisplay&hide=1', new Array('', ''), new Array("", "execute_function"));
		document.getElementById('LegendMinMax').src='<?php echo GRAPHICSPATH; ?>maximize_legend.png';
		document.getElementById('LegendMinMax').title="Legende zeigen";
	}
	else {
		document.getElementById('legenddiv').className = 'normallegend';
		ahah('index.php', 'go=changeLegendDisplay&hide=0', new Array('', ''), new Array("", "execute_function"));
		document.getElementById('LegendMinMax').src='<?php echo GRAPHICSPATH; ?>minimize_legend.png';
		document.getElementById('LegendMinMax').title="Legende verstecken";
	}
}

function home() {
	document.GUI.go.value = '';
	document.GUI.submit();
}

function scrollLayerOptions(){
	layer_id = document.GUI.layer_options_open.value;
	if(layer_id != ''){
		legend_top = document.getElementById('legenddiv').getBoundingClientRect().top;
		legend_bottom = document.getElementById('legenddiv').getBoundingClientRect().bottom;
		posy = document.getElementById('options_'+layer_id).getBoundingClientRect().top;
		if(posy < legend_bottom - 180 && posy > legend_top + 10)document.getElementById('options_content_'+layer_id).style.top = posy - (13+legend_top);		
	}
}

function activateAllClasses(class_ids){
	var classids = class_ids.split(",");
	for(i = 0; i < classids.length; i++){
		selClass = document.getElementsByName("class"+classids[i])[0];
		if(selClass != undefined)selClass.value = 1;
	}
	overlay_submit(currentform);
}

function deactivateAllClasses(class_ids){
	var classids = class_ids.split(",");
	for(i = 0; i < classids.length; i++){
		selClass = document.getElementsByName("class"+classids[i])[0];
		if(selClass != undefined)selClass.value = 0;
	}
	overlay_submit(currentform);
}

/*Anne*/
function changeClassStatus(classid,imgsrc,instantreload,width,height){
	selClass = document.getElementsByName("class"+classid)[0];
	selImg   = document.getElementsByName("imgclass"+classid)[0];
	if(height < width)height = 12;
	else height = 18;
	if(selClass.value=='0'){
		selClass.value='1';
		selImg.src=imgsrc;
	}else if(selClass.value=='1'){
		selClass.value='2';
		selImg.src="graphics/outline"+height+".jpg";
	}else if(selClass.value=='2'){
		selClass.value='0';
		selImg.src="graphics/inactive"+height+".jpg";
	}
	if(instantreload)neuLaden();
}

/*Anne*/
function mouseOverClassStatus(classid,imgsrc,width,height){
	selClass = document.getElementsByName("class"+classid)[0];
	selImg   = document.getElementsByName("imgclass"+classid)[0];
	if(height < width)height = 12;
	else height = 18;
	if(selClass.value=='0'){
		selImg.src=imgsrc;	
	}else if(selClass.value=='1'){
		selImg.src="graphics/outline"+height+".jpg";
	}else if(selClass.value=='2'){
		selImg.src="graphics/inactive"+height+".jpg";
	}
}

/*Anne*/
function mouseOutClassStatus(classid,imgsrc,width,height){
	selClass = document.getElementsByName("class"+classid)[0];
	selImg   = document.getElementsByName("imgclass"+classid)[0];
	if(height < width)height = 12;
	else height = 18;	
	if(selClass.value=='0'){
		selImg.src="graphics/inactive"+height+".jpg";	
	}else if(selClass.value=='1'){
		selImg.src=imgsrc;
	}else if(selClass.value=='2'){
		selImg.src="graphics/outline"+height+".jpg";
	}
}

function showMapParameter(epsg_code, width, height) {
	var gui = document.GUI,
			msg = " \
				<div style=\"text-align: left\"> \
					<h2>Daten des aktuellen Kartenausschnitts</h2><br> \
					Koordinatenreferenzsystem: EPSG: " + epsg_code + "<br> \
					linke untere Ecke: (" + toFixed(gui.minx.value, 3) + ", " + toFixed(gui.miny.value, 3) + ")<br> \
					rechte obere Ecke: (" + toFixed(gui.maxx.value, 3) + ", " + toFixed(gui.maxy.value, 3) + ")<br> \
					Ausdehnung: " + toFixed(gui.maxx.value - gui.minx.value, 3) + " x " + toFixed(gui.maxy.value-gui.miny.value,3) + " m<br> \
					Bildgröße: " + width + " x " + height + " Pixel<br> \
					Pixelgröße: " + toFixed(gui.pixelsize.value, 3) + " m\
				</div> \
			";
	message([{
			'type': 'info',
			'msg': msg
	}]);
}

function showExtentURL(epsg_code) {
	var gui = document.GUI,
			msg = " \
				<div style=\"text-align: left\"> \
					<h2>URL des aktuellen Kartenausschnitts</h2><br> \
					<input id=\"extenturl\" style=\"width: 350px\" type=\"text\" value=\"<? echo URL.APPLVERSION; ?>index.php?go=zoom2coord&INPUT_COORD="+toFixed(gui.minx.value, 3)+","+toFixed(gui.miny.value, 3)+";"+toFixed(gui.maxx.value, 3)+","+toFixed(gui.maxy.value, 3)+"&epsg_code="+epsg_code+"\"><br> \
				</div> \
			";
	message([{
			'type': 'info',
			'msg': msg
	}]);
	document.getElementById('extenturl').select();
}

function toFixed(value, precision) {
	var power = Math.pow(10, precision || 0);
	return String(Math.round(value * power) / power);
}

function exportMapImage(target) {
	var link = document.GUI.hauptkarte.value;
	console.log(link);
	if (target != '') {
		window.open(link, target);
	}
	else {
		location.href = link;
	}
}
</script>
