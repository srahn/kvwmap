<?
include(LAYOUTPATH.'languages/generic_search_'.$this->user->rolle->language.'.php');
include_once(SNIPPETS.'/generic_form_parts.php');
$num_colspan = ($this->user->rolle->visually_impaired) ? 2 : 3;
$date_types = array('date' => 'TT.MM.JJJJ', 'timestamp' => 'TT.MM.JJJJ hh:mm:ss', 'time' => 'hh:mm:ss');
?>

<table class="gsm_tabelle">
<?	if($searchmask_number > 0){						// es ist nicht die erste Suchmaske, sondern eine weitere hinzugefügte
				$prefix = $searchmask_number.'_'; 
?>
	<tr>
		<td colspan="<?php echo $num_colspan; ?>">
			<div class="gsm_undoder">
				<select class="gsm_undoder_select" name="boolean_operator_<? echo $searchmask_number; ?>">
					<option value="OR" <? if($this->formvars['searchmask_operator'][$searchmask_number] == 'OR')echo 'selected'; ?>><? echo $strOr; ?></option>
					<option value="AND" <? if($this->formvars['searchmask_operator'][$searchmask_number] == 'AND')echo 'selected'; ?>><? echo $strAnd; ?></option>
				</select>
				<span data-tooltip="<? echo $strAndOrHint2; ?>"></span>
			</div>
		</td>
	</tr>
<?	} else {
		$prefix = '';
?>
	<tr class="gsm_tabelle_ueberschrift">
		<td class="gsm_tabelle_td_first"><span><? echo $strAttribute; ?></span></td>
		<?php if (!$this->user->rolle->visually_impaired) { ?>
		<td class="gsm_tabelle_td_second">
			<div>
				<span><? echo $strOperator; ?></span>
			</div>
		</td>
		<?php } ?>
		<td class="gsm_tabelle_td_third"><span><? echo $strValue; ?></span></td>
	</tr>
<?	}
	if($this->{'attributes'.$searchmask_number} != NULL){
		$this->attributes = $this->{'attributes'.$searchmask_number};   # dieses Attributarray nehmen, weil eine gespeicherte Suche geladen wurde
	}
	$last_attribute_index = NULL;
	$z_index = 500;
	for($i = 0; $i < count($this->attributes['name']); $i++) {
		if ($this->attributes['mandatory'][$i] == '' or $this->attributes['mandatory'][$i] > -1) {
			$operator = value_of($this->formvars, $prefix . 'operator_' . $this->attributes['name'][$i]);
			if (
				$this->attributes['form_element_type'][$i] != 'dynamicLink' AND
				!($this->attributes['form_element_type'][$i] == 'SubFormFK' AND
				$this->attributes['saveable'][$i] == 0)
			) 
			{
				if ($this->attributes['group'][$i] != value_of($this->attributes['group'], $last_attribute_index)) { # wenn die vorige Gruppe anders ist: ...
					$explosion = explode(';', $this->attributes['group'][$i]);
					if($explosion[1] != '')$collapsed = true;else $collapsed = false;
					$groupname = $explosion[0];
					if($last_attribute_index !== NULL){		# ... Tabelle schliessen, wenn es nicht die erste Gruppe ist
						echo '</table></td></tr>';
					}
					$last_attribute_index = $i;					
					# ... Tabelle beginnen
					echo '
	<tr>
		<td colspan="' . $num_colspan . '" width="100%">
			<table class="gsm_tabelle_gruppe" id="colgroup'.$layer['Layer_ID'].'_'.$i.'_'.$searchmask_number.'"  style="'; if(!$collapsed)echo 'display:none;'; echo ' border:1px solid grey">
				<tr>
					<td colspan="2"><div class="gsm_tabelle_gruppe_zu"><span><a href="javascript:void(0);" onclick="javascript:document.getElementById(\'group'.$layer['Layer_ID'].'_'.$i.'_'.$searchmask_number.'\').style.display=\'\';document.getElementById(\'colgroup'.$layer['Layer_ID'].'_'.$i.'_'.$searchmask_number.'\').style.display=\'none\';"><img border="0" src="'.GRAPHICSPATH.'/plus.gif"></a></span><span>'.$groupname.'</span></div></td>
				</tr>
			</table>
			<table class="gsm_tabelle_gruppe" id="group'.$layer['Layer_ID'].'_'.$i.'_'.$searchmask_number.'" style="'; if($collapsed)echo 'display:none;'; echo 'border:1px solid grey">
				<tr>
					<td colspan="' . $num_colspan . '"><div class="gsm_tabelle_gruppe_auf"><span><a href="javascript:void(0);" onclick="javascript:document.getElementById(\'group'.$layer['Layer_ID'].'_'.$i.'_'.$searchmask_number.'\').style.display=\'none\';document.getElementById(\'colgroup'.$layer['Layer_ID'].'_'.$i.'_'.$searchmask_number.'\').style.display=\'\';"><img border="0" src="'.GRAPHICSPATH.'/minus.gif"></a></span><span>'.$groupname.'</span></div></td>
				</tr>';
				}
?>
				<tr class="gsm_tabelle_attribute">
					<td class="gsm_tabelle_td_first">
						<span>
<?
					if($this->attributes['alias'][$i] != ''){
						echo $this->attributes['alias'][$i];
					} else {
						echo $this->attributes['name'][$i];
					}
?>
						</span>
					</td>
<?					if (
						$operator == 'LIKE' OR					# ähnlich vorauswählen
						(
							in_array(
								$this->attributes['form_element_type'][$i],
								array('Text','Textfeld')
							) AND
							in_array(
								$this->attributes['type'][$i],
								array('varchar', 'text')
							) AND
							$operator == ''
						)
					) $operator = 'LIKE';
?>
<?					if (!$this->user->rolle->visually_impaired) { ?>
					<td class="gsm_tabelle_td_second">
						<select <? if(value_of($this->attributes, 'enum_value') AND @count($this->attributes['enum_value'][$i]) == 0) { ?>onchange="operatorchange(<? echo $this->formvars['selected_layer_id']; ?>, '<? echo $this->attributes['name'][$i]; ?>', <? echo $searchmask_number; ?>);" id="<? echo $prefix; ?>operator_<? echo $this->attributes['name'][$i]; ?>" <? } ?> name="<? echo $prefix; ?>operator_<? echo $this->attributes['name'][$i]; ?>">
							<option title="<? echo $strEqualHint; ?>" value="=" <? if($operator == '='){ echo 'selected';} ?> >=</option>
						<? if($this->attributes['type'][$i] != 'geometry'){ ?>
							<option title="<? echo $strNotEqualHint; ?>" value="!=" <? if($operator == '!='){ echo 'selected';} ?> >!=</option>
						<? }
						if(!in_array($this->attributes['type'][$i], array('bool'))){		# bei boolean und Array-Datentypen nur = und !=
							if($this->attributes['type'][$i] != 'geometry'){ ?>
								<? if(!in_array($this->attributes['type'][$i], array('text'))){ ?>
							<option title="<? echo $strLowerHint; ?>" value="<" <? if($operator == '<'){ echo 'selected';} ?> ><</option>
							<option title="<? echo $strGreaterHint; ?>" value=">" <? if($operator == '>'){ echo 'selected';} ?> >></option>
							<option title="<? echo $strLowerEqualHint; ?>" value="<=" <? if($operator == '<='){ echo 'selected';} ?> ><=</option>
							<option title="<? echo $strGreaterEqualHint; ?>" value=">=" <? if($operator == '>='){ echo 'selected';} ?> >>=</option>
								<? }
								if($this->attributes['form_element_type'][$i] == 'Autovervollständigungsfeld' OR !in_array($this->attributes['type'][$i], array('int2', 'int4', 'int8', 'numeric', 'float4', 'float8', 'date', 'timestampt', 'timestamptz'))){ ?>
							<option title="<? echo $strLikeHint; ?>" value="LIKE" <? if($operator == 'LIKE'){ echo 'selected';} ?> ><? echo $strLike; ?></option>
							<option title="<? echo $strLikeHint; ?>" value="NOT LIKE" <? if($operator == 'NOT LIKE'){ echo 'selected';} ?> ><? echo $strNotLike; ?></option>
								<? }
							} ?>
							<option title="<? echo $strIsEmptyHint; ?>" value="IS NULL" <? if($operator == 'IS NULL'){ echo 'selected';} ?> ><? echo $strIsEmpty; ?></option>
							<option title="<? echo $strIsNotEmptyHint; ?>" value="IS NOT NULL" <? if($operator == 'IS NOT NULL'){ echo 'selected';} ?> ><? echo $strIsNotEmpty; ?></option>
							<? if($this->attributes['type'][$i] != 'geometry'){ ?>
							<option title="<? echo $strInHint; ?>" value="IN" <? if (@count($this->attributes['enum_value'][$i]) > 0){ echo 'disabled="true"'; } ?> <? if($operator == 'IN'){ echo 'selected';} ?> ><? echo $strIsIn; ?></option>
								<? if(!in_array($this->attributes['type'][$i], array('text'))){ ?>
							<option title="<? echo $strBetweenHint; ?>" value="between" <? if (@count($this->attributes['enum_value'][$i]) > 0){ echo 'disabled="true"'; } ?> <? if($operator == 'between'){ echo 'selected';} ?> ><? echo $strBetween; ?></option>
								<? }
							}
						} ?>
						</select>
					</td>
<?					} else {
						if ($operator == '') $operator = '=';
							echo "<input type=\"hidden\" name=\"{$prefix}operator_{$this->attributes['name'][$i]}\" value=\"{$operator}\">";
						} ?>
					<td class="gsm_tabelle_td_third">
						<div>
<?          switch ($this->attributes['form_element_type'][$i]) {
							case 'Auswahlfeld' : case 'Radiobutton' : {	?>
								<select 
<?
								echo 'onchange="update_require_attribute(\''.$this->attributes['req_by'][$i].'\','.$this->formvars['selected_layer_id'].', new Array(\''.implode("','", $this->attributes['name']).'\'), '.$searchmask_number.');" ';
								$array = '';
								if($this->layerset[0]['connectiontype'] != MS_WFS AND substr($this->attributes['type'][$i], 0, 1) != '_'){		# bei WFS-Layern oder Array-Typen keine multible Auswahl
									$array = '[]';
									echo ' multiple="true" size="1" style="height: 25px;z-index:'.($z_index-=1).';position: absolute; top: 0px; width: 293px" onmousedown="if(this.style.height==\'25px\'){this.style.height=\'180px\';preventDefault(event);}" onmouseleave="if(event.relatedTarget){this.style.height=\'25px\';scrollToSelected(this);}"';
								}
?>
								id="<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i]; ?>" name="<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i].$array; ?>"><?echo "\n"; ?>
								<option value="">-- <? echo $this->strChoose; ?> --</option><? echo "\n"; ?>
<?
								if(is_array($this->attributes['enum_value'][$i][0])){
									$this->attributes['enum_value'][$i] = $this->attributes['enum_value'][$i][0];
									$this->attributes['enum_output'][$i] = $this->attributes['enum_output'][$i][0];
								}
								for($o = 0; $o < @count($this->attributes['enum_value'][$i]); $o++){	?>
									<option 
<? 
									if (!is_array($this->formvars[$prefix.'value_'.$this->attributes['name'][$i]])) {
										$this->formvars[$prefix.'value_'.$this->attributes['name'][$i]] = array($this->formvars[$prefix.'value_'.$this->attributes['name'][$i]]);
									}
									if (in_array($this->attributes['enum_value'][$i][$o], $this->formvars[$prefix.'value_'.$this->attributes['name'][$i]]) AND $this->attributes['enum_value'][$i][$o] != '') {
										echo 'selected';
									} ?> value="<? echo $this->attributes['enum_value'][$i][$o]; ?>" title="<? echo $this->attributes['enum_output'][$i][$o]; ?>"><? echo $this->attributes['enum_output'][$i][$o]; ?></option><? echo "\n";
								}
?>
								</select>
								<input id="<? echo $prefix; ?>value2_<? echo $this->attributes['name'][$i]; ?>" name="<? echo $prefix; ?>value2_<? echo $this->attributes['name'][$i]; ?>" type="hidden" value="<? echo $this->formvars[$prefix.'value2_'.$this->attributes['name'][$i]]; ?>">
<?
							}break;
								
							case 'Autovervollständigungsfeld' : {
								echo '<div id="'.$prefix.'_avf_'.$this->attributes['name'][$i].'" style="';
								if(in_array($operator, array('LIKE', 'NOT LIKE')))echo 'display:none';
								echo '">';
									echo Autovervollstaendigungsfeld($this->formvars['selected_layer_id'], $this->attributes['name'][$i], $i, $this->attributes['alias'][$i], $prefix.'value_'.$this->attributes['name'][$i], $this->formvars[$prefix.'value_'.$this->attributes['name'][$i]], $this->attributes['enum_output'][$i][0], 1, $prefix, NULL, NULL, NULL, NULL, false, 15, false, 40, NULL, NULL);
								echo '</div>';
								echo '<div id="'.$prefix.'_text_'.$this->attributes['name'][$i].'" style="';
								if(!in_array($operator, array('LIKE', 'NOT LIKE')))echo 'display:none';
								echo '">';
									echo '<input style="width:293px" id="'.$prefix.'text_value_'.$this->attributes['name'][$i].'" name="'.$prefix.'value_'.$this->attributes['name'][$i].'" type="text" value="'.$this->formvars[$prefix.'value_'.$this->attributes['name'][$i]].'"';
									if(!in_array($operator, array('LIKE', 'NOT LIKE')))echo ' disabled="true"';
									echo '>';
								echo '</div>';
							}break;
                
							case 'Checkbox' : {	?>
								<select  id="<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i]; ?>" name="<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i]; ?>"><?echo "\n"; ?>
									<option value="">-- <? echo $this->strChoose; ?> --</option><? echo "\n"; ?>
									<option <? if($this->formvars[$prefix.'value_'.$this->attributes['name'][$i]] == 't'){ echo 'selected';} ?> value="t">ja</option><? echo "\n"; ?>
									<option <? if($this->formvars[$prefix.'value_'.$this->attributes['name'][$i]] == 'f'){ echo 'selected';} ?> value="f">nein</option><? echo "\n"; ?>
								</select>
								<input style="width:145px" id="<? echo $prefix; ?>value2_<? echo $this->attributes['name'][$i]; ?>" name="<? echo $prefix; ?>value2_<? echo $this->attributes['name'][$i]; ?>" type="hidden" value="<? echo $this->formvars[$prefix.'value2_'.$this->attributes['name'][$i]]; ?>">
<?						}break;
                
							default : {
?>
								<input style="<? if(array_key_exists($this->attributes['type'][$i], $date_types)) { ?>padding-left: 18px; <? } ?> width:<? if(value_of($this->formvars, $prefix.'value2_'.$this->attributes['name'][$i]) != ''){echo '120';}else{echo '293';} ?>px" id="<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i]; ?>" name="<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i]; ?>" type="text" value="<? echo value_of($this->formvars, $prefix.'value_'.$this->attributes['name'][$i]); ?>" onkeyup="checknumbers(this, '<? echo $this->attributes['type'][$i]; ?>', '<? echo $this->attributes['length'][$i]; ?>', '<? echo $this->attributes['decimal_length'][$i]; ?>');">
								<input style="width:145px" id="<? echo $prefix; ?>value2_<? echo $this->attributes['name'][$i]; ?>" name="<? echo $prefix; ?>value2_<? echo $this->attributes['name'][$i]; ?>" type="<? if(value_of($this->formvars, $prefix.'value2_'.$this->attributes['name'][$i]) != ''){echo 'text';}else{echo 'hidden';} ?>" value="<? echo value_of($this->formvars, $prefix.'value2_'.$this->attributes['name'][$i]); ?>">
<?
								if(array_key_exists($this->attributes['type'][$i], $date_types)){	?>
									<div class="gsm_tabelle_kalender"><a href="javascript:;" onclick="add_calendar(event, '<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i]; ?>', '<? echo $this->attributes['type'][$i]; ?>');"><img title="<? echo $date_types[$this->attributes['type'][$i]]; ?>" src="<? echo GRAPHICSPATH; ?>calendarsheet.png" border="0"></a></div><div id="calendar_<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i]; ?>" class="calendar"></div>
<?							}
							}
	      		}
?>
						<div>
					</td>
				</tr>
<?					
	        }
		}
	}
	if($last_attribute_index !== NULL){		# ... Tabelle schliessen, wenn es Gruppen gibt
		echo '</table></td></tr>';
	}
?>
</table>