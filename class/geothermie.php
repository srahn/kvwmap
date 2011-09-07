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
###################
# Klasse Geothermie #
###################

class geothermie {
    
  ################### Liste der Funktionen ########################################################################################################
  # geothermie($database)
  # eintragenNeueAbfrage($user_id,$flurstkennz,$entzugsleistung_soll)
  ##################################################################################################################################################

  function geothermie($database) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
  }

  function eintragenNeueAbfrage($user_id,$flurstkennz,$entzugsleistung_soll) {
    # kann noch nicht genutzt werden. Erst Geometriespalte anlegen und Geometry übergeben.
    $datum=date('Y-m-d',time());
    $this->debug->write('Einfügen der Metadaten zu einer neuen Geothermieabfrage',4);
    $sql ="INSERT INTO gt_abfragen (user_id,flstkennz,entzugsleistung_soll,datum,the_geom)";
    $sql.=" VALUES (".$user_id.",".$flurstkennz.",'".$entzugsleistung_soll."','".$datum."'";
    $sql.=",GeometryFromText('".$umring."',28403))";
    $ret=$this->database->execSQL($sql,4, 1);
    if ($ret[0]) {
      # Fehler beim Eintragen in Datenbank
      $ret[1]='Auf Grund eines Datenbankfehlers konnte die Anfrage nicht eingetragen werden!'.$ret[1];
    }
    return $ret; 
  }
  
  function eintragenEWSbohrpunkte() {
  }
}
?>
