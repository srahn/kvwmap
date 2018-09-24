<?
	
function go_switch_baumfaellantrag($go){
	global $GUI;	
	switch($go) {
		case 'baumfaellantrag_get_flurstueck' : {
			# example for request
			# http://myserver.de/kvwmap/index.php?Stelle_IDgo=baumfaellantrag_get_flurstueck&latitude=53.71181&longitude=11.97404
			# example for response
			# {"landId":"13","kreisId":"99","gemeindId":"99","gemarkungId":"123","gemarkungName":"Testgemarkung","flurId":"1","flurstueckId":"130999-001-00099\/009.00","flurstueckNummer":"99\/9"}
	
			# Layer Id für den Flurstueckslayer
			$GUI->formvars['selected_layer_id'] = BAUMFAELLANTRAG_LAYER_ID_FLURSTUECKE;

			# EPSG-Code der Abfragekoordinaten
			if ($GUI->formvars['epsg_code'] == '')
				$GUI->formvars['epsg_code'] = 4326; # epsg_code of search point coordinates

			# Default Koordinate
			if ($GUI->formvars['longitude'] == '')
				$GUI->formvars['longitude'] = BAUMFAELLANTRAG_DEFAULT_LONGITUDE;
			if ($GUI->formvars['latitude'] == '')
				$GUI->formvars['latitude'] = BAUMFAELLANTRAG_DEFAULT_LATITUDE;
			$GUI->formvars['newpathwkt'] = "POINT(" . $GUI->formvars['longitude'] . " " . $GUI->formvars['latitude'] . ")";

			# Ausgewählte Spalten
			$GUI->formvars['selectors'] = 'landId, kreisId, gemeindeId, gemeindeName, gemarkungId, gemarkungName, flurId, flurstueckId, flurstueckNummer';

			# Format
			$GUI->formvars['mime_type'] = 'formatter';
			if ($GUI->formvars['format'] == '')
				$GUI->formvars['format'] = "json";

			$GUI->formvars['content_type'] = 'text/plain';
			$GUI->GenerischeSuche_Suchen();
		} break;

		case 'baumfaellantrag_get_zustaendige_stelle' : {
			# example for request
			# http://myserver.de/kvwmap/index.php?Stelle_IDgo=baumfaellantrag_get_zustaendige_stelle&latitude=53.71181&longitude=11.97404
			# example for response
			# {"gemnr":"13060999","name":"Musterstelle","lk_nr":"99","email":"pkorduan@beispiel.de","ansprechpartner":"Marc Mustermann","bearbeitungszeit":"ca. 10 Tage","npa_allowed":"t","www":"http:\/\/www.zustaendigestelle.de\/mustin\/"}
	
			# Layer Id für den zuständige Stelle
			$GUI->formvars['selected_layer_id'] = BAUMFAELLANTRAG_LAYER_ID_ZUSTAENDIGESTELLEN;

			# EPSG-Code der Abfragekoordinaten
			if ($GUI->formvars['epsg_code'] == '')
				$GUI->formvars['epsg_code'] = 4326; # epsg_code of search point coordinates

			# Default Koordinate
			if ($GUI->formvars['longitude'] == '')
				$GUI->formvars['longitude'] = BAUMFAELLANTRAG_DEFAULT_LONGITUDE;
			if ($GUI->formvars['latitude'] == '')
				$GUI->formvars['latitude'] = BAUMFAELLANTRAG_DEFAULT_LATITUDE;
			$GUI->formvars['newpathwkt'] = "POINT(" . $GUI->formvars['longitude'] . " " . $GUI->formvars['latitude'] . ")";

			# Ausgewählte Spalten
			$GUI->formvars['selectors'] = 'gemnr, name, lk_nr, email, ansprechpartner, bearbeitungszeit, npa_allowed, www';

			# Format
			$GUI->formvars['mime_type'] = 'formatter';
			if ($GUI->formvars['format'] == '')
				$GUI->formvars['format'] = "json";

			$GUI->formvars['content_type'] = 'text/plain';
			$GUI->GenerischeSuche_Suchen();
		} break;

		case 'baumfaellantrag_get_satzungsgebiet' : {
			# example for request
			# http://myserver.de/kvwmap/index.php?Stelle_IDgo=baumfaellantrag_get_satzungsgebiet&latitude=53.71181&longitude=11.97404
			# example for response
			# {"id":"1","name":"Satzung 1 Testgebiet","type":"Baumf\u00e4llsatzung","erlaubter_durchmesser":"80"}
	
			# Layer Id für den zuständige Stelle
			$GUI->formvars['selected_layer_id'] = BAUMFAELLANTRAG_LAYER_ID_SATZUNGSGEBIETE;

			# EPSG-Code der Abfragekoordinaten
			if ($GUI->formvars['epsg_code'] == '')
				$GUI->formvars['epsg_code'] = 4326; # epsg_code of search point coordinates

			# Default Koordinate
			if ($GUI->formvars['longitude'] == '')
				$GUI->formvars['longitude'] = BAUMFAELLANTRAG_DEFAULT_LONGITUDE;
			if ($GUI->formvars['latitude'] == '')
				$GUI->formvars['latitude'] = BAUMFAELLANTRAG_DEFAULT_LATITUDE;
			$GUI->formvars['newpathwkt'] = "POINT(" . $GUI->formvars['longitude'] . " " . $GUI->formvars['latitude'] . ")";

			# Ausgewählte Spalten
			$GUI->formvars['selectors'] = 'id, name, type, erlaubter_durchmesser';

			# Format
			$GUI->formvars['mime_type'] = 'formatter';
			if ($GUI->formvars['format'] == '')
				$GUI->formvars['format'] = "json";

			$GUI->formvars['content_type'] = 'text/plain';
			$GUI->GenerischeSuche_Suchen();
		} break;

		case 'upload_temp_file' : {
			$GUI->checkCaseAllowed($go);
			include(PLUGINS.'baumfaellantrag/model/kvwmap.php');
			header('Content-Type: text/plain; charset=utf-8');
			echo utf8_decode(json_encode($GUI->uploadTempFile()));
		} break;

		case 'pack_and_mail' : {
			$GUI->checkCaseAllowed($go);
			include(PLUGINS.'baumfaellantrag/model/kvwmap.php');
			$strip_list = "go, go_plus, username, passwort, Stelle_ID, format, version, callback, _dc, file";
			$application_data = formvars_strip($GUI->formvars, $strip_list);

			# erzeuge eine eindeutige Nummer für diesen Antrag
			$antrag_id = date("YmdHis") . str_pad(rand(1, 99), 2, "00", STR_PAD_LEFT);

			# erzeuge die benötigten Dateien
			$application_data = $GUI->createFiles($antrag_id, $application_data);
			#echo 'mandateRef after createFiles: ' . $application_data['mandateReference'];
			# speicher die Antragsdaten in der Datenbank
			if ($GUI->saveApplicationData($antrag_id, $application_data)) {
				# Wenn das geklappt hat, packe die Dateien in zip und versende die E-Mails.
				$GUI->qlayerset[0]['shape'][0] = $GUI->packAndMail($antrag_id, $application_data);
			}
			else {
				$GUI->qlayerset[0]['shape'][0] = array("success" => 0, "data" => $application_data);
			}
			$GUI->mime_type = "formatter";
			if ($GUI->formvars['format'] == '')
				$GUI->formvars['format'] = "json";

			$GUI->formvars['content_type'] = 'text/plain';
			$GUI->output();
		} break;

		default : {
			$GUI->goNotExecutedInPlugins = true;	// in diesem Plugin wurde go nicht ausgeführt
		}
	}
}
?>