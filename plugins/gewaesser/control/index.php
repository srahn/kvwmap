<?

function go_switch_gewaesser($go){
	global $GUI;
	switch($go){
		case 'migrationGewaesser' : {
			if($GUI->Stelle->isFunctionAllowed($go)){
				include(PLUGINS.'gewaesser/model/wasserverband.php');
				$gewaesser = new gewaesser($GUI->pgdatabase);
				
				$gewaesser->truncateSpatial();
				
				$gewaesser->loadSpatial('/home/fgs/wasserverband-kroepelin/gew-kroepelin_utm_ost');
				$gewaesser->loadSpatial('/home/fgs/wasserverband-kroepelin/gew-kroepelin_utm_sued');
				$gewaesser->loadSpatial('/home/fgs/wasserverband-kroepelin/gew-kroepelin_utm_west');
				
				$gewaesser->loadThematic();
				
				$GUI->gewaesser=$gewaesser;
				$GUI->main= PLUGINS.'gewaesser/view/migrationGewaesser.php';
				$GUI->output();
			}
			else {
				# Benutzer ist nicht berechtigt zum Ausf�hren des Anwendungsfalles
				$GUI->Fehlermeldung=$GUI->TaskChangeWarning;
				$GUI->rollenwahl($Stelle_ID);
				$GUI->output();
			}		
		}break;
	
		default : {
			$GUI->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgef�hrt
		}
	}
}
	
?>