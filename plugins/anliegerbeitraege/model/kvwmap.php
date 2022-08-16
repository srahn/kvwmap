<?

	$GUI->Anliegerbeiträge_editor = function() use ($GUI){
    $GUI->main = PLUGINS.'anliegerbeitraege/view/anliegerbeitraege_editor.php';
    $GUI->titel='Anliegerbeiträge';
    # aktuellen Kartenausschnitt laden + zeichnen!
		$saved_scale = $GUI->reduce_mapwidth(100);
    $GUI->loadMap('DataBase');
		if($saved_scale != NULL)$GUI->scaleMap($saved_scale);		# nur beim ersten Aufruf den Extent so anpassen, dass der alte Maßstab wieder da ist
    if ($GUI->formvars['CMD']!='') {
      $GUI->navMap($GUI->formvars['CMD']);
      $GUI->user->rolle->saveDrawmode($GUI->formvars['always_draw']);
    }
    $GUI->queryable_postgis_layers = $GUI->Stelle->getqueryableVectorLayers(NULL, $GUI->user->id, NULL, NULL, NULL, true, true);

  	if(!$GUI->formvars['geom_from_layer']){
      $layerset = $GUI->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
      $GUI->formvars['geom_from_layer'] = $layerset[0]['Layer_ID'];
    }
    if(!$GUI->formvars['geom_from_layer']){
      $layerset = $GUI->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
      $GUI->formvars['layer_id'] = $layerset[0]['Layer_ID'];
    }    
    $currenttime=date('Y-m-d H:i:s',time());
    $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
    $GUI->drawMap();
		$GUI->saveMap('');
    $GUI->output();
  };

	$GUI->Anliegerbeiträge_strasse_speichern = function() use ($GUI){
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
      $ret = $anliegerbeitraege->eintragenNeueStrasse($umring, $GUI->Stelle->id);
      if ($ret[0]) { # fehler beim eintrag
          $GUI->Meldung=$ret[1];
      }
      else { # eintrag erfolgreich
        $GUI->formvars['secondpoly']="true";
				$GUI->add_message('notice', 'Eintrag erfolgreich');
      }
      $GUI->Anliegerbeiträge_editor();
    }
  };

	$GUI->Anliegerbeiträge_buffer_speichern = function() use ($GUI){
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
      $ret = $anliegerbeitraege->eintragenNeueBereiche($umring, $GUI->Stelle->id);
      if ($ret[0]) { # fehler beim eintrag
          $GUI->Meldung=$ret[1];
      }
      else { # eintrag erfolgreich
        $GUI->formvars['secondpoly']="true";
        $GUI->add_message('notice', 'Eintrag erfolgreich');
      }
      $GUI->Anliegerbeiträge_editor();
    }
  };

?>