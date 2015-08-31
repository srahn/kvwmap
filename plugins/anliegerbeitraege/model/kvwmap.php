<?

	$GUI = $this;

	$this->Anliegerbeiträge_editor = function() use ($GUI){
    $GUI->main = PLUGINS.'anliegerbeitraege/view/anliegerbeitraege_editor.php';
    $GUI->titel='Anliegerbeiträge';
    # aktuellen Kartenausschnitt laden + zeichnen!
		$saved_scale = $GUI->reduce_mapwidth(100);
    $GUI->loadMap('DataBase');
		if($_SERVER['REQUEST_METHOD'] == 'GET')$GUI->scaleMap($saved_scale);		# nur beim ersten Aufruf den Extent so anpassen, dass der alte Maßstab wieder da ist
    if ($GUI->formvars['CMD']!='') {
      $GUI->navMap($GUI->formvars['CMD']);
      $GUI->user->rolle->saveDrawmode($GUI->formvars['always_draw']);
    }
    $GUI->queryable_postgis_layers = $GUI->Stelle->getqueryableVectorLayers(NULL, $GUI->user->id);

  	if(!$GUI->formvars['layer_id']){
      $layerset = $GUI->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
      $GUI->formvars['layer_id'] = $layerset[0]['Layer_ID'];
    }
    if($GUI->formvars['layer_id']){
      $layerset = $GUI->user->rolle->getLayer($GUI->formvars['layer_id']);
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
    else{
      $layerset = $GUI->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
      $GUI->formvars['layer_id'] = $layerset[0]['Layer_ID'];
    }

    $GUI->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
    $GUI->drawMap();
    $GUI->output();
  };

	$this->Anliegerbeiträge_strasse_speichern = function() use ($GUI){
    $anliegerbeitraege = new anliegerbeitraege($GUI->pgdatabase);
    $layerset = $GUI->user->rolle->getLayer('AB_Strassen');
    $anliegerbeitraege->layerepsg = $layerset[0]['epsg_code'];
    $anliegerbeitraege->clientepsg = $GUI->user->rolle->epsg_code;
    # eingeabewerte pruefen:
    $ret = $anliegerbeitraege->pruefeEingabedaten($GUI->formvars['newpathwkt']);
    if ($ret[0]) { # fehlerhafte eingabedaten
      $GUI->Meldung=$ret[1];
      $GUI->Anliegerbeiträge_editor();
      return;
    }
    else{
      $umring = $GUI->formvars['newpathwkt'];
      $ret = $anliegerbeitraege->eintragenNeueStrasse($umring);
      if ($ret[0]) { # fehler beim eintrag
          $GUI->Meldung=$ret[1];
      }
      else { # eintrag erfolgreich
        $GUI->formvars['secondpoly']="true";
        showMessage('Eintrag erfolgreich!');
      }
      $GUI->Anliegerbeiträge_editor();
    }
  };

	$this->Anliegerbeiträge_buffer_speichern = function() use ($GUI){
    $anliegerbeitraege = new anliegerbeitraege($GUI->pgdatabase);
    $layerset = $GUI->user->rolle->getLayer('AB_Bereiche');
    $anliegerbeitraege->layerepsg = $layerset[0]['epsg_code'];
    $anliegerbeitraege->clientepsg = $GUI->user->rolle->epsg_code;
    # eingeabewerte pruefen:
    $ret = $anliegerbeitraege->pruefeEingabedaten($GUI->formvars['newpathwkt']);
    if ($ret[0]) { # fehlerhafte eingabedaten
      $GUI->Meldung=$ret[1];
      $GUI->Anliegerbeiträge_editor();
      return;
    }
    else{
      $umring = $GUI->formvars['newpathwkt'];
      $ret = $anliegerbeitraege->eintragenNeueBereiche($umring);
      if ($ret[0]) { # fehler beim eintrag
          $GUI->Meldung=$ret[1];
      }
      else { # eintrag erfolgreich
        $GUI->formvars['secondpoly']="true";
        showMessage('Eintrag erfolgreich!');
      }
      $GUI->Anliegerbeiträge_editor();
    }
  };

?>