Dieses Snippet wird nicht mehr verwendet

<?php
 # 2008-09-30 sr
  include(LAYOUTPATH.'languages/generic_layer_editor_2_'.rolle::$language.'.php');

 # Variablensubstitution
 $layer = $this->qlayerset[$i];
 $attributes = $layer['attributes'];
 $size = 28;
 $select_width = 'width:190px;';
 $this->subform_classname = 'subform_'.$layer['layer_id'];

  	$doit = false;
	  $anzObj = count_or_0($this->qlayerset[$i]['shape']);
	  if ($anzObj > 0) {
			$k = 0;
	  	$this->found = 'true';
	  	$doit = true;
	  }
	  if($this->new_entry == true){
	  	$anzObj = 0;
			$k  = -1;
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
	for ($k;$k<$anzObj;$k++) {
		$checkbox_name .= 'check;'.$attributes['table_alias_name'][$attributes['name'][0]].';'.$attributes['table_name'][$attributes['name'][0]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][0]].'_oid'];
?>
	<tr>
		<td>
			<table class="tgle" border="1">
			  <tbody class="gle">
<?		$trans_oid = explode('|', $layer['shape'][$k]['lock']);
			if($layer['shape'][$k]['lock'] == 'bereits übertragen' OR $trans_oid[1] != '' AND $layer['shape'][$k][$attributes['table_name'][$attributes['name'][0]].'_oid'] == $trans_oid[1]){
				echo '<tr><td colspan="2" align="center"><span class="red">Dieser Datensatz wurde bereits übertragen und kann nicht bearbeitet werden.</span></td></tr>';
				$lock[$k] = true;
			}
			for($j = 0; $j < count($attributes['name']); $j++){
				$layer_id = $layer['layer_id'];
				$dataset = $layer['shape'][$k]; 						# der aktuelle Datensatz (wird nur beim Array- oder Nutzer-Datentyp übergeben)
				$name = $attributes['name'][$j];																# der Name des Attributs
				$alias = $attributes['alias'][$j];															# der Aliasname des Attributs
				$value = $dataset[$name];																				# der Wert des Attributs
				$tablename = $attributes['table_name'][$name];									# der Tabellenname des Attributs
				$oid = $dataset[$layer['maintable'] . '_oid'];									# die oid des Datensatzes
				$attribute_privileg = $attributes['privileg'][$j];							# das Recht des Attributs

				$fieldname = $layer_id . ';' . ($attributes['saveable'][$j]? $attributes['real_name'][$name] : '') . ';' . $tablename . ';' . $oid . ';' . $attributes['form_element_type'][$j] . ';' . $attributes['nullable'][$j] . ';' . $attributes['type'][$j] . ';' . $attributes['saveable'][$j];

				if($attributes['name'][$j] == 'vorschau')continue;		# Vorschauattribut nicht nochmal anzeigen
				if($layer['shape'][$k][$attributes['name'][$j]] == ''){
					$layer['shape'][$k][$attributes['name'][$j]] = $this->formvars[$layer['layer_id'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j]];
				}
				if($this->new_entry == true AND $attributes['default'][$j] != '' AND $layer['shape'][$k][$attributes['name'][$j]] == ''){		# Default-Werte setzen
					$layer['shape'][$k][$attributes['name'][$j]] = $attributes['default'][$j];
				}
				// if($attributes['privileg'][$j] == '0' AND $attributes['form_element_type'][$j] == 'Auswahlfeld' OR $attributes['type'][$j] == 'not_saveable'){				# entweder ist es ein nicht speicherbares Attribut oder ein nur lesbares Auswahlfeld, dann ist es auch nicht speicherbar
					// $attributes['form_element_type'][$j] .= '_not_saveable';
				// }
				if($attributes['invisible'][$attributes['name'][$j]] != 'true'  AND $attributes['name'][$j] != 'lock'){
?>
					<tr 
						<? 
						if($attributes['name'][$j] == 'plan_id')echo 'style="display: none"'; 
						if($attributes['name'][$j] == 'kap2_gemziel_s'){
							echo 'id="kap2_gemziel_s"';
							if($layer['shape'][$k]['gebietstyp_s'] != 33)echo ' style="display: none"';
						}
						if($attributes['name'][$j] == 'kap2_nachstell_s'){
							echo 'id="kap2_nachstell_s"';
							 if($layer['shape'][$k]['gebietstyp_s'] != 33)echo ' style="display: none"';
						}
						?>
						>
<?				if($attributes['type'][$j] != 'geometry'){
						echo '<td  valign="top" bgcolor="'.BG_GLEATTRIBUTE.'">';
						if($attributes['privileg'][$j] != '0' AND !$lock[$k]){
							$this->editable = $layer['layer_id'];
						}
						if($attributes['alias'][$j] == ''){
							$attributes['alias'][$j] = $attributes['name'][$j];
						}
						echo '<table width="100%"><tr><td>';
						echo '<span style="color: #222222;" title="'.$attributes['tooltip'][$j].'">'.$attributes['alias'][$j].'</span>';
						if($attributes['nullable'][$j] == '0' AND $attributes['privileg'][$j] != '0'){
							echo '<span title="Eingabe erforderlich">*</span>';
						}
						if($attributes['tooltip'][$j]!='' AND $attributes['form_element_type'][$j] != 'Time') {
						  echo '<td align="right"><a href="#" title="'.$attributes['tooltip'][$j].'"><img src="'.GRAPHICSPATH.'emblem-important.png" border="0"></a></td>';
						}
						echo '</td></tr></table>';
						echo '</td><td>';
		  			if($attributes['constraints'][$j] != '' AND $attributes['constraints'][$j] != 'PRIMARY KEY'){
		  				if($attributes['privileg'][$j] == '0' OR $lock[$k]){
								echo '<input readonly style="background-color:#e8e3da;" size="6" type="text" name="'.$layer['layer_id'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$layer['shape'][$k][$attributes['name'][$j]].'">';
							}
							else{
		  					echo '<select title="'.$attributes['alias'][$j].'" name="'.$layer['layer_id'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'">';
								foreach ($attributes['enum'][$j] as $enum_key => $enum) {
									echo '<option ';
									if ($enum_key == $layer['shape'][$k][$attributes['name'][$j]] OR ($enum_key != '' AND $enum_key == $this->formvars[$layer['layer_id'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j]])){
										echo 'selected ';
									}
									echo 'value="' . $enum_key . '">' . $enum['output'] . '</option>';
								}
								echo '</select>';
		  				}
		  			}
		  			else{
		  				switch ($attributes['form_element_type'][$j]){
									case 'Textfeld' : {
										echo '<textarea cols="23" onchange="set_changed_flag(document.GUI.changed_'.$layer['layer_id'].'_'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].')"';
										if($attributes['privileg'][$j] == '0' OR $lock[$k]){
											echo ' readonly style="border:0px;background-color:transparent;font-family:arial,verdana,helvetica,sans-serif;"';
										}
										else{
											echo ' style="font-family:arial,verdana,helvetica,sans-serif;"';
										}
										echo ' rows="2" name="'.$layer['layer_id'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'">'.$layer['shape'][$k][$attributes['name'][$j]].'</textarea>';
									}break;

									case 'Auswahlfeld' : {
										if (is_array($attributes['dependent_options'][$j])) {
											$enum = $attributes['enum'][$j][$k];		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
										}
										else{
											$enum = $attributes['enum'][$j];
										}
										echo Auswahlfeld($layer_id, $name, $j, $alias, $fieldname, $value, $enum, $attributes['req_by'][$j], $attributes['req'][$j], $attributes['name'], $attribute_privileg, $k, $oid, $attributes['subform_layer_id'][$j], $attributes['subform_layer_privileg'][$j], $attributes['embedded'][$j], $select_width, $strPleaseSelect, $change_all, 'update_gebietstyp();', $this->subform_classname, $attributes['datatype_id'][$j]);
									}break;

									case 'SubFormPK' : {
										echo '<input ';
										if($attributes['privileg'][$j] == '0' OR $lock[$k]){
											echo ' readonly style="background-color:#e8e3da;"';
										}
										echo ' size="40" type="text" name="'.$layer['layer_id'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$layer['shape'][$k][$attributes['name'][$j]].'">';
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
												echo 	' >'.$strShowPK.'</a>&nbsp;';
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
												echo 	' >'.$strNewPK.'</a>';
											}
										}
									}break;

									case 'SubFormFK' : {
										$dataset = $layer['shape'][$k];								# der aktuelle Datensatz
										$attribute_foreign_keys = $attributes['subform_fkeys'][$j];	# die FKeys des aktuellen Attributes
										for($f = 0; $f < count($attribute_foreign_keys); $f++){
											if($dataset[$attribute_foreign_keys[$f]] == ''){
												$dataset[$attribute_foreign_keys[$f]] = $this->formvars[$layer['layer_id'].';'.$attributes['real_name'][$attribute_foreign_keys[$f]].';'.$attributes['table_name'][$attribute_foreign_keys[$f]].';'.$dataset[$attributes['table_name'][$attribute_foreign_keys[$f]].'_oid'].';TextFK;0;varchar'];
											}
											echo '<input style="';
											if($attributes['privileg'][$attribute_foreign_keys[$f]] == '0' OR $lock[$k]){
												echo ';background-color:transparent;border:0px;display:none;background-color:#e8e3da;" readonly ';
											}
											else{
												'" ';
											}
											echo ' id="'.$attributes['real_name'][$attribute_foreign_keys[$f]].'_'.$k.'" name="'.$layer['layer_id'].';'.$attributes['real_name'][$attribute_foreign_keys[$f]].';'.$attributes['table_name'][$attribute_foreign_keys[$f]].';'.$dataset[$attributes['table_name'][$attribute_foreign_keys[$f]].'_oid'].';TextFK;0;varchar" value="'.$dataset[$attribute_foreign_keys[$f]].'">';
											if($attributes['privileg'][$attribute_foreign_keys[$f]] > 0 AND !$lock[$k]){
												echo '<br>';
											}
											$this->form_field_names .= $layer['layer_id'].';'.$attributes['real_name'][$attribute_foreign_keys[$f]].';'.$attributes['table_name'][$attribute_foreign_keys[$f]].';'.$dataset[$attributes['table_name'][$attribute_foreign_keys[$f]].'_oid'].';TextFK;0;varchar|';
										}
										echo '<input style="border:0px;background-color:transparent;"';
										if($attributes['privileg'][$j] == '0' OR $lock[$k]){
											echo ' readonly style="background-color:#e8e3da;"';
										}
										echo ' size="50" type="text" name="'.$layer['layer_id'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$dataset[$attributes['name'][$j]].'">';
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
												echo 	' >'.$strShowFK.'</a>';
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
															ahah(\'index.php\', \'go=Layer-Suche_Suchen&selected_layer_id='.$attributes['subform_layer_id'][$j];
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
															echo '&targetobject='.$attributes['name'][$j].'_'.$k.'&targetlayer_id='.$layer['layer_id'].'&targetattribute='.$attributes['name'][$j];
															echo '\', new Array(document.getElementById(\''.$attributes['name'][$j].'_'.$k.'\')), \'\');
														</script>
													';
											if($attributes['subform_layer_privileg'][$j] > 0 AND !$lock[$k]){
												if($attributes['embedded'][$j] == true){
													echo '<table width="98%" cellspacing="0" cellpadding="0"><tr><td width="100%" align="right"><a href="javascript:ahah(\'index.php\', \'go=neuer_Layer_Datensatz';
													$data = '';
													for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
														echo '&attributenames['.$p.']='.$attributes['subform_pkeys'][$j][$p];
														echo '&values['.$p.']=\'+document.getElementById(\''.$attributes['subform_pkeys'][$j][$p].'_'.$k.'\').value+\'';
														$data .= '&value_'.$attributes['subform_pkeys'][$j][$p].'=\'+document.getElementById(\''.$attributes['subform_pkeys'][$j][$p].'_'.$k.'\').value+\'';
														$data .= '&operator_'.$attributes['subform_pkeys'][$j][$p].'==';
													}
													$data .= '&preview_attribute='.$attributes['preview_attribute'][$j];
													echo '&data='.str_replace('&', '<und>', $data);
													echo '&selected_layer_id='.$attributes['subform_layer_id'][$j].'&embedded=true&fromobject=subform'.$layer['layer_id'].'_'.$k.'_'.$j.'&targetobject='.$attributes['name'][$j].'_'.$k.'&targetlayer_id='.$layer['layer_id'].'&targetattribute='.$attributes['name'][$j].'\', new Array(document.getElementById(\'subform'.$layer['layer_id'].'_'.$k.'_'.$j.'\')), new Array(\'sethtml\'));clearsubforms();">&nbsp;'.$strNewEmbeddedPK.'</a></td></tr></table>';
													echo '<div style="display:inline" id="subform'.$layer['layer_id'].'_'.$k.'_'.$j.'"></div>';
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
										echo '<input readonly style="border:0px;background-color:transparent;"';
										echo ' size="'.$size.'" type="text" name="'.$layer['layer_id'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$layer['shape'][$k][$attributes['name'][$j]].'">';
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
			  								echo '<a href="javascript:delete_document(\''.$layer['layer_id'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'\');">Dokument <br>lÃ¶schen</a>';
			  							}
											echo '</td></tr></table>';
											echo '<input type="hidden" name="'.$layer['layer_id'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].'_alt'.';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$layer['shape'][$k][$attributes['name'][$j]].'">';

										}
										if($attributes['privileg'][$j] != '0' AND !$lock[$k]){
											echo '<input onchange="set_changed_flag(document.GUI.changed_'.$layer['layer_id'].'_'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" size="43" type="file" onchange="this.title=this.value;" accept="image/*" name="'.$layer['layer_id'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'">';
										}
										else{
											echo '&nbsp;';
										}
									} break;

									case 'Link': {
										if ($layer['shape'][$k][$attributes['name'][$j]]!='') {
											echo '<a class="link" target="_blank" href="'.$layer['shape'][$k][$attributes['name'][$j]].'">';
											if($attributes['options'][$j] != ''){
												echo $attributes['options'][$j];
											}
											else{
												echo basename($layer['shape'][$k][$attributes['name'][$j]]);
											}
											echo '</a><br>';
										}
										if($attributes['privileg'][$j] != '0' OR $lock[$k]){
											echo '<input onchange="set_changed_flag(document.GUI.changed_'.$layer['layer_id'].'_'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" size="61" type="text" name="'.$layer['layer_id'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.htmlspecialchars($layer['shape'][$k][$attributes['name'][$j]]).'">';
										}else{
											echo '<input type="hidden" name="'.$layer['layer_id'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.htmlspecialchars($layer['shape'][$k][$attributes['name'][$j]]).'">';
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
											echo '<a href="javascript:if(document.getElementById(\'dynamicLink'.$layer['layer_id'].'_'.$k.'_'.$j.'\').innerHTML != \'\'){clearsubform(\'dynamicLink'.$layer['layer_id'].'_'.$k.'_'.$j.'\');} else {ahah(\''.$href.'\', \'\', new Array(document.getElementById(\'dynamicLink'.$layer['layer_id'].'_'.$k.'_'.$j.'\')), new Array(\'sethtml\'))}">';
											echo $alias;
											echo '</a><br>';
											echo '<div style="display:inline" id="dynamicLink'.$layer['layer_id'].'_'.$k.'_'.$j.'"></div>';
										}
										else{
											echo '<a ';
											if($explosion[2] != 'no_new_window'){echo 'target="_blank"';}
											echo ' href="'.$href.'">';
											echo $alias;
											echo '</a><br>';
										}
									} break;

									case 'Fläche': {
										echo '<input onchange="set_changed_flag(document.GUI.changed_'.$layer['layer_id'].'_'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" class="custom_area" id="'.$layer_id.'_'.$name.'_'.$k.'" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$attributes['alias'][$j].'" ';
										if($attributes['privileg'][$j] == '0' OR $lock[$k]){
											echo ' readonly style="background-color:#e8e3da;"';
										}
										echo ' size="'.$size.'" type="text" name="'.$layer['layer_id'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.htmlspecialchars($layer['shape'][$k][$attributes['name'][$j]]).'">';
									}break;
									
									case 'Zahl': {
										# bei Zahlen Tausendertrennzeichen einfÃ¼gen 
										$value = tausenderTrenner($layer['shape'][$k][$attributes['name'][$j]]);
										echo '<input onchange="set_changed_flag(document.GUI.changed_'.$layer['layer_id'].'_'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$attributes['alias'][$j].'" ';
										if($attributes['privileg'][$j] == '0' OR $lock[$k]){
											echo ' readonly style="border:0px;background-color:transparent;"';
										}
										if($attributes['name'][$j] == 'lock'){
											echo ' type="hidden"';
										}
										if($attributes['length'][$j] AND !in_array($attributes['type'][$j], array('numeric', 'float4', 'float8', 'int2', 'int4', 'int8'))){
											echo ' maxlength="'.$attributes['length'][$j].'"';
										}
										echo ' size="'.$size.'" type="text" name="'.$layer['layer_id'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" id="'.$attributes['name'][$j].'_'.$k.'" value="'.htmlspecialchars($value).'">';
									}break;
									
									default : {
										$value = $layer['shape'][$k][$attributes['name'][$j]];
										echo '<input onchange="set_changed_flag(document.GUI.changed_'.$layer['layer_id'].'_'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$attributes['alias'][$j].'" ';
										if($attributes['privileg'][$j] == '0' OR $lock[$k]){
											echo ' readonly style="border:0px;background-color:transparent;"';
										}
										if($attributes['name'][$j] == 'lock'){
											echo ' type="hidden"';
										}
										if($attributes['length'][$j] AND !in_array($attributes['type'][$j], array('numeric', 'float4', 'float8', 'int2', 'int4', 'int8'))){
											echo ' maxlength="'.$attributes['length'][$j].'"';
										}
										echo ' size="'.$size.'" type="text" class="' . $this->subform_classname . '" name="'.$layer['layer_id'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].';'.$attributes['saveable'][$j].'" id="'.$attributes['name'][$j].'_'.$k.'" value="'.htmlspecialchars($value).'">';
									}
								}
		  			}
						if($attributes['privileg'][$j] >= '0' AND !($attributes['privileg'][$j] == '0' AND $attributes['form_element_type'][$j] == 'Auswahlfeld')){
							$this->form_field_names .= $layer['layer_id'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'|';
						}
		  		}
		  		else {
		  			$columnname = $attributes['name'][$j];
		  			$tablename = $attributes['table_name'][$attributes['name'][$j]];
		  			$geomtype = $attributes['geomtype'][$attributes['name'][$j]];
		  			$dimension = $attributes['dimension'][$j];
		  			$privileg = $attributes['privileg'][$j];
		  			$this->form_field_names .= $layer['layer_id'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].'|';
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
				    					<a href="index.php?go=PolygonEditor&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['layer_id'];?>&selected_layer_id=<? echo $layer['layer_id'];?>"><? echo $strEditGeom; ?></a>
	<?
									} elseif($geomtype == 'POINT') {
	?>
				    					<a href="index.php?go=PointEditor&dimension=<? echo $dimension; ?>&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&tablename=<? echo $tablename; ?>&columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['layer_id'];?>"><? echo $strEditGeom; ?></a>
	<?
				    				}
				    				elseif($geomtype == 'MULTILINESTRING' OR $geomtype == 'LINESTRING') {
	?>
				    					<a href="index.php?go=LineEditor&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['layer_id'];?>&selected_layer_id=<? echo $layer['layer_id'];?>"><? echo $strEditGeom; ?></a>
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
 if($this->new_entry != true AND $this->formvars['printversion'] == '' AND $layer['shape'][$k]['the_geom']){ ?>
					<tr>
						<? if($layer['querymaps'][$k] != ''){ ?>
						<td bgcolor="<? echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;" align="center"><img style="border:1px solid grey" src="<? echo $layer['querymaps'][$k]; ?>"></td>
						<? } else { ?>
			    	    <td bgcolor="<? echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;">&nbsp;</td>
			    	    <? } ?>
			    	    <td style="padding-top:5px; padding-bottom:5px;">&nbsp;&nbsp;
<?
								if($geomtype == 'POLYGON' OR $geomtype == 'MULTIPOLYGON' OR $geomtype == 'GEOMETRY'){
?>
			    					<a href="index.php?go=zoomtoPolygon&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['layer_id'];?>"><? echo $strMapZoom; ?></a>
<?
								} elseif($geomtype == 'POINT') {
?>
			    					<a href="index.php?go=zoomtoPoint&dimension=<? echo $dimension; ?>&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&tablename=<? echo $tablename; ?>&columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['layer_id'];?>"><? echo $strMapZoom; ?></a>
<?
			    				}
			    				elseif($geomtype == 'MULTILINESTRING' OR $geomtype == 'LINESTRING') {
?>
			    					<a href="index.php?go=zoomToLine&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['layer_id'];?>"><? echo $strMapZoom; ?></a>
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
<input type="hidden" name="checkbox_names_<? echo $layer['layer_id']; ?>" value="<? echo $checkbox_name; ?>">
<?
  }
  else {
  	# nix machen
  }
?>
