<?php
	global $supportedLanguages;
	$language_file_name = 'layer_formular_' . rolle::$language . '.php';

	$language_file = LAYOUTPATH . 'languages/' . $language_file_name;
	include(LAYOUTPATH . 'languages/_include_language_files.php');

	$language_file = PLUGINS . 'mobile/languages/' . $language_file_name;
	include(LAYOUTPATH . 'languages/_include_language_files.php');

	$language_file = PLUGINS . 'portal/languages/' . $language_file_name;
	include(LAYOUTPATH . 'languages/_include_language_files.php');

	include_once(CLASSPATH . 'FormObject.php'); ?>
<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<script type="text/javascript">
	function gotoStelle(event, option_obj){
		if(event.layerX > 300){
			location.href = 'index.php?go=Stelleneditor&selected_stelle_id=' + option_obj.value + '&csrf_token=<? echo $_SESSION['csrf_token']; ?>';
		}
	}

	function showGruppenEditor(gruppeId, layerId) {
		location.href = 'index.php?go=Layergruppe_Editor&selected_group_id=' + gruppeId + '&selected_layer_id=' + layerId + '&csrf_token=<? echo $_SESSION['csrf_token']; ?>';
	}

	function create_generic_data_sql(layer_id) {
		$('#waitingdiv').show();
		$.ajax({
			url: 'index.php',
			data: {
				go : 'get_generic_layer_data_sql',
				selected_layer_id: layer_id,
				csrf_token: '<? echo $_SESSION['csrf_token']; ?>'
			},
			complete: function () {
				$('#waitingdiv').hide();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				message([{ type: 'error', msg: jqXHR + ' ' + textStatus + ' ' + errorThrown}]);
			},
			success: function(response) {
				if (response.success) {
					message([{ type: 'info', msg : 'SQL für Haupttabelle:<br><textarea style="width: 450px; height: 450px">' + response.data_sql + '</textarea>' }]);
				}
				else {
					message([{ type: 'error', msg : response.msg}]);
				}
			}
		});
}

	function updateConnection(){
		if(document.getElementById('connectiontype').value == 6){
			document.getElementById('connection_div').style.display = 'none';
			document.getElementById('connection_id_div').style.display = '';
		}
		else{
			document.getElementById('connection_div').style.display = '';
			document.getElementById('connection_id_div').style.display = 'none';
		}
	}

	function testConnection() {
		if (document.getElementById('connectiontype').value == 7) {
			getCapabilitiesURL=document.getElementById('connection').value+'&service=WMS&request=GetCapabilities';		
			getMapURL = document.getElementById('connection').value+'&SERVICE=WMS&REQUEST=GetMap&srs=EPSG:<?php echo $this->user->rolle->epsg_code; ?>&BBOX=<?php echo $this->user->rolle->oGeorefExt->minx; ?>,<?php echo $this->user->rolle->oGeorefExt->miny; ?>,<?php echo $this->user->rolle->oGeorefExt->maxx; ?>,<?php echo $this->user->rolle->oGeorefExt->maxy; ?>&WIDTH=<?php echo $this->user->rolle->nImageWidth; ?>&HEIGHT=<?php echo $this->user->rolle->nImageHeight; ?>';
			if (getMapURL.toLowerCase().indexOf('version') == -1){
				getMapURL += '&version=' + document.GUI.wms_server_version.value;
			}
			if (getMapURL.toLowerCase().indexOf('format') == -1){
				getMapURL += '&format=' + document.GUI.wms_format.value;
			}
			if (getMapURL.toLowerCase().indexOf('layers') == -1){
				getMapURL += '&layers=' + document.GUI.wms_name.value;
			}
			if (getMapURL.toLowerCase().indexOf('styles') == -1){
				getMapURL += '&styles=';
			}
			document.getElementById('test_img').src = getMapURL;
			document.getElementById('test_img').style.display='block';
			document.getElementById('test_link').href=getCapabilitiesURL;
			document.getElementById('test_link').innerHTML=getCapabilitiesURL;
		}
		else {
			getCapabilitiesURL=document.getElementById('connection').value+'&service=WFS&request=GetCapabilities';
			document.getElementById('test_link').href=getCapabilitiesURL;
			document.getElementById('test_link').innerHTML=getCapabilitiesURL;
		}
	}
	
	function toggleForm(id){
		if(id == 'stellenzuweisung'){
			document.GUI.stellenzuweisung.value = 1;
			document.getElementById('layerform').style.display = 'none';
			document.getElementById('layerform_link').classList.remove('navigation-selected');
			document.getElementById('saveAsNewLayerButton').style.display = 'none';
			document.getElementById('layer_formular_delete_button').style.display = 'none';
		}
		else{
			document.GUI.stellenzuweisung.value = 0;
			document.getElementById('stellenzuweisung').style.display = 'none';
			document.getElementById('stellenzuweisung_link').classList.remove('navigation-selected');
			document.getElementById('saveAsNewLayerButton').style.display = 'inline-block';
			document.getElementById('layer_formular_delete_button').style.display = 'inline-block';
		}
		document.getElementById(id).style.display = 'inline-block';
		document.getElementById(id+'_link').classList.add('navigation-selected');
	}

	function mandatoryValuesMissing() {
		if (document.getElementById('gruppe-select').value == '') {
			message([{ type: 'error', msg: 'Es muss eine Gruppe ausgewählt sein!'}]);
			return true;
		}
		return false;
	}

	function loadMaintables(connection_id) {
		fetch(`index.php?go=Layereditor_get_maintables&connection_id=${connection_id}&csrf_token=<? echo $_SESSION['csrf_token']; ?>`)
		.then(response => {
				if (!response.ok) {
					message([{ type: 'error', msg: `Fehler bei der Abfrage der Tabellen der Connection.<p>HTTP-Status Code: ${response.status} ${response.statusText}` }]);
					response.text().then(text => {
						message([{ type: 'error', msg: `<p>${text}` }]);
					});
				}

				const contentType = response.headers.get("content-type");
				if (contentType && contentType.indexOf("application/json") !== -1) {
					response.json().then(data => {
						if (data.success) {
							if (data.tables.length == 0) {
								$('#maintables_tr').hide();
								resetForm('GUI');
								message([{ type: 'info', msg: `<? echo $strNoConnectionIdSelected; ?>`}]);
							}
							else {
								let maintableSelectField = document.getElementById("maintable_select");
								data.tables.forEach((maintable) => {
									let option = document.createElement("option");
									option.text = `${connection_id}.${maintable.schema_name}.${maintable.name}`;
									maintableSelectField.add(option);
								});
								$('#maintables_tr').show();
							}
						}
						else {
							message([{ type: 'error', msg: `Fehler bei der Abfrage der Tabellen der Connection. ${data.err_msg}` }]);
						}
					});
				}
				else {
					console.log('Es ist kein JSON');
					response.text().then(text => {
						console.log(text);
						message([{ type: 'error', msg: `Fehler bei der Abfrage der Tabellen der Connection.<p>${text}` }]);
					});
				}
			})
			.catch(error => message([ { type: 'error', msg: `${error.message}`}]));
	}

	function fillFromMaintable(value) {
		let elm = document.getElementById("GUI").elements;
		if (value) {
			let [connection_id, schema_name, table_name] = value.split('.');
			elm["name"].value = table_name;
			elm["alias"].value = table_name.split('_').map((word) => { return word[0].toUpperCase() + word.substring(1); }).join(" ");
			elm["connectiontype"].value = 6;
			elm["connectiontype"].onchange();
			elm["connection_id"].value = connection_id;
			elm["maintable"].value = table_name;
			elm["schema"].value = schema_name;
			elm["sizeunits"].value = 6;

			fetch(`index.php?go=Layereditor_info_from_maintable&connection_id=${connection_id}&schema_name=${schema_name}&table_name=${table_name}&csrf_token=<? echo $_SESSION['csrf_token']; ?>`)
				.then(response => {
					if (!response.ok) {
						message([{ type: 'error', msg: `Fehler bei der Abfrage der Maintable-Daten.<p>HTTP-Status Code: ${response.status} ${response.statusText}` }]);
						response.text().then(text => {
							message([{ type: 'error', msg: `<p>${text}` }]);
						});
					}

					const contentType = response.headers.get("content-type");
					if (contentType && contentType.indexOf("application/json") !== -1) {
						response.json().then(data => {
							console.log(data);
							if (data.success) {
								let elm = document.getElementById("GUI").elements;
								elm["epsg_code"].value = data.epsg_code || '';
								elm["oid"].value = data.oid_column;
								elm["datentyp"].value = data.Datentyp;
								elm["pfad"].value =
`SELECT
  *
FROM
  ${data.table_name}
WHERE
  true`;
								elm["data"].value = (data.geom_column ? `${data.geom_column} from (
select
  ${data.oid_column},
  ${data.geom_column}
from
  ${data.schema_name}.${data.table_name}
) as foo using unique ${data.oid_column} using srid=${data.epsg_code}` : '');
							}
							else {
								message([{ type: 'error', msg: `Fehler bei der Abfrage der Maintable-Daten. ${data.err_msg}` }]);
							}
						});
					}
					else {
						console.log('Es ist kein JSON');
						response.text().then(text => {
							console.log(text);
							message([{ type: 'error', msg: `Fehler bei der Abfrage der Maintable-Daten.<p>${text}` }]);
						});
					}
				})
				.catch(error => message([ { type: 'error', msg: `${error.message}`}]));
		}
		else {
			resetForm('GUI');
		}
	}

	function resetForm(form) {
		let elm = document.getElementById(form).elements;
		elm["name"].value = '';
		elm["alias"].value = '';
		elm["connectiontype"].value = '';
		elm["connectiontype"].onchange();
		elm["connection_id"].value = '';
		elm["maintable"].value = '';
		elm["schema"].value = '';
		elm["sizeunits"].value = '';
		elm["epsg_code"].value = '';
		elm["oid"].value = '';
		elm["datentyp"].value = '';
		elm["pfad"].value = '';
		elm["data"].value = '';
	}

	function add_labelitem(){
		var table = document.getElementById('labelitems_table');
		table.firstElementChild.appendChild(table.firstElementChild.firstElementChild.nextElementSibling.cloneNode(true));
	}

	function unselectItem(evt) {
		console.log('click on ', evt.target);
		const datasource_id = $(evt).attr('datasource_id');
		$(evt).parent().remove();
		//console.log('unselect datasource_id', datasource_id);
		$(`#datasource_ids option[value=${datasource_id}]`).prop('selected', false);
		$(`.selected-item[datasource_id="${datasource_id}"]`)
			.toggleClass('selectable-item selected-item')
			.on('click', (evt) => {
				const datasource_id = $(evt.target).attr('datasource_id');
				//console.log('click on selectable item %o with datasource_id %s', evt.target, datasource_id);
				// add clicked item to chosen-choices and select in select field
				$('#chosen-choices').append(`<li class="chosen-item"><span>${evt.target.innerHTML}</span><a datasource_id="${datasource_id}" class="chosen-item-close" data-option-array-index="5" onclick="unselectItem(this)"><i class="fa fa-times" style="color: gray; float: right; margin-right: -16px; margin-top: -1px;"></i></a></li>`);
				$(`#datasource_ids option[value=${datasource_id}]`).prop('selected', true);
				$(`.selectable-item[datasource_id="${datasource_id}"]`).toggleClass('selectable-item selected-item').off();
				$('#chosen-drop').hide();
				$('#add-item-button').show();
			})
			.on('mouseover', (evt) => {
				//console.log('mouseover on selectable-item', evt.target);
				$(evt.target).toggleClass('highlighted-item').siblings().removeClass('highlighted-item');
			});

		$('#add-item-button').show();
		$('#chosen-drop').hide();
	}

	keypress_bound_ctrl_s_button_id = 'layer_formular_submit_button';
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
	
	#form input[type="text"], #form select, #form textarea {
		width: 95%;
	}

	#form textarea {
		height: 350px;
	}

	#form input[type="numeric"] {
		width: 34px;
	}

	#stellenzuweisung{
		display: none;
		width: 100%;
	}
	
	.layerform_header{
		background: rgb(199, 217, 230);
	}

	#labelitems_table tr:nth-of-type(2){
		display: none;
	}

	#labelitems_table tr:first-child:nth-last-child(2){
		display: none;
	}

</style>

<table>
	<tr>
		<th align="right">
			<span class="px17 fetter"><? echo $strLayer;?>:</span>
		</td>
    <td>
			<select id="selected_layer_id" style="min-width:250px" size="1" name="selected_layer_id" onchange="document.GUI.submit();" <?php if(count($this->layerdaten['ID'])==0){ echo 'disabled';}?>>
				<option value="">--------- <?php echo $this->strPleaseSelect; ?> --------</option><?
				for ($i = 0; $i < count($this->layerdaten['ID']); $i++){
					echo '<option';
					if ($this->layerdaten['ID'][$i] == $this->formvars['selected_layer_id']){
						echo ' selected';
					}
					echo ' value="'.$this->layerdaten['ID'][$i].'">' . $this->layerdaten['Bezeichnung'][$i] . ($this->layerdaten['alias'][$i] != '' ? ' [' . $this->layerdaten['alias'][$i] . ']' : '') . '</option>';
				} ?>
			</select>
		</td>
	</tr><?
	if (!$this->formvars['selected_layer_id']) { ?>
		<tr>
			<td align="right">
				<span class="px17 fetter"><?php echo $strConnection; ?>:</span>
			</td>
			<td><?
				echo FormObject::createSelectField(
					'connection_select',
					array_map(
						function($connection) {
							return array('value' => $connection->get('id'), 'output' => $connection->get_connection_string() . ' id: ' . $connection->get_id());
						},
						$this->connections
					),
					$this->formvars['connection_select'],
					1,
					'style="margin-left: 5px"',
					'loadMaintables(this.value)',
					'',
					'',
					'',
					'--------- ' . $this->strPleaseSelect . '--------'
				); ?>
			</td>
		</tr>
		<tr id="maintables_tr" style="display: none">
			<td align="right">
				<span class="px17 fetter"><? echo $strMaintable; ?>:</span>
			</td>
			<td>
				<select id="maintable_select" onchange="fillFromMaintable(this.value)">
					<option value="">--------- <? echo $this->strPleaseSelect; ?>--------</option>
				</select>
				<span data-tooltip="<? echo $strNewLayerFromMaintableHint; ?>"></span>
			</td>
		</tr><?
	} ?>
</table>
<a style="float: right; margin-top: -30px; margin-right: 10px;" href="javascript:window.scrollTo(0, document.body.scrollHeight);"	title="nach unten">
	<i class="fa fa-arrow-down hover-border" aria-hidden="true"></i>
</a>

<table border="0" cellpadding="0" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>" style="margin: 10px">
	<tr align="center"> 
		<td style="width: 100%;">
			<table cellpadding="0" cellspacing="0" class="navigation">
				<tr>
					<th id="layerform_link">
						<a href="javascript:toggleForm('layerform');"><div><? echo $strCommonData; ?></div></a>
					</th><?
					if (!in_array($this->formvars['datentyp'], [MS_LAYER_QUERY])) { ?>
						<th>
							<a href="index.php?go=Klasseneditor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div><? echo $strClasses; ?></div></a>
						</th>
						<th>
							<a href="index.php?go=Style_Label_Editor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div><? echo $strStylesLabels; ?></div></a>
						</th><?
					}
					if (in_array($this->formvars['connectiontype'], [MS_POSTGIS, MS_WFS])) { ?>
						<th>
							<a href="index.php?go=Attributeditor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div><? echo $strAttributes; ?></div></a>
						</th><?
					} ?>
					<th id="stellenzuweisung_link">
						<a href="javascript:toggleForm('stellenzuweisung');"><div><? echo $strStellenAsignment; ?></div></a>
					</th><?
					if (in_array($this->formvars['connectiontype'], [MS_POSTGIS, MS_WFS])) { ?>
						<th>
							<a href="index.php?go=Layerattribut-Rechteverwaltung&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div><? echo $strPrivileges; ?></div></a>
						</th><?
					}
					if (!in_array($this->formvars['datentyp'], [MS_LAYER_QUERY])) { ?>
						<th>
							<a href="index.php?go=show_layer_in_map&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&zoom_to_layer_extent=1&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i class="fa fa-map" style="width: 50px"></i></a>
						</th><?
					} ?>
				</tr>
			</table>
		</td>
	</tr>	
</table>

<table id="form" border="0" cellpadding="0" cellspacing="0" style="width: 100%">
	<tr>
		<td align="center" style="padding: 10px;">
			<div id="layerform" style="width: 100%; background-color: #f8f8f9">
				<table border="0" cellspacing="0" cellpadding="3" style="width: 100%;border:1px solid #bbb">
					<tr align="center">
						<th class="fetter layerform_header"  style="border-bottom:1px solid #C3C7C3" colspan="3"><?php echo $strBaseParameters; ?></th>
					</tr>
					<tr>
						<th class="fetter" width="300px" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strLayerID; ?></th>
						<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="id" type="text" value="<?php echo $this->formvars['selected_layer_id']; ?>" size="5" maxlength="11">
							<input name="old_id" type="hidden" value="<?php echo $this->formvars['selected_layer_id']; ?>">
							<i id="show_duplicate_table_field_button" class="fa fa-clone" aria-hidden="true" onclick="$('.duplicate-table-fields').show(); $(this).hide();" style="float: right; <? echo ($this->formvars['duplicate_criterion'] != '' ? 'display: none' : ''); ?>" title="<? echo $strDuplicateTableFieldsOpenTitle; ?>"></i>
						</td>
					</tr>
					<tr class="duplicate-table-fields" style="<? echo ($this->formvars['duplicate_criterion'] == '' ? 'display: none' : ''); ?>">
						<td colspan="3" style="border-bottom:1px solid #C3C7C3">
							<div class="form-field">
								<div class="form-label fetter">
									<label><? echo $strDuplicateFromLayerId; ?></label>
								</div>
								<div class="form-value"><?
									$duplicate_from_layer_options = array();
									foreach ($this->layerdaten['ID'] AS $index => $layer_id) {
										if ($layer['ID'] != $this->formvars['selected_layer_id']) {
											$duplicate_from_layer_options[] = array(
												'value' => $layer_id,
												'output' => $this->layerdaten['Bezeichnung'][$index] . ($this->layerdaten['alias'][$index] ? ' (' . $this->layerdaten['alias'][$index] . ')' : '')
											);
										}
									}
									echo FormObject::createSelectField(
										'duplicate_from_layer_id',
										$duplicate_from_layer_options,
										$this->formvars['duplicate_from_layer_id'],
										1,
										'',
										'',
										'',
										'',
										'',
										$strNotDublicate
									); ?>&nbsp;
									<span data-tooltip="<? echo $strDuplicateFromLayerIdHelp; ?>"></span>
								</div>
							</div>
							<i class="fa fa-close" aria-hidden="true" onclick="$('.duplicate-table-fields').hide(); $('#show_duplicate_table_field_button').show();" style="float: right" title="<? echo $strDuplicateTableFieldsCloseTitle; ?>"></i>
							<div class="clear"></div>
						</td>
					</tr>
					<tr class="duplicate-table-fields" style="<? echo ($this->formvars['duplicate_criterion'] == '' ? 'display: none' : ''); ?>">
						<td colspan="3" style="border-bottom:1px solid #C3C7C3">
							<div class="form-field">
								<div class="form-label fetter">
									<label><?php echo $strDuplicateCriterion; ?></label>
								</div>
								<div class="form-value">
									<input name="duplicate_criterion" type="text" value="<?php echo $this->formvars['duplicate_criterion']; ?>" size="50" maxlength="255">&nbsp;
									<span data-tooltip="<? echo $strDuplicateCriterionHelp; ?>"></span>
								</div>
							</div>
							<div class="clear"></div>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strName; ?>*</th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="name" type="text" value="<?php echo $this->formvars['name']; ?>" size="50" maxlength="100">
						</td>
					</tr><?
					foreach($supportedLanguages as $language){
						if($language != 'german'){	?>
							<tr>
								<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strName.' '.$language; ?></th>
								<td colspan=2 style="border-bottom:1px solid #C3C7C3">
										<input name="name_<? echo $language; ?>" type="text" value="<?php echo $this->formvars['Name_'.$language]; ?>" size="50" maxlength="100">
								</td>
							</tr><?
						}
					} ?>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strAlias; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="alias" type="text" value="<?php echo $this->formvars['alias']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strGroup; ?>*</th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3"><?
							$group_options = array_map(
								function($group) {
									return array(
										'value' => $group['ID'],
										'output' => $group['Bezeichnung'],
										'selectable_for_shared_layers' => $group['selectable_for_shared_layers']
									);
								},
								vectors_to_assoc_array($this->Groups)
							);
							if (!$this->is_admin_user($this->user->id) AND $this->formvars['shared_from']) {
								$group_options = array_filter(
									$group_options,
									function ($option) {
										return $option['selectable_for_shared_layers'] == '1';
									}
								);
							}
							echo FormObject::createSelectField(
								'gruppe',																	# name
								$group_options,														# options
								$this->formvars['gruppe'],								# value
								1,																				# size
								'',																				# style
								'document.GUI.gruppenaenderung.value=1',	# onchange
								'gruppe-select',													# id
								'',																				# multiple
								'',																				# class
								'-- ' . $this->strPleaseSelect . ' --'		# first option
							); ?>
							<i class="fa fa-pencil" aria-hidden="true" onclick="showGruppenEditor($('#gruppe-select').val(), <? echo $this->formvars['selected_layer_id']; ?>)" style="margin-left: 5px"></i>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strDataType; ?>*</th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<select name="datentyp">
									<option value=""><?php echo $this->strPleaseSelect; ?></option>
									<option <? if($this->formvars['datentyp'] == '0'){echo 'selected ';} ?>value="0">MS_LAYER_POINT</option>
									<option <? if($this->formvars['datentyp'] == 1){echo 'selected ';} ?>value="1">MS_LAYER_LINE</option>
									<option <? if($this->formvars['datentyp'] == 2){echo 'selected ';} ?>value="2">MS_LAYER_POLYGON</option>
									<option <? if($this->formvars['datentyp'] == 3){echo 'selected ';} ?>value="3">MS_LAYER_RASTER</option>
									<option <? if($this->formvars['datentyp'] == 4){echo 'selected ';} ?>value="4">MS_LAYER_ANNOTATION</option>
									<option <? if($this->formvars['datentyp'] == 5){echo 'selected ';} ?>value="5">MS_LAYER_QUERY</option>
									<option <? if($this->formvars['datentyp'] == 6){echo 'selected ';} ?>value="6">MS_LAYER_CIRCLE</option>
									<option <? if($this->formvars['datentyp'] == 7){echo 'selected ';} ?>value="7">MS_LAYER_TILEINDEX</option>
									<option <? if($this->formvars['datentyp'] == 8){echo 'selected ';} ?>value="8">MS_LAYER_CHART</option>
								</select>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strConnectionType; ?>*</th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<select id="connectiontype" name="connectiontype" onchange="updateConnection();">
									<option value=""><?php echo $this->strPleaseSelect; ?></option>
									<option <? if($this->formvars['connectiontype'] == '0'){echo 'selected ';} ?>value="0">MS_INLINE</option>
									<option <? if($this->formvars['connectiontype'] == 1){echo 'selected ';} ?>value="1">MS_SHAPEFILE</option>
									<option <? if($this->formvars['connectiontype'] == 2){echo 'selected ';} ?>value="2">MS_TILED_SHAPEFILE</option>
									<option <? if($this->formvars['connectiontype'] == 3){echo 'selected ';} ?>value="3">MS_SDE</option>
									<option <? if($this->formvars['connectiontype'] == 4){echo 'selected ';} ?>value="4">MS_OGR</option>
									<option <? if($this->formvars['connectiontype'] == 5){echo 'selected ';} ?>value="5">MS_TILED_OGR</option>
									<option <? if($this->formvars['connectiontype'] == 6){echo 'selected ';} ?>value="6">MS_POSTGIS</option>
									<option <? if($this->formvars['connectiontype'] == 7){echo 'selected ';} ?>value="7">MS_WMS</option>
									<option <? if($this->formvars['connectiontype'] == 8){echo 'selected ' ;} ?>value="8">MS_ORACLESPATIAL</option>
									<option <? if($this->formvars['connectiontype'] == 9){echo 'selected ';} ?>value="9">MS_WFS</option>
									<option <? if($this->formvars['connectiontype'] == 10){echo 'selected ';} ?>value="10">MS_GRATICULE</option>
									<option <? if($this->formvars['connectiontype'] == 11){echo 'selected ';} ?>value="11">MS_MYGIS</option>
								</select>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strConnection; ?>*</th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
							<div id="connection_div" <? if($this->formvars['connectiontype'] == MS_POSTGIS){echo 'style="display: none"';} ?>>
								<textarea id="connection" name="connection" cols="33" rows="2"><?	echo $this->formvars['connection']; ?></textarea>
								<input
									type="button"
									onclick="testConnection();"
									value="Test"
									style="display: <? echo (in_array($this->formvars['connectiontype'], array(MS_WMS, MS_WFS)) ? 'inline' : 'none'); ?>;"
								><br>
								<img border="1" id ="test_img" src="" style="display: none;"><br>
								<a id="test_link" href="" target="_blank"></a>
							</div>
							<div id="connection_id_div" <? if($this->formvars['connectiontype'] != MS_POSTGIS){echo 'style="display: none"';} ?>>
					<? 		include_once(CLASSPATH . 'Connection.php');
								$connections = Connection::find($this);
								echo FormObject::createSelectField(
									'connection_id',
									array_map(
										function($connection) {
											return array(
												'value' => $connection->get('id'),
												'output' => $connection->get('name')
											);
										},
										$connections
									),
									$this->formvars['connection_id']
								); ?>
								<a href="index.php?go=connections_anzeigen&selected_layer_id=<? echo $this->formvars['selected_layer_id']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i class="fa fa-pencil fa_lg" style="margin-left: 5px;"></i></a>
							</div>
						</td>
					</tr>
		<? 	if($this->formvars['connectiontype'] == MS_WMS){ ?>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strPrintConnection; ?></th>
						<td style="border-bottom:1px solid #C3C7C3">
							<textarea name="printconnection" cols="33" rows="2"><? echo $this->formvars['printconnection'] ?></textarea>
						</td>
					</tr>
		<?  } ?>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strEpsgCode; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<select name="epsg_code">
									<option value=""><?php echo $this->strPleaseSelect; ?></option>
									<? 
									foreach($this->epsg_codes as $epsg_code){
										echo '<option ';
										if($this->formvars['epsg_code'] == $epsg_code['srid'])echo 'selected ';
										echo ' value="'.$epsg_code['srid'].'">'.$epsg_code['srid'].': '.$epsg_code['srtext'].'</option>';
									}
									?>
								</select>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strPath; ?></th>
						<td colspan=2 valign="top" style="border-bottom:1px solid #C3C7C3">
							<textarea name="pfad" cols="33" rows="4"><? echo $this->formvars['pfad'] ?></textarea>&nbsp;
							<span data-tooltip="Das Query-SQL ist das SQL-Statement, welches für die Sachdatenabfrage verwendet wird. Es kann eine beliebige Abfrage auf Tabellen oder Sichten sein, eine WHERE-Bedingung ist aber erforderlich.&#xa;Der Schemaname wird hier nicht angegeben, sondern im Feld 'Schema'.&#xa;Wenn Unterabfragen verwendet werden, müssen 'select', 'from' und 'where' in der Unterabfrage klein geschrieben werden und in der Hauptabfrage groß!"></span>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
							<?php echo $strData; ?>
						</th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
							<textarea name="data" cols="33" rows="4"><? echo $this->formvars['data'] ?></textarea>&nbsp;
							<span data-tooltip="Das Data-Feld wird vom Mapserver für die Kartendarstellung verwendet (siehe Mapserver-Doku). Etwaige Schemanamen müssen hier angegeben werden."></span>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
							<?php echo $strMaintable; ?> <a title="Zeige SELECT-Statement für <? echo $strMaintable; ?>" href="javascript:create_generic_data_sql(<? echo $this->formvars['selected_layer_id']; ?>);"><img src="graphics/autogen.png"></a>
						</th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="maintable" type="text" value="<?php echo $this->formvars['maintable']; ?>" size="50" maxlength="100">&nbsp;
							<span data-tooltip="Die Haupttabelle ist diejenige der im Query-SQL-Statement abgefragten Tabellen, die die ID-Spalte liefern soll. Nur auf dieser Tabelle finden Schreiboperationen statt.&#xa;&#xa;Die Haupttabelle muss eine eindeutige ID-Spalte besitzen, welche allerdings nicht im SQL angegeben werden muss.&#xa;&#xa;Ist das Feld Haupttabelle leer, wird der Name der Haupttabelle automatisch eingetragen. Bei einer Layerdefinition über mehrere Tabellen hinweg kann es sein, dass kvwmap die falsche Tabelle als Haupttabelle auswählt. In diesem Fall kann hier händisch die gewünschte Tabelle eingetragen werden. Achtung: Wenn die Tabellennamen im Query-SQL geändert werden, muss auch der Eintrag im Feld Haupttabelle angepasst werden!"></span>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strIdAttribute; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="oid" type="text" value="<?php echo $this->formvars['oid']; ?>" size="36" maxlength="100">&nbsp;
							<span data-tooltip="Hier muss die Spalte aus der Haupttabelle angegeben werden, mit der die Datensätze identifiziert werden können (z.B. der Primärschlüssel oder die oid)."></span>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strSchema; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="schema" type="text" value="<?php echo $this->formvars['schema']; ?>" size="50" maxlength="100">
						</td>
					</tr>
				</table>
				<br>
				<table border="0" cellspacing="0" cellpadding="3" style="width:100%; border:1px solid #bbb">
					<tr align="center">
						<th class="fetter layerform_header"  style="border-bottom:1px solid #C3C7C3" colspan="3"><?php echo $strMapParameters; ?></th>
					</tr>
					<tr>
						<th class="fetter" align="right" style="width:300px; border-bottom:1px solid #C3C7C3"><?php echo $strDrawingOrder; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="drawingorder" type="text" value="<?php echo $this->formvars['drawingorder']; ?>" size="50" maxlength="20">&nbsp;
						</td>
					</tr>					
					<tr>
						<th class="fetter" align="right" style="width:300px; border-bottom:1px solid #C3C7C3"><?php echo $strSelectionType; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="selectiontype" type="text" value="<?php echo $this->formvars['selectiontype']; ?>" size="50" maxlength="20">&nbsp;
								<span data-tooltip="<? echo $strSelectionTypeHelp; ?>"></span>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strTileIndex; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="tileindex" type="text" value="<?php echo $this->formvars['tileindex']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strTileItem; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="tileitem" type="text" value="<?php echo $this->formvars['tileitem']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strLabelAngleItem; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="labelangleitem" type="text" value="<?php echo $this->formvars['labelangleitem']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strLabelItem; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
							<div style="display:flex">
								<table id="labelitems_table" style="width: 93%" cellpadding="1" cellspacing="0">
									<tr>
										<th class="fetter">&nbsp;Attribut:</th>
										<th class="fetter">&nbsp;Alias:</th>
									</tr>
									<? for ($l = -1; $l < count_or_0($this->formvars['labelitems']); $l++) { 
											if ($l != -1) {
												$name = $this->formvars['labelitems'][$l]->get('name');
												$alias = $this->formvars['labelitems'][$l]->get('alias');
											}
									?>
									<tr>
										<td>
											<input name="labelitems_name[]" type="text" value="<? echo $name; ?>" size="25" maxlength="100">
										</td>
										<td>
											<input name="labelitems_alias[]" type="text" value="<? echo $alias; ?>" size="25" maxlength="100">
										</td>
										<td>
											<i class="fa fa-times" style="color: gray; cursor: pointer" onclick="this.closest('tr').remove();"></i>
										</td>
									</tr>
									<? } ?>
								</table>
								<i class="fa fa-plus" style="color: gray; cursor: pointer; height: 12px;  margin: 4px 0 0 0" onclick="add_labelitem();"></i>
								<span data-tooltip="<? echo $strLabelItemHelp; ?>" style="height: 12px; margin: 4px 13px 5px;"></span>
							</div>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strLabelMinScale; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="labelminscale" type="text" value="<?php echo $this->formvars['labelminscale']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strLabelMaxScale; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="labelmaxscale" type="text" value="<?php echo $this->formvars['labelmaxscale']; ?>" size="50" maxlength="100">
						</td>
					</tr>					
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strLabelRequires; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="labelrequires" type="text" value="<?php echo $this->formvars['labelrequires']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strClassItem; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="classitem" type="text" value="<?php echo $this->formvars['classitem']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strStyleItem; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="styleitem" type="text" value="<?php echo $this->formvars['styleitem']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strSizeUnits; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<select name="sizeunits">
									<option value=""><?php echo $this->strPleaseSelect; ?></option>
									<option <? if($this->formvars['sizeunits'] == MS_PIXELS){echo 'selected';} ?> value="<? echo MS_PIXELS; ?>">Pixel</option>
									<option <? if($this->formvars['sizeunits'] == MS_METERS){echo 'selected';} ?> value="<? echo MS_METERS; ?>">Meter</option>								
								</select>
						</td>
					</tr>					
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strClassification; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="layer_classification" type="text" value="<?php echo $this->formvars['classification']; ?>" size="50" maxlength="50">&nbsp;
							<span data-tooltip="<? echo $strClassificationHelp; ?>"></span>	
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strClusterMaxdistance; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="cluster_maxdistance" type="text" value="<?php echo $this->formvars['cluster_maxdistance']; ?>" size="50" maxlength="11">&nbsp;
							<span data-tooltip="Bei Punktlayern kann durch Angabe dieses Wertes die Clusterbildung aktiviert werden.&#xa;Der Wert ist der Radius in Pixeln, in dem Punktobjekte zu einem Cluster zusammengefasst werden.&#xa;Damit die Cluster dargestellt werden können, muss es eine Klasse mit der Expression&#xa;('[Cluster_FeatureCount]' != '1')&#xa;geben. Cluster_FeatureCount kann auch als Labelitem verwendet werden, um die Anzahl der Punkte pro Cluster anzuzeigen."></span>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strProcessing; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="processing" type="text" value="<?php echo $this->formvars['processing']; ?>" size="50" maxlength="255">&nbsp;
							<span data-tooltip="Wendet eine Prozessierungsanweisung für den Layer an.&#xa;Die unterstützten Anweisungen hängen vom Layertyp und dem verwendeten Treiber ab. Es gibt Anweisungen für Attribute, Connection Pooling, OGR Styles und Raster. siehe Beschreibung zum Layerattribut PROCESSING unter: http://www.mapserver.org/mapfile/layer.html. Mehrere Prozessinganweisungen werden hier eingegeben getrennt durch Semikolon. z.B. CHART_SIZE=60;CHART_TYPE=pie für die Darstellung eines Tortendiagramms des Typs MS_LAYER_CHART"></span>
						</td>
					</tr>
				</table>
				<br>
				<table border="0" cellspacing="0" cellpadding="3" style="width:100%; border:1px solid #bbb">
					<tr align="center">
						<th class="fetter layerform_header"  style="border-bottom:1px solid #C3C7C3" colspan="3"><a name="query_parameter" style="color: black"><?php echo $strQueryParameters; ?></a></th>
					</tr>
					<tr>
						<th class="fetter" align="right" style="width:300px; border-bottom:1px solid #C3C7C3"><?php echo $strIdentifierText; ?></th>
						<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="identifier_text" type="text" value="<?php echo $this->formvars['identifier_text']; ?>" size="50" maxlength="50">
						</td>
					</tr>					
					<tr>
						<th class="fetter" align="right" style="width:300px; border-bottom:1px solid #C3C7C3"><?php echo $strMaxQueryRows; ?></th>
						<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="max_query_rows" type="text" value="<?php echo $this->formvars['max_query_rows']; ?>" size="50" maxlength="5">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strDocument_path; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="document_path" type="text" value="<?php echo $this->formvars['document_path']; ?>" size="50" maxlength="100"><a href="index.php?go=show_missing_documents&selected_layer_id=<? echo $this->formvars['selected_layer_id']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i class="fa fa-chain-broken" aria-hidden="true" style="margin-top: 5px; margin-left: 5px" title="Zeige Datensätze für die Dateien auf dem Server fehlen."></i></a>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strDocument_url; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="document_url" type="text" value="<?php echo $this->formvars['document_url']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<? if($this->formvars['selected_layer_id']){ ?>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strDdlAttribute; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3"><?php
							include_once(CLASSPATH . 'LayerAttribute.php');
							$attributes = LayerAttribute::find($this, 'layer_id = ' . $this->formvars['selected_layer_id']);
							echo FormObject::createSelectField(
								'ddl_attribute',
								array_map(
									function($attribute) {
										return array(
											'value' => $attribute->get('name'),
											'output' => $attribute->get('name')
										);
									},
									$attributes
								),
								$this->formvars['ddl_attribute'],
								1,
								'',
								'',
								'ddl_attribute',
								'',
								'',
								'-- Auswahl --'
							); ?>
						</td>
					</tr>
					<? } ?>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strTolerance; ?>*</th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="tolerance" type="text" value="<?php echo $this->formvars['tolerance']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strToleranceUnits; ?>*</th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<select name="toleranceunits">
									<option <? if($this->formvars['toleranceunits'] == 'pixels'){echo 'selected';} ?> value="pixels">pixels</option>
									<option <? if($this->formvars['toleranceunits'] == 'meters'){echo 'selected';} ?> value="meters">meters</option>
								</select>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strQueryMap; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<select name="querymap">
									<option <? if($this->formvars['querymap'] == '0'){echo 'selected ';} ?>value="0"><?php echo $this->strNo; ?></option>
									<option <? if($this->formvars['querymap'] == 1){echo 'selected ';} ?>value="1"><?php echo $this->strYes; ?></option>
								</select>
						</td>
					</tr>
					<tr>
						<th class="fetter" width="300" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strLayerCharts; ?></th>
						<td width="370" colspan="2" style="border-bottom:1px solid #C3C7C3">
							<div style="float: left; width: 95%"><?
								if ($this->formvars['selected_layer_id']) {
									include_once(CLASSPATH . 'Layer.php');
									$this->layer = Layer::find_by_id($this, $this->formvars['selected_layer_id']);
									if ($this->layer) { ?>
										<ul><?
											foreach($this->layer->charts AS $chart) { ?>
												<li><a href="index.php?go=layer_chart_Editor&id=<? echo $chart->get_id(); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><? echo $chart->get('title'); ?></a></li><?
											} ?>
										</ul><?php
									}
								} ?>
							</div>
							<a href="index.php?go=layer_charts_Anzeigen&layer_id=<? echo $this->formvars['selected_layer_id']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>">
								<i class="fa fa-pencil" aria-hidden="true" style="margin-top: 5px; margin-left: 5px"></i>
							</a>
						</td>
					</tr>
				</table>
				<br>
				<table border="0" cellspacing="0" cellpadding="3" style="width:100%; border:1px solid #bbb">
					<tr align="center">
						<th class="fetter layerform_header"  style="border-bottom:1px solid #C3C7C3" colspan="3"><?php echo $strOWSParameter; ?></th>
					</tr>
					<tr>
						<th class="fetter" width="300" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strOwsSrs; ?>*</th>
						<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="ows_srs" type="text" value="<?php echo $this->formvars['ows_srs']; ?>" size="50" maxlength="255">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strWMSName; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="wms_name" type="text" value="<?php echo $this->formvars['wms_name']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strWMSKeywordlist; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="wms_keywordlist" type="text" value="<?php echo $this->formvars['wms_keywordlist']; ?>" size="50" maxlength="100">
						</td>
					</tr>				
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strWMSServerVersion; ?>*</th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<select name="wms_server_version">
									<option value=""><?php echo $this->strPleaseSelect; ?></option>
									<option <? if($this->formvars['wms_server_version'] == '1.0.0'){echo 'selected';} ?> value="1.0.0">1.0.0</option>
									<option <? if($this->formvars['wms_server_version'] == '1.1.0'){echo 'selected';} ?> value="1.1.0">1.1.0</option>
									<option <? if($this->formvars['wms_server_version'] == '1.1.1'){echo 'selected';} ?> value="1.1.1">1.1.1</option>
									<option <? if($this->formvars['wms_server_version'] == '1.3.0'){echo 'selected';} ?> value="1.3.0">1.3.0</option>
								</select>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strWMSFormat; ?>*</th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<select name="wms_format">
									<option value=""><?php echo $this->strPleaseSelect; ?></option>
									<option <? if($this->formvars['wms_format'] == 'image/png'){echo 'selected';} ?> value="image/png">image/png</option>
									<option <? if($this->formvars['wms_format'] == 'image/jpeg'){echo 'selected';} ?> value="image/jpeg">image/jpeg</option>
									<option <? if($this->formvars['wms_format'] == 'image/gif'){echo 'selected';} ?> value="image/gif">image/gif</option>
								</select>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strWMSConnectionTimeout; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="wms_connectiontimeout" type="text" value="<?php echo $this->formvars['wms_connectiontimeout']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strWMSAuthUsername; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="wms_auth_username" type="text" value="<?php echo $this->formvars['wms_auth_username']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strWMSAuthPassword; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="wms_auth_password" type="text" value="<?php echo $this->formvars['wms_auth_password']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strWFS_geom; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="wfs_geom" type="text" value="<?php echo $this->formvars['wfs_geom']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strWriteMapserverTemplates; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3"><?php
							echo FormObject::createSelectField(
								'write_mapserver_templates',
								array(array('value' => 'data', 'output' => $strWriteMapserverTemplatesOption1), array('value' => 'generic', 'output' => $strWriteMapserverTemplatesOption2)),
								$this->formvars['write_mapserver_templates'],
								1,
								'width: auto',
								'',
								'',
								'',
								'',
								$this->strPleaseSelect
							); ?>&nbsp;
							<span data-tooltip="<?php echo $strWriteMapserverTemplatesHelp; ?>"></span>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right"><?php echo $strOWSPublication; ?></th>
						<td colspan=2>
							<input type="checkbox" name="ows_publication" value="1"
								<?php echo ($this->formvars['ows_publication'] ? 'checked ' : ''); ?>onchange="document.getElementById('ows_publication_fields').style.display = this.checked ? 'table-row' : 'none';">
							&nbsp;<span data-tooltip="<?php echo $strOWSPublicationHint; ?>"></span>
						</td>
					</tr>
					<tr id="ows_publication_fields" style="display: <?php echo $this->formvars['ows_publication'] ? 'table-row' : 'none'; ?>">
						<td colspan=3 style="border-top:1px solid #C3C7C3">
							<div class="form-field">
								<div class="form-label fetter">
									<label><?php echo $this->strTask; ?></label>
								</div>
								<div class="form-value" style="width: 66%"><?php
									echo FormObject::createSelectField(
										'ows_stelle_id',
										array_map(
											function($key) {
												return array(
													'value' => $this->formvars['selstellen']['ID'][$key],
													'output' => $this->formvars['selstellen']['Bezeichnung'][$key]
												);
											},
											array_keys($this->formvars['selstellen']['ID'])
										),
										$this->formvars['ows_stelle_id'],
										1,
										'width: 50%',
										'',
										'ows_stelle_id', // id
										'',
										''
									); ?>&nbsp;<span data-tooltip="<? echo $strOWSStelleHint; ?>"></span>
								</div>
								<div style="clear: both; height: 5px;"></div>

								<div class="form-label fetter">
									<label><? echo $strMapFilePath; ?></label>
								</div>
								<div class="form-value" style="width: 66%">
									<? echo WMS_MAPFILE_PATH; ?>
									&nbsp;<span data-tooltip="<? echo $strMapFilePathHint; ?>" ></span>
									&nbsp;<? ($this->formvars['ows_mapfile_name'] != '' AND file_exists(WMS_MAPFILE_PATH . $this->formvars['ows_mapfile_name']) ? '<a href="show_map_file">erzeugte Map-Datei</a>' : ''); ?>
								</div>
								<div style="clear: both; height: 5px;"></div>

								<div class="form-label fetter">
									<label><? echo $strMapFileName; ?></label>
								</div>
								<div class="form-value" style="width: 66%">
									<input type="text" name="ows_mapfile_name" value="<?php echo $this->formvars['ows_mapfile_name']; ?>" size="50" placeholder="<? echo $this->formvars['name']; ?>" style="width: 50% !important">
									&nbsp;<span data-tooltip="<? echo $strMapFileNameHint; ?>" ></span>
									&nbsp;<? ($this->formvars['ows_mapfile_name'] != '' AND file_exists(WMS_MAPFILE_PATH . $this->formvars['ows_mapfile_name']) ? '<a href="show_map_file">erzeugte Map-Datei</a>' : ''); ?>
								</div>
								<div style="clear: both; height: 5px;"></div>

								<div class="form-label fetter">
									<label><? echo $strOWSWrapperPath; ?></label>
								</div>
								<div class="form-value" style="width: 66%">
									<? echo str_replace(URL, INSTALLPATH, OWS_SERVICE_ONLINERESOURCE); ?>
									&nbsp;<span data-tooltip="<? echo $strOWSWrapperPathHint; ?>" ></span>
									&nbsp;<? ($this->formvars['ows_wrapper_name'] != '' AND file_exists(WMS_MAPFILE_PATH . $this->formvars['ows_wrapper_name']) ? '<a href="show_wrapper_file">erzeugte Wrapper-Datei</a>' : ''); ?>
								</div>
								<div style="clear: both; height: 5px;"></div>

								<div class="form-label fetter">
									<label><? echo $strOWSWrapperName; ?></label>
								</div>
								<div class="form-value" style="width: 66%">
									<input type="text" name="ows_wrapper_name" value="<?php echo $this->formvars['ows_wrapper_name']; ?>" size="50"  style="width: 50% !important">
									&nbsp;<span data-tooltip="<? echo $strOWSWrapperNameHint; ?>"></span>
								</div>
							</div>
						</td>
					</tr>
				</table>
				<br>
				<table border="0" cellspacing="0" cellpadding="3" style="width:100%; border:1px solid #bbb">
					<tr align="center">
						<th class="fetter layerform_header"  style="border-bottom:1px solid #C3C7C3" colspan="3"><?php echo $strMetaParameters; ?></th>
					</tr>
					<tr>
						<th class="fetter" align="right" style="width: 300px; border-bottom:1px solid #C3C7C3"><?php echo $strDescribtion; ?></th>
						<td style="border-bottom:1px solid #C3C7C3">
							<textarea name="kurzbeschreibung" cols="33" rows="2"><? echo $this->formvars['kurzbeschreibung'] ?></textarea>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="width: 300px; border-bottom:1px solid #C3C7C3"><?php echo $strDataSource; ?></th>
						<td style="border-bottom:1px solid #C3C7C3">
							<div id="datasource_div">
								<!-- Multiselectformfeld mit ausgewählten Werten.//--><?
								include_once(CLASSPATH . 'DataSource.php');
								$datasources = DataSource::find($this, '', "coalesce('name', 'beschreibung')");
								echo FormObject::createSelectField(
									'datasource_ids',
									array_map(
										function($datasource) {
											return array(
												'value' => $datasource->get('id'),
												'output' => $datasource->get('name') ?: $datasource->get('beschreibung')
											);
										},
										$datasources
									),
									implode(',', $this->layerdata['datasource_ids'] ?: []),
									1,
									'display: none;
									width: 93%',
									'',
									'',
									true
								); ?>
								<div id="chosen-container">
									<!-- selectierte Werte //-->
									<ul id="chosen-choices"><?
										foreach ($this->layerdata['datasources'] AS $datasource) { ?>
											<li class="chosen-item"><span><? echo $datasource->get('name') ?? $datasource->get('beschreibung'); ?></span>
											<a datasource_id="<? echo $datasource->get('id'); ?>" class="chosen-item-close" data-option-array-index="5" onclick="unselectItem(this)"><i class="fa fa-times" style="color: gray; float: right; margin-right: -16px; margin-top: -1px;"></i></a></li><?
										} ?>
									</ul>
								</div>
								<div id="chosen-buttons">
									<a id="add-item-button" href="javascript:void(0);"><i class="fa fa-plus fa_lg" style="margin-left: 5px;"></i></a>
									<a href="index.php?go=datasources_anzeigen&selected_layer_id=<? echo $this->formvars['selected_layer_id']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i class="fa fa-pencil fa_lg" style="margin-left: 5px;"></i></a>
								</div>
							</div>
							<div style="clear: both"></div>
							
							<div id="chosen-drop">
								<!-- auswählbare Werte //-->
								<ul class="chosen-results"><?
									$selectable_datasources = array_filter(
										$datasources,
										function ($datasource) {
											return !in_array($datasource->get('id'), $this->layerdata['datasource_ids'] ?: []);
										}
									);
									foreach ($selectable_datasources AS $datasource) { ?>
										<li datasource_id="<? echo $datasource->get('id'); ?>" class="selectable-item" data-option-array-index="0"><? echo $datasource->get('name') ?? $datasource->get('beschreibung'); ?>(<? echo $datasource->get('id'); ?>)</li><?
									} ?>
								</ul>
							</div>
						</td>
					</tr>

					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strDataOwnerName; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="dataowner_name" type="text" value="<?php echo $this->formvars['dataowner_name']; ?>" size="50" maxlength="100">
						</td>
					</tr>

					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $this->strEmail; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="dataowner_email" type="text" value="<?php echo $this->formvars['dataowner_email']; ?>" size="50" maxlength="100">
						</td>
					</tr>

					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $this->strTelephone; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="dataowner_tel" type="text" value="<?php echo $this->formvars['dataowner_tel']; ?>" size="50" maxlength="100">
						</td>
					</tr>

					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strUpToDateness; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="uptodateness" type="text" value="<?php echo $this->formvars['uptodateness']; ?>" size="50" maxlength="100">
						</td>
					</tr>

					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strUpdateCycle; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="updatecycle" type="text" value="<?php echo $this->formvars['updatecycle']; ?>" size="50" maxlength="100">
						</td>
					</tr>

					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strMetaLink; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="metalink" type="text" value="<?php echo $this->formvars['metalink']; ?>" size="50" maxlength="255">
						</td>
					</tr>

					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strTermsOfUseLink; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="terms_of_use_link" type="text" value="<?php echo $this->formvars['terms_of_use_link']; ?>" size="50" maxlength="255">
						</td>
					</tr>					

					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $this->strVersion; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="version" type="text" value="<?php echo $this->formvars['version']; ?>" size="10" maxlength="10">
						</td>
					</tr>

					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $this->strComment; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<textarea name="comment" colls="33" rows="2"><? echo $this->formvars['comment']; ?></textarea>
						</td>
					</tr>

				</table><?
				if ($this->plugin_loaded('mobile')) { ?>
					<br>
					<table border="0" cellspacing="0" cellpadding="3" style="width:100%; border:1px solid #bbb">
						<tr align="center">
							<th class="fetter layerform_header"  style="border-bottom:1px solid #C3C7C3" colspan="3">Plugin <?php echo $str_mobile_plugin_name; ?></th>
						</tr>
						<tr>
							<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $str_mobile_vector_tile_url; ?></th>
							<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="vector_tile_url" type="text" value="<?php echo $this->formvars['vector_tile_url']; ?>" size="50" maxlength="255">&nbsp;
								<span data-tooltip="<? echo $str_mobile_vector_tile_url_help; ?>"></span>
							</td>
						</tr>
					</table><?
				}
				if ($this->plugin_loaded('portal')) { ?>
					<br>
					<table border="0" cellspacing="0" cellpadding="3" style="width:100%; border:1px solid #bbb">
						<tr align="center">
							<th class="fetter layerform_header"  style="border-bottom:1px solid #C3C7C3" colspan="3">Plugin <?php echo $str_portal_plugin_name; ?></th>
						</tr>
						<tr>
							<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><a name="cluster_option"></a><?php echo $str_portal_cluster_option; ?></th>
							<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="cluster_option" type="checkbox" value="1"<?php if ($this->formvars['cluster_option']) echo ' checked'; ?>>&nbsp;
								<span data-tooltip="<?php echo $str_portal_cluster_option_help; ?>"></span>
							</td>
						</tr>
					</table><?
				} ?>
				<br>
				<table border="0" cellspacing="0" cellpadding="3" style="width:100%; border:1px solid #bbb">
					<tr align="center">
						<th class="fetter layerform_header"  style="border-bottom:1px solid #C3C7C3" colspan="3"><?php echo $strAdministrative; ?></th>
					</tr>
					<tr>
						<th class="fetter" align="right" style="width: 300px; border-bottom:1px solid #C3C7C3"><?php echo $strStatus; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="status" type="text" value="<?php echo $this->formvars['status']; ?>" size="50" maxlength="255">&nbsp;
							<span data-tooltip="<? echo $strStatusHelp; ?>"></span>
						</td>
					</tr>
					<tr>
						<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strTriggerFunction; ?></th>
						<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="trigger_function" type="text" value="<?php echo $this->formvars['trigger_function']; ?>" size="50" maxlength="100">&nbsp;
							<span data-tooltip="<? echo $strTriggerFunctionHelp; ?>">
						</td>
					</tr>
					<tr>
						<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><a name="sync"></a><?php echo $strSync; ?></th>
						<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="sync" type="checkbox" value="1"<?php if ($this->formvars['sync']) echo ' checked'; ?>>&nbsp;
							<span data-tooltip="<?php echo $strSyncHelp; ?>"></span>
						</td>
					</tr>
					<tr>
						<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strListed; ?></th>
						<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="listed" type="checkbox" value="1"<?php if ($this->formvars['listed']) echo ' checked'; ?>>
						</td>
					</tr>
					<tr>
						<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strLogged; ?></th>
						<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="logconsume" type="checkbox" value="1"<?php if ($this->formvars['logconsume']) echo ' checked'; ?>>
						</td>
					</tr><?
					if ($this->formvars['shared_from'] OR $this->is_admin_user($this->user->id)) { ?>
						<tr>
							<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strSharedFrom; ?></th>
							<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3"><?
								if ($this->formvars['shared_from']) {
									$shared_user = $this->user->getUserDaten($this->formvars['shared_from'], '', '')[0];
									$shared_name = $shared_user['Vorname'] . ' ' . $shared_user['name'] . (!empty($shared_user['organisation']) ? ' (' . $shared_user['organisation'] . ')' : '');
								}
								if ($this->is_admin_user($this->user->id)) { ?>
									<input name="shared_from" type="text" value="<?php echo $this->formvars['shared_from']; ?>" style="width: <?php echo (strlen($this->formvars['shared_from']) * 15) + 15 ?>px"> <?
									echo $shared_name; ?>
									<span data-tooltip="<?php echo 	$strSharedFromHelp; ?>"></span><?
								}
								else { ?>
									<input name="shared_from" type="hidden" value="<?php echo $this->formvars['shared_from']; ?>"><?
									echo $shared_name;
								} ?>
							</td>
						</tr><?
					} ?>
				</table>
			</div>
		
		<div id="stellenzuweisung" style="background-color: #f8f8f9;">
			<table border="0" cellspacing="0" cellpadding="3" style="width: 100%; border:1px solid #bbb">
				<tr align="center">
					<th class="fetter layerform_header" style="border-bottom:1px solid #C3C7C3" colspan="3"><?php echo $strDefaultValues; ?></th>
				</tr>
				<tr>
					<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strTemplate; ?></th>
					<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="template" type="text" value="<?php echo $this->formvars['template']; ?>" size="50" maxlength="100">
					</td>
				</tr>
				<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strQueryable; ?></th>
					<td colspan=2 style="border-bottom:1px solid #C3C7C3">
							<select name="queryable">
								<option <? if($this->formvars['queryable'] == '0'){echo 'selected ';} ?>value="0"><?php echo $this->strNo; ?></option>
								<option <? if($this->formvars['queryable'] == 1){echo 'selected ';} ?>value="1"><?php echo $this->strYes; ?></option>
							</select>
					</td>
				</tr>
				<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strGeomUsable; ?></th>
					<td colspan=2 style="border-bottom:1px solid #C3C7C3">
							<select name="use_geom">
								<option <? if($this->formvars['use_geom'] == '0'){echo 'selected ';} ?>value="0"><?php echo $this->strNo; ?></option>
								<option <? if($this->formvars['use_geom'] == 1){echo 'selected ';} ?>value="1"><?php echo $this->strYes; ?></option>
							</select>
					</td>
				</tr>
				<tr>
					<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strtransparency; ?></th>
					<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="transparency" type="number" min="0" max="100" onkeyup="enforceMinMax(this)" value="<?php echo $this->formvars['transparency']; ?>" style="width: 95%">
					</td>
				</tr>
				<tr>
					<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strLegendOrder; ?></th>
					<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="legendorder" type="text" value="<?php echo $this->formvars['legendorder']; ?>" size="50" maxlength="15">
					</td>
				</tr>				
				<tr>
					<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strminscale; ?></th>
					<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="minscale" type="text" value="<?php echo $this->formvars['minscale']; ?>" size="50" maxlength="15">
					</td>
				</tr>
				<tr>
					<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strmaxscale; ?></th>
					<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="maxscale" type="text" value="<?php echo $this->formvars['maxscale']; ?>" size="50" maxlength="15">
					</td>
				</tr>
				<tr>
					<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strsymbolscale; ?></th>
					<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="symbolscale" type="text" value="<?php echo $this->formvars['symbolscale']; ?>" size="50" maxlength="15">
					</td>
				</tr>
				<tr>
					<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $stroffsite; ?></th>
					<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="offsite" type="text" value="<?php echo $this->formvars['offsite']; ?>" size="50" maxlength="11">
					</td>
				</tr>
				<tr>
					<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strPostlabelcache; ?></th>
					<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<select name="postlabelcache">
								<option <? if($this->formvars['postlabelcache'] == '0'){echo 'selected ';} ?>value="0"><?php echo $this->strNo; ?></option>
								<option <? if($this->formvars['postlabelcache'] == 1){echo 'selected ';} ?>value="1"><?php echo $this->strYes; ?></option>
							</select>
					</td>
				</tr>

				<tr>
					<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strrequires; ?></th>
					<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3"><?
						$group_layer_options = array();
						foreach (($this->grouplayers['ID'] ?: []) AS $index => $grouplayer_id) {
							$group_layer_options[] = array(
								'value' => $grouplayer_id,
								'output' => $this->grouplayers['Bezeichnung'][$index]
							);
						}
						echo FormObject::createSelectField(
							'requires',
							$group_layer_options,
							$this->formvars['requires'],
							1,
							'',
							'',
							'',
							'',
							'',
							$this->strPleaseSelect
						); ?>
					</td>
				</tr><?

				if (is_array($this->formvars['selstellen']) AND array_key_exists('Bezeichnung', $this->formvars['selstellen']) AND count($this->formvars['selstellen']["Bezeichnung"]) > 0) { ?>
				<tr>
					<td align="center" colspan=3 style="height: 30px;border-bottom:1px solid #C3C7C3">
						<a href="javascript:document.GUI.assign_default_values.value=1;submitWithValue('GUI','go_plus','Speichern')"><? echo $strAssignDefaultValues; ?></a>
					</td>
				</tr>
				<? } ?>
			</table>
			<br>
			<table border="0" cellspacing="0" cellpadding="3" style="width: 100%; border:1px solid #bbb">
				<tr align="center">
					<th class="fetter layerform_header" width="670" style="border-bottom:1px solid #C3C7C3" colspan="3"><?php echo $strTasks; ?></th>
				</tr>
				<tr valign="top"> 
					<td align="right">Zugeordnete<br>
						<select name="selectedstellen" size="10" multiple style="position: relative; width: 340px"><? 
						for ($i = 0; $i < count_or_0($this->formvars['selstellen']["Bezeichnung"] ?: []); $i++) {
								echo '<option class="select_option_link" onclick="gotoStelle(event, this)" value="'.$this->formvars['selstellen']["ID"][$i].'" title="'.$this->formvars['selstellen']["Bezeichnung"][$i].'" onclick="handleClick(event, this)">'.$this->formvars['selstellen']["Bezeichnung"][$i].'</option>';
							 }
						?>
						</select>
					</td>
					<td align="center" valign="middle" width="1"> 
						<input type="button" name="addPlaces" value="&laquo;" onClick=addOptions(document.GUI.allstellen,document.GUI.selectedstellen,document.GUI.selstellen,'value')>
						<input type="button" name="substractPlaces" value="&raquo;" onClick=substractOptions(document.GUI.selectedstellen,document.GUI.selstellen,'value')>
					</td>
					<td>verfügbare<br><?
						$available_stellen_options = array_map(
							function($stelle) {
								return array(
									'value' => $stelle['ID'],
									'title' => $stelle['Bezeichnung'],
									'output' => $stelle['Bezeichnung'],
									'show_shared_layers' => $stelle['show_shared_layers']
								);
							},
							vectors_to_assoc_array($this->stellen)
						);
						if (!$this->is_admin_user($this->user->id) AND $this->formvars['shared_from']) {
							$available_stellen_options = array_filter(
								$available_stellen_options,
								function ($option) {
									return $option['show_shared_layers'] == '1';
								}
							);
						}
						echo FormObject::createSelectField(
							'allstellen',														# name
							$available_stellen_options,							# options
							'',																			# value
							10,																			# size
							'position: relative; width: 340px',			# style
							'',																			# onchange
							'',																			# id
							'true',																	# multiple
							'',																			# class
							'',																			# first option
							'',																			# option_style
							'select_option_link',										# option_class
							'gotoStelle(event, this)'								# onclick
						); ?>
						<!--select name="allstellen" size="10" multiple style="position: relative; width: 340px">
						<? for($i=0; $i < count($this->stellen["Bezeichnung"]); $i++){
								echo '<option
									class="select_option_link"
									onclick="gotoStelle(event, this)"
									value="'.$this->stellen["ID"][$i].'"
									title="'.$this->stellen["Bezeichnung"][$i].'"
								>'.$this->stellen["Bezeichnung"][$i].'</option>';
							 }
						?>
						</select //-->
					</td>
				</tr>
			</table>
		</div>
		
		
		<? if($this->formvars['selected_layer_id']){ # Klassen werden nicht angezeigt aber fürs Kopieren eines Layers im Formular benötigt ?>
		<table border="0" cellspacing="0" cellpadding="3" style="display: none; border:1px solid #bbb">
			<tr>
				<th class="fetter" bgcolor="<?php echo BG_DEFAULT ?>" style="border-bottom:1px solid #C3C7C3" colspan="10"><a name="Klassen"></a><?php echo $strClasses; ?></th>
			</tr>
			<tr>
				<td style="border-bottom:1px solid #C3C7C3">&nbsp;<?php echo $strID; ?></td>
				<td style="border-bottom:1px solid #C3C7C3">&nbsp;<?php echo $strClass; ?></td><?
				foreach($supportedLanguages as $language){
					if ($language != 'german') { ?>
						<td style="border-bottom:1px solid #C3C7C3">&nbsp;<?php echo $strClass.' '.$language; ?></td><?
					}
				} ?>
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><?php echo $strExpression; ?></td>
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><?php echo $strText; ?></td>
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><?php echo $strClassification; ?>
				</td>
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><?php echo $strLegendGraphic; ?>
				</td>
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><?php echo $strOrder; ?>
				</td>
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><?php echo $strDelete; ?></td>
	<!--			<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">ändern</td>	-->
			</tr>
			<?

			$last_classification = (($this->classes AND is_array($this->classes) AND array_key_exists(0, $this->classes)) ? $this->classes[0]['classification'] : '');
			for ($i = 0; $i < count((is_null($this->classes) ? array() : $this->classes)); $i++){
				if($this->classes[$i]['classification'] != $last_classification){
					$last_classification = $this->classes[$i]['classification'];
					if($tr_color == 'gainsboro')$tr_color = '';
					else $tr_color = 'gainsboro';
				}
				echo '
			<tr style="background-color:'.$tr_color.'">
				<input type="hidden" name="ID['.$this->classes[$i]['class_id'].']" value="'.$this->classes[$i]['class_id'].'">
				<td style="border-bottom:1px solid #C3C7C3">'.$this->classes[$i]['class_id'].'</td>'; ?>
				<td style="border-bottom:1px solid #C3C7C3">
					<input size="12" type="text" name="name[<?php echo $this->classes[$i]['class_id']; ?>]" value="<?php echo $this->classes[$i]['name']; ?>">
				</td><?php
				foreach ($supportedLanguages as $language) {
					if ($language != 'german') { ?>
						<td style="border-bottom:1px solid #C3C7C3">
							<input size="12" type="text" name="name_<?php echo $language; ?>[<?php echo $this->classes[$i]['class_id']; ?>]" value="<?php echo $this->classes[$i]['Name_' . $language]; ?>">
						</td><?php
					}
				} ?>
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">
					<textarea name="expression[<?php echo $this->classes[$i]['class_id']; ?>]" cols="28" rows="3"><?php echo $this->classes[$i]['expression']; ?></textarea>
				</td>
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">
					<textarea name="text[<?php echo $this->classes[$i]['text']; ?>]" cols="18" rows="3"><?php echo $this->classes[$i]['text']; ?></textarea>
				</td>
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">
					<input type="text" name="classification[<?php echo $this->classes[$i]['class_id']; ?>]" size="18" value="<?php echo $this->classes[$i]['classification']; ?>">
				</td>
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">
					<table cellpadding="0" cellspacing="2">
						<tr>
							<td colspan="4">
								<? echo $strImagefile; ?>:
								<input type="text" name="legendgraphic[<?php echo $this->classes[$i]['class_id']; ?>]" size="19" value="<?php echo $this->classes[$i]['legendgraphic']; ?>">
							</td>
						</tr>
						<tr>
							<td>
								<? echo $strWidth; ?>:&nbsp;
							</td>
							<td>
								<input size="1" type="text" name="legendimagewidth[<?php echo $this->classes[$i]['class_id']; ?>]" value="<?php echo $this->classes[$i]['legendimagewidth']; ?>">
							</td>
							<td>
								<? echo $strHeight; ?>:&nbsp;
							</td>
							<td>
								<input size="1" type="text" name="legendimageheight[<?php echo $this->classes[$i]['class_id']; ?>]" value="<?php echo $this->classes[$i]['legendimageheight']; ?>">
							</td>
						</tr>
					</table>
				</td>
				<td align="left" style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">
					<table cellpadding="0" cellspacing="2">
						<tr>
							<td>
								<?php echo $strMap; ?>:&nbsp;
							</td>
							<td>
								<input size="3" type="text" name="order[<?php echo $this->classes[$i]['class_id']; ?>]" value="<?php echo $this->classes[$i]['drawingorder']; ?>">
							</td>
						</tr>
							<td>
								<?php echo $strLegend; ?>:&nbsp;
							</td>
							<td>
								<input size="3" type="text" name="classlegendorder[<?php echo $this->classes[$i]['class_id']; ?>]" value="<?php echo $this->classes[$i]['legendorder']; ?>">
							</td>
						</tr>
					</table>
				</td>				
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">
					<? if($this->formvars['editable']) { ?>
					<a href="javascript:Bestaetigung('index.php?go=Layereditor_Klasse_Löschen&class_id=<?php echo $this->classes[$i]['class_id']; ?>&selected_layer_id=<?php echo $this->formvars['selected_layer_id']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>#Klassen',	'<?php echo $this->strDeleteWarningMessage; ?>');"><?php echo $this->strDelete; ?></a>
					<? } ?>
				</td>
			</tr><?php
			} ?>
		</table>
		<?}?>		
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr> 
		<td align="center">
			<input type="hidden" name="go_plus" id="go_plus" value=""><?
			if (
				$this->formvars['selected_layer_id'] > 0 AND
				$this->formvars['editable']
			) { ?>
				<input
					id="layer_formular_submit_button"
					type="button"
					name="dummy"
					value="<?php echo $strButtonSave; ?>"
					onclick="submitWithValue('GUI','go_plus','Speichern')"
				><?
			} ?>
			<input
				type="button"
				id="saveAsNewLayerButton"
				name="dummy"
				value="<?php echo $strButtonSaveAsNewLayer; ?>"
				onclick="mandatoryValuesMissing() || submitWithValue('GUI','go_plus','Als neuen Layer eintragen')"
			><?
			if (
				$this->formvars['selected_layer_id'] > 0 AND
				$this->formvars['editable']
			) { ?>
				<input
					id="layer_formular_delete_button"
					type="button"
					class="delete-button"
					name="layer_formular_delete_button"
					value="<?php echo $this->strDelete; ?>"
					onclick="Bestaetigung('index.php?go=Layer_Löschen&selected_layer_id=<? echo $this->formvars['selected_layer_id']; ?>&order=Name&csrf_token=<? echo $_SESSION['csrf_token']; ?>', '<? echo $this->strDeleteWarningMessage; ?>');"
				><?
			} ?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>

<a style="float: right;margin-top: -30px; margin-right: 10px;" href="javascript:window.scrollTo(0, 0);"	title="nach oben">
	<i class="fa fa-arrow-up hover-border" aria-hidden="true"></i>
</a>

<input type="hidden" name="gruppenaenderung" value="">
<input type="hidden" name="stellenzuweisung" value="<? echo $this->formvars['stellenzuweisung']; ?>">
<input type="hidden" name="go" value="Layereditor">
<input type="hidden" name="assign_default_values" value="0">
<input type="hidden" name="selstellen" value="<? 
	echo $this->formvars['selstellen']["ID"][0];
	for($i=1; $i < count_or_0($this->formvars['selstellen']["Bezeichnung"] ?: []); $i++){
		echo ', '.$this->formvars['selstellen']["ID"][$i];
	}
?>">


<script type="text/javascript">
	<? if($this->formvars['stellenzuweisung'] == 1){ ?>toggleForm('stellenzuweisung');<? } else { ?>toggleForm('layerform');<? } ?>

	$('.selectable-item').on('mouseover', (evt) => {
		//console.log('mouseover on selectable-item', evt.target);
		$(evt.target).toggleClass('highlighted-item').siblings().removeClass('highlighted-item');
	});

	$('.selectable-item').on('click', (evt) => {
		const datasource_id = $(evt.target).attr('datasource_id');
		//console.log('click on selectable item %o with datasource_id %s', evt.target, datasource_id);
		// add clicked item to chosen-choices and select in select field
		$('#chosen-choices').append(`<li class="chosen-item"><span>${evt.target.innerHTML}</span><a datasource_id="${datasource_id}" class="chosen-item-close" data-option-array-index="5" onclick="unselectItem(this)"><i class="fa fa-times" style="color: gray; float: right; margin-right: -16px; margin-top: -1px;"></i></a></li>`);
		$(`#datasource_ids option[value=${datasource_id}]`).prop('selected', true);
		$(`.selectable-item[datasource_id="${datasource_id}"]`).toggleClass('selectable-item selected-item').off();
		$('#chosen-drop').hide();
		$('#add-item-button').show();
	});

	$('#add-item-button').on('click', (evt) => {
		//console.log('click on chosen-container');
		$('#add-item-button').hide();
		$('#chosen-drop').show();
	});

</script>