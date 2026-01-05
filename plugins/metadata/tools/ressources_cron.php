<?
/**
 * Script run Updates for outdated ressources
 *   - See ressource method find_outdated for more details about when a ressource is outdated.
 * ToDo: To implement in Ressource->update_outdated()
 *   - A ressource will only be updated when all its source ressources are uptodate yet.
 */
  //error_reporting(E_ALL);
  error_reporting(E_ALL & ~(E_STRICT|E_NOTICE|E_WARNING));

  function include_($filename) {
    include_once $filename;
  }

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
    include(CLASSPATH . 'postgresql.php');

    define('DBWRITE', DEFAULTDBWRITE);
    $language = 'german';
    $debug = new Debugger(DEBUGFILE, 'text/plain');
    $debug->user_funktion = 'admin';

    if (LOG_LEVEL > 0) {
      $log_postgres = new LogFile(LOGFILE_POSTGRES, 'text', 'Log-Datei Postgres', '------v: ' . date("Y:m:d H:i:s", time()));
    }

    $GUI = new GUI('', '', ''); // übernimmt $debug aus globaler Variable

    // if (!$GUI->is_tool_allowed('only_cli')) exit;
    $GUI->pgdatabase = new pgdatabase();
    $GUI->pgdatabase->open();

    if (isset($argv)) {
      array_shift($argv);
      $_REQUEST = array();
      foreach ($argv AS $arg) {
        list($key, $val) = explode('=', $arg);
        $_REQUEST[$key] = $val;
      }
    }
    $GUI->formvars = $_REQUEST;

    $err_msgs = array();
    $go = (isset($GUI->formvars['go']) ? $GUI->formvars['go'] : '');

    include(PLUGINS . 'metadata/control/index.php');

    switch ($go) {
      case 'metadata_update_outdated' : {
        if (!array_key_exists('stelle_id', $GUI->formvars)) {
          $GUI->debug->show('Parameter stelle_id wurde nicht übergeben.', true);
          break;
        }
        if ($GUI->formvars['stelle_id'] == '') {
          $GUI->debug->show('Parameter stelle_id ist leer.', true);
          break;
        }
         if (!array_key_exists('login_name', $GUI->formvars)) {
          $GUI->debug->show('Parameter login_name wurde nicht übergeben.', true);
          break;
        }
        if ($GUI->formvars['login_name'] == '') {
          $GUI->debug->show('Parameter login_name ist leer.', true);
          break;
        }

        $GUI->Stelle = new stelle($GUI->formvars['stelle_id'], $GUI->pgdatabase);
        $GUI->user = new user($GUI->formvars['login_name'], 0, $GUI->pgdatabase);
        $GUI->user->setRolle($GUI->formvars['stelle_id']);
        $response = Ressource::update_outdated($GUI, $GUI->formvars['ressource_id'], $GUI->formvars['method_only']);
        if (!$response['success']) {
          $GUI->debug->show($response['msg'], true);
          break;
        }
        $GUI->debug->show($response['msg'], true);
      } break;

      default : {
        if (!array_key_exists('go', $GUI->formvars)) {
          $GUI->debug->show('Parameter go wurde nicht übergeben.', true);
        }
        if ($GUI->formvars['go'] == '') {
          $GUI->debug->show('Parameter go ist leer.', true);
        }
      }
    }

    $GUI->debug->show("Ende\n", true);
    $GUI->debug->close();
  }
  catch (Exception $e) {
    $package->update_attr(array('pack_status_id = -1'));
    $GUI->debug->show('Fehler: ' . $e, true);
  }
?>