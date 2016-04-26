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

  function Converter($structDb, $contentDbConn) {
    $this->structDb = $structDb;
    $this->contentDbConn = $contentDbConn;
  }

  function say_hello($msg){
    $hallo = 'Hallo XPlan: <br>' . $msg;
    return $hallo;
  }

  function findRPPlanByKonvertierung($konvertierung) {
    $sql = "SELECT gml_id FROM gml_classes.rp_plan WHERE konvertierung_id == " . $konvertierung->get('id');
    $result = pg_query($this->contentDbConn, $sql);
    return pg_fetch_assoc($result)['gml_id'];
  }

  function convert($konvertierung) {
    $konv_id = $konvertierung->get('id');
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
  }
}

?>
