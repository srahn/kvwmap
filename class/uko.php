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
# Klasse uko #
#############################

class uko{
    

  function uko($database) {
    global $debug;
    $this->debug=$debug;
	$this->srid = $this->getsrid($database);
  }
  
  function get_ukotable_srid($database){
		$sql = "select srid from geometry_columns where f_table_name = 'uko_polygon'";
		$ret = $database->execSQL($sql,4, 1);
		if(!$ret[0]){
			$rs=pg_fetch_array($ret[1]);
			return $rs[0];
		}
  }
  
	function uko_importieren($formvars, $username, $userid, $database){
		$_files = $_FILES;
		$this->formvars = $formvars;
		if($_files['ukofile']['name']){     # eine UKOdatei wurde ausgewählt
		  $this->formvars['ukofile'] = $_files['ukofile']['name'];
		  $nachDatei = UPLOADPATH.$_files['ukofile']['name'];
		  if(move_uploaded_file($_files['ukofile']['tmp_name'],$nachDatei)){
			$ukofile = file($nachDatei);
			for($i = 0; $i < count($ukofile); $i++){
				if(strpos($ukofile[$i], 'KOO') !== false){
					$coords[] = substr($ukofile[$i], 4);
				}
			}
			$polygon = 'MULTIPOLYGON((('.implode(',', $coords).')))';
			$sql = "INSERT INTO uko_polygon (username, userid, dateiname, the_geom) VALUES('".$username."', ".$userid.", '".$_files['ukofile']['name']."', st_geomfromtext('".$polygon."', ".$this->srid.")) RETURNING id";
			$ret = $database->execSQL($sql,4, 1);
			if ($ret[0])$this->success = false;
			else {
				$this->success = true;
				$rs=pg_fetch_array($ret[1]);
				return $rs[0];
			}
		  }
		}
	}
  
 
 
}
?>
