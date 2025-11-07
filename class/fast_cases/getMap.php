<?

function before_last($txt, $delimiter) {
	#echo '<br>Return the part of ' . $txt . ' before the last occurence of ' . $delimiter;
	if (!$delimiter) {
		return '';
	}
	$pos = strrpos($txt, $delimiter);
  return $pos === false ? $txt : substr($txt, 0, $pos);
}

/**
 * Function returns the parts of a mapserver data statement (geom, inner select, using)
 * @param string $data Mapserver data statement
 * @return Array inner sql
 */
function getDataParts($data){
	$first_space_pos = strpos($data, ' ');
	$geom = substr($data, 0, $first_space_pos);					# geom am Anfang
	$rest = substr($data, $first_space_pos);
	$usingposition = strpos($rest, 'using');
	$from = substr($rest, 0, $usingposition);						# from (alles zwischen geom und using)
	$using = substr($rest, $usingposition);							# using ...
	if(strpos($from, '(') === false){		# from table
		$select = 'select * '.$from.' where 1=1';
	}
	else{		# from (select ... from ...) as foo
		$select = stristr($from,'(');
		$select = before_last($select, ')');
		$select = ltrim($select, '(');
	}
	return [
		'geom' => $geom,
		'select' => $select,
		'using' => $using
	];
}

function count_or_0($val) {
	if (is_null($val) OR !is_array($val)) {
		return 0;
	}
	else {
		return count($val);
	}
}

if (MAPSERVERVERSION < 800) {
	function msGetErrorObj(){
		return ms_GetErrorObj();
	}

	function msResetErrorList(){
		return ms_ResetErrorList();
	}
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

function value_of($array, $key) {
	if (!is_array($array)) {
		$array = array();
	}
	return (array_key_exists($key, $array) ? $array[$key] :	'');
}

function replace_params_link($str, $params, $layer_id) {
	if (is_array($params)) {
		foreach($params AS $key => $value){
			$str = str_replace('$'.$key, '<a href="javascript:void(0)" onclick="getLayerOptions(' . $layer_id .  ')">' . $value . '</a>', $str);
		}
	}
	return $str;
}

/**
* Funktion ersetzt in $str die Schlüsselwörter, die in rolle::$layer_params als key enthalten sind durch deren values.
* Zusätzlich werden die vordefinierten Parameter ($USER_ID usw.) ersetzt
* Im optionalen Array $additional_params können weitere zu ersetzende key-value-Paare übergeben werden
*/
function replace_params_rolle($str, $additional_params = NULL) {
	if (strpos($str, '$') !== false) {
		$params = rolle::$layer_params;
		if (is_array($additional_params)) {
			$params = array_merge($params, $additional_params);
		}
		$str = replace_params($str, $params);
		$current_time = time();
		$str = str_replace('$CURRENT_DATE', date('Y-m-d', $current_time), $str);
		$str = str_replace('$CURRENT_TIMESTAMP', date('Y-m-d G:i:s', $current_time), $str);
		$str = str_replace('$USER_ID', rolle::$user_ID, $str);
		$str = str_replace('$STELLE_ID', rolle::$stelle_ID, $str);
		$str = str_replace('$STELLE', rolle::$stelle_bezeichnung, $str);
		$str = str_replace('$HIST_TIMESTAMP', rolle::$hist_timestamp, $str);
		$str = str_replace('$LANGUAGE', rolle::$language, $str);
		$str = str_replace('$EXPORT', rolle::$export, $str);
	}
	return $str;
}

function replace_params($str, $params) {
	if (is_array($params)) {
		foreach ($params AS $key => $value) {
			$str = str_replace('$'.$key, $value, $str);
		}
	}
	return $str;
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
	return $name;
}

function sonderzeichen_umwandeln($name) {
	$name = umlaute_umwandeln($name);
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
	var $mapdb;
	var $img;
	var $FormObject;
	var $StellenForm;
	var $Fehlermeldung;
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
	var $map_factor='';
	var $formatter;
	var $success = true;
	var $login_failed;
	var $only_main = false;
	var $class_load_level;
	var $layer_id_string;
	var $noMinMaxScaling;
	var $stelle_id;
	var $angle_attribute;
	var $titel;
	var $PasswordError;
	var $Meldung;
	var $radiolayers;
	var $show_query_tooltip;
	var $last_query;
	var $querypolygon;
	var $new_entry;
	var $search;
	var $form_field_names;
	var $editable;
	var $groupset;
	var $use_form_data;
	var $stelle;
	var $zoomed;
	var $error_position;
	var $selected_search;
	var $attributes = array();
	var $scrolldown;
	var $queryrect;
	var $notices;
	var $layers_replace_scale = array();
	static $messages = array();

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
		$this->custom_trigger_functions = array();

		$this->queryrect = new rectObj();
	}

	function plugin_loaded($plugin) {
		global $kvwmap_plugins;
		return in_array($plugin, $kvwmap_plugins);
	}

	function loadMultiLingualText($language) {
    #echo 'In der Rolle eingestellte Sprache: '.rolle::$language;
    $this->Stelle->language=$language;
    include(LAYOUTPATH.'languages/'.$language.'.php');
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

				if (MS_DEBUG_LEVEL !== NULL) {
					$map->setConfigOption('MS_ERRORFILE', dirname($this->debug->filename) . '/mapserver' . $this->user->id . '.log');
					$map->debug = MS_DEBUG_LEVEL;
				}
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
				$this->error_message = '';
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
						$error = msGetErrorObj();
						while ($error && $error->code != MS_NOERR) {
							$this->error_message .= '<br>Fehler beim Laden des Layers mit der Layer-ID: ' . $layerset['list'][$i]['Layer_ID'] . 
							'<br>&nbsp;&nbsp;in der Routine ' . $error->routine . ' Msg="' . $error->message . '" code=' . $error->code;
							$error = $error->next();
						}
						msResetErrorList();
					}
				}
				if ($this->error_message != '') {
					$this->error_message .= '<br>';
					//  throw new ErrorException($this->error_message);
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

		/**
	 * Return a drawing order with default layers to top
	 */
	function get_default_layers_top_drawing_order($num_layers, $num_default_layers) {
		$drawing_order = range($num_default_layers, $num_layers - 1);
		foreach(range(0, $num_default_layers - 1) AS $order) {
			$drawing_order[] = $order;
		}
		return $drawing_order;
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
		$layer = new LayerObj($map);
		$layer->name = $layerset[$layer_name_attribute];
		$layer->metadata->set('wms_name', $layerset['wms_name'] ?: ''); #Mapserver8
		$layer->metadata->set('kvwmap_layer_id', $layerset['Layer_ID']);
		$layer->metadata->set('wfs_request_method', 'GET');
		if ($layerset['wms_keywordlist']) {
			$layer->metadata->set('ows_keywordlist', $layerset['wms_keywordlist']);
		}
		$layer->metadata->set('wfs_typename', $layerset['wms_name'] ?: ''); #Mapserver8
		$layer->metadata->set('wms_title', $layerset['Name_or_alias'] ?: ''); #Mapserver8
		$layer->metadata->set('wfs_title', $layerset['Name_or_alias'] ?: ''); #Mapserver8
		# Umlaute umwandeln weil es in einigen Programmen (masterportal und MapSolution) mit Komma und Leerzeichen in wms_group_title zu problemen kommt.
		$layer->metadata->set('wms_group_title', sonderzeichen_umwandeln($layerset['Gruppenname']));
		$layer->metadata->set('wms_queryable',$layerset['queryable']);
		$layer->metadata->set('wms_format',$layerset['wms_format'] ?: ''); #Mapserver8
		$layer->metadata->set('ows_server_version',$layerset['wms_server_version'] ?: ''); #Mapserver8
		$layer->metadata->set('ows_version',$layerset['wms_server_version'] ?: ''); #Mapserver8
		if ($layerset['metalink']) {
			$layer->metadata->set('ows_metadataurl_href',$layerset['metalink']);
			$layer->metadata->set('ows_metadataurl_type', 'ISO 19115');
			$layer->metadata->set('ows_metadataurl_format', 'text/plain');
		}
		if ($layerset['ows_srs'] == '') {
			$layerset['ows_srs'] = 'EPSG:' . $layerset['epsg_code'];
		}		
		$layer->metadata->set('ows_srs', $layerset['ows_srs']);
		$layer->metadata->set('wms_connectiontimeout',$layerset['wms_connectiontimeout'] ?: ''); #Mapserver8
		$layer->metadata->set('ows_auth_username', $layerset['wms_auth_username'] ?: '');
		$layer->metadata->set('ows_auth_password', $layerset['wms_auth_password'] ?: '');
		$layer->metadata->set('ows_auth_type', 'basic');
		$layer->metadata->set('wms_exceptions_format', ($layerset['wms_server_version'] == '1.3.0' ? 'XML' : 'application/vnd.ogc.se_xml'));
		# ToDo: das Setzen von ows_extent muss in dem System erfolgen, in dem der Layer definiert ist (erstmal rausgenommen)
		#$layer->metadata->set("ows_extent", $bb->minx . ' '. $bb->miny . ' ' . $bb->maxx . ' ' . $bb->maxy);		# führt beim WebAtlas-WMS zu einem Fehler
		$layer->metadata->set("gml_featureid", $layerset['oid'] ?: ''); #Mapserver8
		$layer->metadata->set("gml_include_items", "all");
		#$layer->metadata->set('wms_abstract', $layerset['kurzbeschreibung']); #Mapserver8
		$layer->dump = 0;
		$layer->type = $layerset['Datentyp'];
		$layer->group = sonderzeichen_umwandeln($layerset['Gruppenname']);

		if(value_of($layerset, 'status') != ''){
			$layerset['aktivStatus'] = 0;
		}


		//---- wenn die Layer einer eingeklappten Gruppe nicht in der Karte //
		//---- dargestellt werden sollen, muß hier bei aktivStatus != 1 //
		//---- der layer_status auf 0 gesetzt werden//
		if ($layerset['aktivStatus'] == 0) {
			$layer->status = 0;
		}
		else{
			$layer->status = 1;
		}

		# fremde Layer werden auf Verbindung getestet
		if ($layerset['aktivStatus'] != 0 AND $layerset['connectiontype'] == 6) {
			$credentials = $this->pgdatabase->get_credentials($layerset['connection_id']);
			if (!in_array($credentials['host'], array('pgsql', 'localhost'))) {
				$fp = @fsockopen($credentials['host'], $credentials['port'], $errno, $errstr, 5);
				if (!$fp) {			# keine Verbindung --> Layer ausschalten
					$layer->status = 0;
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
				$layer->minscaledenom = $layerset['minscale']/$this->map_factor*1.414;
			}
			else{
				$layer->minscaledenom = $layerset['minscale'];
			}
		}
		if(!$this->noMinMaxScaling AND value_of($layerset, 'maxscale') > 0) {
			if($this->map_factor != ''){
				$layer->maxscaledenom = $layerset['maxscale']/$this->map_factor*1.414;
			}
			else{
				$layer->maxscaledenom = $layerset['maxscale'];
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
			$layer->connection = $layerset['connection'];
		}

		if ($layerset['connectiontype'] > 0) {
			$layer->setConnectionType($layerset['connectiontype'], '');
		}

		if ($layerset['connectiontype'] == 6) {
			$layerset['processing'] = 'CLOSE_CONNECTION=ALWAYS;' . value_of($layerset, 'processing');		# DB-Connection erst am Ende schliessen und nicht für jeden Layer neu aufmachen
		}

		if ($layerset['processing'] != "") {
			$processings = explode(";",
				replace_params_rolle($layerset['processing'])
			);
			foreach ($processings as $processing) {
				if (MAPSERVERVERSION >= 800) {
					$p = explode('=', $processing);
					$layer->setProcessingKey($p[0], $p[1]);
				}
				else {
					$layer->setProcessing($processing);
				}
			}
		}

		if (value_of($layerset, 'postlabelcache') != 0) {
			$layer->postlabelcache = $layerset['postlabelcache'];
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
				$layer->tileindex = SHAPEPATH.$layerset['tileindex'];
			}
			else {
				$layer->data = $layerset['Data'];
			}
			$layer->tileitem = $layerset['tileitem'];
			if ($layerset['offsite']!='') {
				$RGB=explode(' ',$layerset['offsite']);
				$layer->offsite->setRGB($RGB[0],$RGB[1],$RGB[2]);
			}
		}
		else {
			# Vektorlayer
			if ($layerset['Data'] != '') {
				if (strpos($layerset['Data'], '$SCALE') !== false){
					$this->layers_replace_scale[] =& $layer;
				}
				$layer->data = $layerset['Data'];
			}
			
			if (value_of($layerset, 'buffer') != NULL AND value_of($layerset, 'buffer') != 0) {
				$geography = (in_array($layerset['epsg_code'], [4326, 4258])? '::geography' : '');
				$data_parts = getDataParts($layer->data);
				$layer->data = 'geom1 from (select st_buffer(' . $data_parts['geom'] . $geography . ', ' . $layerset['buffer'] . ') as geom1, * from ('. $data_parts['select'] . ') as foo) as fooo ' . $data_parts['using'];
				$layer->type = 2;
			}

			# Setzen der Templatedateien für die Sachdatenanzeige inclt. Footer und Header.
			# Template (Body der Anzeige)
			if (value_of($this->formvars, 'go') == 'OWS') {
				$layer->template = 'dummy';
			}
			# Header (Kopfdatei)
			if (value_of($layerset, 'header') != '') {
				$layer->header = $layerset['header'];
			}
			# Footer (Fusszeile)
			if (value_of($layerset, 'footer') != '') {
				$layer->footer = $layerset['footer'];
			}
			# Setzen der Spalte nach der der Layer klassifiziert werden soll
			if ($layerset['classitem']!='') {
				$layer->classitem = $layerset['classitem'];
			}
			# Setzen des Filters
			if ($layerset['Filter'] != '') {
				$layerset['Filter'] = replace_params_rolle($layerset['Filter']);
				if (substr($layerset['Filter'], 0, 1) == '(') {
					switch (true) {
						case MAPSERVERVERSION >= 800 : {
							$layer->setProcessingKey('NATIVE_FILTER', $layerset['Filter']);
						}break;
						case MAPSERVERVERSION >= 700 : {
							$layer->setProcessing('NATIVE_FILTER=' . $layerset['Filter']);
						} break;
						default : {
							$layer->setFilter($layerset['Filter']);
						}
					}
			 }
			 else {
				 $expr=buildExpressionString($layerset['Filter']);
				 $layer->setFilter($expr);
			 }
			}
			if ($layerset['styleitem']!='') {
				$layer->styleitem = $layerset['styleitem'];
			}
			# Layerweite Labelangaben
			if ($layerset['labelitem']!='') {
				$layer->labelitem = $layerset['labelitem'];
			}
			if (value_of($layerset, 'labelmaxscale') != '') {
				$layer->labelmaxscaledenom = $layerset['labelmaxscale'];
			}
			if (value_of($layerset, 'labelminscale') != '') {
				$layer->labelminscaledenom = $layerset['labelminscale'];
			}
			if (value_of($layerset, 'labelrequires') != '') {
				$layer->labelrequires = $layerset['labelrequires'];
			}
			if (value_of($layerset, 'tolerance') != '') {
				$layer->tolerance = $layerset['tolerance'];
			}
			if (value_of($layerset, 'toleranceunits') != '') {
				$layer->toleranceunits = constant('MS_' . strtoupper($layerset['toleranceunits']));
			}
			
			if (value_of($layerset, 'sizeunits') != '') {
				$layer->sizeunits = $layerset['sizeunits'];
			}
			if ($layerset['transparency']!=''){
				if ($layerset['transparency']==-1) {
						$layer->opacity = MS_GD_ALPHA;
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
					$layer->symbolscaledenom = $layerset['symbolscale']/$this->map_factor*1.414;
				}
				else{
					$layer->symbolscaledenom = $layerset['symbolscale'];
				}
			}
		} # ende of Vektorlayer
		$classset=$layerset['Class'];		
		$this->loadclasses($layer, $layerset, $classset, $map);
	}

  function loadclasses($layer, $layerset, $classset, $map){
		$anzClass = count_or_0($classset);
    for ($j = 0; $j < $anzClass; $j++) {
      $klasse = new ClassObj($layer);
      if ($classset[$j]['Name']!='') {
        $klasse->name = $classset[$j]['Name'];
      }
      if ($classset[$j]['Status']=='1'){
      	$klasse->status = MS_ON;
      }
      else {
      	$klasse->status = MS_OFF;
      }
			if (value_of($layerset, 'template') != '') {
				$klasse->template = value_of($layerset, 'template');
			}
			$klasse->setexpression(str_replace([chr(10), chr(13)], '', $classset[$j]['Expression']));
      if ($classset[$j]['text'] != '' AND is_null($layerset['user_labelitem'])) {
				$klasse->settext("'" . trim($classset[$j]['text'], "'") . "'");
      }
      if ($classset[$j]['legendgraphic'] != '') {
				$imagename = WWWROOT . APPLVERSION . CUSTOM_PATH . 'graphics/' . $classset[$j]['legendgraphic'];
				$klasse->keyimage = $imagename;
			}			
      for ($k = 0; $k < count_or_0($classset[$j]['Style']); $k++) {
        $dbStyle = $classset[$j]['Style'][$k];
				$style = new styleObj($klasse);
				if ($dbStyle['geomtransform'] != '') {
					$style->updateFromString('STYLE GEOMTRANSFORM "' . $dbStyle['geomtransform'] . '" END');
				}
				if ($dbStyle['minscale'] != ''){
					$style->minscaledenom = $dbStyle['minscale'];
				}
				if($dbStyle['maxscale'] != ''){
					$style->maxscaledenom = $dbStyle['maxscale'];
				}
				if ($layerset['Datentyp'] == 0 AND value_of($layerset, 'buffer') != NULL AND value_of($layerset, 'buffer') != 0) {
					$dbStyle['symbolname'] = NULL;
					$dbStyle['symbol'] = NULL;
				}
				if (substr($dbStyle['symbolname'], 0, 1) == '[') {
					$style->updateFromString('STYLE SYMBOL ' .$dbStyle['symbolname']. ' END');
				}
				else {
					if ($dbStyle['symbolname']!='') {
						if (MAPSERVERVERSION < 800) {
							$style->symbolname = $dbStyle['symbolname'];
						}
						else {
							$style->setSymbolByName($map, $dbStyle['symbolname']);
						}
					}
					if ($dbStyle['symbol']>0) {
						$style->symbol = $dbStyle['symbol'];
					}
				}				
				if ($dbStyle['geomtransform'] != '') {
					# Es scheint so als würde phpMapScript in dieser Version centroid und andere ignorieren
					# centroid wird zwar gesetzt was man mit getGeomTransform abfragen kann, aber
					# es landet nicht im STYLE Objekt was man mit convertToString abfragen kann.
					# centroid und andere z.B. simplify werden nicht in der MapDatei mit saveMap ausgegeben!
					# Ein workaround wäre irgend etwas anderes einzufügen, was sich auf den Style nicht tauswirkt
					# und das dann nach dem Speichern der MapDatei mit dem richtigen zu ersetzen.
					# Man könnte sich auch alle Symbole merken die als center gesetzt werden sollen
					# und nach dem Speichern des Mapfiles
					# SYMBOL "symbolname"
					# durch
					# SYMBOL "symbolname"
					# GEOMTRANSFORM "centroid"
					# ersetzen. Das hat den Nachteil, dass centroid auch für Styles gesetzt wird
					# die zwar das Symbol verwenden aber nicht centroid sein sollen.
					# Man könnte auch eine bestimmte OUTLINEWITH schreiben. Die wirkt sich nur auf (ellipse, truetype and polygon vector symbols) aus
					# diese OUTLINEWITH wurde hier im Code bisher noch garnicht verwendet könnte aber auch mit einer bestimmten Konstante ergänzt werden
					# z.B. OUTLINEWITH + 0.000001 Das wird dann erstetzt durch das ohne .000001 und die GEOMTRANSFORM centroid
					# Man könnten auch für verschiedene Konstanten unterschiedliche GEOMTRANSFORM, die auch nicht funktionieren ersetzen.
					$style->updateFromString('STYLE GEOMTRANSFORM "' . $dbStyle['geomtransform'] . '" END');
					$style->updateFromString('STYLE OUTLINEWIDTH 1.00001 END');
				}
				if ($dbStyle['pattern'] != '') {
					if ($this->map_factor != '') {
						$pattern = explode(' ', $dbStyle['pattern']);
						foreach($pattern as &$pat){
							$pat = $pat * $this->map_factor;
						}
						$style->updateFromString("STYLE PATTERN " . implode(' ', $pattern) . " END END");
					}
					else {
						$style->updateFromString("STYLE PATTERN " . $dbStyle['pattern']." END END");		
					}
					$style->linecap = 'butt';
				}
				if($dbStyle['gap'] != '') {
					if($this->map_factor != ''){
						$style->gap = $dbStyle['gap']*$this->map_factor/1.414;
					}
					else{
						$style->gap = $dbStyle['gap'];
					}
				}		
				if($dbStyle['initialgap'] != '') {
					$style->initialgap = $dbStyle['initialgap'];
				}
				if($dbStyle['linecap'] != '') {
					$style->linecap = constant('MS_CJC_'.strtoupper($dbStyle['linecap']));
				}
				else {
					$style->linecap = constant('MS_CJC_ROUND');
				}
				if($dbStyle['linejoin'] != '') {
					$style->linejoin = constant('MS_CJC_'.strtoupper($dbStyle['linejoin']));
				}
				if($dbStyle['linejoinmaxsize'] != '') {
					$style->linejoinmaxsize = $dbStyle['linejoinmaxsize'];
				}
				if($dbStyle['polaroffset'] != '') {
					$style->updateFromString("STYLE POLAROFFSET " . $dbStyle['polaroffset']." END");
				}

				if($dbStyle['size'] != ''){
					if ($layerset['Datentyp'] == 8) {
						# Skalierung der Stylegröße when Type Chart
						$style->setbinding(MS_STYLE_BINDING_SIZE, $dbStyle['size']);
					}
					else {
						if ($this->map_factor != '') {
							if (is_numeric($dbStyle['size'])) {
								$style->size = $dbStyle['size'] * $this->map_factor/1.414;
							}
							else {
								$style->updateFromString("STYLE SIZE [" . $dbStyle['size']."] END");
							}
						}
						else {
							if (is_numeric($dbStyle['size'])) {
								$style->size = $dbStyle['size'];
							}
							else {
								$style->updateFromString("STYLE SIZE [" . $dbStyle['size']."] END");
							}
						}
					}
				}

        if ($dbStyle['minsize']!='') {
          if ($this->map_factor != ''){
            $style->minsize = $dbStyle['minsize']*$this->map_factor/1.414;
          }
          else {
            $style->minsize = $dbStyle['minsize'];
          }
        }

        if ($dbStyle['maxsize']!='') {
          if($this->map_factor != ''){
            $style->maxsize = $dbStyle['maxsize']*$this->map_factor/1.414;
          }
          else{
            $style->maxsize = $dbStyle['maxsize'];
          }
        }

				if($dbStyle['angle'] != '') {
					$style->updateFromString("STYLE ANGLE " . $dbStyle['angle'] . " END"); 		# wegen AUTO
				}

				if ($dbStyle['angleitem']!=''){
					$style->setbinding(MS_STYLE_BINDING_ANGLE, $dbStyle['angleitem']);
        }
        if ($dbStyle['width']!='') {
					if ($this->map_factor != '') {
						if (is_numeric($dbStyle['width'])) {
							$style->width = $dbStyle['width']*$this->map_factor/1.414;
						}
						else {
							$style->updateFromString("STYLE WIDTH [" . $dbStyle['width']."] END");
						}
					}
					else{
						if (is_numeric($dbStyle['width'])) {
							$style->width = $dbStyle['width'];
						}
						else {
							$style->updateFromString("STYLE WIDTH [" . $dbStyle['width']."] END");
						}
					}
        }
		
        if ($dbStyle['minwidth']!='') {
          if ($this->map_factor != '') {
            $style->minwidth = $dbStyle['minwidth']*$this->map_factor/1.414;
          }
          else {
            $style->minwidth = $dbStyle['minwidth'];
          }
        }

        if ($dbStyle['maxwidth']!='') {
          if ($this->map_factor != '') {
            $style->maxwidth = $dbStyle['maxwidth'] * $this->map_factor/1.414;
          }
          else {
            $style->maxwidth = $dbStyle['maxwidth'];
          }
        }

        if ($dbStyle['color']!='') {
          $RGB = array_filter(explode(" ",$dbStyle['color']), 'strlen');
          if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
          if(is_numeric($RGB[0]))$style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
					else $style->updateFromString("STYLE COLOR [" . $dbStyle['color']."] END");
        }
				if ($dbStyle['opacity'] != '') {		# muss nach color gesetzt werden
					$style->opacity = $dbStyle['opacity'];
				}
        if ($dbStyle['outlinecolor']!='') {
          $RGB = array_filter(explode(" ",$dbStyle['outlinecolor']), 'strlen');
        	if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
					if(is_numeric($RGB[0]))$style->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
					else $style->updateFromString("STYLE OUTLINECOLOR [" . $dbStyle['outlinecolor']."] END");					
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
				$offset_attribute = false;
				if (!is_numeric($dbStyle['offsetx'] ?: 0)){
					$dbStyle['offsetx'] = '[' . $dbStyle['offsetx'] . ']';
					$offset_attribute = true;
				}
				if (!is_numeric($dbStyle['offsety'] ?: 0)){
					$dbStyle['offsety'] = '[' . $dbStyle['offsety'] . ']';
					$offset_attribute = true;
				}
				if ($offset_attribute) {
					$style->updateFromString("STYLE offset " . ($dbStyle['offsetx'] ?: '0') . " " . ($dbStyle['offsety'] ?: '0') . " END");
				}
				else {
					if ($dbStyle['offsetx']!='') {
						$style->offsetx = $dbStyle['offsetx'];
					}
					if ($dbStyle['offsety']!='') {
						$style->offsety = $dbStyle['offsety'];
					}
				}
      } # Ende Schleife für mehrere Styles
	  
      # setzen eines oder mehrerer Labels
      # Änderung am 12.07.2005 Korduan
      for ($k=0;$k<count($classset[$j]['Label']);$k++) {
        $dbLabel=$classset[$j]['Label'][$k];
				$label = new labelObj();
				if (MAPSERVERVERSION < 700 ) {
					$label->type = 'truetype';
				}
				if ($dbLabel['text'] != '') {
					$label->updateFromString("LABEL TEXT '" . $dbLabel['text'] . "' END");
				}				
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
					if (MAPSERVERVERSION < 800 ) {
						$style = new styleObj($label);
					}
					else {
						$style = new styleObj();
					}
					$style->setGeomTransform('labelpoly');
					$style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
					$style->offsetx = $dbLabel['backgroundshadowsizex'];
					$style->offsety = $dbLabel['backgroundshadowsizey'];
					if ($dbLabel['buffer']!='') {
						$style->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
						$style->width = $dbLabel['buffer'];
					}
					$label->insertStyle($style);
				}
				if ($dbLabel['backgroundcolor']!='') {
					$RGB=explode(" ",$dbLabel['backgroundcolor']);
					if (MAPSERVERVERSION < 800 ) {
						$style = new styleObj($label);
					}
					else {
						$style = new styleObj();
					}
					$style->setGeomTransform('labelpoly');
					$style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
					if ($dbLabel['buffer']!='') {
						$style->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
						$style->width = $dbLabel['buffer'];
					}
					$label->insertStyle($style);
				}

				$label->angle = $dbLabel['angle'];
				if(value_of($layerset, 'labelangleitem') != ''){
					$label->setBinding(MS_LABEL_BINDING_ANGLE, $layerset['labelangleitem']);
				}
				if($dbLabel['anglemode'] != NULL) {
					$label->anglemode = $dbLabel['anglemode'];
				}
				if ($dbLabel['buffer']!='') {
					$label->buffer = $dbLabel['buffer'];
				}
				$label->maxlength = $dbLabel['maxlength'];
				$label->repeatdistance = $dbLabel['repeatdistance'];
				if (MAPSERVERVERSION >= 800) {
					$label->wrap = chr($dbLabel['wrap']);
				}
				else {
					$label->wrap = $dbLabel['wrap'];
				}
				$label->force = $dbLabel['the_force'];
				$label->partials = $dbLabel['partials'];
				$label->size = $dbLabel['size'];
				$label->minsize = $dbLabel['minsize'];
				$label->maxsize = $dbLabel['maxsize'];
				$label->minfeaturesize = $dbLabel['minfeaturesize'];
				if ($dbLabel['maxscale'] != '') {
					$label->maxscaledenom = $dbLabel['maxscale'];
				}
				if ($dbLabel['minscale'] != '') {
					$label->minscaledenom = $dbLabel['minscale'];
				}
				# Skalierung der Labelschriftgröße, wenn map_factor gesetzt
				if($this->map_factor != ''){
					$label->minsize = $dbLabel['minsize']*$this->map_factor/1.414;
					$label->maxsize = $dbLabel['size']*$this->map_factor/1.414;
					$label->size = $dbLabel['size']*$this->map_factor/1.414;
				}
				if ($dbLabel['position']!='') {
					switch ($dbLabel['position']){
						case '0' :{
							$label->position = MS_UL;
						}break;
						case '1' :{
							$label->position = MS_LR;
						}break;
						case '2' :{
							$label->position = MS_UR;
						}break;
						case '3' :{
							$label->position = MS_LL;
						}break;
						case '4' :{
							$label->position = MS_CR;
						}break;
						case '5' :{
							$label->position = MS_CL;
						}break;
						case '6' :{
							$label->position = MS_UC;
						}break;
						case '7' :{
							$label->position = MS_LC;
						}break;
						case '8' :{
							$label->position = MS_CC;
						}break;
						case '9' :{
							$label->position = MS_AUTO;
						}break;
					}
				}
				$offset_attribute = false;
				if (!is_numeric($dbLabel['offsetx'] ?: 0)){
					$dbLabel['offsetx'] = '[' . $dbLabel['offsetx'] . ']';
					$offset_attribute = true;
				}
				if (!is_numeric($dbLabel['offsety'] ?: 0)){
					$dbLabel['offsety'] = '[' . $dbLabel['offsety'] . ']';
					$offset_attribute = true;
				}
				if ($offset_attribute) {
					$label->updateFromString("LABEL offset " . ($dbLabel['offsetx'] ?: '0') . " " . ($dbLabel['offsety'] ?: '0') . " END");
				}
				else {
					if ($dbLabel['offsetx']!='') {
						$label->offsetx = $dbLabel['offsetx'];
					}
					if ($dbLabel['offsety']!='') {
						$label->offsety = $dbLabel['offsety'];
					}
				}
				$klasse->addLabel($label);
      } # ende Schleife für mehrere Label
    } # end of Schleife Class
  }

  function drawMap($img_urls = false) {
    if($this->main == 'map.php' AND MINSCALE != '' AND $this->map_factor == '' AND $this->map_scaledenom < MINSCALE){
      $this->scaleMap(MINSCALE);
			$this->saveMap('');
    }
		# Parameter $scale in Data ersetzen
		for($i = 0; $i < count($this->layers_replace_scale); $i++){
			$this->layers_replace_scale[$i]->set('data', str_replace('$SCALE', $this->map_scaledenom, $this->layers_replace_scale[$i]->data));
		}

		try {
			$f = @fopen(dirname($this->debug->filename) . '/mapserver' . $this->user->id . '.log', 'r+');
			if ($f !== false) {
					ftruncate($f, 0);
					fclose($f);
			}
    	$this->image_map = $this->map->draw();
		}
		catch (Exception $ex) {
			$this->map->setConfigOption('MS_ERRORFILE', LOGPATH . 'mapserver.log');
			#msCleanup();
			global $errors;
			$errors[] = file_get_contents(dirname($this->debug->filename) . '/mapserver' . $this->user->id . '.log');
			throw new Exception('Fehler beim Erzeugen des Kartenbildes');
		}
		
		#if (!$img_urls) { wegen Bug im Firefox
		if (false) {
			if (MAPSERVERVERSION >= 800) {
				$image = $this->image_map->getBytes();
			}
			else {
				ob_start();
				$this->image_map->saveImage();
				$image = ob_get_clean();
			}
			$this->img['hauptkarte'] = 'data:image/jpg;base64,'.base64_encode($image);
		}
		else {
			$filename = $this->user->id.'_'.rand(0, 1000000).'.'.$this->map->outputformat->extension;
			if (MAPSERVERVERSION >= 800) {
				$this->image_map->save(IMAGEPATH . $filename);
			}
			else {
				$this->image_map->saveImage(IMAGEPATH . $filename);
			}
			$this->img['hauptkarte'] = IMAGEURL . $filename;
		}
		if ($this->formvars['go'] == 'getMap'){
			$this->outputfile = $filename;
			return;
		}
		if ($this->formvars['go'] != 'navMap_ajax'){
			$this->legende = $this->create_dynamic_legend();
			$this->debug->write("Legende erzeugt", 4);
		}
		else {
			# Zusammensetzen eines Layerhiddenstrings, in dem die aktuelle Sichtbarkeit aller aufgeklappten Layer gespeichert ist um damit bei Bedarf die Legende neu zu laden
			for($i = 0; $i < $this->layerset['anzLayer']; $i++) {
				$layer=&$this->layerset['list'][$i];
				if($layer['requires'] == ''){
					if($this->check_layer_visibility($layer))$layerhiddenflag = '0';
					else $layerhiddenflag = '1';
					$this->layerhiddenstring .= $layer['Layer_ID'].' '.$layerhiddenflag.' ';
				}
			}
		}

		# Erstellen des Maßstabes
		$this->map_scaledenom = $this->map->scaledenom;
    $this->switchScaleUnitIfNecessary();
		$this->map->selectOutputFormat('png');
    $img_scalebar = $this->map->drawScaleBar();
		if(!$img_urls){
			if (MAPSERVERVERSION >= 800) {
				$image = $img_scalebar->getBytes();
			}
			else {
				ob_start();
				$img_scalebar->saveImage();
				$image = ob_get_clean();
			}
			$this->img['scalebar'] = 'data:image/png;base64,'.base64_encode($image);
		}
		else{
			$filename = $this->user->id.'_'.rand(0, 1000000).'.png';
			if (MAPSERVERVERSION >= 800) {
				$this->img['scalebar'] = $img_scalebar->save(IMAGEPATH.$filename);
			}
			else {
				$this->img['scalebar'] = $img_scalebar->saveImage(IMAGEPATH.$filename);
			}
			$this->img['scalebar'] = IMAGEURL.$filename;
		}
		$this->calculatePixelSize();
		$this->drawReferenceMap();
  }

	function output() {
		global $sizes;
		# bisher gibt es folgenden verschiedenen Dokumente die angezeigt werden können
		if (array_key_exists('mime_type', $this->formvars) AND $this->formvars['mime_type'] != '') {
			$this->mime_type = $this->formvars['mime_type'];
		}
		switch ($this->mime_type) {
			case 'printversion' : {
				include (LAYOUTPATH . 'snippets/printversion.php');
			} break;
			case 'html' : {
				if ($this->formvars['window_type'] == 'overlay') {
					$this->overlaymain = $this->main;
					include (LAYOUTPATH . 'snippets/overlay.php');
				}
				else {
					if ($this->formvars['only_main']) {
						include_once(SNIPPETS . $this->main);
					}
					else {
						$guifile = $this->get_guifile();
						$this->debug->write("<br>Include <b>" . $guifile . "</b> in kvwmap.php function output()", 4);
						include($guifile);
					}
				}
				if ($this->alert != '') {
					echo '<script type="text/javascript">alert("'.$this->alert.'");</script>';			# manchmal machen alert-Ausgaben über die allgemeinde Funktioen showAlert Probleme, deswegen am besten erst hier am Ende ausgeben
				}
				if (!empty(GUI::$messages)) {
					$this->output_messages();
				}
      } break;
			case 'map_ajax' : {
				$this->debug->write("Include <b>".LAYOUTPATH."snippets/map_ajax.php</b> in kvwmap.php function output()",4);
				include (LAYOUTPATH . 'snippets/map_ajax.php');
			} break;
			case 'pdf' : {
				$this->formvars['file'] = 1;
				if ($this->formvars['file']) {
					header("Content-type: application/pdf");
					header("Content-Disposition: attachment; filename=\"" . $this->outputfile."\"");
					header("Expires: 0");
					header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
					header("Pragma: public");
					readfile(IMAGEPATH . $this->outputfile);
				}
				else {
					$this->pdf->ezStream();
				}
			} break;
			case 'image/jpeg' : case 'image/png' : {
				header("Content-type: " . $this->mime_type);
				header("Cache-Control: max-age=31536000, immutable");
				readfile(IMAGEPATH . $this->outputfile);
			} break;			
			default : {
				if ($this->formvars['format'] != '') {
					include('formatter.php');
					$this->formatter = new formatter(
						($this->formvars['format'] == 'json_result' ? $this->data : $this->qlayersetParamStrip()),
						$this->formvars['format'],
						$this->formvars['content_type'],
						$this->formvars['callback']
					);
					echo utf8_encode($this->formatter->output());
				}
			}
		}
	} # end of function output
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

	function __construct($open = false) {
		global $debug;
		global $GUI;
		$this->gui = $GUI;
		$this->debug = $debug;
		$this->loglevel = LOG_LEVEL;
		$this->defaultloglevel = LOG_LEVEL;
		global $log_mysql;
		$this->logfile = $log_mysql;
		$this->defaultlogfile = $log_mysql;
		$this->ist_Fortfuehrung = 1;
		$this->type = "MySQL";
		$this->commentsign = '#';
		# Wenn dieser Parameter auf 1 gesetzt ist werden alle Anweisungen
		# BEGIN TRANSACTION, ROLLBACK und COMMIT unterdrückt, so daß alle anderen SQL
		# Anweisungen nicht in Transactionsblöcken ablaufen.
		# Kann zur Steigerung der Geschwindigkeit von großen Datenbeständen verwendet werden
		# Vorsicht: Wenn Fehler beim Einlesen passieren, ist der Datenbestand inkonsistent
		# und der Einlesevorgang muss wiederholt werden bis er fehlerfrei durchgelaufen ist.
		# Dazu Fehlerausschriften bearchten.
		$this->blocktransaction = 0;
		if ($open) {
			$this->host = MYSQL_HOST;
			$this->user = MYSQL_USER;
			$this->passwd = MYSQL_PASSWORD;
			$this->dbName = MYSQL_DBNAME;
			$this->open();
		}
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
  var $has_logged_in;
  var $language;

	function __construct($login_name, $id, $database, $password = '', $archived = false) {
		global $debug;
		$this->debug = $debug;
		$this->database = $database;
		$this->has_logged_in = false;
		$this->login_name = $login_name;
		$this->id = (int) $id;
		$this->remote_addr = getenv('REMOTE_ADDR');
		$this->readUserDaten($this->id, $this->login_name, $password, $archived);
	}

	function readUserDaten($id, $login_name = '', $password = '', $archived) {
		$where = array();
		if ($id > 0) array_push($where, "ID = " . $id);
		if ($login_name != '') array_push($where, "login_name = '" . $this->database->mysqli->real_escape_string($login_name) . "'");
		if ($password != '') array_push($where, "password = SHA1('" . $this->database->mysqli->real_escape_string($password) . "')");
		if (!$archived) array_push($where, "archived IS NULL");
		$sql = "
			SELECT
				*
			FROM
				user
			WHERE
				" . implode(" AND ", $where);
		#echo '<br>SQL to read user data: ' . $sql;

		$this->debug->write("<p>file:users.php class:user->readUserDaten - Abfragen des Namens des Benutzers:<br>", 3);
		$this->database->execSQL($sql, 4, 0, true);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>' . $this->database->mysqli->error, 4); return 0; }
		$rs = $this->database->result->fetch_array(MYSQLI_ASSOC);
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
		$this->password_expired = $rs['password_expired'];
		$this->agreement_accepted = $rs['agreement_accepted'];
		$this->start = $rs['start'];
		$this->stop = $rs['stop'];
		$this->archived = $rs['archived'];
		$this->share_rollenlayer_allowed = $rs['share_rollenlayer_allowed'];
		$this->layer_data_import_allowed = $rs['layer_data_import_allowed'];
		$this->tokens = $rs['tokens'];
		$this->num_login_failed = $rs['num_login_failed'];
		$this->login_locked_until = $rs['login_locked_until'];
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
  var $data;

	function __construct($id, $database) {
		global $debug;
		global $log_mysql;
		$this->debug = $debug;
		$this->log = $log_mysql;
		$this->id = $id;
		$this->database = $database;
		$ret = $this->readDefaultValues();
	}

	function readDefaultValues() {
		global $language;
		if ($language != '' AND $language != 'german') {
			$name_column = "
			CASE
				WHEN s.`Bezeichnung_" . $language . "` != \"\" THEN s.`Bezeichnung_" . $language . "`
				ELSE s.`Bezeichnung`
			END AS Bezeichnung";
		}
		else {
			$name_column = "s.`Bezeichnung`";
		}

		$sql = "
			SELECT
				`ID`," .
				$name_column . ",
				`start`,
				`stop`, `minxmax`, `minymax`, `maxxmax`, `maxymax`, `epsg_code`, `Referenzkarte_ID`, `Authentifizierung`, `ALB_status`, `wappen`, `wappen_link`, `logconsume`,
				`ows_title`,
				`wms_accessconstraints`,
				`ows_abstract`,
				`ows_contactorganization`,
				`ows_contactemailaddress`,
				`ows_contactperson`,
				`ows_contactposition`,
				`ows_contactvoicephone`,
				`ows_contactfacsimile`,
				`ows_fees`,
				`ows_srs`,
				`protected`, `check_client_ip`, `check_password_age`, `allowed_password_age`, `use_layer_aliases`, `selectable_layer_params`, `hist_timestamp`, `default_user_id`, `style`
			FROM
				stelle s
			WHERE
				ID = " . $this->id . "
		";
		#echo 'SQL zum Abfragen der Stelle: ' . $sql;
		$this->debug->write('<p>file:stelle.php class:stelle->readDefaultValues - Abfragen der Default Parameter der Karte zur Stelle:<br>', 4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return $ret;
		}
		$rs = $this->database->result->fetch_array();
		$this->data = $rs;
		$this->Bezeichnung = $rs['Bezeichnung'];
		$this->MaxGeorefExt = rectObj($rs['minxmax'], $rs['minymax'], $rs['maxxmax'], $rs['maxymax']);
		$this->epsg_code = $rs['epsg_code'];
		$this->protected = $rs['protected'];
		//---------- OWS Metadaten ----------//
		$this->ows_title = $rs['ows_title'];
		$this->ows_abstract = $rs['ows_abstract'];
		$this->wms_accessconstraints = $rs['wms_accessconstraints'];
		$this->ows_contactorganization = $rs['ows_contactorganization'];
		$this->ows_contactelectronicmailaddress = $rs['ows_contactemailaddress'];
		$this->ows_contactperson = $rs['ows_contactperson'];
		$this->ows_contactposition = $rs['ows_contactposition'];
		$this->ows_contactvoicephone = $rs['ows_contactvoicephone'];
		$this->ows_contactfacsimile = $rs['ows_contactfacsimile'];
		$this->ows_fees = $rs['ows_fees'];
		$this->ows_srs = $rs['ows_srs'];
		$this->check_client_ip = $rs['check_client_ip'];
		$this->checkPasswordAge = $rs['check_password_age'];
		$this->allowedPasswordAge = $rs['allowed_password_age'];
		$this->useLayerAliases = $rs['use_layer_aliases'];
		$this->selectable_layer_params = $rs['selectable_layer_params'];
		$this->hist_timestamp = $rs['hist_timestamp'];
		$this->default_user_id = $rs['default_user_id'];
		$this->show_shared_layers = $rs['show_shared_layers'];
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
	static $language;
	static $hist_timestamp;
	static $layer_params;
	static $user_ID;
	static $stelle_ID;
	static $stelle_bezeichnung;
	static $export;
	var $minx;
	var $newtime;
	var $gui_object;
	var $layerset;

	function __construct($user_id, $stelle_id, $database) {
		global $debug;
		global $GUI;
		$this->gui_object = $GUI;
		$this->debug = $debug;
		$this->user_id = $user_id;
		$this->stelle_id = $stelle_id;
		$this->database = $database;
		rolle::$user_ID = $user_id;
		rolle::$stelle_ID = $stelle_id;
		rolle::$stelle_bezeichnung = $this->gui_object->Stelle->Bezeichnung;
		rolle::$export = 'false';
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
    $this->debug->write("<p>file:rolle.php class:rolle function:readSettings - Abfragen der Einstellungen der Rolle:<br>",4);
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
			rolle::$language=$rs['language'];
			$language = rolle::$language;
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
			$this->selectedButton = $rs['selectedButton'];
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
			$this->redline_text_color = $rs['redline_text_color'];
			$this->redline_font_family = $rs['redline_font_family'];
			$this->redline_font_size = $rs['redline_font_size'];
			$this->redline_font_weight = $rs['redline_font_weight'];
			return 1;
		}
		else {
			return 0;
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
  var $dbname;
  var $user;
  var $passwd;
  var $schema;
  var $pg_text_attribute_types;
  var $version;
  var $connection_id;
  var $error;

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
		$this->error = false;
		# Wenn dieser Parameter auf 1 gesetzt ist werden alle Anweisungen
		# START TRANSACTION, ROLLBACK und COMMIT unterdrückt, so daß alle anderen SQL
		# Anweisungen nicht in Transactionsblöcken ablaufen.
		# Kann zur Steigerung der Geschwindigkeit von großen Datenbeständen verwendet werden
		# Vorsicht: Wenn Fehler beim Einlesen passieren, ist der Datenbestand inkonsistent
		# und der Einlesevorgang muss wiederholt werden bis er fehlerfrei durchgelaufen ist.
		# Dazu Fehlerausschriften bearchten.
		$this->blocktransaction=0;
	}

  function open($connection_id = 0, $flag = NULL) {
		if ($connection_id == 0) {
			# get credentials from object variables
			#echo '<br>connection_id ist 0, hole von object credentials';
			$connection_string = $this->format_pg_connection_string($this->get_object_credentials());
		}
		else {
			#echo '<br>Öffne Datenbank mit connection_id: ' . $connection_id;
			$this->debug->write("Open Database connection with connection_id: " . $connection_id, 4);
			$this->connection_id = $connection_id;
			$connection_string = $this->get_connection_string();
		}
		$this->dbConn = pg_connect($connection_string, $flag);
		if (!$this->dbConn) {
			$this->err_msg = 'Die Verbindung zur PostGIS-Datenbank konnte mit folgenden Daten nicht hergestellt werden connection_id: ' . $connection_id . ' '
				. implode(' ' , array_filter(explode(' ', $connection_string), function($part) { return strpos($part, 'password') === false; }));
			return false;
		}
		else {
			$this->debug->write("Database connection successfully opend.", 4);
			$this->setClientEncodingAndDateStyle();
			$this->connection_id = $connection_id;
			return true;
		}
	}

	function get_connection_string($bash_escaping = false) {
		$connection_string = $this->format_pg_connection_string($this->get_credentials($this->connection_id));
		if ($bash_escaping) {
			$connection_string = str_replace('$', '\$', $connection_string);
		}
		return $connection_string;
	}

	function get_credentials($connection_id) {
		#echo '<p>get_credentials with connection_id: ' . $connection_id;
		include_once(CLASSPATH . 'Connection.php');
		$conn = Connection::find_by_id($this->gui, $connection_id);
		$this->host = $conn->get('host');
		return array(
			'host' => 		($conn->get('host')     != '' ? $conn->get('host')     : 'pgsql'),
			'port' => 		($conn->get('port')     != '' ? $conn->get('port')     : '5432'),
			'dbname' => 	($conn->get('dbname')   != '' ? $conn->get('dbname')   : 'kvwmapsp'),
			'user' => 		($conn->get('user')     != '' ? $conn->get('user')     : 'kvwmap'),
			'password' => ($conn->get('password') != '' ? $conn->get('password') : KVWMAP_INIT_PASSWORD)
		);
	}

	function format_pg_connection_string($credentials) {
		$connection_string = "" .
			"host='" .		 $credentials['host'] 		. "' " .
			"port='" .		 $credentials['port'] 		. "' " .
			"dbname='" .	 $credentials['dbname'] 	. "' " .
			"user='" .		 $credentials['user'] 		. "' " .
			"password='" . addslashes($credentials['password']) . "' " .
			"application_name=kvwmap_user_" . $this->gui->user->id;
		return $connection_string;
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

	function execSQL($sql, $debuglevel = 4, $loglevel = 1, $suppress_err_msg = false, $prepared_params = array()) {
		$ret = array(); // Array with results to return
		$ret['msg'] = '';
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
		if (DBWRITE OR (!stristr($sql, 'INSERT') AND !stristr($sql, 'UPDATE') AND !stristr($sql, 'DELETE'))) {
			#echo "<br>SQL in execSQL: " . $sql;
			if ($this->schema != '') {
				$sql = "SET search_path = " . $this->schema . ", public;" . $sql;
			}
			if (count($prepared_params) > 0) {
				$query_id = 'query_' . rand();
				$query = pg_prepare($this->dbConn, $query_id, $sql);
				$query = pg_execute($this->dbConn, $query_id, $prepared_params);
			}
			else {
				#echo "<br>SQL in execSQL: " . $sql;
				$query = @pg_query($this->dbConn, $sql);
			}
			//$query=0;
			if ($query === false) {
				$this->error = true;
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
				if (PHPVERSION >= 710) {
					$last_notices = pg_last_notice($this->dbConn, PGSQL_NOTICE_ALL);
				}
				else {
					$last_notices = array(pg_last_notice($this->dbConn));
				}
				foreach ($last_notices as $last_notice) {
					if ($strip_context AND strpos($last_notice, 'CONTEXT: ') !== false) {
						$last_notice = substr($last_notice, 0, strpos($last_notice, 'CONTEXT: '));
					}
					# Verarbeite Notice nur, wenn sie nicht schon mal vorher ausgewertet wurde
					if ($last_notice != '' AND ($this->gui->notices == NULL OR !in_array($last_notice, $this->gui->notices))) {
						$this->gui->notices[] = $last_notice;
						if (strpos($last_notice, '{') !== false AND strpos($last_notice, '}') !== false) {
							# Parse als JSON String
							$notice_obj = json_decode(substr($last_notice, strpos($last_notice, '{'), strpos($last_notice, '}') - strpos($last_notice, '{') + 1), true);
							if ($notice_obj AND array_key_exists('success', $notice_obj)) {
								if (!$notice_obj['success']) {
									$ret['success'] = false;
								}
								if (array_key_exists('msg_type', $notice_obj)) {
									$ret['type'] = $notice_obj['msg_type'];
								}
								if (array_key_exists('msg', $notice_obj) AND $notice_obj['msg'] != '') {
									$ret['msg'] .= $notice_obj['msg'];
								}
							}
						}
						else {
							# Gebe Noticetext wie er ist zurück
							$ret['msg'] .= $last_notice.chr(10).chr(10);
						}
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
				# mache nichts, denn die Fehlermeldung wird unterdrückt
			}
			else {
				# gebe Fehlermeldung aus.
				$ret[1] = $ret['msg'] = sql_err_msg('Fehler bei der Abfrage der PostgreSQL-Datenbank:', $sql, $ret['msg'], 'error_div_' . rand(1, 99999));
				$this->gui->add_message($ret['type'], $ret['msg']);
				#header('error: true');	// damit ajax-Requests das auch mitkriegen
			}
		}
		return $ret;
	}

	function read_epsg_codes($order = true){
		global $supportedSRIDs;
		# Wenn zu unterstützende SRIDs angegeben sind, ist die Abfrage diesbezüglich eingeschränkt
		$anzSupportedSRIDs = count($supportedSRIDs);
		$sql = "
			SELECT
				spatial_ref_sys.srid, coalesce(alias, substr(srtext, 9, 35)) as srtext,
				proj4text,
				minx, miny, maxx, maxy
			FROM
				spatial_ref_sys LEFT JOIN
				spatial_ref_sys_alias ON spatial_ref_sys_alias.srid = spatial_ref_sys.srid
			WHERE
				" . ($anzSupportedSRIDs > 0 ? "spatial_ref_sys.srid IN (" . implode(', ', $supportedSRIDs) . ")" : "true") . "
			ORDER BY
				" . ($order ? $order : "srtext") . "
		";
		#echo 'SQL zum Abfragen der EPSG-Codes: ' . $sql;
		$ret = $this->execSQL($sql, 4, 0);
		if ($ret[0] == 0) {
			$i = 0;
			while ($row = pg_fetch_assoc($ret[1])){
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
  var $disabled_classes;

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
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_ReferenceMap - Lesen der Referenzkartendaten:<br>",4);
		$this->db->execSQL($sql);
		if (!$this->db->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . "<br>" . $this->db->mysqli->error, 4); return 0; }
		$rs = $this->db->result->fetch_assoc();
    $this->referenceMap = $rs;
#		echo '<br>sql: ' . print_r($sql, true);
#		echo '<br>ref: ' . print_r($this->referenceMap, true);
    return $rs;
  }

	function read_Groups($all = false, $order = '', $where = 'true') {
		global $language;
		if ($language != 'german') {
			$gruppenname_column = "
			CASE
				WHEN g.`Gruppenname_" . $language . "` != \"\" THEN g.`Gruppenname_" . $language . "`
				ELSE g.`Gruppenname`
			END";
		}
		else {
			$gruppenname_column = "g.`Gruppenname`";
		}

		$sql = "
			SELECT
				g.id,
				" . $gruppenname_column . " AS Gruppenname,
				g.obergruppe,
				g.selectable_for_shared_layers " .
				(!$all ? ", g2r.status" : "") . "
			FROM
				u_groups AS g" . ($all ? "" : "
				JOIN u_groups2rolle AS g2r ON g.id = g2r.id") . "
			WHERE
				" . $where . ($all ? "" : " AND
				g2r.stelle_ID = " . $this->Stelle_ID . " AND
				g2r.user_id = " . $this->User_ID) . "
			ORDER BY " .
				($order != '' ? replace_semicolon($order) : "g.`order`") . "
		";
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Groups - Lesen der Gruppen der Rolle:<br>", 4);
		$this->db->execSQL($sql);
		if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		$groups = array();
		while ($rs = $this->db->result->fetch_assoc()) {
			$groups[$rs['id']]['status'] = value_of($rs, 'status');
			$groups[$rs['id']]['Gruppenname'] = $rs['Gruppenname'];
			$groups[$rs['id']]['obergruppe'] = $rs['obergruppe'];
			$groups[$rs['id']]['id'] = $rs['id'];
			$groups[$rs['id']]['selectable_for_shared_layers'] = $rs['selectable_for_shared_layers'];
			if ($rs['obergruppe']) {
				$groups[$rs['obergruppe']]['untergruppen'][] = $rs['id'];
			}
		}
		$this->anzGroups = count($groups);
		return $groups;
	}

	function read_Layer($withClasses, $useLayerAliases = false, $groups = NULL) {
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
				COALESCE(rl.drawingorder, l.drawingorder) as drawingorder,
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
				l.wms_format, l.wms_auth_username, l.wms_auth_password, l.wms_connectiontimeout, l.selectiontype, l.logconsume, l.metalink, l.terms_of_use_link, l.status, l.trigger_function,
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
				$rs['alias'],
				rolle::$layer_params,
				$rs['Layer_ID']
			);
			foreach (array('Name', 'alias', 'Name_or_alias', 'connection', 'classification', 'classitem', 'tileindex', 'pfad', 'Data') AS $key) {
				$rs[$key] = replace_params_rolle(
					$rs[$key],
					['duplicate_criterion' => $rs['duplicate_criterion']]
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
		#return $classarray ?? NULL;
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
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Class - Lesen der Classen eines Layers:<br>", 4);
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
		$Styles = array();
    $sql ='SELECT * FROM styles AS s,u_styles2classes AS s2c';
    $sql.=' WHERE s.Style_ID=s2c.style_id AND s2c.class_id='.$Class_ID;
    $sql.=' ORDER BY drawingorder';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Styles - Lesen der Styledaten:<br>",4);
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
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Label - Lesen der Labels zur Classe eines Layers:<br>",4);
		$this->db->execSQL($sql);
		if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
		while ($rs = $this->db->result->fetch_assoc()) {
			$Labels[]=$rs;
		}
		return $Labels;
	}

	function read_RollenLayer($id = NULL, $typ = NULL) {
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
				l.wms_auth_username,
				l.wms_auth_password,
				CASE
					WHEN connectiontype = 6 THEN concat('host=', c.host, ' port=', c.port, ' dbname=', c.dbname, ' user=', c.user, ' password=', c.password, ' application_name=kvwmap_user_', l.user_id)
					ELSE l.connection
				END as connection,
				l.`epsg_code`,
				l.`transparency`,
				l.`buffer`,
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
				($typ != NULL ? " AND l.Typ = '" . $typ . "'" : '') . 
				($this->nurLayerID ? " AND l.id = " . -($this->nurLayerID) : '') . 
				($this->nichtLayerID ? " AND l.id != " . -($this->nichtLayerID) : '') . "
		";
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_RollenLayer - Lesen der RollenLayer:<br>",4);
		# echo '<p>SQL zur Abfrage der Rollenlayer: ' . $sql;
		$ret = $this->db->execSQL($sql);
		if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		$Layer = array();
		while ($rs = $ret['result']->fetch_array()) {
			$rs['Class'] = $this->read_Classes(-$rs['id'], $this->disabled_classes);
			foreach (array('Name', 'alias', 'connection', 'classification', 'pfad', 'Data') AS $key) {
				$rs[$key] = replace_params_rolle(
					$rs[$key],
					['duplicate_criterion' => $rs['duplicate_criterion']]
				);
			}
			$rs['alias_link'] = $rs['alias'];
			$Layer[] = $rs;
		}
		return $Layer;
	}
}
?>
