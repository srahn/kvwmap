<?php
	include_once(CLASSPATH.'FormObject.php');
	global $supportedLanguages;
	global $quicksearch_layer_ids;
	include(LAYOUTPATH.'languages/attribut_editor_'.$this->user->rolle->language.'.php');
	$form_element_options = array(
		array('value' => 'Time', 'output' => 'Zeitstempel', 'title' => 'Erzeugt beim Anlegen und Speichern eines Datensatzes automatisch einen Zeitstempel. Dieser läßst sich nicht ändern, auch wenn das Recht des Attributes auf Editieren gesetzt ist.'),
		array('value' => 'User', 'output' => 'Nutzer', 'title' => 'Trägt beim Anlegen und Speichern eines Datensatzes automatisch den angemeldeten Benutzernamen ein. Dieser läßt sich nicht ändern, auch wenn das Recht des Attributes auf Editieren gesetzt ist.'),
		array('value' => 'UserID', 'output' => 'NutzerID'),
		array('value' => 'Stelle', 'output' => 'Stelle'),
		array('value' => 'StelleID', 'output' => 'StelleID')
	);

 ?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--
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

						<td align="center"><span class="fett">Aliasname</span></td><?php

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

						<?php
						if (in_array($this->formvars['selected_layer_id'], $quicksearch_layer_ids)){
							$msg = "Für die Schnellsuche verwenden."; ?>
							<td align="center">
								<i class="fa fa-search" style="font-size:20px" title="<?php echo $msg; ?>"style="cursor: pointer"></i>
							</td>	<?
						}

						$msg = "In der Sachdatenanzeige sichtbar."; ?>
						<td align="center">
							<i class="fa fa-eye" style="font-size:23px" title="<?php echo $msg; ?>"style="cursor: pointer"></i>
						</td>						
						
						<? $msg = "Im Rastertemplate als Vorschau-Attribut verwenden."; ?>
						<td align="center">
							<i class="fa fa-windows" style="font-size:20px" title="<?php echo $msg; ?>"style="cursor: pointer"></i>
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
									echo '
							  	<select  style="width:130px" name="form_element_'.$this->attributes['name'][$i].'">';
							  	if($this->attributes['type'][$i] == 'geometry'){
							  		echo'<option value="Geometrie" selected>Geometrie</option>';
							  	}
							  	elseif($this->attributes['constraints'][$i] != '' AND !in_array($this->attributes['constraints'][$i], array('PRIMARY KEY', 'UNIQUE'))){
							  		echo '<option value="Auswahlfeld" selected>Auswahlfeld</option>';
							  	}
							  	else {
							  		echo '
							  		<option value="Text" ';
							  		if($this->attributes['form_element_type'][$i] == 'Text'){echo 'selected';}
							  		echo ' >Text</option>
							  		<option value="Zahl" ';
							  		if($this->attributes['form_element_type'][$i] == 'Zahl'){echo 'selected';}
							  		echo ' >Zahl</option>
							  		<option value="Textfeld" ';
							  		if($this->attributes['form_element_type'][$i] == 'Textfeld'){echo 'selected';}
							  		echo ' >Textfeld</option>
							  		<option value="Auswahlfeld" ';
							  		if($this->attributes['form_element_type'][$i] == 'Auswahlfeld'){echo 'selected';}
							  		echo ' >Auswahlfeld</option>
										<option value="Autovervollständigungsfeld" ';
							  		if($this->attributes['form_element_type'][$i] == 'Autovervollständigungsfeld'){echo 'selected';}
							  		echo ' >Autovervollständigungsfeld</option>
										<option value="Radiobutton" ';
							  		if($this->attributes['form_element_type'][$i] == 'Radiobutton'){echo 'selected';}
							  		echo ' >Radiobutton</option>
							  		<option value="Checkbox" ';
							  		if($this->attributes['form_element_type'][$i] == 'Checkbox'){echo 'selected';}
							  		echo ' >Checkbox</option>
							  		<option value="SubFormPK" ';
							  		if($this->attributes['form_element_type'][$i] == 'SubFormPK'){echo 'selected';}
							  		echo ' >SubFormPK</option>
							  		<option value="SubFormFK" ';
							  		if($this->attributes['form_element_type'][$i] == 'SubFormFK'){echo 'selected';}
							  		echo ' >SubFormFK</option>
										<option value="SubFormEmbeddedPK" ';
							  		if($this->attributes['form_element_type'][$i] == 'SubFormEmbeddedPK'){echo 'selected';}
							  		echo ' >SubFormEmbeddedPK</option>';

										foreach($form_element_options AS $option) {
											$selected = ($this->attributes['form_element_type'][$i] == $option['value'] ? ' selected' : '');
											echo '<option value="' . $option['value'] . '" title="' . $option['title'] . '"' .	$selected . '>' . $option['output'] . '</option>';
										}

							  		echo '<option value="Dokument" ';
							  		if($this->attributes['form_element_type'][$i] == 'Dokument'){echo 'selected';}
							  		echo ' >Dokument</option>
										<option value="Link" ';
							  		if($this->attributes['form_element_type'][$i] == 'Link'){echo 'selected';}
							  		echo ' >Link</option>
										<option value="dynamicLink" ';
							  		if($this->attributes['form_element_type'][$i] == 'dynamicLink'){echo 'selected';}
							  		echo ' >dynamischer Link</option>
										<option value="mailto" ';
							  		if($this->attributes['form_element_type'][$i] == 'mailto'){echo 'selected';}
							  		echo ' >MailTo</option>
										<option value="Fläche" ';
							  		if($this->attributes['form_element_type'][$i] == 'Fläche'){echo 'selected';}
							  		echo ' >Fläche</option>
							  		<option value="Länge" ';
							  		if($this->attributes['form_element_type'][$i] == 'Länge'){echo 'selected';}
							  		echo ' >Länge</option>
										<option value="Winkel" ';
							  		if($this->attributes['form_element_type'][$i] == 'Winkel'){echo 'selected';}
							  		echo ' >Winkel</option>
										<option value="Style"' . ($this->attributes['form_element_type'][$i] == 'Style' ? ' selected' : '') . '>Style</option>';
							  	}
							  	echo'
							  	</select>';
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
						  	<input name="alias_<?php echo $this->attributes['name'][$i]; ?>" type="text" value="<?php echo $this->attributes['alias'][$i]; ?>">
						  </td>
							
							<?php
							foreach ($supportedLanguages as $language){
								if($language != 'german') { ?>
									<td align="left" valign="top">
										<input name="alias_<?php echo $language; ?>_<?php echo $this->attributes['name'][$i]; ?>" type="text" value="<?php echo $this->attributes['alias_' . $language][$i]; ?>">
									</td><?php
								}
							} ?>

						  <td align="left" valign="top">
								<textarea name="tooltip_<?php echo $this->attributes['name'][$i]; ?>" style="height:22px; width:120px"><?php echo htmlspecialchars($this->attributes['tooltip'][$i]); ?></textarea>
							</td>

							<td align="left" valign="top">
								<input name="group_<?php echo $this->attributes['name'][$i]; ?>" type="text" value="<?php echo $this->attributes['group'][$i]; ?>">
							</td>
							
							<?php
							if ($this->attributes['arrangement'][$i] == 0) { $bgcolor = 'white'; }
							if ($this->attributes['arrangement'][$i] == 1) { $bgcolor = '#faef1e'; } ?>
							<td align="center" valign="top"><?php
								echo FormObject::createSelectField(
											'arrangement_' . $this->attributes['name'][$i],
											array(
												array('value' => 0, 'output' => 'unter dem vorigen'),
												array('value' => 1, 'output' => 'neben dem vorigen')
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
											'width: 80px'
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
											'width: 80px'
										); ?>
							</td>

							<?php
							if (in_array($this->formvars['selected_layer_id'], $quicksearch_layer_ids)) { ?>
								<td align="center" valign="top">
						  		<input name="quicksearch_<?php echo $this->attributes['name'][$i]; ?>" type="checkbox" value="1"<?php echo ($this->attributes['quicksearch'][$i] ? ' checked="true"' : ''); ?>>
						  	</td><?php
							} ?>							
							
							<td align="center" valign="top">
								<input type="checkbox" value="1" name="visible_<? echo $this->attributes['name'][$i]; ?>" <? echo ($this->attributes['visible'][$i] ? ' checked="true"' : ''); ?>>
							</td>							
							
							<td align="center" valign="top">
						  	<input name="raster_visibility_<?php echo $this->attributes['name'][$i]; ?>" type="checkbox" value="1"<?php echo ($this->attributes['raster_visibility'][$i] ? ' checked="true"' : ''); ?>>
						  </td>

						</tr><?php
					}
					if (count($this->attributes) > 0){ ?>
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
