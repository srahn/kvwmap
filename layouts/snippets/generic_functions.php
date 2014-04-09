<script type="text/javascript" src="funktionen/calendar.js"></script>
<script type="text/javascript">

<?
	if($this->formvars['close_window'] != ""){
		echo 'opener.location.reload();';
		echo 'window.close();';
	}
 ?>
 
checknumbers = function(input, type, length, decimal_length){
	if(type == 'numeric' || type == 'float4' || type == 'float8'){
		var val = input.value.replace(/[a-zA-Z]/g, '');
		val = val.replace(/,/g, '.');
		parts = val.split('.');
		ohne_leerz = parts[0].replace(/ /g, '').length;
		mit_leerz = parts[0].length;
		length = parseInt(length) - parseInt(decimal_length);
		if(length != '' &&  ohne_leerz > length){
			alert('Für dieses Feld sind maximal '+length+' Vorkommastellen erlaubt.');
			parts[0] = parts[0].substring(0, length - ohne_leerz + mit_leerz);
		}
		val = parts[0];
		if(parts[1] != undefined){
			if(decimal_length != '' && parts[1].length > parseInt(decimal_length)){
				alert('Für dieses Feld sind maximal '+decimal_length+' Nachkommastellen erlaubt.');
				parts[1] = parts[1].substring(0, decimal_length);
			}
			val = val+'.'+parts[1];
		}
		if(input.value != val){
  		input.value = val;
  	}
  }
	if(type == 'int2' || type == 'int4' || type == 'int8'){
  	if(input.value.search(/[^-\d]/g) != -1 || input.value.search(/.-/g) != -1){
  		alert('Es sind nur ganzzahlige Angaben erlaubt!');
  		var val = input.value.replace(/[^-\d]/g, '');
  		val = val.replace(/-/g, '');
  		input.value = val;
  	}
  }
}

selectall = function(layer_id){
	var k = 0;
	obj = document.getElementById(layer_id+'_'+k);
	while(obj != undefined){
		obj.checked = !obj.checked;
		k++;
		obj = document.getElementById(layer_id+'_'+k);
	}
}

zoom2wkt = function(wkt, epsg){
	currentform.epsg.value = epsg;
	currentform.wkt.value = wkt;
	currentform.go.value = 'zoom2wkt';
	currentform.submit();
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

delete_document = function(attributename){
	if(confirm('Wollen Sie das ausgewählte Dokument wirklich löschen?')){
		currentform.document_attributename.value = attributename; 
		currentform.go.value = 'Dokument_Loeschen';
		currentform.submit();
	}
}

csv_export_all = function(layer_id){
	currentform.all.value = 'true';
	currentform.chosen_layer_id.value = layer_id;
	currentform.go_backup.value = currentform.go.value;
	currentform.go.value = 'generischer_csv_export';
	currentform.submit();
}

shape_export_all = function(layer_id, anzahl){
	currentform.chosen_layer_id.value = layer_id;
	currentform.anzahl.value = anzahl;
	currentform.go_backup.value = currentform.go.value;
	currentform.go.value = 'SHP_Export';
	currentform.submit();
}

shape_export = function(layer_id){
	if(check_for_selection(layer_id)){
		currentform.chosen_layer_id.value = layer_id;
		currentform.go_backup.value = currentform.go.value;
		currentform.go.value = 'SHP_Export';
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

csv_export = function(layer_id){
	if(check_for_selection(layer_id)){
		currentform.chosen_layer_id.value = layer_id;
		currentform.go_backup.value = currentform.go.value;
		currentform.go.value = 'generischer_csv_export';
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

update_require_attribute = function(attributes, k,layer_id, value){
	// attributes ist eine Liste von zu aktualisierenden Attributen, k die Nummer des Datensatzes und value der ausgewaehlte Wert
	attribute = attributes.split(',');
	for(i = 0; i < attribute.length; i++){
		type = document.getElementById(attribute[i]+'_'+k).type;
		if(type == 'text'){action = 'setvalue'};
		if(type == 'select-one'){action = 'sethtml'};
		ahah("<? echo URL.APPLVERSION; ?>index.php", "go=get_select_list&layer_id="+layer_id+"&attribute="+attribute[i]+"&value="+value+"&type="+type, new Array(document.getElementById(attribute[i]+'_'+k)), new Array(action));
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

set_changed_flag = function(flag){
	flag.value=1;
}

</script>