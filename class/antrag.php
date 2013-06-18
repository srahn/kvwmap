<?php
###################################################################
# kvwmap - Kartenserver für Kreisverwaltungen                     #
###################################################################
# Lizenz                                                          #
#                                                                 # 
# Copyright (C) 2008  Peter Korduan                               #
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
#################
# Klasse Antrag #
#################

class antrag {
  var $nr;
  var $debug;
    
  ################### Liste der Funktionen #######################
  #
  # antrag($nr,$db)
  # antrag_aendern($antr_nr_a,$antr_nr_b,$VermStelle,$verm_art,$datum)
  # antrag_eintragen($antr_nr_a,$antr_nr_b,$VermStelle,$verm_art,$datum)
  # clearRecherchePfad()
  # EinmessungsskizzenInOrdnerZusammenstellen(festpunkte)
  # erzeugenUbergabeprotokoll()
  # getAntraege($id,$nr,$richtung,$order)
  # getAntragsnr_Liste()
  # getAnzGN($flurid,$stammnr)
  # getAnzKVZ($flurid,$stammnr)
  # getFFR($flurid,$stammnr,$order)
  # pruefe_antrag_eintragen($antr_nr_a,$antr_nr_b,$VermStelle,$verm_art,$datum)
  ################################################################

  function antrag($nr,$db) {
    global $debug;
    $this->debug=$debug;
    if ($nr!='') {
      $this->nr=$nr;
    }
    $this->database=$db;
  }
  
  function clearRecherchePfad() {
    # Löschen der vorhandenen alten Dateien des Auftrages oder anlegen eines neuen Verzeichnisses
    if (!is_dir(RECHERCHEERGEBNIS_PATH)) {
      # Verzeichnis für recherchierte Aufträge existierte noch nicht. 
      # Anlegen eines neuen Verzeichnisses zur Speicherung der Dokumentendateien entsprechend RECHERCHEERGEBNIS_PATH
      mkdir (RECHERCHEERGEBNIS_PATH, 0777);
      echo "<br>Verzeichnis für Rechercheergebnisse erstmalig angelegt: ".RECHERCHEERGEBNIS_PATH;
    }    
    # Festlegen des Pfades für den Auftrag
    $auftragspfad=RECHERCHEERGEBNIS_PATH.$this->nr;
    #echo '<br>'.$auftragspfad;
    if (!is_dir($auftragspfad)) {
      # Verzeichnis existierte noch nicht. 
      # Anlegen eines neuen Verzeichnisses zur Speicherung der Dokumentendateien mit Auftragsnummer als Name
      mkdir ($auftragspfad, 0777);
      echo "<br>Neues Verzeichnis: ".$auftragspfad." für Auftrag hinzugefügt.";
    }
    else {
      # Verzeichnis existiert schon. Den gesamten Inhalt löschen
      $exceptions = array(".", "..");
      if(delete_files($auftragspfad, $exceptions, 0)) {
        # Ordner wieder neu anlegen
        mkdir ($auftragspfad, 0777);
        echo "<br>Alle alten Dateien des Auftrages vor dem Hinzufügen der neuen Dateien gelöscht.";
      }
      else {
        echo "<br>Löschen vorhandener Dateien fehlgeschlagen";
      }
    }
  }

  function DokumenteInOrdnerZusammenstellen($nachweis){
    $auftragspfad=RECHERCHEERGEBNIS_PATH.$this->nr.'/Nachweise/';
    # Erzeuge ein Unterverzeichnis für die Nachweisdokumente
    mkdir ($auftragspfad,0777);
    # Führe in Schleif für alle zum Auftrag gehörenden Dokumente folgendes aus
    for ($i=0; $i<$nachweis->erg_dokumente;$i++){
      # Erzeuge ein Unterverzeichnis für die Flur des Dokumentes, wenn noch nicht vorhanden
      $flurid=trim($nachweis->Dokumente[$i]['flurid']);
      $zielpfad=$auftragspfad.$flurid.'/';
      if (!is_dir($zielpfad)) {
        mkdir ($zielpfad, 0777);
      }
      # Erzeuge ein Unterverzeichnis für die nr des Dokumentes, wenn noch nicht vorhanden
      $nr = $nachweis->buildNachweisNr($nachweis->Dokumente[$i][NACHWEIS_PRIMARY_ATTRIBUTE], $nachweis->Dokumente[$i][NACHWEIS_SECONDARY_ATTRIBUTE]);
      $zielpfad.=$nr.'/';
      if (!is_dir($zielpfad)) {
        mkdir ($zielpfad, 0777);
      }
      # Erzeuge ein Unterverzeichnis für die Dokumentenart, wenn noch nicht vorhanden
      $artname=ArtCode2Abk($nachweis->Dokumente[$i]['art']);    
      $zielpfad.=$artname.'/';
      if (!is_dir($zielpfad)) {
        mkdir ($zielpfad, 0777);
      }
      # Wie heißt die Datei, die in den Ordner kopiert werden soll
      # Pfad zur Quelle erstellen
      $quellpfad=NACHWEISDOCPATH.$flurid.'/'.$nr.'/';
      $quelle=$quellpfad.$nachweis->Dokumente[$i]['link_datei'];
      # Pfad zum Ziel erstellen
      $ziel=$auftragspfad.$flurid.'/'.$nr.'/'.$nachweis->Dokumente[$i]['link_datei'];
      #echo '<br>von:'.$quelle.' nach:'.$ziel;
      if (!file_exists($quelle)) {
        $errmsg.='Die Datei '.$quelle.' existiert nicht.\n';
      }
      else {
        if (!file_exists($ziel)){
          # Wenn die Datei am Ziel noch nicht existiert dort hin kopieren
          $erfolg=@copy($quelle,$ziel);
          if ($erfolg==0){
            # Es konnte aus irgendeinem Grund nicht erfolgreich kopiert werden
            $errmsg.='Die Datei '.$ziel.' konnte nicht erstellt werden.\n';
          }
        }
        else{
          # Die Datei, die kopiert werden soll existiert schon am ziel.
          $errmsg.='<br>Die Datei '.$ziel.' existiert bereits! ';
        }
      }
    }
    if ($errmsg==''){ $errmsg='Die Nachweisdateien zum Antrag Nr: '.$this->nr.' wurden erfolgreich in Ordner zusammengestellt';}
    return $errmsg;     
  }  

  function EinmessungsskizzenInOrdnerZusammenstellen($festpunkte){
    $skizzenpfad=RECHERCHEERGEBNIS_PATH.$this->nr.'/Einmessungsskizzen/';
    # Erzeuge ein Unterverzeichnis für die Einmessungsskizzen
    mkdir ($skizzenpfad,0777);
    # Frage die in der Liste vorkommenden Kilometerquadrate ab.
    $kmquad=$festpunkte->getKilometerQuadrate();
    # Erzeuge je ein Unterverzeichnis für die Kilometerquadrate
    foreach($kmquad AS $value) {
      mkdir ($skizzenpfad.'/'.$value,0777);
    }
    # Führe in Schleife für alle zum Auftrag gehörenden Einmessungsskizzen folgendes aus
    foreach($festpunkte->liste AS $festpunkt){
      # Wie heißt die Datei, die in den Ordner kopiert werden soll
      # Pfad zur Quelle zusammensetzen
      $quelle=PUNKTDATEIPATH.$festpunkt['datei'];
      # Pfad zum Ziel zusammensetzen
      $ziel=$skizzenpfad.$festpunkt['datei'];;
      #echo '<br>von:'.$quelle.' nach:'.$ziel;
      if (!file_exists($quelle)) {
        $errmsg.='Die Datei '.$quelle.' existiert nicht.\n';
      }
      else {
        if (!file_exists($ziel)){
          # Wenn die Datei am Ziel noch nicht existiert dort hin kopieren
          $erfolg=@copy($quelle,$ziel);
          if ($erfolg==0){
            # Es konnte aus irgendeinem Grund nicht erfolgreich kopiert werden
            $errmsg.='Die Datei '.$ziel.' konnte nicht erstellt werden.\n';
          }
        }
        else{
          # Die Datei, die kopiert werden soll existiert schon am ziel.
          $errmsg.='Die Datei '.$ziel.' existiert bereits!\n';
        }
      }
    }
    if ($errmsg==''){ $errmsg='Die Einmessungsskizzen der Festpunkte zum Antrag Nr: '.$this->nr.' wurden erfolgreich in Ordner zusammengestellt';}
    return $errmsg;     
  }  
  
  function erzeugenUbergabeprotokoll_PDF() {
    $pdf=new Cezpdf();
    $tmp = array('b'=>'Times-Bold.afm','i'=>'Times-Italic.afm','bi'=>'Times-BoldItalic.afm');
    $row=800;
    $rowGap=0;
    $colGap=1;
    $pdf->selectFont(PDFCLASSPATH.'fonts/Times-Roman.afm',$tmp);
    $pdf->addText(120,$row-=12,20,'<b>Anlage der Vermessungsvorbereitung</b>');
    $pdf->addText(165,$row-=20,18,'<b>zur Auftragsnummer '.$this->nr.'</b>');
    $pdf->addText(200,$row-=20,16,utf8_decode('Liste der Fortführungsrisse'));
    $row-=3; $pdf->line(200,$row,375,$row);
    $row-=3; $pdf->line(200,$row,375,$row);
    
    $rowtab=$row-=15;

    $anzTab=0;
    for ($i=0;$i<count($this->FFR);$i++) {
      $row=$row-18;
      $tabledata[$anzTab][]=$this->FFR[$i];
      if ($row < 300) { $anzTab++; $row=800;}
    }
        
    $cols='';
    $title='';
    # Konfiguration der Tabelle
    # Allgemeine Einstellungen für die ganze Tabelle
    $options=array('xPos'=>'left','xOrientation'=>'right','rowGap'=>$rowGap,'colGap'=>$colGap,'showLines'=>2 ,'width'=>550,'showHeadings'=>1,'fontSize'=>13, 'shaded'=>0);
    # Individuelle Einstellungen für die Spalten.
    $options['cols']['Lfd']=array('justification'=>'centre');
    $options['cols']['Riss-Nummer']=array('justification'=>'centre');
    $options['cols']['Antrags-Nummer']=array('justification'=>'centre');
    $options['cols']['FFR']=array('justification'=>'centre');
    $options['cols']['KVZ']=array('justification'=>'centre');
    $options['cols']['GN']=array('justification'=>'centre');
    $options['cols']['andere']=array('justification'=>'centre');
    $options['cols']['Datum']=array('justification'=>'left','width'=>80);
    $options['cols']['gemessen durch']=array('justification'=>'left');
    #$options['cols']['Bemerkung']=array('justification'=>'left','width'=>100);
    $pdf->ezSetY($rowtab);
    $pdf->ezTable($tabledata[0],$cols,$title,$options);
    $zahl=$anzTab+1;
    $pdf->addText(265,10,10,"Seite 1 von $zahl");
    for ($j=1;$j<=$anzTab;$j++){
      $row=800; $k=$j+1;
      $pdf->ezNewPage();
      $pdf->ezSetY(800);
      $pdf->addText(155,$row-=20,16,'<b>weiter zur Auftragsnummer '.$this->nr.'</b>');
      $pdf->addText(200,$row-=20,16,utf8_decode('Liste der Fortführungsrisse'));
      $row-=3; $pdf->line(200,$row,375,$row);
      $row-=3; $pdf->line(200,$row,375,$row);
      $pdf->ezSetY($row-=15);
      $pdf->ezTable($tabledata[$j],$cols,$title,$options);
      $pdf->addText(265,10,10,"Seite $k von $zahl");
    }
    return $pdf;
  }
  
  function erzeugenUbergabeprotokoll_CSV(){
  	# Überschriften
  	foreach($this->FFR[0] as $key=>$value){
  		$csv .= $key.';';
  		next($this->FFR[0]);
  	}
  	$csv.= chr(10);
  	# Daten
  	for($i=0; $i < count($this->FFR); $i++){
  		$csv .= implode(';', $this->FFR[$i]);
  		$csv.= chr(10);
    }
    return $csv;
  }
    
  function getAntraege($id,$nr,$richtung,$order) {
    $sql ="SELECT a.*,a.vermstelle,va.art AS vermart,vs.name AS vermst";
    $sql.=" ,SUBSTRING(a.antr_nr from 1 for 2) AS antr_nr_a";
    $sql.=" ,SUBSTRING(a.antr_nr from 4 for 4) AS antr_nr_b";
    $sql.=" FROM n_antraege AS a,n_vermstelle AS vs, n_vermart AS va";
    $sql.=" WHERE a.vermstelle=vs.id AND a.vermart=va.id";
    if ($id[0]!='') {
      $sql.=" AND a.antr_nr IN ('".$id[0]."'";
      for ($i=1;$i<count($id);$i++) {
        $sql.=",'".$id[$i]."'";
      }
      $sql.=")";
    }
    if ($order=='') {
      $order='antr_nr';
    }
    if ($richtung=='' OR $richtung=='ASC'){
      $richtung=='ASC';
      $this->richtung='DESC';
    }
    if ($richtung=='DESC'){
      $this->richtung='ASC';
    }
    if ($order!='') {
      $sql.=" ORDER BY ".$order." ".$richtung;
    }
    #echo $sql;
    $this->debug->write("<br>nachweis.php getAntraege Abfragen der Anträge.<br>".$sql,4);        
    $queryret=$this->database->execSQL($sql,4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      if (pg_num_rows($queryret[1])=='') {
        $ret[0]=1;
        $ret[1]='Es wurden keine Anträge gefunden!';
      }
      else {
        $ret[0]=0;
        while ($rs=pg_fetch_array($queryret[1])) {
          $this->antragsliste[]=$rs;
        }
        $ret[1]=$this->antragsliste;
      }
    }
    return $ret;
  }
    
  function getFFR($formvars) {
    # Abfrage der Vorgänge, die zu einem Auftrag zugeordnet sind
    # Ein Vorgang umfasst alle FFR, GN, KVZ mit gleicher flurid und stammnr
    # Die Abfrage liefert für jeden Vorgang eine Datenzeile zurück
    # Anschließend wird für jeden Vorgang die Anzahl der FFR, GN und KVZ ermittelt
    # 2006-01-25 pk
    # Die Abfrage der Anzahl der FFR, KVZ und GN kann eingeschränkt werden auf die,
    # die einem Auftrag zugeordnet sind.
    # mehr wird im Protokoll nicht erfasst, nicht jedes einzelne Blatt
    # Dieser Vorgang ist hier mit der Variable FFR belegt, weil zu einem Vorgang
    # meistens mindestens ein Fortführungsriss gehört.
    $this->debug->write('nachweis.php getFFR Abfragen der Risse zum Antrag.',4);                
    $sql ="SELECT DISTINCT n.flurid,n.stammnr,n.rissnummer";
    $sql.=" FROM n_nachweise AS n, n_nachweise2antraege AS n2a";
    $sql.=" WHERE n.id=n2a.nachweis_id AND n2a.antrag_id='".$this->nr."'";
    if($formvars['order'] != '')$sql.=" ORDER BY ".$formvars['order'];
    #echo $sql;
    $ret=$this->database->execSQL($sql,4, 0);    
    if ($ret[0]) { return $ret[1]; }
    else { $query_id=$ret[1]; }
    $i=0;
    while($rs=pg_fetch_array($query_id)) {
      # Setzen der laufenden Nummer der Vorgänge
      if($formvars['Lfd'])$FFR[$i]['Lfd']=$i+1;
      
      if(NACHWEIS_PRIMARY_ATTRIBUTE == 'rissnummer'){
      	if($formvars['Riss-Nummer'])$FFR[$i]['Riss-Nummer']=$rs['flurid'].'/'.$rs['rissnummer'];
      	if($formvars['Antrags-Nummer'])$FFR[$i]['Antrags-Nummer']=str_pad($rs['stammnr'],RISSNUMMERMAXLENGTH,'0',STR_PAD_LEFT);
      }
      else{
      	if($formvars['Antrags-Nummer'])$FFR[$i]['Antrags-Nummer']=$rs['flurid'].'/'.str_pad($rs['stammnr'],ANTRAGSNUMMERMAXLENGTH,'0',STR_PAD_LEFT);
      	if($formvars['Riss-Nummer'])$FFR[$i]['Riss-Nummer']=$rs['rissnummer'];
      }

      # Abfrage der Anzahl der FFR zum Vorgang
      if($formvars['FFR']){
      	$ret=$this->getAnzFFR($rs['flurid'],$rs[NACHWEIS_PRIMARY_ATTRIBUTE], $rs[NACHWEIS_SECONDARY_ATTRIBUTE]);
      	if ($ret[0]) { return $ret; }
      	$FFR[$i]['FFR']=$ret[1];
      }      
      
      # Abfrage der Anzahl der KVZ zum Vorgang
      if($formvars['KVZ']){
	      $ret=$this->getAnzKVZ($rs['flurid'],$rs[NACHWEIS_PRIMARY_ATTRIBUTE], $rs[NACHWEIS_SECONDARY_ATTRIBUTE]);
	      if ($ret[0]) { return $ret; }
	      $FFR[$i]['KVZ']=$ret[1];
      }
      
      # Abfrage der Anzahl der GN zum Vorgang
      if($formvars['GN']){
	      $ret=$this->getAnzGN($rs['flurid'],$rs[NACHWEIS_PRIMARY_ATTRIBUTE], $rs[NACHWEIS_SECONDARY_ATTRIBUTE]);
	      if ($ret[0]) { return $ret; }
	      $FFR[$i]['GN']=$ret[1];
      }
      
      # Abfrage der Anzahl der anderen Dokumente zum Vorgang
      if($formvars['andere']){
	      $ret=$this->getAnzAndere($rs['flurid'],$rs[NACHWEIS_PRIMARY_ATTRIBUTE], $rs[NACHWEIS_SECONDARY_ATTRIBUTE]);
	      if ($ret[0]) { return $ret; }
	      $FFR[$i]['andere']=$ret[1];
      }            
            
      # Abfrage der Datumsangaben im Vorgang
      if($formvars['Datum']){
	      $ret=$this->getDatum($rs['flurid'],$rs[NACHWEIS_PRIMARY_ATTRIBUTE], $rs[NACHWEIS_SECONDARY_ATTRIBUTE]);
	      if ($ret[0]) { return $ret; }
	      $FFR[$i]['Datum']=$ret[1];
      }
      
    	# Abfrage der Dateinamen im Vorgang
      if($formvars['Datei']){
	      $ret=$this->getDatei($rs['flurid'],$rs[NACHWEIS_PRIMARY_ATTRIBUTE], $rs[NACHWEIS_SECONDARY_ATTRIBUTE]);
	      if ($ret[0]) { return $ret; }
	      $FFR[$i]['Datei']=$ret[1];
      }

      # Abfrage der Vermessungsstellen im Vorgang
      if($formvars['gemessendurch']){
	      $ret=$this->getVermessungsStellen($rs['flurid'],$rs[NACHWEIS_PRIMARY_ATTRIBUTE], $rs[NACHWEIS_SECONDARY_ATTRIBUTE]);
	      if ($ret[0]) { return $ret; }
	      $FFR[$i]['gemessen durch']=utf8_decode($ret[1]); 
      }
            
      # Abfrage der Gültigkeiten der Dokumente im Vorgang
      $ret=$this->getGueltigkeit($rs['flurid'],$rs[NACHWEIS_PRIMARY_ATTRIBUTE], $rs[NACHWEIS_SECONDARY_ATTRIBUTE]);
      if ($ret[0]) { return $ret; }
      #$FFR[$i]['Bemerkung']=$ret[1]; 
      #var_dump($FFR[$i]);
      $i++;
    }
    $ret[0]=0;
    $ret[1]=$FFR;
    $this->FFR=$FFR;
    return $ret;
  }
  
  function getDatum($flurid,$nr,$secondary) {
    $this->debug->write('<br>nachweis.php getDatum Abfragen der Datum zu einem Vorgang in der Nachweisführung.',4);
    # Abfragen der Datum zu einem Vorgang in der Nachweisführung
    $sql.="SELECT DISTINCT n.datum FROM n_nachweise AS n";
    $sql.=" WHERE n.flurid=".$flurid." AND n.".NACHWEIS_PRIMARY_ATTRIBUTE."='".$nr."'";
    if($secondary != '')$sql.=" AND n.".NACHWEIS_SECONDARY_ATTRIBUTE."='".$secondary."'";
    $ret=$this->database->execSQL($sql,4, 0);
    if (!$ret[0]) {
      $rs=pg_fetch_array($ret[1]);
      $datum=$rs['datum'];  
      while($rs=pg_fetch_array($ret[1])) {
        $datum.=', '.$rs['datum'];
      }
      $ret[1]=$datum;
    }     
    return $ret;  
  }
  
	function getDatei($flurid,$nr,$secondary) {
    $this->debug->write('<br>nachweis.php getDatei Abfragen der Dateien zu einem Vorgang in der Nachweisführung.',4);
    # Abfragen der Datum zu einem Vorgang in der Nachweisführung
    $sql.="SELECT DISTINCT n.link_datei FROM n_nachweise AS n";
    $sql.=" WHERE n.flurid=".$flurid." AND n.".NACHWEIS_PRIMARY_ATTRIBUTE."='".$nr."'";
    if($secondary != '')$sql.=" AND n.".NACHWEIS_SECONDARY_ATTRIBUTE."='".$secondary."'";
    $ret=$this->database->execSQL($sql,4, 0);
    if (!$ret[0]) {
      $rs=pg_fetch_array($ret[1]);
      $datei=$rs['link_datei'];  
      while($rs=pg_fetch_array($ret[1])) {
        $datei.=', '.$rs['link_datei'];
      }
      $ret[1]=$datei;
    }     
    return $ret;  
  }
  
  function getGueltigkeit($flurid,$nr,$secondary) {
    $this->debug->write('<br>nachweis.php getDatum Abfragen der Gueltigkeit der Dokumente in einem Vorgang in der Nachweisführung.',4);
    # Abfragen der Gueltigkeit der Dokumente in einem Vorgang in der Nachweisführung.
    $sql.="SELECT DISTINCT n.gueltigkeit FROM n_nachweise AS n";
    $sql.=" WHERE n.flurid=".$flurid." AND n.".NACHWEIS_PRIMARY_ATTRIBUTE."='".$nr."'";
    if($secondary != '')$sql.=" AND n.".NACHWEIS_SECONDARY_ATTRIBUTE."='".$secondary."'";
    $sql.=" AND n.gueltigkeit=0";
    $ret=$this->database->execSQL($sql,4, 0);
    if (!$ret[0]) {
      # Wenn die Abfrage mindestens eine Datenzeile enthält, sind ungültige Dokumente enthalten 
      if (pg_num_rows($ret[1])>0) {
        # Ungültige Dokumente enthalten
        $ret[1]='enthält ungültige Dokumente';      
      }
      else {
        # keine ungültigen Dokumente enthalten.
        $ret[1]='';
      }
    }     
    return $ret;  
  }
  
  function getVermessungsStellen($flurid,$nr,$secondary) {
    $this->debug->write('<br>nachweis.php getDatum Abfragen der Vermessungsstellen, die an einem Vorgang beteiligt waren in der Nachweisführung.',4);
    # Abfragen der Vermessungsstellen, die an einem Vorgang beteiligt waren in der Nachweisführung.
    $sql.="SELECT DISTINCT v.name FROM n_nachweise AS n, n_vermstelle AS v";
    $sql.=" WHERE n.flurid=".$flurid." AND n.".NACHWEIS_PRIMARY_ATTRIBUTE."='".$nr."'";
    if($secondary != '')$sql.=" AND n.".NACHWEIS_SECONDARY_ATTRIBUTE."='".$secondary."'";
    $sql.=" AND n.vermstelle::integer=v.id";
    $ret=$this->database->execSQL($sql,4, 0);
    if (!$ret[0]) {
      $rs=pg_fetch_array($ret[1]);
      $vermstellen=$rs['name']; 
      while($rs=pg_fetch_array($ret[1])) {
        $vermstellen.=', '.$rs['name'];
      }
      $ret[1]=$vermstellen;
    }
    return $ret;    
  }
    
  function getAnzFFR($flurid,$nr,$secondary) {
    $this->debug->write('<br>nachweis.php getAnzFFR Abfragen der Anzahl der Blätter eines FFR.',4);
    # Abfrag der Anzahl der zum Riss gehörenden Fortführungsrisse
    $sql.="SELECT COUNT(n.id) AS anzffr FROM n_nachweise AS n";
    if ($this->nr!='') {
      $sql.=",n_nachweise2antraege AS n2a WHERE n.id=n2a.nachweis_id AND n2a.antrag_id='".$this->nr."'";
    }
    else {
      $sql.=" WHERE (1=1)";
    }
    $sql.=" AND n.flurid=".$flurid." AND n.".NACHWEIS_PRIMARY_ATTRIBUTE."='".$nr."'";
    if($secondary != '')$sql.=" AND n.".NACHWEIS_SECONDARY_ATTRIBUTE."='".$secondary."'";
    $sql.=" AND n.art = '100'";
    $queryret=$this->database->execSQL($sql,4, 0);
    if ($queryret[0]) { $ret=$queryret; }
    else {
      $ret[0]=0;
      $rs=pg_fetch_array($queryret[1]);
      if ($rs["anzffr"]>0) {
        $ret[1]=$rs["anzffr"];
      }
      else {
        $ret[1]='-';
      }
    }
    return $ret;
  }
   
  function getAnzKVZ($flurid,$nr,$secondary) {
    $this->debug->write('<br>nachweis.php getAnzKVZ Abfragen der Anzahl der KVZ zum Riss.',4);
    # Abfrag der Anzahl der zum Riss gehörenden Koordinatenverzeichnisse
    $sql.="SELECT COUNT(n.id) AS anzkvz FROM n_nachweise AS n";
    if ($this->nr!='') {
      $sql.=",n_nachweise2antraege AS n2a WHERE n.id=n2a.nachweis_id AND n2a.antrag_id='".$this->nr."'";
    }
    else {
      $sql.=" WHERE (1=1)";
    }
    $sql.=" AND n.flurid=".$flurid." AND n.".NACHWEIS_PRIMARY_ATTRIBUTE."='".$nr."'";
    if($secondary != '')$sql.=" AND n.".NACHWEIS_SECONDARY_ATTRIBUTE."='".$secondary."'";
    $sql.=" AND n.art = '010'";
    $queryret=$this->database->execSQL($sql,4, 0);
    if ($queryret[0]) { $ret=$queryret; }
    else {
      $ret[0]=0;
      $rs=pg_fetch_array($queryret[1]);
      if ($rs['anzkvz']>0) {
        $ret[1]=$rs["anzkvz"];
      }
      else {
        $ret[1]='-';
      }
    }
    return $ret;
  }
  
  function getAnzGN($flurid,$nr,$secondary) {
    $this->debug->write('<br>nachweis.php getAnzGN Abfragen der Anzahl der Grenzniederschriften zum Riss.',4);
    # Abfrage der Anzahl der zum Riss gehörenden Grenzniederschriften
    $sql.="SELECT COUNT(n.id) AS anzgn FROM n_nachweise AS n";
    if ($this->nr!='') {
      $sql.=",n_nachweise2antraege AS n2a WHERE n.id=n2a.nachweis_id AND n2a.antrag_id='".$this->nr."'";
    }
    else {
      $sql.=" WHERE (1=1)";
    }
    $sql.=" AND n.flurid=".$flurid." AND n.".NACHWEIS_PRIMARY_ATTRIBUTE."='".$nr."'";
    if($secondary != '')$sql.=" AND n.".NACHWEIS_SECONDARY_ATTRIBUTE."='".$secondary."'";
    $sql.=" AND n.art = '001'";
    $queryret=$this->database->execSQL($sql,4, 0);
    if ($queryret[0]) { $ret=$queryret; }
    else {
      $ret[0]=0;
      $rs=pg_fetch_array($queryret[1]);
      if ($rs['anzgn']>0) {
        $ret[1]=$rs["anzgn"];
      }
      else {
        $ret[1]='-';
      }
    }
    return $ret;    
  }
  
  function getAnzAndere($flurid,$nr,$secondary) {
    $this->debug->write('<br>nachweis.php getAnzAn Abfragen der Anzahl der anderen Dokumente zum Riss.',4);
    # Abfrage der Anzahl der zum Riss gehörenden Grenzniederschriften
    $sql.="SELECT COUNT(n.id) AS anzan FROM n_nachweise AS n";
    if ($this->nr!='') {
      $sql.=",n_nachweise2antraege AS n2a WHERE n.id=n2a.nachweis_id AND n2a.antrag_id='".$this->nr."'";
    }
    else {
      $sql.=" WHERE (1=1)";
    }
    $sql.=" AND n.flurid=".$flurid." AND n.".NACHWEIS_PRIMARY_ATTRIBUTE."='".$nr."'";
    if($secondary != '')$sql.=" AND n.".NACHWEIS_SECONDARY_ATTRIBUTE."='".$secondary."'";
    $sql.=" AND n.art = '111'";
    $queryret=$this->database->execSQL($sql,4, 0);
    if ($queryret[0]) { $ret=$queryret; }
    else {
      $ret[0]=0;
      $rs=pg_fetch_array($queryret[1]);
      if ($rs['anzan']>0) {
        $ret[1]=$rs["anzan"];
      }
      else {
        $ret[1]='-';
      }
    }
    return $ret;    
  }
  
  
  function pruefe_antrag_eintragen($antr_nr,$VermStelle,$verm_art,$datum) {
    #prüfen, ob die Antragsnummer korrekt eingegeben wurde!
    $strenthalten=0;
    if($antr_nr==''){
      $errmsg.='Bitte geben Sie die Antragsnummer ein! \n';
    }
    $this->debug->write("<br>antrag.php pruefe_antrag_eintragen() prüfen der Eingabe der Anträge, ob Antragsnummer schin vorhanden.<br>".$sql,4);        
    $sql ="SELECT * FROM n_antraege WHERE antr_nr = '".$antr_nr."'";
    $queryret=$this->database->execSQL($sql,4, 0);
    if ($queryret[0]) {
      $errmsg.='Fehler bei der Abfrage der Anträge! '.$queryret[1].'\n';
    }
    else {
      if (pg_num_rows($queryret[1])>0) {
        $errmsg.='Antragsnummer '.$antr_nr.' existiert bereits! '.$queryret[1];
      }
    }

    #prüfen, ob ein Datum eingegeben wurde
    if ($datum==''){
      $errmsg.='Bitte geben Sie ein Datum an! \n';
    }
    else {
      # Pfüfen, ob es sich um das richtige Format handelt.
      $datumteile=explode('-',$datum);
      if (!checkdate($datumteile[1],$datumteile[2],$datumteile[0])) {
        $errmsg.='Das ist kein gültiges Datum. Geben sie es in der Form JJJJ-MM-TT ein.\n';
      }
    }
    if($verm_art==''){
      $errmsg.='Bitte geben Sie die Vermessungsart an! \n';
    }
    return $errmsg;
  }
  
  function antrag_eintragen($antr_nr,$VermStelle,$verm_art,$datum) {
    $sql ="INSERT INTO n_antraege (antr_nr,vermstelle,vermart,datum)";
    $sql.=" VALUES('".$antr_nr."',".$VermStelle.",".$verm_art.",'".$datum."')";
    $queryret=$this->database->execSQL($sql,4, 1);
    if ($queryret[0]) {
      $errmsg='Es konnte keine Antragsnummer in die Datenbank eingetragen werden!\n'; 
    }
    else {
      $errmsg.='Auftragsnummer erfolgreich übernommen! ';
    }
    return $errmsg;
  }
  
  function antrag_aendern($antr_nr,$VermStelle,$verm_art,$datum) {
    $sql ="UPDATE n_antraege SET vermstelle=".(int)$VermStelle.",vermart=".(int)$verm_art.",datum='".$datum."'";
    $sql.=" WHERE antr_nr='".$antr_nr_a."'";
    $queryret=$this->database->execSQL($sql,4, 1);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]='Fehler beim ändern der Antragsdaten in der Datenbank! '.$queryret[1]; 
    }
    else {
      $ret[0]=0;
      $ret[1]='Auftragsdaten erfolgreich geändert!';
    }
    return $ret;
  }
  
  function antrag_loeschen($antr_nr){
    $this->database->begintransaction();
    $this->debug->write("<br>antrag.php antrag_loeschen Löschen der Anträge inclusive der Zuordnungen zu Nachweisdokumenten.<br>",4);
    $sql="DELETE FROM n_antraege WHERE antr_nr='".$antr_nr."'";
    $queryret=$this->database->execSQL($sql,4, 1);
    if ($queryret[0]) {
      $errmsg.='Fehler beim Löschen des Antrages!';
    }
    $sql="DELETE FROM n_nachweise2antraege WHERE antrag_id='".$antr_nr."'";
    $queryret=$this->database->execSQL($sql,4, 1);
    if ($queryret[0]) {
      $errmsg.='Fehler beim Löschen der Zuordnungen der Nachweisdokumente zum Antrag!';
    }
    if ($errmsg!='') {
      $this->database->rollbacktransaction();
    }
    else {
      $exceptions = array(".", "..");
      delete_files(RECHERCHEERGEBNIS_PATH.$antr_nr, $exceptions, 0);
      $errmsg='Antrag erfolgreich gelöscht';
      $this->database->committransaction();      
    }
    return $errmsg;
  }
  
  function getAntragsnr_Liste() {
    # fragt alle Vermessungsstellen aus der Datenbank ab (id, Name)
    $sql = "SELECT * FROM n_antraege ORDER BY antr_nr";
    $queryret=$this->database->execSQL($sql,4, 0);
    if ($queryret[0]) { 
      $errmsg='Fehler bei der Abfrage der Antragsnummern in der Datenbank bei Statement: '; 
    }
    else {
      $Antr_nr_Liste['antr_nr'][0]='';
      while($rs=pg_fetch_array($queryret[1])) {
        $Antr_nr_Liste['antr_nr'][]=$rs['antr_nr'];
      }
      $errmsg='';
    }
    $ret[0]=$errmsg;
    $ret[1]=$Antr_nr_Liste;
    return $ret;
  }
  
  function addFestpunkt($pkz) {
    # Fügt einen Festpunkt in dem Antrag hinzu
    $sql ="INSERT INTO fp_punkte2antraege (pkz,antrag_nr,zeitstempel)";
    $sql.="(";
    $sql.=" SELECT '".$pkz."','".$this->nr."',CURRENT_TIMESTAMP(0)";
    $sql.=" WHERE NOT EXISTS (";
    $sql.=" SELECT 1 FROM fp_punkte2antraege WHERE pkz='".$pkz."' AND antrag_nr='".$this->nr."')";
    $sql.=")";
    $this->debug->write("<br>antrag->addFestpunkt().<br>",4);
    $ret=$this->database->execSQL($sql,4, 1);
    return $ret;
  }
}
?>
