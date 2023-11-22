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

<td id="stellen_td_<? echo $this->stelle->id; ?>" <? if (array_key_exists('stellen_visibility', $this->formvars) AND !in_array($this->stelle->id, $this->formvars['stellen_visibility'])){echo 'style="display: none"';} ?> class="apt-main-td <? if ($this->layer[0]['used_layer_parent_id'] != '') {
			echo 'unterstelle';
			if ($this->formvars['unterstellen_ausblenden']) {
				echo ' hidden';
			}
		} ?>">
<div class="apt-main-div">
	<div class="apt-bezeichnung">
		<? if($this->stelle->id != '' AND $this->layer[0]['Name'] != ''){ ?>
		<span class="fetter px16"><? echo $this->stelle->Bezeichnung; ?></span>
		<? }elseif($this->layer[0]['Name'] != ''){ ?>
		<span class="fetter px16"><? echo $strDefaultPrivileges; ?></span>
		<? } ?>
	</div>
	<? 
		echo $parent_privileges_checkbox; 

		$layer_access_options = array(
			'0' => array(
				'output' => $strReadAndEdit,
				'color' => '#ff735a'
			),
			'1' => array(
				'output' => $strCreateNewRecords,
				'color' => '#eeee39'
			),
			'2' => array(
				'output' => $strCreateAndDelete,
				'color' => '#9ae394'
			)
		);

		$layer_export_options = array(
			'0' => array(
				'output' => $strNoExport,
				'color' => '#ff735a'
			),
			'2' => array(
				'output' => $strOnlyData,
				'color' => '#eeee39'
			),
			'1' => array(
				'output' => $strDataAndGeom,
				'color' => '#9ae394'
			)
		);
	?>
	<div class="apt-layerzugriffsrechte">
		<span class="fett"><? echo $strLayerAccessPrivileges; ?></span><br>
		<select name="privileg<? echo $this->stelle->id; ?>" style="background-color: <? echo $layer_access_options[$this->layer[0]['privileg']]['color']; ?>" onchange="this.setAttribute('style', this.options[this.selectedIndex].getAttribute('style'))" <? echo $disabled; ?>>	<?
			foreach($layer_access_options AS $value => $option) {
				$selected = (strval($this->layer[0]['privileg']) === strval($value) ? ' selected' : '');	?>
				<option value="<? echo $value; ?>" style="background-color: <? echo $option['color']; ?>" <? echo $selected; ?> ><? echo $option['output']; ?></option>	<?
			}	?>
		</select>		
	</div>
	<div class="apt-layerexportrechte">
		<span class="fett"><? echo $strLayerExportPrivileges; ?></span><br>
		<select name="export_privileg<? echo $this->stelle->id; ?>" style="background-color: <? echo $layer_export_options[$this->layer[0]['export_privileg']]['color']; ?>" onchange="this.setAttribute('style', this.options[this.selectedIndex].getAttribute('style'))" <? echo $disabled; ?>>	<?
			foreach($layer_export_options AS $value => $option) {
				$selected = (strval($this->layer[0]['export_privileg']) === strval($value) ? ' selected' : '');	?>
				<option value="<? echo $value; ?>" style="background-color: <? echo $option['color']; ?>" <? echo $selected; ?> ><? echo $option['output']; ?></option>	<?
			}	?>
		</select>		
	</div>
<? 
if ($this->layer[0]['Name'] != '' AND count($this->attributes) != 0) { ?>
	<div class="apt-attributrechte">
		<table>
			<tr>
				<td><span class="fett">Attribut</span></td>
				<td><span class="fett">Privileg</span></td>
				<td style="padding: 0"></td>
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
				'' => array(
					'output' => $strNoAccess,
					'color' => '#ff735a'
				),
				'0' => array(
					'output' => $strRead,
					'color' => '#eeee39'
				),
				'1' => array(
					'output' => $strEdit,
					'color' => '#9ae394'
				)
			);
?>
					<select class="<? echo ($this->stelle->id == ''? 'default_' : ''); ?>privileg_<? echo $this->attributes['name'][$i]; ?>" style="width:100px; background-color: <? echo $privilege_options[$this->attributes_privileges[$this->attributes['name'][$i]]]['color']; ?>" name="privileg_<? echo $this->attributes['name'][$i].'_'.$this->stelle->id; ?>" onchange="this.setAttribute('style', 'width: 100px;' + this.options[this.selectedIndex].getAttribute('style'))" <? echo $disabled; ?>>	<?
						foreach($privilege_options AS $value => $option) {
							$selected = (strval($this->attributes_privileges[$this->attributes['name'][$i]]) === strval($value) ? ' selected' : '');	?>
							<option value="<? echo $value; ?>" style="background-color: <? echo $option['color']; ?>" <? echo $selected; ?> ><? echo $option['output']; ?></option>
<?					} ?>
					</select>
				</td>
				<td style="padding: 0">
<? 		if ($this->stelle->id == '') { ?>
					<a href="javascript:set_all_for_attribute('<? echo $this->attributes['name'][$i]; ?>');" title="<? echo $strUseForAllStellen; ?>"><i class="fa fa-sign-out" style="font-size: 20px"></i></a>
<? 		} ?>
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
					<select style="width:100px" name="" onchange="set_all_for_stelle('<? echo $attributenames; ?>', '<? echo $this->stelle->id; ?>', this.value);"  <? echo $disabled; ?>>
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
