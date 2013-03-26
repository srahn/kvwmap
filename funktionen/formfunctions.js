<script language="JavaScript">
<!--

  function addOptions(selectObj,insertObj) {
    // Diese Funktion fügt alle in selectObj selektierten Optionen zum insertObj hinzu.
	insertObjLengthStart=insertObj.length;
    for(i=0; i<selectObj.length; i++) {
	  if (selectObj.options[i].selected) {
		insertObj.length++;
		insertObj.options[insertObj.length-1].value=selectObj.options[i].value;
		insertObj.options[insertObj.length-1].text=selectObj.options[i].text;
	  }
    }
	if (insertObjLengthStart==insertObj.length) {
	  alert('Erst Schlagwörter im rechten Feld auswählen!');
	}
 }
 
  function substractOptions(selectObj) {
    selectObjLengthStart=selectObj.length;
    unselectedValue= new Array();
    unselectedText= new Array();
	j=0;
    // Diese Funktion entfernt alle selectierten Optionen aus dem selectObj
	for(i=0; i<selectObj.length; i++) {
	  if (!selectObj.options[i].selected) {
	    unselectedValue[j]=selectObj.options[i].value;
	    unselectedText[j]=selectObj.options[i].text;
		j++;
	  }
	}
	selectObj.length=0;
	for(i=0; i<j; i++) {
	  selectObj.length++;
	  selectObj.options[i].value=unselectedValue[i];
	  selectObj.options[i].text=unselectedText[i];
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

  function setBBoxFromMap(pathx,pathy,pixsize,minx,miny,westbl,eastbl,southbl,northbl) {
    // Diese übergibt die Weltkoordinaten der BBox, die sie mit pixel2weltkoord() berechnen lässt an die
	// Formularfelder der Metadatenbbox
	x=pathx.split(","); y=pathy.split(",");
    if (x.length<2) {	
  	  alert('Ziehen Sie vorher ein Rechteck im Kartenfenster auf!');
	}
	else {
	  westbl.value=x[0]; eastbl.value=x[2];
	  if (parseInt(x[0])>parseInt(x[2])) {
	    westbl.value=x[2]; eastbl.value=x[0];
	  }
	  westbl.value=Math.round(pixel2weltkoord(westbl.value,minx,pixsize));
	  eastbl.value=Math.round(pixel2weltkoord(eastbl.value,minx,pixsize));
	  southbl.value=y[0]; northbl.value=y[2];
	  if (parseInt(y[0])>parseInt(y[2])) {
	    southbl.value=y[2]; northbl.value=y[0];
	  }
	  southbl.value=Math.round(pixel2weltkoord(southbl.value,miny,pixsize));
	  northbl.value=Math.round(pixel2weltkoord(northbl.value,miny,pixsize));
	}
 }
 
  function pixel2weltkoord(pix,minwelt,scale) {
    //  Diese Funktion rechnet die Koordinaten der BBox von Bildkoordinaten in Welt-Koordinaten um 
    welt=pix*scale+minwelt;
    return welt;
  }
  
//-->
	</script>