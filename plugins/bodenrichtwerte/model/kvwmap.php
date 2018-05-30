<?	
	$GUI = $this;
	
	$this->bodenRichtWertErfassung = function() use ($GUI){
		include_once(CLASSPATH.'FormObject.php');
    if ($GUI->formvars['oid']=='') {
      $GUI->titel='Bodenrichtwerterfassung';
    }
    else {
      $GUI->titel='Bodenrichtwertzone Ändern';
    }
    if($GUI->formvars['go'] == 'Bodenrichtwertformular_Anzeige'){
    	$GUI->titel='Bodenrichtwertzone Anzeigen';
      $GUI->formvars['loc_y'] = $GUI->formvars['loc_x'] = $GUI->formvars['pathwkt'] = $GUI->formvars['newpath'] = $GUI->formvars['newpathwkt'] = '';
    }
    $layer = $GUI->user->rolle->getLayer(LAYERNAME_BODENRICHTWERTE);
    $GUI->formvars['boris_layer_id'] = $layer[0]['Layer_ID'];
    $GUI->main = PLUGINS."bodenrichtwerte/view/bodenrichtwerterfassung_vboris.php";
    $saved_scale = $GUI->reduce_mapwidth(100);
		$GUI->loadMap('DataBase');
		if($saved_scale != NULL)$GUI->scaleMap($saved_scale);		# nur beim ersten Aufruf den Extent so anpassen, dass der alte Maßstab wieder da ist
    $GUI->Lagebezeichnung = $GUI->getLagebezeichnung($GUI->user->rolle->epsg_code);
    if($GUI->formvars['gemeinde'] == ''){
    	$GUI->formvars['gemeinde'] = $GUI->Lagebezeichnung['gemeinde'];
    }
    if($GUI->formvars['gemarkung'] == ''){
    	$GUI->formvars['gemarkung'] = $GUI->Lagebezeichnung['gemkgschl'];
    }
    # Bodenrichtwertzonenobjekt erzeugen
    $bodenrichtwertzone=new bodenrichtwertzone($GUI->pgdatabase, $layer[0]['epsg_code'], $GUI->user->rolle->epsg_code);
    # Formularobjekt für Gemeinde bilden
    $GemObj=new gemeinde(0,$GUI->pgdatabase);
  	$Gemeindeliste=$GemObj->getGemeindeListe(NULL);
    $GUI->GemFormObj=new FormObject("gemeinde","select",$Gemeindeliste["ID"],$GUI->formvars['gemeinde'],$Gemeindeliste["Name"],1,0,0,158);
    $GUI->GemFormObj->addJavaScript('onchange', "update_require_attribute('gemarkung', ".$GUI->formvars['boris_layer_id'].", this.value);");
    # Formularobjekt für Gemarkung bilden
    $GemkgObj = new gemarkung(0,$GUI->pgdatabase);
  	$gemarkungsliste=$GemkgObj->getGemarkungListe(array($GUI->formvars['gemeinde']),array());
    $GUI->GemkgFormObj=new FormObject('gemarkung','select',$gemarkungsliste['GemkgID'],$GUI->formvars['gemarkung'],$gemarkungsliste['Name'],1,0,0,158);
    
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
    $oldscale=round($GUI->map_scaledenom);
    if ($GUI->formvars['CMD']!='') {
      # Nur Navigieren
      $GUI->navMap($GUI->formvars['CMD']);
      $GUI->user->rolle->saveDrawmode($GUI->formvars['always_draw']);
    }
    elseif($oldscale!=$GUI->formvars['nScale'] AND $GUI->formvars['nScale'] != '') {
      $GUI->scaleMap($GUI->formvars['nScale']);
    }
  	if($GUI->formvars['CMD'] != 'previous' AND $GUI->formvars['CMD'] != 'next'){
    	$currenttime=date('Y-m-d H:i:s',time());
    	$GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
    }
    $GUI->drawMap();
		$GUI->saveMap('');
    $GUI->output();
  };
	
	$this->aendernBodenRichtWert = function() use ($GUI){
    # Bodenrichtwertzone aus der Datenbank abfragen
    $layer = $GUI->user->rolle->getLayer(LAYERNAME_BODENRICHTWERTE);
    $bodenrichtwertzone=new bodenrichtwertzone($GUI->pgdatabase, $layer[0]['epsg_code'], $GUI->user->rolle->epsg_code);
    $ret=$bodenrichtwertzone->getBodenrichtwertzonen($GUI->formvars['oid']);
    if ($ret[0]) {
      # Fehler bei der Abfrage
      showAlert($ret);
    }
    else {
      # Abfrage war erfolgreich
      # Zoom zum Polygon des Dokumentes
      $GUI->loadMap('DataBase');
      $GUI->zoomToBodenrichtwertzone($GUI->formvars['oid'],20);
      $GUI->user->rolle->saveSettings($GUI->map->extent);
      $GUI->user->rolle->readSettings();
      # Zuweisen der Werte der Zone zum Formular
      $GUI->formvars=array_merge($GUI->formvars,$bodenrichtwertzone->zonen[0]);
      $datumteile=explode('-',$GUI->formvars['datum']);
      $GUI->formvars['datum']=$datumteile[0];

      $PolygonAsSVG = transformCoordsSVG($GUI->formvars['svg_umring']);
      $GUI->formvars['newpath'] = $PolygonAsSVG;
      $GUI->formvars['newpathwkt'] = $GUI->formvars['wkt_umring'];
      $GUI->formvars['pathwkt'] = $GUI->formvars['newpathwkt'];

      # Bildung der Textposition zur SVG-Ausgabe
      $point_teil=strrpos($GUI->formvars['wkt_textposition'],'(')+1;
      $point_paar=substr($GUI->formvars['wkt_textposition'],$point_teil,count($point_teil)-2);
      $point_xy=explode(' ',$point_paar);
      $GUI->formvars['loc_x']=$point_xy[0];
      $GUI->formvars['loc_y']=$point_xy[1];
    }
    $GUI->bodenRichtWertErfassung();
  };
	
	$this->bodenRichtWertFormSenden = function() use ($GUI){
    # Zusammensetzen der übergebenen Parameter für das Polygon und die Textposition
    #echo 'formvars[loc_x, loc_y]: '.$GUI->formvars['loc_x'].', '.$GUI->formvars['loc_x'];
    if ($GUI->formvars['loc_x']!='' OR $GUI->formvars['loc_y']!='') {
      $location_x = $GUI->formvars['loc_x'];
      $location_y = $GUI->formvars['loc_y'];
      $GUI->formvars['textposition']="POINT(".$location_x." ".$location_y.")";
      #echo '<br/>formvars[textposition]: '.$GUI->formvars['textposition'];
    }
    else {
      $GUI->formvars['textposition']="";
    }
    $GUI->formvars['umring'] = $GUI->formvars['newpathwkt'];
    # Bodenrichtwertzonenobjekt erzeugen
    $layer = $GUI->user->rolle->getLayer(LAYERNAME_BODENRICHTWERTE);
    $bodenrichtwertzone=new bodenrichtwertzone($GUI->pgdatabase, $layer[0]['epsg_code'], $GUI->user->rolle->epsg_code);

		if ($GUI->formvars['oid']=='') {
			# 2. eintragenNeueZone
			$ret=$bodenrichtwertzone->eintragenNeueZone($GUI->formvars);
			if ($ret[0]) {
				# 2.1 eintrageung fehlerhaft
				$GUI->Meldung=$ret[1];
			}
			else {
				#  2.2 eintragung erfolgreich
				$alertmsg='\nBodenrichtwertzone erfolgreich in die Datenbank eingetragen.'.
				$GUI->formvars['pathx']='';    $GUI->formvars['loc_x']='';
				$GUI->formvars['pathy']='';    $GUI->formvars['loc_y']='';
				$GUI->formvars['umring']='';   $GUI->formvars['textposition']='';
			}
		}
		else {
			# 3. aktualisierenZone
			$ret=$bodenrichtwertzone->aktualisierenZone($GUI->formvars['oid'],$GUI->formvars);
			if ($ret[0]) {
				# 3.1 eintrageung fehlerhaft
				$GUI->Meldung=$ret[1];
			}
			else {
				# 3.2 Aktualisierung erfolgreich
				$alertmsg='\nBodenrichtwertzone erfolgreich in die Datenbank aktualisiert.';
			}
		}
    $GUI->bodenRichtWertErfassung();
  };
	
	$this->zoomToBodenrichtwertzone = function($oid,$border) use ($GUI){
    $layer = $GUI->user->rolle->getLayer(LAYERNAME_BODENRICHTWERTE);
    $zone=new bodenrichtwertzone($GUI->pgdatabase, $layer[0]['epsg_code'], $GUI->user->rolle->epsg_code);
    $ret=$zone->getBBoxAsRectObj($oid);
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
	
	$this->bodenRichtWertZoneLoeschen = function() use ($GUI){
    $layer = $GUI->user->rolle->getLayer(LAYERNAME_BODENRICHTWERTE);
    $zone=new bodenrichtwertzone($GUI->pgdatabase, $layer[0]['epsg_code'], $GUI->user->rolle->epsg_code);
    $ret=$zone->deleteBodenrichtwertzonen(array($GUI->formvars['oid']));
    if ($ret[0]) {
      echo 'Bodenrichtwertzone konnte nicht gelöscht werden.<br>'.$ret[1];
    }
    else {
      //echo 'Bodenrichtwertzone mit oid: '.$GUI->formvars['oid'].' erfolgreich gelöscht.';
    }
    $GUI->loadMap('DataBase');
    $currenttime=date('Y-m-d H:i:s',time());
    $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
    $GUI->drawMap();
    $GUI->saveMap('');
    #$GUI->queryMap();
    $GUI->output();
  };
	
	$this->waehleBodenwertStichtagToCopy = function() use ($GUI){
		include_once(CLASSPATH.'FormObject.php');
    $GUI->main = PLUGINS.'bodenrichtwerte/view/waehlebodenwertstichtagtocopy.php';
    $GUI->titel='Kopieren von Bodenrichtwertzonen auf einen neuen Stichtag';
    # Bodenrichtwertzonenobjekt erzeugen
    $layer = $GUI->user->rolle->getLayer(LAYERNAME_BODENRICHTWERTE);
    $bodenrichtwertzone=new bodenrichtwertzone($GUI->pgdatabase, $layer[0]['epsg_code'], $GUI->user->rolle->epsg_code);
    # Abfragen der bisher vorhandenen Stichtage
    $ret=$bodenrichtwertzone->getStichtage();
    if ($ret[0]) { # Fehler bei der Abfrage der vorhandenen Stichtage

    }
    else { # Stichtage erfolgreich abgefragt
      # Erzeugen des Formularobjektes zur Auswahl der vorhandenen Stichtage
      $GUI->Stichtagform=new FormObject('oldStichtag','select',$ret[1],$ret[1][0],$ret[1],1,'',0,NULL);
    }
    $GUI->output();
  };
	
	$this->copyBodenrichtwertzonen = function() use ($GUI){
    # Bodenrichtwertzonenobjekt erzeugen
    $layer = $GUI->user->rolle->getLayer(LAYERNAME_BODENRICHTWERTE);
    $bodenrichtwertzone=new bodenrichtwertzone($GUI->pgdatabase, $layer[0]['epsg_code'], $GUI->user->rolle->epsg_code);
    # Abfragen, ob der Vorgang schon bestätigt wurde
    if ($GUI->formvars['bestaetigung']!='Ja') {
      # nein
      # zum Bestätigungsformular
      $GUI->commitBodenrichtwertCopy();
    }
    else { # Kopiervorgang wurde bestätigt
      # Starten einer Transaktion
      $bodenrichtwertzone->database->begintransaction();
      $ret=$bodenrichtwertzone->copyZonenToNewStichtag($GUI->formvars['oldStichtag'],$GUI->formvars['newStichtag']);
      if ($ret=0) { # Fehler bei der Datenbank aktion
        # Zurückrollen der Transaktion
        $bodenrichtwertzone->database->rollbacktransaction();
        # Zurück zum Auswahlformular
        $GUI->waehleBodenwertStichtagToCopy();
      }
      else {
        # Anlegen eines neuen Layers für die Bodenrichtwertzonen mit dem neuen Stichtag
        # wenn es ausgewählt wurde
        # Beschließen der Transaktion
        $bodenrichtwertzone->database->committransaction();
        # Starten der letzten Kartenansicht
        # Karteninformationen lesen
        $GUI->loadMap('DataBase');
        # Karte zeichnen, protokollieren und ausgeben
        $currenttime=date('Y-m-d H:i:s',time());
        $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
        $GUI->drawMap();
        $GUI->saveMap('');
        $GUI->output();
      } # ende kopiervorgang erfolgreich
    } # ende kopiervorgang wurde bestätigt
  };
	
	$this->commitBodenrichtwertCopy = function() use ($GUI){
    # Frage eine Bestätigung für die Aktion ab
    $GUI->main = PLUGINS.'bodenrichtwerte/view/bestaetigebodenwertstichtagtocopy.php';
    $GUI->titel='Bodenrichtwertzonen kopieren';
    $GUI->output();
  };
	
?>