<?
		
function go_switch_jagdkataster($go){
	global $GUI;
	switch($go){
	  # Jagdbezirke Sachdaten anzeigen
	  case 'jagdbezirk_show_data' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$GUI->jagdbezirk_show_data();
	  }break;

	  # zoomtoJagdbezirke
	  case 'zoomtojagdbezirk' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$GUI->zoomtojagdbezirk();
	  }break;
	  
	  # Jagdbezirke Suchenflurst
	  case 'jagdbezirke_auswaehlen_Suchen' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$GUI->jagdbezirke_auswaehlen_suchen();
	  }break;
	  
	  # Jagdbezirke Suchenflurst
	  case 'jagdbezirke_auswaehlen_Suchen_csv' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$GUI->jagdbezirke_auswaehlen_suchen_csv();
	  }break;
	  
	  # Jagdbezirke Suchen
	  case 'jagdbezirke_auswaehlen' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$GUI->jagdbezirke_auswaehlen();
	  }break;

	  # Jagdkatastereditor
	  case 'jagdkatastereditor' : {
			$GUI->checkCaseAllowed($go);
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$GUI->jagdkatastereditor();
	  }break;

	  # Jagdkatastereditor
	  case 'jagdkatastereditor_Senden' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$GUI->jagdkatastereditor_senden();
	  }break;

	  # Jagdkatastereditor
	  case 'jagdkatastereditor_Loeschen' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$GUI->jagdkatastereditor_loeschen();
	  }break;

	  # Jagdkatastereditor
	  case 'jagdkatastereditor_kopieren' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$GUI->jagdkatastereditor_kopieren();
	  }break;

	  # Jagdkatastereditor
	  case 'jagdkatastereditor_Eigentuemer_Listen' : {	
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$GUI->jagdkatastereditor_listeigentuemer();
	  }break;
	  
	  # Jagdkatastereditor
	  case 'jagdkatastereditor_Flurstuecke_Listen' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$GUI->jagdkatastereditor_listflurst();
	  }break;
	  
	  # Jagdkatastereditor
	  case 'jagdkatastereditor_Flurstuecke_Listen_csv' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$GUI->jagdkatastereditor_listflurst_csv();
	  }break;
		
		# Jagdkatastereditor
	  case 'jagdkatastereditor_Eigentuemer_Listen_csv' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$GUI->jagdkatastereditor_listeigentuemer_csv();
	  }break;

	  # Jagdkatastereditor
	  case 'jagdkatastereditor_Paechter_Listen' : {
			include(PLUGINS.'jagdkataster/model/kvwmap.php');						# GUI-Objekt erweitern
			include (PLUGINS.'jagdkataster/model/jagdkataster.php');		# jagdkataster-Klasse einbinden
			$GUI->jagdkatastereditor_listpaechter();
	  }break;
		
		default : {
			$GUI->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
}
	
?>