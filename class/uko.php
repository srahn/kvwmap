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
# Klasse uko #
#############################

class uko{
    

  function uko($database) {
    global $debug;
    $this->debug=$debug;
	$this->srid = $this->getsrid($database);
  }
  
  function getsrid($database){
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
  
  function uko_export($formvars, $layerdb){
    $folder = 'uko_Export_'.$formvars['selected_layer_id'].rand(0,10000);
    mkdir(IMAGEPATH.$folder);                       # Ordner erzeugen
    $sql = "select st_numgeometries(".$formvars['layer_columnname'].") from ".$formvars['layer_tablename']." WHERE oid = ".$formvars['oid'];
    $ret = $layerdb->execSQL($sql,4, 1);
    if(!$ret[0]){
    	$rs=pg_fetch_array($ret[1]);
    	if($rs[0] == ''){
    		$rs[0] = 1;
    		$polygon = true;
    	}
    	$numgeom = $rs[0];
    }
    for($i = 1; $i <= $numgeom; $i++){
    	if($polygon){
    		$geom = $formvars['layer_columnname'];
    	}
    	else{
    		$geom = 'st_GeometryN('.$formvars['layer_columnname'].', '.$i.')';
    	}
	    $sql = "SELECT replace(replace(st_astext(st_pointn(st_ExteriorRing($geom), generate_series(1,st_npoints(".$formvars['layer_columnname'].")))), 'POINT(', 'KOO '), ')', '') as coords";
			$sql.= " From ".$formvars['layer_tablename']." WHERE oid = ".$formvars['oid'];
	    #echo $sql;
	    $ret = $layerdb->execSQL($sql,4, 1);
	    if (!$ret[0]) {
	      while ($rs=pg_fetch_array($ret[1])) {
	        $result[$i][] = $rs;
	      }
	    }
	    $uko = 'TYP UPO 1'.chr(10);
	    # Daten schreiben
	    for($j = 0; $j < count($result[$i]); $j++){
		  	$uko .= $result[$i][$j]['coords'];
	      $uko .= chr(10);
	    }
	    $filenames[$i-1] = rand(0, 1000000).'.uko';
			$fp = fopen(IMAGEPATH.$folder.'/'.$filenames[$i-1], 'w');
			fwrite($fp, $uko);
			fclose($fp);
    }
    if(count($filenames) > 1){
      exec(ZIP_PATH.' '.IMAGEPATH.$folder.' '.IMAGEPATH.$folder.'/*'); # Ordner zippen
      #echo ZIP_PATH.' '.IMAGEPATH.$folder.' '.IMAGEPATH.$folder.'/*';
      ob_end_clean();
      header("Content-type: application/zip");
      header("Content-Disposition: attachment; filename=".$folder.".zip");
      header('Expires: 0');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Pragma: public');
      readfile(IMAGEPATH.$folder.'.zip');
    }
    else{
      ob_end_clean();
      header("Content-type: text/uko");
      header("Content-Disposition: attachment; filename=".$filenames[0]);
      header('Expires: 0');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Pragma: public');
   	  readfile(IMAGEPATH.$folder.'/'.$filenames[0]);
    } 
  }
 
 
}
?>
