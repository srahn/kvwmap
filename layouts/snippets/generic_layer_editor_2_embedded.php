<?php
 # 2008-09-30 sr
  include(LAYOUTPATH.'languages/generic_layer_editor_2_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
 ?>
<script type="text/javascript">
<!--

function update_require_attribute(attributes, k,layer_id, value){
	// attributes ist eine Liste von zu aktualisierenden Attribut, k die Nummer des Datensatzes und value der ausgewaehlte Wert
	attribute = attributes.split(',');
	for(i = 0; i < attribute.length; i++){
		ahah("<? echo URL.APPLVERSION; ?>index.php", "go=get_select_list&layer_id="+layer_id+"&attribute="+attribute[i]+"&value="+value, new Array(document.getElementById(attribute[i]+'_'+k)), 'sethtml');
	}
}

//-->
</script>


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
<?
	$checkbox_names = '';
	$columnname = '';
	$tablename = '';
	$geomtype = '';
	$dimension = '';
	$privileg = '';
	for ($k=0;$k<$anzObj;$k++) {
		$checkbox_name .= 'check;'.$this->qlayerset[$i]['attributes']['table_alias_name'][$this->qlayerset[$i]['attributes']['name'][0]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][0]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][0]].'_oid'];
?>
	<tr>
		<td>
			<table class="tgle" border="1">
			  <tbody class="gle">
<?		$trans_oid = explode('|', $this->qlayerset[$i]['shape'][$k]['lock']);
			if($this->qlayerset[$i]['shape'][$k]['lock'] == 'bereits übertragen' OR $trans_oid[1] != '' AND $this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][0]].'_oid'] == $trans_oid[1]){
				echo '<tr><td colspan="2" align="center"><span class="red">Dieser Datensatz wurde bereits übertragen und kann nicht bearbeitet werden.</span></td></tr>';
				$lock[$k] = true;
			}
			for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
				if($this->new_entry == true AND $this->qlayerset[$i]['attributes']['default'][$j] != ''){		# Default-Werte setzen
					$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]] = $this->qlayerset[$i]['attributes']['default'][$j];
				}
				if($this->qlayerset[$i]['attributes']['invisible'][$this->qlayerset[$i]['attributes']['name'][$j]] != 'true'  AND $this->qlayerset[$i]['attributes']['name'][$j] != 'lock'){
				if($this->qlayerset[$i]['attributes']['privileg'][$j] == '0' AND $this->qlayerset[$i]['attributes']['form_element_type'][$j] == 'Auswahlfeld' OR $this->qlayerset[$i]['attributes']['type'][$j] == 'not_saveable'){				# entweder ist es ein nicht speicherbares Attribut oder ein nur lesbares Auswahlfeld, dann ist es auch nicht speicherbar
					$this->qlayerset[$i]['attributes']['form_element_type'][$j] .= '_not_saveable';
				}
?>
				<tr>
<?					if($this->qlayerset[$i]['attributes']['type'][$j] != 'geometry'){
							echo '<td  valign="top" bgcolor="'.BG_GLEATTRIBUTE.'">';
							if($this->qlayerset[$i]['attributes']['privileg'][$j] == '0' OR $lock[$k]){
								if($this->qlayerset[$i]['attributes']['alias'][$j] == ''){
									$this->qlayerset[$i]['attributes']['alias'][$j] = $this->qlayerset[$i]['attributes']['name'][$j];
								}
								echo '<span style="font-size: '.$this->user->rolle->fontsize_gle.'px; color: #222222;" title="'.$this->qlayerset[$i]['attributes']['tooltip'][$j].'">'.$this->qlayerset[$i]['attributes']['alias'][$j].'</span>';
							}
							else{
								$this->editable = 'true';
								if($this->qlayerset[$i]['attributes']['alias'][$j] == ''){
									$this->qlayerset[$i]['attributes']['alias'][$j] = $this->qlayerset[$i]['attributes']['name'][$j];
								}
								echo '<span style="font-size: '.$this->user->rolle->fontsize_gle.'px; color: #222222;" title="'.$this->qlayerset[$i]['attributes']['tooltip'][$j].'">'.$this->qlayerset[$i]['attributes']['alias'][$j].'</span>';
							}
							if($this->qlayerset[$i]['attributes']['nullable'][$j] == '0'){
								echo '<span title="Eingabe erforderlich">*</span>';
							}
							echo '</td>';
						}
?>
					<td>
<?
				  		if($this->qlayerset[$i]['attributes']['type'][$j] != 'geometry'){
				  			if($this->qlayerset[$i]['attributes']['constraints'][$j] != ''){
				  				if($this->qlayerset[$i]['attributes']['privileg'][$j] == '0' OR $lock[$k]){
										echo '<input readonly style="background-color:#e8e3da;" size="6" type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'" value="'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'">';
								} else {
				  					echo '<select title="'.$this->qlayerset[$i]['attributes']['alias'][$j].'" style="font-size: '.$this->user->rolle->fontsize_gle.'px" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'">';
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

										case 'Auswahlfeld' : case 'Auswahlfeld_not_saveable' : {
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
                        echo '<input readonly style="border:0px;background-color:transparent; font-size: '.$this->user->rolle->fontsize_gle.'px;" size="'.$auswahlfeld_output_laenge.'" type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'" value="'.$auswahlfeld_output.'">';
                        $auswahlfeld_output = '';
                      	$auswahlfeld_output_laenge = '';
											}
											else{
												echo '<select title="'.$this->qlayerset[$i]['attributes']['alias'][$j].'" style="width:290px;font-size: '.$this->user->rolle->fontsize_gle.'px"';
												if($this->qlayerset[$i]['attributes']['req_by'][$j] != ''){
													echo 'onchange="update_require_attribute(\''.$this->qlayerset[$i]['attributes']['req_by'][$j].'\', '.$k.','.$this->qlayerset[$i]['Layer_ID'].', this.value);" ';
												}
												echo 'id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'">';
												echo '<option value="">-- Bitte Auswählen --</option>';
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
															echo '<a href="javascript:ahah(\''.URL.APPLVERSION.'index.php\', \'go=neuer_Layer_Datensatz&selected_layer_id='.$this->qlayerset[$i]['attributes']['subform_layer_id'][$j].'&embedded=true&fromobject=subform'.$this->qlayerset[$i]['Layer_ID'].'_'.$k.'_'.$j.'&targetobject='.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'&targetlayer_id='.$this->qlayerset[$i]['Layer_ID'].'&targetattribute='.$this->qlayerset[$i]['attributes']['name'][$j].'\', new Array(document.getElementById(\'subform'.$this->qlayerset[$i]['Layer_ID'].'_'.$k.'_'.$j.'\')), \'sethtml\');">&nbsp;neu</a>';
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
													echo 	' style="font-size: '.$this->user->rolle->fontsize_gle.'px">'.$strShowPK.'</a>&nbsp;|';
												}
												if($this->qlayerset[$i]['attributes']['subform_layer_privileg'][$j] > 0){
													echo '&nbsp;<a href="" onclick="this.href=\'index.php?go=neuer_Layer_Datensatz&selected_layer_id='.$this->qlayerset[$i]['attributes']['subform_layer_id'][$j];
													for($p = 0; $p < count($this->qlayerset[$i]['attributes']['subform_pkeys'][$j]); $p++){
														echo '&attributenames['.$p.']='.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p];
														echo '&values['.$p.']=\'+document.getElementById(\''.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p].'_'.$k.'\').value';
													}
													echo 	'"';
													if($this->qlayerset[$i]['attributes']['no_new_window'][$j] != true){
														echo 	' target="_blank"';
													}
													echo 	' style="font-size: '.$this->user->rolle->fontsize_gle.'px">'.$strNewPK.'</a>';
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
												echo 	' style="font-size: '.$this->user->rolle->fontsize_gle.'px">'.$strShowFK.'</a>';
											}
											/*if($this->qlayerset[$i]['attributes']['subform_layer_privileg'][$j] > 0){
												echo '|&nbsp;<a href="index.php?go=neuer_Layer_Datensatz&selected_layer_id='.$this->qlayerset[$i]['attributes']['subform_layer_id'][$j];
												echo 	'"';
												if($this->qlayerset[$i]['attributes']['no_new_window'][$j] != true){
													echo 	' target="_blank"';
												}
												echo 	' style="font-size: '.$this->user->rolle->fontsize_gle.'px">'.$strNewFK.'</a>';
											}
											*/
										}
									}break;

										case 'SubFormEmbeddedPK' : {
											echo '<div id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'"></div>
														<script type="text/javascript">
																alert(\'Geht leider nicht, weil dieses script nicht ausgeführt wird, weil es ja mit Ajax nachgeladen wurde.\');
																ahah(\''.URL.APPLVERSION.'index.php\', \'go=Layer-Suche_Suchen&selected_layer_id='.$this->qlayerset[$i]['attributes']['subform_layer_id'][$j];
																$data = '';
																for($p = 0; $p < count($this->qlayerset[$i]['attributes']['subform_pkeys'][$j]); $p++){
																	$data .= '&value_'.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p].'='.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p]];
																	$data .= '&operator_'.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p].'==';
																}
																$data .= '&preview_attribute='.$this->qlayerset[$i]['attributes']['preview_attribute'][$j];
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
													echo '<a href="javascript:ahah(\''.URL.APPLVERSION.'index.php\', \'go=neuer_Layer_Datensatz';
													$data = '';
													for($p = 0; $p < count($this->qlayerset[$i]['attributes']['subform_pkeys'][$j]); $p++){
														echo '&attributenames['.$p.']='.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p];
														echo '&values['.$p.']=\'+document.getElementById(\''.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p].'_'.$k.'\').value+\'';
														$data .= '&value_'.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p].'=\'+document.getElementById(\''.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p].'_'.$k.'\').value+\'';
														$data .= '&operator_'.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p].'==';
													}
													$data .= '&preview_attribute='.$this->qlayerset[$i]['attributes']['preview_attribute'][$j];
													echo '&data='.str_replace('&', '<und>', $data);
													echo '&selected_layer_id='.$this->qlayerset[$i]['attributes']['subform_layer_id'][$j].'&embedded=true&fromobject=subform'.$this->qlayerset[$i]['Layer_ID'].'_'.$k.'_'.$j.'&targetobject='.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'&targetlayer_id='.$this->qlayerset[$i]['Layer_ID'].'&targetattribute='.$this->qlayerset[$i]['attributes']['name'][$j].'\', new Array(document.getElementById(\'subform'.$this->qlayerset[$i]['Layer_ID'].'_'.$k.'_'.$j.'\')), \'sethtml\');clearsubforms();">&nbsp;neu</a>';
													echo '<div style="display:inline" id="subform'.$this->qlayerset[$i]['Layer_ID'].'_'.$k.'_'.$j.'"></div>';
												}
												else{
													echo '<a target="_blank" href="" onclick="this.href=\'index.php?go=neuer_Layer_Datensatz';
													for($p = 0; $p < count($this->qlayerset[$i]['attributes']['subform_pkeys'][$j]); $p++){
														echo '&attributenames['.$p.']='.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p];
														echo '&values['.$p.']=\'+document.getElementById(\''.$this->qlayerset[$i]['attributes']['subform_pkeys'][$j][$p].'_'.$k.'\').value+\'';
													}
													echo '&selected_layer_id='.$this->qlayerset[$i]['attributes']['subform_layer_id'][$j].'\'">&nbsp;'.$strNewEmbeddedPK.'</a>';
												}
											}
										}break;

										case 'Time': {
											echo '<input style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px;"';
											if($this->qlayerset[$i]['attributes']['privileg'][$j] == '0' OR $lock[$k]){
												echo ' readonly style="background-color:#e8e3da;"';
											}
											echo ' size="61" type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'" value="'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'">';
										}break;

										# 2008-03-26 pk
										case 'Dokument': {
											if ($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]!='') {
												if($this->qlayerset[$i]['attributes']['options'][$j] != ''){		# bei Layern die auf andere Server zugreifen, wird die URL des anderen Servers verwendet
													$url = $this->qlayerset[$i]['attributes']['options'][$j];
												}
												else{
													$url = URL.APPLVERSION.'index.php?go=sendeDokument&dokument=';
												}
												echo '<iframe src="'.$url.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'&go_plus=mit_vorschau"></iframe>';
												echo '<input type="hidden" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].'_alt'.';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'" value="'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'">';

											}
											if($this->qlayerset[$i]['attributes']['privileg'][$j] != '0' AND !$lock[$k]){
												echo '<input style="font-size: '.$this->user->rolle->fontsize_gle.'px" size="43" type="file" accept="image/*" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'">';
											}
											else{
												echo '&nbsp;';
											}
										} break;

										case 'Link': {
											if ($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]!='') {
												echo '<a target="_blank" style="font-size: '.$this->user->rolle->fontsize_gle.'px" href="'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]].'">';
												if($this->qlayerset[$i]['attributes']['options'][$j] != ''){
													echo $this->qlayerset[$i]['attributes']['options'][$j];
												}
												else{
													echo basename($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]);
												}
												echo '</a><br>';
											}
											if($this->qlayerset[$i]['attributes']['privileg'][$j] != '0' OR $lock[$k]){
												echo '<input style="font-size: '.$this->user->rolle->fontsize_gle.'px" size="61" type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">';
											}
										} break;

										default : {
											echo '<input title="'.$this->qlayerset[$i]['attributes']['alias'][$j].'" ';
											if($this->qlayerset[$i]['attributes']['privileg'][$j] == '0' OR $lock[$k]){
												echo ' readonly style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px;"';
											}
											else{
												echo ' style="font-size: '.$this->user->rolle->fontsize_gle.'px;"';
											}
											echo ' size="49" type="text" name="'.$this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'" id="'.$this->qlayerset[$i]['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['name'][$j]]).'">';
										}
									}
				  			}
								if($this->qlayerset[$i]['attributes']['privileg'][$j] >= '0' AND !($this->qlayerset[$i]['attributes']['privileg'][$j] == '0' AND $this->qlayerset[$i]['attributes']['form_element_type'][$j] == 'Auswahlfeld')){
									$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].';'.$this->qlayerset[$i]['attributes']['type'][$j].'|';
								}
				  		}
				  		else {
				  			$columnname = $this->qlayerset[$i]['attributes']['name'][$j];
				  			$tablename = $this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]];
				  			$geomtype = $this->qlayerset[$i]['attributes']['geomtype'][$this->qlayerset[$i]['attributes']['name'][$j]];
				  			$dimension = $this->qlayerset[$i]['attributes']['dimension'][$j];
				  			$privileg = $this->qlayerset[$i]['attributes']['privileg'][$j];
				  			$this->form_field_names .= $this->qlayerset[$i]['Layer_ID'].';'.$this->qlayerset[$i]['attributes']['real_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].';'.$this->qlayerset[$i]['shape'][$k][$this->qlayerset[$i]['attributes']['table_name'][$this->qlayerset[$i]['attributes']['name'][$j]].'_oid'].';'.$this->qlayerset[$i]['attributes']['form_element_type'][$j].';'.$this->qlayerset[$i]['attributes']['nullable'][$j].'|';
				  		}
?>
					</td>
				</tr>
<?				}
				}
				if($privileg == 1) {
					if($this->new_entry == true){
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
 if($this->new_entry != true AND $this->formvars['printversion'] == '' AND $this->qlayerset[$i]['shape'][$k]['the_geom']){ ?>
					<tr>
						<? if($this->qlayerset[$i]['querymaps'][$k] != ''){ ?>
						<td bgcolor="<? echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;" align="center"><img style="border:1px solid grey" src="<? echo $this->qlayerset[$i]['querymaps'][$k]; ?>"></td>
						<? } else { ?>
			    	    <td bgcolor="<? echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;">&nbsp;</td>
			    	    <? } ?>
			    	    <td style="padding-top:5px; padding-bottom:5px;">&nbsp;&nbsp;
<?
								if($geomtype == 'POLYGON' OR $geomtype == 'MULTIPOLYGON' OR $geomtype == 'GEOMETRY'){
?>
			    					<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="index.php?go=zoomtoPolygon&oid=<?php echo $this->qlayerset[$i]['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $this->qlayerset[$i]['Layer_ID'];?>"><? echo $strMapZoom; ?></a>
<?
								} elseif($geomtype == 'POINT') {
?>
			    					<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="index.php?go=zoomtoPoint&dimension=<? echo $dimension; ?>&oid=<?php echo $this->qlayerset[$i]['shape'][$k][$tablename.'_oid']; ?>&tablename=<? echo $tablename; ?>&columnname=<? echo $columnname; ?>&layer_id=<? echo $this->qlayerset[$i]['Layer_ID'];?>"><? echo $strMapZoom; ?></a>
<?
			    				}
			    				elseif($geomtype == 'MULTILINESTRING' OR $geomtype == 'LINESTRING') {
?>
			    					<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="index.php?go=zoomToLine&oid=<?php echo $this->qlayerset[$i]['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $this->qlayerset[$i]['Layer_ID'];?>"><? echo $strMapZoom; ?></a>
<?
			    				}
?>
			    		</td>
			        </tr>
<? } ?>
			  </tbody>
			</table>
		</td>
	</tr>
<?
	}
?>
</table>
<input type="hidden" name="checkbox_names_<? echo $this->qlayerset[$i]['Layer_ID']; ?>" value="<? echo $checkbox_name; ?>">
<?
  }
  else {
  	# nix machen
  }
?>
