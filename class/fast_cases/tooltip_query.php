<?

function pg_quote($column){
	return ctype_lower($column) ? $column : '"'.$column.'"';
}

function quote($var){
	return is_numeric($var) ? $var : "'".$var."'";
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
		if($center_y != 0.0){
			$cos_lat = cos(pi() * $center_y/180.0);
			$lat_adj = sqrt(1 + $cos_lat * $cos_lat)/sqrt(2);
		}
		return 4374754 * $lat_adj;
	}
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

	function loadMultiLingualText($language) {
    #echo 'In der Rolle eingestellte Sprache: '.$GUI->user->rolle->language;
    $this->Stelle->language=$language;
    $this->Stelle->getName();
    include(LAYOUTPATH.'languages/'.$this->user->rolle->language.'.php');
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
				$rect = ms_newRectObj();										// ?????????
				$rect->setextent($this->formvars['rectminx'],$this->formvars['rectminy'],$this->formvars['rectmaxx'],$this->formvars['rectmaxy']);		// ?????????
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
				$rect=ms_newRectObj();
				$rect->setextent($minx,$miny,$maxx,$maxy);
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
		if($this->formvars['querylayer_id'] != '' AND $this->formvars['querylayer_id'] != 'undefined'){
			if($this->formvars['querylayer_id'] < 0)$layerset=$this->user->rolle->getRollenLayer(-$this->formvars['querylayer_id']);
			else $layerset = $this->user->rolle->getLayer($this->formvars['querylayer_id']);
			$this->formvars['qLayer'.$this->formvars['querylayer_id']] = '1';
		}
		else{
			$layerset = $this->user->rolle->getLayer('');
		}
    $anzLayer=count($layerset);
    $map=ms_newMapObj('');
    $map->set('shapepath', SHAPEPATH);
		$found = false;
    for ($i=0;$i<$anzLayer;$i++) {
			if($found)break;		# wenn in einem Layer was gefunden wurde, abbrechen
			if(	$layerset[$i]['connectiontype'] == 6 AND
					$layerset[$i]['queryable'] AND
					($this->formvars['qLayer'.$layerset[$i]['Layer_ID']]=='1' OR $this->formvars['qLayer'.$layerset[$i]['requires']]=='1') 	AND
					(($layerset[$i]['maxscale'] == 0 OR $layerset[$i]['maxscale'] >= $this->map_scaledenom) AND ($layerset[$i]['minscale'] == 0 OR $layerset[$i]['minscale'] <= $this->map_scaledenom) OR $this->last_query != '')
				) {
				# Dieser Layer soll abgefragt werden
				if($layerset[$i]['alias'] != '' AND $this->Stelle->useLayerAliases){
					$layerset[$i]['Name'] = $layerset[$i]['alias'];
				}
				$layerdb = $this->mapDB->getlayerdatabase($layerset[$i]['Layer_ID'], $this->Stelle->pgdbhost);
				#$path = $layerset[$i]['pfad'];
				$path = replace_params(
					$layerset[$i]['pfad'],
					rolle::$layer_params,
					$this->user->id,
					$this->Stelle->id,
					rolle::$hist_timestamp,
					$this->user->rolle->language
				);
				$privileges = $this->Stelle->get_attributes_privileges($layerset[$i]['Layer_ID']);
				$layerset[$i]['attributes'] = $this->mapDB->read_layer_attributes($layerset[$i]['Layer_ID'], $layerdb, $privileges['attributenames']);
				if($layerset[$i]['Layer_ID'] > 0){			# bei Rollenlayern nicht
					$path = $this->Stelle->parse_path($layerdb, $path, $privileges, $layerset[$i]['attributes']);
				}

				# order by rausnehmen
				$orderbyposition = strrpos(strtolower($path), 'order by');
				$lastfromposition = strrpos(strtolower($path), 'from');
				if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
					$layerset[$i]['attributes']['orderby'] = ' '.substr($path, $orderbyposition);
					$path = substr($path, 0, $orderbyposition);
				}

				# group by rausnehmen
				$groupbyposition = strrpos(strtolower($path), 'group by');
				if($groupbyposition !== false){
					$layerset[$i]['attributes']['groupby'] = ' '.substr($path, $groupbyposition);
					$path = substr($path, 0, $groupbyposition);
				}

				if($rect->minx != ''){	####### Kartenabfrage
					$show = false;
					for($j = 0; $j < @count($layerset[$i]['attributes']['name']); $j++){
						$layerset[$i]['attributes']['tooltip'][$j] = $privileges['tooltip_'.$layerset[$i]['attributes']['name'][$j]];
						if($layerset[$i]['attributes']['tooltip'][$j] == 1){
							$show = true;
						}
					}
					if(!$show){
						continue;
					}
				}

				$distinctpos = strpos(strtolower($path), 'distinct');
				if($distinctpos !== false && $distinctpos < 10){
					$pfad = substr(trim($path), $distinctpos+8);
					$distinct = true;
				}
				else{
					$pfad = substr(trim($path), 7);
				}
				$geometrie_tabelle = $layerset[$i]['attributes']['table_name'][$layerset[$i]['attributes']['the_geom']];
				$j = 0;
				foreach($layerset[$i]['attributes']['all_table_names'] as $tablename){
					if (($tablename == $layerset[$i]['maintable'] OR $tablename == $geometrie_tabelle) AND $layerset[$i]['oid'] != '') {
						$pfad = pg_quote($layerset[$i]['attributes']['table_alias_name'][$tablename]).'.'.$layerset[$i]['oid'].' AS ' . pg_quote($tablename . '_oid').', ' . $pfad;
					}					
					$j++;
				}

				/*if(strpos(strtolower($pfad), 'as the_geom') !== false){
					$the_geom = 'query.the_geom';
				}
				else{*/
					if($layerset[$i]['attributes']['the_geom'] == ''){					# Geometriespalte ist nicht geladen, da auf "nicht sichtbar" gesetzt --> aus Data holen
						$data_attributes = $this->mapDB->getDataAttributes($layerdb, $layerset[$i]['Layer_ID']);
						$layerset[$i]['attributes']['the_geom'] = $data_attributes['the_geom'];
					}
					/*if($layerset[$i]['attributes']['table_alias_name'][$layerset[$i]['attributes']['the_geom']]){
						$the_geom = $layerset[$i]['attributes']['table_alias_name'][$layerset[$i]['attributes']['the_geom']].'.'.$layerset[$i]['attributes']['the_geom'];
					}
					else{*/
						$the_geom = $layerset[$i]['attributes']['the_geom'];
				//  }
				//}

				//$the_geom = $layerset[$i]['attributes']['the_geom'];

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
					$sql_where = " AND ".pg_quote($geometrie_tabelle.'_oid')." = ".quote($this->formvars['oid']);
				}

				# SVG-Geometrie abfragen für highlighting
				if($this->user->rolle->highlighting == '1'){
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
						$pfad = "st_assvg(st_transform(st_simplify(st_intersection(".$layerset[$i]['attributes']['table_alias_name'][$layerset[$i]['attributes']['the_geom']].'.'.$the_geom.", st_transform(st_geomfromtext('".$box_wkt."',".$client_epsg."), ".$layer_epsg.")), ".$tolerance."), ".$client_epsg."), 0, 15) AS highlight_geom, ".$pfad;
					}
					else{
						$buffer = $this->map_scaledenom/260;
						$pfad = "st_assvg(st_buffer(st_transform(".$layerset[$i]['attributes']['table_alias_name'][$layerset[$i]['attributes']['the_geom']].'.'.$the_geom.", ".$client_epsg."), ".$buffer."), 0, 15) AS highlight_geom, ".$pfad;
					}
				}

				# 2006-06-12 sr   Filter zur Where-Klausel hinzugefügt
				if($layerset[$i]['Filter'] != ''){
					$layerset[$i]['Filter'] = str_replace('$userid', $this->user->id, $layerset[$i]['Filter']);
					$sql_where .= " AND ".$layerset[$i]['Filter'];
				}
				
				# group by wieder einbauen
				if($layerset[$i]['attributes']['groupby'] != ''){
					$pfad .= $layerset[$i]['attributes']['groupby'];
					$j = 0;
					foreach($layerset[$i]['attributes']['all_table_names'] as $tablename){
						if($tablename == $layerset[$i]['maintable'] AND $layerset[$i]['oid'] != ''){		# hat Haupttabelle oids?
							$pfad .= ','.pg_quote($tablename.'_oid').' ';
						}
						$j++;
					}
				}
				
				if($distinct == true){
					$pfad = 'DISTINCT '.$pfad;
				}
				
				#if($the_geom == 'query.the_geom'){
					$sql = "SELECT * FROM (SELECT ".$pfad.") as query WHERE 1=1 ".$sql_where;
				/*}
				else{
					$sql = "SELECT ".$pfad." ".$sql_where;
				}
				*/

				# order by wieder einbauen
				if($layerset[$i]['attributes']['orderby'] != ''){										#  der Layer hat im Pfad ein ORDER BY
					$sql .= $layerset[$i]['attributes']['orderby'];
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
    if($found AND $this->show_query_tooltip == true){
      for($i = 0; $i < count($this->qlayerset); $i++) {
      	$layer = $this->qlayerset[$i];
				$output .= $layer['Name'].' : || ';
 				$attributes = $layer['attributes'];
        $anzObj = count($layer['shape']);
        for($k = 0; $k < $anzObj; $k++) {
          $attribcount = 0;
					$highlight_geom .= $layer['shape'][$k]['highlight_geom'].' ';
          for($j = 0; $j < count($attributes['name']); $j++){
            if($attributes['tooltip'][$j]){
							if($attributes['alias'][$j] == '')$attributes['alias'][$j] = $attributes['name'][$j];
							if(substr($attributes['type'][$j], 0, 1) == '_'){
								$values = json_decode($layer['shape'][$k][$attributes['name'][$j]]);
							}
							else{
								$values = array($layer['shape'][$k][$attributes['name'][$j]]);
							}
							if(is_array($values)){
								foreach($values as $value){
									switch ($attributes['form_element_type'][$j]){
										case 'Dokument' : {
											$dokumentpfad = $value;
											$pfadteil = explode('&original_name=', $dokumentpfad);
											$dateiname = $pfadteil[0];
											if($layer['document_url'] != '')$dateiname = url2filepath($dateiname, $layer['document_path'], $layer['document_url']);
											$thumbname = dirname($dokumentpfad).'/'.basename($thumbname);
											$original_name = $pfadteil[1];
											$dateinamensteil=explode('.', $dateiname);
											$type = $dateinamensteil[1];
											$thumbname = $this->get_dokument_vorschau($dateinamensteil);
											if($layer['document_url'] != ''){
												$thumbname = dirname($dokumentpfad).'/'.basename($thumbname);
												$url = '';
											}
											else{
												$this->allowed_documents[] = addslashes($dateiname);
												$this->allowed_documents[] = addslashes($thumbname);
												$url = IMAGEURL.$this->document_loader_name.'?dokument=';
											}
											$pictures .= '| '.$url.$thumbname;
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
												for($e = 0; $e < count($attributes['enum_value'][$j][$k]); $e++){
													if($attributes['enum_value'][$j][$k][$e] == $value){
														$auswahlfeld_output = $attributes['enum_output'][$j][$k][$e];
														break;
													}
												}
											}
											else{
												for($e = 0; $e < count($attributes['enum_value'][$j]); $e++){
													if($attributes['enum_value'][$j][$e] == $value){
														$auswahlfeld_output = $attributes['enum_output'][$j][$e];
														break;
													}
												}
											}
											$output .=  $attributes['alias'][$j].': ';
											$output .= $auswahlfeld_output;
											$output .= '##';
											$attribcount++;
										} break;
										case 'Radiobutton': {
											$radiobutton_output = '';
											for($e = 0; $e < count($attributes['enum_value'][$j]); $e++){
												if($attributes['enum_value'][$j][$e] == $value){
													$radiobutton_output = $attributes['enum_output'][$j][$e];
													break;
												}
											}
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
											$output .=  $attributes['alias'][$j].': ';
											$attribcount++;
											$value = str_replace(chr(10), '##',  $value);
											$output .= $value.'  ';
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

  function get_dokument_vorschau($dateinamensteil){
		$type = strtolower($dateinamensteil[1]);
  	$dokument = $dateinamensteil[0].'.'.$dateinamensteil[1];
		if(in_array($type, array('jpg', 'png', 'gif', 'tif', 'pdf')) ){			// für Bilder und PDFs werden automatisch Thumbnails erzeugt
			$thumbname = $dateinamensteil[0].'_thumb.jpg';
			if(!file_exists($thumbname)){
				exec(IMAGEMAGICKPATH.'convert -filter Hanning '.$dokument.'[0] -quality 75 -background white -flatten -resize '.PREVIEW_IMAGE_WIDTH.'x1000\> '.$thumbname);
			}
		}
		else{																// alle anderen Dokumenttypen bekommen entsprechende Dokumentensymbole als Vorschaubild
			$dateinamensteil[1] = 'gif';
  		switch ($type) {
  			default : {
  				$image = imagecreatefromgif(GRAPHICSPATH.'document.gif');
          $blue = ImageColorAllocate ($image, 26, 87, 150);
					if(strlen($type) > 3)$xoffset = 4;
          imagettftext($image, 12, 0, 23-$xoffset, 34, $blue, WWWROOT.APPLVERSION.'fonts/SourceSansPro-Semibold.ttf', $type);
          $thumbname = IMAGEPATH.rand(0,100000).'.gif';
          imagegif($image, $thumbname);
  			}
  		}
  	}
		return $thumbname;
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
		$this->mysqli = new mysqli($this->host, $this->user, $this->passwd, $this->dbName);
	  $this->debug->write("<br>MySQL VerbindungsID: " . $this->mysqli->thread_id, 4);
		return $this->mysqli->connect_errno;
	}

	function close() {
		$this->debug->write("<br>MySQL Verbindung ID: " . $this->mysqli->thread_id . " schließen.", 4);
		if (LOG_LEVEL > 0) {
			$this->logfile->close();
		}
		return $this->mysqli->close();
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

		$this->debug->write("<p>file:users.php class:user->readUserDaten - Abfragen des Namens des Benutzers:<br>" . $sql, 3);
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
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>' . $this->database->mysqli->error, 4); return 0; }
		while ($rs = $this->database->result->fetch_array()) {
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

	function stelle($id,$database) {
		global $debug;
		$this->debug=$debug;
		$this->id=$id;
		$this->database=$database;
		$this->Bezeichnung=$this->getName();
		$this->readDefaultValues();
	}
	
	function parse_path($database, $path, $privileges, $attributes = NULL){
		$path = str_replace(array("\r\n", "\n"), ' ', $path);
		$distinctpos = strpos(strtolower($path), 'distinct');
		if($distinctpos !== false && $distinctpos < 10){
			$offset = $distinctpos+8;
		}
		else{
			$offset = 7;
		}
		$offstring = substr($path, 0, $offset);
		$path = $database->eliminate_star($path, $offset);
		if(substr_count(strtolower($path), ' from ') > 1){
			$whereposition = strpos($path, ' WHERE ');
			$withoutwhere = substr($path, 0, $whereposition);
			$fromposition = strpos($withoutwhere, ' FROM ');
		}
		else{
			$whereposition = strpos(strtolower($path), ' where ');
			$withoutwhere = substr($path, 0, $whereposition);
			$fromposition = strpos(strtolower($withoutwhere), ' from ');
		}
		$where = substr($path, $whereposition);
		$from = substr($withoutwhere, $fromposition);

		$attributesstring = substr($path, $offset, $fromposition-$offset);
		//$fieldstring = explode(',', $attributesstring);
		$fieldstring = get_select_parts($attributesstring);
		$count = count($fieldstring);
		for($i = 0; $i < $count; $i++){
			if($as_pos = strripos($fieldstring[$i], ' as ')){   # Ausdruck AS attributname
				$attributename = trim(substr($fieldstring[$i], $as_pos+4));
				$real_attributename = substr($fieldstring[$i], 0, $as_pos);
			}
			else{   # tabellenname.attributname oder attributname
				$explosion = explode('.', strtolower($fieldstring[$i]));
				$attributename = trim($explosion[count($explosion)-1]);
				$real_attributename = $fieldstring[$i];
			}
			if($privileges[$attributename] != ''){
				$type = $attributes['type'][$attributes['indizes'][$attributename]];
				if(POSTGRESVERSION >= 930 AND substr($type, 0, 1) == '_' OR is_numeric($type))$newattributesstring .= 'to_json('.$real_attributename.') as '.$attributename.', ';		# Array oder Datentyp
				else $newattributesstring .= $fieldstring[$i].', ';																																			# normal
			}
			if(substr_count($fieldstring[$i], '(') - substr_count($fieldstring[$i], ')') > 0){
				$fieldstring[$i+1] = $fieldstring[$i].','.$fieldstring[$i+1];
			}
		}
		$newattributesstring = substr($newattributesstring, 0, strlen($newattributesstring)-2);
		$newpath = $offstring.' '.$newattributesstring.' '.$from.$where;
		return $newpath;
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

	function get_attributes_privileges($layer_id) {
		$sql = "
			SELECT
				`attributename`,
				`privileg`,
				`tooltip`
			FROM
				`layer_attributes2stelle`
			WHERE
				`stelle_id` = " . $this->id . " AND
				`layer_id` = " . $layer_id;
		#echo '<br>Sql: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->get_attributes_privileges - Abfragen der Layerrechte zur Stelle:<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . $PHP_SELF . " Zeile: " . __LINE__, 4); return 0; }
		while ($rs = $this->database->result->fetch_array()) {
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
				alias, Datentyp, Gruppe, pfad, maintable, oid, maintable_is_view, Data, tileindex, `schema`, max_query_rows, document_path, document_url, classification, ddl_attribute, CASE WHEN connectiontype = 6 THEN concat('host=', c.host, ' port=', c.port, ' dbname=', c.dbname, ' user=', c.user, ' password=', c.password) ELSE l.connection END as connection, printconnection,
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
			SELECT 
				l.*, 
				4 as tolerance, 
				-l.id as Layer_ID, 
				l.query as pfad, 
				CASE WHEN Typ = 'import' THEN 1 ELSE 0 END as queryable, 
				gle_view,
				concat('(', rollenfilter, ')') as Filter,
				'oid' as oid
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
			$this->err_msg = 'Die Verbindung zur PostGIS-Datenbank konnte nicht hergestellt werden';
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

  function eliminate_star($query, $offset){
  	if(substr_count(strtolower($query), ' from ') > 1){
  		$whereposition = strrpos($query, ' WHERE ');
  		$withoutwhere = substr($query, 0, $whereposition);
  		$fromposition = strrpos($withoutwhere, ' FROM ');
  	}
  	else{
  		$whereposition = strpos(strtolower($query), ' where ');
  		if($whereposition){
  			$withoutwhere = substr($query, 0, $whereposition);
  		}
  		else{
  			$withoutwhere = $query;
  		}
  		$fromposition = strpos(strtolower($withoutwhere), ' from ');
  	}
    $select = substr($query, $offset, $fromposition-$offset);
    $from = substr($query, $fromposition);
    $column = explode(',', $select);
    $column = get_select_parts($select);
    for($i = 0; $i < count($column); $i++){
      if(strpos(trim($column[$i]), '*') === 0 OR strpos($column[$i], '.*') !== false){
        $sql = "SELECT ".$column[$i]." ".$from." LIMIT 0";
        $ret = $this->execSQL($sql, 4, 0);
        if($ret[0]==0){
        	$tablename = str_replace('*', '', trim($column[$i]));
          $columns = $tablename.pg_field_name($ret[1], 0);
          for($j = 1; $j < pg_num_fields($ret[1]); $j++){
            $columns .= ', '.$tablename.pg_field_name($ret[1], $j);
          }
          $query = str_replace(trim($column[$i]), $columns, $query);
        }
      }
    }
    return $query;
  }

  function setClientEncoding() {
    $sql ="SET CLIENT_ENCODING TO '".POSTGRES_CHARSET."';";
		$ret = $this->execSQL($sql, 4, 0);
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
      $sql = "SET datestyle TO 'German';".$sql;
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

  function check_oid($tablename){
    $sql = 'SELECT oid from '.$tablename.' limit 0';
    if($this->schema != ''){
    	$sql = "SET search_path = ".$this->schema.", public;".$sql;
    }
    $this->debug->write("<p>file:kvwmap class:postgresql->check_oid:<br>".$sql,4);
    @$query=pg_query($sql);
    if ($query==0) {
			return false;
    }
    else{
      return true;
    }
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
		$this->db = $GUI->database;
		$this->Stelle_ID = $Stelle_ID;
		$this->User_ID = $User_ID;
		$this->rolle = new rolle($User_ID, $Stelle_ID, $this->db);
	}

	function getlayerdatabase($layer_id, $host) {
		#echo '<br>GUI->getlayerdatabase layer_id: ' . $layer_id;
		$layerdb = new pgdatabase();
		$rs = $this->get_layer_connection($layer_id);
		if (@count($rs) == 0) {
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

  function add_attribute_values($attributes, $database, $query_result, $withvalues = true, $stelle_id, $only_current_enums = false){
    # Diese Funktion fügt den Attributen je nach Attributtyp zusätzliche Werte hinzu. Z.B. bei Auswahlfeldern die Auswahlmöglichkeiten.
    for($i = 0; $i < @count($attributes['name']); $i++){
			$type = ltrim($attributes['type'][$i], '_');
			if(is_numeric($type)){
				$attributes['type_attributes'][$i] = $this->add_attribute_values($attributes['type_attributes'][$i], $database, $query_result, $withvalues, $stelle_id, $only_current_enums);
			}
			if($attributes['options'][$i] == '' AND $attributes['constraints'][$i] != '' AND !in_array($attributes['constraints'][$i], array('PRIMARY KEY', 'UNIQUE'))){  # das sind die Auswahlmöglichkeiten, die durch die Tabellendefinition in Postgres fest vorgegeben sind
				$attributes['enum_value'][$i] = explode("','", trim($attributes['constraints'][$i], "'"));
      	$attributes['enum_output'][$i] = $attributes['enum_value'][$i];
      }
      if($withvalues == true){
        switch($attributes['form_element_type'][$i]){
          # Auswahlfelder
          case 'Auswahlfeld' : {
            if($attributes['options'][$i] != ''){     # das sind die Auswahlmöglichkeiten, die man im Attributeditor selber festlegen kann
              if(strpos($attributes['options'][$i], "'") === 0){      # Aufzählung wie 'wert1','wert2','wert3'
								$attributes['enum_value'][$i] = explode("','", trim($attributes['options'][$i], "'"));
                $attributes['enum_output'][$i] = $attributes['enum_value'][$i];
              }
              elseif(strpos(strtolower($attributes['options'][$i]), "select") === 0){     # SQl-Abfrage wie select attr1 as value, atrr2 as output from table1
                $optionen = explode(';', $attributes['options'][$i]);  # SQL; weitere Optionen
                $attributes['options'][$i] = $optionen[0];
                # ------<required by>------
                $req_by_start = strpos(strtolower($attributes['options'][$i]), "<required by>");
                if($req_by_start > 0){
                  $req_by_end = strpos(strtolower($attributes['options'][$i]), "</required by>");
                  $req_by = trim(substr($attributes['options'][$i], $req_by_start+13, $req_by_end-$req_by_start-13));
                  $attributes['req_by'][$i] = $req_by;    # das abhängige Attribut
                  $attributes['options'][$i] = substr($attributes['options'][$i], 0, $req_by_start);    # required-Tag aus SQL entfernen
                }
                # ------<required by>------
                # -----<requires>------
                if(strpos(strtolower($attributes['options'][$i]), "<requires>") > 0){
									if($only_current_enums){		# Ermittlung der Spalte, die als value dient
										$explo1 = explode(' as value', strtolower($attributes['options'][$i]));
										$attribute_value_column = array_pop(explode(' ', $explo1[0]));
									}
                  if($query_result != NULL){
                    for($k = 0; $k < count($query_result); $k++){
											$options = $attributes['options'][$i];
											foreach($attributes['name'] as $attributename){
												if(strpos($options, '<requires>'.$attributename.'</requires>') !== false AND $query_result[$k][$attributename] != ''){
													if($only_current_enums){	# in diesem Fall werden nicht alle Auswahlmöglichkeiten abgefragt, sondern nur die aktuellen Werte des Datensatzes (wird z.B. beim Daten-Export verwendet, da hier nur lesend zugegriffen wird und die Datenmengen sehr groß sein können)
														$options = str_ireplace('where', 'where '.$attribute_value_column.'::text = \''.$query_result[$k][$attributes['name'][$i]].'\' AND ', $options);
													}
													$options = str_replace('<requires>'.$attributename.'</requires>', "'".$query_result[$k][$attributename]."'", $options);
												}
											}
											if(strpos($options, '<requires>') !== false){
												#$options = '';    # wenn in diesem Datensatz des Query-Results ein benötigtes Attribut keinen Wert hat (also nicht alle <requires>-Einträge ersetzt wurden), sind die abhängigen Optionen für diesen Datensatz leer
												$attribute_value = $query_result[$k][$attributes['name'][$i]];
												if($attribute_value != '')$options = "select '".$attribute_value."' as value, '".$attribute_value."' as output";
												else $options = '';		# wenn in diesem Datensatz des Query-Results ein benötigtes Attribut keinen Wert hat (also nicht alle <requires>-Einträge ersetzt wurden) aber das eigentliche Attribut einen Wert hat, wird dieser Wert als value und output genommen, ansonsten sind die Optionen leer
											}
											$attributes['dependent_options'][$i][$k] = $options;
                    }
                  }
                  else{
                    $attributes['options'][$i] = '';      # wenn kein Query-Result übergeben wurde, sind die Optionen leer
                  }
                }
                # -----<requires>------
                if(is_array($attributes['dependent_options'][$i])){   # mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
                  for($k = 0; $k < count($query_result); $k++){
                    $sql = $attributes['dependent_options'][$i][$k];
                    if($sql != ''){
                      $ret=$database->execSQL($sql,4,0);
                      if ($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
                      while($rs = pg_fetch_array($ret[1])){
                        $attributes['enum_value'][$i][$k][] = $rs['value'];
                        $attributes['enum_output'][$i][$k][] = $rs['output'];
                      }
                    }
                  }
                }
                elseif($attributes['options'][$i] != ''){
                  $sql = str_replace('$stelleid', $stelle_id, $attributes['options'][$i]);
									$sql = str_replace('$userid', $this->User_ID, $sql);
                  $ret=$database->execSQL($sql,4,0);
                  if ($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
                  while($rs = pg_fetch_array($ret[1])){
                    $attributes['enum_value'][$i][] = $rs['value'];
                    $attributes['enum_output'][$i][] = $rs['output'];
                  }
                }
								# weitere Optionen
                if($optionen[1] != ''){
                  $further_options = explode(' ', $optionen[1]);      # die weiteren Optionen exploden (opt1 opt2 opt3)
                  for($k = 0; $k < count($further_options); $k++){
                    if(strpos($further_options[$k], 'layer_id') !== false){     #layer_id=XX bietet die Möglichkeit hier eine Layer_ID zu definieren, für die man einen neuen Datensatz erzeugen kann
                      $attributes['subform_layer_id'][$i] = array_pop(explode('=', $further_options[$k]));
                      $layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
                      $attributes['subform_layer_privileg'][$i] = $layer['privileg'];
                    }
                    elseif($further_options[$k] == 'embedded'){       # Subformular soll embedded angezeigt werden
                      $attributes['embedded'][$i] = true;
                    }
                  }
                }
              }
            }
          }break;

					case 'Autovervollständigungsfeld' : {
            if($attributes['options'][$i] != ''){
              if(strpos(strtolower($attributes['options'][$i]), "select") === 0){     # SQl-Abfrage wie select attr1 as value, atrr2 as output from table1
                $optionen = explode(';', $attributes['options'][$i]);  # SQL; weitere Optionen
                $attributes['options'][$i] = $optionen[0];
								if($query_result != NULL){
									for($k = 0; $k < count($query_result); $k++){
										$sql = $attributes['options'][$i];
										$value = $query_result[$k][$attributes['name'][$i]];
										if($value != '' AND !in_array($attributes['operator'][$i], array('LIKE', 'NOT LIKE', 'IN'))){			# falls eine LIKE-Suche oder eine IN-Suche durchgeführt wurde
											$sql = 'SELECT * FROM ('.$sql.') as foo WHERE value = \''.pg_escape_string($value).'\'';
											$ret=$database->execSQL($sql,4,0);
											if ($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
											$rs = pg_fetch_array($ret[1]);
											$attributes['enum_output'][$i][$k] = $rs['output'];
										}
									}
								}
								# weitere Optionen
                if($optionen[1] != ''){
                  $further_options = explode(' ', $optionen[1]);      # die weiteren Optionen exploden (opt1 opt2 opt3)
                  for($k = 0; $k < count($further_options); $k++){
                    if(strpos($further_options[$k], 'layer_id') !== false){     #layer_id=XX bietet die Möglichkeit hier eine Layer_ID zu definieren, für die man einen neuen Datensatz erzeugen kann
                      $attributes['subform_layer_id'][$i] = array_pop(explode('=', $further_options[$k]));
                      $layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
                      $attributes['subform_layer_privileg'][$i] = $layer['privileg'];
                    }
                    elseif($further_options[$k] == 'embedded'){       # Subformular soll embedded angezeigt werden
                      $attributes['embedded'][$i] = true;
                    }
                  }
                }
              }
            }
          }break;
					
          case 'Radiobutton' : {
            if($attributes['options'][$i] != ''){     # das sind die Auswahlmöglichkeiten, die man im Attributeditor selber festlegen kann
              if(strpos($attributes['options'][$i], "'") === 0){      # Aufzählung wie 'wert1','wert2','wert3'
                $attributes['enum_value'][$i] = explode(',', str_replace("'", "", $attributes['options'][$i]));
                $attributes['enum_output'][$i] = $attributes['enum_value'][$i];
              }
              elseif(strpos(strtolower($attributes['options'][$i]), "select") === 0){     # SQl-Abfrage wie select attr1 as value, atrr2 as output from table1
                $optionen = explode(';', $attributes['options'][$i]);  # SQL; weitere Optionen
                $attributes['options'][$i] = $optionen[0];
								if($attributes['options'][$i] != ''){
                  $sql = str_replace('$stelleid', $stelle_id, $attributes['options'][$i]);
                  $ret=$database->execSQL($sql,4,0);
                  if ($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
                  while($rs = pg_fetch_array($ret[1])){
                    $attributes['enum_value'][$i][] = $rs['value'];
                    $attributes['enum_output'][$i][] = $rs['output'];
                  }
                }
								# weitere Optionen
                if($optionen[1] != ''){
                  $further_options = explode(' ', $optionen[1]);      # die weiteren Optionen exploden (opt1 opt2 opt3)
                  for($k = 0; $k < count($further_options); $k++){
										if($further_options[$k] == 'embedded'){       # Subformular soll embedded angezeigt werden
                      $attributes['embedded'][$i] = true;
                    }
                  }
                }
              }
            }
          }break;					

          # SubFormulare mit Primärschlüssel(n)
          case 'SubFormPK' : {
            if($attributes['options'][$i] != ''){
              $options = explode(';', $attributes['options'][$i]);  # layer_id,pkey1,pkey2,pkey3...; weitere optionen
              $subform = explode(',', $options[0]);
              $attributes['subform_layer_id'][$i] = $subform[0];
              $layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
              $attributes['subform_layer_privileg'][$i] = $layer['privileg'];
              for($k = 1; $k < count($subform); $k++){
                $attributes['subform_pkeys'][$i][] = $subform[$k];
              }
              if($options[1] != ''){
                if($options[1] == 'no_new_window'){
                  $attributes['no_new_window'][$i] = true;
                }
              }
            }
          }break;

          # SubFormulare mit Fremdschlüssel
          case 'SubFormFK' : {
            if($attributes['options'][$i] != ''){
              $options = explode(';', $attributes['options'][$i]);  # layer_id,fkey1,fkey2,fkey3...; weitere optionen
              $subform = explode(',', $options[0]);
              $attributes['subform_layer_id'][$i] = $subform[0];
              $layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
              $attributes['subform_layer_privileg'][$i] = $layer['privileg'];
              for($k = 1; $k < count($subform); $k++){
                $attributes['subform_fkeys'][$i][] = $subform[$k];
                $attributes['invisible'][$subform[$k]] = 'true';
              }
              if($options[1] != ''){
                if($options[1] == 'no_new_window'){
                  $attributes['no_new_window'][$i] = true;
                }
              }
            }
          }break;

          # eingebettete SubFormulare mit Primärschlüssel(n)
          case 'SubFormEmbeddedPK' : {
            if($attributes['options'][$i] != ''){
              $options = explode(';', $attributes['options'][$i]);  # layer_id,pkey1,pkey2,preview_attribute; weitere Optionen
              $subform = explode(',', $options[0]);
              $attributes['subform_layer_id'][$i] = $subform[0];
              $layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
              $attributes['subform_layer_privileg'][$i] = $layer['privileg'];
              for($k = 1; $k < count($subform)-1; $k++){
                $attributes['subform_pkeys'][$i][] = $subform[$k];
              }
              $attributes['preview_attribute'][$i] = $subform[$k];
              if($options[1] != ''){
                $further_options = explode(' ', $options[1]);     # die weiteren Optionen exploden (opt1 opt2 opt3)
                for($k = 0; $k < count($further_options); $k++){
                  switch ($further_options[$k]){
                    case 'no_new_window': {
                      $attributes['no_new_window'][$i] = true;
                    }break;
                    case 'embedded': {                            # Subformular soll embedded angezeigt werden
                      $attributes['embedded'][$i] = true;
                    }break;
                  }
                }
              }
            }
          }break;
        }
      }
    }
    return $attributes;
  }

  function get_used_Layer($id) {
    $sql ='SELECT * FROM used_layer WHERE Layer_ID = '.$id.' AND Stelle_ID = '.$this->Stelle_ID;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_used_Layer - Lesen eines Layers:<br>" . $sql,4);
		$this->db->execSQL($sql);
		if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		$layer = $this->db->result->fetch_array();
		return $layer;
  }
}
?>
