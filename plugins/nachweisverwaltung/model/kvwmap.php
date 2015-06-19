<?

	$GUI = $this;

	$this->DokumenteOrdnerPacken = function() use ($GUI){
    if ($GUI->formvars['antr_selected']!=''){
      $antrag=new antrag($GUI->formvars['antr_selected'],$GUI->pgdatabase);
      if (is_dir(RECHERCHEERGEBNIS_PATH.$antrag->nr)){
        //$result = exec(RECHERCHE_PACK_SKRIPT.' '.$antrag->nr.' '.$antrag->nr.' '.RECHERCHEERGEBNIS_PATH);
        //$result = exec('zip -r '.RECHERCHEERGEBNIS_PATH.$antrag->nr.' '.RECHERCHEERGEBNIS_PATH.$antrag->nr);
        chdir(RECHERCHEERGEBNIS_PATH);
        $result = exec(ZIP_PATH.' -r '.RECHERCHEERGEBNIS_PATH.$antrag->nr.' '.'./'.$antrag->nr);
      }
    }
    $filename = RECHERCHEERGEBNIS_PATH.$antrag->nr.'.zip';
    $tmpfilename = copy_file_to_tmp($filename);
    unlink($filename);
    return $tmpfilename;
  };

	$this->DokumenteZumAntragInOrdnerZusammenstellen = function() use ($GUI){
    if ($GUI->formvars['antr_selected']!=''){
      # Vorbereiten des Pfades für die Speicherung der recherchierten Dokumente
      $antrag=new antrag($GUI->formvars['antr_selected'],$GUI->pgdatabase);
      $antrag->clearRecherchePfad();
      # Zusammenstellen der Dokumente der Nachweisverwaltung
      $nachweis=new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
      $ret=$nachweis->getNachw2Antr($GUI->formvars['antr_selected']);
      if($ret==''){
        $ret=$nachweis->getNachweise($nachweis->nachweise_id,'','','','','','','','multibleIDs','','');
        if ($ret==''){
          $ret=$antrag->DokumenteInOrdnerZusammenstellen($nachweis);
          $msg.=$ret;
        }
      }

      # Zusammenstellen der Einmessungsskizzen der Festpunkte
      $festpunkte=new Festpunkte('',$GUI->pgdatabase);
      $ret=$festpunkte->getFestpunkte('',array('0','1'),'','','',$GUI->formvars['antr_selected'],'','pkz');
      if ($ret[0]) {
        $errmsg="Festpunkte konnten nicht abgefragt werden.";
      }
      else {
        $ret=$antrag->EinmessungsskizzenInOrdnerZusammenstellen($festpunkte);
        $msg.=$ret;
      }
      # Schreiben des Koordinatenverzeichnisses der zugeordneten Festpunkte
      $GUI->festpunkteInKVZschreiben();
    }
    else {
      $ret='Geben Sie bitte die entspechende Antragsnummer an';
    }
    return $ret;
  };

	$this->nachweisAenderungsformular = function() use ($GUI){
    #2005-11-25_pk
    # Anzeige des Formulars zum Eintragen neuer/Ändern vorhandener Metadaten zu einem Nachweisdokument
    # (FFR, KVZ oder GN)
    $GUI->menue='menue.php';
    $GUI->main= PLUGINS.'nachweisverwaltung/view/dokumenteneingabeformular.php';
    $GUI->titel='Dokument überarbeiten';    
    # Nachweisdaten aus Datenbank abfragen
    $nachweis=new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
    # abfragen der Dokumentarten
    $GUI->dokumentarten = $nachweis->getDokumentarten();
    #echo 'Suche nach id:'.$GUI->formvars['id'];
    $ret=$nachweis->getNachweise($GUI->formvars['id'],'','','','','','','','bySingleID','',0,0);
    if ($ret!='') {
      # Fehler bei der Abfrage des Nachweises
      # Anzeige des letzten Rechercheergebnisses
      $GUI->nachweisAnzeige();
      showAlert($ret);
    }
    else {
      # Abfrage war erfolgreich
      $nachweis->document=$nachweis->Dokumente[0];
      # Laden der letzten Karteneinstellung
      $saved_scale = $GUI->reduce_mapwidth(100);
			$GUI->loadMap('DataBase');
			if($_SERVER['REQUEST_METHOD'] == 'GET')$GUI->scaleMap($saved_scale);		# nur beim ersten Aufruf den Extent so anpassen, dass der alte Maßstab wieder da ist
      
      $GUI->queryable_vector_layers = $GUI->Stelle->getqueryableVectorLayers(NULL, $GUI->user->id);
	    if(!$GUI->formvars['layer_id']){
	      $layerset = $GUI->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
	      $GUI->formvars['layer_id'] = $layerset[0]['Layer_ID'];
	    }
	    # Spaltenname und from-where abfragen
	    if($GUI->formvars['layer_id']){
		    $data = $GUI->mapDB->getData($GUI->formvars['layer_id']);
		    $data_explosion = explode(' ', $data);
		    $GUI->formvars['columnname'] = $data_explosion[0];
		    $select = $GUI->mapDB->getSelectFromData($data);
		    
		    # order by rausnehmen
		  	$orderbyposition = strrpos(strtolower($select), 'order by');
				$lastfromposition = strrpos(strtolower($select), 'from');
				if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
			  	$select = substr($select, 0, $orderbyposition);
		  	}
		    
		    $GUI->formvars['fromwhere'] = pg_escape_string('from ('.$select.') as foo where 1=1');
		    if(strpos(strtolower($GUI->formvars['fromwhere']), ' where ') === false){
		      $GUI->formvars['fromwhere'] .= ' where (1=1)';
		    }
	    }
      
      # Ausführen von Aktionen vor der Anzeige der Karte und der Zeichnung
			$oldscale=round($GUI->map_scaledenom);  
			if ($GUI->formvars['CMD']!='') {
				$GUI->navMap($GUI->formvars['CMD']);
				$GUI->user->rolle->saveDrawmode($GUI->formvars['always_draw']);
			}
			elseif($oldscale!=$GUI->formvars['nScale'] AND $GUI->formvars['nScale'] != '') {
				$GUI->scaleMap($GUI->formvars['nScale']);
			}
      elseif($nachweis->document['wkt_umring'] != ''){
        # Zoom zum Polygon des Dokumentes
        $GUI->zoomToNachweis($nachweis,10);
        $GUI->user->rolle->saveSettings($GUI->map->extent);
        $GUI->user->rolle->readSettings();
        # Übernahme des Nachweisumrings aus der PostGIS-Datenbank
        $GUI->formvars['newpath'] = transformCoordsSVG($nachweis->document['svg_umring']);
        $GUI->formvars['newpathwkt'] = $nachweis->document['wkt_umring'];
        $GUI->formvars['pathwkt'] = $GUI->formvars['newpathwkt'];
      }
      else{
      	showAlert('Achtung! Nachweis hat noch keine Geometrie!');
      }
      # Zuweisen der Werte des Dokumentes zum Formular
      $GUI->formvars['flurid']=$nachweis->document['flurid'];
      $GUI->formvars['stammnr']=$nachweis->document['stammnr'];
      $GUI->formvars['art']=$nachweis->document['art'];
      $GUI->formvars['Blattnr']=$nachweis->document['blattnummer'];
      $GUI->formvars['datum']=$nachweis->document['datum'];
      $GUI->formvars['VermStelle']=$nachweis->document['vermstelle'];
      $GUI->formvars['Blattformat']=$nachweis->document['format'];
      $GUI->formvars['gueltigkeit']=$nachweis->document['gueltigkeit'];
      $GUI->formvars['Gemeinde']=$nachweis->document['Gemeinde'];
      $GUI->formvars['Gemarkung']=substr($GUI->formvars['flurid'],0,6);
      $GUI->formvars['Flur']=intval(substr($GUI->formvars['flurid'],6,9));
      $GUI->formvars['Bilddatei']=NACHWEISDOCPATH.$nachweis->document['link_datei'];
      $GUI->formvars['andere_art']=$nachweis->document['andere_art'];
      $GUI->formvars['rissnummer']=$nachweis->document['rissnummer'];
      $GUI->formvars['fortfuehrung']=$nachweis->document['fortfuehrung'];
      $GUI->formvars['bemerkungen']=$nachweis->document['bemerkungen'];

      # Abfragen der Gemarkungen
      # 2006-01-26 pk
      $Gemarkung=new gemarkung('',$GUI->pgdatabase);
      $GemkgListe=$Gemarkung->getGemarkungListe('','','gmk.GemkgName');
      # Erzeugen des Formobjektes für die Gemarkungsauswahl
      $GUI->GemkgFormObj=new FormObject("Gemarkung","select",$GemkgListe['GemkgID'],$GUI->formvars['Gemarkung'],$GemkgListe['Bezeichnung'],"1","","",NULL);

      # erzeugen des Formularobjektes für Vermessungsstellen
      $GUI->FormObjVermStelle=$GUI->getFormObjVermStelle('VermStelle', $GUI->formvars['VermStelle']);
      $currenttime=date('Y-m-d H:i:s',time());
      $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
      $GUI->drawMap();
      $GUI->saveMap('');
      $GUI->output();
    }
  };
	
	$this->suchparameterSetzen = function() use ($GUI){
    # speichern der Suchparameter und der Markierungsparameter
    if ($GUI->formvars['f'] OR $GUI->formvars['k'] OR $GUI->formvars['g']) {
      if (!$GUI->formvars['f']) {
        $GUI->formvars['f']='0';
      }
      if (!$GUI->formvars['k']) {
        $GUI->formvars['k']='0';
      }
      if (!$GUI->formvars['g']) {
        $GUI->formvars['g']='0';
      }
      $GUI->formvars['art_einblenden']=$GUI->formvars['f'].$GUI->formvars['k'].$GUI->formvars['g'];
    }

    $_SESSION['f']=$GUI->formvars['f'];
    $_SESSION['k']=$GUI->formvars['k'];
    $_SESSION['g']=$GUI->formvars['g'];

    if ($GUI->formvars['art_einblenden']!='') {
      $_SESSION['art_einblenden']=$GUI->formvars['art_einblenden'];
    }
      if ($GUI->formvars['art_einblenden']=='111'){
        $_SESSION['f']='1' AND $_SESSION['k']='1' AND $_SESSION['g']='1';
      }
      if ($GUI->formvars['art_einblenden']=='100'){
        $_SESSION['f']='1';
      }
      if ($GUI->formvars['art_einblenden']=='010'){
        $_SESSION['k']='1';
      }
      if ($GUI->formvars['art_einblenden']=='001'){
        $_SESSION['g']='1';
      }
      if ($GUI->formvars['art_einblenden']=='110'){
        $_SESSION['f']='1' AND $_SESSION['k']='1' AND $_SESSION['g']='0';
      }
      if ($GUI->formvars['art_einblenden']=='101'){
        $_SESSION['f']='1' AND $_SESSION['g']='1' AND $_SESSION['k']='0';
      }
      if ($GUI->formvars['art_einblenden']=='011'){
        $_SESSION['k']='1' AND $_SESSION['g']='1' AND $_SESSION['f']='0' ;
      }

    if ($GUI->formvars['art_markieren']!='') {
      $_SESSION['art_markieren']=$GUI->formvars['art_markieren'];
    }
    if ($GUI->formvars['abfrage_art']!='') {
      $_SESSION['abfrage_art']=$GUI->formvars['abfrage_art'];
    }
    if($GUI->formvars['FlurID']!=''){
      $_SESSION['FlurID']=$GUI->formvars['FlurID'];
    }
    if($GUI->formvars['stammnr']!=''){
      $_SESSION['stammnr']=$GUI->formvars['stammnr'];
    }
  	if($GUI->formvars['rissnummer']!=''){
      $_SESSION['rissnummer']=$GUI->formvars['rissnummer'];
    }
    if($GUI->formvars['antr_nr_a']!=''){
      $_SESSION['antr_nr_a']=$GUI->formvars['antr_nr_a'];
    }
    if($GUI->formvars['antr_nr_b']!=''){
      $_SESSION['antr_nr_b']=$GUI->formvars['antr_nr_b'];

    }
    if($GUI->formvars['antr_nr']!=''){
      $_SESSION['antr_nr']=$GUI->formvars['antr_nr'];
    }
    if($GUI->suchpolygon!=''){
      $_SESSION['suchpolygon']=$GUI->suchpolygon;
    }
  };

	$this->suchparameterLesen = function() use ($GUI){
    $GUI->formvars['art_einblenden']=$_SESSION['art_einblenden'];
    $GUI->formvars['art_markieren']=$_SESSION['art_markieren'];
    $GUI->formvars['abfrage_art']=$_SESSION['abfrage_art'];
    $GUI->formvars['FlurID']=$_SESSION['FlurID'];
    $GUI->formvars['stammnr']=$_SESSION['stammnr'];
    $GUI->formvars['rissnummer']=$_SESSION['rissnummer'];
    $GUI->formvars['antr_nr_a']=$_SESSION['antr_nr_a'];
    $GUI->formvars['antr_nr_b']=$_SESSION['antr_nr_b'];
    $GUI->formvars['f']=$_SESSION['f'];
    $GUI->formvars['k']=$_SESSION['k'];
    $GUI->formvars['g']=$_SESSION['g'];
    $GUI->formvars['antr_nr']=$_SESSION['antr_nr'];
    $GUI->suchpolygon=$_SESSION['suchpolygon'];
  };

	$this->DokumenteZuAntraegeAnzeigen = function() use ($GUI){
    #echo 'antr'.$GUI->formvars['antr_selected'];
    $GUI->formvars['suchffr']=1;
    $GUI->formvars['suchkvz']=1;
    $GUI->formvars['suchgn']=1;
    $GUI->formvars['suchan']=1;
    $GUI->formvars['suchantrnr']=$GUI->formvars['antr_selected'];
    $GUI->formvars['abfrageart']='antr_nr';
    $GUI->nachweiseRecherchieren();
  };

	$this->nachweiseRecherchieren = function() use ($GUI){
		$GUI->formvars['suchstammnr'] = trim($GUI->formvars['suchstammnr']);
		$GUI->formvars['suchrissnr'] = trim($GUI->formvars['suchrissnr']);
    # Suchparameter, die neu gesetzt worden sind in formvars, sollen übernommen werden und gespeichert werden
    # für späterer Suchanfragen und die anderen sollen aus der Datenbank abgefragt werden.
    # Setzen von Such- und Anzeigeparametern die neu gesetzt worden sind
    # (nur neu gesetzte werden überschrieben)
    if ($GUI->formvars['abfrageart']=='poly') {
      $GUI->formvars['suchpolygon'] = $GUI->formvars['newpathwkt'];
    }
    $GUI->user->rolle->setNachweisSuchparameter($GUI->formvars['suchffr'],$GUI->formvars['suchkvz'],$GUI->formvars['suchgn'], $GUI->formvars['suchan'], $GUI->formvars['abfrageart'],$GUI->formvars['suchgemarkung'],$GUI->formvars['suchflur'],$GUI->formvars['suchstammnr'],$GUI->formvars['suchrissnr'],$GUI->formvars['suchfortf'],$GUI->formvars['suchpolygon'],$GUI->formvars['suchantrnr'], $GUI->formvars['sdatum'],$GUI->formvars['sdatum2'], $GUI->formvars['sVermStelle']);
    # Die Anzeigeparameter werden so gesetzt, daß genau das gezeigt wird, wonach auch gesucht wurde.
    # bzw. was als Suchparameter im Formular angegeben wurde.
    $GUI->user->rolle->setNachweisAnzeigeparameter($GUI->formvars['suchffr'],$GUI->formvars['suchkvz'],$GUI->formvars['suchgn'],$GUI->formvars['suchan'],$GUI->formvars['suchffr'],$GUI->formvars['suchkvz'],$GUI->formvars['suchgn']);
    # Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
    $GUI->formvars = array_merge($GUI->formvars, $GUI->user->rolle->getNachweisParameter());
    # Nachweisobjekt bilden
    $GUI->nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
    # Suchparameter in Ordnung
    # Recherchieren nach den Nachweisen
		$GUI->formvars['such_andere_art'] = implode(',', $GUI->formvars['such_andere_art']);
    $ret=$GUI->nachweis->getNachweise(0,$GUI->formvars['suchpolygon'],$GUI->formvars['suchgemarkung'],$GUI->formvars['suchstammnr'],$GUI->formvars['suchrissnr'],$GUI->formvars['suchfortf'],$GUI->formvars['art_einblenden'],$GUI->formvars['richtung'],$GUI->formvars['abfrageart'], $GUI->formvars['order'],$GUI->formvars['suchantrnr'], $GUI->formvars['sdatum'], $GUI->formvars['sVermStelle'], $GUI->formvars['gueltigkeit'], $GUI->formvars['sdatum2'], $GUI->formvars['suchflur'], $GUI->formvars['flur_thematisch'], $GUI->formvars['such_andere_art']);
    #$GUI->nachweis->getAnzahlNachweise($GUI->formvars['suchpolygon']);
    if($ret!=''){
      # Fehler bei der Recherche im Datenbestand
      $GUI->rechercheFormAnzeigen();
      ?><script type="text/javascript">
      alert("<?php echo $ret; ?>");
      </script><?php
    }
    else {
      # Recherche erfolgreich verlaufen
      if ($GUI->nachweis->erg_dokumente==0) {
        # Keine Dokumente zur Auswahl gefunden.
        $GUI->rechercheFormAnzeigen();
        ?><script type="text/javascript">
        alert("Es konnten keine Dokumente zu der Auswahl gefunden werden.\nWählen Sie neue Suchparameter.");
        </script><?php
      }
      else {
        # Anzeige des Rechercheergebnisses
        $GUI->nachweisAnzeige();
      }
    }
  };
	
	$this->erzeugenUebergabeprotokollNachweise = function($antr_nr) use ($GUI){
    if ($antr_nr==''){
      $GUI->Antraege_Anzeigen();
      showAlert('Wählen Sie bitte eine Antragsnummer aus! ');
    }
    else {
      $GUI->antrag = new antrag($antr_nr,$GUI->pgdatabase);
      $ret=$GUI->antrag->getFFR($GUI->formvars);
      if ($ret[0]) {
        $GUI->Fehlermeldung=$ret[1];
        # Abbruch mit Fehlermeldung und Rücksprung in Auswahl
        $GUI->Antraege_Anzeigen();
      }
      else{
      	$GUI->main= PLUGINS.'nachweisverwaltung/view/uebergabeprotokoll.php';
      	$GUI->titel = 'Übergabeprotokoll zusammenstellen';
        $GUI->output();
      }
    }
  };
  
	$this->erzeugenUebergabeprotokollNachweise_PDF = function() use ($GUI){
  	# Erzeugen des Übergabeprotokolls mit der Zuordnung der Nachweise zum gewählten Auftrag als PDF-Dokument
  	if($GUI->formvars['antr_selected'] == ''){
      $GUI->Antraege_Anzeigen();
      showAlert('Wählen Sie bitte eine Antragsnummer aus! ');
    }
    else {
      $GUI->antrag = new antrag($GUI->formvars['antr_selected'],$GUI->pgdatabase);
      $ret=$GUI->antrag->getFFR($GUI->formvars);
      if ($ret[0]) {
        $GUI->Fehlermeldung=$ret[1];
        # Abbruch mit Fehlermeldung und Rücksprung in Auswahl
        $GUI->Antraege_Anzeigen();
      }
      else{
		    include (PDFCLASSPATH."class.ezpdf.php");
		    $pdf=new Cezpdf();
		    $pdf=$GUI->antrag->erzeugenUbergabeprotokoll_PDF($GUI->formvars);
		    $GUI->pdf=$pdf;
		    $dateipfad=IMAGEPATH;
		    $currenttime = date('Y-m-d_H:i:s',time());
		    $name = umlaute_umwandeln($GUI->user->Name);
		    $dateiname = $name.'-'.$currenttime.'.pdf';
		    $GUI->outputfile = $dateiname;
		    $fp=fopen($dateipfad.$dateiname,'wb');
		    fwrite($fp,$GUI->pdf->ezOutput());
		    fclose($fp);
		    $GUI->mime_type='pdf';
		    $GUI->output();
      }
    }
  };
  
	$this->erzeugenUebergabeprotokollNachweise_CSV = function() use ($GUI){
  	# Erzeugen des Übergabeprotokolls mit der Zuordnung der Nachweise zum gewählten Auftrag als CSV-Dokument
  	if($GUI->formvars['antr_selected'] == ''){
      $GUI->Antraege_Anzeigen();
      showAlert('Wählen Sie bitte eine Antragsnummer aus! ');
    }
    else {
      $GUI->antrag = new antrag($GUI->formvars['antr_selected'],$GUI->pgdatabase);
      $ret=$GUI->antrag->getFFR($GUI->formvars);
      if ($ret[0]) {
        $GUI->Fehlermeldung=$ret[1];
        # Abbruch mit Fehlermeldung und Rücksprung in Auswahl
        $GUI->Antraege_Anzeigen();
      }
      else{
		    $csv=$GUI->antrag->erzeugenUbergabeprotokoll_CSV($GUI->formvars);
		    ob_end_clean();
		    header("Content-type: application/vnd.ms-excel");
		    header("Content-disposition:  inline; filename=Übergabeprotokoll_".date('Y-m-d_G-i-s').".csv");
		    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		    header('Pragma: public');
		    print utf8_decode($csv);
      }
    }
  };

	$this->vermessungsantragsFormular = function() use ($GUI){
    if ($GUI->formvars['antr_nr']!=''){
      $GUI->titel='Antrag überarbeiten';
      # Antragsdaten aus der Dtaenbank abfragen
      $GUI->antrag = new antrag('',$GUI->pgdatabase);
      $ret=$GUI->antrag->getAntraege(array($GUI->formvars['antr_nr']),'',$GUI->formvars['richtung'],$GUI->formvars['order']);
      if ($ret[0]==0) {
        $GUI->formvars['verm_art']=$GUI->antrag->antragsliste[0]['verm_art'];
        $GUI->formvars['antr_nr']=$GUI->antrag->antragsliste[0]['antr_nr'];
        $GUI->formvars['datum']=$GUI->antrag->antragsliste[0]['datum'];
        $GUI->formvars['VermStelle']=$GUI->antrag->antragsliste[0]['vermstelle'];
        $GUI->formvars['antr_nr_a']=$GUI->antrag->antragsliste[0]['antr_nr_a'];
        $GUI->formvars['antr_nr_b']=$GUI->antrag->antragsliste[0]['antr_nr_b'];
        $GUI->formvars['go']='Antrag_Aendern';
        $GUI->vermessungsAntragEingabeForm();
      }
      else {
        $GUI->Antraege_Anzeigen();
        showAlert($ret[1]);
      }
    }
    else {
      $GUI->titel='Antrag eingeben';
      $GUI->formvars['go']='Nachweis_antragsnummer_Senden';
      $GUI->vermessungsAntragEingabeForm();
    }
  };

	$this->check_nachweis_poly = function() use ($GUI){
		$GUI->nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
		echo $GUI->nachweis->check_poly_in_flur($GUI->formvars['umring'], $GUI->formvars['flur'], $GUI->formvars['gemkgschl'], $GUI->user->rolle->epsg_code);
		echo '~check_poly();';
	};

	$this->nachweisFormSenden = function() use ($GUI){
    #2005-11-24_pk
    $GUI->nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
    # Aus Formularvariablen zusammengesetzte Werte bilden.
    # Zusammensetzen der flurid
    $GUI->formvars['flurid']=$GUI->formvars['Gemarkung'].str_pad(intval(trim($GUI->formvars['Flur'])),3,'0',STR_PAD_LEFT);
    # Umwandeln des Kodes für die Dokumentenarten in eine Abkürzung
    $GUI->formvars['artname']=ArtCode2Abk($GUI->formvars['art']);
    # Zusammensetzen der übergebenen Parameter für das Polygon
    $GUI->formvars['umring'] = $GUI->formvars['newpathwkt'];
    ######################################
    # Eingabe eines neuen Dokumentes
    if ($GUI->formvars['id']=='') {
      # Prüfen der Eingabewerte
      #echo '<br>Prüfen der Eingabewerte.';
      $ret=$GUI->nachweis->pruefeEingabedaten($GUI->formvars['datum'],$GUI->formvars['VermStelle'],$GUI->formvars['art'],$GUI->formvars['gueltigkeit'],$GUI->formvars['stammnr'],$GUI->formvars['rissnummer'], $GUI->formvars['fortfuehrung'], $GUI->formvars['Blattformat'],$GUI->formvars['Blattnr'],$GUI->formvars['changeDocument'],$GUI->formvars['Bilddatei_name'],$GUI->formvars['pathlength'],$GUI->formvars['umring']);
      if ($ret[0]) {
        #echo '<br>Ergebnis der Prüfung: '.$ret;
        $errmsg=$ret[1];
      }
      else {
        #echo '<br>Prüfung der Eingabewerte ok';
        # 2. Eingabewerte in Ordnung
        # 2.1 Speichern der Bilddatei zum Nachweis auf dem Server
        # Zusammensetzen des Dateinamen unter dem das Dokument gespeichert werden soll.
        $GUI->formvars['zieldateiname']=$GUI->nachweis->getZielDateiName($GUI->formvars);
        $ret=$GUI->nachweis->dokumentenDateiHochladen($GUI->formvars['flurid'], $GUI->nachweis->buildNachweisNr($GUI->formvars[NACHWEIS_PRIMARY_ATTRIBUTE], $GUI->formvars[NACHWEIS_SECONDARY_ATTRIBUTE]),$GUI->formvars['artname'],$GUI->formvars['Bilddatei'],$GUI->formvars['zieldateiname']);
        if ($ret!='') { $errmsg=$ret; }
        else {
          # Speicherung der Bilddatei erfolgreich, Eintragen in Datenbank
          $GUI->nachweis->database->begintransaction();
          $ret=$GUI->nachweis->eintragenNeuesDokument($GUI->formvars['datum'],$GUI->formvars['flurid'],$GUI->formvars['VermStelle'], $GUI->formvars['art'], $GUI->formvars['andere_art'], $GUI->formvars['gueltigkeit'],$GUI->formvars['stammnr'],$GUI->formvars['Blattformat'],$GUI->formvars['Blattnr'],$GUI->formvars['rissnummer'],$GUI->formvars['fortfuehrung'],$GUI->formvars['bemerkungen'],$GUI->formvars['artname']."/".$GUI->formvars['zieldateiname'],$GUI->formvars['umring'], $GUI->user);
          if ($ret[0]) {
            $GUI->nachweis->database->rollbacktransaction();
            $errmsg=$ret[1];
          }
          else {
            $GUI->nachweis->database->committransaction();
            # Alle Aufgaben erfolgreich ausgeführt
            $errmsg='Daten zum neuen Dokument erfolgreich eingetragen!';
          } # ende Speicherung der Metadaten war erfolgreich
        } # ende Speicherung der Bilddatei war erfolgreich
      } # ende Prüfung war erfolgreich
      # Auswertung/Behandlung bei Aufgetretenen Fehlern
      $GUI->Meldung=$errmsg;
      $GUI->nachweisFormAnzeige();
      showAlert($GUI->Meldung);
    } # ende Fall Eintragen Daten zum neuen Dokument
    else {
      ##################################################
      # 1.2. Änderung eines vorhandenen Dokumentes
      $ret=$GUI->nachweis->changeDokument($GUI->formvars, $GUI->user);
      if ($ret[0]) {
        # Die Änderung wurde auf Grund eines Fehlers nicht durchgeführt
        # 1.3 Zurück zum Änderungsformular mit Anzeige der Fehlermeldung
        $GUI->nachweisFormAnzeige();
				$GUI->Meldung=$ret[1];
        showAlert($GUI->Meldung);
      } # end of fehler bei der Änderung
      else {
				$GUI->nachweisAenderungsformular();
        showAlert($ret[1]);
			}
      # 1.4 Zur zur Anzeige der Rechercheergebnisse mit Meldung über Erfolg der Änderung
      # 1.4.1 Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
      #$GUI->formvars=$GUI->user->rolle->getNachweisParameter();
      #$ret=$GUI->nachweis->getNachweise(0,$GUI->formvars['suchpolygon'],$GUI->formvars['suchgemarkungflurid'],$GUI->formvars['suchstammnr'],$GUI->formvars['suchrissnr'],$GUI->formvars['suchfortf'],$GUI->formvars['art_einblenden'],$GUI->formvars['richtung'],$GUI->formvars['abfrageart'], $GUI->formvars['order'],$GUI->formvars['suchantrnr']);
      # 1.4.2 Anzeige der Rechercheergebnisse
      #$GUI->nachweisAnzeige();
      # 1.4.3 Anzeige der Erfolgsmeldung
        #showAlert($GUI->Meldung);
      #} # end of Änderung war erfolgreich
    }
    return 1;
  };
	
	$this->nachweisFormAnzeigeVorlage = function() use ($GUI){
		# Nachweisdaten aus Datenbank abfragen
    $nachweis=new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
    $ret=$nachweis->getNachweise($GUI->formvars['id'],'','','','','','','','MergeIDs','',0,0);
    $nachweis->document=$nachweis->Dokumente[0];
    # Zuweisen der Werte des Dokumentes zum Formular
    $GUI->formvars['flurid']=$nachweis->document['flurid'];
    $GUI->formvars['stammnr']=$nachweis->document['stammnr'];
    $GUI->formvars['rissnummer']=$nachweis->document['rissnummer'];
    $GUI->formvars['art']=$nachweis->document['art'];
    $GUI->formvars['Blattnr']=$nachweis->document['blattnummer'];
    $GUI->formvars['datum']=$nachweis->document['datum'];
    $GUI->formvars['VermStelle']=$nachweis->document['vermstelle'];
    $GUI->formvars['Blattformat']=$nachweis->document['format'];
    $GUI->formvars['gueltigkeit']=$nachweis->document['gueltigkeit'];
    $GUI->formvars['Gemeinde']=$nachweis->document['Gemeinde'];
    $GUI->formvars['Gemarkung']=substr($GUI->formvars['flurid'],0,6);
    $GUI->formvars['Flur']=intval(substr($GUI->formvars['flurid'],6,9));
    $GUI->formvars['Bilddatei']=NACHWEISDOCPATH.$nachweis->document['link_datei'];
    $GUI->formvars['andere_art']=$nachweis->document['andere_art'];
		$GUI->formvars['fortfuehrung']=$nachweis->document['fortfuehrung'];
    $GUI->formvars['id'] = '';
    $GUI->nachweisFormAnzeige($nachweis);
	};

	$this->nachweisFormAnzeige = function($nachweis = NULL) use ($GUI){
    # letzte Änderung 2006-01-23 pk
    # Anzeige des Formulars zum Eintragen neuer/Ändern vorhandener Metadaten zu einem Nachweisdokument
    # (FFR, KVZ oder GN)
    $GUI->menue='menue.php';

    # Wenn eine oid in formvars übergeben wurde ist es eine Änderung, sonst Neueingabe
    if ($GUI->formvars['oid']=='') {
      $GUI->titel='Dokumenteneingabe';
    }
    else {
      $GUI->titel='Dokumenteneingabe (neuer Ausschnitt)';
    }
    $GUI->main = PLUGINS."nachweisverwaltung/view/dokumenteneingabeformular.php";
    # 2006-01-27
    # aktuellen Kartenausschnitt laden + zeichnen!
    $saved_scale = $GUI->reduce_mapwidth(100);
		
		if($GUI->formvars['neuladen']){
      $GUI->neuLaden();
    }
    else{
      $GUI->loadMap('DataBase');
    }
		
		if($_SERVER['REQUEST_METHOD'] == 'GET')$GUI->scaleMap($saved_scale);		# nur beim ersten Aufruf den Extent so anpassen, dass der alte Maßstab wieder da ist
    
    $GUI->queryable_vector_layers = $GUI->Stelle->getqueryableVectorLayers(NULL, $GUI->user->id);
  	if(!$GUI->formvars['layer_id']){
      $layerset = $GUI->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
      $GUI->formvars['layer_id'] = $layerset[0]['Layer_ID'];
    }
    if($GUI->formvars['layer_id']){
	    $data = $GUI->mapDB->getData($GUI->formvars['layer_id']);
	    $data_explosion = explode(' ', $data);
	    $GUI->formvars['columnname'] = $data_explosion[0];
	    $select = $GUI->mapDB->getSelectFromData($data);
	    
	    # order by rausnehmen
	  	$orderbyposition = strrpos(strtolower($select), 'order by');
			$lastfromposition = strrpos(strtolower($select), 'from');
			if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
		  	$select = substr($select, 0, $orderbyposition);
	  	}
	    
	    $GUI->formvars['fromwhere'] = pg_escape_string('from ('.$select.') as foo where 1=1');
	    if(strpos(strtolower($GUI->formvars['fromwhere']), ' where ') === false){
	      $GUI->formvars['fromwhere'] .= ' where (1=1)';
	    }
	  }
    $oldscale=round($GUI->map_scaledenom);  
    if ($GUI->formvars['CMD']!='') {
      $GUI->navMap($GUI->formvars['CMD']);
      $GUI->user->rolle->saveDrawmode($GUI->formvars['always_draw']);
    }
    elseif($oldscale!=$GUI->formvars['nScale'] AND $GUI->formvars['nScale'] != '') {
      $GUI->scaleMap($GUI->formvars['nScale']);
    }
    elseif($nachweis != '') {
      # Zoom zum Polygon des Dokumentes
      $GUI->zoomToGeom($nachweis->document['geom'],10);
      $GUI->user->rolle->saveSettings($GUI->map->extent);
      $GUI->user->rolle->readSettings();
      # Übernahme des Nachweisumrings aus der PostGIS-Datenbank
      $GUI->formvars['newpath'] = transformCoordsSVG($nachweis->document['svg_umring']);
      $GUI->formvars['newpathwkt'] = $nachweis->document['wkt_umring'];
      $GUI->formvars['pathwkt'] = $GUI->formvars['newpathwkt'];
    }
    
    $GUI->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
    $GUI->drawMap();
	
		if($GUI->formvars['Gemarkung'] == '')$GUI->formvars['Gemarkung'] = $GUI->Lagebezeichung['gemkgschl'];
		if($GUI->formvars['Flur'] == '')$GUI->formvars['Flur'] = $GUI->Lagebezeichung['flur'];
    
    # Abfragen der Gemarkungen
    $GemeindenStelle=$GUI->Stelle->getGemeindeIDs();
    $Gemeinde=new gemeinde('',$GUI->pgdatabase);
    $GemListe=$Gemeinde->getGemeindeListe($GemeindenStelle);
    $Gemarkung=new gemarkung('',$GUI->pgdatabase);
    $GemkgListe=$Gemarkung->getGemarkungListe($GemListe['ID'],'');
        
    # Erzeugen des Formobjektes für die Gemarkungsauswahl
    $GUI->GemkgFormObj=new FormObject("Gemarkung","select",$GemkgListe['GemkgID'],$GUI->formvars['Gemarkung'],$GemkgListe['Bezeichnung'],"1","","",NULL);

    # erzeugen des Formularobjektes für die VermessungsStellen
    $GUI->FormObjVermStelle=$GUI->getFormObjVermStelle('VermStelle', $GUI->formvars['VermStelle']);

    # abfragen der Dokumentarten
    $nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
    $GUI->dokumentarten = $nachweis->getDokumentarten();
    $GUI->output();
  };

	$this->nachweisAnzeige = function() use ($GUI){
    $GUI->menue='menue.php';
    $GUI->titel='Rechercheergebnis';
    $GUI->main= PLUGINS.'nachweisverwaltung/view/nachweisanzeige.php';
    $GUI->FormObjAntr_nr=$GUI->getFormObjAntr_nr($GUI->formvars['suchantrnr']);
    $GUI->output();
  };

	$this->nachweiseZuAuftrag = function() use ($GUI){
    # echo 'Start der Zuweisung der Dokumente zum Antrag';
    # Hinzufügen von recherchierten Nachweisen zu einem Auftrag
    $GUI->nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
    $ret=$GUI->nachweis->pruefe_Auftrag_hinzufuegen_entfernen($GUI->formvars['suchantrnr']);
    if ($ret!=''){ # Fehler bei der Prüfung der Eingangsparameter
      $errmsg=$ret;
    }
    else {
      # Hinzufügen der Dokumente zum Auftrag
      $ret=$GUI->nachweis->zum_Auftrag_hinzufuegen($GUI->formvars['suchantrnr'],$GUI->formvars['id']);
      if ($ret[0]) { # Fehler beim Hinzufügen der Dokumente zum Antrag in der Datenbank
        $errmsg=$ret[1];
      }
      else {
        $okmsg=$ret[1];
      }
    }
    # Zurück zur Anzeige des Rechercheergebnisses mit Meldung über Zuordnung der Dokumente zum Auftrag
    # Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
    $GUI->formvars=$GUI->user->rolle->getNachweisParameter();
    $GUI->nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
		$ret=$GUI->nachweis->getNachweise(0,$GUI->formvars['suchpolygon'],$GUI->formvars['suchgemarkung'],$GUI->formvars['suchstammnr'],$GUI->formvars['suchrissnr'],$GUI->formvars['suchfortf'],$GUI->formvars['art_einblenden'],$GUI->formvars['richtung'],$GUI->formvars['abfrageart'], $GUI->formvars['order'],$GUI->formvars['suchantrnr'], $GUI->formvars['sdatum'], $GUI->formvars['sVermStelle'], $GUI->formvars['gueltigkeit'], $GUI->formvars['sdatum2'], $GUI->formvars['suchflur']);
    if ($ret!='') {
      $errmsg.=$ret;
    }
    # Anzeige der Rechercheergebnisse
    $GUI->nachweisAnzeige();
    if($errmsg!=''){ # Anzeig der Fehlermeldung
      showAlert($errmsg);
    }
    else { # Ohne Fehler bei der Abfrage der Dokumente Anzeige der Erfolgsmeldung
      showAlert($okmsg);
    }
  };

	$this->nachweiseZuAuftragEntfernen = function() use ($GUI){
    # nachweisobjekt erstellen
    $GUI->nachweis = new nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
    # Abfrage, ob schon Löschvorgang schon bestätigt wurde
    if($GUI->formvars['bestaetigung']=='') {
      # Löschvorgang wurde noch nicht bestätigt
      # Aufrufen eines Formulars zur Bestätigung des Löschvorganges
      $GUI->formvars['nachfrage_quelle']='Antrag_entfernen';
      $GUI->formvars['nachfrage']='Möchten sie wirklich Dokumente von der Antragsnummer: ['.$GUI->formvars['suchantrnr'].'] entfernen!';
      $GUI->bestaetigungsformAnzeigen($GUI->formvars['suchantrnr']);
    }
    else {
      if ($GUI->formvars['bestaetigung']=='JA') {
        # Löschvorgang wurde bestätigt
        # Eingabeparameter prüfen
        $ret=$GUI->nachweis->pruefe_Auftrag_hinzufuegen_entfernen($GUI->formvars['suchantrnr']);
        if($ret!=''){ # Fehler bei der Prüfung der Eingangsparameter
          $errmsg=$ret;
        }
        else {
          # Eingabeparameter in Ordnung
          # Nachweise aus Antrag entfernen
          $ret=$GUI->nachweis->aus_Auftrag_entfernen($GUI->formvars['suchantrnr'],$GUI->formvars['id']);
          $errmsg=$ret[1];
        } # ende Eingabeparameter sind ok
      } # ende Löschvorgang wurde bestätigt
      else { # Löschvorgang wurde abgebrochen
        $errmsg='Löschvorgang abgebrochen.';
      }
      # Zurück zur Anzeige des Rechercheergebnisses mit Meldung über Zuordnung der Dokumente zum Auftrag
      # Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
      $GUI->formvars=$GUI->user->rolle->getNachweisParameter();
      $GUI->nachweis = new nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
			$ret=$GUI->nachweis->getNachweise(0,$GUI->formvars['suchpolygon'],$GUI->formvars['suchgemarkung'],$GUI->formvars['suchstammnr'],$GUI->formvars['suchrissnr'],$GUI->formvars['suchfortf'],$GUI->formvars['art_einblenden'],$GUI->formvars['richtung'],$GUI->formvars['abfrageart'], $GUI->formvars['order'],$GUI->formvars['suchantrnr'], $GUI->formvars['sdatum'], $GUI->formvars['sVermStelle'], $GUI->formvars['gueltigkeit'], $GUI->formvars['sdatum2'], $GUI->formvars['suchflur']);
      $errmsg.=$ret[1];
      # Anzeige der Rechercheergebnisse
      $GUI->nachweisAnzeige();
      showAlert($errmsg);

    } # ende Bestätigung ist erfolgt
  };

	$this->nachweisDokumentAnzeigen = function() use ($GUI){
    $GUI->nachweis = new nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
    $ret=$GUI->nachweis->getDocLocation($GUI->formvars['id']);
    if($ret[0]!='') {
      showAlert($ret[0]);
      return 0;
    }
    else {
      $dateiname=basename($ret[1]);
      $dateinamensteil=explode('.',$dateiname);
      ob_end_clean();
      header("Content-type: image/".$dateinamensteil[1]);
      header("Content-Disposition: attachment; filename=".$dateiname);
      header('Expires: 0');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Pragma: public');
      readfile($ret[1]);
      ob_flush();
      return 1;
    }
  };
  
	$this->bestaetigungsformAnzeigen = function() use ($GUI){
    $GUI->menue='menue.php';
    $GUI->titel='Bestätigung';
    $GUI->main = PLUGINS.'nachweisverwaltung/view/bestaetigungsformular.php';
    $GUI->output();
  };
	
	$this->nachweisLoeschen = function() use ($GUI){
    # Abfragen ob der Löschvorgang schon bestätigt wurde.
    if ($GUI->formvars['bestaetigung']=='') {
      # Der Löschvorgang wurde noch nicht bestätigt
      $GUI->suchparameterSetzen();
      $GUI->formvars['nachfrage']='Möchten Sie den Nachweis wirklich löschen? ';
      $GUI->bestaetigungsformAnzeigen();
    }
    else {
      $GUI->nachweis = new nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
      # Abfrage ob gelöscht werden soll oder nicht
      if ($GUI->formvars['bestaetigung']=='JA') {
        # Der Löschvorgang wurde bestätigt und wird jetzt ausgeführt
        $idListe=array_keys($GUI->formvars['id']);
        $ret=$GUI->nachweis->nachweiseLoeschen($idListe,1);
        if ($ret[0]) { # Fehler beim Löschen in Fehlermeldung übergeben
          $GUI->Fehlermeldung=$ret[1];
        }
        else {
          showAlert($ret[1]);
        }
      }
      # Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
      $GUI->formvars=$GUI->user->rolle->getNachweisParameter();
      # Abfragen der Nachweise entsprechend der eingestellten Suchparameter
			$ret=$GUI->nachweis->getNachweise(0,$GUI->formvars['suchpolygon'],$GUI->formvars['suchgemarkung'],$GUI->formvars['suchstammnr'],$GUI->formvars['suchrissnr'],$GUI->formvars['suchfortf'],$GUI->formvars['art_einblenden'],$GUI->formvars['richtung'],$GUI->formvars['abfrageart'], $GUI->formvars['order'],$GUI->formvars['suchantrnr'], $GUI->formvars['sdatum'], $GUI->formvars['sVermStelle'], $GUI->formvars['gueltigkeit'], $GUI->formvars['sdatum2'], $GUI->formvars['suchflur']);
      if ($ret!='') {
        $GUI->Fehlermeldung.=$ret;
      }
      # Anzeige der Rechercheergebnisse
      $GUI->nachweisAnzeige();
    }
  };
	
	$this->rechercheFormAnzeigen = function() use ($GUI){
    # 2006-01-23 pk
    $GUI->menue='menue.php';
    # Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
    $nachweisSuchParameter=$GUI->user->rolle->getNachweisParameter();
    $GUI->formvars=array_merge($GUI->formvars,$nachweisSuchParameter);
    # erzeugen des Formularobjektes für Antragsnr
    $GUI->FormObjAntr_nr=$GUI->getFormObjAntr_nr($GUI->formvars['suchantrnr']);    
    # Wenn eine oid in formvars übergeben wurde ist es eine Änderung, sonst Neueingabe
    if ($GUI->formvars['oid']=='') {
      $GUI->titel='Dokumentenrecherche';
    }
    else {
      $GUI->titel='Dokumentenrecherche ändern';
    }
    $GUI->main= PLUGINS."nachweisverwaltung/view/dokumentenabfrageformular.php";
    
		$nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
    $GUI->dokumentarten = $nachweis->getDokumentarten();
			
	# Abfragen der Gemarkungen
    $GemeindenStelle=$GUI->Stelle->getGemeindeIDs();
    $Gemeinde=new gemeinde('',$GUI->pgdatabase);
    $GemListe=$Gemeinde->getGemeindeListe($GemeindenStelle);
    $Gemarkung=new gemarkung('',$GUI->pgdatabase);
    $GemkgListe=$Gemarkung->getGemarkungListe($GemListe['ID'],'');
    # Erzeugen des Formobjektes für die Gemarkungsauswahl
    $GUI->GemkgFormObj=new FormObject("suchgemarkung","select",$GemkgListe['GemkgID'],$GUI->formvars['suchgemarkung'],$GemkgListe['Bezeichnung'],"1","","",NULL);
		$GUI->GemkgFormObj->insertOption('',0,'--Auswahl--',0);
			
    # erzeugen des Formularobjektes für die VermessungsStellen
    $GUI->FormObjVermStelle=$GUI->getFormObjVermStelle('sVermStelle', $GUI->formvars['sVermStelle']);
    $GUI->FormObjVermStelle->insertOption('', NULL, '--- Auswahl ---', 0);    
    # aktuellen Kartenausschnitt laden + zeichnen!
    $saved_scale = $GUI->reduce_mapwidth(200);
		$GUI->loadMap('DataBase');
		if($_SERVER['REQUEST_METHOD'] == 'GET')$GUI->scaleMap($saved_scale);		# nur beim ersten Aufruf den Extent so anpassen, dass der alte Maßstab wieder da ist
    if ($GUI->formvars['CMD']!='') {
      # Nur Navigieren
      $GUI->navMap($GUI->formvars['CMD']);
      $GUI->user->rolle->saveDrawmode($GUI->formvars['always_draw']);
    }
	
    $GUI->queryable_vector_layers = $GUI->Stelle->getqueryableVectorLayers(NULL, $GUI->user->id);
    # Spaltenname und from-where abfragen
  	if(!$GUI->formvars['layer_id']){
      $layerset = $GUI->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
      $GUI->formvars['layer_id'] = $layerset[0]['Layer_ID'];
    }
    if($GUI->formvars['layer_id']){
	    $data = $GUI->mapDB->getData($GUI->formvars['layer_id']);
	    $data_explosion = explode(' ', $data);
	    $GUI->formvars['columnname'] = $data_explosion[0];
	    $select = $GUI->mapDB->getSelectFromData($data);
	    
	    # order by rausnehmen
	  	$orderbyposition = strrpos(strtolower($select), 'order by');
			$lastfromposition = strrpos(strtolower($select), 'from');
			if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
		  	$select = substr($select, 0, $orderbyposition);
	  	}
	    
	    $GUI->formvars['fromwhere'] = pg_escape_string('from ('.$select.') as foo where 1=1');
	    if(strpos(strtolower($GUI->formvars['fromwhere']), ' where ') === false){
	      $GUI->formvars['fromwhere'] .= ' where (1=1)';
	    }
    }
    $GUI->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
    $GUI->drawMap();
			
    $GUI->output();
  };

	$this->vermessungsAntragEingabeForm = function() use ($GUI){
    $GUI->menue='menue.php';
    $GUI->main= PLUGINS.'nachweisverwaltung/view/antragsnr_eingabe_form.php';
    $GUI->FormObjVermStelle=$GUI->getFormObjVermStelle('VermStelle', $GUI->formvars['VermStelle']);
    $GUI->FormObjVermArt=$GUI->getFormObjVermArt($GUI->formvars['verm_art']);
    $GUI->output();
  };
	
	$this->zoomToNachweis = function($nachweis,$border) use ($GUI){
    # Abfragen der Ausdehnung des Umringes des Nachweises
    $ret=$nachweis->getBBoxAsRectObj($nachweis->document['id'],'nachweis');
    if ($ret[0]) {
      # Fehler bei der Abfrage der BoundingBox
      # Es erfolgt keine Änderung der aktuellen Ausdehnung
    }
    else {
      $rect=$ret[1];
      # Berechnen des Randes in Abhängigkeit vom Parameter border gegeben in Prozent
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
      # Setzen der neuen Kartenausdehnung.
      #var_dump($GUI->map->extent);
      $GUI->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
	    if(MAPSERVERVERSION >= 600 ) {
				$GUI->map_scaledenom = $GUI->map->scaledenom;
			}
			else {
				$GUI->map_scaledenom = $GUI->map->scale;
			}
    }
  };
	
	$this->festpunkteZuAntragZeigen = function() use ($GUI){
    # Funktion fragt alle Festpunkte zum einem Antrag heraus und übergibt diese an die Funktion
    # zum Anzeigen der Festpunkte in der Karte
    $festpunkte=new Festpunkte('',$GUI->pgdatabase);
    $ret=$festpunkte->getFestpunkte('','','','','',$GUI->formvars['antr_selected'],'','pkz');
    if ($ret[0]) {
      $errmsg="Die Festpuntke zum Antrag $GUI->formvars['antr_selected'] konnten nicht abgefragt werden.";
    }
    else {
      if ($festpunkte->anzPunkte==0) {
        $GUI->festpunkteErgebnisanzeige();
      }
      else {
        # Zuweisen der Punktkennzeichen zu einem Array, welches von der Funktion zum Anzeigen in der Karte verwendet wird.
        foreach($festpunkte->liste AS $punkt) {
          $GUI->formvars['pkz'][$punkt['pkz']]=$punkt['pkz'];
        }
        $GUI->festpunkteZeigen();
      }
    }
  };

	$this->festpunkteZeigen = function() use ($GUI){
    $GUI->loadMap('DataBase');
    if (is_array($GUI->formvars['pkz'])) {
      $punktliste=array_keys($GUI->formvars['pkz']);
    }
    else {
      $punktliste=$GUI->formvars['pkz'];
    }
    $GUI->zoomToFestpunkte($punktliste,20);
    $GUI->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
    $GUI->drawMap();
    $GUI->output();
  };

	$this->zoomToFestpunkte = function($FestpunktListe,$border) use ($GUI){
    # Abfragen der Ausdehnung der Festpunkte in der Liste
    $Festpunkte=new Festpunkte('',$GUI->pgdatabase);
    $ret=$Festpunkte->getBBoxAsRectObj($FestpunktListe);
    if ($ret[0]) {
      # Fehler bei der Abfrag der BoundingBox
      # Es erfolgt keine Änderung der aktuellen Ausdehnung
    }
    else {
      $rect=$ret[1];
      # Berechnen des Randes in Abhängigkeit vom Parameter border gegeben in Prozent
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
      # Setzen der neuen Kartenausdehnung.
      $GUI->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
    	if(MAPSERVERVERSION >= 600 ) {
				$GUI->map_scaledenom = $GUI->map->scaledenom;
			}
			else {
				$GUI->map_scaledenom = $GUI->map->scale;
			}
    }
  };

	$this->festpunkteErgebnisanzeige = function() use ($GUI){
    $GUI->titel='Suche nach Festpunkten';
    #$GUI->main='festpunktsuchform.php';
    $GUI->qlayerset[0]['shape']=$GUI->festpunkte->liste;
    $i=0;
    $GUI->main = PLUGINS.'nachweisverwaltung/view/Festpunkte.php';
    $GUI->output();
  };

	$this->festpunkteWahl = function() use ($GUI){
    $GUI->titel='Suche nach Festpunkten';
    $GUI->main = PLUGINS.'nachweisverwaltung/view/festpunktsuchform.php';
    $GUI->output();
  };

	$this->festpunkteSuchen = function() use ($GUI){
    if ($GUI->formvars['antr_selected']=='' AND $GUI->formvars['pkz']=='' AND $GUI->formvars['kiloquad']=='') {
      $GUI->Fehlermeldung='<br>Geben Sie mindestens eine Antragsnummer, Kilometerquadrat oder Punktkennzeichen zu Suche an!';
    }
    else {
      $GUI->festpunkte=new festpunkte('',$GUI->pgdatabase);
      $ret=$GUI->festpunkte->getFestpunkte(array($GUI->formvars['pkz']),array(0,1,2,3,4,5,6),'','','',$GUI->formvars['antr_selected'],$GUI->formvars['kiloquad'],'pkz');
      if ($ret[0]) {
        $GUI->Fehlermeldung='<br>Es konnten keine Festpunkte abgefragt werden'.$ret[1];
      }
      else {
        if ($GUI->festpunkte->anzPunkte==0) {
          $GUI->Fehlermeldung='<br>Es konnten keine Festpunkte gefunden werden, bitte ändern Sie die Anfrage!';
        }
      } # ende Abfrage war erfolgreich
    }
    if ($GUI->Fehlermeldung!='') {
      $GUI->festpunkteWahl();
    }
    else {
      $GUI->festpunkteErgebnisanzeige();
    }
  };
	
	$this->sendeFestpunktskizze = function($Bild,$Pfad) use ($GUI){
    $dateiname=basename($Bild);
    $dateinamensteil=explode('.',$dateiname);
    ob_end_clean();
    if (in_array($dateinamensteil[1],array('png','jpg','gif'))) {
      header("Content-type: image/".$dateinamensteil[1]);
    }
    elseif ($dateinamensteil[1]=='pdf') {
      header("Content-type: application/pdf");
    }
    else{
    	header("Content-Disposition: attachment; filename=".$dateiname);
    }
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    readfile($Pfad.$Bild);
    ob_flush();
    return 1;
  };

	$GUI->showFestpunkteSkizze = function() use ($GUI){
    # Daten sind in Datenbank eingelesen. Herausfiltern von Fehlern

    # 1) Übergeben der Liste von Punkten, die geprüft werden sollen
    if (is_array($GUI->formvars['pkz'])) {
      $abgefragtefestpunkte=array_values($GUI->formvars['pkz']);
    }

    # 2) Abfragen der zu prüfenden Festpunkte
    $festpunkte=new Festpunkte('',$GUI->pgdatabase);
    $festpunkte->getFestpunkte($abgefragtefestpunkte,array(0,1),'','','','','','pkz');
    # 3) Übernehmen der Punkte in eine Liste, die mindestens eine Datei/Blatt haben.
    for ($i=0;$i<$festpunkte->anzPunkte;$i++) {
      $festpunkte->liste[$i]['skizze']=$festpunkte->checkSkizzen($festpunkte->liste[$i]['pkz']);
      if ($festpunkte->liste[$i]['skizze']['is_file']) {
        # Wenn mindestens eine Datei gefunden wurde, pkz in die Liste aufnehmen
        $punktnummern[]=trim(str_replace('-','',$festpunkte->liste[$i]['pkz']));
      }
    }

    # 4) Ermittlung der Kilometerquadrate, in denen Festpunkte gefunden wurden
    $kilometerquadrate=$festpunkte->getKilometerQuadrate();

    # 5) Ermitteln welche Dateien, keine Zuordnungen zu vorhandenen Festpunkten haben
    foreach ($kilometerquadrate AS $kilometerquadrat) {
      # Ausführen für jedes Kilometerquadrad
      # Es werden alle Dateien in dem für das Kiloquad vorgesehenem Verzeichnis mit png und tif Endung gesucht.
      # Es wird geprüft, ob irgend eine Datei im Verzeichnis rumliegt, die sich nicht eindeutig
      # einem Festpunkt zuordnen läßt.
      foreach (glob(PUNKTDATEIPATH.$kilometerquadrat."/*.tif") as $filename) {
        $skizzennummern[]=trim(basename ($filename,".tif"));
      }
    }
    @$GUI->skizzenohnezuordnung=array_values(array_diff($skizzennummern,$punktnummern));
    $GUI->festpunkte=$festpunkte;
    $GUI->titel="Zuordnung Festpunkte zu Einmessungsskizzen";
    $GUI->main = PLUGINS.'nachweisverwaltung/view/festpunkteskizzenzuordnung.php';
    $GUI->output();
  };

	$this->ordneFestpunktSkizzen = function() use ($GUI){
  	$_files = $_FILES;
    ####################################################
    # 1) Verschieben von Dateien, die zu Festpunkten zugeordnet waren,
    # aber jetzt neu zu anderen pkz zugeordnet werden sollen (aus oberen Formularteil)
    # Variable $name
    $Festpunkte=new Festpunkte('',$GUI->pgdatabase);
    if (!is_array($GUI->formvars['name'])) {
      $GUI->formvars['name']=array();
    }
    $vonPkz=array_keys($GUI->formvars['name']);
    $nachNameStern=array_values($GUI->formvars['name']);
    $anzZuordnungen=count($vonPkz);
    # Zerlegen von nachNameStern in Bestandteile :
    # 45601234/45601234120001.*
    # rhhhrzhz/rhhhrzhzapktnr.* davon sind
    # Pfad: 45601234/45601234120001 (rhhhrzhz/rhhhrzhzapktnr)
    # Kiloquadr: 45601234 (rhhhrzhz)
    # Name: 45601234120001 (rhhhrzhzapktnr)
    # Pkz: 45601234-1-20001 (rhhhrzhz-a-pktnr)
    for ($i=0;$i<$anzZuordnungen;$i++) {
      # extrahieren von Kilometerquadrat, Dateiname und Pfad zur Datei an Hand des Punktkennzeichens
      $vonKiloquad[$i]=substr(trim($vonPkz[$i]),0,-8);
      $vonName[$i]=str_replace('-','',trim($vonPkz[$i]));
      $vonPfad[$i]=$vonKiloquad[$i].'/'.$vonName[$i];
      # extrahieren des Punktkennzeichen, Dateinamen und Pfad zum neuen Speicherort der Datei
      $nachName[$i]=basename(substr($nachNameStern[$i],0,-2));
      $nachKiloquad[$i]=dirname($nachNameStern[$i]); # entnommen aus Verzeichnisnamen
      $nachPfad[$i]=$nachKiloquad[$i].'/'.$nachName[$i];
      $nachPkz[$i]=$nachKiloquad[$i].'-'.substr($filebasename,-6,-5).'-'.substr($nachName[$i],-5);
      # Vergleich, ob in einem Feld Änderungen vorgenommen wurden.
      if ($vonPfad[$i]!=$nachPfad[$i]) {
        if ($Festpunkte->is_valid_pfad($nachPfad[$i])) {
          echo '<br>Verschiebe '.$vonPfad[$i].'.* nach: '.$nachPfad[$i].'.*';
          $move['von']=PUNKTDATEIPATH.$vonPfad[$i];
          $move['nach']=PUNKTDATEIPATH.$nachPfad[$i];
          $moveListe[]=$move;
        }
      } # Ende Behandlung von Änderungen
    } # Ende der Schleife zum Auswählen der zu verschiebenden Dateien
    # Verschieben der zu verändernden Datein an neunen Ort
    $Festpunkte->moveFiles($moveListe);

    #################################################################
    # 2) Kopieren der hochgeladenen Dateien an die Speicherplätze, die den PKZ entsprechen.
    # Variable $_FILES
    $uploadedFiles=array_values($_files);
    $uploadedFilesPKZ=array_keys($_files);
    $anzUploadedFiles=count($uploadedFiles);
    for ($i=0;$i<$anzUploadedFiles;$i++) {
      if ($uploadedFiles[$i]['tmp_name']!='') {
        # Zusammensetzung der Dateinamen
        $pkz=substr(trim($uploadedFilesPKZ[$i]),9);
        $ext=substr(trim($uploadedFilesPKZ[$i]),0,3);
        $nachDatei=PUNKTDATEIPATH.$Festpunkte->pkz2pfad($pkz).'.'.$ext;
        if (move_uploaded_file($uploadedFiles[$i]['tmp_name'],$nachDatei)) {
          echo '<br>Lade '.$uploadedFiles[$i]['tmp_name'].' nach '.$nachDatei.' hoch';
        }
        else {
          echo '<br>Datei: '.$uploadedFiles[$i]['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
        }
      }
    }

    ############################################################
    # 3) Verschieben von Dateien, die vorher nicht zu Punkten zugeordnet werden konnten
    # Variable: $renamefile (untere Tabelle im Formular)
    if (!is_array($GUI->formvars['renamefile'])) {
      $GUI->formvars['renamefile']=array();
    }
    $vonPfad=array_keys($GUI->formvars['renamefile']);
    $nachPfadStern=array_values($GUI->formvars['renamefile']);
    $anzRenameFile=count($vonPfad);
    unset($moveListe);
    for ($i=0;$i<$anzRenameFile;$i++) {
      # extrahieren von Kilometerquadrat, Dateiname und Pfad zur Datei an Hand des Punktkennzeichens
      $nachPfad[$i]=substr($nachPfadStern[$i],0,-2);
      # Vergleich, ob in einem Feld Änderungen vorgenommen wurden.
      if ($vonPfad[$i]!=$nachPfad[$i]) {
        if ($Festpunkte->is_valid_pfad($nachPfad[$i])) {
          echo '<br>Verschiebe '.$vonPfad[$i].'.* nach: '.$nachPfad[$i].'.*';
          $move['von']=PUNKTDATEIPATH.$vonPfad[$i];
          $move['nach']=PUNKTDATEIPATH.$nachPfad[$i];
          $moveListe[]=$move;
        }
      } # Ende Behandlung von Änderungen
    } # Ende der Schleife zum Auswählen der zu verschiebenden Dateien
    # Verschieben der zu verändernden Datein an neunen Ort
    $Festpunkte->moveFiles($moveListe);

    ####################################
    # 4 Verschieben der im Formular ausgewählten nicht zuordbaren Dateinen ins Archiv.
    if (!is_array($GUI->formvars['archivfile'])) {
      $GUI->formvars['archivfile']=array();
    }
    $archivFile=array_keys($GUI->formvars['archivfile']);
    $anzArchivFile=count($archivFile);
    if ($anzArchivFile>0) {
      # Anlegen des archiv-Verzeichnisses, wenn noch nicht vorhanden
      if (!file_exists(PUNKTDATEIARCHIVPATH)) {
        mkdir(PUNKTDATEIARCHIVPATH);
      }
    }
    for ($i=0;$i<$anzArchivFile;$i++) {
      $part=explode('/',$archivFile[$i]);
      $kiloquad=trim($part[0]);
      if (!file_exists(PUNKTDATEIARCHIVPATH.$kiloquad)) {
        mkdir(PUNKTDATEIARCHIVPATH.$kiloquad);
      }
      $archiv['von']=PUNKTDATEIPATH.$archivFile[$i];
      $archiv['nach']=PUNKTDATEIARCHIVPATH.$archivFile[$i];
      $archivListe[$i]=$archiv;
    }
    # Verschieben der zu verändernden Datein ins Archiv
    $Festpunkte->moveFiles($archivListe);

    ####################################
    # 5 Löschen der im Formular ausgewählten nicht zuordbaren Dateinen.
    if (!is_array($GUI->formvars['deletefile'])) {
      $GUI->formvars['deletefile']=array();
    }
    $deleteFile=array_keys($GUI->formvars['deletefile']);
    $anzDeleteFile=count($deleteFile);
    for ($i=0;$i<$anzDeleteFile;$i++) {
      $deleteFileAbs=PUNKTDATEIPATH.$deleteFile[$i];
      if (file_exists($deleteFileAbs.'.tif')) {
        echo '<br>Lösche Datei: '.$deleteFileAbs.'.tif';
        unlink($deleteFileAbs.'.tif');
      }
      if (file_exists($deleteFileAbs.'.png')) {
        echo '<br>Lösche Datei: '.$deleteFileAbs.'.png';
        unlink($deleteFileAbs.'.png');
      }
    }
    ###################################
    $GUI->showFestpunkteSkizze();
  };

	$this->uebernehmeFestpunkte = function() use ($GUI){
    $Festpunkte=new Festpunkte(PUNKTDATEIPATH.PUNKTDATEINAME,$GUI->pgdatabase);
    $ret=$Festpunkte->uebernehmen();
    if ($ret[0]) { # Fehler bei der Aktualisierung der Festpunkte
      $GUI->Fehlermeldung=$ret[1];
    }
    else {
      $GUI->Protokoll=$ret[1];
    }
    $GUI->Festpunkte=$Festpunkte;
    $GUI->titel='Übernahme der Festpunkte';
    $GUI->main = PLUGINS.'nachweisverwaltung/view/aktualisierungfestpunkte.php';
    $GUI->output();
  };

	$this->festpunkteZuAuftragFormular = function() use ($GUI){
    $GUI->titel='Festpunkte zum Auftrag Hinzufügen';
    $GUI->main = PLUGINS.'nachweisverwaltung/view/festpunktezuauftragformular.php';
    $GUI->pkz=array_keys($GUI->formvars['pkz']);
    $GUI->anzPunkte=count($GUI->pkz);
    $GUI->FormObjAntr_nr=$GUI->getFormObjAntr_nr('');
    $GUI->FormObjAntr_nr->select['name']='antr_selected';
    $GUI->output();
  };

	$this->festpunkteZuAuftragSenden = function() use ($GUI){
    # Prüfen, ob eine Auftragsnummer mit übergeben wurde
    if ($GUI->formvars['antr_selected']=='') {
      $GUI->Fehlermeldung='Sie müssen erst eine Antragsnummer angeben.';
      $GUI->festpunkteZuAuftragFormular();
    }
    $pkz=array_keys($GUI->formvars['pkz']);
    $anzPunkte=count($pkz);
    $auftrag=new antrag($GUI->formvars['antr_selected'],$GUI->pgdatabase);
    $anzPunkteAdd=0;
    for ($i=0;$i<$anzPunkte;$i++) {
      $ret=$auftrag->addFestpunkt($pkz[$i]);
      if (!$ret[0]) {
        if (pg_affected_rows($ret[1])) {
          $anzPunkteAdd++;
        }
      }
    }
    $GUI->Meldung ='Es wurden '.$anzPunkteAdd.' Festpunkte zum Auftrag ';
    $GUI->Meldung.=$GUI->formvars['antr_selected'].' hinzugefügt!';
    $GUI->Antraege_Anzeigen();
  };

	$this->festpunkteInKVZschreiben = function() use ($GUI){
    #19.06.2008, H.Riedel; Abfrage, ob Antrag ausgewaehlt wurde
    if ($GUI->formvars['antr_selected']=='') {
      $GUI->Fehlermeldung= '<br>Wählen Sie eine Antragsnummer aus!';
    }
    else {
      $festpunkte=new Festpunkte('',$GUI->pgdatabase);
      $ret=$festpunkte->createKVZdatei($GUI->formvars['antr_selected'], $GUI->formvars['pkz']);
      if ($ret[0]) {
        $GUI->Fehlermeldung=$ret[1];
      }
      else {
        $GUI->Meldung=$ret[1];
        $GUI->datei = $ret[2];
      }
    }
  };

	$this->aktualisiereFestpunkte = function() use ($GUI){
    if (is_file(PUNKTDATEIPATH.PUNKTDATEINAME)) {
      # Datei ist vorhanden, Einlesen und Aufbereiten der Punkte in Datenbank
      $Festpunkte=new Festpunkte(PUNKTDATEIPATH.PUNKTDATEINAME,$GUI->pgdatabase);
      $ret=$Festpunkte->aktualisieren();
      if ($ret[0]) { # Fehler bei der Aktualisierung der Festpunkte
        $GUI->Fehlermeldung=$ret[1];
      }
      else {
        $GUI->Protokoll=$ret[1];
      }
    }
    else {
      $GUI->Fehlermeldung='Die Datei '.PUNKTDATEIPATH.PUNKTDATEINAME.' existiert nicht auf dem Server.';
    }
    $GUI->Festpunkte=$Festpunkte;
    $GUI->titel='Aktualisierung der Festpunkte';
    $GUI->main = PLUGINS.'nachweisverwaltung/view/aktualisierungfestpunkte.php';
    $GUI->output();
  };
	
	$this->vermessungsantragAnlegen = function() use ($GUI){
    $GUI->antrag= new antrag('',$GUI->pgdatabase);
    $ret=$GUI->antrag->pruefe_antrag_eintragen($GUI->formvars['antr_nr'],$GUI->formvars['VermStelle'],$GUI->formvars['verm_art'],$GUI->formvars['datum']);
    if($ret==''){
      $ret=$GUI->antrag->antrag_eintragen($GUI->formvars['antr_nr'],$GUI->formvars['VermStelle'],$GUI->formvars['verm_art'],$GUI->formvars['datum']);
    }
    $GUI->Meldung=$ret;
    $GUI->titel='Neuen Antrag anlegen';
    $GUI->vermessungsAntragEingabeForm();
    showAlert($ret);
  };

	$this->vermessungsantragAendern = function() use ($GUI){
    $GUI->antrag= new antrag('',$GUI->pgdatabase);
    $ret=$GUI->antrag->antrag_aendern($GUI->formvars['antr_nr'],$GUI->formvars['VermStelle'],$GUI->formvars['verm_art'],$GUI->formvars['datum']);
    if ($ret[0]) {
      $GUI->vermessungsantragsFormular();
    }
    else {
      $GUI->Antraege_Anzeigen();
    }
    showAlert($ret[1]);
  };
	
	$this->Antraege_Anzeigen = function() use ($GUI){
    $GUI->menue='menue.php';
    $GUI->titel='Antr&auml;ge';
    $GUI->main = PLUGINS.'nachweisverwaltung/view/antragsanzeige.php';
    $GUI->antrag = new antrag('',$GUI->pgdatabase);
    $GUI->antrag->getAntraege('','',$GUI->formvars['richtung'],$GUI->formvars['order']);
    $GUI->output();
  };

	$this->Antrag_Loeschen = function() use ($GUI){
    # 2006-01-30 pk
    if ($GUI->formvars['bestaetigung']=='JA') {
      $GUI->antrag = new antrag('',$GUI->pgdatabase);
      $antragsnummern=array_keys ($GUI->formvars['id']);
      $ret=$GUI->antrag->antrag_loeschen($antragsnummern[0]);
      $GUI->Antraege_Anzeigen();
      showAlert($ret);
    }
    else {
      if ($GUI->formvars['bestaetigung']=='NEIN') {
        $GUI->Antraege_Anzeigen();
      }
      else {
        #$GUI->formvars['nachfrage_quelle']='Antrag_loeschen';
        $GUI->formvars['nachfrage']='Möchten Sie den Antrag ['.$GUI->formvars['antr_nr'].'] wirklich löschen?<br>Es werden auch alle im Rechercheordner zusammengestellten Dokumente des Auftrages gelöscht!';
        $GUI->formvars['id']=$GUI->formvars['antr_nr'];
        $GUI->bestaetigungsformAnzeigen();
      }
    }
  };
	
	$this->getFormObjVermStelle = function($name, $VermStelle) use ($GUI){
    $VermStObj = new Vermessungsstelle($GUI->pgdatabase);
    $back=$VermStObj->getVermStelleListe();
    if ($back[0]=='') {
      # Fehlerfreie Datenabfrage
      $FormObjVermStelle=new FormObject($name,'select',$back[1]['id'],array($VermStelle),$back[1]['name'],1,0,0,200);
    }
    else {
      $FormObjVermStelle=new FormObject($name,'text',array($back[0]),'','',25,255,0,NULL);
    }
    return $FormObjVermStelle;
  };

	$this->getFormObjVermArt = function($verm_art) use ($GUI){
    $VermArtObj = new Vermessungsart($GUI->pgdatabase);
    $back=$VermArtObj->getVermArtListe();
    if ($back[0]=='') {
      # Fehlerfreie Datenabfrage
      $FormObjVermArt=new FormObject('verm_art','select',$back[1]['id'],array($verm_art),$back[1]['art'],1,0,0,NULL);
    }
    else {
      $FormObjVermArt=new FormObject('verm_art','text',array($back[0]),'','',25,255,0,NULL);
    }
    return $FormObjVermArt;
  };

	$this->getFormObjAntr_nr = function($antr_nr) use ($GUI){
    $Antrag = new Antrag($antr_nr,$GUI->pgdatabase);
    $back=$Antrag->getAntragsnr_Liste();
    if ($back[0]=='') {
      # Fehlerfreie Datenabfrage
      $FormObjAntr_nr=new FormObject('suchantrnr','select',$back[1]['antr_nr'],array($antr_nr),$back[1]['antr_nr'],1,0,0,NULL);
    }
    else {
      $FormObjAntr_nr=new FormObject('suchantrnr','text',array($back[0]),'','',25,255,0,NULL);
    }
    return $FormObjAntr_nr;
  };
	
	$this->sendImage = function($name,$format) use ($GUI){
    #var_dump(gd_info());
    #phpinfo();
    $im = ImageCreateFromPng($name);
    ob_end_clean();
    ob_start("output_handler");
    ImagePNG($im);
    ob_end_flush();
  };

?>