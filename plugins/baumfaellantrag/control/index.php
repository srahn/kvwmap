<?
  $this->goNotExecutedInPlugins = false;

  switch($this->go){
    case 'upload_temp_file' : {
      $this->checkCaseAllowed($go);      
      $this->qlayerset[0]['shape'][0] = $this->uploadTempFile(); 
      $this->output();
    } break;
    
    case 'pack_and_mail' : {
      $this->checkCaseAllowed($go);
      $strip_list = "go, go_plus, username, passwort, Stelle_ID, format, version, callback, _dc, file";
      $this->qlayerset[0]['shape'][0] = $this->packAndMail(formvars_strip($this->formvars, $strip_list));
      $this->output();
    } break;

    default : {
      $this->goNotExecutedInPlugins = true;  // in diesem Plugin wurde go nicht ausgeführt
    }
  }
?>