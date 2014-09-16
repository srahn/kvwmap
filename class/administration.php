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

class administration{

	var $database;
	var $migration_logs;
	var $migration_files;
	var $migrations_to_execute;
	
	function administration($database, $pgdatabase){
		$this->database = $database;
		$this->pgdatabase = $pgdatabase;
	}
	
	function get_migration_logs(){
		$sql = "SELECT * FROM migrations";
		$queryret=$this->database->execSQL($sql,4, 0);
    if($queryret[0]) {
      echo '<br>Fehler bei der Abfrage der Tabelle migrations.<br>';
    }
    else {
      while($rs=mysql_fetch_array($queryret[1])){
				$migrations[$rs['component']][$rs['type']][$rs['filename']] = 1;
			}
		}
		return $migrations;
	}
	
	function get_migration_files(){
		global $kvwmap_plugins;
		$migrations['kvwmap']['mysql'] = array_diff(scandir(LAYOUTPATH.'db/mysql/schema'), array('.', '..'));
		$migrations['kvwmap']['postgresql'] = array_diff(scandir(LAYOUTPATH.'db/postgresql/schema'), array('.', '..'));
		for($i = 0; $i < count($kvwmap_plugins); $i++){
			$path = PLUGINS.$kvwmap_plugins[$i].'/db/mysql/schema';
			if(file_exists($path))$migrations[$kvwmap_plugins[$i]]['mysql'] = array_diff(scandir($path), array('.', '..'));
			$path = PLUGINS.$kvwmap_plugins[$i].'/db/postgresql/schema';
			if(file_exists($path))$migrations[$kvwmap_plugins[$i]]['postgresql'] = array_diff(scandir($path), array('.', '..'));
		}
		return $migrations;
	}
	
	function get_database_status(){
		$this->migrations_to_execute['mysql'] = array();
		$this->migrations_to_execute['postgresql'] = array();
		$this->migration_logs = $this->get_migration_logs();
		$this->migration_files = $this->get_migration_files();
		foreach($this->migration_logs as $component => $log_component_migrations){
			foreach($this->migration_files[$component] as $type => $component_type_migrations){
				foreach($component_type_migrations as $file){
					if($log_component_migrations[$type][$file] != 1)$this->migrations_to_execute[$type][$component][] = $file;
				}
			}
		}
	}
	
	function update_databases(){
		foreach($this->migrations_to_execute['mysql'] as $component => $component_migration){
			if($component == 'kvwmap')$prepath = LAYOUTPATH; else $prepath = PLUGINS.$component.'/';
			foreach($component_migration as $file){
				$filepath = $prepath.'db/mysql/schema/'.$file;
				$queryret = $this->database->exec_file($filepath);
				if($queryret[0]){
					echo $queryret[1].'<br>Fehler beim Ausführen von migration-Datei: '.$filepath.'<br>';
				}
				else{
					$sql = "INSERT INTO `migrations` (`component`, `type`, `filename`) VALUES ('".$component."', 'mysql', '".$file."');";
					$queryret=$this->database->execSQL($sql,4, 0);
				}				
			}
		}
		foreach($this->migrations_to_execute['postgresql'] as $component => $component_migration){
			if($component == 'kvwmap')$prepath = LAYOUTPATH; else $prepath = PLUGINS.$component.'/';
			foreach($component_migration as $file){
				$filepath = $prepath.'db/postgresql/schema/'.$file;
				$sql = file_get_contents($filepath);
				if($sql != ''){
					$queryret=$this->pgdatabase->execSQL($sql,4, 0);
					if($queryret[0]){
						echo $queryret[1].'<br>Fehler beim Ausführen von migration-Datei: '.$filepath.'<br>';
					}
					else{
						$sql = "INSERT INTO `migrations` (`component`, `type`, `filename`) VALUES ('".$component."', 'postgresql', '".$file."');";
						$queryret=$this->database->execSQL($sql,4, 0);
					}
				}
			}
		}
	}

}

?>