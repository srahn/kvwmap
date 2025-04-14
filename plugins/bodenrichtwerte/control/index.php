<?
		
function go_switch_bodenrichtwerte($go){
	global $GUI;
	switch($go){		
		case 'Bodenrichtwertzone_Loeschen' : {
			include(PLUGINS.'bodenrichtwerte/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'bodenrichtwerte/model/bodenrichtwerte.php');	# bodenrichtwert-Klasse einbinden
			$GUI->bodenRichtWertZoneLoeschen();
	  } break;

	  case 'Bodenrichtwertformular' : {
			$GUI->checkCaseAllowed('Bodenrichtwertformular');
			include (PLUGINS.'bodenrichtwerte/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'bodenrichtwerte/model/bodenrichtwerte.php');	# bodenrichtwert-Klasse einbinden
			$GUI->titel='Bodenrichtwerterfassung';
			$GUI->bodenRichtWertErfassung();
	  } break;
	  
	  case 'Bodenrichtwertformular_Anzeige' : {
			include(PLUGINS.'bodenrichtwerte/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'bodenrichtwerte/model/bodenrichtwerte.php');	# bodenrichtwert-Klasse einbinden
			$GUI->titel='Bodenrichtwertanzeige';
			$GUI->aendernBodenRichtWert();
	  } break;

	  case 'Bodenrichtwertformular_Aendern' : {
			$GUI->checkCaseAllowed('Bodenrichtwertformular');
			include(PLUGINS.'bodenrichtwerte/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'bodenrichtwerte/model/bodenrichtwerte.php');	# bodenrichtwert-Klasse einbinden
			$GUI->aendernBodenRichtWert();
	  } break;

	  case 'Bodenrichtwertformular_Senden' : {
			$GUI->checkCaseAllowed('Bodenrichtwertformular');
			include(PLUGINS.'bodenrichtwerte/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'bodenrichtwerte/model/bodenrichtwerte.php');	# bodenrichtwert-Klasse einbinden
			$GUI->bodenRichtWertFormSenden();
	  } break;

	  case 'BodenrichtwertzonenKopieren' : {
			$GUI->checkCaseAllowed('BodenrichtwertzonenKopieren');
			include(PLUGINS.'bodenrichtwerte/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'bodenrichtwerte/model/bodenrichtwerte.php');	# bodenrichtwert-Klasse einbinden
			$GUI->waehleBodenwertStichtagToCopy();
	  } break;

	  case 'BodenrichtwertzonenKopieren_Senden' : {
			$GUI->checkCaseAllowed('BodenrichtwertzonenKopieren');
			include(PLUGINS.'bodenrichtwerte/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'bodenrichtwerte/model/bodenrichtwerte.php');	# bodenrichtwert-Klasse einbinden
			$GUI->copyBodenrichtwertzonen();
	  } break;
		
		default : {
			$GUI->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
}
	
?>