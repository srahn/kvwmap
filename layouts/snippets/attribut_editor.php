<?php
	include_once(CLASSPATH.'FormObject.php');
	global $supportedLanguages;
	global $quicksearch_layer_ids;
	include(LAYOUTPATH.'languages/layer_formular_'.$this->user->rolle->language.'.php');
	include(LAYOUTPATH.'languages/attribut_editor_'.$this->user->rolle->language.'.php');
	$form_element_options = array(
		array(
			'value' => 'Text',
			'output' => 'Eingabefeld',
			'title' => 'Einfaches einzeiliges Eingabefeld'
		),
		array(
			'value' => 'Zahl',
			'output' => 'Zahl mit Tausendertrennzeichen',
			'title' => 'Zahlenfeld'
		),
		array(
			'value' => 'Textfeld',
			'output' => 'Textfeld',
			'title' => 'Textfeld mit mehreren Zeilen'
		),
		array(
			'value' => 'Auswahlfeld',
			'output' => 'Auswahlfeld',
			'title' => 'Auswahlfeld'
		),
		array(
			'value' => 'Auswahlfeld_Bild',
			'output' => 'Auswahlfeld mit Bild',
			'title' => 'Auswahlfeld mit Bild'
		),		
		array(
			'value' => 'Autovervollständigungsfeld',
			'output' => 'Autovervollständigungsfeld',
			'title' => 'Autovervollständigungsfeld'
		),
		array(
			'value' => 'Autovervollständigungsfeld_zweispaltig',
			'output' => 'zweispaltiges Autovervollständigungsfeld',
			'title' => 'Autovervollständigungsfeld getrennt durch Leerzeichen'
		),
		array(
			'value' => 'Farbauswahl',
			'output' => 'Farbauswahl',
			'title' => 'Farbauswahl'
		),		
		array(
			'value' => 'Radiobutton',
			'output' => 'Radiobutton',
			'title' => 'Radiobutton'
		),
		array(
			'value' => 'Checkbox',
			'output' => 'Checkbox',
			'title' => 'Checkbox'
		),
		array(
			'value' => 'SubFormPK',
			'output' => 'SubFormPK',
			'title' => 'Unterformular verbunden über Primärschlüssel'
		),
		array(
			'value' => 'SubFormFK',
			'output' => 'SubFormFK',
			'title' => 'Unterformular verbunden über Fremdschlüssel'
		),
		array(
			'value' => 'SubFormEmbeddedPK',
			'output' => 'SubFormEmbeddedPK',
			'title' => 'Eingebettetes Unterformular verbunden über Primärschlüssel'
		),
		array(
			'value' => 'Time',
			'output' => 'Zeitstempel',
			'title' => 'Erzeugt beim Anlegen und Speichern eines Datensatzes automatisch einen Zeitstempel. Dieser läßst sich nicht ändern, auch wenn das Recht des Attributes auf Editieren gesetzt ist.'
		),
		array(
			'value' => 'User',
			'output' => 'Nutzer',
			'title' => 'Trägt beim Anlegen und Speichern eines Datensatzes automatisch den angemeldeten Benutzernamen ein. Dieser läßt sich nicht ändern, auch wenn das Recht des Attributes auf Editieren gesetzt ist.'
		),
		array(
			'value' => 'UserID',
			'output' => 'NutzerID'
		),
		array(
			'value' => 'Stelle',
			'output' => 'Stelle'
		),
		array(
			'value' => 'StelleID',
			'output' => 'StelleID'
		),
		array(
			'value' => 'Dokument',
			'output' => 'Dokument',
			'title' => 'Dokument'
		),
		array(
			'value' => 'Link',
			'output' => 'Link',
			'title' => 'Link'
		),
		array(
			'value' => 'dynamicLink',
			'output' => 'dynamicLink',
			'title' => 'dynamischer Link'
		),
		array(
			'value' => 'mailto',
			'output' => 'mailto',
			'title' => 'MailTo'
		),
		array(
			'value' => 'Fläche',
			'output' => 'Fläche',
			'title' => 'Fläche'
		),
		array(
			'value' => 'Länge',
			'output' => 'Länge',
			'title' => 'Länge'
		),
		array(
			'value' => 'Winkel',
			'output' => 'Winkel',
			'title' => 'Winkel'
		),
		array(
			'value' => 'Style',
			'output' => 'Style',
			'title' => 'Style'
		),
		array(
			'value' => 'Editiersperre',
			'output' => 'Editiersperre',
			'title' => 'Sperrt die Möglichkeit zum Editieren, wenn das Attribut den Wert 1 hat.'
		),
		array(
			'value' => 'ExifLatLng',
			'output' => 'Exif-Koordinate',
			'title' => 'Übernimmt die LatLng-Koordinaten beim Upload des Fotos aus dem Exif-Header falls vorhanden. (Format: Latitude Longitude Dezimal)'
		),
		array(
			'value' => 'ExifRichtung',
			'output' => 'Exif-Richtung',
			'title' => 'Übernimmt die Richtung beim Upload des Fotos aus dem Exif-Header falls vorhanden. (Format: Float Dezimal)'
		),
		array(
			'value' => 'ExifErstellungszeit',
			'output' => 'Exif-Erstellungszeit',
			'title' => 'Übernimmt den Zeitstempel beim Upload des Fotos aus dem Exif-Header falls vorhanden. (Format: YYYY-MM-DD hh:mm:ss)'
		)
	);

 ?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--
var attributes = new Array(<? echo (@count($this->attributes['name']) == 0 ? "" : "'" . implode("', '", $this->attributes['name']) . "'"); ?>);

function update_visibility_form(visibility, attributename){
	if(visibility == 2)document.getElementById('visibility_form_'+attributename).style.display = '';
	else document.getElementById('visibility_form_'+attributename).style.display = 'none';
}

function submitLayerSelector() {
	var element = document.getElementById('selected_datatype_id');
	if(element != undefined)element.value = '<?php echo $strPleaseSelect; ?>';
	document.GUI.submit();
}

function submitDatatypeSelector() {
	var element = document.getElementById('selected_layer_id');
	    element.value = '<?php echo $strPleaseSelect; ?>';
	document.GUI.submit();
}  

function toLayerEditor(){	
	location.href = 'index.php?go=Layereditor&selected_layer_id=' + document.GUI.selected_layer_id.value + '&csrf_token=<? echo $_SESSION['csrf_token']; ?>';
}

function create_aliasnames(){
	for(i = 0; i < attributes.length; i++){
		attribute_field = document.getElementsByName('attribute_'+attributes[i])[0];
		alias_field = document.getElementsByName('alias_'+attributes[i])[0];
		if(alias_field.value == '')alias_field.value = alias_replace(attribute_field.value);
	}
}

function alias_replace(name){
	lowercase_words = new Array('der', 'die', 'das', 'von', 'bis', 'zu', 'hat', 'gueltig');
	name = name.replace(/_/g, ' ');
	var words = name.split(' ');
	for(var i = 0; i < words.length; i++){
		if(lowercase_words.indexOf(words[i]) == -1){
			words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1);
		}
		if(words[i] == 'Id')words[i] = 'ID';
  }
	name = words.join(' ');
	return name;
}

//-->
</script>

<style>
	.navigation{
		border-collapse: collapse; 
		width: 100%;
		min-width: 940px;
		background:rgb(248, 248, 249);
	}

	.navigation th{
		border: 1px solid #bbb;
		border-collapse: collapse;
		width: 17%;
	}
	
	.navigation th div{
		padding: 3px;
		padding: 9px 0 9px 0;
	}	
	
	.navigation th a{
		color: #888;
	}	
	
	.navigation th:hover{
		background-color: rgb(238, 238, 239);
		color: #666;
	}
</style>

<table style="width: 700px; margin: 15px 40px 0 40px">
	<tr>
    <td align="center">
			<span class="px17 fetter"><? echo $strLayer;?>:</span>
      <select id="selected_layer_id" style="width:250px" size="1" name="selected_layer_id" onchange="submitLayerSelector();" <?php if(count($this->layerdaten['ID'])==0){ echo 'disabled';}?>><?
			$layer_options = array(); ?>
      <option value=""><?php echo $strPleaseSelect; ?></option><?
				for ($i = 0; $i < count($this->layerdaten['ID']); $i++) {
					$layer_options[] = array('value' => $this->layerdaten['ID'][$i], 'output' => $this->layerdaten['Bezeichnung'][$i]);
    			echo '<option';
    			if($this->layerdaten['ID'][$i] == $this->formvars['selected_layer_id']){
    				echo ' selected';
    			}
    			echo ' value="'.$this->layerdaten['ID'][$i].'">' . $this->layerdaten['Bezeichnung'][$i] . ($this->layerdaten['alias'][$i] != '' ? ' [' . $this->layerdaten['alias'][$i] . ']' : '') . '</option>';
    		}
    	?>
      </select>
		</td>
		<? if($this->formvars['selected_layer_id'] == '' AND count($this->datatypes) > 0){ ?>
    <td style="padding-left: 40px">
			<span class="px17 fetter"><? echo $strDatatype;?>:</span>
      <select id="selected_datatype_id" style="width:250px" size="1"  name="selected_datatype_id" onchange="submitDatatypeSelector();" <?php if(count($this->datatypes)==0){ echo 'disabled';}?>>
      <option value=""><?php echo $strPleaseSelect; ?></option>
        <?
    		for($i = 0; $i < count($this->datatypes); $i++){
    			echo '<option';
    			if($this->datatypes[$i]['id'] == $this->formvars['selected_datatype_id']){
    				echo ' selected';
    			}
    			echo ' value="' . $this->datatypes[$i]['id'] . '">' . $this->datatypes[$i]['name'] . '</option>';
    		}
    	?>
      </select>
		</td>
		<? } ?>
  </tr>
</table>

<? if($this->formvars['selected_layer_id'] != ''){ ?>

<table border="0" cellpadding="0" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>" style="margin: 10px">
	<tr align="center"> 
		<td style="width: 100%;">
			<table cellpadding="0" cellspacing="0" class="navigation">
				<tr>
					<th><a href="index.php?go=Layereditor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div style="width: 100%"><? echo $strCommonData; ?></div></a></th>
					<th><a href="index.php?go=Klasseneditor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div style="width: 100%"><? echo $strClasses; ?></div></a></th>
					<th><a href="index.php?go=Style_Label_Editor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div style="width: 100%"><? echo $strStylesLabels; ?></div></a></th>
					<th><a href="index.php?go=Attributeditor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div style="background-color: #c7d9e6; color: #111; width: 100%"><? echo $strAttributes; ?></div></a></th>
					<th><a href="index.php?go=Layereditor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&stellenzuweisung=1&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div style="width: 100%"><? echo $strStellenAsignment; ?></div></a></th>
					<th><a href="index.php?go=Layerattribut-Rechteverwaltung&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div style="width: 100%"><? echo $strPrivileges; ?></div></a></th>
				</tr>
			</table>
		</td>
	</tr>	
</table>

<? }
	if($this->formvars['selected_layer_id'] != '' OR $this->formvars['selected_datatype_id']){ ?>

<table style="position: relative; display: block; max-width: 1670px; overflow-x: auto;" cellpadding="5" cellspacing="2" bgcolor="#f8f8f9">
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="2">

			<table align="center" border="0" cellspacing="0" class="scrolltable attribute-editor-table">
				<tbody style="max-height: <? echo ($this->user->rolle->nImageHeight - 120); ?>px">
		<?	if ((count($this->attributes))!=0) { 
					for ($i = 0; $i < @count($this->attributes['type']); $i++){ ?>
						<tr>
							<td align="left" valign="top">
								<? if($i == 0)echo '<div class="fett scrolltable_header" title="Reihenfolge">#</div>'; ?>
						  	<input type="text"
								  name="order_<?php echo $this->attributes['name'][$i]; ?>"
									value="<?php echo $this->attributes['order'][$i]; ?>"
									style="width: 27px"
								>
						  </td>
							<td align="left" valign="top"><?
								if ($i == 0) {
									echo '<div class="fett scrolltable_header">' . $strAttributes .'</div>';
								} ?>
								<input type="text"
									name="attribute_<?php echo $this->attributes['name'][$i]; ?>"
									value="<?php echo $this->attributes['name'][$i]; ?>"
									readonly
								>
						  </td>

							<td align="left" valign="top">
								<? if($i == 0)echo '<div class="fett scrolltable_header">' . $strFormularElement . '</div>';
								$type = ltrim($this->attributes['type'][$i], '_');
								if(is_numeric($type)){ ?>
									<a href="index.php?go=Attributeditor&selected_datatype_id=<?php echo $type; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?php echo $this->attributes['typename'][$i]; ?></a><?php
								}
								else {
									echo '<select style="width:130px" name="form_element_' . $this->attributes['name'][$i] . '">';
									if ($this->attributes['type'][$i] == 'geometry') {
										echo '<option value="Geometrie" selected>Geometrie</option>';
									}
									elseif ($this->attributes['constraints'][$i] != '' AND !in_array($this->attributes['constraints'][$i], array('PRIMARY KEY', 'UNIQUE'))) {
										echo '<option value="Auswahlfeld" selected>Auswahlfeld</option>';
									}
									else {
										foreach ($form_element_options AS $option) {
											$selected = ($this->attributes['form_element_type'][$i] == $option['value'] ? ' selected' : '');
											echo '<option value="' . $option['value'] . '" title="' . $option['title'] . '"' .	$selected . '>' . $option['output'] . '</option>';
										}
									}
									echo'</select>';
								} ?>
							</td>

							<td align="left" valign="top"><?
								if ($i == 0) {
									echo '<div class="fett scrolltable_header">' . $this->layerOptions . '</div>';
								}
								if (
									$this->attributes['options'][$i] == '' AND
									$this->attributes['constraints'][$i] != '' AND
									!in_array($this->attributes['constraints'][$i], array('PRIMARY KEY', 'UNIQUE'))
								) {
									$this->attributes['options'][$i] = $this->attributes['constraints'][$i];
								} ?>
								<textarea name="options_<?php echo $this->attributes['name'][$i]; ?>" style="height:22px; width:180px"><?php echo $this->attributes['options'][$i]; ?></textarea>
						  </td>

						  <td align="left" valign="top">
								<? if($i == 0)echo '<div class="fett scrolltable_header">' . $strAlias . '&nbsp;<a title="aus Attributname erzeugen" href="javascript:create_aliasnames();"><img src="graphics/autogen.png"></a></div>'; ?>
						  	<input name="alias_<?php echo $this->attributes['name'][$i]; ?>" type="text" value="<?php echo htmlspecialchars($this->attributes['alias'][$i]); ?>">
						  </td>
							
							<?php
							foreach ($supportedLanguages as $language){
								if($language != 'german') { ?>
									<td align="left" valign="top"><?
										if ($i == 0) {
											echo '<div class="fett scrolltable_header">' . $strAlias . ' ' . $language . '</div>';
										} ?>
										<input name="alias_<?php echo $language; ?>_<?php echo $this->attributes['name'][$i]; ?>" type="text" value="<?php echo htmlspecialchars($this->attributes['alias_' . $language][$i]); ?>">
									</td><?php
								}
							} ?>

							<td align="left" valign="top"><?
								if ($i == 0) {
									echo '<div class="fett scrolltable_header">' . $strAttributExplanations . '</div>';
								} ?>
								<textarea name="tooltip_<?php echo $this->attributes['name'][$i]; ?>" style="height:22px; width:120px"><?php echo htmlspecialchars($this->attributes['tooltip'][$i]); ?></textarea>
							</td>

							<td align="left" valign="top"><?
								if ($i == 0) {
									echo '<div class="fett scrolltable_header">' . $this->strGroup . '</div>';
								} ?>
								<input name="group_<?php echo $this->attributes['name'][$i]; ?>" type="text" value="<?php echo htmlspecialchars($this->attributes['group'][$i]); ?>">
							</td>
							
							<td align="left" valign="top">
								<? if($i == 0)echo '<div class="fett scrolltable_header">Tab</div>'; ?>
								<input name="tab_<?php echo $this->attributes['name'][$i]; ?>" type="text" value="<?php echo htmlspecialchars($this->attributes['tab'][$i]); ?>">
							</td>							
							
							<?php
							if ($this->attributes['arrangement'][$i] == 0) { $bgcolor = 'white'; }
							if ($this->attributes['arrangement'][$i] == 1) { $bgcolor = '#faef1e'; } ?>
							<td align="center" valign="top"><?
								if ($i == 0) {
									echo '<div class="fett scrolltable_header">' . $strArrangement . '</div>';
								}
								echo FormObject::createSelectField(
									'arrangement_' . $this->attributes['name'][$i],
									array(
										array('value' => 0, 'output' => $strUnderPrevious),
										array('value' => 1, 'output' => $strBesidePrevious, 'style' => 'background-color: #faef1e')
									),
									$this->attributes['arrangement'][$i],
									1,
									"outline: 1px solid lightgrey; border: none; width: 85px; height: 18px; background-color: " . $bgcolor,
									"this.setAttribute('style', 'outline: 1px solid lightgrey; border: none; width: 59px; height: 18px;' + this.options[this.selectedIndex].getAttribute('style'));"
								); ?>
						  </td>

							<?php
							if($this->attributes['labeling'][$i] == 0) $bgcolor = 'white';
							if($this->attributes['labeling'][$i] == 1) $bgcolor = '#faef1e';
							if($this->attributes['labeling'][$i] == 2) $bgcolor = '#ff6600'; ?>
							<td align="center" valign="top"><?
								if ($i == 0) {
									echo '<div class="fett scrolltable_header">' . $strAttributeLabeling . '</div>';
								}
								echo FormObject::createSelectField(
									'labeling_' . $this->attributes['name'][$i],
									array(
										array('value' => 0, 'output' => $strLeftBesideAttribute, 'style' => 'background-color: white'),
										array('value' => 1, 'output' => $strAboveAttribute, 'style' => 'background-color: #faef1e'),
										array('value' => 2, 'output' => $strWithoutLabel, 'style' => 'background-color: #ff6600')
									),
									$this->attributes['labeling'][$i],
									1,
									"outline: 1px solid lightgrey; border: none; width: 88px; height: 18px; background-color: " . $bgcolor,
									"this.setAttribute('style', 'outline: 1px solid lightgrey; border: none; width: 59px; height: 18px;' + this.options[this.selectedIndex].getAttribute('style'));"
								); ?>
							</td>
							
							<td align="center" valign="top"><?
								if($i == 0) {
									echo '<div class="fett scrolltable_header">' . $strAttributeAtSearch . '</div>';
								}
								echo FormObject::createSelectField(
									'mandatory_' . $this->attributes['name'][$i],
									array(
										array('value' => -1, 'output' => $strAttributeNotVisible),
										array('value' => 0, 'output' => $strShowAttribute),
										array('value' => 1, 'output' => $strMandatoryAtSearch)
									),
									$this->attributes['mandatory'][$i],
									1,
									'width: 75px'
								); ?>
							</td>

							<td align="center" valign="top"><?
								if ($i == 0) {
									echo '<div style="margin-top: -9px;" class="fett scrolltable_header">' . $strForNewDataset . '</div>';
								}
								echo FormObject::createSelectField(
									'dont_use_for_new_' . $this->attributes['name'][$i],
									array(
										array('value' => -1, 'output' => $strAttributeNotVisible),
										array('value' => 0, 'output' => $strShowAttribute),
										array('value' => 1, 'output' => $strOmitAttributeValues)
									),
									$this->attributes['dont_use_for_new'][$i],
									1,
									'width: 75'
								); ?>
							</td>
							
							<td align="center" valign="top"><?
								if ($i == 0) {
									echo '<div class="fett scrolltable_header">' . $strAttributeVisible . '</div>';
								} ?>
								<table style="width: 100%" cellspacing="0" cellpadding="0">
									<tr>
										<td align="left"><?
											echo FormObject::createSelectField(
												'visible_' . $this->attributes['name'][$i],
												array(
													array('value' => 0, 'output' => $this->strNo),
													array('value' => 1, 'output' => $this->strYes),
													array('value' => 2, 'output' => $strYesWhen)
												),
												$this->attributes['visible'][$i],
												1,
												'width: 75px',
												'update_visibility_form(this.value, \''.$this->attributes['name'][$i].'\')'
											); ?>
										</td>
										<td id="visibility_form_<? echo $this->attributes['name'][$i]; ?>" style="<? echo ($this->attributes['visible'][$i] == 2 ? '' : 'display:none') ?>">
											<table style="width: 100%" cellspacing="0" cellpadding="0">
												<tr>
													<td><?
														echo FormObject::createSelectField(
															'vcheck_attribute_' . $this->attributes['name'][$i],
															$this->attributes['name'],
															$this->attributes['vcheck_attribute'][$i],
															1
														); ?>
													</td>
													<td><?
														echo FormObject::createSelectField(
															'vcheck_operator_' . $this->attributes['name'][$i],
															array('=', '!=', '<', '>', 'IN'),
															$this->attributes['vcheck_operator'][$i],
															1,
															'width: 35px'
														); ?>
													</td>
													<td>
														<input type="text" style="width: 60px" name="vcheck_value_<? echo $this->attributes['name'][$i]; ?>" value="<? echo htmlentities($this->attributes['vcheck_value'][$i]); ?>">
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>	

							<?php
							if (in_array($this->formvars['selected_layer_id'], $quicksearch_layer_ids)) { ?>
								<td align="center" valign="top"><?
									if ($i == 0) {
										echo '<div class="fett scrolltable_header"><i class="fa fa-search" style="font-size:20px" title="' . $strUseForQuickSearchTitle .'"></i></div>';
									} ?>
						  		<input name="quicksearch_<?php echo $this->attributes['name'][$i]; ?>" type="checkbox" value="1"<?php echo ($this->attributes['quicksearch'][$i] ? ' checked="true"' : ''); ?>>
						  	</td><?php
							} ?>
							
							<td align="center" valign="top"><?
								if ($i == 0) {
									echo '<div class="fett scrolltable_header"><i class="fa fa-th" style="font-size:23px" title="' . $strUseInRasterTemplate . '"></i></div>';
								} ?>
								<input name="raster_visibility_<?php echo $this->attributes['name'][$i]; ?>" type="checkbox" value="1"<?php echo ($this->attributes['raster_visibility'][$i] ? ' checked="true"' : ''); ?>>
						  </td>
							<td>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							</td>

						</tr><?php
					}
				} ?>
				</tbody>
			</table>

		</td>
  </tr>
	<?
		if(count($this->attributes) > 0 AND ($this->layer['editable'] OR $this->formvars['selected_datatype_id'])){ ?>
			<tr>
				<td align="center" style="height: 50px">
					<input id="attribut_editor_save" type="submit" name="go_plus" value="speichern"><?
					echo FormObject::createSelectField(
						'for_attributes_selected_layer_id',
						$layer_options,
						'',
						1,
						'display: none;',
						'',
						'',
						'',
						'',
						$strPleaseSelect
					); ?>
					<input id="attributes_for_other_layer_button" style="display: none; margin-left: 10px" type="submit" name="go_plus" value="Attributeinstellungen für ausgewählten Layer übernehmen">
					<span style="margin-left: 10px;">
					<i
						id="show_attributes_for_other_layer_button"
						title="Magische Funktion um die Attributeinstellungen auf gleich benannte Attribute eines anderen Layers zu übertragen. Vorgenommene Änderungen müssen vorher gespeichert werden!"
						class="fa fa-magic"
						aria-hidden="true"
						onclick="$('#attributes_for_other_layer_button, #for_attributes_selected_layer_id, #attribut_editor_save, #show_attributes_for_other_layer_button, #close_attributes_for_other_layer_button').toggle();"
					></i>
					<i
						id="close_attributes_for_other_layer_button"
						title="Den Spuk wieder schließen."
						style="display: none;" class="fa fa-times"
						aria-hidden="true"
						onclick="$('#attributes_for_other_layer_button, #for_attributes_selected_layer_id, #attribut_editor_save, #show_attributes_for_other_layer_button, #close_attributes_for_other_layer_button').toggle();"
					></i>
				</td>
			</tr><?php
		}	
	?>
</table>

<? } ?>

<input type="hidden" name="go" value="Attributeditor">