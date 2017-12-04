<?

	$this->goNotExecutedInPlugins = false;
	
	switch($go){
		case 'migrationGewaesser' : {
			if($this->Stelle->isFunctionAllowed($go)){
				include(PLUGINS.'gewaesser/model/wasserverband.php');
				$gewaesser = new gewaesser($this->pgdatabase);
				
				$gewaesser->truncateSpatial();
				
				$gewaesser->loadSpatial('/home/fgs/wasserverband-kroepelin/gew-kroepelin_utm_ost');
				$gewaesser->loadSpatial('/home/fgs/wasserverband-kroepelin/gew-kroepelin_utm_sued');
				$gewaesser->loadSpatial('/home/fgs/wasserverband-kroepelin/gew-kroepelin_utm_west');
				
				$gewaesser->loadThematic();
				
				$this->gewaesser=$gewaesser;
				$this->main= PLUGINS.'gewaesser/view/migrationGewaesser.php';
				$this->output();
			}
			else {
				# Benutzer ist nicht berechtigt zum Ausführen des Anwendungsfalles
				$this->Fehlermeldung=$this->TaskChangeWarning;
				$this->rollenwahl($Stelle_ID);
				$this->output();
			}		
		}break;
	
		default : {
			$this->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
	
?>