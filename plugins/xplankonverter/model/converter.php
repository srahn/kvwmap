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
# Klasse Converter #
#############################

class Converter {
  
  static $STATUS = array('in Bearbeitung', 'Konvertierung offen', 'Konvertierung abgeschlossen');
    
  function Converter($structDb, $contentDbConn) {
    global $debug;
    $this->debug=$debug;
    $this->structDb = $structDb;
    $this->contentDbConn = $contentDbConn;
  }

  function say_hello($msg){
    $hallo = 'Hallo XPlan: <br>' . $msg;
    return $hallo;
  }
  
  function getConversions() {
    $sql = "
        SELECT 
          oid AS id,
          nspname AS name,
          'Ruleset A' AS ruleset,
          0 AS status
        FROM pg_namespace WHERE nspowner = 16385
    ";
    $result = pg_query($this->contentDbConn, $sql);
    $data = array();
    while ($konvertierung = pg_fetch_assoc($result)) {
      $data[] = $konvertierung;
    }
    return $data;
//     return array(
//         array('id' => 1, 'name' => 'Konvertierung 1', 'ruleset' => 'Ruleset A', 'status' => 0),
//         array('id' => 2, 'name' => 'Konvertierung 2', 'ruleset' => 'Ruleset B', 'status' => 1),
//         array('id' => 3, 'name' => 'Konvertierung 3', 'ruleset' => 'Ruleset B', 'status' => 0),
//         array('id' => 4, 'name' => 'Konvertierung 4', 'ruleset' => 'Ruleset A', 'status' => 2),
//     );
  }
}
  
?>
