<?

	$GUI = $this;
	
	/**
	* Trigger für Bearbeitung im GLE
	*/
	$this->trigger_functions['check_documentpath'] = function($fired, $event, $layer = '', $oid = 0, $old_dataset = array()) use ($GUI) {
		$executed = true;
		$success = true;

		switch(true) {
			case ($fired == 'AFTER' AND $event == 'UPDATE') : {
				$nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
				$nachweis->check_documentpath($old_dataset);
			} break;

			default : {
				$executed = false;
			}
		}
		return array('executed' => $executed, 'success' => $success);
	};	

	$this->Datei_Download = function($filename) use ($GUI){
    $GUI->formvars['filename'] = $filename;
    $GUI->titel='Datei-Download';
		$GUI->main= PLUGINS.'nachweisverwaltung/view/dateidownload.php';
    $GUI->output();
  };
	
	$this->DokumenteOrdnerPacken = function() use ($GUI){
    if ($GUI->formvars['antr_selected']!=''){			
			$explosion = explode('~', $GUI->formvars['antr_selected']);
			$antr_selected = $explosion[0];
			$stelle_id = $explosion[1];
      $GUI->antrag=new antrag($antr_selected,$stelle_id,$GUI->pgdatabase);
			$GUI->antrag->getAntraege(array($antr_selected),'','','',$stelle_id);
			$GUI->antrag->searches = $GUI->Suchparameter_abfragen($antr_selected, $stelle_id);
			$antragsnr = $GUI->antrag->nr;
			if($stelle_id != '')$antragsnr.='~'.$stelle_id;
      if(is_dir(RECHERCHEERGEBNIS_PATH.$antragsnr)){
        chdir(RECHERCHEERGEBNIS_PATH);
				$GUI->formvars['Riss-Nummer'] = 1;
				$GUI->formvars['Antrags-Nummer'] = 1;
				$GUI->formvars['FFR'] = 1;
				$GUI->formvars['KVZ'] = 1;
				$GUI->formvars['GN'] = 1;
				$GUI->formvars['andere'] = 1;
				$GUI->formvars['Datum'] = 1;
				$GUI->formvars['Datei'] = 1;
				$GUI->formvars['gemessendurch'] = 1;
				$GUI->formvars['Gueltigkeit'] = 1;		
				$timestamp = date('Y-m-d_H-i-s',time());
				if($GUI->nachweis->Dokumente != NULL){		# wenn es Nachweise zu diesem Auftrag gibt
					$GUI->erzeugenUebergabeprotokollNachweise(RECHERCHEERGEBNIS_PATH.$antragsnr.'/Protokolle/Uebergabeprotokoll.pdf');
					$GUI->erzeugenUebersicht_HTML(RECHERCHEERGEBNIS_PATH.$antragsnr.'/Protokolle/Uebersicht.htm');
					$GUI->erzeugenUebersicht_CSV(RECHERCHEERGEBNIS_PATH.$antragsnr.'/Protokolle/Uebersicht.csv');
					#$GUI->erzeugenZuordnungFlst_CSV(RECHERCHEERGEBNIS_PATH.$antragsnr.'/Protokolle/');
					$GUI->create_Recherche_UKO(RECHERCHEERGEBNIS_PATH.$antragsnr.'/Protokolle/');
					$GUI->nachweis->create_Gesamtpolygon(RECHERCHEERGEBNIS_PATH.$antragsnr.'/Protokolle/Gesamtpolygon/');
					$GUI->nachweis->writeIgnoredDokumentarten(RECHERCHEERGEBNIS_PATH.$antragsnr.'/Protokolle/');
					# Loggen der übergebenen Dokumente
					$uebergabe_logpath = $GUI->antrag->create_uebergabe_logpath($GUI->Stelle->Bezeichnung).'/'.$antr_selected.'_'.$timestamp.'.pdf';
					$GUI->erzeugenUebergabeprotokollNachweise($uebergabe_logpath, true);
				}
        $result = exec(ZIP_PATH.' -r '.RECHERCHEERGEBNIS_PATH.$antragsnr.' '.'./'.$antragsnr);
      }
    }
    $filename = RECHERCHEERGEBNIS_PATH.$antragsnr.'.zip';
		$dateiname = $GUI->antrag->nr.'_'.date('Y-m-d_H-i-s',time()).'.zip';
    $tmpfilename = copy_file_to_tmp($filename, $dateiname);
    unlink($filename);
    return $tmpfilename;
  };
	
	$this->DokumenteZumAntragInOrdnerZusammenstellen = function() use ($GUI){
    if ($GUI->formvars['antr_selected']!=''){
      if(strpos($GUI->formvars['antr_selected'], '~') == false)$GUI->formvars['antr_selected'] = str_replace('|', '~', $GUI->formvars['antr_selected']); # für Benutzung im GLE
			$explosion = explode('~', $GUI->formvars['antr_selected']);
			$antr_selected = $explosion[0];
			$stelle_id = $explosion[1];
      $antrag=new antrag($antr_selected,$stelle_id,$GUI->pgdatabase);
      $msg = $antrag->clearRecherchePfad();			
      # Zusammenstellen der Dokumente der Nachweisverwaltung
      $GUI->nachweis=new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
      $ret=$GUI->nachweis->getNachw2Antr($antr_selected,$stelle_id);
      if($ret==''){
        $ret=$GUI->nachweis->getNachweise($GUI->nachweis->nachweise_id,'','','','','','','','multibleIDs','','');
        if ($ret==''){
          $ret=$antrag->DokumenteInOrdnerZusammenstellen($GUI->nachweis);
          $msg.=$ret;
        }
      }

# 2016-11-03 H.Riedel - pkz durch pkn ersetzt
      # Zusammenstellen der Einmessungsskizzen der Festpunkte
      $festpunkte=new Festpunkte('',$GUI->pgdatabase);
      $ret=$festpunkte->getFestpunkte('',array('TP','AP','SiP','SVP'),'','','',$antr_selected,$stelle_id,'','pkn');
      if ($ret[0]) {
        $errmsg="Festpunkte konnten nicht abgefragt werden.";
      }
      elseif(count($festpunkte->liste) > 0){
        $ret=$antrag->EinmessungsskizzenInOrdnerZusammenstellen($festpunkte);
        $msg.=$ret;
      }
      # Schreiben des Koordinatenverzeichnisses der zugeordneten Festpunkte
      $GUI->festpunkteInKVZschreiben();
    }
    else {
      $ret='Geben Sie bitte die entspechende Antragsnummer an';
    }
    return $msg;
  };

	$this->nachweisAenderungsformular = function() use ($GUI){
		include_once(CLASSPATH.'FormObject.php');
    #2005-11-25_pk
    # Anzeige des Formulars zum Eintragen neuer/Ändern vorhandener Metadaten zu einem Nachweisdokument
    # (FFR, KVZ oder GN)
    $GUI->menue='menue.php';
    $GUI->main= PLUGINS.'nachweisverwaltung/view/dokumenteneingabeformular.php';
    $GUI->titel='Dokument überarbeiten';    
		if($GUI->formvars['reset_layers'])$GUI->reset_layers(NULL);
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
			$GUI->add_message('error', $ret);
    }
    else {
      # Abfrage war erfolgreich
      $nachweis->document=$nachweis->Dokumente[0];
      # Laden der letzten Karteneinstellung
      $saved_scale = $GUI->reduce_mapwidth(100);
			if($GUI->formvars['neuladen']){
				$GUI->neuLaden();
			}
			else{
				$GUI->loadMap('DataBase');
			}
			if($saved_scale != NULL)$GUI->scaleMap($saved_scale);		# nur beim ersten Aufruf den Extent so anpassen, dass der alte Maßstab wieder da ist
      # zoomToMaxLayerExtent
			if($GUI->formvars['zoom_layer_id'] != '')$GUI->zoomToMaxLayerExtent($GUI->formvars['zoom_layer_id']);
      $GUI->queryable_vector_layers = $GUI->Stelle->getqueryableVectorLayers(NULL, $GUI->user->id, NULL, NULL, NULL, true);
	    if(!$GUI->formvars['layer_id']){
	      $layerset = $GUI->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
	      $GUI->formvars['layer_id'] = $layerset[0]['Layer_ID'];
	    }
	    # Spaltenname und from-where abfragen
	    if($GUI->formvars['layer_id']){
		    $data = $GUI->mapDB->getData($GUI->formvars['layer_id']);
		    $data_explosion = explode(' ', $data);
		    $GUI->formvars['columnname'] = $data_explosion[0];
		    $select = $fromwhere = $GUI->mapDB->getSelectFromData($data);
				# order by rausnehmen
				$GUI->formvars['orderby'] = '';
				$orderbyposition = strrpos(strtolower($select), 'order by');
				$lastfromposition = strrpos(strtolower($select), 'from');
				if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
					$fromwhere = substr($select, 0, $orderbyposition);
					$GUI->formvars['orderby'] = ' '.substr($select, $orderbyposition);
				}
				$GUI->formvars['fromwhere'] = pg_escape_string('from ('.$fromwhere.') as foo where 1=1');
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
				$GUI->formvars['firstpoly'] = 'true';
				$this->geomload = true;			# Geometrie wird das erste Mal geladen, deshalb nicht in den Weiterzeichnenmodus gehen
      }
      else{
				$GUI->add_message('error', 'Achtung! Nachweis hat noch keine Geometrie!');
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
			$GUI->formvars['bemerkungen_intern']=$nachweis->document['bemerkungen_intern'];

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
		if(strpos($GUI->formvars['antr_selected'], '~') == false)$GUI->formvars['antr_selected'] = str_replace('|', '~', $GUI->formvars['antr_selected']); # für Benutzung im GLE
    $GUI->formvars['suchffr']=1;
    $GUI->formvars['suchkvz']=1;
    $GUI->formvars['suchgn']=1;
    $GUI->formvars['suchan']=1;
    $GUI->formvars['suchantrnr']=$GUI->formvars['antr_selected'];
    $GUI->formvars['abfrageart']='antr_nr';
    $GUI->nachweiseRecherchieren();
  };

	$this->setNachweisSuchparameter = function($stelle_id, $user_id, $suchffr,$suchkvz,$suchgn,$suchan,$abfrageart,$suchgemarkung,$suchflur,$stammnr,$stammnr2,$suchrissnr,$suchrissnr2,$suchfortf,$suchpolygon,$suchantrnr, $sdatum, $sdatum2, $svermstelle) use ($GUI){
		$sql ='UPDATE rolle_nachweise SET ';
		if ($suchffr!='') { $sql.='suchffr="'.$suchffr.'",'; }else{$sql.='suchffr="0",';}
		if ($suchkvz!='') { $sql.='suchkvz="'.$suchkvz.'",'; }else{$sql.='suchkvz="0",';}
		if ($suchgn!='') { $sql.='suchgn="'.$suchgn.'",'; }else{$sql.='suchgn="0",';}
		if ($suchan!='') { $sql.='suchan="'.$suchan.'",'; }else{$sql.='suchan="0",';}
		if ($abfrageart!='') { $sql.='abfrageart="'.$abfrageart.'",'; }
		$sql.='suchgemarkung="'.$suchgemarkung.'",';
		$sql.='suchflur="'.$suchflur.'",';
		$sql.='suchstammnr="'.$stammnr.'",';
		$sql.='suchstammnr2="'.$stammnr2.'",';
		$sql.='suchrissnr="'.$suchrissnr.'",';
		$sql.='suchrissnr2="'.$suchrissnr2.'",';
		if($suchfortf == '')$suchfortf = 'NULL';
		$sql.='suchfortf='.$suchfortf.',';
		if ($suchpolygon!='') { $sql.='suchpolygon="'.$suchpolygon.'",'; }
		if ($suchantrnr!='') { $sql.='suchantrnr="'.$suchantrnr.'",'; }
		$sql.='sdatum="'.$sdatum.'",';
		$sql.='sdatum2="'.$sdatum2.'",';
		if ($svermstelle!='') { $sql.='sVermStelle='.$svermstelle.','; }else{$sql.='sVermStelle= NULL,' ;}
		$sql .= 'user_id = '.$user_id;
		$sql.=' WHERE user_id='.$user_id.' AND stelle_id='.$stelle_id;
		$GUI->debug->write("<p>file:users.php class:rolle->setNachweisSuchparameter - Setzen der aktuellen Parameter für die Nachweissuche",4);
		$GUI->database->execSQL($sql,4, 1);
		return 1;
	};

	$this->setNachweisAnzeigeparameter = function($stelle_id, $user_id, $showffr,$showkvz,$showgn,$showan,$markffr,$markkvz,$markgn) use ($GUI){
		$sql ='UPDATE rolle_nachweise SET ';
		if ($showffr!='') { $sql.='showffr="'.$showffr.'",'; }else{$sql.='showffr="0",';}
		if ($showkvz!='') { $sql.='showkvz="'.$showkvz.'",'; }else{$sql.='showkvz="0",';}
		if ($showgn!='') { $sql.='showgn="'.$showgn.'",'; }else{$sql.='showgn="0",';}
		if ($showan!='') { $sql.='showan="'.$showan.'",'; }else{$sql.='showan="0",';}
		if ($markffr!='') { $sql.='markffr="'.$markffr.'",'; }
		if ($markkvz!='') { $sql.='markkvz="'.$markkvz.'",'; }
		if ($markgn!='') { $sql.='markgn="'.$markgn.'",'; }
		$sql .= 'user_id = '.$user_id;
		$sql.=' WHERE user_id='.$user_id.' AND stelle_id='.$stelle_id;
		$GUI->debug->write("<p>file:users.php class:rolle->setNachweisAnzeigeparameter - Setzen der aktuellen Anzeigeparameter für die Nachweissuche",4);
		$GUI->database->execSQL($sql,4, 1);
		return 1;
	};

	$this->getNachweisParameter = function($stelle_id, $user_id) use ($GUI){
		$sql ='SELECT user_id FROM rolle_nachweise';
		$sql.=' WHERE user_id='.$user_id.' AND stelle_id='.$stelle_id;
		$GUI->debug->write("<p>file:users.php class:user->getNachweisParameter - Abfragen der aktuellen Parameter für die Nachweissuche<br>".$sql,4);
		$query=mysql_query($sql,$GUI->database->dbConn);
		if ($query==0) { $GUI->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		if (mysql_num_rows($query)==0) {
			$sql ='INSERT INTO rolle_nachweise ';
			$sql.='SET user_id='.$user_id.', stelle_id='.$stelle_id;
			$GUI->debug->write("<p>file:users.php class:user->getNachweisParameter - Abfragen der aktuellen Parameter für die Nachweissuche<br>".$sql,4);
			$query=mysql_query($sql,$GUI->database->dbConn);
		}
		$sql ='SELECT *,CONCAT(showffr,showkvz,showgn,showan) AS art_einblenden';
		$sql.=',CONCAT(markffr,markkvz,markgn) AS art_markieren FROM rolle_nachweise';
		$sql.=' WHERE user_id='.$user_id.' AND stelle_id='.$stelle_id;
		$GUI->debug->write("<p>file:users.php class:user->getNachweisParameter - Abfragen der aktuellen Parameter für die Nachweissuche<br>".$sql,4);
		$query=mysql_query($sql,$GUI->database->dbConn);
		$rs=mysql_fetch_assoc($query);
		return $rs;
	};
	
	$this->save_Dokumentauswahl = function($stelle_id, $user_id, $formvars) use ($GUI){
		if(!$formvars['suchan'])$formvars['such_andere_art'] = array();
		if($formvars['suchffr'] == '')$formvars['suchffr'] = 0;
		if($formvars['suchkvz'] == '')$formvars['suchkvz'] = 0;
		if($formvars['suchgn'] == '')$formvars['suchgn'] = 0;
		$sql ='INSERT INTO rolle_nachweise_dokumentauswahl (stelle_id, user_id, name, ffr, kvz, gn, andere) VALUES (';
		$sql .= $stelle_id.', '.$user_id.', "'.$formvars['dokauswahl_name'].'", '.$formvars['suchffr'].', '.$formvars['suchkvz'].', '.$formvars['suchgn'].', "'.implode(',', $formvars['such_andere_art']).'")';
		#echo $sql;
		$GUI->debug->write("<p>file:users.php class:rolle->save_Dokumentauswahl ",4);
		$GUI->database->execSQL($sql,4, 1);
		return mysql_insert_id();
	};
	
	$this->delete_Dokumentauswahl = function($id) use ($GUI){
		$sql ='DELETE FROM rolle_nachweise_dokumentauswahl ';
		$sql.='WHERE id='.$id;
		#echo $sql;
		$GUI->debug->write("<p>file:users.php class:rolle->delete_Dokumentauswahl ",4);
		$GUI->database->execSQL($sql,4, 1);
	};
		
	$this->get_Dokumentauswahl = function($stelle_id, $user_id, $dokauswahl_id) use ($GUI){
		$sql ='SELECT * FROM rolle_nachweise_dokumentauswahl ';
		$sql.='WHERE user_id='.$user_id.' AND stelle_id='.$stelle_id;
		if($dokauswahl_id != '')$sql.=' AND id = '.$dokauswahl_id;
		#echo $sql;
		$GUI->debug->write("<p>file:users.php class:rolle->get_Dokumentauswahl ",4);
		$query=mysql_query($sql,$GUI->database->dbConn);
		if ($query==0) { $GUI->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		while($rs=mysql_fetch_assoc($query)){
			$dokauswahlen[] = $rs;
		}
		return $dokauswahlen;
	};
	
	$this->create_Recherche_UKO = function($pfad) use ($GUI){
		$searches = $GUI->antrag->searches;
		foreach($searches as $params){
			if($params['abfrageart'] == 'poly')$polys[] = "st_geometryfromtext('".$params['suchpolygon']."', 25833)";
		}
		$sql = "select st_astext(st_multi(st_union(ARRAY[".implode(',', $polys)."])))";
		$ret = $GUI->pgdatabase->execSQL($sql, 4, 1);
    $rs=pg_fetch_row($ret[1]);
		$uko = WKT2UKO($rs[0]);
		$ukofile = 'Recherche.uko';
		$fp = fopen($pfad.$ukofile, 'w');
		fwrite($fp, $uko);
		fclose($fp);
	};
		
	$this->Suchparameter_loggen = function($formvars, $stelle_id, $user_id) use ($GUI){
		$sql ='INSERT INTO u_consumeNachweise SELECT ';
		$sql.='"'.$formvars['suchantrnr'].'", ';
		$sql.=$stelle_id.', ';
		$sql.='"'.date('Y-m-d H:i:s',time()).'", ';
		$sql.='`suchffr`, `suchkvz`, `suchgn`, `suchan`, `abfrageart`, `suchgemarkung`, `suchflur`, `suchstammnr`, `suchstammnr2`, `suchrissnr`, `suchrissnr2`, `suchfortf`, `suchpolygon`, `suchantrnr`, `sdatum`, `sdatum2`, `sVermStelle`,';
		if($formvars['flur_thematisch']!='') { $sql.='"'.$formvars['flur_thematisch'].'",'; }else{$sql.='"0",';}
		$sql.='"'.$formvars['such_andere_art'].'"';
		$sql.=' FROM rolle_nachweise';
		$sql.=' WHERE user_id='.$user_id.' AND stelle_id='.$stelle_id;
		$GUI->debug->write("<p>file:users.php class:rolle->Suchparameter_loggen - Setzen der aktuellen Parameter für die Nachweissuche",4);
		$GUI->database->execSQL($sql,4, 1);
	};
	
	$this->Suchparameter_loeschen = function($antrag_nr, $stelle_id) use ($GUI){
		$sql ="DELETE FROM u_consumeNachweise WHERE antrag_nr = '".$antrag_nr."' AND stelle_id = ".$stelle_id;
		$GUI->debug->write("<p>file:users.php class:rolle->Suchparameter_loeschen - Löschen der Parameter für die Nachweissuche",4);
		$GUI->database->execSQL($sql,4, 1);
	};
	
	$this->Suchparameter_abfragen = function($antrag_nr, $stelle_id) use ($GUI){		
		$sql = "SELECT * FROM u_consumeNachweise ";
		$sql.= "WHERE antrag_nr='".$antrag_nr."' AND stelle_id=".$stelle_id;
		$GUI->debug->write("<p>file:users.php class:user->Suchparameter_anhaengen_PDF <br>".$sql,4);
		$query=mysql_query($sql,$GUI->database->dbConn);
		if ($query==0) { $GUI->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		while($rs = mysql_fetch_assoc($query)){
			$searches[] = $rs;
		}
		return $searches;
	};	
	
	$this->Suchparameter_anhaengen_PDF = function($pdf, $antrag_nr, $stelle_id) use ($GUI){
		$row = 0;
		$options = array('aleft'=>30, 'right'=>30, 'justification'=>'left');
		$searches = $GUI->antrag->searches;
		foreach($searches as $params){
			switch ($params['abfrageart']){
				case 'indiv_nr' : {
					$keys = array('suchffr'=>0, 'suchkvz'=>0, 'suchgn'=>0, 'suchan'=>0, 'suchgemarkung'=>0, 'suchflur'=>0, 'suchstammnr'=>0, 'suchstammnr2'=>0, 'suchrissnr'=>0, 'suchrissnr2'=>0, 'suchfortf'=>0, 'sdatum'=>0, 'sdatum2'=>0, 'sVermStelle'=>0, 'flur_thematisch'=>0, 'such_andere_art'=>0);
				}break;
				case 'poly' : {
					$keys = array('suchffr'=>0, 'suchkvz'=>0, 'suchgn'=>0, 'suchan'=>0, 'suchpolygon'=>0, 'such_andere_art'=>0);
				}break;
				case 'antr_nr' : {
					$keys = array('suchffr'=>0, 'suchkvz'=>0, 'suchgn'=>0, 'suchan'=>0, 'suchantrnr'=>0, 'such_andere_art'=>0);
				}break;				
			}
			if($row < 100){
				$pdf->ezNewPage();
				$row = 800;				
			}
			$pdf->ezText('<b>Suche '.$params['time_id'].': '.$params['abfrageart'].'</b>', 14, $options);
			$params = json_encode(array_intersect_key($params, $keys));
			$pdf->ezText($params, 12, $options);
			$pdf->ezText(' ', 12, $options);
		}		
		return $pdf;
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
    $GUI->setNachweisSuchparameter($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id, $GUI->formvars['suchffr'],$GUI->formvars['suchkvz'],$GUI->formvars['suchgn'], $GUI->formvars['suchan'], $GUI->formvars['abfrageart'],$GUI->formvars['suchgemarkung'],$GUI->formvars['suchflur'],$GUI->formvars['suchstammnr'],$GUI->formvars['suchstammnr2'],$GUI->formvars['suchrissnr'],$GUI->formvars['suchrissnr2'],$GUI->formvars['suchfortf'],$GUI->formvars['suchpolygon'],$GUI->formvars['suchantrnr'], $GUI->formvars['sdatum'],$GUI->formvars['sdatum2'], $GUI->formvars['sVermStelle']);
    # Die Anzeigeparameter werden so gesetzt, daß genau das gezeigt wird, wonach auch gesucht wurde.
    # bzw. was als Suchparameter im Formular angegeben wurde.
    $GUI->setNachweisAnzeigeparameter($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id, $GUI->formvars['suchffr'],$GUI->formvars['suchkvz'],$GUI->formvars['suchgn'],$GUI->formvars['suchan'],$GUI->formvars['suchffr'],$GUI->formvars['suchkvz'],$GUI->formvars['suchgn']);
    # Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
    $GUI->formvars = array_merge($GUI->formvars, $GUI->getNachweisParameter($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id));
    # Nachweisobjekt bilden
    $GUI->nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
    # Suchparameter in Ordnung
    # Recherchieren nach den Nachweisen
		if($GUI->formvars['such_andere_art'] != NULL)$GUI->formvars['such_andere_art'] = implode(',', $GUI->formvars['such_andere_art']);
		$ret=$GUI->nachweis->getNachweise(0,$GUI->formvars['suchpolygon'],$GUI->formvars['suchgemarkung'],$GUI->formvars['suchstammnr'],$GUI->formvars['suchrissnr'],$GUI->formvars['suchfortf'],$GUI->formvars['art_einblenden'],$GUI->formvars['richtung'],$GUI->formvars['abfrageart'], $GUI->formvars['order'],$GUI->formvars['suchantrnr'], $GUI->formvars['sdatum'], $GUI->formvars['sVermStelle'], $GUI->formvars['gueltigkeit'], $GUI->formvars['sdatum2'], $GUI->formvars['suchflur'], $GUI->formvars['flur_thematisch'], $GUI->formvars['such_andere_art'], $GUI->formvars['suchbemerkung'], NULL, $GUI->formvars['suchstammnr2'], $GUI->formvars['suchrissnr2'], $GUI->formvars['suchfortf2']);
    #$GUI->nachweis->getAnzahlNachweise($GUI->formvars['suchpolygon']);
    if($ret!=''){
      # Fehler bei der Recherche im Datenbestand
			$GUI->add_message('error', $ret);
      $GUI->rechercheFormAnzeigen();
    }
    else {
      # Recherche erfolgreich verlaufen
      if ($GUI->nachweis->erg_dokumente==0) {
        # Keine Dokumente zur Auswahl gefunden.
				$GUI->add_message('error', 'Es konnten keine Dokumente zu der Auswahl gefunden werden. Wählen Sie neue Suchparameter.');
        $GUI->rechercheFormAnzeigen();				
      }
      else {
        # Anzeige des Rechercheergebnisses
        $GUI->nachweisAnzeige();
      }
    }
  };
	
	$this->zusammenstellenUebergabeprotokollNachweise = function($antr_nr) use ($GUI){
    if ($antr_nr==''){
			$GUI->add_message('error', 'Wählen Sie bitte eine Antragsnummer aus!');
      $GUI->Antraege_Anzeigen();
    }
    else{
			$explosion = explode('~', $antr_nr);
			$antr_nr = $explosion[0];
			$stelle_id = $explosion[1];
      $GUI->antrag = new antrag($antr_nr,$stelle_id,$GUI->pgdatabase);
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
		
	$this->erzeugenZuordnungFlst_CSV = function($path) use ($GUI){
		$intersections = $GUI->antrag->getIntersectedFlst();
		$csv = utf8_decode('Flur;Antragsnummer;Rissnummer;'.(NACHWEIS_SECONDARY_ATTRIBUTE != '' ? NACHWEIS_SECONDARY_ATTRIBUTE.';' : '').'Flurstück;Anteil [m²];Anteil [%]').chr(10);
		foreach($intersections as $intersection){
			$csv .= implode(';', $intersection).chr(10);
		}
		$fp=fopen($path.'Zuordnung-Flurstuecke.csv','wb');
		fwrite($fp, $csv);
		fclose($fp);
	};
	
	$this->erzeugenUebersicht_CSV = function($path) use ($GUI){
		$columns['id'] = 'id';
		$columns['flurid'] = 'Flur';
		$columns['stammnr'] = 'Antragsnummer';
		$columns['blattnummer'] = 'Blattnummer';
		$columns['rissnummer'] = 'Rissnummer';
		$columns['art_name'] = 'Art';
		$columns['datum'] = 'Datum';
		$columns['fortfuehrung'] = 'Fortführung';
		$columns['vermst'] = 'Vermessungsstelle';
		$columns['gueltigkeit'] = 'Gültigkeit';
		$columns['format'] = 'Format';
		$columns['dokument_path'] = 'Dokument';
		foreach($columns as $key=>$column){
			$csv .= utf8_decode($column).';';
		}
		$csv.= chr(10);
		foreach($GUI->nachweis->Dokumente as $nachweis){
			foreach($columns as $key=>$column){
				if($key != 'dokument_path'){
					$strpos = strpos($nachweis[$key], '/');
					if ($strpos !== false AND $strpos < 3) {		# Excel-Datumsproblem
						$nachweis[$key] = $nachweis[$key]."\t";
					}
				}
				if($key == 'dokument_path' AND $nachweis[$key] != ''){
					$csv .= '"=HYPERLINK(""'.$nachweis[$key].'"";""'.basename($nachweis[$key]).'"")"';
				}
				else $csv .= utf8_decode($nachweis[$key]).';';
			}
			$csv.= chr(10);
		}
		$fp=fopen($path,'wb');
		fwrite($fp, $csv);
		fclose($fp);
	};
  
	$this->erzeugenUebersicht_HTML = function($path) use ($GUI){
		$nachweise_json = json_encode($GUI->nachweis->Dokumente);
		$html = "
<html>
	<head>
		<meta http-equiv=Content-Type content=\"text/html; charset=UTF-8\">
		<style>
			body{
				font-family: \"Trebuchet MS\", Helvetica, sans-serif;
			}
			table{
				border-collapse: collapse;
			}
			td, th{
				border: 1px solid #aaaaaa;
				border-left: 1px solid #dddddd;
				border-right: 1px solid #dddddd;
				padding: 2px;
				font-size: 15px;
				}
			th{
				background: rgba(0, 0, 0, 0) linear-gradient(rgb(218, 228, 236) 0%, lightsteelblue 100%);
			}
			input[type=\"text\"]{
				font-size: 15px;
				line-height: 15px;
			}			
			select{
				height: 20px;
			}
			a{
				border: medium none;
				color: firebrick;
				font-size: 15px;
				outline: medium none;
				text-decoration: none;
			}
			a:hover{
				color: black;
			}
			#order_div, #nachweise_table, #filter_div, #head_div {
				margin: 10px;
			}
			#nachweise_table{
				border: 1px solid #aaaaaa;
				display: inline-block;
			}				
			#head_div {
				font-weight: bold;
				font-size: 14px;
			}
			#head_div #lk {
				font-size: 20px;
				margin-bottom: 5px;
			}
			#filter_div div{
				border: 1px solid grey;
				width: 800px;
				padding: 3px;
			}
			.removeFilter{
				float: right;
				cursor: pointer;
			}
			#order_output{
				width: 400px;
				border: none;
			}
			#preview_image{
				position: fixed;
				top: 30px;
				left: 30px;
			}
			#preview_image img{
				max-width: 600px;
				box-shadow: 10px 9px 11px #777;
			}
			.options{
				margin: 4 2 5 10;
				padding: 1px;
				color: grey;
				border: 1px solid lightgrey;
				border-radius: 3px;
				font-size: 10px;
				line-height: 10px;
				float: right;
			}
			.options:hover{
				border: 1px solid grey;
				color: black;
				cursor: pointer;
			}
			#filterform{
				padding: 0px;
				background-color: white;
				border: 1px solid grey;
				position: absolute;
				box-shadow: 10px 9px 11px #777;
			}
			#filterform .headline{
				padding: 2 5;
				color: black;
				background: rgba(0, 0, 0, 0) linear-gradient(rgb(218, 228, 236) 0%, lightsteelblue 100%);
				line-height: 20px;
			}
			#filterform .content{
				padding: 5px;
			}
			#filterform .close{
				float: right;
				cursor: pointer;
			}
			.filter_button{
				margin-top: 10px;
			}
		</style>
		<SCRIPT TYPE=\"text/javascript\">
			var nachweise = JSON.parse('".$nachweise_json."');
			
			var columns = new Array();
			columns['id'] = 'ID';
			columns['flurid'] = 'Flur';
			columns['stammnr'] = 'Antragsnummer';
			columns['blattnummer'] = 'Blattnummer';
			columns['rissnummer'] = 'Rissnummer';
			columns['art_name'] = 'Art';
			columns['datum'] = 'Datum';
			columns['fortfuehrung'] = 'Fortführung';
			columns['vermst'] = 'Vermessungsstelle';
			columns['gueltigkeit'] = 'Gültigkeit';
			columns['format'] = 'Format';
			columns['dokument_path'] = 'Dokument';
			
			var filters = new Array();
			
			var _table_ = document.createElement('table'),
					_tr_ = document.createElement('tr'),
					_th_ = document.createElement('th'),
					_td_ = document.createElement('td');
								
			function buildHtmlTable(arr) {
				var table = _table_.cloneNode(false);
				var tr = _tr_.cloneNode(false);
				for(var key in columns){		// Ueberschriften
					var th = _th_.cloneNode(false);
					a = document.createElement('a');
					a.href = 'javascript:changeOrder(\''+key+'\');';
					a.title = 'sortieren nach '+columns[key];
					a.innerHTML = columns[key];
					th.appendChild(a);
					tr.appendChild(th);
					table.appendChild(tr);
				}
				for(var i=0; i < arr.length; ++i){		// Datenzeilen
					if(!arr[i]['filtered']){
						var tr = _tr_.cloneNode(false);
						for(var key in columns){
							var value = arr[i][key];
							var td = _td_.cloneNode(false);
							if(key == 'dokument_path' && value != null){
								path_parts = value.split('/');
								filename = path_parts[path_parts.length-1];
								a = document.createElement('a');
								a.href = value;
								a.target = '_blank';
								a.setAttribute('onmouseover', \"showPreview('\"+filename+\"')\");
								a.onmouseout = function(){hidePreview()};
								a.innerHTML = filename;
								cellcontent = a;
							}
							else cellcontent = document.createTextNode(value || '');
							td.appendChild(cellcontent);
							options = document.createElement('input');
							options.className='options';
							options.type='button';
							options.setAttribute('onclick', \"showFilterForm(this.parentNode, '\"+key+\"', '\"+value+\"')\");
							options.value= '\u25BD';	// 2630
							td.appendChild(options);
							tr.appendChild(td);
						}
						table.appendChild(tr);
					}
				}
				return table;
			}					
			
			function showPreview(filename){
				file_parts = filename.split('.');								
				preview_image = file_parts[0]+'_thumb.jpg';
				document.getElementById('preview_image').innerHTML = '<img src=\"../Vorschaubilder/'+preview_image+'\">';
			}
			
			function hidePreview(){
				document.getElementById('preview_image').innerHTML = '';
			}
			
			function changeOrder(column){
				var found = false;
				var orderstring = document.getElementById('order').value;
				var order_output = new Array();
				if(orderstring == '')var order_columns = new Array();
				else var order_columns = orderstring.split(';');
				for(var i = 0; i < order_columns.length; i++){
					if(order_columns[i] == column){	// wenn schon im order-String vorhanden -> entfernen
						order_columns.splice(i, 1);
						found = true;
					}
				}
				if(found == false)order_columns.push(column);		// zum order-String hinzufuegen
				for(var key in order_columns){
					order_output.push(columns[order_columns[key]]);
				}
				document.getElementById('order').value = order_columns.join(';');
				document.getElementById('order_output').value = order_output.join(', ');
				nachweise.sort(sortByColumns(order_columns));
				output();
			}
			
			function sortByColumns(order_columns){
				return function(a, b){
					for(var col in order_columns){
						var ax = a[order_columns[col]];
						var bx = b[order_columns[col]];
						if(ax != bx)return (ax < bx) ? -1 : 1;
					}
				}
			}
								
			function showFilterForm(td, key, value){
				hideFilterForm();			
				div = document.createElement('div');
				div.id = 'filterform';
				div.innerHTML = '<div class=\"headline\">Zeilen filtern<a class=\"close\" onclick=\"hideFilterForm();\">\u274C</a></div><div class=\"content\"><input id=\"filter_key\" value=\"'+key+'\" type=\"hidden\">'+columns[key]+' <select id=\"filter_operator\"><option value=\"=\">=</option><option value=\"!=\">!=</option></select><input id=\"filter_value\" type=\"text\" value=\"'+value+'\"><br><input class=\"filter_button\" type=\"button\" value=\"Filtern\" onclick=\"addFilter()\"></div>';
				td.appendChild(div);
			}
			
			function hideFilterForm(){
				if(document.getElementById('filterform') != undefined)document.getElementById('filterform').outerHTML = '';
			}
			
			function addFilter(){
				var filter = new Array();
				if(filters.length == 0)filter['id'] = 0;
				else filter['id'] = filters[filters.length - 1]['id'] + 1;
				filter['key'] = document.getElementById('filter_key').value;
				filter['operator'] = document.getElementById('filter_operator').value;
				filter['value'] = document.getElementById('filter_value').value;
				filters.push(filter);
				filter_output = document.createElement('div');
				filter_output.id = filter['id'];
				filter_output.innerHTML = columns[filter['key']]+' '+filter['operator']+' '+filter['value'];
				filter_remove = document.createElement('a');
				filter_remove.innerHTML = '\u274C';
				filter_remove.title = 'Filter entfernen';
				filter_remove.className = 'removeFilter';
				filter_remove.setAttribute('onclick',  'removeFilter('+filter_output.id+');');
				filter_output.appendChild(filter_remove);						
				document.getElementById('filter_div').appendChild(filter_output);
				filterRows(nachweise, filters);
				hideFilterForm();
				output();
			}
			
			function removeFilter(id){
				for(var j=0; j < filters.length; j++){
					if(filters[j]['id'] == id){
						filters.splice(j, 1);
						break;
					}
				}
				document.getElementById('filter_div').removeChild(document.getElementById(id));						
				filterRows(nachweise, filters);
				output();
			}

			function filterRows(arr, filters){
				for(var i=0; i < arr.length; i++){		// Datenzeilen
					arr[i]['filtered'] = false;
					for(var key in arr[i]){		// Spalten
						if(!arr[i]['filtered']){
							for(var j=0; j < filters.length; j++){	// Filter
								if(filters[j]['key'] == key){
									match = false;
									switch(filters[j]['operator']){
										case '=':
											if(filters[j]['value'] == arr[i][key])match = true;
										break;
										case '!=':
											if(filters[j]['value'] != arr[i][key])match = true;
										break;										
									}
									arr[i]['filtered'] = !match;
								}
							}
						}
					}
				}
			}
				
			function output(){
				document.getElementById('nachweise_table').innerHTML = '';
				document.getElementById('nachweise_table').appendChild(buildHtmlTable(nachweise));
			}
			
		</SCRIPT>
	</head>
	<body onload=\"output();\">
		<div id=\"head_div\">
			<div id=\"lk\">".LANDKREIS."</div>
			<div id=\"datum\">Datum Antragstellung: ".$GUI->antrag->antragsliste[0]['datum']."</div>
			<div id=\"antrag\">Antragsnummer: ".$GUI->antrag->antragsliste[0]['antr_nr']."</div>
			<div id=\"datum\">Datum Download: ".date('d.m.Y',time())."</div>
		</div>
		<div id=\"order_div\">Sortiert nach: <input type=\"text\" id=\"order_output\" readonly=\"true\" value=\"\"><input type=\"hidden\" id=\"order\" value=\"\"></div></div>
		<div id=\"nachweise_table\"></div>
		<div id=\"filter_div\">Filter:<br></div>
		<div id=\"preview_image\"></div>
	</body>
</html>";
		$fp=fopen($path,'wb');
		fwrite($fp, $html);
		fclose($fp);
  };	
	
	$this->erzeugenUebergabeprotokollNachweise = function($path = NULL, $with_search_params = false) use ($GUI){
  	# Erzeugen des Übergabeprotokolls mit der Zuordnung der Nachweise zum gewählten Auftrag als PDF-Dokument
  	if($GUI->formvars['antr_selected'] == ''){
			$GUI->add_message('error', 'Wählen Sie bitte eine Antragsnummer aus!');
      $GUI->Antraege_Anzeigen();
    }
    else{
			$explosion = explode('~', $GUI->formvars['antr_selected']);
			$antr_selected = $explosion[0];
			$stelle_id = $explosion[1];
			if($GUI->antrag == NULL)$GUI->antrag = new antrag($antr_selected,$stelle_id,$GUI->pgdatabase);
      $ret=$GUI->antrag->getFFR($GUI->formvars, true);
      if ($ret[0]) {
        $GUI->Fehlermeldung=$ret[1];
        # Abbruch mit Fehlermeldung und Rücksprung in Auswahl
        $GUI->Antraege_Anzeigen();
      }
      else{
		    include_once (CLASSPATH.'class.ezpdf.php');
		    $pdf=new Cezpdf();
		    $pdf=$GUI->antrag->erzeugenUbergabeprotokoll_PDF();		    
				if($path == NULL){					# Ausgabe direkt an den Browser
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
				else{											# Ausgabe als Datei auf dem Server
					if($with_search_params)$pdf=$GUI->Suchparameter_anhaengen_PDF($pdf, $antr_selected, $stelle_id);					
					$fp=fopen($path,'wb');
					fwrite($fp,$pdf->ezOutput());
					fclose($fp);
				}
      }
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
      $ret=$GUI->nachweis->pruefeEingabedaten($GUI->formvars['id'], $GUI->formvars['datum'],$GUI->formvars['VermStelle'],$GUI->formvars['art'],$GUI->formvars['gueltigkeit'],$GUI->formvars['stammnr'],$GUI->formvars['rissnummer'], $GUI->formvars['fortfuehrung'], $GUI->formvars['Blattformat'],$GUI->formvars['Blattnr'],$GUI->formvars['changeDocument'],$GUI->formvars['Bilddatei_name'],$GUI->formvars['pathlength'],$GUI->formvars['umring'], $GUI->formvars['flurid'], $GUI->formvars['Blattnr']);
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
				$zieldatei = NACHWEISDOCPATH.$GUI->formvars['flurid'].'/'.$this->nachweis->buildNachweisNr($GUI->formvars[NACHWEIS_PRIMARY_ATTRIBUTE], $GUI->formvars[NACHWEIS_SECONDARY_ATTRIBUTE]).'/'.$GUI->formvars['artname'].'/'.$GUI->formvars['zieldateiname'];
        $ret=$GUI->nachweis->dokumentenDateiHochladen($GUI->formvars['Bilddatei'], $zieldatei);
        if ($ret!='') { $errmsg=$ret; }
        else {
          # Speicherung der Bilddatei erfolgreich, Eintragen in Datenbank
          $GUI->nachweis->database->begintransaction();
          $ret=$GUI->nachweis->eintragenNeuesDokument($GUI->formvars['datum'],$GUI->formvars['flurid'],$GUI->formvars['VermStelle'], $GUI->formvars['art'], $GUI->formvars['andere_art'], $GUI->formvars['gueltigkeit'],$GUI->formvars['stammnr'],$GUI->formvars['Blattformat'],$GUI->formvars['Blattnr'],$GUI->formvars['rissnummer'],$GUI->formvars['fortfuehrung'],$GUI->formvars['bemerkungen'],$GUI->formvars['bemerkungen_intern'],$GUI->formvars['artname']."/".$GUI->formvars['zieldateiname'],$GUI->formvars['umring'], $GUI->user);
          if ($ret[0]) {
            $GUI->nachweis->database->rollbacktransaction();
            $errmsg=$ret[1];
          }
          else {
            $GUI->nachweis->database->committransaction();
            # Alle Aufgaben erfolgreich ausgeführt
            $okmsg='Daten zum neuen Dokument erfolgreich eingetragen!';
          } # ende Speicherung der Metadaten war erfolgreich
        } # ende Speicherung der Bilddatei war erfolgreich
      } # ende Prüfung war erfolgreich
      # Auswertung/Behandlung bei Aufgetretenen Fehlern
			if($okmsg)$GUI->add_message('notice', $okmsg);
			if($errmsg)$GUI->add_message('error', $errmsg);
      $GUI->nachweisFormAnzeige();
    } # ende Fall Eintragen Daten zum neuen Dokument
    else {
      ##################################################
      # 1.2. Änderung eines vorhandenen Dokumentes
      $ret=$GUI->nachweis->changeDokument($GUI->formvars, $GUI->user);
      if ($ret[0]) {
        # Die Änderung wurde auf Grund eines Fehlers nicht durchgeführt
        # 1.3 Zurück zum Änderungsformular mit Anzeige der Fehlermeldung
				$GUI->Meldung=$ret[1];
				$GUI->add_message('error', $GUI->Meldung);
        $GUI->nachweisFormAnzeige();
      } # end of fehler bei der Änderung
      else {
				$GUI->add_message('info', $ret[1]);
				$GUI->nachweisAenderungsformular();
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
		$GUI->formvars['bemerkungen']=$nachweis->document['bemerkungen'];
		$GUI->formvars['bemerkungen_intern']=$nachweis->document['bemerkungen_intern'];
    $GUI->formvars['id'] = '';
    $GUI->nachweisFormAnzeige($nachweis);
	};

	$this->nachweisFormAnzeige = function($nachweis = NULL) use ($GUI){
		include_once(CLASSPATH.'FormObject.php');
		if($GUI->formvars['reset_layers'])$GUI->reset_layers(NULL);

    # Wenn eine oid in formvars übergeben wurde ist es eine Änderung, sonst Neueingabe
    if ($GUI->formvars['oid']=='') {
      $GUI->titel='Dokumenteneingabe';
    }
    else {
      $GUI->titel='Dokumenteneingabe (neuer Ausschnitt)';
    }
    $GUI->main = PLUGINS."nachweisverwaltung/view/dokumenteneingabeformular.php";
    if($GUI->formvars['bufferwidth'] == '')$GUI->formvars['bufferwidth'] = 2;
    $saved_scale = $GUI->reduce_mapwidth(100);
		if($GUI->formvars['neuladen']){
      $GUI->neuLaden();
    }
    else{
      $GUI->loadMap('DataBase');
    }
		if($saved_scale != NULL)$GUI->scaleMap($saved_scale);		# nur beim ersten Aufruf den Extent so anpassen, dass der alte Maßstab wieder da ist
    $GUI->queryable_vector_layers = $GUI->Stelle->getqueryableVectorLayers(NULL, $GUI->user->id, NULL, NULL, NULL, true);
  	if(!$GUI->formvars['layer_id']){
      $layerset = $GUI->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
      $GUI->formvars['layer_id'] = $layerset[0]['Layer_ID'];
    }
    if($GUI->formvars['layer_id']){
	    $data = $GUI->mapDB->getData($GUI->formvars['layer_id']);
	    $data_explosion = explode(' ', $data);
	    $GUI->formvars['columnname'] = $data_explosion[0];
	    $select = $fromwhere = $GUI->mapDB->getSelectFromData($data);
			# order by rausnehmen
			$GUI->formvars['orderby'] = '';
			$orderbyposition = strrpos(strtolower($select), 'order by');
			$lastfromposition = strrpos(strtolower($select), 'from');
			if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
				$fromwhere = substr($select, 0, $orderbyposition);
				$GUI->formvars['orderby'] = ' '.substr($select, $orderbyposition);
			}
			$GUI->formvars['fromwhere'] = pg_escape_string('from ('.$fromwhere.') as foo where 1=1');
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
		elseif($GUI->formvars['zoom_layer_id'] != '')$GUI->zoomToMaxLayerExtent($GUI->formvars['zoom_layer_id']);	# zoomToMaxLayerExtent
    
    $GUI->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
    $GUI->drawMap();
	
		if($GUI->formvars['Gemarkung'] == '')$GUI->formvars['Gemarkung'] = $GUI->Lagebezeichung['gemkgschl'];
		if($GUI->formvars['Flur'] == '')$GUI->formvars['Flur'] = $GUI->Lagebezeichung['flur'];
    
    # Abfragen der Gemarkungen
    $GemeindenStelle=$GUI->Stelle->getGemeindeIDs();
    $Gemarkung=new gemarkung('',$GUI->pgdatabase);
		if($GemeindenStelle == NULL){
			$GemkgListe=$Gemarkung->getGemarkungListe(NULL, NULL);
		}
		else{
			$GemkgListe=$Gemarkung->getGemarkungListe(array_keys($GemeindenStelle['ganze_gemeinde']), array_merge(array_keys($GemeindenStelle['ganze_gemarkung']), array_keys($GemeindenStelle['eingeschr_gemarkung'])));
		}
        
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
		$explosion = explode('~', $GUI->formvars['suchantrnr']);
		$GUI->formvars['suchantrnr'] = $explosion[0];
		$stelle_id = $explosion[1];
    $GUI->nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
    $ret=$GUI->nachweis->pruefe_Auftrag_hinzufuegen_entfernen($GUI->formvars['suchantrnr'], $stelle_id);
    if ($ret!=''){ # Fehler bei der Prüfung der Eingangsparameter
      $errmsg=$ret;
    }
    else {
      # Hinzufügen der Dokumente zum Auftrag
      $ret=$GUI->nachweis->zum_Auftrag_hinzufuegen($GUI->formvars['suchantrnr'],$stelle_id,$GUI->formvars['id']);
      if ($ret[0]) { # Fehler beim Hinzufügen der Dokumente zum Antrag in der Datenbank
        $errmsg=$ret[1];
      }
      else {
				$GUI->Suchparameter_loggen($GUI->formvars, $GUI->Stelle->id, $GUI->user->id);
        $okmsg=$ret[1];
      }
    }
    # Zurück zur Anzeige des Rechercheergebnisses mit Meldung über Zuordnung der Dokumente zum Auftrag
    # Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
    $GUI->formvars=$GUI->getNachweisParameter($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id);
    $GUI->nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
		$ret=$GUI->nachweis->getNachweise(0,$GUI->formvars['suchpolygon'],$GUI->formvars['suchgemarkung'],$GUI->formvars['suchstammnr'],$GUI->formvars['suchrissnr'],$GUI->formvars['suchfortf'],$GUI->formvars['art_einblenden'],$GUI->formvars['richtung'],$GUI->formvars['abfrageart'], $GUI->formvars['order'],$GUI->formvars['suchantrnr'], $GUI->formvars['sdatum'], $GUI->formvars['sVermStelle'], $GUI->formvars['gueltigkeit'], $GUI->formvars['sdatum2'], $GUI->formvars['suchflur'], $GUI->formvars['flur_thematisch'], $GUI->formvars['such_andere_art'], $GUI->formvars['suchbemerkung'], NULL, $GUI->formvars['suchstammnr2'], $GUI->formvars['suchrissnr2'], $GUI->formvars['suchfortf2']);
    if ($ret!='') {
      $errmsg.=$ret;
    }    
    if($errmsg!=''){ # Anzeig der Fehlermeldung
			$GUI->add_message('error', $errmsg);
    }
    else { # Ohne Fehler bei der Abfrage der Dokumente Anzeige der Erfolgsmeldung
			$GUI->add_message('notice', $okmsg);
    }
		# Anzeige der Rechercheergebnisse
    $GUI->nachweisAnzeige();		
  };

	$this->nachweiseZuAuftragEntfernen = function() use ($GUI){
		$explosion = explode('~', $GUI->formvars['suchantrnr']);
		$suchantrnr = $explosion[0];
		$stelle_id = $explosion[1];
    # nachweisobjekt erstellen
    $GUI->nachweis = new nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
    # Abfrage, ob schon Löschvorgang schon bestätigt wurde
    if($GUI->formvars['bestaetigung']=='') {
      # Löschvorgang wurde noch nicht bestätigt
      # Aufrufen eines Formulars zur Bestätigung des Löschvorganges
      $GUI->formvars['nachfrage_quelle']='Antrag_entfernen';
      $GUI->formvars['nachfrage']='Möchten sie wirklich Dokumente von der Antragsnummer: ['.$suchantrnr.'] entfernen!';
      $GUI->bestaetigungsformAnzeigen($suchantrnr);
    }
    else {
      if ($GUI->formvars['bestaetigung']=='JA') {
        # Löschvorgang wurde bestätigt
        # Eingabeparameter prüfen
        $ret=$GUI->nachweis->pruefe_Auftrag_hinzufuegen_entfernen($suchantrnr,$stelle_id);
        if($ret!=''){ # Fehler bei der Prüfung der Eingangsparameter
          $errmsg=$ret;
        }
        else {
          # Eingabeparameter in Ordnung
          # Nachweise aus Antrag entfernen
          $result=$GUI->nachweis->aus_Auftrag_entfernen($suchantrnr,$stelle_id,$GUI->formvars['id']);
          $errmsg=$result[0];
					$okmsg=$result[1];
        } # ende Eingabeparameter sind ok
      } # ende Löschvorgang wurde bestätigt
      else { # Löschvorgang wurde abgebrochen
        $errmsg='Löschvorgang abgebrochen.';
      }
      # Zurück zur Anzeige des Rechercheergebnisses mit Meldung über Zuordnung der Dokumente zum Auftrag
      # Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
      $GUI->formvars=$GUI->getNachweisParameter($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id);
      $GUI->nachweis = new nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
			$GUI->nachweis->getNachweise(0,$GUI->formvars['suchpolygon'],$GUI->formvars['suchgemarkung'],$GUI->formvars['suchstammnr'],$GUI->formvars['suchrissnr'],$GUI->formvars['suchfortf'],$GUI->formvars['art_einblenden'],$GUI->formvars['richtung'],$GUI->formvars['abfrageart'], $GUI->formvars['order'],$GUI->formvars['suchantrnr'], $GUI->formvars['sdatum'], $GUI->formvars['sVermStelle'], $GUI->formvars['gueltigkeit'], $GUI->formvars['sdatum2'], $GUI->formvars['suchflur'], $GUI->formvars['flur_thematisch'], $GUI->formvars['such_andere_art'], $GUI->formvars['suchbemerkung'], NULL, $GUI->formvars['suchstammnr2'], $GUI->formvars['suchrissnr2'], $GUI->formvars['suchfortf2']);
      # Anzeige der Rechercheergebnisse			
			if($errmsg)$GUI->add_message('error', $errmsg);
			if($okmsg)$GUI->add_message('notice', $okmsg);
      $GUI->nachweisAnzeige();
    } # ende Bestätigung ist erfolgt
  };

	$this->nachweisDokumentAnzeigen = function() use ($GUI){
    $GUI->nachweis = new nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
    $ret=$GUI->nachweis->getDocLocation($GUI->formvars['id']);
    if($ret[0]!='') {
			$GUI->add_message('error', $ret[0]);
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
        $idListe=$GUI->formvars['id'];
        $ret=$GUI->nachweis->nachweiseLoeschen($idListe,1);
        if ($ret[0]) { # Fehler beim Löschen in Fehlermeldung übergeben
          $GUI->Fehlermeldung=$ret[1];
        }
        else {
					$GUI->add_message('info', $ret[1]);
        }
      }
      # Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
			$GUI->formvars = array_merge($GUI->formvars, $GUI->getNachweisParameter($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id));
      # Abfragen der Nachweise entsprechend der eingestellten Suchparameter
			$ret=$GUI->nachweis->getNachweise(0,$GUI->formvars['suchpolygon'],$GUI->formvars['suchgemarkung'],$GUI->formvars['suchstammnr'],$GUI->formvars['suchrissnr'],$GUI->formvars['suchfortf'],$GUI->formvars['art_einblenden'],$GUI->formvars['richtung'],$GUI->formvars['abfrageart'], $GUI->formvars['order'],$GUI->formvars['suchantrnr'], $GUI->formvars['sdatum'], $GUI->formvars['sVermStelle'], $GUI->formvars['gueltigkeit'], $GUI->formvars['sdatum2'], $GUI->formvars['suchflur'], $GUI->formvars['flur_thematisch'], $GUI->formvars['such_andere_art'], $GUI->formvars['suchbemerkung'], NULL, $GUI->formvars['suchstammnr2'], $GUI->formvars['suchrissnr2'], $GUI->formvars['suchfortf2']);
      if ($ret!='') {
        $GUI->Fehlermeldung.=$ret;
      }
      # Anzeige der Rechercheergebnisse
      $GUI->nachweisAnzeige();
    }
  };
	
	$this->rechercheFormAnzeigen = function() use ($GUI){
		include_once(CLASSPATH.'FormObject.php');
		# Speichern einer neuen Dokumentauswahl
		if($GUI->formvars['go_plus'] == 'Dokumentauswahl_speichern'){
			$GUI->formvars['dokauswahlen'] = $GUI->save_Dokumentauswahl($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id, $GUI->formvars);
		}
		# Löschen einer Dokumentauswahl
		if($GUI->formvars['go_plus'] == 'Dokumentauswahl_löschen'){
			$GUI->delete_Dokumentauswahl($GUI->formvars['dokauswahlen']);
			$GUI->formvars['dokauswahl_name'] = '';
		}
		# die Namen aller gespeicherten Dokumentauswahlen dieser Rolle laden
		$GUI->dokauswahlset=$GUI->get_Dokumentauswahl($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id, NULL);
		# Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
    $nachweisSuchParameter=$GUI->getNachweisParameter($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id);
		$GUI->formvars=array_merge($GUI->formvars,$nachweisSuchParameter);
		# die Parameter einer gespeicherten Dokumentauswahl laden
		if($GUI->formvars['dokauswahlen'] != ''){
			$GUI->selected_dokauswahlset = $GUI->get_Dokumentauswahl($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id, $GUI->formvars['dokauswahlen']);
			$GUI->formvars['dokauswahl_name'] = $GUI->selected_dokauswahlset[0]['name'];			
			$GUI->formvars['suchffr'] = $GUI->selected_dokauswahlset[0]['ffr'];
			$GUI->formvars['suchkvz'] = $GUI->selected_dokauswahlset[0]['kvz'];
			$GUI->formvars['suchgn'] = $GUI->selected_dokauswahlset[0]['gn'];
			if($GUI->selected_dokauswahlset[0]['andere'] != ''){
				$GUI->formvars['suchan'] = 1;
				$GUI->formvars['such_andere_art'] = $GUI->selected_dokauswahlset[0]['andere'];
			}
		}
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
    $Gemarkung=new gemarkung('',$GUI->pgdatabase);
		if($GemeindenStelle == NULL){
			$GemkgListe=$Gemarkung->getGemarkungListe(NULL, NULL);
		}
		else{
			$GemkgListe=$Gemarkung->getGemarkungListe(array_keys($GemeindenStelle['ganze_gemeinde']), array_merge(array_keys($GemeindenStelle['ganze_gemarkung']), array_keys($GemeindenStelle['eingeschr_gemarkung'])));
		}
    # Erzeugen des Formobjektes für die Gemarkungsauswahl
    $GUI->GemkgFormObj=new FormObject("suchgemarkung","select",$GemkgListe['GemkgID'],$GUI->formvars['suchgemarkung'],$GemkgListe['Bezeichnung'],"1","","",NULL);
		$GUI->GemkgFormObj->addJavaScript("onchange","updateGemarkungsschluessel(this.value)");
		$GUI->GemkgFormObj->insertOption('',0,'--Auswahl--',0);
			
    # erzeugen des Formularobjektes für die VermessungsStellen
    $GUI->FormObjVermStelle=$GUI->getFormObjVermStelle('sVermStelle', $GUI->formvars['sVermStelle']);
    $GUI->FormObjVermStelle->insertOption('', NULL, '--- Auswahl ---', 0);    
    # aktuellen Kartenausschnitt laden + zeichnen!
    $saved_scale = $GUI->reduce_mapwidth(200);
		$GUI->loadMap('DataBase');
		if($saved_scale != NULL AND !isset($GUI->formvars['datum']))$GUI->scaleMap($saved_scale);		# nur beim ersten Aufruf den Extent so anpassen, dass der alte Maßstab wieder da ist
    if ($GUI->formvars['CMD']!='') {
      # Nur Navigieren
      $GUI->navMap($GUI->formvars['CMD']);
      $GUI->user->rolle->saveDrawmode($GUI->formvars['always_draw']);
    }
	
    $GUI->queryable_vector_layers = $GUI->Stelle->getqueryableVectorLayers(NULL, $GUI->user->id, NULL, NULL, NULL, true);
    # Spaltenname und from-where abfragen
  	if(!$GUI->formvars['layer_id']){
      $layerset = $GUI->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
      $GUI->formvars['layer_id'] = $layerset[0]['Layer_ID'];
    }
    if($GUI->formvars['layer_id']){
	    $data = $GUI->mapDB->getData($GUI->formvars['layer_id']);
	    $data_explosion = explode(' ', $data);
	    $GUI->formvars['columnname'] = $data_explosion[0];
			$select = $fromwhere = $GUI->mapDB->getSelectFromData($data);
			# order by rausnehmen
			$GUI->formvars['orderby'] = '';
			$orderbyposition = strrpos(strtolower($select), 'order by');
			$lastfromposition = strrpos(strtolower($select), 'from');
			if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
				$fromwhere = substr($select, 0, $orderbyposition);
				$GUI->formvars['orderby'] = ' '.substr($select, $orderbyposition);
			}
			$GUI->formvars['fromwhere'] = pg_escape_string('from ('.$fromwhere.') as foo where 1=1');
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
	
# 2016-11-03 H.Riedel - pkz durch pkn ersetzt
	$this->festpunkteZuAntragZeigen = function() use ($GUI){
    # Funktion fragt alle Festpunkte zum einem Antrag heraus und übergibt diese an die Funktion
    # zum Anzeigen der Festpunkte in der Karte
		$explosion = explode('~', $GUI->formvars['antr_selected']);
		$GUI->formvars['antr_selected'] = $explosion[0];
		$stelle_id = $explosion[1];
    $festpunkte=new Festpunkte('',$GUI->pgdatabase);
    $ret=$festpunkte->getFestpunkte('','','','','',$GUI->formvars['antr_selected'],$stelle_id,'','pkn');
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
          $GUI->formvars['pkn'][$punkt['pkn']]=$punkt['pkn'];
        }
        $GUI->festpunkteZeigen();
      }
    }
  };

# 2016-11-03 H.Riedel - pkz durch pkn ersetzt
	$this->festpunkteZeigen = function() use ($GUI){
    $GUI->loadMap('DataBase');
    if (is_array($GUI->formvars['pkn'])) {
      $punktliste=array_keys($GUI->formvars['pkn']);
    }
    else {
      $punktliste=$GUI->formvars['pkn'];
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

# 2016-11-03 H.Riedel - pkz durch pkn ersetzt
	$this->festpunkteSuchen = function() use ($GUI){
		$explosion = explode('~', $GUI->formvars['antr_selected']);
		$GUI->formvars['antr_selected'] = $explosion[0];
		$stelle_id = $explosion[1];
    if ($GUI->formvars['antr_selected']=='' AND $GUI->formvars['pkn']=='' AND $GUI->formvars['kiloquad']=='') {
      $GUI->Fehlermeldung='<br>Geben Sie mindestens eine Antragsnummer, Kilometerquadrat oder Punktkennzeichen zu Suche an!';
    }
    else {
      $GUI->festpunkte=new festpunkte('',$GUI->pgdatabase);
# 2016-11-03 H.Riedel, Einschraenken der Punktarten wg zu hoher Treffer --> nachfolgende Operationen brechen ab.
#      $ret=$GUI->festpunkte->getFestpunkte(array($GUI->formvars['pkn']),array('TP','AP','GP','GebP','BwP','OP','SiP','SVP','TopP'),'','','',$GUI->formvars['antr_selected'],$stelle_id,$GUI->formvars['kiloquad'],'pkn');
      $ret=$GUI->festpunkte->getFestpunkte(array($GUI->formvars['pkn']),array('TP','AP','OP','SiP','SVP'),'','','',$GUI->formvars['antr_selected'],$stelle_id,$GUI->formvars['kiloquad'],'pkn');
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
    if (in_array($dateinamensteil[1],array('png','jpg','gif','tif'))) {
      header("Content-type: image/".$dateinamensteil[1]);
    }
    elseif ($dateinamensteil[1]=='pdf') {
      header("Content-type: application/pdf");
    }
    header("Content-Disposition: attachment; filename=".$dateiname);
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    readfile($Pfad.$Bild);
    ob_flush();
    return 1;
  };

# 2016-11-03 H.Riedel - pkz durch pkn ersetzt
	$GUI->showFestpunkteSkizze = function() use ($GUI){
    # Daten sind in Datenbank eingelesen. Herausfiltern von Fehlern

    # 1) Übergeben der Liste von Punkten, die geprüft werden sollen
    if (is_array($GUI->formvars['pkn'])) {
      $abgefragtefestpunkte=array_values($GUI->formvars['pkn']);
    }

    # 2) Abfragen der zu prüfenden Festpunkte
    $festpunkte=new Festpunkte('',$GUI->pgdatabase);
    $festpunkte->getFestpunkte($abgefragtefestpunkte,array('TP','AP','SiP','SVP'),'','','','','','','pkn');
    # 3) Übernehmen der Punkte in eine Liste, die mindestens eine Datei/Blatt haben.
    for ($i=0;$i<$festpunkte->anzPunkte;$i++) {
      $festpunkte->liste[$i]['skizze']=$festpunkte->checkSkizzen($festpunkte->liste[$i]['pkn']);
      if ($festpunkte->liste[$i]['skizze']['is_file']) {
        # Wenn mindestens eine Datei gefunden wurde, pkn in die Liste aufnehmen
        $punktnummern[]=trim(str_replace('-','',$festpunkte->liste[$i]['pkn']));
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

# 2016-11-03 H.Riedel - pkz durch pkn ersetzt
	$this->ordneFestpunktSkizzen = function() use ($GUI){
  	$_files = $_FILES;
    ####################################################
    # 1) Verschieben von Dateien, die zu Festpunkten zugeordnet waren,
    # aber jetzt neu zu anderen pkn zugeordnet werden sollen (aus oberen Formularteil)
    # Variable $name
    $Festpunkte=new Festpunkte('',$GUI->pgdatabase);
    if (!is_array($GUI->formvars['name'])) {
      $GUI->formvars['name']=array();
    }
    $vonPkn=array_keys($GUI->formvars['name']);
    $nachNameStern=array_values($GUI->formvars['name']);
    $anzZuordnungen=count($vonPkn);
    # Zerlegen von nachNameStern in Bestandteile :
    # 45601234/45601234120001.*
    # rhhhrzhz/rhhhrzhzapktnr.* davon sind
    # Pfad: 45601234/45601234120001 (rhhhrzhz/rhhhrzhzapktnr)
    # Kiloquadr: 45601234 (rhhhrzhz)
    # Name: 45601234120001 (rhhhrzhzapktnr)
    # Pkn: 45601234-1-20001 (rhhhrzhz-a-pktnr)
    for ($i=0;$i<$anzZuordnungen;$i++) {
      # extrahieren von Kilometerquadrat, Dateiname und Pfad zur Datei an Hand des Punktkennzeichens
      $vonKiloquad[$i]=substr(trim($vonPkn[$i]),0,-8);
      $vonName[$i]=str_replace('-','',trim($vonPkn[$i]));
      $vonPfad[$i]=$vonKiloquad[$i].'/'.$vonName[$i];
      # extrahieren des Punktkennzeichen, Dateinamen und Pfad zum neuen Speicherort der Datei
      $nachName[$i]=basename(substr($nachNameStern[$i],0,-2));
      $nachKiloquad[$i]=dirname($nachNameStern[$i]); # entnommen aus Verzeichnisnamen
      $nachPfad[$i]=$nachKiloquad[$i].'/'.$nachName[$i];
      $nachPkn[$i]=$nachKiloquad[$i].'-'.substr($filebasename,-6,-5).'-'.substr($nachName[$i],-5);
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
    # 2) Kopieren der hochgeladenen Dateien an die Speicherplätze, die den PKN entsprechen.
    # Variable $_FILES
    $uploadedFiles=array_values($_files);
    $uploadedFilesPKN=array_keys($_files);
    $anzUploadedFiles=count($uploadedFiles);
    for ($i=0;$i<$anzUploadedFiles;$i++) {
      if ($uploadedFiles[$i]['tmp_name']!='') {
        # Zusammensetzung der Dateinamen
        $pkn=substr(trim($uploadedFilesPKN[$i]),9);
        $ext=substr(trim($uploadedFilesPKN[$i]),0,3);
        $nachDatei=PUNKTDATEIPATH.$Festpunkte->pkn2pfad($pkn).'.'.$ext;
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

# 2016-11-03 H.Riedel - pkz durch pkn ersetzt
	$this->festpunkteZuAuftragFormular = function() use ($GUI){
    $GUI->titel='Festpunkte zum Auftrag Hinzufügen';
    $GUI->main = PLUGINS.'nachweisverwaltung/view/festpunktezuauftragformular.php';
    $GUI->pkn=array_keys($GUI->formvars['pkn']);
    $GUI->anzPunkte=count($GUI->pkn);
    $GUI->FormObjAntr_nr=$GUI->getFormObjAntr_nr('');
    $GUI->FormObjAntr_nr->select['name']='antr_selected';
    $GUI->output();
  };

# 2016-11-03 H.Riedel - pkz durch pkn ersetzt
	$this->festpunkteZuAuftragSenden = function() use ($GUI){
    # Prüfen, ob eine Auftragsnummer mit übergeben wurde
    if ($GUI->formvars['antr_selected']=='') {
      $GUI->Fehlermeldung='Sie müssen erst eine Antragsnummer angeben.';
      $GUI->festpunkteZuAuftragFormular();
    }
		$explosion = explode('~', $GUI->formvars['antr_selected']);
		$GUI->formvars['antr_selected'] = $explosion[0];
		$stelle_id = $explosion[1];
    $pkn=array_keys($GUI->formvars['pkn']);
    $anzPunkte=count($pkn);
    $auftrag=new antrag($GUI->formvars['antr_selected'],$stelle_id,$GUI->pgdatabase);
    $anzPunkteAdd=0;
    for ($i=0;$i<$anzPunkte;$i++) {
      $ret=$auftrag->addFestpunkt($pkn[$i]);
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

# 2016-11-03 H.Riedel - pkz durch pkn ersetzt
	$this->festpunkteInKVZschreiben = function() use ($GUI){
		$explosion = explode('~', $GUI->formvars['antr_selected']);
		$antr_selected = $explosion[0];
		$stelle_id = $explosion[1];
    if ($antr_selected=='') {
      $GUI->Fehlermeldung= '<br>Wählen Sie eine Antragsnummer aus!';
    }
    else {
      $festpunkte=new Festpunkte('',$GUI->pgdatabase);
      $ret=$festpunkte->createKVZdatei($antr_selected, $stelle_id, $GUI->formvars['pkn']);
      if ($ret[0]) {
        $GUI->Fehlermeldung=$ret[1];
      }
      else {
        $GUI->Meldung=$ret[1];
        $GUI->datei = $ret[2];
      }
    }
  };

	$this->vermessungsantragsFormular = function() use ($GUI){
		global $admin_stellen;
		if($GUI->formvars['antr_nr']!=''){
			if($GUI->formvars['stelle_id'] == $GUI->Stelle->id OR in_array($GUI->Stelle->id, $admin_stellen)){
				$GUI->titel='Antrag überarbeiten';
				# Antragsdaten aus der Datenbank abfragen
				$GUI->antrag = new antrag('','',$GUI->pgdatabase);
				$ret=$GUI->antrag->getAntraege(array($GUI->formvars['antr_nr']),'',$GUI->formvars['richtung'],$GUI->formvars['order'],$GUI->Stelle->id);
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
					$GUI->add_message('info', $ret[1]);
					$GUI->Antraege_Anzeigen();
				}
			}
			else{
				$GUI->add_message('error', "Änderung dieses Antrags nicht erlaubt!");
				$GUI->Antraege_Anzeigen();
			}
		}		
		else{
			$GUI->titel='Antrag eingeben';
			$GUI->formvars['go']='Nachweis_antragsnummer_Senden';
			$GUI->vermessungsAntragEingabeForm();
		}
  };
	
	$this->vermessungsantragAnlegen = function() use ($GUI){
    $GUI->antrag= new antrag('','',$GUI->pgdatabase);
    $ret=$GUI->antrag->pruefe_antrag_eintragen($GUI->formvars['antr_nr'],$GUI->formvars['VermStelle'],$GUI->formvars['verm_art'],$GUI->formvars['datum'],$GUI->Stelle->id);
    if($ret==''){
      $ret=$GUI->antrag->antrag_eintragen($GUI->formvars['antr_nr'],$GUI->formvars['VermStelle'],$GUI->formvars['verm_art'],$GUI->formvars['datum'],$GUI->Stelle->id);
    }
    $GUI->Meldung=$ret;
    $GUI->titel='Neuen Antrag anlegen';
		$GUI->add_message('info', $ret);
    $GUI->vermessungsAntragEingabeForm();
  };
	
	$this->vermessungsantragAendern = function() use ($GUI){
		global $admin_stellen;
		if($GUI->formvars['stelle_id'] == $GUI->Stelle->id OR in_array($GUI->Stelle->id, $admin_stellen)){
			$GUI->antrag= new antrag('','',$GUI->pgdatabase);
			$ret=$GUI->antrag->antrag_aendern($GUI->formvars['antr_nr'],$GUI->formvars['VermStelle'],$GUI->formvars['verm_art'],$GUI->formvars['datum'],$GUI->formvars['stelle_id']);
			$GUI->add_message('info', $ret[1]);
			if ($ret[0]) {
				$GUI->vermessungsantragsFormular();
			}
			else {
				$GUI->Antraege_Anzeigen();
			}
		}
		else{
			$GUI->add_message('error', "Änderung dieses Antrags nicht erlaubt!");
			$GUI->Antraege_Anzeigen();
		}
  };
	
	$this->Antraege_Anzeigen = function() use ($GUI){
    $GUI->titel='Antr&auml;ge';
    $GUI->main = PLUGINS.'nachweisverwaltung/view/antragsanzeige.php';
    $GUI->antrag = new antrag('','',$GUI->pgdatabase);
    $GUI->antrag->getAntraege('','',$GUI->formvars['richtung'],$GUI->formvars['order'], $GUI->Stelle->id);
    $GUI->output();
  };

	$this->Antrag_Loeschen = function() use ($GUI){
    global $admin_stellen;
		if($GUI->formvars['stelle_id'] == $GUI->Stelle->id OR in_array($GUI->Stelle->id, $admin_stellen)){
			if ($GUI->formvars['bestaetigung']=='JA') {
				$GUI->antrag = new antrag('','',$GUI->pgdatabase);
				$antragsnummern=$GUI->formvars['id'];
				$ret=$GUI->antrag->antrag_loeschen($antragsnummern[0],$GUI->formvars['stelle_id']);
				if($ret == 'Antrag erfolgreich gelöscht')$GUI->Suchparameter_loeschen($antragsnummern[0], $GUI->formvars['stelle_id']);
				$GUI->add_message('info', $ret);
				if($GUI->formvars['go_next'] != ''){
					go_switch($GUI->formvars['go_next']);
					exit();
				}
				else $GUI->Antraege_Anzeigen();
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
		}
		else{
			$GUI->add_message('error', "Löschen dieses Antrags nicht erlaubt!");
			$GUI->Antraege_Anzeigen();
		}
  };
	
	$this->getFormObjVermStelle = function($name, $VermStelle) use ($GUI){
		include_once(CLASSPATH.'FormObject.php');
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
		include_once(CLASSPATH.'FormObject.php');
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
		include_once(CLASSPATH.'FormObject.php');
    $Antrag = new Antrag($antr_nr,$GUI->Stelle->id,$GUI->pgdatabase);
    $back=$Antrag->getAntragsnr_Liste($GUI->Stelle->id);
    if ($back[0]=='') {
      # Fehlerfreie Datenabfrage
      $FormObjAntr_nr=new FormObject('suchantrnr','select',$back[1]['antr_nr_stelle_id'],array($antr_nr),$back[1]['antr_nr'],1,0,0,NULL);
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