<?php
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

<STYLE type="text/css">
   td {font-size: 9pt}
   input.class2 {border:none;background-color: transparent;text-align:right;}
   .titel {font-size: 17pt; font-weight: bold}
</STYLE>

<script type="text/javascript">
<!--

function nurZahlen(el)
{
  var val = el.value.replace(/[^-\.\d]/g, '');
  el.value = val;
}

function update_require_attribute(attributes, k,layer_id, value){
	// attributes ist eine Liste von zu aktualisierenden Attributen, k die Nummer des Datensatzes und value der ausgewaehlte Wert
	attribute = attributes.split(',');
	for(i = 0; i < attribute.length; i++){
		type = document.getElementById(attribute[i]+'_'+k).type;
		if(type == 'text'){action = 'setvalue'};
		if(type == 'select-one'){action = 'sethtml'};
		ahah("<? echo URL.APPLVERSION; ?>index.php", "go=get_select_list&layer_id="+layer_id+"&attribute="+attribute[i]+"&value="+value+"&type="+type, new Array(document.getElementById(attribute[i]+'_'+k)), new Array(action));
	}
}

function show_details(oid){
	document.GUI.go.value = 'Layer-Suche_Suchen';
	document.GUI.search.value = 'true';
	document.GUI.selected_layer_id.value = <? echo $this->qlayerset[$i]['Layer_ID'] ?>;
	document.GUI.details.value = 'true';
	document.GUI.offset_<? echo $this->qlayerset[$i]['Layer_ID']; ?>.value = '';
	document.GUI.value_tblf_plan_oid.value = oid;
	document.GUI.submit();
}

function go_back(){
	document.GUI.details.value = '';
	document.GUI.go.value = 'Layer-Suche_Suchen';
	document.GUI.selected_layer_id.value = <? echo $this->qlayerset[$i]['Layer_ID'] ?>;
	document.GUI.value_tblf_plan_oid.value = '';
	document.GUI.offset_<? echo $this->qlayerset[$i]['Layer_ID']; ?>.value = document.GUI._offset_<? echo $this->qlayerset[$i]['Layer_ID']; ?>.value;
	document.GUI.submit();
}

function save_as_new_dataset(){
	form_fieldstring = document.GUI.form_field_names.value+'';
	form_fields = form_fieldstring.split('|');
	for(i = 0; i < form_fields.length-1; i++){
		fieldstring = form_fields[i]+'';
		field = fieldstring.split(';');
		if(document.getElementsByName(fieldstring)[0] != undefined && field[4] != 'Dokument' && document.getElementsByName(fieldstring)[0].readOnly == false && field[5] == '0' && document.getElementsByName(fieldstring)[0].value == ''){
			alert('Das Feld '+document.getElementsByName(fieldstring)[0].title+' erfordert eine Eingabe.');
			return;
		}
	}
	document.GUI.go.value = 'neuer_Layer_Datensatz_speichern';
	document.GUI.submit();
}

function delete_dataset(oid){
	really = confirm('Wollen Sie diesen Datensatz wirklich löschen?');
	if(really){
		if((document.GUI.details.value != 'true' && document.GUI.value_tblf_plan_oid.value == '') || (document.GUI.details.value == 'true' && document.GUI.value_tblf_plan_oid.value != '')){		// Trefferliste vorhanden -> wieder zurück zur Trefferliste
			document.GUI.details.value = '';
			document.GUI.selected_layer_id.value = <? echo $this->qlayerset[$i]['Layer_ID'] ?>;
			document.GUI.value_tblf_plan_oid.value = '';
			document.GUI.offset_<? echo $this->qlayerset[$i]['Layer_ID']; ?>.value = document.GUI._offset_<? echo $this->qlayerset[$i]['Layer_ID']; ?>.value;
		}
		document.GUI.oid.value = oid;
		document.GUI.go.value = 'delete_fplan';
		document.GUI.submit();
	}
}

function zoomto(gkz){
	document.GUI.gkz.value = gkz;
	document.GUI.go.value = 'zoomtofplan';
	document.GUI.submit();
}

//-->
</script>

<? 
	$i = 0;
	$j = 0;
	$k = 0;
	$this->editable = 'true';
	$layer = $this->qlayerset[$i];
 	$attributes = $layer['attributes'];
	$this->editable = 'true';
?>
  
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
	<? /*for($a = 0; $a < count($this->qlayerset[$i]['attributes']['name']); $a++){
		echo $this->qlayerset[$i]['attributes']['name'][$a].'  '.$a.'<br>';
	}*/
	if($this->formvars['value_tblf_plan_oid'] != '' OR $this->new_entry == true){
		$this->formvars['printversion'] = 'n';   # nur dazu da, damit die Links "zurück zur Suche" und "drucken" nicht erscheinen
?>
		<td>&nbsp;&nbsp;&nbsp;</td>
		<td colspan="3" width="100%">
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td>Gemeinde:</td>
					<td><?  $j = 1;
									$this->qlayerset[$i]['attributes']['name'][$j] = 'gkz';
			  					$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
									echo '<select title="'.$this->qlayerset[$i]['attributes']['alias'][$j].'" style="font-size: '.$this->user->rolle->fontsize_gle.'px" ';
									echo 'onchange="update_require_attribute(\''.$this->qlayerset[$i]['attributes']['req_by'][$j].'\', '.$k.','.$this->qlayerset[$i]['Layer_ID'].', this.value);" ';
									echo 'id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'">';
									echo '<option value="">-- Bitte Auswählen --</option>';
									for($e = 0; $e < count($this->qlayerset[$i]['attributes']['enum_value'][$j]); $e++){
										echo '<option ';
										if($this->qlayerset[$i]['attributes']['enum_value'][$j][$e] == $this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]] OR ($this->qlayerset[$i]['attributes']['enum_value'][$j][$e] != '' AND $this->qlayerset[$i]['attributes']['enum_value'][$j][$e] == $this->formvars[$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j]])){
											echo 'selected ';
										}
										echo 'value="'.$this->qlayerset[$i]['attributes']['enum_value'][$j][$e].'">'.$this->qlayerset[$i]['attributes']['enum_output'][$j][$e].'</option>';
									}
									echo '</select>'; 
									$j = 0;?>
					</td>
					<td>Gemeindekennzahl:</td>
					<td><input readonly="true" <? $this->qlayerset[$i]['attributes']['name'][$j] = 'gemkz'; echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
					<td rowspan="4"><span style="background-color: #80AEFF" class="titel">&nbsp;&nbsp;&nbsp;Flächennutzungsplandaten&nbsp;&nbsp;&nbsp;</span></td>
				</tr>
				<tr>
					<td>Amt:</td>
					<td><input readonly="true" <? $this->qlayerset[$i]['attributes']['name'][$j] = 'amt'; echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
					<td>Zentrale Orte:</td>
					<td><input readonly="true" <? $this->qlayerset[$i]['attributes']['name'][$j] = 'zentrort'; echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
				</tr>
				<tr>
					<td>Kreis:</td>
					<td><input readonly="true" <? $this->qlayerset[$i]['attributes']['name'][$j] = 'kreis'; echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
					<td>Stadt-Umland-Raum:</td>
					<td><input readonly="true" <? $this->qlayerset[$i]['attributes']['name'][$j] = 'ordraum'; echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
				</tr>
				<tr>
					<td>Planungsregion:</td>
					<td><input readonly="true" <? $this->qlayerset[$i]['attributes']['name'][$j] = 'planungsregion'; echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
				</tr>
			</table>
		</td>
		<td>&nbsp;&nbsp;&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr bgcolor="#DAE7FE">
		<td colspan="5">&nbsp;</td>
	</tr>
  <tr bgcolor="#DAE7FE">
  	<td>&nbsp;&nbsp;&nbsp;</td>
  	<td>
  		<table width="100%" style="border: 2px solid #80AEFF" cellspacing="0" cellpadding="2">
  			<tr bgcolor="#80AEFF">
  				<td colspan="4" align="center"><b>Plandaten</b></td>
  			</tr>
  			<tr>
  				<td>Planbezeichnung:</td>
  				<?
  					$this->qlayerset[$i]['attributes']['name'][$j] = 'planbez'; 
  					$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|'; 
  				?>
					<td colspan="3" width="500px" ><input size="50" <? echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  			</tr>
  			<tr>
  				<td>Planstand:</td>
  				<?
  					$this->qlayerset[$i]['attributes']['name'][$j] = 'planstand';
  					$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  				?>
					<td width="128px"><input size="15" <? echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
					<td width="75px">Aktenzeichen</td>
					<?
  					$this->qlayerset[$i]['attributes']['name'][$j] = 'aktenzeichen';
  					$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  				?>
					<td><input size="15" <? echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  			</tr>
  			<tr>
  				<td>ROK-Nr.</td>
  				<?
  					$this->qlayerset[$i]['attributes']['name'][$j] = 'lfd_rok_nr';
  					$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  				?>
					<td><input size="15" <? echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
					<td>aktuell:</td>
  				<?
  					$j = 2; 
  					$this->qlayerset[$i]['attributes']['name'][$j] = 'aktuell';
  					$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  				?>
					<td><?  echo '<select style="width:115px" title="'.$this->qlayerset[$i]['attributes']['alias'][$j].'" style="font-size: '.$this->user->rolle->fontsize_gle.'px"';
									echo 'id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'">';
									echo '<option value="">-- Bitte Auswählen --</option>';
									for($e = 0; $e < count($this->qlayerset[$i]['attributes']['enum_value'][$j]); $e++){
										echo '<option ';
										if($this->qlayerset[$i]['attributes']['enum_value'][$j][$e] == $this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]] OR ($this->qlayerset[$i]['attributes']['enum_value'][$j][$e] != '' AND $this->qlayerset[$i]['attributes']['enum_value'][$j][$e] == $this->formvars[$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j]])){
											echo 'selected ';
										}
										echo 'value="'.$this->qlayerset[$i]['attributes']['enum_value'][$j][$e].'">'.$this->qlayerset[$i]['attributes']['enum_output'][$j][$e].'</option>';
									}
									echo '</select>'; 
									$j = 0;?>
					</td>
  			</tr>
  			<tr>
  				<td>Gemeindename alt:</td>
  				<?
  					$this->qlayerset[$i]['attributes']['name'][$j] = 'gemeinde_alt';
  					$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  				?>
					<td colspan="3"><input size="50" <? echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  			</tr>
  		</table>
  		<br>
  		<table style="border: 2px solid #80AEFF" cellspacing="0" cellpadding="4">
  			<tr>
  				<td colspan="2" align="center" bgcolor="#80AEFF"><b>Flächen</b></td>
  			</tr>
  			<tr>
  				<td>
  					<table border="0" style="border-collapse: collapse;">
  						<tr bgcolor="80AEFF">
  							<td style="border: 1px solid black;" align="center" valign="top"><b>Nutzungsart</td>
  							<td style="border: 1px solid black;" align="center" valign="top"><b>Bestand [ha]</b></td>
  							<td style="border: 1px solid black;" align="center" valign="top"><b>+/- Planung [ha]</b></td>
  							<td style="border: 1px solid black;" align="center" valign="top"><b>Gesamt [ha]</b></td>
  						</tr>
  						<tr bgcolor="ACCAFF">
  							<td style="border: 1px solid black;">Wohnbaufläche</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'w_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'w_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'w_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="FFFFFF">
  							<td style="border: 1px solid black;">gemischte Baufläche</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'm_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'm_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'm_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="ACCAFF">
  							<td style="border: 1px solid black;">gewerbliche Baufläche</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'g_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'g_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'g_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="C5FEFE">
  							<td style="border: 1px solid black;">Erholung alt</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'so_erhbest';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'so_erhpl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'so_erhneu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="FFFFFF">
  							<td style="border: 1px solid black;">Handel alt</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'so_handbest';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'so_handpl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'so_handneu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="C5FEFE">
  							<td style="border: 1px solid black;">Wind alt</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'so_windbest';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'so_windpl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'so_windneu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="FFFFFF">
  							<td style="border: 1px solid black;">sonstige alt</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'so_sonstbest';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'so_sonstpl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'so_sonstneu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="C5FEFE">
  							<td style="border: 1px solid black;">Sonderbaufläche gesamt</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'so_gesbest';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'so_gespl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'so_gesneu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						
  						<tr bgcolor="ACCAFF">
  							<td style="border: 1px solid black;">Industrieflächen</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'i_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'i_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'i_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="FFFFFF">
  							<td style="border: 1px solid black;">Flächen für Gemeinbedarf</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'gem_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'gem_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'gem_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="ACCAFF">
  							<td style="border: 1px solid black;">Verkehrsflächen</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'vk_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'vk_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'vk_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="FFFFFF">
  							<td style="border: 1px solid black;">Flächen&nbsp;für&nbsp;Ver&nbsp;u.&nbsp;Entsorgung</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'ver_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'ver_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'ver_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="ACCAFF">
  							<td style="border: 1px solid black;">Grünflächen</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'gruen_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'gruen_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'gruen_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="FFFFFF">
  							<td style="border: 1px solid black;">Flächen für Landwirtschaft</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'lw_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'lw_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'lw_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="ACCAFF">
  							<td style="border: 1px solid black;">Flächen für Wald</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'fo_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'fo_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'fo_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="FFFFFF">
  							<td style="border: 1px solid black;">Wasserflächen</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'wa_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'wa_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'wa_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="ACCAFF">
  							<td style="border: 1px solid black;">Aufschütung und Abtragung</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'auf_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'auf_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'auf_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="FFFFFF">
  							<td style="border: 1px solid black;">sonstige Flächen</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'sonst_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'sonst_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'sonst_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="ACCAFF">
  							<td style="border: 1px solid black;">Gesamtfläche</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'gesfl_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'gesfl_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'gesfl_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  					</table>
  				</td>
  				<td>
  					<table border="0" style="border-collapse: collapse;">
  						<tr bgcolor="80AEFF">
  							<td style="border: 1px solid black;" align="center" valign="top"><b>Sonderbauflächen</td>
  							<td style="border: 1px solid black;" align="center" valign="top"><b>Bestand [ha]</b></td>
  							<td style="border: 1px solid black;" align="center" valign="top"><b>+/- Planung [ha]</b></td>
  							<td style="border: 1px solid black;" align="center" valign="top"><b>Gesamt [ha]</b></td>
  						</tr>
  						<tr bgcolor="FFFFFF">
  							<td style="border: 1px solid black;">Erholung</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'weh_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'weh_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'weh_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="C5FEFE">
  							<td style="border: 1px solid black;">Fremdenbeherbergung</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'fh_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'fh_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'fh_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="FFFFFF">
  							<td style="border: 1px solid black;">Handelseinrichtungen</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'beh_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'beh_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'beh_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="C5FEFE">
  							<td style="border: 1px solid black;">Freizeiteinrichtungen</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'c_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'c_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'c_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="FFFFFF">
  							<td style="border: 1px solid black;">Wissenschaft u. Technik</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'kg_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'kg_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'kg_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="C5FEFE">
  							<td style="border: 1px solid black;">Hafengebiet</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'bw_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'bw_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'bw_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="FFFFFF">
  							<td style="border: 1px solid black;">Wassersporteinrichtungen</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'sh_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'sh_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'sh_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="C5FEFE">
  							<td style="border: 1px solid black;">Klinikgebiet</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'tp_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'tp_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'tp_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						
  						<tr bgcolor="FFFFFF">
  							<td style="border: 1px solid black;">Bundeseinrichtungen</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'rp_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'rp_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'rp_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="C5FEFE">
  							<td style="border: 1px solid black;">Erneuerbare Energien</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'gp_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'gp_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'gp_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="FFFFFF">
  							<td style="border: 1px solid black;">ausschl. gewerbl. Präg.</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'hfn_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'hfn_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'hfn_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="C5FEFE">
  							<td style="border: 1px solid black;">landwirtschaftl Anlagen</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'eh_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'eh_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'eh_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="FFFFFF">
  							<td style="border: 1px solid black;">Verkehrseinrichtungen</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'wind_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'wind_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'wind_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="C5FEFE">
  							<td style="border: 1px solid black;">sonstiges</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'sa_best';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'sa_pl';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'sa_neu';
  								$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  						<tr bgcolor="FFFFFF">
  							<td style="border: 1px solid black;"><input class="class2" size="8" type="text"></td>
  							<td style="border: 1px solid black;"><input class="class2" size="8" type="text"></td>
  							<td style="border: 1px solid black;"><input class="class2" size="8" type="text"></td>
  							<td style="border: 1px solid black;"><input class="class2" size="8" type="text"></td>
  						</tr>
  						<tr bgcolor="FFFFFF">
  							<td style="border: 1px solid black;"><input class="class2" size="8" type="text"></td>
  							<td style="border: 1px solid black;"><input class="class2" size="8" type="text"></td>
  							<td style="border: 1px solid black;"><input class="class2" size="8" type="text"></td>
  							<td style="border: 1px solid black;"><input class="class2" size="8" type="text"></td>
  						</tr>
  						<tr bgcolor="FFFFFF">
  							<td style="border: 1px solid black;"><input class="class2" size="8" type="text"></td>
  							<td style="border: 1px solid black;"><input class="class2" size="8" type="text"></td>
  							<td style="border: 1px solid black;"><input class="class2" size="8" type="text"></td>
  							<td style="border: 1px solid black;"><input class="class2" size="8" type="text"></td>
  						</tr>
  						<tr bgcolor="FFFFFF">
  							<td style="border: 1px solid black;"><input class="class2" size="8" type="text"></td>
  							<td style="border: 1px solid black;"><input class="class2" size="8" type="text"></td>
  							<td style="border: 1px solid black;"><input class="class2" size="8" type="text"></td>
  							<td style="border: 1px solid black;"><input class="class2" size="8" type="text"></td>
  						</tr>
  						<tr bgcolor="ACCAFF">
  							<td style="border: 1px solid black;">Gesamtfläche Sonderbau</td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'so_gesbest';
  								#$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this);" class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'so_gespl';
  								#$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  							<?
  								$this->qlayerset[$i]['attributes']['name'][$j] = 'so_gesneu';
  								#$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  							?>
  							<td style="border: 1px solid black;"><input <? echo ' type="text" onkeyup="nurZahlen(this)"; class="class2" size="8" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  						</tr>
  					</table>
  				</td>
  			</tr>
  		</table>
  	</td>
  	<td>&nbsp;&nbsp;&nbsp;</td>
  	<td valign="top">
  		<table width="100%" style="border: 2px solid #80AEFF" cellspacing="0" cellpadding="2">
  			<tr bgcolor="#80AEFF">
  				<td colspan="4" align="center"><b>Zeitbezug</b></td>
  			</tr>
  			<tr>
					<td>Datum Eingang:</td>
					<?
						$this->qlayerset[$i]['attributes']['name'][$j] = 'datumeing';
						$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
					?>
					<td><input <? echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
				</tr>
				<tr>
					<td>Datum Bearbeitung:</td>
					<?
						$this->qlayerset[$i]['attributes']['name'][$j] = 'datumbearb';
						$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
					?>
					<td><input <? echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
				</tr>
				<tr>
					<td>Datum Genehmigung:</td>
					<?
						$this->qlayerset[$i]['attributes']['name'][$j] = 'datumgenehm';
						$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
					?>
					<td><input <? echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
				</tr>
				<tr>
					<td>Datum Bekanntmachung:</td>
					<?
						$this->qlayerset[$i]['attributes']['name'][$j] = 'datumbeka';
						$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
					?>
					<td><input <? echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
				</tr>
				<tr>
					<td>Datum Aufhebung:</td>
					<?
						$this->qlayerset[$i]['attributes']['name'][$j] = 'datumaufh';
						$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
					?>
					<td><input <? echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
				</tr>
  		</table>
  		<br>
  		<table style="border: 2px solid #80AEFF" cellspacing="0" cellpadding="2">
  			<tr bgcolor="#80AEFF">
  				<td colspan="4" align="center"><b>Ergebnis der Stellungnahme</b></td>
  			</tr>
  			<tr>
					<td>Maßgaben:
					<?
						$this->qlayerset[$i]['attributes']['name'][$j] = 't_massgabe';
						$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
					 echo '<textarea cols="35" style="font-size: '.$this->user->rolle->fontsize_gle.'px" style="font-family: Arial, Verdana, Helvetica, sans-serif;"';
										echo ' rows="3" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'">'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'</textarea>'; ?>
					</td>
				</tr>
				<tr>
					<td>Hinweise:
					<?
						$this->qlayerset[$i]['attributes']['name'][$j] = 't_hinweis';
						$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
					 echo '<textarea cols="35" style="font-size: '.$this->user->rolle->fontsize_gle.'px" style="font-family: Arial, Verdana, Helvetica, sans-serif;"';
										echo ' rows="3" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'">'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'</textarea>'; ?>
					</td>
				</tr>
				<tr>
					<td>Bemerkungen:
					<?
						$this->qlayerset[$i]['attributes']['name'][$j] = 'erteilteaufl';
						$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
					 echo '<textarea cols="35" style="font-size: '.$this->user->rolle->fontsize_gle.'px" style="font-family: Arial, Verdana, Helvetica, sans-serif;"';
										echo ' rows="3" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'">'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'</textarea>'; ?>
					</td>
				</tr>
				<tr>
					<td>
					<?
						$this->qlayerset[$i]['attributes']['name'][$j] = 'ergebnis';
						$this->qlayerset[$i]['attributes']['enum_value'][$j] = array(0,1,2,3);
						$this->qlayerset[$i]['attributes']['enum_output'][$j] = array('', 'in Bearbeitung', 'zugestimmt', 'abgelehnt');
						$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
					  echo '<select title="'.$this->qlayerset[$i]['attributes']['alias'][$j].'" style="font-size: '.$this->user->rolle->fontsize_gle.'px"';
									echo 'id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'">';
									echo '<option value="">-- Bitte Auswählen --</option>';
									for($e = 0; $e < count($this->qlayerset[$i]['attributes']['enum_value'][$j]); $e++){
										echo '<option ';
										if($this->qlayerset[$i]['attributes']['enum_value'][$j][$e] == $this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]] OR ($this->qlayerset[$i]['attributes']['enum_value'][$j][$e] != '' AND $this->qlayerset[$i]['attributes']['enum_value'][$j][$e] == $this->formvars[$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j]])){
											echo 'selected ';
										}
										echo 'value="'.$this->qlayerset[$i]['attributes']['enum_value'][$j][$e].'">'.$this->qlayerset[$i]['attributes']['enum_output'][$j][$e].'</option>';
									}
									echo '</select>'; ?>
					</td>
  			</tr>
			</table>
  	</td>
  	<td>&nbsp;&nbsp;&nbsp;</td> 
  </tr>
  <tr bgcolor="#DAE7FE">
		<td colspan="5">&nbsp;</td>
	</tr>
</table>
<br>
<? if($this->new_entry != true){ ?>
<table width="100%" border="0" cellpadding="2" cellspacing="0">
  <tr align="center"> 
    <td>
    	<input type="button" class="button" name="savebutton" value="<? echo $strSave; ?>" onclick="save();">&nbsp;&nbsp;
    	<input type="button" class="button" name="savebutton2" value="Als neuen Datensatz speichern" onclick="save_as_new_dataset();">&nbsp;&nbsp;
    	<input type="button" class="button" name="deletebutton" value="Löschen" onclick="delete_dataset(<? echo $this->qlayerset[$i]['shape'][$k]['tblf_plan_oid']; ?>);">&nbsp;&nbsp;
    	<input type="button" class="button" name="mapbutton" value="In die Karte" onclick="zoomto('<? echo $this->qlayerset[$i]['shape'][$k]['gemkz']; ?>');">
    </td>
  </tr>
</table>
<? } ?>
<? if($this->new_entry != true AND $this->formvars['details'] == true){ ?>
		<br>
		<a href="javascript:go_back();">zurück zur Trefferliste</a>
		<br>
<? } ?>
<br>
<?php   
		#  zusätzliches Hiddenfeld zum Merken des Offsets der Trefferliste, solange man in der Detailansicht ist 
		echo '<input name="_offset_'.$this->qlayerset[$i]['Layer_ID'].'" type="hidden" value="'.$this->formvars['_offset_'.$this->qlayerset[$i]['Layer_ID']].'">';
	}				# details == true
	else{ ?>
		<table border="1" cellpadding="2" style="border-collapse: collapse;">
			<tr>
				<td><b>Gemeinde:</b></td>
				<td><b>ROK-Nr:</b></td>
  			<td width="300px"><b>Planbezeichnung:</b></td>
  			<td><b>aktuell:</b></td>
  			<td></td>
  		</tr>
	<?	for($k=0;$k<$anzObj;$k++) { ?>
				<tr>
					<? $this->qlayerset[$i]['attributes']['name'][$j] = 'gemeinde'; ?>
					<td valign="top"><a href="javascript:show_details(<? echo $this->qlayerset[$i]['shape'][$k]['tblf_plan_oid']; ?>);"><? echo htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]); ?></a></td>
					<? $this->qlayerset[$i]['attributes']['name'][$j] = 'lfd_rok_nr'; ?>
					<td valign="top"><a href="javascript:show_details(<? echo $this->qlayerset[$i]['shape'][$k]['tblf_plan_oid']; ?>);"><? echo htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]); ?></a></td>
					<? $this->qlayerset[$i]['attributes']['name'][$j] = 'planbez'; ?>
					<td valign="top"><a href="javascript:show_details(<? echo $this->qlayerset[$i]['shape'][$k]['tblf_plan_oid']; ?>);"><? echo htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]); ?></a></td>
					<? $this->qlayerset[$i]['attributes']['name'][$j] = 'aktuell'; ?>
					<td valign="top"><a href="javascript:show_details(<? echo $this->qlayerset[$i]['shape'][$k]['tblf_plan_oid']; ?>);"><? echo htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]); ?></a></td>
					<td valign="top"><a href="javascript:delete_dataset(<? echo $this->qlayerset[$i]['shape'][$k]['tblf_plan_oid']; ?>);">löschen</a></td>
  			</tr>
	<?	} ?>
		</table>
		<input type="hidden" name="value_tblf_plan_oid" value="">
<?	echo '<input name="_offset_'.$this->qlayerset[$i]['Layer_ID'].'" type="hidden" value="'.$this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']].'">';
		$this->editable = 'false';
		}
}
else {
    ?><br><strong><font color="#FF0000">
    Zu diesem Layer wurden keine Objekte gefunden!</font></strong><br>
    Wählen Sie einen neuen Bereich oder prüfen Sie die Datenquellen.<br>
    <?php   
  }
?>
<input type="hidden" value="1" name="changed_<? echo $layer['shape'][$k][$attributes['table_name'][$attributes['name'][0]].'_oid']; ?>">
<input name="search" type="hidden" value="true">
<input type="hidden" name="details" value="<? echo $this->formvars['details']; ?>">
<input type="hidden" name="oid" value="">
<input type="hidden" name="gkz" value="">
<? if($this->new_entry != true){ ?>
<input type="hidden" name="selected_layer_id" value="">
<? } ?>


