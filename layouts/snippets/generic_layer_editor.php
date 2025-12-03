<script>

	function toggleColumn(checkbox, layer_id, attribute_name){
		var head = document.getElementById('column_' + layer_id + '_' + attribute_name);
		var group = head.classList[1].substring(6);
		if (document.getElementById(group)) {
			if (checkbox.checked) {
				document.getElementById(group).dataset.colspan = parseInt(document.getElementById(group).dataset.colspan) + 1;
				if (document.getElementById(group).dataset.colspan == 1) {
					var gap_elements = document.querySelectorAll('.gap_' + group);
					[].forEach.call(gap_elements, function (gap_element){
						gap_element.colSpan = 1;		// Leerspalte zwischen den Gruppen verkleinern
					});
				}
			}
			else {
				document.getElementById(group).dataset.colspan -= 1;
				if (document.getElementById(group).dataset.colspan == 0) {
					var gap_elements = document.querySelectorAll('.gap_' + group);
					[].forEach.call(gap_elements, function (gap_element){
						gap_element.colSpan = 2;		// Leerspalte zwischen den Gruppen verbreitern
					});
				}
			}
			document.getElementById(group).colSpan = document.getElementById(group).dataset.colspan;		// weil colSpan nicht 0 sein kann
		}
		head.classList.toggle('hidden');
		tds = document.querySelectorAll('.value_' + layer_id + '_' + attribute_name);
		[].forEach.call(tds, function (td){
			td.classList.toggle('hidden');
		});
	}
	
	function toggleAll(checkbox, layer_id){
		var heads = document.querySelectorAll('.column_head_' + layer_id);
		[].forEach.call(heads, function (head){
			var group = head.classList[1].substring(6);
			if (checkbox.checked) {
				head.classList.remove('hidden');
				if (document.getElementById(group)) {
					document.getElementById(group).dataset.colspan = parseInt(document.getElementById(group).dataset.colspan) + 1;
					var gap_elements = document.querySelectorAll('.gap_' + group);
					[].forEach.call(gap_elements, function (gap_element){
						gap_element.colSpan = 1;		// Leerspalte zwischen den Gruppen verkleinern
					});
				}
			}
			else {
				head.classList.add('hidden');
				if (document.getElementById(group)) {
					document.getElementById(group).dataset.colspan -= 1;
					var gap_elements = document.querySelectorAll('.gap_' + group);
					[].forEach.call(gap_elements, function (gap_element){
						gap_element.colSpan = 2;		// Leerspalte zwischen den Gruppen verbreitern
					});
				}
			}
			document.getElementById(group).colSpan = document.getElementById(group).dataset.colspan;		// weil colSpan nicht 0 sein kann
		});
		
		var tds = document.querySelectorAll('.gle_attribute_value');
		[].forEach.call(tds, function (td){
			if (checkbox.checked) {
				td.classList.remove('hidden');
			}
			else {
				td.classList.add('hidden');
			}
		});
		
		var checkboxes = document.querySelectorAll('#gle_column_options_div input');
		[].forEach.call(checkboxes, function (cb){
			cb.checked = checkbox.checked;
		});
	}	
	
</script>

<?
include_once(LAYOUTPATH.'languages/generic_layer_editor_2_'.rolle::$language.'.php');
$checkbox_names = '';
$columnname = '';
$geom_tablename = '';
$geomtype = '';
$dimension = '';
$privileg = '';
# Variablensubstitution
$dataset_operation_position = $this->user->rolle->dataset_operations_position;
$layer = $this->qlayerset[$i];
if (!$invisible_attributes) {
	$invisible_attributes = array();
	$invisible_attributes[$layer['layer_id']] = array();
};
$size = 16;
$select_width = 'width: 100%;'; 
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
if ($this->new_entry == true) {
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
	<div id="layer" class="gle_tabular" onclick="remove_calendar();">
		<input type="hidden" value="" id="changed_<? echo $layer['layer_id']; ?>" name="changed_<? echo $layer['layer_id']; ?>"><?
		if ($this->new_entry != true AND $layer['requires'] == '') { ?>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<? if (!$this->user->rolle->visually_impaired AND $anzObj > 0) { ?>
					<td align="right" valign="top" style="padding: 0 10 0 0">
					<?	
						if($this->search == true AND $this->formvars['printversion'] == '' AND $this->formvars['keinzurueck'] == '' AND $this->formvars['subform_link'] == ''){
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
							echo '<a href="'.strip_pg_escape_string($backlink).'" target="' . $target . '" title="'.$strbackToSearch.'"><i class="fa fa-arrow-left hover-border" aria-hidden="true"></i></a>';
						} ?>
					</td>
					<td align="right" valign="top" style="padding: 0 10 0 0">
					</td>
					<td valign="top" style="padding: 0 0 0 0">
					</td>
					<? } ?>
					<td height="30" width="99%" align="center">
					<? if (!$this->user->rolle->visually_impaired AND $anzObj > 0) { ?>
					<td valign="top" style="padding: 0 10 0 0">
					</td>
					<td align="right" valign="top">
					</td>
					<? } ?>
				</tr>
			</table><?
		} 
		$table_id = rand(0, 100000); ?>
		<div style="display: flex; justify-content: space-between;">
			<div style="position: sticky; left: calc(50% - 225px); min-width: 450px">
				<h2 id="layername"><? echo $layer_name; ?></h2><?
				echo $layer['paging']; ?>
			</div>
			<div style="position: sticky; display: flex; right: 5px;  z-index: 1000;">
				<div class="gle-view">	<?
					if ($layer['template'] == '') {
						for ($g = 0; $g < 3; $g++) {
							echo '<img onclick="checkForUnsavedChanges(event);switch_gle_view1(' . $layer['layer_id'] . ', ' . $layer['gle_view'] . ', ' . $g . ', this);" title="' . ${'strSwitchGLEView' . $g} . '" class="hover-border pointer gle-view-button ' . ($layer['gle_view'] == $g? 'active':'') . '" src="' . GRAPHICSPATH . 'gle' . $g . '.png">';
						}
					}	?>
				</div>
				<i id="column_options_button" class="fa fa-columns" aria-hidden="true" onclick="document.getElementById('gle_column_options_div').classList.toggle('hidden')"></i>
				<div id="gle_column_options_div" class="hidden" onmouseleave="this.classList.toggle('hidden');">
					<input type="checkbox" onclick="toggleAll(this, <? echo $layer['layer_id']; ?>, 'alle');" checked> --alle--<br>
	<? 			for ($j = 0; $j < count($layer['attributes']['name']); $j++) {
						if ($layer['attributes']['visible'][$j]) { ?>					
							<input type="checkbox" onclick="toggleColumn(this, <? echo $layer['layer_id']; ?>, '<? echo $layer['attributes']['name'][$j]; ?>');" checked> <? echo ($layer['attributes']['alias'][$j] ?: $layer['attributes']['name'][$j]) . '<br>'; 
						}
					}	?>
				</div>
			</div>
		</div>	<?
		if ($dataset_operation_position == 'oben' OR $dataset_operation_position == 'beide') {
			include('dataset_operations.php');
		} ?>
		<table id="<? echo $table_id; ?>" border="0" cellspacing="1" cellpadding="2" width="100%">
			<tr>
				<td width="100%">   
					<table class="gle1_table" cellspacing="0" cellpadding="0" width="100%">
						<thead>
						<? # Gruppennamen
							if($layer['attributes']['group'][0] != ''){
								echo '<tr><td style="border:none"></td><td style="border:none"></td>';
								$explosion = explode(';', $layer['attributes']['group'][0]);
								$groupname = str_replace(' ', '-', $explosion[0]) . '_' . $layer['layer_id'];
								echo '<td id="'.$groupname.'" style="background: '.BG_GLEATTRIBUTE.'" colspan="';								
								$colspan = 0;
								for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
									if($layer['attributes']['visible'][$j] AND $layer['attributes']['SubFormFK_hidden'][$j] != 1)$colspan++;
									if($layer['attributes']['group'][$j] != $layer['attributes']['group'][$j+1]){
										$explosion = explode(';', $layer['attributes']['group'][$j]);
										if($explosion[1] == 'collapsed')$collapsed = true;else $collapsed = false;
										$groupname = str_replace(' ', '-', $explosion[0]) . '_' . $layer['layer_id'];
										if($collapsed)echo '1';
										else echo $colspan;
										echo '" data-colspan="'.$colspan.'" data-origcolspan="'.$colspan.'">';
										echo '&nbsp;<a href="javascript:void(0);" onclick="toggleGroup(\''.$groupname.'\')"><img id="img_'.$groupname.'" border="0" src="graphics/'.($collapsed ? 'plus' : 'minus').'.gif"></a>&nbsp;<span>'.$explosion[0].'</span></td><td style="border:none;background: url('.BG_IMAGE.');"></td>';
										$colspan = 0;
										if($layer['attributes']['SubFormFK_hidden'][$j] != 1){
											#$colspan = 1;
										}
										if($j < count($this->qlayerset[$i]['attributes']['name'])-1){
											$explosion = explode(';', $layer['attributes']['group'][$j+1]);
											$groupname = str_replace(' ', '-', $explosion[0]) . '_' . $layer['layer_id'];
											echo '<td id="'.$groupname.'" style="background: '.BG_GLEATTRIBUTE.'" colspan="';								
										}
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
								$explosion = explode(';', $layer['attributes']['group'][$j]);
								$collapsed = ($explosion[1] == 'collapsed');
								$groupname = str_replace(' ', '-', $explosion[0]) . '_' . $layer['layer_id'];
								if($layer['attributes']['group'][$j] != $layer['attributes']['group'][$j-1]){		# wenn die vorige Gruppe anders ist, Leerspalte einfügen
									echo '<td class="gap_'.$groupname.'" '.($collapsed? 'colspan="2"' : '').' style="border:none;background: url('.BG_IMAGE.');"></td>';
								}
								if($layer['attributes']['visible'][$j] AND $layer['attributes']['name'][$j] != 'lock'){
									if($this->qlayerset[$i]['attributes']['type'][$j] != 'geometry'){
										if($layer['attributes']['SubFormFK_hidden'][$j] != 1){
											echo '<td style="' . ($collapsed ? 'display: none;' : '') . 'position: relative; background-clip: padding-box;" id="column_' . $layer['layer_id'] . '_' . $layer['attributes']['name'][$j] . '" class="column_head_'. $layer['layer_id'] . ' group_'.$groupname.'"';
											echo ' valign="top" bgcolor="'.BG_GLEATTRIBUTE.'">';									
											if($layer['attributes']['privileg'][$j] != '0' AND !$lock[$k]){
												$this->editable = $layer['layer_id'];
											}
											if($layer['attributes']['alias'][$j] == ''){
												$layer['attributes']['alias'][$j] = $layer['attributes']['name'][$j];
											}
											echo '<table ';
											echo 'width="100%"';
											echo '><tr><td>';
											if($this->formvars['printversion'] == '' AND $layer['attributes']['form_element_type'][$j] != 'SubFormPK' AND $layer['attributes']['form_element_type'][$j] != 'SubFormEmbeddedPK'){
												echo '<a title="Sortieren nach '.$layer['attributes']['alias'][$j].'" href="javascript:change_orderby(\''.$layer['attributes']['name'][$j].'\', '.$layer['layer_id'].');">
																'.$layer['attributes']['alias'][$j].'</a>';
											}
											else{
												echo '<span style="color:#222222;">'.$layer['attributes']['alias'][$j].'</span>';
											}
											if($layer['attributes']['nullable'][$j] == '0' AND $layer['attributes']['privileg'][$j] != '0'){
												echo '<span title="Eingabe erforderlich">*</span>';
											}
											echo attribute_tooltip($layer['attributes'], $j);

											if($layer['attributes']['type'][$j] == 'date' OR $layer['attributes']['type'][$j] == 'timestamp' OR $layer['attributes']['type'][$j] == 'time'){
												echo '<td align="right"><a href="javascript:;" title="(TT.MM.JJJJ)"><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a><div id="calendar"><input type="hidden" id=calendar_'.$layer['attributes']['name'][$j].'_'.$k.'"></div></td>';
											}
											echo '</td>';
											echo '<td><div onmousedown="resizestart(document.getElementById(\'column_' . $layer['layer_id'] . '_' . $layer['attributes']['name'][$j] . '\'), \'col_resize\');" style="transform: translate(8px); float: right; right: 0px; height: 20px; width: 6px; cursor: e-resize;"></div></td>';
											echo '</tr></table>';
											echo '</td>';
										}
									}
									else{
										$has_geom = true;
									}
								}
					  	}
							if($has_geom)echo '<td bgcolor="'.BG_GLEATTRIBUTE.'">&nbsp;</td>';
					  ?>
					  </tr>
					</thead>
					<tbody>
		<?
			for ($k; $k<$anzObj; $k++) {
				$definierte_attribute_privileges = $layer['attributes']['privileg'];		// hier sichern und am Ende des Datensatzes wieder herstellen
				if (is_array($layer['attributes']['privileg'])) {
					if ($layer['shape'][$k][$layer['attributes']['Editiersperre']] == 't') {
						$layer['attributes']['privileg'] = array_map(function($attribut_privileg) { return 0; }, $layer['attributes']['privileg']);
					}
				}
				$checkbox_names .= 'check;'.$layer['attributes']['table_alias_name'][$layer['maintable']].';'.$layer['maintable'].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].';'.$layer['layer_id'].'|';
		?>
			<tr 
			<? if ($this->user->rolle->tooltipquery == 1 AND $this->user->rolle->querymode == 1 AND $layer['attributes']['the_geom'] != '') { ?>
				onmouseenter="highlight_object(<? echo $layer['layer_id']; ?>, '<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>');"
			<? } ?>
			>
				<td style="background-color:<? echo BG_DEFAULT; ?>;">
				  <? if($this->new_entry != true AND $this->formvars['printversion'] == ''){ ?>
				  <table>
						<tr>
							<td style="line-height: 1px; ">
								<a name="anchor_<? echo $layer['layer_id']; ?>_<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>">
								<input type="hidden" value="" onchange="changed_<? echo $layer['layer_id']; ?>.value=this.value;root.document.GUI.gle_changed.value=this.value" name="changed_<? echo $layer['layer_id'].'_'.str_replace('-', '', $layer['shape'][$k][$layer['maintable'].'_oid']); ?>"> 
								<input id="<? echo $layer['layer_id'].'_'.$k; ?>" type="checkbox" onchange="count_selected(<? echo $layer['layer_id']; ?>);" class="check_<? echo $layer['layer_id']; ?> <? if ($layer['shape'][$k][$layer['attributes']['Editiersperre']] == 't')echo 'no_edit'; ?>" name="check;<? echo $layer['attributes']['table_alias_name'][$layer['maintable']].';'.$layer['maintable'].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].';'.$layer['layer_id']; ?>">&nbsp;
							</td>
						</tr>
				  </table>
				  <? } ?>
			</td>

		<?

				for($j = 0; $j < count($layer['attributes']['name']); $j++){
					$explosion = explode(';', $layer['attributes']['group'][$j]);
					if($explosion[1] == 'collapsed')$collapsed = true;else $collapsed = false;
					$groupname = str_replace(' ', '-', $explosion[0]) . '_' . $layer['layer_id'];
					if($layer['attributes']['group'][$j] != $layer['attributes']['group'][$j-1]){		# wenn die vorige Gruppe anders ist, Leerspalte einfügen
						echo '<td class="gap_'.$groupname.'" '.($collapsed? 'colspan="2"' : '').' style="border:none;background: url('.BG_IMAGE.');"></td>';
					}
					// if(($layer['attributes']['privileg'][$j] == '0' AND $layer['attributes']['form_element_type'][$j] == 'Auswahlfeld') OR ($layer['attributes']['form_element_type'][$j] == 'Text' AND $layer['attributes']['saveable'][$j] == '0')){				# entweder ist es ein nicht speicherbares Attribut oder ein nur lesbares Auswahlfeld, dann ist es auch nicht speicherbar
						// $layer['attributes']['form_element_type'][$j] .= '_not_saveable';
					// }
					if($layer['attributes']['visible'][$j]){
						if($layer['attributes']['type'][$j] != 'geometry') {
							if($layer['attributes']['SubFormFK_hidden'][$j] != 1){
								echo '<td id="value_'.$layer['layer_id'].'_'.$layer['attributes']['name'][$j].'_'.$k.'" '.get_td_class_or_style(array('gle_attribute_value group_'.$groupname.' value_'.$layer['layer_id'].'_'.$layer['attributes']['name'][$j], $layer['shape'][$k][$layer['attributes']['style_attribute'][$j]], 'position: relative; text-align: left'.($collapsed ? ';display: none' : ''))) . '>';
								if(in_array($layer['attributes']['type'][$j], array('date', 'time', 'timestamp'))){
									echo calendar($layer['attributes']['type'][$j], $layer['layer_id'].'_'.$layer['attributes']['name'][$j].'_'.$k, $layer['attributes']['privileg'][$j]);
								}
								echo attribute_value($this, $layer, NULL, $j, $k, NULL, $size, $select_width);
								echo '<div onmousedown="resizestart(document.getElementById(\'column_' . $layer['layer_id'] . '_' . $layer['attributes']['name'][$j] . '\'), \'col_resize\');" style="position: absolute; transform: translate(4px); top: 0px; right: 0px; height: 100%; width: 8px; cursor: e-resize;"></div>';
								echo '</td>';
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
						$invisible_attributes[$layer['layer_id']][] = '<input type="hidden" readonly="true" name="'.$layer['layer_id'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'" value="'.htmlspecialchars($layer['shape'][$k][$layer['attributes']['name'][$j]]).'">';
						$this->form_field_names .= $layer['layer_id'].';' . ($layer['attributes']['saveable'][$j]? $layer['attributes']['real_name'][$layer['attributes']['name'][$j]] : '') . ';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].';'.$layer['attributes']['saveable'][$j].'|';
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
												<td style="padding: 0 0 0 10;"><a onclick="checkForUnsavedChanges(event);" title="<? echo $strEditGeom; ?>" target="root" href="index.php?go=<? echo $geomtype; ?>Editor&oid=<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>&layer_tablename=<? echo $geom_tablename; ?>&layer_columnname=<? echo $columnname; ?>&selected_layer_id=<? echo $layer['layer_id'];?>&dimension=<? echo $dimension; ?>"><div class="button edit_geom"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
		<?								} 
										if($layer['shape'][$k][$layer['attributes']['the_geom']]){ ?>
												<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom; ?>" href="javascript:zoom2object(<? echo $layer['layer_id'];?>, '<? echo $columnname; ?>', '<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>', 'zoomonly');"><div class="button zoom_normal"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
										<? if($layer['layer_id'] > 0){ ?>
												<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom.$strAndHighlight; ?>" href="javascript:zoom2object(<? echo $layer['layer_id'];?>, '<? echo $columnname; ?>', '<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>', 'false');"><div class="button zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
												<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom.$strAndHide; ?>" href="javascript:zoom2object(<? echo $layer['layer_id'];?>, '<? echo $columnname; ?>', '<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>', 'true');"><div class="button zoom_select"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
										<? }} ?>
											</tr>
										</table>
		<?
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
				<? 	} ?>
						</tr>
		<?		$layer['attributes']['privileg'] = $definierte_attribute_privileges;
				} ?>
						<tr onclick="toggle_statistic_row(<? echo $layer['layer_id']; ?>);">
							<td style="background-color:<? echo BG_TR; ?>;" valign="top" align="center">
								&Sigma;
							</td><?
							for ($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
								$explosion = explode(';', $layer['attributes']['group'][$j]);
								if($explosion[1] == 'collapsed')$collapsed = true;else $collapsed = false;
								$groupname = str_replace(' ', '-', $explosion[0]) . '_' . $layer['layer_id'];
								if($layer['attributes']['group'][$j] != $layer['attributes']['group'][$j-1]){		# wenn die vorige Gruppe anders ist, Leerspalte einfügen
									echo '<td class="gap_'.$groupname.'" '.($collapsed? 'colspan="2"' : '').' style="border:none;background: url('.BG_IMAGE.');"></td>';
								}
								if($layer['attributes']['type'][$j] != 'geometry' AND $layer['attributes']['visible'][$j] AND $layer['attributes']['SubFormFK_hidden'][$j] != 1){ ?>
									<td valign="top" class="group_<? echo $groupname; ?>" <? if($collapsed)echo 'style="display: none"'; ?> >
										<div class="statistic_row_<? echo $layer['layer_id']; ?>" style="display:none"><?php
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
											#if ($summe > 0) {
												output_statistic($statistic);
											#}
										} ?></div>
									</td><?
								}
							} ?>
						</tr>

						<tr class="result_filter_tr">
							<td style="border: none; padding: 0" <? if ($layer['attributes']['group'][0] != ''){echo 'colspan="2"';} ?>></td>
							<?
							for ($j = 0; $j < count($layer['attributes']['name']); $j++){
								if ($layer['attributes']['type'][$j] != 'geometry' AND $layer['attributes']['visible'][$j] AND $layer['attributes']['SubFormFK_hidden'][$j] != 1) {
									$column_name = $this->qlayerset[$i]['attributes']['name'][$j]; ?>
									<td style="border: none; position: relative; padding: 0">
										<div id="result_filter_<? echo $layer['layer_id'] . '_' . $column_name; ?>" class="gle_result_filter">
											<? 
											if (!empty($this->result_values[$layer['layer_id']][$column_name])) {
												natsort($this->result_values[$layer['layer_id']][$column_name]);
												echo '<i class="fa fa-filter" aria-hidden="true" style="color: #bfbfbf"></i>
															<select multiple="true" class="value_list" style="height: ' . (((count($this->result_values[$layer['layer_id']][$column_name]) + 1) * 22) + 6) . 'px;" onchange="filter_results(\'attr_' . $layer['layer_id'] . '_' . $column_name . '\', this)">
																<option value="#all#">alle</option>';
												foreach ($this->result_values[$layer['layer_id']][$column_name] as $value => $output) {
													echo '<option value="' . $value . '">' . $output . '</option>';
												}
												echo '</select>';
											} ?>
										</div>
									</td><?
								}
							} ?>
						</tr>

		<?
					if($this->new_entry != true AND $this->editable == $layer['layer_id']){
		?>
						<tr id="edit_all1_<? echo $layer['layer_id']; ?>" style="height: 30px">
							<td colspan="200" style="border: none;vertical-align: bottom"><a href="javascript:switch_edit_all(<? echo $layer['layer_id']; ?>);"><? echo $strEditAll; ?></a></td>
						</tr>
						<tr id="edit_all2_<? echo $layer['layer_id']; ?>" style="height: 30px; display: none">
							<td colspan="200" style="border: none;vertical-align: bottom"><a href="javascript:switch_edit_all(<? echo $layer['layer_id']; ?>);"><? echo $strEditAll; ?></a></td>
						</tr>
						<tr id="edit_all3_<? echo $layer['layer_id']; ?>" bgcolor="<?php echo BG_DEFAULT ?>" style="display: none">
						<td></td>
					  <?
					  	for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
								$explosion = explode(';', $layer['attributes']['group'][$j]);
								if($explosion[1] == 'collapsed')$collapsed = true;else $collapsed = false;
								$groupname = str_replace(' ', '-', $explosion[0]) . '_' . $layer['layer_id'];
								if($layer['attributes']['group'][$j] != $layer['attributes']['group'][$j-1]){		# wenn die vorige Gruppe anders ist, Leerspalte einfügen
									echo '<td class="gap_'.$groupname.'" '.($collapsed? 'colspan="2"' : '').' style="border:none;background: url('.BG_IMAGE.');"></td>';
								}
								if($layer['attributes']['visible'][$j] AND $layer['attributes']['name'][$j] != 'lock'){
									if($this->qlayerset[$i]['attributes']['type'][$j] != 'geometry'){
										if($layer['attributes']['SubFormFK_hidden'][$j] != 1){
											echo '<td class="group_'.$groupname.'"';
											if($collapsed)echo 'style="display: none"';
											echo ' valign="top" bgcolor="'.BG_GLEATTRIBUTE.'">';
											if($layer['attributes']['alias'][$j] == ''){
												$layer['attributes']['alias'][$j] = $layer['attributes']['name'][$j];
											}
											echo '<table ';
											echo 'width="100%";';
											echo '><tr><td>';
											echo '<span style="color:#222222;">'.$layer['attributes']['alias'][$j].'</span>';
											if($layer['attributes']['nullable'][$j] == '0' AND $layer['attributes']['privileg'][$j] != '0'){
												echo '<span title="Eingabe erforderlich">*</span>';
											}
											if($layer['attributes']['tooltip'][$j]!='' AND $layer['attributes']['form_element_type'][$j] != 'Time'){
												echo '<td align="right"><a href="javascript:void(0);" title="'.$layer['attributes']['tooltip'][$j].'"><img src="'.GRAPHICSPATH.'emblem-important.png" border="0"></a></td>';
											}
											if($layer['attributes']['type'][$j] == 'date' OR $layer['attributes']['type'][$j] == 'timestamp' OR $layer['attributes']['type'][$j] == 'timestamptz'){
												echo '<td align="right"><a href="javascript:;" title=" (TT.MM.JJJJ) '.$layer['attributes']['tooltip'][$j].'" ';
												if($layer['attributes']['privileg'][$j] == '1' AND !$lock[$k]){
													echo 'onclick="add_calendar(event, \''.$layer['attributes']['name'][$j].'_'.$k.'\');"';
												}
												echo '><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a><div id="calendar"><input type="hidden" id=calendar_'.$layer['attributes']['name'][$j].'_'.$k.'"></div></td>';
											}
											echo '</td></tr></table>';
											echo '</td>';
										}
									}
								}
					  	}
					  ?>
					  </tr>

						<tr id="edit_all4_<? echo $layer['layer_id']; ?>" style="display: none">
							<td style="text-align: center; background-color:<? echo BG_DEFAULT; ?>;">
								<span style="--left: 0px" data-tooltip="Sie können hier die Attribut-Werte von mehreren Datensätzen gleichzeitig bearbeiten. Die Werte werden nur für die ausgewählten Datensätze übernommen."></span>
							</td>
							<?					
								for($j = 0; $j < count($layer['attributes']['name']); $j++){
									$explosion = explode(';', $layer['attributes']['group'][$j]);
									if($explosion[1] == 'collapsed')$collapsed = true;else $collapsed = false;
									$groupname = str_replace(' ', '-', $explosion[0]) . '_' . $layer['layer_id'];
									if($layer['attributes']['group'][$j] != $layer['attributes']['group'][$j-1]){		# wenn die vorige Gruppe anders ist, Leerspalte einfügen
										echo '<td class="gap_'.$groupname.'" '.($collapsed? 'colspan="2"' : '').' style="border:none;background: url('.BG_IMAGE.');"></td>';
									}
									if(($layer['attributes']['privileg'][$j] == '0' AND $layer['attributes']['form_element_type'][$j] == 'Auswahlfeld') OR ($layer['attributes']['form_element_type'][$j] == 'Text' AND $layer['attributes']['saveable'][$j] == '0')){				# entweder ist es ein nicht speicherbares Attribut oder ein nur lesbares Auswahlfeld, dann ist es auch nicht speicherbar
										$layer['attributes']['form_element_type'][$j] .= '_not_saveable';
									}
									if($layer['attributes']['visible'][$j] AND $layer['attributes']['name'][$j] != 'lock'){
										if($layer['attributes']['type'][$j] != 'geometry'){
											if($layer['attributes']['SubFormFK_hidden'][$j] != 1){
												echo '<td class="group_'.$groupname.'" '.($collapsed? 'style="display: none"' : '').'>';
												if(!in_array($layer['attributes']['form_element_type'][$j], array('Dokument', 'SubFormPK', 'SubFormEmbeddedPK'))){
													if(in_array($layer['attributes']['type'][$j], array('date', 'time', 'timestamp'))){
														echo calendar($layer['attributes']['type'][$j], $layer['layer_id'].'_'.$layer['attributes']['name'][$j].'_'.$k, $layer['attributes']['privileg'][$j]);
													}
													echo attribute_value($this, $layer, NULL, $j, $k, NULL, $size, $select_width, true);
												}
												echo '</td>';
											}
										}
									}
								}
							?>
						</tr>
			
			<?  } ?>
						</tbody>
					</table>
				</td>
			</tr>
		</table>
		<?
			if ($dataset_operation_position == 'unten' OR $dataset_operation_position == 'beide') {
				include('dataset_operations.php');
			} ?>
		<?
		for ($l = 0; $l < count_or_0($invisible_attributes[$layer['layer_id']]); $l++) {
			echo $invisible_attributes[$layer['layer_id']][$l]."\n";
		} ?>
		<script type="text/javascript">
			var filters = document.querySelectorAll('.gle_result_filter');
			[].forEach.call(filters, function(filter){
				var column_id = filter.id.replace('result_filter', 'column');
				filter.parentNode.removeChild(filter);
				document.getElementById(column_id).appendChild(filter);
			});			

			var vchangers = document.getElementById(<? echo $table_id; ?>).querySelectorAll('.visibility_changer');
			[].forEach.call(vchangers, function(vchanger){if(vchanger.oninput)vchanger.oninput();});
		</script>

		<input type="hidden" name="checkbox_names_<? echo $layer['layer_id']; ?>" value="<? echo $checkbox_names; ?>">
		<input type="hidden" name="orderby<? echo $layer['layer_id']; ?>" id="orderby<? echo $layer['layer_id']; ?>" value="<? echo $this->formvars['orderby'.$layer['layer_id']]; ?>">
	</div><?
}
elseif($layer['requires'] == ''){
	$this->noMatchLayers[$layer['layer_id']] = $layer_name;
} ?>