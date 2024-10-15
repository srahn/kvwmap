<?
  //error_reporting(E_ALL);
  error_reporting(E_ALL & ~(E_STRICT|E_NOTICE|E_WARNING));
  try {
    include('../../../credentials.php');
    include('../../../config.php');
    include(PLUGINS . 'metadata/config/config.php');
    include(WWWROOT . APPLVERSION . 'funktionen/allg_funktionen.php');
    include(CLASSPATH . 'kvwmap.php');
    include(CLASSPATH . 'log.php');
    include(CLASSPATH . 'rolle.php');
    include(CLASSPATH . 'stelle.php');
    include(CLASSPATH . 'users.php');
    include(CLASSPATH . 'mysql.php');
    include(CLASSPATH . 'postgresql.php');

    define('DBWRITE', DEFAULTDBWRITE);

    $debug = new Debugger(DEBUGFILE);
    $debug->user_funktion = 'admin';

    if (LOG_LEVEL > 0) {
      $log_mysql = new LogFile(LOGFILE_MYSQL,'text','Log-Datei MySQL', '#------v: ' . date("Y:m:d H:i:s", time()));
      $log_postgres = new LogFile(LOGFILE_POSTGRES, 'text', 'Log-Datei Postgres', '------v: ' . date("Y:m:d H:i:s", time()));
    }

    $GUI = new GUI('', '', '');

    if (!$GUI->is_tool_allowed('only_cli')) exit;
    $userDb = new database();
    $userDb->host = MYSQL_HOST;
    $userDb->user = MYSQL_USER;
    $userDb->passwd = MYSQL_PASSWORD;
    $userDb->dbName = MYSQL_DBNAME;
    $GUI->database = $userDb;
    $GUI->database->open(true);
    $GUI->pgdatabase = new pgdatabase();
    $GUI->pgdatabase->open(1);

    include_once(PLUGINS . 'metadata/model/kvwmap.php');
    include_once(PLUGINS . 'metadata/model/Ressource.php');
    include_once(PLUGINS . 'metadata/model/DataPackage.php');
    /**
     * Request for the first package to pack
     *  status:
     * -1 - Abbruch wegen Fehler
     *  0 - Uptodate
     *  1 - Paket noch nicht erstellt
     *  2 - Paketerstellung beauftragt
     *  3 - Paketerstellung in Arbeit
     *  4 - Paket fertiggestellt
     */

    // find if any ressource has pack status in progress
    if ($package_in_progress = DataPackage::find_first_by_status($GUI, 3)) {
      echoLog('Auftrag abgelehnt. Es wird gerade ein anderes Packet Id: ' . $package_in_progress->get_id() . ' gepackt.'); exit;
    }

    // find registered packages to pack
    $packages = DataPackage::find_by_status($GUI, 2);
    if (count($packages) == 0) {
      echoLog('Keine Ressource gefunden die gepackt werden muss.');
      exit;
    }

    $err_msgs = array();
    foreach($packages AS $package) {
      // create stelle and user and set rolle for export and pack job
      $GUI->Stelle = new stelle($package->get('stelle_id'), $GUI->database);
      $GUI->user = new user(METADATA_WORKER_LOGIN_NAME, 0, $GUI->database);
      $GUI->user->setRolle($package->get('stelle_id'));
      // start packing
      $response = $GUI->metadata_create_data_package($package->get_id());
      if (!$response['success']) {
        // Fehler loggen
        $err_msgs[] = $response['msg'];
      }
    }
    if (count($err_msgs) == 0) {
      $response['success'] = true;
      $response['msg'] = 'Alle beauftragten Pakete erfolgreich gepackt.';
    }
    else {
      $resonse['success'] = false;
      $response['msg'] = implode(' ', $err_msgs);
    }

    // Ergebnis loggen
    echoLog($response['msg']);
  }
  catch (Exception $e) {
    $package->update_attr(array('pack_status_id = -1'));
    echoLog('Fehler: ' . $e);
  }

  function echoLog($msg) {
    $timestamp = date("Y-m-d H:i:s", time());
    echo "\n\n" . $timestamp;
    echo "\n" . $msg;
  }
?>