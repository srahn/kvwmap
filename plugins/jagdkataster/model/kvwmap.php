<?

	$GUI = $this;

	$this->jagdbezirk_show_data = function() use ($GUI){
		$GUI->mapDB = new db_mapObj($GUI->Stelle->id,$GUI->user->id);
    $jagdkataster = new jagdkataster($GUI->pgdatabase);
    $jagdkataster->clientepsg = $GUI->user->rolle->epsg_code;
		$layer = $GUI->user->rolle->getLayer(LAYER_ID_JAGDBEZIRKE);
		
		$privileges = $GUI->Stelle->get_attributes_privileges($layer[0]['Layer_ID']);
    $layer[0]['attributes'] = $GUI->mapDB->read_layer_attributes($layer[0]['Layer_ID'], $GUI->pgdatabase, $privileges['attributenames']);
    if($privileges == NULL){    # kein Eintrag -> alle Attribute lesbar
      for($j = 0; $j < count($layer[0]['attributes']['name']); $j++){
        $layer[0]['attributes']['privileg'][$j] = '0';
        $layer[0]['attributes']['privileg'][$layer[0]['attributes']['name'][$j]] = '0';
      }
    }
    else{
      for($j = 0; $j < count($layer[0]['attributes']['name']); $j++){
        $layer[0]['attributes']['privileg'][$j] = $privileges[$layer[0]['attributes']['name'][$j]];
        $layer[0]['attributes']['privileg'][$layer[0]['attributes']['name'][$j]] = $privileges[$layer[0]['attributes']['name'][$j]];
      }
    }
    $GUI->qlayerset = $layer;
    $jagdbezirk = $jagdkataster->getjagdbezirk($GUI->formvars['oid']);
    $GUI->qlayerset[0]['shape'][0] = $jagdbezirk;
    $i = 0;
    $GUI->main = PLUGINS.'jagdkataster/view/jagdbezirke.php';
    $GUI->output();
  };

	$this->zoomtojagdbezirk = function() use ($GUI){
    $jagdkataster = new jagdkataster($GUI->pgdatabase);
    $jagdkataster->clientepsg = $GUI->user->rolle->epsg_code;
    $rect = $jagdkataster->zoomTojagdbezirk($GUI->formvars['oid'], 10);
    $GUI->loadMap('DataBase');
    $GUI->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
  	if(MAPSERVERVERSION >= 600 ) {
			$GUI->map_scaledenom = $GUI->map->scaledenom;
		}
		else {
			$GUI->map_scaledenom = $GUI->map->scale;
		}
    $currenttime=date('Y-m-d H:i:s',time());
    $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
    $GUI->drawMap();
    $GUI->saveMap('');
    $GUI->output();
  };

	$this->jagdbezirke_auswaehlen_suchen_csv = function() use ($GUI){
  	$jagdkataster = new jagdkataster($GUI->pgdatabase);
    $GUI->jagdbezirke = $jagdkataster->suchen($GUI->formvars);
    $anz = count($GUI->jagdbezirke);
    for($i = 0; $i < $anz; $i++) {          	
    	if($GUI->jagdbezirke[$i]['art']=='ejb' OR $GUI->jagdbezirke[$i]['art']=='gjb'){
    		$csv.= "'".$GUI->jagdbezirke[$i]['id']."';";
    	}
    	else{
    		$csv.= "'".$GUI->jagdbezirke[$i]['jb_zuordnung']."';"; 
    	}
    	$csv.= $GUI->jagdbezirke[$i]['name'].';';
      $csv.= "'".$GUI->jagdbezirke[$i]['flaeche']."';";
      $csv.= $GUI->jagdbezirke[$i]['art'].';';
      $csv.= chr(10); 
    }
    $csv = 'lfd. Nummer;Name;Fläche;Typ'.chr(10).$csv;
    ob_end_clean();
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition:  inline; filename=Flurstuecke.csv");
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    print utf8_decode($csv);
  };
  
	$this->jagdbezirke_auswaehlen_suchen = function() use ($GUI){
    $jagdkataster = new jagdkataster($GUI->pgdatabase);
    $GUI->jagdbezirke = $jagdkataster->suchen($GUI->formvars);
    $GUI->jagdbezirke_auswaehlen();
  };

	$this->jagdbezirke_auswaehlen = function() use ($GUI){
    $GUI->main = PLUGINS.'jagdkataster/view/jagdkatastersuchform.php';
    $GUI->titel='Jagdbezirke suchen';
    $GUI->output();
  };

	$this->jagdkatastereditor = function() use ($GUI){
    $GUI->main = PLUGINS.'jagdkataster/view/jagdkatastereditor.php';
    $GUI->titel='Jagdbezirk anlegen';
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
    $jagdkataster = new jagdkataster($GUI->pgdatabase);
    $layerset = $GUI->user->rolle->getLayer(LAYER_ID_JAGDBEZIRKE);
    $jagdkataster->layerepsg = $layerset[0]['epsg_code'];
    $jagdkataster->clientepsg = $GUI->user->rolle->epsg_code;
    if ($GUI->formvars['oid']!='') {           # Jagdbezirk bearbeiten
    	$GUI->titel='Jagdbezirk bearbeiten';
      $rect = $jagdkataster->zoomTojagdbezirk($GUI->formvars['oid'], 10);
      $GUI->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
	    if(MAPSERVERVERSION >= 600 ) {
				$GUI->map_scaledenom = $GUI->map->scaledenom;
			}
			else {
				$GUI->map_scaledenom = $GUI->map->scale;
			}
      $GUI->jagdbezirk = $jagdkataster->getjagdbezirk($GUI->formvars['oid']);
      $GUI->formvars['newpathwkt'] = $GUI->jagdbezirk['wktgeom'];
      $GUI->formvars['pathwkt'] = $GUI->formvars['newpathwkt'];
      $GUI->formvars['newpath'] = transformCoordsSVG($GUI->jagdbezirk['svggeom']);
      $GUI->formvars['firstpoly'] = 'true';
    }
    if ($GUI->formvars['CMD']!='') {
      $GUI->navMap($GUI->formvars['CMD']);
      $GUI->user->rolle->saveDrawmode($GUI->formvars['always_draw']);
    }
    $GUI->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
    $GUI->drawMap();
    $GUI->output();
  };
	
	$this->jagdkatastereditor_senden = function() use ($GUI){
    $jagdkataster = new jagdkataster($GUI->pgdatabase);
    $layerset = $GUI->user->rolle->getLayer(LAYER_ID_JAGDBEZIRKE);
    $jagdkataster->layerepsg = $layerset[0]['epsg_code'];
    $jagdkataster->clientepsg = $GUI->user->rolle->epsg_code;
    # eingeabewerte pruefen:
    $ret = $jagdkataster->pruefeEingabedaten($GUI->formvars['newpathwkt'], $GUI->formvars['nummer']);
    if ($ret[0]) { # fehlerhafte eingabedaten
      $GUI->Meldung=$ret[1];
      $GUI->jagdkatastereditor();
      return;
    }
    else{
      $umring = $GUI->formvars['newpathwkt'];
      $ret = $jagdkataster->eintragenNeueFlaeche($umring, $GUI->formvars['nummer'], $GUI->formvars['name'], $GUI->formvars['art'], $GUI->formvars['area'], $GUI->formvars['jb_zuordnung'], $GUI->formvars['status'], $GUI->formvars['verzicht'], $GUI->formvars['oid']);
      if ($ret[0]) { # fehler beim eintrag
          $GUI->Meldung=$ret[1];
      }
      else { # eintrag erfolgreich
        $GUI->formvars['newpath']="";
        $GUI->formvars['newpathwkt']="";
        $GUI->formvars['pathwkt']="";
        $GUI->formvars['firstpoly']="";
        $GUI->formvars['secondpoly']="";
        showMessage('Eintrag erfolgreich!');
      }
      $GUI->jagdkatastereditor();
    }
  };

	$this->jagdkatastereditor_loeschen = function() use ($GUI){
    $jagdkataster = new jagdkataster($GUI->pgdatabase);
    $jagdkataster->deletejagdbezirk($GUI->formvars);
		if($GUI->formvars['oid'] == ''){
			$GUI->jagdbezirke_auswaehlen_suchen();		# zurück zum Suchergebnis
		}
		else{
			$GUI->loadMap('DataBase');
			$GUI->user->rolle->newtime = $GUI->user->rolle->last_time_id;
			$GUI->drawMap();
			$GUI->saveMap('');
			$GUI->output();
		}
  };

	$this->jagdkatastereditor_kopieren = function() use ($GUI){
    $jagdkataster = new jagdkataster($GUI->pgdatabase);
    $GUI->formvars['oid'] = $jagdkataster->copyjagdbezirk($GUI->formvars['oid']);
    $GUI->jagdkatastereditor();
  };

	$this->jagdkatastereditor_listflurst_csv = function() use ($GUI){
		if($GUI->formvars['FlurstKennz'] != ''){
			$selected_flurstuecke = explode(';', $GUI->formvars['FlurstKennz']);
		}
  	$GUI->jagdkataster = new jagdkataster($GUI->pgdatabase);
  	$GUI->flurstuecke = $GUI->jagdkataster->getIntersectedFlurst($GUI->formvars);
  	for($i = 0; $i < count($GUI->flurstuecke); $i++){
			if($GUI->formvars['FlurstKennz'] == '' OR in_array($GUI->flurstuecke[$i]['flurstkennz'], $selected_flurstuecke)){
				$csv .= $GUI->flurstuecke[$i]['gemkgname'].';';
				$csv .= $GUI->flurstuecke[$i]['flur'].';';
				$csv .= " ".$GUI->flurstuecke[$i]['zaehlernenner'].";";
				for($j=0; $j < count($GUI->flurstuecke[$i]['eigentuemer']); $j++){
					$csv .= str_replace(';', ',', $GUI->flurstuecke[$i]['eigentuemer'][$j]).' ('.$GUI->flurstuecke[$i]['eigentuemer_nr'][$j].')   ';
				}
				$csv .= ';';
				$csv .= $GUI->flurstuecke[$i]['albflaeche'].';';
				$csv .= str_replace('.', ',', round($GUI->flurstuecke[$i]['schnittflaeche'], 2)).';';
				$csv .= str_replace('.', ',', $GUI->flurstuecke[$i]['anteil']).';';
				$csv .= chr(10);  
			}
    }
    $csv = 'Gemarkung;Flur;Zähler/Nenner;Eigentümer (Nr.);amtl. Flst-Fläche;Anteil m²;Anteil %'.chr(10).$csv;
    ob_end_clean();
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition:  inline; filename=Flurstuecke.csv");
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    print utf8_decode($csv);
  };
  
	$this->jagdkatastereditor_listflurst = function() use ($GUI){
    $GUI->main = PLUGINS.'jagdkataster/view/jagdkataster_flurstlist.php';
    if($GUI->formvars['oid'])$GUI->titel='Im Jagdbezirk '.$GUI->formvars['name'].' enthaltene Flurstücke';
    else $GUI->titel='Enthaltene Flurstücke in Jagdbezirken';
    $GUI->jagdkataster = new jagdkataster($GUI->pgdatabase);
    $GUI->flurstuecke = $GUI->jagdkataster->getIntersectedFlurst($GUI->formvars);
    $GUI->output();
  };
	
	$this->jagdkatastereditor_listeigentuemer_csv = function() use ($GUI){
  	$GUI->jagdkataster = new jagdkataster($GUI->pgdatabase);
  	$GUI->eigentuemer = $GUI->jagdkataster->getEigentuemerListe($GUI->formvars);
  	for($i = 0; $i < count($GUI->eigentuemer)-1; $i++){          	
    	$csv .= $GUI->eigentuemer[$i]['eigentuemer'].';';
			$csv .= str_replace('.', ',', $GUI->eigentuemer[$i]['anteil_alk']).';';
      $csv .= str_replace('.', ',', round($GUI->eigentuemer[$i]['albflaeche']*100/$GUI->eigentuemer['albsumme'], 1)).';';
      $csv .= str_replace('.', ',', $GUI->eigentuemer[$i]['albflaeche']).';';
     	$csv .= chr(10);  
    }
    $csv = 'Eigentümer;geometrischer Anteil;Anteil nach amtl. Fläche[%];Anteil nach amtl. Fläche[m²]'.chr(10).$csv;
    ob_end_clean();
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition:  inline; filename=eigentuemer.csv");
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    print utf8_decode($csv);
  };
	
	$this->jagdkatastereditor_listeigentuemer = function() use ($GUI){
    $GUI->main = PLUGINS.'jagdkataster/view/jagdkataster_eigentuemerlist.php';
    $GUI->titel='Eigentümer im Jagdbezirk '.$GUI->formvars['name'];
    $GUI->jagdkataster = new jagdkataster($GUI->pgdatabase);
    $GUI->eigentuemer = $GUI->jagdkataster->getEigentuemerListe($GUI->formvars);
    $GUI->output();
  };
  
	$this->jagdkatastereditor_listpaechter = function() use ($GUI){
    $GUI->main = PLUGINS.'jagdkataster/view/jagdkataster_paechterlist.php';
    $GUI->titel='P&auml;chter im Jagdbezirk '.$GUI->formvars['name'].'';
    $jagdkataster = new jagdkataster($GUI->pgdatabase);
    $GUI->paechter = $jagdkataster->get_paechter($GUI->formvars['oid']);
    $GUI->output();
  };

?>