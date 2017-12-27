<?

	$GUI = $this;	
	
	$this->zoom2bauakte = function() use ($GUI){
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
			$GUI->formvars['oid'] = $GUI->bau->baudata[0]['geom_oid'];
			$GUI->formvars['layer_tablename'] = 'bau_geometrien';
			$GUI->formvars['layer_columnname'] = 'the_geom';
			$GUI->formvars['layer_id'] = LAYER_ID_BAUAKTEN_GEOMETRIEN;
			$GUI->formvars['selektieren'] = 'zoomonly';
			$GUI->zoom_toPoint();
		}
		else{
			$GUI->loadMap('DataBase');
	    $GUI->saveMap('');
			$currenttime=date('Y-m-d H:i:s',time());
			$GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
			$GUI->drawMap();
			$GUI->output();
		}
	};
	
	$this->bauauskunftSuche = function() use ($GUI){
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

	$this->bauauskunftSucheSenden = function($flurstkennz) use ($GUI){
    $GUI->bau = new Bauauskunft($GUI->baudatabase);
    if($GUI->formvars['flurstkennz'] != ''){
      $GUI->formvars['flurstkennz'] = $flurstkennz;
    }
    if($GUI->bau->checkformdaten($GUI->formvars)){
      if(!$GUI->formvars['anzahl']){
        $GUI->formvars['anzahl'] = $GUI->bau->countbaudaten($GUI->formvars);
      }
      $searchvars  = $GUI->bau->getbaudaten($GUI->formvars);
      $GUI->formvars['gemarkung'] = $searchvars['gemarkung'];
      $GUI->formvars['flur'] = $searchvars['flur'];
      $GUI->formvars['flurstueck'] = $searchvars['flurstueck'];

      for($i = 0; $i < count($GUI->bau->baudata); $i++){
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

	$this->bauauskunftanzeige = function() use ($GUI){
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