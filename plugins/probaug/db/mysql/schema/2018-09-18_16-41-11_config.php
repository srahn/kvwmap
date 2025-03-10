<?

$constants = array (
  'LAYER_ID_BAUAKTEN_GEOMETRIEN' => 
  array (
    'name' => 'LAYER_ID_BAUAKTEN_GEOMETRIEN',
    'value' => '988',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'Layer-ID des Layers zur Erfassung der Bauakten-Geometrien
',
    'group' => 'Plugins/probaug',
    'plugin' => 'probaug',
    'saved' => 0,
  ),
);

$config_file = PLUGINS.'probaug/config/config.php';
if(file_exists($config_file)){
	$own_constants = $this->get_constants_from_config(file($config_file), 'probaug');
	foreach($constants as &$const){
		if(array_key_exists($const['name'], $own_constants)){
			$const['value'] = $own_constants[$const['name']]['value'];
			$const['saved'] = 1;
		}
	}
}

$sql = "SELECT * FROM config WHERE plugin = 'probaug'";
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
	$result = $this->write_config_file('probaug');
}

?>
