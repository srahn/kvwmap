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
	var $language;

	function __construct($id, $database) {
		global $debug;
		global $log_mysql;
		$this->debug = $debug;
		$this->log = $log_mysql;
		$this->id = $id;
		$this->database = $database;
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
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else {
			while ($rs = $this->database->result->fetch_array()) {
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
    #echo '<p>SQL zur Abfrage des Stellennamens: ' . $sql;
    $this->debug->write("<p>file:stelle.php class:stelle->getName - Abfragen des Namens der Stelle:<br>",4);
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
				*,";
		if ($this->language != 'german' AND $this->language != ''){
      $sql.='`Bezeichnung_'.$this->language.'` AS ';
    }
    $sql.="
				Bezeichnung
			FROM
				stelle
			WHERE
				ID = " . $this->id . "
		";
		$this->debug->write('<p>file:stelle.php class:stelle->readDefaultValues - Abfragen der Default Parameter der Karte zur Stelle:<br>', 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		$rs = $this->database->result->fetch_array();
		$this->Bezeichnung=$rs['Bezeichnung'];
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
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }

		/*		erstmal rausgenommen, weil sonst beim Ändern einer Stelle die Menüeinstellungen der Nutzer, insbesondere des Default-Nutzers verloren gehen
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
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		*/
		return 1;
	}

	function deleteLayer($layer, $pgdatabase) {
		#echo 'stelle.php deleteLayer ids: ' . implode(', ', $layer);
		if($layer == 0){
			# löscht alle Layer der Stelle
			$sql ='DELETE FROM `used_layer` WHERE `Stelle_ID` = '.$this->id;
			$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			$sql ='DELETE FROM `layer_attributes2stelle` WHERE `stelle_id` = '.$this->id;
			$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			# Filter löschen
			$sql ='SELECT attributvalue FROM `u_attributfilter2used_layer` WHERE `type` = \'geometry\' AND `Stelle_ID` = '.$this->id;
			$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			while ($rs = $this->database->result->fetch_row()) {
				$poly_id = $rs[0];
				if($poly_id != '')$pgdatabase->deletepolygon($poly_id);
			}
			$sql ='DELETE FROM `u_attributfilter2used_layer` WHERE `Stelle_ID` = '.$this->id;
			$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		else{
			# löscht die übergebenen Layer der Stelle
			for ($i=0;$i<count($layer);$i++) {
				$sql ='DELETE FROM `used_layer` WHERE `Stelle_ID` = '.$this->id.' AND `Layer_ID` = '.$layer[$i];
				$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
				$sql ='DELETE FROM `layer_attributes2stelle` WHERE `stelle_id` = '.$this->id.' AND `layer_id` = '.$layer[$i];
				$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; 	}			
				# Filter löschen
				$sql ='SELECT attributvalue FROM `u_attributfilter2used_layer` WHERE `type` = \'geometry\' AND `Stelle_ID` = '.$this->id.' AND `Layer_ID` = '.$layer[$i];
				$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
				$rs = $this->database->result->fetch_array();
				$poly_id = $rs[0];
				if($poly_id != '')$pgdatabase->deletepolygon($poly_id);
				$sql ='DELETE FROM `u_attributfilter2used_layer` WHERE `Stelle_ID` = '.$this->id.' AND `Layer_ID` = '.$layer[$i];
				$this->debug->write("<p>file:stelle.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			}
		}
		return 1;
	}
	
	function deleteDruckrahmen() {
		# löscht alle Druckrahmenzuordnungen der Stelle
		$sql ='DELETE FROM `druckrahmen2stelle` WHERE `stelle_id` = '.$this->id;
		#echo '<br>'.$sql;
		$this->debug->write("<p>file:stelle.php class:stelle function:deleteDruckrahmen - Löschen der Druckrahmen der Stelle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}
	
	function deleteStelleGemeinden() {
		# löscht alle StelleGemeinden der Stelle
		$sql ='DELETE FROM `stelle_gemeinden` WHERE `Stelle_ID` = '.$this->id;
		#echo '<br>'.$sql;
		$this->debug->write("<p>file:stelle.php class:stelle function:deleteStelleGemeinden - Löschen der StelleGemeinden der Stelle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}
	
	function deleteFunktionen() {
		# löscht alle StelleGemeinden der Stelle
		$sql ='DELETE FROM `u_funktion2stelle` WHERE `stelle_id` = '.$this->id;
		#echo '<br>'.$sql;
		$this->debug->write("<p>file:stelle.php class:stelle function:deleteFunktionen - Löschen der Funktionen der Stelle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	function getstellendaten() {
		$sql = "
			SELECT
				*
			FROM
				stelle
			WHERE
				ID = " . $this->id . "
		";
		#echo '<p>SQL zum Abfragen der Stellendaten: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->getstellendaten - Abfragen der Stellendaten<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		$rs=$this->database->result->fetch_array();
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
		$sql.=', minxmax= "'.$stellendaten['minxmax'].'"';
		$sql.=', minymax= "'.$stellendaten['minymax'].'"';
		$sql.=', maxxmax= "'.$stellendaten['maxxmax'].'"';
		$sql.=', maxymax= "'.$stellendaten['maxymax'].'"';
		$sql.=', epsg_code= "'.$stellendaten['epsg_code'].'"';
		$sql.=', start= "'.$stellendaten['start'].'"';
		$sql.=', stop= "'.$stellendaten['stop'].'"';
		if ($stellendaten['postgres_connection_id'] != '') {
			$sql .= ', postgres_connection_id = ' . $stellendaten['postgres_connection_id'];
		}
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
			$this->database->execSQL($sql,4, 0);
			#echo $sql;
			if (!$this->database->success) {
				# Fehler bei der Datenbankanfrage
				$ret[1] .= '<br>Die Stellendaten konnten nicht eingetragen werden.<br>' . $this->database->errormessage;
			}
			else {
				# Abfrage erfolgreich durchgeführt, übergeben der stelle_id zur Rückgabe der Funktion
				$rs = $this->database->result->fetch_array();
				$ret[1] = $rs['ID'];
			}
		}
		return $ret;
	}

	# Stelle ändern
	function Aendern($stellendaten) {
		$stelle = ($stellendaten['id'] != '' ? "`ID` = " . $stellendaten['id'] . ", " : "");
		$wappen = (value_of($stellendaten, 'wappen') != '' ? "`wappen` = '" . $stellendaten['wappen'] . "', " : "");
		$sql = "
			UPDATE
				stelle
			SET" .
				$stelle .
				$wappen . "
				`Bezeichnung` = '" . $stellendaten['bezeichnung'] . "',
				`Referenzkarte_ID` = " . $stellendaten['Referenzkarte_ID'] . ",
				`minxmax` = '" . $stellendaten['minxmax'] . "',
				`minymax` = '" . $stellendaten['minymax'] . "',
				`maxxmax` = '" . $stellendaten['maxxmax'] . "',
				`maxymax` = '" . $stellendaten['maxymax'] . "',
				`epsg_code` = '" . $stellendaten['epsg_code'] . "',
				`start` = '" . $stellendaten['start'] . "',
				`stop` = '" . $stellendaten['stop'] . "',
				`postgres_connection_id` = " . ($stellendaten['postgres_connection_id'] != '' ? $stellendaten['postgres_connection_id'] : 'NULL') . ",
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
				`check_client_ip` =				'" . ($stellendaten['checkClientIP'] 			== '1'	? "1" : "0") . "',
				`check_password_age` =		'" . ($stellendaten['checkPasswordAge'] 	== '1'	? "1" : "0") . "',
				`use_layer_aliases` = 		'" . (value_of($stellendaten, 'use_layer_aliases') 	== '1'	? "1" : "0") . "',
				`hist_timestamp` = 				'" . (value_of($stellendaten, 'hist_timestamp') 		== '1'	? "1" : "0") . "',
				`allowed_password_age` = 	'" . ($stellendaten['allowedPasswordAge'] != '' 	? $stellendaten['allowedPasswordAge'] : "6") . "',
				`default_user_id` = " . ($stellendaten['default_user_id'] != '' ? $stellendaten['default_user_id'] : 'NULL') . "
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

	function getStellen($order, $user_id = 0, $where = "1") {
		global $admin_stellen;
		$sql = "
			SELECT
				s.ID,
				s.Bezeichnung
			FROM
				`stelle` AS s" . (($user_id > 0 AND !in_array($this->id, $admin_stellen)) ? " LEFT JOIN
				`rolle` AS r ON s.ID = r.stelle_id
				" : "") . "
			WHERE " .
				$where . (($user_id > 0 AND !in_array($this->id, $admin_stellen)) ? " AND
				(r.user_id = " . $user_id . " OR r.stelle_id IS NULL)" : "") . "
			ORDER BY " .
				($order != '' ? "`" . $order . "`" : "s.`Bezeichnung`") . "
		";
		#echo '<br>sql: ' . $sql;

		$this->debug->write("<p>file:stelle.php class:stelle->getStellen - Abfragen aller Stellen<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		while($rs = $this->database->result->fetch_array()) {
			$stellen['ID'][] = $rs['ID'];
			$stellen['Bezeichnung'][] = $rs['Bezeichnung'];
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
				h.`child_id`= ".$this->id." "
			.$order;
		#echo '<br>stelle.php getParents sql:<br>' . $sql;

		$this->debug->write("<p>file:stelle.php class:stelle->getParents - Abfragen aller Elternstellen<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return array(); }
		while($rs = $this->database->result->fetch_assoc()) {
			$parents[] = ($return == 'only_ids' ? $rs['ID'] : $rs);
		};
		return $parents;
	}

	function getChildren($parent_id, $order = '', $return = '', $recursive = false) {
		$children = array();
		$sql = "
			SELECT
				s.`ID`,
				s.`Bezeichnung`
			FROM
				`stelle` AS s JOIN
				`stellen_hierarchie` AS h ON (s.`ID` = h.`child_id`)
			WHERE
				h.`parent_id`= ".$parent_id." "
			.$order;
		#echo '<br>sql: ' . $sql;

		$this->debug->write("<p>file:stelle.php class:getChildren - Abfragen aller Kindstellen<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return array(); }
		$result = $this->database->result;
		while($rs = $result->fetch_assoc()) {
			$children[] = ($return == 'only_ids' ? $rs['ID'] : $rs);
			if($recursive){
				$children = array_merge($children, $this->getChildren($rs['ID'], $order, $return, true));
			}
		};
		return $children;
	}

	function getFunktionen($return = '') {
		$funktionen = array();
		# Abfragen der Funktionen, die in der Stelle ausgeführt werden dürfen
		$sql ='SELECT f.id,f.bezeichnung, 1 as erlaubt FROM u_funktionen AS f,u_funktion2stelle AS f2s';
		$sql.=' WHERE f.id=f2s.funktion_id AND f2s.stelle_id='.$this->id.' ORDER BY bezeichnung';
		$this->debug->write("<p>file:stelle.php class:stelle->getFunktionen - Fragt die Funktionen der Stelle ab:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4);
			$errmsg='Fehler bei der Abfrage der Funktionen für die Stelle';
			return $errmsg;
		}
		else {
			while($rs=$this->database->result->fetch_array()) {
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
		if ($this->funktionen == NULL) {
			$this->getFunktionen();
		}
		if ($this->funktionen[$functionname]['erlaubt']) {
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
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4);
			$errmsg='Fehler bei der Ueberpruefung des Menuepunkts für die Stelle';
		}
		else{
			$rs=$this->database->result->fetch_array();
		}
		if($rs[0] != '') {
			return 1;
		}
		else {
			return 0;
		}
	}

	function getFlurstueckeAllowed($FlurstKennz, $database) {
		include_once(PLUGINS.'alkis/model/alkis.php');
		$GemeindenStelle = $this->getGemeindeIDs();
		if($GemeindenStelle != NULL){
			$alkis = new alkis($database);
			$ret=$alkis->getFlurstKennzByGemeindeIDs($GemeindenStelle, $FlurstKennz);
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
		&$layer
	) {
		include_once(CLASSPATH . 'datendrucklayout.php');
		$results = array();
		$old_parents = $this->getParents('ORDER BY `ID`', 'only_ids');
		$document = new Document($this->database);
		$ddl = new ddl($this->database);
				
		# immer alle Elternstellen und deren Zuordnungen entfernen und wieder neu hinzufügen
		foreach($old_parents AS $drop_parent_id) {
			$parent_stelle = new stelle($drop_parent_id, $this->database);
			$menues = array_values(array_diff($menues, $parent_stelle->getMenue(0, 'only_ids')));
			$functions = array_values(array_diff($functions, $parent_stelle->getFunktionen('only_ids')));
			$layouts = array_values(array_diff($layouts, $ddl->load_layouts($drop_parent_id, '', '', '', 'only_ids')));
			$frames = array_values(array_diff($frames, $document->load_frames($drop_parent_id, false, 'only_ids')));
			$parent_layer = $parent_stelle->getLayer('', 'only_ids');
			$layer = array_values(array_diff($layer, $parent_layer));
			$this->dropParent($drop_parent_id);
		}
		
		# Füge Einstellungen der Elternstellen zur Stelle hinzu
		foreach($selected_parents AS $new_parent_id) {
			$parent_stelle = new stelle($new_parent_id, $this->database);
			$menues = $this->merge_menues($menues, $parent_stelle->getMenue(0));
			$functions = array_values(array_unique(array_merge($functions, $parent_stelle->getFunktionen('only_ids'))));
			$layouts = array_values(array_unique(array_merge($layouts, $ddl->load_layouts($new_parent_id, '', '', '', 'only_ids'))));
			$frames = array_values(array_unique(array_merge($frames, $document->load_frames($new_parent_id, false, 'only_ids'))));
			$layer = array_values(array_unique(array_merge($layer, $parent_stelle->getLayer('', 'only_ids'))));
			$results[] = $this->addParent($new_parent_id);
		}
		return $results;
	}

	function merge_menues($menues, $new_menues){
		$menue_objects = empty($menues) ? array() : Menue::find($this, ' id IN ('.implode(',', $menues).')', 'FIELD(id, '.implode(',', $menues).')');
		$insert_index = 0;
		for($i = 0; $i < count($new_menues['ID']); $i++){
			if($new_menues['menueebene'][$i] == 1){
				while($menue_objects[$insert_index]->data['menueebene'] == 1 AND $menue_objects[$insert_index]->data['order'] < $new_menues['ORDER'][$i]){
					$insert_index++;
				}
			}
			array_splice($menue_objects, $insert_index, 0, [(object)['data' => ['id' => $new_menues['ID'][$i], 'order' => $new_menues['ORDER'][$i], 'name' => $new_menues['Bezeichnung'][$i]]]]);
			$insert_index++;
		}
		foreach($menue_objects as $menue){
			$result[] = $menue->data['id'];
		}
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
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4);
			return array(
				'type' => 'error',
				'message' => 'Fehler beim Eintragen der Elternstelle: ' . $this->databse->errormessage
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
		$this->debug->write("<p>file:stelle.php class:stelle->dropParent - Delete Parent Id: " . $drop_parent_id . " von Stelle Id: " . $this->id . "<br>", 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4);
			return array(
				'type' => 'error',
				'message' => 'Fehler beim Löschen der Elternstelle: ' . $this->databse->errormessage
			);
		}
		return array(
			'type' => 'notice',
			'message' => 'Elternstelle ID: ' . $drop_parent_id . ' erfolgreich entfernt.'
		);
	}
	
	function addChild($child_id) {
		$sql = "
			INSERT INTO `stellen_hierarchie` (
				`parent_id`,
				`child_id`
			)
			VALUES (
				" . $this->id . ",
				" . $child_id . "
			)
		";
		#echo 'Sql: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->addChild - Add Child Id: " . $child_id . " zu Stelle Id: " . $this->id . "<br>", 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4);
			return array(
				'type' => 'error',
				'message' => 'Fehler beim Eintragen der Kindstelle: ' . $this->databse->errormessage
			);
		}

		return array(
			'type' => 'notice',
			'message' => 'Kindstelle ID: ' . $child_id . ' erfolgreich zugewiesen.'
		);
	}	
	
	function dropChild($drop_child_id) {
		$sql = "
			DELETE FROM `stellen_hierarchie`
			WHERE
				`parent_id` = " . $this->id . " AND
				`child_id` = " . $drop_child_id . "
		";
		$this->debug->write("<p>file:stelle.php class:stelle->dropChild - Delete Child Id: " . $drop_child_id . " von Stelle Id: " . $this->id . "<br>", 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4);
			return array(
				'type' => 'error',
				'message' => 'Fehler beim Löschen der Kindstelle: ' . $this->databse->errormessage
			);
		}
		return array(
			'type' => 'notice',
			'message' => 'Kindstelle ID: ' . $drop_child_id . ' erfolgreich entfernt.'
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
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else {
			$rs = $this->database->result->fetch_array();
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
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function getMenue($ebene, $return = '') {
		global $language;
		$menue['ID'] = array();
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
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=$this->database->result->fetch_array()) {
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
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			# Layerattributrechte mitkopieren
			$sql ='INSERT IGNORE INTO layer_attributes2stelle (layer_id, attributename, stelle_id, privileg, tooltip) ';
			$sql.='SELECT layer_id, attributename, '.$this->id.', privileg, tooltip FROM layer_attributes2stelle WHERE stelle_id = '.$alte_stelle_id.' AND layer_id = '.$layer_ids[$i];
			$this->debug->write("<p>file:stelle.php class:stelle->copyLayerfromStelle - kopieren der Layer von einer Stelle:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function addFunctions($function_ids){
		# Hinzufügen von Funktionen zur Stelle
		for ($i=0;$i<count($function_ids);$i++) {
			$sql ='INSERT IGNORE INTO u_funktion2stelle ( `funktion_id` , `stelle_id`)';
			$sql.="VALUES ('".$function_ids[$i]."', '".$this->id."')";
			$this->debug->write("<p>file:stelle.php class:stelle->addFunctions - Hinzufügen von Funktionen zur Stelle:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function removeFunctions(){
		# Entfernen von Funktionen zur Stelle
		$sql ='DELETE FROM u_funktion2stelle ';
		$sql.='WHERE stelle_id = '.$this->id;
		$this->debug->write("<p>file:stelle.php class:stelle->removeFunctions - Entfernen von Funktionen zur Stelle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	function addLayer($layer_ids, $drawingorder, $filter = '', $assign_default_values = false) {
		#echo '<br>stelle.php addLayer ids: ' . implode(', ', $layer_ids);
		# Hinzufügen von Layern zur Stelle
		for ($i=0;$i<count($layer_ids);$i++){
			$insert = "(
					`Stelle_ID`,
					`Layer_ID`,
					`queryable`,
					`use_geom`,
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
				)";
			# Einstellungen von der Elternstelle übernehmen
			$sql = "INSERT INTO used_layer " . $insert . "
				SELECT
					'" . $this->id . "',
					'" . $layer_ids[$i] . "',
					queryable,
					use_geom,
					drawingorder, 
					legendorder, 
					minscale, 
					maxscale, 
					symbolscale, 
					offsite, 
					transparency, 
					filter,
					template, 
					header,
					footer,
					`privileg`,
					`export_privileg`,
					postlabelcache,
					requires
				FROM
					used_layer as l,
					stellen_hierarchie
				WHERE
					layer_id = " . $layer_ids[$i] . " AND
					stelle_id = parent_id AND
					child_id = " . $this->id . "
				ON DUPLICATE KEY UPDATE 
					queryable = l.queryable, 
					use_geom = l.use_geom, 
					drawingorder = l.drawingorder, 
					legendorder = l.legendorder, 
					minscale = l.minscale, 
					maxscale = l.maxscale, 
					symbolscale = l.symbolscale, 
					offsite = l.offsite, 
					transparency = l.transparency, 
					template = l.template, 
					postlabelcache = l.postlabelcache,
					`privileg` = l.`privileg`,
					`export_privileg` = l.`export_privileg`,
					requires = l.requires";
			#echo $sql.'<br>';
			$this->debug->write("<p>file:stelle.php class:stelle->addLayer - Hinzufügen von Layern zur Stelle:<br>".$sql,4);
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			if ($this->database->mysqli->affected_rows == 0) {
				# wenn nicht von Elternstelle übernommen, Defaulteinstellungen übernehmen
				$sql = "INSERT " . (!$assign_default_values ? "IGNORE" : "") . " INTO used_layer " . $insert . "
					SELECT
						'" . $this->id . "',
						'" . $layer_ids[$i] . "',
						queryable,
						use_geom,
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
						`privileg`,
						`export_privileg`,
						postlabelcache,
						requires
					FROM
						layer as l
					WHERE
						l.Layer_ID = ".$layer_ids[$i];
					if($assign_default_values){
						$sql .= "
						ON DUPLICATE KEY UPDATE 
							queryable = l.queryable, 
							use_geom = l.use_geom, 
							drawingorder = l.drawingorder, 
							legendorder = l.legendorder, 
							minscale = l.minscale, 
							maxscale = l.maxscale, 
							symbolscale = l.symbolscale, 
							offsite = l.offsite, 
							transparency = l.transparency, 
							template = l.template, 
							postlabelcache = l.postlabelcache,
							requires = l.requires
						";
					}
				#echo '<br>SQL zur Zuordnung eines Layers zur Stelle: ' . $sql;
				$this->debug->write("<p>file:stelle.php class:stelle->addLayer - Hinzufügen von Layern zur Stelle:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			}

			if (!$assign_default_values AND $this->database->mysqli->affected_rows > 0) {
				$insert = "
					INSERT INTO layer_attributes2stelle (
						layer_id,
						attributename,
						stelle_id,
						privileg,
						tooltip
					)
				";
				# Rechte von der Elternstelle übernehmen
				$sql = $insert . "
					SELECT 
						layer_id,
						attributename,
						" . $this->id . ",
						privileg,
						tooltip
					FROM
						layer_attributes2stelle l,
						stellen_hierarchie
					WHERE
						layer_id = " . $layer_ids[$i] . " AND
						stelle_id = parent_id AND
						child_id = " . $this->id . "
					ON DUPLICATE KEY UPDATE
						layer_id = l.layer_id, 
						attributename = l.attributename, 
						stelle_id = " . $this->id . ", 
						privileg = l.privileg, 
						tooltip = l.tooltip
					";
				#echo $sql.'<br>';
				$this->debug->write("<p>file:stelle.php class:stelle->addLayer - Hinzufügen von Layern zur Stelle:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
				if ($this->database->mysqli->affected_rows != 0) {
					# löschen der Einträge für "kein Zugriff" Rechte
					$sql = "
					DELETE l 
					FROM 
						layer_attributes2stelle l 
						LEFT JOIN stellen_hierarchie ON l.stelle_id = child_id 
						LEFT JOIN layer_attributes2stelle l2 ON 
							l2.layer_id = " . $layer_ids[$i] . " AND 
							l2.stelle_id = parent_id AND 
							l.attributename = l2.attributename 
					WHERE
						l.layer_id = " . $layer_ids[$i] . " AND 
						l.stelle_id = " . $this->id . " AND 
						l2.attributename IS NULL;
						";
					#echo $sql.'<br>';
					$this->debug->write("<p>file:stelle.php class:stelle->addLayer - Hinzufügen von Layern zur Stelle:<br>".$sql,4);
					$this->database->execSQL($sql);
					if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
				}
				else {
					# wenn nicht von Elternstelle übernommen, Defaultrechte übernehmen
					$sql = $insert . "
						SELECT 
							" . $layer_ids[$i] . ",
							name,
							" . $this->id . ",
							privileg,
							query_tooltip 
						FROM 
							layer_attributes 
						WHERE 
							layer_id = ".$layer_ids[$i]." AND 
							privileg IS NOT NULL";
				}
				#echo $sql.'<br>';
				$this->debug->write("<p>file:stelle.php class:stelle->addLayer - Hinzufügen von Layern zur Stelle:<br>".$sql,4);
				$this->database->execSQL($sql);
				if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			}
		}
		return 1;
	}
	
	function updateLayerParams() {
		/*
		$sql = "
			UPDATE
				stelle
			SET
				selectable_layer_params = COALESCE(
					(
						SELECT GROUP_CONCAT(id)
						FROM (
							SELECT DISTINCT id
							FROM `layer_parameter` as p, used_layer as ul, layer as l
							WHERE
								ul.Stelle_ID = " . $this->id . " AND
								ul.Layer_ID = l.Layer_ID AND
								locate(concat('$', p.key), concat(l.Name, l.alias, l.connection, l.Data, l.pfad, l.classitem, l.classification)) > 0
						) as foo
					),
					''
				)
			WHERE
				stelle.ID = " . $this->id . "
		";
		*/
		$sql = "
			UPDATE stelle
			SET
				selectable_layer_params = COALESCE((
					SELECT GROUP_CONCAT(id)
					FROM
						(
							SELECT DISTINCT
								id
							FROM
								(
									SELECT
										id
									FROM
										`layer_parameter` as p,
										used_layer as ul,
										layer as l
									WHERE
										ul.Stelle_ID = " . $this->id . " AND
										ul.Layer_ID = l.Layer_ID AND
										locate(
											concat('$', p.key),
											concat(l.Name, COALESCE(l.alias, ''), l.schema, l.connection, l.Data, l.pfad, l.classitem, l.classification, COALESCE(l.connection, ''), COALESCE(l.processing, ''))
										) > 0
									UNION
									SELECT
										p.id
									FROM
										u_menues AS m JOIN
										u_menue2stelle AS m2s ON (m.id = m2s.menue_id) JOIN
										layer_parameter AS p ON (
											locate(
												concat('$', p.key),
												m.links
											) > 0
										)
									WHERE
										m2s.stelle_id = " . $this->id . "
								) AS params
						) AS foo
					),
					''
				)
			WHERE stelle.ID = " . $this->id . "
		";

		#echo '<br>SQL zur Aktualisierung der selectable_layer_params: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->updateLayerParams:<br>".$sql,4);

		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }

		$sql = "
			UPDATE
				rolle
			SET
				layer_params = concat(coalesce(layer_params, ''), 
					coalesce(
						concat(
							CASE WHEN coalesce(layer_params, '') = '' THEN '' ELSE ',' END,
							(SELECT
								GROUP_CONCAT(concat('\"', `key`, '\":\"', default_value, '\"'))
							FROM
								layer_parameter p, stelle
							WHERE
								FIND_IN_SET(p.id, stelle.selectable_layer_params) AND
								locate(concat('\"', p.key, '\"'), coalesce(layer_params, '')) = 0 AND
								stelle.ID = rolle.stelle_id
							)
						),
						''
					)
				)
			WHERE
				rolle.stelle_id = " . $this->id . "
		";
		#echo '<br>SQL zum Aktualisieren der Layerparameter in den Rollen: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->updateLayerParams:<br>".$sql,4);

		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
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
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
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
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
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
    $this->database->execSQL($sql);
    if (!$this->database->success) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    while ($rs=$this->database->result->fetch_assoc()) {
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
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			$i = 0;
			while($rs=$this->database->result->fetch_assoc()) {
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

	function getqueryablePostgisLayers($privileg, $export_privileg = NULL, $no_subform_layers = false, $layer_id = NULL){
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
		if($layer_id != NULL){
			$sql .= ' AND layer.Layer_ID = '.$layer_id;
		}
		$sql .= ' ORDER BY Name) as foo ';
		if($privileg > 0 AND $no_subform_layers){
			$sql .= 'WHERE subformfk IS NULL OR privilegfk = 1';			# nicht editierbare SubformFKs ausschliessen
		}
		#echo $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->getqueryablePostgisLayers - Lesen der abfragbaren PostgisLayer zur Stelle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=$this->database->result->fetch_array()) {
				if($rs['alias'] != '' AND $this->useLayerAliases){
					$rs['Name'] = $rs['alias'];
				}
				$layer['ID'][]=$rs['Layer_ID'];
				$layer['Bezeichnung'][]=$rs['Name'];
				$layer['export_privileg'][]=$rs['export_privileg'];
			}
			// Sortieren der User unter Berücksichtigung von Umlauten
			if($layer['Bezeichnung'] != NULL){
				$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
				$sorted_arrays2 = umlaute_sortieren($layer['Bezeichnung'], $layer['export_privileg']);
				$layer['Bezeichnung'] = $sorted_arrays['array'];
				$layer['ID'] = $sorted_arrays['second_array'];
				$layer['export_privileg'] = $sorted_arrays2['second_array'];
			}
		}
		return $layer;
	}

	function getqueryableVectorLayers($privileg, $user_id, $group_id = NULL, $layer_ids = NULL, $rollenlayer_type = NULL, $use_geom = NULL, $only_geom_layer = false){
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
		if($only_geom_layer){
			$sql .=' AND layer.Datentyp < 4';
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
			$sql .= 'SELECT -id as Layer_ID, concat(`Name`, CASE WHEN Typ = "search" THEN " -Suchergebnis-" ELSE " -eigener Import-" END), "", Gruppe, " ", `connection` FROM rollenlayer';
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
		$this->database->execSQL($sql);		
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=$this->database->result->fetch_assoc()){
				 
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
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
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
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
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
			$this->database->execSQL($sql);
			if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	/*
	* Abfragen der Layer der Stelle
	*/
	function getLayer($Layer_id, $result = '') {
		$layer = array();
		$sql = "
			SELECT
				l.*,
				ul.*,
				parent_id,
				ul2.Stelle_ID as used_layer_parent_id
			FROM
				layer AS l 
				JOIN used_layer AS ul ON l.Layer_ID = ul.Layer_ID
				LEFT JOIN stellen_hierarchie ON child_id = " . $this->id . "
				LEFT JOIN used_layer AS ul2 ON 
					l.Layer_ID = ul2.Layer_ID AND	
					ul2.Stelle_ID = parent_id
			WHERE
				ul.Stelle_ID = " . $this->id .
				($Layer_id != '' ? " AND l.Layer_ID = " . $Layer_id : '') . "
		";
		#echo '<br>getLayer Sql:<br>'. $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->getLayer - Abfragen der Layer zur Stelle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		while ($rs=$this->database->result->fetch_assoc()) {
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
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . $PHP_SELF . " Zeile: " . __LINE__, 4); return 0; }
		while ($rs = $this->database->result->fetch_array()) {
			$privileges[$rs['attributename']] = $rs['privileg'];
			$privileges['tooltip_' . $rs['attributename']] = $rs['tooltip'];
			$privileges['attributenames'][] = $rs['attributename'];
		}
		return $privileges;
	}

	function parse_path($database, $path, $privileges, $attributes = NULL){
		$newattributesstring = '';
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
				$explosion = explode('.', $fieldstring[$i]);
				$attributename = trim($explosion[count($explosion)-1]);
				$real_attributename = $fieldstring[$i];
			}
			if(value_of($privileges, trim($attributename, '"')) != ''){
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

	function set_layer_privileges($layer_id, $privileg, $exportprivileg){
		$sql = 'UPDATE used_layer SET privileg = "'.$privileg.'", export_privileg = "'.$exportprivileg.'" WHERE ';
		$sql.= 'layer_id = '.$layer_id.' AND stelle_id = '.$this->id;
		$this->debug->write("<p>file:stelle.php class:stelle->set_layer_privileges - Speichern der Layerrechte zur Stelle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
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
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . $PHP_SELF . " Zeile: " . __LINE__, 4); return 0; }
		# dann Attributrechte eintragen
		for ($i = 0; $i < count($attributes['type']); $i++) {
			if ($formvars['used_layer_parent_id'] != '') {
				# wenn Eltern-Stelle für diesen Layer vorhanden, deren Rechte übernehmen
				$formvars['privileg_' . $attributes['name'][$i] .'_'. $this->id] = $formvars['privileg_' . $attributes['name'][$i] .'_'. $formvars['used_layer_parent_id']];
				$formvars['tooltip_' . $attributes['name'][$i] .'_'. $this->id] = $formvars['tooltip_' . $attributes['name'][$i] .'_'. $formvars['used_layer_parent_id']];
			}
			if($formvars['privileg_'.$attributes['name'][$i].'_'.$this->id] !== '') {
				$sql = "
					INSERT INTO
						layer_attributes2stelle
					SET 
						`layer_id` = " . $formvars['selected_layer_id'] . ",
						`stelle_id` = " . $this->id . ",
						`attributename` = '" . $attributes['name'][$i] . "',
						`privileg` = " . $formvars['privileg_' . $attributes['name'][$i] .'_'. $this->id] .",
						`tooltip`= " . ($formvars['tooltip_' . $attributes['name'][$i] .'_'. $this->id] == 'on' ? "1" : "0") . "
				";
				#echo '<br>Sql: ' . $sql;
				$this->debug->write("<p>file:stelle.php class:stelle->set_attributes_privileges - Speichern des Layerrechte zur Stelle:<br>" . $sql, 4);
				$this->database->execSQL($sql);
				if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . $PHP_SELF . " Zeile: " . __LINE__, 4); return 0; }
			}
		}
	}

	function getGemeindeIDs() {
		$sql = 'SELECT Gemeinde_ID, Gemarkung, Flur FROM stelle_gemeinden WHERE Stelle_ID = '.$this->id;
		#echo $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->getGemeindeIDs - Lesen der GemeindeIDs zur Stelle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if($this->database->result->num_rows > 0){
			$liste['ganze_gemeinde'] = Array();
			$liste['eingeschr_gemeinde'] = Array();
			$liste['ganze_gemarkung'] = Array();
			$liste['eingeschr_gemarkung'] = Array();
			while($rs=$this->database->result->fetch_assoc()) {
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

/*
	function getGemeinden($database) {
		if($database->type == 'mysql'){
			$ret=$this->database->getGemeindebyID_Name($this->id);
			if ($ret==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
			if ($this->database->result->num_rows == 0) {
				$GemeindeListe['ID'][0]=0;
			}
			else{
				while ($rs = $this->database->result->fetch_array()) {
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
*/

	function getUser($result = '') {
		$user['ID'] = array();
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
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=$this->database->result->fetch_array()) {
				$user['ID'][]=$rs['ID'];
				$user['Bezeichnung'][]=$rs['Name'].', '.$rs['Vorname'];
				$user['email'][]=$rs['email'];
			}
			if(!empty($user['ID'])){
				// Sortieren der User unter Berücksichtigung von Umlauten
				$sorted_arrays = umlaute_sortieren($user['Bezeichnung'], $user['ID']);
				$sorted_arrays2 = umlaute_sortieren($user['Bezeichnung'], $user['email']);
				$user['Bezeichnung'] = $sorted_arrays['array'];
				$user['ID'] = $sorted_arrays['second_array'];
				$user['email'] = $sorted_arrays2['second_array'];
			}
		}
		if ($result == 'only_ids') {
			return $user['ID'];
		}
		else {
			return $user;
		}
	}

	function getWappen() {
		$sql = "
			SELECT
				wappen
			FROM
				stelle
			WHERE
				ID = " . $this->id . "
		";
		$this->debug->write("<p>file:stelle.php class:stelle->getWappen - Abfragen des Wappens der Stelle:<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__, 4); return 0; }
		$rs = $this->database->result->fetch_array();
		return $rs['wappen'];
	}

	function getWappenLink() {
		$sql = "
			SELECT
				wappen_link
			FROM
				stelle
			WHERE ID = " . $this->id . "
		";
		$this->debug->write("<p>file:stelle.php class:stelle->getWappen - Abfragen des Wappens der Stelle:<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__, 4); return 0; }
		$rs = $this->database->result->fetch_array();
		return $rs['wappen_link'];
	}

	/**
	* Function reads all mapfiles in directory WMS_MAPFILE_PATH . $this->Stelle->id
	* @return array An array of mapfiles in the mapfiles directory of the stelle
	*/
	function get_mapfiles() {
		$mapfiles = array();
		if (is_dir(WMS_MAPFILE_PATH . $this->id)) {
			$mapfiles = array_diff(scandir(WMS_MAPFILE_PATH . $this->id), array('.', '..'));
		}
		#echo '<p>Stelle->get_mapfile returns mapfiles: ' . print_r($mapfiles, true);
		return $mapfiles;
	}
}
?>