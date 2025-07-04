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

	function layer_chart_edit(event, id) {
		event.preventDefault();
		root.window.location = 'index.php?go=layer_chart_Editor&id=' + id + '&csrf_token=<? echo $_SESSION['csrf_token']; ?>';
	}

	function change_chart_type(chart, chart_type) {
		chart.config.type = chart_type;
		if (['bar', 'line'].indexOf(chart_type) != -1) {
			chart.config.data.datasets[0].backgroundColor = 'lightblue';
			chart.config.options.plugins.legend.display = false;
		}
		else {
			chart.config.data.datasets[0].backgroundColor = COLORS;
			chart.config.options.plugins.legend.display = true;
		}
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
					layer_id: <? echo $layer['layer_id']; ?>,
					title: $('.diagram_form_elem_' + id + '[name="diagram_title"]').val(),
					type: $('.diagram_form_elem_' + id + '[name="diagram_type"]').val(),
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
				<img class="diagram_group_img" src="graphics/plus.gif" border="0" <? if ($layer['charts_status'] == 2) {echo 'style="display: none"';} ?>>
				<img class="diagram_group_img" src="graphics/minus.gif" border="0" <? if ($layer['charts_status'] != 2) {echo 'style="display: none"';} ?>>
			</a> Diagramme
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div id="diagramms" <? if ($layer['charts_status'] != 2) {echo 'style="display: none"';} ?>><?
				foreach ($layer['charts'] AS $chart) {
					$id = $chart->get($chart->identifier);
					#echo 'Chart: ' . $id; ?>
					<div id="chart_div_<? echo $id; ?>" style="width: <? echo $chart->get('breite'); ?>">
						<canvas id="chart_<? echo $id; ?>"></canvas>
						<div>
							Diagrammtyp: 
							<select id="chart_type_selector_<? echo $id; ?>" onchange="change_chart_type(chart_<? echo $id; ?>, this.value)">
								<option value="bar"<? echo ($chart->get('type') == 'bar' ? ' selected' : ''); ?>>Balken</option>
								<option value="pie"<? echo ($chart->get('type') == 'pie' ? ' selected' : ''); ?>>Torten</option>
								<option value="doughnut"<? echo ($chart->get('type') == 'doughnut' ? ' selected' : ''); ?>>Doughnut</option>
								<option value="line"<? echo ($chart->get('type') == 'line' ? ' selected' : ''); ?>>Linien</option>
							</select><?
							if (
								$this->Stelle->isMenueAllowed('Layereditor') 
							) { ?>
								<button type="button" style="margin-left: 10px" onclick="layer_chart_edit(event, <? echo $id; ?>);">Bearbeiten</button><?php
							} ?>
						</div>
					</div>
					<script>
						let chart_type_<? echo $id; ?> = '<? echo $chart->get('type'); ?>';
						let values_<? echo $id; ?> = $('.attr_<? echo $layer['layer_id']; ?>_<? echo $chart->get('value_attribute_name'); ?>').map((k, v) => { return v.value });
						let names_<? echo $id; ?> = $('.attr_<? echo $layer['layer_id']; ?>_<? echo $chart->get('label_attribute_name'); ?>').map((k, v) => { return (v.options?.[v.selectedIndex].text) ?? v.value});
						let labels_<? echo $id; ?> = [];
						let data_<? echo $id; ?> = [];
						for (let i = 0; i < names_<? echo $id; ?>.length; i++) {
							value = values_<? echo $id; ?>[i].replace(',', '.');
							if (labels_<? echo $id; ?>.indexOf(names_<? echo $id; ?>[i]) == -1) {
								// Die Klasse kommt zum ersten mal vor. Setze labels_$id und data_$id
								labels_<? echo $id; ?>.push(names_<? echo $id; ?>[i]);
								data_<? echo $id; ?>.push(parseFloat(value));
							}
							else {
								data_<? echo $id; ?>[labels_<? echo $id; ?>.indexOf(names_<? echo $id; ?>[i])] += parseFloat(value);
							}
						}
						let chart_<? echo $id; ?> = new Chart(
							document.getElementById('chart_<? echo $id; ?>'), {
							type: chart_type_<? echo $id; ?>,
							data: {
								labels: labels_<? echo $id; ?>,
								datasets: [{
									label: '',
									data: data_<? echo $id; ?>,
									backgroundColor: (['bar', 'line'].indexOf(chart_type_<? echo $id; ?>) != -1 ? 'lightblue' : COLORS),
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
									},
            			legend: {
                		display: (['bar', 'line'].indexOf(chart_type_<? echo $id; ?>) != -1 ? false : true),
									}
								},
								scales: {
									x: {
										display: 'auto'
									},
									y: {
										beginAtZero: true,
										display: 'auto'
									}
								}
							}
						});
					</script><?
				} ?>
			</div>
			<!--button type="button" style="margin-top: 10px" onclick="create_chart()">Neues Diagramm anlegen</button//-->
		</td>
	<tr>
</table>