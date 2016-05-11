<?php
###################################################################
# kvwmap - Kartenserver für Kreisverwaltungen                     #
###################################################################
# Lizenz                                                          #
#                                                                 # 
# Copyright (C) 2008  Peter Korduan                               #
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
# Klasse jagdkataster #
#############################

class anliegerbeitraege {
    
  ################### Liste der Funktionen ########################################################################################################
  # jagdkataster($database)
  ##################################################################################################################################################

  function anliegerbeitraege($database) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
  }
    
 
  
  function pruefeEingabedaten($newpathwkt) {
    $ret[1]='';
    $ret[0]=0;
    if ( $newpathwkt == ''){
      $ret[1]='\nEs muss ein Polygon mit Flaecheninhalt beschrieben werden!';
      $ret[0]=1;
    }
    return $ret; 
  }
  
  function eintragenNeueStrasse($umring){
    $sql = "SELECT st_IsValid(st_geometryfromtext('".$umring."', ".$this->clientepsg."))";
    $ret = $this->database->execSQL($sql, 4, 0);
    $valid = pg_fetch_array($ret[1]);
    if($valid[0] == 't'){
      $sql = "INSERT INTO anliegerbeitraege.anliegerbeitraege_strassen (the_geom)";
      $sql.= " VALUES(st_multi(st_transform(st_geometryfromtext('".$umring."', ".$this->clientepsg."), ".$this->layerepsg.")))";
      $ret = $this->database->execSQL($sql, 4, 1);
      if ($ret[0]) {
        # Fehler beim Eintragen in Datenbank
        $ret[1]='\nAuf Grund eines Datenbankfehlers konnte die Flaeche nicht eingetragen werden!\n'.$ret[1];
      }
    }
    else{
      # Fehlerhafte Geometrie
      $ret[0] = 1;
      $ret[1]='\nDie Flaeche konnte nicht eingetragen werden, da sie fehlerhaft ist!\n';
    }
    return $ret;
  }
  
  function eintragenNeueBereiche($umring){
    $sql = "SELECT st_IsValid(st_geometryfromtext('".$umring."', ".$this->clientepsg."))";
    $ret = $this->database->execSQL($sql, 4, 0);
    $valid = pg_fetch_array($ret[1]);
    if($valid[0] == 't'){
      $sql = "INSERT INTO anliegerbeitraege.anliegerbeitraege_bereiche (the_geom, flaeche) select * from (select bereich, round((amtlicheflaeche*st_area_utm(bereich, ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.")/st_area_utm(wkb_geometry, ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID."))::numeric, 2) as flaeche  from (select amtlicheflaeche, wkb_geometry, st_multi(st_transform(st_Intersection(st_transform(st_geometryfromtext('".$umring."', ".$this->clientepsg."), ".EPSGCODE_ALKIS."),f.wkb_geometry), ".$this->layerepsg.")) as bereich ";
      $sql.= "from alkis.ax_flurstueck as f "; 
      $sql.= "where st_transform(st_geometryfromtext('".$umring."', ".$this->clientepsg."), ".EPSGCODE_ALKIS.") && f.wkb_geometry) as foo ) as foofoo ";
      $sql.= "WHERE flaeche > 0 ";
      $sql.= "AND (GeometryType(bereich) = 'POLYGON' OR GeometryType(bereich) = 'MULTIPOLYGON')";
      $ret = $this->database->execSQL($sql, 4, 1);
      if ($ret[0]) {
        # Fehler beim Eintragen in Datenbank
        $ret[1]='\nAuf Grund eines Datenbankfehlers konnte die Flaeche nicht eingetragen werden!\n'.$ret[1];
      }
    }
    else{
      # Fehlerhafte Geometrie
      $ret[0] = 1;
      $ret[1]='\nDie Flaeche konnte nicht eingetragen werden, da sie fehlerhaft ist!\n';
    }
    return $ret;
  }
  
}
?>
