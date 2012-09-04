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
# pkorduan@gmx.de peter.korduan@auf.uni-rostock.de                #
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
    
  ################### Liste der Funktionen ########################################################################################################
  #
  # pruefeEingabedaten($datum,$VermStelle,$art,$gueltigkeit,$stammnr,$Blattformat,$Blattnr,$Bilddatei_name)
  # dokumentenDateiHochladen($flurid,$stammnr,$art,$Blattnr,$Bilddatei_type,$Bilddatei,$Bilddatei_name,$Bilddatei_type,$Zielpfad,$Zieldatei)
  # nachweiseLoeschen($id,$loeschenDateien)
  # eintragenNeuesDokument($id,$Tag,$Monat,$Jahr,$Gemarkung,$Flur,$VermStelle,$art,$gueltigkeit,$stammnr,$Blattformat,$Blattnr,$link_datei,$umring)  
  # dokumentenDateiLoeschen()
  # getLinkDateiName()
  # aktualisierenDokument($id,$datum, $Gemarkung, $Flur, $VermStelle, $art, $gueltigkeit, $stammnr, $Blattformat,$Blattnr,$link_datei,$umring)
  # pruefeAnzeigedaten($stammnr,$f,$k,$g,$abfrage_art,$antr_nr_a,$antr_nr_b)
  # getNachweise(array($id),$FlurID,$polygon,$stammnr,$art_einblenden,$richtung,$abfrage_art,$order,$antr_nr_a,$antr_nr_b)
  # pruefe_zum_Auftrag_hinzufuegen($antr_nr_a,$antr_nr_b,$id)
  # zum_Auftrag_hinzufuegen($antr_nr_a,$antr_nr_b,$id)
  # getDocLocation($id)
  # zoomToNachweis();
  #
  ##################################################################################################################################################

  function Nachweis($database, $client_epsg) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
    $this->client_epsg=$client_epsg;
  }
  
  function getPolygon($poly_id) {
  $sql ="SELECT asText(the_geom) FROM u_polygon WHERE id = '".$poly_id."'";
  $query=pgsql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $poly_id = $query;
  return $poly_id;
  }
  
  function getZielDateiName($formvars) {
    #2005-11-24_pk
    $pathparts=pathinfo($formvars['Bilddatei_name']);
    $zieldateiname=$formvars['flurid'].'-'.str_pad(trim($formvars['stammnr']),STAMMNUMMERMAXLENGTH,'0',STR_PAD_LEFT).'-'.$formvars['artname'].'-'.str_pad(trim($formvars['Blattnr']),3,'0',STR_PAD_LEFT).'.'.$pathparts['extension'];
    #echo $zieldateiname;
    return $zieldateiname;
  }
  
  function changeDokument($formvars) {
    #2005-11-25_pk
    echo 'Änderung des Dokumentes mit der id: '.$formvars['id'];

    # 1. Starten der Transaktion
    $this->database->begintransaction();        

    # 2. Prüfen der Eingabewerte
    $ret=$this->pruefeEingabedaten($formvars['datum'],$formvars['VermStelle'],$formvars['art'],$formvars['gueltigkeit'],$formvars['stammnr'],$formvars['rissnummer'],$formvars['Blattformat'],$formvars['Blattnr'],$formvars['changeDocument'],$formvars['Bilddatei_name'],$formvars['pathlength'],$formvars['umring']);
    if ($ret[0]) {
      # Fehler bei den Eingabewerten entdeckt.  
      #echo '<br>Ergebnis der Prüfung: '.$ret;
      $errmsg=$ret[1];
    }
    else {
      echo '<br>Eingabewerte geprüft.';
      # 3. Abfragen des Namens der zu ändernden Dokumentendatei
      $ret=$this->getDocLocation($formvars['id']);
      if ($ret[0]) {
        # Abfrage des Namens der zu ändernden Dokumentendatei nicht erfolgreich
        $errmsg=$ret[1];
      }
      else {
        # Name der alten Dokumentendatei gefunden
        $doclocation=$ret[1];
        echo '<br>Speicherort der alten Dokumentendatei: '.$doclocation.' abgefragt.';
        if ($formvars['changeDocument']==0) {
          # Verwenden der vorhandenen Datei für die Bildung des neuen Dateinamens
          # unter dem die Datei nach der Sachdatenänderung gespeichert werden soll
          $formvars['Bilddatei_name']=$doclocation;
        }
        # Zusammensetzen des Dateinamen unter dem das Dokument gespeichert werden soll
        $formvars['zieldateiname']=$this->getZielDateiName($formvars);

        # 4. Ändern der Eintragung in der Datenbank
        $ret=$this->aktualisierenDokument($formvars['id'],$formvars['datum'],$formvars['flurid'],$formvars['VermStelle'],$formvars['art'],$formvars['andere_art'],$formvars['gueltigkeit'],$formvars['stammnr'],$formvars['Blattformat'],$formvars['Blattnr'],$formvars['rissnr'],$formvars['fortf'],$formvars['bemerkungen'],$formvars['umring'],$formvars['artname'].'/'.$formvars['zieldateiname']);
        if ($ret[0]) {
          # Aktualisierungsvorgang in der Datenbank nicht erfolgreich
          $errmsg=$ret[1];
        }
        else {
          # 5. Änderung erfolgreich, überschreiben der alten durch die neue Datei
          echo '<br>Eintragung in Datenbank geändert.';
          # Unterscheidung ob auch die Datei geändert werden soll
          if ($formvars['changeDocument']) {
            # Datei soll auch geändert werden.
            # 5.1 Löschen der bestehenden Bilddatei auf dem Server
            $ret=$this->dokumentenDateiLoeschen($doclocation);
            if ($ret!='') {
              # Alte Datei konnte nicht gelöscht werden   
              $errmsg=$ret;
            }
            else {
              # 5.2 Löschen der alten Datei war erfolgreich
              echo '<br>Alte Datei: '.$doclocation.' gelöscht';
              # Speichern der neuen Bilddatei auf dem Server
              $ret=$this->dokumentenDateiHochladen($formvars['flurid'],$formvars[NACHWEIS_PRIMARY_ATTRIBUTE],$formvars['artname'],$formvars['Bilddatei'],$formvars['zieldateiname']);
              if ($ret!='') {
                # Neue Datei konnte nicht hochgeladen werden
                $errmsg=$ret;
              }
              else {
                # 6. Speicherung der neuen Bilddatei war erfolgreich
                echo '<br>Neue Datei:'.$this->zieldateiname.' auf den Server geladen.';
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
            $zieldatei=NACHWEISDOCPATH.$formvars['flurid'].'/'.str_pad(trim($formvars['stammnr']),STAMMNUMMERMAXLENGTH,'0',STR_PAD_LEFT).'/'.$formvars['artname'].'/'.$formvars['zieldateiname'];
            if ($doclocation!=$zieldatei) {
              echo '<br>Speicherort der Datei muss geändert werden.';
              echo '<br>von:&nbsp; '.$doclocation;
              echo '<br>nach: '.$zieldatei; 
              # Datei muss an neuen Speicherort.
              # Speichern der Bilddatei unter neuem Pfad und Namen auf dem Server
              $ret=$this->dokumentenDateiHochladen($formvars['flurid'],$formvars['stammnr'],$formvars['artname'],$doclocation,$formvars['zieldateiname']);
              if ($ret!='') {
                # bestehende Datei konnte nicht an neuen Ort geschrieben werden
                $errmsg=$ret;
              }
              else {
                # vorhandene Datei wurde an neuen Ort geschrieben
                echo '<br>Alte Dateian an neuen Ort:'.$zieldatei.' geschrieben.';
                # 7 Löschen der bestehenden Bilddatei auf dem Server
                $ret=$this->dokumentenDateiLoeschen($doclocation);
                if ($ret!='') {
                  # Alte Datei konnte nicht gelöscht werden   
                  $errmsg=$ret;
                }
                else {
                  # 7.1 Löschen der alten Datei war erfolgreich
                  # Test ob Verzeichnisse leer, dann löschen
                  $directory = dirname($doclocation);
		              if(is_dir_empty($directory)){
		              	rmdir($directory);
		              	echo '<br>Altes Verzeichnis: '.$directory.' gelöscht';
		              	$directory = dirname($directory);
		              	if(is_dir_empty($directory)){
		              		rmdir($directory);
		              		echo '<br>Altes Verzeichnis: '.$directory.' gelöscht';
		              		$directory = dirname($directory);
			              	if(is_dir_empty($directory)){
			              		rmdir($directory);
			              		echo '<br>Altes Verzeichnis: '.$directory.' gelöscht';
			              	}
		              	}
		              }
                  echo '<br>Alte Datei: '.$doclocation.' gelöscht';
                  # 7.2 Umspeicherung der neuen Bilddatei war erfolgreich
                  # Erfolgreiches abschließen der Transaktion
                  $this->database->committransaction();
                  $ret[0]=0;
                  $ret[1]='Änderung des Datenbankeintrages und der Datei erfolgreich.';
                }
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
      # Es ist eine Fehlermeldung aufgetreten die Transaktion wird rückgängig gemacht.
      # Die Änderung in der Datenbank wird wieder zurückgenommen.
      $this->database->rollbacktransaction();
      $ret[0]=1;
      $ret[1]=$errmsg; 
    }
    return $ret;
  }
  
  function getDokumentarten(){
  	$sql="SELECT * FROM n_dokumentarten"; 
    $ret=$this->database->execSQL($sql,4, 0);    
    if (!$ret[0]) {
      while($rs=pg_fetch_array($ret[1])){
				$art['id'][] = $rs['id'];
				$art['art'][] = $rs['art'];
      }
    }
    return $art;
  }
  
  function getAnzahlNachweise($polygon){
    $sql="SELECT COUNT (the_geom) as anzahl FROM n_nachweise where intersects ('".$polygon."',the_geom)"; 
    $ret=$this->database->execSQL($sql,4, 0);    
    if (!$ret[0]) {
      $rs=pg_fetch_array($ret[1]);
      echo 'Anzahl: '.$rs['anzahl'];
    }
    return $anzahl;
  }
  
  function getBBoxAsRectObj($id,$source) {
    # ermittelt die Boundingbox des Nachweises $id
    $sql ='SELECT XMIN(EXTENT(the_geom)) AS minx,YMIN(EXTENT(the_geom)) AS miny';
    $sql.=',XMAX(EXTENT(the_geom)) AS maxx,YMAX(EXTENT(the_geom)) AS maxy';
    if ($source=='nachweis') { $sql.=' FROM n_nachweise '; }
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
  	$sql = "SELECT isvalid(geomfromtext('".$polygon."', ".$epsg."))";
  	$ret=$this->database->execSQL($sql,4, 1);
  	$rs = pg_fetch_row($ret[1]);
		if($rs[0] == 'f'){
			$result = 'invalid';
			return $result;
		}
  	$sql = "SELECT alknflur.gemkgschl, alknflur.flur FROM alkobj_e_fla, alknflur WHERE alknflur.objnr = alkobj_e_fla.objnr AND intersects(the_geom, TRANSFORM(geometryfromtext('".$polygon."', ".$epsg."), ".EPSGCODE."))";
  	$ret=$this->database->execSQL($sql,4, 1);
  	$result = 'f';	
  	while($rs = pg_fetch_row($ret[1])){
  		if($gemarkung == $rs[0] AND $flur == ltrim($rs[1], '0')){
  			$result = 't';
  			break;
  		}
  	}
  	return $result;
  }
  
	function check_poly_in_flurALKIS($polygon, $flur, $gemarkung, $epsg){
  	$sql = "SELECT isvalid(geomfromtext('".$polygon."', ".$epsg."))";
  	$ret=$this->database->execSQL($sql,4, 1);
  	$rs = pg_fetch_row($ret[1]);
		if($rs[0] == 'f'){
			$result = 'invalid';
			return $result;
		}
  	$sql = "SELECT f.land * 10000 + f.gemarkungsnummer, f.flurnummer FROM alkis.ax_flurstueck f WHERE intersects(wkb_geometry, TRANSFORM(geometryfromtext('".$polygon."', ".$epsg."), ".EPSGCODE_ALKIS."))";
  	$ret=$this->database->execSQL($sql,4, 1);
  	$result = 'f';	
  	while($rs = pg_fetch_row($ret[1])){
  		if($gemarkung == $rs[0] AND $flur == $rs[1]){
  			$result = 't';
  			break;
  		}
  	}
  	return $result;
  }
  
  function pruefeEingabedaten($datum, $VermStelle, $art, $gueltigkeit, $stammnr, $rissnummer, $Blattformat, $Blattnr, $changeDocument,$Bilddatei_name, $pathlength, $umring) {
    #echo '<br>Starten der Funktion zum testen der Eingabedaten.';
    # Test: wurde das Polgon für den raumbezug festgelegt?
    if ($umring == ''){
      $errmsg.='Bitte legen Sie das Polygon für den einzuarbeitenden Nachweis fest! \n';
    }
        
    # Test:  Sind die X-Y-Werte als Arrays übegeben worden?
    if (@is_array($pathy)!=1 OR @is_array($pathx)!=1){
      $errmasg.='X-Y-Koordinaten wurden nicht oder falsch gesetzt! \n';
    }
    
    # test auf korrekte Vermessungstelle 
    $sql='SELECT * FROM n_vermstelle WHERE id='.$VermStelle;
    $queryret=$this->database->execSQL($sql,4, 0);
    if ($queryret[0]) {
      $errmsg.='Fehler bei der Vermessungstellenauswahl! \n';
    }
    else {
      if (pg_num_rows($queryret[1])==0) {
        $errmsg.='Die Vermessungsstelle ist nicht bekannt. \n';
      }
    }
    
    # test des Datum
    if ($datum=='') {
      $errmsg.='Bitte geben Sie ein Datum an!\n';
      
    }
    # test der Jahresangabe
/*  if ($Jahr==''){
      $errmsg.='Bitte geben Sie das Jahr an! \n';
    }
    else{
      if (strlen($Jahr)!=4) {
        $errmsg.='Geben sie das Jahr in der richtigen Form an. \n' ;
      }
      else {
        # richtigkeit des Datums checken
        if (checkdate($Monat, $Tag, $Jahr)==false) {
          $errmsg.= 'Datum ist nicht korrekt angegeben! \n' ;
        }

        # prüfen ob Datum in Zukunft
        $realtime=time();
        $Zeit=mktime(0, 0, 0, $Monat, $Tag, $Jahr);
        if ($realtime < $Zeit) {
          $errmsg.='Das angegebene Datum liegt in der Zukunft! \n' ;
        } 
      }
    }
*/    
    
    #Test des Blattformat
    if ($Blattformat==''){
      $errmsg.='Bitte das Blattformat des Dokuments angeben! \n';
   }
    else{
      $nums= array ("A4","A3","SF");
      if(!in_array($Blattformat, $nums)){
        $errmsg.='Die Auswahl des Blattformat ist nicht korrekt! \n';
      }
    }
    # Test der Dokumentenart  
    if ($art==''){
        $errmsg.='Bitte wählen Sie die Art des einzugebenden Dokuments aus! \n';
    }
    else{
      $nums = array ("100","010","001","111");
      if (!in_array($art,$nums)) {
        $errmsg.='Die Auswahl der Dokumentenart ist nicht korrekt! \n';
      }
    }
    if ($gueltigkeit==''){
      $errmsg.='Bitte wählen Sie die Gültigkeit des einzugebenden Dokuments aus! \n';
    }
    else{
      $nums = array("1","0");
      if (!in_array($gueltigkeit,$nums)){
        $errmsg.='Die Angabe über die Gültigkeit des Dokuments ist nicht korrekt! \n';
      }
    }
    # Testen der Stammnummer
    if(NACHWEIS_PRIMARY_ATTRIBUTE == 'stammnr'){
	    $stammnr=trim($stammnr);
	    if ($stammnr == ''){ 
	      $errmsg.='Bitte geben Sie die Antragsnummer korrekt ein! \n';
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
	        $errmsg.='Ungültige Zeichen bei der Antragsnummer ! \n';
	      }
	    }
    }
  	# Testen der Rissnummer
    if(NACHWEIS_PRIMARY_ATTRIBUTE == 'rissnummer'){
	    $rissnummer=trim($rissnummer);
	    if ($rissnummer == ''){ 
	      $errmsg.='Bitte geben Sie die Rissnummer korrekt ein! \n';
	    }
	    else{
	      $nums = array ( "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0" );
	      $strenthalten=0;
	      for ($i=0;$i<strlen($stammnr);$i++) {
	        if (!in_array($rissnummer[$i],$nums)) {
	          $strenthalten=1;
	        }
	      }
	      if ($strenthalten==1) {
	        $errmsg.='Ungültige Zeichen bei der Rissnummer ! \n';
	      }
	    }
    }
    # Test der Blattnummer
    $Blattnr=trim($Blattnr);
    if ($Blattnr ==''){
      $errmsg.='Bitte geben Sie die Blattnummer ein! \n';
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
        $errmsg.='Die Blattnummer darf nur Ziffern und Buchstaben enthalten ! \n';
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

  function dokumentenDateiHochladen($flurid,$stammnr,$artname,$quelldatei,$zieldateiname) {
    #2005-11-24_pk
    # Speicherort für die Nachweisdatei bestimmen
    $pfad=NACHWEISDOCPATH.$flurid.'/';
    if (!is_dir($pfad)) {
      mkdir ($pfad, 0777);
    }
    $stammnr=str_pad(trim($stammnr),STAMMNUMMERMAXLENGTH,'0',STR_PAD_LEFT);
    $pfad.=$stammnr.'/';
    if (!is_dir($pfad)) {
      mkdir ($pfad, 0777);
    }
    $zielpfad=$pfad.$artname.'/';
    if (!is_dir($zielpfad)) {
      mkdir ($zielpfad, 0777);
    }
    if (is_file($zielpfad.$zieldateiname)){
      $errmsg=' Eine entsprechende Datei existiert bereits auf dem Server!';
    }
    else {
      copy($quelldatei,$zielpfad.$zieldateiname);
      if(!file_exists($zielpfad.$zieldateiname) OR filesize($zielpfad.$zieldateiname) == 0){
      	$errmsg=' Beim Laden der Datei auf den Server sind Fehler aufgetreten!';
      }
    }
    $this->link_datei=$artname.'/'.$zieldateiname;
    return $errmsg;     
  }
  
  function dokumentenDateiLoeschen($doclocation){
    if (file_exists($doclocation)) {
      $erfolg=unlink($doclocation);
      if (!$erfolg){  
        $errmsg.= 'Die Datei konnte nicht gelöscht werden, weil sie nicht existiert oder keine Zugriffsrechte bestehen! ';      
      }
      else {
        #echo '<br>Datei: '.$doclocation.' gelöscht.';
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
          $nachweisDatei=NACHWEISDOCPATH.$this->Dokumente[0]['flurid'].'/'.str_pad($this->Dokumente[0]['stammnr'],STAMMNUMMERMAXLENGTH,'0',STR_PAD_LEFT).'/'.$this->Dokumente[0]['link_datei'];
          if (file_exists($nachweisDatei)) {
            # Datei existiert und kann jetzt im Filesystem gelöscht werden
            if (unlink($nachweisDatei)) {
              # Datei wurde erfolgreich gelöscht
              $msg.='Datei '.$nachweisDatei.' wurde erfolgreich gelöscht.';
            }
            else {
              # Datei konnte nicht gelöscht werden
              $errmsg.='Datei konnte nicht vom Server gelöscht werden. Wahrscheinlich fehlende Zugriffsrechte.';
              $loeschenabbrechen=1;              
            }
          }
          else {
            # Datei existiert nicht
            $msg.='Die Datei '.$nachweisDatei.' konnte nicht gefunden werden.\nWahrscheinlich falscher Pfad/Dateiname\n';
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
      	$sql="DELETE FROM n_nachweise2dokumentarten WHERE nachweis_id = ".$idListe[$i].";";
        $sql.="DELETE FROM n_nachweise WHERE id=".$idListe[$i];
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
  
  function eintragenNeuesDokument($datum,$flurid,$VermStelle,$art,$andere_art,$gueltigkeit,$stammnr,$blattformat,$blattnr,$rissnr,$fortf,$bemerkungen,$zieldatei,$umring) {
    #2005-11-24_pk
    $this->debug->write('Einfügen der Metadaten zum neuen Nachweisdokument in die Sachdatenbank',4);
    $sql ="INSERT INTO n_nachweise (flurid,stammnr,art,blattnummer,datum,vermstelle,gueltigkeit,format,link_datei,the_geom,fortfuehrung,rissnummer,bemerkungen)";
    $sql.=" VALUES (".$flurid.",'".trim($stammnr)."','".$art."','".trim($blattnr)."','".$datum."'";
    $sql.=",'".$VermStelle."','".$gueltigkeit."','".$blattformat."','".$zieldatei."',GeometryFromText('".$umring."',".EPSGCODE.")";
    $sql.=",".$fortf.",'".$rissnummer."','".$bemerkungen."')";
		# echo '<br>Polygon-SQL: '.$sql;
    $ret=$this->database->execSQL($sql,4, 1);
    if($andere_art != ''){
    	$sql = "INSERT INTO n_nachweise2dokumentarten";
    	$sql .= " SELECT id, ".$andere_art." FROM n_nachweise WHERE oid = ".pg_last_oid($ret[1]);
    	#echo $sql;
    	$ret=$this->database->execSQL($sql,4, 1);	
    }
    if ($ret[0]) {
      # Fehler beim Eintragen in Datenbank
      $ret[1]='Auf Grund eines Datenbankfehlers konnte das Dokument nicht eingetragen werden!'.$ret[1];
    }
    return $ret;
  }
  
  function aktualisierenDokument($id,$datum,$flurid,$VermStelle,$art,$andere_art,$gueltigkeit,$stammnr,$Blattformat,$Blattnr,$rissnr,$fortf,$bemerkungen,$umring,$zieldateiname) {
    #2005-11-24_pk
    $this->debug->write('Aktualisieren der Metadaten zu einem bestehenden Nachweisdokument',4);
    $sql="UPDATE n_nachweise SET flurid='".$flurid."', stammnr='".trim($stammnr)."', art='".$art."'";
    $sql.=",blattnummer='".trim($Blattnr)."', datum='".$datum."', vermstelle='".$VermStelle."'";
    $sql.=",gueltigkeit='".$gueltigkeit."', format='".$Blattformat."',the_geom=GeometryFromText('".$umring."',".EPSGCODE."), link_datei='".$zieldateiname."'";
    $sql.=",fortfuehrung=".$fortf.",rissnummer='".$rissnr."',bemerkungen='".$bemerkungen."'";
    $sql.=" WHERE id = ".$id;
    #echo $sql;
    $ret=$this->database->execSQL($sql,4, 1);
    if($andere_art != ''){
    	$sql = "SELECT dokumentart_id FROM n_nachweise2dokumentarten WHERE nachweis_id = ".$id.";";
    	$query=@pg_query($this->database->dbConn,$sql);
	    $rs=pg_fetch_array($query);
	    if ($rs[0]!=''){
    		$sql = "UPDATE n_nachweise2dokumentarten SET dokumentart_id = ".$andere_art." WHERE nachweis_id = ".$id.";";
    		#echo $sql;
    		$ret=$this->database->execSQL($sql,4, 1);
	    }
	    else{
	    	$sql = "INSERT INTO n_nachweise2dokumentarten";
	    	$sql .= " SELECT id, ".$andere_art." FROM n_nachweise WHERE id = ".$id;
	    	#echo $sql;
	    	$ret=$this->database->execSQL($sql,4, 1);	
	    }	
    }
    if ($ret[0]) {
      # Fehler beim Eintragen in Datenbank
      $ret[1]='Auf Grund eines Datenbankfehlers konnte das Dokument nicht aktualisiert werden!'.$ret[1];
    }
    return $ret; 
  }
  
  function pruefeSuchParameterIndivNr($flurid,$stammnr) {
    #echo 'Indiv-nr: '.$flurid.'-'.$stammnr;
    $gemarkung=intval(substr($flurid,0,6));
    $flur=substr($flurid,6,9);
    # Überprüfen, ob die gegebenen Suchparameter ausreichend und gültig sind
    if ($gemarkung=='') {
      $errmsg.='\nFehler: Es ist keine Gemarkungsnummer angegeben worden.';
    }
    if (!is_integer($gemarkung)) {
      $errmsg.='\nFehler: Die Gemarkungsnummer ist keine ganze Zahl.';
    }
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
    $sql ="SELECT isvalid('".$umring."')";
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
  
  function getNachweise($id,$polygon,$FlurID,$stammnr,$rissnr,$fortf,$art_einblenden,$richtung,$abfrage_art,$order,$antr_nr, $datum = NULL, $VermStelle = NULL) {
    # Die Funktion liefert die Nachweise nach verschiedenen Suchverfahren.
    # Vor dem Suchen nach Nachweisen werden jeweils die Suchparameter überprüft    
    if (is_array($id)) { $idListe=$id; } else { $idListe=array($id); }
    $idselected=array_keys($idListe);
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
          $sql ="SELECT n.*,asText(Transform(n.the_geom, ".$this->client_epsg.")) AS wkt_umring, asSVG(Transform(n.the_geom, ".$this->client_epsg.")) AS svg_umring,v.name AS vermst,n2d.dokumentart_id as andere_art FROM n_vermstelle AS v, n_nachweise AS n";
          $sql.=" LEFT JOIN n_nachweise2dokumentarten n2d ON n2d.nachweis_id = n.id";
          $sql.=" WHERE CAST(n.vermstelle AS integer)=v.id AND n.id=".$id;
          #echo $sql;
          $this->debug->write("<br>nachweis.php getNachweise Abfragen der Nachweisdokumente.<br>",4);
          $ret=$this->database->execSQL($sql,4, 0);
          if ($ret[0]) { # Fehler in der Datenbankabfrage
            $errmsg.=$ret[1];
          }
          else { # Datenbankabfrage war fehlerfrei
            if (pg_num_rows($ret[1])==0) {
              $errmsg.='\nEs konnte kein Dokument gefunden werden.';
            }
            else {
              $this->Dokumente[0]=pg_fetch_array($ret[1]);
              $this->erg_dokumente=1;
            } # Ende Ergebnis ist korrekt
          } # Ende Abfrage war fehlerfrei
        } # Ende Suche nach Dokument
      } break;
      
      case "MergeIDs" : {
        $sql ="SELECT n.*,asText(Transform(n.the_geom, ".$this->client_epsg.")) AS wkt_umring, asSVG(Transform(n.the_geom, ".$this->client_epsg.")) AS svg_umring,v.name AS vermst,n2d.dokumentart_id as andere_art FROM n_vermstelle AS v, n_nachweise AS n";
        $sql.=" LEFT JOIN n_nachweise2dokumentarten n2d ON n2d.nachweis_id = n.id";
        $sql.=" WHERE CAST(n.vermstelle AS integer)=v.id AND n.id=".$idselected[0];
        #echo $sql;
        $this->debug->write("<br>nachweis.php getNachweise Abfragen der Nachweisdokumente.<br>",4);
        $ret=$this->database->execSQL($sql,4, 0);
        if ($ret[0]) { # Fehler in der Datenbankabfrage
          $errmsg.=$ret[1];
        }
        else { # Datenbankabfrage war fehlerfrei
        	$sql ="SELECT asText(memgeomunion(Transform(n.the_geom, ".$this->client_epsg."))) AS wkt_umring, asSVG(memgeomunion(Transform(n.the_geom, ".$this->client_epsg."))) AS svg_umring, memgeomunion(Transform(n.the_geom, ".$this->client_epsg.")) as geom";
					$sql.=" FROM n_nachweise AS n";
	        $sql.=" WHERE 1=1";
	        if ($idselected[0]!=0) {
	          $sql.=" AND n.id IN ('".$idselected[0]."'";
	          for ($i=1;$i<count($idselected);$i++) {
	            $sql.=",'".$idselected[$i]."'";
	          }
	          $sql.=")";
	        }
	        #echo $sql;
	        $ret1=$this->database->execSQL($sql,4, 0);
          if (pg_num_rows($ret[1])==0) {
            $errmsg.='\nEs konnte kein Dokument gefunden werden.';
          }
          else {
            $this->Dokumente[0]=pg_fetch_array($ret[1]);
            $geom = pg_fetch_array($ret1[1]);
            $this->Dokumente[0]['wkt_umring'] = $geom['wkt_umring'];
            $this->Dokumente[0]['svg_umring'] = $geom['svg_umring'];
            $this->Dokumente[0]['geom'] = $geom['geom'];
            $this->erg_dokumente=1;
          } # Ende Ergebnis ist korrekt
        } # Ende Suche nach Dokument
      } break;
      
      case "multibleIDs" : {
        $sql ="SELECT n.*,asText(Transform(n.the_geom, ".$this->client_epsg.")) AS wkt_umring,v.name AS vermst, n2d.dokumentart_id AS andere_art, d.art AS andere_art_name";
          $sql.=" FROM n_vermstelle AS v, n_nachweise AS n";
          $sql.=" LEFT JOIN n_nachweise2dokumentarten n2d"; 
					$sql.=" 		LEFT JOIN n_dokumentarten d ON n2d.dokumentart_id = d.id";
					$sql.=" ON n2d.nachweis_id = n.id";
        $sql.=" WHERE CAST(n.vermstelle AS integer)=v.id";
        if ($idselected[0]!=0) {
          $sql.=" AND n.id IN ('".$idselected[0]."'";
          for ($i=1;$i<count($idselected);$i++) {
            $sql.=",'".$idselected[$i]."'";
          }
          $sql.=")";
        }
        if($FlurID!=''){
          $sql.=" AND n.flurid='".$FlurID."'";
        }
        if($stammnr!=''){
          $sql.=" AND n.stammnr='".$stammnr."'";
        }
      	if($rissnr!=''){
          $sql.=" AND n.rissnummer='".$rissnr."'";
        }
      	if($fortf!=''){
          $sql.=" AND n.fortfuehrung=".$fortf;
        }
        if (substr($art_einblenden,0,1)) { $art[]='100'; }
        if (substr($art_einblenden,1,1)) { $art[]='010'; }
        if (substr($art_einblenden,2,1)) { $art[]='001'; }
        if (substr($art_einblenden,3,1)) { $art[]='111'; }
        if ($art_einblenden!='') {
          $sql.=" AND n.art IN ('".$art[0]."'";
          for ($i=1;$i<count($art);$i++) {
            $sql.=",'".$art[$i]."'";
          }
          $sql.=")";
        }

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
        #echo $sql;
        $this->debug->write("<br>nachweis.php getNachweise Abfragen der Nachweisdokumente.<br>",4);
        $ret=$this->database->execSQL($sql,4, 0);    
        if (!$ret[0]) {
          while ($rs=pg_fetch_array($ret[1])) {
            $nachweise[]=$rs;
          }
          $this->erg_dokumente=count($nachweise);
          $this->Dokumente=$nachweise;
        }
      } break;
      
      case "indiv_nr" : {
        # Prüfen der Suchparameter
        # Es mussen mindestens die Flur_id und die stammnr übergeben worden sein.
        $ret=$this->pruefeSuchParameterIndivNr($FlurID,$stammnr);
        if ($ret[0]) {
          # Fehler, die Parameter sind nicht vollständig oder ungültig
          $errmsg=$ret[1];
        }
        else {
          # Suchparameter sind gültig
          # Suche nach individueller Nummer
          #echo '<br>Suche nach individueller Nummer.';
          $sql ="SELECT n.*,asText(Transform(n.the_geom, ".$this->client_epsg.")) AS wkt_umring,v.name AS vermst, n2d.dokumentart_id AS andere_art, d.art AS andere_art_name";
          $sql.=" FROM n_vermstelle AS v, n_nachweise AS n";
          $sql.=" LEFT JOIN n_nachweise2dokumentarten n2d"; 
					$sql.=" 		LEFT JOIN n_dokumentarten d ON n2d.dokumentart_id = d.id";
					$sql.=" ON n2d.nachweis_id = n.id";
          $sql.=" WHERE CAST(n.vermstelle AS integer)=v.id";
          if ($idselected[0]!=0) {
            $sql.=" AND n.id IN ('".$idselected[0]."'";
            for ($i=1;$i<count($idselected);$i++) {
              $sql.=",'".$idselected[$i]."'";
            }
            $sql.=")";
          }
          if($FlurID!=''){
          	if(strpos($FlurID, '%')){
          		$sql.=" AND n.flurid::text LIKE '".$FlurID."'";
          	}
          	else{
            	$sql.=" AND n.flurid='".$FlurID."'";
          	}
          }
          if($stammnr!=''){
            $sql.=" AND n.stammnr='".$stammnr."'";
          }
	        if($rissnr!=''){
	          $sql.=" AND n.rissnummer='".$rissnr."'";
	        }
	      	if($fortf!=''){
	          $sql.=" AND n.fortfuehrung=".$fortf;
	        }
          if($datum != ''){
            $sql.=" AND n.datum = '".$datum."'";
          }
          if($VermStelle!=''){
            $sql.=" AND n.vermstelle = '".$VermStelle."'";
          }
          if (substr($art_einblenden,0,1)) { $art[]='100'; }
          if (substr($art_einblenden,1,1)) { $art[]='010'; }
          if (substr($art_einblenden,2,1)) { $art[]='001'; }
          if (substr($art_einblenden,3,1)) { $art[]='111'; }
          if ($art_einblenden!='') {
            $sql.=" AND n.art IN ('".$art[0]."'";
            for ($i=1;$i<count($art);$i++) {
              $sql.=",'".$art[$i]."'";
            }
            $sql.=")";
          }

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
          #echo $sql;
          $this->debug->write("<br>nachweis.php getNachweise Abfragen der Nachweisdokumente.<br>",4);
          $ret=$this->database->execSQL($sql,4, 0);    
          if (!$ret[0]) {
            while ($rs=pg_fetch_array($ret[1])) {
              $nachweise[]=$rs;
            }
            $this->erg_dokumente=count($nachweise);
            $this->Dokumente=$nachweise;
          }
        }
      } break;

      case "poly" : {
        # Prüfen der Suchparameter
        # Es muss ein gültiges Polygon vorhanden sein.
        $ret=$this->pruefeSuchParameterPolygon($polygon);
        if ($ret[0]) {
          # Fehler, die Parameter sind nicht vollständig oder ungültig
          $errmsg=$ret[1];
        }
        else {
          # Suche mit Suchpolygon
          #echo '<br>Suche mit Suchpolygon.';
          $this->debug->write('Abfragen der Nachweise die das Polygon schneiden',4);
          $sql ="SELECT n.*,asText(Transform(n.the_geom, ".$this->client_epsg.")) AS wkt_umring,v.name AS vermst, n2d.dokumentart_id AS andere_art, d.art AS andere_art_name";
          $sql.=" FROM n_vermstelle AS v, n_nachweise AS n";
          $sql.=" LEFT JOIN n_nachweise2dokumentarten n2d"; 
					$sql.=" 		LEFT JOIN n_dokumentarten d ON n2d.dokumentart_id = d.id";
					$sql.=" ON n2d.nachweis_id = n.id";
 					$sql.=" WHERE CAST(n.vermstelle AS integer)=v.id";
          $sql.=" AND NOT DISJOINT(Transform(GeometryFromText('".$polygon."',".$this->client_epsg."), ".EPSGCODE."),the_geom)";
          
          if (substr($art_einblenden,0,1)) { $art[]='100'; }
          if (substr($art_einblenden,1,1)) { $art[]='010'; }
          if (substr($art_einblenden,2,1)) { $art[]='001'; }
          if (substr($art_einblenden,3,1)) { $art[]='111'; }
          if ($art_einblenden!='') {
            $sql.=" AND n.art IN ('".$art[0]."'";
            for ($i=1;$i<count($art);$i++) {
              $sql.=",'".$art[$i]."'";
            }
            $sql.=")";
          }
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
          #echo $sql;        
          $ret=$this->database->execSQL($sql,4, 0);    
          if (!$ret[0]) {
            while ($rs=pg_fetch_array($ret[1])) {
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
        $sql ="SELECT n.*,v.name AS vermst, n2d.dokumentart_id AS andere_art, d.art AS andere_art_name";
        $sql.=" FROM n_nachweise2antraege AS n2a, n_vermstelle AS v, n_nachweise AS n";
        $sql.=" LEFT JOIN n_nachweise2dokumentarten n2d"; 
				$sql.=" 		LEFT JOIN n_dokumentarten d ON n2d.dokumentart_id = d.id";
				$sql.=" ON n2d.nachweis_id = n.id";
        $sql.=" WHERE CAST(n.vermstelle AS integer)=v.id AND n.id=n2a.nachweis_id";
        $sql.=" AND n2a.antrag_id='".$antr_nr."'";
        if (substr($art_einblenden,0,1)) { $art[]='100'; }
        if (substr($art_einblenden,1,1)) { $art[]='010'; }
        if (substr($art_einblenden,2,1)) { $art[]='001'; }
        if (substr($art_einblenden,3,1)) { $art[]='111'; }
        if ($art_einblenden!='') {
          $sql.=" AND n.art IN ('".$art[0]."'";
          for ($i=1;$i<count($art);$i++) {
            $sql.=",'".$art[$i]."'";
          }
          $sql.=")";
        }
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
        $ret=$this->database->execSQL($sql,4, 0);    
        if (!$ret[0]) {
          while ($rs=pg_fetch_array($ret[1])) {
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
  
  function getNachw2Antr($antr_nr){
    # Funktion liefert alle recherchierten Nachweis-ID´s zurück, die zu einer 
    # Antragsnummer abgelegt wurde.
    $sql="SELECT * FROM n_nachweise2antraege WHERE antrag_id='".$antr_nr."'";
    # echo '<br>'.$sql;
    $queryret=$this->database->execSQL($sql,4, 0);
    $this->debug->write("<br>nachweis.php Recherche nach den Nachweisen, die zu einer Antrnr gespeichert wurden.<br>".$sql,4);    
    if($queryret[0]) {
      $errmsg='Es ist ein Fehler bei der Recherche der Nachweise zur Antragsnummer aufgetreten';
    }
    else {
      if(pg_num_rows($queryret[1])!=0){
        while ($rs=pg_fetch_array($queryret[1])) {
          $ergebnis[$rs['nachweis_id']]=$rs['nachweis_id'];
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
  
  function pruefe_Auftrag_hinzufuegen_entfernen($antr_nr){
    # Funktion dient zum Prüfen der Eingaben beim Arbeitsschritt Auftrag_hinzufügen oder Auftrag_entfernen
    $strenthalten=0;
    if($antr_nr==''){
      $errmsg.='Fehler bei der Angabe der Antragsnummer: '.$antr_nr;
    }
    else {   
      $sql="SELECT * FROM n_nachweise2antraege";
      $sql.=" WHERE antrag_id ='".$antr_nr."'";
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
  
  function zum_Auftrag_hinzufuegen($antrag_id,$nachweis_id){
    #echo '<br>Start der Funktion zum_Autrag_hinzufuegen';
    # Umsortierung der übergebenen ids
    $idselected=array_keys($nachweis_id);
    for ($i=0;$i<count($idselected);$i++) {
      # Abfragen ob die Zuordnung schon existiert.
      $sql ="SELECT * FROM n_nachweise2antraege";
      $sql.=" WHERE nachweis_id=".$idselected[$i]." AND antrag_id='".$antrag_id."'";
      $ret=$this->database->execSQL($sql,4, 0);
      if ($ret[0]) { # Fehler bei der Abfrage
        $errmsg='\nFehler beim Abfragen, ob Eintrag existiert.';
      }
      else {
        # 
        if (pg_num_rows($ret[1])>0) {
          # Die Zuordnung existiert schon, nicht neu hinzufügen
          #echo '<br>Dokument mit id: '.$idselected[$i].' ist schon zu Antrag id: '.$antrag_id.' zugeordnet.';
        }
        else {
          # Zuordnung in Datenbank schreiben
          $sql ="INSERT INTO n_nachweise2antraege (nachweis_id,antrag_id)";
          $sql.=" VALUES (".$idselected[$i].",'".$antrag_id."')";
          $ret=$this->database->execSQL($sql,4, 1);    
          if ($ret[0]) {
            $this->debug->write("<br>Fehler beim hinzufuegen der Dokumente zur Auftragsnummer: ".__LINE__,4);
            $errmsg='\nFehler beim hinzufuegen der Dokumente zur Auftragsnummer';
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
      $ret[0]=0; $ret[1]='\nNachweise erfolgreich zum Auftrag hinzugefügt.';
    }
    return $ret;
  }

  function aus_Auftrag_entfernen($antr_nr,$id){
    #Funktion löscht Nachweise, die unter einer Antragsnummer recherchiert wurde. 
    $idselected=array_keys ($id);
    for ($i=0;$i<count($idselected);$i++) {
      $sql ="DELETE FROM n_nachweise2antraege";
      $sql.=" WHERE antrag_id='".$antr_nr."' AND nachweis_id='".$idselected[$i]."'";
      $ret=$this->database->execSQL($sql,4, 1);
      if ($ret[0]) {
        $this->debug->write("<br>Fehler beim entfernen der Dokumente zur Auftragsnummer: ".__LINE__,4);
        $ret[1].='\nFehler beim entferen aus der Auftragsnummer!';
      }
      else{
        $ret[1]='\nDokumente erfolgreich aus Antrag entfernt!';
        echo '<br>Dokument mit id: '.$idselected[$i].' aus Antrag id: '.$antrag_id.' entfernt.';
      }
    }
    return $ret; 
  }
  
  function getDocLocation($id){
    #2005-11-24_pk
    $sql='SELECT flurid,'.NACHWEIS_PRIMARY_ATTRIBUTE.',link_datei FROM n_nachweise WHERE id ='.$id;
    $this->debug->write("<br>nachweis.php getDocLocation zum Anzeigen der Nachweise.",4);
    $queryret=$this->database->execSQL($sql,4, 0);    
    if ($queryret[0]) {
      $ret=$queryret;
    }
    else {
      $ret[0]=0;
      $rs=pg_fetch_array($queryret[1]);
      $ret[1]=NACHWEISDOCPATH.$rs['flurid'].'/'.str_pad($rs[NACHWEIS_PRIMARY_ATTRIBUTE],STAMMNUMMERMAXLENGTH,'0',STR_PAD_LEFT).'/'.$rs['link_datei'];
    }
    return $ret;
  }

  function istbelegt($var) {
    return ($var!='');
  }
}
?>
