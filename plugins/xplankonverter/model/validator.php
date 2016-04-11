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
# Klasse Validator #
#############################

class Validator {

  function Validator($structDb, $contentDbConn) {
    global $debug;
    $this->debug=$debug;
    $this->structDb = $structDb;
    $this->contentDbConn = $contentDbConn;
  }

  function say_hello($msg){
    $hallo = 'Hallo XPlan: <br>' . $msg;
    return $hallo;
  }

  function doValidate($konvertierung) {
    if (true)
      return array('success' => 'OK');
    else
      return array('success' => 'ERROR', 'error' => 'Validierung fehlgeschlagen');
  }

  function validateKonvertierung($konvertierung,$success,$failure) {
    $result = $this->doValidate($konvertierung);
    if ($result['success'] == 'OK') {
      // status setzen
      $konvertierung->set('status', Konvertierung::$STATUS[4]);
      // success callback ausführen
     $success();
    } else {
      // status setzen
      $konvertierung->set('status', Konvertierung::$STATUS[3]);
      // error callback ausfuehren
     $failure($result['error']);
    }
    return;
  }
}

?>
