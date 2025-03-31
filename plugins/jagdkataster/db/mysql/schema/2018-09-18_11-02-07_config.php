<?

$constants = array (
  'LAYER_ID_JAGDBEZIRKE' => 
  array (
    'name' => 'LAYER_ID_JAGDBEZIRKE',
    'value' => '432',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/jagdkataster',
    'plugin' => 'jagdkataster',
    'saved' => 0,
  ),
);

$config_file = 'config.php';
if(file_exists($config_file)){
	$own_constants = $this->get_constants_from_config(file($config_file), 'jagdkataster');
	foreach($constants as &$const){
		if(array_key_exists($const['name'], $own_constants)){
			$const['value'] = $own_constants[$const['name']]['value'];
			$const['saved'] = 1;
		}
	}
}

$sql = "SELECT * FROM config WHERE plugin = 'jagdkataster'";
$result=$this->database->execSQL($sql,0, 0);
if($result[0]){
	echo '<br>Fehler bei der Abfrage der Tabelle config.<br>';
}
else{
	if($this->database->result->num_rows() == 0){
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
	$result = $this->write_config_file('jagdkataster');
}

?>
