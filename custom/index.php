<?php
$goNotExecutedInIncludeCases = false;
switch($GUI->go) {
		case 'migrationGewaesser' : {
			if ($GUI->Stelle->isFunctionAllowed($go)) {
			    include (CLASSPATH.'custom/wasserverband.php');
				$gewaesser = new gewaesser($GUI->pgdatabase);
				
				$gewaesser->truncateSpatial();
				
				$gewaesser->loadSpatial('/home/fgs/wasserverband-kroepelin/gew-kroepelin_utm_ost');
				$gewaesser->loadSpatial('/home/fgs/wasserverband-kroepelin/gew-kroepelin_utm_sued');
				$gewaesser->loadSpatial('/home/fgs/wasserverband-kroepelin/gew-kroepelin_utm_west');
				
				$gewaesser->loadThematic();
				

				
				/*
				$sql = "";
				$ret=$GUI->pgdatabase->execSQL($sql,4, 1);
				$rs = pg_fetch_array($ret[1]);
				
				*/
				$GUI->gewaesser=$gewaesser;
				$GUI->main='custom/migrationGewaesser.php';
				$GUI->output();
			}
			else {
				# Benutzer ist nicht berechtigt zum Ausführen des Anwendungsfalles
				$GUI->Fehlermeldung=$GUI->TaskChangeWarning;
				$GUI->rollenwahl($Stelle_ID);
				$GUI->output();
			}		
		}break;
		default : {
			$goNotExecutedInIncludeCases = true;
		}		
}
?>