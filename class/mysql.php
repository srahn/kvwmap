<?php
###################################################################
# Lizenz                                                          #
#                                                                 #
# Copyright (C) 2004  Peter Korduan                               #
#                                                                 #
# This program is free software; you can redistribute it and/or   #
# modify it under the terms of the GNU General Public License as  #
# published by the Free Software Foundation; either version 2 of  #
# the License, or (at your option) any later version.             #
#                                                                 #
# This program is distributed in the hope that it will be useful, #
# but WITHOUT ANY WARRANTY; without even the implied warranty of  #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the    #
# GNU General Public License for more details.                    #
#                                                                 #
# You should have received a copy of the GNU General Public       #
# License along with this program; if not, write to the Free      #
# Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,  #
# MA 02111-1307, USA.                                             #
#                                                                 #
# Kontakt:                                                        #
# pkorduan@gmx.de peter.korduan@auf.uni-rostock.de                #
###################################################################
##################################################
# Klasse Datenbank für ALB-Info Modell und MySQL #
##################################################
class database {
  var $ist_Fortfuehrung;
  var $debug;
  var $loglevel;
  var $logfile;
  var $commentsign;
  var $blocktransaction;

# begintransaction() {
# close() {
# committransaction() {
# create_update_dump() {
# database() {
# deleteAddressLagen() {
# deleteBuchungenByHistFlurstuecke() {
# deleteBuchungenByHistGrundbuecher() {
# deleteEigentuemerByHistGrundbuecher() {
# deleteGrundbuecher() {
# deleteGrundstueckeByHistGrundbuecher() {
# deleteHistAdressen() {
# deleteHistAnlieger() {
# deleteHistBaulasten() {
# deleteHistFlurstuecke() {
# deleteHistHinweise() {
# deleteHistHistorie() {
# deleteHistKlassifizierungen() {
# deleteHistLagen() {
# deleteHistNutzungen() {
# deleteHistTexte() {
# deleteHistVerfahren() {
# deleteNewBuchungen() {
# deleteNewEigentuemer() {
# deleteNewGrundstuecke() {
# deleteNewHistorien() {
# deleteOldAdressen() {
# deleteOldAnlieger() {
# deleteOldBaulasten() {
# deleteOldHinweise() {
# deleteOldKlassifizierungen() {
# deleteOldLagen() {
# deleteOldNutzungen() {
# deleteOldTexte() {
# deleteOldVerfahren() {
# deleteTempHistVorgaenger() {
# execSQL($sql,$debuglevel) {
# getAdressenListeByExtent($GebaeudeAdressenListe,$FlurstueckAdressenListe)
# getALBData($FlurstKennz) {
# getAffectedRows() {
# getAktualitaetsNr($FlurstKennz) {
# getAmtsgericht($GemkgSchl) {
# getAnzFluren($neu)
# getAnzFlurstuecke() {
# getAnzGrundbuecher($AktualitaetsNr) {
# getAnzHistorie($Vorgaenger,$Nachfolger)
# getAnzNewFlurstuecke() {
# getBaulasten($FlurstKennz) {
# getBuchungen($FlurstKennz) {
# getEigentuemerliste($FlurstKennz,$Bezirk,$Blatt,$BVNR) {
# getFilterPolygons($used_layer_id) {
# getFilteredUsedLayer($layername) {
# getFinanzamt($FlurstKennz) {
# getFlstFlaeche($FlurstKennz) {
# getFlurkarte($FlurstKennz) {
# getFlurstuecksKennzByRaumbezug($FlurstKennz,$Raumbezug,$Wert)
# getForstamt($FlurstKennz) {
# getFreiText($FlurstKennz) {
# getGemarkungName($GemkgSchl) {
# getGrundbuchbezirk($FlurstKennz) {
# getHausNrListe($GemID,$StrID,$PolygonWKTString,$HausNr,$order)
# getHausNummern($FlurstKennz,$Strasse) {
# getHinweise($FlurstKennz) {
# getHistorie($Vorgaenger,$Nachfolger)
# getKlassifizierung($FlurstKennz) {
# getKreisName($KreisSchl) {
# getLage($FlurstKennz) {
# getNutzung($FlurstKennz) {
# getPruefKZ($FlurstKennz) {
# getRow($select,$from,$where) {
# getStrassen($FlurstKennz) {
# getStrassenListe($GemID,$extent,$order)
# getVerfahren($FlurstKennz) {
# getVorgaenger($FlurstKennz) {
# insertALKAusgestaltungen($colnames,$row) {
# insertALKFlurst($colnames,$row) {
# insertALKGebaeude($colnames,$row) {
# insertALKNutzungen($colnames,$row) {
# insertALKUpdateMessage($anzflurstuecke,$anzgebaeude,$anznutzungen,$anzausgestaltungen) {
# insertAbgabeZeitraum($dategrundausstattung,$zeitraumvon,$zeitraumbis) {
# insertAdmKreis($colnames,$row) {
# insertAdresse($FlurstKennz,$Gemeinde,$Strasse,$HausNr) {
# insertAmtsgericht($Amtsgericht,$Name) {
# insertAnlieger($FlurstKennz,$Kennung,$AnlFlstKennz,$AnlFlstPruefz) {
# insertAusfuehrendeStelle($AusfStelle,$Name) {
# insertBaulast($FlurstKennz,$BlattNr) {
# insertBemerkgZumVerfahren($VerfBemerkung,$Bezeichnung) {
# insertBuchung($FlurstKennz,$Bezirk,$Blatt,$BVNR,$ErbbaurechtsHinw) {
# insertBuchungsart($Buchungsart,$Bezeichnung) {
# insertEigentuemer($Bezirk,$Blatt,$NamensNr,$Eigentuemerart,$Anteilsverhaeltnis,$lfd_Nr_Name) {
# insertEigentuemerart($Eigentuemerart,$Bezeichnung) {
# insertFinanzamt($Finanzamt,$Name) {
# insertFlurstueck($FlurstKennz,$GemkgSchl,$FlurNr,$Pruefzeichen) {
# insertForstamt($Forstamt,$Name) {
# insertGemarkung($GemkgSchl,$Gemeinde,$Amtsgericht,$GemkgName) {
# insertGemeinde($Gemeinde,$Name) {
# insertGrundbuch($Bezirk,$Blatt,$AktualitaetsNr,$Pruefzeichen) {
# insertGrundbuchbezirk($GrundbuchbezSchl,$Amtsgericht,$Bezeichnung) {
# insertGrundstueck($Bezirk,$Blatt,$BVNR,$Buchungsart) {
# insertHinweis($FlurstKennz,$Hinweis) {
# insertHinweisart($HinwZFlst,$Bezeichnung) {
# insertHistorie($Vorgaenger,$Nachfolger) {
# insertKatasteramt($Katasteramt,$ArtAmt,$Name) {
# insertKlassifizierung($FlurstKennz,$TabKenn,$Klass,$KlassFlaeche,$KlassAngabe) {
# insertKlassifizierungsart($TabKenn,$Klass,$Bezeichnung,$Abkuerzung) {
# insertKreis($Kreis,$Name) {
# insertLage($FlurstKennz,$lfdNr,$Lage) {
# insertName($lfd_Nr_Name,$Satzunterart,$Namen) {
# insertNeueGrundbuecher() {
# insertNewAdressen() {
# insertNewAnlieger() {
# insertNewBaulasten() {
# insertNewBuchungen() {
# insertNewEigentuemer() {
# insertNewGrundstuecke() {
# insertNewHinweise() {
# insertNewHistorien() {
# insertNewKlassifizierungen() {
# insertNewLagen() {
# insertNewNamen() {
# insertNewNutzungen() {
# insertNewTexte() {
# insertNewVerfahren() {
# insertNutzung($FlurstKennz,$Nutzungsart,$NutzungFlaeche) {
# insertNutzungsart($Nutzungsart,$Bezeichnung,$Abkuerzung) {
# insertStrasse($Gemeinde,$Strasse,$Name) {
# insertText($FlurstKennz,$lfdNr,$freierText) {
# insertVerfahren($FlurstKennz,$AusfStelle,$VerfNr,$VerfBem) {
# loadDataInFile($filename,$tablename) {
# open() {
# optimizeALKAusgestaltungen() {
# optimizeALKFlurst() {
# readLastUpdateDate() {
# replaceAmtsgerichte() {
# replaceAusfuehrendeStellen() {
# replaceBemerkungenZumVerfahren() {
# replaceBuchungsarten() {
# replaceEigentuemerArten() {
# replaceFinanzaemter() {
# replaceFlurstuecke() {
# replaceForstaemter() {
# replaceGemarkungen() {
# replaceGemeinden() {
# replaceGrundbuchbezirke() {
# replaceHinweise() {
# replaceKatasteraemter() {
# replaceKlassifizierungen() {
# replaceKreise() {
# replaceNutzungsarten() {
# replaceStrassen() {
# rollbacktransaction() {
# setFilter($layer_id,$stelle_id,$filter) {
# setFortfuehrung($ist_Fortfuehrung) {
# setLogLevel($loglevel,$logfile) {
# truncateALKAusgestaltungen() {
# truncateALKFlurst() {
# truncateALKGebaeude() {
# truncateALKNutzungen() {
# truncateAdmKreise() {
# truncateAll() {
# updateEigentuemer() {
# updateFluren() {
# updateFlurstueck($FlurstKennz,$Status,$Entsteh,$LetzFF,$Flaeche,$AktuNr,$Karte,$BauBlock,$KoorRW,$KoorHW,$Forstamt,$Finanzamt) {
# updateGrundbuch($Bezirk,$Blatt,$Zusatz_Eigentuemer,$Bestandsflaeche) {
# updateGrundstueck($Bezirk,$Blatt,$BVNR,$Anteil,$AuftPlanNr,$Sondereigentum) {
# updateLfdNrName() {
# updateName($lfd_Nr_Name,$Satzunterart,$Namen)
# updateNewGrundstuecke()
# updateTempAdressTable()

  function database() {
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

  function login_user($username, $passwort){
  	$sql = "SELECT login_name FROM user WHERE login_name = BINARY('".addslashes($username)."') AND passwort = '".md5($passwort)."'";
  	$sql.=' AND (("'.date('Y-m-d h:i:s').'" >= start AND "'.date('Y-m-d h:i:s').'" <= stop)';
    $sql.=' OR ';
    $sql.='(start="0000-00-00 00:00:00" AND stop="0000-00-00 00:00:00"))';		# Zeiteinschränkung wird nicht berücksichtigt.
  	#echo $sql;
  	$ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $ret = mysql_fetch_array($ret[1]);
    if($ret[0] != ''){
    	return true;
    }
    else{
    	return false;
    }
  }
  
  function read_colors(){
  	$sql = "SELECT * FROM colors";
  	#echo $sql;
  	$ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    if($ret[0]==0){
      while($row = mysql_fetch_array($ret[1])){
        $colors[] = $row;
      }
    }
    return $colors;
  }
  
  function read_color($id){
  	$sql = "SELECT * FROM colors WHERE id = ".$id;
  	#echo $sql;
  	$ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    if($ret[0]==0){
      $color = mysql_fetch_array($ret[1]);
    }
    return $color;
  }

	function create_new_gast($gast_stelle){
    $loginname = "";
    $laenge=10;
    $string="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    mt_srand((double)microtime()*1000000);
    for ($i=1; $i <= $laenge; $i++) {
    	$loginname .= substr($string, mt_rand(0,strlen($string)-1), 1);
    }
    
    $sql = "INSERT INTO user (`login_name`, `Name`, `Vorname`, `Namenszusatz`, `passwort`, `ips`, `Funktion`, `stelle_id`)
            VALUES('".$loginname."' , 'gast' , 'gast', '', 'd4061b1486fe2da19dd578e8d970f7eb', '', 'gast', '$gast_stelle');";
		$query = mysql_query($sql);
		$sql = "SELECT LAST_INSERT_ID();";
    $query = mysql_query($sql);
    $user_id = mysql_fetch_row($query);
    $id = $user_id[0];
    
    $sql = "INSERT INTO `rolle` (`user_id`,`stelle_id`,`nImageWidth`,`nImageHeight`,`minx`,`miny`,`maxx`,`maxy`,`nZoomFactor`,`selectedButton`,`epsg_code`,`active_frame`,`last_time_id`,`language`,`charset`,`hidemenue`,`fontsize_gle`,`highlighting`)
                    SELECT ".$id.", ".$gast_stelle.", '800', '600', minxmax, minymax, maxxmax, maxymax, '2', 'zoomin', epsg_code, ".DEFAULT_DRUCKRAHMEN_ID." , '0000-00-00 00:00:00', 'german', 'windows-1252', '0', '13', '0' FROM stelle WHERE ID = ".$gast_stelle;
    $query = mysql_query($sql);
    
    $sql = "INSERT INTO `u_groups2rolle` (`user_id`,`stelle_id`,`id`,`status`) SELECT ".$id.", ".$gast_stelle.", id, '0' FROM u_groups";
    $query = mysql_query($sql);

    $sql = "INSERT INTO `u_menue2rolle` ( `user_id` , `stelle_id` , `menue_id` , `status` ) SELECT ".$id.", ".$gast_stelle.", menue_id, '0' FROM u_menue2stelle WHERE stelle_id = ".$gast_stelle;
		$query = mysql_query($sql);


    $sql = "INSERT INTO `u_rolle2used_layer` ( `user_id` , `stelle_id` , `layer_id` , `aktivStatus` , `queryStatus` , `showclasses` , `logconsume` ) ";
    $sql.= "SELECT ".$id.", ".$gast_stelle.", Layer_ID, start_aktiv, start_aktiv, 1, 0 FROM used_layer WHERE Stelle_ID=".(int)$gast_stelle;
		$query = mysql_query($sql);
		
    $sql = "UPDATE u_groups2rolle, u_rolle2used_layer, layer SET u_groups2rolle.status = 1 ";
		$sql.= "WHERE u_groups2rolle.user_id = ".$id." "; 
		$sql.= "AND u_groups2rolle.stelle_id = ".$gast_stelle." "; 
		$sql.= "AND u_rolle2used_layer.user_id = ".$id." ";
		$sql.= "AND u_rolle2used_layer.stelle_id = ".$gast_stelle." ";
		$sql.= "AND u_rolle2used_layer.aktivStatus = '1' ";
		$sql.= "AND u_rolle2used_layer.layer_id = layer.Layer_ID ";
		$sql.= "AND layer.Gruppe = u_groups2rolle.id";
    $query = mysql_query($sql);
    
    $gast['username'] = $loginname;
    $gast['passwort'] = 'gast';
    return $gast;
  }

  function getRow($select,$from,$where) {
    $select = str_replace("\'x\'","'x'",$select);
    $select = str_replace("\' \'","' '",$select);
    $select = str_replace("\',\'","','",$select);
		$sql = "SELECT ".$select;
    $sql.= " FROM ".$from;
    $sql.= " WHERE ".$where;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $ret[1]=mysql_fetch_assoc($ret[1]);
    return $ret;
  }

	function create_insert_dump($table, $extra, $sql){
		# Funktion liefert das Ergebnis einer SQL-Abfrage als INSERT-Dump für die Tabelle "$table" 
		# über $extra kann ein Feld angegeben werden, welches nicht mit in das INSERT aufgenommen wird
		# dieses Feld wird jedoch auch mit abgefragt und separat zurückgeliefert
		$this->debug->write("<p>file:kvwmap class:database->create_insert_dump :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }

    $feld_anzahl = mysql_num_fields($query);
    for($i = 0; $i < $feld_anzahl; $i++){
    	$meta = mysql_fetch_field($query,$i);
    	# array mit feldnamen
    	$felder[$i] = $meta->name;
    	if($meta->name == 'connectiontype'){
    		$connectiontype = $i;
    	}
    	if($meta->name == 'connection'){
    		$connection = $i;
    	}
    }
    while($rs = mysql_fetch_array($query)){
    	$insert = '';
    	if($rs[$connectiontype] == 6){
    		$rs[$connection] = '@connection';
    	}
    	$insert .= 'INSERT INTO '.$table.' (';
    	for($i = 0; $i < $feld_anzahl; $i++){
    		if($felder[$i] != $extra){
    			$insert .= "`".$felder[$i]."`";
    			if($feld_anzahl-1 > $i){$insert .= ',';}
    		}
    	}
    	$insert .= ') VALUES(';
    	for($i = 0; $i < $feld_anzahl; $i++){
    		if($felder[$i] != $extra){
    			if(strpos($rs[$i], '@') === 0){
	    			$insert .= addslashes($rs[$i]);
	    		}
	    		else{
	    			if(mysql_field_type($query, $i) != 'string' AND mysql_field_type($query, $i) != 'blob' AND $rs[$i] == ''){
	    				$insert .= "NULL";
	    			}else{
    					$insert .= "'".addslashes($rs[$i])."'";
	    			}
	    		}
	    		if($feld_anzahl-1 > $i){$insert .= ',';}
    		}
    		else{
    			$dump['extra'][] = $rs[$i];
    		}
    	}
    	$insert .= ');';
    	$dump['insert'][] = $insert;
    }
   return $dump;
	}

  function create_update_dump($table){
		# Funktion erstellt zu einer Tabelle einen Update-Dump und liefert ihn als String zurück
		$sql = 'SELECT * FROM '.$table;
		$this->debug->write("<p>file:kvwmap class:database->create_update_dump :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }

    $feld_anzahl = mysql_num_fields($query);
    for($i = 0; $i < $feld_anzahl; $i++){
    	$meta = mysql_fetch_field($query,$i);
    	# array mit feldnamen
    	$felder[$i] = $meta->name;
    	# array mit indizes der primary-keys
    	if($meta->primary_key == 1){
    		$keys[] = $i;
    	}
    }
    while($rs = mysql_fetch_array($query)){
    	$update = 'UPDATE '.$table.' SET ';
    	$update .= $felder[0].' = '.$rs[0];
    	for($i = 1; $i < $feld_anzahl; $i++){
    		$update .= ", ".$felder[$i]." = '".$rs[$i]."'";
    	}
    	$update .= ' WHERE ';
    	for($i = 0; $i < count($keys); $i++){
    		$update .= $felder[$keys[$i]].' = '.$rs[$keys[$i]].' AND ';
    	}
    	$update .= ' (1=1);';
    	$dump .= "\n".$update;
    }
   return $dump;
	}

 
##################################################
# Funktionen der Anwendung kvwmap
#################################################

 # function getFilteredUsedLayer($layername) {
 #   # liefert die id´s der Zuordnung zwischen Layern und Stellen (used_layer_id),
 #   # die mit einem Polygon gefiltert werden sollen
 #   $sql ="SELECT DISTINCT ul.used_layer_id,l.data,ul.Stelle_ID FROM polygon AS p, polygon_used_layer AS pul";
 #   $sql.=", used_layer AS ul, layer AS l WHERE p.polygon_id = pul.polygon_id";
 #   $sql.=" AND pul.used_layer_id = ul.used_layer_id AND ul.Layer_ID = l.Layer_ID";
 #   $sql.=" AND l.Name = '".$layername."'";
 #   return $this->execSQL($sql, 4, 0);
 # }

  function getFilteredUsedLayer($layername) {
    # geändert 2005-12-15 pk
    # liefert layer_id und stelle_id aus used_layer,
    # die mit einem Polygon gefiltert werden sollen
    $sql ="SELECT DISTINCT ul.Stelle_ID,ul.Layer_ID,l.data FROM polygon AS p, u_polygon2used_layer AS pul";
    $sql.=", used_layer AS ul, layer AS l WHERE p.polygon_id = pul.polygon_id";
    $sql.=" AND pul.stelle_id = ul.Stelle_ID AND pul.layer_id = ul.Layer_ID AND ul.Layer_ID = l.Layer_ID";
    $sql.=" AND l.Name = '".$layername."'";
    return $this->execSQL($sql, 4, 0);
  }

 # function getFilterPolygons($used_layer_id) {
 #   # liefert Shapdateinamen und Namen des Polygons mit denen ein Filter
 #   # für used_layer_id berechnet werden soll
 #   $sql ="SELECT p.polygonname,p.datei,p.feldname FROM polygon AS p, polygon_used_layer AS pul";
 #   $sql.=" WHERE p.polygon_id = pul.polygon_id";
 #   $sql.=" AND pul.used_layer_id=".$used_layer_id;
 #   return $this->execSQL($sql, 4, 0);
 # }

  function getFilterPolygons($layer_id,$stelle_id) {
    # geändert 2005-12-15 pk
    # liefert Shapdateinamen und Namen des Polygons mit denen ein Filter
    # für layer_id und stelle_id in Tabelle used_layer berechnet werden soll
    $sql ="SELECT p.polygonname,p.datei,p.feldname FROM polygon AS p, u_polygon2used_layer AS pul";
    $sql.=" WHERE p.polygon_id = pul.polygon_id";
    $sql.=" AND pul.layer_id=".(int)$layer_id." AND pul.stelle_id=".(int)$stelle_id;
    return $this->execSQL($sql, 4, 0);
  }

 # function setFilter($used_layer_id,$filter) {
 #   $sql ="UPDATE used_layer SET Filter='".$filter."'";
 #   $sql.=" WHERE used_layer_id=".$used_layer_id;
 #   return $this->execSQL($sql, 4, 0);
 # }

  function setFilter($layer_id,$stelle_id,$filter) {
    $sql ="UPDATE used_layer SET Filter='".$filter."'";
    $sql.=" WHERE layer_id=".(int)$layer_id." AND stelle_id=".(int)$stelle_id;
    return $this->execSQL($sql, 4, 0);
  }

####################################################
# database Funktionen
###########################################################
  function open() {
    $this->debug->write("<br>MySQL Verbindung öffnen mit Host: ".$this->host." User: ".$this->user,4);
    $this->dbConn=mysql_connect($this->host,$this->user,$this->passwd);
    $this->debug->write("Datenbank mit ID: ".$this->dbConn." und Name: ".$this->dbName." auswählen.",4);
    return mysql_select_db($this->dbName,$this->dbConn);
  }

  function close() {
    $this->debug->write("<br>MySQL Verbindung mit ID: ".$this->dbConn." schließen.",4);
    if (LOG_LEVEL>0){
    	$this->logfile->close();
    }
    return mysql_close($this->dbConn);
  }

  function begintransaction() {
    # Starten einer Transaktion
    # initiates a transaction block, that is, all statements
    # after BEGIN command will be executed in a single transaction
    # until an explicit COMMIT or ROLLBACK is given
    if ($this->blocktransaction==0) {
      $ret=$this->execSQL('START TRANSACTION',4, 1);
    }
    return $ret;
  }

  function rollbacktransaction() {
    # Rückgängigmachung aller bisherigen Änderungen in der Transaktion
    # und Abbrechen der Transaktion
    # rolls back the current transaction and causes all the updates
    # made by the transaction to be discarded
    if ($this->blocktransaction==0) {
      $ret=$this->execSQL('ROLLBACK',4, 1);
    }
    return $ret;
  }

  function committransaction() {
    # Gültigmachen und Beenden der Transaktion
    # commits the current transaction. All changes made by the transaction
    # become visible to others and are guaranteed to be durable if a crash occurs
    if ($this->blocktransaction==0) {
      $ret=$this->execSQL('COMMIT',4, 1);
    }
    return $ret;
  }

  function vacuum() {
    # Hier sollten alle Tabellen optimiert werden können
    # in MySQL müsste man den Befehl OPTIMIZE für alle Tabellen einzeln aufrufen
    # eine Idee wie man das umgehen kann?
    # in postgres gibt es dafür den vacuum-Befehl
    if (!$this->vacuumOff) {
    	# OPTIMIZE ALL
    }
    return $ret;
  }

  function getAffectedRows($query) {
    return mysql_affected_rows();
  }

  function setFortfuehrung($ist_Fortfuehrung) {
    $this->ist_Fortfuehrung=$ist_Fortfuehrung;
    if ($this->ist_Fortfuehrung) {
      $this->tableprefix=TEMPTABLEPREFIX;
    }
    else {
      $this->tableprefix="";
    }
  }

  function setLogLevel($loglevel,$logfile) {
  	if ($loglevel==-1) {
  		# setzen der Defaulteinstellungen
  		$this->loglevel=$this->defaultloglevel;
  		$this->logfile=$this->defaultlogfile;
  	}
  	else {
  		$this->loglevel=$loglevel;
  		$this->logfile=$logfile;
  	}
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
        $ret[1]="<b>Fehler bei SQL Anweisung:</b><br>".$sql."<br>".mysql_error($query);
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
}