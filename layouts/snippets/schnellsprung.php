<?
	$layerset = $this->user->rolle->getLayer(LAYER_ID_SCHNELLSPRUNG);
	if($layerset != NULL){ ?>

<div id="schnellsprung_div">
	<?
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
		$layerdb = $mapdb->getlayerdatabase(LAYER_ID_SCHNELLSPRUNG, $this->Stelle->pgdbhost);
		$path = $mapdb->getPath(LAYER_ID_SCHNELLSPRUNG);
		$privileges = $this->Stelle->get_attributes_privileges(LAYER_ID_SCHNELLSPRUNG);
		$attributes = $mapdb->read_layer_attributes(LAYER_ID_SCHNELLSPRUNG, $layerdb, $privileges['attributenames']);
		# wenn Attributname/Wert-Paare �bergeben wurden, diese im Formular einsetzen
		for($i = 0; $i < count($attributes['name']); $i++){
			$qlayerset['shape'][0][$attributes['name'][$i]] = $this->formvars['value_'.$attributes['name'][$i]];
		}
		# weitere Informationen hinzuf�gen (Auswahlm�glichkeiten, usw.)
		$attributes = $mapdb->add_attribute_values($attributes, $layerdb, $qlayerset['shape'], true, $this->Stelle->id);	
	
		for($i = 0; $i < count($attributes['name']); $i++){
			if($attributes['name'][$i] == 'oid'){
				echo $attributes['alias'][$i] . ':';
				if($attributes['form_element_type'][$i] == 'Auswahlfeld'){
					?><select class="schnellsprung-select-field" 
					<?
						if($attributes['req_by'][$i] != ''){
							echo 'onchange="update_require_attribute(\''.$attributes['req_by'][$i].'\','.LAYER_ID_SCHNELLSPRUNG.', this.value);" ';
						}
						if($attributes['name'][$i] == 'oid'){
							echo 'onchange="zoomto('.LAYER_ID_SCHNELLSPRUNG.', this.value, \''.$attributes['the_geom'].'\');"';
						}
					?> 
						id="value_<?php echo $attributes['name'][$i]; ?>" name="value_<?php echo $attributes['name'][$i]; ?>"><?echo "\n"; ?>
							<option value="">-- <? echo $this->strPleaseSelect; ?> --</option><?php echo "\n";
							if (is_array($attributes['enum'][$i][0])){
								$attributes['enum'][$i] = $attributes['enum'][$i][0];
							}
						foreach ($attributes['enum'][$i] as $enum_key => $enum){
							?>
							<option <? 
								if ($this->formvars['value_'.$attributes['name'][$i]] == $enum_key) {
									echo 'selected';
								} ?> 
								value="<?php echo $enum_key; ?>"><?php echo $enum['output']; ?>
							</option><?php echo "\n";
						} ?>
						</select>
						<input size="9" id="value2_<?php echo $attributes['name'][$i]; ?>" name="value2_<?php echo $attributes['name'][$i]; ?>" type="hidden" value="<?php echo $this->formvars['value2_'.$attributes['name'][$i]]; ?>">
						<?php
				}
				else { 
					?>
					<input size="<? if($this->formvars['value2_'.$attributes['name'][$i]] != ''){echo '9';}else{echo '24';} ?>" id="value_<?php echo $attributes['name'][$i]; ?>" name="value_<?php echo $attributes['name'][$i]; ?>" type="text" value="<?php echo $this->formvars['value_'.$attributes['name'][$i]]; ?>">
					&nbsp;<input size="9" id="value2_<?php echo $attributes['name'][$i]; ?>" name="value2_<?php echo $attributes['name'][$i]; ?>" type="<? if($this->formvars['value2_'.$attributes['name'][$i]] != ''){echo 'text';}else{echo 'hidden';} ?>" value="<?php echo $this->formvars['value2_'.$attributes['name'][$i]]; ?>">
					<?php
				}
			}
		}
	 ?>
</div>
<? } ?>