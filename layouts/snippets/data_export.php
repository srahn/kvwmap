<?php
 # 2008-01-22 pkvvm
  include(LAYOUTPATH.'languages/data_export_'.$this->user->rolle->language.'.php');
	include_once(CLASSPATH . 'FormObject.php');
	$simple = ($this->formvars['simple'] == 1);
	$document_ids = [];
	$available_formats = array(
		"Shape" => array(
			"export_privileg" => 1,
			"geom_attribute" => "the_geom",
			"geom_type" => ""
		),
		"GeoPackage" => array(
			"export_privileg" => 1,
			"geom_attribute" => "the_geom",
			"geom_type" => ""
		),		
		"DXF" => array(
			"export_privileg" => 1,
			"geom_attribute" => "the_geom",
			"geom_type" => ""
		),
		"GPX" => array(
			"export_privileg" => 1,
			"geom_attribute" => "the_geom",
			"geom_type" => ""
		),
		"GML" => array(
			"export_privileg" => 1,
			"geom_attribute" => "the_geom",
			"geom_type" => ""
		),
		"KML" => array(
			"export_privileg" => 1,
			"geom_attribute" => "the_geom",
			"geom_type" => ""
		),
		"GeoJSON" => array(
			"export_privileg" => 1,
			"geom_attribute" => "the_geom",
			"geom_type" => ""
		),
		"UKO" => array(
			"export_privileg" => 1,
			"geom_attribute" => "the_geom",
			"geom_type" => "MS_LAYER_POLYGON"
		),
		"OVL" => array(
			"export_privileg" => 1,
			"geom_attribute" => "the_geom",
			"geom_type" => ""
		),
		"CSV" => array(
			"export_privileg" => 2,
			"geom_attribute" => "",
			"geom_type" => ""
		)
	);
	global $supportedExportFormats;
	$allowed_formats = array();
	foreach($supportedExportFormats AS $supportedExportFormat) {
		$allowed_formats[$supportedExportFormat] = $available_formats[$supportedExportFormat];
	}
?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

function update_format(){
	if(document.GUI.export_format.value == 'UKO' || document.GUI.export_format.value == 'OVL'){
		document.getElementById('attributes_div').style.visibility = 'hidden';
	}
	else{
		document.getElementById('attributes_div').style.visibility = 'visible';
	}
	if(document.GUI.export_format.value == 'CSV'){
		document.getElementById('coord_div').style.display = 'none';
		document.getElementById('geom_div').style.display = 'none';
		if(document.getElementById('groupnames_div') != null)document.getElementById('groupnames_div').style.display = '';		
	}
	else{
		document.getElementById('geom_div').style.display = '';
		document.getElementById('coord_div').style.display = 'inline';
		if(document.getElementById('groupnames_div') != null)document.getElementById('groupnames_div').style.display = 'none';
		if (['KML', 'GPX', 'OVL'].includes(document.GUI.export_format.value)){
			document.getElementById('wgs84').style.display = 'inline';
			document.GUI.epsg.style.display = 'none';
			document.GUI.epsg.value = 4326;
		}
		else{
			document.getElementById('wgs84').style.display = 'none';
			document.GUI.epsg.style.display = 'inline';
		}
	}
}

function data_export() {
	message([{type: 'info', msg: 'Der Download wird automatisch gestartet. Bitte warten.'}]);
	if(document.getElementById('exporttimestamp').value == 1){
		message([{type: 'info', msg: 'Die Zeitstempel werden automatisch gesetzt und die Datensätze damit als heruntergeladen gekennzeichnet.'}]);
	}
	if(document.GUI.download_documents){
		message([{type: 'info', msg: 'Dokumente wurden ' + (document.GUI.download_documents.checked ? 'mit' : 'nicht mit') + ' heruntergeladen!'}]);
	}
	if (document.GUI.selected_layer_id.value != ''){
		if(document.GUI.anzahl == undefined && document.GUI.newpathwkt.value == '' && document.GUI.newpath.value == ''){
			var sure = confirm('<? echo $strSure; ?>');
			if(sure == false)return;
		}
		if(document.GUI.newpathwkt.value == '' && document.GUI.newpath.value != ''){
			document.GUI.newpathwkt.value = SVG.buildwktpolygonfromsvgpath(document.GUI.newpath.value);
		}
		document.GUI.go_plus.value = 'Exportieren';
		document.GUI.submit();
		document.GUI.go_plus.value = '';
	}
	else{
		message([{type: 'info', msg: 'Wählen Sie erst ein Thema zum exportieren aus.'}]);
	}
}

function selectall(geom){
	var attribute_selectors = document.querySelectorAll('.attribute_selector');
	var status = attribute_selectors[0].checked;
	[].forEach.call(attribute_selectors, function (attribute_selector){
		if(attribute_selector.name != 'check_'+geom){
			attribute_selector.checked = !status;			
		}
  });
}

function select_document_attributes(ids){
	if(document.GUI.download_documents.checked){
		var k = 0;
		var id = ids.split(',');
		var obj = document.getElementById('check_attribute_'+id[k]);
		while(obj != undefined){
			obj.checked = true;			
			k++;
			obj = document.getElementById('check_attribute_'+id[k]);
		}
	}
}

function save_settings(){
	if(document.GUI.setting_name.value != ''){
		if(document.GUI.newpathwkt.value == '' && document.GUI.newpath.value != ''){
			document.GUI.newpathwkt.value = SVG.buildwktpolygonfromsvgpath(document.GUI.newpath.value);
		}
		document.GUI.go_plus.value = 'Einstellungen_speichern';
		document.GUI.submit();
	}
	else{
		alert('Bitte geben Sie einen Namen für die Export-Einstellungen an.');
	}
}

function delete_settings(){
	if(document.GUI.export_setting.value != ''){
		document.GUI.go_plus.value = 'Einstellungen_löschen';
		document.GUI.submit();
	}
	else{
		alert('Es wurde keine Export-Einstellung ausgewählt.');
	}
}

//-->
</script>

<?
$floor = floor(count_or_0($this->attributes['name'])/4);
$rest = count_or_0($this->attributes['name']) % 4;
if ($rest % 4 != 0) {
 $r=1;
} else {
 $r=0;
}
$j=0;
?>

<table width="<?php echo ($simple ? ($this->user->rolle->nImageWidth + $sizes[$this->user->rolle->gui]['legend']['width']) : '100%'); ?>" border="0" cellpadding="1" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>" width="100%">
  <tr>
    <td align="center" colspan="8" height="40" valign="middle"><h2><?php echo $strTitle; ?></h2></td>
  </tr>
  <tr>
		<td><?php
			if ($this->formvars['sql_' . $this->formvars['selected_layer_id']] != '') { ?>
				<div style="margin-top:30px; text-align:center;">
					<span class="fett"><? echo $this->formvars['anzahl']; ?> <? if ($this->formvars['anzahl']==1) { echo $strRecordFromGLE; } else { echo $strRecordsFromGLE; } ?></span>
					<input type="hidden" name="sql_<? echo $this->formvars['selected_layer_id']; ?>" value="<? echo htmlspecialchars($this->formvars['sql_'.$this->formvars['selected_layer_id']]); ?>">
					<input type="hidden" name="anzahl" value="<? echo $this->formvars['anzahl']; ?>">
				</div><?
			} ?>
			<div class="flexcontainer1" style="border: 1px solid #C3C7C3; padding: 5px; margin-top:30px;">

				<div style="padding-top:1px; padding-bottom:5px;">
					<table>
						<tr>
							<td>		
								<div id="gsl_formular">
									<div class="gsl_gruppe_waehlen gsl_gruppe_waehlen_name"><? echo $this->strGroup; ?>:</div>
									<div class="gsl_gruppe_waehlen gsl_gruppe_waehlen_select">
										<select size="1"  name="selected_group_id" style="width: 250px" onchange="document.GUI.selected_layer_id.value='';document.GUI.submit();" <?php if(count($this->layergruppen['ID'])==0){ echo 'disabled';}?>>
											<option value="">  -- <?php echo $this->strPleaseSelect; ?> --  </option>
							<?
											for($i = 0; $i < count($this->layergruppen['ID']); $i++){         
												echo '<option';
												if($this->layergruppen['ID'][$i] == $this->formvars['selected_group_id']){
													echo ' selected';
												}
												echo ' value="'.$this->layergruppen['ID'][$i].'">'.$this->layergruppen['Bezeichnung'][$i].'</option>';
											}
							?>
										</select>
									</div>
								
									<div class="gsl_layer_waehlen gsl_layer_waehlen_name"><?php echo $strLayer; ?>:</div>
									<div class="gsl_layer_waehlen gsl_layer_waehlen_select">
										<select size="1"  name="selected_layer_id" style="width: 250px" onchange="if (document.GUI.epsg != undefined) { document.GUI.epsg.value=''; } document.GUI.submit();" <?php if(count($this->layerdaten['ID'])==0){ echo 'disabled';}?>>
											<option value="">  -- <?php echo $this->strPleaseSelect; ?> --  </option>
							<?
											for($i = 0; $i < count($this->layerdaten['ID']); $i++){         
												echo '<option';
												if($this->layerdaten['ID'][$i] == value_of($this->formvars, 'selected_layer_id')){
													$selectindex = $i;
													echo ' selected';
												}
												echo ' value="'.$this->layerdaten['ID'][$i].'">'.$this->layerdaten['Bezeichnung'][$i].'</option>';
											}
							?>
										</select>		
									</div>
								</div>
							</td>
						</tr>
					</table>
				</div><?
				if ($this->formvars['selected_layer_id'] != '') { ?>
					<div style="padding-top:1px; padding-bottom:5px; margin-left: 15px;">
						<table>
							<tr>
								<td><? echo $strFormat; ?>:</td>
							</tr>
							<tr>
								<td>
									<select name="export_format" onchange="update_format();"><?
										foreach ($allowed_formats AS $format => $required) {
											if (
												$this->layerdaten['export_privileg'][$selectindex] <= $required['export_privileg'] AND
												$this->attributes[$required['geom_attribute']] != '' OR
												($this->layerset[0]['connectiontype'] == 9 AND $format == 'GeoJSON') OR
												($this->layerset[0]['connectiontype'] == 6 AND $format == 'CSV')
											) {
												echo '<option' . ($this->formvars['export_format'] == $format ? ' selected' : '') . ' value="' . $format . '">' . $format . '</option>';
												if ($this->formvars['export_format'] == '') {
													$this->formvars['export_format'] = $format;
												}
											}
										} ?>
									</select>
								</td>
							</tr>
						</table>
					</div><?
				}
				if ($this->attributes['the_geom'] != ''){ ?>
					<div id="coord_div" style="padding-top:1px; padding-bottom:5px; margin-left: 15px;<? if($this->formvars['export_format'] == 'CSV' OR $this->layerdaten['export_privileg'][$selectindex] != 1)echo 'display:none'; ?>">
						<table>
							<tr>
								<td><? echo $strTransformInto; ?>:</td>
							</tr>
							<tr>
								<td><?php
									if ($simple) { ?>
										ETRS 89, Zone 32, EPSG-Code 25832
										<input type="hidden" name="epsg" value="<?php echo $this->user->rolle->epsg_code; ?>"><?php 
									}
									else { ?>
										<select name="epsg" <? if($this->formvars['export_format'] == 'KML' OR $this->formvars['export_format'] == 'OVL'){$this->formvars['epsg'] = 4326; echo 'style="display:none"';} ?>>
											<option value="">-- Auswahl --</option><?
											foreach($this->epsg_codes as $epsg_code){
												echo '<option ';
												if ($this->formvars['epsg'] == $epsg_code['srid']) echo 'selected ';
												echo ' value="' . $epsg_code['srid'] . '">' . $epsg_code['srid'] . ': ' . $epsg_code['srtext'] . '</option>';
											} ?>
										</select>
										<span id="wgs84" <? if($this->formvars['export_format'] != 'KML' AND $this->formvars['export_format'] != 'OVL')echo 'style="display:none"'; ?>>4326: WGS84</span><?php
									} ?>
								</td>
							</tr>
						</table>
					</div><?
				} ?>
				<div style="padding:1px 0 5px 10px; margin-left: 15px; border-left: 1px solid #ccc; margin-left: auto">
					<table>
						<tr>
							<td><? echo $strExportSettings; ?>:</td>
						</tr>
						<tr>
							<td>
								<input type="text" name="setting_name" value="">
							</td>
							<td>
								<input type="button" style="width: 86px" name="speichern" value="<? echo $this->strSave; ?>" onclick="save_settings();"></span>
							</td>
						</tr>					
						<tr <? if(empty($this->export_settings)){echo 'style="display: none"'; } ?>>
							<td>
								<select name="export_setting">
									<option value="">  -- <? echo $this->strPleaseSelect; ?> --  </option>
									<?
										for($i = 0; $i < count_or_0($this->export_settings); $i++){
											echo '<option value="'.$this->export_settings[$i]['name'].'" ';
											if($this->selected_export_setting[0]['name'] == $this->export_settings[$i]['name']){echo 'selected ';}
											echo '>'.$this->export_settings[$i]['name'].'</option>';
										}
									?>
								</select>
							</td>
							<td>
								<input type="button" style="width: 86px" name="laden" value="<? echo $this->strLoad; ?>" onclick="document.GUI.submit();">
								<a title="<? echo $this->strDelete; ?>" onclick="delete_settings();"><i class="fa fa-trash" name="delete"></i></a>
							</td>
						</tr>
					</table>
				</div>
			</div>

			<div id="attributes_div" style="<? if($this->formvars['export_format'] == 'UKO' OR $this->formvars['export_format'] == 'OVL' OR $simple) { echo 'display: none';} else { echo 'visibility: visible';} ?>;border-bottom:1px solid #C3C7C3; border-left: 1px solid #C3C7C3; border-right: 1px solid #C3C7C3; padding-top:10px; padding-bottom:5px; padding-left:5px; padding-right:5px;">
				&nbsp;&nbsp;<?php echo $strAttributeSelection; ?>:
				<div class="flexcontainer2"><?
					for($s = 0; $s < 4; $s++){ ?>
						<div style="float: left; padding: 4px;"><?
							for($i = 0; $i < $floor+$r; $i++) {
								if(!in_array($this->attributes['form_element_type'][$j], ['dynamicLink']) AND ($this->attributes['type'][$j] != 'unknown' OR $this->attributes['form_element_type'][$j] == 'SubFormEmbeddedPK')){
									if($this->attributes['group'][$j] != '') $groupnames = true;
									if($this->attributes['form_element_type'][$j] == 'Time' AND $this->attributes['options'][$j] == 'export') $exporttimestamp = true;
									if($this->attributes['form_element_type'][$j] == 'Dokument'){$document_attributes = true; $document_ids[] = $j;} ?>
									<div style="padding: 4px;
								<? 	if($this->attributes['name'][$j] == $this->attributes['the_geom']){
											if($this->formvars['export_format'] == 'CSV' OR $this->layerdaten['export_privileg'][$selectindex] != 1){echo 'display:none"';} echo '" id="geom_div"';
										} ?>
									">
										<input class="attribute_selector" id="check_attribute_<? echo $j; ?>" type="checkbox" <? if($this->formvars['load'] OR $this->formvars['check_'.$this->attributes['name'][$j]] == 1)echo 'checked'; ?> value="1" name="check_<? echo $this->attributes['name'][$j]; ?>"><?php
										if($this->attributes['alias'][$j] != ''){
											echo $this->attributes['alias'][$j];
										}
										else {
											if($this->attributes['name'][$j] == $this->attributes['the_geom']){
												echo $strNameGeometryField;
											}
											else {
												echo $this->attributes['name'][$j];
											}
										} ?>
									</div><?
								}
								$j++;
							} ?>
						</div><?
						$rest=$rest-1;
						if ($rest==0) {
							$r=0;
						}
					} ?>
				</div>
				&nbsp;&nbsp;&nbsp;&nbsp;<a id="selectall_link" href="javascript:selectall('<? echo $this->attributes['the_geom']; ?>')"><? echo $strSelectAll; ?></a>
			</div><?

			if($groupnames OR $document_attributes or true){ ?>
				<div style="border-bottom:1px solid #C3C7C3; border-left: 1px solid #C3C7C3; border-right: 1px solid #C3C7C3; padding-top:10px; padding-bottom:5px; padding-left:5px; padding-right:5px;">
					&nbsp;&nbsp;<? echo $strOptions; ?>:<br>
					<table cellspacing="7">
						<tr>
							<td>
								&nbsp;<? echo $strFilename; ?>:&nbsp;&nbsp;<input type="text" name="layer_name" value="<? echo sonderzeichen_umwandeln($this->layerdaten['Bezeichnung'][$selectindex]); ?>">
							</td>
						</tr>
				<? 	if ($this->layerset[0]['metalink'] != '') { ?>
						<tr>
							<td>
								<input type="checkbox" name="with_metadata_document" value="1" <? if ($this->formvars['with_metadata_document'] == 1)echo 'checked'; ?>> <? echo $strExportMetadatadocument; ?>
							</td>
						</tr>
				<? 	}
						if ($this->layerset[0]['terms_of_use_link'] != '') { ?>
						<tr style="display: none">
							<td>
								<input type="checkbox" name="with_terms_of_use_document" value="1" checked> <? echo $strExportTermsOfUsedocument; ?>
							</td>
						</tr>
				<? 	}
						if ($groupnames){ ?>
							<tr>
								<td>
									<div id="groupnames_div" style="<?
										if ($this->formvars['export_format'] != 'CSV'){
											echo 'display: none';
										}
										 ?>"><input type="checkbox" name="export_groupnames" <? if ($this->formvars['export_groupnames'] == 1)echo 'checked'; ?>> <? echo $strExportGroupnames; ?>
									</div>
								</td>
							</tr><?
						}
						if ($document_attributes or true){ ?>
							<tr>
								<td>
									<input type="checkbox" onclick="select_document_attributes('<? echo implode(',', $document_ids); ?>');" name="download_documents" <? if ($this->formvars['download_documents'] == 1)echo 'checked'; ?>><? echo $strDownloadDocuments; ?>
								</td>
							</tr><?
						} ?>
					</table>
				</div><?
			} ?>
			
			<input type="hidden" id="exporttimestamp" value="<? echo $exporttimestamp; ?>">

			<div style="margin-top:30px; margin-bottom:10px; text-align: center;">
				<input name="cancel" type="button" onclick="home();" value="<? echo $strButtonCancel; ?>"><?php
					if ($this->formvars['selected_layer_id'] != '') { ?>
						<input name="create" type="button" onclick="data_export();" value="<? echo $strButtonGenerateShapeData; ?>"><?php
					} ?>
			</div>

		</td>
	</tr>
  <tr>
  	<td align="center" colspan="2">
  		<input id="go_plus" type="hidden" name="go_plus" value="">
  	</td>
  </tr>
  <tr<?php if ($simple OR $this->attributes['the_geom'] == '') echo ' style="display: none;"'; ?>>
    <td align="right">
			<input type="checkbox" name="within" value="1" <? if($this->formvars['within'] == 1)echo 'checked'; ?>>
			<? echo $strWithin; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="singlegeom" value="true" <? if($this->formvars['singlegeom'])echo 'checked="true"'; ?>>
			<? echo $strSingleGeoms; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<? echo $this->strUseGeometryOf; ?>:
  		<select name="geom_from_layer" onchange="geom_from_layer_change();">
  			<option value=""><?php echo $this->strPleaseSelect; ?></option>
  			<?
  				for($i = 0; $i < count($this->queryable_vector_layers['ID']); $i++){
  					echo '<option';
  					if($this->formvars['geom_from_layer'] == $this->queryable_vector_layers['ID'][$i]){echo ' selected';}
  					echo ' value="'.$this->queryable_vector_layers['ID'][$i].'">'.$this->queryable_vector_layers['Bezeichnung'][$i].'</option>';
  				}
  			?>
  		</select>
      <?php
 				include(LAYOUTPATH.'snippets/SVG_polygon.php')
			?>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>

<input type="hidden" name="selectstring_save" value="<? echo $this->formvars['selectstring_save'] ?>">
<input type="hidden" name="client_epsg" value="<? echo $this->user->rolle->epsg_code ?>">
<input type="hidden" name="go" value="Daten_Export">
<input type="hidden" name="area" value="">
<INPUT TYPE="hidden" NAME="export_columnname" VALUE="<? echo $this->formvars['columnname'] ?>">
<input type="hidden" name="always_draw" value="<? echo $always_draw; ?>"><?php
if ($simple) { ?>
	<input type="hidden" name="simple" value="1"><?php
} ?>

