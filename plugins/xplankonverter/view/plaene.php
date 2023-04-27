<?php
	include('header.php');
?>
<style>
	#container_paint {
		background-color: white;
	}

	.table-wrapper {
		margin-top: -50px;
	}

	.bootstrap-table .fixed-table-toolbar .columns-right {
	  margin-left: 3px;
	  margin-right: 3px;
	}
</style>
<script language="javascript" type="text/javascript">
	$('#gui-table').css('width', '100%');
	$(function () {
		result = $('#eventsResult');
		result.success = function(text) {
			message([{ type: 'notice', msg: text}], 1000, 100, 400);
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
			result.success('Tabelle erfolgreich geladen');
		})
		.on('post-body.bs.table', function (e, data) {
			$('.xpk-func-convert').click(
				starteKonvertierung
			);
/*			$('.xpk-func-generate-gml').click(
				starteXplanGmlGenerierung
			);*/
			$('.xpk-func-generate-inspire-gml').click(
				starteInspireGmlGenerierung
			);
			$('.xpk-func-del-konvertierung').click(
				loescheKonvertierung
			);
		})
		.on('load-error.bs.table', function (e, status) {
			console.log('loaderror');
			result.error('Event: load-error.bs.table');
		})

		// more examples for register events on data tables: http://jsfiddle.net/wenyi/e3nk137y/36/
	});

	// functions
	starteKonvertierung = function(e) {
		e.preventDefault();
		var konvertierung_id = $(e.target).parent().parent().attr('konvertierung_id');
		document.getElementById('sperrspinner').style.display = 'block';
		result.success('Starte Konvertierung und Validierung für Konvertierung-Id: ' + konvertierung_id);
		// set status to 'IN_KONVERTIERUNG'
		$.ajax({
			url: 'index.php?go=xplankonverter_konvertierung_status',
			data: {
				konvertierung_id: konvertierung_id,
				status: "<?php echo Konvertierung::$STATUS['IN_KONVERTIERUNG']; ?>",
				csrf_token: '<? echo $_SESSION['csrf_token']; ?>'
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
						konvertierung_id: konvertierung_id,
						csrf_token: '<? echo $_SESSION['csrf_token']; ?>'
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
								konvertierung_id: konvertierung_id,
								csrf_token: '<? echo $_SESSION['csrf_token']; ?>'
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
		var konvertierung_id = $(e).parent().attr('konvertierung_id'),
				symbol = $(e).children();

		symbol.removeClass('fa-code');
		symbol.addClass('fa-spinner fa-pulse fa-fw');

		// onclick="document.getElementById(\'sperrspinner\').style.display = \'block\';"
		result.success('Starte GML-Ausgabe für Konvertierung-Id: ' + konvertierung_id);
		// set status to 'IN_GML_ERSTELLUNG'
		$.ajax({
			url: 'index.php?go=xplankonverter_konvertierung_status',
			data: {
				konvertierung_id: konvertierung_id,
				status: "<?php echo Konvertierung::$STATUS['IN_GML_ERSTELLUNG']; ?>",
				csrf_token: '<? echo $_SESSION['csrf_token']; ?>'
			},
			complete: function () {
				// document.getElementById('sperrspinner').style.display = 'none';
			},
			error: function(response) {
				result.error('Fehler beim Starten der GML-Erstellung für Konvertierung-Id: ' + konvertierung_id);
				return;
			},
			success: function(response) {
				//$('#konvertierungen_table').bootstrapTable('refresh');
				// gml-Generierung starten
				$.ajax({
					url: 'index.php?go=xplankonverter_gml_generieren',
					data: {
						konvertierung_id: konvertierung_id,
						csrf_token: '<? echo $_SESSION['csrf_token']; ?>'
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

	starteXplanGmlValidation = function(konvertierung_id) {
		console.log('Starte Validierung des XPlanGML-Dokumentes mit konvertierung_id: ' + konvertierung_id + ' beim XPlanValidator der Leitstelle');
	};

	showXplanGmlValidationResult = function() {
		console.log('Zeige Ergebnisse der Validierung vom XPlanValidator der Leitstelle');
		event.stopImmediatePropagation();
	};

	createGeoWebService = function() {
		console.log('Create GeoWebService');
		event.stopImmediatePropagation();
	}

	starteInspireGmlGenerierung = function(e) {
		e.preventDefault();
		var konvertierung_id = $(e.target).parent().parent().attr('konvertierung_id');

		//onclick="document.getElementById(\'sperrspinner\').style.display = \'block\';"
		result.success('Starte INSPIRE GML-Ausgabe für Konvertierung-Id: ' + konvertierung_id);
		// set status to 'IN_INSPIRE_GML_ERSTELLUNG'
		$.ajax({
			url: 'index.php?go=xplankonverter_konvertierung_status',
			data: {
				konvertierung_id: konvertierung_id,
				status: "<?php echo Konvertierung::$STATUS['IN_INSPIRE_GML_ERSTELLUNG']; ?>",
				csrf_token: '<? echo $_SESSION['csrf_token']; ?>'
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
						konvertierung_id: konvertierung_id,
						csrf_token: '<? echo $_SESSION['csrf_token']; ?>'
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

	loescheKonvertierung = function(e) {
		e.preventDefault();
		var plan_name = $(e.target).parent().parent().attr('plan_name'),
				plan_oid = $(e.target).parent().parent().attr('plan_oid'),
				r = confirm("Soll der Plan " + plan_oid + " wirklich gelöscht werden?");

		if (r == true) {
			$(this).closest('tr').remove();
			result.text('Lösche Plan: ' + plan_name);
			$.ajax({
				url: 'index.php',
				data: {
					go: 'xplankonverter_konvertierung_loeschen',
					planart: '<?php echo $this->formvars['planart']; ?>',
					plan_oid: plan_oid,
					csrf_token: '<? echo $_SESSION['csrf_token']; ?>'
				},
				success: function(response) {
					message([response]);
				}
			});
		}
	};

	toggleVeroeffentlicht = function(konvertierung_id, veroeffentlicht) {
		var veroeffentlicht = (veroeffentlicht == 'Ja' ? 'f' : 't');

		$('#veroeffentlicht_button_' + konvertierung_id).hide();
		$('#veroeffentlicht_spinner_' + konvertierung_id).show();
		$.ajax({
			url: 'index.php',
			data: {
				go: 'xplankonverter_konvertierung_veroffentlichen',
				veroeffentlicht: veroeffentlicht,
				konvertierung_id: konvertierung_id,
				csrf_token: '<? echo $_SESSION['csrf_token']; ?>'
			},
			success: function(result) {
				if (result.success) {
					var konvertierung_id = result.konvertierung_id,
							row = $('#konvertierungen_table').bootstrapTable('getRowByUniqueId', konvertierung_id);

					row.veroeffentlicht = result.veroeffentlicht;
					$('#konvertierungen_table').bootstrapTable('updateByUniqueId', { id: konvertierung_id, row: row});

					message([{
						"type": "notice",
						"msg" : 'Plan' + (result.veroeffentlicht == 'Ja' ? ' erfolgreich veröffentlicht' : 'veröffentlichung zurückgenommen') + '!'
					}]);
				}
				else {
					message([{
						"type": "error",
						"msg": result.msg
					}]);
				}
			}
		});
	};

	editVeroeffentlichungsdatum = function(konvertierung_id, veroeffentlichungsdatum) {
		document.getElementById('input_konvertierung_id').value = konvertierung_id;
		document.getElementById('input_veroeffentlichungsdatum').value = veroeffentlichungsdatum;
		$('#downloadMessageSperrDiv, .edit-veroeffentlichungsdatum-div').show();
	};

	toggleVeroeffentlichungsdatum = function(konvertierung_id, veroeffentlichungsdatum) {
		var today = new Date();
		$('#veroeffentlichungsdatum_button_' + konvertierung_id).hide();
		$('#veroeffentlichungsdatum_spinner_' + konvertierung_id).show();
		$.ajax({
			url: 'index.php',
			data: {
				go: 'xplankonverter_konvertierung_veroffentlichungsdatum',
				veroeffentlichungsdatum: veroeffentlichungsdatum,
				konvertierung_id: konvertierung_id,
				csrf_token: '<? echo $_SESSION['csrf_token']; ?>'
			},
			success: function(result) {
				if (result.success) {
					var konvertierung_id = result.konvertierung_id,
							row = $('#konvertierungen_table').bootstrapTable('getRowByUniqueId', konvertierung_id);

					row.veroeffentlichungsdatum = result.veroeffentlichungsdatum.split('-').reverse().join('.');

					$('#konvertierungen_table').bootstrapTable('updateByUniqueId', { id: konvertierung_id, row: row});

					$('#veroeffentlichungsdatum_spinner_' + konvertierung_id).hide();
					$('#veroeffentlichungsdatum_button_' + konvertierung_id).show();
					$('#downloadMessageSperrDiv, .edit-veroeffentlichungsdatum-div').hide();

					message([{
						"type": "notice",
						"msg" : 'Neues Veröffentlichungsdatum erfolgreich eingetragen!'
					}]);
				}
				else {
					message([{
						"type": "error",
						"msg": result.msg
					}]);
				}
			}
		});
	};

	function konvertierungHtmlSpecialchars(value) {
		return htmlspecialchars(value);
	}

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

	// formatter functions
	function konvertierungStatusFormatter(value, row) {
		var output = value;
		return output;
	}

	function konvertierungBundeslandFormatter(value, row) {
		var bundeslaender = JSON.parse(value);
		return bundeslaender;
	}

	function konvertierungEditFunctionsFormatter(value, row) {
		var funcIsAllowed,
				funcIsInProgress,
				disableFrag = ' disabled" onclick="return false',
				output = '<span class="btn-group" role="group" plan_oid="' + row.<?php echo $this->plan_oid_name; ?> + '" plan_name="' + htmlspecialchars(row.anzeigename) + '">';
		output += '<a title="Plan bearbeiten" class="btn btn-link btn-xs xpk-func-btn" href="index.php?go=Layer-Suche_Suchen&selected_layer_id=<?php echo $this->plan_layer_id ?>&operator_plan_gml_id==&value_plan_gml_id=' + row.plan_gml_id + '"><i class="btn-link fa fa-lg fa-pencil"></i></a>';
		output += '<a id="delButton' + value + '" title="Konvertierung l&ouml;schen" class="btn btn-link btn-xs xpk-func-btn xpk-func-del-konvertierung" href="#"><i class="fa fa-lg fa-trash"></i></a>';
		output += '</span>';
		return output;
	}

	function konvertierungFunctionsFormatter(value, row) {
		var funcIsAllowed,
				funcIsInProgress,
				disableFrag = ' xpk-func-btn-disabled disabled" onclick="return false',
				output = '<span class="btn-group" role="group" plan_oid="' + row.<?php echo $this->plan_oid_name; ?> + '" konvertierung_id="' + value + '">';

		// enabled by status of konvertierung
		// Shapefile upload
		funcIsAllowed = true; // function is always allowed
		output += '<a title="Shapefiles bearbeiten" class="btn btn-link btn-xs	xpk-func-btn' + (funcIsAllowed ? '' : disableFrag) + '" href="index.php?go=xplankonverter_shapefiles_index&konvertierung_id=' + value + '"><i class="fa fa-lg fa-upload"></i></a>';

		// Konvertieren und validieren
		funcIsAllowed = row.konvertierung_status == "<?php echo Konvertierung::$STATUS['ERSTELLT'					]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['KONVERTIERUNG_OK'	]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['KONVERTIERUNG_ERR' ]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_OK' ]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_ERR']; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_VALIDIERUNG_OK'				 ]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_VALIDIERUNG_ERR'			 ]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_ERR']; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK' ]; ?>";
		output += '<a title="Konvertierung durchführen & validieren" class="btn btn-link btn-xs' + (funcIsAllowed ? ' xpk-func-btn' : disableFrag) + '" href="index.php?go=xplankonverter_konvertierung&konvertierung_id=' + value + '&planart=<?php echo $this->formvars['planart']; ?>" onclick="document.getElementById(\'sperrspinner\').style.display = \'block\';"><i class="fa fa-lg fa-cogs"></i></a>';

		// Validierungsergebnisse anzeigen
		funcIsAllowed = row.konvertierung_status == "<?php echo Konvertierung::$STATUS['ERSTELLT'					]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['KONVERTIERUNG_OK'	]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['KONVERTIERUNG_ERR' ]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_OK' ]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_ERR']; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_VALIDIERUNG_OK'				 ]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_VALIDIERUNG_ERR'			 ]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_ERR']; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK' ]; ?>";
		output += '<a title="Validierungsergebnisse der Konvertierung anzeigen" class="btn btn-link btn-xs' + (funcIsAllowed ? ' xpk-func-btn' : disableFrag) + '" href="index.php?go=xplankonverter_validierungsergebnisse&konvertierung_id=' + value + '" onclick="document.getElementById(\'sperrspinner\').style.display = \'block\';"><i class="fa fa-lg fa-list-alt"></i></a>';

		// GML-Erzeugen
		funcIsAllowed = row.konvertierung_status == "<?php echo Konvertierung::$STATUS['KONVERTIERUNG_OK'					]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_OK'				 ]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_VALIDIERUNG_OK'				 ]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_VALIDIERUNG_ERR'			 ]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_ERR']; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK' ]; ?>";

		funcIsInProgress = row.konvertierung_status == "<?php echo Konvertierung::$STATUS['IN_GML_ERSTELLUNG']; ?>";
		output += '<a ' +
								'title="XPlan-GML-Datei erzeugen" ' +
								'class="btn btn-link btn-xs xpk-func-generate-gml' + (funcIsAllowed ? ' xpk-func-btn" ' +
								'onclick="starteXplanGmlGenerierung(this)' : disableFrag) + '" ' +
								'href="#"' +
							'>' +
								'<i class="' + (funcIsInProgress ? 'fa fa-spinner fa-pulse fa-fw' : 'fa fa-lg fa-code') + '"></i>' +
							'</a>';<?

		if (XPLANKONVERTER_FUNC_VALIDATOR) { ?>
			// GML-Validieren
			funcIsAllowed = row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_OK'				 ]; ?>"
									 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_VALIDIERUNG_OK'				 ]; ?>"
									 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_VALIDIERUNG_ERR'			 ]; ?>"
									 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_ERR']; ?>"
									 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK' ]; ?>";

			if (funcIsAllowed) {
				funcOnClick = "";
			}
			else {
				funcOnClick = "message([{type: 'info', msg: 'Sie müssen erst das XPlanGML-Dokument erzeugen!'}], 2000, 1000, 400)";
			}
			output += '<a ' +
						 			'title="XPlanGML-Datei mit dem XPlanValidator der XPlanung-Leitstelle validieren" ' +
									'class="btn btn-link btn-xs xpk-func-validate-gml ' + (funcIsAllowed ? 'xpk-func-btn" onclick="message([{ type: \'confirm\', msg: \'XPlanGML bei der Leitstelle validieren.<br>Die alten Validierungsergebnisse werden dabei überschrieben!\'}], 2000, 1000, 400, \'' + row.konvertierung_id + '\', \'starteXplanGmlValidation\', \'Validerung starten\')' : disableFrag) + '" ' +
									'href="#"' +
								'>' +
									'<i class="' + (funcIsInProgress ? 'fa fa-spinner fa-pulse fa-fw' : 'fa fa-lg fa-check') + '"></i>' +
								'</a>';

			// GML-Validierungsergebnisse ansehen
			funcIsAllowed = row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_VALIDIERUNG_OK'				 ]; ?>"
									 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_VALIDIERUNG_ERR'			 ]; ?>"
									 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_ERR']; ?>"
									 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK' ]; ?>";
			output += '<a ' +
									'title="Validierungsergebnisse der Konvertierung anzeigen" ' +
									'class="btn btn-link btn-xs' + (funcIsAllowed ? ' xpk-func-btn' : disableFrag) + '" ' +
									'href="index.php?go=Layer-Suche_Suchen&selected_layer_id=522&konvertierung_id_operator==&konvertierung_id_value=' + row.konvertierung_id +
									'onclick="document.getElementById(\'sperrspinner\').style.display = \'block\';"' +
								'>' +
									 '<i class="fa fa-lg fa-list-alt"></i>' +
									 '<i class="fa fa-lg fa-check" style="position: absolute; top: 7px; left: 13px;"></i>' +
								'</a>';<?
		}
		
		if (XPLANKONVERTER_FUNC_INSPIRE) { ?>
			if (<?php echo ((defined('XPLANKONVERTER_INSPIRE_KONVERTER') AND !XPLANKONVERTER_INSPIRE_KONVERTER) ? 'false' : 'true'); ?>) {
				// INSPIRE-Erstellung
				funcIsAllowed = row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_OK']; ?>"
											|| row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK']; ?>";
				funcIsInProgress = row.konvertierung_status == "<?php echo Konvertierung::$STATUS['IN_INSPIRE_GML_ERSTELLUNG']; ?>";
				output += '<a title="INSPIRE-GML-Datei erzeugen" class="btn btn-link btn-xs xpk-func-generate-inspire-gml' + (funcIsAllowed ? ' xpk-func-btn' : disableFrag) + '" href="#"><i class="' + (funcIsInProgress ? 'fa fa-spinner fa-pulse fa-fw' : 'fa fa-lg fa-globe') + '"></i></a>';
			} <?
		}

		if (XPLANKONVERTER_FUNC_SERVICE) { ?>
			// Dienst-erzeugen
			funcIsAllowed = row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_VALIDIERUNG_OK'				 ]; ?>"
									 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK' ]; ?>";
			output += '<a ' +
									 'title="GeoWeb-Dienst für den Plan anlegen" ' +
									 'class="btn btn-link btn-xs xpk-func-create-geoweb-service' + (funcIsAllowed ? ' xpk-func-btn' : disableFrag) + '" ' +
									 'href="index.php?go=">\
									 <i class="' + (funcIsInProgress ? 'fa fa-spinner fa-pulse fa-fw' : 'fa fa-lg fa-map-o') + '"></i>\
									 <i class="fa fa-plus-circle" style="position: absolute; top: 14px; left: 25px;"></i>\
								</a>'; <?
		} ?>

		output += '</span>';
		return output;
	}

	function konvertierungVeroeffentlichtFormatter(value, row) {
		var output = '<a\
			title="' + (row.veroeffentlicht == 'Ja' ? 'Planveröffentlichung zurücknehmen' : 'Plan veröffentlichen') + ' (Bei Auswahl wird der Datensatz im Bauportal M-V angezeigt. Bitte nur Pläne veröffentlichen, für die mindestens eine Plandatei verfügbar ist.)"\
			class="btn btn-link btn-xs xpk-func-btn ' + (row.veroeffentlicht == 'Ja' ? 'green' : 'red') + '"\
			href="#"\
			onclick="toggleVeroeffentlicht(' + row.konvertierung_id + ', \'' + row.veroeffentlicht + '\')");\
		><i\
			id="veroeffentlicht_button_' + row.konvertierung_id + '"\
			class="fa fa-lg ' + (row.veroeffentlicht == 'Ja' ? 'fa-eye' : 'fa-eye-slash') + '"\
		></i></a>\
		<i\
			id="veroeffentlicht_spinner_' + row.konvertierung_id + '"\
			class="fa fa-spinner fa-spin"\
			style="display: none"\
		></i>';

		return output;
	}

	function konvertierungVeroeffentlichungsdatumFormatter(value, row) {
		var symbol = {};
		var today = new Date();
		var veroeffentlichungsdatum = row.veroeffentlichungsdatum === null ? '' : row.veroeffentlichungsdatum.split('.').reverse().join('-');

		//console.log('today: ', today);
		//console.log('veroeff: ', new Date(veroeffentlichungsdatum));
		//console.log('vergleich: ', new Date(veroeffentlichungsdatum) == today);

		if (veroeffentlichungsdatum === '') {
			symbol.title = 'Plan ist nicht veröffentlicht. Kein Datum angegeben!';
			symbol.colorClass = 'red';
			symbol.class = 'fa-eye-slash'; // ist nicht veröffentlicht
		}
		else if (new Date(veroeffentlichungsdatum) > today) {
			symbol.title = 'Plan wird am ' + value + ' veröffentlicht.';
			symbol.colorClass = 'yellow';
			symbol.class = 'fa-clock-o'; // wird noch veröffentlicht
		}
		else if ((new Date(veroeffentlichungsdatum)).toDateString() == today.toDateString()) {
			symbol.title = 'Plan wurde heute veröffentlicht.';
			symbol.colorClass = 'green';
			symbol.class = 'fa-eye'; // ist veröffentlicht
		}
		else {
			symbol.title = 'Plan wurde am ' + value + ' veröffentlicht.';
			symbol.colorClass = 'green';
			symbol.class = 'fa-eye'; // ist veröffentlicht
		}

		var output = '<a\
			title="' + symbol['title'] + '"\
			class="btn btn-link btn-xs xpk-func-btn ' + symbol['colorClass'] + '"\
			href="#"\
			onclick="editVeroeffentlichungsdatum(' + row.konvertierung_id + ', \'' + veroeffentlichungsdatum + '\')");\
		><i\
			id="veroeffentlichungsdatum_button_' + row.konvertierung_id + '"\
			class="fa fa-lg ' + symbol['class'] + '"\
		></i></a>\
		<i\
			id="veroeffentlichungsdatum_spinner_' + row.konvertierung_id + '"\
			class="color: fa fa-spinner fa-spin"\
			style="display: none"\
		></i>';

		return output;
	}

	function konvertierungDownloadsFormatter(value, row) {
		var funcIsAllowed, funcIsInProgress,
			disableFrag = ' xpk-func-btn-disabled disabled" onclick="return false';
		output = '<span class="btn-group" role="group" plan_oid="' + row.<?php echo $this->plan_oid_name; ?> + '" konvertierung_id="' + value + '">';

		// hochgeladene Shapes
		funcIsAllowed = row.konvertierung_status == "<?php echo Konvertierung::$STATUS['IN_ERSTELLUNG'		 ]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['ERSTELLT'					]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['KONVERTIERUNG_OK'	]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['KONVERTIERUNG_ERR' ]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_OK' ]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_ERR']; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_VALIDIERUNG_OK']; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_VALIDIERUNG_ERR']; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_ERR']; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK' ]; ?>";
		output += '<a title="Hochgeladene Shapes" class="btn btn-link btn-xs xpk-func-download-gml' + (funcIsAllowed ? ' xpk-func-btn' : disableFrag) + ' green" href="javascript:void(0);" onclick="$(\'#downloadMessageSperrDiv\').show(); $(\'#downloadMessage\').show(); ahah(\'index.php?go=xplankonverter_download_files_query\', \'file_type=uploaded_shape_files&konvertierung_id=' + value + '\', [$(\'#downloadMessage\')[0]], [\'sethtml\']);"><i class="fa fa-lg fa-file-photo-o"></i></a>';

		// geänderte Shapes
		funcIsAllowed =	row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_OK'				 ]; ?>"
									|| row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_ERR']; ?>"
									|| row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK' ]; ?>";
		output += '<a title="Geänderte Shapes" class="btn btn-link btn-xs xpk-func-download-gml' + (funcIsAllowed ? ' xpk-func-btn' : disableFrag) + ' orange" href="javascript:void(0);" onclick="$(\'#downloadMessageSperrDiv\').show(); $(\'#downloadMessage\').show(); ahah(\'index.php?go=xplankonverter_download_files_query\', \'file_type=edited_shape_files&konvertierung_id=' + value + '\', [$(\'#downloadMessage\')[0]], [\'sethtml\']);"><i class="fa fa-lg fa-file-image-o"></i></a>';

		// XPlanung-GML
		funcIsAllowed =	row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_OK'				 ]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_VALIDIERUNG_OK']; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_VALIDIERUNG_ERR']; ?>"
									|| row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_ERR']; ?>"
									|| row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK' ]; ?>";
		output += '<a title="XPlanung-GML" class="btn btn-link btn-xs xpk-func-download-gml' + (funcIsAllowed ? ' xpk-func-btn' : disableFrag) + ' red" href="javascript:void(0);" onclick="$(\'#downloadMessageSperrDiv\').show(); $(\'#downloadMessage\').show(); ahah(\'index.php?go=xplankonverter_download_files_query\', \'file_type=xplan_gml&konvertierung_id=' + value + '\', [$(\'#downloadMessage\')[0]], [\'sethtml\']);"><i class="fa fa-lg fa-file-excel-o";"></i></a>';

		// XPlanung-Shapes
		funcIsAllowed =	row.konvertierung_status == "<?php echo Konvertierung::$STATUS['KONVERTIERUNG_OK'					]; ?>"
									|| row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_ERSTELLUNG_OK'				 ]; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_VALIDIERUNG_OK']; ?>"
								 || row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GML_VALIDIERUNG_ERR']; ?>"
									|| row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_ERR']; ?>"
									|| row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK' ]; ?>";
		output += '<a title="XPlanung-Shapes" class="btn btn-link btn-xs xpk-func-download-gml' + (funcIsAllowed ? ' xpk-func-btn' : disableFrag) + ' red" href="javascript:void(0);" onclick="$(\'#downloadMessageSperrDiv\').show(); $(\'#downloadMessage\').show(); ahah(\'index.php?go=xplankonverter_download_files_query\', \'file_type=xplan_shape_files&konvertierung_id=' + value + '\', [$(\'#downloadMessage\')[0]], [\'sethtml\']);"><i class="fa fa-lg fa-file-picture-o"></i></a>';<?

		if (XPLANKONVERTER_FUNC_INSPIRE) { ?>
			if (<?php echo ((defined('XPLANKONVERTER_INSPIRE_KONVERTER') AND !XPLANKONVERTER_INSPIRE_KONVERTER) ? 'false' : 'true'); ?>) {
				// INSPIRE-GML
				funcIsAllowed = row.konvertierung_status == "<?php echo Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK']; ?>";
				output += '<a title="INSPIRE-GML" class="btn btn-link btn-xs xpk-func-btn xpk-func-download-inspire-gml' + (funcIsAllowed ? '' : disableFrag) + ' blue" href="javascript:void(0);" onclick="$(\'#downloadMessageSperrDiv\').show(); $(\'#downloadMessage\').show(); ahah(\'index.php?go=xplankonverter_download_files_query\', \'file_type=inspire_gml&konvertierung_id=' + value + '\', [$(\'#downloadMessage\')[0]], [\'sethtml\']);" download="inspire_gml.gml" download="inspire"><i class="fa fa-lg fa-file-code-o"></i></a>';
			}<?
		}

		if (XPLANKONVERTER_FUNC_SERVICE) { ?>
			// GeoWebService Capabilities
			funcIsAllowed =	row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GEO_WEBSERVICE_ERSTELLT']; ?>";
			output += '<a title="Dienst-Capabilities" class="btn btn-link btn-xs xpk-func-download-gml' + (funcIsAllowed ? ' xpk-func-btn' : disableFrag) + ' red" href="javascript:void(0);" onclick="$(\'#downloadMessageSperrDiv\').show(); $(\'#downloadMessage\').show(); ahah(\'index.php?go=xplankonverter_download_files_query\', \'file_type=geoweb_service_capabilities&konvertierung_id=' + value + '\', [$(\'#downloadMessage\')[0]], [\'sethtml\']);"><i class="fa fa-lg fa-file-code-o"></i></a>';

			// Dienst-Metadaten
			funcIsAllowed =	row.konvertierung_status == "<?php echo Konvertierung::$STATUS['GEO_WEBSERVICE_ERSTELLT']; ?>";
			output += '<a title="Dienst-Metadaten" class="btn btn-link btn-xs xpk-func-download-gml' + (funcIsAllowed ? ' xpk-func-btn' : disableFrag) + ' red" href="javascript:void(0);" onclick="$(\'#downloadMessageSperrDiv\').show(); $(\'#downloadMessage\').show(); ahah(\'index.php?go=xplankonverter_download_files_query\', \'file_type=geoweb_service_metadata&konvertierung_id=' + value + '\', [$(\'#downloadMessage\')[0]], [\'sethtml\']);"><i class="fa fa-lg fa-file-text-o"></i></a>';<?
		} ?>

		output += '</span>';
		return output;
	}

	function konvertierungWirksamkeitsdatumSorter(fieldA, fieldB, rowA, rowB) {
		const dateA = fieldA.split(".").reverse().join("");
		const dateB = fieldB.split(".").reverse().join("");
		console.log('A: ' + dateA + ' > B: ' + dateB);
		return dateA > dateB ? 1 : -1;
	}

</script>
<h2><?php echo htmlspecialchars($this->title); ?></h2><?php
if ($this->Stelle->id > 200) { ?>
	<button type="button" id="new_konvertierung" name="go_plus" onclick="location.href='index.php?go=neuer_Layer_Datensatz&selected_layer_id=<?php echo $this->plan_layer_id ?>'">neu</button>
	<button type="button" id="new_konvertierung_from_gml" name="go_plus" onclick="location.href='index.php?go=xplankonverter_upload_xplan_gml&planart=<?php echo $this->formvars['planart'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>'">Neuer Plan aus XPlanGML</button><?
}
else { ?>
	Neue Pläne können nur in Amts oder Gemeindestellen angelegt werden. Wechseln Sie dazu die Stelle über <a href="#" onclick="
		$('#user_options').toggle();
		$('#sperr_div').toggle()
	">Einstellungen</a>.<?
}
?>

<!--div class="alert alert-success" style="white-space: pre-wrap" id="eventsResult">
		Here is the result of event.
</div//-->
<div id="downloadMessage"></div>
<div id="downloadMessageSperrDiv" class="sperr-div"></div>
<div id="editVeroeffentlichungsdatumDiv" class="edit-veroeffentlichungsdatum-div">
	<h2>Veröffentlichungsdatum</h2>
Liegt das Datum in der Zukunft, wird der Plan automatisch zu diesem Datum veröffentlicht. Wird das Datum gelöscht, gilt der Plan als nicht veröffentlicht. Ein Datum in der Vergangenheit oder heute setzt den Plan als veröffentlicht.<p></p>
	<input id="input_konvertierung_id" type="hidden">
	<input id="input_veroeffentlichungsdatum" type="date" style="margin: 20px"><p></p>
	<input type="button" value="<? echo $this->strCancel; ?>" onclick="$('#downloadMessageSperrDiv, .edit-veroeffentlichungsdatum-div').hide()"/>
	<input type="button" name="Sende Veröffentlichungsdatum" value="Übernehmen" onclick="toggleVeroeffentlichungsdatum(document.getElementById('input_konvertierung_id').value, document.getElementById('input_veroeffentlichungsdatum').value)"/>
</div>
<div class="table-wrapper">
	<table
		id="konvertierungen_table"
		data-unique-id="konvertierung_id"
		data-toggle="table"
		data-url="index.php"
		data-click-to-select="false"
		data-filter-control="true"
		data-sort-name="Name"
		data-sort-order="asc"
		data-search="true"
		data-visible-search="true"
		data-show-export="false"
		data-show-refresh="false"
		data-show-toggle="false"
		data-show-columns="true"
		data-query-params="go=Layer-Suche_Suchen&selected_layer_id=<?php echo $this->plan_layer_id ?>&anzahl=10000&mime_type=formatter&format=json"
		data-pagination="true"
		data-page-list=[10,15,25,50,100,250,500,1000,all]
		data-page-size="15"
		data-show-export="false"
		data-export_types=['json', 'xml', 'csv', 'txt', 'sql', 'excel']
		data-toggle="table"
		data-toolbar="#toolbar"
	>
		<thead>
			<tr>
				<th
					data-field="anzeigename"
					data-sortable="true"
					data-visible="true"
					data-formatter="konvertierungHtmlSpecialchars"
					class="col-md-7"
					data-filter-control="input"
					data-filter-control-placeholder="Suchen"
				>Name</th>
				<th
					data-field="nummer"
					data-sortable="true"
					data-visible="false"
					class="col-md-2"
					data-filter-control="input"
				>Nr</th><?php
				if ($this->plan_layer_id == XPLANKONVERTER_RP_PLAENE_LAYER_ID) { ?>
					<th
						data-field="bundesland"
						data-visible="false"
						data-sortable="true"
						data-formatter="konvertierungBundeslandFormatter"
						class="col-md-2"
						data-filter-control="select"
					>Bundesland</th><?php
				}
				else { ?>
					<th
						data-field="gemeinde"
						data-visible="true"
						data-sortable="true"
						data-formatter="konvertierungGemeindeFormatter"
						class="col-md-2"
						data-filter-control="select"
						data-filter-control-placeholder="Filtern nach"
					>Gemeinden</th><?php
				}
				if ($this->plan_layer_id != XPLANKONVERTER_RP_PLAENE_LAYER_ID) { ?>
					<th
						data-field="konvertierung_status"
						data-visible="true"
						data-sortable="true"
						data-formatter="konvertierungStatusFormatter"
						class="col-md-2"
						data-filter-control="select"
						data-filter-control-placeholder="Filtern nach"
					>Status</th><?php
				} ?>
				<th
					data-field="wirksamkeitsdatum"
					data-sortable="true"
					data-visible="true"
					data-sorter="konvertierungWirksamkeitsdatumSorter"
					class="col-md-2"
					data-filter-control="input"
				>Wirksamkeit</th><?
				/*
				if (XPLANKONVERTER_ENABLE_PUBLISH) { ?>
					<th
						data-field="veroeffentlicht"
						data-visible="true"
						data-sortable="true"
						data-formatter="konvertierungVeroeffentlichtFormatter"
						data-searchable="false"
						data-switchable="true"
					><i title="Veröffentlichung" class="fa fa-share-alt" aria-hidden="true" style="color: black"></i></th><?
				}
				*/
				if (XPLANKONVERTER_ENABLE_PUBLISH) { ?>
					<th
						data-field="veroeffentlichungsdatum"
						data-visible="true"
						data-sortable="true"
						data-formatter="konvertierungVeroeffentlichungsdatumFormatter"
						data-searchable="false"
						data-switchable="false"
						class="text-center"
					><i title="Veröffentlichungsdatum" class="fa fa-share-alt" aria-hidden="true" style="color: black"></i></th><?
				} ?>
				<th
					data-field="konvertierung_id"
					data-visible="true"
					data-formatter="konvertierungFunctionsFormatter"
					data-switchable="false"
					class="col-md-2 text-center"
				>Funktionen</th>
				<th
					data-field="konvertierung_id"
					data-visible="true"
					data-formatter="konvertierungDownloadsFormatter"
					data-switchable="false"
					class="col-md-2 align-top text-center"
				>Downloads</th>
				<th
					data-field="plan_gml_id"
					data-visible="true"
					data-formatter="konvertierungEditFunctionsFormatter"
					data-switchable="false"
					class="col-md-2 text-center"
				>Edit</th>
				<th
					data-field="konvertierung_id"
					data-sortable="true"
					data-visible="false"
					data-switchable="true"
					data-searchable="false"
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
			</tr>
		</thead>
	</table>
</div><br>
<button type="button" id="backButton" class="xplankonverter-back-button" title="Nach oben" onclick="window.scrollTo({ top: 0, behavior: 'smooth' });">Zurück nach oben</button><?
if ($this->Stelle->id > 200) { ?>
	<button style="margin-top: 10px; margin-bottom: 10px" type="button" id="new_konvertierung" name="go_plus" onclick="location.href='index.php?go=neuer_Layer_Datensatz&selected_layer_id=<?php echo $this->plan_layer_id ?>'">neu</button>
	<button type="button" id="new_konvertierung_from_gml" name="go_plus" onclick="location.href='index.php?go=xplankonverter_upload_xplan_gml&planart=<?php echo $this->formvars['planart'] ?>'">Neuer Plan aus XPlanGML</button><?
} ?>
