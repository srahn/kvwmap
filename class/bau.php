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
  	$baudata = $this->database->getbaudaten($searchvars);
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
  	
  	$this->baudata = $this->database->getbaudaten($searchvars);
  	return $searchvars;
  }
  
  function countbaudaten($searchvars){
  	$searchvars['withlimit'] = false;
  	$ret = $this->database->getbaudaten($searchvars);
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
  	if(ALKIS)$FlurstKennz = formatFlurstkennzALKIS($FlurstKennz);
  	return $FlurstKennz;
  }
  
  function readvorhaben(){
  	$this->vorhaben = $this->database->readvorhaben();
  }
  
  function readverfahrensart(){
  	$this->verfahrensart = $this->database->readverfahrensart();
  }
  
  function readaktualitaet(){
  	$this->aktualitaet = $this->database->readaktualitaet();
  }
  
  function checkformdaten($formvars){
  		return true;
  }
   
}

?>