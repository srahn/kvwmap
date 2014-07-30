	  # Jagdbezirke Sachdaten anzeigen
	  case 'jagdbezirk_show_data' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$this->jagdbezirk_show_data();
	  }break;

	  # zoomtoJagdbezirke
	  case 'zoomtojagdbezirk' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$this->zoomtojagdbezirk();
	  }break;
	  
	  # Jagdbezirke Suchenflurst
	  case 'jagdbezirke_auswaehlen_Suchen' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$this->jagdbezirke_auswaehlen_suchen();
	  }break;
	  
	  # Jagdbezirke Suchenflurst
	  case 'jagdbezirke_auswaehlen_Suchen_csv' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$this->jagdbezirke_auswaehlen_suchen_csv();
	  }break;
	  
	  # Jagdbezirke Suchen
	  case 'jagdbezirke_auswaehlen' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$this->jagdbezirke_auswaehlen();
	  }break;

	  # Jagdkatastereditor
	  case 'jagdkatastereditor' : {
			$this->checkCaseAllowed($go);
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$this->jagdkatastereditor();
	  }break;

	  # Jagdkatastereditor
	  case 'jagdkatastereditor_Senden' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$this->jagdkatastereditor_senden();
	  }break;

	  # Jagdkatastereditor
	  case 'jagdkatastereditor_Loeschen' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$this->jagdkatastereditor_loeschen();
	  }break;

	  # Jagdkatastereditor
	  case 'jagdkatastereditor_kopieren' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$this->jagdkatastereditor_kopieren();
	  }break;

	  # Jagdkatastereditor
	  case 'jagdkatastereditor_Eigentuemer_Listen' : {	
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$this->jagdkatastereditor_listeigentuemer();
	  }break;
	  
	  # Jagdkatastereditor
	  case 'jagdkatastereditor_Flurstuecke_Listen' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$this->jagdkatastereditor_listflurst();
	  }break;
	  
	  # Jagdkatastereditor
	  case 'jagdkatastereditor_Flurstuecke_Listen_csv' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$this->jagdkatastereditor_listflurst_csv();
	  }break;
		
		# Jagdkatastereditor
	  case 'jagdkatastereditor_Eigentuemer_Listen_csv' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$this->jagdkatastereditor_listeigentuemer_csv();
	  }break;

	  # Jagdkatastereditor
	  case 'jagdkatastereditor_Paechter_Listen' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$this->jagdkatastereditor_listpaechter();
	  }break;