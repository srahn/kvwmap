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
	if($this->currentform == 'document.GUI2')$size = 40;
	else $size = 61;
	$linksize = $this->user->rolle->fontsize_gle - 1;
	$select_width = ''; 
	if($layer['alias'] != '' AND $this->Stelle->useLayerAliases){
		$layer['Name'] = $layer['alias'];
	}
?>
<div id="layer" onclick="remove_calendar();">
<? if($this->new_entry != true AND $layer['requires'] == ''){ ?>
<input type="hidden" value="" id="changed_<? echo $layer['Layer_ID']; ?>" name="changed_<? echo $layer['Layer_ID']; ?>">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="99%" align="center"><h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $layer['Name']; ?></h2></td>
    <? if (!$this->user->rolle->visually_impaired) { ?>
			<td valign="top" style="padding: 0 10 0 0">
				<? if($layer['template'] == '' OR $layer['template'] == 'generic_layer_editor_2.php'){ ?>
				<a href="javascript:switch_gle_view(<? echo $layer['Layer_ID']; ?>);"><img title="<? echo $strSwitchGLEViewColumns; ?>" class="hover-border" src="<? echo GRAPHICSPATH.'columns.png'; ?>"></a>
				<? }else{ ?>
				<a href="javascript:switch_gle_view(<? echo $layer['Layer_ID']; ?>);"><img title="<? echo $strSwitchGLEViewRows; ?>" class="hover-border" src="<? echo GRAPHICSPATH.'rows.png'; ?>"></a>
				<? } ?>
			</td>
			<td align="right">			
				<a href="javascript:scrollbottom();"><img class="hover-border" title="nach unten" src="<? echo GRAPHICSPATH; ?>pfeil.gif" width="11" height="11" border="0"></a>&nbsp;
			</td>
		<? } ?>
	</tr>
</table>
<? }

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
  if($doit == true){
?>
<table border="0" cellspacing="0" cellpadding="2">
<?
	for ($k=0;$k<$anzObj;$k++) {
		$datapart = '';
		$checkbox_names .= 'check;'.$attributes['table_alias_name'][$layer['maintable']].';'.$layer['maintable'].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].'|';
?>
	<tr>
	  <td>
			<img height="7" src="<? echo GRAPHICSPATH ?>leer.gif">
	    <div id="datensatz" 
			<? if($this->new_entry != true AND $this->user->rolle->querymode == 1){ ?>
			onmouseenter="if(typeof FormData !== 'undefined')ahah('index.php', 'go=tooltip_query&querylayer_id=<? echo $layer['Layer_ID']; ?>&oid=<? echo $layer['shape'][$k][$attributes['table_name'][$attributes['the_geom']].'_oid']; ?>', new Array(top.document.GUI.result, ''), new Array('setvalue', 'execute_function'));"
			<? } ?>
			>
	    <input type="hidden" value="" onchange="changed_<? echo $layer['Layer_ID']; ?>.value=this.value" name="changed_<? echo $layer['Layer_ID'].'_'.$layer['shape'][$k][$layer['maintable'].'_oid']; ?>"> 
	    <table id="dstable" class="tgle" <? if($attributes['group'][0] != ''){echo 'border="0" cellpadding="6" cellspacing="0"';}else{echo 'border="1"';} ?>>
				<? if (!$this->user->rolle->visually_impaired) include(LAYOUTPATH . 'snippets/generic_layer_editor_2_layer_head.php'); ?>
        <tbody <? if($attributes['group'][0] == '')echo 'class="gle"'; ?>>
<?		$trans_oid = explode('|', $layer['shape'][$k]['lock']);
			if($layer['shape'][$k]['lock'] == 'bereits übertragen' OR $trans_oid[1] != '' AND $layer['shape'][$k][$layer['maintable'].'_oid'] == $trans_oid[1]){
				echo '<tr><td colspan="2" align="center"><span class="red">Dieser Datensatz wurde bereits übertragen und kann nicht bearbeitet werden.</span></td></tr>';
				$lock[$k] = true;
			}
						
			for($j = 0; $j < count($attributes['name']); $j++){
				if($layer['shape'][$k][$attributes['name'][$j]] == ''){
					#$layer['shape'][$k][$attributes['name'][$j]] = $this->formvars[$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j]];
				}
				if(($attributes['privileg'][$j] == '0' AND $attributes['form_element_type'][$j] == 'Auswahlfeld') OR ($attributes['form_element_type'][$j] == 'Text' AND $attributes['type'][$j] == 'not_saveable')){				# entweder ist es ein nicht speicherbares Attribut oder ein nur lesbares Auswahlfeld, dann ist es auch nicht speicherbar
					$attributes['form_element_type'][$j] .= '_not_saveable';
				}
				
				if($attributes['group'][$j] != $attributes['group'][$j-1]){		# wenn die vorige Gruppe anders ist, Tabelle beginnen
					$explosion = explode(';', $attributes['group'][$j]);
					if($explosion[1] != '')$collapsed = true;else $collapsed = false;
					$groupname = $explosion[0];
					$datapart .= '<tr>
									<td colspan="2" width="100%">
										<table width="100%" id="colgroup'.$layer['Layer_ID'].'_'.$j.'_'.$k.'" class="tgle" '; if(!$collapsed)$datapart .= 'style="display:none"'; $datapart .= ' border="2"><tbody width="100%" class="gle">
											<tr>
												<td width="100%" bgcolor="'.BG_GLEATTRIBUTE.'" colspan="2">&nbsp;<a href="javascript:void(0);" onclick="javascript:document.getElementById(\'group'.$layer['Layer_ID'].'_'.$j.'_'.$k.'\').style.display=\'\';document.getElementById(\'colgroup'.$layer['Layer_ID'].'_'.$j.'_'.$k.'\').style.display=\'none\';"><img border="0" src="'.GRAPHICSPATH.'/plus.gif"></a>&nbsp;&nbsp;<span class="fett">'.$groupname.'</span></td>
											</tr>
										</table>
										<table width="100%" class="tgle" id="group'.$layer['Layer_ID'].'_'.$j.'_'.$k.'" '; if($collapsed)$datapart .= 'style="display:none"'; $datapart .= 'border="2"><tbody class="gle">
											<tr>
												<td bgcolor="'.BG_GLEATTRIBUTE.'" colspan="40">&nbsp;<a href="javascript:void(0);" onclick="javascript:document.getElementById(\'group'.$layer['Layer_ID'].'_'.$j.'_'.$k.'\').style.display=\'none\';document.getElementById(\'colgroup'.$layer['Layer_ID'].'_'.$j.'_'.$k.'\').style.display=\'\';"><img border="0" src="'.GRAPHICSPATH.'/minus.gif"></a>&nbsp;&nbsp;<span class="fett">'.$groupname.'</span></td>
											</tr>';
				}
				
				if($attributes['invisible'][$attributes['name'][$j]] != 'true' AND $attributes['name'][$j] != 'lock'){
					if($attributes['type'][$j] != 'geometry'){
						if($attributes['privileg'][$j] != '0' AND !$lock[$k])$this->editable = $layer['Layer_ID'];
						if($attributes['alias'][$j] == '')$attributes['alias'][$j] = $attributes['name'][$j];
						
						if($attributes['arrangement'][$j] != 1)$datapart .= '<tr>';							# wenn Attribut nicht daneben -> neue Zeile beginnen
						if($attributes['labeling'][$j] != 2){
							$td = '	<td class="gle_attribute_name" '; if($attributes['labeling'][$j] == 1 AND $attributes['arrangement'][$j] == 1 AND $attributes['arrangement'][$j+1] != 1)$td .= 'colspan="20" ';if($attributes['group'][0] != '' AND $attributes['arrangement'][$j] != 1)$td .= 'width="10%">';else $td.='width="1%">';
							$td.= 			attribute_name($layer['Layer_ID'], $attributes, $j, $k, $this->user->rolle->fontsize_gle);
							$td.= '	</td>';
							if($nl AND $attributes['labeling'][$j] != 1)$next_line .= $td; else $datapart .= $td;
						}
						if($attributes['labeling'][$j] == 1)$nl = true;										# Attributname soll oben stehen -> alle weiteren tds für die nächste Zeile aufsammeln
						$td = '	<td class="gle_attribute_value"'; if($attributes['arrangement'][$j+1] != 1)$td .= 'colspan="20"'; $td .= '>';												
						$td.= 			attribute_value($this, $layer['Layer_ID'], $attributes, $j, $k, $layer['shape'][$k], $size, $select_width, $this->user->rolle->fontsize_gle);
						$td.= '	</td>';
						if($nl)$next_line .= $td; else $datapart .= $td;
						if($attributes['arrangement'][$j+1] != 1)$datapart .= '</tr>';						# wenn nächstes Attribut nicht daneben -> Zeile abschliessen
						if($attributes['arrangement'][$j+1] != 1 AND $nl){												# die aufgesammelten tds in neuer Zeile ausgeben
							$datapart .= '<tr>'.$next_line.'</tr>';
							$next_line = '';
							$nl = false;
						}
						
						if($attributes['privileg'][$j] >= '0'){
							$this->form_field_names .= $layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'|';
						}
					}
					else {
						$columnname = $attributes['name'][$j];
						$geom_tablename = $attributes['table_name'][$attributes['name'][$j]];
						$geomtype = $attributes['geomtype'][$attributes['name'][$j]];
						# Frage den Geometrietyp aus der Layerdefinition, wenn in geometry_columns nur als Geometry definiert.
						if ($geomtype == 'GEOMETRY' OR empty($geomtype)) {
							$geomtypes = array('POINT', 'LINESTRING', 'POLYGON');
							$geomtype = $geomtypes[$layer['Datentyp']];
							?><input type="hidden" name="Datentyp" value="<?php echo $layer['Datentyp']; ?>"><?php
						}
						$dimension = $attributes['dimension'][$j];
						$privileg = $attributes['privileg'][$j];
						$nullable = $attributes['nullable'][$j];
						$this->form_field_names .= $layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';Geometrie;'.$attributes['nullable'][$j].'|';
					}
				}
				if($attributes['group'][$j] != $attributes['group'][$j+1]){		# wenn die nächste Gruppe anders ist, Tabelle schliessen
					$datapart .= '</table></td></tr>';
				}
			}
			if($this->new_entry != true)echo $datapart;
				
				if(($columnname != '' OR $layer['shape'][$k]['wfs_geom'] != '') AND $this->new_entry != true AND $this->formvars['printversion'] == ''){
					if($attributes['group'][0] != ''){ ?>
						<tr><td colspan="2"><table width="100%" class="tgle" border="2" cellpadding="0" cellspacing="0"><tbody class="gle">
					<? } ?>
				 
					<tr>
						<? if($layer['querymaps'][$k] != ''){ ?>
						<td <? if($attributes['group'][0] != '')echo 'width="200px"'; ?> bgcolor="<? echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;" align="center"><img style="border:1px solid grey" src="<? echo $layer['querymaps'][$k]; ?>"></td>
						<? } else { ?>
			    	    <td <? if($attributes['group'][0] != '')echo 'width="200px"'; ?> bgcolor="<? echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;">&nbsp;</td>
			    	    <? } ?>
			    	    <td style="padding-top:5px; padding-bottom:5px;" valign="middle">
<?						
							if(!$layer['shape'][$k]['wfs_geom']){		// kein WFS 
								echo '<input type="hidden" id="'.$columnname.'_'.$k.'" value="'.$layer['shape'][$k][$columnname].'">';

								if($geomtype == 'POLYGON' OR $geomtype == 'MULTIPOLYGON'){ ?>
									<table cellspacing="0" cellpadding="0">
										<tr>
<?								if($privileg == 1 AND !$lock[$k]) { ?>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strEditGeom; ?>" href="index.php?go=PolygonEditor&oid=<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>&layer_tablename=<? echo $geom_tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selected_layer_id=<? echo $layer['Layer_ID'];?>"><div class="emboss edit_geom"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
<?								} 
									if($layer['shape'][$k][$attributes['the_geom']]){ ?>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom; ?>" href="javascript:zoom2object('go=zoomtoPolygon&oid=<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>&layer_tablename=<? echo $geom_tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=zoomonly');"><div class="emboss zoom_normal"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
									<? if($layer['Layer_ID'] > 0){ ?>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom.$strAndHighlight; ?>" href="javascript:zoom2object('go=zoomtoPolygon&oid=<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>&layer_tablename=<? echo $geom_tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=false');"><div class="emboss zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom.$strAndHide; ?>" href="javascript:zoom2object('go=zoomtoPolygon&oid=<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>&layer_tablename=<? echo $geom_tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=true');"><div class="emboss zoom_select"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
									<? }} ?>
										</tr>
									</table>
<?							}
								elseif($geomtype == 'POINT'){ ?>
									<table cellspacing="0" cellpadding="0">
										<tr>
<?								if($privileg == 1 AND !$lock[$k]) { ?>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strEditGeom; ?>" href="index.php?go=PointEditor&dimension=<? echo $dimension; ?>&oid=<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>&layer_tablename=<? echo $geom_tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>"><div class="emboss edit_geom"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
<?								}
									if($layer['shape'][$k][$attributes['the_geom']]){ ?>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom; ?>" href="javascript:zoom2object('go=zoomtoPoint&dimension=<? echo $dimension; ?>&oid=<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>&layer_tablename=<? echo $geom_tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=zoomonly')"><div class="emboss zoom_normal"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
									<? if($layer['Layer_ID'] > 0){ ?>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom.$strAndHighlight; ?>" href="javascript:zoom2object('go=zoomtoPoint&dimension=<? echo $dimension; ?>&oid=<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>&layer_tablename=<? echo $geom_tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=false')"><div class="emboss zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
											<td style="padding: 0 0 0 10;"><!--a title="<? echo $strMapZoom.$strAndHide; ?>" href="javascript:zoom2object('go=zoomtoPoint&dimension=<? #echo $dimension; ?>&oid=<?php #echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>&layer_tablename=<? #echo $geom_tablename; ?>&layer_columnname=<? #echo $columnname; ?>&layer_id=<? #echo $layer['Layer_ID'];?>&selektieren=true')"><div class="emboss zoom_select"><img src="<? #echo GRAPHICSPATH.'leer.gif'; ?>"></div></a--></td>
									<? }} ?>
										</tr>
									</table>
<?	    				}
								elseif($geomtype == 'MULTILINESTRING' OR $geomtype == 'LINESTRING') { ?>
									<table cellspacing="0" cellpadding="0">
										<tr>
<?								if($privileg == 1 AND !$lock[$k]) { ?>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strEditGeom; ?>" href="index.php?go=LineEditor&oid=<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>&layer_tablename=<? echo $geom_tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selected_layer_id=<? echo $layer['Layer_ID'];?>"><div class="emboss edit_geom"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
<?								}
									if($layer['shape'][$k][$attributes['the_geom']]){ ?>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom; ?>" href="javascript:zoom2object('go=zoomToLine&oid=<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>&layer_tablename=<? echo $geom_tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=zoomonly')"><div class="emboss zoom_normal"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
									<? if($layer['Layer_ID'] > 0){ ?>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom.$strAndHighlight; ?>" href="javascript:zoom2object('go=zoomToLine&oid=<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>&layer_tablename=<? echo $geom_tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=false')"><div class="emboss zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom.$strAndHide; ?>" href="javascript:zoom2object('go=zoomToLine&oid=<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>&layer_tablename=<? echo $geom_tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=true')"><div class="emboss zoom_select"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
									<? }} ?>
										</tr>
									</table>
<?
			    				}
						}
						else{		# bei WFS-Layern
?>						<table cellspacing="0" cellpadding="0">
								<tr>
									<td style="padding: 0 0 0 5;"><a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="javascript:zoom2object('go=zoom2wkt&wkt=<? echo $layer['shape'][$k]['wfs_geom']; ?>&epsg=<? echo $layer['epsg_code']; ?>');"><div class="emboss zoom_normal"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
								</tr>
							</table>
<?															
						}							
?>								
							</td>
			    </tr>
			    
			    <? if($attributes['group'][0] != ''){ ?>
								</table></td></tr>
					<? }		    
	}

				if($this->new_entry == true){
					if($privileg == 1){
						if(!$this->user->rolle->geom_edit_first)echo $datapart;
						if($nullable === '0'){ ?>
							<script type="text/javascript">
    						geom_not_null = true;
    					</script>
<?					}
						$this->titel=$strTitleGeometryEditor;
						if($geomtype == 'POLYGON' OR $geomtype == 'MULTIPOLYGON'){
							echo '
							<tr>
								<td colspan="2" align="center">';
									include(LAYOUTPATH.'snippets/PolygonEditor.php');
							echo'
								</td>
							</tr>';
						} elseif($geomtype == 'POINT') {
							$this->formvars['dimension'] = $dimension;
							echo '
							<tr>
								<td colspan="2" align="center">';
									include(LAYOUTPATH.'snippets/PointEditor.php');
							echo'
								</td>
							</tr>';
						} elseif($geomtype == 'MULTILINESTRING' OR $geomtype == 'LINESTRING') {
							echo '
							<tr>
								<td colspan="2" align="center">';
									include(LAYOUTPATH.'snippets/LineEditor.php');
							echo'
								</td>
							</tr>';
						}
						if($this->user->rolle->geom_edit_first)echo $datapart;
					}
					else echo $datapart;
				}
 ?>
			</tbody>
				<? if ($this->user->rolle->visually_impaired) include(LAYOUTPATH . 'snippets/generic_layer_editor_2_layer_head.php'); ?>
			</table>
			</div>
			<img height="7" src="<? echo GRAPHICSPATH ?>leer.gif">
		</td>
	</tr>
<?
	}
	if($this->formvars['printversion'] == ''){
?>
	<tr>
		<td colspan="2"align="left">
		<? if($layer['connectiontype'] == 6 AND $this->new_entry != true AND $layer['Layer_ID'] > 0){ ?>
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
						<? if($this->formvars['go'] == 'Zwischenablage' OR $this->formvars['go'] == 'gemerkte_Datensaetze_anzeigen'){ ?>
								<td style="padding: 5 10 0 0;"><a title="<? echo $strDontRememberDataset; ?>" href="javascript:remove_from_clipboard(<? echo $layer['Layer_ID']; ?>);"><div class="button_background"><div class="emboss nicht_mehr_merken"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
							<? }else{ ?>
								<td id="merk_link_<? echo $layer['Layer_ID']; ?>" style="padding: 5 10 0 0;"><a title="<? echo $strRemember; ?>" href="javascript:add_to_clipboard(<? echo $layer['Layer_ID']; ?>);"><div class="button_background"><div class="emboss merken"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
							<? } ?>
					<? if($layer['privileg'] == '2'){ ?>
								<td id="delete_link_<? echo $layer['Layer_ID']; ?>" style="padding: 5 10 0 0;"><a title="<? echo $strdelete; ?>" href="javascript:delete_datasets(<?php echo $layer['Layer_ID']; ?>);"><div class="button_background"><div class="emboss datensatz_loeschen"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></td>
					<?} if($layer['export_privileg'] != 0){ ?>
								<td style="padding: 5 10 0 0;"><a title="<? echo $strExport; ?>" href="javascript:daten_export(<?php echo $layer['Layer_ID']; ?>, <? echo $layer['count']; ?>);"><div class="button_background"><div class="emboss datensatz_exportieren"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
					<? } if($layer['layouts']){ ?>
								<td id="print_link_<? echo $layer['Layer_ID']; ?>" style="padding: 5 10 0 0;"><a title="<? echo $strPrint; ?>" href="javascript:print_data(<?php echo $layer['Layer_ID']; ?>);"><div class="button_background"><div class="emboss drucken"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
					<? } ?>
					<? if($privileg != ''){ ?>
								<td id="zoom_link_<? echo $layer['Layer_ID']; ?>" style="padding: 5 10 0 0;"><a title="<? echo $strzoomtodatasets; ?>" href="javascript:zoomto_datasets(<?php echo $layer['Layer_ID']; ?>, '<? echo $geom_tablename; ?>', '<? echo $columnname; ?>');"><div class="emboss zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
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
	<? } ?>
</table>


<input type="hidden" name="checkbox_names_<? echo $layer['Layer_ID']; ?>" value="<? echo $checkbox_names; ?>">
<input type="hidden" name="orderby<? echo $layer['Layer_ID']; ?>" id="orderby<? echo $layer['Layer_ID']; ?>" value="<? echo $this->formvars['orderby'.$layer['Layer_ID']]; ?>">

<?
  }
  elseif($layer['requires'] == '' AND $layer['required'] == ''){
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
