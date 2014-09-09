<?

	$this->goNotExecutedInPlugins = false;
	
	switch($this->go){
		# Anliegerbeiträge
	  case 'anliegerbeitraege' : {
			$this->checkCaseAllowed($this->go);
			include(PLUGINS.'anliegerbeitraege/model/kvwmap.php');
			$this->Anliegerbeiträge_editor();
	  }break;

	  # Anliegerbeiträge Strasse speichern
	  case 'anliegerbeitraege_strasse_speichern' : {
			include(PLUGINS.'anliegerbeitraege/model/kvwmap.php');
			include(PLUGINS.'anliegerbeitraege/model/anliegerbeitraege.php');
			$this->Anliegerbeiträge_strasse_speichern();
	  }break;

	  # Anliegerbeiträge Buffer speichern
	  case 'anliegerbeitraege_buffer_speichern' : {
			include(PLUGINS.'anliegerbeitraege/model/kvwmap.php');
			include(PLUGINS.'anliegerbeitraege/model/anliegerbeitraege.php');
			$this->Anliegerbeiträge_buffer_speichern();
	  }break;
		
		default : {
			$this->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
	
?>