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
# Klasse dbf #
#############################

class dbf {
    
  ################### Liste der Funktionen ########################################################################################################
  # dbf($database)
  ##################################################################################################################################################

  function dbf() {
    global $debug;
    $this->debug=$debug;
  }
 	
 	function get_dbf_header($dbfname) {
		$fdbf = fopen($dbfname,'r');
		$dbfhdrarr = array();
		$buff32 = array();
		$i = 1;
		$goon = true;
		
		while ($goon) {
			if (!feof($fdbf)) {
				$buff32 = fread($fdbf,32);
				if ($i > 1) {
					if (substr($buff32,0,1) == chr(13)){
						$goon = false;
					}
					else{
						$pos = strpos(substr($buff32,0,10),chr(0));
						$pos = ($pos == 0?10:$pos);
						$fieldname = strtolower(substr($buff32,0,$pos));
						$fieldtype = substr($buff32,11,1);
						$fieldlen = ord(substr($buff32,16,1));
						$fielddec = ord(substr($buff32,17,1));
						array_push($dbfhdrarr, array($fieldname,$fieldtype,$fieldlen,$fielddec));
					}
				}
	      $i++;
			} 
			else{
	    	$goon = false;
	    }
		}
		fclose($fdbf);
		return($dbfhdrarr);
	}
	
	function get_sql_types($dbf_header){
		for($i = 0; $i < count($dbf_header); $i++){
	  	switch($dbf_header[$i][1]){
				case 'C' : {
			    $dbf_header[$i]['type'] = 'varchar('.$dbf_header[$i][2].')';
			  } break;
			  case 'L' : {
			    $dbf_header[$i]['type'] = 'boolean';
			  } break;
			  case 'D' : {
			    $dbf_header[$i]['type'] = 'date';
			  } break;
			  case 'N' : {
			    if($dbf_header[$i][3] > 0){
			    	if($dbf_header[$i][2] > 18){
			    		$dbf_header[$i]['type'] = 'numeric';
			    	}
			    	else{
			    		$dbf_header[$i]['type'] = 'float8';
			    	}
			    }
			    else{
			    	if($dbf_header[$i][2] < 6){
			    		$dbf_header[$i]['type'] = 'int2';
			    	}
			    	elseif($dbf_header[$i][2] < 11){
			    		$dbf_header[$i]['type'] = 'int4';
			    	}
			    	elseif($dbf_header[$i][2] < 20){
			    		$dbf_header[$i]['type'] = 'int8';
			    	}
			    	else{
			    		$dbf_header[$i]['type'] = 'numeric('.$dbf_header[$i][2].',0)';
			    	}
			    }
			  } break;
			}
  	}
  	return $dbf_header;	
	}
 
}
?>
