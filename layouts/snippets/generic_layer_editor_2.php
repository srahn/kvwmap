<?
include_once(LAYOUTPATH.'languages/generic_layer_editor_2_'.rolle::$language.'.php');
$invisible_attributes = array();
$checkbox_names = '';
$columnname = '';
$geom_tablename = '';
$geomtype = '';
$dimension = '';
$privileg = '';
$visibility = '';
$dataset_operation_position = $this->user->rolle->dataset_operations_position;
# Variablensubstitution
$layer = $this->qlayerset[$i];
if ($this->currentform == 'document.GUI2') {
	$size = 40;
}
else {
	$size = 61;
}
$select_width = '';
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
if ($this->new_entry == true){
	$anzObj = 0;
	$k = -1;
	$doit = true;
}

if ($doit == true) {
	if ($layer['attributes']['the_geom'] != '') {
		$index = $layer['attributes']['indizes'][$layer['attributes']['the_geom']];
		$columnname = $layer['attributes']['real_name'][$layer['attributes']['the_geom']];
		$geom_tablename = $layer['attributes']['table_name'][$layer['attributes']['the_geom']];
		$column_geomtype = $layer['attributes']['geomtype'][$layer['attributes']['the_geom']];
		$geomtype = getGeomType($column_geomtype, $layer['datentyp']);
		$privileg = $layer['attributes']['privileg'][$index];
		$nullable = $layer['attributes']['nullable'][$index];
		if ($this->new_entry == true AND $privileg == 1) {
			$show_geom_editor = true; ?>
			<style>
				#nds_titel p {
					margin: 0px 0px -32px 0px;
				}
				#nds_edit #layer {
					margin: 0;
				}
			</style>
<?	}
		if ($nullable === '0'){ ?>
			<script type="text/javascript">
				geom_not_null = true;
			</script><?
		}
	} ?>
	<div id="layer" onclick="remove_calendar();">
		<input type="hidden" value="" id="changed_<? echo $layer['layer_id']; ?>" name="changed_<? echo $layer['layer_id']; ?>"><?
		if ($this->new_entry != true) { ?>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td><? 
						if (
							$this->search == true AND
							$this->Stelle->isMenueAllowed('Layer-Suche') AND
							!$this->user->rolle->visually_impaired AND
							$anzObj > 0 AND
							$this->formvars['printversion'] == '' AND
							$this->formvars['keinzurueck'] == '' AND
							$this->formvars['subform_link'] == ''
						) {
							if ($this->formvars['backlink'] == '') {
								# kein backlink angegeben -> zurück zur Suche im Hauptfenster
								$backlink = 'javascript:currentform.go.value=\'get_last_search\';currentform.submit();';
								$target = 'root';
							}
							else {
								# es ist ein backlink angegeben -> zurück zum backlink im selben Fenster
								$backlink = $this->formvars['backlink'];
								$target = '_self';
								if ($this->formvars['window_type'] == 'overlay') {
									$backlink .= '&window_type=' . $this->formvars['window_type'];
								}
							}
							echo '<a href="'.strip_pg_escape_string($backlink) . '" target="' . $target . '" title="' . $strbackToSearch . '"><i class="fa fa-arrow-left hover-border" aria-hidden="true"></i></a>';
						} ?>
					</td>
					<td width="99%" align="center"></td><?
						if (!$this->user->rolle->visually_impaired AND $anzObj > 0 AND value_of($this->formvars, 'printversion') == '') { ?>
						<td valign="top" style="padding: 0 10 0 0" class="layer_header">
						</td>
						<td>
						</td><?
					} ?>
				</tr>
			</table><?
		}
		$table_id = rand(0, 100000); 
		
		if ($this->new_entry != true) {	?>
			<div style="display: flex; justify-content: space-between;">
				<div style="position: sticky; left: calc(50% - 225px); min-width: 450px"> 
					<h2 id="layername"><? echo $layer_name; ?></h2><?
					echo value_of($layer, 'paging'); ?>
				</div>
				<? if ($layer['records_status'] !== '0') { ?>
				<div class="gle-view">	<?
					$s = ($layer['template'] == ''? 0 : 1);
					for ($g = $s; $g < 3; $g++) {
						echo '<img onclick="checkForUnsavedChanges(event);switch_gle_view1(' . $layer['layer_id'] . ', ' . $layer['gle_view'] . ', ' . $g . ', this);" title="' . ${'strSwitchGLEView' . $g} . '" class="hover-border pointer gle-view-button ' . ($layer['gle_view'] == $g? 'active':'') . '" src="' . GRAPHICSPATH . 'gle' . $g . '.png">';
					}	?>
				</div>
				<? } ?>
			</div> <?
		}

		if ($dataset_operation_position == 'oben' OR $dataset_operation_position == 'beide') {
			include('dataset_operations.php');
		}		?>
		<div id="<? echo $table_id; ?>" class="records" style="width: 100%; padding:2; <? 
			if ($layer['records_status'] === '0') {
				echo 'display: none;';
			}
			elseif ($layer['gle_view'] == 2){
				echo 'display: flex;';
			} ?>"><?
			for ($k; $k<$anzObj; $k++) {
				$table = array();
				$nl = false;
				$next_row = array();
				$checkbox_names .= 'check;'.$layer['attributes']['table_alias_name'][$layer['maintable']].';'.$layer['maintable'].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].';'.$layer['layer_id'].'|'; ?>
						<div id="datensatz_<? echo $layer['layer_id'].'_'.$k; ?>" class="datensatz"
							<?
							if ($this->new_entry != true AND $this->user->rolle->tooltipquery == 1 AND $this->user->rolle->querymode == 1 AND $layer['attributes']['the_geom'] != '') { ?>
								onmouseenter="highlight_object(<? echo $layer['layer_id']; ?>, '<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>');"<?
							} ?>
						><?php
						$definierte_attribute_privileges = $layer['attributes']['privileg'];		// hier sichern und am Ende des Datensatzes wieder herstellen
						if (is_array($layer['attributes']['privileg'])) {
							if (value_of($layer['shape'][$k], value_of($layer['attributes'], 'Editiersperre')) == 't') {
								$layer['attributes']['privileg'] = array_map(function($attribut_privileg) { return 0; }, $layer['attributes']['privileg']);
								$privileg = 0;
							}
						}
						?>
						<input type="hidden" value="" onchange="changed_<? echo $layer['layer_id']; ?>.value=this.value;root.document.GUI.gle_changed.value=this.value" name="changed_<? echo $layer['layer_id'].'_'.str_replace('-', '', $layer['shape'][$k][$layer['maintable'].'_oid']); ?>">
						<input type="hidden" value="<? echo $this->formvars['opentab_' . $layer['layer_id'] . '_' . $k] ?: '0'; ?>" id="opentab_<? echo $layer['layer_id'] . '_' . $k; ?>" name="opentab_<? echo $layer['layer_id'] . '_' . $k; ?>">
						<table class="tgle dstable" border="0" cellpadding="5" cellspacing="0">
							<? if (!$this->user->rolle->visually_impaired) include(LAYOUTPATH . 'snippets/generic_layer_editor_2_layer_head.php'); ?>
							<tbody <? if(!$show_geom_editor AND $layer['attributes']['group'][0] == '')echo 'class="gle gledata"'; ?>><?
						$visibility = '';
						if (empty($layer['attributes']['tabs']) AND $show_geom_editor) {
							$layer['attributes']['tabs'] = ['Sachdaten'];
							$sachdaten_tab = true;
						}

						if (!empty($layer['attributes']['tabs'])) {
							if ($show_geom_editor) {
								if ($this->user->rolle->geom_edit_first) {
									array_unshift($layer['attributes']['tabs'], 'Geometrie');
								}
								else {
									array_push($layer['attributes']['tabs'], 'Geometrie');
									if ($this->formvars['opentab_' . $layer['layer_id'] . '_' . $k] != count($layer['attributes']['tabs']) - 1) {
										# wenn Geometrie-Tab nicht aktiv war
										$visibility_geom = 'collapsed';
									}
								}
							}
							$opentab = $layer['attributes']['tabs'][$this->formvars['opentab_' . $layer['layer_id'] . '_' . $k] ?: $this->formvars['opentab'] ?: 0];
							echo '
							<tr>
								<th>
									<div class="gle_tabs tab_' . $layer['layer_id'] . '_' . $k . '">';
										foreach ($layer['attributes']['tabs'] as $t => $tab) {
											$tabname = sonderzeichen_umwandeln($tab);
											echo '<div class="' . $layer['layer_id'] . '_' . $k . '_' . $tabname . (($opentab == $tab)? ' active_tab' : '') . '" onclick="toggle_tab(this, ' . $layer['layer_id'] . ', ' . $k . ', ' . $t . ', \'' . $tabname . '\');">' . $tab . '</div>';
										}
										echo '
									</div>
								</th>
							</tr>';
						}
						
						$visibility = '';
						if ($sachdaten_tab) {
							$tabname = 'Sachdaten';
							if ($opentab != 'Sachdaten') {
								$visibility = 'collapsed';
							}
							if ($layer['attributes']['group'][0] == '') {
								echo '<tr class="tab tab_' . $layer['layer_id'] . '_-1_' .$tabname. ' ' . $visibility . '"><td><table class="tgle"><tbody class="gle gledata">';
							}
						}
						
						for($j = 0; $j < count_or_0($layer['attributes']['name']); $j++) {
							$attribute_class = (($this->new_entry == true AND $layer['attributes']['dont_use_for_new'][$j] == -1) ? 'hidden' : 'visible');
							// if(($layer['attributes']['privileg'][$j] == '0' AND $layer['attributes']['form_element_type'][$j] == 'Auswahlfeld') OR ($layer['attributes']['form_element_type'][$j] == 'Text' AND $layer['attributes']['saveable'][$j] == '0')){				# entweder ist es ein nicht speicherbares Attribut oder ein nur lesbares Auswahlfeld, dann ist es auch nicht speicherbar
								// $layer['attributes']['form_element_type'][$j] .= '_not_saveable';
							// }
							if($this->success === false){			# nach einem fehlgeschlagenen UPDATE oder INSERT die Formularfelder mit den übergebenen Werten befüllen
								$layer['shape'][$k][$layer['attributes']['name'][$j]] = $this->formvars[$layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j]];
							}				
				
							if (
									$layer['attributes']['group'][$j] != value_of($layer['attributes']['group'], $j-1) or 
									$layer['attributes']['tab'][$j] != $layer['attributes']['tab'][$j-1]
								) {		# wenn die vorige Gruppe anders ist, Tabelle beginnen								
								$explosion = explode(';', $layer['attributes']['group'][$j]);
								if(value_of($explosion, 1) != '')$collapsed = true;else $collapsed = false;
								$groupname = $explosion[0];
								$groupname_short = explode('<br>', $groupname);
								$groupname_short = str_replace([' ', '"'], '_', $groupname_short[0]);
								if ($layer['attributes']['tab'][$j] != '') {
									$visibility = '';
									$tabname = sonderzeichen_umwandeln($layer['attributes']['tab'][$j]);
									if ($opentab != $layer['attributes']['tab'][$j]) {
										$visibility = 'collapsed';
									}
								}
								echo '<tr class="'.$layer['layer_id'].'_group_'.$groupname_short.' tab tab_' . $layer['layer_id'] . '_' . $k . '_' . $tabname . ' ' . $visibility . '">
												<td colspan="2" width="100%">
													<div>
														<table ' . ($groupname_short == $tabname? 'style="display: none"' : '') . ' width="100%" class="tglegroup" border="0" cellspacing="0" cellpadding="0"><tbody class="gle glehead">
															<tr>
																<td colspan="40">&nbsp;<a href="javascript:void(0);" onclick="toggle_group(\''.$layer['layer_id'].'_'.$j.'_'.$k.'\')">
																	<img id="group_img'.$layer['layer_id'].'_'.$j.'_'.$k.'" border="0" src="'.GRAPHICSPATH; if($collapsed)echo 'plus.gif'; else echo 'minus.gif'; echo '"></a>&nbsp;&nbsp;<span class="fett">'.$groupname.'</span>
																</td>
															</tr>
														</table>
														<table width="100%" class="tgle" id="group'.$layer['layer_id'].'_'.$j.'_'.$k.'" '; if($collapsed)echo 'style="display:none"'; echo 'border="0"><tbody class="gle gledata">';
							}

							if($layer['attributes']['visible'][$j]){
								if($layer['attributes']['type'][$j] != 'geometry'){
									if(@$layer['attributes']['SubFormFK_hidden'][$j] != 1){
										if($layer['attributes']['privileg'][$j] != '0')$this->editable = $layer['layer_id'];
										if($layer['attributes']['alias'][$j] == '')$layer['attributes']['alias'][$j] = $layer['attributes']['name'][$j];
						
										####### wenn Attribut nicht daneben -> neue Zeile beginnen ########
										if ($layer['attributes']['arrangement'][$j] != 1) {
											$row['id'] = 'tr_'.$layer['layer_id'].'_'.$layer['attributes']['name'][$j].'_'.$k;
											$row['class'] = $attribute_class;
											if ($layer['attributes']['arrangement'][$j+1] == 1) {	# wenn nächstes Attribut neben diesem stehen soll
												if($nl){
													$next_row['sidebyside'] = true;
												}
												else{
													$row['sidebyside'] = true;
												}
											}
										}
										######### Attributname #########
										if($layer['attributes']['labeling'][$j] != 2){
											$cell['properties'] = 'class="gle-attribute-name"';
											$cell['id'] = 'name_'.$layer['layer_id'].'_'.$layer['attributes']['name'][$j].'_'.$k;
											$cell['content'] = attribute_name($layer['layer_id'], $layer['attributes'], $j, $k, (value_of($this->formvars, 'printversion') == '' AND $anzObj > 1) ? true : false);
											if($nl AND $layer['attributes']['labeling'][$j] != 1){
												$next_row['contains_attribute_names'] = true;
												$next_row['cells'][] = $cell;
											}
											else{
												$row['contains_attribute_names'] = true;
												$row['cells'][] = $cell;
											}
										}
										if($layer['attributes']['labeling'][$j] == 1)$nl = true;										# Attributname soll oben stehen -> alle weiteren Zellen für die nächste Zeile aufsammeln
										######### /Attributname #########
						
										if(value_of($row, 'sidebyside') OR value_of($next_row, 'sidebyside')){
											$select_width2 = '';
											$size2 = 10;
										}
										else{
											$size2 = $size;
											$select_width2 = $select_width;
										}
										if ($select_width2 == '') $select_width2 = 'max-width: 600px;';

										######### Attributwert #########
										$cell['content'] = attribute_value($this, $layer, NULL, $j, $k, NULL, $size2, $select_width2);
										$cell['id'] = 'value_'.$layer['layer_id'].'_'.$layer['attributes']['name'][$j].'_'.$k;
										$cell['properties'] = get_td_class_or_style(array($layer['shape'][$k][$layer['attributes']['style_attribute'][$j]], 'gle_attribute_value value_'.$layer['layer_id'].'_'.$layer['attributes']['name'][$j]));
										if ($nl){
											$next_row['cells'][] = $cell;
										}
										else{
											$row['cells'][] = $cell;
										}
										unset($cell);
										######### /Attributwert #########
							
										if ($layer['attributes']['arrangement'][$j+1] != 1){		# wenn nächstes Attribut nicht daneben -> Zeile abschliessen
											$table['rows'][] = $row;
											if (count($row['cells']) > value_of($table, 'max_cell_count')) {
												$table['max_cell_count'] = count($row['cells']);
											}
											unset($row);
										}
										if ($layer['attributes']['arrangement'][$j+1] != 1 AND $nl){			# die aufgesammelten Zellen in neuer Zeile ausgeben
											$table['rows'][] = $next_row;
											unset($next_row);
											$nl = false;
										}
						
										if($layer['attributes']['privileg'][$j] >= '0'){
											$this->form_field_names .= $layer['layer_id'].';' . ($layer['attributes']['saveable'][$j]? $layer['attributes']['real_name'][$layer['attributes']['name'][$j]] : '') . ';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|';
										}
									}
								}
								else {
									$this->form_field_names .= $layer['layer_id'].';' . ($layer['attributes']['saveable'][$j]? $layer['attributes']['real_name'][$layer['attributes']['name'][$j]] : '') . ';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].';Geometrie;'.$layer['attributes']['nullable'][$j].'|';
								}
							}
							else{
								$vc_class = '';
								$onchange = '';
								if ($layer['attributes']['dependents'][$j] != NULL) {
									$vc_class = ' visibility_changer';
									$onchange = 'this.oninput();" oninput="check_visibility('.$layer['layer_id'].', this, [\''.implode('\',\'', $layer['attributes']['dependents'][$j]).'\'], '.$k.');';
								}
								$invisible_attributes[$layer['layer_id']][] = '<input type="hidden" id="' . $layer['layer_id'] . '_' . $layer['attributes']['name'][$j] . '_' . $k . '" onchange="'.$onchange.'" class="attr_' . $layer['layer_id'] . '_' . $layer['attributes']['name'][$j] . ' ' . $vc_class . '" name="'.$layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'" readonly="true" value="'.htmlspecialchars($layer['shape'][$k][$layer['attributes']['name'][$j]]).'">';
								$this->form_field_names .= $layer['layer_id'].';' . ($layer['attributes']['saveable'][$j]? $layer['attributes']['real_name'][$layer['attributes']['name'][$j]] : '') . ';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|';
							}
							if (
									$layer['attributes']['group'][$j] != value_of($layer['attributes']['group'], $j+1) or 
									$layer['attributes']['tab'][$j] != $layer['attributes']['tab'][$j+1]
								){		# wenn die nächste Gruppe anders ist, Tabelle schliessen
								echo output_table($table);
								unset($table);
								$table = array();
								echo '</table></div></td></tr>';
							}
						}
						if($table){
							echo output_table($table);
							unset($table);
							$table = '';
						}						
						if ($sachdaten_tab AND $layer['attributes']['group'][0] == '') {
							echo '</tbody></table></td></tr>';
						}
						if ($show_geom_editor) {
							echo '
							<tr class="tab tab_' . $layer['layer_id'] . '_-1_Geometrie ' . $visibility_geom . '">
								<td colspan="2" align="center">';
									include(LAYOUTPATH.'snippets/'.$geomtype.'Editor.php');
							echo '
								</td>
							</tr>';
							if ($this->user->rolle->geom_edit_first) {
								$first_tab = false;
							}
						}
							
							if (($columnname != '' OR $layer['shape'][$k]['wfs_geom'] != '') AND $this->new_entry != true AND value_of($this->formvars, 'printversion') == '') {
								if ($layer['attributes']['group'][0] != '') { ?>
									<tr>
										<td colspan="2">
											<table width="100%" class="tgle" border="0" cellpadding="0" cellspacing="0"><tbody class="gle glegeom"><?
								} ?>
				 
												<tr><? if (value_of($layer, 'querymaps') AND $layer['querymaps'][$k] != ''){ ?>
												<td <? echo ($layer['attributes']['group'][0] != '' ? 'width="203px"' : ''); ?> bgcolor="<? echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;border-right: 1px solid #ccc" align="center"><img style="border:1px solid grey" src="<? echo $layer['querymaps'][$k]; ?>"></td>
									<? } else { ?>
											<td <? if($layer['attributes']['group'][0] != '')echo 'width="203px"'; ?> bgcolor="<? echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;border-right: 1px solid #ccc">&nbsp;</td>
											<? } ?>
											<td class="button_background" style="box-shadow: none; padding: 5px;" valign="middle" colspan="19"><?
												if (!value_of($layer['shape'][$k], 'wfs_geom')) { // kein WFS
													echo '<input type="hidden" id="' . $layer['layer_id'] . '_' . $columnname . '_' . $k . '" class="attr_' . $layer['layer_id'] . '_' . $columnname . '" value="'.$layer['shape'][$k][$columnname].'">'; ?>
													<table cellspacing="0" cellpadding="0">
														<tr><?
															if ($privileg == 1) { ?>
																<td><a onclick="checkForUnsavedChanges(event);" title="<? echo $strEditGeom; ?>" target="root" href="index.php?go=<? echo $geomtype; ?>Editor&oid=<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>&selected_layer_id=<? echo $layer['layer_id'];?>&dimension=<? echo $dimension; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div class="button edit_geom"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td><?
															} 
															if ($layer['shape'][$k][$layer['attributes']['the_geom']] AND $layer['maintable'] != ''){ ?>
																<td>
																	<a title="<? echo $strMapZoom; ?>" href="javascript:zoom2object(<? echo $layer['layer_id'];?>, '<? echo $columnname; ?>', '<? echo $layer['shape'][$k][$layer		['maintable'].'_oid']; ?>', 'zoomonly');"><div class="button zoom_normal"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a>
																</td><?
																if ($layer['layer_id'] > 0){ ?>
																	<td>
																		<a title="<? echo $strMapZoom.$strAndHighlight; ?>" href="javascript:zoom2object(<? echo $layer['layer_id'];?>, '<? echo $columnname; ?>', '<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>', 'false');"><div class="button zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
																	<td>
																		<a title="<? echo $strMapZoom.$strAndHide; ?>" href="javascript:zoom2object(<? echo $layer['layer_id'];?>, '<? echo $columnname; ?>', '<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>', 'true');"><div class="button zoom_select"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a>
																	</td>
																	<td style="position: relative">
																		<a 
																			title="QR-Code" 
																			href="javascript:void(0);" 
																			onmouseenter="get_position_qrcode(<? echo $layer['layer_id']; ?>, '<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>');"
																			onmouseleave="remove_position_qrcode(<? echo $layer['layer_id']; ?>, '<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>');"
																		>
																			<div class="button qr_code"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div>
																		</a>
																		<img id="qr_<? echo $layer['layer_id']; ?>_<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>" src="<? echo GRAPHICSPATH.'leer.gif'; ?>" style="position: absolute; bottom: 0px; left: 40px; box-shadow: 0px 0px 7px rgba(0, 0, 0, 0.4);">
																	</td><?
																}
															} ?>
														</tr>
													</table><?
												}
												else { # bei WFS-Layern ?>
													<table cellspacing="0" cellpadding="0">
														<tr>
															<td style="padding: 0 0 0 5;">
																<a href="javascript:zoom2wkt('<? echo $layer['shape'][$k]['wfs_bbox'] ?: $layer['shape'][$k]['wfs_geom']; ?>', '<? echo $layer['epsg_code']; ?>');"><div class="button zoom_normal"><img	src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a>
															</td>
														</tr>
													</table><?
												} ?>
											</td>
										</tr>
					
								<? if($layer['attributes']['group'][0] != ''){ ?>
											</table></td></tr>
								<? }				
						}

							if($this->new_entry == true){
								echo $datapart;
							}
			 ?>
						</tbody>
							<? if ($this->user->rolle->visually_impaired) include(LAYOUTPATH . 'snippets/generic_layer_editor_2_layer_head.php'); ?>
						</table>
						</div>
						<?
					$layer['attributes']['privileg'] = $definierte_attribute_privileges;
					$privileg = $layer['attributes']['privileg'][$index];
				}
				?>
			<div style="display: none">
				<?
					if (value_of($invisible_attributes, $layer['layer_id'])){
						for ($l = 0; $l < count($invisible_attributes[$layer['layer_id']]); $l++){
							echo $invisible_attributes[$layer['layer_id']][$l]."\n";
						}
					} ?>
			</div>
		</div><?
		if ($dataset_operation_position == 'unten' OR $dataset_operation_position == 'beide') {
			include('dataset_operations.php');
		}
		if (array_key_exists('charts', $layer) AND count($layer['charts']) > 0) {
			include(SNIPPETS . 'layer_charts.php');
		} ?>
		<script type="text/javascript">
			var vchangers = document.getElementById(<? echo $table_id; ?>).querySelectorAll('.visibility_changer');
			[].forEach.call(vchangers, function(vchanger){vchanger.oninput();});
		</script>

		<input type="hidden" name="checkbox_names_<? echo $layer['layer_id']; ?>" value="<? echo $checkbox_names; ?>">
		<input type="hidden" name="orderby<? echo $layer['layer_id']; ?>" id="orderby<? echo $layer['layer_id']; ?>" value="<? echo value_of($this->formvars, 'orderby'.$layer['layer_id']); ?>">
		<input type="hidden" id="geom_privileg_<? echo $layer['layer_id']; ?>" value="<? echo $privileg; ?>">
	</div><?
}
elseif ($layer['requires'] == '') {
	$this->noMatchLayers[$layer['layer_id']] = $layer_name;
} ?>