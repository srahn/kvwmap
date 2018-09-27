<?

$constants = array (
  'NACHWEISDOCPATH' => 
  array (
    'name' => 'NACHWEISDOCPATH',
    'value' => 'nachweise/',
    'prefix' => 'SHAPEPATH',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/nachweisverwaltung',
    'plugin' => 'nachweisverwaltung',
    'saved' => 0,
  ),
  'PUNKTDATEIPATH' => 
  array (
    'name' => 'PUNKTDATEIPATH',
    'value' => 'festpunkte/',
    'prefix' => 'SHAPEPATH',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/nachweisverwaltung',
    'plugin' => 'nachweisverwaltung',
    'saved' => 0,
  ),
  'PUNKTDATEIARCHIVPATH' => 
  array (
    'name' => 'PUNKTDATEIARCHIVPATH',
    'value' => 'archiv/',
    'prefix' => 'PUNKTDATEIPATH',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/nachweisverwaltung',
    'plugin' => 'nachweisverwaltung',
    'saved' => 0,
  ),
  'KVZAUSGABEDATEINAME' => 
  array (
    'name' => 'KVZAUSGABEDATEINAME',
    'value' => 'festpunkte.kvz',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/nachweisverwaltung',
    'plugin' => 'nachweisverwaltung',
    'saved' => 0,
  ),
  'KVZKOPF' => 
  array (
    'name' => 'KVZKOPF',
    'value' => '# Datenaustauschformat Landkreis Rostock
#KST PKN             VMA  RECHTSWERT   HOCHWERT    HOEHE    GST  VWL  DES  ART
# ',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/nachweisverwaltung',
    'plugin' => 'nachweisverwaltung',
    'saved' => 0,
  ),
  'SKIZZEN_DATEI_TYP' => 
  array (
    'name' => 'SKIZZEN_DATEI_TYP',
    'value' => 'tif',
    'prefix' => '',
    'type' => 'string',
    'description' => '',
    'group' => 'Plugins/nachweisverwaltung',
    'plugin' => 'nachweisverwaltung',
    'saved' => 0,
  ),
  'RECHERCHEERGEBNIS_PATH' => 
  array (
    'name' => 'RECHERCHEERGEBNIS_PATH',
    'value' => 'recherchierte_antraege/',
    'prefix' => 'SHAPEPATH',
    'type' => 'string',
    'description' => 'Pfad zum Speichern der Nachweisrecherche
',
    'group' => 'Plugins/nachweisverwaltung',
    'plugin' => 'nachweisverwaltung',
    'saved' => 0,
  ),
  'RISSNUMMERMAXLENGTH' => 
  array (
    'name' => 'RISSNUMMERMAXLENGTH',
    'value' => '8',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'Erlaubte maximale Länge der Rissnummer in der Fachschale Nachweisverwaltung
',
    'group' => 'Plugins/nachweisverwaltung',
    'plugin' => 'nachweisverwaltung',
    'saved' => 0,
  ),
  'ANTRAGSNUMMERMAXLENGTH' => 
  array (
    'name' => 'ANTRAGSNUMMERMAXLENGTH',
    'value' => '9',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'Erlaubte maximale Länge der Antragsnummer in der Fachschale Nachweisverwaltung
',
    'group' => 'Plugins/nachweisverwaltung',
    'plugin' => 'nachweisverwaltung',
    'saved' => 0,
  ),
  'BLATTNUMMERMAXLENGTH' => 
  array (
    'name' => 'BLATTNUMMERMAXLENGTH',
    'value' => '4',
    'prefix' => '',
    'type' => 'numeric',
    'description' => 'Erlaubte maximale Länge der Blattnummer in der Fachschale Nachweisverwaltung
',
    'group' => 'Plugins/nachweisverwaltung',
    'plugin' => 'nachweisverwaltung',
    'saved' => 0,
  ),
  'NACHWEIS_PRIMARY_ATTRIBUTE' => 
  array (
    'name' => 'NACHWEIS_PRIMARY_ATTRIBUTE',
    'value' => 'rissnummer',
    'prefix' => '',
    'type' => 'string',
    'description' => 'das primäre Ordnungskriterium der Nachweisverwaltung: rissnummer/stammnr
',
    'group' => 'Plugins/nachweisverwaltung',
    'plugin' => 'nachweisverwaltung',
    'saved' => 0,
  ),
  'NACHWEIS_SECONDARY_ATTRIBUTE' => 
  array (
    'name' => 'NACHWEIS_SECONDARY_ATTRIBUTE',
    'value' => '',
    'prefix' => '',
    'type' => 'string',
    'description' => 'das zusätzliche Ordnungskriterium der Nachweisverwaltung (kann bei eindeutigem primärem leer gelassen werden): fortfuehrung
',
    'group' => 'Plugins/nachweisverwaltung',
    'plugin' => 'nachweisverwaltung',
    'saved' => 0,
  ),
  'nachweis_unique_attributes' => 
  array (
    'name' => 'nachweis_unique_attributes',
    'value' => '[
    "gemarkung",
    "flur",
    "rissnummer",
    "art",
    "blattnr"
]',
    'prefix' => '',
    'type' => 'array',
    'description' => 'die Attribute, die einen Nachweis eindeutig identifizieren
',
    'group' => 'Plugins/nachweisverwaltung',
    'plugin' => 'nachweisverwaltung',
    'saved' => 0,
  ),
  'LAYER_ID_NACHWEISE' => 
  array (
    'name' => 'LAYER_ID_NACHWEISE',
    'value' => '786',
    'prefix' => '',
    'type' => 'numeric',
    'description' => '',
    'group' => 'Plugins/nachweisverwaltung',
    'plugin' => 'nachweisverwaltung',
    'saved' => 0,
  ),
);

$config_file = 'config.php';
if(file_exists($config_file)){
	$own_constants = $this->get_constants_from_config(file($config_file), 'nachweisverwaltung');
	foreach($constants as &$constant){
		if(array_key_exists($constant['name'], $own_constants)){
			$constant['value'] = $own_constants[$constant['name']]['value'];
			$constant['saved'] = 1;
		}
	}
}

$sql = "SELECT * FROM config WHERE plugin = 'nachweisverwaltung'";
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
	$result = $this->write_config_file('nachweisverwaltung');
}

?>
