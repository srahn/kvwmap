<?
if (value_of($this->formvars, 'anzahl') == '') {
	$this->formvars['anzahl'] = 0;
}

include('funktionen/input_check_functions.php');
include_once(LAYOUTPATH.'languages/generic_layer_editor_2_'.rolle::$language.'.php');
?>

<script type="text/javascript">

	var geom_not_null = false;
	var enclosingForm = <? echo $this->currentform; ?>;
		
	update_geometry = function(){
		SVG.update_geometry();
	}
		
	adjustHref = function(link){
		if (link.href.indexOf('index.php?') != -1 && link.target != 'root' && enclosingForm.name == 'GUI2') {
			link.href = link.href.replace('?', '?window_type=overlay&');
		}
	}

	/**
	 * @abstract Vervollständigt value vom datefield Element
	 * Wenn Tag, Monat oder Jahr nicht angegeben sind wird es vom aktuellen Datum genommen.
	 * Wenn Werte negativ sind wird 1 für Tag und Monat sowie 2000 für Jahr genommen.
	 * Wenn Tag > 31 wird 31 genommen.
	 * Wenn Monat > 12 wird 12 genommen.
	 * Wenn Jahr > 9999 wird 9999 genommen.
	 * Wenn Jahr >= 0 und <=99 ist wird das aktuelle Jahrhundert davor gehängt. Dadurch
	 * kann man die Jahre '0000' bis '0099' im nullten Jahrhundert nicht angeben!
	 */
	completeDate = function(datefield) {
		var d = new Date();

		var currentYear = d.getFullYear();
		var currentCentury = Math.round(currentYear / 100);
		var currentMonth = d.getMonth() + 1;
		var currentDay = d.getDate();

		var part = datefield.value.split(".");

		var	inputDay = parseInt(part[0]);
		var day = (isNaN(inputDay) ? currentDay : (inputDay > 31 ? 31 : (inputDay < 1 ? 1 : inputDay)));

		var inputMonth = parseInt(part[1]);
		var month = (isNaN(inputMonth) ? currentMonth : (inputMonth > 12 ? 12 : (inputMonth < 1 ? 1 : inputMonth)));

		var inputYear = parseInt(part[2]);
		var year = 9999;
		if (isNaN(inputYear)) {
			year = currentYear;
		}
		else {
			if (inputYear < 0) {
				inputYear = 0;
			}
			if (inputYear > 9999) {
				inputYear = 9999;
			}
			year = ((inputYear >= 0 && inputYear <= 99) ? currentCentury * 100 : 0) + inputYear;
		}

		datefield.value = ('00' + day).slice(-2) + '.' + ('00' + month).slice(-2) + '.' + ('0000' + year).slice(-4);
	}
	
	completeTime = function(timefield){
		timefield.value = timefield.value.replace('.', ':');
		var split = timefield.value.split(":");
		if (split.length == 2) {
			if (split[1] == '') {
				timefield.value += '00';
			}
		}
		else {
			if (split.length == 1) {
				timefield.value += ':00';
			}
		}
	}	

	save_scrollposition = function(){
		var pos = document.getElementsByName('gle_scrollposition_' + enclosingForm.active_layer_id.value)[0];
		if (enclosingForm.name == 'GUI2') {
			pos.value = document.scrollingElement.scrollTop;
		}
		else {
			pos.value = document.getElementById('contentdiv').scrollTop;
		}
	}

	scrollto_saved_position = function(){
		var pos = document.getElementsByName('gle_scrollposition_' + enclosingForm.active_layer_id.value)[0];
		if (enclosingForm.name == 'GUI2') {
			window.scrollTo({top: pos.value, left: 0, behavior: "instant"});
		}
		else {
			document.getElementById('contentdiv').scrollTo({top: pos.value, left: 0, behavior: "instant"});
		}
	}

	scrolltop = function(){
		if(querymode == 1){
			window.scrollTo(0, 0);
		}
		else{
			document.getElementById('contentdiv').scrollTop = 0;
		}
	}
	
	scrollbottom = function(){
		if(querymode == 1){
			window.scrollTo(0, 9999999);
		}
		else{
			document.getElementById('contentdiv').scrollTop = document.getElementById('contentdiv').scrollHeight;
		}
	}
		
	toggle_group = function(id){
		var group = document.getElementById('group'+id);
		var group_img = document.getElementById('group_img'+id);
		if(group.style.display == 'none'){
			group.style.display = '';
			group_img.src = 'graphics/minus.gif';
		}
		else{
			group.style.display = 'none';
			group_img.src = 'graphics/plus.gif';
		}
	}
	
	toggle_tab = function(tab, layer_id, k, t, tabname){
		var dataset = document.getElementById('datensatz_' + layer_id + '_' + k);
		var opentab_field = document.getElementById('opentab_' + layer_id + '_' + k);
		var active_tab = dataset.querySelector('.active_tab');
		opentab_field.value = t;
		active_tab.classList.remove("active_tab");
		tab.classList.add("active_tab");
		var groups_to_close = dataset.querySelectorAll('.tab');
		[].forEach.call(groups_to_close, function (group){
			group.classList.add('collapsed');
		});
		var groups_to_open = dataset.querySelectorAll('.tab_' + layer_id + '_' + k + '_' + tabname);
		[].forEach.call(groups_to_open, function (group){
			group.classList.remove('collapsed');
		});
	}

	toggle_layer = function(tab, layer_id){
		enclosingForm.active_layer_id.value = layer_id;
		var active_tab = document.querySelector('.gle_layer_tab.active_tab');
		active_tab.classList.remove("active_tab");
		tab.classList.add("active_tab");
		var layer_to_close = document.querySelectorAll('.layer_results');
		[].forEach.call(layer_to_close, function (layer){
			layer.classList.add('collapsedfull');
		});
		var layer_to_open = document.querySelector('#result_' + layer_id);
		layer_to_open.classList.remove('collapsedfull');
		scrollto_saved_position();
		ahah('index.php?go=set_last_query_layer', 'layer_id=' + layer_id, [], []);
	}

	check_visibility_rule = function(layer_id, rule, scope, k) {
		// Leaf-Regel (kein logic → einfache Bedingung)
		if (!rule.logic && rule.attribute) {
			const field = document.getElementById(layer_id + '_' + rule.attribute + '_' + k);
			return field_has_value(field, rule.operator, rule.value);
		}
		// Logische Gruppe (AND / OR)
		if (rule.logic && Array.isArray(rule.rules)) {
			const results = rule.rules.map(r => check_visibility_rule(layer_id, r, scope, k));

			if (rule.logic === 'AND') {
				return results.every(Boolean);
			}

			if (rule.logic === 'OR') {
				return results.some(Boolean);
			}
		}
		// Fallback (ungültige Regel)
		return false;
	}
	
	check_visibility_dependents = function(layer_id, object, dependents, k) {
		if(object == null)return;
		var group_display;
		dependents.forEach(function(dependent){
			var scope = object.closest('table');		// zuerst in der gleichen Tabelle suchen
			if (scope.querySelector('#visibility_rules_'+dependent) == undefined){
				scope = document;			// ansonsten global
			}
			var rule = JSON.parse(scope.querySelector('#visibility_rules_'+dependent).value);
			
			// visibility of attribute
			var name_dependent = scope.querySelector('#name_'+layer_id+'_'+dependent+'_'+k);
			var value_dependent = scope.querySelector('#value_'+layer_id+'_'+dependent+'_'+k);

			if (check_visibility_rule(layer_id, rule, scope, k)){
				if (name_dependent != null) {
					name_dependent.classList.remove('collapsedfull');
				}
				value_dependent.classList.remove('collapsedfull');
			}
			else {
				if (name_dependent != null) {
					name_dependent.classList.add('collapsedfull');
				}
				value_dependent.classList.add('collapsedfull');
			}
			// visibility of row
			var row = value_dependent.parentNode;
			all_attributes_in_row = [].slice.call(row.childNodes);
			row_display = 'none';
			all_attributes_in_row.forEach(function(td){
				if(td.nodeType == 1 && td.id != '' && !td.classList.contains('collapsedfull')){
					row_display = '';
				}
			})
			row.style.display = row_display;
			if(name_dependent != null){
				var name_row = name_dependent.parentNode;	// in case name row is above value row
				name_row.style.display = row_display;
			}
			// visibility of group
			if(row.closest('table').firstChild.children != null){
				all_trs = [].slice.call(row.closest('table').firstChild.children);		// alle trs in der Gruppe
				group_display = 'none';
				all_trs.forEach(function(tr){
					if(tr.id != '' && tr.style.display != 'none'){
						group_display = '';
					}
				})
				row.closest('div').closest('tr').style.display = group_display;
			}
		})
		// visibility of tabs
		if (document.querySelector('.gle_tabs.tab_' + layer_id + '_' + k) != null) {
			tabs = [].slice.call(document.querySelector('.gle_tabs.tab_' + layer_id + '_' + k).children);
			tabs.forEach(function(tab){
				tab_display = 'none';
				tab_groups = [].slice.call(document.querySelectorAll('.tab.tab_' + tab.classList[0]));
				tab_groups.forEach(function(tab_group){
					if (tab_group.style.display != 'none') {
						tab_display = '';
					}
				})
				tab.style.display = tab_display;
			})
		}
	}

	field_has_value = function(field, operator, value) {
		if (operator == '=')operator = '==';
		var field_value = field.value;
		if (field.type == 'radio') {
			field_value = '';
			var radio = document.querySelector('input[name="'+field.name+'"]:checked');
			if (radio != null) {
				field_value = radio.value;
			}
		}
		if (field.type == 'checkbox') {
			if (
				(operator == '==' && value == 't' && field.checked)  ||
				(operator == '==' && value != 't' && !field.checked) ||
				(operator == '!=' && value == 't' && !field.checked) ||
				(operator == '!=' && value != 't' && field.checked)
			) {
				return true;
			}
			else {
				return false;
			}
		}
		else {
			if (operator == 'IN') {
				return Array.isArray(value) && value.includes(field_value);
			}
			else {
				return eval("'" + field_value + "' " + operator + " '" + value + "'");
			}
		}
	}

	toggleGroup = function(groupname){			// fuer die spaltenweise Ansicht
		var group_elements = document.querySelectorAll('.group_'+groupname);
		var gap_elements = document.querySelectorAll('.gap_'+groupname);
		var group = document.getElementById(groupname);
		var img = document.getElementById('img_'+groupname);
		if(img.src.indexOf('graphics/minus.gif') != -1){		// Gruppe ist aufgeklappt
			[].forEach.call(group_elements, function (group_element){
				group_element.style.display='none';		// Attribute der Gruppe ausblenden
			});
			[].forEach.call(gap_elements, function (gap_element){
				gap_element.colSpan=2;		// Leerspalte zwischen den Gruppen verbreitern
			});
			group.colSpan=1;
			img.src='graphics/plus.gif';
		}
		else{ // Gruppe ist zusammengeklappt
			[].forEach.call(group_elements, function (group_element){
				group_element.style.display='';		// Attribute der Gruppe einblenden
			});
			[].forEach.call(gap_elements, function (gap_element){
				gap_element.colSpan=1;		// Leerspalte zwischen den Gruppen verkleinern
			});
			group.colSpan=group.dataset.origcolspan;
			img.src='graphics/minus.gif';
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

	buildJSONString = function(id, is_array) {
		var field = document.getElementById(id);
		var value;
		var name;
		var type;
		values = new Array();
		field_infos = field.name.split(';');
		datatype = field_infos[6]?.replace('_', '');
		elements = document.getElementsByClassName(id);
		for (i = 0; i < elements.length; i++) {
			if (elements[i].classList[0] == id)	{
				value = elements[i].value;
				name = elements[i].name;
				type = elements[i].type;			
				if (['int', 'int4', 'int8'].includes(datatype)) {
					value = parseInt(value);
				}
				if (['numeric', 'float4', 'float8', 'float'].includes(datatype)) {
					value = parseFloat(value);
				}
				if (type == 'checkbox' && elements[i].checked == false) {
					value = 'f';
				}				
				if (type == 'radio' && elements[i].checked == false) {
					value = '';
				}					
				if(name.slice(-4) != '_alt'){
					if (type == 'file') { // Spezialfall bei Datei-Upload-Feldern:
						if (value != '') {
							value = 'file:' + name; // wenn value vorhanden, wurde eine Datei ausgewählt, dann den Namen des Input-Feldes einsammeln + einem Prefix "file:"
						}
						else {
							old_file_path = document.getElementsByName(name+'_alt');
							if (old_file_path[0] != undefined) {
								value = old_file_path[0].value; // ansonsten den gespeicherten alten Dateipfad
							}
						}
					}
					if (!is_array) { // Datentyp
						if (value == '') {
							value = 'null';
						}
						else {
							if (['{', '['].indexOf(String(value).substring(0,1)) == -1) {		// wenn value kein Array oder Objekt
								value = '"' + value + '"';
							}
						}
						id_parts = elements[i].id.split('_');
						if(id_parts.length == 3)attribute_name = id_parts[1];		// normales Attribut
						else attribute_name = id_parts.pop();										// Nutzerdatentyp-Attribut
						values.push('"' + attribute_name + '":' + value);
					}
					else {
						if (i > 0) { // Array (hier ist das erste Element ein Dummy -> auslassen)
							if (value != '') {
								values.push(value);
							}
						}
					}
				}
			}
		}
		if (!is_array) {
			json = '{'+values.join()+'}';
		}
		else {
			if(values.length > 0){
				json = JSON.stringify(values);
			}
			else{
				json = '';
			}
		}
		field.value = json;
		if (field.onchange) {
			field.onchange();
		}
	}

	addArrayElement = function(fieldname, form_element_type, oid){
		outer_div = document.getElementById(fieldname+'_elements');
		first_element = document.getElementById('div_'+fieldname+'_-1');
		new_element = first_element.cloneNode(true);
		new_id = outer_div.childElementCount - 1;
		new_element.id = 'div_'+fieldname+'_'+new_id;
		var regex = new RegExp(fieldname+'_-1', "g");
		new_element.innerHTML = new_element.innerHTML.replace(regex, fieldname+'_'+new_id);
		new_element.style.display = 'block';
		outer_div.appendChild(new_element);
		buildJSONString(fieldname, true);
	}
	
	removeArrayElement = function(fieldname, remove_element_id){
		var outer_div = document.getElementById(fieldname+'_elements');
		var remove_element = document.getElementById('div_'+remove_element_id);
		getFileAttributesInArray(remove_element);
		outer_div.removeChild(remove_element);
		buildJSONString(fieldname, true);
	}
	
	moveArrayElement = function(fieldname, element_id, direction){
		var element = document.getElementById('div_' + element_id);
		var moved_element, stationary_element;
		if (direction == 'up') {
			if (element.previousSibling.previousSibling) {	// das ist so richtig
				moved_element = element;
				stationary_element = element.previousSibling;
			}
			else {
				moved_element = element;
				stationary_element = null;
			}
		}
		else {
			if (element.nextSibling) {
				moved_element = element.nextSibling;
				stationary_element = element;
			}
			else {
				moved_element = element;
				stationary_element = element.parentNode.childNodes[1];
			}
		}
		element.parentNode.insertBefore(moved_element, stationary_element);
		buildJSONString(fieldname, true);
	}

	function getFileAttributesInArray(remove_element){
		var file_attributes = remove_element.querySelectorAll('input[type="file"]');
		[].forEach.call(file_attributes, function (file_attribute){
			var old_file_path = document.getElementsByName(file_attribute.name + '_alt');
			if (old_file_path[0] != undefined) {
				enclosingForm.delete_documents.value += old_file_path[0].value+'|';
			}
		});
	}
	
	nextdatasets = function(layer_id){
		var sure = true;
		if(document.getElementById('changed_'+layer_id) != undefined && document.getElementById('changed_'+layer_id).value == 1){
			sure = confirm('Die Daten in diesem Thema wurden verändert aber noch nicht gespeichert. Wollen Sie dennoch weiterblättern?');
		}
		if(sure){
			document.getElementsByName('gle_scrollposition_' + enclosingForm.active_layer_id.value)[0].value = 0;
			enclosingForm.target = '';
			enclosingForm.go.value = 'get_last_query';
			if(enclosingForm.go_backup.value != ''){
				enclosingForm.go.value = enclosingForm.go_backup.value;
			}
			obj = document.getElementById('offset_'+layer_id);
			if(obj.value == '' || obj.value == undefined){
				obj.value = 0;
			}
			obj.value = parseInt(obj.value) + <? echo $this->formvars['anzahl']; ?>;
			root.overlay_submit(enclosingForm, false);
		}
	}
	
	lastdatasets = function(layer_id, count){
		var sure = true;
		if(document.getElementById('changed_'+layer_id) != undefined && document.getElementById('changed_'+layer_id).value == 1){
			sure = confirm('Die Daten in diesem Thema wurden verändert aber noch nicht gespeichert. Wollen Sie dennoch weiterblättern?');
		}
		if(sure){
			document.getElementsByName('gle_scrollposition_' + enclosingForm.active_layer_id.value)[0].value = 0;
			enclosingForm.target = '';
			enclosingForm.go.value = 'get_last_query';
			if(enclosingForm.go_backup.value != ''){
				enclosingForm.go.value = enclosingForm.go_backup.value;
			}
			obj = document.getElementById('offset_'+layer_id);
			if(obj.value == '' || obj.value == undefined){
				obj.value = 0;
			}
			obj.value = count - (count % <? echo $this->formvars['anzahl']; ?>);
			root.overlay_submit(enclosingForm, false);
		}
	}
	
	firstdatasets = function(layer_id){
		var sure = true;
		if(document.getElementById('changed_'+layer_id) != undefined && document.getElementById('changed_'+layer_id).value == 1){
			sure = confirm('Die Daten in diesem Thema wurden verändert aber noch nicht gespeichert. Wollen Sie dennoch zurückblättern?');
		}
		if(sure){
			document.getElementsByName('gle_scrollposition_' + enclosingForm.active_layer_id.value)[0].value = 0;
			enclosingForm.target = '';
			enclosingForm.go.value = 'get_last_query';
			if(enclosingForm.go_backup.value != ''){
				enclosingForm.go.value = enclosingForm.go_backup.value;
			}
			obj = document.getElementById('offset_'+layer_id);
			obj.value = 0;
			root.overlay_submit(enclosingForm, false);
		}
	}

	prevdatasets = function(layer_id){
		var sure = true;
		if(document.getElementById('changed_'+layer_id) != undefined && document.getElementById('changed_'+layer_id).value == 1){
			sure = confirm('Die Daten in diesem Thema wurden verändert aber noch nicht gespeichert. Wollen Sie dennoch zurückblättern?');
		}
		if(sure){
			document.getElementsByName('gle_scrollposition_' + enclosingForm.active_layer_id.value)[0].value = 0;
			enclosingForm.target = '';
			enclosingForm.go.value = 'get_last_query';
			if(enclosingForm.go_backup.value != ''){
				enclosingForm.go.value = enclosingForm.go_backup.value;
			}
			obj = document.getElementById('offset_'+layer_id);
			if(obj.value == '' || obj.value == undefined){
				obj.value = 0;
			}
			obj.value = parseInt(obj.value) - <? echo $this->formvars['anzahl']; ?>;
			root.overlay_submit(enclosingForm, false);
		}
	}

	back = function(){
		enclosingForm.go.value = 'Layer-Suche';
		enclosingForm.submit();
	}

	druck = function(){
		enclosingForm.target = '_blank';
		enclosingForm.printversion.value = 'true';
		enclosingForm.go.value = 'get_last_query';
		enclosingForm.submit();
		enclosingForm.target = '';
		enclosingForm.printversion.value = '';
	}
	
	reload_subform_list = function(list_div_id, list_edit, weiter_erfassen, weiter_erfassen_params, further_params){
		root.open_subform_requests++;
		if (typeof list_div_id == 'string') {
			list_div = document.getElementById(list_div_id);
		}
		else {
			list_div = list_div_id;
		}
		var params = list_div.dataset.reload_params;
		if(enclosingForm.name == 'GUI2')params += '&window_type=overlay';
		if(list_edit)params += '&list_edit='+list_edit;
		if(weiter_erfassen)params += '&weiter_erfassen='+weiter_erfassen;
		if(weiter_erfassen_params)params += '&weiter_erfassen_params='+weiter_erfassen_params;
		if(further_params)params += further_params;
		ahah('index.php?go=Layer-Suche_Suchen', params, new Array(list_div), new Array('sethtml'));
	}
	
	convert_belated = function(field){
		if (field.type == 'file' && field.files && field.files.length > 0) {
			file = field.files[0];
			field.setAttribute('onchange', "");
			field.type = 'text';
			field.value  = JSON.stringify({
				name: file.name,
				size: file.size,
				lastmodified: file.lastModified
			});
		}
	}

	save = function() {
		var open_subforms = document.querySelectorAll('.subForm:not(:empty)');
		if (open_subforms.length > 0) {
			message([{'type': 'info', 'msg': 'Es gibt noch offene Unterformulare, die noch nicht gespeichert wurden!'}]);
			return;
		}
		form_fieldstring = enclosingForm.form_field_names.value + '';
		form_fields = form_fieldstring.split('|');
		for (i = 0; i < form_fields.length-1; i++) {
			fieldstring = form_fields[i]+'';
			field = fieldstring.split(';');
			var element = document.getElementsByName(fieldstring)[0];
			
			if (element != undefined && element.type != 'hidden' && field[4] != 'Dokument' && (element.readOnly != true) && field[5] == '0' && element.value == '') {
				message('Das Feld ' + element.title + ' erfordert eine Eingabe.');
				return;
			}

			if (element != undefined && field[6] == 'date' && field[4] != 'Time' && element.value != '') {
				completeDate(element);
				if (!checkDate(element.value)) {
					message('Das Datumsfeld ' + element.title + ' hat nicht das Format TT.MM.JJJJ.');
					return;
				}
			}

			if (element != undefined && field[6] == 'time' && field[4] != 'Time' && element.value != '' && !checkDate(element.value)) {
				completeTime(element);
				if(!checkTime(element.value)){
					message('Das Uhrzeitfeld ' + element.title + ' hat nicht das Format hh:mm:ss.');
					return;
				}
			}
			if (upload_only_file_metadata == 1) {
				if (field[4] == 'Dokument') {
					convert_belated(element);
				}
			}
		}
		enclosingForm.go.value = 'Sachdaten_speichern';
		document.getElementById('loader').style.display = '';
		setTimeout('document.getElementById(\'loaderimg\').src=\'graphics/ajax-loader.gif\'', 50);
		root.document.GUI.gle_changed.value = '';
		root.overlay_submit(enclosingForm, false);
	}

	save_new_dataset = function(){
		if (
			(enclosingForm.newpath != undefined && enclosingForm.newpath.value == '' && enclosingForm.loc_x == undefined) 
			|| 
			(enclosingForm.loc_x != undefined && enclosingForm.loc_x.value == '')
		){
			if (geom_not_null) {
				message('Sie müssen noch eine Geometrie für den Datensatz erfassen!');
				return;
			}
			else {
				if (!confirm('Wollen Sie den Datensatz wirklich ohne Geometrie anlegen?')) {
					return;
				}
			}
		}
  	form_fieldstring = enclosingForm.form_field_names.value+'';
		form_fields = form_fieldstring.split('|');
		for (i = 0; i < form_fields.length; i++) {
			fieldstring = form_fields[i] + '';
			field = fieldstring.split(';');
			var element = document.getElementsByName(fieldstring)[0];
			if (
				element != undefined &&
				element.type != 'hidden' &&
				// field[4] != 'SubFormFK' &&
				field[7] != '0' &&
				element.readOnly != true &&
				field[5] == '0' &&
				element.value == ''
			) {
			  message('Das Feld ' + element.title +' erfordert eine Eingabe.');
				return;
			}
			if (element != undefined && field[6] == 'date' && field[4] != 'Time' && element.value != '' && !checkDate(element.value)){
				completeDate(element);
				if(!checkDate(element.value)){
					message('Das Datumsfeld '+element.title+' hat nicht das Format TT.MM.JJJJ.');
					return;
				}
			}
			if (upload_only_file_metadata == 1) {
				if (field[4] == 'Dokument') {
					convert_belated(element);
				}
			}
		}
		enclosingForm.go.value = 'neuer_Layer_Datensatz_speichern';
		document.getElementById('sachdatenanzeige_save_button').disabled = true;
		root.document.GUI.gle_changed.value = '';
		overlay_submit(enclosingForm, false);
	}

	subdelete_data = function(layer_id, fromobject, oid, reload_object){
		// layer_id ist die von dem Layer, in dem der Datensatz geloescht werden soll
		// fromobject ist die id von dem div, welches das Formular des Datensatzes enthaelt, welches entfernt wird
		// reload_object ist die id vom gesamten Subformular, welches nach Loeschung des Datensatzes aktualisiert werden soll (optional)
		if (confirm('Wollen Sie die ausgewählten Datensätze wirklich löschen?')) {
			var formData = new FormData();
			formData.append('go', 'Layer_Datensatz_Loeschen');
			formData.append('chosen_layer_id', layer_id);
			formData.append('oid', oid);
			formData.append('reload_object', reload_object);
			ahah('index.php', formData, new Array(document.getElementById(fromobject), ''), new Array('sethtml', 'execute_function'));
		}
	}

	subdelete_all = function(layer_id, fromobject, targetobject, reload) {
		// layer_id ist die von dem Layer, in dem die Datensätze gespeichert werden soll
		// fromobject ist die id von dem div, welches das Formular der Datensätze enthält
		// targetobject ist die id von dem Objekt im Hauptformular, welches nach Speicherung des Datensatzes aktualisiert werden soll
		if (confirm('Wollen Sie die Datensätze wirklich löschen?')) {
			checkboxes = Array.prototype.slice.call(document.getElementById(fromobject).querySelectorAll('.check_' + layer_id));
			checkbox_names = '';
			var formData = new FormData();
			for (i = 0; i < checkboxes.length; i++) {
				checkbox_names += checkboxes[i].name + '|';
				formData.append(checkboxes[i].name, 'on');
			}
			formData.append('go', 'Layer_Datensaetze_Loeschen');
			if (reload) {
				formData.append('reload', reload);
			}
			formData.append('chosen_layer_id', layer_id);
			formData.append('targetobject', targetobject);
			formData.append('checkbox_names_' + layer_id, checkbox_names);
			formData.append('embedded', 'true');
			ahah('index.php', formData, new Array(document.getElementById(fromobject), ''), new Array('sethtml', 'execute_function'));
		}
	}

	subsave_data = function(layer_id, fromobject, targetobject, reload) {
		// layer_id ist die von dem Layer, in dem die Datensätze gespeichert werden soll
		// fromobject ist die id von dem div, welches das Formular der Datensätze enthält
		// targetobject ist die id von dem Objekt im Hauptformular, welches nach Speicherung des Datensatzes aktualisiert werden soll
		form_fields = Array.prototype.slice.call(document.getElementById(fromobject).querySelectorAll('.subform_' + layer_id));
		form_fieldstring = '';
		var formData = new FormData();
		for (i = 0; i < form_fields.length; i++) {
			if (form_fields[i].name.indexOf(';') != -1 && form_fields[i].name.slice(-4) != '_alt') {
				form_fieldstring += form_fields[i].name + '|';
			}
			field = form_fields[i].name.split(';');
			if (field[4] != 'Dokument' && form_fields[i].readOnly != true && field[5] == '0' && form_fields[i].value == ''){
				message('Das Feld ' + form_fields[i].title + ' erfordert eine Eingabe.');
				return;
			}
			if (field[6] == 'date' && field[4] != 'Time' && form_fields[i].value != '' && !checkDate(form_fields[i].value)){
				completeDate(form_fields[i]);
				if(!checkDate(form_fields[i].value)){
					message('Das Datumsfeld ' + form_fields[i].title + ' hat nicht das Format TT.MM.JJJJ.');
					return;
				}
			}
			if (['checkbox', 'radio'].indexOf(form_fields[i].type) == -1 || form_fields[i].checked) {
				if (upload_only_file_metadata == 1) {
					if (field[4] == 'Dokument') {
						convert_belated(form_fields[i]);
					}
				}
				if (form_fields[i].type == 'file' && form_fields[i].files[0] != undefined) {
					value = form_fields[i].files[0];
				}
				else {
					value = form_fields[i].value;
				}
				formData.append(form_fields[i].name, value);
			}
		}
		root.document.GUI.gle_changed.value = '';
		formData.append('go', 'Sachdaten_speichern');
		if (reload) {
			formData.append('reload', reload);
		}
		formData.append('selected_layer_id', layer_id);
		formData.append('targetobject', targetobject);
		formData.append('form_field_names', form_fieldstring);
		formData.append('embedded', 'true');
		ahah('index.php', formData, new Array(''), new Array('execute_function'));
	}

	subsave_new_layer_data = function(layer_id, fromobject, targetobject, targetlayer_id, targetattribute, reload, list_edit){
		// layer_id ist die von dem Layer, in dem ein neuer Datensatz gespeichert werden soll
		// fromobject ist die id von dem div, welches das Formular zur Eingabe des neuen Datensatzes enthaelt
		// targetobject ist die id von dem Objekt im Hauptformular, welches nach Speicherung des neuen Datensatzes aktualisiert werden soll
		// targetlayer_id ist die von dem Layer, zu dem das targetobject gehoert
		// targetattribute ist das Attribut, zu dem das targetobject gehoert
  	form_fields = Array.prototype.slice.call(document.getElementById(fromobject).querySelectorAll('.subform_'+layer_id));
		form_fieldstring = '';
		var formData = new FormData();
  	for(i = 0; i < form_fields.length; i++){
			if(form_fields[i].name.slice(-4) != '_alt')form_fieldstring += form_fields[i].name+'|';
  		field = form_fields[i].name.split(';');
			if(field[4] != 'Dokument' && form_fields[i].readOnly != true && form_fields[i].type != 'hidden' && field[5] == '0' && form_fields[i].value == ''){
  			message('Das Feld '+form_fields[i].title+' erfordert eine Eingabe.');
  			return;
  		}
  		if(field[6] == 'date' && field[4] != 'Time' && form_fields[i].value != '' && !checkDate(form_fields[i].value)){
  			completeDate(form_fields[i]);
				if(!checkDate(form_fields[i].value)){
					message('Das Datumsfeld '+form_fields[i].title+' hat nicht das Format TT.MM.JJJJ.');
					return;
				}
  		}
			if (['checkbox', 'radio'].indexOf(form_fields[i].type) == -1 || form_fields[i].checked) {
				if(form_fields[i].type == 'file' && form_fields[i].files[0] != undefined)value = form_fields[i].files[0];
				else value = form_fields[i].value;
				formData.append(form_fields[i].name, value);
			}
  	}
		root.document.GUI.gle_changed.value = '';
		formData.append('go', 'neuer_Layer_Datensatz_speichern');
		if(reload)formData.append('reload', reload);
		formData.append('selected_layer_id', layer_id);
		formData.append('targetobject', targetobject);
		formData.append('targetlayer_id', targetlayer_id);
		formData.append('targetattribute', targetattribute);
		formData.append('form_field_names', form_fieldstring);
		formData.append('embedded', 'true');
		formData.append('list_edit', list_edit);
		ahah('index.php', formData, new Array(document.getElementById(fromobject), document.getElementById(targetobject), ''), new Array('sethtml', 'sethtml', 'execute_function'));
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
		auto_resize_overlay();
	}

	clearsubform = function(subformid){
		document.getElementById(subformid).innerHTML = '';
		auto_resize_overlay();
	}
	
	switch_gle_view1 = function(layer_id, from_mode, to_mode, button){
		var active_button = enclosingForm.querySelector('.gle-view-button.active');
		var div = enclosingForm.querySelector('#result_' + layer_id + '>#layer>div.records');
		var req = 'go=switch_gle_view&chosen_layer_id=' + layer_id + '&mode=' + to_mode;
		var reload = false;

		if (from_mode == 0 && to_mode > 0 || from_mode > 0 && to_mode == 0){		// Wechsel zwischen 0 und 1/2
			overlay_link(req + '&reload=1');
		}
		else {																																	// Wechsel zwischen 1 und 2
			active_button.classList.remove('active');
			button.classList.add('active');
			div.style.display = (to_mode == 1? '' : 'flex');
			auto_resize_overlay();
			ahah('index.php', req, [], []);
		}		
	}
	
	autocomplete1 = function(event, layer_id, attribute, field_id, inputvalue, listentyp, k, req_attr_names_array, req_by_attr_name) {
		listentyp = listentyp || 'ok';
		var suggest_field = document.getElementById('suggests_' + field_id);

		// required_by-Attribute leeren
		var req_by = document.getElementById('output_' + layer_id + '_' + req_by_attr_name + '_' + k);
		if (req_by) {
			req_by.value = '';
		}
		// requires-Attributdaten holen
		req_attribute_data = get_attribute_values(document.getElementById(field_id), k, layer_id, req_attr_names_array);

		if(event.key == 'ArrowDown'){
			suggest_field.firstChild.selectedIndex = suggest_field.firstChild.selectedIndex + 1;
		}
		else if(event.key == 'ArrowUp'){
			suggest_field.firstChild.selectedIndex = suggest_field.firstChild.selectedIndex - 1;
		}
		else if(event.key == 'Enter'){
			suggest_field.firstChild.click();
		}
		else if(event.key == 'Tab'){
			// nix machen
		}
		else if(event.key == 'Escape'){
			document.getElementById('output_'+field_id).onchange();
		}
		else{
			suggest_field.style.display = 'none';
			if (inputvalue.length > 0) {
				ahah('index.php', 'go=autocomplete_request&layer_id=' + layer_id + '&attribute=' + attribute + '&inputvalue=' + inputvalue + '&field_id=' + field_id + '&attributenames=' + req_attribute_data['attributenames'] + '&attributevalues=' + req_attribute_data['attributevalues'] + (listentyp != '' ? '&listentyp=' + listentyp : ''), new Array(suggest_field, ""), new Array("sethtml", "execute_function"));
			}
			else{
				document.getElementById(field_id).value = '';
			}
		}
	}

	get_current_attribute_values = function(layer_id, attributenamesarray, geom_attribute, k){
		var attributenames = '';
		var attributevalues = '';
		var geom = '';
		var value = '';
		for(i = 0; i < attributenamesarray.length; i++){
			if(document.getElementById(layer_id+'_'+attributenamesarray[i]+'_'+k) != undefined){
				value = document.getElementById(layer_id+'_'+attributenamesarray[i]+'_'+k).value;
				if (attributenamesarray[i] == geom_attribute) {
					value = "'" + value + "'";
				}
				attributenames += attributenamesarray[i] + '|';
				attributevalues += encodeURIComponent(value) + '|';
			}
			else if(attributenamesarray[i] == geom_attribute){	// wenn es das Geometrieattribut ist, handelt es sich um eine Neuerfassung --> aktuelle Geometrie nehmen
				if(enclosingForm.loc_x != undefined && enclosingForm.loc_x.value != ''){		// Punktgeometrie
					geom = 'POINT('+enclosingForm.loc_x.value+' '+enclosingForm.loc_y.value+')';
				}
				else if(enclosingForm.newpathwkt.value == ''){		// Polygon- oder Liniengeometrie
					if(enclosingForm.newpath.value != ''){
						geom = SVG.buildwktpolygonfromsvgpath(enclosingForm.newpath.value);
					}
				}
				attributenames += attributenamesarray[i] + '|';
				if (geom != '') {
					geom = 'SRID=<? echo $this->user->rolle->epsg_code; ?>;' + geom;		// EWKT mit dem user-epsg draus machen
				}
				else {
					geom = 'POINT EMPTY';		// leere Geometrie zurückliefern
				}
				attributevalues += 'st_geometryfromtext(\'' + geom + '\')|';
			}
		}
		return new Array(attributenames, attributevalues);
	}
	
	auto_generate = function(attributenamesarray, geom_attribute, attribute, k, layer_id){
		names_values = get_current_attribute_values(layer_id, attributenamesarray, geom_attribute, k);
		ahah("index.php", "go=auto_generate&layer_id="+layer_id+"&attribute="+attribute+"&attributenames="+names_values[0]+"&attributevalues="+names_values[1], new Array(document.getElementById(layer_id+'_'+attribute+'_'+k)), new Array("setvalue"));
	}
	
	openCustomSubform = function(layer_id, attribute, attributenamesarray, field_id, k){
		names_values = get_current_attribute_values(layer_id, attributenamesarray, '', k);
		document.getElementById('waitingdiv').style.background = 'rgba(200,200,200,0.8)';
		document.getElementById('waitingdiv').style.display = '';
		subformWidth = document.GUI.browserwidth.value-70;
		subform = '<div style="position:relative; margin: 30px;width:'+subformWidth+'px; height:90%">';
		subform += '<div style="position: absolute;top: 2px;right: -2px"><a href="javascript:closeCustomSubform();" title="Schlie&szlig;en"><img style="border:none" src="<? echo GRAPHICSPATH.'exit2.png'; ?>"></img></a></div>';
		subform += '<iframe id="customSubform" style="width:100%; height:100%" src=""></iframe>';
		subform += '</div>';
		document.getElementById('waitingdiv').innerHTML= subform;
		ahah("index.php", "go=openCustomSubform&layer_id="+layer_id+"&attribute="+attribute+"&attributenames="+names_values[0]+"&attributevalues="+names_values[1]+"&field_id="+field_id, new Array(document.getElementById('customSubform')), new Array("src"));
	}
	
	closeCustomSubform = function(){
		document.getElementById('waitingdiv').style.display = 'none';
		document.getElementById('waitingdiv').innerHTML = '';
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

	selectall = function(layer_id) {
		var k = 0,
				obj = document.getElementById(layer_id + '_' + k),
				status = obj.checked;

		while (obj != undefined) {
			if (obj.offsetParent !== null) {	// nur wenn Datensatz sichtbar
				obj.checked = !status;
			}
			k++;
			obj = document.getElementById(layer_id + '_' + k);
		}
		count_selected(layer_id);
		document.getElementById('selectDatasetsLinkText_' + layer_id).classList.toggle('hidden');
		document.getElementById('deselectDatasetsLinkText_' + layer_id).classList.toggle('hidden');
		message([{ 'type': 'notice', 'msg': (status ? '<? echo $strAllDeselected; ?>' : '<? echo $strAllSelected; ?>')}]);
	}
	
	get_position_qrcode = function(layer_id, oid) {
		var img = document.getElementById('qr_' + layer_id + '_' + oid);
		img.src = 'index.php?go=get_position_qrcode&layer_id=' + layer_id + '&oid=' + oid;
	}

	remove_position_qrcode = function(layer_id, oid) {
		var img = document.getElementById('qr_' + layer_id + '_' + oid);
		img.src = 'graphics/leer.gif';
	}

	zoom2object = function(layer_id, columnname, oid, selektieren){
		params = 'go=zoomto_dataset&oid='+oid+'&layer_columnname='+columnname+'&layer_id='+layer_id+'&selektieren='+selektieren;
		if (root.document.getElementById('mapimage') != null) {					// aus overlay und Hauptkarte im root Fenster heraus --> Kartenzoom per Ajax machen
			startwaiting();
			root.get_map_ajax(params, '', 'highlight_object('+layer_id+', '+oid+');');		// Objekt highlighten
		}
		else {
			root.location.href = 'index.php?'+params;		// aus normaler Sachdatenanzeige heraus --> normalen Kartenzoom machen
		}
	}
	
	zoom2wkt = function(wkt, epsg){
		params = 'go=zoom2wkt&wkt='+wkt+'&epsg='+epsg;
		if (root.document.getElementById('mapimage') != null) {					// aus overlay und Hauptkarte im root Fenster heraus --> Kartenzoom per Ajax machen
			startwaiting();
			root.get_map_ajax(params, '', '');
		}
		else{
			root.location.href = 'index.php?'+params;		// aus normaler Sachdatenanzeige heraus --> normalen Kartenzoom machen
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
			message('Es wurde kein Datensatz ausgewählt.');
			return false;
		}
		else{
			return true;
		}
	}

	zoomto_datasets = function(layer_id, tablename, columnname){
		if(check_for_selection(layer_id)){
			enclosingForm.chosen_layer_id.value = layer_id;
			enclosingForm.layer_tablename.value = tablename;
			enclosingForm.layer_columnname.value = columnname;
			enclosingForm.go.value = 'zoomto_selected_datasets';
			root.overlay_submit(enclosingForm, false, 'root');
		}
	}

	delete_datasets = function(layer_id) {
		var no_edit_checkboxes = document.querySelectorAll('.no_edit');
		[].forEach.call(no_edit_checkboxes, function (checkbox){
				checkbox.checked = false;		// nicht editierbare Datensaetze deselektieren
			});
		if (check_for_selection(layer_id)){
			if(confirm('Wollen Sie die ausgewählten Datensätze wirklich löschen?')){
				enclosingForm.chosen_layer_id.value = layer_id;
				enclosingForm.go.value = 'Layer_Datensaetze_Loeschen';
				root.overlay_submit(enclosingForm, false);
			}
		}
	}

	delete_document = function(attributename, layer_id, fromobject, targetobject, reload){
		if (confirm('Wollen Sie das ausgewählte Dokument wirklich löschen?')){
			field = document.getElementsByName(attributename);
			field[0].type = 'hidden'; // bei einem Typ "file" kann man sonst den value nicht setzen
			field[0].value = 'file:' + attributename;	// damit der JSON-String eines evtl. vorhandenen übergeordneten Attributs richtig gebildet wird
			field[0].onchange(); // --||--
			field[0].value = 'delete';
			if (fromobject != '') {		// SubForm-Layer
				subsave_data(layer_id, fromobject, targetobject, reload);
			}
			else {												// normaler Layer
				enclosingForm.go.value = 'Sachdaten_speichern';
				root.document.GUI.gle_changed.value = '';
				root.overlay_submit(enclosingForm, false);
			}
		}
	}

	daten_export = function(layer_id, anzahl, format){
		enclosingForm.all.value = (document.getElementById('all_' + layer_id + '_2').checked? '1' : '');
		if(enclosingForm.all.value || check_for_selection(layer_id)){				// entweder alle gefundenen oder die ausgewaehlten
			var option = document.createElement("option");
			option.text = anzahl;
			option.value = anzahl;
			enclosingForm.anzahl.add(option);
			enclosingForm.anzahl.selectedIndex = enclosingForm.anzahl.options.length-1;
			enclosingForm.chosen_layer_id.value = layer_id;
			enclosingForm.go_backup.value = enclosingForm.go.value;
			enclosingForm.go.value = 'Daten_Export';
			root.overlay_submit(enclosingForm, false, 'root');
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
			saved_go = enclosingForm.go.value;
			enclosingForm.chosen_layer_id.value = layer_id;
			enclosingForm.go.value = 'Datensaetze_Merken';
			formdata = new FormData(enclosingForm);
			root.ahah("index.php", formdata, new Array(), new Array());
			enclosingForm.go.value = saved_go;
			message([{'type': 'notice', 'msg': 'Datensätze gemerkt'}]);
		}
	}

	remove_from_clipboard = function(layer_id){
		if(check_for_selection(layer_id)){
			saved_go = enclosingForm.go.value;
			enclosingForm.chosen_layer_id.value = layer_id;
			enclosingForm.go.value = 'Datensaetze_nicht_mehr_merken';
			formdata = new FormData(enclosingForm);
			root.ahah("index.php", formdata, new Array(), new Array());
			enclosingForm.go.value = saved_go;
			message([{'type': 'notice', 'msg': 'Datensätze entfernt'}]);
		}
	}

	use_for_new_dataset = function(layer_id){
		if(check_for_selection(layer_id)){
			enclosingForm.chosen_layer_id.value = layer_id;
			enclosingForm.pathwkt.value = '';
			enclosingForm.newpathwkt.value = '';
			enclosingForm.newpath.value = '';
			enclosingForm.go_backup.value = enclosingForm.go.value;
			enclosingForm.go.value = 'neuer_Layer_Datensatz';
			if(document.getElementById('geom_privileg_'+layer_id).value == 1){
				root.overlay_submit(enclosingForm, false, 'root');
			}
			else{
				root.overlay_submit(enclosingForm, false);
			}
		}
	}
	
	dublicate_dataset = function(layer_id){
		if(check_for_selection(layer_id)){
			if(confirm('Der Datensatz und alle mit ihm verknüpften Objekte werden kopiert. Wollen Sie fortfahren?')){
				enclosingForm.chosen_layer_id.value = layer_id;
				enclosingForm.go_backup.value = enclosingForm.go.value;
				enclosingForm.go.value = 'Datensatz_dublizieren';
				root.overlay_submit(enclosingForm, false);
			}
		}
	}

	print_data = function(layer_id){
		if (check_for_selection(layer_id)) {
			enclosingForm.chosen_layer_id.value = layer_id;
			enclosingForm.go_backup.value = enclosingForm.go.value;
			enclosingForm.go.value = 'generischer_sachdaten_druck';
			root.overlay_submit(enclosingForm, false, 'root');
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
			enclosingForm.target = "_blank";
			enclosingForm.chosen_layer_id.value = layer_id;
			enclosingForm.width.value = 700;
			enclosingForm.go_backup.value = enclosingForm.go.value;
			enclosingForm.go.value = 'generisches_sachdaten_diagramm';
			root.overlay_submit(enclosingForm, false, 'root');
			enclosingForm.target = "";
		}
	}

	get_attribute_field = function(object, k, layer_id, attributename){
		// object ist das Objekt welches diese Funktion ausgeloest hat
		// k die Nummer des Datensatzes
		// attributename Name des Attributfelds, das geholt werden soll
		// gibt eine Nodelist zurück
		// die Layer-ID muss aufgesplittet werden, um sie für css zu escapen
		var id = layer_id.toString();
		var id1 = id.substring(0, 1);
		var id2 = id.substring(1);
		//var scope = object.closest('table'); // zuerst in der gleichen Tabelle suchen
		var scope = object.closest('table'); // zuerst in der gleichen Tabelle suchen
		if (scope.querySelector('#\\3' + id1 + ' ' + id2 + '_' + attributename + '_' + k) == undefined) {
			scope = document; // ansonsten global
		}
		return scope.querySelectorAll('#\\3' + id1 + ' ' + id2 + '_' + attributename + '_' + k);
	}

	get_attribute_values = function(object, k, layer_id, attributenamesarray){
		// object ist das Objekt welches diese Funktion ausgeloest hat
		// k die Nummer des Datensatzes
		// attributenamesarray ein Array der Attribute, deren Werte geholt werden sollen
		var attributenames = '';
		var attributevalues = '';
		var field;
		for(var i = 0; i < attributenamesarray.length; i++){
			field = get_attribute_field(object, k, layer_id, attributenamesarray[i])[0];
			if (field != undefined){
				attributenames += attributenamesarray[i] + '|';
				attributevalues += encodeURIComponent(field.value) + '|';
			}
		}
		return {
			'attributenames' : attributenames,
			'attributevalues' : attributevalues
		};
	}

	update_require_attribute = function(object, attributes, k, layer_id, attributenamesarray){
		// object ist das Objekt welches diese Funktion ausgeloest hat
		// attributes ist eine Liste von zu aktualisierenden Attributen
		// k die Nummer des Datensatzes
		// attributenamesarray ein Array aller Attribute im Formular
		var datatype = '';
		if(object.dataset.datatype_id)datatype = '&datatype_id='+object.dataset.datatype_id;
		attribute_data = get_attribute_values(object, k, layer_id, attributenamesarray);
		attribute = attributes.split(',');
		for(var i = 0; i < attribute.length; i++){
			var elements = [].slice.call(get_attribute_field(object, k, layer_id, attribute[i]));
			elements.forEach(function(element){
				var target = element;
				var type = element.type;
				var action = 'sethtml';
				if(['text', 'select-one', 'hidden'].indexOf(type) !== -1){
					if (type == 'text'){
						action = 'setvalue';
					}
					ahah("index.php", "go=get_select_list&layer_id="+layer_id+datatype+"&attribute="+attribute[i]+"&attributenames="+attribute_data['attributenames']+"&attributevalues="+attribute_data['attributevalues']+"&type="+type, new Array(target), new Array(action));
				}
			})
		}
	}

	change_orderby = function(attribute, layer_id){
		enclosingForm.go.value = 'get_last_query';
		if(document.getElementById('orderby'+layer_id).value == attribute){
			document.getElementById('orderby'+layer_id).value = attribute+' DESC';
		}
		else{
			document.getElementById('orderby'+layer_id).value = attribute;
		}
		overlay_submit(enclosingForm);
	}
	
	switch_edit_all = function(layer_id){
		if(document.getElementById('edit_all3_'+layer_id).style.display == 'none'){
			var obj = document.getElementById(layer_id+'_0');
			if(!obj.checked)selectall(layer_id);
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
	
	change_all = function(layer_id, k, layerid_attribute){
		allfield = document.getElementById(layerid_attribute+'_'+k);
		for(var i = 0; i < k; i++){			
			if(document.getElementById(layer_id+'_'+i).checked){
				formfield = document.getElementById(layerid_attribute+'_'+i);
				if(formfield.onchange){		// nur editierbare Felder aendern
					if(formfield.type == 'checkbox'){
						formfield.checked = allfield.checked;
					}
					else{
						formfield.value = allfield.value;
					}
					document.getElementById(layerid_attribute+'_'+i).onchange();
				}
			}
		}		
	}

	set_changed_flag = function(field, flag_name) {
		// rausgenommen wegen Problem mit Radiobuttons
		/*if (field.type != 'file') {
			var same_fields = document.querySelectorAll('[name="' + field.name + '"]');
			[].forEach.call(same_fields, function (same_field) {
				same_field.value = field.value;	// alle gleichen Felder auf den selben Wert setzen, falls der gleiche Datensatz im GLE nochmal vorkommt (durch Subforms)
			});
		}*/
		root.changed_form_fields.push(field);
		var flags = document.querySelectorAll('[name="' + flag_name + '"]');
		[].forEach.call(flags, function (flag){
			if(flag != undefined){
				flag.value=1;
				if(flag.onchange)flag.onchange();
			}
		});
	}
	
	activate_save_button = function(layerdiv, layer_id){
		var button = layerdiv.querySelector('#subform_save_button_'+layer_id);
		if(button && button.style.display == 'none'){
			button.style.display = '';
		}		
	}

	filter_results = function(attribute_class, select){
		var options = select.selectedOptions;
		var values = Array.from(options).map(({ value }) => value);
		var fields = document.querySelectorAll('.gle_attribute_value .' + attribute_class);
		var value;
		if (values[0] != '#all#'){
			select.previousElementSibling.style.color = 'gray';
		}
		else {
			select.previousElementSibling.style.color = '#bfbfbf';
		}
		[].forEach.call(fields, function (field){
			if (field.type == 'checkbox' && !field.checked) {
				value = 'f';
			}
			else {
				value = field.value;
			}
			var tr = field.closest('tr');
			if (values[0] != '#all#' && !values.includes(value)) {
				tr.style.display = 'none';
				tr.classList.add(attribute_class);
			}
			else {
				tr.classList.remove(attribute_class);
				if (tr.className == '') {
					tr.style.display = '';
				}
			}
		});
	}

	count_selected = function(layer_id){
		document.getElementById('selected_count_' + layer_id).innerHTML = document.querySelectorAll('.check_' + layer_id + ':checked').length;
	}

</script>