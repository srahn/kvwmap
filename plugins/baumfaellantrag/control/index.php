<?
	$this->goNotExecutedInPlugins = false;
	switch($this->go) {
		case 'baumfaellantrag_get_flurstueck' : {
			# example for request
			# http://myserver.de/kvwmap/index.php?Stelle_IDgo=baumfaellantrag_get_flurstueck&latitude=53.71181&longitude=11.97404
			# example for response
			# {"landId":"13","kreisId":"99","gemeindId":"99","gemarkungId":"123","gemarkungName":"Testgemarkung","flurId":"1","flurstueckId":"130999-001-00099\/009.00","flurstueckNummer":"99\/9"}
	
			# Layer Id für den Flurstueckslayer
			$this->formvars['selected_layer_id'] = BAUMFAELLANTRAG_LAYER_ID_FLURSTUECKE;

			# EPSG-Code der Abfragekoordinaten
			if ($this->formvars['epsg_code'] == '')
				$this->formvars['epsg_code'] = 4326; # epsg_code of search point coordinates

			# Default Koordinate
			if ($this->formvars['longitude'] == '')
				$this->formvars['longitude'] = BAUMFAELLANTRAG_DEFAULT_LONGITUDE;
			if ($this->formvars['latitude'] == '')
				$this->formvars['latitude'] = BAUMFAELLANTRAG_DEFAULT_LATITUDE;
			$this->formvars['newpathwkt'] = "POINT(" . $this->formvars['longitude'] . " " . $this->formvars['latitude'] . ")";

			# Ausgewählte Spalten
			$this->formvars['selectors'] = 'landId, kreisId, gemeindeId, gemeindeName, gemarkungId, gemarkungName, flurId, flurstueckId, flurstueckNummer';

			# Format
			$this->formvars['mime_type'] = 'formatter';
			$this->formvars['format'] = 'json';

			$this->GenerischeSuche_Suchen();
		} break;

		case 'baumfaellantrag_get_zustaendige_stelle' : {
			# example for request
			# http://myserver.de/kvwmap/index.php?Stelle_IDgo=baumfaellantrag_get_zustaendige_stelle&latitude=53.71181&longitude=11.97404
			# example for response
			# {"gemnr":"13060999","name":"Musterstelle","lk_nr":"99","email":"pkorduan@beispiel.de","ansprechpartner":"Marc Mustermann","bearbeitungszeit":"ca. 10 Tage","npa_allowed":"t","www":"http:\/\/www.zustaendigestelle.de\/mustin\/"}
	
			# Layer Id für den zuständige Stelle
			$this->formvars['selected_layer_id'] = BAUMFAELLANTRAG_LAYER_ID_ZUSTAENDIGESTELLEN;

			# EPSG-Code der Abfragekoordinaten
			if ($this->formvars['epsg_code'] == '')
				$this->formvars['epsg_code'] = 4326; # epsg_code of search point coordinates

			# Default Koordinate
			if ($this->formvars['longitude'] == '')
				$this->formvars['longitude'] = BAUMFAELLANTRAG_DEFAULT_LONGITUDE;
			if ($this->formvars['latitude'] == '')
				$this->formvars['latitude'] = BAUMFAELLANTRAG_DEFAULT_LATITUDE;
			$this->formvars['newpathwkt'] = "POINT(" . $this->formvars['longitude'] . " " . $this->formvars['latitude'] . ")";

			# Ausgewählte Spalten
			$this->formvars['selectors'] = 'gemnr, name, lk_nr, email, ansprechpartner, bearbeitungszeit, npa_allowed, www';

			# Format
			$this->formvars['mime_type'] = 'formatter';
			$this->formvars['format'] = 'json';

			$this->GenerischeSuche_Suchen();
		} break;

		case 'baumfaellantrag_get_satzungsgebiet' : {
			# example for request
			# http://myserver.de/kvwmap/index.php?Stelle_IDgo=baumfaellantrag_get_satzungsgebiet&latitude=53.71181&longitude=11.97404
			# example for response
			# {"id":"1","name":"Satzung 1 Testgebiet","type":"Baumf\u00e4llsatzung","erlaubter_durchmesser":"80"}
	
			# Layer Id für den zuständige Stelle
			$this->formvars['selected_layer_id'] = BAUMFAELLANTRAG_LAYER_ID_SATZUNGSGEBIETE;

			# EPSG-Code der Abfragekoordinaten
			if ($this->formvars['epsg_code'] == '')
				$this->formvars['epsg_code'] = 4326; # epsg_code of search point coordinates

			# Default Koordinate
			if ($this->formvars['longitude'] == '')
				$this->formvars['longitude'] = BAUMFAELLANTRAG_DEFAULT_LONGITUDE;
			if ($this->formvars['latitude'] == '')
				$this->formvars['latitude'] = BAUMFAELLANTRAG_DEFAULT_LATITUDE;
			$this->formvars['newpathwkt'] = "POINT(" . $this->formvars['longitude'] . " " . $this->formvars['latitude'] . ")";

			# Ausgewählte Spalten
			$this->formvars['selectors'] = 'id, name, type, erlaubter_durchmesser';

			# Format
			$this->formvars['mime_type'] = 'formatter';
			$this->formvars['format'] = 'json';

			$this->GenerischeSuche_Suchen();
		} break;

		case 'upload_temp_file' : {
			$this->checkCaseAllowed($this->go);
			include(PLUGINS.'baumfaellantrag/model/kvwmap.php');
			$this->qlayerset[0]['shape'][0] = $this->uploadTempFile(); 
			$this->mime_type = "formatter";
			if ($this->formvars['format'] == '') $this->formvars['format'] = "json";
			if ($this->formvars['content_type'] == '') $this->formvars['content_type'] = "text/html";
			$this->output();
		} break;

		case 'pack_and_mail' : {
			$this->checkCaseAllowed($this->go);
			include(PLUGINS.'baumfaellantrag/model/kvwmap.php');
			$strip_list = "go, go_plus, username, passwort, Stelle_ID, format, version, callback, _dc, file";
			$application_data = formvars_strip($this->formvars, $strip_list);

			# erzeuge eine eindeutige Nummer für diesen Antrag
			$antrag_id = date("YmdHis") . str_pad(rand(1, 99), 2, "00", STR_PAD_LEFT);
			
			if ($this->saveApplicationData($antrag_id, $application_data)) {
				$this->qlayerset[0]['shape'][0] = $this->packAndMail($antrag_id, $application_data);;
			}
			else {
				$this->qlayerset[0]['shape'][0] = array("success" => 0, "data" => $application_data);;
			}
			$this->mime_type = "formatter";
			if ($this->formvars['format'] == '') $this->formvars['format'] = "json";
			$this->output();
		} break;

		default : {
			$this->goNotExecutedInPlugins = true;	// in diesem Plugin wurde go nicht ausgeführt
		}
	}
?>