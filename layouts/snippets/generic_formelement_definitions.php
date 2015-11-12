<?

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
	
	function Auswahlfeld($layer_id, $name, $j, $alias, $fieldname, $value, $enum_value, $enum_output, $req_by, $attributenames, $privileg, $k, $oid, $subform_layer_id, $subform_layer_privileg, $embedded, $lock, $fontsize, $strPleaseSelect){
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
			$datapart .= '<option value="">-- '.$strPleaseSelect.' --</option>';
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