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

function compare_migration_filenames($a, $b) {
	if ($a['file'] == $b['file']) {
			return 0;
	}
	return ($a['file'] < $b['file']) ? -1 : 1;
}

class administration{

	var $database;
	var $config_params = array();
	var $migration_logs;
	var $migration_files;
	var $migrations_to_execute;

	function __construct($pgdatabase) {
		$this->pgdatabase = $pgdatabase;
	}

	function get_migration_logs() {
		#echo '<br>Get Migration logs';
		$migrations = array();
		$sql = "
			SELECT *
			FROM kvwmap.migrations
		";
		#echo '<br>SQL zur Abfrage der registrierten Migrationen: ' . $sql;
		$result = $this->pgdatabase->execSQL($sql,0, 0);
		if (!$this->pgdatabase->success) {
			echo '<br>Migrationstabelle existiert noch nicht. Bei Neuinstallation wird sie angelegt ... <br>'; // bei Neuinstallation gibt es diese Tabelle noch nicht
			$sql = "
				CREATE TABLE IF NOT EXISTS kvwmap.migrations (
				  component varchar(50) NOT NULL,
				  type enum('postgresql') NOT NULL,
				  filename varchar(255) NOT NULL
				);
			";
			$result = $this->pgdatabase->execSQL($sql,0, 0);
			if ($this->pgdatabase->success) {
				echo ' Migrationstabelle erfolgreich angelegt!';
			}
			else {
				echo '<br>Breche Installationsvorgang ab, da migrationstabelle nicht angelegt werden konnte und somit keine Migrationen registriert werden können!';
				exit;
			}
		}
		else {
			while ($rs = pg_fetch_array($result[1])) {
				$migrations[$rs['component']][$rs['type']][$rs['filename']] = 1;
			}
		}
		#echo '<br>Gefundene Migrationen: ' . print_r($migrations, true);
		return $migrations;
	}

	function get_schema_migration_files() {
		#echo '<br>Get Schema Migration Files';
		global $kvwmap_plugins;
		$migrations['kvwmap']['mysql'] = array_diff (scandir(LAYOUTPATH.'db/mysql/schema'), array('.', '..'));
		sort($migrations['kvwmap']['mysql']);
		$migrations['kvwmap']['postgresql'] = array_diff (scandir(LAYOUTPATH.'db/postgresql/schema'), array('.', '..'));
		sort($migrations['kvwmap']['postgresql']);
		for($i = 0; $i < count($kvwmap_plugins); $i++) {
			$path = PLUGINS.$kvwmap_plugins[$i].'/db/mysql/schema';
			if (file_exists($path)) {
				$migrations[$kvwmap_plugins[$i]]['mysql'] = array_diff (scandir($path), array('.', '..'));
				sort($migrations[$kvwmap_plugins[$i]]['mysql']);
			}
			$path = PLUGINS.$kvwmap_plugins[$i].'/db/postgresql/schema';
			if (file_exists($path)) {
				$migrations[$kvwmap_plugins[$i]]['postgresql'] = array_diff (scandir($path), array('.', '..'));
				sort($migrations[$kvwmap_plugins[$i]]['postgresql']);
			}
		}
		return $migrations;
	}

	function get_seed_files() {
		global $kvwmap_plugins;
		for($i = 0; $i < count($kvwmap_plugins); $i++) {
			$path = PLUGINS.$kvwmap_plugins[$i].'/db/mysql/data';
			if (file_exists($path))$seeds[$kvwmap_plugins[$i]]['mysql'] = array_diff (scandir($path), array('.', '..'));
		}
		return $seeds;
	}

	function get_database_status() {
		$this->migrations_to_execute['postgresql'] = array();
		$this->seeds_to_execute['postgresql'] = array();
		$this->migration_logs = $this->get_migration_logs();
		$this->schema_migration_files = $this->get_schema_migration_files();
		$this->seed_files = $this->get_seed_files();
		foreach ($this->schema_migration_files as $component => $file_component_migrations) {
			foreach ($file_component_migrations as $type => $file_component_type_migrations) {
				foreach ($file_component_type_migrations as $file) {
					if ($this->migration_logs[$component][$type][$file] != 1) {
						$this->migrations_to_execute[$type][$component][] = $file;
					}
				}
			}
		}
		if ($this->seed_files != '') {
			foreach ($this->seed_files as $component => $file_component_seeds) {
				foreach ($file_component_seeds as $type => $file_component_type_seeds) {
					foreach ($file_component_type_seeds as $file) {
						if ($this->migration_logs[$component][$type][$file] != 1) {
							$this->seeds_to_execute[$type][$component][] = $file;
						}
					}
				}
			}
		}
	}

	function update_databases() {
		$err_msgs = array();

		foreach ($this->migrations_to_execute['postgresql'] as $component => $component_migration) {
			foreach ($component_migration as $file) {
				$migration['component'] = $component;
				$migration['file'] = $file;
				$pg_migrations[] = $migration;
			}
		}
		$err_msgs = array_merge($err_msgs, $this->execute_migrations('postgresql', $pg_migrations));

		// foreach ($this->seeds_to_execute['mysql'] as $component => $component_seed) {
		// 	$prepath = PLUGINS.$component.'/';
		// 	foreach ($component_seed as $file) {
		// 		$filepath = $prepath.'db/mysql/data/'.$file;
		// 		#echo '<br>Execute SQL from seed file: ' . $filepath;
		// 		$result = $this->database->exec_commands(
		// 			file_get_contents($filepath),
		// 			null, # Do not replace connection since we have connection_id's in layer defenitions ($this->pgdatabase->get_connection_string(),)
		// 			$this->pgdatabase->connection_id,
		// 			true
		// 		); # replace known constants
		// 		if ($result[0]) {
		// 			echo $result[1] . getTimestamp('H:i:s', 4). ' Fehler beim Ausführen von seed-Datei: '.$filepath.'<br>';
		// 		}
		// 		else{
		// 			$sql = "
		// 				INSERT INTO `migrations`
		// 					(`component`, `type`, `filename`)
		// 				VALUES ('" . $component . "', 'mysql', '" . $file . "');
		// 			";
		// 			#echo '<p>Register MySQL migration for component ' . $component . ' with sql: <br>' . $sql;
		// 			$result=$this->database->execSQL($sql,0, 0);
		// 		}
		// 	}
		// }
		return $err_msgs;
	}

	function execute_migrations($database_type, $migrations) {
		$err_msgs = array();
		if ($migrations != NULL) {
			usort($migrations, 'compare_migration_filenames');		# sortieren, damit die Migrationen in der richtigen Reihenfolge ausgeführt werden
			foreach ($migrations as $migration) {
				$component = $migration['component'];
				$file = $migration['file'];
				#echo '<br>Execute sql from migration for component: ' . $component . ' from file: ' . $file;
				if ($component == 'kvwmap') {
					$prepath = LAYOUTPATH;
				}
				else {
					$prepath = PLUGINS . $component.'/';
				}
				$filepath = $prepath . 'db/' . $database_type . '/schema/';
				$filetype = pathinfo($filepath . $file)['extension'];
				#echo ' filetype: ' . $filetype;
				switch ($filetype) {
					case 'sql' : {
						$sql = file_get_contents($filepath . $file);
						if ($sql != '') {
							if (EPSGCODE_ALKIS != -1) {
								$sql = str_replace(':alkis_epsg', EPSGCODE_ALKIS, $sql);
							}
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
									#echo ' Query SQL';
									$result = $this->pgdatabase->execSQL($sql, 4, 0, true);	# postgresql
								}
							}
						}
					} break;

					case 'php' : {
						include $filepath . $file;
					} break;
				}
				if ($result[0]) {
					$err_msgs[] = getTimestamp('H:i:s', 4) . ': Fehler beim Ausführen von migration-Datei:<br>' . $file . '<br>in Pfad: ' . str_replace(WWWROOT.APPLVERSION, '../', $filepath) . '<br>' . $result[1];
					$result = $this->pgdatabase->execSQL('ROLLBACK;', 0, 0, false);
					break;
				}
				else {
					$sql = "
						INSERT INTO kvwmap.migrations (
							component,
							type,
							filename
						) VALUES (
							'" . $component . "',
							'" . $database_type . "',
							'" . $file . "'
						);
					";
					#echo 'register migration with sql: ' . $sql;
					$result=$this->pgdatabase->execSQL($sql, 4, 0);
				}
			}
		}
		return $err_msgs;
	}

	function update_code() {
		$folder = WWWROOT.APPLVERSION;
		if (defined('HTTP_PROXY')) {
			putenv('https_proxy='.HTTP_PROXY);
		}
		exec('sudo -u ' . GIT_USER . ' git status -s --porcelain 2>&1', $output, $return_var);
		if (count($output) > 0) {
			$this->pgdatabase->gui->add_message('Fehler', 'Update kann nicht erfolgen!<p>Es gibt folgende noch nicht committete Änderungen:<br>' . implode('<br>', $output) . '<br>Erst Änderungen committen oder auschecken!');
			return false;
		}
		else {
			exec('cd ' . $folder . ' && sudo -u ' . GIT_USER . ' git stash && sudo -u ' . GIT_USER . ' git pull origin', $ausgabe, $ret);
			if ($ret != 0) {
				$this->pgdatabase->gui->add_message('Fehler', 'Fehler bei der Ausführung von "git pull origin"!');
			}
			return $ausgabe;
		}
	}

	function get_config_params() {
		$this->config_params = array();
		$sql = "
			SELECT *
			FROM kvwmap.config
			ORDER BY \"group\", name
		";
		#echo 'SQL: ' . $sql;
		$ret = $this->pgdatabase->execSQL($sql, 0, 0);
		if (!$this->pgdatabase->success) {
			#echo '<br>Fehler bei der Abfrage der Tabelle config.<br>';
		}
		else {
			while ($rs = pg_fetch_assoc($ret[1])) {
				$this->config_params[$rs['name']] = $rs;
			}
			foreach ($this->config_params as &$param) {
				$param['real_value'] = $this->get_real_value($param['name']);
				#echo '<br>' . $param['name'] . ' real_value: ' . $param['real_value'];
			}
		}
	}

	function get_real_value($name) {
		$name = trim($name);
		#echo '<p>' . $name . ' prefix vor behandlung' . $this->config_params[$name]['prefix'];
		if ($this->config_params[$name]['prefix'] != '') {
			if ($this->config_params[$name]['value'] == '' AND !in_array($param['editable'], [1, 3])) {
				return NULL;
			}
			#echo '<p>' . $name . ' prefix: ' . print_r(explode('.', $this->config_params[$name]['prefix']), true);
			foreach (explode('.', $this->config_params[$name]['prefix']) as $prefix_constant) {
				#echo '<br>part : ' . $prefix_constant;
				$prefix_value .= $this->get_real_value($prefix_constant);
				#echo '<br>ordne zu: ' . $prefix_value;
			}
			#echo '<br>ordne prefix ' . $prefix_value . ' zu ' . $this->config_params[$name]['value'];
			return $prefix_value . $this->config_params[$name]['value'];
		}
		else {
			return $this->config_params[$name]['value'];
		}
	}

	function save_config($formvars) {
		global $kvwmap_plugins;
		foreach ($this->config_params as $param) {
			if (isset($formvars[$param['name']])) {
				$sql = "
					UPDATE
						kvwmap.config
					SET
						" . ($formvars[$param['name'] . '_prefix'] != '' ? "prefix = '" . $formvars[$param['name'] . '_prefix'] . "'," : "") . "
						value = '" . $formvars[$param['name']] . "',
						saved = 1
					WHERE
						name = '" . $param['name'] . "'
				";
				#echo 'Update config with sql: ' . $sql . ' prefix: ' . $param['prefix'] . ' formvar: ' . $formvars[$param['prefix']] . ' <br>';
				$result = $this->pgdatabase->execSQL($sql,0, 0);
				if ($result[0]) {
					echo '<br>Fehler beim Update der Tabelle config.<br>';
				}
			}
		}
		$this->write_config_file('');
		for ($i = 0; $i < count($kvwmap_plugins); $i++) {
			$this->write_config_file($kvwmap_plugins[$i]);
		}
	}

	function write_config_file($plugin) {
		$this->get_config_params();
		$config = '';
		foreach ($this->config_params as $param) {
			#echo '<br>plugin: ' . $param['plugin'] . ' name: ' . $param['name'] . ' type: ' . $param['type'] . ' real: ' . $param['real_value'];
			if ($param['plugin'] == $plugin) {
				if ($param['description'] != '') {
					$param['description'] = rtrim($param['description']);
					$lines = explode("\n", $param['description']);
					for($l = 0; $l < count($lines); $l++) {
						$config .= "# " . $lines[$l] . "\n";
					}
				}
				if ($param['type'] == 'array') {
					$param_array_str = str_replace(['(object) ', 'stdClass::__set_state'], '', var_export(json_decode($param['value']), true));
					if ($param_array_str == 'NULL') {
						$this->pgdatabase->gui->add_message('error', 'Syntaxfehler im Parameter: ' . $param['name'] . '!<br>Bitte auf das richtige setzen von Anführungsstrichen<br>und Klammern achten.');
						$param_array_str = 'array()';
					}
					$config .= "$" . $param['name'] . " = " . $param_array_str . ";\n\n";
				}
				else {
					if ($param['type'] == 'string' OR $param['type'] == 'password') {
						$quote = "'";
					}
					else {
						$quote = '';
						if ($param['real_value'] == '') {
							$param['real_value'] = 'NULL';
						}
					}
					$config .= "define('" . $param['name'] . "', " . $quote . $param['real_value'] . $quote . ");\n\n";
				}
			}
		}
		if ($config != '') {
			if ($plugin != '') {
				$prepath = PLUGINS . $plugin . '/config/';
				if (!file_exists($prepath)) {
					mkdir($prepath);
				}
			}
			if (file_put_contents($prepath . 'config.php', "<?\n\n" . $config . "?>") === false) {
				$result[0] = 1;
				$result[1] = 'Fehler beim Schreiben der config-Datei ' . $prepath . 'config.php';
			}
			else {
				if($plugin == ''){
					$this->pgdatabase->gui->add_message('warning', 'Konfigurationsdatei config.php geschrieben.<br>Zum Wirksamwerden muss die Seite nochmal geladen werden.');
				}
				$result[0] = 0;
			}
		}
		return $result;
	}


	function get_constants_from_config($config_lines, $plugin) {			# diese Funktion wird nur in den Migrationen gebraucht, die von config.php auf config-Tabelle umstellen
		$def_constants = get_defined_constants(true)['user'];
		foreach ($config_lines as $config_line) {
			if (substr(ltrim($config_line), 0, 6) == 'define') {																												# Konstanten
				$name = get_first_word_after(str_replace(' ', '', $config_line), 'define', '(', ',');
				$name = trim($name, '"\'');
				$value = ltrim(substr($config_line, strpos($config_line, ',')+1));
				$value = rtrim(substr($value, 0, strpos($value, ');')));
				if (substr($value, 0, 1) == '$')$value = $def_constants[$name];
				$prefix = explode('"', $value)[0];
				$prefix = explode("'", $prefix)[0];
				if (strpos($value, '"') === false AND strpos($value, "'") === false AND is_numeric(trim($value, "'\"")))$type = 'numeric';
				else{
					if ($value == 'true' OR $value == 'false')$type = 'boolean';
					else $type = 'string';
				}
				$value = trim($value, "'\"");
				if ($type == 'string' AND strpos($prefix, '.') != '') {
					$prefix = rtrim($prefix, '. ');
					$value = array_pop(explode("'", $value));
				}
				else $prefix = '';
				if (substr($value, 0, 1) == '$') {
					global ${substr($value, 1)};
					$value = ${substr($value, 1)};
				}
				$constant['name'] = $name;
				$constant['value'] = $value;
				$constant['prefix'] = $prefix;
				$constant['type'] = $type;
				$constant['description'] = $description;
				if ($plugin != '')$constant['group'] = 'Plugins/'.$plugin;
				else $constant['group'] = $group;
				$constant['plugin'] = $plugin;
				$constant['saved'] = 0;
				$description = '';
				$constants[$name] = $constant;
			}
			elseif (strpos($config_line, 'array(') !== false AND substr(trim($config_line), 0, 1) == '$') {			# Arrays
				$name = trim(substr($config_line, 0, strpos($config_line, '=')), ' $');
				global ${$name};
				$constant['name'] = $name;
				$constant['value'] = json_encode(${$name}, JSON_PRETTY_PRINT);
				$constant['type'] = 'array';
				$constant['description'] = $description;
				if ($plugin != '')$constant['group'] = 'Plugins/'.$plugin;
				else $constant['group'] = $group;
				$constant['plugin'] = $plugin;
				$constant['saved'] = 0;
				$description = '';
				$constants[$name] = $constant;
			}
			elseif (substr($config_line, 0, 26) == '##########################') {
				$group = trim(substr($config_line, 26));
			}
			elseif (trim($config_line) == '')$description = '';
			else $description.= trim($config_line, " \t#/");
		}
		return $constants;
	}

	public static function deleteDir($dirPath) {
		if (is_dir($dirPath)){
			if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
					$dirPath .= '/';
			}
			$files = glob($dirPath . '*', GLOB_MARK);
			foreach ($files as $file) {
				if (is_dir($file)) {
					self::deleteDir($file);
				} else {
					unlink($file);
				}
			}
			rmdir($dirPath);
		}
	}

	function create_inserts_from_dataset($schema, $table, $where) {
		global $GUI;
		include_once(CLASSPATH . 'PgObject.php');
		$pgo = new PgObject($GUI, $schema, $table);
		$datasets = $pgo->find_where($where);
		if (count($datasets) > 0) {
			$fkeys = $datasets[0]->get_fkey_constraints();
			$attribute_types = $datasets[0]->get_attribute_types();
			#echo '<p>attribute_types from table ' . $datasets[0]->tableName . ': ' . print_r($attribute_types, 1);
			foreach ($datasets AS $dataset) {
				#echo '<p>return inserts for '. $table . ': ' . $dataset->get('id');
				return $dataset->as_inserts_with_childs($fkeys, $attribute_types);
			}
		}
	}

}

?>
