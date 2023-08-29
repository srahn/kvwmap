<?
	/**
	 * Formular zur Bearbeitung von Diagrammdefinitionen
	 * Styles diagram-edit-label-div und diagram-edit-input-div vorher setzen
	 * Funktionen aus layer_charts_functions.js vorher laden
	 */
?>
<script>
	function create_chart() {
		let id = '';
		Check = confirm('Diagramm wirklich anlegen?');
		if (Check == true) {
			alert('Diagramm wird angelegt!');
			$.ajax({
				url: 'index.php',
				data: {
					go: 'chart_erzeugen',
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
			<option value="average"<? echo ($chart->get('aggregate_function') == 'average' ? ' selected' : ''); ?>>Durchschnitt</option>
			<option value="min"<? echo ($chart->get('aggregate_function') == 'min' ? ' selected' : ''); ?>>Minimalwert</option>
			<option value="max"<? echo ($chart->get('aggregate_function') == 'max' ? ' selected' : ''); ?>>Maximalwert</option>
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