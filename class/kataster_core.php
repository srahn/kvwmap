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
#  kataster.php  Klassenbibliothek für Klassen zum Kataster       #
###################################################################

class Flur_core {
  var $FlurID;
  var $database;
  ###################### Liste der Funktionen ####################################
  #
  # function Flur($GemID,$GemkgID,$FlurID)  - Construktor
  # function getFlurListe($GemkgID,$FlurID,$order)
  # function getFlurListeByExtent($extent)
  # function getFlurstByPoint($point)
  # function getDataSourceName()
  # function getMER($layer)
  # function updateFluren()
  #
  ################################################################################

  function Flur_core($GemID,$GemkgID,$FlurID,$database) {
    # constructor
    global $debug;
    $this->debug=$debug;
    $this->GemID=$GemID;
    $this->GemkgID=$GemkgID;
    $this->FlurID=$FlurID;
    $this->database=$database;
  }
  
  function getBezeichnungFromPosition($position, $epsgcode) {
    $this->debug->write("<p>kataster.php Flur->getBezeichnungFromPosition:",4);
    $sql ="SELECT gm.gemeindename,gm.gemeinde,g.gemkgname,g.gemkgschl,f.flur";
    $sql.=" FROM alb_v_gemarkungen AS g,alknflur AS f,alb_v_gemeinden AS gm, alkobj_e_fla AS fla";
    $sql.=" WHERE f.gemkgschl::integer=g.gemkgschl AND g.gemeinde=gm.gemeinde AND fla.objnr=f.objnr";
    $sql.=" AND WITHIN(Transform(GeomFromText('POINT(".$position['rw']." ".$position['hw'].")',".$epsgcode."), ".EPSGCODE."),fla.the_geom)";
    #echo $sql;
    $ret=$this->database->execSQL($sql,4, 0);
    if ($ret[0]!=0) {
      $ret[1]='Fehler bei der Abfrage der Datenbank.'.$ret[1];
    }
    else {
      if (pg_num_rows($ret[1])>0) {
        $ret[1]=pg_fetch_array($ret[1]);
      }
    }
    return $ret;
  }

} # ende der Klasse flur

?>