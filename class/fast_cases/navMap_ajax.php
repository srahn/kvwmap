<?
function get_remote_ip() {
	$ip = '172.0.0.1';
	if (strpos(getenv('REMOTE_ADDR'), '172.') !== 0) {
		$ip = getenv('REMOTE_ADDR');
	}
	else {
		$ip = $_SERVER['HTTP_X_REAL_IP'];
		if ($ip == '') {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
	}
	return $ip;
}

function before_last($txt, $delimiter) {
	#echo '<br>Return the part of ' . $txt . ' before the last occurence of ' . $delimiter;
	if (!$delimiter) {
		return '';
	}
	$pos = strrpos($txt, $delimiter);
  return $pos === false ? $txt : substr($txt, 0, $pos);
}

function getDataParts($data){
	$first_space_pos = strpos($data, ' ');
	$geom = substr($data, 0, $first_space_pos);					# geom am Anfang
	$rest = substr($data, $first_space_pos);
	$usingposition = stripos($rest, 'using');
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

function get_first_word_after($str, $word, $delim1 = ' ', $delim2 = ' ', $last = false){
	if ($last) {
		$word_pos = strripos($str, $word);
	}
	else {
		$word_pos = stripos($str, $word);
	}
	if ($word_pos !== false) {
		$str_from_word_pos = substr($str, $word_pos + strlen($word));
		$parts = explode($delim2, trim($str_from_word_pos, $delim1));
		return trim($parts[0]);
	}
}

function count_or_0($val) {
	if (is_null($val) OR !is_array($val)) {
		return 0;
	}
	else {
		return count($val);
	}
}

function replace_semicolon($text) {
	return str_replace(';', '', $text);
}

function rectObj($minx, $miny, $maxx, $maxy, $imageunits = 0) {
	if (MAPSERVERVERSION >= 800) {
		return new RectObj($minx, $miny, $maxx, $maxy, $imageunits);
	}
	else {
		$rect = new RectObj();
		$rect->setextent($minx, $miny, $maxx, $maxy);
		return $rect;
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

function replace_params_link($str, $params, $layer_id) {
	if (is_array($params)) {
		foreach($params AS $key => $value){
			$str = str_replace('$'.$key, '<a href="javascript:void(0)" onclick="getLayerOptions(' . $layer_id .  ')">' . $value . '</a>', $str);
		}
	}
	return $str;
}

/**
* Function returns a readable message of sql errors optionally with word $find replaced by asterists *****
*/
function err_msg($file, $line, $msg, $find = '') {
	return "<br>Abbruch in " . $file . " Zeile: " . $line . "<br>wegen: " . ($find != '' ? str_replace($find, '*****', $msg) : $msg). "<p>" . INFO1;
}

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
	if (!is_array($array)) {
		$array = array();
	}
	return (array_key_exists($key, $array) ? $array[$key] :	'');
}

function InchesPerUnit($unit, $center_y){
	if($unit == MS_METERS){
		return 39.3701;
	}
	elseif($unit == MS_DD){
		return 39.3701 * degree2meter($center_y);
	}
}

function degree2meter($center_y) {
	if($center_y != 0.0){
		$cos_lat = cos(pi() * $center_y/180.0);
		$lat_adj = sqrt(1 + $cos_lat * $cos_lat)/sqrt(2);
	}
	return 111319 * $lat_adj;
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
	var $flst;
	var $formvars;
	var $legende;
	var $map;
	var $mapDB;
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
	var $layers_replace_scale = array();
	static $messages = array();

  function __construct($main, $style, $mime_type) {
    # Debugdatei setzen
    global $debug;
    $this->debug=$debug;
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

	function sanitize($vars) {
		foreach ($vars as $name => $type) {
			sanitize($this->formvars[$name], $type);
		}
	}

	function plugin_loaded($plugin) {
		global $kvwmap_plugins;
		return in_array($plugin, $kvwmap_plugins);
	}

	function resizeMap2Window() {
		global $sizes;
		$size = $sizes[$this->user->rolle->gui];
		$gui_light = ($this->user->rolle->gui == 'layouts/gui_light.php');

		if (array_key_exists('legenddisplay', $this->formvars) AND $this->formvars['legenddisplay'] !== NULL) {
			$hideLegend = $this->formvars['legenddisplay'];		// falls die Legende gerade ein/ausgeblendet wurde
		}
		else {
			$hideLegend = $this->user->rolle->hideLegend;
		}

		$width = $this->formvars['browserwidth'] -
			$size['margin']['width']
			- ($this->user->rolle->hideMenue == 1 ? $size['menue']['hide_width'] : $size['menue']['width'])
			- ($gui_light? 0 : ($hideLegend == 1 ? $size['legend']['hide_width'] : $size['legend']['width']))
			- ($gui_light ? 0 : 18);	# Breite für möglichen Scrollbalken

		$height = $this->formvars['browserheight'] -
			$size['margin']['height'] -
			$size['header']['height'] -
			$size['scale_bar']['height'] -
			((defined('LAGEBEZEICHNUNGSART') AND LAGEBEZEICHNUNGSART != '') ? $size['lagebezeichnung_bar']['height'] : 0) -
			($this->user->rolle->showmapfunctions == 1 ? $size['map_functions_bar']['height'] : 0) -
			$size['footer']['height'];

		if($width  < 0) $width = 1000;
		if($height < 0) $height = 800;
		if($height % 2 != 0)$height = $height - 1;		# muss gerade sein, sonst verspringt die Karte beim Panen immer um 1 Pixel
		if($width  % 2 != 0)$width = $width - 1;				# muss gerade sein, sonst verspringt die Karte beim Panen immer um 1 Pixel

		// $this->debug->write('<br>resizeMap2Window for gui: ' . $this->user->rolle->gui . ' to: ' . $width . 'x' . $height, 4, false);
		$this->user->rolle->setSize($width.'x'.$height);
		$this->user->rolle->readSettings();
	}
		
	/**
	 * Function zoom to maximum extent of Layer with $layer_id in current map object $this->map
	 * @param int $layer_id Id of layer or rollenlayer to zoom to
	 * @return void
	 */
	function zoom_to_max_layer_extent($layer_id) {
		sanitize($layer_id, 'int');
		# Abfragen der maximalen Ausdehnung aller Daten eines Layers
		if ($layer_id > 0) {
			$layer = $this->user->rolle->getLayer($layer_id)[0];
		}
		else {
			$layer = $this->user->rolle->getRollenLayer(-$layer_id)[0];
		}
		switch ($layer['datentyp']) {
			case MS_LAYER_POLYGON : case MS_LAYER_LINE : case MS_LAYER_POINT : {
				# Abfragen der Datenbankverbindung des Layers
				$layerdb = $this->mapDB->getlayerdatabase($layer_id, $this->Stelle->pgdbhost);
				$data = $layer['data'];
				if ($data != '') {
					# suchen nach dem ersten Vorkommen von using
					$pos = strpos(strtolower($data), 'using ');
					# Abschneiden der using Wörter im Datastatement wenn unique verwendet wurde
					if ($pos !== false) {
						$subquery = substr($data, 0, $pos);
					}
					else {
						# using kommt nicht vor, es handelt sich um ein einfaches Data Statement in der Form
						# the_geom from tabelle, übernehmen wie es ist.
						$subquery = $data;
					}
					$explosion = explode(' ', $data);
					$this->attributes['the_geom'] = $explosion[0];
				}
				else {
					$subquery = substr($layer['pfad'], 7);
					$this->attributes = $this->mapDB->read_layer_attributes($layer_id, $layerdb, NULL);
				}

				# Filter berücksichtigen
				$filter = $this->mapDB->getFilter($layer_id, $this->Stelle->id);
				if ($filter != '') {
					$filter = str_replace('$USER_ID', $this->user->id, $filter);
					$subquery .= ' WHERE ' . $filter;
				}

				$subquery = replace_params(
					$subquery,
					rolle::$layer_params,
					$this->User_ID,
					$this->Stelle_ID,
					rolle::$hist_timestamp,
					$this->rolle->language,
					$layer['duplicate_criterion']
				);

				# Erzeugen des Abfragestatements für den maximalen Extent aus dem Data String
				$sql = "
					SELECT
						st_xmin(extent) AS minx,
						st_ymin(extent) AS miny,
						st_xmax(extent) AS maxx,
						st_ymax(extent) AS maxy
					FROM
						(
							SELECT
								st_transform(
									st_setsrid(
										st_extent(" . $this->attributes['the_geom'] . "),
										" . $layer['epsg_code'] . "
									),
									" . $this->user->rolle->epsg_code . "
								) AS extent
							FROM
								(
									SELECT
										" . $subquery . "
								) AS fooForMaxLayerExtent
						) as foo
				";
				#echo $sql;

				# Abfragen der Layerausdehnung
				$ret = $layerdb->execSQL($sql, 4, 0);
				if ($ret[0]) {
					echo err_msg(htmlentities($_SERVER['PHP_SELF']), __LINE__, $sql);
					return 0;
				}
				$rs = pg_fetch_array($ret[1]);
			} break;

			case MS_LAYER_RASTER : {
				if ($layer['data'] != '') {
					# eine einzelne Rasterdatei
					$raster_file = SHAPEPATH . $layer['data'];
					if (file_exists($raster_file)) {
						$output = rand(0, 100000);
						$command = OGR_BINPATH . 'gdalinfo "' . $raster_file . '" > ' . IMAGEPATH . $output . '.info';
						exec($command);
						$infotext = file_get_contents(IMAGEPATH . $output . '.info');
						$ll = explode(', ', trim(get_first_word_after($infotext, 'Lower Left', '', ')'), ' ('));
						$ur = explode(', ', trim(get_first_word_after($infotext, 'Upper Right', '', ')'), ' ('));
					}
				}
				elseif ($layer['tileindex'] != '') {
					# ein Tile-Index
					$shape_file = SHAPEPATH.$layer['tileindex'];
					if (file_exists($shape_file)) {
						$output = rand(0, 100000);
						$command = OGR_BINPATH . 'ogrinfo -al -so ' . $shape_file . ' > ' . IMAGEPATH . $output . '.info';
						exec($command);
						$infotext = file_get_contents(IMAGEPATH . $output . '.info');
						$extent = get_first_word_after($infotext, 'Extent:', ' ', chr(10));
						$corners = explode('-', $extent);
						$ll = explode(', ', trim($corners[0], '() '));
						$ur = explode(', ', trim($corners[1], '() '));
					}
				}
				$extent = new rectObj();
				$extent->setextent($ll[0], $ll[1], $ur[0], $ur[1]);
				$rasterProjection = new projectionObj("init=epsg:".$layer['epsg_code']);
				$userProjection = new projectionObj("init=epsg:".$this->user->rolle->epsg_code);
				$extent->project($rasterProjection, $userProjection);
				$rs['minx'] = $extent->minx;
				$rs['maxx'] = $extent->maxx;
				$rs['miny'] = $extent->miny;
				$rs['maxy'] = $extent->maxy;
			} break;
		}
		if ($rs['minx'] != '') {
			if ($this->user->rolle->epsg_code == 4326) {
				$rand = 10/10000;
			}
			else {
				$rand = 10;
			}
			$minx = $rs['minx'] - $rand;
			$maxx = $rs['maxx'] + $rand;
			$miny = $rs['miny'] - $rand;
			$maxy = $rs['maxy'] + $rand;
			#echo 'box:'.$minx.' '.$miny.','.$maxx.' '.$maxy;
			$this->map->setextent($minx, $miny, $maxx, $maxy);
			# damit nicht außerhalb des Stellen-Extents oder des maximalen Layer-Maßstabs gezoomt wird
			$oPixelPos = new PointObj();
			$oPixelPos->setXY($this->map->width / 2, $this->map->height / 2);
			if ($layer['maxscale'] > 0 AND $layer['maxscale'] < $this->map->scaledenom) {
				$nScale = $layer['maxscale'] - 1;
			}
			else {
				$nScale = $this->map->scaledenom;
			}
			$this->map->zoomscale($nScale, $oPixelPos, $this->map->width, $this->map->height, $this->map->extent, $this->Stelle->MaxGeorefExt);
			$this->map_scaledenom = $this->map->scaledenom;
		}
	}

	function login_agreement() {
		$this->expect = array('agreement_accepted');
		$this->gui = LOGIN_AGREEMENT;
		$this->output();
	}

	function loadlayer($map, $layerset, $strict_layer_name = false) {
		$this->debug->write('<br>Lade Layer: ' . $layerset['name'], 4);
		if ($strict_layer_name) {
			$layer_name_attribute = 'name';
		}
		else {
			$layer_name_attribute = 'Name_or_alias';
		}
		$layer = new LayerObj($map);
		$layer->name = $layerset[$layer_name_attribute];
		$layer->metadata->set('wms_name', $layerset['wms_name'] ?: ''); #Mapserver8
		$layer->metadata->set('kvwmap_layer_id', $layerset['layer_id']);
		$layer->metadata->set('wfs_request_method', 'GET');
		if ($layerset['wms_keywordlist']) {
			$layer->metadata->set('ows_keywordlist', $layerset['wms_keywordlist']);
		}
		$layer->metadata->set('wfs_typename', $layerset['wms_name'] ?: ''); #Mapserver8
		$layer->metadata->set('wms_title', $layerset['Name_or_alias'] ?: ''); #Mapserver8
		$layer->metadata->set('wfs_title', $layerset['Name_or_alias'] ?: ''); #Mapserver8
		# Umlaute umwandeln weil es in einigen Programmen (masterportal und MapSolution) mit Komma und Leerzeichen in wms_group_title zu problemen kommt.
		$layer->metadata->set('wms_group_title', sonderzeichen_umwandeln($layerset['gruppenname']));
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
		$layer->type = $layerset['datentyp'];
		$layer->group = sonderzeichen_umwandeln($layerset['gruppenname']);

		if(value_of($layerset, 'status') != ''){
			$layerset['aktivstatus'] = 0;
		}


		//---- wenn die Layer einer eingeklappten Gruppe nicht in der Karte //
		//---- dargestellt werden sollen, muß hier bei aktivStatus != 1 //
		//---- der layer_status auf 0 gesetzt werden//
		if ($layerset['aktivstatus'] == 0) {
			$layer->status = 0;
		}
		else{
			$layer->status = 1;
		}

		// # fremde Layer werden auf Verbindung getestet
		// if ($layerset['aktivstatus'] != 0 AND $layerset['connectiontype'] == 6) {
		// 	$credentials = $this->pgdatabase->get_credentials($layerset['connection_id']);
		// 	if (!in_array($credentials['host'], array('pgsql', 'localhost'))) {
		// 		$fp = @fsockopen($credentials['host'], $credentials['port'], $errno, $errstr, 5);
		// 		if (!$fp) {			# keine Verbindung --> Layer ausschalten
		// 			$layer->status = 0;
		// 			$layer->metadata->set('queryStatus', 0);
		// 			$this->Fehlermeldung = $errstr.' für Layer: '.$layerset['name'].'<br>';
		// 		}
		// 	}
		// }
		
		if($layerset['aktivstatus'] != 0){
			$collapsed = false;
			if($group = value_of($this->groupset, $layerset['gruppe'])){				# die Gruppe des Layers
				if($group['status'] == 0){
					$this->group_has_active_layers[$layerset['gruppe']] = 1;  	# die zugeklappte Gruppe hat aktive Layer
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
			$processings = explode(";",	replace_params_rolle($layerset['processing']));
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

		if($layerset['datentyp'] == MS_LAYER_POINT AND value_of($layerset, 'cluster_maxdistance') != ''){
			$layer->cluster->maxdistance = $layerset['cluster_maxdistance'];
			$layer->cluster->region = 'ellipse';
		}

		if ($layerset['datentyp']=='3') {
			if($layerset['transparency'] != ''){
				if (MAPSERVERVERSION > 700) {
					$layer->updateFromString("LAYER COMPOSITE OPACITY ".$layerset['transparency']." END END");
				}
				else{
					$layer->opacity = $layerset['transparency'];
				}
			}
			if ($layerset['tileindex']!='') {
				$layer->tileindex = SHAPEPATH.$layerset['tileindex'];
			}
			else {
				$layer->data = $layerset['data'];
			}
			$layer->tileitem = $layerset['tileitem'];
			if ($layerset['offsite']!='') {
				$RGB=explode(' ',$layerset['offsite']);
				$layer->offsite->setRGB($RGB[0],$RGB[1],$RGB[2]);
			}
		}
		else {
			# Vektorlayer
			if ($layerset['data'] != '') {
				if (strpos($layerset['data'], '$SCALE') !== false){
					$this->layers_replace_scale[] =& $layer;
				}
				$layer->data = $layerset['data'];
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
			if ($layerset['filter'] != '') {
				# 2024-07-28 pk Replace all params in Filter
				// $layerset['filter'] = str_replace('$USER_ID', $this->user->id, $layerset['filter']);
				$layerset['filter'] = replace_params_rolle($layerset['filter']);
				if (substr($layerset['filter'], 0, 1) == '(') {
					switch (true) {
						case MAPSERVERVERSION >= 800 : {
							$layer->setProcessingKey('NATIVE_FILTER', $layerset['filter']);
						}break;
						case MAPSERVERVERSION >= 700 : {
							$layer->setProcessing('NATIVE_FILTER=' . $layerset['filter']);
						} break;
						default : {
							$layer->setFilter($layerset['filter']);
						}
					}
			 }
			 else {
				 $expr=buildExpressionString($layerset['filter']);
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
						$layer->opacity = $layerset['transparency'];
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

	function save_legend_role_parameters() {
		# Scrollposition der Legende wird gespeichert
  	$this->user->rolle->setScrollPosition($this->formvars['scrollposition']);
    # Änderungen in den Gruppen werden gesetzt
    $this->formvars = $this->user->rolle->setGroupStatus($this->formvars);
    # Wenn ein Button im Kartenfenster gewählt wurde,
    # werden auch die Einstellungen aus der Legende übernommen
    $this->user->rolle->setAktivLayer($this->formvars,$this->Stelle->id,$this->user->id);
    $this->user->rolle->setQueryStatus($this->formvars);
	}

  function neuLaden() {
		$this->save_legend_role_parameters();
		if(in_array($this->formvars['last_button'], array('zoomin', 'zoomout', 'recentre', 'pquery', 'touchquery', 'ppquery', 'polygonquery')))$this->user->rolle->set_selected_button($this->formvars['last_button']);		// das ist für den Fall, dass ein Button schon angeklickt wurde, aber die Aktion nicht ausgeführt wurde
		if($this->formvars['delete_rollenlayer'] != ''){
			$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
			$mapDB->deleteRollenlayer(NULL, $this->formvars['delete_rollenlayer_type']);
		}
    # Karteninformationen lesen
    $this->loadMap('DataBase');
    # zwischenspeichern des vorherigen Maßstabs
    $oldscale=round($this->map_scaledenom);
		# zoom_to_max_layer_extent
		if($this->formvars['zoom_layer_id'] != '') {
			$this->zoom_to_max_layer_extent($this->formvars['zoom_layer_id']);
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

	function zoomToRefExt() {
    # Zoomen auf den in der Referenzkarte gesetzten Punkt
    # Berechnen der Koordinaten des angeklickten Punktes in der Referencekarte
    $refmapwidthm=($this->reference_map->reference->extent->maxx-$this->reference_map->reference->extent->minx);
    $refmappixsize=$refmapwidthm/$this->reference_map->reference->width;
    $refmapxposm=$this->reference_map->reference->extent->minx+$refmappixsize*$this->formvars['refmap_x'];
    $refmapyposm=$this->reference_map->reference->extent->maxy-$refmappixsize*$this->formvars['refmap_y'];

		$point = new PointObj();
	  $point->setXY($refmapxposm, $refmapyposm);

		if($this->ref['epsg_code'] != $this->user->rolle->epsg_code){
			$projFROM = new projectionObj('init=epsg:' . $this->ref['epsg_code']);
			$projTO = new projectionObj('init=epsg:' . $this->user->rolle->epsg_code);
			$point->project($projFROM, $projTO);
		}

		$halfmapwidthm=($this->map->extent->maxx-$this->map->extent->minx)/2;
    $halfmapheight=($this->map->extent->maxy-$this->map->extent->miny)/2;
    $zoommaxx=$point->x + $halfmapwidthm;
    $zoomminx=$point->x - $halfmapwidthm;
    $zoommaxy=$point->y + $halfmapheight;
    $zoomminy=$point->y - $halfmapheight;

    $this->map->setextent($zoomminx,$zoomminy,$zoommaxx,$zoommaxy);
    $oPixelPos = new PointObj();
    $oPixelPos->setXY($this->map->width/2,$this->map->height/2);
    $this->map->zoompoint(1,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
    $this->saveMap('');
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
        $map->setProjection('+init='.strtolower($this->formvars['post_epsg']));

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
					$layer = new layerObj($map);
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
					if (isset($this->formvars['gast']) AND $this->formvars['gast'] != '') {
						$ows_onlineresource = OWS_SERVICE_ONLINERESOURCE . '&gast=' . $this->formvars['gast'];
					}
					else {
						$ows_onlineresource = OWS_SERVICE_ONLINERESOURCE . '&Stelle_ID=' . $this->Stelle->id .'&login_name=' . value_of($_REQUEST, 'login_name') . '&passwort=' .  urlencode(value_of($_REQUEST, 'passwort'));
					}
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
					$this->ref['refMapImg'] = REFERENCEMAPPATH.$this->ref['dateiname'];
				}
				$reference_map->web->imagepath = IMAGEPATH;
				$reference_map->setProjection('+init=epsg:' . $this->ref['epsg_code']);
				$reference_map->reference->extent->minx = round($this->ref['minx']);
				$reference_map->reference->extent->miny = round($this->ref['miny']);
				$reference_map->reference->extent->maxx = round($this->ref['maxx']);
				$reference_map->reference->extent->maxy = round($this->ref['maxy']);
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
				$r = hexdec(substr(BG_MENUETOP, 1, 2));
        $g = hexdec(substr(BG_MENUETOP, 3, 2));
        $b = hexdec(substr(BG_MENUETOP, 5, 2));
        $map->scalebar->imagecolor->setRGB($r, $g, $b);
				$r = hexdec(substr(TXT_SCALEBAR, 1, 2));
        $g = hexdec(substr(TXT_SCALEBAR, 3, 2));
        $b = hexdec(substr(TXT_SCALEBAR, 5, 2));
        $map->scalebar->color->setRGB($r, $g, $b);
				$map->scalebar->outlinecolor->setRGB($r, $g, $b);
				$map->scalebar->label->color->setRGB($r, $g, $b);
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
					$layerset['layers_of_group'][$layerset['list'][$i]['gruppe']][] = $i;
					if(value_of($layerset['list'][$i], 'legendorder') != ''){
						$layerset['layer_group_has_legendorder'][$layerset['list'][$i]['gruppe']] = true;
					}
					if(value_of($layerset['list'][$i], 'requires') == ''){
						$this->layer_ids_of_group[$layerset['list'][$i]['gruppe']][] = $layerset['list'][$i]['layer_id'];				# die Layer-IDs in einer Gruppe
					}
					$this->layer_id_string .= $layerset['list'][$i]['layer_id'].'|';							# alle Layer-IDs hintereinander in einem String

					if (value_of($layerset['list'][$i], 'requires') != '') {
						$layerset['list'][$i]['aktivstatus'] = $layerset['layer_ids'][$layerset['list'][$i]['requires']]['aktivstatus'];
						$layerset['list'][$i]['showclasses'] = $layerset['layer_ids'][$layerset['list'][$i]['requires']]['showclasses'];
					}

					if ($this->class_load_level == 2 OR ($this->class_load_level == 1 AND $layerset['list'][$i]['aktivstatus'] != 0)) {
						# nur wenn der Layer aktiv ist, sollen seine Parameter gesetzt werden
						$layerset['list'][$i]['layer_index_mapobject'] = $map->numlayers;

						$this->loadlayer($map, $layerset['list'][$i], $strict_layer_name);
						$error = msGetErrorObj();
						$test = 0;
						while ($test < 100 AND $error && $error->code != MS_NOERR) {
							$this->error_message .= '<br>Fehler beim Laden des Layers mit der Layer-ID: ' . $layerset['list'][$i]['layer_id'] . 
							'<br>&nbsp;&nbsp;in der Routine ' . $error->routine . ' Msg="' . $error->message . '" code=' . $error->code;
							$error = $error->next();
							$test++;
						}
						msResetErrorList();
					}
				}
				if ($this->error_message != '') {
					#$this->error_message .= '<br>';
					#throw new ErrorException($this->error_message);
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
		$anzClass = count_or_0($classset);
    for ($j = 0; $j < $anzClass; $j++) {
      $klasse = new ClassObj($layer);
      if ($classset[$j]['name']!='') {
        $klasse->name = $classset[$j]['name'];
      }
      if ($classset[$j]['status']=='1'){
      	$klasse->status = MS_ON;
      }
      else {
      	$klasse->status = MS_OFF;
      }
			if (value_of($layerset, 'template') != '') {
				$klasse->template = value_of($layerset, 'template');
			}
			$klasse->setexpression(str_replace([chr(10), chr(13)], '', $classset[$j]['expression']));
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
				if ($layerset['datentyp'] == 0 AND value_of($layerset, 'buffer') != NULL AND value_of($layerset, 'buffer') != 0) {
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
					if ($layerset['datentyp'] == 8) {
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
					if (MAPSERVERVERSION >= 800) {
						$style->updateFromString("STYLE OPACITY " . $dbStyle['opacity'] . " END");
					}
					else {
						$style->opacity = $dbStyle['opacity'];
					}
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
				$label->align = $dbLabel['align'];
				$RGB = explode(" ",$dbLabel['color']);
				if ($RGB[0] == '') { 
					$RGB[0]=0; $RGB[1]=0; $RGB[2]=0; 
				}
				if (is_numeric($RGB[0])) {
					$label->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
				}
				else {
					$label->updateFromString("LABEL COLOR [" . $dbLabel['color']."] END");
				}
				if($dbLabel['outlinecolor'] != ''){
					$RGB=explode(" ",$dbLabel['outlinecolor']);
					if (is_numeric($RGB[0])) {
						$label->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
					}
					else {
						$label->updateFromString("LABEL OUTLINECOLOR [" . $dbLabel['outlinecolor']."] END");
					}
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
					if (is_numeric($RGB[0])) {
						$style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
					}
					else {
						$style->updateFromString("STYLE COLOR [" . $dbLabel['backgroundcolor']."] END");
					}
					if ($dbLabel['buffer']!='') {
						if (is_numeric($RGB[0])) {
							$style->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
						}
						else {
							$style->updateFromString("STYLE OUTLINECOLOR [" . $dbLabel['backgroundcolor']."] END");
						}
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
				if (is_numeric($dbLabel['size'])) {
					$label->size = $dbLabel['size'];
					$label->minsize = $dbLabel['minsize'];
					$label->maxsize = $dbLabel['maxsize'];
				}
				else {
					$label->updateFromString("LABEL SIZE [" . $dbLabel['size']."] END");
				}
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

  function navMap($cmd) {
		switch ($cmd) {
      case "previous" : {
#        $this->user->rolle->set_selected_button('previous');
        $this->setPrevMapExtent($this->user->rolle->last_time_id);
      } break;
      case "next" : {
#        $this->user->rolle->set_selected_button('next');
        $this->setNextMapExtent($this->user->rolle->last_time_id);
      } break;
      case "zoomin" : {
        $this->user->rolle->set_selected_button('zoomin');
        $this->zoomMap($this->user->rolle->nZoomFactor);
      } break;
			case "zoomin_wheel" : {
        $this->zoomMap($this->user->rolle->nZoomFactor);
      } break;
      case "zoomout" : {
        $this->user->rolle->set_selected_button('zoomout');
        $this->zoomMap($this->user->rolle->nZoomFactor*-1);
      } break;
      case "recentre" : {
        $this->user->rolle->set_selected_button('recentre');
        $this->zoomMap(1);
      } break;
      // case "jump_coords" : {
        // $this->user->rolle->set_selected_button('recentre');
        // $this->zoomMap(1);
      // } break;
      case "pquery" : {
        $this->user->rolle->set_selected_button('pquery');
        $this->queryMap();
      } break;
      case "touchquery" : {
        $this->user->rolle->set_selected_button('touchquery');
        $this->queryMap();
      } break;
      case "ppquery" : {
        $this->user->rolle->set_selected_button('ppquery');
        $this->queryMap();
      } break;
      case "polygonquery" : {
        $this->user->rolle->set_selected_button('polygonquery');
        $this->queryMap();
      } break;
      case "Full_Extent" : {
        $this->user->rolle->set_selected_button('zoomin');   # um anschliessend wieder neu zoomen zu koennen!
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
    $currentextent = rectObj($this->map->extent->minx, $this->map->extent->miny, $this->map->extent->maxx, $this->map->extent->maxy);
    $prevextent = rectObj($this->map->extent->minx, $this->map->extent->miny, $this->map->extent->maxx, $this->map->extent->maxy);
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
						$prevextent = rectObj($ret[1]['minx'],$ret[1]['miny'],$ret[1]['maxx'],$ret[1]['maxy']);
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
    $currentextent = rectObj($this->map->extent->minx, $this->map->extent->miny, $this->map->extent->maxx, $this->map->extent->maxy);
    $nextextent = rectObj($this->map->extent->minx, $this->map->extent->miny, $this->map->extent->maxx, $this->map->extent->maxy);
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
        $nextextent = rectObj($ret[1]['minx'],$ret[1]['miny'],$ret[1]['maxx'],$ret[1]['maxy']);
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
      $oPixelPos = new PointObj();
			$oPixelPos->setXY($minx,$maxy);
			$this->map->zoompoint($nZoomFactor,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
    }
    else {
      # Zoomen auf ein Rechteck
      $this->debug->write('<br>Es wird auf ein Rechteck gezoomt',4);
      if ($minx != 'undefined' AND $miny != 'undefined' AND $maxx != 'undefined' AND $maxy != 'undefined') {
				$oPixelExt = rectObj($minx,$miny,$maxx,$maxy, MS_TRUE);
				if (MAPSERVERVERSION >= 800) {
					$this->map->zoomrectangle($oPixelExt, $this->map->width, $this->map->height, $this->map->extent, $this->Stelle->MaxGeorefExt);
				}
				else {
					$this->map->zoomrectangle($oPixelExt,$this->map->width,$this->map->height,$this->map->extent);
					# Nochmal Zoomen auf die Mitte mit Faktor 1, damit der Ausschnitt in den erlaubten Bereich
					# verschoben wird, falls er ausserhalb liegt, zoompoint berücksichtigt das, zoomrectangle nicht.
					$oPixelPos = new PointObj();
					$oPixelPos->setXY($this->map->width/2,$this->map->height/2);
					$this->map->zoompoint(1,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
				}
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
	
	function drawMap($img_urls = false) {
		if($this->main == 'map.php' AND $this->user->rolle->epsg_code != 4326 AND MINSCALE != '' AND $this->map_factor == '' AND $this->map_scaledenom < MINSCALE){			
      $this->scaleMap(MINSCALE);
			$this->saveMap('');
    }
		# Parameter $scale in Data ersetzen
		for($i = 0; $i < count($this->layers_replace_scale); $i++){
			$this->layers_replace_scale[$i]->data = str_replace('$SCALE', $this->map_scaledenom, $this->layers_replace_scale[$i]->data);
		}

		try {
			$f = @fopen(dirname($this->debug->filename) . '/mapserver' . $this->user->id . '.log', 'r+');
			if ($f !== false) {
					ftruncate($f, 0);
					fclose($f);
			}
    	$this->image_map = $this->map->draw();
			# sorgt dafür, dass die mapserver' . $this->user->id . '.log geschrieben und abgeschlossen wird
			# unter Mapserver 7 wird durch drawmap() kein Fehler geschmissen, deswegen steht diese Zeile hier auch nochmal
			$this->map->setConfigOption('MS_ERRORFILE', LOGPATH . 'mapserver.log');
		}
		catch (Exception $ex) {
			# sorgt dafür, dass die mapserver' . $this->user->id . '.log geschrieben und abgeschlossen wird
			$this->map->setConfigOption('MS_ERRORFILE', LOGPATH . 'mapserver.log');
			global $errors;
			$errors[] = file_get_contents(dirname($this->debug->filename) . '/mapserver' . $this->user->id . '.log');
			throw new Exception('Fehler beim Erzeugen des Kartenbildes');
		}

		if (!$img_urls) {
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
					$this->layerhiddenstring .= $layer['layer_id'].' '.$layerhiddenflag.' ';
				}
			}
		}

		# Erstellen des Maßstabes
		$this->map_scaledenom = $this->map->scaledenom;
		if ($this->user->rolle->epsg_code == 4326) {
			$center_y = ($this->user->rolle->oGeorefExt->maxy + $this->user->rolle->oGeorefExt->miny) / 2;
			$this->map_scaledenom = degree2meter($center_y) * $this->map_scaledenom;
		}
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
			$this->img['scalebar'] = $img_scalebar->saveImage(IMAGEPATH.$filename);
			$this->img['scalebar'] = IMAGEURL.$filename;
		}
		$this->calculatePixelSize();
		$this->drawReferenceMap();
  }

  function scaleMap($nScale) {
    $oPixelPos = new PointObj();
    $oPixelPos->setXY($this->map->width/2,$this->map->height/2);
		if ($this->user->rolle->epsg_code == 4326) {
			$center_y = ($this->user->rolle->oGeorefExt->maxy + $this->user->rolle->oGeorefExt->miny) / 2;
			$nScale = $nScale / degree2meter($center_y);
		}
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
		if ($this->map_scaledenom > $this->scaleUnitSwitchScale) $this->map->scalebar->units = MS_KILOMETERS;
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
				$projFROM = new projectionObj('init=epsg:' . $this->user->rolle->epsg_code);
				$projTO = new projectionObj('init=epsg:' . $this->ref['epsg_code']);
				$this->reference_map->extent->project($projFROM, $projTO);
			}
      $img_refmap = $this->reference_map->drawReferenceMap();
			if (MAPSERVERVERSION >= 800) {
				$image = $img_refmap->getBytes();
			}
			else {
				ob_start();
				$img_refmap->saveImage();
				$image = ob_get_clean();
			}
      $this->img['referenzkarte'] = 'data:image/jpg;base64,'.base64_encode($image);
      $this->Lagebezeichung=$this->getLagebezeichnung($this->user->rolle->epsg_code);
    }
	}

	function getLagebezeichnung($epsgcode) {
		global $GUI;
		if (defined('LAGEBEZEICHNUNGSART')) {
			switch (LAGEBEZEICHNUNGSART) {
				case 'Flurbezeichnung' : {
					include_once(PLUGINS.'alkis/model/kvwmap.php');
					$Lagebezeichnung = $GUI->getFlurbezeichnung($epsgcode);
				} break;
				default : {
					$Lagebezeichnung = '';
				}
			}
		}
		else {
			$Lagebezeichnung = '';
		}
		return $Lagebezeichnung;
	}

  function getFlurbezeichnung($epsgcode) {
    $Flurbezeichnung = [];
 	  $flur = new Flur('','','',$this->pgdatabase);
		$bildmitte['rw']=($this->map->extent->maxx+$this->map->extent->minx)/2;
		$bildmitte['hw']=($this->map->extent->maxy+$this->map->extent->miny)/2;
		$ret=$flur->getBezeichnungFromPosition($bildmitte, $epsgcode);
		if ($ret[0]) {
		}
		else {
			if (is_array($ret[1]) AND $ret[1]['flur'] != '') {
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
		GUI::add_message_($type, $msg);
	}

	public static function add_message_($type, $msg) {
		if (is_array($msg) AND array_key_exists('success', $msg) AND is_array($msg)) {
			$type = 'notice';
			$msg = $msg['msg'];
		}
		if ($type == 'array' or is_array($msg)) {
			foreach($msg AS $m) {
				GUI::add_message($m['type'], $m['msg']);
			}
		}
		else {
			GUI::$messages[] = array(
				'type' => $type,
				'msg' => $msg
			);
		}
	}

	function output_messages($option = 'with_script_tags') {		
		$html = "message(" . json_encode(GUI::$messages) . ");";
		if ($option == 'with_script_tags') {
			$html = "<script type=\"text/javascript\">" . $html . "</script>";
		}
		echo $html;
		GUI::$messages = array();
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
				if ($this->formvars['window_type'] == 'overlay') {
					$this->overlaymain = $this->main;
					include (LAYOUTPATH.'snippets/overlay.php');
				}
				else {
					$guifile = $this->get_guifile();
					$this->debug->write("<br>Include <b>" . $guifile . "</b> in kvwmap.php function output()",4);
					include($guifile);
				}
				if($this->alert != ''){
					echo '<script type="text/javascript">alert("'.$this->alert.'");</script>';			# manchmal machen alert-Ausgaben über die allgemeinde Funktioen showAlert Probleme, deswegen am besten erst hier am Ende ausgeben
				}
				if (!empty(GUI::$messages)) {
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
		if ($login_name != '') array_push($where, "login_name = '" . pg_escape_string($login_name) . "'");
		if ($password != '') array_push($where, "password = kvwmap.sha1('" . pg_escape_string($password) . "')");
		if (!$archived) array_push($where, "archived IS NULL");
		$sql = "
			SELECT
				*
			FROM
				kvwmap.user
			WHERE
				" . implode(" AND ", $where);
		#echo '<br>SQL to read user data: ' . $sql;

		$this->debug->write("<p>file:users.php class:user->readUserDaten - Abfragen des Namens des Benutzers:<br>", 3);
		$ret = $this->database->execSQL($sql, 4, 0, true);
		if(!$ret[0]) {
      $rs = pg_fetch_assoc($ret[1]);
    }
		$this->id = $rs['id'];
		$this->login_name = $rs['login_name'];
		$this->Namenszusatz = $rs['namenszusatz'];
		$this->Name = $rs['name'];
		$this->Vorname = $rs['vorname'];
		$this->stelle_id = $rs['stelle_id'];
		$this->phon = $rs['phon'];
		$this->email = $rs['email'];
		$this->organisation = $rs['organisation'];
		$this->position = $rs['position'];
		if (CHECK_CLIENT_IP) {
			$this->ips = $rs['ips'];
		}
		$this->funktion = $rs['Funktion'];
		$this->debug->user_funktion = $this->funktion;
		$this->password_setting_time = $rs['password_setting_time'];
		$this->password_expired = $rs['password_expired'] === 't';
		$this->userdata_checking_time = $rs['userdata_checking_time'];
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

	function getLastStelle() {
		$sql = "
			SELECT
				stelle_id
			FROM
				kvwmap.user
			WHERE
				id= " . $this->id ."
		";
		$this->debug->write("<p>file:users.php class:user->getLastStelle - Abfragen der zuletzt genutzten Stelle:<br>" . $sql, 4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>', 4); return 0; }
		$rs = pg_fetch_array($ret[1]);
		return $rs['stelle_id'];
	}

	function StellenZugriff($stelle_id) {
		$this->Stellen=$this->getStellen($stelle_id);
		if (count($this->Stellen['ID'])>0) {
			return 1;
		}
		return 0;
	}

	function getStellen($stelle_ID, $with_expired = false) {
		global $language;
		if ($language != '' AND $language != 'german') {
			$name_column = "
			CASE
				WHEN s.bezeichnung_" . $language . " != \"\" THEN s.bezeichnung_" . $language . "
				ELSE s.bezeichnung
			END AS bezeichnung";
		}
		else {
			$name_column = "s.bezeichnung";
		}
		$sql = "
			SELECT
				s.id,
				" . $name_column . "
			FROM
				kvwmap.stelle AS s,
				kvwmap.rolle AS r
			WHERE
				s.id = r.stelle_id AND
				r.user_id = " . $this->id .
				($stelle_ID > 0 ? " AND s.id = " . $stelle_ID : "") . 
				(!$with_expired ? "
				AND (
					('" . date('Y-m-d h:i:s') . "' >= s.start AND ('" . date('Y-m-d h:i:s') . "' <= s.stop OR s.stop IS NULL))
					OR
					(s.start IS NULL AND s.stop IS NULL)
				)" : "") . "
			ORDER BY
				bezeichnung;
		";

		#debug_write('<br>sql: ', $sql, 1);
		$this->debug->write("<p>file:users.php class:user->getStellen - Abfragen der Stellen die der User einnehmen darf:", 4);
		$ret = $this->database->execSQL($sql, 4, 0, true);
		while ($rs = pg_fetch_assoc($ret[1])) {
			$stellen['ID'][]=$rs['id'];
			$stellen['Bezeichnung'][]=$rs['bezeichnung'];
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

	function updateStelleID($stelle_id) {
		# sezten der aktuell für den Nutzer eingestellten Stelle
		$sql = "
			UPDATE
				kvwmap.user
			SET
				stelle_id = " . $stelle_id . "
			WHERE
				id = " . $this->id . "
		";
		$this->debug->write("<p>file:users.php class:user->updateStelleID - Setzen der aktuellen Stellen-ID für den User<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>', 4); return 0; }
		$this->debug->write('Stelle gewechselt, neue Stellen_ID: ' . $stelle_id, 4);
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
		global $log_postgres;
		$this->debug = $debug;
		$this->log = $log_postgres;
		$this->id = $id;
		$this->database = $database;
		$ret = $this->readDefaultValues();
	}

	function readDefaultValues() {
		global $language;
		if ($language != '' AND $language != 'german') {
			$name_column = "
			CASE
				WHEN s.bezeichnung_" . $language . " != \"\" THEN s.bezeichnung_" . $language . "
				ELSE s.bezeichnung
			END AS bezeichnung";
		}
		else {
			$name_column = "s.bezeichnung";
		}

		$sql = "
			SELECT
				id," .
				$name_column . ",
				start,
				stop, minxmax, minymax, maxxmax, maxymax, epsg_code, referenzkarte_id, authentifizierung, alb_status, wappen, wappen_link
				ows_namespace,
				ows_title,
				wms_accessconstraints,
				ows_abstract,
				ows_updatesequence,
				ows_geographicdescription,
				ows_fees,
				ows_srs,

				ows_contactorganization,
				ows_contactaddress,
				ows_contactpostalcode,
				ows_contactcity,
				ows_contactadministrativearea,
				ows_contactemailaddress,
				ows_contactperson,
				ows_contactposition,
				ows_contactvoicephone,
				ows_contactfacsimile,

				ows_distributionorganization,
				ows_distributionaddress,
				ows_distributionpostalcode,
				ows_distributioncity,
				ows_distributionadministrativearea,
				ows_distributionemailaddress,
				ows_distributionperson,
				ows_distributionposition,
				ows_distributionvoicephone,
				ows_distributionfacsimile,

				ows_contentorganization,
				ows_contentaddress,
				ows_contentpostalcode,
				ows_contentcity,
				ows_contentadministrativearea,
				ows_contentemailaddress,
				ows_contentperson,
				ows_contentposition,
				ows_contentvoicephone,
				ows_contentfacsimile,

				protected, check_client_ip, check_password_age, allowed_password_age, use_layer_aliases, selectable_layer_params, hist_timestamp, default_user_id,
				style,
				show_shared_layers,
				reset_password_text,
				invitation_text
			FROM
				kvwmap.stelle s
			WHERE
				ID = " . $this->id . "
		";
		#echo 'SQL zum Abfragen der Stelle: ' . $sql;
		$this->debug->write('<p>file:stelle.php class:stelle->readDefaultValues - Abfragen der Default Parameter der Karte zur Stelle:<br>', 4);
		$ret = $this->database->execSQL($sql, 4, 0, true);
		if(!$ret[0]) {
      $rs = pg_fetch_assoc($ret[1]);
    }
		$this->data = $rs;
		$this->Bezeichnung = $rs['bezeichnung'];
		$this->MaxGeorefExt = rectObj($rs['minxmax'], $rs['minymax'], $rs['maxxmax'], $rs['maxymax']);
		$this->epsg_code = $rs['epsg_code'];
		$this->protected = $rs['protected'];
		//---------- OWS Metadaten ----------//
		$this->ows_title = $rs['ows_title'];
		$this->ows_abstract = $rs['ows_abstract'];
		$this->ows_namespace = $rs['ows_namespace'];
		$this->ows_updatesequence = $rs['ows_updatesequence'];
		$this->ows_geographicdescription = $rs['ows_geographicdescription'];
		$this->ows_fees = $rs['ows_fees'];
		$this->ows_srs = preg_replace(array('/: +/', '/ +:/'), ':', $rs['ows_srs']);

		$this->ows_contactorganization = $rs['ows_contactorganization'];
		$this->ows_contactaddress = $rs['ows_contactaddress'];
		$this->ows_contactpostalcode = $rs['ows_contactpostalcode'];
		$this->ows_contactcity = $rs['ows_contactcity'];
		$this->ows_contactadministrativearea = $rs['ows_contactadministrativearea'];
		$this->ows_contactemailaddress = $rs['ows_contactemailaddress'];
		$this->ows_contactperson = $rs['ows_contactperson'];
		$this->ows_contactposition = $rs['ows_contactposition'];
		$this->ows_contactvoicephone = $rs['ows_contactvoicephone'];
		$this->ows_contactfacsimile = $rs['ows_contactfacsimile'];

		$this->ows_distributionorganization = $rs['ows_distributionorganization'];
		$this->ows_distributionaddress = $rs['ows_distributionaddress'];
		$this->ows_distributionpostalcode = $rs['ows_distributionpostalcode'];
		$this->ows_distributioncity = $rs['ows_distributioncity'];
		$this->ows_distributionadministrativearea = $rs['ows_distributionadministrativearea'];
		$this->ows_distributionemailaddress = $rs['ows_distributionemailaddress'];
		$this->ows_distributionperson = $rs['ows_distributionperson'];
		$this->ows_distributionposition = $rs['ows_distributionposition'];
		$this->ows_distributionvoicephone = $rs['ows_distributionvoicephone'];
		$this->ows_distributionfacsimile = $rs['ows_distributionfacsimile'];

		$this->ows_contentorganization = $rs['ows_contentorganization'];
		$this->ows_contentaddress = $rs['ows_contentaddress'];
		$this->ows_contentpostalcode = $rs['ows_contentpostalcode'];
		$this->ows_contentcity = $rs['ows_contentcity'];
		$this->ows_contentadministrativearea = $rs['ows_contentadministrativearea'];
		$this->ows_contentemailaddress = $rs['ows_contentemailaddress'];
		$this->ows_contentperson = $rs['ows_contentperson'];
		$this->ows_contentposition = $rs['ows_contentposition'];
		$this->ows_contentvoicephone = $rs['ows_contentvoicephone'];
		$this->ows_contentfacsimile = $rs['ows_contentfacsimile'];

		$this->wms_accessconstraints = $rs['wms_accessconstraints'];
		$this->check_client_ip = $rs['check_client_ip'];
		$this->checkPasswordAge = $rs['check_password_age'];
		$this->allowedPasswordAge = $rs['allowed_password_age'];
		$this->useLayerAliases = $rs['use_layer_aliases'];
		$this->selectable_layer_params = $rs['selectable_layer_params'];
		$this->hist_timestamp = $rs['hist_timestamp'];
		$this->default_user_id = $rs['default_user_id'];
		$this->show_shared_layers = $rs['show_shared_layers'];
		$this->style = $rs['style'];
		$this->reset_password_text = $rs['reset_password_text'];
		$this->invitation_text = $rs['invitation_text'];
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
	
	function setSize($mapsize) {
		# setzen der Werte, die aktuell für die Nutzung der Stelle durch den Nutzer gelten sollen.
		$teil = explode('x',$mapsize);
		$nImageWidth = $teil[0];
		$nImageHeight = $teil[1];
		$sql = "
			UPDATE
				kvwmap.rolle
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
		if ($user_id == NULL) {
			# man kann auch eine user_id übergeben um den Kartenausschnitt eines anderen Users abzufragen
			$user_id = $this->user_id;
		}
    $sql = "
			SELECT 
				* 
			FROM 
				kvwmap.u_consume
			WHERE 
				user_id = " . $user_id ." AND 
				stelle_id = " . $this->stelle_id . " AND 
				time_id = " . ($consumetime != ''? "'" . $consumetime . "'" : 'NULL');
    #echo '<br>'.$sql;
    $queryret = $this->database->execSQL($sql, 4, 0, true);
    if ($queryret[0]) {
      # Fehler bei Datenbankanfrage
      $ret[0]=1;
      $ret[1]='<br>Fehler bei der Abfrage der letzten Zugriffszeit.<br>'.$ret[1];
    }
    else {
			$rs = pg_fetch_assoc($queryret[1]);
      $ret[0]=0;
      $ret[1]=$rs;
    }
    return $ret;
  }
	
	function updateNextConsumeTime($time_id,$nexttime) {
		$sql = "
			UPDATE
				kvwmap.u_consume
			SET
				next = '" . $nexttime . "'
			WHERE
				time_id = '" . $time_id . "'
		";
		#echo '<br>'.$sql;
		$queryret = $this->database->execSQL($sql, 4, 1);
		if ($queryret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[0] = 1;
			$ret[1] = '<br>Fehler bei der Aktualisierung des Zeitstempels des Nachfolgers Next.<br>' . $ret[1];
		}
		else {
			$ret[0] = 0;
			$ret[1] = 1;
		}
		return $ret;
	}

	function updatePrevConsumeTime($time_id, $prevtime) {
		$sql = "
			UPDATE
				kvwmap.u_consume
			SET
				prev = '" . $prevtime . "'
			WHERE
				time_id = '" . $time_id . "'
		";
		#echo '<br>' . $sql;
		$queryret=$this->database->execSQL($sql,4, 1);
		if ($queryret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[0] = 1;
			$ret[1] = '<br>Fehler bei der Aktualisierung des Zeitstempels des Vorgängers Prev.<br>' . $ret[1];
		}
		else {
			$ret[0] = 0;
			$ret[1] = 1;
		}
		return $ret;
	}

	function setScrollPosition($scrollposition){
		if($scrollposition != ''){
			$sql = '
				UPDATE 
					kvwmap.rolle 
				SET 
					scrollposition = ' . $scrollposition . '
				WHERE 
					user_id = ' . $this->user_id . ' AND 
					stelle_id = ' . $this->stelle_id;
			$this->debug->write("<p>file:rolle.php class:rolle->setOneLayer - Setzen eines Layers:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
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
						kvwmap.u_groups2rolle
					SET
						status = '" . $group_status . "'
					WHERE
						user_id = " . $this->user_id . " AND
						stelle_id = " . $this->stelle_id . " AND
						id = " . $this->groupset[$i]['id'] . "
				";
				#echo '<br>Sql: ' . $sql;
				$this->debug->write("<p>file:rolle.php class:rolle->setGroupStatus - Speichern des Status der Gruppen zur Rolle:", 4);
				$this->database->execSQL($sql, 4, $this->loglevel);
			}
		}
		return $formvars;
	}

  function getGroups($GroupName) {
    # Abfragen der Gruppen in der Rolle
    $sql = '
			SELECT 
				g2r.*, ' .
				(rolle::$language != 'german'? 'CASE WHEN gruppenname_' . rolle::$language . ' != "" THEN gruppenname_' . rolle::$language . ' ELSE gruppenname END AS ' : '') . '
				gruppenname 
			FROM 
				kvwmap.u_groups AS g, 
				kvwmap.u_groups2rolle AS g2r 
			WHERE 
				g2r.stelle_id = ' . $this->stelle_id . ' AND 
				g2r.user_id = '.$this->user_id . ' AND 
				g2r.id = g.id';
    if ($GroupName != '') {
      $sql.=' AND gruppenname = "' . $GroupName . '"';
    }
    $this->debug->write("<p>file:rolle.php class:rolle->getGroups - Abfragen der Gruppen zur Rolle:<br>".$sql,4);
    $ret = $this->database->execSQL($sql);
    while ($rs = pg_fetch_assoc($ret[1])) {
      $groups[]=$rs;
    }
    return $groups;
  }

	function setQueryStatus($formvars) {
		# Eintragen des query_status=1 für Layer, die für die Abfrage selektiert wurden
		foreach ($formvars['qLayer'] as $layer_id => $query_status) {
			if ($layer_id != '' AND $query_status !== '') {	
				$table = ($layer_id > 0 ? 'u_rolle2used_layer' : 'rollenlayer');
				$id = ($layer_id > 0 ? 'layer_id' : 'id');
				$sql ='
					UPDATE 
						kvwmap.' . $table . ' 
					SET 
						querystatus=' . $query_status . '
					WHERE 
						user_id = ' . $this->user_id . ' AND 
						stelle_id = ' . $this->stelle_id . ' AND 
						' . $id . ' = ' . abs($layer_id);
				$this->debug->write("<p>file:rolle.php class:rolle->setQueryStatus - Speichern des Abfragestatus der Layer zur Rolle:",4);
				$this->database->execSQL($sql,4, $this->loglevel);
			}
		}
		return 1;
	}

	function setAktivLayer($formvars, $stelle_id, $user_id, $ignore_rollenlayer = false) {
		$this->layerset = $this->getLayer('');
		if (!$ignore_rollenlayer AND $rollenlayer = $this->getRollenLayer('', NULL)) {
			$this->layerset['layer_ids'] = $this->layerset['layer_ids'] + $rollenlayer['layer_ids'];
			$this->layerset = $this->layerset + $rollenlayer;
		}
		# Eintragen des Status der Layer, 1 angezeigt oder 0 nicht.
		foreach ($formvars['thema'] as $layer_id => $aktiv_status) {
			$layer = $this->layerset['layer_ids'][$layer_id];
			$requires_status = value_of($formvars, 'thema[' . value_of($layer, 'requires') . '');
			if ($aktiv_status !== '' OR $requires_status !== '') { // entweder ist der Layer selber an oder sein requires-Layer
				$aktiv_status = (int)$aktiv_status + (int)$requires_status;
				if ($layer['layer_id'] > 0) {
					$sql ="
						UPDATE
							kvwmap.u_rolle2used_layer
						SET
							aktivstatus = " . $aktiv_status . "
						WHERE
							user_id = " . $this->user_id . " AND
							stelle_id = " . $this->stelle_id . " AND
							layer_id = " . $layer['layer_id'] . "
					";
					$this->debug->write("<p>file:rolle.php class:rolle->setAktivLayer - Speichern der aktiven Layer zur Rolle:",4);
					$this->database->execSQL($sql,4, $this->loglevel);
				}
				else { # Rollenlayer
					$sql  = "
						UPDATE
							kvwmap.rollenlayer
						SET
							aktivstatus = '" . $aktiv_status . "'
						WHERE
							user_id = " . $this->user_id . " AND
							stelle_id = " . $this->stelle_id . " AND
							id = " . abs($layer['layer_id']) . "
					";
					$this->debug->write("<p>file:rolle.php class:rolle->setAktivLayer - Speichern der aktiven Layer zur Rolle:",4);
					$this->database->execSQL($sql,4, $this->loglevel);
				}
			}
		}
		foreach ($formvars['group_checkbox'] as $group_id => $aktiv_status) {
			$sql = "
				UPDATE
					kvwmap.u_rolle2used_layer
				SET
					aktivstatus = " . $aktiv_status . "
					" . ($aktiv_status == 0 ? ',querystatus = 0' : '') . "
				WHERE
					user_id = " . $this->user_id . " AND
					stelle_id = " . $this->stelle_id . " AND
					layer_id IN (
						WITH RECURSIVE cte (group_id) AS (
							SELECT 
								" .  $group_id . "
							UNION ALL
							SELECT 
								u_groups.id
							FROM 
								cte,
								kvwmap.u_groups
							WHERE 
								cte.group_id = u_groups.obergruppe AND 
								obergruppe IS NOT NULL
						)
						SELECT DISTINCT 
							layer.layer_id
						FROM 
							cte,
							kvwmap.layer
						WHERE
							gruppe = cte.group_id
					)
			";
			$this->debug->write("<p>file:rolle.php class:rolle->setAktivLayer - Speichern der aktiven Layer zur Rolle:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
		}
		foreach ($formvars['class'] as $class_id => $class_status) {
			if ($class_status == '0' OR $class_status == '2'){
				$sql2 = '
					INSERT INTO kvwmap.u_rolle2used_class 
						(user_id, stelle_id, class_id, status) 
					VALUES 
						('.$this->user_id.', '.$this->stelle_id.', ' . $class_id . ', ' . $class_status . ')
					ON CONFLICT (user_id, stelle_id, class_id) DO 
						UPDATE SET
							status = excluded.status
					;';
				$this->database->execSQL($sql2,4, $this->loglevel);
			}
			elseif ($class_status == '1') {
				$sql1 = "
					DELETE FROM
						kvwmap.u_rolle2used_class
					WHERE
						user_id = " . $this->user_id . " AND
						stelle_id = " . $this->stelle_id . " AND
						class_id = " . $class_id . "
				";
				$this->database->execSQL($sql1,4, $this->loglevel);
			}
		}
		return 1;
	}

	function getLayer($LayerName, $only_active = false, $replace_params = true) {
		$layer = [];
		$layer_name_filter = '';
		$privilegfk = '';

		# Abfragen der Layer in der Rolle
		if (rolle::$language != 'german') {
			$name_column = "
			CASE
				WHEN l.name_" . rolle::$language . " != \"\" THEN l.name_" . rolle::$language . "
				ELSE l.name
			END AS name";
		} else {
			$name_column = "l.name";
		}

		if ($LayerName != '') {
			if (is_numeric($LayerName)) {
				$layer_name_filter .= " AND l.layer_id = " . $LayerName;
			} else {
				$layer_name_filter = " AND (l.name LIKE '" . $LayerName . "' OR l.alias LIKE '" . $LayerName . "')";
			}
			$privilegfk = ",
				(
					SELECT
						max(las.privileg)
					FROM
						kvwmap.layer_attributes AS la,
						kvwmap.layer_attributes2stelle AS las
					WHERE
						la.layer_id = ul.layer_id AND
						form_element_type = 'SubformFK' AND
						las.stelle_id = ul.stelle_id AND
						ul.layer_id = las.layer_id AND
						las.attributename = split_part(split_part(la.options, ';', 1) , ',', -1)
				) as privilegfk";
		}

		if ($only_active) {
			$active_filter = " AND (r2ul.aktivstatus = 1)";
		}
		else {
			$active_filter = '';
		}

		$sql = "
			SELECT " .
			$name_column . ",
				l.layer_id,
				l.alias, datentyp, COALESCE(ul.group_id, gruppe) AS Gruppe, pfad, maintable, oid, identifier_text, maintable_is_view, data, tileindex, l.schema, max_query_rows, document_path, document_url, classification, ddl_attribute, 
				CASE 
					WHEN connectiontype = 6 THEN concat('host=', c.host, ' port=', c.port, ' dbname=', c.dbname, ' user=', c.user, ' password=', c.password, ' application_name=kvwmap_user_', r2ul.user_id)
					ELSE l.connection 
				END as connection, 
				printconnection, classitem, connectiontype, epsg_code, tolerance, toleranceunits, sizeunits, wms_name, wms_auth_username, wms_auth_password, wms_server_version, ows_srs,
				wfs_geom,
				write_mapserver_templates,
				selectiontype, querymap, processing, kurzbeschreibung, dataowner_name, dataowner_email, dataowner_tel, uptodateness, updatecycle, metalink, terms_of_use_link, status, trigger_function, version,
				ul.queryable,
				l.drawingorder,
				l.legendorder,
				ul.minscale,
				ul.maxscale,
				ul.offsite,
				coalesce(r2ul.transparency, ul.transparency, 100) as transparency,
				coalesce(r2ul.labelitem, l.labelitem) as labelitem,
				l.labelitem as original_labelitem,
				l.duplicate_from_layer_id,
				l.duplicate_criterion,
				l.shared_from,
				l.geom_column,
				ul.postlabelcache,
				filter,
				r2ul.gle_view,
				ul.template,
				header,
				footer,
				ul.symbolscale,
				ul.requires,
				ul.privileg,
				ul.export_privileg,
				start_aktiv,
				r2ul.showclasses,
				r2ul.rollenfilter,
				r2ul.geom_from_layer 
				" . $privilegfk . "
				" . ($this->gui_object->plugin_loaded('mobile') ? ', l.sync' : '') . "
				" . ($this->gui_object->plugin_loaded('mobile') ? ', l.vector_tile_url' : '') . "
				" . ($this->gui_object->plugin_loaded('portal') ? ', l.cluster_option' : '') . "
			FROM
				kvwmap.layer AS l JOIN
				kvwmap.used_layer AS ul ON l.layer_id = ul.layer_id JOIN
				kvwmap.u_rolle2used_layer as r2ul ON r2ul.stelle_id = ul.stelle_id AND r2ul.layer_id = ul.layer_id LEFT JOIN
				kvwmap.connections as c ON l.connection_id = c.id
			WHERE
				ul.stelle_id = " . $this->stelle_id . " AND
				r2ul.user_id = " . $this->user_id .
			$layer_name_filter . 
			$active_filter . "
			ORDER BY
				l.drawingorder desc
		";
		#echo '<br>SQL zur Abfrage des Layers der Rolle: ' . $sql;
		$this->debug->write("<p>file:rolle.php class:rolle->getLayer - Abfragen der Layer zur Rolle:<br>" . $sql, 4);
		$ret = $this->database->execSQL($sql);
		$i = 0;
		while ($rs = pg_fetch_assoc($ret[1])) {
			$rs['queryable'] = ($rs['queryable'] === 't');
			if ($rs['rollenfilter'] != '') {		// Rollenfilter zum Filter hinzufügen
				if ($rs['filter'] == '') {
					$rs['filter'] = '(' . $rs['rollenfilter'] . ')';
				} else {
					$rs['filter'] = str_replace(' AND ', ' AND (' . $rs['rollenfilter'] . ') AND ', $rs['filter']);
				}
			}
			if ($replace_params) {
				foreach (array('name', 'alias', 'connection', 'maintable', 'classification', 'pfad', 'data') as $key) {
					$rs[$key] = replace_params_rolle(
						$rs[$key],
						['duplicate_criterion' => $rs['duplicate_criterion']]
					);
				}
			}
			$rs['Name_or_alias'] = $rs[($rs['alias'] == '' OR !$this->gui_object->Stelle->useLayerAliases) ? 'name' : 'alias'];
			$layer[$i] = $rs;
			$layer['layer_ids'][$rs['layer_id']] = &$layer[$i];
			$layer['layer_ids'][$layer[$i]['requires']]['required'] = $rs['layer_id'];
			$i++;
		}
		return $layer;
	}

	function getRollenLayer($LayerName, $typ = NULL) {
		$where = array();
		$where[] = "l.stelle_id = " . $this->stelle_id;
		$where[] = "l.user_id = " . $this->user_id;
		if ($LayerName != '') {
			if (is_numeric($LayerName)) {
				$where[] = "l.id = " . $LayerName;
			}
			else {
				$where[] = "l.name LIKE '" . $LayerName . "'";
			}
		}
		if ($typ != NULL) {
			$where[] = "typ = '" . $typ . "'";
		}
		$sql = "
			SELECT
				l.*,
				4 as tolerance,
				-l.id as layer_id,
				l.query as pfad,
				CASE WHEN Typ = 'import' THEN 1 ELSE 0 END as queryable,
				gle_view,
				'(' || rollenfilter || ')' as filter
			FROM
				kvwmap.rollenlayer AS l
			WHERE
				l.stelle_id = " . $this->stelle_id . " AND
				l.user_id = " . $this->user_id . "
		";
		if ($LayerName != '') {
			$sql .= " AND (l.name LIKE '" . $LayerName . "' ";
			if (is_numeric($LayerName)) {
				$sql .= 'OR l.id = ' . $LayerName . ')';
			}
			else {
				$sql .= ')';
			}
		}
		if ($typ != NULL){
			$sql .= " AND typ = '" . $typ . "'";
		}
		#echo '<br>SQL zur Abfrage des Rollenlayers: ' . $sql;
		$this->debug->write("<p>file:rolle.php class:rolle->getRollenLayer - Abfragen der Rollenlayer zur Rolle:<br>".$sql,4);
		$ret = $this->database->execSQL($sql);
		$layer = array();
		$i = 0;
		while ($rs = pg_fetch_assoc($ret[1])) {
			$rs['Name_or_alias'] = $rs['name'];
			$layer[$i] = $rs;
			$layer['layer_ids'][$rs['layer_id']] = &$layer[$i];
			$i++;
		}
		return $layer;
	}

	function readSettings() {
    # Abfragen und Zuweisen der Einstellungen der Rolle
    $sql = "
			SELECT
				*
			FROM
				kvwmap.rolle
			WHERE
				user_id = " . $this->user_id . " AND
				stelle_id = " . $this->stelle_id . "
		";
		#echo '<br>Read rolle settings mit sql: ' . $sql;
		$ret = $this->database->execSQL($sql, 4, 0, true);
		if (pg_num_rows($ret[1]) > 0){
			$rs = pg_fetch_assoc($ret[1]);
			$this->oGeorefExt = rectObj($rs['minx'],$rs['miny'],$rs['maxx'],$rs['maxy']);
			$this->nImageWidth=$rs['nimagewidth'];
			$this->nImageHeight=$rs['nimageheight'];			
			$this->mapsize=$this->nImageWidth.'x'.$this->nImageHeight;
			$this->auto_map_resize=$rs['auto_map_resize'];
			@$this->pixwidth=($rs['maxx']-$rs['minx'])/$rs['nimagewidth'];
			@$this->pixheight=($rs['maxy']-$rs['miny'])/$rs['nimageheight'];
			$this->pixsize=($this->pixwidth+$this->pixheight)/2;
			$this->nZoomFactor=$rs['nzoomfactor'];
			$this->epsg_code=$rs['epsg_code'];
			$this->epsg_code2=$rs['epsg_code2'];
			$this->coordtype=$rs['coordtype'];
			$this->last_time_id=$rs['last_time_id'];
			$this->gui=$rs['gui'];
			rolle::$language = $rs['language'];
			$this->hideMenue = ($rs['hidemenue'] == 'f'? false : true);
			$this->hideLegend = ($rs['hidelegend'] == 'f'? false : true);
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
			$this->layer_selection_mode=$rs['layer_selection_mode'];
			$this->layer_selection=$rs['layer_selection'];
			$this->singlequery=$rs['singlequery'];
			$this->querymode=$rs['querymode'];
			$this->geom_edit_first=$rs['geom_edit_first'];
			$this->dataset_operations_position = $rs['dataset_operations_position'];
			$this->immer_weiter_erfassen = $rs['immer_weiter_erfassen'];
			$this->upload_only_file_metadata = $rs['upload_only_file_metadata'];
			$this->overlayx=$rs['overlayx'];
			$this->overlayy=$rs['overlayy'];
			$this->last_query_layer=$rs['last_query_layer'];
			$this->instant_reload=$rs['instant_reload'];
			$this->menu_auto_close=$rs['menu_auto_close'];
			rolle::$layer_params = (array)json_decode('{' . $rs['layer_params'] . '}');
			$this->visually_impaired = $rs['visually_impaired'];
			$this->font_size_factor = $rs['font_size_factor'];
			$this->legendtype = $rs['legendtype'];
			$this->print_legend_separate = $rs['print_legend_separate'];
			$this->print_scale = $rs['print_scale'];
			if ($rs['hist_timestamp'] != '') {
				$this->hist_timestamp_de = $rs['hist_timestamp'];			# der wird zur Anzeige des Timestamps benutzt
				rolle::$hist_timestamp = DateTime::createFromFormat('d.m.Y H:i:s', $rs['hist_timestamp'])->format('Y-m-d\TH:i:s\Z');	# der hat die Form, wie der timestamp in der PG-DB steht und wird für die Abfragen benutzt
			}
			else {
				rolle::$hist_timestamp = $this->hist_timestamp_de = '';
				#rolle::$hist_timestamp = '';
			}
			$this->selectedButton = $rs['selectedbutton'];
			$buttons = explode(',', $rs['buttons']);
			$this->back = in_array('back', $buttons);
			$this->forward = in_array('forward', $buttons);
			$this->zoomin = in_array('zoomin', $buttons);
			$this->zoomout = in_array('zoomout', $buttons);
			$this->zoomall = in_array('zoomall', $buttons);
			$this->recentre = in_array('recentre', $buttons);
			$this->jumpto = in_array('jumpto', $buttons);
			$this->coord_query = in_array('coord_query', $buttons);
			$this->elevation_profile = in_array('elevation_profile', $buttons);
			$this->query = in_array('query', $buttons);
			$this->queryradius = in_array('queryradius', $buttons);
			$this->polyquery = in_array('polyquery', $buttons);
			$this->touchquery = in_array('touchquery', $buttons);
			$this->measure = in_array('measure', $buttons);
			$this->punktfang = in_array('punktfang', $buttons);
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

  function set_selected_button($selectedButton) {
    $this->selectedButton=$selectedButton;
    # Eintragen des aktiven Button
    $sql = "
			UPDATE 
				kvwmap.rolle 
			SET 
				selectedbutton = '" . $selectedButton . "'
    	WHERE 
				user_id = " . $this->user_id . ' 
				AND stelle_id = ' . $this->stelle_id;
    $this->debug->write("<p>file:rolle.php class:rolle->set_selected_button - Speichern des zuletzt gewählten Buttons aus dem Kartenfensters:",4);
    $this->database->execSQL($sql,4, $this->loglevel);
    return 1;
  }

  function saveSettings($extent) {
    $sql ='UPDATE kvwmap.rolle SET minx='.$extent->minx.',miny='.$extent->miny;
    $sql.=',maxx='.$extent->maxx.',maxy='.$extent->maxy;
    $sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
    # echo $sql;
    $this->debug->write("<p>file:rolle.php class:rolle function:saveSettings - Speichern der Einstellungen zur Rolle:",4);
    $this->database->execSQL($sql,4, $this->loglevel);
    return 1;
  }

  function setConsumeActivity($time,$activity,$prevtime) {
		$errmsg = '';
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
        $sql ='
					INSERT INTO 
						kvwmap.u_consume 
					VALUES (
						' . $this->user_id . ', 
						' . $this->stelle_id . ", 
						'" . $time . "',
						'" . $activity . "',						
						" . $this->nImageWidth . ',
						' . $this->nImageHeight . ", 
						'" . $this->epsg_code . "', 
						" . $this->oGeorefExt->minx . ', 
						' . $this->oGeorefExt->miny . ', 
						' . $this->oGeorefExt->maxx . ', 
						' . $this->oGeorefExt->maxy . ",
						'" . ($prevtime ?: $time) . "'
					) 
					ON CONFLICT DO NOTHING";
				#echo '<p>SQL zum Eintragen von consume-Aktivitäten in der Karte: ' . $sql;
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
            $sql = '
							INSERT INTO 
								kvwmap.u_consume2layer
							VALUES (
            		' . $this->user_id . ', 
								' . $this->stelle_id . ", 
								'" . $time . "', 
								" . $layer[$i] . '
							)';
						#echo '<p>SQL zum Eintragen des consumierten Layers: ' . $sql;
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

	function set_last_time_id($time) {
		# Eintragen der last_time_id
		$sql = "
			UPDATE
				kvwmap.rolle
			SET
				last_time_id = '" . $time . "'
			WHERE
				user_id = " . $this->user_id . " AND
				stelle_id = " . $this->stelle_id . "
		";
		#echo $sql;
		$ret=$this->database->execSQL($sql, 4, 1);
		return $ret;
	}

  function getAktivLayer($aktivStatus,$queryStatus,$logconsume) {
		$layer = array();
    # Abfragen der zu loggenden Layer der Rolle
    $sql = '
			SELECT 
				r2ul.layer_id 
			FROM 
				kvwmap.u_rolle2used_layer AS r2ul' . 
    		($logconsume? ', kvwmap.layer AS l' : '') . '
    	WHERE 
				r2ul.user_id = ' . $this->user_id . ' AND 
				r2ul.stelle_id = ' . $this->stelle_id;
    if ($logconsume) {
      $sql .= ' 
				AND r2ul.layer_id = l.layer_id 
				AND l.logconsume';
    }
    $anzaktivStatus=count($aktivStatus);
    if ($anzaktivStatus > 0) {
      $sql.=' AND r2ul.aktivstatus IN (' . implode(',', $aktivStatus) . ')';
    }
    $anzqueryStatus=count($queryStatus);
    if ($anzqueryStatus > 0) {
      $sql.=' AND r2ul.querystatus IN (' . implode(',', $queryStatus) . ')';
    }
    #echo $sql;
    $this->debug->write("<p>file:rolle.php class:rolle->getAktivLayer - Abfragen der aktiven Layer zur Rolle:<br>".$sql,4);
    $ret = $this->database->execSQL($sql,4, 0);
    if ($ret[0]) {
      $ret[1] = '<br>Die aktiven Layer konnten nicht abgefragt werden.<br>'.$ret[1];
    }
    else {
			while ($rs = pg_fetch_assoc($ret[1])) {
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
	var $dbname;
	var $user;
	var $passwd;
	var $schema;
	var $pg_text_attribute_types = array('character', 'character varying', 'text', 'timestamp without time zone', 'timestamp with time zone', 'date', 'USER-DEFINED');
	var $version = POSTGRESVERSION;
	var $connection_id;
	var $error;
	var $dbName;

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

	/**
	* Open the database connection based on the given connection_id
	* @param integer, $connection_id The id of the connection defined in connections table, if 0 default connection will be used
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
			$this->debug->write("Database connection successfully opend.", 4);
			$this->setClientEncodingAndDateStyle();
			$this->connection_id = $connection_id;
			return true;
		}
	}

	/**
	* return the credential details as array from connections_table
	* or default values if no exists for connection_id
	* @param integer $connection_id The id of connection information in connection table
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
			'host'     => $this->host ?: POSTGRES_HOST,
			'port'     => $this->port ?: 5432,
			'dbname'   => $this->dbName ?: POSTGRES_DBNAME,
			'user'     => $this->user ?: POSTGRES_USER,
			'password' => $this->passwd ?: POSTGRES_PASSWORD
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

	function execSQL($sql, $debuglevel = 4, $loglevel = 1, $suppress_err_msg = false, $prepared_params = array()) {
		if (!$this->dbConn) {
			echo '<p>pgconn: ' . $this->dbConn;
		}
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
				$ret['sql'] = $sql;
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
					if ($error_obj) {
						if (array_key_exists('msg_type', $error_obj)) {
							$ret['type'] = $error_obj['msg_type'];
						}
						if (array_key_exists('msg', $error_obj) AND $error_obj['msg'] != '') {
							$ret['msg'] = $error_obj['msg'];
						}
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
			# Fehler setze entsprechende Flags und Fehlermeldung
			$ret[0] = 1;
			$ret[1] = $ret['msg'];
			if ($suppress_err_msg) {
				# mache nichts, denn die Fehlermeldung wird unterdrückt
			}
			else {
				if (strpos(strtolower($this->gui->formvars['export_format']), 'json') !== false) {
					header('Content-Type: application/json; charset=utf-8');
					echo utf8_decode(json_encode($ret));
					exit;
				}
				# gebe Fehlermeldung aus.
				$ret[1] = $ret['msg'] = sql_err_msg('Fehler bei der Abfrage der PostgreSQL-Datenbank:' . $sql, $sql, $ret['msg'], 'error_div_' . rand(1, 99999));
				$this->gui->add_message($ret['type'], $ret['msg']);
				header('error: true');	// damit ajax-Requests das auch mitkriegen
			}
		}
		$this->success = $ret['success'];
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
		$this->db = $GUI->pgdatabase;
		$this->Stelle_ID = $Stelle_ID;
		$this->User_ID = $User_ID;
		$this->rolle = new rolle($User_ID, $Stelle_ID, $this->db);
	}
	
	function getFilter($layer_id, $stelle_id){
    $sql ='SELECT filter FROM kvwmap.used_layer WHERE layer_id = '.$layer_id.' AND stelle_id = '.$stelle_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getFilter - Lesen des Filter-Statements des Layers:<br>" . $sql,4);
    $ret = $this->db->execSQL($sql);
    if (!$this->db->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . "<br>", 4); return 0; }
    $rs = pg_fetch_assoc($ret[1]);
    $filter = $rs['filter'];
    return $filter;
  }	
	
  function read_layer_attributes($layer_id, $layerdb, $attributenames, $all_languages = false, $recursive = false, $get_default = false, $replace = true, $replace_only = array('default', 'options', 'visibility_rules'), $attribute_values = []) {
		global $language;
		$attributes = array(
			'name' => array(),
			'tab' => array()
		);
		$einschr = '';

		$alias_column = (
			(!$all_languages AND $language != 'german') ?
			"
				CASE
					WHEN alias_" . $language. " != '' THEN alias_" . $language . "
					ELSE alias
				END AS alias
			" :
			"
				alias
			"
		);

		if ($attributenames != NULL) {
			$einschr = " AND a.name IN ('" . implode("', '", $attributenames) . "')";
		}

		$sql = "
			SELECT
				\"order\", " .
				$alias_column . ", alias_low_german, alias_english, alias_polish, alias_vietnamese,
				layer_id,
				a.name,
				real_name,
				tablename,
				table_alias_name,
				a.schema,
				type,
				d.name as typename,
				geometrytype,
				constraints,
				saveable,
				nullable,
				length,
				decimal_length,
				\"default\",
				form_element_type,
				options,
				tooltip,
				\"group\",
				tab,
				arrangement,
				labeling,
				raster_visibility,
				dont_use_for_new,
				mandatory,
				quicksearch,
				visible,
				visibility_rules,
				\"order\",
				privileg,
				query_tooltip,
				style_attribute,
				visibility_rules
			FROM
				kvwmap.layer_attributes as a LEFT JOIN
				kvwmap.datatypes as d ON d.id::text = REPLACE(type, '_', '')
			WHERE
				layer_id = " . $layer_id .
				$einschr . "
			ORDER BY
			\"order\"
		";
		// echo '<br>Sql read_layer_attributes: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_layer_attributes:<br>",4);
		$ret = $this->db->execSQL($sql);
		$i = 0;
		while ($rs = pg_fetch_assoc($ret[1])) {
			$attributes['enum'][$i] = array();
			$attributes['order'][$i] = $rs['order'];
			$attributes['name'][$i] = $rs['name'];
			$attributes['indizes'][$rs['name']] = $i;
			if($rs['real_name'] == '')$rs['real_name'] = $rs['name'];
			$attributes['real_name'][$rs['name']] = $rs['real_name'];
			if ($rs['tablename']) {
				$attributes['table_name'][$i] = $rs['tablename'];
				$attributes['table_name'][$rs['name']] = $rs['tablename'];
				$attributes['schema'][$i] = $rs['schema'];
			}
			if ($rs['table_alias_name'])$attributes['table_alias_name'][$i] = $rs['table_alias_name'];
			if ($rs['table_alias_name'])$attributes['table_alias_name'][$rs['name']] = $rs['table_alias_name'];
			$attributes['table_alias_name'][$rs['tablename']] = $rs['table_alias_name'];
			$attributes['type'][$i] = $rs['type'];
			$attributes['typename'][$i] = $rs['typename'];
			$type = ltrim($rs['type'], '_');
			if ($recursive AND is_numeric($type)){
				$attributes['type_attributes'][$i] = $this->read_datatype_attributes($layer_id, $type, $layerdb, NULL, $all_languages, true, $replace);
			}
			if ($rs['type'] == 'geometry'){
				$attributes['the_geom'] = $rs['name'];
			}
			$attributes['geomtype'][$i]= $rs['geometrytype'];
			$attributes['geomtype'][$rs['name']]= $rs['geometrytype'];
			$attributes['constraints'][$i]= $rs['constraints'];
			$attributes['constraints'][$rs['real_name']]= $rs['constraints'];
			if ($rs['constraints'] == 'PRIMARY KEY') {
				$attributes['pk'][] = $rs['real_name'];
			}
			$attributes['saveable'][$i]= $rs['saveable'];
			$attributes['nullable'][$i]= $rs['nullable'];
			$attributes['length'][$i]= $rs['length'];
			$attributes['decimal_length'][$i]= $rs['decimal_length'];
			$attributes['default'][$i] = $rs['default'];
			$attributes['options'][$i] = $rs['options'];
			$attributes['style_attribute'][$i] = $rs['style_attribute'];
			
			$attributes['visibility_rules'][$i] = $rs['visibility_rules'];
			// Referenz auf dependents
    	$attributes['dependents'][$i] = &$dependents[$rs['name']];
			// JSON parsen
			$rulesJson = $rs['visibility_rules'] ?? '';
			$rules = $rulesJson ? json_decode($rulesJson, true) : null;

			// Abhängige Attribute sammeln
			$dependentAttributes = [];
			if ($rules) {
				$collectAttributes = function($node) use (&$dependentAttributes, &$collectAttributes) {
						if (isset($node['rules'])) {
								foreach ($node['rules'] as $child) $collectAttributes($child);
						} elseif (isset($node['attribute'])) {
								$dependentAttributes[] = $node['attribute'];
						}
				};
				$collectAttributes($rules);
				$dependentAttributes = array_unique($dependentAttributes);
			}

			// Abhängigkeiten eintragen
			foreach ($dependentAttributes as $depAttr) {
					$dependents[$depAttr][] = $rs['name'];
			}


			if ($replace) {
				foreach($replace_only AS $column) {
					if ($attributes[$column][$i] != '') {
						$attributes[$column][$i] = 	replace_params_rolle(
							$attributes[$column][$i],
							((count($attribute_values) > 0 AND in_array('default', $replace_only)) ? $attribute_values : NULL)
						);
					}
				}
			}

			if ($get_default AND $attributes['default'][$i] != '') {
				# da Defaultvalues auch dynamisch sein können (z.B. 'now'::date) wird der Defaultwert erst hier ermittelt
				$default = (substr($attributes['type'][$i], 0, 1) == '_' ? 'to_json(' . $attributes['default'][$i] . ')' : $attributes['default'][$i]); # to_json für Array-Datentyp
				$ret1 = $layerdb->execSQL('SELECT ' . $default, 4, 0);
				if ($ret1[0] == 0) {
					$attributes['default'][$i] = @array_pop(pg_fetch_row($ret1[1]));
				}
			}
			$attributes['form_element_type'][$i] = $rs['form_element_type'];
			$attributes['form_element_type'][$rs['name']] = $rs['form_element_type'];
			$attributes['options'][$rs['name']] = $attributes['options'][$i];
			$attributes['alias'][$i] = $rs['alias'];
			$attributes['alias_low-german'][$i] = $rs['alias_low-german'];
			$attributes['alias_english'][$i] = $rs['alias_english'];
			$attributes['alias_polish'][$i] = $rs['alias_polish'];
			$attributes['alias_vietnamese'][$i] = $rs['alias_vietnamese'];
			$attributes['tooltip'][$i] = $rs['tooltip'];
			$attributes['group'][$i] = $rs['group'];
			$attributes['tab'][$i] = $rs['tab'];
			$attributes['arrangement'][$i] = $rs['arrangement'];
			$attributes['labeling'][$i] = $rs['labeling'];
			$attributes['raster_visibility'][$i] = $rs['raster_visibility'];
			$attributes['dont_use_for_new'][$i] = $rs['dont_use_for_new'];
			$attributes['mandatory'][$i] = $rs['mandatory'];
			$attributes['quicksearch'][$i] = $rs['quicksearch'];
			$attributes['visible'][$i] = $rs['visible'];
			$attributes['privileg'][$i] = $rs['privileg'];
			$attributes['query_tooltip'][$i] = $rs['query_tooltip'];
			if ($rs['form_element_type'] == 'Style') {
				$attributes['style'][] = $rs['name'];
				$attributes['visible'][$i] = 0;
			}
			if ($rs['form_element_type'] == 'Editiersperre') {
				$attributes['Editiersperre'] = $rs['name'];
			}
			$i++;
		}
		if (value_of($attributes, 'table_name') != NULL) {
			$attributes['all_table_names'] = array_unique($attributes['table_name']);
		}
		else {
			$attributes['all_table_names'] = array();
		}
		$attributes['tabs'] = array_values(array_filter(array_unique($attributes['tab']), 'strlen'));
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
		sanitize($layer_id, 'int');
		#echo 'Class db_map Method get_layer_connection';
		# $layer_id < 0 Rollenlayer else normal layer
		$sql = "
			SELECT
				connection_id,
				" . ($layer_id < 0 ? "'" . CUSTOM_SHAPE_SCHEMA . "' AS " : "") . "schema
			FROM
				" . ($layer_id < 0 ? "kvwmap.rollenlayer" : "kvwmap.layer") . "
			WHERE
				" . ($layer_id < 0 ? "-id" : "layer_id") . " = " . $layer_id . " AND
				connectiontype = 6
		";
		#echo '<br>sql: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->get_layer_connection - Lesen der connection Daten des Layers:<br>" . $sql, 4);
		$ret = $this->db->execSQL($sql);
		if (!$ret[0]) {
			return pg_fetch_assoc($ret[1]);
		}
		else {
			$this->debug->write("<br>Abbruch beim Lesen der Layer connection in get_layer_connection, Zeile: " . __LINE__ . "<br>", 4);
			return array();
		}
	}

	function read_ReferenceMap() {
    $sql = "
			SELECT
				r.*
			FROM
				kvwmap.referenzkarten AS r,
				kvwmap.stelle AS s
			WHERE
				r.id = s.referenzkarte_id
    		AND s.id = " . $this->Stelle_ID . "
		";
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_ReferenceMap - Lesen der Referenzkartendaten:<br>",4);
		$ret = $this->db->execSQL($sql, 4, 0, true);
		$rs = pg_fetch_assoc($ret[1]);
    $this->referenceMap = $rs;
#		echo '<br>sql: ' . print_r($sql, true);
#		echo '<br>ref: ' . print_r($this->referenceMap, true);
    return $rs;
  }

	function read_Layer($withClasses, $useLayerAliases = false, $groups = NULL) {
		global $language;

		if ($language != 'german') {
			$name_column = "
			CASE
				WHEN l.name_" . $language . " != \"\" THEN l.name_" . $language . "
				ELSE l.name
			END AS name";
			$group_column = '
			CASE
				WHEN gruppenname_' . $language . ' IS NOT NULL THEN gruppenname_' . $language . '
				ELSE gruppenname
			END AS gruppenname';
		}
		else {
			$name_column = "l.name";
			$group_column = 'gruppenname';
		}

		$sql = "
			SELECT 
				l.oid,
				coalesce(rl.transparency, ul.transparency, 100) as transparency,
				rl.aktivstatus,
				rl.querystatus,
				rl.gle_view,
				rl.showclasses,
				rl.rollenfilter,
				ul.queryable,
				COALESCE(rl.drawingorder, l.drawingorder) as drawingorder,
				l.legendorder,
				ul.minscale, ul.maxscale,
				ul.offsite,
				ul.postlabelcache,
				ul.filter,
				ul.template,
				ul.header,
				ul.footer,
				ul.symbolscale,
				ul.requires,
				ul.privileg,
				ul.export_privileg,
				ul.group_id,
				l.layer_id," .
				$name_column . ",
				l.alias,
				l.datentyp, COALESCE(ul.group_id, l.Gruppe) AS Gruppe, l.pfad, l.Data, l.tileindex, l.tileitem, l.labelangleitem, coalesce(rl.labelitem, l.labelitem) as labelitem, rl.labelitem as user_labelitem,
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
				" . ($this->GUI->plugin_loaded('mobile') ? ', l.sync' : '') . "
				" . ($this->GUI->plugin_loaded('mobile') ? ', l.vector_tile_url' : '') . "
				" . ($this->GUI->plugin_loaded('portal') ? ', l.cluster_option' : '') . "
			FROM
				kvwmap.u_rolle2used_layer AS rl,
				kvwmap.used_layer AS ul JOIN
				kvwmap.layer AS l ON l.layer_id = ul.layer_id LEFT JOIN
				kvwmap.u_groups AS g ON COALESCE(ul.group_id, l.Gruppe) = g.id LEFT JOIN
				kvwmap.u_groups2rolle AS gr ON g.id = gr.id LEFT JOIN
				kvwmap.connections as c ON l.connection_id = c.id
			WHERE
				rl.stelle_id = ul.stelle_id AND
				rl.layer_id = ul.layer_id AND
				(ul.minscale != -1 OR ul.minscale IS NULL) AND
				l.datentyp != 5 AND 
				rl.stelle_id = " . $this->Stelle_ID . " AND rl.user_id = " . $this->User_ID . " AND
				gr.stelle_id = " . $this->Stelle_ID . " AND
				gr.user_id = " . $this->User_ID .
				($groups != NULL ? " AND g.id IN (" . $groups . ")" : '') .
				($this->nurAufgeklappteLayer ? " AND (rl.aktivstatus != '0' OR gr.status != '0' OR ul.requires IS NOT NULL)" : '') .
				($this->nurAktiveLayer ? " AND (rl.aktivstatus != '0')" : '') .
				($this->OhneRequires ? " AND (ul.requires IS NULL)" : '') .
				($this->nurFremdeLayer ? " AND (c.host NOT IN ('pgsql', 'localhost') OR l.connectiontype != 6 AND rl.aktivstatus != '0')" : '') .
				($this->nurNameLike ? " AND l.name LIKE '" . $this->nurNameLike . "'" : '') . 
				($this->nurPostgisLayer ? " AND l.connectiontype = 6" : '') . 
				($this->keinePostgisLayer ? " AND l.connectiontype != 6" : '') . 
				($this->nurLayerID ? " AND l.layer_id = " . $this->nurLayerID : '') .
				($this->nurLayerIDs ? " AND l.layer_id IN (" . $this->nurLayerIDs . ")" : '') .
				($this->nichtLayerID ? " AND l.layer_id != " . $this->nichtLayerID : '') . "
			ORDER BY
				drawingorder
		";
		# echo '<br>SQL zur Abfrage der Layer: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Layer - Lesen der Layer der Rolle:<br>", 4);
		$ret = $this->db->execSQL($sql, 4, 0, true);
		$layer = array();
		$layer['list'] = array();
		$this->disabled_classes = $this->read_disabled_classes();
		$i = 0;
		while ($rs = pg_fetch_assoc($ret[1])) {
			if ($rs['rollenfilter'] != '') {		// Rollenfilter zum Filter hinzufügen
				if ($rs['filter'] == '') {
					$rs['filter'] = '('.$rs['rollenfilter'].')';
				}
				else {
					$rs['filter'] = str_replace(' AND ', ' AND ('.$rs['rollenfilter'].') AND ', $rs['filter']);
				}
			}

			$rs['Name_or_alias'] = $rs[($rs['alias'] == '' OR !$useLayerAliases) ? 'name' : 'alias'];
			$rs['id'] = $i;
			$rs['alias_link'] = replace_params_link(
				$rs['Name_or_alias'],
				rolle::$layer_params,
				$rs['layer_id']
			);
			foreach (array('name', 'alias', 'Name_or_alias', 'connection', 'classification', 'classitem', 'tileindex', 'pfad', 'data') AS $key) {
				$rs[$key] = replace_params_rolle(
					$rs[$key],
					['duplicate_criterion' => $rs['duplicate_criterion']]
				);
			}
			if ($withClasses == 2 OR $rs['requires'] != '' OR ($withClasses == 1 AND $rs['aktivstatus'] != '0')) {
				# bei withclasses == 2 werden für alle Layer die Klassen geladen,
				# bei withclasses == 1 werden Klassen nur dann geladen, wenn der Layer aktiv ist
				$rs['Class'] = $this->read_Classes($rs['layer_id'], $this->disabled_classes, false, $rs['classification']);
			}
			if ($rs['maxscale'] > 0) {
				$rs['maxscale'] = $rs['maxscale'] + 0.3;
			}
			if ($rs['minscale'] > 0) {
				$rs['minscale'] = $rs['minscale'] - 0.3;
			}
			$rs['queryable'] = ($rs['queryable'] == 't');
			$layer['list'][$i] = $rs;
			# Pointer auf requires-Array
			$layer['list'][$i]['required'] =& $requires_layer[$rs['layer_id']];
			if ($rs['requires'] != '') {
				# requires-Array füllen
				$requires_layer[$rs['requires']][] = $rs['layer_id'];
				if ($rs['queryable']) {
					# wenn der untergeordnete Layer queryable ist, wird queryable auch beim übergeordneten gesetzt, damit die Checkbox in der Legende erscheint
					$layer['layer_ids'][$rs['requires']]['queryable'] = $rs['queryable'];
				}
			}
			$layer['layer_ids'][$rs['layer_id']] =& $layer['list'][$i]; # damit man mit einer Layer-ID als Schlüssel auf dieses Array zugreifen kann
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
				kvwmap.u_rolle2used_class
			WHERE
				user_id = " . $this->User_ID . "
				AND stelle_id = " . $this->Stelle_ID . "
		";
		#echo '<p>SQL zur Abfrage von diabled classes: ' . $sql;
		$ret = $this->db->execSQL($sql, 4, 0, true);
    while ($row = pg_fetch_assoc($ret[1])) {
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
						WHEN name_" . $language . "IS NOT NULL THEN name_" . $language . "
						ELSE name
					END" : "
					name"
				) . " AS name,
				name_low_german,
				name_english,
				name_polish,
				name_vietnamese,
				class_id,
				layer_id,
				expression,
				classification,
				legendgraphic,
				legendimagewidth,
				legendimageheight,
				drawingorder,
				legendorder,
				text
			FROM
				kvwmap.classes
			WHERE
				layer_id = " . $Layer_ID .
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
				class_id
		";
		#echo $sql.'<br>';
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Class - Lesen der Classen eines Layers:<br>", 4);
		$ret = $this->db->execSQL($sql, 4, 0, true);
		$index = 0;
		while ($rs = pg_fetch_assoc($ret[1])) {
			$rs['Style'] = $this->read_Styles($rs['class_id']);
			$rs['Label'] = $this->read_Label($rs['class_id']);
			$rs['index'] = $index;
			#Anne
			if($disabled_classes){
				if($disabled_classes['status'][$rs['class_id']] == 2) {
					$rs['status'] = 1;
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
				elseif ($disabled_classes['status'][$rs['class_id']] == '0') {
					$rs['Status'] = 0;
				}
				else $rs['status'] = 1;
			}
			else $rs['status'] = 1;

			$Classes[] = $rs;
			$index++;
		}
		return $Classes;
	}

  function read_Styles($Class_ID) {
		$Styles = array();
    $sql ='
			SELECT 
				* 
			FROM 
				kvwmap.styles AS s,
				kvwmap.u_styles2classes AS s2c
			WHERE 
				s.Style_ID = s2c.style_id AND 
				s2c.class_id = ' . $Class_ID . '
			ORDER BY drawingorder';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Styles - Lesen der Styledaten:<br>",4);
    $ret = $this->db->execSQL($sql, 4, 0, true);
    while ($rs = pg_fetch_assoc($ret[1])) {
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
				kvwmap.labels AS l,
				kvwmap.u_labels2classes AS l2c
			WHERE
				l.label_id = l2c.label_id
				AND l2c.class_id = " . $Class_ID . "
		";
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Label - Lesen der Labels zur Classe eines Layers:<br>",4);
		$ret = $this->db->execSQL($sql, 4, 0, true);
		while ($rs = pg_fetch_assoc($ret[1])) {
			$Labels[]=$rs;
		}
		return $Labels;
	}

	function read_RollenLayer($id = NULL, $typ = NULL, $autodelete = NULL) {
		$sql = "
			SELECT DISTINCT
				l.id,
				l.user_id,
				l.stelle_id,
				l.aktivstatus,
				l.querystatus,
				l.name,
				l.name as alias,
				l.gruppe,
				l.typ,
				l.datentyp,
				l.data,
				l.query,
				l.connectiontype,
				l.connection_id,
				l.wms_auth_username,
				l.wms_auth_password,
				CASE
					WHEN connectiontype = 6 THEN concat('host=', c.host, ' port=', c.port, ' dbname=', c.dbname, ' user=', c.user, ' password=', c.password, ' application_name=kvwmap_user_', l.user_id)
					ELSE l.connection
				END as connection,
				l.epsg_code,
				l.transparency,
				l.buffer,
				l.labelitem,
				l.classitem,
				l.gle_view,
				l.rollenfilter,
				l.duplicate_from_layer_id,
				l.duplicate_criterion,
				g.gruppenname,
				-l.id AS layer_id,
				1 as showclasses,
				CASE WHEN Typ = 'import' THEN 1 ELSE 0 END as queryable,
				CASE WHEN rollenfilter != '' THEN concat('(', rollenfilter, ')') END as Filter,
				'' as wms_name,
				'' as wms_format,
				'' as wms_server_version,
				'' as wms_connectiontimeout,
				'' as oid
			FROM
				kvwmap.rollenlayer AS l JOIN
				kvwmap.u_groups AS g ON l.Gruppe = g.id LEFT JOIN
				kvwmap.connections AS c ON l.connection_id = c.id
			WHERE
				l.stelle_id=" . $this->Stelle_ID . " AND
				l.user_id = " . $this->User_ID .
				($id != NULL ? " AND l.id = " . $id : '') .
				($typ != NULL ? " AND l.typ = '" . $typ . "'" : '') . 
				($autodelete != NULL ? " AND l.autodelete = '" . $autodelete . "'" : '') . "
		";
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_RollenLayer - Lesen der RollenLayer:<br>",4);
		#echo '<p>SQL zur Abfrage der Rollenlayer: ' . $sql;
		$ret = $this->db->execSQL($sql, 4, 0, true);
		$Layer = array();
		while ($rs = pg_fetch_assoc($ret[1])) {
			$rs['Class'] = $this->read_Classes(-$rs['id'], $this->disabled_classes);
			foreach (array('name', 'alias', 'connection', 'classification', 'classitem', 'pfad', 'data') AS $key) {
				$rs[$key] = replace_params_rolle(
					$rs[$key],
					['duplicate_criterion' => $rs['duplicate_criterion']]
				);
			}
			$rs['alias_link'] = $rs['alias'];
			$rs['Name_or_alias'] = $rs['alias'];
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
		$this->debug->write("<p>kataster.php Flur->getBezeichnungFromPosition:",4);
		$sql ="SELECT gm.bezeichnung as gemeindename, fl.gemeindezugehoerigkeit_gemeinde gemeinde, gk.bezeichnung as gemkgname, fl.land::text||fl.gemarkungsnummer::text as gemkgschl, fl.flurnummer as flur, CASE WHEN fl.nenner IS NULL THEN fl.zaehler::text ELSE fl.zaehler::text||'/'||fl.nenner::text end as flurst, s.bezeichnung as strasse, l.hausnummer ";
    $sql.="FROM alkis.ax_gemarkung as gk, alkis.ax_gemeinde as gm, alkis.ax_flurstueck as fl ";
		$sql.="LEFT JOIN alkis.ax_lagebezeichnungmithausnummer l ON l.gml_id = ANY(fl.weistauf) ";
		$sql.="LEFT JOIN alkis.ax_lagebezeichnungkatalogeintrag s ON l.kreis=s.kreis AND l.gemeinde=s.gemeinde AND s.lage = lpad(l.lage,5,'0') ";
    $sql.="WHERE gk.gemarkungsnummer = fl.gemarkungsnummer AND gm.kreis = fl.gemeindezugehoerigkeit_kreis AND gm.gemeinde = fl.gemeindezugehoerigkeit_gemeinde ";
    $sql.=" AND ST_WITHIN(st_transform(st_geomfromtext('POINT(".$position['rw']." ".$position['hw'].")',".$epsgcode."), ".EPSGCODE_ALKIS."),fl.wkb_geometry) ";
		$sql.= $this->database->build_temporal_filter(array('gk', 'gm', 'fl'));
    #echo $sql;
    $ret=$this->database->execSQL($sql,4, 0);
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
}
?>
