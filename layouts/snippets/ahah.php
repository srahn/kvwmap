<?

$ahah = '
<script language="javascript" type="text/javascript">

function ahah(url, data, target, action){
	for (k=0; k < target.length; ++k) {
		if(target[k] != null && target[k].tagName == "DIV" && target[k].innerHTML == ""){
			target[k].innerHTML = \'<img src="graphics/ajax-loader.gif">\';
		}
	}
  if (top.window.XMLHttpRequest){
		var req = new XMLHttpRequest();
  } 
  else if(top.window.ActiveXObject){
  	var req = new ActiveXObject("Microsoft.XMLHTTP");
  }
  if(req != undefined){
  	req.onreadystatechange = function() {ahahDone(url, target, req, action);};
    req.open("POST", url, true);
		req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=iso-8859-15");
  	req.send(data);		
  }
}  

function ahahDone(url, targets, req, actions) {
  if (req.readyState == 4) { // only if req is "loaded"
    if(req.status == 200) { // only if "OK"
			var found = false;
    	response = ""+req.responseText;
    	//response = response.replace(/\s/,"");		# bei neueren Postgis-Versionen wird hier das Leerzeichen nach dem M bei asSVG-Abfragen entfernt 
    	response = response.replace(/\n/,"");
    	response = response.replace(/\n/,"");
    	response = response.replace(/\r/,"");
	    //Behandlung des Zielformelements als ein Array
	    //Zerlegung des Resultes für den Fall, dass es mehrere Responsvalues sind
	    responsevalues = response.split("~");
			if(actions == undefined || actions == "")actions = new Array();
	    for (i=0; i < targets.length; ++i) {
				if(actions[i] == undefined)actions[i] = "";
				switch (actions[i]) {
					case "execute_function":
						eval(responsevalues[i]);
					break;
					case "src":
						targets[i].src = responsevalues[i];
					break;
					case "xlink:href":
						//targets[i].setAttribute("xlink:href", responsevalues[i]);	
						targets[i].setAttributeNS("http://www.w3.org/1999/xlink", "xlink:href", responsevalues[i]);
					break;
					case "points":
						targets[i].setAttribute("points", responsevalues[i]);	
					break;
				  case "sethtml":
						if(targets[i] != undefined){
				    	targets[i].innerHTML = responsevalues[i];
							targets[i].outerHTML = targets[i].outerHTML;		// Bug-Workaround fuer den IE beim setzen eines select-Objekts
						}
				  break;
					case "setvalue":
						targets[i].value = responsevalues[i];
				  break;
				  default :{
						if(targets[i] != null){
				    	if (targets[i].value == undefined) {
				    		targets[i].innerHTML = responsevalues[i];
				    	}
				    	else{
								if(targets[i].type == "checkbox"){
									if(responsevalues[i] == "1"){
										targets[i].checked = "true";
									}
									else{
										targets[i].checked = "";
									}
								}
				    		if(targets[i].type == "select-one") {
									found = false;
				    			for (j = 0; j < targets[i].length; ++j) {
				    				if (targets[i].options[j].value == responsevalues[i]) {
				    					targets[i].options[j].selected = true;
											found = true;
				    				}
				    			}
									if(found == false){		// wenns nicht dabei ist, wirds hinten rangehangen
										targets[i].options[targets[i].length] = new Option(responsevalues[i], responsevalues[i]);
										targets[i].options[targets[i].length-1].selected = true;
									}
				    		}
				    	  else {
									if(targets[i].type == "select-multiple") {
										targets[i].innerHTML = responsevalues[i];
									}
									else{
				      	  	targets[i].value = responsevalues[i];
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
