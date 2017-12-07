<?

	global $strShowPK;
	global $strNewPK;
	global $strShowFK;
	global $strShowAll;
	global $strNewEmbeddedPK;
	global $hover_preview;

	function attribute_name($layer_id, $attributes, $j, $k, $fontsize, $sort_links = true){
		$datapart .= '<table ';
		if($attributes['group'][0] != '' AND $attributes['arrangement'][$j+1] != 1 AND $attributes['arrangement'][$j-1] != 1 AND $attributes['arrangement'][$j] != 1)$datapart .= 'width="200px"';
		else $datapart .= 'width="100%"';
		$datapart .= '><tr style="border: none"><td>';
		if($sort_links AND !in_array($attributes['form_element_type'][$j], array('SubFormPK', 'SubFormEmbeddedPK', 'SubFormFK', 'dynamicLink'))){
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
		if($attributes['type'][$j] == 'date') {
			$datapart .= '
				<td align="right">
						<a id="caldbl" href="javascript:;" title=" (TT.MM.JJJJ) ' . $attributes['tooltip'][$j] . '"
						onclick="$(\'.calendar\').show();' . (($attributes['privileg'][$j] == '1' AND !$lock[$k]) ? 'add_calendar(event, \''.$layer_id.'_'.$attributes['name'][$j].'_'.$k.'\');' : '').'"
						ondblclick="$(\'.calendar\').hide(); $(\'#' . $layer_id.'_'.$attributes['name'][$j].'_'.$k.'\').val(\'' . date('d.m.Y') . '\')"
					><img src="' . GRAPHICSPATH . 'calendarsheet.png" border="0"></a>
					<div id="calendar" class="calendar">
						<input type="hidden" id="calendar_'.$layer_id.'_'.$attributes['name'][$j] . '_' . $k . '">
					</div>
				</td>
			';
		}
		$datapart .= '</td></tr></table>';
		return $datapart;
	}

	function attribute_value(&$gui, $layer_id, $attributes, $j, $k, $dataset, $size, $select_width, $fontsize, $change_all = false, $onchange = NULL, $field_name = NULL, $field_id = NULL){
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
		if($field_name == NULL)$fieldname = $layer_id.';'.$attributes['real_name'][$name].';'.$tablename.';'.$oid.';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j];
		else $fieldname = $field_name;
				
		if(!$change_all){
			$onchange .= 'set_changed_flag(currentform.changed_'.$layer_id.'_'.$oid.');';
		}
		else{
			$onchange .= 'change_all('.$layer_id.', '.$k.', \''.$layer_id.'_'.$name.'\');';
		}
		
		###### Array-Typ #####
		if(POSTGRESVERSION >= 930 AND substr($attributes['type'][$j], 0, 1) == '_'){
			if($field_id != NULL)$id = $field_id;		# wenn field_id übergeben wurde (nicht die oberste Ebene)
			else $id = $k.'_'.$name;	# oberste Ebene
			$datapart .= '<input type="hidden" title="'.$alias.'" name="'.$fieldname.'" id="'.$id.'" onchange="'.$onchange.'" value="'.htmlspecialchars($value).'">';
			$datapart .= '<div id="'.$id.'_elements" style="">';
			$elements = json_decode($value);		# diese Funktion decodiert immer den kommpletten String
			$attributes2 = $attributes;
			$attributes2['name'][$j] = '';
			$attributes2['table_name'][$attributes2['name'][$j]] = $tablename;
			$attributes2['type'][$j] = substr($attributes['type'][$j], 1);			
			$dataset2[$tablename.'_oid'] = $oid;
			$elements_fieldname = $id;
			$onchange2 = 'buildJSONString(\''.$id.'\', true);';
			for($e = -1; $e < count($elements); $e++){
				if(is_array($elements[$e]) OR is_object($elements[$e]))$elements[$e] = json_encode($elements[$e]);		# ist ein Array oder Objekt (also entweder ein Array-Typ oder ein Datentyp) und wird zur Übertragung wieder encodiert
				$dataset2[$attributes2['name'][$j]] = $elements[$e];
				$datapart .= '<div id="div_'.$id.'_'.$e.'" style="display: '.($e==-1 ? 'none' : 'block').'"><table cellpadding="0" cellspacing="0"><tr><td>';
				$datapart .= attribute_value($gui, $layer_id, $attributes2, $j, $k, $dataset2, $size, $select_width, $fontsize, $change_all, $onchange2, $elements_fieldname, $elements_fieldname.'_'.$e);
				$datapart .= '</td>';
				if($attributes['privileg'][$j] == '1' AND !$lock[$k]){
					$datapart .= '<td valign="top"><a href="#" onclick="removeArrayElement(\''.$id.'\', \'div_'.$id.'_'.$e.'\');'.$onchange2.'return false;"><img style="width: 18px" src="'.GRAPHICSPATH.'datensatz_loeschen.png"></a></td>';
				}
				$datapart .= '</tr></table>';
				$datapart .= '</div>';
			}
			$datapart .= '</div>';
			if($attributes['privileg'][$j] == '1' AND !$lock[$k]){
				$datapart .= '<div style="padding: 3px 10px 3px 3px;float: right"><a href="javascript:addArrayElement(\''.$id.'\', \''.$attributes['form_element_type'][$j].'\', \''.$oid.'\')" class="buttonlink"><span>'.$strNewEmbeddedPK.'</span></a></div>';
			}
			return $datapart;
		}
		
		###### Nutzer-Datentyp #####
		if(is_numeric($attributes['type'][$j])){
			if($field_id != NULL)$id = $field_id;		# wenn field_id übergeben wurde (nicht die oberste Ebene)
			else $id = $k.'_'.$name;	# oberste Ebene
			$datapart .= '<input type="hidden" title="'.$alias.'" name="'.$fieldname.'" id="'.$id.'" onchange="'.$onchange.'" value="'.htmlspecialchars($value).'">';
			$type_attributes = $attributes['type_attributes'][$j];
			$elements = json_decode($value);	# diese Funktion decodiert immer den kommpletten String
			$tsize = 20;
			$datapart .= '<table border="2" class="gle_datatype_table">';
			$onchange2 = 'buildJSONString(\''.$id.'\', false);';
			$elements_fieldname = $id;
			for($e = 0; $e < count($type_attributes['name']); $e++){
				if($elements != NULL){
					$elem_value = current($elements);
					next($elements);
				}
				if(is_array($elem_value) OR is_object($elem_value))$elem_value = json_encode($elem_value);		# ist ein Array oder Objekt (also entweder ein Array-Typ oder ein Datentyp) und wird zur Übertragung wieder encodiert
				$dataset2[$type_attributes['name'][$e]] = $elem_value;
				$type_attributes['privileg'][$e] = $attributes['privileg'][$j];
				if($type_attributes['alias'][$e] == '')$type_attributes['alias'][$e] = $type_attributes['name'][$e];
				$datapart .= '<tr><td valign="top" class="gle_attribute_name"><table><tr><td>'.$type_attributes['alias'][$e].'</td></tr></table></td>';
				$datapart .= '<td class="gle_attribute_value">'.attribute_value($gui, $layer_id, $type_attributes, $e, NULL, $dataset2, $tsize, $select_width, $fontsize, $change_all, $onchange2, $elements_fieldname, $elements_fieldname.'_'.$e).'</td></tr>';
			}
			$datapart .= '</tr></table>';
			return $datapart;
		}
		
		###### normal #####
		if($attributes['constraints'][$j] != '' AND !in_array($attributes['constraints'][$j], array('PRIMARY KEY', 'UNIQUE'))){
			if($attributes['privileg'][$j] == '0' OR $lock[$k]){
				$size1 = 1.3*strlen($dataset[$attributes['name'][$j]]);
				$datapart .= '<input readonly style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;" size="'.$size1.'" type="text" name="'.$fieldname.'" value="'.$value.'">';
			}
			else{
				$datapart .= '<select id="'.$layer_id.'_'.$attributes['name'][$j].'_'.$k.'" onchange="'.$onchange.'" title="'.$attributes['alias'][$j].'"  style="'.$select_width.'font-size: '.$fontsize.'px" name="'.$fieldname.'">';
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
					$datapart .= '<textarea title="'.$alias.'" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" id="'.$layer_id.'_'.$name.'_'.$k.'" cols="'.$size.'" onchange="'.$onchange.'"';
					if($attribute_privileg == '0' OR $lock[$k]){
						$datapart .= ' readonly style="display: none"';
					}
					else{
						$datapart .= ' tabindex="1" style="font-size: '.$fontsize.'px"';
					}
					$datapart .= ' rows="3" name="'.$fieldname.'">'.$value.'</textarea>';
					if($attribute_privileg > '0' AND $attributes['options'][$j] != ''){
						if(strtolower(substr($attributes['options'][$j], 0, 6)) == 'select'){
							$datapart .= '&nbsp;<a title="automatisch generieren" href="javascript:auto_generate(new Array(\''.implode($attributes['name'], "','").'\'), \''.$attributes['the_geom'].'\', \''.$name.'\', '.$k.', '.$layer_id.');set_changed_flag(currentform.changed_'.$layer_id.'_'.$oid.')"><img src="'.GRAPHICSPATH.'autogen.png"></a>';
						}
						else{
							$datapart .= '&nbsp;<a title="Eingabewerkzeug verwenden" href="javascript:openCustomSubform('.$layer_id.', \''.$name.'\', new Array(\''.implode($attributes['name'], "','").'\'), \''.$name.'_'.$k.'\', '.$k.');"><img src="'.GRAPHICSPATH.'autogen.png"></a>';
						}
					}
					if($attribute_privileg == '0' OR $lock[$k]){ // nur lesbares Attribut
						if($size == 12){		// spaltenweise
							$datapart .= htmlspecialchars($value);
						}
						else{								// zeilenweise
							$maxwidth = $size * 11;
							$minwidth = $size * 7.1;
							$datapart .= '<div style="padding: 0 0 0 3; min-width: '.$minwidth.'px; max-width:'.$maxwidth.'px; font-size: '.$fontsize.'px;"><pre>'.$value.'</pre></div>';
						}
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
					$datapart .= Auswahlfeld($layer_id, $name, $j, $alias, $fieldname, $value, $enum_value, $enum_output, $attributes['req_by'][$j], $attributes['name'], $attribute_privileg, $k, $oid, $attributes['subform_layer_id'][$j], $attributes['subform_layer_privileg'][$j], $attributes['embedded'][$j], $lock[$k], $select_width, $fontsize, $strPleaseSelect, $change_all, $onchange);
				}break;
				
				case 'Autovervollständigungsfeld' : {
					$datapart .= Autovervollstaendigungsfeld($layer_id, $name, $j, $alias, $fieldname, $value, $attributes['enum_output'][$j][$k], $attribute_privileg, $k, $oid, $attributes['subform_layer_id'][$j], $attributes['subform_layer_privileg'][$j], $attributes['embedded'][$j], $lock[$k], $fontsize, $change_all, $size, $onchange);
				}break;
				
				case 'Radiobutton' : {
					$enum_value = $attributes['enum_value'][$j];
					$enum_output = $attributes['enum_output'][$j];
					if($attributes['nullable'][$j] != '0' OR $gui->new_entry == true)$strPleaseSelect = $gui->strPleaseSelect;
					if($privileg == '0' OR $lock){
						for($e = 0; $e < count($enum_value); $e++){
							if($enum_value[$e] == $value){
								$auswahlfeld_output = $enum_output[$e];
								$auswahlfeld_output_laenge=strlen($auswahlfeld_output)+1;
								break;
							}
						}
						$datapart .= '<input readonly id="'.$layer_id.'_'.$name.'_'.$k.'" style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;" size="'.$auswahlfeld_output_laenge.'" type="text" name="'.$fieldname.'" value="'.$auswahlfeld_output.'">';
						$auswahlfeld_output = '';
						$auswahlfeld_output_laenge = '';
					}
					else{
						if($change_all){
							$onchange = 'change_all('.$layer_id.', '.$k.', \''.$name.'\');';
						}						
						for($e = 0; $e < count($enum_value); $e++){
							$datapart .= '<input tabindex="1" type="radio" name="'.$fieldname.'" id="'.$layer_id.'_'.$name.'_'.$k.'_'.$e.'"';
							$datapart .= ' onchange="'.$onchange.'" ';
							if($enum_value[$e] == $value){
								$datapart .= 'checked ';
							}
							$datapart .= 'value="'.$enum_value[$e].'"><label for="'.$layer_id.'_'.$name.'_'.$k.'_'.$e.'">'.$enum_output[$e].'</label><br>';
						}
					}
				}break;				
				
				case 'Checkbox' : {
					$datapart .= '<input type="checkbox" id="'.$layer_id.'_'.$name.'_'.$k.'" title="'.$alias.'" cols="45" onchange="'.$onchange.'"';
					if($attribute_privileg == '0' OR $lock[$k]){
						$datapart .= ' onclick="return false" style="border:0px;background-color:transparent;"';
					}
					else{
						$datapart .= ' tabindex="1" ';
					}
					$datapart .= 'value="t" name="'.$fieldname.'"';
					if($value == 't')$datapart .= 'checked=true';
					$datapart .= '>';
				}break;

				case 'SubFormPK' : {
					$datapart .= '<table width="98%" cellspacing="0" cellpadding="0"><tr><td>';
					if($size == 12){		// spaltenweise
						$datapart .= htmlspecialchars($value);
					}
					else{								// zeilenweise
						$maxwidth = $size * 8;
						$minwidth = $size * 4;
						$datapart .= '<div style="padding: 0 0 0 3; min-width: '.$minwidth.'px; max-width:'.$maxwidth.'px; font-size: '.$fontsize.'px;">'.htmlspecialchars($value).'</div>';
					}
					$datapart .= '</td>';
					if($gui->new_entry != true){
						$datapart .= '<td width="100%" align="right">';
						if($value != ''){
							$params = 'go=Layer-Suche_Suchen&selected_layer_id='.$attributes['subform_layer_id'][$j].'&subform_link=true';
							for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
								$params .= '&value_'.$attributes['subform_pkeys'][$j][$p].'='.$dataset[$attributes['subform_pkeys'][$j][$p]];
								$params .= '&operator_'.$attributes['subform_pkeys'][$j][$p].'==';
							}
							if($attributes['no_new_window'][$j] == true){
								$datapart .= '&nbsp;<a href="javascript:overlay_link(\''.$params.'\');"';
							}
							else{	
								$datapart .= '&nbsp;<a href="index.php?'.$params.'" target="_blank"';
							}
							$datapart .= 	' class="buttonlink"><span>'.$strShowPK.'</span></a>&nbsp;';
						}
						if($attributes['subform_layer_privileg'][$j] > 0){
							$datapart .= '<a href="" onclick="this.href=\'index.php?go=neuer_Layer_Datensatz&subform=true&selected_layer_id='.$attributes['subform_layer_id'][$j];
							for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
								$datapart .= '&attributenames['.$p.']='.$attributes['subform_pkeys'][$j][$p];
								$datapart .= '&values['.$p.']=\'+document.getElementById(\''.$layer_id.'_'.$attributes['subform_pkeys'][$j][$p].'_'.$k.'\').value';
							}
							$datapart .= 	'"';
							if($attributes['no_new_window'][$j] != true){
								$datapart .= 	' target="_blank"';
							}
							$datapart .= 	' class="buttonlink"><span>'.$strNewPK.'</span></a>&nbsp;';
						}
						$datapart .= '</td>';
					}
					$datapart .= '</tr></table>';
				}break;

				case 'SubFormFK' : {
					$datapart .= '<table width="98%" cellpadding="0" cellspacing="0"><tr><td>';
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
								if($attributes['subform_layer_privileg'][$index] != '0')$gui->editable = $layer_id;
								$datapart .= Autovervollstaendigungsfeld($layer_id, $name_, $index, $attributes['alias'][$name_], $fieldname_[$f], $dataset[$name_], $attributes['enum_output'][$index][$k], $attributes['privileg'][$name_], $k, $oid, $attributes['subform_layer_id'][$index], $attributes['subform_layer_privileg'][$index], $attributes['embedded'][$index], $lock[$k], $fontsize, $change_all, $size, $onchange);
							}break;
							case 'Auswahlfeld' : {
								if($attributes['subform_layer_privileg'][$index] != '0')$gui->editable = $layer_id;
								if(is_array($attributes['dependent_options'][$index])){
									$enum_value = $attributes['enum_value'][$index][$k];		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
									$enum_output = $attributes['enum_output'][$index][$k];		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
								}
								else{
									$enum_value = $attributes['enum_value'][$index];
									$enum_output = $attributes['enum_output'][$index];
								}
								if($attributes['nullable'][$index] != '0')$strPleaseSelect = $gui->strPleaseSelect;
								$onchange = 'set_changed_flag(currentform.changed_'.$layer_id.'_'.$oid.');';
								$datapart .= Auswahlfeld($layer_id, $name_, $j, $attributes['alias'][$name_], $fieldname_[$f], $dataset[$name_], $enum_value, $enum_output, $attributes['req_by'][$index], $attributes['name'], $attributes['privileg'][$name_], $k, $oid, $attributes['subform_layer_id'][$index], $attributes['subform_layer_privileg'][$index], $attributes['embedded'][$index], $lock[$k], $select_width, $fontsize, $strPleaseSelect, $change_all, $onchange);
							}break;
							default : {
								$datapart .= '<input style="font-size: '.(0.9*$fontsize).'px';
								if($attributes['privileg'][$name_] == '0' OR $lock[$k]){
									$datapart .= ';background-color:transparent;border:0px;display:none;background-color:#e8e3da;" readonly ';
								}
								else{
									'" ';
								}
								$datapart .= ' id="'.$layer_id.'_' . $name_ . '_'.$k.'" name="'.$fieldname_[$f].'" value="'.$dataset[$name_].'">';
							}
						}
						$gui->form_field_names .= $fieldname_[$f].'|';
					}
					if($size == 12){		// spaltenweise
						$datapart .= htmlspecialchars($value);
					}
					else{								// zeilenweise
						$maxwidth = $size * 8;
						$minwidth = $size * 4;
						$datapart .= '<div style="padding: 0 0 0 3; min-width: '.$minwidth.'px; max-width:'.$maxwidth.'px; font-size: '.$fontsize.'px;">'.htmlspecialchars($value).'</div>';
					}
					$datapart .= '</td><td align="right" valign="top">';
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
								$datapart .= '&nbsp;<a id="new_'.$layer_id.'_'.$name.'_'.$k.'" class="buttonlink" href="javascript:ahah(\'index.php\', \'go=neuer_Layer_Datensatz';
								$data = '';
								for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
									$datapart .= '&attributenames['.$p.']='.$attributes['subform_pkeys'][$j][$p];
									$datapart .= '&values['.$p.']=\'+document.getElementById(\''.$layer_id.'_'.$attributes['subform_pkeys'][$j][$p].'_'.$k.'\').value+\'';
									$data .= '&value_'.$attributes['subform_pkeys'][$j][$p].'=\'+document.getElementById(\''.$layer_id.'_'.$attributes['subform_pkeys'][$j][$p].'_'.$k.'\').value+\'';
									$data .= '&operator_'.$attributes['subform_pkeys'][$j][$p].'==';
								}
								$data .= '&preview_attribute='.$attributes['preview_attribute'][$j];
								$datapart .= '&data='.str_replace('&', '<und>', $data);
								$datapart .= '&selected_layer_id='.$attributes['subform_layer_id'][$j] .
														 '&embedded=true&fromobject=subform' . $layer_id . '_' . $k . '_' . $j .
														 '&targetobject=' . $layer_id . '_' . $name . '_' . $k .
														 '&targetlayer_id=' . $layer_id .
														 '&targetattribute=' . $name . '\', new Array(document.getElementById(\'subform'.$layer_id.'_'.$k.'_'.$j.'\'), \'\'), new Array(\'sethtml\', \'execute_function\'));clearsubforms('.$attributes['subform_layer_id'][$j].');"><span>'.$strNewEmbeddedPK.'</span></a>';
								$datapart .= '<div style="display:inline" id="subform'.$layer_id.'_'.$k.'_'.$j.'"></div>';
							}
							else{
								$datapart .= '&nbsp;<a class="buttonlink"';
								if($attributes['no_new_window'][$j] != true){
									$datapart .= 	' target="_blank"';
								}
								$datapart .= ' href="javascript:overlay_link(\'go=neuer_Layer_Datensatz&subform=true';
								for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
									$datapart .= '&attributenames['.$p.']='.$attributes['subform_pkeys'][$j][$p];
									$datapart .= '&values['.$p.']=\'+document.getElementById(\''.$layer_id.'_'.$attributes['subform_pkeys'][$j][$p].'_'.$k.'\').value+\'';
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
							//$datapart .= '<a href="javascript:delete_document(\''.$fieldname.'\');"><span>Dokument <br>löschen</span></a>';
							$datapart .= '<a href="javascript:delete_document(\''.$fieldname.'\', '.$layer_id.', \''.$gui->formvars['fromobject'].'\', \''.$gui->formvars['targetobject'].'\', \''.$gui->formvars['targetlayer_id'].'\', \''.$gui->formvars['targetattribute'].'\', \''.$gui->formvars['data'].'\', \''.$gui->formvars['reload'].'\');"><span>Dokument <br>löschen</span></a>';
						}
						$datapart .= '</td></tr>';
						$datapart .= '<tr><td colspan="2"><span id="image_original_name">'.$original_name.'</span></td></tr>';
						$datapart .= '</table>';
						$datapart .= '<input type="hidden" name="'.$layer_id.';'.$attributes['real_name'][$name].';'.$tablename.';'.$oid.';'.$attributes['form_element_type'][$j].'_alt'.';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$value.'">';
					}
					if($attribute_privileg != '0' AND !$lock[$k]){
						$datapart .= '<input tabindex="1" onchange="'.$onchange.'" style="font-size: '.$fontsize.'px" size="43" type="file" onchange="this.title=this.value;" id="'.$layer_id.'_'.$name.'_'.$k.'" name="'.$fieldname.'">';
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
						$datapart .= '<input tabindex="1" onchange="'.$onchange.'" id="'.$layer_id.'_'.$name.'_'.$k.'" style="font-size: '.$fontsize.'px" size="'.$size.'" type="text" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
					}else{
						$datapart .= '<input type="hidden" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
					}
				} break;

				case 'dynamicLink': {
					$show_link = false;
					$one_param_is_null = false;
					$options = $attributes['options'][$j];
					for($a = 0; $a < count($attributes['name']); $a++){
						if(strpos($options, '$'.$attributes['name'][$a]) !== false){
							$options = str_replace('$'.$attributes['name'][$a], $dataset[$attributes['name'][$a]], $options);
							if(empty($dataset[$attributes['name'][$a]])) {
								$one_param_is_null = true;
							}
							else {
								$show_link = true;
							}
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
					if ($explosion[3] == 'all_not_null' and $one_param_is_null) {
						$show_link = false;
					}

					if ($show_link) {
						if($explosion[2] == 'embedded'){
							$datapart .= '<a class="dynamicLink" href="javascript:if(document.getElementById(\'dynamicLink'.$layer_id.'_'.$k.'_'.$j.'\').innerHTML != \'\'){clearsubform(\'dynamicLink'.$layer_id.'_'.$k.'_'.$j.'\');} else {ahah(\''.$href.'\', \'\', new Array(document.getElementById(\'dynamicLink'.$layer_id.'_'.$k.'_'.$j.'\')), new Array(\'sethtml\'))}">';
							$datapart .= $alias;
							$datapart .= '</a><br>';
							$datapart .= '<div style="display:inline" id="dynamicLink'.$layer_id.'_'.$k.'_'.$j.'"></div>';
						}
						else{
							$datapart .= '<a ';
							if($explosion[2] != 'no_new_window'){$datapart .= 'target="_blank"';}
							$datapart .= ' class="dynamicLink" style="font-size: '.$fontsize.'px" href="'.$href.'">';
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
						$datapart .= '<input tabindex="1" onchange="'.$onchange.'" id="'.$layer_id.'_'.$name.'_'.$k.'" style="font-size: '.$fontsize.'px" size="'.$size.'" type="text" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
					}else{
						$datapart .= '<input type="hidden" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
					}
				} break;

				case 'Fläche': {
					$datapart .= '<input onchange="'.$onchange.'" id="custom_area" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$alias.'" ';
					if($attribute_privileg == '0' OR $lock[$k]){
						$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;"';
					}
					else{
						$datapart .= ' style="font-size: '.$fontsize.'px;"';
					}
					$datapart .= ' size="'.$size.'" type="text" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
				}break;
				
				case 'Länge': {
					$datapart .= '<input onchange="'.$onchange.'" id="custom_length" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$alias.'" ';
					if($attribute_privileg == '0' OR $lock[$k]){
						$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;"';
					}
					else{
						$datapart .= ' style="font-size: '.$fontsize.'px;"';
					}
					$datapart .= ' size="'.$size.'" type="text" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
				}break;
				
				case 'Winkel': {
					$datapart .= '<input onchange="'.$onchange.'" id="custom_angle" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$alias.'" ';
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
					$datapart .= '<input onchange="'.$onchange.'" onkeyup="checknumbers(this, \'Zahl\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$alias.'" ';
					if($attribute_privileg == '0' OR $lock[$k]){
						$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;"';
					}
					else{
						$datapart .= ' tabindex="1" style="width: 100%; font-size: '.$fontsize.'px;"';
					}
					if($name == 'lock'){
						$datapart .= ' type="hidden"';
					}
					if($attributes['length'][$j] AND !in_array($attributes['type'][$j], array('numeric', 'float4', 'float8', 'int2', 'int4', 'int8'))){
						$datapart .= ' maxlength="'.$attributes['length'][$j].'"';
					}
					$datapart .= ' size="'.$size.'" type="text" name="'.$fieldname.'" id="'.$layer_id.'_'.$name.'_'.$k.'" value="'.htmlspecialchars($value).'">';
				}break;
				
				default : {
					$datapart .= '<input onchange="'.$onchange.'" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$alias.'" ';
					if($attribute_privileg == '0' OR $lock[$k]){
						$datapart .= ' readonly style="display:none;"';
					}
					else{
						$datapart .= ' tabindex="1" style="width: 100%; font-size: '.$fontsize.'px;"';
					}
					if($name == 'lock'){
						$datapart .= ' type="hidden"';
					}
					if($attributes['length'][$j] AND !in_array($attributes['type'][$j], array('numeric', 'float4', 'float8', 'int2', 'int4', 'int8'))){
						$datapart .= ' maxlength="'.$attributes['length'][$j].'"';
					}
					$datapart .= ' size="'.$size.'" type="text" name="'.$fieldname.'" id="'.$layer_id.'_'.$name.'_'.$k.'" value="'.htmlspecialchars($value).'">';
					if($attribute_privileg == '0' OR $lock[$k]){ // nur lesbares Attribut
						if($size == 12){		// spaltenweise
							$datapart .= htmlspecialchars($value);
						}
						else{								// zeilenweise
							$maxwidth = $size * 11;
							$minwidth = $size * 7.1;
							$datapart .= '<div class="readonly_text" style="padding: 0 0 0 3; min-width: '.$minwidth.'px; max-width:'.$maxwidth.'px; font-size: '.$fontsize.'px;">'.$value.'</div>';
						}
					}
					if($attribute_privileg > '0' AND $attributes['options'][$j] != ''){
						if(strtolower(substr($attributes['options'][$j], 0, 6)) == 'select'){
							$datapart .= '&nbsp;<a title="automatisch generieren" href="javascript:auto_generate(new Array(\''.implode($attributes['name'], "','").'\'), \''.$attributes['the_geom'].'\', \''.$name.'\', '.$k.', '.$layer_id.');set_changed_flag(currentform.changed_'.$layer_id.'_'.$oid.')"><img src="'.GRAPHICSPATH.'autogen.png"></a>';
						}
						else{
							$datapart .= '&nbsp;<a title="Eingabewerkzeug verwenden" href="javascript:openCustomSubform('.$layer_id.', \''.$name.'\', new Array(\''.implode($attributes['name'], "','").'\'), \''.$name.'_'.$k.'\', '.$k.');"><img src="'.GRAPHICSPATH.'autogen.png"></a>';
						}
					}
				}
			}
		}
		return $datapart;
	}

	function Autovervollstaendigungsfeld($layer_id, $name, $j, $alias, $fieldname, $value, $output, $privileg, $k, $oid, $subform_layer_id, $subform_layer_privileg, $embedded, $lock, $fontsize, $change_all, $size, $onchange){
		if($change_all){
			$onchange = 'change_all('.$layer_id.', '.$k.', \''.$layer_id.'_'.$name.'\');';
			$onchange_output = 'change_all('.$layer_id.', '.$k.', \'output_'.$layer_id.'_'.$name.'\');';
		}
		$datapart = '<table cellpadding="0" cellspacing="0"><tr><td><div>';
		$datapart .= '<input autocomplete="off" title="'.$alias.'" onkeydown="if(this.backup_value==undefined){this.backup_value=this.value; document.getElementById(\''.$layer_id.'_'.$name.'_'.$k.'\').backup_value=document.getElementById(\''.$layer_id.'_'.$name.'_'.$k.'\').value;}" onkeyup="autocomplete1(\''.$layer_id.'\', \''.$name.'\', \''.$layer_id.'_'.$name.'_'.$k.'\', this.value);" onchange="if(document.getElementById(\'suggests_'.$layer_id.'_'.$name.'_'.$k.'\').style.display==\'block\'){this.value=this.backup_value; document.getElementById(\''.$layer_id.'_'.$name.'_'.$k.'\').value=document.getElementById(\''.$layer_id.'_'.$name.'_'.$k.'\').backup_value; setTimeout(function(){document.getElementById(\'suggests_'.$layer_id.'_'.$name.'_'.$k.'\').style.display = \'none\';}, 500);}'.$onchange_output.'if(\''.$oid.'\' != \'\')set_changed_flag(currentform.changed_'.$layer_id.'_'.$oid.')"';
		if($privileg == '0' OR $lock){
			$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;"';
		}
		else{
			$datapart .= ' tabindex="1" style="font-size: '.$fontsize.'px;"';
		}
		$datapart .= ' size="'.$size.'" type="text" id="output_'.$layer_id.'_'.$name.'_'.$k.'" value="'.htmlspecialchars($output).'">';
		$datapart .= '<input type="hidden" onchange="'.$onchange.';" name="'.$fieldname.'" id="'.$layer_id.'_'.$name.'_'.$k.'" value="'.htmlspecialchars($value).'">';
		$datapart .= '<div valign="top" style="height:0px; position:relative;">
				<div id="suggests_'.$layer_id.'_'.$name.'_'.$k.'" style="z-index: 3000;display:none; position:absolute; left:0px; top:0px; width: 400px; vertical-align:top; overflow:hidden; border:solid grey 1px;"></div>
			</div>
		</div>';
		
		if($subform_layer_id != ''){
			$datapart .= '</td><td>';
			if($subform_layer_privileg > 0){
				if($embedded == true){
					$datapart .= '&nbsp;&nbsp;<a class="buttonlink" href="javascript:ahah(\'index.php\', \'go=neuer_Layer_Datensatz&selected_layer_id='.$subform_layer_id.'&embedded=true&fromobject=subform'.$layer_id.'_'.$k.'_'.$j.'&targetobject='.$layer_id.'_'.$name.'_'.$k.'&targetlayer_id='.$layer_id.'&targetattribute='.$name.'\', new Array(document.getElementById(\'subform'.$layer_id.'_'.$k.'_'.$j.'\')), new Array(\'sethtml\'));">&nbsp;neu&nbsp;</a>';
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
	
	function Auswahlfeld($layer_id, $name, $j, $alias, $fieldname, $value, $enum_value, $enum_output, $req_by, $attributenames, $privileg, $k, $oid, $subform_layer_id, $subform_layer_privileg, $embedded, $lock, $select_width, $fontsize, $strPleaseSelect, $change_all, $onchange){
		if($privileg == '0' OR $lock){
			for($e = 0; $e < count($enum_value); $e++){
				if($enum_value[$e] == $value){
					$auswahlfeld_output = $enum_output[$e];
					$auswahlfeld_output_laenge=strlen($auswahlfeld_output)+1;
					break;
				}
			}
			$datapart .= '<input readonly id="'.$layer_id.'_'.$name.'_'.$k.'" style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;" size="'.$auswahlfeld_output_laenge.'" type="text" name="'.$fieldname.'" value="'.$auswahlfeld_output.'">';
			$auswahlfeld_output = '';
			$auswahlfeld_output_laenge = '';
		}
		else{
			if($change_all){
				$onchange = 'change_all('.$layer_id.', '.$k.', \''.$layer_id.'_'.$name.'\');';
			}
			$datapart .= '<select tabindex="1" title="'.$alias.'" style="'.$select_width.'font-size: '.$fontsize.'px"';
			if($req_by != ''){
				$onchange .= 'update_require_attribute(\''.$req_by.'\', '.$k.','.$layer_id.', new Array(\''.implode($attributenames, "','").'\'));';
			}
			$datapart .= ' onchange="'.$onchange.'" ';
			$datapart .= 'id="'.$layer_id.'_'.$name.'_'.$k.'" name="'.$fieldname.'">';
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
	
	function output_statistic($statistic) {
		echo '<table>';
		foreach($statistic AS $key => $row) {
			if ($key == 'relative Häufigkeit' or $key == 'absolute Häufigkeit') {
				echo '<tr><td colspan="2">' . $row['title'] . '&nbsp;:</td></tr>';
				foreach ($row['values'] AS $key => $row) {
					echo '<tr><td align="right">' . $row['title'] . '&nbsp;:</td><td align="left">' . $row['value'] . '</td></tr>';
				}
			}
			else {
				echo '<tr><td align="left">' . $row['title'] . '&nbsp;:</td><td align="left">' . $row['value'] . '</td></tr>';
			}
		}
		echo '</table>';
	}

	function relative_haeufigkeit($data, $column_name, $min, $max) {
		$ha = array('title' => 'hr(A)', 'values' => array());
		$percent_values = array_map(
			function ($row) use ($column_name, $min, $max) {
				$value = $row[$column_name];
				$delta = $max - $min;
				return ($max == $min) ? 100 : round(($value - $min) * 100 / ($max - $min));
			},
			$data
		);
		sort($percent_values);
		$hist_values = array();
		foreach($percent_values AS $percent_value) {
			if (!isset($hist_values[$percent_value]))
				$hist_values[$percent_value] = 0;
			$hist_values[$percent_value]++;
		}
		foreach($hist_values AS $key => $value) {
			$hr['values'][] = array('title' => round($key * ($max - $min) / 100 + $min, strlen(substr(strrchr($summe, "."), 1))), 'value' => $value);
		}
		return $hr;
	}

	function absolute_haeufigkeit($data, $column_name) {
		$ha = array('title' => 'ha(A)', 'values' => array());
		foreach($data AS $row) {
			$value = $row[$column_name];
			if (empty($ha['values'][$value])) {
				$ha['values'][$value] = array('title' => $value, 'value' => 1);
			}
			else {
				$ha['values'][$value]['value']++;
			}
		}
		ksort($ha['values']);
		return $ha;
	}

?>