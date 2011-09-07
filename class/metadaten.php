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
#####################################
# Klassen zur Metadatenverarbeitung #
#####################################

########################
# Klasse Metadatensatz #
########################
class metadatensatz {
  var $MD_id;
  var $debug;
    
  ################### Liste der Funktionen #######################
  #
  ################################################################

  function metadatensatz($MD_id,$db) {
    global $debug;
    $this->debug=$debug;
    if ($MD_id!='') {
      $this->MD_id=$MD_id;
    }
    $this->database=$db;
  }
  
  function getMetadaten($md) {
    # Liesst Metadatenwerte zu einer übergegebenen Metadatensatz_id
    $ret=$this->database->getMetadata($md);
    if ($ret[0]) {
      $ret[1]='Fehler beim Abfragen der Datenbank'.$ret[1];
    }
    else {
      $this->anzMetadatensaetze=count($ret[1]);
    }
    return $ret;
  }
  
  function getMetadatenQuickSearch($md) {
    # Durchsucht die Datenbank mit der Schnellsuche nach Metadatensätzen
    $ret=$this->database->getMetadataQuickSearch($md);
    if ($ret[0]) {
      $ret[1]='Fehler beim Abfragen der Datenbank'.$ret[1];
    }
    else {
      $this->anzMetadatensaetze=count($ret[1]);
    }
    return $ret;
  }
  
  function readDefaultValues($user) {
    #2005-11-29_pk
    # Weißt Standardwerte zu den Metadatenfeldern zu und liefert diese als Array zurück
    $md['mdfileid']=rand();
    $md['mddatest']=date('Y-m-d');
    $md['reseddate']=date('Y-m-d');
    $md['validfrom']=date('Y-m-d');
    $md['validtill']=date('Y-m-d',mktime(0, 0, 0, date('m'),  date('d'),  date('Y')+1));
    $md['westbl']=round($user->rolle->oGeorefExt->minx);
    $md['eastbl']=round($user->rolle->oGeorefExt->maxx);
    $md['southbl']=round($user->rolle->oGeorefExt->miny);
    $md['northbl']=round($user->rolle->oGeorefExt->maxy);
    $md['serviceversion']='1.0.0';
    return $md;
  }
  
  function getKeywords($id,$keyword,$keytyp,$thesaname,$metadata_id,$order) {
    $ret=$this->database->getKeywords($id,$keyword,$keytyp,$thesaname,$metadata_id,$order);
    return $ret;
  }
  
  function speichern($metadaten) {
    #2005-11-29_pk
    # Prüfen der Metadaten
    if ($ret[0]) {
      $ret[1]='<br>Fehler beim Prüfen der Metadaten.'.$ret[1];
    }
    else {
      if ($metadaten['id']) {
        # Es handelt sich um schon vorhandene Metadaten, die aktualisiert werden sollen
        # UPDATE
      }
      else {
        # Neuer Metadatensatz INSERT
        $ret=$this->database->insertMetadata($metadaten);
      }
    }
    return $ret;
  }
  
  function checkMetadata($md) {
    #2005-11-29_pk
    # Prüft die eingegebenen Metadaten auf Richtigkeit und formt gegebenenfalls Datentypen um
    if ($md['restitle']=='') { $errmsg='<br>Geben Sie einen Titel an.'; }
    if ($md['mdfileid']=='') {
      $errmsg='<br>Geben Sie eine eindeutige Metadatenid an.';
    }
    else {
      # Abfragen ob es diese eindeutige Kennung schon in der Datenbank gibt
      $ret=$this->database->getMetadataByMdFileID($md['mdfileid']);
      var_dump($ret);
      if ($ret[0]) {
        $errmsg.='<br>Fehler beim Abfragen der neuen Metadatenid.<br>'.$ret[1];
      }
      else {
        if ($ret[1]['mdfileid']==$md['mdfileid']) {
          $errmsg.='<br>Die angegebene Identifikation ist schon vorhanden.';
        }
      }
    }

    if ($md['postcode']=='') { $md['postcode']='NULL'; }
    if ($md['vector_scale']=='') { $md['vector_scale']='NULL'; }
    if ($md['mdcontact']=='') { $md['mdcontact']='NULL'; }
    if ($md['spatrepinfo']=='') { $md['spatrepinfo']='NULL'; }
    if ($md['refsysinfo']=='') { $md['refsysinfo']='NULL'; }
    if ($md['mdextinfo']=='') { $md['mdextinfo']='NULL'; }
    if ($md['dataidinfo']=='') { $md['dataidinfo']='NULL'; }
    if ($md['continfo']=='') { $md['continfo']='NULL'; }
    if ($md['distinfo']=='') { $md['distinfo']='NULL'; }
    if ($md['databinding']=='') { $md['databinding']=0; }
    # Zusammenfassen der selectierten Schlagwörter
    if ($md['selectedthemekeywordids']=='') {
      $errmsg.='<br>Geben Sie thematische Schlagwörter ein.';
    }
    else {
      $keywords=array_unique(explode(", ",$md['selectedthemekeywordids']));
      $md['selectedthemekeywordids']=$keywords[0];
      for ($i=1;$i<count($keywords);$i++) {
        $md['selectedthemekeywordids'].=", ".$keywords[$i];
      }
    }
    if ($md['selectedplacekeywordids']=='') {
      $errmsg.='<br>Geben Sie räumliche Schlagwörter ein.';
    }
    else {
      $keywords=array_unique(explode(", ",$md['selectedplacekeywordids']));
      $md['selectedplacekeywordids']=$keywords[0];
      for ($i=1;$i<count($keywords);$i++) {
        $md['selectedthemekeywordids'].=", ".$keywords[$i];
      }
    }
    $md['umring'] ='POLYGON(('.$md['eastbl'].' '.$md['southbl'].','.$md['westbl'].' '.$md['southbl'];
    $md['umring'].=','.$md['westbl'].' '.$md['northbl'].','.$md['eastbl'].' '.$md['northbl'];
    $md['umring'].=','.$md['eastbl'].' '.$md['southbl'].'))';
    if ($errmsg!='') {
      $ret[0]=1; $ret[1]=$errmsg;
    }
    else {
      $ret[0]=0; # fehlerfrei
      $ret[1]=$md;
    }
    return $ret;
  }
} # Ende Klasse Metadaten

################
# Klasse Notiz #
################
class notiz {
  var $oid;
  var $debug;
    
  ################### Liste der Funktionen #######################
  # aktualisierenNotiz
  # eintragenNeueNotiz
  # notiz
  # pruefeEingabedaten
  # NotizLoeschen
  # getKategorie
  # selectKategorie
  # notizKategorieAenderung
  # getNotizen
  ################################################################

  function notiz($db) {
    global $debug;
    $this->debug=$debug;
    $this->database=$db;
  }
  
 function pruefeEingabedaten($formvars) {
    $anlegenKategorie = $this->getKategorie($formvars['kategorie_id'], $this->formvars['stelle_id'], NULL, 'true', NULL);
    if($anlegenKategorie == NULL){
    	$ret[1].='\nAnlegen einer Notiz in dieser Kategorie nicht erlaubt.';
    	$ret[0]=1;
    }
    $ret[0]=0;
    if ($formvars['textposition']=='') {
      $ret[1].='\nGeben Sie die Position für die Textanzeige an.';
      $ret[0]=1;
    }
    return $ret;
  }

  function getKategorie($kat_id, $stelle_id, $read, $write, $change){
    $this->debug->write('<br>file:metadaten.php class:notiz function getKategorie<br>Abfragen der Notiz-Kategorie in<br>PostGIS',4);
    $sql = 'SELECT * FROM q_notiz_kategorien ';
    if($stelle_id OR $read OR $write OR $change){
    	$sql.= ' , q_notiz_kategorie2stelle WHERE q_notiz_kategorien.id = q_notiz_kategorie2stelle.kat_id';
    }
    else{
    	$sql.= ' WHERE 1 = 1';
    }
    if($stelle_id){
      $sql.= ' AND q_notiz_kategorie2stelle.stelle = '.$stelle_id;
    }
    if($kat_id){
      $sql.= ' AND q_notiz_kategorien.id = '.$kat_id;
    }
    if($read){
      $sql.= ' AND q_notiz_kategorie2stelle.lesen = '.$read;
    }
    if($write){
      $sql.= ' AND q_notiz_kategorie2stelle.anlegen = '.$write;
    }
    if($change){
      $sql.= ' AND q_notiz_kategorie2stelle.aendern = '.$change;
    }
    #echo $sql;
    $ret=$this->database->execSQL($sql,4, 0);
    if ($ret[0]) {
      # Fehler beim Abfragen in Datenbank
      $ret[1]='\nAuf Grund eines Datenbankfehlers konnte die Notiz nicht abgefragt werden!\n'.$ret[1];
    }
    else {
      while($rs=pg_fetch_array($ret[1])) {
        $kategorien[]=$rs;
      }
    }
    return $kategorien; 
  }
  
  function insertKategorie($newKategorie){
  	# Abfragen ob die Kategorie schon existiert
  	$a_sql=" SELECT * FROM q_notiz_kategorien WHERE kategorie='".$newKategorie."'";
  	$ret=$this->database->execSQL($a_sql,4, 0);
    $rs=pg_fetch_array($ret[1]);

    if ($rs[1]==''){
    # Wenn nicht, dann in Datenbank eintragen 
    	$i_sql=" INSERT INTO q_notiz_kategorien (kategorie) VALUES ('".$newKategorie."')";
    	$ret=$this->database->execSQL($i_sql,4, 0);  
    	$error='Kategorie "'.$newKategorie.'" wurde erfolgreich eingetragen.';  	
    }
    else {
    	$error='Fehler: Die Kategorie "'.$newKategorie.'" existiert bereits!';
    }
    return $error;
  }
  
  function selectKategorie($id,$kategorie,$max) {
    $this->debug->write('<br>file:metadaten.php class:notiz function selectKategorie<br>Abfragen der Notiz-Kategorie in<br>PostGIS',4);
    $sql = "SELECT * FROM q_notiz_kategorien WHERE 1 = 1";
    if ($id!='') {
    	$sql.=" AND id='".$id."'";
    }
    if ($kategorie!='') {
    	$sql.=" AND kategorie='".$kategorie."'";    	
    }
    if ($max!=''){
    	$sql.=" AND MIN(id)";
    }
    $ret=$this->database->execSQL($sql,4, 0);
    if ($ret[0]) {
      # Fehler beim Abfragen in Datenbank
      $ret[1]='\nAuf Grund eines Datenbankfehlers konnte die Kategorien nicht abgefragt werden!\n'.$ret[1];
    }
    else {
      while($rs=pg_fetch_array($ret[1])) {
        $sKategorien[]=$rs;
      }
    }
    return $sKategorien;
  }# END of function selectKategorie
  
  function selectKat2stelle($kategorie){
  	$this->debug->write('<br>file:metadaten.php class:notiz function selectKat2Stelle <br>Abfragen der Rechte pro Stelle für eine Kategorie<br>PostGIS',4);
    $sql = "SELECT * FROM q_notiz_kategorie2stelle WHERE (1=1)";
    $sql.= " AND kat_id='".$kategorie."'";
    $ret=$this->database->execSQL($sql,4, 0);
    while($rs=pg_fetch_array($ret[1])) {
      $kat2stelle[]=$rs;
    } 
    return $kat2stelle;
  }
  
  function notizKategorieAenderung($formvars){
  # Diese Funktion ändert und fügt hinzu oder entfernt Zugriffe von Stellen
  # auf die entsprechenden Kategorien der Notizen  
  	for ($i=0; $i<$formvars['stellenanzahl'] ;$i++) {
  		if ($formvars['checkstellelesen'.$i]!='' OR $formvars['checkstelleanlegen'.$i]!='' OR $formvars['checkstelleaendern'.$i]!=''){
  			# abfragen ob stelle_und_Kategorie schon existieren
  			$a_sql =" SELECT * FROM q_notiz_kategorie2stelle WHERE (1=1)";
  			$a_sql.=" AND stelle='".$formvars['checkstelle'.$i]."' AND kat_id='".$formvars['kategorie_id']."'";
  			$ret=$this->database->execSQL($a_sql,4, 0);
  			$rs=pg_fetch_array($ret[1]);
  			if ($rs!=''){
  				# wenn ja, dann "update" durchführen
  				if ($formvars['checkstellelesen'.$i]=='') {$formvars['checkstellelesen'.$i]='0'; }
  				if ($formvars['checkstelleanlegen'.$i]=='') {$formvars['checkstelleanlegen'.$i]='0'; }
  				if ($formvars['checkstelleaendern'.$i]=='') {$formvars['checkstelleaendern'.$i]='0'; }  				
  				$u_sql =" UPDATE q_notiz_kategorie2stelle SET lesen='".$formvars['checkstellelesen'.$i]."', anlegen='".$formvars['checkstelleanlegen'.$i]."', aendern='".$formvars['checkstelleaendern'.$i]."'";
  				$u_sql.=" WHERE stelle='".$formvars['checkstelle'.$i]."' AND kat_id='".$formvars['kategorie_id']."'";
  			  $ret=$this->database->execSQL($u_sql,4, 0);
    		} 	
    		if ($rs==''){
    			# wenn nicht dann "insert" ausführen
    			if ($formvars['checkstellelesen'.$i]=='') {$formvars['checkstellelesen'.$i]='0'; }
  				if ($formvars['checkstelleanlegen'.$i]=='') {$formvars['checkstelleanlegen'.$i]='0'; }
  				if ($formvars['checkstelleaendern'.$i]=='') {$formvars['checkstelleaendern'.$i]='0'; } 
    			$i_sql =" INSERT INTO q_notiz_kategorie2stelle (stelle,kat_id,lesen,anlegen,aendern)";
    			$i_sql.=" VALUES ('".$formvars['checkstelle'.$i]."','".$formvars['kategorie_id']."','".$formvars['checkstellelesen'.$i]."','".$formvars['checkstelleanlegen'.$i]."','".$formvars['checkstelleaendern'.$i]."')";
    			$ret=$this->database->execSQL($i_sql,4, 0);
    		}
  		} 
  		if ($formvars['checkstellelesen'.$i]=='' AND $formvars['checkstelleanlegen'.$i]=='' AND $formvars['checkstelleaendern'.$i]==''){
  			# abfragen ob stelle_und_Kategorie existieren
  			$sql =" SELECT * FROM q_notiz_kategorie2stelle WHERE (1=1)";
  			$sql.=" AND stelle='".$formvars['checkstelle'.$i]."' AND kat_id='".$formvars['kategorie_id']."'";
  			$ret=$this->database->execSQL($sql,4, 0);
  			$rs=pg_fetch_array($ret[1]);
  			if ($rs!=''){
  				# wenn ja, dann wird dieser Eintrag komplett gelöscht
  				$d_sql =" DELETE FROM q_notiz_kategorie2stelle WHERE (1=1)";
  				$d_sql.=" AND stelle='".$formvars['checkstelle'.$i]."' AND kat_id='".$formvars['kategorie_id']."'";
  				$ret=$this->database->execSQL($d_sql,4, 0);
  			}  			
  		} 
  		
  	} # ende der FOR-Schleife
  } # END of function notizKategorieAenderung
  
  function notizKategorieLoeschen($kategorie,$plus_notiz){
  	# Funktion zum löschen der Kategorie
  	$sql="DELETE FROM q_notiz_kategorien WHERE q_notiz_kategorien.id='".$kategorie."'";
  	$ret=$this->database->execSQL($sql,4, 0);
  	$sql="DELETE FROM q_notiz_kategorie2stelle WHERE kat_id='".$kategorie."'";
  	$ret=$this->database->execSQL($sql,4, 0);
  	if ($plus_notiz=='1'){
  		# wenn zuvor gewählt, dann werden hier alle Notizen zur Kategorie mitgelöscht
  		$sql="DELETE FROM q_notizen WHERE kategorie_id='".$kategorie."'";
  	  $ret=$this->database->execSQL($sql,4, 0);
  	}
  }# END of function notizKatVerwaltung

  function getNotizen($oid,$kategorie,$person,$vondatum,$bisdatum) {
    $this->debug->write('<br>file:metadaten.php class:notiz function getNotizen<br>Abfragen der Daten zu Notizen in<br>PostGIS',4);
    $sql ="SELECT oid,notiz,kategorie_id,person,datum,AsText(the_geom) AS textgeom, AsSVG(the_geom) AS svggeom FROM q_notizen WHERE (1=1)";
    if ($oid!='' AND $oid!=0) {
      $sql.=" AND oid=".$oid;
    }
    if ($kategorie!='') {
      $sql.=" AND kategorie_id='".$kategorie."'";
    }
    if ($person!='') {
      $sql.=" AND person='".$person."'";
    }
    $sql.=" ORDER BY datum DESC";
    #echo $sql;
    $ret=$this->database->execSQL($sql,4, 0);
    if ($ret[0]) {
      # Fehler beim Abfragen in Datenbank
      $ret[1]='\nAuf Grund eines Datenbankfehlers konnte die Notiz nicht abgefragt werden!\n'.$ret[1];
    }
    else {
      while($rs=pg_fetch_array($ret[1])) {
        $notizen[]=$rs;
      }
      $ret[1]=$notizen;
    }
    return $ret; 
  }
  
  function eintragenNeueNotiz($formvars) {
  	# in formvars wird übergeben:
  	# die notiz selbst
  	# die kategorie die der Notiz zugeordnet werden soll
  	# die Person, die die Notiz erstellt hat,
  	# die Position wo sich die Notiz befinden soll
  	# und zusätzlich seit 2006-06-21:
  	# epsg_von: enthält den epsgcode in dem die Position der Notiz erfasst wurde
  	# das entspricht dem epsg code der rolle
  	# epsg_nach: enthält den epsgcode die der Tabelle q_notizen zugeordnet wurde
  	# das entspricht dem epsg code des Layers Notizen
    $this->debug->write('<br>file:metadaten.php class:notiz function eintragenNeueNotiz<br>Einfügen der Daten zu einer Notiz in<br>PostGIS',4);
    $sql ="INSERT INTO q_notizen (notiz,kategorie_id,person,datum,the_geom)";
    $sql.=" VALUES ('".$formvars['notiz']."','".$formvars['kategorie_id']."','".$formvars['person']."','".date("Y-m-d",time())."'";
    #$sql.=",GeometryFromText('".$formvars['textposition']."',".EPSGCODE."))";
    # seit 2006-06-21 wird von dem epsg-code des Viewers in den epsg code der Datenlayer transformiert
    # der epsg-code des Views ist an die Rolle gebunden und wird aus Datenbank abgefragt
    # als epsg-code der Tabelle Notiz wird die Konstante EPSGCODE aus config.php genommen.
    $formvars['epsg_nach']=EPSGCODE;
    $sql.=",transform(GeometryFromText('".$formvars['textposition']."',".$formvars['epsg_von']."),".$formvars['epsg_nach']."))";
    #echo $sql;
    $ret=$this->database->execSQL($sql,4, 1);
    if ($ret[0]) {
      # Fehler beim Eintragen in Datenbank
      $ret[1]='\nAuf Grund eines Datenbankfehlers konnte die Notiz nicht eingetragen werden!\n'.$ret[1];
    }
    return $ret; 
  }

  function aktualisierenNotiz($oid,$formvars) {
    $this->debug->write('<br>file:metadaten.php class:notiz function aktualisierenNotiz<br>Aktualisieren der Daten zu einer Notiz in<br>PostGIS',4);
    $sql ="UPDATE q_notizen SET notiz='".$formvars['notiz']."',kategorie_id='".$formvars['kategorie_id']."'";
    $sql.=",person='".$formvars['person']."',datum='".date("Y-m-d",time())."'";
    $sql.=",the_geom=GeometryFromText('".$formvars['textposition']."',".EPSGCODE.")";
    $sql.=" WHERE oid=".$oid;
    $ret=$this->database->execSQL($sql,4, 1);
    if ($ret[0]) {
      # Fehler beim Eintragen in Datenbank
      $ret[1]='\nAuf Grund eines Datenbankfehlers konnte die Notiz nicht aktualisiert werden!\n'.$ret[1];
    }
    return $ret;
  }
  
  function NotizLoeschen($oid) {
    $this->debug->write('<br>file:metadaten.php class:notiz function NotizLoeschen<br>Löschen einer Notiz in<br>PostGIS',4);
    $sql ="DELETE FROM q_notizen";
    $sql.=" WHERE oid=".$oid;
    $ret=$this->database->execSQL($sql,4, 1);
    if ($ret[0]) {
      # Fehler beim Eintragen in Datenbank
      $ret[1]='\nAuf Grund eines Datenbankfehlers konnte die Notiz nicht aktualisiert werden!\n'.$ret[1];
    }
    return $ret;
  }
  
  function NotizKat___($kategorie){
    $this->debug->write('<br>file:metadaten.php class:notiz function NotizKatbearbeiten<br>Abfragen der Rechte pro Stelle für eine Kategorie<br>PostGIS',4);
    $sql ="SELECT * FROM q_notiz_kategorie2stelle";
    $sql.=" WHERE kat_id=".$kategorie;
    #echo $sql;
    $ret=$this->database->execSQL($sql,4, 1);
    while($rs=pg_fetch_array($ret)) {
      $kat[]=$rs;
    }
    return $notizen;   	
  } # END OF function NotizKatbearbeiten
  
} # Ende Klasse Notizen
?>