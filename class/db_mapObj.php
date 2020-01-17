<?

class db_mapObj{
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
		global $GUI;
		$this->debug = $debug;
		$this->GUI = $GUI;
		$this->Stelle_ID = $Stelle_ID;
		$this->User_ID = $User_ID;
		$this->rolle = new rolle($User_ID, $Stelle_ID, $database);
		$this->database = $database;
	}

	function read_ReferenceMap() {
    $sql ='SELECT r.* FROM referenzkarten AS r, stelle AS s WHERE r.ID=s.Referenzkarte_ID';
    $sql.=' AND s.ID='.$this->Stelle_ID;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_ReferenceMap - Lesen der Referenzkartendaten:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    $this->referenceMap=$rs;
#		echo '<br>sql: ' . print_r($sql, true);
#		echo '<br>ref: ' . print_r($this->referenceMap, true);
    return $rs;
  }

  function read_RollenLayer($id = NULL, $typ = NULL){
		$sql = "SELECT DISTINCT l.*, l.Name as alias, g.Gruppenname, -l.id AS Layer_ID, 1 as showclasses, CASE WHEN Typ = 'import' THEN 1 ELSE 0 END as queryable, CASE WHEN rollenfilter != '' THEN concat('(', rollenfilter, ')') END as Filter from rollenlayer AS l, u_groups AS g";
    $sql.= ' WHERE l.Gruppe = g.id AND l.stelle_id='.$this->Stelle_ID.' AND l.user_id='.$this->User_ID;
    if($id != NULL){
    	$sql .= ' AND l.id = '.$id;
    }
  	if($typ != NULL){
    	$sql .= ' AND l.Typ = \''.$typ.'\'';
    }
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_RollenLayer - Lesen der RollenLayer:<br>" . $sql,4);
    $query=mysql_query($sql);
		if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
    $Layer = array();
    while ($rs=mysql_fetch_array($query)) {
      $rs['Class']=$this->read_Classes(-$rs['id'], $this->disabled_classes);
      $Layer[]=$rs;
    }
    return $Layer;
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
				l.labelmaxscale, l.labelminscale, l.labelrequires, CASE WHEN connectiontype = 6 THEN concat('host=', c.host, ' port=', c.port, ' dbname=', c.dbname, ' user=', c.user, ' password=', c.password) ELSE l.connection END as connection, l.printconnection, l.connectiontype, l.classitem, l.classification, l.filteritem,
				l.cluster_maxdistance, l.tolerance, l.toleranceunits, l.processing, l.epsg_code, l.ows_srs, l.wms_name, l.wms_keywordlist, l.wms_server_version,
				l.wms_format, l.wms_auth_username, l.wms_auth_password, l.wms_connectiontimeout, l.selectiontype, l.logconsume,l.metalink, l.status, l.trigger_function, l.sync,
				g.id, ".$group_column.", g.obergruppe, g.order
			FROM
				u_rolle2used_layer AS rl,
				used_layer AS ul,
				u_groups AS g,
				u_groups2rolle as gr,
				layer AS l
				LEFT JOIN connections as c ON l.connection_id = c.id
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
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Layer - Lesen der Layer der Rolle:<br>" . $sql,4);
    $query=mysql_query($sql);
		if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
    $layer = array();
		$layer['list'] = array();
    $this->disabled_classes = $this->read_disabled_classes();
		$i = 0;
    while ($rs=mysql_fetch_assoc($query)){
			if($rs['rollenfilter'] != ''){		// Rollenfilter zum Filter hinzufügen
				if($rs['Filter'] == ''){
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
			foreach (array('Name', 'alias', 'connection', 'classification') AS $key) {
				$rs[$key] = replace_params(
					$rs[$key],
					rolle::$layer_params,
					$this->User_ID,
					$this->Stelle_ID,
					rolle::$hist_timestamp,
					$this->rolle->language
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
    $query=mysql_query($sql);
		if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
    while ($rs=mysql_fetch_array($query)) {
			$groups[$rs['id']]['status'] = $rs['status'];
      $groups[$rs['id']]['Gruppenname'] = $rs['Gruppenname'];
			$groups[$rs['id']]['obergruppe'] = $rs['obergruppe'];
			$groups[$rs['id']]['id'] = $rs['id'];
			if($rs['obergruppe'])$groups[$rs['obergruppe']]['untergruppen'][] = $rs['id'];
    }
    $this->anzGroups=count($groups);
    return $groups;
  }

  // function read_Group($id) {
    // $sql ='SELECT g2r.*, g.Gruppenname FROM u_groups AS g, u_groups2rolle AS g2r';
    // $sql.=' WHERE g2r.stelle_ID='.$this->Stelle_ID.' AND g2r.user_id='.$this->User_ID.' AND g2r.id = g.id AND g.id='.$id;
    // $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Group - Lesen einer Gruppe der Rolle:<br>" . $sql,4);
    // $query=mysql_query($sql);
    //if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql, $this->connection); return 0; }
    // $rs=mysql_fetch_array($query);
    // return $rs;
  // }


	function read_ClassesbyClassid($class_id) {
		global $language;

		$sql = "
			SELECT" .
				((!$all_languages AND $language != 'german') ? "
					CASE
						WHEN `Name_" . $language . "` IS NOT NULL THEN `Name_" . $language . "`
						ELSE `Name`
					END" : "`Name`"
				) . " AS Name,
				`Class_ID`,
				`Layer_ID`,
				`Expression`,
				`classification`,
				`legendgraphic`,
				`drawingorder`,
				`legendorder`,
				`text`
			FROM
				`classes`
			WHERE
				`Class_ID` = " . $class_id . "
			ORDER BY
				`classification`,
				`drawingorder`,
				`Class_ID`
		";

		#echo $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Class - Lesen der Classen eines Layers:<br>" . $sql, 4);
		$query=mysql_query($sql);
    if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
		while ($rs = mysql_fetch_array($query)) {
			$rs['Style'] = $this->read_Styles($rs['Class_ID']);
			$rs['Label'] = $this->read_Label($rs['Class_ID']);
			$Classes[] = $rs;
		}
		return $Classes;
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
				NULLIF(classification, '') IS NULL,
				classification,
				drawingorder,
				Class_ID
		";
		#echo $sql.'<br>';
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Class - Lesen der Classen eines Layers:<br>" . $sql, 4);
		$query = mysql_query($sql);
		if ($query == 0) { echo "<br>Abbruch in " . $PHP_SELF . " Zeile: " . __LINE__ .'<br>'.$sql; return 0; }
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

  function read_Styles($Class_ID) {
    $sql ='SELECT * FROM styles AS s,u_styles2classes AS s2c';
    $sql.=' WHERE s.Style_ID=s2c.style_id AND s2c.class_id='.$Class_ID;
    $sql.=' ORDER BY drawingorder';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Styles - Lesen der Styledaten:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
    while($rs=mysql_fetch_assoc($query)) {
      $Styles[]=$rs;
    }
    return $Styles;
  }


  # Änderung am 12.07.2005 von 1.4.4 nach 1.4.5, Korduan
  # Einer Klasse können nun mehrere Labels zugeordnet werden
  # Abfrage der Labels nicht mehr aus Tabelle classes sondern aus u_labels2classes
  function read_Label($Class_ID) {
    $sql ='SELECT * FROM labels AS l,u_labels2classes AS l2c';
    $sql.=' WHERE l.Label_ID=l2c.label_id AND l2c.class_id='.$Class_ID;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Label - Lesen der Labels zur Classe eines Layers:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
    while ($rs=mysql_fetch_assoc($query)) {
      $Labels[]=$rs;
    }
    return $Labels;
  }

	function zoomToDatasets($oids, $tablename, $columnname, $border, $layerdb, $layer_epsg, $client_epsg) {
  	$sql ="SELECT st_xmin(bbox) AS minx,st_ymin(bbox) AS miny,st_xmax(bbox) AS maxx,st_ymax(bbox) AS maxy";
  	$sql.=" FROM (SELECT st_transform(ST_SetSRID(ST_Extent(" . $columnname."), " . $layer_epsg."), " . $client_epsg.") as bbox";
  	$sql.=" FROM " . $tablename." WHERE oid IN (";
  	for($i = 0; $i < count($oids); $i++){
    	$sql .= "'" . $oids[$i]."',";
    }
    $sql = substr($sql, 0, -1);
		$sql.=")) AS foo";
		#echo $sql;
    $ret = $layerdb->execSQL($sql, 4, 0);
		$rs = pg_fetch_array($ret[1]);
		$rect = ms_newRectObj();
    $rect->minx=$rs['minx'];
    $rect->maxx=$rs['maxx'];
    $rect->miny=$rs['miny'];
    $rect->maxy=$rs['maxy'];
		if(defined('ZOOMBUFFER') AND ZOOMBUFFER > 0){
			if($client_epsg == 4326)$randx = $randy = ZOOMBUFFER/10000;
			else $randx = $randy = ZOOMBUFFER;
		}
		else{
			if($rect->maxx-$rect->minx < 1){		# bei einem Punktdatensatz
				$randx = $randy = 50;
				if($client_epsg == 4326)$randx = $randy = $randy/10000;
			}
			else{
				$randx=($rect->maxx-$rect->minx)*$border/100;
				$randy=($rect->maxy-$rect->miny)*$border/100;
			}
		}
    $rect->minx -= $randx;
    $rect->miny -= $randy;
    $rect->maxx += $randx;
    $rect->maxy += $randy;
    return $rect;
  }

  function deleteFilter($stelle_id, $layer_id, $attributname){
    $sql = 'DELETE FROM u_attributfilter2used_layer WHERE Stelle_ID = '.$stelle_id.' AND Layer_ID = '.$layer_id.' AND attributname = "'.$attributname.'"';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->deleteFilter - Löschen eines Attribut-Filters eines used_layers:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
  }

  function writeFilter($database, $filter, $layer, $stelle){
    if($filter != ''){
      $layerdata = $this->get_Layer($layer);
      $filterstring = '(1 = 1';
      for($i = 0; $i < count($filter); $i++){
        if($filter[$i]['type'] == 'geometry'){
          $poly_geom = $database->getpolygon($filter[$i]['attributvalue'], $layerdata['epsg_code']);
          #$filterstring .= ' AND '.$filter[$i]['attributname'].' && \''.$poly_geom.'\'';		// ist ja bei within und intersects schon mit drin
          $filterstring .= ' AND '.$filter[$i]['operator'].'('.$filter[$i]['attributname'].',\''.$poly_geom.'\'::geometry)';
        }
        else{
          if($filter[$i]['operator'] == 'IS'){
            $filterstring .= ' AND '.$filter[$i]['attributname'].' '.$filter[$i]['operator'].' '.$filter[$i]['attributvalue'];
          }
          elseif($filter[$i]['operator'] == 'IN'){
            if($filter[$i]['type'] == 'varchar' OR $filter[$i]['type'] == 'text'){
              $values = explode(',', $filter[$i]['attributvalue']);
              $filter[$i]['attributvalue'] = "'".implode("','", $values)."'";
            }
            $filterstring .= ' AND '.$filter[$i]['attributname'].' '.$filter[$i]['operator'].' ('.$filter[$i]['attributvalue'].')';
          }
          else{
            $filterstring .= ' AND '.$filter[$i]['attributname'].' '.$filter[$i]['operator'].' \''.$filter[$i]['attributvalue'].'\'';
          }
        }
      }
      $filterstring .= ')';
    }
    $sql = 'UPDATE used_layer SET Filter = "'.$filterstring.'" WHERE Stelle_ID = '.$stelle.' AND Layer_ID = '.$layer;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->writeFilter - Speichern des Filterstrings:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
  }

  function checkPolygon($poly_id){
    $sql = 'SELECT * FROM u_attributfilter2used_layer WHERE attributvalue = "'.$poly_id.'" AND type = "geometry"';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->checkPolygon - Testen ob Polygon_id noch in einem Filter benutzt wird:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs = mysql_fetch_array($query);
    if($rs == NULL){
      return false;
    }
    else{
      return true;
    }
  }

  function getPolygonID($stelle_id,$layer_id) {
    $sql = 'SELECT attributvalue AS id FROM u_attributfilter2used_layer';
    $sql.= ' WHERE stelle_id = "'.$stelle_id.'" AND layer_id = "'.$layer_id.'" AND type = "geometry"';
    #echo $sql;
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $ret=mysql_fetch_row($query);
    $poly_id = $ret[0];
    return $poly_id;
  }

  function saveAttributeFilter($formvars){
    if(MYSQLVERSION > 410){
      $sql = 'INSERT INTO u_attributfilter2used_layer SET';
      $sql .= ' attributname = "'.$formvars['attributname'].'",';
      $sql .= " attributvalue = '" . $formvars['attributvalue']."',";
      $sql .= ' operator = "'.$formvars['operator'].'",';
      $sql .= ' type = "'.$formvars['type'].'",';
      $sql .= ' Stelle_ID = '.$formvars['stelle'].',';
      $sql .= ' Layer_ID = '.$formvars['layer'];
      $sql .= " ON DUPLICATE KEY UPDATE  attributvalue = '" . $formvars['attributvalue']."', operator = '" . $formvars['operator']."'";
    }
    else{
      $sql = 'REPLACE INTO u_attributfilter2used_layer SET';
      $sql .= ' attributname = "'.$formvars['attributname'].'",';
      $sql .= " attributvalue = '" . $formvars['attributvalue']."',";
      $sql .= ' operator = "'.$formvars['operator'].'",';
      $sql .= ' type = "'.$formvars['type'].'",';
      $sql .= ' Stelle_ID = '.$formvars['stelle'].',';
      $sql .= ' Layer_ID = '.$formvars['layer'];
    }
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->saveAttributeFilter - Speichern der Attribute-Filter-Parameter:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
  }

	function readAttributeFilter($Stelle_ID, $Layer_ID) {
		$sql = "
			SELECT
				*
			FROM
				u_attributfilter2used_layer
			WHERE
				Stelle_ID = " . $Stelle_ID . " AND
				Layer_ID = " . $Layer_ID . "
		";
		# echo '<br>Sql: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->readAttributeFilter - Lesen der Attribute-Filter-Parameter:<br>" . $sql, 4);
		$query = mysql_query($sql);
		if ($query == 0) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__,4); return 0; }
		while($rs = mysql_fetch_array($query)) {
			$filter[] = $rs;
		}
		return $filter;
	}

	function getFilter($layer_id, $stelle_id){
    $sql ='SELECT Filter FROM used_layer WHERE Layer_ID = '.$layer_id.' AND Stelle_ID = '.$stelle_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getFilter - Lesen des Filter-Statements des Layers:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs = mysql_fetch_array($query);
    $filter = $rs[0];
    return $filter;
  }

  function getData($layer_id){
  	if($layer_id < 0){	# Rollenlayer
  		$sql = "
				SELECT
					Data
				FROM
					rollenlayer
				WHERE
					-id = " . $layer_id . "
			";
  	}
  	else{
    	$sql = "
				SELECT
					Data
				FROM
					layer
				WHERE
					Layer_ID = " . $layer_id . "
			";
  	}
  	#echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getData - Lesen des Data-Statements des Layers:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs = mysql_fetch_assoc($query);
    $data = replace_params(
			$rs['Data'],
			rolle::$layer_params,
			$this->User_ID,
			$this->Stelle_ID,
			rolle::$hist_timestamp,
			$this->rolle->language
		);
    return $data;
  }

  function getPath($layer_id){
    $sql ='SELECT Pfad FROM layer WHERE Layer_ID = '.$layer_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getPath - Lesen des Path-Statements des Layers:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs = mysql_fetch_array($query);
    $pfad = $rs[0];
    return $pfad;
  }

  function getDocument_Path($doc_path, $doc_url, $dynamic_path_sql, $attributenames, $attributevalues, $layerdb, $originalname){
		// diese Funktion liefert den Pfad des Dokuments, welches hochgeladen werden soll (absoluter Pfad mit Dateiname ohne Dateiendung)
		// sowie die URL des Dokuments, falls eine verwendet werden soll
		if ($doc_path == '') {
			$doc_path = CUSTOM_IMAGE_PATH;
		}
		if(strtolower(substr($dynamic_path_sql, 0, 6)) == 'select'){		// ist im Optionenfeld eine SQL-Abfrage definiert, diese ausführen und mit dem Ergebnis den Dokumentenpfad erweitern
			$sql = $dynamic_path_sql;
			for($a = 0; $a < count($attributenames); $a++){
				if($attributenames[$a] != '')$sql = str_replace('$'.$attributenames[$a], $attributevalues[$a], $sql);
			}
			$ret = $layerdb->execSQL($sql,4, 1);
			$dynamic_path = pg_fetch_row($ret[1]);
			$doc_path .= $dynamic_path[0];		// der ganze Pfad mit Dateiname ohne Endung
			if($doc_url)$doc_url = $doc_url.$dynamic_path[0];
			$path_parts = explode('/', $doc_path);
			array_pop($path_parts);
			$new_path = implode('/', $path_parts);		// der evtl. neu anzulegende Pfad ohne Datei
			@mkdir($new_path, 0777, true);
		}
		else{			// andernfalls werden keine weiteren Unterordner generiert und der Dateiname aus Zeitstempel und Zufallszahl zusammengesetzt
			if(!$doc_url){
				$filename = date('Y-m-d_H_i_s',time()).'-'.rand(100000, 999999);
			}
			else{
				$filename = $originalname.'-'.rand(100000, 999999);
				$doc_url .= $filename;
			}
			$doc_path .= $filename;
		}
    return array('doc_path' => $doc_path, 'doc_url' => $doc_url);
  }

	function getlayerdatabase($layer_id, $host){
		if($layer_id < 0){	# Rollenlayer
			$sql ='SELECT `connection`, "'.CUSTOM_SHAPE_SCHEMA.'" as `schema` FROM rollenlayer WHERE -id = '.$layer_id.' AND connectiontype = 6';
		}
		else{
			$sql ="SELECT concat('host=', c.host, ' port=', c.port, ' dbname=', c.dbname, ' user=', c.user, ' password=', c.password) as `connection`, `schema` FROM layer as l, connections as c WHERE l.Layer_ID = ".$layer_id." AND l.connection_id = c.id AND l.connectiontype = 6";
		}
		$this->debug->write("<p>file:kvwmap class:db_mapObj->getlayerdatabase - Lesen des connection-Strings des Layers:<br>" . $sql,4);
		$query=mysql_query($sql);
		if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		$rs = mysql_fetch_array($query);
		$connectionstring = $rs[0];
#		$this->debug->write("<p>file:kvwmap class:db_mapObj->getlayerdatabase - Gefundener Connection String des Layers:<br>" . $connectionstring, 4);
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

  function getSelectFromData($data){
    if(strpos($data, '(') === false){
      $from = stristr($data, ' from ');
      $usingposition = strpos($from, 'using');
      if($usingposition > 0){
        $from = substr($from, 0, $usingposition);
      }
      $select = 'select * '.$from.' where 1=1';
    }
    else{
      $select = stristr($data,'(');
      $select = trim($select, '(');
      $select = substr($select, 0, strrpos($select, ')'));
      if(strpos($select, 'select') != false){
        $select = stristr($select, 'select');
      }
    }
		return replace_params(
						$select,
						rolle::$layer_params,
						$this->User_ID,
						$this->Stelle_ID,
						rolle::$hist_timestamp,
						$this->user->rolle->language
					);
  }

	function getDataAttributes($database, $layer_id, $ifEmptyUseQuery = false) {
		$data = $this->getData($layer_id);
		if ($data != '') {
			$select = $this->getSelectFromData($data);
			if ($database->schema != '') {
				$select = str_replace($database->schema.'.', '', $select);
			}
			$ret = $database->getFieldsfromSelect($select);
			if ($ret[0]) {
				$this->GUI->add_message('error', $ret[1]);
			}
			return $ret[1];
		}
		elseif ($ifEmptyUseQuery){
			$path = replace_params(
				$this->getPath($layer_id),
				rolle::$layer_params,
				$this->User_ID,
				$this->Stelle_ID,
				rolle::$hist_timestamp,
				$this->user->rolle->language
			);
			return $this->getPathAttributes($database, $path);
		}
		else {
			echo 'Das Data-Feld des Layers mit der Layer-ID ' . $layer_id . ' ist leer.';
			return NULL;
		}
	}

	function getPathAttributes($database, $path) {
		$pathAttributes = array();
		if ($path != '') {
			$ret = $database->getFieldsfromSelect($path);
			if ($ret['success']) {
				$pathAttributes = $ret[1]; # Gebe die Attribute zurück
			}
			else {
				$pathAttributes = array();
				$this->GUI->add_message('waring', 'Der Fehler ist bei der Abfrage der Attribute des Query-Statements aufgetreten. Es sollte geprüft werden ob die Abfrage im Query-Statement korrekt ist.');
			}
		}
		return $pathAttributes;
	}

	function add_attribute_values($attributes, $database, $query_result, $withvalues = true, $stelle_id, $only_current_enums = false) {
		# Diese Funktion fügt den Attributen je nach Attributtyp zusätzliche Werte hinzu. Z.B. bei Auswahlfeldern die Auswahlmöglichkeiten.
		for($i = 0; $i < count($attributes['name']); $i++) {
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
			if ($attributes['options'][$i] == '' AND $attributes['constraints'][$i] != '' AND !in_array($attributes['constraints'][$i], array('PRIMARY KEY', 'UNIQUE'))) {	# das sind die Auswahlmöglichkeiten, die durch die Tabellendefinition in Postgres fest vorgegeben sind
				$attributes['enum_value'][$i] = explode("','", trim($attributes['constraints'][$i], "'"));
				$attributes['enum_output'][$i] = $attributes['enum_value'][$i];
			}
			if ($withvalues == true) {
				switch ($attributes['form_element_type'][$i]) {
					# Auswahlfelder
					case 'Auswahlfeld' : {
						if ($attributes['options'][$i] != '') {		 # das sind die Auswahlmöglichkeiten, die man im Attributeditor selber festlegen kann
							if (strpos($attributes['options'][$i], "'") === 0) {			# Aufzählung wie 'wert1','wert2','wert3'
								$attributes['enum_value'][$i] = explode("','", trim(str_replace(["', ", chr(10), chr(13)], ["',", '', ''], $attributes['options'][$i]), "'"));
								$attributes['enum_output'][$i] = $attributes['enum_value'][$i];
							}
							elseif (strpos(strtolower($attributes['options'][$i]), "select") === 0) {		 # SQl-Abfrage wie select attr1 as value, atrr2 as output from table1
								$optionen = explode(';', $attributes['options'][$i]);	# SQL; weitere Optionen
								# --------- weitere Optionen -----------
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
									}
								}
								# --------- weitere Optionen -----------
								if ($attributes['subform_layer_id'][$i] != NULL) {
									$attributes['options'][$i] = str_replace(' from ', ',oid from ', strtolower($optionen[0]));		# auch die oid abfragen
								}
								# ------------ SQL ---------------------
								else $attributes['options'][$i] = $optionen[0];
								# ------<required by>------
								$req_by_start = strpos(strtolower($attributes['options'][$i]), "<required by>");
								if ($req_by_start > 0) {
									$req_by_end = strpos(strtolower($attributes['options'][$i]), "</required by>");
									$req_by = trim(substr($attributes['options'][$i], $req_by_start+13, $req_by_end-$req_by_start-13));
									$attributes['req_by'][$i] = $req_by;		# das abhängige Attribut
									$attributes['options'][$i] = substr($attributes['options'][$i], 0, $req_by_start);		# required-Tag aus SQL entfernen
								}
								# ------<required by>------
								# -----<requires>------
								if (strpos(strtolower($attributes['options'][$i]), "<requires>") > 0) {
									if ($only_current_enums) {		# Ermittlung der Spalte, die als value dient
										$explo1 = explode(' as value', strtolower($attributes['options'][$i]));
										$attribute_value_column = array_pop(explode(' ', $explo1[0]));
									}
									if ($query_result != NULL) {
										foreach ($attributes['name'] as $attributename) {
											if (strpos($attributes['options'][$i], '<requires>'.$attributename.'</requires>') !== false) {
												$attributes['req'][$i][] = $attributename;			# die Attribute, die in <requires>-Tags verwendet werden zusammen sammeln
											}
										}
										foreach ($query_result as $k => $record) {	# bei Erfassung eines neuen DS hat $k den Wert -1
											$options = $attributes['options'][$i];
											foreach ($attributes['req'][$i] as $attributename) {
												if ($query_result[$k][$attributename] != '') {
													if ($only_current_enums) {	# in diesem Fall werden nicht alle Auswahlmöglichkeiten abgefragt, sondern nur die aktuellen Werte des Datensatzes (wird z.B. beim Daten-Export verwendet, da hier nur lesend zugegriffen wird und die Datenmengen sehr groß sein können)
														$options = str_ireplace('where', 'where '.$attribute_value_column.'::text = \''.$query_result[$k][$attributes['name'][$i]].'\' AND ', $options);
													}
													$options = str_replace('<requires>'.$attributename.'</requires>', "'" . $query_result[$k][$attributename]."'", $options);
												}
											}
											if (strpos($options, '<requires>') !== false) {
												#$options = '';		# wenn in diesem Datensatz des Query-Results ein benötigtes Attribut keinen Wert hat (also nicht alle <requires>-Einträge ersetzt wurden), sind die abhängigen Optionen für diesen Datensatz leer
												$attribute_value = $query_result[$k][$attributes['name'][$i]];
												if ($attribute_value != '')$options = "select '" . $attribute_value."' as value, '" . $attribute_value."' as output";
												else $options = '';		# wenn in diesem Datensatz des Query-Results ein benötigtes Attribut keinen Wert hat (also nicht alle <requires>-Einträge ersetzt wurden) aber das eigentliche Attribut einen Wert hat, wird dieser Wert als value und output genommen, ansonsten sind die Optionen leer
											}
											$attributes['dependent_options'][$i][$k] = $options;
										}
									}
									else {
										$attributes['options'][$i] = '';			# wenn kein Query-Result übergeben wurde, sind die Optionen leer
									}
								}
								# -----<requires>------
								if (is_array($attributes['dependent_options'][$i])) {	 # mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
									foreach ($query_result as $k => $record) {	# bei Erfassung eines neuen DS hat $k den Wert -1
										$sql = $attributes['dependent_options'][$i][$k];
										if ($sql != '') {
											$ret = $database->execSQL($sql, 4, 0);
											if ($ret[0]) {
												$this->GUI->add_message('error', 'Fehler bei der Abfrage der Optionen für das Attribut "' . $attributes['name'][$i] . '"<br>' . err_msg($PHP_SELF, __LINE__, $ret[1]));
												return 0;
											}
											while($rs = pg_fetch_array($ret[1])) {
												$attributes['enum_value'][$i][$k][] = $rs['value'];
												$attributes['enum_output'][$i][$k][] = $rs['output'];
												$attributes['enum_oid'][$i][$k][] = $rs['oid'];
											}
										}
									}
								}
								elseif ($attributes['options'][$i] != '') {
									$sql = str_replace('$stelleid', $stelle_id, $attributes['options'][$i]);
									$sql = str_replace('$userid', $this->User_ID, $sql);
									$ret = $database->execSQL($sql, 4, 0);
									if ($ret[0]) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
									while($rs = pg_fetch_array($ret[1])) {
										$attributes['enum_value'][$i][] = $rs['value'];
										$attributes['enum_output'][$i][] = $rs['output'];
										$attributes['enum_oid'][$i][] = $rs['oid'];
									}
								}
							}
						}
					} break;

					case 'Autovervollständigungsfeld' : case 'Autovervollständigungsfeld_zweispaltig' : {
						if ($attributes['options'][$i] != '') {
							if (strpos(strtolower($attributes['options'][$i]), "select") === 0) {		 # SQl-Abfrage wie select attr1 as value, atrr2 as output from table1
								$optionen = explode(';', $attributes['options'][$i]);	# SQL; weitere Optionen
								$attributes['options'][$i] = $optionen[0];
								if ($query_result != NULL) {
									foreach ($query_result as $k => $record) {	# bei Erfassung eines neuen DS hat $k den Wert -1
										$sql = $attributes['options'][$i];
										$value = $query_result[$k][$attributes['name'][$i]];
										if ($value != '' AND !in_array($attributes['operator'][$i], array('LIKE', 'NOT LIKE', 'IN'))) {			# falls eine LIKE-Suche oder eine IN-Suche durchgeführt wurde
											$sql = 'SELECT * FROM ('.$sql.') as foo WHERE value = \''.pg_escape_string($value).'\'';
											$ret = $database->execSQL($sql, 4, 0);
											if ($ret[0]) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
											$rs = pg_fetch_array($ret[1]);
											$attributes['enum_output'][$i][$k] = $rs['output'];
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
							$optionen = explode(';', $attributes['options'][$i]);	# Optionen; weitere Optionen
							$attributes['options'][$i] = $optionen[0];
							if (strpos($attributes['options'][$i], "'") === 0) {			# Aufzählung wie 'wert1','wert2','wert3'
								$attributes['enum_value'][$i] = explode(',', str_replace("'", "", $attributes['options'][$i]));
								$attributes['enum_output'][$i] = $attributes['enum_value'][$i];
							}
							elseif (strpos(strtolower($attributes['options'][$i]), "select") === 0) {		 # SQl-Abfrage wie select attr1 as value, atrr2 as output from table1
								if ($attributes['options'][$i] != '') {
									$sql = str_replace('$stelleid', $stelle_id, $attributes['options'][$i]);
									$ret = $database->execSQL($sql, 4, 0);
									if ($ret[0]) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
									while($rs = pg_fetch_array($ret[1])) {
										$attributes['enum_value'][$i][] = $rs['value'];
										$attributes['enum_output'][$i][] = $rs['output'];
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
									elseif ($further_options[$k] == 'horizontal') {			 # Radiobuttons nebeneinander anzeigen
										$attributes['horizontal'][$i] = true;
									}										
								}
							}
						}
					} break;

					# SubFormulare mit Primärschlüssel(n)
					case 'SubFormPK' : {
						if ($attributes['options'][$i] != '') {
							$options = explode(';', $attributes['options'][$i]);	# layer_id,pkey1,pkey2,pkey3...; weitere optionen
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
							$options = explode(';', $attributes['options'][$i]);	# layer_id,fkey1,fkey2,fkey3...; weitere optionen
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
							$options = explode(';', $attributes['options'][$i]);	# layer_id,pkey1,pkey2,preview_attribute; weitere Optionen
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

	function load_attributes($database, $path) {
		# Attributname und Typ aus Pfad-Statement auslesen:
		$attributes = $this->getPathAttributes($database, $path);
		return $attributes;
	}
	
	function save_attributes($layer_id, $attributes){
		$insert_count = 0;
		for ($i = 0; $i < count($attributes); $i++) {
			if($attributes[$i] == NULL)continue;
			if($attributes[$i]['nullable'] == '')$attributes[$i]['nullable'] = 'NULL';
			if($attributes[$i]['length'] == '')$attributes[$i]['length'] = 'NULL';
			if($attributes[$i]['decimal_length'] == '')$attributes[$i]['decimal_length'] = 'NULL';
			$sql = "
				INSERT INTO
					`layer_attributes`
				SET
					layer_id = " . $layer_id.",
					name = '" . $attributes[$i]['name'] . "',
					real_name = '" . $attributes[$i]['real_name'] . "',
					tablename = '" . $attributes[$i]['table_name'] ."',
					table_alias_name = '" . $attributes[$i]['table_alias_name'] . "',
					type = '" . $attributes[$i]['type'] . "',
					geometrytype = '" . $attributes[$i]['geomtype'] . "',
					constraints = '".addslashes($attributes[$i]['constraints']) . "',
					nullable = " . $attributes[$i]['nullable'] . ",
					length = " . $attributes[$i]['length'] . ",
					decimal_length = " . $attributes[$i]['decimal_length'] . ",
					`default` = '".addslashes($attributes[$i]['default']) . "',
					`order` = " . $i . "
				ON DUPLICATE KEY UPDATE
					real_name = '" . $attributes[$i]['real_name'] . "',
					tablename = '" . $attributes[$i]['table_name'] . "',
					table_alias_name = '" . $attributes[$i]['table_alias_name'] . "',
					type = '" . $attributes[$i]['type'] . "',
					geometrytype = '" . $attributes[$i]['geomtype'] . "',
					constraints = '".addslashes($attributes[$i]['constraints']) . "',
					nullable = " . $attributes[$i]['nullable'] . ",
					length = " . $attributes[$i]['length'] . ",
					decimal_length = " . $attributes[$i]['decimal_length'] . ",
					`default` = '" . addslashes($attributes[$i]['default']) . "',
					`order` = `order` + ".$insert_count."
			";
			#echo '<br>Sql: ' . $sql;
			$this->debug->write("<p>file:kvwmap class:db_mapObj->save_postgis_attributes - Speichern der Layerattribute:<br>" . $sql,4);
			$query=mysql_query($sql);
			if(mysql_affected_rows() == 1)$insert_count++;		# ein neues Attribut wurde per Insert eingefügt
			if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
		}
	}		

	function save_postgis_attributes($layer_id, $attributes, $maintable, $schema){
		$this->save_attributes($layer_id, $attributes);
	
		if($maintable == ''){
			$maintable = $attributes[0]['table_name'];
			$sql = "UPDATE layer SET maintable = '" . $maintable."' WHERE (maintable IS NULL OR maintable = '') AND Layer_ID = " . $layer_id;
			$this->debug->write("<p>file:kvwmap class:db_mapObj->save_postgis_attributes - Speichern der Layerattribute:<br>" . $sql,4);
			$query=mysql_query($sql);
			if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
		}

		$sql = "select 1 from information_schema.views WHERE table_name = '" . $maintable."' AND table_schema = '" . $schema."'";
		$query = pg_query($sql);
		$is_view = pg_num_rows($query);
		$sql = "UPDATE layer SET maintable_is_view = " . $is_view." WHERE Layer_ID = " . $layer_id;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->save_postgis_attributes - Speichern der Layerattribute:<br>" . $sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }

		# den PRIMARY KEY constraint rausnehmen, falls der tablename nicht der maintable entspricht
		$sql = "
			UPDATE
				`layer_attributes`,
				`layer`
			SET
				`constraints` = ''
			WHERE
				`layer_attributes`.
				`layer_id` = " . $layer_id . " AND
				`layer`.`Layer_ID` = " . $layer_id . " AND
				`constraints` = 'PRIMARY KEY' AND
				`tablename` != maintable
		";
		#echo '<br>Sql: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->save_postgis_attributes - Speichern der Layerattribute:<br>" . $sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
	}

  function delete_old_attributes($layer_id, $attributes){
  	$sql = "DELETE FROM layer_attributes WHERE layer_id = " . $layer_id;
  	if($attributes){
  		$sql.= " AND name NOT IN (";
	  	for($i = 0; $i < count($attributes); $i++){
	  		$sql .= "'" . $attributes[$i]['name']."',";
	  	}
	  	$sql = substr($sql, 0, -1);
	  	$sql .=")";
  	}
  	#echo $sql.'<br><br>';
  	$this->debug->write("<p>file:kvwmap class:db_mapObj->delete_old_attributes - Löschen von alten Layerattributen:<br>" . $sql,4);
    $query=mysql_query($sql);
		if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
  }

	function create_layer_dumpfile($database, $layer_ids, $with_privileges = false, $with_datatypes = false) {
		$success = true;
		$dump_text .= "-- Layerdump aus kvwmap vom " . date("d.m.Y H:i:s");
		$dump_text .= "\n-- Achtung: Die Datenbank in die der Dump eingespielt wird, sollte die gleiche Migrationsversion haben,";
		$dump_text .= "\n-- wie die Datenbank aus der exportiert wurde! Anderenfalls kann es zu Fehlern bei der Ausführung des SQL kommen.";
		$dump_text .= "\n\nSET @group_id = 1;";
		$dump_text .= "\nSET @connection = 'user=xxxx password=xxxx dbname=kvwmapsp';";

		if ($with_privileges) {
			# Frage Stellen der Layer ab
			$sql = "
				SELECT DISTINCT
					ID,
					Bezeichnung
				FROM
					`stelle` AS s JOIN
					`used_layer` AS ul ON (s.`ID` = ul.`Stelle_ID`)
				WHERE
					ul.`Layer_ID` IN (" . implode(', ', $layer_ids) . ")
			";
			#echo '<br>Sql: ' . $sql;
			$ret = $database->execSQL($sql, 4, 0);
			if ($ret[0]) {
				$success = false;
				$err_msg = $ret[1];
			}
			else {
				while($rs = mysql_fetch_assoc($ret[1])) {
					$stelle_id_var = '@stelle_id_' . $rs['ID'];
					$stellen[] = array(
						'id' => $rs['ID'],
						'var' => $stelle_id_var
					);

					$stelle = $database->create_insert_dump(
						'stelle',
						'ID',
						"
							SELECT
								*
							FROM
								`stelle`
							WHERE
								`ID` = " . $rs['ID'] . "
						"
					);
					# Stelle
					$dump_text .= "\n\n-- Stelle " . $rs['Bezeichnung'] . " (id=" . $rs['ID'] . ")";
					$dump_text .= "\n" . $stelle['insert'][0];

					# Variable für Stelle
					$dump_text .= "\n-- Falls Stelle schon existiert, INSERT mit /* */ auskommentieren und statt LAST_INSERT_ID() die vorhandene Stellen-ID eintragen.";
					$dump_text .= "\nSET " . $stelle_id_var . " = LAST_INSERT_ID();";
				}
			}
		}

		for($i = 0; $i < count($layer_ids); $i++) {
			$layer = $database->create_insert_dump('layer', '', 'SELECT `Name`, `alias`, `Datentyp`, \'@group_id\' AS `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, wms_auth_username, wms_auth_password, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg`, `trigger_function`, `sync` FROM layer WHERE Layer_ID='.$layer_ids[$i]);
			$dump_text .= "\n\n-- Layer " . $layer_ids[$i] . "\n" . $layer['insert'][0];
			$last_layer_id = '@last_layer_id'.$layer_ids[$i];
			$dump_text .= "\nSET " . $last_layer_id . "=LAST_INSERT_ID();";

			if ($with_privileges) {
				for ($s = 0; $s < count($stellen); $s++) {
					# Zuordnung des Layers zur Stelle
					$used_layer = $database->create_insert_dump(
						'used_layer',
						'',
						"
							SELECT
								'" . $last_layer_id . "' AS Layer_ID,
								'" . $stellen[$s]['var'] . "' AS Stelle_ID,
								`queryable`,
								`drawingorder`,
								`legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`,
								`template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`,
								`start_aktiv`,
								`use_geom`
							FROM
								`used_layer`
							WHERE
								`Layer_ID` = " . $layer_ids[$i] . " AND
								`Stelle_ID` = " . $stellen[$s]['id'] . "
						"
					);
					if (count($used_layer['insert']) > 0) {
						$dump_text .= "\n\n-- Zuordnung Layer " . $layer_ids[$i] . " zu Stelle " . $stellen[$s]['id'] . "\n" . implode("\n", $used_layer['insert']);
					}

					# Attributfilter des Layers in der Stelle
					$attributfilter2used_layer = $database->create_insert_dump(
						'u_attributfilter2used_layer',
						'',
						"
							SELECT
								'" . $stellen[$s]['var'] . "' AS Stelle_ID,
								'" . $last_layer_id . "' AS Layer_ID,
								`attributname`,
								`attributvalue`,
								`operator`,
								`type`
							FROM
								`u_attributfilter2used_layer`
							WHERE
								`Layer_ID` = " . $layer_ids[$i] . " AND
								`Stelle_ID` = " . $stellen[$s]['id'] . "
						"
					);
					if (count($attributfilter2used_layer['insert']) > 0) {
						$dump_text .= "\n\n-- Zuordnung der Attributfilter des Layers " . $layer_ids[$i] . " zur Stelle " . $stellen[$s]['id'] . "\n" . implode("\n", $attributfilter2used_layer['insert']);
					}
				}
			}

			$layer_attributes = $database->create_insert_dump('layer_attributes', 'layer_attribut_id', 'SELECT `name` AS layer_attribut_id, \''.$last_layer_id.'\' AS `layer_id`, `name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`, `options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `arrangement`, `labeling`, `raster_visibility`, `dont_use_for_new`, `mandatory`, `quicksearch`, `visible`, `vcheck_attribute`, `vcheck_operator`, `vcheck_value`, `order`, `privileg`, `query_tooltip` FROM layer_attributes WHERE layer_id = ' . $layer_ids[$i]);
			for($j = 0; $j < count($layer_attributes['insert']); $j++){
				# Attribut des Layers
				$dump_text .= "\n\n-- Attribut " . $layer_attributes['extra'][$j] . " des Layers " . $layer_ids[$i] . "\n" . $layer_attributes['insert'][$j];
			}

			if ($with_privileges) {
				for ($s = 0; $s < count($stellen); $s++) {
					# Attributrechte in der Stelle
					$layer_attributes2stelle = $database->create_insert_dump(
						'layer_attributes2stelle',
						'',
						"
							SELECT
								'". $last_layer_id . "' AS layer_id,
								'" . $stellen[$s]['var'] . "' AS stelle_id,
								`attributename`,
								`privileg`,
								`tooltip`
							FROM
								`layer_attributes2stelle`
							WHERE
								`layer_id` = " . $layer_ids[$i] . " AND
								`stelle_id` = " . $stellen[$s]['id'] . "
						"
					);
					if (count($layer_attributes2stelle['insert']) > 0) {
						$dump_text .= "\n\n-- Zuordnung der Layerattribute des Layers " . $layer_ids[$i] . " zur Stelle " . $stellen[$s]['id'] . "\n" . implode("\n", $layer_attributes2stelle['insert']);
					}
				}
			}

			$classes = $database->create_insert_dump('classes', 'Class_ID', 'SELECT `Class_ID`, `Name`, \''.$last_layer_id.'\' AS `Layer_ID`, `Expression`, `drawingorder`, `text` FROM classes WHERE Layer_ID=' . $layer_ids[$i]);
			for ($j = 0; $j < count($classes['insert']); $j++) {
				$dump_text .= "\n\n-- Class " . $classes['extra'][$j] . " des Layers " . $layer_ids[$i] . "\n" . $classes['insert'][$j];
				$dump_text .= "\nSET @last_class_id=LAST_INSERT_ID();";

				$styles = $database->create_insert_dump('styles', 'Style_ID', 'SELECT styles.Style_ID, `symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`, `colorrange`, `datarange`, `rangeitem`, `opacity`, `minsize`,`maxsize`, `minscale`, `maxscale`, `angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`, `offsetx`, `offsety`, `polaroffset`, `pattern`, `geomtransform`, `gap`, `initialgap`, `linecap`, `linejoin`, `linejoinmaxsize` FROM styles, u_styles2classes WHERE u_styles2classes.style_id = styles.Style_ID AND Class_ID='.$classes['extra'][$j].' ORDER BY drawingorder');				
				for ($k = 0; $k < count($styles['insert']); $k++) {
					$dump_text .= "\n\n-- Style " . $styles['extra'][$k] . " der Class " . $classes['extra'][$j];
					$dump_text .= "\n" . $styles['insert'][$k] . "\nSET @last_style_id=LAST_INSERT_ID();";
					$dump_text .= "\n-- Zuordnung Style " . $styles['extra'][$k] . " zu Class " . $classes['extra'][$j];
					$dump_text .= "\nINSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, " . $k . ");";
				}

				$labels = $database->create_insert_dump('labels', 'Label_ID', 'SELECT labels.Label_ID, `font`,`type`,`color`,`outlinecolor`,`shadowcolor`,`shadowsizex`,`shadowsizey`,`backgroundcolor`,`backgroundshadowcolor`,`backgroundshadowsizex`,`backgroundshadowsizey`,`size`,`minsize`,`maxsize`,`position`,`offsetx`,`offsety`,`angle`,`autoangle`,`buffer`,`antialias`,`minfeaturesize`,`maxfeaturesize`,`partials`,`wrap`,`the_force` FROM labels, u_labels2classes WHERE u_labels2classes.label_id = labels.Label_ID AND Class_ID='.$classes['extra'][$j]);
				for ($k = 0; $k < count($labels['insert']); $k++) {
					$dump_text .= "\n\n-- Label " . $labels['extra'][$k] . " der Class " . $classes['extra'][$j];
					$dump_text .= "\n" . $labels['insert'][$k] . "\nSET @last_label_id=LAST_INSERT_ID();";
					$dump_text .= "\n-- Zuordnung Label " . $labels['extra'][$k] . " zu Class " . $classes['extra'][$j];
					$dump_text .=	"\nINSERT INTO u_labels2classes (label_id, class_id) VALUES (@last_label_id, @last_class_id);";
				}
			}
		}
		for ($i = 0; $i < count($layer_ids); $i++) {
			$dump_text .= "\n\n-- Replace attribute options for Layer " . $layer_ids[$i];
			$dump_text .= "\nUPDATE layer_attributes SET options = REPLACE(options, 'layer_id=" . $layer_ids[$i]."', CONCAT('layer_id=', @last_layer_id" . $layer_ids[$i].")) WHERE layer_id IN (@last_layer_id" . implode(', @last_layer_id', $layer_ids) . ") AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink');";
			$dump_text .= "\nUPDATE layer_attributes SET options = REPLACE(options, '" . $layer_ids[$i].",', CONCAT(@last_layer_id" . $layer_ids[$i].", ',')) WHERE layer_id IN (@last_layer_id" . implode(', @last_layer_id', $layer_ids) . ") AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK');";
		}

		if ($with_datatypes) {
			# Frage Datatypes der Layer ab
			$datatypes = $this->get_datatypes($layer_ids);

			foreach ($datatypes AS $datatype) {
				$datatype_dump = $database->create_insert_dump(
					'datatypes',
					'id',
					"
						SELECT
							*
						FROM
							datatypes
						WHERE
							id = " . $datatype['id'] . "
					"
				);

				$dump_text .= "\n\n-- Datatype " . $datatype['id'] . "\n" . $datatype_dump['insert'][0];
				$last_datatype_id = '@last_datatype_id' . $datatype['id'];
				$dump_text .= "\nSET " . $last_datatype_id . "=LAST_INSERT_ID();";

				$datatype_attributes_dump = $database->create_insert_dump(
					'datatype_attributes',
					'',
					"
						SELECT
							'" . $last_datatype_id . "' AS datatype_id,
							`name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`,
							`options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `quicksearch`,
							`order`, `privileg`, `query_tooltip`, `visible`, `vcheck_attribute`, `vcheck_operator`, `vcheck_value`, `arrangement`, `labeling`
						FROM
							datatype_attributes
						WHERE
							datatype_id = " . $datatype['id'] . "
					"
				);

				$dump_text .= "\n\n-- Datatype_attributes " . $datatype['id'] . "\n" . $datatype_attributes_dump['insert'][0];
			}
		}

		$filename = rand(0, 1000000).'.sql';
		$fp = fopen(IMAGEPATH . $filename, 'w');
		fwrite($fp, $dump_text);
		//fwrite($fp, str_replace('
//', '', $dump_text));
		#fwrite($fp, utf8_decode($dump_text));

		return array(
			'success' => $success,
			'layer_dumpfile' => $filename
		);
	}

  function deleteLayer($id){
    $sql = 'DELETE FROM layer WHERE Layer_ID = '.$id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->deleteLayer - Löschen eines Layers:<br>" . $sql,4);
    $query=mysql_query($sql);
		if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
    if(MYSQLVERSION > 412){
      # Den Autowert für die Layer_id zurücksetzen
      $sql ="ALTER TABLE layer AUTO_INCREMENT = 1";
      $this->debug->write("<p>file:kvwmap class:db_mapObj->deleteLayer - Zurücksetzen des Auto_Incrementwertes:<br>" . $sql,4);
      #echo $sql;
      $query=mysql_query($sql);
			if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
    }
  }

	function deleteRollenFilter(){
		$sql = 'UPDATE u_rolle2used_layer SET rollenfilter = NULL WHERE user_id = '.$this->User_ID;
		#echo $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->deleteRollenFilter:<br>" . $sql,4);
		$query=mysql_query($sql);
		$sql = 'UPDATE rollenlayer SET rollenfilter = NULL WHERE user_id = '.$this->User_ID;
		#echo $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->deleteRollenFilter:<br>" . $sql,4);
		$query=mysql_query($sql);
	}

  function deleteRollenLayer($id = NULL, $type = NULL){
  	$rollenlayerset = $this->read_RollenLayer($id, $type);
		for($i = 0; $i < count($rollenlayerset); $i++){
			if($rollenlayerset[$i]['Datentyp'] != 3 AND $rollenlayerset[$i]['Typ'] == 'import'){		# beim Import-Layern die Tabelle löschen
				$explosion = explode(CUSTOM_SHAPE_SCHEMA.'.', $rollenlayerset[$i]['Data']);
				$explosion = explode(' ', $explosion[1]);
				$sql = "SELECT count(id) FROM rollenlayer WHERE Data like '%" . $explosion[0]."%'";
				$query=mysql_query($sql);
				if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
				$rs=mysql_fetch_array($query);
				if($rs[0] == 1){		# Tabelle nur löschen, wenn das der einzige Layer ist, der sie benutzt
					$sql = 'DROP TABLE IF EXISTS '.CUSTOM_SHAPE_SCHEMA.'.'.$explosion[0].';';
					$this->debug->write("<p>file:kvwmap class:db_mapObj->deleteRollenLayer - Löschen eines RollenLayers:<br>" . $sql,4);
					$query=pg_query($sql);
				}
			}
			$sql = 'DELETE FROM rollenlayer WHERE id = '.$rollenlayerset[$i]['id'];
			#echo $sql;
			$this->debug->write("<p>file:kvwmap class:db_mapObj->deleteRollenLayer - Löschen eines RollenLayers:<br>" . $sql,4);
			$query=mysql_query($sql);
			if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
			if(MYSQLVERSION > 412){
				# Den Autowert für die Layer_id zurücksetzen
				$sql ="ALTER TABLE rollenlayer AUTO_INCREMENT = 1";
				$this->debug->write("<p>file:kvwmap class:db_mapObj->deleteRollenLayer - Zurücksetzen des Auto_Incrementwertes:<br>" . $sql,4);
				#echo $sql;
				$query=mysql_query($sql);
				if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
			}
			$this->delete_layer_attributes(-$rollenlayerset[$i]['id']);
			# auch die Klassen und styles löschen
			if($rollenlayerset[$i]['Class'] != ''){
				foreach($rollenlayerset[$i]['Class'] as $class){
					$this->delete_Class($class['Class_ID']);
					if($class['Style'] != ''){
						foreach($class['Style'] as $style){
							$this->delete_Style($style['Style_ID']);
						}
					}
				}
			}
		}
  }

	function addRollenLayerStyling($layer_id, $datatype, $labelitem, $user){
		$attrib['name'] = ' ';
		$attrib['layer_id'] = -$layer_id;
		$attrib['expression'] = '';
		$attrib['order'] = 0;
		$class_id = $this->new_Class($attrib);
		$this->formvars['class'] = $class_id;
		$color = $user->rolle->readcolor();
		$style['colorred'] = $color['red'];
		$style['colorgreen'] = $color['green'];
		$style['colorblue'] = $color['blue'];
		$style['outlinecolorred'] = 0;
		$style['outlinecolorgreen'] = 0;
		$style['outlinecolorblue'] = 0;
		switch ($datatype) {
			case 0 : {
				if(defined('ZOOM2POINT_STYLE_ID') AND ZOOM2POINT_STYLE_ID != ''){
					$style_id = $this->copyStyle(ZOOM2POINT_STYLE_ID);
				}
				else{
					$style['size'] = 8;
					$style['maxsize'] = 8;
					$style['symbolname'] = 'circle';
				}
			} break;
			case 1 : {
				$style['width'] = 2;
				$style['minwidth'] = 1;
				$style['maxwidth'] = 3;
				$style['symbolname'] = NULL;
			} break;
			case 2 :{
				$style['size'] = 1;
				if($user->rolle->result_hatching){
					$style['symbolname'] = 'hatch';
					$style['size'] = 11;
					$style['width'] = 5;
					$style['angle'] = 45;
				}
				else{
					$style['symbolname'] = NULL;
				}
			}
		}
		$style['backgroundcolor'] = NULL;
		$style['minsize'] = NULL;
		$style_id = $this->new_Style($style);
		$this->addStyle2Class($class_id, $style_id, 0); # den Style der Klasse zuordnen
		if($user->rolle->result_hatching){
			$style['symbolname'] = NULL;
			$style['width'] = 1;
			$style['colorred'] = -1;
			$style['colorgreen'] = -1;
			$style['colorblue'] = -1;
			$style_id = $this->new_Style($style);
			$this->addStyle2Class($class_id, $style_id, 0); # den Style der Klasse zuordnen
		}
		if($labelitem != '') {
			$label['font'] = 'arial';
			$label['color'] = '0 0 0';
			$label['outlinecolor'] = '255 255 255';
			$label['size'] = 8;
			$label['minsize'] = 6;
			$label['maxsize'] = 10;
			$label['position'] = 9;
			$new_label_id = $this->new_Label($label);
			$this->addLabel2Class($class_id, $new_label_id, 0);
		}
	}

	function newRollenLayer($formvars){
		$formvars['Data'] = str_replace ( "'", "''", $formvars['Data']);
		$formvars['query'] = str_replace ( "'", "''", $formvars['query']);

		$sql = "
			INSERT INTO rollenlayer (
				`user_id`,
				`stelle_id`,
				`aktivStatus`,
				`Name`,
				`Datentyp`,
				`Gruppe`,
				`Typ`,
				`Data`,
				`query`,
				`connectiontype`,
				`connection`,
				`transparency`,
				`epsg_code`,
				`labelitem`,
				`classitem`
			)
			VALUES (
				'" . $formvars['user_id'] . "',
				'" . $formvars['stelle_id'] . "',
				'" . $formvars['aktivStatus'] . "',
				'" . addslashes($formvars['Name']) . "',
				'" . $formvars['Datentyp'] . "',
				'" . $formvars['Gruppe'] . "',
				'" . $formvars['Typ'] . "',
				'" . $formvars['Data'] . "',
				'" . $formvars['query'] . "',
				'" . $formvars['connectiontype'] . "',
				'" . $formvars['connection'] . "',
				'" . $formvars['transparency'] . "',
				'" . $formvars['epsg_code'] . "',
				'" . $formvars['labelitem'] . "',
				'" . $formvars['classitem'] . "'
			)
		";
    #echo 'SQL: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->newRollenLayer - Erzeugen eines RollenLayers" . str_replace($formvars['connection'], 'Connection', $sql), 4);
		$query=mysql_query($sql);
		if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql, $formvars['connection']); return 0; }
		return mysql_insert_id();
	}

	function createAutoClasses($classes, $attribute, $layer_id, $datatype, $database){
		global $supportedLanguages;
		$result_colors = read_colors($database);
		shuffle($result_colors);
		$i = 0;
		foreach($classes as $value => $name){
			if($i == count($result_colors))return;				# Anzahl der Klassen ist auf die Anzahl der Colors beschränkt
			$classdata['name'] = $name.' ';
			foreach($supportedLanguages as $language){
				if($language != 'german'){
					$classdata['name_'.$language] = $name.' ';
				}
			}
      $classdata['layer_id'] = -$layer_id;
			$classdata['classification'] = $attribute;
      $classdata['expression'] = "('[" . $attribute."]' eq '" . $value."')";
      $classdata['order'] = 0;
      $class_id = $this->new_Class($classdata);
    	$style['colorred'] = $result_colors[$i]['red'];
      $style['colorgreen'] = $result_colors[$i]['green'];
      $style['colorblue'] = $result_colors[$i]['blue'];
      $style['outlinecolorred'] = 0;
      $style['outlinecolorgreen'] = 0;
      $style['outlinecolorblue'] = 0;
     	$style['size'] = 3;
     	if($datatype < 2){
      	$style['symbolname'] = 'circle';
      	if($datatype == 0){
      		$style['size'] = 13;
      		$style['minsize'] = 5;
      		$style['maxsize'] = 20;
      	}
     	}
      $style['backgroundcolor'] = NULL;
      if (MAPSERVERVERSION > '500') {
      	$style['angle'] = 360;
      }
      $style_id = $this->new_Style($style);
      $this->addStyle2Class($class_id, $style_id, 0);          # den Style der Klasse zuordnen
			$i++;
		}
	}

	function updateLayer($formvars) {
		global $supportedLanguages;
		$formvars['Layer_ID'] = $formvars['id'];

		$attribute_sets = array();

		# Scheibt alle unterstützten Language Attribute, außer german
		foreach($supportedLanguages as $language) {
			if ($language != 'german') {
				$attribute_sets[] = "`Name_" . $language . "` = '" . $formvars['Name_'.$language] . "'";
			}
		}

		# Schreibt alle Attribute, die nur geschrieben werden sollen wenn Wert != '' ist
		foreach(
			array(
				'Layer_ID'
			) AS $key
		) {
			if ($formvars[$key]	!= '') {
				$attribute_sets[] = "`" . $key . "` = '" . $formvars[$key] . "'";
			}
		}

		# Schreibt alle Attribute, die NULL bekommen sollen wenn Wert == '' ist
		foreach(
			array(
				'cluster_maxdistance',
				'transparency',
				'drawingorder',
				'legendorder',
				'labelmaxscale',
				'labelminscale',
				'minscale',
				'maxscale',
				'symbolscale',
				'requires',
				'connection_id'
			) AS $key
		) {
			$attribute_sets[] = $key . " = " . ($formvars[$key] == '' ? 'NULL' : "'" . $formvars[$key] . "'");
		}

		# Schreibt alle Attribute, die '0' bekommen sollen wenn Wert == '' ist
		foreach(
			array(
				'sync',
				'listed'
			) AS $key
		) {
			$attribute_sets[] = $key . " = '" . ($formvars[$key] == '' ? '0' : $formvars[$key]) . "'";
		}

		# Schreibt alle Attribute, die immer geschrieben werden sollen, egal wie der Wert ist
		# Besonderheit beim Attribut classification, kommt aus Field layer_classification,
		# weil classification schon belegt ist von den Classes
		$attribute_sets[] = "`classification` = '" . $formvars['layer_classification'] . "'";
		# the rest where column names equal to the field names in layer editor form
		foreach(
			array(
				'Name',
				'alias',
				'Datentyp',
				'Gruppe',
				'pfad',
				'maintable',
				'oid',
				'Data',
				'schema',
				'document_path',
				'document_url',
				'ddl_attribute',
				'tileindex',
				'tileitem',
				'labelangleitem',
				'labelitem',
				'offsite',
				'labelrequires',
				'postlabelcache',
				'connection',
				'printconnection',
				'connectiontype',
				'classitem',
				'filteritem',
				'tolerance',
				'toleranceunits',
				'epsg_code',
				'template',
				'queryable',
				'ows_srs',
				'wms_name',
				'wms_keywordlist',
				'wms_server_version',
				'wms_format',
				'wms_connectiontimeout',
				'wms_auth_username',
				'wms_auth_password',
				'wfs_geom',
				'selectiontype',
				'querymap',
				'processing',
				'kurzbeschreibung',
				'datenherr',
				'metalink',
				'status',
				'trigger_function'
			) AS $key
		) {
			$attribute_sets[] = "`" . $key . "` = '" . $formvars[$key] . "'";
		}

		$sql = "
			UPDATE
				layer
			SET
				" . implode(', ', $attribute_sets) . "
			WHERE
				Layer_ID = " . $formvars['selected_layer_id'] . "
		";
		#echo '<br>Update Layer mit SQL: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->updateLayer - Aktualisieren eines Layers:<br>" . $sql, 4);
		$ret = $this->GUI->database->execSQL($sql, 4, 1, true);
		if (!$ret['success']) {
			$this->GUI->add_message('error', $ret[1]);
			return 0;
		}
	}

  function newLayer($layerdata) {
		global $supportedLanguages;
    # Erzeugt einen neuen Layer (entweder aus formvars oder aus einem Layerobjekt)
    if(is_array($layerdata)){
      $formvars = $layerdata;   # formvars wurden übergeben

      $sql = "INSERT INTO layer (";
      if($formvars['id'] != ''){
        $sql.="`Layer_ID`, ";
      }
      $sql.= "`Name`, ";
			foreach($supportedLanguages as $language){
				if($language != 'german'){
					$sql .= "`Name_" . $language."`, ";
				}
			}
			$sql.="`alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `oid`, `Data`, `schema`, `document_path`, `document_url`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `postlabelcache`, `connection`, `connection_id`, `printconnection`, `connectiontype`, `classitem`, `classification`, `filteritem`, `cluster_maxdistance`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `symbolscale`, `offsite`, `requires`, `ows_srs`, `wms_name`, `wms_keywordlist`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `status`, `trigger_function`, `sync`, `listed`) VALUES(";
      if($formvars['id'] != ''){
        $sql.="'" . $formvars['id']."', ";
      }
      $sql .= "'" . $formvars['Name']."', ";
			foreach($supportedLanguages as $language){
				if($language != 'german'){
					$sql .= "'" . $formvars['Name_'.$language]."', ";
				}
			}
      $sql .= "'" . $formvars['alias']."', ";
      $sql .= "'" . $formvars['Datentyp']."', ";
      $sql .= "'" . $formvars['Gruppe']."', ";
      if($formvars['pfad'] == ''){
        $sql .= "NULL, ";
      }
      else{
        $sql .= "'" . $formvars['pfad']."', ";
      }
    	if($formvars['maintable'] == ''){
        $sql .= "NULL, ";
      }
      else{
        $sql .= "'" . $formvars['maintable']."', ";
      }
			$sql .= "'" . $formvars['oid']."', ";
      if($formvars['Data'] == ''){
        $sql .= "NULL, ";
      }
      else{
        $sql .= "'" . $formvars['Data']."', ";
      }
      if($formvars['schema'] == ''){
        $sql .= "NULL, ";
      }
      else{
        $sql .= "'" . $formvars['schema']."', ";
      }
      if($formvars['document_path'] == '')$sql .= "NULL, ";
      else $sql .= "'" . $formvars['document_path']."', ";
			if($formvars['document_url'] == '')$sql .= "NULL, ";
      else $sql .= "'" . $formvars['document_url']."', ";
      $sql .= "'" . $formvars['tileindex']."', ";
      $sql .= "'" . $formvars['tileitem']."', ";
      $sql .= "'" . $formvars['labelangleitem']."', ";
      $sql .= "'" . $formvars['labelitem']."', ";
      if($formvars['labelmaxscale']==''){$formvars['labelmaxscale']='NULL';}
      $sql .= $formvars['labelmaxscale'].", ";
      if($formvars['labelminscale']==''){$formvars['labelminscale']='NULL';}
      $sql .= $formvars['labelminscale'].", ";
      $sql .= "'" . $formvars['labelrequires']."', ";
			$sql .= "'" . $formvars['postlabelcache']."', ";
      $sql .= "'" . $formvars['connection']."', ";
			if($formvars['connection_id'] == '')$sql .= "NULL, ";
      else $sql .= "'" . $formvars['connection_id']."', ";
      $sql .= "'" . $formvars['printconnection']."', ";
      $sql .= ($formvars['connectiontype'] =='' ? "6" : $formvars['connectiontype']) .", "; # Set default to postgis layer
      $sql .= "'" . $formvars['classitem']."', ";
			$sql .= "'" . $formvars['layer_classification']."', ";
      $sql .= "'" . $formvars['filteritem']."', ";
			if($formvars['cluster_maxdistance'] == '')$formvars['cluster_maxdistance'] = 'NULL';
			$sql .= $formvars['cluster_maxdistance'].", ";
      if($formvars['tolerance']==''){$formvars['tolerance']='3';}
      $sql .= $formvars['tolerance'].", ";
      if($formvars['toleranceunits']==''){$formvars['toleranceunits']='pixels';}
      $sql .= "'" . $formvars['toleranceunits']."', ";
      $sql .= "'" . $formvars['epsg_code']."', ";
      $sql .= "'" . $formvars['template']."', ";
      $sql .= "'" . $formvars['queryable']."', ";
      if($formvars['transparency']==''){$formvars['transparency']='NULL';}
      $sql .= $formvars['transparency'].", ";
      if($formvars['drawingorder']==''){$formvars['drawingorder']='NULL';}
      $sql .= $formvars['drawingorder'].", ";
      if($formvars['legendorder']==''){$formvars['legendorder']='NULL';}
      $sql .= $formvars['legendorder'].", ";
      if($formvars['minscale']==''){$formvars['minscale']='NULL';}
      $sql .= $formvars['minscale'].", ";
      if($formvars['maxscale']==''){$formvars['maxscale']='NULL';}
      $sql .= $formvars['maxscale'].", ";
			if($formvars['symbolscale']==''){$formvars['symbolscale']='NULL';}
      $sql .= $formvars['symbolscale'].", ";
      $sql .= "'" . $formvars['offsite']."', ";
			if($formvars['requires']==''){$formvars['requires']='NULL';}
      $sql .= $formvars['requires'].", ";
      $sql .= "'" . $formvars['ows_srs']."', ";
      $sql .= "'" . $formvars['wms_name']."', ";
			$sql .= "'" . $formvars['wms_keywordlist']."', ";			
      $sql .= "'" . $formvars['wms_server_version']."', ";
      $sql .= "'" . $formvars['wms_format']."', ";
      if ($formvars['wms_connectiontimeout']=='') {
        $formvars['wms_connectiontimeout']='60';
      }
      $sql .= $formvars['wms_connectiontimeout'].", ";
      $sql .= "'" . $formvars['wms_auth_username']."', ";
      $sql .= "'" . $formvars['wms_auth_password']."', ";
      $sql .= "'" . $formvars['wfs_geom']."', ";
      $sql .= "'" . $formvars['selectiontype']."', ";
      $sql .= "'" . $formvars['querymap']."', ";
      $sql .= "'" . $formvars['processing']."', ";
      $sql .= "'" . $formvars['kurzbeschreibung']."', ";
      $sql .= "'" . $formvars['datenherr']."', ";
      $sql .= "'" . $formvars['metalink']."', ";
			$sql .= "'" . $formvars['status']."', ";
			$sql .= "'" . $formvars['trigger_function']."', ";
			if($formvars['sync'] == '')$formvars['sync'] = 0;
			$sql .= "'" . $formvars['sync']."', ";
			if($formvars['listed'] == '')$formvars['listed'] = 0;
			$sql .= "'" . $formvars['listed']."'";
      $sql .= ")";

    }
    else{
      $layer = $layerdata;      # ein Layerobject wurde übergeben
      $projection = explode('epsg:', $layer->getProjection());
      $sql = "INSERT INTO layer (`Name`, `Datentyp`, `Gruppe`, `pfad`, `Data`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `connectiontype`, `classitem`,  `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `trigger_function`, `sync`) VALUES(";
      $sql .= "'" . $layer->name."', ";
      $sql .= "'" . $layer->type."', ";
      $sql .= "'" . $layer->group."', ";
      $sql .= "'', ";                 # pfad
      $sql .= "'" . $layer->data."', ";
      $sql .= "'" . $layer->tileindex."', ";
      $sql .= "'" . $layer->tileitem."', ";
      $sql .= "'" . $layer->labelangleitem."', ";
      $sql .= "'" . $layer->labelitem."', ";
      $sql .= $layer->labelmaxscale.", ";
      $sql .= $layer->labelminscale.", ";
      $sql .= "'" . $layer->labelrequires."', ";
      $sql .= "'" . $layer->connection."', ";
      $sql .= $layer->connectiontype.", ";
      $sql .= "'" . $layer->classitem."', ";
      $sql .= "'" . $layer->filteritem."', ";
      $sql .= $layer->tolerance.", ";
      $sql .= "'" . $layer->toleranceunits."', ";
      $sql .= "'" . $projection[1]."', ";               # epsg_code
      $sql .= "'', ";               # ows_srs
      $sql .= "'', ";               # wms_name
      $sql .= "'', ";               # wms_server_version
      $sql .= "'', ";               # wms_format
      $sql .= "60";                 # wms_connectiontimeout
      $sql .= "'" . $layer->trigger_function . "', ";
      $sql .= "'" . $layer->sync . "'";
      $sql .= ")";
    }

    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->newLayer - Erzeugen eines Layers:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql, $this->connection); return 0; }
    return mysql_insert_id();
  }

	function save_datatype_attributes($attributes, $database, $formvars){
		global $supportedLanguages;
		$language_columns = array();

		foreach ($supportedLanguages as $language){
			if ($language != 'german') {
				$language_columns[] = "`alias_" . $language . "` = '" . $formvars['alias_' . $language . '_' . $attributes['name'][$i]] . "'";
			}
		}
		$language_columns = (count($language_columns) > 0 ? implode(',
					', $language_columns) . ',' : '');
					
		for ($i = 0; $i < count($attributes['name']); $i++) {
			if($formvars['visible_' . $attributes['name'][$i]] != 2 OR $formvars['vcheck_value_'.$attributes['name'][$i]] == ''){
				$formvars['vcheck_attribute_'.$attributes['name'][$i]] = '';
				$formvars['vcheck_operator_'.$attributes['name'][$i]] = '';
				$formvars['vcheck_value_'.$attributes['name'][$i]] = '';
			}
			$sql = "
				INSERT INTO
					datatype_attributes
				SET
					`datatype_id` = " . $formvars['selected_datatype_id'] . ",
					" . $language_columns . "
					`name` = '" . $attributes['name'][$i] . "',
					`form_element_type` = '" . $formvars['form_element_' . $attributes['name'][$i]] . "',
					`options` = '" . $formvars['options_' . $attributes['name'][$i]] . "',
					`tooltip` = '" . $formvars['tooltip_' . $attributes['name'][$i]] . "',
					`alias` = '" . $formvars['alias_'.$attributes['name'][$i]] . "',
					`group` = '" . $formvars['group_' . $attributes['name'][$i]] . "',
					`raster_visibility` = " . ($formvars['raster_visibility_' . $attributes['name'][$i]] == '' ? "NULL" : $formvars['raster_visibility_' . $attributes['name'][$i]]) . ",
					`mandatory` = " . ($formvars['mandatory_' . $attributes['name'][$i]] == '' ? "NULL" : $formvars['mandatory_' . $attributes['name'][$i]]) . ",
					`quicksearch` = " . ($formvars['quicksearch_' . $attributes['name'][$i]] == '' ? "NULL" : $formvars['quicksearch_' . $attributes['name'][$i]]) . ",
					`visible` = " . ($formvars['visible_' . $attributes['name'][$i]] == '' ? "0" : $formvars['visible_' . $attributes['name'][$i]]) . ",
					`vcheck_attribute`= '" . $formvars['vcheck_attribute_'.$attributes['name'][$i]]."',
					`vcheck_operator`= '" . $formvars['vcheck_operator_'.$attributes['name'][$i]]."',
					`vcheck_value`= '" . $formvars['vcheck_value_'.$attributes['name'][$i]]."',
					`arrangement` = " . ($formvars['arrangement_' . $attributes['name'][$i]] == '' ? "0" : $formvars['arrangement_' . $attributes['name'][$i]]) . ",
					`labeling` = " . ($formvars['labeling_' . $attributes['name'][$i]] == '' ? "0" : $formvars['labeling_' . $attributes['name'][$i]]) . "
				ON DUPLICATE KEY UPDATE
					" . $language_columns . "
					`name` = '" . $attributes['name'][$i] . "',
					`form_element_type` = '" . $formvars['form_element_' . $attributes['name'][$i]] . "',
					`options` = '" . $formvars['options_' . $attributes['name'][$i]] . "',
					`tooltip` = '" . $formvars['tooltip_' . $attributes['name'][$i]] . "',
					`alias` = '" . $formvars['alias_'.$attributes['name'][$i]] . "',
					`group` = '" . $formvars['group_' . $attributes['name'][$i]] . "',
					`raster_visibility` = " . ($formvars['raster_visibility_' . $attributes['name'][$i]] == '' ? "NULL" : $formvars['raster_visibility_' . $attributes['name'][$i]]) . ",
					`mandatory` = " . ($formvars['mandatory_' . $attributes['name'][$i]] == '' ? "NULL" : $formvars['mandatory_' . $attributes['name'][$i]]) . ",
					`quicksearch` = " . ($formvars['quicksearch_' . $attributes['name'][$i]] == '' ? "NULL" : $formvars['quicksearch_' . $attributes['name'][$i]]) . ",
					`visible` = " . ($formvars['visible_' . $attributes['name'][$i]] == '' ? "0" : $formvars['visible_' . $attributes['name'][$i]]) . ",
					`vcheck_attribute`= '" . $formvars['vcheck_attribute_'.$attributes['name'][$i]]."',
					`vcheck_operator`= '" . $formvars['vcheck_operator_'.$attributes['name'][$i]]."',
					`vcheck_value`= '" . $formvars['vcheck_value_'.$attributes['name'][$i]]."',					
					`arrangement` = " . ($formvars['arrangement_' . $attributes['name'][$i]] == '' ? "0" : $formvars['arrangement_' . $attributes['name'][$i]]) . ",
					`labeling` = " . ($formvars['labeling_' . $attributes['name'][$i]] == '' ? "0" : $formvars['labeling_' . $attributes['name'][$i]]) . "
			";
			#echo '<br>Save datatype Sql: ' . $sql;
			$this->debug->write("<p>file:kvwmap class:Document->save_datatype_attributes :", 4);
			$ret = $database->execSQL($sql, 4, 1);
			if ($ret[0]) {
				$msg = 'Fehler beim Speichern der Datentypeinstellungen:<br>' . $ret[1];
				$this->debug->write('<p>file:kvwmap class:Document->save_datatype_attributes: ' . $msg, 4);
				$this->GUI->add_message('error', $msg);
			}
		}
	}

	/*
	* Speichert die Einstellungen, die in formvars enthalten sind für die Attribute, die in $attributes angegeben sind.
	* Formvars, die nicht zu Attributen in $attributes passen werden ignoriert.
	*/
	function save_layer_attributes($attributes, $database, $formvars){
		global $supportedLanguages;

		for ($i = 0; $i < count($attributes['name']); $i++) {
			if ($formvars['attribute_' . $attributes['name'][$i]] != '') {
				$alias_rows = "`alias` = '" . $formvars['alias_' . $attributes['name'][$i]] . "',";
				foreach ($supportedLanguages as $language) {
					if ($language != 'german') {
						$alias_rows .= "`alias_" . $language . "` = '" . $formvars['alias_' . $language . '_' . $attributes['name'][$i]] . "',";
					}
				}
				if ($formvars['visible_' . $attributes['name'][$i]] != 2){
					$formvars['vcheck_attribute_'.$attributes['name'][$i]] = '';
					$formvars['vcheck_operator_'.$attributes['name'][$i]] = '';
					$formvars['vcheck_value_'.$attributes['name'][$i]] = '';
				}
				if ($formvars['group_' . $attributes['name'][$i]] == '' AND $last_group != ''){
					$formvars['group_' . $attributes['name'][$i]] = $last_group;
				}
				$last_group = $formvars['group_' . $attributes['name'][$i]];
				$rows = "
					`order` = " . ($formvars['order_' . $attributes['name'][$i]] == '' ? 0 : $formvars['order_' . $attributes['name'][$i]]) . ",
					`name` = '" . $attributes['name'][$i] . "', " .
					$alias_rows . "
					`form_element_type` = '" . $formvars['form_element_' . $attributes['name'][$i]] . "',
					`options` = '" . pg_escape_string($formvars['options_' . $attributes['name'][$i]]) . "',
					`tooltip` = '" . $formvars['tooltip_' . $attributes['name'][$i]] . "',
					`group` = '" . $formvars['group_' . $attributes['name'][$i]] . "',
					`arrangement` = " . ($formvars['arrangement_' . $attributes['name'][$i]] == '' ? 0 : $formvars['arrangement_' . $attributes['name'][$i]]) . ",
					`labeling` = " . ($formvars['labeling_' . $attributes['name'][$i]] == '' ? 0 : $formvars['labeling_' . $attributes['name'][$i]]) . ",
					`raster_visibility` = " . ($formvars['raster_visibility_' . $attributes['name'][$i]] == '' ? "NULL" : $formvars['raster_visibility_' . $attributes['name'][$i]]) . ",
					`dont_use_for_new`= " . ($formvars['dont_use_for_new_' . $attributes['name'][$i]] == '' ? "NULL" : $formvars['dont_use_for_new_' . $attributes['name'][$i]]) . ",
					`mandatory` = " . ($formvars['mandatory_' . $attributes['name'][$i]] == '' ? "NULL" : $formvars['mandatory_' . $attributes['name'][$i]]) . ",
					`quicksearch`= " . ($formvars['quicksearch_' . $attributes['name'][$i]] == '' ? "NULL" : $formvars['quicksearch_' . $attributes['name'][$i]]) . ",
					`visible`= ".($formvars['visible_'.$attributes['name'][$i]] == '' ? "0" : $formvars['visible_'.$attributes['name'][$i]]).",
					`vcheck_attribute`= '" . $formvars['vcheck_attribute_'.$attributes['name'][$i]]."',
					`vcheck_operator`= '" . $formvars['vcheck_operator_'.$attributes['name'][$i]]."',
					`vcheck_value`= '" . $formvars['vcheck_value_'.$attributes['name'][$i]]."'
				";
				$sql = "
					INSERT INTO
						`layer_attributes`
					SET
						`layer_id` = " . $formvars['selected_layer_id'] . ", " .
						$rows . "
					ON DUPLICATE KEY UPDATE " .
						$rows . "
				";
				#echo '<br>Sql: ' . $sql;
				$this->debug->write("<p>file:kvwmap class:Document->save_layer_attributes :",4);
				$database->execSQL($sql, 4, 1);
			}
		}
	}

	function delete_layer_filterattributes($layer_id){
    $sql = 'DELETE FROM u_attributfilter2used_layer WHERE layer_id = '.$layer_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_layer_filterattributes:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
  }

  function delete_layer_attributes($layer_id){
    $sql = 'DELETE FROM layer_attributes WHERE layer_id = '.$layer_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_layer_attributes:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
  }

  function delete_layer_attributes2stelle($layer_id, $stelle_id){
    $sql = 'DELETE FROM layer_attributes2stelle WHERE layer_id = '.$layer_id.' AND stelle_id = '.$stelle_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_layer_attributes2stelle:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
  }

  function read_datatype_attributes($datatype_id, $datatypedb, $attributenames, $all_languages = false, $recursive = false){
		global $language;

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
			SELECT " .
				$alias_column . ", `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`,
				`datatype_id`,
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
				`raster_visibility`,
				`mandatory`,
				`quicksearch`,
				`order`,
				`privileg`,
				`query_tooltip`,
				`visible`,
				`vcheck_attribute`,
				`vcheck_operator`,
				`vcheck_value`,
				`arrangement`,
				`labeling`
			FROM
				`datatype_attributes` as a LEFT JOIN
				`datatypes` as d ON d.`id` = REPLACE(`type`, '_', '')
			WHERE
				`datatype_id` = " . $datatype_id .
				$einschr . "
			ORDER BY
				`order`
		";
		/* Attributes die fehlen im Vergleich zu layer_attributes
		`dont_use_for_new`
		*/
		#echo '<br>Sql read_datatype_attributes: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_datatype_attributes:<br>" . $sql,4);
		$query=mysql_query($sql);
    if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
		$i = 0;
		while($rs = mysql_fetch_array($query)){
			$attributes['datatype_id'][$i] = $rs['datatype_id'];
			$attributes['name'][$i] = $rs['name'];
			$attributes['indizes'][$rs['name']] = $i;
			$attributes['real_name'][$rs['name']]= $rs['real_name'];
			if($rs['tablename']){
				if(strpos($rs['tablename'], '.') !== false){
					$explosion = explode('.', $rs['tablename']);
					$rs['tablename'] = $explosion[1];		# Tabellenname ohne Schema
					$attributes['schema_name'][$rs['tablename']] = $explosion[0];
				}
				$attributes['table_name'][$i]= $rs['tablename'];
				$attributes['table_name'][$rs['name']] = $rs['tablename'];
			}
			if($rs['table_alias_name'])$attributes['table_alias_name'][$i]= $rs['table_alias_name'];
			if($rs['table_alias_name'])$attributes['table_alias_name'][$rs['name']]= $rs['table_alias_name'];
			$attributes['table_alias_name'][$rs['tablename']]= $rs['table_alias_name'];
			$attributes['type'][$i]= $rs['type'];
			$attributes['typename'][$i]= $rs['typename'];
			$type = ltrim($rs['type'], '_');
			if($recursive AND is_numeric($type)){
				$attributes['type_attributes'][$i] = $this->read_datatype_attributes($type, $layerdb, NULL, $all_languages, true);
			}
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
			if($datatypedb != NULL){
				if(substr($rs['default'], 0, 6) == 'SELECT'){					# da Defaultvalues auch dynamisch sein können (z.B. 'now'::date) wird der Defaultwert erst hier ermittelt
					$ret1 = $datatypedb->execSQL($rs['default'], 4, 0);
					if($ret1[0]==0){
						$attributes['default'][$i] = array_pop(pg_fetch_row($ret1[1]));
					}
				}
				else{															# das sind die alten Defaultwerte ohne 'SELECT ' davor, ab Version 1.13 haben Defaultwerte immer ein SELECT, wenn man den datatype in dieser Version einmal gespeichert hat
					$attributes['default'][$i]= $rs['default'];
				}
			}
			$attributes['form_element_type'][$i]= $rs['form_element_type'];
			$attributes['form_element_type'][$rs['name']]= $rs['form_element_type'];
			$rs['options'] = str_replace('$hist_timestamp', rolle::$hist_timestamp, $rs['options']);
			$rs['options'] = str_replace('$language', $this->user->rolle->language, $rs['options']);
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
			$attributes['arrangement'][$i]= $rs['arrangement'];
			$attributes['labeling'][$i]= $rs['labeling'];
			$attributes['raster_visibility'][$i]= $rs['raster_visibility'];
			$attributes['mandatory'][$i]= $rs['mandatory'];
			$attributes['quicksearch'][$i]= $rs['quicksearch'];
			$attributes['privileg'][$i]= $rs['privileg'];
			$attributes['query_tooltip'][$i]= $rs['query_tooltip'];
			$attributes['visible'][$i]= $rs['visible'];
			$attributes['vcheck_attribute'][$i] = $rs['vcheck_attribute'];
			$attributes['vcheck_operator'][$i] = $rs['vcheck_operator'];
			$attributes['vcheck_value'][$i] = $rs['vcheck_value'];
			$attributes['dependents'][$i] = &$dependents[$rs['name']];
			$dependents[$rs['vcheck_attribute']][] = $rs['name'];
			$attributes['arrangement'][$i]= $rs['arrangement'];
			$attributes['labeling'][$i]= $rs['labeling'];
			$i++;
		}
		return $attributes;
  }

  function read_layer_attributes($layer_id, $layerdb, $attributenames, $all_languages = false, $recursive = false){
		global $language;

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
		$query=mysql_query($sql);
    if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
		$i = 0;
		while ($rs = mysql_fetch_array($query)){
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
					$attributes['default'][$i] = array_pop(pg_fetch_row($ret1[1]));
				}
			}
			else {															# das sind die alten Defaultwerte ohne 'SELECT ' davor, ab Version 1.13 haben Defaultwerte immer ein SELECT, wenn man den Layer in dieser Version einmal gespeichert hat
				$attributes['default'][$i] = $rs['default'];
			}
			$attributes['form_element_type'][$i] = $rs['form_element_type'];
			$attributes['form_element_type'][$rs['name']] = $rs['form_element_type'];
			$rs['options'] = str_replace('$hist_timestamp', rolle::$hist_timestamp, $rs['options']);
			$rs['options'] = str_replace('$language', $this->user->rolle->language, $rs['options']);
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
		if ($attributes['table_name'] != NULL) {
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

	/*
	* Returns a list of datatypes used by layer, given in layer_ids array
	*/
	function get_datatypes($layer_ids) {
		$datatypes = array();
		$sql = "
			SELECT DISTINCT
				dt.*
			FROM
				`layer_attributes` la JOIN
				`datatypes` dt ON replace(la.type,'_', '') = dt.id
			WHERE
				la.layer_id IN (" . implode(', ', $layer_ids) . ")
		";

		$this->debug->write("<p>file:kvwmap class:db_mapObj->get_datatypes - Lesen der Datentypen der Layer mit id (" . implode(', ', $layer_ids) . "):<br>" . $sql , 4);
		$query = mysql_query($sql);
		if ($query == 0) {
			$this->GUI->add_message('error', err_msg($PHP_SELF, __LINE__, $sql));
			return 0;
		}
		while ($rs = mysql_fetch_assoc($query)) {
			$datatypes[] = $rs;
		}
		return $datatypes;
	}

	function getall_Datatypes($order) {
		$datatypes = array();
		$order_sql = ($order != '') ? "ORDER BY " . replace_semicolon($order) : '';
		$sql = "
			SELECT
				*
			FROM
				datatypes
			" . $order_sql;

		$this->debug->write("<p>file:kvwmap class:db_mapObj->getall_Datatypes - Lesen aller Datentypen:<br>" . $sql , 4);
		$query = mysql_query($sql);
    if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
		while($rs = mysql_fetch_assoc($query)) {
			/*
			foreach($rs AS $key => $value) {
				$datatypes[$key][] = $value;
			}
			*/
			$datatypes[] = $rs;
		}
		return $datatypes;
	}

	function getall_Layer($order, $only_listed = false, $user_id = NULL, $stelle_id = NULL) {
		global $language;
		global $admin_stellen;
		$more_from = '';
		$where = array();

		if ($language != 'german') {
			$name_column = "
			CASE
				WHEN l.`name_" . $language . "` != \"\" THEN l.`name_" . $language . "`
				ELSE l.`name`
			END";
		}
		else {
			$name_column = "l.`name`";
		}

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

		if ($only_listed) {
			$where[] = "listed = 1";
		}
		
		if ($user_id != NULL AND !in_array($stelle_id, $admin_stellen)) {
			$more_from = "
				LEFT JOIN used_layer ul ON l.Layer_ID = ul.Layer_id
				LEFT JOIN rolle rall ON ul.Stelle_ID = rall.stelle_id
				LEFT JOIN rolle radm ON rall.stelle_id = radm.stelle_id
			";
			$where[] = "(radm.user_id = ".$this->User_ID." OR ul.Layer_id IS NULL)";
		}

		if ($order != '') {
			$order = " ORDER BY " . replace_semicolon($order);
		}

		$sql = "
			SELECT DISTINCT " .
				$name_column . " AS Name," .
				$gruppenname_column . " AS Gruppenname,
				l.Layer_ID,
				l.Gruppe,
				l.kurzbeschreibung,
				l.datenherr,
				l.alias
			FROM
				layer l JOIN
				u_groups g ON l.Gruppe = g.id" .
				$more_from .
			(count($where) > 0 ? " WHERE " . implode(' AND ', $where) : "") .
			$order . "
		";
		#echo '<br>sql: ' . $sql;

		/*
		$sql ='SELECT ';
		if($language != 'german') {
			$sql.='CASE WHEN `Name_'.$language.'` != "" THEN `Name_'.$language.'` ELSE `Name` END AS ';
		}
		$sql.='Name, Layer_ID, Gruppe, kurzbeschreibung, datenherr, alias, ';
		if($language != 'german') {
			$sql.='CASE WHEN `Gruppenname_'.$language.'` != "" THEN `Gruppenname_'.$language.'` ELSE `Gruppenname` END AS ';
		}
		$sql.='Gruppenname FROM layer, u_groups';
		$sql.=' WHERE layer.Gruppe = u_groups.id';
		if($only_listed)$sql.=' AND listed=1';
		if($order != ''){$sql .= ' ORDER BY ' . replace_semicolon($order);}
*/

		$this->debug->write("<p>file:kvwmap class:db_mapObj->getall_Layer - Lesen aller Layer:<br>" . $sql,4);
		$query=mysql_query($sql);
    if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
		$i = 0;
		while($rs=mysql_fetch_array($query)){
			$layer['ID'][]=$rs['Layer_ID'];
			$layer['Bezeichnung'][]=$rs['Name'];
			$layer['Gruppe'][]=$rs['Gruppenname'];
			$layer['GruppeID'][]=$rs['Gruppe'];
			$layer['Kurzbeschreibung'][]=$rs['kurzbeschreibung'];
			$layer['Datenherr'][]=$rs['datenherr'];
			$layer['alias'][]=$rs['alias'];
			$layer['layers_of_group'][$rs['Gruppe']][] = $i;
			$i++;
		}
		if($order == 'Bezeichnung'){
			// Sortieren der Layer unter Berücksichtigung von Umlauten
			$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
			$layer['ID'] = $sorted_arrays['second_array'];
			$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['GruppeID']);
			$layer['Bezeichnung'] = $sorted_arrays['array'];
			$layer['GruppeID'] = $sorted_arrays['second_array'];
		}
		return $layer;
	}

	function get_all_layer_params() {
		$layer_params = array();
		$sql = "SELECT * FROM layer_parameter";
		$params_result = mysql_query($sql);
		if($params_result==0) {
			echo '<br>Fehler bei der Abfrage der Layerparameter mit SQL: ' . $sql;
		}
		else{
			while($rs = mysql_fetch_assoc($params_result)){
				$params[] = $rs;
			}
		}
		return $params;
	}

	function save_all_layer_params($formvars) {
		$sql = "TRUNCATE layer_parameter";
		$result = mysql_query($sql);
		$sql = "INSERT INTO layer_parameter VALUES ";
		for ($i = 0; $i < count($formvars['key']); $i++) {
			if ($formvars['key'][$i] != '') {
				if ($formvars['id'][$i] == '') {
					$formvars['id'][$i] = 'NULL';
				}
				if ($komma) {
					$sql .= ",";
				}
				$sql .= "(
					" . $formvars['id'][$i] . ",
					'" . $formvars['key'][$i] . "',
					'" . $formvars['alias'][$i] . "',
					'" . $formvars['default_value'][$i] . "',
					'" . mysql_real_escape_string($formvars['options_sql'][$i]) . "'
				)";
				$komma = true;
			}
		}
		$result = mysql_query($sql);
		if ($result==0) echo '<br>Fehler beim Speichern der Layerparameter mit SQL: ' . $sql;
	}

	function get_all_layer_params_default_values() {
		$layer_params = array();
		$sql = "
			SELECT
				GROUP_CONCAT(concat('\"', `key`, '\":\"', `default_value`, '\"')) AS params
			FROM
				layer_parameter p
		";
		$params_result = mysql_query($sql);
		if ($params_result == 0) {
			echo '<br>Fehler bei der Abfrage der Layerparameter mit SQL: ' . $sql;
		}
		else {
			$rs = mysql_fetch_assoc($params_result);
		}
		$params = $rs['params'];
		$params = str_replace('$user_id', $this->User_ID, $params);
		$params = str_replace('$stelle_id', $this->Stelle_ID, $params);
		return (array)json_decode('{' . $params . '}');
	}

  function get_stellen_from_layer($layer_id){
    $sql = "
			SELECT
				`ID`,
				`Bezeichnung`
			FROM
				`stelle`,
				`used_layer`
			WHERE
				`used_layer`.`Stelle_ID` = `stelle`.`ID` AND
				`used_layer`.`Layer_ID` = " . $layer_id . "
			ORDER BY
				`Bezeichnung`
		";
		#echo '<br>Sql: ' . $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_stellen_from_layer - Lesen der Stellen eines Layers:<br>" . $sql, 4);
    $query=mysql_query($sql);
    if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
    while ($rs = mysql_fetch_array($query)) {
      $stellen['ID'][] = $rs['ID'];
      $stellen['Bezeichnung'][] = $rs['Bezeichnung'];
    }
    return $stellen;
  }

  function get_layers_of_type($types, $order) {
		global $language;
		if($language != 'german') {
			$name_column = "
			CASE
				WHEN `Name_" . $language . "` != \"\" THEN `Name_" . $language . "`
				ELSE `Name`
			END AS Name";
		}
		else{
			$name_column = "Name";
		}
    $sql ='SELECT Layer_ID, '.$name_column.' FROM layer';
    $sql.=' WHERE connectiontype IN ('.$types.')';
    if($order != ''){$sql .= ' ORDER BY ' . replace_semicolon($order);}
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getall_Layer - Lesen aller Layer:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
    while($rs=mysql_fetch_array($query)) {
          $layer['ID'][]=$rs['Layer_ID'];
          $layer['Bezeichnung'][]=$rs['Name'];
      }
    if($order == 'Bezeichnung'){
      // Sortieren der Layer unter Berücksichtigung von Umlauten
      $sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
      $layer['Bezeichnung'] = $sorted_arrays['array'];
      $layer['ID'] = $sorted_arrays['second_array'];
    }
    return $layer;
  }

  function get_Layer($id, $replace_class_item = false) {
    $sql = "
			SELECT
				*
			FROM
				`layer`
			WHERE
				`Layer_ID` = " . $id ."
		";
		#echo '<br>Sql: ' . $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_Layer - Lesen eines Layers:<br>" . $sql, 4);
    $query = mysql_query($sql);
    if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
    $layer = mysql_fetch_array($query);
		if ($replace_class_item) {
			foreach (array('classitem', 'classification') AS $key) {
				$layer[$key] = replace_params(
					$layer[$key],
					rolle::$layer_params,
					$this->User_ID,
					$this->Stelle_ID,
					rolle::$hist_timestamp,
					$this->rolle->language
				);
			}
		}
    return $layer;
  }

	function set_default_layer_privileges($formvars, $attributes){
		for ($i = 0; $i < count($attributes['type']); $i++) {
			if ($formvars['privileg_'.$attributes['name'][$i].'_'] == '') $formvars['privileg_'.$attributes['name'][$i].'_'] = 'NULL';
			$sql = "
				UPDATE
					`layer_attributes`
				SET
					`privileg` = " . $formvars['privileg_' . $attributes['name'][$i].'_'] . ",
					`query_tooltip` = " . ($formvars['tooltip_' . $attributes['name'][$i].'_'] == 'on' ? "1" : "0") ."
				WHERE
					`layer_id` = " . $formvars['selected_layer_id'] . " AND
					`name` = '" . $attributes['name'][$i] . "'
			";
			#echo '<br>Sql: ' . $sql;
			$this->debug->write("<p>file:users.php class:stelle->set_default_layer_privileges - Speichern des Layerrechte zur Stelle:<br>" . $sql, 4);
			$query = mysql_query($sql);
			if ($query == 0) { $this->debug->write("<br>Abbruch in " . $PHP_SELF . " Zeile: " . __LINE__, 4); return 0; }

			$sql = "
				UPDATE
					`layer`
				SET
					`privileg` = '" . $formvars['privileg'] . "',
					`export_privileg` = '" . $formvars['export_privileg'] . "'
				WHERE
					`Layer_ID` = " . $formvars['selected_layer_id'] . "
			";
			#echo '<br>Sql: ' . $sql;
			$this->debug->write("<p>file:users.php class:stelle->set_default_layer_privileges - Speichern der Layerrechte zur Stelle:<br>" . $sql,4);
			$query=mysql_query($sql);
			if ($query==0) { $this->debug->write("<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
	}

	function get_layersfromgroup($group_id ) {
    $sql ='SELECT * FROM layer';
		if($group_id != '')$sql.=' WHERE Gruppe = '.$group_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_Layerfromgroup - Lesen der Layer einer Gruppe:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
		while($rs=mysql_fetch_array($query)) {
    	$layer['ID'][]=$rs['Layer_ID'];
      $layer['Bezeichnung'][]=$rs['Name'];
    }
    // Sortieren der Layer unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
    $layer['Bezeichnung'] = $sorted_arrays['array'];
    $layer['ID'] = $sorted_arrays['second_array'];
    return $layer;
  }

	function id_exists($tablename, $id) {
	  $layer = $this->get_Layer($id);
		if ($layer) {
		  return true;
		}
		else {
		  return false;
		}
	}

	function get_table_information($dbname, $tablename) {
		$sql = "SELECT * FROM information_schema.tables WHERE table_schema = '" . $dbname."' AND table_name = '" . $tablename."'";
		$this->debug->write("<p>file:kvwmap class:db_mapObj->get_table_information - Lesen der Metadaten der Tabelle " . $tablename." in db " . $dbname.":<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
    $metadata = mysql_fetch_array($query);
    return $metadata;
	}

  function get_used_Layer($id) {
    $sql ='SELECT * FROM used_layer WHERE Layer_ID = '.$id.' AND Stelle_ID = '.$this->Stelle_ID;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_used_Layer - Lesen eines Layers:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
    $layer = mysql_fetch_array($query);
    return $layer;
  }

  function newGroup($groupname, $order){
    $sql = 'INSERT INTO u_groups (Gruppenname, `order`) VALUES ("'.$groupname.'", '.$order.')';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->newGroup - Erstellen einer Gruppe:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
    return mysql_insert_id();
  }

  function get_Groups($layergruppen = NULL) {
		$this->groupset = $this->read_Groups(true, 'Gruppenname');
		if($layergruppen == NULL){	# alle abfragen
			$layergruppen['ID'] = array_unique(array_keys($this->groupset));
		}
		foreach($layergruppen['ID'] as $groupid){
			$uppergroupnames = $this->list_uppergroups($groupid);
			$layergruppen['Bezeichnung'][] = implode('->', array_reverse($uppergroupnames));;
		}
		// Sortieren der Gruppen unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($layergruppen['Bezeichnung'], $layergruppen['ID']);
    $layergruppen['Bezeichnung'] = $sorted_arrays['array'];
    $layergruppen['ID'] = $sorted_arrays['second_array'];
		return $layergruppen;
  }

	function list_uppergroups($groupid){
		if($groupid != ''){
			$lastgroupid = '';
			while($groupid != '' AND $lastgroupid != $groupid){
				$uppergroups[] = $this->groupset[$groupid]['Gruppenname'];
				$lastgroupid = $groupid;
				$groupid = $this->groupset[$groupid]['obergruppe'];
			}
			return $uppergroups;
		}
	}

  function getGroupbyName($groupname){
    $sql ="SELECT * FROM u_groups WHERE Gruppenname = '" . $groupname."'";
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getGroupbyName - Lesen einer Gruppe:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
    $rs=mysql_fetch_array($query);
    return $rs;
  }

  function getClassFromObject($select, $layer_id, $classitem){
    # diese Funktion bestimmt für ein über die oid gegebenes Objekt welche Klasse dieses Objekt hat
    $classes = $this->read_Classes($layer_id);
    $anzahl = count($classes);
    if($anzahl == 1){
      return $classes[0]['Class_ID'];
    }
    else{
      for($i = 0; $i < $anzahl; $i++){
        $exp = str_replace(array("'[", "]'", '[', ']'), '', $classes[$i]['Expression']);
        $exp = str_replace(' eq ', '=', $exp);
        $exp = str_replace(' ne ', '!=', $exp);

				# wenn im Data sowas wie "tabelle.attribut" vorkommt, soll das anstatt dem "attribut" aus der Expression verwendet werden
        //$attributes = explode(',', substr($select, 0, strpos(strtolower($select), ' from ')));
        $attributes = get_select_parts(substr($select, 0, strpos(strtolower($select), ' from ')));        
				if(substr($exp, 0, 1) == '('){
					$exp_parts = explode(' ', $exp);
					for($k = 0; $k < count($exp_parts); $k++){
						for($j = 0; $j < count($attributes); $j++){
							if($exp_parts[$k] != '' AND strpos(strtolower($attributes[$j]), '.'.$exp_parts[$k]) !== false){
								$exp_parts[$k] = str_replace('select ', '', strtolower($attributes[$j]));
							}
						}
					}
					$exp = implode(' ', $exp_parts);
				}
				elseif($classitem != ''){		# Classitem davor setzen
					if(substr($exp, 0, 1) != "'")$quote = "'";
					$exp = $classitem."::text = ".$quote.$exp.$quote;
				}	
				$sql = 'SELECT * FROM ('.$select.") as foo WHERE (" . $exp.")";
        $this->debug->write("<p>file:kvwmap class:db_mapObj->getClassFromObject - Lesen einer Klasse eines Objektes:<br>" . $sql,4);
        $query=pg_query($sql);
    		if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
        $count=pg_num_rows($query);
        if($count > 0){
          return $classes[$i]['Class_ID'];
        }
      }
    }
  }

	function copyStyle($style_id){
		$sql = "INSERT INTO styles (symbol,symbolname,size,color,backgroundcolor,outlinecolor,minsize,maxsize,angle,angleitem,antialias,width,minwidth,maxwidth,geomtransform) SELECT symbol,symbolname,size,color,backgroundcolor,outlinecolor,minsize,maxsize,angle,angleitem,antialias,width,minwidth,maxwidth,geomtransform FROM styles WHERE Style_ID = " . $style_id;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->copyStyle - Kopieren eines Styles:<br>" . $sql,4);
		$query=mysql_query($sql);
    if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
		return mysql_insert_id();
	}

	function copyLabel($label_id){
		$sql = "INSERT INTO labels (font,type,color,outlinecolor,shadowcolor,shadowsizex,shadowsizey,backgroundcolor,backgroundshadowcolor,backgroundshadowsizex,backgroundshadowsizey,size,minsize,maxsize,position,offsetx,offsety,angle,autoangle,buffer,antialias,minfeaturesize,maxfeaturesize,partials,wrap,the_force) SELECT font,type,color,outlinecolor,shadowcolor,shadowsizex,shadowsizey,backgroundcolor,backgroundshadowcolor,backgroundshadowsizex,backgroundshadowsizey,size,minsize,maxsize,position,offsetx,offsety,angle,autoangle,buffer,antialias,minfeaturesize,maxfeaturesize,partials,wrap,the_force FROM labels WHERE Label_ID = " . $label_id;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->copyLabel - Kopieren eines Labels:<br>" . $sql,4);
		$query=mysql_query($sql);
    if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
		return mysql_insert_id();
	}

  function copyClass($class_id, $layer_id){
    # diese Funktion kopiert eine Klasse mit Styles und Labels und gibt die ID der neuen Klasse zurück
    $class = $this->read_ClassesbyClassid($class_id);
    $sql = "INSERT INTO classes (Name, `Name_low-german`, Name_english, Name_polish, Name_vietnamese, Layer_ID,Expression,classification,drawingorder,text) SELECT Name, `Name_low-german`, Name_english, Name_polish, Name_vietnamese, " . $layer_id.",Expression,classification,drawingorder,text FROM classes WHERE Class_ID = " . $class_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->copyClass - Kopieren einer Klasse:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
    $new_class_id = mysql_insert_id();
    for($i = 0; $i < count($class[0]['Style']); $i++){
      $new_style_id = $this->copyStyle($class[0]['Style'][$i]['Style_ID']);
      $this->addStyle2Class($new_class_id, $new_style_id, $class[0]['Style'][$i]['drawingorder']);
    }
    for($i = 0; $i < count($class[0]['Label']); $i++){
			$new_label_id = $this->copyLabel($class[0]['Label'][$i]['Label_ID']);
      $this->addLabel2Class($new_class_id, $new_label_id);
    }
    return $new_class_id;
  }

	function new_Class($classdata) {
		global $supportedLanguages;
		if (is_array($classdata)) {
			$attrib = $classdata; # Attributarray wurde übergeben
			if ($attrib['legendimagewidth'] == '') $attrib['legendimagewidth'] = 'NULL';
			if ($attrib['legendimageheight'] == '') $attrib['legendimageheight'] = 'NULL';
			if ($attrib['legendorder'] == '') $attrib['legendorder'] = 'NULL';
			# attrib:(Name, Layer_ID, Expression, classification, legendgraphic, legendimagewidth, legendimageheight, drawingorder, legendorder)
			$sql = 'INSERT INTO classes (Name, ';
			foreach ($supportedLanguages as $language) {
				if ($language != 'german') {
					$sql.= '`Name_'.$language.'`, ';
				}
			}
			$sql .= 'Layer_ID, Expression, classification, legendgraphic, legendimagewidth, legendimageheight, drawingorder, legendorder) VALUES ("' . $attrib['name'] . '",';
			foreach ($supportedLanguages as $language) {
				if ($language != 'german'){
					$sql .= '"' . $attrib['name_' . $language] . '",';
				}
			}
			$sql .= $attrib['layer_id'] . ', "' . $attrib['expression'] . '", "' . $attrib['classification'] . '", "' . $attrib['legendgraphic'] . '", ' . $attrib['legendimagewidth'] . ', ' . $attrib['legendimageheight'] . ', "' . $attrib['order'] . '", ' . $attrib['legendorder'] . ')';
		}
		else {
			$class = $classdata; # Classobjekt wurde übergeben
			if (MAPSERVERVERSION > 500) {
				$expression = $class->getExpressionString();
			}
			else {
				$expression = $class->getExpression();
			}
			$sql  = "
				INSERT INTO classes (
					Name,
					Layer_ID,
					Expression,
					classification,
					drawingorder
				) VALUES (
					'" . $class->name . "',
					" . $class->layer_id . ",
					'" . $expression . "',
					'" . $class->classification . "',
					'" . $class->drawingorder . "'
				)
			";
		}
		#echo $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->new_Class - Erstellen einer Klasse zu einem Layer:<br>" . $sql, 4);
		$query=mysql_query($sql);
		if ($this->database->logfile != NULL) $this->database->logfile->write($sql . ';');
    if ($query==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
		return mysql_insert_id();
	}

  function delete_Class($class_id){
    $sql = 'DELETE FROM classes WHERE Class_ID = '.$class_id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_Class - Löschen einer Klasse:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }

    # Einträge in u_styles2classes und evtl. die Styles mitlöschen
    $styles = $this->read_Styles($class_id);
    for($i = 0; $i < count($styles); $i++){
    	$this->removeStyle2Class($class_id, $styles[$i]['style_id']);
      $other_classes = $this->get_classes2style($styles[$i]['style_id']);
      if($other_classes == NULL){
      	$this->delete_Style($styles[$i]['style_id']);
      }
    }
    # Einträge in u_labels2classes und evtl. die Labels mitlöschen
    $labels = $this->read_Label($class_id);
    for($i = 0; $i < count($labels); $i++){
    	$this->removeLabel2Class($class_id, $labels[$i]['label_id']);
    	$other_classes = $this->get_classes2label($labels[$i]['label_id']);
    	if($other_classes == NULL){
      	$this->delete_Label($labels[$i]['label_id']);
    	}
    }
  }

	function update_Class($attrib) {
		global $supportedLanguages;
		if($attrib['legendimagewidth'] == '')$attrib['legendimagewidth'] = 'NULL';
		if($attrib['legendimageheight'] == '')$attrib['legendimageheight'] = 'NULL';
		if($attrib['order'] == '')$attrib['order'] = 'NULL';
		if($attrib['legendorder'] == '')$attrib['legendorder'] = 'NULL';
		$names = implode(
			', ',
			array_map(
				function($language) use ($attrib) {
					if($language != 'german')return "`Name_" . $language . "` = '" . $attrib['name_' . $language] . "'";
					else return "`Name` = '" . $attrib['name']."'";
				},
				$supportedLanguages
			)
		);

		$sql = '
			UPDATE
				classes
			SET
				`Class_ID` = ' . $attrib['new_class_id'] . ',
				'.$names.',
				`Layer_ID` = ' . $attrib['layer_id'] . ',
				`Expression` = "' . $attrib['expression'] . '",
				`text` = "' . $attrib['text'] . '",
				`classification` = "' . $attrib['classification'] . '",
				`legendgraphic`= "' . $attrib['legendgraphic'] . '",
				`legendimagewidth`= ' . $attrib['legendimagewidth'] . ',
				`legendimageheight`= ' . $attrib['legendimageheight'] . ',
				`drawingorder` = ' . $attrib['order'] . ',
				`legendorder` = '. $attrib['legendorder'] . '
			WHERE
				`Class_ID` = ' . $attrib['class_id'] . '
		';

		#echo $sql.'<br>';
		$this->debug->write("<p>file:kvwmap class:db_mapObj->update_Class - Aktualisieren einer Klasse:<br>" . $sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
	}

  function new_Style($style){
    if(is_array($style)){
      $sql = "INSERT INTO styles SET ";
			if($style['colorred'] != ''){$sql.= "color = '" . $style['colorred']." " . $style['colorgreen']." " . $style['colorblue']."'";}
      else $sql.= "color = '" . $style['color']."'";
      if($style['symbol']){$sql.= ", symbol = '" . $style['symbol']."'";}
      if($style['symbolname']){$sql.= ", symbolname = '" . $style['symbolname']."'";}
      if($style['size']){$sql.= ", size = '" . $style['size']."'";}
      if($style['backgroundcolor'] !== NULL){$sql.= ", backgroundcolor = '" . $style['backgroundcolor']."'";}
      if($style['backgroundcolorred'] !== NULL){$sql.= ", backgroundcolor = '" . $style['backgroundcolorred']." " . $style['backgroundcolorgreen']." " . $style['backgroundcolorblue']."'";}
      if($style['outlinecolor'] !== NULL){$sql.= ", outlinecolor = '" . $style['outlinecolor']."'";}
      if($style['outlinecolorred'] !== NULL){$sql.= ", outlinecolor = '" . $style['outlinecolorred']." " . $style['outlinecolorgreen']." " . $style['outlinecolorblue']."'";}
			if($style['colorrange'] !== NULL){$sql.= ", colorrange = '" . $style['colorrange']."'";}
			if($style['datarange'] !== NULL){$sql.= ", datarange = '" . $style['datarange']."'";}
			if($style['opacity'] !== NULL){$sql.= ", opacity = " . $style['opacity'];}
      if($style['minsize']){$sql.= ", minsize = '" . $style['minsize']."'";}
      if($style['maxsize']){$sql.= ", maxsize = '" . $style['maxsize']."'";}
      if($style['angle']){$sql.= ", angle = '" . $style['angle']."'";}
			if($style['angleitem']){$sql.= ", angleitem = '" . $style['angleitem']."'";}
			if($style['antialias']){$sql.= ", antialias = " . $style['antialias'];}
      if($style['width']){$sql.= ", width = '" . $style['width']."'";}
      if($style['minwidth']){$sql.= ", minwidth = '" . $style['minwidth']."'";}
      if($style['maxwidth']){$sql.= ", maxwidth = '" . $style['maxwidth']."'";}
			if($style['offsetx']){$sql.= ", offsetx = " . $style['offsetx'];}
			if($style['offsety']){$sql.= ", offsety = " . $style['offsety'];}
			if($style['polaroffset']){$sql.= ", polaroffset = '" . $style['polaroffset']."'";}
			if($style['pattern']){$sql.= ", pattern = '" . $style['pattern']."'";}
			if($style['geomtransform']){$sql.= ", geomtransform = '" . $style['geomtransform']."'";}
			if($style['gap']){$sql.= ", gap = " . $style['gap'];}
			if($style['initialgap']){$sql.= ", initialgap = " . $style['initialgap'];}
			if($style['linecap']){$sql.= ", linecap = '" . $style['linecap']."'";}
			if($style['linejoin']){$sql.= ", linejoin = '" . $style['linejoin']."'";}
			if($style['linejoinmaxsize']){$sql.= ", linejoinmaxsize = " . $style['linejoinmaxsize'];}
    }
    else{
    # Styleobjekt wird übergeben
      $sql = "INSERT INTO styles SET ";
      $sql.= "symbol = '" . $style->symbol."', ";
      $sql.= "symbolname = '" . $style->symbolname."', ";
      $sql.= "size = '" . $style->size."', ";
      $sql.= "color = '" . $style->color->red." " . $style->color->green." " . $style->color->blue."', ";
      $sql.= "backgroundcolor = '" . $style->backgroundcolor->red." " . $style->backgroundcolor->green." " . $style->backgroundcolor->blue."', ";
      $sql.= "outlinecolor = '" . $style->outlinecolor->red." " . $style->outlinecolor->green." " . $style->outlinecolor->blue."', ";
      $sql.= "minsize = '" . $style->minsize."', ";
      $sql.= "maxsize = '" . $style->maxsize."'";
    }
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->new_Style - Erzeugen eines Styles:<br>" . $sql,4);
    $query=mysql_query($sql);
		if($this->database->logfile != NULL)$this->database->logfile->write($sql.';');
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
    return mysql_insert_id();
  }

	function get_classes2style($style_id){
		$sql = 'SELECT class_id FROM u_styles2classes WHERE Style_ID = '.$style_id;
		#echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_classes2style - Abfragen der Klassen, die einen Style benutzen:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
    while($rs=mysql_fetch_array($query)) {
      $classes[]=$rs[0];
    }
    return $classes;
	}

  function delete_Style($style_id){
    $sql = 'DELETE FROM styles WHERE Style_ID = '.$style_id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_Style - Löschen eines Styles:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function moveup_Style($style_id, $class_id){
    $sql = 'SELECT * FROM u_styles2classes WHERE class_id = '.$class_id.' ORDER BY drawingorder';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
    $i = 0;
    while($rs=mysql_fetch_array($query)) {
      $styles[$i]=$rs;
      if($rs['style_id'] == $style_id){
        $index = $i;
      }
      $i++;
    }
    $sql = 'UPDATE u_styles2classes SET drawingorder = '.$styles[$index]['drawingorder'].' WHERE class_id = '.$class_id.' AND style_id = '.$styles[$index+1]['style_id'];
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
    $sql = 'UPDATE u_styles2classes SET drawingorder = '.$styles[$index+1]['drawingorder'].' WHERE class_id = '.$class_id.' AND style_id = '.$styles[$index]['style_id'];
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function movedown_Style($style_id, $class_id){
    $sql = 'SELECT * FROM u_styles2classes WHERE class_id = '.$class_id.' ORDER BY drawingorder';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
    $i = 0;
    while($rs=mysql_fetch_array($query)) {
      $styles[$i]=$rs;
      if($rs['style_id'] == $style_id){
        $index = $i;
      }
      $i++;
    }
    $sql = 'UPDATE u_styles2classes SET drawingorder = '.$styles[$index]['drawingorder'].' WHERE class_id = '.$class_id.' AND style_id = '.$styles[$index-1]['style_id'];
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
    $sql = 'UPDATE u_styles2classes SET drawingorder = '.$styles[$index-1]['drawingorder'].' WHERE class_id = '.$class_id.' AND style_id = '.$styles[$index]['style_id'];
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function delete_Label($label_id){
    $sql = 'DELETE FROM labels WHERE Label_ID = '.$label_id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_Label - Löschen eines Labels:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function addStyle2Class($class_id, $style_id, $drawingorder){
    if($drawingorder == NULL){
      $sql = 'SELECT MAX(drawingorder) FROM u_styles2classes WHERE class_id = '.$class_id;
      $this->debug->write("<p>file:kvwmap class:db_mapObj->addStyle2Class :<br>" . $sql,4);
      $query=mysql_query($sql);
      if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
      $rs = mysql_fetch_array($query);
      $drawingorder = $rs[0]+1;
    }
    $sql = 'INSERT INTO u_styles2classes VALUES ('.$class_id.', '.$style_id.', "'.$drawingorder.'")';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->addStyle2Class - Hinzufügen eines Styles zu einer Klasse:<br>" . $sql,4);
    $query=mysql_query($sql);
		if($this->database->logfile != NULL)$this->database->logfile->write($sql.';');
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function removeStyle2Class($class_id, $style_id){
    $sql = 'DELETE FROM u_styles2classes WHERE class_id = '.$class_id.' AND style_id = '.$style_id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->removeStyle2Class - Löschen eines Styles:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function save_Style($formvars){
  	# wenn der Style nicht der Klasse zugeordnet ist, zuordnen
  	$classes = $this->get_classes2style($formvars["style_id"]);
  	if(!in_array($formvars["class_id"], $classes))$this->addStyle2Class($formvars["class_id"], $formvars["style_id"], NULL);
    $sql ="UPDATE styles SET ";
    if($formvars["style_symbol"]){$sql.="symbol = '" . $formvars["style_symbol"]."',";}else{$sql.="symbol = NULL,";}
    $sql.="symbolname = '" . $formvars["style_symbolname"]."',";
    if($formvars["style_size"] != ''){$sql.="size = '" . $formvars["style_size"]."',";}else{$sql.="size = NULL,";}
    if($formvars["style_color"] != ''){$sql.="color = '" . $formvars["style_color"]."',";}else{$sql.="color = NULL,";}
    if($formvars["style_backgroundcolor"] != ''){$sql.="backgroundcolor = '" . $formvars["style_backgroundcolor"]."',";}else{$sql.="backgroundcolor = NULL,";}
    if($formvars["style_outlinecolor"] != ''){$sql.="outlinecolor = '" . $formvars["style_outlinecolor"]."',";}else{$sql.="outlinecolor = NULL,";}
		if($formvars["style_colorrange"] != ''){$sql.="colorrange = '" . $formvars["style_colorrange"]."',";}else{$sql.="colorrange = NULL,";}
		if($formvars["style_datarange"] != ''){$sql.="datarange = '" . $formvars["style_datarange"]."',";}else{$sql.="datarange = NULL,";}
		if($formvars["style_rangeitem"] != ''){$sql.="rangeitem = '" . $formvars["style_rangeitem"]."',";}else{$sql.="rangeitem = NULL,";}
    if($formvars["style_minsize"] != ''){$sql.="minsize = '" . $formvars["style_minsize"]."',";}else{$sql.="minsize = NULL,";}
    if($formvars["style_maxsize"] != ''){$sql.="maxsize = '" . $formvars["style_maxsize"]."',";}else{$sql.="maxsize = NULL,";}
		if($formvars["style_minscale"] != ''){$sql.="minscale = '" . $formvars["style_minscale"]."',";}else{$sql.="minscale = NULL,";}
    if($formvars["style_maxscale"] != ''){$sql.="maxscale = '" . $formvars["style_maxscale"]."',";}else{$sql.="maxscale = NULL,";}
    if($formvars["style_angle"] != ''){$sql.="angle = '" . $formvars["style_angle"]."',";}else{$sql.="angle = NULL,";}
    $sql.="angleitem = '" . $formvars["style_angleitem"]."',";
    if($formvars["style_antialias"] != ''){$sql.="antialias = '" . $formvars["style_antialias"]."',";}else{$sql.="antialias = NULL,";}
    if($formvars["style_width"] != ''){$sql.="width = '" . $formvars["style_width"]."',";}else{$sql.="width = NULL,";}
    if($formvars["style_minwidth"] != ''){$sql.="minwidth = '" . $formvars["style_minwidth"]."',";}else{$sql.="minwidth = NULL,";}
    if($formvars["style_maxwidth"] != ''){$sql.="maxwidth = '" . $formvars["style_maxwidth"]."',";}else{$sql.="maxwidth = NULL,";}
    if($formvars["style_offsetx"] != ''){$sql.="offsetx = '" . $formvars["style_offsetx"]."',";}else{$sql.="offsetx = NULL,";}
    if($formvars["style_offsety"] != ''){$sql.="offsety = '" . $formvars["style_offsety"]."',";}else{$sql.="offsety = NULL,";}
		if($formvars["style_polaroffset"] != ''){$sql.="polaroffset = '" . $formvars["style_polaroffset"]."',";}else{$sql.="polaroffset = NULL,";}
    if($formvars["style_pattern"] != ''){$sql.="pattern = '" . $formvars["style_pattern"]."',";}else{$sql.="pattern = NULL,";}
  	if($formvars["style_geomtransform"] != ''){$sql.="geomtransform = '" . $formvars["style_geomtransform"]."',";}else{$sql.="geomtransform = NULL,";}
		if($formvars["style_gap"] != ''){$sql.="gap = " . $formvars["style_gap"].",";}else{$sql.="gap = NULL,";}
		if($formvars["style_initialgap"] != ''){$sql.="initialgap = " . $formvars["style_initialgap"].",";}else{$sql.="initialgap = NULL,";}
		if($formvars["style_opacity"] != ''){$sql.="opacity = " . $formvars["style_opacity"].",";}else{$sql.="opacity = NULL,";}
		if($formvars["style_linecap"] != ''){$sql.="linecap = '" . $formvars["style_linecap"]."',";}else{$sql.="linecap = NULL,";}
		if($formvars["style_linejoin"] != ''){$sql.="linejoin = '" . $formvars["style_linejoin"]."',";}else{$sql.="linejoin = NULL,";}
		if($formvars["style_linejoinmaxsize"] != ''){$sql.="linejoinmaxsize = " . $formvars["style_linejoinmaxsize"].",";}else{$sql.="linejoinmaxsize = NULL,";}
    $sql.="Style_ID = " . $formvars["style_Style_ID"];
    $sql.=" WHERE Style_ID = " . $formvars["style_id"];
		#echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->save_Style - Speichern der Styledaten:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function get_Style($style_id){
  	if($style_id){
	    $sql ='SELECT * FROM styles AS s';
	    $sql.=' WHERE s.Style_ID = '.$style_id;
	    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_Style - Lesen der Styledaten:<br>" . $sql,4);
	    $query=mysql_query($sql);
	    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
	    $rs=mysql_fetch_assoc($query);
	    return $rs;
  	}
  }

  function save_Label($formvars){
    $sql ="UPDATE labels SET ";
    if($formvars["label_font"]){$sql.="font = '" . $formvars["label_font"]."',";}
    if($formvars["label_type"]){$sql.="type = '" . $formvars["label_type"]."',";}
		if($formvars["label_type"]){$sql.="type = '".$formvars["label_type"]."',";}else{$sql.="type = NULL,";}
    if($formvars["label_color"]){$sql.="color = '" . $formvars["label_color"]."',";}
    if($formvars["label_outlinecolor"] != ''){$sql.="outlinecolor = '" . $formvars["label_outlinecolor"]."',";}else{$sql.="outlinecolor = NULL,";}
    if($formvars["label_shadowcolor"] != ''){$sql.="shadowcolor = '" . $formvars["label_shadowcolor"]."',";}else{$sql.="shadowcolor = NULL,";}
    if($formvars["label_shadowsizex"] != ''){$sql.="shadowsizex = '" . $formvars["label_shadowsizex"]."',";}else{$sql.="shadowsizex = NULL,";}
    if($formvars["label_shadowsizey"] != ''){$sql.="shadowsizey = '" . $formvars["label_shadowsizey"]."',";}else{$sql.="shadowsizey = NULL,";}
    if($formvars["label_backgroundcolor"] != ''){$sql.="backgroundcolor = '" . $formvars["label_backgroundcolor"]."',";}else{$sql.="backgroundcolor = NULL,";}
    if($formvars["label_backgroundshadowcolor"] != ''){$sql.="backgroundshadowcolor = '" . $formvars["label_backgroundshadowcolor"]."',";}else{$sql.="backgroundshadowcolor = NULL,";}
    if($formvars["label_backgroundshadowsizex"] != ''){$sql.="backgroundshadowsizex = '" . $formvars["label_backgroundshadowsizex"]."',";}else{$sql.="backgroundshadowsizex = NULL,";}
    if($formvars["label_backgroundshadowsizey"] != ''){$sql.="backgroundshadowsizey = '" . $formvars["label_backgroundshadowsizey"]."',";}else{$sql.="backgroundshadowsizey = NULL,";}
    if($formvars["label_size"]){$sql.="size = '" . $formvars["label_size"]."',";}
    if($formvars["label_minsize"]){$sql.="minsize = '" . $formvars["label_minsize"]."',";}
    if($formvars["label_maxsize"]){$sql.="maxsize = '" . $formvars["label_maxsize"]."',";}
    if($formvars["label_position"]){$sql.="position = '" . $formvars["label_position"]."',";}
    if($formvars["label_offsetx"] != ''){$sql.="offsetx = '" . $formvars["label_offsetx"]."',";}else{$sql.="offsetx = NULL,";}
    if($formvars["label_offsety"] != ''){$sql.="offsety = '" . $formvars["label_offsety"]."',";}else{$sql.="offsety = NULL,";}
    if($formvars["label_angle"] != ''){$sql.="angle = '" . $formvars["label_angle"]."',";}else{$sql.="angle = NULL,";}
    if($formvars["label_autoangle"]){$sql.="autoangle = '" . $formvars["label_autoangle"]."',";}else $sql.="autoangle = NULL,";
    if($formvars["label_buffer"]){$sql.="buffer = '" . $formvars["label_buffer"]."',";}
    if($formvars["label_antialias"] != ''){$sql.="antialias = '" . $formvars["label_antialias"]."',";}else{$sql.="antialias = NULL,";}
    if($formvars["label_minfeaturesize"]){$sql.="minfeaturesize = '" . $formvars["label_minfeaturesize"]."',";}
    if($formvars["label_maxfeaturesize"]){$sql.="maxfeaturesize = '" . $formvars["label_maxfeaturesize"]."',";}
    if($formvars["label_partials"] != ''){$sql.="partials = '" . $formvars["label_partials"]."',";}
		if($formvars["label_maxlength"] != ''){$sql.="maxlength = '" . $formvars["label_maxlength"]."',";}
    if($formvars["label_wrap"] != ''){$sql.="wrap = '" . $formvars["label_wrap"]."',";}
    if($formvars["label_the_force"] != ''){$sql.="the_force = '" . $formvars["label_the_force"]."',";}
    $sql.="Label_ID = " . $formvars["label_Label_ID"];
    $sql.=" WHERE Label_ID = " . $formvars["label_id"];
    $this->debug->write("<p>file:kvwmap class:db_mapObj->save_Label - Speichern der Labeldaten:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function get_Label($label_id) {
    $sql ='SELECT * FROM labels AS l';
    $sql.=' WHERE l.Label_ID = '.$label_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_Label - Lesen der Labeldaten:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
    $rs=mysql_fetch_assoc($query);
    return $rs;
  }

  function new_Label($label){
  	if(is_array($label)){
  	$sql = "INSERT INTO labels SET ";
	    if($label[type]){$sql.= "type = '" . $label[type]."', ";}
	    if($label[font]){$sql.= "font = '" . $label[font]."', ";}
	    if($label[size]){$sql.= "size = '" . $label[size]."', ";}
	    if($label[color]){$sql.= "color = '" . $label[color]."', ";}
	    if($label[shadowcolor]){$sql.= "shadowcolor = '" . $label[shadowcolor]."', ";}
	    if($label[shadowsizex]){$sql.= "shadowsizex = '" . $label[shadowsizex]."', ";}
	    if($label[shadowsizey]){$sql.= "shadowsizey = '" . $label[shadowsizey]."', ";}
	    if($label[backgroundcolor]){$sql.= "backgroundcolor = '" . $label[backgroundcolor]."', ";}
	    if($label[backgroundshadowcolor]){$sql.= "backgroundshadowcolor = '" . $label[backgroundshadowcolor]."', ";}
	    if($label[backgroundshadowsizex]){$sql.= "backgroundshadowsizex = '" . $label[backgroundshadowsizex]."', ";}
	    if($label[backgroundshadowsizey]){$sql.= "backgroundshadowsizey = '" . $label[backgroundshadowsizey]."', ";}
	    if($label[outlinecolor]){$sql.= "outlinecolor = '" . $label[outlinecolor]."', ";}
	    if($label[position]){$sql.= "position = '" . $label[position]."', ";}
	    if($label[offsetx]){$sql.= "offsetx = '" . $label[offsetx]."', ";}
	    if($label[offsety]){$sql.= "offsety = '" . $label[offsety]."', ";}
	    if($label[angle]){$sql.= "angle = '" . $label[angle]."', ";}
	    if($label[autoangle]){$sql.= "autoangle = '" . $label[autoangle]."', ";}
	    if($label[buffer]){$sql.= "buffer = '" . $label[buffer]."', ";}
	    if($label[antialias]){$sql.= "antialias = '" . $label[antialias]."', ";}
	    if($label[minfeaturesize]){$sql.= "minfeaturesize = '" . $label[minfeaturesize]."', ";}
	    if($label[maxfeaturesize]){$sql.= "maxfeaturesize = '" . $label[maxfeaturesize]."', ";}
	    if($label[partials]){$sql.= "partials = '" . $label[partials]."', ";}
	    if($label[wrap]){$sql.= "wrap = '" . $label[wrap]."', ";}
	    if($label[the_force]){$sql.= "the_force = '" . $label[the_force]."', ";}
	    if($label[minsize]){$sql.= "minsize = '" . $label[minsize]."', ";}
	    if($label[maxsize]){$sql.= "maxsize = '" . $label[maxsize]."'";}
  	}
  	else{
	    # labelobjekt wird übergeben
	    $sql = "INSERT INTO labels SET ";
	    if($label->type){$sql.= "type = '" . $label->type."', ";}
	    if($label->font){$sql.= "font = '" . $label->font."', ";}
	    if($label->size){$sql.= "size = '" . $label->size."', ";}
	    if($label->color){$sql.= "color = '" . $label->color->red." " . $label->color->green." " . $label->color->blue."', ";}
	    if($label->shadowcolor){$sql.= "shadowcolor = '" . $label->shadowcolor->red." " . $label->shadowcolor->green." " . $label->shadowcolor->blue."', ";}
	    if($label->shadowsizex){$sql.= "shadowsizex = '" . $label->shadowsizex."', ";}
	    if($label->shadowsizey){$sql.= "shadowsizey = '" . $label->shadowsizey."', ";}
	    if($label->backgroundcolor){$sql.= "backgroundcolor = '" . $label->backgroundcolor->red." " . $label->backgroundcolor->green." " . $label->backgroundcolor->blue."', ";}
	    if($label->backgroundshadowcolor){$sql.= "backgroundshadowcolor = '" . $label->backgroundshadowcolor->red." " . $label->backgroundshadowcolor->green." " . $label->backgroundshadowcolor->blue."', ";}
	    if($label->backgroundshadowsizex){$sql.= "backgroundshadowsizex = '" . $label->backgroundshadowsizex."', ";}
	    if($label->backgroundshadowsizey){$sql.= "backgroundshadowsizey = '" . $label->backgroundshadowsizey."', ";}
	    if($label->outlinecolor){$sql.= "outlinecolor = '" . $label->outlinecolor->red." " . $label->outlinecolor->green." " . $label->outlinecolor->blue."', ";}
	    if($label->position !== NULL){$sql.= "position = '" . $label->position."', ";}
	    if($label->offsetx){$sql.= "offsetx = '" . $label->offsetx."', ";}
	    if($label->offsety){$sql.= "offsety = '" . $label->offsety."', ";}
	    if($label->angle){$sql.= "angle = '" . $label->angle."', ";}
	    if($label->autoangle){$sql.= "autoangle = '" . $label->autoangle."', ";}
	    if($label->buffer){$sql.= "buffer = '" . $label->buffer."', ";}
	    if($label->antialias){$sql.= "antialias = '" . $label->antialias."', ";}
	    if($label->minfeaturesize){$sql.= "minfeaturesize = '" . $label->minfeaturesize."', ";}
	    if($label->maxfeaturesize){$sql.= "maxfeaturesize = '" . $label->maxfeaturesize."', ";}
	    if($label->partials){$sql.= "partials = '" . $label->partials."', ";}
	    if($label->wrap){$sql.= "wrap = '" . $label->wrap."', ";}
	    if($label->the_force){$sql.= "the_force = '" . $label->the_force."', ";}
	    if($label->minsize){$sql.= "minsize = '" . $label->minsize."', ";}
	    if($label->maxsize){$sql.= "maxsize = '" . $label->maxsize."'";}
  	}
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->new_Style - Erzeugen eines Styles:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
    return mysql_insert_id();
  }

	function get_classes2label($label_id){
		$sql = 'SELECT class_id FROM u_labels2classes WHERE Label_ID = '.$label_id;
		#echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_classes2label - Abfragen der Klassen, die ein Label benutzen:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
    while($rs=mysql_fetch_array($query)) {
      $classes[]=$rs[0];
    }
    return $classes;
	}

  function addLabel2Class($class_id, $label_id){
    $sql = 'INSERT INTO u_labels2classes VALUES ('.$class_id.', '.$label_id.')';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->addLabel2Class - Hinzufügen eines Labels zu einer Klasse:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function removeLabel2Class($class_id, $label_id){
    $sql = 'DELETE FROM u_labels2classes WHERE class_id = '.$class_id.' AND label_id = '.$label_id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->removeLabels2Class - Löschen eines Labels:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function getShapeByAttribute($layer,$attribut,$value) {
    $layer->queryByAttributes($attribut,$value,0);
    $result=$layer->getResult(0);
    if ($layer->getNumResults()==0) {
      return 0;
    }
    else {
      $layer->open();
      if(MAPSERVERVERSION > 500){
        $shape=$layer->getFeature($result->shapeindex,-1);
      }
      else{
        $shape=$layer->getShape(-1,$result->shapeindex);
      }
    }
    return $shape;
  }

  function getMaxMapExtent() {
    $rect=ms_newRectObj();
    $sql ='SELECT MIN(minxmax) AS minxmax, MIN(minymax) AS minymax';
    $sql.=', MAX(maxxmax) AS maxxmax, MAX(maxymax) AS maxymax FROM stelle';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getMaxMapExtent - Lesen der Maximalen Kartenausdehnung:<br>" . $sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in " . $PHP_SELF." Zeile: ".__LINE__; return 0; }
    $rs=mysql_fetch_array($query);
    return $rs;
  }
}

?>