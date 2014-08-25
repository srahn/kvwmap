<?

	$this->goNotExecutedInPlugins = false;
	
	switch($this->go){		
		case 'Bauauskunft_Suche' : {
			$this->checkCaseAllowed($go);
			include(PLUGINS.'probaug/model/kvwmap.php');
			include(PLUGINS.'probaug/model/bau.php');
			$this->bauauskunftSuche();
			$this->output();
	  } break;

	  case 'Bauauskunft_Suche_Suchen' : {
			$this->checkCaseAllowed('Bauakteneinsicht');
			include(PLUGINS.'probaug/model/kvwmap.php');
			include(PLUGINS.'probaug/model/bau.php');
			$this->bauauskunftSucheSenden($this->formvars['flurstkennz']);
			$this->output();
	  } break;

	  case 'Baudatenanzeige' : {
			$this->checkCaseAllowed('Bauakteneinsicht');
			include(PLUGINS.'probaug/model/kvwmap.php');
			include(PLUGINS.'probaug/model/bau.php');
			$this->bauauskunftanzeige();
			$this->output();
	  } break;
		
		default : {
			$this->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
	
?>