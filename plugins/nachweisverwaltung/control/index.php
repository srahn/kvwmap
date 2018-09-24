<?

include_once(PLUGINS.'nachweisverwaltung/model/kvwmap.php');						# GUI-Objekt immer erweitern, damit Triggerfunktion bei GLE-Speicherung vorhanden
include_once(PLUGINS.'nachweisverwaltung/model/nachweis.php');					# nachweis-Klasse immer einbinden, damit Triggerfunktion bei GLE-Speicherung vorhanden

function go_switch_nachweisverwaltung($go){
	global $GUI;	
	switch($go){
		case 'Antraege_Anzeigen' : {
			$GUI->checkCaseAllowed('Antraege_Anzeigen');
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->Antraege_Anzeigen();
	  } break;

	  case 'Antrag_loeschen' : {
			$GUI->checkCaseAllowed($go);
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->Antrag_Loeschen();
	  } break;

	  case 'Antraganzeige_Festpunkte_in_Karte_Anzeigen' : {
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->festpunkteZuAntragZeigen();
	  } break;

	  case 'Antraganzeige_Festpunkte_in_Liste_Anzeigen' : {
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->festpunkteSuchen();
	  } break;

	  case 'Antraganzeige_Festpunkte_in_KVZ_schreiben' : {
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->festpunkteInKVZschreiben();
			$GUI->Antraege_Anzeigen();
	  } break;

	  case 'Antraganzeige_Zugeordnete_Dokumente_Anzeigen' : {
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->checkCaseAllowed($go);
			$GUI->DokumenteZuAntraegeAnzeigen();
	  } break;

	  case 'Antraganzeige_Uebergabeprotokoll_Zusammenstellen' : {
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->zusammenstellenUebergabeprotokollNachweise($GUI->formvars['antr_selected']);
	  }break;
	  
	  case 'Antraganzeige_Uebergabeprotokoll_Erzeugen' : {
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->erzeugenUebergabeprotokollNachweise();
	  }break;

	  case 'Antraganzeige_Zusammenstellen_Zippen' : {
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$ret=$GUI->DokumenteZumAntragInOrdnerZusammenstellen();
			if($ret != '')showAlert($ret);
			$filename = $GUI->DokumenteOrdnerPacken(false);
			$GUI->Datei_Download($filename);
	  } break;
		
	  case 'Antraganzeige_Zusammenstellen_Zippen_mit_Uebersichten' : {
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$ret=$GUI->DokumenteZumAntragInOrdnerZusammenstellen();
			if($ret != '')showAlert($ret);
			$filename = $GUI->DokumenteOrdnerPacken(true);
			$GUI->Datei_Download($filename);
	  } break;		

	  case 'Nachweisloeschen':{
			$GUI->checkCaseAllowed($go);
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->nachweisLoeschen();
	  } break;


		#Documente die in der Ergebnisliste ausgewählt wurden sollen weiterverarbeitet werden!
		# 2006-01-26 pk
	  case 'Nachweisanzeige_zum_Auftrag_hinzufuegen' : {
			$GUI->checkCaseAllowed($go);
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->nachweiseZuAuftrag();
	  } break;

	  case 'Nachweisanzeige_aus_Auftrag_entfernen':{
			$GUI->checkCaseAllowed($go);
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->nachweiseZuAuftragEntfernen();
	  } break;

		case 'Nachweisanzeige_Geometrieuebernahme':{
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->nachweiseGeometrieuebernahme();
		} break;
		
	  # Rechercheanfrage an die Datenbank senden / mit prüfen der Eingabedaten
	  case 'Nachweisanzeige' : {
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			# keine, alle oder alle Nachweise der gleichen Messung markieren oder selektierte Nachweise einblenden (keine Speicherung in rolle_nachweise)
			if(in_array($GUI->formvars['markhauptart'][0], array('000', '111', '222')) OR $GUI->formvars['showhauptart'][0] == 2222){
				$ids = $GUI->formvars['id'];
			}
			else{
				$GUI->setNachweisAnzeigeparameter($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id, $GUI->formvars['showhauptart'],$GUI->formvars['markhauptart']);
			}
			# Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
			$GUI->savedformvars=$GUI->getNachweisParameter($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id);
			$GUI->formvars=array_merge($GUI->savedformvars,$GUI->formvars);
			if($GUI->formvars['showhauptart'][0] == '')$GUI->formvars['showhauptart'] = $GUI->formvars['suchhauptart'];		# ist bei "alle einblenden" der Fall
			$GUI->nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
			$GUI->hauptdokumentarten = $GUI->nachweis->getHauptDokumentarten();
			$ret=$GUI->nachweis->getNachweise($ids,$GUI->formvars['suchpolygon'],$GUI->formvars['suchgemarkung'],$GUI->formvars['suchstammnr'],$GUI->formvars['suchrissnummer'],$GUI->formvars['suchfortfuehrung'],$GUI->formvars['showhauptart'],$GUI->formvars['richtung'],$GUI->formvars['abfrageart'], $GUI->formvars['order'], $GUI->formvars['suchantrnr'], $GUI->formvars['sdatum'], $GUI->formvars['sVermStelle'], $GUI->formvars['gueltigkeit'], $GUI->formvars['sdatum2'], $GUI->formvars['suchflur'], $GUI->formvars['flur_thematisch'], $GUI->formvars['suchunterart'], $GUI->formvars['suchbemerkung'], NULL, $GUI->formvars['suchstammnr2'], $GUI->formvars['suchrissnummer2'], $GUI->formvars['suchfortfuehrung2']);
			if($ret!=''){
				$GUI->nachweisAnzeige();
				showAlert($ret);
			}
			else {
				$GUI->nachweisAnzeige();
			}
	  } break;

	  case 'document_anzeigen' : {
			$GUI->nachweisDokumentAnzeigen();
	  } break;
	  
	  case 'document_vorschau' : {
			$GUI->nachweisDokumentVorschau();
	  } break;

	  case 'Nachweisformular' : {
			$GUI->checkCaseAllowed($go);		
			# Unterscheidung ob vorhandene Dokumente geändert werden sollen oder neu eingegeben
			if ($GUI->formvars['id']!='') {
				# Ein Nachweis soll geändert werden
				$GUI->nachweisAenderungsformular();
			}
			else {
				# Eingabe von Daten zu einem neuen Nachweisdokument
				# Anzeige des Neueingabeformulars
				$GUI->nachweisFormAnzeige();
			}
	  } break;
		
	  case 'Nachweisformular_Senden' : {
	  	$_files = $_FILES;
			$GUI->formvars['Bilddatei']=$_files['Bilddatei']['tmp_name'];
			$GUI->formvars['Bilddatei_name']=$_files['Bilddatei']['name'];
			$GUI->formvars['Bilddatei_size']=$_files['Bilddatei']['size'];
			$GUI->formvars['Bilddatei_type']=$_files['Bilddatei']['type'];
			$GUI->nachweisFormSenden();
	  } break;
	  
	  case 'Nachweisformular_Vorlage' : {
			$GUI->nachweisFormAnzeigeVorlage();
	  } break;

		case 'check_nachweis_poly' : {
			$GUI->check_nachweis_poly();
	  } break;

	  case 'Antrag_Aendern' : {
			$GUI->checkCaseAllowed($go);
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->vermessungsantragAendern();
	  } break;

	  case 'Nachweis_antragsnr_form_aufrufen' : {
			$GUI->checkCaseAllowed($go);
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->vermessungsantragsFormular();
	  } break;

	  case 'Nachweis_antragsnummer_Senden' : {
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->vermessungsantragAnlegen();
	  } break;

	  case 'Nachweisrechercheformular':{
			$GUI->checkCaseAllowed($go);
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->rechercheFormAnzeigen();
	  } break;
		
		case 'Nachweisrechercheformular_Dokumentauswahl_speichern':{
			$GUI->checkCaseAllowed('Nachweisrechercheformular');
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->rechercheFormAnzeigen();
	  } break;
		
		case 'Nachweisrechercheformular_Dokumentauswahl_löschen':{
			$GUI->checkCaseAllowed('Nachweisrechercheformular');
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->rechercheFormAnzeigen();
	  } break;		

	  # Rechercheanfrage an die Datenbank senden/ mit prüfen der Eingabedaten
	  case 'Nachweisrechercheformular_Senden':{
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->nachweiseRecherchieren();
	  } break;
				
		case 'Sachdaten_Festpunkte Anzeigen' : {
			$GUI->festpunkteZeigen();
	  } break;

	  case 'Festpunkte Anzeigen' : {
			$GUI->festpunkteZeigen();
	  } break;

	  case 'Festpunkte in Liste Anzeigen' : {
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->festpunkteSuchen();
	  } break;

	  case 'Festpunkte_Auswaehlen' : {
			$GUI->checkCaseAllowed($go);
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->festpunkteWahl();
	  } break;

	  case 'Festpunkte_Auswaehlen_Suchen' : {
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->festpunkteSuchen();
	  } break;
		
		case 'Sachdaten_Festpunkte zu Auftrag Hinzufügen' : {
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->festpunkteZuAuftragFormular();
	  } break;
	  
	  case 'Sachdaten_KVZ-Datei erzeugen' : {
			$GUI->formvars['antr_selected'] = 'ohne';
			$GUI->festpunkteInKVZschreiben();
			ob_end_clean();
			header("Content-type: text/kvz");
			header("Content-Disposition: attachment; filename=".basename($GUI->datei));
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			readfile($GUI->datei);
	  } break;

	  case 'Festpunkte zum Antrag Hinzufügen_Senden' : {
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->festpunkteZuAuftragSenden();
	  } break;

	  case 'sendImage' : {
			if ($GUI->formvars['format'] == '') {
				$GUI->formvars['format']='png';
			}
			$GUI->sendImage($GUI->formvars['name'],'png');
	  } break;

	  case 'sendeFestpunktskizze' : {
			$GUI->checkCaseAllowed($go);
			$GUI->sendeFestpunktskizze($GUI->formvars['name'],PUNKTDATEIPATH);
	  } break;

	  case 'Sachdaten_FestpunkteSkizzenZuordnung' : {
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->showFestpunkteSkizze();
	  } break;

	  case 'FestpunkteSkizzenZuordnung_Senden' : {
			$GUI->checkCaseAllowed($go);
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->ordneFestpunktSkizzen();
	  } break;
		
		default : {
			$GUI->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
}
	
?>