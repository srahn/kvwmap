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

    if (isset($argv)) {
      array_shift($argv);
      $_REQUEST = array();
      foreach ($argv AS $arg) {
        list($key, $val) = explode('=', $arg);
        $_REQUEST[$key] = $val;
      }
      $GUI->formvars = $_REQUEST;
    }
    $go = (isset($GUI->formvars['go']) ? $GUI->formvars['go'] : '');
    // echo 'go: ' . $go;
    $err_msgs = array();
    switch ($go) {
      case 'metadata_create_bundle_package' : {
        if (!array_key_exists('stelle_id', $GUI->formvars)) {
          $err_msg[] = 'Parameter stelle_id wurde nicht übergeben.';
          break;
        }
        if ($GUI->formvars['stelle_id'] == '') {
          $err_msg[] = 'Parameter stelle_id ist leer.';
          break;
        }
        if (!array_key_exists('login_name', $GUI->formvars)) {
          $err_msg[] = 'Parameter login_name wurde nicht übergeben.';
          break;
        }
        if ($GUI->formvars['login_name'] == '') {
          $err_msg[] = 'Parameter login_name ist leer.';
          break;
        }

        $GUI->Stelle = new stelle($GUI->formvars['stelle_id'], $GUI->database);
        $GUI->user = new user($GUI->formvars['login_name'], 0, $GUI->database);
        $GUI->user->setRolle($GUI->formvars['stelle_id']);
        $response = $GUI->metadata_create_bundle_package($GUI->formvars['stelle_id']);
        if (!$response['success']) {
          $err_msgs[] = $response['msg'];
          break;
        }
        $msg = $response['msg'];
      } break;
      default : {
        // handle cases without go argument
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
          $err_msg[] = 'Auftrag abgelehnt. Es wird gerade ein anderes Packet Id: ' . $package_in_progress->get_id() . ' gepackt.';
          break;
        }

        // find registered packages to pack
        $packages = DataPackage::find_by_status($GUI, 2);
        if (count($packages) == 0) {
          $err_msg[] = 'Keine Ressource gefunden die gepackt werden muss.';
          break;
        }

        if (!array_key_exists('login_name', $GUI->formvars)) {
          $err_msg[] = 'Parameter login_name wurde nicht übergeben.';
          break;
        }
        if ($GUI->formvars['login_name'] == '') {
          $err_msg[] = 'Parameter login_name ist leer.';
          break;
        }

        foreach ($packages AS $package) {
          // create stelle and user and set rolle for export and pack job
          $GUI->Stelle = new stelle($package->get('stelle_id'), $GUI->database);
          $GUI->user = new user($GUI->formvars['login_name'], 0, $GUI->database);
          $GUI->user->setRolle($package->get('stelle_id'));
          // start packing
          $response = $GUI->metadata_create_data_package($package->get_id());
          if (!$response['success']) {
            // Fehler loggen
            $err_msgs[] = $response['msg'];
          }
        }
        if (count($err_msg) == 0) {
          $msg = 'Alle beauftragten Pakete erfolgreich gepackt.';
        }
      }
    }

    if (count($err_msg) == 0) {
      return array(
        'success' => true,
        'msg' => $msg
      );
    }
    else {
      // Ergebnis loggen
      $msg = implode(' ', $err_msg);
      echoLog($msg);
      return array(
        'success' => false,
        'msg' => $msg
      );
    }
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