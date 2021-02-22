<?php
###################################
# class_rolle #
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

	/*
	* Speichert den Status der Layergruppen
	* @param $formvars array mit key group_<group_id> welcher den Status der Gruppe enthält 
	*/
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

  function setSelectedButton($selectedButton) {
    $this->selectedButton=$selectedButton;
    # Eintragen des aktiven Button
    $sql ='UPDATE rolle SET selectedButton="'.$selectedButton.'"';
    $sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
    $this->debug->write("<p>file:rolle.php class:rolle->setSelectedButton - Speichern des zuletzt gewählten Buttons aus dem Kartenfensters:",4);
    $this->database->execSQL($sql,4, $this->loglevel);
    return 1;
  }

	function getLayer($LayerName) {
		global $language;
		$layer_name_filter = '';
		
		# Abfragen der Layer in der Rolle
		if ($language != 'german') {
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
	
	function set_print_legend_separate($separate){
		$sql ='UPDATE rolle SET print_legend_separate="'.$separate.'"';
    $sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
    # echo $sql;
    $this->debug->write("<p>file:rolle.php class:rolle function:set_print_legend_separate - Speichern der Einstellungen zur Rolle:",4);
    $this->database->execSQL($sql,4, $this->loglevel);
    return 1;
	}
	
	function switch_gle_view($layer_id){
		if($layer_id > 0){
			$sql ='UPDATE u_rolle2used_layer SET gle_view = CASE WHEN gle_view IS NULL THEN 0 ELSE NOT gle_view END';
			$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id.' AND layer_id='.$layer_id;
		}
		else{
			$sql ='UPDATE rollenlayer SET gle_view = CASE WHEN gle_view IS NULL THEN 0 ELSE NOT gle_view END';
			$sql.=' WHERE id='.(-$layer_id);
		}
    #echo $sql;
    $this->debug->write("<p>file:rolle.php class:rolle function:switch_gle_view - Speichern der Einstellungen zur Rolle:",4);
    $this->database->execSQL($sql,4, $this->loglevel);
    return 1;
  }
		
	function setLanguage($language) {
    $sql ='UPDATE rolle SET language="'.$language.'"';
    $sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
    # echo $sql;
    $this->debug->write("<p>file:rolle.php class:rolle function:setLanguage - Speichern der Einstellungen zur Rolle:",4);
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
  
	function saveDrawmode($always_draw){
		if($always_draw == '')$always_draw = 'false';
    $sql ='UPDATE rolle SET always_draw = '.$always_draw;
    $sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
    #echo $sql;
    $this->debug->write("<p>file:rolle.php class:rolle function:saveDrawmode - Speichern der Einstellungen zur Rolle:",4);
    $this->database->execSQL($sql,4, $this->loglevel);
    return 1;
  }
	
	function savePrintScale($print_scale){
    $sql ='UPDATE rolle SET print_scale = "'.$print_scale.'"';
    $sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
    #echo $sql;
    $this->debug->write("<p>file:rolle.php class:rolle function:savePrintScale - Speichern der Einstellungen zur Rolle:",4);
    $this->database->execSQL($sql,4, $this->loglevel);
    return 1;
  }	

	function saveGeomFromLayer($layer_id, $geom_from_layer_id) {
		$sql = "
			UPDATE u_rolle2used_layer
			SET geom_from_layer = " . $geom_from_layer_id . "
			WHERE
				user_id = " . $this->user_id . " AND
				stelle_id = " . $this->stelle_id . " AND
				layer_id = " . $layer_id . "
		";
		#echo $sql;
		$this->debug->write("<p>file:rolle.php class:rolle function:saveGeomFromLayer - Speichern der Einstellungen zur Rolle:", 4);
		$this->database->execSQL($sql, 4, $this->loglevel);
		return 1;
	}

	function setHistTimestamp($timestamp, $go_next = '') {
		$sql ='UPDATE rolle SET ';
		if($timestamp != ''){
			$time = new DateTime(DateTime::createFromFormat('d.m.Y H:i:s', $timestamp)->format('Y-m-d H:i:s'));
			$sql.='hist_timestamp="'.$time->format('Y-m-d H:i:s').'"';
			showAlert('Der Zeitpunkt der ALKIS-Historie wurde auf '.$time->format('d.m.Y H:i:s').' geändert.');
		}
		else{
			$sql.='hist_timestamp = NULL';
			if(rolle::$hist_timestamp != '')showAlert('Der Zeitpunkt der ALKIS-Historie ist jetzt wieder aktuell.');
		}
		$sql.=' WHERE stelle_id='.$this->stelle_id.' AND user_id='.$this->user_id;		
		#echo $sql;
		$this->debug->write("<p>file:rolle.php class:rolle->setHistTimestamp - Setzen der Einstellungen für die Rolle<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		$this->debug->write('Neue Werte für Rolle eingestellt: '.$formvars['nZoomFactor'].', '.$formvars['mapsize'],4);
		if($go_next != ''){
			$this->readSettings();
			go_switch($go_next);
			exit();
		}
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

	function get_layer_params($selectable_layer_params, $pgdatabase) {
		$layer_params = array();
		if (!empty($selectable_layer_params)) {
			$sql = "
				SELECT
					*
				FROM
					layer_parameter
				WHERE
					id IN (" . $selectable_layer_params . ")
			";
			#echo '<br>Sql: ' . $sql;
			$this->database->execSQL($sql, 4, 0);
			if (!$this->database->success) {
				echo '<br>Fehler bei der Abfrage der Layerparameter mit SQL: ' . $sql;
			}
			else {
				while ($param = $this->database->result->fetch_assoc()) {
					$sql = $param['options_sql'];
					$sql = str_replace('$user_id', $this->user_id, $sql);
					$sql = str_replace('$stelle_id', $this->stelle_id, $sql);
					#echo '<br>sql: ' . $sql;
					$options_result = $pgdatabase->execSQL($sql, 4, 0, false);
					if ($options_result['success']) {
						$param['options'] = array();
						while ($option = pg_fetch_assoc($options_result[1])) {
							$param['options'][] = $option;
						}
						$layer_params[$param['key']] = $param;
					}
					else {
						$layer_params['error_message'] = 'Fehler bei der Abfrage der Parameteroptionen: ' . $options_result[1];
					}
				}
			}
		}
		return $layer_params;
	}

	function set_layer_params($layer_params) {
		$sql = "
			UPDATE
				rolle
			SET
				`layer_params` = '" . $layer_params . "'
			WHERE
				user_id = " . $this->user_id . " AND
				stelle_id = " . $this->stelle_id . "
		";
		#echo $sql;
		$ret = $this->database->execSQL($sql,4, 1);
		rolle::$layer_params = (array)json_decode('{' . $layer_params . '}');
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

  # 2006-02-16 pk
  function getLastConsumeTime() {
    $sql ='SELECT time_id,prev FROM u_consume';
    $sql.=' WHERE stelle_id='.$this->stelle_id.' AND user_id='.$this->user_id;
    $sql.=' ORDER BY time_id DESC limit 1';
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
  
# 2006-03-20 pk
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

  # 2006-03-20 pk
  function updatePrevConsumeTime($time_id,$prevtime) {
    $sql ='UPDATE u_consume SET prev="'.$prevtime.'"';
    $sql.=' WHERE time_id="'.$time_id.'"';
    #echo '<br>'.$sql;
    $queryret=$this->database->execSQL($sql,4, 1);
    if ($queryret[0]) {
      # Fehler bei Datenbankanfrage
      $ret[0]=1;
      $ret[1]='<br>Fehler bei der Aktualisierung des Zeitstempels des Vorgängers Prev.<br>'.$ret[1];
    }
    else {
      $ret[0]=0;
      $ret[1]=1;
    }
    return $ret;
  }


  function setConsumeALK($time,$druckrahmen_id) {
    if (LOG_CONSUME_ACTIVITY==1) {
      # function setzt eine ALK-PDF-EXportaktivität
      $sql ='INSERT INTO u_consumeALK SET';
      $sql.=' user_id='.$this->user_id;
      $sql.=', stelle_id='.$this->stelle_id;
      $sql.=', time_id="'.$time.'"';
      $sql .= ', druckrahmen_id = "'.$druckrahmen_id.'"';
      #echo $sql;
      $ret=$this->database->execSQL($sql,4, 1);
      if ($ret[0]) {
        # Fehler bei Datenbankanfrage
        $errmsg.='<br>Die Verbraucheraktivität konnte nicht eingetragen werden.<br>'.$ret[1];
      }
    }
    else {
      $ret[0]=0;
      $ret[1]='<br>Funktion zur Speicherung der Verbraucheraktivitäten ist ausgeschaltet (LOG_CONSUME_ACTIVITY).';
    }
    return $ret;
  }


# 2006-03-20 pk
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
        $sql ='INSERT IGNORE INTO u_consume SET';
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
            $sql ='INSERT IGNORE INTO u_consume2layer SET';
            $sql.=' user_id='.$this->user_id;
            $sql.=', stelle_id='.$this->stelle_id;
            $sql.=', time_id="'.$time.'"';
            $sql.=', layer_id='.$layer[$i];
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
	
	function save_last_query($go, $layer_id, $query, $sql_order, $limit, $offset){
		if($limit == '')$limit = 'NULL';
		if($offset == '')$offset = 'NULL';
		$sql = "INSERT INTO rolle_last_query (user_id, stelle_id, go, layer_id, `sql`, orderby, `limit`, `offset`) VALUES (";
		$sql.= $this->user_id.", ".$this->stelle_id.", '".$go."', ".$layer_id.", '".addslashes($query)."', '".addslashes($sql_order)."', ".$limit.", ".$offset.")";
		$this->debug->write("<p>file:rolle.php class:rolle->save_last_query - Speichern der letzten Abfrage:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}
	
	function delete_last_query(){
		$sql = "DELETE FROM rolle_last_query WHERE user_id = ".$this->user_id." AND stelle_id = ".$this->stelle_id;
		$this->debug->write("<p>file:rolle.php class:rolle->delete_last_query - Löschen der letzten Abfrage:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}
	
	function get_last_query($layer_id = NULL){
		$sql = "SELECT * FROM rolle_last_query WHERE user_id = ".$this->user_id." AND stelle_id = ".$this->stelle_id;
		if($layer_id != NULL)$sql .= " AND layer_id = ".$layer_id;
		$this->debug->write("<p>file:rolle.php class:rolle->get_last_query - Abfragen der letzten Abfrage:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		while ($rs = $this->database->result->fetch_assoc()) {
			$last_query['go'] = $rs['go'];
			$last_query['layer_ids'][] = $rs['layer_id'];
			$last_query[$rs['layer_id']] = $rs;
		}
		return $last_query;
	}
	
	function get_last_search_layer_id(){
		$sql = "SELECT layer_id FROM search_attributes2rolle WHERE name = '<last_search>' AND user_id = ".$this->user_id." AND stelle_id = ".$this->stelle_id;
		$this->debug->write("<p>file:rolle.php class:rolle->get_last_search_layer_id - Abfragen der letzten Suche:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		$rs = $this->database->result->fetch_assoc();
		return $rs['layer_id'];
	}
	
	function get_csv_attribute_selections(){
		$sql = 'SELECT name FROM rolle_csv_attributes WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id.' ORDER BY name';
		$this->debug->write("<p>file:rolle.php class:rolle->get_csv_attribute_selections - Abfragen der gespeicherten CSV-Attributlisten der Rolle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		while ($rs = $this->database->result->fetch_assoc()) {
			$attribute_selections[]=$rs;
		}
		return $attribute_selections;
	}

	function get_csv_attribute_selection($name){
		$sql = 'SELECT name, attributes FROM rolle_csv_attributes WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id.' AND name = "'.$name.'"';
		$this->debug->write("<p>file:rolle.php class:rolle->get_csv_attribute_selection - Abfragen einer CSV-Attributliste der Rolle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		$rs = $this->database->result->fetch_assoc();
		return $rs;
	}

	function save_csv_attribute_selection($name, $attributes){
		# alle anderen Listen unter dem Namen löschen
		$this->delete_csv_attribute_selection($name);
		$sql = 'INSERT INTO rolle_csv_attributes (user_id, stelle_id, name, attributes) VALUES ('.$this->user_id.', '.$this->stelle_id.', "'.$name.'", "'.$attributes.'");';
		$this->debug->write("<p>file:rolle.php class:rolle->save_search - Speichern einer Attributauswahl:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}

	function delete_csv_attribute_selection($name){
		if($name != ''){
			$sql = 'DELETE FROM rolle_csv_attributes WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id.' AND name = "'.$name.'"';
			$this->debug->write("<p>file:rolle.php class:rolle->delete_search - Loeschen einer Suchabfrage:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
		}
	}

	function save_search($attributes, $formvars){
		$search_params_set = false;
		# alle anderen Suchabfragen unter dem Namen löschen
		$this->delete_search($formvars['search_name'], $formvars['selected_layer_id']);
		for($m = 0; $m <= $formvars['searchmask_count']; $m++){
			if($m > 0){				// es ist nicht die erste Suchmaske, sondern eine weitere hinzugefügte
				$prefix = $m.'_';
			}
			else{
				$prefix = '';
			}
			for($i = 0; $i < count($attributes['name']); $i++){
				if(value_of($formvars, $prefix.'value_'.$attributes['name'][$i]) != '' OR value_of($formvars, $prefix.'operator_'.$attributes['name'][$i]) == 'IS NULL' OR value_of($formvars, $prefix.'operator_'.$attributes['name'][$i]) == 'IS NOT NULL'){
					if(is_array($formvars[$prefix.'value_'.$attributes['name'][$i]])){
						$formvars[$prefix.'value_'.$attributes['name'][$i]] = json_encode($formvars[$prefix.'value_'.$attributes['name'][$i]], JSON_UNESCAPED_UNICODE);
					}
					$search_params_set = true;
					$sql = "INSERT INTO search_attributes2rolle VALUES ('".$formvars['search_name']."', ".$this->user_id.", ".$this->stelle_id.", ".$formvars['selected_layer_id'].", '".$attributes['name'][$i]."', '".value_of($formvars, $prefix.'operator_'.$attributes['name'][$i])."', '".$formvars[$prefix.'value_'.$attributes['name'][$i]]."', '".$formvars[$prefix.'value2_'.$attributes['name'][$i]]."', ".$m.", '".value_of($formvars, 'boolean_operator_'.$m)."');";
					$this->debug->write("<p>file:rolle.php class:rolle->save_search - Speichern einer Suchabfrage:",4);
					$this->database->execSQL($sql,4, $this->loglevel);
				}
			}
			if(!$search_params_set){		# keine Suchparameter gesetzt -> erstes Attribut speichern, damit Suche mit der Auswahl des Layers trotzdem gespeichert ist
				$sql = 'INSERT INTO search_attributes2rolle VALUES ("'.$formvars['search_name'].'", '.$this->user_id.', '.$this->stelle_id.', '.$formvars['selected_layer_id'].', "'.$attributes['name'][0].'", "'.$formvars[$prefix.'operator_'.$attributes['name'][0]].'", NULL, NULL, 0, NULL);';
				$this->debug->write("<p>file:rolle.php class:rolle->save_search - Speichern einer Suchabfrage:",4);
				$this->database->execSQL($sql,4, $this->loglevel);
			}
		}
	}

	function delete_search($search, $layer_id = NULL){
		if($search != ''){
			$sql = 'DELETE FROM search_attributes2rolle WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id.' AND name = "'.$search.'"';
			if($layer_id != NULL)$sql.=' AND layer_id='.$layer_id;
			$this->debug->write("<p>file:rolle.php class:rolle->delete_search - Loeschen einer Suchabfrage:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
		}
	}

	function getsearches($layer_id){
		$searches = array();
		$sql = 'SELECT distinct a.name, a.layer_id, b.Name as layername FROM search_attributes2rolle as a, layer as b WHERE a.name != \'<last_search>\' AND a.layer_id = b.Layer_ID AND user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		if($layer_id != '') $sql.= ' AND a.layer_id='.$layer_id;
		$sql .= ' ORDER BY b.Name, a.name';
		$this->debug->write("<p>file:rolle.php class:rolle->getsearches - Abfragen der gespeicherten Suchabfragen der Rolle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		while ($rs = $this->database->result->fetch_assoc()) {
			$searches[]=$rs;
		}
		return $searches;
	}

	function getsearch($layer_id, $name){
		$sql = 'SELECT * FROM search_attributes2rolle WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id.' AND layer_id='.$layer_id.' AND name = "'.$name.'" ORDER BY searchmask_number DESC';
		$this->debug->write("<p>file:rolle.php class:rolle->getsearch - Abfragen der gespeicherten Suchabfrage:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		while ($rs = $this->database->result->fetch_assoc()) {
			$json_test = json_decode($rs['value1']);
			if(is_array($json_test))$rs['value1'] = $json_test;
			$search[]=$rs;
		}
		return $search;
	}
	
	function saveExportSettings($formvars){
		$layerset = $this->getLayer($formvars['selected_layer_id']);
		$mapdb = new db_mapObj($this->stelle_id, $this->user_id);
		$layerdb = $mapdb->getlayerdatabase($formvars['selected_layer_id'], $stelle->pgdbhost);
		$this->attributes = $mapdb->read_layer_attributes($formvars['selected_layer_id'], $layerdb, NULL);
		# Attribute
		for ($i = 0; $i < count($this->attributes['name']); $i++) {
			if ($formvars['check_'.$this->attributes['name'][$i]]) {
				$selected_attributes[] = $this->attributes['name'][$i];
			}
		}
		$sql = "
			REPLACE INTO rolle_export_settings SET
				stelle_id = " . $this->stelle_id . ",
				user_id = " . $this->user_id . ",
				layer_id = " . $formvars['selected_layer_id'] . ",
				name = '" . $formvars['setting_name'] . "',
				format = '" . $formvars['export_format'] . "',
				epsg = " . $formvars['epsg'] . ",
				attributes = '" . implode(',', $selected_attributes) . "',
				metadata = " . ($formvars['with_metadata_document'] ?: '0'). ",
				groupnames = " . ($formvars['export_groupnames'] ? '1': '0'). ",
				documents = " . ($formvars['download_documents'] ?: '0') . ",
				geom = '" . $formvars['newpathwkt'] . "',
				within = " . ($formvars['within'] ?: '0'). ",
				singlegeom = " . ($formvars['singlegeom'] ?: '0'). "
		";
		$this->database->execSQL($sql, 4, 1);
	}
	
	function deleteExportSettings($formvars){
		$sql = "
			DELETE FROM 
				rolle_export_settings 
			WHERE
				stelle_id = " . $this->stelle_id . " AND 
				user_id = " . $this->user_id . " AND 
				layer_id = " . $formvars['selected_layer_id'] . " AND 
				name = '" . $formvars['export_setting'] . "'
		";
		$this->database->execSQL($sql, 4, 1);
	}
	
	function getExportSettings($layer_id, $name = NULL){
		$settings = array();
		$sql = "
			SELECT 
				* 
			FROM 
				rolle_export_settings 
			WHERE 
				layer_id = " . $layer_id . " AND
				user_id = " . $this->user_id . " AND 
				stelle_id = " . $this->stelle_id . 
				($name ? " AND name = '" . $name . "'": '') . "
			ORDER BY name";
		$this->debug->write("<p>file:rolle.php class:rolle->getsettings - Abfragen der gespeicherten Export-Einstellungen der Rolle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		while ($rs = $this->database->result->fetch_assoc()) {
			$settings[]=$rs;
		}
		return $settings;
	}

	function read_Group($id) {
		$sql ='SELECT g2r.*, g.Gruppenname FROM u_groups AS g, u_groups2rolle AS g2r';
		$sql.=' WHERE g2r.stelle_ID='.$this->stelle_id.' AND g2r.user_id='.$this->user_id.' AND g2r.id = g.id AND g.id='.$id;
		$this->debug->write("<p>file:kvwmap class:rolle->read_Group - Lesen einer Gruppe der Rolle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		$rs = $this->database->result->fetch_assoc();
		return $rs;
	}

	function setOneLayer($layer_id, $status){
		$sql ='UPDATE u_rolle2used_layer SET aktivStatus="'.$status.'"';
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		$sql.=' AND layer_id='.$layer_id;
		$this->debug->write("<p>file:rolle.php class:rolle->setOneLayer - Setzen eines Layers:",4);
		$this->database->execSQL($sql,4, $this->loglevel);

		$sql ='UPDATE u_rolle2used_layer set queryStatus="'.$status.'"';
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		$sql.=' AND layer_id='.$layer_id;
		$this->debug->write("<p>file:rolle.php class:rolle->setOneLayer - Setzen eines Layers:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}

	function setScrollPosition($scrollposition){
		if($scrollposition != ''){
			$sql = 'UPDATE rolle SET scrollposition = '.$scrollposition;
			$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
			$this->debug->write("<p>file:rolle.php class:rolle->setOneLayer - Setzen eines Layers:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
		}
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
	
	function resetLayers($layer_id){
		$this->update_layer_status($layer_id, '0');
	}

	function update_layer_status($layer_id, $status) {
		if($layer_id > 0 OR $layer_id == NULL){
			$sql = "
				UPDATE
					u_rolle2used_layer
				SET
					aktivStatus = '" . $status . "'
				WHERE
					user_id = " . $this->user_id . " AND
					stelle_id = " . $this->stelle_id .
					($layer_id != '' ? " AND layer_id = " . $layer_id : "");
			#echo '<br>Sql: ' . $sql;
			$this->debug->write("<p>file:rolle.php class:rolle->update_layer_status - schalte ein oder alle Layer Stati der Rolle um:", 4);
			$this->database->execSQL($sql, 4, $this->loglevel);		
		}
		if($layer_id < 0 OR $layer_id == NULL){
			$sql = "
				UPDATE
					rollenlayer
				SET
					aktivStatus = '" . $status . "'
				WHERE
					user_id = " . $this->user_id . " AND
					stelle_id = " . $this->stelle_id .
					($layer_id != '' ? " AND id = " . abs($layer_id) : "") . "
			";
			#echo '<br>Sql: ' . $sql;
			$this->debug->write("<p>file:rolle.php class:rolle->update_layer_status - schalte ein oder alle Layer Stati der Rolle um:", 4);
			$this->database->execSQL($sql, 4, $this->loglevel);		
		}
	}

	function resetQuerys($layer_id){
		if($layer_id > 0 OR $layer_id == NULL){
			$sql ="
				UPDATE 
					u_rolle2used_layer 
				SET 
					queryStatus = '0'
				WHERE 
					user_id=".$this->user_id." AND 
					stelle_id=".$this->stelle_id.
					($layer_id != '' ? " AND layer_id = ".$layer_id : "");
			$this->debug->write("<p>file:rolle.php class:rolle->resetQuerys - resetten aller aktiven Layer zur Rolle:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
		}
		if($layer_id < 0 OR $layer_id == NULL){
			$sql ="
				UPDATE 
					rollenlayer 
				SET 
					queryStatus = '0'
				WHERE 
					user_id=".$this->user_id." AND 
					stelle_id=".$this->stelle_id.
					($layer_id != '' ? " AND id = ".abs($layer_id) : "");
			$this->debug->write("<p>file:rolle.php class:rolle->resetQuerys - resetten aller aktiven Layer zur Rolle:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
		}
	}

	function resetClasses(){
		$sql = 'DELETE FROM u_rolle2used_class WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		$this->debug->write("<p>file:rolle.php class:rolle->resetQuerys - resetten aller aktiven Layer zur Rolle:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
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
			if($aktiv_status !== '' OR $requires_status !== ''){										// entweder ist der Layer selber an oder sein requires-Layer
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
	
	function setQueryStatus($formvars) {
		# Eintragen des query_status=1 für Layer, die für die Abfrage selektiert wurden
		for ($i=0; $i<count($this->layerset)-1; $i++){
			$query_status = value_of($formvars, 'qLayer'.value_of($this->layerset[$i], 'Layer_ID'));
			if($query_status !== ''){	
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
	
	function saveLegendOptions($layerset, $formvars){
		$sql ="	UPDATE rolle SET 
						legendtype=".$formvars['legendtype']." 
						WHERE user_id=".$this->user_id." AND stelle_id=".$this->stelle_id;
		#echo $sql;
		$this->debug->write("<p>file:rolle.php class:rolle function:saveLegendOptions - :",4);
		$this->database->execSQL($sql,4, $this->loglevel);
		if($formvars['active_layers'] != ''){
			$active_layers = $formvars['active_layers'];		// $active_layers ist ein Array mit den Layer-IDs der aktiven Layern in der neuen Reihenfolge
			$active_layer_count = count($active_layers);
			for($i = $active_layer_count-2; $i >= 0; $i--){		# von hinten beginnen
				$layer_oben = &$layerset['layer_ids'][$active_layers[$i]];
				$layer_unten = $layerset['layer_ids'][$active_layers[$i+1]];
				if($layer_oben['drawingorder'] <= $layer_unten['drawingorder']){		// drawingorder muss erhöht werden
					$newdrawingorder = $layer_unten['drawingorder'] + 1;
					$layer_oben['drawingorder'] = $newdrawingorder;
					$layers_changed[$layer_oben['id']] = true;
					$layers_changed[$layer_unten['id']] = true;		// muss gesetzt werden, obwohl die drawingorder des unteren Layers nicht verändert wird, damit er in der folgenden for-Schleife nicht erhöht wird
					$next_id = $layer_unten['id'] + 1;		// id des nächsten Layers im Layer-Array
					if($layerset['list'][$next_id]['drawingorder'] <= $newdrawingorder){		// wenn erforderlich auch die drawingorders der Layer darüber erhöhen
						$increase = $newdrawingorder - $layerset['list'][$next_id]['drawingorder'] + 1;		// um wieviel muss erhöht werden?
						for($j = $next_id; $j < count($layerset['list']); $j++){
							if($layers_changed[$layerset['list'][$j]['id']] !== true){		// nur, wenn dieser Layer nicht schon angepasst wurde
								$layerset['list'][$j]['drawingorder'] += $increase;
								$layers_changed[$j] = true;
							}
						}
					}
				}
			}
			if($layers_changed != ''){				
				foreach($layers_changed as $id => $value){
					$sql = 'UPDATE u_rolle2used_layer SET drawingorder = '.$layerset['list'][$id]['drawingorder'].' WHERE layer_id='.$layerset['list'][$id]['Layer_ID'].' AND user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
					$this->debug->write("<p>file:rolle.php class:rolle function:saveLegendOptions - :",4);
					$this->database->execSQL($sql,4, $this->loglevel);
				}
			}
		}
	}
	
	function removeDrawingOrders(){
		$sql ='UPDATE u_rolle2used_layer set drawingorder = NULL';
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		$this->debug->write("<p>file:rolle.php class:rolle->removeDrawingOrders:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}
	
	function setRollenLayerName($formvars){
		$sql ='UPDATE rollenlayer set Name = \''.$formvars['layer_options_name'].'\'';
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		$sql.=' AND id= -1*'.$formvars['layer_options_open'];
		$this->debug->write("<p>file:rolle.php class:rolle->setRollenLayerName:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}

	function setLabelitem($formvars) {
		if (isset($formvars['layer_options_labelitem'])) {
			if ($formvars['layer_options_open'] > 0) { # normaler Layer
				$sql ='UPDATE u_rolle2used_layer set labelitem = \''.$formvars['layer_options_labelitem'].'\'';
				$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
				$sql.=' AND layer_id='.$formvars['layer_options_open'];
				$this->debug->write("<p>file:rolle.php class:rolle->setLabelitem:",4);
				$this->database->execSQL($sql,4, $this->loglevel);
			}
			elseif($formvars['layer_options_open'] < 0) { # Rollenlayer
				$sql ='UPDATE rollenlayer set labelitem = \''.$formvars['layer_options_labelitem'].'\'';
				$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
				$sql.=' AND id= -1*'.$formvars['layer_options_open'];
				$this->debug->write("<p>file:rolle.php class:rolle->setLabelitem:",4);
				$this->database->execSQL($sql,4, $this->loglevel);
			}
		}
	}

	function removeLabelitem($formvars) {
		$sql ='UPDATE u_rolle2used_layer set labelitem = NULL';
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		$sql.=' AND layer_id='.$formvars['layer_options_open'];
		$this->debug->write("<p>file:rolle.php class:rolle->removeLabelitem:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}

	function setRollenFilter($formvars) {
		if (isset($formvars['layer_options_rollenfilter']) AND $formvars['layer_options_open'] <> 0) {
			# SQL-Injection verhindern
			$formvars['layer_options_rollenfilter'] = str_replace([';', '--'], '', $formvars['layer_options_rollenfilter']);
			if ($formvars['layer_options_open'] > 0) { # normaler Layer
				$table_name = "u_rolle2used_layer";
				$where_id = "layer_id = " . $formvars['layer_options_open'];
			}
			else { # layer_options_open < 0 Rollenlayer
				$table_name = "rollenlayer";
				$where_id = "id = -1*" . $formvars['layer_options_open'];
			}
			$sql = "
				UPDATE
					" . $table_name . "
				SET
					rollenfilter = '" . pg_escape_string($formvars['layer_options_rollenfilter']) . "'
				WHERE
					user_id = " . $this->user_id . " AND
					stelle_id = " . $this->stelle_id . " AND
					" . $where_id . "
			";
			#echo '<br>Sql: ' . $sql;
			$this->debug->write("<p>file:rolle.php class:rolle->setLabelitem:", 4);
			$this->database->execSQL($sql, 4, $this->loglevel);
		}
	}

	function setStyle($style_id, $formvars) {
		$sql ='
			UPDATE 
				styles 
			SET 
				color = "'.$formvars['layer_options_color'].'",
				symbolname = CASE WHEN symbolname IS NULL OR symbolname = "hatch" OR symbolname = "" THEN "'.$formvars['layer_options_hatching'].'" ELSE symbolname END
			WHERE 
				Style_ID = '.$style_id;
		$this->debug->write("<p>file:rolle.php class:rolle->setColor:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}

	function setTransparency($formvars) {
		if($formvars['layer_options_open'] > 0){		# normaler Layer
			$sql ='
				UPDATE u_rolle2used_layer 
				INNER JOIN layer ON layer.Layer_ID = u_rolle2used_layer.layer_id
				set u_rolle2used_layer.transparency = '.$formvars['layer_options_transparency'].'
				WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id.'
				AND (u_rolle2used_layer.layer_id='.$formvars['layer_options_open'].' OR requires = '.$formvars['layer_options_open'].')';
			$this->debug->write("<p>file:rolle.php class:rolle->setTransparency:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
		}
		elseif($formvars['layer_options_open'] < 0){		# Rollenlayer
			$sql ='UPDATE rollenlayer set transparency = '.$formvars['layer_options_transparency'];
			$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
			$sql.=' AND id= -1*'.$formvars['layer_options_open'];
			$this->debug->write("<p>file:rolle.php class:rolle->setTransparency:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
		}
	}
	
	function removeTransparency($formvars) {
		$sql ='UPDATE u_rolle2used_layer set transparency = NULL';
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		$sql.=' AND layer_id='.$formvars['layer_options_open'];
		$this->debug->write("<p>file:rolle.php class:rolle->removeTransparency:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
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

	function setSavedLayersFromDefaultUser($user_id, $stelle_id, $default_user_id){
		# Gespeicherte Themeneinstellungen von default user übernehmen
		if ($default_user_id > 0 AND $default_user_id != $user_id) {
			$sql = "
				INSERT INTO `rolle_saved_layers` (
					`user_id`,
					`stelle_id`,
					`name`,
					`layers`,
					`query`
				)
				SELECT " .
					$user_id . "," .
					$stelle_id . ",
					`name`,
					`layers`,
					`query`
				FROM
					`rolle_saved_layers`
				WHERE
					`user_id` = " . $default_user_id . " AND
					`stelle_id` = " . $stelle_id . "
			";
			#echo '<br>'.$sql;
			$this->debug->write("<p>file:rolle.php class:rolle function:setSavedLayersFromDefaultUser :<br>" . $sql, 4);
			$ret = $this->database->execSQL($sql, 4, 0);
			if (!$ret['success']) {
				$this->debug->write("<br>Abbruch in " . $PHP_SELF . " Zeile: " . __LINE__ . $ret[1], 4);
				return 0;
			}
		}
		return 1;
	}

	function setRolle($user_id, $stelle_id, $default_user_id) {
		# trägt die Rolle für einen Benutzer ein.
		if ($default_user_id > 0 AND $default_user_id != $user_id) {
			# Rolleneinstellungen vom Defaultnutzer verwenden
			$sql = "
				INSERT IGNORE INTO `rolle` (
					`user_id`,
					`stelle_id`,
					`nImageWidth`, `nImageHeight`,
					`auto_map_resize`,
					`minx`, `miny`, `maxx`, `maxy`,
					`nZoomFactor`,
					`selectedButton`,
					`epsg_code`,
					`epsg_code2`,
					`coordtype`,
					`active_frame`,
					`last_time_id`,
					`gui`,
					`language`,
					`hidemenue`,
					`hidelegend`,
					`fontsize_gle`,
					`highlighting`,
					`buttons`,
					`scrollposition`,
					`result_color`,
					`result_hatching`,
					`result_transparency`,
					`always_draw`,
					`runningcoords`,
					`showmapfunctions`,
					`showlayeroptions`,
					`showrollenfilter`,
					`singlequery`,
					`querymode`,
					`geom_edit_first`,
					`immer_weiter_erfassen`,
					`overlayx`, `overlayy`,
					`instant_reload`,
					`menu_auto_close`,
					`layer_params`,
					`visually_impaired`,
					`menue_buttons`
				) 
				SELECT ".
					$user_id.", ".
					$stelle_id.",
					`nImageWidth`, `nImageHeight`,
					`auto_map_resize`,
					`minx`, `miny`, `maxx`, `maxy`,
					`nZoomFactor`,
					`selectedButton`,
					`epsg_code`,
					`epsg_code2`,
					`coordtype`,
					`active_frame`,
					`last_time_id`,
					`gui`,
					`language`,
					`hidemenue`,
					`hidelegend`,
					`fontsize_gle`,
					`highlighting`,
					`buttons`,
					`scrollposition`,
					`result_color`,
					`result_hatching`,
					`result_transparency`,
					`always_draw`,
					`runningcoords`,
					`showmapfunctions`,
					`showlayeroptions`,
					`showrollenfilter`,
					`singlequery`,
					`querymode`,
					`geom_edit_first`,
					`immer_weiter_erfassen`,
					`overlayx`, `overlayy`,
					`instant_reload`,
					`menu_auto_close`,
					`layer_params`,
					`visually_impaired`,
					`menue_buttons`
				FROM
					`rolle`
				WHERE
					`user_id` = ".$default_user_id." AND
					`stelle_id` = ".$stelle_id."
			";
		}
		else {
			# Default - Rolleneinstellungen verwenden
			$sql = "
				INSERT IGNORE INTO rolle (user_id, stelle_id, epsg_code, minx, miny, maxx, maxy)
				SELECT " .
					$user_id . ",
					ID,
					epsg_code,
					minxmax,
					minymax,
					maxxmax,
					maxymax
				FROM
					stelle
				WHERE
					ID = ".$stelle_id."
			";
		}
		#echo '<br>'.$sql;
		$this->debug->write("<p>file:rolle.php class:rolle function:setRolle - Einfügen einer neuen Rolle:<br>" . $sql, 4);
		$ret = $this->database->execSQL($sql, 4, 0);
		if (!$ret['success']) {
			$this->debug->write("<br>Abbruch in " . $PHP_SELF . " Zeile: " . __LINE__ . $ret[1], 4);
			return 0;
		}
		return 1;
	}

	function deleteRollen($user_id, $stellen) {
		# löscht die übergebenen Stellen für einen Benutzer.
		for ($i = 0; $i < @count($stellen); $i++) {
			$sql = "
				DELETE FROM `rolle`
				WHERE
					`user_id` = " . $user_id . ' AND
					`stelle_id` = ' . $stellen[$i] . "
			";
			#echo '<br>'.$sql;
			$this->debug->write("<p>file:rolle.php class:rolle function:deleteRollen - Löschen der Rollen:<br>" . $sql, 4);
			$ret = $this->database->execSQL($sql, 4, 0);
			if (!$ret['success']) {
				$this->debug->write("<br>Abbruch in " . $PHP_SELF . " Zeile: " . __LINE__ . $ret[1], 4);
				return 0;
			}
			# rolle_nachweise
			if ($this->gui_object->plugin_loaded('nachweisverwaltung')) {
				$sql = "
					DELETE FROM `rolle_nachweise`
					WHERE
						`user_id` = " . $user_id . " AND
						`stelle_id` = " . $stellen[$i] . "
				";
				#echo '<br>'.$sql;
				$this->debug->write("<p>file:rolle.php class:rolle function:deleteRollen - Löschen der Rollen:<br>".$sql,4);
				$ret = $this->database->execSQL($sql, 4, 0);
				if (!$ret['success']) {
					$this->debug->write("<br>Abbruch in " . $PHP_SELF . " Zeile: " . __LINE__ . $ret[1], 4);
					return 0;
				}
			}
		}
		return 1;
	}

	function setMenue($user_id, $stelle_id, $default_user_id) {
		# trägt die Menuepunkte der übergebenen Stellenid für einen Benutzer ein.
		if ($default_user_id > 0 AND $default_user_id != $user_id) {
			# Menueeinstellungen von Defaultrolle abfragen
			$menue2rolle_select_sql = "
				SELECT " .
					$user_id . ", " .
					$stelle_id . ",
					`menue_id`,
					`status`
				FROM
					`u_menue2rolle`
				WHERE
					`stelle_id` = " . $stelle_id . " AND
					`user_id` = " . $default_user_id . "
			";
		}
		else {
			# Menueeinstellungen mit status 0 von stelle abfragen
			$menue2rolle_select_sql = "
				SELECT " .
					$user_id . ", " .
					$stelle_id . ",
					`menue_id`,
					0
				FROM
					`u_menue2stelle`
				WHERE
					`stelle_id` = " . $stelle_id . "
			";
		}
		$sql = "
			INSERT IGNORE INTO `u_menue2rolle` (
				`user_id`,
				`stelle_id`,
				`menue_id`,
				`status`
			) " .
			$menue2rolle_select_sql . "
		";
		#echo '<br>sql: ' . $sql;
		$this->debug->write("<p>file:rolle.php class:rolle function:setMenue - Setzen der Menuepunkte der Rolle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	function deleteMenue($user_id, $stellen, $menues) {
		# löscht die Menuepunkte der übergebenen Stellen für einen Benutzer.
		if($menues == 0) {
			for ($i = 0; $i < @count($stellen); $i++) {
				# löscht alle Menuepunkte der Stelle
				$sql = "
					DELETE FROM
						`u_menue2rolle`
					WHERE
						`user_id` = " . $user_id . " AND
						`stelle_id` = " . $stellen[$i] . "
				";
				#echo '<br>'.$sql;
				$this->debug->write("<p>file:rolle.php class:stelle function:deleteMenue - Löschen der Menuepunkte der Stelle:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			}
		}
		else {
			for ($i = 0; $i < count($stellen); $i++) {
				for ($j=0; $j < count($menues); $j++) {
					$sql = "
						DELETE FROM
							`u_menue2rolle`
						WHERE
							`user_id` = " . $user_id . " AND
							`stelle_id` = " . $stellen[$i] . " AND
							`menue_id` = " . $menues[$j] . "
					";
					#echo '<br>'.$sql;
					$this->debug->write("<p>file:rolle.php class:rolle function:deleteMenue - Löschen der Menuepunkte der Rollen:<br>".$sql,4);
					$this->database->execSQL($sql);
					if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
				}
			}
		}
		return 1;
	}


	function set_one_Group($user_id, $stelle_id, $group_id, $open) {
		$sql ='REPLACE INTO u_groups2rolle VALUES('.$user_id.', '.$stelle_id.', '.$group_id.', '.$open.')';
		$this->debug->write("<p>file:rolle.php class:rolle function:setGroups - Setzen der Gruppen der Rollen:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	function setGroups($user_id, $stelle_id, $default_user_id, $layerids) {
		# trägt die Gruppen und Obergruppen der übergebenen Stellenid und Layerids für einen Benutzer ein. Gruppen, die aktive Layer enthalten werden aufgeklappt
		if ($default_user_id > 0 AND $default_user_id != $user_id) {
			$sql = "
				INSERT IGNORE INTO 
					u_groups2rolle
				SELECT 
					".$user_id.",
					stelle_id,
					id,
					status
				FROM 
						u_groups2rolle
				WHERE
					stelle_id = ".$stelle_id." AND
					user_id = ".$default_user_id;
			#echo '<br>Gruppen: '.$sql;
			$this->debug->write("<p>file:rolle.php class:rolle function:setGroups - Setzen der Gruppen der Rolle:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		else {
			for($j = 0; $j < @count($layerids); $j++){
				$sql = "
					INSERT IGNORE INTO u_groups2rolle 
					SELECT DISTINCT 
						".$user_id.", 
						".$stelle_id.", 
						u_groups.id, 
						0
					FROM (
						SELECT 
							@id AS id, 
							@id := IF(@id IS NOT NULL, (SELECT obergruppe FROM u_groups WHERE id = @id), NULL) AS obergruppe
						FROM 
							u_groups, 
							(SELECT @id := (SELECT Gruppe FROM layer where layer.Layer_ID = ".$layerids[$j].")) AS vars
						WHERE @id IS NOT NULL
					) AS dat
					JOIN u_groups ON dat.id = u_groups.id";
				#echo '<br>Gruppen: '.$sql;
				$this->debug->write("<p>file:rolle.php class:rolle function:setGroups - Setzen der Gruppen der Rollen:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			}
		}
		return 1;
	}

	function deleteGroups($user_id,$stellen) {
		# löscht die Gruppen der übergebenen Stellen für einen Benutzer.
		for ($i = 0; $i < @count($stellen); $i++) {
			$sql ='DELETE FROM `u_groups2rolle` WHERE `user_id` = '.$user_id.' AND `stelle_id` = '.$stellen[$i];
			#echo '<br>'.$sql;
			$this->debug->write("<p>file:rolle.php class:rolle function:deleteGroups - Löschen der Gruppen der Rollen:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function updateGroups($user_id,$stelle_id, $layer_id) {
		# überprüft anHand der übergebenen layer_id ob die entsprechende Gruppe und deren Obergruppen in u_groups2rolle überflüssig sind
		#
		# Abfragen der Gruppe und der Obergruppen
		$sql ='SELECT DISTINCT u_groups.id';
		$sql.=' FROM (SELECT @id AS id, @id := IF(@id IS NOT NULL, (SELECT obergruppe FROM u_groups WHERE id = @id), NULL) AS obergruppe';
		$sql.='       FROM u_groups, (SELECT @id := (SELECT Gruppe FROM layer where layer.Layer_ID = '.$layer_id.')) AS vars';
		$sql.='       WHERE @id IS NOT NULL';
		$sql.='	    ) AS dat';
		$sql.='	JOIN u_groups ON dat.id = u_groups.id';
		#echo '<br>'.$sql;
		$this->debug->write("<p>file:rolle.php class:rolle function:updateGroups - überprüft anHand der übergebenen layer_id ob die entsprechende Gruppe in u_groups2rolle überflüssig ist:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		while ($rs = $this->database->result->fetch_row()) {
			$gruppen_ids[] = $rs[0];
		}
		if($gruppen_ids != NULL){
			# Test ob Layer in den Gruppen vorhanden sind
			$sql ='SELECT DISTINCT Gruppe FROM layer, u_rolle2used_layer AS r2ul WHERE Gruppe IN ('.implode(',', $gruppen_ids).') AND ';
			$sql.='r2ul.layer_id = layer.Layer_ID AND ';
			$sql.='r2ul.user_id = '.$user_id.' AND ';
			$sql.='r2ul.stelle_id = '.$stelle_id;
			#echo '<br>'.$sql;
			$this->debug->write("<p>file:rolle.php class:rolle function:updateGroups - überprüft anHand der übergebenen layer_id ob die entsprechende Gruppe in u_groups2rolle überflüssig ist:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			while ($rs = $this->database->result->fetch_assoc()) {
				$rs_layer[$rs['Gruppe']] = $rs['Gruppe'];		# ein Array mit den GruppenIDs, die noch Layer haben
			}
			
			# Test ob Untergruppen in den Gruppen vorhanden sind
			$sql ='SELECT u_groups.id FROM u_groups, u_groups2rolle as r2g WHERE u_groups.id NOT IN ('.implode(',', $gruppen_ids).') AND u_groups.obergruppe IN ('.implode(',', $gruppen_ids).') AND ';
			$sql.='r2g.id = u_groups.id AND ';
			$sql.='r2g.user_id = '.$user_id.' AND ';
			$sql.='r2g.stelle_id = '.$stelle_id;
			#echo '<br>'.$sql;
			$this->debug->write("<p>file:rolle.php class:rolle function:updateGroups - überprüft anHand der übergebenen layer_id ob die entsprechende Gruppe in u_groups2rolle überflüssig ist:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			$rs_subgroups = $this->database->result->fetch_assoc();
			
			if($rs_layer[$gruppen_ids[0]] == ''){					# wenn die erste Gruppe, also die Gruppe des Layers keine Layer hat, diese löschen
				$sql ='DELETE FROM `u_groups2rolle` WHERE `user_id` = '.$user_id.' AND `stelle_id` = '.$stelle_id.' AND `id` = '.$gruppen_ids[0].';';
				if($rs_layer == '' AND !$rs_subgroups[0]){				# wenn darüberhinaus keine Layer oder Untergruppen in den Gruppen darüber vorhanden sind, diese auch löschen
					$sql ='DELETE FROM `u_groups2rolle` WHERE `user_id` = '.$user_id.' AND `stelle_id` = '.$stelle_id.' AND `id` IN ('.implode(',', $gruppen_ids).');';
					$this->debug->write("<p>file:rolle.php class:rolle function:deleteGroups - Löschen der Gruppen der Rollen:<br>".$sql,4);
					$this->database->execSQL($sql);
					if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
				}
				$this->debug->write("<p>file:rolle.php class:rolle function:deleteGroups - Löschen der Gruppen der Rollen:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			}
		}
		return 1;
	}

	function set_one_Layer($user_id, $stelle_id, $layer_id,  $active) {
		$sql ='INSERT IGNORE INTO u_rolle2used_layer VALUES ('.$user_id.', '.$stelle_id.', '.$layer_id.', "'.$active.'", "0", "1", "0")';
		$this->debug->write("<p>file:rolle.php class:rolle function:set_one_Layer - Setzen eines Layers der Rolle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	function setLayer($user_id, $stelle_id, $default_user_id) {
		# trägt die Layer der entsprehenden Rolle für einen Benutzer ein.
		if ($default_user_id > 0 AND $default_user_id != $user_id) {
			# Layereinstellungen von Defaultrolle abfragen
			$rolle2used_layer_select_sql = "
				SELECT " .
					$user_id . ", " .
					$stelle_id . ",
					`layer_id`,
					`aktivStatus`,
					`queryStatus`,
					`gle_view`,
					`showclasses`,
					`logconsume`
				FROM
					u_rolle2used_layer
				WHERE
					user_id = " . $default_user_id . " AND
					stelle_id = " . $stelle_id . "
			";
		}
		else {
			# Layereinstellungen von Defaultlayerzuordnung abfragen
			$rolle2used_layer_select_sql = "
				SELECT " .
					$user_id . ", " .
					$stelle_id . ",
					`Layer_ID`,
					`start_aktiv`,
					`start_aktiv`,
					1,
					1,
					0
				FROM
					used_layer
				WHERE
					Stelle_ID = " . (int)$stelle_id . "
			";
		}
		# Layereinstellungen der Rolle eintragen
		$sql = "
			INSERT IGNORE INTO `u_rolle2used_layer` (
				`user_id`,
				`stelle_id`,
				`layer_id`,
				`aktivStatus`,
				`queryStatus`,
				`gle_view`,
				`showclasses`,
				`logconsume`
			) " .
			$rolle2used_layer_select_sql . "
		";
		$this->debug->write("<p>file:rolle.php class:rolle function:setLayer - Setzen der Layer der Rolle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	function deleteLayer($user_id, $stellen, $layer) {
		# löscht die Layer der übergebenen Stellen für einen Benutzer.
		for ($i = 0; $i < @count($stellen); $i++) {
			if (!is_array($layer)) {
				$layer = array();
			}
			for ($j = 0; $j < count($layer); $j++) {
				$sql = "
					DELETE
					FROM
						`u_rolle2used_layer`
					WHERE
						`stelle_id` = " . $stellen[$i] .
						($user_id != 0 ? " AND `user_id` = " . $user_id : "") .
						($layer != 0 ? " AND `layer_id` = " . $layer[$j] : "") . "
				";
				#echo '<br>'.$sql;
				$this->debug->write("<p>file:rolle.php class:rolle function:deleteLayer - Löschen der Layer der Rollen:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			}
		}
		return 1;
	}

	function readcolor(){
		return $this->database->read_color($this->result_color);
	}

	function hideMenue($hide) {
		# speichern des Zustandes des Menües
		# hide=0 Menü ist zu sehen
		# hide=1 Menü wird nicht angezeigt
		$this->hideMenue = $hide;
		$sql ="UPDATE rolle SET hidemenue='".$hide."'";
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		#echo $sql;
		$this->debug->write("<p>file:rolle.php class:rolle function:hideMenue - :",4);
		$this->database->execSQL($sql,4, $this->loglevel);
		return 1;
	}
	
	function changeLegendDisplay($hide) {
		# speichern des Zustandes der Legende
		# hide=0 Legende ist zu sehen
		# hide=1 Legende wird nicht angezeigt
		$sql ="UPDATE rolle SET hidelegend='".$hide."'";
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		#echo $sql;
		$this->debug->write("<p>file:rolle.php class:rolle function:hideMenue - :",4);
		$this->database->execSQL($sql,4, $this->loglevel);
		return 1;
	}	
	
	function saveOverlayPosition($x, $y){
		$sql ="UPDATE rolle SET overlayx = ".$x.", overlayy=".abs($y);
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		#echo $sql;
		$this->debug->write("<p>file:rolle.php class:rolle function:saveOverlayPosition - :",4);
		$this->database->execSQL($sql,4, $this->loglevel);
		return 1;
	}

	function getMapComments($consumetime, $public = false, $order) {
		$sql ='SELECT c.user_id, c.time_id, c.comment, c.public, u.Name, u.Vorname FROM u_consume2comments as c, user as u WHERE c.user_id = u.ID AND (';
		if($public)$sql.=' c.public OR';
		$sql.=' c.user_id='.$this->user_id;
		$sql.=') AND c.stelle_id='.$this->stelle_id;
		if ($consumetime!='') {
			$sql.=' AND time_id="'.$consumetime.'"';
		}
		$sql.=' ORDER BY '.replace_semicolon($order);
		#echo '<br>'.$sql;
		$queryret=$this->database->execSQL($sql,4, 0);
		if ($queryret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[0]=1;
			$ret[1]='<br>Fehler beim Laden des Kommentares zum Kartenausschnitt.<br>'.$ret[1];
		}
		else {
			while ($rs = $this->database->result->fetch_assoc()) {
				$mapComments[]=$rs;
			}
			$ret[0]=0;
			$ret[1]=$mapComments;
		}
		return $ret;
	}
	
	function getLayerComments($id, $user_id) {
		$where_id = ($id != '' ? " AND id = " . $id : "");
		$sql = "
			SELECT
				`id`,
				`name`,
				`layers`,
				`query`
			FROM
				`rolle_saved_layers`
			WHERE
				`user_id` = " . $user_id . " AND
				`stelle_id` = " . $this->stelle_id .
				$where_id . "
			ORDER BY
				`name`
		";
		#echo '<br>Sql: ' . $sql;

		$this->database->execSQL($sql, 4, 0);
		if (!$this->database->success) {
			# Fehler bei Datenbankanfrage
			$ret[0] = 1;
			$ret[1] = '<br>Fehler beim Laden der Themenauswahl.<br>' . $ret[1];
		}
		else {
			while ($rs = $this->database->result->fetch_assoc()) {
				$layerComments[] = $rs;
			}
			$ret[0] = 0;
			$ret[1] = $layerComments;
		}
		return $ret;
	}

	function insertMapComment($consumetime,$comment,$public) {
		if($public == '')$public = 0;
		$sql ='REPLACE INTO u_consume2comments SET';
		$sql.=' user_id='.$this->user_id;
		$sql.=', stelle_id='.$this->stelle_id;
		$sql.=', time_id="'.$consumetime.'"';
		$sql.=', comment="'.$comment.'"';
		$sql.=', public = '.$public;
		#echo '<br>'.$sql;
		$queryret=$this->database->execSQL($sql,4, 1);
		if ($queryret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[0]=1;
			$ret[1]='<br>Fehler beim Speichern des Kommentares zum Kartenausschnitt.<br>'.$ret[1];
		}
		else {
			$ret[0]=0;
			$ret[1]=1;
		}
		return $ret;
	}
	
	function insertLayerComment($layerset,$comment) {
		$layers = array();
		$query = array();
		$sql ='REPLACE INTO rolle_saved_layers SET';
		$sql.=' user_id='.$this->user_id;
		$sql.=', stelle_id='.$this->stelle_id;
		$sql.=', name="'.$comment.'"';
		for($i=0; $i < count($layerset['list']); $i++){
			if($layerset['list'][$i]['Layer_ID'] > 0 AND $layerset['list'][$i]['aktivStatus'] == 1){
				$layers[] = $layerset['list'][$i]['Layer_ID'];
				if($layerset['list'][$i]['queryStatus'] == 1)$query[] = $layerset['list'][$i]['Layer_ID'];
			}
		}
		$sql.=', layers="'.implode(',', $layers).'"';
		$sql.=', query="'.implode(',', $query).'"';
		#echo '<br>'.$sql;
		$queryret=$this->database->execSQL($sql,4, 1);
		if ($queryret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[0]=1;
			$ret[1]='<br>Fehler beim Speichern des Kommentares zur Layerauswahl.<br>'.$ret[1];
		}
		else {
			$ret[0]=0;
			$ret[1]=1;
		}
		return $ret;
	}

	function deleteMapComment($storetime){
		$sql = 'DELETE FROM u_consume2comments WHERE user_id = '.$this->user_id.' AND stelle_id = '.$this->stelle_id.' AND time_id = "'.$storetime.'"';
		#echo '<br>'.$sql;
		$queryret=$this->database->execSQL($sql,4, 1);
		if ($queryret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[0]=1;
			$ret[1]='<br>Fehler beim Löschen des Kommentares zum Kartenausschnitt.<br>'.$ret[1];
		}
	}
		
	function deleteLayerComment($id){
		$sql = 'DELETE FROM rolle_saved_layers WHERE user_id = '.$this->user_id.' AND stelle_id = '.$this->stelle_id.' AND id = "'.$id.'"';
		#echo '<br>'.$sql;
		$queryret=$this->database->execSQL($sql,4, 1);
		if ($queryret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[0]=1;
			$ret[1]='<br>Fehler beim Löschen der Themenauswahl.<br>'.$ret[1];
		}
	}

	function setConsumeALB($time,$format,$log_number,$wz,$pagecount) {
		if (LOG_CONSUME_ACTIVITY==1) {
			for($i = 0; $i < count($log_number); $i++){
				# function setzt eine ALB-PDF-EXportaktivität
				$sql ='INSERT IGNORE INTO u_consumeALB SET';
				$sql.=' user_id='.$this->user_id;
				$sql.=', stelle_id='.$this->stelle_id;
				$sql.=', time_id="'.$time.'"';
				$sql.=', format="'.$format.'"';
				$sql .= ', log_number = "'.$log_number[$i].'"';
				$sql .= ', wz = "'.$wz.'"';
				$sql .= ', numpages = '.$pagecount;
				#echo $sql.'<br>';
				$ret=$this->database->execSQL($sql,4, 1);
				if ($ret[0]) {
					# Fehler bei Datenbankanfrage
					$errmsg.='<br>Die Verbraucheraktivität konnte nicht eingetragen werden.<br>'.$ret[1];
				}
			}
		}
		else {
			$ret[0]=0;
			$ret[1]='<br>Funktion zur Speicherung der Verbraucheraktivitäten ist ausgeschaltet (LOG_CONSUME_ACTIVITY).';
		}
		return $ret;
	}

	function setConsumeCSV($time,$art,$numdatasets) {
		if (LOG_CONSUME_ACTIVITY==1) {
			$sql ='INSERT INTO u_consumeCSV SET';
			$sql.=' user_id='.$this->user_id;
			$sql.=', stelle_id='.$this->stelle_id;
			$sql.=', time_id="'.$time.'"';
			$sql.=', art="'.$art.'"';
			$sql .= ', numdatasets = "'.$numdatasets.'"';
			#echo $sql;
			$ret=$this->database->execSQL($sql,4, 1);
			if ($ret[0]) {
				# Fehler bei Datenbankanfrage
				$errmsg.='<br>Die Verbraucheraktivität konnte nicht eingetragen werden.<br>'.$ret[1];
			}
		}
		else {
			$ret[0]=0;
			$ret[1]='<br>Funktion zur Speicherung der Verbraucheraktivitäten ist ausgeschaltet (LOG_CONSUME_ACTIVITY).';
		}
		return $ret;
	}

	function setConsumeShape($time,$layerid,$numdatasets) {
		if (LOG_CONSUME_ACTIVITY==1) {
			$sql ='INSERT INTO u_consumeShape SET';
			$sql.=' user_id='.$this->user_id;
			$sql.=', stelle_id='.$this->stelle_id;
			$sql.=', time_id="'.$time.'"';
			$sql.=', layer_id="'.$layerid.'"';
			$sql .= ', numdatasets = "'.$numdatasets.'"';
			#echo $sql;
			$ret=$this->database->execSQL($sql,4, 1);
			if ($ret[0]) {
				# Fehler bei Datenbankanfrage
				$errmsg.='<br>Die Verbraucheraktivität konnte nicht eingetragen werden.<br>'.$ret[1];
			}
		}
		else {
			$ret[0]=0;
			$ret[1]='<br>Funktion zur Speicherung der Verbraucheraktivitäten ist ausgeschaltet (LOG_CONSUME_ACTIVITY).';
		}
		return $ret;
	}

}
?>