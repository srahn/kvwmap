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
   td {font-size: 15px}
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


show_details = function(oid){
	currentform.go.value = 'Layer-Suche_Suchen';
	currentform.search.value = 'true';
	currentform.selected_layer_id.value = <? echo $this->qlayerset[$i]['layer_id'] ?>;
	currentform.details.value = 'true';
	currentform.offset_<? echo $this->qlayerset[$i]['layer_id']; ?>.value = '';
	currentform.value_f_plan_stammdaten_oid.value = oid;
	overlay_submit(currentform, false);
}

go_back = function(){
	currentform.details.value = '';
	currentform.go.value = 'Layer-Suche_Suchen';
	currentform.selected_layer_id.value = <? echo $this->qlayerset[$i]['layer_id'] ?>;
	currentform.value_f_plan_stammdaten_oid.value = '';
	currentform.offset_<? echo $this->qlayerset[$i]['layer_id']; ?>.value = currentform._offset_<? echo $this->qlayerset[$i]['layer_id']; ?>.value;
	overlay_submit(currentform, false);
}

copy_dataset = function(plan_id){
	currentform.plan_id.value = plan_id;
	currentform.go.value = 'copy_fplan';
	overlay_submit(currentform, false);
}

update_fplan_from_rok = function(plan_id){
	really = confirm('Wollen Sie die Flächen der Gebiete und Sondergebiete wirklich mit den ROK-Flächen überschreiben?');
	if(really){
		currentform.plan_id.value = plan_id;
		currentform.go.value = 'update_fplan_from_rok';
		overlay_submit(currentform, false);
	}
}

delete_dataset = function(plan_id){
	really = confirm('Wollen Sie diesen Datensatz wirklich löschen?');
	if(really){
		if((currentform.details.value != 'true' && currentform.value_f_plan_stammdaten_oid.value == '') || (currentform.details.value == 'true' && currentform.value_f_plan_stammdaten_oid.value != '')){		// Trefferliste vorhanden -> wieder zurück zur Trefferliste
			currentform.details.value = '';
			currentform.selected_layer_id.value = <? echo $this->qlayerset[$i]['layer_id'] ?>;
			currentform.value_f_plan_stammdaten_oid.value = '';
			currentform.offset_<? echo $this->qlayerset[$i]['layer_id']; ?>.value = currentform._offset_<? echo $this->qlayerset[$i]['layer_id']; ?>.value;
		}
		currentform.plan_id.value = plan_id;
		currentform.go.value = 'delete_fplan';
		overlay_submit(currentform, false);
	}
}

zoom_to = function(roknr, art){
	currentform.roknr.value = roknr;
	currentform.art.value = art;
	currentform.go.value = 'zoomtobplan';
	currentform.submit();
}

set_changed_flag = function(flag){
}

update_gebietstyp = function(){
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
	$dataset = $layer['shape'][$k];
?>
<input type="hidden" value="" id="changed_<? echo $layer['layer_id']; ?>" name="changed_<? echo $layer['layer_id']; ?>">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>&nbsp;&nbsp;</td>
		<td>  
	<? /*for($a = 0; $a < count($this->qlayerset[$i]['attributes']['name']); $a++){
		echo $this->qlayerset[$i]['attributes']['name'][$a].'  '.$a.'<br>';
	}*/
	if($this->formvars['value_f_plan_stammdaten_oid'] != '' OR $this->new_entry == true OR $this->formvars['details'] == 'true'){
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
					<td width="240px"><?  $j = $this->qlayerset[$i]['attributes']['indizes']['gkz'];
			  					echo attribute_value($this, $layer, NULL, $j, $k, NULL, $size2, $select_width2);
									$this->form_field_names .= $layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|'; 
									$j = 0;?>
					</td>
					<td>Gemeindekennzahl:</td>
					<td>
						<?  $j = $this->qlayerset[$i]['attributes']['indizes']['gemkz'];
							echo attribute_value($this, $layer, NULL, $j, $k, NULL, $size2, $select_width2);  
					 ?>
					</td>
					<td rowspan="4"><span style="background-color: <? echo BG_GLEHEADER; ?>" class="titel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Flächennutzungsplandaten&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
				</tr>
				<tr>
					<td>Amt:</td>
					<td>
					<?  $j = $this->qlayerset[$i]['attributes']['indizes']['amt'];
							echo attribute_value($this, $layer, NULL, $j, $k, NULL, $size2, $select_width2);  
					 ?>
					</td>
					<td>Zentrale Orte:</td>
					<td>
						<?  $j = $this->qlayerset[$i]['attributes']['indizes']['zentrort'];
							echo attribute_value($this, $layer, NULL, $j, $k, NULL, $size2, $select_width2);  
					 ?>
					</td>
				</tr>
				<tr>
					<td>Kreis:</td>
					<td>
					<?  $j = $this->qlayerset[$i]['attributes']['indizes']['kreis'];
							echo attribute_value($this, $layer, NULL, $j, $k, NULL, $size2, $select_width2);  
					 ?>
					</td>
					<td>Stadt-Umland-Raum:</td>
					<td>
					<?  $j = $this->qlayerset[$i]['attributes']['indizes']['sur'];
							echo attribute_value($this, $layer, NULL, $j, $k, NULL, $size2, $select_width2);  
					 ?>
					</td>
				</tr>
				<tr>
					<td>Planungsregion:</td>
					<td>
						<?  $j = $this->qlayerset[$i]['attributes']['indizes']['pr'];
							echo attribute_value($this, $layer, NULL, $j, $k, NULL, $size2, $select_width2);  
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
  					$j = $this->qlayerset[$i]['attributes']['indizes']['bezeichnung'];
  					$this->form_field_names .= $layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|';  
  				?>
					<td colspan="3" width="530px" ><input style="width: 470px" <? echo ' type="text" name="'.$layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  			</tr>
  			<tr>
  				<td>Plannummer:</td>
  				<?
  					$j = $this->qlayerset[$i]['attributes']['indizes']['pl_nr'];
  					$this->form_field_names .= $layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|'; 
  				?>
					<td width="190px"><input <? echo ' type="text" style="width: 170px" name="'.$layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
					<td width="75px">Aktenzeichen</td>
					<?
						$j = $this->qlayerset[$i]['attributes']['indizes']['aktenzeichen'];
  					$this->form_field_names .= $layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|'; 
  				?>
					<td><input <? echo ' type="text" style="width: 170px" name="'.$layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  			</tr>
  			<tr>
  				<td>Planart:</td>
  				<?
  					$j = $this->qlayerset[$i]['attributes']['indizes']['art'];
  					$this->form_field_names .= $layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|'; 
  				?>
					<td><?  
						echo attribute_value($this, $layer, NULL, $j, $k, NULL, $size2, $select_width2);
					?>
					</td>
					<td>Plan-ID:</td>
  				<?
  					$j = $this->qlayerset[$i]['attributes']['indizes']['plan_id'];
  					$this->form_field_names .= $layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|'; 
  				?>
					<td><input readonly="true" <? echo ' style="width: 170px" type="text" name="'.$layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
  			</tr>
  			<tr>
  				<td>ROK-Nr.</td>
  				<?
  					$j = $this->qlayerset[$i]['attributes']['indizes']['lfd_rok_nr'];
  					$this->form_field_names .= $layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|'; 
  				?>
					<td><input <? echo ' type="text" style="width: 170px" name="'.$layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
					<td>Verfahrensstand:</td>
  				<?
  					$j = $this->qlayerset[$i]['attributes']['indizes']['aktuell'];
  					$this->form_field_names .= $layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|'; 
  				?>
					<td><?  
						echo attribute_value($this, $layer, NULL, $j, $k, NULL, $size2, $select_width2);
					?>
					</td>					
  			</tr>
  			<tr>
  				<td>Gemeindename alt:</td>
  				<?
  					$j = $this->qlayerset[$i]['attributes']['indizes']['gemeinde_alt'];
  					$this->form_field_names .= $layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|'; 
  				?>
					<td colspan="1"><input style="width: 170px" <? echo ' type="text" name="'.$layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?></td>
					<!-- td colspan="2">Kapazität</td-->
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
  									$j = $this->qlayerset[$i]['attributes']['indizes']['gebiete'];
										echo attribute_value($this, $layer, NULL, $j, $k, NULL, $size2, $select_width2);
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
  									$j = $this->qlayerset[$i]['attributes']['indizes']['sondergebiete'];
										echo attribute_value($this, $layer, NULL, $j, $k, NULL, $size2, $select_width2);
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
						$j = $this->qlayerset[$i]['attributes']['indizes']['datumeing'];
						$this->form_field_names .= $layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|'; 
					?>
					<td align="right"><input size="10" <? echo ' type="text" name="'.$layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?>
					<?
						echo '<a href="javascript:;" title=" (TT.MM.JJJJ) '.$attributes['tooltip'][$j].'" ';
						echo '><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a><div id="calendar"><a name="calendar_'.$attributes['name'][$j].'_'.$k.'"></div>';
					?>
					</td>
				</tr>
				<tr>
					<td>Datum Zustimmung:</td>
					<?
						$j = $this->qlayerset[$i]['attributes']['indizes']['datumzust'];
						$this->form_field_names .= $layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|'; 
					?>
					<td align="right"><input size="10" <? echo ' type="text" name="'.$layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?>
					<?
						echo '<a href="javascript:;" title=" (TT.MM.JJJJ) '.$attributes['tooltip'][$j].'" ';
						echo '><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a><div id="calendar"><a name="calendar_'.$attributes['name'][$j].'_'.$k.'"></div>';
					?>
					</td>
				</tr>
				<tr>
					<td>Datum Ablehnung:</td>
					<?
						$j = $this->qlayerset[$i]['attributes']['indizes']['datumabl'];
						$this->form_field_names .= $layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|'; 
					?>
					<td align="right"><input size="10" <? echo ' type="text" name="'.$layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?>
					<?
						echo '<a href="javascript:;" title=" (TT.MM.JJJJ) '.$attributes['tooltip'][$j].'" ';
						echo '><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a><div id="calendar"><a name="calendar_'.$attributes['name'][$j].'_'.$k.'"></div>';
					?>
					</td>
				</tr>
				<tr>
					<td>Datum Genehmigung:</td>
					<?
						$j = $this->qlayerset[$i]['attributes']['indizes']['datumgenehm'];
						$this->form_field_names .= $layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|'; 
					?>
					<td align="right"><input size="10" <? echo ' type="text" name="'.$layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?>
					<?
						echo '<a href="javascript:;" title=" (TT.MM.JJJJ) '.$attributes['tooltip'][$j].'" ';
						echo '><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a><div id="calendar"><a name="calendar_'.$attributes['name'][$j].'_'.$k.'"></div>';
					?>
					</td>
				</tr>
				<tr>
					<td>Datum Bekanntmachung:</td>
					<?
						$j = $this->qlayerset[$i]['attributes']['indizes']['datumbeka'];
						$this->form_field_names .= $layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|'; 
					?>
					<td align="right"><input size="10" <? echo ' type="text" name="'.$layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?>
					<?
						echo '<a href="javascript:;" title=" (TT.MM.JJJJ) '.$attributes['tooltip'][$j].'" ';
						echo '><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a><div id="calendar"><a name="calendar_'.$attributes['name'][$j].'_'.$k.'"></div>';
					?>
					</td>
				</tr>
				<tr>
					<td>Datum Aufhebung:</td>
					<?
						$j = $this->qlayerset[$i]['attributes']['indizes']['datumaufh'];
						$this->form_field_names .= $layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|'; 
					?>
					<td align="right"><input size="10" <? echo ' type="text" name="'.$layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">'; ?>
					<?
						echo '<a href="javascript:;" title=" (TT.MM.JJJJ) '.$attributes['tooltip'][$j].'" ';
						echo '><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a><div id="calendar"><a name="calendar_'.$attributes['name'][$j].'_'.$k.'"></div>';
					?>
					</td>
				</tr>
  		</table>
  		<br>
  		<table style="background-color:<? echo BG_GLEATTRIBUTE; ?>;border: 2px solid <? echo BG_GLEHEADER; ?>" cellspacing="0" cellpadding="2">
  			<tr>
					<td>Maßgaben:
					<?
						$j = $this->qlayerset[$i]['attributes']['indizes']['erteilteaufl'];
						$this->form_field_names .= $layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|'; 
					 echo '<textarea style="width: 300px" style="font-family: Arial, Verdana, Helvetica, sans-serif;"';
										echo ' rows="4" name="'.$layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'">'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'</textarea>'; ?>
					</td>
				</tr>
				<tr>
					<td>Hinweise:
					<?
						$j = $this->qlayerset[$i]['attributes']['indizes']['ert_hinweis'];
						$this->form_field_names .= $layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|'; 
					 echo '<textarea style="width: 300px" style="font-family: Arial, Verdana, Helvetica, sans-serif;"';
										echo ' rows="3" name="'.$layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'">'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'</textarea>'; ?>
					</td>
				</tr>
				<tr>
					<td>Bemerkungen:
					<?
						$j = $this->qlayerset[$i]['attributes']['indizes']['ert_bemerkungen'];
						$this->form_field_names .= $layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|'; 
					 echo '<textarea style="width: 300px" style="font-family: Arial, Verdana, Helvetica, sans-serif;"';
										echo ' rows="3" name="'.$layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'">'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'</textarea>'; ?>
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
    	<input type="button" name="savebutton" value="<? echo $strSave; ?>" onclick="save();">&nbsp;&nbsp;
    	<input type="button" name="savebutton2" value="Datensatz kopieren" onclick="copy_dataset(<? echo $this->qlayerset[$i]['shape'][$k]['plan_id']; ?>);">&nbsp;&nbsp;
    	<input type="button" name="deletebutton" value="Löschen" onclick="delete_dataset(<? echo $this->qlayerset[$i]['shape'][$k]['plan_id']; ?>);">&nbsp;&nbsp;
			<input type="button" name="rokbutton" value="Flächen aus ROK holen" onclick="update_fplan_from_rok(<? echo $this->qlayerset[$i]['shape'][$k]['plan_id']; ?>);">&nbsp;&nbsp;
    	<!--input type="button" name="mapbutton" value="In die Karte" onclick="zoomto('<? echo $this->qlayerset[$i]['shape'][$k]['lfd_rok_nr']; ?>', '<? echo $this->qlayerset[$i]['shape'][$k]['art']; ?>');"-->
    </td>
  </tr>
	<tr>
		<td height="30" valign="bottom" align="center" colspan="5" id="loader" style="display:none"><img id="loaderimg" src="graphics/ajax-loader.gif"></td>
	</tr>
</table>
<? } ?>
<? if($this->new_entry != true AND $this->formvars['details'] == 'true'){ ?>
		<br>
		<a href="javascript:go_back();">zurück zur Trefferliste</a>
		<br>
<? } ?>
<br>
<?php   
		#  zusätzliches Hiddenfeld zum Merken des Offsets der Trefferliste, solange man in der Detailansicht ist 
		echo '<input name="_offset_'.$this->qlayerset[$i]['layer_id'].'" type="hidden" value="'.$this->formvars['_offset_'.$this->qlayerset[$i]['layer_id']].'">';
		<input type="hidden" name="value_f_plan_stammdaten_oid" value="">';
	}				# details == true
	else{ ?>
		<table border="1" cellpadding="2" style="border-collapse: collapse;">
			<tr>
				<td><b>Gemeinde:</b></td>
				<td width="300px"><b>Planart:</b></td>
				<td><b>Plan-Nr:</b></td>
				<td width="300px"><b>Planbezeichnung:</b></td>
				<td><b>ROK-Nr:</b></td>
  			<td><b>Verfahrensstand:</b></td>
  			<td></td>
  		</tr>
	<?	for($k=0;$k<$anzObj;$k++) { ?>
				<tr <?
					$j = 7;$this->qlayerset[$i]['attributes']['name'][$j] = 'aktuell';
					if($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]] == 1){
						echo 'style="font-weight:bold;background-color:lightsteelblue"';
					} 
					?>><?
					$j = 25;$this->qlayerset[$i]['attributes']['name'][$j] = 'gemeinde'; ?>
					<td valign="top"><a href="javascript:show_details(<? echo $this->qlayerset[$i]['shape'][$k]['f_plan_stammdaten_oid']; ?>);"><? echo htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]); ?></a></td>
					<? $j = 2;$this->qlayerset[$i]['attributes']['name'][$j] = 'art'; ?>
					<td valign="top"><a href="javascript:show_details(<? echo $this->qlayerset[$i]['shape'][$k]['f_plan_stammdaten_oid']; ?>);"><? echo htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]); ?></a></td>
					<? $j = 3;$this->qlayerset[$i]['attributes']['name'][$j] = 'pl_nr'; ?>
					<td valign="top"><a href="javascript:show_details(<? echo $this->qlayerset[$i]['shape'][$k]['f_plan_stammdaten_oid']; ?>);"><? echo htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]); ?></a></td>
					<? $j = 6;$this->qlayerset[$i]['attributes']['name'][$j] = 'bezeichnung'; ?>
					<td valign="top"><a href="javascript:show_details(<? echo $this->qlayerset[$i]['shape'][$k]['f_plan_stammdaten_oid']; ?>);"><? echo htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]); ?></a></td>
					<? $j = 8;$this->qlayerset[$i]['attributes']['name'][$j] = 'lfd_rok_nr'; ?>
					<td valign="top"><a href="javascript:show_details(<? echo $this->qlayerset[$i]['shape'][$k]['f_plan_stammdaten_oid']; ?>);"><? echo htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]); ?></a></td>
					<? $j = 7;$this->qlayerset[$i]['attributes']['name'][$j] = 'aktuell'; ?>
					<td valign="top"><a href="javascript:show_details(<? echo $this->qlayerset[$i]['shape'][$k]['f_plan_stammdaten_oid']; ?>);"><? echo htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]); ?></a></td>
					<td valign="top"><a href="javascript:delete_dataset(<? echo $this->qlayerset[$i]['shape'][$k]['plan_id']; ?>);">löschen</a></td>
  			</tr>
	<?	} ?>
		</table>
		</td>
		<td>&nbsp;</td>
		</tr>
		</table>
		<input type="hidden" name="value_f_plan_stammdaten_oid" value="">
<?	echo '<input name="_offset_'.$this->qlayerset[$i]['layer_id'].'" type="hidden" value="'.$this->formvars['offset_'.$this->qlayerset[$i]['layer_id']].'">';
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

<input type="hidden" value="1" name="changed_<? echo $layer['layer_id'].'_'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][0]].'_oid']; ?>">
<input name="search" type="hidden" value="true">
<input type="hidden" name="details" value="<? echo $this->formvars['details']; ?>">
<input type="hidden" name="plan_id" value="">
<input type="hidden" name="roknr" value="">
<input type="hidden" name="art" value="">

<?
	for($j = 0; $j < count_or_0($layer['attributes']['name']); $j++){
		$value = $this->formvars[$prefix.'value_'.$layer['attributes']['name'][$j]];
		if (!is_array($value)) {
			$value = [$value];
		}
		foreach($value as $val) {
			echo '<input name="'.$prefix.'value_'.$layer['attributes']['name'][$j].'" type="hidden" value="'.$val.'">';
		}
		echo '
			<input name="'.$prefix.'value2_'.$layer['attributes']['name'][$j].'" type="hidden" value="'.$this->formvars[$prefix.'value2_'.$layer['attributes']['name'][$j]].'">
			<input name="'.$prefix.'operator_'.$layer['attributes']['name'][$j].'" type="hidden" value="'.$this->formvars[$prefix.'operator_'.$layer['attributes']['name'][$j]].'">
		';
	}
?>

<? if($this->new_entry != true){ ?>
<input type="hidden" name="selected_layer_id" value="<? echo $this->qlayerset[$i]['Layer_ID']; ?>">
<? } ?>


