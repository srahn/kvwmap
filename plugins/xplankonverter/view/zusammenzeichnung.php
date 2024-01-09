<?
	$xplan_version = '5.4';
/* Beispiel für einen Aufruf
	https://testportal-plandigital.de/kvwmap/index.php?go=xplankonverter_zusammenzeichnung&planart=FP-Plan
	Process steps xplankonverter
		_upload_zusammenzeichnung
		_validate_zusammenzeichnung
		_import_zusammenzeichnung
		_convert_zusammenzeichnung
		_gml_generieren
		_create_geoweb_service
		_create_metadata
		_replace_zusammenzeichnung
	*/
?>
	<link rel="stylesheet" href="plugins/xplankonverter/styles/styles.css">
	<script src="plugins/xplankonverter/model/Zusammenzeichnung.js"></script>
<?
	if ($this->plan_title === 'Plan') { ?>
		<br>
		<h2>Zusammenzeichnungen</h2>
		<br>
		<br>
		Sie müssen im Parameter planart eine gültige Planart angeben.
		<br>
		<br>
		Mögliche Werte sind:
		<ul style="color: black">
			<li>BP-Plan</li>
			<li>FP-Plan</li>
			<li>SO-Plan</li>
			<li>RP-Plan</li>
		</ul><?
	}
	else {
		$zusammenzeichnung_exists = false;
		$layers_with_content = array();

		if (count($this->zusammenzeichnungen['published']) > 0) {
			$zusammenzeichnung = $this->zusammenzeichnungen['published'][0];
			$zusammenzeichnung_exists = true;
		}
		else if (count($this->zusammenzeichnungen['draft']) > 0) {
			$zusammenzeichnung = $this->zusammenzeichnungen['draft'][0];
			$zusammenzeichnung_exists = true;
		}
		if ($zusammenzeichnung_exists) {
			if ($zusammenzeichnung->plan === false) {
				$this->add_message('error', 'Die Zusammenzeichnung ' . $zusammenzeichnung->get('id') . ' hat keinen zugeordneten Plan!');
				$plandaten = array(
					'name' => $zusammenzeichnung->get('bezeichnung'),
					'aktualitaet' => 'Die gefundene Zusammenzeichnung hat keinen zugeordneten Plan',
					'gml_id' => '',
					'first_ags' => 'keine Angaben',
					'first_gemeinde_name' => 'keine Angaben',
					'nummer' => '',
					'rechtsstand' => '0'
				);
			}
			else {
				$zusammenzeichnung->plan->get_center_coord();
				$plandaten = array(
					'name' => $zusammenzeichnung->plan->get('name'),
					'aktualitaet' => $zusammenzeichnung->get_aktualitaetsdatum(),
					'gml_id' => $zusammenzeichnung->plan->get('gml_id'),
					'first_ags' => $zusammenzeichnung->plan->get_first_ags(),
					'first_gemeinde_name' => $zusammenzeichnung->plan->get_first_gemeinde_name(),
					'nummer' => $zusammenzeichnung->plan->get('nummer'),
					'rechtsstand' => $zusammenzeichnung->plan->get('rechtsstand')
				);

				$result = $zusammenzeichnung->plan->get_layers_with_content(
						$this->xplankonverter_get_xplan_layers($zusammenzeichnung->get('planart')),
						$zusammenzeichnung->get($zusammenzeichnung->identifier)
				);
				if (! $result['success']) {
					$this->add_message('error', $result['msg']);
				}
				$layers_with_content = $result['layers_with_content'];
			}
		}

		function get_rechtsstand($rechtsstand) {
			global $GUI;
			$sql = "
				SELECT
					beschreibung
				FROM
					xplan_gml.enum_fp_rechtsstand
				WHERE
					wert = " . $rechtsstand . "
			";
			$ret = $GUI->pgdatabase->execSQL($sql,4, 1);
			if ($ret['success'] AND pg_num_rows($ret[1]) > 0) {
				$rs = pg_fetch_assoc($ret[1]);
				return $rs['beschreibung'];
			}
			return '';
		} ?>
		<script>
			let zz = new Zusammenzeichnung(
				<? echo ($zusammenzeichnung_exists ? $zusammenzeichnung->get_id() : 0); ?>,
				'<? echo $this->formvars['planart']; ?>',
				'<? echo $_SESSION['csrf_token']; ?>'
			);

			function show_upload_zusammenzeichnung(msg) {
				if (confirm('Prüfen Sie ob Ihre Dienstmetadaten auf dem aktuellen Stand sind. Wählen Sie "Abbrechen" und Sie werden zu den Dienstmetadaten weitergeleitet.')) {
					$('#zusammenzeichnung, #keine_zusammenzeichnung').hide();
					$('#upload_zusammenzeichnung_msg').html(msg);
					$('#neue_zusammenzeichnung').show();
				}
				else {
					location.href = 'index.php?go=Dienstmetadaten&csrf_token=<? echo $_SESSION['csrf_token']; ?>';
				}
			}

			function show_class_completenesses(konvertierung_id) {
				let url = `index.php?go=xplankonverter_show_class_completenesses&konvertierung_id=${konvertierung_id}&csrf_token=<? echo $_SESSION['csrf_token']; ?>`;
				$('#sperr_div, #waitingdiv').show();
				fetch(url)
					.then(response => response.text())
					.then((data) => {
						let e = document.createElement('div');
						e.innerHTML = data;
						document.getElementById('class_completeness_div').prepend(e);
						$('#sperr_div, #waitingdiv').hide();
					})
					.catch((error) => {
						message('error', 'Fehler bei der Abfrage der Vollständigkeit der Styles für die Klassen der Layer!');
						$('#sperr_div, #waitingdiv').hide();
					});
			}
		</script>
		<h2 style="margin-top: 15px; margin-bottom: 10px">Zusammenzeichnung <?php echo $this->plan_title; ?> <? echo $this->Stelle->Bezeichnung; ?></h2>
		<style>
			#container_paint {
				min-height: 500px;
			}

			#upload_zusammenzeichnung_div {
				border-radius: 5px;
				width: 75%;
				margin: 0 auto;
				padding-top: 150px;
				padding-bottom: 150px;
				outline: 2px dashed steelblue;
				outline-offset: -6px;
				background:	linear-gradient(#adc7da 0%, #DAE4EC 50%, #adc7da 100%);
				opacity: 0.7;
				transition: all 0.2s ease;
			}

			.dragover {
				background:	linear-gradient(#b3daad 0%, #DBECDA 50%, #addab2 100%) !important
				/* linear-gradient(#b3daad 0%, #DBECDA 50%, #addab2 100%) Rot*/
				/* linear-gradient(#addab0 0%, #DBECDA 50%, #b1daad 100%) */
			}

			.blink {
				animation: blinker 1.5s linear infinite;
			}

			@keyframes blinker {
				50% {
					opacity: 0;
				}
			}

			.centered_div {
				overflow: hidden;
				text-align: center;
			}

			.head_div {
				text-align: left;
				font-weight: bold;
				font-size: 15px;
				padding: 2px;
				background: linear-gradient(#DAE4EC 0%, #c7d9e6 100%);
				border: 1px solid gray;
				margin-left: 1%;
				margin-top: 5px;
				margin-right: 1%;
			}

			.head_div:hover {
				background: linear-gradient(#DAE4EC 0%, #adc7da 100%);
			}


			.class-th {
				text-align: center;
				font-weight: bold;
				font-size: 15px;
				padding: 2px;
				background: linear-gradient(#DAE4EC 0%, #c7d9e6 100%);
			}

			.class-th:hover {
				background: linear-gradient(#DAE4EC 0%, #adc7da 100%);
			}

			.class-td {
				background: #c6cde1;
				padding: 2px;
			}

			.margin-right {
        margin-right: 30px
      }

			.content_div {
				text-align: left;
				padding: 2px;
				background:  rgb(237, 239, 239);
				border-left: 1px solid gray;
				border-right: 1px solid gray;
				border-bottom: 1px solid gray;
				margin-left: 1%;
				margin-right: 1%;
        padding-bottom: 8px;
			}
	
			.head_icon {
				margin-left: 5px;
				margin-right: 5px;
				color: #236dbf;
				font-size: 15px;
				width: 11px;
			}

			#steps_div {
				display: none;
				padding: 5 5 15 85;
			}

			.step {
				margin-top: 10px;
			}

			.between_step {
				float: left;
				margin-right: 15px;
				margin-top: 10px;
			}

			.step_in_progress {
				background: #ffb4b4;
			}

			.step_ready {
				background: #addab2;
			}

			.clear {
				clear: both;
			}

			.zusammenzeichnung-list-div {
				margin-top: 2px;
				margin-left: 5px;
			}
		</style><?
	
		if (! $zusammenzeichnung_exists) { ?>
			<div id="keine_zusammenzeichnung" class="centered_div">
				In dieser Stelle gibt es noch keine veröffentlichte Zusammenzeichnung vom <? echo $this->plan_title; ?>.<p><?
        if (count($this->zusammenzeichnungen['faulty']) > 0) { ?>
          <div id="faulty_head" class="head_div" onclick="toggle_head(this)">
            <i class="fa fa-caret-down head_icon" aria-hidden="true"></i>Fehlgeschlagene Upload-Versuche
          </div>
          <div id="faulty_div" class="content_div"><?
            foreach ($this->zusammenzeichnungen['faulty'] AS $konvertierung) {
              if ($konvertierung->plan) {
                $list_url = "index.php?go=Layer-Suche_Suchen&selected_layer_id=" . XPLANKONVERTER_FP_PLAENE_LAYER_ID . "&value_plan_gml_id=" . $konvertierung->plan->get('gml_id') . "&operator_plan_gml_id==&csrf_token=" . $_SESSION['csrf_token'];
                $list_text = "{$konvertierung->get('bezeichnung')} Stand: {$konvertierung->get_aktualitaetsdatum()} Versuch vom: " . date_format(date_create($konvertierung->get('created_at')), 'd.m.Y H:i');
              }
              else {
                $list_url = "index.php?go=Layer-Suche_Suchen&selected_layer_id=" . XPLANKONVERTER_KONVERTIERUNGEN_LAYER_ID . "&value_konvertierung_id=" . $konvertierung->get_id() . "&operator_konvertierung_id==&csrf_token=" . $_SESSION['csrf_token'];
                $list_text = "Konvertierung: {$konvertierung->get_id()} Upload vom: " . date_format(date_create($konvertierung->get('created_at')), 'd.m.Y H:i');
              } ?>
              <div id="failed_zusammenzeichnung_<? echo $konvertierung->get_id(); ?>" class="zusammenzeichnung-list-div">
                <a href="<? echo urlencode2($list_url); ?>" class="margin-right"><i class="fa fa-list-alt" aria-hidden="true"></i> <? echo $list_text; ?></a>
                <a href="#" onclick="delete_zusammenzeichnung('<? echo $konvertierung->get_id(); ?>')" class="margin-right"><i class="fa fa-lg fa-trash" style="float: right; margin-right: 5px"></i></a>
              </div><?
            } ?>
          </div><?
        } ?>
        <input id="upload_zusammenzeichnung_button" type="button" value="Zusammenzeichnung hochladen" onclick="show_upload_zusammenzeichnung('Zusammenzeichnung hier reinziehen.')" style="margin-top: 20px">
			</div><?
		} ?>

		<div id="neue_zusammenzeichnung" class="centered_div hidden">
			<div
				id="upload_zusammenzeichnung_div"
				ondrop="zz.upload_zusammenzeichnung(event)"
				ondragover="$(this).addClass('dragover');"
				ondragleave="$(this).removeClass('dragover')"
			>
				<span id="upload_zusammenzeichnung_msg"></span>
			</div><p><?
			if ($this->user->id == 3) { ?>
				<input id="suppress_ticket_and_notification" type="checkbox" name="suppress_ticket_and_notification" value="1"> im Fehlerfall kein Ticket anlegen und keine Benachrichtigung senden<p><?
			} ?>
			<p style="margin-bottom: 8px;">Die hoch zu ladenden Daten müssen folgende Eigenschaften aufweisen:</p>
			<div style="
				text-align: left;
				margin-left: 157px;
				width: 75%;
				margin-bottom: 20px;
			">
				<ul style="
					color: black;
					margin: 0px;
					padding: 0px;
					list-style: circle;
				">
					<li>Die Daten müssen in einem ZIP-Archiv abgelegt sein.</li>
					<li>Die GML-Datei im ZIP-Archiv muss die Dateibezeichnung "Zusammenzeichnung.gml" aufweisen.</li>
					<li>Es kann eine GML-Datei mit Geltungsbereichen von Änderungsplänen enthalten sein. Sie muss "Geltungsbereiche.gml" heißen.</li>
					<li>Die Zusammenzeichnung eines FNP muss ein Datum im Attribut wirksamkeitsdatum oder aenderungenbisdatum beinhalten.</li>
				</ul>
			</div>
			<input id="cancel_zusammenzeichnung_button" type="button" value="Hochladen abbrechen" onclick="cancel_upload_zusammenzeichnung()" style="margin-bottom: 20px">
			<div id="upload_result_msg_div" class="hidden"></div>
		</div><?

		if ($zusammenzeichnung_exists) { ?>
			<div id="zusammenzeichnung" class="centered_div">
				Stand: <? echo $plandaten['aktualitaet']; ?>
				<? if ($zusammenzeichnung->art == 'draft') {
					?> <span class="red">Noch keine Dienste veröffentlicht!</a> <!--a href="#">jetzt veröffentlichen</a//--><?
				} ?>
				<div id="plandaten_head" class="head_div" onclick="toggle_head(this)">
					<i class="fa fa-caret-right head_icon" aria-hidden="true"></i>Plandaten
				</div>
				<div id="plandaten_div" class="content_div">
					<table>
						<tr>
							<td>Name:</td><td><? echo $plandaten['name']; ?><td>
						</tr>
						<tr>
							<td>Nummer:</td><td><? echo $plandaten['nummer']; ?><td>
						</tr>
						<tr>
							<td>GML-ID:</td><td><? echo $plandaten['gml_id']; ?><td>
						</tr>
						<tr>
							<td>Gemeinde:</td><td><? echo $plandaten['first_gemeinde_name']; ?> (<? echo $plandaten['first_gemeinde_ags']; ?>)<td>
						</tr>
						<tr>
							<td>Rechtsstand:</td><td><? echo get_rechtsstand($plandaten['rechtsstand']); ?> (<? echo $plandaten['rechtsstand']; ?>)<td>
						</tr>
						<tr>
							<td>Datum der Wirksamkeit<br>der letzten Änderung:</td><td><? echo $plandaten['aktualitaet']; ?><td>
						</tr>
						<tr>
							<td>Konvertierung ID:</td><td><? echo $zusammenzeichnung->get_id(); ?><td>
						</tr>
						<tr>
							<td align="center"><!--img src="<? #querymap oder Kartenauszug ?>"//--></td>
							<td><?
								if ($zusammenzeichnung->plan != false) { ?>
									<a title="Details zum Plan im Sachdatenformular anzeigen." href="index.php?go=Layer-Suche_Suchen&selected_layer_id=<? echo XPLANKONVERTER_FP_PLAENE_LAYER_ID; ?>&operator_plan_gml_id==&value_plan_gml_id=<? echo $zusammenzeichnung->plan->get('gml_id'); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i class="fa fa-list-alt" aria-hidden="true"></i> Plandetails anzeigen</a><br>
									<a title="Zusammenzeichnung in der Karte anzeigen." href="index.php?go=zoomto_dataset&oid=<? echo $zusammenzeichnung->plan->get('gml_id'); ?>&layer_columnname=raeumlichergeltungsbereich&layer_id=<? echo XPLANKONVERTER_FP_PLAENE_LAYER_ID; ?>&selektieren=0"><i class="fa fa-map" aria-hidden="true"></i> In Karte anzeigen</a><br>
									<a title="Plan im UVP-Portal anzeigen." target="uvp" href="https://uvp.niedersachsen.de/kartendienste?layer=blp&N=<? echo $zusammenzeichnung->plan->center_coord['lat']; ?>&E=<? echo $zusammenzeichnung->plan->center_coord['lon']; ?>&zoom=13"><i class="fa fa-globe" aria-hidden="true"></i> Im UVP-Portal Anzeigen</a><?
								} ?>
							</td>
						</tr>
					</table>
				</div>
				<div id="dokumente_head" class="head_div" onclick="toggle_head(this)">
					<i class="fa fa-caret-down head_icon" aria-hidden="true"></i>Dokumente
				</div>
				<div id="dokumente_div" class="content_div hidden">
					<table>
						<tr>
							<td>Hochgeladene XPlanGML-Datei:</td><td><a href="index.php?go=xplankonverter_download_uploaded_xplan_gml&page=zusammenzeichnung&planart=<?php echo $zusammenzeichnung->get('planart'); ?>&konvertierung_id=<? echo $zusammenzeichnung->get_id(); ?>"><i class="fa fa-file-archive-o" aria-hidden="true"></i> Download</a><td>
						</tr>
						<tr>
							<td>XPlan-Validator Bericht der Leitstelle:</td><td><a href="index.php?go=Layer-Suche_Suchen&selected_layer_id=<? echo XPLANKONVERTER_XPLANVALIDATOR_REPORT_LAYER_ID; ?>&operator_konvertierung_id==&value_konvertierung_id=<? echo $zusammenzeichnung->get_id(); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i class="fa fa-list-alt" aria-hidden="true"></i> Anzeigen</a><td>
						</tr>
						<tr>
							<td>XPlan-Validators semantischer Bericht der Leitstelle:</td><td><a href="index.php?go=xplankonverter_xplankonverter_report&page=zusammenzeichnung&planart=<?php echo $zusammenzeichnung->get('planart'); ?>&konvertierung_id=<? echo $zusammenzeichnung->get_id(); ?>"><i class="fa fa-list-alt" aria-hidden="true"></i> Anzeigen</a><td>
						</tr>
						<tr>
							<td>Ergebnisse der internen Konvertierung:</td><td><a href="index.php?go=xplankonverter_validierungsergebnisse&page=zusammenzeichnung&planart=<?php echo $zusammenzeichnung->get('planart'); ?>&konvertierung_id=<? echo $zusammenzeichnung->get_id(); ?>"><i class="fa fa-list-alt" aria-hidden="true"></i> Anzeigen</a><td>
						</tr>
						<tr>
							<td>Erzeugte XPlanGML-Datei in Version <?php echo $xplan_version; ?>:</td><td><a href="index.php?go=xplankonverter_download_xplan_gml&page=zusammenzeichnung&planart=<?php echo $zusammenzeichnung->get('planart'); ?>&konvertierung_id=<? echo $zusammenzeichnung->get_id(); ?>"><i class="fa fa-file-code-o" aria-hidden="true"></i> Download</a><td>
						</tr>
					</table>
				</div>
				<div id="dienst_head" class="head_div" onclick="toggle_head(this)">
					<i class="fa fa-caret-down head_icon" aria-hidden="true"></i>Dienst
				</div>
				<div id="dienst_div" class="content_div hidden">
					<table style="width: 100%">
						<tr>
							<td style="border-right: 0px solid gray">Metadaten über den Geodatensatz:</td>
							<td style="border-right: 0px solid gray"><?
								if ($zusammenzeichnung->get('metadata_dataset_uuid') == '') { ?>
									<a title="Metadaten über Geodatensatz anlegen" target="metadata" href="index.php?go=xplankonverter_create_metadata&planart=<?php echo $zusammenzeichnung->get('planart'); ?>&konvertierung_id=<? echo $zusammenzeichnung->get_id(); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>">Jetzt Anlegen</a><?
								}
								else { ?>
									<a title="Metadaten über Geodatensatz runterladen" target="metadata" href="https://mis.testportal-plandigital.de/geonetwork/srv/api/records/<? echo $zusammenzeichnung->get('metadata_dataset_uuid'); ?>/formatters/xml"><i class="fa fa-file-code-o" aria-hidden="true"></i> XML-Datei</a><?
								} ?>
							</td>
							<td rowspan="3" style="border-bottom: solid 1px gray;">
								<a href="index.php?go=xplankonverter_create_metadata&planart=<?php echo $zusammenzeichnung->get('planart'); ?>&konvertierung_id=<? echo $zusammenzeichnung->get_id(); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>" title="Metadaten aktualisieren" target="metadata">Metadaten aktualisieren</a>
							</td>
						</tr>
						<tr>
							<td style="border-right: 0px solid gray">Metadaten über den Darstellungsdienst (WMS):</td>
							<td style="border-right: 0px solid gray"><?
								if ($zusammenzeichnung->get('metadata_viewservice_uuid') == '') { ?>
									<a title="Metadaten über Geodatensatz anlegen" target="metadata" href="index.php?go=xplankonverter_create_metadata&planart=<?php echo $zusammenzeichnung->get('planart'); ?>&konvertierung_id=<? echo $zusammenzeichnung->get_id(); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>">Jetzt Anlegen</a><?
								}
								else { ?>
									<a title="Metadaten über Darstellungsdienst runterladen" target="metadata" href="https://mis.testportal-plandigital.de/geonetwork/srv/api/records/<? echo $zusammenzeichnung->get('metadata_viewservice_uuid'); ?>/formatters/xml"><i class="fa fa-file-code-o" aria-hidden="true"></i> XML-Datei</a><?
								} ?>
							</td>
						</tr>
						<tr>
							<td style="border-bottom: solid 1px gray; border-right: 0px solid gray">Metadaten über Downloaddienst (WFS):</td>
							<td style="border-bottom: solid 1px gray; border-right: 0px solid gray"><?
								if ($zusammenzeichnung->get('metadata_downloadservice_uuid') == '') { ?>
									<a title="Metadaten über Geodatensatz anlegen" target="metadata" href="index.php?go=xplankonverter_create_metadata&planart=<?php echo $zusammenzeichnung->get('planart'); ?>&konvertierung_id=<? echo $zusammenzeichnung->get_id(); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>">Jetzt Anlegen</a><?
								}
								else { ?>
									<a title="Metadaten über Downloaddienst runterladen" target="metadata"href="https://mis.testportal-plandigital.de/geonetwork/srv/api/records/<? echo $zusammenzeichnung->get('metadata_downloadservice_uuid'); ?>/formatters/xml"><i class="fa fa-file-code-o" aria-hidden="true"></i> XML-Datei</a><?
								} ?>
							</td>
						</tr>
						<tr>
							<td>Capabilities zum WMS:</td>
							<td><?php
								$capabilities_url = URL . 'ows/' . $this->Stelle->id . '/' . $this->plan_abk . '?Service=WMS&Request=GetCapabilities';
								if (get_headers($capabilities_url, 1)[0] == 'HTTP/1.1 404 Not Found') { ?>
									<a title="Erzeuge GeoWeb-Dienst" target="metadata" href="index.php?go=xplankonverter_create_geoweb_service&planart=<?php echo $zusammenzeichnung->get('planart'); ?>&konvertierung_id=<? echo $zusammenzeichnung->get_id(); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>">Jetzt anlegen</a><?
								}
								else { ?>
									<a title="Capabilities zum WMS runterladen" target="metadata" href="<?php echo $capabilities_url; ?>"><i class="fa fa-file-code-o" aria-hidden="true"></i> XML-Datei</a><?
								} ?>
							</td>
							<td rowspan="2">
								&nbsp;
							</td>
						</tr>
						<tr>
							<td>Capabilities zum WFS:</td>
							<td><?php
								$capabilities_url = URL . 'ows/' . $this->Stelle->id . '/' . $this->plan_abk . '?Service=WFS&Request=GetCapabilities';
								if (get_headers($capabilities_url, 1)[0] == 'HTTP/1.1 404 Not Found') { ?>
									<a title="Erzeuge GeoWeb-Dienst" target="metadata" href="index.php?go=xplankonverter_create_geoweb_service&planart=<?php echo $zusammenzeichnung->get('planart'); ?>&konvertierung_id=<? echo $zusammenzeichnung->get_id(); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>">Jetzt anlegen</a><?
								}
								else { ?>
									<a title="Capabilities zum WFS runterladen" target="metadata" href="<? echo $capabilities_url; ?>"><i class="fa fa-file-code-o" aria-hidden="true"></i> XML-Datei</a><?
								} ?>
							</td>
						</tr>
					</table>
				</div>
				<div id="class_completeness_head" class="head_div" onclick="toggle_head(this)">
					<i class="fa fa-caret-down head_icon" aria-hidden="true"></i>Planzeichen (Objektklassen)
				</div>
				<div id="class_completeness_div" class="content_div hidden" style="text-align: center; padding: 20px">
					<p>
					<input type="button" name="load_class_completeness" value="Lade Objektklassen" onclick="show_class_completenesses(<? echo $zusammenzeichnung->get_id(); ?>)"/>
					<?php
/*
					$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
					foreach($layers_with_content AS $layer) {
						$class_completeness_result .= '<a target="_blank" href="#" onclick="$(\'#class_expressions_layer_' . $layer['id'] . '\').toggle();event.preventDefault();">Layer: ' . layer_name_with_alias($layer['Name'], $layer['alias'], array('alias_first' => true, 'brace_type' => 'round')) . '</a><br>';
						$this->formvars['layer_id'] = $layer['id'];
						$classes = $mapDB->read_Classes($layer['id']);
						$class_completeness_result .= '<table id="class_expressions_layer_' . $layer['id'] . '" style="display: none">
							<th class="class-th" style="width: 30%">Klasse</th>
							<th class="class-th" style="width: 70%">Definition</th>' . implode(
								'',
								array_map(
									function($class) {
										return '
											<tr>
												<td class="class-td">' . $class['Name'] . '</td>
												<td class="class-td">' . $class['Expression'] . '</td>
											</tr>
										';
									},
									$classes
								)
							) . '
						</table>';
						$result = $this->check_class_completeness();
						$class_completeness_result .= $result['html'];
					}
					echo $class_completeness_result;
					*/ ?>
				</div><?
				if (count($this->zusammenzeichnungen['archived']) > 0) { ?>
					<div id="alte_staende_head" class="head_div" onclick="toggle_head(this)">
						<i class="fa fa-caret-down head_icon" aria-hidden="true"></i>Ältere Versionen
					</div>
					<div id="alte_staende_div" class="content_div hidden"><?
						foreach ($this->zusammenzeichnungen['archived'] AS $archivdatei) { ?>
							<div class="zusammenzeichnung-list-div"><a href="index.php?go=xplankonverter_download_alte_zusammenzeichnung&datei=<? echo basename($archivdatei); ?>&page=zusammenzeichnung&planart=<? echo $this->formvars['planart']; ?>"><i class="fa fa-file-archive-o" aria-hidden="true"></i> <? echo basename($archivdatei); ?></a></div><?
						} ?>
					</div><?
				}
				if (count($this->zusammenzeichnungen['faulty']) > 0) { ?>
					<div id="faulty_head" class="head_div" onclick="toggle_head(this)">
						<i class="fa fa-caret-down head_icon" aria-hidden="true"></i>Fehlgeschlagene Upload-Versuche
					</div>
					<div id="faulty_div" class="content_div hidden"><?
						foreach ($this->zusammenzeichnungen['faulty'] AS $konvertierung) {
							if ($konvertierung->plan) {
								$list_url = "index.php?go=Layer-Suche_Suchen&selected_layer_id=" . XPLANKONVERTER_FP_PLAENE_LAYER_ID . "&value_plan_gml_id=" . $konvertierung->plan->get('gml_id') . "&operator_plan_gml_id==&csrf_token=" . $_SESSION['csrf_token'];
								$list_text = "{$konvertierung->get('bezeichnung')} Stand: {$konvertierung->plan->get('wirksamkeitsdatum')} Versuch vom: " . date_format(date_create($konvertierung->get('created_at')), 'd.m.Y H:i');
							}
							else {
								$list_url = "index.php?go=Layer-Suche_Suchen&selected_layer_id=" . XPLANKONVERTER_KONVERTIERUNGEN_LAYER_ID . "&value_konvertierung_id=" . $konvertierung->get_id() . "&operator_konvertierung_id==&csrf_token=" . $_SESSION['csrf_token'];
								$list_text = "Konvertierung: {$konvertierung->get_id()} Upload vom: " . date_format(date_create($konvertierung->get('created_at')), 'd.m.Y H:i');
							} ?>
							<div id="failed_zusammenzeichnung_<? echo $konvertierung->get_id(); ?>" class="zusammenzeichnung-list-div">
								<a href="<? echo urlencode2($list_url); ?>"><i class="fa fa-list-alt" aria-hidden="true"></i> <? echo $list_text; ?></a>
								<a href="#" onclick="delete_zusammenzeichnung('<? echo $konvertierung->get_id(); ?>')"><i class="fa fa-lg fa-trash" style="float: right; margin-right: 5px"></i></a>
							</div><?
						} ?>
					</div><?
				} ?>
				<p>
				<input type="button" value="Neue Version hochladen" onclick="show_upload_zusammenzeichnung('Neue Version der Zusammenzeichnung hier reinziehen.')">
			</div><?
		} ?>
		<script>
			function toggle_head(head_div) {
				$(head_div).children().toggleClass('fa-caret-down fa-caret-right');
				$(head_div).next().toggle();
			}

			var fileobj;

			window.addEventListener("dragover",function(e){
				e = e || event;
				e.preventDefault();
			},false);
			window.addEventListener("drop",function(e){
				e = e || event;
				e.preventDefault();
			},false);

			/**
			* ToDo wird die Funktion gebaucht?
			*/
			function file_explorer() {
				document.getElementById('selectfile').click();
				document.getElementById('selectfile').onchange = function() {
					fileobj = document.getElementById('selectfile').files[0];
					ajax_file_upload(fileobj);
				};
			}

			function cancel_upload_zusammenzeichnung() {
				$('#neue_zusammenzeichnung').hide();
				$('#sperr_div').hide().html('');
				$('#<? if (!$zusammenzeichnung_exists) { ?>keine_<? } ?>zusammenzeichnung').show();
			}

			function show_new_version() {
				console.log('show_new_version');
				window.location = 'index.php?go=xplankonverter_zusammenzeichnung&planart=FP-Plan&neue_version=1';
			}

			function show_aktuelle_version() {
				console.log('show_aktuelle_version');
				window.location = 'index.php?go=xplankonverter_zusammenzeichnung&planart=FP-Plan';
			}

			function delete_zusammenzeichnung(konvertierung_id) {
				let formData = new FormData();
				formData.append('go', 'Layer_Datensaetze_Loeschen');
				formData.append('mime_type', 'json');
				formData.append('format', 'json_result');
				formData.append('output', 'false');
				formData.append('chosen_layer_id', '<? echo XPLANKONVERTER_KONVERTIERUNGEN_LAYER_ID; ?>');
				formData.append('check;main_table_name;alias_name;' + konvertierung_id, 'on');
				formData.append('checkbox_names_<? echo XPLANKONVERTER_KONVERTIERUNGEN_LAYER_ID; ?>', 'check;main_table_name;alias_name;' + konvertierung_id);
				formData.append('csrf_token', '<? echo $_SESSION['csrf_token']; ?>');
				startwaiting(true);
				let response = fetch('index.php', {
					method: 'POST',
					body: formData
				})
				.then(response => response.text())
				.then(text => {
					stopwaiting(true);
					try {
						const result = JSON.parse(text);
						if (result.success) {
							// remove div for zusammenzeichnung
							$('#failed_zusammenzeichnung_' + konvertierung_id).remove();
							message([{ type: 'notice', msg: 'Fehlgeschlagene Zusammenzeichnung erfolgreich gelöscht.'}]);
						}
						else {
							// zeige Fehler
							message([{ type: 'error', msg: 'Fehler beim Löschen der Zusammenzeichnung: ' + result.msg }]);
						}
					} catch(err) {
						// Fehler beim Parsen
						message([{ type: 'error', msg: 'Fehler beim Parsen des Ergebnisses nach dem Löschen der Zusammenzeichnung: ' + result.msg }]);
					}
				})
			}
		</script><?
	}
?>