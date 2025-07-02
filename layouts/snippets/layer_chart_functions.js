/**
 * Functions to manage layer_charts. Include before view layer_chart_formular.php and layer_charts.php
 */ 
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

function create_chart(chart_div_id, layer_id, options = []) {
	let chart_type = options['type'];
	let values = $('.attr_' + layer_id + '_' + options['value_attribute_name']).map((k, v) => { return v.value });
	let names = $('.attr_' + layer_id + '_' + options['label_attribute_name']).map((k, v) => { return v.options[v.selectedIndex].text});
	let labels = [];
	let data = [];
	for (let i = 0; i < names.length; i++) {
		if (labels.indexOf(names[i]) == -1) {
			labels.push(names[i]);
			data.push(parseFloat(values[i]) / 10000);
		}
		else {
			data[labels.indexOf(names[i])] += parseFloat(values[i]) / 10000;
		}
	}
	let chart = new Chart(
		document.getElementById(chart_div_id), {
		type: chart_type,
		data: {
			labels: labels,
			datasets: [{
				label: options['value_attribute_label'],
				data: data,
				backgroundColor: (['bar', 'line'].indexOf(chart_type) != -1 ? 'lightblue' : COLORS),
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
					text: options['title']
				}
			},
			scales: {
				y: {
					beginAtZero: true
				}
			}
		}
	});
	return chart;
}

function change_chart_type(chart, chart_type) {
	chart.config.type = chart_type;
	chart.config.data.datasets[0].backgroundColor = (chart_type == 'bar' ? 'lightblue' : COLORS);
	chart.update();
}

function change_chart_title(chart, chart_title) {
	chart.config.options.plugins.title.text = chart_title;
	chart.update();
}

function create_chart(layer_id) {
	let id = '';
	Check = confirm('Diagramm wirklich anlegen?');
	if (Check == true) {
		alert('Diagramm wird angelegt!');
		$.ajax({
			url: 'index.php',
			data: {
				go: 'chart_erzeugen',
				layer_id: layer_id,
				title: $('.diagram_form_elem_' + id + '[name="diagram_title"]').val(),
				type: $('.diagram_form_elem_' + id + '[name="diagram_type"]').val(),
				value_attribute_label: $('.diagram_form_elem_' + id + '[name="diagram_value_attribute_label"]').val(),
				aggregate_function: $('.diagram_form_elem_' + id + '[name="diagram_aggregate_function"]').val(),
				value_attribute_name: $('.diagram_form_elem_' + id + '[name="diagram_value_attribute_name"]').val(),
				label_attribute_name: $('.diagram_form_elem_' + id + '[name="diagram_label_attribute_name"]').val(),
				csrf_token: csrf_token
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
				csrf_token: csrf_token
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