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
   td {font-size: 10pt}
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
		ahah("<? echo URL.APPLVERSION; ?>index.php", "go=get_select_list&layer_id="+layer_id+"&attribute="+attribute[i]+"&value="+value+"&type="+type, new Array(document.getElementById(attribute[i]+'_'+k)), action);
	}
}


function show_details(oid){
	document.GUI.go.value = 'Layer-Suche_Suchen';
	document.GUI.search.value = 'true';
	document.GUI.selected_layer_id.value = <? echo $this->qlayerset[$i]['Layer_ID'] ?>;
	document.GUI.details.value = 'true';
	document.GUI.offset_<? echo $this->qlayerset[$i]['Layer_ID']; ?>.value = '';
	document.GUI.value_b_plan_stammdaten_oid.value = oid;
	document.GUI.submit();
}

function go_back(){
	document.GUI.details.value = '';
	document.GUI.go.value = 'Layer-Suche_Suchen';
	document.GUI.selected_layer_id.value = <? echo $this->qlayerset[$i]['Layer_ID'] ?>;
	document.GUI.value_b_plan_stammdaten_oid.value = '';
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
		if((document.GUI.details.value != 'true' && document.GUI.value_b_plan_stammdaten_oid.value == '') || (document.GUI.details.value == 'true' && document.GUI.value_b_plan_stammdaten_oid.value != '')){		// Trefferliste vorhanden -> wieder zurück zur Trefferliste
			document.GUI.details.value = '';
			document.GUI.selected_layer_id.value = <? echo $this->qlayerset[$i]['Layer_ID'] ?>;
			document.GUI.value_b_plan_stammdaten_oid.value = '';
			document.GUI.offset_<? echo $this->qlayerset[$i]['Layer_ID']; ?>.value = document.GUI._offset_<? echo $this->qlayerset[$i]['Layer_ID']; ?>.value;
		}
		document.GUI.oid.value = oid;
		document.GUI.go.value = 'delete_bplan';
		document.GUI.submit();
	}
}

function zoomto(roknr){
	document.GUI.roknr.value = roknr;
	document.GUI.go.value = 'zoomtobplan';
	document.GUI.submit();
}

function set_changed_flag(flag){
}

function update_planart(){
	if(document.getElementById('art_0').value == 'Innenbereichssatzung' || document.getElementById('art_0').value == 'Außenbereichssatzung'){
		document.getElementById('kap').style.display = '';
	}
	else{
		document.getElementById('kap').style.display = 'none';
	}
}

function update_gebietstyp(){
	if(document.getElementById('kap2_gemziel')){
		if(document.getElementById('gebietstyp_0').value == 33){	// Wohnen+Ferienwohnen
			document.getElementById('kap2_gemziel').style.display = '';
			document.getElementById('kap2_nachstell').style.display = '';
		}
		else{
			document.getElementById('kap2_gemziel').style.display = 'none';
			document.getElementById('kap2_nachstell').style.display = 'none';
		}
	}
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
?>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>&nbsp;&nbsp;</td>
		<td>  
	<? /*for($a = 0; $a < count($this->qlayerset[$i]['attributes']['name']); $a++){
		echo $this->qlayerset[$i]['attributes']['name'][$a].'  '.$a.'<br>';
	}*/
	if($this->formvars['value_b_plan_stammdaten_oid'] != '' OR $this->new_entry == true){
		$this->formvars['printversion'] = 'n';   # nur dazu da, damit die Links "zurück zur Suche" und "drucken" nicht erscheinen
?>
<table style="border: 1px solid grey" width="1020px" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;&nbsp;</td>
		<td colspan="3" width="100%">
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td>Gemeinde:</td>
					<td width="240px"><?  $j = 1;
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
					<td>
						<?  $j = 24;
							$attributes['name'][$j] = 'gemkz';
							for($e = 0; $e < count($attributes['enum_value'][$j][$k]); $e++){
								if($attributes['enum_value'][$j][$k][$e] == $layer['shape'][$k][$attributes['name'][$j]]){
									$auswahlfeld_output = $attributes['enum_output'][$j][$k][$e];
									$auswahlfeld_output_laenge=strlen($auswahlfeld_output)+1;
									break;
								}
							}
              echo '<input readonly id="'.$attributes['name'][$j].'_'.$k.'" style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px;" size="'.$auswahlfeld_output_laenge.'" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$auswahlfeld_output.'">';
              $auswahlfeld_output = '';
              $auswahlfeld_output_laenge = ''; 
					 ?>
					</td>
					<td rowspan="4"><span style="background-color: <? echo BG_GLEHEADER; ?>" class="titel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bebauungsplandaten&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
				</tr>
				<tr>
					<td>Amt:</td>
					<td>
					<?  $j = 26;
							$attributes['name'][$j] = 'amt';
							for($e = 0; $e < count($attributes['enum_value'][$j][$k]); $e++){
								if($attributes['enum_value'][$j][$k][$e] == $layer['shape'][$k][$attributes['name'][$j]]){
									$auswahlfeld_output = $attributes['enum_output'][$j][$k][$e];
									$auswahlfeld_output_laenge=strlen($auswahlfeld_output)+1;
									break;
								}
							}
              echo '<input readonly id="'.$attributes['name'][$j].'_'.$k.'" style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px;" size="'.$auswahlfeld_output_laenge.'" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$auswahlfeld_output.'">';
              $auswahlfeld_output = '';
              $auswahlfeld_output_laenge = ''; 
					 ?>
					</td>
					<td>Zentrale Orte:</td>
					<td>
						<?  $j = 27;
							$attributes['name'][$j] = 'zentrort';
							for($e = 0; $e < count($attributes['enum_value'][$j][$k]); $e++){
								if($attributes['enum_value'][$j][$k][$e] == $layer['shape'][$k][$attributes['name'][$j]]){
									$auswahlfeld_output = $attributes['enum_output'][$j][$k][$e];
									$auswahlfeld_output_laenge=strlen($auswahlfeld_output)+1;
									break;
								}
							}
              echo '<input readonly id="'.$attributes['name'][$j].'_'.$k.'" style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px;" size="'.$auswahlfeld_output_laenge.'" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$auswahlfeld_output.'">';
              $auswahlfeld_output = '';
              $auswahlfeld_output_laenge = ''; 
					 ?>
					</td>
				</tr>
				<tr>
					<td>Kreis:</td>
					<td>
					<?  $j = 28;
							$attributes['name'][$j] = 'kreis';
							for($e = 0; $e < count($attributes['enum_value'][$j][$k]); $e++){
								if($attributes['enum_value'][$j][$k][$e] == $layer['shape'][$k][$attributes['name'][$j]]){
									$auswahlfeld_output = $attributes['enum_output'][$j][$k][$e];
									break;
								}
							}
              echo '<input readonly id="'.$attributes['name'][$j].'_'.$k.'" style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px;" size="33" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$auswahlfeld_output.'">';
              $auswahlfeld_output = '';
              $auswahlfeld_output_laenge = ''; 
					 ?>
					</td>
					<td>Stadt-Umland-Raum:</td>
					<td>
					<?  $j = 29;
							$attributes['name'][$j] = 'sur';
							for($e = 0; $e < count($attributes['enum_value'][$j][$k]); $e++){
								if($attributes['enum_value'][$j][$k][$e] == $layer['shape'][$k][$attributes['name'][$j]]){
									$auswahlfeld_output = $attributes['enum_output'][$j][$k][$e];
									$auswahlfeld_output_laenge=strlen($auswahlfeld_output)+1;
									break;
								}
							}
              echo '<input readonly id="'.$attributes['name'][$j].'_'.$k.'" style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px;" size="'.$auswahlfeld_output_laenge.'" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$auswahlfeld_output.'">';
              $auswahlfeld_output = '';
              $auswahlfeld_output_laenge = ''; 
					 ?>
					</td>
				</tr>
				<tr>
					<td>Planungsregion:</td>
					<td>
						<?  $j = 30;
							$attributes['name'][$j] = 'pr';
							for($e = 0; $e < count($attributes['enum_value'][$j][$k]); $e++){
								if($attributes['enum_value'][$j][$k][$e] == $layer['shape'][$k][$attributes['name'][$j]]){
									$auswahlfeld_output = $attributes['enum_output'][$j][$k][$e];
									break;
								}
							}
              echo '<input readonly id="'.$attributes['name'][$j].'_'.$k.'" style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px;" size="33" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$auswahlfeld_output.'">';
              $auswahlfeld_output = '';
              $auswahlfeld_output_laenge = ''; 
					 ?>
					</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;&nbsp;&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
  <tr>
  	<td>&nbsp;&nbsp;&nbsp;</td>
  	<td valign="top" width="800px">
  		<table width="100%" style="background-color:<? echo BG_GLEATTRIBUTE; ?>;border: 2px solid <? echo BG_GLEHEADER; ?>" cellspacing="0" cellpadding="2">
  			<tr style="background-color:<? echo BG_GLEHEADER; ?>;">
  				<td colspan="4" align="center"><b>Plandaten</b></td>
  			</tr>
  			<tr>
  				<td width="130px">Planbezeichnung:</td>
  				<?
  					$this->qlayerset[$i]['attributes']['name'][$j] = 'bezeichnung'; 
  					$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|'; 
  				?>
					<td colspan="3" width="530px" ><input style="width: 443px" <? echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  			</tr>
  			<tr>
  				<td>Plannummer:</td>
  				<?
  					$this->qlayerset[$i]['attributes']['name'][$j] = 'pl_nr';
  					$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  				?>
					<td width="190px"><input <? echo ' type="text" style="width: 170px" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
					<td width="75px">Aktenzeichen</td>
					<?
  					$this->qlayerset[$i]['attributes']['name'][$j] = 'aktenzeichen';
  					$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  				?>
					<td><input <? echo ' type="text" style="width: 170px" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  			</tr>
  			<tr>
  				<td>Planart:</td>
  				<?
  					$j = 2;
  					$this->qlayerset[$i]['attributes']['name'][$j] = 'art';
  					$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  				?>
					<td><?  echo '<select onchange="update_planart()" title="'.$this->qlayerset[$i]['attributes']['alias'][$j].'" style="width: 170px;font-size: '.$this->user->rolle->fontsize_gle.'px"';
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
					<td>Plan-ID:</td>
  				<?
  					$this->qlayerset[$i]['attributes']['name'][$j] = 'plan_id';
  					$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  				?>
					<td><input readonly="true" <? echo ' style="width: 170px" type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  			</tr>
  			<tr>
  				<td>ROK-Nr.</td>
  				<?
  					$this->qlayerset[$i]['attributes']['name'][$j] = 'lfd_rok_nr';
  					$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  				?>
					<td><input <? echo ' type="text" style="width: 170px" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
					<td>aktuell:</td>
  				<?
  					$j = 7; 
  					$this->qlayerset[$i]['attributes']['name'][$j] = 'aktuell';
  					$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  				?>
					<td><?  echo '<select title="'.$this->qlayerset[$i]['attributes']['alias'][$j].'" style="width: 170px;font-size: '.$this->user->rolle->fontsize_gle.'px"';
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
					<td colspan="1"><input style="width: 170px" <? echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
					<!-- td colspan="2">Kapazität</td-->
  			</tr>
  			<tr>
  				<td>Geltungsbereich [ha]:</td>
  				<?
  					$this->qlayerset[$i]['attributes']['name'][$j] = 'geltungsbereich';
  					$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
  				?>
					<td><input <? echo ' type="text" style="width: 170px" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
					<td colspan="2">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr id="kap" <? if($layer['shape'][$k]['art'] != 'Innenbereichssatzung' && $layer['shape'][$k]['art'] != 'Außenbereichssatzung')echo ' style="display: none"'; ?>>
								<td>Gemeindeziel:&nbsp;</td>
			  				<?
			  					$this->qlayerset[$i]['attributes']['name'][$j] = 'kap_gemziel';
			  					$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
			  				?>
								<td width="60px"><input <? echo ' type="text" style="width: 40px" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
								<td>nach Stell.:&nbsp;</td>
			  				<?
			  					$this->qlayerset[$i]['attributes']['name'][$j] = 'kap_nachstell';
			  					$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
			  				?>
								<td><input <? echo ' type="text" style="width: 40px" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
							</tr>
						</table>
					</td>
  			</tr>
  		</table>
  		<br>
  		<table width="100%" style="background-color:<? echo BG_GLEATTRIBUTE; ?>;border: 2px solid <? echo BG_GLEHEADER; ?>" cellspacing="0" cellpadding="2">
  			<tr>
  				<td colspan="2" align="center" bgcolor="<? echo BG_GLEHEADER; ?>"><b>Gebiete, Flächen und Kapazitäten</b></td>
  			</tr>
  			<tr>
  				<td width="50%" valign="top">
  					<table width="100%" border="0" style="border: 1px solid grey;border-collapse: collapse;">
  						<tr>
  							<td style="border: 1px solid grey;" align="center" valign="top"><b>Gebiete</b></td>
  						</tr>
  						<tr>
  							<td>
  								<?
  									$j = 21;
										echo '<div id="'.$attributes['name'][$j].'_'.$k.'"></div>';
										$no_query = false;
										for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
											if($layer['shape'][$k][$attributes['subform_pkeys'][$j][$p]] == ''){
												$no_query = true;
											}
										}
										if($this->new_entry != true AND $no_query != true){
											echo '<script type="text/javascript">
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
													echo '&selected_layer_id='.$attributes['subform_layer_id'][$j].'&embedded=true&fromobject=subform'.$layer['Layer_ID'].'_'.$k.'_'.$j.'&targetobject='.$attributes['name'][$j].'_'.$k.'&targetlayer_id='.$layer['Layer_ID'].'&targetattribute='.$attributes['name'][$j].'\', new Array(document.getElementById(\'subform'.$layer['Layer_ID'].'_'.$k.'_'.$j.'\')), \'sethtml\');clearsubforms();">&nbsp;+&nbsp;</a></td></tr></table>';
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
  								?>
  							</td>
  						</tr>
  					</table>
  				</td>
  				<td width="50%" valign="top">
  					<table width="100%" border="0" style="border: 1px solid grey;border-collapse: collapse;">
  						<tr>
  							<td style="border: 1px solid grey;" align="center" valign="top"><b>Sondergebiete</b></td>
  						</tr>
  						<tr>
  							<td>
  								<?
  									$j = 22;
										echo '<div id="'.$attributes['name'][$j].'_'.$k.'"></div>';
										$no_query = false;
										for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
											if($layer['shape'][$k][$attributes['subform_pkeys'][$j][$p]] == ''){
												$no_query = true;
											}
										}
										if($this->new_entry != true AND $no_query != true){
											echo '<script type="text/javascript">
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
													echo '&selected_layer_id='.$attributes['subform_layer_id'][$j].'&embedded=true&fromobject=subform'.$layer['Layer_ID'].'_'.$k.'_'.$j.'&targetobject='.$attributes['name'][$j].'_'.$k.'&targetlayer_id='.$layer['Layer_ID'].'&targetattribute='.$attributes['name'][$j].'\', new Array(document.getElementById(\'subform'.$layer['Layer_ID'].'_'.$k.'_'.$j.'\')), \'sethtml\');clearsubforms();">&nbsp;+</a></td></tr></table>';
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
  								?>
  							</td>
  						</tr>
  					</table>
  				</td>
  			</tr>
  		</table>
  	</td>
  	<td>&nbsp;&nbsp;&nbsp;</td>
  	<td width="320px" valign="top">
  		<table width="100%" style="background-color:<? echo BG_GLEATTRIBUTE; ?>;border: 2px solid <? echo BG_GLEHEADER; ?>" cellspacing="0" cellpadding="2">
  			<tr bgcolor="<? echo BG_GLEHEADER; ?>">
  				<td colspan="4" align="center"><b>Zeitbezug</b></td>
  			</tr>
  			<tr>
					<td>Datum Eingang:</td>
					<?
						$j = 12;
						$this->qlayerset[$i]['attributes']['name'][$j] = 'datumeing';
						$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
					?>
					<td align="right"><input size="10" <? echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?>
					<?
						echo '<a href="javascript:;" title=" (TT.MM.JJJJ) '.$attributes['tooltip'][$j].'" ';
						echo '><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a><div id="calendar"><a name="calendar_'.$attributes['name'][$j].'_'.$k.'"></div>&nbsp;';
					?>
					</td>
				</tr>
				<tr>
					<td>Datum Zustimmung:</td>
					<?
						$j = 13;
						$this->qlayerset[$i]['attributes']['name'][$j] = 'datumzust';
						$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
					?>
					<td align="right"><input size="10" <? echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?>
					<?
						echo '<a href="javascript:;" title=" (TT.MM.JJJJ) '.$attributes['tooltip'][$j].'" ';
						echo '><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a><div id="calendar"><a name="calendar_'.$attributes['name'][$j].'_'.$k.'"></div>&nbsp;';
					?>
					</td>
				</tr>
				<tr>
					<td>Datum Ablehnung:</td>
					<?
						$j = 14;
						$this->qlayerset[$i]['attributes']['name'][$j] = 'datumabl';
						$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
					?>
					<td align="right"><input size="10" <? echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?>
					<?
						echo '<a href="javascript:;" title=" (TT.MM.JJJJ) '.$attributes['tooltip'][$j].'" ';
						echo '><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a><div id="calendar"><a name="calendar_'.$attributes['name'][$j].'_'.$k.'"></div>&nbsp;';
					?>
					</td>
				</tr>
				<tr>
					<td>Datum Genehmigung:</td>
					<?
						$j = 15;
						$this->qlayerset[$i]['attributes']['name'][$j] = 'datumgenehm';
						$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
					?>
					<td align="right"><input size="10" <? echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?>
					<?
						echo '<a href="javascript:;" title=" (TT.MM.JJJJ) '.$attributes['tooltip'][$j].'" ';
						echo '><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a><div id="calendar"><a name="calendar_'.$attributes['name'][$j].'_'.$k.'"></div>&nbsp;';
					?>
					</td>
				</tr>
				<tr>
					<td>Datum Bekanntmachung:</td>
					<?
						$j = 16;
						$this->qlayerset[$i]['attributes']['name'][$j] = 'datumbeka';
						$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
					?>
					<td align="right"><input size="10" <? echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?>
					<?
						echo '<a href="javascript:;" title=" (TT.MM.JJJJ) '.$attributes['tooltip'][$j].'" ';
						echo '><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a><div id="calendar"><a name="calendar_'.$attributes['name'][$j].'_'.$k.'"></div>&nbsp;';
					?>
					</td>
				</tr>
				<tr>
					<td>Datum Aufhebung:</td>
					<?
						$j = 17;
						$this->qlayerset[$i]['attributes']['name'][$j] = 'datumaufh';
						$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
					?>
					<td align="right"><input size="10" <? echo ' type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?>
					<?
						echo '<a href="javascript:;" title=" (TT.MM.JJJJ) '.$attributes['tooltip'][$j].'" ';
						echo '><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a><div id="calendar"><a name="calendar_'.$attributes['name'][$j].'_'.$k.'"></div>&nbsp;';
					?>
					</td>
				</tr>
  		</table>
  		<br>
  		<table style="background-color:<? echo BG_GLEATTRIBUTE; ?>;border: 2px solid <? echo BG_GLEHEADER; ?>" cellspacing="0" cellpadding="2">
  			<tr>
					<td>Maßgaben:
					<?
						$this->qlayerset[$i]['attributes']['name'][$j] = 'erteilteaufl';
						$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
					 echo '<textarea style="width: 300px;font-size: '.$this->user->rolle->fontsize_gle.'px" style="font-family: Arial, Verdana, Helvetica, sans-serif;"';
										echo ' rows="4" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'">'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'</textarea>'; ?>
					</td>
				</tr>
				<tr>
					<td>Hinweise:
					<?
						$this->qlayerset[$i]['attributes']['name'][$j] = 'ert_hinweis';
						$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
					 echo '<textarea style="width: 300px;font-size: '.$this->user->rolle->fontsize_gle.'px" style="font-family: Arial, Verdana, Helvetica, sans-serif;"';
										echo ' rows="3" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'">'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'</textarea>'; ?>
					</td>
				</tr>
				<tr>
					<td>Bemerkungen:
					<?
						$this->qlayerset[$i]['attributes']['name'][$j] = 'ert_bemerkungen';
						$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
					 echo '<textarea style="width: 300px;font-size: '.$this->user->rolle->fontsize_gle.'px" style="font-family: Arial, Verdana, Helvetica, sans-serif;"';
										echo ' rows="3" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'">'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'</textarea>'; ?>
					</td>
				</tr>
			</table>
  	</td>
  	<td>&nbsp;&nbsp;&nbsp;</td> 
  </tr>
  <tr>
		<td colspan="5">&nbsp;</td>
	</tr>
</table>
</td>
<td>&nbsp;&nbsp;</td>
</tr>
</table>
<br>
<? if($this->new_entry != true){ ?>
<table width="100%" border="0" cellpadding="2" cellspacing="0">
  <tr align="center"> 
    <td>
    	<input type="button" class="button" name="savebutton" value="<? echo $strSave; ?>" onclick="save();">&nbsp;&nbsp;
    	<input type="button" class="button" name="savebutton2" value="Als neuen Datensatz speichern" onclick="save_as_new_dataset();">&nbsp;&nbsp;
    	<input type="button" class="button" name="deletebutton" value="Löschen" onclick="delete_dataset(<? echo $this->qlayerset[$i]['shape'][$k]['b_plan_stammdaten_oid']; ?>);">&nbsp;&nbsp;
    	<input type="button" class="button" name="mapbutton" value="In die Karte" onclick="zoomto('<? echo $this->qlayerset[$i]['shape'][$k]['lfd_rok_nr']; ?>');">
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
  			<td><b>Plan-Nr:</b></td>
  			<td><b>aktuell:</b></td>
  			<td></td>
  		</tr>
	<?	for($k=0;$k<$anzObj;$k++) { ?>
				<tr>
					<? $this->qlayerset[$i]['attributes']['name'][$j] = 'gemeinde'; ?>
					<td valign="top"><a href="javascript:show_details(<? echo $this->qlayerset[$i]['shape'][$k]['b_plan_stammdaten_oid']; ?>);"><? echo htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]); ?></a></td>
					<? $this->qlayerset[$i]['attributes']['name'][$j] = 'lfd_rok_nr'; ?>
					<td valign="top"><a href="javascript:show_details(<? echo $this->qlayerset[$i]['shape'][$k]['b_plan_stammdaten_oid']; ?>);"><? echo htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]); ?></a></td>
					<? $this->qlayerset[$i]['attributes']['name'][$j] = 'bezeichnung'; ?>
					<td valign="top"><a href="javascript:show_details(<? echo $this->qlayerset[$i]['shape'][$k]['b_plan_stammdaten_oid']; ?>);"><? echo htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]); ?></a></td>
					<? $this->qlayerset[$i]['attributes']['name'][$j] = 'pl_nr'; ?>
					<td valign="top"><a href="javascript:show_details(<? echo $this->qlayerset[$i]['shape'][$k]['b_plan_stammdaten_oid']; ?>);"><? echo htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]); ?></a></td>
					<? $this->qlayerset[$i]['attributes']['name'][$j] = 'aktuell'; ?>
					<td valign="top"><a href="javascript:show_details(<? echo $this->qlayerset[$i]['shape'][$k]['b_plan_stammdaten_oid']; ?>);"><? echo htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]); ?></a></td>
					<td valign="top"><a href="javascript:delete_dataset(<? echo $this->qlayerset[$i]['shape'][$k]['b_plan_stammdaten_oid']; ?>);">löschen</a></td>
  			</tr>
	<?	} ?>
		</table>
		</td>
		<td>&nbsp;</td>
		</tr>
		</table>
		<input type="hidden" name="value_b_plan_stammdaten_oid" value="">
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
<input type="hidden" name="roknr" value="">
<? if($this->new_entry != true){ ?>
<input type="hidden" name="selected_layer_id" value="">
<? } ?>


