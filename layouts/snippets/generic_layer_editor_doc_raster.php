<?php
 # 2008-09-30 sr
  include(LAYOUTPATH.'languages/generic_layer_editor_2_'.$this->user->rolle->language.'.php');
 
	include(SNIPPETS.'generic_functions.php'); 
 
	# Variablensubstitution
	$layer = $this->qlayerset[$i];
	$attributes = $layer['attributes'];
	if($this->currentform == 'document.GUI2')$size = 40;
	else $size = 61;
	$linksize = $this->user->rolle->fontsize_gle - 1;
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
	}
	
	close_record = function(record){
		document.getElementById(record).className = 'raster_record';
	}
		
</script>

<div id="layer" align="left" onclick="remove_calendar();">
<? if($this->new_entry != true){ ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="95%" align="center"><h2>&nbsp;&nbsp;<? echo $layer['Name']; ?></h2></td>
	</tr>
	<tr><td><img height="7" src="<? echo GRAPHICSPATH ?>leer.gif"></td></tr>
</table>
<?
		}
  	$doit = false;
	  $anzObj = count($this->qlayerset[$i]['shape']);
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
	<tr>
<?
	$hover_preview = true;
	$checkbox_names = '';
	$columnname = '';
	$tablename = '';
	$geomtype = '';
	$dimension = '';
	$privileg = '';
	for ($k=0;$k<$anzObj;$k++) {
		$checkbox_names .= 'check;'.$attributes['table_alias_name'][$layer['maintable']].';'.$layer['maintable'].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].'|';
		if($k%5==0){
?>
	</tr>
</table>
<table>		
	<tr>
<? } ?>
		<td valign="top">
		<div <? if($this->new_entry != true)echo 'class="raster_record" onclick="open_record(event, this)"'; ?> id="record_<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>" <? if($k%5==0)echo 'style="clear: both;"'?>>
			<? if($this->new_entry != true){ ?>
			<div style="position: absolute;top: 1px;right: 1px"><a href="javascript:close_record('record_<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>');" title="Schlie&szlig;en"><img style="border:none" src="<? echo GRAPHICSPATH."exit2.png"; ?>"></img></a></div>
			<? } ?>
			<input type="hidden" value="" name="changed_<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>"> 
			<table class="tgle" border="1">
				<? if($this->new_entry != true AND $this->formvars['printversion'] == ''){ ?>
				<tr class="tr_hide">
	        <th colspan="2" style="background-color:<? echo BG_GLEHEADER; ?>;">			  
						<table width="100%" cellspacing="0" cellpadding="0">
							<tr>
								<? if($layer['connectiontype'] == 6){ ?>
								<td>
									<input style="display:none" id="<? echo $layer['Layer_ID'].'_'.$k; ?>" type="checkbox" name="check;<? echo $attributes['table_alias_name'][$layer['maintable']].';'.$layer['maintable'].';'.$layer['shape'][$k][$layer['maintable'].'_oid']; ?>">&nbsp;
								</td>
								<? } ?>
								<td align="right">
									<table cellspacing="0" cellpadding="0">
										<tr>
											<? if($this->formvars['go'] == 'Zwischenablage' OR $this->formvars['go'] == 'gemerkte_Datensaetze_anzeigen'){ ?>
												<td style="padding: 0 0 0 10;"><a title="<? echo $strDontRememberDataset; ?>" href="javascript:select_this_dataset(<? echo $layer['Layer_ID']; ?>, <? echo $k; ?>);remove_from_clipboard(<? echo $layer['Layer_ID']; ?>);"><div class="button_background"><div class="emboss nicht_mehr_merken"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
											<? }else{ ?>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strRememberDataset; ?>" href="javascript:select_this_dataset(<? echo $layer['Layer_ID']; ?>, <? echo $k; ?>);add_to_clipboard(<? echo $layer['Layer_ID']; ?>);"><div class="button_background"><div class="emboss merken"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
											<? } ?>
								<? 	if($layer['privileg'] > '0'){ ?>
											<td style="padding: 0 0 0 10;"><a href="javascript:select_this_dataset(<? echo $layer['Layer_ID']; ?>, <? echo $k; ?>);use_for_new_dataset(<? echo $layer['Layer_ID']; ?>, <? echo $k; ?>)" title="<? echo $strUseForNewDataset; ?>"><div class="button_background"><div class="emboss use_for_dataset"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
								<? 	} 
										if(false AND $layer['connectiontype'] == 6 AND $layer['export_privileg'] != 0){ ?>
											<td style="padding: 0 0 0 10;"><a href="javascript:select_this_dataset(<? echo $layer['Layer_ID']; ?>, <? echo $k; ?>);daten_export(<? echo $layer['Layer_ID']; ?>, <? echo $layer['count']; ?>);" title="<? echo $strExportThis; ?>"><div class="button_background"><div class="emboss datensatz_exportieren"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
								<? 	}  
										if($layer['layouts']){ ?>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strPrintDataset; ?>" href="javascript:select_this_dataset(<? echo $layer['Layer_ID']; ?>, <? echo $k; ?>);print_data(<?php echo $layer['Layer_ID']; ?>);"><div class="button_background"><div class="emboss drucken"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
								<?	}
										if($layer['privileg'] == '2'){ ?>
											<td style="padding: 0 40 0 10;"><a href="javascript:select_this_dataset(<? echo $layer['Layer_ID']; ?>, <? echo $k; ?>);delete_datasets(<?php echo $layer['Layer_ID']; ?>);" title="<? echo $strDeleteThisDataset; ?>"><div class="button_background"><div class="emboss datensatz_loeschen"><img width="30" src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div></a></td>
									<? } ?>
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
			if($layer['shape'][$k]['lock'] == 'bereits übertragen' OR $trans_oid[1] != '' AND $layer['shape'][$k][$layer['maintable'].'_oid'] == $trans_oid[1]){
				echo '<tr><td colspan="2" align="center"><span class="red">Dieser Datensatz wurde bereits übertragen und kann nicht bearbeitet werden.</span></td></tr>';
				$lock[$k] = true;
			}
			for($j = 0; $j < count($attributes['name']); $j++){
				$datapart = '';
				if($layer['shape'][$k][$attributes['name'][$j]] == ''){
					$layer['shape'][$k][$attributes['name'][$j]] = $this->formvars[$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j]];
				}
				if(($attributes['privileg'][$j] == '0' AND $attributes['form_element_type'][$j] == 'Auswahlfeld') OR ($attributes['form_element_type'][$j] == 'Text' AND $attributes['type'][$j] == 'not_saveable')){				# entweder ist es ein nicht speicherbares Attribut oder ein nur lesbares Auswahlfeld, dann ist es auch nicht speicherbar
					$attributes['form_element_type'][$j] .= '_not_saveable';
				}
				if($attributes['invisible'][$attributes['name'][$j]] != 'true'  AND $attributes['name'][$j] != 'lock'){
?>
					<tr class="<? if($attributes['raster_visibility'][$j] == 1)echo 'tr_show'; else echo 'tr_hide'; ?>">
<?				if($attributes['type'][$j] != 'geometry'){
						echo '<td  valign="top" bgcolor="'.BG_GLEATTRIBUTE.'">';
						if($attributes['privileg'][$j] != '0' AND !$lock[$k]){
							$this->editable = 'true';
						}
						if($attributes['alias'][$j] == ''){
							$attributes['alias'][$j] = $attributes['name'][$j];
						}
						echo '<table width="100%" cellspacing="0" cellpadding="0"><tr style="border: none"><td>';
						if(!in_array($attributes['form_element_type'][$j], array('SubFormPK', 'SubFormEmbeddedPK', 'SubFormFK', 'dynamicLink'))){
							echo '<a title="Sortieren nach '.$attributes['alias'][$j].'" href="javascript:change_orderby(\''.$attributes['name'][$j].'\', '.$layer['Layer_ID'].');">'.$attributes['alias'][$j].'</a>';
						}
						else{
							echo '<span style="color:#222222;">'.$attributes['alias'][$j].'</span>';
						}
						if($attributes['nullable'][$j] == '0' AND $attributes['privileg'][$j] != '0'){
							echo '<span title="Eingabe erforderlich">*</span>';
						}
						if($attributes['tooltip'][$j]!='' AND $attributes['form_element_type'][$j] != 'Time') {
						  echo '<td align="right"><a href="#" title="'.$attributes['tooltip'][$j].'"><img src="'.GRAPHICSPATH.'emblem-important.png" border="0"></a></td>';
						}
						if($attributes['type'][$j] == 'date'){
							echo '<td align="right"><a href="javascript:;" title=" (TT.MM.JJJJ) '.$attributes['tooltip'][$j].'" ';
							if($attributes['privileg'][$j] == '1' AND !$lock[$k]){
								echo 'onclick="add_calendar(event, \''.$attributes['name'][$j].'_'.$k.'\');"';
							}
							echo '><img src="'.GRAPHICSPATH.'calendarsheet.png" border="0"></a><div id="calendar"><input type="hidden" id="calendar_'.$attributes['name'][$j].'_'.$k.'"></div></td>';
						}
						echo '</td></tr></table>';
						echo '</td><td><div id="formelement">';
						if($attributes['constraints'][$j] != '' AND !in_array($attributes['constraints'][$j], array('PRIMARY KEY', 'UNIQUE'))){
		  				if($attributes['privileg'][$j] == '0' OR $lock[$k]){
								echo '<input readonly style="background-color:#e8e3da;" size="6" type="text" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'" value="'.$layer['shape'][$k][$attributes['name'][$j]].'">';
							}
							else{
		  					echo '<select title="'.$attributes['alias'][$j].'" style="font-size: '.$this->user->rolle->fontsize_gle.'px" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'">';
								for($e = 0; $e < count($attributes['enum_value'][$j]); $e++){
									echo '<option ';
									if($attributes['enum_value'][$j][$e] == $layer['shape'][$k][$attributes['name'][$j]] OR ($attributes['enum_value'][$j][$e] != '' AND $attributes['enum_value'][$j][$e] == $this->formvars[$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j]])){
										echo 'selected ';
									}
									echo 'value="'.$attributes['enum_value'][$j][$e].'">'.$attributes['enum_output'][$j][$e].'</option>';
								}
								echo '</select>';
		  				}
		  			}
		  			else{
							include(SNIPPETS.'generic_formelements.php');
							echo $datapart;
		  			}
						if($attributes['privileg'][$j] >= '0' AND !($attributes['privileg'][$j] == '0' AND $attributes['form_element_type'][$j] == 'Auswahlfeld')){
							$this->form_field_names .= $layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].';'.$attributes['type'][$j].'|';
						}
		  		}
		  		else {
		  			$columnname = $attributes['name'][$j];
		  			$tablename = $attributes['table_name'][$attributes['name'][$j]];
		  			$geomtype = $attributes['geomtype'][$attributes['name'][$j]];
		  			$dimension = $attributes['dimension'][$j];
		  			$privileg = $attributes['privileg'][$j];
		  			$this->form_field_names .= $layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].'|';
		  		}
?>
						</div>
					</td>
				</tr>
<?				}
				}
				
				if(($columnname != '' OR $layer['shape'][$k]['geom'] != '') AND $this->new_entry != true AND $this->formvars['printversion'] == ''){
					if($attributes['group'][0] != ''){ ?>
						<tr><td colspan="2"><table width="100%" class="tgle" border="2" cellpadding="0" cellspacing="0"><tbody class="gle">
					<? } ?>
				 
					<tr class="tr_hide">
						<? if($layer['querymaps'][$k] != ''){ ?>
						<td <? if($attributes['group'][0] != '')echo 'width="200px"'; ?> bgcolor="<? echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;" align="center"><img style="border:1px solid grey" src="<? echo $layer['querymaps'][$k]; ?>"></td>
						<? } else { ?>
			    	    <td <? if($attributes['group'][0] != '')echo 'width="200px"'; ?> bgcolor="<? echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;">&nbsp;</td>
			    	    <? } ?>
			    	    <td style="padding-top:5px; padding-bottom:5px;" valign="middle">
<?						
							if(!$layer['shape'][$k]['geom']){		// kein WFS
								echo '<input type="hidden" id="'.$columnname.'_'.$k.'" value="'.$layer['shape'][$k][$columnname].'">';
								if($geomtype == 'POLYGON' OR $geomtype == 'MULTIPOLYGON' OR $geomtype == 'GEOMETRY'){
									 ?>
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
			    </tr>
			    
			    <? if($attributes['group'][0] != ''){ ?>
								</table></td></tr>
					<? }		    
				}
				
				if($privileg == 1) {
					if($this->new_entry == true){
						$this->titel=$strTitleGeometryEditor;
						if($geomtype == 'POLYGON' OR $geomtype == 'MULTIPOLYGON' OR $geomtype == 'GEOMETRY'){
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
						} elseif($geomtype == 'MULTILINESTRING'  OR $geomtype == 'LINESTRING') {
							echo '
							<tr>
								<td colspan="2" align="center">';
									include(LAYOUTPATH.'snippets/LineEditor.php');
							echo'
								</td>
							</tr>';
						}
					}
				}
?>
			  </tbody>
			</table>
		</div>
<?
	}
?>
	</tr>
<table>
</div>
<input type="hidden" name="checkbox_names_<? echo $layer['Layer_ID']; ?>" value="<? echo $checkbox_names; ?>">
<input type="hidden" name="orderby<? echo $layer['Layer_ID']; ?>" id="orderby<? echo $layer['Layer_ID']; ?>" value="<? echo $this->formvars['orderby'.$layer['Layer_ID']]; ?>">
<?
  }
  else {
  	# nix machen
  }
?>
