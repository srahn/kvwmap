<?php
#####################################
/* class_stelle
* functions
*	stelle($id, $database)
* getsubmenues($id)
* getName()
* readDefaultValues()
* checkClientIpIsOn()
* Löschen()
* deleteMenue(text)
* deleteLayer($layer, $pgdatabase)
* deleteDruckrahmen()
* deleteStelleGemeinden()
* deleteFunktionen()
* getstellendaten()
* NeueStelleAnlegen($stellendaten)
* Aendern($stellendaten)
* getStellen($order)
*/
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

	function stelle($id, $database) {
		global $debug;
		global $log_mysql;
		$this->debug = $debug;
		$this->log = $log_mysql;
		$this->id = $id;
		$this->database = $database;
		$this->Bezeichnung = $this->getName();
		$this->readDefaultValues();
	}

	function getsubmenues($id){
		$sql ='SELECT menue_id,';
		if ($this->language != 'german') {
			$sql.='`name_'.$this->language.'` AS ';
		}
		$sql .=' name, target, links FROM u_menue2stelle, u_menues';
		$sql .=' WHERE stelle_id = '.$this->id;
		$sql .=' AND obermenue = '.$id;
		$sql .=' AND menueebene = 2';
		$sql .=' AND u_menue2stelle.menue_id = u_menues.id';
		$sql .= ' ORDER BY menue_order';
		$this->debug->write("<p>file:stelle.php class:stelle->getsubMenues - Lesen der UnterMenuepunkte eines Menüpunktes:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=mysql_fetch_array($query)) {
				$menue['name'][]=$rs['name'];
				$menue['target'][]=$rs['target'];
				$menue['links'][]=$rs['links'];
			}
		}
		$html = '<table cellspacing="2" cellpadding="0" border="0">';
		for ($i = 0; $i < count($menue['name']); $i++) {
			$html .='
        <tr>
          <td> 
            <img src="'.GRAPHICSPATH.'leer.gif" width="17" height="1" border="0">
					</td>
					<td>
            <a href="';
			if ($menue['target'][$i]=='confirm') {
				$html .='javascript:Bestaetigung(\'';
			}
			$html .= $menue['links'][$i];
			if ($menue['target'][$i]=='confirm') {
				$html .= '\',\'Diese Aktion wirklich ausf&uuml;hren?\')';
				$menue['target'][$i]='';
			}
			$html .= '" class="menuered"';
			if ($menue['target'][$i]!='') {
				$html .= ' target="'.$menue['target'][$i].'"';
			}
			$html .= '>'.$menue['name'][$i].'</a>
          </td>
        </tr>';
		}
		$html .= '</table>';
		return $html;
	}
	
  function getName() {
    $sql ='SELECT ';
    if ($this->language != 'german' AND $this->language != ''){
      $sql.='`Bezeichnung_'.$this->language.'` AS ';
    }
    $sql.='Bezeichnung FROM stelle WHERE ID='.$this->id;
    #echo $sql;
    $this->debug->write("<p>file:stelle.php class:stelle->getName - Abfragen des Namens der Stelle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
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
    $this->debug->write("<p>file:stelle.php class:stelle->readDefaultValues - Abfragen der Default Parameter der Karte zur Stelle:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);    
    $this->MaxGeorefExt=ms_newRectObj();
    $this->MaxGeorefExt->setextent($rs['minxmax'],$rs['minymax'],$rs['maxxmax'],$rs['maxymax']);
    $this->epsg_code=$rs["epsg_code"];
    $this->alb_raumbezug=$rs["alb_raumbezug"];
    $this->alb_raumbezug_wert=$rs["alb_raumbezug_wert"];
    $this->pgdbhost = ($rs["pgdbhost"] == 'PGSQL_PORT_5432_TCP_ADDR') ? getenv('PGSQL_PORT_5432_TCP_ADDR') : $rs["pgdbhost"];
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
		$this->selectable_layer_params = $rs['selectable_layer_params'];
		$this->hist_timestamp=$rs["hist_timestamp"];
		$this->default_user_id = $rs['default_user_id'];
  }

  function checkClientIpIsOn() {
    $sql ='SELECT check_client_ip FROM stelle WHERE ID = '.$this->id;
    $this->debug->write("<p>file:stelle.php class:stelle->checkClientIpIsOn- Abfragen ob IP's der Nutzer in der Stelle getestet werden sollen<br>".$sql,4);
    #echo '<br>'.$sql;
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    if ($rs['check_client_ip']=='1') {
      return 1;
    }
    return 0;
  }
	
	function Löschen() {
		$sql ='DELETE FROM stelle';
		$sql.=' WHERE ID = '.$this->id;
		$ret=$this->database->execSQL($sql,4, 0);
		if ($ret[0]) {
			$ret[1].='<br>Die Stelle konnte nicht gelöscht werden.<br>'.$ret[1];
		}
		return $ret;
	}

	function deleteMenue($menue_ids) {
		$where_menue_id = ((is_array($menue_ids) and count($menue_ids) > 0) ? " AND `menue_id` IN (" . implode(", ", $menue_ids) . ")" : "");
		# Löschen der Zuordnung der Menüs zu der Stelle
		$sql = "
			DELETE FROM
				`u_menue2stelle`
			WHERE
				`stelle_id` = " . $this->id .
				$where_menue_id . "
		";
		#echo '<br>stelle.php deleteMenue(' . (is_array($menue_ids) ? implode(', ', $menue_ids) : $menue_ids) . ') Löschen der Menüpunkte der Stelle mit sql: ' . $sql . '!';
		$this->debug->write("<p>file:stelle.php class:stelle function:deleteMenue - Löschen der Menuepunkte der Stelle in menue2stelle:<br>" . $sql, 4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }

		# Löschen der Zuordnung der Menüs zu den Rollen der Stelle
		$sql = "
			DELETE FROM
				`u_menue2rolle`
			WHERE
				`stelle_id` = " . $this->id .
				$where_menue_id . "
		";
		#echo '<br>stelle.php deleteMenue (' . (is_array($menue_ids) ? implode(', ', $menue_ids) : $menue_ids) . 'Löschen der Menüpunkte der Rollen der Stellen sql: ' . $sql . '!';
		$this->debug->write("<p>file:stelle.php class:stelle function:deleteMenue - Löschen der Menuepunkte der Rollen der Stelle in menue2rolle:<br>" . $sql, 4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	function deleteLayer($layer, $pgdatabase) {
		#echo 'stelle.php deleteLayer ids: ' . implode(', ', $layer);
		if($layer == 0){
			# löscht alle Layer der Stelle
			$sql ='DELETE FROM `used_layer` WHERE `Stelle_ID` = '.$this->id;
			$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			$sql ='DELETE FROM `layer_attributes2stelle` WHERE `stelle_id` = '.$this->id;
			$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			# Filter löschen
			$sql ='SELECT attributvalue FROM `u_attributfilter2used_layer` WHERE `type` = \'geometry\' AND `Stelle_ID` = '.$this->id;
			$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			while($rs=mysql_fetch_row($query)){
				$poly_id = $rs[0];
				if($poly_id != '')$pgdatabase->deletepolygon($poly_id);
			}
			$sql ='DELETE FROM `u_attributfilter2used_layer` WHERE `Stelle_ID` = '.$this->id;
			$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		else{
			# löscht die übergebenen Layer der Stelle
			for ($i=0;$i<count($layer);$i++) {
				$sql ='DELETE FROM `used_layer` WHERE `Stelle_ID` = '.$this->id.' AND `Layer_ID` = '.$layer[$i];
				$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
				$query=mysql_query($sql,$this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
				$sql ='DELETE FROM `layer_attributes2stelle` WHERE `stelle_id` = '.$this->id.' AND `layer_id` = '.$layer[$i];
				$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
				$query=mysql_query($sql,$this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; 	}			
				# Filter löschen
				$sql ='SELECT attributvalue FROM `u_attributfilter2used_layer` WHERE `type` = \'geometry\' AND `Stelle_ID` = '.$this->id.' AND `Layer_ID` = '.$layer[$i];
				$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
				$query=mysql_query($sql,$this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
				$rs=mysql_fetch_row($query);
				$poly_id = $rs[0];
				if($poly_id != '')$pgdatabase->deletepolygon($poly_id);
				$sql ='DELETE FROM `u_attributfilter2used_layer` WHERE `Stelle_ID` = '.$this->id.' AND `Layer_ID` = '.$layer[$i];
				$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
				$query=mysql_query($sql,$this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			}
		}
		return 1;
	}
	
	function deleteDruckrahmen() {
		# löscht alle Druckrahmenzuordnungen der Stelle
		$sql ='DELETE FROM `druckrahmen2stelle` WHERE `stelle_id` = '.$this->id;
		#echo '<br>'.$sql;
		$this->debug->write("<p>file:stelle.php class:stelle function:deleteDruckrahmen - Löschen der Druckrahmen der Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}
	
	function deleteStelleGemeinden() {
		# löscht alle StelleGemeinden der Stelle
		$sql ='DELETE FROM `stelle_gemeinden` WHERE `Stelle_ID` = '.$this->id;
		#echo '<br>'.$sql;
		$this->debug->write("<p>file:stelle.php class:stelle function:deleteStelleGemeinden - Löschen der StelleGemeinden der Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}
	
	function deleteFunktionen() {
		# löscht alle StelleGemeinden der Stelle
		$sql ='DELETE FROM `u_funktion2stelle` WHERE `stelle_id` = '.$this->id;
		#echo '<br>'.$sql;
		$this->debug->write("<p>file:stelle.php class:stelle function:deleteFunktionen - Löschen der Funktionen der Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	function getstellendaten() {
		$sql ='SELECT * FROM stelle';
		$sql.=' WHERE ID = '.$this->id;
		$this->debug->write("<p>file:stelle.php class:stelle->getstellendaten - Abfragen der Stellendaten<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		$rs=mysql_fetch_array($query);
		return $rs;
	}

	function NeueStelleAnlegen($stellendaten) {
		$_files = $_FILES;
		# Neue Stelle anlegen
		$sql ='INSERT INTO stelle SET';
		if($stellendaten['id'] != ''){
			$sql.=' ID='.$stellendaten['id'].', ';
		}
		$sql.=' Bezeichnung="'.$stellendaten['bezeichnung'].'"';
		$sql.=', Referenzkarte_ID='.$stellendaten['Referenzkarte_ID'];
		$sql.=', alb_raumbezug="'.$stellendaten['alb_raumbezug'].'"';
		$sql.=', alb_raumbezug_wert="'.$stellendaten['alb_raumbezug_wert'].'"';
		$sql.=', minxmax= "'.$stellendaten['minxmax'].'"';
		$sql.=', minymax= "'.$stellendaten['minymax'].'"';
		$sql.=', maxxmax= "'.$stellendaten['maxxmax'].'"';
		$sql.=', maxymax= "'.$stellendaten['maxymax'].'"';
		$sql.=', epsg_code= "'.$stellendaten['epsg_code'].'"';
		$sql.=', start= "'.$stellendaten['start'].'"';
		$sql.=', stop= "'.$stellendaten['stop'].'"';
		if ($stellendaten['pgdbhost']!='') {
			$sql.=', pgdbhost= "'.$stellendaten['pgdbhost'].'"';
		}
		$sql.=', pgdbname= "'.$stellendaten['pgdbname'].'"';
		$sql.=', pgdbuser= "'.$stellendaten['pgdbuser'].'"';
		$sql.=', pgdbpasswd= "'.$stellendaten['pgdbpasswd'].'"';
		$sql.=', ows_title= "'.$stellendaten['ows_title'].'"';
		$sql.=', ows_abstract= "'.$stellendaten['ows_abstract'].'"';
		$sql.=', wms_accessconstraints= "'.$stellendaten['wms_accessconstraints'].'"';
		$sql.=', ows_contactperson= "'.$stellendaten['ows_contactperson'].'"';
		$sql.=', ows_contactorganization= "'.$stellendaten['ows_contactorganization'].'"';
		$sql.=', ows_contactemailaddress= "'.$stellendaten['ows_contactemailaddress'].'"';
		$sql.=', ows_contactposition= "'.$stellendaten['ows_contactposition'].'"';
		$sql.=', ows_fees= "'.$stellendaten['ows_fees'].'"';
		$sql.=', ows_srs= "'.$stellendaten['ows_srs'].'"';
		$sql.=', wappen_link= "'.$stellendaten['wappen_link'].'"';
		if($stellendaten['wappen']){
			$sql.=', wappen="'.$_files['wappen']['name'].'"';
		}
		elseif($stellendaten['wappen_save']){
			$sql.=', wappen="'.$stellendaten['wappen_save'].'"';
		}
		$sql.=', check_client_ip="';if($stellendaten['checkClientIP']=='1')$sql.='1';else $sql.='0';$sql.='"';
		$sql.=', check_password_age="';if($stellendaten['checkPasswordAge']=='1')$sql.='1';else $sql.='0';$sql.='"';
		$sql.=', allowed_password_age=';if($stellendaten['allowedPasswordAge']!='')$sql.=$stellendaten['allowedPasswordAge'];else $sql.='6';
		$sql.=', use_layer_aliases="';if($stellendaten['use_layer_aliases']=='1')$sql.='1';else $sql.='0';$sql.='"';
		$sql.=', hist_timestamp="';if($stellendaten['hist_timestamp']=='1')$sql.='1';else $sql.='0';$sql.='"';
		# Abfrage starten
		$ret=$this->database->execSQL($sql,4, 0);
		if ($ret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[1].='<br>Die Stellendaten konnten nicht eingetragen werden.<br>'.$ret[1];
		}
		else {
			# Stelle Erfolgreich angelegt
			# Abfragen der stelle_id des neu eingetragenen Benutzers
			$sql ='SELECT ID FROM stelle WHERE';
			$sql.=' Bezeichnung="'.$stellendaten['bezeichnung'].'"';
			# Starten der Anfrage
			$ret=$this->database->execSQL($sql,4, 0);
			#echo $sql;
			if ($ret[0]) {
				# Fehler bei der Datenbankanfrage
				$ret[1].='<br>Die Stellendaten konnten nicht eingetragen werden.<br>'.$ret[1];
			}
			else {
				# Abfrage erfolgreich durchgeführt, übergeben der stelle_id zur Rückgabe der Funktion
				$rs=mysql_fetch_array($ret[1]);
				$ret[1]=$rs['ID'];
			}
		}
		return $ret;
	}

	# Stelle ändern
	function Aendern($stellendaten) {
		$stelle = ($stellendaten['id'] != '' ? "`ID` = " . $stellendaten['id'] . ", " : "");
		$wappen = ($stellendaten['wappen'] != '' ? "`wappen` = '" . $stellendaten['wappen'] . "', " : "");
		$sql = "
			UPDATE
				stelle
			SET" .
				$stelle .
				$wappen . "
				`Bezeichnung` = '" . $stellendaten['bezeichnung'] . "',
				`Referenzkarte_ID` = " . $stellendaten['Referenzkarte_ID'] . ",
				`alb_raumbezug` = '" . $stellendaten['alb_raumbezug'] . "',
				`alb_raumbezug_wert` = '" . $stellendaten['alb_raumbezug_wert'] . "',
				`minxmax` = '" . $stellendaten['minxmax'] . "',
				`minymax` = '" . $stellendaten['minymax'] . "',
				`maxxmax` = '" . $stellendaten['maxxmax'] . "',
				`maxymax` = '" . $stellendaten['maxymax'] . "',
				`epsg_code` = '" . $stellendaten['epsg_code'] . "',
				`start` = '" . $stellendaten['start'] . "',
				`stop` = '" . $stellendaten['stop'] . "',
				`pgdbhost` = '" . $stellendaten['pgdbhost'] . "',
				`pgdbname` = '" . $stellendaten['pgdbname'] . "',
				`pgdbuser` = '" . $stellendaten['pgdbuser'] . "',
				`pgdbpasswd` = '" . $stellendaten['pgdbpasswd'] . "',
				`ows_title` = '" . $stellendaten['ows_title'] . "',
				`ows_abstract` = '" . $stellendaten['ows_abstract'] . "',
				`wms_accessconstraints` = '" . $stellendaten['wms_accessconstraints'] . "',
				`ows_contactperson` = '" . $stellendaten['ows_contactperson'] . "',
				`ows_contactorganization` = '" . $stellendaten['ows_contactorganization'] . "',
				`ows_contactemailaddress` = '" . $stellendaten['ows_contactemailaddress'] . "',
				`ows_contactposition` = '" . $stellendaten['ows_contactposition'] . "',
				`ows_fees` = '" . $stellendaten['ows_fees'] . "',
				`ows_srs` = '" . $stellendaten['ows_srs'] . "',
				`wappen_link` = '" . $stellendaten['wappen_link'] . "',
				`check_client_ip` =				'" . ($stellendaten['checkClientIP'] 				== '1'	? "1" : "0") . "',
				`check_password_age` =		'" . ($stellendaten['checkPasswordAge'] 		== '1'	? "1" : "0") . "',
				`use_layer_aliases` = 		'" . ($stellendaten['use_layer_aliases'] 		== '1'	? "1" : "0") . "',
				`hist_timestamp` = 				'" . ($stellendaten['hist_timestamp'] 			== '1'	? "1" : "0") . "',
				`allowed_password_age` = 	'" . ($stellendaten['allowedPasswordAge'] != '' 	? $stellendaten['allowedPasswordAge'] : "6") . "'
			WHERE
				ID = " . $this->id . "
		";

		#echo '<br>sql' . $sql;
		# Abfrage starten
		$ret=$this->database->execSQL($sql,4, 0);
		if ($ret[0]) {
			# Fehler bei Datenbankanfrage
			$ret[1].='<br>Die Stellendaten konnten nicht eingetragen werden.<br>'.$ret[1];
		}
		return $ret[1];
	}

	function getStellen($order) {
		if ($order != '') $order = " ORDER BY `" . $order . "`";
		$sql = "
			SELECT
				s.ID,
				s.Bezeichnung
			FROM
				`stelle` AS s" .
			$order . "
		";
		#echo '<br>sql: ' . $sql;

		$this->debug->write("<p>file:stelle.php class:stelle->getStellen - Abfragen aller Stellen<br>" . $sql, 4);
		$query = mysql_query($sql, $this->database->dbConn);
		if ($query == 0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		while($rs = mysql_fetch_array($query)) {
			$stellen['ID'][]=$rs['ID'];
			$stellen['Bezeichnung'][]=$rs['Bezeichnung'];
		}
		return $stellen;
	}

	function getParents($order = '', $return = '') {
		$parents = array();
		$sql = "
			SELECT
				s.`ID`,
				s.`Bezeichnung`
			FROM
				`stelle` AS s JOIN
				`stellen_hierarchie` AS h ON (s.`ID` = h.`parent_id`)
			WHERE
				h.`child_id`= " . $this->id . "
				" . $order . "
		";
		#echo '<br>stelle.php getParents sql:<br>' . $sql;

		$this->debug->write("<p>file:stelle.php class:stelle->getParents - Abfragen aller Elternstellen<br>" . $sql, 4);
		$query = mysql_query($sql, $this->database->dbConn);
		if ($query == 0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return array(); }
		while($rs = mysql_fetch_assoc($query)) {
			$parents[] = ($return == 'only_ids' ? $rs['ID'] : $rs);
		};
		return $parents;
	}

	function getChildren($parent_id, $order = '') {
		$children = array();
		if ($order != '') $order = " ORDER BY `" . $order . "`";
		$sql = "
			SELECT
				s.`ID`,
				s.`Bezeichnung`
			FROM
				`stelle` AS s JOIN
				`stellen_hierarchie` AS h ON (s.`ID` = h.`child_id`)
			WHERE
				h.`parent_id`= " . $parent_id .
			$order . "
		";
		#echo '<br>sql: ' . $sql;

		$this->debug->write("<p>file:stelle.php class:getChildren - Abfragen aller Kindstellen<br>" . $sql, 4);
		$query = mysql_query($sql, $this->database->dbConn);
		if ($query == 0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return array(); }

		while($rs = mysql_fetch_assoc($query)) {
			$children[] = $rs;
			$children = array_merge($children, $this->getChildren($rs['ID']));
		};
		return $children;
	}

	/*
	* function collect all distinct menue_ids from parents of this stelle  
	*/
	function get_parent_menues() {
		#echo '<p>stelle.php get_parent_menues';
		$parent_menue_ids = array();
		foreach($this->getParents() AS $parent) {
			$parent_stelle = new stelle($parent['ID'], $this->database);
			$parent_menues = $parent_stelle->getMenue(0);
			foreach($parent_menues['ID'] AS $parent_menue_id) {
				if (!in_array($parent_menue_id, $parent_menue_ids)) $parent_menue_ids[] = $parent_menue_id;
			}
		}
		#echo '<br>Returned parent_menue_ids: ' . implode(', ', $parent_menue_ids);
		return $parent_menue_ids;
	}

	function getFunktionen($return = '') {
		# Abfragen der Funktionen, die in der Stelle ausgeführt werden dürfen
		$sql ='SELECT f.id,f.bezeichnung, 1 as erlaubt FROM u_funktionen AS f,u_funktion2stelle AS f2s';
		$sql.=' WHERE f.id=f2s.funktion_id AND f2s.stelle_id='.$this->id.' ORDER BY bezeichnung';
		$this->debug->write("<p>file:stelle.php class:stelle->getFunktionen - Fragt die Funktionen der Stelle ab:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4);
			$errmsg='Fehler bei der Abfrage der Funktionen für die Stelle';
			return $errmsg;
		}
		else {
			while($rs=mysql_fetch_array($query)) {
				if ($return == 'only_ids') {
					$funktionen[] = $rs['id'];
				}
				else {
					$funktionen[$rs['bezeichnung']]=$rs;
					$funktionen['array'][]=$rs;
				}
			}
		}
		$this->funktionen=$funktionen;
		return $funktionen;
	}

	function isFunctionAllowed($functionname) {
		$this->getFunktionen();
		if($this->funktionen[$functionname]['erlaubt']) {
			return 1;
		}
		else {
			return 0;
		}
	}

	function isMenueAllowed($menuename){
		$sql = "SELECT distinct a.* from u_menues as a, u_menue2stelle as b ";
		$sql.= "WHERE links LIKE 'index.php?go=".$menuename."%' AND b.menue_id = a.id AND b.stelle_id = ".$this->id;
		#echo $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->isMenueAllowed - Guckt ob der Menuepunkt der Stelle zugeordnet ist:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4);
			$errmsg='Fehler bei der Ueberpruefung des Menuepunkts für die Stelle';
		}
		else{
			$rs=mysql_fetch_array($query);
		}
		if($rs[0] != '') {
			return 1;
		}
		else {
			return 0;
		}
	}

	function getFlurstueckeAllowed($FlurstKennz, $database) {
		include_(CLASSPATH.'alb.php');
		$GemeindenStelle = $this->getGemeindeIDs();
		if($GemeindenStelle != NULL){
			$alb = new ALB($database);
			$ret=$alb->getFlurstKennzByGemeindeIDs($GemeindenStelle, $FlurstKennz);
			if ($ret[0]==0) {
				$anzFlurstKennz=count($ret[1]);
				if ($anzFlurstKennz==0) {
					$ret[0]=1;
					$ret[1]="Sie haben keine Berechtigung zur Ansicht diese(s)r Flurstücke(s)";
				}
			}
			# ret[0] = 1 wenn Fehler in Datenbankabfrage oder keine FlurstKennz zurück
			# ret[1] = Fehlermeldung oder Liste FlurstKennz
		}
		else{
			$ret[1] = $FlurstKennz;
		}
		return $ret;
	}

	/*
	* Add inheritted menues, functions, layouts, layers and users that not allready exists in formvars
	* Remove inheritted menues, functions, layouts, layers and users that currently exists in formvars
	*/
	function apply_parent_selection(
		$selected_parents,
		&$menues,
		&$functions,
		&$frames,
		&$layouts,
		&$layer,
		&$selectedusers
	) {
		include_once(CLASSPATH . 'datendrucklayout.php');
		$results = array();
		$old_parents = $this->getParents('ORDER BY `ID`', 'only_ids');
		$document = new Document($this->database);
		$ddl = new ddl($this->database);
		
		# Entferne Einstellungen der Elternstellen von Stelle
		foreach(array_diff($old_parents, $selected_parents) AS $drop_parent_id) {
			# echo '<br>Entferne Elternstelle ' . $drop_parent_id;
			$parent_stelle = new stelle($drop_parent_id, $this->database);
			$menues = array_values(array_diff($menues, $parent_stelle->getMenue(0, 'only_ids')));
			$functions = array_values(array_diff($functions, $parent_stelle->getFunktionen('only_ids')));
			$layouts = array_values(array_diff($layouts, $ddl->load_layouts($drop_parent_id, '', '', '', 'only_ids')));
			$frames = array_values(array_diff($frames, $document->load_frames($drop_parent_id, false, 'only_ids')));
			$parent_layer = $parent_stelle->getLayer('', 'only_ids');
			$layer = array_values(array_diff($layer, $parent_layer));
			$selectedusers = array_values(array_diff($selectedusers, $parent_stelle->getUser('only_ids')));
			$results[] = $this->dropParent($drop_parent_id);
		}

		# Füge Einstellungen der Elternstellen zur Stelle hinzu
		foreach(array_diff($selected_parents, $old_parents) AS $new_parent_id) {
			$parent_stelle = new stelle($new_parent_id, $this->database);

			$menues = $this->merge_menues($menues, $parent_stelle->getMenue(0, 'only_ids'));
			$functions = array_values(array_unique(array_merge($functions, $parent_stelle->getFunktionen('only_ids'))));
			$layouts = array_values(array_unique(array_merge($layouts, $ddl->load_layouts($new_parent_id, '', '', '', 'only_ids'))));
			$frames = array_values(array_unique(array_merge($frames, $document->load_frames($new_parent_id, false, 'only_ids'))));
			$layer = array_values(array_unique(array_merge($layer, $parent_stelle->getLayer('', 'only_ids'))));
			$selectedusers = array_values(array_unique(array_merge($selectedusers, $parent_stelle->getUser('only_ids'))));
			$results[] = $this->addParent($new_parent_id);
		}
		return $results;
	}

	/*
	* Merge $new_menues in correct order with $menue
	* ToDo: replace array_merge by correct logig to merge with order not only append
	*/
	function merge_menues($menues, $new_menues) {
		$result = array_values(array_unique(array_merge($menues, $new_menues)));
		return $result;
	}

	function addParent($parent_id) {
		$sql = "
			INSERT INTO `stellen_hierarchie` (
				`parent_id`,
				`child_id`
			)
			VALUES (
				" . $parent_id . ",
				" . $this->id . "
			)
		";
		#echo 'Sql: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->addParent - Add Parent Id: " . $parent_id . " zu Stelle Id: " . $this->id . "<br>" . $sql, 4);
		$query = mysql_query($sql, $this->database->dbConn);
		if ($query == 0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4);
			return array(
				'type' => 'error',
				'message' => 'Fehler beim Eintragen der Elternstelle: ' . mysql_error()
			);
		}

		return array(
			'type' => 'notice',
			'message' => 'Elternstelle Id: ' . $parent_id . ' erfolgreich zugewiesen.'
		);
	}

	function dropParent($drop_parent_id) {
		$sql = "
			DELETE FROM `stellen_hierarchie`
			WHERE
				`parent_id` = " . $drop_parent_id . " AND
				`child_id` = " . $this->id . "
		";
		#echo '<p>stelle.php dropParent(' . $drop_parent_id . ') Sql: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->dropParent - Delete Parent Id: " . $drop_parent_id . " von Stelle Id: " . $this->id . "<br>" . $sql, 4);
		$query = mysql_query($sql, $this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4);
			return array(
				'type' => 'error',
				'message' => 'Fehler beim Löschen der Elternstelle: ' . mysql_error()
			);
		}

		return array(
			'type' => 'notice',
			'message' => 'Elternstelle Id: ' . $drop_parent_id . ' erfolgreich gelöscht.'
		);
	}

	/*
	* Hinzufügen von Menuepunkten zur Stelle
	*/
	function addMenue($menue_ids) {
		$sql = "
			SELECT
				MAX(menue_order)
			FROM
				u_menue2stelle
			WHERE
				stelle_id = " . $this->id . "
		";
		#echo '<br>stelle.php addMenue Sql: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->addMenue - Lesen der maximalen menue_order der Menuepunkte der Stelle:<br>".$sql,4);
		$query = mysql_query($sql, $this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else {
			$rs = mysql_fetch_array($query);
		}
		$count = ($rs[0] == '' ? 0 : $rs[0]);
		for ($i = 0; $i < count($menue_ids); $i++) {
			$sql ="
				INSERT IGNORE INTO
					u_menue2stelle (
						`stelle_id`,
						`menue_id`,
						`menue_order`
					)
				VALUES (
					'" . $this->id ."',
					'" . $menue_ids[$i] . "',
					'" . $count . "'
				)
			";
			#echo '<br>stelle.php addMenue Sql: ' . $sql;
			$count++;
			$this->debug->write("<p>file:stelle.php class:stelle->addMenue - Hinzufügen von Menuepunkten zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }

			/* $sql ='SELECT id FROM u_menues WHERE obermenue = '.$menue_ids[$i];
			 $this->debug->write("<p>file:stelle.php class:stelle->addMenue - Lesen der Untermenuepunkte zu den Obermenuepunken zur Stelle:<br>".$sql,4);
			 $query=mysql_query($sql,$this->database->dbConn);
			 if ($query==0) {
			 $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
			 }
			 else{
			 while($rs=mysql_fetch_array($query)) {
			 $sql ="INSERT IGNORE INTO u_menue2stelle ( `stelle_id` , `menue_id` , `menue_order` ) VALUES ('".$this->id."', '".$rs[0]."', '".$count."')";
			 $count++;
			 $this->debug->write("<p>file:stelle.php class:stelle->addMenue - Hinzufügen von Menuepunkten zur Stelle:<br>".$sql,4);
			 $query1=mysql_query($sql,$this->database->dbConn);
			 if ($query1==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			 }
			 }
			 */
		}
		return 1;
	}

	function getMenue($ebene, $return = '') {
		global $language;

		# Lesen der Menuepunkte zur Stelle
		if ($language != 'german') {
			$name_column = "
			CASE
				WHEN m.`name_" . $language . "` != \"\" THEN m.`name_" . $language . "`
				ELSE m.`name`
			END AS `name`";
		}
		else
			$name_column = "m.`name`";

		$sql = "
			SELECT
				`menue_id`," .
				$name_column . ",
				`menueebene`,
				`order`
			FROM
				`u_menues` m JOIN
				`u_menue2stelle` m2s ON m.`id` = m2s.`menue_id`
			WHERE
				m2s.`stelle_id` = " . $this->id .
				($ebene != 0 ? " AND menueebene = " . $ebene : "") . "
			ORDER BY
				menue_order
		";
		#echo '<br>stelle.php getMenue(' . $ebene . ') Sql: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->getMenue - Lesen der Menuepunkte zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=mysql_fetch_array($query)) {
				$menue['ID'][]=$rs['menue_id'];
				$menue['ORDER'][]=$rs['order'];
				$menue['menueebene'][]=$rs['menueebene'];
				if($rs['menueebene'] == 2){
					$menue['Bezeichnung'][]='&nbsp;&nbsp;-->&nbsp;'.$rs['name'];
				}
				else{
					$menue['Bezeichnung'][]=$rs['name'];
				}
			}
		}
		if ($return == 'only_ids') {
			return $menue['ID'];
		} else {
			return $menue;
		}
	}

	function copyLayerfromStelle($layer_ids, $alte_stelle_id){
		# kopieren der Layer von einer Stelle
		for ($i=0;$i<count($layer_ids);$i++) {
			$sql ='INSERT IGNORE INTO used_layer ( `Stelle_ID` , `Layer_ID` , `queryable` , `drawingorder` , `minscale` , `maxscale` , `offsite` , `transparency`, `template` , `header` , `footer` , `symbolscale`, `logconsume`, `requires`, `privileg` )';
			$sql .= ' SELECT '.$this->id.', `Layer_ID` , `queryable` , `drawingorder` , `minscale` , `maxscale` , `offsite` , `transparency`, `template` , `header` , `footer` , `symbolscale`, `logconsume`, `requires`, `privileg` FROM used_layer WHERE Stelle_ID = '.$alte_stelle_id.' AND Layer_ID = '.$layer_ids[$i];
			$this->debug->write("<p>file:stelle.php class:stelle->copyLayerfromStelle - kopieren der Layer von einer Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			# Layerattributrechte mitkopieren
			$sql ='INSERT IGNORE INTO layer_attributes2stelle (layer_id, attributename, stelle_id, privileg, tooltip) ';
			$sql.='SELECT layer_id, attributename, '.$this->id.', privileg, tooltip FROM layer_attributes2stelle WHERE stelle_id = '.$alte_stelle_id.' AND layer_id = '.$layer_ids[$i];
			$this->debug->write("<p>file:stelle.php class:stelle->copyLayerfromStelle - kopieren der Layer von einer Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function addFunctions($function_ids){
		# Hinzufügen von Funktionen zur Stelle
		for ($i=0;$i<count($function_ids);$i++) {
			$sql ='INSERT IGNORE INTO u_funktion2stelle ( `funktion_id` , `stelle_id`)';
			$sql.="VALUES ('".$function_ids[$i]."', '".$this->id."')";
			$this->debug->write("<p>file:stelle.php class:stelle->addFunctions - Hinzufügen von Funktionen zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function removeFunctions(){
		# Entfernen von Funktionen zur Stelle
		$sql ='DELETE FROM u_funktion2stelle ';
		$sql.='WHERE stelle_id = '.$this->id;
		$this->debug->write("<p>file:stelle.php class:stelle->removeFunctions - Entfernen von Funktionen zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	function addLayer($layer_ids, $drawingorder, $filter = '') {
		#echo '<br>stelle.php addLayer ids: ' . implode(', ', $layer_ids);
		# Hinzufügen von Layern zur Stelle
		for ($i=0;$i<count($layer_ids);$i++) {
			$sql = "
				INSERT IGNORE INTO used_layer (
					`Stelle_ID`,
					`Layer_ID`,
					`queryable`,
					`drawingorder`,
					`legendorder`,
					`minscale`,
					`maxscale`,
					`symbolscale`,
					`offsite`,
					`transparency`,
					`Filter`,
					`template`,
					`header`,
					`footer`,
					`privileg`,
					`export_privileg`,
					`postlabelcache`,
					`requires`
				)
				SELECT
					'" . $this->id . "',
					'" . $layer_ids[$i] . "',
					queryable, 
					drawingorder, 
					legendorder, 
					minscale, 
					maxscale, 
					symbolscale, 
					offsite, 
					transparency, 
					'" . $filter . "',
					template, 
					NULL,
					NULL,
					privileg, 
					export_privileg, 
					postlabelcache,
					requires
				FROM
					layer
				WHERE
					Layer_ID = " . $layer_ids[$i];
			#echo '<br>' . $sql;
			$this->debug->write("<p>file:stelle.php class:stelle->addLayer - Hinzufügen von Layern zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			
			if(mysql_affected_rows() > 0){
				$sql = "INSERT IGNORE INTO layer_attributes2stelle (layer_id, attributename, stelle_id, privileg, tooltip) ";
				$sql.= "SELECT ".$layer_ids[$i].", name, ".$this->id.", privileg, query_tooltip FROM layer_attributes WHERE layer_id = ".$layer_ids[$i]." AND privileg IS NOT NULL";
				#echo $sql.'<br>';
				$this->debug->write("<p>file:stelle.php class:stelle->addLayer - Hinzufügen von Layern zur Stelle:<br>".$sql,4);
				$query=mysql_query($sql,$this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			}
		}
		return 1;
	}
	
	function updateLayerParams(){
		$sql = "UPDATE stelle SET selectable_layer_params = ";
		$sql.= "COALESCE((SELECT GROUP_CONCAT(id) ";
		$sql.= "FROM `layer_parameter` as p, used_layer as ul, layer as l ";
		$sql.= "WHERE ul.Stelle_ID = stelle.ID ";
		$sql.= "AND ul.Layer_ID = l.Layer_ID ";
		$sql.= "AND locate(concat('$', p.key), concat(l.Name, l.alias, l.connection, l.Data, l.pfad, l.classitem, l.classification)) > 0), '') ";
		$sql.= "WHERE stelle.ID = ".$this->id;
		$this->debug->write("<p>file:stelle.php class:stelle->updateLayerParams:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		$sql = "UPDATE rolle SET layer_params = ";
		$sql.= "COALESCE((SELECT GROUP_CONCAT(concat('\"', `key`, '\":\"', default_value, '\"')) ";
		$sql.= "FROM layer_parameter p, stelle ";
		$sql.= "WHERE FIND_IN_SET(p.id, stelle.selectable_layer_params) ";
		$sql.= "AND stelle.ID = rolle.stelle_id), '') ";
		$sql.= "WHERE rolle.stelle_id = ".$this->id;
		$this->debug->write("<p>file:stelle.php class:stelle->updateLayerParams:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
	}

	function updateLayer($formvars){
		# Aktualisieren der LayerzuStelle-Eigenschaften
		$sql = 'UPDATE used_layer SET Layer_ID = '.$formvars['selected_layer_id'];
		$sql .= ', queryable = "'.$formvars['queryable'].'"';
		$sql .= ', use_geom = '.$formvars['use_geom'];
		if ($formvars['minscale']!='') {
			$sql .= ', minscale = '.$formvars['minscale'];
		}
		else{
			$sql .= ', minscale = NULL';
		}
		if ($formvars['maxscale']!='') {
			$sql .= ', maxscale = '.$formvars['maxscale'];
		}
		else{
			$sql .= ', maxscale = NULL';
		}
		$sql .= ', offsite = "'.$formvars['offsite'].'"';
		if ($formvars['transparency']!='') {
			$sql .= ', transparency = '.$formvars['transparency'];
		}
		else{
			$sql .= ', transparency = NULL';
		}
		$sql .= ', postlabelcache = "'.$formvars['postlabelcache'].'"';
		$sql .= ", Filter = '".$formvars['Filter']."'";
		$sql .= ', template = "'.$formvars['template'].'"';
		$sql .= ', header = "'.$formvars['header'].'"';
		$sql .= ', footer = "'.$formvars['footer'].'"';
		if ($formvars['symbolscale']!='') {
			$sql .= ', symbolscale = '.$formvars['symbolscale'];
		}
		else{
			$sql .= ', symbolscale = NULL';
		}
		if($formvars['requires'] == '')$formvars['requires'] = 'NULL';
		$sql .= ', requires = '.$formvars['requires'];
		$sql .= ', start_aktiv = "'.$formvars['startaktiv'].'"';
		$sql .= ', logconsume = "'.$formvars['logconsume'].'"';
		$sql .= ' WHERE Stelle_ID = '.$formvars['selected_stelle_id'].' AND Layer_ID = '.$formvars['selected_layer_id'];
		#echo $sql.'<br>';
		$this->debug->write("<p>file:stelle.php class:stelle->updateLayer - Aktualisieren der LayerzuStelle-Eigenschaften:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
	}

	function updateLayerOrder($formvars){
		# Aktualisieren der LayerzuStelle-Eigenschaften
		if($formvars['legendorder'] == '')$formvars['legendorder'] = 'NULL';
		$sql = 'UPDATE used_layer SET Layer_ID = '.$formvars['selected_layer_id'];
		$sql .= ', drawingorder = '.$formvars['drawingorder'];
		$sql .= ', legendorder = '.$formvars['legendorder'];
		$sql .= ' WHERE Stelle_ID = '.$formvars['selected_stelle_id'].' AND Layer_ID = '.$formvars['selected_layer_id'];
		#echo $sql.'<br>';
		$this->debug->write("<p>file:stelle.php class:stelle->updateLayerdrawingorder - Aktualisieren der LayerzuStelle-Eigenschaften:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
	}

  function getGroups() {
		global $language;
		$sql = 'SELECT DISTINCT';
		if($language != 'german') {
			$sql.=' CASE WHEN `Gruppenname_'.$language.'` IS NOT NULL THEN `Gruppenname_'.$language.'` ELSE `Gruppenname` END AS';
		}
		$sql.=' Gruppenname, obergruppe, g.id FROM u_groups AS g, u_groups2rolle AS g2r';
		$sql.=' WHERE g2r.stelle_ID='.$this->id;
		$sql.=' AND g2r.id = g.id';
		$sql.=' ORDER BY `order`';
		#echo $sql;
    $this->debug->write("<p>file:kvwmap class:stelle->getGroups - Lesen der Gruppen der Stelle:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    while ($rs=mysql_fetch_assoc($query)) {
      $groups[$rs['id']]=$rs;
			if($rs['obergruppe'])$groups[$rs['obergruppe']]['untergruppen'][] = $rs['id'];
    }
    return $groups;
  }
	
	function getLayers($group, $order = 'legendorder, drawingorder desc', $return = '') {
		$layer = array(
			'ID' => array(),
			'Bezeichnung' => array(),
			'drawingorder' => array(),
			'Gruppe' => array()
		);

		$condition = "
			stelle_id = " . $this->id .
			($group != NULL ? " AND layer.Gruppe = " . $group : "") . "
		";
		$order = ($order != NULL ? 'ORDER BY '.$order : 'ORDER BY legendorder, drawingorder desc');

		# Lesen der Layer zur Stelle
		$sql = "
			SELECT
				layer.Layer_ID,
				layer.Gruppe,
				Name,
				used_layer.drawingorder,
				used_layer.legendorder
			FROM
				used_layer JOIN
				layer ON used_layer.Layer_ID = layer.Layer_ID 
			WHERE" .
				$condition .
			$order . "
		";
		#echo '<br>stelle.php getLayers Sql:<br>' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->getLayers - Lesen der Layer zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			$i = 0;
			while($rs=mysql_fetch_assoc($query)) {
				$layer['ID'][]=$rs['Layer_ID'];
				$layer['Bezeichnung'][]=$rs['Name'];
				$layer['drawingorder'][]=$rs['drawingorder'];
				$layer['legendorder'][]=$rs['legendorder'];
				$layer['Gruppe'][]=$rs['Gruppe'];
				$layer['layers_of_group'][$rs['Gruppe']][] = $i;
				$i++;
			}
			if($order == 'Name'){
				// Sortieren der Layer unter Berücksichtigung von Umlauten
				$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
				$sorted_layer['Bezeichnung'] = $sorted_arrays['array'];
				$sorted_layer['ID'] = $sorted_arrays['second_array'];
				
				$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['drawingorder']);
				$sorted_layer['drawingorder'] = $sorted_arrays['second_array'];
				
				$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['legendorder']);
				$sorted_layer['legendorder'] = $sorted_arrays['second_array'];
				
				$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['Gruppe']);
				$sorted_layer['Gruppe'] = $sorted_arrays['second_array'];
				$layer = $sorted_layer;
			}
		}
		if ($return == 'only_ids') {
			return $layer['ID'];
		}
		else {
			return $layer;
		}
	}

	function getqueryablePostgisLayers($privileg, $export_privileg = NULL, $no_subform_layers = false){
		$sql = 'SELECT distinct Layer_ID, Name, alias, export_privileg FROM (';
		$sql .='SELECT layer.Layer_ID, layer.Name, layer.alias, used_layer.export_privileg, form_element_type as subformfk, las.privileg as privilegfk ';
		$sql .='FROM u_groups, layer, used_layer ';
		$sql .='LEFT JOIN layer_attributes as la ON la.layer_id = used_layer.Layer_ID AND form_element_type = \'SubformFK\' ';
		$sql .='LEFT JOIN layer_attributes2stelle as las ON las.stelle_id = used_layer.Stelle_ID AND  used_layer.Layer_ID = las.layer_id AND las.attributename = SUBSTRING_INDEX(SUBSTRING_INDEX(la.options, \';\', 1) , \',\',  -1) ';		
		$sql .=' WHERE used_layer.stelle_id = '.$this->id;
		$sql .=' AND layer.Gruppe = u_groups.id AND layer.connectiontype = 6';
		$sql .=' AND layer.Layer_ID = used_layer.Layer_ID';
		$sql .=' AND used_layer.queryable = \'1\'';
		if($privileg != NULL){
			$sql .=' AND used_layer.privileg >= "'.$privileg.'"';
		}
		if($export_privileg != NULL){
			$sql .=' AND used_layer.export_privileg > 0';
		}
		$sql .= ' ORDER BY Name) as foo ';
		if($privileg > 0 AND $no_subform_layers){
			$sql .= 'WHERE subformfk IS NULL OR privilegfk = 1';			# nicht editierbare SubformFKs ausschliessen
		}
		#echo $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->getqueryablePostgisLayers - Lesen der abfragbaren PostgisLayer zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=mysql_fetch_array($query)) {
				if($rs['alias'] != '' AND $this->useLayerAliases){
					$rs['Name'] = $rs['alias'];
				}
				$layer['ID'][]=$rs['Layer_ID'];
				$layer['Bezeichnung'][]=$rs['Name'];
				$layer['export_privileg'][]=$rs['export_privileg'];
			}
			// Sortieren der User unter Berücksichtigung von Umlauten
			$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
			$sorted_arrays2 = umlaute_sortieren($layer['Bezeichnung'], $layer['export_privileg']);
			$layer['Bezeichnung'] = $sorted_arrays['array'];
			$layer['ID'] = $sorted_arrays['second_array'];
			$layer['export_privileg'] = $sorted_arrays2['second_array'];
		}
		return $layer;
	}

	function getqueryableVectorLayers($privileg, $user_id, $group_id = NULL, $layer_ids = NULL, $rollenlayer_type = NULL, $use_geom = NULL){
		global $language;
		$sql = 'SELECT layer.Layer_ID, ';
		if($language != 'german') {
			$sql.='CASE WHEN `Name_'.$language.'` != "" THEN `Name_'.$language.'` ELSE `Name` END AS ';
		}
		$sql .='Name, alias, Gruppe, ';
		if($language != 'german') {
			$sql.='CASE WHEN `Gruppenname_'.$language.'` != "" THEN `Gruppenname_'.$language.'` ELSE `Gruppenname` END AS ';
		}
		$sql .='Gruppenname, `connection` FROM used_layer, layer, u_groups';
		$sql .=' WHERE stelle_id = '.$this->id;
		$sql .=' AND layer.Gruppe = u_groups.id AND (layer.connectiontype = 6 OR layer.connectiontype = 9)';
		$sql .=' AND layer.Layer_ID = used_layer.Layer_ID';
		if($use_geom != NULL){
			$sql .=' AND used_layer.use_geom = 1';
		}
		else{
			$sql .=' AND used_layer.queryable = \'1\'';
		}
		if($privileg != NULL){
			$sql .=' AND used_layer.privileg >= "'.$privileg.'"';
		}		
		if($group_id != NULL){
			$sql .=' AND u_groups.id = '.$group_id;
		}
		if($layer_ids != NULL){
			$sql .=' AND layer.Layer_ID IN ('.implode($layer_ids, ',').')';
		}
		if($user_id != NULL){
			$sql .= ' UNION ';
			$sql .= 'SELECT -id as Layer_ID, concat(substring( `Name` FROM 1 FOR locate( ")", `Name` )), CASE WHEN Typ = "search" THEN " -Suchergebnis-" ELSE " -eigener Import-" END), "", Gruppe, " ", `connection` FROM rollenlayer';
			$sql .= ' WHERE stelle_id = '.$this->id.' AND user_id = '.$user_id.' AND connectiontype = 6';			
			if($rollenlayer_type != NULL){
				$sql .=' AND Typ = "'.$rollenlayer_type.'"';
			}
			if($group_id != NULL){
				$sql .=' AND Gruppe = '.$group_id;
			}
		}
		$sql .= ' ORDER BY Name';
		#echo $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->getqueryableVectorLayers - Lesen der abfragbaren VektorLayer zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);		
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=mysql_fetch_assoc($query)){
				 
				# fremde Layer werden auf Verbindung getestet (erstmal rausgenommen, dauert relativ lange)
				// if(strpos($rs['connection'], 'host') !== false AND strpos($rs['connection'], 'host=localhost') === false){
					// $connection = explode(' ', trim($rs['connection']));
					// for($j = 0; $j < count($connection); $j++){
						// if($connection[$j] != ''){
							// $value = explode('=', $connection[$j]);
							// if(strtolower($value[0]) == 'host'){
								// $host = $value[1];
							// }
							// if(strtolower($value[0]) == 'port'){
								// $port = $value[1];
							// }
						// }
					// }
					// if($port == '')$port = '5432';
					// $fp = @fsockopen($host, $port, $errno, $errstr, 0.1);
					// if(!$fp){			# keine Verbindung --> Layer ausschalten
						// #$this->Fehlermeldung = $errstr.' für Layer: '.$rs['Name'].'<br>';
						// continue;
					// }
				// }
				
				$rs['Name'] = replace_params($rs['Name'], rolle::$layer_params);
				$rs['alias'] = replace_params($rs['alias'], rolle::$layer_params);				
				
				if($rs['alias'] != '' AND $this->useLayerAliases){
					$rs['Name'] = $rs['alias'];
				}
				$layer['ID'][]=$rs['Layer_ID'];
				$layer['Bezeichnung'][]=$rs['Name'];
				$layer['Gruppe'][]=$rs['Gruppe'];
				$layer['Gruppenname'][]=$rs['Gruppenname'];
			}
			// Sortieren der User unter Berücksichtigung von Umlauten
			$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
			$layer['Bezeichnung'] = $sorted_arrays['array'];
			$layer['ID'] = $sorted_arrays['second_array'];
		}
		return $layer;
	}

	function addAktivLayer($layerid) {
		# Hinzufügen der Layer als aktive Layer
		for ($i=0;$i<count($layerid);$i++) {
			$sql ='UPDATE used_layer SET aktivStatus="1"';
			$sql.=' WHERE Stelle_ID='.$this->id.' AND Layer_ID='.$layerid[$i];
			$this->debug->write("<p>file:stelle.php class:stelle->addAktivLayer - Hinzufügen von aktiven Layern zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function setAktivLayer($formvars) {
		# Eintragen des Status der Layer, 1 angezeigt oder 0 nicht.
		$layerset=$this->getLayer('');
		for ($i=0;$i<count($layerset);$i++) {
			if ($formvars['thema'.$layerset[$i]['Layer_ID']]==1) {
				$aktiv_status=1;
			}
			else {
				$aktiv_status=0;
			}
			$sql ='UPDATE used_layer SET aktivStatus="'.$aktiv_status.'"';
			$sql.=' WHERE Stelle_ID='.$this->id.' AND Layer_ID='.$layerset[$i]['Layer_ID'];
			$this->debug->write("<p>file:stelle.php class:stelle->setAktivLayer - Speichern der aktiven Layer zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function setQueryStatus($formvars) {
		# Eintragen des query_status=1 für Layer, die für die Abfrage selektiert wurden
		$layerset=$this->getLayer('');
		for ($i=0;$i<count($layerset);$i++) {
			if ($formvars['qLayer'.$layerset[$i]['Layer_ID']]) {
				$query_status=1;
			}
			else {
				$query_status=0;
			}
			$sql ='UPDATE used_layer set queryStatus="'.$query_status.'"';
			$sql.=' WHERE Layer_ID='.$layerset[$i]['Layer_ID'];
			$this->debug->write("<p>file:stelle.php class:stelle->setQueryStatus - Speichern des Abfragestatus der Layer zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	/*
	* Abfragen der Layer der Stelle
	*/
	function getLayer($Layer_id, $result = '') {
		#echo '<br>stelle.php getLayer';
		$sql = "
			SELECT
				l.*,
				ul.*
			FROM
				layer AS l JOIN
				used_layer AS ul ON l.Layer_ID = ul.Layer_ID
			WHERE
				Stelle_ID = " . $this->id .
				($Layer_id != '' ? " AND l.Layer_ID = " . $Layer_id : '') . "
		";
		#echo '<br>getLayer Sql:<br>'. $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->getLayer - Abfragen der Layer zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		while ($rs=mysql_fetch_array($query)) {
			$layer[] = ($result == 'only_ids' ? $rs['Layer_ID'] : $rs);
		}
		return $layer;
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
		$query = mysql_query($sql, $this->database->dbConn);
		if ($query == 0) { $this->debug->write("<br>Abbruch in " . $PHP_SELF . " Zeile: " . __LINE__, 4); return 0; }
		while ($rs = mysql_fetch_array($query)) {
			$privileges[$rs['attributename']] = $rs['privileg'];
			$privileges['tooltip_' . $rs['attributename']] = $rs['tooltip'];
			$privileges['attributenames'][] = $rs['attributename'];
		}
		return $privileges;
	}

	function parse_path($database, $path, $privileges, $attributes = NULL){
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
		if($privileges == NULL){  # alle Attribute sind abfragbar
			$newpath = $path;
		}
		else{
			$where = substr($path, $whereposition);
			$from = substr($withoutwhere, $fromposition);

			$attributesstring = substr($path, $offset, $fromposition-$offset);
			//$fieldstring = explode(',', $attributesstring);
			$fieldstring = get_select_parts($attributesstring);
			$count = count($fieldstring);
			for($i = 0; $i < $count; $i++){
				if(strpos(strtolower($fieldstring[$i]), ' as ')){   # Ausdruck AS attributname
					$explosion = explode(' as ', strtolower($fieldstring[$i]));
					$attributename = trim(array_pop($explosion));
					$real_attributename = $explosion[0];
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
		}
		return $newpath;
	}

	function set_layer_privileges($layer_id, $privileg, $exportprivileg){
		$sql = 'UPDATE used_layer SET privileg = "'.$privileg.'", export_privileg = "'.$exportprivileg.'" WHERE ';
		$sql.= 'layer_id = '.$layer_id.' AND stelle_id = '.$this->id;
		$this->debug->write("<p>file:stelle.php class:stelle->set_layer_privileges - Speichern der Layerrechte zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
	}

	function set_attributes_privileges($formvars, $attributes){
		# erst alles löschen zu diesem Layer und Stelle
		$sql = "
			DELETE FROM
				`layer_attributes2stelle`
			WHERE
				`layer_id` = " . $formvars['selected_layer_id'] . " AND
				`stelle_id` = " . $this->id . "
		";
		#echo '<br>Sql: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->set_attributes_privileges - Speichern des Layerrechte zur Stelle:<br>" . $sql, 4);
		$query=mysql_query($sql, $this->database->dbConn);
		if ($query == 0) { $this->debug->write("<br>Abbruch in " . $PHP_SELF . " Zeile: " . __LINE__, 4); return 0; }
		# dann Attributrechte eintragen
		for ($i = 0; $i < count($attributes['type']); $i++) {
			if($formvars['privileg_'.$attributes['name'][$i].$this->id] !== '') {
				$sql = "
					INSERT INTO
						layer_attributes2stelle
					SET 
						`layer_id` = " . $formvars['selected_layer_id'] . ",
						`stelle_id` = " . $this->id . ",
						`attributename` = '" . $attributes['name'][$i] . "',
						`privileg` = " . $formvars['privileg_' . $attributes['name'][$i] . $this->id] .",
						`tooltip`= " . ($formvars['tooltip_' . $attributes['name'][$i] . $this->id] == 'on' ? "1" : "0") . "
				";
				#echo '<br>Sql: ' . $sql;
				$this->debug->write("<p>file:stelle.php class:stelle->set_attributes_privileges - Speichern des Layerrechte zur Stelle:<br>" . $sql, 4);
				$query=mysql_query($sql, $this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in " . $PHP_SELF . " Zeile: " . __LINE__, 4); return 0; }
			}
		}
	}

	function getGemeindeIDs() {
		$sql = 'SELECT Gemeinde_ID, Gemarkung, Flur FROM stelle_gemeinden WHERE Stelle_ID = '.$this->id;
		#echo $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->getGemeindeIDs - Lesen der GemeindeIDs zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if(mysql_num_rows($query) > 0){
			$liste['ganze_gemeinde'] = Array();
			$liste['eingeschr_gemeinde'] = Array();
			$liste['ganze_gemarkung'] = Array();
			$liste['eingeschr_gemarkung'] = Array();
			while($rs=mysql_fetch_assoc($query)) {
				if($rs['Gemarkung'] != ''){
					$liste['eingeschr_gemeinde'][$rs['Gemeinde_ID']] = NULL;
					if($rs['Flur'] != '')$liste['eingeschr_gemarkung'][$rs['Gemarkung']][] = $rs['Flur'];
					else $liste['ganze_gemarkung'][$rs['Gemarkung']] = NULL;
				}
				else{
					$liste['ganze_gemeinde'][$rs['Gemeinde_ID']] = NULL;
				}
			}
		}
		return $liste;		
	}

	function getGemeinden($database) {
		if($database->type == 'mysql'){
			$ret=$this->database->getGemeindebyID_Name($this->id);
			if ($ret==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
			if (mysql_num_rows($ret[1])==0) {
				$GemeindeListe['ID'][0]=0;
			}
			else{
				while ($rs=mysql_fetch_array($ret[1])) {
					$GemeindeListe['ID'][]=$rs['ID'];
					$GemeindeListe['Name'][]=$rs['Name'];
				}
			}
		}
		elseif($database->type == 'postgresql'){
			$liste = $this->getGemeindeIDs();
			for($i = 0; $i < count($liste); $i++){
				$ret = $database->getGemeindeName($liste[$i]);
				$GemeindeListe['ID'][]=$liste[$i];
				$GemeindeListe['Name'][]=$ret[1]['name'];
			}
		}
		return $GemeindeListe;
	}

	function getUser($result = '') {
		# Lesen der User zur Stelle
		$sql = "
			SELECT
				user.*
			FROM
				user JOIN
				rolle ON user.ID = rolle.user_id
			WHERE
				rolle.stelle_id = " . $this->id . "
			ORDER BY Name
		";
		#echo "<br>Sql: " . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->getUser - Lesen der User zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=mysql_fetch_array($query)) {
				$user['ID'][]=$rs['ID'];
				$user['Bezeichnung'][]=$rs['Name'].', '.$rs['Vorname'];
				$user['email'][]=$rs['email'];
			}
			// Sortieren der User unter Berücksichtigung von Umlauten
			$sorted_arrays = umlaute_sortieren($user['Bezeichnung'], $user['ID']);
			$sorted_arrays2 = umlaute_sortieren($user['Bezeichnung'], $user['email']);
			$user['Bezeichnung'] = $sorted_arrays['array'];
			$user['ID'] = $sorted_arrays['second_array'];
			$user['email'] = $sorted_arrays2['second_array'];
		}
		if ($result == 'only_ids') {
			return $user['ID'];
		}
		else {
			return $user;
		}
	}

	function getWappen() {
		$sql ='SELECT wappen FROM stelle WHERE ID='.$this->id;
		$this->debug->write("<p>file:stelle.php class:stelle->getWappen - Abfragen des Wappens der Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		$rs=mysql_fetch_array($query);
		return $rs['wappen'];
	}
	
	function getWappenLink() {
		$sql ='SELECT wappen_link FROM stelle WHERE ID='.$this->id;
		$this->debug->write("<p>file:stelle.php class:stelle->getWappen - Abfragen des Wappens der Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		$rs=mysql_fetch_array($query);
		return $rs['wappen_link'];
	}
}
?>