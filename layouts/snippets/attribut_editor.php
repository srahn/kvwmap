<?php
	include_once(CLASSPATH.'FormObject.php');
	global $supportedLanguages;
	global $quicksearch_layer_ids;
	include(LAYOUTPATH.'languages/attribut_editor_'.$this->user->rolle->language.'.php');
	$form_element_options = array(
		array(
			'value' => 'Text',
			'output' => 'Text',
			'title' => 'Einfaches Textfeld'
		),
		array(
			'value' => 'Zahl',
			'output' => 'Zahl',
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
			'value' => 'Autovervollständigungsfeld',
			'output' => 'Autovervollständigungsfeld',
			'title' => 'Autovervollständigungsfeld'
		),
		array(
			'value' => 'Autovervollständigungsfeld zweispaltig',
			'output' => 'zweispaltiges Autovervollständigungsfeld',
			'title' => 'Autovervollständigungsfeld getrennt durch Leerzeichen'
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
		)
	);

 ?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

<? if ((count($this->attributes))!=0) { ?>
var attributes = new Array('<? echo implode("', '", $this->attributes['name']); ?>');
<? } ?>

function update_visibility_form(visibility, attributename){
	if(visibility == 2)document.getElementById('visibility_form_'+attributename).style.display = '';
	else document.getElementById('visibility_form_'+attributename).style.display = 'none';
}

function submitLayerSelector() {
	var element = document.getElementById('selected_datatype_id');
	    element.value = '<?php echo $strPleaseSelect; ?>';
	document.GUI.submit();
}

function submitDatatypeSelector() {
	var element = document.getElementById('selected_layer_id');
	    element.value = '<?php echo $strPleaseSelect; ?>';
	document.GUI.submit();
}  

function toLayerEditor(){	
	location.href='index.php?go=Layereditor&selected_layer_id='+document.GUI.selected_layer_id.value;
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
<table cellpadding="5" cellspacing="2" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center">
    <td colspan="2"><h2><?php echo $this->titel; ?></h2></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td style="border: 1px solid #C3C7C3;">
			<?php echo $strLayer;?><br>
      <select id="selected_layer_id" style="width:250px" size="1" name="selected_layer_id" onchange="submitLayerSelector();" <?php if(count($this->layerdaten['ID'])==0){ echo 'disabled';}?>>
      <option value=""><?php echo $strPleaseSelect; ?></option>
        <?
    		for($i = 0; $i < count($this->layerdaten['ID']); $i++){
    			echo '<option';
    			if($this->layerdaten['ID'][$i] == $this->formvars['selected_layer_id']){
    				echo ' selected';
    			}
    			echo ' value="'.$this->layerdaten['ID'][$i].'">'.$this->layerdaten['Bezeichnung'][$i].'</option>';
    		}
    	?>
      </select>
			&nbsp;&nbsp;<a id="toLayerLink" href="javascript:toLayerEditor();" style="<? if($this->formvars['selected_layer_id'] != '')echo 'display:inline';else echo 'display:none'; ?>">zum Layer</a>
		</td>
    <td style="border:1px solid #C3C7C3;<? if(count($this->datatypes) == 0)echo 'display: none'; ?>">
			<?php echo $strDatatype;?><br>
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
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="2">

			<table align="center" border="0" cellspacing="0" class="attribute-editor-table"><?
				if ((count($this->attributes))!=0) { ?>
					<tr>
						<td align="center"><span class="fett">Attribut</span></td>

						<td align="center"><span class="fett">Formularelement</span></td>

						<td align="center"><span class="fett">Optionen</span></td>

						<td align="center"><span class="fett">Aliasname</span>&nbsp;<a title="aus Attributname erzeugen" href="javascript:create_aliasnames();"><img src="<? echo GRAPHICSPATH; ?>autogen.png"></a></td><?php

						foreach($supportedLanguages as $language) {
							if($language != 'german') { ?>
								<td align="center"><span class="fett">Aliasname <?php echo $language; ?></span></td><?php
							}
						} ?>

						<td align="center"><span class="fett">Erläuterungen</span></td>

						<td align="center"><span class="fett">Gruppe</span></td>

						<td align="center"><span class="fett">Anordnung</span></td>

						<td align="center"><span class="fett">Beschriftung</span></td>
						
						<td align="center"><span class="fett">Bei der Suche</span></td>

						<td align="center">
							<span	class="fett" style="cursor: pointer">F&uuml;r neuen<br>Datensatz</span>
						</td>

						<td align="center"><span class="fett">sichtbar</span></td>
						
						<?
						if (in_array($this->formvars['selected_layer_id'], $quicksearch_layer_ids)){
							$msg = "Für die Schnellsuche verwenden."; ?>
							<td align="center">
								<i class="fa fa-search" style="font-size:20px" title="<?php echo $msg; ?>"></i>
							</td>	<?
						}
						
						$msg = "Im Rastertemplate als Vorschau-Attribut verwenden."; ?>
						<td align="center">
							<i class="fa fa-windows" style="font-size:20px" title="<?php echo $msg; ?>"></i>
						</td>

					</tr><?php

					for ($i = 0; $i < count($this->attributes['type']); $i++){ ?>
						<tr>
						  <td align="left" valign="top">
						  	<input type="text"
								  name="attribute_<?php echo $this->attributes['name'][$i]; ?>"
									value="<?php echo $this->attributes['name'][$i]; ?>"
									readonly
								>
						  </td>

							<td align="left" valign="top"><?
								$type = ltrim($this->attributes['type'][$i], '_');
								if(is_numeric($type)){ ?>
									<a href="index.php?go=Attributeditor&selected_datatype_id=<?php echo $type; ?>"><?php echo $this->attributes['typename'][$i]; ?></a><?php
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

						  <td align="left" valign="top"><?php
						  if($this->attributes['options'][$i] == '' AND $this->attributes['constraints'][$i] != '' AND !in_array($this->attributes['constraints'][$i], array('PRIMARY KEY', 'UNIQUE'))) { ?>
						  	<input style="width:180px" name="options_<?php echo $this->attributes['name'][$i]; ?>" type="text" value="<?php echo $this->attributes['constraints'][$i]; ?>"><?php
						  }
						  else { ?>
								<textarea name="options_<?php echo $this->attributes['name'][$i]; ?>" style="height:22px; width:180px"><?php echo $this->attributes['options'][$i]; ?></textarea><?php
						  } ?>
						  </td>

						  <td align="left" valign="top">
						  	<input name="alias_<?php echo $this->attributes['name'][$i]; ?>" type="text" value="<?php echo htmlspecialchars($this->attributes['alias'][$i]); ?>">
						  </td>
							
							<?php
							foreach ($supportedLanguages as $language){
								if($language != 'german') { ?>
									<td align="left" valign="top">
										<input name="alias_<?php echo $language; ?>_<?php echo $this->attributes['name'][$i]; ?>" type="text" value="<?php echo htmlspecialchars($this->attributes['alias_' . $language][$i]); ?>">
									</td><?php
								}
							} ?>

						  <td align="left" valign="top">
								<textarea name="tooltip_<?php echo $this->attributes['name'][$i]; ?>" style="height:22px; width:120px"><?php echo htmlspecialchars($this->attributes['tooltip'][$i]); ?></textarea>
							</td>

							<td align="left" valign="top">
								<input name="group_<?php echo $this->attributes['name'][$i]; ?>" type="text" value="<?php echo htmlspecialchars($this->attributes['group'][$i]); ?>">
							</td>
							
							<?php
							if ($this->attributes['arrangement'][$i] == 0) { $bgcolor = 'white'; }
							if ($this->attributes['arrangement'][$i] == 1) { $bgcolor = '#faef1e'; } ?>
							<td align="center" valign="top"><?php
								echo FormObject::createSelectField(
											'arrangement_' . $this->attributes['name'][$i],
											array(
												array('value' => 0, 'output' => 'unter dem vorigen'),
												array('value' => 1, 'output' => 'neben dem vorigen', 'style' => 'background-color: #faef1e')
											),
											$this->attributes['arrangement'][$i],
											1,
											"outline: 1px solid lightgrey; border: none; width: 57px; height: 18px; background-color: " . $bgcolor,
											"this.setAttribute('style', 'outline: 1px solid lightgrey; border: none; width: 59px; height: 18px;' + this.options[this.selectedIndex].getAttribute('style'));"
										); ?>
						  </td>

							<?php
							if($this->attributes['labeling'][$i] == 0) $bgcolor = 'white';
							if($this->attributes['labeling'][$i] == 1) $bgcolor = '#faef1e';
							if($this->attributes['labeling'][$i] == 2) $bgcolor = '#ff6600'; ?>
						  <td align="center" valign="top"><?php
								echo FormObject::createSelectField(
											'labeling_' . $this->attributes['name'][$i],
											array(
												array('value' => 0, 'output' => 'links neben dem Attribut', 'style' => 'background-color: white'),
												array('value' => 1, 'output' => 'über dem Attribut', 'style' => 'background-color: #faef1e'),
												array('value' => 2, 'output' => 'ohne', 'style' => 'background-color: #ff6600')
											),
											$this->attributes['labeling'][$i],
											1,
											"outline: 1px solid lightgrey; border: none; width: 53px; height: 18px; background-color: " . $bgcolor,
											"this.setAttribute('style', 'outline: 1px solid lightgrey; border: none; width: 59px; height: 18px;' + this.options[this.selectedIndex].getAttribute('style'));"
										); ?>
							</td>
							
						  <td align="center" valign="top"><?php
								echo FormObject::createSelectField(
											'mandatory_' . $this->attributes['name'][$i],
											array(
												array('value' => -1, 'output' => 'nicht sichtbar'),
												array('value' => 0, 'output' => 'anzeigen'),
												array('value' => 1, 'output' => 'Pflichtangabe')
											),
											$this->attributes['mandatory'][$i],
											1,
											'width: 75px'
										); ?>
							</td>

							<td align="center" valign="top"><?php
								echo FormObject::createSelectField(
											'dont_use_for_new_' . $this->attributes['name'][$i],
											array(
												array('value' => -1, 'output' => 'nicht sichtbar'),
												array('value' => 0, 'output' => 'anzeigen'),
												array('value' => 1, 'output' => 'Werte nicht übernehmen')
											),
											$this->attributes['dont_use_for_new'][$i],
											1,
											'width: 75'
										); ?>
							</td>
							
							<td align="center" valign="top">
								<table style="width: 100%" cellspacing="0" cellpadding="0">
									<tr>
										<td align="left"><?
								echo FormObject::createSelectField(
											'visible_' . $this->attributes['name'][$i],
											array(
												array('value' => 0, 'output' => 'nein'),
												array('value' => 1, 'output' => 'ja'),
												array('value' => 2, 'output' => 'ja, wenn')
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
								<td align="center" valign="top">
						  		<input name="quicksearch_<?php echo $this->attributes['name'][$i]; ?>" type="checkbox" value="1"<?php echo ($this->attributes['quicksearch'][$i] ? ' checked="true"' : ''); ?>>
						  	</td><?php
							} ?>							
							
							<td align="center" valign="top">
						  	<input name="raster_visibility_<?php echo $this->attributes['name'][$i]; ?>" type="checkbox" value="1"<?php echo ($this->attributes['raster_visibility'][$i] ? ' checked="true"' : ''); ?>>
						  </td>

						</tr><?php
					}
					if(count($this->attributes) > 0 AND ($this->layer['editable'] OR $this->formvars['selected_datatype_id'])){ ?>
						<tr>
							<td align="center" colspan="19"><br><br>
								<input type="submit" name="go_plus" value="speichern">
							</td>
						</tr><?php
					}
				} ?>
			</table>

		</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" >&nbsp;</td>
  </tr>
</table>

<input type="hidden" name="go" value="Attributeditor">
