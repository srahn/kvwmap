<?
								$fontsize = $this->user->rolle->fontsize_gle;
								$layer_id = $layer['Layer_ID'];																	# die Layer-ID
								$dataset = $layer['shape'][$k];																	# der aktuelle Datensatz
								$name = $attributes['name'][$j];																# der Name des Attributs
								$alias = $attributes['alias'][$j];															# der Aliasname des Attributs
								$value = $dataset[$name];																				# der Wert des Attributs
								$tablename = $attributes['table_name'][$name];									# der Tabellenname des Attributs
								$oid = $dataset[$tablename.'_oid'];															# die oid des Datensatzes
								$privileg = $attributes['privileg'][$j];												# das Recht des Attributs
								$fieldname = $layer_id.';'.$attributes['real_name'][$name].';'.$tablename.';'.$oid.';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j];
								switch ($attributes['form_element_type'][$j]){
									case 'Textfeld' : {
										$datapart .= '<textarea title="'.$alias.'" cols="45" onchange="set_changed_flag(currentform.changed_'.$oid.')"';
										if($privileg == '0' OR $lock[$k]){
											$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;"';
										}
										else{
											$datapart .= ' style="font-size: '.$fontsize.'px"';
										}
										$datapart .= ' rows="3" name="'.$fieldname.'">'.$value.'</textarea>';
									}break;

									case 'Auswahlfeld' : case 'Auswahlfeld_not_saveable' : {
										if($privileg == '0' OR $lock[$k]){
										  if(is_array($attributes['dependent_options'][$j])){		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
												for($e = 0; $e < count($attributes['enum_value'][$j][$k]); $e++){
													if($attributes['enum_value'][$j][$k][$e] == $value){
														$auswahlfeld_output = $attributes['enum_output'][$j][$k][$e];
														$auswahlfeld_output_laenge=strlen($auswahlfeld_output)+1;
														break;
													}
												}
											}
											else{
												for($e = 0; $e < count($attributes['enum_value'][$j]); $e++){
													if($attributes['enum_value'][$j][$e] == $value){
														$auswahlfeld_output = $attributes['enum_output'][$j][$e];
														$auswahlfeld_output_laenge=strlen($auswahlfeld_output)+1;
														break;
													}
												}
											}
                      $datapart .= '<input readonly id="'.$name.'_'.$k.'" style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;" size="'.$auswahlfeld_output_laenge.'" type="text" name="'.$fieldname.'" value="'.$auswahlfeld_output.'">';
                      $auswahlfeld_output = '';
                      $auswahlfeld_output_laenge = '';
										}
										else{
											$datapart .= '<select title="'.$alias.'" style="'.$select_width.'font-size: '.$fontsize.'px"';
											if($attributes['req_by'][$j] != ''){
												$datapart .= 'onchange="update_require_attribute(\''.$attributes['req_by'][$j].'\', '.$k.','.$layer_id.', this.value);set_changed_flag(currentform.changed_'.$oid.')" ';
											}
											else{
												$datapart .= 'onchange="set_changed_flag(currentform.changed_'.$oid.')"';
											}
											$datapart .= 'id="'.$name.'_'.$k.'" name="'.$fieldname.'">';
											$datapart .= '<option value="">-- '.$this->strPleaseSelect.' --</option>';
											if(is_array($attributes['dependent_options'][$j])){		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
												for($e = 0; $e < count($attributes['enum_value'][$j][$k]); $e++){
													$datapart .= '<option ';
													if($attributes['enum_value'][$j][$k][$e] == $value OR ($attributes['enum_value'][$j][$k][$e] != '' AND $attributes['enum_value'][$j][$k][$e] == $this->formvars[$layer_id.';'.$attributes['real_name'][$name].';'.$tablename.';'.$oid.';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j]])){
														$datapart .= 'selected ';
													}
													$datapart .= 'value="'.$attributes['enum_value'][$j][$k][$e].'">'.$attributes['enum_output'][$j][$k][$e].'</option>';
												}
											}
											else{
												for($e = 0; $e < count($attributes['enum_value'][$j]); $e++){
													$datapart .= '<option ';
													if($attributes['enum_value'][$j][$e] == $value OR ($attributes['enum_value'][$j][$e] != '' AND $attributes['enum_value'][$j][$e] == $this->formvars[$layer_id.';'.$attributes['real_name'][$name].';'.$tablename.';'.$oid.';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j]])){
														$datapart .= 'selected ';
													}
													$datapart .= 'value="'.$attributes['enum_value'][$j][$e].'">'.$attributes['enum_output'][$j][$e].'</option>';
												}
											}
											$datapart .= '</select>';
											if($attributes['subform_layer_id'][$j] != ''){
												if($attributes['subform_layer_privileg'][$j] > 0){
													if($attributes['embedded'][$j] == true){
														$datapart .= '<a href="javascript:ahah(\'index.php\', \'go=neuer_Layer_Datensatz&selected_layer_id='.$attributes['subform_layer_id'][$j].'&embedded=true&fromobject=subform'.$layer_id.'_'.$k.'_'.$j.'&targetobject='.$name.'_'.$k.'&targetlayer_id='.$layer_id.'&targetattribute='.$name.'\', new Array(document.getElementById(\'subform'.$layer_id.'_'.$k.'_'.$j.'\')), new Array(\'sethtml\'));clearsubforms('.$layer_id.');">&nbsp;neu&nbsp;</a>';
														$datapart .= '<div style="display:inline" id="subform'.$layer_id.'_'.$k.'_'.$j.'"></div>';
													}
													else{
														$datapart .= '<a target="_blank" href="index.php?go=neuer_Layer_Datensatz&selected_layer_id='.$attributes['subform_layer_id'][$j].'">&nbsp;neu&nbsp;</a>';
													}
												}
											}
										}
									}break;
									
									case 'Autovervollständigungsfeld' : {
										$datapart .= Autovervollstaendigungsfeld($layer_id, $name, $alias, $fieldname, $value, $privileg, $k, $oid, $lock[$k], $fontsize);
									}break;
									
									case 'Checkbox' : {
										$datapart .= '<input type="checkbox" title="'.$alias.'" cols="45" onchange="set_changed_flag(currentform.changed_'.$oid.')"';
										if($privileg == '0' OR $lock[$k]){
											$datapart .= ' onclick="return false" style="border:0px;background-color:transparent;"';
										}
										$datapart .= 'value="t" name="'.$fieldname.'"';
										if($value == 't')$datapart .= 'checked=true';
										$datapart .= '>';
									}break;

									case 'SubFormPK' : {
										$datapart .= '<input style="font-size: '.$fontsize.'px"';
										if($privileg == '0' OR $lock[$k]){
											$datapart .= ' readonly style="background-color:#e8e3da;"';
										}
										$datapart .= ' size="40" type="text" name="'.$fieldname.'" value="'.$value.'">';
										if($this->new_entry != true){
											if($value != ''){
												$datapart .= '&nbsp;<a href="javascript:overlay_link(\'go=Layer-Suche_Suchen&selected_layer_id='.$attributes['subform_layer_id'][$j];
												for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
													$datapart .= '&value_'.$attributes['subform_pkeys'][$j][$p].'='.$dataset[$attributes['subform_pkeys'][$j][$p]];
													$datapart .= '&operator_'.$attributes['subform_pkeys'][$j][$p].'==';
												}
												$datapart .= 	'\')"';
												if($attributes['no_new_window'][$j] != true){
													$datapart .= 	' target="_blank"';
												}
												$datapart .= 	' class="buttonlink"><span>'.$strShowPK.'</span></a>&nbsp;';
											}
											if($attributes['subform_layer_privileg'][$j] > 0){
												$datapart .= '<a href="" onclick="this.href=\'index.php?go=neuer_Layer_Datensatz&selected_layer_id='.$attributes['subform_layer_id'][$j];
												for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
													$datapart .= '&attributenames['.$p.']='.$attributes['subform_pkeys'][$j][$p];
													$datapart .= '&values['.$p.']=\'+document.getElementById(\''.$attributes['subform_pkeys'][$j][$p].'_'.$k.'\').value';
												}
												$datapart .= 	'"';
												if($attributes['no_new_window'][$j] != true){
													$datapart .= 	' target="_blank"';
												}
												$datapart .= 	' class="buttonlink"><span>'.$strNewPK.'</span></a>&nbsp;';
											}
										}
									}break;

									case 'SubFormFK' : {
										$attribute_foreign_keys = $attributes['subform_fkeys'][$j];	# die FKeys des aktuellen Attributes
										for($f = 0; $f < count($attribute_foreign_keys); $f++){											
											$name_ = $attribute_foreign_keys[$f];
											$tablename_ = $attributes['table_name'][$name_];
											$oid = $dataset[$tablename_.'_oid'];
											if($attributes['form_element_type'][$attribute_foreign_keys[$f]] == 'Autovervollständigungsfeld'){
												$fieldname_[$f] = $layer_id.';'.$attributes['real_name'][$name_].';'.$tablename_.';'.$oid.';Autovervollständigungsfeld;0;varchar';
												if($dataset[$name_] == '')$dataset[$name_] = $this->formvars[$fieldname_[$f]];
												$datapart .= Autovervollstaendigungsfeld($layer_id, $name_, $attributes['alias'][$name_], $fieldname_[$f], $dataset[$name_], $attributes['privileg'][$name_], $k, $oid, $lock[$k], $fontsize);
											}
											else{
												$datapart .= '<input style="font-size: '.(0.9*$fontsize).'px';
												if($attributes['privileg'][$name_] == '0' OR $lock[$k]){
													$datapart .= ';background-color:transparent;border:0px;display:none;background-color:#e8e3da;" readonly ';
												}
												else{
													'" ';
												}
												$fieldname_[$f] = $layer_id.';'.$attributes['real_name'][$name_].';'.$tablename_.';'.$oid.';TextFK;0;varchar';
												if($dataset[$name_] == '')$dataset[$name_] = $this->formvars[$fieldname_[$f]];
												$datapart .= ' id="'.$attributes['real_name'][$name_].'_'.$k.'" name="'.$fieldname_[$f].'" value="'.$dataset[$name_].'">';
											}
											$this->form_field_names .= $fieldname_[$f].'|';
										}
										$size = $size - 10;
										$datapart .= '<input size="'.$size.'" style="border:0px;background-color:transparent;font-size: '.$fontsize.'px"';
										$size = $size + 10;
										if($privileg == '0' OR $lock[$k]){
											$datapart .= ' readonly style="background-color:#e8e3da;"';
										}
										$datapart .= ' type="text" name="'.$fieldname.'" value="'.$value.'">';
										if($this->new_entry != true){
											if($value != ''){
												$datapart .= '<a class="buttonlink" href="javascript:overlay_link(\'go=Layer-Suche_Suchen&selected_layer_id='.$attributes['subform_layer_id'][$j];
												for($f = 0; $f < count($attribute_foreign_keys); $f++){
													$datapart .= '&value_'.$attribute_foreign_keys[$f].'='.$dataset[$attribute_foreign_keys[$f]];
													$datapart .= '&operator_'.$attribute_foreign_keys[$f].'==';
												}
												$datapart .= 	'\')"';
												if($attributes['no_new_window'][$j] != true){
													$datapart .= 	' target="_blank"';
												}
												$datapart .= 	' style="font-size: '.$fontsize.'px"><span>'.$strShowFK.'</span></a>';
											}
										}
									}break;

									case 'SubFormEmbeddedPK' : {
										$datapart .= '<div id="'.$name.'_'.$k.'"><img src="'.GRAPHICSPATH.'leer.gif" ';
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
															$datapart .= '&targetobject='.$name.'_'.$k.'&targetlayer_id='.$layer_id.'&targetattribute='.$name;
															$datapart .= '\', new Array(document.getElementById(\''.$name.'_'.$k.'\')), new Array(\'sethtml\'));
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
											$datapart .= '<a id="show_all_'.$name.'_'.$k.'" style="font-size: '.$linksize.'px;display:none" class="buttonlink" href="javascript:overlay_link(\'go=Layer-Suche_Suchen&selected_layer_id='.$attributes['subform_layer_id'][$j];
											for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
												$datapart .= '&value_'.$attributes['subform_pkeys'][$j][$p].'='.$dataset[$attributes['subform_pkeys'][$j][$p]];
												$datapart .= '&operator_'.$attributes['subform_pkeys'][$j][$p].'==';
											}
											$datapart .= 	'\')"><span>'.$strShowAll.'</span></a>';												
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
													$datapart .= '&selected_layer_id='.$attributes['subform_layer_id'][$j].'&embedded=true&fromobject=subform'.$layer_id.'_'.$k.'_'.$j.'&targetobject='.$name.'_'.$k.'&targetlayer_id='.$layer_id.'&targetattribute='.$name.'\', new Array(document.getElementById(\'subform'.$layer_id.'_'.$k.'_'.$j.'\')), new Array(\'sethtml\'));clearsubforms('.$attributes['subform_layer_id'][$j].');"><span>'.$strNewEmbeddedPK.'</span></a>';
													$datapart .= '<div style="display:inline" id="subform'.$layer_id.'_'.$k.'_'.$j.'"></div>';
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
													$datapart .= '&layer_id='.$layer_id;
													$datapart .= '&oid='.$dataset[$attributes['table_name'][$attributes['subform_pkeys'][$j][0]].'_oid'];			# die oid des Datensatzes und die Layer-ID wird mit übergeben, für evtl. Zoom auf den Datensatz
													$datapart .= '&tablename='.$attributes['table_name'][$attributes['the_geom']];											# dito
													$datapart .= '&columnname='.$attributes['the_geom'];																								# dito
													$datapart .= '&selected_layer_id='.$attributes['subform_layer_id'][$j].'\')"><span>&nbsp;'.$strNewEmbeddedPK.'</span></a>';
												}
											}
										}
										$datapart .= '</td></tr></table>';
									}break;

									case 'Time': {
										$datapart .= '<input readonly style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;"';
										$datapart .= ' size="'.$size.'" type="text" name="'.$fieldname.'" value="'.$value.'">';
									}break;

									case 'Dokument': {
										if ($value!='') {
											$dokumentpfad = $value;
											$pfadteil = explode('&original_name=', $dokumentpfad);
											$dateiname = $pfadteil[0];
											$original_name = $pfadteil[1];
											$dateinamensteil=explode('.', $dateiname);
											$type = $dateinamensteil[1];
											$thumbname = $this->get_dokument_vorschau($dateinamensteil);
											$this->allowed_documents[] = addslashes($dateiname);
											$this->allowed_documents[] = addslashes($thumbname);
											if($attributes['options'][$j] != '' AND strtolower(substr($attributes['options'][$j], 0, 6)) != 'select'){		# bei Layern die auf andere Server zugreifen, wird die URL des anderen Servers verwendet
												$url = $attributes['options'][$j].session_id().'.php?dokument=';
											}
											else{
												$url = IMAGEURL.session_id().'.php?dokument=';
											}											
											$datapart .= '<table border="0"><tr><td>';
											if($type == 'jpg' OR $type == 'png' OR $type == 'gif' OR $type == 'pdf' ){
												$datapart .= '<a href="'.$url.$dokumentpfad.'"><img class="preview_image" src="'.$url.$thumbname.'"></a>';									
											}
											else{
												$datapart .= '<a href="'.$url.$dokumentpfad.'"><img class="preview_doc" src="'.$url.$thumbname.'"></a>';									
											}
			  							$datapart .= '</td><td width="100%">';
			  							if($privileg != '0' AND !$lock[$k]){
			  								$datapart .= '<a href="javascript:delete_document(\''.$fieldname.'\');"><span>Dokument <br>löschen</span></a>';
			  							}
											$datapart .= '</td></tr>';
											$datapart .= '<tr><td colspan="2"><span>'.$original_name.'</span></td></tr>';
											$datapart .= '</table>';
											$datapart .= '<input type="hidden" name="'.$layer_id.';'.$attributes['real_name'][$name].';'.$tablename.';'.$oid.';'.$attributes['form_element_type'][$j].'_alt'.';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$value.'">';
										}
										if($privileg != '0' AND !$lock[$k]){
											$datapart .= '<input onchange="set_changed_flag(currentform.changed_'.$oid.')" style="font-size: '.$fontsize.'px" size="43" type="file" onchange="this.title=this.value;" id="'.$name.'_'.$k.'" name="'.$fieldname.'">';
										}
										else{
											$datapart .= '&nbsp;';
										}
									} break;

									case 'Link': {
										if ($value!='') {
											if(substr($value, 0, 4) == 'http')$target = '_blank';
											$datapart .= '<a class="link" target="'.$target.'" style="font-size: '.$fontsize.'px" href="'.$value.'">';
											if($attributes['options'][$j] != ''){
												$datapart .= $attributes['options'][$j];
											}
											else{
												$datapart .= basename($value);
											}
											$datapart .= '</a><br>';
										}
										if($privileg != '0' OR $lock[$k]){
											$datapart .= '<input onchange="set_changed_flag(currentform.changed_'.$oid.')" style="font-size: '.$fontsize.'px" size="'.$size.'" type="text" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
										}else{
											$datapart .= '<input type="hidden" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
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
											$datapart .= '<a href="javascript:if(document.getElementById(\'dynamicLink'.$layer_id.'_'.$k.'_'.$j.'\').innerHTML != \'\'){clearsubform(\'dynamicLink'.$layer_id.'_'.$k.'_'.$j.'\');} else {ahah(\''.$href.'\', \'\', new Array(document.getElementById(\'dynamicLink'.$layer_id.'_'.$k.'_'.$j.'\')), new Array(\'sethtml\'))}">';
											$datapart .= $alias;
											$datapart .= '</a><br>';
											$datapart .= '<div style="display:inline" id="dynamicLink'.$layer_id.'_'.$k.'_'.$j.'"></div>';
										}
										else{
											$datapart .= '<a ';
											if($explosion[2] != 'no_new_window'){$datapart .= 'target="_blank"';}
											$datapart .= ' style="font-size: '.$fontsize.'px" href="'.$href.'">';
											$datapart .= $alias;
											$datapart .= '</a><br>';
										}
									} break;
									
									case 'mailto': {
										if ($value!='') {
											$datapart .= '<a class="link" target="_blank" style="font-size: '.$fontsize.'px" href="mailto:'.$value.'">';
											if($attributes['options'][$j] != ''){
												$datapart .= $attributes['options'][$j];
											}
											else{
												$datapart .= basename($value);
											}
											$datapart .= '</a><br>';
										}
										if($privileg != '0' OR $lock[$k]){
											$datapart .= '<input onchange="set_changed_flag(currentform.changed_'.$oid.')" style="font-size: '.$fontsize.'px" size="'.$size.'" type="text" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
										}else{
											$datapart .= '<input type="hidden" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
										}
									} break;

									case 'Fläche': {
										$datapart .= '<input onchange="set_changed_flag(currentform.changed_'.$oid.')" id="custom_area" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$alias.'" ';
										if($privileg == '0' OR $lock[$k]){
											$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;"';
										}
										else{
											$datapart .= ' style="font-size: '.$fontsize.'px;"';
										}
										$datapart .= ' size="'.$size.'" type="text" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
									}break;
									
									case 'Länge': {
										$datapart .= '<input onchange="set_changed_flag(currentform.changed_'.$oid.')" id="custom_length" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$alias.'" ';
										if($privileg == '0' OR $lock[$k]){
											$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;"';
										}
										else{
											$datapart .= ' style="font-size: '.$fontsize.'px;"';
										}
										$datapart .= ' size="'.$size.'" type="text" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
									}break;
									
									case 'Zahl': {
										# bei Zahlen Tausendertrennzeichen einfügen 
										$value = tausenderTrenner($value);
										$datapart .= '<input onchange="set_changed_flag(currentform.changed_'.$oid.')" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$alias.'" ';
										if($privileg == '0' OR $lock[$k]){
											$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;"';
										}
										else{
											$datapart .= ' style="font-size: '.$fontsize.'px;"';
										}
										if($name == 'lock'){
											$datapart .= ' type="hidden"';
										}
										if($attributes['length'][$j] AND !in_array($attributes['type'][$j], array('numeric', 'float4', 'float8', 'int2', 'int4', 'int8'))){
											$datapart .= ' maxlength="'.$attributes['length'][$j].'"';
										}
										$datapart .= ' size="'.$size.'" type="text" name="'.$fieldname.'" id="'.$name.'_'.$k.'" value="'.htmlspecialchars($value).'">';
									}break;
									
									default : {
										$datapart .= '<input onchange="set_changed_flag(currentform.changed_'.$oid.')" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$alias.'" ';
										if($privileg == '0' OR $lock[$k]){
											$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;"';
										}
										else{
											$datapart .= ' style="font-size: '.$fontsize.'px;"';
										}
										if($name == 'lock'){
											$datapart .= ' type="hidden"';
										}
										if($attributes['length'][$j] AND !in_array($attributes['type'][$j], array('numeric', 'float4', 'float8', 'int2', 'int4', 'int8'))){
											$datapart .= ' maxlength="'.$attributes['length'][$j].'"';
										}
										$datapart .= ' size="'.$size.'" type="text" name="'.$fieldname.'" id="'.$name.'_'.$k.'" value="'.htmlspecialchars($value).'">';
										if($privileg > '0' AND $attributes['options'][$j] != ''){
											$datapart .= '&nbsp;<a title="automatisch generieren" href="javascript:auto_generate(new Array(\''.implode($attributes['name'], "','").'\'), \''.$attributes['the_geom'].'\', \''.$name.'\', '.$k.', '.$layer_id.');"><img src="'.GRAPHICSPATH.'autogen.png"></a>';
										}
									}
								}
?>
