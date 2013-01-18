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
# Klasse rok #
#############################

class rok {
    
  function rok($database) {
    global $debug;
    $this->debug=$debug;
    $this->database = $database;
  }
  
  function delete_bplan($oid){
  	$success = true;
  	$sql = "DELETE FROM tblb_plan_neu WHERE oid = ".$oid;
  	#echo $sql;
  	$ret = $this->database->execSQL($sql,4, 1);
    if ($ret[0]) {
     showAlert('Löschen fehlgeschlagen');
    }
    else{
    	showAlert('Löschen erfolgreich');
    }
  }
  
  function delete_fplan($oid){
  	$success = true;
  	$sql = "DELETE FROM tblf_plan WHERE oid = ".$oid;
  	#echo $sql;
  	$ret = $this->database->execSQL($sql,4, 1);
    if ($ret[0]) {
     showAlert('Löschen fehlgeschlagen');
    }
    else{
    	showAlert('Löschen erfolgreich');
    }
  }
 	
  
  function getExtentFromRokNrBplan($roknr, $border, $epsg) {
		$sql = "SELECT st_xmin(st_extent(st_transform(the_geom,".$epsg."))) AS minx,st_ymin(st_extent(st_transform(the_geom, ".$epsg."))) as miny,st_xmax(st_extent(st_transform(the_geom, ".$epsg."))) AS maxx,st_ymax(st_extent(st_transform(the_geom, ".$epsg."))) AS maxy FROM rok_edit.rok_geltungsbereiche WHERE roknr = '".$roknr."'";
		#echo $sql;
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
	
	function getExtentFromRokNrFplan($gkz, $border, $epsg) {
		$sql = "SELECT st_xmin(st_extent(st_transform(the_geom,".$epsg."))) AS minx,st_ymin(st_extent(st_transform(the_geom, ".$epsg."))) as miny,st_xmax(st_extent(st_transform(the_geom, ".$epsg."))) AS maxx,st_ymax(st_extent(st_transform(the_geom, ".$epsg."))) AS maxy FROM kataster.adm_gem09 WHERE gemnr = '".$gkz."'";
		#echo $sql;
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
}
	
?>
