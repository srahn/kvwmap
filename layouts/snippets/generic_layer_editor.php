<?

	include(LAYOUTPATH.'languages/generic_layer_editor_2_'.$this->user->rolle->language.'.php');
  
	$checkbox_names = '';
	$columnname = '';
	$tablename = '';
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
?>
<div id="layer" onclick="remove_calendar();">
<? if($this->new_entry != true AND $layer['requires'] == ''){ ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td valign="top" style="padding: 0 0 0 0">
			<? if($layer['template'] == '' OR $layer['template'] == 'generic_layer_editor_2.php'){ ?>
			<a href="javascript:switch_gle_view(<? echo $layer['Layer_ID']; ?>);"><img title="<? echo $strSwitchGLEViewColumns; ?>" class="hover-border" src="<? echo GRAPHICSPATH.'columns.png'; ?>"></a>
			<? }else{ ?>
			<a href="javascript:switch_gle_view(<? echo $layer['Layer_ID']; ?>);"><img title="<? echo $strSwitchGLEViewRows; ?>" class="hover-border" src="<? echo GRAPHICSPATH.'rows.png'; ?>"></a>
			<? } ?>
		</td>
		<td height="30" width="99%" align="center"><h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $layer['Name']; ?></h2></td>
		<td valign="top" style="padding: 0 10 0 0">
			<? if($layer['template'] == '' OR $layer['template'] == 'generic_layer_editor_2.php'){ ?>
			<a href="javascript:switch_gle_view(<? echo $layer['Layer_ID']; ?>);"><img title="<? echo $strSwitchGLEViewColumns; ?>" class="hover-border" src="<? echo GRAPHICSPATH.'columns.png'; ?>"></a>
			<? }else{ ?>
			<a href="javascript:switch_gle_view(<? echo $layer['Layer_ID']; ?>);"><img title="<? echo $strSwitchGLEViewRows; ?>" class="hover-border" src="<? echo GRAPHICSPATH.'rows.png'; ?>"></a>
			<? } ?>
		</td>
		<td align="right" valign="top">			
			<a href="javascript:scrollbottom();"><img class="hover-border" title="nach unten" src="<? echo GRAPHICSPATH; ?>pfeil.gif" width="11" height="11" border="0"></a>&nbsp;
		</td>		
	</tr>
</table>
<?
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
  if($doit == true){
?>
<table border="0" cellspacing="1" cellpadding="2" width="100%">
	<tr>
		<td width="100%">   
			<table border="1" style="border-collapse:collapse" cellspacing="0" cellpadding="2" width="100%">
			  <tr bgcolor="<?php echo BG_DEFAULT ?>">
				<td></td>
			  
			  <?
			  	for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
						if($attributes['invisible'][$attributes['name'][$j]] != 'true' AND $attributes['name'][$j] != 'lock'){
							if($this->qlayerset[$i]['attributes']['type'][$j] != 'geometry'){
								echo '<td ';
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
									echo 'width="100%";';
									echo '><tr><td>';
									if($attributes['form_element_type'][$j] != 'SubFormPK' AND $attributes['form_element_type'][$j] != 'SubFormEmbeddedPK'){
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
							else{
								echo '<td bgcolor="'.BG_GLEATTRIBUTE.'">&nbsp;</td>';
							}
						}
			  	}
			  ?>
			  </tr>
<?
	for ($k=0;$k<$anzObj;$k++) {
		$checkbox_names .= 'check;'.$attributes['table_alias_name'][$layer['maintable']].';'.$layer['maintable'].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].'|';
?>
	<input type="hidden" value="" name="changed_<? echo $layer['Layer_ID'].'_'.$layer['shape'][$k][$layer['maintable'].'_oid']; ?>"> 
	<tr 
	<? if($this->user->rolle->querymode == 1){ ?>
		onmouseenter="ahah('index.php', 'go=tooltip_query&querylayer_id=<? echo $layer['Layer_ID']; ?>&oid=<? echo $layer['shape'][$k][$attributes['table_name'][$attributes['the_geom']].'_oid']; ?>', new Array(top.document.GUI.result, ''), new Array('setvalue', 'execute_function'));"
	<? } ?>
	>
		<td style="background-color:<? echo BG_GLEHEADER; ?>;">
		  <? if($this->new_entry != true AND $this->formvars['printversion'] == ''){ ?>
		  <table>
			<tr>
			  <td>
				<input id="<? echo $layer['Layer_ID'].'_'.$k; ?>" type="checkbox" name="check;<? echo $attributes['table_alias_name'][$layer['maintable']].';'.$layer['maintable'].';'.$layer['shape'][$k][$layer['maintable'].'_oid']; ?>">&nbsp;
			  </td>
			</tr>
		  </table>
		  <? } ?>
	</td>
	
<?

		for($j = 0; $j < count($attributes['name']); $j++){
			$datapart = '';
			if(($attributes['privileg'][$j] == '0' AND $attributes['form_element_type'][$j] == 'Auswahlfeld') OR ($attributes['form_element_type'][$j] == 'Text' AND $attributes['type'][$j] == 'not_saveable')){				# entweder ist es ein nicht speicherbares Attribut oder ein nur lesbares Auswahlfeld, dann ist es auch nicht speicherbar
				$attributes['form_element_type'][$j] .= '_not_saveable';
			}
			if($attributes['invisible'][$attributes['name'][$j]] != 'true' AND $attributes['name'][$j] != 'lock'){
				if($attributes['type'][$j] != 'geometry'){
					echo '<td>';
					$datapart .= attribute_value($this, $layer['Layer_ID'], $attributes, $j, $k, $layer['shape'][$k], $size, $select_width, $this->user->rolle->fontsize_gle);
					echo $datapart;
					echo '
							</td>
					';			  			
					if($attributes['privileg'][$j] >= '0'){
						$this->form_field_names .= $layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'|';
					}
				}
				else {
					$columnname = $attributes['name'][$j];
					$tablename = $attributes['table_name'][$attributes['name'][$j]];
					$geomtype = $attributes['geomtype'][$attributes['name'][$j]];
					$dimension = $attributes['dimension'][$j];
					$privileg = $attributes['privileg'][$j];
					$nullable = $attributes['nullable'][$j];
					$this->form_field_names .= $layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';Geometrie;'.$attributes['nullable'][$j].'|';
				}
			}
		}
				if(($columnname != '' OR $layer['shape'][$k]['geom'] != '') AND $this->new_entry != true AND $this->formvars['printversion'] == ''){
					$geometry = true;
					// if($attributes['group'][0] != ''){ ?>
						 <!--tr><td colspan="2"><table width="100%" class="tgle" border="2"><tbody class="gle"-->
					 <? //} ?>
				 
						<? //if($layer['querymaps'][$k] != ''){ ?>
						<!--td <? //if($attributes['group'][0] != '')echo 'width="200px"'; ?> bgcolor="<? #echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;" align="center"><img style="border:1px solid grey" src="<? echo $layer['querymaps'][$k]; ?>"></td>
						<? //} else { ?>
			    	    <td <? //if($attributes['group'][0] != '')echo 'width="200px"'; ?> bgcolor="<? #echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;">&nbsp;</td-->
			    	    <? //} ?>
			    	    <td style="padding-top:5px; padding-bottom:5px;">
<?						
							if(!$layer['shape'][$k]['geom']){		// kein WFS
								echo '<input type="hidden" id="'.$columnname.'_'.$k.'" value="'.$layer['shape'][$k][$columnname].'">';
								if($geomtype == 'POLYGON' OR $geomtype == 'MULTIPOLYGON' OR $geomtype == 'GEOMETRY'){ ?>
									<table cellspacing="0" cellpadding="0">
										<tr>
<?								if($privileg == 1 AND !$lock[$k]) { ?>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strEditGeom; ?>" href="index.php?go=PolygonEditor&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selected_layer_id=<? echo $layer['Layer_ID'];?>"><div class="emboss edit_geom"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
<?								} 
									if($layer['shape'][$k][$attributes['the_geom']]){ ?>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom; ?>" href="javascript:zoom2object('go=zoomtoPolygon&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=zoomonly');"><div class="emboss zoom_normal"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom.$strAndHighlight; ?>" href="javascript:zoom2object('go=zoomtoPolygon&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=false');"><div class="emboss zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom.$strAndHide; ?>" href="javascript:zoom2object('go=zoomtoPolygon&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=true');"><div class="emboss zoom_select"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
									<? } ?>
										</tr>
									</table>
<?							}
								elseif($geomtype == 'POINT'){ ?>
									<table cellspacing="0" cellpadding="0">
										<tr>
<?								if($privileg == 1 AND !$lock[$k]) { ?>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strEditGeom; ?>" href="index.php?go=PointEditor&dimension=<? echo $dimension; ?>&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>"><div class="emboss edit_geom"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
<?								}
									if($layer['shape'][$k][$attributes['the_geom']]){ ?>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom; ?>" href="javascript:zoom2object('go=zoomtoPoint&dimension=<? echo $dimension; ?>&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=zoomonly')"><div class="emboss zoom_normal"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom.$strAndHighlight; ?>" href="javascript:zoom2object('go=zoomtoPoint&dimension=<? echo $dimension; ?>&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=false')"><div class="emboss zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
											<td style="padding: 0 0 0 10;"><!--a title="<? echo $strMapZoom.$strAndHide; ?>" href="javascript:zoom2object('go=zoomtoPoint&dimension=<? #echo $dimension; ?>&oid=<?php #echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? #echo $tablename; ?>&layer_columnname=<? #echo $columnname; ?>&layer_id=<? #echo $layer['Layer_ID'];?>&selektieren=true')"><div class="emboss zoom_select"><img src="<? #echo GRAPHICSPATH.'leer.gif'; ?>"></div></a--></td>
									<? } ?>
										</tr>
									</table>
<?	    				}
								elseif($geomtype == 'MULTILINESTRING' OR $geomtype == 'LINESTRING') { ?>
									<table cellspacing="0" cellpadding="0">
										<tr>
<?								if($privileg == 1 AND !$lock[$k]) { ?>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strEditGeom; ?>" href="index.php?go=LineEditor&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selected_layer_id=<? echo $layer['Layer_ID'];?>"><div class="emboss edit_geom"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
<?								}
									if($layer['shape'][$k][$attributes['the_geom']]){ ?>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom; ?>" href="javascript:zoom2object('go=zoomToLine&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=zoomonly')"><div class="emboss zoom_normal"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom.$strAndHighlight; ?>" href="javascript:zoom2object('go=zoomToLine&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=false')"><div class="emboss zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom.$strAndHide; ?>" href="javascript:zoom2object('go=zoomToLine&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selektieren=true')"><div class="emboss zoom_select"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
									<? } ?>
										</tr>
									</table>
<?
			    				}
						}
						else{		# bei WFS-Layern
?>						<table cellspacing="0" cellpadding="0">
								<tr>
									<td style="padding: 0 0 0 5;"><a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="javascript:zoom2object('go=zoom2wkt&wkt=<? echo $layer['shape'][$k]['geom']; ?>&epsg=<? echo $layer['epsg_code']; ?>');"><div class="emboss zoom_normal"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
								</tr>
							</table>
<?															
						}				

?>
			    </td>
		<? 	} ?>
				</tr>
<?	} 
			if(false and $this->new_entry != true AND $this->editable == $layer['Layer_ID']){
?>
				<tr>
					<td style="border: none">&nbsp;</td>
				</tr>
				<tr>
					<td style="background-color:<? echo BG_GLEHEADER; ?>;"></td>
					<?					
						for($j = 0; $j < count($attributes['name']); $j++){
							$datapart = '';
							if(($attributes['privileg'][$j] == '0' AND $attributes['form_element_type'][$j] == 'Auswahlfeld') OR ($attributes['form_element_type'][$j] == 'Text' AND $attributes['type'][$j] == 'not_saveable')){				# entweder ist es ein nicht speicherbares Attribut oder ein nur lesbares Auswahlfeld, dann ist es auch nicht speicherbar
								$attributes['form_element_type'][$j] .= '_not_saveable';
							}
							if($attributes['invisible'][$attributes['name'][$j]] != 'true' AND $attributes['name'][$j] != 'lock'){
								if($attributes['type'][$j] != 'geometry'){
									echo '<td>';
									if($attributes['form_element_type'][$j] != 'Dokument'){
										echo attribute_value($this, $layer['Layer_ID'], $attributes, $j, $k, $layer['shape'][$k], $size, $select_width, $this->user->rolle->fontsize_gle, true);
									}
									echo '</td>';
								}
							}
						}
						if($geometry){
							echo '<td>&nbsp;</td>';
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
								<td id="delete_link_<? echo $layer['Layer_ID']; ?>" style="padding: 5 10 0 0;"><a title="<? echo $strdelete; ?>" href="javascript:delete_datasets(<?php echo $layer['Layer_ID']; ?>);"><div class="button_background"><div class="emboss datensatz_loeschen"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></td>
					<?} if($layer['export_privileg'] != 0){ ?>
								<td style="padding: 5 10 0 0;"><a title="<? echo $strExport; ?>" href="javascript:daten_export(<?php echo $layer['Layer_ID']; ?>, <? echo $layer['count']; ?>);"><div class="button_background"><div class="emboss datensatz_exportieren"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
					<? } if($layer['layouts']){ ?>
								<td id="print_link_<? echo $layer['Layer_ID']; ?>" style="padding: 5 10 0 0;"><a title="<? echo $strPrint; ?>" href="javascript:print_data(<?php echo $layer['Layer_ID']; ?>);"><div class="button_background"><div class="emboss drucken"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
					<? } ?>
					<? if($privileg != ''){ ?>
								<td id="zoom_link_<? echo $layer['Layer_ID']; ?>" style="padding: 5 10 0 0;"><a title="<? echo $strzoomtodatasets; ?>" href="javascript:zoomto_datasets(<?php echo $layer['Layer_ID']; ?>, '<? echo $tablename; ?>', '<? echo $columnname; ?>');"><div class="emboss zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
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
	<? } ?>


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
