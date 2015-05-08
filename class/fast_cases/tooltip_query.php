<?function in_subnet($ip,$net) {
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
class GUI {  var $layout;  var $style;  var $mime_type;  var $menue;  var $pdf;  var $addressliste;  var $debug;  var $dbConn;  var $flst;  var $formvars;  var $legende;  var $map;  var $mapDB;  var $img;  var $FormObject;  var $StellenForm;  var $Fehlermeldung;  var $Hinweis;  var $Stelle;  var $ALB;  var $activeLayer;  var $nImageWidth;  var $nImageHeight;  var $user;  var $qlayerset;  var $scaleUnitSwitchScale;  var $map_scaledenom;  var $map_factor;  var $formatter;  function GUI($main, $style, $mime_type) {
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
  }
	function loadMultiLingualText($language) {
    #echo 'In der Rolle eingestellte Sprache: '.$GUI->user->rolle->language;
    $this->Stelle->language=$language;
    $this->Stelle->getName();
    include(LAYOUTPATH.'languages/'.$this->user->rolle->language.'.php');
  }
	function queryMap() {
		# scale ausrechnen, da wir uns das loadmap sparen
		$width = $this->user->rolle->nImageWidth;
		$pixelsize = ($this->user->rolle->oGeorefExt->maxx - $this->user->rolle->oGeorefExt->minx)/($width-1);		# das width - 1 kommt daher, weil der Mapserver das auch so macht
		$this->map_scaledenom = round($pixelsize * 96 / 0.0254);
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
			$layerset = $this->user->rolle->getLayer($this->formvars['querylayer_id']);
			$this->formvars['qLayer'.$this->formvars['querylayer_id']] = '1';
		}
		else{
			$layerset = $this->user->rolle->getLayer('');
		}		
		$layerset = array_reverse($layerset);
    $anzLayer=count($layerset);
    $map=ms_newMapObj('');
    $map->set('shapepath', SHAPEPATH);
		$found = false;
    for ($i=0;$i<$anzLayer;$i++) {
			if($found)break;		# wenn in einem Layer was gefunden wurde, abbrechen
			if($this->formvars['qLayer'.$layerset[$i]['Layer_ID']]=='1' AND ($layerset[$i]['maxscale'] == 0 OR $layerset[$i]['maxscale'] > $this->map_scaledenom) AND ($layerset[$i]['minscale'] == 0 OR $layerset[$i]['minscale'] < $this->map_scaledenom)){
				# Dieser Layer soll abgefragt werden
				if($layerset[$i]['alias'] != '' AND $this->Stelle->useLayerAliases){
					$layerset[$i]['Name'] = $layerset[$i]['alias'];
				}	
				$layerdb = $this->mapDB->getlayerdatabase($layerset[$i]['Layer_ID'], $this->Stelle->pgdbhost);
				#$path = $layerset[$i]['pfad'];
				$path = str_replace('$hist_timestamp', rolle::$hist_timestamp, $layerset[$i]['pfad']);
				$privileges = $this->Stelle->get_attributes_privileges($layerset[$i]['Layer_ID']);
				#$path = $this->Stelle->parse_path($layerdb, $path, $privileges);
				$layerset[$i]['attributes'] = $this->mapDB->read_layer_attributes($layerset[$i]['Layer_ID'], $layerdb, $privileges['attributenames']);      

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
					for($j = 0; $j < count($layerset[$i]['attributes']['name']); $j++){
						$layerset[$i]['attributes']['tooltip'][$j] = $privileges['tooltip_'.$layerset[$i]['attributes']['name'][$j]];
						if($layerset[$i]['attributes']['tooltip'][$j] == 1){
							$show = true;
						}
					}
					if(!$show){
						return NULL;
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
				$j = 0;
				foreach($layerset[$i]['attributes']['all_table_names'] as $tablename){
					if($layerset[$i]['attributes']['oids'][$j]){      # hat Tabelle oids?
						$pfad = $layerset[$i]['attributes']['table_alias_name'][$tablename].'.oid AS '.$tablename.'_oid, '.$pfad;
					}
					$j++;
				}
				if($distinct == true){
					$pfad = 'DISTINCT '.$pfad;
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
					$sql_where = " AND ".$layerset[$i]['maintable']."_oid = ".$this->formvars['oid'];
				}
				
				# SVG-Geometrie abfragen für highlighting
				if($this->user->rolle->highlighting == '1'){
					$pfad = "st_assvg(st_transform(".$layerset[$i]['attributes']['table_alias_name'][$layerset[$i]['attributes']['the_geom']].'.'.$the_geom.", ".$client_epsg."), 0, 8) AS highlight_geom, ".$pfad;
				}
				
				# 2006-06-12 sr   Filter zur Where-Klausel hinzugefügt
				if($layerset[$i]['Filter'] != ''){
					$layerset[$i]['Filter'] = str_replace('$userid', $this->user->id, $layerset[$i]['Filter']);
					$sql_where .= " AND ".$layerset[$i]['Filter'];
				}
				#if($the_geom == 'query.the_geom'){
					$sql = "SELECT * FROM (SELECT ".$pfad.") as query WHERE 1=1 ".$sql_where;
				/*}
				else{
					$sql = "SELECT ".$pfad." ".$sql_where;
				}
				*/
							
				# group by wieder einbauen
				if($layerset[$i]['attributes']['groupby'] != ''){
					$sql .= $layerset[$i]['attributes']['groupby'];
					$j = 0;
					foreach($layerset[$i]['attributes']['all_table_names'] as $tablename){
						if($layerset[$i]['attributes']['oids'][$j]){      # hat Tabelle oids?
							$sql .= ','.$tablename.'_oid ';
						}
						$j++;
					}	
				}
				
				# order by wieder einbauen
				if($layerset[$i]['attributes']['orderby'] != ''){										#  der Layer hat im Pfad ein ORDER BY
					$sql .= $layerset[$i]['attributes']['orderby'];
				}
				
				# Anhängen des Begrenzers zur Einschränkung der Anzahl der Ergebniszeilen
				$sql_limit =' LIMIT '.MAXQUERYROWS;

				#echo '<br>sql:<br>'.$sql;
				$ret=$layerdb->execSQL($sql.$sql_limit,4, 0);
				if (!$ret[0]) {
					while ($rs=pg_fetch_array($ret[1])) {
						$found = true;
						$layerset[$i]['shape'][]=$rs;
					}
				}
				
				# Hier nach der Abfrage der Sachdaten die weiteren Attributinformationen hinzufügen
				# Steht an dieser Stelle, weil die Auswahlmöglichkeiten von Auswahlfeldern abhängig sein können
				$layerset[$i]['attributes'] = $this->mapDB->add_attribute_values($layerset[$i]['attributes'], $layerdb, $layerset[$i]['shape'], true);
				
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
            	switch ($attributes['form_element_type'][$j]){
				        case 'Dokument' : {
									$dokumentpfad = $layer['shape'][$k][$attributes['name'][$j]];
									$pfadteil = explode('&original_name=', $dokumentpfad);
									$dateiname = $pfadteil[0];
									$original_name = $pfadteil[1];
									$dateinamensteil=explode('.', $dateiname);
									$type = $dateinamensteil[1];
									$thumbname = $this->get_dokument_vorschau($dateinamensteil);
									$this->allowed_documents[] = addslashes($dateiname);
									$this->allowed_documents[] = addslashes($thumbname);
									$url = IMAGEURL.$this->document_loader_name.'?dokument=';
									$pictures .= '| '.$url.$thumbname;
				        }break;
				        case 'Link': {
		              $attribcount++;
									if($layer['shape'][$k][$attributes['name'][$j]]!='') {
										$link = 'xlink:'.$layer['shape'][$k][$attributes['name'][$j]];
										$links .= $link.'##';
									}
								} break;
								case 'Auswahlfeld': {
									if(is_array($attributes['dependent_options'][$j])){		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
										for($e = 0; $e < count($attributes['enum_value'][$j][$k]); $e++){
											if($attributes['enum_value'][$j][$k][$e] == $layer['shape'][$k][$attributes['name'][$j]]){
												$auswahlfeld_output = $attributes['enum_output'][$j][$k][$e];
												break;
											}
										}
									}
									else{
										for($e = 0; $e < count($attributes['enum_value'][$j]); $e++){
											if($attributes['enum_value'][$j][$e] == $layer['shape'][$k][$attributes['name'][$j]]){
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
				        default : {
		              $output .=  $attributes['alias'][$j].': ';
		              $attribcount++;
		              $output .= $layer['shape'][$k][$attributes['name'][$j]].'  ';
		              $output .= '##';
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
      echo umlaute_javascript(umlaute_html($output)).'~showtooltip(top.document.GUI.result.value, '.$showdata.');';
    }
  }
  function get_dokument_vorschau($dateinamensteil){
		$type = strtolower($dateinamensteil[1]);
  	$dokument = $dateinamensteil[0].'.'.$dateinamensteil[1];
		if(in_array($type, array('jpg', 'png', 'gif', 'tif', 'pdf')) ){			// für Bilder und PDFs werden automatisch Thumbnails erzeugt
			$thumbname = $dateinamensteil[0].'_thumb.jpg';			
			if(!file_exists($thumbname)){
				exec(IMAGEMAGICKPATH.'convert -filter Hanning '.$dokument.'[0] -quality 75 -resize '.PREVIEW_IMAGE_WIDTH.'\> '.$thumbname);
			}
		}
		else{																// alle anderen Dokumenttypen bekommen entsprechende Dokumentensymbole als Vorschaubild
			$dateinamensteil[1] = 'gif';
  		switch ($type) {  			
  			case 'doc' :{
					$thumbname = WWWROOT.APPLVERSION.GRAPHICSPATH.'openoffice.gif';
  			}break;
  			
  			default : {
  				$image = imagecreatefromgif(GRAPHICSPATH.'document.gif');
          $textbox = imagettfbbox(13, 0, dirname(FONTSET).'/arial.ttf', '.'.$type);
          $textwidth = $textbox[2] - $textbox[0] + 13;
          $blue = ImageColorAllocate ($image, 26, 87, 150);
          imagettftext($image, 13, 0, 22, 34, $blue, dirname(FONTSET).'/arial_bold.ttf', $type);
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
			$allowed_documents = array(\''.implode('\',\'', $this->allowed_documents).'\');
			if(in_array($_REQUEST[\'dokument\'], $allowed_documents)){
				if($_REQUEST[\'original_name\'] == "")$_REQUEST[\'original_name\'] = basename($_REQUEST[\'dokument\']);
				$type = strtolower(array_pop(explode(\'.\', $_REQUEST[\'dokument\'])));
				if(in_array($type, array(\'jpg\', \'gif\', \'png\')))header("Content-type: image/".$type);
				else header("Content-type: application/".$type);
				header("Content-Disposition: attachment; filename=\"".$_REQUEST[\'original_name\']."\"");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Pragma: public");
				readfile($_REQUEST[\'dokument\']);
			}
		?>';
		fwrite($handle, $code);
		fclose($handle);
	}
}class database {  var $ist_Fortfuehrung;  var $debug;  var $loglevel;  var $logfile;  var $commentsign;  var $blocktransaction;  function database() {
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
}class user {  var $id;  var $Name;  var $Vorname;  var $login_name;  var $funktion;  var $dbConn;  var $Stellen;  var $nZoomFactor;  var $nImageWidth;  var $nImageHeight;  var $database;  var $remote_addr;	function user($login_name,$id,$database) {
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
  function readUserDaten($id,$login_name) {
    $sql ='SELECT * FROM user WHERE 1=1';
    if ($id>0) {
      $sql.=' AND ID='.$id;
    }
    if ($login_name!='') {
      $sql.=' AND login_name LIKE "'.$login_name.'"';
    }
    $this->debug->write("<p>file:users.php class:user->readUserDaten - Abfragen des Namens des Benutzers:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    $this->id=$rs['ID'];
    $this->login_name=$rs['login_name'];
    $this->Namenszusatz=$rs['Namenszusatz'];
    $this->Name=$rs['Name'];
    $this->Vorname=$rs['Vorname'];
    $this->stelle_id=$rs['stelle_id'];
    $this->phon=$rs['phon'];
    $this->email=$rs['email'];
    if (CHECK_CLIENT_IP) {
      $this->ips=$rs['ips'];
    }
    $this->password_setting_time=$rs['password_setting_time'];
  }
  function getLastStelle() {
    $sql = 'SELECT stelle_id FROM user WHERE ID='.$this->id;
    $this->debug->write("<p>file:users.php class:user->getLastStelle - Abfragen der zuletzt genutzten Stelle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    return $rs['stelle_id'];
  }
	function clientIpIsValide($remote_addr) {
    # Prüfen ob die übergebene IP Adresse zu den für den Nutzer eingetragenen Adressen passt
    $ips=explode(';',$this->ips);
    foreach ($ips AS $ip) {
      if (trim($ip)!='') {
        $ip=trim($ip);
        if (in_subnet($remote_addr,$ip)) {
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
}class stelle {  var $id;  var $Bezeichnung;  var $debug;  var $nImageWidth;  var $nImageHeight;  var $oGeorefExt;  var $pixsize;  var $selectedButton;  var $database;	function stelle($id,$database) {
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
    $this->debug->write("<p>file:users.php class:stelle->getName - Abfragen des Namens der Stelle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    $this->Bezeichnung=$rs['Bezeichnung'];
    return $rs['Bezeichnung'];
  }
  function readDefaultValues() {
    $sql ='SELECT * FROM stelle WHERE ID='.$this->id;
    $this->debug->write("<p>file:users.php class:stelle->readDefaultValues - Abfragen der Default Parameter der Karte zur Stelle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);    
    $this->MaxGeorefExt=ms_newRectObj();
    $this->MaxGeorefExt->setextent($rs['minxmax'],$rs['minymax'],$rs['maxxmax'],$rs['maxymax']);
    $this->epsg_code=$rs["epsg_code"];
    $this->alb_raumbezug=$rs["alb_raumbezug"];
    $this->alb_raumbezug_wert=$rs["alb_raumbezug_wert"];
    $this->wasserzeichen=$rs["wasserzeichen"];
    $this->pgdbhost=$rs["pgdbhost"];
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
  }
  function checkClientIpIsOn() {
    $sql ='SELECT check_client_ip FROM stelle WHERE ID = '.$this->id;
    $this->debug->write("<p>file:users.php class:stelle->checkClientIpIsOn- Abfragen ob IP's der Nutzer in der Stelle getestet werden sollen<br>".$sql,4);
    #echo '<br>'.$sql;
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    if ($rs['check_client_ip']=='1') {
      return 1;
    }
    return 0;
  }
	function get_attributes_privileges($layer_id){
		$sql = 'SELECT attributename, privileg, tooltip FROM layer_attributes2stelle WHERE stelle_id = '.$this->id.' AND layer_id = '.$layer_id;
		$this->debug->write("<p>file:users.php class:stelle->get_attributes_privileges - Abfragen der Layerrechte zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		while ($rs=mysql_fetch_array($query)) {
			$privileges[$rs['attributename']] = $rs['privileg'];
			$privileges['tooltip_'.$rs['attributename']] = $rs['tooltip'];
			$privileges['attributenames'][] = $rs['attributename'];
		}
		return $privileges;
	}
}class rolle {  var $user_id;  var $stelle_id;  var $debug;  var $database;  var $loglevel;  static $hist_timestamp;	function rolle($user_id,$stelle_id,$database) {
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
    $sql ='SELECT * FROM rolle WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
    #echo $sql;
    $this->debug->write("<p>file:users.php class:rolle function:readSettings - Abfragen der Einstellungen der Rolle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) {
      $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4);
      return 0;
    }
		if(mysql_num_rows($query) > 0){
			$rs=mysql_fetch_assoc($query);
			$this->oGeorefExt=ms_newRectObj();
			$this->oGeorefExt->setextent($rs['minx'],$rs['miny'],$rs['maxx'],$rs['maxy']);
			$this->nImageWidth=$rs['nImageWidth'];
			$this->nImageHeight=$rs['nImageHeight'];
			$this->mapsize=$this->nImageWidth.'x'.$this->nImageHeight;
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
			$this->singlequery=$rs['singlequery'];
			$this->querymode=$rs['querymode'];
			$this->geom_edit_first=$rs['geom_edit_first'];		
			$this->overlayx=$rs['overlayx'];
			$this->overlayy=$rs['overlayy'];
			$this->instant_reload=$rs['instant_reload'];
			$this->menu_auto_close=$rs['menu_auto_close'];
			if($rs['hist_timestamp'] != ''){
				$this->hist_timestamp = DateTime::createFromFormat('Y-m-d H:i:s', $rs['hist_timestamp'])->format('d.m.Y H:i:s');			# der wird zur Anzeige des Timestamps benutzt
				rolle::$hist_timestamp = DateTime::createFromFormat('Y-m-d H:i:s', $rs['hist_timestamp'])->format('Y-m-d\TH:i:s\Z');	# der hat die Form, wie der timestamp in der PG-DB steht und wird für die Abfragen benutzt
			}
			else rolle::$hist_timestamp = $this->hist_timestamp = '';
			$buttons = explode(',', $rs['buttons']);
			$this->back = in_array('back', $buttons);
			$this->forward = in_array('forward', $buttons);
			$this->zoomin = in_array('zoomin', $buttons);
			$this->zoomout = in_array('zoomout', $buttons);
			$this->zoomall = in_array('zoomall', $buttons);
			$this->recentre = in_array('recentre', $buttons);
			$this->jumpto = in_array('jumpto', $buttons);
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
  function getLayer($LayerName) {
		global $language;
    # Abfragen der Layer in der Rolle
		$sql ='SELECT ';
		if($language != 'german') {
			$sql.='CASE WHEN `Name_'.$language.'` != "" THEN `Name_'.$language.'` ELSE `Name` END AS ';
		}
		$sql.='Name, l.Layer_ID, alias, Datentyp, Gruppe, pfad, maintable, Data, `schema`, document_path, labelitem, connection, printconnection, connectiontype, epsg_code, tolerance, wms_name, wms_auth_username, wms_auth_password, wms_server_version, ows_srs, wfs_geom, selectiontype, querymap, processing, kurzbeschreibung, datenherr, metalink, status, ul.* FROM layer AS l, used_layer AS ul';
    $sql.=' WHERE l.Layer_ID=ul.Layer_ID AND Stelle_ID='.$this->stelle_id;
    if ($LayerName!='') {
      $sql.=' AND (l.Name LIKE "'.$LayerName.'" ';
      if(is_numeric($LayerName)){
        $sql.='OR l.Layer_ID = "'.$LayerName.'")';
      }
      else{
        $sql.=')';
      }
    }
		$sql.=' ORDER BY ul.drawingorder';
    #echo $sql.'<br>';
    $this->debug->write("<p>file:users.php class:rolle->getLayer - Abfragen der Layer zur Rolle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
    while ($rs=mysql_fetch_array($query)) {
      $layer[]=$rs;
    }
    return $layer;
  }
}class pgdatabase {  var $ist_Fortfuehrung;  var $debug;  var $loglevel;  var $defaultloglevel;  var $logfile;  var $defaultlogfile;  var $commentsign;  var $blocktransaction;	function pgdatabase() {
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
  }
  function open() {
  	if($this->port == '') $this->port = 5432;
    #$this->debug->write("<br>Datenbankverbindung öffnen: Datenbank: ".$this->dbName." User: ".$this->user,4);
		$connect_string = 'dbname='.$this->dbName.' port='.$this->port.' user='.$this->user.' password='.$this->passwd;
		if($this->host != 'localhost' AND $this->host != '127.0.0.1')$connect_string .= ' host='.$this->host;		// das beschleunigt den Connect extrem
    $this->dbConn=pg_connect($connect_string);
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
      #echo "<br>".$sql;
      $sql = "SET datestyle TO 'German';".$sql;
      if($this->schema != ''){
      	$sql = "SET search_path = ".$this->schema.", public;".$sql;
      }
      $query=pg_query($this->dbConn,$sql);
      //$query=0;
      if ($query==0) {
        $ret[0]=1;
        $ret[1]="Fehler bei SQL Anweisung:<br>".$sql."<br>".pg_result_error($query);
        echo "<br><b>".$ret[1]."</b>";
        $this->debug->write("<br><b>".$ret[1]."</b>",$debuglevel);
        if ($logsql) {
          $this->logfile->write($this->commentsign." ".$ret[1]);
        }
      }
      else {
      	# Abfrage wurde erfolgreich ausgeführt
        $ret[0]=0;
        $ret[1]=$query;
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
    $sql ="SELECT spatial_ref_sys.srid, srtext, alias, minx, miny, maxx, maxy FROM spatial_ref_sys ";
    $sql.="LEFT JOIN spatial_ref_sys_alias ON spatial_ref_sys_alias.srid = spatial_ref_sys.srid";
    # Wenn zu unterstützende SRIDs angegeben sind, ist die Abfrage diesbezüglich eingeschränkt
    $anzSupportedSRIDs = count($supportedSRIDs);
    if ($anzSupportedSRIDs > 0) {
      $sql.=" WHERE spatial_ref_sys.srid IN (".implode(',', $supportedSRIDs).")";
    }
    if($order)$sql.=" ORDER BY spatial_ref_sys.srid";
    #echo $sql;		
    $ret = $this->execSQL($sql, 4, 0);		
    if($ret[0]==0){
			$i = 0;
      while($row = pg_fetch_array($ret[1])){
      	if($row['alias'] != ''){
      		$row['srtext'] = $row['alias'];
      	}
      	else{
	        $explosion = explode('[', $row['srtext']);
	        if(strlen($explosion[1]) > 30){
	          $explosion[1] = substr($explosion[1], 0, 30);
	        }
	        $row['srtext'] = $explosion[1];
      	}
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
}class db_mapObj {  var $debug;  var $referenceMap;  var $Layer;  var $anzLayer;  var $nurAufgeklappteLayer;  var $Stelle_ID;  var $User_ID;  function db_mapObj($Stelle_ID,$User_ID) {
    global $debug;
    $this->debug=$debug;
    $this->Stelle_ID=$Stelle_ID;
    $this->User_ID=$User_ID;
  }
  function getlayerdatabase($layer_id, $host){
  	if($layer_id < 0){	# Rollenlayer
  		$sql ='SELECT `connection`, "'.CUSTOM_SHAPE_SCHEMA.'" as `schema` FROM rollenlayer WHERE -id = '.$layer_id.' AND connectiontype = 6';
  	}
  	else{
    	$sql ='SELECT `connection`, `schema` FROM layer WHERE Layer_ID = '.$layer_id.' AND connectiontype = 6';
  	}
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getlayerdatabase - Lesen des connection-Strings des Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs = mysql_fetch_array($query);
    $connectionstring = $rs[0];
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getlayerdatabase - Gefundener Connection String des Layers:<br>".$connectionstring, 4);
    if($connectionstring != ''){
      $layerdb = new pgdatabase();
      if($rs[1] == ''){
      	$rs[1] = 'public';
      }
      $layerdb->schema = $rs[1];
      $connection = explode(' ', trim($connectionstring));
      for($j = 0; $j < count($connection); $j++){
        if($connection[$j] != ''){
          $value = explode('=', $connection[$j]);
          if(strtolower($value[0]) == 'user'){
            $layerdb->user = $value[1];
          }
          if(strtolower($value[0]) == 'dbname'){
            $layerdb->dbName = $value[1];
          }
          if(strtolower($value[0]) == 'password'){
            $layerdb->passwd = $value[1];
          }
          if(strtolower($value[0]) == 'host'){
            $layerdb->host = $value[1];
          }
          if(strtolower($value[0]) == 'port'){
            $layerdb->port = $value[1];
          }
        }
      }
      if (!isset($layerdb->host)) {
        $layerdb->host = $host;
      }
      if (!$layerdb->open()) {
        echo 'Die Verbindung zur PostGIS-Datenbank konnte mit folgenden Daten nicht hergestellt werden:';
        echo '<br>Host: '.$layerdb->host;
        echo '<br>User: '.$layerdb->user;
        echo '<br>Datenbankname: '.$layerdb->dbName;
        exit;
      }
    }
    return $layerdb;
  }
  function read_layer_attributes($layer_id, $layerdb, $attributenames, $all_languages = false){
		global $language;
		if($attributenames != NULL){
			$einschr = ' AND name IN (\'';
			$einschr.= implode('\', \'', $attributenames);
			$einschr.= '\')';
		}
		$sql = 'SELECT ';
		if(!$all_languages AND $language != 'german') {
			$sql.='CASE WHEN `alias_'.$language.'` != "" THEN `alias_'.$language.'` ELSE `alias` END AS ';
		}
		$sql.='alias, `alias_low-german`, alias_english, alias_polish, alias_vietnamese, layer_id, name, real_name, tablename, table_alias_name, type, geometrytype, constraints, nullable, length, decimal_length, `default`, form_element_type, options, tooltip, `group`, raster_visibility, mandatory, quicksearch, `order`, privileg, query_tooltip FROM layer_attributes WHERE layer_id = '.$layer_id.$einschr.' ORDER BY `order`';
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_layer_attributes:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		$i = 0;
		while($rs=mysql_fetch_array($query)){
			$attributes['name'][$i]= $rs['name'];
			$attributes['indizes'][$rs['name']] = $i;
			$attributes['real_name'][$rs['name']]= $rs['real_name'];
			if($rs['tablename'])$attributes['table_name'][$i]= $rs['tablename'];
			if($rs['tablename'])$attributes['table_name'][$rs['name']] = $rs['tablename']; 
			if($rs['table_alias_name'])$attributes['table_alias_name'][$i]= $rs['table_alias_name'];
			if($rs['table_alias_name'])$attributes['table_alias_name'][$rs['name']]= $rs['table_alias_name'];
			$attributes['table_alias_name'][$rs['tablename']]= $rs['table_alias_name'];
			$attributes['type'][$i]= $rs['type'];
			if($rs['type'] == 'geometry'){
				$attributes['the_geom'] = $rs['name'];
			}
			$attributes['geomtype'][$i]= $rs['geometrytype'];
			$attributes['geomtype'][$rs['name']]= $rs['geometrytype'];
			$attributes['constraints'][$i]= $rs['constraints'];
			$attributes['constraints'][$rs['real_name']]= $rs['constraints'];
			$attributes['nullable'][$i]= $rs['nullable'];
			$attributes['length'][$i]= $rs['length'];
			$attributes['decimal_length'][$i]= $rs['decimal_length'];
		
			if(substr($rs['default'], 0, 6) == 'SELECT'){					# da Defaultvalues auch dynamisch sein können (z.B. 'now'::date) wird der Defaultwert erst hier ermittelt
				$ret1 = $layerdb->execSQL($rs['default'], 4, 0);					
				if($ret1[0]==0){
					$attributes['default'][$i] = array_pop(pg_fetch_row($ret1[1]));
				}
			}
			else{															# das sind die alten Defaultwerte ohne 'SELECT ' davor, ab Version 1.13 haben Defaultwerte immer ein SELECT, wenn man den Layer in dieser Version einmal gespeichert hat
				$attributes['default'][$i]= $rs['default'];
			}
			$attributes['form_element_type'][$i]= $rs['form_element_type'];
			$attributes['form_element_type'][$rs['name']]= $rs['form_element_type'];
			$rs['options'] = str_replace('$hist_timestamp', rolle::$hist_timestamp, $rs['options']);
			$attributes['options'][$i]= $rs['options'];
			$attributes['options'][$rs['name']]= $rs['options'];
			$attributes['alias'][$i]= $rs['alias'];
			$attributes['alias'][$attributes['name'][$i]]= $rs['alias'];
			$attributes['alias_low-german'][$i]= $rs['alias_low-german'];
			$attributes['alias_english'][$i]= $rs['alias_english'];
			$attributes['alias_polish'][$i]= $rs['alias_polish'];
			$attributes['alias_vietnamese'][$i]= $rs['alias_vietnamese'];
			$attributes['tooltip'][$i]= $rs['tooltip'];
			$attributes['group'][$i]= $rs['group'];
			$attributes['raster_visibility'][$i]= $rs['raster_visibility'];
			$attributes['mandatory'][$i]= $rs['mandatory'];
			$attributes['quicksearch'][$i]= $rs['quicksearch'];
			$attributes['privileg'][$i]= $rs['privileg'];
			$attributes['query_tooltip'][$i]= $rs['query_tooltip'];
			$i++;
		}
		if($attributes['table_name'] != NULL){   
			$attributes['all_table_names'] = array_unique($attributes['table_name']);
			//$attributes['all_alias_table_names'] = array_values(array_unique($attributes['table_alias_name']));
			foreach($attributes['all_table_names'] as $tablename){
				$attributes['oids'][] = $layerdb->check_oid($tablename);   # testen ob Tabelle oid hat
			}
		}
		else{
			$attributes['all_table_names'] = array();
		}
		return $attributes;
  }
  function add_attribute_values($attributes, $database, $query_result, $withvalues = true){
    # Diese Funktion fügt den Attributen je nach Attributtyp zusätzliche Werte hinzu. Z.B. bei Auswahlfeldern die Auswahlmöglichkeiten.
    for($i = 0; $i < count($attributes['name']); $i++){
			if($attributes['constraints'][$i] != '' AND !in_array($attributes['constraints'][$i], array('PRIMARY KEY', 'UNIQUE'))){  # das sind die Auswahlmöglichkeiten, die durch die Tabellendefinition in Postgres fest vorgegeben sind
      	$attributes['enum_value'][$i] = explode(',', str_replace("'", "", $attributes['constraints'][$i]));
      	$attributes['enum_output'][$i] = $attributes['enum_value'][$i];
      }
      if($withvalues == true){
        switch($attributes['form_element_type'][$i]){
          # Auswahlfelder
          case 'Auswahlfeld' : {
            if($attributes['options'][$i] != ''){     # das sind die Auswahlmöglichkeiten, die man im Attributeditor selber festlegen kann
              if(strpos($attributes['options'][$i], "'") === 0){      # Aufzählung wie 'wert1','wert2','wert3'
                $attributes['enum_value'][$i] = explode(',', str_replace("'", "", $attributes['options'][$i]));
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
                  if($query_result != NULL){
                    for($k = 0; $k < count($query_result); $k++){
											$options = $attributes['options'][$i];
											foreach($attributes['name'] as $attributename){
												if(strpos($options, '<requires>'.$attributename.'</requires>') !== false AND $query_result[$k][$attributename] != ''){
													$options = str_replace('<requires>'.$attributename.'</requires>', "'".$query_result[$k][$attributename]."'", $options);
												}
											}
											if(strpos($options, '<requires>') !== false){
												$options = '';    # wenn in diesem Datensatz des Query-Results ein benötigtes Attribut keinen Wert hat (also nicht alle <requires>-Einträge ersetzt wurden), sind die abhängigen Optionen für diesen Datensatz leer
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
                        $attributes[$attributes['name'][$i]]['enum_value'][$k][] = $rs['value'];
                        $attributes['enum_output'][$i][$k][] = $rs['output'];
                      }
                    }
                  }
                }
                elseif($attributes['options'][$i] != ''){
                  $sql = $attributes['options'][$i];
                  $ret=$database->execSQL($sql,4,0);
                  if ($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
                  while($rs = pg_fetch_array($ret[1])){
                    $attributes['enum_value'][$i][] = $rs['value'];
                    $attributes[$attributes['name'][$i]]['enum_value'][] = $rs['value'];
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
										if($value != ''){
											$sql = 'SELECT * FROM ('.$sql.') as foo WHERE value = \''.$value.'\'';
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
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_used_Layer - Lesen eines Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    $layer = mysql_fetch_array($query);
    return $layer;
  }
}?>