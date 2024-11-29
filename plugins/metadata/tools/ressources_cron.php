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

    // if (!$GUI->is_tool_allowed('only_cli')) exit;
    $userDb = new database();
    $userDb->host = MYSQL_HOST;
    $userDb->user = MYSQL_USER;
    $userDb->passwd = MYSQL_PASSWORD;
    $userDb->dbName = MYSQL_DBNAME;
    $GUI->database = $userDb;
    $GUI->database->open(true);
    $GUI->pgdatabase = new pgdatabase();
    $GUI->pgdatabase->open(1);

    if (isset($argv)) {
      array_shift($argv);
      $_REQUEST = array();
      foreach ($argv AS $arg) {
        list($key, $val) = explode('=', $arg);
        $_REQUEST[$key] = $val;
      }
      $GUI->formvars = $_REQUEST;
    }
    $err_msgs = array();
    $go = (isset($GUI->formvars['go']) ? $GUI->formvars['go'] : '');

    include(PLUGINS . 'metadata/control/index.php');

    switch ($go) {
      case 'metadata_update_outdated' : {
        if (!array_key_exists('stelle_id', $GUI->formvars)) {
          $err_msgs[] = 'Parameter stelle_id wurde nicht übergeben.';
          break;
        }
        if ($GUI->formvars['stelle_id'] == '') {
          $err_msgs[] = 'Parameter stelle_id ist leer.';
          break;
        }
         if (!array_key_exists('login_name', $GUI->formvars)) {
          $err_msgs[] = 'Parameter login_name wurde nicht übergeben.';
          break;
        }
        if ($GUI->formvars['login_name'] == '') {
          $err_msgs[] = 'Parameter login_name ist leer.';
          break;
        }

        $GUI->Stelle = new stelle($GUI->formvars['stelle_id'], $GUI->database);
        $GUI->user = new user($GUI->formvars['login_name'], 0, $GUI->database);
        $GUI->user->setRolle($GUI->formvars['stelle_id']);
        if ($GUI->formvars['ressource_id'] == '') {
          $msg = 'Update outdated ressources';
        }
        else {
          $msg = 'Update Ressource id: ' . $GUI->formvars['ressource_id'];
        }

        $response = Ressource::update_outdated($GUI, $GUI->formvars['ressource_id'], $GUI->formvars['method_only']);

        if (!$response['success']) {
          $err_msgs[] = $response['msg'];
          break;
        }
        $msg = $response['msg'];
      } break;

      default : {
        if (!array_key_exists('go', $GUI->formvars)) {
          $err_msgs[] = 'Parameter go wurde nicht übergeben.';
        }
        if ($GUI->formvars['go'] == '') {
          $err_msgs[] = 'Parameter go ist leer.';
        }
      }
    }

    if (count($err_msgs) == 0) {
      echoLog($msg);
      return array(
        'success' => true,
        'msg' => $msg
      );
    }
    else {
      // Ergebnis loggen
      $msg = implode(' ', $err_msgs);
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