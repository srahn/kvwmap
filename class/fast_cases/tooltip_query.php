<?

/**
* Function returns a readable message of sql errors optionally with word $find replaced by asterists *****
*/
function err_msg($file, $line, $msg, $find = '') {
	return "<br>Abbruch in " . $file . " Zeile: " . $line . "<br>wegen: " . ($find != '' ? str_replace($find, '*****', $msg) : $msg). "<p>" . INFO1;
}

function count_or_0($val) {
	if (is_null($val) OR !is_array($val)) {
		return 0;
	}
	else {
		return count($val);
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

function get_first_word_after($str, $word, $delim1 = ' ', $delim2 = ' ', $last = false) {
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
	return '';
}

function mapserverExp2SQL($exp, $classitem) {
	$exp = str_replace(array("'[", "]'", '[', ']'), '', $exp);
	$exp = str_replace(' eq ', ' = ', $exp);
	$exp = str_replace(' ne ', ' != ', $exp);
	$exp = str_replace(' ge ', ' >= ', $exp);
	$exp = str_replace(' le ', ' <= ', $exp);
	$exp = str_replace(' gt ', ' > ', $exp);
	$exp = str_replace(' lt ', ' < ', $exp);
	$exp = str_replace(" = ''", ' IS NULL', $exp);
	$exp = str_replace('\b', '\y', $exp);
	if (strpos($exp, ' IN ') != false) {
		$array = get_first_word_after($exp, ' IN');
		$exp = str_replace(' IN ', ' = ANY(', $exp);
		$exp = str_replace($array, $array . ')', $exp);
	}

	if ($exp != '' AND substr($exp, 0, 1) != '(' AND $classitem != '') { # Classitem davor setzen
		if (strpos($exp, '/') === 0) { # regex
			$operator = '~';
			$exp = str_replace('\/', 'escaped_slash', $exp);
			$exp = str_replace('/', '', $exp);
			$exp = str_replace('escaped_slash', '/', $exp);
		}
		else {
			$operator = '=';
		}
		if (substr($exp, 0, 1) != "'") {
			$quote = "'";
		}
		$exp = '"' . $classitem . '"::text ' . $operator . ' ' . $quote . $exp . $quote;
	}
	return $exp;
}

function replace_semicolon($text) {
	return str_replace(';', '', $text);
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

function url2filepath($url, $doc_path, $doc_url) {
	if ($doc_path == '') {
		$doc_path = CUSTOM_IMAGE_PATH;
	}
	$url_parts = explode($doc_url, $url);
	return $doc_path . $url_parts[1];
}

function format_human_filesize($bytes, $precision = 2) {
	$sz = 'BKMGTP';
	$factor = floor((strlen($bytes) - 1) / 3);
	return sprintf("%." . $precision. "f", $bytes / pow(1024, $factor)) . ' ' . @$sz[$factor] . 'B';
}

function human_filesize($file) {
	$bytes = @filesize($file);
	return format_human_filesize($bytes);
}

function pg_quote($column){
	return ctype_lower($column) ? $column : '"'.$column.'"';
}

function quote($var, $type = NULL){
	switch ($type) {
		case 'text' : case 'varchar' : {
			return "'".$var."'";
		}break;
		default : {
			return is_numeric($var) ? $var : "'".$var."'";
		}
	}
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
	if(!is_array($array))$array = array();
	return (array_key_exists($key, $array) ? $array[$key] :	'');
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

function umlaute_html($string){
	$string = str_replace('&auml;', 'ä', $string);
	$string = str_replace('&uuml;', 'ü', $string);
	$string = str_replace('&ouml;', 'ö', $string);
	$string = str_replace('&Auml;', 'Ä', $string);
	$string = str_replace('&Uuml;', 'Ü', $string);
	$string = str_replace('&Ouml;', 'Ö', $string);
	$string = str_replace('&szlig;', 'ß', $string);
	$string = str_replace('&oslash;', 'ø', $string);
	$string = str_replace('&aelig;', 'æ', $string);
	$string = str_replace('&nbsp;', ' ', $string);
	return $string;
}

function umlaute_javascript($text){
	$text = str_replace("ä", "%E4", $text);
	$text = str_replace("ö", "%F6", $text);
	$text = str_replace("ü", "%FC", $text);
	$text = str_replace("Ä", "%C4", $text);
	$text = str_replace("Ö", "%D6", $text);
	$text = str_replace("Ü", "%DC", $text);
	$text = str_replace("ß", "%DF", $text);
	$text = str_replace("²", "%B2", $text);
	$text = str_replace('"', '%A8', $text);
	$text = str_replace('&', '%26', $text);
	return $text;
}

function get_select_parts($select){
	$column = explode(',', $select);		# an den Kommas splitten
  for($i = 0; $i < count($column); $i++){
  	$klammerauf = substr_count($column[$i], '(');
  	$klammerzu = substr_count($column[$i], ')');
		$hochkommas = substr_count($column[$i], "'");
		# Wenn ein Select-Teil eine ungerade Anzahl von Hochkommas oder mehr Klammern auf als zu hat,
		# wurde hier entweder ein Komma im einem String verwendet (z.B. x||','||y) oder eine Funktion (z.B. round(x, 2)) bzw. eine Unterabfrage mit Kommas verwendet
  	if($hochkommas % 2 != 0 OR $klammerauf > $klammerzu){
  		$column[$i] = $column[$i].','.$column[$i+1];
  		array_splice($column, $i+1, 1);
			$i--;							# und nochmal prüfen, falls mehrere Kommas drin sind
  	}
  }
  return $column;
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

	function plugin_loaded($plugin) {
		global $kvwmap_plugins;
		return in_array($plugin, $kvwmap_plugins);
	}

	function sanitize($vars) {
		foreach ($vars as $name => $type) {
			sanitize($this->formvars[$name], $type);
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

	function loadMultiLingualText($language) {
    #echo 'In der Rolle eingestellte Sprache: '.rolle::$language;
    $this->Stelle->language=$language;
    $this->Stelle->getName();
    include(LAYOUTPATH.'languages/'.$language.'.php');
  }

	function queryMap() {
		# scale ausrechnen, da wir uns das loadmap sparen
		$center_y = ($this->user->rolle->oGeorefExt->maxy + $this->user->rolle->oGeorefExt->miny) / 2;
		if($this->user->rolle->epsg_code == 4326){$unit = MS_DD;} else {$unit = MS_METERS;}
		$md = ($this->user->rolle->nImageWidth-1)/(96 * InchesPerUnit($unit, $center_y));
		$gd = $this->user->rolle->oGeorefExt->maxx - $this->user->rolle->oGeorefExt->minx;
		$this->map_scaledenom = round($gd/$md);
    # Abfragebereich berechnen
		if($this->formvars['querypolygon'] != ''){
			$rect = $this->formvars['querypolygon'];
		}
		else{
			if($this->formvars['rectminx'] != ''){			// ?????????
				$rect = rectObj($this->formvars['rectminx'],$this->formvars['rectminy'],$this->formvars['rectmaxx'],$this->formvars['rectmaxy']);		// ?????????
			}
			else{
				$rect = $this->create_query_rect($this->formvars['INPUT_COORD']);
			}
		}
    if($this->show_query_tooltip == true){
      $this->tooltip_query($rect);
    }
    else{
      $this->SachdatenAnzeige($rect);
			if($this->formvars['printversion'] != ''){
				$this->mime_type = 'printversion';
			}
			$this->output();
    }
  }

	function create_query_rect($input_coords){
		if($input_coords != ''){
			$corners=explode(';', $input_coords);
			if(count($corners) < 3){
				$lo=explode(',',$corners[0]); # linke obere Ecke in Bildkoordinaten von links oben gesehen
				$ru=explode(',',$corners[1]); # reche untere Ecke des Auswahlbereiches in Bildkoordinaten von links oben gesehen
				$width=$this->user->rolle->pixsize*($ru[0]-$lo[0]); # Breite des Auswahlbereiches in m
				$height=$this->user->rolle->pixsize*($ru[1]-$lo[1]); # Höhe des Auswahlbereiches in m
				#echo 'Abfragerechteck im Bild: '.$lo[0].' '.$lo[1].' '.$ru[0].' '.$ru[1];
				# linke obere Ecke im Koordinatensystem in m
				$minx=$this->user->rolle->oGeorefExt->minx+$this->user->rolle->pixsize*$lo[0]; # x Wert
				$miny=$this->user->rolle->oGeorefExt->miny+$this->user->rolle->pixsize*($this->user->rolle->nImageHeight-$ru[1]); # y Wert
				$maxx=$minx+$width;
				$maxy=$miny+$height;
				$rect = rectObj($minx,$miny,$maxx,$maxy);
			}
			else{
				$polygon = 'POLYGON((';
				for($i = 0; $i < count($corners); $i++){
					$coord = explode(',',$corners[$i]);
					$coordx[$i] = $coord[0];
					$coordy[$i] = $coord[1];
					$polygon .= $coordx[$i].' '.$coordy[$i].',';
				}
				$polygon .= $coordx[0].' '.$coordy[0].'))';
				$rect = $polygon;
			}
			return $rect;
		}
	}

	function tooltip_query($rect){
		$showdata = 'true';
    $this->mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->queryrect = $rect;
		if ($this->formvars['querylayer_id'] != '' AND $this->formvars['querylayer_id'] != 'undefined') {
			if ($this->formvars['querylayer_id'] < 0) {
				$layerset=$this->user->rolle->getRollenLayer(-$this->formvars['querylayer_id']);
			}
			else {
				$layerset = $this->user->rolle->getLayer($this->formvars['querylayer_id']);
			}
		}
		else{
			$layerset = $this->user->rolle->getLayer('');
		}
    $anzLayer=count($layerset);
		$disabled_class_expressions = $this->user->rolle->read_disabled_class_expressions($layerset);
    #$map = new MapObj('');
    #$map->shapepath = SHAPEPATH;
		$found = false;
		$queryfield = ($this->user->rolle->singlequery == 2? 'thema' : 'qLayer');
    for ($i=0;$i<$anzLayer;$i++) {
			if ($found)break;		# wenn in einem Layer was gefunden wurde, abbrechen
			if ($layerset[$i]['connectiontype'] == 6 AND
					$layerset[$i]['queryable'] AND
					(
						(	# Karte
							(
								$this->formvars[$queryfield][$layerset[$i]['layer_id']] == '1' OR
								$this->formvars[$queryfield][$layerset[$i]['requires']] == '1'
							) AND
							($layerset[$i]['maxscale'] == 0 OR $layerset[$i]['maxscale'] >= $this->map_scaledenom) AND 
							($layerset[$i]['minscale'] == 0 OR $layerset[$i]['minscale'] <= $this->map_scaledenom)
						)
						OR
						(	# Datensatz
							$this->formvars['querylayer_id'] != ''
						)
					)
				) {
				# Dieser Layer soll abgefragt werden
				$layerdb = $this->mapDB->getlayerdatabase($layerset[$i]['layer_id'], $this->Stelle->pgdbhost);				
				$privileges = $this->Stelle->get_attributes_privileges($layerset[$i]['layer_id']);
				$layerset[$i]['attributes'] = $this->mapDB->read_layer_attributes($layerset[$i]['layer_id'], $layerdb, $privileges['attributenames']);

				if ($layerset[$i]['maintable'] == '') {		# ist z.B. bei Rollenlayern der Fall
					$layerset[$i]['maintable'] = $layerset[$i]['attributes']['table_name'][$layerset[$i]['attributes']['the_geom']];
				}
				if ($layerset[$i]['oid'] == '' AND count_or_0($layerset[$i]['attributes']['pk']) == 1) {		# ist z.B. bei Rollenlayern der Fall
					$layerset[$i]['oid'] = $layerset[$i]['attributes']['pk'][0];
				}
				$query_parts = $this->mapDB->getQueryParts($layerset[$i], $privileges);
				$pfad = $query_parts['query'];
				
				if($rect->minx != ''){	####### Kartenabfrage
					$show = false;
					for($j = 0; $j < count_or_0($layerset[$i]['attributes']['name']); $j++){
						$layerset[$i]['attributes']['tooltip'][$j] = $privileges['tooltip_'.$layerset[$i]['attributes']['name'][$j]];
						if($layerset[$i]['attributes']['tooltip'][$j] == 1){
							$show = true;
						}
					}
					if(!$show){
						continue;
					}
				}
				$the_geom = $layerset[$i]['attributes']['the_geom'];
				# Aktueller EPSG in der die Abfrage ausgeführt wurde
				$client_epsg=$this->user->rolle->epsg_code;
				# EPSG-Code des Layers der Abgefragt werden soll
				$layer_epsg=$layerset[$i]['epsg_code'];

				if($rect->minx != ''){		################ Kartenabfrage ################
					switch ($layerset[$i]['toleranceunits']) {
						case 'pixels' : $pixsize=$this->user->rolle->pixsize; break;
						case 'meters' : $pixsize=1; break;
						default : $pixsize=$this->user->rolle->pixsize;
					}
					$rand=$layerset[$i]['tolerance']*$pixsize;
					# Bildung der Where-Klausel für die räumliche Abfrage mit der searchbox
					$loosesearchbox_wkt ="POLYGON((";
					$loosesearchbox_wkt.=strval($rect->minx-$rand)." ".strval($rect->miny-$rand).",";
					$loosesearchbox_wkt.=strval($rect->maxx+$rand)." ".strval($rect->miny-$rand).",";
					$loosesearchbox_wkt.=strval($rect->maxx+$rand)." ".strval($rect->maxy+$rand).",";
					$loosesearchbox_wkt.=strval($rect->minx-$rand)." ".strval($rect->maxy+$rand).",";
					$loosesearchbox_wkt.=strval($rect->minx-$rand)." ".strval($rect->miny-$rand)."))";

					# Wenn das Koordinatenssystem des Views anders ist als vom Layer wird die Suchbox und die Suchgeometrie
					# in epsg des layers transformiert
					if ($client_epsg!=$layer_epsg) {
						$sql_where =" AND ".$the_geom." && st_transform(st_geomfromtext('".$loosesearchbox_wkt."',".$client_epsg."),".$layer_epsg.")";
					}
					else {
						$sql_where =" AND ".$the_geom." && st_geomfromtext('".$loosesearchbox_wkt."',".$client_epsg.")";
					}

					# Wenn es sich bei der Suche um eine punktuelle Suche handelt, wird die where Klausel um eine
					if($rect->minx==$rect->maxx AND $rect->miny==$rect->maxy AND $this->querypolygon == ''){
						if ($client_epsg!=$layer_epsg) {
							$sql_where.=" AND st_distance(".$the_geom.",st_transform(st_geomfromtext('POINT(".$rect->minx." ".$rect->miny.")',".$client_epsg."),".$layer_epsg."))";
						}
						else {
							$sql_where.=" AND st_distance(".$the_geom.",st_geomfromtext('POINT(".$rect->minx." ".$rect->miny.")',".$client_epsg."))";
						}
						$sql_where.=" <= ".$rand;
					}
				}
				else{		################ mouseover auf Datensatz in Sachdatenanzeige ################
					$showdata = 'false';
					$sql_where = " AND ".pg_quote($layerset[$i]['maintable'].'_oid')." = '" . $this->formvars['oid'] . "'";
				}

				# SVG-Geometrie abfragen für highlighting
				if($layerset[$i]['attributes']['geomtype'][$the_geom] != 'POINT'){
					$rand = $this->map_scaledenom/1000;
					$tolerance = $this->map_scaledenom/10000;
					if($client_epsg == 4326){
						$tolerance = $tolerance / 60000;		# wegen der Einheit Grad
						$rand = $rand / 60000;		# wegen der Einheit Grad
					}
					$box_wkt ="POLYGON((";
					$box_wkt.=strval($this->user->rolle->oGeorefExt->minx-$rand)." ".strval($this->user->rolle->oGeorefExt->miny-$rand).",";
					$box_wkt.=strval($this->user->rolle->oGeorefExt->maxx+$rand)." ".strval($this->user->rolle->oGeorefExt->miny-$rand).",";
					$box_wkt.=strval($this->user->rolle->oGeorefExt->maxx+$rand)." ".strval($this->user->rolle->oGeorefExt->maxy+$rand).",";
					$box_wkt.=strval($this->user->rolle->oGeorefExt->minx-$rand)." ".strval($this->user->rolle->oGeorefExt->maxy+$rand).",";
					$box_wkt.=strval($this->user->rolle->oGeorefExt->minx-$rand)." ".strval($this->user->rolle->oGeorefExt->miny-$rand)."))";
					$query_parts['select'] .= ", st_assvg(st_transform(st_simplify(st_intersection(" . $the_geom . ", st_transform(st_geomfromtext('".$box_wkt."',".$client_epsg."), ".$layer_epsg.")), ".$tolerance."), ".$client_epsg."), 0, 15) AS highlight_geom";
				}
				else{
					$buffer = $this->map_scaledenom/260;
					$query_parts['select'] .= ", st_assvg(st_buffer(st_transform(" . $the_geom . ", ".$client_epsg."), ".$buffer."), 0, 15) AS highlight_geom";
				}

				if ($layerset[$i]['filter'] != '') {
					$layerset[$i]['filter'] = replace_params_rolle($layerset[$i]['filter']);
					$sql_where .= " AND " . $layerset[$i]['filter'];
				}
				# Filter auf Grund von ausgeschalteten Klassen hinzufügen
				if (QUERY_ONLY_ACTIVE_CLASSES AND array_key_exists($layerset[$i]['layer_id'], $disabled_class_expressions)) {
					foreach($disabled_class_expressions[$layerset[$i]['layer_id']] as $disabled_class) {
						$disabled_class_filter[$layerset[$i]['layer_id']][] = '(' . (mapserverExp2SQL($disabled_class['expression'], $layerset[$i]['classitem']) ?: 'true') . ')';
					}
					$sql_where .= " AND COALESCE(NOT (" . implode(' OR ', $disabled_class_filter[$layerset[$i]['layer_id']]) . "), true)";
				}	
								
				$sql = "SELECT " . $query_parts['select'] . " FROM (SELECT " . $pfad . ") as query WHERE 1=1 ".$sql_where;

				# order by wieder einbauen
				if ($query_parts['orderby'] != ''){										#  der Layer hat im Pfad ein ORDER BY
					$sql .= $query_parts['orderby'];
				}

				# Anhängen des Begrenzers zur Einschränkung der Anzahl der Ergebniszeilen
				$sql_limit =' LIMIT '.($layerset[$i]['max_query_rows'] ?: MAXQUERYROWS);

				#echo '<br>sql:<br>'.$sql;
				$ret=$layerdb->execSQL($sql.$sql_limit,4, 0);
				if (!$ret[0]) {
					while ($rs=pg_fetch_assoc($ret[1])) {
						$found = true;
						$layerset[$i]['shape'][]=$rs;
					}
				}

				# Hier nach der Abfrage der Sachdaten die weiteren Attributinformationen hinzufügen
				# Steht an dieser Stelle, weil die Auswahlmöglichkeiten von Auswahlfeldern abhängig sein können
				$layerset[$i]['attributes'] = $this->mapDB->add_attribute_values($layerset[$i]['attributes'], $layerdb, $layerset[$i]['shape'], true, $this->Stelle->id);

				if($found)$this->qlayerset[]=$layerset[$i];
			}
    } # ende der Schleife zur Abfrage der Layer der Stelle
    # Tooltip-Abfrage
    if($found){
      for($i = 0; $i < count($this->qlayerset); $i++) {
      	$layer = $this->qlayerset[$i];
				$output .= $layer['Name_or_alias'].' : || ';
 				$attributes = $layer['attributes'];
        $anzObj = count($layer['shape']);
        for($k = 0; $k < $anzObj; $k++) {
          $attribcount = 0;
					$links = '';
					$pictures = '';
					$highlight_geom .= $layer['shape'][$k]['highlight_geom'].' ';
          for ($j = 0; $j < count($attributes['name']); $j++){
            if ($attributes['tooltip'][$j]){
							if ($attributes['alias'][$j] == '') {
								$attributes['alias'][$j] = $attributes['name'][$j];
							}
							if (substr($attributes['type'][$j], 0, 1) == '_'){
								$values = json_decode($layer['shape'][$k][$attributes['name'][$j]]);
							}
							else {
								$values = array($layer['shape'][$k][$attributes['name'][$j]]);
							}
							if(is_array($values)){
								foreach($values as $value){
									switch ($attributes['form_element_type'][$j]){
										case 'Dokument' : {
											$preview = $this->get_dokument_vorschau($value, $layer['document_path'], $layer['document_url']);
											$pictures .= '| ' . ($preview['doc_type'] == 'videostream' ? $preview['doc_src'] : $preview['thumb_src']);
										}break;
										case 'Link': {
											$attribcount++;
											if($value!='') {
												$link = 'xlink:'.$value;
												$links .= $link.'##';
											}
										} break;
										case 'Auswahlfeld': {
											$auswahlfeld_output = '';
											if(is_array($attributes['dependent_options'][$j])){		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
												$auswahlfeld_output = $attributes['enum'][$j][$k][$value]['output'];
											}
											else{
												$auswahlfeld_output = $attributes['enum'][$j][$value]['output'];
											}
											$output .=  $attributes['alias'][$j].': ';
											$output .= $auswahlfeld_output;
											$output .= '##';
											$attribcount++;
										} break;
										case 'Radiobutton': {
											$radiobutton_output = $attributes['enum'][$j][$value]['output'];
											$output .=  $attributes['alias'][$j].': ';
											$output .= $radiobutton_output;
											$output .= '##';
											$attribcount++;
										} break;
										case 'Checkbox': {
											$output .=  $attributes['alias'][$j].': ';
											$value = str_replace('f', 'nein',  $value);
											$value = str_replace('t', 'ja',  $value);
											$output .= $value.'  ';
											$output .= '##';
										} break;
										default : {
											if (is_scalar($value ?? '')) {
												$value = strval($value);
											}
											else {
												$value = json_encode($value);
											}
											$output .=  $attributes['alias'][$j].': ';
											$attribcount++;
											$value = str_replace(chr(10), '##',  $value);
											$output .= $value . '  ';
											$output .= '##';
										}
									}
								}
							}
            }
          }
          # Links und Bild-URLs anfügen
          $output .= $links;
      		$output .= $pictures;
      		$pictures = '';
          $output .= '|| ';
        }
      }
      # highlighting-Geometrie anfügen
      $output .= '||| '.$highlight_geom;
      echo umlaute_javascript(umlaute_html($output)).'█root.showtooltip(root.document.GUI.result.value, '.$showdata.');';
    }
  }

	function create_dokument_vorschau($doc_type, $pathinfo) {
		if ($doc_type == 'local_img') {
			# für lokale Bilder und PDFs werden automatisch Thumbnails erzeugt
			$thumbname = $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '_thumb.jpg';
			if (!file_exists($thumbname)) {
				$command = IMAGEMAGICKPATH . 'convert -filter Hanning "' . $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '.' . $pathinfo['extension'] . '"[0] -quality 75 -background white -flatten -resize ' . PREVIEW_IMAGE_WIDTH . 'x1000\> "' . $thumbname . '"';
				#echo 'Erzeuge Thumbnail mit commando: ' . $command;
				exec($command);
			}
		}
		else {
			#	alle anderen Dokumenttypen oder Dateien auf fremden Servern bekommen entsprechende Dokumentensymbole als Vorschaubild
			$image = imagecreatefromgif(GRAPHICSPATH.'document.gif');
			$blue = ImageColorAllocate ($image, 26, 87, 150);
			if (strlen(strtolower($pathinfo['extension'])) > 3) {
				$xoffset = 4;
			}
			imagettftext($image, 12, 0, 23-$xoffset, 34, $blue, WWWROOT.APPLVERSION.'fonts/SourceSansPro-Semibold.ttf', $pathinfo['extension']);
			$thumbname = IMAGEPATH.rand(0,100000).'.gif';
			imagegif($image, $thumbname);
		}
		return $thumbname;
	}

	function get_dokument_vorschau($value, $document_path, $document_url) {
		$doc_src = $doc_type = $thumb_src = $original_name = $target = $filesize = '';

		$pfadteil = explode('&original_name=', $value);
		$dateipfad = $pfadteil[0];
		$pathinfo = pathinfo($dateipfad);
		$type = strtolower($pathinfo['extension']);

		if ($document_url != '') {
			if (in_array($type, array('mp4'))) {
				$doc_type = 'videostream';
			}
			else if (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) != parse_url($document_url, PHP_URL_HOST)) {
				# die URL verweist auf einen anderen Server
				$doc_type = 'remote_url';
			}
			$dateipfad = url2filepath($dateipfad, $document_path, $document_url);
		}
		if (file_exists($dateipfad) OR $doc_type != '') {
			$pathinfo = pathinfo($dateipfad);
			if ($doc_type == '') {
				$type = strtolower($pathinfo['extension']);
				if (in_array($type, array('jpg', 'png', 'gif', 'tif', 'pdf'))) {
					$doc_type = 'local_img';
				}
				else {
					$doc_type = 'local_doc';
				}
			}

			$thumbname = $this->create_dokument_vorschau($doc_type, $pathinfo);

			if ($document_url != '') {
				$original_name = basename($dateipfad);
				$doc_src = $value; # URL zu der Datei (komplette URL steht schon in $value)
				$target = 'target="_blank"';
				if (dirname($thumbname).'/' == IMAGEPATH){
					$thumbname = IMAGEURL.basename($thumbname);
				}
				else {
					$thumbname = dirname($value).'/'.basename($thumbname);
				}
				$thumb_src = $thumbname;
			}
			else {
				$original_name = $pfadteil[1];
				$this->allowed_documents[] = addslashes($dateipfad);
				$this->allowed_documents[] = addslashes($thumbname);
				$url = IMAGEURL . $this->document_loader_name . '?dokument=';
				$doc_src = $url . $value;
				$thumb_src = $url . $thumbname;
			}
			$filesize = human_filesize($dateipfad);
		}

		return array(
			'doc_src' => $doc_src,
			'doc_type' => $doc_type,
			'thumb_src' => $thumb_src,
			'original_name' => $original_name,
			'target' => $target,
			'filesize' => $filesize
		);
	}

	function write_document_loader(){
		$handle = fopen(IMAGEPATH.$this->document_loader_name, 'w');
		$code = '<?
			error_reporting(E_ALL & ~(E_STRICT|E_NOTICE));
			$allowed_documents = array(\''.implode('\',\'', $this->allowed_documents).'\');
			if(in_array($_REQUEST[\'dokument\'], $allowed_documents)){
				if(!array_key_exists(\'original_name\', $_REQUEST))$_REQUEST[\'original_name\'] = basename($_REQUEST[\'dokument\']);
				$type = strtolower(array_pop(explode(\'.\', $_REQUEST[\'dokument\'])));
				if(in_array($type, array(\'jpg\', \'gif\', \'png\')))header("Content-type: image/" . $type);
				else header("Content-type: application/" . $type);
				header("Content-Disposition: attachment; filename=\"" . $_REQUEST[\'original_name\']."\"");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Pragma: public");
				readfile($_REQUEST[\'dokument\']);
			}
		?>';
		fwrite($handle, $code);
		fclose($handle);
	}
}

class user {
	# // TODO: Beim Anlegen eines neuen Benutzers müssen die Einstellungen für die Karte aus der Stellenbeschreibung als Anfangswerte übernommen werden

	var $id;
	var $Name;
	var $Vorname;
	var $login_name;
	var $funktion;
	var $dbConn; # Datenbankverbindungskennung
	var $Stellen;
	var $nZoomFactor;
	var $nImageWidth;
	var $nImageHeight;
	var $database;
	var $remote_addr;
	var $has_logged_in;
	var $language = 'german';
	var $debug;
	var $share_rollenlayer_allowed;

	/**
	 * Create a user object
	 * if only login_name is defined, find_by login_name only
	 * if login_name and password is defined, find_by login_name and password
	*/
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
					(
						('" . date('Y-m-d h:i:s') . "' >= s.start OR s.start IS NULL) AND 
						('" . date('Y-m-d h:i:s') . "' <= s.stop OR s.stop IS NULL)
					)
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
		$rolle=new rolle($this->id,$stelle_id,$this->database);		
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

	function __construct($id,$database) {
		global $debug;
		$this->debug=$debug;
		$this->id=$id;
		$this->database=$database;
		$this->Bezeichnung=$this->getName();
		$this->readDefaultValues();
	}
	
  function getName() {
    $sql ='SELECT ';
    if (rolle::$language != 'german' AND rolle::$language != ''){
      $sql.='bezeichnung_'.rolle::$language.' AS ';
    }
    $sql.='bezeichnung FROM kvwmap.stelle WHERE id = '.$this->id;
    #echo $sql;
    $this->debug->write("<p>file:stelle.php class:stelle->getName - Abfragen des Namens der Stelle:<br>".$sql,4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0;
		}
		$rs = pg_fetch_array($ret[1]);
    $this->Bezeichnung = $rs['bezeichnung'];
    return $rs['bezeichnung'];
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
				stop, minxmax, minymax, maxxmax, maxymax, epsg_code, referenzkarte_id, Authentifizierung, ALB_status, wappen, wappen_link,
				ows_namespace,
				ows_title,
				wms_accessconstraints,
				ows_abstract,
				ows_updatesequence,
				ows_geographicdescription,
				ows_fees,
				ows_inspireidentifiziert,
				ows_srs,

				ows_contactorganization,
				ows_contacturl,
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
				ows_distributionurl,
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
				ows_contenturl,
				ows_contentaddress,
				ows_contentpostalcode,
				ows_contentcity,
				ows_contentadministrativearea,
				ows_contentemailaddress,
				ows_contentperson,
				ows_contentposition,
				ows_contentvoicephone,
				ows_contentfacsimile,

				protected, check_client_ip::int, check_password_age, allowed_password_age, use_layer_aliases, selectable_layer_params, hist_timestamp, default_user_id,
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
		$this->ows_inspireidentifiziert = $rs['ows_inspireidentifiziert'];
		$this->ows_srs = preg_replace(array('/: +/', '/ +:/'), ':', $rs['ows_srs']);

		$this->ows_contactorganization = $rs['ows_contactorganization'];
		$this->ows_contacturl = $rs['ows_contacturl'];
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
		$this->ows_distributionurl = $rs['ows_distributionurl'];
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
		$this->ows_contenturl = $rs['ows_contenturl'];
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

	function get_attributes_privileges($layer_id) {
		if ($layer_id > 0) {
			$sql = "
				SELECT
					attributename,
					privileg,
					tooltip
				FROM
					kvwmap.layer_attributes2stelle
				WHERE
					stelle_id = " . $this->id . " AND
					layer_id = " . $layer_id;
		}
		else {
			$sql = "
				SELECT 
					name as attributename,
					0 as privileg
				FROM 
					kvwmap.layer_attributes 
				WHERE 
					layer_id = " . $layer_id;
		}
		// echo '<br>Sql: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->get_attributes_privileges - Abfragen der Layerrechte zur Stelle:<br>" . $sql, 4);
		$ret = $this->database->execSQL($sql);
		while ($rs = pg_fetch_array($ret[1])) {
			$privileges[$rs['attributename']] = $rs['privileg'];
			$privileges['tooltip_' . $rs['attributename']] = $rs['tooltip'];
			$privileges['attributenames'][] = $rs['attributename'];
		}
		return $privileges;
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

	function read_disabled_class_expressions($layerset) {
		$sql = "
			SELECT 
				cl.layer_id,
				cl.class_id,
				cl.expression,
				cl.classification
			FROM 
				kvwmap.classes as cl
				JOIN kvwmap.u_rolle2used_class as r2uc ON r2uc.class_id = cl.class_id
			WHERE 
				r2uc.status = 0 AND 
				r2uc.user_id = " . $this->user_id . "	AND 
				r2uc.stelle_id = " . $this->stelle_id . "
		";
		#echo '<p>SQL zur Abfrage von diabled classes: ' . $sql;
		$ret = $this->database->execSQL($sql);
    while ($row = pg_fetch_assoc($ret[1])) {
			if ($layerset['layer_ids'][$row['layer_id']]['classification'] == $row['classification']) {
  			$result[$row['layer_id']][] = $row;
			}
		}
		return $result ?: [];
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
				ul.legendorder,
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
	* @param integer, $connection_id The id of the connection defined in connections table, if 0 default connection will be used
	* @return boolean, True if success or set an error message in $this->err_msg and return false when fail to find the credentials or open the connection
	*/
  function open($connection_id = 0, $flag = NULL) {
		$this->debug->write("Open Database connection with connection_id: " . $connection_id, 4);
		$this->connection_id = $connection_id;
		$connection_string = $this->get_connection_string();
		try {
			$this->dbConn = pg_connect($connection_string . ' connect_timeout=5', $flag);
		}
		catch (Exception $e) {
			$this->err_msg = 'Die Verbindung zur PostGIS-Datenbank konnte mit folgenden Daten nicht hergestellt werden connection_id: ' . $connection_id . ' '
				. implode(' ' , array_filter(explode(' ', $connection_string), function($part) { return strpos($part, 'password') === false; })) . '<br>Exception: ' . $e;
			return false;
		}

		if (!$this->dbConn) {
			$this->err_msg = 'Die Verbindung zur PostGIS-Datenbank konnte mit folgenden Daten nicht hergestellt werden connection_id: ' . $connection_id . ' '
				. implode(' ' , array_filter(explode(' ', $connection_string), function($part) { return strpos($part, 'password') === false; }));
			return false;
		}
		else {
			$this->debug->write("Database connection successfully opend.", 4);
			$this->setClientEncodingAndDateStyle();
			$this->connection_id = $connection_id ?: POSTGRES_CONNECTION_ID;
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
		if ($connection_id == 0) {
			return $this->get_object_credentials();
		}
		else {
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

	function get_connection_string($bash_escaping = false) {
		$connection_string = $this->format_pg_connection_string($this->get_credentials($this->connection_id));
		if ($bash_escaping) {
			$connection_string = str_replace('$', '\$', $connection_string);
		}
		return $connection_string;
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
      #echo "<br>".$sql;
      if($this->schema != ''){
      	$sql = "SET search_path = ".$this->schema.", public;".$sql;
      }
      $query=pg_query($this->dbConn,$sql);
      //$query=0;
      if ($query==0) {
				$ret['success'] = false;
				$errormessage = pg_last_error($this->dbConn);
				header('error: true');		// damit ajax-Requests das auch mitkriegen
        $ret[0]=1;
        $ret[1]="Fehler bei SQL Anweisung:<br><br>\n\n".$sql."\n\n<br><br>".$errormessage;
        echo "<br><b>".$ret[1]."</b>";
        $this->debug->write("<br><b>".$ret[1]."</b>",$debuglevel);
        if ($logsql) {
          $this->logfile->write($this->commentsign." ".$ret[1]);
        }
      }
      else {
      	# Abfrage wurde erfolgreich ausgeführt
        $ret[0]=0;
        $ret['success'] = true;
				$ret[1] = $query;
				$ret['query'] = $ret[1];
        $this->debug->write("<br>".$sql,$debuglevel);
        # 2006-07-04 pk $logfile ersetzt durch $this->logfile
        if ($logsql) {
          $this->logfile->write($sql.';');
        }
      }
      $ret[2]=$sql;
    }
    else {
      # Es werden keine SQL-Kommandos ausgeführt
      # Die Funktion liefert ret[0]=0, und zeigt damit an, daß kein Datenbankfehler aufgetreten ist,
      $ret[0]=0;
      # jedoch hat $ret[1] keine query_ID sondern auch den Wert 0
      $ret[1]=0;
      # Wenn $this->loglevel != 0 wird die sql-Anweisung in die logdatei geschrieben
      # zusätzlich immer in die debugdatei
      # 2006-07-04 pk $logfile ersetzt durch $this->logfile
      if ($logsql) {
        $this->logfile->write($sql.';');
      }
      $this->debug->write("<br>".$sql,$debuglevel);
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
	var $disabled_classes;

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

	function getlayerdatabase($layer_id, $host) {
		#echo '<br>GUI->getlayerdatabase layer_id: ' . $layer_id;
		$layerdb = new pgdatabase();
		$rs = $this->get_layer_connection($layer_id);
		if (count_or_0($rs) == 0) {
			return null;
		}
		$rs['schema'] = replace_params_rolle($rs['schema']);
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
	
	function getQueryParts($layerset, $privileges){
		$path = $layerset['pfad'];

		foreach ($layerset['attributes']['name'] as $i => $attributename) {
			if (value_of($privileges, $attributename) != '') {
				$type = $layerset['attributes']['type'][$i];
				if (POSTGRESVERSION >= 930 AND substr($type, 0, 1) == '_' OR is_numeric($type)) {
					$newattributesarray[] = 'to_json(' . $attributename . ')::text as ' . $attributename;								# Array oder Datentyp
				}
				else {
					$newattributesarray[] = pg_quote($attributename);					# normal
				}
			}
		}
		$lastwhereposition = strrpos(strtolower($path), 'where');
		# order by rausnehmen
		$orderbyposition = strrpos(strtolower($path), 'order by');
		if($orderbyposition !== false AND $orderbyposition > $lastwhereposition){
			$orderby = ' '.substr($path, $orderbyposition);
			$path = substr($path, 0, $orderbyposition);
		}

		# group by rausnehmen
		$groupbyposition = strrpos(strtolower($path), 'group by');
		if($groupbyposition !== false AND $groupbyposition > $lastwhereposition){
			$layerset['attributes']['groupby'] = ' '.substr($path, $groupbyposition);
			$path = substr($path, 0, $groupbyposition);
		}

		$distinctpos = strpos(strtolower($path), 'distinct');
		if($distinctpos !== false && $distinctpos < 10){
			$pfad = substr(trim($path), $distinctpos+8);
			$distinct = true;
		}
		else{
			$pfad = substr(trim($path), 7);
		}
		if ($layerset['maintable'] != '' AND $layerset['oid'] != '') {
			$pfad = pg_quote($layerset['attributes']['table_alias_name'][$layerset['maintable']] ?: $layerset['maintable']).'.'.$layerset['oid'].' AS ' . pg_quote($layerset['maintable'] . '_oid').', ' . $pfad;
			$newattributesarray[] = pg_quote($layerset['maintable'] . '_oid');
		}
		if ($distinct == true) {
			$pfad = 'DISTINCT ' . $pfad;
		}

		$the_geom = $layerset['attributes']['the_geom'];
				
		# group by wieder einbauen
		if($layerset['attributes']['groupby'] != ''){
			$pfad .= $layerset['attributes']['groupby'];
			$j = 0;
			foreach($layerset['attributes']['all_table_names'] as $tablename){
				if($tablename == $layerset['maintable'] AND $layerset['oid'] != ''){		# hat Haupttabelle oids?
					$pfad .= ','.pg_quote($tablename.'_oid').' ';
				}
				$j++;
			}
		}
		return [
				'select' => implode(', ', $newattributesarray),
				'query' => $pfad, 
				'orderby' => $orderby
			];
	}

  function read_layer_attributes($layer_id, $layerdb, $attributenames, $all_languages = false, $recursive = false, $get_default = false, $replace = true, $replace_only = array('default', 'options', 'vcheck_value'), $attribute_values = []) {
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
				vcheck_attribute,
				vcheck_operator,
				vcheck_value,
				\"order\",
				privileg,
				query_tooltip
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
			$attributes['vcheck_attribute'][$i] = $rs['vcheck_attribute'];
			$attributes['vcheck_operator'][$i] = $rs['vcheck_operator'];
			$attributes['vcheck_value'][$i] = $rs['vcheck_value'];
			$attributes['dependents'][$i] = &$dependents[$rs['name']];
			$dependents[$rs['vcheck_attribute']][] = $rs['name'];			

			if ($replace) {
				foreach($replace_only AS $column) {
					if ($attributes[$column][$i] != '') {
						$attributes[$column][$i] = 	replace_params_rolle(
																					$attributes[$column][$i],
																					((count($attribute_values) > 0 AND $replace_only == 'default') ? $attribute_values : NULL)
																				);
					}
				}
			}

			if ($get_default AND $attributes['default'][$i] != '') {
				# da Defaultvalues auch dynamisch sein können (z.B. 'now'::date) wird der Defaultwert erst hier ermittelt
				$ret1 = $layerdb->execSQL('SELECT ' . $attributes['default'][$i], 4, 0);
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

	function add_attribute_values($attributes, $database, $query_result, $withvalues, $stelle_id, $all_options = false, $with_requires_options = false) {
		$attributes['req_by'] = $attributes['requires'] = $attributes['enum_requires_value'] = array();
		# Diese Funktion fügt den Attributen je nach Attributtyp zusätzliche Werte hinzu. Z.B. bei Auswahlfeldern die Auswahlmöglichkeiten.
		for ($i = 0; $i < count_or_0($attributes['name']); $i++) {
			$type = ltrim($attributes['type'][$i], '_');
			if (is_numeric($type) AND $query_result != NULL) {			# Attribut ist ein Datentyp
				$query_result2 = array();
				foreach ($query_result as $k => $record) {	# bei Erfassung eines neuen DS hat $k den Wert -1
					$json = str_replace('}"', '}', str_replace('"{', '{', str_replace("\\", "", $query_result[$k][$attributes['name'][$i]])));	# warum diese Zeichen dort reingekommen sind, ist noch nicht klar...
					@$datatype_query_result = json_decode($json, true);
					if ($attributes['type'][$i] != $type) {
						$datatype_query_result = $datatype_query_result[0];		# falls das Attribut ein Array von Datentypen ist, behelfsweise erstmal nur das erste Array-Element berücksichtigen
					}
					$query_result2[$k] = $datatype_query_result;
				}
				$attributes['type_attributes'][$i] = $this->add_attribute_values($attributes['type_attributes'][$i], $database, $query_result2, $withvalues, $stelle_id, $only_current_enums);
			}
			if (
				$attributes['options'][$i] == '' AND
				$attributes['constraints'][$i] != '' AND
				!in_array($attributes['constraints'][$i], array('PRIMARY KEY', 'UNIQUE'))
			) {	# das sind die Auswahlmöglichkeiten, die durch die Tabellendefinition in Postgres fest vorgegeben sind
				$explosion = explode("','", trim($attributes['constraints'][$i], "'"));
				foreach ($explosion as $option) {
					$attributes['enum'][$i][$option]['output'] = $option;
				}
			}
			if ($withvalues == true) {
				switch ($attributes['form_element_type'][$i]) {
					# Auswahlfelder
					case 'Auswahlfeld' : case 'Auswahlfeld_Bild' : {
						if ($attributes['options'][$i] != '') {
							# das sind die Auswahlmöglichkeiten, die man im Attributeditor selber festlegen kann
							if (strpos($attributes['options'][$i], "'") === 0) {
								# Aufzählung wie 'wert1','wert2','wert3'
								$explosion = explode("','", substr(str_replace(["', ", chr(10), chr(13)], ["',", '', ''], $attributes['options'][$i]), 1, -1));
								foreach ($explosion as $option) {
									$attributes['enum'][$i][$option]['output'] = $option;
								}
							}
							elseif (strpos(strtolower($attributes['options'][$i]), "select") === 0) {
								# SQL-Abfrage wie select attr1 as value, atrr2 as output from table1
								$optionen = explode(';', $attributes['options'][$i]);	# SQL; weitere Optionen // get_options
								# --------- weitere Optionen -----------
								if (value_of($optionen, 1) != '') {
									# die weiteren Optionen exploden (opt1 opt2 opt3)
									$further_options = explode(' ', $optionen[1]);
									for ($k = 0; $k < count($further_options); $k++) {
										if (strpos($further_options[$k], 'layer_id') !== false) {
											#layer_id=XX bietet die Möglichkeit hier eine Layer_ID zu definieren, für die man einen neuen Datensatz erzeugen kann
											$attributes['subform_layer_id'][$i] = array_pop(explode('=', $further_options[$k]));
											$layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
											$attributes['subform_layer_privileg'][$i] = $layer['privileg'];
										}
										elseif (trim($further_options[$k]) == 'embedded') {
											# Subformular soll embedded angezeigt werden
											$attributes['embedded'][$i] = true;
										}
									}
								}
								# --------- weitere Optionen -----------
								if ($attributes['subform_layer_id'][$i] != '' AND $layer['oid'] != '') {
									 # auch die oid abfragen
									 $attributes['options'][$i] = str_replace(' from ', ', ' . $layer['oid'] . ' as oid from ', strtolower($optionen[0]));
								}
								# ------------ SQL ---------------------
								else {
									$attributes['options'][$i] = $optionen[0];
								}
								# ------<required by>------
								$req_by_start = strpos(strtolower($attributes['options'][$i]), "<required by>");
								if ($req_by_start > 0) {
									$req_by_end = strpos(strtolower($attributes['options'][$i]), "</required by>");
									$req_by = trim(substr($attributes['options'][$i], $req_by_start + 13, $req_by_end - $req_by_start - 13));
									$attributes['req_by'][$i] = $req_by; # das abhängige Attribut
									$attributes['options'][$i] = substr($attributes['options'][$i], 0, $req_by_start); # required-Tag aus SQL entfernen
								}
								# ------<required by>------
								# -----<requires>------
								if (strpos(strtolower($attributes['options'][$i]), "<requires>") > 0) {
									if ($all_options) {
										# alle Auswahlmöglichkeiten -> where abschneiden
										foreach ($attributes['name'] as $attributename) {
											if (strpos($attributes['options'][$i], '<requires>' . $attributename . '</requires>') !== false) {
												$attributes['req'][$i][] = $attributename; # die Attribute, die in <requires>-Tags verwendet werden zusammen sammeln
											}
										}
										$attributes['options'][$i] = substr($attributes['options'][$i], 0, stripos($attributes['options'][$i], 'where'));
									}
									else {
										if ($query_result != NULL) {
											$attributes['options'][$i] = str_replace('=<requires>', '= <requires>', $attributes['options'][$i]);
											foreach ($attributes['name'] as $attributename) {
												if (strpos($attributes['options'][$i], '<requires>' . $attributename . '</requires>') !== false) {
													$attributes['req'][$i][] = $attributename; # die Attribute, die in <requires>-Tags verwendet werden zusammen sammeln
												}
											}
											foreach ($query_result as $k => $record) { # bei Erfassung eines neuen DS hat $k den Wert -1
												$options = $attributes['options'][$i];
												foreach ($attributes['req'][$i] as $attributename) {
													if ($query_result[$k][$attributename] != '') {
														if (is_array($query_result[$k][$attributename])) {
															$query_result[$k][$attributename] = implode("','", $query_result[$k][$attributename]);
														}
														$options = str_replace('= <requires>' . $attributename.'</requires>',	" IN ('" . $query_result[$k][$attributename] . "')", $options);
														$options = str_replace('<requires>'.$attributename.'</requires>', "'".$query_result[$k][$attributename]."'", $options);	# fallback
													}
												}
												if (strpos($options, '<requires>') !== false) {
													#$options = '';		# wenn in diesem Datensatz des Query-Results ein benötigtes Attribut keinen Wert hat (also nicht alle <requires>-Einträge ersetzt wurden), sind die abhängigen Optionen für diesen Datensatz leer
													$attribute_value = $query_result[$k][$attributes['name'][$i]];
													if ($attribute_value != '') {
														$options = "select '" . $attribute_value . "' as value, '" . $attribute_value . "' as output";
													}
													else {
														$options = '';
														# wenn in diesem Datensatz des Query-Results ein benötigtes Attribut keinen Wert hat (also nicht alle <requires>-Einträge ersetzt wurden) aber das eigentliche Attribut einen Wert hat, wird dieser Wert als value und output genommen, ansonsten sind die Optionen leer
													}
												}
												$attributes['dependent_options'][$i][$k] = $options;
											}
										}
										else {
											$attributes['options'][$i] = '';			# wenn kein Query-Result übergeben wurde, sind die Optionen leer
										}
									}
								}
								# -----<requires>------
								if (
									value_of($attributes, 'dependent_options') AND
									is_array($attributes['dependent_options'][$i])
								) {
									# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
									foreach ($query_result as $k => $record) {
										# bei Erfassung eines neuen DS hat $k den Wert -1
										$sql = $attributes['dependent_options'][$i][$k];
										if ($sql != '') {
											$ret = $database->execSQL($sql, 4, 0);
											if ($ret[0]) {
												$this->GUI->add_message('error', 'Fehler bei der Abfrage der Optionen für das Attribut "' . $attributes['name'][$i] . '"<br>' . err_msg($this->script_name, __LINE__, $ret[1]));
												return 0;
											}
											$attributes['enum'][$i][$k] = array();
											while ($rs = pg_fetch_array($ret[1])) {
												$attributes['enum'][$i][$k][$rs['value']] = [
													'value' 	=> $rs['value'],
													'output' 	=> $rs['output'],
													'oid'			=> $rs['oid'],
													'image'		=> value_of($rs, 'image')
												];
											}
										}
									}
								}
								elseif ($attributes['options'][$i] != '') {
									if ($requires_options != '') {
										$sql = $requires_options;
									}
									else {
										$sql = $attributes['options'][$i];
									}
									$ret = $database->execSQL($sql, 4, 0);
									if ($ret[0]) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
									while ($rs = pg_fetch_array($ret[1])) {
										$attributes['enum'][$i][$rs['value']] = [
											'value' 	=> $rs['value'],
											'output' 	=> $rs['output'],
											'oid'			=> $rs['oid'],
											'image'		=> value_of($rs, 'image')
										];
										if ($rs['requires']) {
											$attributes['enum'][$i][$rs['value']]['requires_value'] = $rs['requires'];
										}
										// if ($requires_options != '') {
										// 	$attributes['enum_requires_value'][$i][] = $rs['requires'];
										// }
									}
									#echo '<br>attr: ' . print_r($attributes['enum'], true);
								}
							}
						}
					} break;

					case 'Autovervollständigungsfeld' : case 'Autovervollständigungsfeld_zweispaltig' : {
						if ($attributes['options'][$i] != '') {
							if (strpos(strtolower($attributes['options'][$i]), "select") === 0) {		 # SQl-Abfrage wie select attr1 as value, atrr2 as output from table1
								$optionen = explode(';', $attributes['options'][$i]);	# SQL; weitere Optionen // get_options
								$attributes['options'][$i] = $optionen[0];
								if ($query_result != NULL) {
									foreach ($query_result as $k => $record) {	# bei Erfassung eines neuen DS hat $k den Wert -1
										$options_sql = $attributes['options'][$i];
										$value = $query_result[$k][$attributes['name'][$i]];
										if ($value != '' AND !in_array($attributes['operator'][$i], array('LIKE', 'NOT LIKE', 'IN'))) {			# falls eine LIKE-Suche oder eine IN-Suche durchgeführt wurde
											$values = json_decode($value);
											if (is_array($values)) {		# Array-Typ
												foreach ($values as $value) {
													$sql = 'SELECT * FROM ('.$options_sql.') as foo WHERE value = \''.pg_escape_string($value).'\'';
													$ret = $database->execSQL($sql, 4, 0);
													if ($ret[0]) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
													$rs = pg_fetch_array($ret[1]);
													$attributes['enum_output'][$i][$k][] = $rs['output'];
												}
											}
											else {
												$sql = 'SELECT * FROM ('.$options_sql.') as foo WHERE value = \''.pg_escape_string($value).'\'';
												$ret = $database->execSQL($sql, 4, 0);
												if ($ret[0]) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
												$rs = pg_fetch_array($ret[1]);
												$attributes['enum_output'][$i][$k] = $rs['output'];
											}
										}
									}
								}
								# weitere Optionen
								if ($optionen[1] != '') {
									$further_options = explode(' ', $optionen[1]);			# die weiteren Optionen exploden (opt1 opt2 opt3)
									for($k = 0; $k < count($further_options); $k++) {
										if (strpos($further_options[$k], 'layer_id') !== false) {		 #layer_id=XX bietet die Möglichkeit hier eine Layer_ID zu definieren, für die man einen neuen Datensatz erzeugen kann
											$attributes['subform_layer_id'][$i] = array_pop(explode('=', $further_options[$k]));
											$layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
											$attributes['subform_layer_privileg'][$i] = $layer['privileg'];
										}
										elseif ($further_options[$k] == 'embedded') {			 # Subformular soll embedded angezeigt werden
											$attributes['embedded'][$i] = true;
										}
										elseif ($further_options[$k] == 'anywhere') {			 # der eingegebene Text kann überall in den Auswahlmöglichkeiten vorkommen
											$attributes['anywhere'][$i] = true;
										}
									}
								}
							}
						}
					} break;

					case 'Radiobutton' : {
						if ($attributes['options'][$i] != '') {		 # das sind die Auswahlmöglichkeiten, die man im Attributeditor selber festlegen kann
							$optionen = explode(';', $attributes['options'][$i]);	# Optionen; weitere Optionen // get_options
							$attributes['options'][$i] = $optionen[0];
							if (strpos($attributes['options'][$i], "'") === 0) {			# Aufzählung wie 'wert1','wert2','wert3'
								$explosion = explode(',', str_replace("'", "", $attributes['options'][$i]));
								foreach ($explosion as $option) {
									$attributes['enum'][$i][$option]['output'] = $option;
								}
							}
							elseif (strpos(strtolower($attributes['options'][$i]), "select") === 0) {		 # SQl-Abfrage wie select attr1 as value, atrr2 as output from table1
								if ($attributes['options'][$i] != '') {
									$ret = $database->execSQL($sql, 4, 0);
									if ($ret[0]) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
									while($rs = pg_fetch_array($ret[1])) {
										$attributes['enum'][$i][$rs['value']]['output'] = $rs['output'];
									}
								}
							}
							# weitere Optionen
							if ($optionen[1] != '') {
								$further_options = explode(' ', $optionen[1]);			# die weiteren Optionen exploden (opt1 opt2 opt3)
								for($k = 0; $k < count($further_options); $k++) {
									if ($further_options[$k] == 'embedded') {			 # Subformular soll embedded angezeigt werden
										$attributes['embedded'][$i] = true;
									}
									elseif (strpos($further_options[$k], 'horizontal') !== false) {			 # Radiobuttons nebeneinander anzeigen
										$explosion = explode('=', $further_options[$k]);
										if($explosion[1] != ''){
											$attributes['horizontal'][$i] = $explosion[1];
										}
										else{
											$attributes['horizontal'][$i] = true;
										}
									}
								}
							}
						}
					} break;

					# SubFormulare mit Primärschlüssel(n)
					case 'SubFormPK' : {
						if ($attributes['options'][$i] != '') {
							$options = explode(';', $attributes['options'][$i]);	# layer_id,pkey1,pkey2,pkey3...; weitere optionen // get_options
							$subform = explode(',', $options[0]);
							$attributes['subform_layer_id'][$i] = $subform[0];
							$layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
							$attributes['subform_layer_privileg'][$i] = $layer['privileg'];
							for($k = 1; $k < count($subform); $k++) {
								$attributes['subform_pkeys'][$i][] = $subform[$k];
							}
							if ($options[1] != '') {
								if ($options[1] == 'no_new_window') {
									$attributes['no_new_window'][$i] = true;
								}
							}
						}
					} break;

					# SubFormulare mit Fremdschlüssel
					case 'SubFormFK' : {
						if ($attributes['options'][$i] != '') {
							$options = explode(';', $attributes['options'][$i]);	# layer_id,fkey1,fkey2,fkey3...; weitere optionen // get_options
							$subform = explode(',', $options[0]);
							$attributes['subform_layer_id'][$i] = $subform[0];
							$layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
							$attributes['subform_layer_privileg'][$i] = $layer['privileg'];
							for($k = 1; $k < count($subform); $k++) {
								$attributes['subform_fkeys'][$i][] = $subform[$k];
								$attributes['SubFormFK_hidden'][$attributes['indizes'][$subform[$k]]] = 1;
							}
							if ($options[1] != '') {
								if ($options[1] == 'no_new_window') {
									$attributes['no_new_window'][$i] = true;
								}
							}
						}
					} break;

					# eingebettete SubFormulare mit Primärschlüssel(n)
					case 'SubFormEmbeddedPK' : {
						if ($attributes['options'][$i] != '') {
							$options = explode(';', $attributes['options'][$i]);	# layer_id,pkey1,pkey2,preview_attribute; weitere Optionen // get_options
							$subform = explode(',', $options[0]);
							$attributes['subform_layer_id'][$i] = $subform[0];
							$layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
							$attributes['subform_layer_privileg'][$i] = $layer['privileg'];
							for($k = 1; $k < count($subform)-1; $k++) {
								$attributes['subform_pkeys'][$i][] = $subform[$k];
							}
							$attributes['preview_attribute'][$i] = $subform[$k];
							if ($options[1] != '') {
								$further_options = explode(' ', $options[1]);		 # die weiteren Optionen exploden (opt1 opt2 opt3)
								for($k = 0; $k < count($further_options); $k++) {
									switch ($further_options[$k]) {
										case 'no_new_window': {
											$attributes['no_new_window'][$i] = true;
										} break;
										case 'embedded': {														# Subformular soll embedded angezeigt werden
											$attributes['embedded'][$i] = true;
										} break;
										case 'list_edit': {														# nur Listen-Editier-Modus
											$attributes['list_edit'][$i] = true;
										} break;
										case 'reload': {														# die komplette Sachdatenanzeige soll neu geladen werden
											$attributes['reload'][$i] = true;
										} break;
										case 'show_count': {														# die Anzahl der Subform-Datensätze anzeigen
											$attributes['show_count'][$i] = true;
										} break;										
									}
								}
							}
						}
					} break;
				}
			}
		}
		return $attributes;
	}

	function get_used_Layer($id) {
    $sql = '
			SELECT 
				l.oid, 
				ul.* 
			FROM 
				kvwmap.layer as l,
				kvwmap.used_layer as ul 
			WHERE 
				l.layer_id = ul.layer_id AND
				l.layer_id = '.$id.' AND 
				ul.stelle_id = '.$this->Stelle_ID;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_used_Layer - Lesen eines Layers:<br>" . $sql,4);
		$ret = $this->db->execSQL($sql);
		$layer = pg_fetch_array($ret[1]);
		return $layer;
  }
}
?>
