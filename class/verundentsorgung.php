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

class versiegelungsflaeche {
    
  ################### Liste der Funktionen ########################################################################################################
  # versiegelungsflaeche($database)
  # copyZonenToNewStichtag
  # eintragenNeueFlaeche($umring,$vgrad)
  # pruefeEingabedaten
  ##################################################################################################################################################

  function versiegelungsflaeche($database) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
  }
  
  function pruefeEingabedaten($newpathwkt,$vgrad) {
    #showAlert($pathx.'~'.$pathy.'~'.$vgrad.'~'.count(explode(',',$pathx)));
    $ret[1]='';
    $ret[0]=0;
    if ( $newpathwkt == ''){
      $ret[1]='\nEs muss ein Polygon mit Flaecheninhalt beschrieben werden!';
      $ret[0]=1;
    }
    if ($vgrad==''){
      $ret[1].='\nDas Formularobjekt hat keinen Versiegelungsgrad zugewiesen!';
      $ret[0]=1;
    }
    return $ret; 
  }

  function eintragenNeueFlaeche($umring,$vgrad) { 
    $this->debug->write('Einfügen der Daten zu einer Richtwertzone',4);
    $sql ="INSERT INTO ve_versiegelung (grad,the_geom)";
    $sql.=" VALUES ('".$vgrad."',";
    $sql.="GeometryFromText('".$umring."',".EPSGCODE."))";
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
