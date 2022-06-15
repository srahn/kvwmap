<?

function go_switch_lenris($go){
	global $GUI;	
	switch($go){
		case 'Antraege_Anzeigen' : {
			$GUI->checkCaseAllowed('Antraege_Anzeigen');
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->Antraege_Anzeigen();
	  } break;
		
		default : {
			$GUI->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
}
	
?>