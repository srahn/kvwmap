<div style="margin-left:10px">
	<table style="border:1px solid grey">
	<?
		$layerset = $this->user->rolle->getLayer(LAYER_ID_SCHNELLSPRUNG);
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
		$layerdb = $mapdb->getlayerdatabase(LAYER_ID_SCHNELLSPRUNG, $this->Stelle->pgdbhost);
		$layerdb->setClientEncoding();
		$path = $mapdb->getPath(LAYER_ID_SCHNELLSPRUNG);
		$privileges = $this->Stelle->get_attributes_privileges(LAYER_ID_SCHNELLSPRUNG);
		$newpath = $this->Stelle->parse_path($layerdb, $path, $privileges);
		$attributes = $mapdb->read_layer_attributes(LAYER_ID_SCHNELLSPRUNG, $layerdb, $privileges['attributenames']);
		# wenn Attributname/Wert-Paare übergeben wurden, diese im Formular einsetzen
		for($i = 0; $i < count($attributes['name']); $i++){
			$qlayerset['shape'][0][$attributes['name'][$i]] = $this->formvars['value_'.$attributes['name'][$i]];
		}
		# weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
		$attributes = $mapdb->add_attribute_values($attributes, $layerdb, $qlayerset['shape'], true);	
	
		for($i = 0; $i < count($attributes['name']); $i++){
			if($attributes['name'][$i] == 'oid'){
				echo '<tr><td>&nbsp;'.$attributes['alias'][$i].':</td></tr>';
				?><tr>
					<td align="left"><?php
						 if($attributes['form_element_type'][$i] == 'Auswahlfeld'){
								?><select class="select" 
								<?
									if($attributes['req_by'][$i] != ''){
										echo 'onchange="update_require_attribute(\''.$attributes['req_by'][$i].'\','.LAYER_ID_SCHNELLSPRUNG.', this.value);" ';
									}
									if($attributes['name'][$i] == 'oid'){
										echo 'onchange="zoomto('.LAYER_ID_SCHNELLSPRUNG.', this.value, \''.$layerset[0]['maintable'].'\', \''.$attributes['the_geom'].'\');"';
									}
								?> 
									id="value_<?php echo $attributes['name'][$i]; ?>" name="value_<?php echo $attributes['name'][$i]; ?>"><?echo "\n"; ?>
										<option value="">-- <? echo $this->strPleaseSelect; ?> --</option><?php echo "\n";
										if(is_array($attributes['enum_value'][$i][0])){
											$attributes['enum_value'][$i] = $attributes['enum_value'][$i][0];
											$attributes['enum_output'][$i] = $attributes['enum_output'][$i][0];
										}
									for($o = 0; $o < count($attributes['enum_value'][$i]); $o++){
										?>
										<option <? if($this->formvars['value_'.$attributes['name'][$i]] == $attributes['enum_value'][$i][$o]){ echo 'selected';} ?> value="<?php echo $attributes['enum_value'][$i][$o]; ?>"><?php echo $attributes['enum_output'][$i][$o]; ?></option><?php echo "\n";
									} ?>
									</select>
									<input class="input" size="9" id="value2_<?php echo $attributes['name'][$i]; ?>" name="value2_<?php echo $attributes['name'][$i]; ?>" type="hidden" value="<?php echo $this->formvars['value2_'.$attributes['name'][$i]]; ?>">
									<?php
							}
							else { 
								?>
								<input class="input" size="<? if($this->formvars['value2_'.$attributes['name'][$i]] != ''){echo '9';}else{echo '24';} ?>" id="value_<?php echo $attributes['name'][$i]; ?>" name="value_<?php echo $attributes['name'][$i]; ?>" type="text" value="<?php echo $this->formvars['value_'.$attributes['name'][$i]]; ?>">
								&nbsp;<input class="input" size="9" id="value2_<?php echo $attributes['name'][$i]; ?>" name="value2_<?php echo $attributes['name'][$i]; ?>" type="<? if($this->formvars['value2_'.$attributes['name'][$i]] != ''){echo 'text';}else{echo 'hidden';} ?>" value="<?php echo $this->formvars['value2_'.$attributes['name'][$i]]; ?>">
								<?php
						 }
				 ?></td>
				</tr><?php
			}
		}
	 ?>
	 </table>
</div>