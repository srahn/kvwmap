<?
if($this->formvars['anzahl'] == ''){$this->formvars['anzahl'] = 0;}

include('funktionen/input_check_functions.php');
?>

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
	
	nextquery = function(offset){
		currentform.target = '';
		if(currentform.go_backup.value != ''){
			currentform.go.value = currentform.go_backup.value;
		}
		obj = document.getElementById(offset);
		if(obj.value == '' || obj.value == undefined){
			obj.value = 0;
		}
		obj.value = parseInt(obj.value) + <? echo $this->formvars['anzahl']; ?>;
		overlay_submit(currentform, false);
	}

	prevquery = function(offset){
		currentform.target = '';
		if(currentform.go_backup.value != ''){
			currentform.go.value = currentform.go_backup.value;
		}
		obj = document.getElementById(offset);
		if(obj.value == '' || obj.value == undefined){
			obj.value = 0;
		}
		obj.value = parseInt(obj.value) - <? echo $this->formvars['anzahl']; ?>;
		overlay_submit(currentform, false);
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
  		if(document.getElementsByName(fieldstring)[0] != undefined && field[4] != 'Dokument' && field[4] != 'SubFormFK' && field[6] != 'not_saveable' && (document.getElementsByName(fieldstring)[0].readOnly != true) && field[5] == '0' && document.getElementsByName(fieldstring)[0].value == ''){
			  alert('Das Feld '+document.getElementsByName(fieldstring)[0].title+' erfordert eine Eingabe.');
  			return;
  		}
  		if(document.getElementsByName(fieldstring)[0] != undefined && field[6] == 'date' && field[4] != 'Time' && document.getElementsByName(fieldstring)[0].value != '' && !checkDate(document.getElementsByName(fieldstring)[0].value)){
  			alert('Das Datumsfeld '+document.getElementsByName(fieldstring)[0].title+' hat nicht das Format TT.MM.JJJJ.');
  			return;
  		}
  	}
  	currentform.go.value = 'neuer_Layer_Datensatz_speichern';
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
			data = 'go=Layer_Datensaetze_Loeschen&chosen_layer_id='+layer_id+'&selected_layer_id='+layer_id+'&fromobject='+fromobject+'&targetobject='+targetobject+'&targetlayer_id='+targetlayer_id+'&targetattribute='+targetattribute+'&data='+data+'&embedded=true' + data_r;
			data += '&checkbox_names_'+layer_id+'='+document.getElementsByName('checkbox_names_'+layer_id)[0].value;
			data += '&'+document.getElementsByName('checkbox_names_'+layer_id)[0].value+'=on';
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
  		if(document.getElementsByName(fieldstring)[0] != undefined && document.getElementsByName(fieldstring)[0].readOnly != true && field[5] == '0' && document.getElementsByName(fieldstring)[0].value == ''){
  			alert('Das Feld '+document.getElementsByName(fieldstring)[0].title+' erfordert eine Eingabe.');
  			return;
  		}
  		if(document.getElementsByName(fieldstring)[0] != undefined && field[6] == 'date' && field[4] != 'Time' && document.getElementsByName(fieldstring)[0].value != '' && !checkDate(document.getElementsByName(fieldstring)[0].value)){
  			alert('Das Datumsfeld '+document.getElementsByName(fieldstring)[0].title+' hat nicht das Format TT.MM.JJJJ.');
  			return;
  		}
			if(document.getElementsByName(form_fields[i])[0] != undefined){
				data_r += '&'+form_fields[i]+'='+document.getElementsByName(form_fields[i])[0].value;
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
  	form_fieldstring = document.getElementById('sub_'+layer_id+'_form_field_names').value;
  	form_fields = form_fieldstring.split('|');
  	for(i = 0; i < form_fields.length-1; i++){
  		fieldstring = form_fields[i]+'';
  		field = fieldstring.split(';');
  		if(document.getElementsByName(fieldstring)[0] != undefined && document.getElementsByName(fieldstring)[0].readOnly != true && field[5] == '0' && document.getElementsByName(fieldstring)[0].value == ''){
  			alert('Das Feld '+document.getElementsByName(fieldstring)[0].title+' erfordert eine Eingabe.');
  			return;
  		}
  		if(document.getElementsByName(fieldstring)[0] != undefined && field[6] == 'date' && field[4] != 'Time' && document.getElementsByName(fieldstring)[0].value != '' && !checkDate(document.getElementsByName(fieldstring)[0].value)){
  			alert('Das Datumsfeld '+document.getElementsByName(fieldstring)[0].title+' hat nicht das Format TT.MM.JJJJ.');
  			return;
  		}
  		if(document.getElementsByName(form_fields[i])[0] != undefined){
  			data_r += '&'+form_fields[i]+'='+document.getElementsByName(form_fields[i])[0].value;
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

</script>