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

class gpx {
    
  ################### Liste der Funktionen ########################################################################################################
  # dbf($database)
  ##################################################################################################################################################

  function gpx() {
    global $debug;
    $this->debug=$debug;
  }
  
  
	function gpx_import($formvars){
		$this->formvars = $formvars;
    if($_FILES['gpxfile']['name']){     # eine GPXdatei wurde ausgewählt
      $this->formvars['gpxfile'] = $_FILES['gpxfile']['name'];
      $folder = basename($_FILES['gpxfile']['name'], '.gpx').'/';
      if(file_exists(UPLOADPATH.$folder))exec('rm -R '.UPLOADPATH.$folder);											# altes Verzeichnis löschen
      mkdir(UPLOADPATH.$folder);
      $nachDatei = UPLOADPATH.$folder.$_FILES['gpxfile']['name'];
      if(move_uploaded_file($_FILES['gpxfile']['tmp_name'],$nachDatei)){
				#exec('gpx2shp '.$nachDatei);																														# über Programm gpx2shp (http://sourceforge.jp/projects/gpx2shp/downloads/13458/gpx2shp-0.69.tar.gz/)
				#$file = basename($_FILES['gpxfile']['name'], '.gpx').'_trk'.'.dbf';										#
				
				exec(OGR_BINPATH.'ogr2ogr -f "ESRI Shapefile" '.UPLOADPATH.$folder.' '.$nachDatei);			# über ogr2ogr
				#echo OGR_BINPATH.'ogr2ogr -f "ESRI Shapefile" '.UPLOADPATH.$folder.' '.$nachDatei;
				$file = 'tracks.dbf';																																		#
        if(file_exists(UPLOADPATH.$folder.$file)){
        	$this->formvars['dbffile'] = UPLOADPATH.$folder.$file;
        }
      }
    }
  }
  
  function gpx_import_importieren($formvars, $database){
  	$this->formvars = $formvars;
    if(file_exists($this->formvars['dbffile'])){      
      $command = POSTGRESBINPATH.'shp2pgsql '.$this->formvars['table_option'].' ';
      if($this->formvars['gist'] != ''){
        $command .= '-I ';
      }
      $command .= ' -s 4326 ';
      $command.= $this->formvars['dbffile'].' '.$this->formvars['table_name'].' > '.UPLOADPATH.$this->formvars['table_name'].'.sql'; 
      exec($command);
      #echo $command;
      exec(POSTGRESBINPATH.'psql -f '.UPLOADPATH.$this->formvars['table_name'].'.sql '.$database->dbName.' '.$database->user);
      //echo POSTGRESBINPATH.'psql -f '.UPLOADPATH.$this->formvars['table_name'].'.sql '.$database->dbName.' '.$database->user;
      $sql = 'SELECT count(*) FROM '.$this->formvars['table_name'];
      $ret = $database->execSQL($sql,4, 0);
      if (!$ret[0]) {
        $count = pg_fetch_array($ret[1]);
        $alert = 'Import erfolgreich.';
        if($this->formvars['table_option'] == '-c'){
        	$alert.= ' Die Tabelle '.$this->formvars['table_name'].' wurde erzeugt.';
        }
        $alert .= ' Die Tabelle enthält jetzt '.$count[0].' Datensätze.';
        showAlert($alert);
      }
      else{
        showAlert('Import fehlgeschlagen.');
      }
    }
  }
   
 
 
}
?>
