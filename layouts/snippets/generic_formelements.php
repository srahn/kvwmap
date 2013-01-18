<?
								switch ($attributes['form_element_type'][$j]){
									case 'Textfeld' : {
										echo '<textarea title="'.$attributes['alias'][$j].'" cols="45" onchange="set_changed_flag(document.GUI.changed_'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].')"';
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
                      echo '<input readonly id="'.$attributes['name'][$j].'_'.$k.'" style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px;" size="'.$auswahlfeld_output_laenge.'" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$auswahlfeld_output.'">';
                      $auswahlfeld_output = '';
                      $auswahlfeld_output_laenge = '';
										}
										else{
											echo '<select title="'.$attributes['alias'][$j].'" style="'.$select_width.'font-size: '.$this->user->rolle->fontsize_gle.'px"';
											if($attributes['req_by'][$j] != ''){
												echo 'onchange="update_require_attribute(\''.$attributes['req_by'][$j].'\', '.$k.','.$layer['Layer_ID'].', this.value);set_changed_flag(document.GUI.changed_'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" ';
											}
											else{
												echo 'onchange="set_changed_flag(document.GUI.changed_'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].')"';
											}
											echo 'id="'.$attributes['name'][$j].'_'.$k.'" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'">';
											echo '<option value="">-- '.$this->strPleaseSelect.' --</option>';
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
										echo ' size="'.$size.'" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$layer['shape'][$k][$attributes['name'][$j]].'">';
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
										echo ' size="'.$size.'" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.htmlspecialchars($layer['shape'][$k][$attributes['name'][$j]]).'">';
									}break;
									
									case 'Zahl': {
										# bei Zahlen Tausendertrennzeichen einfügen 
										$value = tausenderTrenner($layer['shape'][$k][$attributes['name'][$j]]);
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
										echo ' size="'.$size.'" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" id="'.$attributes['name'][$j].'_'.$k.'" value="'.htmlspecialchars($value).'">';
									}break;
									
									default : {
										$value = $layer['shape'][$k][$attributes['name'][$j]];
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
										echo ' size="'.$size.'" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" id="'.$attributes['name'][$j].'_'.$k.'" value="'.htmlspecialchars($value).'">';
									}
								}
?>
