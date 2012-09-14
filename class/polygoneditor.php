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
#############################
# Klasse Polygoneditor #
#############################

class polygoneditor {

  function polygoneditor($database, $layerepsg, $clientepsg) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
    $this->clientepsg = $clientepsg;
    #echo '<br>EPSG-Code im Client:'.$this->clientepsg;
    $this->layerepsg = $layerepsg;
    #echo '<br>EPSG-Code vom Layer:'.$this->layerepsg;
  }
  
  function zoomTopolygon($oid, $tablename, $columnname,  $border) {
  	# Eine Variante mit der nur einmal transformiert wird
  	$sql ="SELECT xmin(bbox) AS minx,ymin(bbox) AS miny,xmax(bbox) AS maxx,ymax(bbox) AS maxy";
  	$sql.=" FROM (SELECT box2D(Transform(".$columnname.", ".$this->clientepsg.")) as bbox";
  	$sql.=" FROM ".$tablename." WHERE oid = '".$oid."') AS foo";
#  	$sql = 'SELECT MIN(XMIN(ENVELOPE(Transform(the_geom, '.$this->clientepsg.')))) AS minx, MAX(XMAX(ENVELOPE(Transform(the_geom, '.$this->clientepsg.')))) AS maxx';
#    $sql.= ', MIN(YMIN(ENVELOPE(Transform(the_geom, '.$this->clientepsg.')))) AS miny, MAX(YMAX(ENVELOPE(Transform(the_geom, '.$this->clientepsg.')))) AS maxy';
#    $sql.= " FROM ".$tablename." WHERE oid = '".$oid."';";
    $ret = $this->database->execSQL($sql, 4, 0);
		$rs = pg_fetch_array($ret[1]);
		$rect = ms_newRectObj();
    $rect->minx=$rs['minx']; 
    $rect->maxx=$rs['maxx'];
    $rect->miny=$rs['miny']; 
    $rect->maxy=$rs['maxy'];
    $randx=($rect->maxx-$rect->minx)*$border/100;
    $randy=($rect->maxy-$rect->miny)*$border/100;
    $rect->minx -= $randx;
    $rect->miny -= $randy;
    $rect->maxx += $randx;
    $rect->maxy += $randy;
    return $rect;
  }
  
  function pruefeEingabedaten($newpathwkt) {
    $ret[1]='';
    $ret[0]=0;
    if($newpathwkt == ''){
      $ret[1]='\nEs muss ein Polygon mit Flaecheninhalt beschrieben werden!';
      $ret[0]=1;
    }
    else{
    	$sql = "SELECT isvalid(SnapToGrid(geomfromtext('".$newpathwkt."'), 0.0001))";
    	$ret = $this->database->execSQL($sql, 4, 0);
    	$valid = pg_fetch_row($ret[1]);
			if($valid[0] == 'f'){
				$sql = "SELECT st_isvalidreason(SnapToGrid(geomfromtext('".$newpathwkt."'), 0.0001))";
				$ret = $this->database->execSQL($sql, 4, 0);
    		$reason = pg_fetch_row($ret[1]);
				$ret[1]='\nDie Geometrie des Polygons ist fehlerhaft und kann nicht gespeichert werden: \n'.$reason[0];
      	$ret[0]=1;
			}
    }
    return $ret; 
  }
  
  function eintragenFlaeche($umring, $oid, $tablename, $columnname){  	
		$sql = "UPDATE ".$tablename." SET ".$columnname." = Transform(GeometryFromText('".$umring."',".$this->clientepsg."),".$this->layerepsg.") WHERE oid = ".$oid;
		$ret = $this->database->execSQL($sql, 4, 1);
		if ($ret[0]) {
      # Fehler beim Eintragen in Datenbank
      $ret[1]='\nAuf Grund eines Datenbankfehlers konnte die Flaeche nicht eingetragen werden!\n'.$ret[1];
    }
    return $ret;
  }
	
	function getpolygon($oid, $tablename, $columnname, $extent){
		$sql = "SELECT assvg(Transform(".$columnname.",".$this->clientepsg."), 0, 8) AS svggeom, astext(Transform(".$columnname.",".$this->clientepsg.")) AS wktgeom FROM ".$tablename." WHERE oid = ".$oid;
		$ret = $this->database->execSQL($sql, 4, 0);
		$polygon = pg_fetch_array($ret[1]);
		$polygon['svggeom'] = transformCoordsSVG($polygon['svggeom']); 
		return $polygon;
	}	  
}
?>
