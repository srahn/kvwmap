<?
  $GUI = $this;

  $this->uploadTempFile = function() use ($GUI){
    $GUI->mime_type = "formatter";
    if ($GUI->formvars['format'] == '') $GUI->formvars['format'] = "json";
    if ($GUI->formvars['content_type'] == '') $GUI->formvars['content_type'] = "text/html";

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
    $upload_file = UPLOADPATH . basename($_FILES["file"]["tmp_name"] . "." . $pathinfo["extension"]);
    
    # copiere die temporäre Datei in den upload ordner 
    if (!@copy($_FILES["file"]["tmp_name"], $upload_file))
      return array("success" => 0, "error_message" => "Fehler: Die hochgeladene Datei konnte nicht auf dem Server gespeichert werden. Beim Kopieren vom temporären Uploadverzeichnis in das Uploadverzeichnis der Anwendung trat ein Fehler auf. Wahrscheinlich fehlen die Schreibrechte im Uploadverzeichnins für den WebServer-Nutzer.");

    # sende den Namen der temporären Datei zurück
    return array("success" => 1, "temp_file" => $upload_file);
  }

  function packAndMail($data) {
    #var_dump($data);
    
    $GUI->mime_type = "formatter";
    if ($GUI->formvars['format'] == '') $GUI->formvars['format'] = "json";
    
    # pruefe Version
    if ($GUI->formvars['version'] != "1.0.0")
      return array("success" => 0, "error_message" => "Geben Sie eine gültige Versionsnummer an. Derzeit wird nur die Version 1.0.0 unterstützt.");

    # erzeuge eine eindeutige Nummer für diesen Antrag
    $antrag_id = date("YmdHis") . str_pad(rand(1, 99), 2, "00", STR_PAD_LEFT);

    # create reference files
    $ref_files = array();
    include (PLUGINS . 'baumfaellantrag/view/rename_reference_files.php'); // create references to uploaded files
    
    # create xml file
    $xml_file_name =  "Antrag_" . $antrag_id . ".xml";
    include (PLUGINS . 'baumfaellantrag/view/xml_template.php');
    $xml = new SimpleXMLElement($xml_string);
    $xml->asXML(IMAGEPATH . $xml_file_name);

    # create pdf file
    $pdf_file_name = 'Antrag_' . $antrag_id . '.pdf';
    $fp=fopen(IMAGEPATH . $pdf_file_name, 'wb');
    include (PLUGINS . 'baumfaellantrag/view/pdf_template.php'); // create pdf and put it in $pdf_output variable
    fwrite($fp, $pdf_output);
    fclose($fp);
    
    # create zip file
    $zip_file_name =  'Antrag_' . $antrag_id;
    if (file_exists(IMAGEPATH . $xml_file_name))
      exec(ZIP_PATH . ' ' . IMAGEPATH . $zip_file_name . ' ' . IMAGEPATH . $xml_file_name);   // add xml file
    if (file_exists(IMAGEPATH . $pdf_file_name))
      exec(ZIP_PATH . ' ' . IMAGEPATH . $zip_file_name . ' ' . IMAGEPATH . $pdf_file_name);   // add pdf file
    foreach($ref_files AS $ref_file) {
      if (file_exists($ref_file))
        exec(ZIP_PATH . ' ' . IMAGEPATH . $zip_file_name . ' ' . $ref_file); // add mandate file
    }
    $zip_file_name .= '.zip';

    # create email
    $mail = array();
    include (PLUGINS . 'baumfaellantrag/view/email_template.php');
    # send email
    $success = mail_att($mail['from_name'], $mail['from_email'], $mail['to_email'], $mail['cc_email'], $mail['reply_email'],  $mail['subject'], $mail['message'], $mail['attachement']);

  return array("success" => $success, "antrag_id" => $antrag_id, "xml_file" => IMAGEURL . $xml_file_name, "pdf_file" => IMAGEURL . $pdf_file_name, "zip_file" => IMAGEURL . $zip_file_name, "email_text" => $email_text, "email_recipient" => $email_recipient, "authority_processingTime" => $data['authority_processingTime'], "data:" => $data);
  }
?>