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

class tif {
    
  ################### Liste der Funktionen ########################################################################################################
  # tif($map)
  ##################################################################################################################################################

  function tif($map, $resolution) {
    $this->map=$map;
    $this->resolution=$resolution;
  }
  
  function setmap(){
  	$breite = $this->map->extent->maxx - $this->map->extent->minx;
		$current_resolution = $breite/$this->map->width;
		str_replace(',','.',$this->resolution);
		$ratio = $current_resolution/$this->resolution;
		$this->map->set('width', $this->map->width*$ratio);
		$this->map->set('height', $this->map->height*$ratio);
		$this->final_resolution = $breite/$this->map->width;
		return $this->map;
  }
  
  function create_tif($karte){
  	$this->dateiname = explode('.', basename($karte));
    exec(IMAGEMAGICKPATH.'convert '.IMAGEPATH.basename($karte).' '.IMAGEPATH.$this->dateiname[0].'.tif');
    #echo IMAGEMAGICKPATH.'convert '.IMAGEPATH.basename($karte).' '.IMAGEPATH.$this->dateiname[0].'.tif';
    if(file_exists(IMAGEPATH.$this->dateiname[0].'.tif')){
    	$this->tifimage = IMAGEURL.$this->dateiname[0].'.tif';
    }
    else{
    	$this->tifimage = 'error'; 
    }
  }
  
  function create_tfw(){
  	$fp = fopen(IMAGEPATH.$this->dateiname[0].'.tfw', 'w');
  	fwrite($fp, $this->final_resolution.chr(10));
  	fwrite($fp, '0.00'.chr(10));
  	fwrite($fp, '0.00'.chr(10));
  	fwrite($fp, '-'.$this->final_resolution.chr(10));
  	fwrite($fp, $this->map->extent->minx.chr(10));
  	fwrite($fp, $this->map->extent->maxy.chr(10));
  	$this->tfwfile = IMAGEURL.$this->dateiname[0].'.tfw';
  }
 
}
?>
