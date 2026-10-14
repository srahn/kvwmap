<?
	include_once(LAYOUTPATH.'languages/generic_layer_editor_2_'.rolle::$language.'.php');
	$layerset = $this->qlayerset[$i];
	#echo 'qLayerset shape: ' . print_r($layerset['shape'], true) . '<p>';
	#echo 'Layerset attribute Keys: ' . print_r(array_keys($layerset['attributes']), true) . '<p>';

	#echo 'oid: ' . print_r($layerset['oid'], true) . '<p>';
	#echo 'type: ' . print_r($layerset['attributes']['type'], true) . '<p>';
	#echo 'typename: ' . print_r($layerset['attributes']['typename'], true) . '<p>';
	#echo 'form_element_type: ' . print_r($layerset['attributes']['form_element_type'], true) . '<p>';
	#echo 'rastervisibility: ' . print_r($layerset['attributes']['raster_visibility'], true) . '<p>';
	#echo 'Daten: ' . print_r($this->qlayerset[0]['shape'], true) . '<p>';
	if ($layerset['shape'] AND count($layerset['shape']) === 1) {
		if ($layerset['gle_view'] > 0) {
			include(SNIPPETS . 'generic_layer_editor_2.php');
		}
		else {
			include(SNIPPETS . 'generic_layer_editor.php');
		}
	}
	else {
		include_once(CLASSPATH . 'LayerAttributeRolleSetting.php');
		$larsObj = new LayerAttributeRolleSetting($this, $this->Stelle->id, $this->user->id, $layerset['layer_id']);
		$rolle_attribute_settings = $larsObj->read_layer_attributes2rolle($layerset['layer_id'], $this->Stelle->id, $this->user->id);
		if (count($rolle_attribute_settings) > 0) {
			$sort_attribute = array_values(
				array_filter(
					$rolle_attribute_settings,
					function ($attribute) {
						return $attribute['sort_order'];
					}
				)
			)[0];
		}
		else {
			$sort_attribute = array(
				'attributename' => $layerset['oid'],
				'sort_direction' => 'asc'
			);
		}
		#echo '<br>data-sort-name: ' . $sort_attribute['attributename'];
		#echo '<br>data-sort-order: ' . $sort_attribute['sort_direction']; ?>
		<link rel="stylesheet" href="<? echo BOOTSTRAP_PATH; ?>css/bootstrap.min.css">

		<script src="<? echo BOOTSTRAP_PATH; ?>lib/popper-1.16.1.min.js"></script>
		<script src="<? echo BOOTSTRAP_PATH; ?>js/bootstrap.min.js"></script>

		<link rel="stylesheet" href="<? echo BOOTSTRAPTABLE_PATH; ?>bootstrap-table.min.css">
		<script src="<? echo BOOTSTRAPTABLE_PATH; ?>bootstrap-table.min.js"></script>
		<script src="<? echo BOOTSTRAPTABLE_PATH; ?>locale/bootstrap-table-de-DE.min.js"></script>
		<script type="text/javascript" src="<? echo BOOTSTRAPTABLE_PATH; ?>/extensions/filter-control/bootstrap-table-filter-control.min.js"></script>

		<link rel="stylesheet" href="<? echo BOOTSTRAPTABLE_PATH; ?>/extensions/filter-control/bootstrap-table-filter-control.min.css">
		<script src="funktionen/bootstrap-table-settings.js"></script>

		<script language="javascript" type="text/javascript">
			$('#gui-table').css('width', '100%');
			$('#container_paint').css('height', 'auto');
			resizeBootstrapTable = function() {
				//console.log('browserwidth: ' + document.body.width);
				$('.bootstrap-table').css('width', (document.body.offsetWidth - 235) + 'px');
			};
			window.addEventListener('resize', resizeBootstrapTable);

			$(function () {
				result = $('#eventsResult');
				result.success = function(text) {
					message([{ type: 'notice', msg: text}], 1000, 500);
					result.text(text);
					result.removeClass('alert-danger');
					result.addClass('alert-success');
				};
				result.error = function(text) {
					message([{ type: 'error', msg: text}]);
					result.text(text);
					result.removeClass('alert-success');
					result.addClass('alert-danger');
				};

				// event handler
				$('#data_table')
					.one('load-success.bs.table', function (e, data) {
						resizeBootstrapTable();
						result.success('Tabelle erfolgreich geladen');
						$('.sachdatenanzeige_footer').hide();
						registerEventHandler();
					})
					.on('load-error.bs.table', function (e, status) {
						console.log('loaderror');
						result.error('Event: load-error.bs.table');
					})
					.bootstrapTable({
						fixedHeader: true, // Aktiviert den festen Tabellenkopf
						height: 300,       // Maximale Höhe der Tabelle (in Pixeln)
						pagination: true,  // Aktiviert die Paginierung
						filterControl: true, // Aktiviert den Filter
						showSearch: true,  // Zeigt die Suchleiste an
						pageSize: 10       // Anzahl der Einträge pro Seite
					});
			});

			function jaNeinFormatter(value, row) {
				return (value == 't' ? 'Ja' : 'Nein');
			}

			function boolTypeFormatter(value, row) {
				//console.log(value);
				return (value == 't' ? 'ja' : 'nein');
			}

			function editFormatter(value, row) {
				var output = '<a href="index.php?go=Layer-Suche_Suchen&selected_layer_id=<? echo $layerset['layer_id']; ?>&value_<? echo $layerset['oid']; ?>=' + row.<? echo $layerset['oid']; ?> + '&operator_<? echo $layerset['oid']; ?>==&csrf_token=<? echo $_SESSION['csrf_token']; ?>" target="editor" title="<? echo $strEditDataset; ?>"><i class="btn-link fa fa-lg fa-pencil"></i></a>';
				return output;
			}

			function openLinkFormatter(value, row) {
				var output = `<a href="index.php?go=Layer-Suche_Suchen&selected_layer_id=<? echo $layerset['layer_id']; ?>&value_<? echo $layerset['oid']; ?>=${value}&operator_<? echo $layerset['oid']; ?>==&csrf_token=<? echo $_SESSION['csrf_token']; ?>" target="editor" title="Datensatz ID: ${value} öffnen">${value}</a>`;
				return output;
			}
		</script>

		<style>
			#message_box {
				margin-top: 10px;
			}

			.columns columns-right btn-group {
				background-color: #ffffff;
			}

			/* .btn-secondary:hover {
				background-color: #cccccc;
			}

			.btn-secondary.dropdown-toggle::after {
					color: gray !important;
			} */

			/* Primärer Button */
			.btn-primary {
					background-color: white !important;
					border-color: #2980b9 !important;
			}

			.btn-primary:hover,
			.btn-primary:active,
			.btn-primary:focus {
					background-color: white !important;
					border-color: #1a5276 !important;
			}

			/* Sekundärer Button */
			.btn-secondary {
					background-color: white !important;
					border-color: #7f8c8d !important;
			}

			.btn-secondary:hover,
			.btn-secondary:active,
			.btn-secondary:focus {
					background-color: white !important;
					border-color: #5e6c70 !important;
			}

			.btn-secondary.dropdown-toggle::after {
					color: gray !important;
			}

			/* Erfolg-Button */
			.btn-success {
					background-color: #2ecc71 !important;
					border-color: #27ae60 !important;
			}

			.btn-success:hover,
			.btn-success:active,
			.btn-success:focus {
					background-color: #27ae60 !important;
					border-color: #1e8449 !important;
			}

			/* Warn-Button */
			.btn-warning {
					background-color: #f39c12 !important;
					border-color: #d35400 !important;
			}

			.btn-warning:hover,
			.btn-warning:active,
			.btn-warning:focus {
					background-color: #d35400 !important;
					border-color: #a04000 !important;
			}

			/* Gefahr-Button */
			.btn-danger {
					background-color: #e74c3c !important;
					border-color: #c0392b !important;
			}

			.btn-danger:hover,
			.btn-danger:active,
			.btn-danger:focus {
					background-color: #c0392b !important;
					border-color: #992d22 !important;
			}

			/* Info-Button */
			.btn-info {
					background-color: #1abc9c !important;
					border-color: #16a085 !important;
			}

			.btn-info:hover,
			.btn-info:active,
			.btn-info:focus {
					background-color: #16a085 !important;
					border-color: #0e6251 !important;
			}

			/* Helle & dunkle Buttons */
			.btn-light {
					background-color: white !important;
					border-color: #bdc3c7 !important;
					color: #2c3e50 !important;
			}

			.btn-light:hover,
			.btn-light:active,
			.btn-light:focus {
					background-color: white !important;
					border-color: #95a5a6 !important;
					color: #2c3e50 !important;
			}

			.btn-dark {
					background-color: white !important;
					border-color: #2c3e50 !important;
			}

			.btn-dark:hover,
			.btn-dark:active,
			.btn-dark:focus {
					background-color: white !important;
					border-color: #1a252f !important;
			}

			.collapsedfull {
				display: none;
			}

			/* Container für die Tabelle */
			.table-wrapper {
				max-height:100%; /* Maximale Höhe des Containers (anpassen!) */
				overflow: hidden; /* Verhindert horizontales Scrollen */
				border: 1px solid #dee2e6; /* Optional: Rahmen für bessere Sichtbarkeit */
			}

			/* Tabellenkopf bleibt fest positioniert */
			.table-fixed-header thead {
				position: sticky;
				top: 0;
				background-color: white;
				z-index: 10;
			}

			/* Tabellenkörper scrollt vertikal */
			.table-fixed-header tbody {
				display: block;
				max-height: calc(500px - 40px); /* Höhe des Containers minus Höhe des Headers */
				overflow-y: auto;
			}

			/* Optional: Spaltenbreite anpassen */
			.table-fixed-header th,
			.table-fixed-header td {
				min-width: 100px; /* Beispielwert */
			}
			.page-size {
				color: gray;
			}

		</style>
		<!--div class="alert alert-success" style="white-space: pre-wrap" id="eventsResult">
				Here is the result of event.
		</div//-->
		<h2><? echo $layerset['Name_or_alias']; ?></h2>
		<div class="table-wrapper">
			<table
				id="data_table"
				data-unique-id="<? echo $layerset['oid']; ?>"
				data-toggle="table"
				data-url="index.php"
				data-height="900"
				data-click-to-select="true"
				data-filter-control="true"
				data-sort-name="<? echo $sort_attribute['attributename']; ?>"
				data-sort-order="<? echo $sort_attribute['sort_direction']; ?>"
				data-search="true"
				data-show-export="false"
				data-export_types=['json', 'xml', 'csv', 'txt', 'sql', 'excel']
				data-show-refresh="false"
				data-show-toggle="true"
				data-show-columns="true"
				data-query-params="go=Layer-Suche_Suchen&selected_layer_id=<? echo $layerset['layer_id']; ?>&anzahl=10000&mime_type=formatter&format=json"
				data-pagination="true"
				data-page-list=[25,100,250,500,1000,all]
				data-page-size="100"
				data-toolbar="#toolbar"
				data-fixed-header="true"
				data-toggle="table"
			>
				<thead>
					<tr><?
						foreach ($layerset['attributes']['name'] AS $key => $name) {
							if ($layerset['attributes']['visible'][$key]) { ?>
								<th
										data-field="<? echo $name; ?>"
										data-sortable="true"
										data-switchable="true"
										data-visible="<? echo ((
											(
												!array_key_exists($name, $rolle_attribute_settings) AND
												$layerset['attributes']['raster_visibility'][$key] == 1
											) OR
											$rolle_attribute_settings[$name]['switched_on'] == 1
										) ? 'true' : 'false'); ?>"
										data-filter-control="<? echo ($layerset['attributes']['type'][$key] === 'bool' ? 'select' : 'input'); ?>"<?
										if ($layerset['attributes']['type'][$key] === 'bool') { ?>
											data-formatter="boolTypeFormatter"<?
										}
										if ($layerset['oid'] == $layerset['attributes']['name'][$key]) { ?>
											data-formatter="openLinkFormatter"<?
										} ?>
									><? echo $layerset['attributes']['alias'][$key]; ?>
								</th><?
							}
						} ?>
				</thead>
			</table>
		</div><?
	}
?>