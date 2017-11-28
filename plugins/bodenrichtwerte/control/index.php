<?
		
	$this->goNotExecutedInPlugins = false;	
		
	switch($go){		
		case 'Bodenrichtwertzone_Loeschen' : {
			include(PLUGINS.'bodenrichtwerte/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'bodenrichtwerte/model/bodenrichtwerte.php');	# bodenrichtwert-Klasse einbinden
			$this->bodenRichtWertZoneLoeschen();
	  } break;

	  case 'Bodenrichtwertformular' : {
			$this->checkCaseAllowed('Bodenrichtwertformular');
			include (PLUGINS.'bodenrichtwerte/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'bodenrichtwerte/model/bodenrichtwerte.php');	# bodenrichtwert-Klasse einbinden
			$this->titel='Bodenrichtwerterfassung';
			$this->bodenRichtWertErfassung();
	  } break;
	  
	  case 'Bodenrichtwertformular_Anzeige' : {
			include(PLUGINS.'bodenrichtwerte/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'bodenrichtwerte/model/bodenrichtwerte.php');	# bodenrichtwert-Klasse einbinden
			$this->titel='Bodenrichtwertanzeige';
			$this->aendernBodenRichtWert();
	  } break;

	  case 'Bodenrichtwertformular_Aendern' : {
			$this->checkCaseAllowed('Bodenrichtwertformular');
			include(PLUGINS.'bodenrichtwerte/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'bodenrichtwerte/model/bodenrichtwerte.php');	# bodenrichtwert-Klasse einbinden
			$this->aendernBodenRichtWert();
	  } break;

	  case 'Bodenrichtwertformular_Senden' : {
			$this->checkCaseAllowed('Bodenrichtwertformular');
			include(PLUGINS.'bodenrichtwerte/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'bodenrichtwerte/model/bodenrichtwerte.php');	# bodenrichtwert-Klasse einbinden
			$this->bodenRichtWertFormSenden();
	  } break;

	  case 'BodenrichtwertzonenKopieren' : {
			$this->checkCaseAllowed('BodenrichtwertzonenKopieren');
			include(PLUGINS.'bodenrichtwerte/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'bodenrichtwerte/model/bodenrichtwerte.php');	# bodenrichtwert-Klasse einbinden
			$this->waehleBodenwertStichtagToCopy();
	  } break;

	  case 'BodenrichtwertzonenKopieren_Senden' : {
			$this->checkCaseAllowed('BodenrichtwertzonenKopieren');
			include(PLUGINS.'bodenrichtwerte/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'bodenrichtwerte/model/bodenrichtwerte.php');	# bodenrichtwert-Klasse einbinden
			$this->copyBodenrichtwertzonen();
	  } break;
		
		default : {
			$this->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
	
?>