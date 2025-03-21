<? 
	include(LAYOUTPATH.'languages/attribut_privileges_form_'.rolle::$language.'.php');
	include(LAYOUTPATH.'languages/layer_formular_'.rolle::$language.'.php');
?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">

function toggle_privileges(checkbox){
	var fields = checkbox.closest('.apt-main-div').querySelectorAll('input[type=“checkbox”], select');
	[].forEach.call(fields, function (field){
    if (field != checkbox) {
			field.disabled = checkbox.checked;
		}
  });
}

function set_all_for_stelle(attribute_names, stelle, value){
	names = attribute_names.split('|');
	for(i = 0; i < names.length; i++){
		element = document.getElementsByName('privileg_'+names[i]+'_'+stelle);
		element[0].value = value;
		element[0].onchange();
	}
}

function set_all_for_attribute(attribute){
	var default_field = document.querySelector('.default_privileg_' + attribute);
	var fields = document.querySelectorAll('.privileg_' + attribute);
	[].forEach.call(fields, function (field){
		field.value = default_field.value;
		field.onchange();
  });
}

function get_from_default(attribute_names, stellen){
	really = true;
	stelle = stellen.split('|');
	if(stelle.length > 1){
		really = confirm('Wollen Sie die Default-Rechte wirklich allen Stellen zuweisen?');
	}
	if(really){
		for(j = 0; j < stelle.length; j++){
			element1 = document.getElementsByName('privileg'+stelle[j]);
			element2 = document.getElementsByName('privileg');
			element1[0].value = element2[0].value;
			element1 = document.getElementsByName('export_privileg'+stelle[j]);
			element2 = document.getElementsByName('export_privileg');
			element1[0].value = element2[0].value;
			names = attribute_names.split('|');
			for(i = 0; i < names.length; i++){
				element1 = document.getElementsByName('privileg_'+names[i]+'_'+stelle[j]);
				element2 = document.getElementsByName('privileg_'+names[i]+'_');
				element1[0].value = element2[0].value;
				tooltip1 = document.getElementsByName('tooltip_'+names[i]+'_'+stelle[j]);
				tooltip2 = document.getElementsByName('tooltip_'+names[i]+'_');
				tooltip1[0].checked = tooltip2[0].checked;
			}
		}
		save(stellen);
	}
}


function save(stelle, other_selected_layer_id) {
	other_selected_layer_id = other_selected_layer_id || '';
	document.GUI.stelle.value = stelle;
	if (other_selected_layer_id != '') {
		document.GUI.from_layer_id.value = $('#selected_layer_id').val();
		document.GUI.to_layer_id.value = other_selected_layer_id;
		document.GUI.go_plus.value = 'Attributrechte für ausgewählten Layer übernehmen';
	}
	else {
		document.GUI.go_plus.value = 'speichern';
	}
	document.GUI.submit();
}

function toggle_unterstellen(){
	var unterstellen = document.querySelectorAll('.unterstelle');
	[].forEach.call(unterstellen, function (unterstelle){
		unterstelle.classList.toggle('hidden');
  });
}

function update_stellen_visibility(){
	var stellen = document.getElementById('stellen_visibility');
	if (stellen.value != '') {
		document.getElementById('stellendiv').classList.add('filtered');
	}
	else {
		document.getElementById('stellendiv').classList.remove('filtered');
	}
	for (var i=1; i < stellen.options.length; i++) {
		var td = document.getElementById('stellen_td_' + stellen.options[i].value);
    if (stellen.options[i].selected) {
      td.classList.add('visible');
    }
		else {
			td.classList.remove('visible');
		}
  }
}

</script>

<style>
	.navigation{
		border-collapse: collapse; 
		width: 940px;
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
		width: 100%;
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
	.apf-magic {
		margin-top: 15px;
	}
	.apf-template-div-default, .apf-template-div {
		background-color: #f8f8f9; 
		border-width:1px 0px 1px 1px;
		border-style: solid;
		border-color: #bbb;
	}
	.apf-template-div {
		 float:right; 
		 overflow:auto; 
		 overflow-y:hidden;
	}
	.apt-main-td {
		width:280px;
		border-right: 1px solid #bbb;
	}
	.apt-bezeichnung, .apt-defaultrechteanstelle, .apt-attributrechtespeichern {
		text-align: center;
		margin: 10px 0px 20px 0px;
	}
	.apt-bezeichnung {
		height: 40px;
	}
	.apt-layerzugriffsrechte, .apt-layerexportrechte {
		margin: 10px 10px 0 4px;
	}
	.apt-use_parent_privileges {
		margin-top: -30px;
	}	
	.apt-attributrechte {
		margin: 20px 0 10px 0;
	}
	.apt-attributrechte table{
		border-spacing: 0;
	}
	.apt-attributrechte table td{
		padding: 0 5px 0 5px;
	}
	.apt-attributrechte table tr:last:child {
		margin-top: 50px;
	}
	.apt-attributname {
		margin-top: 2px;
		padding: 3px;
		position: absolute;
		width: 100px;
		background-color: #f8f8f9;		
		overflow: hidden;
		outline: 1px solid #f8f8f9;
		cursor: default;
	}
	.apt-attributname:hover {
		width: auto;
		outline: 1px solid #ccc;
	}
	#stellendiv.filtered td.apt-main-td {
		display: none;
	}
	#stellendiv.filtered td.apt-main-td.visible {
		display: revert;
	}
	</style>

<table style="width: 700px; margin: 0px 40px 0 40px">
	<tr>
		<td align="center">
			<span class="px17 fetter"><? echo $strLayer;?>:</span>
			<select id="selected_layer_id" style="width:250px" size="1" name="selected_layer_id" onchange="document.GUI.submit();" <?php if(count($this->layerdaten['ID'] ?: [])==0){ echo 'disabled';}?>><?
			$layer_options = array(); ?>
			<option value="">--------- <?php echo $this->strPleaseSelect; ?> --------</option><?
			for ($i = 0; $i < count($this->layerdaten['ID'] ?: []); $i++) {
				$layer_options[] = array(
					'value' => $this->layerdaten['ID'][$i],
					'output' => $this->layerdaten['Bezeichnung'][$i]
				);
				echo '<option';
				if ($this->layerdaten['ID'][$i] == $this->formvars['selected_layer_id']) {
					echo ' selected';
				}
				echo ' value="'.$this->layerdaten['ID'][$i].'">' . $this->layerdaten['Bezeichnung'][$i] . ($this->layerdaten['alias'][$i] != '' ? ' [' . $this->layerdaten['alias'][$i] . ']' : '') . '</option>';
			} ?>
			</select>
		</td>
	</tr>
</table>

<? if($this->formvars['selected_layer_id'] != ''){ ?>

<table border="0" cellpadding="0" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>" style="margin: 10px">
	<tr align="center"> 
		<td style="width: 100%;">
			<table cellpadding="0" cellspacing="0" class="navigation">
				<tr>
					<th>
						<a href="index.php?go=Layereditor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div><? echo $strCommonData; ?></div></a>
					</th><?
					if (!in_array($this->layer[0]['datentyp'], [MS_LAYER_QUERY])) { ?>
						<th>
							<a href="index.php?go=Klasseneditor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div><? echo $strClasses; ?></div></a>
						</th>
						<th>
							<a href="index.php?go=Style_Label_Editor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div><? echo $strStylesLabels; ?></div></a>
						</th><?
					} ?>
					<th>
						<a href="index.php?go=Attributeditor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div><? echo $strAttributes; ?></div></a>
					</th>
					<th>
						<a href="index.php?go=Layereditor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&stellenzuweisung=1&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div><? echo $strStellenAsignment; ?></div></a>
					</th>
					<th class="navigation-selected">
						<a href="index.php?go=Attributeditor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div><? echo $strPrivileges; ?></div></a>
					</th><?
					if (!in_array($this->layer[0]['datentyp'], [MS_LAYER_QUERY])) { ?>
						<th>
							<a href="index.php?go=show_layer_in_map&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&zoom_to_layer_extent=1&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i class="fa fa-map" style="width: 50px"></i></a>
						</th><?
					} ?>
				</tr>
			</table>
		</td>
	</tr>	
</table>

<? } ?>

<table border="0" cellpadding="5" cellspacing="2" bgcolor="<?php echo $bgcolor; ?>">
  <? if($this->layer[0]['name'] != ''){ ?>
	<tr>
  	<td>
			<div style="display: flex; justify-content: space-between">
				<div class="apf-tip-magic" style="min-width: 200px;">
<?					echo FormObject::createSelectField(
						'for_attribute_privileges_selected_layer_id',
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
					<input
						id="attribute_privileges_for_other_layer_button"
						style="display: none; margin-left: 10px"
						type="button"
						onclick="save(
							'<? echo implode('|', $this->stellen['ID']); ?>',
							$('#for_attribute_privileges_selected_layer_id').val()
						)"
						value="Attributrechte für ausgewählten Layer übernehmen"
					>
					<i
						id="show_attribute_privileges_for_other_layer_button"
						style="cursor: pointer" 
						title="Magische Funktion um die Attributrechte auf gleich benannte Attribute eines anderen Layers zu übertragen. Vorgenommene Änderungen müssen vorher gespeichert werden!"
						class="fa fa-magic apf-magic"
						aria-hidden="true"
						onclick="$('#attribute_privileges_for_other_layer_button, #for_attribute_privileges_selected_layer_id, #show_attribute_privileges_for_other_layer_button, #close_attribute_privileges_for_other_layer_button').toggle();"
					></i>
					<i
						id="close_attribute_privileges_for_other_layer_button"
						title="Den Spuk wieder schließen."
						style="display: none; cursor: pointer" class="fa fa-times"
						aria-hidden="true"
						onclick="$('#attribute_privileges_for_other_layer_button, #for_attribute_privileges_selected_layer_id, #show_attribute_privileges_for_other_layer_button, #close_attribute_privileges_for_other_layer_button').toggle();"
					></i>
					&nbsp;
				</div>
				<div style="flex-grow: 2; text-align: center; position: relative">
					<div style="display:flex; justify-content: center; position: absolute; width: 100%;">
						<div style="margin-top: 3px;">Stellen:&nbsp;</div>
						<select name="stellen_visibility[]" id="stellen_visibility" style="z-index: 1000; height: 24px; max-height: 200px; scrollbar-width: thin;" multiple="true" onchange="update_stellen_visibility();" onmousedown="if(this.style.height=='24px'){this.style.height = (this.length * 22) + 6;preventDefault(event);}" onmouseleave="if(event.relatedTarget){this.style.height='24px';scrollToSelected(this);}">
							<option value="">- alle -</option>
							<?
							for($i = 0; $i < count($this->stellen['ID']); $i++){
								echo '<option value="'.$this->stellen['ID'][$i].'" ';
								if (in_array($this->stellen['ID'][$i], $this->formvars['stellen_visibility'] ?: [])){
									echo 'selected';
								}
								echo '>'.$this->stellen['Bezeichnung'][$i].'</option>';
							}
						?>
						</select>
					</div>
				</div>
				<div style="padding-right: 20px; float: right">
					<input type="checkbox" name="unterstellen_ausblenden" <? echo ($this->formvars['unterstellen_ausblenden'] != ''? 'checked' : ''); ?> onclick="toggle_unterstellen();"> Unterstellen ausblenden
				</div>
				<span style="--left: -650px; --width: 750px" data-tooltip="Globale sowie attributive Rechte der Stelle beim Zugriff den ausgewählten Layer.&#xa;&#xa;Die eingestellten Default-Rechte werden beim erstmaligen Zuordnen eines Layers zu einer Stelle verwendet.&#xa;&#xa;Layerzugriffsrechte&#xa;Globale Privilegien auf Layerebene (globale editierende Rechte am Layer müssen durch die entsprechenden attributbezogenen Rechte aktiviert werden):&#xa;- 'lesen und bearbeiten': Mindestzugriffsrecht. Vorhandene Datensätze können gelesen und bearbeitet werden.&#xa;- 'neue Datensätze erzeugen': Datensätze können gelesen, bearbeitet und neu angelegt werden.&#xa;- 'Datensätze erzeugen und löschen': Datensätze können gelesen, bearbeitet, erzeugt und gelöscht werden.&#xa;&#xa;Layerexportrechte&#xa;- 'Export nicht erlaubt': Datensätze sind in der Sachdatenabfrage grundsätzlich sichtbar, können jedoch nicht exportiert werden.&#xa;- 'nur Sachdaten': Der Export eines Datensatzes ist nur in Nicht-Geometrie-Formate möglich.&#xa;- 'Sach- und Geometriedaten': Default. Der Export eines Datensatzes ist in alle Datenformate möglich.&#xa;&#xa;Attributbezogene Rechte:&#xa;- 'kein Zugriff': Das Attribut erscheint in der Sachdatenabfrage nicht.&#xa;- 'lesen': Das Attribut erscheint in der Sachdatenabfrage, ist aber nicht editierbar.&#xa;- 'editieren': Das Attribut erscheint in der Sachdatenabfrage und ist editierbar.&#xa;&#xa;Ist für das Geometrie-Attribut ('the_geom') das Privileg 'kein Zugriff' eingetragen, kann man nicht von der Sachdatenanzeige in die Karte auf das Objekt zoomen. Dafür muß es mindestens lesbar sein.&#xa;Damit ein Attribut in der Layer-Suche als Suchoption zur Verfügung steht, muss es mindestens lesbar sein.&#xa;&#xa;Tooltip: Inhalt des angehakten Attributs erscheint in der Karte beim Hovern über ein Objekt. Funktioniert auch mit Fotos.&#xa;&#xa;Hinweis 'Default-Rechte allen Stellen zuweisen': Je nach nach Anzahl der Stellen und Attribute kann eine sehr große Anzahl an Formularvariablen übermittelt werden. Möglicherweise muss dafür in der php.ini der Wert für max_input_vars hoch gesetzt werden."></span>
			</div>
  	</td>
  </tr>
  <tr>
  	<td>
  		<table>
				<tr>
					<td></td>
					<td></td>
					<td><?
						$stellenanzahl = ($this->stellen ? count($this->stellen['ID']) : 0);
						if($stellenanzahl > 0){
						$width1 = $width = 297*$stellenanzahl;
						if($width > 1187)$width = 1187;
						if($width1 > 1187){ ?>
						<div id="upperscrollbar" style="overflow:auto; overflow-y:hidden;width:1187px" onscroll="document.getElementById('stellendiv').scrollLeft=this.scrollLeft">
							<div style="width:<? echo $width1; ?>px;height:1px"></div>
						</div>
						<? } ?>
					</td>
				</tr>
  			<tr>
			  	<td valign="top">
			  		<div class="apf-template-div-default">
							<table border="0" style="border-collapse:collapse" cellspacing="0" cellpadding="10">
								<tr>  	
			  					<? $template_div = 'default'; ?>
								<? include(LAYOUTPATH.'snippets/attribute_privileges_template.php'); ?>
			  				</tr>
							</table>
						</div>
					<td>	
					<td valign="top">
						<div id="stellendiv" class="apf-template-div <? if ($this->formvars['stellen_visibility'] != '') {echo 'filtered';}?>" style="width:<? echo $width; ?>px;" onscroll="document.GUI.scrollposition.value = this.scrollLeft; document.getElementById('upperscrollbar').scrollLeft=this.scrollLeft">
							<table border="0" style="border-collapse:collapse" cellspacing="0" cellpadding="10">
								<tr>
							<?
								for($s = 0; $s < count($this->stellen['ID']); $s++){
									$template_div = '';
									$this->stelle = new stelle($this->stellen['ID'][$s], $this->database);
									$this->layer = $this->stelle->getLayer($this->formvars['selected_layer_id']);
									$this->attributes_privileges = $this->stelle->get_attributes_privileges($this->formvars['selected_layer_id'], true);
									include(LAYOUTPATH.'snippets/attribute_privileges_template.php');
								}
							?>
								</tr>
							</table>
						</div>
					</td>
					<? } ?>
				</tr>
			</table>
		</td>
  </tr>
  <tr> 
    <td colspan="4" >&nbsp;</td>
  </tr>
  <? } ?>
</table>

<input type="hidden" name="scrollposition" value="<? echo $this->formvars['scrollposition']; ?>">
<input type="hidden" name="go" value="Layerattribut-Rechteverwaltung">
<input type="hidden" name="go_plus" value="">
<input type="hidden" name="stelle" value="">
<input type="hidden" name="from_layer_id" value="">
<input type="hidden" name="to_layer_id" value="">
<script type="text/javascript">

	if(document.getElementById("stellendiv"))document.getElementById("stellendiv").scrollLeft="<? echo $this->formvars['scrollposition']; ?>"

</script>


