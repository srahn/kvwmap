<script type="text/javascript" src="funktionen/calendar.js"></script>
<script type="text/javascript">

<?
	if($this->formvars['close_window'] != ""){
		echo 'opener.location.reload();';
		echo 'window.close();';
	}
 ?>

autocomplete1 = function(layer_id, attribute, field_id, inputvalue){
	document.getElementById('suggests_'+field_id).style.display='none';
	if(inputvalue.length > 0){
		ahah('index.php', 'go=autocomplete_request&layer_id='+layer_id+'&attribute='+attribute+'&inputvalue='+inputvalue+'&field_id='+field_id, new Array(document.getElementById('suggests_'+field_id), ""), new Array("sethtml", "execute_function"));
	}
}
 
auto_generate = function(attributenamesarray, geom_attribute, attribute, k, layer_id){
	var attributenames = '';
	var attributevalues = '';
	var geom = '';
	for(i = 0; i < attributenamesarray.length; i++){
		if(document.getElementById(attributenamesarray[i]+'_'+k) != undefined){
			attributenames += attributenamesarray[i] + '|';
			attributevalues += document.getElementById(attributenamesarray[i]+'_'+k).value + '|';
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
	ahah("index.php", "go=auto_generate&layer_id="+layer_id+"&attribute="+attribute+"&attributenames="+attributenames+"&attributevalues="+attributevalues, new Array(document.getElementById(attribute+'_'+k)), new Array("setvalue"));
}
 
update_buttons = function(all, layer_id){
	delete_link = document.getElementById('delete_link_'+layer_id);
	print_link = document.getElementById('print_link_'+layer_id);
	zoom_link = document.getElementById('zoom_link_'+layer_id);
	classify_link = document.getElementById('classify_link_'+layer_id);
	if(all == 'true'){		
		if(print_link != undefined)print_link.style.display = 'none';
		if(delete_link != undefined)delete_link.style.display = 'none';
		if(zoom_link != undefined)zoom_link.style.display = 'none';
		if(classify_link != undefined)classify_link.style.display = 'none';
	}
	else{
		if(print_link != undefined)print_link.style.display = '';
		if(delete_link != undefined)delete_link.style.display = '';
		if(zoom_link != undefined)zoom_link.style.display = '';
		if(classify_link != undefined)classify_link.style.display = '';
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

delete_document = function(attributename){
	if(confirm('Wollen Sie das ausgewählte Dokument wirklich löschen?')){
		currentform.document_attributename.value = attributename; 
		currentform.go.value = 'Dokument_Loeschen';
		currentform.submit();
	}
}

csv_export = function(layer_id){
	currentform.all.value = document.getElementById('all_'+layer_id).value;
	if(currentform.all.value || check_for_selection(layer_id)){				// entweder alle gefundenen oder die ausgewaehlten
		currentform.chosen_layer_id.value = layer_id;
		currentform.go_backup.value = currentform.go.value;
		currentform.go.value = 'generischer_csv_export';
		currentform.submit();
	}
}

shape_export = function(layer_id, anzahl){
	currentform.all.value = document.getElementById('all_'+layer_id).value;
	if(currentform.all.value || check_for_selection(layer_id)){				// entweder alle gefundenen oder die ausgewaehlten
		currentform.anzahl.value = anzahl;		
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
		ahah("index.php", "go=get_select_list&layer_id="+layer_id+"&attribute="+attribute[i]+"&value="+value+"&type="+type, new Array(document.getElementById(attribute[i]+'_'+k)), new Array(action));
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