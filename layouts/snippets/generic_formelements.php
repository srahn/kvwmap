<?
								$dataset = $layer['shape'][$k];								# der aktuelle Datensatz
								switch ($attributes['form_element_type'][$j]){
									case 'Textfeld' : {
										$datapart .= '<textarea title="'.$attributes['alias'][$j].'" cols="45" onchange="set_changed_flag(currentform.changed_'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].')"';
										if($attributes['privileg'][$j] == '0' OR $lock[$k]){
											$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px;"';
										}
										else{
											$datapart .= ' style="font-size: '.$this->user->rolle->fontsize_gle.'px"';
										}
										$datapart .= ' rows="3" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'">'.$dataset[$attributes['name'][$j]].'</textarea>';
									}break;

									case 'Auswahlfeld' : case 'Auswahlfeld_not_saveable' : {
										if($attributes['privileg'][$j] == '0' OR $lock[$k]){
										  if(is_array($attributes['dependent_options'][$j])){		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
												for($e = 0; $e < count($attributes['enum_value'][$j][$k]); $e++){
													if($attributes['enum_value'][$j][$k][$e] == $dataset[$attributes['name'][$j]]){
														$auswahlfeld_output = $attributes['enum_output'][$j][$k][$e];
														$auswahlfeld_output_laenge=strlen($auswahlfeld_output)+1;
														break;
													}
												}
											}
											else{
												for($e = 0; $e < count($attributes['enum_value'][$j]); $e++){
													if($attributes['enum_value'][$j][$e] == $dataset[$attributes['name'][$j]]){
														$auswahlfeld_output = $attributes['enum_output'][$j][$e];
														$auswahlfeld_output_laenge=strlen($auswahlfeld_output)+1;
														break;
													}
												}
											}
                      $datapart .= '<input readonly id="'.$attributes['name'][$j].'_'.$k.'" style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px;" size="'.$auswahlfeld_output_laenge.'" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$auswahlfeld_output.'">';
                      $auswahlfeld_output = '';
                      $auswahlfeld_output_laenge = '';
										}
										else{
											$datapart .= '<select title="'.$attributes['alias'][$j].'" style="'.$select_width.'font-size: '.$this->user->rolle->fontsize_gle.'px"';
											if($attributes['req_by'][$j] != ''){
												$datapart .= 'onchange="update_require_attribute(\''.$attributes['req_by'][$j].'\', '.$k.','.$layer['Layer_ID'].', this.value);set_changed_flag(currentform.changed_'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" ';
											}
											else{
												$datapart .= 'onchange="set_changed_flag(currentform.changed_'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].')"';
											}
											$datapart .= 'id="'.$attributes['name'][$j].'_'.$k.'" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'">';
											$datapart .= '<option value="">-- '.$this->strPleaseSelect.' --</option>';
											if(is_array($attributes['dependent_options'][$j])){		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
												for($e = 0; $e < count($attributes['enum_value'][$j][$k]); $e++){
													$datapart .= '<option ';
													if($attributes['enum_value'][$j][$k][$e] == $dataset[$attributes['name'][$j]] OR ($attributes['enum_value'][$j][$k][$e] != '' AND $attributes['enum_value'][$j][$k][$e] == $this->formvars[$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j]])){
														$datapart .= 'selected ';
													}
													$datapart .= 'value="'.$attributes['enum_value'][$j][$k][$e].'">'.$attributes['enum_output'][$j][$k][$e].'</option>';
												}
											}
											else{
												for($e = 0; $e < count($attributes['enum_value'][$j]); $e++){
													$datapart .= '<option ';
													if($attributes['enum_value'][$j][$e] == $dataset[$attributes['name'][$j]] OR ($attributes['enum_value'][$j][$e] != '' AND $attributes['enum_value'][$j][$e] == $this->formvars[$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j]])){
														$datapart .= 'selected ';
													}
													$datapart .= 'value="'.$attributes['enum_value'][$j][$e].'">'.$attributes['enum_output'][$j][$e].'</option>';
												}
											}
											$datapart .= '</select>';
											if($attributes['subform_layer_id'][$j] != ''){
												if($attributes['subform_layer_privileg'][$j] > 0){
													if($attributes['embedded'][$j] == true){
														$datapart .= '<a href="javascript:ahah(\'index.php\', \'go=neuer_Layer_Datensatz&selected_layer_id='.$attributes['subform_layer_id'][$j].'&embedded=true&fromobject=subform'.$layer['Layer_ID'].'_'.$k.'_'.$j.'&targetobject='.$attributes['name'][$j].'_'.$k.'&targetlayer_id='.$layer['Layer_ID'].'&targetattribute='.$attributes['name'][$j].'\', new Array(document.getElementById(\'subform'.$layer['Layer_ID'].'_'.$k.'_'.$j.'\')), new Array(\'sethtml\'));clearsubforms();">&nbsp;neu&nbsp;</a>';
														$datapart .= '<div style="display:inline" id="subform'.$layer['Layer_ID'].'_'.$k.'_'.$j.'"></div>';
													}
													else{
														$datapart .= '<a target="_blank" href="index.php?go=neuer_Layer_Datensatz&selected_layer_id='.$attributes['subform_layer_id'][$j].'">&nbsp;neu&nbsp;</a>';
													}
												}
											}
										}
									}break;
									
									case 'Checkbox' : {
										$datapart .= '<input type="checkbox" title="'.$attributes['alias'][$j].'" cols="45" onchange="set_changed_flag(currentform.changed_'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].')"';
										if($attributes['privileg'][$j] == '0' OR $lock[$k]){
											$datapart .= ' onclick="return false" style="border:0px;background-color:transparent;"';
										}
										$datapart .= 'value="t" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'"';
										if($dataset[$attributes['name'][$j]] == 't')$datapart .= 'checked=true';
										$datapart .= '>';
									}break;

									case 'SubFormPK' : {
										$datapart .= '<input style="font-size: '.$this->user->rolle->fontsize_gle.'px"';
										if($attributes['privileg'][$j] == '0' OR $lock[$k]){
											$datapart .= ' readonly style="background-color:#e8e3da;"';
										}
										$datapart .= ' size="40" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$dataset[$attributes['name'][$j]].'">';
										if($this->new_entry != true){
											if($dataset[$attributes['name'][$j]] != ''){
												$datapart .= '&nbsp;<a href="javascript:overlay_link(\'go=Layer-Suche_Suchen&selected_layer_id='.$attributes['subform_layer_id'][$j];
												for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
													$datapart .= '&value_'.$attributes['subform_pkeys'][$j][$p].'='.$dataset[$attributes['subform_pkeys'][$j][$p]];
													$datapart .= '&operator_'.$attributes['subform_pkeys'][$j][$p].'==';
												}
												$datapart .= 	'\')"';
												if($attributes['no_new_window'][$j] != true){
													$datapart .= 	' target="_blank"';
												}
												$datapart .= 	' style="font-size: '.$this->user->rolle->fontsize_gle.'px">'.$strShowPK.'</a>&nbsp;';
											}
											if($attributes['subform_layer_privileg'][$j] > 0){
												$datapart .= '|&nbsp;<a href="" onclick="this.href=\'index.php?go=neuer_Layer_Datensatz&selected_layer_id='.$attributes['subform_layer_id'][$j];
												for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
													$datapart .= '&attributenames['.$p.']='.$attributes['subform_pkeys'][$j][$p];
													$datapart .= '&values['.$p.']=\'+document.getElementById(\''.$attributes['subform_pkeys'][$j][$p].'_'.$k.'\').value';
												}
												$datapart .= 	'"';
												if($attributes['no_new_window'][$j] != true){
													$datapart .= 	' target="_blank"';
												}
												$datapart .= 	' style="font-size: '.$this->user->rolle->fontsize_gle.'px">'.$strNewPK.'</a>';
											}
										}
									}break;

									case 'SubFormFK' : {
										$attribute_foreign_keys = $attributes['subform_fkeys'][$j];	# die FKeys des aktuellen Attributes
										for($f = 0; $f < count($attribute_foreign_keys); $f++){
											if($dataset[$attribute_foreign_keys[$f]] == ''){
												$dataset[$attribute_foreign_keys[$f]] = $this->formvars[$layer['Layer_ID'].';'.$attributes['real_name'][$attribute_foreign_keys[$f]].';'.$attributes['table_name'][$attribute_foreign_keys[$f]].';'.$dataset[$attributes['table_name'][$attribute_foreign_keys[$f]].'_oid'].';TextFK;0;varchar'];
											}
											$datapart .= '<input style="font-size: '.(0.9*$this->user->rolle->fontsize_gle).'px';
											if($attributes['privileg'][$attribute_foreign_keys[$f]] == '0' OR $lock[$k]){
												$datapart .= ';background-color:transparent;border:0px;display:none;background-color:#e8e3da;" readonly ';
											}
											else{
												'" ';
											}
											$datapart .= ' id="'.$attributes['real_name'][$attribute_foreign_keys[$f]].'_'.$k.'" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attribute_foreign_keys[$f]].';'.$attributes['table_name'][$attribute_foreign_keys[$f]].';'.$dataset[$attributes['table_name'][$attribute_foreign_keys[$f]].'_oid'].';TextFK;0;varchar" value="'.$dataset[$attribute_foreign_keys[$f]].'">';
											if($attributes['privileg'][$attribute_foreign_keys[$f]] > 0 AND !$lock[$k]){
												$datapart .= '<br>';
											}
											$this->form_field_names .= $layer['Layer_ID'].';'.$attributes['real_name'][$attribute_foreign_keys[$f]].';'.$attributes['table_name'][$attribute_foreign_keys[$f]].';'.$dataset[$attributes['table_name'][$attribute_foreign_keys[$f]].'_oid'].';TextFK;0;varchar|';
										}
										$datapart .= '<input style="width:83%;border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px"';
										if($attributes['privileg'][$j] == '0' OR $lock[$k]){
											$datapart .= ' readonly style="background-color:#e8e3da;"';
										}
										$datapart .= ' type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$dataset[$attributes['name'][$j]].'">';
										if($this->new_entry != true){
											if($dataset[$attributes['name'][$j]] != ''){
												$datapart .= '&nbsp;<a href="javascript:overlay_link(\'go=Layer-Suche_Suchen&selected_layer_id='.$attributes['subform_layer_id'][$j];
												for($f = 0; $f < count($attribute_foreign_keys); $f++){
													$datapart .= '&value_'.$attribute_foreign_keys[$f].'='.$dataset[$attribute_foreign_keys[$f]];
													$datapart .= '&operator_'.$attribute_foreign_keys[$f].'==';
												}
												$datapart .= 	'\')"';
												if($attributes['no_new_window'][$j] != true){
													$datapart .= 	' target="_blank"';
												}
												$datapart .= 	' style="font-size: '.$this->user->rolle->fontsize_gle.'px">'.$strShowFK.'</a>';
											}
										}
									}break;

									case 'SubFormEmbeddedPK' : {
										$datapart .= '<div id="'.$attributes['name'][$j].'_'.$k.'"><img src="'.GRAPHICSPATH.'leer.gif" ';
										if($this->new_entry != true AND $no_query != true){
											$datapart .= 'onload="ahah(\'index.php\', \'go=Layer-Suche_Suchen&selected_layer_id='.$attributes['subform_layer_id'][$j];
															$data = '';
															for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
																$data .= '&value_'.$attributes['subform_pkeys'][$j][$p].'='.$dataset[$attributes['subform_pkeys'][$j][$p]];
																$data .= '&operator_'.$attributes['subform_pkeys'][$j][$p].'==';
															}
															$data .= '&preview_attribute='.$attributes['preview_attribute'][$j];
															$data .= '&count='.$k;
															$data .= '&no_new_window='.$attributes['no_new_window'][$j];
															$datapart .= $data;
															$datapart .= '&data='.str_replace('&', '<und>', $data);
															$datapart .= '&embedded_subformPK=true';
															if($attributes['embedded'][$j] == true){
																$datapart .= '&embedded=true';
															}
															$datapart .= '&targetobject='.$attributes['name'][$j].'_'.$k.'&targetlayer_id='.$layer['Layer_ID'].'&targetattribute='.$attributes['name'][$j];
															$datapart .= '\', new Array(document.getElementById(\''.$attributes['name'][$j].'_'.$k.'\')), new Array(\'sethtml\'));
														"';
										}
										$datapart .= '></div><table width="98%" cellspacing="0" cellpadding="2"><tr style="border: none"><td width="100%" align="right">';
										$no_query = false;
										for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
											if($dataset[$attributes['subform_pkeys'][$j][$p]] == ''){
												$no_query = true;
											}
										}
										if($this->new_entry != true AND $no_query != true){
											$datapart .= '<a id="show_all_'.$attributes['name'][$j].'_'.$k.'" style="font-size: '.$linksize.'px;display:none" class="buttonlink" href="javascript:overlay_link(\'go=Layer-Suche_Suchen&selected_layer_id='.$attributes['subform_layer_id'][$j];
											for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
												$datapart .= '&value_'.$attributes['subform_pkeys'][$j][$p].'='.$dataset[$attributes['subform_pkeys'][$j][$p]];
												$datapart .= '&operator_'.$attributes['subform_pkeys'][$j][$p].'==';
											}
											$datapart .= 	'\')">'.$strShowAll.'</a>';												
											if($attributes['subform_layer_privileg'][$j] > 0 AND !$lock[$k]){
												if($attributes['embedded'][$j] == true){
													$datapart .= '&nbsp;<a class="buttonlink" href="javascript:ahah(\'index.php\', \'go=neuer_Layer_Datensatz';
													$data = '';
													for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
														$datapart .= '&attributenames['.$p.']='.$attributes['subform_pkeys'][$j][$p];
														$datapart .= '&values['.$p.']=\'+document.getElementById(\''.$attributes['subform_pkeys'][$j][$p].'_'.$k.'\').value+\'';
														$data .= '&value_'.$attributes['subform_pkeys'][$j][$p].'=\'+document.getElementById(\''.$attributes['subform_pkeys'][$j][$p].'_'.$k.'\').value+\'';
														$data .= '&operator_'.$attributes['subform_pkeys'][$j][$p].'==';
													}
													$data .= '&preview_attribute='.$attributes['preview_attribute'][$j];
													$datapart .= '&data='.str_replace('&', '<und>', $data);
													$datapart .= '&selected_layer_id='.$attributes['subform_layer_id'][$j].'&embedded=true&fromobject=subform'.$layer['Layer_ID'].'_'.$k.'_'.$j.'&targetobject='.$attributes['name'][$j].'_'.$k.'&targetlayer_id='.$layer['Layer_ID'].'&targetattribute='.$attributes['name'][$j].'\', new Array(document.getElementById(\'subform'.$layer['Layer_ID'].'_'.$k.'_'.$j.'\')), new Array(\'sethtml\'));clearsubforms();">'.$strNewEmbeddedPK.'</a>';
													$datapart .= '<div style="display:inline" id="subform'.$layer['Layer_ID'].'_'.$k.'_'.$j.'"></div>';
												}
												else{
													$datapart .= '&nbsp;<a class="buttonlink"';
													if($attributes['no_new_window'][$j] != true){
														$datapart .= 	' target="_blank"';
													}
													$datapart .= ' href="javascript:overlay_link(\'go=neuer_Layer_Datensatz';
													for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
														$datapart .= '&attributenames['.$p.']='.$attributes['subform_pkeys'][$j][$p];
														$datapart .= '&values['.$p.']=\'+document.getElementById(\''.$attributes['subform_pkeys'][$j][$p].'_'.$k.'\').value+\'';
													}
													$datapart .= '&layer_id='.$layer['Layer_ID'];
													$datapart .= '&oid='.$dataset[$attributes['table_name'][$attributes['subform_pkeys'][$j][0]].'_oid'];			# die oid des Datensatzes und die Layer-ID wird mit übergeben, für evtl. Zoom auf den Datensatz
													$datapart .= '&tablename='.$attributes['table_name'][$attributes['the_geom']];											# dito
													$datapart .= '&columnname='.$attributes['the_geom'];																								# dito
													$datapart .= '&selected_layer_id='.$attributes['subform_layer_id'][$j].'\')">&nbsp;'.$strNewEmbeddedPK.'</a>';
												}
											}
										}
										$datapart .= '</td></tr></table>';
									}break;

									case 'Time': {
										$datapart .= '<input readonly style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px;"';
										$datapart .= ' size="'.$size.'" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$dataset[$attributes['name'][$j]].'">';
									}break;

									case 'Dokument': {
										if ($dataset[$attributes['name'][$j]]!='') {
											if($attributes['options'][$j] != ''){		# bei Layern die auf andere Server zugreifen, wird die URL des anderen Servers verwendet
												$url = $attributes['options'][$j];
											}
											else{
												$url = URL.APPLVERSION.'index.php?go=sendeDokument&dokument=';
											}
											$type = strtolower(array_pop(explode('.', $dataset[$attributes['name'][$j]])));
											$original_name = array_pop(explode('original_name=', $dataset[$attributes['name'][$j]]));
											$datapart .= '<table border="0"><tr><td>';
			  							if($type == 'jpg' OR $type == 'png' OR $type == 'gif' ){									
												$datapart .= '<a href="'.$url.$dataset[$attributes['name'][$j]].'"><img style="border:1px solid black" src="'.$url.$dataset[$attributes['name'][$j]].'&go_plus=mit_vorschau"></a>';									
											}else{
												$datapart .= '<a href="'.$url.$dataset[$attributes['name'][$j]].'"><img style="border:none" src="'.$url.$dataset[$attributes['name'][$j]].'&go_plus=mit_vorschau"></a>';
											}
			  							$datapart .= '</td><td width="100%">';
			  							if($attributes['privileg'][$j] != '0' AND !$lock[$k]){
			  								$datapart .= '<a href="javascript:delete_document(\''.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'\');">Dokument <br>löschen</a>';
			  							}
											$datapart .= '</td></tr>';
											$datapart .= '<tr><td colspan="2">'.$original_name.'</td></tr>';
											$datapart .= '</table>';
											$datapart .= '<input type="hidden" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].'_alt'.';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$dataset[$attributes['name'][$j]].'">';

										}
										if($attributes['privileg'][$j] != '0' AND !$lock[$k]){
											$datapart .= '<input onchange="set_changed_flag(currentform.changed_'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" style="font-size: '.$this->user->rolle->fontsize_gle.'px" size="43" type="file" onchange="this.title=this.value;" id="'.$attributes['name'][$j].'_'.$k.'" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'">';
										}
										else{
											$datapart .= '&nbsp;';
										}
									} break;

									case 'Link': {
										if ($dataset[$attributes['name'][$j]]!='') {
											if(substr($dataset[$attributes['name'][$j]], 0, 4) == 'http')$target = '_blank';
											$datapart .= '<a class="link" target="'.$target.'" style="font-size: '.$this->user->rolle->fontsize_gle.'px" href="'.$dataset[$attributes['name'][$j]].'">';
											if($attributes['options'][$j] != ''){
												$datapart .= $attributes['options'][$j];
											}
											else{
												$datapart .= basename($dataset[$attributes['name'][$j]]);
											}
											$datapart .= '</a><br>';
										}
										if($attributes['privileg'][$j] != '0' OR $lock[$k]){
											$datapart .= '<input onchange="set_changed_flag(currentform.changed_'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" style="font-size: '.$this->user->rolle->fontsize_gle.'px" size="'.$size.'" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.htmlspecialchars($dataset[$attributes['name'][$j]]).'">';
										}else{
											$datapart .= '<input type="hidden" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.htmlspecialchars($dataset[$attributes['name'][$j]]).'">';
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
												$alias = str_replace('$'.$explosion2[0], $dataset[$explosion2[0]], $alias);
											}
										}
										else{
											$alias = $href;
										}
										$explosion1 = explode('$', $href);
										for($d = 1; $d < count($explosion1); $d++){
											$explosion2 = explode('&', $explosion1[$d]);
											$href = str_replace('$'.$explosion2[0], $dataset[$explosion2[0]], $href);
										}
										if($explosion[2] == 'embedded'){
											$datapart .= '<a href="javascript:if(document.getElementById(\'dynamicLink'.$layer['Layer_ID'].'_'.$k.'_'.$j.'\').innerHTML != \'\'){clearsubform(\'dynamicLink'.$layer['Layer_ID'].'_'.$k.'_'.$j.'\');} else {ahah(\''.$href.'\', \'\', new Array(document.getElementById(\'dynamicLink'.$layer['Layer_ID'].'_'.$k.'_'.$j.'\')), new Array(\'sethtml\'))}">';
											$datapart .= $alias;
											$datapart .= '</a><br>';
											$datapart .= '<div style="display:inline" id="dynamicLink'.$layer['Layer_ID'].'_'.$k.'_'.$j.'"></div>';
										}
										else{
											$datapart .= '<a ';
											if($explosion[2] != 'no_new_window'){$datapart .= 'target="_blank"';}
											$datapart .= ' style="font-size: '.$this->user->rolle->fontsize_gle.'px" href="'.$href.'">';
											$datapart .= $alias;
											$datapart .= '</a><br>';
										}
									} break;
									
									case 'mailto': {
										if ($dataset[$attributes['name'][$j]]!='') {
											$datapart .= '<a class="link" target="_blank" style="font-size: '.$this->user->rolle->fontsize_gle.'px" href="mailto:'.$dataset[$attributes['name'][$j]].'">';
											if($attributes['options'][$j] != ''){
												$datapart .= $attributes['options'][$j];
											}
											else{
												$datapart .= basename($dataset[$attributes['name'][$j]]);
											}
											$datapart .= '</a><br>';
										}
										if($attributes['privileg'][$j] != '0' OR $lock[$k]){
											$datapart .= '<input onchange="set_changed_flag(currentform.changed_'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" style="font-size: '.$this->user->rolle->fontsize_gle.'px" size="'.$size.'" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.htmlspecialchars($dataset[$attributes['name'][$j]]).'">';
										}else{
											$datapart .= '<input type="hidden" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.htmlspecialchars($dataset[$attributes['name'][$j]]).'">';
										}
									} break;

									case 'Fläche': {
										$datapart .= '<input onchange="set_changed_flag(currentform.changed_'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" id="custom_area" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$attributes['alias'][$j].'" ';
										if($attributes['privileg'][$j] == '0' OR $lock[$k]){
											$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px;"';
										}
										else{
											$datapart .= ' style="font-size: '.$this->user->rolle->fontsize_gle.'px;"';
										}
										$datapart .= ' size="'.$size.'" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.htmlspecialchars($dataset[$attributes['name'][$j]]).'">';
									}break;
									
									case 'Länge': {
										$datapart .= '<input onchange="set_changed_flag(currentform.changed_'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" id="custom_length" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$attributes['alias'][$j].'" ';
										if($attributes['privileg'][$j] == '0' OR $lock[$k]){
											$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px;"';
										}
										else{
											$datapart .= ' style="font-size: '.$this->user->rolle->fontsize_gle.'px;"';
										}
										$datapart .= ' size="'.$size.'" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.htmlspecialchars($dataset[$attributes['name'][$j]]).'">';
									}break;
									
									case 'Zahl': {
										# bei Zahlen Tausendertrennzeichen einfügen 
										$value = tausenderTrenner($dataset[$attributes['name'][$j]]);
										$datapart .= '<input onchange="set_changed_flag(currentform.changed_'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$attributes['alias'][$j].'" ';
										if($attributes['privileg'][$j] == '0' OR $lock[$k]){
											$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px;"';
										}
										else{
											$datapart .= ' style="font-size: '.$this->user->rolle->fontsize_gle.'px;"';
										}
										if($attributes['name'][$j] == 'lock'){
											$datapart .= ' type="hidden"';
										}
										if($attributes['length'][$j] AND !in_array($attributes['type'][$j], array('numeric', 'float4', 'float8', 'int2', 'int4', 'int8'))){
											$datapart .= ' maxlength="'.$attributes['length'][$j].'"';
										}
										$datapart .= ' size="'.$size.'" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" id="'.$attributes['name'][$j].'_'.$k.'" value="'.htmlspecialchars($value).'">';
									}break;
									
									default : {
										$value = $dataset[$attributes['name'][$j]];
										$datapart .= '<input onchange="set_changed_flag(currentform.changed_'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$attributes['alias'][$j].'" ';
										if($attributes['privileg'][$j] == '0' OR $lock[$k]){
											$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$this->user->rolle->fontsize_gle.'px;"';
										}
										else{
											$datapart .= ' style="font-size: '.$this->user->rolle->fontsize_gle.'px;"';
										}
										if($attributes['name'][$j] == 'lock'){
											$datapart .= ' type="hidden"';
										}
										if($attributes['length'][$j] AND !in_array($attributes['type'][$j], array('numeric', 'float4', 'float8', 'int2', 'int4', 'int8'))){
											$datapart .= ' maxlength="'.$attributes['length'][$j].'"';
										}
										$datapart .= ' size="'.$size.'" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" id="'.$attributes['name'][$j].'_'.$k.'" value="'.htmlspecialchars($value).'">';
									}
								}
?>
