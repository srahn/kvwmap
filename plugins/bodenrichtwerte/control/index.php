	  case 'Bodenrichtwertzone_Loeschen' : {
			include(PLUGINS.'bodenrichtwerte/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'bodenrichtwerte/model/bodenrichtwerte.php');	# bodenrichtwertobjekt erzeugen
			$this->bodenRichtWertZoneLoeschen();
	  } break;

	  case 'Bodenrichtwertformular' : {
			$this->checkCaseAllowed('Bodenrichtwertformular');
			include (PLUGINS.'bodenrichtwerte/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'bodenrichtwerte/model/bodenrichtwerte.php');	# bodenrichtwertobjekt erzeugen
			$this->titel='Bodenrichtwerterfassung';
			$this->bodenRichtWertErfassung();
	  } break;
	  
	  case 'Bodenrichtwertformular_Anzeige' : {
			include(PLUGINS.'bodenrichtwerte/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'bodenrichtwerte/model/bodenrichtwerte.php');	# bodenrichtwertobjekt erzeugen
			$this->titel='Bodenrichtwertanzeige';
			$this->aendernBodenRichtWert();
	  } break;

	  case 'Bodenrichtwertformular_Aendern' : {
			$this->checkCaseAllowed('Bodenrichtwertformular');
			include(PLUGINS.'bodenrichtwerte/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'bodenrichtwerte/model/bodenrichtwerte.php');	# bodenrichtwertobjekt erzeugen
			$this->aendernBodenRichtWert();
	  } break;

	  case 'Bodenrichtwertformular_Senden' : {
			$this->checkCaseAllowed('Bodenrichtwertformular');
			include(PLUGINS.'bodenrichtwerte/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'bodenrichtwerte/model/bodenrichtwerte.php');	# bodenrichtwertobjekt erzeugen
			$this->bodenRichtWertFormSenden();
	  } break;

	  case 'BodenrichtwertzonenKopieren' : {
			$this->checkCaseAllowed('BodenrichtwertzonenKopieren');
			include(PLUGINS.'bodenrichtwerte/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'bodenrichtwerte/model/bodenrichtwerte.php');	# bodenrichtwertobjekt erzeugen
			$this->waehleBodenwertStichtagToCopy();
	  } break;

	  case 'BodenrichtwertzonenKopieren_Senden' : {
			$this->checkCaseAllowed('BodenrichtwertzonenKopieren');
			include(PLUGINS.'bodenrichtwerte/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'bodenrichtwerte/model/bodenrichtwerte.php');	# bodenrichtwertobjekt erzeugen
			$this->copyBodenrichtwertzonen();
	  } break;