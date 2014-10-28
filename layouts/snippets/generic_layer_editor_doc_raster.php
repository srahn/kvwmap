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
<div id="layer" align="left">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="95%" align="center"><h2>&nbsp;&nbsp;<? echo $layer['Name']; ?></h2></td>
	</tr>
	<tr><td><img height="7" src="<? echo GRAPHICSPATH ?>leer.gif"></td></tr>
</table>
<?
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
	$checkbox_names = '';
	$columnname = '';
	$tablename = '';
	$geomtype = '';
	$dimension = '';
	$privileg = '';
	for ($k=0;$k<$anzObj;$k++) {
		$checkbox_name .= 'check;'.$attributes['table_alias_name'][$layer['maintable']].';'.$layer['maintable'].';'.$layer['shape'][$k][$layer['maintable'].'_oid'];
		if($k%5==0){
?>
	</tr>
</table>
<table>		
	<tr>
<? } ?>
		<td valign="top">
		<div <? if($this->new_entry != true)echo 'class="raster_record"'; ?> id="record_<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>" <? if($k%5==0)echo 'style="clear: both;"'?>>
			<input type="hidden" value="" name="changed_<? echo $layer['shape'][$k][$layer['maintable'].'_oid']; ?>"> 
			<table class="tgle" border="1">
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
				if($this->new_entry == true AND $attributes['default'][$j] != '' AND $layer['shape'][$k][$attributes['name'][$j]] == ''){		# Default-Werte setzen
					$layer['shape'][$k][$attributes['name'][$j]] = $attributes['default'][$j];
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
						echo '<span style="color: #222222;" title="'.$attributes['tooltip'][$j].'">'.$attributes['alias'][$j].'</span>';
						if($attributes['nullable'][$j] == '0' AND $attributes['privileg'][$j] != '0'){
							echo '<span title="Eingabe erforderlich">*</span>';
						}
						if($attributes['tooltip'][$j]!='' AND $attributes['form_element_type'][$j] != 'Time') {
						  echo '<td align="right"><a href="#" title="'.$attributes['tooltip'][$j].'"><img src="'.GRAPHICSPATH.'emblem-important.png" border="0"></a></td>';
						}
						echo '</td></tr></table>';
						echo '</td><td>';
		  			if($attributes['constraints'][$j] != '' AND $attributes['constraints'][$j] != 'PRIMARY KEY'){
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
									if($layer['connectiontype'] == 6 AND $layer['export_privileg'] == '1' AND $layer['shape'][$k][$attributes['the_geom']]){ ?>
			    					<script type="text/javascript">
			    						document.getElementById('uko_<? echo $layer['Layer_ID'].'_'.$k; ?>').href = 'index.php?go=UKO_Export&oid=<?php echo $layer['shape'][$k][$tablename.'_oid']; ?>&layer_tablename=<? echo $tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selected_layer_id=<? echo $layer['Layer_ID'];?>';
			    						document.getElementById('td_uko_<? echo $layer['Layer_ID'].'_'.$k; ?>').style.display = '';
			    					</script>
									<? } ?>
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
<input type="hidden" name="checkbox_names_<? echo $layer['Layer_ID']; ?>" value="<? echo $checkbox_name; ?>">
<?
  }
  else {
  	# nix machen
  }
?>
