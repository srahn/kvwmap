<?
/* Beispiel für einen Aufruf
	https://testportal-plandigital.de/kvwmap/index.php?go=xplankonverter_zusammenzeichnung&planart=FP-Plan&neue_version=
	Process steps xplankonverter
		_upload_zusammenzeichnung
		_validate_zusammenzeichnung
		_import_zusammenzeichnung
		_convert_zusammenzeichnung
		_create_gml_file
		_create_geoweb_service
		_create_service_metadata
	*/
?><link rel="stylesheet" href="plugins/xplankonverter/styles/styles.css">
<script src="plugins/xplankonverter/model/Zusammenzeichnung.js"></script><?
$zusammenzeichnung_id = ($this->zusammenzeichnung_exists ? $this->zusammenzeichnung->get('id') : ''); ?>
<script>
	let zz = new Zusammenzeichnung(<? echo $zusammenzeichnung_id; ?>);
</script>
<h2 style="margin-top: 15px; margin-bottom: 10px">Zusammenzeichnung F-Plan <? echo $this->Stelle->Bezeichnung; ?></h2>
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
		margin-left: 5%;
		margin-top: 5px;
		margin-right: 5%;
	}

	.head_div:hover {
		background: linear-gradient(#DAE4EC 0%, #adc7da 100%);
	}

	.content_div {
		text-align: left;
		padding: 2px;
		background:  rgb(237, 239, 239);
		border-left: 1px solid gray;
		border-right: 1px solid gray;
		border-bottom: 1px solid gray;
		margin-left: 5%;
		margin-right: 5%;
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
</style>
<?php
	#ToDo
	/*
		Klären wie neue Version und Zusammenzeichnung zusammenhängen sollen
		Soll ein Verweis in konvertierung hinterlegt sein welche andere Konvertierung eine neue Version ist?
		Wo soll ein Rückverweis von der neuen Version auf die alte noch gültige Version erfolgen, wenn ja wo?
	*/
?>

	<div id="keine_zusammenzeichnung" class="centered_div<? if ($this->zusammenzeichnung_exists) { ?> hidden<? } ?>">
		In dieser Stelle gibt es noch keine Zusammenzeichnung eines F-Planes.<p>
		<input id="upload_zusammenzeichnung_button" type="button" value="Zusammenzeichnung hochladen" onclick="show_upload_zusammenzeichnung('Zusammenzeichnung hier reinziehen.')">
	</div>

	<!--div id="steps_div">
		<div id="Schritt_1" class="step step_in_progress">Schritt 1<br>Plandaten hochladen</div><div class="between_step">=></div>
		<div id="Schritt_2" class="step">Schritt 2<br>Dienst erzeugen</div><div class="between_step">=></div>
		<div id="Schritt_3" class="step">Schritt 3<br>Dienst veröffentlichen</div>
		<div class="clear"></div>
	</div///-->

	<div id="neue_zusammenzeichnung" class="centered_div hidden">
		<div
			id="upload_zusammenzeichnung_div"
			ondrop="zz.upload_zusammenzeichnung(event, <? echo $zusammenzeichnung_id; ?>)"
			ondragover="$(this).addClass('dragover');"
			ondragleave="$(this).removeClass('dragover')"
		>
			<span id="upload_zusammenzeichnung_msg"></span>
		</div><p>
		<input id="cancel_zusammenzeichnung_button" type="button" value="Hochladen abbrechen" onclick="cancel_upload_zusammenzeichnung()">
		<div id="upload_result_msg_div" class="hidden"></div>
	</div><?

	if ($this->zusammenzeichnung_exists) { ?>
		<div id="zusammenzeichnung" class="centered_div<? if (!$this->zusammenzeichnung_exists) { ?> hidden<? } ?>">
			Stand: <? echo $this->zusammenzeichnung->plan->get('wirksamkeitsdatum'); ?>, Status: noch nicht veröffentlicht
			<div id="plandaten_head" class="head_div" onclick="toggle_head(this)">
				<i class="fa fa-caret-right head_icon" aria-hidden="true"></i>Plandaten
			</div>
			<div id="plandaten_div" class="content_div">
				<table>
					<tr>
						<td>Name:</td><td><? echo $this->zusammenzeichnung->plan->get('name'); ?><td>
					</tr>
					<tr>
						<td>Nummer:</td><td><? echo $this->zusammenzeichnung->plan->get('nummer'); ?><td>
					</tr>
					<tr>
						<td>GML-ID:</td><td><? echo $this->zusammenzeichnung->plan->get('gml_id'); ?><td>
					</tr>
					<tr>
						<td style="vertical-align: top; padding-top: 2px">Gemeinde:</td>
						<td><?
							echo $this->zusammenzeichnung->plan->get('gemeinde');
							/*
							$gemeinden = json_decode($this->zusammenzeichnung->plan->get('gemeinde'));
							foreach ($gemeinden AS $gemeinde) { ?>
								<table>
									<tr>
										<td>Name:</td><td><? echo $gemeinde->gemeindename; ?></td>
									</tr>
									<tr>
										<td>AGS:</td><td><? echo $gemeinde->ags; ?></td>
									</tr>
									<tr>
										<td>RS:</td><td><? echo $gemeinde->rs; ?></td>
									</tr>
								</table><?
							}
							*/ ?>
						<td>
					</tr>
					<tr>
						<td>Rechtsstand:</td><td><? echo get_rechtsstand($this->zusammenzeichnung->plan->get('rechtsstand')); ?> (<? echo $this->zusammenzeichnung->plan->get('rechtsstand'); ?>)<td>
					</tr>
					<tr>
						<td>Datum der Wirksamkeit<br>der letzten Änderung:</td><td><? echo $this->zusammenzeichnung->plan->get('wirksamkeitsdatum'); ?><td>
					</tr>
					<tr>
						<td align="center"><!--img src="<? #querymap oder Kartenauszug ?>"//--></td>
						<td>
							<a href="index.php">In Karte anzeigen</a><br>
							<a target="_blank" href="https://uvp.niedersachsen.de/kartendienste?layer=blp&N=<? echo $this->zusammenzeichnung->plan->center_coord['lat']; ?>&E=<? echo $this->zusammenzeichnung->plan->center_coord['lon']; ?>&zoom=13">Im UVP-Portal Anzeigen</a>
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
						<td>Hochgeladene XPlanGML-Datei:</td><td><? ?><td>
					</tr>
					<tr>
						<td>Ergebnisse des XPlan-Validators der Leitstelle:</td><td><a href="index.php?go=xplankonverter_xplankonverter_report&konvertierung_id=<? echo $zusammenzeichnung_id; ?>">Anzeigen</a><td>
					</tr>
					<tr>
						<td>Ergebnisse der internen Konvertierung:</td><td><a href="index.php?go=xplankonverter_validierungsergebnisse&konvertierung_id=<? echo $zusammenzeichnung_id; ?>">Anzeigen</a><td>
					</tr>
					<tr>
						<td>Erzeugte XPlanGML-Datei:</td><td><a href="index.php?go=xplankonverter_download_xplan_gml&konvertierung_id=<? echo $zusammenzeichnung_id; ?>">Download</a><td>
					</tr>
				</table>
			</div>
			<div id="dienst_head" class="head_div" onclick="toggle_head(this)">
				<i class="fa fa-caret-down head_icon" aria-hidden="true"></i>Dienst
			</div>
			<div id="dienst_div" class="content_div hidden">
				Noch nicht veröffentlicht!
			</div><?
			if (count($this->andere_versionen) > 0) { ?>
				<div id="alte_staende_head" class="head_div" onclick="toggle_head(this)">
					<i class="fa fa-caret-down head_icon" aria-hidden="true"></i>Ältere Versionen
				</div>
				<div id="alte_staende_div" class="content_div hidden">
					<ul>
						<li><a href="zusammenzeichnung_2021-08-03.gml">Zusammenzeichnung Stand 03.08.2021</a></li>
						<li><a href="zusammenzeichnung_2020-10-14.gml">Zusammenzeichnung Stand 14.10.2020</a></li>
					</ul>
				</div><?
			} ?>
			<p><?
			if ($this->zusammenzeichnung_neu_exists) {
				if (array_key_exists('neue_version', $this->formvars) AND $this->formvars['neue_version'] == 1) { ?>
					<input type="button" value="Zur aktuellen Version" onclick="show_aktuelle_version()"><?
				}
				else { ?>
					<input type="button" value="Zur neuen Version" onclick="show_new_version()"><?
				}
			}
			if ($this->zusammenzeichnung_exists AND !$this->zusammenzeichnung_neu_exists) { ?>
				<input type="button" value="Zusammenzeichnung Löschen" onclick="delete_zusammenzeichnung()">
				<input type="button" value="Neue Version hochladen" onclick="show_upload_zusammenzeichnung('Neue Version der Zusammenzeichnung hier reinziehen.')"><?
			} ?>
		</div><?
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
	}

?>
<script>

	const process = {
		'upload_zusammenzeichnung' : {
			'nr' : 1,
			'msg' : 'Hochladen der Zusammenzeichnung auf den Server'
		},
		'validate_zusammenzeichnung' : {
			'nr' : 2,
			'msg' : 'Validierung der hochgeladenen GML-Datei mit dem XPlanValidator'
		},
		'import_zusammenzeichnung' : {
			'nr' : 3,
			'msg' : 'Importieren der GML-Datei in die Portaldatenbank'
		},
		'convert_zusammenzeichnung' : {
			'nr' : 4,
			'msg' : 'Konvertierung der Plandaten in die Version 5.4'
		},
		'create_gml_file' : {
			'nr' : 5,
			'msg' : 'Erzeugen der GML-Datei in Version 5.4'
		},
		'create_geoweb_service' : {
			'nr' : 6,
			'msg' : 'Erzeugen des GeoWeb-Dienstes für den Plan'
		},
		'create_service_metadata' : {
			'nr' : 7,
			'msg' : 'Anlegen der Metadaten für den Dienst'
		}
	}

	function toggle_head(head_div) {
		console.log('toggle_head');
		$(head_div).children().toggleClass('fa-caret-down fa-caret-right');
		$(head_div).next().toggle();
	}

	function show_upload_zusammenzeichnung(msg) {
		console.log('show_upload_zusammenzeichnung');
		$('#zusammenzeichnung, #keine_zusammenzeichnung').hide();
		$('#upload_zusammenzeichnung_msg').html(msg);
		$('#neue_zusammenzeichnung').show();
	}

	function delete_zusammenzeichnung() {
		console.log('delete_zusammenzeichnung');
		alert('Löschen der Zusammenzeichnung noch nicht implementiert.');
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

	function upload_zusammenzeichnung(event) {
		fileobj = event.dataTransfer.files[0];
		ajax_file_upload(fileobj);
	}

	function file_explorer() {
		document.getElementById('selectfile').click();
		document.getElementById('selectfile').onchange = function() {
			fileobj = document.getElementById('selectfile').files[0];
			ajax_file_upload(fileobj);
		};
	}

	function ajax_file_upload(file_obj) {
		if (file_obj != undefined) {
			var form_data = new FormData();
			form_data.append('go', 'xplankonverter_upload_zusammenzeichnung');
			form_data.append('konvertierung_id', <? echo $zusammenzeichnung_id; ?>);
			form_data.append('upload_file', file_obj);
			var xhttp = new XMLHttpRequest();
			xhttp.open("POST", "index.php", true);
			xhttp.onload = function(event) {
				if (xhttp.status == 200) {
					try {
						response = JSON.parse(this.responseText);
						if (!Array.isArray(response.msg)) { response.msg = [response.msg]; }
						if (response.success) {
							confirm_step('upload_zusammenzeichnung', true);
							validate_zusammenzeichnung();
						}
						else {
							confirm_step('upload_zusammenzeichnung', false);
							message(response.msg);
						}
					} catch (err) {
						if (this.responseText.indexOf('<input id="login_name"') > 0) {
							window.location = 'index.php';
						}
						show_upload_zusammenzeichnung('Neue Version der Zusammenzeichnung hier reinziehen.');
						$('#upload_zusammenzeichnung_msg').removeClass('blink');
						$('#upload_zusammenzeichnung_div').removeClass('dragover');
						message([{ type: 'error', msg: 'Fehler beim Hochladen der Zusammenzeichnung!<p>' + err + ' ' + this.responseText }]);
					}
				}
				else {
					message([{ type: 'error', msg: 'Fehler ' + xhttp.status + ' aufgetreten beim Versuch die Datei hochzuladen! ' + this.responseText }]);
				}
				//$('#upload_result_msg_div').show();
			}

//			show_upload_zusammenzeichnung('Zusammenzeichnung wird verarbeitet');
			$('#sperr_div').show();
			$('#sperr_div').html('\
				<div id="upload_zusammenzeichnung_progress_div" class="xplankonverter-upload-zusammenzeichnung-div">\
					<h2 style="margin-bottom: 20px; float:left">Neue Zusammenzeichnung</h2>\
					<i class="fa fa-times" aria-hidden="true" style="float: right; margin: -5"></i>\
				</div>\
			');
			next_step('upload_zusammenzeichnung');
//			$('#upload_zusammenzeichnung_msg').addClass('blink');
			xhttp.send(form_data);
		}
	}
	
	function next_step(step) {
		$('#upload_zusammenzeichnung_progress_div').append('\
			<div id="upload_zusammenzeichnung_step_' + process[step].nr + '" style="float:left">' + process[step].msg + '</div>\
			<div id="upload_zusammenzeichnung_step_confirm_' + process[step].nr + '" style="float: right"></div>\
			<div style="clear: both"></div>\
		');
	}

	function confirm_step(step, success) {
		$('#upload_zusammenzeichnung_step_' + process[step].nr).addClass(success ? 'green' : 'red');
		$('#upload_zusammenzeichnung_step_confirm_' + process[step].nr).html('<i class="fa fa-' + (success ? 'check green' : 'times red') + '" aria-hidden="true" ></i>');
	}

	function cancel_upload_zusammenzeichnung() {
		$('#neue_zusammenzeichnung').hide();
		$('#sperr_div').hide().html('');
		$('#<? if (!$this->zusammenzeichnung_exists) { ?>keine_<? } ?>zusammenzeichnung').show();
	}

	function show_new_version() {
		console.log('show_new_version');
		window.location = 'index.php?go=xplankonverter_zusammenzeichnung&planart=FP-Plan&neue_version=1';
	}

	function show_aktuelle_version() {
		console.log('show_aktuelle_version');
		window.location = 'index.php?go=xplankonverter_zusammenzeichnung&planart=FP-Plan';
	}

</script>