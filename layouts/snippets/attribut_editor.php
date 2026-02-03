<?php
	include_once(CLASSPATH.'FormObject.php');
	global $supportedLanguages;
	global $quicksearch_layer_ids;
	include(LAYOUTPATH.'languages/layer_formular_'.rolle::$language.'.php');
	include(LAYOUTPATH.'languages/attribut_editor_'.rolle::$language.'.php');
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
			'output' => 'StelleID',
			'title' => 'Trägt beim Anlegen und Speichern eines Datensatzes automatisch die ID der Stelle ein in der der Nutzer gerade angemeldet ist. Dieser läßt sich nicht ändern, auch wenn das Recht des Attributes auf Editieren gesetzt ist.'
		),
		array(
			'value' => 'ClientID',
			'output' => 'ClientID',
			'title' => 'Trägt beim Anlegen und Speichern eines Datensatzes automatisch die ID des Gerätes ein welches der Nutzer gerade benutzt. Wenn der Nutzer mit kvmobile arbeitet wird die Device-ID des Gerätes eingetragen. Arbeit der Nutzer im Browser bleibt die ID leer.'
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
var attributes = new Array(<? echo (count_or_0($this->attributes['name'] ?: []) == 0 ? "" : "'" . implode("', '", $this->attributes['name']) . "'"); ?>);

function takeover_attributes(){
	if (document.getElementById('for_attributes_selected_layer_id').value == '') {
		message('Bitte wählen Sie einen Layer aus, für den die Attribute übernommen werden sollen.');
	}
	else {
		document.GUI.go.value = 'Attributeditor_Attributeinstellungen für ausgewählten Layer übernehmen';
		document.GUI.submit();
	}
}

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

function clear_all(column){
	for(i = 0; i < attributes.length; i++){
		field = document.getElementsByName(column + '_'+attributes[i])[0];
		field.value = '';
	}
}

function set_all(column){
	for(i = 0; i < attributes.length; i++){
		field = document.getElementsByName(column + '_'+attributes[i])[0];
		field.value = document.getElementById(column).value;
		if (field.onchange) {
			field.onchange();
		}
	}
}



// ----------------- Visibility Rules ----------------------
let visibilityRules = [];
<?	for ($i = 0; $i < count_or_0($this->attributes['type']); $i++) { 
			if ($this->attributes['visibility_rules'][$i]) { ?>
				visibilityRules[<? echo $i; ?>] = JSON.parse('<? echo $this->attributes['visibility_rules'][$i]; ?>');
<?		}
		} ?>

const operators = ['=', '!=', '<', '>', 'IN'];

function syncHiddenField(i) {
  const input = document.getElementById('visibilityRulesInput_' + i);
  if(input) input.value = JSON.stringify(visibilityRules[i]);
}

// --------------------
// Rekursiver Renderer
// --------------------
function renderNode(node, i, parent=null, index=null){
  if(node.rules) return renderGroup(node, i, parent, index);
  return renderRule(node, i, parent, index);
}

function renderGroup(group, i, parent=null, index=null){
  const div = document.createElement('div');
  div.className = 'group';

  // Logic
  const logicSelect = document.createElement('select');
  ['AND','OR'].forEach(op => {
    const option = document.createElement('option');
    option.textContent = option.value = op;
    if(group.logic===op) option.selected=true;
    logicSelect.appendChild(option);
  });
  logicSelect.onchange = () => { group.logic = logicSelect.value; syncHiddenField(i); };
  div.appendChild(logicSelect);

  // Kinder
  group.rules.forEach((child, idx) => {
    const childEl = renderNode(child, i, group, idx);
    childEl.classList.add('child');
    div.appendChild(childEl);
  });

  // Buttons
  div.appendChild(createAddRuleButton(group, i));
  div.appendChild(createAddGroupButton(group, i));

  // Delete-Button für Gruppe, außer Root
  if(parent){
    const delBtn = document.createElement('button');
    delBtn.type='button';
    delBtn.textContent='Löschen';
    delBtn.onclick = () => { parent.rules.splice(index,1); render(i); };
    div.appendChild(delBtn);
  }

  return div;
}

function renderRule(rule, i, parent=null, index=null){
  const div = document.createElement('div');
  div.className = 'rule';

  // Attribut
  const attrSelect = document.createElement('select');
  attributes.forEach(attr => {
    const o = document.createElement('option');
    o.value = attr; o.textContent = attr;
    if(rule.attribute===attr) o.selected=true;
    attrSelect.appendChild(o);
  });
  attrSelect.onchange = () => { rule.attribute=attrSelect.value; syncHiddenField(i); };

  // Operator
  const opSelect = document.createElement('select');
  operators.forEach(op => {
    const o = document.createElement('option');
    o.value = op; o.textContent = op;
    if(rule.operator===op) o.selected=true;
    opSelect.appendChild(o);
  });
  opSelect.onchange = () => { rule.operator=opSelect.value; syncHiddenField(i); };

  // Value
  const valueInput = document.createElement('input');
  if(rule.operator==='IN'){
    valueInput.value = Array.isArray(rule.value)?rule.value.join('|'):'';
  } else {
    valueInput.value = rule.value || '';
  }
  valueInput.oninput = () => {
    if(rule.operator==='IN') rule.value=valueInput.value.split('|').map(s=>s.trim());
    else rule.value=valueInput.value;
    syncHiddenField(i);
  };

  div.append(attrSelect, opSelect, valueInput);

  // Delete-Button
  if(parent){
    const delBtn = document.createElement('button');
    delBtn.type='button';
    delBtn.textContent='Löschen';
    delBtn.onclick = () => { parent.rules.splice(index,1); render(i); };
    div.appendChild(delBtn);
  }

  return div;
}

// --------------------
// Buttons hinzufügen
// --------------------
function createAddRuleButton(group, i){
  const btn = document.createElement('button');
  btn.type='button';
  btn.textContent='+ Bedingung';
  btn.onclick = () => { group.rules.push({attribute:'', operator:'=', value:''}); render(i); };
  return btn;
}

function createAddGroupButton(group, i){
  const btn = document.createElement('button');
  btn.type='button';
  btn.textContent='+ Gruppe';
  btn.onclick = () => { group.rules.push({logic:'AND', rules:[]}); render(i); };
  return btn;
}

// --------------------
// Render-Funktion für Attribut i
// --------------------
function render(i){
  const container = document.getElementById('rulesDiv_' + i);
  container.innerHTML='';
	if (visibilityRules[i]) {
  	container.appendChild(renderNode(visibilityRules[i], i));
	}
  syncHiddenField(i);
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
		
	.navigation th:not(.navigation-selected) a{
		color: #888;
	}	
	.navigation th:not(.navigation-selected):hover{
		background-color: rgb(238, 238, 239);
	}
	.navigation-selected{
		background-color: #c7d9e6;
	}
	.navigation-selected div{
		color: #111;
	}

	td {
		vertical-align: top;
	}

	.rulediv {
		overflow: hidden;
		max-height: 24px; /* Höhe des ersten Elements */
		transition: max-height 0.3s ease;
	}

	.rulediv.open {
		max-height: 500px; /* ausreichend groß */
		transition: max-height 0.3s ease;
	}

	.rule {
		display: flex;
	}

	.rule *, .group *{
		margin: 0 1px 2px 1px;
	}

	.rulediv.open .group {
		border: 1px solid #aaa;
		padding: 2px;
	}

	.group:not(:has(> :nth-child(5))) > :first-child {
		display: none;
	}

	.group.child:not(:has(> :nth-child(6))) > :first-child {
		display: none;
	}

	.child {
		margin: 0px 2px 2px 10px;
	}

</style>

<table style="width: 700px; margin: 15px 40px 0 40px">
	<tr>
    <td align="center">
			<span class="px17 fetter"><? echo $strLayer;?>:</span>
      <select id="selected_layer_id" style="width:250px" size="1" name="selected_layer_id" onchange="submitLayerSelector();" <?php if(count($this->layerdaten['ID'] ?: [])==0){ echo 'disabled';}?>><?
			$layer_options = array(); ?>
      <option value=""><?php echo $strPleaseSelect; ?></option><?
				for ($i = 0; $i < count($this->layerdaten['ID'] ?: []); $i++) {
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
		<? if($this->formvars['selected_layer_id'] != '' AND $this->formvars['selected_datatype_id'] != ''){ ?>
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
					<th>
						<a href="index.php?go=Layereditor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div style="width: 100%"><? echo $strCommonData; ?></div></a>
					</th><?
					if (!in_array($this->layer['datentyp'], [MS_LAYER_QUERY])){ ?>
						<th>
							<a href="index.php?go=Klasseneditor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div style="width: 100%"><? echo $strClasses; ?></div></a>
						</th>
						<th>
							<a href="index.php?go=Style_Label_Editor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div style="width: 100%"><? echo $strStylesLabels; ?></div></a>
						</th><?
					} ?>
					<th class="navigation-selected">
						<a href="index.php?go=Attributeditor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div style="width: 100%"><? echo $strAttributes; ?></div></a>
					</th>
					<th>
						<a href="index.php?go=Layereditor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&stellenzuweisung=1&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div style="width: 100%"><? echo $strStellenAsignment; ?></div></a>
					</th>
					<th>
						<a href="index.php?go=Layerattribut-Rechteverwaltung&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div style="width: 100%"><? echo $strPrivileges; ?></div></a>
					</th><?
					if (!in_array($this->layer['datentyp'], [MS_LAYER_QUERY])) { ?>
						<th>
							<a href="index.php?go=show_layer_in_map&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&zoom_to_layer_extent=1&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i class="fa fa-map" style="width: 50px"></i></a>
						</th><?
					} ?>
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
				<tbody style="max-height: <? echo ($this->user->rolle->nImageHeight - 162); ?>px">
		<?	if ((count($this->attributes))!=0) { 
					for ($i = 0; $i < count_or_0($this->attributes['type']); $i++){ ?>
						<tr class="listen-tr" title="<? echo $this->attributes['name'][$i]; ?>">
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
									<a href="index.php?go=Attributeditor&selected_layer_id=<? echo $this->formvars['selected_layer_id']; ?>&selected_datatype_id=<?php echo $type; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?php echo $this->attributes['typename'][$i]; ?></a><?php
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
								if ($i == count_or_0($this->attributes['type']) - 1) {
									echo '<div class="fett scrolltable_footer">
														<a href="javascript:clear_all(\'options\');" title="alle Einträge entfernen"><i style="font-size: 19px;vertical-align: text-bottom;" class="fa fa-trash-o"></i></a>
													</div>';
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
							<? 	if ($i == 0) {
										echo '<div class="fett scrolltable_header">' . $strDefault . '</div>';
								}
								if ($i == count_or_0($this->attributes['type']) - 1) {
									echo '<div class="fett scrolltable_footer">
														<a href="javascript:clear_all(\'default\');" title="alle Einträge entfernen"><i style="font-size: 19px;vertical-align: text-bottom;" class="fa fa-trash-o"></i></a>
													</div>';
									}	?>
						  	<input name="default_<?php echo $this->attributes['name'][$i]; ?>" type="text" value="<?php echo htmlspecialchars($this->attributes['default'][$i]); ?>">
						  </td>							

						  <td align="left" valign="top">
						<? 	if ($i == 0) {
										echo '<div class="fett scrolltable_header">' . $strAlias . '</div>';
								}
								if ($i == count_or_0($this->attributes['type']) - 1) {
									echo '<div class="fett scrolltable_footer">
														<a title="aus Attributname erzeugen" href="javascript:create_aliasnames();"><img src="graphics/autogen.png"></a>
														<a href="javascript:clear_all(\'alias\');" title="alle Einträge entfernen"><i style="font-size: 19px;vertical-align: text-bottom;" class="fa fa-trash-o"></i></a>
													</div>';
								} ?>
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
								}
								if ($i == count_or_0($this->attributes['type']) - 1) {
									echo '<div class="fett scrolltable_footer">
													<a href="javascript:clear_all(\'tooltip\');" title="alle Einträge entfernen"><i style="font-size: 19px;vertical-align: text-bottom;" class="fa fa-trash-o"></i></a>
												</div>';
								} ?>
								<textarea name="tooltip_<?php echo $this->attributes['name'][$i]; ?>" style="height:22px; width:120px"><?php echo htmlspecialchars($this->attributes['tooltip'][$i]); ?></textarea>
							</td>

							<td align="left" valign="top"><?
								if ($i == 0) {
									echo '<div class="fett scrolltable_header">' . $this->strGroup . '</div>';
								}
								if ($i == count_or_0($this->attributes['type']) - 1) {
									echo '<div class="fett scrolltable_footer">
													<a href="javascript:clear_all(\'group\');" title="alle Einträge entfernen"><i style="font-size: 19px;vertical-align: text-bottom;" class="fa fa-trash-o"></i></a>
												</div>';
								} ?>
								<input name="group_<?php echo $this->attributes['name'][$i]; ?>" type="text" value="<?php echo htmlspecialchars($this->attributes['group'][$i]); ?>">
							</td>
							
							<td align="left" valign="top">
						<? 	if ($i == 0) {
										echo '<div class="fett scrolltable_header">Tab</div>';
								}
								if ($i == count_or_0($this->attributes['type']) - 1) {
									echo '<div class="fett scrolltable_footer">
														<a href="javascript:clear_all(\'tab\');" title="alle Einträge entfernen"><i style="font-size: 19px;vertical-align: text-bottom;" class="fa fa-trash-o"></i></a>
													</div>';
								}	?>
								<input name="tab_<?php echo $this->attributes['name'][$i]; ?>" type="text" value="<?php echo htmlspecialchars($this->attributes['tab'][$i]); ?>">
							</td>							
							
							<?php
							if ($this->attributes['arrangement'][$i] == 0) { $bgcolor = 'white'; }
							if ($this->attributes['arrangement'][$i] == 1) { $bgcolor = '#faef1e'; } ?>
							<td align="center" valign="top"><?
								if ($i == 0) {
									echo '<div class="fett scrolltable_header">' . $strArrangement . '</div>';
								}
								if ($i == count_or_0($this->attributes['type']) - 1) {
									echo '<div class="fett scrolltable_footer" style="padding: 0">' . 
													FormObject::createSelectField(
													'arrangement',
													array(
														array('value' => 0, 'output' => $strUnderPrevious),
														array('value' => 1, 'output' => $strBesidePrevious, 'style' => 'background-color: #faef1e')
													),
													'',
													1,
													"outline: 1px solid lightgrey; border: none; width: 85px; background-color: " . $bgcolor,
													"this.setAttribute('style', 'outline: 1px solid lightgrey; border: none; width: 85px;' + this.options[this.selectedIndex].getAttribute('style'));
													 set_all('arrangement');",
													 '',
													 '',
													 '',
													 '- Auswahl -'
												) .
												'</div>';
								}
								echo FormObject::createSelectField(
									'arrangement_' . $this->attributes['name'][$i],
									array(
										array('value' => 0, 'output' => $strUnderPrevious),
										array('value' => 1, 'output' => $strBesidePrevious, 'style' => 'background-color: #faef1e')
									),
									$this->attributes['arrangement'][$i],
									1,
									"outline: 1px solid lightgrey; border: none; width: 85px; background-color: " . $bgcolor,
									"this.setAttribute('style', 'outline: 1px solid lightgrey; border: none; width: 85px;' + this.options[this.selectedIndex].getAttribute('style'));"
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
								if ($i == count_or_0($this->attributes['type']) - 1) {
									echo '<div class="fett scrolltable_footer" style="padding: 0">' . 
													FormObject::createSelectField(
													'labeling',
													array(
														array('value' => 0, 'output' => $strLeftBesideAttribute, 'style' => 'background-color: white'),
														array('value' => 1, 'output' => $strAboveAttribute, 'style' => 'background-color: #faef1e'),
														array('value' => 2, 'output' => $strWithoutLabel, 'style' => 'background-color: #ff6600')
													),
													'',
													1,
													"outline: 1px solid lightgrey; border: none; width: 88px; background-color: " . $bgcolor,
													"this.setAttribute('style', 'outline: 1px solid lightgrey; border: none; width: 88px;' + this.options[this.selectedIndex].getAttribute('style'));
													 set_all('labeling');",
													 '',
													 '',
													 '',
													 '- Auswahl -'
												) .
												'</div>';
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
									"outline: 1px solid lightgrey; border: none; width: 88px; background-color: " . $bgcolor,
									"this.setAttribute('style', 'outline: 1px solid lightgrey; border: none; width: 88px;' + this.options[this.selectedIndex].getAttribute('style'));"
								); ?>
							</td>

							<? if ($this->attributes['style'][0] != '') { ?>
							<td>
								<?	if ($i == 0) {
											echo '<div class="fett scrolltable_header">' . $strStyleAttribute . '</div>';
										}
										if ($i == count_or_0($this->attributes['type']) - 1) {
											echo '<div class="fett scrolltable_footer" style="padding: 0">' . 
															FormObject::createSelectField(
															'style_attribute',
															$this->attributes['style'],
															$this->attributes['style_attribute'][$i],
															1,
															'',
															"set_all('style_attribute');",
															'',
															'',
															'',
															'- Auswahl -'
														) .
														'</div>';
										}
										echo FormObject::createSelectField(
											'style_attribute_' . $this->attributes['name'][$i],
											$this->attributes['style'],
											$this->attributes['style_attribute'][$i],
											1,
											'',
											'',
											'',
											'',
											'',
											'- Auswahl -'
										); ?>
							</td>
							<? } ?>
							
							<td align="center" valign="top"><?
								if($i == 0) {
									echo '<div style="margin-top: -9px;" class="fett scrolltable_header">' . $strAttributeAtSearch . '</div>';
								}
								if ($i == count_or_0($this->attributes['type']) - 1) {
									echo '<div class="fett scrolltable_footer" style="padding: 0">' . 
													FormObject::createSelectField(
													'mandatory',
													array(
														array('value' => -1, 'output' => $strAttributeNotVisible),
														array('value' => 0, 'output' => $strShowAttribute),
														array('value' => 1, 'output' => $strMandatoryAtSearch)
													),
													'',
													1,
													'outline: 1px solid lightgrey; border: none; width: 75px; background-color: white;',
													"set_all('mandatory');",
													 '',
													 '',
													 '',
													 '- Auswahl -'
												) .
												'</div>';
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
									'outline: 1px solid lightgrey; border: none; width: 75px; background-color: white;'
								); ?>
							</td>

							<td align="center" valign="top"><?
								if ($i == 0) {
									echo '<div style="margin-top: -9px;" class="fett scrolltable_header">' . $strForNewDataset . '</div>';
								}
								if ($i == count_or_0($this->attributes['type']) - 1) {
									echo '<div class="fett scrolltable_footer" style="padding: 0">' . 
													FormObject::createSelectField(
													'dont_use_for_new',
													array(
														array('value' => -1, 'output' => $strAttributeNotVisible),
														array('value' => 0, 'output' => $strShowAttribute),
														array('value' => 1, 'output' => $strOmitAttributeValues)
													),
													'',
													1,
													'outline: 1px solid lightgrey; border: none; width: 75px; background-color: white;',
													"set_all('dont_use_for_new');",
													 '',
													 '',
													 '',
													 '- Auswahl -'
												) .
												'</div>';
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
									'outline: 1px solid lightgrey; border: none; width: 75px; background-color: white;'
								); ?>
							</td>
							
							<td align="center" valign="top"><?
								if ($i == 0) {
									echo '<div class="fett scrolltable_header">' . $strAttributeVisible . '</div>';
								}
								if ($i == count_or_0($this->attributes['type']) - 1) {
									echo '<div class="fett scrolltable_footer" style="padding: 0">' . 
													FormObject::createSelectField(
												'visible',
												array(
													array('value' => 0, 'output' => $this->strNo),
													array('value' => 1, 'output' => $this->strYes),
													array('value' => 2, 'output' => $strYesWhen)
												),
												'',
												1,
												'outline: 1px solid lightgrey; border: none; width: 75px; background-color: white;',
													"set_all('visible');",
													 '',
													 '',
													 '',
													 '- Auswahl -'
												) .
												'</div>';
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
												'outline: 1px solid lightgrey; border: none; width: 75px; background-color: white;',
												'update_visibility_form(this.value, \''.$this->attributes['name'][$i].'\')'
											); ?>
										</td>
										<td id="visibility_form_<? echo $this->attributes['name'][$i]; ?>" style="<? echo ($this->attributes['visible'][$i] == 2 ? '' : 'display:none') ?>">
											<div id="rulesDiv_<? echo $i; ?>" class="rulediv" onmouseenter="this.classList.add('open');" onmouseleave="this.classList.remove('open');"></div>
  										<input type="hidden" id="visibilityRulesInput_<? echo $i; ?>" name="visibility_rules_<? echo $this->attributes['name'][$i]; ?>">
											<script>
												render(<? echo $i; ?>);
											</script>
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
		if (count($this->attributes) > 0 AND $this->layer['editable']) { ?>
			<tr>
				<td align="center" style="padding-top: 50px;height: 90px"><?
					if ($this->formvars['selected_datatype_id'] == '') {
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
						<input id="attributes_for_other_layer_button" style="display: none; margin-left: 10px" type="button" onclick="takeover_attributes();" value="Attributeinstellungen für ausgewählten Layer übernehmen">
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
						></i> <?
					}
					else {	?>
						<input type="checkbox" value="1" name="for_all_layers"> für alle Layer übernehmen<br><br>
<?				}			?>
					<input id="attribut_editor_save" type="submit" name="go_plus" value="speichern">
				</td>
			</tr><?php
		}	
	?>
</table>

<? } ?>

<input type="hidden" name="go" value="Attributeditor">