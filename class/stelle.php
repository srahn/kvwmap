<?php
#####################################
# class_stelle #
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
		$this->debug->write("<p>file:users.php class:stelle->getsubMenues - Lesen der UnterMenuepunkte eines Menüpunktes:<br>".$sql,4);
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
	
	function Löschen() {
		$sql ='DELETE FROM stelle';
		$sql.=' WHERE ID = '.$this->id;
		$ret=$this->database->execSQL($sql,4, 0);
		if ($ret[0]) {
			$ret[1].='<br>Die Stelle konnte nicht gelöscht werden.<br>'.$ret[1];
		}
		return $ret;
	}

	function deleteMenue($menues) {
		$where_menue_id = (is_array($menues) and count($menues) > 0 ? ", `menue_id` IN (" . implode(", ", $menues) . ")" : "");
		# Löschen der Zuordnung der Menüs zu der Stelle
		$sql = "
			DELETE FROM
				`u_menue2stelle`
			WHERE
				`stelle_id` = " . $this->id .
				$where_menue_id . "
		";
		#echo '<br>Löschen der Menüpunkte der Stelle sql: ' . $sql;
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
		#echo '<br>Löschen der Menüpunkte der Rollen der Stellen sql: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle function:deleteMenue - Löschen der Menuepunkte der Rollen der Stelle in menue2rolle:<br>" . $sql, 4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	function deleteLayer($layer, $pgdatabase) {
		if($layer == 0){
			# löscht alle Layer der Stelle
			$sql ='DELETE FROM `used_layer` WHERE `Stelle_ID` = '.$this->id;
			$this->debug->write("<p>file:users.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			$sql ='DELETE FROM `layer_attributes2stelle` WHERE `stelle_id` = '.$this->id;
			$this->debug->write("<p>file:users.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			# Filter löschen
			$sql ='SELECT attributvalue FROM `u_attributfilter2used_layer` WHERE `type` = \'geometry\' AND `Stelle_ID` = '.$this->id;
			$this->debug->write("<p>file:users.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			while($rs=mysql_fetch_row($query)){
				$poly_id = $rs[0];
				if($poly_id != '')$pgdatabase->deletepolygon($poly_id);
			}
			$sql ='DELETE FROM `u_attributfilter2used_layer` WHERE `Stelle_ID` = '.$this->id;
			$this->debug->write("<p>file:users.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		else{
			# löscht die übergebenen Layer der Stelle
			for ($i=0;$i<count($layer);$i++) {
				$sql ='DELETE FROM `used_layer` WHERE `Stelle_ID` = '.$this->id.' AND `Layer_ID` = '.$layer[$i];
				$this->debug->write("<p>file:users.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
				$query=mysql_query($sql,$this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
				$sql ='DELETE FROM `layer_attributes2stelle` WHERE `stelle_id` = '.$this->id.' AND `layer_id` = '.$layer[$i];
				$this->debug->write("<p>file:users.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
				$query=mysql_query($sql,$this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; 	}			
				# Filter löschen
				$sql ='SELECT attributvalue FROM `u_attributfilter2used_layer` WHERE `type` = \'geometry\' AND `Stelle_ID` = '.$this->id.' AND `Layer_ID` = '.$layer[$i];
				$this->debug->write("<p>file:users.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
				$query=mysql_query($sql,$this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
				$rs=mysql_fetch_row($query);
				$poly_id = $rs[0];
				if($poly_id != '')$pgdatabase->deletepolygon($poly_id);
				$sql ='DELETE FROM `u_attributfilter2used_layer` WHERE `Stelle_ID` = '.$this->id.' AND `Layer_ID` = '.$layer[$i];
				$this->debug->write("<p>file:users.php class:stelle function:deleteLayer - Löschen der Layer der Stelle:<br>".$sql,4);
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
		$this->debug->write("<p>file:users.php class:stelle function:deleteDruckrahmen - Löschen der Druckrahmen der Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}
	
	function deleteStelleGemeinden() {
		# löscht alle StelleGemeinden der Stelle
		$sql ='DELETE FROM `stelle_gemeinden` WHERE `Stelle_ID` = '.$this->id;
		#echo '<br>'.$sql;
		$this->debug->write("<p>file:users.php class:stelle function:deleteStelleGemeinden - Löschen der StelleGemeinden der Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}
	
	function deleteFunktionen() {
		# löscht alle StelleGemeinden der Stelle
		$sql ='DELETE FROM `u_funktion2stelle` WHERE `stelle_id` = '.$this->id;
		#echo '<br>'.$sql;
		$this->debug->write("<p>file:users.php class:stelle function:deleteFunktionen - Löschen der Funktionen der Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	function getstellendaten() {
		$sql ='SELECT * FROM stelle';
		$sql.=' WHERE ID = '.$this->id;
		$this->debug->write("<p>file:users.php class:stelle->getstellendaten - Abfragen der Stellendaten<br>".$sql,4);
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
				`allowed_password_age` = 	'" . ($stellendaten['allowed_password_age'] != '' 	? $stellendaten['allowed_password_age'] : "6") . "'
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
		if ($order != '') $order = ' ORDER BY ' . $order;
		$sql = "
			SELECT
				s.ID,
				s.Bezeichnung
			FROM
				`stelle` AS s" .
			$order . "
		";

		#echo '<br>sql: ' . $sql;
		$this->debug->write("<p>file:users.php class:stelle->getStellen - Abfragen aller Stellen<br>" . $sql, 4);
		$query = mysql_query($sql, $this->database->dbConn);
		if ($query == 0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		while($rs = mysql_fetch_array($query)) {
			$stellen['ID'][]=$rs['ID'];
			$stellen['Bezeichnung'][]=$rs['Bezeichnung'];
		}
		return $stellen;
	}

	function getFunktionen() {
		# Abfragen der Funktionen, die in der Stelle ausgeführt werden dürfen
		$sql ='SELECT f.id,f.bezeichnung, 1 as erlaubt FROM u_funktionen AS f,u_funktion2stelle AS f2s';
		$sql.=' WHERE f.id=f2s.funktion_id AND f2s.stelle_id='.$this->id.' ORDER BY bezeichnung';
		$this->debug->write("<p>file:users.php class:stelle->getFunktionen - Fragt die Funktionen der Stelle ab:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4);
			$errmsg='Fehler bei der Abfrage der Funktionen für die Stelle';
		}
		else {
			while($rs=mysql_fetch_array($query)) {
				$funktionen[$rs['bezeichnung']]=$rs;
				$funktionen['array'][]=$rs;
			}
		}
		$this->funktionen=$funktionen;
		return $errmsg;
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
		$this->debug->write("<p>file:users.php class:stelle->isMenueAllowed - Guckt ob der Menuepunkt der Stelle zugeordnet ist:<br>".$sql,4);
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

	function addMenue($menue_ids) {
		# Hinzufügen von Menuepunkten zur Stelle
		$sql = "
			SELECT
				MAX(menue_order)
			FROM
				u_menue2stelle
			WHERE
				stelle_id = " . $this->id . "
		";
		$this->debug->write("<p>file:users.php class:stelle->addMenue - Lesen der maximalen menue_order der Menuepunkte der Stelle:<br>".$sql,4);
		$query = mysql_query($sql, $this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
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
			#echo '<br>sql: ' . $sql;
			$count++;
			$this->debug->write("<p>file:users.php class:stelle->addMenue - Hinzufügen von Menuepunkten zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }

			/* $sql ='SELECT id FROM u_menues WHERE obermenue = '.$menue_ids[$i];
			 $this->debug->write("<p>file:users.php class:stelle->addMenue - Lesen der Untermenuepunkte zu den Obermenuepunken zur Stelle:<br>".$sql,4);
			 $query=mysql_query($sql,$this->database->dbConn);
			 if ($query==0) {
			 $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
			 }
			 else{
			 while($rs=mysql_fetch_array($query)) {
			 $sql ="INSERT IGNORE INTO u_menue2stelle ( `stelle_id` , `menue_id` , `menue_order` ) VALUES ('".$this->id."', '".$rs[0]."', '".$count."')";
			 $count++;
			 $this->debug->write("<p>file:users.php class:stelle->addMenue - Hinzufügen von Menuepunkten zur Stelle:<br>".$sql,4);
			 $query1=mysql_query($sql,$this->database->dbConn);
			 if ($query1==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			 }
			 }
			 */
		}
		return 1;
	}

	function getMenue($ebene) {
		# Lesen der Menuepunkte zur Stelle
		$sql ='SELECT menue_id,';
		if ($this->language != 'german') {
			$sql.='`name_'.$this->language.'` AS ';
		}
		$sql.=' name, menueebene, `order` FROM u_menue2stelle, u_menues';
		$sql .=' WHERE stelle_id = '.$this->id;
		$sql .=' AND menue_id = u_menues.id';
		if($ebene != 0){
			$sql .=' AND menueebene = '.$ebene;
		}
		$sql .= ' ORDER BY menue_order';
		#echo $sql;
		$this->debug->write("<p>file:users.php class:stelle->getMenue - Lesen der Menuepunkte zur Stelle:<br>".$sql,4);
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
		return $menue;
	}

	function copyLayerfromStelle($layer_ids, $alte_stelle_id){
		# kopieren der Layer von einer Stelle
		for ($i=0;$i<count($layer_ids);$i++) {
			$sql ='INSERT IGNORE INTO used_layer ( `Stelle_ID` , `Layer_ID` , `queryable` , `drawingorder` , `minscale` , `maxscale` , `offsite` , `transparency`, `Filter` , `template` , `header` , `footer` , `symbolscale`, `logconsume`, `requires`, `privileg` )';
			$sql .= ' SELECT '.$this->id.', `Layer_ID` , `queryable` , `drawingorder` , `minscale` , `maxscale` , `offsite` , `transparency`, `Filter` , `template` , `header` , `footer` , `symbolscale`, `logconsume`, `requires`, `privileg` FROM used_layer WHERE Stelle_ID = '.$alte_stelle_id.' AND Layer_ID = '.$layer_ids[$i];
			$this->debug->write("<p>file:users.php class:stelle->copyLayerfromStelle - kopieren der Layer von einer Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			# Layerattributrechte mitkopieren
			$sql ='INSERT IGNORE INTO layer_attributes2stelle (layer_id, attributename, stelle_id, privileg, tooltip) ';
			$sql.='SELECT layer_id, attributename, '.$this->id.', privileg, tooltip FROM layer_attributes2stelle WHERE stelle_id = '.$alte_stelle_id.' AND layer_id = '.$layer_ids[$i];
			$this->debug->write("<p>file:users.php class:stelle->copyLayerfromStelle - kopieren der Layer von einer Stelle:<br>".$sql,4);
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
			$this->debug->write("<p>file:users.php class:stelle->addFunctions - Hinzufügen von Funktionen zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function removeFunctions(){
		# Entfernen von Funktionen zur Stelle
		$sql ='DELETE FROM u_funktion2stelle ';
		$sql.='WHERE stelle_id = '.$this->id;
		$this->debug->write("<p>file:users.php class:stelle->removeFunctions - Entfernen von Funktionen zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		return 1;
	}

	function addLayer($layer_ids, $drawingorder, $filter = '') {
		# Hinzufügen von Layern zur Stelle
		for ($i=0;$i<count($layer_ids);$i++) {
			$sql = "
				SELECT
					queryable, template, transparency, drawingorder, minscale, maxscale, symbolscale, offsite, requires, privileg, postlabelcache
				FROM
					layer
				WHERE
					Layer_ID = " . $layer_ids[$i];
			#echo '<br>sql: ' . $sql;
			$this->debug->write("<p>file:users.php class:stelle->addLayer - Hinzufügen von Layern zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			$rs = mysql_fetch_array($query);
			$queryable = $rs['queryable'];
			$template = $rs['template'];
			if($rs['transparency'] == ''){
				$rs['transparency'] = 'NULL';
			}
			$transparency = $rs['transparency'];
			$drawingorder = $rs['drawingorder'];
			$minscale = $rs['minscale'];
			$maxscale = $rs['maxscale'];
			$symbolscale = $rs['symbolscale'];
			$offsite = $rs['offsite'];
			$privileg = $rs['privileg'];
			$postlabelcache = $rs['postlabelcache'];
			if($rs['requires'] == '')$rs['requires']='NULL';
			$requires = $rs['requires'];
			$sql = "
				INSERT IGNORE INTO used_layer (
					`Stelle_ID`,
					`Layer_ID`,
					`queryable`,
					`drawingorder`,
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
					`postlabelcache`,
					`requires`
				)
				VALUES (
					'" . $this->id . "',
					'" . $layer_ids[$i] . "',
					'" . $queryable . "',
					'" . $drawingorder . "',
					'" . $minscale . "',
					'" . $maxscale . "',
					'" . $symbolscale . "',
					'" . $offsite . "',
					" . $transparency . ",
					'" . $filter . "',
					'" . $template . "',
					NULL,
					NULL,
					'" . $privileg . "',
					'" . $postlabelcache . "',
					" . $requires . "
				)
			";
			#echo '<br>' . $sql;
			$this->debug->write("<p>file:users.php class:stelle->addLayer - Hinzufügen von Layern zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			
			if(mysql_affected_rows() > 0){
				$sql = "INSERT IGNORE INTO layer_attributes2stelle (layer_id, attributename, stelle_id, privileg, tooltip) ";
				$sql.= "SELECT ".$layer_ids[$i].", name, ".$this->id.", privileg, query_tooltip FROM layer_attributes WHERE layer_id = ".$layer_ids[$i]." AND privileg IS NOT NULL";
				#echo $sql.'<br>';
				$this->debug->write("<p>file:users.php class:stelle->addLayer - Hinzufügen von Layern zur Stelle:<br>".$sql,4);
				$query=mysql_query($sql,$this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			}
		}
		return 1;
	}
	
	function updateLayerParams(){
		$sql = "UPDATE stelle SET selectable_layer_params = ";
		$sql.= "(SELECT GROUP_CONCAT(id) ";
		$sql.= "FROM `layer_parameter` as p, used_layer as ul, layer as l ";
		$sql.= "WHERE ul.Stelle_ID = stelle.ID ";
		$sql.= "AND ul.Layer_ID = l.Layer_ID ";
		$sql.= "AND locate(concat('$', p.key), concat(l.Data, l.pfad, l.classitem, l.classification)) > 0) ";
		$sql.= "WHERE stelle.ID = ".$this->id;
		$this->debug->write("<p>file:users.php class:stelle->updateLayerParams:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		$sql = "UPDATE rolle SET layer_params = ";
		$sql.= "(SELECT GROUP_CONCAT(concat('\"', `key`, '\":\"', default_value, '\"')) ";
		$sql.= "FROM layer_parameter p, stelle ";
		$sql.= "WHERE FIND_IN_SET(p.id, stelle.selectable_layer_params) ";
		$sql.= "AND stelle.ID = rolle.stelle_id) ";
		$sql.= "WHERE rolle.layer_params IS NULL ";
		$sql.= "AND rolle.stelle_id = ".$this->id;
		$this->debug->write("<p>file:users.php class:stelle->updateLayerParams:<br>".$sql,4);
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
		$this->debug->write("<p>file:users.php class:stelle->updateLayer - Aktualisieren der LayerzuStelle-Eigenschaften:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
	}

	function updateLayerdrawingorder($formvars){
		# Aktualisieren der LayerzuStelle-Eigenschaften
		$sql = 'UPDATE used_layer SET Layer_ID = '.$formvars['selected_layer_id'];
		$sql .= ', drawingorder = '.$formvars['drawingorder'];
		$sql .= ' WHERE Stelle_ID = '.$formvars['selected_stelle_id'].' AND Layer_ID = '.$formvars['selected_layer_id'];
		#echo $sql.'<br>';
		$this->debug->write("<p>file:users.php class:stelle->updateLayerdrawingorder - Aktualisieren der LayerzuStelle-Eigenschaften:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
	}

	function getLayers($group, $order = NULL) {
		# Lesen der Layer zur Stelle
		$sql ='SELECT layer.Layer_ID, layer.Gruppe, Name, used_layer.drawingorder FROM used_layer, layer, u_groups';
		$sql .=' WHERE stelle_id = '.$this->id;
		$sql .=' AND layer.Gruppe = u_groups.id';
		$sql .=' AND layer.Layer_ID = used_layer.Layer_ID';
		if($group != NULL){
			$sql .= ' AND layer.Gruppe = '.$group;
		}
		if($order != NULL){
			$sql .= ' ORDER BY '.$order;
		}
		#echo $sql;
		$this->debug->write("<p>file:users.php class:stelle->getLayers - Lesen der Layer zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		else{
			while($rs=mysql_fetch_array($query)) {
				$layer['ID'][]=$rs['Layer_ID'];
				$layer['Bezeichnung'][]=$rs['Name'];
				$layer['drawingorder'][]=$rs['drawingorder'];
				$layer['Gruppe'][]=$rs['Gruppe'];
			}
			if($order == 'Name'){
				// Sortieren der Layer unter Berücksichtigung von Umlauten
				$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
				$sorted_layer['Bezeichnung'] = $sorted_arrays['array'];
				$sorted_layer['ID'] = $sorted_arrays['second_array'];
				
				$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['drawingorder']);
				$sorted_layer['drawingorder'] = $sorted_arrays['second_array'];
				
				$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['Gruppe']);
				$sorted_layer['Gruppe'] = $sorted_arrays['second_array'];
				$layer = $sorted_layer;
			}
		}
		return $layer;
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
		$this->debug->write("<p>file:users.php class:stelle->getqueryablePostgisLayers - Lesen der abfragbaren PostgisLayer zur Stelle:<br>".$sql,4);
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
			$sql .= 'SELECT -id as Layer_ID, concat(substring( `Name` FROM 1 FOR locate( ")", `Name` )), CASE WHEN Typ = "search" THEN " -Suchergebnis-" ELSE " -Shape-Import-" END), "", Gruppe, " ", `connection` FROM rollenlayer';
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
		$this->debug->write("<p>file:users.php class:stelle->getqueryableVectorLayers - Lesen der abfragbaren VektorLayer zur Stelle:<br>".$sql,4);
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
			$this->debug->write("<p>file:users.php class:stelle->addAktivLayer - Hinzufügen von aktiven Layern zur Stelle:<br>".$sql,4);
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
			$this->debug->write("<p>file:users.php class:stelle->setAktivLayer - Speichern der aktiven Layer zur Stelle:<br>".$sql,4);
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
			$this->debug->write("<p>file:users.php class:stelle->setQueryStatus - Speichern des Abfragestatus der Layer zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql,$this->database->dbConn);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
		return 1;
	}

	function getLayer($Layer_id) {
		# Abfragen der Layer der Stelle
		$sql ='SELECT l.*, ul.* FROM layer AS l, used_layer AS ul';
		$sql.=' WHERE l.Layer_ID=ul.Layer_ID AND Stelle_ID='.$this->id;
		if ($Layer_id!='') {
			$sql.=' AND l.Layer_ID = "'.$Layer_id.'"';
		}
		#echo $sql;
		$this->debug->write("<p>file:users.php class:stelle->getLayer - Abfragen der Layer zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		while ($rs=mysql_fetch_array($query)) {
			$layer[]=$rs;
		}
		return $layer;
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
					$attributename = array_pop($explosion);
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
		$this->debug->write("<p>file:users.php class:stelle->set_layer_privileges - Speichern der Layerrechte zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
	}

	function set_attributes_privileges($formvars, $attributes){
		# erst alles löschen zu diesem Layer und Stelle
		$sql = 'DELETE FROM layer_attributes2stelle WHERE ';
		$sql.= 'layer_id = '.$formvars['selected_layer_id'].' AND ';
		$sql.= 'stelle_id = '.$this->id;
		$this->debug->write("<p>file:users.php class:stelle->set_attributes_privileges - Speichern des Layerrechte zur Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		# dann Attributrechte eintragen
		for($i = 0; $i < count($attributes['type']); $i++){
			if($formvars['privileg_'.$attributes['name'][$i].$this->id] !== ''){
				$sql = 'INSERT INTO layer_attributes2stelle SET ';
				$sql.= 'layer_id = '.$formvars['selected_layer_id'].', ';
				$sql.= 'stelle_id = '.$this->id.', ';
				$sql.= 'attributename = "'.$attributes['name'][$i].'", ';
				$sql.= 'privileg = '.$formvars['privileg_'.$attributes['name'][$i].$this->id];
				if($formvars['tooltip_'.$attributes['name'][$i].$this->id] == 'on'){
					$sql.= ', tooltip = 1';
				}
				else{
					$sql.= ', tooltip = 0';
				}
				$this->debug->write("<p>file:users.php class:stelle->set_attributes_privileges - Speichern des Layerrechte zur Stelle:<br>".$sql,4);
				$query=mysql_query($sql,$this->database->dbConn);
				if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			}
		}
	}

	function getGemeindeIDs() {
		$sql = 'SELECT Gemeinde_ID, Gemarkung, Flur FROM stelle_gemeinden WHERE Stelle_ID = '.$this->id;
		#echo $sql;
		$this->debug->write("<p>file:users.php class:stelle->getGemeindeIDs - Lesen der GemeindeIDs zur Stelle:<br>".$sql,4);
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

	function getUser() {
		# Lesen der User zur Stelle
		$sql ='SELECT user.* FROM user, rolle';
		$sql .=' WHERE rolle.stelle_id = '.$this->id;
		$sql .=' AND rolle.user_id = user.ID';
		$sql .= ' ORDER BY Name';
		$this->debug->write("<p>file:users.php class:stelle->getUser - Lesen der User zur Stelle:<br>".$sql,4);
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
		return $user;
	}

	function getWappen() {
		$sql ='SELECT wappen FROM stelle WHERE ID='.$this->id;
		$this->debug->write("<p>file:users.php class:stelle->getWappen - Abfragen des Wappens der Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		$rs=mysql_fetch_array($query);
		return $rs['wappen'];
	}
	
	function getWappenLink() {
		$sql ='SELECT wappen_link FROM stelle WHERE ID='.$this->id;
		$this->debug->write("<p>file:users.php class:stelle->getWappen - Abfragen des Wappens der Stelle:<br>".$sql,4);
		$query=mysql_query($sql,$this->database->dbConn);
		if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		$rs=mysql_fetch_array($query);
		return $rs['wappen_link'];
	}
}
?>