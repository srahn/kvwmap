<?
	$GUI = $this;
	
	# set upload_path from layers document_path
	$layer = $GUI->user->rolle->getLayer(BAUMFAELLANTRAG_LAYER_ID_ANTRAEGE);
	define('BAUMFAELLANTRAG_UPLOAD_PATH', $layer[0]['document_path']);

	$this->uploadTempFile = function() use ($GUI) {
		# pruefe Version
		if ($GUI->formvars['version'] != "1.0.0")
			return array("success" => 0, "error_message" => "Geben Sie eine gültige Versionsnummer an. Derzeit wird nur die Version 1.0.0 unterstützt.");
				
		# pruefe ob upload erfolgreich war
		if ($_FILES["file"]["error"] != UPLOAD_ERR_OK)
			return array("success" => 0, "error_message" => "Fehler: " . get_upload_error_message($_FILES["file"]["error"]));
		
		# prüfe ob eine Datei mitgeschickt wurde
		if ($_FILES["file"]["name"] == "")
			return array("success" => 0, "error_message" => "Fehler: Es wurde keine Datei mitgeschickt.");

		# prüfe ob die Datei klein genug ist
		if ($_FILES["file"]["size"] > 10737418240)
			return array("success" => 0, "error_message" => "Fehler: Die Datei ist " . formatBytes($_FILES["file"]["size"], 2) . " groß und damti größer als die zugelassenen 10 MB");

		# prüfe ob Dateiformat zulässig
		if (!in_array($_FILES["file"]["type"], array("image/jpeg", "image/jpg", "image/jp2", "image/png", "image/gif", "application/pdf")))
			return array("success" => 0, "error_message" => "Fehler: Der Dateityp " . $_FILES["file"]["type"] . " ist nicht zulässig. Nur die folgenden Dateitypen sind erlaubt: image/jpeg, image/jp2, image/png, image/gif, application/pdf.");
		$pathinfo = pathinfo($_FILES["file"]["name"]);
		$upload_file = basename($_FILES["file"]["tmp_name"] . "." . $pathinfo["extension"]);

		# copiere die temporäre Datei in den upload ordner
		if (!@copy($_FILES["file"]["tmp_name"], BAUMFAELLANTRAG_UPLOAD_PATH . $upload_file))
			return array("success" => 0, "error_message" => "Fehler: Die hochgeladene Datei konnte nicht auf dem Server gespeichert werden. Beim Kopieren vom temporären Uploadverzeichnis in das Uploadverzeichnis der Anwendung trat ein Fehler auf. Wahrscheinlich fehlen die Schreibrechte im Uploadverzeichnins für den WebServer-Nutzer.");

		# sende den Namen der temporären Datei zurück
		return array("success" => 1, "temp_file" => $upload_file);
	};

	$this->packAndMail = function($antrag_id, $data) use ($GUI) {
		$plugin_name = 'baumfaellantrag';

		# pruefe Version
		if ($GUI->formvars['version'] != "1.0.0")
			return array("success" => 0, "error_message" => "Geben Sie eine gültige Versionsnummer an. Derzeit wird nur die Version 1.0.0 unterstützt.");

		# create reference files
		$ref_files = array();
		include (PLUGINS . $plugin_name . '/view/rename_reference_files.php'); // create references to uploaded files

		# create xml file
		$xml_file_name =	"Antrag_" . $antrag_id . ".xml";
		include (PLUGINS . $plugin_name . '/view/xml_template.php');
		$xml = new SimpleXMLElement($xml_string);
		$xml->asXML(BAUMFAELLANTRAG_UPLOAD_PATH . $xml_file_name);

		# create pdf file
		$pdf_file_name = 'Antrag_' . $antrag_id . '.pdf';
		$fp = fopen(BAUMFAELLANTRAG_UPLOAD_PATH . $pdf_file_name, 'wb');
		include (PLUGINS . $plugin_name . '/view/pdf_template.php'); // create pdf and put it in $pdf_output variable
		fwrite($fp, $pdf_output);
		fclose($fp);
		
		# create zip file
		$zip_file_name =	'Antrag_' . $antrag_id;
		if (file_exists(BAUMFAELLANTRAG_UPLOAD_PATH . $xml_file_name))
			exec(ZIP_PATH . ' ' . BAUMFAELLANTRAG_UPLOAD_PATH . $zip_file_name . ' ' . BAUMFAELLANTRAG_UPLOAD_PATH . $xml_file_name);	 // add xml file
		if (file_exists(BAUMFAELLANTRAG_UPLOAD_PATH . $pdf_file_name))
			exec(ZIP_PATH . ' ' . BAUMFAELLANTRAG_UPLOAD_PATH . $zip_file_name . ' ' . BAUMFAELLANTRAG_UPLOAD_PATH . $pdf_file_name);	 // add pdf file
		foreach($ref_files AS $ref_file) {
			if (file_exists($ref_file))
				exec(ZIP_PATH . ' ' . BAUMFAELLANTRAG_UPLOAD_PATH . $zip_file_name . ' ' . $ref_file); // add mandate file
		}
		$zip_file_name .= '.zip';

		# create and send email for bearbeiter
		$mail_bearbeiter = array();
		include (PLUGINS . $plugin_name . '/view/email_bearbeiter_template.php');
		$success = mail_att($mail_bearbeiter['from_name'], $mail_bearbeiter['from_email'], $mail_bearbeiter['to_email'], $mail_bearbeiter['cc_email'], $mail_bearbeiter['reply_email'],	$mail_bearbeiter['subject'], $mail_bearbeiter['message'], $mail_bearbeiter['attachement'], MAILMETHOD, MAILSMTPSERVER, MAILSMTPPORT);
		
		if ($success and $data['email'] != '') {
			# create and send email for absender
			$mail_absender = array();
			include (PLUGINS . $plugin_name . '/view/email_absender_template.php');
			$success = mail_att($mail_absender['from_name'], $mail_absender['from_email'], $mail_absender['to_email'], $mail_absender['cc_email'], $mail_absender['reply_email'],	$mail_absender['subject'], $mail_absender['message'], $mail_absender['attachement'], MAILMETHOD, MAILSMTPSERVER, MAILSMTPPORT);
		}

	return array("success" => $success, "antrag_id" => $antrag_id, "xml_file" =>  $xml_file_name, "pdf_file" => $pdf_file_name, "zip_file" => $zip_file_name, "email_text" => $email_text, "email_recipient" => $email_recipient, "authority_processingTime" => $data['authority_processingTime'], "data" => $data);
	};
	
	$this->saveApplicationData = function($antrag_id, $data) use ($GUI) {
		# get layer database
		$mapDB = new db_mapObj(null, null);
		$antraegeDb = $mapDB->getlayerdatabase(BAUMFAELLANTRAG_LAYER_ID_ANTRAEGE, 'localhost');

		$success = 1;
		if (!is_array($data['wood_species']) or count($data['wood_species']) < 1) return 0;
		if (!is_array($data['latitude']) or count($data['latitude']) < 1) return 0;
		if (!is_array($data['longitude']) or count($data['longitude']) < 1) return 0;
		if ($data['surname'] == '') return 0;
		if ($data['place'] == '') return 0;
		if ($data['postcode'] == '') return 0;
		if ($data['streetName'] == '') return 0;
		if ($data['streetNo'] == '') return 0;
		# ... TODO add more check constraints

		$sql = '';
		for ($i = 0; $i < count ( $data['wood_species'] ); $i++ ) {
			$sql .= "INSERT INTO baumfaellantrag.antraege (
			nr,
			surname,
			forename,
			place,
			postcode,
			streetname,
			streetno,
			email,
			phone,
			fax,
			ownerinfo,
			authority_municipalitynr,
			authority_municipalityname,
			authority_districtnr,
			authority_contactperson,
			authority_email,
			authority_processingtime,
			cadastre_stateid,
			cadastre_districtid,
			cadastre_municipalityid,
			cadastre_municipalityname,
			cadastre_boundaryid,
			cadastre_boundaryname,
			cadastre_sectionid,
			cadastre_parcelId,
			cadastre_parcelno,
			statute_id,
			statute_type,
			statute_name,
			statute_alloweddiameter,
			reason,
			wood_species,
			trunk_circumference,
			crown_diameter,
			mandatereference,
			locationsketchreference,
			npa_authenticated,
			provider_id,
			tree_geometry
			)
			VALUES (
				".$antrag_id.",
				'".$data['surname']."',
				'".$data['forename']."',
				'".$data['place']."',
				'".$data['postcode']."',
				'".$data['streetName']."',	
				'".$data['streetNo']."',
				".(($data['email'] == '') ? 'NULL' : "'".$data['email']."'").",
				".(($data['fax'] == '') ? 'NULL' : "'".$data['fax']."'").",
				".(($data['phone'] == '') ? 'NULL' : "'".$data['phone']."'").",
				'".$data['ownerinfo']."',
				".(($data['authority_municipalityNr'] == '') ? 'NULL' : $data['authority_municipalityNr']).",
				'".$data['authority_municipalityName']."',
				".(($data['authority_districtNr'] == '') ? 'NULL' : $data['authority_districtNr']).",
				'".$data['authority_contactPerson']."',
				'".$data['authority_email']."',
				'".$data['authority_processingTime']."',
				".(($data['cadastre_stateId'] == '') ? 'NULL' : $data['cadastre_stateId']).",
				".(($data['cadastre_districtId'] == '') ? 'NULL' : $data['cadastre_districtId']).",
				".(($data['cadastre_municipalityId'] == '') ? 'NULL' : $data['cadastre_municipalityId']).",
				'".$data['cadastre_municipalityName']."',
				".(($data['cadastre_boundaryId'] == '') ? 'NULL' : $data['cadastre_boundaryId']).",
				'".$data['cadastre_boundaryName']."',
				".(($data['cadastre_sectionId'] == '') ? 'NULL' : $data['cadastre_sectionId']).",
				'".$data['cadastre_parcelId']."',
				'".$data['cadastre_parcelNo']."',
				".(($data['statute_id'] == '') ? 'NULL' : $data['statute_id']).",
				'".$data['statute_type']."',
				'".$data['statute_name']."',
				".(($data['statute_allowedDiameter'] == '') ? 'NULL' : $data['statute_allowedDiameter']).",
				'".$data['reason']."',
				'".$data['wood_species'][$i]."',
				".(($data['trunk_circumference'][$i] == '') ? 'NULL' : $data['trunk_circumference'][$i]).",
				".(($data['crown_diameter'][$i] == '') ? 'NULL' : $data['crown_diameter'][$i]).",
				".(($data['mandateReference'] == '') ? 'NULL' : "'".BAUMFAELLANTRAG_UPLOAD_PATH.$data['mandateReference']."&original_name=Vollmacht.".end(explode('.', $data['mandateReference']))."'").",
				".(($data['locationSketchReference'] == '') ? 'NULL' : "'".BAUMFAELLANTRAG_UPLOAD_PATH.$data['locationSketchReference']."&original_name=Skizze.".end(explode('.', $data['locationSketchReference']))."'").",
				".(($data['npa_authenticated'] == '') ? 'false' : $data['npa_authenticated']).",
				".(($data['provider_id'] == '') ? 'NULL' : $data['provider_id']).",
				ST_GeometryFromText('POINT(".$data['longitude'][$i]." ".$data['latitude'][$i].")', 4326)
			)
			RETURNING id;";
			#echo 'sql: '.$sql.'<br>';
			$ret = $antraegeDb->execSQL($sql, 4, 1);
			#$data['debug'][] = $sql;
			if ($ret[0] == 1) {
				# set success to 0 if an error occur in saving the dataset and break the loop
				$success = 0;
				break;
			}
		}
		$antraegeDb->close();
		return $success;
	}
?>