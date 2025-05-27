<?
  ini_set('memory_limit', '8192M');
  global $GUI;
	global $kvwmap_plugins;

  # credentials befüllen
  $credentials = $this->pgdatabase->get_credentials(POSTGRES_CONNECTION_ID);
  file_put_contents(
    'credentials.php', 
    "
<?
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
      echo "Fehler beim Öffnen der Datei.\n";
  }

  # Plugin-Migrationen eintragen
	$plugin_migrations = [
		'alkis' => [
			'2025-05-20_15-46-00_config.sql'
		],
		'anliegerbetraege' => [
			'2025-05-20_15-46-00_layer.sql',
			'2025-05-20_15-46-00_menues.sql'
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
			'2025-05-20_15-46-00_menues.sql'
		],
		'ukos' => [
			'2025-05-20_15-46-00_menues.sql'
		],
		'xplankonverter' => [
			'2025-05-20_15-46-00_config.sql',
			'2025-05-20_15-46-00_layer.sql',
			'2025-05-20_15-46-00_menues.sql'
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
      table_type='BASE TABLE';
	";
  $ret = $this->database->execSQL($sql,4, 1);
  $res = $this->database->result;
  while ($r = $res->fetch_assoc()){
    $inserts .= implode(chr(13), $this->database->create_insert_dump($r['table_name'], '', 'SELECT * FROM '. $r['table_name'])['insert']);
  }
  
  #echo $inserts;

  $sql = "
    BEGIN;
    SET search_path = kvwmap, public;
    SET session_replication_role = 'replica';
    " . $inserts . "
    SET session_replication_role = 'origin';
    COMMIT;
  ";
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
            setval(col_sequence, coalesce(max_val, 1)) --<< this will change the sequence
      from maxvals;";
    $this->pgdatabase->execSQL($sql, 0, 0);

    $result[0] = false; # Migration bestätigen
  }
  else {
    $result[0] = true; # Migration nicht bestätigen
  }

?>