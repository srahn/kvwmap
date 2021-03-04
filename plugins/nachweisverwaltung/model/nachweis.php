<?php
###################################################################
# kvwmap - Kartenserver für Kreisverwaltungen                     #
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
# peter.korduan@gdi-service.de                                    #
# stefan.rahn@gdi-service.de                                      #
###################################################################
###################
# Klasse Nachweis #
###################

class Nachweis {
  var $id;
  var $Gemeinde;
  var $Tag;
  var $Monat;
  var $Jahr;
  var $Formulardatenisvalide;
  var $debug;
  var $database;
  var $client_epsg;
    
  function __construct($database, $client_epsg) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
    $this->client_epsg=$client_epsg;
  }

	function check_documentpath($old_dataset){		
		$ret=$this->getNachweise($old_dataset['id'],'','','','','','','','bySingleID','','');
		if ($ret=='') {
			$this->Dokumente[0]['artname'] = strtolower($this->hauptarten[$this->Dokumente[0]['hauptart']]['abkuerzung']);
			$this->Dokumente[0]['Bilddatei_name'] = $this->Dokumente[0]['link_datei'];
			$this->Dokumente[0]['Blattnr'] = $this->Dokumente[0]['blattnummer'];
			$formvars['zieldateiname']=$this->getZielDateiName($this->Dokumente[0]);
			$oldpath = $this->Dokumente[0]['link_datei'];
			$newpath = NACHWEISDOCPATH.$this->Dokumente[0]['flurid'].'/'.$this->buildNachweisNr($this->Dokumente[0][NACHWEIS_PRIMARY_ATTRIBUTE], $this->Dokumente[0][NACHWEIS_SECONDARY_ATTRIBUTE]).'/'.$this->Dokumente[0]['artname'].'/'.$formvars['zieldateiname'];
			#echo $oldpath.'<br>';
			#echo $newpath.'<br>';
			if($oldpath != $newpath){
				$this->adjust_documentpath($oldpath, $newpath);
				$this->aktualisierenDokument($old_dataset['id'],NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,$newpath,NULL);
			}
		}
	}
	
	function adjust_documentpath($doclocation, $zieldatei){
		#echo '<br>Speicherort der Datei muss geändert werden.';
		#echo '<br>von:&nbsp; '.$doclocation;
		#echo '<br>nach: '.$zieldatei; 
		$errmsg = $this->dokumentenDateiHochladen($doclocation, $zieldatei);
		if($errmsg == ''){
			$errmsg = $this->dokumentenDateiLoeschen($doclocation);		
		}
		return $errmsg;
	}

	function create_Gesamtpolygon($pfad){
		include_(CLASSPATH.'data_import_export.php');
    $io = new data_import_export();
		$sql = "SELECT st_multi(st_union(n.the_geom)) as the_geom FROM nachweisverwaltung.n_nachweise n ";
		$sql.= "LEFT JOIN nachweisverwaltung.n_dokumentarten d ON n.art = d.id ";
		$sql.= "WHERE n.id IN (".implode(',', $this->nachweise_id).") ";
		$sql.= "AND d.geometrie_relevant";
		# temp. Tabelle erzeugen für den Export
		$temp_table = 'nwv_'.rand(1, 10000);
    $sql = 'CREATE TABLE public.'.$temp_table.' AS '.$sql;
    $ret = $this->database->execSQL($sql,4, 0);
		$sql = 'SELECT the_geom FROM public.'.$temp_table;
		$io->ogr2ogr_export($sql, '"ESRI Shapefile"', $pfad.'Shape/gesamtpolygon.shp', $this->database);
		$io->ogr2ogr_export($sql, 'GeoJSON', $pfad.'GeoJSON/gesamtpolygon.json', $this->database);
		$io->ogr2ogr_export($sql, 'GML', $pfad.'GML/gesamtpolygon.xml', $this->database);
		$io->ogr2ogr_export($sql, 'DXF', $pfad.'DXF/gesamtpolygon.dxf', $this->database);
		$io->create_uko($this->database, $sql, 'the_geom', EPSGCODE, $pfad.'UKO/gesamtpolygon.uko');
		# temp. Tabelle wieder löschen
		$sql = 'DROP TABLE '.$temp_table;
		$ret = $this->database->execSQL($sql,4, 0);
	}
	
	function writeIgnoredDokumentarten($pfad){
		# Readme-Datei mit ignorierten Dokumentarten schreiben
		$sql = "SELECT d.art FROM nachweisverwaltung.n_nachweise n ";
		$sql.= "LEFT JOIN nachweisverwaltung.n_dokumentarten d ON n.art = d.id ";
		$sql.= "WHERE n.id IN (".implode(',', $this->nachweise_id).") ";
		$sql.= "AND NOT d.geometrie_relevant";
		$ret = $this->database->execSQL($sql,4, 0);
		if(!$ret[0]){
      while($rs=pg_fetch_array($ret[1])){
				$art[] = $rs['art'];
      }
			if(count($art) > 0){
				$fp = fopen($pfad.'readme.txt', 'w');
				fwrite($fp, 'Diese Dokumentarten wurden bei der Berechnung der Flurstückszuordnung und des Gesamtpolygons nicht berücksichtigt:'.chr(10).chr(10));
				fwrite($fp, implode(chr(10), array_unique($art)));
				fclose($fp);
			}
    }
	}
	
	function get_next_nachweis_id(){
		$sql = "select last_value + 1 from nachweisverwaltung.n_nachweise_id_seq";
		$ret = $this->database->execSQL($sql,4, 0);
		if(!$ret[0]){
			$rs = pg_fetch_row($ret[1]);
			return $rs[0];
		}
	}
	
  function getZielDateiName($formvars) {
    #2005-11-24_pk
    $pathparts=pathinfo($formvars['Bilddatei_name']);
		if($formvars[NACHWEIS_PRIMARY_ATTRIBUTE] == ''){		# primäres Ordungskriterium leer -> Nachweis-ID nehmen
			$id = $secondary.str_pad(($formvars['id'] ?: $this->get_next_nachweis_id()), RISSNUMMERMAXLENGTH,'0',STR_PAD_LEFT);
		}
		else{
			$id = $this->buildNachweisNr($formvars[NACHWEIS_PRIMARY_ATTRIBUTE], $formvars[NACHWEIS_SECONDARY_ATTRIBUTE]);
		}
    $zieldateiname=$formvars['flurid'].'-'.$id.'-'.$formvars['artname'].'-'.str_pad(trim($formvars['Blattnr']),3,'0',STR_PAD_LEFT).'.'.$pathparts['extension'];
    #echo $zieldateiname;
    return $zieldateiname;
  }
  
  function changeDokument($formvars, $user) {
    # 1. Starten der Transaktion
    $this->database->begintransaction();        

    # 2. Prüfen der Eingabewerte
		$dokumentarten = $this->getDokumentarten();
    $ret=$this->pruefeEingabedaten($formvars['id'], $formvars['datum'],$formvars['VermStelle'],$formvars['hauptart'],$formvars['gueltigkeit'],$formvars['stammnr'],$formvars['rissnummer'],$formvars['fortfuehrung'],$formvars['Blattformat'],$formvars['Blattnr'],false,$formvars['Bilddatei_name'],$formvars['pathlength'],$formvars['umring'], $formvars['flurid'], $formvars['Blattnr'], $dokumentarten[$formvars['hauptart']][$formvars['unterart']]['pok_pflicht']);
    if ($ret[0]) {
      # Fehler bei den Eingabewerten entdeckt.  
      #echo '<br>Ergebnis der Prüfung: '.$ret;
      $errmsg=$ret[1];
    }
    else {
      #echo '<br>Eingabewerte geprüft.';
      # 3. Abfragen des Namens der zu ändernden Dokumentendatei
      $ret=$this->getDocLocation($formvars['id']);
      if ($ret[0]) {
        # Abfrage des Namens der zu ändernden Dokumentendatei nicht erfolgreich
        $errmsg=$ret[1];
      }
      else {
        # Name der alten Dokumentendatei gefunden
        $doclocation=$ret[1];
        #echo '<br>Speicherort der alten Dokumentendatei: '.$doclocation.' abgefragt.';
        if($formvars['Bilddatei'] == ''){
          # Verwenden der vorhandenen Datei für die Bildung des neuen Dateinamens
          # unter dem die Datei nach der Sachdatenänderung gespeichert werden soll
          $formvars['Bilddatei_name']=$doclocation;
        }
        # Zusammensetzen des Dateinamen unter dem das Dokument gespeichert werden soll
				$formvars['zieldateiname']=$this->getZielDateiName($formvars);
				$zieldatei=NACHWEISDOCPATH.$formvars['flurid'].'/'.$this->buildNachweisNr($formvars[NACHWEIS_PRIMARY_ATTRIBUTE], $formvars[NACHWEIS_SECONDARY_ATTRIBUTE]).'/'.$formvars['artname'].'/'.$formvars['zieldateiname'];

        # 4. Ändern der Eintragung in der Datenbank
        $ret=$this->aktualisierenDokument($formvars['id'],$formvars['datum'],$formvars['flurid'],$formvars['VermStelle'],$formvars['unterart_'.$formvars['hauptart']],$formvars['gueltigkeit'],$formvars['geprueft'],$formvars['stammnr'],$formvars['Blattformat'],$formvars['Blattnr'],$formvars['rissnummer'],$formvars['fortfuehrung'],$formvars['bemerkungen'],$formvars['bemerkungen_intern'],$formvars['umring'],$zieldatei, $user);
        if ($ret[0]) {
          # Aktualisierungsvorgang in der Datenbank nicht erfolgreich
          $errmsg=$ret[1];
        }
        else {
          # 5. Änderung erfolgreich, überschreiben der alten durch die neue Datei
          #echo '<br>Eintragung in Datenbank geändert.';
          # Unterscheidung ob auch die Datei geändert werden soll
          if($formvars['Bilddatei'] != ''){
            # Datei soll auch geändert werden.
            # 5.1 Löschen der bestehenden Bilddatei auf dem Server
            $ret=$this->dokumentenDateiLoeschen($doclocation);
            if ($ret!='') {
              # Alte Datei konnte nicht gelöscht werden   
              $errmsg=$ret;
            }
            else {
              # 5.2 Löschen der alten Datei war erfolgreich
              #echo '<br>Alte Datei: '.$doclocation.' gelöscht';
              # Speichern der neuen Bilddatei auf dem Server
              $ret=$this->dokumentenDateiHochladen($formvars['Bilddatei'], $zieldatei);
              if ($ret!='') {
                # Neue Datei konnte nicht hochgeladen werden
                $errmsg=$ret;
              }
              else {
                # 6. Speicherung der neuen Bilddatei war erfolgreich
                #echo '<br>Neue Datei:'.$this->zieldateiname.' auf den Server geladen.';
                # Erfolgreiches abschließen der Transaktion
                $this->database->committransaction();
                $ret[0]=0;
                $ret[1]='Änderung des Datenbankeintrages und der Datei erfolgreich.';
              } # end of Speicherung der neuen Bilddatei
            } # end of Löschen der alten Datei
          } # end of eine neue Datei soll hochgeladen werden
          else {
            # vorhandene Datei soll nicht durch neu hochgeladene Datei ersetzt werden
            # Prüfen, ob sich der Speicherort auf Grund von geänderten Sachdaten ändern muss
            # $doclocation... alter Speicherort
            # Zusammensetzen des neuen Speicherortes
            if ($doclocation!=$zieldatei) {
              $errmsg = $this->adjust_documentpath($doclocation, $zieldatei);
							if($errmsg == ''){
								# Erfolgreiches abschließen der Transaktion
								$this->database->committransaction();
								$ret[0]=0;
								$ret[1]='Änderung des Datenbankeintrages und des Speicherortes der Datei erfolgreich.';
							}
            }
            else {
              # Erfolgreiches abschließen der Transaktion
              $this->database->committransaction();
              $ret[0]=0;
              $ret[1]='Änderung des Datenbankeintrags ohne Änderung des Speicherortes der Datei erfolgreich.';
            }
          } # end of keine neue hochgeladene Datei
        } # end of Eintragen der Änderung in Datenbank
      } # end of alter Speicherort erfolgreich ermittelt
    } # end of Prüfung der Eingabewerte ok
    if ($errmsg!='') {
      # Es ist eine Fehlermeldung aufgetreten die Transaktion wird rückgüngig gemacht.
      # Die Änderung in der Datenbank wird wieder zurückgenommen.
      $this->database->rollbacktransaction();
      $ret[0]=1;
      $ret[1]=$errmsg; 
    }
    return $ret;
  }

  function getHauptDokumentarten(){
  	$sql="SELECT * FROM nachweisverwaltung.n_hauptdokumentarten order by id"; 
    $ret=$this->database->execSQL($sql,4, 0);    
    if (!$ret[0]) {
      while($rs=pg_fetch_array($ret[1])){
				$art[$rs['id']] = $rs;
      }
    }
    return $art;
  }	
  
  function getDokumentarten(){
  	$sql="SELECT id, art, geometrie_relevant, hauptart, sortierung, abkuerzung, pok_pflicht::integer FROM nachweisverwaltung.n_dokumentarten order by sortierung, art"; 
    $ret=$this->database->execSQL($sql,4, 0);    
    if (!$ret[0]) {
      while($rs=pg_fetch_assoc($ret[1])){
				$art[$rs['hauptart']][$rs['id']] = $rs;
      }
    }
    return $art;
  }
  
  function getAnzahlNachweise($polygon){
    $sql="SELECT COUNT (the_geom) as anzahl FROM nachweisverwaltung.n_nachweise where st_intersects ('".$polygon."',the_geom)"; 
    $ret=$this->database->execSQL($sql,4, 0);    
    if (!$ret[0]) {
      $rs=pg_fetch_array($ret[1]);
      #echo 'Anzahl: '.$rs['anzahl'];
    }
    return $anzahl;
  }
  
  function getBBoxAsRectObj($id,$source) {
    # ermittelt die Boundingbox des Nachweises $id
    $sql ='SELECT st_xmin(st_extent(st_transform(the_geom, '.$this->client_epsg.'))) AS minx,st_ymin(st_extent(st_transform(the_geom, '.$this->client_epsg.'))) AS miny';
    $sql.=',st_xmax(st_extent(st_transform(the_geom, '.$this->client_epsg.'))) AS maxx,st_ymax(st_extent(st_transform(the_geom, '.$this->client_epsg.'))) AS maxy';
    if ($source=='nachweis') { $sql.=' FROM nachweisverwaltung.n_nachweise '; }
    $sql.='WHERE id='.$id;
    $ret=$this->database->execSQL($sql,4, 0);
    if ($ret[0]) {
      $ret[1].='Fehler bei der Abfrage der Boundingbox des Nachweisdokumentes! \n';
    }
    else {
      # Abfrage fehlerfrei
      # Erzeugen eines RectObject
      $rect= ms_newRectObj();
      # Abfragen und zuordnen der Koordinaten der Box
      $rs=pg_fetch_array($ret[1]);
      if ($rs['maxx']-$rs['minx']==0) {
        $rs['maxx']=$rs['maxx']+1;
        $rs['minx']=$rs['minx']-1;        
      }
      if ($rs['maxy']-$rs['miny']==0) {
        $rs['maxy']=$rs['maxy']+1;
        $rs['miny']=$rs['miny']-1;        
      }
      $rect->minx=$rs['minx']; $rect->miny=$rs['miny'];
      $rect->maxx=$rs['maxx']; $rect->maxy=$rs['maxy'];
      $ret[1]=$rect;
    }
    return $ret;
  }
  
  function check_poly_in_flur($polygon, $flur, $gemarkung, $epsg){
  	$sql = "SELECT st_isvalid(st_geomfromtext('".$polygon."', ".$epsg."))";
  	$ret=$this->database->execSQL($sql,4, 1);
  	$rs = pg_fetch_row($ret[1]);
		if($rs[0] == 'f'){
			$result = 'invalid';
			return $result;
		}
  	$ret=$this->database->check_poly_in_flur($polygon, $epsg);
  	$result = 'f';	
  	while($rs = pg_fetch_row($ret[1])){
  		if($gemarkung == $rs[0] AND $flur == ltrim($rs[1], '0')){
  			$result = 't';
  			break;
  		}
  	}
  	return $result;
  }
  
  function pruefeEingabedaten($id, $datum, $VermStelle, $hauptart, $gueltigkeit, $stammnr, $rissnummer, $fortfuehrung, $Blattformat, $Blattnr, $changeDocument,$Bilddatei_name, $pathlength, $umring, $flur, $blattnr, $pok_pflicht){
		global $nachweis_unique_attributes;
		# Test ob schon ein Nachweis mit dieser Kombination existiert
		if($pok_pflicht AND $nachweis_unique_attributes != NULL){
			if(NACHWEIS_SECONDARY_ATTRIBUTE == 'fortfuehrung')$test_fortfuehrung = $fortfuehrung;
			if(in_array('art', $nachweis_unique_attributes)){
				$test_art = array($hauptart);
			}
			if(in_array('blattnr', $nachweis_unique_attributes))$test_blattnr = $Blattnr;			
			if(NACHWEIS_PRIMARY_ATTRIBUTE == 'stammnr'){
				$nachweise = $this->getNachweise(NULL,NULL,$gemarkung,$stammnr,NULL,$test_fortfuehrung,$test_art,NULL,'multibleIDs',NULL,NULL,NULL,NULL,NULL,NULL, $flur, true,NULL,NULL, $test_blattnr);
			}
			else{
				$nachweise = $this->getNachweise(NULL,NULL,$gemarkung,NULL,$rissnummer,$test_fortfuehrung,$test_art,NULL,'multibleIDs',NULL,NULL,NULL,NULL,NULL,NULL, $flur, true,NULL,NULL, $test_blattnr);
			}
			if(($this->Dokumente[0]['id'] != '' AND $id != $this->Dokumente[0]['id']) OR ($this->Dokumente[1]['id'] != '' AND $id != $this->Dokumente[1]['id'])){
				$errmsg.='Es existiert bereits ein Nachweis mit diesen Parametern. ';
			}
		}
		
    if ($umring == ''){
      $errmsg.='Bitte legen Sie das Polygon für den einzuarbeitenden Nachweis fest! <br>';
    }
            
    # test auf korrekte Vermessungstelle 
    $sql='SELECT * FROM nachweisverwaltung.n_vermstelle WHERE id='.$VermStelle;
    $queryret=$this->database->execSQL($sql,4, 0);
    if ($queryret[0]) {
      $errmsg.='Fehler bei der Vermessungstellenauswahl! <br>';
    }
    else {
      if (pg_num_rows($queryret[1])==0) {
        $errmsg.='Die Vermessungsstelle ist nicht bekannt. <br>';
      }
    }
    
    # test des Datum
    if ($datum=='') {
      $errmsg.='Bitte geben Sie ein Datum an!<br>';
    }
    else{
			# richtigkeit des Datums checken
			$explosion = explode('.', $datum);
			if (checkdate($explosion[1], $explosion[0], $explosion[2])==false) {
				$errmsg.= 'Datum ist nicht korrekt angegeben! <br<' ;
			}
			# prüfen ob Datum in Zukunft
			$realtime=time();
			$Zeit=mktime(0, 0, 0, $explosion[1], $explosion[0], $explosion[2]);
			if ($realtime < $Zeit) {
				$errmsg.='Das angegebene Datum liegt in der Zukunft! <br>' ;
			} 
    }    
    
    #Test des Blattformat
    if ($Blattformat==''){
      $errmsg.='Bitte das Blattformat des Dokuments angeben! <br>';
   }
    else{
      $nums= array ("A4","A3","SF");
      if(!in_array($Blattformat, $nums)){
        $errmsg.='Die Auswahl des Blattformat ist nicht korrekt! <br>';
      }
    }
    # Test der Dokumentenart  
    if ($hauptart==''){
        $errmsg.='Bitte wählen Sie die Art des einzugebenden Dokuments aus! <br>';
    }
    if ($gueltigkeit==''){
      $errmsg.='Bitte wählen Sie die Gültigkeit des einzugebenden Dokuments aus! <br>';
    }
    else{
      $nums = array("1","0");
      if (!in_array($gueltigkeit,$nums)){
        $errmsg.='Die Angabe über die Gültigkeit des Dokuments ist nicht korrekt! <br>';
      }
    }
    # Testen der Stammnummer
    if(NACHWEIS_PRIMARY_ATTRIBUTE == 'stammnr'){
	    $stammnr=trim($stammnr);
	    if ($stammnr == ''){
				if($pok_pflicht){
					$errmsg.='Bitte geben Sie die Antragsnummer korrekt ein! <br>';
				}
	    }
	    else{
	      $nums = array ( "-", "(", ")", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0" );
	      $strenthalten=0;
	      for ($i=0;$i<strlen($stammnr);$i++) {
	        if (!in_array($stammnr[$i],$nums)) {
	          $strenthalten=1;
	        }
	      }
	      if ($strenthalten==1) {
	        $errmsg.='Ungültige Zeichen bei der Antragsnummer ! <br>';
	      }
	    }
    }
  	# Testen der Rissnummer
    if(NACHWEIS_PRIMARY_ATTRIBUTE == 'rissnummer'){
	    $rissnummer=trim($rissnummer);
	    if ($rissnummer == ''){
				if($pok_pflicht){
					$errmsg.='Bitte geben Sie die Rissnummer korrekt ein! <br>';
				}
	    }
	    else{
	      $nums = array ( "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0" );
	      $strenthalten=0;
	      for ($i=0;$i<strlen($rissnummer);$i++) {
	        if (!in_array($rissnummer[$i],$nums)) {
	          $strenthalten=1;
	        }
	      }
	      if ($strenthalten==1) {
	        $errmsg.='Ungültige Zeichen bei der Rissnummer ! <br>';
	      }
	    }
    }
  # Testen der Fortfuehrung
    if(NACHWEIS_SECONDARY_ATTRIBUTE == 'fortfuehrung'){
    	if($fortfuehrung == '' OR $fortfuehrung < 1860 OR $fortfuehrung > date('Y')){
	      $errmsg.='Bitte geben Sie das Fortführungsjahr korrekt ein! <br>';
	    }
    }
    # Test der Blattnummer
    $Blattnr=trim($Blattnr);
    if ($Blattnr ==''){
      $errmsg.='Bitte geben Sie die Blattnummer ein! <br>';
    }
    else{
      $nums = array ( "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0" );
      $strenthalten=0;
      for ($i=0;$i<strlen($Blattnr);$i++) {
        if (!in_array($Blattnr[$i],$nums)) {
          $strenthalten=1;
        }
      }
      if ($strenthalten==1) {
        $errmsg.='Die Blattnummer darf nur Ziffern und Buchstaben enthalten ! <br>';
      }
    }
    # Test der Bilddatei 
    if($changeDocument AND $Bilddatei_name==''){
      $errmsg.='Bitte wählen Sie die Bilddatei aus! ';
    }
    if ($errmsg!='') {
      $ret[0]=1;
      $ret[1]=$errmsg;
    }
    else {
      $ret[0]=0;
    }
    return $ret;
  }

  static function buildNachweisNr($primary, $secondary){
  	if(NACHWEIS_PRIMARY_ATTRIBUTE == 'rissnummer'){
  		return $secondary.str_pad($primary, RISSNUMMERMAXLENGTH,'0',STR_PAD_LEFT);
  	}
  	else{
  		return $secondary.str_pad($primary, ANTRAGSNUMMERMAXLENGTH,'0',STR_PAD_LEFT);
  	}
  }
  
	function CreateNachweisDokumentVorschau($dateiname){		
		$dir = dirname($dateiname);
		$dateinamensteil=explode('.',$dateiname);
		if(mb_strtolower($dateinamensteil[1]) == 'pdf'){
			$pagecount = getNumPagesPdf($dateiname);
			if($pagecount > 1)$label = '-pointsize 11 -draw "stroke #0009 fill #0007 stroke-width 2 circle 120,120 200,120 
																											 stroke none fill white text 98,102 \''.$pagecount.'\'
																																							text 60,150 \'Seiten\'"';
		}
		$command = IMAGEMAGICKPATH.'convert -density 300x300 '.$dateiname.'[0] -quality 75 -background white '.$label.' -flatten -resize 1800x1800\> '.$dateinamensteil[0].'_thumb.jpg';
		exec($command, $ausgabe, $ret);
		if($ret == 1){
			$type = $dateinamensteil[1];
  		switch ($type) {  			
  			default : {
  				$image = imagecreatefromgif(GRAPHICSPATH.'document.gif');
          $textbox = imagettfbbox(13, 0, WWWROOT.APPLVERSION.'fonts/arial.ttf', '.'.$type);
          $textwidth = $textbox[2] - $textbox[0] + 13;
          $blue = ImageColorAllocate ($image, 26, 87, 150);
          imagettftext($image, 13, 0, 22, 34, $blue, WWWROOT.APPLVERSION.'fonts/arial_bold.ttf', $type);
          $thumbname = $dateinamensteil[0].'_thumb.jpg';
          imagejpeg($image, $thumbname);
  			}
  		}
		}
  }
	
  function dokumentenDateiHochladen($quelldatei,$zieldatei) {
		if(is_file($zieldatei)){
      $errmsg=' Eine entsprechende Datei existiert bereits auf dem Server!';
    }
    else{
			$zielordner = dirname($zieldatei);
			if(!is_dir($zielordner)){
				mkdir($zielordner, 0777, true);
			}
      copy($quelldatei,$zieldatei);
      if(!file_exists($zieldatei) OR filesize($zieldatei) == 0){
      	$errmsg=' Beim Laden der Datei auf den Server sind Fehler aufgetreten!';
      }
			else{
				$errmsg = $this->CreateNachweisDokumentVorschau($zieldatei);
			}
    }
    return $errmsg;     
  }
  
  function dokumentenDateiLoeschen($doclocation){
    if(file_exists($doclocation)){
      $erfolg=unlink($doclocation);
      if(!$erfolg){  
        $errmsg.= 'Die Datei konnte nicht gelöscht werden, weil sie nicht existiert oder keine Zugriffsrechte bestehen! ';      
      }
      else{
				$dateinamensteil = explode('.', $doclocation);
				unlink($dateinamensteil[0].'_thumb.jpg');
				$directory = dirname($doclocation);
				if(is_dir_empty($directory)){
					rmdir($directory);
					#echo '<br>Altes Verzeichnis: '.$directory.' gelöscht';
					$directory = dirname($directory);
					if(is_dir_empty($directory)){
						rmdir($directory);
						#echo '<br>Altes Verzeichnis: '.$directory.' gelöscht';
						$directory = dirname($directory);
						if(is_dir_empty($directory)){
							rmdir($directory);
							#echo '<br>Altes Verzeichnis: '.$directory.' gelöscht';
						}
					}
				}
      }
    }
    return $errmsg;
  }

  function nachweiseLoeschen($id,$loeschenDateien) {
    # Löschen von Nachweisen in Datenbank und eventuelle auch die dazugehörigen Dateien
    $errmsg='';
    $msg='';
    # Prüfe ob es ein oder mehrere ids sind zum Löschen
    if (is_array($id)) { $idListe=$id; } else { $idListe=array($id); }
    $anzahlIDs=count($idListe);
    # Parameter, mit dem der Löschvorgang abgebrochen werden kann
    $loeschenabbrechen=0;
    # Lösche die Nachweise eventuell mit samt Dateien von 1 bis n
    for ($i=0;$i<$anzahlIDs;$i++) {
      # Sollen auch die Dateien zu den Nachweisen auf dem Server gelöscht werden
      if ($loeschenDateien) {
        # Abfragen des Namens der Datei zum Nachweis
        $ret=$this->getNachweise($idListe[$i],'','','','','','','','bySingleID','','');
        if ($ret=='') {
          # Abfrage war erfolgreich
          # Es wurde ein Eintrag in Datenbank gefunden, das löschen der Datei kann erfolgen
          # Abfrage, ob die Datei überhaupt existiert
          $nachweisDatei=$this->Dokumente[0]['link_datei'];
          if (file_exists($nachweisDatei)) {
            # Datei existiert und kann jetzt im Filesystem gelöscht werden
						$ret = $this->dokumentenDateiLoeschen($nachweisDatei);
						if ($ret == '') {
              $msg.='Datei '.$nachweisDatei.' wurde erfolgreich gelöscht. ';
            }
          }
          else {
            # Datei existiert nicht
            $msg.='Die Datei '.$nachweisDatei.' konnte nicht gefunden werden.<br>Wahrscheinlich falscher Pfad/Dateiname<br>';
          }
        }
        else {
          # Abfrage des Speicherortes der Datei fehlerhaft
          $errmsg.='Fehler bei der Datenbankabfrage des Speicherortes der Dokumentendatei.';
          $errmsg.=$ret[1];
          $loeschenabbrechen=1;
        }
      }
      # Wenn keine Fehler aufgetreten sind, löschen des Datenbankeintrages des Nachweises.
      if ($errmsg=='') {
        $sql ="DELETE FROM nachweisverwaltung.n_nachweise WHERE id=".(int)$idListe[$i];
        $ret=$this->database->execSQL($sql,4, 1);
        if ($ret[0]==0) {
          # Datensatz erfolgreich gelöscht
          $msg.='Nachweis mit ID:'.$idListe[$i].' erfolgreich gelöscht.';
        }
        else {
          # Fehler beim löschen des Datensatzes
          $errmsg.='Nachweisdokument konnte in Datenbank nicht gelöscht wurde.';
          $errmsg.=$ret[1];
          $loeschenabbrechen=1;
        }
      }
      if ($loeschenabbrechen) {
        $i=$anzahlIDs;
      }
    }
    if ($loeschenabbrechen) {
      $ret[0]=1;  $ret[1]=$errmsg;
    }
    else {
      $ret[0]=0;  $ret[1]=$msg;
    }
    return $ret;
  }
  
  function eintragenNeuesDokument($datum,$flurid,$VermStelle,$unterart,$gueltigkeit,$geprueft,$stammnr,$blattformat,$blattnr,$rissnummer,$fortf,$bemerkungen,$bemerkungen_intern,$zieldatei,$umring,$user) {
    #2005-11-24_pk
    if($fortf == '')$fortf = 'NULL';
    $this->debug->write('Einfügen der Metadaten zum neuen Nachweisdokument in die Sachdatenbank',4);
    $sql ="INSERT INTO nachweisverwaltung.n_nachweise (flurid,stammnr,art,blattnummer,datum,vermstelle,gueltigkeit,geprueft,format,link_datei,the_geom,fortfuehrung,rissnummer,bemerkungen,bemerkungen_intern,bearbeiter,zeit,erstellungszeit)";
    $sql.=" VALUES (".$flurid.",'".trim($stammnr)."',".$unterart.",'".trim($blattnr)."','".$datum."'";
    $sql.=",'".$VermStelle."','".$gueltigkeit."','".$geprueft."','".$blattformat."','".$zieldatei."',st_transform(st_geometryfromtext('".$umring."', ".$this->client_epsg."), (select srid from geometry_columns where f_table_name = 'n_nachweise'))";
    $sql.=",".trim($fortf).",'".trim($rissnummer)."','".$bemerkungen."','".$bemerkungen_intern."','".$user->Vorname." ".$user->Name."', '".date('Y-m-d G:i:s')."', '".date('Y-m-d G:i:s')."')";
		#echo '<br>Polygon-SQL: '.$sql;
    $ret=$this->database->execSQL($sql,4, 1);
    if ($ret[0]) {
      # Fehler beim Eintragen in Datenbank
      $ret[1]='Auf Grund eines Datenbankfehlers konnte das Dokument nicht eingetragen werden!'.$ret[1];
    }
    return $ret;
  }
  
  function aktualisierenDokument($id,$datum,$flurid,$VermStelle,$unterart,$gueltigkeit,$geprueft,$stammnr,$Blattformat,$Blattnr,$rissnr,$fortf,$bemerkungen,$bemerkungen_intern,$umring,$zieldateiname,$user){
    $this->debug->write('Aktualisieren der Metadaten zu einem bestehenden Nachweisdokument',4);
    $sql="UPDATE nachweisverwaltung.n_nachweise SET ";
		if($flurid != NULL)$sql.="flurid='".$flurid."', ";
		if($stammnr !== NULL){
			if($stammnr === '')$sql.="stammnr=NULL, ";
			else $sql.="stammnr='".trim($stammnr)."', ";
		}
		if($unterart != NULL)$sql.="art=".$unterart;
    if($Blattnr != NULL)$sql.=",blattnummer='".trim($Blattnr)."', ";
		if($datum != NULL)$sql.="datum='".$datum."', ";
		if($VermStelle != NULL)$sql.="vermstelle='".$VermStelle."', ";
    if($gueltigkeit != NULL)$sql.="gueltigkeit='".$gueltigkeit."', ";
		if($geprueft != NULL)$sql.="geprueft='".$geprueft."', ";
		if($Blattformat != NULL)$sql.="format='".$Blattformat."', ";
		if($umring != NULL)$sql.="the_geom=st_transform(st_geometryfromtext('".$umring."', ".$this->client_epsg."), (select srid from geometry_columns where f_table_name = 'n_nachweise')), ";
		if($zieldateiname != NULL)$sql.="link_datei='".$zieldateiname."', ";
		if($fortf !== NULL){
			if($fortf === '')$sql.="fortfuehrung=NULL, ";
			else $sql.="fortfuehrung=".(int)$fortf.", ";
		}
		if($rissnr !== NULL){
			if($rissnr === '')$sql.="rissnummer=NULL, ";
			else $sql.="rissnummer='".trim($rissnr)."', ";
		}
		if($bemerkungen !== NULL)$sql.="bemerkungen='".$bemerkungen."', ";
		if($bemerkungen_intern !== NULL)$sql.="bemerkungen_intern='".$bemerkungen_intern."', ";
		if($user !== NULL)$sql.=" bearbeiter='".$user->Vorname." ".$user->Name."', ";
		$sql.=" zeit='".date('Y-m-d G:i:s')."'";
    $sql.=" WHERE id = ".$id;
    #echo $sql;
    $ret=$this->database->execSQL($sql,4, 1);
		if ($ret[0]){
			# Fehler beim Eintragen in Datenbank
			$ret[1]='Auf Grund eines Datenbankfehlers konnte das Dokument nicht aktualisiert werden!'.$ret[1];
		}
    return $ret; 
  }
	
	function updateBearbeitungshinweis($user, $id, $text){
		$sql="UPDATE nachweisverwaltung.n_nachweise 
						SET bemerkungen_intern = coalesce(bemerkungen_intern, '') || CASE WHEN NULLIF(bemerkungen_intern, '') IS NOT NULL THEN '\n\n' ELSE '' END || '".$user->Vorname." ".$user->Name.":\n".$text."'
					WHERE id = ".$id;
		#echo $sql;
		$ret=$this->database->execSQL($sql,4, 1);
	}
  
  function pruefeSuchParameterIndivNr($stammnr) {
    if ($errmsg!='') { $ret[0]=1; $ret[1]=$errmsg; }
    return $ret;
  }
  
  function pruefeSuchParameterSingleID($id) {
    #echo 'Single ID: '.$id;
    # Überprüfen, ob die gegebenen Suchparameter ausreichend und gültig sind
    if ($id=='') {
      $errmsg.='\nFehler: Es ist keine DokumentenID angegeben worden.';
    }
    if (!is_integer(intval($id))) {
      $errmsg.='\nFehler: Die DokumentenID ist keine ganze Zahl.';
    }
    if ($errmsg!='') { $ret[0]=1; $ret[1]=$errmsg; }
    return $ret;
  }

  function pruefeSuchParameterPolygon($umring) {
    $sql ="SELECT st_isvalid('".$umring."')";
    $this->debug->write("<br>nachweis.php pruefeSuchParameterPolygon.<br>".$sql."<br>",4);
    $query=@pg_query($this->database->dbConn,$sql);
    if ($query=='') { # Fehler in der Datenbankabfrage
      $errmsg.='\nFehler: Sie haben kein Suchpolygon verwendet.';
    }
    else {
      $rs=pg_fetch_array($query);
      if ($rs[0]!='t') {
        $errmsg.='\nFehler: Die Geometry des Suchpolygons ist ungültig.';
      }
    }
    if ($errmsg!='') { $ret[0]=1; $ret[1]=$errmsg; }
    return $ret;
  }
  
  function pruefeAnzeigedaten($stammnr,$f,$k,$g,$abfrage_art,$antr_nr){
    if ($abfrage_art==''){
      $errmsg.='Bitte wählen Sie die Art der Recherche! \n';
    }
    
    else{
      if($abfrage_art=='indiv_nr'){
        if ($stammnr==''){
            $errmsg.='Bitte geben Sie die Stammnummer ein! \n';
        }
        else{
          $nums = array ( "1", "2", "3", "4", "5", "6", "7", "8", "9", "0" );
          $strenthalten=0;
          $stammnr=trim($stammnr);
          for ($i=0;$i<strlen($stammnr);$i++) {
            if (!in_array($stammnr[$i],$nums)) {
              $strenthalten=1;
            }
          }
        }
        if ($strenthalten==1) {
          $errmsg.='Die Stammnummer darf nur Ziffern enthalten! \n';
        }
        else {
          $stammnr=intval($stammnr);
          if ($stammnr==0) {
              $errmsg.='Die Stammnummer muß ungleich 0 sein! \n';
          }
        }
      }
      if($abfrage_art=='poly'){
        $errmsg.='Bitte legen Sie das Suchpolygon in der Graphik fest! \n';
      }
      if($abfrage_art=='antr_nr'){
        if($antr_nr==''){
          $errmsg.='Fehler bei der Antragsnummer! \n';
        } 
      }
      if(!($f OR $k OR $g)){
        $errmsg.='Bitte geben Sie eine Dokumentenart an, nach der gesucht werden soll!\n';
      }
    }
    return $errmsg;
  }
  
  function getNachweise($id,$polygon,$gemarkung,$stammnr,$rissnr,$fortf,$hauptart,$richtung,$abfrage_art,$order,$antr_nr, $datum = NULL, $VermStelle = NULL, $gueltigkeit = NULL, $datum2 = NULL, $flur = NULL, $flur_thematisch = NULL, $unterart = NULL, $suchbemerkung = NULL, $blattnr = NULL, $stammnr2 = NULL, $rissnr2 = NULL, $fortf2 = NULL, $geprueft = NULL, $alle_der_messung = NULL) {
		$explosion = explode('~', $antr_nr);
		$antr_nr = $explosion[0];
		$stelle_id = $explosion[1];
		$n = 'n';
		if($order==''){
			$order="n.flurid, n.stammnr, n.datum";
		}
		$order_rissnummer = "
			(regexp_matches(n.rissnummer, '^\D*'))[1],
			NULLIF(regexp_replace(n.rissnummer, '\D', '', 'g'), '')::bigint,
			(regexp_matches(n.rissnummer, '\D*$'))[1]
		";
		$order = str_replace('blattnummer', "NULLIF(regexp_replace(n.blattnummer, '\D', '', 'g'), '')::bigint", $order);		// nach Blattnummer nummerisch sortieren
		$order = str_replace('rissnummer', $order_rissnummer, $order);		// nach Rissnummer 3-stufig alphanumerisch und nummerisch sortieren
    # Die Funktion liefert die Nachweise nach verschiedenen Suchverfahren.
    # Vor dem Suchen nach Nachweisen werden jeweils die Suchparameter überprüft    
    if (is_array($id)) { $idListe=$id; } else { $idListe=array($id); }
    $idselected=$idListe;
    #echo 'Suche nach Verfahren: '.$abfrage_art;
    # Unterscheidung der Suchverfahren.
    switch ($abfrage_art) {
      case "bySingleID" : {
        # Prüfen der Suchparameter
        # Es muss mindestens eine ID übergeben worden sein.
        #echo 'Prüfen mit id:'.$id;
        $ret=$this->pruefeSuchParameterSingleID($id);
        if ($ret[0]) {
          # Fehler, der Parameter ist nicht vollständig oder ungültig
          $errmsg=$ret[1];
        }
        else {
          # Suche nach einer einzelnen Nachweis_id
          # echo '<br>Suche nach einer einzelnen ID.';
          $sql ="SELECT distinct n.*,st_astext(st_transform(n.the_geom, ".$this->client_epsg.")) AS wkt_umring, st_assvg(st_transform(n.the_geom, ".$this->client_epsg.")) AS svg_umring,v.name AS vermst, h.id as hauptart, n.art as unterart FROM nachweisverwaltung.n_nachweise AS n";
					$sql.=" LEFT JOIN nachweisverwaltung.n_vermstelle v ON CAST(n.vermstelle AS integer)=v.id ";
					$sql.=" LEFT JOIN nachweisverwaltung.n_dokumentarten d ON n.art = d.id";
					$sql.=" LEFT JOIN nachweisverwaltung.n_hauptdokumentarten h ON h.id = d.hauptart";
          $sql.=" WHERE n.id=".(int)$id;
					if($gueltigkeit != NULL)$sql.=" AND gueltigkeit = ".$gueltigkeit;
					if($geprueft != NULL)$sql.=" AND geprueft = ".$geprueft;
          #echo $sql;
          $this->debug->write("<br>nachweis.php getNachweise Abfragen der Nachweisdokumente.<br>",4);
          $ret=$this->database->execSQL($sql,4, 1);
          if ($ret[0]) { # Fehler in der Datenbankabfrage
            $errmsg.=$ret[1];
          }
          else { # Datenbankabfrage war fehlerfrei
            if (pg_num_rows($ret[1])==0) {
              $errmsg.='\nEs konnte kein Dokument gefunden werden.';
            }
            else {
              $this->Dokumente[0]=pg_fetch_assoc($ret[1]);
              $this->erg_dokumente=1;
            } # Ende Ergebnis ist korrekt
          } # Ende Abfrage war fehlerfrei
        } # Ende Suche nach Dokument
      } break;
      
      case "MergeIDs" : {
        $sql ="SELECT distinct n.*,st_astext(st_transform(n.the_geom, ".$this->client_epsg.")) AS wkt_umring, st_assvg(st_transform(n.the_geom, ".$this->client_epsg.")) AS svg_umring,v.name AS vermst, h.id as hauptart, n.art as unterart FROM nachweisverwaltung.n_nachweise AS n";
				$sql.=" LEFT JOIN nachweisverwaltung.n_vermstelle v ON CAST(n.vermstelle AS integer)=v.id ";
				$sql.=" LEFT JOIN nachweisverwaltung.n_dokumentarten d ON n.art = d.id";
				$sql.=" LEFT JOIN nachweisverwaltung.n_hauptdokumentarten h ON h.id = d.hauptart";
        $sql.=" WHERE n.id=".(int)$idselected[0];
				if($gueltigkeit != NULL)$sql.=" AND gueltigkeit = ".$gueltigkeit;
				if($geprueft != NULL)$sql.=" AND geprueft = ".$geprueft;
        #echo $sql;
        $this->debug->write("<br>nachweis.php getNachweise Abfragen der Nachweisdokumente.<br>",4);
        $ret=$this->database->execSQL($sql,4, 0);
        if ($ret[0]) { # Fehler in der Datenbankabfrage
          $errmsg.=$ret[1];
        }
        else { # Datenbankabfrage war fehlerfrei
        	$sql ="SELECT distinct st_astext(st_union(st_transform(n.the_geom, ".$this->client_epsg."))) AS wkt_umring, st_assvg(st_union(st_transform(n.the_geom, ".$this->client_epsg."))) AS svg_umring, st_union(st_transform(n.the_geom, ".$this->client_epsg.")) as geom";
					$sql.=" FROM nachweisverwaltung.n_nachweise AS n";
	        $sql.=" WHERE 1=1";
					if($gueltigkeit != NULL)$sql.=" AND gueltigkeit = ".$gueltigkeit;
					if($geprueft != NULL)$sql.=" AND geprueft = ".$geprueft;
	        if ($idselected[0]!=0) {
	          $sql.=" AND n.id IN ('".$idselected[0]."'";
	          for ($i=1;$i<count($idselected);$i++) {
	            $sql.=",'".$idselected[$i]."'";
	          }
	          $sql.=")";
	        }
	        #echo $sql;
	        $ret1=$this->database->execSQL($sql,4, 1);
          if (pg_num_rows($ret[1])==0) {
            $errmsg.='\nEs konnte kein Dokument gefunden werden.';
          }
          else {
            $this->Dokumente[0]=pg_fetch_assoc($ret[1]);
            $geom = pg_fetch_assoc($ret1[1]);
            $this->Dokumente[0]['wkt_umring'] = $geom['wkt_umring'];
            $this->Dokumente[0]['svg_umring'] = $geom['svg_umring'];
            $this->Dokumente[0]['geom'] = $geom['geom'];
            $this->erg_dokumente=1;
          } # Ende Ergebnis ist korrekt
        } # Ende Suche nach Dokument
      } break;

      case "multibleIDs" : {
				$sql ="SELECT distinct n.*,st_astext(st_multi(st_transform(n.the_geom, ".$this->client_epsg."))) AS wkt_umring,v.name AS vermst, h.id as hauptart, lower(h.abkuerzung) as hauptart_abk, n.art AS unterart, d.art AS unterart_name, h.art as art_name"; 
				$sql.=" FROM nachweisverwaltung.n_nachweise AS n";
				$sql.=" LEFT JOIN nachweisverwaltung.n_vermstelle v ON CAST(n.vermstelle AS integer)=v.id ";
				$sql.=" LEFT JOIN nachweisverwaltung.n_dokumentarten d ON n.art = d.id";
				$sql.=" LEFT JOIN nachweisverwaltung.n_hauptdokumentarten h ON h.id = d.hauptart";
        $sql.=" WHERE true ";
				if($gueltigkeit != NULL)$sql.=" AND gueltigkeit = ".$gueltigkeit." AND ";
				if($geprueft != NULL)$sql.=" AND geprueft = ".$geprueft;
        if ($idselected[0]!=0) {
          $sql.=" AND n.id IN ('".$idselected[0]."'";
          for ($i=1;$i<count($idselected);$i++) {
            $sql.=",'".$idselected[$i]."'";
          }
          $sql.=")";
        }
				if($flur_thematisch != 0){
					if($flur == '')	$sql.=" AND substr(n.flurid::text, 1, 6) = '".$gemarkung."'";
					else $sql.=" AND n.flurid='".$gemarkung.str_pad($flur,3,'0',STR_PAD_LEFT)."'";
				}				
        if($stammnr!=''){
          $sql.=" AND n.stammnr='".$stammnr."'";
        }
      	if($rissnr!=''){
          $sql.=" AND n.rissnummer='".$rissnr."'";
        }
      	if($fortf!=''){
          $sql.=" AND n.fortfuehrung=".(int)$fortf;
        }
				if($blattnr!=''){
					$sql.=" AND n.blattnummer='".$blattnr."'";
				}				
				if(!empty($hauptart)){
					if($hauptart[0] == '2222' AND $idselected[0] != ''){
						$sql.=" AND n.id IN (".implode(',', $idselected).")";
					}
					else{
						$sql.=" AND h.id IN (".implode(',', $hauptart).")";
					}
				}
				if(!empty($unterart))$sql.=" AND ((n.art IN (".implode(',', $unterart).")) OR (d.hauptart NOT IN (select distinct hauptart from nachweisverwaltung.n_dokumentarten where id IN (".implode(',', $unterart)."))))";				
				if($suchbemerkung != ''){
          $sql.=" AND n.bemerkungen LIKE '%".$suchbemerkung."%'";
        }				
        if ($richtung=='' OR $richtung=='ASC'){
          $richtung=="ASC";
          $this->richtung="DESC";
        }
        if ($richtung=="DESC"){
          $this->richtung="ASC";
        }
        $sql.=" ORDER BY ".$order." ".$richtung;
        #echo $sql;
        $this->debug->write("<br>nachweis.php getNachweise Abfragen der Nachweisdokumente.<br>",4);
        $ret=$this->database->execSQL($sql,4, 1);    
        if (!$ret[0]) {
          while ($rs=pg_fetch_assoc($ret[1])) {
						if($rs['link_datei'] != '')$rs['dokument_path']='../Nachweise/'.$rs['flurid'].'/'.$this->buildNachweisNr($rs[NACHWEIS_PRIMARY_ATTRIBUTE], $rs[NACHWEIS_SECONDARY_ATTRIBUTE]).'/'.$rs['hauptart_abk'].'/'.basename($rs['link_datei']);
            $nachweise[]=$rs;
          }
          $this->erg_dokumente=@count($nachweise);
          $this->Dokumente=$nachweise;
        }
      } break;
      
      case "indiv_nr" : {
        # Prüfen der Suchparameter
        # Es muss mindestens die stammnr übergeben worden sein.
        $ret=$this->pruefeSuchParameterIndivNr($stammnr);
        if ($ret[0]) {
          # Fehler, die Parameter sind nicht vollständig oder ungültig
          $errmsg=$ret[1];
        }
        else {
          # Suchparameter sind gültig
          # Suche nach individueller Nummer
          #echo '<br>Suche nach individueller Nummer.';
          $sql ="SELECT DISTINCT ".$order_rissnummer.", NULLIF(regexp_replace(n.blattnummer, '\D', '', 'g'), '')::bigint, n.id, n.flurid, n.blattnummer, n.datum, n.vermstelle, n.gueltigkeit, n.link_datei,n. format, n.stammnr, n.fortfuehrung, n.rissnummer, n.bemerkungen, n.bearbeiter, n.zeit, n.erstellungszeit, n.bemerkungen_intern, n.geprueft, n.art, v.name AS vermst, h.id as hauptart, n.art AS unterart, d.art AS unterart_name";
          $sql.=" FROM ";
					if($gemarkung != '' AND $flur_thematisch == 0){
						$sql.=" alkis.pp_flur as flur, ";
					}
					$sql.=" nachweisverwaltung.n_nachweise AS n";
					$sql.=" LEFT JOIN nachweisverwaltung.n_vermstelle v ON CAST(n.vermstelle AS integer)=v.id ";
					$sql.=" LEFT JOIN nachweisverwaltung.n_dokumentarten d ON n.art = d.id";
					$sql.=" LEFT JOIN nachweisverwaltung.n_hauptdokumentarten h ON h.id = d.hauptart";
					if($alle_der_messung){
						$sql.=" JOIN nachweisverwaltung.n_nachweise AS n2 ON n.oid = n2.oid OR (n.flurid = n2.flurid AND n.".NACHWEIS_PRIMARY_ATTRIBUTE." = n2.".NACHWEIS_PRIMARY_ATTRIBUTE." ".((NACHWEIS_SECONDARY_ATTRIBUTE) ? "and n.".NACHWEIS_SECONDARY_ATTRIBUTE." = n2.".NACHWEIS_SECONDARY_ATTRIBUTE : "").")";
						$n = 'n2';
					}					
          $sql.=" WHERE 1=1 ";
					if($gueltigkeit != NULL)$sql.=" AND gueltigkeit = ".$gueltigkeit;
					if($geprueft != NULL)$sql.=" AND geprueft = ".$geprueft;
          if ($idselected[0]!=0) {
            $sql.=" AND ".$n.".id IN ('".$idselected[0]."'";
            for ($i=1;$i<count($idselected);$i++) {
              $sql.=",'".$idselected[$i]."'";
            }
            $sql.=")";
          }
					if($gemarkung != '' AND $flur_thematisch != 0){
						if($flur == '')	$sql.=" AND substr(".$n.".flurid::text, 1, 6) = '".$gemarkung."'";
						else $sql.=" AND ".$n.".flurid='".$gemarkung.str_pad($flur,3,'0',STR_PAD_LEFT)."'";
          }
					else{
						if($gemarkung != ''){
							$sql.=" AND flur.land||flur.gemarkung = '".$gemarkung."' AND st_intersects(st_transform(flur.the_geom, ".EPSGCODE."), ".$n.".the_geom)";
						}
						if($flur != ''){
							$sql.=" AND flur.flurnummer = ".$flur." ";
						}
					}
          if($stammnr!=''){
						if($stammnr2!=''){
							$sql.=" AND COALESCE(NULLIF(REGEXP_REPLACE(".$n.".stammnr, '[^0-9]+' ,'', 'g'), ''), '0')::bigint between 
											COALESCE(NULLIF(REGEXP_REPLACE('".$stammnr."', '[^0-9]+' ,'', 'g'), ''), '0')::bigint AND 
											COALESCE(NULLIF(REGEXP_REPLACE('".$stammnr2."', '[^0-9]+' ,'', 'g'), ''), '0')::bigint";
						}
						else{
							if(is_numeric($stammnr)){
								$sql.=" AND REGEXP_REPLACE(COALESCE(".$n.".stammnr, ''), '[^0-9]+' ,'', 'g') = '".$stammnr."'";
							}
							else{
								$sql.=" AND lower(".$n.".stammnr)='".mb_strtolower($stammnr)."'";
							}
						}
          }
	        if($rissnr!=''){
						if($rissnr2!=''){
							$sql.=" AND COALESCE(NULLIF(REGEXP_REPLACE(".$n.".rissnummer, '[^0-9]+' ,'', 'g'), ''), '0')::bigint between 
											COALESCE(NULLIF(REGEXP_REPLACE('".$rissnr."', '[^0-9]+' ,'', 'g'), ''), '0')::bigint AND 
											COALESCE(NULLIF(REGEXP_REPLACE('".$rissnr2."', '[^0-9]+' ,'', 'g'), ''), '0')::bigint";
						}
						else{
							if(is_numeric($rissnr)){
								$sql.=" AND REGEXP_REPLACE(COALESCE(".$n.".rissnummer, ''), '[^0-9]+' ,'', 'g') = '".$rissnr."'";
							}
							else{
								$sql.=" AND lower(".$n.".rissnummer)='".mb_strtolower($rissnr)."'";
							}
						}
	        }
	      	if($fortf!=''){
						if($fortf2!=''){
							$sql.=" AND ".$n.".fortfuehrung between ".(int)$fortf." AND ".(int)$fortf2;
						}
						else{
							$sql.=" AND ".$n.".fortfuehrung=".(int)$fortf;
						}
	        }
					if($blattnr!=''){
	          $sql.=" AND ".$n.".blattnummer='".$blattnr."'";
	        }					
          if($datum != ''){
						if($datum2 != ''){
							$sql.=" AND ".$n.".datum between '".$datum."' AND '".$datum2."'";
						}
						else{
							$sql.=" AND ".$n.".datum = '".$datum."'";
						}
          }
          if($VermStelle!=''){
            $sql.=" AND ".$n.".vermstelle = '".$VermStelle."'";
          }
					if(!empty($hauptart)){
						if($hauptart[0] == '2222' AND $idselected[0] != ''){
							$sql.=" AND ".$n.".id IN (".implode(',', $idselected).")";
						}
						else{
							$sql.=" AND h.id IN (".implode(',', $hauptart).")";
						}
					}
					if(!empty($unterart))$sql.=" AND ((".$n.".art IN (".implode(',', $unterart).")) OR (d.hauptart NOT IN (select distinct hauptart from nachweisverwaltung.n_dokumentarten where id IN (".implode(',', $unterart)."))))";					
					if($suchbemerkung != ''){
						$sql.=" AND lower(".$n.".bemerkungen) LIKE '%".mb_strtolower($suchbemerkung)."%'";
					}
          if ($richtung=='' OR $richtung=='ASC'){
            $richtung=="ASC";
            $this->richtung="DESC";
          }
          if ($richtung=="DESC"){
            $this->richtung="ASC";
          }
          $sql.=" ORDER BY ".$order." ".$richtung;
          #echo $sql;
          $this->debug->write("<br>nachweis.php getNachweise Abfragen der Nachweisdokumente.<br>",4);
          $ret=$this->database->execSQL($sql,4, 1);    
          if (!$ret[0]) {
            while ($rs=pg_fetch_assoc($ret[1])) {
              $nachweise[]=$rs;
            }
            $this->erg_dokumente = @count($nachweise);
            $this->Dokumente=$nachweise;
          }
        }
      } break;

      case "poly" : {
        # Prüfen der Suchparameter
        # Es muss ein gültiges Polygon vorhanden sein.
        $ret=$this->pruefeSuchParameterPolygon($polygon);
        if ($ret[0]) {
          # Fehler, die Parameter sind nicht vollstündig oder ungültig
          $errmsg=$ret[1];
        }
        else {
          # Suche mit Suchpolygon
          #echo '<br>Suche mit Suchpolygon.';
          $this->debug->write('Abfragen der Nachweise die das Polygon schneiden',4);
					$sql ="SELECT distinct ".$order_rissnummer.", NULLIF(regexp_replace(n.blattnummer, '\D', '', 'g'), '')::bigint, n.id, n.flurid, n.blattnummer, n.datum, n.vermstelle, n.gueltigkeit, n.link_datei, n.format, n.stammnr, n.fortfuehrung, n.rissnummer, n.bemerkungen, n.bearbeiter, n.zeit, n.erstellungszeit, n.bemerkungen_intern, n.geprueft, n.art, v.name AS vermst, h.id as hauptart, n.art AS unterart, d.art AS unterart_name";
          $sql.=" FROM nachweisverwaltung.n_nachweise AS n";
					$sql.=" LEFT JOIN nachweisverwaltung.n_vermstelle v ON CAST(n.vermstelle AS integer)=v.id ";
          $sql.=" LEFT JOIN nachweisverwaltung.n_dokumentarten d ON n.art = d.id";					
					$sql.=" LEFT JOIN nachweisverwaltung.n_hauptdokumentarten h ON h.id = d.hauptart";
					if($alle_der_messung){
						$sql.=" JOIN nachweisverwaltung.n_nachweise AS n2 ON n.oid = n2.oid OR (n.flurid = n2.flurid AND n.".NACHWEIS_PRIMARY_ATTRIBUTE." = n2.".NACHWEIS_PRIMARY_ATTRIBUTE." ".((NACHWEIS_SECONDARY_ATTRIBUTE) ? "and n.".NACHWEIS_SECONDARY_ATTRIBUTE." = n2.".NACHWEIS_SECONDARY_ATTRIBUTE : "").")";
						$n = 'n2';
					}
 					$sql.=" WHERE 1=1";
          $sql.=" AND st_intersects(st_transform(st_geometryfromtext('".$polygon."',".$this->client_epsg."), (select srid from geometry_columns where f_table_name = 'n_nachweise')), ".$n.".the_geom)";
					if($gueltigkeit != NULL)$sql.=" AND ".$n.".gueltigkeit = ".$gueltigkeit;
					if($geprueft != NULL)$sql.=" AND ".$n.".geprueft = ".$geprueft;
					if(!empty($hauptart)){
						if($hauptart[0] == '2222' AND $idselected[0] != ''){
							$sql.=" AND ".$n.".id IN (".implode(',', $idselected).")";
						}
						else{
							$sql.=" AND h.id IN (".implode(',', $hauptart).")";
						}
					}
					if(!empty($unterart))$sql.=" AND ((".$n.".art IN (".implode(',', $unterart).")) OR (d.hauptart NOT IN (select distinct hauptart from nachweisverwaltung.n_dokumentarten where id IN (".implode(',', $unterart)."))))";				
          if ($richtung=='' OR $richtung=='ASC'){
            $richtung=="ASC";
            $this->richtung="DESC";
          }
          if ($richtung=="DESC"){
            $this->richtung="ASC";
          }
          $sql.=" ORDER BY ".$order." ".$richtung;
          #echo $sql;        
          $ret=$this->database->execSQL($sql,4, 1);    
          if (!$ret[0]) {
            while ($rs=pg_fetch_assoc($ret[1])) {
              $nachweise[]=$rs;
            }
            $this->erg_dokumente=count($nachweise);
            $this->Dokumente=$nachweise;      
          }
        }
      } break;

      case "antr_nr" : {
        # Suche nach Antragsnummer
        # echo '<br>Suche nach Antragsnummer.';
        $this->debug->write('Abfragen der Nachweise die zum Antrag gehören',4);
				$sql ="SELECT distinct ".$order_rissnummer.", NULLIF(regexp_replace(n.blattnummer, '\D', '', 'g'), '')::bigint, n.*,v.name AS vermst, h.id as hauptart, n.art AS unterart, d.art AS unterart_name";
        $sql.=" FROM nachweisverwaltung.n_nachweise2antraege AS n2a, nachweisverwaltung.n_nachweise AS n";
				$sql.=" LEFT JOIN nachweisverwaltung.n_vermstelle v ON CAST(n.vermstelle AS integer)=v.id ";
				$sql.=" LEFT JOIN nachweisverwaltung.n_dokumentarten d ON n.art = d.id";
				$sql.=" LEFT JOIN nachweisverwaltung.n_hauptdokumentarten h ON h.id = d.hauptart";				
        $sql.=" WHERE n.id=n2a.nachweis_id";
        $sql.=" AND n2a.antrag_id='".$antr_nr."'";				
				if($stelle_id == '')$sql.=" AND stelle_id IS NULL";
				else $sql.=" AND stelle_id=".$stelle_id;
				if($gueltigkeit != NULL)$sql.=" AND gueltigkeit = ".$gueltigkeit;
				if($geprueft != NULL)$sql.=" AND geprueft = ".$geprueft;
				if(!empty($hauptart)){
					if($hauptart[0] == '2222' AND $idselected[0] != ''){
						$sql.=" AND n.id IN (".implode(',', $idselected).")";
					}
					else{
						$sql.=" AND h.id IN (".implode(',', $hauptart).")";
					}
				}
				if(!empty($unterart))$sql.=" AND ((n.art IN (".implode(',', $unterart).")) OR (d.hauptart NOT IN (select distinct hauptart from nachweisverwaltung.n_dokumentarten where id IN (".implode(',', $unterart)."))))";				
        if ($order=='') {
          $order="flurid, stammnr, datum";
        }
        if ($richtung=='' OR $richtung=='ASC'){
          $richtung=="ASC";
          $this->richtung="DESC";
        }
        if ($richtung=="DESC"){
          $this->richtung="ASC";
        }
        $sql.=" ORDER BY ".$order." ".$richtung;        
        $ret=$this->database->execSQL($sql,4, 1);    
        if (!$ret[0]) {
          while ($rs=pg_fetch_assoc($ret[1])) {
            $nachweise[]=$rs;
          }
          $this->erg_dokumente=count($nachweise);
          $this->Dokumente=$nachweise;      
        }
      } break;
    }
    # echo '<br>'.$sql;
    return $errmsg;
  }
  
  function getNachw2Antr($antr_nr,$stelle_id){
    # Funktion liefert alle recherchierten Nachweis-IDs zurück, die zu einer 
    # Antragsnummer abgelegt wurde.
    $sql="SELECT * FROM nachweisverwaltung.n_nachweise2antraege WHERE antrag_id='".$antr_nr."'";
		if($stelle_id == '')$sql.=" AND stelle_id IS NULL";
		else $sql.=" AND stelle_id=".$stelle_id;
    # echo '<br>'.$sql;
    $queryret=$this->database->execSQL($sql,4, 0);
    $this->debug->write("<br>nachweis.php Recherche nach den Nachweisen, die zu einer Antrnr gespeichert wurden.<br>".$sql,4);    
    if($queryret[0]) {
      $errmsg='Es ist ein Fehler bei der Recherche der Nachweise zur Antragsnummer aufgetreten';
    }
    else {
      if(pg_num_rows($queryret[1])!=0){
        while ($rs=pg_fetch_array($queryret[1])) {
          $ergebnis[]=$rs['nachweis_id'];
        }
        $this->nachweisanz=count($ergebnis);
        $this->nachweise_id=$ergebnis;
      }
      else {
        $errmsg.='Es wurden keine Nachweise zur Antragsnummer gefunden!';   
      }
    }
    return $errmsg;
  }
  
  function pruefe_Auftrag_hinzufuegen_entfernen($antr_nr, $stelle_id){
    # Funktion dient zum Prüfen der Eingaben beim Arbeitsschritt Auftrag_hinzufügen oder Auftrag_entfernen
    $strenthalten=0;
    if($antr_nr==''){
      $errmsg.='Fehler bei der Angabe der Antragsnummer: '.$antr_nr;
    }
    else {   
      $sql="SELECT * FROM nachweisverwaltung.n_nachweise2antraege";
      $sql.=" WHERE antrag_id ='".$antr_nr."'";
			if($stelle_id == '')$sql.=" AND stelle_id IS NULL";
			else $sql.=" AND stelle_id=".$stelle_id;
      $queryret=$this->database->execSQL($sql,4, 0);
      if ($queryret[0]) {
        $errmsg.='Fehler bei der Abfrage ob schon Nachweise zum Auftrag gehören!';
      }
      else {
        if (pg_num_rows($queryret[1])==''){
          $this->bestaetigung='0';
          $errmsg='';
        }
        else{
          $this->bestaetigung='1';
        }
      }
    }
    return $errmsg;
  }
  
  function zum_Auftrag_hinzufuegen($antrag_id,$stelle_id,$nachweis_id){
    #echo '<br>Start der Funktion zum_Autrag_hinzufuegen';
    # Umsortierung der übergebenen ids
    $idselected=$nachweis_id;
    for ($i=0;$i<count($idselected);$i++) {
      # Abfragen ob die Zuordnung schon existiert.
      $sql ="SELECT * FROM nachweisverwaltung.n_nachweise2antraege";
      $sql.=" WHERE nachweis_id=".(int)$idselected[$i]." AND antrag_id='".$antrag_id."'";
			if($stelle_id == '')$sql.=" AND stelle_id IS NULL";
			else $sql.=" AND stelle_id=".$stelle_id;
      $ret=$this->database->execSQL($sql,4, 0);
      if ($ret[0]) { # Fehler bei der Abfrage
        $errmsg='Fehler beim Abfragen, ob Eintrag existiert.';
      }
      else {
        # 
        if (pg_num_rows($ret[1])>0) {
          # Die Zuordnung existiert schon, nicht neu hinzufügen
          #echo '<br>Dokument mit id: '.$idselected[$i].' ist schon zu Antrag id: '.$antrag_id.' zugeordnet.';
        }
        else {
          # Zuordnung in Datenbank schreiben
          $sql ="INSERT INTO nachweisverwaltung.n_nachweise2antraege (nachweis_id,antrag_id,stelle_id)";
					if($stelle_id == '')$stelle_id = 'NULL';
          $sql.=" VALUES (".$idselected[$i].",'".$antrag_id."',".$stelle_id.")";
          $ret=$this->database->execSQL($sql,4, 1);    
          if ($ret[0]) {
            $this->debug->write("<br>Fehler beim hinzufuegen der Dokumente zur Auftragsnummer: ".__LINE__,4);
            $errmsg='Fehler beim hinzufuegen der Dokumente zur Auftragsnummer';
          }
          else {
            #echo '<br>Dokument mit id: '.$idselected[$i].' zu Antrag id: '.$antrag_id.' zugeordnet.';
          }
        } # ende Zuordnung in Datenbank schreiben
      } # ende Abfrag ob schon vorhanden in Ordnung
    } # ende Schleife
    if ($errmsg!='') {
      $ret[0]=1; $ret[1]=$errmsg;
    }
    else {
      $ret[0]=0; $ret[1]='Nachweise erfolgreich zum Auftrag hinzugefügt.';
    }
    return $ret;
  }

  function aus_Auftrag_entfernen($antr_nr,$stelle_id,$id){
    #Funktion löscht Nachweise, die unter einer Antragsnummer recherchiert wurde. 
    $idselected=$id;
    for ($i=0;$i<count($idselected);$i++) {
      $sql ="DELETE FROM nachweisverwaltung.n_nachweise2antraege";
      $sql.=" WHERE antrag_id='".$antr_nr."' AND nachweis_id='".$idselected[$i]."'";
			if($stelle_id == '')$sql.=" AND stelle_id IS NULL";
			else $sql.=" AND stelle_id=".$stelle_id;
      $ret=$this->database->execSQL($sql,4, 1);
      if ($ret[0]) {
        $this->debug->write("<br>Fehler beim entfernen der Dokumente zur Auftragsnummer: ".__LINE__,4);
        $result[0]='Fehler beim entferen aus der Auftragsnummer!';
      }
      else{
        $result[1]='Dokumente erfolgreich aus Antrag entfernt!';
        #echo '<br>Dokument mit id: '.$idselected[$i].' aus Antrag id: '.$antrag_id.' entfernt.';
      }
    }
    return $result;
  }
	
	function Geometrieuebernahme($ref_geom, $id){
		$sql = "UPDATE nachweisverwaltung.n_nachweise n 
						SET the_geom = (
							SELECT st_union(n2.the_geom) 
							FROM nachweisverwaltung.n_nachweise n2
							WHERE n2.id IN (".implode(',', $ref_geom).")
						)
						WHERE n.id IN (".implode(',', $id).")";
		$ret=$this->database->execSQL($sql,4, 1);
    if ($ret[0])$result[0]='Fehler bei der Geometrieübernahme!';
    else $result[1]='Geometrien erfolgreich übernommen!';
    return $result;
	}
  
  function getDocLocation($id){
    #2005-11-24_pk
    $sql='SELECT link_datei FROM nachweisverwaltung.n_nachweise WHERE id ='.$id;
    $this->debug->write("<br>nachweis.php getDocLocation zum Anzeigen der Nachweise.",4);
    $queryret=$this->database->execSQL($sql,4, 0);    
    if ($queryret[0]) {
      $ret=$queryret;
    }
    else {
      $ret[0]=0;
      $rs=pg_fetch_assoc($queryret[1]);
      $ret[1]=$rs['link_datei'];
    }
    return $ret;
  }

  function istbelegt($var) {
    return ($var!='');
  }
		
}


#-----------------------------------------------------------------------------------------------------------------
################
#-> Festpunkte #
################

# 2016-11-03 H.Riedel - pkz durch pkn ersetzt, par durch art ersetzt
class Festpunkte {
  var $pkn;
  var $par;
  var $koordinaten;
  var $debug;
  var $database;
  var $dateiname;
  var $festpunkttabellenname;
  var $liste;

  ###################### Liste der Funktionen ####################################
  #
  # aktualisieren()
  # Festpunktdatei()
  # filebasenamepkn($filename)
  # getFestpunkte($pkn,$par,$vermarkt,$verhandelt,$polygon,$auftr_nr,$kmquad,$order)
  # getKilometerQuadrate()
  # is_valid_pfad()
  # ladenFestpunktdatei()
  # leereFestpunktTabelle()
  # moveFiles()
  # pkn2pfad()
  # pruefeSuchParameterPolygon($polygon)
  # uebernehmen()
  #
  ################################################################################

# 2016-11-03 H.Riedel - fp_punkte_temp durch fp_punkte_alkis ersetzt
  function __construct($dateiname,$database) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
    $this->dateiname=$dateiname;
    $this->tabellenname='fp_punkte_alkis';
  }

# 2016-11-03 H.Riedel - pkz durch pkn ersetzt, an ALKIS angepasst
  function createKVZdatei($antrag_nr, $stelle_id, $pkn = '') {
    # Diese Funktion erzeugt im Verzeichnis der recherchierten Antraege (RECHERCHEERGEBNIS_PATH)
    # ein Datei mit Koordinatenverzeichnis im Satzformat aus GEOI-Programm (Standardisiertes Ausgabeformat)
    # Ausgegeben werden alle Punkte, die zur antrag_nr gehören, die über die Abfrage getFestpunkte in $this->liste geschrieben wurden
    # Verzeichnispfad bilden und prüfen ob schon vorhanden, wenn nicht Verzeichnis anlegen
    if($pkn != '')$pkn = array_values($pkn);
#		if($stelle_id != '')$antrag_nr.='~'.$stelle_id;
		$ret=$this->getFestpunkte($pkn,'','','','',$antrag_nr,$stelle_id,'','pkn');
		if($stelle_id != '')$antrag_nr.='~'.$stelle_id;
    if($this->anzPunkte > 0){
			$kvzPfad=RECHERCHEERGEBNIS_PATH.$antrag_nr.'/';
#			$kvzPfad=RECHERCHEERGEBNIS_PATH.'ohne/';
			if (!is_dir($kvzPfad)) {
				mkdir($kvzPfad,0770); # erzeugt das Verzeichnis für den Auftrag, weil es das noch nicht gibt
			}
			$kvzPfad.='KVZ/';
			if (!is_dir($kvzPfad)) {
				mkdir($kvzPfad,0775); # erzeugt das Verzeichnis für das KVZ, weil es das noch nicht gibt
			}
			# Dateinamen bilden mit Pfadangabe
			$dateiname=$kvzPfad.KVZAUSGABEDATEINAME;
			if (!$fp=fopen($dateiname,'w')) {
				$errmsg="Kann die Datei $dateiname nicht öffnen";
			}
			else {
				if($antrag_nr == 'ohne'){
					$antrag_nr = '';
				}
				if ($ret[0]) {
					$errmsg="Festpunkte konnten nicht abgefragt werden.";
				}
				else{
					$zeile = KVZKOPF;
					$zeile.="\r\n"; # Zeilenumbruch
					for ($i=0;$i<$this->anzPunkte;$i++) {
						if($i!=0)$zeile = "";
						$p=$this->liste[$i];
						# Entfernen der - in pkn für Punktnummer ohne -
#						$p["pnr"]=str_replace("-","",$p["pkn"]);
            if ($p["kst"]=='') {
              $zeile.=str_repeat(" ",4); # 1-5 Wenn Koordinatenstatus nicht angegeben mit Leerstellen auffüllen
            }
            else { 
              $zeile.=$p["kst"]; # 1-5 Koordinatenstatus
            }
						$zeile.=" ".str_pad(trim($p["pkn"]),15," ",STR_PAD_LEFT); # Punktkennzeichen
						$zeile.=" ".str_pad(trim($p["abm"]),4,"0",STR_PAD_LEFT); # Vermarkungsart
            $zeile.=" ".sprintf("%12.3f","33".$p["rw"]); # 27-38 Rechtswert
            $zeile.=" ".sprintf("%11.3f",$p["hw"]); # 40-50 Hochwert
#						$zeile.=" "; # 47 Leerstelle
            if ($p["hoe"]=='') {
              $zeile.=str_repeat(" ",9); # 48-55 Wenn Höhe nicht angegeben mit Leerstellen auffüllen
            }
            else {
              $zeile.=" ".sprintf("%08.3f",$p["hoe"]); # 48-55 Höhe
            }
#						$zeile.=" "; # 56 Leerstelle
            if ($p["gst"]=='') {
              $zeile.=str_repeat(" ",5); # 48-55 Wenn Genauigkeitsstufe nicht angegeben mit Leerstellen auffüllen
            }
            else { 
              $zeile.=" ".$p["gst"]; # Genauigkeitsstufe
            }
            if ($p["vwl"]=='') {
              $zeile.=str_repeat(" ",5); # 48-55 Wenn Vertrauenswuerdigkeit nicht angegeben mit Leerstellen auffüllen
            }
            else { 
              $zeile.=" ".$p["vwl"]; # Vertrauenswuerdigkeit
            }
            if ($p["des"]=='') {
              $zeile.=str_repeat(" ",5); # 48-55 Wenn Description nicht angegeben mit Leerstellen auffüllen
            }
            else { 
              $zeile.=" ".$p["des"]; # Description
            }
						$zeile.=str_pad($p["par"],4," ",STR_PAD_LEFT); # Art des Punktes
						$zeile.=" "; # 65 Leerstelle
						$zeile.="\r\n"; # Zeilenumbruch
						if (!fwrite($fp,$zeile)) {
							$errmsg.="Kann folgende Zeile nicht in die Datei $dateiname schreiben:<br>".$zeile;
							$i=$this->anzPunkte; # Zum Abbruch der Schleife
						}
					}
					if (!fclose($fp)) {
						$errmsg.="Kann die Datei $dateiname nicht schließen.";
					}
				} # end of festpunkte konnten abgefragt werden
			}
			if ($errmsg!='') {
				$ret[0]=1;
				$ret[1]=$errmsg;
			}
			else {
				$ret[0]=0;
				if($this->anzPunkte > 0)$ret[1]=$this->anzPunkte." Zeilen in die Datei ".$dateiname." geschrieben.";
				$ret[2]=$dateiname;
			}
		}
    return $ret;
  }

# 2016-11-03 H.Riedel - the_geom durch wkb_geometry, pkz durch pkn ersetzt
  # 2006-02-19 pk koordinaten durch the_geom ersetzt
  function getBBoxAsRectObj($Festpunkte) {
    if (is_array($Festpunkte)) { $Liste=$Festpunkte;  } else { $Liste=array($Festpunkte); }
    $sql ="SELECT round(st_xmin(st_extent(wkb_geometry))) as xmin,round(st_ymin(st_extent(wkb_geometry))) as ymin";
    $sql.=",round(st_xmax(st_extent(wkb_geometry))) as xmax, round(st_ymax(st_extent(wkb_geometry))) as ymax";
    $sql.=" FROM nachweisverwaltung.fp_punkte_alkis WHERE pkn IN ('".$Liste[0]."'";
    for ($i=1;$i<count($Liste);$i++) {
      $sql.=",'".$Liste[$i]."'";
    }
    $sql.=")";
    #echo "<br>".$sql;
    $this->debug->write("<p>kataster.php->Festpunkte->getBBoxASRectObj",4);
    $ret=$this->database->execSQL($sql,4, 0);
    if ($ret[0]) {
      $ret[1]='<br>Fehler bei der Abfrage der Datenbank'.$ret[1];
    }
    else {
      $rect= ms_newRectObj();
      # Abfragen und zuordnen der Koordinaten der Box
      $rs=pg_fetch_array($ret[1]);
      $xmin=intval($rs['xmin']); $xmax=intval($rs['xmax']);
      $ymin=intval($rs['ymin']); $ymax=intval($rs['ymax']);
      # Aufweiten des Anzeigefensters, wenn nur nach einen Punkt gesucht wurde
      $weite=20;
      if ($xmax-$xmin==0) {
        $xmax=$xmax+$weite;
        $xmin=$xmin-$weite;
      }
      if ($ymax-$ymin==0) {
        $ymax=$ymax+$weite;
        $ymin=$ymin-$weite;
      }
      $rect->minx=$xmin; $rect->miny=$ymin;
      $rect->maxx=$xmax; $rect->maxy=$ymax;
      $ret[1]=$rect;
    }
    return $ret;
  }

# 2016-03-11 H.Riedel - pkz durch pkn ersetzt
  function filebasename2pkn($filebasename) {
    $kiloquad=substr($filebasename,0,-6);
#    $art=substr($filebasename,-6,-5);
    $nr=substr($filebasename,-6);
#    $pkz=$kiloquad.'-'.$art.'-'.$nr;
    $pkn=$kiloquad.$nr;
    return $pkn;
  }

# 2016-03-11 H.Riedel - pkz durch pkn ersetzt, pfadname an alkis angepasst
  function pkn2pfad($pkn) {
    # erzeugt aus einer pkz Angabe den Dateiname mit Pfadangabe für eine Einmessungsskizze.
    # png oder tif muss selbst noch hinterher angehängt werden.
    $kiloquad=substr($pkn,0,-6);
#    $filename=str_replace('-','',$pkz);
#    $pfadname=$kiloquad.'/'.$filename;
    $pfadname=$kiloquad.'/'.$pkn;
    return $pfadname;
  }

  function is_valid_pfad($pfadname) {
    $dirname=dirname($pfadname);
    $dateiname=basename(basename($pfadname,'.tif'),'.png');
    $kiloquad=substr($dateiname,0,-6);
    if ($dirname==$kiloquad) {
      return 1;
    }
    else {
      return 0;
    }
  }

  function moveFiles($moveListe) {
    # verschiebt Dateien aus $moveListe von alten an neuen Ort und überschreibt dabei ggf. vorhandene
    for ($i=0;$i<count($moveListe);$i++) {
      @rename ($moveListe[$i]['von'].'.tif',$moveListe[$i]['von'].'_temp.tif');
      @rename ($moveListe[$i]['von'].'.png',$moveListe[$i]['von'].'_temp.png');
    }
    for ($i=0;$i<count($moveListe);$i++) {
      if (!@rename ($moveListe[$i]['von'].'_temp.tif',$moveListe[$i]['nach'].'.tif')) {
        echo '<br>'.$moveListe[$i]['von'].'.tif nicht vorhanden!';
      }
      if (!@rename ($moveListe[$i]['von'].'_temp.png',$moveListe[$i]['nach'].'.png')) {
        echo '<br>'.$moveListe[$i]['von'].'.png nicht vorhanden!';
      }
    }
  }

# 2016-11-03 H.Riedel, pkz durch pkn ersetzt
  function checkSkizzen($pkn) {
    # ermittelt für pkn ob Einmessungskizzen in Form von tif oder png Dateien vorliegen,
    # Konvertieren des pkn in den Pfadnamen für die Einmessungsskizze
    $relPfad=$this->pkn2pfad($pkn);
    $skizze['tif']=0;
    if (file_exists(PUNKTDATEIPATH.$relPfad.'.tif')) {
      $skizze['tif']=1;
    }
    $skizze['png']=0;
    if (file_exists(PUNKTDATEIPATH.$relPfad.'.png')) {
      $skizze['png']=1;
    }
    $skizze['is_file']=0;
    if ($skizze['tif'] OR $skizze['png']) {
      $skizze['is_file']=1;
    }
    return $skizze;
  }

  function leereFestpunktTabelle() {
    # Abfragen wieviele Punkte in der Tabelle enthalten sind.
    $ret=$this->getAnzFestpunkte();
    if (!$ret[0]) { $anzPunkte=$ret[1]; }
    # Löschen aller Einträge in der Tabelle der Festpunkte
    $sql ="TRUNCATE TABLE nachweisverwaltung.".$this->tabellenname;
    $this->debug->write("<p>kataster.php->Festpunkte->leereFestpunktTabelle->leeren der Festpunkttabelle",4);
    $ret=$this->database->execSQL($sql,4, 0);
    if ($ret[0]) { # Fehler beim löschen
      $ret[1].='<br>Fehler beim leeren der Festpunkttabelle';
    }
    else {
      $ret[1]='<br>'.$anzPunkte['Gesamt'].' Zeilen aus Tabelle '.$this->tabellenname.' gelöscht.';
    }
    return $ret;
  }

# 2016-11-03 H.Riedel, pkz durch pkn ersetzt, art durch par ersetzt
  function getAnzFestpunkte() {
    $sql ="SELECT par, COUNT(pkn) AS anz FROM nachweisverwaltung.fp_punkte_alkis GROUP BY par ORDER BY par";
    $this->debug->write("<p>kataster.php->Festpunkte->getAnzFestpunkte",4);
    $ret=$this->database->execSQL($sql,4, 0);
    if ($ret[0]) {
      $ret[1].='<br>Fehler beim Abfragen der Punktanzahl.';
    }
    else {
      $anzPunkte['Gesamt']=0;
      $rs=pg_fetch_array($ret[1]); $anzPunkte['TP']=$rs['anz']; $anzPunkte['Gesamt']+=$rs['anz'];
      $rs=pg_fetch_array($ret[1]); $anzPunkte['AP']=$rs['anz']; $anzPunkte['Gesamt']+=$rs['anz'];
      $rs=pg_fetch_array($ret[1]); $anzPunkte['GP']=$rs['anz']; $anzPunkte['Gesamt']+=$rs['anz'];
      $rs=pg_fetch_array($ret[1]); $anzPunkte['GebP']=$rs['anz']; $anzPunkte['Gesamt']+=$rs['anz'];
      $rs=pg_fetch_array($ret[1]); $anzPunkte['BwP']=$rs['anz']; $anzPunkte['Gesamt']+=$rs['anz'];
      $rs=pg_fetch_array($ret[1]); $anzPunkte['SiP']=$rs['anz']; $anzPunkte['Gesamt']+=$rs['anz'];
      $rs=pg_fetch_array($ret[1]); $anzPunkte['OP']=$rs['anz']; $anzPunkte['Gesamt']+=$rs['anz'];
      $rs=pg_fetch_array($ret[1]); $anzPunkte['SVP']=$rs['anz']; $anzPunkte['Gesamt']+=$rs['anz'];
      $rs=pg_fetch_array($ret[1]); $anzPunkte['TopP']=$rs['anz']; $anzPunkte['Gesamt']+=$rs['anz'];
      $ret[1]=$anzPunkte;
    }
    return $ret;
  }

# 2016-11-03 H.Riedel, the_geom durch wkb_geometry, pkz durch pkn ersetzt, art durch par ersetzt
#                      Ergaenzung wg. Antrag_nr 'ohne'
  function getFestpunkte($pkn,$par,$vermarkt,$verhandelt,$polygon,$antrag_nr,$stelle_id,$kmquad,$order) {
    # Die Funktion liefert die Festpunkte nach verschiedenen Suchkriterien.
    if (is_array($pkn)) { $pknListe=$pkn; } else { $pknListe=array($pkn); }
    if (is_array($par)) { $parListe=$par; } else {
      if ($par=='') {
        $par='-1';
      }
      $parListe=array($par);
    }
    # Prüfen der Suchparameter
    # Es muss ein gültiges Polygon vorhanden sein.
    $ret=$this->pruefeSuchParameterPolygon($polygon);
    if ($ret[0]) {
      # Fehler, die Parameter sind nicht vollständig oder ungültig
      $errmsg.='Fehler bei der Prüfung der Suchparameter gefunden.<br>'.$ret[1];
    }
    else {
      $this->debug->write('Abfragen der Festpunkte:',4);
			rsort($pknListe);
			$sql ="SELECT p.*,st_asText(p.wkb_geometry) AS wkt_wkb_geometry FROM nachweisverwaltung.fp_punkte_alkis AS p";
			if ($antrag_nr!='') {
				if ($antrag_nr!='ohne') {
					$sql.=",nachweisverwaltung.fp_punkte2antraege AS p2a";
				}
			}
			$sql.=" WHERE 1=1";
			$anzPkn=count($pknListe);
			if ($pknListe[0]!='') {
				if ($anzPkn==1) {
					$sql.=" AND p.pkn LIKE '".$pknListe[0]."'";
				}
				else {
					$sql.=" AND p.pkn IN ('".$pknListe[0]."'";
					for ($i=1;$i<$anzPkn;$i++) {
						$sql.=",'".$pknListe[$i]."'";
					}
					$sql.=")";
				}
			}
			$anzArten=count($parListe);
			if ($parListe[0]!='-1') {
				$sql.=" AND p.par IN ('".$parListe[0];
				for ($i=1;$i<$anzArten;$i++) {
					$sql.="','".$parListe[$i];
				}
				$sql.="')";
			}
			if ($kmquad!='') {
				$sql.=" AND substring(p.pkn, 1, 9)='".$kmquad."'";
			}
			if ($polygon!='') {
				$sql.=" AND NOT DISJOINT('".$polygon."',p.wkb_geometry)";
			}
			if ($antrag_nr!='') {
				if ($antrag_nr!='ohne') {
				$sql.=" AND p.pkn=p2a.pkn AND p2a.antrag_nr='".$antrag_nr."'";
				}
			}
			if ($stelle_id!='') {
				$sql.=" AND p2a.stelle_id='".$stelle_id."'";
			}
			if ($order=='') {
				$order="pkn";
			}
			# nur die Aktuellen Punkte auswaehlen
			$sql.=" AND endet IS NULL AND endet_punktort IS NULL";
			$sql.=" ORDER BY ".$order;
			$ret=$this->database->execSQL($sql,4, 0);
			if ($ret[0]) {
				$errmsg.='Fehler bei der Abfrage der Festpunkte:<br>'.$ret[1];
			}
			else {
				$this->anzPunkte=0;
				while ($rs=pg_fetch_array($ret[1])) {
					$festpunkte[]=$rs;
					$this->anzPunkte++;
				}
				$ret[1]=$festpunkte;
			}
    }
    if ($errmsg!='') { $ret[1]=$errmsg; }
    $this->liste=$festpunkte;
    return $ret;
  } # ende funktion getFestpunkte

# 2016-11-03 H.Riedel - Kilometerquadrate aus pkn extrahieren
  function getKilometerQuadrate() {
    $kilometerquadrate=array();
    if ($this->anzPunkte>0) {
      # liefert alle KilometerQuadrate der Festpunkte, die sich aktuell in der Liste des Objektes befinden.
      $this->debug->write('<br>kataster.php->getKilometerQuadrate Ermitteln der Kilometerquadrate der Festpunkte:',4);
      # Auslesen aller Werte aus der Liste der Festpunkte
      foreach($this->liste AS $value) {
        # extrahieren der Bestandteile von pkz, getrennt durch "-"
#        $pkz=explode('-',$value['pkz']);
        if ($value['par']=='TP' OR $value['par']=='OP') {
          $pkn=substr($value['pkn'],0,-5);
        }
        else {
          $pkn=substr($value['pkn'],0,-6);
        } 
#        echo $pkn.', ';
        # zuweisen des ersten Teiles der pkn zur Liste der kilometerquadrate
        $kilometerquadrate[]=$pkn;
      }
      # Reduzieren der Liste der Kilometerquadrate um einmalig vorkommene Werte
      $kilometerquadrate=array_values(array_unique($kilometerquadrate));
    }
    return $kilometerquadrate;
  }

  function pruefeSuchParameterPolygon($polygon) {
    # Prüft ob das Polygon gültig ist für eine Suche nach Festpunkten
    return $ret;
  } # ende Funktion pruefeSuchParameterPolygon

}



#-----------------------------------------------------------------------------------------------------------------
############################
# Klasse Vermessungsstelle #
############################

class Vermessungsstelle {

  ###################### Liste der Funktionen ####################################
  #
  # function Vermessungsstelle($db)  - Construktor
  # function getVermStelleListe()
  #
  ################################################################################

  function __construct($db){
    $this->database=$db;
  }

  function getVermStelleListe(){
    # fragt alle Vermessungsstellen aus der Datenbank ab (id, Name)
    $sql = 'SELECT * FROM nachweisverwaltung.n_vermstelle ORDER BY ascii(name), name';
    $queryret=$this->database->execSQL($sql,4, 0);
    if ($queryret[0]) {
      $errmsg='Fehler bei der Abfrage der Vermessungsstellen in der Datenbank bei Statement: '.$sql;
    }
    else {
      while($rs=pg_fetch_array($queryret[1])) {
        $VermStListe['id'][]=$rs['id'];
        $VermStListe['name'][]=$rs['name'];
      }
      $errmsg='';
    }
    $ret[0]=$errmsg;
    $ret[1]=$VermStListe;
    return $ret;
  }
} # end of class Vermessungsstelle

#-----------------------------------------------------------------------------------------------------------------
#########################
# Klasse Vermessungsart #
#########################

class Vermessungsart {

  ###################### Liste der Funktionen ####################################
  #
  # function Vermessungsart($db)  - Construktor
  # function getVermart()
  #
  ################################################################################

  function __construct($db){
    $this->database=$db;
  }

  function getVermArtListe(){
    # fragt alle Vermessungsstellen aus der Datenbank ab (id, Name)
    $sql = 'SELECT * FROM nachweisverwaltung.n_vermart ORDER BY art';
    $queryret=$this->database->execSQL($sql,4, 0);
    if ($queryret[0]) {
      $errmsg='Fehler bei der Abfrage der Vermessungsarten in der Datenbank bei Statement: '.$sql;
    }
    else {
      while($rs=pg_fetch_array($queryret[1])) {
        $VermartListe['id'][]=$rs['id'];
        $VermartListe['art'][]=$rs['art'];
      }
      $errmsg='';
    }
    $ret[0]=$errmsg;
    $ret[1]=$VermartListe;
    return $ret;
  }
} # end of class Vermessungsart

?>
