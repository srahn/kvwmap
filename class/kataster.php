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
#  kataster.php  Klassenbibliothek für Klassen zum Kataster       #
###################################################################
# Liste der Klassen:
#########################
# Flur
# Adresse
# Gebaeude
# Nutzung
# Ausgestaltung
# Festpunkte
# Finanzamt
# Forstamt
# Kreis
# Gemeinde
# Amtsgericht
# Gemarkung
# Eigentuemer
# Grundstueck
# Grundbuch
# Flurstueck
# Vermessungsstelle
#########################

#-----------------------------------------------------------------------------------------------------------------
################
#-> Festpunkte #
################

class Festpunkte {
  var $pkz;
  var $art;
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
  # filebasenamepkz($filename)
  # getFestpunkte($pkz,$art,$vermarkt,$verhandelt,$polygon,$auftr_nr,$kmquad,$order)
  # getKilometerQuadrate()
  # is_valid_pfad()
  # ladenFestpunktdatei()
  # leereFestpunktTabelle()
  # moveFiles()
  # pkz2pfad()
  # pruefeSuchParameterPolygon($polygon)
  # uebernehmen()
  #
  ################################################################################

  function Festpunkte($dateiname,$database) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
    $this->dateiname=$dateiname;
    $this->tabellenname='fp_punkte_temp';
  }

  function createKVZdatei($antrag_nr, $pkz = '') {
    # Diese Funktion erzeugt im Verzeichnis der recherchierten Antraege (RECHERCHEERGEBNIS_PATH)
    # ein Datei mit Koordinatenverzeichnis im Satzformat aus GEOI-Programm (Standardisiertes Ausgabeformat)
    # Ausgegeben werden alle Punkte, die zur antrag_nr gehören, die über die Abfrage getFestpunkte in $this->liste geschrieben wurden
    # Verzeichnispfad bilden und prüfen ob schon vorhanden, wenn nicht Verzeichnis anlegen
    if($pkz != '')$pkz = array_values($pkz);
    $kvzPfad=RECHERCHEERGEBNIS_PATH.$antrag_nr.'/';
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
      $ret=$this->getFestpunkte($pkz,'','','','',$antrag_nr,'','pkz');
      if ($ret[0]) {
        $errmsg="Festpunkte konnten nicht abgefragt werden.";
      }
      else {
        $zeile = KVZKOPF;
				$zeile.="\r\n"; # Zeilenumbruch
        for ($i=0;$i<$this->anzPunkte;$i++) {
		  		if($i!=0)$zeile = "";
          $p=$this->liste[$i];
          # Entfernen der - in pkz für Punktnummer ohne -
          $p["pnr"]=str_replace("-","",$p["pkz"]);
          $zeile.=$p["ls"]; # Lagestatus
          $zeile.=" ".str_pad(trim($p["pnr"]),14," ",STR_PAD_LEFT); # Punktkennzeichen
          $zeile.=" ".str_pad(trim($p["vma"]),3,"0",STR_PAD_LEFT); # Vermarkungsart
          $zeile.=" ".$p["rw"]; # Rechtswert
          $zeile.=" ".$p["hw"]; # Hochwert
          $zeile.=" "; # 47 Leerstelle
          if ($p["hoe"]=='') {
            $zeile.=str_repeat(" ",8); # 48-55 Wenn Höhe nicht angegeben mit Leerstellen auffüllen
          }
          else {
            $zeile.=" ".sprintf("%08.3f",$p["hoe"]); # 48-55 Höhe
          }
          $zeile.=" "; # 56 Leerstelle
          if ($p["hz"]=='') {
            $zeile.=" "; # 57 Wenn HZ nicht angegeben mit Leerzstelle auffüllen
          }
          else {
            $zeile.=sprintf("%1d",$p["hz"]); # 57 HZ
          }
          $zeile.=" "; # 58 Leerstelle
          if ($p["hg"]=='') {
            $zeile.=" "; # 59 Wenn hg nicht angegeben mit Leerzstelle auffüllen
          }
          else {
            $zeile.=sprintf("%1d",$p["hg"]); # 59 HG
          }
          $zeile.="  "; # 60-61 Leerzeichen
          $zeile.=sprintf("%1d",$p["lz"]); # 62 Lagezuverlässigkeit
          $zeile.=" "; # 63 Leerstelle
          $zeile.=sprintf("%1d",$p["lg"]); # 64 Lagegenauigkeitsstufe
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
      $ret[1]=$this->anzPunkte." Zeilen in die Datei ".$dateiname." geschrieben.";
      $ret[2]=$dateiname;
    }
    return $ret;
  }

  # 2006-02-19 pk koordinaten durch the_geom ersetzt
  function getBBoxAsRectObj($Festpunkte) {
    if (is_array($Festpunkte)) { $Liste=$Festpunkte;  } else { $Liste=array($Festpunkte); }
    $sql ="SELECT round(xmin(extent(the_geom))) as xmin,round(ymin(extent(the_geom))) as ymin";
    $sql.=",round(xmax(extent(the_geom))) as xmax, round(ymax(extent(the_geom))) as ymax";
    $sql.=" FROM fp_punkte WHERE pkz IN ('".$Liste[0]."'";
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

  function filebasename2pkz($filebasename) {
    $kiloquad=substr($filebasename,0,-6);
    $art=substr($filebasename,-6,-5);
    $nr=substr($filebasename,-5);
    $pkz=$kiloquad.'-'.$art.'-'.$nr;
    return $pkz;
  }

  function pkz2pfad($pkz) {
    # erzeugt aus einer pkz Angabe den Dateiname mit Pfadangabe für eine Einmessungsskizze.
    # png oder tif muss selbst noch hinterher angehängt werden.
    $kiloquad=substr($pkz,0,-8);
    $filename=str_replace('-','',$pkz);
    $pfadname=$kiloquad.'/'.$filename;
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

  function uebernehmen() {
  	#	4. Streifen
    $this->tabellenname='fp_punkte';
    # Transaktion starten
    $this->database->begintransaction();
    # Vorhandene Daten löschen
    $ret=$this->leereFestpunktTabelle();
    # Punkte aus der temporären Tabelle in Festpunkttabelle übernehmen
    $sql ="INSERT INTO fp_punkte SELECT * FROM fp_punkte_temp WHERE substring(rw from 0 for 2) = '4';";
    $this->debug->write("<p>kataster.php->Festpunkte->uebernehmen",4);
    $ret=$this->database->execSQL($sql,4, 1);
    if ($ret[0]) { # Fehler bei der Datenbankaktion
      # Zurückrollen der Transaktion
      $this->database->rollbacktransaction();
      $errmsg.='Die bisherigen Datenbankaktionen wurden rückgängig gemacht.';
      $ret[0]=1; $ret[1]=$errmsg;
    }
    else {
      $ret=$this->getAnzFestpunkte();
      if (!$ret[0]) { $anzPunkte=$ret[1]; }
      # Beschließen der Transaktion
      $this->database->committransaction();
      $protokoll.='<p>Die Übernahme ist fehlerfrei erfolgt.';
      $protokoll.='<br>'.$anzPunkte['Gesamt'].' neu eingelesen und aufbereitet.';
      $protokoll.='<br>'.$anzPunkte['TP'].' topographische Festpunkte';
      $protokoll.='<br>'.$anzPunkte['OP'].' Orientierungspunkte';
      $protokoll.='<br>'.$anzPunkte['AP'].' Aufnahmepunkte';
      $protokoll.='<br>'.$anzPunkte['SiP'].' Sicherungspunkte';
      $protokoll.='<br>'.$anzPunkte['GrenzP'].' Grenzpunkte';
      $protokoll.='<br>'.$anzPunkte['GebP'].' Gebäudepunkte';
      $protokoll.='<br>'.$anzPunkte['NutzP'].' Nutzungsartenpunkte';
      $ret[0]=0; $ret[1]=$protokoll;
    }
    #----------------------------------------------------------------------
    if(FESTPUNKTE_2_STREIFEN == 'true'){
	    #	5. Streifen
	    $this->tabellenname='fp_punkte2';
	    # Transaktion starten
	    $this->database->begintransaction();
	    # Vorhandene Daten löschen
	    $ret2=$this->leereFestpunktTabelle();
	    # Punkte aus der temporären Tabelle in Festpunkttabelle übernehmen
	    $sql ="INSERT INTO fp_punkte2 SELECT * FROM fp_punkte_temp WHERE substring(rw from 0 for 2) = '5';";
	    $this->debug->write("<p>kataster.php->Festpunkte->uebernehmen",4);
	    $ret2=$this->database->execSQL($sql,4, 1);
	    if ($ret2[0]) { # Fehler bei der Datenbankaktion
	      # Zurückrollen der Transaktion
	      $this->database->rollbacktransaction();
	      $errmsg.='Die bisherigen Datenbankaktionen wurden rückgängig gemacht.';
	      $ret2[0]=1; $ret2[1]=$errmsg;
	    }
	    else {
	      # Beschließen der Transaktion
	      $this->database->committransaction();
	    }
    }
    return $ret;
  }

  function updateFlaechenQualitaet() {
    # Löschen der vorhandenen Tabelle für die Darstellung der Flurstücksflächenqualitätsparameter
    $sql='TRUNCATE q_alknflst';

    # Eintragen der Flurstücke, bei denen alle Grenzpunkte verhandet sind
    # vollständigverhandelt
    $sql ="INSERT INTO q_alknflst (objnr,verhandelt) SELECT o.objnr,MIN(p.verhandelt)";
    $sql.=" FROM alkobj_e_fla AS o,fp_punkte AS p WHERE o.folie='001' AND o.the_geom && p.the_geom";
    $sql.=" AND touches(o.the_geom,p.the_geom) GROUP BY o.objnr,o.the_geom HAVING MIN(p.verhandelt)=1";
    $sql.=" AND npoints(o.the_geom)-1=count(o.objnr)";

    # Eintragen der Flurstkennz, bei denen nicht alle Grenzpunkte verhandelt sind.
    $sql ="INSERT INTO q_alknflst (objnr,verhandelt) SELECT o.objnr,MIN(p.verhandelt) FROM alkobj_e_fla AS o";
    $sql.=",fp_punkte AS p WHERE o.folie='001' AND o.the_geom && p.the_geom AND touches(o.the_geom,p.the_geom)";
    $sql.=" GROUP BY o.objnr,o.the_geom HAVING MIN(p.verhandelt)=0 AND npoints(o.the_geom)-1=count(o.objnr)";
  }

  function aktualisieren() {
    # 2006-01-26 pk
    # Transaktion starten
    $this->database->begintransaction();
    # Vorhandene Daten löschen
    $ret=$this->leereFestpunktTabelle();
    if ($ret[0]) {
      $errmsg=$ret[1];
    }
    else {
      $protokoll.=$ret[1];
      # Datei in Datenbanktabelle einlesen
      $ret=$this->ladenFestpunktdatei();
      if ($ret[0]) {
        $errmsg.=$ret[1];
      }
      else {
        $fp_ret=$this->getAnzFestpunkte();
        if (!$ret[0]) { $anzPunkte=$fp_ret[1]; }
        $protokoll.='<br>'.$anzPunkte['Gesamt'].' neu eingelesen und aufbereitet.';
        $protokoll.='<br>'.$anzPunkte['TP'].' topographische Festpunkte';
        $protokoll.='<br>'.$anzPunkte['OP'].' Orientierungspunkte';
        $protokoll.='<br>'.$anzPunkte['AP'].' Aufnahmepunkte';
        $protokoll.='<br>'.$anzPunkte['SiP'].' Sicherungspunkte';
        $protokoll.='<br>'.$anzPunkte['GrenzP'].' Grenzpunkte';
        $protokoll.='<br>'.$anzPunkte['GebP'].' Gebäudepunkte';
        $protokoll.='<br>'.$anzPunkte['NutzP'].' Nutzungsartenpunkte';
      }
    }
    # Transaktion beenden oder zurückrollen
    if ($errmsg!='') { # Fehler bei der Datenbankaktion
      # Zurückrollen der Transaktion
      $this->database->rollbacktransaction();
      $ret[1]='Die bisherigen Datenbankaktionen wurden rückgängig gemacht.';
    }
    else {
      # Beschließen der Transaktion zum Einlesen der Punkte
      $this->database->committransaction();
      $ret[1]=$protokoll.'<p>Das Einlesen der Punkte ist fehlerfrei erfolgt.';
    } # ende erfolgreiches Einlesen
    return $ret;
  }

  function checkSkizzen($pkz) {
    # ermittelt für pkz ob Einmessungskizzen in Form von tif oder png Dateien vorliegen,
    # Konvertieren des pkz in den Pfadnamen für die Einmessungsskizze
    $relPfad=$this->pkz2pfad($pkz);
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

  # 2006-02-19 pk koordinaten durch the_geom ersetzt
  function ladenFestpunktdatei() {
  	if(CHECKPUNKTDATEI == 'true'){
  		# Festpunktdatei in Array einlesen und doppelte Punkte in diesem Array löschen
  		$punktdatei = file(PUNKTDATEIPATH.PUNKTDATEINAME);
  		sort($punktdatei);
  		for($i = 0; $i < count($punktdatei); $i++){
  			if(substr($punktdatei[$i], 0, 16) == substr($punktdatei[$i+1], 0, 16)){
  				$doppelte[] = $punktdatei[$i];
  				$punktdatei[$i] = '';
  			}
  		}
  		# Festpunktdatei zurückschreiben
  		$fp = fopen(PUNKTDATEIPATH.PUNKTDATEINAME, w);
  		for($i = 0; $i < count($punktdatei); $i++){
  			if($punktdatei != ''){
  				fwrite($fp, $punktdatei[$i]);
  			}
  		}
  		fclose($fp);
  		# Datei mit doppelten Punkten erzeugen
  		$fp = fopen(PUNKTDATEIPATH.'doppelte_festpunkte.txt', w);
  		for($i = 0; $i < count($doppelte); $i++){
  			fwrite($fp, $doppelte[$i]);
  		}
  		fclose($fp);
  	}
		#angepasst, um die 1. Zeile aus den csv-Dateien nicht mehr zu löschen 19.05.2006 H. Riedel
		if(POSTGRESVERSION >= '810'){
    	$sql ="COPY ".$this->tabellenname." (PKZ,RW,HW,HOE,S,ZST,VMA,BEM,ENT,UNT,ZUO,TEX,LS,LG,LZ,LBJ,LAH,HS,HG,HZ,HBJ,HAH) FROM '".PUNKTDATEIPATH.PUNKTDATEINAME."' WITH DELIMITER AS ';' CSV HEADER FORCE NOT NULL hoe, zuo, tex, hs, hg, hz, hbj, hah; ";
		}
		else{
			$sql ="COPY ".$this->tabellenname." (PKZ,RW,HW,HOE,S,ZST,VMA,BEM,ENT,UNT,ZUO,TEX,LS,LG,LZ,LBJ,LAH,HS,HG,HZ,HBJ,HAH) FROM '".PUNKTDATEIPATH.PUNKTDATEINAME."' WITH DELIMITER AS ';' CSV FORCE NOT NULL hoe, zuo, tex, hs, hg, hz, hbj, hah; ";
		}

    # Ersetzte die Kommas durch Punkte
    $sql.="UPDATE ".$this->tabellenname." SET rw=replace(rw,',','.'), hw=replace(hw,',','.'), hoe=replace(hoe,',','.'); ";

    # Auffüllen der Geometriespalten aus den Angaben zu Rechts-, Hochwert und Höhe
    $sql.="UPDATE ".$this->tabellenname." SET the_geom=force_3D(GeometryFromText('POINT('||rw||' '||hw||' '||hoe||')', 2398)) WHERE substring(rw from 0 for 2) = '4'; ";
    $sql.="UPDATE ".$this->tabellenname." SET the_geom=force_3D(GeometryFromText('POINT('||rw||' '||hw||' '||hoe||')', 2399)) WHERE substring(rw from 0 for 2) = '5'; ";

    # Selektieren der Punktarten aus den Punktkennzeichen
    $sql.="UPDATE ".$this->tabellenname." SET art=CAST(substring(pkz from '-(.)-') AS int); ";

    # Selektieren der Orientierungspunkte aus Punktkennzeichen
    $sql.="UPDATE ".$this->tabellenname." SET art=6 WHERE art=0 AND SUBSTRING(pkz,'.$') NOT LIKE '0'; ";

    # Selektieren der Sicherungspunkte aus der Spalte ent
    $sql.="UPDATE ".$this->tabellenname." SET art=5 WHERE ent LIKE '*%'; ";

    # Selektieren des Dateinamens der Einmessungsskizze
    $sql.="UPDATE ".$this->tabellenname." SET pkz=trim(both ' ' from pkz); ";
    $sql.="UPDATE ".$this->tabellenname." SET datei=substring(pkz from 0 for position('-' in pkz))||'/'||replace(pkz,'-','')||'.".SKIZZEN_DATEI_TYP."'; ";

    # Selektieren ob verhandelt oder nicht
    $sql.="UPDATE ".$this->tabellenname." SET verhandelt=1 WHERE lah LIKE '%*'; ";

    # Selektieren ob vermarkt oder unvermarkt
    $sql.="UPDATE ".$this->tabellenname." SET vermarkt=1 WHERE vma NOT IN ('000','070','071','073','088','089','090','091','093'); ";

    # Selektieren der Punktnummern aus den Punktkennzeichen
    $sql.="UPDATE ".$this->tabellenname." SET pktnr=TRIM(leading '0' FROM SUBSTRING(pkz FROM '.....$')); ";

    $this->debug->write("<p>kataster.php->Festpunkte->ladenFestpunktdatei",4);
    $ret=$this->database->execSQL($sql,4, 0);

    return $ret;
  }

  function ladenFehlerellipsendatei($datei) {
    # Laden der Fehlerellipsendatei
    # noch defaultmässig hier eingestellt diese Datei zum Testen
    $datei="/www/kvwmap/var/data/Festpunkte/SYSTRA.KOO";
    $sql ="COPY q_fehlerellipsen (pkz,rw,hw,hoe,mfge,ls,phi,a,b) FROM '".$datei."' WITH DELIMITER ';';";
    $this->debug->write("<p>kataster.php->Festpunkte->ladenFehlerellipsendatei",4);
    $ret=$this->database->execSQL($sql,4, 0);

    return $ret;
  }

  function getFehlerellipsen($pkz,$minx,$maxx,$miny,$maxy,$minmfge,$maxmfge,$ls,$mina,$maxa,$minb,$maxb) {
    $sql ="SELECT pkz,rw,hw,hoe,mfge,ls,phi,a,b FROM q_fehlerellipsen";
    if ($minx!='') {
      # zusammensetzen des Rechtecks
      # fehlt hier noch
      $sql.=" WHERE the_geom && GeometryFromText('xxxx',".EPSG_CODE.")";
      $sql.=" AND  NOT Disjoint(position,GeometryFromText('xxxx',".EPSG_CODE."))";
    }
    $this->debug->write("<p>kataster.php->Festpunkte->ladenFehlerellipsendatei",4);
    $ret=$this->database->execSQL($sql,4, 0);
    if ($ret[0]) {
      $errmsg.='Fehler bei der Abfrage der Fehlerellipsen:<br>'.$ret[1];
    }
    else {
      $this->anzFehlerellipsen=0;
      while ($rs=pg_fetch_array($ret[1])) {
        $fehlerellipsen[]=$rs;
        $this->anzFehlerellipsen++;
      }
      $ret[1]=$fehlerellipsen;
    }
    return $ret;
  }

  function leereFestpunktTabelle() {
    # Abfragen wieviele Punkte in der Tabelle enthalten sind.
    $ret=$this->getAnzFestpunkte();
    if (!$ret[0]) { $anzPunkte=$ret[1]; }
    # Löschen aller Einträge in der Tabelle der Festpunkte
    $sql ="TRUNCATE TABLE ".$this->tabellenname;
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

  function getAnzFestpunkte() {
    $sql ="SELECT art,COUNT(pkz) AS anz FROM fp_punkte_temp GROUP BY art ORDER BY art";
    $this->debug->write("<p>kataster.php->Festpunkte->getAnzFestpunkte",4);
    $ret=$this->database->execSQL($sql,4, 0);
    if ($ret[0]) {
      $ret[1].='<br>Fehler beim Abfragen der Punktanzahl.';
    }
    else {
      $anzPunkte['Gesamt']=0;
      $rs=pg_fetch_array($ret[1]); $anzPunkte['TP']=$rs['anz']; $anzPunkte['Gesamt']+=$rs['anz'];
      $rs=pg_fetch_array($ret[1]); $anzPunkte['AP']=$rs['anz']; $anzPunkte['Gesamt']+=$rs['anz'];
      $rs=pg_fetch_array($ret[1]); $anzPunkte['GrenzP']=$rs['anz']; $anzPunkte['Gesamt']+=$rs['anz'];
      $rs=pg_fetch_array($ret[1]); $anzPunkte['GebP']=$rs['anz']; $anzPunkte['Gesamt']+=$rs['anz'];
      $rs=pg_fetch_array($ret[1]); $anzPunkte['NutzP']=$rs['anz']; $anzPunkte['Gesamt']+=$rs['anz'];
      $rs=pg_fetch_array($ret[1]); $anzPunkte['SiP']=$rs['anz']; $anzPunkte['Gesamt']+=$rs['anz'];
      $rs=pg_fetch_array($ret[1]); $anzPunkte['OP']=$rs['anz']; $anzPunkte['Gesamt']+=$rs['anz'];
      $ret[1]=$anzPunkte;
    }
    return $ret;
  }

  # 2006-02-19 pk koordinaten durch the_geom ersetzt
  function getFestpunkte($pkz,$art,$vermarkt,$verhandelt,$polygon,$antrag_nr,$kmquad,$order) {
    # Die Funktion liefert die Festpunkte nach verschiedenen Suchkriterien.
    if (is_array($pkz)) { $pkzListe=$pkz; } else { $pkzListe=array($pkz); }
    if (is_array($art)) { $artListe=$art; } else {
      if ($art=='') {
        $art='-1';
      }
      $artListe=array($art);
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
      if (PUNKTDATEINAME=='alk') {
        $sql ="SELECT p.nbz||'-'||p.pat||'-'||p.pnr AS pkz,p.nbz,p.pat AS art,p.pnr,asText(o.the_geom) AS wkt_the_geom";
        $sql.=" FROM alknpunkt AS p,alkobj_e_pkt AS o";
        if ($antrag_nr!='') {
          $sql.=",fp_punkte2antraege AS p2a";
        }
        $sql.=" WHERE p.objnr=o.objnr";
        $anzPkz=count($pkzListe);
        if ($pkzListe[0]!='') {
          if ($anzPkz==1) {
            $sql.=" AND pkz LIKE '".$pkzListe[0]."'";
          }
          else {
            $sql.=" AND pkz IN ('".$pkzListe[0]."'";
            for ($i=1;$i<$anzPkz;$i++) {
              $sql.=",'".$pkzListe[$i]."'";
            }
            $sql.=")";
          }
        }
        $anzArten=count($artListe);
        if ($artListe[0]>'-1') {
          $sql.=" AND p.pat IN (".$artListe[0];
          for ($i=1;$i<$anzArten;$i++) {
            $sql.=",".$artListe[$i];
          }
          $sql.=")";
        }
        if ($kmquad!='') {
          $sql.=" AND p.nbz='".$kmquad."'";
        }
        if ($polygon!='') {
          $sql.=" AND NOT DISJOINT('".$polygon."',o.the_geom)";
        }
        if ($antrag_nr!='') {
          $sql.=" AND pkz=p2a.pkz AND p2a.antrag_nr='".$antrag_nr."'";
        }
        if ($order=='') {
          $order="pkz";
        }
        $sql.=" ORDER BY ".$order;
        #echo $sql;
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
      } # ende Datenquelle für Festpunkte alk-Tabellen
      else {
        $sql ="SELECT p.*,asText(p.the_geom) AS wkt_the_geom FROM ".$this->tabellenname." AS p";
        if ($antrag_nr!='') {
          $sql.=",fp_punkte2antraege AS p2a";
        }
        $sql.=" WHERE 1=1";
        $anzPkz=count($pkzListe);
        if ($pkzListe[0]!='') {
          if ($anzPkz==1) {
            $sql.=" AND p.pkz LIKE '".$pkzListe[0]."'";
          }
          else {
            $sql.=" AND p.pkz IN ('".$pkzListe[0]."'";
            for ($i=1;$i<$anzPkz;$i++) {
              $sql.=",'".$pkzListe[$i]."'";
            }
            $sql.=")";
          }
        }
        $anzArten=count($artListe);
        if ($artListe[0]>'-1') {
          $sql.=" AND p.art IN (".$artListe[0];
          for ($i=1;$i<$anzArten;$i++) {
            $sql.=",".$artListe[$i];
          }
          $sql.=")";
        }
        if ($kmquad!='') {
          $sql.=" AND substring(p.pkz from 0 for position('-' in p.pkz))='".$kmquad."'";
        }
        if ($polygon!='') {
          $sql.=" AND NOT DISJOINT('".$polygon."',p.the_geom)";
        }
        if ($antrag_nr!='') {
          $sql.=" AND p.pkz=p2a.pkz AND p2a.antrag_nr='".$antrag_nr."'";
        }
        if ($order=='') {
          $order="pkz";
        }
        $sql.=" ORDER BY ".$order;
        #echo $sql;
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
      } # ende Datenquelle für Festpunkte fp_punkte
    }
    if ($errmsg!='') { $ret[1]=$errmsg; }
    $this->liste=$festpunkte;
    return $ret;
  } # ende funktion getFestpunkte

  function getKilometerQuadrate() {
    $kilometerquadrate=array();
    if ($this->anzPunkte>0) {
      # liefert alle KilometerQuadrate der Festpunkte, die sich aktuell in der Liste des Objektes befinden.
      $this->debug->write('<br>kataster.php->getKilometerQuadrate Ermitteln der Kilometerquadrate der Festpunkte:',4);
      # Auslesen aller Werte aus der Liste der Festpunkte
      foreach($this->liste AS $value) {
        # extrahieren der Bestandteile von pkz, getrennt durch "-"
        $pkz=explode('-',$value['pkz']);
        # zuweisen des ersten Teiles der pkz zur Liste der kilometerquadrate
        $kilometerquadrate[]=$pkz[0];
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
##########
#-> Flur #
##########

class Flur extends Flur_core{
  var $FlurID;
  var $database;
  ###################### Liste der Funktionen ####################################
  #
  # function Flur($GemID,$GemkgID,$FlurID)  - Construktor
  # function getFlurListe($GemkgID,$FlurID,$order)
  # function getFlurListeByExtent($extent)
  # function getFlurstByPoint($point)
  # function getDataSourceName()
  # function getMER($layer)
  # function updateFluren()
  #
  ################################################################################

  function Flur($GemID,$GemkgID,$FlurID,$database) {
    # constructor
    global $debug;
    $this->debug=$debug;
    $this->GemID=$GemID;
    $this->GemkgID=$GemkgID;
    $this->FlurID=$FlurID;
    $this->database=$database;
    $this->LayerName=LAYERNAME_FLUR;
  }

  function getFlurListe($GemkgID,$FlurID,$order, $historical = false) {
    # Abfragen der Fluren
    $Liste=$this->database->getFlurenListeByGemkgIDByFlurID($GemkgID,$FlurID,$order, $historical);
    return $Liste;
  }
  
  function getFlurListeALKIS($GemkgID,$FlurID,$order, $historical = false) {
    # Abfragen der Fluren
    $Liste=$this->database->getFlurenListeByGemkgIDByFlurIDALKIS($GemkgID,$FlurID,$order, $historical);
    return $Liste;
  }
  
  
  function getFlurListeALK($GemkgID,$order, $historical = false) {
    # Abfragen der Fluren
    $Liste=$this->database->getFlurenListeByGemkgIDByFlurIDALK($GemkgID,$order, $historical);
    return $Liste;
  }

  function getFlurListeByExtent($extent) {
    # Abfragen der Fluren, die im aktuellen Ausschnitt zu sehen sind.
    $map=ms_newMapObj('');
    $layer=ms_newLayerObj($map);
    $layer->set('data',SHAPEPATH.$this->getDataSourceName());
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->queryByRect($extent);
    $layer->open();
    $anzResult=$layer->getNumResults();
    for ($i=0;$i<$anzResult;$i++) {
      $result=$layer->getResult($i);
      $shapeindex=$result->shapeindex;
      if(MAPSERVERVERSION > 500){
      	$shape=$layer->getFeature($shapeindex,-1);
      }
      else{
      	$shape=$layer->getShape(-1,$shapeindex);
      }
      $FlurListe['Flur'][]=$shape->values['FLUR'];
      $FlurListe['FlurID'][]=$shape->values['FLUR_ID'];
    }
    return $FlurListe;
  }

  function getDataSourceName() {
    # Abfragen des Namens der Shapedatei für Fluren
    $sql ='SELECT Data FROM layer WHERE Name="'.$this->LayerName.'"';
    $this->debug->write("<p>kataster.php Flur->getDataSourceName Abfragen des Shapefilenamen für die Fluren:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    return $rs['Data'];
  }

  function getMER($layer) {
    # diese Funktion liefert die Koordinaten des kleinsten einschließenden Rechtecks
    # Minimum Enclosing Rectangle der Flur aus dem übergebenen MapObjekt
    @$layer->queryByAttributes('FLUR_ID',$this->GemkgID.$this->FlurID,0);
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
      return $shape->bounds;
    }
  }

  function updateFluren() {
    return 'updateFluren';
  }

} # ende der Klasse flur

#-----------------------------------------------------------------------------------------------------------------
#############
#-> Adresse #
#############
class adresse {
  var $GemeindeSchl;
  var $StrassenSchl;
  var $HausNr;
  var $dbConn;
  var $debug;
  var $database;

  ###################### Liste der Funktionen ####################################
  #
  # function adresse($GemeindeSchl,$StrassenSchl,$HausNr) - Construktor
  # function setDBConn($dbConn)
  # function getGebaeude()
  # function getQuelle()
  # function getFlurstKennzListe()
  # function updateAdressTable()
  # function getStrassenListe($GemID,$AdressenListeByExtent,$order)
  # function getAdressenListeByFlst($FlstListe,$order)
  # function getAdressenListeByExtent($extent)
  # function getHausNrListe($GemID,$StrID,$HausNr,$order)
  # function getStrIDfromName($GemID,$StrName)
  #
  ################################################################################

  function adresse($GemeindeSchl,$StrassenSchl,$HausNr,$database) {
    global $debug;
    $this->debug=$debug;
    $this->GemeindeSchl=$GemeindeSchl;
    $this->StrassenSchl=$StrassenSchl;
    $this->HausNr=$HausNr;
    $this->database=$database;
  }

  function setDBConn($dbConn) {
    $this->dbConn=$dbConn;
  }

  function getGebaeude() {
    $Gebaeude=new gebaeude($this);
    return $Gebaeude->getGebaeude();
  }

  function getQuelle() {
    $sql ='SELECT Quelle FROM tmp_Adressen WHERE Gemeinde='.$this->GemeindeSchl;
    $sql.=' AND Strasse="'.$this->StrassenSchl.'" AND HausNr="'.$this->HausNr.'"';
    $sql.=' ORDER BY Quelle DESC';
    $this->debug->write("<p>kataster.php Adresse->getQuelle Abfragen der Quelle:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    return $rs['Quelle'];
  }

  function getFlurstKennzListe() {
    # liefert FlurstKennz zur Adressangaben aus dem ALB Bestand
    if(ALKIS)$ret=$this->database->getFlurstKennzListeByGemSchlByStrSchlALKIS($this->GemeindeSchl,$this->StrassenSchl,$this->HausNr);
    else $ret=$this->database->getFlurstKennzListeByGemSchlByStrSchl($this->GemeindeSchl,$this->StrassenSchl,$this->HausNr);
    if ($ret[0]) {
      $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4);
      return 0;
    }
    else {
      return $ret[1];
    }
  }

  # Funktion aktualisiert die Tabelle in der die Adressen aus ALK und ALB zusammengefasst sind
  function updateAdressTable() {
    # übernommen nach mysql.php und postgres.php
    $this->database->updateTempAdressTable();
  }

  function getStrassenListe($GemID,$extent,$order) {
    # Funktion liefert eine Liste der Strassen innerhalb der GemID und ggf. des extent
    $PolygonWKTString=rectObj2WKTPolygon($extent);
    if(ALKIS)$StrassenListe=$this->database->getStrassenListeALKIS($GemID,'',$PolygonWKTString,$order);
    else $StrassenListe=$this->database->getStrassenListe($GemID,$PolygonWKTString,$order);
    # liefert Array mit Arrays mit StrID und Name zurück
    return $StrassenListe;
  }
  
  function getStrassenListeByGemkg($GemkgID,$extent,$order) {
    # Funktion liefert eine Liste der Strassen innerhalb der GemID und ggf. des extent
    $PolygonWKTString=rectObj2WKTPolygon($extent);
    if(ALKIS)$StrassenListe=$this->database->getStrassenListeALKIS('',$GemkgID,$PolygonWKTString,$order);
    else $StrassenListe=$this->database->getStrassenListeByGemkg($GemkgID,$PolygonWKTString,$order);
    # liefert Array mit Arrays mit StrID und Name zurück
    return $StrassenListe;
  }

  function getAdressenListeByFlst($FlstListe,$order) {
    # liefert eine Liste von Adressen bei gegebenen Flurstuecken
    # aus der Tabelle f_Adressen
    $sql ='SELECT DISTINCT Gemeinde,Strasse,HausNr FROM f_Adressen WHERE 1=1';
    # Einschränkung wenn Flurstücke in der Liste übergeben werden
    if ($FlstListe!=0 AND $FlstListe!='') {
      $sql.=' AND FlurstKennz IN ('.$FlstListe[0];
      for($i=1;$i<count($FlstListe);$i++) {
        $sql.=','.$FlstListe[$i];
      }
      $sql.=')';
    }
    # sortiert nach order wenn angegeben
    if ($order!=0 AND $order!='') {
      $sql.=' ORDER BY '.$order;
    }
    $this->debug->write("<p>kvwmap->getAdressenListeByFlst->Abfragen der Adressdaten für Flurstuecke:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while ($rs=mysql_fetch_array($query)) {
      $Liste['GemID'][]=$rs['Gemeinde'];
      $Liste['StrID'][]=$rs['Strasse'];
      $Liste['HausNr'][]=$rs['HausNr'];
    }
    return $Liste;
  }

  function getAdressenListeByExtent($extent) {
    # 2006-01-09
    # Abfragen der Strassen, die im aktuellen Ausschnitt zu sehen sind.
    # 1. Abfragen der Adressen von Gebäuden im Ausschnitt
    $Gebaeude=new gebaeude('');
    $GebaeudeAdressenListe=$Gebaeude->getGebaeudeListeByExtent($extent);
    #var_dump($GebaeudeAdressenListe);

    # 2. Abfragen der Flurstuecke, die im extent liegen
    $Flurstueck=new flurstueck('',$this->database);
    $FlurstListe=$Flurstueck->getFlstListeByExtent($extent);

    # 3. Abfragen der Adressen der Flurstücke im Ausschnitt
    $FlurstueckAdressenListe=$this->getAdressenListeByFlst($FlurstListe['FKZ'],'Gemeinde,Strasse,HausNr');

    # 4. Vereinen der GebaeudeAdressen mit den Flurstuecksadressen durch Abfrage auf tmp_adressen
    # übernommen nach mysql.php und postgres.php 2005-12-27 pk
    return $AdressenListeInExtent=$this->database->getAdressenListeByExtent($GebaeudeAdressenListe,$FlurstueckAdressenListe);
  }

  function getHausNrListe($GemID,$StrID,$HausNr,$extent,$order) {
    # 2006-01-11 pk
    # Funktion liefert die Hausnummern zu einer GemID, StrID Kombination
    # und bei Bedarf auch im angegebenen extent zurück
    # Wenn mysql ausgewählt wurde ist die Einschränkung über ein extent nicht berücksichtigt.
    $PolygonWKTString=rectObj2WKTPolygon($extent);
    if(ALKIS)$HausNrListe=$this->database->getHausNrListeALKIS($GemID,$StrID,$HausNr,$PolygonWKTString,$order);
    else $HausNrListe=$this->database->getHausNrListe($GemID,$StrID,$HausNr,$PolygonWKTString,$order);
    # liefert ein Array mit HausNr und Nr_Quelle jeweils mit einem Array für die Listen zurück
    return $HausNrListe;
  }

  function getStrIDfromName($GemID,$StrName) {
    # ermitteln ob es sich bei dem StrNamen um einen gültigen Strassennamen der Gemeinde GemID handelt
    # Abfragen und die entsprechende StrID zurückliefern
    $ret=$this->database->getStrIDByName($GemID,$StrName);
    if ($ret[0]==0 AND count($ret[1])>0) {
      # liefert die erste gefundene Strasse zurück
      return $ret[1][0]['Strasse'];
    }
    else {
      return 0;
    }
  }
}

#-----------------------------------------------------------------------------------------------------------------
#####################
#-> Klasse Gebaeude #
#####################

class gebaeude {
  var $Adresse;
  var $debug;

  ###################### Liste der Funktionen ####################################
  #
  # function gebaeude($Adresse) - Construktor
  # function getTableDef()
  # function getColNames()
  # function getGebaeude()
  # function getRectByGebaeudeListe($IDlist,$layer)
  # function getGebaeudeListeByExtent($extent)
  # function getDataSourceName()
  #
  ################################################################################


  function gebaeude($Adresse) {
    global $debug; $this->debug=$debug;
    $this->Adresse=$Adresse;
    $this->LayerName=LAYERNAME_GEBAEUDE;
  }

  function getTableDef() {
    $def = array(
      array("OBJGR","N",6,0),
      array("OBJID","C",8),
      array("FOLIE","N",6,0),
      array("OBJART","N",6,0),
      array("TEXTART","C",255),
      array("INFOART","N",6,0),
      array("INFOTEXT","C",21),
      array("AKTUAL","N",6,0),
      array("QUELLE","N",6,0),
      array("ID","N",11,0),
      array("GEMEINDE","N",8,0),
      array("STRKEY","C",5),
      array("HAUSNR","C",8)
    );
    $this->tabdef=$def;
    return $def;
  }

  function getColNames() {
    for ($i=0;$i<count($this->tabdef);$i++) {
      $names[$i]=$this->tabdef[$i][0];
    }
    $this->colnames=$names;
    return $names;
  }

  function getALKGebaeude() {
    $gebaeude=$this->database->getGebaeude();
  }

  # ermittelt die ID für das Gebäude welche sowohl im Shapefile Gebaeude.dbf als auch
  # in der Tabelle ALK_Gebaeude steht. Die Abfrage erfolgt in der Datenbank
  function getGebaeude() {
    if ($this->Adresse->GemeindeSchl=='' OR $this->Adresse->StrassenSchl=='') { return 0; }
    $sql ='SELECT ID,Gemeinde AS GemeindeSchl,STRKEY AS StrassenSchl,HAUSNR AS HausNr';
    $sql.=' FROM ALK_Gebaeude WHERE Gemeinde = '.$this->Adresse->GemeindeSchl;
    $sql.=' AND STRKEY = "'.$this->Adresse->StrassenSchl.'" AND HAUSNR = "'.$this->Adresse->HausNr.'"';
    $query=mysql_query($sql);
    if ($this->debug) {
      $this->debug->write('<br>kataster.php gebaeude getGebaeude<br>Abfrage der Gebaeudedaten<br>'.$sql,4);
    }
    if ($query==0) {
      $this->debug->write('<br>Abbruch in Zeile:'.__LINE__.': '.$sql,4);
      echo '<br>Abbruch in Zeile:'.__LINE__.'<br>kataster.php gebaeude getGebaeude<br>Abfrage der Gebaeudedaten<br>'.$sql;
      return 0;
    }
    if (mysql_num_rows($query)==0) {
      $Gebaeude['ID'][0]=0;
    }
    else {
      while($rs=mysql_fetch_array($query)) {
        $Gebaeude['ID'][]=$rs['ID'];
        $Gebaeude['GemeindeSchl'][]=$rs['GemeindeSchl'];
        $Gebaeude['StrassenSchl'][]=$rs['StrassenSchl'];
        $Gebaeude['HausNr'][]=$rs['HausNr'];
      }
    }
    return $Gebaeude;
  }

  function getRectByGebaeudeListe($IDlist,$layer) {
    $anzGeb=count($IDlist);
    $minx=9999999;
    $miny=9999999;
    $maxx=0;
    $maxy=0;
    for ($i=0;$i<$anzGeb;$i++) {
      @$layer->queryByAttributes('ID',$IDlist[$i],0);
      $result=$layer->getResult(0);
      if ($layer->getNumResults()>0) {
        $numResults+=$layer->getNumResults();
        $layer->open();
        if(MAPSERVERVERSION > 500){
        	$shape=$layer->getFeature($result->shapeindex,-1);
        }
        else{
        	$shape=$layer->getShape(-1,$result->shapeindex);
        }
        $bounds=$shape->bounds;
        if ($minx>$bounds->minx) { $minx=$bounds->minx; }
        if ($miny>$bounds->miny) { $miny=$bounds->miny; }
        if ($maxx<$bounds->maxx) { $maxx=$bounds->maxx; }
        if ($maxy<$bounds->maxy) { $maxy=$bounds->maxy; }
      }
    }
    if ($numResults==0) {
      return 0;
    }
    else {
      $bounds->setextent($minx,$miny,$maxx,$maxy);
      return $bounds;
    }
  }

  function getGebaeudeListeByExtent($extent) {
    # Abfragen der Gebäude, die im aktuellen Ausschnitt zu sehen sind.
    # Hier könnte später eine Unterteilung in die Abfrage der Gebäude im Kartenausschnitt aus
    # Shape-Datei oder
    # postgis-Datenbank erfolgen. Je nachdem, wo die Gebäude befinden.
    $map=ms_newMapObj('');
    $layer=ms_newLayerObj($map);
    $layer->set('data',SHAPEPATH.$this->getDataSourceName());
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->queryByRect($extent);
    $layer->open();
    $anzResult=$layer->getNumResults();
    for ($i=0;$i<$anzResult;$i++) {
      $result=$layer->getResult($i);
      $shapeindex=$result->shapeindex;
      if(MAPSERVERVERSION > 500){
      	$shape=$layer->getFeature($shapeindex,-1);
      }
      else{
      	$shape=$layer->getShape(-1,$shapeindex);
      }
      $Liste[$i]['GemID']=$shape->values['GEMEINDE'];
      $Liste[$i]['StrID']=$shape->values['STRKEY'];
      $Liste[$i]['HausNr']=$shape->values['HAUSNR'];
    }
    return $Liste;
  }

  function getDataSourceName() {
    # Abfragen des Namens der Shapedatei für Gebaeude
    $sql ='SELECT Data FROM layer WHERE Name="Gebaeude"';
    $this->debug->write("<p>kataster.php Gebaeude getDataSourceName Abfragen des Shapefilenamen für Gebaeude:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    $this->datasourcename=$rs['Data'];
    return $rs['Data'];
  }
}

#-----------------------------------------------------------------------------------------------------------------

####################
#-> Klasse Nutzung #
####################

class nutzung {
  var $debug;

  ###################### Liste der Funktionen ####################################
  #
  # function nutzung()  - Construktor
  # function getTableDef()
  # function getColNames()
  # function getDataSourceName()
  #
  ################################################################################


  function nutzung() {
    global $debug; $this->debug=$debug;
    $this->LayerName=LAYERNAME_NUTZUNGEN; # geändert 2005-12-15 pk
  }

  function getTableDef() {
    $def = array(
      array("OBJGR","N",6,0),
      array("OBJID","C",8),
      array("FOLIE","N",6,0),
      array("OBJART","N",6,0),
      array("TEXTART","C",255),
      array("INFOART","N",6,0),
      array("INFOTEXT","C",21),
      array("AKTUAL","N",6,0),
      array("QUELLE","N",6,0),
      array("ID","N",11,0)
    );
    $this->tabdef=$def;
    return $def;
  }

  function getColNames() {
    for ($i=0;$i<count($this->tabdef);$i++) {
      $names[$i]=$this->tabdef[$i][0];
    }
    $this->colnames=$names;
    return $names;
  }

  function getDataSourceName() {
    # Abfragen des Namens der Shapedatei für Gebaeude
    $sql ='SELECT Data FROM layer WHERE Name="'.$this->LayerName.'"';
    $this->debug->write("<p>kataster.php Nutzung getDataSourceName Abfragen des Shapefilenamen für Nutzungsarten:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    $this->datasourcename=$rs['Data'];
    return $rs['Data'];
  }
}

#-----------------------------------------------------------------------------------------------------------------

##########################
#-> Klasse Ausgestaltung #
##########################

class ausgestaltung {
  var $debug;

  ###################### Liste der Funktionen ####################################
  #
  # function ausgestaltung()  - Construktor
  # function getTableDef()
  # function getColNames()
  # function getDataSourceName()
  #
  ################################################################################

  function ausgestaltung() {
    global $debug; $this->debug=$debug;
    $this->LayerName=LAYERNAME_AUSGESTALTUNGEN;
  }

  function getTableDef() {
    $def = array(
      array("OBJGR","N",6,0),
      array("OBJID","C",8),
      array("FOLIE","N",6,0),
      array("OBJART","N",6,0),
      array("INFOART","N",6,0),
      array("INFOTEXT","C",21),
      array("KURZTEXT","C",255),
      array("ARTGEO","N",6,0),
      array("DARST","N",6,0),
      array("AKTUAL","N",6,0),
      array("QUELLE","N",6,0),
      array("ID","N",11,0)
    );
    $this->tabdef=$def;
    return $def;
  }

  function getColNames() {
    for ($i=0;$i<count($this->tabdef);$i++) {
      $names[$i]=$this->tabdef[$i][0];
    }
    $this->colnames=$names;
    return $names;
  }

  function getDataSourceName() {
    # Abfragen des Namens der Shapedatei für Gebaeude
    $sql ='SELECT Data FROM layer WHERE Name="'.$this->LayerName.'"';
    $this->debug->write("<p>kataster.php Ausgestaltung getDataSourceName Abfragen des Shapefilenamen für die Ausgestaltung:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    $this->datasourcename=$rs['Data'];
    return $rs['Data'];
  }
}

#-----------------------------------------------------------------------------------------------------------------

######################
#-> Klasse Finanzamt #
######################

class finanzamt {
  var $FinanzamtSchl;
  var $FinanzamtName;

  ###################### Liste der Funktionen ####################################
  #
  # function finanzamt($FinanzamtSchl)  - Construktor
  # function getFinanzamtName()
  #
  ################################################################################

  function finanzamt($FinanzamtSchl) {
    $this->FinanzamtSchl=$FinanzamtSchl;
    $this->FinanzamtName=$this->getFinanzamtName();
  }

  function getFinanzamtName() {
    $sql = 'SELECT Name AS FinanzamtName FROM v_Finanzaemter WHERE Finanzamt ='.$this->FinanzamtSchl;
    $query=mysql_query($sql);
    $rs=mysql_fetch_array($query);
    return $rs['FinanzamtName'];
  }
}

#-----------------------------------------------------------------------------------------------------------------
#####################
#-> Klasse Forstamt #
#####################

class forstamt {
  var $Schluessel;
  var $Name;

  ###################### Liste der Funktionen ####################################
  #
  # function forstamt($Schluessel)  - Construktor
  # function getName()
  #
  ################################################################################

  function forstamt($Schluessel) {
    global $debug;
    $this->debug=$debug;
    $this->Schluessel=$Schluessel;
    $this->Name=$this->getName();
  }

  function getName() {
    if ($Schluessel>0) {
      $sql = 'SELECT Name FROM v_Forstaemter WHERE Forstamt ='.$this->ForstamtSchl;
      $this->debug->write("<p>kataster.php Forstamt->getName:<br>".$sql,4);
      $query=mysql_query($sql);
      if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
      $rs=mysql_fetch_array($query);
      return $rs['Name'];
    }
    else {
      return 'keine Angabe';
    }
  }
}

#-----------------------------------------------------------------------------------------------------------------
################
# Klasse Kreis #
################

class kreis {
  var $KreisSchl;
  var $KreisName;
  var $database;

  ###################### Liste der Funktionen ####################################
  #
  # function kreis($KreisSchl)  - Construktor
  # function getKreisName()
  # function getDataSourceName()
  # function getTableDef()
  # function getColNames()
  # function updateKreise()
  #
  ################################################################################

  function kreis($KreisSchl,$database) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
    $this->KreisSchl=$KreisSchl;
  }

  function getKreisName() {
    $this->debug->write("<p>kataster.php kreis getKreisName Abfragen des Namens des Kreises:",4);
    $ret=$this->database->getKreisName($this->KreisSchl);
    return $ret[1];
  }

  function getDataSourceName() {
    # Abfragen des Namens der Shapedatei für Kreise
    $sql ='SELECT Data FROM layer WHERE Name="Landkreis"';
    $this->debug->write("<p>kataster.php kreis getDataSourceName Abfragen des Shapefilenamen für Kreise:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    $this->datasourcename=$rs['Data'];
    return $rs['Data'];
  }

  function getTableDef() {
    $def = array(
      array("AREA","N",16,0),
      array("PERIMETER","N",16,0),
      array("KREIS_","N",11,0),
      array("KREIS_ID","N",11,0),
      array("KREIS","C",50),
      array("ID","N",11,0)
    );

    $this->tabdef=$def;
    return $def;
  }

  function getColNames() {
    for ($i=0;$i<count($this->tabdef);$i++) {
      $names[$i]=$this->tabdef[$i][0];
    }
    $this->colnames=$names;
    return $names;
  }

  function updateKreise() {
    $filename=SHAPEPATH.'temp/'.$this->getDataSourceName();
    # Für die Kreisdaten testen ob es eine shp, dbf, und shx gibt
    $msg = '<b>Aktualisieren der Kreisgrenzen:</b>';
    if (!is_file($filename.'.shp')) {
      $errmsg='<br>'.$filename.'.shp';
    }
    if (!is_file($filename.'.shx')) {
      $errmsg.='<br>'.$filename.'.shx';
    }
    if (!is_file($filename.'.dbf')) {
      $errmsg.='<br>'.$filename.'.dbf';
    }
    if ($errmsg!='') {
      $msg.='<br>Zur Fortführung der Kreisgrenzen fehlen folgende Dateien:';
      $msg.=$errmsg;
    }
    else {
      $msg.='<br>Temporäre Dateien zum aktualisieren gefunden.';
      # lesen der soll-Tabellendefinition
      $tabdef=$this->getTableDef();
      $colnames=$this->getColNames();
      $dbfin=dbase_open($filename.'.dbf',0);
      $dbfout=dbase_create($filename.'_neu.dbf',$tabdef);
      if ($dbfin==0 OR $dbfout==0) {
        $msg.='<b><br>Fehler beim Öffnen der dbf-Tabelle für die Landkreise!</b>';
      }
      else {
        $numfieldsin=dbase_numfields($dbfin);
        $msg.='<br>Schreiben der dbf-Tabelle...';
        echo "Lese Landkreise...";
        # Leeren des bisherigen Bestandes an Landkreisen in der Datenbank
        $ret=$this->database->truncateAdmKreise();
        if ($ret[0] AND DBWRITE) {
          $errmsg ='<br>Fehler beim Löschen der Kreisgrenzen in der Datenbank.';
          $errmsg.='<br>'.$ret[1];
        }
        else {
          for ($i=1;$i<=dbase_numrecords($dbfin);$i++) {
            $rsin=dbase_get_record_with_names($dbfin,$i);
            echo "in".$rsin['KREIS'];
            if ($i-1==$i10) {
              if ($i>1) { echo "<br>".($i-1)." Zeilen eingelesen."; }
              $i10+=10;
            }
            echo "<br>";
            for ($numfieldsout=0;$numfieldsout<$numfieldsin;$numfieldsout++) {
              $rsout[$numfieldsout]=trim(ANSII2DOS($rsin[$numfieldsout]));
            }
            # Auffüllen des records mit den zusätzlichen Spalten
            $rsout[$numfieldsout++]=$i; # ID
            if (!dbase_add_record($dbfout,$rsout)) {
              $msg.='<br><b>Fehler beim Umschreiben der dbf-Tabelle in Zeile '.$i.'!</b>';
            }
            # Eintragen der Datenzeile in die Datenbanktabelle
            $ret=$this->database->insertAdmKreis($colnames,$rsout);
            if ($ret[0] AND DBWRITE) {
              $msg.='<br>Fehler beim Einfügen eines Landkreises in die Datenbank.';
              $msg.='<br>'.$ret[1];
            }
          }
        } # end of lesen und überschreiben der Landkreisdaten
      }
      $msg.='<br>...fertig<br>'.($i-1).' Zeilen in neue dbf-Tabelle geschrieben.';
      dbase_close ($dbfin);
      dbase_close($dbfout);
      # kopieren der temporären Tabellen ins Datenverzeichnis
      $source=$filename;
      $target=SHAPEPATH.$flst->datasourcename;
      if (!copy($source.'.shp',$target.'.shp')) {
         $errmsg='<br>'.$filename.'.shp\n';
      }
      if (!copy($source.'.shx',$target.'.shx')) {
         $errmsg.='<br>'.$filename.'.shx\n';
      }
      if (!copy($source.'_neu.dbf',$target.'.dbf')) {
         $errmsg.='<br>'.$filename.'.dbf\n';
      }
      if ($errmsg!='') {
        $msg.='<br>Fehler beim Überschreiben der vorherigen Landkreisgrenzen, bei folgenden Dateien:';
        $msg.=$errmsg;
        $msg.='<br>Achtung!!! Die Fortführung war nicht erfolgreich!';
      }
      else {
        $msg.='<br>Alter Datensatz überschrieben.';
      }
    }
    $this->anzKreise=($i-1);
    return $msg;
  }
}

#-----------------------------------------------------------------------------------------------------------------
#####################
#-> Klasse Gemeinde #
#####################

class gemeinde {
  var $GemeindeSchl;
  var $GemeindeName;
  var $KreisSchl;
  var $debug;
  var $database;

  ###################### Liste der Funktionen ####################################
  #
  # function gemeinde($GemeindeSchl)  - Construktor
  # function getTableDef()
  # function getColNames()
  # function getKreisSchl()
  # function getGemeindeName()
  # function getGemeindeListe($Gemeinden,$order)
  # function getGemeindeListeByExtent($extent)
  # function getGemeindeListeByPermission($GemListe)
  # function getDataSourceName()
  # function getMER($layer)
  # function updateGemeinden()
  #
  ################################################################################


  function gemeinde($GemeindeSchl,$database) {
    global $debug; $this->debug=$debug;
    $this->GemeindeSchl=$GemeindeSchl;
    $this->KreisSchl=$this->getKreisSchl();
    $this->LayerName=LAYERNAME_GEMEINDEN;
    $this->database=$database;
  }

  function getTableDef() {
    $def = array(
      array("GEMEINDE_L","N",8,0),
      array("COUNT","N",3,0),
      array("AMT_LANG_I","N",4,0),
      array("AMT_ID","N",2,0),
      array("GEMEINDE_I","N",3,0),
      array("GEMEINDE","C",255),
      array("POLYNAME","C",255),
      array("ID","N",11,0)
    );
    $this->tabdef=$def;
    return $def;
  }

  function getColNames() {
    for ($i=0;$i<count($this->tabdef);$i++) {
      $names[$i]=$this->tabdef[$i][0];
    }
    $this->colnames=$names;
    return $names;
  }

  function getKreisSchl() {
    return substr($this->GemeindeSchl,0,5);
  }

  function getGemeindeName($Gemeinde) {
    $ret=$this->database->getGemeindeName($Gemeinde);
    if ($ret[0]) {
      $Name='Gemeindename nicht gefunden.<br>'.$ret[1];
    }
    else {
      $Name=$ret[1]['name'];
    }
    return $Name;
  }

  function getGemeindeListe($Gemeinden,$order) {
    	$GemeindeListe=$this->database->getGemeindeListeByKreisGemeinden($Gemeinden,$order);
    return $GemeindeListe;
  }
  
  function getGemeindeListeALKIS($Gemeinden,$order) {
  	$GemeindeListe=$this->database->getGemeindeListeByKreisGemeindenALKIS($Gemeinden,$order);
    return $GemeindeListe;
  }

  function getGemeindeListeByExtent($extent) {
    # Hier könnte später eine Unterteilung in die Abfrage der Gemeinden im Kartenausschnitt aus
    # Shape-Datei oder
    # postgis-Datenbank erfolgen. Je nachdem, wo die Gemeindegrenzen befinden.

    # Abfragen der Gemeinen, die im aktuellen Ausschnitt zu sehen sind.
    $map=ms_newMapObj('');
    $layer=ms_newLayerObj($map);
    $layer->set('data',SHAPEPATH.$this->getDataSourceName());
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->queryByRect($extent);
    $layer->open();
    $anzResult=$layer->getNumResults();
    for ($i=0;$i<$anzResult;$i++) {
      $result=$layer->getResult($i);
      $shapeindex=$result->shapeindex;
      if(MAPSERVERVERSION > 500){
      	$shape=$layer->getFeature($shapeindex,-1);
      }
      else{
      	$shape=$layer->getShape(-1,$shapeindex);
      }
      $GemeindeListe['ID'][]=$shape->values['GEMEINDE_L'];
      $GemeindeListe['Name'][]=$shape->values['GEMEINDE'];
    }
    return $GemeindeListe;
  }

  function getGemeindeListeByPermission($GemListe) {
    # Abfragen des Filters für den Layer Gemeinde, Welche Gemeinden sind erlaubt
    $sql ='SELECT Filter FROM used_layer AS ul,layer AS l WHERE ul.Layer_ID=l.Layer_ID';
    $sql.=' AND l.Name="Gemeinde" AND ul.Stelle_ID='.$Stelle->id;
    $this->debug->write("<p>kataster.php Gemeinde->getGemeindeListeByExtent Abfragen des Filters zum GemeindeLayer:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    if ($rs['Filter']!='') { # Filter wird nur ausgewertet, wenn er gesetzt ist
      $IDallow=decompressListe($rs['Filter']);
    }
    else { # ansonsten wird $IDallow[0] auf 'all' gesetzt
      $IDallow[0]='all';
    }

    # Abfragen der sortierten und zusammengefassten Gemeinden
    $sql ='SELECT DISTINCT adr.Gemeinde AS GemID,adr.GemeindeName AS Name';
    $sql.=' FROM tmp_Adressen AS adr,'.$GemeindeShapeFileName.' AS Gem';
    $sql.=' WHERE adr.Gemeinde=Gem.GEMEINDE_L AND Gem.ID IN ('.$IDinRect['ID'][0];
    for ($i=1;$i<count($IDinRect['ID']);$i++) {
      $sql.=','.$IDinRect['ID'][$i];
    }
    $sql.=')';
    if ($IDallow[0]!='all') {
      $sql.=' AND Gem.ID IN ('.$IDallow[0];
      for ($i=1;$i<count($IDallow);$i++) {
        $sql.=','.$IDallow[$i];
      }
      $sql.=')';
    }
    if ($order!='') {
      $sql.=' ORDER BY '.$order;
    }
    $this->debug->write("<p>kataster.php Gemeinde->getGemeindeListeByExtent Abfragen der Geimeinden aus dem Kartenausschnitt:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while ($rs=mysql_fetch_array($query)) {
      $GemeindeListe['GemID'][]=$rs['GemID'];
      $GemeindeListe['Name'][]=$rs['Name'];
    }
    return $GemeindeListe;
  }

  function getDataSourceName() {
    # Abfragen des Namens der Shapedatei für Gemeinden
    $sql ='SELECT Data FROM layer WHERE Name="Gemeinde"';
    $this->debug->write("<p>kataster.php Gemeinde getDataSourceName Abfragen des Shapefilenamen für Geimeinden:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    return $rs['Data'];
  }

  function getMER($layer) {
    @$layer->queryByAttributes('GEMEINDE_L',$this->GemeindeSchl,0);
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
      return $shape->bounds;
    }
  }

  function updateGemeinden() {
    $filename=SHAPEPATH.'temp/'.$this->getDataSourceName();
    # Für die Gemeinde testen ob es eine shp, dbf, und shx gibt
    $msg = '<b>Aktualisieren der Gemeindegrenzen:</b>';
    if (!is_file($filename.'.shp')) {
      $errmsg='<br>'.$filename.'.shp';
    }
    if (!is_file($filename.'.shx')) {
      $errmsg.='<br>'.$filename.'.shx';
    }
    if (!is_file($filename.'.dbf')) {
      $errmsg.='<br>'.$filename.'.dbf';
    }
    if ($errmsg!='') {
      $msg.='<br>Zur Fortführung der Gemeindegrenzen fehlen folgende Dateien:';
      $msg.=$errmsg;
    }
    else {
      $msg.='<br>Temporäre Dateien zum aktualisieren gefunden.';
      # lesen der soll-Tabellendefinition
      $tabdef=$this->getTableDef();
      $colnames=$this->getColNames();

      $dbfin=dbase_open($filename.'.dbf',0);
      $dbfout=dbase_create($filename.'_neu.dbf',$tabdef);
      if ($dbfin==0 OR $dbfout==0) {
        $msg.='<b><br>Fehler beim Öffnen der dbf-Tabelle für die Gemeinden!</b>';
      }
      else {
        $numfieldsin=dbase_numfields($dbfin);
        $msg.='<br>Schreiben der dbf-Tabelle...';
        echo "Lese Landkreise...";
        # Leeren des bisherigen Bestandes an Gemeinden in der Datenbank
        $ret=$this->database->truncateAdmKreise();
        if ($ret[0] AND DBWRITE) {
          $errmsg ='<br>Fehler beim Löschen der Gemeindegrenzen in der Datenbank.';
          $errmsg.='<br>'.$ret[1];
        }
        else {
          for ($i=1;$i<=dbase_numrecords($dbfin);$i++) {
            $rsin=dbase_get_record($dbfin,$i);
            if ($i-1==$i10) {
              if ($i>1) { echo "<br>".($i-1)." Zeilen eingelesen."; }
              $i10+=10;
            }
            for ($numfieldsout=0;$numfieldsout<$numfieldsin;$numfieldsout++) {
              $rsout[$numfieldsout]=trim(ANSII2DOS($rsin[$numfieldsout]));
            }
            # Auffüllen des records mit den zusätzlichen Spalten
            $rsout[$numfieldsout++]=$i; # ID
            if (!dbase_add_record($dbfout,$rsout)) {
              $msg.='<br><b>Fehler beim Umschreiben der dbf-Tabelle in Zeile '.$i.'!</b>';
            }
            # Eintragen der Datenzeile in die Datenbanktabelle
            $ret=$this->database->insertAdmKreis($colnames,$rsout);
            if ($ret[0] AND DBWRITE) {
              $msg.='<br>Fehler beim Einfügen einer Gemeinde in die Datenbank.';
              $msg.='<br>'.$ret[1];
            }
          }
        } # end of lesen und überschreiben der Landkreisdaten
      }
      $msg.='<br>...fertig<br>'.($i-1).' Zeilen in neue dbf-Tabelle geschrieben.';
      dbase_close ($dbfin);
      dbase_close($dbfout);
      # kopieren der temporären Tabellen ins Datenverzeichnis
      $source=$filename;
      $target=SHAPEPATH.$flst->datasourcename;
      if (!copy($source.'.shp',$target.'.shp')) {
         $errmsg='<br>'.$filename.'.shp\n';
      }
      if (!copy($source.'.shx',$target.'.shx')) {
         $errmsg.='<br>'.$filename.'.shx\n';
      }
      if (!copy($source.'_neu.dbf',$target.'.dbf')) {
         $errmsg.='<br>'.$filename.'.dbf\n';
      }
      if ($errmsg!='') {
        $msg.='<br>Fehler beim Überschreiben der vorherigen Gemeindegrenzen, bei folgenden Dateien:';
        $msg.=$errmsg;
        $msg.='<br>Achtung!!! Die Fortführung war nicht erfolgreich!';
      }
      else {
        $msg.='<br>Alter Datensatz überschrieben.';
      }
    }
    $this->anzGemeinden=($i-1);
    return $msg;
  }
} # ende der klasse gemeinde

#-----------------------------------------------------------------------------------------------------------------
######################
# Klasse Amtsgericht #
######################
class amtsgericht {
  var $id;
  var $debug;

  ###################### Liste der Funktionen ####################################
  #
  # function function amtsgericht($id)  - Construktor
  # function getName()
  #
  ################################################################################


  function amtsgericht($id) {
    global $debug;
    $this->debug=$debug;
    $this->id=$id;
    $this->debug=$debug;
    $this->Name=$this->getName();
  }

  function getName() {
    if ($this->id=="") { return 0; }
    $sql = 'SELECT Name AS AmtsgerichtName FROM v_Amtsgerichte WHERE Amtsgericht ="'.$this->id.'"';
    $this->debug->write("<p>kataster.php->amtsgericht->getName Abfragen des Amtsgerichtes:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in kataster.php Zeile: ".__LINE__."<br>".$sql; return 0; }
    if (mysql_num_rows($query)==0) { return 0; }
    else { $rs=mysql_fetch_array($query); return $rs['AmtsgerichtName']; }
  }
}

#-----------------------------------------------------------------------------------------------------------------
####################
# Klasse Gemarkung #
####################
class gemarkung {
  var $GemkgSchl;
  var $GemkgName;
  var $GemeindeSchl;
  var $AmtsgerichtSchl;
  var $debug;
  var $database;

  ###################### Liste der Funktionen ####################################
  #
  # function gemarkung($GemkgSchl)  - Construktor
  # function getTableDef()
  # function getAmtsgericht()
  # function getGemkgName()
  # function getGemeindeSchl()
  # function getGemkgSchlFromName($GemID,$Name)
  # function getGemarkungListe($GemID,$GemkgID,$order)
  # function getGemarkungListeByExtent($extent)
  # function getDataSourceName()
  # function getMER($layer)
  # function updateGemarkungen()
  #
  ################################################################################

  function gemarkung($GemkgSchl,$database) {
    global $debug;
    $this->debug=$debug;
    $this->GemkgSchl=$GemkgSchl;
    $this->database=$database;
    $this->LayerName=LAYERNAME_GEMARKUNGEN;
  }

  function getTableDef() {
    $def = array(
      array("GEMEINDE_L","N",8,0),
      array("GEMEINDE_I","N",3,0),
      array("GEMARKUNG_","N",6,0),
      array("COUNT","N",3,0),
      array("GEMARK_ID","N",4,0),
      array("GEMARKUNG", "C",255),
      array("ID","N",11,0)
    );
    return $def;
  }

  function getAmtsgericht() {
    if ($this->GemkgSchl=="") { return 0; }
    $ret=$this->database->getAmtsgericht($this->GemkgSchl);
    return $ret[1];
  }
  
  function getGemkgName() {
    $ret=$this->database->getGemarkungName($this->GemkgSchl);
    if ($ret[0] AND DBWRITE) {
      return $ret[1];
    }
    return $ret[1];
    #$query=mysql_query($sql);  if ($query==0) { return 0; }
    #$rs=mysql_fetch_array($query);
    #return $rs['GemkgName'];
  }

  function getGemeindeSchl() {
    $sql = 'SELECT Gemeinde AS GemeindeSchl FROM v_Gemarkungen WHERE GemkgSchl ='.$this->GemkgSchl;
    $query=mysql_query($sql);  if ($query==0) { return 0; }
    $rs=mysql_fetch_array($query);
    return $rs['GemeindeSchl'];
  }

  function getGemarkungListe($GemID,$GemkgID,$order) {
    # Abfragen der Gemarkungen mit seinen GemeindeNamen
    $Liste=$this->database->getGemeindeListeByGemIDByGemkgSchl($GemID,$GemkgID,$order);
    return $Liste;
  }
  
  function getGemarkungListeALKIS($GemID,$GemkgID,$order) {
    # Abfragen der Gemarkungen mit seinen GemeindeNamen
    $Liste=$this->database->getGemeindeListeByGemIDByGemkgSchlALKIS($GemID,$GemkgID,$order);
    return $Liste;
  }

  function getGemarkungListeByExtent($extent) {
    # Abfragen der Gemarkungen, die im aktuellen Ausschnitt zu sehen sind.
    $map=ms_newMapObj('');
    $layer=ms_newLayerObj($map);
    $layer->set('data',SHAPEPATH.$this->getDataSourceName());
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->queryByRect($extent);
    $layer->open();
    $anzResult=$layer->getNumResults();
    for ($i=0;$i<$anzResult;$i++) {
      $result=$layer->getResult($i);
      $shapeindex=$result->shapeindex;
      if(MAPSERVERVERSION > 500){
      	$shape=$layer->getFeature($shapeindex,-1);
      }
      else{
      	$shape=$layer->getShape(-1,$shapeindex);
      }
      $GemkgListe['GemkgID'][]=$shape->values['GEMARKUNG_'];
      $GemeindeListe['Name'][]=$shape->values['GEMARKUNG'];
    }
    return $GemkgListe;
  }

  function getDataSourceName() {
    # Abfragen des Namens der Shapedatei für Gemeinden
    $sql ='SELECT Data FROM layer WHERE Name="'.$this->LayerName.'"';
    $this->debug->write("<p>kataster.php Gemarkung getDataSourceName Abfragen des Shapefilenamen für die Gemarkung:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    return $rs['Data'];
  }

  function getMER($layer) {
    # diese Funktion liefert die Koordinaten des kleinsten einschließenden Rechtecks
    # Minimum Enclosing Rectangle der Gemarkung aus dem übergebenen layer
    @$layer->queryByAttributes('GEMARKUNG_',$this->GemkgSchl,0);
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
      return $shape->bounds;
    }
  }

  function updateGemarkungen() {
    return 'updateGemarkungen';
  }
} # end of class Gemarkung

#-----------------------------------------------------------------------------------------------------------------
#################
#-> Eigentuemer #
################
class eigentuemer {
  var $Grundbuch;
  var $NamensNr;
  var $Name;
  var $debug;
  ###################### Liste der Funktionen ####################################
 #
 # function eigentuemer($Grundbuch,$NamensNr)  - Construktor
 # function getEigentuemerName()
 #
 ################################################################################

  function eigentuemer($Grundbuch,$NamensNr) {
    global $debug;
    $this->debug=$debug;
    $this->Grundbuch=$Grundbuch;
    $this->NamensNr=$NamensNr;
    /*$NrTeil=explode('.',$NamensNr);
    $this->Nr=$NrTeil[0];
    if ($NrTeil[1]!='') {
      $this->Nr.='.'.intval($NrTeil[1]);
    }
    */
    $this->Nr = $NamensNr;
  }

  function getEigentuemerName() {
    if ($this->NamensNr=="" OR $this->Grundbuch->Bezirk=="" OR $this->Grundbuch->Blatt=="") {
      $Name[0]="";
      return $Name;
    }
    $sql ='SELECT Name1,Name2,Name3,Name4 FROM g_Eigentümer AS eig,g_Namen AS nam';
    $sql.=' WHERE eig.lfd_Nr_Name=nam.lfd_Nr_Name AND eig.Bezirk = '.$this->Grundbuch->Bezirk.'"';
    $sql.=' AND eig.Blatt= "'.$this->Grundbuch->Blatt.'" AND eig.NamensNr = "'.$this->NamensNr.'"';
    if ($this->debug) { echo "<br>".$sql; }
    $query=mysql_query($sql);  if ($query==0) { return 0; }
    if (mysql_num_rows($query)==0) {
      $Name[0] = "";
      return $Name;
    }
    else {
      $rs=mysql_fetch_array($query);
      $Name[] = $rs['Name1'];
      if ($rs['Name2']!="") { $Name[] = $rs['Name2']; }
      if ($rs['Name3']!="") { $Name[] = $rs['Name3']; }
      if ($rs['Name4']!="") { $Name[] = $rs['Name4']; }
      return $Name;
    }
  }

  function getAdressaenderungen($name1, $name2, $name3, $name4) {
    $sql ="SELECT neu_name3, neu_name4, user_id, datum FROM alb_g_namen_temp";
    $sql.=" WHERE 1=1 ";
    if($name1 != ''){
    	$sql.= "AND name1 = '".$name1."' ";
    }
    if($name2 != ''){
    	$sql.= "AND name2 = '".$name2."' ";
    }
    if($name3 != ''){
    	$sql.= "AND name3 = '".$name3."' ";
    }
    if($name4 != ''){
    	$sql.= "AND name4 = '".$name4."' ";
    }
    #echo $sql;
    $query=pg_query($sql);
    if ($query==0){
    	return 0;
    }
    else{
      $rs=pg_fetch_array($query);
      return $rs;
    }
  }
}

#-----------------------------------------------------------------------------------------------------------------
######################
# Klasse Grundstueck #
######################
class grundstueck {
  var $Grundbuch;
  var $BVNR;
  var $Eigentuemer;
  var $debug;
  ###################### Liste der Funktionen ####################################
 #
 # function grundstueck($Grundbuch,$BVNR)  - Construktor
 # function getEigentuemer()
 #
 ################################################################################

  function grundstueck($Grundbuch,$BVNR) {
    global $debug;
    $this->Grundbuch=$Grundbuch;
    $this->BVNR=$BVNR;
    $this->debug=$debug;
  }
  function getEigentuemer() {
    if ($this->BVNR=="" OR $this->Grundbuch->Bezirk=="" OR $this->Grundbuch->Blatt=="") {
      $Eigentuemer[0] = new eigentuemer($this->$Grundbuch,"");
      return $Eigentuemer;
    }
    $sql ='SELECT NamensNr FROM g_Eigentümer WHERE Bezirk = "'.$this->Grundbuch->Bezirk.'"';
    $sql.=' AND Blatt= "'.$this->Grundbuch->Blatt.'" ORDER BY NamensNr';
    if ($this->debug) { echo "<br>".$sql; }
    $query=mysql_query($sql); if ($query==0) { return 0; }
    if (mysql_num_rows($query)==0) {
      $Eigentuemer[0] = new eigentuemer($this->Grundbuch,"");
      return $Eigentuemer;
    }
    else {
      while($rs=mysql_fetch_array($query)) {
        $Eigentuemer[] = new eigentuemer($this->Grundbuch,$rs['NamensNr']);
      }
      return $Eigentuemer;
    }
  }
}

#-----------------------------------------------------------------------------------------------------------------
####################
# Klasse Grundbuch #
####################
class grundbuch {
  var $Bezirk;
  var $Blatt;
  ###################### Liste der Funktionen ####################################
  #
  # function grundbuch($Bezirk,$Blatt,$debug) - Construktor
  #
  ################################################################################

  function grundbuch($Bezirk,$Blatt,$database) {
    global $debug;
    $this->debug=$debug;
    $this->Bezirk=$Bezirk;
    $this->Blatt=$Blatt;
    $this->database=$database;
  }

  function getBuchungen($flurstkennz,$bvnr,$erbaurechtshinweise,$keine_historischen) {
    if(ALKIS)$ret=$this->database->getBuchungenFromGrundbuchALKIS('',$this->Bezirk,$this->Blatt,$keine_historischen);
    else $ret=$this->database->getBuchungenFromGrundbuch('',$this->Bezirk,$this->Blatt,$keine_historischen);
    if ($ret[0]) {
      $ret[1]='Fehler bei der Datenbank abfrage<br>'.$ret[1];
    }
    else {
      # Zuordnen der Gemeinde und Gemarkungsnamen zu den Flurstücken.
      $buchungen=$ret[1];
      $anzBuchungen=count($buchungen);
      for($i=0;$i<$anzBuchungen;$i++) {
        if(ALKIS){
        	$buchungenret=$this->database->getGemarkungNameALKIS(substr($buchungen[$i]['flurstkennz'],0,6));
	        $buchungen[$i]['gemkgname']=$buchungenret[1];
	        $buchungen[$i]['flur']=intval(substr($buchungen[$i]['flurstkennz'],6,3));
	        $buchungen[$i]['zaehler']=intval(substr($buchungen[$i]['flurstkennz'],9,5));
	        $buchungen[$i]['nenner']=intval(substr($buchungen[$i]['flurstkennz'],14,6));
	        $buchungen[$i]['flurstuecksnr']=$buchungen[$i]['zaehler'];
	        if ($buchungen[$i]['nenner']>0) {
	          $buchungen[$i]['flurstuecksnr'].='/'.$buchungen[$i]['nenner'];
	        }
        }
        else{
        	$buchungenret=$this->database->getGemarkungName(substr($buchungen[$i]['flurstkennz'],0,6));
	        $buchungen[$i]['gemkgname']=$buchungenret[1];
	        $buchungen[$i]['flur']=intval(substr($buchungen[$i]['flurstkennz'],7,3));
	        $buchungen[$i]['zaehler']=intval(substr($buchungen[$i]['flurstkennz'],11,5));
	        $buchungen[$i]['nenner']=intval(substr($buchungen[$i]['flurstkennz'],17,3));
	        $buchungen[$i]['flurstuecksnr']=$buchungen[$i]['zaehler'];
	        if ($buchungen[$i]['nenner']>0) {
	          $buchungen[$i]['flurstuecksnr'].='/'.$buchungen[$i]['nenner'];
	        }
        }
      }
      $ret[1]=$buchungen;
    }
    return $ret;
  }

  function grundbuchblattSuchParameterPruefen() {
    if ($this->Bezirk=='' OR $this->Bezirk==0) {
      $errmsg.='<br>Die Nummer für Bezirk ist leer.';
    }
    if (strlen($this->Bezirk)<5) {
      $errmsg.='<br>Die Bezirksnummer ist keine 6 Zeichen lang.';
    }
    if ($this->Blatt=='' OR $this->Blatt==0) {
      $errmsg.='<br>Die Blattnummer ist leer.';
    }
    if ($errmsg!='') {
      $ret[0]=1; $ret[1]=$errmsg;
    }
    else {
      $ret[0]=0; $ret[1]='<br>Keine Fehler in den Angaben zu Bezirk und Blattnummer gefunden.';
    }
    return $ret;
  }

  function getGrundbuchbezirksliste(){
  	if(ALKIS)return $this->database->getGrundbuchbezirkslisteALKIS();
  	else return $this->database->getGrundbuchbezirksliste();
  }

  function getGrundbuchbezirkslisteByGemkgIDs($gemkg_ids){
  	if(ALKIS)return $this->database->getGrundbuchbezirkslisteByGemkgIDsALKIS($gemkg_ids);
  	else return $this->database->getGrundbuchbezirkslisteByGemkgIDs($gemkg_ids);
  }
  
  function getGrundbuchblattliste($bezirk){
  	if(ALKIS)return $this->database->getGrundbuchblattlisteALKIS($bezirk);
  	else return $this->database->getGrundbuchblattliste($bezirk);
  }
}

#-----------------------------------------------------------------------------------------------------------------
################
#-> Flurstueck #
################
class flurstueck {
  var $Amtsgericht;
  var $FlurstKennz;
  var $Infotext;
  var $Gemarkung;
  var $GemkgSchl;
  var $Finanzamt;
  var $Entstehung;
  var $Flaeche;
  var $Grundstuecke;
  var $debug;
  var $Zaehler;
  var $Nenner;
  var $Lagebezeichnung;
  var $Adresse;
  var $datadourcename;
  var $database;

  ###################### Liste der Funktionen ####################################
  #
  # function ALB_Form_Auswaehlen($art)
  # function FKZ_Format($format)
  # function flurstueck($FlurstKennz) - Construktor
  # function getAdresse()
  # function getAktualitaetsNr()
  # function getAmtsgericht()
  # function getBaulasten()
  # function getBuchungen($Bezirk,$Blatt,$keine_historischen)
  # function getColNames()
  # function getDataSourceName()
  # function getEigentuemerliste($Bezirk,$Blatt,$BVNR)
  # function getEntstehung()
  # function getFinanzamt()
  # function getFlaeche()
  # function getFlstListe($GemID,$GemkgID,$FlurID,$FlstKennz,$order)
  # function getFlstListeByExtent($rectObj)
  # function getFlurID()
  # function getFlurkarte()
  # function getFlstListeByExtent($rectObj)
  # function getFlurstByPoint($point)
  # function getFlurstByLfdNrName($name,$limitAnzahl)
  # function getForstamt()
  # function getFortfuehrung()
  # function getFreiText()
  # function getGemarkung($id)
  # function getGemkgSchl()
  # function getGrundbuchbezirk()
  # function getGrundstuecke()
  # function getHinweis()
  # function getKlassifizierung()
  # function getKreis()
  # function getLage()
  # function getNamen($name1,$name2,$name3,$name4,$bezirk,$gemkgschl,$limitAnzahl,$caseSensitive)
  # function getNutzung()
  # function getPruefKZ()
  # function getPruefzeichen()
  # function getTableDef()
  # function getVerfahren()
  # function getVorgaenger()
  # function is_FlurstKennz($Eingabe)
  # function is_FlurstNr($GemkgID,$FlurID,$Eingabe)
  # function is_FlurstZaehler($GemkgID,$FlurID,$Eingabe)
  # function readALB_Data($FlurstKennz)
  #
  ################################################################################

  function flurstueck($FlurstKennz,$database) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
    if ($FlurstKennz!='') {
      $this->FlurstKennz=$FlurstKennz;
      $this->GemkgSchl= $this->getGemkgSchl();
      $this->FlurID=$this->getFlurID();
    }
    $this->LayerName=LAYERNAME_FLURSTUECKE;
  }

  function getVerfahren() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getVerfahren Abfrage der Verfahrensdaten<br>".$sql,4);
    if(ALKIS){}		#ALKIS TODO
    else $ret=$this->database->getVerfahren($this->FlurstKennz);
    return $ret[1];
  }

  function getBaulasten() {
    if ($this->FlurstKennz=="") { return 0; }
    $Baulasten=array();
    $this->debug->write("<br>kataster.php->flurstueck->getBaulasten Abfrage der Baulasten zum Flurstück<br>",4);
    if(ALKIS){}		#ALKIS TODO
    else $ret=$this->database->getBaulasten($this->FlurstKennz);
    return $ret[1];
  }

	function getEMZfromALK() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getEMZfromALK Abfrage der Klassifizierungen zum Flurstück<br>".$sql,4);
    if(ALKIS){}		# ALKIS-TODO
    else $emz=$this->database->getEMZfromALK($this->FlurstKennz);
    return $emz;
  }

  function getKlassifizierung() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getKlassifizierung Abfrage der Klassifizierungen zum Flurstück<br>".$sql,4);
    if(ALKIS){}		# ALKIS-TODO
    else $ret=$this->database->getKlassifizierung($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $Klassifizierung=$ret[1];
    return $Klassifizierung;
  }

  function getFreiText() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getFreiText Abfrage der freien Texte zum Flurstück<br>",4);
    if(ALKIS){}		#ALKIS TODO
    else $ret=$this->database->getFreiText($this->FlurstKennz);
    return $ret[1];
  }

  function getBuchungen($Bezirk,$Blatt,$keine_historischen) {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getBuchungen Abfrage der Buchungen zum Flurstück auf dem Grundbuch<br>",4);
    #$ret=$this->database->getBuchungen($this->FlurstKennz);
    if(ALKIS)$ret=$this->database->getBuchungenFromGrundbuchALKIS($this->FlurstKennz,$Bezirk,$Blatt,$keine_historischen);
    else $ret=$this->database->getBuchungenFromGrundbuch($this->FlurstKennz,$Bezirk,$Blatt,$keine_historischen);
    return $ret[1];
  }

  function getGrundbuecher() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getGrundbuecher Abfrage der Angaben zum Grundbuch auf dem das Flurstück gebucht ist<br>",4);
    if(ALKIS)$ret=$this->database->getGrundbuecherALKIS($this->FlurstKennz);
    else $ret=$this->database->getGrundbuecher($this->FlurstKennz);
    return $ret[1];
  }

  function getHinweis() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getHinweis Abfrage des Hinweises zum Flurstück<br>",4);
    if(ALKIS){}		#ALKIS TODO
    else $ret=$this->database->getHinweise($this->FlurstKennz);
    return $ret[1];
  }

  function getTableDef() {
/*
    $def = array(
      array("AOBJID","C",7),
      array("OBJART","C",4),
      array("AKT","C",2),
      array("BL","C",2),
      array("GM","C",4),
      array("FL","C",3),
      array("FZ","C",5),
      array("FN","C",3),
      array("FF","C",2),
      array("INFOTEXT","C",21),
      array("ID","N",11,0),
      array("FKZ","C",23),
      array("GEMKGSCHL","N",6,0),
      array("FLURNR","N",3,0),
      array("ZAEHLER","N",5,0),
      array("NENNER","N",3,0),
      array("FLURSTNR","C",9),
      array("FLURSTBEZ","C",15)
    );
#CREATE TABLE `ALK_Flurst` (
#  `AOBJID` varchar(7),
#  `OBJART` varchar(4),
#  `AKT` varchar(2),
#  `BL` varchar(2),
#  `GM` varchar(4),
#  `FL` varchar(3),
#  `FZ` varchar(5),
#  `FN` varchar(3),
#  `FF` varchar(2),
#  `INFOTEXT` varchar(21) default NULL,
#  `ID` int(11) NOT NULL default '0',
#  `FKZ` varchar(23) NOT NULL default '',
#  `GEMKGSCHL` int(6) NOT NULL default '0',
#  `FLURNR` int(3) NOT NULL default '0',
#  `ZAEHLER` int(5) NOT NULL default '0',
#  `NENNER` int(3) NOT NULL default '0',
#  `FLURSTNR` varchar(9) NOT NULL default '',
#  `FLURSTBEZ` varchar(15) NOT NULL default '',
#  PRIMARY KEY  (`ID`),
#  KEY `GemkgSchl` (`GEMKGSCHL`),
#  KEY `Zaehler` (`ZAEHLER`),
#  KEY `Nenner` (`NENNER`),
#  KEY `FlurNr` (`FLURNR`),
#  KEY `FlurstKennz` (`FKZ`)
#) ENGINE=MyISAM;

*/    $def = array(
      array("OBJGR","N",6,0),
      array("OBJID","C",8),
      array("FOLIE","N",6,0),
      array("OBJART","N",6,0),
      array("TEXTART","C",255),
      array("INFOART","N",6,0),
      array("INFOTEXT","C",21),
      array("AKTUAL","N",6,0),
      array("QUELLE","N",6,0),
      array("ID","N",11,0),
      array("FKZ","C",23),
      array("GEMKGSCHL","N",6,0),
      array("FLURNR","N",3,0),
      array("ZAEHLER","N",5,0),
      array("NENNER","N",3,0),
      array("FLURSTNR","C",9),
      array("FLURSTBEZ","C",15)
    );

# Dazugehörige Tabellendefinition in Datenbank
#CREATE TABLE `ALK_Flurst` (
#  `OBJGR` smallint(6) default NULL,
#  `OBJID` varchar(255) NOT NULL default '',
#  `FOLIE` smallint(6) default NULL,
#  `OBJART` smallint(6) default NULL,
#  `TEXTART` varchar(255) default NULL,
#  `INFOART` smallint(6) default NULL,
#  `INFOTEXT` varchar(21) default NULL,
#  `AKTUAL` smallint(6) default NULL,
#  `QUELLE` smallint(6) default NULL,
#  `ID` int(11) NOT NULL default '0',
#  `FKZ` varchar(23) NOT NULL default '',
#  `GEMKGSCHL` int(6) NOT NULL default '0',
#  `FLURNR` int(3) NOT NULL default '0',
#  `ZAEHLER` int(5) NOT NULL default '0',
#  `NENNER` int(3) NOT NULL default '0',
#  `FLURSTNR` varchar(9) NOT NULL default '',
#  `FLURSTBEZ` varchar(15) NOT NULL default '',
#  PRIMARY KEY  (`ID`),
#  KEY `GemkgSchl` (`GEMKGSCHL`),
#  KEY `Zaehler` (`ZAEHLER`),
#  KEY `Nenner` (`NENNER`),
#  KEY `FlurNr` (`FLURNR`),
#  KEY `FlurstKennz` (`FKZ`)
#) ENGINE=MyISAM;

    $this->tabdef=$def;
    return $def;
  }

  function getColNames() {
    for ($i=0;$i<count($this->tabdef);$i++) {
      $names[$i]=$this->tabdef[$i][0];
    }
    $this->colnames=$names;
    return $names;
  }

  function is_FlurstKennz($Eingabe) {
    if ($Eingabe=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->is_FlurstKennz Abfrage ob $Eingabe einer gültigen FlurstKennz entspricht<br>",4);
    $ret=$this->database->is_FlurstKennz($Eingabe);
    return $ret;
  }

  function is_FlurstNr($GemkgID,$FlurID,$Eingabe) {
    # Zerlegen der Eingabe in die Teile vor und nach dem /
    $NrTeil=explode('/',$Eingabe);
    $Zaehler=str_pad($NrTeil[0],5,"0",STR_PAD_LEFT);
    $Nenner=str_pad($NrTeil[1],3,"0",STR_PAD_LEFT);
    # Zusammensetzen der Angaben zu einem FlurstKennz
    $FlurKennz=str_pad($FlurID,3,"0",STR_PAD_LEFT);
    $FlurstKennz=$GemkgID.'-'.$FlurKennz.'-'.$Zaehler.'/'.$Nenner.'.00';
    # Abfrage ob gebildetes $FlutstKennz einem gültigen Flurstueckskennzeichen entspricht
    if ($this->is_FlurstKennz($FlurstKennz)) {
      return $FlurstKennz;
    }
    return 0;
  }

  function is_FlurstZaehler($GemkgID,$FlurID,$Eingabe) {
    # liefert eine Liste mit FlurstKennz von Flurstuecken,
    # die mit Gemkg, FlurID und im Zähler mit Eingabe übereinstimmen
    # Zerlegen der Eingabe in die Teile vor und nach dem /
    $NrTeil=explode('/',$Eingabe);
    $Zaehler=str_pad($NrTeil[0],5,"0",STR_PAD_LEFT);
    # Zusammensetzen des Teiles des FlurstKennz bis Zaehler
    $FlurKennz=str_pad($FlurID,3,"0",STR_PAD_LEFT);
    $KennzTeil=$GemkgID.'-'.$FlurKennz.'-'.$Zaehler.'/';
    # Abfrage der FlurstKennz für die die GemkgID, FlurID und der Zaehler gültig ist
    $FlstListe=$this->database->is_FlurstZaehler($KennzTeil);
    return $FlstListe;
  }

  function getEigentuemerliste($Bezirk,$Blatt,$BVNR) {
    if ($this->FlurstKennz=="") {
      $Grundbuch = new grundbuch("","",$this->debug);
      $Eigentuemerliste[0] = new eigentuemer($Grundbuch,"");
      return $Eigentuemerliste;
    }
    $this->debug->write("<p>kataster flurstueck->getEigentuemerliste Abfragen der Flurstücksdaten aus dem ALK Bestand:<br>",4);
    if(ALKIS)$ret=$this->database->getEigentuemerlisteALKIS($this->FlurstKennz,$Bezirk,$Blatt,$BVNR);
    else $ret=$this->database->getEigentuemerliste($this->FlurstKennz,$Bezirk,$Blatt,$BVNR);
    if ($ret[0] AND DBWRITE) {
      $Grundbuch = new grundbuch("","",$this->debug);
      $Eigentuemerliste[0] = new eigentuemer($Grundbuch,"");
      return $Eigentuemerliste;
    }
    return $ret[1];
  }

  function getGrundstuecke() {
    if ($this->FlurstKennz=="") {
      $Grundstuecke[0] = new grundstueck("","");
      return $Grundstuecke;
    }
    $sql = 'SELECT Bezirk,Blatt,BVNR FROM g_Buchungen WHERE FlurstKennz = "'.$this->FlurstKennz.'"';
    $this->debug->write("<br>".$sql,4);
    $query=mysql_query($sql);
    if (mysql_num_rows($query)==0) {
      $Grundstuecke[0] = new grundstueck("","");
      return $Grundstuecke;
    }
    else {
      while ($rs=mysql_fetch_array($query)) {
        $Grundbuch = new grundbuch($rs['Bezirk'],$rs['Blatt'],$this->database);
        $Grundstuecke[]=new grundstueck($Grundbuch,$rs['BVNR']);
      }
      return $Grundstuecke;
    }
    return $ret;
  }

  function getGrundbuchbezirk() {
    if ($this->FlurstKennz=="") { return 0; }
    if(ALKIS)$ret=$this->database->getGrundbuchbezirkeALKIS($this->FlurstKennz);
    else $ret=$this->database->getGrundbuchbezirke($this->FlurstKennz);
    return $ret;
  }

  function getFortfuehrung() {
    # Abfrage des Datums der letzten Fortführung des Flurstuecks
    if ($this->FlurstKennz=="") { return 0; }

    $sql = 'SELECT LetzFF AS Fortfuehrung FROM Flurstuecke WHERE FlurstKennz = "'.$this->FlurstKennz.'"';
    $this->debug->write("<br>".$sql,4);
    $query=mysql_query($sql); $rs=mysql_fetch_array($query);

    if ($rs['Fortfuehrung']=="") { return "keine Angabe"; } else { return $rs['Fortfuehrung']; }
  }

  function getEntstehung() {
    if ($this->FlurstKennz=="") { return 0; }
    $sql = 'SELECT Entsteh AS Entstehung FROM Flurstuecke WHERE FlurstKennz = "'.$this->FlurstKennz.'"';
    $this->debug->write("<br>".$sql,4);
    $query=mysql_query($sql); if ($query==0) { return 0; }
    $rs=mysql_fetch_array($query);
    if ($rs['Entstehung']=="") { return "keine Angabe"; } else { return $rs['Entstehung']; }
  }

  function getAktualitaetsNr() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("kataster.php flurstueck->getAktualitaetsNr:",4);
    if(ALKIS) {} # ALKIS TODO
    else $ret=$this->database->getAktualitaetsNr($this->FlurstKennz);
    return $ret[1];
  }

  function getPruefzeichen() {
    if ($this->FlurstKennz=="") { return 0; }
    $sql = 'SELECT Pruefzeichen FROM Flurstuecke WHERE FlurstKennz = "'.$this->FlurstKennz.'"';
      $this->debug->write("<br>".$sql,4);
      $query=mysql_query($sql); if ($query==0) { return 0; }
      $rs=mysql_fetch_array($query);
      if ($rs['Pruefzeichen']=="") { return "keine Angabe"; } else { return $rs['Pruefzeichen']; }
  }

  function getForstamt() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php flurstuecke->getForstamt: ".$sql,4);
    if(ALKIS) {} # ALKIS TODO
    else $ret=$this->database->getForstamt($this->FlurstKennz);
    return $ret[1];
  }

  function getFinanzamt() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("kataster.php->flurstueck->getFinanzamt Abfrage des Finanzamtes zum Flurstück<br>".$sql,4);
    $ret=$this->database->getFinanzamt($this->FlurstKennz);
    return $ret[1];
  }

  function getAmtsgericht() {
    $blattnummer = strval($this->Buchungen[0]['blatt']);
    if ($blattnummer >= 90000 and $blattnummer <= 99999) {
      $ret[1]=array("schluessel"=>"", "name"=>"Im Grundbuch nicht gebucht");
    }
    else {
      if(ALKIS)$ret=$this->database->getAmtsgerichtbyBezirkALKIS($this->Grundbuchbezirk);
      else $ret=$this->database->getAmtsgerichtbyFlurst($this->FlurstKennz);
    }  
    return $ret[1];
  }

  function getGemarkung($id) {
    $Gemarkung=new gemarkung($id,$this->database);
    return $Gemarkung;
  }

  function getGemkgSchl() {
    return substr($this->FlurstKennz,0,6);
  }

  function getFlurID() {
  	if(ALKIS)return substr($this->FlurstKennz,6,3);
    else return substr($this->FlurstKennz,7,3);
  }

  function FKZ_Format($format) {
    switch ($format) {
      case 'ALB-Info': {
        $str =substr($this->Infotext,2,6)."-".substr($this->Infotext,8,3);
        $str.="-".substr($this->Infotext,11,5)."/".substr($this->Infotext,16,3).".".substr($this->Infotext,19,2);
      } break;
      case 'EDBS' : {
        $FKZ_Format=$this->Infotext;
      } break;
      case 'ALB' : {
        $str =substr($this->Infotext,2,6).ereg_replace('0',' ',substr($this->Infotext,8,3));
        $str.=ereg_replace('0',' ',substr($this->Infotext,11,5));
        $str.=str_pad (ereg_replace('0',' ',substr($this->Infotext,16,3)),5," ",STR_PAD_LEFT);
        $str.=str_pad ($this->getPruefKZ(), 11, " ", STR_PAD_LEFT);
      } break;
      default : {
        $str=$this->Infotext;
      }
    }
    return $str;
  }

  function getPruefKZ() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("kataster.php flurstueck getPruefKZ<br>line: ".__LINE__,4);
    $ret=$this->database->getPruefKZ($this->FlurstKennz);
    return $ret[1];
  }

  function getFlurkarte() {
    if ($this->FlurstKennz=="") { return 0; }
    $ret=$this->database->getFlurkarte($this->FlurstKennz);
    return $ret[1];
  }

  function getKreis() {
    if ($this->FlurstKennz=="") { return 0; }
    $sql ='SELECT k.Kreis AS ID,k.KreisName AS Name FROM v_Kreise AS k,v_Gemarkungen AS gk,Flurstuecke AS f';
    $sql.=' WHERE f.GemkgSchl=gk.GemkgSchl AND SUBSTRING(gk.Gemeinde,1,5)=k.Kreis';
    $sql.=' AND f.FlurstKennz="'.$this->FlurstKennz.'"';
    $this->debug->write("<br>".$sql,4);
    $query=mysql_query($sql); $rs=mysql_fetch_array($query);
    $this->KreisID=$rs['ID'];
    $this->KreisName=$rs['Name'];
    return $rs;
  }

  function getLage() {
    if(ALKIS)$ret=$this->database->getLageALKIS($this->FlurstKennz);
    else $ret=$this->database->getLage($this->FlurstKennz);
    $this->debug->write("<br>kataster.php flurstueck->getLage() Abfrage der Lagebezeichnung zum Flurstück:",4);
    return $ret[1];
  }

  function getAdresse() {
    $this->debug->write("<br>kataster.php flurstueck->getAdresse() Abfragen der Strassen zum Flurstück:",4);
		if(ALKIS)$ret=$this->database->getStrassenALKIS($this->FlurstKennz);
		else $ret=$this->database->getStrassen($this->FlurstKennz);
    $Strassen=$ret[1];
    for ($i=0;$i<count($Strassen);$i++) {
      $this->debug->write("<br>kataster.php flurstueck->getAdresse() Abfragen der Hausnummern zu den Strassen zum Flurstück:",4);
      if(ALKIS)$ret=$this->database->getHausNummernALKIS($this->FlurstKennz,$Strassen[$i]['strasse']);
      else $ret=$this->database->getHausNummern($this->FlurstKennz,$Strassen[$i]['strasse']);
      $HausNr=$ret[1];
      natsort($HausNr);
      $HausNr = array_values($HausNr);
      $Strassen[$i]['hausnr']=trim($HausNr[0]);
      for ($j=1;$j<count($HausNr);$j++) {
        $Strassen[$i]['hausnr'].=', '.trim($HausNr[$j]);
      } # ende der Schleife  zum Auslesen der Hausnummern der Strasse
    } # ende schleife zum Auslesen der Strassen
    return $Strassen;
  }
  
  function getNutzung() {
    if ($this->FlurstKennz=="") { return 0; }
    if(ALKIS)$ret=$this->database->getNutzungALKIS($this->FlurstKennz);
    else $ret=$this->database->getNutzung($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { return 0; }
    return $ret[1];
  }

  function getFlaeche() {
    if ($this->FlurstKennz=="") { return 0; }
    $ret=$this->database->getFlstFlaeche($this->FlurstKennz);
    # testen ob Abfrage erfolgreich, sonst Abbruch und 0 zurück
    if ($ret[0] AND DBWRITE) { return 0; }
    return $ret[1];
  }

  function getKoordinaten() {
  	$queryret=$this->database->getFlstKoordinaten($this->FlurstKennz);
    if ($queryret[0]) {
      $errmsg='Fehler bei der Abfrage der Koordinaten in getKoordinaten: '.$queryret[1];
    }
    else {
      $rs=pg_fetch_array($queryret[1]);
      $start=strrpos($rs['koordinaten'],'(')+1;
      $end=strpos($rs['koordinaten'],')');
      $vertex=explode(',',substr($rs['koordinaten'],$start,$end-$start));
      for ($i=1;$i<count($vertex);$i++) {
      	$koord=explode(' ',trim($vertex[$i]));
      	$points[$i-1]['lfdnr']=$i;
      	$points[$i-1]['x']=$this->vermessungsrunden(trim($koord[0]), 1);
      	$points[$i-1]['y']=$this->vermessungsrunden(trim($koord[1]), 1);
      }
    }
    $ret[0]=$errmsg;
    $ret[1]=$points;
    return $ret;
  }

  function vermessungsrunden($zahl, $stellen){
  	// Die Funktion rundet eine Gleitkommazahl auf $stellen Stellen nach dem Komma,
  	// wobei bei einer 5 nicht immer aufgerundet, sondern immer zur geraden Zahl gerundet wird.
  	$teil = explode('.', $zahl);
  	$vorkomma = $teil[0];
  	$nachkomma = $teil[1];
  	$newnachkomma = substr($nachkomma, 0, $stellen);
  	$newzahl = $vorkomma.'.'.$newnachkomma;
  	$round = str_pad(substr($nachkomma, 0, $stellen), strlen($nachkomma), '5000000000000000000');
  	if($nachkomma > $round){
  		$newzahl = $newzahl + 1/pow(10, $stellen);
  	}
  	elseif($nachkomma == $round){
  		if($newnachkomma % 2 == 1){
  			$newzahl = $newzahl + 1/pow(10, $stellen);
  		}
  	}
  	if(strpos($newzahl, '.') == false){
  		$newzahl .= '.'.str_repeat('0', $stellen);;
  	}
  	return $newzahl;
  }

  function ALB_Form_Auswaehlen($art) {
    switch ($art) {
      default : {
        $kvwmap = new kvwmap();
        $kvwmap->output("forms/ALB_Auswahl.php","form","html");
      }
      break;
    }
  }

	function getNachfolger() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<p>kataster flurstueck->getNachfolger (vom Flurstück):<br>",4);
    if(ALKIS){}		#ALKIS TODO
    else $ret=$this->database->getNachfolger($this->FlurstKennz);
    return $ret[1];
  }

  function getVorgaenger() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<p>kataster flurstueck->getVorgaenger (vom Flurstück):<br>",4);
    if(ALKIS){}		#ALKIS TODO
    else $ret=$this->database->getVorgaenger($this->FlurstKennz);
    return $ret[1];
  }

  function readALB_Data($FlurstKennz) {
    $this->debug->write("<p>kataster.php flurstueck->readALB_Data (vom Flurstück)",4);
    if(ALKIS)$ret=$this->database->getALBDataALKIS($FlurstKennz);
    else $ret=$this->database->getALBData($FlurstKennz);
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<p>kvwmap readALB_Data Abfragen der ALB-Flurstücksdaten';
      $errmsg.='in line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $rs=$ret[1];
    $this->Zaehler=intval($rs['zaehler']);
    $this->Nenner=intval($rs['nenner']);
    $this->FlurstNr=$this->Zaehler;
    if(ALKIS)$this->Flurstkennz_alt = $rs['gemkgschl'].'-'.$rs['flurnr'].'-'.str_pad($rs['zaehler'], 5, '0', STR_PAD_LEFT).'/'.str_pad($rs['nenner'], 3, '0', STR_PAD_LEFT).'.00';
    if ($this->Nenner!='') { $this->FlurstNr.="/".$this->Nenner; }
    $this->KreisID=$rs['kreisid'];
    $this->KreisName=$rs['kreisname'];
    $this->GemeindeID=$rs['gemeinde'];
    $this->GemeindeName=$rs['gemeindename'];
    $this->GemkgSchl=$rs['gemkgschl'];
    $this->GemkgName=$rs['gemkgname'];
    $this->FlurID=$rs['flurnr'];
    $this->FlurNr=intval($rs['flurnr']);
    $this->FinanzamtName=$rs['finanzamtname'];
    $this->FinanzamtSchl=$rs['finanzamt'];
    if($rs['entsteh'] == '/     -')$rs['entsteh'] = '';
    $this->Entstehung=$rs['entsteh'];
    $this->LetzteFF=$rs['letzff'];
    $this->Flurkarte=$rs['karte'];
    $this->ALB_Flaeche=$rs['flaeche'];
    $this->Status=$rs['status'];
    $this->Forstamt=$this->getForstamt();				# ALKIS TODO
    $this->AktualitaetsNr=$this->getAktualitaetsNr();			# ALKIS TODO
    $this->Adresse=$this->getAdresse();
    $this->Lage=$this->getLage();
    $this->Grundbuchbezirk=$this->getGrundbuchbezirk();
    $this->Klassifizierung=$this->getKlassifizierung();		# ALKIS TODO
    $this->Grundbuecher=$this->getGrundbuecher();
    $this->Buchungen=$this->getBuchungen($Bezirk,$Blatt,1);
    $this->Amtsgericht=$this->getAmtsgericht(); 
    $this->FreiText=$this->getFreiText();		# ALKIS TODO
    $this->Hinweis=$this->getHinweis();		# ALKIS TODO
    $this->Verfahren=$this->getVerfahren();		# ALKIS TODO
    $this->Baulasten=$this->getBaulasten();		# ALKIS TODO
    $this->Vorgaenger=$this->getVorgaenger();	# ALKIS TODO
    $this->Nachfolger=$this->getNachfolger();	# ALKIS TODO
    # Abfragen der Nutzungen
    $this->Nutzung=$this->getNutzung();
    if(ALKIS){}		# ALKIS TODO
    else $updateDate = $this->database->readLastUpdateDate('');
    $this->updateDate = $updateDate[1]['lastupdate'];
  }

  function is_ALK_Flurstueck($FlurstKennz) {
    $this->isALK=$this->database->is_ALK_Flurstueck($FlurstKennz);
  }

  function isALK($FlurstKennz) {
    $this->isALK=0;
    # Funktion fragt ab, ob das Flutstück in der ALK vorkommt
    # Wenn ja wird dem Parameter isALK des Objektes Flurstück der Wert 1 zugewiesen.
    $ALK=new ALK();
    $ALKFlurst=$ALK->getALK_Flurst(0,0,0,array($FlurstKennz),'','FKZ');
    $anzALKFlurst=count($ALKFlurst['FlurstKennz']);
    if ($anzALKFlurst>0) {
      $this->isALK=1;
    }
  }

	function getFlstListeALK($GemID,$GemkgID,$FlurID,$order, $historical = false) {
		$Liste=$this->database->getFlurstuecksListeALK($GemID,$GemkgID,$FlurID,$order, $historical);
		return $Liste;
	}

  function getFlstListe($GemID,$GemkgID,$FlurID,$order, $historical = false) {
    $Liste=$this->database->getFlurstuecksListe($GemID,$GemkgID,$FlurID,$order, $historical);
    return $Liste;
  }
  
  function getFlstListeALKIS($GemID,$GemkgID,$FlurID,$order, $historical = false) {
    $Liste=$this->database->getFlurstuecksListeALKIS($GemID,$GemkgID,$FlurID,$order, $historical);
    return $Liste;
  }

  function getFlstListeByExtent($rectObj) {
    $map=ms_newMapObj('');
    $layer=ms_newLayerObj($map);
    $layer->set('data',SHAPEPATH.$this->getDataSourceName());
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->queryByRect($rectObj);
    $layer->open();
    $anzResult=$layer->getNumResults();
    for ($i=0;$i<$anzResult;$i++) {
      $result=$layer->getResult($i);
      $shapeindex=$result->shapeindex;
      if(MAPSERVERVERSION > 500){
      	$shape=$layer->getFeature($shapeindex,-1);
      }
      else{
      	$shape=$layer->getShape(-1,$shapeindex);
      }
      $Liste['FKZ'][$i]=$shape->values["FKZ"];
    }
    return $Liste;
  }

  function getFlurstByPoint($point) {
    $map=ms_newMapObj('');
    $layer=ms_newLayerObj($map);
    $layer->set('data',SHAPEPATH.$this->getDataSourceName());
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->queryByPoint($point,MS_SINGLE,0);
    $layer->open();
    $result=$layer->getResult(0);
    $shapeindex=$result->shapeindex;
    if(MAPSERVERVERSION > 500){
    	$shape=$layer->getFeature($shapeindex,-1);
    }
    else{
    	$shape=$layer->getShape(-1,$shapeindex);
    }
    $Flurst['FKZ']=$shape->values["FKZ"];
    $Flurst['bounds']=$shape->bounds;
    return $Flurst;
  }

  function getFlurstByLfdNrName($lfd_nr_name,$limitAnzahl) {
    $ret=$this->database->getFlurstueckeByLfdNrName($lfd_nr_name,$limitStart,$limitAnzahl);
    if ($ret[0]) {
      $ret[1]='<br>Fehler bei der Abfrage der Flurstückskennzeichen.'.$ret[1];
    }
    return $ret;
  }
  
  function getFlurstByGrundbuecher($gbarray) {
  	$Flurstuecke = array();
  	for($i = 0; $i < count($gbarray); $i++){
  		$gb = explode('-', $gbarray[$i]);
  		if(ALKIS)$Flurst = $this->database->getFlurstueckeByGrundbuchblattALKIS($gb[0], $gb[1]);
  		else $Flurst = $this->database->getFlurstueckeByGrundbuchblatt($gb[0], $gb[1]);
  		$Flurstuecke = array_merge($Flurstuecke, $Flurst);
  	}
    return $Flurstuecke;
  }

  function getNamen($name1,$name2,$name3,$name4,$bezirk,$blatt,$gemkgschl,$flur,$limitAnzahl,$limitStart,$caseSensitive, $order) {
    if ($name1=='' AND $name2=='' AND $name3=='' AND $name4=='' AND $bezirk=='') {
      $ret[0]=1;
      $ret[1]='<br>Geben Sie mindestens einen Suchbegriff ein!';
    }
    else {
    	if($blatt != ''){
    		$blatt = str_pad($blatt, 5, '0', STR_PAD_LEFT);
    	}
      if(ALKIS)$ret=$this->database->getNamenALKIS($name1,$name2,$name3,$name4,$bezirk,$blatt,$gemkgschl,$flur,$limitAnzahl,$limitStart,$caseSensitive, $order);
      else $ret=$this->database->getNamen($name1,$name2,$name3,$name4,$bezirk,$blatt,$gemkgschl,$flur,$limitAnzahl,$limitStart,$caseSensitive, $order);
      if ($ret[0]) {
        $ret[1]='<br>Fehler bei der Abfrage der Eigentümernamen.'.$ret[1];
      }
    }
    return $ret;
  }

  function getDataSourceName() {
    # Abfragen des Namens der Shapedatei für Flurstücke
    $sql ='SELECT Data FROM layer WHERE Name="'.$this->LayerName.'"';
    $this->debug->write("<p>kataster.php Flurstueck->getDataSourceName Abfragen des Shapefilenamen für die Flurstücke:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    $this->datasourcename=$rs['Data'];
    return $rs['Data'];
  }

  function getFlurstByNutzungen($gemkgschl, $nutzung, $anzahl){
  	$rs = $this->database->getFlurstByNutzungen($gemkgschl, $nutzung, $anzahl);
    return $rs;
  }

}# end of class Flurstueck

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

  function Vermessungsstelle($db){
    $this->database=$db;
  }

  function getVermStelleListe(){
    # fragt alle Vermessungsstellen aus der Datenbank ab (id, Name)
    $sql = 'SELECT * FROM n_vermstelle ORDER BY name';
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

  function Vermessungsart($db){
    $this->database=$db;
  }

  function getVermArtListe(){
    # fragt alle Vermessungsstellen aus der Datenbank ab (id, Name)
    $sql = 'SELECT * FROM n_vermart ORDER BY art';
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