<?

	global $strShowPK;
	global $strNewPK;
	global $strShowFK;
	global $strShowAll;
	global $strNewEmbeddedPK;
	global $hover_preview;

	function attribute_name($layer_id, $attributes, $j, $k, $fontsize){
		$datapart .= '<table ';
		if($attributes['group'][0] != '' AND $attributes['position'][$j+1] != 'daneben' AND $attributes['position'][$j-1] != 'daneben' AND $attributes['position'][$j] != 'daneben')$datapart .= 'width="200px"';
		else $datapart .= 'width="100%"';
		$datapart .= '><tr style="border: none"><td>';
		if(!in_array($attributes['form_element_type'][$j], array('SubFormPK', 'SubFormEmbeddedPK', 'SubFormFK', 'dynamicLink'))){
			$datapart .= '<a style="font-size: '.$fontsize.'px" title="Sortieren nach '.$attributes['alias'][$j].'" href="javascript:change_orderby(\''.$attributes['name'][$j].'\', '.$layer_id.');">
							'.$attributes['alias'][$j].'</a>';
		}
		else{
			$datapart .= '<span style="font-size: '.$fontsize.'px; color:#222222;">'.$attributes['alias'][$j].'</span>';
		}
		if($attributes['nullable'][$j] == '0' AND $attributes['privileg'][$j] != '0'){
			$datapart .= '<span title="Eingabe erforderlich">*</span>';
		}
		if($attributes['tooltip'][$j]!='' AND $attributes['form_element_type'][$j] != 'Time'){
			if(substr($attributes['tooltip'][$j], 0, 4) == 'http')$title_link = 'href="'.$attributes['tooltip'][$j].'" target="_blank"';
			else $title_link = 'href="javascript:void(0);"';
			$datapart .= '<td align="right"><a '.$title_link.' title="'.$attributes['tooltip'][$j].'"><img src="'.GRAPHICSPATH.'emblem-important.png" border="0"></a></td>';
		}
		if($attributes['type'][$j] == 'date'){
			$datapart .= '<td align="right"><a href="javascript:;" title=" (TT.MM.JJJJ) '.$attributes['tooltip'][$j].'" ';
			if($attributes['privileg'][$j] == '1' AND !$lock[$k]){
				$datapart .= 'onclick="add_calendar(event, \''.$attributes['name'][$j].'_'.$k.'\');"';
			}
			$datapart .= '><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a><div id="calendar"><input type="hidden" id="calendar_'.$attributes['name'][$j].'_'.$k.'"></div></td>';
		}
		$datapart .= '</td></tr></table>';
		return $datapart;
	}

	function attribute_value(&$gui, $layer_id, $attributes, $j, $k, $dataset, $size, $select_width, $fontsize){
		global $strShowPK;
		global $strNewPK;
		global $strShowFK;
		global $strShowAll;
		global $strNewEmbeddedPK;
		global $hover_preview;
		# $dataset 																											# der aktuelle Datensatz
		$name = $attributes['name'][$j];																# der Name des Attributs
		$alias = $attributes['alias'][$j];															# der Aliasname des Attributs
		$value = $dataset[$name];																				# der Wert des Attributs
		$tablename = $attributes['table_name'][$name];									# der Tabellenname des Attributs
		$oid = $dataset[$tablename.'_oid'];															# die oid des Datensatzes
		$attribute_privileg = $attributes['privileg'][$j];							# das Recht des Attributs
		$fieldname = $layer_id.';'.$attributes['real_name'][$name].';'.$tablename.';'.$oid.';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j];

		if($attributes['position'][$j+1] == 'daneben' OR $attributes['position'][$j] == 'daneben')$size = 12;
		
		if($attributes['constraints'][$j] != '' AND !in_array($attributes['constraints'][$j], array('PRIMARY KEY', 'UNIQUE'))){
			if($attributes['privileg'][$j] == '0' OR $lock[$k]){
				$size1 = 1.3*strlen($dataset[$attributes['name'][$j]]);
				$datapart .= '<input readonly style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;" size="'.$size1.'" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$dataset[$attributes['name'][$j]].'">';
			}
			else{
				$datapart .= '<select id="'.$attributes['name'][$j].'_'.$k.'" onchange="set_changed_flag(currentform.changed_'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].')" title="'.$attributes['alias'][$j].'"  style="'.$select_width.'font-size: '.$fontsize.'px" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'">';
				for($e = 0; $e < count($attributes['enum_value'][$j]); $e++){
					$datapart .= '<option ';
					if($attributes['enum_value'][$j][$e] == $dataset[$attributes['name'][$j]]){
						$datapart .= 'selected ';
					}
					$datapart .= 'value="'.$attributes['enum_value'][$j][$e].'">'.$attributes['enum_output'][$j][$e].'</option>';
				}
				$datapart .= '</select>';
			}
		}
		else{
			switch ($attributes['form_element_type'][$j]){
				case 'Textfeld' : {
					$datapart .= '<textarea title="'.$alias.'" id="'.$name.'_'.$k.'" cols="45" onchange="set_changed_flag(currentform.changed_'.$layer_id.'_'.$oid.')"';
					if($attribute_privileg == '0' OR $lock[$k]){
						$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;"';
					}
					else{
						$datapart .= ' style="font-size: '.$fontsize.'px"';
					}
					$datapart .= ' rows="3" name="'.$fieldname.'">'.$value.'</textarea>';
					if($attribute_privileg > '0' AND $attributes['options'][$j] != ''){
						$datapart .= '&nbsp;<a title="automatisch generieren" href="javascript:auto_generate(new Array(\''.implode($attributes['name'], "','").'\'), \''.$attributes['the_geom'].'\', \''.$name.'\', '.$k.', '.$layer_id.');"><img src="'.GRAPHICSPATH.'autogen.png"></a>';
					}
				}break;

				case 'Auswahlfeld' : case 'Auswahlfeld_not_saveable' : {
					if(is_array($attributes['dependent_options'][$j])){
						$enum_value = $attributes['enum_value'][$j][$k];		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
						$enum_output = $attributes['enum_output'][$j][$k];		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
					}
					else{
						$enum_value = $attributes['enum_value'][$j];
						$enum_output = $attributes['enum_output'][$j];
					}
					if($attributes['nullable'][$j] != '0' OR $gui->new_entry == true)$strPleaseSelect = $gui->strPleaseSelect;
					$datapart .= Auswahlfeld($layer_id, $name, $j, $alias, $fieldname, $value, $enum_value, $enum_output, $attributes['req_by'][$j], $attributes['name'], $attribute_privileg, $k, $oid, $attributes['subform_layer_id'][$j], $attributes['subform_layer_privileg'][$j], $attributes['embedded'][$j], $lock[$k], $select_width, $fontsize, $strPleaseSelect);
				}break;
				
				case 'Autovervollständigungsfeld' : {
					$datapart .= Autovervollstaendigungsfeld($layer_id, $name, $j, $alias, $fieldname, $value, $attributes['enum_output'][$j][$k], $attribute_privileg, $k, $oid, $attributes['subform_layer_id'][$j], $attributes['subform_layer_privileg'][$j], $attributes['embedded'][$j], $lock[$k], $fontsize);
				}break;
				
				case 'Checkbox' : {
					$datapart .= '<input type="checkbox" title="'.$alias.'" cols="45" onchange="set_changed_flag(currentform.changed_'.$layer_id.'_'.$oid.')"';
					if($attribute_privileg == '0' OR $lock[$k]){
						$datapart .= ' onclick="return false" style="border:0px;background-color:transparent;"';
					}
					$datapart .= 'value="t" name="'.$fieldname.'"';
					if($value == 't')$datapart .= 'checked=true';
					$datapart .= '>';
				}break;

				case 'SubFormPK' : {
					$datapart .= '<input style="font-size: '.$fontsize.'px"';
					if($attribute_privileg == '0' OR $lock[$k]){
						$datapart .= ' readonly style="background-color:#e8e3da;"';
					}
					$datapart .= ' size="40" type="text" name="'.$fieldname.'" value="'.$value.'">';
					if($gui->new_entry != true){
						if($value != ''){
							$datapart .= '&nbsp;<a href="javascript:overlay_link(\'go=Layer-Suche_Suchen&selected_layer_id='.$attributes['subform_layer_id'][$j];
							for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
								$datapart .= '&value_'.$attributes['subform_pkeys'][$j][$p].'='.$dataset[$attributes['subform_pkeys'][$j][$p]];
								$datapart .= '&operator_'.$attributes['subform_pkeys'][$j][$p].'==';
							}
							$datapart .= 	'&subform_link=true\')"';
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
					$datapart .= '<table width="100%" cellpadding="0" cellspacing="0"><tr><td>';
					$attribute_foreign_keys = $attributes['subform_fkeys'][$j];	# die FKeys des aktuellen Attributes
					for($f = 0; $f < count($attribute_foreign_keys); $f++){											
						$name_ = $attribute_foreign_keys[$f];
						$tablename_ = $attributes['table_name'][$name_];
						$oid = $dataset[$tablename_.'_oid'];
						$index = $attributes['indizes'][$attribute_foreign_keys[$f]];
						$fieldname_[$f] = $layer_id.';'.$attributes['real_name'][$name_].';'.$tablename_.';'.$oid.';'.$attributes['form_element_type'][$index].';'.$attributes['nullable'][$index].';'.$attributes['type'][$index];
						if($dataset[$name_] == '')$dataset[$name_] = $gui->formvars[$fieldname_[$f]];
						switch ($attributes['form_element_type'][$attribute_foreign_keys[$f]]){
							case 'Autovervollständigungsfeld' : {
								if($attributes['subform_layer_privileg'][$index] != '0')$gui->editable = 'true';
								$datapart .= Autovervollstaendigungsfeld($layer_id, $name_, $index, $attributes['alias'][$name_], $fieldname_[$f], $dataset[$name_], $attributes['enum_output'][$index][$k], $attributes['privileg'][$name_], $k, $oid, $attributes['subform_layer_id'][$index], $attributes['subform_layer_privileg'][$index], $attributes['embedded'][$index], $lock[$k], $fontsize);
								$datapart .= '</td><td align="right" valign="top">';
							}break;
							case 'Auswahlfeld' : {
								if($attributes['subform_layer_privileg'][$index] != '0')$gui->editable = 'true';
								if(is_array($attributes['dependent_options'][$index])){
									$enum_value = $attributes['enum_value'][$index][$k];		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
									$enum_output = $attributes['enum_output'][$index][$k];		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
								}
								else{
									$enum_value = $attributes['enum_value'][$index];
									$enum_output = $attributes['enum_output'][$index];
								}
								if($attributes['nullable'][$index] != '0')$strPleaseSelect = $gui->strPleaseSelect;
								$datapart .= Auswahlfeld($layer_id, $name_, $j, $attributes['alias'][$name_], $fieldname_[$f], $dataset[$name_], $enum_value, $enum_output, $attributes['req_by'][$index], $attributes['name'], $attributes['privileg'][$name_], $k, $oid, $attributes['subform_layer_id'][$index], $attributes['subform_layer_privileg'][$index], $attributes['embedded'][$index], $lock[$k], $select_width, $fontsize, $strPleaseSelect);
								$datapart .= '</td><td align="right">';
							}break;
							default : {
								$datapart .= '<input style="font-size: '.(0.9*$fontsize).'px';
								if($attributes['privileg'][$name_] == '0' OR $lock[$k]){
									$datapart .= ';background-color:transparent;border:0px;display:none;background-color:#e8e3da;" readonly ';
								}
								else{
									'" ';
								}
								$datapart .= ' id="'.$attributes['real_name'][$name_].'_'.$k.'" name="'.$fieldname_[$f].'" value="'.$dataset[$name_].'">';
							}
						}
						$gui->form_field_names .= $fieldname_[$f].'|';
					}
					$datapart .= $value.' ';
					if($gui->new_entry != true){
						if($value != ''){
							$datapart .= '<a class="buttonlink" href="javascript:overlay_link(\'go=Layer-Suche_Suchen&selected_layer_id='.$attributes['subform_layer_id'][$j];
							for($f = 0; $f < count($attribute_foreign_keys); $f++){
								$datapart .= '&value_'.$attribute_foreign_keys[$f].'='.$dataset[$attribute_foreign_keys[$f]];
								$datapart .= '&operator_'.$attribute_foreign_keys[$f].'==';
							}
							$datapart .= 	'&subform_link=true\')"';
							if($attributes['no_new_window'][$j] != true){
								$datapart .= 	' target="_blank"';
							}
							$datapart .= 	' style="font-size: '.$fontsize.'px"><span>'.$strShowFK.'</span></a>';
						}
					}
					$datapart .= '</td></tr></table>';
				}break;

				case 'SubFormEmbeddedPK' : {
					$datapart .= '<div id="'.$layer_id.'_'.$name.'_'.$k.'"><img src="'.GRAPHICSPATH.'leer.gif" ';
					if($gui->new_entry != true AND $no_query != true){
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
										$datapart .= '&targetobject='.$layer_id.'_'.$name.'_'.$k.'&targetlayer_id='.$layer_id.'&targetattribute='.$name;
										$datapart .= '\', new Array(document.getElementById(\''.$layer_id.'_'.$name.'_'.$k.'\')), new Array(\'sethtml\'));
									"';
					}
					$datapart .= '></div><table width="98%" cellspacing="0" cellpadding="2"><tr style="border: none"><td width="100%" align="right">';
					$no_query = false;
					for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
						if($dataset[$attributes['subform_pkeys'][$j][$p]] == ''){
							$no_query = true;
						}
					}
					if($gui->new_entry != true AND $no_query != true){
						$datapart .= '<a id="show_all_'.$layer_id.'_'.$name.'_'.$k.'" style="font-size: '.$linksize.'px;display:none" class="buttonlink" href="javascript:overlay_link(\'go=Layer-Suche_Suchen&selected_layer_id='.$attributes['subform_layer_id'][$j];
						for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
							$datapart .= '&value_'.$attributes['subform_pkeys'][$j][$p].'='.$dataset[$attributes['subform_pkeys'][$j][$p]];
							$datapart .= '&operator_'.$attributes['subform_pkeys'][$j][$p].'==';
						}
						$datapart .= 	'&subform_link=true\')"><span>'.$strShowAll.'</span></a>';												
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
								$datapart .= '&selected_layer_id='.$attributes['subform_layer_id'][$j].'&embedded=true&fromobject=subform'.$layer_id.'_'.$k.'_'.$j.'&targetobject='.$layer_id.'_'.$name.'_'.$k.'&targetlayer_id='.$layer_id.'&targetattribute='.$name.'\', new Array(document.getElementById(\'subform'.$layer_id.'_'.$k.'_'.$j.'\')), new Array(\'sethtml\'));clearsubforms('.$attributes['subform_layer_id'][$j].');"><span>'.$strNewEmbeddedPK.'</span></a>';
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
					$datapart .= '<input readonly style="padding: 0 0 0 3;border:0px;background-color:transparent;font-size: '.$fontsize.'px;"';
					$datapart .= ' size="'.$size.'" type="text" name="'.$fieldname.'" value="'.$value.'">';
				}break;

				case 'Dokument': {
					if ($value!='') {
						$dokumentpfad = $value;
						$pfadteil = explode('&original_name=', $dokumentpfad);
						$dateiname = $pfadteil[0];
						$original_name = $pfadteil[1];
						$dateinamensteil=explode('.', $dateiname);
						$type = strtolower($dateinamensteil[1]);
						$thumbname = $gui->get_dokument_vorschau($dateinamensteil);
						$gui->allowed_documents[] = addslashes($dateiname);
						$gui->allowed_documents[] = addslashes($thumbname);
						if($attributes['options'][$j] != '' AND strtolower(substr($attributes['options'][$j], 0, 6)) != 'select'){		# bei Layern die auf andere Server zugreifen, wird die URL des anderen Servers verwendet
							$url = $attributes['options'][$j].$gui->document_loader_name.'?dokument=';
						}
						else{
							$url = IMAGEURL.$gui->document_loader_name.'?dokument=';
						}											
						$datapart .= '<table border="0"><tr><td>';
						if($hover_preview){
							$onmouseover='onmouseenter="document.getElementById(\'vorschau\').style.border=\'1px solid grey\';document.getElementById(\'preview_img\').src=this.src" onmouseleave="document.getElementById(\'vorschau\').style.border=\'none\';document.getElementById(\'preview_img\').src=\''.GRAPHICSPATH.'leer.gif\'"';
						}
						if(in_array($type, array('jpg', 'png', 'gif', 'tif', 'pdf')) ){
							$datapart .= '<a href="'.$url.$dokumentpfad.'"><img class="preview_image" src="'.$url.$thumbname.'" '.$onmouseover.'></a>';									
						}
						else{
							$datapart .= '<a href="'.$url.$dokumentpfad.'"><img class="preview_doc" src="'.$url.$thumbname.'"></a>';									
						}
						$datapart .= '</td><td>';
						if($attribute_privileg != '0' AND !$lock[$k]){
							$datapart .= '<a href="javascript:delete_document(\''.$fieldname.'\');"><span>Dokument <br>löschen</span></a>';
						}
						$datapart .= '</td></tr>';
						$datapart .= '<tr><td colspan="2"><span>'.$original_name.'</span></td></tr>';
						$datapart .= '</table>';
						$datapart .= '<input type="hidden" name="'.$layer_id.';'.$attributes['real_name'][$name].';'.$tablename.';'.$oid.';'.$attributes['form_element_type'][$j].'_alt'.';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$value.'">';
					}
					if($attribute_privileg != '0' AND !$lock[$k]){
						$datapart .= '<input onchange="set_changed_flag(currentform.changed_'.$layer_id.'_'.$oid.')" style="font-size: '.$fontsize.'px" size="43" type="file" onchange="this.title=this.value;" id="'.$name.'_'.$k.'" name="'.$fieldname.'">';
					}
					else{
						$datapart .= '&nbsp;';
					}
				} break;

				case 'Link': {
					if ($value!='') {
						if(substr($value, 0, 4) == 'http')$target = '_blank';
						$datapart .= '<a style="padding: 0 0 0 3;" class="link" target="'.$target.'" style="font-size: '.$fontsize.'px" href="'.$value.'">';
						if($attributes['options'][$j] != ''){
							$datapart .= $attributes['options'][$j];
						}
						else{
							$datapart .= basename($value);
						}
						$datapart .= '</a><br>';
					}
					if($attribute_privileg != '0' OR $lock[$k]){
						$datapart .= '<input onchange="set_changed_flag(currentform.changed_'.$layer_id.'_'.$oid.')" style="font-size: '.$fontsize.'px" size="'.$size.'" type="text" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
					}else{
						$datapart .= '<input type="hidden" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
					}
				} break;

				case 'dynamicLink': {
					$show_link = false;
					$options = $attributes['options'][$j];
					for($a = 0; $a < count($attributes['name']); $a++){
						if(strpos($options, '$'.$attributes['name'][$a]) !== false){
							$options = str_replace('$'.$attributes['name'][$a], $dataset[$attributes['name'][$a]], $options);
							if($dataset[$attributes['name'][$a]] != '')$show_link = true;
						}
					}
					$explosion = explode(';', $options);		# url;alias;embedded
					$href = $explosion[0];
					if($explosion[1] != ''){
						$alias = $explosion[1];
					}
					else{
						$alias = $href;
					}
					if($show_link){
						if($explosion[2] == 'embedded'){
							$datapart .= '<a style="padding: 0 0 0 3;" href="javascript:if(document.getElementById(\'dynamicLink'.$layer_id.'_'.$k.'_'.$j.'\').innerHTML != \'\'){clearsubform(\'dynamicLink'.$layer_id.'_'.$k.'_'.$j.'\');} else {ahah(\''.$href.'\', \'\', new Array(document.getElementById(\'dynamicLink'.$layer_id.'_'.$k.'_'.$j.'\')), new Array(\'sethtml\'))}">';
							$datapart .= $alias;
							$datapart .= '</a><br>';
							$datapart .= '<div style="display:inline" id="dynamicLink'.$layer_id.'_'.$k.'_'.$j.'"></div>';
						}
						else{
							$datapart .= '<a ';
							if($explosion[2] != 'no_new_window'){$datapart .= 'target="_blank"';}
							$datapart .= ' style="padding: 0 0 0 3;font-size: '.$fontsize.'px" href="'.$href.'">';
							$datapart .= $alias;
							$datapart .= '</a><br>';
						}
					}
				} break;
				
				case 'mailto': {
					if ($value!='') {
						$datapart .= '<a style="padding: 0 0 0 3;" class="link" target="_blank" style="font-size: '.$fontsize.'px" href="mailto:'.$value.'">';
						if($attributes['options'][$j] != ''){
							$datapart .= $attributes['options'][$j];
						}
						else{
							$datapart .= basename($value);
						}
						$datapart .= '</a><br>';
					}
					if($attribute_privileg != '0' OR $lock[$k]){
						$datapart .= '<input onchange="set_changed_flag(currentform.changed_'.$layer_id.'_'.$oid.')" style="font-size: '.$fontsize.'px" size="'.$size.'" type="text" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
					}else{
						$datapart .= '<input type="hidden" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
					}
				} break;

				case 'Fläche': {
					$datapart .= '<input onchange="set_changed_flag(currentform.changed_'.$layer_id.'_'.$oid.')" id="custom_area" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$alias.'" ';
					if($attribute_privileg == '0' OR $lock[$k]){
						$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;"';
					}
					else{
						$datapart .= ' style="font-size: '.$fontsize.'px;"';
					}
					$datapart .= ' size="'.$size.'" type="text" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
				}break;
				
				case 'Länge': {
					$datapart .= '<input onchange="set_changed_flag(currentform.changed_'.$layer_id.'_'.$oid.')" id="custom_length" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$alias.'" ';
					if($attribute_privileg == '0' OR $lock[$k]){
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
					$datapart .= '<input onchange="set_changed_flag(currentform.changed_'.$layer_id.'_'.$oid.')" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$alias.'" ';
					if($attribute_privileg == '0' OR $lock[$k]){
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
					$datapart .= '<input onchange="set_changed_flag(currentform.changed_'.$layer_id.'_'.$oid.')" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$alias.'" ';
					if($attribute_privileg == '0' OR $lock[$k]){
						$datapart .= ' readonly style="display:none;"';
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
					if($attribute_privileg == '0' OR $lock[$k]){ // nur lesbares Attribut
						if($size == 12){		// spaltenweise
							$datapart .= htmlspecialchars($value);
						}
						else{								// zeilenweise
							$maxwidth = $size * 9;
							$minwidth = $size * 7.1;
							$datapart .= '<div style="padding: 0 0 0 3; min-width: '.$minwidth.'px; max-width:'.$maxwidth.'px; font-size: '.$fontsize.'px;">'.htmlspecialchars($value).'</div>';
						}
					}
					if($attribute_privileg > '0' AND $attributes['options'][$j] != ''){
						$datapart .= '&nbsp;<a title="automatisch generieren" href="javascript:auto_generate(new Array(\''.implode($attributes['name'], "','").'\'), \''.$attributes['the_geom'].'\', \''.$name.'\', '.$k.', '.$layer_id.');"><img src="'.GRAPHICSPATH.'autogen.png"></a>';
					}
				}
			}
		}
		return $datapart;
	}

	function Autovervollstaendigungsfeld($layer_id, $name, $j, $alias, $fieldname, $value, $output, $privileg, $k, $oid, $subform_layer_id, $subform_layer_privileg, $embedded, $lock, $fontsize){
		$datapart = '<table cellpadding="0" cellspacing="0"><tr><td><div>';
		$datapart .= '<input autocomplete="off" title="'.$alias.'" onkeydown="if(this.backup_value==undefined){this.backup_value=this.value; document.getElementById(\''.$name.'_'.$k.'\').backup_value=document.getElementById(\''.$name.'_'.$k.'\').value;}" onkeyup="autocomplete1(\''.$layer_id.'\', \''.$name.'\', \''.$name.'_'.$k.'\', this.value);" onchange="if(document.getElementById(\'suggests_'.$name.'_'.$k.'\').style.display==\'block\'){this.value=this.backup_value; document.getElementById(\''.$name.'_'.$k.'\').value=document.getElementById(\''.$name.'_'.$k.'\').backup_value; setTimeout(function(){document.getElementById(\'suggests_'.$name.'_'.$k.'\').style.display = \'none\';}, 500);}if(\''.$oid.'\' != \'\')set_changed_flag(currentform.changed_'.$layer_id.'_'.$oid.')"';
		if($privileg == '0' OR $lock){
			$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;"';
		}
		else{
			$datapart .= ' style="font-size: '.$fontsize.'px;"';
		}
		$datapart .= ' size="40" type="text" id="'.$name.'_'.$k.'_output" value="'.htmlspecialchars($output).'">';
		$datapart .= '<input type="hidden" readonly name="'.$fieldname.'" id="'.$name.'_'.$k.'" value="'.htmlspecialchars($value).'">';
		$datapart .= '<div valign="top" style="height:0px; position:relative;">
				<div id="suggests_'.$name.'_'.$k.'" style="z-index: 3000;display:none; position:absolute; left:0px; top:0px; width: 400px; vertical-align:top; overflow:hidden; border:solid grey 1px;"></div>
			</div>
		</div>';
		
		if($subform_layer_id != ''){
			$datapart .= '</td><td>';
			if($subform_layer_privileg > 0){
				if($embedded == true){
					$datapart .= '&nbsp;&nbsp;<a class="buttonlink" href="javascript:ahah(\'index.php\', \'go=neuer_Layer_Datensatz&selected_layer_id='.$subform_layer_id.'&embedded=true&fromobject=subform'.$layer_id.'_'.$k.'_'.$j.'&targetobject='.$name.'_'.$k.'&targetlayer_id='.$layer_id.'&targetattribute='.$name.'\', new Array(document.getElementById(\'subform'.$layer_id.'_'.$k.'_'.$j.'\')), new Array(\'sethtml\'));">&nbsp;neu&nbsp;</a>';
					$datapart .= '</td></tr><tr><td><div style="display:inline" id="subform'.$layer_id.'_'.$k.'_'.$j.'"></div>';
				}
				else{
					$datapart .= '&nbsp;&nbsp;<a class="buttonlink" href="index.php?go=neuer_Layer_Datensatz&selected_layer_id='.$subform_layer_id.'">&nbsp;neu&nbsp;</a>';
				}
			}
		}
		$datapart .= '</td></tr></table>';
		return $datapart;
	}
	
	function Auswahlfeld($layer_id, $name, $j, $alias, $fieldname, $value, $enum_value, $enum_output, $req_by, $attributenames, $privileg, $k, $oid, $subform_layer_id, $subform_layer_privileg, $embedded, $lock, $select_width, $fontsize, $strPleaseSelect){
		if($privileg == '0' OR $lock){
			for($e = 0; $e < count($enum_value); $e++){
				if($enum_value[$e] == $value){
					$auswahlfeld_output = $enum_output[$e];
					$auswahlfeld_output_laenge=strlen($auswahlfeld_output)+1;
					break;
				}
			}
			$datapart .= '<input readonly id="'.$name.'_'.$k.'" style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;" size="'.$auswahlfeld_output_laenge.'" type="text" name="'.$fieldname.'" value="'.$auswahlfeld_output.'">';
			$auswahlfeld_output = '';
			$auswahlfeld_output_laenge = '';
		}
		else{
			$datapart .= '<select title="'.$alias.'" style="'.$select_width.'font-size: '.$fontsize.'px"';
			if($req_by != ''){
				$datapart .= 'onchange="update_require_attribute(\''.$req_by.'\', '.$k.','.$layer_id.', new Array(\''.implode($attributenames, "','").'\'));set_changed_flag(currentform.changed_'.$layer_id.'_'.$oid.')" ';
			}
			else{
				$datapart .= 'onchange="set_changed_flag(currentform.changed_'.$layer_id.'_'.$oid.')"';
			}
			$datapart .= 'id="'.$name.'_'.$k.'" name="'.$fieldname.'">';
			if($strPleaseSelect)$datapart .= '<option value="">-- '.$strPleaseSelect.' --</option>';
			for($e = 0; $e < count($enum_value); $e++){
				$datapart .= '<option ';
				if($enum_value[$e] == $value){
					$datapart .= 'selected ';
				}
				$datapart .= 'value="'.$enum_value[$e].'">'.$enum_output[$e].'</option>';
			}
			$datapart .= '</select>';
			if($subform_layer_id != ''){
				if($subform_layer_privileg > 0){
					if($embedded == true){
						$datapart .= '&nbsp;&nbsp;<a class="buttonlink" href="javascript:ahah(\'index.php\', \'go=neuer_Layer_Datensatz&selected_layer_id='.$subform_layer_id.'&embedded=true&fromobject=subform'.$layer_id.'_'.$k.'_'.$j.'&targetobject='.$name.'_'.$k.'&targetlayer_id='.$layer_id.'&targetattribute='.$name.'\', new Array(document.getElementById(\'subform'.$layer_id.'_'.$k.'_'.$j.'\')), new Array(\'sethtml\'));">&nbsp;neu&nbsp;</a>';
						$datapart .= '<div style="display:inline" id="subform'.$layer_id.'_'.$k.'_'.$j.'"></div>';
					}
					else{
						$datapart .= '&nbsp;&nbsp;<a class="buttonlink" target="_blank" href="index.php?go=neuer_Layer_Datensatz&selected_layer_id='.$subform_layer_id.'">&nbsp;neu&nbsp;</a>';
					}
				}
			}
		}
		return $datapart;
	}

?>