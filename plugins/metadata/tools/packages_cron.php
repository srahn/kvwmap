<?
  $timestamp = date("Y-m-d H:i:s", time());
  echo "\n\n" . $timestamp;
  // Running this script with a cron job like this:
  // cd /var/www/apps/kvwmap/plugins/metadata/tools; php -f packages_cron.php login_name=pkorduan >> /var/www/logs/cron/packages_cron.log 2>&1
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
    include(CLASSPATH . 'Layer.php');
    include(CLASSPATH . 'postgresql.php');

    define('DBWRITE', DEFAULTDBWRITE);
    $language = 'german';
    $debug = new Debugger(DEBUGFILE);
    $debug->user_funktion = 'admin';

    if (LOG_LEVEL > 0) {
      $log_postgres = new LogFile(LOGFILE_POSTGRES, 'text', 'Log-Datei Postgres', '------v: ' . date("Y:m:d H:i:s", time()));
    }

    $GUI = new GUI('', '', '');

    if (!$GUI->is_tool_allowed('only_cli')) exit;
    $GUI->pgdatabase = new pgdatabase();
    $GUI->pgdatabase->open();
    $ressources_layer = Layer::find_by_id($GUI, METADATA_RESSOURCES_LAYER_ID);

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
    $err_msgs = array();
    $go = (isset($GUI->formvars['go']) ? $GUI->formvars['go'] : '');
    switch ($go) {
      case 'metadata_create_bundle_package' : {
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

        $GUI->Stelle = new stelle($GUI->formvars['stelle_id'], $GUI->pgdatabase);
        $GUI->user = new user($GUI->formvars['login_name'], 0, $GUI->pgdatabase);
        $GUI->user->setRolle($GUI->formvars['stelle_id']);
        $msg = 'Create bundle package for stelle_id: ' . $GUI->formvars['stelle_id'];
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
          $updated_at = strtotime($package_in_progress->get('updated_at'));
          $now = time();
          if ($now - $updated_at > 1800) {
            // cancle packing for package in progress
            $msg = 'Fehler beim Exportieren des Layers ID: ' . $package_in_progress->get('layer_id') . "\nDas Packen wurde " . date('d.m.Y H:i:s', $updated_at) . ' beauftragt und ist ' . date('d.m.Y H:i:s', $now) . ' noch nicht fertig. Abbruch weil es über 30 min gedauert hat.';
            $package_in_progress->log($msg);
            $package_in_progress->update_attr(array('pack_status_id = -1'));
          }
          else {
            $err_msgs[] = 'Auftrag abgelehnt. Es wird gerade ein anderes Packet Id: ' . $package_in_progress->get_id() . ' gepackt.';
            break;
          }
        }

        // find registered packages to pack
        $packages = DataPackage::find_by_status($GUI, 2);
        if (count($packages) == 0) {
          $err_msgs[] = 'Keine Ressource gefunden die gepackt werden muss.';
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

        foreach ($packages AS $package) {
          // create stelle and user and set rolle for export and pack job
          $GUI->Stelle = new stelle($package->get('stelle_id'), $GUI->pgdatabase);
          $GUI->user = new user($GUI->formvars['login_name'], 0, $GUI->pgdatabase);
          $GUI->user->setRolle($package->get('stelle_id'));
          // start packing
          $response = $GUI->metadata_create_data_package($package->get_id());
          if (!$response['success']) {
            // Fehler loggen
            $err_msgs[] = $response['msg'];
          }
        }
        if (count($err_msgs) == 0) {
          $msg = 'Alle beauftragten Pakete erfolgreich gepackt.';
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
    echo "\n" . $msg;
  }
?>