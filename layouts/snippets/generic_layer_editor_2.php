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
 $size = 61;
 $select_width = ''; 
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
							if($attributes['tooltip'][$j]!='' AND $attributes['form_element_type'][$j] != 'Time'){
							  echo '<td align="right"><a href="#" title="'.$attributes['tooltip'][$j].'"><img src="'.GRAPHICSPATH.'emblem-important.png" border="0"></a></td>';
							}
							if($attributes['type'][$j] == 'date' OR $attributes['type'][$j] == 'timestamp' OR $attributes['type'][$j] == 'timestamptz'){
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
								include(SNIPPETS.'generic_formelements.php');
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
						elseif($layer['shape'][$k]['geom']){		# bei WFS-Layern
?>
							&bull;&nbsp;<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="index.php?go=zoom2wkt&wkt=<? echo $layer['shape'][$k]['geom']; ?>&epsg=<? echo $layer['epsg_code']; ?>"><? echo $strMapZoom; ?></a>&nbsp;&nbsp;&nbsp;
<?															
						}
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
