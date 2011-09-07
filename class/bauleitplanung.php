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
# Klasse Bodenrichtwertzone #
#############################

class bauleitplanung {
    
  ################### Liste der Funktionen ########################################################################################################
  # bauleitplanung($database)
  # eintragenNeueFlaeche($umring,$vgrad)
  # pruefeEingabedaten
  ##################################################################################################################################################

  function bauleitplanung($database) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
  }
  
  function pruefeEingabedaten($newpathwkt,$email,$user) {
    #showAlert($pathx.'~'.$pathy.'~'.$vgrad.'~'.count(explode(',',$pathx)));
    $ret[1]='';
    $ret[0]=0;
    if ( $newpathwkt == ''){
      $ret[1]='\nEs muss ein Polygon mit Flaecheninhalt beschrieben werden!';
      $ret[0]=1;
    }
    if ($email==''){
      $ret[1].='\nGeben Sie eine korrekte Email-Adresse an!';
      $ret[0]=1;
    }
    return $ret; 
  }

  function eintragenNeueFlaeche($umring,$user,$hinweis,$bemerkung,$datum) { 
    $sql ="INSERT INTO bp_aenderungen (the_geom, username, hinweis, bemerkung, datum)";
    $sql.=" VALUES (GeometryFromText('".$umring."',".EPSGCODE."), '".$user."', '".$hinweis."', '".$bemerkung."', '".$datum."')";
    # echo $sql;
    $ret=$this->database->execSQL($sql,4, 1);
    if ($ret[0]) {
      # Fehler beim Eintragen in Datenbank
      $ret[1]='\nAuf Grund eines Datenbankfehlers konnte die Flaeche nicht eingetragen werden!\n'.$ret[1];
    }
    return $ret; 
  }
  
  function FlaecheLoeschen($id, $loeschuser, $loeschdatum){
  	$sql = "UPDATE bp_aenderungen SET loeschdatum = '".$loeschdatum."', loeschusername = '".$loeschuser."' WHERE id = ".$id;
  	# echo $sql;
    $ret=$this->database->execSQL($sql,4, 1);
    if ($ret[0]) {
      # Fehler beim Eintragen in Datenbank
      $ret[1]='\nAuf Grund eines Datenbankfehlers konnte die Flaeche nicht eingetragen werden!\n'.$ret[1];
    }
    return $ret;
  }
  
}
?>
