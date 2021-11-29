<?
	$disabled = '';
	$parent_privileges_checkbox = '';
	if ($this->layer[0]['used_layer_parent_id'] != ''){
		if ($this->layer[0]['use_parent_privileges']) {
			$disabled = 'disabled="true"';
		}
		$parent_privileges_checkbox = '
			<div class="apt-use_parent_privileges">
				<input id="parent' . $this->stelle->id . '" value="1" type="checkbox" onclick="toggle_privileges(this);" name="use_parent_privileges' . $this->stelle->id . '" '. ($this->layer[0]['use_parent_privileges'] ? 'checked="true"' : '') .'>
				<label for="parent' . $this->stelle->id . '" class="fett">' . $strUseParentPrivileges . '&nbsp;('.$this->layer[0]['used_layer_parent_bezeichnung'].')</label>
			</div>';
	}
?>

<td class="apt-main-td">
<div class="apt-main-div">
	<div class="apt-bezeichnung">
		<? if($this->stelle->id != '' AND $this->layer[0]['Name'] != ''){ ?>
		<span class="fetter px16"><? echo $this->stelle->Bezeichnung; ?></span>
		<? }elseif($this->layer[0]['Name'] != ''){ ?>
		<span class="fetter px16"><? echo $strDefaultPrivileges; ?></span>
		<? } ?>
	</div>
	<? echo $parent_privileges_checkbox; ?>
	<div class="apt-layerzugriffsrechte">
		<span class="fett"><? echo $strLayerAccessPrivileges; ?></span><br>
		<select name="privileg<? echo $this->stelle->id; ?>" <? echo $disabled; ?>>
			<option <? if($this->layer[0]['privileg'] == '0'){echo 'selected';} ?> value="0"><? echo $strReadAndEdit; ?></option>
			<option <? if($this->layer[0]['privileg'] == '1'){echo 'selected';} ?> value="1"><? echo $strCreateNewRecords; ?></option>
			<option <? if($this->layer[0]['privileg'] == '2'){echo 'selected';} ?> value="2"><? echo $strCreateAndDelete; ?></option>
		</select>		
	</div>
	<div class="apt-layerexportrechte">
		<span class="fett"><? echo $strLayerExportPrivileges; ?></span><br>
		<select name="export_privileg<? echo $this->stelle->id; ?>" <? echo $disabled; ?>>
			<option <? if($this->layer[0]['export_privileg'] == '0'){echo 'selected';} ?> value="0"><? echo $strNoExport; ?></option>						  			
				<option <? if($this->layer[0]['export_privileg'] == '2'){echo 'selected';} ?> value="2"><? echo $strOnlyData; ?></option>
				<option <? if($this->layer[0]['export_privileg'] == '1'){echo 'selected';} ?> value="1"><? echo $strDataAndGeom; ?></option>
		</select>		
	</div>
<? 
if ($this->layer[0]['Name'] != '' AND count($this->attributes) != 0) { ?>
	<div class="apt-attributrechte">
		<table>
			<tr>
				<td><span class="fett">Attribut</span></td>
				<td><span class="fett">Privileg</span></td>
				<td><span class="fett">Tooltip</span></td>
			</tr>
<?
	if($this->stelle->id != '' AND $this->attributes_privileges == NULL){				# zu diesem Layer und Stelle gibt es keinen Eintrag -> alle Attribute sind lesbar
		$noentry = true;
	}
	else{
		$noentry = false;
	}
	if (array_key_exists('name', $this->attributes)) {
		$attributenames = implode('|', $this->attributes['name']);
		for ($i = 0; $i < @count($this->attributes['type']); $i++){
			if ($this->stelle->id == ''){
				$this->attributes_privileges[$this->attributes['name'][$i]] = $this->attributes['privileg'][$i]; 	# die default-Rechte kommen aus layer_attributes
				$this->attributes_privileges['tooltip_'.$this->attributes['name'][$i]] = $this->attributes['query_tooltip'][$i]; 	# die default-Rechte kommen aus layer_attributes
			}
?>
			<tr>
				<td>
					<div style="height: 26px; width: 100px; position: relative">
						<div class="apt-attributname">
							<span><? echo $this->attributes['name'][$i]; ?></span>
						</div>
					</div>
				</td>
				<td>
<?
			$privilege_options = array(
				array(
					'value' => '',
					'output' => $strNoAccess,
				),
				array(
					'value' => '0',
					'output' => $strRead,
				),
				array(
					'value' => '1',
					'output' => $strEdit,
				)
			);
?>
					<select style="width:100px" name="privileg_<? echo $this->attributes['name'][$i].'_'.$this->stelle->id; ?>"  <? echo $disabled; ?>>
<?
			foreach($privilege_options AS $option) {
				$selected = ($this->attributes_privileges[$this->attributes['name'][$i]] == $option['value'] ? ' selected' : '');
?>
			<option value="<? echo $option['value']; ?>" <? echo $selected; ?> ><? echo $option['output']; ?></option>
<?			} ?>
				</select>
				</td>
				<td style="text-align: center;">
					<input type="checkbox" name="tooltip_<? echo $this->attributes['name'][$i].'_'.$this->stelle->id; ?>"&nbsp; <? echo $disabled; ?>
					<? if($this->attributes_privileges['tooltip_'.$this->attributes['name'][$i]] == 1){ echo 'checked'; } ?> >
				</td>
			</tr>			
<?		} ?>
			<tr height="50px" valign="middle">
				<td><? if($this->formvars['stelle'] != 'a'){ ?>Alle<? } ?></td>
				<td>
					<select style="width:100px" name="" onchange="set_all('<? echo $attributenames; ?>', '<? echo $this->stelle->id; ?>', this.value);"  <? echo $disabled; ?>>
						<option value=""> - <? echo $this->strChoose; ?> - </option>
						<option value=""><? echo $strNoAccess; ?></option>
						<option value="0"><? echo $strRead; ?></option>
						<option value="1"><? echo $strEdit; ?></option>
					</select>
				</td>			
			</tr>
<?
	} ?>
		</table>
	</div>
<?
	if (count($this->attributes) > 0) {
		$stelle_and_layer_selected = $this->stelle->id != '' AND $this->layer[0]['Name'] != '';
		if ($stelle_and_layer_selected ) {
			$default_stellen_ids = $this->stelle->id;
			$default_privileges_link_text = $strUseDefaultPrivileges;
			$save_stellen_ids = implode('|', $this->stellen['ID']);
		}
		else {
			$default_stellen_ids = implode('|', $this->stellen['ID']);
			$default_privileges_link_text = $strAssignDefaultPrivileges;
			$save_stellen_ids = '';
		}
		if ($stelle_and_layer_selected OR count($this->stellen['ID']) > 0) { 
?>
	<div class="apt-defaultrechteanstelle">
		<a href="javascript:get_from_default('<? echo $attributenames; ?>','<? echo $default_stellen_ids; ?>');"><? echo $default_privileges_link_text; ?></a>
	</div>
	<div class="apt-attributrechtespeichern">
		<input type="button" onclick="save('<? echo $save_stellen_ids; ?>');" name="speichern" value="<? echo $this->strSave; ?>">
	</div>
<?		} ?>
	<div class="apt-bezeichnung">
		<span class="fetter px16">
			<? if($this->stelle->id != '' AND $this->layer[0]['Name'] != ''){ echo $this->stelle->Bezeichnung; } else { echo '&nbsp;'; } ?>
		</span>
	</div>
<?	} ?>						
<? 
} else { ?>
		<div>Keine Attribute zu diesem Layer</div>
<? 
} ?>
</div>
</td>
