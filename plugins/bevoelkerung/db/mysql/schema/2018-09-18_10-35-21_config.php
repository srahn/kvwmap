<?

$constants = array (
  'BEVOELKERUNG_IMPORT_DATA_PATH' => 
  array (
    'name' => 'BEVOELKERUNG_IMPORT_DATA_PATH',
    'value' => 'prognosedaten/',
    'prefix' => 'SHAPEPATH',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/bevoelkerung',
    'plugin' => 'bevoelkerung',
    'saved' => 0,
  ),
);

$config_file = PLUGINS.'bevoelkerung/config/config.php';
if(file_exists($config_file)){
	$own_constants = $this->get_constants_from_config(file($config_file), 'bevoelkerung');
	foreach($constants as &$constant){
		if(array_key_exists($constant['name'], $own_constants)){
			$constant['value'] = $own_constants[$constant['name']]['value'];
			$constant['saved'] = 1;
		}
	}
}

$sql = "SELECT * FROM config WHERE plugin = 'bevoelkerung'";
$result=$this->database->execSQL($sql,0, 0);
if($result[0]){
	echo '<br>Fehler bei der Abfrage der Tabelle config.<br>';
}
else{
	if(mysql_num_rows($result[1]) == 0){
		$sql = '';
		foreach($constants as $constant){
			$sql.="INSERT INTO config (name, prefix, value, description, type, `group`, `plugin`, `saved`) VALUES ('".$constant['name']."', '".$constant['prefix']."', '".addslashes($constant['value'])."', '".addslashes($constant['description'])."', '".$constant['type']."', '".$constant['group']."', '".$constant['plugin']."', ".$constant['saved'].");\n";
		}
		# config Tabelle befüllen
		$result = $this->database->exec_commands($sql, NULL, NULL);
	}
}

if($result[0] == 0){
	# config.php schreiben
	$result = $this->write_config_file('bevoelkerung');
}

?>
