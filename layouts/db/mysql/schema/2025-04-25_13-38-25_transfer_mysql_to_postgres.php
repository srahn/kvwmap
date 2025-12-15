<?
  ini_set('memory_limit', '16000M');
  set_time_limit(1800);
  global $GUI;
	global $kvwmap_plugins;

  # credentials befüllen
  $credentials = $this->pgdatabase->get_credentials(POSTGRES_CONNECTION_ID);
  file_put_contents(
    'credentials.php', 
    "<?
define('POSTGRES_DBNAME', '" . $credentials['dbname'] . "');
define('POSTGRES_HOST', '" . $credentials['host'] . "');
define('POSTGRES_PASSWORD', '" . $credentials['password'] . "');    
define('POSTGRES_USER', '" . $credentials['user'] . "');
?>",
    FILE_APPEND
  );

  # diese Konstanten aus config.php und Tabelle config entfernen
  $constants = ['POSTGRES_DBNAME', 'POSTGRES_HOST', 'POSTGRES_PASSWORD', 'POSTGRES_USER'];
  $inputFile = 'config.php'; // Pfad zur Originaldatei
  $tempFile = 'config_temp.php'; // Temporäre Datei

  $handle = fopen($inputFile, 'r');
  $tempHandle = fopen($tempFile, 'w');

  if ($handle && $tempHandle) {
    while (($line = fgets($handle)) !== false) {
      $write = true;
      foreach($constants as $constant) {
        if (strpos($line, $constant) !== false) {
          $write = false;
        }
      }
      if ($write) {
        fwrite($tempHandle, $line);
      }
    }
    fclose($handle);
    fclose($tempHandle);

    rename($tempFile, $inputFile);

    $sql = "
      DELETE FROM 
        config
      WHERE 
        name IN ('" . implode("', '", $constants) . "');
    ";
    $ret = $this->database->execSQL($sql,4, 1);
  } 
  else {
      echo "Fehler beim Öffnen der Datei config.php.\n";
  }

  # Plugin-Migrationen eintragen
	$plugin_migrations = [
		'alkis' => [
			'2025-05-20_15-46-00_config.sql'
		],
		'anliegerbeitraege' => [
			'2025-05-20_15-46-00_layer.sql',
			'2025-05-20_15-46-00_menues.sql'
		],
    'bauleitplanung' => [
			'aemter.sql',
      'gebietstypen_fnp.sql',
      'gebietstypen.sql',
      'gemeinden.sql',
      'konkretisierungen.sql',
      'kreise.sql',
      'planungsregionen.sql',
      'stadtumlandraum.sql'
		],
		'bodenrichtwerte' => [
			'2025-05-20_15-46-00_config.sql',
			'2025-05-20_15-46-00_layer.sql',
			'2025-05-20_15-46-00_menues.sql'
		],
		'dbak' => [
			'2025-05-20_15-46-00_config.sql'
		],
		'fortfuehrungslisten' => [
			'2025-05-20_15-46-00_config.sql',
			'2025-05-20_15-46-00_layer.sql',
			'2025-05-20_15-46-00_menues.sql'
		],
		'jagdkataster' => [
			'2025-05-20_15-46-00_config.sql',
			'2025-05-20_15-46-00_layer.sql',
			'2025-05-20_15-46-00_menues.sql'
		],
		'kolibri' => [
			'2025-05-20_15-46-00_u_funktionen.sql'
		],
		'metadata' => [
			'2025-05-20_15-46-00_config.sql',
			'2025-05-20_15-46-00_layer.sql',
			'2025-05-20_15-46-00_u_funktionen.sql'
		],
		'nachweisverwaltung' => [
			'2025-05-20_15-46-00_config.sql',
			'2025-05-20_15-46-00_layer.sql',
			'2025-05-20_15-46-00_menues.sql'
		],
		'portal' => [
			'2025-05-20_15-46-00_config.sql'
		],
		'probaug' => [
			'2025-05-20_15-46-00_config.sql',
			'2025-05-20_15-46-00_layer.sql',
			'2025-05-20_15-46-00_menues.sql',
      'bau_verfahrensart.sql',
      'bau_vorhaben.sql'
		],
		'ukos' => [
			'2025-05-20_15-46-00_menues.sql'
		],
		'xplankonverter' => [
			'2025-05-20_15-46-00_config.sql',
			'2025-05-20_15-46-00_layer.sql',
			'2025-05-20_15-46-00_menues.sql',
      'codelists.sql',
      'enums_rp.sql',
      'plaene.sql'
		]
	];
 
	foreach ($kvwmap_plugins as $plugin) {
		foreach ($plugin_migrations[$plugin] as $migration) {
			$sql = "
				INSERT INTO migrations
					(component, type, filename)
				VALUES 
					('" . $plugin . "', 'postgresql', '" . $migration . "')
			";
			$ret = $this->database->execSQL($sql,4, 1);
		}
	}  
	
  # Transfer von MySQL zu PostgreSQL
	$sql = "
		SELECT 
      table_name 
    FROM 
      information_schema.tables
    WHERE 
      table_schema = '" . MYSQL_DBNAME . "' AND 
      table_type='BASE TABLE' AND table_name IN (
        'belated_files',
        'classes',
        'colors',
        'config',
        'connections',
        'cron_jobs',
        'datasources',
        'datatypes',
        'datatype_attributes',
        'datendrucklayouts',
        'ddl2freilinien',
        'ddl2freirechtecke',
        'ddl2freitexte',
        'ddl2stelle',
        'ddl_colors',
        'ddl_elemente',
        'druckausschnitte',
        'druckfreibilder',
        'druckfreilinien',
        'druckfreirechtecke',
        'druckfreitexte',
        'druckrahmen',
        'druckrahmen2freibilder',
        'druckrahmen2freitexte',
        'druckrahmen2stelle',
        'invitations',
        'labels',
        'layer',
        'layer_attributes',
        'layer_attributes2rolle',
        'layer_attributes2stelle',
        'layer_charts',
        'layer_datasources',
        'layer_labelitems',
        'migrations',
        'notifications',
        'referenzkarten',
        'rolle',
        'rollenlayer',
        'rolle_csv_attributes',
        'rolle_export_settings',
        'rolle_last_query',
        'rolle_nachweise',
        'rolle_nachweise_dokumentauswahl',
        'rolle_nachweise_rechercheauswahl',
        'rolle_saved_layers',
        'search_attributes2rolle',
        'stellen_hierarchie',
        'stelle_gemeinden',
        'styles',
        'used_layer',
        'user2notifications',
        'u_attributfilter2used_layer',
        'u_consume',
        'u_consume2comments',
        'u_consume2layer',
        'u_consumeALB',
        'u_consumeALK',
        'u_consumeCSV',
        'u_consumeNachweise',
        'u_consumeShape',
        'u_funktion2stelle',
        'u_funktionen',
        'u_groups2rolle',
        'u_labels2classes',
        'u_menue2rolle',
        'u_menue2stelle',
        'u_menues',
        'u_rolle2used_class',
        'u_rolle2used_layer',
        'u_styles2classes',
        'zwischenablage',
        'stelle',
        'user',
        'layer_parameter',
        'u_groups'
      );
	";
  $ret = $this->database->execSQL($sql,4, 1);
  $res = $this->database->result;

  $sql = "BEGIN;";
  $ret = $this->pgdatabase->execSQL($sql, 0, 0);

  while ($r = $res->fetch_assoc()){
    $inserts = $this->database->create_insert_dump($r['table_name'], '', 'SELECT * FROM '. $r['table_name'])['insert'];

    $batchSize = 1000;
    $total = count($inserts);
    
    for ($i = 0; $i < $total; $i += $batchSize) {
      $batch = array_slice($inserts, $i, $batchSize);    
      $sql = "
        SET search_path = kvwmap, public;
        SET session_replication_role = 'replica';
        " . implode("\n", $batch) . "
        SET session_replication_role = 'origin';
      ";
      $ret = $this->pgdatabase->execSQL($sql, 0, 0);
      if (!$ret['success']) {
        echo $r['table_name'].':<br>';
        echo substr($ret['msg'], 0, 1000).'<br>';
      }
    }
  }
  
  #echo $inserts;

  $sql = "COMMIT;";
  $ret = $this->pgdatabase->execSQL($sql, 0, 0);

  # Sequenzen auf Max-Werte setzen
  if ($ret['success']) {
    $sql = "
      with sequences as (
        select *
        from (
          select table_schema,
                table_name,
                column_name,
                pg_get_serial_sequence(format('%I.%I', table_schema, table_name), column_name) as col_sequence
          from information_schema.columns
          where table_schema = 'kvwmap'
        ) t
        where col_sequence is not null
      ), maxvals as (
        select table_schema, table_name, column_name, col_sequence,
                (xpath('/row/max/text()',
                  query_to_xml(format('select max(%I) from %I.%I', column_name, table_schema, table_name), true, true, ''))
                )[1]::text::bigint as max_val
        from sequences
      ) 
      select table_schema, 
            table_name, 
            column_name, 
            col_sequence,
            coalesce(max_val, 0) as max_val,
            setval(col_sequence, coalesce(max_val + 1, 1)) --<< this will change the sequence
      from maxvals;";
    $this->pgdatabase->execSQL($sql, 0, 0);

    $result[0] = false; # Migration bestätigen
  }
  else {
    echo substr($ret['msg'], 0, 1000);
    $result[0] = true; # Migration nicht bestätigen
  }

?>