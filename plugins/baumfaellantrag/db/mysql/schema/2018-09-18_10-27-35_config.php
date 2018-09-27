<?

$constants = array (
  'BAUMFAELLANTRAG_ANTRAGSTELLER_STELLE_ID' => 
  array (
    'name' => 'BAUMFAELLANTRAG_ANTRAGSTELLER_STELLE_ID',
    'value' => '1',
    'prefix' => '',
    'type' => 'numeric',
    'description' => '',
    'group' => 'Plugins/baumfaellantrag',
    'plugin' => 'baumfaellantrag',
    'saved' => 0,
  ),
  'BAUMFAELLANTRAG_BEARBEITER_STELLE_ID' => 
  array (
    'name' => 'BAUMFAELLANTRAG_BEARBEITER_STELLE_ID',
    'value' => '2',
    'prefix' => '',
    'type' => 'numeric',
    'description' => '',
    'group' => 'Plugins/baumfaellantrag',
    'plugin' => 'baumfaellantrag',
    'saved' => 0,
  ),
  'BAUMFAELLANTRAG_LAYER_ID_FLURSTUECKE' => 
  array (
    'name' => 'BAUMFAELLANTRAG_LAYER_ID_FLURSTUECKE',
    'value' => '10',
    'prefix' => '',
    'type' => 'numeric',
    'description' => '',
    'group' => 'Plugins/baumfaellantrag',
    'plugin' => 'baumfaellantrag',
    'saved' => 0,
  ),
  'BAUMFAELLANTRAG_LAYER_ID_SATZUNGSGEBIETE' => 
  array (
    'name' => 'BAUMFAELLANTRAG_LAYER_ID_SATZUNGSGEBIETE',
    'value' => '11',
    'prefix' => '',
    'type' => 'numeric',
    'description' => '',
    'group' => 'Plugins/baumfaellantrag',
    'plugin' => 'baumfaellantrag',
    'saved' => 0,
  ),
  'BAUMFAELLANTRAG_LAYER_ID_ZUSTAENDIGESTELLEN' => 
  array (
    'name' => 'BAUMFAELLANTRAG_LAYER_ID_ZUSTAENDIGESTELLEN',
    'value' => '12',
    'prefix' => '',
    'type' => 'numeric',
    'description' => '',
    'group' => 'Plugins/baumfaellantrag',
    'plugin' => 'baumfaellantrag',
    'saved' => 0,
  ),
  'BAUMFAELLANTRAG_LAYER_ID_ANTRAEGE' => 
  array (
    'name' => 'BAUMFAELLANTRAG_LAYER_ID_ANTRAEGE',
    'value' => '13',
    'prefix' => '',
    'type' => 'numeric',
    'description' => '',
    'group' => 'Plugins/baumfaellantrag',
    'plugin' => 'baumfaellantrag',
    'saved' => 0,
  ),
  'BAUMFAELLANTRAG_DEFAULT_LATITUDE' => 
  array (
    'name' => 'BAUMFAELLANTRAG_DEFAULT_LATITUDE',
    'value' => '53.71181',
    'prefix' => '',
    'type' => 'numeric',
    'description' => '',
    'group' => 'Plugins/baumfaellantrag',
    'plugin' => 'baumfaellantrag',
    'saved' => 0,
  ),
  'BAUMFAELLANTRAG_DEFAULT_LONGITUDE' => 
  array (
    'name' => 'BAUMFAELLANTRAG_DEFAULT_LONGITUDE',
    'value' => '11.97404',
    'prefix' => '',
    'type' => 'numeric',
    'description' => '',
    'group' => 'Plugins/baumfaellantrag',
    'plugin' => 'baumfaellantrag',
    'saved' => 0,
  ),
);

$config_file = PLUGINS.'baumfaellantrag/config/config.php';
if(file_exists($config_file)){
	$own_constants = $this->get_constants_from_config(file($config_file), 'baumfaellantrag');
	foreach($constants as &$constant){
		if(array_key_exists($constant['name'], $own_constants)){
			$constant['value'] = $own_constants[$constant['name']]['value'];
			$constant['saved'] = 1;
		}
	}
}

$sql = "SELECT * FROM config WHERE plugin = 'baumfaellantrag'";
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
	$result = $this->write_config_file('baumfaellantrag');
}

?>
