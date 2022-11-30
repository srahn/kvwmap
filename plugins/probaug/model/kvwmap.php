<?

	$GUI->sanitizeBauauskunftSuche = function() use ($GUI){
		$GUI->sanitize([
		'jahr' => 'text',
    'obergruppe' => 'text',
    'nummer' => 'text',
    'vorhaben' => 'text',
    'verfahrensart' => 'text',
    'gemarkung' => 'text',
    'flur' => 'text',
    'flurstueck' => 'text',
    'vorname' => 'text',
    'nachname' => 'text',
    'strasse' => 'text',
    'hausnummer' => 'text',
    'plz' => 'text',
    'ort' => 'text',
    'vonJahr' => 'text']);
	};

	$GUI->zoom2bauakte = function() use ($GUI){
		if($GUI->formvars['nummer'] != '' AND $GUI->formvars['jahr'] != ''){
			$GUI->bau = new Bauauskunft($GUI->baudatabase);
			$GUI->bau->getbaudaten($GUI->formvars);
			for($i = 0; $i < count($GUI->bau->baudata); $i++){
				$flst = explode(', ', $GUI->bau->baudata[$i]['feld14']);
				for($j = 0; $j < count($flst); $j++){
					$GUI->bau->grundstueck[] = $GUI->bau->formatFlurstKennz($GUI->bau->baudata[$i]['feld39'].$GUI->bau->baudata[$i]['feld12'].'-'.$GUI->bau->baudata[$i]['feld13'].'-'.$flst[$j]);
				}
			}
			$GUI->zoomToALKFlurst($GUI->bau->grundstueck, 10);
			if($GUI->bau->baudata[0]['geom_status'] == 't'){
				$GUI->formvars['oid'] = $GUI->bau->baudata[0]['geom_gid'];
				$GUI->formvars['layer_tablename'] = 'bau_geometrien';
				$GUI->formvars['layer_columnname'] = 'the_geom';
				$GUI->formvars['layer_id'] = LAYER_ID_BAUAKTEN_GEOMETRIEN;
				$GUI->formvars['selektieren'] = 'zoomonly';
				$GUI->zoom_toPoint();
			}
			else{
				$GUI->saveMap('');
				$currenttime=date('Y-m-d H:i:s',time());
				$GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
				$GUI->drawMap();
				$GUI->layerhiddenstring = 'reload ';		// Legenden-Reload erzwingen, damit eigene Abfragen-Layer angezeigt werden
				$GUI->output();
			}
		}
		else{
			$GUI->loadMap('DataBase');
	    $GUI->saveMap('');
			$currenttime=date('Y-m-d H:i:s',time());
			$GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
			$GUI->drawMap();
			$GUI->output();
		}
	};
	
	$GUI->bauauskunftSuche = function() use ($GUI){
    $GUI->bau = new Bauauskunft($GUI->baudatabase);
    $GUI->bau->readvorhaben();
    $GUI->bau->readverfahrensart();
    $GUI->bau->readaktualitaet();

    # Abfragen für welche Gemeinden die Stelle Zugriffsrechte hat
    # GemeindenStelle wird eine Liste mit ID´s der Gemeinden zugewiesen, die zur Stelle gehören
    $GemeindenStelle=$GUI->Stelle->getGemeindeIDs();
    $Gemarkung=new gemarkung('',$GUI->pgdatabase);
		if($GemeindenStelle == NULL){
			$GUI->GemkgListe=$Gemarkung->getGemarkungListe(NULL, NULL);
		}
		else{
			$GUI->GemkgListe=$Gemarkung->getGemarkungListe(array_keys($GemeindenStelle['ganze_gemeinde']), array_merge(array_keys($GemeindenStelle['ganze_gemarkung']), array_keys($GemeindenStelle['eingeschr_gemarkung'])));
		}
    $GUI->main = PLUGINS.'probaug/view/bauauskunftsuche.php';
    $GUI->titel='Bauauskunftsuche';
  };

	$GUI->bauauskunftSucheSenden = function() use ($GUI){
    $GUI->bau = new Bauauskunft($GUI->baudatabase);
    if($GUI->bau->checkformdaten($GUI->formvars)){
      if(!$GUI->formvars['anzahl']){
        $GUI->formvars['anzahl'] = $GUI->bau->countbaudaten($GUI->formvars);
      }
      $searchvars  = $GUI->bau->getbaudaten($GUI->formvars);
      $GUI->formvars['gemarkung'] = $searchvars['gemarkung'];
      $GUI->formvars['flur'] = $searchvars['flur'];
      $GUI->formvars['flurstueck'] = $searchvars['flurstueck'];

      for($i = 0; $i < @count($GUI->bau->baudata); $i++){
        $gemarkungs_searchvars['jahr'] = $GUI->bau->baudata[$i]['feld1'];
        $gemarkungs_searchvars['obergruppe'] = $GUI->bau->baudata[$i]['feld2'];
        $gemarkungs_searchvars['nummer'] = $GUI->bau->baudata[$i]['feld3'];
        $baudata = $GUI->bau->getbaudaten2($gemarkungs_searchvars);
        $Gemarkung=new gemarkung($baudata[0]['feld39'].$baudata[0]['feld12'],$GUI->pgdatabase);
        $GUI->bau->baudata[$i]['bauort'] = $Gemarkung->getGemkgName();
      }
      $GUI->main = PLUGINS.'probaug/view/bauauskunftsuchergebnis.php';
      $GUI->titel='Suchergebnis';
    }
    else{
      $GUI->main = PLUGINS.'probaug/view/bauauskunftsuche.php';
      $GUI->titel='Bauauskunftsuche';
    }
  };

	$GUI->bauauskunftanzeige = function() use ($GUI){
    $GUI->bau = new Bauauskunft($GUI->baudatabase);
    $GUI->bau->getbaudaten($GUI->formvars);
    for($i = 0; $i < count($GUI->bau->baudata); $i++){
			$flst = explode(', ', $GUI->bau->baudata[$i]['feld14']);
			for($j = 0; $j < count($flst); $j++){
				$GUI->bau->grundstueck[] = $GUI->bau->baudata[$i]['feld39'].$GUI->bau->baudata[$i]['feld12'].'-'.$GUI->bau->baudata[$i]['feld13'].'-'.$flst[$j];
			}
    }
    $Gemarkung=new gemarkung($GUI->bau->baudata[0]['feld12'],$GUI->pgdatabase);
    $GUI->bau->baudata[0]['bauort'] = $Gemarkung->getGemkgName();
    $GUI->main = PLUGINS.'probaug/view/bauauskunftanzeige.php';
    $GUI->titel='Baudatenanzeige';
  };
	
?>