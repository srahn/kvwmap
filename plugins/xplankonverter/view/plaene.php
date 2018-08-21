<?php
	include('header.php');
?>
<script language="javascript" type="text/javascript">
	$('#gui-table').css('width', '100%');
	$(function () {
		result = $('#eventsResult');
		result.success = function(text) {
//			message([{ type: 'notice', msg: text}], 1000, 500, '13%');
/*			result.text(text);
			result.removeClass('alert-danger');
			result.addClass('alert-success');*/
		};
		result.error = function(text){
			message([{ type: 'error', msg: text}]);
/*			result.text(text);
			result.removeClass('alert-success');
			result.addClass('alert-danger');*/
		};

		// event handler
		$('#konvertierungen_table')
		.one('load-success.bs.table', function (e, data) {
			result.success('Tabelle erfolgreich geladen.');
		})
		.on('load-success.bs.table', function (e, data) {
			$('.xpk-func-convert').click(
				starteKonvertierung
			);
			$('.xpk-func-generate-gml').click(
				starteXplanGmlGenerierung
			);
			$('.xpk-func-generate-inspire-gml').click(
				starteInspireGmlGenerierung
			);
			$('.xpk-func-del-konvertierung').click(
				loescheKonvertierung
			);
		})
		.on('load-error.bs.table', function (e, status) {
			result.error('Event: load-error.bs.table');
		});
		// more examples for register events on data tables: http://jsfiddle.net/wenyi/e3nk137y/36/
	});

	// functions
	starteKonvertierung = function(e) {
		var konvertierung_id = $(e.target).parent().parent().attr('konvertierung_id');
		document.getElementById('sperrspinner').style.display = 'block';
		result.success('Starte Konvertierung und Validierung für Konvertierung-Id: ' + konvertierung_id);
		// set status to 'IN_KONVERTIERUNG'
		$.ajax({
			url: 'index.php?go=xplankonverter_konvertierung_status',
			data: {
				konvertierung_id: konvertierung_id,
				status: "<?php echo Konvertierung::$STATUS['IN_KONVERTIERUNG']; ?>"
			},
			success: function(response) {
				if (!response.success){
					result.success(response.msg);
					return;
				}
				$('#konvertierungen_table').bootstrapTable('refresh');
				// konvertiere wenn Status gesetzt
				$.ajax({
					url: 'index.php?go=xplankonverter_regeln_anwenden',
					data: {
						konvertierung_id: konvertierung_id
					},
					complete: function () {
						document.getElementById('sperrspinner').style.display = 'none';
					},
					error: function(response) {
						result.error(response.msg);
					},
					success: function(response) {
						result.success(response.msg);
						if (!response.success) return;
						// validiere, wenn Konvertierung erfolgreich
						$.ajax({
							url: 'index.php?go=xplankonverter_konvertierung_validate',
							data: {
								konvertierung_id: konvertierung_id
							},
							complete: function () {
								document.getElementById('sperrspinner').style.display = 'none';
							},
							error: function(response) {
								result.error(response.msg);
							},
							success: function(response) {
								$('#konvertierungen_table').one('load-success.bs.table', function () {
									result.success(response.msg);
								});
								$('#konvertierungen_table').bootstrapTable('refresh');
							}
						});
					}
				});
			}
		});
	};

	starteXplanGmlGenerierung = function(e) {
		var konvertierung_id = $(e.target).parent().parent().attr('konvertierung_id');

		// onclick="document.getElementById(\'sperrspinner\').style.display = \'block\';"
		result.success('Starte GML-Ausgabe für Konvertierung-Id: ' + konvertierung_id);
		// set status to 'IN_GML_ERSTELLUNG'
		$.ajax({
			url: 'index.php?go=xplankonverter_konvertierung_status',
			data: {
				konvertierung_id: konvertierung_id,
				status: "<?php echo Konvertierung::$STATUS['IN_GML_ERSTELLUNG']; ?>"
			},
			complete: function () {
				// document.getElementById('sperrspinner').style.display = 'none';
			},
			error: function(response) {
				result.error('Fehler beim Starten der GML-Erstellung für Konvertierung-Id: ' + konvertierung_id);
				return;
			},
			success: function(response) {
				$('#konvertierungen_table').bootstrapTable('refresh');
				// gml-Generierung starten
				$.ajax({
					url: 'index.php?go=xplankonverter_gml_generieren',
					data: {
						konvertierung_id: konvertierung_id
					},
					complete: function () {
						//document.getElementById('sperrspinner').style.display = 'none';
					},
					error: function(response) {
						$('#konvertierungen_table').bootstrapTable('refresh');
						result.error('Fehler bei der XPlan-GML-Erstellung für Konvertierung-Id: ' + konvertierung_id + "\n" + response.responseText);
						console.error(response.responseText);
					},
					success: function(response) {
						if (!response.success){
							result.error(response.msg);
							return;
						}
						$('#konvertierungen_table').one('load-success.bs.table', function () {
							result.success(response.msg);
						});
						$('#konvertierungen_table').bootstrapTable('refresh');
					}
				});
			}
		});
	};

	starteInspireGmlGenerierung = function(e) {
		var konvertierung_id = $(e.target).parent().parent().attr('konvertierung_id');

		//onclick="document.getElementById(\'sperrspinner\').style.display = \'block\';"
		result.success('Starte INSPIRE GML-Ausgabe für Konvertierung-Id: ' + konvertierung_id);
		// set status to 'IN_INSPIRE_GML_ERSTELLUNG'
		$.ajax({
			url: 'index.php?go=xplankonverter_konvertierung_status',
			data: {
				konvertierung_id: konvertierung_id,
				status: "<?php echo Konvertierung::$STATUS['IN_INSPIRE_GML_ERSTELLUNG']; ?>"
			},
			complete: function () {
				// document.getElementById('sperrspinner').style.display = 'none';
			},
			error: function(response) {
				result.error('Fehler beim Starten der INSPIRE-GML-Erstellung für Konvertierung-Id: ' + konvertierung_id);
				return;
			},
			success: function(response) {
				$('#konvertierungen_table').bootstrapTable('refresh');
				// inspire-gml-Generierung starten
				$.ajax({
					url: 'index.php?go=xplankonverter_inspire_gml_generieren',
					data: {
						konvertierung_id: konvertierung_id
					},
					complete: function () {
						// document.getElementById('sperrspinner').style.display = 'none';
					},
					error: function(response) {
						$('#konvertierungen_table').bootstrapTable('refresh');
						result.error('Fehler bei der INSPIRE-GML-Erstellung für Konvertierung-Id: ' + konvertierung_id);
						console.error(response.responseText);
					},
					success: function(response) {
						if (!response.success){
							result.error(response.msg);
							return;
						}
						$('#konvertierungen_table').one('load-success.bs.table', function () {
							result.success(response.msg);
						});
						$('#konvertierungen_table').bootstrapTable('refresh');
					}
				});
			}
		});
	};

	// formatter functions
	function konvertierungGemeindeFormatter(value, row) {
		var gemeinden = JSON.parse(value);
		return $.map(
			gemeinden,
			function(gemeinde) {
				return gemeinde.gemeindename;
			}
		).join(', ')
	}

	function konvertierungBundeslandFormatter(value, row) {
		var bundeslaender = JSON.parse(value);
		return bundeslaender;
	}

	function konvertierungEditFunctionsFormatter(value, row) {
		var funcIsAllowed,
				funcIsInProgress,
				disableFrag = ' disabled" onclick="return false',
				output = '<span class="btn-group" role="group" konvertierung_oid="' + row.konvertierungen_oid + '" konvertierung_id="' + value + '">';
		output += '<a title="Plan bearbeiten" class="btn btn-link btn-xs xpk-func-btn" href="index.php?go=Layer-Suche_Suchen&selected_layer_id=<?php echo $this->plan_layer_id ?>&operator_plan_gml_id==&value_plan_gml_id=' + row.plan_gml_id + '"><i class="btn-link fa fa-lg fa-pencil"></i></a>';
		output += '<a title="Konvertierung l&ouml;schen" class="btn btn-link btn-xs xpk-func-btn xpk-func-del-konvertierung" href="#"><i class="btn-link fa fa-lg fa-trash"></i></a>';
		output += '</span>';
		return output;
	}

	function konvertierungFunctionsFormatter(value, row) {
		var funcIsAllowed,
				funcIsInProgress,
				disableFrag = ' disabled" onclick="return false',
				output = '<span class="btn-group" role="group" konvertierung_oid="' + row.konvertierungen_oid + '" konvertierung_id="' + value + '">';

		// enabled by status of konvertierung
		// Shapefile upload
    funcIsAllowed = true; // function is always allowed
		output += '<a title="Shapefiles bearbeiten" class="btn btn-link btn-xs	xpk-func-btn' + (funcIsAllowed ? '' : disableFrag) + '" href="index.php?go=xplankonverter_shapefiles_index&konvertierung_id=' + value + '"><i class="btn-link fa fa-lg fa-upload"></i></a>';

		// Konvertieren und validieren
    funcIsAllowed = row.konvertierung_status == "<?php echo Konvertierung::$STATUS['ERSTELLT'          ]; ?>"
                 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['KONVERTIERUNG_OK'  ]; ?>"
                 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['KONVERTIERUNG_ERR' ]; ?>"
                 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_OK' ]; ?>"
                 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_ERR']; ?>"
                 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_ERR']; ?>"
                 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK' ]; ?>";
		output += '<a title="Konvertierung durchführen & validieren" class="btn btn-link btn-xs xpk-func-btn' + (funcIsAllowed ? '' : disableFrag) + '" href="index.php?go=xplankonverter_konvertierung&konvertierung_id=' + value + '" onclick="document.getElementById(\'sperrspinner\').style.display = \'block\';"><i class="btn-link fa fa-lg fa-cogs"></i></a>';

		// Validierungsergebnisse anzeigen
    funcIsAllowed = row.konvertierung_status == "<?php echo Konvertierung::$STATUS['ERSTELLT'          ]; ?>"
                 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['KONVERTIERUNG_OK'  ]; ?>"
                 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['KONVERTIERUNG_ERR' ]; ?>"
                 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_OK' ]; ?>"
                 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_ERR']; ?>"
                 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_ERR']; ?>"
                 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK' ]; ?>";
		output += '<a title="Validierungsergebnisse anzeigen" class="btn btn-link btn-xs xpk-func-btn' + (funcIsAllowed ? '' : disableFrag) + '" href="index.php?go=xplankonverter_validierungsergebnisse&konvertierung_id=' + value + '" onclick="document.getElementById(\'sperrspinner\').style.display = \'block\';"><i class="btn-link fa fa-lg fa-eye"></i></a>';

		// GML-Erzeugen
		funcIsAllowed =  row.konvertierung_status == "<?php echo Konvertierung::$STATUS['KONVERTIERUNG_OK'          ]; ?>"
                  || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_OK'         ]; ?>"
                  || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_ERR']; ?>"
                  || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK' ]; ?>";

		funcIsInProgress = row.konvertierung_status == "<?php echo Konvertierung::$STATUS['IN_GML_ERSTELLUNG']; ?>";
		output += '<a title="XPlan-GML-Datei erzeugen" class="btn btn-link btn-xs xpk-func-btn xpk-func-generate-gml' + (funcIsAllowed ? '' : disableFrag) + '" href="#"><i class="' + (funcIsInProgress ? 'btn-link fa fa-spinner fa-pulse fa-fw' : 'btn-link fa fa-lg fa-code') + '"></i></a>';

		if (<?php echo ((defined('XPLANKONVERTER_INSPIRE_KONVERTER') AND !XPLANKONVERTER_INSPIRE_KONVERTER) ? 'false' : 'true'); ?>) {
			// INSPIRE-Erstellung
			funcIsAllowed = row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_OK']; ?>"
										|| row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK']; ?>";
			funcIsInProgress = row.konvertierung_status == "<?php echo Konvertierung::$STATUS['IN_INSPIRE_GML_ERSTELLUNG']; ?>";
			output += '<a title="INSPIRE-GML-Datei erzeugen" class="btn btn-link btn-xs xpk-func-btn xpk-func-generate-inspire-gml' + (funcIsAllowed ? '' : disableFrag) + '" href="#"><i class="' + (funcIsInProgress ? 'btn-link fa fa-spinner fa-pulse fa-fw' : 'btn-link fa fa-lg fa-globe') + '"></i></a>';
		}

		output += '</span>';
		return output;
	}

	function konvertierungDownloadsFormatter(value, row) {
		var funcIsAllowed, funcIsInProgress,
			disableFrag = ' disabled" onclick="return false';
		output = '<span class="btn-group" role="group" konvertierung_oid="' + row.konvertierungen_oid + '" konvertierung_id="' + value + '">';

		// hochgeladene Shapes
		funcIsAllowed = row.konvertierung_status == "<?php echo Konvertierung::$STATUS['IN_ERSTELLUNG'     ]; ?>"
		             || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['ERSTELLT'          ]; ?>"
		             || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['KONVERTIERUNG_OK'  ]; ?>"
		             || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['KONVERTIERUNG_ERR' ]; ?>"
		             || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_OK' ]; ?>"
		             || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_ERR']; ?>"
		             || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_ERR']; ?>"
		             || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK' ]; ?>";
		output += '<a title="Hochgeladene Shapes" class="btn btn-link btn-xs xpk-func-btn xpk-func-download-gml' + (funcIsAllowed ? '' : disableFrag) + '" href="index.php?go=xplankonverter_download_uploaded_shapes&konvertierung_id=' + value + '"><i class="btn-link fa fa-lg fa-file-photo-o" style="color: green;"></i></a>';

		// geänderte Shapes
		funcIsAllowed =  row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_OK'         ]; ?>"
		              || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_ERR']; ?>"
		              || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK' ]; ?>";
		output += '<a title="Geänderte Shapes" class="btn btn-link btn-xs xpk-func-btn xpk-func-download-gml' + (funcIsAllowed ? '' : disableFrag) + '" href="index.php?go=xplankonverter_download_edited_shapes&konvertierung_id=' + value + '"><i class="btn-link fa fa-lg fa-file-image-o" style="color: orange;"></i></a>';

		// XPlanung-GML
		funcIsAllowed =  row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_OK'         ]; ?>"
	                || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_ERR']; ?>"
	                || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK' ]; ?>";
		output += '<a title="XPlanung-GML" class="btn btn-link btn-xs xpk-func-btn xpk-func-download-gml' + (funcIsAllowed ? '' : disableFrag) + '" href="index.php?go=xplankonverter_download_xplan_gml&konvertierung_id=' + value + '"><i class="btn-link fa fa-lg fa-file-excel-o" style="color: red;"></i></a>';

		// GML-Erzeugen
		funcIsAllowed =  row.konvertierung_status == "<?php echo Konvertierung::$STATUS['KONVERTIERUNG_OK'          ]; ?>"
		              || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_OK'         ]; ?>"
		              || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_ERR']; ?>"
		              || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK' ]; ?>";
	output += '<a title="XPlanung-Shapes" class="btn btn-link btn-xs xpk-func-btn xpk-func-download-gml' + (funcIsAllowed ? '' : disableFrag) + '" href="index.php?go=xplankonverter_download_xplan_shapes&konvertierung_id=' + value + '"><i class="btn-link fa fa-lg fa-file-picture-o" style="color: red;"></i></a>';

		if (<?php echo ((defined('XPLANKONVERTER_INSPIRE_KONVERTER') AND !XPLANKONVERTER_INSPIRE_KONVERTER) ? 'false' : 'true'); ?>) {
			// INSPIRE-GML
			funcIsAllowed = row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK']; ?>";
			output += '<a title="INSPIRE-GML" class="btn btn-link btn-xs xpk-func-btn xpk-func-download-inspire-gml' + (funcIsAllowed ? '' : disableFrag) + '" href="index.php?go=xplankonverter_download_inspire_gml&konvertierung_id=' + value + '" download="inspire_gml.gml"><i class="btn-link fa fa-lg fa-file-code-o" style="color: blue;"></i></a>';
		}

		output += '</span>';
		return output;
	}

	loescheKonvertierung = function(e) {
		var konvertierung_id = $(e.target).parent().parent().attr('konvertierung_id'),
				konvertierung_oid = $(e.target).parent().parent().attr('konvertierung_oid');
		var r = confirm("Soll die Konvertierung mit der Id " + konvertierung_id + " wirklich gelöscht werden?")
		if(r == true) {
			$(this).closest('tr').remove();
			result.text('Lösche Konvertierung für Id: ' + konvertierung_id);
			$.ajax({
				url: 'index.php?checkbox_names_<?php echo $this->plan_layer_id ?>=check;konvertierungen;konvertierungen;' + konvertierung_oid + '&check;konvertierungen;konvertierungen;' + konvertierung_oid + '=on',
				data: {
					go: 'xplankonverter_konvertierung_loeschen',
					chosen_layer_id: <?php echo $this->plan_layer_id ?>
				},
				success: function(response) {
					result.text(response.msg);
				}
			});
		}
	};

</script>
<h2><?php echo $this->title; ?></h2>
<button type="button" id="new_konvertierung" name="go_plus" onclick="location.href='index.php?go=neuer_Layer_Datensatz&selected_layer_id=<?php echo $this->plan_layer_id ?>'">neu</button>
<button type="button" id="new_konvertierung_from_gml" name="go_plus" onclick="location.href='index.php?go=xplankonverter_upload_xplan_gml'">Neuer Plan aus XPlanGML</button>
<!--div class="alert alert-success" style="white-space: pre-wrap" id="eventsResult">
		Here is the result of event.
</div//-->
<table
	id="konvertierungen_table"
	data-toggle="table"
	data-url="index.php"
	data-height="100%"
	data-click-to-select="false"
	data-filter-control="true" 
	data-sort-name="gemeinde"
	data-sort-order="asc"
	data-search="true"
	data-show-export="false"
	data-show-refresh="false"
	data-show-toggle="false"
	data-show-columns="true"
	data-query-params="go=Layer-Suche_Suchen&selected_layer_id=<?php echo $this->plan_layer_id ?>&anzahl=10000&mime_type=formatter&format=json"
	data-pagination="true"
	data-page-size="25"
	data-show-export="false"
	data-export_types=['json', 'xml', 'csv', 'txt', 'sql', 'excel']
	data-toggle="table"
	data-toolbar="#toolbar"
>
	<thead>
		<tr>
			<th
				data-field="konvertierung_id"
				data-sortable="true"
				data-visible="false"
				data-switchable="true"
			>Konvertierung Id</th>
			<th
				data-field="plan_gml_id"
				data-sortable="true"
				data-visible="false"
				data-switchable="true"
			>Plan-Id</th>
			<th
				data-field="stelle_id"
				data-sortable="true"
				data-visible="false"
				data-switchable="true"
			>Stelle Id</th>
			<th
				data-field="anzeigename"
				data-sortable="true"
				data-visible="true"
				class="col-md-7"
			>Name</th><?php
				if ($this->plan_layer_id = XPLANKONVERTER_RP_PLAENE_LAYER_ID) { ?>
					<th
						data-field="bundesland"
						data-visible="false"
						data-sortable="true"
						data-formatter="konvertierungBundeslandFormatter"
						class="col-md-2"
					>Bundesland</th><?php
				}
				else { ?>
					<th
						data-field="gemeinde"
						data-visible="true"
						data-sortable="true"
						data-formatter="konvertierungGemeindeFormatter"
						class="col-md-2"
					>Gemeinden</th><?php
				}
				if ($this->plan_layer_id != XPLANKONVERTER_RP_PLAENE_LAYER_ID) { ?>
					<th
						data-field="konvertierung_status"
						data-visible="true"
						data-sortable="true"
						class="col-md-2"
					>Status</th><?php
				} ?>
			<th
				data-field="konvertierung_id"
				data-visible="true"
				data-formatter="konvertierungFunctionsFormatter"
				data-switchable="false"
				class="col-md-2"
			>Funktionen&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th
				data-field="konvertierung_id"
				data-visible="true"
				data-formatter="konvertierungDownloadsFormatter"
				data-switchable="false"
				class="col-md-2"
			>Downloads&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th
				data-field="konvertierung_id"
				data-visible="true"
				data-formatter="konvertierungEditFunctionsFormatter"
				data-switchable="false"
			>Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
		</tr>
	</thead>
</table>
<button style="margin-bottom: 10px" type="button" id="new_konvertierung" name="go_plus" onclick="location.href='index.php?go=neuer_Layer_Datensatz&selected_layer_id=<?php echo $this->plan_layer_id ?>'">neu</button>
<button type="button" id="new_konvertierung_from_gml" name="go_plus" onclick="location.href='index.php?go=xplankonverter_upload_xplan_gml&planart=<?php echo $this->formvars['planart'] ?>'">Neuer Plan aus XPlanGML</button>