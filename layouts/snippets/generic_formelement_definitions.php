<?

	function Autovervollstaendigungsfeld($layer_id, $name, $alias, $fieldname, $value, $privileg, $k, $oid, $lock, $fontsize){
		$datapart = '<div style="width:150px;">';
		$datapart .= '<input title="'.$alias.'" onkeydown="if(this.backup_value==undefined)this.backup_value=this.value" onkeyup="autocomplete1(\''.$layer_id.'\', \''.$name.'\', \''.$name.'_'.$k.'\', this.value);" onchange="if(document.getElementById(\'suggests_'.$name.'_'.$k.'\').style.display==\'block\')this.value=this.backup_value;set_changed_flag(currentform.changed_'.$oid.')"';
		if($privileg == '0' OR $lock){
			$datapart .= ' readonly style="border:0px;background-color:transparent;font-size: '.$fontsize.'px;"';
		}
		else{
			$datapart .= ' style="font-size: '.$fontsize.'px;"';
		}
		$datapart .= ' size="'.$size.'" type="text" name="'.$fieldname.'" id="'.$name.'_'.$k.'" value="'.htmlspecialchars($value).'">';
		$datapart .= '<div valign="top" style="height:0px; position:relative;">
				<div id="suggests_'.$name.'_'.$k.'" style="z-index: 3000;display:none; position:absolute; left:0px; top:0px; width: 150px; vertical-align:top; overflow:hidden; border:solid grey 1px;"></div>
			</div>
		</div>';
		return $datapart;
	}

?>