<?

	$this->goNotExecutedInPlugins = false;
	
	switch($go){
	  case 'spatialDocIndexing' : {
			include(PLUGINS.'geodoc/model/kvwmap.php');
			include(PLUGINS.'geodoc/model/documents.php');
			$GUI->spatialDocIndexing();
	  } break;
		
		default : {
			$this->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}

?>