<?
$ahah = '
<script language="javascript" type="text/javascript">
	function ahah(url, data, target, action, progress){
		for(k = 0; k < target.length; ++k){
			if(target[k] != null && target[k].tagName == "DIV"){
				waiting_img = document.createElement("img");
				waiting_img.src = "graphics/ajax-loader.gif";
				target[k].appendChild(waiting_img);
			}
		}
		var req = new XMLHttpRequest();
		if(req != undefined){
			req.onreadystatechange = function() { ahahDone(url + \'&csrf_token=' . $_SESSION['csrf_token'] . '\', target, req, action); };
			if (typeof progress !== \'undefined\') {
				req.upload.addEventListener("progress", progress);
			}
			req.open("POST", url + \'&csrf_token=' . $_SESSION['csrf_token'] . '\', true);
			if (typeof data == "string") {
				req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=iso-8859-15"); // data kann entweder ein String oder ein FormData-Objekt sein
			}
			req.send(data);
		}
		return req;
	}

	function ahahDone(url, targets, req, actions) {
		if (req.readyState == 4) { // only if req is "loaded"
			if (req.status == 200) { // only if "OK"
				if (req.getResponseHeader(\'logout\') == \'true\') { // falls man zwischenzeitlich ausgeloggt wurde
					window.location = url;
					return;
				}
				if(req.getResponseHeader(\'error\') == \'true\'){
					message(req.responseText);
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

							case "xlink:href":
								targets[i].setAttributeNS("http://www.w3.org/1999/xlink", "xlink:href", responsevalues[i]);
							break;

							case "points":
								targets[i].setAttribute("points", responsevalues[i]);	
							break;

							case "sethtml":
								if (targets[i] != undefined && req.getResponseHeader(\'error\') != \'true\') {
									targets[i].innerHTML = responsevalues[i];
									$(targets[i]).change();
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
							
							case "prependhtml":
								targets[i].insertAdjacentHTML(\'beforebegin\', responsevalues[i]);
							break;
							
							case "appendhtml":
								targets[i].insertAdjacentHTML(\'beforeend\', responsevalues[i]);
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
</script>
' ;
?>
