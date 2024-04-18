<?
function replace_semicolon($text) {
	return str_replace(';', '', $text);
}

function rectObj($minx, $miny, $maxx, $maxy, $imageunits = 0){
	if (MAPSERVERVERSION >= 800) {
		return new RectObj($minx, $miny, $maxx, $maxy, $imageunits);
	}
	else {
		$rect = new RectObj();
		$rect->setextent($minx, $miny, $maxx, $maxy);
		return $rect;
	}
}

function umlaute_umwandeln($name) {
	$name = str_replace('ä', 'ae', $name);
	$name = str_replace('ü', 'ue', $name);
	$name = str_replace('ö', 'oe', $name);
	$name = str_replace('Ä', 'Ae', $name);
	$name = str_replace('Ü', 'Ue', $name);
	$name = str_replace('Ö', 'Oe', $name);
	$name = str_replace('a?', 'ae', $name);
	$name = str_replace('u?', 'ue', $name);
	$name = str_replace('o?', 'oe', $name);
	$name = str_replace('A?', 'ae', $name);
	$name = str_replace('U?', 'ue', $name);
	$name = str_replace('O?', 'oe', $name);
	$name = str_replace('ß', 'ss', $name);
	$name = str_replace('.', '', $name);
	$name = str_replace(':', '', $name);
	$name = str_replace('(', '', $name);
	$name = str_replace(')', '', $name);
	$name = str_replace('[', '', $name);
	$name = str_replace(']', '', $name);
	$name = str_replace('/', '-', $name);
	$name = str_replace(' ', '_', $name);
	$name = str_replace('-', '_', $name);
	$name = str_replace('?', '_', $name);
	$name = str_replace('+', '_', $name);
	$name = str_replace(',', '_', $name);
	$name = str_replace('*', '_', $name);
	$name = str_replace('$', '', $name);
	$name = str_replace('&', '_', $name);
	$name = str_replace('#', '_', $name);
	$name = iconv("UTF-8", "UTF-8//IGNORE", $name);
	return $name;
}

function url_get_contents($url, $username = NULL, $password = NULL, $useragent = NULL) {
	$hostname = parse_url($url, PHP_URL_HOST);
	try {
		$ctx['http']['timeout'] = 20;
		#$ctx['http']['header'] = 'Referer: http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];		// erstmal wieder rausgenommen, da sonst Authorization nicht funktioniert
		if ($useragent) {
			$ctx['http']['header'] = 'User-Agent: ' . $useragent;
		}
		if ($username) {
			$ctx['http']['header'].= "Authorization: Basic ".base64_encode($username . ':' . $password);
		}
		$proxy = getenv('HTTP_PROXY');
		if ($proxy != '' AND $hostname != 'localhost') {
			$ctx['http']['proxy'] = $proxy;
			$ctx['http']['request_fulluri'] = true;
			$ctx['ssl']['SNI_server_name'] = $hostname;
			$ctx['ssl']['SNI_enabled'] = true;
		}
		$context = stream_context_create($ctx);
		$response =  @file_get_contents($url, false, $context);
		if ($response === false) {
			$error = 'Fehler beim Abfragen der URL mit file_get_contents(' . $url . ')';
			GUI::add_message_('error', $error);
			throw new Exception($error);
		}
	}
	catch (Exception $e) {
		$response = curl_get_contents($url, $username, $password);
	}
	return $response;
}

function sanitize(&$value, $type) {
	switch ($type) {
		case 'int' : {
			$value = (int) $value;
		} break;
		case 'text' : {
			$value = pg_escape_string($value);
		} break;
		default : {
			// let $value as it is
		}
	}
	return $value;
}

function value_of($array, $key) {
	if(!is_array($array))$array = array();
	return (array_key_exists($key, $array) ? $array[$key] :	'');
}

function compare_legendorder($a, $b){
	if($a['legendorder'] > $b['legendorder'])return 1;
	else return 0;
}

function replace_params($str, $params, $user_id = NULL, $stelle_id = NULL, $hist_timestamp = NULL, $language = NULL, $duplicate_criterion = NULL) {
	if (!is_null($duplicate_criterion))	$str = str_replace('$duplicate_criterion', $duplicate_criterion, $str);
	if (is_array($params)) {
		foreach($params AS $key => $value){
			$str = str_replace('$'.$key, $value, $str);
		}
	}
	if (!is_null($user_id))							$str = str_replace('$user_id', $user_id, $str);
	if (!is_null($stelle_id))						$str = str_replace('$stelle_id', $stelle_id, $str);
	if (!is_null($hist_timestamp))			$str = str_replace('$hist_timestamp', $hist_timestamp, $str);
	if (!is_null($language))						$str = str_replace('$language', $language, $str);
	return $str;
}

function replace_params_link($str, $params, $layer_id) {
	if (is_array($params)) {
		foreach($params AS $key => $value){
			$str = str_replace('$'.$key, '<a href="javascript:void(0)" onclick="getLayerOptions(' . $layer_id .  ')">' . $value . '</a>', $str);
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


class GUI {

  var $alert;
  var $gui;
  var $layout;
  var $style;
  var $mime_type;
  var $menue;
  var $pdf;
  var $addressliste;
  var $debug;
  var $mysqli;
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
  var $login_failed;
  var $only_main;
  var $class_load_level;
  var $layer_id_string;
  var $noMinMaxScaling;
  var $stelle_id;
  var $angle_attribute;
  var $titel;
  var $PasswordError;
  var $Meldung;
  var $radiolayers;

	function __construct($main, $style, $mime_type) {
		# Debugdatei setzen
		global $debug;
		$this->debug = $debug;

		# Logdatei für Mysql setzen
		global $log_mysql;
		$this->log_mysql = $log_mysql;

		# Logdatei für PostgreSQL setzten
		global $log_postgres;
		$this->log_postgres = $log_postgres;

		global $log_loginfail;
		$this->log_loginfail = $log_loginfail;

		# layout Templatedatei zur Anzeige der Daten
		if ($main != "") {
			$this->main = $main;
		}

		# Stylesheetdatei
		if (isset($style)) {
			$this->style = $style;
		}

		# mime_type html, pdf
		if (isset ($mime_type)) $this->mime_type=$mime_type;
		$this->scaleUnitSwitchScale = 239210;
		$this->trigger_functions = array();
	}

	function sanitize($vars) {
		foreach ($vars as $name => $type) {
			sanitize($this->formvars[$name], $type);
		}
	}

	function plugin_loaded($plugin) {
		global $kvwmap_plugins;
		return in_array($plugin, $kvwmap_plugins);
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
		for($i = 0; $i < @count($this->layers_replace_scale ?: []); $i++){
			$this->layers_replace_scale[$i]->set('data', str_replace('$scale', $this->map_scaledenom, $this->layers_replace_scale[$i]->data));
		}
    echo $this->create_group_legend($this->formvars['group'], $this->formvars['status']);
  }

  function loadMap($loadMapSource, $layerset = array(), $strict_layer_name = false) {
		$this->group_has_active_layers = array();
    $this->debug->write("<p>Funktion: loadMap('" . $loadMapSource . ")",4);
    switch ($loadMapSource) {
      # lade Karte aus Post-Parametern
      case 'Post' : {
        if (MAPSERVERVERSION < 600) {
				  $map = ms_newMapObj(SHAPEPATH.'MapFiles/tk_niedersachsen.map');
				}
				else {
				  $map = new mapObj(SHAPEPATH.'MapFiles/tk_niedersachsen.map');
				}
				#echo '<br>MapServer Version: '.ms_GetVersionInt();
				#echo '<br>Details: '.ms_GetVersion();

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
        $map->web->metadata->set('ows_title', 'WMS Ausdruck');
        $map->web->metadata->set('wms_extent',$this->formvars['post_minx'].''.$this->formvars['post_miny'].' '.$this->formvars['post_maxx'].' '.$this->formvars['post_maxy']);

        # Legendobject
        $map->legend->set('status', MS_ON);
        #$map->legend->set('transparent', MS_OFF);
        $map->legend->set('keysizex', '16');
        $map->legend->set('keysizey', '16');
        $map->legend->set('template', LAYOUTPATH . 'legend_layer.htm');
        $map->legend->imagecolor -> setRGB(255,255,255);
        $map->legend->outlinecolor -> setRGB(-1,-1,-1);
        $map->legend->label->set('type', MS_TRUETYPE);
        $map->legend->label->set('font', 'arial');
        $map->legend->label->set('size', 12);
        $map->legend->label->color->setRGB(5,30,220);

        # layer
        if (is_array($this->formvars['layer'])) {
          $layerset = array_values($this->formvars['layer']);
        }
        for ($i = 0; $i < count($layerset); $i++) {
				  if (MAPSERVERVERSION < 600) {
            $layer = ms_newLayerObj($map);
          }
					else {
					  $layer = new layerObj($map);
					}
					$layer->metadata->set('wms_name', $layerset[$i][name]);
          $layer->metadata->set('wms_server_version','1.1.1');
          $layer->metadata->set('wms_format','image/png');
          $layer->metadata->set('wms_extent',$this->formvars['post_minx'].' '.$this->formvars['post_miny'].' '.$this->formvars['post_maxx'].' '.$this->formvars['post_maxy']);
          $layer->metadata->set('ows_title', $layerset[$i][name]);
          if($layerset[$i][epsg_code] != ''){
            $layer->metadata->set('ows_srs', $layerset[$i][epsg_code]);
          }
          else{
            $layer->metadata->set('ows_srs', $this->formvars['post_epsg']);
          }
          $layer->metadata->set('wms_exceptions_format', 'application/vnd.ogc.se_inimage');
          $layer->metadata->set('real_layer_status', 1);
          $layer->metadata->set('off_requires',0);
          $layer->metadata->set('wms_connectiontimeout',60);
          $layer->metadata->set('wms_queryable',0);
          $layer->metadata->set('wms_group_title','WMS');
          $layer->set('type', 3);
          $layer->set('name', $layerset[$i][name]);
          $layer->set('status', 1);
          if($this->map_factor == ''){
            $this->map_factor=1;
          }
          if($layerset[$i]['maxscale'] > 0) {
            $layer->set('maxscaledenom', $layerset[$i]['maxscale']/$this->map_factor*1.414);
          }
          if($layerset[$i]['minscale'] > 0) {
						$layer->set('minscaledenom', $layerset[$i]['minscale']/$this->map_factor*1.414);
          }
          if($layerset[$i][epsg_code] != ''){
            $layer->setProjection('+init='.strtolower($layerset[$i][epsg_code])); # recommended
          }
          else{
            $layer->setProjection('+init='.strtolower($this->formvars['post_epsg']));
          }

					#$layer->set('connection',"http://www.kartenserver.niedersachsen.de/wmsconnector/com.esri.wms.Esrimap/Biotope?LAYERS=7&REQUEST=GetMap&TRANSPARENT=true&FORMAT=image/png&SERVICE=WMS&VERSION=1.1.1&STYLES=&EXCEPTIONS=application/vnd.ogc.se_xml&SRS=EPSG:31467");
					#echo '<br>Name: '.$layerset[$i][name];
					$layer->set('connection',	$layerset[$i][connection]);
					#echo '<br>Connection: ' . replace_params($layerset[$i][connection], rolle::$layer_params);
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
        $this->debug->write("MapDatei $connStr laden",4);
				if (MAPSERVERVERSION < 600) {
          $this->map = ms_newMapObj(DEFAULTMAPFILE);
        }
				else {
				  $this->map = new mapObj(DEFAULTMAPFILE);
				}
			} break;

      # lade Karte von Datenbank
      case 'DataBase' : {
				$this->debug->write('<br>Lade Defaultmapfile: ' . DEFAULTMAPFILE, 4);
				if (MAPSERVERVERSION < 600) {
					$map = ms_newMapObj(DEFAULTMAPFILE);
				}
				else {
					$map = new mapObj(DEFAULTMAPFILE);
				}

        $mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
				$num_default_layers = $map->numlayers;

				# Allgemeine Parameter
				define('MINIMAGESIZE', 10); # prevent error in setextent
				$map->resolution = 96;
				#$map->set('transparent', MS_OFF);
				#$map->set('interlace', MS_ON);
				$map->status = MS_ON;
				$map->name = MAPFILENAME;

				if (MS_DEBUG_LEVEL > 0) {
					$map->setConfigOption('MS_ERRORFILE', '/var/www/logs/mapserver.log');
					$map->debug = MS_DEBUG_LEVEL;
				};
				$map->imagecolor->setRGB(255,255,255);
				$map->maxsize = 4096;
				$map->setProjection('+init=epsg:' . $this->user->rolle->epsg_code);

				$map->setSize(
					($this->user->rolle->nImageWidth < MINIMAGESIZE ? MINIMAGESIZE : $this->user->rolle->nImageWidth),
					($this->user->rolle->nImageHeight < MINIMAGESIZE ? MINIMAGESIZE : $this->user->rolle->nImageHeight)
				);

				$bb = $this->Stelle->MaxGeorefExt;

				# setzen der Kartenausdehnung über die letzten Benutzereinstellungen
				if ($this->user->rolle->oGeorefExt->minx==='') {
				  echo "Richten Sie mit phpMyAdmin in der kvwmap Datenbank eine Referenzkarte, eine Stelle, einen Benutzer und eine Rolle ein ";
				  echo "<br>(Tabellen referenzkarten, stelle, user, rolle) ";
				  echo "<br>oder wenden Sie sich an ihren Systemverwalter.";
				  exit;
				}
				else {
					if ($this->user->rolle->oGeorefExt->minx < $this->Stelle->MaxGeorefExt->minx)			$this->user->rolle->oGeorefExt->minx = $this->Stelle->MaxGeorefExt->minx;
					if ($this->user->rolle->oGeorefExt->miny < $this->Stelle->MaxGeorefExt->miny)			$this->user->rolle->oGeorefExt->miny = $this->Stelle->MaxGeorefExt->miny;
					if ($this->user->rolle->oGeorefExt->maxx > $this->Stelle->MaxGeorefExt->maxx)			$this->user->rolle->oGeorefExt->maxx = $this->Stelle->MaxGeorefExt->maxx;
					if ($this->user->rolle->oGeorefExt->maxy > $this->Stelle->MaxGeorefExt->maxy)			$this->user->rolle->oGeorefExt->maxy = $this->Stelle->MaxGeorefExt->maxy;
					if ($this->user->rolle->oGeorefExt->maxx <= $this->user->rolle->oGeorefExt->minx) $this->user->rolle->oGeorefExt->maxx = $this->user->rolle->oGeorefExt->minx + 1;
					if ($this->user->rolle->oGeorefExt->maxy <= $this->user->rolle->oGeorefExt->miny) $this->user->rolle->oGeorefExt->maxy = $this->user->rolle->oGeorefExt->miny + 1;
					if (value_of($this->formvars, 'go') != 'OWS') {
						$map->setextent($this->user->rolle->oGeorefExt->minx,$this->user->rolle->oGeorefExt->miny,$this->user->rolle->oGeorefExt->maxx,$this->user->rolle->oGeorefExt->maxy);
					}
					else {
						$map->setextent($bb->minx, $bb->miny, $bb->maxx, $bb->maxy);
					}
				}



				# OWS Metadaten
				$map->web->metadata->set("ows_title", $this->Stelle->ows_title ?: OWS_TITLE);
				$map->web->metadata->set("ows_abstract", $this->Stelle->ows_abstract ?: OWS_ABSTRACT);
				$map->web->metadata->set("ows_accessconstraints", $this->Stelle->wms_accessconstraints ?: OWS_ACCESSCONSTRAINTS);
				$map->web->metadata->set("ows_contactorganization", $this->Stelle->ows_contactorganization ?: OWS_CONTACTORGANIZATION);
				$map->web->metadata->set("ows_contactperson", $this->Stelle->ows_contactperson ?: OWS_CONTACTPERSON);
				$map->web->metadata->set("ows_contactposition", $this->Stelle->ows_contactposition ?: OWS_CONTACTPOSITION);
				$map->web->metadata->set("ows_contactelectronicmailaddress", $this->Stelle->ows_contactelectronicmailaddress ?: OWS_CONTACTELECTRONICMAILADDRESS);
				$map->web->metadata->set("ows_contactvoicetelephone", $this->Stelle->ows_contactvoicephone ?: OWS_CONTACTVOICETELEPHONE);
				$map->web->metadata->set("ows_contactfacsimiletelephone", $this->Stelle->ows_contactfacsimile ?: OWS_CONTACTFACSIMILETELEPHONE);
				$map->web->metadata->set("ows_stateorprovince", $this->Stelle->ows_contactadministrativearea ?: OWS_STATEORPROVINCE);
				$map->web->metadata->set("ows_address", $this->Stelle->ows_contactaddress ?: OWS_ADDRESS);
				$map->web->metadata->set("ows_postcode", $this->Stelle->ows_contactpostalcode ?: OWS_POSTCODE);
				$map->web->metadata->set("ows_city", $this->Stelle->ows_contactcity ?: OWS_CITY);
				$map->web->metadata->set("ows_country", OWS_COUNTRY);
				$map->web->metadata->set("ows_addresstype", 'postal');
				$map->web->metadata->set("ows_fees", $this->Stelle->ows_fees ?: OWS_FEES);
				$map->web->metadata->set("ows_encoding", 'UTF-8');
				$map->web->metadata->set("ows_keywordlist", OWS_KEYWORDLIST);
				$map->web->metadata->set("ows_contactinstructions", OWS_CONTACTINSTRUCTIONS);
				$map->web->metadata->set("ows_hoursofservice", OWS_HOURSOFSERVICE);
				$map->web->metadata->set("ows_role", OWS_ROLE);
				$map->web->metadata->set("ows_srs", $this->Stelle->ows_srs ?: OWS_SRS);
				if (value_of($_REQUEST, 'onlineresource') != '') {
					$ows_onlineresource = $_REQUEST['onlineresource'];
				}
				else {
					$ows_onlineresource = OWS_SERVICE_ONLINERESOURCE . '&Stelle_ID=' . $this->Stelle->id .'&login_name=' . value_of($_REQUEST, 'login_name') . '&passwort=' .  urlencode(value_of($_REQUEST, 'passwort'));
				}
				$map->web->metadata->set("ows_onlineresource", $ows_onlineresource);
				$map->web->metadata->set("ows_service_onlineresource", $ows_onlineresource);

				$map->web->metadata->set("wms_extent", $bb->minx . ' ' . $bb->miny . ' ' . $bb->maxx . ' ' . $bb->maxy);
				// enable service types
				$map->web->metadata->set("ows_enable_request", '*');

        ///------------------------------////

        $map->setSymbolSet(SYMBOLSET);
        $map->setFontSet(FONTSET);
        $map->shapepath = SHAPEPATH;

        # Umrechnen des Stellenextents kann hier raus, weil es schon in start.php gemacht wird

        # Webobject
        $map->web->imagepath = IMAGEPATH;
        $map->web->imageurl = IMAGEURL;
        $map->web->log = LOGPATH . 'mapserver.log';
        $map->web->metadata->set('wms_feature_info_mime_type', 'text/html');
        //$map->web->set('ERRORFILE', LOGPATH.'mapserver_error.log');

        # Referenzkarte
				$reference_map = new mapObj(DEFAULTMAPFILE);
				$this->ref=$mapDB->read_ReferenceMap();
				if (!is_array($this->ref)) {
					$this->ref = array(
						'epsg_code' => 25833,
						'refMapImg' => WWWROOT . APPLVERSION . GRAPHICSPATH . 'karte.png'
					);
				}
				else {
					$this->ref['refMapImg'] = REFERENCEMAPPATH.$this->ref['Dateiname'];
				}
				$reference_map->web->imagepath = IMAGEPATH;
				$reference_map->setProjection('+init=epsg:' . $this->ref['epsg_code']);
				$reference_map->reference->extent->minx = round($this->ref['xmin']);
				$reference_map->reference->extent->miny = round($this->ref['ymin']);
				$reference_map->reference->extent->maxx = round($this->ref['xmax']);
				$reference_map->reference->extent->maxy = round($this->ref['ymax']);
				$reference_map->reference->image = $this->ref['refMapImg'];
        $reference_map->reference->width = $this->ref['width'];
        $reference_map->reference->height = $this->ref['height'];
        $reference_map->reference->status = MS_ON;
				$reference_map->reference->color->setRGB(-1,-1,-1);
				$reference_map->reference->outlinecolor->setRGB(255,0,0);

        # Scalebarobject
        $map->scalebar->status = MS_ON;
        $map->scalebar->units =  MS_METERS;
        $map->scalebar->intervals = 4;
        $map->scalebar->color->setRGB(0,0,0);
        $r = substr(BG_MENUETOP, 1, 2);
        $g = substr(BG_MENUETOP, 3, 2);
        $b = substr(BG_MENUETOP, 5, 2);
        $map->scalebar->imagecolor->setRGB(hexdec($r), hexdec($g), hexdec($b));
        $map->scalebar->outlinecolor->setRGB(0,0,0);
				$map->scalebar->label->font = 'SourceSansPro';		# Kommentarzeichen wieder entfernt, da sonst auf Metropolplaner Fehler
				if (MAPSERVERVERSION < 700 ) {
					$map->scalebar->label->type = 'truetype';
				}
				$map->scalebar->label->size = 10.5;

				# Groups
				if (value_of($this->formvars, 'nurAufgeklappteLayer') == '') {
					$this->groupset = $mapDB->read_Groups();
				}

				# Filter für read_Layer
				$mapDB->nurAktiveLayer = value_of($this->formvars, 'nurAktiveLayer');
				$mapDB->nurAufgeklappteLayer = value_of($this->formvars, 'nurAufgeklappteLayer');
				$mapDB->nurFremdeLayer = value_of($this->formvars, 'nurFremdeLayer');
				$mapDB->nurNameLike = value_of($this->formvars, 'nurNameLike');
				$mapDB->nurPostgisLayer = value_of($this->formvars, 'only_postgis_layer');
				$mapDB->keinePostgisLayer = value_of($this->formvars, 'no_postgis_layer');
				$mapDB->nurLayerID = value_of($this->formvars, 'only_layer_id');
				$mapDB->nurLayerIDs = value_of($this->formvars, 'only_layer_ids');
				$mapDB->nichtLayerID = value_of($this->formvars, 'not_layer_id');

        if ($this->class_load_level == '') {
          $this->class_load_level = 1;
        }

				if (count($layerset) == 0) {
					$layerset = $mapDB->read_Layer($this->class_load_level, $this->Stelle->useLayerAliases, $this->list_subgroups(value_of($this->formvars, 'group')));
					$rollenlayer = $mapDB->read_RollenLayer();
					$layerset['list'] = array_merge($layerset['list'], $rollenlayer);
					$layerset['anzLayer'] = count($layerset['list']);
				}
        unset($this->layer_ids_of_group);		# falls loadmap zweimal aufgerufen wird
				$layerset['layer_group_has_legendorder'] = array();
				for ($i = 0; $i < $layerset['anzLayer']; $i++) {
					$layerset['layers_of_group'][$layerset['list'][$i]['Gruppe']][] = $i;
					if(value_of($layerset['list'][$i], 'legendorder') != ''){
						$layerset['layer_group_has_legendorder'][$layerset['list'][$i]['Gruppe']] = true;
					}
					if(value_of($layerset['list'][$i], 'requires') == ''){
						$this->layer_ids_of_group[$layerset['list'][$i]['Gruppe']][] = $layerset['list'][$i]['Layer_ID'];				# die Layer-IDs in einer Gruppe
					}
					$this->layer_id_string .= $layerset['list'][$i]['Layer_ID'].'|';							# alle Layer-IDs hintereinander in einem String

					if (value_of($layerset['list'][$i], 'requires') != '') {
						$layerset['list'][$i]['aktivStatus'] = $layerset['layer_ids'][$layerset['list'][$i]['requires']]['aktivStatus'];
						$layerset['list'][$i]['showclasses'] = $layerset['layer_ids'][$layerset['list'][$i]['requires']]['showclasses'];
					}

					if ($this->class_load_level == 2 OR ($this->class_load_level == 1 AND $layerset['list'][$i]['aktivStatus'] != 0)) {
						# nur wenn der Layer aktiv ist, sollen seine Parameter gesetzt werden
						$layerset['list'][$i]['layer_index_mapobject'] = $map->numlayers;
						$this->loadlayer($map, $layerset['list'][$i], $strict_layer_name);
					}
				}
				$this->layerset = $layerset;
				if ($num_default_layers > 0 AND $map->numlayers > $num_default_layers) {
					#$map->setLayersDrawingOrder($this->get_default_layers_top_drawing_order($map->numlayers, $num_default_layers)); geht wohl so in SwigMapscript nicht
				}
				$this->map = $map;
				$this->reference_map = $reference_map;
				if (MAPSERVERVERSION >= 600 ) {
					$this->map_scaledenom = $map->scaledenom;
				}
				else {
					$this->map_scaledenom = $map->scale;
				}
				$this->mapDB = $mapDB;
			} break; # end of lade Karte von Datenbank
		} # end of switch loadMapSource
		return 1;
	}

	function list_subgroups($groupid){
		$subgroups = '';
		if($groupid != ''){
			$group = $this->groupset[$groupid];
			if(value_of($group, 'untergruppen') != ''){
				foreach($group['untergruppen'] as $untergruppe){
					$subgroups .= ', '.$this->list_subgroups($untergruppe);
				}
				return $groupid.$subgroups;
			}
			else return $groupid;
		}
	}

	function loadlayer($map, $layerset, $strict_layer_name = false) {
		$this->debug->write('<br>Lade Layer: ' . $layerset['Name'], 4);
		if ($strict_layer_name) {
			$layer_name_attribute = 'Name';
		}
		else {
			$layer_name_attribute = 'Name_or_alias';
		}
		$layer = ms_newLayerObj($map);
		$layer->set('name', $layerset[$layer_name_attribute]);
		$layer->metadata->set('wms_name', $layerset['wms_name']);
		$layer->metadata->set('kvwmap_layer_id', $layerset['Layer_ID']);
		$layer->metadata->set('wfs_request_method', 'GET');
		if ($layerset['wms_keywordlist']) {
			$layer->metadata->set('ows_keywordlist', $layerset['wms_keywordlist']);
		}
		$layer->metadata->set('wfs_typename', $layerset['wms_name']);
		$layer->metadata->set('ows_title', $layerset['Name_or_alias']); # required
		$layer->metadata->set('wms_group_title', $layerset['Gruppenname']);
		$layer->metadata->set('wms_queryable',$layerset['queryable']);
		$layer->metadata->set('wms_format',$layerset['wms_format']);
		$layer->metadata->set('ows_server_version',$layerset['wms_server_version']);
		$layer->metadata->set('ows_version',$layerset['wms_server_version']);
		if ($layerset['metalink']) {
			$layer->metadata->set('ows_metadataurl_href',$layerset['metalink']);
			$layer->metadata->set('ows_metadataurl_type', 'ISO 19115');
			$layer->metadata->set('ows_metadataurl_format', 'text/plain');
		}
		if ($layerset['ows_srs'] == '') {
			$layerset['ows_srs'] = 'EPSG:' . $layerset['epsg_code'];
		}
		$layer->metadata->set('ows_srs', $layerset['ows_srs']);
		$layer->metadata->set('wms_connectiontimeout',$layerset['wms_connectiontimeout']);
		$layer->metadata->set('ows_auth_username', $layerset['wms_auth_username']);
		$layer->metadata->set('ows_auth_password', $layerset['wms_auth_password']);
		$layer->metadata->set('ows_auth_type', 'basic');
		$layer->metadata->set('wms_exceptions_format', ($layerset['wms_server_version'] == '1.3.0' ? 'XML' : 'application/vnd.ogc.se_xml'));
		# ToDo: das Setzen von ows_extent muss in dem System erfolgen, in dem der Layer definiert ist (erstmal rausgenommen)
		#$layer->metadata->set("ows_extent", $bb->minx . ' '. $bb->miny . ' ' . $bb->maxx . ' ' . $bb->maxy);		# führt beim WebAtlas-WMS zu einem Fehler
		$layer->metadata->set("gml_featureid", $layerset['oid']);
		$layer->metadata->set("gml_include_items", "all");
		$layer->metadata->set('wms_abstract', $layerset['kurzbeschreibung']);
		$layer->set('dump', 0);
		$layer->set('type',$layerset['Datentyp']);
		$layer->set('group', umlaute_umwandeln($layerset['Gruppenname']));

		if(value_of($layerset, 'status') != ''){
			$layerset['aktivStatus'] = 0;
		}

		//---- wenn die Layer einer eingeklappten Gruppe nicht in der Karte //
		//---- dargestellt werden sollen, muß hier bei aktivStatus != 1 //
		//---- der layer_status auf 0 gesetzt werden//
		if ($layerset['aktivStatus'] == 0) {
			$layer->set('status', 0);
		}
		else{
			$layer->set('status', 1);
		}

		# fremde Layer werden auf Verbindung getestet
		if ($layerset['aktivStatus'] != 0 AND $layerset['connectiontype'] == 6) {
			$credentials = $this->pgdatabase->get_credentials($layerset['connection_id']);
			if (!in_array($credentials['host'], array('pgsql', 'localhost'))) {
				$fp = @fsockopen($credentials['host'], $credentials['port'], $errno, $errstr, 5);
				if (!$fp) {			# keine Verbindung --> Layer ausschalten
					$layer->set('status', 0);
					$layer->metadata->set('queryStatus', 0);
					$this->Fehlermeldung = $errstr.' für Layer: '.$layerset['Name'].'<br>';
				}
			}
		}

		if($layerset['aktivStatus'] != 0){
			$collapsed = false;
			if($group = value_of($this->groupset, $layerset['Gruppe'])){				# die Gruppe des Layers
				if($group['status'] == 0){
					$this->group_has_active_layers[$layerset['Gruppe']] = 1;  	# die zugeklappte Gruppe hat aktive Layer
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
		}

		if(!$this->noMinMaxScaling AND value_of($layerset, 'minscale') >= '0') {
			if($this->map_factor != ''){
				$layer->set('minscaledenom', $layerset['minscale']/$this->map_factor*1.414);
			}
			else{
				$layer->set('minscaledenom', $layerset['minscale']);
			}
		}
		if(!$this->noMinMaxScaling AND value_of($layerset, 'maxscale') > 0) {
			if($this->map_factor != ''){
				$layer->set('maxscaledenom', $layerset['maxscale']/$this->map_factor*1.414);
			}
			else{
				$layer->set('maxscaledenom', $layerset['maxscale']);
			}
		}
		$layer->setProjection('+init=epsg:' . $layerset['epsg_code']); # recommended
		if ($layerset['connection']!='') {
			if($layerset['connectiontype'] == 7) { # WMS-Layer
				# $layerset['connection'] .= '&SERVICE=WMS'; # Das kann zu Fehler führen. MapServer setzt selber SERVICE=WMS
				if ($this->map_factor != '') {
					if ($layerset['printconnection']!=''){
						$layerset['connection'] = $layerset['printconnection']; 		# wenn es eine Druck-Connection gibt, wird diese verwendet
					}
					else{
						//$layerset['connection'] .= '&mapfactor='.$this->map_factor;			# bei WMS-Layern wird der map_factor durchgeschleift (für die eigenen WMS) erstmal rausgenommen, weil einige WMS-Server der zusätzliche Parameter mapfactor stört
					}
				}
			}
			if ($layerset['connectiontype'] == 6) {
				# z.B. für Klassen mit Umlauten
				$layerset['connection'] .= " options='-c client_encoding=UTF8'";
			}
			$layer->set('connection', $layerset['connection']);
		}

		if ($layerset['connectiontype'] > 0) {
			$layer->setConnectionType($layerset['connectiontype']);
		}

		if ($layerset['connectiontype'] == 6) {
			$layerset['processing'] = 'CLOSE_CONNECTION=ALWAYS;' . value_of($layerset, 'processing');		# DB-Connection erst am Ende schliessen und nicht für jeden Layer neu aufmachen
		}

		if ($layerset['processing'] != "") {
			$processings = explode(";",
				replace_params(
					$layerset['processing'],
					rolle::$layer_params,
					$this->user->id,
					$this->Stelle->id,
					rolle::$hist_timestamp,
					$this->user->rolle->language
				)
			);
			foreach ($processings as $processing) {
				$layer->setProcessing($processing);
			}
		}

		if (value_of($layerset, 'postlabelcache') != 0) {
			$layer->set('postlabelcache',$layerset['postlabelcache']);
		}

		if($layerset['Datentyp'] == MS_LAYER_POINT AND value_of($layerset, 'cluster_maxdistance') != ''){
			$layer->cluster->maxdistance = $layerset['cluster_maxdistance'];
			$layer->cluster->region = 'ellipse';
		}

		if ($layerset['Datentyp']=='3') {
			if($layerset['transparency'] != ''){
				if (MAPSERVERVERSION > 700) {
					$layer->updateFromString("LAYER COMPOSITE OPACITY ".$layerset['transparency']." END END");
				}
				else{
					$layer->set('opacity',$layerset['transparency']);
				}
			}
			if ($layerset['tileindex']!='') {
				$layer->set('tileindex',SHAPEPATH.$layerset['tileindex']);
			}
			else {
				$layer->set('data', $layerset['Data']);
			}
			$layer->set('tileitem',$layerset['tileitem']);
			if ($layerset['offsite']!='') {
				$RGB=explode(' ',$layerset['offsite']);
				$layer->offsite->setRGB($RGB[0],$RGB[1],$RGB[2]);
			}
		}
		else {
			# Vektorlayer
			if ($layerset['Data'] != '') {
				if(strpos($layerset['Data'], '$scale') !== false){
					$this->layers_replace_scale[] =& $layer;
				}
				$layer->set('data', $layerset['Data']);
			}
			
			if (value_of($layerset, 'buffer') != 0) {
				$geom = explode(' ', $layer->data)[0];
				$data = substr_replace($layer->data, 'geom1', 0, strlen($geom));
				$geography = (in_array($layerset['epsg_code'], [4326, 4258])? '::geography' : '');
				$layer->set('data', str_ireplace('select ', 'select st_buffer(' . $geom . $geography . ', ' . $layerset['buffer'] . ') as geom1, ', $data));
				$layer->data;
				$layer->set('type', 2);
			}

			# Setzen der Templatedateien für die Sachdatenanzeige inclt. Footer und Header.
			# Template (Body der Anzeige)
			if (value_of($this->formvars, 'go') == 'OWS') {
				$layer->set('template', 'dummy');
			}
			# Header (Kopfdatei)
			if (value_of($layerset, 'header') != '') {
				$layer->set('header',$layerset['header']);
			}
			# Footer (Fusszeile)
			if (value_of($layerset, 'footer') != '') {
				$layer->set('footer',$layerset['footer']);
			}
			# Setzen der Spalte nach der der Layer klassifiziert werden soll
			if ($layerset['classitem']!='') {
				$layer->set('classitem', $layerset['classitem']);
			}
			else {
				#$layer->set('classitem','id');
			}
			# Setzen des Filters
			if($layerset['Filter'] != ''){
				$layerset['Filter'] = str_replace('$userid', $this->user->id, $layerset['Filter']);
				if(substr($layerset['Filter'],0,1)=='('){
					if(MAPSERVERVERSION > 700){
						$layer->setProcessing('NATIVE_FILTER='.$layerset['Filter']);
					}
					else{
						$layer->setFilter($layerset['Filter']);
					}
			 }
			 else {
				 $expr=buildExpressionString($layerset['Filter']);
				 $layer->setFilter($expr);
			 }
			}
			if ($layerset['styleitem']!='') {
				$layer->set('styleitem',$layerset['styleitem']);
			}
			# Layerweite Labelangaben
			if ($layerset['labelitem']!='') {
				$layer->set('labelitem',$layerset['labelitem']);
			}
			if (value_of($layerset, 'labelmaxscale') != '') {
				$layer->set('labelmaxscaledenom',$layerset['labelmaxscale']);
			}
			if (value_of($layerset, 'labelminscale') != '') {
				$layer->set('labelminscaledenom',$layerset['labelminscale']);
			}
			if (value_of($layerset, 'labelrequires') != '') {
				$layer->set('labelrequires',$layerset['labelrequires']);
			}
			if (value_of($layerset, 'tolerance') != '') {
				$layer->set('tolerance',$layerset['tolerance']);
			}
			if (value_of($layerset, 'toleranceunits') != '') {
				$layer->set('toleranceunits', constant('MS_' . strtoupper($layerset['toleranceunits'])));
			}
			
			if (value_of($layerset, 'sizeunits') != '') {
				$layer->set('sizeunits', $layerset['sizeunits']);
			}
			if ($layerset['transparency']!=''){
				if ($layerset['transparency']==-1) {
						$layer->set('opacity',MS_GD_ALPHA);
				}
				else {
					if (MAPSERVERVERSION > 700) {
						$layer->updateFromString("LAYER COMPOSITE OPACITY ".$layerset['transparency']." END END");
					}
					else{
						$layer->set('opacity',$layerset['transparency']);
					}
				}
			}
			if (value_of($layerset, 'symbolscale') != '') {
				if($this->map_factor != ''){
					$layer->set('symbolscaledenom',$layerset['symbolscale']/$this->map_factor*1.414);
				}
				else{
					$layer->set('symbolscaledenom',$layerset['symbolscale']);
				}
			}
		} # ende of Vektorlayer
		$classset=$layerset['Class'];
		$this->loadclasses($layer, $layerset, $classset, $map);
	}

  function loadclasses($layer, $layerset, $classset, $map){
    $anzClass=@count($classset);
    for ($j=0;$j<$anzClass;$j++) {
      $klasse = new ClassObj($layer);
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
				$imagename = '../' . CUSTOM_PATH . 'graphics/' . $classset[$j]['legendgraphic'];
				$klasse->set('keyimage', $imagename);
			}
      for ($k=0;$k<@count($classset[$j]['Style']);$k++) {
        $dbStyle=$classset[$j]['Style'][$k];
				$style = new styleObj($klasse);
				if($dbStyle['geomtransform'] != ''){
					$style->updateFromString("STYLE GEOMTRANSFORM '" . $dbStyle['geomtransform']."' END");
				}
				if($dbStyle['minscale'] != ''){
					$style->set('minscaledenom', $dbStyle['minscale']);
				}
				if($dbStyle['maxscale'] != ''){
					$style->set('maxscaledenom', $dbStyle['maxscale']);
				}
				if (substr($dbStyle['symbolname'], 0, 1) == '[') {
					$style->updateFromString('STYLE SYMBOL ' .$dbStyle['symbolname']. ' END');
				}
				else {
					if ($dbStyle['symbolname']!='') {
						$style->set('symbolname',$dbStyle['symbolname']);
					}
					if ($dbStyle['symbol']>0) {
						$style->set('symbol',$dbStyle['symbol']);
					}
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
						if($this->map_factor != ''){
							$style->set('gap', $dbStyle['gap']*$this->map_factor/1.414);
						}
						else{
							$style->set('gap', $dbStyle['gap']);
						}
	        }
					if($dbStyle['initialgap'] != '') {
            $style->set('initialgap', $dbStyle['initialgap']);
          }
					if($dbStyle['linecap'] != '') {
	          $style->set('linecap', constant('MS_CJC_'.strtoupper($dbStyle['linecap'])));
	        }
					if($dbStyle['linejoin'] != '') {
	          $style->set('linejoin', constant('MS_CJC_'.strtoupper($dbStyle['linejoin'])));
	        }
					if($dbStyle['linejoinmaxsize'] != '') {
	          $style->set('linejoinmaxsize', $dbStyle['linejoinmaxsize']);
	        }
					if($dbStyle['polaroffset'] != '') {
	          $style->updateFromString("STYLE POLAROFFSET " . $dbStyle['polaroffset']." END");
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
				if($dbStyle['size'] != ''){
					if ($layerset['Datentyp'] == 8) {
						# Skalierung der Stylegröße when Type Chart
						$style->setbinding(MS_STYLE_BINDING_SIZE, $dbStyle['size']);
					}
					else {
						if($this->map_factor != '') {
							if(is_numeric($dbStyle['size']))$style->set('size', $dbStyle['size']*$this->map_factor/1.414);
							else $style->updateFromString("STYLE SIZE [" . $dbStyle['size']."] END");
						}
						else{
							if(is_numeric($dbStyle['size']))$style->set('size', $dbStyle['size']);
							else $style->updateFromString("STYLE SIZE [" . $dbStyle['size']."] END");
						}
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
					$style->updateFromString("STYLE ANGLE " . $dbStyle['angle']." END"); 		# wegen AUTO
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
				
        if ($dbStyle['color']!='') {
          $RGB = array_filter(explode(" ",$dbStyle['color']), 'strlen');
          if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
          if(is_numeric($RGB[0]))$style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
					else $style->updateFromString("STYLE COLOR [" . $dbStyle['color']."] END");
        }
				if($dbStyle['opacity'] != '') {		# muss nach color gesetzt werden
					$style->set('opacity', $dbStyle['opacity']);
				}
        if ($dbStyle['outlinecolor']!='') {
          $RGB = array_filter(explode(" ",$dbStyle['outlinecolor']), 'strlen');
        	if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
          $style->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
        }
        if ($dbStyle['backgroundcolor']!='') {
          $RGB = array_filter(explode(" ",$dbStyle['backgroundcolor']), 'strlen');
        	if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
          $style->backgroundcolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
        }
				if($dbStyle['colorrange'] != '') {
					$style->updateFromString("STYLE COLORRANGE " . $dbStyle['colorrange']." END");
				}
				if($dbStyle['datarange'] != '') {
					$style->updateFromString("STYLE DATARANGE " . $dbStyle['datarange']." END");
				}
				if($dbStyle['rangeitem'] != '') {
					$style->updateFromString("STYLE RANGEITEM " . $dbStyle['rangeitem']." END");
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
					if($dbLabel['anglemode'] != NULL) {
						$label->set('anglemode', $dbLabel['anglemode']);
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

	function create_group_legend($group_id, $status = NULL){
		$layerlist = $this->layerset['list'];
		if (@$this->groupset[$group_id]['untergruppen'] == NULL AND @$this->layerset['layers_of_group'][$group_id] == NULL)return;			# wenns keine Layer oder Untergruppen gibt, nix machen
    $groupname = $this->groupset[$group_id]['Gruppenname'];
	  $groupstatus = $this->groupset[$group_id]['status'];
    $legend =  '
	  <div id="groupdiv_'.$group_id.'" style="width:100%">
      <table cellspacing="0" cellpadding="0" border="0" style="width:100%">
				<tr>
					<td>
						<input id="group_' . $group_id . '" name="group_' . $group_id . '" type="hidden" value="' . $groupstatus . '">
						<a href="javascript:getlegend(\'' . $group_id . '\', \'\', document.GUI.nurFremdeLayer.value)">
							<img border="0" id="groupimg_' . $group_id . '" src="graphics/' . ($groupstatus == 1 ? 'minus' : 'plus') . '.gif">&nbsp;
						</a>
						<span class="legend_group' . (value_of($this->group_has_active_layers, $group_id) != '' ? '_active_layers' : '') . '">
							<!--a
								href="javascript:getGroupOptions(' . $group_id . ')"
								onmouseover="$(\'#test_' . $group_id . '\').show()"
								onmouseout="$(\'#test_' . $group_id . '\').hide()"
							>' . html_umlaute($groupname) . '
								<i id="test_' . $group_id . '" class="fa fa-bars" style="display: none;"></i>
							</a//-->' .
							html_umlaute($groupname) . '
							'.($groupname == 'eigene Abfragen' ? '<a href="javascript:deleteRollenlayer(\'search\');"><i class="fa fa-trash pointer" title="alle entfernen"></i></a>' : '').'
							'.(($groupname == 'Eigene Importe' OR $groupname == 'WMS-Importe') ? '<a href="javascript:deleteRollenlayer(\'import\');"><i class="fa fa-trash pointer" title="alle entfernen"></i></a>' : '').'
							<div style="position:static;" id="group_options_' . $group_id . '"></div>
						</span>
					</td>
				</tr>
				<tr>
					<td>
						<div id="layergroupdiv_'.$group_id.'" style="width:100%;'.(($groupstatus != 1 AND value_of($this->group_has_active_layers, $group_id) != '') ? 'display: none' : '').'"><table cellspacing="0" cellpadding="0">';
		$layercount = @count($this->layerset['layers_of_group'][$group_id] ?: []);
		if($groupstatus == 1 OR value_of($this->group_has_active_layers, $group_id) != ''){		# Gruppe aufgeklappt oder hat aktive Layer
			if(value_of($this->groupset[$group_id], 'untergruppen') != ''){
				for($u = 0; $u < count($this->groupset[$group_id]['untergruppen']); $u++){			# die Untergruppen rekursiv durchlaufen
					$legend .= '<tr><td colspan="3"><table cellspacing="0" cellpadding="0" style="width:100%"><tr><td><img src="'.GRAPHICSPATH.'leer.gif" width="13" height="1" border="0"></td><td style="width: 100%">';
					$legend .= $this->create_group_legend($this->groupset[$group_id]['untergruppen'][$u], $status);
					$legend .= '</td></tr></table></td></tr>';
				}
			}
			if ($layercount > 0) {		# Layer vorhanden
				if (value_of($this->layerset['layer_group_has_legendorder'], $group_id) != ''){			# Gruppe hat Legendenreihenfolge -> sortieren
					usort($this->layerset['layers_of_group'][$group_id], function($a, $b) use ($layerlist) {
						return $layerlist[$a]['legendorder'] - $layerlist[$b]['legendorder'];
					});
				}
				else {
					$this->layerset['layers_of_group'][$group_id] = array_reverse($this->layerset['layers_of_group'][$group_id]);		# umgedrehte Zeichenreihenfolge verwenden
				}
				if (!value_of($this->formvars, 'nurFremdeLayer')) {
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
				for ($j = 0; $j < $layercount; $j++) {
					$layer = $this->layerset['list'][$this->layerset['layers_of_group'][$group_id][$j]];
					$legend .= $this->create_layer_legend($layer);
				}
			}
	  }
    $legend .= '</table></div></td></tr></table>';
    $legend .= '<input type="hidden" name="radiolayers_'.$group_id.'" value="'.value_of($this->radiolayers, $group_id).'">';
	  $legend .= '</div>';
    return $legend;
  }

	function create_layer_legend($layer, $requires = false){
		if(!$requires AND value_of($layer, 'requires') != '' OR $requires AND value_of($layer, 'requires') == '')return;
		global $legendicon_size;
		$visible = $this->check_layer_visibility($layer);
		# sichtbare Layer
		if ($visible) {
			if (value_of($layer, 'requires') == '') {
				$legend = '<tr><td valign="top" style="position: relative;">';

				if (!empty($layer['shared_from'])) {
					$user_daten = $this->user->getUserDaten($layer['shared_from'], '', '');
					$legend .= ' <a
						href="javascript:void(0)"
						onclick="message([{ \'type\': \'info\', \'msg\' : \'' . $this->strLayerSharedFrom . ' ' . $user_daten[0]['Vorname'] . ' ' . $user_daten[0]['Name'] . (!empty($user_daten[0]['organisation']) ? ' (' . $user_daten[0]['organisation'] . ')' : '') . '\'}])"
						style="
							font-size: 10px;
							margin-left: -10px;
							margin-top: 5px;
							position: absolute;
						"
					><i class="fa fa-share-alt" aria-hidden="true"></i></a>';
				}

				if ($layer['queryable'] == 1 AND !value_of($this->formvars, 'nurFremdeLayer')) {
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
				if(value_of($layer, 'selectiontype') == 'radio'){
					$legend .=  'type="radio" ';
					$legend .=  ' onClick="this.checked = this.checked2;" onMouseUp="this.checked = this.checked2;" onMouseDown="updateQuery(event, document.getElementById(\'thema_'.$layer['Layer_ID'].'\'), document.getElementById(\'qLayer'.$layer['Layer_ID'].'\'), document.GUI.radiolayers_'.$layer['Gruppe'].', '.$this->user->rolle->instant_reload.')"';
					$this->radiolayers[$layer['Gruppe']] = value_of($this->radiolayers, $layer['Gruppe']).$layer['Layer_ID'].'|';
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
				if(value_of($layer, 'metalink') != ''){
					$legend .= ' class="metalink boldhover" href="javascript:void(0);">';
				}
				else {
					$legend .= ' class="visiblelayerlink boldhover" href="javascript:void(0)">';
				}
				$legend .= '<span id="'.str_replace('"', '', str_replace("'", '', str_replace('-', '_', $layer['Name_or_alias']))).'"';
				if(value_of($layer, 'minscale') != -1 AND value_of($layer, 'maxscale') > 0){
					$legend .= ' title="'.round($layer['minscale']).' - '.round($layer['maxscale']).'"';
				}
				$legend .= ' >' . html_umlaute($layer['alias_link']) . '</span>';
				$legend .= '</a>';

				# Bei eingeschalteten Layern und eingeschalteter Rollenoption ist ein Optionen-Button sichtbar
				if ($layer['aktivStatus'] == 1 and $this->user->rolle->showlayeroptions) {
					$legend .= '&nbsp';
					if ($layer['rollenfilter'] != '') {
						$legend .= '<a href="javascript:void(0)" onclick="getLayerOptions('.$layer['Layer_ID'] . ')">
							<i class="fa fa-filter button layerOptionsIcon" title="' . $layer['rollenfilter'] . '"></i>
						</a>';
					}
					$legend .= '<a href="javascript:void(0)" onclick="getLayerOptions(' . $layer['Layer_ID'] . ')">
						<i class="fa fa-bars pointer button layerOptionsIcon" title="'.$this->layerOptions.'"></i>
					</a>';
				}
				$legend.='<div style="position:static; float:right" id="options_'.$layer['Layer_ID'].'"> </div>';
			}
			if($layer['aktivStatus'] == 1 AND isset($layer['Class'][0])){
				if(value_of($layer, 'requires') == '' AND $layer['Layer_ID'] > 0){
					$legend .= '<input id="classes_'.$layer['Layer_ID'].'" name="classes_'.$layer['Layer_ID'].'" type="hidden" value="'.$layer['showclasses'].'">';
				}
				if ($layer['showclasses'] != 0) {
					if($layer['connectiontype'] == 7){      # WMS
						if($layer['Class'][0]['legendgraphic'] != ''){
							$imagename = $original_class_image = CUSTOM_PATH . 'graphics/' . $layer['Class'][0]['legendgraphic'];
							$legend .=  '<div id="lg'.$j.'_'.$l.'"><img src="'.$imagename.'"></div>';
						}
						else{
							$legend .=  $this->get_legend_graphics($layer);
						}
					}
					else {
						$legend .= '<table border="0" cellspacing="0" cellpadding="0">';
						$maplayer = $this->map->getLayer($layer['layer_index_mapobject']);
						if($layer['Class'][0]['legendorder'] != ''){
							usort($layer['Class'], 'compare_legendorder');
						}
						for($k = 0; $k < $maplayer->numclasses; $k++){
							$class = $maplayer->getClass($layer['Class'][$k]['index']);
							if ($class->name != '') {
								for($s = 0; $s < $class->numstyles; $s++){
									$style = $class->getStyle($s);
									if($maplayer->type > 0){
										$symbol = $this->map->getSymbolObjectById($style->symbol);
										if($symbol->type == 1006){ 	# 1006 == hatch
											$style->set('size', 2*$style->width);					# size und maxsize beim Typ Hatch auf die doppelte Linienbreite setzen, damit man was in der Legende erkennt
											$style->set('maxsize', 2*$style->width);
										}
										elseif($style->symbolname == ''){
											$style->set('size', 2);					# size und width bei Linien und Polygonlayern immer auf 2 setzen, damit man was in der Legende erkennt
											$style->set('maxsize', 2);
											$style->set('width', 2);
											$style->set('maxwidth', 2);
										}
										if ($maplayer->type == MS_LAYER_CHART) {
											$maplayer->set('type', MS_LAYER_POLYGON);		# Bug-Workaround Chart-Typ
										}
									}
									else{		# Punktlayer
										if($style->size > 14 OR $style->size == -1){
											$style->set('size', 14);
										}
										$style->set('maxsize', $style->size);		# maxsize auf size setzen bei Punktlayern, damit man was in der Legende erkennt
										$style->set('minsize', $style->size);		# minsize auf size setzen bei Punktlayern, damit man was in der Legende erkennt
										if($class->numstyles == 1){							# wenn es nur einen Style in der Klasse gibt, die Offsets auf 0 setzen, damit man was in der Legende erkennt
											$style->set('offsety', 0);
											$style->set('offsetx', 0);
										}
									}
								}
								$legend .= '<tr style="line-height: 15px"><td style="line-height: 14px">';
								if($s > 0 OR $class->status == MS_OFF){
									$width = $height = '';
									if($layer['Class'][$k]['legendimagewidth'] != '')$width = $layer['Class'][$k]['legendimagewidth'];
									if($layer['Class'][$k]['legendimageheight'] != '')$height = $layer['Class'][$k]['legendimageheight'];
									$padding = 1;
									###### eigenes Klassenbild ######
									if ($layer['Class'][$k]['legendgraphic'] != '') {
										$imagename = $original_class_image = CUSTOM_PATH . 'graphics/' . $layer['Class'][$k]['legendgraphic'];
										if ($width == '') {
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
											$imagename = TEMPPATH_REL.$newname;
										}
										else{																												# vom Mapserver generiertes Klassenbild
											$image = $class->createLegendIcon($width, $height);
											ob_start();
											$image->saveImage();
											$image = ob_get_clean();
											$imagename = 'data:image/jpg;base64,'.base64_encode($image);
										}
										$original_class_image = $imagename;
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
									$legend .= '<input type="hidden" size="2" name="class'.$classid.'" value="'.$status.'"><a href="#" onmouseover="mouseOverClassStatus('.$classid.',\''.$original_class_image.'\', '.$width.', '.$height.', ' . $maplayer->type . ')" onmouseout="mouseOutClassStatus('.$classid.',\''.$original_class_image.'\', '.$width.', '.$height.', ' . $maplayer->type . ')" onclick="changeClassStatus('.$classid.',\''.$original_class_image.'\', '.$this->user->rolle->instant_reload.', '.$width.', '.$height.', ' . $maplayer->type . ')"><img style="vertical-align:middle;padding-bottom: '.$padding.'" border="0" name="imgclass'.$classid.'" width="'.$width.'" height="'.$height.'" src="'.$imagename.'"></a>';
								}
								$legend .= '&nbsp;<span class="px13">'.html_umlaute($class->name).'</span></td></tr>';
							}
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
		if(value_of($layer, 'requires') == '' AND !$visible){
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
			$legend .= '<span class="legend_layer_hidden" id="'.str_replace('-', '_', $layer['Name_or_alias']).'"';
			if($layer['minscale'] != -1 AND $layer['maxscale'] != -1){
				$legend .= 'title="'.round($layer['minscale']).' - '.round($layer['maxscale']).'"';
			}
			$legend .= ' >'.html_umlaute($layer['Name_or_alias']).'</span></a>';
			$legend.='<div style="position:static; float:right" id="options_'.$layer['Layer_ID'].'"> </div>';
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

		# requires-Layer
		if(value_of($layer, 'required') != ''){
			foreach($layer['required'] as $require_layer_id){
				$legend .= $this->create_layer_legend($this->layerset['layer_ids'][$require_layer_id], true);
			}
		}
		return $legend;
	}


	function get_legend_graphics($layer){
		$output = '';
		$url = $layer['connection'];
		$pos = strpos(strtolower($layer['connection']), 'styles=');
		if ($pos !== false) {
			$stylesection = substr($layer['connection'], $pos + 7);
			$pos = strpos($stylesection, '&');
			if ($pos !== false) {
				$stylesection = substr($stylesection, 0, $pos);
			}
		}
		$styles = explode(',', $stylesection);
		if (strpos(strtolower($url), 'format') === false) {
			$url .= '&format=image/png';
		}
		if (strpos(strtolower($url), 'version') === false) {
			$url .= '&version=' . $layer['wms_server_version'];
		}
		$pos = strpos(strtolower($layer['connection']), 'layers');
		if ($pos !== false) {
			$layersection = substr($layer['connection'], $pos + 7);
			$pos = strpos($layersection, '&');
			if ($pos !== false) {
				$layersection = substr($layersection, 0, $pos);
			}
		}
		else {
			$layersection = $layer['wms_name'];
		}
		$layers = explode(',', $layersection);
		for($l = 0; $l < count($layers); $l++){
			$url = $url . '&layer=' . $layers[$l] . '&style=' . $styles[$l] . '&service=WMS&request=GetLegendGraphic';
			if ($layer['wms_auth_username'] != '') {
				$img = url_get_contents($url, $layer['wms_auth_username'], $layer['wms_auth_password']);
				$url = 'data:image/jpg;base64,'.base64_encode($img);
			}
			$output .=  '<div id="lg_'.$layer['Layer_ID'].'_'.$l.'"><img src="' . $url . '" onerror="ImageLoadFailed(this)"></div>';
		}
		return $output;
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
  var $success;
  var $errormessage;

  function __construct() {
    global $debug;
		global $GUI;
		$this->gui = $GUI;
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
		$this->debug->write("<br>MySQL Verbindung öffnen mit Host: " . $this->host . " User: " . $this->user . " Datenbbank: " . $this->dbName, 4);
		$this->mysqli = mysqli_init();
		$ret = $this->mysqli->real_connect($this->host, $this->user, $this->passwd, $this->dbName, 3306, null, MYSQLI_CLIENT_FOUND_ROWS);
	  $this->debug->write("<br>MySQL VerbindungsID: " . $this->mysqli->thread_id, 4);
		$this->debug->write("<br>MySQL Fehlernummer: " . mysqli_connect_errno(), 4);
		$this->debug->write("<br>MySQL Fehler: " . mysqli_connect_error(), 4);
		return $ret;
	}

	function execSQL($sql, $debuglevel = 4, $loglevel = 0, $suppress_error_msg = false) {
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
			#echo '<br>sql in execSQL: ' . $sql;
			if ($result = $this->mysqli->query($sql)) {
				$ret[0] = 0;
				$ret['success'] = $this->success = true;
				$ret[1] = $ret['query'] = $ret['result'] = $this->result = $result;
				$this->errormessage = '';
				if ($logsql) {
					$this->logfile->write($sql . ';');
				}
				$this->debug->write(date('H:i:s')."<br>" . $sql, $debuglevel);
			}
			else {
				$ret[0] = 1;
				$ret['success'] = $this->success = false;
				$div_id = rand(1, 99999);
				$errormessage = $this->mysqli->error;
				$ret[1] = $this->errormessage = sql_err_msg('MySQL', $sql, $errormessage, $div_id);
				if ($logsql) {
					$this->logfile->write("#" . $errormessage);
				}
				if (!$suppress_error_msg) {
					if (gettype($this->gui) == 'object') {
						$this->gui->add_message('error', $this->errormessage);
					}
					else {
						echo '<br>error: ' . $this->errormessage;
					}
				}
			}
			$ret[2] = $sql;
		}
		else {
			if ($logsql) {
				$this->logfile->write($sql . ';');
			}
			$this->debug->write("<br>" . $sql, $debuglevel);
		}
		return $ret;
	}

	function close() {
		$this->debug->write("<br>MySQL Verbindung ID: " . $this->mysqli->thread_id . " schließen.", 4);
		if (LOG_LEVEL > 0) {
			$this->logfile->close();
		}
		return $this->mysqli->close();
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

	function __construct($login_name, $id, $database, $passwort = '') {
		global $debug;
		$this->debug = $debug;
		$this->database = $database;
		if ($login_name) {
			$this->login_name = $login_name;
			$this->readUserDaten(0, $login_name, $passwort);
			$this->remote_addr = getenv('REMOTE_ADDR');
		}
		else {
			$this->id = $id;
			$this->readUserDaten($id, 0);
		}
	}

	function readUserDaten($id, $login_name, $password = '') {
		$where = array();
		if ($id > 0) array_push($where, "ID = " . $id);
		if ($login_name != '') array_push($where, "login_name LIKE '" . $this->database->mysqli->real_escape_string($login_name) . "'");
		if ($password != '') array_push($where, "password = SHA1('" . $this->database->mysqli->real_escape_string($password) . "')");
		$sql = "
			SELECT
				*
			FROM
				user
			WHERE
				" . implode(" AND ", $where) . "
		";
		#echo '<br>SQL to read user data: ' . $sql;

		$this->debug->write("<p>file:users.php class:user->readUserDaten - Abfragen des Namens des Benutzers:<br>", 3);
		$this->database->execSQL($sql, 4, 0, true);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>' . $this->database->mysqli->error, 4); return 0; }
		$rs = $this->database->result->fetch_array();
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
		$this->agreement_accepted = $rs['agreement_accepted'];
		$this->start = $rs['start'];
		$this->stop = $rs['stop'];
		$this->share_rollenlayer_allowed = $rs['share_rollenlayer_allowed'];
		$this->layer_data_import_allowed = $rs['layer_data_import_allowed'];
		$this->tokens = $rs['tokens'];
	}

	function getUserDaten($id, $login_name, $order, $stelle_id = 0, $admin_id = 0) {
		global $admin_stellen;
		$where = array();

		if ($admin_id > 0 AND !in_array($stelle_id, $admin_stellen)) {
			$more_from = "
				LEFT JOIN rolle rall ON u.ID = rall.user_id
				LEFT JOIN rolle radm ON radm.stelle_id = rall.stelle_id
			";
			$where[] = "(radm.user_id = ".$admin_id." OR rall.user_id IS NULL)";
		}

		if ($id > 0) {
			$where[] = 'u.ID = ' . $id;
		}

		if ($login_name != '') {
			$where[] = 'login_name LIKE "' . $login_name . '"';
		}

		if ($order != '') {
			$order = ' ORDER BY ' . replace_semicolon($order);
		}

		$sql = "
			SELECT DISTINCT
				u.*, (select max(c.time_id) from u_consume c where u.ID = c.user_id ) as last_timestamp
			FROM
				user u " .
				$more_from .
			(count($where) > 0 ? " WHERE " . implode(' AND ', $where) : "") .
			$order . "
		";
		#echo '<br>sql: ' . $sql;

		$this->debug->write("<p>file:users.php class:user->readUserDaten - Abfragen des Namens des Benutzers:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>' . $this->database->mysqli->error, 4);
			return 0;
		}
		while ($rs = $this->database->result->fetch_array()) {
			$userdaten[] = $rs;
		}
		return $userdaten;
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
  var $language;

	function __construct($id, $database) {
		global $debug;
		global $log_mysql;
		$this->debug = $debug;
		$this->log = $log_mysql;
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
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0;
		}
		$rs = $this->database->result->fetch_array();
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
		$this->debug->write('<p>file:stelle.php class:stelle->readDefaultValues - Abfragen der Default Parameter der Karte zur Stelle:<br>' . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0;
		}
		$rs = $this->database->result->fetch_array();
		$this->MaxGeorefExt = rectObj($rs['minxmax'], $rs['minymax'], $rs['maxxmax'], $rs['maxymax']);
		$this->epsg_code = $rs['epsg_code'];
		$this->postgres_connection_id = $rs['postgres_connection_id'];
		# ---> deprecated
			$this->pgdbhost = ($rs['pgdbhost'] == 'PGSQL_PORT_5432_TCP_ADDR' ? getenv('PGSQL_PORT_5432_TCP_ADDR') : $rs['pgdbhost']);
			$this->pgdbname = $rs['pgdbname'];
			$this->pgdbuser = $rs['pgdbuser'];
			$this->pgdbpasswd = $rs['pgdbpasswd'];
		# <---
		$this->protected = $rs['protected'];
		//---------- OWS Metadaten ----------//
		$this->ows_title = $rs['ows_title'];
		$this->ows_abstract = $rs['ows_abstract'];
		$this->wms_accessconstraints = $rs['wms_accessconstraints'];
		$this->ows_contactperson = $rs['ows_contactperson'];
		$this->ows_contactorganization = $rs['ows_contactorganization'];
		$this->ows_contactelectronicmailaddress = $rs['ows_contactemailaddress'];
		$this->ows_contactposition = $rs['ows_contactposition'];
		$this->ows_fees = $rs['ows_fees'];
		$this->ows_srs = $rs['ows_srs'];
		$this->check_client_ip = $rs['check_client_ip'];
		$this->checkPasswordAge = $rs['check_password_age'];
		$this->allowedPasswordAge = $rs['allowed_password_age'];
		$this->useLayerAliases = $rs['use_layer_aliases'];
		$this->selectable_layer_params = $rs['selectable_layer_params'];
		$this->hist_timestamp = $rs['hist_timestamp'];
		$this->default_user_id = $rs['default_user_id'];
		$this->style = $rs['style'];
	}
}

class rolle {

  var $user_id;
  var $stelle_id;
  var $debug;
  var $database;
  var $loglevel;
  var $hist_timestamp_de;
  static $hist_timestamp;
  static $layer_params;
  var $minx;
  var $language;
  var $newtime;

	function __construct($user_id, $stelle_id, $database) {
		global $debug;
		global $GUI;
		$this->gui_object = $GUI;
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
		#echo 'Read rolle settings mit sql: ' . $sql;
    $this->debug->write("<p>file:rolle.php class:rolle function:readSettings - Abfragen der Einstellungen der Rolle:<br>".$sql,4);
    $this->database->execSQL($sql);
    if (!$this->database->success) {
      $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4);
      return 0;
    }
		if ($this->database->result->num_rows > 0){
			$rs = $this->database->result->fetch_assoc();
			$this->oGeorefExt = rectObj($rs['minx'],$rs['miny'],$rs['maxx'],$rs['maxy']);
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
			$this->tooltipquery=$rs['tooltipquery'];
			$this->scrollposition=$rs['scrollposition'];
			$this->result_color=$rs['result_color'];
			$this->result_hatching=$rs['result_hatching'];
			$this->result_transparency=$rs['result_transparency'];
			$this->always_draw=$rs['always_draw'];
			$this->runningcoords=$rs['runningcoords'];
			$this->showmapfunctions=$rs['showmapfunctions'];
			$this->showlayeroptions=$rs['showlayeroptions'];
			$this->showrollenfilter=$rs['showrollenfilter'];
			$this->menue_buttons=$rs['menue_buttons'];
			$this->singlequery=$rs['singlequery'];
			$this->querymode=$rs['querymode'];
			$this->geom_edit_first=$rs['geom_edit_first'];
			$this->dataset_operations_position = $rs['dataset_operations_position'];
			$this->immer_weiter_erfassen = $rs['immer_weiter_erfassen'];
			$this->upload_only_file_metadata = $rs['upload_only_file_metadata'];
			$this->overlayx=$rs['overlayx'];
			$this->overlayy=$rs['overlayy'];
			$this->instant_reload=$rs['instant_reload'];
			$this->menu_auto_close=$rs['menu_auto_close'];
			rolle::$layer_params = (array)json_decode('{' . $rs['layer_params'] . '}');
			$this->visually_impaired = $rs['visually_impaired'];
			$this->font_size_factor = $rs['font_size_factor'];
			$this->legendtype = $rs['legendtype'];
			$this->print_legend_separate = $rs['print_legend_separate'];
			$this->print_scale = $rs['print_scale'];
			if ($rs['hist_timestamp'] != '') {
				$this->hist_timestamp_de = DateTime::createFromFormat('Y-m-d H:i:s', $rs['hist_timestamp'])->format('d.m.Y H:i:s');			# der wird zur Anzeige des Timestamps benutzt
				rolle::$hist_timestamp = DateTime::createFromFormat('Y-m-d H:i:s', $rs['hist_timestamp'])->format('Y-m-d\TH:i:s\Z');	# der hat die Form, wie der timestamp in der PG-DB steht und wird für die Abfragen benutzt
			}
			else {
				rolle::$hist_timestamp = $this->hist_timestamp_de = '';
				#rolle::$hist_timestamp = '';
			}
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
			$this->gps = in_array('gps', $buttons);
			$this->geom_buttons = explode(',', str_replace(' ', '', $rs['geom_buttons']));
			return 1;
		}
		else {
			return 0;
		}
	}

	function setGroupStatus($formvars) {
		$this->groupset = $this->getGroups('');
		# Eintragen des group_status=1 für Gruppen, die angezeigt werden sollen
		for ($i = 0; $i < count($this->groupset); $i++) {
			if(value_of($formvars, 'group_'.$this->groupset[$i]['id']) !== '') {
				$group_status = (value_of($formvars, 'group_'.$this->groupset[$i]['id']) == 1 ? 1 : 0);
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
    $this->database->execSQL($sql);
    if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
    while ($rs = $this->database->result->fetch_assoc()) {
      $groups[]=$rs;
    }
    return $groups;
  }

	function setClassStatus($formvars) {
		if(value_of($formvars, 'layer_id') != ''){
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
	var $host;
	var $port;
	var $schema;
	var $pg_text_attribute_types = array('character', 'character varying', 'text', 'timestamp without time zone', 'timestamp with time zone', 'date', 'USER-DEFINED');
	var $version = POSTGRESVERSION;
	var $connection_id;

	function __construct() {
		global $debug;
		global $GUI;
		$this->gui = $GUI;
		$this->debug=$debug;
		$this->loglevel=LOG_LEVEL;
		$this->defaultloglevel=LOG_LEVEL;
		global $log_postgres;
		$this->logfile=$log_postgres;
		$this->defaultlogfile=$log_postgres;
		$this->ist_Fortfuehrung=1;
		$this->type='postgresql';
		$this->commentsign='--';
		$this->err_msg = '';
		# Wenn dieser Parameter auf 1 gesetzt ist werden alle Anweisungen
		# START TRANSACTION, ROLLBACK und COMMIT unterdrückt, so daß alle anderen SQL
		# Anweisungen nicht in Transactionsblöcken ablaufen.
		# Kann zur Steigerung der Geschwindigkeit von großen Datenbeständen verwendet werden
		# Vorsicht: Wenn Fehler beim Einlesen passieren, ist der Datenbestand inkonsistent
		# und der Einlesevorgang muss wiederholt werden bis er fehlerfrei durchgelaufen ist.
		# Dazu Fehlerausschriften bearchten.
		$this->blocktransaction=0;
	}

	/**
	* Open the database connection based on the given connection_id
	* @param integer, $connection_id The id of the connection defined in mysql connections table, if 0 default connection will be used
	* @return boolean, True if success or set an error message in $this->err_msg and return false when fail to find the credentials or open the connection
	*/
  function open($connection_id = 0) {
		if ($connection_id == 0) {
			# get credentials from object variables
			$connection_string = $this->format_pg_connection_string($this->get_object_credentials());
		}
		else {
			$this->debug->write("Open Database connection with connection_id: " . $connection_id, 4);
			$this->connection_id = $connection_id;
			$connection_string = $this->get_connection_string();
		}
		$this->dbConn = pg_connect($connection_string);
		if (!$this->dbConn) {
			$this->err_msg = 'Die Verbindung zur PostGIS-Datenbank konnte mit folgenden Daten nicht hergestellt werden: ' . str_replace($credentials['password'], '********', $connection_string);
			return false;
		}
		else {
			$this->debug->write("Database connection: successfully opend.", 4);
			$this->setClientEncodingAndDateStyle();
			$this->connection_id = $connection_id;
			return true;
		}
	}

	/**
	* return the credential details as array from connections_table
	* or default values if no exists for connection_id
	* @param integer $connection_id The id of connection information in connection mysql table
	* @return array $credentials array with connection details
	*/
	function get_credentials($connection_id) {
		#echo '<p>get_credentials with connection_id: ' . $connection_id;
		include_once(CLASSPATH . 'Connection.php');
		$conn = Connection::find_by_id($this->gui, $connection_id);
		return array(
			'host' => 		($conn->get('host')     != '' ? $conn->get('host')     : 'pgsql'),
			'port' => 		($conn->get('port')     != '' ? $conn->get('port')     : '5432'),
			'dbname' => 	($conn->get('dbname')   != '' ? $conn->get('dbname')   : 'kvwmapsp'),
			'user' => 		($conn->get('user')     != '' ? $conn->get('user')     : 'kvwmap'),
			'password' => ($conn->get('password') != '' ? $conn->get('password') : KVWMAP_INIT_PASSWORD)
		);
	}

	/**
	* returns a postgres connection string used to connect to postgres with pg_connect
	* @param array $credentials array with connection details
	* @return string the postgres connection string
	*/
	function format_pg_connection_string($credentials) {
		$connection_string = '' .
			'host=' . 		$credentials['host'] 		. ' ' .
			'port=' . 		$credentials['port'] 		. ' ' .
			'dbname=' . 	$credentials['dbname'] 	. ' ' .
			'user=' . 		$credentials['user'] 		. ' ' .
			'password=' .	$credentials['password'];
		return $connection_string;
	}

	function get_connection_string() {
		return $this->format_pg_connection_string($this->get_credentials($this->connection_id));
	}

	/**
	* Set credentials to postgres object variables
	*/
	function set_object_credentials($credentials) {
		$this->host = 	$credentials['host'];
		$this->port = 	$credentials['port'];
		$this->dbName = $credentials['dbname'];
		$this->user = 	$credentials['user'];
		$this->passwd = $credentials['password'];
	}

	/**
	* Get credentials from postgres object variables
	*/
	function get_object_credentials() {
		return array(
			'host'     => $this->host,
			'port'     => $this->port,
			'dbname'   => $this->dbName,
			'user'     => $this->user,
			'password' => $this->passwd
		);
	}

  function setClientEncodingAndDateStyle() {
    $sql = "
			SET CLIENT_ENCODING TO '".POSTGRES_CHARSET."';
			SET datestyle TO 'German';
			";
		$ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    return $ret[1];
  }

	function execSQL($sql, $debuglevel, $loglevel, $suppress_err_msg = false) {
		$ret = array(); // Array with results to return
		$strip_context = true;

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
			#echo "<br>SQL in execSQL: " . $sql;
			if ($this->schema != '') {
				$sql = "SET search_path = " . $this->schema . ", public;" . $sql;
			}
			#echo "<br>SQL in execSQL: " . $sql;
			$query = @pg_query($this->dbConn, $sql);
			//$query=0;
			if ($query == 0) {
				$ret['success'] = false;
				# erzeuge eine Fehlermeldung;
				$last_error = pg_last_error($this->dbConn);
				if ($strip_context AND strpos($last_error, 'CONTEXT: ') !== false) {
					$ret['msg'] = substr($last_error, 0, strpos($last_error, 'CONTEXT: '));
				}
				else {
					$ret['msg'] = $last_error;
				}

				if (strpos($last_error, '{') !== false AND strpos($last_error, '}') !== false) {
					# Parse als JSON String;
					$error_obj = json_decode(substr($last_error, strpos($last_error, '{'), strpos($last_error, '}') - strpos($last_error, '{') + 1), true);
					if (array_key_exists('msg_type', $error_obj)) {
						$ret['type'] = $error_obj['msg_type'];
					}
					if (array_key_exists('msg', $error_obj) AND $error_obj['msg'] != '') {
						$ret['msg'] = $error_obj['msg'];
					}
				}
				else {
					$ret['type'] = 'error';
				}
				$this->debug->write("<br><b>" . $last_error . "</b>", $debuglevel);
				if ($logsql) {
					$this->logfile->write($this->commentsign . ' ' . $sql . ' ' . $last_error);
				}
			}
			else {
				# Abfrage wurde zunächst erfolgreich ausgeführt
				$ret[0] = 0;
				$ret['success'] = true;
				$ret[1] = $ret['query'] = $query;

				# Prüfe ob eine Fehlermeldung in der Notice steckt
				$last_notice = pg_last_notice($this->dbConn);
				if ($strip_context AND strpos($last_notice, 'CONTEXT: ') !== false) {
					$last_notice = substr($last_notice, 0, strpos($last_notice, 'CONTEXT: '));
				}
				# Verarbeite Notice nur, wenn sie nicht schon mal vorher ausgewertet wurde
				if ($last_notice != '' AND ($this->gui->notices == NULL OR !in_array($last_notice, $this->gui->notices))) {
					$this->gui->notices[] = $last_notice;
					if (strpos($last_notice, '{') !== false AND strpos($last_notice, '}') !== false) {
						# Parse als JSON String
						$notice_obj = json_decode(substr($last_notice, strpos($last_notice, '{'), strpos($last_notice, '}') - strpos($last_notice, '{') + 1), true);
						if (array_key_exists('success', $notice_obj)) {
							if (!$notice_obj['success']) {
								$ret['success'] = false;
							}
							if (array_key_exists('msg_type', $notice_obj)) {
								$ret['type'] = $notice_obj['msg_type'];
							}
							if (array_key_exists('msg', $notice_obj) AND $notice_obj['msg'] != '') {
								$ret['msg'] = $notice_obj['msg'];
							}
						}
					}
					else {
						# Gebe Noticetext wie er ist zurück
						$ret['msg'] = $last_notice;
					}
				}

				# Schreibe Meldungen in Log und Debugfile
				$this->debug->write("<br>" . $sql, $debuglevel);
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

		if ($ret['success']) {
			# alles ok mach nichts weiter
		}
		else {
			# Fehler setze entsprechende Fags und Fehlermeldung
			$ret[0] = 1;
			$ret[1] = $ret['msg'];
			if ($suppress_err_msg) {
				# mache nichts, den die Fehlermeldung wird unterdrückt
			}
			else {
				# gebe Fehlermeldung aus.
				$ret[1] = $ret['msg'] = sql_err_msg('Fehler bei der Abfrage der PostgreSQL-Datenbank:', $sql, $ret['msg'], 'error_div_' . rand(1, 99999));
				$this->gui->add_message($ret['type'], $ret['msg']);
				header('error: true');	// damit ajax-Requests das auch mitkriegen
			}
		}
		return $ret;
	}

	function read_epsg_codes($order = true){
    global $supportedSRIDs;
    $sql ="SELECT spatial_ref_sys.srid, coalesce(alias, substr(srtext, 9, 35)) as srtext, proj4text, minx, miny, maxx, maxy FROM spatial_ref_sys ";
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
  var $db;
  var $OhneRequires;
	var $script_name;
	var $GUI;
	var $rolle;

	function __construct($Stelle_ID, $User_ID) {
		global $debug;
		global $GUI;
		$this->script_name = 'db_MapObj.php';
		$this->debug = $debug;
		$this->GUI = $GUI;
		$this->db = $GUI->database;
		$this->Stelle_ID = $Stelle_ID;
		$this->User_ID = $User_ID;
		$this->rolle = new rolle($User_ID, $Stelle_ID, $this->db);
	}

	function read_ReferenceMap() {
    $sql = "
			SELECT
				r.*
			FROM
				referenzkarten AS r,
				stelle AS s
			WHERE
				r.ID = s.Referenzkarte_ID
    		AND s.ID = " . $this->Stelle_ID . "
		";
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_ReferenceMap - Lesen der Referenzkartendaten:<br>" . $sql,4);
		$this->db->execSQL($sql);
		if (!$this->db->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . "<br>" . $this->db->mysqli->error, 4); return 0; }
		$rs = $this->db->result->fetch_array();
    $this->referenceMap = $rs;
#		echo '<br>sql: ' . print_r($sql, true);
#		echo '<br>ref: ' . print_r($this->referenceMap, true);
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

    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Groups - Lesen der Gruppen der Rolle:<br>" . $sql,4);
    $this->db->execSQL($sql);
		if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
    while ($rs = $this->db->result->fetch_array()) {
			$groups[$rs['id']]['status'] = value_of($rs, 'status');
      $groups[$rs['id']]['Gruppenname'] = $rs['Gruppenname'];
			$groups[$rs['id']]['obergruppe'] = $rs['obergruppe'];
			$groups[$rs['id']]['id'] = $rs['id'];
			if($rs['obergruppe'])$groups[$rs['obergruppe']]['untergruppen'][] = $rs['id'];
    }
    $this->anzGroups=count($groups);
    return $groups;
  }

	function read_Layer($withClasses, $useLayerAliases = false, $groups = NULL) {
		include_once(CLASSPATH . 'DataSource.php');
		global $language;

		if ($language != 'german') {
			$name_column = "
			CASE
				WHEN l.`Name_" . $language . "` != \"\" THEN l.`Name_" . $language . "`
				ELSE l.`Name`
			END AS Name";
			$group_column = '
			CASE
				WHEN `Gruppenname_' . $language . '` IS NOT NULL THEN `Gruppenname_' . $language . '`
				ELSE `Gruppenname`
			END AS Gruppenname';
		}
		else {
			$name_column = "l.Name";
			$group_column = 'Gruppenname';
		}

		$sql = "
			SELECT DISTINCT
				l.oid,
				coalesce(rl.transparency, ul.transparency, 100) as transparency,
				rl.`aktivStatus`,
				rl.`queryStatus`,
				rl.`gle_view`,
				rl.`showclasses`,
				rl.`logconsume`,
				rl.`rollenfilter`,
				ul.`queryable`,
				COALESCE(rl.drawingorder, ul.drawingorder) as drawingorder,
				ul.legendorder,
				ul.`minscale`, ul.`maxscale`,
				ul.`offsite`,
				ul.`postlabelcache`,
				ul.`Filter`,
				ul.`template`,
				ul.`header`,
				ul.`footer`,
				ul.`symbolscale`,
				ul.`logconsume`,
				ul.`requires`,
				ul.`privileg`,
				ul.`export_privileg`,
				ul.`group_id`,
				l.Layer_ID," .
				$name_column . ",
				l.alias,
				l.Datentyp, COALESCE(ul.group_id, l.Gruppe) AS Gruppe, l.pfad, l.Data, l.tileindex, l.tileitem, l.labelangleitem, coalesce(rl.labelitem, l.labelitem) as labelitem, rl.labelitem as user_labelitem,
				l.labelmaxscale, l.labelminscale, l.labelrequires,
				l.connection_id,
				CASE
					WHEN connectiontype = 6 THEN concat('host=', c.host, ' port=', c.port, ' dbname=', c.dbname, ' user=', c.user, ' password=', c.password, ' application_name=kvwmap_user_', gr.user_id)
					ELSE l.connection
				END as connection,
				l.printconnection,
				l.connectiontype,
				l.classitem, l.styleitem, l.classification,
				l.cluster_maxdistance, l.tolerance, l.toleranceunits, l.sizeunits, l.processing, l.epsg_code, l.ows_srs, l.wms_name, l.wms_keywordlist, l.wms_server_version,
				l.wms_format, l.wms_auth_username, l.wms_auth_password, l.wms_connectiontimeout, l.selectiontype, l.logconsume,l.metalink, l.status, l.trigger_function,
				l.duplicate_from_layer_id,
				l.duplicate_criterion,
				l.shared_from,
				l.kurzbeschreibung,
				l.dataowner_name,
				l.dataowner_email,
				l.dataowner_tel,
				l.uptodateness,
				l.updatecycle,
				g.id,
				" . $group_column . ",
				g.obergruppe,
				g.order
				" . ($this->GUI->plugin_loaded('mobile') ? ', l.`sync`' : '') . "
				" . ($this->GUI->plugin_loaded('mobile') ? ', l.`vector_tile_url`' : '') . "
				" . ($this->GUI->plugin_loaded('portal') ? ', l.`cluster_option`' : '') . "
			FROM
				u_rolle2used_layer AS rl,
				used_layer AS ul JOIN
				layer AS l ON l.Layer_ID = ul.Layer_ID LEFT JOIN
				u_groups AS g ON COALESCE(ul.group_id, l.Gruppe) = g.id LEFT JOIN
				u_groups2rolle AS gr ON g.id = gr.id LEFT JOIN
				connections as c ON l.connection_id = c.id
			WHERE
				rl.stelle_id = ul.Stelle_ID AND
				rl.layer_id = ul.Layer_ID AND
				(ul.minscale != -1 OR ul.minscale IS NULL) AND
				l.Datentyp != 5 AND 
				rl.stelle_ID = " . $this->Stelle_ID . " AND rl.user_id = " . $this->User_ID . " AND
				gr.stelle_id = " . $this->Stelle_ID . " AND
				gr.user_id = " . $this->User_ID .
				($groups != NULL ? " AND g.id IN (" . $groups . ")" : '') .
				($this->nurAufgeklappteLayer ? " AND (rl.aktivStatus != '0' OR gr.status != '0' OR ul.requires != '')" : '') .
				($this->nurAktiveLayer ? " AND (rl.aktivStatus != '0')" : '') .
				($this->OhneRequires ? " AND (ul.requires IS NULL)" : '') .
				($this->nurFremdeLayer ? " AND (c.host NOT IN ('pgsql', 'localhost') OR l.connectiontype != 6 AND rl.aktivStatus != '0')" : '') .
				($this->nurNameLike ? " AND l.Name LIKE '" . $this->nurNameLike . "'" : '') . 
				($this->nurPostgisLayer ? " AND l.connectiontype = 6" : '') . 
				($this->keinePostgisLayer ? " AND l.connectiontype != 6" : '') . 
				($this->nurLayerID ? " AND l.Layer_ID = " . $this->nurLayerID : '') .
				($this->nurLayerIDs ? " AND l.Layer_ID IN (" . $this->nurLayerIDs . ")" : '') .
				($this->nichtLayerID ? " AND l.Layer_ID != " . $this->nichtLayerID : '') . "
			ORDER BY
				drawingorder
		";
		# echo '<br>SQL zur Abfrage der Layer: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Layer - Lesen der Layer der Rolle:<br>", 4);
		$ret = $this->db->execSQL($sql);
		if (!$this->db->success) { 
			echo err_msg($this->script_name, __LINE__, $sql); 
			return 0;
		}
		$layer = array();
		$layer['list'] = array();
		$this->disabled_classes = $this->read_disabled_classes();
		$i = 0;
		while ($rs = $ret['result']->fetch_assoc()) {
			if ($rs['rollenfilter'] != '') {		// Rollenfilter zum Filter hinzufügen
				if ($rs['Filter'] == '') {
					$rs['Filter'] = '('.$rs['rollenfilter'].')';
				}
				else {
					$rs['Filter'] = str_replace(' AND ', ' AND ('.$rs['rollenfilter'].') AND ', $rs['Filter']);
				}
			}

			$rs['Name_or_alias'] = $rs[($rs['alias'] == '' OR !$useLayerAliases) ? 'Name' : 'alias'];
			$rs['id'] = $i;
			$rs['alias_link'] = replace_params_link(
				$rs['Name_or_alias'],
				rolle::$layer_params,
				$rs['Layer_ID']
			);
			foreach (array('Name', 'alias', 'Name_or_alias', 'connection', 'classification', 'classitem', 'tileindex', 'pfad', 'Data') AS $key) {
				$rs[$key] = replace_params(
					$rs[$key],
					rolle::$layer_params,
					$this->User_ID,
					$this->Stelle_ID,
					rolle::$hist_timestamp,
					$this->rolle->language,
					$rs['duplicate_criterion']
				);
			}
			if ($withClasses == 2 OR $rs['requires'] != '' OR ($withClasses == 1 AND $rs['aktivStatus'] != '0')) {
				# bei withclasses == 2 werden für alle Layer die Klassen geladen,
				# bei withclasses == 1 werden Klassen nur dann geladen, wenn der Layer aktiv ist
				$rs['Class'] = $this->read_Classes($rs['Layer_ID'], $this->disabled_classes, false, $rs['classification']);
			}
			if ($rs['maxscale'] > 0) {
				$rs['maxscale'] = $rs['maxscale'] + 0.3;
			}
			if ($rs['minscale'] > 0) {
				$rs['minscale'] = $rs['minscale'] - 0.3;
			}
			$rs['datasource_ids'] = implode(',', array_map(function($datasource) { return $datasource->get('id'); }, DataSource::find_by_layer_id($this->GUI, $rs['Layer_ID'])));
			$layer['list'][$i] = $rs;
			# Pointer auf requires-Array
			$layer['list'][$i]['required'] =& $requires_layer[$rs['Layer_ID']];
			if ($rs['requires'] != '') {
				# requires-Array füllen
				$requires_layer[$rs['requires']][] = $rs['Layer_ID'];
				if ($rs['queryable']) {
					# wenn der untergeordnete Layer queryable ist, wird queryable auch beim übergeordneten gesetzt, damit die Checkbox in der Legende erscheint
					$layer['layer_ids'][$rs['requires']]['queryable'] = $rs['queryable'];
				}
			}
			$layer['layer_ids'][$rs['Layer_ID']] =& $layer['list'][$i]; # damit man mit einer Layer-ID als Schlüssel auf dieses Array zugreifen kann
			$i++;
		}
		return $layer;
	}

  function read_disabled_classes() {
		$sql = "
			SELECT
				class_id,
				status
			FROM
				u_rolle2used_class
			WHERE
				user_id = " . $this->User_ID . "
				AND stelle_id = " . $this->Stelle_ID . "
		";
		#echo '<p>SQL zur Abfrage von diabled classes: ' . $sql;
		$this->db->execSQL($sql);
    while ($row = $this->db->result->fetch_assoc()) {
  		$classarray['class_id'][] = $row['class_id'];
			$classarray['status'][$row['class_id']] = $row['status'];
		}
		return $classarray;
  }

	function read_Classes($Layer_ID, $disabled_classes = NULL, $all_languages = false, $classification = '') {
		global $language;
		$Classes = array();

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
				NULLIF(classification, '') IS NULL,
				classification,
				drawingorder,
				Class_ID
		";
		#echo $sql.'<br>';
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Class - Lesen der Classen eines Layers:<br>" . $sql, 4);
		$ret = $this->db->execSQL($sql);
		if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name . " Zeile: " . __LINE__ .'<br>'.$sql; return 0; }
		$index = 0;
		while ($rs = $ret['result']->fetch_assoc()) {
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
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Styles - Lesen der Styledaten:<br>" . $sql,4);
    $ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
    while($rs = $this->db->result->fetch_assoc()) {
      $Styles[]=$rs;
    }
    return $Styles;
  }

	function read_Label($Class_ID) {
		$Labels = array();
		$sql = "
			SELECT
				*
			FROM
				labels AS l,
				u_labels2classes AS l2c
			WHERE
				l.Label_ID = l2c.label_id
				AND l2c.class_id = " . $Class_ID . "
		";
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Label - Lesen der Labels zur Classe eines Layers:<br>" . $sql,4);
		$this->db->execSQL($sql);
		if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
		while ($rs = $this->db->result->fetch_assoc()) {
			$Labels[]=$rs;
		}
		return $Labels;
	}

	function read_RollenLayer($id = NULL, $typ = NULL){
		$sql = "
			SELECT DISTINCT
				l.`id`,
				l.`user_id`,
				l.`stelle_id`,
				l.`aktivStatus`,
				l.`queryStatus`,
				l.`Name`,
				l.`Name` as alias,
				l.`Gruppe`,
				l.`Typ`,
				l.`Datentyp`,
				l.`Data`,
				l.`query`,
				l.`connectiontype`,
				l.connection_id,
				CASE
					WHEN connectiontype = 6 THEN concat('host=', c.host, ' port=', c.port, ' dbname=', c.dbname, ' user=', c.user, ' password=', c.password)
					ELSE l.connection
				END as connection,
				l.`epsg_code`,
				l.`transparency`,
				l.`labelitem`,
				l.`classitem`,
				l.`gle_view`,
				l.`rollenfilter`,
				l.`duplicate_from_layer_id`,
				l.`duplicate_criterion`,
				g.Gruppenname,
				-l.id AS Layer_ID,
				1 as showclasses,
				CASE WHEN Typ = 'import' THEN 1 ELSE 0 END as queryable,
				CASE WHEN rollenfilter != '' THEN concat('(', rollenfilter, ')') END as Filter
			FROM
				rollenlayer AS l JOIN
				u_groups AS g ON l.Gruppe = g.id LEFT JOIN
				connections AS c ON l.connection_id = c.id
			WHERE
				l.stelle_id=" . $this->Stelle_ID . " AND
				l.user_id = " . $this->User_ID .
				($id != NULL ? " AND l.id = " . $id : '') .
				($typ != NULL ? " AND l.Typ = '" . $typ . "'" : '') . "
		";
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_RollenLayer - Lesen der RollenLayer:<br>",4);
		# echo '<p>SQL zur Abfrage der Rollenlayer: ' . $sql;
		$ret = $this->db->execSQL($sql);
		if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
    $Layer = array();
    while ($rs = $ret['result']->fetch_array()) {
      $rs['Class'] = $this->read_Classes(-$rs['id'], $this->disabled_classes);
			foreach (array('Name', 'alias', 'connection', 'classification', 'pfad', 'Data') AS $key) {
				$rs[$key] = replace_params(
					$rs[$key],
					rolle::$layer_params,
					$this->User_ID,
					$this->Stelle_ID,
					rolle::$hist_timestamp,
					$this->rolle->language,
					$rs['duplicate_criterion']
				);
			}
			$rs['alias_link'] = $rs['alias'];
      $Layer[] = $rs;
    }
    return $Layer;
  }
}
?>
