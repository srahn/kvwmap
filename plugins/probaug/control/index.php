<?

function go_switch_probaug($go){
	global $GUI;	
	switch($go){		
		case 'Bauauskunft_Suche' : {
			$GUI->checkCaseAllowed($go);
			include_once(PLUGINS.'alkis/model/kataster.php');
			include_once(PLUGINS.'probaug/model/kvwmap.php');
			include_once(PLUGINS.'probaug/model/bau.php');
			$GUI->bauauskunftSuche();
			$GUI->output();
	  } break;

	  case 'Bauauskunft_Suche_Suchen' : {
			$GUI->sanitizeBauauskunftSuche();
			$GUI->checkCaseAllowed('Bauakteneinsicht');
			include_once(PLUGINS.'alkis/model/kataster.php');
			include_once(PLUGINS.'probaug/model/kvwmap.php');
			include_once(PLUGINS.'probaug/model/bau.php');
			$GUI->bauauskunftSucheSenden();
			$GUI->output();
	  } break;

	  case 'Baudatenanzeige' : {
			$GUI->sanitizeBauauskunftSuche();
			$GUI->checkCaseAllowed('Bauakteneinsicht');
			include_once(PLUGINS.'alkis/model/kataster.php');
			include_once(PLUGINS.'probaug/model/kvwmap.php');
			include_once(PLUGINS.'probaug/model/bau.php');
			$GUI->bauauskunftanzeige();
			$GUI->output();
	  } break;
		
		case 'zoom2bauakte' : {
			$GUI->sanitizeBauauskunftSuche();
			$GUI->checkCaseAllowed('Bauakteneinsicht');
			include_once(PLUGINS.'alkis/model/kvwmap.php');
			include_once(PLUGINS.'alkis/model/kataster.php');
			include_once(PLUGINS.'probaug/model/kvwmap.php');
			include_once(PLUGINS.'probaug/model/bau.php');
			$GUI->zoom2bauakte();
	  } break;		
		
		default : {
			$GUI->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
}
	
?>