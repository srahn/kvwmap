<?php
 # 2008-09-30 sr
  include(LAYOUTPATH.'languages/generic_layer_editor_2_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
 ?>
<script type="text/javascript">
<!--

<?
	if($this->formvars['close_window'] != ""){
		echo 'opener.location.reload();';
		echo 'window.close();';
	}
 ?>

function checknumbers(input, type){
	if(type == 'numeric' || type == 'float4' || type == 'float8'){
		var val = input.value.replace(/,/g, '.');
  	input.value = val;
  }
  if(type == 'int2' || type == 'int4'){
  	if(input.value.search(/[^-\d]/g) != -1){
  		alert('Es sind nur ganzzahlige Angaben erlaubt!');
  	}
		var val = input.value.replace(/[^-\d]/g, '');
  	input.value = val;
  }
}

function selectall(layer_id){
	var k = 0;
	obj = document.getElementById(layer_id+'_'+k);
	while(obj != undefined){
		obj = document.getElementById(layer_id+'_'+k);
		obj.checked = !obj.checked;
		k++;
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

function csv_export_all(layer_id){
	document.GUI.all.value = 'true';
	document.GUI.chosen_layer_id.value = layer_id;
	document.GUI.go_backup.value = document.GUI.go.value;
	document.GUI.go.value = 'generischer_csv_export';
	document.GUI.submit();
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


//-->
</script>

<h2><? echo $this->qlayerset[$i]['Name'] ?></h2>
<?
	$doit = false;
  $anzObj = count($this->qlayerset[$i]['shape']);
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
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td>&nbsp;

		</td>
	</tr>
<?
	$checkbox_names = '';
	$columnname = '';
	$tablename = '';
	$geomtype = '';
	$dimension = '';
	$privileg = '';
	for ($k=0;$k<$anzObj;$k++) {
		$checkbox_names .= 'check;'.$this->qlayerset[$i]['attributes']['table_alias_name'][$this->qlayerset[$i]['attributes']['name'][0]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][0]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][0]].'_oid'].'|';
?>
	<tr>
		<td align="center" valign="top">
			<? if($this->new_entry != true AND $this->formvars['printversion'] == ''){ ?>
			<input id="<? echo $this->qlayerset[$i]['Layer_ID'].'_'.$k; ?>" type="checkbox" name="check;<? echo $this->qlayerset[$i]['attributes']['table_alias_name'][$this->qlayerset[$i]['attributes']['name'][0]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][0]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][0]].'_oid']; ?>">&nbsp;
			<? } ?>
		</td>
		<td>
			<table border="1" cellspacing="0" cellpadding="2" width="95%">
<?		$trans_oid = explode('|', $this->qlayerset[$i]['shape'][$k]['lock']);
			if($trans_oid[1] != '' AND $this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][0]].'_oid'] == $trans_oid[1]){
				echo '<tr><td colspan="2" align="center"><span class="red">Dieser Datensatz ist gelockt und kann nicht bearbeitet werden.</span></td></tr>';
				$lock[$k] = true;
			}
			for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
				if($this->new_entry == true AND $this->qlayerset[$i]['attributes']['default'][$j] != ''){		# Default-Werte setzen
					$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]] = $this->qlayerset[$i]['attributes']['default'][$j];
				}
				if($this->qlayerset[$i]['attributes']['invisible'][$this->qlayerset[$i]['attributes']['name'][$j]] != 'true'){
					if($this->qlayerset[$i]['attributes']['type'][$j] != 'geometry'){
							echo '<tr><td align="center" bgcolor="'.BG_DEFAULT.'">';
							if($this->qlayerset[$i]['attributes']['privileg'][$j] == '0' OR $lock[$k]){
								if($this->qlayerset[$i]['attributes']['alias'][$j] == ''){
									$this->qlayerset[$i]['attributes']['alias'][$j] = $this->qlayerset[$i]['attributes']['name'][$j];
								}
								echo '<table width="100%"><tr><td><a style="font-size: '.$this->user->rolle->fontsize_gle.'px" title="Sortieren nach '.$this->qlayerset[$i]['attributes']['alias'][$j].'" style="color: #6c6c6c;" href="javascript:change_orderby(\''.$this->qlayerset[$i]['attributes']['name'][$j].'\', '.$this->qlayerset[$i]['Layer_ID'].');"><b>'.$this->qlayerset[$i]['attributes']['alias'][$j].'</b></a>';
								if ($this->qlayerset[$i]['attributes']['tooltip'][$j]!='' AND $this->qlayerset[$i]['attributes']['form_element_type'][$j] != 'Time') {
								  echo '<td align="right"><a href="#" title="'.$this->qlayerset[$i]['attributes']['tooltip'][$j].'"><img src="'.GRAPHICSPATH.'emblem-important.png" border="0"></a></td>';
								}
								if ($this->qlayerset[$i]['attributes']['type'][$j] == 'date' OR $this->qlayerset[$i]['attributes']['type'][$j] == 'timestamp' OR $this->qlayerset[$i]['attributes']['type'][$j] == 'timestamptz') {
								  echo '<td align="right"><a href="#" title=" (TT.MM.JJJJ) '.$this->qlayerset[$i]['attributes']['tooltip'][$j].'"><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a></td>';
								}
								echo '</td></tr></table>';
							}
							else{
								$this->editable = 'true';
								if($this->qlayerset[$i]['attributes']['alias'][$j] == ''){
									$this->qlayerset[$i]['attributes']['alias'][$j] = $this->qlayerset[$i]['attributes']['name'][$j];
								}
								echo '<table width="100%"><tr><td><a style="font-size: '.$this->user->rolle->fontsize_gle.'px" title="Sortieren nach '.$this->qlayerset[$i]['attributes']['alias'][$j].'" style="color: #6c6c6c;" href="javascript:change_orderby(\''.$this->qlayerset[$i]['attributes']['name'][$j].'\', '.$this->qlayerset[$i]['Layer_ID'].');"><b>'.$this->qlayerset[$i]['attributes']['alias'][$j].'</b></a>';
								if ($this->qlayerset[$i]['attributes']['tooltip'][$j]!='' AND $this->qlayerset[$i]['attributes']['form_element_type'][$j] != 'Time') {
								  echo '<td align="right"><a href="#" title="'.$this->qlayerset[$i]['attributes']['tooltip'][$j].'"><img src="'.GRAPHICSPATH.'emblem-important.png" border="0"></a></td>';
								}
								if ($this->qlayerset[$i]['attributes']['type'][$j] == 'date' OR $this->qlayerset[$i]['attributes']['type'][$j] == 'timestamp' OR $this->qlayerset[$i]['attributes']['type'][$j] == 'timestamptz') {
								  echo '<td align="right"><a href="#" title=" (TT.MM.JJJJ) '.$this->qlayerset[$i]['attributes']['tooltip'][$j].'"><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a></td>';
								}
								echo '</td></tr></table>';
							}
							echo '</td><td>';
			  			if($this->qlayerset[$i]['attributes']['constraints'][$j] != '' AND $this->qlayerset[$i]['attributes']['constraints'][$j] != 'PRIMARY KEY'){
			  				if($this->qlayerset[$i]['attributes']['privileg'][$j] == '0' OR $lock[$k]){
			  					$size = 1.3*strlen($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]);
									echo '<input readonly style="background-color:#e8e3da;" size="'.$size.'" type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'" value="'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'">';
							} else {
			  					echo '<select title="'.$this->qlayerset[$i]['attributes']['alias'][$j].'"  style="font-size: '.$this->user->rolle->fontsize_gle.'px" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'">';
									for($e = 0; $e < count($this->qlayerset[$i]['attributes']['enum_value'][$j]); $e++){
										echo '<option ';
										if($this->qlayerset[$i]['attributes']['enum_value'][$j][$e] == $this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]] OR ($this->qlayerset[$i]['attributes']['enum_value'][$j][$e] != '' AND $this->qlayerset[$i]['attributes']['enum_value'][$j][$e] == $this->formvars[$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j]])){
											echo 'selected ';
										}
										echo 'value="'.$this->qlayerset[$i]['attributes']['enum_value'][$j][$e].'">'.$this->qlayerset[$i]['attributes']['enum_output'][$j][$e].'</option>';
									}
									echo '</select>';
			  				}
			  			} else {
			  					if($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]] == ''){
										$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]] = $this->formvars[$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j]];
									}
								switch ($this->qlayerset[$i]['attributes']['form_element_type'][$j]){
									case 'Textfeld' : {
										echo '<textarea cols="45" style="font-size: '.$this->user->rolle->fontsize_gle.'px" style="font-family: Arial, Verdana, Helvetica, sans-serif;"';
										if($this->qlayerset[$i]['attributes']['privileg'][$j] == '0' OR $lock[$k]){
											echo ' readonly style="background-color:#e8e3da;"';
										}
										echo ' rows="3" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'">'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'</textarea>';
									}break;

									case 'Auswahlfeld' : {
										if($this->qlayerset[$i]['attributes']['privileg'][$j] == '0' OR $lock[$k]){
										  if(is_array($this->qlayerset[$i]['attributes']['dependent_options'][$j])){		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
												for($e = 0; $e < count($this->qlayerset[$i]['attributes']['enum_value'][$j][$k]); $e++){
													if($this->qlayerset[$i]['attributes']['enum_value'][$j][$k][$e] == $this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]){
														$auswahlfeld_output = $this->qlayerset[$i]['attributes']['enum_output'][$j][$k][$e];
														$auswahlfeld_output_laenge=strlen($auswahlfeld_output);
														break;
													}
												}
											}
											else{
												for($e = 0; $e < count($this->qlayerset[$i]['attributes']['enum_value'][$j]); $e++){
													if($this->qlayerset[$i]['attributes']['enum_value'][$j][$e] == $this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]){
														$auswahlfeld_output = $this->qlayerset[$i]['attributes']['enum_output'][$j][$e];
														$auswahlfeld_output_laenge=strlen($auswahlfeld_output);
														break;
													}
												}
											}
                      echo '<input readonly style="font-size: '.$this->user->rolle->fontsize_gle.'px;background-color:#e8e3da;" size="'.$auswahlfeld_output_laenge.'" type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'" value="'.$auswahlfeld_output.'">';
										}
										else{
											echo '<select title="'.$this->qlayerset[$i]['attributes']['alias'][$j].'" style="font-size: '.$this->user->rolle->fontsize_gle.'px"';
											if($this->qlayerset[$i]['attributes']['req_by'][$j] != ''){
												echo 'onchange="update_require_attribute(\''.$this->qlayerset[$i]['attributes']['req_by'][$j].'\', '.$k.','.$this->qlayerset[$i]['Layer_ID'].', this.value);" ';
											}
											echo 'id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'">';
											echo '<option value="">-- '.$this->strPleaseSelect.' --</option>';
											if(is_array($this->qlayerset[$i]['attributes']['dependent_options'][$j])){		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
												for($e = 0; $e < count($this->qlayerset[$i]['attributes']['enum_value'][$j][$k]); $e++){
													echo '<option ';
													if($this->qlayerset[$i]['attributes']['enum_value'][$j][$k][$e] == $this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]] OR ($this->qlayerset[$i]['attributes']['enum_value'][$j][$k][$e] != '' AND $this->qlayerset[$i]['attributes']['enum_value'][$j][$k][$e] == $this->formvars[$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j]])){
														echo 'selected ';
													}
													echo 'value="'.$this->qlayerset[$i]['attributes']['enum_value'][$j][$k][$e].'">'.$this->qlayerset[$i]['attributes']['enum_output'][$j][$k][$e].'</option>';
												}
											}
											else{
												for($e = 0; $e < count($this->qlayerset[$i]['attributes']['enum_value'][$j]); $e++){
													echo '<option ';
													if($this->qlayerset[$i]['attributes']['enum_value'][$j][$e] == $this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]] OR ($this->qlayerset[$i]['attributes']['enum_value'][$j][$e] != '' AND $this->qlayerset[$i]['attributes']['enum_value'][$j][$e] == $this->formvars[$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j]])){
														echo 'selected ';
													}
													echo 'value="'.$this->qlayerset[$i]['attributes']['enum_value'][$j][$e].'">'.$this->qlayerset[$i]['attributes']['enum_output'][$j][$e].'</option>';
												}
											}
											echo '</select>';
											if($this->qlayerset[$i]['attributes']['subform_layer_id'][$j] != ''){
												if($this->qlayerset[$i]['attributes']['subform_layer_privileg'][$j] > 0){
													if($this->qlayerset[$i]['attributes']['embedded'][$j] == true){
														echo '<a href="javascript:ahah(\''.URL.APPLVERSION.'index.php\', \'go=neuer_Layer_Datensatz&selected_layer_id='.$this->qlayerset[$i]['attributes']['subform_layer_id'][$j].'&embedded=true&fromobject=subform'.$this->qlayerset[$i]['Layer_ID'].'_'.$k.'_'.$j.'&targetobject='.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'&targetlayer_id='.$this->qlayerset[$i]['Layer_ID'].'&targetattribute='.$this->qlayerset[$i]['attributes']['name'][$j].'\', new Array(document.getElementById(\'subform'.$this->qlayerset[$i]['Layer_ID'].'_'.$k.'_'.$j.'\')), \'sethtml\');clearsubforms();">&nbsp;neu</a>';
														echo '<div style="display:inline" id="subform'.$this->qlayerset[$i]['Layer_ID'].'_'.$k.'_'.$j.'"></div>';
													}
													else{
														echo '<a target="_blank" href="index.php?go=neuer_Layer_Datensatz&selected_layer_id='.$this->qlayerset[$i]['attributes']['subform_layer_id'][$j].'">&nbsp;neu</a>';
													}
												}
											}
										}
									}break;

									case 'SubFormPK' : {
										echo '<input style="font-size: '.$this->user->rolle->fontsize_gle.'px"';
										if($this->qlayerset[$i]['attributes']['privileg'][$j] == '0' OR $lock[$k]){
											echo ' readonly style="background-color:#e8e3da;"';
										}
										echo ' size="40" type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'" value="'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'">';
										if($this->new_entry != true){
											if($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]] != ''){
												echo '&nbsp;<a href="index.php?go=Layer-Suche_Suchen&selected_layer_id='.$this->qlayerset[$i]['attributes']['subform_layer_id'][$j];
												for($p = 0; $p < count($this->qlayerset[$i]['attributes']['subform_pkeys'][$j]); $p++){
													echo '&value_'.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p].'='.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p]];
													echo '&operator_'.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p].'==';
												}
												echo 	'"';
												if($this->qlayerset[$i]['attributes']['no_new_window'][$j] != true){
													echo 	' target="_blank"';
												}
												echo 	' style="font-size: '.$this->user->rolle->fontsize_gle.'px">anzeigen</a>&nbsp;';
											}
											if($this->qlayerset[$i]['attributes']['subform_layer_privileg'][$j] > 0){
												echo '|&nbsp;<a href="" onclick="this.href=\'index.php?go=neuer_Layer_Datensatz&selected_layer_id='.$this->qlayerset[$i]['attributes']['subform_layer_id'][$j];
												for($p = 0; $p < count($this->qlayerset[$i]['attributes']['subform_pkeys'][$j]); $p++){
													echo '&attributenames['.$p.']='.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p];
													echo '&values['.$p.']=\'+document.getElementById(\''.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p].'_'.$k.'\').value';
												}
												echo 	'"';
												if($this->qlayerset[$i]['attributes']['no_new_window'][$j] != true){
													echo 	' target="_blank"';
												}
												echo 	' style="font-size: '.$this->user->rolle->fontsize_gle.'px">neu</a>';
											}
										}
									}break;

									case 'SubFormFK' : {
										$dataset = $this->qlayerset[$i]['shape'][$k];								# der aktuelle Datensatz
										$attributes = $this->qlayerset[$i]['attributes'];						# alle Attribute
										$attribute_foreign_keys = $attributes['subform_fkeys'][$j];	# die FKeys des aktuellen Attributes
										for($f = 0; $f < count($attribute_foreign_keys); $f++){
											if($dataset[$attribute_foreign_keys[$f]] == ''){
												$dataset[$attribute_foreign_keys[$f]] = $this->formvars[$this->qlayerset[$i]['Layer_ID'].';'.$attributes['real_name'][$attribute_foreign_keys[$f]].';'.$attributes['table_name'][$attribute_foreign_keys[$f]].';'.$dataset[$attributes['table_name'][$attribute_foreign_keys[$f]].'_oid'].';TextFK;0;varchar'];
											}
											echo '<input style="font-size: '.(0.9*$this->user->rolle->fontsize_gle).'px';
											if($attributes['privileg'][$attribute_foreign_keys[$f]] == '0' OR $lock[$k]){
												echo ';background-color:transparent;border:0px;display:none;background-color:#e8e3da;" readonly ';
											}
											else{
												'" ';
											}
											echo ' id="'.$attributes['real_name'][$attribute_foreign_keys[$f]].'_'.$k.'" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$attributes['real_name'][$attribute_foreign_keys[$f]].';'.$attributes['table_name'][$attribute_foreign_keys[$f]].';'.$dataset[$attributes['table_name'][$attribute_foreign_keys[$f]].'_oid'].';TextFK;0;varchar" value="'.$dataset[$attribute_foreign_keys[$f]].'">';
											if($attributes['privileg'][$attribute_foreign_keys[$f]] > 0 AND !$lock[$k]){
												echo '<br>';
											}
											$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$attributes['real_name'][$attribute_foreign_keys[$f]].';'.$attributes['table_name'][$attribute_foreign_keys[$f]].';'.$dataset[$attributes['table_name'][$attribute_foreign_keys[$f]].'_oid'].';TextFK;0;varchar|';
										}
										echo '<input style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px"';
										if($this->qlayerset[$i]['attributes']['privileg'][$j] == '0' OR $lock[$k]){
											echo ' readonly style="background-color:#e8e3da;"';
										}
										echo ' size="50" type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$dataset[$attributes['name'][$j]].'">';
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
												echo 	' style="font-size: '.$this->user->rolle->fontsize_gle.'px">anzeigen</a>';
											}
											/*if($this->qlayerset[$i]['attributes']['subform_layer_privileg'][$j] > 0){
												echo '|&nbsp;<a href="index.php?go=neuer_Layer_Datensatz&selected_layer_id='.$this->qlayerset[$i]['attributes']['subform_layer_id'][$j];
												echo 	'"';
												if($this->qlayerset[$i]['attributes']['no_new_window'][$j] != true){
													echo 	' target="_blank"';
												}
												echo 	' style="font-size: '.$this->user->rolle->fontsize_gle.'px">neu</a>';
											}
											*/
										}
									}break;

									case 'SubFormEmbeddedPK' : {
										echo '<div id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'"></div>';
										if($this->new_entry != true){
											echo '	<script type="text/javascript">
															ahah(\''.URL.APPLVERSION.'index.php\', \'go=Layer-Suche_Suchen&selected_layer_id='.$this->qlayerset[$i]['attributes']['subform_layer_id'][$j];
															$data = '';
															for($p = 0; $p < count($this->qlayerset[$i]['attributes']['subform_pkeys'][$j]); $p++){
																$data .= '&value_'.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p].'='.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p]];
																$data .= '&operator_'.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p].'==';
															}
															$data .= '&preview_attribute='.$this->qlayerset[$i]['attributes']['preview_attribute'][$j];
															$data .= '&count='.$k;
															$data .= '&no_new_window='.$this->qlayerset[$i]['attributes']['no_new_window'][$j];
															echo $data;
															echo '&data='.str_replace('&', '<und>', $data);
															echo '&embedded_subformPK=true';
															if($this->qlayerset[$i]['attributes']['embedded'][$j] == true){
																echo '&embedded=true';
															}
															echo '&targetobject='.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'&targetlayer_id='.$this->qlayerset[$i]['Layer_ID'].'&targetattribute='.$this->qlayerset[$i]['attributes']['name'][$j];
															echo '\', new Array(document.getElementById(\''.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'\')), \'\');
														</script>
													';
											if($this->qlayerset[$i]['attributes']['subform_layer_privileg'][$j] > 0){
												if($this->qlayerset[$i]['attributes']['embedded'][$j] == true){
													echo '<table width="98%" cellspacing="0" cellpadding="0"><tr><td width="100%" align="right"><a href="javascript:ahah(\''.URL.APPLVERSION.'index.php\', \'go=neuer_Layer_Datensatz';
													$data = '';
													for($p = 0; $p < count($this->qlayerset[$i]['attributes']['subform_pkeys'][$j]); $p++){
														echo '&attributenames['.$p.']='.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p];
														echo '&values['.$p.']=\'+document.getElementById(\''.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p].'_'.$k.'\').value+\'';
														$data .= '&value_'.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p].'=\'+document.getElementById(\''.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p].'_'.$k.'\').value+\'';
														$data .= '&operator_'.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p].'==';
													}
													$data .= '&preview_attribute='.$this->qlayerset[$i]['attributes']['preview_attribute'][$j];
													echo '&data='.str_replace('&', '<und>', $data);
													echo '&selected_layer_id='.$this->qlayerset[$i]['attributes']['subform_layer_id'][$j].'&embedded=true&fromobject=subform'.$this->qlayerset[$i]['Layer_ID'].'_'.$k.'_'.$j.'&targetobject='.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'&targetlayer_id='.$this->qlayerset[$i]['Layer_ID'].'&targetattribute='.$this->qlayerset[$i]['attributes']['name'][$j].'\', new Array(document.getElementById(\'subform'.$this->qlayerset[$i]['Layer_ID'].'_'.$k.'_'.$j.'\')), \'sethtml\');clearsubforms();">&nbsp;neu</a></td></tr></table>';
													echo '<div style="display:inline" id="subform'.$this->qlayerset[$i]['Layer_ID'].'_'.$k.'_'.$j.'"></div>';
												}
												else{
													echo '<table width="98%" cellspacing="0" cellpadding="0"><tr><td width="100%" align="right"><a ';
													if($this->qlayerset[$i]['attributes']['no_new_window'][$j] != true){
														echo 	' target="_blank"';
													}
													echo ' href="" onclick="this.href=\'index.php?go=neuer_Layer_Datensatz';
													for($p = 0; $p < count($this->qlayerset[$i]['attributes']['subform_pkeys'][$j]); $p++){
														echo '&attributenames['.$p.']='.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p];
														echo '&values['.$p.']=\'+document.getElementById(\''.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p].'_'.$k.'\').value+\'';
													}
													echo '&selected_layer_id='.$this->qlayerset[$i]['attributes']['subform_layer_id'][$j].'\'">&nbsp;neu</a></td></tr></table>';
												}
											}
										}
									}break;

									case 'Time': {
										echo '<input readonly style="font-size: '.$this->user->rolle->fontsize_gle.'px;background-color:#e8e3da;"';
										echo ' size="61" type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'" value="'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'">';
									}break;

									case 'Dokument': {
										if ($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]!='') {
											if($this->qlayerset[$i]['attributes']['options'][$j] != ''){		# bei Layern die auf andere Server zugreifen, wird die URL des anderen Servers verwendet
												$url = $this->qlayerset[$i]['attributes']['options'][$j];
											}
											else{
												$url = URL.APPLVERSION.'index.php?go=sendeDokument&dokument=';
											}
											$type = strtolower(array_pop(explode('.', $this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]])));
			  							if($type == 'jpg' OR $type == 'png' OR $type == 'gif' ){
												echo '<iframe height="160" style="border:none" frameborder="0" marginheight="3" marginwidth="3" src="'.$url.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'&go_plus=mit_vorschau"></iframe>';
			  							}else{
			  								echo '<iframe height="80" style="border:none" frameborder="0" marginheight="3" marginwidth="3" src="'.$url.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'&go_plus=mit_vorschau"></iframe>';
			  							}
											echo '<input type="hidden" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].'_alt'.';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'" value="'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'">';

										}
										if($this->qlayerset[$i]['attributes']['privileg'][$j] != '0' AND !$lock[$k]){
											echo '<input style="font-size: '.$this->user->rolle->fontsize_gle.'px" size="43" type="file" onchange="this.title=this.value;" accept="image/*" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'">';
										}
										else{
											echo '&nbsp;';
										}
									} break;

									case 'Link': {
										if ($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]!='') {
											echo '<a target="_blank" style="font-size: '.$this->user->rolle->fontsize_gle.'px" href="'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'">'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'</a><br>';
										}
										if($this->qlayerset[$i]['attributes']['privileg'][$j] != '0' OR $lock[$k]){
											echo '<input style="font-size: '.$this->user->rolle->fontsize_gle.'px" size="61" type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">';
										}
									} break;

									default : {
										echo '<input onkeyup="checknumbers(this, \''.$this->qlayerset[$i]['attributes']['type'][$j].'\');"; title="'.$this->qlayerset[$i]['attributes']['alias'][$j].'" ';
										if($this->qlayerset[$i]['attributes']['length'][$j]){
											echo ' maxlength="'.$this->qlayerset[$i]['attributes']['length'][$j].'"';
										}
										if($this->qlayerset[$i]['attributes']['privileg'][$j] == '0' OR $lock[$k]){
											echo ' readonly style="font-size: '.$this->user->rolle->fontsize_gle.'px;background-color:#e8e3da;"';
										}
										else{
											echo ' style="font-size: '.$this->user->rolle->fontsize_gle.'px;"';
										}
										echo ' size="61" type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">';
									}
								}
			  			}
			  			echo '
									</td>
								</tr>
							';
							if($this->qlayerset[$i]['attributes']['privileg'][$j] >= '0'){
								if($this->qlayerset[$i]['attributes']['privileg'][$j] == '0' AND $this->qlayerset[$i]['attributes']['form_element_type'][$j] == 'Auswahlfeld'){
									$this->qlayerset[$i]['attributes']['form_element_type'][$j] = 'Auswahlfeld_readonly';
								}
								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'|';
							}
			  		}
			  		else {
			  			$columnname = $this->qlayerset[$i]['attributes']['name'][$j];
			  			$tablename = $this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]];
			  			$geomtype = $this->qlayerset[$i]['attributes']['geomtype'][$this->qlayerset[$i]['attributes']['name'][$j]];
			  			$dimension = $this->qlayerset[$i]['attributes']['dimension'][$j];
			  			$privileg = $this->qlayerset[$i]['attributes']['privileg'][$j];
			  			$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';Geometrie;'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
			  		}
					}
				}

				 if($this->new_entry != true AND $this->formvars['printversion'] == '' AND $this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['the_geom']]){ ?>
					<tr>
						<? if($this->qlayerset[$i]['querymaps'][$k] != ''){ ?>
						<td bgcolor="<? echo BG_DEFAULT; ?>" align="center" rowspan="2" ><img style="border:1px solid grey" src="<? echo $this->qlayerset[$i]['querymaps'][$k]; ?>"></td>
						<? } ?>
			    	<td colspan="2" align="center">
			    		<table border="0" cellspacing="0" cellpadding="2">
			    			<tr>
			    				<td align="center">
<?
								if($geomtype == 'POLYGON' OR $geomtype == 'MULTIPOLYGON' OR $geomtype == 'GEOMETRY'){
?>
			    					<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="" onclick="this.href='index.php?go=zoomtoPolygon&oid=<?php echo $this->qlayerset[$i]['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $this->qlayerset[$i]['Layer_ID'];?>&selektieren='+document.GUI.selektieren<? echo $this->qlayerset[$i]['Layer_ID'].'_'.$k; ?>.checked;"><? echo $strMapZoom; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px"><? echo $strMapSelect; ?></span><input type="checkbox" name="selektieren<? echo $this->qlayerset[$i]['Layer_ID'].'_'.$k; ?>" value="1">
<?
								} elseif($geomtype == 'POINT') {
?>
			    					<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="index.php?go=zoomtoPoint&dimension=<? echo $dimension; ?>&oid=<?php echo $this->qlayerset[$i]['shape'][$k][$tablename.'_oid']; ?>&tablename=<? echo $tablename; ?>&columnname=<? echo $columnname; ?>&layer_id=<? echo $this->qlayerset[$i]['Layer_ID'];?>"><? echo $strMapZoom; ?></a>
<?
			    				}
			    				elseif($geomtype == 'MULTILINESTRING' OR $geomtype == 'LINESTRING') {
?>
			    					<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="" onclick="this.href='index.php?go=zoomToLine&oid=<?php echo $this->qlayerset[$i]['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $this->qlayerset[$i]['Layer_ID'];?>&selektieren='+document.GUI.selektieren<? echo $this->qlayerset[$i]['Layer_ID'].'_'.$k; ?>.checked;"><? echo $strMapZoom; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px"><? echo $strMapSelect; ?></span><input type="checkbox" name="selektieren<? echo $this->qlayerset[$i]['Layer_ID'].'_'.$k; ?>" value="1">
<?
			    				}
?>
			    				</td>
							</tr>
						</table>
					</td>
			    </tr>
			    <tr>
			    	<td colspan="3" align="center"><a href="index.php?go=jagdkatastereditor_Flurstuecke_Listen&oid=<?php echo $this->qlayerset[$i]['shape'][$k]['jagdbezirke_oid']; ?>&name=<? echo $this->qlayerset[$i]['shape'][$k]['name'] ?>&search_nummer=<? echo $this->formvars['search_nummer']; ?>&search_name=<? echo $this->formvars['search_name']; ?>&search_art=<? echo $this->formvars['search_art']; ?>">enthaltene Flurstücke</a></td>
			    </tr>
<? }

				if($privileg == 1) {
					if($this->new_entry == true){
						$this->titel='Geometrie-Editor';
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
						} elseif($geomtype == 'MULTILINESTRING') {
							echo '
							<tr>
								<td colspan="2" align="center">';
									include(LAYOUTPATH.'snippets/LineEditor.php');
							echo'
								</td>
							</tr>';
						}
					}
					else{
							if($this->formvars['printversion'] == '' AND !$lock[$k]){
	?>
								<tr>
						    	<td colspan="2" align="center">
						    		<table border="0" cellspacing="0" cellpadding="2">
						    			<tr>
						    				<td align="center">
	<?
									if($geomtype == 'POLYGON' OR $geomtype == 'MULTIPOLYGON' OR $geomtype == 'GEOMETRY'){
	?>
				    					<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="index.php?go=PolygonEditor&oid=<?php echo $this->qlayerset[$i]['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $this->qlayerset[$i]['Layer_ID'];?>&selected_layer_id=<? echo $this->qlayerset[$i]['Layer_ID'];?>"><? echo $strEditGeom; ?></a>
	<?
									} elseif($geomtype == 'POINT') {
	?>
				    					<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="index.php?go=PointEditor&dimension=<? echo $dimension; ?>&oid=<?php echo $this->qlayerset[$i]['shape'][$k][$tablename.'_oid']; ?>&tablename=<? echo $tablename; ?>&columnname=<? echo $columnname; ?>&layer_id=<? echo $this->qlayerset[$i]['Layer_ID'];?>"><? echo $strEditGeom; ?></a>
	<?
				    				}
				    				elseif($geomtype == 'MULTILINESTRING' OR $geomtype == 'LINESTRING') {
	?>
				    					<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="index.php?go=LineEditor&oid=<?php echo $this->qlayerset[$i]['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $this->qlayerset[$i]['Layer_ID'];?>&selected_layer_id=<? echo $this->qlayerset[$i]['Layer_ID'];?>"><? echo $strEditGeom; ?></a>
	<?
				    				}
	?>
										</td>
									</tr>
								</table>
							</td>
					    </tr>
	<?
						}
					}
				}
 ?>
			</table>
			<br>
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
					<td>
						<a href="javascript:selectall(<? echo $this->qlayerset[$i]['Layer_ID']; ?>);"><? echo $strSelectAll; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<? echo $strSelectedDatasets.':'; ?>&nbsp;
					</td>
					<td height="23">
					<? if($this->qlayerset[$i]['privileg'] == '2'){ ?>
						&bull;&nbsp;<a href="javascript:delete_datasets(<?php echo $this->qlayerset[$i]['Layer_ID']; ?>);"><? echo $strdelete; ?></a>&nbsp;&nbsp;&nbsp;
					<?}?>
						&bull;&nbsp;<a id="csv_link" href="javascript:csv_export(<?php echo $this->qlayerset[$i]['Layer_ID']; ?>);"><? echo $strCSVExport; ?></a>&nbsp;&nbsp;&nbsp;
						&bull;&nbsp;<a id="csv_link" href="javascript:print_data(<?php echo $this->qlayerset[$i]['Layer_ID']; ?>);"><? echo $strPrint; ?></a>
					</td>
				<? if($this->formvars['printversion'] == '' AND $privileg != ''){ ?>
				</tr>
				<tr>
					<td></td>
					<td colspan="3">
						&bull;&nbsp;<a href="javascript:zoomto_datasets(<?php echo $this->qlayerset[$i]['Layer_ID']; ?>, '<? echo $tablename; ?>', '<? echo $columnname; ?>');"><? echo $strzoomtodatasets; ?></a>
						<select name="klass_<?php echo $this->qlayerset[$i]['Layer_ID']; ?>">
							<option value="">klassifiziert nach:</option>
							<?
							for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
								if($this->qlayerset[$i]['attributes']['name'][$j] != $this->qlayerset[$i]['attributes']['the_geom']){
									echo '<option value="'.$this->qlayerset[$i]['attributes']['name'][$j].'">'.$this->qlayerset[$i]['attributes']['alias'][$j].'</option>';
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
						&bull;&nbsp;<a href="javascript:showcharts(<?php echo $this->qlayerset[$i]['Layer_ID']; ?>);"><? echo $strCreateChart; ?></a>
					</td>
				</tr>
				<tr id="charts_<?php echo $this->qlayerset[$i]['Layer_ID']; ?>" style="display:none">
					<td></td>
					<td>
						<table>
							<tr>
								<td colspan="2">
									&nbsp;&nbsp;<select name="charttype_<?php echo $this->qlayerset[$i]['Layer_ID']; ?>" onchange="change_charttype(<?php echo $this->qlayerset[$i]['Layer_ID']; ?>);">
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
									<select style="width:133px" id="" name="chartlabel_<?php echo $this->qlayerset[$i]['Layer_ID']; ?>" >
										<?
										for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
											if($this->qlayerset[$i]['attributes']['name'][$j] != $this->qlayerset[$i]['attributes']['the_geom']){
												echo '<option value="'.$this->qlayerset[$i]['attributes']['name'][$j].'">'.$this->qlayerset[$i]['attributes']['alias'][$j].'</option>';
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
									<select style="width:133px" name="chartvalue_<?php echo $this->qlayerset[$i]['Layer_ID']; ?>" onchange="create_chart(<?php echo $this->qlayerset[$i]['Layer_ID']; ?>);">
										<option value="">--- Bitte Wählen ---</option>
										<?
										for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
											if($this->qlayerset[$i]['attributes']['name'][$j] != $this->qlayerset[$i]['attributes']['the_geom']){
												echo '<option value="'.$this->qlayerset[$i]['attributes']['name'][$j].'">'.$this->qlayerset[$i]['attributes']['alias'][$j].'</option>';
											}
										}
										?>
									</select>
								</td>
							</tr>
							<tr id="split_<?php echo $this->qlayerset[$i]['Layer_ID']; ?>" style="display:none">
								<td>
									&nbsp;&nbsp;Trenn-Attribut:
								</td>
								<td>
									<select style="width:133px" name="chartsplit_<?php echo $this->qlayerset[$i]['Layer_ID']; ?>" onchange="create_chart(<?php echo $this->qlayerset[$i]['Layer_ID']; ?>);">
										<option value="">--- Bitte Wählen ---</option>
										<?
										for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
											if($this->qlayerset[$i]['attributes']['name'][$j] != $this->qlayerset[$i]['attributes']['the_geom']){
												echo '<option value="'.$this->qlayerset[$i]['attributes']['name'][$j].'">'.$this->qlayerset[$i]['attributes']['alias'][$j].'</option>';
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
		<td colspan="2" align="center"><a id="csv_link" href="javascript:csv_export_all(<?php echo $this->qlayerset[$i]['Layer_ID']; ?>);"><? echo $strCSVExportAll; ?></a></td>
	</tr>
	<?}
	}?>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
<input type="hidden" name="checkbox_names_<? echo $this->qlayerset[$i]['Layer_ID']; ?>" value="<? echo $checkbox_names; ?>">
<input type="hidden" name="orderby<? echo $this->qlayerset[$i]['Layer_ID']; ?>" id="orderby<? echo $this->qlayerset[$i]['Layer_ID']; ?>" value="<? echo $this->formvars['orderby'.$this->qlayerset[$i]['Layer_ID']]; ?>">

<?
  }
  else {
?>
<br><strong><font color="#FF0000">
Zu diesem Layer wurden keine Objekte gefunden!</font></strong><br>
Wählen Sie einen neuen Bereich oder prüfen Sie die Datenquellen.<br>
<?
  }
?>
