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
#  bau.php  Klasse zur Baudatenabfrage       #
###################################################################


#-----------------------------------------------------------------------------------------------------------------

class Bauauskunft {
  ###################### Liste der Funktionen ####################################
  #
  # getbaudaten();
  # checkbaudaten();
  # updatedatabase();
  # checkfile();
  # 
  ################################################################################

  function Bauauskunft($baudatabase) {
    global $debug;
    $this->debug=$debug;
    $this->database = $baudatabase;
		$this->database->open();
  }
  
  function getbaudaten2($searchvars){
  	$baudata = $this->getbaudaten_db($searchvars);
  	return $baudata;
  }
  
  function getbaudaten($searchvars){
  	if($searchvars['flurstkennz'] != ''){
  		$explosion = explode('-', $searchvars['flurstkennz']);
  		$gemarkung = $explosion[0]; 
  		$flur = $explosion[1];
  		$flurstueck = $explosion[2];
  		// flur
  		$flur = ltrim($flur, "0");
  		// flurstueck
  		$explosion = explode('/',$flurstueck);
  		$zaehler = $explosion[0];
  		$nenner = $explosion[1];
  		$zaehler = ltrim($zaehler, "0");
  		$nenner = trim($nenner, "0");
  		$nenner = trim($nenner, ".");
  		if($nenner != ''){
  			$flurstueck = $zaehler.'/'.$nenner;
  		}
  		else{
  			$flurstueck = $zaehler;
  		}
  	
  		$searchvars['gemarkung'] = $gemarkung;
  		$searchvars['flur'] = $flur;
  		$searchvars['flurstueck'] = $flurstueck;
  	}
  	
  	$this->baudata = $this->getbaudaten_db($searchvars);
  	return $searchvars;
  }
	
	function getbaudaten_db($searchvars){
    if($searchvars['distinct'] == 1){
      $sql = 'SELECT DISTINCT feld1, feld2, feld3, feld8, feld11, feld20 FROM probaug.bau_akten WHERE 1 = 1';
    }
    else{
      $sql = 'SELECT * FROM probaug.bau_akten WHERE 1 = 1';
    }
    if($searchvars['jahr'] != ''){$sql .= " AND Feld1 = '".$searchvars['jahr']."'";}
    if($searchvars['obergruppe'] != ''){$sql .= " AND Feld2 = '".$searchvars['obergruppe']."'";}
    if($searchvars['nummer'] != ''){$sql .= " AND Feld3 = '".$searchvars['nummer']."'";}
    if($searchvars['vorhaben'] != ''){$sql .= " AND Feld8 LIKE '%".$searchvars['vorhaben']."%'";}
    if($searchvars['verfahrensart'] != ''){$sql .= " AND Feld9 LIKE '%".$searchvars['verfahrensart']."%'";}
    if($searchvars['gemarkung'] != ''){$sql .= " AND '13'||Feld12 = '".$searchvars['gemarkung']."'";}
    if($searchvars['flur'] != ''){$sql .= " AND trim(trim(leading '0' from Feld13)) = '".$searchvars['flur']."'";}
    if($searchvars['flurstueck'] != ''){$sql .= " AND trim(trim(leading '0' from Feld14)) LIKE '".$searchvars['flurstueck']."'";}
    if($searchvars['vorname'] != ''){$sql .= " AND Feld19 LIKE '%".$searchvars['vorname']."%'";}
    if($searchvars['nachname'] != ''){$sql .= " AND Feld20 LIKE '%".$searchvars['nachname']."%'";}
    if($searchvars['strasse'] != ''){$sql .= " AND Feld21 LIKE '%".$searchvars['strasse']."%'";}
    if($searchvars['hausnummer'] != ''){$sql .= " AND Feld22 LIKE '%".$searchvars['hausnummer']."%'";}
    if($searchvars['plz'] != ''){$sql .= " AND Feld23 LIKE '%".$searchvars['plz']."%'";}
    if($searchvars['ort'] != ''){$sql .= " AND Feld24 LIKE '%".$searchvars['ort']."%'";}
    if($searchvars['vonJahr'] != ''){$sql .= " AND Feld1 BETWEEN '".$searchvars['vonJahr']."' AND '".$searchvars['bisJahr']."'";}
    if($searchvars['withlimit'] == 'true'){$sql .= ' LIMIT '.$searchvars['limit'].' OFFSET '.$searchvars['offset'];}
    #echo $sql;
    $ret = $this->database->execSQL($sql, 4, 0);
    if($ret[0]==0){
      while($row = pg_fetch_array($ret[1])){
        $baudata[] = $row;
      }
    }
    return $baudata;
  }
	
  function readvorhaben(){
    $sql = 'SELECT vorhaben FROM probaug.bau_vorhaben';
    $ret = $this->database->execSQL($sql, 4, 0);
    if($ret[0]==0){
      while($row = pg_fetch_array($ret[1])){
        $vorhaben[] = $row;
      }
    }
    $this->vorhaben = $vorhaben;
  }

  function readverfahrensart(){
    $sql = 'SELECT verfahrensart FROM probaug.bau_verfahrensart';
    $ret = $this->database->execSQL($sql, 4, 0);
    if($ret[0]==0){
      while($row = pg_fetch_array($ret[1])){
        $verfahrensart[] = $row;
      }
    }
    $this->verfahrensart = $verfahrensart;
  }

  function readaktualitaet(){
  	$sql = "SET datestyle TO 'German';";
		$sql.= "SELECT max(datum::date) FROM tabelleninfo WHERE thema = 'probaug'"; 
    $ret = $this->database->execSQL($sql, 4, 0);
    if($ret[0]==0){
      $datum = pg_fetch_array($ret[1]);
    }
    $this->aktualitaet = $datum[0];
  }
  
  function countbaudaten($searchvars){
  	$searchvars['withlimit'] = false;
  	$ret = $this->getbaudaten($searchvars);
  	return count($ret);
  }
  
  function formatFlurstKennz($FlurstKennz){
  	$explosion = explode('-', $FlurstKennz);
  	$gem = trim($explosion[0]);
  	$flur = trim($explosion[1]);
  	$flurst = trim($explosion[2]);
  	// Flur
  	$flur = str_pad ($flur, 3, '0', STR_PAD_LEFT);
  	// Flurstück
  	$explosion = explode('/',$flurst);
  	$zaehler = $explosion[0];
  	$nenner = $explosion[1];
  	$zaehler = str_pad ($zaehler, 5, '0', STR_PAD_LEFT);
  	$explosion = explode('.',$nenner);
  	$vorkomma = $explosion[0];
  	$nachkomma = $explosion[1];
  	$vorkomma = str_pad ($vorkomma, 3, '0', STR_PAD_LEFT);
  	$nachkomma = str_pad ($nachkomma, 2, '0', STR_PAD_RIGHT);
  	$nenner = $vorkomma.'.'.$nachkomma;
  	
  	$FlurstKennz = $gem.'-'.$flur.'-'.$zaehler.'/'.$nenner;
  	$FlurstKennz = formatFlurstkennzALKIS($FlurstKennz);
  	return $FlurstKennz;
  }
        
  function checkformdaten($formvars){
  		return true;
  }
   
}

?>