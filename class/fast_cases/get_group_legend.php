<?

function in_subnet($ip,$net) {
	$ipparts=explode('.',$ip);
	$netparts=explode('.',$net);

	# Direkter Vergleich
	if ($ip==$net) {
		return 1;
	}

  # Test auf C-Netz
	if (trim($netparts[3],'0')=='' OR $netparts[3]=='*') {
		# C-Netzvergleich
	  if ($ipparts[0].'.'.$ipparts[1].'.'.$ipparts[2]==$netparts[0].'.'.$netparts[1].'.'.$netparts[2]) {
	  	return 1;
	  }
	}

  # Test auf B-Netz
	if ((trim($netparts[3],'0')=='' OR $netparts[3]=='*') AND (trim($netparts[2],'0')=='' OR $netparts[2]=='*')) {
		# B-Netzvergleich
	  if ($ipparts[0].'.'.$ipparts[1]==$netparts[0].'.'.$netparts[1]) {
	  	return 1;
	  }
	}

  # Test auf A-Netz
	if ((trim($netparts[3],'0')=='' OR $netparts[3]=='*') AND (trim($netparts[2],'0')=='' OR $netparts[2]=='*') AND (trim($netparts[1],'0')=='' OR $netparts[1]=='*')) {
		# A-Netzvergleich
	  if ($ipparts[0]==$netparts[0]) {
	  	return 1;
	  }
	}
	return 0;
}

function checkPasswordAge($passwordSettingTime,$allowedPassordAgeMonth) {
  $passwordSettingUnixTime=strtotime($passwordSettingTime); # Unix Zeit in Sekunden an dem das Passwort gesetzt wurde
  $allowedPasswordAgeDays=round($allowedPassordAgeMonth*30.5); # Zeitintervall, wie alt das Password sein darf in Tagen
  $passwordAgeDays=round((time()-$passwordSettingUnixTime)/60/60/24); # Zeitinterval zwischen setzen des Passwortes und aktueller Zeit in Tagen
  $allowedPasswordAgeRemainDays=$allowedPasswordAgeDays-$passwordAgeDays; # Zeitinterval wie lange das Passwort noch gilt in Tagen
	return $allowedPasswordAgeRemainDays; // Passwort ist abgelaufen wenn Wert < 1  
}

function replace_params($str, $params) {
	if (is_array($params)) {
		foreach($params AS $key => $value){
			$str = str_replace('$'.$key, $value, $str);
		}
	}
	return $str;
}

function html_umlaute($string){
	$string = str_replace('ä', '&auml;', $string);
	$string = str_replace('ü', '&uuml;', $string);
	$string = str_replace('ö', '&ouml;', $string);
	$string = str_replace('Ä', '&Auml;', $string);
	$string = str_replace('Ü', '&Uuml;', $string);
	$string = str_replace('Ö', '&Ouml;', $string);
	$string = str_replace('ß', '&szlig;', $string);
	$string = str_replace('ø', '&oslash;', $string);
	$string = str_replace('æ', '&aelig;', $string);
	return $string;
}

function compare_legendorder($a, $b){
	if($a['legendorder'] > $b['legendorder'])return 1;
	else return 0;
}


class GUI {

  var $layout;
  var $style;
  var $mime_type;
  var $menue;
  var $pdf;
  var $addressliste;
  var $debug;
  var $dbConn;
  var $flst;
  var $formvars;
  var $legende;
  var $map;
  var $mapDB;
  var $img;
  var $FormObject;
  var $StellenForm;
  var $Fehlermeldung;
  var $messages;
  var $Hinweis;
  var $Stelle;
  var $ALB;
  var $activeLayer;
  var $nImageWidth;
  var $nImageHeight;
  var $user;
  var $qlayerset;
  var $scaleUnitSwitchScale;
  var $map_scaledenom;
  var $map_factor;
  var $formatter;
  var $success;

  function GUI($main, $style, $mime_type) {
    # Debugdatei setzen
    global $debug;
    $this->debug=$debug;
    # Logdatei für Mysql setzen
    global $log_mysql;
    $this->log_mysql=$log_mysql;
    # Logdatei für PostgreSQL setzten
    global $log_postgres;
    $this->log_postgres=$log_postgres;
    # layout Templatedatei zur Anzeige der Daten
    if ($main!="") $this->main=$main;
    # Stylesheetdatei
    if (isset($style)) $this->style=$style;
    # mime_type html, pdf
    if (isset ($mime_type)) $this->mime_type=$mime_type;
		$this->scaleUnitSwitchScale = 239210;
		$this->trigger_functions = array();
  }

	function loadMultiLingualText($language) {
    #echo 'In der Rolle eingestellte Sprache: '.$GUI->user->rolle->language;
    $this->Stelle->language=$language;
    $this->Stelle->getName();
    include(LAYOUTPATH.'languages/'.$this->user->rolle->language.'.php');
  }

	function get_group_legend() {
    # Änderungen in den Gruppen werden gesetzt
    $this->formvars = $this->user->rolle->setGroupStatus($this->formvars);
    # Ein- oder Ausblenden der Klassen
    $this->user->rolle->setClassStatus($this->formvars);
    $this->loadMap('DataBase');
    echo $this->create_group_legend($this->formvars['group']);
  }

  function loadMap($loadMapSource) {
    $this->debug->write("<p>Funktion: loadMap('".$loadMapSource."','".$connStr."')",4);
    switch ($loadMapSource) {
      # lade Karte aus Post-Parametern
      case 'Post' : {
        if (MAPSERVERVERSION < 600) {
				  $map = ms_newMapObj(SHAPEPATH.'MapFiles/tk_niedersachsen.map');
				}
				else {
				  $map = new mapObj(SHAPEPATH.'MapFiles/tk_niedersachsen.map');
				}
				echo '<br>MapServer Version: '.ms_GetVersionInt();
				echo '<br>Details: '.ms_GetVersion();

        # Allgemeine Parameter
        #var_dump($this->formvars);
        $map->width = $this->formvars['post_width'];
        $map->set('height', $this->formvars['post_height']);
        $map->set('resolution',72);
        $map->set('units',MS_METERS);
        #$map->set('transparent', MS_OFF);
        #$map->set('interlace', MS_ON);
        $map->set('status', MS_ON);
        $map->set('name', MAPFILENAME);
        $map->imagecolor->setRGB(255,255,255);
        if($this->formvars['post_minx'] != ''){
          $map->setextent($this->formvars['post_minx'], $this->formvars['post_miny'], $this->formvars['post_maxx'], $this->formvars['post_maxy']);
        }
        else{
          $map->setextent($this->user->rolle->oGeorefExt->minx,$this->user->rolle->oGeorefExt->miny,$this->user->rolle->oGeorefExt->maxx,$this->user->rolle->oGeorefExt->maxy);
        }
        $map->setProjection('+init='.strtolower($this->formvars['post_epsg']),MS_TRUE);

        $map->setSymbolSet(SYMBOLSET);
        $map->setFontSet(FONTSET);
        $map->set('shapepath', SHAPEPATH);

        # Webobject
        $map->web->set('imagepath', IMAGEPATH);
        $map->web->set('imageurl', IMAGEURL);

        # OWS Metadaten
        $map->setMetaData('ows_title', 'WMS Ausdruck');
        $map->setMetaData('wms_extent',$this->formvars['post_minx'].''.$this->formvars['post_miny'].' '.$this->formvars['post_maxx'].' '.$this->formvars['post_maxy']);

        # Legendobject
        $map->legend->set('status', MS_ON);
        #$map->legend->set('transparent', MS_OFF);
        $map->legend->set('keysizex', '16');
        $map->legend->set('keysizey', '16');
        $map->legend->set('template', LAYOUTPATH.'legend_layer.htm');
        $map->legend->imagecolor -> setRGB(255,255,255);
        $map->legend->outlinecolor -> setRGB(-1,-1,-1);
        $map->legend->label->set('type', MS_TRUETYPE);
        $map->legend->label->set('font', 'arial');
        $map->legend->label->set('size', 12);
        $map->legend->label->color->setRGB(5,30,220);

        # layer
        if (is_array($this->formvars['layer'])) {
          $layerset=array_values($this->formvars['layer']);
        }
        else {
          $layerset=array();
        }
        for ($i=0; $i<count($layerset); $i++) {
				  if (MAPSERVERVERSION < 600) {
            $layer = ms_newLayerObj($map);
          }
					else {
					  $layer = new layerObj($map);
					}
					$layer->setMetaData('wms_name', $layerset[$i][name]);
          $layer->setMetaData('wms_server_version','1.1.1');
          $layer->setMetaData('wms_format','image/png');
          $layer->setMetaData('wms_extent',$this->formvars['post_minx'].' '.$this->formvars['post_miny'].' '.$this->formvars['post_maxx'].' '.$this->formvars['post_maxy']);
          $layer->setMetaData('ows_title', $layerset[$i][name]);
          if($layerset[$i][epsg_code] != ''){
            $layer->setMetaData('ows_srs', $layerset[$i][epsg_code]);
          }
          else{
            $layer->setMetaData('ows_srs', $this->formvars['post_epsg']);
          }
          $layer->setMetaData('wms_exceptions_format', 'application/vnd.ogc.se_inimage');
          $layer->setMetaData('real_layer_status', 1);
          $layer->setMetaData('off_requires',0);
          $layer->setMetaData('wms_connectiontimeout',60);
          $layer->setMetaData('wms_queryable',0);
          $layer->setMetaData('wms_group_title','WMS');
          $layer->set('type', 3);
          $layer->set('name', $layerset[$i][name]);
          $layer->set('status', 1);
          if($this->map_factor == ''){
            $this->map_factor=1;
          }
          if($layerset[$i]['maxscale'] > 0) {
            if(MAPSERVERVERSION > 500){
              $layer->set('maxscaledenom', $layerset[$i]['maxscale']/$this->map_factor*1.414);
            }
            else{
              $layer->set('maxscale', $layerset[$i]['maxscale']/$this->map_factor*1.414);
            }
          }
          if($layerset[$i]['minscale'] > 0) {
            if(MAPSERVERVERSION > 500){
              $layer->set('minscaledenom', $layerset[$i]['minscale']/$this->map_factor*1.414);
            }
            else{
              $layer->set('minscale', $layerset[$i]['minscale']/$this->map_factor*1.414);
            }
          }
          if($layerset[$i][epsg_code] != ''){
            $layer->setProjection('+init='.strtolower($layerset[$i][epsg_code])); # recommended
          }
          else{
            $layer->setProjection('+init='.strtolower($this->formvars['post_epsg']));
          }
          #$layer->set('connection',"http://www.kartenserver.niedersachsen.de/wmsconnector/com.esri.wms.Esrimap/Biotope?LAYERS=7&REQUEST=GetMap&TRANSPARENT=true&FORMAT=image/png&SERVICE=WMS&VERSION=1.1.1&STYLES=&EXCEPTIONS=application/vnd.ogc.se_xml&SRS=EPSG:31467");
          #echo '<br>Name: '.$layerset[$i][name];
          #echo '<br>Connection: '.$layerset[$i][connection];
          $layer->set('connection', $layerset[$i][connection]);
          if (MAPSERVERVERSION < 540) {
			      $layer->set('connectiontype', 7);
			    }
			    else {
			      $layer->setConnectionType(7);
			    }
          if($layerset[$i]['transparency'] != ''){
            if(MAPSERVERVERSION > 500){
              $layer->set('opacity',$layerset[$i]['transparency']);
            }
            else{
              $layer->set('transparency',$layerset[$i]['transparency']);
            }
          }
        } # end of Schleife layer
        $this->map=$map;
      } break;

      # lade Karte von einer Map-Datei
      case 'File' : {
        $debug->write("MapDatei $connStr laden",4);
				if (MAPSERVERVERSION < 600) {
          $this->map = ms_newMapObj(DEFAULTMAPFILE);
        }
				else {
				  $this->map = new mapObj(DEFAULTMAPFILE);
				}
			} break;

      # lade Karte von Datenbank
      case 'DataBase' : {
				if (MAPSERVERVERSION < 600) {
							$map = ms_newMapObj(DEFAULTMAPFILE);
						}
				else {
					$map = new mapObj(DEFAULTMAPFILE, SHAPEPATH);
				}
        $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);

        # Allgemeine Parameter
        $map->set('width',$this->user->rolle->nImageWidth);
        $map->set('height',$this->user->rolle->nImageHeight);
        $map->set('resolution',96);
        #$map->set('transparent', MS_OFF);
        #$map->set('interlace', MS_ON);
        $map->set('status', MS_ON);
        $map->set('name', MAPFILENAME);
        $map->set('debug', MS_ON);
        $map->imagecolor->setRGB(255,255,255);
        $map->maxsize = 4096;
        $map->setProjection('+init=epsg:'.$this->user->rolle->epsg_code,MS_TRUE);

				# setzen der Kartenausdehnung über die letzten Benutzereinstellungen
				if($this->user->rolle->oGeorefExt->minx==='') {
				  echo "Richten Sie mit phpMyAdmin in der kvwmap Datenbank eine Referenzkarte, eine Stelle, einen Benutzer und eine Rolle ein ";
				  echo "<br>(Tabellen referenzkarten, stelle, user, rolle) ";
				  echo "<br>oder wenden Sie sich an ihren Systemverwalter.";
				  exit;
				}
				else {
				  $map->setextent($this->user->rolle->oGeorefExt->minx,$this->user->rolle->oGeorefExt->miny,$this->user->rolle->oGeorefExt->maxx,$this->user->rolle->oGeorefExt->maxy);
        }

        # OWS Metadaten

        if($this->Stelle->ows_title != ''){
          $map->setMetaData("ows_title",$this->Stelle->ows_title);}
        else{
          $map->setMetaData("ows_title",OWS_TITLE);
        }
        if($this->Stelle->ows_abstract != ''){
          $map->setMetaData("ows_abstract",$this->Stelle->ows_abstract);}
        else{
          $map->setMetaData("ows_title",OWS_ABSTRACT);
        }
        if($this->Stelle->wms_accessconstraints != ''){
          $map->setMetaData("wms_accessconstraints",$this->Stelle->wms_accessconstraints);}
        else{
          $map->setMetaData("wms_accessconstraints",OWS_ACCESSCONSTRAINTS);
        }
        if($this->Stelle->ows_contactperson != ''){
          $map->setMetaData("ows_contactperson",$this->Stelle->ows_contactperson);}
        else{
          $map->setMetaData("ows_contactperson",OWS_CONTACTPERSON);
        }
        if($this->Stelle->ows_contactorganization != ''){
          $map->setMetaData("ows_contactorganization",$this->Stelle->ows_contactorganization);}
        else{
          $map->setMetaData("ows_contactorganization",OWS_CONTACTORGANIZATION);
        }
        if($this->Stelle->ows_contactelectronicmailaddress != ''){
          $map->setMetaData("ows_contactelectronicmailaddress",$this->Stelle->ows_contactelectronicmailaddress);}
        else{
          $map->setMetaData("ows_contactelectronicmailaddress",OWS_CONTACTELECTRONICMAILADDRESS);
        }
        if($this->Stelle->ows_contactposition != ''){
          $map->setMetaData("ows_contactposition",$this->Stelle->ows_contactposition);}
        else{
          $map->setMetaData("ows_contactposition",OWS_CONTACTPOSITION);
        }
        if($this->Stelle->ows_fees != ''){
          $map->setMetaData("ows_fees",$this->Stelle->ows_fees);}
        else{
          $map->setMetaData("ows_fees",OWS_FEES);
        }
        if($this->Stelle->ows_srs != ''){
          $map->setMetaData("ows_srs",$this->Stelle->ows_srs);}
        else{
          $map->setMetaData("ows_srs",OWS_SRS);
        }
        $ows_onlineresource = OWS_SERVICE_ONLINERESOURCE.'&Stelle_ID='.$this->Stelle->id;
        $map->setMetaData("ows_onlineresource",$ows_onlineresource);
        $bb=$this->Stelle->MaxGeorefExt;
        $map->setMetaData("wms_extent",$bb->minx.' '.$bb->miny.' '.$bb->maxx.' '.$bb->maxy);
				// enable service types
        $map->setMetaData("ows_enable_request", '*');
        ///------------------------------////

        $map->setSymbolSet(SYMBOLSET);
        $map->setFontSet(FONTSET);
        $map->set('shapepath', SHAPEPATH);

        # Umrechnen des Stellenextents kann hier raus, weil es schon in start.php gemacht wird

        # Webobject
        $map->web->set('imagepath', IMAGEPATH);
        $map->web->set('imageurl', IMAGEURL);
        $map->web->set('log', LOGPATH.'mapserver.log');
        $map->setMetaData('wms_feature_info_mime_type',  'text/html');
        //$map->web->set('ERRORFILE', LOGPATH.'mapserver_error.log');

        # Referenzkarte
				$this->ref=$mapDB->read_ReferenceMap();
				if(MAPSERVERVERSION < 600){
					$reference_map = ms_newMapObj(DEFAULTMAPFILE);
				}
				else {
					$reference_map = new mapObj(DEFAULTMAPFILE);
				}
				$reference_map->web->set('imagepath', IMAGEPATH);
				$reference_map->setProjection('+init=epsg:'.$this->ref['epsg_code'],MS_FALSE);
				#$reference_map->extent->setextent in drawreferencemap() ausgelagert, da der Extent sich geändert haben kann nach dem loadmap
				$reference_map->reference->extent->setextent(round($this->ref['xmin']),round($this->ref['ymin']),round($this->ref['xmax']),round($this->ref['ymax']));
        $reference_map->reference->set('image',REFERENCEMAPPATH.$this->ref['Dateiname']);
        $reference_map->reference->set('width',$this->ref['width']);
        $reference_map->reference->set('height',$this->ref['height']);
        $reference_map->reference->set('status','MS_ON');
				if (MAPSERVERVERSION < 600) {
					$extent=ms_newRectObj();
				}
				else {
				  $extent = new rectObj();
				}
        $reference_map->reference->color->setRGB(-1,-1,-1);
        $reference_map->reference->outlinecolor->setRGB(255,0,0);

        # Scalebarobject
        $map->scalebar->set('status', MS_ON);
        $map->scalebar->set('units', MS_METERS);
        $map->scalebar->set('intervals', 4);
        $map->scalebar->color->setRGB(0,0,0);
        $r = substr(BG_MENUETOP, 1, 2);
        $g = substr(BG_MENUETOP, 3, 2);
        $b = substr(BG_MENUETOP, 5, 2);
        $map->scalebar->imagecolor->setRGB(hexdec($r), hexdec($g), hexdec($b));
        $map->scalebar->outlinecolor->setRGB(0,0,0);
				$map->scalebar->label->type = 'truetype';
				$map->scalebar->label->font = 'SourceSansPro';
				$map->scalebar->label->size = 10.5;

        # Groups
        if($this->formvars['nurAufgeklappteLayer'] == ''){
	        $this->groupset=$mapDB->read_Groups();
        }

        # Layer
				$mapDB->nurAktiveLayer = $this->formvars['nurAktiveLayer'];
        $mapDB->nurAufgeklappteLayer=$this->formvars['nurAufgeklappteLayer'];
        $mapDB->nurFremdeLayer=$this->formvars['nurFremdeLayer'];
        if($this->class_load_level == ''){
          $this->class_load_level = 1;
        }
        $layerset = $mapDB->read_Layer($this->class_load_level, $this->Stelle->useLayerAliases, $this->list_subgroups($this->formvars['group']));     # class_load_level: 2 = für alle Layer die Klassen laden, 1 = nur für aktive Layer laden, 0 = keine Klassen laden
        $rollenlayer = $mapDB->read_RollenLayer();
        $layerset['list'] = array_merge($layerset['list'], $rollenlayer);
        $layerset['anzLayer'] = count($layerset['list']);
        unset($this->layer_ids_of_group);		# falls loadmap zweimal aufgerufen wird
        for($i=0; $i < $layerset['anzLayer']; $i++){
					$layerset['layers_of_group'][$layerset['list'][$i]['Gruppe']][] = $i;
					if($layerset['list'][$i]['requires'] == ''){
						$this->layer_ids_of_group[$layerset['list'][$i]['Gruppe']][] = $layerset['list'][$i]['Layer_ID'];				# die Layer-IDs in einer Gruppe
					}
					$this->layer_id_string .= $layerset['list'][$i]['Layer_ID'].'|';							# alle Layer-IDs hintereinander in einem String

					if($layerset['list'][$i]['requires'] != ''){
						$layerset['list'][$i]['aktivStatus'] = $layerset['layer_ids'][$layerset['list'][$i]['requires']]['aktivStatus'];
						$layerset['list'][$i]['showclasses'] = $layerset['layer_ids'][$layerset['list'][$i]['requires']]['showclasses'];
					}

					if($this->class_load_level == 2 OR ($this->class_load_level == 1 AND $layerset['list'][$i]['aktivStatus'] != 0)){      # nur wenn der Layer aktiv ist, sollen seine Parameter gesetzt werden
						$layer = ms_newLayerObj($map);
						$layer->setMetaData('wfs_request_method', 'GET');
						$layer->setMetaData('wms_name', $layerset['list'][$i]['wms_name']);
						$layer->setMetaData('wfs_typename', $layerset['list'][$i]['wms_name']);
						$layer->setMetaData('ows_title', $layerset['list'][$i]['Name']); # required
						$layer->setMetaData('wms_group_title',$layerset['list'][$i]['Gruppenname']);
						$layer->setMetaData('wms_queryable',$layerset['list'][$i]['queryable']);
						$layer->setMetaData('wms_format',$layerset['list'][$i]['wms_format']);
						$layer->setMetaData('ows_server_version',$layerset['list'][$i]['wms_server_version']);
						$layer->setMetaData('ows_version',$layerset['list'][$i]['wms_server_version']);
						if($layerset['list'][$i]['ows_srs'] == '')$layerset['list'][$i]['ows_srs'] = 'EPSG:'.$layerset['list'][$i]['epsg_code'];
						$layer->setMetaData('ows_srs',$layerset['list'][$i]['ows_srs']);
						$layer->setMetaData('wms_connectiontimeout',$layerset['list'][$i]['wms_connectiontimeout']);
						$layer->setMetaData('ows_auth_username', $layerset['list'][$i]['wms_auth_username']);
						$layer->setMetaData('ows_auth_password', $layerset['list'][$i]['wms_auth_password']);
						$layer->setMetaData('ows_auth_type', 'basic');
						$layer->setMetaData('wms_exceptions_format', 'application/vnd.ogc.se_xml');

						$layer->set('dump', 0);
						$layer->set('type',$layerset['list'][$i]['Datentyp']);
						$layer->set('group',$layerset['list'][$i]['Gruppenname']);

						$layer->set('name', $layerset['list'][$i]['alias']);

						if($layerset['list'][$i]['status'] != ''){
							$layerset['list'][$i]['aktivStatus'] = 0;
						}

						//---- wenn die Layer einer eingeklappten Gruppe nicht in der Karte //
						//---- dargestellt werden sollen, muß hier bei aktivStatus != 1 //
						//---- der layer_status auf 0 gesetzt werden//
						if($layerset['list'][$i]['aktivStatus'] == 0){
						$layer->set('status', 0);
						}
						else{
						$layer->set('status', 1);
						}
						$layer->set('debug',MS_ON);

						# fremde Layer werden auf Verbindung getestet
						if($layerset['list'][$i]['aktivStatus'] != 0 AND $layerset['list'][$i]['connectiontype'] == 6 AND strpos($layerset['list'][$i]['connection'], 'host') !== false AND strpos($layerset['list'][$i]['connection'], 'host=localhost') === false AND strpos($layerset['list'][$i]['connection'], 'host=pgsql') === false){
						$connection = explode(' ', trim($layerset['list'][$i]['connection']));
								for($j = 0; $j < count($connection); $j++){
								if($connection[$j] != ''){
									$value = explode('=', $connection[$j]);
									if(strtolower($value[0]) == 'host'){
									$host = $value[1];
									}
									if(strtolower($value[0]) == 'port'){
									$port = $value[1];
									}
								}
								}
								if($port == '')$port = '5432';
						$fp = @fsockopen($host, $port, $errno, $errstr, 5);
						if(!$fp){			# keine Verbindung --> Layer ausschalten
							$layer->set('status', 0);
							$layer->setMetaData('queryStatus', 0);
									$this->Fehlermeldung = $errstr.' für Layer: '.$layerset['list'][$i]['Name'].'<br>';
						}
						}

						if($layerset['list'][$i]['aktivStatus'] != 0){
							$collapsed = false;
							$group = $this->groupset[$layerset['list'][$i]['Gruppe']];				# die Gruppe des Layers
							if($group['status'] == 0){
								$this->group_has_active_layers[$layerset['list'][$i]['Gruppe']] = 1;  	# die zugeklappte Gruppe hat aktive Layer
								$collapsed = true;
							}
							while($group['obergruppe'] != ''){
								$group = $this->groupset[$group['obergruppe']];
								if($collapsed OR $group['status'] == 0){
									$this->group_has_active_layers[$group['id']] = 1;  	# auch alle Obergruppen durchlaufen
									$collapsed = true;
								}
							}
						}

						if(!$this->noMinMaxScaling AND $layerset['list'][$i]['minscale']>=0) {
							if($this->map_factor != ''){
								if(MAPSERVERVERSION > 500){
									$layer->set('minscaledenom', $layerset['list'][$i]['minscale']/$this->map_factor*1.414);
								}
								else{
									$layer->set('minscale', $layerset['list'][$i]['minscale']/$this->map_factor*1.414);
								}
							}
							else{
								if(MAPSERVERVERSION > 500){
									$layer->set('minscaledenom', $layerset['list'][$i]['minscale']);
								}
								else{
									$layer->set('minscale', $layerset['list'][$i]['minscale']);
								}
							}
						}
						if(!$this->noMinMaxScaling AND $layerset['list'][$i]['maxscale']>0) {
							if($this->map_factor != ''){
								if(MAPSERVERVERSION > 500){
									$layer->set('maxscaledenom', $layerset['list'][$i]['maxscale']/$this->map_factor*1.414);
								}
								else{
									$layer->set('maxscale', $layerset['list'][$i]['maxscale']/$this->map_factor*1.414);
								}
							}
							else{
								if(MAPSERVERVERSION > 500){
									$layer->set('maxscaledenom', $layerset['list'][$i]['maxscale']);
								}
								else{
									$layer->set('maxscale', $layerset['list'][$i]['maxscale']);
								}
							}
						}
            $layer->setProjection('+init=epsg:'.$layerset['list'][$i]['epsg_code']); # recommended
            if ($layerset['list'][$i]['connection']!='') {
              if($this->map_factor != '' AND $layerset['list'][$i]['connectiontype'] == 7){		# WMS-Layer
              	if($layerset['list'][$i]['printconnection']!=''){
              		$layerset['list'][$i]['connection'] = $layerset['list'][$i]['printconnection']; 		# wenn es eine Druck-Connection gibt, wird diese verwendet
              	}
              	else{
                	//$layerset['list'][$i]['connection'] .= '&mapfactor='.$this->map_factor;			# bei WMS-Layern wird der map_factor durchgeschleift (für die eigenen WMS) erstmal rausgenommen, weil einige WMS-Server der zusätzliche Parameter mapfactor stört
              	}
              }
              if($layerset['list'][$i]['connectiontype'] == 6)$layerset['list'][$i]['connection'] .= " options='-c client_encoding=".MYSQL_CHARSET."'";		# z.B. für Klassen mit Umlauten
              $layer->set('connection', $layerset['list'][$i]['connection']);
            }
            if ($layerset['list'][$i]['connectiontype']>0) {
              if (MAPSERVERVERSION >= 540) {
                $layer->setConnectionType($layerset['list'][$i]['connectiontype']);
              }
              else {
                $layer->set('connectiontype',$layerset['list'][$i]['connectiontype']);
              }
            }

						if($layerset['list'][$i]['connectiontype'] == 6)$layerset['list'][$i]['processing'] = 'CLOSE_CONNECTION=DEFER;'.$layerset['list'][$i]['processing'];		# DB-Connection erst am Ende schliessen und nicht für jeden Layer neu aufmachen
            if ($layerset['list'][$i]['processing'] != "") {
              $processings = explode(";",$layerset['list'][$i]['processing']);
              foreach ($processings as $processing) {
                $layer->setProcessing($processing);
              }
            }
						if ($layerset['list'][$i]['postlabelcache'] != 0) {
							$layer->set('postlabelcache',$layerset['list'][$i]['postlabelcache']);
						}

						if($layerset['list'][$i]['Datentyp'] == MS_LAYER_POINT AND $layerset['list'][$i]['cluster_maxdistance'] != ''){
							$layer->cluster->maxdistance = $layerset['list'][$i]['cluster_maxdistance'];
							$layer->cluster->region = 'ellipse';
						}

            if ($layerset['list'][$i]['Datentyp']=='3') {
              if($layerset['list'][$i]['transparency'] != ''){
                if(MAPSERVERVERSION > 500){
                  $layer->set('opacity',$layerset['list'][$i]['transparency']);
                }
                else{
                  $layer->set('transparency',$layerset['list'][$i]['transparency']);
                }
              }
              if ($layerset['list'][$i]['tileindex']!='') {
                $layer->set('tileindex',SHAPEPATH.$layerset['list'][$i]['tileindex']);
              }
              else {
                $layer->set('data', $layerset['list'][$i]['Data']);
              }
              $layer->set('tileitem',$layerset['list'][$i]['tileitem']);
              if ($layerset['list'][$i]['offsite']!='') {
                $RGB=explode(' ',$layerset['list'][$i]['offsite']);
                $layer->offsite->setRGB($RGB[0],$RGB[1],$RGB[2]);
              }
            }
            else {
              # Vektorlayer
              if($layerset['list'][$i]['Data'] != ''){
								$layerset['list'][$i]['Data'] = str_replace('$hist_timestamp', rolle::$hist_timestamp, $layerset['list'][$i]['Data']);
								$layerset['list'][$i]['Data'] = str_replace('$language', $this->user->rolle->language, $layerset['list'][$i]['Data']);
								$layerset['list'][$i]['Data'] = replace_params($layerset['list'][$i]['Data'], rolle::$layer_params);
                $layer->set('data', $layerset['list'][$i]['Data']);
              }

              # Setzen der Templatedateien für die Sachdatenanzeige inclt. Footer und Header.
              # Template (Body der Anzeige)
              if ($layerset['list'][$i]['template']!='') {
                $layer->set('template',$layerset['list'][$i]['template']);
              }
              else {
                $layer->set('template',DEFAULTTEMPLATE);
              }
              # Header (Kopfdatei)
              if ($layerset['list'][$i]['header']!='') {
                $layer->set('header',$layerset['list'][$i]['header']);
              }
              # Footer (Fusszeile)
              if ($layerset['list'][$i]['footer']!='') {
                $layer->set('footer',$layerset['list'][$i]['footer']);
              }
              # Setzen der Spalte nach der der Layer klassifiziert werden soll
              if ($layerset['list'][$i]['classitem']!='') {
                $layer->set('classitem', replace_params($layerset['list'][$i]['classitem'], rolle::$layer_params));
              }
              else {
                #$layer->set('classitem','id');
              }
              # Setzen des Filters
              if($layerset['list'][$i]['Filter'] != ''){
              	$layerset['list'][$i]['Filter'] = str_replace('$userid', $this->user->id, $layerset['list'][$i]['Filter']);
               if (substr($layerset['list'][$i]['Filter'],0,1)=='(') {
                 $expr=$layerset['list'][$i]['Filter'];
               }
               else {
                 $expr=buildExpressionString($layerset['list'][$i]['Filter']);
               }
               $layer->setFilter($expr);
              }
              # Layerweite Labelangaben
              if (MAPSERVERVERSION < 500 AND $layerset['list'][$i]['labelangleitem']!='') {
                $layer->set('labelangleitem',$layerset['list'][$i]['labelangleitem']);
              }
              if ($layerset['list'][$i]['labelitem']!='') {
                $layer->set('labelitem',$layerset['list'][$i]['labelitem']);
              }
              if ($layerset['list'][$i]['labelmaxscale']!='') {
                if(MAPSERVERVERSION > 500){
                  $layer->set('labelmaxscaledenom',$layerset['list'][$i]['labelmaxscale']);
                }
                else{
                  $layer->set('labelmaxscale',$layerset['list'][$i]['labelmaxscale']);
                }
              }
              if ($layerset['list'][$i]['labelminscale']!='') {
                if(MAPSERVERVERSION > 500){
                  $layer->set('labelminscaledenom',$layerset['list'][$i]['labelminscale']);
                }
                else{
                  $layer->set('labelminscale',$layerset['list'][$i]['labelminscale']);
                }
              }
              if ($layerset['list'][$i]['labelrequires']!='') {
                $layer->set('labelrequires',$layerset['list'][$i]['labelrequires']);
              }
              if ($layerset['list'][$i]['tolerance']!='3') {
                $layer->set('tolerance',$layerset['list'][$i]['tolerance']);
              }
              if ($layerset['list'][$i]['toleranceunits']!='pixels') {
                $layer->set('toleranceunits',$layerset['list'][$i]['toleranceunits']);
              }
              if ($layerset['list'][$i]['transparency']!=''){
                if(MAPSERVERVERSION > 500){
                  if ($layerset['list'][$i]['transparency']==-1) {
                      $layer->set('opacity',MS_GD_ALPHA);
                  }
                  else {
                      $layer->set('opacity',$layerset['list'][$i]['transparency']);
                  }
                }
                else {
                  if ($layerset['list'][$i]['transparency']==-1) {
                      $layer->set('transparency',MS_GD_ALPHA);
                  }
                  else {
                      $layer->set('transparency',$layerset['list'][$i]['transparency']);
                  }
                }
              }
              if ($layerset['list'][$i]['symbolscale']!='') {
                if($this->map_factor != ''){
                  if(MAPSERVERVERSION > 500){
                    $layer->set('symbolscaledenom',$layerset['list'][$i]['symbolscale']/$this->map_factor*1.414);
                  }
                  else{
                    $layer->set('symbolscale',$layerset['list'][$i]['symbolscale']/$this->map_factor*1.414);
                  }
                }
                else{
                  if(MAPSERVERVERSION > 500){
                    $layer->set('symbolscaledenom',$layerset['list'][$i]['symbolscale']);
                  }
                  else{
                    $layer->set('symbolscale',$layerset['list'][$i]['symbolscale']);
                  }
                }
              }
            } # ende of Vektorlayer
            # Klassen
            $classset=$layerset['list'][$i]['Class'];
            $this->loadclasses($layer, $layerset['list'][$i], $classset, $map);
          } # Ende Layer ist aktiv
        } # end of Schleife layer

				$this->layerset = $layerset;
        $this->map=$map;
				$this->reference_map = $reference_map;
				if (MAPSERVERVERSION >= 600 ) {
					$this->map_scaledenom = $map->scaledenom;
				}
				else {
					$this->map_scaledenom = $map->scale;
				}
        $this->mapDB=$mapDB;
      } break; # end of lade Karte von Datenbank
    } # end of switch loadMapSource
    return 1;
  }

	function list_subgroups($groupid){
		if($groupid != ''){
			$group = $this->groupset[$groupid];
			if($group['untergruppen'] != ''){
				foreach($group['untergruppen'] as $untergruppe){
					$subgroups .= ', '.$this->list_subgroups($untergruppe);
				}
				return $groupid.$subgroups;
			}
			else return $groupid;
		}
	}

  function loadclasses($layer, $layerset, $classset, $map){
    $anzClass=count($classset);
    for ($j=0;$j<$anzClass;$j++) {
      $klasse = ms_newClassObj($layer);
      if ($classset[$j]['Name']!='') {
        $klasse -> set('name',$classset[$j]['Name']);
      }
      if($classset[$j]['Status']=='1'){
      	$klasse->set('status', MS_ON);
      }
      else{
      	$klasse->set('status', MS_OFF);
      }
      $klasse -> set('template', $layerset['template']);
      $klasse -> setexpression($classset[$j]['Expression']);
      if ($classset[$j]['text']!='') {
        $klasse -> settext($classset[$j]['text']);
      }
      if ($classset[$j]['legendgraphic'] != '') {
				$imagename = WWWROOT.APPLVERSION.GRAPHICSPATH . 'custom/' . $classset[$j]['legendgraphic'];
				$klasse->set('keyimage', $imagename);
			}
      for ($k=0;$k<count($classset[$j]['Style']);$k++) {
        $dbStyle=$classset[$j]['Style'][$k];
				if (MAPSERVERVERSION < 600) {
          $style = ms_newStyleObj($klasse);
        }
				else {
				  $style = new styleObj($klasse);
				}
				if($dbStyle['geomtransform'] != ''){
					$style->updateFromString("STYLE GEOMTRANSFORM '".$dbStyle['geomtransform']."' END"); 
				}
				if($dbStyle['minscale'] != ''){
					$style->set('minscaledenom', $dbStyle['minscale']);
				}
				if($dbStyle['maxscale'] != ''){
					$style->set('maxscaledenom', $dbStyle['maxscale']);
				}
				if ($dbStyle['symbolname']!='') {
          $style->set('symbolname',$dbStyle['symbolname']);
        }
        if ($dbStyle['symbol']>0) {
          $style->set('symbol',$dbStyle['symbol']);
        }
        if (MAPSERVERVERSION >= 620) {
					if($dbStyle['geomtransform'] != '') {
						$style->setGeomTransform($dbStyle['geomtransform']);
					}
          if ($dbStyle['pattern']!='') {
            $style->setPattern(explode(' ',$dbStyle['pattern']));
            $style->linecap = 'butt';
          }
					if($dbStyle['gap'] != '') {
	          $style->set('gap', $dbStyle['gap']);
	        }
					if($dbStyle['initialgap'] != '') {
            $style->set('initialgap', $dbStyle['initialgap']);
          }
					if($dbStyle['linecap'] != '') {
	          $style->set('linecap', constant(MS_CJC_.strtoupper($dbStyle['linecap'])));
	        }
					if($dbStyle['linejoin'] != '') {
	          $style->set('linejoin', constant(MS_CJC_.strtoupper($dbStyle['linejoin'])));
	        }
					if($dbStyle['linejoinmaxsize'] != '') {
	          $style->set('linejoinmaxsize', $dbStyle['linejoinmaxsize']);
	        }
					if($dbStyle['polaroffset'] != '') {
	          $style->updateFromString("STYLE POLAROFFSET ".$dbStyle['polaroffset']." END"); 
	        }
        }

        if($this->map_factor != ''){
          if (MAPSERVERVERSION >= 620) {
            $pattern = $style->getpatternarray();
            if($pattern){
					    foreach($pattern as &$pat){
					      $pat = $pat * $this->map_factor;
					    }
					    $style->setPattern($pattern);
				    }
          }
          else {
            if($style->symbol > 0){
              $symbol = $map->getSymbolObjectById($style->symbol);
              $pattern = $symbol->getpatternarray();
              if(is_array($pattern) AND $symbol->inmapfile != 1){
                foreach($pattern as &$pat){
                  $pat = $pat * $this->map_factor;
                }
                $symbol->setpattern($pattern);
                $symbol->set('inmapfile', 1);
              }
            }
          }
        }
				if ($layerset['Datentyp'] == 8) {
					# Skalierung der Stylegröße when Type Chart
					$style->setbinding(MS_STYLE_BINDING_SIZE, $dbStyle['size']);
				}
				else {
					if($this->map_factor != '') {
						$style->set('size', $dbStyle['size']*$this->map_factor/1.414);
					}
					else{
						$style->set('size', $dbStyle['size']);
					}
				}

        if ($dbStyle['minsize']!='') {
          if($this->map_factor != ''){
            $style -> set('minsize',$dbStyle['minsize']*$this->map_factor/1.414);
          }
          else{
            $style -> set('minsize',$dbStyle['minsize']);
          }
        }

        if ($dbStyle['maxsize']!='') {
          if($this->map_factor != ''){
            $style -> set('maxsize',$dbStyle['maxsize']*$this->map_factor/1.414);
          }
          else{
            $style -> set('maxsize',$dbStyle['maxsize']);
          }
        }

				if($dbStyle['angle'] != '') {
					$style->updateFromString("STYLE ANGLE ".$dbStyle['angle']." END"); 		# wegen AUTO
				}
        if ($dbStyle['angleitem']!=''){
          if(MAPSERVERVERSION < 500){
            $style->set('angleitem',$dbStyle['angleitem']);
          }
          else{
            $style->setbinding(MS_STYLE_BINDING_ANGLE, $dbStyle['angleitem']);
          }
        }
        if ($dbStyle['width']!='') {
          if ($dbStyle['antialias']!='') {
            $style -> set('antialias',$dbStyle['antialias']);
          }
          if($this->map_factor != ''){
            $style -> set('width',$dbStyle['width']*$this->map_factor/1.414);
          }
          else{
            $style->set('width',$dbStyle['width']);
          }
        }

        if ($dbStyle['minwidth']!='') {
          if($this->map_factor != ''){
            $style->set('minwidth',$dbStyle['minwidth']*$this->map_factor/1.414);
          }
          else{
            $style->set('minwidth',$dbStyle['minwidth']);
          }
        }

        if ($dbStyle['maxwidth']!='') {
          if($this->map_factor != ''){
            $style->set('maxwidth',$dbStyle['maxwidth']*$this->map_factor/1.414);
          }
          else{
            $style->set('maxwidth',$dbStyle['maxwidth']);
          }
        }

        if (MAPSERVERVERSION < 500 AND $dbStyle['sizeitem']!='') {
          $style->set('sizeitem', $dbStyle['sizeitem']);
        }
        if ($dbStyle['color']!='') {
          $RGB=explode(" ",$dbStyle['color']);
          if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
          if(is_numeric($RGB[0]))$style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
					else $style->updateFromString("STYLE COLOR [".$dbStyle['color']."] END");
        }
				if($dbStyle['opacity'] != '') {		# muss nach color gesetzt werden
					$style->set('opacity', $dbStyle['opacity']);
				}
        if ($dbStyle['outlinecolor']!='') {
          $RGB=explode(" ",$dbStyle['outlinecolor']);
        	if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
          $style->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
        }
        if ($dbStyle['backgroundcolor']!='') {
          $RGB=explode(" ",$dbStyle['backgroundcolor']);
        	if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
          $style->backgroundcolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
        }
				if($dbStyle['colorrange'] != '') {
					$style->updateFromString("STYLE COLORRANGE ".$dbStyle['colorrange']." END");
				}
				if($dbStyle['datarange'] != '') {
					$style->updateFromString("STYLE DATARANGE ".$dbStyle['datarange']." END");
				}
				if($dbStyle['rangeitem'] != '') {
					$style->updateFromString("STYLE RANGEITEM ".$dbStyle['rangeitem']." END");
				}
        if ($dbStyle['offsetx']!='') {
          $style->set('offsetx', $dbStyle['offsetx']);
        }
        if ($dbStyle['offsety']!='') {
          $style->set('offsety', $dbStyle['offsety']);
        }
      } # Ende Schleife für mehrere Styles

      # setzen eines oder mehrerer Labels
      # Änderung am 12.07.2005 Korduan
      for ($k=0;$k<count($classset[$j]['Label']);$k++) {
        $dbLabel=$classset[$j]['Label'][$k];
        if (MAPSERVERVERSION < 600) {
          $klasse->label->set('type',$dbLabel['type']);
          $klasse->label->set('font',$dbLabel['font']);
          $RGB=explode(" ",$dbLabel['color']);
          if ($RGB[0]=='') { $RGB[0]=0; }
          if ($RGB[1]=='') { $RGB[1]=0; }
          if ($RGB[2]=='') { $RGB[2]=0; }
          $klasse->label->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
          $RGB=explode(" ",$dbLabel['outlinecolor']);
          $klasse->label->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
          if ($dbLabel['shadowcolor']!='') {
            $RGB=explode(" ",$dbLabel['shadowcolor']);
            $klasse->label->shadowcolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
            $klasse->label->set('shadowsizex',$dbLabel['shadowsizex']);
            $klasse->label->set('shadowsizey',$dbLabel['shadowsizey']);
          }
          if ($dbLabel['backgroundcolor']!='') {
            $RGB=explode(" ",$dbLabel['backgroundcolor']);
            $klasse->label->backgroundcolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
          }
          if ($dbLabel['backgroundshadowcolor']!='') {
            $RGB=explode(" ",$dbLabel['backgroundshadowcolor']);
            $klasse->label->backgroundshadowcolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
            $klasse->label->set('backgroundshadowsizex',$dbLabel['backgroundshadowsizex']);
            $klasse->label->set('backgroundshadowsizey',$dbLabel['backgroundshadowsizey']);
          }
          $klasse->label->set('angle',$dbLabel['angle']);
          if(MAPSERVERVERSION > 500 AND $layerset['labelangleitem']!=''){
            $klasse->label->setbinding(MS_LABEL_BINDING_ANGLE, $layerset['labelangleitem']);
          }
        	if($dbLabel['autoangle']==1) {
            if(MAPSERVERVERSION >= 600){
	          	$klasse->label->set('anglemode', MS_AUTO);
	          }
	          else{
	          	$klasse->label->set('autoangle',$dbLabel['autoangle']);
            }
          }
          if ($dbLabel['buffer']!='') {
            $klasse->label->set('buffer',$dbLabel['buffer']);
          }
					$klasse->label->set('maxlength',$dbLabel['maxlength']);
          $klasse->label->set('wrap',$dbLabel['wrap']);
          $klasse->label->set('force',$dbLabel['the_force']);
          $klasse->label->set('partials',$dbLabel['partials']);
          $klasse->label->set('size',$dbLabel['size']);
          $klasse->label->set('minsize',$dbLabel['minsize']);
          $klasse->label->set('maxsize',$dbLabel['maxsize']);
          # Skalierung der Labelschriftgröße, wenn map_factor gesetzt
          if($this->map_factor != ''){
            $klasse->label->set('minsize',$dbLabel['minsize']*$this->map_factor/1.414);
            $klasse->label->set('maxsize',$dbLabel['size']*$this->map_factor/1.414);
            $klasse->label->set('size',$dbLabel['size']*$this->map_factor/1.414);
          }
          if ($dbLabel['position']!='') {
            switch ($dbLabel['position']){
              case '0' :{
                $klasse->label->set('position', MS_UL);
              }break;
              case '1' :{
                $klasse->label->set('position', MS_LR);
              }break;
              case '2' :{
                $klasse->label->set('position', MS_UR);
              }break;
              case '3' :{
                $klasse->label->set('position', MS_LL);
              }break;
              case '4' :{
                $klasse->label->set('position', MS_CR);
              }break;
              case '5' :{
                $klasse->label->set('position', MS_CL);
              }break;
              case '6' :{
                $klasse->label->set('position', MS_UC);
              }break;
              case '7' :{
                $klasse->label->set('position', MS_LC);
              }break;
              case '8' :{
                $klasse->label->set('position', MS_CC);
              }break;
              case '9' :{
                $klasse->label->set('position', MS_AUTO);
              }break;
            }
          }
          if ($dbLabel['offsetx']!='') {
            $klasse->label->set('offsetx',$dbLabel['offsetx']);
          }
          if ($dbLabel['offsety']!='') {
            $klasse->label->set('offsety',$dbLabel['offsety']);
          }
        } # ende mapserver < 600
        else {
          $label = new labelObj();
          $label->type = $dbLabel['type'];
          $label->font = $dbLabel['font'];
          $RGB=explode(" ",$dbLabel['color']);
          if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
          $label->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
          if($dbLabel['outlinecolor'] != ''){
						$RGB=explode(" ",$dbLabel['outlinecolor']);
						$label->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
					}
          if ($dbLabel['shadowcolor']!='') {
            $RGB=explode(" ",$dbLabel['shadowcolor']);
            $label->shadowcolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
            $label->shadowsizex = $dbLabel['shadowsizex'];
            $label->shadowsizey = $dbLabel['shadowsizey'];
          }

          if($dbLabel['backgroundshadowcolor']!='') {
            $RGB=explode(" ",$dbLabel['backgroundshadowcolor']);
            $style = new styleObj($label);
						$style->setGeomTransform('labelpoly');
            $style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
            $style->set('offsetx', $dbLabel['backgroundshadowsizex']);
						$style->set('offsety', $dbLabel['backgroundshadowsizey']);
						if ($dbLabel['buffer']!='') {
							$style->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
							$style->set('width', $dbLabel['buffer']);
						}
          }
					if ($dbLabel['backgroundcolor']!='') {
            $RGB=explode(" ",$dbLabel['backgroundcolor']);
						$style = new styleObj($label);
						$style->setGeomTransform('labelpoly');
            $style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
						if ($dbLabel['buffer']!='') {
							$style->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
							$style->set('width', $dbLabel['buffer']);
						}
          }

          $label->angle = $dbLabel['angle'];
          if($layerset['labelangleitem']!=''){
            $label->setBinding(MS_LABEL_BINDING_ANGLE, $layerset['labelangleitem']);
          }
        	if($dbLabel['autoangle']==1) {
            if(MAPSERVERVERSION >= 600){
            	$label->set('anglemode', MS_AUTO);
            }
            else{
            	$label->autoangle = $dbLabel['autoangle'];
            }
          }
          if ($dbLabel['buffer']!='') {
            $label->buffer = $dbLabel['buffer'];
          }
					$label->set('maxlength',$dbLabel['maxlength']);
          $label->wrap = $dbLabel['wrap'];
          $label->force = $dbLabel['the_force'];
          $label->partials = $dbLabel['partials'];
          $label->size = $dbLabel['size'];
          $label->minsize = $dbLabel['minsize'];
          $label->maxsize = $dbLabel['maxsize'];
          # Skalierung der Labelschriftgröße, wenn map_factor gesetzt
          if($this->map_factor != ''){
            $label->minsize = $dbLabel['minsize']*$this->map_factor/1.414;
            $label->maxsize = $dbLabel['size']*$this->map_factor/1.414;
            $label->size = $dbLabel['size']*$this->map_factor/1.414;
          }
          if ($dbLabel['position']!='') {
            switch ($dbLabel['position']){
              case '0' :{
                $label->set('position', MS_UL);
              }break;
              case '1' :{
                $label->set('position', MS_LR);
              }break;
              case '2' :{
                $label->set('position', MS_UR);
              }break;
              case '3' :{
                $label->set('position', MS_LL);
              }break;
              case '4' :{
                $label->set('position', MS_CR);
              }break;
              case '5' :{
                $label->set('position', MS_CL);
              }break;
              case '6' :{
                $label->set('position', MS_UC);
              }break;
              case '7' :{
                $label->set('position', MS_LC);
              }break;
              case '8' :{
                $label->set('position', MS_CC);
              }break;
              case '9' :{
                $label->set('position', MS_AUTO);
              }break;
            }
          }
          if ($dbLabel['offsetx']!='') {
            $label->offsetx = $dbLabel['offsetx'];
          }
          if ($dbLabel['offsety']!='') {
            $label->offsety = $dbLabel['offsety'];
          }
          $klasse->addLabel($label);
        } # ende mapserver >=600
      } # ende Schleife für mehrere Label
    } # end of Schleife Class
  }

	function create_group_legend($group_id){
		$layerlist = $this->layerset['list'];
		if($this->groupset[$group_id]['untergruppen'] == NULL AND $this->layerset['layers_of_group'][$group_id] == NULL)return;			# wenns keine Layer oder Untergruppen gibt, nix machen
    $groupname = $this->groupset[$group_id]['Gruppenname'];
	  $groupstatus = $this->groupset[$group_id]['status'];
    $legend .=  '
	  <div id="groupdiv_'.$group_id.'" style="width:100%">
      <table cellspacing="0" cellpadding="0" border="0" style="width:100%">
				<tr>
					<td>
						<input id="group_' . $group_id . '" name="group_' . $group_id . '" type="hidden" value="' . $groupstatus . '">
						<a href="javascript:getlegend(\'' . $group_id . '\', \'\', document.GUI.nurFremdeLayer.value)">
							<img border="0" id="groupimg_' . $group_id . '" src="graphics/' . ($groupstatus == 1 ? 'minus' : 'plus') . '.gif">&nbsp;
						</a>
						<span class="legend_group' . ($this->group_has_active_layers[$group_id] != '' ? '_active_layers' : '') . '">
							<!--a
								href="javascript:getGroupOptions(' . $group_id . ')" 
								onmouseover="$(\'#test_' . $group_id . '\').show()"
								onmouseout="$(\'#test_' . $group_id . '\').hide()"
							>' . html_umlaute($groupname) . '
								<i id="test_' . $group_id . '" class="fa fa-bars" style="display: none;"></i>
							</a//-->' .
							html_umlaute($groupname) . '
							<div style="position:static;" id="group_options_' . $group_id . '"></div>
						</span>
					</td>
				</tr>
				<tr>
					<td>
						<div id="layergroupdiv_'.$group_id.'" style="width:100%"><table cellspacing="0" cellpadding="0">';
		$layercount = count($this->layerset['layers_of_group'][$group_id]);
    if($groupstatus == 1){		# Gruppe aufgeklappt
			for($u = 0; $u < count($this->groupset[$group_id]['untergruppen']); $u++){			# die Untergruppen rekursiv durchlaufen
				$legend .= '<tr><td colspan="3"><table cellspacing="0" cellpadding="0" style="width:100%"><tr><td><img src="'.GRAPHICSPATH.'leer.gif" width="13" height="1" border="0"></td><td style="width: 100%">';
				$legend .= $this->create_group_legend($this->groupset[$group_id]['untergruppen'][$u]);
				$legend .= '</td></tr></table></td></tr>';
			}
			if($layercount > 0){		# Layer vorhanden
				if($this->layerset['list'][$this->layerset['layers_of_group'][$group_id][0]]['legendorder'] != ''){		# erster Layer hat eine Legendenreihenfolge -> sortieren
					usort($this->layerset['layers_of_group'][$group_id], function($a, $b) use ($layerlist) {
						return $layerlist[$a]['legendorder'] - $layerlist[$b]['legendorder'];
					});
				}
				else $this->layerset['layers_of_group'][$group_id] = array_reverse($this->layerset['layers_of_group'][$group_id]);		# umgedrehte Zeichenreihenfolge verwenden
				if(!$this->formvars['nurFremdeLayer']){
					$legend .=  '<tr>
												<td align="center">
													<input name="layers_of_group_'.$group_id.'" type="hidden" value="'.implode(',', $this->layer_ids_of_group[$group_id]).'">';
					if(!$this->user->rolle->singlequery) {
						$legend .=  '<a href="javascript:selectgroupquery(document.GUI.layers_of_group_'.$group_id.', '.$this->user->rolle->instant_reload.')"><img border="0" src="graphics/pfeil.gif" title="'.$this->strActivateAllQueries.'"></a>';
					}
					$legend .=		'</td>
												<td align="center">
													<a href="javascript:selectgroupthema(document.GUI.layers_of_group_'.$group_id.', '.$this->user->rolle->instant_reload.')"><img border="0" src="graphics/pfeil.gif" title="'.$this->strActivateAllLayers.'"></a>
												</td>
												<td>
													<span class="legend_layer">'.$this->all.'</span>
												</td>
											</tr>';
				}
				for($j = 0; $j < $layercount; $j++){
					$layer = $this->layerset['list'][$this->layerset['layers_of_group'][$group_id][$j]];
					$legend .= $this->create_layer_legend($layer);
				}
			}
	  }
    $legend .= '</table></div></td></tr></table>';
    $legend .= '<input type="hidden" name="radiolayers_'.$group_id.'" value="'.$this->radiolayers[$group_id].'">';
	  $legend .= '</div>';
    return $legend;
  }

	function create_layer_legend($layer){
		global $legendicon_size;
		$visible = $this->check_layer_visibility($layer);
		# sichtbare Layer
		if ($visible) {
			if ($layer['requires'] == '') {
				$legend .= '<tr><td valign="top">';

				if ($layer['queryable'] == 1 AND !$this->formvars['nurFremdeLayer']) {
					$input_attr['id'] = 'qLayer' . $layer['Layer_ID'];
					$input_attr['name'] = 'qLayer' . $layer['Layer_ID'];
					$input_attr['title'] = ($layer['queryStatus'] == 1 ? $this->deactivatequery : $this->activatequery);
					$input_attr['value'] = 1;
					$input_attr['class'] = 'info-select-field';
					$input_attr['type'] = (($this->user->rolle->singlequery or $layer['selectiontype'] == 'radio') ? 'radio' : 'checkbox');
					$input_attr['style'] = ((
						$this->user->rolle->query or
						$this->user->rolle->touchquery or
						$this->user->rolle->queryradius or
						$this->user->rolle->polyquery
					) ? '' : 'display: none');
					$input_attr['onClick'] = ($input_attr['type'] == 'radio' ?
						"this.checked = this.checked2;" :
						"updateThema(
							event,
							document.getElementById('thema_" . $layer['Layer_ID'] . "'),
							document.getElementById('qLayer" . $layer['Layer_ID'] . "'),
							'',
							''," .
							$this->user->rolle->instant_reload . "
						)"
					);
					$input_attr['onMouseUp'] = ($input_attr['type'] == 'radio' ?
						"this.checked = this.checked2;" :
						""
					);
					
					$input_attr['onMouseDown'] = ($input_attr['type'] == 'radio' ?
						"updateThema(
							event,
							document.getElementById('thema_" . $layer['Layer_ID'] . "'),
							document.getElementById('qLayer" . $layer['Layer_ID'] . "')," .
							($layer['selectiontype'] == 'radio' ? "document.GUI.radiolayers_" . $layer['Gruppe'] : "''") . "," .
							($this->user->rolle->singlequery ? "document.GUI.layers" : "''") . "," .
							$this->user->rolle->instant_reload . "
						)" :
						""
					);

					# die sichtbaren Layer brauchen dieses Hiddenfeld mit dem gleichen Namen, welches immer den value 0 hat,
					# damit sie beim Neuladen ausgeschaltet werden können, denn eine nicht angehakte Checkbox/Radiobutton wird ja nicht übergeben
					$legend .= '<input type="hidden" name="qLayer'.$layer['Layer_ID'].'" value="0">';
					$legend .= '<input';
					foreach ($input_attr AS $key => $value) {
						$legend .= ($value != '' ? ' ' . $key . '="' . $value . '"' : '');
					}
					$legend .= ($layer['queryStatus'] == 1 ? ' checked' : '');
					$legend .= '>';
				}
				else{
					$legend .= '<img src="'.GRAPHICSPATH.'leer.gif" width="17" height="1" border="0">';
				}
				$legend .=  '</td><td valign="top">';
				// die sichtbaren Layer brauchen dieses Hiddenfeld mit dem gleichen Namen, welches immer den value 0 hat, damit sie beim Neuladen ausgeschaltet werden können, denn eine nicht angehakte Checkbox/Radiobutton wird ja nicht übergeben
				$legend .=  '<input type="hidden" id="thema'.$layer['Layer_ID'].'" name="thema'.$layer['Layer_ID'].'" value="0">';

				$legend .=  '<input id="thema_'.$layer['Layer_ID'].'" ';
				if($layer['selectiontype'] == 'radio'){
					$legend .=  'type="radio" ';
					$legend .=  ' onClick="this.checked = this.checked2;" onMouseUp="this.checked = this.checked2;" onMouseDown="updateQuery(event, document.getElementById(\'thema_'.$layer['Layer_ID'].'\'), document.getElementById(\'qLayer'.$layer['Layer_ID'].'\'), document.GUI.radiolayers_'.$layer['Gruppe'].', '.$this->user->rolle->instant_reload.')"';
					$this->radiolayers[$layer['Gruppe']] .= $layer['Layer_ID'].'|';
				}
				else{
					$legend .=  'type="checkbox" ';
					$legend .=  ' onClick="updateQuery(event, document.getElementById(\'thema_'.$layer['Layer_ID'].'\'), document.getElementById(\'qLayer'.$layer['Layer_ID'].'\'), \'\', '.$this->user->rolle->instant_reload.')"';
				}
				$legend .=  ' name="thema'.$layer['Layer_ID'].'" value="1" ';
				if($layer['aktivStatus'] == 1){
					$legend .=  'checked title="'.$this->deactivatelayer.'"';
				}
				else{
					$legend .=  ' title="'.$this->activatelayer.'"';
				}
				$legend .= ' ></td><td valign="middle">';

				$legend .= '<a';
				# Bei eingeschalteter Rollenoption Layeroptionen anzeigen wird das Optionsfeld mit einem Rechtsklick geöffnet.
				if ($this->user->rolle->showlayeroptions) {
					$legend .= ' oncontextmenu="getLayerOptions(' . $layer['Layer_ID'] . '); return false;"';
				}
				if($layer['metalink'] != '' AND substr($layer['metalink'], 0, 10) != 'javascript')
					$legend .= ' target="_blank"';
				if($layer['metalink'] != '')
					$legend .= ' class="metalink boldhover" href="'.$layer['metalink'].'">';
				else
					$legend .= ' class="visiblelayerlink boldhover" href="javascript:void(0)">';
				$legend .= '<span id="'.str_replace('-', '_', $layer['alias']).'"';
				if($layer['minscale'] != -1 AND $layer['maxscale'] > 0){
					$legend .= ' title="'.round($layer['minscale']).' - '.round($layer['maxscale']).'"';
				}			  
				$legend .=' >'.html_umlaute($layer['alias']).'</span>';
				$legend .= '</a>';

				# Bei eingeschalteten Layern und eingeschalteter Rollenoption ist ein Optionen-Button sichtbar
				if($layer['aktivStatus'] == 1 and $this->user->rolle->showlayeroptions) $legend.='&nbsp;<a href="javascript:getLayerOptions('.$layer['Layer_ID'].')">
				<i class="fa fa-bars pointer button layerOptionsIcon" title="'.$this->layerOptions.'"></i>
				</a>';
				$legend.='<div style="position:static" id="options_'.$layer['Layer_ID'].'"> </div>';
			}
			if($layer['aktivStatus'] == 1 AND $layer['Class'][0]['Name'] != ''){
				if($layer['requires'] == '' AND $layer['Layer_ID'] > 0){
					$legend .= '<input id="classes_'.$layer['Layer_ID'].'" name="classes_'.$layer['Layer_ID'].'" type="hidden" value="'.$layer['showclasses'].'">';
				}
				if ($layer['showclasses'] != 0) {
					if($layer['connectiontype'] == 7){      # WMS
						$layersection = substr($layer['connection'], strpos(strtolower($layer['connection']), 'layers')+7);
						$pos = strpos($layersection, '&');
						if($pos !== false)$layersection = substr($layersection, 0, $pos);
						$layers = explode(',', $layersection);
						for($l = 0; $l < count($layers); $l++){
							$legend .=  '<div style="display:inline" id="lg'.$j.'_'.$l.'"><br><img src="'.$layer['connection'].'&layer='.$layers[$l].'&service=WMS&request=GetLegendGraphic" onerror="ImageLoadFailed(\'lg'.$j.'_'.$l.'\')"></div>';
						}
					}
					else {
						$legend .= '<table border="0" cellspacing="0" cellpadding="0">';
						$maplayer = $this->map->getLayerByName($layer['alias']);
						if($layer['Class'][0]['legendorder'] != ''){
							usort($layer['Class'], 'compare_legendorder');
						}
						for($k = 0; $k < $maplayer->numclasses; $k++){
							$class = $maplayer->getClass($layer['Class'][$k]['index']);
							for($s = 0; $s < $class->numstyles; $s++){
								$style = $class->getStyle($s);
								if($maplayer->type > 0){
									$symbol = $this->map->getSymbolObjectById($style->symbol);
									if($symbol->type == 1006){ 	# 1006 == hatch
										$style->set('size', 2*$style->width);					# size und maxsize beim Typ Hatch auf die doppelte Linienbreite setzen, damit man was in der Legende erkennt
										$style->set('maxsize', 2*$style->width);
									}
									else{
										$style->set('size', 2);					# size und maxsize bei Linien und Polygonlayern immer auf 2 setzen, damit man was in der Legende erkennt
										$style->set('maxsize', 2);
									}
								}
								else{		# Punktlayer
									if($style->size > 14)$style->set('size', 14);
									$style->set('maxsize', $style->size);		# maxsize auf size setzen bei Punktlayern, damit man was in der Legende erkennt
									$style->set('minsize', $style->size);		# minsize auf size setzen bei Punktlayern, damit man was in der Legende erkennt
									if($class->numstyles == 1){							# wenn es nur einen Style in der Klasse gibt, die Offsets auf 0 setzen, damit man was in der Legende erkennt
										$style->set('offsety', 0);
										$style->set('offsetx', 0);
									}
								}
							}
							$legend .= '<tr style="line-height: 15px"><td style="line-height: 14px">';
							if($s > 0){
								$width = $height = '';
								if($layer['Class'][$k]['legendimagewidth'] != '')$width = $layer['Class'][$k]['legendimagewidth'];
								if($layer['Class'][$k]['legendimageheight'] != '')$height = $layer['Class'][$k]['legendimageheight'];
								$padding = 1;
								###### eigenes Klassenbild ######
								if($layer['Class'][$k]['legendgraphic'] != ''){
									$imagename = $original_class_image = GRAPHICSPATH . 'custom/' . $layer['Class'][$k]['legendgraphic'];
									if($width == ''){
										$size = getimagesize($imagename);
										$width = $size[0];
										$height = $size[1];
									}
								}
								###### generiertes Klassenbild ######
								else{
									if($width == '')$width = $legendicon_size['width'][$maplayer->type];
									if($height == '')$height = $legendicon_size['height'][$maplayer->type];
									if($layer['Class'][$k]['Style'][0]['colorrange'] != ''){		# generierte Color-Ramp
										$padding = 0;
										$newname = rand(0, 1000000).'.jpg';
										$this->colorramp(IMAGEPATH.$newname, $width, $height, $layer['Class'][$k]['Style'][0]['colorrange']);
									}
									else{																												# vom Mapserver generiertes Klassenbild
										$image = $class->createLegendIcon($width, $height);
										$filename = $this->map_saveWebImage($image,'jpeg');
										$newname = $this->user->id.basename($filename);
										rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);
									}
									$imagename = $original_class_image = TEMPPATH_REL.$newname;
								}
								####################################
								$classid = $layer['Class'][$k]['Class_ID'];
								if($this->mapDB->disabled_classes['status'][$classid] == '0'){
									if($height < $width)$height1 = 12;
									else $height1 = 18;
									$imagename = 'graphics/inactive'.$height1.'.jpg';
									$status = 0;
								}
								elseif($this->mapDB->disabled_classes['status'][$classid] == 2){
									$status = 2;
								}
								else{
									$status = 1;
								}
								# $original_class_image ist das eigentliche Klassenbild bei Status 1, $imagename das Bild, welches entsprechend des Status gerade gesetzt ist
								$legend .= '<input type="hidden" size="2" name="class'.$classid.'" value="'.$status.'"><a href="#" onmouseover="mouseOverClassStatus('.$classid.',\''.$original_class_image.'\', '.$width.', '.$height.')" onmouseout="mouseOutClassStatus('.$classid.',\''.$original_class_image.'\', '.$width.', '.$height.')" onclick="changeClassStatus('.$classid.',\''.$original_class_image.'\', '.$this->user->rolle->instant_reload.', '.$width.', '.$height.')"><img style="vertical-align:middle;padding-bottom: '.$padding.'" border="0" name="imgclass'.$classid.'" width="'.$width.'" height="'.$height.'" src="'.$imagename.'"></a>';
							}
							$legend .= '&nbsp;<span class="px13">'.html_umlaute($class->name).'</span></td></tr>';
						}
						$legend .= '</table>';
					}
				}
			}
			if($j+1 < $count AND $current_groupgetMetaData_off_requires != 1){		// todo
				$legend .= '</td></tr>';
			}
		}

		# unsichtbare Layer
		if($layer['requires'] == '' AND !$visible){
			$legend .=  '
						<tr>
							<td valign="top">';
			if($layer['queryable'] == 1){
				$style = ((
						$this->user->rolle->query or
						$this->user->rolle->touchquery or
						$this->user->rolle->queryradius or
						$this->user->rolle->polyquery
					) ? '' : 'style="display: none"');
				$legend .=  '<input ';
				if($layer['selectiontype'] == 'radio'){
					$legend .=  'type="radio" ';
				}
				else{
					$legend .=  'type="checkbox" ';
				}
				if($layer['queryStatus'] == 1){
					$legend .=  'checked="true"';
				}
				$legend .=' type="checkbox" name="pseudoqLayer'.$layer['Layer_ID'].'" disabled '.$style.'>';
			}
			$legend .=  '</td><td valign="top">';
			// die nicht sichtbaren Layer brauchen dieses Hiddenfeld mit dem gleichen Namen nur bei Radiolayern, damit sie beim Neuladen ausgeschaltet werden können, denn ein disabledtes input-Feld wird ja nicht übergeben
			$legend .=  '<input type="hidden" id="thema'.$layer['Layer_ID'].'" name="thema'.$layer['Layer_ID'].'" value="'.$layer['aktivStatus'].'">';
			$legend .=  '<input ';
			if($layer['selectiontype'] == 'radio'){
				$legend .=  'type="radio" ';
				$this->radiolayers[$layer['Gruppe']] .= $layer['Layer_ID'].'|';
			}
			else{
				$legend .=  'type="checkbox" ';
			}
			if($layer['aktivStatus'] == 1){
				$legend .=  'checked="true" ';
			}
			$legend .= 'id="thema_'.$layer['Layer_ID'].'" name="thema'.$layer['Layer_ID'].'" disabled="true"></td><td>';
			$legend .= '<a ';
			if ($this->user->rolle->showlayeroptions) {
				$legend .= ' oncontextmenu="getLayerOptions(' . $layer['Layer_ID'] . '); return false;"';
			}
			$legend .= 'class="invisiblelayerlink boldhover" href="javascript:void(0)">';
			$legend .= '<span class="legend_layer_hidden" id="'.str_replace('-', '_', $layer['alias']).'"';
			if($layer['minscale'] != -1 AND $layer['maxscale'] != -1){
				$legend .= 'title="'.round($layer['minscale']).' - '.round($layer['maxscale']).'"';
			}
			$legend .= ' >'.html_umlaute($layer['alias']).'</span></a>';
			$legend.='<div style="position:static" id="options_'.$layer['Layer_ID'].'"> </div>';
			if($layer['status'] != ''){
				$legend .= '&nbsp;<img title="Thema nicht verfügbar: '.$layer['status'].'" src="'.GRAPHICSPATH.'warning.png">';
			}
			if($layer['queryable'] == 1){
				$legend .=  '<input type="hidden" name="qLayer'.$layer['Layer_ID'].'"';
				if($layer['queryStatus'] != 0){
					$legend .=  ' value="1"';
				}
				$legend .=  '>';
			}
			$legend .=  '</td>
					</tr>';
		}
		return $legend;
	}

	function check_layer_visibility(&$layer){
		if($layer['status'] != '' OR ($this->map_scaledenom < $layer['minscale'] OR ($layer['maxscale'] > 0 AND $this->map_scaledenom > $layer['maxscale']))) {
			return false;
		}
		return true;
	}

	function map_saveWebImage($image,$format) {
		if(MAPSERVERVERSION >= 600 ) {
			return $image->saveWebImage();
		}
		else {
			return $image->saveWebImage($format, 1, 1, 0);
		}
	}
}

class database {

  var $ist_Fortfuehrung;
  var $debug;
  var $loglevel;
  var $logfile;
  var $commentsign;
  var $blocktransaction;

  function database() {
    global $debug;
    $this->debug=$debug;
    $this->loglevel=LOG_LEVEL;
 		$this->defaultloglevel=LOG_LEVEL;
 		global $log_mysql;
    $this->logfile=$log_mysql;
 		$this->defaultlogfile=$log_mysql;
    $this->ist_Fortfuehrung=1;
    $this->type="MySQL";
    $this->commentsign='#';
    # Wenn dieser Parameter auf 1 gesetzt ist werden alle Anweisungen
    # BEGIN TRANSACTION, ROLLBACK und COMMIT unterdrückt, so daß alle anderen SQL
    # Anweisungen nicht in Transactionsblöcken ablaufen.
    # Kann zur Steigerung der Geschwindigkeit von großen Datenbeständen verwendet werden
    # Vorsicht: Wenn Fehler beim Einlesen passieren, ist der Datenbestand inkonsistent
    # und der Einlesevorgang muss wiederholt werden bis er fehlerfrei durchgelaufen ist.
    # Dazu Fehlerausschriften bearchten.
    $this->blocktransaction=0;
  }

  function open() {
    $this->debug->write("<br>MySQL Verbindung öffnen mit Host: ".$this->host." User: ".$this->user,4);
    $this->dbConn=mysql_connect($this->host,$this->user,$this->passwd);
    $this->debug->write("Datenbank mit ID: ".$this->dbConn." und Name: ".$this->dbName." auswählen.",4);
    return mysql_select_db($this->dbName,$this->dbConn);
  }

  function execSQL($sql,$debuglevel, $loglevel) {
  	switch ($this->loglevel) {
  		case 0 : {
  			$logsql=0;
  		} break;
  		case 1 : {
  			$logsql=1;
  		} break;
  		case 2 : {
  			$logsql=$loglevel;
  		} break;
  	}
    # SQL-Statement wird nur ausgeführt, wenn DBWRITE gesetzt oder
    # wenn keine INSERT, UPDATE und DELETE Anweisungen in $sql stehen.
    # (lesend immer, aber schreibend nur mit DBWRITE=1)
    if (DBWRITE OR (!stristr($sql,'INSERT') AND !stristr($sql,'UPDATE') AND !stristr($sql,'DELETE'))) {
      $query=mysql_query($sql,$this->dbConn);
      #echo $sql;
      if ($query==0) {
        $ret[0]=1;
        $ret[1]="<b>Fehler bei SQL Anweisung:</b><br>".$sql."<br>".mysql_error($this->dbConn);
        $this->debug->write($ret[1],$debuglevel);
        if ($logsql) {
          $this->logfile->write("#".$ret[1]);
        }
      }
      else {
        $ret[0]=0;
        $ret[1]=$query;
        if ($logsql) {
          $this->logfile->write($sql.';');
        }
        $this->debug->write(date('H:i:s')."<br>".$sql,$debuglevel);
      }
      $ret[2]=$sql;
    }
    else {
    	if ($logsql) {
    		$this->logfile->write($sql.';');
    	}
    	$this->debug->write("<br>".$sql,$debuglevel);
    }
    return $ret;
  }

  function close() {
    $this->debug->write("<br>MySQL Verbindung mit ID: ".$this->dbConn." schließen.",4);
    if (LOG_LEVEL>0){
    	$this->logfile->close();
    }
    return mysql_close($this->dbConn);
  }
}

class user {

  var $id;
  var $Name;
  var $Vorname;
  var $login_name;
  var $funktion;
  var $dbConn;
  var $Stellen;
  var $nZoomFactor;
  var $nImageWidth;
  var $nImageHeight;
  var $database;
  var $remote_addr;

	function user($login_name,$id,$database) {
		global $debug;
		$this->debug=$debug;
		$this->database=$database;
		if($login_name){
			$this->login_name=$login_name;
			$this->readUserDaten(0,$login_name);
			$this->remote_addr=getenv('REMOTE_ADDR');
		}
		else{
			$this->id = $id;
			$this->readUserDaten($id,0);
		}
	}

	function readUserDaten($id, $login_name) {
		$where = array();
		if ($id > 0) array_push($where, "ID = " . $id);
		if ($login_name != '') array_push($where, "login_name LIKE '" . $login_name . "'");
		$sql = "
			SELECT
				*
			FROM
				user
			WHERE " .
				implode(" AND ", $where) . "
		";
		#echo '<br>Sql: ' . $sql;

		$this->debug->write("<p>file:users.php class:user->readUserDaten - Abfragen des Namens des Benutzers:<br>" . $sql, 4);
		$query = mysql_query($sql,$this->database->dbConn);
		if ($query == 0) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__, 4); return 0; }
		$rs = mysql_fetch_array($query);
		$this->id = $rs['ID'];
		$this->login_name = $rs['login_name'];
		$this->Namenszusatz = $rs['Namenszusatz'];
		$this->Name = $rs['Name'];
		$this->Vorname = $rs['Vorname'];
		$this->stelle_id = $rs['stelle_id'];
		$this->phon = $rs['phon'];
		$this->email = $rs['email'];
		if (CHECK_CLIENT_IP) {
			$this->ips = $rs['ips'];
		}
		$this->funktion = $rs['Funktion'];
		$this->password_setting_time = $rs['password_setting_time'];
  }

  function getLastStelle() {
    $sql = 'SELECT stelle_id FROM user WHERE ID='.$this->id;
    $this->debug->write("<p>file:users.php class:user->getLastStelle - Abfragen der zuletzt genutzten Stelle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    return $rs['stelle_id'];
  }

	function StellenZugriff($stelle_id) {
		$this->Stellen=$this->getStellen($stelle_id);
		if (count($this->Stellen['ID'])>0) {
			return 1;
		}
		return 0;
	}

	function getStellen($stelle_ID) {
		$sql = "
			SELECT
				s.ID,
				s.Bezeichnung
			FROM
				stelle AS s,
				rolle AS r
			WHERE
				s.ID = r.stelle_id AND
				r.user_id = " . $this->id .
				($stelle_ID > 0 ? " AND s.ID = " . $stelle_ID : "") . "
				AND (
					('" . date('Y-m-d h:i:s') . "' >= s.start AND '" . date('Y-m-d h:i:s') . "' <= s.stop)
					OR
					(s.start = '0000-00-00 00:00:00' AND s.stop = '0000-00-00 00:00:00')
				)
			ORDER BY
				Bezeichnung;
		";

		#echo '<br>sql: ' . $sql;
		$this->debug->write("<p>file:users.php class:user->getStellen - Abfragen der Stellen die der User einnehmen darf:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		while($rs=mysql_fetch_array($query)) {
			$stellen['ID'][]=$rs['ID'];
			$stellen['Bezeichnung'][]=$rs['Bezeichnung'];
		}
		return $stellen;
	}

	function clientIpIsValide($remote_addr) {
    # Prüfen ob die übergebene IP Adresse zu den für den Nutzer eingetragenen Adressen passt
    $ips=explode(';',$this->ips);
    foreach ($ips AS $ip) {
      if (trim($ip)!='') {
        $ip=trim($ip);
				if(!is_numeric(array_pop(explode('.', $ip))))$ip = gethostbyname($ip);			# für dyndns-Hosts
        if (in_subnet($remote_addr, $ip)) {
          $this->debug->write('<br>IP:'.$remote_addr.' paßt zu '.$ip,4);
          #echo '<br>IP:'.$remote_addr.' paßt zu '.$ip;
          return 1;
        }
      }
    }
    return 0;
  }

	function setRolle($stelle_id) {
		# Abfragen und zuweisen der Einstellungen für die Rolle		
		$rolle = new rolle($this->id, $stelle_id, $this->database);		
		if ($rolle->readSettings()) {
			$this->rolle=$rolle;
			return 1;
		}
		return 0;
	}
}

class stelle {

  var $id;
  var $Bezeichnung;
  var $debug;
  var $nImageWidth;
  var $nImageHeight;
  var $oGeorefExt;
  var $pixsize;
  var $selectedButton;
  var $database;

	function stelle($id, $database) {
		global $debug;
		$this->debug = $debug;
		$this->id = $id;
		$this->database = $database;
		$this->Bezeichnung = $this->getName();
		$this->readDefaultValues();
	}

  function getName() {
    $sql ='SELECT ';
    if ($this->language != 'german' AND $this->language != ''){
      $sql.='`Bezeichnung_'.$this->language.'` AS ';
    }
    $sql.='Bezeichnung FROM stelle WHERE ID='.$this->id;
    #echo $sql;
    $this->debug->write("<p>file:stelle.php class:stelle->getName - Abfragen des Namens der Stelle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    $this->Bezeichnung=$rs['Bezeichnung'];
    return $rs['Bezeichnung'];
  }

  function readDefaultValues() {
    $sql = "
			SELECT
				*
			FROM
				stelle
			WHERE
				ID = " . $this->id . "
		";
    $this->debug->write("<p>file:stelle.php class:stelle->readDefaultValues - Abfragen der Default Parameter der Karte zur Stelle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);    
    $this->MaxGeorefExt=ms_newRectObj();
    $this->MaxGeorefExt->setextent($rs['minxmax'],$rs['minymax'],$rs['maxxmax'],$rs['maxymax']);
    $this->epsg_code=$rs["epsg_code"];
    $this->alb_raumbezug=$rs["alb_raumbezug"];
    $this->alb_raumbezug_wert=$rs["alb_raumbezug_wert"];
    $this->pgdbhost = ($rs["pgdbhost"] == 'PGSQL_PORT_5432_TCP_ADDR') ? getenv('PGSQL_PORT_5432_TCP_ADDR') : $rs["pgdbhost"];
    $this->pgdbname=$rs["pgdbname"];
    $this->pgdbuser=$rs["pgdbuser"];
    $this->pgdbpasswd=$rs["pgdbpasswd"];
    $this->protected=$rs["protected"];
    //---------- OWS Metadaten ----------//
    $this->ows_title=$rs["ows_title"];
    $this->ows_abstract=$rs["ows_abstract"];
    $this->wms_accessconstraints=$rs["wms_accessconstraints"];
    $this->ows_contactperson=$rs["ows_contactperson"];
    $this->ows_contactorganization=$rs["ows_contactorganization"];
    $this->ows_contactelectronicmailaddress=$rs["ows_contactemailaddress"];
    $this->ows_contactposition=$rs["ows_contactposition"];
    $this->ows_fees=$rs["ows_fees"];
    $this->ows_srs=$rs["ows_srs"];
    $this->check_client_ip=$rs["check_client_ip"];
    $this->checkPasswordAge=$rs["check_password_age"];
    $this->allowedPasswordAge=$rs["allowed_password_age"];
    $this->useLayerAliases=$rs["use_layer_aliases"];
		$this->selectable_layer_params = $rs['selectable_layer_params'];
		$this->hist_timestamp=$rs["hist_timestamp"];
		$this->default_user_id = $rs['default_user_id'];
  }

  function checkClientIpIsOn() {
    $sql ='SELECT check_client_ip FROM stelle WHERE ID = '.$this->id;
    $this->debug->write("<p>file:stelle.php class:stelle->checkClientIpIsOn- Abfragen ob IP's der Nutzer in der Stelle getestet werden sollen<br>".$sql,4);
    #echo '<br>'.$sql;
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    if ($rs['check_client_ip']=='1') {
      return 1;
    }
    return 0;
  }
}

class rolle {

  var $user_id;
  var $stelle_id;
  var $debug;
  var $database;
  var $loglevel;
  static $hist_timestamp;
  static $layer_params;

	function rolle($user_id,$stelle_id,$database) {
		global $debug;
		$this->debug=$debug;
		$this->user_id=$user_id;
		$this->stelle_id=$stelle_id;
		$this->database=$database;
		#$this->layerset=$this->getLayer('');
		#$this->groupset=$this->getGroups('');
		$this->loglevel = 0;
	}

  function readSettings() {
		global $language;
    # Abfragen und Zuweisen der Einstellungen der Rolle
    $sql = "
			SELECT
				*
			FROM
				rolle
			WHERE
				user_id = " . $this->user_id . " AND
				stelle_id = " . $this->stelle_id . "
		";
    #echo $sql;
    $this->debug->write("<p>file:rolle.php class:rolle function:readSettings - Abfragen der Einstellungen der Rolle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) {
      $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4);
      return 0;
    }
		if(mysql_num_rows($query) > 0){
			$rs = mysql_fetch_assoc($query);
			$this->oGeorefExt=ms_newRectObj();
			$this->oGeorefExt->setextent($rs['minx'],$rs['miny'],$rs['maxx'],$rs['maxy']);
			$this->nImageWidth=$rs['nImageWidth'];
			$this->nImageHeight=$rs['nImageHeight'];			
			$this->mapsize=$this->nImageWidth.'x'.$this->nImageHeight;
			$this->auto_map_resize=$rs['auto_map_resize'];
			@$this->pixwidth=($rs['maxx']-$rs['minx'])/$rs['nImageWidth'];
			@$this->pixheight=($rs['maxy']-$rs['miny'])/$rs['nImageHeight'];
			$this->pixsize=($this->pixwidth+$this->pixheight)/2;
			$this->nZoomFactor=$rs['nZoomFactor'];
			$this->epsg_code=$rs['epsg_code'];
			$this->epsg_code2=$rs['epsg_code2'];
			$this->coordtype=$rs['coordtype'];
			$this->last_time_id=$rs['last_time_id'];
			$this->gui=$rs['gui'];
			$this->language=$rs['language'];
			$language = $this->language;
			$this->hideMenue=$rs['hidemenue'];
			$this->hideLegend=$rs['hidelegend'];
			$this->fontsize_gle=$rs['fontsize_gle'];
			$this->highlighting=$rs['highlighting'];
			$this->scrollposition=$rs['scrollposition'];
			$this->result_color=$rs['result_color'];
			$this->always_draw=$rs['always_draw'];
			$this->runningcoords=$rs['runningcoords'];
			$this->showmapfunctions=$rs['showmapfunctions'];
			$this->showlayeroptions=$rs['showlayeroptions'];
			$this->menue_buttons=$rs['menue_buttons'];
			$this->singlequery=$rs['singlequery'];
			$this->querymode=$rs['querymode'];
			$this->geom_edit_first=$rs['geom_edit_first'];		
			$this->overlayx=$rs['overlayx'];
			$this->overlayy=$rs['overlayy'];
			$this->instant_reload=$rs['instant_reload'];
			$this->menu_auto_close=$rs['menu_auto_close'];
			rolle::$layer_params = (array)json_decode('{' . $rs['layer_params'] . '}');
			$this->visually_impaired = $rs['visually_impaired'];
			$this->legendtype = $rs['legendtype'];
			if($rs['hist_timestamp'] != ''){
				$this->hist_timestamp = DateTime::createFromFormat('Y-m-d H:i:s', $rs['hist_timestamp'])->format('d.m.Y H:i:s');			# der wird zur Anzeige des Timestamps benutzt
				rolle::$hist_timestamp = DateTime::createFromFormat('Y-m-d H:i:s', $rs['hist_timestamp'])->format('Y-m-d\TH:i:s\Z');	# der hat die Form, wie der timestamp in der PG-DB steht und wird für die Abfragen benutzt
			}
			else
				rolle::$hist_timestamp = $this->hist_timestamp = '';
			$this->selectedButton=$rs['selectedButton'];
			$buttons = explode(',', $rs['buttons']);
			$this->back = in_array('back', $buttons);
			$this->forward = in_array('forward', $buttons);
			$this->zoomin = in_array('zoomin', $buttons);
			$this->zoomout = in_array('zoomout', $buttons);
			$this->zoomall = in_array('zoomall', $buttons);
			$this->recentre = in_array('recentre', $buttons);
			$this->jumpto = in_array('jumpto', $buttons);
			$this->coord_query = in_array('coord_query', $buttons);			
			$this->query = in_array('query', $buttons);
			$this->queryradius = in_array('queryradius', $buttons);
			$this->polyquery = in_array('polyquery', $buttons);
			$this->touchquery = in_array('touchquery', $buttons);
			$this->measure = in_array('measure', $buttons);
			$this->freepolygon = in_array('freepolygon', $buttons);
			$this->freetext = in_array('freetext', $buttons);
			$this->freearrow = in_array('freearrow', $buttons);
			return 1;
		}else return 0;
  }

	function setGroupStatus($formvars) {
		$this->groupset = $this->getGroups('');
		# Eintragen des group_status=1 für Gruppen, die angezeigt werden sollen
		for ($i = 0; $i < count($this->groupset); $i++) {
			if ($formvars['group_' . $this->groupset[$i]['id']] !== NULL) {
				$group_status = ($formvars['group_' . $this->groupset[$i]['id']] == 1 ? 1 : 0);
				$sql = "
					UPDATE
						`u_groups2rolle`
					SET
						`status` = '" . $group_status . "'
					WHERE
						`user_id` = " . $this->user_id . " AND
						`stelle_id` = " . $this->stelle_id . " AND
						`id` = " . $this->groupset[$i]['id'] . "
				";
				#echo '<br>Sql: ' . $sql;
				$this->debug->write("<p>file:rolle.php class:rolle->setGroupStatus - Speichern des Status der Gruppen zur Rolle:", 4);
				$this->database->execSQL($sql, 4, $this->loglevel);
			}
		}
		return $formvars;
	}

  function getGroups($GroupName) {
		global $language;
    # Abfragen der Gruppen in der Rolle
    $sql ='SELECT g2r.*, ';
		if($language != 'german') {
			$sql.='CASE WHEN `Gruppenname_'.$language.'` != "" THEN `Gruppenname_'.$language.'` ELSE `Gruppenname` END AS ';
		}
		$sql.='Gruppenname FROM u_groups AS g, u_groups2rolle AS g2r ';
    $sql.=' WHERE g2r.stelle_ID='.$this->stelle_id.' AND g2r.user_id='.$this->user_id;
    $sql.=' AND g2r.id = g.id';
    if ($GroupName!='') {
      $sql.=' AND Gruppenname LIKE "'.$GroupName.'"';
    }
    $this->debug->write("<p>file:rolle.php class:rolle->getGroups - Abfragen der Gruppen zur Rolle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
    while ($rs=mysql_fetch_array($query)) {
      $groups[]=$rs;
    }
    return $groups;
  }

	function setClassStatus($formvars) {
		if($formvars['layer_id'] != ''){
			# Eintragen des showclasses=1 für Klassen, die angezeigt werden sollen
			$sql ='UPDATE u_rolle2used_layer set showclasses = "'.$formvars['show_classes'].'"';
			$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
			$sql.=' AND layer_id='.$formvars['layer_id'];
			$this->debug->write("<p>file:rolle.php class:rolle->setClassStatus - Speichern des Status der Klassen zur Rolle:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
		}
	}
}

class pgdatabase {

  var $ist_Fortfuehrung;
  var $debug;
  var $loglevel;
  var $defaultloglevel;
  var $logfile;
  var $defaultlogfile;
  var $commentsign;
  var $blocktransaction;

	function pgdatabase() {
	  global $debug;
    $this->debug=$debug;
    $this->loglevel=LOG_LEVEL;
 		$this->defaultloglevel=LOG_LEVEL;
 		global $log_postgres;
    $this->logfile=$log_postgres;
 		$this->defaultlogfile=$log_postgres;
    $this->ist_Fortfuehrung=1;
    $this->type='postgresql';
    $this->commentsign='--';
    # Wenn dieser Parameter auf 1 gesetzt ist werden alle Anweisungen
    # START TRANSACTION, ROLLBACK und COMMIT unterdrï¿½ckt, so daï¿½ alle anderen SQL
    # Anweisungen nicht in Transactionsblï¿½cken ablaufen.
    # Kann zur Steigerung der Geschwindigkeit von groï¿½en Datenbestï¿½nden verwendet werden
    # Vorsicht: Wenn Fehler beim Einlesen passieren, ist der Datenbestand inkonsistent
    # und der Einlesevorgang muss wiederholt werden bis er fehlerfrei durchgelaufen ist.
    # Dazu Fehlerausschriften bearchten.
    $this->blocktransaction=0;
		$this->spatial_ref_code = EPSGCODE_ALKIS . ", " . EARTH_RADIUS;
  }

  function open() {
  	if($this->port == '') $this->port = 5432;
    #$this->debug->write("<br>Datenbankverbindung öffnen: Datenbank: ".$this->dbName." User: ".$this->user,4);
		$this->connect_string = '' .
      'dbname='. $this->dbName .
      ' port=' . $this->port .
      ' user=' . $this->user .
      ' password=' . $this->passwd;
		if($this->host != 'localhost' AND $this->host != '127.0.0.1')
      $this->connect_string .= ' host=' . $this->host; // das beschleunigt den Connect extrem
    $this->dbConn = pg_connect($this->connect_string);
    $this->debug->write("Datenbank mit Connection_ID: ".$this->dbConn." geöffnet.",4);
    # $this->version = pg_version($this->dbConn); geht erst mit PHP 5
    $this->version = POSTGRESVERSION;
    return $this->dbConn;
  }

  function setClientEncoding() {
    $sql ="SET CLIENT_ENCODING TO '".POSTGRES_CHARSET."';";
		$ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    return $ret[1];    	
  }  

	function execSQL($sql, $debuglevel, $loglevel, $suppress_error_msg = false) {
		$ret = array(); // Array with results to return

		switch ($this->loglevel) {
			case 0 : {
				$logsql = 0;
			} break;
			case 1 : {
				$logsql = 1;
			} break;
			case 2 : {
				$logsql = $loglevel;
			} break;
		}
		# SQL-Statement wird nur ausgeführt, wenn DBWRITE gesetzt oder
		# wenn keine INSERT, UPDATE und DELETE Anweisungen in $sql stehen.
		# (lesend immer, aber schreibend nur mit DBWRITE=1)
		if (DBWRITE OR (!stristr($sql,'INSERT') AND !stristr($sql,'UPDATE') AND !stristr($sql,'DELETE'))) {
			#echo "<br>".$sql;
			if (stristr($sql, 'SELECT')) {
				$sql = "SET datestyle TO 'German';" . $sql;
			};
			if ($this->schema != ''){
				$sql = "SET search_path = " . $this->schema . ", public;" . $sql;
			}
			if ($suppress_error_msg) {
				$query = @pg_query($this->dbConn, $sql);
			}
			else {
				$query = @pg_query($this->dbConn, $sql);
			}

			//$query=0;
			if ($query == 0) {
				$ret[0] = 1;
				$ret['success'] = false;
				$errormessage = pg_last_error($this->dbConn);
				#header('error: true');		// damit ajax-Requests das auch mitkriegen
				$ret[1] = "Fehler bei SQL Anweisung:<br><br>\n\n" . $sql . "\n\n<br><br>" . $errormessage;
				$ret['msg'] = $ret[1];
				$ret['type'] = 'error';
				if (!$suppress_error_msg) {
					echo "<br><b>" . $ret[1] . "</b>";
				}
				$this->debug->write("<br><b>" . $ret[1] . "</b>", $debuglevel);
				if ($logsql) {
					$this->logfile->write($this->commentsign . " " . $ret[1]);
				}
			}
			else {
				# Abfrage wurde erfolgreich ausgeführt
				$ret[0] = 0;
				$ret['success'] = true;
				$ret[1] = $query;
				$ret['query'] = $ret[1]; 
				$this->debug->write("<br>" . $sql, $debuglevel);
				# 2006-07-04 pk $logfile ersetzt durch $this->logfile
				if ($logsql) {
					$this->logfile->write($sql . ';');
				}
			}
			$ret[2] = $sql;
		}
		else {
			# Es werden keine SQL-Kommandos ausgeführt
			# Die Funktion liefert ret[0]=0, und zeigt damit an, daß kein Datenbankfehler aufgetreten ist,
			$ret[0] = 0;
			$ret['success'] = true;
			# jedoch hat $ret[1] keine query_ID sondern auch den Wert 0
			$ret[1] = 0;
			# Wenn $this->loglevel != 0 wird die sql-Anweisung in die logdatei geschrieben
			# zusätzlich immer in die debugdatei
			# 2006-07-04 pk $logfile ersetzt durch $this->logfile
			if ($logsql) {
				$this->logfile->write($sql . ';');
			}
			$this->debug->write("<br>" . $sql, $debuglevel);
		}
		return $ret;
	}

	function read_epsg_codes($order = true){
    global $supportedSRIDs;
    $sql ="SELECT spatial_ref_sys.srid, coalesce(alias, substr(srtext, 9, 35)) as srtext, minx, miny, maxx, maxy FROM spatial_ref_sys ";
    $sql.="LEFT JOIN spatial_ref_sys_alias ON spatial_ref_sys_alias.srid = spatial_ref_sys.srid";
    # Wenn zu unterstützende SRIDs angegeben sind, ist die Abfrage diesbezüglich eingeschränkt
    $anzSupportedSRIDs = count($supportedSRIDs);
    if ($anzSupportedSRIDs > 0) {
      $sql.=" WHERE spatial_ref_sys.srid IN (".implode(',', $supportedSRIDs).")";
    }
    if($order)$sql.=" ORDER BY srtext";
    #echo $sql;		
    $ret = $this->execSQL($sql, 4, 0);		
    if($ret[0]==0){
			$i = 0;
      while($row = pg_fetch_assoc($ret[1])){
				$epsg_codes[$row['srid']] = $row;
				$i++;
      }
    }
    return $epsg_codes;
  }

  function close() {
    $this->debug->write("<br>PostgreSQL Verbindung mit ID: ".$this->dbConn." schließen.",4);
    return pg_close($this->dbConn);
  }
}

class db_mapObj {

  var $debug;
  var $referenceMap;
  var $Layer;
  var $anzLayer;
  var $nurAufgeklappteLayer;
  var $Stelle_ID;
  var $User_ID;
  var $database;

  function db_mapObj($Stelle_ID, $User_ID, $database = NULL) {
    global $debug;
    $this->debug=$debug;
    $this->Stelle_ID=$Stelle_ID;
    $this->User_ID=$User_ID;
		$this->rolle = new rolle($User_ID, $Stelle_ID, $database);
		$this->database=$database;
  }

	function read_ReferenceMap() {
    $sql ='SELECT r.* FROM referenzkarten AS r, stelle AS s WHERE r.ID=s.Referenzkarte_ID';
    $sql.=' AND s.ID='.$this->Stelle_ID;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_ReferenceMap - Lesen der Referenzkartendaten:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    $this->referenceMap=$rs;
    return $rs;
  }

  function read_Groups($all = false, $order = '') {
		global $language;
		$sql = 'SELECT ';
		if($all == false) $sql .= 'g2r.status, ';
		if($language != 'german') {
			$sql.='CASE WHEN `Gruppenname_'.$language.'` IS NOT NULL THEN `Gruppenname_'.$language.'` ELSE `Gruppenname` END AS ';
		}
		$sql.='Gruppenname, obergruppe, g.id FROM u_groups AS g';
		if($all == false){
			$sql.=', u_groups2rolle AS g2r ';
			$sql.=' WHERE g2r.stelle_ID='.$this->Stelle_ID.' AND g2r.user_id='.$this->User_ID;
			$sql.=' AND g2r.id = g.id';
		}
		if($order != '')$sql.=' ORDER BY '. replace_semicolon($order);
		else $sql.=' ORDER BY `order`';
		#echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Groups - Lesen der Gruppen der Rolle:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    while ($rs=mysql_fetch_array($query)) {
      $groups[$rs['id']]=$rs;
			if($rs['obergruppe'])$groups[$rs['obergruppe']]['untergruppen'][] = $rs['id'];
    }
    $this->anzGroups=count($groups);
    return $groups;
  }

  function read_Layer($withClasses, $useLayerAliases = false, $groups = NULL){
		global $language;

		if($language != 'german') {
			$name_column = "
			CASE
				WHEN l.`Name_" . $language . "` != \"\" THEN l.`Name_" . $language . "`
				ELSE l.`Name`
			END AS Name";
		}
		else
			$name_column = "l.Name";

		$sql = "
			SELECT DISTINCT
				coalesce(rl.transparency, ul.transparency, 100) as transparency, rl.`aktivStatus`, rl.`queryStatus`, rl.`gle_view`, rl.`showclasses`, rl.`logconsume`, 
				ul.`queryable`, COALESCE(rl.drawingorder, ul.drawingorder) as drawingorder, ul.legendorder, ul.`minscale`, ul.`maxscale`, ul.`offsite`, ul.`postlabelcache`, ul.`Filter`, ul.`template`, ul.`header`, ul.`footer`, ul.`symbolscale`, ul.`logconsume`, ul.`requires`, ul.`privileg`, ul.`export_privileg`,
				l.Layer_ID," .
				$name_column . ",
				l.alias,
				l.Datentyp, l.Gruppe, l.pfad, l.Data, l.tileindex, l.tileitem, l.labelangleitem, l.labelitem,
				l.labelmaxscale, l.labelminscale, l.labelrequires, l.connection, l.printconnection, l.connectiontype, l.classitem, l.classification, l.filteritem,
				l.cluster_maxdistance, l.tolerance, l.toleranceunits, l.processing, l.epsg_code, l.ows_srs, l.wms_name, l.wms_server_version,
				l.wms_format, l.wms_auth_username, l.wms_auth_password, l.wms_connectiontimeout, l.selectiontype, l.logconsume,l.metalink, l.status,
				g.*
			FROM
				u_rolle2used_layer AS rl,
				used_layer AS ul,
				layer AS l,
				u_groups AS g,
				u_groups2rolle as gr
			WHERE
				rl.stelle_id = ul.Stelle_ID AND
				rl.layer_id = ul.Layer_ID AND
				l.Layer_ID = ul.Layer_ID AND
				(ul.minscale != -1 OR ul.minscale IS NULL) AND l.Gruppe = g.id AND rl.stelle_ID = " . $this->Stelle_ID . " AND rl.user_id = " . $this->User_ID . " AND
				gr.id = g.id AND
				gr.stelle_id = " . $this->Stelle_ID . " AND
				gr.user_id = " . $this->User_ID;

		if($groups != NULL){
			$sql.=' AND g.id IN ('.$groups.')';
		}
    if($this->nurAufgeklappteLayer){
      $sql.=' AND (rl.aktivStatus != "0" OR gr.status != "0" OR ul.requires != "")';
    }
    if($this->nurAktiveLayer){
      $sql.=' AND (rl.aktivStatus != "0")';
    }
		if($this->OhneRequires){
      $sql.=' AND (ul.requires IS NULL)';
    }
    if($this->nurFremdeLayer){			# entweder fremde (mit host=...) Postgis-Layer oder aktive nicht-Postgis-Layer
    	$sql.=' AND (l.connection like "%host=%" AND l.connection NOT like "%host=localhost%" OR l.connectiontype != 6 AND rl.aktivStatus != "0")';
    }
    $sql.=' ORDER BY drawingorder';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Layer - Lesen der Layer der Rolle:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    $layer = array();
		$layer['list'] = array();
    $this->disabled_classes = $this->read_disabled_classes();
		$i = 0;
    while ($rs=mysql_fetch_assoc($query)){
			if($rs['alias'] == '' OR !$useLayerAliases){
				$rs['alias'] = $rs['Name'];
			}
			$rs['id'] = $i;
			$rs['Name'] = replace_params($rs['Name'], rolle::$layer_params);
			$rs['alias'] = replace_params($rs['alias'], rolle::$layer_params);
			$rs['connection'] = replace_params($rs['connection'], rolle::$layer_params);
			$rs['classification'] = replace_params($rs['classification'], rolle::$layer_params);
			if ($withClasses == 2 OR $rs['requires'] != '' OR ($withClasses == 1 AND $rs['aktivStatus'] != '0')) {
				# bei withclasses == 2 werden für alle Layer die Klassen geladen,
				# bei withclasses == 1 werden Klassen nur dann geladen, wenn der Layer aktiv ist
				$rs['Class']=$this->read_Classes($rs['Layer_ID'], $this->disabled_classes, false, $rs['classification']);
			}
			if($rs['maxscale'] > 0)$rs['maxscale'] = $rs['maxscale']+0.3;
			if($rs['minscale'] > 0)$rs['minscale'] = $rs['minscale']-0.3;
			$layer['list'][$i]=$rs;			
			$layer['layer_ids'][$rs['Layer_ID']] =& $layer[$i];		# damit man mit einer Layer-ID als Schlüssel auf dieses Array zugreifen kann
			$i++;
    }
    return $layer;
  }

  function read_disabled_classes(){
  	#Anne
    $sql_classes = 'SELECT class_id, status FROM u_rolle2used_class WHERE user_id='.$this->User_ID.' AND stelle_id='.$this->Stelle_ID.';';
    $query_classes=mysql_query($sql_classes);
    while($row = mysql_fetch_assoc($query_classes)){
  		$classarray['class_id'][] = $row['class_id'];
			$classarray['status'][$row['class_id']] = $row['status'];
		}
		return $classarray;
  }

	function read_Classes($Layer_ID, $disabled_classes = NULL, $all_languages = false, $classification = '') {
		global $language;

		$sql = "
			SELECT " .
				((!$all_languages AND $language != 'german') ? "
					CASE
						WHEN `Name_" . $language . "`IS NOT NULL THEN `Name_" . $language . "`
						ELSE `Name`
					END" : "
					`Name`"
				) . " AS Name,
				`Name_low-german`,
				`Name_english`,
				`Name_polish`,
				`Name_vietnamese`,
				`Class_ID`,
				`Layer_ID`,
				`Expression`,
				`classification`,
				`legendgraphic`,
				`legendimagewidth`,
				`legendimageheight`,
				`drawingorder`,
				`legendorder`,
				`text`
			FROM
				`classes`
			WHERE
				`Layer_ID` = " . $Layer_ID .
				(
					(!empty($classification)) ? " AND
						(
							classification IS NULL OR classification IN ('', '" . $classification . "')
						)
					" : ""
				) . "
			ORDER BY
				classification,
				drawingorder,
				Class_ID
		";
		#echo $sql.'<br>';
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Class - Lesen der Classen eines Layers:<br>" . $sql, 4);
		$query = mysql_query($sql);
		if ($query == 0) { echo "<br>Abbruch in " . $PHP_SELF . " Zeile: " . __LINE__; return 0; }
		$index = 0;
		while ($rs = mysql_fetch_assoc($query)) {
			$rs['Style'] = $this->read_Styles($rs['Class_ID']);
			$rs['Label'] = $this->read_Label($rs['Class_ID']);
			$rs['index'] = $index;
			#Anne
			if($disabled_classes){
				if($disabled_classes['status'][$rs['Class_ID']] == 2) {
					$rs['Status'] = 1;
					for($i = 0; $i < count($rs['Style']); $i++) {
						if ($rs['Style'][$i]['color'] != '' AND $rs['Style'][$i]['color'] != '-1 -1 -1') {
							$rs['Style'][$i]['outlinecolor'] = $rs['Style'][$i]['color'];
							$rs['Style'][$i]['color'] = '-1 -1 -1';
							if($rs['Style'][$i]['width'] == '') $rs['Style'][$i]['width'] = 3;
							if($rs['Style'][$i]['minwidth'] == '') $rs['Style'][$i]['minwidth'] = 2;
							if($rs['Style'][$i]['maxwidth'] == '') $rs['Style'][$i]['maxwidth'] = 4;
							$rs['Style'][$i]['symbolname'] = '';
						}
					}
				}
				elseif ($disabled_classes['status'][$rs['Class_ID']] == '0') {
					$rs['Status'] = 0;
				}
				else $rs['Status'] = 1;
			}
			else $rs['Status'] = 1;

			$Classes[] = $rs;
			$index++;
		}
		return $Classes;
	}

  function read_Styles($Class_ID) {
    $sql ='SELECT * FROM styles AS s,u_styles2classes AS s2c';
    $sql.=' WHERE s.Style_ID=s2c.style_id AND s2c.class_id='.$Class_ID;
    $sql.=' ORDER BY drawingorder';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Styles - Lesen der Styledaten:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    while($rs=mysql_fetch_assoc($query)) {
      $Styles[]=$rs;
    }
    return $Styles;
  }

  function read_Label($Class_ID) {
    $sql ='SELECT * FROM labels AS l,u_labels2classes AS l2c';
    $sql.=' WHERE l.Label_ID=l2c.label_id AND l2c.class_id='.$Class_ID;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Label - Lesen der Labels zur Classe eines Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    while ($rs=mysql_fetch_assoc($query)) {
      $Labels[]=$rs;
    }
    return $Labels;
  }

  function read_RollenLayer($id = NULL, $typ = NULL){
		$sql = "SELECT DISTINCT l.*, l.Name as alias, g.Gruppenname, -l.id AS Layer_ID, 1 as showclasses, CASE WHEN Typ = 'import' THEN 1 ELSE 0 END as queryable from rollenlayer AS l, u_groups AS g";
    $sql.= ' WHERE l.Gruppe = g.id AND l.stelle_id='.$this->Stelle_ID.' AND l.user_id='.$this->User_ID;
    if($id != NULL){
    	$sql .= ' AND l.id = '.$id;
    }
  	if($typ != NULL){
    	$sql .= ' AND l.Typ = \''.$typ.'\'';
    }
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_RollenLayer - Lesen der RollenLayer:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    $Layer = array();
    while ($rs=mysql_fetch_array($query)) {
      $rs['Class']=$this->read_Classes(-$rs['id'], $this->disabled_classes);
      $Layer[]=$rs;
    }
    return $Layer;
  }
}
?>
