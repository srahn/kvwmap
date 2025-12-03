<?php
 # 2008-09-30 sr
  include(LAYOUTPATH.'languages/generic_layer_editor_2_'.rolle::$language.'.php');
 
	# Variablensubstitution
	$layer = $this->qlayerset[$i];
	$layer_id = $layer['layer_id'];
	if ($this->currentform == 'document.GUI2') {
		$size = 40;
	}
	else {
		$size = 61;
	}
	$select_width = '';
?>

<script type="text/javascript">

	open_record = function(event, record){
		if(record.className == 'raster_record'){
			event.preventDefault();
			alldivs = document.getElementsByTagName('div');
			for(i = 0; i < alldivs.length; i++){
				classname = alldivs[i].className;
				if(classname == 'raster_record_open'){
					alldivs[i].className = 'raster_record';
				}
			}
			record.className = 'raster_record_open';
		}
		setTimeout(auto_resize_overlay, 250);
	}
	
	close_record = function(record){
		document.getElementById(record).className = 'raster_record';
	}
		
</script>

<? if($this->formvars['embedded_subformPK'] == '' AND $this->new_entry != true){ ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<!--td valign="top" style="padding: 0 0 0 0">
			<? if($layer['template'] != 'generic_layer_editor.php'){ ?>
			<a href="javascript:switch_gle_view1(<? echo $layer['layer_id']; ?>);"><img title="<? echo $strSwitchGLEViewColumns; ?>" class="hover-border" src="<? echo GRAPHICSPATH.'columns.png'; ?>"></a>
			<? }else{ ?>
			<a href="javascript:switch_gle_view1(<? echo $layer['layer_id']; ?>);"><img title="<? echo $strSwitchGLEViewRows; ?>" class="hover-border" src="<? echo GRAPHICSPATH.'rows.png'; ?>"></a>
			<? } ?>
		</td-->
		<td height="30" width="99%" align="center"><h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $layer['name']; ?></h2></td>
		<!--td valign="top" style="padding: 0 10 0 0">
			<? if($layer['template'] != 'generic_layer_editor.php'){ ?>
			<a href="javascript:switch_gle_view1(<? echo $layer['layer_id']; ?>);"><img title="<? echo $strSwitchGLEViewColumns; ?>" class="hover-border" src="<? echo GRAPHICSPATH.'columns.png'; ?>"></a>
			<? }else{ ?>
			<a href="javascript:switch_gle_view1(<? echo $layer['layer_id']; ?>);"><img title="<? echo $strSwitchGLEViewRows; ?>" class="hover-border" src="<? echo GRAPHICSPATH.'rows.png'; ?>"></a>
			<? } ?>
		</td-->
		<td align="right" valign="top">
		</td>
	</tr>
	<tr><td><img height="7" src="<? echo GRAPHICSPATH ?>leer.gif"></td></tr>
</table>
<?
		}

		if ($this->formvars['overwrite_layer_name'] != '') {
			$layer_name = $this->formvars['overwrite_layer_name']; ?>
			<input type="hidden" value="<? echo $this->formvars['overwrite_layer_name']; ?>" name="overwrite_layer_name"><?
		}
		else {
			$layer_name = $layer['Name_or_alias'];
		}

  	$doit = false;
	  $anzObj = count_or_0($layer['shape']);
		if ($anzObj > 0) {
			$this->found = 'true';
			$k = 0;
			$doit = true;
		}
		if($this->new_entry == true){
			$anzObj = 0;
			$k = -1;
			$doit = true;
		}
	  if($doit == true){
?>
<div id="layer" align="left" onclick="remove_calendar();">
	<input type="hidden" value="" id="changed_<? echo $layer['layer_id']; ?>" onchange="activate_save_button(this.closest('#layer').parentElement, '<? echo $layer['layer_id']; ?>');" name="changed_<? echo $layer['layer_id']; ?>">
	<table border="0" cellspacing="0" cellpadding="2">
		<tr>
			<td>
				<div style="<? if($this->new_entry != true)echo 'max-width: 735px;'; ?> display: flex; flex-wrap: wrap; align-items: flex-start">
<?
	$hover_preview = true;
	$checkbox_names = '';
	$columnname = '';
	$geom_tablename = '';
	$geomtype = '';
	$dimension = '';
	$privileg = '';
	if($this->formvars['embedded_subformPK'] == '')$records_per_row = 5;
	else $records_per_row = 3;
	
	if($this->formvars['attribute_privileg'] == '0'){
		$layer['privileg'] = $this->formvars['attribute_privileg'];
	}
	
	for ($k;$k<$anzObj;$k++) {
		$definierte_attribute_privileges = $layer['attributes']['privileg'];		// hier sichern und am Ende des Datensatzes wieder herstellen
		if (is_array($layer['attributes']['privileg'])) {
			if ($layer['shape'][$k][$layer['attributes']['Editiersperre']] == 't' OR $this->formvars['attribute_privileg'] == '0') {
				$layer['attributes']['privileg'] = array_map(function($attribut_privileg) { return 0; }, $layer['attributes']['privileg']);
			}
		}
		
		$checkbox_names .= 'check;'.$layer['attributes']['table_alias_name'][$layer['maintable']].';'.$layer['maintable'].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].';'.$layer['layer_id'].'|'; ?>
		
		<div <? if($this->new_entry != true)echo 'class="raster_record" onclick="open_record(event, this)"'; ?> id="record_<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>" <? if($k%5==0)echo 'style="clear: both;"'?>>
			<? if($this->new_entry != true){ ?>
			<div style="position: absolute;top: 1px;right: 1px"><a href="javascript:close_record('record_<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>');" title="Schlie&szlig;en"><img style="border:none" src="<? echo GRAPHICSPATH."exit2.png"; ?>"></img></a></div>
			<? } ?>
			<input type="hidden" value="" onchange="this.closest('#layer').querySelector('#changed_<? echo $layer['layer_id']; ?>').value=this.value; this.closest('#layer').querySelector('#changed_<? echo $layer['layer_id']; ?>').onchange();" name="changed_<? echo $layer['layer_id'].'_'.str_replace('-', '', $layer['shape'][$k][$layer['maintable'].'_oid']); ?>"> 
			<table class="tgle" border="0">
				<? if($this->new_entry != true AND $this->formvars['printversion'] == ''){ ?>
				<tr class="tr_hide">
	        <th colspan="2" class="datensatz_header">			  
						<table width="100%" cellspacing="0" cellpadding="0">
							<tr>
								<? if($layer['connectiontype'] == 6){ ?>
								<td align="left" style="padding: 3px">
									<input
										id="<? echo $layer['layer_id'] . '_' . $k; ?>"
										type="checkbox"
										class="check_<? echo $layer['layer_id']; ?> <? if ($layer['shape'][$k][$layer['attributes']['Editiersperre']] == 't') { echo 'no_edit'; } ?>"
										name="check;<? echo $layer['attributes']['table_alias_name'][$layer['maintable']].';'.$layer['maintable'].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].';'.$layer['layer_id']; ?>"
										onchange="count_selected(<? echo $layer['layer_id']; ?>);"
									>&nbsp;<span style="color:<? echo TXT_GLEHEADER; ?>;"><? echo $strSelectThisDataset; ?></span><?
									if ($layer['shape'][$k][$layer['attributes']['Editiersperre']] == 't') { ?>
										<span class="editier_sperre fa-stack" title="Dieser Datensatz ist zur Bearbeitung gesperrt">
											<i class="fa fa-pencil fa-stack-1x" style="font-size:15px; margin-top: 5px;"></i>
											<i class="fa fa-ban fa-stack-1x fa-flip-horizontal" style="color: tomato; font-size:29px; margin-top: 5px;"></i>
										</span>
			<?					} ?>
								</td>
								<? } ?>
								<td align="right" style="padding: 0">
									<table cellspacing="0" cellpadding="0" class="button_background" style="border-left: 1px solid #bbb">
										<tr>
											<? if($this->formvars['go'] == 'Zwischenablage' OR $this->formvars['go'] == 'gemerkte_Datensaetze_anzeigen'){ ?>
												<td style="padding: 0"><a title="<? echo $strDontRememberDataset; ?>" href="javascript:select_this_dataset(<? echo $layer['layer_id']; ?>, <? echo $k; ?>);remove_from_clipboard(<? echo $layer['layer_id']; ?>);"><div class="button_background"><div class="button nicht_mehr_merken"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
											<? }else{ ?>
											<td style="padding: 0"><a title="<? echo $strRememberDataset; ?>" href="javascript:select_this_dataset(<? echo $layer['layer_id']; ?>, <? echo $k; ?>);add_to_clipboard(<? echo $layer['layer_id']; ?>);"><div class="button_background"><div class="button merken"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
											<? } ?>
								<? 	if($layer['privileg'] > '0'){ ?>
											<td style="padding: 0"><a href="javascript:select_this_dataset(<? echo $layer['layer_id']; ?>, <? echo $k; ?>);use_for_new_dataset(<? echo $layer['layer_id']; ?>, <? echo $k; ?>)" title="<? echo $strUseForNewDataset; ?>"><div class="button_background"><div class="button use_for_dataset"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
								<? 	} 
										if(false AND $layer['connectiontype'] == 6 AND $layer['export_privileg'] != 0){ ?>
											<td style="padding: 0"><a href="javascript:select_this_dataset(<? echo $layer['layer_id']; ?>, <? echo $k; ?>);daten_export(<? echo $layer['layer_id']; ?>, <? echo $layer['count']; ?>);" title="<? echo $strExportThis; ?>"><div class="button_background"><div class="button datensatz_exportieren"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
								<? 	}  
										if($layer['layouts']){ ?>
											<td style="padding: 0"><a title="<? echo $strPrintDataset; ?>" href="javascript:select_this_dataset(<? echo $layer['layer_id']; ?>, <? echo $k; ?>);print_data(<?php echo $layer['layer_id']; ?>);"><div class="button_background"><div class="button drucken"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
								<?	}
										if($layer['privileg'] == '2' and $layer['shape'][$k][$layer['attributes']['Editiersperre']] != 't'){
											if($this->formvars['embedded_subformPK'] == ''){ ?>											
												<td style="padding: 0"><a href="javascript:select_this_dataset(<? echo $layer['layer_id']; ?>, <? echo $k; ?>);delete_datasets(<?php echo $layer['layer_id']; ?>);" title="<? echo $strDeleteThisDataset; ?>"><div class="button_background"><div class="button datensatz_loeschen"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td> <?
											}
											else{ ?>
												<td style="padding: 0"><a href="javascript:select_this_dataset(<? echo $layer['layer_id']; ?>, <? echo $k; ?>);subdelete_data(<? echo $layer['layer_id']; ?>, 'record_<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>', '<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>', '<? echo $this->formvars['targetobject'] ?>');" title="<? echo $strDeleteThisDataset; ?>"><div class="button_background"><div class="button datensatz_loeschen"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td> <?
											}
										} ?>
											<td><img src="<? echo GRAPHICSPATH; ?>leer.gif" style="padding: 0 0 0 30"></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</th>
				</tr>
			<? } ?>
			  <tbody class="gle">
<?		$trans_oid = explode('|', $layer['shape'][$k]['lock']);
			if($layer['shape'][$k]['lock'] == 'bereits �bertragen' OR $trans_oid[1] != '' AND $layer['shape'][$k][$layer['maintable'].'_oid'] == $trans_oid[1]){
				echo '<tr><td colspan="2" align="center"><span class="red">Dieser Datensatz wurde bereits �bertragen und kann nicht bearbeitet werden.</span></td></tr>';
				$lock[$k] = true;
			}
			for($j = 0; $j < count($layer['attributes']['name']); $j++){
				$datapart = '';
				if($layer['shape'][$k][$layer['attributes']['name'][$j]] == ''){
					#$layer['shape'][$k][$layer['attributes']['name'][$j]] = $this->formvars[$layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j]];
				}
				// if(($layer['attributes']['privileg'][$j] == '0' AND $layer['attributes']['form_element_type'][$j] == 'Auswahlfeld') OR ($layer['attributes']['form_element_type'][$j] == 'Text' AND $layer['attributes']['saveable'][$j] == '0')){				# entweder ist es ein nicht speicherbares Attribut oder ein nur lesbares Auswahlfeld, dann ist es auch nicht speicherbar
					// $layer['attributes']['form_element_type'][$j] .= '_not_saveable';
				// }
				
				if($layer['attributes']['group'][$j] != $layer['attributes']['group'][$j-1]){		# wenn die vorige Gruppe anders ist, Tabelle beginnen
					$explosion = explode(';', $layer['attributes']['group'][$j]);
					if($explosion[1] != '')$collapsed = true;else $collapsed = false;
					$groupname = $explosion[0];
					echo '<tr>
									<td colspan="2" width="100%">
										<table width="100%" id="colgroup'.$layer['layer_id'].'_'.$j.'_'.$k.'" class="tgle" '; if(!$collapsed)echo 'style="display:none"'; echo ' border="0"><tbody width="100%" class="gle">
											<tr class="tr_hide">
												<td width="100%" bgcolor="'.BG_GLEATTRIBUTE.'" colspan="2">&nbsp;<a href="javascript:void(0);" onclick="javascript:document.getElementById(\'group'.$layer['layer_id'].'_'.$j.'_'.$k.'\').style.display=\'\';document.getElementById(\'colgroup'.$layer['layer_id'].'_'.$j.'_'.$k.'\').style.display=\'none\';"><img border="0" src="'.GRAPHICSPATH.'/plus.gif"></a>&nbsp;&nbsp;<span class="fett">'.$groupname.'</span></td>
											</tr>
										</table>
										<table width="100%" class="tgle" id="group'.$layer['layer_id'].'_'.$j.'_'.$k.'" '; if($collapsed)echo 'style="display:none"'; echo 'border="0"><tbody class="gle">
											<tr class="tr_hide">
												<td bgcolor="'.BG_GLEATTRIBUTE.'" colspan="40">&nbsp;<a href="javascript:void(0);" onclick="javascript:document.getElementById(\'group'.$layer['layer_id'].'_'.$j.'_'.$k.'\').style.display=\'none\';document.getElementById(\'colgroup'.$layer['layer_id'].'_'.$j.'_'.$k.'\').style.display=\'\';"><img border="0" src="'.GRAPHICSPATH.'/minus.gif"></a>&nbsp;&nbsp;<span class="fett">'.$groupname.'</span></td>
											</tr>';
				}				
				
				if($layer['attributes']['visible'][$j]){
					if($layer['attributes']['SubFormFK_hidden'][$j] != 1){
?>
					<tr class="<? if($layer['attributes']['raster_visibility'][$j] == 1)echo 'tr_show'; else echo 'tr_hide'; ?>">
<?				if($layer['attributes']['type'][$j] != 'geometry'){
						echo '<td valign="top" bgcolor="'.BG_GLEATTRIBUTE.'">';
						if($layer['attributes']['privileg'][$j] != '0' AND !$lock[$k]){
							$this->editable = $layer['layer_id'];
						}
						if($layer['attributes']['alias'][$j] == ''){
							$layer['attributes']['alias'][$j] = $layer['attributes']['name'][$j];
						}
						echo '<table width="100%" cellspacing="0" cellpadding="0"><tr style="border: none"><td>';
						if(!in_array($layer['attributes']['form_element_type'][$j], array('SubFormPK', 'SubFormEmbeddedPK', 'SubFormFK', 'dynamicLink'))){
							echo '<a title="Sortieren nach '.$layer['attributes']['alias'][$j].'" href="javascript:change_orderby(\''.$layer['attributes']['name'][$j].'\', '.$layer['layer_id'].');">'.$layer['attributes']['alias'][$j].'</a>';
						}
						else{
							echo '<span style="color:#222222;">'.$layer['attributes']['alias'][$j].'</span>';
						}
						if($layer['attributes']['nullable'][$j] == '0' AND $layer['attributes']['privileg'][$j] != '0'){
							echo '<span title="Eingabe erforderlich">*</span>';
						}
						if($layer['attributes']['tooltip'][$j]!='' AND $layer['attributes']['form_element_type'][$j] != 'Time') {
						  echo '<td align="right"><a href="#" title="'.$layer['attributes']['tooltip'][$j].'"><img src="'.GRAPHICSPATH.'emblem-important.png" border="0"></a></td>';
						}
						$date_types = array('date' => 'TT.MM.JJJJ', 'timestamp' => 'TT.MM.JJJJ hh:mm:ss', 'time' => 'hh:mm:ss');
						if(array_key_exists($layer['attributes']['type'][$j], $date_types)){
							echo '
								<td align="right">
										<a id="caldbl" href="javascript:;" title="('.$date_types[$layer['attributes']['type'][$j]].')"'.
										(($layer['attributes']['privileg'][$j] == '1') ? 'onclick="add_calendar(event, \''.$layer_id.'_'.$layer['attributes']['name'][$j].'_'.$k.'\', \''.$layer['attributes']['type'][$j].'\');" 
																														 ondblclick="add_calendar(event, \''.$layer_id.'_'.$layer['attributes']['name'][$j].'_'.$k.'\', \''.$layer['attributes']['type'][$j].'\', true);"' : '').'
									><img src="' . GRAPHICSPATH . 'calendarsheet.png" border="0"></a>
									<div id="calendar_'.$layer_id.'_'.$layer['attributes']['name'][$j].'_'.$k.'" class="calendar"></div>
								</td>
							';
						}
						echo '</td></tr></table>';
						echo '</td><td '.get_td_class_or_style(array($layer['shape'][$k][$layer['attributes']['style_attribute'][$j]], 'gle_attribute_value')).'><div id="formelement">';
						echo attribute_value($this, $layer, NULL, $j, $k, NULL, $size, $select_width, false, NULL, NULL, NULL, $this->subform_classname);
						if($layer['attributes']['privileg'][$j] >= '0' AND !($layer['attributes']['privileg'][$j] == '0' AND $layer['attributes']['form_element_type'][$j] == 'Auswahlfeld')){
							$this->form_field_names .= $layer['layer_id'].';' . ($layer['attributes']['saveable'][$j]? $layer['attributes']['real_name'][$layer['attributes']['name'][$j]] : '') . ';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|';
						}
		  		}
		  		else {
						$columnname = $layer['attributes']['real_name'][$layer['attributes']['name'][$j]];
		  			$geom_tablename = $layer['attributes']['table_name'][$layer['attributes']['name'][$j]];
		  			$geomtype = $layer['attributes']['geomtype'][$layer['attributes']['name'][$j]];
		  			$dimension = $layer['attributes']['dimension'][$j];
		  			$privileg = $layer['attributes']['privileg'][$j];
		  			$this->form_field_names .= $layer['layer_id'].';' . ($layer['attributes']['saveable'][$j]? $layer['attributes']['real_name'][$layer['attributes']['name'][$j]] : '') . ';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].'|';
		  		}
?>
						</div>
					</td>
				</tr>
<?					}
					}
					else{
						$invisible_attributes[$layer['layer_id']][] = '<input type="hidden" id="'.$layer['layer_id'].'_'.$layer['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($layer['shape'][$k][$layer['attributes']['name'][$j]]).'">';
					}
					if($layer['attributes']['group'][$j] != $layer['attributes']['group'][$j+1]){		# wenn die n�chste Gruppe anders ist, Tabelle schliessen
						echo '</table></td></tr>';
					}
				}
				
				if($geomtype == 'POLYGON' OR $geomtype == 'MULTIPOLYGON' OR $geomtype == 'GEOMETRY')$geomtype = 'Polygon';
				elseif($geomtype == 'POINT')$geomtype = 'Point';
				elseif($geomtype == 'MULTILINESTRING' OR $geomtype == 'LINESTRING')$geomtype = 'Line';
				
				if(($columnname != '' OR $layer['shape'][$k]['geom'] != '') AND $this->new_entry != true AND $this->formvars['printversion'] == ''){
					if($layer['attributes']['group'][0] != ''){ ?>
						<tr><td colspan="2"><table width="100%" class="tgle" border="2" cellpadding="0" cellspacing="0"><tbody class="gle">
					<? } ?>
				 
					<tr class="tr_hide">
						<? if($layer['querymaps'][$k] != ''){ ?>
						<td <? if($layer['attributes']['group'][0] != '')echo 'width="200px"'; ?> bgcolor="<? echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;" align="center"><img style="border:1px solid grey" src="<? echo $layer['querymaps'][$k]; ?>"></td>
						<? } else { ?>
			    	    <td <? if($layer['attributes']['group'][0] != '')echo 'width="200px"'; ?> bgcolor="<? echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;">&nbsp;</td>
			    	    <? } ?>
			    	    <td style="padding-top:5px; padding-bottom:5px;" valign="middle">
<?						
							if(!$layer['shape'][$k]['wfs_geom']){		// kein WFS
								echo '<input type="hidden" id="'.$columnname.'_'.$k.'" value="'.$layer['shape'][$k][$columnname].'">'; ?>
								<table cellspacing="0" cellpadding="0">
									<tr><?
										if ($privileg == 1 AND !$lock[$k]) { ?>
											<td style="padding: 0 0 0 10;">
												<a
													onclick="checkForUnsavedChanges(event);"
													title="<? echo $strEditGeom; ?>"
													href="index.php?go=<? echo $geomtype; ?>Editor&oid=<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>&layer_tablename=<? echo $geom_tablename; ?>&layer_columnname=<? echo $columnname; ?>&selected_layer_id=<? echo $layer['layer_id'];?>&dimension=<? echo $dimension; ?>"
												><div class="button edit_geom"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a>
											</td><?
										}
										if ($layer['shape'][$k][$layer['attributes']['the_geom']]) { ?>
											<td style="padding: 0 0 0 10;">
												<a
													title="<? echo $strMapZoom; ?>"
													href="javascript:zoom2object(<? echo $layer['layer_id'];?>, '<? echo $columnname; ?>', '<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>', 'zoomonly');"
												><div class="button zoom_normal"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a>
											</td><?
											if ($layer['layer_id'] > 0) { ?>
												<td style="padding: 0 0 0 10;">
													<a
														title="<? echo $strMapZoom.$strAndHighlight; ?>"
														href="javascript:zoom2object(<? echo $layer['layer_id'];?>, '<? echo $columnname; ?>', '<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>', 'false');"
													><div class="button zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a>
												</td>
												<td style="padding: 0 0 0 10;">
													<a
														title="<? echo $strMapZoom.$strAndHide; ?>"
														href="javascript:zoom2object(<? echo $layer['layer_id'];?>, '<? echo $columnname; ?>', '<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>', 'true');"
													><div class="button zoom_select"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a>
												</td><?
											}
										} ?>
									</tr>
								</table><?
						}
						else{		# bei WFS-Layern
?>						<table cellspacing="0" cellpadding="0">
								<tr>
									<td style="padding: 0 0 0 5;"><a href="javascript:zoom2object('go=zoom2wkt&wkt=<? echo $layer['shape'][$k]['geom']; ?>&epsg=<? echo $layer['epsg_code']; ?>');"><div class="button zoom_normal"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
								</tr>
							</table>
<?															
						}									
?>								
							</td>
			    </tr>
			    
			    <? if($layer['attributes']['group'][0] != ''){ ?>
								</table></td></tr>
					<? }		    
				}
				
				if($privileg == 1) {
					if($this->new_entry == true){
						$this->titel=$strTitleGeometryEditor;
						echo '
						<tr>
							<td colspan="2" align="center">';
								include(LAYOUTPATH.'snippets/'.$geomtype.'Editor.php');
						echo'
							</td>
						</tr>';	
					}
				}
?>
			  </tbody>
			</table>
		</div>
<?
		$layer['attributes']['privileg'] = $definierte_attribute_privileges;
	}
?>
			</div>
		</td>
	</tr>
	
<?	if($this->formvars['embedded_subformPK'] == '' AND $this->formvars['printversion'] == ''){?>
	<tr>
		<td colspan="2"align="left">
		<? if($layer['connectiontype'] == 6 AND $this->new_entry != true AND $layer['layer_id'] > 0){ ?>
			<table width="100%" border="0" cellspacing="4" cellpadding="0">
				<tr>
					<td colspan="2">
						<i><? echo $layer['name'] ?></i>:&nbsp;<a href="javascript:selectall(<? echo $layer['layer_id']; ?>);">
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
						<select id="all_<? echo $layer['layer_id']; ?>" name="all_<? echo $layer['layer_id']; ?>" onchange="update_buttons(this.value, <? echo $layer['layer_id']; ?>);">
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
						<table cellspacing="0" cellpadding="0" class="button_background" style="box-shadow: none; border: 1px solid #bbb">
							<tr>
						<? if($this->formvars['go'] == 'Zwischenablage' OR $this->formvars['go'] == 'gemerkte_Datensaetze_anzeigen'){ ?>
								<td><a title="<? echo $strDontRememberDataset; ?>" href="javascript:remove_from_clipboard(<? echo $layer['layer_id']; ?>);"><div class="button nicht_mehr_merken"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
							<? }else{ ?>
								<td id="merk_link_<? echo $layer['layer_id']; ?>"><a title="<? echo $strRemember; ?>" href="javascript:add_to_clipboard(<? echo $layer['layer_id']; ?>);"><div class="button merken"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
							<? } ?>
					<? if($layer['privileg'] == '2'){ ?>
								<td id="delete_link_<? echo $layer['layer_id']; ?>"><a title="<? echo $strdelete; ?>" href="javascript:delete_datasets(<?php echo $layer['layer_id']; ?>);"><div class="button datensatz_loeschen"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></td>
					<?} if($layer['export_privileg'] != 0){ ?>
								<td><a title="<? echo $strExport; ?>" href="javascript:daten_export(<?php echo $layer['layer_id']; ?>, <? echo $layer['count']; ?>);"><div class="button datensatz_exportieren"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
					<? } if($layer['layouts']){ ?>
								<td id="print_link_<? echo $layer['layer_id']; ?>"><a title="<? echo $strPrint; ?>" href="javascript:print_data(<?php echo $layer['layer_id']; ?>);"><div class="button drucken"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
					<? } ?>
					<? if($privileg != ''){ ?>
								<td id="zoom_link_<? echo $layer['layer_id']; ?>" style="padding: 0 0 0 15px"><a title="<? echo $strzoomtodatasets; ?>" href="javascript:zoomto_datasets(<?php echo $layer['layer_id']; ?>, '<? echo $geom_tablename; ?>', '<? echo $columnname; ?>');"><div class="button zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
								<td id="classify_link_<? echo $layer['layer_id']; ?>" style="padding: 0 5px 0 0">
									<select style="width: 130px" name="klass_<?php echo $layer['layer_id']; ?>">
										<option value=""><? echo $strClassify; ?>:</option>
										<?
										for($j = 0; $j < count($layer['attributes']['name']); $j++){
											if($layer['attributes']['name'][$j] != $layer['attributes']['the_geom']){
												echo '<option value="'.$layer['attributes']['name'][$j].'">'.$layer['attributes']['alias'][$j].'</option>';
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
						&nbsp;&nbsp;&bull;&nbsp;<a href="javascript:showcharts(<?php echo $layer['layer_id']; ?>);"><? echo $strCreateChart; ?></a>
					</td>
				</tr>
				<tr id="charts_<?php echo $layer['layer_id']; ?>" style="display:none">
					<td></td>
					<td>
						<table>
							<tr>
								<td colspan="2">
									&nbsp;&nbsp;<select name="charttype_<?php echo $layer['layer_id']; ?>" onchange="change_charttype(<?php echo $layer['layer_id']; ?>);">
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
									<select style="width:133px" id="" name="chartlabel_<?php echo $layer['layer_id']; ?>" >
										<?
										for($j = 0; $j < count($layer['attributes']['name']); $j++){
											if($layer['attributes']['name'][$j] != $layer['attributes']['the_geom']){
												echo '<option value="'.$layer['attributes']['name'][$j].'">'.$layer['attributes']['alias'][$j].'</option>';
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
									<select style="width:133px" name="chartvalue_<?php echo $layer['layer_id']; ?>" onchange="create_chart(<?php echo $layer['layer_id']; ?>);">
										<option value="">--- Bitte W�hlen ---</option>
										<?
										for($j = 0; $j < count($layer['attributes']['name']); $j++){
											if($layer['attributes']['name'][$j] != $layer['attributes']['the_geom']){
												echo '<option value="'.$layer['attributes']['name'][$j].'">'.$layer['attributes']['alias'][$j].'</option>';
											}
										}
										?>
									</select>
								</td>
							</tr>
							<tr id="split_<?php echo $layer['layer_id']; ?>" style="display:none">
								<td>
									&nbsp;&nbsp;Trenn-Attribut:
								</td>
								<td>
									<select style="width:133px" name="chartsplit_<?php echo $layer['layer_id']; ?>" onchange="create_chart(<?php echo $layer['layer_id']; ?>);">
										<option value="">--- Bitte W�hlen ---</option>
										<?
										for($j = 0; $j < count($layer['attributes']['name']); $j++){
											if($layer['attributes']['name'][$j] != $layer['attributes']['the_geom']){
												echo '<option value="'.$layer['attributes']['name'][$j].'">'.$layer['attributes']['alias'][$j].'</option>';
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
	<? } ?>
	
</table>
</div>

<?

	for($l = 0; $l < count_or_0($invisible_attributes[$layer['layer_id']]); $l++){
		echo $invisible_attributes[$layer['layer_id']][$l]."\n";
	}

?>

<input type="hidden" name="checkbox_names_<? echo $layer['layer_id']; ?>" value="<? echo $checkbox_names; ?>">
<input type="hidden" name="orderby<? echo $layer['layer_id']; ?>" id="orderby<? echo $layer['layer_id']; ?>" value="<? echo $this->formvars['orderby'.$layer['layer_id']]; ?>">
<input type="hidden" id="geom_privileg_<? echo $layer['layer_id']; ?>" value="<? echo $privileg; ?>">
<?
  }
  else {
  	$this->noMatchLayers[$layer['layer_id']] = $layer_name;
  }
?>
