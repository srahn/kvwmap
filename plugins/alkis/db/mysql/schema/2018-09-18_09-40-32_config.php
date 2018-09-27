<?

$constants = array (
  'LANDKREIS' => 
  array (
    'name' => 'LANDKREIS',
    'value' => 'Landkreis XY',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'AMT' => 
  array (
    'name' => 'AMT',
    'value' => 'Kataster-/Vermessungsamt ',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'STRASSE' => 
  array (
    'name' => 'STRASSE',
    'value' => 'Strasse',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'STRASSE2' => 
  array (
    'name' => 'STRASSE2',
    'value' => 'Strasse2',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'PLZ' => 
  array (
    'name' => 'PLZ',
    'value' => 'PLZ',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'ORT' => 
  array (
    'name' => 'ORT',
    'value' => 'Ort',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'POSTANSCHRIFT' => 
  array (
    'name' => 'POSTANSCHRIFT',
    'value' => 'Postanschrift',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'POSTANSCHRIFT_STRASSE' => 
  array (
    'name' => 'POSTANSCHRIFT_STRASSE',
    'value' => 'Postanschrift Strasse',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'POSTANSCHRIFT_PLZ' => 
  array (
    'name' => 'POSTANSCHRIFT_PLZ',
    'value' => 'Postanschrift PLZ',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'POSTANSCHRIFT_ORT' => 
  array (
    'name' => 'POSTANSCHRIFT_ORT',
    'value' => 'Postanschrift Ort',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'BEARBEITER' => 
  array (
    'name' => 'BEARBEITER',
    'value' => 'false',
    'prefix' => '',
    'type' => 'boolean',
    'description' => 'definiert, ob Nutzername im ALB-Auszug 30 angezeigt wird, oder nicht
',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'GUTACHTERAUSSCHUSS' => 
  array (
    'name' => 'GUTACHTERAUSSCHUSS',
    'value' => '12345',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Gutachterausschuss BORIS
',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'katasterfuehrendestelle' => 
  array (
    'name' => 'katasterfuehrendestelle',
    'value' => '{
    "132845": "0019",
    "132846": "0021"
}',
    'prefix' => '',
    'type' => 'array',
    'description' => 'katasterführende Stellen ALB
bei zwei katasterführenden Stellen in einer kvwmap-DB (Nur für Adressänderungen wichtig, sonst auskommentieren)
erste Stelle bis einschließlich GBBZ-Schlüssel, zweite Stelle bis einschließlich GBBZ-Schlüssel, ....
wer nur eine katasterführende Stelle hat, kann das Array weglassen oder auskommentieren
',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'HAUSNUMMER_TYPE' => 
  array (
    'name' => 'HAUSNUMMER_TYPE',
    'value' => 'LOWER',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Legt fest, ob die Hausnummernzusätze groß oder klein dargestellt werden
',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'LAGEBEZEICHNUNGSART' => 
  array (
    'name' => 'LAGEBEZEICHNUNGSART',
    'value' => 'Flurbezeichnung',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Auswahl der Art der Lagebezeichung für den aktuell angezeigten Kartenausschnitt
Je nach dem was hier eingetragen wird wird ein Fall zur Anzeige der Lage verwendet
Die Unterscheidung wird in der Funkiton getLagebezeichnung in kvwmap.php vorgenommen
Varianten:
Flurbezeichnung: bedeutet Ausgabe von Gemeinde, Gemarkung und Flur, soweit in ALK tabellen vorhanden
Wenn kein Wert gesetzt wird, erfolgt keine Anzeige einer Lagebezeichung
',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'EPSGCODE' => 
  array (
    'name' => 'EPSGCODE',
    'value' => '2398',
    'prefix' => '',
    'type' => 'string',
    'description' => 'EPSG-Code dem die Koordinaten der Flurstücke zugeordnet werden sollen in den Tabellen
alb_flurstuecke und alb_x_flurstuecke wenn man postgres verwendet
die Geometriespalte muß auch mit dieser EPSG Nummer angelegt sein.
',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'EPSGCODE_ALKIS' => 
  array (
    'name' => 'EPSGCODE_ALKIS',
    'value' => '25833',
    'prefix' => '',
    'type' => 'string',
    'description' => 'EPSG-Code der ALKIS-Daten
',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'DHK_CALL_URL' => 
  array (
    'name' => 'DHK_CALL_URL',
    'value' => 'http://dhkserver/call?form=login',
    'prefix' => '',
    'type' => 'string',
    'description' => 'DHK-Call-Schnittstelle
',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'DHK_CALL_USER' => 
  array (
    'name' => 'DHK_CALL_USER',
    'value' => '12345',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'DHK_CALL_PASSWORD' => 
  array (
    'name' => 'DHK_CALL_PASSWORD',
    'value' => '6789',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'DHK_CALL_ANTRAGSNUMMER' => 
  array (
    'name' => 'DHK_CALL_ANTRAGSNUMMER',
    'value' => 'BWAPK_0000002',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'DHK_CALL_PROFILKENNUNG' => 
  array (
    'name' => 'DHK_CALL_PROFILKENNUNG',
    'value' => 'mvaaa',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'LAYERNAME_FLURSTUECKE' => 
  array (
    'name' => 'LAYERNAME_FLURSTUECKE',
    'value' => 'Flurstücke',
    'prefix' => '',
    'type' => 'string',
    'description' => 'Name des Flurstückslayers
',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'LAYERNAME_GEBAEUDE' => 
  array (
    'name' => 'LAYERNAME_GEBAEUDE',
    'value' => 'Gebaeude',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'LAYERNAME_NUTZUNGEN' => 
  array (
    'name' => 'LAYERNAME_NUTZUNGEN',
    'value' => 'Nutzung',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'LAYERNAME_AUSGESTALTUNGEN' => 
  array (
    'name' => 'LAYERNAME_AUSGESTALTUNGEN',
    'value' => 'Ausgestaltung',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'LAYERNAME_GEMARKUNGEN' => 
  array (
    'name' => 'LAYERNAME_GEMARKUNGEN',
    'value' => 'Gemeinde',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'LAYERNAME_GEMEINDEN' => 
  array (
    'name' => 'LAYERNAME_GEMEINDEN',
    'value' => 'Gemarkung',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'LAYERNAME_FLUR' => 
  array (
    'name' => 'LAYERNAME_FLUR',
    'value' => 'Flur',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'LAYER_ID_ADRESSAENDERUNGEN_PERSON' => 
  array (
    'name' => 'LAYER_ID_ADRESSAENDERUNGEN_PERSON',
    'value' => '827',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
  'LAYER_ID_ADRESSAENDERUNGEN_ANSCHRIFT' => 
  array (
    'name' => 'LAYER_ID_ADRESSAENDERUNGEN_ANSCHRIFT',
    'value' => '162',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/alkis',
    'plugin' => 'alkis',
    'saved' => 0,
  ),
);

$config_file = 'config.php';
if(file_exists($config_file)){
	$own_constants = $this->get_constants_from_config(file($config_file), 'alkis');
	foreach($constants as &$constant){
		if(array_key_exists($constant['name'], $own_constants)){
			$constant['value'] = $own_constants[$constant['name']]['value'];
			$constant['saved'] = 1;
		}
	}
}

$sql = "SELECT * FROM config WHERE plugin = 'alkis'";
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
	$result = $this->write_config_file('alkis');
}

?>
