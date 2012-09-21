<?php
 # 2008-09-30 sr
  include(LAYOUTPATH.'languages/generic_layer_editor_2_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
 ?>
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

function zoomto_datasets(layer_id, tablename, columnname){
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
	}
	else{
		document.GUI.chosen_layer_id.value = layer_id;
		document.GUI.layer_tablename.value = tablename;
		document.GUI.layer_columnname.value = columnname;
		document.GUI.go.value = 'zoomto_selected_datasets';
		document.GUI.submit();
	}
}

function delete_datasets(layer_id){
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
	}
	else{
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

function csv_export(layer_id){
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
	}
	else{
		document.GUI.chosen_layer_id.value = layer_id;
		document.GUI.go_backup.value = document.GUI.go.value;
		document.GUI.go.value = 'generischer_csv_export';
		document.GUI.submit();
	}
}

function print_data(layer_id){
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
	}
	else{
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
	go = 'false';
	if(document.getElementsByName('charttype_'+layer_id)[0].value == 'mirrorbar' && ((document.getElementsByName('chartsplit_'+layer_id)[0].value == '') || (document.getElementsByName('chartvalue_'+layer_id)[0].value == ''))){
		return;
	}
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
	}
	else{
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
<?php
 # Variablensubstitution
 $layer = $this->qlayerset[$i];
 $attributes = $layer['attributes'];
 
?>
<div id="layer">

<h2><? echo $layer['Name'] ?></h2>
<?
	$doit = false;
  $anzObj = count($layer['shape']);
  if ($anzObj > 0) {
  	$this->found = 'true';
  	$doit = true;
  }
  if($this->new_entry == true){
  	$anzObj = 1;
  	$doit = true;
  }
  if($doit == true){
?>
<table border="0" cellspacing="10" cellpadding="2">
<?
	$checkbox_names = '';
	$columnname = '';
	$tablename = '';
	$geomtype = '';
	$dimension = '';
	$privileg = '';
	for ($k=0;$k<$anzObj;$k++) {
		$checkbox_names .= 'check;'.$attributes['table_alias_name'][$attributes['name'][0]].';'.$attributes['table_name'][$attributes['name'][0]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][0]].'_oid'].'|';
?>
	<tr>
	  <td>
	    <div id="datensatz">
	    <input type="hidden" value="" name="changed_<? echo $layer['shape'][$k][$attributes['table_name'][$attributes['name'][0]].'_oid']; ?>"> 
	    <table class="tgle" border="1">
	      <thead class="gle">
	        <th colspan="2" style="background-color:<? echo BG_GLEHEADER; ?>;">
			  <? if($this->new_entry != true AND $this->formvars['printversion'] == ''){ ?>
			  <table width="100%">
			    <tr>
			      <td>
			        <input id="<? echo $layer['Layer_ID'].'_'.$k; ?>" type="checkbox" name="check;<? echo $attributes['table_alias_name'][$attributes['name'][0]].';'.$attributes['table_name'][$attributes['name'][0]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][0]].'_oid']; ?>">&nbsp;
			        <span style="color:<? echo TXT_GLEHEADER; ?>;"><? echo $strSelectThisDataset; ?></span>
			      </td>
			      <td align="right">
			      	<a id="uko_<? echo $layer['Layer_ID'].'_'.$k; ?>" style="visibility:hidden" href="" title="<? echo $strUKOExportThis; ?>"><img src="<? echo GRAPHICSPATH; ?>datensatz_exportieren_uko.png" border="0"></a>&nbsp;&nbsp;
			        <a href="javascript:select_this_dataset(<? echo $layer['Layer_ID']; ?>, <? echo $k; ?>);csv_export(<? echo $layer['Layer_ID']; ?>);" title="<? echo $strCSVExportThis; ?>"><img src="<? echo GRAPHICSPATH; ?>datensatz_exportieren_csv.png" border="0"></a>&nbsp;&nbsp;
			        <? if($layer['privileg'] == '2'){ ?>
			        	<a href="javascript:select_this_dataset(<? echo $layer['Layer_ID']; ?>, <? echo $k; ?>);delete_datasets(<?php echo $layer['Layer_ID']; ?>);" title="<? echo $strDeleteThisDataset; ?>"><img src="<? echo GRAPHICSPATH; ?>datensatz_loeschen.png" border="0"></a>
			        <? } ?>
			      </td>
			    </tr>
			  </table>
			  <? } ?>
		    </th>
		  </thead>
          <tbody class="gle">
<?		$trans_oid = explode('|', $layer['shape'][$k]['lock']);
			if($layer['shape'][$k]['lock'] == 'bereits übertragen' OR $trans_oid[1] != '' AND $layer['shape'][$k][$attributes['table_name'][$attributes['name'][0]].'_oid'] == $trans_oid[1]){
				echo '<tr><td colspan="2" align="center"><span class="red">Dieser Datensatz wurde bereits übertragen und kann nicht bearbeitet werden.</span></td></tr>';
				$lock[$k] = true;
			}
			for($j = 0; $j < count($attributes['name']); $j++){
				if($layer['shape'][$k][$attributes['name'][$j]] == ''){
					$layer['shape'][$k][$attributes['name'][$j]] = $this->formvars[$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j]];
				}
				if($this->new_entry == true AND $attributes['default'][$j] != '' AND $layer['shape'][$k][$attributes['name'][$j]] == ''){		# Default-Werte setzen
					$layer['shape'][$k][$attributes['name'][$j]] = $attributes['default'][$j];
				}
				if($attributes['privileg'][$j] == '0' AND $attributes['form_element_type'][$j] == 'Auswahlfeld' OR $attributes['type'][$j] == 'not_saveable'){				# entweder ist es ein nicht speicherbares Attribut oder ein nur lesbares Auswahlfeld, dann ist es auch nicht speicherbar
					$attributes['form_element_type'][$j] .= '_not_saveable';
				}
				if($attributes['invisible'][$attributes['name'][$j]] != 'true' AND $attributes['name'][$j] != 'lock'){
					if($attributes['type'][$j] != 'geometry'){
							echo '<tr><td valign="top" bgcolor="'.BG_GLEATTRIBUTE.'">';
							if($attributes['privileg'][$j] != '0' AND !$lock[$k]){
								$this->editable = 'true';
							}
							if($attributes['alias'][$j] == ''){
								$attributes['alias'][$j] = $attributes['name'][$j];
							}
							echo '<table width="100%"><tr><td>';
							if($attributes['form_element_type'][$j] != 'SubFormPK' AND $attributes['form_element_type'][$j] != 'SubFormEmbeddedPK'){
								echo '<a style="font-size: '.$this->user->rolle->fontsize_gle.'px" title="Sortieren nach '.$attributes['alias'][$j].'" href="javascript:change_orderby(\''.$attributes['name'][$j].'\', '.$layer['Layer_ID'].');">
							 					'.$attributes['alias'][$j].'</a>';
							}
							else{
								echo '<span style="font-size: '.$this->user->rolle->fontsize_gle.'px; color:#222222;">'.$attributes['alias'][$j].'</span>';
							}
							if($attributes['nullable'][$j] == '0' AND $attributes['privileg'][$j] != '0'){
								echo '<span title="Eingabe erforderlich">*</span>';
							}
							if ($attributes['tooltip'][$j]!='' AND $attributes['form_element_type'][$j] != 'Time') {
							  echo '<td align="right"><a href="#" title="'.$attributes['tooltip'][$j].'"><img src="'.GRAPHICSPATH.'emblem-important.png" border="0"></a></td>';
							}
							if ($attributes['type'][$j] == 'date' OR $attributes['type'][$j] == 'timestamp' OR $attributes['type'][$j] == 'timestamptz') {
							  echo '<td align="right"><a href="javascript:;" title=" (TT.MM.JJJJ) '.$attributes['tooltip'][$j].'" ';
							  if($attributes['privileg'][$j] == '1' AND !$lock[$k]){
							  	echo 'onclick="new CalendarJS().init(\''.$attributes['name'][$j].'_'.$k.'\');"';
							  }
							  echo '><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a><div id="calendar"><a name="calendar_'.$attributes['name'][$j].'_'.$k.'"></div></td>';
							}
							echo '</td></tr></table>';
							echo '</td><td>';
			  			if($attributes['constraints'][$j] != ''){
			  				if($attributes['privileg'][$j] == '0' OR $lock[$k]){
			  					$size = 1.3*strlen($layer['shape'][$k][$attributes['name'][$j]]);
									echo '<input readonly style="background-color:#e8e3da;" size="'.$size.'" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$layer['shape'][$k][$attributes['name'][$j]].'">';
							}
							else{
			  					echo '<select onchange="set_changed_flag(document.GUI.changed_'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" title="'.$attributes['alias'][$j].'"  style="font-size: '.$this->user->rolle->fontsize_gle.'px" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'">';
									for($e = 0; $e < count($attributes['enum_value'][$j]); $e++){
										echo '<option ';
										if($attributes['enum_value'][$j][$e] == $layer['shape'][$k][$attributes['name'][$j]]){
											echo 'selected ';
										}
										echo 'value="'.$attributes['enum_value'][$j][$e].'">'.$attributes['enum_output'][$j][$e].'</option>';
									}
									echo '</select>';
			  				}
			  			}
			  			else{
								switch ($attributes['form_element_type'][$j]){
									case 'Textfeld' : {
										echo '<textarea cols="45" onchange="set_changed_flag(document.GUI.changed_'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].')"';
										if($attributes['privileg'][$j] == '0' OR $lock[$k]){
											echo ' readonly style="border:0px;background-color:transparent;font-family:arial,verdana,helvetica,sans-serif;font-size: '.$this->user->rolle->fontsize_gle.'px;"';
										}
										else{
											echo ' style="font-family:arial,verdana,helvetica,sans-serif;font-size: '.$this->user->rolle->fontsize_gle.'px"';
										}
										echo ' rows="3" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'">'.$layer['shape'][$k][$attributes['name'][$j]].'</textarea>';
									}break;

									case 'Auswahlfeld' : case 'Auswahlfeld_not_saveable' : {
										if($attributes['privileg'][$j] == '0' OR $lock[$k]){
										  if(is_array($attributes['dependent_options'][$j])){		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
												for($e = 0; $e < count($attributes['enum_value'][$j][$k]); $e++){
													if($attributes['enum_value'][$j][$k][$e] == $layer['shape'][$k][$attributes['name'][$j]]){
														$auswahlfeld_output = $attributes['enum_output'][$j][$k][$e];
														$auswahlfeld_output_laenge=strlen($auswahlfeld_output)+1;
														break;
													}
												}
											}
											else{
												for($e = 0; $e < count($attributes['enum_value'][$j]); $e++){
													if($attributes['enum_value'][$j][$e] == $layer['shape'][$k][$attributes['name'][$j]]){
														$auswahlfeld_output = $attributes['enum_output'][$j][$e];
														$auswahlfeld_output_laenge=strlen($auswahlfeld_output)+1;
														break;
													}
												}
											}
                      echo '<input readonly style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px;" size="'.$auswahlfeld_output_laenge.'" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$auswahlfeld_output.'">';
                      $auswahlfeld_output = '';
                      $auswahlfeld_output_laenge = '';
										}
										else{
											echo '<select title="'.$attributes['alias'][$j].'" style="font-size: '.$this->user->rolle->fontsize_gle.'px"';
											if($attributes['req_by'][$j] != ''){
												echo 'onchange="update_require_attribute(\''.$attributes['req_by'][$j].'\', '.$k.','.$layer['Layer_ID'].', this.value);set_changed_flag(document.GUI.changed_'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" ';
											}
											else{
												echo 'onchange="set_changed_flag(document.GUI.changed_'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].')"';
											}
											echo 'id="'.$attributes['name'][$j].'_'.$k.'" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'">';
											echo '<option value="">-- Bitte Auswählen --</option>';
											if(is_array($attributes['dependent_options'][$j])){		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
												for($e = 0; $e < count($attributes['enum_value'][$j][$k]); $e++){
													echo '<option ';
													if($attributes['enum_value'][$j][$k][$e] == $layer['shape'][$k][$attributes['name'][$j]] OR ($attributes['enum_value'][$j][$k][$e] != '' AND $attributes['enum_value'][$j][$k][$e] == $this->formvars[$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j]])){
														echo 'selected ';
													}
													echo 'value="'.$attributes['enum_value'][$j][$k][$e].'">'.$attributes['enum_output'][$j][$k][$e].'</option>';
												}
											}
											else{
												for($e = 0; $e < count($attributes['enum_value'][$j]); $e++){
													echo '<option ';
													if($attributes['enum_value'][$j][$e] == $layer['shape'][$k][$attributes['name'][$j]] OR ($attributes['enum_value'][$j][$e] != '' AND $attributes['enum_value'][$j][$e] == $this->formvars[$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j]])){
														echo 'selected ';
													}
													echo 'value="'.$attributes['enum_value'][$j][$e].'">'.$attributes['enum_output'][$j][$e].'</option>';
												}
											}
											echo '</select>';
											if($attributes['subform_layer_id'][$j] != ''){
												if($attributes['subform_layer_privileg'][$j] > 0){
													if($attributes['embedded'][$j] == true){
														echo '<a href="javascript:ahah(\''.URL.APPLVERSION.'index.php\', \'go=neuer_Layer_Datensatz&selected_layer_id='.$attributes['subform_layer_id'][$j].'&embedded=true&fromobject=subform'.$layer['Layer_ID'].'_'.$k.'_'.$j.'&targetobject='.$attributes['name'][$j].'_'.$k.'&targetlayer_id='.$layer['Layer_ID'].'&targetattribute='.$attributes['name'][$j].'\', new Array(document.getElementById(\'subform'.$layer['Layer_ID'].'_'.$k.'_'.$j.'\')), \'sethtml\');clearsubforms();">&nbsp;neu</a>';
														echo '<div style="display:inline" id="subform'.$layer['Layer_ID'].'_'.$k.'_'.$j.'"></div>';
													}
													else{
														echo '<a target="_blank" href="index.php?go=neuer_Layer_Datensatz&selected_layer_id='.$attributes['subform_layer_id'][$j].'">&nbsp;neu</a>';
													}
												}
											}
										}
									}break;

									case 'SubFormPK' : {
										echo '<input style="font-size: '.$this->user->rolle->fontsize_gle.'px"';
										if($attributes['privileg'][$j] == '0' OR $lock[$k]){
											echo ' readonly style="background-color:#e8e3da;"';
										}
										echo ' size="40" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$layer['shape'][$k][$attributes['name'][$j]].'">';
										if($this->new_entry != true){
											if($layer['shape'][$k][$attributes['name'][$j]] != ''){
												echo '&nbsp;<a href="index.php?go=Layer-Suche_Suchen&selected_layer_id='.$attributes['subform_layer_id'][$j];
												for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
													echo '&value_'.$attributes['subform_pkeys'][$j][$p].'='.$layer['shape'][$k][$attributes['subform_pkeys'][$j][$p]];
													echo '&operator_'.$attributes['subform_pkeys'][$j][$p].'==';
												}
												echo 	'"';
												if($attributes['no_new_window'][$j] != true){
													echo 	' target="_blank"';
												}
												echo 	' style="font-size: '.$this->user->rolle->fontsize_gle.'px">'.$strShowPK.'</a>&nbsp;';
											}
											if($attributes['subform_layer_privileg'][$j] > 0){
												echo '|&nbsp;<a href="" onclick="this.href=\'index.php?go=neuer_Layer_Datensatz&selected_layer_id='.$attributes['subform_layer_id'][$j];
												for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
													echo '&attributenames['.$p.']='.$attributes['subform_pkeys'][$j][$p];
													echo '&values['.$p.']=\'+document.getElementById(\''.$attributes['subform_pkeys'][$j][$p].'_'.$k.'\').value';
												}
												echo 	'"';
												if($attributes['no_new_window'][$j] != true){
													echo 	' target="_blank"';
												}
												echo 	' style="font-size: '.$this->user->rolle->fontsize_gle.'px">'.$strNewPK.'</a>';
											}
										}
									}break;

									case 'SubFormFK' : {
										$dataset = $layer['shape'][$k];								# der aktuelle Datensatz
										$attribute_foreign_keys = $attributes['subform_fkeys'][$j];	# die FKeys des aktuellen Attributes
										for($f = 0; $f < count($attribute_foreign_keys); $f++){
											if($dataset[$attribute_foreign_keys[$f]] == ''){
												$dataset[$attribute_foreign_keys[$f]] = $this->formvars[$layer['Layer_ID'].';'.$attributes['real_name'][$attribute_foreign_keys[$f]].';'.$attributes['table_name'][$attribute_foreign_keys[$f]].';'.$dataset[$attributes['table_name'][$attribute_foreign_keys[$f]].'_oid'].';TextFK;0;varchar'];
											}
											echo '<input style="font-size: '.(0.9*$this->user->rolle->fontsize_gle).'px';
											if($attributes['privileg'][$attribute_foreign_keys[$f]] == '0' OR $lock[$k]){
												echo ';background-color:transparent;border:0px;display:none;background-color:#e8e3da;" readonly ';
											}
											else{
												'" ';
											}
											echo ' id="'.$attributes['real_name'][$attribute_foreign_keys[$f]].'_'.$k.'" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attribute_foreign_keys[$f]].';'.$attributes['table_name'][$attribute_foreign_keys[$f]].';'.$dataset[$attributes['table_name'][$attribute_foreign_keys[$f]].'_oid'].';TextFK;0;varchar" value="'.$dataset[$attribute_foreign_keys[$f]].'">';
											if($attributes['privileg'][$attribute_foreign_keys[$f]] > 0 AND !$lock[$k]){
												echo '<br>';
											}
											$this->form_field_names .= $layer['Layer_ID'].';'.$attributes['real_name'][$attribute_foreign_keys[$f]].';'.$attributes['table_name'][$attribute_foreign_keys[$f]].';'.$dataset[$attributes['table_name'][$attribute_foreign_keys[$f]].'_oid'].';TextFK;0;varchar|';
										}
										echo '<input style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px"';
										if($attributes['privileg'][$j] == '0' OR $lock[$k]){
											echo ' readonly style="background-color:#e8e3da;"';
										}
										echo ' size="50" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$dataset[$attributes['name'][$j]].'">';
										if($this->new_entry != true){
											if($dataset[$attributes['name'][$j]] != ''){
												echo '&nbsp;<a href="index.php?go=Layer-Suche_Suchen&selected_layer_id='.$attributes['subform_layer_id'][$j];
												for($f = 0; $f < count($attribute_foreign_keys); $f++){
													echo '&value_'.$attribute_foreign_keys[$f].'='.$dataset[$attribute_foreign_keys[$f]];
													echo '&operator_'.$attribute_foreign_keys[$f].'==';
												}
												echo 	'"';
												if($attributes['no_new_window'][$j] != true){
													echo 	' target="_blank"';
												}
												echo 	' style="font-size: '.$this->user->rolle->fontsize_gle.'px">'.$strShowFK.'</a>';
											}
										}
									}break;

									case 'SubFormEmbeddedPK' : {
										echo '<div id="'.$attributes['name'][$j].'_'.$k.'"></div>';
										$no_query = false;
										for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
											if($layer['shape'][$k][$attributes['subform_pkeys'][$j][$p]] == ''){
												$no_query = true;
											}
										}
										if($this->new_entry != true AND $no_query != true){
											echo '	<script type="text/javascript">
															ahah(\''.URL.APPLVERSION.'index.php\', \'go=Layer-Suche_Suchen&selected_layer_id='.$attributes['subform_layer_id'][$j];
															$data = '';
															for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
																$data .= '&value_'.$attributes['subform_pkeys'][$j][$p].'='.$layer['shape'][$k][$attributes['subform_pkeys'][$j][$p]];
																$data .= '&operator_'.$attributes['subform_pkeys'][$j][$p].'==';
															}
															$data .= '&preview_attribute='.$attributes['preview_attribute'][$j];
															$data .= '&count='.$k;
															$data .= '&no_new_window='.$attributes['no_new_window'][$j];
															echo $data;
															echo '&data='.str_replace('&', '<und>', $data);
															echo '&embedded_subformPK=true';
															if($attributes['embedded'][$j] == true){
																echo '&embedded=true';
															}
															echo '&targetobject='.$attributes['name'][$j].'_'.$k.'&targetlayer_id='.$layer['Layer_ID'].'&targetattribute='.$attributes['name'][$j];
															echo '\', new Array(document.getElementById(\''.$attributes['name'][$j].'_'.$k.'\')), \'\');
														</script>
													';
											if($attributes['subform_layer_privileg'][$j] > 0 AND !$lock[$k]){
												if($attributes['embedded'][$j] == true){
													echo '<table width="98%" cellspacing="0" cellpadding="0"><tr><td width="100%" align="right"><a href="javascript:ahah(\''.URL.APPLVERSION.'index.php\', \'go=neuer_Layer_Datensatz';
													$data = '';
													for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
														echo '&attributenames['.$p.']='.$attributes['subform_pkeys'][$j][$p];
														echo '&values['.$p.']=\'+document.getElementById(\''.$attributes['subform_pkeys'][$j][$p].'_'.$k.'\').value+\'';
														$data .= '&value_'.$attributes['subform_pkeys'][$j][$p].'=\'+document.getElementById(\''.$attributes['subform_pkeys'][$j][$p].'_'.$k.'\').value+\'';
														$data .= '&operator_'.$attributes['subform_pkeys'][$j][$p].'==';
													}
													$data .= '&preview_attribute='.$attributes['preview_attribute'][$j];
													echo '&data='.str_replace('&', '<und>', $data);
													echo '&selected_layer_id='.$attributes['subform_layer_id'][$j].'&embedded=true&fromobject=subform'.$layer['Layer_ID'].'_'.$k.'_'.$j.'&targetobject='.$attributes['name'][$j].'_'.$k.'&targetlayer_id='.$layer['Layer_ID'].'&targetattribute='.$attributes['name'][$j].'\', new Array(document.getElementById(\'subform'.$layer['Layer_ID'].'_'.$k.'_'.$j.'\')), \'sethtml\');clearsubforms();">&nbsp;'.$strNewEmbeddedPK.'</a></td></tr></table>';
													echo '<div style="display:inline" id="subform'.$layer['Layer_ID'].'_'.$k.'_'.$j.'"></div>';
												}
												else{
													echo '<table width="98%" cellspacing="0" cellpadding="0"><tr><td width="100%" align="right"><a ';
													if($attributes['no_new_window'][$j] != true){
														echo 	' target="_blank"';
													}
													echo ' href="" onclick="this.href=\'index.php?go=neuer_Layer_Datensatz';
													for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
														echo '&attributenames['.$p.']='.$attributes['subform_pkeys'][$j][$p];
														echo '&values['.$p.']=\'+document.getElementById(\''.$attributes['subform_pkeys'][$j][$p].'_'.$k.'\').value+\'';
													}
													echo '&selected_layer_id='.$attributes['subform_layer_id'][$j].'\'">&nbsp;'.$strNewEmbeddedPK.'</a></td></tr></table>';
												}
											}
										}
									}break;

									case 'Time': {
										echo '<input readonly style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px;"';
										echo ' size="61" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$layer['shape'][$k][$attributes['name'][$j]].'">';
									}break;

									case 'Dokument': {
										if ($layer['shape'][$k][$attributes['name'][$j]]!='') {
											if($attributes['options'][$j] != ''){		# bei Layern die auf andere Server zugreifen, wird die URL des anderen Servers verwendet
												$url = $attributes['options'][$j];
											}
											else{
												$url = URL.APPLVERSION.'index.php?go=sendeDokument&dokument=';
											}
											$type = strtolower(array_pop(explode('.', $layer['shape'][$k][$attributes['name'][$j]])));
											echo '<table border="0"><tr><td>';
			  							if($type == 'jpg' OR $type == 'png' OR $type == 'gif' ){
												echo '<iframe height="160" style="border:none" frameborder="0" marginheight="3" marginwidth="3" src="'.$url.$layer['shape'][$k][$attributes['name'][$j]].'&go_plus=mit_vorschau"></iframe>';
			  							}else{
			  								echo '<iframe height="80" style="border:none" frameborder="0" marginheight="3" marginwidth="3" src="'.$url.$layer['shape'][$k][$attributes['name'][$j]].'&go_plus=mit_vorschau"></iframe>';
			  							}
			  							echo '</td><td>';
			  							if($attributes['privileg'][$j] != '0' AND !$lock[$k]){
			  								echo '<a href="javascript:delete_document(\''.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'\');">Dokument <br>löschen</a>';
			  							}
											echo '</td></tr></table>';
											echo '<input type="hidden" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].'_alt'.';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$layer['shape'][$k][$attributes['name'][$j]].'">';

										}
										if($attributes['privileg'][$j] != '0' AND !$lock[$k]){
											echo '<input onchange="set_changed_flag(document.GUI.changed_'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" style="font-size: '.$this->user->rolle->fontsize_gle.'px" size="43" type="file" onchange="this.title=this.value;" accept="image/*" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'">';
										}
										else{
											echo '&nbsp;';
										}
									} break;

									case 'Link': {
										if ($layer['shape'][$k][$attributes['name'][$j]]!='') {
											echo '<a class="link" target="_blank" style="font-size: '.$this->user->rolle->fontsize_gle.'px" href="'.$layer['shape'][$k][$attributes['name'][$j]].'">';
											if($attributes['options'][$j] != ''){
												echo $attributes['options'][$j];
											}
											else{
												echo basename($layer['shape'][$k][$attributes['name'][$j]]);
											}
											echo '</a><br>';
										}
										if($attributes['privileg'][$j] != '0' OR $lock[$k]){
											echo '<input onchange="set_changed_flag(document.GUI.changed_'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" style="font-size: '.$this->user->rolle->fontsize_gle.'px" size="61" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.htmlspecialchars($layer['shape'][$k][$attributes['name'][$j]]).'">';
										}else{
											echo '<input type="hidden" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.htmlspecialchars($layer['shape'][$k][$attributes['name'][$j]]).'">';
										}
									} break;

									case 'dynamicLink': {
										$explosion = explode(';', $attributes['options'][$j]);		# url;alias;embedded
										$href = $explosion[0];
										if($explosion[1] != ''){
											$alias = $explosion[1];
											$explosion1 = explode('$', $alias);
											for($d = 1; $d < count($explosion1); $d++){
												$explosion2 = explode('&', $explosion1[$d]);
												$alias = str_replace('$'.$explosion2[0], $layer['shape'][$k][$explosion2[0]], $alias);
											}
										}
										else{
											$alias = $href;
										}
										$explosion1 = explode('$', $href);
										for($d = 1; $d < count($explosion1); $d++){
											$explosion2 = explode('&', $explosion1[$d]);
											$href = str_replace('$'.$explosion2[0], $layer['shape'][$k][$explosion2[0]], $href);
										}
										if($explosion[2] == 'embedded'){
											echo '<a href="javascript:if(document.getElementById(\'dynamicLink'.$layer['Layer_ID'].'_'.$k.'_'.$j.'\').innerHTML != \'\'){clearsubform(\'dynamicLink'.$layer['Layer_ID'].'_'.$k.'_'.$j.'\');} else {ahah(\''.$href.'\', \'\', new Array(document.getElementById(\'dynamicLink'.$layer['Layer_ID'].'_'.$k.'_'.$j.'\')), \'sethtml\')}">';
											echo $alias;
											echo '</a><br>';
											echo '<div style="display:inline" id="dynamicLink'.$layer['Layer_ID'].'_'.$k.'_'.$j.'"></div>';
										}
										else{
											echo '<a ';
											if($explosion[2] != 'no_new_window'){echo 'target="_blank"';}
											echo ' style="font-size: '.$this->user->rolle->fontsize_gle.'px" href="'.$href.'">';
											echo $alias;
											echo '</a><br>';
										}
									} break;

									case 'Fläche': {
										echo '<input onchange="set_changed_flag(document.GUI.changed_'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" id="custom_area" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$attributes['alias'][$j].'" ';
										if($attributes['privileg'][$j] == '0' OR $lock[$k]){
											echo ' readonly style="font-size: '.$this->user->rolle->fontsize_gle.'px;background-color:#e8e3da;"';
										}
										else{
											echo ' style="font-size: '.$this->user->rolle->fontsize_gle.'px;"';
										}
										echo ' size="61" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.htmlspecialchars($layer['shape'][$k][$attributes['name'][$j]]).'">';
									}break;

									default : {
										$value = $layer['shape'][$k][$attributes['name'][$j]];
										if(in_array($attributes['type'][$j], array('numeric', 'float4', 'float8', 'int2', 'int4', 'int8'))){		# bei Zahlen Tausendertrennzeichen einfügen 
											$value = tausenderTrenner($layer['shape'][$k][$attributes['name'][$j]]);
										}
										echo '<input onchange="set_changed_flag(document.GUI.changed_'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$attributes['alias'][$j].'" ';
										if($attributes['privileg'][$j] == '0' OR $lock[$k]){
											echo ' readonly style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px;"';
										}
										else{
											echo ' style="font-size: '.$this->user->rolle->fontsize_gle.'px;"';
										}
										if($attributes['name'][$j] == 'lock'){
											echo ' type="hidden"';
										}
										if($attributes['length'][$j] AND !in_array($attributes['type'][$j], array('numeric', 'float4', 'float8', 'int2', 'int4', 'int8'))){
											echo ' maxlength="'.$attributes['length'][$j].'"';
										}
										echo ' size="61" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" id="'.$attributes['name'][$j].'_'.$k.'" value="'.htmlspecialchars($value).'">';
									}
								}
			  			}
			  			echo '
									</td>
								</tr>
							';
							if($attributes['privileg'][$j] >= '0'){
								$this->form_field_names .= $layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'|';
							}
			  		}
			  		else {
			  			$columnname = $attributes['name'][$j];
			  			$tablename = $attributes['table_name'][$attributes['name'][$j]];
			  			$geomtype = $attributes['geomtype'][$attributes['name'][$j]];
			  			$dimension = $attributes['dimension'][$j];
			  			$privileg = $attributes['privileg'][$j];
			  			$nullable = $attributes['nullable'][$j];
			  			$this->form_field_names .= $layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';Geometrie;'.$attributes['nullable'][$j].'|';
			  		}
					}
				}
				 if($this->new_entry != true AND $this->formvars['printversion'] == ''){ ?>
					<tr>
						<? if($layer['querymaps'][$k] != ''){ ?>
						<td bgcolor="<? echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;" align="center"><img style="border:1px solid grey" src="<? echo $layer['querymaps'][$k]; ?>"></td>
						<? } else { ?>
			    	    <td bgcolor="<? echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;">&nbsp;</td>
			    	    <? } ?>
			    	    <td style="padding-top:5px; padding-bottom:5px;">&nbsp;&nbsp;
<?						
							if($layer['shape'][$k][$attributes['the_geom']]){
								if($geomtype == 'POLYGON' OR $geomtype == 'MULTIPOLYGON' OR $geomtype == 'GEOMETRY'){
?>
			    					&bull;&nbsp;<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="" onclick="this.href='index.php?go=zoomtoPolygon&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren='+document.GUI.selektieren<? echo $layer['Layer_ID'].'_'.$k; ?>.checked;"><? echo $strMapZoom; ?></a>&nbsp;&nbsp;&nbsp;<span style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px"><? echo $strMapSelect; ?></span><input type="checkbox" name="selektieren<? echo $layer['Layer_ID'].'_'.$k; ?>" value="1">
			    					<script type="text/javascript">
			    						document.getElementById('uko_<? echo $layer['Layer_ID'].'_'.$k; ?>').href = 'index.php?go=UKO_Export&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selected_layer_id=<? echo $layer['Layer_ID'];?>';
			    						document.getElementById('uko_<? echo $layer['Layer_ID'].'_'.$k; ?>').style.visibility = 'visible';
			    					</script>
<?
								} elseif($geomtype == 'POINT') {
?>
			    					&bull;&nbsp;<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="index.php?go=zoomtoPoint&dimension=<? echo $dimension; ?>&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&tablename=<? echo $tablename; ?>&columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>"><? echo $strMapZoom; ?></a>
<?
			    				}
			    				elseif($geomtype == 'MULTILINESTRING' OR $geomtype == 'LINESTRING') {
?>
			    					&bull;&nbsp;<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="" onclick="this.href='index.php?go=zoomToLine&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren='+document.GUI.selektieren<? echo $layer['Layer_ID'].'_'.$k; ?>.checked;"><? echo $strMapZoom; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px"><? echo $strMapSelect; ?></span><input type="checkbox" name="selektieren<? echo $layer['Layer_ID'].'_'.$k; ?>" value="1">
<?
			    				}
?>
							<br><br>&nbsp;&nbsp;
<?					}
								if($privileg == 1 AND !$lock[$k]) {
									if($geomtype == 'POLYGON' OR $geomtype == 'MULTIPOLYGON' OR $geomtype == 'GEOMETRY'){
	?>
				    					&bull;&nbsp;<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="index.php?go=PolygonEditor&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selected_layer_id=<? echo $layer['Layer_ID'];?>"><? echo $strEditGeom; ?></a>
				    					
	<?
									} elseif($geomtype == 'POINT') {
	?>
				    					&bull;&nbsp;<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="index.php?go=PointEditor&dimension=<? echo $dimension; ?>&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&tablename=<? echo $tablename; ?>&columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>"><? echo $strEditGeom; ?></a>
	<?
				    				}
				    				elseif($geomtype == 'MULTILINESTRING' OR $geomtype == 'LINESTRING') {
	?>
				    					&bull;&nbsp;<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="index.php?go=LineEditor&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selected_layer_id=<? echo $layer['Layer_ID'];?>"><? echo $strEditGeom; ?></a>
	<?
				    				}
								}
?>
			    </tr>
<? }

				if($privileg == 1) {
					if($this->new_entry == true){
						if($nullable === '0'){ ?>
							<script type="text/javascript">
    						geom_not_null = true;
    					</script>
<?					}
						$this->titel=$strTitleGeometryEditor;
						if($geomtype == 'POLYGON' OR $geomtype == 'MULTIPOLYGON' OR $geomtype == 'GEOMETRY'){
							echo '
							<tr>
								<td colspan="2" align="center">';
									include(LAYOUTPATH.'snippets/PolygonEditor.php');
							echo'
								</td>
							</tr>';
						} elseif($geomtype == 'POINT') {
							$this->formvars['dimension'] = $dimension;
							echo '
							<tr>
								<td colspan="2" align="center">';
									include(LAYOUTPATH.'snippets/PointEditor.php');
							echo'
								</td>
							</tr>';
						} elseif($geomtype == 'MULTILINESTRING' OR $geomtype == 'LINESTRING') {
							echo '
							<tr>
								<td colspan="2" align="center">';
									include(LAYOUTPATH.'snippets/LineEditor.php');
							echo'
								</td>
							</tr>';
						}
					}
				}
 ?>
			</tbody>
			</table>
			<br>
			</div>
		</td>
	</tr>
<?
	}
	if($this->formvars['printversion'] == ''){
?>
	<tr>
		<td colspan="2"align="left">
		<? if($this->new_entry != true){ ?>
			<table border="0">
				<tr>
					<td colspan="2">
						<i><? echo $layer['Name'] ?></i>:&nbsp;<a href="javascript:selectall(<? echo $layer['Layer_ID']; ?>);">
						<? if ($layer['count'] > MAXQUERYROWS) {
						    echo $strSelectAllShown;
						   } else {
						    echo $strSelectAll;
						   } ?>
						</a>
					</td>
				</tr>
				<tr>
					<td valign="top"><? echo $strSelectedDatasets.':'; ?></td>
					<td>
					<? if($layer['privileg'] == '2'){ ?>
						&bull;&nbsp;<a href="javascript:delete_datasets(<?php echo $layer['Layer_ID']; ?>);"><? echo $strdelete; ?></a><br>
					<?}?>
						&bull;&nbsp;<a id="csv_link" href="javascript:csv_export(<?php echo $layer['Layer_ID']; ?>);"><? echo $strCSVExport; ?></a><br>
					<? if($layer['layouts']){ ?>
						&bull;&nbsp;<a id="print_link" href="javascript:print_data(<?php echo $layer['Layer_ID']; ?>);"><? echo $strPrint; ?></a>
					<? } ?>
					</td>
				<? if($this->formvars['printversion'] == '' AND $privileg != ''){ ?>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="3">
						&bull;&nbsp;<a href="javascript:zoomto_datasets(<?php echo $layer['Layer_ID']; ?>, '<? echo $tablename; ?>', '<? echo $columnname; ?>');"><? echo $strzoomtodatasets; ?></a>
						<select name="klass_<?php echo $layer['Layer_ID']; ?>">
							<option value="">klassifiziert nach:</option>
							<?
							for($j = 0; $j < count($attributes['name']); $j++){
								if($attributes['name'][$j] != $attributes['the_geom']){
									echo '<option value="'.$attributes['name'][$j].'">'.$attributes['alias'][$j].'</option>';
								}
							}
							?>
						</select>
					</td>
				</tr>
					<?}?>
				<tr style="display:none">
					<td></td>
					<td height="23" colspan="3">
						&bull;&nbsp;<a href="javascript:showcharts(<?php echo $layer['Layer_ID']; ?>);"><? echo $strCreateChart; ?></a>
					</td>
				</tr>
				<tr id="charts_<?php echo $layer['Layer_ID']; ?>" style="display:none">
					<td></td>
					<td>
						<table>
							<tr>
								<td colspan="2">
									&nbsp;&nbsp;<select name="charttype_<?php echo $layer['Layer_ID']; ?>" onchange="change_charttype(<?php echo $layer['Layer_ID']; ?>);">
										<option value="bar">Balkendiagramm</option>
										<option value="mirrorbar">doppeltes Balkendiagramm</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;&nbsp;Beschriftung:
								</td>
								<td>
									<select style="width:133px" id="" name="chartlabel_<?php echo $layer['Layer_ID']; ?>" >
										<?
										for($j = 0; $j < count($attributes['name']); $j++){
											if($attributes['name'][$j] != $attributes['the_geom']){
												echo '<option value="'.$attributes['name'][$j].'">'.$attributes['alias'][$j].'</option>';
											}
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;&nbsp;Wert:
								</td>
								<td>
									<select style="width:133px" name="chartvalue_<?php echo $layer['Layer_ID']; ?>" onchange="create_chart(<?php echo $layer['Layer_ID']; ?>);">
										<option value="">--- Bitte Wählen ---</option>
										<?
										for($j = 0; $j < count($attributes['name']); $j++){
											if($attributes['name'][$j] != $attributes['the_geom']){
												echo '<option value="'.$attributes['name'][$j].'">'.$attributes['alias'][$j].'</option>';
											}
										}
										?>
									</select>
								</td>
							</tr>
							<tr id="split_<?php echo $layer['Layer_ID']; ?>" style="display:none">
								<td>
									&nbsp;&nbsp;Trenn-Attribut:
								</td>
								<td>
									<select style="width:133px" name="chartsplit_<?php echo $layer['Layer_ID']; ?>" onchange="create_chart(<?php echo $layer['Layer_ID']; ?>);">
										<option value="">--- Bitte Wählen ---</option>
										<?
										for($j = 0; $j < count($attributes['name']); $j++){
											if($attributes['name'][$j] != $attributes['the_geom']){
												echo '<option value="'.$attributes['name'][$j].'">'.$attributes['alias'][$j].'</option>';
											}
										}
										?>
									</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				</tr>
			</table>
		<?} ?>
		</td>
	</tr>
	<? if($this->new_entry != true){ ?>
	<tr>
		<td><a id="csv_link" href="javascript:csv_export_all(<?php echo $layer['Layer_ID']; ?>);"><? echo $strCSVExportAll; ?></a>
		<? if ($layer['count'] > MAXQUERYROWS) {
		  echo "&nbsp;(".$layer['count'].")";
		   } ?>
		&nbsp;&nbsp;<a id="csv_link" href="javascript:shape_export_all(<?php echo $layer['Layer_ID']; ?>, <? echo $layer['count']; ?>);"><? echo $strSHPExportAll; ?></a>
		<? if ($layer['count'] > MAXQUERYROWS) {
		  echo "&nbsp;(".$layer['count'].")";
		   } ?>
		</td>
	</tr>
	<?}
	}?>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>



<input type="hidden" name="checkbox_names_<? echo $layer['Layer_ID']; ?>" value="<? echo $checkbox_names; ?>">
<input type="hidden" name="orderby<? echo $layer['Layer_ID']; ?>" id="orderby<? echo $layer['Layer_ID']; ?>" value="<? echo $this->formvars['orderby'.$layer['Layer_ID']]; ?>">

<?
  }
  else {
?>
<table border="0" cellspacing="10" cellpadding="2">
  <tr>
	<td>
      <span style="font-size:12px; color:#FF0000;"><? echo $strNoMatch; ?></span>
	</td>
  </tr>
</table>

<?
  }
?>
</div>
