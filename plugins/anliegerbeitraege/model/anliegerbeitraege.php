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

  function __construct($database) {
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
  
  function eintragenNeueStrasse($umring, $stelle_id){
    $sql = "SELECT st_IsValid(st_geometryfromtext('".$umring."', ".$this->clientepsg."))";
    $ret = $this->database->execSQL($sql, 4, 0);
    $valid = pg_fetch_array($ret[1]);
    if($valid[0] == 't'){
      $sql = "INSERT INTO anliegerbeitraege.anliegerbeitraege_strassen (the_geom, stelle_id)";
      $sql.= " VALUES(st_multi(st_transform(st_geometryfromtext('".$umring."', ".$this->clientepsg."), ".$this->layerepsg.")), ".$stelle_id.")";
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
  
  function eintragenNeueBereiche($umring, $stelle_id){
    $sql = "SELECT st_IsValid(st_geometryfromtext('".$umring."', ".$this->clientepsg."))";
    $ret = $this->database->execSQL($sql, 4, 0);
    $valid = pg_fetch_array($ret[1]);
    if($valid[0] == 't'){
      $sql = "INSERT INTO anliegerbeitraege.anliegerbeitraege_bereiche (stelle_id, the_geom, flaeche, flurstueckskennzeichen) select * from (select ".$stelle_id.", bereich, round((amtlicheflaeche*st_area_utm(bereich, ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.")/st_area_utm(wkb_geometry, ".EPSGCODE_ALKIS.", ".EARTH_RADIUS."))::numeric, 2) as flaeche, flurstueckskennzeichen  from (select flurstueckskennzeichen, amtlicheflaeche, wkb_geometry, st_multi(st_transform(st_Intersection(st_transform(st_geometryfromtext('".$umring."', ".$this->clientepsg."), ".EPSGCODE_ALKIS."),f.wkb_geometry), ".$this->layerepsg.")) as bereich ";
      $sql.= "from alkis.ax_flurstueck as f "; 
      $sql.= "where f.endet IS NULL AND st_transform(st_geometryfromtext('".$umring."', ".$this->clientepsg."), ".EPSGCODE_ALKIS.") && f.wkb_geometry) as foo ) as foofoo ";
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
