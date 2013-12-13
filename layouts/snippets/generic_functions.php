<script type="text/javascript" src="funktionen/calendar.js"></script>
<script type="text/javascript">
<!--

<?
	if($this->formvars['close_window'] != ""){
		echo 'opener.location.reload();';
		echo 'window.close();';
	}
 ?>

function checknumbers(input, type, length, decimal_length){
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

function selectall(layer_id){
	var k = 0;
	obj = document.getElementById(layer_id+'_'+k);
	while(obj != undefined){
		obj.checked = !obj.checked;
		k++;
		obj = document.getElementById(layer_id+'_'+k);
	}
}

function zoom2wkt(wkt, epsg){
	document.GUI.epsg.value = epsg;
	document.GUI.wkt.value = wkt;
	document.GUI.go.value = 'zoom2wkt';
	document.GUI.submit();
}

function check_for_selection(layer_id){
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

function zoomto_datasets(layer_id, tablename, columnname){
	if(check_for_selection(layer_id)){
		document.GUI.chosen_layer_id.value = layer_id;
		document.GUI.layer_tablename.value = tablename;
		document.GUI.layer_columnname.value = columnname;
		document.GUI.go.value = 'zoomto_selected_datasets';
		document.GUI.submit();
	}
}

function delete_datasets(layer_id){
	if(check_for_selection(layer_id)){
		if(confirm('Wollen Sie die ausgewählten Datensätze wirklich löschen?')){
			document.GUI.chosen_layer_id.value = layer_id;
			document.GUI.go.value = 'Layer_Datensaetze_Loeschen';
			document.GUI.submit();
		}
	}
}

function delete_document(attributename){
	if(confirm('Wollen Sie das ausgewählte Dokument wirklich löschen?')){
		document.GUI.document_attributename.value = attributename; 
		document.GUI.go.value = 'Dokument_Loeschen';
		document.GUI.submit();
	}
}

function csv_export_all(layer_id){
	document.GUI.all.value = 'true';
	document.GUI.chosen_layer_id.value = layer_id;
	document.GUI.go_backup.value = document.GUI.go.value;
	document.GUI.go.value = 'generischer_csv_export';
	document.GUI.submit();
}

function shape_export_all(layer_id, anzahl){
	document.GUI.chosen_layer_id.value = layer_id;
	document.GUI.anzahl.value = anzahl;
	document.GUI.go_backup.value = document.GUI.go.value;
	document.GUI.go.value = 'SHP_Export';
	document.GUI.submit();
}

function shape_export(layer_id){
	if(check_for_selection(layer_id)){
		document.GUI.chosen_layer_id.value = layer_id;
		document.GUI.go_backup.value = document.GUI.go.value;
		document.GUI.go.value = 'SHP_Export';
		document.GUI.submit();
	}
}

function select_this_dataset(layer_id, n){
	var k = 0;
	obj = document.getElementById(layer_id+'_'+k);
	while(obj != undefined){
		obj.checked = false;
		k++;
		obj = document.getElementById(layer_id+'_'+k);
	}
	document.getElementById(layer_id+'_'+n).checked = true;
}

function use_for_new_dataset(layer_id){
	if(check_for_selection(layer_id)){
		document.GUI.chosen_layer_id.value = layer_id;
		document.GUI.pathwkt.value = '';
		document.GUI.newpathwkt.value = '';
		document.GUI.newpath.value = '';
		document.GUI.go_backup.value = document.GUI.go.value;
		document.GUI.go.value = 'neuer_Layer_Datensatz';
		document.GUI.submit();
	}
}

function csv_export(layer_id){
	if(check_for_selection(layer_id)){
		document.GUI.chosen_layer_id.value = layer_id;
		document.GUI.go_backup.value = document.GUI.go.value;
		document.GUI.go.value = 'generischer_csv_export';
		document.GUI.submit();
	}
}

function print_data(layer_id){
	if(check_for_selection(layer_id)){
		document.GUI.chosen_layer_id.value = layer_id;
		document.GUI.go_backup.value = document.GUI.go.value;
		document.GUI.go.value = 'generischer_sachdaten_druck';
		document.GUI.submit();
	}
}

function showcharts(layer_id){
	if(document.getElementById('charts_'+layer_id).style.display == 'none'){
		document.getElementById('charts_'+layer_id).style.display = '';
	}
	else{
		document.getElementById('charts_'+layer_id).style.display = 'none';
	}
}

function change_charttype(layer_id){
	if(document.getElementsByName('charttype_'+layer_id)[0].value == 'mirrorbar'){
		document.getElementById('split_'+layer_id).style.display = '';
	}
	else{
		document.getElementById('split_'+layer_id).style.display = 'none';
	}
}

function create_chart(layer_id){
	if(check_for_selection(layer_id)){
		document.GUI.target = "_blank";
		document.GUI.chosen_layer_id.value = layer_id;
		document.GUI.width.value = 700;
		document.GUI.go_backup.value = document.GUI.go.value;
		document.GUI.go.value = 'generisches_sachdaten_diagramm';
		document.GUI.submit();
		document.GUI.target = "";
	}
}

function update_require_attribute(attributes, k,layer_id, value){
	// attributes ist eine Liste von zu aktualisierenden Attributen, k die Nummer des Datensatzes und value der ausgewaehlte Wert
	attribute = attributes.split(',');
	for(i = 0; i < attribute.length; i++){
		type = document.getElementById(attribute[i]+'_'+k).type;
		if(type == 'text'){action = 'setvalue'};
		if(type == 'select-one'){action = 'sethtml'};
		ahah("<? echo URL.APPLVERSION; ?>index.php", "go=get_select_list&layer_id="+layer_id+"&attribute="+attribute[i]+"&value="+value+"&type="+type, new Array(document.getElementById(attribute[i]+'_'+k)), action);
	}
}

function change_orderby(attribute, layer_id){
	if(document.GUI.go_backup.value != ''){
		document.GUI.go.value = document.GUI.go_backup.value;
	}
	if(document.getElementById('orderby'+layer_id).value == attribute){
		document.getElementById('orderby'+layer_id).value = attribute+' DESC';
	}
	else{
		document.getElementById('orderby'+layer_id).value = attribute;
	}
	document.GUI.submit();
}

function set_changed_flag(flag){
	flag.value=1;
}


//-->
</script>