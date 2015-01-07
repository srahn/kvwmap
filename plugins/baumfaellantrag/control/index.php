<?
  $this->goNotExecutedInPlugins = false;
  switch($this->go){
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
      $this->goNotExecutedInPlugins = true;  // in diesem Plugin wurde go nicht ausgeführt
    }
  }
?>