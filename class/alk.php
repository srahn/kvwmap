<?php
#-----------------------------------------------------------------------------------------------------------------
##############
# Klasse ALK #
##############
class ALK {
  var $FlstLayerName;
  var $alk_protokoll_einlesen;
  var $database;


  function ALK() {
    global $debug;
    $this->debug=$debug;
    $this->LayerName=LAYERNAME_FLURSTUECKE;
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
  
  function getMERfromGebaeude($Gemeinde,$Strasse,$Hausnr, $epsgcode) {
    $ret=$this->database->getMERfromGebaeude($Gemeinde,$Strasse,$Hausnr, $epsgcode);
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
    $ret=$this->database->getMERfromFlurstuecke($flstliste, $epsgcode);
    if ($ret[0]==0) {
      $rect=ms_newRectObj();
      $rect->minx=$ret[1]['minx']; $rect->maxx=$ret[1]['maxx'];
      $rect->miny=$ret[1]['miny']; $rect->maxy=$ret[1]['maxy'];
      $ret[1]=$rect;
    }
    return $ret;
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