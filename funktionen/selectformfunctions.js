
  function getSelectedOptionContent(selectFieldName) {
	options = document.getElementsByName(selectFieldName)[0].getElementsByTagName('option');
	content = '';
	for (var i=0; i < options.length; i++) {
	  if (options[i].selected && options[i].innerHTML != '--Auswahl--') content=options[i].innerHTML;
	}
	return content;
  }

  function getFirstOptionContent(selectFieldName) {
    contentText = '';
	options = document.getElementsByName(selectFieldName)[0].getElementsByTagName('option');
	if (options.length > 0) contentText = options[0].innerHTML;
	return contentText;
  } 

  function submitWithValue(formId, formObjId, value) {
    // Setzt den Wert des Formularobjects mit der Id formObjId auf value
    // und sendet das Formular mit der Id formId ab
    formObj=document.getElementById(formObjId);
    formObj.value=value;
    form=document.getElementById(formId);
    form.submit();
  }

  function selectbyString(selectObj, string){
	// Diese Funktion selektiert die erste Option eines Auswahlfeldes, deren Wert mit string beginnt.
		var found = false;
		for(i = 0; i < selectObj.length; i++) {
		  value	= selectObj.options[i].value + "";
		  result = value.search("^"+string);
		  if (result != -1){
	  		selectObj.selectedIndex = i;
				found = true;
	  		break;
	  	}
		}
		if (found == false){
			selectObj.selectedIndex = -1;
		}
	}

	function addOptionsWithIndex(selectObj,insertObj,hiddenObj,mode,index) {
    // Diese Funktion fügt alle im Selectfeld selectObj selektierten Optionen im Selectfeld insertObj an der mit index definierten Position ein.
		var insertObjLengthStart = insertObj.length;
		//------- erst den Rest entfernen und dabei die entfernten merken
		saveOptionsObj = insertObj.cloneNode(true);
		if(index < insertObjLengthStart){
			for(i=index; i<insertObj.length; i++){
			  insertObj.options[i].selected = true;
			  saveOptionsObj.options[i].selected = true;
			}
			substractOptions(insertObj,hiddenObj,mode);
		}
		
		//------- dann die neuen Optionen einfügen
		addOptions(selectObj,insertObj,hiddenObj,mode);
		
		if(index < insertObjLengthStart){
			//------- und dann den Rest wieder anhängen
			addOptions(saveOptionsObj,insertObj,hiddenObj,mode);
		}
  }

  function addOptions(selectObj,insertObj,hiddenObj,mode) {
    // Diese Funktion fügt alle im Selectfeld selectObj selektierten Optionen zum Selectfeld insertObj hinzu.
    // Und füllt das Hiddenfield hiddenObj mit den selektierten Values auf
    // hiddenObj.value enthält eine mit Komma getrennte Liste der ausgewählten Werte
		insertObjLengthStart=insertObj.length;
    for(i=0; i<selectObj.length; i++) {
	  	if (selectObj.options[i].selected) {
	  	 // alert('insertObj.length'+insertObj.length);
  			insertObj.length++;
				insertObj.options[insertObj.length-1].value=selectObj.options[i].value;
				insertObj.options[insertObj.length-1].text=selectObj.options[i].text;
				insertObj.options[insertObj.length-1].id=selectObj.options[i].id;
				if(mode=='text'){
					addTextToHiddenField(hiddenObj,selectObj.options[i].text);
				}
				else{
					if(mode=='value'){
						addTextToHiddenField(hiddenObj,selectObj.options[i].value);
					}
				}
	  	}
    }
		if (insertObjLengthStart==insertObj.length) {
		  alert('Erst Schlagwörter im rechten Feld auswählen!');
		}
  }
 
  function substractOptions(selectObj,hiddenObj,mode) {
  	selectObjLengthStart=selectObj.length;
    unselectedValue= new Array();
    unselectedText= new Array();
    unselectedId= new Array();
	  j=0;
    // Diese Funktion entfernt alle selectierten Optionen aus dem Selektfeld selectObj
		for(i=0; i<selectObj.length; i++) {
		  if (!selectObj.options[i].selected) {
		    unselectedValue[j]=selectObj.options[i].value;
		    unselectedText[j]=selectObj.options[i].text;
		    unselectedId[j]=selectObj.options[i].id;
			j++;
		  }
		}
		selectObj.length=0;
		hiddenObj.value='';
		for(i=0; i<j; i++) {
		  	selectObj.length++;
		  	selectObj.options[i].value=unselectedValue[i];
		  	selectObj.options[i].text=unselectedText[i];
		  	selectObj.options[i].id=unselectedId[i];
		  	if(mode=='text'){
				addTextToHiddenField(hiddenObj,unselectedText[i])
			}
			else{
				if(mode=='value'){
					addTextToHiddenField(hiddenObj,unselectedValue[i])
				}
			}
		}
		if (selectObjLengthStart==selectObj.length) {
	  	if (selectObj.length==0) {
	    	alert('Die linke Liste ist leer!');
	  	}
	  	else {
	    	alert('Erst Schlagwörter im linken Feld auswählen!');
	  	} 
		}
  }
  
  function addSelectedValuesToHiddenField(selectObj, hiddenObj){
  	for(i = 0; i < selectObj.length; i++) {
	  	if(selectObj.options[i].selected){
	  		// alert('insertObj.length'+insertObj.length);
				addTextToHiddenField(hiddenObj,selectObj.options[i].value);
			}
		}
  }
  
  function addSelectedIndizesToHiddenField(selectObj, hiddenObj){
  	for(i = 0; i < selectObj.length; i++) {
	  	if(selectObj.options[i].selected){
	  		// alert('insertObj.length'+insertObj.length);
				addTextToHiddenField(hiddenObj,i);
			}
		}
  }
  
  function addTextToHiddenField(hiddenObj,text) {
		if (hiddenObj.value!='') {
		  hiddenObj.value=hiddenObj.value+', ';
		}
		hiddenObj.value=hiddenObj.value+text;
  }