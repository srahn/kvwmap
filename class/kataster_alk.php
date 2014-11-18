<?
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
#  kataster_alkis.php  Klassenbibliothek für Klassen zum Kataster #
###################################################################

class grundbuch extends grundbuch_alkis{

  function getBuchungen($flurstkennz,$bvnr,$erbaurechtshinweise,$keine_historischen) {
    $ret=$this->database->getBuchungenFromGrundbuch('',$this->Bezirk,$this->Blatt,$keine_historischen);
    if ($ret[0]) {
      $ret[1]='Fehler bei der Datenbank abfrage<br>'.$ret[1];
    }
    else {
      # Zuordnen der Gemeinde und Gemarkungsnamen zu den Flurstücken.
      $buchungen=$ret[1];
      $anzBuchungen=count($buchungen);
      for($i=0;$i<$anzBuchungen;$i++) {
				$buchungenret=$this->database->getGemarkungName(substr($buchungen[$i]['flurstkennz'],0,6));
				$buchungen[$i]['gemkgname']=$buchungenret[1];
				$buchungen[$i]['flur']=intval(substr($buchungen[$i]['flurstkennz'],7,3));
				$buchungen[$i]['zaehler']=intval(substr($buchungen[$i]['flurstkennz'],11,5));
				$buchungen[$i]['nenner']=intval(substr($buchungen[$i]['flurstkennz'],17,3));
				$buchungen[$i]['flurstuecksnr']=$buchungen[$i]['zaehler'];
				if ($buchungen[$i]['nenner']>0) {
					$buchungen[$i]['flurstuecksnr'].='/'.$buchungen[$i]['nenner'];
				}
      }
      $ret[1]=$buchungen;
    }
    return $ret;
  }

}

class flurstueck extends flurstueck_alkis{

	function getFlurID() {
  	return substr($this->FlurstKennz,7,3);
  }

}


class Flur extends Flur_alkis {
  var $FlurID;
  var $database;
	
	function Flur_alkis($GemID,$GemkgID,$FlurID,$database) {
    # constructor
    global $debug;
    $this->debug=$debug;
    $this->GemID=$GemID;
    $this->GemkgID=$GemkgID;
    $this->FlurID=$FlurID;
    $this->database=$database;
    $this->LayerName=LAYERNAME_FLUR;
  }
	
	function getBezeichnungFromPosition($position, $epsgcode) {
    $this->debug->write("<p>kataster.php Flur->getBezeichnungFromPosition:",4);
    $sql ="SELECT gm.gemeindename,gm.gemeinde,g.gemkgname,g.gemkgschl,f.flur";
    $sql.=" FROM alb_v_gemarkungen AS g,alknflur AS f,alb_v_gemeinden AS gm, alkobj_e_fla AS fla";
    $sql.=" WHERE f.gemkgschl::integer=g.gemkgschl AND g.gemeinde=gm.gemeinde AND fla.objnr=f.objnr";
    $sql.=" AND ST_WITHIN(st_transform(st_geomfromtext('POINT(".$position['rw']." ".$position['hw'].")',".$epsgcode."), ".EPSGCODE."),fla.the_geom)";
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
	
}

?>