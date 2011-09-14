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
#############################
# Klasse dbf #
#############################

class gps {
    
  ################### Liste der Funktionen ########################################################################################################
  # gps($database)
  ##################################################################################################################################################

  function gps($source) {
    global $debug;
    $this->debug=$debug;
    $this->source=$source;
    $this->sentence_type='';
    $this->lon=12; # initialer Wert für die Länge
    $this->lat=54; # initialer Wert für die Breite
  }
 	
 	function readPosition(){
 		# lese GPS-Datensätze aus der Resource, die in GPSPATH angegeben ist
		$data_sets = file($this->source);
		#var_dump($data_sets);
    if (substr($this->source,0,7)=='http://' OR substr($this->source,0,8)=='https://') {
    	#echo 'Abfrage vom Server<br>';
    	# Es handelt sich um einen Dienst, bei dem nur eine zeile angegeben ist
    	$linenumber=0;
    }
    else {
    	#echo 'Abfrage aus Datei<br>';
    	# Es handelt sich um eine Datei in der mehrere Zeilen gelogt sind. Nur die letzte Zeile wird entnommen
    	$linenumber=count($data_sets)-2;
    }
    #echo 'linenumber:'.$linenumber.'<br>';
    $next_line = true;
    while($next_line == true AND $linenumber > 0){
	    # Extrahieren der Zeile
	   	$line=$data_sets[$linenumber];
	   	#echo 'line:'.$line.'<br>';
	   	# Separieren in Array nach Kommas
	    $parts=explode(',',$line);
	    $this->sentence_type=substr($parts[0],-3);
	    #echo 'Sentence Type:'.$this->sentence_type;
	    switch ($this->sentence_type) {
	    	case 'GGA' : {
		      # Extrahieren der Koordinaten und Umrechnen in Dezimalgrad
		      $this->lon=$this->degMin2deg(substr($parts[4],0,3),substr($parts[4],3));
		      $this->lat=$this->degMin2deg(substr($parts[2],0,2),substr($parts[2],2));
		      $next_line = false;
	    	} break;
	    	case 'RMC' : {
		      # Extrahieren der Koordinaten und Umrechnen in Dezimalgrad
		      $this->lon=$this->degMin2deg(substr($parts[5],0,3),substr($parts[5],3));
		      $this->lat=$this->degMin2deg(substr($parts[3],0,2),substr($parts[3],2));
		      $next_line = false;
	    	} break;
	    	default : {
	    		$next_line = true;
	    		$linenumber--;
	    	}
	    }
    }
    #echo '<br>lon:'.$this->lon;
    #echo '<br>lat:'.$this->lat;    
 	}
 	
 	function degMin2deg($deg,$min) {
 		return floatval(trim($deg,"0"))+floatval(trim($min, "0"))/60;
 	}
}
?>
