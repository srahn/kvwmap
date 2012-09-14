<?php
#-----------------------------------------------------------------------------------------------------------------
##############
# Klasse ALK #
##############
class ALK {
  var $FlstLayerName;
  var $alk_protokoll_einlesen;
  var $database;

  ##################### Liste der Funktionen ####################################
  #
  # function ALK()  - Construktor
  # function setUpdateMessage($anzflurstuecke,$anzgebaeude,$anznutzungen,$anzausgestaltungen)
  # function updateFlurstuecke()
  # function updateGebaeude()
  # function updateNutzungen()
  # function updateAusgestaltungen()
  # function getRectByFlurstListe($FlurstKennz,$layer)
  # function getALK_Flurst($GemID,$GemkgID,$FlurID,$FlstKennz,$extent,$order)
  # function getALKListeByExtent($rectObj)
  # function getDataSourceName()
  #
  ################################################################################

  function ALK() {
    global $debug;
    $this->debug=$debug;
    $this->LayerName=LAYERNAME_FLURSTUECKE;
  }

  function setUpdateMessage($anzflurstuecke,$anzgebaeude,$anznutzungen,$anzausgestaltungen) {
    $ret=$this->database->insertALKUpdateMessage($anzflurstuecke,$anzgebaeude,$anznutzungen,$anzausgestaltungen);
    if ($ret[0] AND DBWRITE) {
      $msg.='<br>Fehler beim Eintragen der Fortführungsmeldung in der Datenbank.';
      $msg.='<br>'.$ret[1];
    }
    else {
      $msg.='<br>ALK-Aktualisierung erfolgreich abgeschlossen und gebucht.';
    }
  }
  
  function updateFlurstuecke() {
    $flst=new flurstueck('',$this->database);
    $flst->getDataSourceName();
    $filename=SHAPEPATH.'temp/'.$flst->datasourcename;
    # Für die Flurstücksdateien testen ob es eine shp, dbf, und shx gibt
    $msg = 'Fortführen des Flurstücksbestandes.';
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
      $msg.='<br>Zur Fortführung des Flurstücksbestandes fehlen folgende Dateien:';
      $msg.=$errmsg;
    }
    else {
      $msg.='<br>Temporäre Dateien zum aktualisieren gefunden.';
      # lesen der soll-Tabellendefinition
      $tabdef=$flst->getTableDef();
      $colnames=$flst->getColNames();
      $dbfin=dbase_open($filename.'.dbf',0);
      $dbfout=dbase_create($filename.'_neu.dbf',$tabdef);
      if ($dbfin==0 OR $dbfout==0) {
        $msg.='<b><br>Fehler beim Öffnen der dbf-Tabelle für die Flurstücke!</b>';
      }
      else {
        $numfieldsin=dbase_numfields($dbfin);
        $msg.='<br>Schreiben der dbf-Tabelle...';
        echo "Lese Flurstücke...";
        # Leeren des bisherigen ALK-Bestandes an Flurstücken in der Datenbank
        $ret=$this->database->truncateALKFlurst();
        if ($ret[0] AND DBWRITE) {
          $errmsg ='<br>Fehler beim Löschen der ALK-Flurstücke in der Datenbank.';
          $errmsg.='<br>'.$ret[1];
        }
        else {
          $this->database->optimizeALKFlurst();
          # Einlesen der Daten aus der neuen dbf-Tabelle in einer Schleife
          $dbase_num_record=dbase_numrecords($dbfin);
          $alktempfilename=IMAGEPATH.'ALK_temp.txt';
          $fptxt=fopen($alktempfilename,'w');
          $starttime=time();
          for ($i=1;$i<=$dbase_num_record;$i++) {
            # Einlesen der Datenzeile
            $rsin=dbase_get_record($dbfin,$i);
            if ($i-1==$i1000) {
              if ($i>1) { echo "<br>".($i-1)." Zeilen eingelesen.".date('i:s',time()-$starttime);
              
              }
              $i1000+=1000;
            }
            # Konvertieren der Texte in den Spalten der Datenzeile in einer Schleife
            for ($numfieldsout=0;$numfieldsout<$numfieldsin;$numfieldsout++) {
              # Konvertieren der Texte
              $rsout[$numfieldsout]=trim(ANSII2DOS($rsin[$numfieldsout]));
            }
            # Nochmal einlesen der dbf-Zeile, aber diesmal als assoz. Array um auf die Spalten
            # zugreifen zu können
            $rsin=dbase_get_record_with_names($dbfin,$i);          
            # Auffüllen des records mit den zusätzlichen Spalten
            $rsout[$numfieldsout++]=$i; # ID
            $GemkgSchl=substr($rsin['INFOTEXT'],2,6);
            $FlurNr=substr($rsin['INFOTEXT'],8,3);
            $Zaehler=substr($rsin['INFOTEXT'],11,5);
            $Nenner=substr($rsin['INFOTEXT'],16,3);
            $rsout[$numfieldsout++]=$GemkgSchl.'-'.$FlurNr.'-'.$Zaehler.'/'.$Nenner.'.'.substr($rsin['INFOTEXT'],19,2); # FKZ
            $rsout[$numfieldsout++]=$GemkgSchl; # GemkgSchl
            $rsout[$numfieldsout++]=intval($FlurNr); # FlurNr
            $Zaehlerzahl=intval($Zaehler);
            $rsout[$numfieldsout++]=$Zaehlerzahl; # Zaehler
            $Nennerzahl=intval($Nenner);
            $rsout[$numfieldsout++]=$Nennerzahl; # Nenner
            $flurstnr=$Zaehlerzahl;
            $flurstbez=$Zaehlerzahl;
            if ($Nennerzahl>0) {
              $flurstnr.='/'.$Nennerzahl;
              $flurstbez.=';--;'.$Nennerzahl;
            }
            $rsout[$numfieldsout++]=$flurstnr; # FlurstNr
            $rsout[$numfieldsout++]=$flurstbez; # FlurstBez
            # Schreiben der Datenzeile in die vervollständigte temporäre dbf-Tabelle
            if (!dbase_add_record($dbfout,$rsout)) {
              $msg.='<br><b>Fehler beim Umschreiben der dbf-Tabelle in Zeile '.$i.'!</b>';
            }
            # Schreiben der Datenzeile in die temporäre Datei zum Einlesen in die Datenbank mit Load Data INFILE
            $datenzeile=$rsout[0];
            for ($sp=1;$sp<count($rsout);$sp++) {
              $datenzeile.="\t".$rsout[$sp];
            }
            $datenzeile.="\n";
            fputs($fptxt,$datenzeile);
            if ($ret[0]) {
              $msg.='<br>Fehler beim Einfügen eines ALK-Flurstückes in die Datenbank.';
              $msg.='<br>'.$ret[1];
            }
          }
          fclose($fptxt);
          echo '<br>Lese temporär geschriebene ALK Textdatei in Datenbanktabelle ein...';
          $this->database->loadDataInFile($alktempfilename,'ALK_Flurst');
          echo '...fertig.';
        } # end of lesen und überschreiben der Flurstücksdaten
      }
      
      $msg.='<br>...fertig<br>'.($i-1).' Zeilen in neue dbf-Tabelle geschrieben.';
      # Schließen der neuen ALK-Datei und neuen temporären Datei
      dbase_close ($dbfin);
      dbase_close($dbfout);    
      # kopieren der temporären Tabellen ins Datenverzeichnis als neue ALK-Datei
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
        $msg.='<br>Fehler beim Überschreiben des vorherigen Flurstücksbestandes, bei folgenden Dateien:';
        $msg.=$errmsg;
        $msg.='<br>Achtung!!! Die Fortführung war nicht erfolgreich!';
      }
      else {
        $msg.='<br>Alter Datensatz überschrieben.';
      }
    }
    $this->alk_protokoll_einlesen.=$msg;
    return ($i-1);
  }
  
  function updateGebaeude() {
    $geb=new gebaeude('');
    $geb->getDataSourceName();
    $filename=SHAPEPATH.'temp/'.$geb->datasourcename;
    # Für die Gebäudedaten testen ob es eine shp, dbf, und shx gibt
    $msg = '<p>Fortführen des Gebäudebestandes.';
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
      $msg.='<br>Zur Fortführung des Gebäudebestandes fehlen folgende Dateien:';
      $msg.=$errmsg;
    }
    else {
      $msg.='<br>Temporäre Dateien zum aktualisieren gefunden.';
      # lesen der soll-Tabellendefinition
      $tabdef=$geb->getTableDef();
      $colnames=$geb->getColNames();      
      $dbfin=dbase_open($filename.'.dbf',0);
      $dbfout=dbase_create($filename.'_neu.dbf',$tabdef);
      if ($dbfin==0 OR $dbfout==0) {
        $msg.='<b><br>Fehler beim Öffnen der dbf-Tabelle für die Gebäude!</b>';
      }
      else {
        $numfieldsin=dbase_numfields($dbfin);
        $msg.='<br>Schreiben der dbf-Tabelle...';
        echo '<br>Lese Gebäude...';
        # Leeren des bisherigen ALK-Bestandes an Gebäuden in der Datenbank
        $ret=$this->database->truncateALKGebaeude();
        if ($ret[0]) {
          $errmsg ='<br>Fehler beim Löschen der ALK-Gebäude in der Datenbank.';
          $errmsg.='<br>'.$ret[1];
        }
        else {
          for ($i=1;$i<=dbase_numrecords($dbfin);$i++) {
            $rsin=dbase_get_record($dbfin,$i);
            if ($i-1==$i1000) {
              if ($i>1) { echo "<br>".($i-1)." Zeilen eingelesen."; }
              $i1000+=1000;
            }
            for ($numfieldsout=0;$numfieldsout<$numfieldsin;$numfieldsout++) {
              $rsout[$numfieldsout]=trim(ANSII2DOS($rsin[$numfieldsout]));
            }
            $rsin=dbase_get_record_with_names($dbfin,$i);          
            # Auffüllen des records mit den zusätzlichen Spalten
            $rsout[$numfieldsout++]=$i; # ID
            $rsout[$numfieldsout++]=substr($rsin['INFOTEXT'],2,8); # Gemeinde
            $rsout[$numfieldsout++]=substr($rsin['INFOTEXT'],10,5); # STRKEY
            $hausnr=intval(substr($rsin['INFOTEXT'],15,4));
            $zusatz=substr($rsin['INFOTEXT'],19,4);
            if ($zusatz!='0') { $hausnr.=' '.$zusatz; }
            $rsout[$numfieldsout++]=$hausnr; # Hausnr
            if (!dbase_add_record($dbfout,$rsout)) {
              $msg.='<br><b>Fehler beim Umschreiben der dbf-Tabelle in Zeile '.$i.'!</b>';
            }
            # Eintragen der Datenzeile in die Datenbanktabelle
            $ret=$this->database->insertALKGebaeude($colnames,$rsout);
            if ($ret[0]) {
              $msg.='<br>Fehler beim Einfügen eines ALK-Gebäudes in die Datenbank.';
              $msg.='<br>'.$ret[1];
            }            
          }
        }
      }
      $msg.='<br>...fertig<br>'.($i-1).' Zeilen in neue dbf-Tabelle geschrieben.';
      dbase_close ($dbfin);
      dbase_close($dbfout);    
      # kopieren der temporären Tabellen ins Datenverzeichnis
      $source=$filename;
      $target=SHAPEPATH.$geb->datasourcename;      
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
        $msg.='<br>Fehler beim Überschreiben des vorherigen Gebäudebestandes, bei folgenden Dateien:';
        $msg.=$errmsg;
        $msg.='<br>Achtung!!! Die Fortführung war nicht erfolgreich!';
      } 
      else {
        $msg.='<br>Alter Datensatz überschrieben.';
      }
    }
    $this->alk_protokoll_einlesen.=$msg;
    return ($i-1);    
  }  

  function updateNutzungen() {
    $nutzung=new nutzung('');
    $nutzung->getDataSourceName();
    $filename=SHAPEPATH.'temp/'.$nutzung->datasourcename;
    # Für die Nutzungen testen ob es eine shp, dbf, und shx gibt
    $msg = '<p>Fortführen des Nutzungsartenbestandes.';
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
      $msg.='<br>Zur Fortführung des Nutzungsartenbestandes fehlen folgende Dateien:';
      $msg.=$errmsg;
    }
    else {
      $msg.='<br>Temporäre Dateien zum aktualisieren gefunden.';
      # lesen der soll-Tabellendefinition
      $tabdef=$nutzung->getTableDef();
      $colnames=$nutzung->getColNames();      
      $dbfin=dbase_open($filename.'.dbf',0);
      $dbfout=dbase_create($filename.'_neu.dbf',$tabdef);
      if ($dbfin==0 OR $dbfout==0) {
        $msg.='<b><br>Fehler beim Öffnen der dbf-Tabelle für die Nutzungen!</b>';
      }
      else {
        $numfieldsin=dbase_numfields($dbfin);
        $msg.='<br>Schreiben der dbf-Tabelle...';
        echo '<br>Lese Nutzungsarten...';
        # Leeren des bisherigen ALK-Bestandes an Nutzungen in der Datenbank
        $ret=$this->database->truncateALKNutzungen();
        if ($ret[0]) {
          $errmsg ='<br>Fehler beim Löschen der ALK-Nutzung in der Datenbank.';
          $errmsg.='<br>'.$ret[1];
        }
        else {
          for ($i=1;$i<=dbase_numrecords($dbfin);$i++) {
            $rsin=dbase_get_record($dbfin,$i);
            if ($i-1==$i1000) {
              if ($i>1) { echo "<br>".($i-1)." Zeilen eingelesen."; }
              $i1000+=1000;
            }
            for ($numfieldsout=0;$numfieldsout<$numfieldsin;$numfieldsout++) {
              $rsout[$numfieldsout]=trim(ANSII2DOS($rsin[$numfieldsout]));
            }
            $rsin=dbase_get_record_with_names($dbfin,$i);          
            # Auffüllen des records mit den zusätzlichen Spalten
            $rsout[$numfieldsout++]=$i; # ID
            if (!dbase_add_record($dbfout,$rsout)) {
              $msg.='<br><b>Fehler beim Umschreiben der dbf-Tabelle in Zeile '.$i.'!</b>';
            }
            # Eintragen der Datenzeile in die Datenbanktabelle
            $ret=$this->database->insertALKNutzungen($colnames,$rsout);
            if ($ret[0]) {
              $msg.='<br>Fehler beim Einfügen einer ALK-Nutzung in die Datenbank.';
              $msg.='<br>'.$ret[1];
            }            
          }
        }
      }
      $msg.='<br>...fertig<br>'.($i-1).' Zeilen in neue dbf-Tabelle geschrieben.';
      dbase_close ($dbfin);
      dbase_close($dbfout);    
      # kopieren der temporären Tabellen ins Datenverzeichnis
      $source=$filename;
      $target=SHAPEPATH.$nutzung->datasourcename; # geändert 2005-12-15 pk     
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
        $msg.='<br>Fehler beim Überschreiben des vorherigen Nutzungsbestandes, bei folgenden Dateien:';
        $msg.=$errmsg;
        $msg.='<br>Achtung!!! Die Fortführung war nicht erfolgreich!';
      } 
      else {
        $msg.='<br>Alter Datensatz überschrieben.';
      }
    }
    $this->alk_protokoll_einlesen.=$msg;
    return ($i-1);
  }  
  
  function updateAusgestaltungen() {
    $ausgest=new ausgestaltung('');
    $ausgest->getDataSourceName();
    $filename=SHAPEPATH.'temp/'.$ausgest->datasourcename;
    # Für die Ausgestaltungen testen ob es eine shp, dbf, und shx gibt
    $msg = '<p>Fortführen der Ausgestaltung.';
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
      $msg.='<br>Zur Fortführung der Ausgestaltung fehlen folgende Dateien:';
      $msg.=$errmsg;
    }
    else {
      $msg.='<br>Temporäre Dateien zum aktualisieren gefunden.';
      # lesen der soll-Tabellendefinition
      $tabdef=$ausgest->getTableDef();
      $colnames=$ausgest->getColNames();      
      $dbfin=dbase_open($filename.'.dbf',0);
      $dbfout=dbase_create($filename.'_neu.dbf',$tabdef);
      if ($dbfin==0 OR $dbfout==0) {
        $msg.='<b><br>Fehler beim Öffnen der dbf-Tabelle für die Ausgestaltungen!</b>';
      }
      else {
        $numfieldsin=dbase_numfields($dbfin);
        $msg.='<br>Schreiben der dbf-Tabelle...';
        echo '<br>Lese Ausgestaltung...';
        # Leeren des bisherigen ALK-Bestandes an Ausgestaltungen in der Datenbank
        $ret=$this->database->truncateALKAusgestaltungen();
        if ($ret[0]) {
          $errmsg ='<br>Fehler beim Löschen der ALK-Ausgestaltung in der Datenbank.';
          $errmsg.='<br>'.$ret[1];
        }
        else {
          $this->database->optimizeALKAusgestaltungen();
          # Einlesen der Daten aus der neuen dbf-Tabelle in einer Schleife
          $dbase_num_record=dbase_numrecords($dbfin);
          $alktempfilename=IMAGEPATH.'ALK_temp.txt';
          $fptxt=fopen($alktempfilename,'w');
          $starttime=time();
          for ($i=1;$i<=$dbase_num_record;$i++) {
            $rsin=dbase_get_record($dbfin,$i);
            if ($i-1==$i1000) {
              if ($i>1) { echo "<br>".($i-1)." Zeilen eingelesen.".date('i:s',time()-$starttime); }
              $i1000+=1000;
            }
            for ($numfieldsout=0;$numfieldsout<$numfieldsin;$numfieldsout++) {
              $rsout[$numfieldsout]=trim(ANSII2DOS($rsin[$numfieldsout]));
            }
            $rsin=dbase_get_record_with_names($dbfin,$i);          
            # Auffüllen des records mit den zusätzlichen Spalten
            $rsout[$numfieldsout++]=$i; # ID
            if (!dbase_add_record($dbfout,$rsout)) {
              $msg.='<br><b>Fehler beim Umschreiben der dbf-Tabelle in Zeile '.$i.'!</b>';
            }
            # Schreiben der Datenzeile in die temporäre Datei zum Einlesen in die Datenbank mit Load Data INFILE
            $datenzeile=$rsout[0];
            for ($sp=1;$sp<count($rsout);$sp++) {
              $datenzeile.="\t".$rsout[$sp];
            }
            $datenzeile.="\n";
            fputs($fptxt,$datenzeile);            
            if ($ret[0]) {
              $msg.='<br>Fehler beim Einfügen einer ALK-Ausgestaltung in die Datenbank.';
              $msg.='<br>'.$ret[1];
            }
          }
          fclose($fptxt);
          echo '<br>Lese temporär geschriebene ALK Textdatei für Ausgestaltung in Datenbanktabelle ein...';
          $this->database->loadDataInFile($alktempfilename,'ALK_Ausgest');
          echo '...fertig.';         
        }
      }
      $msg.='<br>...fertig<br>'.($i-1).' Zeilen in neue dbf-Tabelle geschrieben.';
      dbase_close ($dbfin);
      dbase_close($dbfout);    
      # kopieren der temporären Tabellen ins Datenverzeichnis
      $source=$filename;
      $target=SHAPEPATH.$ausgest->datasourcename; # geändert 2005-12-15 pk     
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
        $msg.='<br>Fehler beim Überschreiben der vorherigen Ausgestaltungen, bei folgenden Dateien:';
        $msg.=$errmsg;
        $msg.='<br>Achtung!!! Die Fortführung war nicht erfolgreich!';
      } 
      else {
        $msg.='<br>Alter Datensatz überschrieben.';
      }
    }
    $this->alk_protokoll_einlesen.=$msg;
    return ($i-1);    
  }  
  
  function getRectByFlurstListe($FlurstKennz,$layer) {
    $anzFlst=count($FlurstKennz);
    $minx=9999999;
    $miny=9999999;
    $maxx=0;
    $maxy=0;
    #echo $FlurstKennz[0];
    for ($i=0;$i<$anzFlst;$i++) {
      @$layer->queryByAttributes('FKZ',$FlurstKennz[$i],0);
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
  
  function getMERfromGebaeude($Hausnr, $epsgcode) {
    # 2006-01-31 pk
    if(ALKIS)$ret=$this->database->getMERfromGebaeudeALKIS($Hausnr, $epsgcode);
    else $ret=$this->database->getMERfromGebaeude($Hausnr, $epsgcode);
    if ($ret[0]==0) {
      $rect=ms_newRectObj();
      $rect->minx=$ret[1]['minx']; $rect->maxx=$ret[1]['maxx'];
      $rect->miny=$ret[1]['miny']; $rect->maxy=$ret[1]['maxy'];
      $ret[1]=$rect;
    }
    return $ret;
  }
  
  function getMERfromGemeinde($Gemeinde, $epsgcode) {
    # 2006-01-31 pk
    $ret=$this->database->getMERfromGemeinde($Gemeinde, $epsgcode);
    if ($ret[0]==0) {
      $rect=ms_newRectObj();
      $rect->minx=$ret[1]['minx']; $rect->maxx=$ret[1]['maxx'];
      $rect->miny=$ret[1]['miny']; $rect->maxy=$ret[1]['maxy'];
      $ret[1]=$rect;
    }
    return $ret;
  }
  
  function getMERfromGemarkung($Gemkgschl, $epsgcode) {
    # 2006-02-01 pk
    $ret=$this->database->getMERfromGemarkung($Gemkgschl, $epsgcode);
    if ($ret[0]==0) {
      $rect=ms_newRectObj();
      $rect->minx=$ret[1]['minx']; $rect->maxx=$ret[1]['maxx'];
      $rect->miny=$ret[1]['miny']; $rect->maxy=$ret[1]['maxy'];
      $ret[1]=$rect;
    }
    return $ret;
  }

  function getMERfromFlur($Gemarkung,$Flur, $epsgcode) {
    # 2006-02-01 pk
    $ret=$this->database->getMERfromFlur($Gemarkung,$Flur,$epsgcode);
    if ($ret[0]==0) {
      $rect=ms_newRectObj();
      $rect->minx=$ret[1]['minx']; $rect->maxx=$ret[1]['maxx'];
      $rect->miny=$ret[1]['miny']; $rect->maxy=$ret[1]['maxy'];
      $ret[1]=$rect;
    }
    return $ret;
  }
    
  function getMERfromFlurstuecke($flstliste, $epsgcode) {
    #2005-11-30_pk
    if(ALKIS)$ret=$this->database->getMERfromFlurstueckeALKIS($flstliste, $epsgcode);
    else $ret=$this->database->getMERfromFlurstuecke($flstliste, $epsgcode);
    if ($ret[0]==0) {
      $rect=ms_newRectObj();
      $rect->minx=$ret[1]['minx']; $rect->maxx=$ret[1]['maxx'];
      $rect->miny=$ret[1]['miny']; $rect->maxy=$ret[1]['maxy'];
      $ret[1]=$rect;
    }
    return $ret;
  }
  
  function getALK_Flurst($GemID,$GemkgID,$FlurID,$FlstKennz,$extent,$order) {
    # Abfragen der Flurstücksdaten aus dem ALK Bestand
    $sql ='SELECT *';
    $sql.=' FROM ALK_Flurst WHERE 1=1';
    if ($GemkgID>0) {
      $sql.=' AND GemkgSchl= '.$GemkgID;
    }
    if ($FlurID!='') {
      $FlurKennz=str_pad($FlurID,3,"0",STR_PAD_LEFT);        
      $sql.=' AND SUBSTRING(FKZ,8,3)="'.$FlurKennz.'"';
    }
    $anzFlurst=count($FlstKennz);
    if ($anzFlurst>0) {
      $sql.=' AND FKZ IN ("'.$FlstKennz[0].'"';
      for ($i=1;$i<$anzFlurst;$i++) {
        $sql.=',"'.$FlstKennz[$i].'"';
      }
      $sql.=')';
    }
    if ($extent!=0 AND $extent!='') {
      $FlurstInExtent=$this->getALKListeByExtent($extent);
      $sql.=' AND FKZ IN ("'.$FlurstInExtent['ID'][0].'"';
      for ($i=1;$i<count($FlurstInExtent['ID']);$i++) {
        $sql.=',"'.$FlurstInExtent['ID'][$i].'"';
      }
      $sql.=')';
    }    
    if ($order!='') {
      $sql.=' ORDER BY '.$order; 
    }
    $this->debug->write("<p>kataster.php->getALK_Flurst->Abfragen der Flurstücksdaten aus dem ALK Bestand:<br>".$sql,4);    
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while ($rs=mysql_fetch_array($query)) {
      $Liste['FlurstKennz'][]=$rs['FKZ'];
      $Liste['FlurstNr'][]=$rs['FLURSTNR'];
    }
    return $Liste;    
  }

  function getALKListeByExtent($rectObj) {
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
      $Liste['ID'][$i]=$shape->values["FKZ"];
    }
    return $Liste;
  }

  function getDataSourceName() {
    # Abfragen des Namens der Shapedatei für Flurstücke
    $sql ='SELECT Data FROM layer WHERE Name="'.$this->LayerName.'"';
    $this->debug->write("<p>kataster.php ALK->getDataSourceName Abfragen des Shapefilenamen für die Flurstücke:<br>".$sql,4);    
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    return $rs['Data'];
  } 
} # end of class ALK
?>