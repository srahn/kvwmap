<?

function attribute_name($attributes, $j, $fontsize){
	$datapart .= '<table ';
	if($attributes['group'][0] != '')$datapart .= 'width="200px"';
	else $datapart .= 'width="100%";';
	$datapart .= '><tr style="border: none"><td>';
	if(!in_array($attributes['form_element_type'][$j], array('SubFormPK', 'SubFormEmbeddedPK', 'SubFormFK', 'dynamicLink'))){
		$datapart .= '<a style="font-size: '.$fontsize.'px" title="Sortieren nach '.$attributes['alias'][$j].'" href="javascript:change_orderby(\''.$attributes['name'][$j].'\', '.$layer['Layer_ID'].');">
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

?>