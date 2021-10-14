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
# peter.korduan@gdi-service.de                                    #
# stefan.rahn@gdi-service.de                                      #
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

  function __construct($nr,$stelle_id,$db) {
    global $debug;
    $this->debug=$debug;
    if($nr!='') {
      $this->nr=$nr;
    }
		if($stelle_id!='') {
      $this->stelle_id=$stelle_id;
    }
    $this->database=$db;
  }
  
	function create_uebergabe_logpath($Stelle){
		$path = RECHERCHEERGEBNIS_PATH.'Uebergebene_Dokumente/'.$Stelle;
		if(!is_dir($path)){
			mkdir($path, 0777, true);
		}
		return $path;
	}
	
  function clearRecherchePfad() {
    # Löschen der vorhandenen alten Dateien des Auftrages oder anlegen eines neuen Verzeichnisses
    if (!is_dir(RECHERCHEERGEBNIS_PATH)) {
      # Verzeichnis für recherchierte Aufträge existierte noch nicht. 
      # Anlegen eines neuen Verzeichnisses zur Speicherung der Dokumentendateien entsprechend RECHERCHEERGEBNIS_PATH
      mkdir (RECHERCHEERGEBNIS_PATH, 0777);
      $msg = "Verzeichnis für Rechercheergebnisse erstmalig angelegt: ".RECHERCHEERGEBNIS_PATH."<br>";
    }    
    # Festlegen des Pfades für den Auftrag
    $auftragspfad=RECHERCHEERGEBNIS_PATH.$this->nr;
		if($this->stelle_id != '')$auftragspfad.='~'.$this->stelle_id;
    #echo '<br>'.$auftragspfad;
    if (!is_dir($auftragspfad)) {
      # Verzeichnis existierte noch nicht. 
      # Anlegen eines neuen Verzeichnisses zur Speicherung der Dokumentendateien mit Auftragsnummer als Name
      mkdir ($auftragspfad, 0777);
      #$msg = "Neues Verzeichnis: ".$auftragspfad." für Auftrag hinzugefügt.\\r\\n";
    }
    else {
      # Verzeichnis existiert schon. Den gesamten Inhalt löschen
      $exceptions = array(".", "..");
      if(delete_files($auftragspfad, $exceptions, 0)) {
        # Ordner wieder neu anlegen
        mkdir ($auftragspfad, 0777);
        $msg = "Alle alten Dateien des Auftrages vor dem Hinzufügen der neuen Dateien gelöscht.\n";
      }
      else {
        $msg = "Löschen vorhandener Dateien fehlgeschlagen.<br>";
      }
    }
		return $msg;
  }

  function DokumenteInOrdnerZusammenstellen($nachweis){
		$antragsnr = $this->nr;
		if($this->stelle_id != '')$antragsnr.='~'.$this->stelle_id;
    $auftragspfad=RECHERCHEERGEBNIS_PATH.$antragsnr.'/Nachweise/';	# Erzeuge ein Unterverzeichnis für die Nachweisdokumente
    mkdir ($auftragspfad,0777);
		$vorschaupfad=RECHERCHEERGEBNIS_PATH.$antragsnr.'/Vorschaubilder/';	# Erzeuge ein Unterverzeichnis für die Vorschaubilder
    mkdir ($vorschaupfad,0777);
		$nachweiseUKOpfad=RECHERCHEERGEBNIS_PATH.$antragsnr.'/Nachweise-UKO/';	# Erzeuge ein Unterverzeichnis für die Nachweis-UKOs
    mkdir ($nachweiseUKOpfad,0777);
		$uebersichtspfad=RECHERCHEERGEBNIS_PATH.$antragsnr.'/Protokolle/';	# Erzeuge ein Unterverzeichnis für die Protokoll- und Übersichtsdateien
    mkdir ($uebersichtspfad,0777);
		$gesamtpolygonpfad=RECHERCHEERGEBNIS_PATH.$antragsnr.'/Protokolle/Gesamtpolygon/';	# Erzeuge ein Unterverzeichnis für die Gesamtpolygondateien
    mkdir ($gesamtpolygonpfad,0777);
		$gesamtpolygonSHPpfad=RECHERCHEERGEBNIS_PATH.$antragsnr.'/Protokolle/Gesamtpolygon/Shape/';	# Erzeuge ein Unterverzeichnis für die Gesamtpolygondateien
    mkdir ($gesamtpolygonSHPpfad,0777);
		$gesamtpolygonUKOpfad=RECHERCHEERGEBNIS_PATH.$antragsnr.'/Protokolle/Gesamtpolygon/UKO/';	# Erzeuge ein Unterverzeichnis für die Gesamtpolygondateien
    mkdir ($gesamtpolygonUKOpfad,0777);
		$gesamtpolygonGMLpfad=RECHERCHEERGEBNIS_PATH.$antragsnr.'/Protokolle/Gesamtpolygon/GML/';	# Erzeuge ein Unterverzeichnis für die Gesamtpolygondateien
    mkdir ($gesamtpolygonGMLpfad,0777);
		$gesamtpolygonGeoJSONpfad=RECHERCHEERGEBNIS_PATH.$antragsnr.'/Protokolle/Gesamtpolygon/GeoJSON/';	# Erzeuge ein Unterverzeichnis für die Gesamtpolygondateien
    mkdir ($gesamtpolygonGeoJSONpfad,0777);
		$gesamtpolygonDXFpfad=RECHERCHEERGEBNIS_PATH.$antragsnr.'/Protokolle/Gesamtpolygon/DXF/';	# Erzeuge ein Unterverzeichnis für die Gesamtpolygondateien
    mkdir ($gesamtpolygonDXFpfad,0777);
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
      $artname = strtolower($nachweis->hauptdokumentarten[$nachweis->Dokumente[$i]['hauptart']]['abkuerzung']);
      $zielpfad.=$artname.'/';
      if (!is_dir($zielpfad)) {
        mkdir ($zielpfad, 0777);
      }
      # Wie heißt die Datei, die in den Ordner kopiert werden soll
      # Pfad zur Quelle erstellen
      $quellpfad = dirname($nachweis->Dokumente[$i]['link_datei']);
      $quelle = $nachweis->Dokumente[$i]['link_datei'];
      # Pfad zum Ziel erstellen
			$ziel = $zielpfad.basename($nachweis->Dokumente[$i]['link_datei']);
      #echo '<br>von:'.$quelle.' nach:'.$ziel;
			$dateinamensteil = explode('.', basename($nachweis->Dokumente[$i]['link_datei']));
      if (!file_exists($quelle)) {
        $errmsg.='Die Datei '.$quelle.' existiert nicht.<br>';
      }
      else {
        if (!file_exists($ziel)){
          # Wenn die Datei am Ziel noch nicht existiert dort hin kopieren
          $erfolg=@copy($quelle,$ziel);
          if ($erfolg==0){
            # Es konnte aus irgendeinem Grund nicht erfolgreich kopiert werden
            $errmsg.='Die Datei '.$ziel.' konnte nicht erstellt werden.<br>';
          }
					else{	
						# Vorschaubild kopieren
						$vorschaudatei = $dateinamensteil[0].'_thumb.jpg';
						$quelle=$quellpfad.'/'.$vorschaudatei;
						$erfolg=copy($quelle,$vorschaupfad.$vorschaudatei);
						# Nachweis-UKOs erzeugen
						$uko = WKT2UKO($nachweis->Dokumente[$i]['wkt_umring']);
						$ukofile = $nachweiseUKOpfad.basename($dateinamensteil[0]).'.uko';
						$fp = fopen($ukofile, 'w');
						fwrite($fp, $uko);
						fclose($fp);
					}
        }
        else{
          # Die Datei, die kopiert werden soll existiert schon am ziel.
          $errmsg.='<br>Die Datei '.$ziel.' existiert bereits! ';
        }
      }
    }
    #if ($errmsg==''){ $errmsg='Die Nachweisdateien zum Antrag Nr: '.$this->nr.' wurden erfolgreich in Ordner zusammengestellt';}
    return $errmsg;     
  }  

  function EinmessungsskizzenInOrdnerZusammenstellen($festpunkte){
		$antragsnr = $this->nr;
		if($this->stelle_id != '')$antragsnr.='~'.$this->stelle_id;
    $skizzenpfad=RECHERCHEERGEBNIS_PATH.$antragsnr.'/Einmessungsskizzen/';
    # Erzeuge ein Unterverzeichnis für die Einmessungsskizzen
    mkdir ($skizzenpfad,0777);
    # Frage die in der Liste vorkommenden Kilometerquadrate ab.
    $kmquad=$festpunkte->getKilometerQuadrate();
    # Erzeuge je ein Unterverzeichnis für die Kilometerquadrate
    foreach($kmquad AS $value) {
      mkdir ($skizzenpfad.'/'.$value,0777);
    }
    # Führe in Schleife für alle zum Auftrag gehörenden Einmessungsskizzen folgendes aus
		if(count($festpunkte->liste) > 0){
			foreach($festpunkte->liste AS $festpunkt){
				# Wie heißt die Datei, die in den Ordner kopiert werden soll
				# Pfad zur Quelle zusammensetzen
				$quelle=PUNKTDATEIPATH.$festpunkt['datei'];
				# Pfad zum Ziel zusammensetzen
				$ziel=$skizzenpfad.$festpunkt['datei'];;
				#echo '<br>von:'.$quelle.' nach:'.$ziel;
				if (!file_exists($quelle)) {
					$errmsg.='Die Datei '.$quelle.' existiert nicht.<br>';
				}
				else {
					if (!file_exists($ziel)){
						# Wenn die Datei am Ziel noch nicht existiert dort hin kopieren
						$erfolg=@copy($quelle,$ziel);
						if ($erfolg==0){
							# Es konnte aus irgendeinem Grund nicht erfolgreich kopiert werden
							$errmsg.='Die Datei '.$ziel.' konnte nicht erstellt werden.<br>';
						}
					}
					else{
						# Die Datei, die kopiert werden soll existiert schon am ziel.
						$errmsg.='Die Datei '.$ziel.' existiert bereits!<br>';
					}
				}
			}
			if ($errmsg==''){ $errmsg='Die Einmessungsskizzen der Festpunkte zum Antrag Nr: '.$this->nr.' wurden erfolgreich in Ordner zusammengestellt';}
			return $errmsg;     
		}
  }  
  
	function Seitenkopf(&$pdf, $row_start){
		$pdf->ezSetMargins(0,0,0,0);
		$pages = $pdf->objects['3']['info']['pages'];
		$pagecount = count($pages);
		for($i = 0; $i < $pagecount; $i++){
			$row = $row_start;
			$pagenumber = $i + 1;
			$pdf->reopenObject($pages[$i]+1);		# die Page-IDs sind komischerweise alle um 1 größer
			if ($pagecount!=1)$footer = 'Seite '.$pagenumber.' / ';
			$footer .= $pagecount.' Seite';
			if ($pagecount!=1)$footer .= 'n';
			$pdf->addText(395,10,6, $footer);
			$pdf->addText(765,$row+10,8, date('d.m.Y',time()));
			$pdf->addText(30,$row-=12,10,'<b>Anlage der Vermessungsvorbereitung zu Auftragsnummer '.$this->nr.'</b>');
			$pdf->addText(30,$row-=25,10,utf8_decode('Liste der ausgegebenen Unterlagen'));
			if (defined('ZUSATZ_UEBERGABEPROTOKOLL') AND ZUSATZ_UEBERGABEPROTOKOLL != '') {
				$pdf->addText(30,$row-=16,9,utf8_decode(ZUSATZ_UEBERGABEPROTOKOLL));
			}
		}
	}



	
  function erzeugenUbergabeprotokoll_PDF(){
    $pdf=new Cezpdf('A4', 'landscape');
    $tmp = array('b'=>'Helvetica-Bold.afm','i'=>'Helvetica-Oblique.afm','bi'=>'Helvetica-BoldOblique.afm');
		$pageheight = 595;
		$margin = 57;
    $row = $pageheight - $margin;
		$table_row = $row - 80;
		$table_margin = $pageheight - $table_row;
    $rowGap=3;
    $colGap=3;
		$pdf->ezSetMargins($table_margin,30,30,40);
    $pdf->selectFont(WWWROOT . APPLVERSION . 'fonts/PDFClass/Helvetica.afm',$tmp);		
            
    $cols='';
    $title='';
    # Konfiguration der Tabelle
    # Allgemeine Einstellungen für die ganze Tabelle
    $options=array('xPos'=>'left','xOrientation'=>'right','rowGap'=>$rowGap,'colGap'=>$colGap,'showLines'=>2, 'lineCol'=> [0.9,0.9,0.9], 'width'=>780,'showHeadings'=>1,'fontSize'=>8, 'shaded'=>0, 'textCol'=> [0.1,0.1,0.1], 'shadeHeadingCol'=> [0.9,0.9,0.9]);
    # Individuelle Einstellungen für die Spalten.
		foreach($this->FFR[0] as $property => $value){
			$options['cols'][$property]=array('justification'=>'left');
		}		
		$options['cols']['FFR']=$options['cols']['KVZ']=$options['cols']['GN']=$options['cols']['FPN']=$options['cols']['KRT']=$options['cols']['PL']=$options['cols']['ERG']=array('justification'=>'center');
		$options['cols']['Datei']=array('width'=>210);
		$options['cols'][utf8_decode('Gültigkeit')]=array('width'=>62);
		$pdf->ezSetY($table_row);
		$pdf->ezTable($this->FFR,$cols,$title,$options);
		
		$this->Seitenkopf($pdf, $row);
		
    return $pdf;
  }
  	    
  function getAntraege($id,$nr,$richtung,$order,$current_stelle_id) {
		global $admin_stellen;
    $sql ="SELECT a.*,a.vermstelle,va.art AS vermart,vs.name AS vermst";
    $sql.=" ,SUBSTRING(a.antr_nr from 1 for 2) AS antr_nr_a";
    $sql.=" ,SUBSTRING(a.antr_nr from 4 for 4) AS antr_nr_b";
    $sql.=" FROM nachweisverwaltung.n_antraege AS a";
		$sql.=" LEFT JOIN nachweisverwaltung.n_vermstelle vs ON a.vermstelle=vs.id";
    $sql.=" LEFT JOIN nachweisverwaltung.n_vermart va ON a.vermart=va.id WHERE 1=1";
    if ($id[0]!='') {
      $sql.=" AND a.antr_nr IN ('".$id[0]."'";
      for ($i=1;$i<count($id);$i++) {
        $sql.=",'".$id[$i]."'";
      }
      $sql.=")";
    }
		if(!in_array($current_stelle_id, $admin_stellen))$sql.= " AND stelle_id = ".$current_stelle_id;
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
	
	function getIntersectedFlst(){	# diese Verschneidung mit den Flurstücken kann u.U. sehr lange dauern, deswegen erstmal zurückgestellt
		$this->spatial_ref_code = EPSGCODE_ALKIS.", ".EARTH_RADIUS;
		$sql ="SELECT flurid, stammnr, rissnummer, ";
		if(NACHWEIS_SECONDARY_ATTRIBUTE != '')$sql.=" ".NACHWEIS_SECONDARY_ATTRIBUTE.",";
		$sql.=" flurstueckskennzeichen,";
		$sql.=" round(inter::numeric) as anteil_abs,";
		$sql.=" round((inter / f_area * 100)::numeric, 1) as anteil_pro";
		$sql.=" from (";
		$sql.=" SELECT DISTINCT n.flurid, n.stammnr, n.rissnummer, f.flurstueckskennzeichen,";
		if(NACHWEIS_SECONDARY_ATTRIBUTE != '')$sql.=" n.".NACHWEIS_SECONDARY_ATTRIBUTE.",";
		$sql.=" st_area(st_intersection(st_transform(n.the_geom, 25833), f.wkb_geometry)) as inter,";
		$sql.=" st_area(f.wkb_geometry) as f_area";
		$sql.=" FROM alkis.ax_flurstueck f,";
		$sql.=" (SELECT n.flurid, n.stammnr, n.art, n.blattnummer, n.rissnummer, n.the_geom";
		if(NACHWEIS_SECONDARY_ATTRIBUTE != '')$sql.=", n.".NACHWEIS_SECONDARY_ATTRIBUTE;		
		$sql.=" FROM nachweisverwaltung.n_nachweise2antraege AS n2a, nachweisverwaltung.n_nachweise AS n";
		$sql.=" LEFT JOIN nachweisverwaltung.n_dokumentarten d ON n.art = d.id";
		$sql.=" WHERE  n.id=n2a.nachweis_id AND n2a.antrag_id='".$this->nr."'";
		if($this->stelle_id == '')$sql.=" AND stelle_id IS NULL";
		else $sql.=" AND stelle_id=".$this->stelle_id;    
		$sql.=" AND (n.art < '111' OR d.geometrie_relevant)";
		$sql.=" )as n";
		$sql.=" WHERE f.endet is null";
		$sql.=" AND st_intersects(st_transform(n.the_geom, ".EPSGCODE_ALKIS."), f.wkb_geometry)";
		$sql.=" order by n.flurid,n.stammnr,n.rissnummer,f.flurstueckskennzeichen) as foo";
		#echo $sql;
		$ret=$this->database->execSQL($sql,4, 0); 
    while($rs=pg_fetch_assoc($ret[1])){
			$rs['stammnr'] = utf8_decode($rs['stammnr']);
			$rs['rissnummer'] = utf8_decode($rs['rissnummer']);		
			$rs['anteil_abs'] = str_replace('.', ',', $rs['anteil_abs']);
			$rs['anteil_pro'] = str_replace('.', ',', $rs['anteil_pro']);
			$intersections[] = $rs;
		}
		return $intersections;
	}
    
  function getFFR($formvars, $withFileLinks = false) {
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
    $sql ="SELECT DISTINCT max(datum) as datum, n.flurid, n.".NACHWEIS_PRIMARY_ATTRIBUTE;
		if(NACHWEIS_SECONDARY_ATTRIBUTE != '')$sql.=",n.".NACHWEIS_SECONDARY_ATTRIBUTE." ";
    $sql.=" FROM nachweisverwaltung.n_nachweise AS n, nachweisverwaltung.n_nachweise2antraege AS n2a";
    $sql.=" WHERE n.id=n2a.nachweis_id AND n2a.antrag_id='".$this->nr."'";
		if($this->stelle_id == '')$sql.=" AND stelle_id IS NULL";
		else $sql.=" AND stelle_id=".$this->stelle_id;
		$sql.=" GROUP BY n.flurid, n.".NACHWEIS_PRIMARY_ATTRIBUTE;
		if(NACHWEIS_SECONDARY_ATTRIBUTE != '')$sql.=",n.".NACHWEIS_SECONDARY_ATTRIBUTE." ";
    $sql.=" ORDER BY datum";
		if(NACHWEIS_SECONDARY_ATTRIBUTE != '' AND $formvars['order'] == NACHWEIS_PRIMARY_ATTRIBUTE)$sql.=", ".NACHWEIS_SECONDARY_ATTRIBUTE;
    #echo $sql;
    $ret=$this->database->execSQL($sql,4, 0);    
    if ($ret[0]) { return $ret[1]; }
    else { $query_id=$ret[1]; }
    $i=0;
    while($rs=pg_fetch_array($query_id)) {      
      if(NACHWEIS_PRIMARY_ATTRIBUTE == 'rissnummer'){
      	if($formvars['Riss-Nummer'])$FFR[$i]['Rissnummer']=$rs['flurid'].'/'.$rs['rissnummer'];
				if(NACHWEIS_SECONDARY_ATTRIBUTE != '')$FFR[$i]['Rissnummer'] .= ' - '.$rs[NACHWEIS_SECONDARY_ATTRIBUTE];
      }
      else{
      	if($formvars['Antrags-Nummer'])$FFR[$i]['Antragsnummer']=$rs['flurid'].'/'.str_pad($rs['stammnr'],ANTRAGSNUMMERMAXLENGTH,'0',STR_PAD_LEFT);
      }
			
      # Abfrage der Riss-/Stammnummern (des nicht primären Ordnungskriteriums)
			$ret=$this->getNotPrimary($rs['flurid'],$rs[NACHWEIS_PRIMARY_ATTRIBUTE], $rs[NACHWEIS_SECONDARY_ATTRIBUTE]);
			if ($ret[0]) { return $ret; }
			if(NACHWEIS_PRIMARY_ATTRIBUTE == 'rissnummer')$not_primary = 'Antragsnummer';
			else $not_primary = 'Rissnummer';
			$FFR[$i][$not_primary]=$ret[1];

      # Abfrage der Anzahl der verschiedenen Dokumentarten zum Vorgang      
			$anz_arten = $this->getAnzArten($rs['flurid'],$rs[NACHWEIS_PRIMARY_ATTRIBUTE], $rs[NACHWEIS_SECONDARY_ATTRIBUTE]);
			foreach($anz_arten as $anz_art){
				$FFR[$i][$anz_art['abkuerzung']] = $anz_art['anz'];
			}
      
    	# Abfrage der Dateinamen im Vorgang
      if($formvars['Datei']){
	      $ret=$this->getDatei($rs['flurid'],$rs[NACHWEIS_PRIMARY_ATTRIBUTE], $rs[NACHWEIS_SECONDARY_ATTRIBUTE], $withFileLinks);
	      if ($ret[0]) { return $ret; }
				$FFR[$i]['Datei - Messungsdatum'] = $ret[1];
      }

      # Abfrage der Vermessungsstellen im Vorgang
      if($formvars['gemessendurch']){
	      $ret=$this->getVermessungsStellen($rs['flurid'],$rs[NACHWEIS_PRIMARY_ATTRIBUTE], $rs[NACHWEIS_SECONDARY_ATTRIBUTE]);
	      if ($ret[0]) { return $ret; }
	      $FFR[$i]['gemessen durch']=utf8_decode($ret[1]);
      }
            
      # Abfrage der Gültigkeiten der Dokumente im Vorgang
	  if($formvars['Gueltigkeit']){
		$ret=$this->getGueltigkeit($rs['flurid'],$rs[NACHWEIS_PRIMARY_ATTRIBUTE], $rs[NACHWEIS_SECONDARY_ATTRIBUTE]);
		if ($ret[0]) { return $ret; }
		$FFR[$i][utf8_decode('Gültigkeit')]=utf8_decode($ret[1]); 
	  }
      $i++;
    }
    $ret[0]=0;
    $ret[1]=$FFR;
    $this->FFR=$FFR;
    return $ret;
  }
	
	function getNotPrimary($flurid,$primary,$secondary){
		if(NACHWEIS_PRIMARY_ATTRIBUTE == 'rissnummer')$not_primary = 'stammnr';
		else $not_primary = 'rissnummer';
    $this->debug->write('<br>antrag.php getNotPrimary Abfragen der NotPrimary zu einem Vorgang in der Nachweisführung.',4);
    $sql.="SELECT ".$not_primary." FROM nachweisverwaltung.n_nachweise AS n";
		$sql.=",nachweisverwaltung.n_nachweise2antraege AS n2a WHERE n.id=n2a.nachweis_id AND n2a.antrag_id='".$this->nr."'";
    $sql.=" AND n.flurid=".$flurid." AND n.".NACHWEIS_PRIMARY_ATTRIBUTE."='".$primary."'";
    if($secondary != '')$sql.=" AND n.".NACHWEIS_SECONDARY_ATTRIBUTE."='".$secondary."'";
		$sql.= "order by art, blattnummer";
    $ret=$this->database->execSQL($sql,4, 0);
    if (!$ret[0]) {
      while($rs=pg_fetch_array($ret[1])) {
        $notPrimary[] = utf8_decode($rs[$not_primary]);
      }
			if(count(array_unique($notPrimary)) == 1)$ret[1] = $notPrimary[0];
      else $ret[1] = implode(chr(10), $notPrimary);
    }     
    return $ret;  
  }
    
	function getDatei($flurid,$nr,$secondary, $withFileLinks) {
    $this->debug->write('<br>nachweis.php getDatei Abfragen der Dateien zu einem Vorgang in der Nachweisführung.',4);
    # Abfragen der Datum zu einem Vorgang in der Nachweisführung
    $sql.="SELECT n.link_datei, datum, lower(h.abkuerzung) as hauptart FROM nachweisverwaltung.n_nachweise AS n";
		$sql.=" LEFT JOIN nachweisverwaltung.n_dokumentarten d ON n.art = d.id";
		$sql.=" LEFT JOIN nachweisverwaltung.n_hauptdokumentarten h ON h.id = d.hauptart";
    if ($this->nr!='') {
      $sql.=",nachweisverwaltung.n_nachweise2antraege AS n2a WHERE n.id=n2a.nachweis_id AND n2a.antrag_id='".$this->nr."'";
    }
    else {
      $sql.=" WHERE (1=1)";
    }
    $sql.=" AND n.flurid=".$flurid." AND n.".NACHWEIS_PRIMARY_ATTRIBUTE."='".$nr."'";
    if($secondary != '')$sql.=" AND n.".NACHWEIS_SECONDARY_ATTRIBUTE."='".$secondary."'";
		$sql.= "order by n.art, n.blattnummer";
    $ret=$this->database->execSQL($sql,4, 0);
    if (!$ret[0]) {
      while($rs=pg_fetch_array($ret[1])) {
				if($withFileLinks)$dateien[] = '<c:alink:../Nachweise/'.$flurid.'/'.Nachweis::buildNachweisNr($nr, $secondary).'/'.$rs['hauptart'].'/'.basename($rs['link_datei']).'>'.basename($rs['link_datei']).'</c:alink>   '.$rs['datum'];
        else $dateien[] =basename($rs['link_datei']).' '.$rs['datum'];
      }
      $ret[1] = implode(chr(10), $dateien);
    }     
    return $ret;  
  }
  
  function getGueltigkeit($flurid,$nr,$secondary) {
    $this->debug->write('<br>nachweis.php getDatum Abfragen der Gueltigkeit der Dokumente in einem Vorgang in der Nachweisführung.',4);
    # Abfragen der Gueltigkeit der Dokumente in einem Vorgang in der Nachweisführung.
    $sql.="SELECT DISTINCT n.gueltigkeit FROM nachweisverwaltung.n_nachweise AS n";
    if ($this->nr!='') {
      $sql.=",nachweisverwaltung.n_nachweise2antraege AS n2a WHERE n.id=n2a.nachweis_id AND n2a.antrag_id='".$this->nr."'";
    }
    else {
      $sql.=" WHERE (1=1)";
    }
    $sql.=" AND n.flurid=".$flurid." AND n.".NACHWEIS_PRIMARY_ATTRIBUTE."='".$nr."'";
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
        $ret[1]='alle gültig';
      }
    }     
    return $ret;  
  }
  
  function getVermessungsStellen($flurid,$nr,$secondary) {
    $this->debug->write('<br>nachweis.php getDatum Abfragen der Vermessungsstellen, die an einem Vorgang beteiligt waren in der Nachweisführung.',4);
    # Abfragen der Vermessungsstellen, die an einem Vorgang beteiligt waren in der Nachweisführung.
    $sql.="SELECT DISTINCT v.name FROM nachweisverwaltung.n_nachweise AS n, nachweisverwaltung.n_vermstelle AS v";
    if ($this->nr!='') {
      $sql.=",nachweisverwaltung.n_nachweise2antraege AS n2a WHERE n.id=n2a.nachweis_id AND n2a.antrag_id='".$this->nr."'";
    }
    else {
      $sql.=" WHERE (1=1)";
    }
    $sql.=" AND n.flurid=".$flurid." AND n.".NACHWEIS_PRIMARY_ATTRIBUTE."='".$nr."'";
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
    
  function getAnzArten($flurid,$nr,$secondary) {
    $this->debug->write('<br>nachweis.php getAnzFFR Abfragen der Anzahl der Blätter eines FFR.',4);
    # Abfrag der Anzahl der zum Riss gehörenden Fortführungsrisse
    $sql.="SELECT h.abkuerzung, CASE WHEN COUNT(n2a.nachweis_id) = 0 THEN '-' ELSE COUNT(n2a.nachweis_id)::text END AS anz FROM nachweisverwaltung.n_hauptdokumentarten h " ;
		$sql.="LEFT JOIN nachweisverwaltung.n_dokumentarten d ON h.id = d.hauptart ";
		$sql.="LEFT JOIN nachweisverwaltung.n_nachweise AS n ON n.art = d.id AND n.flurid=".$flurid." AND n.".NACHWEIS_PRIMARY_ATTRIBUTE."='".$nr."'";
    if($secondary != '')$sql.=" AND n.".NACHWEIS_SECONDARY_ATTRIBUTE."='".$secondary."'";
    if ($this->nr!='') {
			$sql.=" LEFT JOIN nachweisverwaltung.n_nachweise2antraege AS n2a ON n.id=n2a.nachweis_id AND n2a.antrag_id='".$this->nr."'";
    }
    $sql.=" GROUP BY h.id, h.abkuerzung";
		$sql.=" ORDER BY h.id";
    $queryret=$this->database->execSQL($sql,4, 0);
    if ($queryret[0]) { $ret=$queryret; }
    else {
      $ret[0]=0;
      while($rs=pg_fetch_array($queryret[1])){
				$anz_art[]=$rs;
			}
    }
    return $anz_art;
  }
  
  function pruefe_antrag_eintragen($antr_nr,$VermStelle,$verm_art,$datum,$stelle_id) {
    #prüfen, ob die Antragsnummer korrekt eingegeben wurde!
    $strenthalten=0;
    if($antr_nr==''){
      $errmsg.='Bitte geben Sie die Antragsnummer ein!<br>';
    }
    $this->debug->write("<br>antrag.php pruefe_antrag_eintragen() prüfen der Eingabe der Anträge, ob Antragsnummer schon vorhanden.<br>".$sql,4);        
    $sql ="SELECT * FROM nachweisverwaltung.n_antraege WHERE antr_nr = '".$antr_nr."' AND stelle_id=".$stelle_id;
    $queryret=$this->database->execSQL($sql,4, 0);
    if ($queryret[0]) {
      $errmsg.='Fehler bei der Abfrage der Anträge! '.$queryret[1].'<br>';
    }
    else {
      if (pg_num_rows($queryret[1])>0) {
        $errmsg.='Antragsnummer '.$antr_nr.' existiert bereits!';
      }
    }

    #prüfen, ob ein Datum eingegeben wurde
    if ($datum==''){
      $errmsg.='Bitte geben Sie ein Datum an!<br>';
    }
    else {
      # Pfüfen, ob es sich um das richtige Format handelt.
      $datumteile=explode('.',$datum);
      if (!checkdate($datumteile[1],$datumteile[0],$datumteile[2])) {
        $errmsg.='Das ist kein gültiges Datum. Geben sie es in der Form TT-MM-JJJJ ein.<br>';
      }
    }
    if($verm_art==''){
      $errmsg.='Bitte geben Sie die Vermessungsart an!<br>';
    }
    return $errmsg;
  }
  
  function antrag_eintragen($antr_nr,$VermStelle,$verm_art,$datum,$stelle_id) {
    $sql ="INSERT INTO nachweisverwaltung.n_antraege (antr_nr,vermstelle,vermart,datum,stelle_id)";
    $sql.=" VALUES('".$antr_nr."',".$VermStelle.",".$verm_art.",'".$datum."',".$stelle_id.")";
    $queryret=$this->database->execSQL($sql,4, 1);
    if ($queryret[0]) {
      $errmsg='Es konnte keine Antragsnummer in die Datenbank eingetragen werden!'; 
    }
    else {
      $errmsg.='Auftragsnummer erfolgreich übernommen! ';
    }
    return $errmsg;
  }
  
  function antrag_aendern($antr_nr,$VermStelle,$verm_art,$datum,$stelle_id) {
		$sql ="UPDATE nachweisverwaltung.n_antraege SET vermstelle=".(int)$VermStelle.",vermart=".(int)$verm_art.",datum='".$datum."'";
		$sql.=" WHERE antr_nr='".$antr_nr."'";
		if($stelle_id == '')$sql.=" AND stelle_id IS NULL";
		else $sql.=" AND stelle_id=".$stelle_id;
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
  
  function antrag_loeschen($antr_nr, $stelle_id){
		$this->database->begintransaction();
		$this->debug->write("<br>antrag.php antrag_loeschen Löschen der Anträge inclusive der Zuordnungen zu Nachweisdokumenten.<br>",4);
		$sql="DELETE FROM nachweisverwaltung.n_antraege WHERE antr_nr='".$antr_nr."'";
		if($stelle_id == '')$sql.=" AND stelle_id IS NULL";
		else $sql.=" AND stelle_id=".$stelle_id;
		$queryret=$this->database->execSQL($sql,4, 1);
		if ($queryret[0]) {
			$errmsg.='Fehler beim Löschen des Antrages!';
		}
		$sql="DELETE FROM nachweisverwaltung.n_nachweise2antraege WHERE antrag_id='".$antr_nr."'";
		if($stelle_id == '')$sql.=" AND stelle_id IS NULL";
		else $sql.=" AND stelle_id=".$stelle_id;
		$queryret=$this->database->execSQL($sql,4, 1);
		if ($queryret[0]) {
			$errmsg.='Fehler beim Löschen der Zuordnungen der Nachweisdokumente zum Antrag!';
		}
		if ($errmsg!='') {
			$this->database->rollbacktransaction();
		}
		else {
			$exceptions = array(".", "..");
			if($stelle_id != '')$antr_nr .= '~'.$stelle_id;
			delete_files(RECHERCHEERGEBNIS_PATH.$antr_nr, $exceptions, 0);
			$errmsg='Antrag erfolgreich gelöscht';
			$this->database->committransaction();      
		}
    return $errmsg;
  }
  
  function getAntragsnr_Liste($stelle_id) {
    global $admin_stellen;		
    $sql = "SELECT * FROM nachweisverwaltung.n_antraege ";
		if(!in_array($stelle_id, $admin_stellen))$sql.= "WHERE stelle_id = ".$stelle_id;
		$sql.= " ORDER BY antr_nr";
    $queryret=$this->database->execSQL($sql,4, 0);
    if ($queryret[0]) { 
      $errmsg='Fehler bei der Abfrage der Antragsnummern in der Datenbank bei Statement: '; 
    }
    else {
      $Antr_nr_Liste['antr_nr'][0]='';
			$Antr_nr_Liste['antr_nr_stelle_id'][0]='';
      while($rs=pg_fetch_array($queryret[1])) {
        $Antr_nr_Liste['antr_nr'][]=$rs['antr_nr'];
				$Antr_nr_Liste['antr_nr_stelle_id'][]=$rs['antr_nr'].'~'.$rs['stelle_id'];
      }
      $errmsg='';
    }
    $ret[0]=$errmsg;
    $ret[1]=$Antr_nr_Liste;
    return $ret;
  }
  
# 2016-11-03, H.Riedel - um stelle_id erweitert, pkz in pkn geaendert
  function addFestpunkt($pkn) {
    # Fügt einen Festpunkt in dem Antrag hinzu
    $sql ="INSERT INTO nachweisverwaltung.fp_punkte2antraege (pkn,antrag_nr,zeitstempel,stelle_id)";
    $sql.="(";
    $sql.=" SELECT '".$pkn."','".$this->nr."',CURRENT_TIMESTAMP(0), '".$this->stelle_id."' ";
    $sql.=" WHERE NOT EXISTS (";
    $sql.=" SELECT 1 FROM nachweisverwaltung.fp_punkte2antraege WHERE pkn='".$pkn."' AND antrag_nr='".$this->nr."' AND stelle_id='".$this->stelle_id."')";
    $sql.=")";
    $this->debug->write("<br>antrag->addFestpunkt().<br>",4);
    $ret=$this->database->execSQL($sql,4, 1);
    return $ret;
  }
}
?>
