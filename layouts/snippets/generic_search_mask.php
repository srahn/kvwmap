<?
include(LAYOUTPATH.'languages/generic_search_'.$this->user->rolle->language.'.php');
include_once(SNIPPETS.'/generic_form_parts.php');
$num_colspan = ($this->user->rolle->visually_impaired) ? 2 : 3;
$date_types = array('date' => 'TT.MM.JJJJ', 'timestamp' => 'TT.MM.JJJJ hh:mm:ss', 'time' => 'hh:mm:ss');
?>


<div class="gsm_tabelle gsm_tabelle_defaults"> <!-- komplette Tabelle -->
<?php	
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
			 
			
<?php
	} else {
		$prefix = '';
?>
	<div class="gsm_tabelle_ueberschrift">
		<div class="gsm_tabelle_td_first">
			<? echo $strAttribute; ?>
		</div>
		<?php if (!$this->user->rolle->visually_impaired) { ?>
		<div class="gsm_tabelle_td_second">
			<div><? echo $strOperator; ?></div>
			<div>
				<span data-tooltip="<? echo $strLikeSearchHint."\n\n".$strOperatorHint; ?>"></span>
			</div>
		</div>
		<?php } ?>
		<div class="gsm_tabelle_td_third">
			<div><? echo $strValue; ?></div>
			<div>
				<a href="javascript:clear();" title="Suchfelder leeren"><img style="vertical-align:top;" src="<? echo GRAPHICSPATH.'edit-clear.png'; ?>"></a>
			</div>
		</div>
	</div>
<?php
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
	<div class="gsm_tabelle_gruppe gsm_tabelle_gruppe_zu" id="colgroup<?php echo $layer['Layer_ID'].'_'.$i.'_'.$searchmask_number; ?>" style="<?php if(!$collapsed)echo 'display: none;'; ?>">
		<div class="gsm_tabelle_gruppe_name" onclick="javascript:document.getElementById('<?php echo 'group'.$layer['Layer_ID'].'_'.$i.'_'.$searchmask_number; ?>').style.display='block'; document.getElementById('<?php echo 'colgroup'.$layer['Layer_ID'].'_'.$i.'_'.$searchmask_number; ?>').style.display='none'">
			<div>+</div>
			<div><?php echo $groupname; ?></div>
		</div>
	</div>
	<div class="gsm_tabelle_gruppe gsm_tabelle_gruppe_auf" id="group<?php echo $layer['Layer_ID'].'_'.$i.'_'.$searchmask_number; ?>" style="<?php if($collapsed)echo 'display: none;'; ?>">
		<div class="gsm_tabelle_gruppe_name" onclick="javascript:document.getElementById('<?php echo 'group'.$layer['Layer_ID'].'_'.$i.'_'.$searchmask_number; ?>').style.display='none'; document.getElementById('<?php echo 'colgroup'.$layer['Layer_ID'].'_'.$i.'_'.$searchmask_number; ?>').style.display='block';">
			<div>–</div>
			<div><?php echo $groupname; ?></div>
		</div>
<?php
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
<?php
						if($this->attributes['alias'][$i] != ''){
							echo $this->attributes['alias'][$i];
						} else {
							echo $this->attributes['name'][$i];
						}
?>
					</div>
<?php
					if (!$this->user->rolle->visually_impaired) {
						if(is_array($this->attributes['enum_value'][$i][0])){
							$this->attributes['enum_value'][$i] = $this->attributes['enum_value'][$i][0];
							$this->attributes['enum_output'][$i] = $this->attributes['enum_output'][$i][0];
						}
						$output_not_numeric = false;
						for($o = 0; $o < @count($this->attributes['enum_value'][$i]); $o++){
							if(!is_numeric($this->attributes['enum_output'][$i][$o])) {
								$output_not_numeric = true;
							}
						}
?>
					<div class="gsm_tabelle_td_second">
						<select onchange="operatorchange(<?php echo $this->formvars['selected_layer_id']; ?>, '<?php echo $this->attributes['name'][$i]; ?>', <?php echo $searchmask_number; ?>);" id="<?php echo $prefix; ?>operator_<?php echo $this->attributes['name'][$i]; ?>" name="<?php echo $prefix; ?>operator_<?php echo $this->attributes['name'][$i]; ?>">
							<option title="<?php echo $strEqualHint; ?>" value="=" <?php if($operator == '='){ echo 'selected';} ?> >=</option>
						<?php if($this->attributes['type'][$i] != 'geometry'){ ?>
							<option title="<?php echo $strNotEqualHint; ?>" value="!=" <?php if($operator == '!='){ echo 'selected';} ?> >!=</option>
						<?php }
						if(!in_array($this->attributes['type'][$i], array('bool'))){		# bei boolean und Array-Datentypen nur = und !=
							if($this->attributes['type'][$i] != 'geometry'){ ?>
								<?php if(!in_array($this->attributes['type'][$i], array('text'))){ ?>
							<option title="<?php echo $strLowerHint; ?>" value="<" <?php if ($output_not_numeric == true){ echo 'disabled="true"'; } ?> <?php if($operator == '<'){ echo 'selected';} ?> ><</option>
							<option title="<?php echo $strGreaterHint; ?>" value=">" <?php if ($output_not_numeric == true){ echo 'disabled="true"'; } ?> <?php if($operator == '>'){ echo 'selected';} ?> >></option>
							<option title="<?php echo $strLowerEqualHint; ?>" value="<=" <?php if ($output_not_numeric == true){ echo 'disabled="true"'; } ?> <?php if($operator == '<='){ echo 'selected';} ?> ><=</option>
							<option title="<?php echo $strGreaterEqualHint; ?>" value=">=" <?php if ($output_not_numeric == true){ echo 'disabled="true"'; } ?> <?php if($operator == '>='){ echo 'selected';} ?> >>=</option>
								<?php }
								if($this->attributes['form_element_type'][$i] == 'Autovervollständigungsfeld' OR !in_array($this->attributes['type'][$i], array('int2', 'int4', 'int8', 'numeric', 'float4', 'float8', 'date', 'timestampt', 'timestamptz'))){ ?>
							<option title="<?php echo $strLikeHint; ?>" value="LIKE" <?php if($operator == 'LIKE'){ echo 'selected';} ?> ><?php echo $strLike; ?></option>
							<option title="<?php echo $strLikeHint; ?>" value="NOT LIKE" <?php if($operator == 'NOT LIKE'){ echo 'selected';} ?> ><?php echo $strNotLike; ?></option>
								<?php }
							} ?>
							<option title="<?php echo $strIsEmptyHint; ?>" value="IS NULL" <?php if($operator == 'IS NULL'){ echo 'selected';} ?> ><?php echo $strIsEmpty; ?></option>
							<option title="<?php echo $strIsNotEmptyHint; ?>" value="IS NOT NULL" <?php if($operator == 'IS NOT NULL'){ echo 'selected';} ?> ><?php echo $strIsNotEmpty; ?></option>
							<?php if($this->attributes['type'][$i] != 'geometry'){ ?>
							<option title="<?php echo $strInHint; ?>" value="IN" <?php if (@count($this->attributes['enum_value'][$i]) > 0){ echo 'disabled="true"'; } ?> <?php if($operator == 'IN'){ echo 'selected';} ?> ><?php echo $strIsIn; ?></option>
								<?php if(!in_array($this->attributes['type'][$i], array('text'))){ ?>
							<option title="<?php echo $strBetweenHint; ?>" value="between" <?php if (@count($this->attributes['enum_value'][$i]) > 0){ echo 'disabled="true"'; } ?> <?php if($operator == 'between'){ echo 'selected';} ?> ><?php echo $strBetween; ?></option>
								<?php }
							}
						} ?>
						</select>					
					</div>
<?php
					} else {
						if ($operator == '') $operator = '=';
							echo "<input type=\"hidden\" name=\"{$prefix}operator_{$this->attributes['name'][$i]}\" value=\"{$operator}\">";
						}
																			
					 
																																 
																										 
								
	
																																																																																																														 
										
																																																																																						
											 
																																																																																																																																																																 
				 
?>
					<div class="gsm_tabelle_td_third">
<?php          		
					$tabelle_td_height_el = ($tabelle_td_height - $tabelle_td_height_padding);
					switch ($this->attributes['form_element_type'][$i]) {
						case 'Auswahlfeld' : case 'Radiobutton' : {	?>
							<div class="GP">
							<select id="<?php echo $prefix; ?>value_<?php echo $this->attributes['name'][$i]; ?>"
<?php 
							echo ' onchange="update_require_attribute(\''.$this->attributes['req_by'][$i].'\','.$this->formvars['selected_layer_id'].', new Array(\''.implode("','", $this->attributes['name']).'\'), '.$searchmask_number.');" ';
							$array = '';
							if($this->layerset[0]['connectiontype'] != MS_WFS AND substr($this->attributes['type'][$i], 0, 1) != '_'){		# bei WFS-Layern oder Array-Typen keine multible Auswahl
								$array = '[]';
								echo ' multiple size="1" style="display: block; min-height: 24px; height: calc((var(--tabelle-td-height) - var(--tabelle-td-height-padding)) * 1px); z-index:'.($z_index-=1).';" onmousedown="if(this.style.height==\'calc((var(--tabelle-td-height) - var(--tabelle-td-height-padding)) * 1px)\'){this.style.height=\'180px\';preventDefault(event);}" onmouseleave="if(event.relatedTarget){this.style.height=\'calc((var(--tabelle-td-height) - var(--tabelle-td-height-padding)) * 1px)\';scrollToSelected(this);}"';
							}
?>
							 name="<?php echo $prefix; ?>value_<?php echo $this->attributes['name'][$i].$array; ?>"><?echo "\n"; ?>
							<option value="">-- <?php echo $this->strChoose; ?> --</option><?php echo "\n"; ?>
<?php 
							if(is_array($this->attributes['enum_value'][$i][0])){
								$this->attributes['enum_value'][$i] = $this->attributes['enum_value'][$i][0];
								$this->attributes['enum_output'][$i] = $this->attributes['enum_output'][$i][0];
							}
							for($o = 0; $o < @count($this->attributes['enum_value'][$i]); $o++){	?>
								<option  
<?php 
								if (!is_array($this->formvars[$prefix.'value_'.$this->attributes['name'][$i]])) {
									$this->formvars[$prefix.'value_'.$this->attributes['name'][$i]] = array($this->formvars[$prefix.'value_'.$this->attributes['name'][$i]]);																																																																																															 
								}
								if (in_array($this->attributes['enum_value'][$i][$o], $this->formvars[$prefix.'value_'.$this->attributes['name'][$i]]) AND $this->attributes['enum_value'][$i][$o] != '') {
									echo 'selected';
								} ?> value="<?php echo $this->attributes['enum_value'][$i][$o]; ?>" title="<?php echo $this->attributes['enum_output'][$i][$o]; ?>"><?php echo $this->attributes['enum_output'][$i][$o]; ?></option><?php echo "\n";
							}
?>
							</select>
							</div>
							<input id="<?php echo $prefix; ?>value2_<?php echo $this->attributes['name'][$i]; ?>" name="<?php echo $prefix; ?>value2_<?php echo $this->attributes['name'][$i]; ?>" type="hidden" value="<?php echo $this->formvars[$prefix.'value2_'.$this->attributes['name'][$i]]; ?>">
<?php 
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
							<select  id="<?php echo $prefix; ?>value_<?php echo $this->attributes['name'][$i]; ?>" name="<?php echo $prefix; ?>value_<?php echo $this->attributes['name'][$i]; ?>"><?echo "\n"; ?>
								<option value="">-- <?php echo $this->strChoose; ?> --</option><?php echo "\n"; ?>
								<option <?php if($this->formvars[$prefix.'value_'.$this->attributes['name'][$i]] == 't'){ echo 'selected';} ?> value="t"><?php echo $strChooseYes; ?></option><?php echo "\n"; ?>
								<option <?php if($this->formvars[$prefix.'value_'.$this->attributes['name'][$i]] == 'f'){ echo 'selected';} ?> value="f"><?php echo $strChooseNo; ?></option><?php echo "\n"; ?>
							</select>
							<input id="<?php echo $prefix; ?>value2_<?php echo $this->attributes['name'][$i]; ?>" name="<?php echo $prefix; ?>value2_<?php echo $this->attributes['name'][$i]; ?>" type="hidden" value="<?php echo $this->formvars[$prefix.'value2_'.$this->attributes['name'][$i]]; ?>">
<?php 					}break;
                
						default : {
?>
							<div class="gsm_default_input">
								<div id="gsm_default_input1_<?php echo $this->attributes['name'][$i]; ?>">
									<input id="<?php echo $prefix; ?>value_<?php echo $this->attributes['name'][$i]; ?>" name="<?php echo $prefix; ?>value_<?php echo $this->attributes['name'][$i]; ?>" style="<?php if(array_key_exists($this->attributes['type'][$i], $date_types)) { ?>padding-left: 20px; <?php } ?>" type="text" value="<?php echo value_of($this->formvars, $prefix.'value_'.$this->attributes['name'][$i]); ?>" onkeyup="checknumbers(this, '<?php echo $this->attributes['type'][$i]; ?>', '<?php echo $this->attributes['length'][$i]; ?>', '<?php echo $this->attributes['decimal_length'][$i]; ?>');">
<?php 
									if(array_key_exists($this->attributes['type'][$i], $date_types)){	?>
										<div class="gsm_tabelle_kalender" onclick="add_calendar(event, '<?php echo $prefix; ?>value_<?php echo $this->attributes['name'][$i]; ?>', '<?php echo $this->attributes['type'][$i]; ?>');"><img title="<?php echo $date_types[$this->attributes['type'][$i]]; ?>" src="<?php echo GRAPHICSPATH; ?>calendarsheet.png" border="0"></div><div id="calendar_<?php echo $prefix; ?>value_<?php echo $this->attributes['name'][$i]; ?>" class="calendar"></div>
<?php 								}
				
					
?>
								</div>
								<div id="gsm_default_input2_<?php echo $this->attributes['name'][$i]; ?>" <?php if(value_of($this->formvars, $prefix.'value2_'.$this->attributes['name'][$i]) != null) { ?>style="display: block;"<?php } ?>>
									<input id="<?php echo $prefix; ?>value2_<?php echo $this->attributes['name'][$i]; ?>" name="<?php echo $prefix; ?>value2_<?php echo $this->attributes['name'][$i]; ?>" style="<?php if(array_key_exists($this->attributes['type'][$i], $date_types)) { ?>padding-left: 20px; <?php } ?>" type="text" value="<?php echo value_of($this->formvars, $prefix.'value2_'.$this->attributes['name'][$i]); ?>">
<?php 
									if(array_key_exists($this->attributes['type'][$i], $date_types)){	?>
										<div class="gsm_tabelle_kalender" onclick="add_calendar(event, '<?php echo $prefix; ?>value2_<?php echo $this->attributes['name'][$i]; ?>', '<?php echo $this->attributes['type'][$i]; ?>');"><img title="<?php echo $date_types[$this->attributes['type'][$i]]; ?>" src="<?php echo GRAPHICSPATH; ?>calendarsheet.png" border="0"></div><div id="calendar_<?php echo $prefix; ?>value2_<?php echo $this->attributes['name'][$i]; ?>" class="calendar"></div>
<?php 								}
?>
								</div>
							</div>
<?php
						}
					}
?>
					</div>					
	</div> <!-- entweder Ende gsm_tabelle_gruppe oder Ende gsm_tabelle_attribute, wenn es keine Gruppen gibt -->
<?php
			}
		}
	}
	if($last_attribute_index !== NULL){		# ... Tabelle schliessen, wenn es Gruppen gibt
		echo '</div> <!-- Ende letzte Gruppe -->';
	}
?>

</div> <!-- Ende komplette Tabelle -->
