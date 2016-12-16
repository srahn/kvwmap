<?
if($this->formvars['anzahl'] == ''){$this->formvars['anzahl'] = 0;}

include('funktionen/input_check_functions.php');
?>

<script type="text/javascript" src="funktionen/calendar.js"></script>
<script type="text/javascript">

	var geom_not_null = false;
	
	update_geometry = function(){
		document.getElementById("svghelp").SVGupdate_geometry();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
	}
	
	show_foreign_vertices = function(){
		document.getElementById("svghelp").SVGshow_foreign_vertices();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
	}

	scrolltop = function(){
		if(currentform.name == 'GUI2'){
			document.getElementById('contentdiv').scrollTop = 0;
		}else{
			window.scrollTo(0,0);
		}
	}
	
	scrollbottom = function(){
		if(currentform.name == 'GUI2'){
			document.getElementById('contentdiv').scrollTop = document.getElementById('contentdiv').scrollHeight;
		}else{
			window.scrollTo(0, document.body.scrollHeight);
		}
	}
	
	toggle_statistic_row = function(layer_id) {
		var x = document.getElementsByClassName('statistic_row_'+layer_id),
				i;
		for (i = 0; i < x.length; i++) {
			if (x[i].style.display == '') {
				x[i].style.display = 'none';
			}
			else {
				x[i].style.display = '';
			}
		}
	}
	
	buildJSONString = function(id, is_array){
		var field = document.getElementById(id);		
		values = new Array();
		elements = document.getElementsByName(id);
		for(i = 0; i < elements.length; i++){
			value = elements[i].value;
			if(!is_array){
				if(value == '')value = 'null';
				else if(value.substring(0,1) != '{')value = '"'+value+'"';
				values.push('"'+elements[i].title+'":'+value);
			}			
			else if(i > 0 && value != '')values.push(value);		// bei Arrays ist das erste Element ein Dummy
		}
		if(!is_array)json = '{'+values.join()+'}';
		else json = '['+values.join()+']';
		field.value = json;		
		if(field.onchange)field.onchange();
	}
	
	addArrayElement = function(fieldname){
		outer_div = document.getElementById(fieldname+'_elements');
		first_element = document.getElementById('div_'+fieldname+'_-1');
		new_element = first_element.cloneNode(true);
		last_id = outer_div.lastChild.id;
		parts = last_id.split('div_'+fieldname+'_');
		new_id = parseInt(parts[1])+1;
		new_element.id = 'div_'+fieldname+'_'+new_id;
		var regex = new RegExp(fieldname+'_-1', "g");
		new_element.innerHTML = new_element.innerHTML.replace(regex, fieldname+'_'+new_id);
		new_element.style.display = 'block';
		outer_div.appendChild(new_element);
		buildJSONString(fieldname, true);
	}
	
	removeArrayElement = function(fieldname, remove_element_id){
		outer_div = document.getElementById(fieldname+'_elements');
		remove_element = document.getElementById(remove_element_id);
		outer_div.removeChild(remove_element);
		buildJSONString(fieldname, false);
	}
	
	nextdatasets = function(layer_id){
		var sure = true;
		if(document.getElementById('changed_'+layer_id).value == 1){
			sure = confirm('Die Daten in diesem Thema wurden verändert aber noch nicht gespeichert. Wollen Sie dennoch weiterblättern?');
		}
		if(sure){
			currentform.target = '';
			if(currentform.go_backup.value != ''){
				currentform.go.value = currentform.go_backup.value;
			}
			obj = document.getElementById('offset_'+layer_id);
			if(obj.value == '' || obj.value == undefined){
				obj.value = 0;
			}
			obj.value = parseInt(obj.value) + <? echo $this->formvars['anzahl']; ?>;
			overlay_submit(currentform, false);
		}
	}
	
	lastdatasets = function(layer_id, count){
		var sure = true;
		if(document.getElementById('changed_'+layer_id).value == 1){
			sure = confirm('Die Daten in diesem Thema wurden verändert aber noch nicht gespeichert. Wollen Sie dennoch weiterblättern?');
		}
		if(sure){
			currentform.target = '';
			if(currentform.go_backup.value != ''){
				currentform.go.value = currentform.go_backup.value;
			}
			obj = document.getElementById('offset_'+layer_id);
			if(obj.value == '' || obj.value == undefined){
				obj.value = 0;
			}
			obj.value = count - (count % <? echo $this->formvars['anzahl']; ?>);
			overlay_submit(currentform, false);
		}
	}
	
	firstdatasets = function(layer_id){
		var sure = true;
		if(document.getElementById('changed_'+layer_id).value == 1){
			sure = confirm('Die Daten in diesem Thema wurden verändert aber noch nicht gespeichert. Wollen Sie dennoch zurückblättern?');
		}
		if(sure){
			currentform.target = '';
			if(currentform.go_backup.value != ''){
				currentform.go.value = currentform.go_backup.value;
			}
			obj = document.getElementById('offset_'+layer_id);
			obj.value = 0;
			overlay_submit(currentform, false);
		}
	}

	prevdatasets = function(layer_id){
		var sure = true;
		if(document.getElementById('changed_'+layer_id).value == 1){
			sure = confirm('Die Daten in diesem Thema wurden verändert aber noch nicht gespeichert. Wollen Sie dennoch zurückblättern?');
		}
		if(sure){
			currentform.target = '';
			if(currentform.go_backup.value != ''){
				currentform.go.value = currentform.go_backup.value;
			}
			obj = document.getElementById('offset_'+layer_id);
			if(obj.value == '' || obj.value == undefined){
				obj.value = 0;
			}
			obj.value = parseInt(obj.value) - <? echo $this->formvars['anzahl']; ?>;
			overlay_submit(currentform, false);
		}
	}

	back = function(){
		currentform.go.value = 'Layer-Suche';
		currentform.submit();
	}

	druck = function(){
		currentform.target = '_blank';
		currentform.printversion.value = 'true';
		currentform.submit();
	}

	save = function(){
		form_fieldstring = currentform.form_field_names.value+'';
		form_fields = form_fieldstring.split('|');
		for(i = 0; i < form_fields.length-1; i++){
			fieldstring = form_fields[i]+'';
			field = fieldstring.split(';');
			if(document.getElementsByName(fieldstring)[0] != undefined && field[4] != 'Dokument' && (document.getElementsByName(fieldstring)[0].readOnly != true) && field[5] == '0' && document.getElementsByName(fieldstring)[0].value == ''){
				alert('Das Feld '+document.getElementsByName(fieldstring)[0].title+' erfordert eine Eingabe.');
				return;
			}
			if(document.getElementsByName(fieldstring)[0] != undefined && field[6] == 'date' && field[4] != 'Time' && document.getElementsByName(fieldstring)[0].value != '' && !checkDate(document.getElementsByName(fieldstring)[0].value)){
				alert('Das Datumsfeld '+document.getElementsByName(fieldstring)[0].title+' hat nicht das Format TT.MM.JJJJ.');
				return;
			}
		}
		currentform.go.value = 'Sachdaten_speichern';
		document.getElementById('loader').style.display = '';
		setTimeout('document.getElementById(\'loaderimg\').src=\'graphics/ajax-loader.gif\'', 50);
		overlay_submit(currentform, false);
	}

	save_new_dataset = function(){
		if((geom_not_null && currentform.newpath.value == '' && currentform.loc_x == undefined) || (geom_not_null && currentform.loc_x != undefined && currentform.loc_x.value == '')){ 
			alert('Sie haben keine Geometrie angegeben.');
			return;
		}
  	form_fieldstring = currentform.form_field_names.value+'';
  	form_fields = form_fieldstring.split('|');
  	for(i = 0; i < form_fields.length; i++){
  		fieldstring = form_fields[i]+'';
  		field = fieldstring.split(';'); 
  		if(document.getElementsByName(fieldstring)[0] != undefined && field[4] != 'SubFormFK' && field[6] != 'not_saveable' && (document.getElementsByName(fieldstring)[0].readOnly != true) && field[5] == '0' && document.getElementsByName(fieldstring)[0].value == ''){
			  alert('Das Feld '+document.getElementsByName(fieldstring)[0].title+' erfordert eine Eingabe.');
  			return;
  		}
  		if(document.getElementsByName(fieldstring)[0] != undefined && field[6] == 'date' && field[4] != 'Time' && document.getElementsByName(fieldstring)[0].value != '' && !checkDate(document.getElementsByName(fieldstring)[0].value)){
  			alert('Das Datumsfeld '+document.getElementsByName(fieldstring)[0].title+' hat nicht das Format TT.MM.JJJJ.');
  			return;
  		}
  	}
  	currentform.go.value = 'neuer_Layer_Datensatz_speichern';
		document.getElementById('go_plus').disabled = true;
  	overlay_submit(currentform, false);
	}

	subdelete_data = function(layer_id, fromobject, targetobject, targetlayer_id, targetattribute, data){
		// layer_id ist die von dem Layer, in dem der Datensatz geloescht werden soll
		// fromobject ist die id von dem div, welches das Formular des Datensatzes enthaelt
		// targetobject ist die id von dem Objekt im Hauptformular, welches nach Loeschung des Datensatzes aktualisiert werden soll
		// targetlayer_id ist die von dem Layer, zu dem das targetobject gehoert
		// targetattribute ist das Attribut, zu dem das targetobject gehoert
		// data ist ein string, der weitere benötigte KVPs enthalten kann (durch <und> getrennt)
		if(confirm('Wollen Sie die ausgewählten Datensätze wirklich löschen?')){
			data_r = data.replace(/<und>/g, "&");
			form_fieldstring = document.getElementById('sub_'+layer_id+'_form_field_names').value;
			data = 'go=Layer_Datensaetze_Loeschen&chosen_layer_id='+layer_id+'&selected_layer_id='+layer_id+'&fromobject='+fromobject+'&targetobject='+targetobject+'&targetlayer_id='+targetlayer_id+'&targetattribute='+targetattribute+'&data='+data+'&form_field_names='+form_fieldstring+'&embedded=true' + data_r;
			data += '&checkbox_names_'+layer_id+'='+document.getElementsByName('checkbox_names_'+layer_id)[0].value;
			data += '&'+document.getElementsByName('checkbox_names_'+layer_id)[0].value+'=on';			
			if(typeof (window.FormData) != 'undefined'){		// in alten IEs gibts FormData nicht
				formdata = new FormData(currentform);
				data = urlstring2formdata(formdata, data);
			}			
			ahah('index.php', data, new Array(document.getElementById(fromobject), document.getElementById(targetobject)), new Array('sethtml', 'sethtml'));
		}
	}

	subsave_data = function(layer_id, fromobject, targetobject, targetlayer_id, targetattribute, data, reload){
		// layer_id ist die von dem Layer, in dem der Datensatz gespeichert werden soll
		// fromobject ist die id von dem div, welches das Formular des Datensatzes enthaelt
		// targetobject ist die id von dem Objekt im Hauptformular, welches nach Speicherung des Datensatzes aktualisiert werden soll
		// targetlayer_id ist die von dem Layer, zu dem das targetobject gehoert
		// targetattribute ist das Attribut, zu dem das targetobject gehoert
		// data ist ein string, der weitere benötigte KVPs enthalten kann (durch <und> getrennt)
		data_r = data.replace(/<und>/g, "&");
  	form_fieldstring = document.getElementById('sub_'+layer_id+'_form_field_names').value;
  	form_fields = form_fieldstring.split('|');
  	for(i = 0; i < form_fields.length-1; i++){
  		fieldstring = form_fields[i]+'';
  		field = fieldstring.split(';');
  		if(document.getElementsByName(fieldstring)[0] != undefined && field[4] != 'Dokument' && document.getElementsByName(fieldstring)[0].readOnly != true && field[5] == '0' && document.getElementsByName(fieldstring)[0].value == ''){
  			alert('Das Feld '+document.getElementsByName(fieldstring)[0].title+' erfordert eine Eingabe.');
  			return;
  		}
  		if(document.getElementsByName(fieldstring)[0] != undefined && field[6] == 'date' && field[4] != 'Time' && document.getElementsByName(fieldstring)[0].value != '' && !checkDate(document.getElementsByName(fieldstring)[0].value)){
  			alert('Das Datumsfeld '+document.getElementsByName(fieldstring)[0].title+' hat nicht das Format TT.MM.JJJJ.');
  			return;
  		}
			if(document.getElementsByName(form_fields[i])[0] != undefined){
				//data_r += '&'+form_fields[i]+'='+document.getElementsByName(form_fields[i])[0].value;		// kann evtl. weg
			}
  	}
  	data = 'go=Sachdaten_speichern&reload='+reload+'&selected_layer_id='+layer_id+'&fromobject='+fromobject+'&targetobject='+targetobject+'&targetlayer_id='+targetlayer_id+'&targetattribute='+targetattribute+'&data='+data+'&form_field_names='+form_fieldstring+'&embedded=true' + data_r;
		if(typeof (window.FormData) != 'undefined'){		// in alten IEs gibts FormData nicht
			formdata = new FormData(currentform);
			data = urlstring2formdata(formdata, data);
		}
		ahah('index.php', data, new Array(document.getElementById(fromobject), document.getElementById(targetobject), ''), new Array('sethtml', 'sethtml', 'execute_function'));
	}

	subsave_new_layer_data = function(layer_id, fromobject, targetobject, targetlayer_id, targetattribute, data, reload){
		// layer_id ist die von dem Layer, in dem ein neuer Datensatz gespeichert werden soll
		// fromobject ist die id von dem div, welches das Formular zur Eingabe des neuen Datensatzes enthaelt
		// targetobject ist die id von dem Objekt im Hauptformular, welches nach Speicherung des neuen Datensatzes aktualisiert werden soll
		// targetlayer_id ist die von dem Layer, zu dem das targetobject gehoert
		// targetattribute ist das Attribut, zu dem das targetobject gehoert
		// data ist ein string, der weitere benötigte KVPs enthalten kann (durch <und> getrennt)
		data_r = data.replace(/<und>/g, "&");
  	form_fieldstring = document.getElementById('sub_new_'+layer_id+'_form_field_names').value;
  	form_fields = form_fieldstring.split('|');
  	for(i = 0; i < form_fields.length-1; i++){
  		fieldstring = form_fields[i]+'';
  		field = fieldstring.split(';');
  		if(document.getElementsByName(fieldstring)[0] != undefined && document.getElementsByName(fieldstring)[0].readOnly != true && field[5] == '0' && document.getElementsByName(fieldstring)[0].value == ''){
  			alert('Das Feld '+document.getElementsByName(fieldstring)[0].title+' erfordert eine Eingabe.');
  			//return;
  		}
  		if(document.getElementsByName(fieldstring)[0] != undefined && field[6] == 'date' && field[4] != 'Time' && document.getElementsByName(fieldstring)[0].value != '' && !checkDate(document.getElementsByName(fieldstring)[0].value)){
  			alert('Das Datumsfeld '+document.getElementsByName(fieldstring)[0].title+' hat nicht das Format TT.MM.JJJJ.');
  			return;
  		}
  		if(document.getElementsByName(form_fields[i])[0] != undefined){
  			//data_r += '&'+form_fields[i]+'='+document.getElementsByName(form_fields[i])[0].value;			// kann evtl. weg
  		}
  	}
  	data = 'go=neuer_Layer_Datensatz_speichern&reload='+reload+'&selected_layer_id='+layer_id+'&fromobject='+fromobject+'&targetobject='+targetobject+'&targetlayer_id='+targetlayer_id+'&targetattribute='+targetattribute+'&data='+data+'&form_field_names='+form_fieldstring+'&embedded=true' + data_r;
		if(typeof (window.FormData) != 'undefined'){		// in alten IEs gibts FormData nicht
			formdata = new FormData(currentform);
			data = urlstring2formdata(formdata, data);
		}
		ahah('index.php', data, new Array(document.getElementById(fromobject), document.getElementById(targetobject), ''), new Array('sethtml', 'sethtml', 'execute_function'));
	}

	clearsubforms = function(layer_id){
		layer_id = layer_id + '';
		alldivs = document.getElementsByTagName('div');
		for(i = 0; i < alldivs.length; i++){
			id = alldivs[i].id + '';
			if(id.substr(0, 7 + layer_id.length) == 'subform'+layer_id){
				alldivs[i].innerHTML = '';
			}
		}
	}

	clearsubform = function(subformid){
		document.getElementById(subformid).innerHTML = '';
	}
	
	switch_gle_view = function(layer_id){
		currentform.chosen_layer_id.value = layer_id;
		currentform.go.value='switch_gle_view';
		overlay_submit(currentform, false);
	}
	
	add_calendar = function(event, elementid){
		event.stopPropagation();
		remove_calendar();
		calendar = new CalendarJS();
		calendar.init(elementid);
		document.getElementById('layer').calendar = calendar;
	}
	 
	remove_calendar = function(){
		if(document.getElementById('layer').calendar != undefined)document.getElementById('layer').calendar.destroy();
	}
	 
	autocomplete1 = function(layer_id, attribute, field_id, inputvalue){
		document.getElementById('suggests_'+field_id).style.display='none';
		if(inputvalue.length > 0){
			ahah('index.php', 'go=autocomplete_request&layer_id='+layer_id+'&attribute='+attribute+'&inputvalue='+inputvalue+'&field_id='+field_id, new Array(document.getElementById('suggests_'+field_id), ""), new Array("sethtml", "execute_function"));
		}
		else{
			document.getElementById(field_id).value = '';
		}
	}
	
	get_current_attribute_values = function(attributenamesarray, geom_attribute, k){
		var attributenames = '';
		var attributevalues = '';
		var geom = '';
		for(i = 0; i < attributenamesarray.length; i++){
			if(document.getElementById(attributenamesarray[i]+'_'+k) != undefined){
				attributenames += attributenamesarray[i] + '|';
				attributevalues += encodeURIComponent(document.getElementById(attributenamesarray[i]+'_'+k).value) + '|';
			}
			else if(attributenamesarray[i] == geom_attribute ){	// wenn es das Geometrieattribut ist, handelt es sich um eine Neuerfassung --> aktuelle Geometrie nehmen
				if(document.GUI.loc_x != undefined && document.GUI.loc_x.value != ''){		// Punktgeometrie
					geom = 'POINT('+document.GUI.loc_x.value+' '+document.GUI.loc_y.value+')';
				}
				else if(document.GUI.newpathwkt.value == ''){		// Polygon- oder Liniengeometrie
					if(document.GUI.newpath.value != ''){
						geom = buildwktpolygonfromsvgpath(document.GUI.newpath.value);
					}
				}
				attributenames += attributenamesarray[i] + '|';
				if(geom != '')attributevalues += 'SRID=<? echo $this->user->rolle->epsg_code; ?>;' + geom + '|';		// EWKT mit dem user-epsg draus machen
				else attributevalues += 'POINT EMPTY|';		// leere Geometrie zurückliefern
			}
		}
		return new Array(attributenames, attributevalues);
	}
	
	auto_generate = function(attributenamesarray, geom_attribute, attribute, k, layer_id){
		names_values = get_current_attribute_values(attributenamesarray, geom_attribute, k);
		ahah("index.php", "go=auto_generate&layer_id="+layer_id+"&attribute="+attribute+"&attributenames="+names_values[0]+"&attributevalues="+names_values[1], new Array(document.getElementById(attribute+'_'+k)), new Array("setvalue"));
	}
	
	openCustomSubform = function(layer_id, attribute, attributenamesarray, field_id, k){
		names_values = get_current_attribute_values(attributenamesarray, '', k);
		document.getElementById('sperrdiv').style.background = 'rgba(200,200,200,0.8)';
		document.getElementById('sperrdiv').style.width = '100%';
		subformWidth = document.GUI.browserwidth.value-70;
		subform = '<div style="position:relative; margin: 30px;width:'+subformWidth+'px; height:90%">';
		subform += '<div style="position: absolute;top: 2px;right: -2px"><a href="javascript:closeCustomSubform();" title="Schlie&szlig;en"><img style="border:none" src="<? echo GRAPHICSPATH.'exit2.png'; ?>"></img></a></div>';
		subform += '<iframe id="customSubform" style="width:100%; height:100%" src=""></iframe>';
		subform += '</div>';
		document.getElementById('sperrdiv').innerHTML= subform;
		ahah("index.php", "go=openCustomSubform&layer_id="+layer_id+"&attribute="+attribute+"&attributenames="+names_values[0]+"&attributevalues="+names_values[1]+"&field_id="+field_id, new Array(document.getElementById('customSubform')), new Array("src"));
	}
	
	closeCustomSubform = function(){
		document.getElementById('sperrdiv').style.background = 'rgba(200,200,200,0.3)';
		document.getElementById('sperrdiv').style.width = '0%';
		document.getElementById('sperrdiv').innerHTML = '';
	}
	 
	update_buttons = function(all, layer_id){
		merk_link = document.getElementById('merk_link_'+layer_id);
		delete_link = document.getElementById('delete_link_'+layer_id);
		print_link = document.getElementById('print_link_'+layer_id);
		zoom_link = document.getElementById('zoom_link_'+layer_id);
		classify_link = document.getElementById('classify_link_'+layer_id);
		if(all == 'true'){		
			if(merk_link != undefined)merk_link.style.display = 'none';
			if(print_link != undefined)print_link.style.display = 'none';
			if(delete_link != undefined)delete_link.style.display = 'none';
			if(zoom_link != undefined)zoom_link.style.display = 'none';
			if(classify_link != undefined)classify_link.style.display = 'none';
		}
		else{
			if(merk_link != undefined)merk_link.style.display = '';
			if(print_link != undefined)print_link.style.display = '';
			if(delete_link != undefined)delete_link.style.display = '';
			if(zoom_link != undefined)zoom_link.style.display = '';
			if(classify_link != undefined)classify_link.style.display = '';
		}
	} 

	selectall = function(layer_id){
		var k = 0;
		var obj = document.getElementById(layer_id+'_'+k);
		var status = obj.checked;
		while(obj != undefined){
			obj.checked = !status;
			k++;
			obj = document.getElementById(layer_id+'_'+k);
		}
	}

	zoom2object = function(params){
		if(currentform.id == 'GUI2'){					// aus overlay heraus --> Kartenzoom per Ajax machen
			startwaiting();
			get_map_ajax(params);
		}
		else{
			window.location.href = 'index.php?'+params;		// aus normaler Sachdatenanzeige heraus --> normalen Kartenzoom machen
		}
	}

	check_for_selection = function(layer_id){
		go = 'false';
		checkbox_name_obj = document.getElementsByName('checkbox_names_'+layer_id);
		checkbox_name_string = checkbox_name_obj[0].value;
		checkbox_names = checkbox_name_string.split('|');
		for(i = 0; i < checkbox_names.length; i++){
			if(document.getElementsByName(checkbox_names[i])[0] != undefined && document.getElementsByName(checkbox_names[i])[0].checked == true){
				go = 'true';
			}
		}
		if(go == 'false'){
			alert('Es wurde kein Datensatz ausgewählt.');
			return false;
		}
		else{
			return true;
		}
	}

	zoomto_datasets = function(layer_id, tablename, columnname){
		if(check_for_selection(layer_id)){
			currentform.chosen_layer_id.value = layer_id;
			currentform.layer_tablename.value = tablename;
			currentform.layer_columnname.value = columnname;
			currentform.go.value = 'zoomto_selected_datasets';
			currentform.submit();
		}
	}

	delete_datasets = function(layer_id){
		if(check_for_selection(layer_id)){
			if(confirm('Wollen Sie die ausgewählten Datensätze wirklich löschen?')){
				currentform.chosen_layer_id.value = layer_id;
				currentform.go.value = 'Layer_Datensaetze_Loeschen';
				currentform.submit();
			}
		}
	}

	delete_document = function(attributename, layer_id, fromobject, targetobject, targetlayer_id, targetattribute, data, reload){
		if(confirm('Wollen Sie das ausgewählte Dokument wirklich löschen?')){
			currentform.document_attributename.value = attributename;
			if(targetlayer_id != ''){		// SubForm-Layer
				subsave_data(layer_id, fromobject, targetobject, targetlayer_id, targetattribute, data, reload);
				currentform.document_attributename.value = '';
			}
			else{												// normaler Layer
				currentform.go.value = 'Dokument_Loeschen';
				currentform.submit();
			}
		}
	}

	daten_export = function(layer_id, anzahl, format){
		currentform.all.value = document.getElementById('all_'+layer_id).value;
		if(currentform.all.value || check_for_selection(layer_id)){				// entweder alle gefundenen oder die ausgewaehlten
			var option = document.createElement("option");
			option.text = anzahl;
			option.value = anzahl;
			currentform.anzahl.add(option);
			currentform.anzahl.selectedIndex = currentform.anzahl.options.length-1;
			currentform.chosen_layer_id.value = layer_id;
			currentform.go_backup.value = currentform.go.value;
			currentform.go.value = 'Daten_Export';
			currentform.submit();
		}
	}

	select_this_dataset = function(layer_id, n){
		var k = 0;
		obj = document.getElementById(layer_id+'_'+k);
		while(obj != undefined){
			obj.checked = false;
			k++;
			obj = document.getElementById(layer_id+'_'+k);
		}
		document.getElementById(layer_id+'_'+n).checked = true;
	}

	add_to_clipboard = function(layer_id){
		if(check_for_selection(layer_id)){
			saved_go = currentform.go.value;
			currentform.chosen_layer_id.value = layer_id;
			currentform.go.value = 'Datensaetze_Merken';
			formdata = new FormData(currentform);
			ahah("index.php", formdata, new Array(), new Array());
			currentform.go.value = saved_go;
			message("Datensätze gemerkt");
		}
	}

	remove_from_clipboard = function(layer_id){
		if(check_for_selection(layer_id)){
			saved_go = currentform.go.value;
			currentform.chosen_layer_id.value = layer_id;
			currentform.go.value = 'Datensaetze_nicht_mehr_merken';
			formdata = new FormData(currentform);
			ahah("index.php", formdata, new Array(), new Array());
			currentform.go.value = saved_go;
			message("Datensätze entfernt");
		}
	}

	use_for_new_dataset = function(layer_id){
		if(check_for_selection(layer_id)){
			currentform.chosen_layer_id.value = layer_id;
			currentform.pathwkt.value = '';
			currentform.newpathwkt.value = '';
			currentform.newpath.value = '';
			currentform.go_backup.value = currentform.go.value;
			currentform.go.value = 'neuer_Layer_Datensatz';
			currentform.submit();
		}
	}

	print_data = function(layer_id){
		if(check_for_selection(layer_id)){
			currentform.chosen_layer_id.value = layer_id;
			currentform.go_backup.value = currentform.go.value;
			currentform.go.value = 'generischer_sachdaten_druck';
			currentform.submit();
		}
	}

	showcharts = function(layer_id){
		if(document.getElementById('charts_'+layer_id).style.display == 'none'){
			document.getElementById('charts_'+layer_id).style.display = '';
		}
		else{
			document.getElementById('charts_'+layer_id).style.display = 'none';
		}
	}

	change_charttype = function(layer_id){
		if(document.getElementsByName('charttype_'+layer_id)[0].value == 'mirrorbar'){
			document.getElementById('split_'+layer_id).style.display = '';
		}
		else{
			document.getElementById('split_'+layer_id).style.display = 'none';
		}
	}

	create_chart = function(layer_id){
		if(check_for_selection(layer_id)){
			currentform.target = "_blank";
			currentform.chosen_layer_id.value = layer_id;
			currentform.width.value = 700;
			currentform.go_backup.value = currentform.go.value;
			currentform.go.value = 'generisches_sachdaten_diagramm';
			currentform.submit();
			currentform.target = "";
		}
	}


	update_require_attribute = function(attributes, k,layer_id, attributenamesarray){
		// attributes ist eine Liste von zu aktualisierenden Attributen, k die Nummer des Datensatzes und attributenamesarray ein Array aller Attribute im Formular
		var attributenames = '';
		var attributevalues = '';
		for(i = 0; i < attributenamesarray.length; i++){
			if(document.getElementById(attributenamesarray[i]+'_'+k) != undefined){
				attributenames += attributenamesarray[i] + '|';
				attributevalues += document.getElementById(attributenamesarray[i]+'_'+k).value + '|';
			}
		}
		attribute = attributes.split(',');
		for(i = 0; i < attribute.length; i++){
			type = document.getElementById(attribute[i]+'_'+k).type;
			if(type == 'text'){action = 'setvalue'};
			if(type == 'select-one'){action = 'sethtml'};
			ahah("index.php", "go=get_select_list&layer_id="+layer_id+"&attribute="+attribute[i]+"&attributenames="+attributenames+"&attributevalues="+attributevalues+"&type="+type, new Array(document.getElementById(attribute[i]+'_'+k)), new Array(action));
		}
	}

	change_orderby = function(attribute, layer_id){
		if(currentform.go_backup.value != ''){
			currentform.go.value = currentform.go_backup.value;
		}
		if(document.getElementById('orderby'+layer_id).value == attribute){
			document.getElementById('orderby'+layer_id).value = attribute+' DESC';
		}
		else{
			document.getElementById('orderby'+layer_id).value = attribute;
		}
		overlay_submit(currentform);
	}
	
	switch_edit_all = function(layer_id){
		if(document.getElementById('edit_all3_'+layer_id).style.display == 'none'){
			document.getElementById('edit_all1_'+layer_id).style.display = 'none';			
			document.getElementById('edit_all2_'+layer_id).style.display = '';
			document.getElementById('edit_all3_'+layer_id).style.display = '';
			document.getElementById('edit_all4_'+layer_id).style.display = '';
		}
		else{
			document.getElementById('edit_all1_'+layer_id).style.display = '';			
			document.getElementById('edit_all2_'+layer_id).style.display = 'none';
			document.getElementById('edit_all3_'+layer_id).style.display = 'none';
			document.getElementById('edit_all4_'+layer_id).style.display = 'none';
		}
	}
	
	change_all = function(layer_id, k, attribute){
		allfield = document.getElementById(attribute+'_'+k);
		for(var i = 0; i < k; i++){			
			if(document.getElementById(layer_id+'_'+i).checked){
				formfield = document.getElementById(attribute+'_'+i);
				if(formfield.type == 'checkbox'){
					formfield.checked = allfield.checked;
				}
				else{
					formfield.value = allfield.value;
				}
				document.getElementById(attribute+'_'+i).onchange();
			}
		}		
	}

	set_changed_flag = function(flag){
		if(flag != undefined){
			flag.value=1;
			if(flag.onchange)flag.onchange();
		}
	}

</script>