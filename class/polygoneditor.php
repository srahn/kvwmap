<?php
###################################################################
# kvwmap - Kartenserver f�r Kreisverwaltungen                     #
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
  	$sql ="SELECT st_xmin(bbox) AS minx,st_ymin(bbox) AS miny,st_xmax(bbox) AS maxx,st_ymax(bbox) AS maxy";
  	$sql.=" FROM (SELECT box2D(st_transform(".$columnname.", ".$this->clientepsg.")) as bbox";
  	$sql.=" FROM ".$tablename." WHERE oid = '".$oid."') AS foo";
    $ret = $this->database->execSQL($sql, 4, 0);
		$rs = pg_fetch_array($ret[1]);
		$rect = ms_newRectObj();
    $rect->minx=$rs['minx']; 
    $rect->maxx=$rs['maxx'];
    $rect->miny=$rs['miny']; 
    $rect->maxy=$rs['maxy'];
		if(defined('ZOOMBUFFER') AND ZOOMBUFFER > 0){
			if($this->clientepsg == 4326)$randx = $randy = ZOOMBUFFER/10000;
			else $randx = $randy = ZOOMBUFFER;
		}
		else{
			$randx=($rect->maxx-$rect->minx)*$border/100;
			$randy=($rect->maxy-$rect->miny)*$border/100;
		}		
    $rect->minx -= $randx;
    $rect->miny -= $randy;
    $rect->maxx += $randx;
    $rect->maxy += $randy;
    return $rect;
  }
  
  function pruefeEingabedaten($newpathwkt) {
    $ret[1]='';
    $ret[0]=0;
    if($newpathwkt != ''){
    	$sql = "SELECT st_isvalid(ST_SnapToGrid(st_geomfromtext('".$newpathwkt."'), 0.0001))";
    	$ret = $this->database->execSQL($sql, 4, 0);
    	$valid = pg_fetch_row($ret[1]);
			if($valid[0] == 'f'){
				$sql = "SELECT st_isvalidreason(ST_SnapToGrid(st_geomfromtext('".$newpathwkt."'), 0.0001))";
				$ret = $this->database->execSQL($sql, 4, 0);
    		$reason = pg_fetch_row($ret[1]);
				$ret[1]='\nDie Geometrie des Polygons ist fehlerhaft und kann nicht gespeichert werden: \n'.$reason[0];
      	$ret[0]=1;
			}
    }
    return $ret; 
  }
  
  function eintragenFlaeche($umring, $oid, $tablename, $columnname){  	
		if($umring == '')$sql = "UPDATE ".$tablename." SET ".$columnname." = NULL WHERE oid = ".$oid;
		else $sql = "UPDATE ".$tablename." SET ".$columnname." = st_transform(st_multi(st_geometryfromtext('".$umring."',".$this->clientepsg.")),".$this->layerepsg.") WHERE oid = ".$oid;
		$ret = $this->database->execSQL($sql, 4, 1);
		if(!$ret[0]){
			if(pg_affected_rows($ret[1]) == 0){
      	$ret[0] = 1;
      	$result = pg_fetch_row($ret[1]);
      	$ret[1]='Eintrag nicht erfolgreich.\n'.$result[0];
			}
		}
		else{
			$ret[1]='\nAuf Grund eines Datenbankfehlers konnte die Flaeche nicht eingetragen werden!\n';
		}
    return $ret;
  }
	
	function getpolygon($oid, $tablename, $columnname, $extent){
		$sql = "SELECT st_assvg(st_transform(st_union(".$columnname."),".$this->clientepsg."), 0, 8) AS svggeom, st_astext(st_transform(st_union(".$columnname."),".$this->clientepsg.")) AS wktgeom FROM ".$tablename;
		if($oid != NULL)$sql .= " WHERE oid = ".$oid;
		$ret = $this->database->execSQL($sql, 4, 0);
		$polygon = pg_fetch_array($ret[1]);
		$polygon['svggeom'] = transformCoordsSVG($polygon['svggeom']); 
		return $polygon;
	}	  
}
?>
