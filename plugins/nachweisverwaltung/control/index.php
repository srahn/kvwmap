<?

include_once(PLUGINS.'nachweisverwaltung/model/kvwmap.php');						# GUI-Objekt immer erweitern, damit Triggerfunktion bei GLE-Speicherung vorhanden
include_once(PLUGINS.'nachweisverwaltung/model/nachweis.php');					# nachweis-Klasse immer einbinden, damit Triggerfunktion bei GLE-Speicherung vorhanden

function go_switch_nachweisverwaltung($go){
	global $GUI;	
	switch($go){
		case 'LENRIS_get_all_nachweise' : {
			if ($GUI->Stelle->isFunctionAllowed('LENRIS')) {
				$GUI->LENRIS_get_all_nachweise();
			}
	  } break;
		
		case 'LENRIS_get_new_nachweise' : {
			if ($GUI->Stelle->isFunctionAllowed('LENRIS')) {
				$GUI->LENRIS_get_new_nachweise();
			}
	  } break;
		
		case 'LENRIS_get_changed_nachweise' : {
			if ($GUI->Stelle->isFunctionAllowed('LENRIS')) {
				$GUI->LENRIS_get_changed_nachweise();
			}
	  } break;		
		
		case 'LENRIS_get_deleted_nachweise' : {
			if ($GUI->Stelle->isFunctionAllowed('LENRIS')) {
				$GUI->LENRIS_get_deleted_nachweise();
			}
	  } break;
		
		case 'LENRIS_confirm_new_nachweise' : {
			if ($GUI->Stelle->isFunctionAllowed('LENRIS')) {
				$GUI->LENRIS_confirm_new_nachweise();
			}
	  } break;
		
		case 'LENRIS_confirm_changed_nachweise' : {
			if ($GUI->Stelle->isFunctionAllowed('LENRIS')) {
				$GUI->LENRIS_confirm_changed_nachweise();
			}
	  } break;
		
		case 'LENRIS_confirm_deleted_nachweise' : {
			if ($GUI->Stelle->isFunctionAllowed('LENRIS')) {
				$GUI->LENRIS_confirm_deleted_nachweise();
			}
	  } break;
		
		case 'LENRIS_get_document' : {
			if ($GUI->Stelle->isFunctionAllowed('LENRIS')) {
				$GUI->LENRIS_get_document();
			}
	  } break;		
		
		case 'Antraege_Anzeigen' : {
			$GUI->checkCaseAllowed('Antraege_Anzeigen');
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->Antraege_Anzeigen();
	  } break;

	  case 'Antrag_loeschen' : {
			$GUI->sanitize(['id' => 'text']);
			$GUI->checkCaseAllowed($go);
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->Antrag_Loeschen();
	  } break;

	  case 'Antraganzeige_Festpunkte_in_Karte_Anzeigen' : {
			$GUI->sanitize(['antr_selected' => 'text']);
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
			$GUI->sanitize(['antr_selected' => 'text']);
			$GUI->sanitize(['lea_id' => 'int']);
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->checkCaseAllowed($go);
			$GUI->DokumenteZuAntraegeAnzeigen();
	  } break;

	  case 'Antraganzeige_Uebergabeprotokoll_Zusammenstellen' : {
			$GUI->sanitize(['antr_selected' => 'text']);
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->zusammenstellenUebergabeprotokollNachweise($GUI->formvars['antr_selected']);
	  }break;
	  
	  case 'Antraganzeige_Uebergabeprotokoll_Erzeugen' : {
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->erzeugenUebergabeprotokollNachweise();
	  }break;

	  case 'Antraganzeige_Zusammenstellen_Zippen' : {
			$GUI->sanitize(['antr_selected' => 'text']);
			$GUI->sanitize(['lea_id' => 'int']);
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$ret=$GUI->DokumenteZumAntragInOrdnerZusammenstellen();
			if($ret != '')showAlert($ret);
			$filename = $GUI->DokumenteOrdnerPacken(true);
			$GUI->Datei_Download($filename);
	  } break;
		
	  case 'Antraganzeige_Zusammenstellen_Zippen_mit_Uebersichten' : {
			$GUI->sanitize(['antr_selected' => 'text']);
			$GUI->sanitize(['lea_id' => 'int']);
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$ret=$GUI->DokumenteZumAntragInOrdnerZusammenstellen();
			if($ret != '')showAlert($ret);
			$filename = $GUI->DokumenteOrdnerPacken(false);
			$GUI->Datei_Download($filename);
	  } break;		

	  case 'Nachweisloeschen':{
			$GUI->sanitizeNachweisSearch();
			$GUI->checkCaseAllowed($go);
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->nachweisLoeschen();
	  } break;

		case 'Nachweisanzeige_auswahl_speichern' : {
			$GUI->nachweiseAuswahlSpeichern($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id, $GUI->formvars['id']);
	  } break;
		
	  case 'Nachweisanzeige_zum_Auftrag_hinzufuegen' : {
			$GUI->sanitizeNachweisSearch();
			$GUI->checkCaseAllowed($go);
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->nachweiseZuAuftrag();
	  } break;

	  case 'Nachweisanzeige_aus_Auftrag_entfernen':{
			$GUI->sanitizeNachweisSearch();
			$GUI->checkCaseAllowed($go);
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->nachweiseZuAuftragEntfernen();
	  } break;

		case 'Nachweisanzeige_Geometrieuebernahme':{
			$GUI->sanitizeNachweisSearch();
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->nachweiseGeometrieuebernahme();
		} break;
		
	  # Rechercheanfrage an die Datenbank senden / mit prüfen der Eingabedaten
	  case 'Nachweisanzeige' : {
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
			if($GUI->formvars['bearbeitungshinweis_id'] != ''){
				$GUI->nachweis->updateBearbeitungshinweis($GUI->user, $GUI->formvars['bearbeitungshinweis_id'], $GUI->formvars['bearbeitungshinweis_text']);
			}
			# keine, alle oder alle Nachweise der gleichen Messung markieren oder selektierte Nachweise einblenden (keine Speicherung in rolle_nachweise)
			if(in_array($GUI->formvars['markhauptart'][0], array('000', '111', '222')) OR $GUI->formvars['showhauptart'][0] == 2222){
				$ids = $GUI->formvars['id'];
			}
			else{
				$GUI->setNachweisAnzeigeparameter($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id, $GUI->formvars['showhauptart'],$GUI->formvars['markhauptart']);
			}
			if (isset($GUI->formvars['order'])) {
				$GUI->setNachweisOrder($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id, $GUI->formvars['order']);
			}
			if ($GUI->formvars['columns_changed']) {
				$GUI->setColumns();
			}
			# Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
			$GUI->savedformvars=$GUI->getNachweisParameter($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id);
			$GUI->formvars=array_merge($GUI->savedformvars,$GUI->formvars);
			if ($GUI->formvars['auswahl']) {
				$GUI->formvars['id'] = $GUI->getNachweiseAuswahl($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id);
			}
			if($GUI->formvars['showhauptart'][0] == '')$GUI->formvars['showhauptart'] = $GUI->formvars['suchhauptart'];		# ist bei "alle einblenden" der Fall			
			$GUI->hauptdokumentarten = $GUI->nachweis->getHauptDokumentarten();
			$ret=$GUI->nachweis->getNachweise($ids,$GUI->formvars['suchpolygon'],$GUI->formvars['suchgemarkung'],$GUI->formvars['suchstammnr'],$GUI->formvars['suchrissnummer'],$GUI->formvars['suchfortfuehrung'],$GUI->formvars['showhauptart'],$GUI->formvars['richtung'],$GUI->formvars['abfrageart'], $GUI->formvars['order'], $GUI->formvars['suchantrnr'], $GUI->formvars['sdatum'], $GUI->formvars['sVermStelle'], $GUI->formvars['suchgueltigkeit'], $GUI->formvars['sdatum2'], $GUI->formvars['suchflur'], $GUI->formvars['flur_thematisch'], $GUI->formvars['suchunterart'], $GUI->formvars['suchbemerkung'], NULL, $GUI->formvars['suchstammnr2'], $GUI->formvars['suchrissnummer2'], $GUI->formvars['suchfortfuehrung2'], $GUI->formvars['suchgeprueft'], $GUI->formvars['alle_der_messung']);
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
			$GUI->sanitize([
			'datum' => 'text',
			'flurid' => 'int',
			'VermStelle' => 'text',
			'unterart' => 'int',
			'gueltigkeit' => 'text',
			'geprueft' => 'text',
			'stammnr' => 'text',
			'Blattformat' => 'text',
			'Blattnr' => 'text',
			'rissnummer' => 'text',
			'fortfuehrung' => 'int',
			'bemerkungen' => 'text',
			'bemerkungen_intern' => 'text',
			'umring' => 'text']);
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
			$GUI->sanitize(['umring' => 'text']);
			$GUI->check_nachweis_poly();
	  } break;

	  case 'Antrag_Aendern' : {
			$GUI->sanitize([
			'antr_nr' => 'text',
			'VermStelle' => 'int',
			'verm_art' => 'int',
			'datum' => 'text']);
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
			$GUI->sanitize([
			'antr_nr' => 'text',
			'VermStelle' => 'int',
			'verm_art' => 'int',
			'datum' => 'text']);
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
			$GUI->sanitizeNachweisSearch();
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
			$GUI->sanitize(['antr_selected' => 'text', 'pkn' => 'text']);
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
			$GUI->sanitize(['pkn' => 'text']);
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->showFestpunkteSkizze();
	  } break;

	  case 'FestpunkteSkizzenZuordnung_Senden' : {
			$GUI->checkCaseAllowed($go);
			include (PLUGINS.'nachweisverwaltung/model/antrag.php');						# antrag-Klasse einbinden
			$GUI->ordneFestpunktSkizzen();
	  } break;
		
		case 'get_geom_preview' : {
			$GUI->sanitize(['id' => 'int']);
			$GUI->getGeomPreview($GUI->formvars['id']);
	  } break;
		
		default : {
			$GUI->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
}
	
?>