<?
	/**
	 * View zur Darstellung von Diagrammen des Layers $layer
	 * Dargestellt werden die LayerCharts-Objekte aus dem Array $layer['charts']
	 */
	include_once(CLASSPATH . 'FormObject.php');
?>
<script type="text/javascript" src="<? echo THIRDPARTY_PATH; ?>Chart/chart.js"></script>
<script>
	const COLORS = [
		'#4dc9f6',
		'#f67019',
		'#f53794',
		'#537bc4',
		'#acc236',
		'#166a8f',
		'#00a950',
		'#58595b',
		'#8549ba'
	];

	function change_chart_type(chart, chart_type) {
		chart.config.type = chart_type;
		chart.config.data.datasets[0].backgroundColor = (chart_type == 'bar' ? 'lightblue' : COLORS);
		chart.update();
	}

	function change_chart_title(chart, chart_title) {
		chart.config.options.plugins.title.text = chart_title;
		chart.update();
	}

	function create_chart() {
		let id = '';
		Check = confirm('Diagramm wirklich anlegen?');
		if (Check == true) {
			alert('Diagramm wird angelegt!');
			$.ajax({
				url: 'index.php',
				data: {
					go: 'chart_speichern',
					layer_id: <? echo $layer['Layer_ID']; ?>,
					title: $('.diagram_form_elem_' + id + '[name="diagram_title"]').val(),
					type: $('.diagram_form_elem_' + id + '[name="diagram_type"]').val(),
					value_attribute_label: $('.diagram_form_elem_' + id + '[name="diagram_value_attribute_label"]').val(),
					aggregate_function: $('.diagram_form_elem_' + id + '[name="diagram_aggregate_function"]').val(),
					value_attribute_name: $('.diagram_form_elem_' + id + '[name="diagram_value_attribute_name"]').val(),
					label_attribute_name: $('.diagram_form_elem_' + id + '[name="diagram_label_attribute_name"]').val(),
					csrf_token: '<? echo $_SESSION['csrf_token']; ?>'
				},
				error: function(response) {
					message([{'type' : 'error', 'msg': response.msg}])
				},
				success: function(response) {
					if (response.success) {
						message([{'type' : 'notice', 'msg': response.msg}]);
						alert('Erzeuge Chart im Browser');
						$('#diagram_edit_div_' + id + ', #diagram_save_button_' + id + ', #diagram_edit_button_' + id + ', #diagram_delete_button_' + id).toggle(1000);
					}
					else {
						message([{'type' : 'error', 'msg': response.msg}]);
					}
				}
			});
		};
	}

	function save_chart(chart, id) {
		Check = confirm('Diagramm wirklich speichern?');
		console.log('dvan: ', $('.diagram_form_elem_' + id + '[name="diagram_value_attribute_name"]'));
		if (Check == true) {
			$.ajax({
				url: 'index.php',
				data: {
					go: 'chart_speichern',
					id: id,
					title: $('.diagram_form_elem_' + id + '[name="diagram_title"]').val(),
					type: $('.diagram_form_elem_' + id + '[name="diagram_type"]').val(),
					value_attribute_label: $('.diagram_form_elem_' + id + '[name="diagram_value_attribute_label"]').val(),
					aggregate_function: $('.diagram_form_elem_' + id + '[name="diagram_aggregate_function"]').val(),
					value_attribute_name: $('.diagram_form_elem_' + id + '[name="diagram_value_attribute_name"]').val(),
					label_attribute_name: $('.diagram_form_elem_' + id + '[name="diagram_label_attribute_name"]').val(),
					csrf_token: '<? echo $_SESSION['csrf_token']; ?>'
				},
				error: function(response) {
					message([{'type' : 'error', 'msg': response.msg}])
				},
				success: function(response) {
					if (response.success) {
						$('#chart_type_selector_' + id).val($('.diagram_form_elem_' + id + '[name="diagram_type"]').val());
						$('#chart_type_selector_' + id)[0].dispatchEvent(new Event('change'));
						change_chart_title(chart, $('.diagram_form_elem_' + id + '[name="diagram_title"]').val());
						message([{'type' : 'notice', 'msg': response.msg}]);
						$('#diagram_edit_div_' + id + ', #diagram_save_button_' + id + ', #diagram_edit_button_' + id + ', #diagram_delete_button_' + id).toggle(1000);
					}
					else {
						message([{'type' : 'error', 'msg': response.msg}]);
					}
				}
			});
		}
	}
	function delete_chart(id) {
		Check = confirm('Diagramm wirklich löschen?');
		if (Check == true) {
			alert('Diagramm wird gelöscht');
			/*
			$.ajax({
				url: 'index.php',
				data: {
					go: 'chart_loeschen',
					id: id,
					csrf_token: '<? echo $_SESSION['csrf_token']; ?>'
				},
				error: function(response) {
					message(response.msg);
				},
				success: function(response) {
					$('#chart_div_' + id).remove();
				}
			});
			*/
		}
	}
</script>
<style>
	.chart_div {
		width: 700px;
		margin: auto;
		/*height: 600px;*/
	}
	.diagram-edit-label-div {
		float: left;
		margin-right: 10px;
		text-align: right;
		width: 300px;
		margin-top: 3px;
		font-weight: bold;
	}
	.diagram-edit-input-div {
		float: left;
		width: 50%;
		margin-top: 3px;
	}
</style>
<table style="width: 100%">
	<tr>
		<td style="
			background-color: #cdd8dc;
			padding: 5px;
			border: 1px solid #bbb;
			font-weight: bold;
		">
			<a href="javascript:void(0);" onclick="$('#diagramms, .diagram_group_img').toggle()">
				<img class="diagram_group_img" src="graphics//plus.gif" border="0">
				<img class="diagram_group_img" src="graphics//minus.gif" border="0" style="display: none">
			</a> Diagramme
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div id="diagramms" style="display: none">
				<script>
					let i;
					let value_elements;
					let name_elements;
				</script><?
				foreach ($layer['charts'] AS $chart) {
					$id = $chart->get($chart->identifier);
					if ($this->user->id == 1) {
						#echo '<p>chart: ' . print_r($chart->data, true);
					}
					?>
					<div id="chart_div_<? echo $id; ?>" class="chart_div">
						<canvas id="chart_<? echo $id; ?>"></canvas>
						<div>
							Diagrammtyp: 
							<select id="chart_type_selector_<? echo $id; ?>" onchange="change_chart_type(chart_<? echo $id; ?>, this.value)">
								<option value="bar"<? echo ($chart->get('type') == 'bar' ? ' selected' : ''); ?>>Balken</option>
								<option value="pie"<? echo ($chart->get('type') == 'pie' ? ' selected' : ''); ?>>Torten</option>
								<option value="doughnut"<? echo ($chart->get('type') == 'doughnut' ? ' selected' : ''); ?>>Doughnut</option>
							</select><?
							if ($this->user->funktion == 'admin') { ?>
								<button id="diagram_edit_button_<? echo $id; ?>" type="button" style="margin-left: 10px" onclick="$('#diagram_edit_div_<? echo $id; ?>, #diagram_save_button_<? echo $id; ?>, #diagram_edit_button_<? echo $id; ?>, #diagram_delete_button_<? echo $id; ?>').toggle()">Bearbeiten</button>
								<button id="diagram_delete_button_<? echo $id; ?>" type="button" style="margin-left: 10px" onclick="delete_chart(<? echo $id; ?>)">Löschen</button><?php
							} ?>
						</div><?
						if ($this->user->funktion == 'admin') { ?>
							<div id="diagram_edit_div_<? echo $id; ?>" style="display: none">
								<input type="hidden" name="diagram_id_<? echo $id; ?>" value="<? echo $id; ?>">
								<div class="diagram-edit-label-div">Diagrammtitel:</div>
								<div class="diagram-edit-input-div"><input type="text" class="diagram_form_elem_<? echo $id; ?>" name="diagram_title" value="<? echo $chart->get('title'); ?>" style="width: 100%"></div>
								<div style="clear: both"></div>
								<div class="diagram-edit-label-div">Diagrammtyp:</div>
								<div class="diagram-edit-input-div">
									<select class="diagram_form_elem_<? echo $id; ?>" name="diagram_type">
										<option value="bar"<? echo ($chart->get('type') == 'bar' ? ' selected' : ''); ?>>Balken</option>
										<option value="pie"<? echo ($chart->get('type') == 'pie' ? ' selected' : ''); ?>>Torten</option>
										<option value="doughnut"<? echo ($chart->get('type') == 'doughnut' ? ' selected' : ''); ?>>Doughnut</option>
									</select>
								</div>
								<div style="clear: both"></div>
								<div class="diagram-edit-label-div">Beschriftung Balken:</div>
								<div class="diagram-edit-input-div"><input class="diagram_form_elem_<? echo $id; ?>" type="text" name="diagram_value_attribute_label" value="<? echo $chart->get('value_attribute_label'); ?>"></div>
								<div style="clear: both"></div>
								<div class="diagram-edit-label-div">Aggregationsfunktion:</div>
								<div class="diagram-edit-input-div">
									<select class="diagram_form_elem_<? echo $id; ?>" name="diagram_aggregate_function">
										<option value="sum"<? echo ($chart->get('aggregate_function') == 'sum' ? ' selected' : ''); ?>>Summe</option>
										<!--option value="average"<? echo ($chart->get('aggregate_function') == 'average' ? ' selected' : ''); ?>>Durchschnitt</option>
										<option value="min"<? echo ($chart->get('aggregate_function') == 'min' ? ' selected' : ''); ?>>Minimalwert</option>
										<option value="max"<? echo ($chart->get('aggregate_function') == 'max' ? ' selected' : ''); ?>>Maximalwert</option//-->
									</select>
								</div>
								<div style="clear: both"></div>
								<div class="diagram-edit-label-div">Werteattribut (Hochwert):</div>
								<div class="diagram-edit-input-div"><?
									echo FormObject::createSelectField(
										'diagram_value_attribute_name',
										array_map(
											function($name, $alias) {
												return array(
													'value' => $name,
													'output' => $alias
												);
											},
											$layer['attributes']['name'],
											$layer['attributes']['alias']
										),
										$chart->get('value_attribute_name'),
										1, '', '', '', '',
										'diagram_form_elem_' . $id
									); ?>
								</div>
								<div style="clear: both"></div>
								<div class="diagram-edit-label-div">Beschriftungsattribut (Rechtswert):</div>
								<div class="diagram-edit-input-div"><?
									echo FormObject::createSelectField(
										'diagram_label_attribute_name',
										array_map(
											function($name, $alias) {
												return array(
													'value' => $name,
													'output' => $alias
												);
											},
											$layer['attributes']['name'],
											$layer['attributes']['alias']
										),
										$chart->get('label_attribute_name'),
										1, '', '', '', '',
										'diagram_form_elem_' . $id
									); ?>
								<div style="clear: both"></div>
							</div>
							<div style="clear: both"></div>
							<button id="diagram_save_button_<? echo $id; ?>" type="button" style="margin-top: 9px; margin-left: 309px; display: none" onclick="save_chart(chart_<? echo $id; ?>, <? echo $id; ?>);">Speichern</button><?
						} ?>
					</div>
					<script>
						let chart_type_<? echo $id; ?> = '<? echo $chart->get('type'); ?>';
						value_elements = $('.attr_<? echo $layer['Layer_ID']; ?>_<? echo $chart->get('value_attribute_name'); ?>');
						let values_<? echo $id; ?>;
						if (value_elements[0].type == 'select-one') {
							values_<? echo $id; ?> = value_elements.map((k, v) => { return v.options[v.selectedIndex].text; });
						}
						else {
							values_<? echo $id; ?> = value_elements.map((k, v) => { return v.value; });
						}
						name_elements = $('.attr_<? echo $layer['Layer_ID']; ?>_<? echo $chart->get('label_attribute_name'); ?>');

						let names_<? echo $id; ?>;
						if (name_elements[0].type == 'select-one') {
							names_<? echo $id; ?> = name_elements.map((k, v) => { return v.options[v.selectedIndex].text; });
						}
						else {
							names_<? echo $id; ?> = name_elements.map((k, v) => { return v.value; });
						}
						let labels_<? echo $id; ?> = [];
						let data_<? echo $id; ?> = [];
						for (i = 0; i < names_<? echo $id; ?>.length; i++) {
							if (labels_<? echo $id; ?>.indexOf(names_<? echo $id; ?>[i]) == -1) {
								labels_<? echo $id; ?>.push(names_<? echo $id; ?>[i]);
								data_<? echo $id; ?>.push(parseFloat(values_<? echo $id; ?>[i]));
							}
							else {
								data_<? echo $id; ?>[labels_<? echo $id; ?>.indexOf(names_<? echo $id; ?>[i])] += parseFloat(values_<? echo $id; ?>[i]);
							}
						}
						// Chart-Objekt erzeugen
						let chart_<? echo $id; ?> = new Chart(
							document.getElementById('chart_<? echo $id; ?>'), {
							type: chart_type_<? echo $id; ?>,
							data: {
								labels: labels_<? echo $id; ?>,
								datasets: [{
									label: '<? echo $chart->get('value_attribute_label'); ?>',
									data: data_<? echo $id; ?>,
									backgroundColor: (chart_type_<? echo $id; ?> == 'bar' ? 'lightblue' : COLORS),
									//backgroundColor: COLORS,
									borderWidth: 1
								}]
							},
							options: {
								plugins : {
									title: {
										display: true,
										font: {
											weight: 'bold',
											size: '20px'
										},
										text: '<? echo $chart->get('title'); ?>'
									}
								},
								scales: {
									y: {
										beginAtZero: true
									}
								}
							}
						});
					</script><?
				} ?>
			</div>
			<button type="button" style="margin-top: 10px" onclick="create_chart()">Neues Diagramm anlegen</button>
		</td>
	<tr>
</table>