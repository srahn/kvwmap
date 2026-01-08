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
	static $language;
	static $hist_timestamp;
	static $layer_params;
	static $user_ID;
	static $stelle_ID;
	static $stelle_bezeichnung;
	static $export;
	var $minx;
	var $newtime;
	var $gui; // file to include as gui
	var $gui_object;
	var $layerset;
	var $data;

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

	/**
	 * Function create a rolle for a user with $user_id and all relations to menues, layers and layergroups in stelle with $stelle_id.
	 * If $default_user_id is given them settings will be used.
	 * @param database $database PostgreSQL-Database object from class pgdatabase defined in classes/postgresql.php
	 * @param Integer $stelle_id
	 * @param Integer $user_id
	 * @param Integer $default_user_id Die id eines Default-Users.
	 * @param Layer[] $layer Array with layer_ids to assign rolle to there groups.
	 * @return Array Result with Boolean success and String $msg.
	 */
	public static	function create($database, $stelle_id, $user_id, $default_user_id = 0, $layer = array(), $parent_stelle_id = NULL) {
		$rolle = new rolle($user_id, $stelle_id, $database);
		# Hinzufügen einer neuen Rolle (selektierte User zur Stelle)
		if (!$rolle->setRolle($user_id, $stelle_id, $default_user_id, $parent_stelle_id)) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Anlegen der Rolle.<br>' . $database->errormessage
			);
		}

		# Hinzufügen der selektierten Obermenüs zur Rolle
		if (!$rolle->setMenue($user_id, $stelle_id, $default_user_id)) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Zuordnen der Menüs der Stelle zum Nutzer.<br>' . $database->errormessage
			);
		}

		# Hinzufügen der Layer zur Rolle
		if (!$rolle->setLayer($user_id, $stelle_id, $default_user_id)) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Zuordnen des Layers zur Rolle.<br>' . $database->errormessage
			);
		}

		# Hinzufügen der Layergruppen der selektierten Layer zur Rolle
		if (!$rolle->setGroups($user_id, $stelle_id, $default_user_id, $layer)) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Hinzufügen der Layergruppen der selektierten Layer zur Rolle.<br>' . $database->errormessage
			);
		};	

		if (!$rolle->setSavedLayersFromDefaultUser($user_id, $stelle_id, $default_user_id)) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Zuordnen von savedLayersFromDefaultUser.<br>' . $database->errormessage
			);
		}

		return array(
			'success' => true,
			'msg' => 'Anlegen der Rolle erfolgreich.'
		);
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

	function setLayerSelection($id) {
    $sql = "
			UPDATE 
				kvwmap.rolle 
			SET 
				layer_selection = " . ($id ?: 'NULL') . "
    	WHERE 
				user_id = " . $this->user_id . ' 
				AND stelle_id = ' . $this->stelle_id;
    $this->debug->write("<p>file:rolle.php class:rolle->setLayerSelection:",4);
    $this->database->execSQL($sql,4, $this->loglevel);
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
				foreach (array('name', 'alias', 'connection', 'maintable', 'classification', 'pfad', 'data', 'metalink') as $key) {
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

	function set_print_legend_separate($separate){
		$sql = "
			UPDATE
				kvwmap.rolle
			SET
				print_legend_separate = " . ($separate == '' ? 0 : 1 ) . "
			WHERE
				user_id = " . $this->user_id . " AND
				stelle_id = " . $this->stelle_id . "
		";
		# echo $sql;
		$this->debug->write("<p>file:rolle.php class:rolle function:set_print_legend_separate - Speichern der Einstellungen zur Rolle:", 4);
		$this->database->execSQL($sql,4, $this->loglevel);
		return 1;
	}

	function switch_gle_view($layer_id, $mode) {
		if ($layer_id > 0) {
			$sql = "
				UPDATE
					kvwmap.u_rolle2used_layer
				SET
					gle_view = " . $mode . "
				WHERE
					user_id = " . $this->user_id . " AND
					stelle_id = " . $this->stelle_id . " AND
					layer_id = " . $layer_id . "
			";
		}
		else {
			$sql = "
				UPDATE
					kvwmap.rollenlayer
				SET
					gle_view = " . $mode . "
				WHERE
					id = " . (-$layer_id) . "
			";
		}
		#echo $sql;
		$this->debug->write("<p>file:rolle.php class:rolle function:switch_gle_view - Speichern der Einstellungen zur Rolle:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
		return 1;
	}

	function setLanguage($language) {
    $sql ='UPDATE kvwmap.rolle SET language="'.$language.'"';
    $sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
    # echo $sql;
    $this->debug->write("<p>file:rolle.php class:rolle function:setLanguage - Speichern der Einstellungen zur Rolle:",4);
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
  
	function saveDrawmode($always_draw){
		if($always_draw == '')$always_draw = 'false';
    $sql ='UPDATE kvwmap.rolle SET always_draw = '.$always_draw;
    $sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
    #echo $sql;
    $this->debug->write("<p>file:rolle.php class:rolle function:saveDrawmode - Speichern der Einstellungen zur Rolle:",4);
    $this->database->execSQL($sql,4, $this->loglevel);
    return 1;
  }
	
	function savePrintScale($print_scale){
    $sql ='UPDATE kvwmap.rolle SET print_scale = "'.$print_scale.'"';
    $sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
    #echo $sql;
    $this->debug->write("<p>file:rolle.php class:rolle function:savePrintScale - Speichern der Einstellungen zur Rolle:",4);
    $this->database->execSQL($sql,4, $this->loglevel);
    return 1;
  }	

	function saveGeomFromLayer($layer_id, $geom_from_layer_id) {
		$sql = "
			UPDATE 
				kvwmap.u_rolle2used_layer
			SET 
				geom_from_layer = " . $geom_from_layer_id . "
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
		$sql ='UPDATE kvwmap.rolle SET ';
		if($timestamp != ''){
			$time = new DateTime(DateTime::createFromFormat('d.m.Y H:i:s', $timestamp)->format('Y-m-d H:i:s'));
			$sql.= "hist_timestamp='" . $time->format('Y-m-d H:i:s') ."'";
			showAlert('Der Zeitpunkt für historische Daten wurde auf '.$time->format('d.m.Y H:i:s').' geändert.');
		}
		else{
			$sql.='hist_timestamp = NULL';
			if(rolle::$hist_timestamp != '')showAlert('Der Zeitpunkt für historische Daten ist jetzt wieder aktuell.');
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
			$this->menu_auto_close=$rs['layer_selection_mode'];
			rolle::$layer_params = array_map(
				function ($layer_param) {
					return ($layer_param === 'Array' ? '' : $layer_param);
				},
				(array)json_decode('{' . $rs['layer_params'] . '}')
			);
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
			$this->routing = in_array('routing', $buttons);
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

	function get_layer_params($selectable_layer_params, $pgdatabase) {
		$layer_params = array();
		if (!empty($selectable_layer_params)) {
			$sql = "
				SELECT
					*
				FROM
					kvwmap.layer_parameter
				WHERE
					id IN (" . $selectable_layer_params . ")
			";
			#echo '<br>Sql: ' . $sql;
			$ret = $this->database->execSQL($sql, 4, 0);
			if ($ret[0]) {
				echo '<br>Fehler bei der Abfrage der Layerparameter mit SQL: ' . $sql;
			}
			else {
				$ewkt_extent = 'SRID=' . $this->epsg_code . ';POLYGON((
							' . $this->oGeorefExt->minx . ' ' . $this->oGeorefExt->miny . ', 
							' . $this->oGeorefExt->minx . ' ' . $this->oGeorefExt->maxy . ', 
							' . $this->oGeorefExt->maxx . ' ' . $this->oGeorefExt->maxy . ', 
							' . $this->oGeorefExt->maxx . ' ' . $this->oGeorefExt->miny . ', 
							' . $this->oGeorefExt->minx . ' ' . $this->oGeorefExt->miny . '
							))';
				while ($param = pg_fetch_assoc($ret[1])) {
					$sql = $param['options_sql'];
					$sql = str_replace('$USER_ID', $this->user_id, $sql);
					$sql = str_replace('$STELLE_ID', $this->stelle_id, $sql);
					$sql = str_replace('$EXTENT', $ewkt_extent, $sql);
					#echo '<br>SQL zur Abfrage der Optionen des Layerparameter ' . $param['key'] . ': ' . $sql;
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
				kvwmap.rolle
			SET
				layer_params = '" . sanitize($layer_params, 'text') . "'
			WHERE
				user_id = " . $this->user_id . " AND
				stelle_id = " . $this->stelle_id . "
		";
		#echo '<br>SQL to set Layer params: ' . $sql;
		$ret = $this->database->execSQL($sql,4, 1);
		rolle::$layer_params = (array)json_decode('{' . $layer_params . '}');
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

	function getLastConsumeTime() {
		$sql = "
			SELECT
				time_id,
				prev
			FROM
				kvwmap.u_consume
			WHERE
				stelle_id = " . $this->stelle_id . " AND
				user_id = " . $this->user_id . "
			ORDER BY
				time_id DESC
			LIMIT 1
		";
		#echo '<br>'.$sql;
		$queryret = $this->database->execSQL($sql, 4, 0);
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

	function setConsumeALK($time,$druckrahmen_id) {
		if (LOG_CONSUME_ACTIVITY == 1) {
			# function setzt eine ALK-PDF-EXportaktivität
			$sql = "
				INSERT INTO
					kvwmap.u_consumealk
				VALUES (
					" . $this->user_id . ",
					" . $this->stelle_id . ",
					'" . $time . "',
					'" . $druckrahmen_id . "'
				)
			";
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
					ON CONFLICT (user_id, stelle_id, time_id) DO NOTHING";
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
	
	function save_last_query($go, $layer_id, $query, $sql_order, $limit, $offset) {
		$sql = "
			INSERT INTO kvwmap.rolle_last_query (
				user_id,
				stelle_id,
				go,
				layer_id,
				sql,
				orderby,
				\"limit\",
				\"offset\"
			)
			VALUES (
				" . $this->user_id . ",
				" . $this->stelle_id . ",
				'" . $go . "',
				" . $layer_id . ",
				'" . pg_escape_string($query) . "',
				'" . pg_escape_string($sql_order) . "',
				" . ($limit == '' ? 'NULL' : $limit) . ",
				" . ($offset == '' ? 'NULL' : $offset) . "
			)
		";
		$this->debug->write("<p>file:rolle.php class:rolle->save_last_query - Speichern der letzten Abfrage:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}
	
	function delete_last_query(){
		$sql = "DELETE FROM kvwmap.rolle_last_query WHERE user_id = ".$this->user_id." AND stelle_id = ".$this->stelle_id;
		$this->debug->write("<p>file:rolle.php class:rolle->delete_last_query - Löschen der letzten Abfrage:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}
	
	function get_last_query($layer_id = NULL){
		$sql = "SELECT * FROM kvwmap.rolle_last_query WHERE user_id = ".$this->user_id." AND stelle_id = ".$this->stelle_id;
		if($layer_id != NULL)$sql .= " AND layer_id = ".$layer_id;
		$this->debug->write("<p>file:rolle.php class:rolle->get_last_query - Abfragen der letzten Abfrage:<br>".$sql,4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		while ($rs = pg_fetch_assoc($ret[1])) {
			$last_query['go'] = $rs['go'];
			$last_query['layer_ids'][] = $rs['layer_id'];
			$last_query[$rs['layer_id']] = $rs;
		}
		return $last_query;
	}
	
	function get_last_search_layer_id(){
		$sql = "SELECT layer_id FROM kvwmap.search_attributes2rolle WHERE name = '<last_search>' AND user_id = ".$this->user_id." AND stelle_id = ".$this->stelle_id;
		$this->debug->write("<p>file:rolle.php class:rolle->get_last_search_layer_id - Abfragen der letzten Suche:<br>".$sql,4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		$rs = pg_fetch_assoc($ret[1]);
		return $rs['layer_id'];
	}
	
	function get_csv_attribute_selections(){
		$sql = 'SELECT name FROM kvwmap.rolle_csv_attributes WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id.' ORDER BY name';
		$this->debug->write("<p>file:rolle.php class:rolle->get_csv_attribute_selections - Abfragen der gespeicherten CSV-Attributlisten der Rolle:<br>".$sql,4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		while ($rs = pg_fetch_assoc($ret[1])) {
			$attribute_selections[]=$rs;
		}
		return $attribute_selections;
	}

	function get_csv_attribute_selection($name){
		$sql = "
			SELECT 
				name, 
				attributes 
			FROM 
				kvwmap.rolle_csv_attributes 
			WHERE 
				user_id = " . $this->user_id . " AND 
				stelle_id = " . $this->stelle_id . " AND 
				name = '" . $name . "'";
		$this->debug->write("<p>file:rolle.php class:rolle->get_csv_attribute_selection - Abfragen einer CSV-Attributliste der Rolle:<br>".$sql,4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		$rs = pg_fetch_assoc($ret[1]);
		return $rs;
	}

	function save_csv_attribute_selection($name, $attributes){
		# alle anderen Listen unter dem Namen löschen
		$this->delete_csv_attribute_selection($name);
		$sql = "
			INSERT INTO kvwmap.rolle_csv_attributes 
				(user_id, stelle_id, name, attributes) 
			VALUES (
				" . $this->user_id . ", 
				" . $this->stelle_id . ", 
				'" . $name . "', 
				'" . $attributes . "');";
		$this->debug->write("<p>file:rolle.php class:rolle->save_search - Speichern einer Attributauswahl:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}

	function delete_csv_attribute_selection($name){
		if($name != ''){
			$sql = "
				DELETE FROM 
					kvwmap.rolle_csv_attributes 
				WHERE 
					user_id = " . $this->user_id . " AND 
					stelle_id = " . $this->stelle_id . " AND 
					name = '" . $name . "'";
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
					$sql = "
						INSERT INTO 
							kvwmap.search_attributes2rolle 
						VALUES (
							'".$formvars['search_name']."', 
							".$this->user_id.", 
							".$this->stelle_id.", 
							".$formvars['selected_layer_id'].", 
							'".$attributes['name'][$i]."', 
							'".value_of($formvars, $prefix.'operator_'.$attributes['name'][$i])."', 
							'" . pg_escape_string($formvars[$prefix.'value_'.$attributes['name'][$i]]) . "', 
							'".$formvars[$prefix.'value2_'.$attributes['name'][$i]]."', 
							".$m.", 
							" . (value_of($formvars, 'boolean_operator_'.$m) != '' ? "'" . value_of($formvars, 'boolean_operator_' . $m) . "'" : "NULL") . ");";
					$this->debug->write("<p>file:rolle.php class:rolle->save_search - Speichern einer Suchabfrage:",4);
					$this->database->execSQL($sql,4, $this->loglevel);
				}
			}
			if(!$search_params_set){		# keine Suchparameter gesetzt -> erstes Attribut speichern, damit Suche mit der Auswahl des Layers trotzdem gespeichert ist
				$sql = "
					INSERT INTO 
						kvwmap.search_attributes2rolle 
					VALUES (
						'".$formvars['search_name']."', 
						".$this->user_id.", 
						".$this->stelle_id.", 
						".$formvars['selected_layer_id'].", 
						'".$attributes['name'][0]."', 
						'".$formvars[$prefix.'operator_'.$attributes['name'][0]]."', 
						NULL, 
						NULL, 
						0, 
						NULL);";
				$this->debug->write("<p>file:rolle.php class:rolle->save_search - Speichern einer Suchabfrage:",4);
				$this->database->execSQL($sql,4, $this->loglevel);
			}
		}
	}

	function delete_search($search, $layer_id = NULL){
		if($search != ''){
			$sql = '
				DELETE FROM 
					kvwmap.search_attributes2rolle 
				WHERE 
					user_id = ' . $this->user_id . ' AND 
					stelle_id = ' . $this->stelle_id . " AND 
					name = '" . $search . "'";
			if($layer_id != NULL)$sql.=' AND layer_id = ' . $layer_id;
			$this->debug->write("<p>file:rolle.php class:rolle->delete_search - Loeschen einer Suchabfrage:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
		}
	}

	function getsearches($layer_id){
		$searches = array();
		$sql = '
			SELECT distinct 
				a.name, 
				a.layer_id, 
				b.name as layername 
			FROM 
				kvwmap.search_attributes2rolle as a, 
				kvwmap.layer as b 
			WHERE 
				a.name != \'<last_search>\' AND 
				a.layer_id = b.layer_id AND 
				user_id = ' . $this->user_id . ' AND 
				stelle_id = ' . $this->stelle_id;
		if($layer_id != '') $sql.= ' AND a.layer_id = '.$layer_id;
		$sql .= ' ORDER BY b.name, a.name';
		$this->debug->write("<p>file:rolle.php class:rolle->getsearches - Abfragen der gespeicherten Suchabfragen der Rolle:<br>".$sql,4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		while ($rs = pg_fetch_assoc($ret[1])) {
			$searches[]=$rs;
		}
		return $searches;
	}

	function getsearch($layer_id, $name){
		$sql = "
			SELECT 
				* 
			FROM 
				kvwmap.search_attributes2rolle 
			WHERE 
				user_id = " . $this->user_id . " AND 
				stelle_id = " . $this->stelle_id . " AND 
				layer_id = " . $layer_id . " AND 
				name = '" . $name . "' 
			ORDER BY 
				searchmask_number DESC";
		$this->debug->write("<p>file:rolle.php class:rolle->getsearch - Abfragen der gespeicherten Suchabfrage:<br>".$sql,4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		while ($rs = pg_fetch_assoc($ret[1])) {
			$json_test = json_decode($rs['value1']);
			if(is_array($json_test))$rs['value1'] = $json_test;
			$search[]=$rs;
		}
		return $search;
	}

	function saveExportSettings($formvars) {
		$mapdb = new db_mapObj($this->stelle_id, $this->user_id);
		$layerdb = $mapdb->getlayerdatabase($formvars['selected_layer_id'], $stelle->pgdbhost);
		$this->attributes = $mapdb->read_layer_attributes($formvars['selected_layer_id'], $layerdb, NULL);
		# Attribute
		for ($i = 0; $i < count($this->attributes['name']); $i++) {
			if ($formvars['check_' . $this->attributes['name'][$i]]) {
				$selected_attributes[] = $this->attributes['name'][$i];
			}
		}
		$rows = [
			'stelle_id' => $this->stelle_id,
			'user_id' => $this->user_id,
			'layer_id' => $formvars['selected_layer_id'],
			'name' => "'" . $formvars['setting_name'] . "'",
			'format' => "'" . $formvars['export_format'] . "'",
			'epsg' => ($formvars['epsg'] ?: 'NULL'),
			'attributes' => "'" . implode(',', $selected_attributes) . "'",
			'metadata' => ($formvars['with_metadata_document'] ?: '0'),
			'groupnames' => ($formvars['export_groupnames'] ? '1': '0'),
			'documents' => ($formvars['download_documents'] ? '1' : '0'),
			'geom' => "'" . $formvars['newpathwkt'] . "'",
			'within' => ($formvars['within'] ?: '0'),
			'singlegeom' => ($formvars['singlegeom'] ?: '0')
		];
		$sql = "
			INSERT INTO
				kvwmap.rolle_export_settings
				(" . implode(', ', array_keys($rows)) . ")
			VALUES	
				(" . implode(', ', $rows) . ")
			ON CONFLICT (stelle_id, user_id, layer_id, name) DO	UPDATE 
				SET " .
					implode(', ',	array_map(function($key) {return $key . ' = EXCLUDED.' . $key;}, array_keys($rows)));
		$this->database->execSQL($sql, 4, 1);
	}
	
	function deleteExportSettings($formvars){
		$sql = "
			DELETE FROM 
				kvwmap.rolle_export_settings 
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
				kvwmap.rolle_export_settings 
			WHERE 
				layer_id = " . $layer_id . " AND
				user_id = " . $this->user_id . " AND 
				stelle_id = " . $this->stelle_id . 
				($name ? " AND name = '" . $name . "'": '') . "
			ORDER BY name";
		$this->debug->write("<p>file:rolle.php class:rolle->getsettings - Abfragen der gespeicherten Export-Einstellungen der Rolle:<br>".$sql,4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		while ($rs = pg_fetch_assoc($ret[1])) {
			$settings[]=$rs;
		}
		return $settings;
	}

	function read_Group($id) {
		$sql ='SELECT g2r.*, g.gruppenname FROM kvwmap.u_groups AS g, u_groups2rolle AS g2r';
		$sql.=' WHERE g2r.stelle_id='.$this->stelle_id.' AND g2r.user_id='.$this->user_id.' AND g2r.id = g.id AND g.id='.$id;
		$this->debug->write("<p>file:kvwmap class:rolle->read_Group - Lesen einer Gruppe der Rolle:<br>".$sql,4);
		$ret = $this->database->execSQL($sql);
		if (!$this->database->success) { echo "<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		$rs = pg_fetch_assoc($ret[1]);
		return $rs;
	}

	function setOneLayer($layer_id, $status){
		$sql ='UPDATE kvwmap.u_rolle2used_layer SET aktivstatus='.$status;
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		$sql.=' AND layer_id='.$layer_id;
		$this->debug->write("<p>file:rolle.php class:rolle->setOneLayer - Setzen eines Layers:",4);
		$this->database->execSQL($sql,4, $this->loglevel);

		$sql ='UPDATE kvwmap.u_rolle2used_layer set querystatus='.$status;
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		$sql.=' AND layer_id='.$layer_id;
		$this->debug->write("<p>file:rolle.php class:rolle->setOneLayer - Setzen eines Layers:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
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
				1 as queryable,
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

	function resetLayers($layer_id){
		$this->update_layer_status($layer_id, '0');
	}

	function update_layer_status($layer_id, $status) {
		if($layer_id > 0 OR $layer_id == NULL){
			$sql = "
				UPDATE
					kvwmap.u_rolle2used_layer
				SET
					aktivstatus = '" . $status . "'
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
					kvwmap.rollenlayer
				SET
					aktivstatus = '" . $status . "'
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
					kvwmap.u_rolle2used_layer 
				SET 
					querystatus = '0'
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
					kvwmap.rollenlayer 
				SET 
					querystatus = '0'
				WHERE 
					user_id=".$this->user_id." AND 
					stelle_id=".$this->stelle_id.
					($layer_id != '' ? " AND id = ".abs($layer_id) : "");
			$this->debug->write("<p>file:rolle.php class:rolle->resetQuerys - resetten aller aktiven Layer zur Rolle:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
		}
	}

	function resetClasses(){
		$sql = 'DELETE FROM kvwmap.u_rolle2used_class WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		$this->debug->write("<p>file:rolle.php class:rolle->resetQuerys - resetten aller aktiven Layer zur Rolle:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}

	function setAktivLayer($formvars, $stelle_id, $user_id, $ignore_rollenlayer = false) {
		$this->layerset = $this->getLayer('');
		if (!$ignore_rollenlayer) {
			$rollenlayer = $this->getRollenLayer('', NULL);
			$this->layerset = array_merge_recursive($this->layerset, $rollenlayer);
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
						' . $id . ' = ' . $layer_id;
				$this->debug->write("<p>file:rolle.php class:rolle->setQueryStatus - Speichern des Abfragestatus der Layer zur Rolle:",4);
				$this->database->execSQL($sql,4, $this->loglevel);
			}
		}
		return 1;
	}

	function setClassStatus($formvars) {
		if (value_of($formvars, 'only_layer_id') != '' AND $formvars['show_classes'] != ''){
			# Eintragen des showclasses=1 für Klassen, die angezeigt werden sollen
			$sql ='
				UPDATE 
					kvwmap.u_rolle2used_layer 
				SET 
					showclasses = ' . $formvars['show_classes'] . '
				WHERE 
					user_id = ' . $this->user_id . ' AND 
					stelle_id = ' . $this->stelle_id . ' AND 
					layer_id = ' . $formvars['only_layer_id'];
			$this->debug->write("<p>file:rolle.php class:rolle->setClassStatus - Speichern des Status der Klassen zur Rolle:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
		}
	}

	function saveDrawingorder($layerset, $formvars){
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
			if ($layers_changed != '') {
				foreach ($layers_changed as $id => $value) {
					$sql = "
						UPDATE
							kvwmap.u_rolle2used_layer
						SET
							drawingorder = " . $layerset['list'][$id]['drawingorder'] . "
						WHERE
							layer_id = " . $layerset['list'][$id]['layer_id'] . " AND
							user_id = " . $this->user_id . " AND
							stelle_id = " . $this->stelle_id . "
					";
					$this->debug->write("<p>file:rolle.php class:rolle function:saveDrawingorder - :",4);
					$this->database->execSQL($sql,4, $this->loglevel);
				}
			}
		}
	}
	
	function changeLegendType(){
		$sql ="
			UPDATE
				kvwmap.rolle
			SET 
				legendtype = abs(legendtype - 1)
			WHERE
				user_id = " . $this->user_id . " AND
				stelle_id = " . $this->stelle_id . "
		";;
		#echo $sql;
		$this->debug->write("<p>file:rolle.php class:rolle function:changeLegendType - :",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}

	function removeDrawingOrders() {
		$sql = "
			UPDATE
				kvwmap.u_rolle2used_layer
			SET
				drawingorder = NULL
			WHERE
				user_id = " . $this->user_id . " AND
				stelle_id = " . $this->stelle_id . "
		";
		$this->debug->write("<p>file:rolle.php class:rolle->removeDrawingOrders:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}

	function setRollenLayerParams($formvars){
		$sql = "
			UPDATE
				kvwmap.rollenlayer
			SET
				name = '" . $formvars['layer_options_name'] . "',
				autodelete = '" . ($formvars['layer_options_autodelete'] ?: '0') . "',
				buffer = " . quote_or_null($formvars['layer_options_buffer']) . ",
				classitem = " . quote_or_null($formvars['classitem']) . "
			WHERE
				user_id = " . $this->user_id . " AND
				stelle_id = " . $this->stelle_id . " AND
				id = -1*" . $formvars['layer_options_open'] . "
		";
		$this->debug->write("<p>file:rolle.php class:rolle->setRollenLayerName:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}

	function setLabelitem($formvars) {
		if (isset($formvars['layer_options_labelitem'])) {
			if ($formvars['layer_options_open'] > 0) { # normaler Layer
				$sql = "
					UPDATE
						kvwmap.u_rolle2used_layer
					SET
						labelitem = '" . $formvars['layer_options_labelitem'] . "'
					WHERE
						user_id = " . $this->user_id . " AND
						stelle_id = " . $this->stelle_id . " AND
						layer_id = " . $formvars['layer_options_open'] . "
				";
				$this->debug->write("<p>file:rolle.php class:rolle->setLabelitem:",4);
				$this->database->execSQL($sql,4, $this->loglevel);
			}
			elseif ($formvars['layer_options_open'] < 0) { # Rollenlayer
				$sql = "
					UPDATE
						kvwmap.rollenlayer
					SET
						labelitem = '" . $formvars['layer_options_labelitem'] . "'
					WHERE
					 user_id = " . $this->user_id . " AND
					 stelle_id = " . $this->stelle_id . " AND
					 id = -1*" . $formvars['layer_options_open'] . "
				";
				$this->debug->write("<p>file:rolle.php class:rolle->setLabelitem:",4);
				$this->database->execSQL($sql,4, $this->loglevel);
			}
		}
	}

	function removeLabelitem($formvars) {
		$sql = "
			UPDATE
				kvwmap.u_rolle2used_layer
			SET
				labelitem = NULL
			WHERE
				user_id = " . $this->user_id . " AND
				stelle_id = " . $this->stelle_id . " AND
				layer_id = " . $formvars['layer_options_open'] . "
		";
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
					kvwmap." . $table_name . "
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
		$sql = "
			UPDATE 
				kvwmap.styles 
			SET 
				color = '" . $formvars['layer_options_color'] . "',
				" . ($formvars['layer_options_hatching']? 'size = 11, width = 5, angle = 45, ' : 'size = 8, width = NULL, angle = NULL, ') . "
				symbolname = CASE WHEN symbolname IS NULL OR symbolname = 'hatch' OR symbolname = '' THEN '" . $formvars['layer_options_hatching'] . "' ELSE symbolname END
			WHERE 
				style_id = " . $style_id;
		$this->debug->write("<p>file:rolle.php class:rolle->setColor:",4);
		$this->database->execSQL($sql,4, $this->loglevel);
	}

	function setTransparency($formvars) {
		if ($formvars['layer_options_transparency'] < 0 OR $formvars['layer_options_transparency'] > 100) {
			$formvars['layer_options_transparency'] = 100;
		}
		if ($formvars['layer_options_open'] > 0) { # normaler Layer
			$sql = "
				UPDATE
					kvwmap.u_rolle2used_layer
				SET
					transparency = " . $formvars['layer_options_transparency'] . "
				FROM 
					kvwmap.layer
				WHERE
					layer.layer_id = u_rolle2used_layer.layer_id AND 
					user_id = " . $this->user_id . " AND
					stelle_id = " . $this->stelle_id . " AND (
						u_rolle2used_layer.layer_id = " . $formvars['layer_options_open'] . " OR
						requires = " . $formvars['layer_options_open'] . "
					)
			";
			$this->debug->write("<p>file:rolle.php class:rolle->setTransparency:",4);
			$this->database->execSQL($sql, 4, $this->loglevel);
		}
		elseif ($formvars['layer_options_open'] < 0) { # Rollenlayer
			$sql = "
				UPDATE
					kvwmap.rollenlayer
				SET
					transparency = " . $formvars['layer_options_transparency'] . "
				WHERE
					user_id = " . $this->user_id . " AND
					stelle_id = " . $this->stelle_id . " AND
					id = -1* " . $formvars['layer_options_open'] . "
			";
			$this->debug->write("<p>file:rolle.php class:rolle->setTransparency:",4);
			$this->database->execSQL($sql,4, $this->loglevel);
		}
	}

	function removeTransparency($formvars) {
		$sql = "
			UPDATE
				kvwmap.u_rolle2used_layer
			SET
				transparency = NULL
			WHERE
				user_id= " . $this->user_id . " AND
				stelle_id = " . $this->stelle_id . " AND
				layer_id = " . $formvars['layer_options_open'] . "
		";
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
				kvwmap.rolle
			SET
				nimagewidth = " . $nImageWidth . ",
				nimageheight = " . $nImageHeight . "
			WHERE
				stelle_id = " . $this->stelle_id . "
				AND user_id = " . $this->user_id . "
		";
		$this->debug->write("<p>file:rolle.php class:rolle->setSize - Setzen der Einstellungen für die Bildgröße", 4);
		$this->database->execSQL($sql,4, $this->loglevel);
		$this->debug->write('Neue Werte für Rolle eingestellt: ' . $nImageWidth . ', ' . $nImageHeight, 4);
		return 1;
	}

	/**
	 * Function set saved themes from Default user with $default_user_id to User with $user_id in Stelle $stelle_id
	 * @param int $user_id
	 * @param int $stelle_id
	 * @param int $default_user_id
	 * @return int 1 | 0 Wenn success 1 else 0
	 */
	function setSavedLayersFromDefaultUser($user_id, $stelle_id, $default_user_id){
		# Gespeicherte Themeneinstellungen von default user übernehmen
		if ($default_user_id > 0 AND $default_user_id != $user_id) {
			$sql = "
				INSERT INTO kvwmap.rolle_saved_layers (
					user_id,
					stelle_id,
					name,
					layers,
					query
				)
				SELECT " .
					$user_id . "," .
					$stelle_id . ",
					name,
					layers,
					query
				FROM
					kvwmap.rolle_saved_layers
				WHERE
					user_id = " . $default_user_id . " AND
					stelle_id = " . $stelle_id . "
			";
			#echo '<br>'.$sql;
			$this->debug->write("<p>file:rolle.php class:rolle function:setSavedLayersFromDefaultUser :<br>" . $sql, 4);
			$ret = $this->database->execSQL($sql, 4, 0);
			if (!$ret['success']) {
				$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF']) . " Zeile: " . __LINE__ . $ret[1], 4);
				return 0;
			}
		}
		return 1;
	}

	/**
	 * Function set role for user with $user_id in Stelle $stelle_id
	 * @param int $user_id
	 * @param int $stelle_id
	 * @param int $default_user_id
	 * @param int|null $parent_stelle_id
	 * @return int 1 | 0 Wenn success 1 else 0
	 */
	function setRolle($user_id, $stelle_id, $default_user_id, $parent_stelle_id = NULL) {
		$role = Role::find_by_id($this->gui_object, $user_id, $stelle_id);

		# trägt die Rolle für einen Benutzer ein.
		if ($default_user_id > 0 AND ($default_user_id != $user_id OR $parent_stelle_id)) {
			$result = $role->set_rolle_from_default_user_or_parent_stelle($user_id, $stelle_id, $default_user_id, $parent_stelle_id);
		}
		else {
			$result = $role->set_rolle_from_stelle_default($user_id, $stelle_id);
		}
		$this->debug->write("<p>file:rolle.php class:rolle function:setRolle - Einfügen einer neuen Rolle:<br>" . $result['sql'], 4);
		if (!$result['success']) {
			$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF']) . " Zeile: " . __LINE__ . $result['msg'], 4);
			return 0;
		}
		$role = Role::find_by_id($this->gui_object, $user_id, $stelle_id);
		$role->rectify_layer_params($role->get('layer_params'));
		return 1;
	}

	/**
	 * Function set the layer parameter for rolle attribut layer_params as string
	 * @param PgObject The PgObject of the rolle.
	 * @param Array The assoziative array with layer params of rolle.
	 * @return void
	 */
	function set_rolle_layer_params($rolle, $layer_params) {
		$rolle->update(array('layer_params', implode(',', $new_layer_params)));
	}

	function deleteRollen($user_id, $stellen) {
		# löscht die übergebenen Stellen für einen Benutzer.
		for ($i = 0; $i < count_or_0($stellen); $i++) {
			$sql = "
				DELETE FROM 
					kvwmap.rolle
				WHERE
					user_id = " . $user_id . ' AND
					stelle_id = ' . $stellen[$i] . "
			";
			#echo '<br>'.$sql;
			$this->debug->write("<p>file:rolle.php class:rolle function:deleteRollen - Löschen der Rollen:<br>" . $sql, 4);
			$ret = $this->database->execSQL($sql, 4, 0);
			if (!$ret['success']) {
				$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF']) . " Zeile: " . __LINE__ . $ret[1], 4);
				return 0;
			}
			# default_user_id
			$sql = "
				UPDATE
					kvwmap.stelle 
				SET	
					default_user_id = NULL
				WHERE 
					default_user_id = " . $user_id . " AND 
					id = " . $stellen[$i];
			$ret = $this->database->execSQL($sql, 4, 0);
			if (!$ret['success']) {
				$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF']) . " Zeile: " . __LINE__ . $ret[1], 4);
				return 0;
			}
			else {
				if (pg_affected_rows($ret[1]) > 0){
					GUI::add_message_('notice', 'Achtung! Der Standardnutzer wurde von der Stelle entfernt.');
				}
			}
			# rolle_nachweise
			if ($this->gui_object->plugin_loaded('nachweisverwaltung')) {
				$sql = "
					DELETE FROM 
						kvwmap.rolle_nachweise
					WHERE
						user_id = " . $user_id . " AND
						stelle_id = " . $stellen[$i] . "
				";
				#echo '<br>'.$sql;
				$this->debug->write("<p>file:rolle.php class:rolle function:deleteRollen - Löschen der Rollen:<br>".$sql,4);
				$ret = $this->database->execSQL($sql, 4, 0);
				if (!$ret['success']) {
					$this->debug->write("<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF']) . " Zeile: " . __LINE__ . $ret[1], 4);
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
					menue_id,
					status
				FROM
					kvwmap.u_menue2rolle
				WHERE
					stelle_id = " . $stelle_id . " AND
					user_id = " . $default_user_id . "
			";
		}
		else {
			# Menueeinstellungen mit status 0 von stelle abfragen
			$menue2rolle_select_sql = "
				SELECT " .
					$user_id . ", " .
					$stelle_id . ",
					menue_id,
					0
				FROM
					kvwmap.u_menue2stelle
				WHERE
					stelle_id = " . $stelle_id . "
			";
		}
		$sql = "
			INSERT INTO kvwmap.u_menue2rolle (
				user_id,
				stelle_id,
				menue_id,
				status
			) " .
			$menue2rolle_select_sql . "
			ON CONFLICT (user_id,	stelle_id, menue_id) DO NOTHING
		";
		#echo '<br>sql: ' . $sql;
		$this->debug->write("<p>file:rolle.php class:rolle function:setMenue - Setzen der Menuepunkte der Rolle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	function deleteMenue($user_id, $stellen, $menues) {
		# löscht die Menuepunkte der übergebenen Stellen für einen Benutzer.
		if($menues == 0) {
			for ($i = 0; $i < count_or_0($stellen); $i++) {
				# löscht alle Menuepunkte der Stelle
				$sql = "
					DELETE FROM
						kvwmap.u_menue2rolle
					WHERE
						user_id = " . $user_id . " AND
						stelle_id = " . $stellen[$i] . "
				";
				#echo '<br>'.$sql;
				$this->debug->write("<p>file:rolle.php class:stelle function:deleteMenue - Löschen der Menuepunkte der Stelle:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
			}
		}
		else {
			for ($i = 0; $i < count($stellen); $i++) {
				for ($j=0; $j < count($menues); $j++) {
					$sql = "
						DELETE FROM
							kvwmap.u_menue2rolle
						WHERE
							user_id = " . $user_id . " AND
							stelle_id = " . $stellen[$i] . " AND
							menue_id = " . $menues[$j] . "
					";
					#echo '<br>'.$sql;
					$this->debug->write("<p>file:rolle.php class:rolle function:deleteMenue - Löschen der Menuepunkte der Rollen:<br>".$sql,4);
					$this->database->execSQL($sql);
					if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
				}
			}
		}
		return 1;
	}


	function set_one_Group($user_id, $stelle_id, $group_id, $open) {
		$sql = '
			INSERT INTO 
				kvwmap.u_groups2rolle 
			VALUES (
				' . $user_id . ', 
				' . $stelle_id . ', 
				' . $group_id . ', 
				' . $open . '
			)
			ON CONFLICT (user_id, stelle_id, id) DO NOTHING';
		$this->debug->write("<p>file:rolle.php class:rolle function:setGroups - Setzen der Gruppen der Rollen:<br>".$sql,4);
		$ret = $this->database->execSQL($sql);
		if ($ret[0]) { $this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}


	function setGroups($user_id, $stelle_id, $default_user_id, $layerids) {
		# trägt die Gruppen und Obergruppen der übergebenen Stellenid und Layerids für einen Benutzer ein. Gruppen, die aktive Layer enthalten werden aufgeklappt
		if ($default_user_id > 0 AND $default_user_id != $user_id) {
			$sql = "
				INSERT INTO
					kvwmap.u_groups2rolle
				SELECT 
					" . $user_id . ",
					stelle_id,
					id,
					status
				FROM 
						kvwmap.u_groups2rolle
				WHERE
					stelle_id = " . $stelle_id . " AND
					user_id = " . $default_user_id . "
				ON CONFLICT (user_id, stelle_id, id) DO NOTHING
			";
			#echo '<br>SQL zum Zuordnen der Rolle zu den Layergruppen: '.$sql;
			$this->debug->write("<p>file:rolle.php class:rolle function:setGroups - Setzen der Gruppen der Rolle:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { 
				$this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); 
				return 0;
			}
		}
		else {
			for ($j = 0; $j < count_or_0($layerids); $j++){
				$sql = "
					INSERT INTO kvwmap.u_groups2rolle 
					WITH RECURSIVE cte (group_id) AS (
						SELECT 
            	coalesce(ul.group_id, l.gruppe) AS group_id
						FROM
          		kvwmap.used_layer ul JOIN
            	kvwmap.layer l ON ul.layer_id = l.layer_id
            WHERE 
            	l.layer_id = " . $layerids[$j] . " AND
            	ul.stelle_id = " . $stelle_id . "
						UNION ALL
						SELECT obergruppe FROM kvwmap.u_groups, cte WHERE cte.group_id = u_groups.id AND obergruppe IS NOT NULL
					)
					SELECT 
						" . $user_id . ", 
						" . $stelle_id . ", 
						group_id, 
						0
					FROM cte
					ON CONFLICT (user_id, stelle_id, id) DO NOTHING;";
				#echo '<br>Gruppen: '.$sql;
				$this->debug->write("<p>file:rolle.php class:rolle function:setGroups - Setzen der Gruppen der Rollen:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) { 
					$this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); 
					return 0; 
				}
			}
		}
		return 1;
	}

	function deleteGroups($user_id,$stellen) {
		# löscht die Gruppen der übergebenen Stellen für einen Benutzer.
		for ($i = 0; $i < count_or_0($stellen); $i++) {
			$sql ='DELETE FROM kvwmap.u_groups2rolle WHERE user_id = '.$user_id.' AND stelle_id = '.$stellen[$i];
			#echo '<br>'.$sql;
			$this->debug->write("<p>file:rolle.php class:rolle function:deleteGroups - Löschen der Gruppen der Rollen:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) {
				$this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4);
				return 0;
			}
		}
		return 1;
	}


	static function setGroupsForAll($database) {
		$sql = "
			INSERT INTO kvwmap.u_groups2rolle 				
			WITH RECURSIVE cte (stelle_id, user_id, group_id) AS (
				SELECT DISTINCT
						ul.stelle_id AS stelle_id,
						r.user_id,
						coalesce(ul.group_id, l.Gruppe) AS group_id
				FROM
						kvwmap.used_layer ul JOIN
						kvwmap.layer l ON ul.layer_id = l.layer_id JOIN
						kvwmap.rolle r ON ul.stelle_id = r.stelle_id
				UNION ALL
				SELECT 
						cte.stelle_id,
						cte.user_id,
						obergruppe AS group_id
				FROM 
					kvwmap.u_groups, cte 
				WHERE 
						cte.group_id = u_groups.id AND 
						obergruppe IS NOT NULL
			)
			SELECT DISTINCT 
				user_id,
				stelle_id,
				group_id,
				0 
			FROM cte
			ON CONFLICT (user_id, stelle_id, id) DO NOTHING";
		#echo '<br>Gruppen: '.$sql;
		$database->execSQL($sql);
	}

	static function clear_groups2rolle($database) {
		$sql = "
			DELETE FROM
				kvwmap.u_groups2rolle
			USING 
				kvwmap.u_groups2rolle g2r LEFT JOIN
				(
					SELECT DISTINCT
						*
					FROM
						(
							WITH RECURSIVE cte (stelle_id, user_id, group_id) AS (
								(
									SELECT DISTINCT
										ul.stelle_id AS stelle_id,
										r.user_id,
										coalesce(ul.group_id, l.gruppe) AS group_id
									FROM
										kvwmap.used_layer ul JOIN
										kvwmap.layer l ON ul.layer_id = l.layer_id JOIN
										kvwmap.rolle r ON ul.stelle_id = r.stelle_id
									UNION ALL
									SELECT DISTINCT
										stelle_id,
										user_id,
										gruppe
									FROM
										kvwmap.rollenlayer
								)
								UNION ALL
								SELECT 
										cte.stelle_id,
										cte.user_id,
										obergruppe AS group_id
								FROM 
										kvwmap.u_groups, cte 
								WHERE 
										cte.group_id = u_groups.id AND 
										obergruppe IS NOT NULL
							)
							SELECT * FROM cte
						) sub
					WHERE
						group_id IS NOT NULL
				) n ON 
					g2r.stelle_id = n.stelle_id AND 
					g2r.user_id = n.user_id AND 
					g2r.id = n.group_id
			WHERE
				g2r.stelle_id = u_groups2rolle.stelle_id AND 
				g2r.user_id = u_groups2rolle.user_id AND 
				g2r.id = u_groups2rolle.id AND 
				n.stelle_id IS NULL AND
				n.user_id IS NULL AND
				n.group_id IS NULL
		";
		// echo '<br>SQL zum Löschen nicht mehr benötigter groups2rolle Eintragungen: ' . $sql . '<br>'; 
		$database->execSQL($sql);
	}

	function set_one_Layer($user_id, $stelle_id, $layer_id,  $active) {
		$sql = "
			INSERT INTO kvwmap.u_rolle2used_layer 
			VALUES (
				" . $user_id . ", 
				" . $stelle_id . ", 
				" . $layer_id . ", 
				" . $active . ", 
				0,
				1,
				false
			)
			ON CONFLICT (user_id, stelle_id, layer_id) DO NOTHING";
		$this->debug->write("<p>file:rolle.php class:rolle function:set_one_Layer - Setzen eines Layers der Rolle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	/**
	 * Trägt die Layer der entsprehenden Rolle für einen Benutzer ein.
	 */
	function setLayer($user_id, $stelle_id, $default_user_id) {
		if ($default_user_id > 0 AND $default_user_id != $user_id) {
			// echo '<br>Layereinstellungen von Defaultrolle abfragen';
			$rolle2used_layer_select_sql = "
				SELECT " .
					$user_id . ", " .
					$stelle_id . ",
					layer_id,
					aktivstatus,
					querystatus,
					gle_view,
					showclasses,
					geom_from_layer
				FROM
					kvwmap.u_rolle2used_layer
				WHERE
					user_id = " . $default_user_id . " AND
					stelle_id = " . $stelle_id . "
			";
		}
		else {
			// echo '<br>Layereinstellungen von Defaultlayerzuordnung abfragen';
			$rolle2used_layer_select_sql = "
				SELECT " .
					$user_id . ", " .
					$stelle_id . ",
					layer_id,
					start_aktiv::integer,
					start_aktiv::integer,
					1,
					1,
					layer_id
				FROM
					kvwmap.used_layer
				WHERE
					stelle_id = " . (int)$stelle_id . "
			";
		}
		# Layereinstellungen der Rolle eintragen
		$sql = "
			INSERT INTO kvwmap.u_rolle2used_layer (
				user_id,
				stelle_id,
				layer_id,
				aktivstatus,
				querystatus,
				gle_view,
				showclasses,
				geom_from_layer
			) " .
			$rolle2used_layer_select_sql . "
			ON CONFLICT (user_id, stelle_id, layer_id) DO NOTHING
		";
		// echo '<br>Sql: ' . $sql;
		$this->debug->write("<p>file:rolle.php class:rolle function:setLayer - Setzen der Layer der Rolle:<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	function deleteLayer($user_id, $stellen, $layer) {
		# löscht die Layer der übergebenen Stellen für einen Benutzer.
		for ($i = 0; $i < count_or_0($stellen); $i++) {
			if (!is_array($layer)) {
				$layer = array();
			}
			for ($j = 0; $j < count($layer); $j++) {
				$sql = "
					DELETE FROM
						kvwmap.u_rolle2used_layer
					WHERE
						stelle_id = " . $stellen[$i] .
						($user_id != 0 ? " AND user_id = " . $user_id : "") .
						($layer != 0 ? " AND layer_id = " . $layer[$j] : "") . "
				";
				#echo '<br>'.$sql;
				$this->debug->write("<p>file:rolle.php class:rolle function:deleteLayer - Löschen der Layer der Rollen:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".htmlentities($_SERVER['PHP_SELF'])." Zeile: ".__LINE__,4); return 0; }
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
		$sql ="UPDATE kvwmap.rolle SET hidemenue = " . $hide;
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
		$sql ="UPDATE kvwmap.rolle SET hidelegend = " . $hide;
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		#echo $sql;
		$this->debug->write("<p>file:rolle.php class:rolle function:hideMenue - :",4);
		$this->database->execSQL($sql,4, $this->loglevel);
		return 1;
	}	
	
	function saveOverlayPosition($x, $y){
		$sql ="UPDATE kvwmap.rolle SET overlayx = ".$x.", overlayy=".abs($y);
		$sql.=' WHERE user_id='.$this->user_id.' AND stelle_id='.$this->stelle_id;
		#echo $sql;
		$this->debug->write("<p>file:rolle.php class:rolle function:saveOverlayPosition - :",4);
		$this->database->execSQL($sql,4, $this->loglevel);
		return 1;
	}

	function set_last_query_layer($layer_id){
		$sql = '
			UPDATE 
				kvwmap.rolle 
			SET 
				last_query_layer = ' . $layer_id . '
			WHERE 
				user_id = ' . $this->user_id . ' AND 
				stelle_id = ' . $this->stelle_id;
		#echo $sql;
		$this->debug->write("<p>file:rolle.php class:rolle function:set_last_query_layer - :",4);
		$this->database->execSQL($sql,4, $this->loglevel);
		return 1;
	}	

	function getMapComments($consumetime, $public = false, $order) {
		$sql ='SELECT c.user_id, c.time_id, c.comment, c.public, u.name, u.vorname FROM kvwmap.u_consume2comments as c, kvwmap.user as u WHERE c.user_id = u.id AND (';
		if($public)$sql.=' c.public = 1 OR';
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
			while ($rs = pg_fetch_assoc($queryret[1])) {
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
				id,
				name,
				array_to_string(layers, ',') as layers,
				query
			FROM
				kvwmap.rolle_saved_layers
			WHERE
				user_id = " . $user_id . " AND
				stelle_id = " . $this->stelle_id .
				$where_id . "
			ORDER BY
				name
		";
		#echo '<br>Sql: ' . $sql;

		$ret = $this->database->execSQL($sql, 4, 0);
		if (!$this->database->success) {
			# Fehler bei Datenbankanfrage
			$ret[0] = 1;
			$ret[1] = '<br>Fehler beim Laden der Themenauswahl.<br>' . $ret[1];
		}
		else {
			while ($rs = pg_fetch_assoc($ret[1])) {
				$layerComments[] = $rs;
			}
			$ret[0] = 0;
			$ret[1] = $layerComments;
		}
		return $ret;
	}

	function insertMapComment($consumetime,$comment,$public) {
		if($public == '')$public = 0;
		$rows = [
			'user_id' => $this->user_id, 
			'stelle_id' => $this->stelle_id, 
			'time_id' => "'" . $consumetime . "'",
			'comment' => "'" . $comment . "'",
			'public' => $public
		];
		$sql = "
			INSERT INTO
				kvwmap.u_consume2comments
				(" . implode(', ', array_keys($rows)) . ")
			VALUES	
				(" . implode(', ', $rows) . ")
			ON CONFLICT (user_id, stelle_id, time_id) DO	UPDATE 
				SET " .
					implode(', ',	array_map(function($key) {return $key . ' = EXCLUDED.' . $key;}, array_keys($rows)));
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
		for($i=0; $i < count($layerset['list']); $i++){
			if($layerset['list'][$i]['layer_id'] > 0 AND $layerset['list'][$i]['aktivstatus'] == 1){
				$layers[] = $layerset['list'][$i]['layer_id'];
				if($layerset['list'][$i]['queryStatus'] == 1)$query[] = $layerset['list'][$i]['layer_id'];
			}
		}
		$rows = [
			'user_id' => $this->user_id,
			'stelle_id' => $this->stelle_id,
			'name' => "'" . $comment . "'",
			'layers' => "'{" . implode(',', $layers) . "'}",
			'query' => "'" . implode(',', $query) . "'"
		];
		$sql = "
			INSERT INTO
				kvwmap.rolle_saved_layers
				(" . implode(', ', array_keys($rows)) . ")
			VALUES	
				(" . implode(', ', $rows) . ")";
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
		$sql = "
			DELETE FROM 
				kvwmap.u_consume2comments 
			WHERE 
				user_id = " . $this->user_id . " AND 
				stelle_id = " . $this->stelle_id . " 
				AND time_id = '" . $storetime . "'";
		#echo '<br>'.$sql;
		$queryret=$this->database->execSQL($sql,4, 1);
		if ($queryret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[0]=1;
			$ret[1]='<br>Fehler beim Löschen des Kommentares zum Kartenausschnitt.<br>'.$ret[1];
		}
	}
		
	function deleteLayerComment($id){
		$sql = "
			DELETE FROM 
				kvwmap.rolle_saved_layers 
			WHERE 
				user_id = " . $this->user_id . " AND 
				stelle_id = " . $this->stelle_id . " AND 
				id = " . $id;
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
				$sql = "
					INSERT INTO 
						kvwmap.u_consumeALB 
					VALUES (
						" . $this->user_id . ",
						" . $this->stelle_id . ",
						'" . $time . "',
						'" . $format . "',
						'" . $log_number[$i] . "',
						'" . $wz . "'
					)
					ON CONFLICT (user_id, stelle_id, time_id, log_number) DO NOTHING";
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
			$sql = "
				INSERT INTO 
					kvwmap.u_consumeCSV
				VALUES (
					" . $this->user_id . ",
					" . $this->stelle_id . ",
					'" . $time . "',
					'" . $art . "',
					" . $numdatasets . "
				)";
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
		if (LOG_CONSUME_ACTIVITY == 1) {
			$sql = "
				INSERT INTO 
					kvwmap.u_consumeshape 
				VALUES (
					" . $this->user_id . ", 
					" . $this->stelle_id . ",
					'" . $time . "',
					" . $layerid . ",
					" . $numdatasets . ")";
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
include(CLASSPATH . 'Role.php');
?>