<?

	$GUI->flurstueckshistorie_drucken = function() use ($GUI){
  	$randomnumber = rand(0, 1000000);
  	$svgfile  = $randomnumber.'.svg';
  	$jpgfile = $randomnumber.'.jpg';
  	$fpsvg = fopen(IMAGEPATH.$svgfile, 'w');
		fputs($fpsvg, $GUI->formvars['svg_string']);
  	fclose($fpsvg);
  	exec(IMAGEMAGICKPATH.'convert '.IMAGEPATH.$svgfile.' '.IMAGEPATH.$jpgfile);
  	#echo IMAGEMAGICKPATH.'convert '.IMAGEPATH.$svgfile.' '.IMAGEPATH.$jpgfile;exit;

    if(function_exists('imagecreatefromjpeg')){
    	$mainimage = imagecreatefromjpeg(IMAGEPATH.$jpgfile);
      ob_end_clean();
      ImageJPEG($mainimage, IMAGEPATH.$jpgfile);
    }
		echo "
			<html>
				<head>
					<title>Flurstückshistorie</title>
					<script type=\"text/javascript\">
						function copyImageById(Id){
							var imgs = document.createElement('img');
							imgs.src = document.getElementById(Id).src;
							var bodys = document.body;
							bodys.appendChild(imgs);
							if(document.createRange){
								var myrange = document.createRange();
								myrange.setStartBefore(imgs);
								myrange.setEndAfter(imgs);
								myrange.selectNode(imgs);
							}
							var sel = window.getSelection();
							sel.addRange(myrange);
							var successful = document.execCommand('copy');
							bodys.removeChild(imgs);
						}
					</script>
				</head>
				<body style=\"text-align:center\">
					<img id=\"mapimg\" src=\"".TEMPPATH_REL.$jpgfile."\" style=\"box-shadow:  0px 0px 14px #777;\"><br><br>
					<input type=\"button\" onclick=\"copyImageById('mapimg');\" value=\"Bild kopieren\">
				</body>
			</html>
			";
  };

	$GUI->flurstueckshistorie = function() use ($GUI){
		include_once(PLUGINS.'alkis/model/kataster.php');
		$flst = new flurstueck($GUI->formvars['flurstueckskennzeichen'], $GUI->pgdatabase);
		$GUI->flst_historie = $flst->getFlstHistorie();
    $GUI->main = PLUGINS.'alkis/view/flst_historie.php';
  };

	$GUI->getFlurbezeichnung = function($epsgcode) use ($GUI){
		include_once(PLUGINS.'alkis/model/kataster.php');
    $Flurbezeichnung = [];
 	  $flur = new Flur('','','',$GUI->pgdatabase);
		$bildmitte['rw']=($GUI->map->extent->maxx+$GUI->map->extent->minx)/2;
		$bildmitte['hw']=($GUI->map->extent->maxy+$GUI->map->extent->miny)/2;
		$ret=$flur->getBezeichnungFromPosition($bildmitte, $epsgcode);
		if ($ret[0]) {
		}
		else {
			if (is_array($ret[1]) AND $ret[1]['flur'] != '') {
				$Flurbezeichnung = $ret[1];
			}
		}
		return $Flurbezeichnung;
  };

  $GUI->zoomToALKGemarkung = function($Gemkgschl,$border) use ($GUI){
    # 2006-02-01 pk
    # 1. Funktion ermittelt das umschließende Rechteck der $Gemarkung aus der postgis Datenbank
    # 2. zoom auf diese Rechteck
    # 3. und stellt die Gemarkung in einem gesonderten Layer in Gelb dar
    # zu 1)
		include_once(PLUGINS.'alkis/model/alkis.php');
		include_once(PLUGINS.'alkis/model/kataster.php');
		$alkis = new ALKIS($GUI->pgdatabase);
    $ret=$alkis->getMERfromGemarkung($Gemkgschl, $GUI->user->rolle->epsg_code);
    if ($ret[0]) {
      $GUI->Fehlermeldung='Es konnte keine Gemarkung gefunden werden.<br>'.$ret[1];
      $rect=$GUI->user->rolle->oGeorefExt;
      $GUI->flurstwahl();
    }
    else {
      $rect=$ret[1];
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
    }
    # zu 2)
    $GUI->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
		$GUI->map_scaledenom = $GUI->map->scaledenom;
    # zu 3)
    $GemkgObj=new Gemarkung($Gemkgschl,$GUI->pgdatabase);
    $layer = new LayerObj($GUI->map);
    $datastring ="the_geom from (SELECT 1 as id, st_multi(st_buffer(st_union(wkb_geometry), 0.1)) as the_geom FROM alkis.ax_flurstueck ";
    $datastring.="WHERE land||gemarkungsnummer = '" . $Gemkgschl."'";
		$datastring.=" AND CASE WHEN '" . rolle::$hist_timestamp . "' = '' THEN endet IS NULL ELSE beginnt::text <= '" . rolle::$hist_timestamp . "' and ('" . rolle::$hist_timestamp . "' <= endet::text or endet IS NULL) END";
    $datastring.=") as foo using unique id using srid=".EPSGCODE_ALKIS;
    $legendentext ="Gemarkung: " . $GemkgObj->getGemkgName($Gemkgschl);
    $layer->data = $datastring;
    $layer->status = MS_ON;
    $layer->template = ' ';
    $layer->name = $legendentext;
    $layer->type = 2;
    $layer->group = 'eigene Abfragen';
    $layer->metadata->set('off_requires',0);
    $layer->metadata->set('layer_has_classes',0);
    $GUI->map->web->metadata->set('group_status_eigene Abfragen','0');
    $GUI->map->web->metadata->set('group_eigene Abfragen_has_active_layers','0');
    $layer->setConnectionType(6, '');
    $layer->updateFromString("LAYER COMPOSITE OPACITY 50 END END");
    $layer->connection = $GUI->pgdatabase->get_connection_string();
    $layer->metadata->set('queryStatus','2');
    $layer->metadata->set('wms_queryable','0');
    $layer->metadata->set('layer_hidden','0'); #2005-11-30_pk
    $klasse = new ClassObj($layer);
    $klasse->status = MS_ON;
    $klasse->setexpression($expression);
    $style = new StyleObj($klasse);
    $style->color->setRGB(255,255,128);
    $style->outlinecolor->setRGB(0,0,0);
  };

  $GUI->zoomToALKFlur = function($GemID,$GemkgID,$FlurID,$border) use ($GUI){
    # 2006-02-01 pk
    # 1. Funktion ermittelt das umschließende Rechteck der $Gemarkung aus der postgis Datenbank
    # 2. zoom auf diese Rechteck
    # 3. und stellt die Gemarkung in einem gesonderten Layer in Gelb dar
    # zu 1)
		include_once(PLUGINS.'alkis/model/alkis.php');
		include_once(PLUGINS.'alkis/model/kataster.php');
		$alkis = new ALKIS($GUI->pgdatabase);
    $ret=$alkis->getMERfromFlur($GemkgID,$FlurID, $GUI->user->rolle->epsg_code);
    if ($ret[0]) {
      $GUI->Fehlermeldung='Es konnte keine Flur gefunden werden.<br>'.$ret[1];
      $rect=$GUI->user->rolle->oGeorefExt;
    }
    else {
      $rect=$ret[1];
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
    }
    # zu 2)
    $GUI->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
		$GUI->map_scaledenom = $GUI->map->scaledenom;
    # zu 3)
    $GemkgObj=new Gemarkung($GemkgID,$GUI->pgdatabase);
    $layer = new LayerObj($GUI->map);
    $datastring ="the_geom from (SELECT 1 as id, st_multi(st_buffer(st_union(wkb_geometry), 0.1)) as the_geom FROM alkis.ax_flurstueck ";
    $datastring.="WHERE land||gemarkungsnummer = '" . $GemkgID."'";
    $datastring.=" AND flurnummer = ".(int)$FlurID;
		$datastring.=" AND CASE WHEN '" . rolle::$hist_timestamp . "' = '' THEN endet IS NULL ELSE beginnt::text <= '" . rolle::$hist_timestamp . "' and ('" . rolle::$hist_timestamp . "' <= endet::text or endet IS NULL) END";
    $datastring.=") as foo using unique id using srid=".EPSGCODE_ALKIS;
    $legendentext ="Gemarkung: " . $GemkgObj->getGemkgName($GemkgID);
    $legendentext .="<br>Flur: " . $FlurID;
    $layer->data = $datastring;
    $layer->status = MS_ON;
    $layer->template = ' ';
    $layer->name = $legendentext;
    $layer->type = 2;
    $layer->group = 'eigene Abfragen';
    $layer->metadata->set('off_requires',0);
    $layer->metadata->set('layer_has_classes',0);
    $GUI->map->web->metadata->set('group_status_eigene Abfragen','0');
    $GUI->map->web->metadata->set('group_eigene Abfragen_has_active_layers','0');
    $layer->setConnectionType(6, '');
    $layer->updateFromString("LAYER COMPOSITE OPACITY 50 END END");
    $layer->connection = $GUI->pgdatabase->get_connection_string();
    $layer->metadata->set('queryStatus','2');
    $layer->metadata->set('wms_queryable','0');
    $layer->metadata->set('layer_hidden','0');
    $klasse = new ClassObj($layer);
    $klasse->status = MS_ON;
    $style = new StyleObj($klasse);
    $style->color->setRGB(255,255,128);
    $style->outlinecolor->setRGB(0,0,0);
  };

	$GUI->zoomToALKFlurst = function($FlurstListe, $border, $zoom = true, $without_temporal_filter = false) use ($GUI){
		include_once(PLUGINS.'alkis/model/alkis.php');
		$dbmap = new db_mapObj($GUI->Stelle->id,$GUI->user->id);
		$alkis = new ALKIS($GUI->pgdatabase);
    $ret=$alkis->getMERfromFlurstuecke($FlurstListe, $GUI->user->rolle->epsg_code, $without_temporal_filter);
    if ($ret[0]) {
      $GUI->Fehlermeldung='Es konnten keine Flurstücke gefunden werden.<br>'.$ret[1];
      $rect=$GUI->user->rolle->oGeorefExt;
    }
    else {
      $rect=$ret[1];
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
    }
		$epsg = EPSGCODE_ALKIS;
    if (!$without_temporal_filter) {
		  $layerset = $GUI->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
		  $data = $layerset[0]['data'];
    }
		if($data == '')$data ="the_geom from (select distinct on (f.gml_id) f.gml_id as oid, wkb_geometry as the_geom from alkis.ax_flurstueck as f where 1=1 order by gml_id, endet IS NULL desc, endet desc) as foo using unique oid using srid=" . $epsg;
		$explosion = explode(' ', $data);
		$datageom = $explosion[0];
		$explosion = explode('using unique ', strtolower($data));
		$end = $explosion[1];
    $data = str_replace('$SCALE', '1000', $data);
    $select = getDataParts($data)['select'];
		$orderbyposition = strpos(strtolower($select), ' order by ');
		if($orderbyposition > 0)$select = substr($select, 0, $orderbyposition);
		if(strpos(strtolower($select), 'where') === false)$select .= " WHERE ";
		else $select .= " AND ";
		$select .= " flurstueckskennzeichen IN ('" . implode("','", $FlurstListe) . "')";
    $legendentext = "Flurstück" . (count_or_0($FlurstListe) > 1 ? 'e' : '');
    $legendentext .= " (".date('d.m. H:i',time())."): " . implode(" ", $FlurstListe);
    $datastring = $datageom." from (" . $select . ") as foo using unique " . $end;
    $group = $dbmap->getGroupbyName('eigene Abfragen');
    if($group != ''){
      $groupid = $group['id'];
    }
    else{
      $groupid = $dbmap->newGroup('eigene Abfragen', 0);
    }
    $GUI->formvars['original_layer_id'] = $layerset[0]['layer_id'];
    $GUI->formvars['user_id'] = $GUI->user->id;
    $GUI->formvars['stelle_id'] = $GUI->Stelle->id;
    $GUI->formvars['aktivstatus'] = 1;
		$GUI->formvars['name'] = substr($legendentext, 0, 255);
    $GUI->formvars['gruppe'] = $groupid;
    $GUI->formvars['typ'] = 'search';
    $GUI->formvars['datentyp'] = 2;
    $GUI->formvars['data'] = $datastring;
    $GUI->formvars['query'] = $select;
    $GUI->formvars['connectiontype'] = 6;
    $GUI->formvars['connection_id'] = POSTGRES_CONNECTION_ID;
    $GUI->formvars['epsg_code'] = $epsg;
    $GUI->formvars['transparency'] = $GUI->user->rolle->result_transparency;

    $layer_id = $dbmap->newRollenLayer($GUI->formvars);
    $attributes = $dbmap->load_attributes($GUI->pgdatabase, $select);
		$dbmap->save_postgis_attributes($GUI->pgdatabase, -$layer_id, $attributes, '', '');
		
		$dbmap->addRollenLayerStyling($layer_id, $GUI->formvars['datentyp'], $GUI->formvars['labelitem'], $GUI->user, 'zoom');
		
    $GUI->user->rolle->set_one_Group($GUI->user->id, $GUI->Stelle->id, $groupid, 1);# der Rolle die Gruppe zuordnen

    $GUI->loadMap('DataBase');

    if ($zoom) {
      $GUI->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
		  $GUI->map_scaledenom = $GUI->map->scaledenom;
    }
  };

	$GUI->zoomToALKGebaeude = function($Gemeinde,$Strasse,$Hausnr,$border) use ($GUI){
    # 2006-01-31 pk
    # 1. Funktion ermittelt das umschließende Rechteck der mit $Gemeinde,$Strasse und $Hausnr übergebenen
    # Gebaeude aus der postgis Datenbank mit Rand entsprechend dem Faktor $border
    # 2. zoom auf diese Rechteck
    # 3. und stellt die Gebaeude in einem gesonderten Layer in Gelb dar
    # zu 1)
		include_once(PLUGINS.'alkis/model/alkis.php');
		$alkis = new ALKIS($GUI->pgdatabase);
    $GUI->loadMap('DataBase');
    $ret = $alkis->getMERfromGebaeude($Gemeinde,$Strasse,$Hausnr, $GUI->user->rolle->epsg_code);
    if ($ret[0]) {
      $rect=$GUI->user->rolle->oGeorefExt;
      $GUI->add_message('warning', 'Der Strasse sind keine Gebäude zugeordnet.');
    }
    else {
      $rect = $ret[1]['rect'];
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;

			$GUI->map_scaledenom = $GUI->map->scaledenom;
	    # zu 3)
			$epsg = EPSGCODE_ALKIS;
			$datastring = "the_geom from (
												select 
													g.gml_id as oid, 
													wkb_geometry as the_geom 
												FROM 
													alkis.ax_gebaeude g
												WHERE	
													g.zeigtauf && ARRAY (
														SELECT
															gml_id
														FROM
															alkis.ax_lagebezeichnungmithausnummer l 
														WHERE 
															true ";
			if ($Hausnr != '') {
				$datastring.=" AND concat_ws('-', l.land || l.regierungsbezirk || l.kreis || l.gemeinde, l.lage, l.hausnummer) IN ('" . str_replace(", ", "', '", $Hausnr) . "')";
			}
			else{
				$kreis = substr($Gemeinde, 3, 2);
				$gemeinde = substr($Gemeinde, 5, 3);
				if ($Strasse != '') {
					$datastring .= " AND l.kreis = '" . $kreis . "' AND l.gemeinde = '" . $gemeinde . "' AND l.lage IN ('" . str_replace(", ", "', '", $Strasse) . "')";
				}
			}
      $datastring.= $GUI->pgdatabase->build_temporal_filter(['l']);
			$datastring.= ")" . $GUI->pgdatabase->build_temporal_filter(['g']);
	    $datastring.=") as foo using unique oid using srid=" . $epsg;
	    $legendentext ="Gebäude";
	    if ($Hausnr!='') {
	      $legendentext.=" HausNr: ".str_replace(',', ' ', $Hausnr);
	    }
	    else{
				$str = $GUI->pgdatabase->getStrNameByID($Gemeinde,$Strasse);
	    	$legendentext .= $str[1];
	    }

	    $dbmap = new db_mapObj($GUI->Stelle->id,$GUI->user->id);

	    $group = $dbmap->getGroupbyName('eigene Abfragen');
	    if($group != ''){
	      $groupid = $group['id'];
	    }
	    else{
	      $groupid = $dbmap->newGroup('eigene Abfragen', 0);
	    }

	    $GUI->formvars['user_id'] = $GUI->user->id;
	    $GUI->formvars['stelle_id'] = $GUI->Stelle->id;
	    $GUI->formvars['aktivstatus'] = 1;
	    $GUI->formvars['name'] = $legendentext;
	    $GUI->formvars['gruppe'] = $groupid;
	    $GUI->formvars['typ'] = 'search';
	    $GUI->formvars['datentyp'] = 2;
	    $GUI->formvars['data'] = $datastring;
	    $GUI->formvars['connectiontype'] = 6;
	    $GUI->formvars['connection_id'] = $GUI->pgdatabase->connection_id;
	    $GUI->formvars['epsg_code'] = $epsg;
	    $GUI->formvars['transparency'] = $GUI->user->rolle->result_transparency;

	    $layer_id = $dbmap->newRollenLayer($GUI->formvars);

	    $dbmap->addRollenLayerStyling($layer_id, $GUI->formvars['datentyp'], $GUI->formvars['labelitem'], $GUI->user, 'zoom');
			
	    $GUI->user->rolle->set_one_Group($GUI->user->id, $GUI->Stelle->id, $groupid, 1);# der Rolle die Gruppe zuordnen

	    # zu 2)
	    $GUI->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
			$GUI->map_scaledenom = $GUI->map->scaledenom;
    }
    return $ret;
  };
	
	$GUI->adresswahl = function() use ($GUI){
		$GUI->sanitize(['GemID' => 'text', 'StrID' => 'text', 'StrName' => 'text']);
		include_once(PLUGINS.'alkis/model/kataster.php');
		include_once(CLASSPATH.'FormObject.php');
    $Adresse=new adresse('','','',$GUI->pgdatabase);
    $GUI->main = PLUGINS.'alkis/view/adresssuche.php';
    if($GUI->formvars['ALK_Suche'] == 1){
    	$GUI->titel='Adresssuche (zur Karte)';
    }
    else{
    	$GUI->titel='Adressensuche';
    }
		if($GUI->formvars['titel'] != '')$GUI->titel = $GUI->formvars['titel'];
    if ($GUI->formvars['aktualisieren']=='Neu') {
      $GemID=0; $StrID=0; $StrName=''; $HausID=0; $HausNr='';
    }
    else {
      $GemID=$GUI->formvars['GemID'];
      $GemkgID=$GUI->formvars['GemkgID'];
      $StrID=$GUI->formvars['StrID'];
      $StrName=$GUI->formvars['StrName'];
      if ($StrName!='') {
        $StrID=$Adresse->getStrIDfromName($GemID,$StrName);
      }
      $HausID=$GUI->formvars['HausID'];
      $HausNr=$GUI->formvars['HausNr'];
      $selHausID = explode(', ',$GUI->formvars['selHausID']);
    }
    $Gemeinde=new gemeinde('',$GUI->pgdatabase);
		$Gemarkung=new gemarkung('',$GUI->pgdatabase);
    $GemeindenStelle=$GUI->Stelle->getGemeindeIDs();

		if($GemeindenStelle == NULL){
			$GemListe=$Gemeinde->getGemeindeListe(NULL);
			$GemkgListe=$Gemarkung->getGemarkungListe(NULL,'');
		}
		else{
			$GemListe=$Gemeinde->getGemeindeListe(array_merge(array_keys($GemeindenStelle['ganze_gemeinde']), array_keys($GemeindenStelle['eingeschr_gemeinde'])));
			$GemkgListe=$Gemarkung->getGemarkungListe(array_keys($GemeindenStelle['ganze_gemeinde']), array_merge(array_keys($GemeindenStelle['ganze_gemarkung']), array_keys($GemeindenStelle['eingeschr_gemarkung'])));
		}		
		# Wenn nur eine Gemeinde zur Auswahl steht, wird diese gewählt; Verhalten so, als würde die Gemeinde vorher gewählt worden sein.
		if(count_or_0($GemListe['ID'])==1)$GemID=$GemListe['ID'][0];
    // Sortieren der Gemarkungen unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($GemkgListe['Bezeichnung'], $GemkgListe['GemkgID']);
    $GemkgListe['Bezeichnung'] = $sorted_arrays['array'];
    $GemkgListe['GemkgID'] = $sorted_arrays['second_array'];
    # Erzeugen des Formobjektes für die Gemarkungsauswahl
    if (count($GemkgListe['GemkgID'])==1) { $GemkgID=$GemkgListe['GemkgID'][0]; }
    $GemkgFormObj=new selectFormObject("GemkgID","select",$GemkgListe['GemkgID'],array($GemkgID),$GemkgListe['Bezeichnung'],"1","","",NULL);
    $GemkgFormObj->addJavaScript('onclick', 'document.GUI.GemID.disabled = true');
    $GemkgFormObj->insertOption(-1,0,'--Auswahl--',0);
    $GemkgFormObj->outputHTML();

    // Sortieren der Gemeinden unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($GemListe['name'], $GemListe['ID']);
    $GemListe['name'] = $sorted_arrays['array'];
    $GemListe['ID'] = $sorted_arrays['second_array'];
    # Erzeugen des Formobjektes für die Gemeindeauswahl
    $GemFormObj=new selectFormObject("GemID","select",$GemListe['ID'],array($GemID),$GemListe['name'],"1","","",NULL);
    $GemFormObj->addJavaScript('onclick', 'document.GUI.GemkgID.disabled = true');
    $GemFormObj->insertOption(-1,0,'--Auswahl--',0);
    $GemFormObj->outputHTML();
    # Wenn Gemeinde gewählt wurde, oder nur eine zur Auswahl stand, Auswahlliste für Strassen erzeugen
    if ($GemFormObj->selected OR $GemkgFormObj->selected){
    	if($GemFormObj->selected)$StrassenListe=$Adresse->getStrassenListe($GemID,'', '');
    	elseif($GemkgFormObj->selected)$StrassenListe=$Adresse->getStrassenListe('', $GemkgID,'');
      $StrSelected[0]=$StrID;
      # Erzeugen des Formobjektes für die Strassenauswahl
      $StrFormObj=new selectFormObject("StrID","select",$StrassenListe['StrID'],$StrSelected,$StrassenListe['name'],"1","","",NULL);
      # Unterscheidung ob Strasse ausgewählt wurde
      if ($StrFormObj->selected){
      	if($GemID == -1 OR $GemID == ''){
					$Gemeinde = $Gemarkung->getGemarkungListe(NULL, array($GUI->formvars['GemkgID']), NULL);
		    	$GemID = $Gemeinde['gemeinde'][0];
		    }
        $HausNrListe=$Adresse->getHausNrListe($GemID,$StrID,'','','hausnr*1,ASCII(REVERSE(hausnr)),quelle');
        # Erzeugen des Formobjektes für die Flurstücksauswahl
        if (count_or_0($HausNrListe['HausID'])==1){
          $HausID=$HausNrListe['HausID'][0];
          $HausID = array($HausID);
        }
        $HausNrFormObj=new FormObject("HausID","select",$HausNrListe['HausID'],array($HausID),$HausNrListe['HausNr'],"12","","multiple",100);
        $HausNrFormObj->outputHTML();
        if($GUI->formvars['selHausID'] != ''){
          $SelectedHausNrFormObj=new FormObject("selectedHausID","select", $selHausID, NULL, $selHausID,"12","","multiple",170);
        }
        else{
          $SelectedHausNrFormObj=new FormObject("selectedHausID","select",NULL,NULL,"","12","","multiple",170);
        }
        $SelectedHausNrFormObj->outputHTML();
      }

    	else {
        if($GUI->formvars['selHausID'] != ''){
          $SelectedHausNrFormObj=new FormObject("selectedHausID","select", $selHausID, NULL, $selHausID,"12","","multiple",100);
          $SelectedHausNrFormObj->outputHTML();
        }
        else{
          $HausNrFormObj=new FormObject("HausNr","text","","","","5","5","multiple",NULL);
        }
      }
    }
		$GUI->FormObject["Orte"]=$OrtsFormObj;
    $GUI->FormObject["Gemeinden"]=$GemFormObj;
    $GUI->FormObject["Gemarkungen"]=$GemkgFormObj;
    $GUI->FormObject["Strassen"]=$StrFormObj;
    $GUI->FormObject["HausNr"]=$HausNrFormObj;
    $GUI->FormObject["selectedHausNr"]=$SelectedHausNrFormObj;
  };

	$GUI->adresseSuchen = function() use ($GUI){
		$GUI->sanitize(['GemID' => 'text', 'StrID' => 'text', 'StrName' => 'text', 'selHausID' => 'text']);
    include_once(PLUGINS.'alkis/model/kataster.php');
    $GemID=$GUI->formvars['GemID'];
    if($GemID == -1){
    	$Gemarkung=new gemarkung('',$GUI->pgdatabase);
    	$Gemeinde = $Gemarkung->getGemarkungListe(NULL, array($GUI->formvars['GemkgID']));
    	$GemID = $Gemeinde['gemeinde'][0];
    }
    if ($GemID!='-1') {
      $Adresse=new adresse($GemID,'','',$GUI->pgdatabase);
      $StrID=$GUI->formvars['StrID'];
      $Adresse->StrassenSchl=$StrID;
      $HausID=$GUI->formvars['selHausID'];
      $HausNr=$GUI->formvars['HausNr'];
      if ($HausNr!='') {
        $HausID=$HausNr;
      }
      if ($HausID=='-1') {
        $HausID='';
      }
      $Adresse->HausNr=$HausID;
      # $GUI->searchInExtent=$GUI->formvars['searchInExtent'];
      # Wenn keine Strasse angegeben ist zoom auf die ganze Gemeinde
      if ($StrID == '0') {
        $GUI->loadMap('DataBase');
        $GUI->zoomToALKGemeinde($GemID,10);
        $currenttime=date('Y-m-d H:i:s',time());
        $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
        $GUI->drawMap();
        $GUI->saveMap('');
      }
      else {
        # StrassenID ist angegeben
        if($GUI->formvars['ALK_Suche'] == 1){
	        $ret = $GUI->zoomToALKGebaeude($GemID,$StrID,$HausID,100);
          if (!$ret[0]) {
            $GUI->saveMap('');
          }
          $FlurstKennz = $Adresse->getFlurstKennzListe($ret[1]['gml_ids']);
          if ($FlurstKennz) {
	          $GUI->zoomToALKFlurst($FlurstKennz, 100, $ret[1]['gml_ids'] == '');
          }
					if($GUI->formvars['go_next'] != ''){
						$GUI->saveMap('');
						go_switch($GUI->formvars['go_next']);
						exit();
					}
	        $currenttime=date('Y-m-d H:i:s',time());
          $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
          $GUI->saveMap('');
          $GUI->drawMap();
        }
        else{
          $FlurstKennz = $Adresse->getFlurstKennzListe();
	        if ($FlurstKennz > 0) {
	          $GUI->flurstAnzeige($FlurstKennz);
	        }
	        else {
	          # Karte laden, auf die Gebaeude zoomen, Karte Zeichnen und speichern für späteren gebrauch
	          $GUI->zoomToALKGebaeude($GemID,$StrID,$HausID,100);
	          $currenttime=date('Y-m-d H:i:s',time());
	          $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
	          $GUI->drawMap();
	          $GUI->saveMap('');
	        }
        }
      }
    }
    else {
      $GUI->Fehlermeldung='Wählen Sie eine Gemeinde aus!';
      $GUI->adresswahl();
    }
  };
	
	$GUI->flurstwahl = function() use ($GUI){
		$GUI->sanitize(['GemID' => 'text', 'GemkgID' => 'text', 'FlurID' => 'text', 'FlstID' => 'text']);
		include_once(PLUGINS.'alkis/model/kataster.php');
		include_once(CLASSPATH.'FormObject.php');
		$GUI->main = PLUGINS.'alkis/view/flurstueckssuche.php';
    ####### Import ###########
		$_files = $_FILES;
    if($_files['importliste']['name']){
			$importliste = file($_files['importliste']['tmp_name'], FILE_IGNORE_NEW_LINES);
			$bom = pack('H*','EFBBBF');
			$importliste[0] = preg_replace("/^$bom/", '', $importliste[0]);
      foreach ($importliste as &$zeile) {
        $zeile = trim($zeile, ";, \t");
      }
			$importliste_string = implode(';', $importliste);
			$importliste_string = formatFlurstkennzALKIS($importliste_string);
			$importliste = explode(';', $importliste_string);
			$GUI->formvars['selFlstID'] = implode(', ', $importliste);
			$GUI->formvars['GemkgID'] = substr($importliste[0], 0, 6);
			$GUI->formvars['FlurID'] = substr($importliste[0], 6, 3);
			$GUI->formvars['without_temporal_filter'] = 1;
		}
		##########################
		# Übernahme der Formularwerte für die Einstellung der Auswahlmaske
		$GemID=$GUI->formvars['GemID'];
		$GemkgID=$GUI->formvars['GemkgID'];
		$FlurID=$GUI->formvars['FlurID'];
		$FlstID=$GUI->formvars['FlstID'];
		$FlstNr=$GUI->formvars['FlstNr'];
		$selFlstID = explode(', ',$GUI->formvars['selFlstID']);
    $GemeindenStelle=$GUI->Stelle->getGemeindeIDs();
		$Gemarkung=new gemarkung('',$GUI->pgdatabase);
		if ($GUI->formvars['ALK_Suche'] == 1 OR $GemeindenStelle != NULL) {
			if($GemeindenStelle == NULL){
				$GemkgListe = $Gemarkung->getGemarkungListe(NULL, NULL);
			}
			else{
				$GemkgListe = $Gemarkung->getGemarkungListe(array_keys($GemeindenStelle['ganze_gemeinde']), array_merge(array_keys($GemeindenStelle['ganze_gemarkung']), array_keys($GemeindenStelle['eingeschr_gemarkung'])));
			}
		}
		else {
			$GemkgListe = $Gemarkung->getGemarkungListeAll(NULL, NULL);
		}
		if ($GemkgListe['hist'][$GemkgID]) {
			$GUI->formvars['history_mode'] = 'historisch';
		}
		if ($GUI->formvars['history_mode'] == '') {
			$GUI->formvars['history_mode'] = 'aktuell';
		}
    if ($GUI->formvars['history_mode'] != 'aktuell') {
			$GUI->formvars['without_temporal_filter'] = 1;
    }
		$GUI->land_schluessel = substr($GemkgListe['GemkgID'][0], 0, 2);
    // Sortieren der Gemarkungen unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($GemkgListe['Bezeichnung'], $GemkgListe['GemkgID']);
    $GemkgListe['Bezeichnung'] = $sorted_arrays['array'];
    $GemkgListe['GemkgID'] = $sorted_arrays['second_array'];
    # Erzeugen des Formobjektes für die Gemarkungsauswahl
    if (count($GemkgListe['GemkgID'])>0) {
      if (count($GemkgListe['GemkgID'])==1) { $GemkgID=$GemkgListe['GemkgID'][0]; }
      $GemkgFormObj=new selectFormObject("GemkgID","select",$GemkgListe['GemkgID'],array($GemkgID),$GemkgListe['Bezeichnung'],"1","","",NULL);
    }
    else {
      $GemkgFormObj=new selectFormObject("GemkgID","text","","","","25","25","",NULL);
    }
    $GemkgFormObj->insertOption(-1,0,'--Auswahl--',0);
    $GemkgFormObj->outputHTML();
    # Wenn Gemarkung gewählt wurde, oder nur eine Gemarkung zur Wahl steht, Auswahlliste für Flur erzeugen
    if ($GemkgFormObj->selected) {
      # Abragen der Fluren zur Gemarkung
      if ($GemkgID==0) { $GemkgID=$GemkgListe['GemkgID'][0]; }
      $Flur=new Flur('','','',$GUI->pgdatabase);
    	$FlurListe=$Flur->getFlurListe($GemkgID, $GemeindenStelle['eingeschr_gemarkung'][$GemkgID], $GUI->formvars['history_mode']);
      # Erzeugen des Formobjektes für die Flurauswahl
      if (count_or_0($FlurListe['FlurID'])==1) { $FlurID=$FlurListe['FlurID'][0]; }
      $FlurFormObj=new selectFormObject("FlurID","select",$FlurListe['FlurID'],array($FlurID),$FlurListe['name'],"1","","",NULL);
      $FlurFormObj->insertOption(-1,0,'--Auswahl--',0);
      $FlurFormObj->outputHTML();
      # Wenn Flur gewählt wurde, oder nur eine Flur zur Auswahl steht, Auswahllist für Flurstuecke erzeugen
      if ($FlurFormObj->selected) {
        # Abfragen der Flurstücke zur Flur
        $FlstNr=new flurstueck('',$GUI->pgdatabase);
        if ($FlurID == '') { $FlurID=$FlurListe['FlurID'][0]; }
				$FlstNrListe=$FlstNr->getFlstListe($GemID, $GemkgID, $FlurID, $GemeindenStelle['eingeschr_flur'][$GemkgID][(int)$FlurID], $GUI->formvars['history_mode']);
        # Erzeugen des Formobjektes für die Flurstücksauswahl
        if (count($FlstNrListe['FlstID'])==1){
          $FLstID=$FlstNrListe['FlstID'][0];
          $FlstID = array($FLstID);
        }
        $FlstNrFormObj=new FormObject("FlstID","select",$FlstNrListe['FlstID'],array($FlstID),$FlstNrListe['FlstNr'],"12","","multiple",100);
				$FlstNrFormObj->insertOption('alle',false,' -- alle -- ', 0);
				$FlstNrFormObj->addJavaScript('onclick', 'if(this.value==\'alle\'){this.options[0].selected = false; for(var i=1; i<this.options.length; i++){this.options[i].selected = true;}}');
        $FlstNrFormObj->outputHTML();
        if($GUI->formvars['selFlstID'] != ''){
          $SelectedFlstNrFormObj=new FormObject("selectedFlstID","select", $selFlstID, NULL, $selFlstID,"12","","multiple",170);
        }
        else{
          $SelectedFlstNrFormObj=new FormObject("selectedFlstID","select",NULL,NULL,"","12","","multiple",170);
        }
        $SelectedFlstNrFormObj->outputHTML();
      }
      else {
        if($GUI->formvars['selFlstID'] != ''){
          $SelectedFlstNrFormObj=new FormObject("selectedFlstID","select", $selFlstID, NULL, $selFlstID,"12","","multiple",100);
          $SelectedFlstNrFormObj->outputHTML();
        }
        else{
          $FlstNrFormObj=new FormObject("FlstNr","text","","","","0","0","multiple",NULL);
        }
      }
    }
    else {
      $FlurFormObj=new FormObject("FlurID","text","","","","5","5","multiple",NULL);
      $FlstNrFormObj=new FormObject("FlstNr","text","","","","25","25","multiple",NULL);
    }
    $GUI->FormObject["Gemeinden"]=$GemFormObj;
    $GUI->FormObject["Gemarkungen"]=$GemkgFormObj;
    $GUI->FormObject["GemkgSchl"]=$GemkgSchlFormObj;
    $GUI->FormObject["Fluren"]=$FlurFormObj;
    $GUI->FormObject["FlstNr"]=$FlstNrFormObj;
    $GUI->FormObject["selectedFlstNr"]=$SelectedFlstNrFormObj;
  };
	
	$GUI->flurstSuchen = function() use ($GUI){
		$GUI->sanitize(['GemID' => 'text', 'GemkgID' => 'text', 'FlurID' => 'text', 'selFlstID' => 'text', 'FlstNr' => 'text']);
		include_once(PLUGINS.'alkis/model/kataster.php');
    $GemID = $GUI->formvars['GemID'];
    $GemkgID = $GUI->formvars['GemkgID'];
    if ($GUI->formvars['FlurID'] != '-1') {
      # dreistelliges auffüllen der Flurnummer mit Nullen
      $FlurID = str_pad($GUI->formvars['FlurID'],3,"0",STR_PAD_LEFT);
    }
    else {
      $FlurID = $GUI->formvars['FlurID'];
    }
    $FlstID = $GUI->formvars['selFlstID'];
    $FlstNr = formatFlurstkennzALKIS($GUI->formvars['FlstNr']);
    $Gemarkung=new gemarkung('',$GUI->pgdatabase);
    # abfragen, ob es sich um eine gültige GemarkungsID handelt
		if ($GUI->formvars['historical'] != 1) {
			$GemkgListe=$Gemarkung->getGemarkungListe(array($GemID),array($GemkgID));
		}
		else {
			$GemkgListe=$Gemarkung->getGemarkungListeAll(NULL, array($GemkgID));
		}
		if(count_or_0($GemkgListe['GemkgID']) > 0){
      # Die Gemarkung ist ausgewählt und gültig aber Flur leer, zoom auf Gemarkung
      if ($FlurID === 0 OR $FlurID == '-1') {
				if($GUI->formvars['ALK_Suche'] == 1){
					$GUI->loadMap('DataBase');
					$GUI->zoomToALKGemarkung($GemkgID,10);
					$currenttime=date('Y-m-d H:i:s',time());
					$GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
					$GUI->drawMap();
					$GUI->saveMap('');
				}
				else{			# Anzeige der Flurstuecke der Gemarkung
					$FlstNr=new flurstueck('',$GUI->pgdatabase);
					$FlstNrListe=$FlstNr->getFlstListe($GemID,$GemkgID,'',$GUI->formvars['historical']);
					$FlstID = $FlstNrListe['FlstID'];
					$FlurstKennz = array_values(array_unique($FlstID));
					$GUI->flurstAnzeige($FlurstKennz);
				}
      }
      else {
        # ist Gemarkung und Flur ausgefüllt aber keine Angabe zum Flurstück, zoom auf Flur
        if(($FlstID=='' AND $FlstNr=='') OR ($FlstID=='-1')){
        	if($GUI->formvars['ALK_Suche'] == 1){
	          $GUI->loadMap('DataBase');
	          $GUI->zoomToALKFlur($GemID,$GemkgID,$FlurID,10);
	          $currenttime=date('Y-m-d H:i:s',time());
	          $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
	          $GUI->drawMap();
	          $GUI->saveMap('');
        	}
	        else{			# Anzeige der Flurstuecke der Flur
	      		$FlstNr=new flurstueck('',$GUI->pgdatabase);
	      		$FlstNrListe=$FlstNr->getFlstListe($GemID,$GemkgID,$FlurID,$GUI->formvars['historical']);
		        $FlstID = $FlstNrListe['FlstID'];
	          $FlurstKennz = array_values(array_unique($FlstID));
	          $GUI->flurstAnzeige($FlurstKennz);
	      	}
        }
        else {
          # es existiert eine Angabe zum Flurstück
          $Flurstueck=new flurstueck('',$GUI->pgdatabase);
          # wenn keine FlstID angegeben wurde, wird versucht die FlstID aus der FlstNr abzuleiten
          if ($FlstID=='') {
            # ableiten der FlstID aus den Angaben in FlstNr
            $FlurstKennz[0]=$Flurstueck->is_FlurstNr($GemkgID,$FlurID,$FlstNr);
            if ($FlurstKennz[0]==0) {
              # aus FlstNr konnte kein eindeutiges FlurstKennz abgeleitet werden
              # Abfrage ob der Zähler eines Flurstücks mit FlstNr übereinstimmt
              $FlurstKennz=$Flurstueck->is_FlurstZaehler($GemkgID,$FlurID,$FlstNr);
              # wenn im Ergebnis die Anzahl der gefundenen FlurstKennz 0 ist wird weiter unten Suche abgebrochen
            }
          }
          else {
            # wenn FlstID nicht leer ist, wird diese zur Suche übernommen
            $FlurstKennz = explode(', ', $FlstID);
            $FlurstKennz = array_values(array_unique($FlurstKennz));
          }
          $anzFlurst=count($FlurstKennz);
          if ($anzFlurst==0) {
            # es konnten überhaupt keine gültigen Flurstuecke aus den Angaben FlstNr gefunden werden
            # zurück zur Auswahl mit Hinweis, daß Flurstücksauswahl zu keinem Ergebnis führt
            $GUI->Fehlermeldung='Zu diesem Flurstück wurden keine Angaben gefunden!';
            $GUI->flurstwahl();
          }
          else {
            # Es wurde mindestens ein eindeutiges FlurstKennz in FlstID ausgewählt, oder ein oder mehrere über FlstNr gefunden
            # Zoom auf Flurstücke
						if ($GUI->user->rolle->querymode == 1 OR $GUI->formvars['ALK_Suche'] == 1) {
              if($GUI->formvars['historical'])echo 'sdfsdf';
							$GUI->zoomToALKFlurst($FlurstKennz, 10, true, ($GUI->formvars['history_mode'] != '' AND $GUI->formvars['history_mode'] != 'aktuell'));
							$GUI->saveMap('');
						}						
            if($GUI->formvars['ALK_Suche'] == 1){
							if($GUI->formvars['go_next'] != ''){
								$GUI->formvars['FlurstKennz'] = $FlurstKennz;
								go_switch($GUI->formvars['go_next']);
								exit();
							}
		          $currenttime=date('Y-m-d H:i:s',time());
		          $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
		          $GUI->drawMap();
		          $GUI->saveMap('');
            }
            else{
            	$GUI->flurstAnzeige($FlurstKennz);
							$GUI->zoomed = true;
            }
          }
        } # ende Suche nach Flurstück
      } # ende Suche nach Flur
    }
    else {
			if($FlstNr != ''){
				if($GUI->formvars['ALK_Suche'] == 1){
					$GUI->zoomToALKFlurst(array($FlstNr),10);
					if($GUI->formvars['go_next'] != ''){
						$GUI->saveMap('');
						go_switch($GUI->formvars['go_next']);
						exit();
					}
					$currenttime=date('Y-m-d H:i:s',time());
					$GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
					$GUI->drawMap();
					$GUI->saveMap('');
				}
				else{
					$GUI->flurstAnzeige(array($FlstNr));			# ein Flurstückskennzeichen wurde in das EIngabefeld eingetragen
				}
			}
			else{
				$GUI->Fehlermeldung='Wählen Sie eine Gemarkung!';
				$GUI->flurstwahl();
			}
    }
  };

	$GUI->flurstSuchenByLatLng = function() use ($GUI){
		include_once(PLUGINS.'alkis/model/kataster.php');
    $flurstueck = new flurstueck('',$GUI->pgdatabase);
		if (in_array($GUI->formvars['version'], array("1.0", "1.0.0"))) {
			$result= $flurstueck->getFlurstByLatLng($GUI->formvars['latitude'], $GUI->formvars['longitude']);
			$layerset['landId'] = $result['land'];
			$layerset['kreisId'] = $result['kreis'];
			$layerset['gemeindId'] = $result['gemeinde'];
			$layerset['gemarkungId'] = $result['gemarkungsnummer'];
			$layerset['gemarkungName'] = $result['gemarkungname'];
			$layerset['flurId'] = $result['flurnummer'];
			$layerset['flurstueckId'] = $result['flurstkennz'];
			$layerset['flurstueckNummer'] = $result['flurstuecksnummer'];
			$GUI->qlayerset[0]['shape'][0] = $layerset;
			$GUI->mime_type = 'formatter';
		}
		else {
			$GUI->loadMap('DataBase');
      $GUI->user->rolle->newtime = $GUI->user->rolle->last_time_id;
      $GUI->drawMap();
     	$GUI->saveMap('');
		}
	};

	$GUI->Flurstueck_GetVersionen = function() use ($GUI){
		include_once(PLUGINS.'alkis/model/kataster.php');
		$ret=$GUI->Stelle->getFlurstueckeAllowed(array($GUI->formvars['flurstkennz']), $GUI->pgdatabase);
    if($ret[0]) {
      $GUI->Fehlermeldung=$ret[1];
    }
    else{
      $flst = new flurstueck($GUI->formvars['flurstkennz'], $GUI->pgdatabase);
			$versionen = $flst->getVersionen();
			$timestamp = DateTime::createFromFormat('d.m.Y H:i:s', $GUI->user->rolle->hist_timestamp_de);
			$output = '	<table cellspacing="0" cellpadding="3">
										<tr style="background-color: #EDEFEF;">
											<td style="border-bottom: 1px solid '.BG_DEFAULT.'">
												<a href="javascript:hide_versions(\''.$GUI->formvars['flurstkennz'].'\');"><img src="'.GRAPHICSPATH.'minus.gif"></a>
											</td>
											<td style="border-bottom: 1px solid '.BG_DEFAULT.'">
												<span class="px14">Versionen</span>
											</td>
										</tr>
										<tr>
											<td></td>
											<td>';
												if(count($versionen) > 0){
			$output.= '					<select name="versions_'.$k.'" onchange="overlay_link(\'go=setHistTimestamp&timestamp=\' + this.value + \'&go_next=get_last_query\', false); if(root.document.getElementById(\'map\')){root.neuLaden();}" style="max-width: 500px">';
													$selected = false;
													$v = 1;
													$count = count($versionen);
													reset($versionen);
													while($version = current($versionen)){
														$version_beginnt = key($versionen);
														$next_version = next($versionen);
														$next_version_beginnt = key($versionen);
														$beginnt = DateTime::createFromFormat('d.m.Y H:i:s', $version_beginnt);
														$next_beginnt = DateTime::createFromFormat('d.m.Y H:i:s', $next_version_beginnt);
														$output.= '<option ';
														if($selected == false AND
															(($timestamp >= $beginnt AND $timestamp < $next_beginnt) OR				# timestamp liegt im Intervall
															$v == $count)																											# letzte Version (aktuell)
														){$selected = true; $output.= 'selected';}
														if($v < $count)$output.= ' value="'.$version_beginnt.'"';
														else $output.= ' value=""';
														$output.= ' title="'.implode(', ', $version['table']).'">';
														$output.= $version_beginnt.' '.implode(' ', $version['anlass']).'</option>';
														$v++;
													}
			$output.= '					</select>';
												}
			$output.= '			</td>
										</tr>
									</table>';
			echo $output;
    }
	};

	$GUI->flurstAnzeige = function($FlurstKennzListe) use ($GUI){
		include_once(PLUGINS.'alkis/model/kataster.php');
    # 2006-01-26 pk
    # Abfrage der Berechtigung zum Anzeigen der FlurstKennzListe
    $ret=$GUI->Stelle->getFlurstueckeAllowed($FlurstKennzListe, $GUI->pgdatabase);
    if ($ret[0]) {
      $GUI->Fehlermeldung=$ret[1];
      $anzFlurst=0;
    }
    else {
      $FlurstKennzListe=$ret[1];
      $anzFlurst=count($FlurstKennzListe);
    }

    $GUI->mapDB = new db_mapObj($GUI->Stelle->id,$GUI->user->id);
    $layer = $GUI->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
		$layerdb = $GUI->mapDB->getlayerdatabase($layer[0]['layer_id'], $GUI->Stelle->pgdbhost);
    $privileges = $GUI->Stelle->get_attributes_privileges($layer[0]['layer_id']);
    $layer[0]['attributes'] = $GUI->mapDB->read_layer_attributes($layer[0]['layer_id'], $layerdb, $privileges['attributenames']);

		for($j = 0; $j < count($layer[0]['attributes']['name']); $j++){
			$layer[0]['attributes']['privileg'][$j] = $privileges[$layer[0]['attributes']['name'][$j]];
			$layer[0]['attributes']['privileg'][$layer[0]['attributes']['name'][$j]] = $privileges[$layer[0]['attributes']['name'][$j]];
		}
    $GUI->qlayerset[] = $layer[0];
    $GUI->main = $layer[0]['template'];

		$GUI->user->rolle->delete_last_query();
		$GUI->user->rolle->save_last_query('Flurstueck_Anzeigen', $layer[0]['layer_id'], implode(';', $FlurstKennzListe), NULL, NULL, NULL);

    $j = $GUI->qlayerset[0]['attributes']['indizes']['eigentuemer'];
    if ($GUI->qlayerset[0]['attributes']['vcheck_attribute'][$j]) {
      $GUI->formvars['selected_layer_id'] = $layer[0]['layer_id'];
      $GUI->formvars['value_flurstkennz'] = implode('|', $FlurstKennzListe);
      $GUI->formvars['operator_flurstkennz'] = 'IN';
      $GUI->formvars['no_output'] = true;
      $GUI->GenerischeSuche_Suchen();
    }
    else {
      for ($i=0;$i<$anzFlurst;$i++) {
        $GUI->qlayerset[0]['shape'][$i]['flurstkennz'] = $FlurstKennzListe[$i];
      }
    }
    $i = 0;
  };
	
	$GUI->ALKIS_WSDL = function() use ($GUI){
		include_once(PLUGINS.'alkis/model/alkis.php');
		$alkis = new ALKIS($GUI->pgdatabase);
		echo $alkis->dhk_wsdl_login();
	};

	$GUI->ALKIS_Auszug = function($FlurstKennz,$Grundbuchbezirk,$Grundbuchblatt,$Buchnungstelle,$formnummer) use ($GUI){
		include_once(PLUGINS.'alkis/model/alkis.php');
		include_once(PLUGINS.'alkis/model/kataster.php');
    if($FlurstKennz[0] == '' AND ($Grundbuchbezirk != NULL OR $Buchnungstelle != NULL)){
      $grundbuch=new grundbuch($Grundbuchbezirk,$Grundbuchblatt,$GUI->pgdatabase);
      # Abfrage aller Flurstücke, die auf dem angegebenen Grundbuchblatt liegen.
      $ret=$grundbuch->getBuchungen('','','',1, $Buchnungstelle);
      $buchungen=$ret[1];
      for ($b=0;$b < count($buchungen);$b++) {
        $FlurstKennz[] = $buchungen[$b]['flurstkennz'];
      }
    }
    # Abfrage der Berechtigung zum Anzeigen der FlurstKennz
    $ret=$GUI->Stelle->getFlurstueckeAllowed($FlurstKennz,$GUI->pgdatabase);
    if ($ret[0]) {
      $GUI->Fehlermeldung=$ret[1];
      $GUI->loadMap('DataBase');
			$GUI->user->rolle->newtime = $GUI->user->rolle->last_time_id;
			$GUI->drawMap();
			$GUI->output();
    }
    else{
      $FlurstKennz=$ret[1];
			$GUI->getFunktionen();
			if(!$GUI->Stelle->funktionen[$formnummer]['erlaubt']){
				showAlert('Die Anzeige dieses Nachweises ist für diese Stelle nicht erlaubt.');
				exit();
			}
      # Ausgabe der Flurstücksdaten im PDF Format
			$alkis = new ALKIS($GUI->pgdatabase);
			$nasfile = $alkis->create_nas_request_xml_file($FlurstKennz, $Grundbuchbezirk, $Grundbuchblatt, $Buchnungstelle, NULL, $formnummer);
			$sessionid = $alkis->dhk_call_login(DHK_CALL_URL, DHK_CALL_USER, DHK_CALL_PASSWORD);

			$currenttime=date('Y-m-d_H-i-s',time());
			switch($formnummer){
				case 'MV0700' : {
					$log_number = array($Grundbuchbezirk.'-'.$Grundbuchblatt);
					$filename = 'Bestandsnachweis_'.$currenttime;
				}break;

				case 'MV0600' : {
					$log_number = array($Buchnungstelle);
					$filename = 'Grundstücksnachweis_'.$currenttime;
				}break;

				default : {
					$log_number = $FlurstKennz;
					$filename = 'Flurstücksnachweis_'.$currenttime;
				}break;
			}
			$output = $alkis->dhk_call_getPDF(DHK_CALL_URL, $sessionid, $nasfile, $filename);
			switch (substr($output, 0, 2)){
				case 'PK' : $type = 'zip'; break;
				case '<?' : $type = 'xml'; break;
				case '%P' : $type = 'pdf'; break;
			}
			$currenttime=date('Y-m-d H:i:s',time());
      $GUI->user->rolle->setConsumeALB($currenttime, substr($formnummer, 3, 3),$log_number, 0, 'NULL');
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header('Content-Disposition: attachment; filename='.$filename.'.'.$type);
			header("Content-Transfer-Encoding: binary");
			print $output;
		}
	};

	$GUI->ALKIS_Kartenauszug = function($layout, $formvars) use ($GUI){
		include_once(PLUGINS.'alkis/model/alkis.php');
		$alkis = new ALKIS($GUI->pgdatabase);
		$point = new PointObj();
		$point->setXY($formvars['center_x'], $formvars['center_y']);
		$projFROM = new projectionObj("init=epsg:" . $GUI->user->rolle->epsg_code);
		$projTO = new projectionObj("init=epsg:".EPSGCODE_ALKIS);
		$point->project($projFROM, $projTO);
		$print_params['coord'] = $point->x.' '.$point->y;
		$print_params['printscale'] = $formvars['printscale'];
		$print_params['format'] = substr($layout['format'], 0, 3);
		$formnummer = $layout['dhk_call'];
		$nasfile = $alkis->create_nas_request_xml_file(NULL, NULL, NULL, NULL, $print_params, $formnummer);
		$sessionid = $alkis->dhk_call_login(DHK_CALL_URL, DHK_CALL_USER, DHK_CALL_PASSWORD);
		$currenttime=date('Y-m-d_H-i-s',time());
		$filename = 'Kartenauszug_'.$currenttime;
		$GUI->user->rolle->setConsumeALK($currenttime, $GUI->Docu->activeframe[0]['id']);
		return $alkis->dhk_call_getPDF(DHK_CALL_URL, $sessionid, $nasfile, $filename);
	};

	$GUI->ALB_Anzeigen = function($FlurstKennz,$formnummer,$Grundbuchbezirk,$Grundbuchblatt) use ($GUI){
		include_once(PLUGINS.'alkis/model/alkis.php');
		include_once(PLUGINS.'alkis/model/kataster.php');
    if($FlurstKennz[0] == '' AND ($Grundbuchbezirk != NULL OR $Buchnungstelle != NULL)){
      $grundbuch=new grundbuch($Grundbuchbezirk,$Grundbuchblatt,$GUI->pgdatabase);
      # Abfrage aller Flurstücke, die auf dem angegebenen Grundbuchblatt liegen.
      $ret=$grundbuch->getBuchungen('','','',1, $Buchnungstelle);
      $buchungen=$ret[1];
      for ($b=0;$b < count($buchungen);$b++) {
        $FlurstKennz[] = $buchungen[$b]['flurstkennz'];
      }
    }
    # Abfrage der Berechtigung zum Anzeigen der FlurstKennz
    $ret=$GUI->Stelle->getFlurstueckeAllowed($FlurstKennz,$GUI->pgdatabase);
    if ($ret[0]) {
      $GUI->Fehlermeldung=$ret[1];
      $GUI->loadMap('DataBase');
			$GUI->user->rolle->newtime = $GUI->user->rolle->last_time_id;
			$GUI->drawMap();
			$GUI->output();
    }
    else {
      $FlurstKennz=$ret[1];
      $GUI->getFunktionen();
      # Prüfen ob stelle Formular 30 sehen darf
      if ($formnummer==30) {
        if(!$GUI->Stelle->funktionen['ALB-Auszug 30']['erlaubt']) {
          showAlert('Die Anzeige des Eigentümernachweises ist für diese Stelle nicht erlaubt.');
          exit();
        }
      }
      # Prüfen ob stelle Formular 35 sehen darf
      if ($formnummer==35) {
        if(!$GUI->Stelle->funktionen['ALB-Auszug 35']['erlaubt']) {
          showAlert('Die Anzeige des Eigentümernachweises ist für diese Stelle nicht erlaubt.');
          exit();
        }
      }
      # Prüfen ob stelle Formular 40 sehen darf
      if ($formnummer==40) {
        if(!$GUI->Stelle->funktionen['ALB-Auszug 40']['erlaubt']) {
          showAlert('Die Anzeige des Eigentümernachweises ist für diese Stelle nicht erlaubt.');
          exit();
        }
      }
      # Prüfen ob stelle Formular 20 sehen darf
      if ($formnummer==20) {
        if(!$GUI->Stelle->funktionen['ALB-Auszug 20']['erlaubt']) {
          showAlert('Die Anzeige des Eigentümernachweises ist für diese Stelle nicht erlaubt.');
          exit();
        }
      }
      # Prüfen ob stelle Formular 25 sehen darf
      if ($formnummer==25) {
        if(!$GUI->Stelle->funktionen['ALB-Auszug 25']['erlaubt']) {
          showAlert('Die Anzeige des Eigentümernachweises ist für diese Stelle nicht erlaubt.');
          exit();
        }
      }
      # Ausgabe der Flurstücksdaten im PDF Format
      include (CLASSPATH.'class.ezpdf.php');
      $pdf=new Cezpdf();
			$alkis = new ALKIS($GUI->pgdatabase);

      if($formnummer < 26){
        $log_number = array($Grundbuchbezirk.'-'.$Grundbuchblatt);
        $currenttime=date('Y-m-d H:i:s',time());
        $pdf=$alkis->ALBAuszug_Bestand($Grundbuchbezirk,$Grundbuchblatt,$formnummer);
        $GUI->user->rolle->setConsumeALB($currenttime,$formnummer,$log_number,$GUI->formvars['wz'],$pdf->pagecount);
      }
      else{
        $currenttime=date('Y-m-d H:i:s',time());
        $pdf=$alkis->ALBAuszug_Flurstueck($FlurstKennz,$formnummer);
        $GUI->user->rolle->setConsumeALB($currenttime,$formnummer,$FlurstKennz,$GUI->formvars['wz'],$pdf->pagecount);
      }
      $GUI->pdf=$pdf;

      $dateipfad=IMAGEPATH;
      $currenttime = date('Y-m-d_H-i-s',time());
      $name = sonderzeichen_umwandeln($GUI->user->Name);
      $dateiname = $name.'-'.$currenttime.'.pdf';
      $GUI->outputfile = $dateiname;
      $fp=fopen($dateipfad.$dateiname,'wb');
      fwrite($fp,$GUI->pdf->ezOutput());
      fclose($fp);

      $GUI->mime_type='pdf';
    }
    $GUI->output();
  };
	
	$GUI->generischer_Flurstuecksauszug = function($flurst_array) use ($GUI){
		$GUI->formvars['chosen_layer_id'] = $GUI->formvars['selected_layer_id'];
		$GUI->formvars['value_flurstueckskennzeichen'] = implode('|', $flurst_array);
		$GUI->formvars['operator_flurstueckskennzeichen'] = 'IN';
		$GUI->formvars['no_output'] = true;
		$GUI->formvars['no_last_search'] = true;
		$GUI->GenerischeSuche_Suchen();
		$GUI->formvars['aktivesLayout'] = $GUI->formvars['formnummer'];
		$result = $GUI->generischer_sachdaten_druck_createPDF();
    $GUI->outputfile = basename($result['pdf_file']);
    $GUI->mime_type='pdf';
    $GUI->output();
  };

	$GUI->export_Adressaenderungen = function() use ($GUI){
    $GUI->titel='Adressänderungen der Eigentümer exportieren';
		$GUI->main = PLUGINS.'alkis/view/Adressaenderungen_Export.php';
    $GUI->output();
  };

	$GUI->export_Adressaenderungen_exportieren = function() use ($GUI){
		include_once(PLUGINS.'alkis/model/adressaenderungen.php');
    $adressaenderungen = new adressaenderungen($GUI->pgdatabase);
    $adressaenderungen->delete_old_entries();
    $adressaenderungen->read_anschriften();
		$adressaenderungen->read_personen();
    $GUI->filename = $adressaenderungen->export_into_file();
    if ($GUI->filename != '') { 
      ob_end_clean();
      header('Content-type: application/octet-stream');
      header("Content-disposition:	attachment; filename=" . basename($GUI->filename));
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Pragma: public');
      readfile($GUI->filename);
    }
  };
	
	$GUI->export_flurst_csv = function() use ($GUI){
		$GUI->attribute_selections = $GUI->user->rolle->get_csv_attribute_selections();
    $GUI->attribute = explode(';', $GUI->formvars['attributliste']);
		$GUI->main = PLUGINS.'alkis/view/export_flurstuecke_csv.php';
   	$GUI->titel = $GUI->formvars['formnummer'].'-CSV-Export';
    $GUI->output();
  };

	$GUI->export_flurst_csv_auswahl_speichern = function() use ($GUI){
  	$GUI->user->rolle->save_csv_attribute_selection($GUI->formvars['name'], $GUI->formvars['attributes']);
  	$GUI->formvars['selection'] = $GUI->formvars['name'];
  	$GUI->export_flurst_csv_auswahl_laden();
  };

	$GUI->export_flurst_csv_auswahl_laden = function() use ($GUI){
  	$GUI->selection = $GUI->user->rolle->get_csv_attribute_selection($GUI->formvars['selection']);
  	$attributes = explode('|', $GUI->selection['attributes']);
  	for($i = 0; $i < count($attributes); $i++){
  		$GUI->formvars[$attributes[$i]] = 'true';
  	}
  	$GUI->export_flurst_csv();
  };

	$GUI->export_flurst_csv_auswahl_loeschen = function() use ($GUI){
  	$GUI->user->rolle->delete_csv_attribute_selection($GUI->formvars['selection']);
  	$GUI->export_flurst_csv();
  };

	$GUI->export_flurst_csv_exportieren = function() use ($GUI){
		include_once(PLUGINS.'alkis/model/kataster.php');
		include_once(PLUGINS.'alkis/model/alkis.php');
    $flurstuecke = explode(';', $GUI->formvars['FlurstKennz']);
    $ret = $GUI->Stelle->getFlurstueckeAllowed($flurstuecke, $GUI->pgdatabase);
    if ($ret[0]) {
      $GUI->Fehlermeldung=$ret[1];
      showAlert($ret[1]);
    }
    else {
      $flurstuecke = $ret[1];
      $alkis = new ALKIS($GUI->pgdatabase);
      $currenttime=date('Y-m-d H:i:s',time());
      switch ($GUI->formvars['formnummer']){
      	case 'Flurstück' : {
      		$alkis->export_flurst_csv($flurstuecke, $GUI->formvars);
      		$GUI->user->rolle->setConsumeCSV($currenttime,'Flurstück',count($flurstuecke));
      	}break;
      	case 'Nutzungsarten' : {
      		$alkis->export_nutzungsarten_csv($flurstuecke, $GUI->formvars);
      		$GUI->user->rolle->setConsumeCSV($currenttime,'Nutzungsarten',count($flurstuecke));
      	}break;
      	case 'Eigentümer' : {
      		$alkis->export_eigentuemer_csv($flurstuecke, $GUI->formvars);
      		$GUI->user->rolle->setConsumeCSV($currenttime,'Eigentümer',count($flurstuecke));
      	}break;
      	case 'Klassifizierung' : {
      		$alkis->export_klassifizierung_csv($flurstuecke, $GUI->formvars);
      		$GUI->user->rolle->setConsumeCSV($currenttime,'Klassifizierung',count($flurstuecke));
      	}break;
      }
    }
  };
	
	$GUI->grundbuchblattWahl = function() use ($GUI){
		include_once(PLUGINS.'alkis/model/kataster.php');
    $GUI->titel='Suche nach Grundbuchblättern';
		$GUI->main = PLUGINS.'alkis/view/grundbuchblattsuchform.php';
    $grundbuch = new grundbuch('', '', $GUI->pgdatabase);
    $GemeindenStelle=$GUI->Stelle->getGemeindeIDs();
    if (!empty($GemeindenStelle['ganze_gemeinde']) OR !empty($GemeindenStelle['ganze_gemarkung']) OR !empty($GemeindenStelle['eingeschr_gemarkung'])){   // Stelle ist auf Gemeinden eingeschränkt
      $Gemarkung=new gemarkung('',$GUI->pgdatabase);
			$ganze_gemarkungen = array_keys($GemeindenStelle['ganze_gemarkung']);
			if (!empty($GemeindenStelle['ganze_gemeinde'])) {
				$GemkgListe = $Gemarkung->getGemarkungListe(array_keys($GemeindenStelle['ganze_gemeinde']), NULL);
				$ganze_gemarkungen = array_merge($GemkgListe['GemkgID'], $ganze_gemarkungen);
			}
      $gbliste = $grundbuch->getGrundbuchbezirkslisteByGemkgIDs($ganze_gemarkungen, $GemeindenStelle['eingeschr_gemarkung']);
    }
    else{
      $gbliste = $grundbuch->getGrundbuchbezirksliste();
    }
    // Sortieren der Grundbuchbezirke unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($gbliste['bezeichnung'], $gbliste['schluessel']);
    $gbliste['schluessel'] = $sorted_arrays['second_array'];
    $sorted_arrays = umlaute_sortieren($gbliste['bezeichnung'], $gbliste['beides']);
    $gbliste['bezeichnung'] = $sorted_arrays['array'];
    $gbliste['beides'] = $sorted_arrays['second_array'];
    $GUI->gbliste = $gbliste;
		####### Import ###########
		$_files = $_FILES;
    if($_files['importliste']['name']){
			$importliste = file($_files['importliste']['tmp_name'], FILE_IGNORE_NEW_LINES);
			$GUI->formvars['selBlatt'] = implode(', ', $importliste);
			$GUI->formvars['Bezirk'] = substr($importliste[0], 0, 6);
		}
		##########################
    if($GUI->formvars['Bezirk'] != ''){
    	if($GUI->formvars['selBlatt'])$GUI->selblattliste = explode(', ',$GUI->formvars['selBlatt']);
			if (!empty($GemeindenStelle['ganze_gemeinde']) OR !empty($GemeindenStelle['ganze_gemarkung']) OR !empty($GemeindenStelle['eingeschr_gemarkung'])){   // Stelle ist auf Gemeinden eingeschränkt
				$GUI->blattliste = $grundbuch->getGrundbuchblattlisteByGemkgIDs($GUI->formvars['Bezirk'], $ganze_gemarkungen, $GemeindenStelle['eingeschr_gemarkung'], $GemeindenStelle['ganze_flur'], $GemeindenStelle['eingeschr_flur']);
			}
			else{
				$GUI->blattliste = $grundbuch->getGrundbuchblattliste($GUI->formvars['Bezirk']);
			}
    }
    $GUI->output();
  };

	$GUI->grundbuchblattSuchen = function() use ($GUI){
		include_once(PLUGINS.'alkis/model/kataster.php');
  	$blaetter = explode(', ', $GUI->formvars['selBlatt']);
  	for($i = 0; $i < count($blaetter); $i++){
  		$blatt = explode('-', $blaetter[$i]);		# bezirk-blatt
	    # Prüfen der eingegebenen Parameter
	    $grundbuch=new grundbuch($blatt[0],$blatt[1],$GUI->pgdatabase);
	    $ret=$grundbuch->grundbuchblattSuchParameterPruefen();
	    if ($ret[0]) {
	      $GUI->Fehlermeldung='Angaben fehlerhaft:'.$ret[1];
	      $GUI->grundbuchblattWahl();
				return;
	    }
	    else {
	      # Suchparameter sind in Ordnung
	      # Abfrage aller Flurstücke, die auf dem angegebenen Grundbuchblatt liegen.
	      $ret=$grundbuch->getBuchungen('','','',1);
	      if ($ret[0]) {
	        # Fehler bei der Abfrage der Flurstücke des Grundbuchblattes
	        $GUI->Fehlermeldung=$ret[1];
	        $GUI->grundbuchblattWahl();
					return;
	      }
	      else {
	        $buchungen=$ret[1];
	        # Test ob Flurstücke gefunden wurden
	        $anzFlst=count($buchungen);
	        if ($anzFlst==0) {
	          # Wenn keine Flurstücke gefunden wurden
	          $GUI->Fehlermeldung.='Es konnten keine Flurstücke zu dem Grundbuchblatt '.$blatt[0].'-'.$blatt[1].' gefunden werden.<br>';
	          $GUI->grundbuchblattWahl();
						return;
	        }
	        else {
	          # Es wurden Flurstücke gefunden, ins Ergebnisarray aufnehmen
	          $gbblaetter[] = $buchungen;
	        } # Ende mit Flurstücksanzeige
	      } # Ende mit Flurstücke erfolgreich abgefragt
	    } # Ende mit Suchparameter sind in Ordnung
  	}
		$GUI->user->rolle->delete_last_query();
		$GUI->user->rolle->save_last_query('Grundbuchblatt_Auswaehlen_Suchen', 0, $GUI->formvars['selBlatt'], NULL, NULL, NULL);
  	$GUI->grundbuchblattanzeige($gbblaetter);
  };

	$GUI->grundbuchblattanzeige = function($gbblaetter) use ($GUI){
		$GUI->main = PLUGINS.'alkis/view/grundbuchblattanzeige.php';
    $GUI->titel='Buchungen zum Grundbuchblatt';
    $GUI->gbblaetter=$gbblaetter;
    $GUI->output();
  };
	

	$GUI->namenWahl = function() use ($GUI){
		include_once(PLUGINS.'alkis/model/kataster.php');
		include_once(CLASSPATH.'FormObject.php');
    if ($GUI->formvars['anzahl']==0) {
      $GUI->formvars['anzahl']=10;
    }
		$GUI->main = PLUGINS.'alkis/view/namensuchform.php';
		$GemeindenStelle=$GUI->Stelle->getGemeindeIDs();
		$GemkgID=$GUI->formvars['GemkgID'];
		$Gemarkung=new gemarkung('',$GUI->pgdatabase);
		if($GemeindenStelle == NULL){
			$GemkgListe=$Gemarkung->getGemarkungListe(NULL, NULL);
		}
		else{
			$GemkgListe=$Gemarkung->getGemarkungListe(array_keys($GemeindenStelle['ganze_gemeinde']), array_merge(array_keys($GemeindenStelle['ganze_gemarkung']), array_keys($GemeindenStelle['eingeschr_gemarkung'])));
		}
    // Sortieren der Gemarkungen unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($GemkgListe['Bezeichnung'], $GemkgListe['GemkgID']);
    $GemkgListe['Bezeichnung'] = $sorted_arrays['array'];
    $GemkgListe['GemkgID'] = $sorted_arrays['second_array'];
    # Erzeugen des Formobjektes für die Gemarkungsauswahl
    $GUI->GemkgFormObj=new selectFormObject("GemkgID","select",$GemkgListe['GemkgID'],array($GemkgID),$GemkgListe['Bezeichnung'],"1","","",NULL);
    $GUI->GemkgFormObj->insertOption(-1,0,'--Auswahl--',0);
    $GUI->GemkgFormObj->outputHTML();
    # Abragen der Fluren zur Gemarkung
    if($GemkgID > 0){
    	$Flur=new Flur('','','',$GUI->pgdatabase);
			$FlurListe=$Flur->getFlurListe($GemkgID, $GemeindenStelle['eingeschr_gemarkung'][$GemkgID], 'aktuell');
    	# Erzeugen des Formobjektes für die Flurauswahl
    	if (count($FlurListe['FlurID'])==1) { $FlurID=$FlurListe['FlurID'][0]; }
    }
    $GUI->FlurFormObj=new FormObject("FlurID","select",$FlurListe['FlurID'],$GUI->formvars['FlurID'],$FlurListe['name'],"1","","",NULL);
    $GUI->FlurFormObj->insertOption(-1,0,'--Auswahl--',0);
    $GUI->FlurFormObj->outputHTML();
    if (value_of($GUI->formvars, 'map_flag') != '') {
      ################# Map ###############################################
      if (value_of($GUI->formvars, 'geom_from_layer') == '') {
        $GUI->formvars['geom_from_layer'] = $GUI->formvars['selected_layer_id'];
      }
      $saved_scale = $GUI->reduce_mapwidth(10);
      $GUI->loadMap('DataBase');
      if (value_of($GUI->formvars, 'CMD') == '' AND $saved_scale != NULL) {
        $GUI->scaleMap($saved_scale);		# nur, wenn nicht navigiert wurde
      }
      $GUI->queryable_vector_layers = $GUI->Stelle->getqueryableVectorLayers(NULL, $GUI->user->id, NULL, NULL, NULL, true, true);
      if (in_array(value_of($GUI->formvars, 'CMD'), ['Full_Extent', 'recentre', 'zoomin', 'zoomout', 'previous', 'next'])) {
        $GUI->navMap($GUI->formvars['CMD']);
      }
      $GUI->drawMap();
      $GUI->saveMap('');
      $currenttime=date('Y-m-d H:i:s',time());
      $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
      ########################################################################
    }
    $GUI->output();
  };

	$GUI->nameSuchen = function() use ($GUI){
		include_once(PLUGINS.'alkis/model/kataster.php');
		$layerset = $GUI->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
		$GemeindenStelle=$GUI->Stelle->getGemeindeIDs();
		if(!empty($GemeindenStelle['ganze_gemeinde'])){
			$Gemarkung=new gemarkung('',$GUI->pgdatabase);
			$GemkgListe = $Gemarkung->getGemarkungListe(array_keys($GemeindenStelle['ganze_gemeinde']), array_keys($GemeindenStelle['ganze_gemarkung']));
			$GemeindenStelle['ganze_gemarkung'] = array_flip($GemkgListe['GemkgID']);
		}
    $formvars = $GUI->formvars;
    $formvars['user_epsg'] = $GUI->user->rolle->epsg_code;
    $flurstueck=new flurstueck('',$GUI->pgdatabase);
		$ret=$flurstueck->getNamen($formvars, array_keys($GemeindenStelle['ganze_gemarkung']), $GemeindenStelle['eingeschr_gemarkung'], $GemeindenStelle['ganze_flur'], $GemeindenStelle['eingeschr_flur']);
    if ($ret[0]) {
      $GUI->Fehlermeldung='<br>Es konnten keine Namen abgefragt werden'.$ret[1];
      $GUI->namenWahl();
    }
    else {
      $GUI->namen=$ret[1];
      if (count_or_0($GUI->namen)==0) {
        $GUI->Fehlermeldung='<br>Es konnten keine Namen gefunden werden, bitte ändern Sie die Anfrage!';
      }
      else {
				$formvars['anzahl'] = '';
				$formvars['offset'] = '';
				$ret=$flurstueck->getNamen($formvars, @array_keys($GemeindenStelle['ganze_gemarkung']), $GemeindenStelle['eingeschr_gemarkung'], $GemeindenStelle['ganze_flur'], $GemeindenStelle['eingeschr_flur']);
        $GUI->anzNamenGesamt=count($ret[1]);

				for($i = 0; $i < count($GUI->namen); $i++){
					$currenttime=date('Y-m-d H:i:s',time());
					$GUI->user->rolle->setConsumeALB($currenttime, 'Eigentümersuche', array($GUI->namen[$i]['gml_id']), 0, 'NULL');		# die gml_id aus ax_namensnummer wird geloggt
					if($GUI->formvars['withflurst'] == 'on'){
            $ret[1] = $flurstueck->getFlurstByGrundbuecher(array($GUI->namen[$i]['bezirk'].'-'.$GUI->namen[$i]['blatt']));
            $GUI->namen[$i]['flurstuecke'] = $ret[1];
            for($j = 0; $j < count($GUI->namen[$i]['flurstuecke']); $j++){
              $ret = $GUI->pgdatabase->getALBData($GUI->namen[$i]['flurstuecke'][$j], false, $layerset[0]['oid']);
              $GUI->namen[$i]['alb_data'][$j] = $ret[1];
            }
          }
        }

      }
      $GUI->namenWahl();
    } # ende Abfrage war erfolgreich
  };

	$GUI->flurstuecksAnzeigeByGrundbuecher = function() use ($GUI){
		include_once(PLUGINS.'alkis/model/kataster.php');
    $flurstueck=new flurstueck('',$GUI->pgdatabase);
    $gbarray = explode(', ', $GUI->formvars['selBlatt']);
    $Flurstuecke = $flurstueck->getFlurstByGrundbuecher($gbarray);
    if (count($Flurstuecke)==0) {
      $GUI->Fehlermeldung='<br>Es konnten keine Flurstücke gefunden werden, bitte ändern Sie die Anfrage!';
      $GUI->namenWahl();
    }
    else {
      # Anzeige der Flurstuecke
      $GUI->zoomToALKFlurst($Flurstuecke,10);
      $currenttime=date('Y-m-d H:i:s',time());
      $GUI->user->rolle->setConsumeActivity($currenttime,'getMap',$GUI->user->rolle->last_time_id);
      $GUI->drawMap();
      $GUI->saveMap('');
      $GUI->output();
    }
  };

	$GUI->flurstuecksSucheByGrundbuecher = function() use ($GUI){
		include_once(PLUGINS.'alkis/model/kataster.php');
    $flurstueck=new flurstueck('',$GUI->pgdatabase);
    $gbarray = explode(', ', $GUI->formvars['selBlatt']);
    $Flurstuecke = $flurstueck->getFlurstByGrundbuecher($gbarray);
    if(count($Flurstuecke)==0) {
        $GUI->Fehlermeldung='<br>Es konnten keine Flurstücke gefunden werden, bitte ändern Sie die Anfrage!';
        $GUI->namenWahl();
    }
    else {
      $GUI->flurstAnzeige($Flurstuecke);
      $GUI->output();
    }
  };

?>