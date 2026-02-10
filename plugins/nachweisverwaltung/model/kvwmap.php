<?
	
	/**
	* Trigger für Bearbeitung im GLE
	*/
	$GUI->trigger_functions['check_documentpath'] = function($fired, $event, $layer = '', $oid = 0, $old_dataset = array()) use ($GUI) {
		$executed = true;
		$success = true;

		switch(true) {
			case ($fired == 'AFTER' AND $event == 'UPDATE') : {
				$nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
				$nachweis->hauptarten = $nachweis->getHauptDokumentarten();
				$nachweis->check_documentpath($old_dataset);
			} break;

			default : {
				$executed = false;
			}
		}
		return array('executed' => $executed, 'success' => $success);
	};
	
	$GUI->LENRIS_get_all_nachweise = function() use ($GUI){
		$nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
		$nachweis->LENRIS_get_all_nachweise();
	};
	
	$GUI->LENRIS_get_new_nachweise = function() use ($GUI){
		$nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
		$nachweis->LENRIS_get_new_nachweise();
	};
	
	$GUI->LENRIS_get_changed_nachweise = function() use ($GUI){
		$nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
		$nachweis->LENRIS_get_changed_nachweise();
	};
	
	$GUI->LENRIS_get_deleted_nachweise = function() use ($GUI){
		$nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
		$nachweis->LENRIS_get_deleted_nachweise();
	};
	
	$GUI->LENRIS_confirm_new_nachweise = function() use ($GUI){
		$nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
		$nachweis->LENRIS_confirm_new_nachweise($GUI->formvars['ids']);
	};
	
	$GUI->LENRIS_confirm_changed_nachweise = function() use ($GUI){
		$nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
		$nachweis->LENRIS_confirm_changed_nachweise($GUI->formvars['ids']);
	};
	
	$GUI->LENRIS_confirm_deleted_nachweise = function() use ($GUI){
		$nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
		$nachweis->LENRIS_confirm_deleted_nachweise($GUI->formvars['ids']);
	};
	
	$GUI->LENRIS_get_document = function() use ($GUI){
		$nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
		$nachweis->LENRIS_get_document($GUI->formvars['document']);
	};
	
	$GUI->getGeomPreview = function($id) use ($GUI){
		$mapDB = new db_mapObj($GUI->Stelle->id, $GUI->user->id);
		$layerset = $GUI->user->rolle->getLayer(LAYER_ID_NACHWEISE);
		$map = new mapObj(NULL);
		$map->debug = 5;
		# Auf den Datensatz zoomen
		$sql ="SELECT st_xmin(bbox) AS minx,st_ymin(bbox) AS miny,st_xmax(bbox) AS maxx,st_ymax(bbox) AS maxy";
		$sql.=" FROM (SELECT box2D(st_transform(the_geom, ".$GUI->user->rolle->epsg_code.")) as bbox";
		$sql.=" FROM nachweisverwaltung.n_nachweise WHERE id = ".$id.") AS foo";
		$ret = $GUI->pgdatabase->execSQL($sql, 4, 0);
		$rs = pg_fetch_array($ret[1]);
		$rect = rectObj(
			$rs['minx'],
			$rs['miny'],			
			$rs['maxx'],
			$rs['maxy']
		);
		$randx=($rect->maxx-$rect->minx)*0.02;
		$randy=($rect->maxy-$rect->miny)*0.02;
		if($rect->minx != ''){
			$map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
			$map->setFontSet(FONTSET);
			# FST-Layer erzeugen
			$layer = new LayerObj($map);
			$layer->data = 'the_geom from (SELECT ogc_fid, zaehler||coalesce(\'/\'||nenner, \'\') as fsnum, wkb_geometry as the_geom FROM alkis.ax_flurstueck WHERE endet IS NULL) as foo using unique ogc_fid using srid='.EPSGCODE_ALKIS;
			$layer->status = MS_ON;
			$layer->template = ' ';
			$layer->name = 'querymap'.$k;
			$layer->type = 2;
			$layer->symbolscaledenom = 10000;
			$layer->labelitem = 'fsnum';
			$layer->setConnectionType(6, '');
			$layer->connection = $layerset[0]['connection'];
			$layer->setProjection('+init=epsg:'.EPSGCODE_ALKIS);
			$layer->metadata->set('wms_queryable','0');
			if (MAPSERVERVERSION >= 800) {
				$layer->setProcessingKey('CLOSE_CONNECTION', 'ALWAYS');
			}
			else {
				$layer->setProcessing('CLOSE_CONNECTION=ALWAYS');
			}
			$klasse = new ClassObj($layer);
			$klasse->status = MS_ON;
			$style = new StyleObj($klasse);
			$style->color->setRGB(-1,-1,-1);
			$style->width = 0.2;
			$style->maxwidth = 0.2;
			$style->outlinecolor->setRGB(100,100,100);
			$label = new labelObj();
			if (MAPSERVERVERSION < 700 ) {
				$label->type = 'truetype';
			}			
			$label->size = 8;
			$label->minsize = 8;
			$label->maxsize = 8;
			$label->color->setRGB(40, 40, 40);
			$label->font = 'arial';
			$klasse->addLabel($label);
			# Gebäude-Layer erzeugen
			$layer = new LayerObj($map);
			$layer->data = 'the_geom from (SELECT ogc_fid, wkb_geometry as the_geom FROM alkis.ax_gebaeude WHERE endet IS NULL) as foo using unique ogc_fid using srid='.EPSGCODE_ALKIS;
			$layer->status = MS_ON;
			$layer->template = ' ';
			$layer->name = 'querymap'.$k;
			$layer->type = 2;
			$layer->maxscaledenom = 2000;
			$layer->symbolscaledenom = 10000;
			$layer->updateFromString("LAYER COMPOSITE OPACITY 5 END END");
			$layer->setConnectionType(6, '');
			$layer->connection = $layerset[0]['connection'];
			$layer->setProjection('+init=epsg:'.EPSGCODE_ALKIS);
			$layer->metadata->set('wms_queryable','0');
			if (MAPSERVERVERSION >= 800) {
				$layer->setProcessingKey('CLOSE_CONNECTION', 'ALWAYS');
			}
			else {
				$layer->setProcessing('CLOSE_CONNECTION=ALWAYS');
			}
			$klasse = new ClassObj($layer);
			$klasse->status = MS_ON;
			$style = new StyleObj($klasse);
			$style->color->setRGB(40,40,40);
			$style->width = 1;
			$style->maxwidth = 1;
			$style->outlinecolor->setRGB(0,0,0);
			# Flur-Layer erzeugen
			$layer= new layerObj($map);
			$layer->data = 'the_geom from alkis.pp_flur as foo using unique gid using srid='.EPSGCODE_ALKIS;
			$layer->status = MS_ON;
			$layer->template = ' ';
			$layer->name = 'querymap'.$k;
			$layer->type = 2;
			$layer->symbolscaledenom = 10000;
			$layer->updateFromString("LAYER COMPOSITE OPACITY 80 END END");
			$layer->setConnectionType(6, '');
			$layer->connection = $layerset[0]['connection'];
			$layer->setProjection('+init=epsg:'.EPSGCODE_ALKIS);
			$layer->metadata->set('wms_queryable','0');
			if (MAPSERVERVERSION >= 800) {
				$layer->setProcessingKey('CLOSE_CONNECTION', 'ALWAYS');
			}
			else {
				$layer->setProcessing('CLOSE_CONNECTION=ALWAYS');
			}
			$klasse = new ClassObj($layer);
			$klasse->status = MS_ON;
			$style = new StyleObj($klasse);
			$style->color->setRGB(-1,-1,-1);
			$style->width = 1;
			$style->maxwidth = 1;
			$style->outlinecolor->setRGB(40,80,165);
			# Datensatz-Layer erzeugen
			$layer = new LayerObj($map);
			$datastring = "the_geom from (select id, the_geom from nachweisverwaltung.n_nachweise";
			$datastring.=" WHERE id = '".$id."'";
			$datastring.=") as foo using unique id using srid=".$layerset[0]['epsg_code'];
			$layer->data = $datastring;

			$layer->status = MS_ON;
			$layer->template = ' ';
			$layer->name = 'querymap'.$k;
			$layer->type = 2;
			$layer->updateFromString("LAYER COMPOSITE OPACITY 50 END END");
			$layer->setConnectionType(6, '');
			$layer->connection = $layerset[0]['connection'];
			$layer->setProjection('+init=epsg:'.$layerset[0]['epsg_code']);
			$layer->metadata->set('wms_queryable','0');
			if (MAPSERVERVERSION >= 800) {
				$layer->setProcessingKey('CLOSE_CONNECTION', 'ALWAYS');
			}
			else {
				$layer->setProcessing('CLOSE_CONNECTION=ALWAYS');
			}
			$klasse = new ClassObj($layer);
			$klasse->status = MS_ON;
			$style = new StyleObj($klasse);
			$style->color->setRGB(252,101,84);
			$style->width = 2;
			$style->outlinecolor->setRGB(-1,-1,-1);
			# Karte rendern
			$map->setProjection('+init=epsg:'.$GUI->user->rolle->epsg_code);
			$map->web->imagepath = IMAGEPATH;
			$map->web->imageurl = IMAGEURL;
			$map->width = 300;
			$map->height = 300;
			$image_map = $map->draw();
			$filename = $GUI->map_saveWebImage($image_map,'jpeg');
			$newname = $GUI->user->id.basename($filename);
			rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);
			$type = pathinfo(IMAGEPATH.$newname, PATHINFO_EXTENSION);
			$data = file_get_contents(IMAGEPATH.$newname);
			$base64 = 'data:image/'.$type.';base64,'.base64_encode($data);
			echo $base64;
		}
		else{
			echo GRAPHICSPATH.'nogeom.png';
		}
	};

	$GUI->Datei_Download = function($filename) use ($GUI){
    $GUI->formvars['filename'] = $filename;
    $GUI->titel='Datei-Download';
		$GUI->main= PLUGINS.'nachweisverwaltung/view/dateidownload.php';
    $GUI->output();
  };
	
	$GUI->DokumenteOrdnerPacken = function($light) use ($GUI){
		$GUI->formvars['antr_selected'] = $GUI->formvars['antr_selected'] ?: $GUI->formvars['lea_id'];
		$explosion = explode('~', $GUI->formvars['antr_selected']);
		$antr_selected = $explosion[0];
		$stelle_id = $explosion[1];
		$GUI->antrag = new antrag($antr_selected, $stelle_id, $GUI->pgdatabase);
		$GUI->antrag->getAntraege(array($antr_selected), $GUI->formvars['lea_id'],'','', $stelle_id ?: $GUI->Stelle->id);
		$GUI->antrag->searches = $GUI->Suchparameter_abfragen($antr_selected, $stelle_id ?: $GUI->Stelle->id);
		$antragsnr = sonderzeichen_umwandeln($GUI->antrag->nr);
		if($stelle_id != '')$antragsnr.='~'.$stelle_id;
		if(is_dir(RECHERCHEERGEBNIS_PATH.$antragsnr)){
			chdir(RECHERCHEERGEBNIS_PATH);
			$GUI->formvars['Riss-Nummer'] = 1;
			$GUI->formvars['Antrags-Nummer'] = 1;
			$GUI->formvars['Datum'] = 1;
			$GUI->formvars['Datei'] = 1;
			$GUI->formvars['gemessendurch'] = 1;
			$GUI->formvars['Gueltigkeit'] = 1;		
			$timestamp = date('Y-m-d_H-i-s',time());
			if($GUI->nachweis->Dokumente != NULL){		# wenn es Nachweise zu diesem Auftrag gibt
				$GUI->erzeugenUebergabeprotokollNachweise(RECHERCHEERGEBNIS_PATH.$antragsnr.'/Protokolle/Uebergabeprotokoll.pdf');
				$GUI->erzeugenUebersicht_HTML(RECHERCHEERGEBNIS_PATH.$antragsnr.'/Protokolle/Uebersicht.htm', $light);
				if(!$light){
					$GUI->erzeugenUebersicht_CSV(RECHERCHEERGEBNIS_PATH.$antragsnr.'/Protokolle/Uebersicht.csv');
					#$GUI->erzeugenZuordnungFlst_CSV(RECHERCHEERGEBNIS_PATH.$antragsnr.'/Protokolle/');
					$GUI->create_Recherche_UKO(RECHERCHEERGEBNIS_PATH.$antragsnr.'/Protokolle/');
					$GUI->nachweis->create_Gesamtpolygon(RECHERCHEERGEBNIS_PATH.$antragsnr.'/Protokolle/Gesamtpolygon/');
					$GUI->nachweis->writeIgnoredDokumentarten(RECHERCHEERGEBNIS_PATH.$antragsnr.'/Protokolle/');
				}
				# Loggen der übergebenen Dokumente
				$uebergabe_logpath = $GUI->antrag->create_uebergabe_logpath($GUI->Stelle->Bezeichnung).'/'.$antr_selected.'_'.$timestamp.'.pdf';
				$GUI->erzeugenUebergabeprotokollNachweise($uebergabe_logpath, true);
			}
			if(!$light)$result = exec(ZIP_PATH.' -r '.RECHERCHEERGEBNIS_PATH.$antragsnr.' '.'./'.$antragsnr);		# gesamten Rechercheordner packen
			else{
				$result = exec(ZIP_PATH.' -j -r '.RECHERCHEERGEBNIS_PATH.$antragsnr.' '.'./'.$antragsnr.'/Nachweise');		# Nachweise-Ordnerstruktur verwerfen und nur Nachweise
				$result = exec(ZIP_PATH.' '.RECHERCHEERGEBNIS_PATH.$antragsnr.' '.'./'.$antragsnr.'/Protokolle/Uebergabeprotokoll.pdf');		# und das Übergabeprotokoll 
				$result = exec(ZIP_PATH.' '.RECHERCHEERGEBNIS_PATH.$antragsnr.' '.'./'.$antragsnr.'/Protokolle/Uebersicht.htm');		# und Uebersicht.htm
				$result = exec(ZIP_PATH.' -r '.RECHERCHEERGEBNIS_PATH.$antragsnr.' '.'./'.$antragsnr.'/Vorschaubilder');		# und Vorschaubilder 
				$result = exec(ZIP_PATH.' -r '.RECHERCHEERGEBNIS_PATH.$antragsnr.' '.'./'.$antragsnr.'/Einmessungsskizzen');		# und, wenn vorhanden, die Einmessungsskizzen
				$result = exec(ZIP_PATH.' -r '.RECHERCHEERGEBNIS_PATH.$antragsnr.' '.'./'.$antragsnr.'/KVZ');		# und, wenn vorhanden, die KVZ packen
			}
			chdir(WWWROOT.APPLVERSION);
		}
		$filename = RECHERCHEERGEBNIS_PATH.$antragsnr.'.zip';
		$dateiname = $GUI->antrag->nr.'_'.date('Y-m-d_H-i-s',time()).'.zip';
		$tmpfilename = copy_file_to_tmp($filename, $dateiname);
		unlink($filename);
		$GUI->antrag->clearRecherchePfad();	
		return $tmpfilename;
  };
	
	$GUI->DokumenteZumAntragInOrdnerZusammenstellen = function() use ($GUI){
    if ($GUI->formvars['lea_id'] == ''){
      if(strpos($GUI->formvars['antr_selected'], '~') == false)$GUI->formvars['antr_selected'] = str_replace('|', '~', $GUI->formvars['antr_selected']); # für Benutzung im GLE
			$explosion = explode('~', $GUI->formvars['antr_selected']);
			$antr_selected = $explosion[0];
			$stelle_id = $explosion[1];
		}
		else {
			$stelle_id = NULL;
		}
		$antrag = new antrag($antr_selected ?: $GUI->formvars['lea_id'], $stelle_id, $GUI->pgdatabase);
		$msg = $antrag->clearRecherchePfad();			
		# Zusammenstellen der Dokumente der Nachweisverwaltung
		$GUI->nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
		$GUI->nachweis->hauptdokumentarten = $GUI->nachweis->getHauptDokumentarten();
		$ret=$GUI->nachweis->getNachw2Antr($antr_selected, $stelle_id, $GUI->formvars['lea_id']);
		if($ret==''){
			$ret=$GUI->nachweis->getNachweise($GUI->nachweis->nachweise_id,'','','','','','','','multibleIDs','','');
			if ($ret==''){
				$ret=$antrag->DokumenteInOrdnerZusammenstellen($GUI->nachweis);
				$msg.=$ret;
			}
		}
		if ($GUI->formvars['lea_id'] == ''){
			# 2016-11-03 H.Riedel - pkz durch pkn ersetzt
			# Zusammenstellen der Einmessungsskizzen der Festpunkte
			$festpunkte=new Festpunkte('',$GUI->pgdatabase);
			$ret=$festpunkte->getFestpunkte('',array('TP','AP','SiP','SVP','OP'),'','','',$antr_selected,$stelle_id,'','pkn');
			if ($ret[0]) {
				$errmsg="Festpunkte konnten nicht abgefragt werden.";
			}
			elseif(count_or_0($festpunkte->liste) > 0){
				$ret=$antrag->EinmessungsskizzenInOrdnerZusammenstellen($festpunkte);
				$msg.=$ret;
			}
			# Schreiben des Koordinatenverzeichnisses der zugeordneten Festpunkte
			$GUI->festpunkteInKVZschreiben();
		}
    return $msg;
  };

	$GUI->nachweisAenderungsformular = function() use ($GUI){
		include_once(CLASSPATH.'FormObject.php');
		include_once(PLUGINS.'alkis/model/kataster.php');
    $GUI->menue='menue.php';
    $GUI->main= PLUGINS.'nachweisverwaltung/view/dokumenteneingabeformular.php';
    $GUI->titel='Dokument überarbeiten';    
		if($GUI->formvars['reset_layers'])$GUI->reset_layers(NULL);
		if($GUI->formvars['bufferwidth'] == '')$GUI->formvars['bufferwidth'] = 2;
    # Nachweisdaten aus Datenbank abfragen
    $nachweis=new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
    $GUI->hauptdokumentarten = $nachweis->getHauptDokumentarten();
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
      # zoom_to_max_layer_extent
			if($GUI->formvars['zoom_layer_id'] != '')$GUI->zoom_to_max_layer_extent($GUI->formvars['zoom_layer_id']);
      $GUI->queryable_vector_layers = $GUI->Stelle->getqueryableVectorLayers(NULL, $GUI->user->id, NULL, NULL, NULL, true, true);
	    if(!$GUI->formvars['geom_from_layer']){
	      $layerset = $GUI->user->rolle->getLayer(LAYER_ID_NACHWEISE);
	      $GUI->formvars['geom_from_layer'] = $layerset[0]['geom_from_layer'];
	    }   
      # Ausführen von Aktionen vor der Anzeige der Karte und der Zeichnung
			$oldscale=round($GUI->map_scaledenom);  
			$GUI->formvars['unterart'] = $GUI->formvars['unterart_'.$GUI->formvars['hauptart']];
			if ($GUI->formvars['CMD']!='') {
				$GUI->navMap($GUI->formvars['CMD']);
			}
			elseif($oldscale!=$GUI->formvars['nScale'] AND $GUI->formvars['nScale'] != '') {
				$GUI->scaleMap($GUI->formvars['nScale']);
			}
      elseif($GUI->formvars['rissnummer'] == '' AND $GUI->formvars['stammnr'] == ''){		# nur am Anfang setzen
	      # Zuweisen der Werte des Dokumentes zum Formular
				$GUI->formvars['flurid']=$nachweis->document['flurid'];
				$GUI->formvars['stammnr']=$nachweis->document['stammnr'];
				$GUI->formvars['hauptart']=$nachweis->document['hauptart'];
				$GUI->formvars['unterart']=$nachweis->document['unterart'];				
				$GUI->formvars['Blattnr']=$nachweis->document['blattnummer'];
				$GUI->formvars['datum']=$nachweis->document['datum'];
				$GUI->formvars['VermStelle']=$nachweis->document['vermstelle'];
				$GUI->formvars['Blattformat']=$nachweis->document['format'];
				$GUI->formvars['gueltigkeit']=$nachweis->document['gueltigkeit'];
				$GUI->formvars['geprueft']=$nachweis->document['geprueft'];
				$GUI->formvars['Gemeinde']=$nachweis->document['Gemeinde'];
				$GUI->formvars['Gemarkung']=substr($GUI->formvars['flurid'],0,6);
				$GUI->formvars['Flur']=intval(substr($GUI->formvars['flurid'],6,9));
				$GUI->formvars['Bilddatei']=$nachweis->document['link_datei'];				
				$GUI->formvars['rissnummer']=$nachweis->document['rissnummer'];
				$GUI->formvars['fortfuehrung']=$nachweis->document['fortfuehrung'];
				$GUI->formvars['bemerkungen']=$nachweis->document['bemerkungen'];
				$GUI->formvars['bemerkungen_intern']=$nachweis->document['bemerkungen_intern'];
				if($nachweis->document['wkt_umring'] != ''){
					if($GUI->formvars['neuladen'] == ''){
						# Zoom zum Polygon des Dokumentes
						$GUI->zoomToNachweise($nachweis, array($nachweis->document['id']), 10);
						$GUI->user->rolle->saveSettings($GUI->map->extent);
						$GUI->user->rolle->readSettings();
					}
					# Übernahme des Nachweisumrings aus der PostGIS-Datenbank
					$GUI->formvars['newpath'] = transformCoordsSVG($nachweis->document['svg_umring']);
					$GUI->formvars['newpathwkt'] = $nachweis->document['wkt_umring'];
					$GUI->formvars['pathwkt'] = $GUI->formvars['newpathwkt'];
					$GUI->formvars['firstpoly'] = 'true';
					$GUI->geomload = true;			# Geometrie wird das erste Mal geladen, deshalb nicht in den Weiterzeichnenmodus gehen
				}
				else{
					$GUI->add_message('error', 'Achtung! Nachweis hat noch keine Geometrie!');
				}
			}

      # Abfragen der Gemarkungen
      # 2006-01-26 pk
      $Gemarkung=new gemarkung('',$GUI->pgdatabase);
      $GemkgListe=$Gemarkung->getGemarkungListeAll('','');
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
	
	$GUI->DokumenteZuAntraegeAnzeigen = function() use ($GUI){
		if(strpos($GUI->formvars['antr_selected'], '~') == false)$GUI->formvars['antr_selected'] = str_replace('|', '~', $GUI->formvars['antr_selected']); # für Benutzung im GLE
    $GUI->formvars['suchantrnr']=$GUI->formvars['antr_selected'];
    $GUI->formvars['abfrageart'] = ($GUI->formvars['lea_id'] ? 'lea_id' : 'antr_nr');
    $GUI->nachweiseRecherchieren();
  };
	
	$GUI->nachweiseAuswahlSpeichern = function($stelle_id, $user_id, $nachweis_ids) use ($GUI){
		$sql = '
			DELETE FROM 
				rolle_nachweise_rechercheauswahl 
			WHERE
				stelle_id = ' . $stelle_id . ' AND
				user_id = ' . $user_id;
		#echo $sql;
		$GUI->debug->write("<p>nachweiseAuswahlSpeichern - Speichern der aktuellen Auswahl im Rechercheergebnis",4);
		$GUI->database->execSQL($sql,4, 1);
		if (count_or_0($nachweis_ids) > 0) {
			$sql = '
				INSERT INTO 
					rolle_nachweise_rechercheauswahl 
				VALUES ';
			for ($i = 0; $i < count($nachweis_ids); $i++){
				$sql .= ($i > 0? ',' : '') . '(
					' . $stelle_id . ', 
					' . $user_id . ',
					' . $nachweis_ids[$i] . ')';
			}
			#echo $sql;
			$GUI->debug->write("<p>nachweiseAuswahlSpeichern - Speichern der aktuellen Auswahl im Rechercheergebnis",4);
			$GUI->database->execSQL($sql,4, 1);
		}
		return 1;
	};
	
	$GUI->getNachweiseAuswahl = function($stelle_id, $user_id) use ($GUI){
		$sql = '
			SELECT * FROM 
				rolle_nachweise_rechercheauswahl 
			WHERE
				stelle_id = ' . $stelle_id . ' AND
				user_id = ' . $user_id;
		#echo $sql;
		$GUI->debug->write("<p>getNachweiseAuswahl - abfragen der aktuellen Auswahl im Rechercheergebnis",4);
		$GUI->database->execSQL($sql,4, 1);
		while($rs = $GUI->database->result->fetch_assoc()){
			$nachweisauswahl[] = $rs['nachweis_id'];
		}
		return $nachweisauswahl;
	};

	$GUI->setNachweisOrder = function($stelle_id, $user_id, $order) use ($GUI){
		$sql = "
			UPDATE 
				rolle_nachweise 
			SET 
				`order` = '" . $order . "' 
			WHERE 
				user_id = " . $user_id . " AND 
				stelle_id = " . $stelle_id;
		#echo $sql;
		$GUI->debug->write("<p>setNachweisOrder - Setzen der aktuellen Order für die Nachweissuche",4);
		$GUI->database->execSQL($sql,4, 1);
		return 1;
	};

	$GUI->setNachweisSuchparameter = function($stelle_id, $user_id, $suchhauptart,$suchunterart,$abfrageart,$suchgemarkung,$suchflur,$stammnr,$stammnr2,$suchrissnummer,$suchrissnummer2,$suchfortfuehrung,$suchfortfuehrung2,$suchpolygon,$suchantrnr, $sdatum, $sdatum2, $svermstelle, $suchbemerkung, $flur_thematisch, $alle_der_messung, $order) use ($GUI){
		if($suchhauptart == NULL)$suchhauptart = array();
		if($suchunterart == NULL)$suchunterart = array();
		$sql ='UPDATE rolle_nachweise SET ';
		$sql.='suchhauptart="'.implode(',', $suchhauptart).'",';
		$sql.='suchunterart="'.implode(',', $suchunterart).'",';
		if ($abfrageart != '') { $sql.='abfrageart="'.$abfrageart.'",'; }
		$sql.='suchgemarkung="'.$suchgemarkung.'",';
		$sql.='suchflur="'.$suchflur.'",';
		$sql.='suchstammnr="'.$stammnr.'",';
		$sql.='suchstammnr2="'.$stammnr2.'",';
		$sql.='suchrissnummer="'.$suchrissnummer.'",';
		$sql.='suchrissnummer2="'.$suchrissnummer2.'",';
		if($suchfortfuehrung == '')$suchfortfuehrung = 'NULL';
		$sql.='suchfortfuehrung='.$suchfortfuehrung.',';
		if($suchfortfuehrung2 == '')$suchfortfuehrung2 = 'NULL';
		$sql.='suchfortfuehrung2='.$suchfortfuehrung2.',';
		if ($suchpolygon!='') { $sql.='suchpolygon="'.$suchpolygon.'",'; }
		if ($suchantrnr!='') { $sql.='suchantrnr="'.$suchantrnr.'",'; }
		$sql.='sdatum="'.$sdatum.'",';
		$sql.='sdatum2="'.$sdatum2.'",';
		if ($svermstelle!='') { $sql.='sVermStelle='.$svermstelle.','; }else{$sql.='sVermStelle= NULL,' ;}
		$sql.='suchbemerkung="'.$suchbemerkung.'",';
		$sql.='flur_thematisch = '.($flur_thematisch ?: 0).',';
		$sql.='alle_der_messung = ' . (int)$alle_der_messung . ',';
		if ($order != '') {
			$sql.='`order`="'.$order.'",';
		}
		$sql .= 'user_id = '.$user_id;
		$sql.=' WHERE user_id='.$user_id.' AND stelle_id='.$stelle_id;
		#echo $sql;
		$GUI->debug->write("<p>setNachweisSuchparameter - Setzen der aktuellen Parameter für die Nachweissuche",4);
		$GUI->database->execSQL($sql,4, 1);
		return 1;
	};

	$GUI->setNachweisAnzeigeparameter = function($stelle_id, $user_id, $showhauptart,$markhauptart) use ($GUI){
		if($showhauptart == NULL)$showhauptart = array();
		if($markhauptart == NULL)$markhauptart = array();
		$sql = "
			UPDATE 
				rolle_nachweise 
			SET 
				showhauptart = '" . implode(',', $showhauptart) . "', 
				markhauptart = '" . implode(',', $markhauptart) . "', 
				user_id = " . $user_id . "
			WHERE 
				user_id = " . $user_id . " AND 
				stelle_id = " . $stelle_id;
		$GUI->debug->write("<p>file:users.php class:rolle->setNachweisAnzeigeparameter - Setzen der aktuellen Anzeigeparameter für die Nachweissuche",4);
		$GUI->database->execSQL($sql,4, 1);
		return 1;
	};

	$GUI->getNachweisParameter = function($stelle_id, $user_id) use ($GUI){
		$sql ='SELECT user_id FROM rolle_nachweise';
		$sql.=' WHERE user_id='.$user_id.' AND stelle_id='.$stelle_id;
		$GUI->debug->write("<p>file:users.php class:user->getNachweisParameter - Abfragen der aktuellen Parameter für die Nachweissuche<br>".$sql,4);
		$GUI->database->execSQL($sql,4, 1);
		if ($GUI->database->result->num_rows == 0) {
			$sql ='INSERT INTO rolle_nachweise ';
			$sql.='SET user_id='.$user_id.', stelle_id='.$stelle_id;
			$GUI->debug->write("<p>file:users.php class:user->getNachweisParameter - Abfragen der aktuellen Parameter für die Nachweissuche<br>".$sql,4);
			$GUI->database->execSQL($sql,4, 1);
		}
		$sql ='SELECT * FROM rolle_nachweise';
		$sql.=' WHERE user_id='.$user_id.' AND stelle_id='.$stelle_id;
		$GUI->debug->write("<p>file:users.php class:user->getNachweisParameter - Abfragen der aktuellen Parameter für die Nachweissuche<br>".$sql,4);
		$GUI->database->execSQL($sql,4, 1);
		$rs = $GUI->database->result->fetch_assoc();
		$rs['suchhauptart'] = array_filter(explode(',', $rs['suchhauptart']));
		$rs['suchunterart'] = array_filter(explode(',', $rs['suchunterart']));
		$rs['showhauptart'] = array_filter(explode(',', $rs['showhauptart']));
		$rs['markhauptart'] = array_filter(explode(',', $rs['markhauptart']));
		return $rs;
	};
	
	$GUI->save_Dokumentauswahl = function($stelle_id, $user_id, $formvars) use ($GUI){
		$sql ="
			INSERT INTO rolle_nachweise_dokumentauswahl 
				(stelle_id, user_id, name, suchhauptart, suchunterart) 
			VALUES (
			" . $stelle_id . ", 
			" . $user_id . ",
			'" . $formvars['dokauswahl_name'] . "', 
			'" . implode(',', $formvars['suchhauptart']) . "', 
			'" . implode(',', $formvars['suchunterart'] ?: []) . "')";
		#echo $sql;
		$GUI->debug->write("<p>file:users.php class:rolle->save_Dokumentauswahl ",4);
		$GUI->database->execSQL($sql,4, 1);
		return $GUI->database->mysqli->insert_id;
	};
	
	$GUI->delete_Dokumentauswahl = function($id) use ($GUI){
		$sql = "
			DELETE FROM 
				rolle_nachweise_dokumentauswahl 
			WHERE id = " . $id;
		#echo $sql;
		$GUI->debug->write("<p>file:users.php class:rolle->delete_Dokumentauswahl ",4);
		$GUI->database->execSQL($sql,4, 1);
	};
		
	$GUI->get_Dokumentauswahl = function($stelle_id, $user_id, $dokauswahl_id) use ($GUI){
		$sql ='SELECT * FROM rolle_nachweise_dokumentauswahl ';
		$sql.='WHERE user_id='.$user_id.' AND stelle_id='.$stelle_id;
		if($dokauswahl_id != '')$sql.=' AND id = '.$dokauswahl_id;
		#echo $sql;
		$GUI->debug->write("<p>file:users.php class:rolle->get_Dokumentauswahl ",4);
		$GUI->database->execSQL($sql,4, 1);
		while($rs = $GUI->database->result->fetch_assoc()){
			$dokauswahlen[] = $rs;
		}
		return $dokauswahlen;
	};
	
	$GUI->create_Recherche_UKO = function($pfad) use ($GUI){
		$searches = $GUI->antrag->searches;
		if($searches != NULL){
			foreach($searches as $params){
				if($params['abfrageart'] == 'poly')$polys[] = "st_geometryfromtext('".$params['suchpolygon']."', 25833)";
			}
			if($polys != NULL){
				$sql = "select st_astext(st_multi(st_union(ARRAY[".implode(',', $polys)."])))";
				$ret = $GUI->pgdatabase->execSQL($sql, 4, 1);
				$rs=pg_fetch_row($ret[1]);
				$uko = WKT2UKO($rs[0]);
				$ukofile = 'Recherche.uko';
				$fp = fopen($pfad.$ukofile, 'w');
				fwrite($fp, $uko);
				fclose($fp);
			}
		}
	};
		
	$GUI->Suchparameter_loggen = function($formvars, $stelle_id, $user_id) use ($GUI){
		$sql = "
			INSERT INTO 
				u_consumeNachweise 
			SELECT
				'" . ($formvars['suchantrnr'] ?: $formvars['lea_id']) . "', 
				" . $stelle_id . ", 
				'" . date('Y-m-d H:i:s',time()) . "', 
				`suchhauptart`, 
				`suchunterart`, 
				`abfrageart`, 
				`suchgemarkung`, 
				`suchflur`, 
				`suchstammnr`, 
				`suchstammnr2`, 
				`suchrissnummer`, 
				`suchrissnummer2`, 
				`suchfortfuehrung`, 
				`suchpolygon`, 
				`suchantrnr`, 
				`sdatum`, 
				`sdatum2`, 
				`sVermStelle`, " .
				(($formvars['flur_thematisch'] != '') ? $formvars['flur_thematisch'] : "0") . "
			FROM 
				rolle_nachweise 
			WHERE 
				user_id = " . $user_id . " AND 
				stelle_id = " . $stelle_id;
		$GUI->debug->write("<p>file:users.php class:rolle->Suchparameter_loggen - Setzen der aktuellen Parameter für die Nachweissuche",4);
		$GUI->database->execSQL($sql,4, 1);
	};
	
	$GUI->Suchparameter_loeschen = function($antrag_nr, $stelle_id) use ($GUI){
		$sql ="DELETE FROM u_consumeNachweise WHERE antrag_nr = '".$antrag_nr."' AND stelle_id = ".$stelle_id;
		$GUI->debug->write("<p>file:users.php class:rolle->Suchparameter_loeschen - Löschen der Parameter für die Nachweissuche",4);
		$GUI->database->execSQL($sql,4, 1);
	};
	
	$GUI->Suchparameter_abfragen = function($antrag_nr, $stelle_id) use ($GUI){		
		$searches = array();
		$sql = "SELECT * FROM u_consumeNachweise ";
		$sql.= "WHERE antrag_nr='".$antrag_nr."' AND stelle_id=".$stelle_id;
		$GUI->debug->write("<p>file:users.php class:user->Suchparameter_anhaengen_PDF <br>".$sql,4);
		$GUI->database->execSQL($sql,4, 1);
		while($rs = $GUI->database->result->fetch_assoc()){
			$searches[] = $rs;
		}
		return $searches;
	};	
	
	$GUI->Suchparameter_anhaengen_PDF = function($pdf, $antrag_nr, $stelle_id) use ($GUI){
		$row = 0;
		$options = array('aleft'=>30, 'right'=>30, 'justification'=>'left');
		$searches = $GUI->antrag->searches;
		foreach($searches as $params){
			switch ($params['abfrageart']){
				case 'indiv_nr' : {
					$keys = array('suchhauptart'=>0, 'suchunterart'=>0, 'suchgemarkung'=>0, 'suchflur'=>0, 'suchstammnr'=>0, 'suchstammnr2'=>0, 'suchrissnummer'=>0, 'suchrissnummer2'=>0, 'suchfortfuehrung'=>0, 'sdatum'=>0, 'sdatum2'=>0, 'sVermStelle'=>0, 'flur_thematisch'=>0);
				}break;
				case 'poly' : {
					$keys = array('suchhauptart'=>0, 'suchunterart'=>0, 'suchpolygon'=>0);
				}break;
				case 'antr_nr' : {
					$keys = array('suchhauptart'=>0, 'suchunterart'=>0, 'suchantrnr'=>0);
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
	
	$GUI->sanitizeNachweisSearch = function() use ($GUI){
		$GUI->sanitize([
			'bearbeitungshinweis_id' => 'int', 
			'bearbeitungshinweis_text' => 'text',
			'showhauptart' => 'text',
			'markhauptart' => 'text',
			'order' => 'text',
			'suchpolygon' => 'text',
			'suchgemarkung' => 'text',
			'suchstammnr' => 'text',
			'suchrissnummer' => 'text',
			'suchfortfuehrung' => 'int',
			'suchantrnr' => 'text',
			'sdatum' => 'text',
			'sVermStelle' => 'text',
			'suchgueltigkeit' => 'boolean',
			'sdatum2' => 'text',
			'suchflur' => 'text',
			'suchbemerkung' => 'text',
			'suchstammnr2' => 'text',
			'suchrissnummer2' => 'text',
			'suchfortfuehrung2' => 'int',
			'suchgeprueft' => 'boolean',
			'flur_thematisch' => 'int',
			'lea_id' => 'int']);
	};
	
	$GUI->nachweiseRecherchieren = function() use ($GUI){
		if($GUI->formvars['abfrageart'] != ''){		# nur wenn man aus dem Suchformular kommt, Suchparameter speichern
			$GUI->formvars['suchstammnr'] = trim($GUI->formvars['suchstammnr']);
			$GUI->formvars['suchrissnummer'] = trim($GUI->formvars['suchrissnummer']);
			# Suchparameter, die neu gesetzt worden sind in formvars, sollen übernommen werden und gespeichert werden
			# für späterer Suchanfragen und die anderen sollen aus der Datenbank abgefragt werden.
			# Setzen von Such- und Anzeigeparametern die neu gesetzt worden sind
			# (nur neu gesetzte werden überschrieben)
			if ($GUI->formvars['abfrageart']=='poly') {
				$GUI->formvars['suchpolygon'] = $GUI->formvars['newpathwkt'];
			}
			$GUI->setNachweisSuchparameter($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id, $GUI->formvars['suchhauptart'],$GUI->formvars['suchunterart'], $GUI->formvars['abfrageart'],$GUI->formvars['suchgemarkung'],$GUI->formvars['suchflur'],$GUI->formvars['suchstammnr'],$GUI->formvars['suchstammnr2'],$GUI->formvars['suchrissnummer'],$GUI->formvars['suchrissnummer2'],$GUI->formvars['suchfortfuehrung'],$GUI->formvars['suchfortfuehrung2'],$GUI->formvars['suchpolygon'],$GUI->formvars['suchantrnr'], $GUI->formvars['sdatum'],$GUI->formvars['sdatum2'], $GUI->formvars['sVermStelle'], $GUI->formvars['suchbemerkung'], $GUI->formvars['flur_thematisch'], $GUI->formvars['alle_der_messung'], $GUI->formvars['order']);
			# Die Anzeigeparameter werden so gesetzt, daß genau das gezeigt wird, wonach auch gesucht wurde.
			# bzw. was als Suchparameter im Formular angegeben wurde.
			$GUI->setNachweisAnzeigeparameter($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id, $GUI->formvars['suchhauptart'],$GUI->formvars['suchhauptart']);
		}
    # Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
    $GUI->formvars = array_merge($GUI->formvars, $GUI->getNachweisParameter($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id));
    # Nachweisobjekt bilden
    $GUI->nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
		$GUI->hauptdokumentarten = $GUI->nachweis->getHauptDokumentarten();
    # Suchparameter in Ordnung
    # Recherchieren nach den Nachweisen
		$ret=$GUI->nachweis->getNachweise(0,$GUI->formvars['suchpolygon'],$GUI->formvars['suchgemarkung'],$GUI->formvars['suchstammnr'],$GUI->formvars['suchrissnummer'],$GUI->formvars['suchfortfuehrung'],$GUI->formvars['suchhauptart'],$GUI->formvars['richtung'],$GUI->formvars['abfrageart'], $GUI->formvars['order'],$GUI->formvars['suchantrnr'], $GUI->formvars['sdatum'], $GUI->formvars['sVermStelle'], $GUI->formvars['suchgueltigkeit'], $GUI->formvars['sdatum2'], $GUI->formvars['suchflur'], $GUI->formvars['flur_thematisch'], $GUI->formvars['suchunterart'], $GUI->formvars['suchbemerkung'], NULL, $GUI->formvars['suchstammnr2'], $GUI->formvars['suchrissnummer2'], $GUI->formvars['suchfortfuehrung2'], $GUI->formvars['suchgeprueft'], $GUI->formvars['alle_der_messung'], $GUI->formvars['suchformat'], $GUI->formvars['lea_id']);
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
				if ($GUI->user->rolle->querymode == 1) {
					$GUI->nachweisAnzeige();
				}
				else {
					$GUI->rechercheFormAnzeigen();
				}
      }
      else {
				if ($GUI->user->rolle->querymode == 1) {
					if (in_array($GUI->formvars['abfrageart'], ['indiv_nr', 'antr_nr'])) {
						# Zoom auf Nachweise
						for ($i=0; $i < $GUI->nachweis->erg_dokumente; $i++) {
							$ids[] = $GUI->nachweis->Dokumente[$i]['id'];
						}
						$GUI->loadMap('DataBase');
						$GUI->zoomToNachweise($GUI->nachweis, $ids, 10);
						$GUI->user->rolle->saveSettings($GUI->map->extent);
					}
					$GUI->zoomed = true;
				}
				# Anzeige des Rechercheergebnisses
        $GUI->nachweisAnzeige();
      }
    }
  };
	
	$GUI->zusammenstellenUebergabeprotokollNachweise = function($antr_nr) use ($GUI){
    if ($antr_nr==''){
			$GUI->add_message('error', 'Wählen Sie bitte eine Antragsnummer aus!');
      $GUI->Antraege_Anzeigen();
    }
    else{
			$explosion = explode('~', $antr_nr);
			$antr_nr = $explosion[0];
			$stelle_id = $explosion[1];
			$GUI->nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
			$GUI->hauptarten = $GUI->nachweis->getHauptDokumentarten();
			if($GUI->formvars['hauptart'] == '')$GUI->formvars['hauptart'] = array_keys($GUI->hauptarten);
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
		
	$GUI->erzeugenZuordnungFlst_CSV = function($path) use ($GUI){
		$intersections = $GUI->antrag->getIntersectedFlst();
		$csv = utf8_decode('Flur;Antragsnummer;Rissnummer;'.(NACHWEIS_SECONDARY_ATTRIBUTE != '' ? NACHWEIS_SECONDARY_ATTRIBUTE.';' : '').'Flurstück;Anteil [m²];Anteil [%]').chr(10);
		foreach($intersections as $intersection){
			$csv .= implode(';', $intersection).chr(10);
		}
		$fp=fopen($path.'Zuordnung-Flurstuecke.csv','wb');
		fwrite($fp, $csv);
		fclose($fp);
	};
	
	$GUI->erzeugenUebersicht_CSV = function($path) use ($GUI){
		$columns['id'] = 'id';
		$columns['flurid'] = 'Flur';
		$columns['stammnr'] = 'Antragsnummer';
		$columns['blattnummer'] = 'Blattnummer';
		$columns['rissnummer'] = 'Rissnummer';
		$columns['unterart_name'] = 'Art';
		$columns['datum'] = 'Datum';
		$columns['fortfuehrung'] = 'Fortführung';
		$columns['vermst'] = 'Vermessungsstelle';
		$columns['gueltigkeit'] = 'Gültigkeit';
		$columns['geprueft'] = 'geprüft';
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
  
	$GUI->erzeugenUebersicht_HTML = function($path, $light) use ($GUI){
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
			var nachweise = new Array();\n";
			
		for($i = 0; $i < count($GUI->nachweis->Dokumente); $i++){
			$GUI->nachweis->Dokumente[$i]['bearbeitungshinweis'] = 'mailto:' . $GUI->nachweis->Dokumente[$i]['email'] . '?subject=Bearbeitungshinweis zum Nachweis ' . $GUI->nachweis->Dokumente[$i]['client_nachweis_id'];
			$json = str_replace('\\"', '\\\"', str_replace('\\\"', '"', str_replace("'", "\'", str_replace('\\r', '\\\r', str_replace('\\n', '\\\n', str_replace('\\t', '\\\t', json_encode($GUI->nachweis->Dokumente[$i])))))));
			$html.= "			nachweise.push(JSON.parse('".$json."'));\n";
		}	
		
		$html.= "
			var columns = new Array();
			columns['id'] = 'ID';
			columns['flurid'] = 'Flur';
			columns['stammnr'] = 'Antragsnummer';
			columns['blattnummer'] = 'Blattnummer';
			columns['rissnummer'] = 'Rissnummer';
			columns['unterart_name'] = 'Art';
			columns['datum'] = 'Datum';
			columns['fortfuehrung'] = 'Fortführung';
			columns['vermst'] = 'Vermessungsstelle';
			columns['gueltigkeit'] = 'Gültigkeit';
			columns['geprueft'] = 'geprüft';
			columns['format'] = 'Format';
			columns['dokument_path'] = 'Dokument';
			columns['bearbeitungshinweis'] = 'Bearbeitungshinweis';
			
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
							options = null;
							if(key == 'dokument_path' && value != null){
								path_parts = value.split('/');
								filename = path_parts[path_parts.length-1];
								a = document.createElement('a');";
								if($light)$html.="a.href = '../../'+filename;";
								else $html.= "a.href = value;";
								$html.="
								a.target = '_blank';
								a.setAttribute('onmouseover', \"showPreview('\"+filename+\"')\");
								a.onmouseout = function(){hidePreview()};
								a.innerHTML = filename;
								cellcontent = a;
							}
							else if(key == 'bearbeitungshinweis' && value != null) {
								a = document.createElement('a');
								a.target = '_blank';
								a.href = value;
								a.innerHTML = 'Hinweis senden';
								cellcontent = a;
							}
							else {
								cellcontent = document.createTextNode(value || '');
								options = document.createElement('input');
								options.className='options';
								options.type='button';
								options.setAttribute('onclick', \"showFilterForm(this.parentNode, '\"+key+\"', '\"+value+\"')\");
								options.value= '\u25BD';	// 2630
							}
							td.appendChild(cellcontent);
							if (options != null)td.appendChild(options);
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
	
	$GUI->erzeugenUebergabeprotokollNachweise = function($path = NULL, $with_search_params = false) use ($GUI){
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
					$name = sonderzeichen_umwandeln($GUI->user->Name);
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
  
	$GUI->check_nachweis_poly = function() use ($GUI){
		$GUI->nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
		echo $GUI->nachweis->check_poly_in_flur($GUI->formvars['umring'], $GUI->formvars['flur'], $GUI->formvars['gemkgschl'], $GUI->user->rolle->epsg_code);
		echo '█check_poly();';
	};

	$GUI->nachweisFormSenden = function() use ($GUI){
    #2005-11-24_pk
    $GUI->nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
    $hauptarten = $GUI->nachweis->getHauptDokumentarten();
		$dokumentarten = $GUI->nachweis->getDokumentarten();
		$GUI->formvars['unterart'] = $GUI->formvars['unterart_'.$GUI->formvars['hauptart']];
    $GUI->formvars['artname'] = strtolower($dokumentarten[$GUI->formvars['hauptart']][$GUI->formvars['unterart']]['abkuerzung'] ?: $hauptarten[$GUI->formvars['hauptart']]['abkuerzung']);
		# Zusammensetzen der flurid
    $GUI->formvars['flurid']=$GUI->formvars['Gemarkung'].str_pad(intval(trim($GUI->formvars['Flur'])),3,'0',STR_PAD_LEFT);
    # Zusammensetzen der übergebenen Parameter für das Polygon
    $GUI->formvars['umring'] = $GUI->formvars['newpathwkt'];
    ######################################
    # Eingabe eines neuen Dokumentes
    if ($GUI->formvars['id']=='') {
      # Prüfen der Eingabewerte
      #echo '<br>Prüfen der Eingabewerte.';
      $ret=$GUI->nachweis->pruefeEingabedaten($GUI->formvars['id'], $GUI->formvars['datum'],$GUI->formvars['VermStelle'],$GUI->formvars['hauptart'],$GUI->formvars['gueltigkeit'],$GUI->formvars['stammnr'],$GUI->formvars['rissnummer'], $GUI->formvars['fortfuehrung'], $GUI->formvars['Blattformat'],$GUI->formvars['Blattnr'],true,$GUI->formvars['Bilddatei_name'],$GUI->formvars['pathlength'],$GUI->formvars['umring'], $GUI->formvars['flurid'], $GUI->formvars['Blattnr'], $dokumentarten[$GUI->formvars['hauptart']][$GUI->formvars['unterart']]['pok_pflicht']);
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
				$zieldatei = NACHWEISDOCPATH.$GUI->formvars['flurid'].'/'.$GUI->nachweis->buildNachweisNr($GUI->formvars[NACHWEIS_PRIMARY_ATTRIBUTE], $GUI->formvars[NACHWEIS_SECONDARY_ATTRIBUTE]).'/'.$GUI->formvars['artname'].'/'.$GUI->formvars['zieldateiname'];
        $ret=$GUI->nachweis->dokumentenDateiHochladen($GUI->formvars['Bilddatei'], $zieldatei);
        if ($ret!='') { $errmsg=$ret; }
        else {
          # Speicherung der Bilddatei erfolgreich, Eintragen in Datenbank
          $GUI->nachweis->database->begintransaction();
          $ret=$GUI->nachweis->eintragenNeuesDokument($GUI->formvars['datum'],$GUI->formvars['flurid'],$GUI->formvars['VermStelle'], $GUI->formvars['unterart'], $GUI->formvars['gueltigkeit'], $GUI->formvars['geprueft'], $GUI->formvars['stammnr'],$GUI->formvars['Blattformat'],$GUI->formvars['Blattnr'],$GUI->formvars['rissnummer'],$GUI->formvars['fortfuehrung'],$GUI->formvars['bemerkungen'],$GUI->formvars['bemerkungen_intern'],$zieldatei,$GUI->formvars['umring'], $GUI->user);
          if ($ret[0]) {
            $GUI->nachweis->database->rollbacktransaction();
						$GUI->nachweis->dokumentenDateiLoeschen($zieldatei);
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
				$GUI->add_message('notice', $ret[1]);
				$GUI->nachweisAenderungsformular();
			}
      # 1.4 Zur zur Anzeige der Rechercheergebnisse mit Meldung über Erfolg der Änderung
      # 1.4.1 Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
      #$GUI->formvars=$GUI->user->rolle->getNachweisParameter();
      #$ret=$GUI->nachweis->getNachweise(0,$GUI->formvars['suchpolygon'],$GUI->formvars['suchgemarkungflurid'],$GUI->formvars['suchstammnr'],$GUI->formvars['suchrissnummer'],$GUI->formvars['suchfortfuehrung'],$GUI->formvars['art_einblenden'],$GUI->formvars['richtung'],$GUI->formvars['abfrageart'], $GUI->formvars['order'],$GUI->formvars['suchantrnr']);
      # 1.4.2 Anzeige der Rechercheergebnisse
      #$GUI->nachweisAnzeige();
      # 1.4.3 Anzeige der Erfolgsmeldung
        #showAlert($GUI->Meldung);
      #} # end of Änderung war erfolgreich
    }
    return 1;
  };
	
	$GUI->nachweisFormAnzeigeVorlage = function() use ($GUI){
		# Nachweisdaten aus Datenbank abfragen
    $nachweis=new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
    $ret=$nachweis->getNachweise($GUI->formvars['id'],'','','','','','','','MergeIDs','',0,0);
    $nachweis->document=$nachweis->Dokumente[0];
    # Zuweisen der Werte des Dokumentes zum Formular
    $GUI->formvars['flurid']=$nachweis->document['flurid'];
    $GUI->formvars['stammnr']=$nachweis->document['stammnr'];
    $GUI->formvars['rissnummer']=$nachweis->document['rissnummer'];
    $GUI->formvars['hauptart']=$nachweis->document['hauptart'];
		$GUI->formvars['unterart_'.$GUI->formvars['hauptart']] = $nachweis->document['unterart'];
    $GUI->formvars['Blattnr']=$nachweis->document['blattnummer'];
    $GUI->formvars['datum']=$nachweis->document['datum'];
    $GUI->formvars['VermStelle']=$nachweis->document['vermstelle'];
    $GUI->formvars['Blattformat']=$nachweis->document['format'];
    $GUI->formvars['gueltigkeit']=$nachweis->document['gueltigkeit'];
		$GUI->formvars['geprueft']=$nachweis->document['geprueft'];
    $GUI->formvars['Gemeinde']=$nachweis->document['Gemeinde'];
    $GUI->formvars['Gemarkung']=substr($GUI->formvars['flurid'],0,6);
    $GUI->formvars['Flur']=intval(substr($GUI->formvars['flurid'],6,9));
    $GUI->formvars['Bilddatei']=$nachweis->document['link_datei'];
		$GUI->formvars['fortfuehrung']=$nachweis->document['fortfuehrung'];
		$GUI->formvars['bemerkungen']=$nachweis->document['bemerkungen'];
		$GUI->formvars['bemerkungen_intern']=$nachweis->document['bemerkungen_intern'];
    $GUI->formvars['id'] = '';
    $GUI->nachweisFormAnzeige($nachweis);
	};

	$GUI->nachweisFormAnzeige = function($nachweis = NULL) use ($GUI){
		$GUI->sanitize(['id' => 'int', 'FlurstKennz' => 'text']);
		include_once(PLUGINS.'alkis/model/kataster.php');
		include_once(CLASSPATH.'FormObject.php');
		if($GUI->formvars['reset_layers'])$GUI->reset_layers(NULL);
    $GUI->titel='Dokumenteneingabe';
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
    $GUI->queryable_vector_layers = $GUI->Stelle->getqueryableVectorLayers(NULL, $GUI->user->id, NULL, NULL, NULL, true, true);
  	if(!$GUI->formvars['geom_from_layer']){
      $layerset = $GUI->user->rolle->getLayer(LAYER_ID_NACHWEISE);
	    $GUI->formvars['geom_from_layer'] = $layerset[0]['geom_from_layer'];
    }
    $oldscale=round($GUI->map_scaledenom);  
		$GUI->formvars['unterart'] = $GUI->formvars['unterart_'.$GUI->formvars['hauptart']];
    if ($GUI->formvars['CMD']!=''){			
      $GUI->navMap($GUI->formvars['CMD']);
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
		elseif($GUI->formvars['zoom_layer_id'] != '')$GUI->zoom_to_max_layer_extent($GUI->formvars['zoom_layer_id']);	# zoom_to_max_layer_extent
		
		if($GUI->formvars['FlurstKennz'] != ''){		# über die Flurstückssuche gefundene Flurstücke -> Geometrie als Suchpolygon übernehmen
			$GUI->formvars['suchpolygon'] = $GUI->pgdatabase->getGeomfromFlurstuecke($GUI->formvars['FlurstKennz'], $GUI->user->rolle->epsg_code);
		}
		if($GUI->formvars['zurueck'] OR $GUI->formvars['FlurstKennz'] != ''){
			$GUI->formvars['pathwkt'] = $GUI->formvars['suchpolygon'];
			$GUI->formvars['newpathwkt'] = $GUI->formvars['suchpolygon'];
			$GUI->formvars['firstpoly'] = 'true';
			$GUI->formvars['last_doing'] = 'draw_second_polygon';
			$GUI->formvars['last_button'] = 'pgon0';
		}
    
    $GUI->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
    $GUI->drawMap();
	
		if($GUI->formvars['Gemarkung'] == '')$GUI->formvars['Gemarkung'] = $GUI->formvars['gemschl'] ?: $GUI->Lagebezeichung['gemkgschl'];
		if($GUI->formvars['Flur'] == '')$GUI->formvars['Flur'] = (int)$GUI->formvars['FlurID'] ?: $GUI->Lagebezeichung['flur'];
    
    # Abfragen der Gemarkungen
    $GemeindenStelle=$GUI->Stelle->getGemeindeIDs();
    $Gemarkung=new gemarkung('',$GUI->pgdatabase);
		if($GemeindenStelle == NULL){
			$GemkgListe=$Gemarkung->getGemarkungListeAll(NULL, NULL);
		}
		else{
			$GemkgListe=$Gemarkung->getGemarkungListeAll(array_keys($GemeindenStelle['ganze_gemeinde']), array_merge(array_keys($GemeindenStelle['ganze_gemarkung']), array_keys($GemeindenStelle['eingeschr_gemarkung'])));
		}
        
    # Erzeugen des Formobjektes für die Gemarkungsauswahl
    $GUI->GemkgFormObj=new FormObject("Gemarkung","select",$GemkgListe['GemkgID'],$GUI->formvars['Gemarkung'],$GemkgListe['Bezeichnung'],"1","","",NULL);

    # erzeugen des Formularobjektes für die VermessungsStellen
    $GUI->FormObjVermStelle=$GUI->getFormObjVermStelle('VermStelle', $GUI->formvars['VermStelle']);

    # abfragen der Dokumentarten
    $nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
		$GUI->hauptdokumentarten = $nachweis->getHauptDokumentarten();
    $GUI->dokumentarten = $nachweis->getDokumentarten();
    $GUI->output();
  };

	$GUI->nachweisAnzeige = function() use ($GUI){
    $GUI->titel='Rechercheergebnis';
    $GUI->main= PLUGINS.'nachweisverwaltung/view/nachweisanzeige.php';
    $GUI->FormObjAntr_nr=$GUI->getFormObjAntr_nr($GUI->formvars['suchantrnr'], 8);
		$GUI->noOverlayFooter = true;
    $GUI->output();
  };
	
	$GUI->nachweiseGeometrieuebernahme = function() use ($GUI){
		$GUI->nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
		$GUI->hauptdokumentarten = $GUI->nachweis->getHauptDokumentarten();
		$ret=$GUI->nachweis->Geometrieuebernahme($GUI->formvars['ref_geom'], $GUI->formvars['id'], $GUI->Stelle->isFunctionAllowed('gepruefte_Nachweise_bearbeiten'));
    $GUI->formvars = array_merge($GUI->formvars, $GUI->getNachweisParameter($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id));
		$GUI->nachweis->getNachweise(0,$GUI->formvars['suchpolygon'],$GUI->formvars['suchgemarkung'],$GUI->formvars['suchstammnr'],$GUI->formvars['suchrissnummer'],$GUI->formvars['suchfortfuehrung'],$GUI->formvars['suchhauptart'],$GUI->formvars['richtung'],$GUI->formvars['abfrageart'], $GUI->formvars['order'],$GUI->formvars['suchantrnr'], $GUI->formvars['sdatum'], $GUI->formvars['sVermStelle'], $GUI->formvars['suchgueltigkeit'], $GUI->formvars['sdatum2'], $GUI->formvars['suchflur'], $GUI->formvars['flur_thematisch'], $GUI->formvars['suchunterart'], $GUI->formvars['suchbemerkung'], NULL, $GUI->formvars['suchstammnr2'], $GUI->formvars['suchrissnummer2'], $GUI->formvars['suchfortfuehrung2'], $GUI->formvars['suchgeprueft']);
    if($ret[0] != ''){
			$GUI->add_message('error', $ret[0]);
    }
    else {
			$GUI->add_message('notice', $ret[1]);
    }
    $GUI->nachweisAnzeige();			
	};

	$GUI->nachweiseZuAuftrag = function() use ($GUI){
    # echo 'Start der Zuweisung der Dokumente zum Antrag';
    # Hinzufügen von recherchierten Nachweisen zu einem Auftrag
		$GUI->nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
		$GUI->hauptdokumentarten = $GUI->nachweis->getHauptDokumentarten();
    $GUI->dokumentarten = $GUI->nachweis->getDokumentarten();
		if ($GUI->formvars['lea_id'] == '') {
			$explosion = explode('~', $GUI->formvars['suchantrnr']);
			$GUI->formvars['suchantrnr'] = $explosion[0];
			$stelle_id = $explosion[1];
			$ret=$GUI->nachweis->pruefe_Auftrag_hinzufuegen_entfernen($GUI->formvars['suchantrnr'], $stelle_id);
		}
		else {
			$stelle_id = NULL;
			$ret = '';
		}
    if ($ret!=''){ # Fehler bei der Prüfung der Eingangsparameter
      $errmsg=$ret;
    }
    else {
      # Hinzufügen der Dokumente zum Auftrag
      $ret=$GUI->nachweis->zum_Auftrag_hinzufuegen($GUI->formvars['suchantrnr'], $stelle_id, $GUI->formvars['id'], $GUI->formvars['lea_id']);
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
    $GUI->formvars = array_merge($GUI->formvars, $GUI->getNachweisParameter($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id));
		$ret=$GUI->nachweis->getNachweise(0,$GUI->formvars['suchpolygon'],$GUI->formvars['suchgemarkung'],$GUI->formvars['suchstammnr'],$GUI->formvars['suchrissnummer'],$GUI->formvars['suchfortfuehrung'],$GUI->formvars['showhauptart'],$GUI->formvars['richtung'],$GUI->formvars['abfrageart'], $GUI->formvars['order'],$GUI->formvars['suchantrnr'], $GUI->formvars['sdatum'], $GUI->formvars['sVermStelle'], $GUI->formvars['suchgueltigkeit'], $GUI->formvars['sdatum2'], $GUI->formvars['suchflur'], $GUI->formvars['flur_thematisch'], $GUI->formvars['suchunterart'], $GUI->formvars['suchbemerkung'], NULL, $GUI->formvars['suchstammnr2'], $GUI->formvars['suchrissnummer2'], $GUI->formvars['suchfortfuehrung2'], $GUI->formvars['suchgeprueft']);
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

	$GUI->nachweiseZuAuftragEntfernen = function() use ($GUI){
    # nachweisobjekt erstellen
    $GUI->nachweis = new nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
		$GUI->hauptdokumentarten = $GUI->nachweis->getHauptDokumentarten();
    $GUI->dokumentarten = $GUI->nachweis->getDokumentarten();
		if ($GUI->formvars['lea_id'] == '') {
			$explosion = explode('~', $GUI->formvars['suchantrnr']);
			$suchantrnr = $explosion[0];
			$stelle_id = $explosion[1];
			# Eingabeparameter prüfen
			$ret=$GUI->nachweis->pruefe_Auftrag_hinzufuegen_entfernen($suchantrnr,$stelle_id);
		}
		else {
			$stelle_id = NULL;
			$ret = '';
		}
		if($ret!=''){ # Fehler bei der Prüfung der Eingangsparameter
			$errmsg=$ret;
		}
		else {
			# Eingabeparameter in Ordnung
			# Nachweise aus Antrag entfernen
			$result=$GUI->nachweis->aus_Auftrag_entfernen($suchantrnr, $stelle_id, $GUI->formvars['id'], $GUI->formvars['lea_id']);
			$errmsg=$result[0];
			$okmsg=$result[1];
		} # ende Eingabeparameter sind ok
		# Zurück zur Anzeige des Rechercheergebnisses mit Meldung über Zuordnung der Dokumente zum Auftrag
		# Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
		$GUI->formvars = array_merge($GUI->formvars, $GUI->getNachweisParameter($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id));
		$GUI->nachweis->getNachweise(0,$GUI->formvars['suchpolygon'],$GUI->formvars['suchgemarkung'],$GUI->formvars['suchstammnr'],$GUI->formvars['suchrissnummer'],$GUI->formvars['suchfortfuehrung'],$GUI->formvars['showhauptart'],$GUI->formvars['richtung'],$GUI->formvars['abfrageart'], $GUI->formvars['order'],$GUI->formvars['suchantrnr'], $GUI->formvars['sdatum'], $GUI->formvars['sVermStelle'], $GUI->formvars['suchgueltigkeit'], $GUI->formvars['sdatum2'], $GUI->formvars['suchflur'], $GUI->formvars['flur_thematisch'], $GUI->formvars['suchunterart'], $GUI->formvars['suchbemerkung'], NULL, $GUI->formvars['suchstammnr2'], $GUI->formvars['suchrissnummer2'], $GUI->formvars['suchfortfuehrung2'], $GUI->formvars['suchgeprueft'], NULL, NULL, $GUI->formvars['lea_id']);
		# Anzeige der Rechercheergebnisse			
		if($errmsg)$GUI->add_message('error', $errmsg);
		if($okmsg)$GUI->add_message('notice', $okmsg);
		$GUI->nachweisAnzeige();
  };

	$GUI->nachweisDokumentAnzeigen = function() use ($GUI){
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
  
	$GUI->bestaetigungsformAnzeigen = function() use ($GUI){
    $GUI->titel='Bestätigung';
    $GUI->main = PLUGINS.'nachweisverwaltung/view/bestaetigungsformular.php';
    $GUI->output();
  };
	
	$GUI->nachweisLoeschen = function() use ($GUI){
		$GUI->nachweis = new nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
		$GUI->hauptdokumentarten = $GUI->nachweis->getHauptDokumentarten();
		$idListe=$GUI->formvars['id'];
		$ret=$GUI->nachweis->nachweiseLoeschen($idListe,1);
		if ($ret[0]) { # Fehler beim Löschen in Fehlermeldung übergeben
			$GUI->Fehlermeldung=$ret[1];
		}
		else {
			$GUI->add_message('info', $ret[1]);
		}
		# Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
		$GUI->formvars = array_merge($GUI->formvars, $GUI->getNachweisParameter($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id));
		# Abfragen der Nachweise entsprechend der eingestellten Suchparameter
		$ret=$GUI->nachweis->getNachweise(0,$GUI->formvars['suchpolygon'],$GUI->formvars['suchgemarkung'],$GUI->formvars['suchstammnr'],$GUI->formvars['suchrissnummer'],$GUI->formvars['suchfortfuehrung'],$GUI->formvars['suchhauptart'],$GUI->formvars['richtung'],$GUI->formvars['abfrageart'], $GUI->formvars['order'],$GUI->formvars['suchantrnr'], $GUI->formvars['sdatum'], $GUI->formvars['sVermStelle'], $GUI->formvars['suchgueltigkeit'], $GUI->formvars['sdatum2'], $GUI->formvars['suchflur'], $GUI->formvars['flur_thematisch'], $GUI->formvars['suchunterart'], $GUI->formvars['suchbemerkung'], NULL, $GUI->formvars['suchstammnr2'], $GUI->formvars['suchrissnummer2'], $GUI->formvars['suchfortfuehrung2'], $GUI->formvars['suchgeprueft']);
		if ($ret!='') {
			$GUI->Fehlermeldung.=$ret;
		}
		# Anzeige der Rechercheergebnisse
		$GUI->nachweisAnzeige();
  };
	
	$GUI->rechercheFormAnzeigen = function() use ($GUI){
		$GUI->sanitize(['dokauswahl_name' => 'text', 'dokauswahlen' => 'int', 'FlurstKennz' => 'text']);
		include_once(PLUGINS.'alkis/model/kataster.php');
		include_once(CLASSPATH.'FormObject.php');
		if($GUI->formvars['bufferwidth'] == '')$GUI->formvars['bufferwidth'] = 2;
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
		if($GUI->formvars['CMD'] == ''){		# wenn nicht navigiert wurde
			# Abfragen aller aktuellen Such- und Anzeigeparameter aus der Datenbank
			$nachweisSuchParameter=$GUI->getNachweisParameter($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id);
			$GUI->formvars=array_merge($GUI->formvars,$nachweisSuchParameter);
			if($GUI->formvars['FlurstKennz'] != ''){		# über die Flurstückssuche gefundene Flurstücke -> Geometrie als Suchpolygon übernehmen
				$GUI->formvars['suchpolygon'] = $GUI->pgdatabase->getGeomfromFlurstuecke($GUI->formvars['FlurstKennz'], $GUI->user->rolle->epsg_code);
			}
			if($GUI->formvars['zurueck'] OR $GUI->formvars['FlurstKennz'] != ''){
				$GUI->formvars['pathwkt'] = $GUI->formvars['suchpolygon'];
				$GUI->formvars['newpathwkt'] = $GUI->formvars['suchpolygon'];
				$GUI->formvars['firstpoly'] = 'true';
				$GUI->formvars['last_doing'] = 'draw_second_polygon';
				$GUI->formvars['last_button'] = 'pgon0';
			}
			else{
				$GUI->formvars['alle_der_messung'] = NULL;
			}
		}
		# die Parameter einer gespeicherten Dokumentauswahl laden
		if($GUI->formvars['dokauswahlen'] != ''){
			$GUI->selected_dokauswahlset = $GUI->get_Dokumentauswahl($GUI->user->rolle->stelle_id, $GUI->user->rolle->user_id, $GUI->formvars['dokauswahlen']);
			$GUI->formvars['dokauswahl_name'] = $GUI->selected_dokauswahlset[0]['name'];			
			$GUI->formvars['suchhauptart'] = explode(',', $GUI->selected_dokauswahlset[0]['suchhauptart']);
			$GUI->formvars['suchunterart'] = explode(',', $GUI->selected_dokauswahlset[0]['suchunterart']);
		}
    # erzeugen des Formularobjektes für Antragsnr
    $GUI->FormObjAntr_nr=$GUI->getFormObjAntr_nr($GUI->formvars['suchantrnr']);    
    $GUI->titel='Dokumentenrecherche';
    $GUI->main= PLUGINS."nachweisverwaltung/view/dokumentenabfrageformular.php";
    
		$nachweis = new Nachweis($GUI->pgdatabase, $GUI->user->rolle->epsg_code);
		$GUI->hauptdokumentarten = $nachweis->getHauptDokumentarten();
    $GUI->dokumentarten = $nachweis->getDokumentarten();
			
	# Abfragen der Gemarkungen
    $GemeindenStelle=$GUI->Stelle->getGemeindeIDs();
    $Gemarkung=new gemarkung('',$GUI->pgdatabase);
		if($GemeindenStelle == NULL){
			$GemkgListe=$Gemarkung->getGemarkungListeAll(NULL, NULL);
		}
		else{
			$GemkgListe=$Gemarkung->getGemarkungListeAll(array_keys($GemeindenStelle['ganze_gemeinde']), array_merge(array_keys($GemeindenStelle['ganze_gemarkung']), array_keys($GemeindenStelle['eingeschr_gemarkung'])));
		}
    # Erzeugen des Formobjektes für die Gemarkungsauswahl
    $GUI->GemkgFormObj=new FormObject("suchgemarkung","select",$GemkgListe['GemkgID'],$GUI->formvars['suchgemarkung'],$GemkgListe['Bezeichnung'],"1","","", 190);
		$GUI->GemkgFormObj->addJavaScript("onchange","updateGemarkungsschluessel(this.value)");
		$GUI->GemkgFormObj->insertOption('',0,'--Auswahl--',0);
			
    # erzeugen des Formularobjektes für die VermessungsStellen
    $GUI->FormObjVermStelle=$GUI->getFormObjVermStelle('sVermStelle', $GUI->formvars['sVermStelle']);
    $GUI->FormObjVermStelle->insertOption('', NULL, '--- Auswahl ---', 0);    
    # aktuellen Kartenausschnitt laden + zeichnen!
    $saved_scale = $GUI->reduce_mapwidth(207);
		$GUI->loadMap('DataBase');
		if($saved_scale != NULL AND !isset($GUI->formvars['datum']))$GUI->scaleMap($saved_scale);		# nur beim ersten Aufruf den Extent so anpassen, dass der alte Maßstab wieder da ist
    if ($GUI->formvars['CMD']!='') {
      # Nur Navigieren
      $GUI->navMap($GUI->formvars['CMD']);
    }
		elseif($GUI->formvars['lea_id'] != '') {
			$GUI->zoomToLEA($nachweis, $GUI->formvars['lea_id'], 10);
		}
	
    $GUI->queryable_vector_layers = $GUI->Stelle->getqueryableVectorLayers(NULL, $GUI->user->id, NULL, NULL, NULL, true, true);
    # Spaltenname und from-where abfragen
  	if(!$GUI->formvars['geom_from_layer']){
      $layerset = $GUI->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
      $GUI->formvars['geom_from_layer'] = $layerset[0]['Layer_ID'];
    }
    $GUI->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
    $GUI->drawMap();
			
    $GUI->output();
  };

	$GUI->vermessungsAntragEingabeForm = function() use ($GUI){
    $GUI->menue='menue.php';
    $GUI->main= PLUGINS.'nachweisverwaltung/view/antragsnr_eingabe_form.php';
    $GUI->FormObjVermStelle=$GUI->getFormObjVermStelle('VermStelle', $GUI->formvars['VermStelle']);
    $GUI->FormObjVermArt=$GUI->getFormObjVermArt($GUI->formvars['verm_art']);
    $GUI->output();
  };
	
	$GUI->zoomToNachweise = function($nachweis, $ids, $border) use ($GUI){
    # Abfragen der Ausdehnung des Umringes des Nachweises
    $ret=$nachweis->getBBoxAsRectObj($ids);
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

	$GUI->zoomToLEA = function($nachweis, $lea_id, $border) use ($GUI){
    # Abfragen der Ausdehnung des Umringes des Nachweises
    $ret = $nachweis->getLeaBBoxAsRectObj($lea_id);
    if ($ret[0]) {
      # Fehler bei der Abfrage der BoundingBox
      # Es erfolgt keine Änderung der aktuellen Ausdehnung
    }
    else {
      $rect=$ret[1];
      # Berechnen des Randes in Abhängigkeit vom Parameter border gegeben in Prozent
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
      $GUI->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
			$GUI->map_scaledenom = $GUI->map->scaledenom;
    }
  };
	
# 2016-11-03 H.Riedel - pkz durch pkn ersetzt
	$GUI->festpunkteZuAntragZeigen = function() use ($GUI){
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
	$GUI->festpunkteZeigen = function() use ($GUI){
		$GUI->sanitize(['pkn' => 'text']);
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

	$GUI->zoomToFestpunkte = function($FestpunktListe,$border) use ($GUI){
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

	$GUI->festpunkteErgebnisanzeige = function() use ($GUI){
    $GUI->titel='Suche nach Festpunkten';
    #$GUI->main='festpunktsuchform.php';
    $GUI->qlayerset[0]['shape']=$GUI->festpunkte->liste;
    $i=0;
    $GUI->main = PLUGINS.'nachweisverwaltung/view/Festpunkte.php';
    $GUI->output();
  };

	$GUI->festpunkteWahl = function() use ($GUI){
    $GUI->titel='Suche nach Festpunkten';
    $GUI->main = PLUGINS.'nachweisverwaltung/view/festpunktsuchform.php';
    $GUI->output();
  };

# 2016-11-03 H.Riedel - pkz durch pkn ersetzt
	$GUI->festpunkteSuchen = function() use ($GUI){
		$GUI->sanitize(['antr_selected' => 'text', 'pkn' => 'text', 'kiloquad' => 'text']);
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
	
	$GUI->sendeFestpunktskizze = function($Bild,$Pfad) use ($GUI){
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
    $festpunkte->getFestpunkte($abgefragtefestpunkte,array('TP','AP','SiP','SVP','OP'),'','','','','','','pkn');
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
	$GUI->ordneFestpunktSkizzen = function() use ($GUI){
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
	$GUI->festpunkteZuAuftragFormular = function() use ($GUI){
    $GUI->titel='Festpunkte zum Auftrag Hinzufügen';
    $GUI->main = PLUGINS.'nachweisverwaltung/view/festpunktezuauftragformular.php';
    $GUI->pkn=array_keys($GUI->formvars['pkn']);
    $GUI->anzPunkte=count($GUI->pkn);
    $GUI->FormObjAntr_nr=$GUI->getFormObjAntr_nr('');
    $GUI->FormObjAntr_nr->select['name']='antr_selected';
    $GUI->output();
  };

# 2016-11-03 H.Riedel - pkz durch pkn ersetzt
	$GUI->festpunkteZuAuftragSenden = function() use ($GUI){
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
	$GUI->festpunkteInKVZschreiben = function() use ($GUI){
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

	$GUI->vermessungsantragsFormular = function() use ($GUI){
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
	
	$GUI->vermessungsantragAnlegen = function() use ($GUI){
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
	
	$GUI->vermessungsantragAendern = function() use ($GUI){
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
	
	$GUI->Antraege_Anzeigen = function() use ($GUI){
    $GUI->titel='Antr&auml;ge';
    $GUI->main = PLUGINS.'nachweisverwaltung/view/antragsanzeige.php';
    $GUI->antrag = new antrag('','',$GUI->pgdatabase);
    $GUI->antrag->getAntraege('','',$GUI->formvars['richtung'],$GUI->formvars['order'], $GUI->Stelle->id);
    $GUI->output();
  };

	$GUI->Antrag_Loeschen = function() use ($GUI){
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
	
	$GUI->getFormObjVermStelle = function($name, $VermStelle) use ($GUI){
		include_once(CLASSPATH.'FormObject.php');
    $VermStObj = new Vermessungsstelle($GUI->pgdatabase);
    $back=$VermStObj->getVermStelleListe();
    if ($back[0]=='') {
      # Fehlerfreie Datenabfrage
      $FormObjVermStelle=new FormObject($name,'select',$back[1]['id'],array($VermStelle),$back[1]['name'],1,0,0, 260, NULL, '', false);
    }
    else {
      $FormObjVermStelle=new FormObject($name,'text',array($back[0]),'','',25,255,0,NULL);
    }
    return $FormObjVermStelle;
  };

	$GUI->getFormObjVermArt = function($verm_art) use ($GUI){
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

	$GUI->getFormObjAntr_nr = function($antr_nr, $size = 1) use ($GUI){
		include_once(CLASSPATH.'FormObject.php');
    $Antrag = new Antrag($antr_nr,$GUI->Stelle->id,$GUI->pgdatabase);
    $back=$Antrag->getAntragsnr_Liste($GUI->Stelle->id);
    if ($back[0]=='') {
      # Fehlerfreie Datenabfrage
      $FormObjAntr_nr=new FormObject('suchantrnr','select',$back[1]['antr_nr_stelle_id'],array($antr_nr),$back[1]['antr_nr'],$size,0,0,NULL);
    }
    else {
      $FormObjAntr_nr=new FormObject('suchantrnr','text',array($back[0]),'','',25,255,0,NULL);
    }
    return $FormObjAntr_nr;
  };
	
	$GUI->sendImage = function($name,$format) use ($GUI){
    #var_dump(gd_info());
    #phpinfo();
    $im = ImageCreateFromPng($name);
    ob_end_clean();
    ob_start("output_handler");
    ImagePNG($im);
    ob_end_flush();
  };

?>