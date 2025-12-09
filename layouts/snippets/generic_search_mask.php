<?
include(LAYOUTPATH.'languages/generic_search_'.rolle::$language.'.php');
include_once(SNIPPETS.'/generic_form_parts.php');
$num_colspan = ($this->user->rolle->visually_impaired) ? 2 : 3;
$date_types = array('date' => 'TT.MM.JJJJ', 'timestamp' => 'TT.MM.JJJJ hh:mm:ss', 'time' => 'hh:mm:ss');
?>

<script>
	function isChrome142OrNewer() {
		const ua = navigator.userAgent;
		const match = ua.match(/Chrome\/(\d+)/);
		if (!match) return false;
		const version = parseInt(match[1], 10);
		return version >= 142;
	}
	chrome142 = isChrome142OrNewer();
</script>

<div class="gsm_tabelle gsm_tabelle_defaults"> <!-- komplette Tabelle -->
<?	
	if($searchmask_number > 0){						// es ist nicht die erste Suchmaske, sondern eine weitere hinzugefügte
		$prefix = $searchmask_number.'_'; 
?>
		 
																						
	<div class="gsm_undoder">
		<select class="gsm_undoder_select" name="boolean_operator_<? echo $searchmask_number; ?>">
			<option value="OR" <? if($this->formvars['searchmask_operator'][$searchmask_number] == 'OR')echo 'selected'; ?>><? echo $strOr; ?></option>
			<option value="AND" <? if($this->formvars['searchmask_operator'][$searchmask_number] == 'AND')echo 'selected'; ?>><? echo $strAnd; ?></option>
		</select>
		<span data-tooltip="<? echo $strAndOrHint; ?>"></span>	
	</div>
			 
			
<?
	} else {
		$prefix = '';
?>
	<div class="gsm_tabelle_ueberschrift">
		<div class="gsm_tabelle_td_first">
			<? echo $strAttribute; ?>
		</div>
		<? if (!$this->user->rolle->visually_impaired) { ?>
		<div class="gsm_tabelle_td_second">
			<div><? echo $strOperator; ?></div>
			<div>
				<span data-tooltip="<? echo $strLikeSearchHint."\n\n".$strOperatorHint; ?>"></span>
			</div>
		</div>
		<? } ?>
		<div class="gsm_tabelle_td_third">
			<div><? echo $strValue; ?></div>
			<div>
				<a href="javascript:clear();" title="Suchparameter zurücksetzen"><img style="vertical-align:top;" src="<? echo GRAPHICSPATH.'edit-clear.png'; ?>"></a>
			</div>
		</div>
	</div>
<?
	}
	if($this->{'attributes'.$searchmask_number} != NULL){
		$this->attributes = $this->{'attributes'.$searchmask_number};   # dieses Attributarray nehmen, weil eine gespeicherte Suche geladen wurde
	}
	$last_attribute_index = NULL;
	$z_index = 500;
	for($i = 0; $i < count($this->attributes['name']); $i++) {
		if ($this->attributes['mandatory'][$i] == '' or $this->attributes['mandatory'][$i] > -1) {
			$operator = value_of($this->formvars, $prefix . 'operator_' . $this->attributes['name'][$i]);
			if	(
				$this->attributes['form_element_type'][$i] != 'dynamicLink' AND
				!($this->attributes['form_element_type'][$i] == 'SubFormFK' AND
				$this->attributes['saveable'][$i] == 0)
				) 
			{
				if ($this->attributes['group'][$i] != value_of($this->attributes['group'], $last_attribute_index)) { # wenn die vorige Gruppe anders ist, dann ...
					$explosion = explode(';', $this->attributes['group'][$i]);
					if($explosion[1] != '')$collapsed = true;else $collapsed = false;
					$groupname = $explosion[0];
					if($last_attribute_index !== NULL){		# ... Tabelle schliessen, wenn es nicht die erste Gruppe ist
						echo '</div> <!-- Ende Gruppe -->';
					}
					$last_attribute_index = $i;					
					# ... und/oder Tabelle beginnen				
		
?>
	<div class="gsm_tabelle_gruppe gsm_tabelle_gruppe_zu" id="colgroup<? echo $layer['Layer_ID'].'_'.$i.'_'.$searchmask_number; ?>" style="<? if(!$collapsed)echo 'display: none;'; ?>">
		<div class="gsm_tabelle_gruppe_name" onclick="javascript:document.getElementById('<? echo 'group'.$layer['Layer_ID'].'_'.$i.'_'.$searchmask_number; ?>').style.display='block'; document.getElementById('<? echo 'colgroup'.$layer['Layer_ID'].'_'.$i.'_'.$searchmask_number; ?>').style.display='none'">
			<div>+</div>
			<div><? echo $groupname; ?></div>
		</div>
	</div>
	<div class="gsm_tabelle_gruppe gsm_tabelle_gruppe_auf" id="group<? echo $layer['Layer_ID'].'_'.$i.'_'.$searchmask_number; ?>" style="<? if($collapsed)echo 'display: none;'; ?>">
		<div class="gsm_tabelle_gruppe_name" onclick="javascript:document.getElementById('<? echo 'group'.$layer['Layer_ID'].'_'.$i.'_'.$searchmask_number; ?>').style.display='none'; document.getElementById('<? echo 'colgroup'.$layer['Layer_ID'].'_'.$i.'_'.$searchmask_number; ?>').style.display='block';">
			<div>–</div>
			<div><? echo $groupname; ?></div>
		</div>
<?
				}
				if (
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
				<div class="gsm_tabelle_attribute">
					<div class="gsm_tabelle_td_first">
<?
						if($this->attributes['alias'][$i] != ''){
							echo $this->attributes['alias'][$i];
						} else {
							echo $this->attributes['name'][$i];
						}
?>
					</div>
<?
					if (!$this->user->rolle->visually_impaired) {
						if (is_array($this->attributes['dependent_options'][$i])) {
							$this->attributes['enum'][$i] = $this->attributes['enum'][$i][0];
						}
						$output_not_numeric = false;
						if (is_array($this->attributes['enum'][$i])) {
							foreach ($this->attributes['enum'][$i] as $enum){
								if (!is_numeric($enum['output'])) {
									$output_not_numeric = true;
								}
							}
						}
?>
					<div class="gsm_tabelle_td_second">
						<select onchange="operatorchange(<? echo $this->formvars['selected_layer_id']; ?>, '<? echo $this->attributes['name'][$i]; ?>', <? echo $searchmask_number; ?>);" id="<? echo $prefix; ?>operator_<? echo $this->attributes['name'][$i]; ?>" name="<? echo $prefix; ?>operator_<? echo $this->attributes['name'][$i]; ?>">
							<option title="<? echo $strEqualHint; ?>" value="=" <? if($operator == '='){ echo 'selected';} ?> >=</option>
						<? if($this->attributes['type'][$i] != 'geometry'){ ?>
							<option title="<? echo $strNotEqualHint; ?>" value="!=" <? if($operator == '!='){ echo 'selected';} ?> >!=</option>
						<? }
						if(!in_array($this->attributes['type'][$i], array('bool'))){		# bei boolean und Array-Datentypen nur = und !=
							if($this->attributes['type'][$i] != 'geometry'){ ?>
								<? if(!in_array($this->attributes['type'][$i], array('text'))){ ?>
							<option title="<? echo $strLowerHint; ?>" value="<" <? if ($output_not_numeric == true){ echo 'disabled="true"'; } ?> <? if($operator == '<'){ echo 'selected';} ?> ><</option>
							<option title="<? echo $strGreaterHint; ?>" value=">" <? if ($output_not_numeric == true){ echo 'disabled="true"'; } ?> <? if($operator == '>'){ echo 'selected';} ?> >></option>
							<option title="<? echo $strLowerEqualHint; ?>" value="<=" <? if ($output_not_numeric == true){ echo 'disabled="true"'; } ?> <? if($operator == '<='){ echo 'selected';} ?> ><=</option>
							<option title="<? echo $strGreaterEqualHint; ?>" value=">=" <? if ($output_not_numeric == true){ echo 'disabled="true"'; } ?> <? if($operator == '>='){ echo 'selected';} ?> >>=</option>
								<? }
								if($this->attributes['form_element_type'][$i] == 'Autovervollständigungsfeld' OR !in_array($this->attributes['type'][$i], array('int2', 'int4', 'int8', 'numeric', 'float4', 'float8', 'date', 'timestampt', 'timestamptz'))){ ?>
							<option title="<? echo $strLikeHint; ?>" value="LIKE" <? if($operator == 'LIKE'){ echo 'selected';} ?> ><? echo $strLike; ?></option>
							<option title="<? echo $strLikeHint; ?>" value="NOT LIKE" <? if($operator == 'NOT LIKE'){ echo 'selected';} ?> ><? echo $strNotLike; ?></option>
								<? }
							} ?>
							<option title="<? echo $strIsEmptyHint; ?>" value="IS NULL" <? if($operator == 'IS NULL'){ echo 'selected';} ?> ><? echo $strIsEmpty; ?></option>
							<option title="<? echo $strIsNotEmptyHint; ?>" value="IS NOT NULL" <? if($operator == 'IS NOT NULL'){ echo 'selected';} ?> ><? echo $strIsNotEmpty; ?></option>
							<? if($this->attributes['type'][$i] != 'geometry'){ ?>
							<option title="<? echo $strInHint; ?>" value="IN" <? if (count_or_0($this->attributes['enum'][$i]) > 0){ echo 'disabled="true"'; } ?> <? if($operator == 'IN'){ echo 'selected';} ?> ><? echo $strIsIn; ?></option>
								<? if(!in_array($this->attributes['type'][$i], array('text'))){ ?>
							<option title="<? echo $strBetweenHint; ?>" value="between" <? if (count_or_0($this->attributes['enum'][$i]) > 0){ echo 'disabled="true"'; } ?> <? if($operator == 'between'){ echo 'selected';} ?> ><? echo $strBetween; ?></option>
								<? }
							}
						} ?>
						</select>					
					</div>
<?
					} else {
						if ($operator == '') $operator = '=';
							echo "<input type=\"hidden\" name=\"{$prefix}operator_{$this->attributes['name'][$i]}\" value=\"{$operator}\">";
						}			 
?>
					<div class="gsm_tabelle_td_third" id="<? echo $prefix . '_third_' . $this->attributes['name'][$i]; ?>">
<?          		
					switch ($this->attributes['form_element_type'][$i]) {
						case 'Auswahlfeld' : case 'Radiobutton' : {	?>
							<div class="GP">
							<select id="<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i]; ?>"
<? 
							echo ' onchange="update_require_attribute(\''.$this->attributes['req_by'][$i].'\','.$this->formvars['selected_layer_id'].', new Array(\''.implode("','", $this->attributes['name']).'\'), '.$searchmask_number.');" ';
							$array = '';
							if($this->layerset[0]['connectiontype'] != MS_WFS AND substr($this->attributes['type'][$i], 0, 1) != '_'){		# bei WFS-Layern oder Array-Typen keine multible Auswahl
								$array = '[]';
								echo ' multiple size="1" style="display: block; min-height: 24px; height: calc((var(--tabelle-td-height) - var(--tabelle-td-height-padding)) * 1px); z-index:'.($z_index-=1).';" onmousedown="this.focus();if(!chrome142 && this.style.height==\'calc((var(--tabelle-td-height) - var(--tabelle-td-height-padding)) * 1px)\'){this.style.height=\'180px\';preventDefault(event);}" onmouseleave="if(!chrome142 && event.relatedTarget){this.style.height=\'calc((var(--tabelle-td-height) - var(--tabelle-td-height-padding)) * 1px)\';scrollToSelected(this);}"';
							}
?>
							 name="<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i].$array; ?>"><?echo "\n"; ?>
							<option value="">-- <? echo $this->strChoose; ?> --</option><? echo "\n"; ?>
<? 
							if (is_array($this->attributes['enum'][$i])){
								foreach ($this->attributes['enum'][$i] as $enum_key => $enum) {	?>
									<option  <? 
									if ($this->formvars[$prefix.'value_'.$this->attributes['name'][$i]] != '' AND !is_array($this->formvars[$prefix.'value_'.$this->attributes['name'][$i]])) {
										$this->formvars[$prefix.'value_'.$this->attributes['name'][$i]] = array($this->formvars[$prefix.'value_'.$this->attributes['name'][$i]]);
									}
									if (in_array($enum_key, $this->formvars[$prefix.'value_'.$this->attributes['name'][$i]] ?: []) AND $enum_key != '') {
										echo 'selected';
									} ?> value="<? echo $enum_key; ?>" title="<? echo $enum['output']; ?>"><? echo $enum['output']; ?></option><? echo "\n";
								}
							}
?>
							</select>
							</div>
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
								echo '<input style="min-height: 24px; height: calc((var(--tabelle-td-height) - var(--tabelle-td-height-padding)) * 1px)" id="'.$prefix.'text_value_'.$this->attributes['name'][$i].'" name="'.$prefix.'value_'.$this->attributes['name'][$i].'" type="text" value="'.$this->formvars[$prefix.'value_'.$this->attributes['name'][$i]].'"';
								if(!in_array($operator, array('LIKE', 'NOT LIKE')))echo ' disabled="true"';
								echo '>';
							echo '</div>';
						}break;
                
						case 'Checkbox' : {	?>
							<select  id="<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i]; ?>" name="<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i]; ?>"><?echo "\n"; ?>
								<option value="">-- <? echo $this->strChoose; ?> --</option><? echo "\n"; ?>
								<option <? if($this->formvars[$prefix.'value_'.$this->attributes['name'][$i]] == 't'){ echo 'selected';} ?> value="t"><? echo $strChooseYes; ?></option><? echo "\n"; ?>
								<option <? if($this->formvars[$prefix.'value_'.$this->attributes['name'][$i]] == 'f'){ echo 'selected';} ?> value="f"><? echo $strChooseNo; ?></option><? echo "\n"; ?>
							</select>
							<input id="<? echo $prefix; ?>value2_<? echo $this->attributes['name'][$i]; ?>" name="<? echo $prefix; ?>value2_<? echo $this->attributes['name'][$i]; ?>" type="hidden" value="<? echo $this->formvars[$prefix.'value2_'.$this->attributes['name'][$i]]; ?>">
<? 					}break;
                
						default : {
?>
							<div class="gsm_default_input">
								<div id="<? echo $prefix; ?>gsm_default_input1_<? echo $this->attributes['name'][$i]; ?>">
									<input id="<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i]; ?>" name="<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i]; ?>" style="<? if(array_key_exists($this->attributes['type'][$i], $date_types)) { ?>padding-left: 20px; <? } ?>" type="text" value="<? echo value_of($this->formvars, $prefix.'value_'.$this->attributes['name'][$i]); ?>" onkeyup="checknumbers(this, '<? echo $this->attributes['type'][$i]; ?>', '<? echo $this->attributes['length'][$i]; ?>', '<? echo $this->attributes['decimal_length'][$i]; ?>');">
<? 
									if(array_key_exists($this->attributes['type'][$i], $date_types)){	?>
										<div class="gsm_tabelle_kalender" onclick="add_calendar(event, '<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i]; ?>', '<? echo $this->attributes['type'][$i]; ?>');"><img title="<? echo $date_types[$this->attributes['type'][$i]]; ?>" src="<? echo GRAPHICSPATH; ?>calendarsheet.png" border="0"></div><div id="calendar_<? echo $prefix; ?>value_<? echo $this->attributes['name'][$i]; ?>" class="calendar"></div>
<? 								}
				
					
?>
								</div>
								<div id="<? echo $prefix; ?>gsm_default_input2_<? echo $this->attributes['name'][$i]; ?>" <? if(value_of($this->formvars, $prefix.'value2_'.$this->attributes['name'][$i]) != null) { ?>style="display: block;"<? } ?>>
									<input id="<? echo $prefix; ?>value2_<? echo $this->attributes['name'][$i]; ?>" name="<? echo $prefix; ?>value2_<? echo $this->attributes['name'][$i]; ?>" style="<? if(array_key_exists($this->attributes['type'][$i], $date_types)) { ?>padding-left: 20px; <? } ?>" type="text" value="<? echo value_of($this->formvars, $prefix.'value2_'.$this->attributes['name'][$i]); ?>">
<? 
									if(array_key_exists($this->attributes['type'][$i], $date_types)){	?>
										<div class="gsm_tabelle_kalender" onclick="add_calendar(event, '<? echo $prefix; ?>value2_<? echo $this->attributes['name'][$i]; ?>', '<? echo $this->attributes['type'][$i]; ?>');"><img title="<? echo $date_types[$this->attributes['type'][$i]]; ?>" src="<? echo GRAPHICSPATH; ?>calendarsheet.png" border="0"></div><div id="calendar_<? echo $prefix; ?>value2_<? echo $this->attributes['name'][$i]; ?>" class="calendar"></div>
<? 								}
?>
								</div>
							</div>
<?
						}
					}
?>
					</div>					
	</div> <!-- entweder Ende gsm_tabelle_gruppe oder Ende gsm_tabelle_attribute, wenn es keine Gruppen gibt -->
<?
			}
		}
	}
	if($last_attribute_index !== NULL){		# ... Tabelle schliessen, wenn es Gruppen gibt
		echo '</div> <!-- Ende letzte Gruppe -->';
	}
?>

</div> <!-- Ende komplette Tabelle -->
