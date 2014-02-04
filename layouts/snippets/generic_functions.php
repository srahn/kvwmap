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
  	if(input.value.search(/[-\d]/g) != -1 || input.value.search(/.-/g) != -1){
  		alert('Es sind nur ganzzahlige Angaben erlaubt!');
  		var val = input.value.replace(/[-\d]/g, '');
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
	gui.epsg.value = epsg;
	gui.wkt.value = wkt;
	gui.go.value = 'zoom2wkt';
	gui.submit();
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
		gui.chosen_layer_id.value = layer_id;
		gui.layer_tablename.value = tablename;
		gui.layer_columnname.value = columnname;
		gui.go.value = 'zoomto_selected_datasets';
		gui.submit();
	}
}

function delete_datasets(layer_id){
	if(check_for_selection(layer_id)){
		if(confirm('Wollen Sie die ausgewählten Datensätze wirklich löschen?')){
			gui.chosen_layer_id.value = layer_id;
			gui.go.value = 'Layer_Datensaetze_Loeschen';
			gui.submit();
		}
	}
}

function delete_document(attributename){
	if(confirm('Wollen Sie das ausgewählte Dokument wirklich löschen?')){
		gui.document_attributename.value = attributename; 
		gui.go.value = 'Dokument_Loeschen';
		gui.submit();
	}
}

function csv_export_all(layer_id){
	gui.all.value = 'true';
	gui.chosen_layer_id.value = layer_id;
	gui.go_backup.value = gui.go.value;
	gui.go.value = 'generischer_csv_export';
	gui.submit();
}

function shape_export_all(layer_id, anzahl){
	gui.chosen_layer_id.value = layer_id;
	gui.anzahl.value = anzahl;
	gui.go_backup.value = gui.go.value;
	gui.go.value = 'SHP_Export';
	gui.submit();
}

function shape_export(layer_id){
	if(check_for_selection(layer_id)){
		gui.chosen_layer_id.value = layer_id;
		gui.go_backup.value = gui.go.value;
		gui.go.value = 'SHP_Export';
		gui.submit();
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
		gui.chosen_layer_id.value = layer_id;
		gui.pathwkt.value = '';
		gui.newpathwkt.value = '';
		gui.newpath.value = '';
		gui.go_backup.value = gui.go.value;
		gui.go.value = 'neuer_Layer_Datensatz';
		gui.submit();
	}
}

function csv_export(layer_id){
	if(check_for_selection(layer_id)){
		gui.chosen_layer_id.value = layer_id;
		gui.go_backup.value = gui.go.value;
		gui.go.value = 'generischer_csv_export';
		gui.submit();
	}
}

function print_data(layer_id){
	if(check_for_selection(layer_id)){
		gui.chosen_layer_id.value = layer_id;
		gui.go_backup.value = gui.go.value;
		gui.go.value = 'generischer_sachdaten_druck';
		gui.submit();
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
		gui.target = "_blank";
		gui.chosen_layer_id.value = layer_id;
		gui.width.value = 700;
		gui.go_backup.value = gui.go.value;
		gui.go.value = 'generisches_sachdaten_diagramm';
		gui.submit();
		gui.target = "";
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
	if(gui.go_backup.value != ''){
		gui.go.value = gui.go_backup.value;
	}
	if(document.getElementById('orderby'+layer_id).value == attribute){
		document.getElementById('orderby'+layer_id).value = attribute+' DESC';
	}
	else{
		document.getElementById('orderby'+layer_id).value = attribute;
	}
	gui.submit();
}

function set_changed_flag(flag){
	flag.value=1;
}


//-->
</script>