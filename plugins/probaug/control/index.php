<?

	$this->goNotExecutedInPlugins = false;
	
	switch($go){		
		case 'Bauauskunft_Suche' : {
			$this->checkCaseAllowed($go);
			include_once(PLUGINS.'probaug/model/kvwmap.php');
			include_once(PLUGINS.'probaug/model/bau.php');
			$this->bauauskunftSuche();
			$this->output();
	  } break;

	  case 'Bauauskunft_Suche_Suchen' : {
			$this->checkCaseAllowed('Bauakteneinsicht');
			include_once(PLUGINS.'probaug/model/kvwmap.php');
			include_once(PLUGINS.'probaug/model/bau.php');
			$this->bauauskunftSucheSenden($this->formvars['flurstkennz']);
			$this->output();
	  } break;

	  case 'Baudatenanzeige' : {
			$this->checkCaseAllowed('Bauakteneinsicht');
			include_once(PLUGINS.'probaug/model/kvwmap.php');
			include_once(PLUGINS.'probaug/model/bau.php');
			$this->bauauskunftanzeige();
			$this->output();
	  } break;
		
		case 'zoom2bauakte' : {
			$this->checkCaseAllowed('Bauakteneinsicht');
			include_once(PLUGINS.'probaug/model/kvwmap.php');
			include_once(PLUGINS.'probaug/model/bau.php');
			$this->zoom2bauakte();
	  } break;		
		
		default : {
			$this->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
	
?>