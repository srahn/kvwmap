<?php
 # 2008-09-30 sr
  include(LAYOUTPATH.'languages/generic_layer_editor_2_'.$this->user->rolle->language.'.php');
 
 # Variablensubstitution
 $layer = $this->qlayerset[$i];
 $attributes = $layer['attributes'];
 $size = 40;
 $select_width = 'width:290px;';
?>

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
<?
	$checkbox_names = '';
	$columnname = '';
	$geom_tablename = '';
	$geomtype = '';
	$dimension = '';
	$privileg = '';
	for ($k=0;$k<$anzObj;$k++) {
		$checkbox_name .= 'check;'.$attributes['table_alias_name'][$layer['maintable']].';'.$layer['maintable'].';'.$layer['shape'][$k][$layer['maintable'].'_oid'];
?>
	<tr>
		<td>
			<input type="hidden" value="" name="changed_<? echo $layer['Layer_ID'].'_'.$layer['shape'][$k][$layer['maintable'].'_oid']; ?>"> 
			<table class="tgle" <? if($attributes['group'][0] != ''){echo 'border="0" cellpadding="6" cellspacing="0"';}else{echo 'border="1"';} ?>>
			  <tbody <? if($attributes['group'][0] == '')echo 'class="gle"'; else echo 'class="nogle"'; ?>>
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

				if($attributes['visible'][$j]){
					if($attributes['type'][$j] != 'geometry'){
						if($attributes['privileg'][$j] != '0' AND !$lock[$k])$this->editable = $layer['Layer_ID'];
						if($attributes['alias'][$j] == '')$attributes['alias'][$j] = $attributes['name'][$j];
						
						if($attributes['arrangement'][$j] != 1)$datapart .= '<tr>';							# wenn Attribut nicht daneben -> neue Zeile beginnen
						if($attributes['labeling'][$j] != 2){
							$td = '	<td class="gle_attribute_name" '; if($attributes['labeling'][$j] == 1 AND $attributes['arrangement'][$j] == 1 AND $attributes['arrangement'][$j+1] != 1)$td .= 'colspan="20" ';if($attributes['group'][0] != '' AND $attributes['arrangement'][$j] != 1)$td .= 'width="1%">';else $td.='width="1%">';
							$td.= 			attribute_name($layer['Layer_ID'], $attributes, $j, $k, $this->user->rolle->fontsize_gle, false);
							$td.= '	</td>';
							if($nl AND $attributes['labeling'][$j] != 1)$next_line .= $td; else $datapart .= $td;
						}
						if($attributes['labeling'][$j] == 1)$nl = true;										# Attributname soll oben stehen -> alle weiteren tds für die nächste Zeile aufsammeln
						$td = '	<td width="20%" class="gle_attribute_value"'; if($attributes['arrangement'][$j+1] != 1)$td .= 'colspan="20"'; $td .= '>';												
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
		  		else{
		  			$columnname = $attributes['name'][$j];
		  			$geom_tablename = $attributes['table_name'][$attributes['name'][$j]];
		  			$geomtype = $attributes['geomtype'][$attributes['name'][$j]];
		  			$dimension = $attributes['dimension'][$j];
		  			$privileg = $attributes['privileg'][$j];
		  			$this->form_field_names .= $layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$layer['shape'][$k][$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].';'.$attributes['nullable'][$j].'|';
		  		}
				}
				else{
					$invisible_attributes[$layer['Layer_ID']][] = '<input type="hidden" id="'.$layer['Layer_ID'].'_'.$attributes['name'][$j].'_'.$k.'" value="'.htmlspecialchars($layer['shape'][$k][$attributes['name'][$j]]).'">';
				}
				if($attributes['group'][$j] != $attributes['group'][$j+1]){		# wenn die nächste Gruppe anders ist, Tabelle schliessen
					$datapart .= '</table></td></tr>';
				}
				echo $datapart;
			}
				
				if($geomtype == 'POLYGON' OR $geomtype == 'MULTIPOLYGON' OR $geomtype == 'GEOMETRY')$geomtype = 'Polygon';
				elseif($geomtype == 'POINT')$geomtype = 'Point';
				elseif($geomtype == 'MULTILINESTRING' OR $geomtype == 'LINESTRING')$geomtype = 'Line';				
				
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
				if($this->new_entry != true AND $this->formvars['printversion'] == '' AND $layer['shape'][$k]['the_geom']){
 					if($attributes['group'][0] != ''){ ?>
						<tr><td colspan="2"><table width="100%" class="tgle" border="2" cellpadding="0" cellspacing="0"><tbody class="gle">
					<? } ?>
					<tr>
						<? if($layer['querymaps'][$k] != ''){ ?>
						<td <? if($attributes['group'][0] != '')echo 'width="200px"'; ?> bgcolor="<? echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;" align="center"><img style="border:1px solid grey" src="<? echo $layer['querymaps'][$k]; ?>"></td>
						<? } else { ?>
			    	    <td <? if($attributes['group'][0] != '')echo 'width="200px"'; ?> bgcolor="<? echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;">&nbsp;</td>
			    	    <? } ?>
			    	    <td style="padding-top:5px; padding-bottom:5px;" valign="middle" colspan="19">
<?						
								echo '<input type="hidden" id="'.$layer['Layer_ID'].'_'.$columnname.'_'.$k.'" value="'.$layer['shape'][$k][$columnname].'">';						
?>								
									<table cellspacing="0" cellpadding="0">
										<tr>
	<?								if($privileg == 1 AND !$lock[$k]) { ?>
											<td style="padding: 0 0 0 10;"><a onclick="checkForUnsavedChanges(event);" title="<? echo $strEditGeom; ?>" href="index.php?go=<? echo $geomtype; ?>Editor&oid=<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>&layer_tablename=<? echo $geom_tablename; ?>&layer_columnname=<? echo $columnname; ?>&layer_id=<? echo $layer['Layer_ID'];?>&selected_layer_id=<? echo $layer['Layer_ID'];?>&dimension=<? echo $dimension; ?>"><div class="emboss edit_geom"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
	<?								} 
									if($layer['shape'][$k][$attributes['the_geom']]){ ?>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom; ?>" href="javascript:zoom2object(<? echo $layer['Layer_ID'];?>, '<? echo $geomtype; ?>', '<? echo $geom_tablename; ?>', '<? echo $columnname; ?>', '<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>', 'zoomonly');"><div class="emboss zoom_normal"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
									<? if($layer['Layer_ID'] > 0){ ?>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom.$strAndHighlight; ?>" href="javascript:zoom2object(<? echo $layer['Layer_ID'];?>, '<? echo $geomtype; ?>', '<? echo $geom_tablename; ?>', '<? echo $columnname; ?>', '<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>', 'false');"><div class="emboss zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
											<td style="padding: 0 0 0 10;"><a title="<? echo $strMapZoom.$strAndHide; ?>" href="javascript:zoom2object(<? echo $layer['Layer_ID'];?>, '<? echo $geomtype; ?>', '<? echo $geom_tablename; ?>', '<? echo $columnname; ?>', '<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>', 'true');"><div class="emboss zoom_select"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
									<? }} ?>
										</tr>
									</table>
								</td>
			        </tr>
<? } ?>
			  </tbody>
			</table>
		</td>
	</tr>
<?
	}
?>
</table>

<?

	for($l = 0; $l < count($invisible_attributes[$layer['Layer_ID']]); $l++){
		echo $invisible_attributes[$layer['Layer_ID']][$l]."\n";
	}

?>

<input type="hidden" name="checkbox_names_<? echo $layer['Layer_ID']; ?>" value="<? echo $checkbox_name; ?>">
<?
  }
  else {
  	# nix machen
  }
?>
