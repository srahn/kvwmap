<?

	function Autovervollstaendigungsfeld($layer_id, $name, $alias, $fieldname, $value, $output, $privileg, $k, $oid, $subform_layer_id, $subform_layer_privileg, $embedded, $lock, $fontsize){
		$datapart = '<div style="float: left;">';
		$datapart .= '<input title="'.$alias.'" onkeydown="if(this.backup_value==undefined){this.backup_value=this.value; document.getElementById(\''.$name.'_'.$k.'\').backup_value=document.getElementById(\''.$name.'_'.$k.'\').value;}" onkeyup="autocomplete1(\''.$layer_id.'\', \''.$name.'\', \''.$name.'_'.$k.'\', this.value);" onchange="if(document.getElementById(\'suggests_'.$name.'_'.$k.'\').style.display==\'block\'){this.value=this.backup_value; document.getElementById(\''.$name.'_'.$k.'\').value=document.getElementById(\''.$name.'_'.$k.'\').backup_value}set_changed_flag(currentform.changed_'.$oid.')"';
		if($privileg == '0' OR $lock){
			$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;"';
		}
		else{
			$datapart .= ' style="font-size: '.$fontsize.'px;"';
		}
		$datapart .= ' size="'.$size.'" type="text" id="'.$name.'_'.$k.'_output" value="'.htmlspecialchars($output).'">';
		$datapart .= '<input type="hidden" readonly name="'.$fieldname.'" id="'.$name.'_'.$k.'" value="'.htmlspecialchars($value).'">';
		$datapart .= '<div valign="top" style="height:0px; position:relative;">
				<div id="suggests_'.$name.'_'.$k.'" style="z-index: 3000;display:none; position:absolute; left:0px; top:0px; width: 150px; vertical-align:top; overflow:hidden; border:solid grey 1px;"></div>
			</div>
		</div>';
		
		if($subform_layer_id != ''){
			if($subform_layer_privileg > 0){
				if($embedded == true){
					$datapart .= '<a href="javascript:ahah(\'index.php\', \'go=neuer_Layer_Datensatz&selected_layer_id='.$subform_layer_id.'&embedded=true&fromobject=subform'.$layer_id.'_'.$k.'_'.$j.'&targetobject='.$name.'_'.$k.'&targetlayer_id='.$layer_id.'&targetattribute='.$name.'\', new Array(document.getElementById(\'subform'.$layer_id.'_'.$k.'_'.$j.'\')), new Array(\'sethtml\'));clearsubforms('.$layer_id.');">&nbsp;neu&nbsp;</a>';
					$datapart .= '<div style="display:inline" id="subform'.$layer_id.'_'.$k.'_'.$j.'"></div>';
				}
				else{
					$datapart .= '<a href="index.php?go=neuer_Layer_Datensatz&selected_layer_id='.$subform_layer_id.'">&nbsp;neu&nbsp;</a>';
				}
			}
		}
		
		return $datapart;
	}

?>