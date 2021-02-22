<?

function sql_err_msg($title, $sql, $msg, $div_id) {
	$err_msg = "
		<div style=\"text-align: left;\">" .
		$title . "<br>" .
		$msg . "
		<div style=\"text-align: center\">
			<a href=\"#\" onclick=\"debug_t = this; $('#error_details_" . $div_id . "').toggle(); $(this).children().toggleClass('fa-caret-down fa-caret-up')\"><i class=\"fa fa-caret-down\" aria-hidden=\"true\"></i></a>
		</div>
		<div id=\"error_details_" . $div_id . "\" style=\"display: none\">
			Aufgetreten bei SQL-Anweisung:<br>
			<textarea id=\"sql_statement_" . $div_id . "\" class=\"sql-statement\" type=\"text\" style=\"height: " . round(strlen($sql) / 2) . "px; max-height: 600px\">
				" . $sql . "
			</textarea><br>
			<button type=\"button\" onclick=\"
				copyText = document.getElementById('sql_statement_" . $div_id . "');
				copyText.select();
				document.execCommand('copy');
			\">In Zwischenablage kopieren</button>
		</div>
	</div>";
	return $err_msg;
}

function value_of($array, $key) {
	return (array_key_exists($key, $array) ? $array[$key] :	'');
}

function InchesPerUnit($unit, $center_y){
	if($unit == MS_METERS){
		return 39.3701;
	}
	elseif($unit == MS_DD){
		if($center_y != 0.0){
			$cos_lat = cos(pi() * $center_y/180.0);
			$lat_adj = sqrt(1 + $cos_lat * $cos_lat)/sqrt(2);
		}
		return 4374754 * $lat_adj;
	}
}

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

function replace_params($str, $params, $user_id = NULL, $stelle_id = NULL, $hist_timestamp = NULL, $language = NULL, $duplicate_criterion = NULL) {
	if (is_array($params)) {
		foreach($params AS $key => $value){
			$str = str_replace('$'.$key, $value, $str);
		}
	}
	if (!is_null($user_id))							$str = str_replace('$user_id', $user_id, $str);
	if (!is_null($stelle_id))						$str = str_replace('$stelle_id', $stelle_id, $str);
	if (!is_null($hist_timestamp))			$str = str_replace('$hist_timestamp', $hist_timestamp, $str);
	if (!is_null($language))						$str = str_replace('$language', $language, $str);
	if (!is_null($duplicate_criterion))	$str = str_replace('$duplicate_criterion', $duplicate_criterion, $str);
	return $str;
}

function umlaute_umwandeln($name){
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
  $name = str_replace('/', '-', $name);
  $name = str_replace(' ', '', $name);
  $name = str_replace('-', '_', $name);
  $name = str_replace('?', '_', $name);
	$name = str_replace('+', '_', $name);
	$name = str_replace(',', '_', $name);
	$name = str_replace('*', '_', $name);
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
	var $mapDB;
	var $img;
	var $FormObject;
	var $StellenForm;
	var $Fehlermeldung;
	var $messages = array();
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
	var $layers_replace_scale = array();

  function __construct($main, $style, $mime_type) {
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
	
	function resizeMap2Window() {
		global $sizes;

		$size = $sizes[$this->user->rolle->gui];

		if (array_key_exists('legenddisplay', $this->formvars) AND $this->formvars['legenddisplay'] !== NULL) {
			$hideLegend = $this->formvars['legenddisplay'];		// falls die Legende gerade ein/ausgeblendet wurde
		}
		else {
			$hideLegend = $this->user->rolle->hideLegend;
		}

		$width = $this->formvars['browserwidth'] -
			$size['margin']['width'] -
			($this->user->rolle->hideMenue  == 1 ? $size['menue']['hide_width'] : $size['menue']['width']) -
			($hideLegend == 1 ? $size['legend']['hide_width'] : $size['legend']['width'])
			- 18;	# Breite für möglichen Scrollbalken

		$height = $this->formvars['browserheight'] -
			$size['margin']['height'] -
			$size['header']['height'] -
			$size['scale_bar']['height'] -
			((defined('LAGEBEZEICHNUNGSART') AND LAGEBEZEICHNUNGSART != '') ? $size['lagebezeichnung_bar']['height'] : 0) -
			($this->user->rolle->showmapfunctions == 1 ? $size['map_functions_bar']['height'] : 0) -
			$size['footer']['height'];

		if($width  < 0) $width = 10;
		if($height < 0) $height = 10;
		if($height % 2 != 0)$height = $height - 1;		# muss gerade sein, sonst verspringt die Karte beim Panen immer um 1 Pixel
		if($width  % 2 != 0)$width = $width - 1;				# muss gerade sein, sonst verspringt die Karte beim Panen immer um 1 Pixel

		$this->user->rolle->setSize($width.'x'.$height);
		$this->user->rolle->readSettings();
	}	
	
	function zoomToMaxLayerExtent($layer_id) {
    # Abfragen der maximalen Ausdehnung aller Daten eines Layers
		if($layer_id > 0){
			$layer = $this->user->rolle->getLayer($layer_id);
		}
		else{
			$layer = $this->user->rolle->getRollenLayer(-$layer_id);
		}
		switch ($layer[0]['Datentyp']) {
			case MS_LAYER_POLYGON : case MS_LAYER_LINE : case MS_LAYER_POINT : {
				# Abfragen der Datenbankverbindung des Layers
				$layerdb=$this->mapDB->getlayerdatabase($layer_id, $this->Stelle->pgdbhost);
				$data = $layer[0]['Data'];
				if ($data != ''){
					# ersetzen von $scale
					$data = str_replace('$scale', 1000, $data);
					# suchen nach dem ersten Vorkommen von using
					$pos = strpos(strtolower($data),'using ');
					# Abschneiden der uing Wörter im Datastatement wenn unique verwendet wurde
					if ($pos !== false) {
						$subquery=substr($data,0,$pos);
					}
					else {
						# using kommt nicht vor, es handelt sich um ein einfaches Data Statement in der Form
						# the_geom from tabelle, übernehmen wie es ist.
						$subquery = $data;
					}
					$explosion = explode(' ', $data);
					$this->attributes['the_geom'] = $explosion[0];
				}
				else{
					$subquery = substr($layer[0]['pfad'], 7);
					$this->attributes = $this->mapDB->read_layer_attributes($layer_id, $layerdb, NULL);
				}

				# Filter berücksichtigen
				$filter = $this->mapDB->getFilter($layer_id, $this->Stelle->id);
				if($filter != ''){
					$filter = str_replace('$userid', $this->user->id, $filter);
					$subquery .= ' WHERE '.$filter;
				}

				# Erzeugen des Abfragestatements für den maximalen Extent aus dem Data String
				$sql ='SELECT st_xmin(extent) AS minx,st_ymin(extent) AS miny,st_xmax(extent) AS maxx,st_ymax(extent) AS maxy FROM (SELECT st_transform(st_setsrid(st_extent('.$this->attributes['the_geom'].'), '.$layer[0]['epsg_code'].'), '.$this->user->rolle->epsg_code.') AS extent FROM (SELECT ';
				$sql.=$subquery;
				$sql.=') AS fooForMaxLayerExtent) as foo';
				#echo $sql;

				# Abfragen der Layerausdehnung
				$ret=$layerdb->execSQL($sql,4,0);
				if ($ret[0]) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
				$rs = pg_fetch_array($ret[1]);
			}break;
			
			case MS_LAYER_RASTER : {
				if($layer[0]['Data'] != ''){				# eine einzelne Rasterdatei
					$raster_file = SHAPEPATH.$layer[0]['Data'];
					if(file_exists($raster_file)){
						$output = rand(0, 100000);
						$command = OGR_BINPATH.'gdalinfo '.$raster_file.' > '.IMAGEPATH.$output.'.info';
						exec($command);
						$infotext = file_get_contents(IMAGEPATH.$output.'.info');
						$ll = explode(', ', trim(get_first_word_after($infotext, 'Lower Left', '', ')'), ' ('));
						$ur = explode(', ', trim(get_first_word_after($infotext, 'Upper Right', '', ')'), ' ('));
					}
				}
				elseif($layer[0]['tileindex'] != ''){		# ein Tile-Index
					$shape_file = SHAPEPATH.$layer[0]['tileindex'];
					if(file_exists($shape_file)){
						$output = rand(0, 100000);
						$command = OGR_BINPATH.'ogrinfo -al -so '.$shape_file.' > '.IMAGEPATH.$output.'.info';
						exec($command);
						$infotext = file_get_contents(IMAGEPATH.$output.'.info');
						$extent = get_first_word_after($infotext, 'Extent:', ' ', chr(10));
						$corners = explode('-', $extent);
						$ll = explode(', ', trim($corners[0], '() '));
						$ur = explode(', ', trim($corners[1], '() '));
					}
				}
				$extent = new rectObj();
				$extent->setextent($ll[0],$ll[1],$ur[0],$ur[1]);
				$rasterProjection = ms_newprojectionobj("init=epsg:".$layer[0]['epsg_code']);
				$userProjection = ms_newprojectionobj("init=epsg:".$this->user->rolle->epsg_code);
				$extent->project($rasterProjection, $userProjection);
				$rs['minx'] = $extent->minx;
				$rs['maxx'] = $extent->maxx;
				$rs['miny'] = $extent->miny;
				$rs['maxy'] = $extent->maxy;
			}break;
		}
		if($rs['minx'] != ''){
			if($this->user->rolle->epsg_code == 4326)$rand = 10/10000;
			else $rand = 10;
			$minx=$rs['minx']-$rand;
			$maxx=$rs['maxx']+$rand;
			$miny=$rs['miny']-$rand;
			$maxy=$rs['maxy']+$rand;
			#echo 'box:'.$minx.' '.$miny.','.$maxx.' '.$maxy;
			$this->map->setextent($minx,$miny,$maxx,$maxy);
			# damit nicht außerhalb des Stellen-Extents oder des maximalen Layer-Maßstabs gezoomt wird
			$oPixelPos=ms_newPointObj();
			$oPixelPos->setXY($this->map->width/2,$this->map->height/2);
			if (MAPSERVERVERSION > 600) {
				if($layer[0]['maxscale'] > 0 AND $layer[0]['maxscale'] < $this->map->scaledenom)$nScale = $layer[0]['maxscale']-1;
				else $nScale = $this->map->scaledenom;
				$this->map->zoomscale($nScale,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				if($layer[0]['maxscale'] > 0 AND $layer[0]['maxscale'] < $this->map->scale)$nScale = $layer[0]['maxscale']-1;
				else $nScale = $this->map->scale;
				$this->map->zoomscale($nScale,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
				$this->map_scaledenom = $this->map->scale;
			}
		}
  }

	function loadlayer($map, $layerset){
		$layer = ms_newLayerObj($map);
		$layer->setMetaData('wfs_request_method', 'GET');
		$layer->setMetaData('wms_name', $layerset['wms_name']);
		if($layerset['wms_keywordlist'])$layer->setMetaData('ows_keywordlist', $layerset['wms_keywordlist']);
		$layer->setMetaData('wfs_typename', $layerset['wms_name']);
		$layer->setMetaData('ows_title', $layerset['Name']); # required
		$layer->setMetaData('wms_group_title',$layerset['Gruppenname']);
		$layer->setMetaData('wms_queryable',$layerset['queryable']);
		$layer->setMetaData('wms_format',$layerset['wms_format']);
		$layer->setMetaData('ows_server_version',$layerset['wms_server_version']);
		$layer->setMetaData('ows_version',$layerset['wms_server_version']);
		if($layerset['metalink']){
			$layer->setMetaData('ows_metadataurl_href',$layerset['metalink']);
			$layer->setMetaData('ows_metadataurl_type', 'ISO 19115');
			$layer->setMetaData('ows_metadataurl_format', 'text/plain');
		}
		if($layerset['ows_srs'] == '') $layerset['ows_srs'] = 'EPSG:' . $layerset['epsg_code'];
		$layer->setMetaData('ows_srs', $layerset['ows_srs']);
		$layer->setMetaData('wms_connectiontimeout',$layerset['wms_connectiontimeout']);
		$layer->setMetaData('ows_auth_username', $layerset['wms_auth_username']);
		$layer->setMetaData('ows_auth_password', $layerset['wms_auth_password']);
		$layer->setMetaData('ows_auth_type', 'basic');
		$layer->setMetaData('wms_exceptions_format', 'application/vnd.ogc.se_xml');
		# ToDo: das Setzen von ows_extent muss in dem System erfolgen, in dem der Layer definiert ist (erstmal rausgenommen)
		#$layer->setMetaData("ows_extent", $bb->minx . ' '. $bb->miny . ' ' . $bb->maxx . ' ' . $bb->maxy);		# führt beim WebAtlas-WMS zu einem Fehler
		$layer->setMetaData("gml_featureid", "ogc_fid");
		$layer->setMetaData("gml_include_items", "all");

		$layer->set('dump', 0);
		$layer->set('type',$layerset['Datentyp']);
		$layer->set('group',$layerset['Gruppenname']);

		$layer->set('name', $layerset['alias']);

		if($layerset['status'] != ''){
			$layerset['aktivStatus'] = 0;
		}

		//---- wenn die Layer einer eingeklappten Gruppe nicht in der Karte //
		//---- dargestellt werden sollen, muß hier bei aktivStatus != 1 //
		//---- der layer_status auf 0 gesetzt werden//
		if($layerset['aktivStatus'] == 0){
			$layer->set('status', 0);
		}
		else{
			$layer->set('status', 1);
		}

		# fremde Layer werden auf Verbindung getestet
		if (
			$layerset['aktivStatus'] != 0 AND
			$layerset['connectiontype'] == 6 AND
			strpos($layerset['connection'], 'host') !== false AND
			strpos($layerset['connection'], 'host=localhost') === false AND
			strpos($layerset['connection'], 'host=pgsql') === false
		) {
			$connection = explode(' ', trim($layerset['connection']));
			for ($j = 0; $j < count($connection); $j++) {
				if ($connection[$j] != '') {
					$value = explode('=', $connection[$j]);
					if (strtolower($value[0]) == 'host') {
						$host = $value[1];
					}
					if(strtolower($value[0]) == 'port') {
						$port = $value[1];
					}
				}
			}
			if ($port == '') $port = '5432';
			$fp = @fsockopen($host, $port, $errno, $errstr, 5);
			if(!$fp) {			# keine Verbindung --> Layer ausschalten
				$layer->set('status', 0);
				$layer->setMetaData('queryStatus', 0);
				$this->Fehlermeldung = $errstr.' für Layer: '.$layerset['Name'].'<br>';
			}
		}

		if($layerset['aktivStatus'] != 0){
			$collapsed = false;
			$group = $this->groupset[$layerset['Gruppe']];				# die Gruppe des Layers
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

		if(!$this->noMinMaxScaling AND $layerset['minscale']>=0) {
			if($this->map_factor != ''){
				$layer->set('minscaledenom', $layerset['minscale']/$this->map_factor*1.414);
			}
			else{
				$layer->set('minscaledenom', $layerset['minscale']);
			}
		}
		if(!$this->noMinMaxScaling AND $layerset['maxscale']>0) {
			if($this->map_factor != ''){
				$layer->set('maxscaledenom', $layerset['maxscale']/$this->map_factor*1.414);
			}
			else{
				$layer->set('maxscaledenom', $layerset['maxscale']);
			}
		}
		$layer->setProjection('+init=epsg:'.$layerset['epsg_code']); # recommended
		if ($layerset['connection']!='') {
			if($layerset['connectiontype'] == 7) {		# WMS-Layer
				$layerset['connection'] .= '&SERVICE=WMS';
				if ($this->map_factor != ''){
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
				$layerset['connection'] .= " options='-c client_encoding=".MYSQL_CHARSET."'";
			}
			$layer->set('connection', 
				replace_params(
					$layerset['connection'],
					rolle::$layer_params,
					$this->user->id,
					$this->Stelle->id,
					rolle::$hist_timestamp,
					$this->user->rolle->language
				)
			);
		}

		if ($layerset['connectiontype'] > 0) {
			$layer->setConnectionType($layerset['connectiontype']);			
		}

		if ($layerset['connectiontype'] == 6) {
			$layerset['processing'] = 'CLOSE_CONNECTION=DEFER;' . $layerset['processing'];		# DB-Connection erst am Ende schliessen und nicht für jeden Layer neu aufmachen
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

		if ($layerset['postlabelcache'] != 0) {
			$layer->set('postlabelcache',$layerset['postlabelcache']);
		}

		if($layerset['Datentyp'] == MS_LAYER_POINT AND $layerset['cluster_maxdistance'] != ''){
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

			# Setzen der Templatedateien für die Sachdatenanzeige inclt. Footer und Header.
			# Template (Body der Anzeige)
			if ($this->formvars['go'] == 'OWS') {
				$layer->set('template', 'dummy');
			}
			# Header (Kopfdatei)
			if ($layerset['header']!='') {
				$layer->set('header',$layerset['header']);
			}
			# Footer (Fusszeile)
			if ($layerset['footer']!='') {
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
			 if (substr($layerset['Filter'],0,1)=='(') {
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
			if ($layerset['labelmaxscale']!='') {
				$layer->set('labelmaxscaledenom',$layerset['labelmaxscale']);
			}
			if ($layerset['labelminscale']!='') {
				$layer->set('labelminscaledenom',$layerset['labelminscale']);
			}
			if ($layerset['labelrequires']!='') {
				$layer->set('labelrequires',$layerset['labelrequires']);
			}
			if ($layerset['tolerance']!='3') {
				$layer->set('tolerance',$layerset['tolerance']);
			}
			if ($layerset['toleranceunits']!='pixels') {
				$layer->set('toleranceunits',$layerset['toleranceunits']);
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
			if ($layerset['symbolscale']!='') {
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

	function saveLegendRoleParameters(){
		# Scrollposition der Legende wird gespeichert
  	$this->user->rolle->setScrollPosition($this->formvars['scrollposition']);
    # Änderungen in den Gruppen werden gesetzt
    $this->formvars = $this->user->rolle->setGroupStatus($this->formvars);
    # Ein- oder Ausblenden der Klassen
    #$this->user->rolle->setClassStatus($this->formvars);			# kann wahrscheinlich weg
    # Wenn ein Button im Kartenfenster gewählt wurde,
    # werden auch die Einstellungen aus der Legende übernommen
    $this->user->rolle->setAktivLayer($this->formvars,$this->Stelle->id,$this->user->id);
    $this->user->rolle->setQueryStatus($this->formvars);
	}

  function neuLaden() {
		$this->saveLegendRoleParameters();
		if(in_array($this->formvars['last_button'], array('zoomin', 'zoomout', 'recentre', 'pquery', 'touchquery', 'ppquery', 'polygonquery')))$this->user->rolle->setSelectedButton($this->formvars['last_button']);		// das ist für den Fall, dass ein Button schon angeklickt wurde, aber die Aktion nicht ausgeführt wurde
		if($this->formvars['delete_rollenlayer'] != ''){
			$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
			$mapDB->deleteRollenlayer(NULL, $this->formvars['delete_rollenlayer_type']);
		}
    # Karteninformationen lesen
    $this->loadMap('DataBase');
    # zwischenspeichern des vorherigen Maßstabs
    $oldscale=round($this->map_scaledenom);
		# zoomToMaxLayerExtent
		if($this->formvars['zoom_layer_id'] != '') {
			$this->zoomToMaxLayerExtent($this->formvars['zoom_layer_id']);
		}

		if ($oldscale!=$this->formvars['nScale'] AND $this->formvars['nScale'] != '') {
      # Zoom auf den in der Maßstabsauswahl ausgewählten Maßstab
      # wenn er sich von der vorherigen Maßstabszahl unterscheidet
      # (das heißt wenn eine andere Zahl eingegeben wurde)
      $this->scaleMap($this->formvars['nScale']);
			$this->user->rolle->saveSettings($this->map->extent);
			$this->user->rolle->readSettings();
    }
    # Zoom auf den in der Referenzkarte ausgewählten Ausschnitt
    if ($this->formvars['refmap_x'] > 0) {
      $this->zoomToRefExt();
    }
    else {
      # Wenn ein Navigationskommando ausgewählt/übergeben wurde
      # Zoom/Pan auf den in der Karte ausgewählten Ausschnitt
      if ($this->formvars['CMD']!='') {
        $this->navMap($this->formvars['CMD']);
      }
    }
  }
	
	function reduce_mapwidth($width_reduction, $height_reduction = 0){
		# Diese Funktion reduziert die aktuelle Kartenbildbreite um $width_reduction Pixel (und optional die Kartenbildhöhe um $height_reduction Pixel), damit das Kartenbild in Fachschalen nicht zu groß erscheint.
		# Diese reduzierte Breite wird aber nicht in der Datenbank gespeichert, sondern gilt nur solange man in der Fachschale bleibt.
		# Außerdem wird bei Bedarf der aktuelle Maßstab berechnet und zurückgeliefert (er wird berechnet, weil ein loadmap() ja noch nicht aufgerufen wurde).
		# Mit diesem Maßstab kann dann einmal beim ersten Aufruf der Fachschale von der Hauptkarte aus nach dem loadmap() der Extent wieder so angepasst werden, dass der ursprüngliche Maßstab erhalten bleibt.
		# Dieser verkleinerte Extent wird wiederum in der Datenbank gespeichert. In der Datenbank steht dann also weiterhin die ursprüngliche Kartenbildgröße und der (dazu eigentlich nicht passende) in der Breite verkleinerte Extent.
		# Damit der Extent aber nur dann angepasst wird, wenn es notwendig ist (nämlich wenn man von der Hauptkarte kommt), wird der Maßstab nur berechnet, wenn Kartenbildgröße und Extent zusammenpassen.
		# Am "Nichtzusammenpassen" von Kartenbildgröße und Extent wird also erkannt, dass der Extent schon einmal verkleinert wurde.
		$this->formvars['width_reduction'] = $width_reduction;
		$this->formvars['height_reduction'] = $height_reduction;
		$width = $this->user->rolle->nImageWidth;
		$height = $this->user->rolle->nImageHeight;
		$extentwidth = $this->user->rolle->oGeorefExt->maxx - $this->user->rolle->oGeorefExt->minx;
		$extentheight = $this->user->rolle->oGeorefExt->maxy - $this->user->rolle->oGeorefExt->miny;
		$ratio_image = round($width/$height, 2);
		$ratio_extent = round($extentwidth/$extentheight, 2);
		if($ratio_image == $ratio_extent){
			$center_y = ($this->user->rolle->oGeorefExt->maxy + $this->user->rolle->oGeorefExt->miny) / 2;
			if($this->user->rolle->epsg_code == 4326){$unit = MS_DD;} else {$unit = MS_METERS;}
			$md = ($width-1)/(96 * InchesPerUnit($unit, $center_y));
			$gd = $this->user->rolle->oGeorefExt->maxx - $this->user->rolle->oGeorefExt->minx;
			$scale = $gd/$md;
		}
		$width = $width - $width_reduction;
		$height = $height - $height_reduction;
		if($this->user->rolle->hideMenue == 1){$width = $width - 195;}
		if($this->user->rolle->hideLegend == 1){$width = $width - 254;}
		$this->user->rolle->nImageWidth = $width;
		$this->user->rolle->nImageHeight = $height;
		return $scale;
	}
	
	function loadMultiLingualText($language) {
    #echo 'In der Rolle eingestellte Sprache: '.$GUI->user->rolle->language;
    $this->Stelle->language=$language;
    $this->Stelle->getName();
    include(LAYOUTPATH.'languages/'.$this->user->rolle->language.'.php');
  }

  function loadMap($loadMapSource) {
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
					$layer->set(
						'connection',
						replace_params(
							$layerset[$i][connection],
							rolle::$layer_params,
							$this->user->id,
							$this->Stelle->id,
							rolle::$hist_timestamp,
							$this->user->rolle->language
						)
					);
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
					$map = new mapObj(DEFAULTMAPFILE, SHAPEPATH);
				}
        $mapDB = new db_mapObj($this->Stelle->id, $this->user->id);

        # Allgemeine Parameter
        $map->set('width',$this->user->rolle->nImageWidth);
        $map->set('height',$this->user->rolle->nImageHeight);
        $map->set('resolution',96);
        #$map->set('transparent', MS_OFF);
        #$map->set('interlace', MS_ON);
        $map->set('status', MS_ON);
        $map->set('name', MAPFILENAME);
        $map->imagecolor->setRGB(255,255,255);
        $map->maxsize = 4096;
        $map->setProjection('+init=epsg:'.$this->user->rolle->epsg_code,MS_TRUE);

				$bb=$this->Stelle->MaxGeorefExt;

				# setzen der Kartenausdehnung über die letzten Benutzereinstellungen
				if($this->user->rolle->oGeorefExt->minx==='') {
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
					else{
						$map->setextent($bb->minx, $bb->miny, $bb->maxx, $bb->maxy);
					}
				}

        # OWS Metadaten

        if($this->Stelle->ows_title != ''){
          $map->setMetaData("ows_title",$this->Stelle->ows_title);}
        else{
          $map->setMetaData("ows_title",OWS_TITLE);
        }
        if($this->Stelle->ows_abstract != ''){
          $map->setMetaData("ows_abstract", $this->Stelle->ows_abstract);}
        else{
          $map->setMetaData("ows_abstract", OWS_ABSTRACT);
        }
        if($this->Stelle->wms_accessconstraints != ''){
          $map->setMetaData("ows_accessconstraints",$this->Stelle->wms_accessconstraints);}
        else{
          $map->setMetaData("ows_accessconstraints",OWS_ACCESSCONSTRAINTS);
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

				$map->setMetaData("ows_encoding", 'UTF-8');
				$map->setMetaData("ows_keywordlist", OWS_KEYWORDLIST);
				$map->setMetaData("ows_contactvoicetelephone", OWS_CONTACTVOICETELEPHONE);
				$map->setMetaData("ows_contactfacsimiletelephone", OWS_CONTACTFACSIMILETELEPHONE);
				$map->setMetaData("ows_addresstype", 'postal');
				$map->setMetaData("ows_address", OWS_ADDRESS);
				$map->setMetaData("ows_city", OWS_CITY);
				$map->setMetaData("ows_stateorprovince", OWS_STATEORPROVINCE);
				$map->setMetaData("ows_postcode", OWS_POSTCODE);
				$map->setMetaData("ows_country", OWS_COUNTRY);
				$map->setMetaData("ows_contactinstructions", OWS_CONTACTINSTRUCTIONS);
				$map->setMetaData("ows_hoursofservice", OWS_HOURSOFSERVICE);
				$map->setMetaData("ows_role", OWS_ROLE);

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
				if (value_of($_REQUEST, 'onlineresource') != '') {
					$ows_onlineresource = $_REQUEST['onlineresource'];
				}
				else {
					$ows_onlineresource = OWS_SERVICE_ONLINERESOURCE . '&Stelle_ID=' . $this->Stelle->id .'&login_name=' . value_of($_REQUEST, 'login_name') . '&passwort=' .  value_of($_REQUEST, 'passwort');
				}
        $map->setMetaData("ows_onlineresource", $ows_onlineresource);
				$map->setMetaData("ows_service_onlineresource", $ows_onlineresource);

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
				if(MAPSERVERVERSION < 600){
					$reference_map = ms_newMapObj(DEFAULTMAPFILE);
				}
				else {
					$reference_map = new mapObj(DEFAULTMAPFILE);
				}
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
				$reference_map->web->set('imagepath', IMAGEPATH);
				$reference_map->setProjection('+init=epsg:'.$this->ref['epsg_code'], MS_FALSE);
				$reference_map->reference->extent->setextent(round($this->ref['xmin']),round($this->ref['ymin']),round($this->ref['xmax']),round($this->ref['ymax']));
				$reference_map->reference->set('image', $this->ref['refMapImg']);
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
				$map->scalebar->label->font = 'SourceSansPro';
				if (MAPSERVERVERSION < 700 ) {
					$map->scalebar->label->type = 'truetype';
				}
				$map->scalebar->label->size = 10.5;

        # Groups
        if(value_of($this->formvars, 'nurAufgeklappteLayer') == ''){
	        $this->groupset=$mapDB->read_Groups();
        }

        # Layer
				$mapDB->nurAktiveLayer = value_of($this->formvars, 'nurAktiveLayer');
        $mapDB->nurAufgeklappteLayer = value_of($this->formvars, 'nurAufgeklappteLayer');
        $mapDB->nurFremdeLayer = value_of($this->formvars, 'nurFremdeLayer');
        if ($this->class_load_level == '') {
          $this->class_load_level = 1;
        }
        $layerset = $mapDB->read_Layer($this->class_load_level, $this->Stelle->useLayerAliases, $this->list_subgroups(value_of($this->formvars, 'group')));     # class_load_level: 2 = für alle Layer die Klassen laden, 1 = nur für aktive Layer laden, 0 = keine Klassen laden
        $rollenlayer = $mapDB->read_RollenLayer();
        $layerset['list'] = array_merge($layerset['list'], $rollenlayer);
        $layerset['anzLayer'] = count($layerset['list']);
        unset($this->layer_ids_of_group);		# falls loadmap zweimal aufgerufen wird
        for($i=0; $i < $layerset['anzLayer']; $i++){
					$layerset['layers_of_group'][$layerset['list'][$i]['Gruppe']][] = $i;
					if($layerset['list'][$i]['legendorder'] != ''){
						$layerset['layer_group_has_legendorder'][$layerset['list'][$i]['Gruppe']] = true;
					}
					if($layerset['list'][$i]['requires'] == ''){
						$this->layer_ids_of_group[$layerset['list'][$i]['Gruppe']][] = $layerset['list'][$i]['Layer_ID'];				# die Layer-IDs in einer Gruppe
					}
					$this->layer_id_string .= $layerset['list'][$i]['Layer_ID'].'|';							# alle Layer-IDs hintereinander in einem String

					if($layerset['list'][$i]['requires'] != ''){
						$layerset['list'][$i]['aktivStatus'] = $layerset['layer_ids'][$layerset['list'][$i]['requires']]['aktivStatus'];
						$layerset['list'][$i]['showclasses'] = $layerset['layer_ids'][$layerset['list'][$i]['requires']]['showclasses'];
					}

					if($this->class_load_level == 2 OR ($this->class_load_level == 1 AND $layerset['list'][$i]['aktivStatus'] != 0)){      # nur wenn der Layer aktiv ist, sollen seine Parameter gesetzt werden
						$layerset['list'][$i]['layer_index_mapobject'] = $map->numlayers;
						$this->loadlayer($map, $layerset['list'][$i]);
          }
        }
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
				$imagename = WWWROOT . CUSTOM_PATH . 'graphics/' . $classset[$j]['legendgraphic'];
				$klasse->set('keyimage', $imagename);
			}
      for ($k=0; $k < @count($classset[$j]['Style']);$k++) {
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
            $style->updateFromString("STYLE PATTERN " . $dbStyle['pattern']." END");
            $style->linecap = 'butt';
          }
					if($dbStyle['gap'] != '') {
	          $style->set('gap', $dbStyle['gap']);
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
					if($this->map_factor != '') {
						if(is_numeric($dbStyle['width']))$style->set('width', $dbStyle['width']*$this->map_factor/1.414);
						else $style->updateFromString("STYLE WIDTH [" . $dbStyle['width']."] END");
					}
					else{
						if(is_numeric($dbStyle['width']))$style->set('width', $dbStyle['width']);
						else $style->updateFromString("STYLE WIDTH [" . $dbStyle['width']."] END");
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
				$label = new labelObj();
				if (MAPSERVERVERSION < 700 ) {
					$label->type = 'truetype';
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
				$label->set('repeatdistance',$dbLabel['repeatdistance']);
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
      } # ende Schleife für mehrere Label
    } # end of Schleife Class
  }

  function navMap($cmd) {
    switch ($cmd) {
      case "previous" : {
#        $this->user->rolle->setSelectedButton('previous');
        $this->setPrevMapExtent($this->user->rolle->last_time_id);
      } break;
      case "next" : {
#        $this->user->rolle->setSelectedButton('next');
        $this->setNextMapExtent($this->user->rolle->last_time_id);
      } break;
      case "zoomin" : {
        $this->user->rolle->setSelectedButton('zoomin');
        $this->zoomMap($this->user->rolle->nZoomFactor);
      } break;
			case "zoomin_wheel" : {
        $this->zoomMap($this->user->rolle->nZoomFactor);
      } break;
      case "zoomout" : {
        $this->user->rolle->setSelectedButton('zoomout');
        $this->zoomMap($this->user->rolle->nZoomFactor*-1);
      } break;
      case "recentre" : {
        $this->user->rolle->setSelectedButton('recentre');
        $this->zoomMap(1);
      } break;
      // case "jump_coords" : {
        // $this->user->rolle->setSelectedButton('recentre');
        // $this->zoomMap(1);
      // } break;
      case "pquery" : {
        $this->user->rolle->setSelectedButton('pquery');
        $this->queryMap();
      } break;
      case "touchquery" : {
        $this->user->rolle->setSelectedButton('touchquery');
        $this->queryMap();
      } break;
      case "ppquery" : {
        $this->user->rolle->setSelectedButton('ppquery');
        $this->queryMap();
      } break;
      case "polygonquery" : {
        $this->user->rolle->setSelectedButton('polygonquery');
        $this->queryMap();
      } break;
      case "Full_Extent" : {
        $this->user->rolle->setSelectedButton('zoomin');   # um anschliessend wieder neu zoomen zu koennen!
        $this->setFullExtent();
      } break;
      default : {
      }
    }
  	if (MAPSERVERVERSION > 600) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }
	
  function setPrevMapExtent($consumetime) {
    $currentextent = ms_newRectObj();
    $prevextent = ms_newRectObj();
    $currentextent->setextent($this->map->extent->minx, $this->map->extent->miny, $this->map->extent->maxx, $this->map->extent->maxy);
    $prevextent->setextent($this->map->extent->minx, $this->map->extent->miny, $this->map->extent->maxx, $this->map->extent->maxy);
    $ret = $this->user->rolle->getConsume($consumetime);
    $i = 0;
    while($i < 100 AND 
					round($currentextent->minx, 2) == round($prevextent->minx, 2) AND 
					round($currentextent->miny, 2) == round($prevextent->miny, 2) AND 
					round($currentextent->maxx, 2) == round($prevextent->maxx, 2) AND 
					round($currentextent->maxy, 2) == round($prevextent->maxy, 2)){
      # Setzen des next Wertes des vorherigen Kartenausschnittes
      $prevtime=$ret[1]['prev'];
      $this->user->rolle->newtime = $prevtime;
      if (!($prevtime=='' OR $prevtime=='2006-09-29 12:55:50')) {
        $ret=$this->user->rolle->updateNextConsumeTime($prevtime,$consumetime);
        if ($ret[0]) {
          $this->errmsg="Der Nachfolger für den letzten Kartenausschnitt konnte nicht eingetragen werden.<br>" . $ret[1];
        }
        else {
          # Abfragen der vorherigen Kartenausdehnung
          $ret=$this->user->rolle->getConsume($prevtime);
          if ($ret[0]) {
            $this->errmsg="Der letzte Kartenausschnitt konnte nicht abgefragt werden.<br>" . $ret[1];
          }
          else {
						$consumetime = $prevtime;
						$prevextent->setextent($ret[1]['minx'],$ret[1]['miny'],$ret[1]['maxx'],$ret[1]['maxy']);
          }
        }
      }
      $i++;
    }
    $this->user->rolle->set_last_time_id($prevtime);
    $this->map->setextent($ret[1]['minx'],$ret[1]['miny'],$ret[1]['maxx'],$ret[1]['maxy']);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }

  function setNextMapExtent($consumetime) {
    $currentextent = ms_newRectObj();
    $nextextent = ms_newRectObj();
    $currentextent->setextent($this->map->extent->minx, $this->map->extent->miny, $this->map->extent->maxx, $this->map->extent->maxy);
    $nextextent->setextent($this->map->extent->minx, $this->map->extent->miny, $this->map->extent->maxx, $this->map->extent->maxy);
		$nexttime = $consumetime;
    # Abfragen der nächsten Kartenausdehnung
    $ret = $this->user->rolle->getConsume($consumetime);
    $i = 0;
    while($i < 100 AND 
		(string)$currentextent->minx == (string)$nextextent->minx AND 
		(string)$currentextent->miny == (string)$nextextent->miny AND 
		(string)$currentextent->maxx == (string)$nextextent->maxx AND 
		(string)$currentextent->maxy == (string)$nextextent->maxy){
      $lasttime = $nexttime;
      $nexttime=$ret[1]['next'];
      if($nexttime == NULL){
        $nexttime = $lasttime;
        $i = 100;
      }
      $this->user->rolle->newtime = $nexttime;
      $ret=$this->user->rolle->getConsume($nexttime);
      if ($ret[0]) {
        $this->errmsg="Der nächste Kartenausschnitt konnte nicht abgefragt werden.<br>" . $ret[1];
      }
      else {
        $nextextent->setextent($ret[1]['minx'],$ret[1]['miny'],$ret[1]['maxx'],$ret[1]['maxy']);
        #echo '<br>gewechselt auf Einstellung von:'.$this->consumetime;
      }
      $i++;
    }
    $this->user->rolle->set_last_time_id($ret[1]['time_id']);
    $this->map->setextent($ret[1]['minx'],$ret[1]['miny'],$ret[1]['maxx'],$ret[1]['maxy']);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }	
	
	function setFullExtent() {
		$this->map->setextent($this->Stelle->MaxGeorefExt->minx,$this->Stelle->MaxGeorefExt->miny,$this->Stelle->MaxGeorefExt->maxx,$this->Stelle->MaxGeorefExt->maxy);
	}	

  function zoomMap($nZoomFactor){
		# Funktion zum Zoomen über die Navigationswerkzeuge; Koordinaten sind Bildkoordinaten
    $corners=explode(';',$this->formvars['INPUT_COORD']);
    # Auslesen der ersten übergebenen Koordinate
    $lo=explode(',',$corners[0]);
    $minx=$lo[0];
    $maxy=$lo[1];
    # Abfrage, ob eine oder zwei Koordinaten übergeben wurden
    if (count($corners)==1) {
      # es wurde nur ein Punkt übergeben zum zoomen
      #echo '<br>Zoom zum Punkt.';
      $zoom='point';
    }
    else {
      # es wurde ein Rechteck gesetzt zum zoomen
      #echo '<br>Zoom to Rechteck.';
      $ru=explode(',',$corners[1]);
      $miny=$ru[1];
      $maxx=$ru[0];
      if ($minx==$maxx AND $miny==$maxy) {
        # Das Rechteck hat die Kantenlänge 0 deshalb zoom auf Punkt
        $zoom='point';
      }
      else {
        # zoom auf Rechteck wegen Kantenlänge > 0
        $zoom='rectangle';
      }
    }
    if ($zoom=='point') {
      # Zoomen auf einen Punkt
      $this->debug->write('<br>Es wird auf einen Punkt gezoomt',4);
      # Erzeugen eines Punktobjektes
      $oPixelPos=ms_newPointObj();

			$oPixelPos->setXY($minx,$maxy);
			$this->map->zoompoint($nZoomFactor,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
    }
    else {
      # Zoomen auf ein Rechteck
      $this->debug->write('<br>Es wird auf ein Rechteck gezoomt',4);
			$oPixelExt=ms_newRectObj();
      if($minx != 'undefined' AND $miny != 'undefined' AND $maxx != 'undefined' AND $maxy != 'undefined'){
       	$oPixelExt->setextent($minx,$miny,$maxx,$maxy);
        $this->map->zoomrectangle($oPixelExt,$this->map->width,$this->map->height,$this->map->extent);
        # Nochmal Zoomen auf die Mitte mit Faktor 1, damit der Ausschnitt in den erlaubten Bereich
        # verschoben wird, falls er ausserhalb liegt, zoompoint berücksichtigt das, zoomrectangle nicht.
        $oPixelPos=ms_newPointObj();
        $oPixelPos->setXY($this->map->width/2,$this->map->height/2);
        $this->map->zoompoint(1,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
      }
    }
  }

  function saveMap($saveMapDestination) {
		if ($saveMapDestination=='') {
      $saveMapDestination=SAVEMAPFILE;
    }
    if ($saveMapDestination != '') {
      $this->map->save($saveMapDestination);
    }
    $this->user->rolle->saveSettings($this->map->extent);
    # 2006-02-16 pk
    $this->user->rolle->readSettings();
  }
	
	function layer_error_handling(){
		global $errors;
		for($i = 0; $i < 2; $i++){		// es wird nach den ersten beiden Fehlern abgebrochen, da die Fehlermeldungen bei mehrmaligem Aufruf immer mehr werden...
			$error_details .= $errors[$i].chr(10);
			if(strpos($errors[$i], 'named') !== false){
				$start = strpos($errors[$i], '\'')+1;
				$end = strpos($errors[$i], '\'', $start);
				$length = $end - $start;
				$error_layername = substr($errors[$i], $start, $length);
				$layer = $this->user->rolle->getLayer($error_layername);
				if($layer == NULL)$layer = $this->user->rolle->getRollenLayer($error_layername);
				$error_layer_id = $layer[0]['Layer_ID'];
			}
		}
		restore_error_handler();
		if($this->formvars['go'] != 'navMap_ajax'){
			include(LAYER_ERROR_PAGE);
		}
		else{
			header('error: true');	// damit ajax-Requests das auch mitkriegen
		}
	}

  function drawMap() {
		if($this->formvars['go'] != 'navMap_ajax')set_error_handler("MapserverErrorHandler");		// ist in allg_funktionen.php definiert
    if($this->main == 'map.php' AND MINSCALE != '' AND $this->map_factor == '' AND $this->map_scaledenom < MINSCALE){
      $this->scaleMap(MINSCALE);
			$this->saveMap('');
    }
		# Parameter $scale in Data ersetzen
		for($i = 0; $i < count($this->layers_replace_scale); $i++){
			$this->layers_replace_scale[$i]->set('data', str_replace('$scale', $this->map_scaledenom, $this->layers_replace_scale[$i]->data));
		}		
    $this->image_map = $this->map->draw() OR die($this->layer_error_handling());
		ob_start();
		$this->image_map->saveImage();
		$image = ob_get_clean();
    $this->img['hauptkarte'] = 'data:image/jpg;base64,'.base64_encode($image);

		if($this->formvars['go'] != 'navMap_ajax'){
			$this->legende = $this->create_dynamic_legend();
			$this->debug->write("Legende erzeugt",4);
		}
		else{
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
    $img_scalebar = $this->map->drawScaleBar();
		ob_start();
		$img_scalebar->saveImage();
		$image = ob_get_clean();
    $this->img['scalebar'] = 'data:image/jpg;base64,'.base64_encode($image);
		$this->calculatePixelSize();
		$this->drawReferenceMap();
  }

  function scaleMap($nScale) {
    $oPixelPos=ms_newPointObj();
    $oPixelPos->setXY($this->map->width/2,$this->map->height/2);
    $this->map->zoomscale($nScale,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
  	if (MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }

	function check_layer_visibility(&$layer){
		if($layer['status'] != '' OR ($this->map_scaledenom < $layer['minscale'] OR ($layer['maxscale'] > 0 AND $this->map_scaledenom > $layer['maxscale']))) {
			return false;
		}
		return true;
	}

  function switchScaleUnitIfNecessary() {
		if ($this->map_scaledenom > $this->scaleUnitSwitchScale) $this->map->scalebar->set('units', MS_KILOMETERS);
  }

	function map_saveWebImage($image,$format) {
		if(MAPSERVERVERSION >= 600 ) {
			return $image->saveWebImage();
		}
		else {
			return $image->saveWebImage($format, 1, 1, 0);
		}
	}

	function calculatePixelSize() {
    $this->pixwidth = ($this->map->extent->maxx - $this->map->extent->minx)/$this->map->width;
    $this->pixheight = ($this->map->extent->maxy - $this->map->extent->miny)/$this->map->height;
    if ($this->pixwidth>$this->pixheight) {
      $this->pixsize=$this->pixwidth;
    }
    else {
      $this->pixsize=$this->pixheight;
    }
	}

  function drawReferenceMap(){
    # Erstellen der Referenzkarte
    if($this->reference_map->reference->image != NULL){
			$this->reference_map->setextent($this->map->extent->minx,$this->map->extent->miny,$this->map->extent->maxx,$this->map->extent->maxy);
			if($this->ref['epsg_code'] != $this->user->rolle->epsg_code){
				if(MAPSERVERVERSION < '600'){
					$projFROM = ms_newprojectionobj("init=epsg:".$this->user->rolle->epsg_code);
					$projTO = ms_newprojectionobj("init=epsg:".$this->ref['epsg_code']);
				}
				else{
					$projFROM = $this->map->projection;
					$projTO = $this->reference_map->projection;
				}
				$this->reference_map->extent->project($projFROM, $projTO);
			}
      $img_refmap = $this->reference_map->drawReferenceMap();
      ob_start();
			$img_refmap->saveImage();
			$image = ob_get_clean();
      $this->img['referenzkarte'] = 'data:image/jpg;base64,'.base64_encode($image);
      $this->Lagebezeichung=$this->getLagebezeichnung($this->user->rolle->epsg_code);
    }
	}

  function getLagebezeichnung($epsgcode) {
    switch (LAGEBEZEICHNUNGSART) {
      case 'Flurbezeichnung' : {
        $Lagebezeichnung = $this->getFlurbezeichnung($epsgcode);
			} break;
			default : {
			  $Lagebezeichnung = '';
			}
	  }
    return $Lagebezeichnung;
  }

  function getFlurbezeichnung($epsgcode) {
    $Flurbezeichnung = '';
 	  $flur = new Flur('','','',$this->pgdatabase);
		$bildmitte['rw']=($this->map->extent->maxx+$this->map->extent->minx)/2;
		$bildmitte['hw']=($this->map->extent->maxy+$this->map->extent->miny)/2;
		$ret=$flur->getBezeichnungFromPosition($bildmitte, $epsgcode);
		if ($ret[0]) {
		}
		else {
			if ($ret[1]['flur'] != '') {
				$Flurbezeichnung = $ret[1];
			}
		}
		return $Flurbezeichnung;
  }
	# extrahiert die Daten aus qlayerset in ein Array
	function qlayersetParamStrip() {
		$result = array();
		# Nehme ersten layerset
		$layerset = $this->qlayerset[0]['shape'];
    if (is_array($layerset)) {
  		# Durchlaufe alle gefundenen Datensätze im Layerset
  		foreach ($layerset AS $record) {
  			$data = array();
  			if ($this->formvars['selectors'] != '') {
  				$selectors = explode(',', $this->formvars['selectors']);
  				foreach ($selectors AS $selector) {
  					$selector = trim($selector);
  					$data[$selector] = $record[strtolower($selector)];
  				}
  			}
  			else {
  				$data = $record;
  			}
  			$result[] = $data;
  		}
    }
#		if (count($result) == 1) $result = $result[0];
		return $result;
	}

	/*
	* This function returns the file that sould be included as gui file in output
	* The gui will be retrieved from $this->gui if exists or
	* otherwise from $this->user->rolle->gui
	* The value of $this->user->rolle->gui can have 2 cases
	* if value containing basename(CUSTOM_PATH) . '/' e.g. custom/ at the beginning than replace it BY CUSTOM_PATH
	* else prepend WWWROOT . APPLVERSION
	*/
	function get_guifile() {
		if ($this->gui != '') {
			return $this->gui;
		}

		if (strpos($this->user->rolle->gui, basename(CUSTOM_PATH) . '/') === 0) {
			return str_replace(basename(CUSTOM_PATH) . '/', CUSTOM_PATH, $this->user->rolle->gui);
		}
		else {
			return WWWROOT . APPLVERSION . $this->user->rolle->gui;
		}
	}

	function add_message($type, $msg) {
		if (is_array($msg) AND array_key_exists('success', $msg) AND is_array($msg)) {
			$type = 'notice';
			$msg = $msg['msg'];
		}
		if ($type == 'array' or is_array($msg)) {
			foreach($msg AS $m) {
				$this->add_message($m['type'], $m['msg']);
			}
		}
		else {
			$this->messages[] = array(
				'type' => $type,
				'msg' => $msg
			);
		}
	}

	function output_messages($option = 'with_script_tags') {
		$html = "message(" . json_encode($this->messages) . ");";
		if ($option == 'with_script_tags') {
			$html = "<script type=\"text/javascript\">" . $html . "</script>";
		}
		echo $html;
	}

  function output() {
		global $sizes;
    # bisher gibt es folgenden verschiedenen Dokumente die angezeigt werden können
		if ($this->formvars['mime_type'] != '') $this->mime_type = $this->formvars['mime_type'];
    switch ($this->mime_type) {
      case 'printversion' : {
        include (LAYOUTPATH.'snippets/printversion.php');
      } break;
      case 'html' : {
				$guifile = $this->get_guifile();
				$this->debug->write("<br>Include <b>" . $guifile . "</b> in kvwmap.php function output()",4);
				include($guifile);

				if ($this->alert != '') {
					echo '<script type="text/javascript">alert("'.$this->alert.'");</script>';			# manchmal machen alert-Ausgaben über die allgemeinde Funktioen showAlert Probleme, deswegen am besten erst hier am Ende ausgeben
				}
				if (!empty($this->messages)) {
					$this->output_messages();
				}
      } break;
			case 'overlay_html' : {
				$this->overlaymain = $this->main;
				include (LAYOUTPATH.'snippets/overlay.php');
				if($this->alert != ''){
					echo '<script type="text/javascript">alert("'.$this->alert.'");</script>';			# manchmal machen alert-Ausgaben über die allgemeinde Funktioen showAlert Probleme, deswegen am besten erst hier am Ende ausgeben
				}
				if (!empty($this->messages)) {
					$this->output_messages();
				}
			} break;
      case 'map_ajax' : {
				$this->debug->write("Include <b>".LAYOUTPATH."snippets/map_ajax.php</b> in kvwmap.php function output()",4);
        include (LAYOUTPATH.'snippets/map_ajax.php');
      } break;
      case 'pdf' : {
        $this->formvars['file']=1;
        if ($this->formvars['file']) {
          $htmlstr.='<html><head><title>PDF-Ausgabe</title>';
          $htmlstr.='<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
          $htmlstr.='<META HTTP-EQUIV=REFRESH CONTENT="0; URL='.TEMPPATH_REL.$this->outputfile.'">';
          $htmlstr.='</head><body>';
          $htmlstr.='<BR>Folgende Datei wird automatisch aufgerufen: <a href="'.TEMPPATH_REL.$this->outputfile.'">'.$this->outputfile.'</a>';
          $htmlstr.='</body></html>';
          echo $htmlstr;
        }
        else {
          $this->pdf->ezStream();
        }
      } break;
			default : {
				if ($this->formvars['format'] != '') {
					include('formatter.php');
					$this->formatter = new formatter($this->qlayersetParamStrip(), $this->formvars['format'], $this->formvars['content_type'], $this->formvars['callback']);
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

  function __construct() {
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
		$this->debug->write("<br>MySQL Verbindung öffnen mit Host: " . $this->host . " User: " . $this->user . " Datenbbank: " . $this->dbName, 4);
		$this->mysqli = new mysqli($this->host, $this->user, $this->passwd, $this->dbName);
	  $this->debug->write("<br>MySQL VerbindungsID: " . $this->mysqli->thread_id, 4);
		return $this->mysqli->connect_errno;
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

	function __construct($login_name,$id,$database) {
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

	function readUserDaten($id, $login_name, $passwort = '') {
		$where = array();
		if ($id > 0) array_push($where, "ID = " . $id);
		if ($login_name != '') array_push($where, "login_name LIKE '" . $login_name . "'");
		if ($passwort != '') array_push($where, "passwort = md5('" . $this->database->mysqli->real_escape_string($passwort) . "')");
		$sql = "
			SELECT
				*
			FROM
				user
			WHERE
				" . implode(" AND ", $where) . "
		";
		#echo '<br>Sql: ' . $sql;

		$this->debug->write("<p>file:users.php class:user->readUserDaten - Abfragen des Namens des Benutzers:<br>", 3);
		$this->database->execSQL($sql);
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
	}

	function getLastStelle() {
		$sql = "
			SELECT
				stelle_id
			FROM
				user
			WHERE
				ID= " . $this->id ."
		";
		$this->debug->write("<p>file:users.php class:user->getLastStelle - Abfragen der zuletzt genutzten Stelle:<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>' . $this->database->mysqli->error, 4); return 0; }
		$rs = $this->database->result->fetch_array();
		return $rs['stelle_id'];
	}

	function StellenZugriff($stelle_id) {
		$this->Stellen=$this->getStellen($stelle_id);
		if (count($this->Stellen['ID'])>0) {
			return 1;
		}
		return 0;
	}

	function getStellen($order, $user_id = 0) {
		global $admin_stellen;

		if ($order != '') {
			$order = " ORDER BY `" . $order . "`";
		}

		if ($user_id > 0 AND !in_array($this->id, $admin_stellen)) {
			$where = "
				LEFT JOIN `rolle` AS r ON s.ID = r.stelle_id
				WHERE r.user_id = ".$user_id." OR r.stelle_id IS NULL
			";
		}

		$sql = "
			SELECT
				s.ID,
				s.Bezeichnung
			FROM
				`stelle` AS s" .
				$where .
				$order . "
		";
		#echo '<br>sql: ' . $sql;

		$this->debug->write("<p>file:stelle.php class:stelle->getStellen - Abfragen aller Stellen<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		while($rs = $this->database->result->fetch_array()) {
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
	var $language;

	function __construct($id, $database) {
		global $debug;
		$this->debug=$debug;
		$this->id=$id;
		$this->database=$database;
		$this->Bezeichnung=$this->getName();
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
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
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
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		$rs = $this->database->result->fetch_array();
		$this->MaxGeorefExt = ms_newRectObj();
		$this->MaxGeorefExt->setextent($rs['minxmax'], $rs['minymax'], $rs['maxxmax'], $rs['maxymax']);
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

  function checkClientIpIsOn() {
    $sql = "
			SELECT
				check_client_ip
			FROM
				stelle
			WHERE ID = " . $this->id . "
		";
    $this->debug->write("<p>file:stelle.php class:stelle->checkClientIpIsOn- Abfragen ob IP's der Nutzer in der Stelle getestet werden sollen<br>".$sql,4);
    #echo '<br>'.$sql;
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		$rs = $this->database->result->fetch_array();
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

	function __construct($user_id,$stelle_id,$database) {
		global $debug;
		$this->debug=$debug;
		$this->user_id=$user_id;
		$this->stelle_id=$stelle_id;
		$this->database=$database;
		#$this->layerset=$this->getLayer('');
		#$this->groupset=$this->getGroups('');
		$this->loglevel = 0;
	}
	
	function setSize($mapsize) {
		# setzen der Werte, die aktuell für die Nutzung der Stelle durch den Nutzer gelten sollen.
		$teil = explode('x',$mapsize);
		$nImageWidth = $teil[0];
		$nImageHeight = $teil[1];
		$sql = "
			UPDATE
				rolle
			SET
				nImageWidth = " . $nImageWidth . ",
				nImageHeight = " . $nImageHeight . "
			WHERE
				stelle_id = " . $this->stelle_id . "
				AND user_id = " . $this->user_id . "
		";
		$this->debug->write("<p>file:rolle.php class:rolle->setSize - Setzen der Einstellungen für die Bildgröße", 4);
		$this->database->execSQL($sql,4, $this->loglevel);
		$this->debug->write('Neue Werte für Rolle eingestellt: ' . $nImageWidth . ', ' . $nImageHeight, 4);
		return 1;
	}	
	
  function getConsume($consumetime, $user_id = NULL) {
		if($user_id == NULL)$user_id = $this->user_id;		# man kann auch eine user_id übergeben um den Kartenausschnitt eines anderen Users abzufragen
    $sql ='SELECT * FROM u_consume';
    $sql.=' WHERE user_id='.$user_id.' AND stelle_id='.$this->stelle_id;
    $sql.=' AND time_id="'.$consumetime.'"';
    #echo '<br>'.$sql;
    $queryret=$this->database->execSQL($sql,4, 0);
    if ($queryret[0]) {
      # Fehler bei Datenbankanfrage
      $ret[0]=1;
      $ret[1]='<br>Fehler bei der Abfrage der letzten Zugriffszeit.<br>'.$ret[1];
    }
    else {
      $rs = $this->database->result->fetch_assoc();
      $ret[0]=0;
      $ret[1]=$rs;
    }
    return $ret;
  }
	
  function updateNextConsumeTime($time_id,$nexttime) {
    $sql ='UPDATE u_consume SET next="'.$nexttime.'"';
    $sql.=' WHERE time_id="'.$time_id.'"';
    #echo '<br>'.$sql;
    $queryret=$this->database->execSQL($sql,4, 1);
    if ($queryret[0]) {
      # Fehler bei Datenbankanfrage
      $ret[0]=1;
      $ret[1]='<br>Fehler bei der Aktualisierung des Zeitstempels des Nachfolgers Next.<br>'.$ret[1];
    }
    else {
      $ret[0]=0;
      $ret[1]=1;
    }
    return $ret;
  }	

	function setScrollPosition($scrollposition){
		if($scrollposition != ''){
			$sql = 'UPDATE rolle SET scrollposition = '.$scrollposition;
			$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
			$this->debug->write("<p>file:rolle.php class:rolle->setOneLayer - Setzen eines Layers:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
		}
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
    $this->database->execSQL($sql);
    if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
    while ($rs = $this->database->result->fetch_assoc()) {
      $groups[]=$rs;
    }
    return $groups;
  }

	function setAktivLayer($formvars, $stelle_id, $user_id, $ignore_rollenlayer = false) {
		$this->layerset=$this->getLayer('');
		if(!$ignore_rollenlayer){
			$rollenlayer=$this->getRollenLayer('', NULL);
			$this->layerset = array_merge($this->layerset, $rollenlayer);
		}
		# Eintragen des Status der Layer, 1 angezeigt oder 0 nicht.
		for ($i=0;$i<count($this->layerset)-1;$i++) {
			#echo $i.' '.$this->layerset[$i]['Layer_ID'].' '.$formvars['thema'.$this->layerset[$i]['Layer_ID']].'<br>';
			$aktiv_status = value_of($formvars, 'thema'.value_of($this->layerset[$i], 'Layer_ID'));
			$requires_status = value_of($formvars, 'thema'.value_of($this->layerset[$i], 'requires'));
			if($aktiv_status != '' OR $requires_status != ''){										// entweder ist der Layer selber an oder sein requires-Layer
				$aktiv_status = (int)$aktiv_status + (int)$requires_status;
				if($this->layerset[$i]['Layer_ID'] > 0){
					$sql ='UPDATE u_rolle2used_layer SET aktivStatus="'.$aktiv_status.'"';
					$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
					$sql.=' AND layer_id='.$this->layerset[$i]['Layer_ID'];
					$this->debug->write("<p>file:rolle.php class:rolle->setAktivLayer - Speichern der aktiven Layer zur Rolle:",4);
					$this->database->execSQL($sql,4, $this->loglevel);
				}
				else{						# Rollenlayer
					$sql ='UPDATE rollenlayer SET aktivStatus="'.$aktiv_status.'"';
					$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
					$sql.=' AND id = '.abs($this->layerset[$i]['Layer_ID']);
					$this->debug->write("<p>file:rolle.php class:rolle->setAktivLayer - Speichern der aktiven Layer zur Rolle:",4);
					$this->database->execSQL($sql,4, $this->loglevel);
				}
				#neu eintragen der deaktiven Klassen
				if($aktiv_status != 0){
					$sql = 'SELECT Class_ID FROM classes WHERE Layer_ID='.$this->layerset[$i]['Layer_ID'].';';
					$this->database->execSQL($sql);
					$result = $this->database->result;
					while ($rs = mysqli_fetch_assoc($result)) {
						if(value_of($formvars, 'class'.$rs['Class_ID']) == '0' OR value_of($formvars, 'class'.$rs['Class_ID']) == '2'){
							$sql2 = 'REPLACE INTO u_rolle2used_class (user_id, stelle_id, class_id, status) VALUES ('.$this->user_id.', '.$this->stelle_id.', '.$rs['Class_ID'].', '.$formvars['class'.$rs['Class_ID']].');';
							$this->database->execSQL($sql2,4, $this->loglevel);
						}
						elseif(value_of($formvars, 'class'.$rs['Class_ID']) == '1'){
							$sql1 = 'DELETE FROM u_rolle2used_class WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id.' AND class_id='.$rs['Class_ID'].';';
							$this->database->execSQL($sql1,4, $this->loglevel);
						}
					}
				}
			}
		}
		return 1;
	}

	function getLayer($LayerName) {
		global $language;
		$layer_name_filter = '';
		
		# Abfragen der Layer in der Rolle
		if($language != 'german') {
			$name_column = "
			CASE
				WHEN l.`Name_" . $language . "` != \"\" THEN l.`Name_" . $language . "`
				ELSE l.`Name`
			END AS Name";
		}
		else
			$name_column = "l.Name";

		if ($LayerName != '') {
			$layer_name_filter = " AND (l.Name LIKE '" . $LayerName . "' OR l.alias LIKE '" . $LayerName . "'";
			if(is_numeric($LayerName))
				$layer_name_filter .= " OR l.Layer_ID = " . $LayerName;
			$layer_name_filter .= ")";
		}

		$sql = "
			SELECT " .
				$name_column . ",
				l.Layer_ID,
				alias, Datentyp, Gruppe, pfad, maintable, oid, maintable_is_view, Data, tileindex, `schema`, document_path, document_url, classification, ddl_attribute, CASE WHEN connectiontype = 6 THEN concat('host=', c.host, ' port=', c.port, ' dbname=', c.dbname, ' user=', c.user, ' password=', c.password) ELSE l.connection END as connection, printconnection,
				classitem, connectiontype, epsg_code, tolerance, toleranceunits, wms_name, wms_auth_username, wms_auth_password, wms_server_version, ows_srs,
				wfs_geom, selectiontype, querymap, processing, kurzbeschreibung, datenherr, metalink, status, trigger_function, ul.`queryable`, ul.`drawingorder`,
				ul.`minscale`, ul.`maxscale`,
				ul.`offsite`,
				coalesce(r2ul.transparency, ul.transparency, 100) as transparency,
				coalesce(r2ul.labelitem, l.labelitem) as labelitem,
				l.labelitem as original_labelitem,
				l.duplicate_from_layer_id,
				l.duplicate_criterion,
				ul.`postlabelcache`,
				`Filter`,
				r2ul.gle_view,
				ul.`template`,
				`header`,
				`footer`,
				ul.`symbolscale`,
				ul.`logconsume`,
				ul.`requires`,
				ul.`privileg`,
				ul.`export_privileg`,
				`start_aktiv`,
				r2ul.showclasses,
				r2ul.rollenfilter,
				r2ul.geom_from_layer
			FROM
				used_layer AS ul,
				u_rolle2used_layer as r2ul,
				layer AS l
				LEFT JOIN connections as c ON l.connection_id = c.id
			WHERE
				l.Layer_ID=ul.Layer_ID AND
				r2ul.Stelle_ID=ul.Stelle_ID AND
				r2ul.Layer_ID=ul.Layer_ID AND
				ul.Stelle_ID= " . $this->stelle_id . " AND
				r2ul.User_ID= " . $this->user_id .
				$layer_name_filter . "
			ORDER BY
				ul.drawingorder desc
		";
		#echo $sql.'<br>';
		$this->debug->write("<p>file:rolle.php class:rolle->getLayer - Abfragen der Layer zur Rolle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		$i = 0;
		while ($rs = $this->database->result->fetch_assoc()) {
			if($rs['rollenfilter'] != ''){		// Rollenfilter zum Filter hinzufügen
				if($rs['Filter'] == ''){
					$rs['Filter'] = '('.$rs['rollenfilter'].')';
				}
				else {
					$rs['Filter'] = str_replace(' AND ', ' AND ('.$rs['rollenfilter'].') AND ', $rs['Filter']);
				}
			}
			foreach(array('Name', 'alias', 'connection', 'classification', 'pfad', 'Data') AS $key) {
				$rs[$key] = replace_params(
					$rs[$key],
					rolle::$layer_params,
					$this->user_id,
					$this->stelle_id,
					rolle::$hist_timestamp,
					$language,
					$rs['duplicate_criterion']
				);
			}
			$layer[$i]=$rs;
			$layer['layer_ids'][$rs['Layer_ID']] =& $layer[$i];
			$layer['layer_ids'][$layer[$i]['requires']]['required'] = $rs['Layer_ID'];
			$i++;
		}
		return $layer;
	}

	function getRollenLayer($LayerName, $typ = NULL) {
		$sql ="
			SELECT l.*, 4 as tolerance, -l.id as Layer_ID, l.query as pfad, CASE WHEN Typ = 'import' THEN 1 ELSE 0 END as queryable, gle_view,
				concat('(', rollenfilter, ')') as Filter
			FROM rollenlayer AS l";
    $sql.=' WHERE l.stelle_id = '.$this->stelle_id.' AND l.user_id = '.$this->user_id;
    if ($LayerName!='') {
      $sql.=' AND (l.Name LIKE "'.$LayerName.'" ';
      if(is_numeric($LayerName)){
        $sql.='OR l.id = "'.$LayerName.'")';
      }
      else{
        $sql.=')';
      }
    }
		if($typ != NULL){
			$sql .= " AND Typ = '".$typ."'";
		}
    #echo $sql.'<br>';
    $this->debug->write("<p>file:rolle.php class:rolle->getRollenLayer - Abfragen der Rollenlayer zur Rolle:<br>".$sql,4);
    $this->database->execSQL($sql);
    if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		$layer = array();
    while ($rs = $this->database->result->fetch_assoc()) {
      $layer[]=$rs;
    }
    return $layer;
  }

	function setQueryStatus($formvars) {
		# Eintragen des query_status=1 für Layer, die für die Abfrage selektiert wurden
		for ($i=0;$i<count($this->layerset);$i++){
			$query_status = $formvars['qLayer'.$this->layerset[$i]['Layer_ID']];
			if(isset($query_status)){	
				if($this->layerset[$i]['Layer_ID'] > 0){
					$sql ='UPDATE u_rolle2used_layer set queryStatus="'.$query_status.'"';
					$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
					$sql.=' AND layer_id='.$this->layerset[$i]['Layer_ID'];
				}
				else{		# Rollenlayer
					$sql ='UPDATE rollenlayer set queryStatus="'.$query_status.'"';
					$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
					$sql.=' AND id='.-$this->layerset[$i]['Layer_ID'];
				}
				$this->debug->write("<p>file:rolle.php class:rolle->setQueryStatus - Speichern des Abfragestatus der Layer zur Rolle:",4);
				$this->database->execSQL($sql,4, $this->loglevel);
			}
		}
		return 1;
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
			$this->immer_weiter_erfassen = $rs['immer_weiter_erfassen'];
			$this->overlayx=$rs['overlayx'];
			$this->overlayy=$rs['overlayy'];
			$this->instant_reload=$rs['instant_reload'];
			$this->menu_auto_close=$rs['menu_auto_close'];
			rolle::$layer_params = (array)json_decode('{' . $rs['layer_params'] . '}');
			$this->visually_impaired = $rs['visually_impaired'];
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

  function setSelectedButton($selectedButton) {
    $this->selectedButton=$selectedButton;
    # Eintragen des aktiven Button
    $sql ='UPDATE rolle SET selectedButton="'.$selectedButton.'"';
    $sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
    $this->debug->write("<p>file:rolle.php class:rolle->setSelectedButton - Speichern des zuletzt gewählten Buttons aus dem Kartenfensters:",4);
    $this->database->execSQL($sql,4, $this->loglevel);
    return 1;
  }

  function saveSettings($extent) {
    $sql ='UPDATE rolle SET minx='.$extent->minx.',miny='.$extent->miny;
    $sql.=',maxx='.$extent->maxx.',maxy='.$extent->maxy;
    $sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
    # echo $sql;
    $this->debug->write("<p>file:rolle.php class:rolle function:saveSettings - Speichern der Einstellungen zur Rolle:",4);
    $this->database->execSQL($sql,4, $this->loglevel);
    return 1;
  }

  function setConsumeActivity($time,$activity,$prevtime) {
    if (LOG_CONSUME_ACTIVITY==1) {
      # function setzt eine Verbraucheraktivität (den Zugriff auf Layer oder Daten)
      # Starten der Transaktion
      $sql ='START TRANSACTION';
      $ret=$this->database->execSQL($sql,4, 1);
      if ($ret[0]) {
        # Fehler bei Datenbankanfrage
        $ret[1]='<br>Die Transaktion zur Eintragung der Verbraucheraktivität konnte gestartet werden.<br>'.$ret[1];
      }
      else {
        # Eintragen der Consume Activity
        $sql ='INSERT INTO u_consume SET';
        $sql.=' user_id='.$this->user_id;
        $sql.=', stelle_id='.$this->stelle_id;
        $sql.=', time_id="'.$time.'"';
        $sql.=',activity="'.$activity.'"';
        if ($prevtime=="0000-00-00 00:00:00" OR $prevtime=='') {
          $prevtime=$time;
        }
        $sql.=',prev="'.$prevtime.'"';        
        $sql.=', nimagewidth='.$this->nImageWidth.',nimageheight='.$this->nImageHeight;
				$sql.=", epsg_code='".$this->epsg_code."'";
        $sql.=', minx='.$this->oGeorefExt->minx.', miny='.$this->oGeorefExt->miny;
        $sql.=', maxx='.$this->oGeorefExt->maxx.', maxy='.$this->oGeorefExt->maxy;
        #echo $sql;
        $ret=$this->database->execSQL($sql,4, 1);
        
        if ($ret[0]) {
          # Fehler bei Datenbankanfrage
          $errmsg.='<br>Die Verbraucheraktivität konnte nicht eingetragen werden.<br>'.$ret[1];
        }
        if($activity != 'print' AND $activity != 'print_preview'){    # bei der Druckvorschau und dem PDF-Export zwar loggen aber nicht in die History aufnehmen
          $this->newtime = $time;
          $ret = $this->set_last_time_id($time);
          if ($ret[0]) {
            # Fehler bei Datenbankanfrage
            $errmsg.='<br>Die Verbraucheraktivität konnte nicht eingetragen werden.<br>'.$ret[1];
          }
        }
        
        # Abfragen der aktiven Layer
        $ret=$this->getAktivLayer(array(1,2),array(),1);
        if ($ret[0]) {
          # Fehler bei Datenbankanfrage
          $errmsg.='<br>Fehler bei der Abfrage der aktiven Layer.<br>'.$ret[1];
        }
        else {
          $layer=$ret[1];
          # Eintragung des Zugriffs auf die angeschalteten Layer
          for ($i=0;$i<count($layer);$i++) {
            # !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            # Hier eventuell später mal einbauen, dass geprüft wird, ob die Layer wirklich verfügbar sind.
            # Wichtig wird das besonders für externe Datenquellen wie fremde WMS oder WFS Layer
            # Die dürfen nicht mit abgerechnet werden, wenn sie beim Client nicht erscheinen.
            # bzw. nicht geliefert werden
            $sql ='INSERT INTO u_consume2layer SET';
            $sql.=' user_id='.$this->user_id;
            $sql.=', stelle_id='.$this->stelle_id;
            $sql.=', time_id="'.$time.'"';
            $sql.=', layer_id='.$layer[$i];
            $ret=$this->database->execSQL($sql,4, 1);
            if ($ret[0]) {
              # Fehler bei Datenbankanfrage
              $errmsg.='<br>Die Verbraucheraktivität für den Zugiff auf den Layer: '.$layer[$i].' konnte nicht eingetragen werden.<br>'.$ret[1];
            }
          } # ende eintragen aktiver Layer
        } # ende erfolgreiches Abfragen der aktiven Layer
      } # ende kartenausschnitt loggen
      if ($errmsg!='') {
        # Es sind Fehler innerhalb der Transaktion aufgetreten, Abbrechen der Transaktion
        $sql ='ROLLBACK TRANSACTION';
        $ret=$this->database->execSQL($sql,4, 1);
        if ($ret[0]) {
          # Fehler bei Datenbankanfrage
          $errmsg.='<br>Die Transaktion zum Eintragen der Verbraucheraktivität konnte nicht abgebrochen werden.<br>'.$ret[1];
        }
        $ret[0]=1;
        $ret[1]=$errmsg;
      }
      else {
        # Es sind keine Fehler innerhalb der Transaktion aufgetreten, Erfolgreich abschließen der Transaktion
        $sql ='COMMIT';
        $ret=$this->database->execSQL($sql,4, 1);
        if ($ret[0]) {
          # Fehler bei Datenbankanfrage
          $errmsg.='<br>Die Transaktion zum Eintragen der Verbraucheraktivität konnte nicht erfolgreich abgeschlossen werden.<br>'.$ret[1];
        }
        $ret[0]=0;
        $ret[1]='<br>Verbraucheraktivität erfolgreich eingetragen.';
      }
    }
    else {
      $ret[0]=0;
      $ret[1]='<br>Funktion zur Speicherung der Verbraucheraktivitäten ist ausgeschaltet (LOG_CONSUME_ACTIVITY).';
    }
    return $ret;
  }

  function set_last_time_id($time){
    # Eintragen der last_time_id
    $sql = 'UPDATE rolle SET last_time_id="'.$time.'"';
    $sql.= ' WHERE user_id = '.$this->user_id.' AND stelle_id = '.$this->stelle_id;
    #echo $sql;
    $ret=$this->database->execSQL($sql,4, 1);
    return $ret;
  }

  function getAktivLayer($aktivStatus,$queryStatus,$logconsume) {
		$layer = array();
    # Abfragen der zu loggenden Layer der Rolle
    $sql ='SELECT r2ul.layer_id FROM u_rolle2used_layer AS r2ul';
    if ($logconsume) {
      $sql.=',used_layer AS ul,layer AS l,stelle AS s';
    }
    $sql.=' WHERE r2ul.user_id='.$this->user_id.' AND r2ul.stelle_id='.$this->stelle_id;
    if ($logconsume) {
      $sql.=' AND r2ul.layer_id=ul.Layer_ID AND r2ul.stelle_id=ul.Stelle_ID';
      $sql.=' AND ul.Layer_ID=l.Layer_ID AND ul.Stelle_ID=s.ID';
      $sql.=' AND (s.logconsume="1"';
      $sql.=' OR l.logconsume="1"';
      $sql.=' OR ul.logconsume="1"';
      $sql.=' OR r2ul.logconsume="1")';
    }
    $anzaktivStatus=count($aktivStatus);
    if ($anzaktivStatus>0) {
      $sql.=' AND r2ul.aktivStatus IN ("'.$aktivStatus[0].'"';
      for ($i=1;$i<$anzaktivStatus;$i++) {
        $sql.=',"'.$aktivStatus[$i].'"';
      }
      $sql.=')';
    }
    $anzqueryStatus=count($queryStatus);
    if ($anzqueryStatus>0) {
      $sql.=' AND r2ul.queryStatus IN ("'.$queryStatus[0].'"';
      for ($i=1;$i<$anzqueryStatus;$i++) {
        $sql.=',"'.$queryStatus[$i].'"';
      }
      $sql.=')';
    }
    #echo $sql;
    $this->debug->write("<p>file:rolle.php class:rolle->getAktivLayer - Abfragen der aktiven Layer zur Rolle:<br>".$sql,4);
    $this->database->execSQL($sql,4, 0);
    if (!$this->database->success) {
      # Fehler bei Datenbankanfrage
      $ret[0] = 1;
      $ret[1] = '<br>Die aktiven Layer konnten nicht abgefragt werden.<br>'.$ret[1];
    }
    else {
      while ($rs = $this->database->result->fetch_assoc()) {
        $layer[] = $rs['layer_id'];
      }
      $ret[0] = 0;
      $ret[1] = $layer;
    }
    return $ret;
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
		$this->spatial_ref_code = EPSGCODE_ALKIS . ", " . EARTH_RADIUS;
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
			$this->debug->write("Database connection: " . $this->dbConn . " successfully opend.", 4);
			$this->setClientEncoding();
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

	function getBezeichnungFromPosition($position, $epsgcode) {
    $this->debug->write("<p>kataster.php Flur->getBezeichnungFromPosition:",4);
		$sql ="SELECT gm.bezeichnung as gemeindename, fl.gemeindezugehoerigkeit_gemeinde gemeinde, gk.bezeichnung as gemkgname, fl.land::text||fl.gemarkungsnummer::text as gemkgschl, fl.flurnummer as flur, CASE WHEN fl.nenner IS NULL THEN fl.zaehler::text ELSE fl.zaehler::text||'/'||fl.nenner::text end as flurst, s.bezeichnung as strasse, l.hausnummer ";
    $sql.="FROM alkis.ax_gemarkung as gk, alkis.ax_gemeinde as gm, alkis.ax_flurstueck as fl ";
		$sql.="LEFT JOIN alkis.ax_lagebezeichnungmithausnummer l ON l.gml_id = ANY(fl.weistauf) ";
		$sql.="LEFT JOIN alkis.ax_lagebezeichnungkatalogeintrag s ON l.kreis=s.kreis AND l.gemeinde=s.gemeinde AND s.lage = lpad(l.lage,5,'0') ";
    $sql.="WHERE gk.gemarkungsnummer = fl.gemarkungsnummer AND gm.kreis = fl.gemeindezugehoerigkeit_kreis AND gm.gemeinde = fl.gemeindezugehoerigkeit_gemeinde ";
    $sql.=" AND ST_WITHIN(st_transform(st_geomfromtext('POINT(".$position['rw']." ".$position['hw'].")',".$epsgcode."), ".EPSGCODE_ALKIS."),fl.wkb_geometry) ";
		$sql.= $this->build_temporal_filter(array('gk', 'gm', 'fl'));
    #echo $sql;
    $ret=$this->execSQL($sql,4, 0);
    if ($ret[0]!=0) {
      $ret[1]='Fehler bei der Abfrage der Datenbank.'.$ret[1];
    }
    else {
      if (pg_num_rows($ret[1])>0) {
        $ret[1]=pg_fetch_assoc($ret[1]);
      }
    }
    return $ret;
  }

	function build_temporal_filter($tablenames){
		$timestamp = rolle::$hist_timestamp;
		if($timestamp == ''){
			foreach($tablenames as $tablename){
				$filter .= ' AND '.$tablename.'.endet IS NULL ';
			}
		}
		else{
			foreach($tablenames as $tablename){
				$filter .= ' AND '.$tablename.'.beginnt <= \''.$timestamp.'\' and (\''.$timestamp.'\' < '.$tablename.'.endet or '.$tablename.'.endet IS NULL) ';
			}
		}
		return $filter;
	}

  function close() {
    $this->debug->write("<br>PostgreSQL Verbindung mit ID: ".$this->dbConn." schließen.",4);
    return pg_close($this->dbConn);
  }
}

class db_mapObj{
  var $debug;
  var $referenceMap;
  var $Layer;
  var $anzLayer;
  var $nurAufgeklappteLayer;
  var $Stelle_ID;
  var $User_ID;
	var $db;
	var $OhneRequires;

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
	
	function getFilter($layer_id, $stelle_id){
    $sql ='SELECT Filter FROM used_layer WHERE Layer_ID = '.$layer_id.' AND Stelle_ID = '.$stelle_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getFilter - Lesen des Filter-Statements des Layers:<br>" . $sql,4);
    $this->db->execSQL($sql);
    if (!$this->db->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . "<br>" . $this->db->mysqli->error, 4); return 0; }
    $rs = $this->db->result->fetch_row();
    $filter = $rs[0];
    return $filter;
  }		
	
	function read_layer_attributes($layer_id, $layerdb, $attributenames, $all_languages = false, $recursive = false){
		global $language;
		$attributes = array();
		$einschr = '';

		$alias_column = (
			(!$all_languages AND $language != 'german') ?
			"
				CASE
					WHEN `alias_" . $language. "` != '' THEN `alias_" . $language . "`
					ELSE `alias`
				END AS alias
			" :
			"
				`alias`
			"
		);

		if ($attributenames != NULL) {
			$einschr = " AND a.name IN ('" . implode("', '", $attributenames) . "')";
		}

		$sql = "
			SELECT 
				`order`, " .
				$alias_column . ", `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`,
				`layer_id`,
				a.`name`,
				`real_name`,
				`tablename`,
				`table_alias_name`,
				`type`,
				d.`name` as typename,
				`geometrytype`,
				`constraints`,
				`saveable`,
				`nullable`,
				`length`,
				`decimal_length`,
				`default`,
				`form_element_type`,
				`options`,
				`tooltip`,
				`group`,
				`arrangement`,
				`labeling`,
				`raster_visibility`,
				`dont_use_for_new`,
				`mandatory`,
				`quicksearch`,
				`visible`,
				`vcheck_attribute`,
				`vcheck_operator`,
				`vcheck_value`,
				`order`,
				`privileg`,
				`query_tooltip`
			FROM
				`layer_attributes` as a LEFT JOIN
				`datatypes` as d ON d.`id` = REPLACE(`type`, '_', '')
			WHERE
				`layer_id` = " . $layer_id .
				$einschr . "
			ORDER BY
				`order`
		";
		#echo '<br>Sql read_layer_attributes: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_layer_attributes:<br>" . $sql,4);
		$ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		$i = 0;
		while ($rs = $ret['result']->fetch_array()){
			$attributes['order'][$i] = $rs['order'];
			$attributes['name'][$i] = $rs['name'];
			$attributes['indizes'][$rs['name']] = $i;
			if($rs['real_name'] == '')$rs['real_name'] = $rs['name'];
			$attributes['real_name'][$rs['name']] = $rs['real_name'];
			if ($rs['tablename']){
				if (strpos($rs['tablename'], '.') !== false){
					$explosion = explode('.', $rs['tablename']);
					$rs['tablename'] = $explosion[1];		# Tabellenname ohne Schema
					$attributes['schema_name'][$rs['tablename']] = $explosion[0];
				}
				$attributes['table_name'][$i]= $rs['tablename'];
				$attributes['table_name'][$rs['name']] = $rs['tablename'];
			}
			if ($rs['table_alias_name'])$attributes['table_alias_name'][$i] = $rs['table_alias_name'];
			if ($rs['table_alias_name'])$attributes['table_alias_name'][$rs['name']] = $rs['table_alias_name'];
			$attributes['table_alias_name'][$rs['tablename']] = $rs['table_alias_name'];
			$attributes['type'][$i] = $rs['type'];
			$attributes['typename'][$i] = $rs['typename'];
			$type = ltrim($rs['type'], '_');
			if ($recursive AND is_numeric($type)){
				$attributes['type_attributes'][$i] = $this->read_datatype_attributes($type, $layerdb, NULL, $all_languages, true);
			}
			if ($rs['type'] == 'geometry'){
				$attributes['the_geom'] = $rs['name'];
			}
			$attributes['geomtype'][$i]= $rs['geometrytype'];
			$attributes['geomtype'][$rs['name']]= $rs['geometrytype'];
			$attributes['constraints'][$i]= $rs['constraints'];
			$attributes['constraints'][$rs['real_name']]= $rs['constraints'];
			$attributes['saveable'][$i]= $rs['saveable'];
			$attributes['nullable'][$i]= $rs['nullable'];
			$attributes['length'][$i]= $rs['length'];
			$attributes['decimal_length'][$i]= $rs['decimal_length'];

			if (substr($rs['default'], 0, 6) == 'SELECT'){					# da Defaultvalues auch dynamisch sein können (z.B. 'now'::date) wird der Defaultwert erst hier ermittelt
				$ret1 = $layerdb->execSQL($rs['default'], 4, 0);
				if ($ret1[0] == 0) {
					$attributes['default'][$i] = @array_pop(pg_fetch_row($ret1[1]));
				}
			}
			else {															# das sind die alten Defaultwerte ohne 'SELECT ' davor, ab Version 1.13 haben Defaultwerte immer ein SELECT, wenn man den Layer in dieser Version einmal gespeichert hat
				$attributes['default'][$i] = $rs['default'];
			}
			$attributes['form_element_type'][$i] = $rs['form_element_type'];
			$attributes['form_element_type'][$rs['name']] = $rs['form_element_type'];
			$rs['options'] = str_replace('$hist_timestamp', rolle::$hist_timestamp, $rs['options']);
			$rs['options'] = str_replace('$language', $language, $rs['options']);
			$attributes['options'][$i] = $rs['options'];
			$attributes['options'][$rs['name']] = $rs['options'];
			$attributes['alias'][$i] = $rs['alias'];
			$attributes['alias'][$attributes['name'][$i]] = $rs['alias'];
			$attributes['alias_low-german'][$i] = $rs['alias_low-german'];
			$attributes['alias_english'][$i] = $rs['alias_english'];
			$attributes['alias_polish'][$i] = $rs['alias_polish'];
			$attributes['alias_vietnamese'][$i] = $rs['alias_vietnamese'];
			$attributes['tooltip'][$i] = $rs['tooltip'];
			$attributes['group'][$i] = $rs['group'];
			$attributes['arrangement'][$i] = $rs['arrangement'];
			$attributes['labeling'][$i] = $rs['labeling'];
			$attributes['raster_visibility'][$i] = $rs['raster_visibility'];
			$attributes['dont_use_for_new'][$i] = $rs['dont_use_for_new'];
			$attributes['mandatory'][$i] = $rs['mandatory'];
			$attributes['quicksearch'][$i] = $rs['quicksearch'];
			$attributes['visible'][$i] = $rs['visible'];
			$attributes['vcheck_attribute'][$i] = $rs['vcheck_attribute'];
			$attributes['vcheck_operator'][$i] = $rs['vcheck_operator'];
			$attributes['vcheck_value'][$i] = $rs['vcheck_value'];
			$attributes['dependents'][$i] = &$dependents[$rs['name']];
			$dependents[$rs['vcheck_attribute']][] = $rs['name'];
			$attributes['privileg'][$i] = $rs['privileg'];
			$attributes['query_tooltip'][$i] = $rs['query_tooltip'];
			if ($rs['form_element_type'] == 'Style') {
				$attributes['style'] = $rs['name'];
				$attributes['visible'][$i] = 0;
			}
			if ($rs['form_element_type'] == 'Editiersperre') {
				$attributes['Editiersperre'] = $rs['name'];
			}
			$i++;
		}
		if (value_of($attributes, 'table_name') != NULL) {
			$attributes['all_table_names'] = array_unique($attributes['table_name']);
			//$attributes['all_alias_table_names'] = array_values(array_unique($attributes['table_alias_name']));
			foreach ($attributes['all_table_names'] as $tablename) {
				$attributes['oids'][] = $layerdb->check_oid($tablename);   # testen ob Tabelle oid hat
			}
		}
		else {
			$attributes['all_table_names'] = array();
		}
		return $attributes;
  }

	function getlayerdatabase($layer_id, $host) {
		#echo '<br>GUI->getlayerdatabase layer_id: ' . $layer_id;
		$layerdb = new pgdatabase();
		$rs = $this->get_layer_connection($layer_id);
		if (count($rs) == 0) {
			return null;
		}
		$layerdb->schema = ($rs['schema'] == '' ? 'public' : $rs['schema']);
		$layerdb->host = $host; # depricated since host is allways in connection table
		if (!$layerdb->open($rs['connection_id'])) {
			echo 'Die Verbindung zur PostGIS-Datenbank konnte mit connection_id: ' . $rs['connection_id'] . ' nicht hergestellt werden:';
			exit;
		}
		return $layerdb;
	}

	/**
	* Function get the postgres connection_id and the schema of the layer with given layer_id
	* @params integer $layer_id, If layer_id is negativ the connection_id is from table rollen_layer
	* @return array with integer connection_id and string schema name, return an empty array if no connection for layer_id found
	*/
	function get_layer_connection($layer_id) {
		# $layer_id < 0 Rollenlayer else normal layer
		$sql = "
			SELECT
				`connection_id`,
				" . ($layer_id < 0 ? "'" . CUSTOM_SHAPE_SCHEMA . "' AS " : "") . "`schema`
			FROM
				" . ($layer_id < 0 ? "rollenlayer" : "layer") . "
			WHERE
				" . ($layer_id < 0 ? "-id" : "Layer_ID") . " = " . $layer_id . " AND
				`connectiontype` = 6
		";
		$this->debug->write("<p>file:kvwmap class:db_mapObj->get_layer_connection - Lesen der connection Daten des Layers:<br>" . $sql, 4);
		$this->db->execSQL($sql);
		if ($this->db->success) {
			return $this->db->result->fetch_assoc();
		}
		else {
			$this->debug->write("<br>Abbruch beim Lesen der Layer connection in get_layer_connection, Zeile: " . __LINE__ . "<br>" . $this->db->mysqli->error, 4);
			return array();
		}
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

	function read_Layer($withClasses, $useLayerAliases = false, $groups = NULL){
		global $language;

		if($language != 'german') {
			$name_column = "
			CASE
				WHEN l.`Name_" . $language . "` != \"\" THEN l.`Name_" . $language . "`
				ELSE l.`Name`
			END AS Name";
			$group_column = '
			CASE 
				WHEN `Gruppenname_'.$language.'` IS NOT NULL THEN `Gruppenname_'.$language.'` 
				ELSE `Gruppenname` 
			END AS Gruppenname';
		}
		else{
			$name_column = "l.Name";
			$group_column = 'Gruppenname';
		}

		$sql = "
			SELECT DISTINCT
				coalesce(rl.transparency, ul.transparency, 100) as transparency, rl.`aktivStatus`, rl.`queryStatus`, rl.`gle_view`, rl.`showclasses`, rl.`logconsume`, rl.`rollenfilter`,
				ul.`queryable`, COALESCE(rl.drawingorder, ul.drawingorder) as drawingorder, ul.legendorder, ul.`minscale`, ul.`maxscale`, ul.`offsite`, ul.`postlabelcache`, ul.`Filter`, ul.`template`, ul.`header`, ul.`footer`, ul.`symbolscale`, ul.`logconsume`, ul.`requires`, ul.`privileg`, ul.`export_privileg`,
				l.Layer_ID," .
				$name_column . ",
				l.alias,
				l.Datentyp, l.Gruppe, l.pfad, l.Data, l.tileindex, l.tileitem, l.labelangleitem, coalesce(rl.labelitem, l.labelitem) as labelitem,
				l.labelmaxscale, l.labelminscale, l.labelrequires,
				l.connection_id,
				CASE
					WHEN connectiontype = 6 THEN concat('host=', c.host, ' port=', c.port, ' dbname=', c.dbname, ' user=', c.user, ' password=', c.password)
					ELSE l.connection
				END as connection,
				l.printconnection,
				l.connectiontype,
				l.classitem, l.styleitem, l.classification, 
				l.cluster_maxdistance, l.tolerance, l.toleranceunits, l.processing, l.epsg_code, l.ows_srs, l.wms_name, l.wms_keywordlist, l.wms_server_version,
				l.wms_format, l.wms_auth_username, l.wms_auth_password, l.wms_connectiontimeout, l.selectiontype, l.logconsume,l.metalink, l.status, l.trigger_function, l.sync,
				l.duplicate_from_layer_id,
				l.duplicate_criterion,
				g.id, ".$group_column.", g.obergruppe, g.order
			FROM
				u_rolle2used_layer AS rl,
				used_layer AS ul,
				layer AS l LEFT JOIN
				u_groups AS g ON l.Gruppe = g.id LEFT JOIN
				u_groups2rolle AS gr ON g.id = gr.id LEFT JOIN
				connections as c ON l.connection_id = c.id
			WHERE
				rl.stelle_id = ul.Stelle_ID AND
				rl.layer_id = ul.Layer_ID AND
				l.Layer_ID = ul.Layer_ID AND
				(ul.minscale != -1 OR ul.minscale IS NULL) AND
				rl.stelle_ID = " . $this->Stelle_ID . " AND rl.user_id = " . $this->User_ID . " AND
				gr.stelle_id = " . $this->Stelle_ID . " AND
				gr.user_id = " . $this->User_ID .
				($groups != NULL ? " AND g.id IN (" . $groups . ")" : '') .
				($this->nurAufgeklappteLayer ? " AND (rl.aktivStatus != '0' OR gr.status != '0' OR ul.requires != '')" : '') .
				($this->nurAktiveLayer ? " AND (rl.aktivStatus != '0')" : '') .
				($this->OhneRequires ? " AND (ul.requires IS NULL)" : '') .
				($this->nurFremdeLayer ? " AND (c.host NOT IN ('pgsql', 'localhost') OR l.connectiontype != 6 AND rl.aktivStatus != '0')" : '') . "
			ORDER BY
				drawingorder
		";
		#echo '<br>SQL zur Abfrage der Layer: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Layer - Lesen der Layer der Rolle:<br>" . $sql,4);
		$ret = $this->db->execSQL($sql);
		if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		$layer = array();
		$layer['list'] = array();
		$this->disabled_classes = $this->read_disabled_classes();
		$i = 0;
		while ($rs = $ret['result']->fetch_assoc()) {
			if ($rs['rollenfilter'] != '') {		// Rollenfilter zum Filter hinzufügen
				if ($rs['Filter'] == ''){
					$rs['Filter'] = '('.$rs['rollenfilter'].')';
				}
				else {
					$rs['Filter'] = str_replace(' AND ', ' AND ('.$rs['rollenfilter'].') AND ', $rs['Filter']);
				}
			}
			if($rs['alias'] == '' OR !$useLayerAliases){
				$rs['alias'] = $rs['Name'];
			}
			$rs['id'] = $i;
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
			if ($withClasses == 2 OR $rs['requires'] != '' OR ($withClasses == 1 AND $rs['aktivStatus'] != '0')) {
				# bei withclasses == 2 werden für alle Layer die Klassen geladen,
				# bei withclasses == 1 werden Klassen nur dann geladen, wenn der Layer aktiv ist
				$rs['Class']=$this->read_Classes($rs['Layer_ID'], $this->disabled_classes, false, $rs['classification']);
			}
			if($rs['maxscale'] > 0)$rs['maxscale'] = $rs['maxscale']+0.3;
			if($rs['minscale'] > 0)$rs['minscale'] = $rs['minscale']-0.3;
			$layer['list'][$i]=$rs;
			$layer['list'][$i]['required'] =& $requires_layer[$rs['Layer_ID']];		# Pointer auf requires-Array
			if($rs['requires'] != '')$requires_layer[$rs['requires']][] = $rs['Layer_ID'];		# requires-Array füllen
			$layer['layer_ids'][$rs['Layer_ID']] =& $layer['list'][$i];		# damit man mit einer Layer-ID als Schlüssel auf dieses Array zugreifen kann
			$i++;
		}
		return $layer;
	}

  function read_disabled_classes() {
		$classarray = array();
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

	# Einer Klasse können mehrere Labels zugeordnet werden
	# Abfrage der Labels nicht aus Tabelle classes sondern aus u_labels2classes
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
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_RollenLayer - Lesen der RollenLayer:<br>" . $sql,4);
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
      $Layer[] = $rs;
    }
    return $Layer;
  }

}

class Flur {

  var $FlurID;
  var $database;

	function __construct($GemID,$GemkgID,$FlurID,$database) {
    # constructor
    global $debug;
    $this->debug=$debug;
    $this->GemID=$GemID;
    $this->GemkgID=$GemkgID;
    $this->FlurID=$FlurID;
    $this->database=$database;
    $this->LayerName=LAYERNAME_FLUR;
  }

	function getBezeichnungFromPosition($position, $epsgcode){
		return $this->database->getBezeichnungFromPosition($position, $epsgcode);
  }
}
?>
