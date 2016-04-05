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
##########
#-> Flur #
##########

class Flur {
  var $FlurID;
  var $database;

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
	
	function getBezeichnungFromPosition($position, $epsgcode){
		return $this->database->getBezeichnungFromPosition($position, $epsgcode);
  }

  function getFlurListe($GemkgID,$FlurID, $historical = false) {
    # Abfragen der Fluren
    $Liste=$this->database->getFlurenListeByGemkgIDByFlurID($GemkgID,$FlurID, $historical);
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

  function getFlurstKennzListe() {
    # liefert FlurstKennz zur Adressangaben aus dem ALB Bestand
    $ret=$this->database->getFlurstKennzListeByGemSchlByStrSchl($this->GemeindeSchl,$this->StrassenSchl,$this->HausNr);
    if ($ret[0]) {
      $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4);
      return 0;
    }
    else {
      return $ret[1];
    }
  }

  function getStrassenListe($GemID,$GemkgID,$extent) {
    # Funktion liefert eine Liste der Strassen innerhalb der GemID und ggf. des extent
    $PolygonWKTString=rectObj2WKTPolygon($extent);
    $StrassenListe=$this->database->getStrassenListe($GemID, $GemkgID, $PolygonWKTString);
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
    $PolygonWKTString=rectObj2WKTPolygon($extent);
    $HausNrListe=$this->database->getHausNrListe($GemID,$StrID,$HausNr,$PolygonWKTString,$order);
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
  
	function getStrNamefromID($GemID,$StrID) {
    $ret=$this->database->getStrNameByID($GemID,$StrID);
    if ($ret[0]==0 AND count($ret[1])>0) {
      # liefert die erste gefundene Strasse zurück
      return $ret[1];
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

  function getGemeindeListe($Gemeinden) {
    $GemeindeListe=$this->database->getGemeindeListeByKreisGemeinden($Gemeinden);
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

  function getGemarkungListe($GemID,$GemkgID) {
    # Abfragen der Gemarkungen mit seinen GemeindeNamen
    $Liste=$this->database->getGemeindeListeByGemIDByGemkgSchl($GemID,$GemkgID);
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

  function eigentuemer($Grundbuch,$NamensNr, $database = NULL) {
    global $debug;
    $this->debug=$debug;
    $this->Grundbuch=$Grundbuch;
    $this->NamensNr=$NamensNr;
    $this->database=$database;
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

  function getAdressaenderungen($gml_id) {
    $sql ="SELECT gml_id, hat, datum, user_id, coalesce(strasse1, strasse) as strasse,  coalesce(hausnummer1, hausnummer) as hausnummer, coalesce(postleitzahlpostzustellung1, postleitzahlpostzustellung) as postleitzahlpostzustellung, coalesce(ort_post1, ort_post) as ort_post, coalesce(ortsteil1, ortsteil) as ortsteil FROM ";
		$sql.="(SELECT p.*, a.strasse as strasse1, a.hausnummer as hausnummer1, a.postleitzahlpostzustellung as postleitzahlpostzustellung1, a.ort_post as ort_post1, a.ortsteil as ortsteil1, at.strasse, at.hausnummer, at.postleitzahlpostzustellung, at.ort_post, at.ortsteil FROM alkis.ax_person_temp p ";
		$sql.="LEFT JOIN alkis.ax_anschrift_temp at ON p.hat = at.gml_id ";
		$sql.="LEFT JOIN alkis.ax_anschrift a ON p.hat = a.gml_id ";
    $sql.="WHERE p.gml_id = '".$gml_id."') as foo";
    #echo $sql;
    $query=pg_query($sql);
  	$ret=$this->database->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    $rs=pg_fetch_array($ret[1]);
    return $rs;
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

  function grundbuch($Bezirk,$Blatt,$database) {
    global $debug;
    $this->debug=$debug;
    $this->Bezirk=$Bezirk;
    $this->Blatt=$Blatt;
    $this->database=$database;
  }

  function getBuchungen($flurstkennz,$bvnr,$erbaurechtshinweise,$keine_historischen, $buchungsstelle = NULL) {
    $ret=$this->database->getBuchungenFromGrundbuch('',$this->Bezirk,$this->Blatt, NULL, NULL, $buchungsstelle);
    if ($ret[0]) {
      $ret[1]='Fehler bei der Datenbank abfrage<br>'.$ret[1];
    }
    else {
      # Zuordnen der Gemeinde und Gemarkungsnamen zu den Flurstücken.
      $buchungen=$ret[1];
      $anzBuchungen=count($buchungen);
      for($i=0;$i<$anzBuchungen;$i++) {
				//$buchungenret=$this->database->getGemarkungName(substr($buchungen[$i]['flurstkennz'],0,6));
				$buchungen[$i]['gemkgname']=$buchungen[$i]['gemarkungsname'];
				$buchungen[$i]['flur']=intval(substr($buchungen[$i]['flurstkennz'],6,3));
				$buchungen[$i]['zaehler']=intval(substr($buchungen[$i]['flurstkennz'],9,5));
				$buchungen[$i]['nenner']=intval(substr($buchungen[$i]['flurstkennz'],14,6));
				$buchungen[$i]['flurstuecksnr']=$buchungen[$i]['zaehler'];
				if ($buchungen[$i]['nenner']>0) {
					$buchungen[$i]['flurstuecksnr'].='/'.$buchungen[$i]['nenner'];
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
    if ($this->Blatt=='' OR $this->Blatt===0) {
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
  	return $this->database->getGrundbuchbezirksliste();
  }

  function getGrundbuchbezirkslisteByGemkgIDs($gemkg_ids){
  	return $this->database->getGrundbuchbezirkslisteByGemkgIDs($gemkg_ids);
  }
  
  function getGrundbuchblattliste($bezirk){
  	return $this->database->getGrundbuchblattliste($bezirk);
  }
	
	function getGrundbuchblattlisteByGemkgIDs($bezirk, $gemkg_ids){
  	return $this->database->getGrundbuchblattlisteByGemkgIDs($bezirk, $gemkg_ids);
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
		
	function getSonstigesrecht() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getSonstigesrecht Abfrage des Sonstigesrechts zum Flurstück<br>".$sql,4);
    $ret=$this->database->getSonstigesrecht($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $Sonstigesrecht=$ret[1];
    return $Sonstigesrecht;
  }
	
	function getDenkmalschutzrecht() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getDenkmalschutzrecht Abfrage des Denkmalschutzrechts zum Flurstück<br>".$sql,4);
    $ret=$this->database->getDenkmalschutzrecht($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $Denkmalschutzrecht=$ret[1];
    return $Denkmalschutzrecht;
  }
	
	function getBauBodenrecht() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getBauBodenrecht Abfrage des BauBodenrechts zum Flurstück<br>".$sql,4);
    $ret=$this->database->getBauBodenrecht($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $BauBodenrecht=$ret[1];
    return $BauBodenrecht;
  }
		
	function getNaturUmweltrecht() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getNaturUmweltrecht Abfrage des NaturUmweltrechts zum Flurstück<br>".$sql,4);
    $ret=$this->database->getNaturUmweltrecht($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $NaturUmweltrecht=$ret[1];
    return $NaturUmweltrecht;
  }
	
	function getSchutzgebiet() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getSchutzgebiet Abfrage des Schutzgebiets zum Flurstück<br>".$sql,4);
    $ret=$this->database->getSchutzgebiet($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $Schutzgebiet=$ret[1];
    return $Schutzgebiet;
  }
	
	function getWasserrecht() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getWasserrecht Abfrage des Wasserrechts zum Flurstück<br>".$sql,4);
    $ret=$this->database->getWasserrecht($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $Wasserrecht=$ret[1];
    return $Wasserrecht;
  }
	
	function getStrassenrecht() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getStrassenrecht Abfrage des Strassenrechts zum Flurstück<br>".$sql,4);
    $ret=$this->database->getStrassenrecht($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $Strassenrecht=$ret[1];
    return $Strassenrecht;
  }
		
	function getForstrecht() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getForstrecht Abfrage des Forstrechts zum Flurstück<br>".$sql,4);
    $ret=$this->database->getForstrecht($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $Forstrecht=$ret[1];
    return $Forstrecht;
  }
	
	function getStrittigeGrenze() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getStrittigeGrenze Abfrage der strittigen Grenzen zum Flurstück<br>".$sql,4);
    $ret=$this->database->getStrittigeGrenze($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $strittigeGrenze=$ret[1];
    return $strittigeGrenze;
  }	

  function getKlassifizierung() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getKlassifizierung Abfrage der Klassifizierungen zum Flurstück<br>".$sql,4);
    $ret=$this->database->getKlassifizierung($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $Klassifizierung=$ret[1];
    return $Klassifizierung;
  }

  function getBuchungen($Bezirk,$Blatt,$hist_alb = false) {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getBuchungen Abfrage der Buchungen zum Flurstück auf dem Grundbuch<br>",4);
    #$ret=$this->database->getBuchungen($this->FlurstKennz);
    $ret=$this->database->getBuchungenFromGrundbuch($this->FlurstKennz,$Bezirk,$Blatt,$hist_alb, $this->fiktiv);
    return $ret[1];
  }

  function getGrundbuecher() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getGrundbuecher Abfrage der Angaben zum Grundbuch auf dem das Flurstück gebucht ist<br>",4);
    $ret=$this->database->getGrundbuecher($this->FlurstKennz, $this->hist_alb);
		if($ret['fiktiv'])$this->fiktiv = true;
    return $ret[1];
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
    $ret=$this->database->getEigentuemerliste($this->FlurstKennz,$Bezirk,$Blatt,$BVNR);
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
    $ret=$this->database->getGrundbuchbezirke($this->FlurstKennz, $this->hist_alb);
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
    # ALKIS TODO
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
    $ret=$this->database->getForstamt($this->FlurstKennz);
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
      $ret=$this->database->getAmtsgerichtby($this->FlurstKennz, $this->Grundbuchbezirk);
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
  	return substr($this->FlurstKennz,6,3);
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
    $ret=$this->database->getLage($this->FlurstKennz);
    $this->debug->write("<br>kataster.php flurstueck->getLage() Abfrage der Lagebezeichnung zum Flurstück:",4);
    return $ret[1];
  }

  function getAdresse() {
    $this->debug->write("<br>kataster.php flurstueck->getAdresse() Abfragen der Strassen zum Flurstück:",4);
		$ret=$this->database->getStrassen($this->FlurstKennz);
    $Strassen=$ret[1];
    for ($i=0;$i<count($Strassen);$i++) {
      $this->debug->write("<br>kataster.php flurstueck->getAdresse() Abfragen der Hausnummern zu den Strassen zum Flurstück:",4);
      $ret=$this->database->getHausNummern($this->FlurstKennz,$Strassen[$i]['strasse']);
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
    $ret=$this->database->getNutzung($this->FlurstKennz);
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
	
	function getVersionen() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<p>kataster flurstueck->getVersionen (vom Flurstück):<br>",4);
    $versionen=$this->database->getVersionen('alkis.ax_flurstueck', $this->gml_id);
		for($b=0; $b < count($this->Buchungen); $b++){
			$versionen= array_merge($versionen, $this->database->getVersionen('alkis.ax_buchungsstelle', $this->Buchungen[$b]['gml_id']));
		}		
		usort($versionen, function($a, $b){return DateTime::createFromFormat('d.m.Y H:i:s', $a['beginnt']) > DateTime::createFromFormat('d.m.Y H:i:s', $b['beginnt']);});
		for($i = 0; $i < count($versionen); $i++){
			if($unique_versionen[$versionen[$i]['beginnt']]['endet'] == '' OR $unique_versionen[$versionen[$i]['beginnt']]['endet'] > $versionen[$i]['endet'])$unique_versionen[$versionen[$i]['beginnt']]['endet'] = $versionen[$i]['endet'];
			if($versionen[$i]['anlass'] != '')$unique_versionen[$versionen[$i]['beginnt']]['anlass'] .= $versionen[$i]['anlass'].' ';
		}
    return $unique_versionen;
  }
	
	function getNachfolger() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<p>kataster flurstueck->getNachfolger (vom Flurstück):<br>",4);
    $ret=$this->database->getNachfolger($this->FlurstKennz);
    return $ret[1];
  }

  function getVorgaenger() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<p>kataster flurstueck->getVorgaenger (vom Flurstück):<br>",4);
		$ret=$this->database->getVorgaenger($this->FlurstKennz);
    return $ret[1];
  }

  function readALB_Data($FlurstKennz, $without_temporal_filter = false) {
    $this->debug->write("<p>kataster.php flurstueck->readALB_Data (vom Flurstück)",4);
    $ret=$this->database->getALBData($FlurstKennz, $without_temporal_filter);
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<p>kvwmap readALB_Data Abfragen der ALB-Flurstücksdaten';
      $errmsg.='in line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
		if($without_temporal_filter AND $ret[1]['hist_alb'] == 0){
			if($ret[1]['endet'] != '')rolle::$hist_timestamp = DateTime::createFromFormat('d.m.Y H:i:s', $ret[1]['beginnt'])->format('Y-m-d\TH:i:s\Z');
			else rolle::$hist_timestamp = '';
		}
    $rs=$ret[1];
		$this->gml_id=$rs['gml_id'];
    $this->Zaehler=intval($rs['zaehler']);
    $this->Nenner=intval($rs['nenner']);
    $this->FlurstNr=$this->Zaehler;
    $this->Flurstkennz_alt = $rs['gemkgschl'].'-'.$rs['flurnr'].'-'.str_pad($rs['zaehler'], 5, '0', STR_PAD_LEFT).'/'.str_pad($rs['nenner'], 3, '0', STR_PAD_LEFT).'.00';
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
		$this->abweichenderrechtszustand=$rs['abweichenderrechtszustand'];
    $this->endet=$rs['endet'];
		$this->beginnt=$rs['beginnt'];
		$this->hist_alb=$rs['hist_alb'];
    $this->Pruefzeichen=$rs['pruefzeichen'];
    $this->Forstamt=$this->getForstamt();	
    $this->AktualitaetsNr=$this->getAktualitaetsNr();			# ALKIS TODO
    $this->Adresse=$this->getAdresse();
    $this->Lage=$this->getLage();
    $this->Grundbuchbezirk=$this->getGrundbuchbezirk();
    $this->Klassifizierung=$this->getKlassifizierung();
		$this->Forstrecht=$this->getForstrecht();
		$this->Strassenrecht=$this->getStrassenrecht();
		$this->Wasserrecht=$this->getWasserrecht();
		$this->Schutzgebiet=$this->getSchutzgebiet();		
		$this->NaturUmweltrecht=$this->getNaturUmweltrecht();
		$this->BauBodenrecht=$this->getBauBodenrecht();
		$this->Denkmalschutzrecht=$this->getDenkmalschutzrecht();
		$this->Sonstigesrecht=$this->getSonstigesrecht();				
		$this->strittigeGrenze=$this->getStrittigeGrenze();
    //$this->Grundbuecher=$this->getGrundbuecher();							# steht im Snippet
    //$this->Buchungen=$this->getBuchungen($Bezirk,$Blatt,1);		# steht im Snippet
    $this->Amtsgericht=$this->getAmtsgericht(); 
    $this->Vorgaenger=$this->getVorgaenger();	
    $this->Nachfolger=$this->getNachfolger();	
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

  function getFlstListe($GemID,$GemkgID,$FlurID, $historical = false) {
    $Liste=$this->database->getFlurstuecksListe($GemID,$GemkgID,$FlurID, $historical);
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
  		$Flurst = $this->database->getFlurstueckeByGrundbuchblatt($gb[0], $gb[1]);
  		$Flurstuecke = array_merge($Flurstuecke, $Flurst);
  	}
    return $Flurstuecke;
  }

  function getNamen($formvars,$gemkgschl) {
    if ($formvars['name1']=='' AND $formvars['name2']=='' AND $formvars['name3']=='' AND $formvars['name4']=='' AND $formvars['name5']=='' AND $formvars['name6']=='' AND $formvars['name7']=='' AND $formvars['name8']=='') {
      $ret[0]=1;
      $ret[1]='<br>Geben Sie mindestens einen Suchbegriff ein!';
    }
    else {
    	if($blatt != ''){
    		$blatt = str_pad($blatt, 5, '0', STR_PAD_LEFT);
    	}
      $ret=$this->database->getNamen($formvars, $gemkgschl);
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
	
	function getFlurstByLatLng($latitude, $longitude) {
		$rs = Array();
		if ($latitude != '' or $longitude != '') {
			$rs = $this->database->getFlurstueckByLatLng($latitude, $longitude);
	    $this->Zaehler = $rs['zaehler'];
	    $this->Nenner = $rs['nenner'];
	    $this->FlurstNr = $this->Zaehler;
	    $this->Flurstkennz_alt = $rs['flurstkennz'];
	    if ($this->Nenner != '') { $this->FlurstNr .= "/".$this->Nenner; }
	    $this->KreisID = $rs['kreis'];
	#    $this->KreisName=$rs['kreisname'];
	    $this->GemeindeID = $rs['gemeinde'];
	#    $this->GemeindeName=$rs['gemeindename'];
	    $this->GemkgSchl = $rs['gemarkungsnummer'];
	#    $this->GemkgName=$rs['gemkgname'];
	    $this->FlurID = $rs['flurnummer'];
	#    $this->FlurNr = $rs['flurnr'];
		}
		return $rs;
	}

}# end of class Flurstueck

?>