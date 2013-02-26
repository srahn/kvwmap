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
  
  function delete_bplan($plan_id){
  	$sql = "DELETE FROM b_plan_stammdaten WHERE plan_id = ".$plan_id.';';
  	$sql.= "DELETE FROM b_plan_gebiete WHERE plan_id = ".$plan_id.';';
  	$sql.= "DELETE FROM b_plan_sondergebiete WHERE plan_id = ".$plan_id.';';
  	#echo $sql;
  	$ret = $this->database->execSQL($sql,4, 1);
    if ($ret[0]) {
     showAlert('Löschen fehlgeschlagen');
    }
    else{
    	showAlert('Löschen erfolgreich');
    }
  }
  
  function copy_bplan($plan_id){
  	$sql = "BEGIN;";
  	$sql.= "INSERT INTO b_plan_stammdaten (gkz, art, pl_nr, gemeinde_alt, geltungsbereich, bezeichnung, aktuell, lfd_rok_nr, aktenzeichen, kap_gemziel, kap_nachstell, datumeing, datumzust, datumabl, datumgenehm, datumbeka, datumaufh, erteilteaufl, ert_hinweis, ert_bemerkungen) ";
  	$sql.= "SELECT gkz, art, pl_nr, gemeinde_alt, geltungsbereich, bezeichnung, aktuell, lfd_rok_nr, aktenzeichen, kap_gemziel, kap_nachstell, datumeing, datumzust, datumabl, datumgenehm, datumbeka, datumaufh, erteilteaufl, ert_hinweis, ert_bemerkungen FROM b_plan_stammdaten ";
  	$sql.= "WHERE plan_id = ".$plan_id;
  	$ret = $this->database->execSQL($sql,4, 1);
  	$new_oid = pg_last_oid($ret[1]);
  	$sql = "SELECT plan_id FROM b_plan_stammdaten WHERE oid = ".$new_oid;
  	$ret = $this->database->execSQL($sql, 4, 0);
		$rs = pg_fetch_array($ret[1]);
		$sql = "INSERT INTO b_plan_gebiete SELECT ".$rs['plan_id'].", gebietstyp, flaeche, kap_gemziel, kap_nachstell, konkretisierung FROM b_plan_gebiete WHERE plan_id = ".$plan_id.";";
		$sql.= "INSERT INTO b_plan_sondergebiete SELECT ".$rs['plan_id'].", gebietstyp, flaeche, kap1_gemziel, kap1_nachstell, kap2_gemziel, kap2_nachstell, konkretisierung FROM b_plan_sondergebiete WHERE plan_id = ".$plan_id.";";
		$sql.= "COMMIT;"; 
		$ret = $this->database->execSQL($sql, 4, 0);
  	if ($ret[0]) {
     showAlert('Kopieren fehlgeschlagen');
    }
    else{
    	showAlert('Kopieren erfolgreich');
    }
    return $new_oid;
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
