<?php
	include(SNIPPETS . 'generic_form_parts.php');
	$layer = $this->qlayerset[$i];
	$attributes = $layer['attributes'];
?>
<style>
	body {
		font-family: sans-serif;
	}
	.subform {
/*		border: solid 1px red;
		background-color: aquamarine;*/
		padding: 5px;
		display: inline-block;
	}
	.form-field {
		margin-right: 4px;
	}
	.autofeld_zweispaltig_div {
		float: left;
		width: 250px;
	}
	.order-button {
		color: gray;
		margin-left: 5px;
	}
	.order-button:hover {
		cursor: pointer;
		color: black;
	}
	.option-button {
		color: gray;
		vertical-align: middle;
		margin-left: 5px;
		padding: 1px 7px 1px 7px;
		border: solid 1px white;
	}
	.option-button:hover {
		cursor: pointer;
		color: black;
	}
	.row-drop-button {
		color: gray;
		vertical-align: middle;
		margin-left: 5px;
		padding: 1px 3px 1px 3px;
		border: solid 1px white;
	}
	.row-drop-button:hover {
		cursor: pointer;
		border: solid 1px gray;
		border-radius: 4px;
		background-color: lightpink;
/*				box-shadow: 2px 2px 2px 0px grey;*/
	}
</style>
<script>
	data = {
		"layer_id" : <?php echo $this->formvars['selected_layer_id']; ?>,
		"fk_column" : 'kartierung_id',
		"attributes" : {
			"kartierung_id" : {
				"type" : 'hidden'
			},
			"pflanzenart_id" : {
				"type" : 'Autovervollständigungsfeld_zweispaltig',
				"alias" : 'Pflanzenart',
				"options" : [{
					"values" : '',
					"output" : '--- Bitte Wählen ---'
				}, {
					"value" : 589,
					"output" : 'Artemisia absinthium - Wermut'
				}, {
					"value" : 453,
					"output" : 'Artemisia biennis - Zweijähriger Beifuß'
				}, {
					"value" : 284,
					"output" : 'Berteroa incana - Graukresse'
				}]
			},
			"dzv" : {
				"type" : 'Auswahlfeld',
				"alias" : 'DZV',
				"options" : [{
					"value" : 'D',
					"output" : 'D'
				}, {
					"value" : 'Z',
					"output" : 'Z'
				}, {
					"value" : 'V',
					"output" : 'V'
				}]
			},
			"cf" : {
				"type" : 'Checkbox',
				"alias" : 'cf'
			}
		},
		"rows" : <?php echo json_encode($layer['shape']); ?>
	};

	row_id = 0;

	create_row = function(layer_id, fk_column, attributes, row_data) {
		console.log(row_data);
		var row = $('<div class="row-div">'),
				formField;

		row_id = row_id + 1;
		for (var key in attributes) {
			if (key != fk_column) {
				switch (attributes[key].type) {
					case 'text' :
						formField = $('<input\
							name="' + key + '"\
							type="text"\
							value="' + (row_data.hasOwnProperty(key) ? row_data[key] : '') + '"\
						>');
					break;
					case 'Auswahlfeld' :
						formField = $('<select\
							name="' + key + '"\
						>');
						for (var i in data.attributes[key].options) {
							var o = data.attributes[key].options[i],
									option = $('<option\
										value="' + o.value + '"\
										' + (row_data.hasOwnProperty(key) && row_data[key] == o.value ? ' selected' : '') + '\
									>').html(o.output);
							formField.append(option);
						};
					break;
					case 'Autovervollständigungsfeld_zweispaltig' :
						formField = $('<div id="autofeld_zweispaltig_auswahl_und_suggest_div_' + row_id + '" class="autofeld_zweispaltig_div">\
							<input id="output_106_pflanzenart_id_0_' + row_id + '" title="Planzenart" autocomplete="off" onkeydown="\
									if (this.backup_value == undefined) {\
										this.backup_value = this.value;\
										document.getElementById(\'106_pflanzenart_id_0_' + row_id + '\').backup_value = document.getElementById(\'106_pflanzenart_id_0_' + row_id + '"\').value;\
									}\
								" onkeyup="\
									autocomplete1(\'106\', \'pflanzenart_id\', \'106_pflanzenart_id_0_' + row_id + '\', this.value, \'zweispaltig\');\
								" onchange="\
									if (document.getElementById(\'suggests_106_pflanzenart_id_0_' + row_id + '\').style.display == \'block\') {\
										this.value = this.backup_value;\
										document.getElementById(\'106_pflanzenart_id_0_' + row_id + '\').value = document.getElementById(\'106_pflanzenart_id_0_' + row_id + '\').backup_value;\
										setTimeout(function() {\
												document.getElementById(\'suggests_106_pflanzenart_id_0_' + row_id + '\').style.display = \'none\';\
											},\
											500\
										);\
									}\
									if (\'\' != \'\') {\
										set_changed_flag(currentform.changed_106_)\
									}\
								" 1="" size="40" type="text" value="' + (typeof row_data['abbreviat'] === 'undefined' ? '' : row_data['abbreviat']) + '">\
							<input id="106_pflanzenart_id_0_' + row_id + '" class="" type="hidden" onchange="set_changed_flag(currentform.changed_106_);" name="106;pflanzenart_id;pflanzenvorkommen;;Autovervollständigungsfeld_zweispaltig;0;int4" value="' + row_data[key] + '">\
							<div valign="top" style="height:0px; position:relative;">\
								<div id="suggests_106_pflanzenart_id_0_' + row_id + '" style="\
										z-index: 3000;\
										display:none;\
										position:absolute;\
										left:0px; top:0px;\
										width: 400px;\
										vertical-align:top;\
										overflow:hidden;\
										border:solid grey 1px;\
									"></div>\
							</div>\
						</div>');
/*						formField = $('<input\
							name="' + key + '"\
							type="text"\
							style="width: 250px"\
							value="' + (row_data.hasOwnProperty(key) ? row_data[key] : '') + '"\
						>');*/
					break;
					case 'Checkbox' :
						formField = $('<input\
							name="' + key + '"\
							type="checkbox"\
							value="true"\
							' + (row_data.hasOwnProperty(key) && row_data[key] == 't' ? ' checked' : '') + '\
						>');
					break;
				}
				row.append(formField.addClass('form-field'));
			}
		}
		row.append('<i\
			class="fa fa-times row-drop-button"\
			aria-hidden="true"\
			onclick="$(this).parent().hide(); alert(\'Wirklich Löschen\')"\
		>');
		return row;
	},

	write_rows = function(data) {
		var keyElmt,
				head = $('<div class="head-div">').html('sortiert nach: ');

		sortField = $('<select onchange="console.log(\'Sortieren nach: \' + this.value)">');
		for (var key in data.attributes) {
			if (key != data.fk_column) {
				sortField.append($('<option value="' + key + '">' + data.attributes[key].alias + '</option>'));
			}
		}

		head.append(sortField);

		$('#add_row_button_' + data.layer_id).before(head);
		
		data.rows.forEach(function(row_data) {
//					$('#subform_div_' + data.layer_id).append(create_row(data.layer_id, data.attributes, row_data));
			$('#add_row_button_' + data.layer_id).before(
				create_row(data.layer_id, data.fk_column, data.attributes, row_data)
			);
		});
	};
</script>
<div id="subform_div_106" class="subform">
	<button
		id="add_row_button_106"
		name="test"
		value="test"
		type="button"
		href="#"
		onclick="
			$('#add_row_button_' + data.layer_id).before(create_row(data.layer_id, data.fk_column, data.attributes, {}));
			$('#add_row_button_' + data.layer_id).prev().find('input').first().focus();
		"
	>Hinzufügen</button>
	<input type="hidden" name="go" value="SubformFK">
	<input
		type="submit" name="go_plus" value="Alle Speichern" style="float: right"
		onclick="console.log('Sammle alle Werte aus dem Formular zusammen, erzeuge davon ein JSON und sende es zum Updaten der FK-Tabelle ab. Nach Erfolg zeige die Listendarstellung vom normalen Subform Embedded GLE an.')"
	>
</div>
<script>
	write_rows(data);
</script>