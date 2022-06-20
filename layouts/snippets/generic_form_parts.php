<?
	global $strShowPK;
	global $strNewPK;
	global $strShowFK;
	global $strShowAll;
	global $strNewEmbeddedPK;
	global $hover_preview;
	
	function output_table($table) {
		$output = '';
		if (is_array($table['rows'])) {
			foreach($table['rows'] as $row) {
				$output .= '<tr id="' . $row['id'] . '" class="' . $row['class'] . '">';
				if (value_of($row, 'sidebyside') AND !$row['contains_attribute_names']) {
					$width = 'width="'.(100 / $table['max_cell_count']).'%"';
				}
				$cell_count = count($row['cells']);
				$colspan = $table['max_cell_count'] - $cell_count + 1;
				for($i = 0; $i < $cell_count; $i++) {
					$cell = $row['cells'][$i];
					if ($row['contains_attribute_names']) {
						if ($cell['properties'] == 'class="gle-attribute-name"') {
							$width = 'width="' . ($i == 0 ? '10%' : '1%') . '"';
						}
						else {
							$width = '';
						}
					}
					$output .= '<td ' .
						$width . ' ' .
						($cell['id'] ? 'id="' . $cell['id'] . '"' : '') .
						$cell['properties'] . ' ' .
						(($colspan > 1 AND $i == $cell_count - 1) ? 'colspan="' . $colspan . '"' : '') .
					'>';
					$output .= $cell['content'];
					if ($cell['id']) {
						$output .= '<div onmousedown="resizestart(document.getElementById(\'' . $cell['id'] . '\'), \'col_resize\');" style="position: absolute; transform: translate(4px); top: 0px; right: 0px; height: 20px; width: 6px; cursor: e-resize;"></div>';
					}
					$output .= '</td>';
				}
				$output .= '</tr>';
			}
		}
		return $output;
	}

	function attribute_name($layer_id, $attributes, $j, $k, $fontsize, $sort_links = true) {
		$datapart = '<table ';
		if($attributes['group'][0] != '' AND $attributes['arrangement'][$j+1] != 1 AND $attributes['arrangement'][$j] != 1 AND $attributes['labeling'][$j] != 1)$datapart .= 'width="200px"';
		else $datapart .= 'width="100%"';
		$datapart .= '><tr style="border: none"><td' . (($attributes['nullable'][$j] == '0' AND $attributes['privileg'][$j] != '0') ? ' class="gle-attribute-mandatory"' : '') . '>';
		if($attributes['alias'][$j] == '')$attributes['alias'][$j] = $attributes['name'][$j];
		if (
			$sort_links AND
			!(
				in_array($attributes['form_element_type'][$j], array('SubFormPK', 'SubFormEmbeddedPK', 'SubFormFK', 'dynamicLink')) OR
				is_numeric($attributes['type'][$j]) OR
				substr($attributes['type'][$j], 0, 1) == '_'
			)
		) {
			$datapart .= '<a style="font-size: '.$fontsize.'px" title="Sortieren nach '.$attributes['alias'][$j].'" href="javascript:change_orderby(\''.$attributes['name'][$j].'\', '.$layer_id.');">'.$attributes['alias'][$j].'</a>';
		}
		else {
			$datapart .= '<span style="font-size: '.$fontsize.'px;">'.$attributes['alias'][$j].'</span>';
		}
		if ($attributes['nullable'][$j] == '0' AND $attributes['privileg'][$j] != '0'){
			$datapart .= '<span title="Eingabe erforderlich">*</span>';
		}
		if($attributes['tooltip'][$j]!='' AND $attributes['form_element_type'][$j] != 'Time'){
			if (substr($attributes['tooltip'][$j], 0, 4) == 'http') {
				$title_link = 'href="'.$attributes['tooltip'][$j].'" target="_blank"';
			}
			else {
				$title_link = 'href="javascript:void(0);"';
			}
			$datapart .= '<td align="right"><a ' . $title_link . ' title="' . htmlentities($attributes['tooltip'][$j]) . '"><img src="' . GRAPHICSPATH . 'emblem-important.png" border="0" onclick="message([{\'type\': \'info\', \'msg\': \'' . str_replace(array("\r\n", "\r", "\n"), "<br>", htmlentities($attributes['tooltip'][$j], ENT_QUOTES)) . '\'}])"></a></td>';
		}
		if(in_array($attributes['type'][$j], array('date', 'time', 'timestamp', 'timestamptz'))){
			$datapart .= '<td align="right">'.calendar($attributes['type'][$j], $layer_id.'_'.$attributes['name'][$j].'_'.$k, $attributes['privileg'][$j]).'</td>';
		}
		$datapart .= '</td></tr></table>';
		return $datapart;
	}

	function calendar($type, $field_id, $privileg){
		$date_types = array('date' => 'TT.MM.JJJJ', 'timestamp' => 'TT.MM.JJJJ hh:mm:ss', 'time' => 'hh:mm:ss');
		$cal = '<a id="caldbl" href="javascript:;" title="('.$date_types[$type].')"'.
						(($privileg == '1') ? 'onclick="add_calendar(event, \''.$field_id.'\', \''.$type.'\');" 
																										 ondblclick="add_calendar(event, \''.$field_id.'\', \''.$type.'\', true);"' : '').'
						><img src="' . GRAPHICSPATH . 'calendarsheet.png" border="0"></a>
						<div id="calendar_'.$field_id.'" class="calendar"></div>';
		return $cal;
	}

	function attribute_value(&$gui, $layer, $attributes, $j, $k, $dataset, $size, $select_width, $fontsize, $change_all = false, $onchange = NULL, $field_name = NULL, $field_id = NULL, $field_class = NULL){
		$datapart = '';
		$after_attribute = '';
		global $strShowPK;
		global $strNewPK;
		global $strShowFK;
		global $strShowAll;
		global $strNewEmbeddedPK;
		global $hover_preview;
		$layer_id = $layer['Layer_ID'];
		if($dataset == NULL)$dataset = $layer['shape'][$k]; 						# der aktuelle Datensatz (wird nur beim Array- oder Nutzer-Datentyp übergeben)
		if($attributes == NULL) {
			$attributes = $layer['attributes'];			# das Attribut-Array (wird nur beim Array- oder Nutzer-Datentyp übergeben)
		}
		$name = $attributes['name'][$j];																# der Name des Attributs
		$alias = $attributes['alias'][$j];															# der Aliasname des Attributs
		$value = $dataset[$name];																				# der Wert des Attributs
		$tablename = $attributes['table_name'][$name];									# der Tabellenname des Attributs
		$oid = $dataset[$tablename.'_oid'];															# die oid des Datensatzes
		$attribute_privileg = $attributes['privileg'][$j];							# das Recht des Attributs

		if($field_name == NULL)$fieldname = $layer_id.';'.$attributes['real_name'][$name].';'.$tablename.';'.$oid.';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].';'.$attributes['saveable'][$j];
		else $fieldname = $field_name;
				
		if(!$change_all){
			$onchange .= 'set_changed_flag(this, \'changed_'.$layer_id.'_'.str_replace('-', '', $oid).'\');';
		}
		else{
			$onchange .= 'change_all('.$layer_id.', '.$k.', \''.$layer_id.'_'.$name.'\');';
		}
		
		$old_field_class = $field_class;
		if ($attributes['dependents'][$j] != NULL) {
			$field_class .= ' visibility_changer';
			$onchange .= 'this.oninput();" oninput="check_visibility('.$layer_id.', this, [\''.implode('\',\'', $attributes['dependents'][$j]).'\'], '.$k.');';
		}
		
		if($attributes['vcheck_attribute'][$j] != ''){
			$after_attribute .= '<input type="hidden" id="vcheck_attribute_'.$attributes['name'][$j].'" value="'.$attributes['vcheck_attribute'][$j].'">';
			$after_attribute .= '<input type="hidden" id="vcheck_operator_'.$attributes['name'][$j].'" value="'.$attributes['vcheck_operator'][$j].'">';
			$after_attribute .= '<input type="hidden" id="vcheck_value_'.$attributes['name'][$j].'" value="'.htmlentities($attributes['vcheck_value'][$j]).'">';
		}

		###### Array-Typ #####
		if (POSTGRESVERSION >= 930 AND substr($attributes['type'][$j], 0, 1) == '_'){
			if ($field_id != NULL) $id = $field_id.'_'.$name;		# wenn field_id übergeben wurde (nicht die oberste Ebene)
			else $id = $layer_id.'_'.$name.'_'.$k;	# oberste Ebene
			$datapart .= '<input type="hidden" class="'.$field_class.'" title="'.$alias.'" name="'.$fieldname.'" id="'.$id.'" onchange="'.$onchange.'" value="'.htmlspecialchars($value).'">';
			$datapart .= '<div id="'.$id.'_elements" '.($attributes['form_element_type'][$j] == 'Dokument' ? 'style="max-width: 735px; display: flex; flex-wrap: wrap; align-items: flex-start"' : '').'>';
			$elements = json_decode($value);		# diese Funktion decodiert immer den kommpletten String
			$attributes2 = $attributes;
			#$attributes2['name'][$j] = '';		// rausgenommen weil sonst in dynamischen Links nicht richtig ersetzt wird, aber es hatte wahrscheinlich einen Grund
			$attributes2['dependents'][$j] = '';		// die Array-Elemente sollen keine Visibility-Changer sein, nur das gemeinsame Hidden-Feld oben
			$attributes2['table_name'][$attributes2['name'][$j]] = $tablename;
			$attributes2['type'][$j] = substr($attributes['type'][$j], 1);			
			$dataset2 = $dataset;
			$dataset2[$tablename.'_oid'] = $oid;
			$onchange2 = 'buildJSONString(\''.$id.'\', true);';
			for($e = -1; $e < count_or_0($elements); $e++){
				if(is_array($elements[$e]) OR is_object($elements[$e]))$elements[$e] = json_encode($elements[$e]);		# ist ein Array oder Objekt (also entweder ein Array-Typ oder ein Datentyp) und wird zur Übertragung wieder encodiert
				$dataset2[$attributes2['name'][$j]] = $elements[$e];
				$datapart .= '<div id="div_'.$id.'_'.$e.'" style="margin: 5px; display: '.($e==-1 ? 'none' : 'block').'"><table cellpadding="0" cellspacing="0"><tr><td style="height: 22px">';
				$datapart .= attribute_value($gui, $layer, $attributes2, $j, $k, $dataset2, $size, $select_width, $fontsize, $change_all, $onchange2, $id.'_'.$e, $id.'_'.$e, $id.' '.$old_field_class);
				$datapart .= '</td>';
				if($attributes['privileg'][$j] == '1' AND !$lock[$k]){
					$datapart .= '
						<td valign="top">
							<div style="display: flex">
								<a href="javascript:void(0)" title="'.$gui->strDelete.'" onclick="removeArrayElement(\''.$id.'\', \''.$id.'_'.$e.'\');'.$onchange2.'return false;"><img style="width: 18px" src="'.GRAPHICSPATH.'datensatz_loeschen.png"></a>
								<a href="javascript:void(0)" onclick="moveArrayElement(\''.$id.'\', \''.$id.'_'.$e.'\', \'up\');'.$onchange2.'return false;" style="padding: 3px 3px 0 0"><img src="'.GRAPHICSPATH.'pfeil2.gif"></a>
								<a href="javascript:void(0)" onclick="moveArrayElement(\''.$id.'\', \''.$id.'_'.$e.'\', \'down\');'.$onchange2.'return false;" style="padding: 3px 3px 0 0"><img src="'.GRAPHICSPATH.'pfeil.gif"></a>
							</div>
						</td>';
				}
				$datapart .= '</tr></table>';
				$datapart .= '</div>';
			}
			$datapart .= '</div>';
			if($attributes['privileg'][$j] == '1' AND !$lock[$k]){
				$datapart .= '<div style="padding: 3px 10px 3px 3px;float: right"><a href="javascript:addArrayElement(\''.$id.'\', \''.$attributes['form_element_type'][$j].'\', \''.$oid.'\')" class="buttonlink"><span>'.$strNewEmbeddedPK.'</span></a></div>';
			}
			return $datapart.$after_attribute;
		}

		###### Nutzer-Datentyp #####
		if(is_numeric($attributes['type'][$j])){
			if($field_id != NULL)$id = $field_id.'_'.$name;		# wenn field_id übergeben wurde (nicht die oberste Ebene)
			else $id = $k.'_'.$name;	# oberste Ebene
			$datapart .= '<input type="hidden" class="'.$field_class.'" title="'.$alias.'" name="'.$fieldname.'" id="'.$id.'" onchange="'.$onchange.'" value="'.htmlspecialchars($value).'">';
			$type_attributes = $attributes['type_attributes'][$j];
			$elements = json_decode($value);	# diese Funktion decodiert immer den kommpletten String
			if($elements != NULL){
				foreach($elements as $element => $elem_value){
					if (is_array($elem_value) OR is_object($elem_value)) {
						# ist ein Array oder Objekt (also entweder ein Array-Typ oder ein Datentyp) und wird zur Übertragung wieder encodiert
						$elem_value = json_encode($elem_value);
					}
					$dataset2[$element] = $elem_value;
				}
			}
			$tsize = 20;
			$datapart .= '<table border="2" class="gle_datatype_table">';
			$onchange2 = "buildJSONString('" . $id . "', false);";
			for ($e = 0; $e < count($type_attributes['name']); $e++) {
				if ($type_attributes['visible'][$e] != 0) {
					$type_attributes['privileg'][$e] = $attributes['privileg'][$j];
					if ($type_attributes['alias'][$e] == '') $type_attributes['alias'][$e] = $type_attributes['name'][$e];
					switch ($type_attributes['labeling'][$e]) {
						case 1 : {
							$datapart .= '
								<tr>
									<td id="name_'.$layer_id.'_'.$type_attributes['name'][$e].'_'.$k.'" colspan="2" valign="top" class="gle-attribute-name">
										<table>
											<tr>
												<td>' . $type_attributes['alias'][$e] . '</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td id="value_'.$layer_id.'_'.$type_attributes['name'][$e].'_'.$k.'" colspan="2" class="gle_attribute_value">
										' . attribute_value($gui, $layer, $type_attributes, $e, $k, $dataset2, $tsize, $select_width, $fontsize, $change_all, $onchange2, $id.'_'.$e, $id.'_'.$e, $id) . '
									</td>
								</tr>
							';
						} break;
						case 2 : {
							$datapart .= '
								<tr>
									<td id="value_'.$layer_id.'_'.$type_attributes['name'][$e].'_'.$k.'" colspan="2" class="gle_attribute_value">
										' . attribute_value($gui, $layer, $type_attributes, $e, $k, $dataset2, $tsize, $select_width, $fontsize, $change_all, $onchange2, $id.'_'.$e, $id.'_'.$e, $id) . '
									</td>
								</tr>
							';
						} break;
						default : {
							$datapart .= '
								<tr id="tr_'.$layer_id.'_'.$type_attributes['name'][$e].'_'.$k.'" class="' . $attribute_class . '">
									<td id="name_'.$layer_id.'_'.$type_attributes['name'][$e].'_'.$k.'" valign="top" class="gle_attribute_name">
										<table>
											<tr>
												<td>' . $type_attributes['alias'][$e] . '</td>
											</tr>
										</table>
									</td>
									<td id="value_'.$layer_id.'_'.$type_attributes['name'][$e].'_'.$k.'" class="gle_attribute_value">
										' . attribute_value($gui, $layer, $type_attributes, $e, $k, $dataset2, $tsize, $select_width, $fontsize, $change_all, $onchange2, $id.'_'.$e, $id.'_'.$e, $id) . '
									</td>
								</tr>';
						}
					}
				}
			}
			$datapart .= '</table>';
			return $datapart.$after_attribute;
		}

		###### normal #####
		if ($field_id != NULL) {
			$id = $field_id; # wenn field_id übergeben wurde (nicht die oberste Ebene)
		}
		else {
			$id = $layer_id.'_'.$name.'_'.$k;	# oberste Ebene ($id kann eigentlich für alle Typen verwendet werden)
		}
		if ($attributes['constraints'][$j] != '' AND !in_array($attributes['constraints'][$j], array('PRIMARY KEY', 'UNIQUE'))) {
			if ($attributes['privileg'][$j] == '0' OR $lock[$k]) {
				$output_value = $value;
				if (is_array($attributes['enum_value'][$j]) AND count($attributes['enum_value'][$j]) > 0) {
					$enum_index = array_search($value, $attributes['enum_value'][$j]);
					if ($enum_index !== false) {
						$output_value = $attributes['enum_output'][$j][$enum_index];
					}
				}
				$size1 = 1.3 * strlen($output_value);
				$datapart .= '<input
					class="' . $field_class . '"
					readonly
					style="
						border: 0px;
						background-color: transparent;
						font-size: ' . $fontsize . 'px;
					"
					size="' . $size1 . '"
					type="text"
					name="' . $fieldname . '" value="' . htmlspecialchars($output_value) . '"
				>';
			}
			else {
				$datapart .= '<select
					class="' . $field_class . '"
					id="' . $layer_id . '_' . $attributes['name'][$j] . '_' . $k . '"
					onchange="' . $onchange.'"
					title="'.$attributes['alias'][$j] . '"
					style="' . $select_width . 'font-size: ' . $fontsize.'px"
					name="' . $fieldname . '"
				>';
				if ($attributes['nullable'][$j] != '0' OR $gui->new_entry == true) {
					$datapart .= '<option value="">-- '.$gui->strPleaseSelect.' --</option>';
				}
				for ($e = 0; $e < count($attributes['enum_value'][$j]); $e++) {
					$datapart .= '<option'
						. ($attributes['enum_value'][$j][$e] == $dataset[$attributes['name'][$j]] ? ' selected' : '')
						. ' value="' . $attributes['enum_value'][$j][$e] . '">'
							. $attributes['enum_output'][$j][$e]
						. '</option>';
				}
				$datapart .= '</select>';
			}
		}
		else{
			switch ($attributes['form_element_type'][$j]){
				case 'Textfeld' : {
					$datapart .= '<textarea class="'.$field_class.'" title="'.$alias.'" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" id="'.$layer_id.'_'.$name.'_'.$k.'" cols="'.$size.'" onchange="'.$onchange.'"';
					if($attributes['length'][$j] AND !in_array($attributes['type'][$j], array('numeric', 'float4', 'float8', 'int2', 'int4', 'int8'))){
						$datapart .= ' maxlength="'.$attributes['length'][$j].'" ';
					}
					if($attribute_privileg == '0' OR $lock[$k]){
						$datapart .= ' readonly style="display: none"';
					}
					else{
						$datapart .= ' tabindex="1" style="width: 100%;font-size: '.$fontsize.'px"';
					}
					$datapart .= ' rows="3" name="'.$fieldname.'">' . htmlspecialchars($value) . '</textarea>';
					if($attribute_privileg > '0' AND $attributes['options'][$j] != ''){
						if(strtolower(substr($attributes['options'][$j], 0, 6)) == 'select'){
							$datapart .= '&nbsp;<a title="automatisch generieren" href="javascript:auto_generate(new Array(\''.implode("','", $attributes['name']).'\'), \''.$attributes['the_geom'].'\', \''.$name.'\', '.$k.', '.$layer_id.');'.$onchange.'"><img src="'.GRAPHICSPATH.'autogen.png"></a>';
						}
						else{
							$datapart .= '&nbsp;<a title="Eingabewerkzeug verwenden" href="javascript:openCustomSubform('.$layer_id.', \''.$name.'\', new Array(\''.implode("','", $attributes['name']).'\'), \''.$name.'_'.$k.'\', '.$k.');"><img src="'.GRAPHICSPATH.'autogen.png"></a>';
						}
					}
					if($attribute_privileg == '0' OR $lock[$k]){ // nur lesbares Attribut
						if($size == 16){		// spaltenweise
							$datapart .= htmlspecialchars($value);
						}
						else{								// zeilenweise
							$datapart .= '<div class="readonly_text" style="padding: 0 0 0 3; font-size: '.$fontsize.'px;"><pre>' . $value . '</pre></div>';
						}
					}
				}break;

				case 'Auswahlfeld' : {
					if(is_array($attributes['dependent_options'][$j])){
						$enum_value = $attributes['enum_value'][$j][$k];		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
						$enum_output = $attributes['enum_output'][$j][$k];		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
					}
					else{
						$enum_value = $attributes['enum_value'][$j];
						$enum_output = $attributes['enum_output'][$j];
					}
					if($attributes['nullable'][$j] != '0')$strPleaseSelect = '-';
					if($gui->new_entry == true)$strPleaseSelect = '-- '.$gui->strPleaseSelect.' --';
					$datapart .= Auswahlfeld($layer_id, $name, $j, $alias, $fieldname, $value, $enum_value, $enum_output, $attributes['req_by'][$j], $attributes['req'][$j], $attributes['name'], $attribute_privileg, $k, $oid, $attributes['subform_layer_id'][$j], $attributes['subform_layer_privileg'][$j], $attributes['embedded'][$j], $lock[$k], $select_width, $fontsize, $strPleaseSelect, $change_all, $onchange, $field_class, $attributes['datatype_id'][$j]);
				} break;
				
				case 'Farbauswahl' : {
					if ($gui->result_colors == '') {
						$gui->result_colors = $gui->database->read_colors();
					}
					$datapart .= '
						<select class="'.$field_class.'" tabindex="1" name="'.$fieldname.'" id="'.$layer_id.'_'.$name.'_'.$e.'_'.$k.'" style="width: 80px; background-color: rgb(' . $value . ')" onchange="' . $onchange . ';this.setAttribute(\'style\', this.options[this.selectedIndex].getAttribute(\'style\'));">';
						for($i = 0; $i < count($gui->result_colors); $i++){
							$rgb = $gui->result_colors[$i]['red'] . ' ' . $gui->result_colors[$i]['green'] . ' ' . $gui->result_colors[$i]['blue'];
							$datapart .= '<option ';
							if ($value == $rgb){
								$datapart .= ' selected';
							}
							$datapart .= '	style="width: 80px; background-color: rgb(' . $rgb . ')"
															value="' . $rgb . '">
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														</option>' . "\n";
						}
					$datapart .= '</select>';
				} break;
				
				case 'Autovervollständigungsfeld' : {
					$datapart .= Autovervollstaendigungsfeld($layer_id, $name, $j, $alias, $fieldname, $value, $attributes['enum_output'][$j][$k], $attribute_privileg, $k, $oid, $attributes['subform_layer_id'][$j], $attributes['subform_layer_privileg'][$j], $attributes['embedded'][$j], $lock[$k], $fontsize, $change_all, $size, $onchange, $field_class);
				} break;

				case 'Autovervollständigungsfeld_zweispaltig' : {
					$datapart .= Autovervollstaendigungsfeld_zweiSpaltig($layer_id, $name, $j, $alias, $fieldname, $value, $attributes['enum_output'][$j][$k], $attribute_privileg, $k, $oid, $attributes['subform_layer_id'][$j], $attributes['subform_layer_privileg'][$j], $attributes['embedded'][$j], $lock[$k], $fontsize, $change_all, $size, $onchange, $field_class);
				} break;
				
				case 'Radiobutton' : {
					$enum_value = $attributes['enum_value'][$j];
					$enum_output = $attributes['enum_output'][$j];
					if($change_all){
						$onchange = 'change_all('.$layer_id.', '.$k.', \''.$name.'\');';
					}						
					for($e = 0; $e < count($enum_value); $e++){
						$datapart .= '<input class="'.$field_class.'" tabindex="1" type="radio" name="'.$fieldname.'" id="'.$layer_id.'_'.$name.'_'.$e.'_'.$k.'"';
						$datapart .= ' onchange="'.$onchange.'" ';
						if ($enum_value[$e] == $value) {
							$datapart .= 'checked ';
						}
						$datapart .= ' onclick="'.($attribute_privileg == '0'? 'return false;' : '').'if(this.checked2 == undefined){this.checked2 = true;}this.checked = this.checked2; if(this.checked===false){var evt = document.createEvent(\'HTMLEvents\');evt.initEvent(\'change\', false, true); this.dispatchEvent(evt);}" onmousedown="this.checked2 = !this.checked;"';
						$datapart .= 'value="'.$enum_value[$e].'"><label for="'.$layer_id.'_'.$name.'_'.$e.'_'.$k.'" style="margin-right: 15px">'.$enum_output[$e].'</label>';
						if(!$attributes['horizontal'][$j] OR (is_numeric($attributes['horizontal'][$j]) AND($e+1) % $attributes['horizontal'][$j] == 0))$datapart .= '<br>';
					}
				}break;				
				
				case 'Checkbox' : {
					$datapart .= '<input class="'.$field_class.'" type="checkbox" id="'.$layer_id.'_'.$name.'_'.$k.'" title="'.$alias.'" cols="45" onchange="'.$onchange.'"';
					if($attribute_privileg == '0' OR $lock[$k]){
						$datapart .= ' onclick="return false"';
					}
					else{
						$datapart .= ' tabindex="1" ';
					}
					$datapart .= 'value="t" name="'.$fieldname.'"';
					if ($value == 't') $datapart .= 'checked=true';
					$datapart .= '>';
				}break;

				case 'SubFormPK' : {
					$datapart .= '<table width="98%" cellspacing="0" cellpadding="0"><tr><td>';
					if($size == 16){		// spaltenweise
						$datapart .= htmlspecialchars($value);
					}
					else{								// zeilenweise
						$maxwidth = $size * 8;
						$minwidth = $size * 4;
						$datapart .= '<div style="padding: 0 0 0 3; min-width: '.$minwidth.'px; max-width:'.$maxwidth.'px; font-size: '.$fontsize.'px;">' . htmlspecialchars($value) . '</div>';
					}
					$datapart .= '</td>';
					if($gui->new_entry != true){
						$datapart .= '<td width="100%" align="right">';
						if ($value != '') {
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
						if ($attributes['subform_layer_privileg'][$j] > 0 AND $attribute_privileg > 0){
							$datapart .= '<a href="javascript:void(0);" onclick="overlay_link(\'go=neuer_Layer_Datensatz&subform=true&selected_layer_id=' . $attributes['subform_layer_id'][$j] . '&csrf_token=' . $_SESSION['csrf_token'];
							for ($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++) {
								$datapart .= '&attributenames[' . $p . ']='.$attributes['subform_pkeys'][$j][$p];
								$datapart .= '&values[' . $p . ']=\' + document.getElementById(\'' . $layer_id . '_' . $attributes['subform_pkeys'][$j][$p] . '_' . $k . '\').value)';
							}
							$datapart .= 	'"';
							if ($attributes['no_new_window'][$j] != true){
								$datapart .= 	' target="_blank"';
							}
							$datapart .= 	' class="buttonlink"><span>' . $strNewPK . '</span></a>&nbsp;';
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
						$fieldname_[$f] = $layer_id.';'.$attributes['real_name'][$name_].';'.$tablename_.';'.$oid.';'.$attributes['form_element_type'][$index].';'.$attributes['nullable'][$index].';'.$attributes['type'][$index].';'.$attributes['saveable'][$index];
						if($dataset[$name_] == '')$dataset[$name_] = $gui->formvars[$fieldname_[$f]];
						switch ($attributes['form_element_type'][$attribute_foreign_keys[$f]]){
							case 'Autovervollständigungsfeld' : {
								if($attributes['subform_layer_privileg'][$index] != '0')$gui->editable = $layer_id;
								$datapart .= Autovervollstaendigungsfeld($layer_id, $name_, $index, $attributes['alias'][$name_], $fieldname_[$f], $dataset[$name_], $attributes['enum_output'][$index][$k], $attributes['privileg'][$name_], $k, $oid, $attributes['subform_layer_id'][$index], $attributes['subform_layer_privileg'][$index], $attributes['embedded'][$index], $lock[$k], $fontsize, $change_all, $size, $onchange, $field_class);
							}break;
							case 'Autovervollständigungsfeld zweispaltig' : {
								if($attributes['subform_layer_privileg'][$index] != '0')$gui->editable = $layer_id;
								$datapart .= Autovervollstaendigungsfeld_zweispaltig($layer_id, $name_, $index, $attributes['alias'][$name_], $fieldname_[$f], $dataset[$name_], $attributes['enum_output'][$index][$k], $attributes['privileg'][$name_], $k, $oid, $attributes['subform_layer_id'][$index], $attributes['subform_layer_privileg'][$index], $attributes['embedded'][$index], $lock[$k], $fontsize, $change_all, $size, $onchange, $field_class);
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
								$onchange = 'set_changed_flag(this, \'changed_'.$layer_id.'_'.$oid.'\');';
								$datapart .= Auswahlfeld($layer_id, $name_, $j, $attributes['alias'][$name_], $fieldname_[$f], $dataset[$name_], $enum_value, $enum_output, $attributes['req_by'][$index], $attributes['req'][$index], $attributes['name'], $attributes['privileg'][$name_], $k, $oid, $attributes['subform_layer_id'][$index], $attributes['subform_layer_privileg'][$index], $attributes['embedded'][$index], $lock[$k], $select_width, $fontsize, $strPleaseSelect, $change_all, $onchange, $field_class);
							}break;
							default : {
								$datapart .= '<input class="'.$field_class.'" style="font-size: '.(0.9*$fontsize).'px';
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
					if($size == 16){		// spaltenweise
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
							$datapart .= '<a class="buttonlink" href="javascript:overlay_link(\'go=Layer-Suche_Suchen&selected_layer_id=' . $attributes['subform_layer_id'][$j] . '&csrf_token=' . $_SESSION['csrf_token'];
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
					$reloadParams= '&selected_layer_id='.$attributes['subform_layer_id'][$j];
					for($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++){
						if (strpos($attributes['subform_pkeys'][$j][$p], ':')) {
							$exp = explode(':', $attributes['subform_pkeys'][$j][$p]);
							$key = $exp[0];			# Verknüpfungsattribut in diesem Layer
							$subkey = $exp[1];	# Verknüpfungsattribut im Sub-Layer
						}
						else {
							$key = $subkey = $attributes['subform_pkeys'][$j][$p];
						}
						if($dataset[$key] == '')$subform_request = false;		// eines der Verknüpfungsattribute ist leer -> keinen Subform-Request machen
						$reloadParams .= '&value_'.$subkey.'='.$dataset[$key];
						$reloadParams .= '&operator_'.$subkey.'==';
						$reloadParams .= '&attributenames['.$p.']='.$subkey;
						$reloadParams .= '&values['.$p.']='.$dataset[$key];
					}
					$reloadParams .= '&preview_attribute='.$attributes['preview_attribute'][$j];
					$reloadParams .= '&count='.$k;
					$reloadParams .= '&no_new_window='.$attributes['no_new_window'][$j];
					$reloadParams .= '&embedded_subformPK=true';
					if($attributes['embedded'][$j] == true)$reloadParams .= '&embedded=true';
					if($attributes['list_edit'][$j] == true)$reloadParams .= '&list_edit=true';
					if($attributes['show_count'][$j] == true)$reloadParams .= '&show_count=true';
					$reloadParams .= '&targetobject='.$layer_id.'_'.$name.'_'.$k;
					$reloadParams .= '&fromobject='.$layer_id.'_'.$name.'_'.$k;
					$reloadParams .= '&targetlayer_id='.$layer_id;
					$reloadParams .= '&targetattribute='.$name;
					$reloadParams .= '&reload='.$attributes['reload'][$j];
					$reloadParams .= '&oid_mother='.$dataset[$attributes['table_name'][$key].'_oid'];			# die oid des Datensatzes und wird mit übergeben, für evtl. Zoom auf den Datensatz
					$reloadParams .= '&tablename_mother='.$attributes['table_name'][$attributes['the_geom']];											# dito
					$reloadParams .= '&columnname_mother='.$attributes['the_geom'];																								# dito
					$reloadParams .= '&attribute_privileg='.$attribute_privileg;
					
					$datapart .= '<div id="'.$layer_id.'_'.$name.'_'.$k.'" data-reload_params="'.$reloadParams.'" style="margin-top: 3px">';
					if($gui->new_entry != true){
						$subform_request = true;
						$datapart .= '
							<img src="' . GRAPHICSPATH . 'leer.gif" onload="reload_subform_list(this.parentElement);">
						';
					}
					$datapart .= '</div><table width="98%" cellspacing="0" cellpadding="2"><tr style="border: none"><td width="100%" align="right">';
					$datapart .= '</td></tr></table>';					
				}break;

				case 'Time': {
					$datapart .= '<input class="'.$field_class.'" readonly style="padding: 0 0 0 3;border:0px;background-color:transparent;font-size: '.$fontsize.'px;"';
					$datapart .= ' size="16" type="text" name="'.$fieldname.'" value="' . htmlspecialchars($value) . '">';
				}break;

				case 'Dokument': {
					if ($value != '') {
						$preview = $gui->get_dokument_vorschau($value, $layer['document_path'], $layer['document_url']);
						if ($preview['doc_src'] != '') {
							$datapart .= '<table border="0"><tr><td>';
							if ($hover_preview) {
								$onmouseover = 'onmouseenter="root.document.getElementById(\'vorschau\').style.border=\'1px solid grey\';root.document.getElementById(\'preview_img\').src=this.src" onmouseleave="root.document.getElementById(\'vorschau\').style.border=\'none\';root.document.getElementById(\'preview_img\').src=\''.GRAPHICSPATH.'leer.gif\'"';
							}
							switch ($preview['doc_type']) {
								case 'local_img' : { # Bilder mit Vorschaubild
									$datapart .= '<a href="' . $preview['doc_src'] . '" ' . $preview['target'] . '><img class="preview_image" src="' . $preview['thumb_src'] . '" ' . $onmouseover . '></a>';
								} break;

								case 'local_doc' : case 'remote_url' : { # lokale Dateien oder fremde URLs
									$datapart .= '<a href="' . $preview['doc_src'] . '" ' . $preview['target'] . '><img class="preview_doc" src="' . $preview['thumb_src'] . '"></a>';
								} break;

								case 'videostream' : { # Videostream
									$datapart .= '
										<video width="' . PREVIEW_IMAGE_WIDTH . '" controls>
											<source src="' . $preview['doc_src'] . '" type="video/mp4">
										</video>
									';
								} break;
							}
							$datapart .= '<br>';
							if ($attribute_privileg != '0' AND !$lock[$k]) {
								//$datapart .= '<a href="javascript:delete_document(\''.$fieldname.'\');"><span>Dokument <br>löschen</span></a>';
								$datapart .= '<a href="javascript:delete_document(\'' . $fieldname . '\', ' . $layer_id . ', \'' . $gui->formvars['fromobject'] . '\', \'' . $gui->formvars['targetobject'] . '\',  \'' . $gui->formvars['reload'] . '\');"><span>Dokument löschen</span></a>';
							}
							$datapart .= '</td></tr>';
							$datapart .= '<tr><td colspan="2"><span id="image_original_name">' . $preview['original_name'] . ' (' . $preview['filesize'] . ')</span></td></tr>';
							$datapart .= '</table>';
						}
						else {
							$datapart .= '<div>';
							$datapart .= 'Oooops!<p>';
							$datapart .= 'Die Datei ' . $dateipfad . ' wurde nicht auf dem Server gefunden.';
							if ($layer['document_url'] == '') {
								$datapart .= '<br>Der originale Name der Datei war ' . $preview['original_name'];
							}
							$datapart .= '<br>Laden Sie die Datei neu auf den Server hoch oder fragen Sie Ihren Administrator warum die Datei auf dem Server fehlt und lassen Sie sie wiederherstellen.';
							$datapart .= '</div>';
						}
						$datapart .= '<input type="hidden" name="'.$fieldname.'_alt" class="' . $field_class . '" value="' . htmlspecialchars($value) . '">';
					}
					if ($attribute_privileg != '0') {
						$datapart .= '
							<label id="label_'.$id.'" for="'.$id.'" class="buttonlink" style="position: relative;">
								<input
									tabindex="1"
									onchange="'.$onchange.'; if(this.files){document.getElementById(\'label_'.$id.'\').lastElementChild.innerHTML = this.files[0][\'name\']};"
									style=" position: absolute;
													top: 0;
													left: 0;
													opacity: 0;
													width: 100%;
													height: 100%;
													display: block;"
									size="43"
									type="file"
									id="'.$id.'"
									class="' . $field_class . '" name="' . $fieldname . '"
								>
								<span>Durchsuchen...</span>
							</label>
							';
					}
					else {
						$datapart .= '&nbsp;';
					}
				} break;

				case 'Link': {
					if ($attribute_privileg != '0' OR $lock[$k]) {
						$datapart .= '<input class="'.$field_class.'" tabindex="1" onchange="'.$onchange.'" id="'.$layer_id.'_'.$name.'_'.$k.'" style="font-size: '.$fontsize.'px" size="'.$size.'" type="text" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
					}
					else {
						$datapart .= '<input class="'.$field_class.'" onchange="'.$onchange.'" type="hidden" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
					}
					if ($value!='') {
						if (substr($value, 0, 4) == 'http') {
							$target = '_blank';
						}
						else {
							$target = 'root';
						}
						$datapart .= '<div class="formelement-link"><a class="link" target="'.$target.'" style="font-size: '.$fontsize.'px" href="' . htmlspecialchars($value) .'">';
						if ($attributes['options'][$j] != '') {
							$datapart .= $attributes['options'][$j];
						}
						else {
							$datapart .= htmlspecialchars(basename($value));
						}
						$datapart .= '</a></div>';
					}
				 } break;

				case 'dynamicLink': {
					$show_link = false;
					$one_param_is_null = false;
					$options = $attributes['options'][$j];
					for ($a = 0; $a < count($attributes['name']); $a++) {
						if(strpos($options, '$'.$attributes['name'][$a]) !== false){
							$options = str_replace('$'.$attributes['name'][$a], $dataset[$attributes['name'][$a]], $options);
							if ($dataset[$attributes['name'][$a]] == '') {
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
					if ($explosion[3] == 'all_null'){
						$show_link = true;
					}
					$datapart .= '<input class="'.$field_class.'" onchange="'.$onchange.'" type="hidden" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
					if ($show_link) {
						if ($explosion[2] == 'embedded'){
							$datapart .= '<a class="dynamicLink" href="javascript:void(0);" onclick="if(document.getElementById(\'dynamicLink'.$layer_id.'_'.$k.'_'.$j.'\').innerHTML != \'\'){clearsubform(\'dynamicLink'.$layer_id.'_'.$k.'_'.$j.'\');} else {ahah(\''.urlencode2($href).'&embedded=true\', \'\', new Array(document.getElementById(\'dynamicLink'.$layer_id.'_'.$k.'_'.$j.'\')), new Array(\'sethtml\'))}">';
							$datapart .= $alias;
							$datapart .= '</a><br>';
							$datapart .= '<div style="display:inline" id="dynamicLink'.$layer_id.'_'.$k.'_'.$j.'"></div>';
						}
						else {
							switch ($explosion[2]) { 
								case 'no_new_window' : {
									$link_target = '_self';
								} break;
								case 'root' : {
									$link_target = 'root';
								} break;
								default : {
									$link_target = '_blank';
								}
							}
							if ($explosion[2] == 'no_new_window' AND substr($href, 0, 10) != 'javascript') {
								$onclick = 'checkForUnsavedChanges(event);adjustHref(this);';
							}
							# link_parts: link_type:link_url
							$link_type = explode(':', $href)[0];
							$link_url = explode(':', $href)[1];
							switch ($link_type) {
								case 'mailto' : {
									$url_parts = explode('?', $link_url);
									$mail_addresses = explode(' ', $url_parts[0]);
									$params = array();
									if (count($mail_addresses) > 1) {
										# append extra email addresses in cc deliminated by ;
										$params[] = 'cc=' . implode(';', array_slice($mail_addresses, 1));
									}
									$params[] = $url_parts[1];
									$href = $link_type . ':' . $mail_addresses[0] . '?' . implode('&', $params);
								} break;								
							}
							
							$datapart .= '<a
								tabindex="1"
								target="' . $link_target . '"
								class="dynamicLink"
								style="font-size: ' . $fontsize . 'px"
								onclick="' . $onclick . '"
								href="' . urlencode2(add_csrf($href)) . '"
							>' . $alias . '</a><br>';
						}
					}
				} break;
				
				case 'mailto': {
					if ($value!='') {
						$datapart .= '<a style="padding: 0 0 0 3;" class="link" target="_blank" style="font-size: '.$fontsize.'px" href="mailto:' . htmlspecialchars($value) . '">';
						if($attributes['options'][$j] != ''){
							$datapart .= $attributes['options'][$j];
						}
						else{
							$datapart .= htmlspecialchars(basename($value));
						}
						$datapart .= '</a><br>';
					}
					if($attribute_privileg != '0' OR $lock[$k]){
						$datapart .= '<input class="'.$field_class.'" tabindex="1" onchange="'.$onchange.'" id="'.$layer_id.'_'.$name.'_'.$k.'" style="font-size: '.$fontsize.'px" size="'.$size.'" type="text" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
					}else{
						$datapart .= '<input class="'.$field_class.'" type="hidden" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
					}
				} break;

				case 'Fläche': {
					$datapart .= '<input class="'.$field_class.' custom_area" onchange="'.$onchange.'" id="'.$layer_id.'_'.$name.'_'.$k.'" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$alias.'" ';
					if($attribute_privileg == '0' OR $lock[$k]){
						$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;"';
					}
					else{
						$datapart .= ' style="font-size: '.$fontsize.'px;"';
					}
					$datapart .= ' size="'.$size.'" type="text" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
				}break;
				
				case 'Länge': {
					$datapart .= '<input class="'.$field_class.'" onchange="'.$onchange.'" id="custom_length" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$alias.'" ';
					if($attribute_privileg == '0' OR $lock[$k]){
						$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;"';
					}
					else{
						$datapart .= ' style="font-size: '.$fontsize.'px;"';
					}
					$datapart .= ' size="'.$size.'" type="text" name="'.$fieldname.'" value="'.htmlspecialchars($value).'">';
				}break;
				
				case 'Winkel': {
					$datapart .= '<input class="'.$field_class.'" onchange="'.$onchange.'" id="custom_angle" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$alias.'" ';
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
					$datapart .= '<input class="'.$field_class.'" onchange="'.$onchange.'" onkeyup="checknumbers(this, \'Zahl\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$alias.'" ';
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

				case 'ExifLatLng': {
					$datapart .= '<input class="'.$field_class.'" onchange="'.$onchange.'" id="custom_latlng" ';
					if ($attribute_privileg == '0' OR $lock[$k]) {
						$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: ' . $fontsize . 'px;"';
					}
					else {
						$datapart .= ' style="font-size: ' . $fontsize . 'px;"';
					}
					$datapart .= ' size="' . $size . '" type="text" name="' . $fieldname . '" value="'.htmlspecialchars($value) . '">';
				}break;

				case 'ExifRichtung': {
					$datapart .= '<input class="'.$field_class.'" onchange="'.$onchange.'" id="custom_richtung" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$alias.'" ';
					if ($attribute_privileg == '0' OR $lock[$k]) {
						$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;"';
					}
					else{
						$datapart .= ' style="font-size: '.$fontsize.'px;"';
					}
					$datapart .= ' size="' . $size . '" type="text" name="' . $fieldname . '" value="' . htmlspecialchars($value) . '">';
				}break;

				case 'ExifErstellungszeit': {
					$datapart .= '<input class="'.$field_class.'" onchange="'.$onchange.'" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$alias.'" ';
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
					if($size)$datapart .= ' size="'.$size.'"';
					$datapart .= ' type="text" name="'.$fieldname.'" id="'.$layer_id.'_'.$name.'_'.$k.'" value="'.htmlspecialchars($value).'">';
					if($attribute_privileg == '0' OR $lock[$k]){ // nur lesbares Attribut
						$angezeigter_value = (($attributes['type'][$j] == 'bool' OR $attributes['form_element_type'][$j] == 'Editiersperre') ? ($value == 't' ? $gui->strYes : $gui->strNo) : $value);
						$datapart .= '<div class="readonly_text" style="font-size: '.$fontsize.'px;">' . htmlspecialchars($angezeigter_value) . '</div>';
					}
				} break;

				default : {
					$datapart .= '<input class="'.$field_class.'" onchange="'.$onchange.'" onkeyup="checknumbers(this, \''.$attributes['type'][$j].'\', \''.$attributes['length'][$j].'\', \''.$attributes['decimal_length'][$j].'\');" title="'.$alias.'" ';
					if($attribute_privileg == '0'){
						$datapart .= ' readonly style="display:none;"';
					}
					else{
						$datapart .= ' tabindex="1" style="width: 100%; font-size: '.$fontsize.'px;"';
					}
					if($name == 'lock'){
						$datapart .= ' type="hidden"';
					}
					if (in_array($attributes['type'][$j], ['numeric', 'float4', 'float8', 'int2', 'int4', 'int8'])) {
						$value = str_replace('.', ',', $value);
					}
					elseif ($attributes['length'][$j]) {
						$datapart .= ' maxlength="'.$attributes['length'][$j].'"';
					}				
					if($size)$datapart .= ' size="'.$size.'"';
					$datapart .= ' type="text" name="'.$fieldname.'" id="'.$layer_id.'_'.$name.'_'.$k.'" value="'.htmlspecialchars($value).'">';
					if($attribute_privileg == '0'){ // nur lesbares Attribut
						$angezeigter_value = (($attributes['type'][$j] == 'bool' OR $attributes['form_element_type'][$j] == 'Editiersperre') ? ($value == 't' ? $gui->strYes : $gui->strNo) : $value);
						$datapart .= '<div class="readonly_text" style="font-size: '.$fontsize.'px;">' . htmlspecialchars($angezeigter_value) . '</div>';
					}
					if($attribute_privileg > '0' AND $attributes['options'][$j] != '' AND strpos($attributes['options'][$j], 'require') === false){		# bei <requires> oder <required by> nicht
						if(strtolower(substr($attributes['options'][$j], 0, 6)) == 'select') {
							$datapart .= '&nbsp;<a title="automatisch generieren" href="javascript:auto_generate(new Array(\''.implode("','", $attributes['name']).'\'), \''.$attributes['the_geom'].'\', \''.$name.'\', '.$k.', '.$layer_id.');'.$onchange.'"><img src="'.GRAPHICSPATH.'autogen.png"></a>';
						}
						else{
							$datapart .= '&nbsp;<a title="Eingabewerkzeug verwenden" href="javascript:openCustomSubform('.$layer_id.', \''.$name.'\', new Array(\''.implode("','", $attributes['name']).'\'), \''.$layer_id.'_'.$name.'_'.$k.'\', '.$k.');"><img src="'.GRAPHICSPATH.'autogen.png"></a>';
						}
					}
				}
			}
		}
		return $datapart.$after_attribute;
	}

	function Autovervollstaendigungsfeld($layer_id, $name, $j, $alias, $fieldname, $value, $output, $privileg, $k, $oid, $subform_layer_id, $subform_layer_privileg, $embedded, $lock, $fontsize, $change_all, $size, $onchange, $field_class){
		$element_id = $layer_id . '_' . $name . '_' . $k;
		$dataparts = array();

		if ($change_all) {
			$onchange = 'change_all('.$layer_id.', '.$k.', \''.$layer_id.'_'.$name.'\');';
			$onchange_output = 'change_all('.$layer_id.', '.$k.', \'output_'.$layer_id.'_'.$name.'\');';
		}
		# datapart besteht aus
		# - autofeld_zweispaltig_auswahl_und_suggest_div
		# - bei Subformverknüpfung und Anlegerechten entweder
		# 	- bei embedded der Neu Button und ein div für subform
		#		- oder ein Link zum Anlegen eines neuen Datensatzes
		# - dargestellt in 1, 2 oder 3 Spalten		
		if ($subform_layer_id != '' AND $subform_layer_privileg > 0) {
			if ($embedded == true) {
				$href = 'javascript:void(0);" onclick="ahah(
					\'index.php\',
					\'go=neuer_Layer_Datensatz&selected_layer_id=' . $subform_layer_id . '&embedded=true&fromobject=subform' . $layer_id . '_' . $k . '_' . $j . '&targetobject=' . $element_id . '&targetlayer_id=' . $layer_id . '&targetattribute=' . $name . '\',
					new Array(document.getElementById(\'subform' . $layer_id . '_' . $k . '_' . $j . '\')),
					new Array(\'sethtml\')
				)';
				$subform_div = '<td><div style="display:inline" id="subform' . $layer_id . '_' . $k . '_' . $j . '"></div></td>';
			}
			else {
				$href = 'index.php?go=neuer_Layer_Datensatz&selected_layer_id=' . $subform_layer_id . '&csrf_token=' . $_SESSION['csrf_token'];
			}
			$new_button = '<td>&nbsp;&nbsp;<a class="buttonlink" href="' . $href . '">&nbsp;neu&nbsp;</a></td>';
		}
		$datapart = '
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<div id="autofeld_zweispaltig_auswahl_und_suggest_div">
							<input
								autocomplete="off"
								title="' . $alias . '"
								onkeydown="
									if (this.backup_value == undefined) {
										this.backup_value = this.value;
										document.getElementById(\'' . $element_id . '\').backup_value = document.getElementById(\'' . $element_id . '\').value;
									}
								"
								onkeyup="
									autocomplete1(event, \'' . $layer_id . '\', \'' . $name . '\', \'' . $element_id . '\', this.value);
								"
								onchange="
									if(document.getElementById(\'suggests_' . $element_id . '\').style.display == \'block\') {
										this.value = this.backup_value;
										document.getElementById(\'' . $element_id . '\').value = document.getElementById(\'' . $element_id . '\').backup_value;
										setTimeout(function(){
											document.getElementById(\'suggests_' . $element_id . '\').style.display = \'none\';
											},
											500
										);
									}' . $onchange_output . '
									if (\'' . $oid . '\' != \'\') {
										set_changed_flag(this, \'changed_' . $layer_id . '_' . $oid . '\')
									}
								"' .
								(($privileg == '0' OR $lock)
									? ' readonly style="border:0px;background-color:transparent;font-size: ' . $fontsize . 'px;"'
									: ' tabindex="1" style="font-size: ' . $fontsize . 'px;"'
								) . '
								size="' . $size . '"
								type="text"
								id="output_' . $element_id . '"
								value="' . htmlspecialchars($output) . '"
							>
							<input
								class="' . $field_class . '"
								type="hidden"
								onchange="' . $onchange . ';"
								name="' . $fieldname . '"
								id="' . $element_id . '"
								value="' . htmlspecialchars($value) . '"
							>
							<div valign="top" style="height:0px; position:relative;">
								<div
									id="suggests_' . $element_id . '"
									style="
										z-index: 3000;
										display:none;
										position:absolute;
										left:0px; top:0px;
										width: 400px;
										vertical-align:top;
										overflow:hidden;
										border:solid grey 1px;
									"
								></div>
							</div>
						</div>
					</td>
					' . $new_button . '
					' . $subform_div . '
				</tr>
			</table>
		';
		return $datapart;
	}

	function Autovervollstaendigungsfeld_zweispaltig($layer_id, $name, $j, $alias, $fieldname, $value, $output, $privileg, $k, $oid, $subform_layer_id, $subform_layer_privileg, $embedded, $lock, $fontsize, $change_all, $size, $onchange, $field_class) {
		$element_id = $layer_id . '_' . $name . '_' . $k;
		$dataparts = array();

		if ($change_all) {
			$onchange = 				'change_all(' . $layer_id . ', ' . $k . ', \'' . $layer_id . '_' . $name . '\');';
			$onchange_output = 	'change_all(' . $layer_id . ', ' . $k . ', \'output_' . $layer_id.'_'.$name . '\');';
		}
		# datapart besteht aus
		# - autofeld_zweispaltig_auswahl_und_suggest_div
		# - bei Subformverknüpfung und Anlegerechten entweder
		# 	- bei embedded der Neu Button und ein div für subform
		#		- oder ein Link zum Anlegen eines neuen Datensatzes
		# - dargestellt in 1, 2 oder 3 Spalten		
		if ($subform_layer_id != '' AND $subform_layer_privileg > 0) {
			if ($embedded == true) {
				$href = 'javascript:void(0);" onclick="ahah(
					\'index.php\',
					\'go=neuer_Layer_Datensatz&selected_layer_id=' . $subform_layer_id.'&embedded=true&fromobject=subform'.$layer_id.'_'.$k.'_'.$j.'&targetobject=' . $element_id . '&targetlayer_id=' . $layer_id.'&targetattribute='.$name.'\',
					new Array(document.getElementById(\'subform'.$layer_id.'_'.$k.'_'.$j.'\')),
					new Array(\'sethtml\')
				)';
				$subform_div = '<td><div style="display:inline" id="subform' . $layer_id . '_' . $k . '_' . $j . '"></div></td>';
			}
			else {
				$href = 'index.php?go=neuer_Layer_Datensatz&selected_layer_id=' . $subform_layer_id . '&csrf_token=' . $_SESSION['csrf_token'];
			}
			$new_button = '<td>&nbsp;&nbsp;<a class="buttonlink" href="' . $href . '">&nbsp;neu&nbsp;</a></td>';
		}
		$datapart = '
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<div id="autofeld_zweispaltig_auswahl_und_suggest_div">
							<input
								id="output_' . $element_id . '"
								title="' . $alias . '"
								autocomplete="off"
								onkeydown="
									if (this.backup_value == undefined) {
										this.backup_value = this.value;
										document.getElementById(\'' . $element_id . '\').backup_value = document.getElementById(\'' . $element_id . '\').value;
									}
								"
								onkeyup="
									autocomplete1(event, \'' . $layer_id . '\', \'' . $name . '\', \'' . $element_id . '\', this.value, \'zweispaltig\');
								"
								onchange="
									if (document.getElementById(\'suggests_' . $element_id . '\').style.display == \'block\') {
										this.value = this.backup_value;
										document.getElementById(\'' . $element_id . '\').value = document.getElementById(\'' . $element_id . '\').backup_value;
										setTimeout(function() {
												document.getElementById(\'suggests_' . $element_id . '\').style.display = \'none\';
											},
											500
										);
									}
									' . $onchange_output . '
									if (\'' . $oid . '\' != \'\') {
										set_changed_flag(this, \'changed_' . $layer_id . '_' . $oid . '\')
									}
								"' .
								(($privileg == '0' OR $lock)
									? ' readonly style="border:0px;background-color:transparent;font-size: ' . $fontsize . 'px;"'
									: ' tabindex="1" style="font-size: ' . $fontsize . 'px;"'
								) . '
								size="' . $size . '"
								type="text"
								value="' . htmlspecialchars($output) . '"
							>
							<input
								id="' . $element_id . '"
								class="' . $field_class . '"
								type="hidden"
								onchange="' . $onchange . ';"
								name="' . $fieldname . '"
								value="' . htmlspecialchars($value) . '"
							>
							<div valign="top" style="height:0px; position:relative;">
								<div
									id="suggests_' . $element_id . '"
									style="
										z-index: 3000;
										display:none;
										position:absolute;
										left:0px; top:0px;
										width: 400px;
										vertical-align:top;
										overflow:hidden;
										border:solid grey 1px;
									"
								></div>
							</div>
						</div>
					</td>
					' . $new_button . '
					' . $subform_div . '
				</tr>
			</table>
		';
		return $datapart;
	}

	function Auswahlfeld($layer_id, $name, $j, $alias, $fieldname, $value, $enum_value, $enum_output, $req_by, $req, $attributenames, $privileg, $k, $oid, $subform_layer_id, $subform_layer_privileg, $embedded, $lock, $select_width, $fontsize, $strPleaseSelect, $change_all, $onchange, $field_class, $datatype_id = ''){
		if (!is_array($req)) {
			$req = array();
		}
		if($privileg == '0' OR $lock){
			for($e = 0; $e < @count($enum_value); $e++){
				if($enum_value[$e] == $value){
					$auswahlfeld_output = $enum_output[$e];
					$auswahlfeld_output_laenge=strlen($auswahlfeld_output)+1;
					break;
				}
			}
			$datapart .= '<input readonly id="'.$layer_id.'_'.$name.'_'.$k.'" style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;" size="'.$auswahlfeld_output_laenge.'" type="text" value="'.$auswahlfeld_output.'">';
			$datapart .= '<input type="hidden" name="'.$fieldname.'" class="'.$field_class.'" onchange="'.$onchange.'" value="' . htmlspecialchars($value) . '">';		// falls das Attribut ein visibility-changer ist
			$auswahlfeld_output = '';
			$auswahlfeld_output_laenge = '';
		}
		else{
			if($change_all){
				$onchange = 'change_all('.$layer_id.', '.$k.', \''.$layer_id.'_'.$name.'\');';
			}
			$datapart .= '<select class="'.$field_class.'" tabindex="1" title="'.$alias.'" style="'.$select_width.'font-size: '.$fontsize.'px"';
			if($req_by != ''){
				$onchange = 'update_require_attribute(this, \''.$req_by.'\', '.$k.','.$layer_id.', new Array(\''.implode("','", $attributenames).'\'));'.$onchange;
			}
			$datapart .= ' onchange="'.$onchange.'" ';
			if($datatype_id != '')$datapart .= ' data-datatype_id="'.$datatype_id.'" ';
			$datapart .= 'id="'.$layer_id.'_'.$name.'_'.$k.'" name="'.$fieldname.'">';
			if($strPleaseSelect)$datapart .= '<option value="">'.$strPleaseSelect.'</option>';
			for($e = 0; $e < @count($enum_value); $e++){
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
						$datapart .= '&nbsp;&nbsp;<a class="buttonlink" href="javascript:void(0);" onclick="ahah(\'index.php\', \'go=neuer_Layer_Datensatz&selected_layer_id=' . $subform_layer_id;
						for($p = 0; $p < count($req); $p++){
							$datapart .= '&attributenames['.$p.']='.$req[$p];
							$datapart .= '&values['.$p.']=\'+document.getElementById(\''.$layer_id.'_'.$req[$p].'_'.$k.'\').value+\'';
						}
						$datapart .= '&embedded=true&fromobject=subform'.$layer_id.'_'.$k.'_'.$j.'&targetobject='.$layer_id.'_'.$name.'_'.$k.'&targetlayer_id='.$layer_id.'&targetattribute='.$name.'\', new Array(document.getElementById(\'subform'.$layer_id.'_'.$k.'_'.$j.'\')), new Array(\'sethtml\'));">&nbsp;neu&nbsp;</a>';
						$datapart .= '<div style="display:inline" id="subform'.$layer_id.'_'.$k.'_'.$j.'"></div>';
					}
					else{
						$datapart .= '&nbsp;&nbsp;<a class="buttonlink" target="_blank" href="index.php?go=neuer_Layer_Datensatz&selected_layer_id=' . $subform_layer_id . '&csrf_token=' . $_SESSION['csrf_token'] . '">&nbsp;neu&nbsp;</a>';
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

	/*
	* Diese Funktion erzeugt ein class und/oder ein style Attribut eines html-Elementes
	* geführt von einem Leerzeichen je nach dem ob die in einem Array übergebenen Strings ein ":" enthalten (style) oder nicht (class).
	* @param array $class_or_style Ein Array welches beliebig viele Klassennamen oder Styledefinitionen enthalten kann
	* @return string Text in der Form ' class="class_name" style="css-text"'
	*/
	function get_td_class_or_style($class_or_style){
		foreach($class_or_style as $elem){
			if($elem != ''){
				if(strpos($elem, ':') === false)$class[] = $elem;
				else $style[] = $elem;
			}
		}
		if(!empty($class))$output = ' class="'.implode(' ', $class).'"';
		if(!empty($style))$output.= ' style="'.implode(';', $style).'"';
		return $output;
	}
	
	function getGeomType($column_geomtype, $layer_datatype){
		$geomtype = $column_geomtype;
		# Frage den Geometrietyp aus der Layerdefinition ab, wenn in geometry_columns nur als Geometry definiert.
		if ($geomtype == 'GEOMETRY' OR empty($geomtype)) {
			$geomtypes = array('POINT', 'LINESTRING', 'POLYGON');
			$geomtype = $geomtypes[$layer_datatype];
		}
		if ($geomtype == 'POLYGON' OR $geomtype == 'MULTIPOLYGON' OR $geomtype == 'GEOMETRY') {
			$geomtype = 'Polygon';
		}
		elseif ($geomtype == 'POINT') {
			$geomtype = 'Point';
		}
		elseif ($geomtype == 'MULTILINESTRING' OR $geomtype == 'LINESTRING') {
			$geomtype = 'Line';
		}
		return $geomtype;
	}
?>