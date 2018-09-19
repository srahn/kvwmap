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

function compare_migration_filenames($a, $b){
	if ($a['file'] == $b['file']) {
			return 0;
	}
	return ($a['file'] < $b['file']) ? -1 : 1;
}

class administration{

	var $database;
	var $config_params;
	var $migration_logs;
	var $migration_files;
	var $migrations_to_execute;
	
	function administration($database, $pgdatabase){
		$this->database = $database;
		$this->pgdatabase = $pgdatabase;
	}
	
	function get_migration_logs(){
		$migrations = array();
		$sql = "SELECT * FROM migrations";
		$result=$this->database->execSQL($sql,0, 0);
    if($result[0]) {
      #echo '<br>Fehler bei der Abfrage der Tabelle migrations.<br>'; 	// bei Neuinstallation gibt es diese Tabelle noch nicht
    }
    else {
      while($rs=mysql_fetch_array($result[1])){
				$migrations[$rs['component']][$rs['type']][$rs['filename']] = 1;
			}
		}
		return $migrations;
	}
	
	function get_schema_migration_files(){
		global $kvwmap_plugins;
		$migrations['kvwmap']['mysql'] = array_diff(scandir(LAYOUTPATH.'db/mysql/schema'), array('.', '..'));
		sort($migrations['kvwmap']['mysql']);
		$migrations['kvwmap']['postgresql'] = array_diff(scandir(LAYOUTPATH.'db/postgresql/schema'), array('.', '..'));
		sort($migrations['kvwmap']['postgresql']);
		for($i = 0; $i < count($kvwmap_plugins); $i++){
			$path = PLUGINS.$kvwmap_plugins[$i].'/db/mysql/schema';
			if(file_exists($path)){
				$migrations[$kvwmap_plugins[$i]]['mysql'] = array_diff(scandir($path), array('.', '..'));
				sort($migrations[$kvwmap_plugins[$i]]['mysql']);
			}
			$path = PLUGINS.$kvwmap_plugins[$i].'/db/postgresql/schema';
			if(file_exists($path)){
				$migrations[$kvwmap_plugins[$i]]['postgresql'] = array_diff(scandir($path), array('.', '..'));
				sort($migrations[$kvwmap_plugins[$i]]['postgresql']);
			}
		}
		return $migrations;
	}
	
	function get_seed_files(){
		global $kvwmap_plugins;
		for($i = 0; $i < count($kvwmap_plugins); $i++){
			$path = PLUGINS.$kvwmap_plugins[$i].'/db/mysql/data';
			if(file_exists($path))$seeds[$kvwmap_plugins[$i]]['mysql'] = array_diff(scandir($path), array('.', '..'));
		}
		return $seeds;
	}
	
	function get_database_status(){
		$this->migrations_to_execute['mysql'] = array();
		$this->migrations_to_execute['postgresql'] = array();
		$this->seeds_to_execute['mysql'] = array();
		$this->seeds_to_execute['postgresql'] = array();
		$this->migration_logs = $this->get_migration_logs();
		$this->schema_migration_files = $this->get_schema_migration_files();
		$this->seed_files = $this->get_seed_files();
		foreach($this->schema_migration_files as $component => $file_component_migrations){
			foreach($file_component_migrations as $type => $file_component_type_migrations){
				foreach($file_component_type_migrations as $file){
					if($this->migration_logs[$component][$type][$file] != 1)$this->migrations_to_execute[$type][$component][] = $file;
				}
			}
		}
		if($this->seed_files != ''){
			foreach($this->seed_files as $component => $file_component_seeds){
				foreach($file_component_seeds as $type => $file_component_type_seeds){
					foreach($file_component_type_seeds as $file){
						if($this->migration_logs[$component][$type][$file] != 1)$this->seeds_to_execute[$type][$component][] = $file;
					}
				}
			}
		}
	}
	
	function update_databases(){
		foreach($this->migrations_to_execute['mysql'] as $component => $component_migration) {
			foreach($component_migration as $file){
				$migration['component'] = $component;
				$migration['file'] = $file;
				$my_migrations[] = $migration;
			}
		}

		foreach($this->migrations_to_execute['postgresql'] as $component => $component_migration) {
			foreach($component_migration as $file){
				$migration['component'] = $component;
				$migration['file'] = $file;
				$pg_migrations[] = $migration;
			}
		}
		$this->execute_migrations('postgresql', $pg_migrations);
		$this->execute_migrations('mysql', $my_migrations);

		foreach($this->seeds_to_execute['mysql'] as $component => $component_seed){
			$prepath = PLUGINS.$component.'/';
			foreach($component_seed as $file){
				$filepath = $prepath.'db/mysql/data/'.$file;
				$connection = 'user='.$this->pgdatabase->user.' password='.$this->pgdatabase->passwd.' dbname='.$this->pgdatabase->dbName;
				if($this->pgdatabase->host != '')$connection .= ' host='.$this->pgdatabase->host;
				$result = $this->database->exec_commands(file_get_contents($filepath), 'user=xxxx password=xxxx dbname=kvwmapsp', $connection, true); # replace known constants
				if($result[0]){
					echo $result[1].'<br>Fehler beim Ausführen von seed-Datei: '.$filepath.'<br>';
				}
				else{
					$sql = "
						INSERT INTO `migrations`
							(`component`, `type`, `filename`)
						VALUES ('" . $component . "', 'mysql', '" . $file . "');
					";
					#echo '<p>Register MySQL migration for component ' . $component . ' with sql: <br>' . $sql;
					$result=$this->database->execSQL($sql,0, 0);
				}
			}
		}
	}

	function execute_migrations($database_type, $migrations){
		if ($migrations != NULL) {
			usort($migrations, 'compare_migration_filenames');		# sortieren, damit die Migrationen in der richtigen Reihenfolge ausgeführt werden
			foreach($migrations as $migration){
				$component = $migration['component'];
				$file = $migration['file'];
				if($component == 'kvwmap')$prepath = LAYOUTPATH; else $prepath = PLUGINS.$component.'/';
				$filepath = $prepath.'db/'.$database_type.'/schema/'.$file;			
				$filetype = pathinfo($filepath)['extension'];
				switch($filetype){
					case 'sql' : {
						$sql = file_get_contents($filepath);
						if ($sql != '') {
							$sql = str_replace('$EPSGCODE_ALKIS', EPSGCODE_ALKIS, $sql);
							$sql = str_replace(':alkis_epsg', EPSGCODE_ALKIS, $sql);
							if($database_type == 'mysql'){
								$result = $this->database->exec_commands($sql, NULL, NULL);	# mysql
							}
							else {
								if (stripos($sql, '-- exec statements separated') !== false) {
									$sql = str_ireplace('-- exec statements separated', '', $sql);
									$sql = str_ireplace('BEGIN;', '', $sql);
									$sql = str_ireplace('COMMIT;', '', $sql);
									$sql_parts = explode(';', $sql);
								}
								else {
									$sql_parts = array($sql);
								}
								foreach ($sql_parts AS $sql) {
									$sql = trim($sql);
									if ($sql != '') {
										$result = $this->pgdatabase->execSQL($sql, 0, 0, true);	# postgresql
									}
								}
							}
						}
					}break;
					
					case 'php' : {
						include $filepath;
					}break;
				}
				if($result[0]){
					echo $result[1].'<br>Fehler beim Ausführen von migration-Datei: '.$filepath.'<br>';
				}
				else{
					$sql = "INSERT INTO `migrations` (`component`, `type`, `filename`) VALUES ('".$component."', '".$database_type."', '".$file."');";
					$result=$this->database->execSQL($sql,0, 0);
				}
			}
		}
	}
	
	function update_code(){
		$folder = WWWROOT.APPLVERSION;
		if(defined('HTTP_PROXY'))putenv('https_proxy='.HTTP_PROXY);
		exec('cd '.$folder.' && sudo -u '.GIT_USER.' git stash && sudo -u '.GIT_USER.' git pull origin', $ausgabe, $ret);
		if($ret != 0)showAlert('Fehler bei der Ausführung von "git pull origin".');
		return $ausgabe;
	}
	
	function get_config_params(){
		$sql = "SELECT * FROM config ORDER BY `group`";
		$result=$this->database->execSQL($sql,0, 0);
    if($result[0]) {
      echo '<br>Fehler bei der Abfrage der Tabelle config.<br>';
    }
    else {
      while($rs=mysql_fetch_assoc($result[1])){
				$this->config_params[$rs['name']] = $rs;
			}
			foreach($this->config_params as &$param){
				$param['real_value'] = $this->get_real_value($param['name']);
			}
		}
	}
	
	function get_real_value($name){
		if($this->config_params[$name]['prefix'] != ''){
			foreach(explode('.', $this->config_params[$name]['prefix']) as $prefix_constant){
				$prefix_value .= $this->get_real_value($prefix_constant);
			}
			return $prefix_value.$this->config_params[$name]['value'];
		}
		else{
			return $this->config_params[$name]['value'];
		}
	}	
	
	function write_config_file($plugin){
		$this->get_config_params();
		foreach($this->config_params as $param){
			if($param['plugin'] == $plugin){
				if($param['description'] != ''){
					$lines = explode("\n", $param['description']);
					for($l = 0; $l < count($lines)-1; $l++){
						$config.= "# ".$lines[$l]."\n";
					}
				}
				if($param['type'] == 'array'){
					$config.= "$".$param['name']." = ".preg_replace('/stdClass::__set_state/', '(object)', var_export(json_decode($param['value']), true)).";\n\n";
				}
				else{
					$config.= "define(".$param['name'].", '".$param['real_value']."');\n\n";
				}
			}
		}
		if($plugin != '')$prepath = PLUGINS.$plugin.'/config/';
		if(file_put_contents($prepath.'config_live.php', "<?\n\n".$config."\n?>") === false){
			$result[0]=1;
			$result[1]='Fehler beim Schreiben der config-Datei '.$prepath.'config.php';
		}
		else $result[0]=0;
		return $result;
	}
		
	function get_constants_from_all_configs(){
		$constants = $this->get_constants_from_config(file('config.php'), '');
		var_export($constants);
		global $kvwmap_plugins;
  	if(count($kvwmap_plugins) > 0){
			for($i = 0; $i < count($kvwmap_plugins); $i++){
				if(file_exists(PLUGINS.$kvwmap_plugins[$i].'/config/config.php')){
					$constants = $this->get_constants_from_config(file(PLUGINS.$kvwmap_plugins[$i].'/config/config.php'), $kvwmap_plugins[$i]);
					echo "\n".$kvwmap_plugins[$i].":\n";
					var_export($constants);
				}
			}
		}
	}
	
	function get_constants_from_config($config_lines, $plugin){
		$def_constants = get_defined_constants(true)['user'];
		foreach($config_lines as $config_line){
			if(substr(ltrim($config_line), 0, 6) == 'define'){																												# Konstanten
				$name = get_first_word_after(str_replace(' ', '', $config_line), 'define', '(', ',');				
				$name = trim($name, '"\'');
				$value = ltrim(substr($config_line, strpos($config_line, ',')+1));
				$value = rtrim(substr($value, 0, strpos($value, ');')));
				if($value == '')$value = $def_constants[$name];					
				$prefix = explode('"', $value)[0];
				$prefix = explode("'", $prefix)[0];
				if(strpos($value, '"') === false AND strpos($value, "'") === false AND is_numeric(trim($value, "'\"")))$type = 'numeric';
				else{
					if($value == 'true' OR $value == 'false')$type = 'boolean';
					else $type = 'string';
				}
				$value = trim($value, "'\"");
				if($type == 'string' AND strpos($prefix, '.') != ''){
					$prefix = rtrim($prefix, '. ');
					$value = array_pop(explode("'", $value));
				}
				else $prefix = '';
				if(substr($value, 0, 1) == '$'){
					global ${substr($value, 1)};
					$value = ${substr($value, 1)};
				}
				$constant['name'] = $name;
				$constant['value'] = $value;
				$constant['prefix'] = $prefix;
				$constant['type'] = $type;
				$constant['description'] = $description;
				if($plugin != '')$constant['group'] = 'Plugins/'.$plugin;
				else $constant['group'] = $group;
				$constant['plugin'] = $plugin;
				$constant['saved'] = 0;
				$description = '';
				$constants[$name] = $constant;
			}
			elseif(strpos($config_line, 'array(') !== false AND substr(trim($config_line), 0, 1) == '$'){			# Arrays
				$name = trim(substr($config_line, 0, strpos($config_line, '=')), ' $');
				global ${$name};
				$constant['name'] = $name;
				$constant['value'] = json_encode(${$name}, JSON_PRETTY_PRINT);
				$constant['type'] = 'array';
				$constant['description'] = $description;
				if($plugin != '')$constant['group'] = 'Plugins/'.$plugin;
				else $constant['group'] = $group;
				$constant['plugin'] = $plugin;
				$constant['saved'] = 0;
				$description = '';
				$constants[$name] = $constant;
			}
			elseif(substr($config_line, 0, 26) == '##########################'){
				$group = trim(substr($config_line, 26));
			}
			elseif(trim($config_line) == '')$description = '';
			else $description.= trim($config_line, " \t#/");
		}
		return $constants;
	}
	
	
}

?>