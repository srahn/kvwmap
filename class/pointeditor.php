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
#############################
# Klasse Pointeditor #
#############################

class pointeditor {

  function pointeditor($database, $layerepsg, $clientepsg) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
    $this->clientepsg = $clientepsg;
    $this->layerepsg = $layerepsg;
  }
  
  function pruefeEingabedaten($locx, $locy) {
    $ret[1]='';
    $ret[0]=0;
    return $ret; 
  }
  
  function eintragenPunkt($pointx, $pointy, $oid, $tablename, $columnname, $dimension){
		if($pointx == '')$sql = "UPDATE ".$tablename." SET ".$columnname." = NULL WHERE oid = ".$oid;
		else{
			if($dimension == 3){  	
				$sql = "UPDATE ".$tablename." SET ".$columnname." = st_transform(St_GeomFromText('POINT(".$pointx." ".$pointy." 0)',".$this->clientepsg."),".$this->layerepsg.") WHERE oid = ".$oid;
			}
			else{
				$sql = "UPDATE ".$tablename." SET ".$columnname." = st_transform(St_GeomFromText('POINT(".$pointx." ".$pointy.")',".$this->clientepsg."),".$this->layerepsg.") WHERE oid = ".$oid;
			}
		}
		$ret = $this->database->execSQL($sql, 4, 1);
		if ($ret[0]) {
      # Fehler beim Eintragen in Datenbank
      $ret[1]='\nAuf Grund eines Datenbankfehlers konnte die Flaeche nicht eingetragen werden!\n'.$ret[1];
    }
    return $ret;
  }
	
	function getpoint($oid, $tablename, $columnname, $angle_column){
		$sql = "SELECT st_x(st_transform(".$columnname.",".$this->clientepsg.")) AS pointx, st_y(st_transform(".$columnname.",".$this->clientepsg.")) AS pointy ";
		if($angle_column != '')$sql.= ", ".$angle_column." as angle ";
		$sql.= "FROM ".$tablename." WHERE oid = ".$oid;
		$ret = $this->database->execSQL($sql, 4, 0);
		$point = pg_fetch_array($ret[1]);
		return $point;
	}  
}
?>
