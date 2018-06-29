<?

	include(LAYOUTPATH.'languages/generic_layer_editor_2_'.$this->user->rolle->language.'.php');
  
	$checkbox_names = '';
	$columnname = '';
	$geom_tablename = '';
	$geomtype = '';
	$dimension = '';
	$privileg = '';
	# Variablensubstitution
	$layer = $this->qlayerset[$i];
	$attributes = $layer['attributes'];
	$size = 12;
	$select_width = 'width: 100px;'; 
	if($layer['alias'] != '' AND $this->Stelle->useLayerAliases){
		$layer['Name'] = $layer['alias'];
	}
	$doit = false;
  $anzObj = count($layer['shape']);
  if ($anzObj > 0) {
  	$this->found = 'true';
  	$doit = true;
  }
  if($this->new_entry == true){
  	$anzObj = 1;
  	$doit = true;
  }
?>
<SCRIPT src="funktionen/tooltip.js" language="JavaScript"  type="text/javascript"></SCRIPT>

<div id="layer" onclick="remove_calendar();">
<input type="hidden" value="" id="changed_<? echo $layer['Layer_ID']; ?>" name="changed_<? echo $layer['Layer_ID']; ?>">
<? if($this->new_entry != true AND $layer['requires'] == ''){ ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<? if (!$this->user->rolle->visually_impaired AND $anzObj > 0) { ?>
		<td align="right" valign="top" style="padding: 0 10 0 0">
			<a href="javascript:scrollbottom();"><img class="hover-border" title="nach unten" src="<? echo GRAPHICSPATH; ?>pfeil.gif" width="11" height="11" border="0"></a>&nbsp;
		</td>
		<td valign="top" style="padding: 0 0 0 0">
			<? if($layer['template'] == '' OR $layer['template'] == 'generic_layer_editor_2.php'){ ?>
			<img onclick="checkForUnsavedChanges(event);switch_gle_view1(<? echo $layer['Layer_ID']; ?>);"" title="<? echo $strSwitchGLEViewColumns; ?>" class="hover-border pointer" src="<? echo GRAPHICSPATH.'columns.png'; ?>">
			<? }else{ ?>
			<img onclick="checkForUnsavedChanges(event);switch_gle_view1(<? echo $layer['Layer_ID']; ?>);"" title="<? echo $strSwitchGLEViewRows; ?>" class="hover-border pointer" src="<? echo GRAPHICSPATH.'rows.png'; ?>">
			<? } ?>
		</td>
		<? } ?>
		<td height="30" width="99%" align="center"><h2><? echo $layer['Name']; ?></h2></td>
		<? if (!$this->user->rolle->visually_impaired AND $anzObj > 0) { ?>
		<td valign="top" style="padding: 0 10 0 0">
			<? if($layer['template'] == '' OR $layer['template'] == 'generic_layer_editor_2.php'){ ?>
			<img onclick="checkForUnsavedChanges(event);switch_gle_view1(<? echo $layer['Layer_ID']; ?>);"" title="<? echo $strSwitchGLEViewColumns; ?>" class="hover-border pointer" src="<? echo GRAPHICSPATH.'columns.png'; ?>">
			<? }else{ ?>
			<img onclick="checkForUnsavedChanges(event);switch_gle_view1(<? echo $layer['Layer_ID']; ?>);"" title="<? echo $strSwitchGLEViewRows; ?>" class="hover-border pointer" src="<? echo GRAPHICSPATH.'rows.png'; ?>">
			<? } ?>
		</td>
		<td align="right" valign="top">
			<a href="javascript:scrollbottom();"><img class="hover-border" title="nach unten" src="<? echo GRAPHICSPATH; ?>pfeil.gif" width="11" height="11" border="0"></a>&nbsp;
		</td>
		<? } ?>
	</tr>
</table>
<?
	}

  if($doit == true){
?>
<table border="0" cellspacing="1" cellpadding="2" width="100%">
	<tr>
		<td width="100%">   
			<table class="gle1_table" cellspacing="0" cellpadding="2" width="100%">
				<? # Gruppennamen
					if($attributes['group'][0] != ''){
						echo '<tr><td style="border:none"></td><td style="border:none"></td>';
						$colspan = 1;
						for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
							$colspan++;							
							if($j == 0 OR $attributes['group'][$j] != $attributes['group'][$j+1]){
								$explosion = explode(';', $attributes['group'][$j]);
								if($explosion[1] != '')$collapsed = true;else $collapsed = false;
								if($j > 0){
									echo $colspan.'" data-colspan="'.$colspan.'">';
									echo '&nbsp;<a href="javascript:void(0);" onclick="toggleGroup(\''.$attributes['group'][$j].'\')"><img id="img_'.$attributes['group'][$j].'" border="0" src="graphics/'.($collapsed ? 'plus' : 'minus').'.gif"></a>&nbsp;'.$attributes['group'][$j].'</td><td style="border:none;background: url(graphics/bg.gif);"></td>';
									$colspan = 0;
								}
								else $colspan = 1;
								if($j < count($this->qlayerset[$i]['attributes']['name'])-1)echo '<td id="'.$attributes['group'][$j+1].'" style="background: '.BG_GLEATTRIBUTE.'" colspan="';								
							}
						}
						echo '</tr>';
					}
					?>
			  <tr bgcolor="<?php echo BG_DEFAULT ?>">
				<td></td>
			  <?
					$has_geom = false;
			  	for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
						if($attributes['group'][$j] != $attributes['group'][$j-1]){		# wenn die vorige Gruppe anders ist, Leerspalte einfügen
							echo '<td class="gap_'.$attributes['group'][$j].'" style="border:none;background: url(graphics/bg.gif);"></td>';
						}
						if($attributes['visible'][$j] AND $attributes['name'][$j] != 'lock'){
							if($this->qlayerset[$i]['attributes']['type'][$j] != 'geometry'){
								echo '<td id="column'.$j.'" class="group_'.$attributes['group'][$j].'"';
									//if($attributes['group'][0] != '')echo 'width="10%"';
									echo ' valign="top" bgcolor="'.BG_GLEATTRIBUTE.'">';									
									if($attributes['privileg'][$j] != '0' AND !$lock[$k]){
										$this->editable = $layer['Layer_ID'];
									}
									if($attributes['alias'][$j] == ''){
										$attributes['alias'][$j] = $attributes['name'][$j];
									}
									echo '<table ';
									//if($attributes['group'][0] != '')echo 'width="200px"';
									echo 'width="100%"';
									echo '><tr><td>';
									if($this->formvars['printversion'] == '' AND $attributes['form_element_type'][$j] != 'SubFormPK' AND $attributes['form_element_type'][$j] != 'SubFormEmbeddedPK'){
										echo '<a style="font-size: '.$this->user->rolle->fontsize_gle.'px" title="Sortieren nach '.$attributes['alias'][$j].'" href="javascript:change_orderby(\''.$attributes['name'][$j].'\', '.$layer['Layer_ID'].');">
														'.$attributes['alias'][$j].'</a>';
									}
									else{
										echo '<span style="font-size: '.$this->user->rolle->fontsize_gle.'px; color:#222222;">'.$attributes['alias'][$j].'</span>';
									}
									if($attributes['nullable'][$j] == '0' AND $attributes['privileg'][$j] != '0'){
										echo '<span title="Eingabe erforderlich">*</span>';
									}
									if($attributes['tooltip'][$j]!='' AND $attributes['form_element_type'][$j] != 'Time'){
										echo '<td align="right"><a href="javascript:void(0);" title="'.$attributes['tooltip'][$j].'"><img src="'.GRAPHICSPATH.'emblem-important.png" border="0"></a></td>';
									}
									if($attributes['type'][$j] == 'date' OR $attributes['type'][$j] == 'timestamp' OR $attributes['type'][$j] == 'time'){
										echo '<td align="right"><a href="javascript:;" title="(TT.MM.JJJJ)"><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a><div id="calendar"><input type="hidden" id=calendar_'.$attributes['name'][$j].'_'.$k.'"></div></td>';
									}
									echo '</td>';
									echo '<td><div onmousedown="resizestart(document.getElementById(\'column'.$j.'\'), \'col_resize\');" style="transform: translate(8px); float: right; right: 0px; height: 20px; width: 6px; cursor: e-resize;"></div></td>';
									echo '</tr></table>';
									echo '</td>';									
							}
							else{
								$has_geom = true;
							}
						}
			  	}
					if($has_geom)echo '<td bgcolor="'.BG_GLEATTRIBUTE.'">&nbsp;</td>';
			  ?>
			  </tr>
<?
	for ($k=0;$k<$anzObj;$k++) {
		$checkbox_names .= 'check;'.$attributes['table_alias_name'][$layer['maintable']].';'.$layer['maintable'].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].'|';
?>
	<tr 
	<? if($this->user->rolle->querymode == 1){ ?>
		onmouseenter="highlight_object(<? echo $layer['Layer_ID']; ?>, '<? echo $layer['shape'][$k][$attributes['table_name'][$attributes['the_geom']].'_oid']; ?>');"
	<? } ?>
	>
		<td style="background-color:<? echo BG_DEFAULT; ?>;">
		  <? if($this->new_entry != true AND $this->formvars['printversion'] == ''){ ?>
		  <table>
				<tr>
					<td style="line-height: 1px; ">
						<input type="hidden" value="" onchange="changed_<? echo $layer['Layer_ID']; ?>.value=this.value;document.GUI.gle_changed.value=this.value" name="changed_<? echo $layer['Layer_ID'].'_'.$layer['shape'][$k][$layer['maintable'].'_oid']; ?>"> 
						<input id="<? echo $layer['Layer_ID'].'_'.$k; ?>" type="checkbox" name="check;<? echo $attributes['table_alias_name'][$layer['maintable']].';'.$layer['maintable'].';'.$layer['shape'][$k][$layer['maintable'].'_oid']; ?>">&nbsp;
					</td>
				</tr>
		  </table>
		  <? } ?>
	</td>
	
<?

		for($j = 0; $j < count($attributes['name']); $j++){
			if($attributes['group'][$j] != $attributes['group'][$j-1]){		# wenn die vorige Gruppe anders ist, Leerspalte einfügen
				echo '<td class="gap_'.$attributes['group'][$j].'" style="border:none;background: url(graphics/bg.gif);"></td>';
			}
			if(($attributes['privileg'][$j] == '0' AND $attributes['form_element_type'][$j] == 'Auswahlfeld') OR ($attributes['form_element_type'][$j] == 'Text' AND $attributes['type'][$j] == 'not_saveable')){				# entweder ist es ein nicht speicherbares Attribut oder ein nur lesbares Auswahlfeld, dann ist es auch nicht speicherbar
				$attributes['form_element_type'][$j] .= '_not_saveable';
			}
			if($attributes['visible'][$j]){
				if($attributes['type'][$j] != 'geometry') {
					echo '<td' . get_td_class_or_style(array('group_'.$attributes['group'][$j], $layer['shape'][$k][$attributes['style']], 'position: relative; text-align: right')) . '>';
					if(in_array($attributes['type'][$j], array('date', 'time', 'timestamp'))){
						echo calendar($attributes['type'][$j], $layer['Layer_ID'].'_'.$attributes['name'][$j].'_'.$k, $attributes['privileg'][$j]);
					}
					echo attribute_value($this, $layer, NULL, $j, $k, NULL, $size, $select_width, $this->user->rolle->fontsize_gle);
					echo '<div onmousedown="resizestart(document.getElementById(\'column'.$j.'\'), \'col_resize\');" style="position: absolute; transform: translate(4px); top: 0px; right: 0px; height: 100%; width: 8px; cursor: e-resize;"></div>';
					echo '</td>';
					if($attributes['privileg'][$j] >= '0'){
						$this->form_field_names .= $layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'|';
					}
				}
				else {
					$columnname = $attributes['real_name'][$attributes['name'][$j]];
					$geom_tablename = $attributes['table_name'][$attributes['name'][$j]];
					$geomtype = $attributes['geomtype'][$attributes['name'][$j]];
					$dimension = $attributes['dimension'][$j];
					$privileg = $attributes['privileg'][$j];
					$nullable = $attributes['nullable'][$j];
					$this->form_field_names .= $layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';Geometrie;'.$attributes['nullable'][$j].'|';
				}
			}
			else{
				$invisible_attributes[$layer['Layer_ID']][] = '<input type="hidden" id="'.$layer['Layer_ID'].'_'.$attributes['name'][$j].'_'.$k.'" value="'.htmlspecialchars($layer['shape'][$k][$attributes['name'][$j]]).'">';
			}
		}
				if(($columnname != '' OR $layer['shape'][$k]['geom'] != '') AND $this->new_entry != true AND $this->formvars['printversion'] == ''){
					$geometry = true; ?>
			    	    <td style="padding-top:5px; padding-bottom:5px;">
<?						
							if(!$layer['shape'][$k]['wfs_geom']){		// kein WFS
								echo '<input type="hidden" id="'.$columnname.'_'.$k.'" value="'.$layer['shape'][$k][$columnname].'">';
								if($geomtype == 'POLYGON' OR $geomtype == 'MULTIPOLYGON' OR $geomtype == 'GEOMETRY')$geomtype = 'Polygon';
								elseif($geomtype == 'POINT')$geomtype = 'Point';
								elseif($geomtype == 'MULTILINESTRING' OR $geomtype == 'LINESTRING')$geomtype = 'Line';
?>								
								<table cellspacing="0" cellpadding="0">
									<tr>
<?								if($privileg == 1 AND !$lock[$k]) { ?>
										<td style="padding: 0 0 0 10;"><a onclick="checkForUnsavedChanges(event);" title="<? echo $strEditGeom; ?>" href="index.php?go=<? echo $geomtype; ?>Editor&oid=<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>&layer_tablename=<? echo $geom_tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selected_layer_id=<? echo $layer['Layer_ID'];?>&dimension=<? echo $dimension; ?>"><div class="button edit_geom"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
<?								} 
								if($layer['shape'][$k][$attributes['the_geom']]){ ?>
										<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom; ?>" href="javascript:zoom2object(<? echo $layer['Layer_ID'];?>, '<? echo $geomtype; ?>', '<? echo $geom_tablename; ?>', '<? echo $columnname; ?>', '<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>', 'zoomonly');"><div class="button zoom_normal"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
								<? if($layer['Layer_ID'] > 0){ ?>
										<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom.$strAndHighlight; ?>" href="javascript:zoom2object(<? echo $layer['Layer_ID'];?>, '<? echo $geomtype; ?>', '<? echo $geom_tablename; ?>', '<? echo $columnname; ?>', '<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>', 'false');"><div class="button zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
										<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom.$strAndHide; ?>" href="javascript:zoom2object(<? echo $layer['Layer_ID'];?>, '<? echo $geomtype; ?>', '<? echo $geom_tablename; ?>', '<? echo $columnname; ?>', '<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>', 'true');"><div class="button zoom_select"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
								<? }} ?>
									</tr>
								</table>
<?
						}
						else{		# bei WFS-Layern
?>						<table cellspacing="0" cellpadding="0">
								<tr>
									<td style="padding: 0 0 0 5;"><a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="javascript:zoom2object('go=zoom2wkt&wkt=<? echo $layer['shape'][$k]['geom']; ?>&epsg=<? echo $layer['epsg_code']; ?>');"><div class="button zoom_normal"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
								</tr>
							</table>
<?															
						}				

?>
			    </td>
		<? 	} ?>
				</tr>
<?	} ?>
				<tr onclick="toggle_statistic_row(<? echo $layer['Layer_ID']; ?>);">
					<td style="background-color:<? echo BG_TR; ?>;" valign="top" align="center">
						&Sigma;
					</td><?
					for ($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
						if($attributes['group'][$j] != $attributes['group'][$j-1]){		# wenn die vorige Gruppe anders ist, Leerspalte einfügen
							echo '<td class="gap_'.$attributes['group'][$j].'" style="border:none;background: url(graphics/bg.gif);"></td>';
						}
						if($attributes['visible'][$j] AND $attributes['name'][$j] != 'lock'){ ?>
							<td valign="top" class="group_<? echo $attributes['group'][$j]; ?>">
								<div class="statistic_row_<? echo $layer['Layer_ID']; ?>" style="display:none"><?php
								$column_name = $this->qlayerset[$i]['attributes']['name'][$j];
								if(in_array($this->qlayerset[$i]['attributes']['type'][$j], array('numeric', 'float4', 'float8', 'int2', 'int4', 'int8'))) {
									$values = array_map(
										function ($row) use ($column_name) {
											return $row[$column_name];
										},
										$this->qlayerset[$i]['shape']
									);
									$summe = array_sum($values);
									$average = round($summe / count($values), 2);
									$min = min($values);
									$max = max($values);
									$statistic = array();
									$statistic['Summe'] = array('title' => '&Sigma;', 'value' => $summe);
									$statistic['Durchschnitt'] = array('title' => '&empty;', 'value' => $average);
									$statistic['Min'] = array('title' => '&darr;', 'value' => $min);
									$statistic['Max'] = array('title' => '&uarr;', 'value' => $max);
									#$statistic['relative Häufigkeit'] = relative_haeufigkeit($this->qlayerset[$i]['shape'], $column_name, $min, $max);
									#$statistic['absolute Häufigkeit'] = absolute_haeufigkeit($this->qlayerset[$i]['shape'], $column_name);
									if ($summe > 0) {
										output_statistic($statistic);
									}
								} ?></div>
							</td><?
						}
					} ?>
				</tr>

<?
			if($this->new_entry != true AND $this->editable == $layer['Layer_ID']){
?>
				<tr id="edit_all1_<? echo $layer['Layer_ID']; ?>" style="height: 30px">
					<td colspan="200" style="border: none;vertical-align: bottom"><a href="javascript:switch_edit_all(<? echo $layer['Layer_ID']; ?>);"><? echo $strEditAll; ?></a></td>
				</tr>
				<tr id="edit_all2_<? echo $layer['Layer_ID']; ?>" style="height: 30px; display: none">
					<td colspan="200" style="border: none;vertical-align: bottom"><a href="javascript:switch_edit_all(<? echo $layer['Layer_ID']; ?>);"><? echo $strEditAll; ?></a></td>
				</tr>
				<tr id="edit_all3_<? echo $layer['Layer_ID']; ?>" bgcolor="<?php echo BG_DEFAULT ?>" style="display: none">
				<td></td>
			  <?
			  	for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
						if($attributes['group'][$j] != $attributes['group'][$j-1]){		# wenn die vorige Gruppe anders ist, Leerspalte einfügen
							echo '<td class="gap_'.$attributes['group'][$j].'" style="border:none;background: url(graphics/bg.gif);"></td>';
						}
						if($attributes['visible'][$j] AND $attributes['name'][$j] != 'lock'){
							if($this->qlayerset[$i]['attributes']['type'][$j] != 'geometry'){
								echo '<td class="group_'.$attributes['group'][$j].'"';
									echo ' valign="top" bgcolor="'.BG_GLEATTRIBUTE.'">';
									if($attributes['alias'][$j] == ''){
										$attributes['alias'][$j] = $attributes['name'][$j];
									}
									echo '<table ';
									echo 'width="100%";';
									echo '><tr><td>';
									echo '<span style="font-size: '.$this->user->rolle->fontsize_gle.'px; color:#222222;">'.$attributes['alias'][$j].'</span>';
									if($attributes['nullable'][$j] == '0' AND $attributes['privileg'][$j] != '0'){
										echo '<span title="Eingabe erforderlich">*</span>';
									}
									if($attributes['tooltip'][$j]!='' AND $attributes['form_element_type'][$j] != 'Time'){
										echo '<td align="right"><a href="javascript:void(0);" title="'.$attributes['tooltip'][$j].'"><img src="'.GRAPHICSPATH.'emblem-important.png" border="0"></a></td>';
									}
									if($attributes['type'][$j] == 'date' OR $attributes['type'][$j] == 'timestamp' OR $attributes['type'][$j] == 'timestamptz'){
										echo '<td align="right"><a href="javascript:;" title=" (TT.MM.JJJJ) '.$attributes['tooltip'][$j].'" ';
										if($attributes['privileg'][$j] == '1' AND !$lock[$k]){
											echo 'onclick="add_calendar(event, \''.$attributes['name'][$j].'_'.$k.'\');"';
										}
										echo '><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a><div id="calendar"><input type="hidden" id=calendar_'.$attributes['name'][$j].'_'.$k.'"></div></td>';
									}
									echo '</td></tr></table>';
									echo '</td>';
							}
						}
			  	}
			  ?>
			  </tr>

				<tr id="edit_all4_<? echo $layer['Layer_ID']; ?>" style="display: none">
					<td style="text-align: center; background-color:<? echo BG_DEFAULT; ?>;">
						<img src="<?php echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(new Array('Hilfe:','Sie können hier die Attribut-Werte von mehreren Datensätzen gleichzeitig bearbeiten. Die Werte werden nur für die ausgewählten Datensätze übernommen.'),Style[0], document.getElementById('TipLayer<? echo $layer['Layer_ID']; ?>'))" onmouseout="htm()">
						<DIV id="TipLayer<? echo $layer['Layer_ID']; ?>" style="visibility:hidden;position:absolute;z-index:1000;"></DIV>
					</td>
					<?					
						for($j = 0; $j < count($attributes['name']); $j++){
							if($attributes['group'][$j] != $attributes['group'][$j-1]){		# wenn die vorige Gruppe anders ist, Leerspalte einfügen
								echo '<td class="gap_'.$attributes['group'][$j].'" style="border:none;background: url(graphics/bg.gif);"></td>';
							}
							if(($attributes['privileg'][$j] == '0' AND $attributes['form_element_type'][$j] == 'Auswahlfeld') OR ($attributes['form_element_type'][$j] == 'Text' AND $attributes['type'][$j] == 'not_saveable')){				# entweder ist es ein nicht speicherbares Attribut oder ein nur lesbares Auswahlfeld, dann ist es auch nicht speicherbar
								$attributes['form_element_type'][$j] .= '_not_saveable';
							}
							if($attributes['visible'][$j] AND $attributes['name'][$j] != 'lock'){
								if($attributes['type'][$j] != 'geometry'){
									echo '<td class="group_'.$attributes['group'][$j].'">';
									if(!in_array($attributes['form_element_type'][$j], array('Dokument', 'SubFormPK', 'SubFormEmbeddedPK'))){
										if(in_array($attributes['type'][$j], array('date', 'time', 'timestamp'))){
											echo calendar($attributes['type'][$j], $layer['Layer_ID'].'_'.$attributes['name'][$j].'_'.$k, $attributes['privileg'][$j]);
										}
										echo attribute_value($this, $layer, NULL, $j, $k, NULL, $size, $select_width, $this->user->rolle->fontsize_gle, true);
									}
									echo '</td>';
								}
							}
						}
					?>
				</tr>
				
	<?  } ?>
			</table>
		</td>
	</tr>
</table>

<?
	if($this->formvars['printversion'] == ''){
?>
<table width="100%" border="0">
	<tr>
		<td colspan="2"align="left">
		<? if($this->new_entry != true){ ?>
			<table width="100%" border="0" cellspacing="4" cellpadding="0">
				<tr>
					<td colspan="2">
						<i><? echo $layer['Name'] ?></i>:&nbsp;<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="javascript:selectall(<? echo $layer['Layer_ID']; ?>);">
						<? if ($layer['count'] > MAXQUERYROWS) {
						    echo $strSelectAllShown;
						   } else {
						    echo $strSelectAll;
						   } ?>
						</a>
					</td>
				</tr>
				<tr>
					<? if($layer['export_privileg'] != 0){ ?>
					<td style="padding: 5 0 0 0;">
						<select id="all_<? echo $layer['Layer_ID']; ?>" name="all_<? echo $layer['Layer_ID']; ?>" onchange="update_buttons(this.value, <? echo $layer['Layer_ID']; ?>);">
							<option value=""><? echo $strSelectedDatasets.':'; ?></option>
							<option value="true"><? echo $strAllDatasets.':'; ?><? if ($layer['count'] > MAXQUERYROWS){	echo "&nbsp;(".$layer['count'].")"; } ?></option>
						</select>
					</td>					
					<? }else{ ?>
					<td style="padding: 5 0 0 0;"><? echo $strSelectedDatasets.':'; ?></td>
					<? } ?>
				</tr>
				<tr>
					<td>
						<table cellspacing="0" cellpadding="0">
							<tr>
					<? if($layer['privileg'] == '2'){ ?>
								<td id="delete_link_<? echo $layer['Layer_ID']; ?>" style="padding: 5 10 0 0;"><a onclick="checkForUnsavedChanges(event);" title="<? echo $strdelete; ?>" href="javascript:delete_datasets(<?php echo $layer['Layer_ID']; ?>);"><div class="button_background"><div class="button datensatz_loeschen"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></td>
					<?} if($layer['export_privileg'] != 0){ ?>
								<td style="padding: 5 10 0 0;"><a title="<? echo $strExport; ?>" href="javascript:daten_export(<?php echo $layer['Layer_ID']; ?>, <? echo $layer['count']; ?>);"><div class="button_background"><div class="button datensatz_exportieren"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
					<? } if($layer['layouts']){ ?>
								<td id="print_link_<? echo $layer['Layer_ID']; ?>" style="padding: 5 10 0 0;"><a title="<? echo $strPrint; ?>" href="javascript:print_data(<?php echo $layer['Layer_ID']; ?>);"><div class="button_background"><div class="button drucken"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
					<? } ?>
					<? if($privileg != ''){ ?>
								<td id="zoom_link_<? echo $layer['Layer_ID']; ?>" style="padding: 5 10 0 0;"><a title="<? echo $strzoomtodatasets; ?>" href="javascript:zoomto_datasets(<?php echo $layer['Layer_ID']; ?>, '<? echo $geom_tablename; ?>', '<? echo $columnname; ?>');"><div class="button zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
								<td id="classify_link_<? echo $layer['Layer_ID']; ?>" style="padding: 5 0 0 0;">
									<select style="width: 130px" name="klass_<?php echo $layer['Layer_ID']; ?>">
										<option value=""><? echo $strClassify; ?>:</option>
										<?
										for($j = 0; $j < count($attributes['name']); $j++){
											if($attributes['name'][$j] != $attributes['the_geom']){
												echo '<option value="'.$attributes['name'][$j].'">'.$attributes['alias'][$j].'</option>';
											}
										}
										?>
									</select>
								</td>
					<?}?>
							</tr>
						</table>
					</td>
				</tr>
				<tr style="display:none">
					<td height="23" colspan="3">
						&nbsp;&nbsp;&bull;&nbsp;<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="javascript:showcharts(<?php echo $layer['Layer_ID']; ?>);"><? echo $strCreateChart; ?></a>
					</td>
				</tr>
				<tr id="charts_<?php echo $layer['Layer_ID']; ?>" style="display:none">
					<td></td>
					<td>
						<table>
							<tr>
								<td colspan="2">
									&nbsp;&nbsp;<select name="charttype_<?php echo $layer['Layer_ID']; ?>" onchange="change_charttype(<?php echo $layer['Layer_ID']; ?>);">
										<option value="bar">Balkendiagramm</option>
										<option value="mirrorbar">doppeltes Balkendiagramm</option>
										<option value="circle">Kreisdiagramm</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;&nbsp;Beschriftung:
								</td>
								<td>
									<select style="width:133px" id="" name="chartlabel_<?php echo $layer['Layer_ID']; ?>" >
										<?
										for($j = 0; $j < count($attributes['name']); $j++){
											if($attributes['name'][$j] != $attributes['the_geom']){
												echo '<option value="'.$attributes['name'][$j].'">'.$attributes['alias'][$j].'</option>';
											}
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;&nbsp;Wert:
								</td>
								<td>
									<select style="width:133px" name="chartvalue_<?php echo $layer['Layer_ID']; ?>" onchange="create_chart(<?php echo $layer['Layer_ID']; ?>);">
										<option value="">--- Bitte Wählen ---</option>
										<?
										for($j = 0; $j < count($attributes['name']); $j++){
											if($attributes['name'][$j] != $attributes['the_geom']){
												echo '<option value="'.$attributes['name'][$j].'">'.$attributes['alias'][$j].'</option>';
											}
										}
										?>
									</select>
								</td>
							</tr>
							<tr id="split_<?php echo $layer['Layer_ID']; ?>" style="display:none">
								<td>
									&nbsp;&nbsp;Trenn-Attribut:
								</td>
								<td>
									<select style="width:133px" name="chartsplit_<?php echo $layer['Layer_ID']; ?>" onchange="create_chart(<?php echo $layer['Layer_ID']; ?>);">
										<option value="">--- Bitte Wählen ---</option>
										<?
										for($j = 0; $j < count($attributes['name']); $j++){
											if($attributes['name'][$j] != $attributes['the_geom']){
												echo '<option value="'.$attributes['name'][$j].'">'.$attributes['alias'][$j].'</option>';
											}
										}
										?>
									</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		<?} ?>
		</td>
	</tr>
</table>
	<? } 
	
	for($l = 0; $l < count($invisible_attributes[$layer['Layer_ID']]); $l++){
		echo $invisible_attributes[$layer['Layer_ID']][$l]."\n";
	}
	
?>
	
<input type="hidden" name="checkbox_names_<? echo $layer['Layer_ID']; ?>" value="<? echo $checkbox_names; ?>">
<input type="hidden" name="orderby<? echo $layer['Layer_ID']; ?>" id="orderby<? echo $layer['Layer_ID']; ?>" value="<? echo $this->formvars['orderby'.$layer['Layer_ID']]; ?>">

<?
  }
  elseif($layer['requires'] == ''){
?>
<table border="0" cellspacing="10" cellpadding="2">
  <tr>
	<td>
      <span style="font-size:12px; color:#FF0000;"><? echo $strNoMatch; ?></span>
	</td>
  </tr>
</table>

<?
  }
?>
</div>
