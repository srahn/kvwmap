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

function ahahDone(url, target, req, action) {
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
			if(action == undefined)action = "";
			actions = action.split("~");
	    for (i=0; i < target.length; ++i) {
				if(actions[i] == undefined)actions[i] = action;
				switch (actions[i]) {
					case "execute_function":
						eval(responsevalues[i]);
					break;
					case "src":
						target[i].src = responsevalues[i];
					break;
					case "xlink:href":
						//target[i].setAttribute("xlink:href", responsevalues[i]);	
						target[i].setAttributeNS("http://www.w3.org/1999/xlink", "xlink:href", responsevalues[i]);
					break;
					case "points":
						target[i].setAttribute("points", responsevalues[i]);	
					break;
				  case "sethtml":
						if(target[i] != undefined){
				    	target[i].innerHTML = responsevalues[i];
							target[i].outerHTML = target[i].outerHTML;		// Bug-Workaround fuer den IE beim setzen eines select-Objekts
						}
				  break;
					case "setvalue":
						target[i].value = responsevalues[i];
				  break;
				  default :{
						if(target[i] != null){
				    	if (target[i].value == undefined) {
				    		target[i].innerHTML = responsevalues[i];
				    	}
				    	else{
								if(target[i].type == "checkbox"){
									if(responsevalues[i] == "1"){
										target[i].checked = "true";
									}
									else{
										target[i].checked = "";
									}
								}
				    		if(target[i].type == "select-one") {
									found = false;
				    			for (j = 0; j < target[i].length; ++j) {
				    				if (target[i].options[j].value == responsevalues[i]) {
				    					target[i].options[j].selected = true;
											found = true;
				    				}
				    			}
									if(found == false){		// wenns nicht dabei ist, wirds hinten rangehangen
										target[i].options[target[i].length] = new Option(responsevalues[i], responsevalues[i]);
										target[i].options[target[i].length-1].selected = true;
									}
				    		}
				    	  else {
									if(target[i].type == "select-multiple") {
										target[i].innerHTML = responsevalues[i];
									}
									else{
				      	  	target[i].value = responsevalues[i];
									}
				    	  }
				    	}
						}
					}
				}
	    }	      	
    } 
    else{
    	target.value =" AHAH Error:"+ req.status + " " +req.statusText;
    	//alert(target.value);
    }
  }
}

</script>
' ;

?>
