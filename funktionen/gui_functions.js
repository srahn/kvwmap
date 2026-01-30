var browser_string = navigator.userAgent.toLowerCase();

if(browser_string.indexOf('firefox') >= 0){
	var browser = 'firefox';
}	
else if(browser_string.indexOf('chrome') >= 0){	
	var browser = 'chrome';
}
else if (browser_string.indexOf('edg') >= 0){
	var browser = 'edge';
}
else{
	var browser = 'ie';
}

var query_tab;
var root = window;
root.resized = 0;
root.open_subform_requests = 0;
root.getlegend_requests = new Array();
var current_date = new Date().toLocaleString().replace(',', '');;
var new_hist_timestamp;
var loc = window.location.href.toString().split('index.php')[0];
var mapimg, mapimg0, mapimg3, mapimg4;
var compare_clipping = false;
var formdata = new FormData();
root.changed_form_fields = new Array();

window.addEventListener("beforeunload", function (e) {
	if (
			root.document &&
			root.document.GUI &&
			root.document.GUI.gle_changed.value == 1
	) {
			highlightChangedFormFields();
			// Browser-Warnung auslösen (eigener Text wird ignoriert)
			e.preventDefault();
			e.returnValue = "";
	}
});

window.addEventListener("unload", function () {
	try {
		if (window.opener && window.opener.document) {
			window.opener.document.GUI.gle_changed.value = 0;
		}
	} catch (err) {}
});

/*
* @param url string
* @param data siehe Doku von XMLHttpRequest (z.B. kvp's String)
* @param target array ['divname', ...]
* @param action array ['sethtml'. ...]
*/
function ahah(url, data, target, action, progress, loading_img = true) {
	if (csrf_token && csrf_token !== '') {
		if (typeof data == 'string') {
			data = data + '&csrf_token=' + csrf_token;
		}
		else {
			data.append('csrf_token', csrf_token);
		}
	}
	if (loading_img) {
		for (k = 0; k < target.length; ++k) {
			if (target[k] != null && target[k].tagName == "DIV") {
				waiting_img = document.createElement("img");
				waiting_img.src = "graphics/ajax-loader.gif";
				target[k].appendChild(waiting_img);
			}
		}
	}
	var req = new XMLHttpRequest();
	if (req != undefined) {
		req.onreadystatechange = function() {
			ahahDone(url, target, req, action);
		};
		if (typeof progress !== 'undefined') {
			req.upload.addEventListener("progress", progress);
		}
		req.open("POST", url, true);
		if (typeof data == "string") {
			req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=iso-8859-15"); // data kann entweder ein String oder ein FormData-Objekt sein
		}
		req.send(data);
	}
	return req;
}

function ahahDone(url, targets, req, actions) {
	if (req.readyState == 4) { // only if req is "loaded"
		if (req.getResponseHeader('error') == 'true'){
			message(req.responseText);
		}
		if (req.getResponseHeader('logout') == 'true') { // falls man zwischenzeitlich ausgeloggt wurde
			window.location = url;
			return;
		}		
		if (req.status == 200) { // only if "OK"
			if (req.getResponseHeader('warning') == 'true'){
				message([{ type: 'warning', msg: req.responseText}]);
			}
			var found = false;
			var response = "" + req.responseText;
			var responsevalues = response.split("█");
			if (actions == undefined || actions == "") {
				actions = new Array();
			}
			for (i = 0; i < targets.length; ++i) {
				if (targets[i] != undefined) {
					if (actions[i] == undefined) {
						actions[i] = "";
					}
					switch (actions[i]) {
						case "execute_function":
							eval(responsevalues[i]);
						break;

						case "src":
							targets[i].src = responsevalues[i];
						break;

						case "href":
							targets[i].setAttribute("href", responsevalues[i]);
						break;

						case "points":
							targets[i].setAttribute("points", responsevalues[i]);	
						break;

						case "sethtml":
							if (targets[i] != undefined && req.getResponseHeader('error') != 'true') {
								targets[i].innerHTML = responsevalues[i];
								scripts = targets[i].getElementsByTagName("script"); // Alle script-Bloecke evaln damit diese Funktionen bekannt sind
								for (s = 0; s < scripts.length; s++) {
									if (scripts[s].hasAttribute("src")) {
										var script = document.createElement("script");
										script.setAttribute("src", scripts[s].src);
										document.head.appendChild(script);
									}
									else {
										eval(scripts[s].innerHTML);
									}
								}
							}
						break;
						
						case "setouterhtml":
							targets[i].outerHTML = responsevalues[i];
						break;
						
						case "prependhtml":
							targets[i].insertAdjacentHTML('beforebegin', responsevalues[i]);
						break;
						
						case "appendhtml":
							targets[i].insertAdjacentHTML('beforeend', responsevalues[i]);
						break;

						case "setvalue":
							targets[i].value = responsevalues[i];
						break;

						default : {
							if (targets[i] != null) {
								if (targets[i].value == undefined) {
									targets[i].innerHTML = responsevalues[i];
								}
								else {
									if (targets[i].type == "checkbox") {
										if (responsevalues[i] == "1") {
											targets[i].checked = "true";
										}
										else{
											targets[i].checked = "";
										}
									}
									if (targets[i].type == "select-one") {
										found = false;
										for (j = 0; j < targets[i].length; ++j) {
											if (targets[i].options[j].value == responsevalues[i]) {
												targets[i].options[j].selected = true;
												found = true;
											}
										}
										if (found == false) {
											// wenns nicht dabei ist, wirds hinten rangehangen
											targets[i].options[targets[i].length] = new Option(responsevalues[i], responsevalues[i]);
											targets[i].options[targets[i].length-1].selected = true;
										}
									}
									else {
										if (targets[i].type == "select-multiple") {
											targets[i].innerHTML = responsevalues[i];
										}
										else {
											targets[i].value = responsevalues[i];
										}
									}
								}
							}
						}
					}
				}
			}
		} 
		else{
			//target.value =" AHAH Error:"+ req.status + " " +req.statusText;
			//alert(target.value);
		}
	}
}

function enforceMinMax(el) {
  if (el.value != '') {
    if (parseInt(el.value) < parseInt(el.min)) {
      el.value = el.min;
    }
    if (parseInt(el.value) > parseInt(el.max)) {
      el.value = el.max;
    }
  }
}

function delete_user2notification(notification_id) {
	let formData = new FormData();
	formData.append('go', 'delete_user2notification');
	formData.append('notification_id', notification_id);
	formData.append('csrf_token', csrf_token);
	let response = fetch('index.php', {
		method: 'POST',
		body: formData
	})
	.then(response => response.text())
	.then(text => {
		try {
			const data = JSON.parse(text);
			if (data.success) {
				$('#notification_box_' + notification_id).remove();
				let num_notifications = $('#num_notification_div').html() - 1;
				if (num_notifications == 0) {
					$('#num_notification_div').hide();
				}
				else {
					$('#num_notification_div').html(num_notifications);
				}
			}
			else {
				message([{ 'type': 'error', 'msg' : 'Fehler beim Löschen Benachrichtigung für den Nutzer: ' + data.err_msg + ' ' + text}]);
			}
		} catch(err) {
			message([{ 'type': 'error', 'msg' : err.name + ': ' + err.message + ' in Zeile: ' + err.lineNumber + ' Response: ' + text}]);
		}
	});
}

highlight_object = function(layer_id, oid){
	root.ahah('index.php', 'go=tooltip_query&querylayer_id='+layer_id+'&oid='+oid, new Array(root.document.GUI.result, ''), new Array('setvalue', 'execute_function'));
}

add_calendar = function(event, elementid, type, setnow){
	event.stopPropagation();
	remove_calendar();
	calendar = new CalendarJS();
	calendar.init(elementid, type, setnow);
	root.document.getElementById('gui-table').calendar = calendar;
}
 
remove_calendar = function(){
	if(root.document.getElementById('gui-table').calendar != undefined)root.document.getElementById('gui-table').calendar.destroy();
}

/*
* function convert a number into a hexagesimal character with a lenght of 2 signs
* @param c integer the number
* @return string with a lenght of 2
*/
function componentToHex(c) {
  let hex = parseInt(c).toString(16);
  return hex.length == 1 ? "0" + hex : hex;
}

/*
* function convert RGB Values into hexagesimal String with leading # sign
* @param r integer The red value or RGB as String separated by empty spaces or as array with r, g, b values
* @param g integer The green value
* @param b integer The blue value
* @return string The hex value of the color representing the rgb color.
*/
function rgbToHex(r, g, b) {
  var r_ = r, g_ = g, b_ = b;
  if (Array.isArray(r)) {
    r_ = r[0];
    g_ = r[1];
    b_ = r[2];
  }  
  else if (typeof r === 'string' && /\s/.test(r.trim())) { // if white spaces exists
    r_ = r.trim().split(' ')[0];
    g_ = r.trim().split(' ')[1];
    b_ = r.trim().split(' ')[2];
  }
  return "#" + componentToHex(r_) + componentToHex(g_) + componentToHex(b_);
}

function Bestaetigung(link, text) {
	Check = confirm(text);
	if (Check == true) {
		window.location.href = link;
	}
}

Element.prototype.closestExcluding = function(selector, excludeSelector) {
	let el = this;
	while (el) {
		if (el.matches(selector) && !el.matches(excludeSelector)) {
			return el;
		}
		el = el.parentElement;
	}
	return null;
};

// closest() for IE
if (!Element.prototype.matches)
    Element.prototype.matches = Element.prototype.msMatchesSelector || 
                                Element.prototype.webkitMatchesSelector;

if (!Element.prototype.closest)
    Element.prototype.closest = function(s) {
        var el = this;
        if (!document.documentElement.contains(el)) return null;
        do {
            if (el.matches(s)) return el;
            el = el.parentElement || el.parentNode;
        } while (el !== null && el.nodeType === 1); 
        return null;
    };

function roundNumber(num, scale){
  if(!("" + num).indexOf("e") != -1) {
    return +(Math.round(num + "e+" + scale)  + "e-" + scale);  
  } else {
    var arr = ("" + num).split("e");
    var sig = ""
    if(+arr[1] + scale > 0) {
      sig = "+";
    }
    var i = +arr[0] + "e" + sig + (+arr[1] + scale);
    var j = Math.round(i);
    var k = +(j + "e-" + scale);
    return k;  
  }
}

Element.prototype.scrollIntoViewIfNeeded = function (options) {
	var rect = this.getBoundingClientRect();
	if(rect.y + rect.height > window.innerHeight){
		this.scrollIntoView(options);
	}
}

function scrollToSelected(select){
	var height = select.scrollHeight / select.childElementCount;
  for(var i = 0; i < select.options.length; i++){
		if(select.options[i].selected){			
			select.scrollTop = i * height;
		}
	}
}

function toggle(obj){
	if(obj.style.display == 'none')obj.style.display = '';
	else obj.style.display = 'none';
}

function ImageLoadFailed(img) {
  img.parentNode.innerHTML = '';
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

function printMapFast(filetype = 'pdf'){
	if(typeof addRedlining != 'undefined'){
		addRedlining();
	}
	document.GUI.go.value = 'Schnelle_Druckausgabe';
	document.GUI.target = '_blank';
	document.GUI.output_filetype.value = filetype;
	document.GUI.submit();
	document.GUI.go.value = 'neu Laden';
	document.GUI.target = '';
}

function checkForUnsavedChanges(event){
	var sure = true;
	if(root.document.GUI.gle_changed.value == 1){
		var c = root.changed_form_fields.length;
		highlightChangedFormFields();
		sure = confirm('Es gibt noch ungespeicherte Datensätze (' + c + ' Feld' + (c > 1 ? 'er' : '') + '). Wollen Sie dennoch fortfahren?');
	}
	if(!sure){
		if(event != undefined)event.preventDefault();
		query_tab?.focus();
		preventSubmit();
	}
	else{
		root.document.GUI.gle_changed.value = 0;
		root.allowSubmit();
	}
	return sure;
}

function highlightChangedFormFields(){
	[].forEach.call(root.changed_form_fields, function (field)	{
		field.classList.add('changed');
	});
}

function startwaiting(lock) {
	var lock = lock || false;
	root.document.GUI.stopnavigation.value = 1;
	waitingdiv = root.document.getElementById('waitingdiv');
	waitingdiv.style.display='';
	if(lock)waitingdiv.className='waitingdiv_spinner_lock';
	else waitingdiv.className='waitingdiv_spinner';
}

function stopwaiting() {
	root.document.GUI.stopnavigation.value = 0;
	waitingdiv = root.document.getElementById('waitingdiv');
	waitingdiv.style.display='none';
}

function getBrowserSize(){
	if (typeof(window.innerWidth) == 'number'){
		width = window.innerWidth;
		height = window.innerHeight;
	} else if(document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)){
		width = document.documentElement.clientWidth;
		height = document.documentElement.clientHeight;
	} else if(document.body && (document.body.clientWidth || document.body.clientHeight)){
		width = document.body.clientWidth;
		height = document.body.clientHeight;
	}
  if (root.document.GUI) {
    root.document.GUI.browserwidth.value = width;
	  root.document.GUI.browserheight.value = height;
  }
}

function resizemap2window(){
	getBrowserSize();
	params = 'go=ResizeMap2Window&browserwidth='+document.GUI.browserwidth.value+'&browserheight='+document.GUI.browserheight.value;
	if(document.getElementById('map_frame') != undefined){
		startwaiting();
		document.location.href='index.php?'+params+'&nScale='+document.GUI.nScale.value+'&reloadmap=true';			// in der Hauptkarte neuladen
	}
	else{
		ahah('index.php', params, new Array(''), new Array(''));																								// ansonsten nur die neue Mapsize setzen
	}
}

function fetchMessageFromURL(url) {
  fetch(url)
   .then(response => response.json())
   .then(data => message([{'type':'notice','msg': data.header}, {'type':'info', 'msg': data.body}]))    	
   .catch(error => message([{'type': 'error', 'msg' : error}]));
}

/*
* Function create content to show messages of different types
* in div message_box
* @param array or string messages contain the messages as array
* or as a single string
* Examples:
*		message('Dieser Text wird immer als Warung ausgegeben.');
*		message([
*			{ type: 'error', msg: 'Dieser Text ist eine Fehlermeldung'},
*			{ type: 'info', msg: 'Hier noch eine Info.'},
*			{ type: 'waring', msg: 'Dies ist nur eine Warung.'},
*			{ type: 'notice', msg: 'und eine Notiz'},
*		]);
* @param integer t_visible Time how long the message shall be visible
* @param integer t_fade Time how long the message shall fade out
* @param string css_top The css value for distance to the top of the page
* @param string confim_value The Value that will be send with the callback function wenn the message ist confirmed
* @param string callback The name of the function called when the user confirmd the message
*/
function message(messages, t_visible = 1000, t_fade = 2000, css_top, confirm_value, callback, confirm_button_value = 'Ja', cancel_button_value = 'Abbrechen', maxWidth = null, width = null) {
	//console.log('Show Message: %o: ', messages);
	//console.log('function message with callback: %o: ', callback);
	confirm_value = confirm_value || 'ok';
	var messageTimeoutID;
	var msgBoxDiv = $('#message_box');
	if (msgBoxDiv.is(':visible')) {
		$('#message_box').stop().show();
		msgBoxDiv.stop().css('opacity', '1').show();
	}
	else {
		msgBoxDiv.html('');
	}
	if (maxWidth != null) {
		msgBoxDiv.css('maxWidth', maxWidth);
	}
	if (width != null) {
		msgBoxDiv.css('width', width);
	}
	if (document.getElementById('messages') == null) {
    msgBoxDiv.append('<div id="messages"></div>');
  }
	var msgDiv = $('#messages');
	var confirm = false;
	
	if (typeof css_top  !== 'undefined') {
		msgBoxDiv.css('top', css_top);
	}
	
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
		},
		'confirm': {
			'description' : 'Bestätigung',
			'icon': 'fa-question-circle-o',
			'color': 'red'
	  }
	}
	//	,confirmMsgDiv = false;

	if (!$.isArray(messages)) {
		messages = [{
			'type': 'warning',
			'msg': messages
		}];
	}

	$.each(messages, function (index, msg) {
		msg.type = (['notice', 'info', 'error', 'confirm'].indexOf(msg.type) > -1 ? msg.type : 'warning');
		msgDiv.append('<div class="message-box message-box-' + msg.type + '">' + (types[msg.type].icon ? '<div class="message-box-type"><i class="fa ' + types[msg.type].icon + '" style="color: ' + types[msg.type].color + '; cursor: default;"></i></div>' : '') + '<div class="message-box-msg">' + msg.msg + '</div><div style="clear: both"></div></div>');
		if (types[msg.type].confirm && document.getElementById('message_ok_button') == null) {
			msgBoxDiv.append('<input id="message_ok_button" type="button" onclick="$(\'#message_box\').hide();stopwaiting();" value="' + confirm_value + '" style="margin: 10px 0px 0px 0px;">');
		}
		if (msg.type == 'confirm' && root.document.getElementById('message_confirm_button') == null) {
			msgBoxDiv.append('<input id="message_confirm_button" type="button" onclick="root.$(\'#message_box\').hide();' + (callback ? callback + '(' + confirm_value + ')' : '') + '" value="' + confirm_button_value + '" style="margin: 10px 0px 0px 0px;">');
			msgBoxDiv.append('<input id="message_cancel_button" type="button" onclick="root.$(\'#message_box\').hide();" value="' + cancel_button_value + '" style="margin: 0px 0px -6px 8px;">');
		}
	});
	
	if (msgDiv.html() != '') {
		msgBoxDiv.show();
	}

	if (document.getElementById('message_ok_button') == null && document.getElementById('message_confirm_button') == null) {
		// wenn kein OK-Button da ist, ausblenden
		messageTimeoutID = setTimeout(function() { msgBoxDiv.fadeOut(t_fade); }, t_visible);
	}
	else {
		clearTimeout(messageTimeoutID);
		$('#message_box').stop().fadeIn().show();
	}
}

function clearMessageBox(){
	document.getElementById('message_box').innerHTML = '';
}

function onload_functions() {
	if(scrolldown){
		window.scrollTo(0,document.body.scrollHeight);
	}
	document.onmousemove = drag;
  document.onmouseup = dragstop;
	getBrowserSize();
	if(auto_map_resize){
		window.onresize = function(){ clearTimeout(doit); doit = setTimeout(resizemap2window, 200);};
	}
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

function auto_resize_overlay(){
	if (root.resized < 2) {		// wenn resized > 1 hat der Nutzer von Hand die Groesse veraendert, dann keine automatische Anpassung
		root.resized = 0;
		var scrollWidth = document.getElementById("contentdiv")?.scrollWidth;
		var clientWidth = document.getElementById("contentdiv")?.clientWidth;
		if (scrollWidth < screen.width) {
			if (scrollWidth > clientWidth) {
				window.resizeTo(scrollWidth + 33, 800);
			}
		}
		else {
			window.resizeTo(screen.width, screen.height);
			//window.moveTo(0, 0);
		}
	}
}

function activate_overlay(){
	root.changed_form_fields = [];
	document.onmousemove = drag;
  document.onmouseup = dragstop;
	window.onmouseout = function(evt){
		if(evt.relatedTarget == evt.toElement && (root.document.GUI.overlayx.value != window.screenX || root.document.GUI.overlayy.value != window.screenY)){
			root.document.GUI.overlayx.value = window.screenX;
			root.document.GUI.overlayy.value = window.screenY;
			ahah('index.php', 'go=saveOverlayPosition&overlayx='+window.screenX+'&overlayy='+window.screenY, new Array(''), new Array(""));
		}
	};
	window.onresize = function(evt){
		root.resized++;
	};
	if(root.document.SVG != undefined){
		svgdoc = root.document.SVG.getSVGDocument();	
		if(svgdoc != undefined)svgdoc.getElementById('polygon').setAttribute("points", "");
	}
	auto_resize_overlay();
	document.fullyLoaded = true;
	window.focus();
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

function formdata2urlstring(formdata){
	var entries = formdata.entries();
	for(i = 0; i < entries.length; i++) {
		var pair = entries[i];
		url = '&' + encodeURIComponent(pair[0]) + '=' + encodeURIComponent(pair[1]);
	}
	return url;
}

function setScale(select){
	if(select.value != ''){
		document.GUI.nScale.value=select.value;
		document.getElementById('scales').style.display='none';
		document.GUI.submit();
	}
}

function add_split_mapimgs() {
	svgdoc = document.SVG.getSVGDocument();
	svgdoc.getElementById("mapimg3")?.remove();
	svgdoc.getElementById("mapimg4")?.remove();	
	svgdoc.getElementById("mapimg0")?.remove();
	mapimg = svgdoc.getElementById("mapimg");
	var movegroup = svgdoc.getElementById("moveGroup");
	var cartesian = svgdoc.getElementById("cartesian");
	mapimg3 = mapimg.cloneNode();
	mapimg4 = mapimg.cloneNode();
	mapimg0 = mapimg.cloneNode();
	mapimg3.setAttribute('id', 'mapimg3');
	mapimg4.setAttribute('id', 'mapimg4');
	mapimg0.setAttribute('id', 'mapimg0');
	mapimg0.setAttribute('onload', 'top.pass_preloaded_img()');
	movegroup.insertBefore(mapimg0, mapimg);
	movegroup.insertBefore(mapimg4, cartesian);
	movegroup.insertBefore(mapimg3, mapimg4);
}

function pass_preloaded_img(){
	mapimg4.setAttribute('href', mapimg0.getAttribute('href'));
	mapimg.setAttribute('href', mapimg0.getAttribute('href'));
}

function compare_view_for_layer(layer_id){
	compare_clipping = true;
	add_split_mapimgs();
	get_map(mapimg3, 'not_layer_id=' + layer_id);
	mapimg4.setAttribute("clip-path", 'url(#compare_clipper)');
}

function set_hist_timestamp() {
	add_split_mapimgs();
	if (hist_timestamp != '') {
		var scroll = 2720;
		new_hist_timestamp = structuredClone(hist_timestamp);
	}
	else {
		var scroll = 6000;
		new_hist_timestamp = new Date();
	}
	let ts = new_hist_timestamp.toLocaleString().replace(',', '');
	get_map(mapimg3, 'no_postgis_layer=1&hist_timestamp=' + ts);
	//get_map(mapimg4, 'only_postgis_layer=1&hist_timestamp=' + ts);
	document.GUI.hist_timestamp3.value = 0;
	$('#hist_timestamp_form').show();
	document.getElementById('hist_range_div').scrollLeft = scroll;
}

function get_map_hist(){
	var nht = structuredClone(new_hist_timestamp);
	nht.setMonth(nht.getMonth() + parseInt(document.GUI.hist_timestamp3.value));
	let ts = nht.toLocaleString().replace(',', '');
	document.GUI.hist_timestamp2.value = ts;
	get_map(mapimg0, 'only_postgis_layer=1&hist_timestamp=' + ts);
}

function get_map(img, filter){
	img.setAttribute("href", loc + 'index.php?go=getMap&' + filter + '&current_date=' + current_date);
}

function get_map_ajax(postdata, code2execute_before, code2execute_after){
	current_date = new Date().toLocaleString().replace(',', '');
	top.startwaiting();
	svgdoc = document.SVG.getSVGDocument();
	$('#hist_timestamp_form').hide();
	svgdoc.getElementById("mapimg0")?.remove();
	svgdoc.getElementById("mapimg3")?.remove();
	svgdoc.getElementById("mapimg4")?.remove();

	var targets = new Array(
		'',
		svgdoc.getElementById("mapimg2"), 
		document.getElementById("scalebar"),
		document.getElementById("refmap"), 
		document.getElementById("scale"),
		document.getElementById("lagebezeichnung"),
		document.GUI.minx,
		document.GUI.miny,
		document.GUI.maxx,
		document.GUI.maxy,
		document.GUI.pixelsize,
		svgdoc.getElementById("polygon"),
		''
	);

	var actions = new Array("execute_function", "href", "src", "src", "setvalue", "sethtml", "setvalue", "setvalue", "setvalue", "setvalue", "setvalue", "points", "execute_function");
	
	var input_coord = document.GUI.INPUT_COORD.value;
	var cmd = document.GUI.CMD.value;
	var refmap_x = document.GUI.refmap_x.value;
	var refmap_y = document.GUI.refmap_y.value;
	var width_reduction = '';
	var height_reduction = '';
	var browserwidth = '';
	var browserheight = '';
	var legendtouched = '';
	if(document.GUI.width_reduction)width_reduction = document.GUI.width_reduction.value;
	if(document.GUI.height_reduction)height_reduction = document.GUI.height_reduction.value;
	if(document.GUI.browserwidth)browserwidth = document.GUI.browserwidth.value;
	if(document.GUI.browserheight)browserheight = document.GUI.browserheight.value;
	if(document.GUI.legendtouched)legendtouched = document.GUI.legendtouched.value;
	
	if(browser == 'ie'){
		code2execute_after += 'moveback();';
	}
	
	if(document.GUI.punktfang != undefined && document.GUI.punktfang.checked)code2execute_after += 'toggle_vertices();';

	postdata = postdata+"&mime_type=map_ajax&legendtouched="+legendtouched+"&browserwidth="+browserwidth+"&browserheight="+browserheight+"&width_reduction="+width_reduction+"&height_reduction="+height_reduction+"&INPUT_COORD="+input_coord+"&CMD="+cmd+"&refmap_x="+refmap_x+"&refmap_y="+refmap_y+"&code2execute_before="+code2execute_before+"&code2execute_after="+code2execute_after;

	postdata.split("&")
		.forEach(function (item) {
			pos = item.indexOf('=');
			key = item.substring(0, pos);
			value = item.substring(pos+1);
			formdata.append(key, value);			// hier muesste eigentlich set verwendet werden, kann der IE 11 aber nicht
		});
	
	ahah("index.php",	formdata, targets, actions);
	formdata = new FormData();
	document.GUI.INPUT_COORD.value = '';
	document.GUI.CMD.value = '';
}

function overlay_submit(gui, start, target){
	// diese Funktion öffnet beim Fenstermodus und einer Kartenabfrage oder einem Aufruf aus dem Overlay-Fenster ein Browser-Fenster (bzw. benutzt es falls schon vorhanden) mit den Formulardaten des uebergebenen Formularobjektes, ansonsten einen normalen Submit
	startwaiting();
	if(!gui){
		gui = root.document.GUI;
	}
	if(querymode == 1 && (start || gui.id == 'GUI2')){
		if(target){
			gui.target = target;
		}
		else{
			if(query_tab != undefined && query_tab.closed){		// wenn Fenster geschlossen wurde, resized zuruecksetzen
				root.resized = 0;
			}
			else if(gui.id == 'GUI' && browser == 'firefox' && query_tab != undefined && root.resized < 2){	// bei Abfrage aus Hauptfenster und Firefox und keiner Groessenanpassung des Fensters, Fenster neu laden
				query_tab.close();
			}
			query_tab = root.window.open("", "Sachdaten", "left="+root.document.GUI.overlayx.value+",top="+root.document.GUI.overlayy.value+",location=0,status=0,height=800,width=700,scrollbars=1,resizable=1");
			gui.window_type.value = 'overlay';
			gui.target = 'Sachdaten';			
		}
	}
	gui.submit();
	if(gui.CMD != undefined)gui.CMD.value = "";
	gui.target = '';
	gui.window_type.value = '';
}

function overlay_link(data, start, target){
	// diese Funktion öffnet bei Aufruf aus dem Overlay-Fenster ein Browser-Fenster (bzw. benutzt es falls schon vorhanden) mit den übergebenen Daten, ansonsten wird das Ganze wie ein normaler Link aufgerufen
	data = 'csrf_token=' + csrf_token + '&' + data;
	if (checkForUnsavedChanges()) {
		if (target == 'root') {
			root.location.href = 'index.php?' + data;
		}
		else {
			if (querymode == 1 && (start || currentform.name == 'GUI2')) {
				if (query_tab != undefined && query_tab.closed) {		// wenn Fenster geschlossen wurde, resized zuruecksetzen
					root.resized = 0;
				}
				else if (start && browser == 'firefox' && query_tab != undefined && root.resized < 2) {		// bei Abfrage aus Hauptfenster und Firefox und keiner Groessenanpassung des Fensters, Fenster neu laden
					query_tab.close();
				}
				query_tab = root.window.open("index.php?window_type=overlay&" + data, "Sachdaten", "left=" + root.document.GUI.overlayx.value + ",top=" + root.document.GUI.overlayy.value + ",location=0,status=0,height=800,width=700,scrollbars=1,resizable=1");
				if(root.document.GUI.CMD != undefined)root.document.GUI.CMD.value = "";
			}
			else {
				window.location.href = 'index.php?' + data;
			}
		}
	}
}

var listener;

function handleCustomSelectKeyDown(event, dropdown) {
	var selected_option = dropdown.querySelector('li.selected');
	if ([38, 40, 13].includes(event.keyCode)) {
		// Pfeiltasten und Enter
		switch (event.keyCode) {
			case 38 : {
				selected_option.previousElementSibling.onmouseenter();
			}break;
			case 40 : {
				selected_option.nextElementSibling.onmouseenter();
			}break;
			case 13 : {
				selected_option.onclick();
			}break;
		}
	}
	else {
		// Zeichen
		[].some.call(dropdown.children, function(option) {
			if (event.key == option.dataset.value.substring(0, 1).toLowerCase()) {
				option.scrollIntoView({behavior: "smooth", block: "center"});
				return true;
			}
		});
	}
}

function toggle_custom_select(id) {
	var custom_select_div = document.getElementById('custom_select_' + id);
	var dropdown = custom_select_div.querySelector('.dropdown');
	custom_select_div.classList.toggle('active');
	if (custom_select_div.classList.contains('active')) {
		listener = (event) => handleCustomSelectKeyDown(event, dropdown);
		window.addEventListener('keydown', listener, true);
	}
	else {
		window.removeEventListener('keydown', listener, true);
	}
	custom_select_hover(custom_select_div.querySelector('li.selected')); 
	if (dropdown.getBoundingClientRect().bottom > 900) {
		dropdown.classList.add('upward');
	}
	else {
		dropdown.classList.remove('upward');
	}
}

function custom_select_hover(option) {
	var custom_select_div = option.closest('.custom-select');
	option.scrollIntoView({behavior: "smooth", block: "nearest"});
	custom_select_div.querySelector('li.selected')?.classList.remove('selected');
	option.classList.add('selected');
}

function custom_select_click(option) {
	var custom_select_div = option.closest('.custom-select');
	var field = custom_select_div.querySelector('input');
	custom_select_hover(option);
	field.value = option.dataset.value;
	if (custom_select_div.querySelector('.placeholder img')) {
		custom_select_div.querySelector('.placeholder img').src = option.querySelector('img').src;
	}
	custom_select_div.querySelector('.placeholder span').innerHTML = option.querySelector('span').innerHTML;
	if (field.onchange) {
		field.onchange();
	}
	toggle_custom_select(field.id);
}


function datecheck(value){
	dateElements = value.split('.');
	var date1 = new Date(dateElements[2],dateElements[1]-1,dateElements[0]);
	if(date1 == 'Invalid Date')return false;
	else return date1;
}

function add_to_formdata(element){
	if (['checkbox', 'radio'].indexOf(element.type) == -1 || element.checked) {
		value = element.value;
	}
	else {
		value = 0;
	}
	formdata.set(element.name, value);
}

function update_legend(layerhiddenstring){
	parts = layerhiddenstring.split(' ');
	for(j = 0; j < parts.length-1; j=j+2){
		if (
			(parts[j] == 'reload') ||																																																								// wenn Legenden-Reload erzwungen wird oder
			(document.getElementById('thema'+parts[j]) != undefined && document.getElementById('thema'+parts[j]).disabled && parts[j+1] == 0) || 	// wenn Layer nicht sichtbar war und jetzt sichtbar ist
			(document.getElementById('thema'+parts[j]) != undefined && !document.getElementById('thema'+parts[j]).disabled && parts[j+1] == 1)) 	// oder andersrum
		{
			clearLegendRequests();
			legende = document.getElementById('legend');
			root.getlegend_requests.push(ahah('index.php', 'go=get_legend', new Array(legende), ""));
			break;
		}
	}
}

function get_layer_legend(layer_id, params, toggle_classes) {
	var layer_tr = document.getElementById('legend_layer_' + layer_id);
	if (toggle_classes) {
		var classes_field = document.getElementById('classes_' + layer_id);
		classes_field.value = 1 - classes_field.value;
		params = params + '&show_classes=' + classes_field.value;
	}
	ahah("index.php",	"go=get_layer_legend&only_layer_id=" + layer_id + params, new Array(layer_tr), new Array("sethtml"));
}

/*
* optional status to set values irrespective of current value and open all subgroups
*/
function getlegend(groupid, status) {
	status = status || 0;
	groupdiv = document.getElementById('groupdiv_' + groupid);
	group = document.getElementById('group_' + groupid);
	var open = status || !parseInt(group.value);
	if (open) {												// eine Gruppe wurde aufgeklappt -> Layerstruktur per Ajax holen
		group.value = 1;
		ahah('index.php', 'go=get_group_legend&' + group.name + '=' + group.value + '&group=' + groupid + '&status=' + status, new Array(groupdiv), ['setouterhtml']);
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

function updateThema(event, thema, query, groupradiolayers, queryradiolayers, instantreload){
	var status = query.checked;
	var reload = false;
	document.GUI.legendtouched.value = 1;
  if(status == true){
    if(thema.checked == false){
			thema.checked = true;
			thema.title = deactivatelayer;	
			if(instantreload)reload = true;
		}
		query.title = deactivatequery;
  }
	else{
		query.title = activatequery;
	}
  if(groupradiolayers != '' && groupradiolayers.value != ''){
    preventDefault(event);
		groupradiolayerstring = groupradiolayers.value+'';			// die Radiolayer innerhalb einer Gruppe
		radiolayer = groupradiolayerstring.split('|');
		for(i = 0; i < radiolayer.length-1; i++){
			if(document.getElementById('thema'+radiolayer[i]) != undefined){
				if(document.getElementById('thema'+radiolayer[i]) != thema){
					document.getElementById('thema'+radiolayer[i]).checked = false;
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
							thema.title = deactivatelayer;
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
			if(document.getElementById('thema'+radiolayer[i]) != undefined){
				if(document.getElementById('thema'+radiolayer[i]) != thema){
					if(document.getElementById('qLayer'+radiolayer[i]) != undefined) {
						document.getElementById('qLayer'+radiolayer[i]).checked = false;
						add_to_formdata(document.getElementById('qLayer'+radiolayer[i]));
					}
				}
				else{
					query.checked = !status;
					query.checked2 = query.checked;		// den check-Status hier nochmal merken, damit man ihn bei allen Click-Events setzen kann, sonst setzt z.B. Chrome den immer wieder zurueck
					if(query.checked == true){
						if(thema.checked == false){
							thema.checked = true;
							thema.title = deactivatelayer;
							if(instantreload)reload = true;
						}
					}
				}
			}
		}
  }
	add_to_formdata(thema);
	add_to_formdata(query);
	if(reload)neuLaden();
}

function updateQuery(event, thema, query, radiolayers, instantreload){
	document.GUI.legendtouched.value = 1;
  if(query){
    if(thema.checked == false){
      query.checked = false;
			add_to_formdata(query);
			thema.title = activatelayer;
			query.title = activatequery;
    }
		else{
			thema.title = deactivatelayer;
		}
  }
  if(radiolayers != '' && radiolayers.value != ''){  
  	preventDefault(event);
  	radiolayerstring = radiolayers.value+'';
  	radiolayer = radiolayerstring.split('|');
  	for(i = 0; i < radiolayer.length-1; i++){
			radio = document.getElementById('thema'+radiolayer[i]);
  		if (radio != thema) {
  			radio.checked = false;
				add_to_formdata(radio);
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
	add_to_formdata(thema);
	if(instantreload)neuLaden();
}

function deleteRollenlayer(type){
	document.GUI.delete_rollenlayer.value = 'true';
	document.GUI.delete_rollenlayer_type.value = type;
	document.GUI.go.value='neu Laden';
	document.GUI.submit();
}

function neuLaden(){
	startwaiting(true);
	clearLegendRequests();
	if (currentform.neuladen) {
		currentform.neuladen.value='true';
	}
	get_map_ajax('go=navMap_ajax', '', 'if(document.GUI.oldscale != undefined){document.GUI.oldscale.value=document.GUI.nScale.value;}');
}

function clearLegendRequests(){
	[].forEach.call(root.getlegend_requests, function (request){	// noch laufende getlegend-Requests abbrechen
		request.abort();				
	});
	root.getlegend_requests = new Array();
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
	if (group) {
		if(Array.isArray(group)) {
			group = group.filter(function( x ) {
				return x !== undefined;
			});
			value = group.map(function(x){return x.value+""}).join(",");
		} else {
			value = group.value+"";
		}
		
		var layers = value.split(",");
		var check;
		for(i = 0; i < layers.length; i++){			// erst den ersten checkbox-Layer suchen und den check-Status merken
			query = document.getElementById("qLayer"+layers[i]);
			if(query && query.type == 'checkbox' && !query.disabled){
				check = !query.checked;
				break;
			}
		}
		for(i = 0; i < layers.length; i++){
			query = document.getElementById("qLayer"+layers[i]);
			if(query){
				query.checked = check;
				thema = document.getElementById("thema"+layers[i]);
				updateThema('', thema, query, '', '', 0);
			}
		}
		if(instantreload)neuLaden();
	}
}

function selectgroupthema(group, instantreload){
	var value = "";
	if(Array.isArray(group)) {
		group = group.filter(function( x ) {
			return x !== undefined;
		});
		value = group.map(function(x){return x.value+""}).join(",");
	} else {
		value = group.value+"";
	}
  
  var layers = value.split(",");
	var check;
  for(i = 0; i < layers.length; i++){			// erst den ersten checkbox-Layer suchen und den check-Status merken
    thema = document.getElementById("thema"+layers[i]);
		if(thema && thema.type == 'checkbox' && !thema.disabled){
			check = !thema.checked;
			break;
    }
  }
	for(i = 0; i < layers.length; i++){
    thema = document.getElementById("thema"+layers[i]);
    if(thema && (!check || thema.type == 'checkbox')){		// entweder alle Layer sollen ausgeschaltet werden oder es ist ein checkbox-Layer
      thema.checked = check;
      query = document.getElementById("qLayer"+layers[i]);
      updateQuery('', thema, query, '', 0);
    }
  }
	if(instantreload)neuLaden();
}

function selectgroupthemaAll(group_checkbox, instantreload){
	add_to_formdata(group_checkbox);
	if(instantreload)neuLaden();
}

function zoomToMaxLayerExtent(zoom_layer_id){
	currentform.zoom_layer_id.value = zoom_layer_id;
	currentform.legendtouched.value = 1;
	neuLaden();
	currentform.zoom_layer_id.value = '';
}

function getLayerOptions(layer_id){
	if(document.GUI.layer_options_open.value != '')closeLayerOptions(document.GUI.layer_options_open.value);
	ahah('index.php', 'go=getLayerOptions&layer_id=' + layer_id, new Array(document.getElementById('options_content_'+layer_id), ''), new Array('sethtml', 'execute_function'), null, false);
	document.GUI.layer_options_open.value = layer_id;
}

function getLayerParamsForm(layer_id){
	if(document.GUI.layer_options_open.value != '')closeLayerOptions(document.GUI.layer_options_open.value);
	ahah('index.php', 'go=getLayerParamsForm&layer_id=' + layer_id + '&open=1', new Array(document.getElementById('options_content_'+layer_id), ''), new Array('sethtml', 'execute_function'), null, false);
	document.GUI.layer_options_open.value = layer_id;
}

function sendShareRollenlayer(layer_id) {
	console.log('send Form to share Rollenlayer layer_id: %o', layer_id);
	document.GUI.go.value = 'share_rollenlayer';
	document.GUI.submit();
}

function shareRollenlayer(layer_id) {
  //console.log('shareRollenLayer layer_id: %s', layer_id);
  if (typeof $('input[name=shared_layer_group_id]:checked').val() === "undefined") {
    message([{ type: "error", msg: "Es muss erst eine Layergruppe ausgewählt werden."}]);
  }
	else {
		message([{ type: "confirm", msg: "Soll der Rollenlayer wirklich freigegeben werden?"}], 1000, 2000, undefined, layer_id, 'sendShareRollenlayer');
	}
}

function sendDeleteSharedLayer(layer_id) {
	console.log('send Form to delete shared layer_id: %o', layer_id);
	document.GUI.go.value = 'delete_shared_layer';
	document.GUI.submit();
}

function deleteSharedLayer(layer_id) {
  //console.log('shareRollenLayer layer_id: %s', layer_id);
	message([{ type: "confirm", msg: "Soll der freigegebene Layer wirklich gelöscht werden?"}], 1000, 2000, undefined, layer_id, 'sendDeleteSharedLayer');
}

function getGroupOptions(group_id) {
	if (document.GUI.group_options_open.value != '') closeGroupOptions(document.GUI.group_options_open.value);
	ahah('index.php', 'go=getGroupOptions&group_id=' + group_id, new Array(document.getElementById('group_options_' + group_id), ''), new Array('sethtml', 'execute_function'));
	document.GUI.group_options_open.value = group_id;
}

function closeLayerOptions(layer_id){
	document.GUI.layer_options_open.value = '';
	document.getElementById('options_content_'+layer_id).innerHTML=' ';
}

function closeGroupOptions(group_id) {
	document.GUI.group_options_open.value = '';
	document.getElementById('group_options_' + group_id).innerHTML = ' ';
}

function saveLayerOptions(layer_id){	
	var formdata = new FormData(document.GUI);
	formdata.set('go', 'saveLayerOptions');
	ahah("index.php",	formdata, [], []);
	neuLaden();
}

function setLayerParam(name) {
	var data = 'go=setLayerParams&prefix=options_&options_layer_parameter_' + name + '=' + document.getElementsByName('options_layer_parameter_' + name)[0].value;
	ahah('index.php', data, [], []);
	document.GUI.legendtouched.value = 1;
	neuLaden();
}

function resetLayerOptions(layer_id){	
	document.GUI.go.value = 'resetLayerOptions';
	document.GUI.submit();
}

function changeLegendType(){
	var legende = document.getElementById('legend');
	ahah('index.php', 'go=changeLegendType', [legende], ['sethtml']);
	document.getElementById('legendtype_switch').classList.toggle('in_groups');
	document.getElementById('legendtype_switch').classList.toggle('alphabetical');
	document.getElementById('layersearchdiv').classList.toggle('hidden');
}

function saveDrawingorder(){
	document.GUI.go.value = 'saveDrawingorder';
	document.GUI.submit();
}

function resetDrawingorder(){
	document.GUI.go.value = 'resetDrawingorder';
	document.GUI.submit();
}

function toggleDrawingOrderForm(){
	document.getElementById('legendOptions').style.display = 'inline-block';
	drawingOrderForm = document.getElementById('drawingOrderForm');
	if(drawingOrderForm.innerHTML == ''){
		ahah('index.php', 'go=loadDrawingOrderForm', new Array(drawingOrderForm), new Array('sethtml'));
	}
	else{
		document.getElementById('legendOptions').style.display = 'none';
		drawingOrderForm.innerHTML = '';
	}
}


// --- html5 Drag and Drop --- //
 
var dragSrcEl, srcDropZone = null;

function handleDragStart(e){
	dragSrcEl = e.target;
	if (!dragSrcEl.classList.contains('dragging')) {
		var dropzones = document.querySelectorAll('.DropZone');
		[].forEach.call(dropzones, function (dropzone){		// DropZones groesser machen
			dropzone.classList.add('ready');
		});
		if(browser == 'firefox')e.dataTransfer.setData('text/html', null);	
		dragSrcEl.classList.add('dragging');
		setTimeout(function(){dragSrcEl.classList.add('picked');}, 1);
		srcDropZone = dragSrcEl.nextElementSibling;
		dragSrcEl.parentNode.removeChild(srcDropZone);
	}
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
	dstDropZone.classList.remove('over');
	dragSrcEl.classList.remove('dragging');
	dragSrcEl.classList.remove('picked');
	if(srcDropZone != dstDropZone){
		dstDropZone.parentNode.insertBefore(dragSrcEl, dstDropZone);		// dragSrcEl verschieben
		dragSrcEl.parentNode.insertBefore(srcDropZone, dragSrcEl);		// dropzone verschieben
	}
  return false;
}

function handleDragEnd(e){
	dragSrcEl.classList.remove('dragging');
	dragSrcEl.classList.remove('picked');
	var dropzones = document.querySelectorAll('.DropZone');
	[].forEach.call(dropzones, function (dropzone){		// DropZones kleiner machen
    dropzone.classList.remove('ready');
  });
}

// --- html5 Drag and Drop der Layer im drawingOrderForm --- //
 
function jumpToLayer(searchtext){
	for (var layername in layernames) {
		for (var key in layernames[layername]) {
			let layer_id = layernames[layername][key];
			layer = document.getElementById('legend_' + layer_id);						
			if (searchtext.length == 0 || layername.toLowerCase().search(searchtext.toLowerCase()) != -1) {
				layer.classList.remove('hidden');
			}
			else {
				layer.classList.add('hidden');
			}
		}
	}
}

function filterRows(searchtext){
	var rows = document.querySelectorAll('.listen-tr');
	[].forEach.call(rows, function (row) {
		layername = row.querySelector('a').innerHTML;
		if (searchtext.length == 0 || layername.toLowerCase().search(searchtext.toLowerCase()) != -1) {
			row.classList.remove('hidden');
		}
		else {
			row.classList.add('hidden');
		}
	});
}

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
		ahah('index.php', 'go=changeLegendDisplay&hide=true', new Array('', ''), new Array("", "execute_function"));
		document.getElementById('LegendMinMax').src = 'graphics/maximize_legend.png';
		document.getElementById('LegendMinMax').title="Legende zeigen";
	}
	else {
		document.getElementById('legenddiv').className = 'normallegend';
		ahah('index.php', 'go=changeLegendDisplay&hide=false', new Array('', ''), new Array("", "execute_function"));
		document.getElementById('LegendMinMax').src = 'graphics/minimize_legend.png';
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
		options_height = document.getElementById('options_content_'+layer_id).getBoundingClientRect().height;
		if(posy < legend_bottom - options_height && posy > legend_top)document.getElementById('options_content_'+layer_id).style.top = posy - (legend_top);		
	}
}

function activateAllClasses(class_ids){
	var classids = class_ids.split(",");
	for(i = 0; i < classids.length; i++){
		selClass = document.getElementsByName("class[" + classids[i] + "]")[0];
		if (selClass != undefined) {
			selClass.value = 1;
			add_to_formdata(selClass);
		}
	}
	overlay_submit(currentform);
}

function deactivateAllClasses(class_ids){
	var classids = class_ids.split(",");
	for(i = 0; i < classids.length; i++){
		selClass = document.getElementsByName("class[" + classids[i] + "]")[0];
		if (selClass != undefined) {
			selClass.value = 0;
			add_to_formdata(selClass);
		}
	}
	overlay_submit(currentform);
}

/*Anne*/
function changeClassStatus(classid, imgsrc, instantreload, width, height, type){
	selClass = document.getElementsByName("class[" + classid + "]")[0];
	selImg   = document.getElementsByName("imgclass"+classid)[0];
	if (height < width) {
		height = 12;
	}
	else {
		height = 18;
	}
	if (selClass.value == '0') {
		selClass.value='1';
		selImg.src=imgsrc;
	}
	else if (type > 1 && selClass.value == '1') {
		selClass.value='2';
		selImg.src="graphics/outline"+height+".jpg";
	}
	else if (selClass.value == '2' || type < 2) {
		selClass.value='0';
		selImg.src="graphics/inactive"+height+".jpg";
	}
	add_to_formdata(selClass);
	if(instantreload)neuLaden();
}

/*Anne*/
function mouseOverClassStatus(classid, imgsrc, width, height, type){
	selClass = document.getElementsByName("class[" + classid + "]")[0];
	selImg   = document.getElementsByName("imgclass"+classid)[0];
	if (height < width) {
		height = 12;
	}
	else {
		height = 18;
	}
	if (selClass.value == '0'){
		selImg.src=imgsrc;
	}
	else if (type > 1 && selClass.value == '1'){
		selImg.src="graphics/outline"+height+".jpg";
	}
	else if (selClass.value == '2' || type < 2){
		selImg.src="graphics/inactive"+height+".jpg";
	}
}

/*Anne*/
function mouseOutClassStatus(classid, imgsrc, width, height, type){
	selClass = document.getElementsByName("class[" + classid + "]")[0];
	selImg   = document.getElementsByName("imgclass"+classid)[0];
	if (height < width) {
		height = 12;
	}
	else {
		height = 18;
	}
	if (selClass.value == '0') {
		selImg.src="graphics/inactive"+height+".jpg";	
	}
	else if (selClass.value == '1'){
		selImg.src=imgsrc;
	}
	else if (selClass.value == '2'){
		selImg.src="graphics/outline"+height+".jpg";
	}
}

function showCopyrights(header){
	clearMessageBox();
	message([{
			'type': 'info',
			'msg': '<h2 style="padding: 4px 4px 10px 4px">' + header + '</h2><div id="copyrights_div"></div>'
	}], 1000, 2000, null, null, null, 'Ja', 'Abbrechen', 800);
	root.ahah('index.php', 'go=get_copyrights', new Array(root.document.getElementById('copyrights_div')), new Array('sethtml'));
}

function showMapParameter(epsg_code, width, height, l) {
	clearMessageBox();
	var gui = document.GUI,
			msg = " \
				<div style=\"text-align: left\"> \
					<h2>" + l.strShowMapParameterHeader + "</h2><br> \
					" + l.strCoordinateReferenceSystem + ": EPSG: " + epsg_code + "<br> \
					" + l.strLowerLeftCorner + ": (" + toFixed(gui.minx.value, 3) + ", " + toFixed(gui.miny.value, 3) + ")<br> \
					" + l.strUpperRightCorner + ": (" + toFixed(gui.maxx.value, 3) + ", " + toFixed(gui.maxy.value, 3) + ")<br> \
					" + l.strMapExtent + ": " + toFixed(gui.maxx.value - gui.minx.value, 3) + " x " + toFixed(gui.maxy.value-gui.miny.value,3) + " m<br> \
					" + l.strMapSize + ": " + width + " x " + height + " Pixel<br> \
					" + l.strPixelSize + ": " + toFixed(gui.pixelsize.value, 3) + " m\
				</div> \
			";
	message([{
			'type': 'info',
			'msg': msg
	}]);
}

function showURL(params, headline) {
	let url = `${document.baseURI.match(/.*\//)}index.php?${params}`;
	navigator.clipboard.writeText(url);
	let msg = `
		<div style="text-align: left;">
			<h2 style="margin-top: 2px; margin-buttom: 2px">${headline}</h2>
			<div style="display:flex; margin-top: 10px">
				<div style="float: left;"><textarea id="url" style="min-width: 385px;">${url}</textarea></div>
				<div style="float: left; margin-left: 5px"><a href="${url}"><i class="fa fa-hand-o-right" aria-hidden="true"></i></a></div>
			</div>
			<div style="clear: both"></div>
		</div>
	`;
	// msg = `
	// 	<div style="text-align: left">
	// 		<h2 style="margin-top: 2px; margin-buttom: 2px">${headline}</h2>
	// 		<input type="text" id="url" style="width: 93%; float: left" value="${url}">
	// 		<a href="${url}"><i class="fa fa-hand-o-right" aria-hidden="true" style="float: right""></i></a>
	// 	</div>
	// `;
	message([{
			'type': 'info',
			'msg': msg
	}]);
	document.getElementById('url').select();
}

function showExtentURL(epsg_code) {
	var gui = document.GUI;
	showURL("go=zoom2coord&INPUT_COORD="+toFixed(gui.minx.value, 3)+","+toFixed(gui.miny.value, 3)+";"+toFixed(gui.maxx.value, 3)+","+toFixed(gui.maxy.value, 3)+"&epsg_code="+epsg_code, 'URL des aktuellen Kartenausschnitts');
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

function htmlspecialchars(value) {
	var map = {
		'&': '&amp;',
		'<': '&lt;',
		'>': '&gt;',
		'"': '&quot;',
		"'": '&#039;'
	};
	return value.replace(/[&<>"']/g, function(m) { return map[m]; });
}

function sonderzeichen_umwandeln(value) {
	var map = {
		'ä' : 'ae',
		'Ä' : 'Ae',
		'ö' : 'oe',
		'Ö' : 'Oe',
		'ü' : 'ue',
		'Ü' : 'Ue',
		'ß' : 'ss',
		'<' : '',
		'>' : '',
		'@' : '_',
		'€' : 'Eur',
		',' : '_',
		';' : '_',
		'.' : '_',
		':' : '_',
		'-' : '_',
		'!' : '_',
		'"' : '',
		'§' : '',
		'$' : '',
		'%' : '',
		'&' : '_',
		'/' : '_',
		'(' : '',
		')' : '',
		'=' : '_',
		'?' : '',
		'`' : '',
		'´' : '',
		'*' : '_',
		'+' : '',
		"'" : '',
		'#' : '_',
		'^' : '',
		'°' : '',
		' ' : '_',
		'1' : '1',
		'2' : '2',
		'3' : '3',
		'4' : '4',
		'5' : '5',
		'6' : '6',
		'7' : '7',
		'8' : '8',
		'9' : '9',
		'0' : '0'
	};
	return value.replace(/[äÄöÖüÜß<>@€,;.:\-!"§$%&/()=?`´*+'#^° 1234567890]/g, function(m) { return map[m]; });
}

function getRandomString(chars, length){
	var i = 1;
	randomString = '';
	while ( i <= length ) {
		$max = chars.length - 1;
		$num = Math.floor(Math.random() * $max);
		$temp = chars.substr($num, 1);
		randomString += $temp;
		i++;
	}
	return randomString;
}

function shuffle(string) {
	var parts = string.split('');
	for (var i = parts.length; i > 0;) {
			var random = parseInt(Math.random() * i);
			var temp = parts[--i];
			parts[i] = parts[random];
			parts[random] = temp;
	}
	return parts.join('');
}

function getRandomPassword() {
	var check_condition = password_check.split('');
	var check_count = password_check.substring(1).split('1').length - 1;
	if (check_condition[0] == '0') {
		check_count = 4;
	}
	if (check_count == 0) {
		check_count = 1;
	}
	var lower_chars = 'abcdefghijklmnopqrstuvwxyz';
	var upper_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	var numbers = '0123456789';
	var special_chars = '@#$_+?%^&)';
	var length = Math.ceil(password_minlength / check_count);
	var randomPassword;

	randomPassword = getRandomString(lower_chars, length);
	
	if (check_condition[0] == '0' || check_condition[2] == '1') {
		randomPassword += getRandomString(upper_chars, length);
	}
	if (check_condition[0] == '0' || check_condition[3] == '1') {
		randomPassword += getRandomString(numbers, length);
	}
	if (check_condition[0] == '0' || check_condition[4] == '1') {
		randomPassword += getRandomString(special_chars, length);
	}

	return shuffle(randomPassword);
}

function togglePasswordVisibility(t, p1, p2) {
	$(t).toggleClass('fa-eye fa-eye-slash');

	if ($('#' + p1).attr('type') == 'text') {
		$('#' + p1 + ', #' + p2).attr('type', 'password');
	}
	else {
		$('#' + p1 + ', #' + p2).attr('type', 'text');
	}
}

/**
  Copies a string to the clipboard. Must be called from within an 
  event handler such as click. May return false if it failed, but
  this is not always possible. Browser support for Chrome 43+, 
  Firefox 42+, Safari 10+, Edge and IE 10+.
  IE: The clipboard feature may be disabled by an administrator. By
  default a prompt is shown the first time the clipboard is 
  used (per session).
*/
function copyToClipboard(text) {
	if (window.clipboardData && window.clipboardData.setData) {
		//IE specific code path to prevent textarea being shown while dialog is visible.
		return clipboardData.setData("Text", text); 
	}
	else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
		var textarea = document.createElement("textarea");
		textarea.textContent = text;
		textarea.style.position = "fixed";	//Prevent scrolling to bottom of page in MS Edge.
		document.body.appendChild(textarea);
		textarea.select();
		try {
			let result = document.execCommand("copy");	//Security exception may be thrown by some browsers.
			message([{ type: 'notice', msg: text + ' in die Zwischenablage kopiert'}], 500, 1000);
			return result;
		} catch (ex) {
			console.warn("Copy to clipboard failed.", ex);
			return false;
		} finally {
			document.body.removeChild(textarea);
		}
	}
}

format_duration = function (sec_num) {
	var hours   = Math.floor(sec_num / 3600);
	var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
	var seconds = sec_num - (hours * 3600) - (minutes * 60);
	var parts = [];

	if (hours 		> 0) parts.push(hours 	+ ' Stunde' 	+ (hours 		> 1 ? 'n' : ''));
	if (minutes 	> 0) parts.push(minutes + ' Minute' 	+ (minutes 	> 1 ? 'n' : ''));
	if (seconds 	> 0) parts.push(seconds + ' Sekunde' 	+ (seconds 	> 1 ? 'n' : ''));
	if (parts.length == 0) return '';
	if (parts.length == 1) return parts[0];
	if (parts.length == 2) return parts[0] + ' und ' + parts[1];
	if (parts.length == 3) return parts[0] + ', ' + parts[1] + ' und ' + parts[2]
}

function show_validation_error(validation_error) {
	const attribute = validation_error.attribute;
	const formElem = $('#' + validation_error.attribute);
	const errElemId = validation_error.attribute + '_error_messages';
	if ($('#' + errElemId).length == 0) {
		formElem.after('<div id="' + errElemId + '"></div>');
		formElem.change((evt) => {
			errElem.remove();
			formElem.unbind('change');
			formElem.removeClass('message-box-error');
			if ($('.message-box-error').length == 0) {
				$('#form-submit-button').show();
			}
		});
	}
	const errElem = $('#' + errElemId);
	formElem.addClass('message-box-error');
	errElem.append('<div class="red">' + validation_error.msg + '</div>');
	$('#form-submit-button').hide();
}

/**
	Split text by delimiter and add text line by line with delay and delimiter in between to element.
	@param text String The text that shall be added to the element with this delay function.
  @param element jquery Element object where the text has to be append.
	@param delay integer Delay in milliseconds between adding one line after the other
	@param prefix String Text in front of each line
  @param delimiter String Text to delimit the lines of text
*/
function add_text_with_delay(text, element, delay = 3000, prefix = '', delimiter = '<br>') {
	text.split(delimiter).forEach(
		(line, i) => {
			setTimeout((i, prefix, delimiter) => { element.append(prefix + line + delimiter) }, i * delay, i, prefix, delimiter);
		}
	)
}