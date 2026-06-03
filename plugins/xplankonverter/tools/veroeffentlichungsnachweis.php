<?php
// ToDos:
// pkorduan aus veroeffentlichungsprotokoll->find_users raus!
// debug_mode in nachweis_test_cases.json anpassen
// function send_alert nach veroeffentlichungsnachweis umbauen.

/**
 * auslegung Auslegung
 *   hat veroeffentlichungsprotokoll Veroeffentlichungsprotokoll
 *     hat nachweise Veroeffentlichungsnachweise
 *     hat nachweis_luecken PgObject
 *   hat plan XP_Plan
 *     hat veroeffentlichungsprotokoll_dokumente PgObject
 *
 */
$pruefzeit = time();
$pruefstunde = (int)($pruefzeit / 3600) * 3600;
echo_log("\n". date('Y-m-d H:i:s', $pruefzeit) . ' Starte Prüfung');
// Running this script with a cron job like this:
// cd /var/www/apps/kvwmap/plugins/xplankonverter/tools; php -f veroeffentlichungsnachweis.php login_name=pkorduan >> /var/www/logs/cron/veroeffentlichungsnachweis.log 2>&1
error_reporting(E_ALL & ~(E_STRICT|E_NOTICE|E_WARNING));

try {
  include('../../../credentials.php');
  include('../../../config.php');
  include(PLUGINS . 'xplankonverter/config/config.php');
  $test_cases = json_decode(file_get_contents('nachweis_test_cases.json'));
  $debug_mode = 1;
  if (
    $test_cases->is_testzeit AND
    date('Y-m-d H:m:s') >= $test_cases->testzeitraum->start  AND
    date('Y-m-d H:m:s') < $test_cases->testzeitraum->ende
  ) {
    define('AUSLEGUNG_MODE', 'dev');
    echo_log('Testmodus an, Konfigurationseinstellungen: ' . print_r($test_cases, true), 1);
    $debug_mode = $test_cases->debug_mode;
    define('AUSLEGUNG_URL', $test_cases->auslegung_url);
  }
  else {
    define('AUSLEGUNG_MODE', 'prod');
    define('AUSLEGUNG_URL', "https://bplan.geodaten-mv.de/bauportal/Uebersicht/Details");
  }
  include(WWWROOT . APPLVERSION . 'funktionen/allg_funktionen.php');
  include(CLASSPATH . 'kvwmap.php');
  include(CLASSPATH . 'log.php');
  include(CLASSPATH . 'rolle.php');
  include(CLASSPATH . 'stelle.php');
  include(CLASSPATH . 'users.php');
  include(CLASSPATH . 'Layer.php');
  include(CLASSPATH . 'postgresql.php');
  include(PLUGINS . 'xplankonverter/model/XP_Plan.php');
  include(PLUGINS . 'xplankonverter/model/auslegung.php');
  include(PLUGINS . 'xplankonverter/model/veroeffentlichungsnachweis.php');
  include(PLUGINS . 'xplankonverter/model/veroeffentlichungsprotokoll.php');

  define('DBWRITE', DEFAULTDBWRITE);
  $language = 'german';
  $debug = new Debugger(DEBUGFILE);

  if (LOG_LEVEL > 0) {
    $log_postgres = new LogFile(LOGFILE_POSTGRES, 'text', 'Log-Datei Postgres', '------v: ' . date("Y:m:d H:i:s", time()));
  }
  $GUI = new GUI('', '', '');
  if (!$GUI->is_tool_allowed('only_cli')) exit;
  $GUI->pgdatabase = new pgdatabase();
  $GUI->pgdatabase->open();
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
  if (!array_key_exists('stelle_id', $GUI->formvars)) {
    throw new ErrorException('Parameter stelle_id wurde nicht übergeben.');
  }
  if ($GUI->formvars['stelle_id'] == '') {
    throw new ErrorException('Parameter stelle_id ist leer.');
  }
  if (!array_key_exists('login_name', $GUI->formvars)) {
    throw new ErrorException('Parameter login_name wurde nicht übergeben.');
  }
  if ($GUI->formvars['login_name'] == '') {
    throw new ErrorException('Parameter login_name ist leer.');
  }
  $GUI->Stelle = new stelle($GUI->formvars['stelle_id'], $GUI->pgdatabase);
  $GUI->user = new user($GUI->formvars['login_name'], 0, $GUI->pgdatabase);
  $GUI->user->setRolle($GUI->formvars['stelle_id']);
  $GUI->debug->user_funktion = 'admin';

  # Aktuelle Auslegungen abfragen
  echo_log('Frage aktuelle Auslegungen ab: ', 2);
  $result = Auslegung::find_aktuelle($GUI, $GUI->formvars['plan_gml_id'], $pruefzeit);
  if (!$result['success']) {
    echo_log('Fehler in tool veroeffentlichungsnachweis.php ' . __LINE__ . ': ' . $result['msg'], 1);
    exit;
  }
  $auslegungen = $result['auslegungen'];
  $nachweis_obj = new PgObject($GUI, 'xplankonverter', 'veroeffentlichungsnachweise');
  foreach ($auslegungen AS $auslegung) {
     echo_log('Auslegung ' . $auslegung->get('plan_gml_id') . ' von: ' . $auslegung->get('startdatum') . ' bis: ' . $auslegung->get('enddatum') . ' Anzahl Dokumente: ' . count($auslegung->plan->veroeffentlichungsprotokoll_dokumente), 2);
    if (!$auslegung->veroeffentlichungsprotokoll_exists()) {
      $result = Veroeffentlichungsprotokoll::open($auslegung, $pruefstunde);
      if (!$result['success']) {
        echo_log('Fehler beim Anlegen des Veröffentlichungsprotokolls. ' . $result['msg'], 1);
        exit;
      }
      $auslegung->veroeffentlichungsprotokoll = $result['protokoll'];
      $auslegung->veroeffentlichungsprotokoll->create_and_send_ueberwachungsbeginn_alert($auslegung, $pruefzeit);
      echo_log('Veröffentlichungsprotokoll angelegt für Planart: ' . $auslegung->get('planart') . ' plan_gml_id: ' . $auslegung->get('plan_gml_id') . ' lfdnr: ' . $auslegung->get('lfdnr'), 2);
    }

    $pruef_result = pruefe_nachweise($auslegung->veroeffentlichungsprotokoll, $pruefstunde);
    if ($pruef_result['pruefcode'] == -1) {
      $save_result = Veroeffentlichungsnachweis::save_veroeffentlichungsnachweis_luecke($auslegung, $pruef_result);
      if (!$save_result['success']) {
        $err_msgs[] = 'Fehler beim Speichern der Nachweislücke ' . $save_result['msg'];
        continue;
      }
      $auslegung->veroeffentlichungsprotokoll->create_and_send_nachweis_luecke_alert($auslegung, $pruef_result);
    }

    $pruef_result = pruefe_auslegung(AUSLEGUNG_URL . '?type=' . urlencode($auslegung->get_plan_type()) . '&id=' . $auslegung->get('plan_gml_id'), $auslegung->get('plan_gml_id'));
    if ($pruef_result['pruefcode'] > 0) {
      $save_result = Veroeffentlichungsnachweis::save_veroeffentlichungsnachweis($auslegung, $pruefstunde, $pruef_result);
      if (!$save_result['success']) {
        $err_msgs[] = 'Fehler beim Speichern des Veröffentlichungsnachweises: ' . $save_result['msg'];
        continue;
      }
    }
    echo_log('Update veroeffentlichungsprotokoll last_pruefcode: ' . $pruef_result['pruefcode'] . ', last_pruefung: ' . date("Y-m-d H:i:s", $pruefzeit) . ', observation_start: ' . $auslegung->veroeffentlichungsprotokoll->get('observationstart') . ', pruefstunde: ' . date('Y-m-d H:i:s', $pruefstunde) . ', pruefungen_seit_observerationstart: ' . (volle_stunden($auslegung->veroeffentlichungsprotokoll->get('observationstart'), date('Y-m-d H:i:s', $pruefstunde)) + 1), 2);
    $result = $auslegung->veroeffentlichungsprotokoll->update_attr(array(
      "last_pruefcode = " . $pruef_result['pruefcode'],
      "last_pruefung = '" . date("Y-m-d H:i:s", $pruefzeit) . "'",
      "pruefungen_seit_observationstart = " . (volle_stunden($auslegung->veroeffentlichungsprotokoll->get('observationstart'), date('Y-m-d H:i:s', $pruefstunde)) + 1)
    ), true);
    $fehler = pg_last_error();
    if ($fehler) {
      echo_log('Fehler bei der Aktualisierung des Prüfprotokolls. Fehler: ' . $fehler, 2);
      exit;
    }

    $result = pruefe_5_stunden_zeitraum($nachweis_obj, $auslegung, $pruefstunde);
    if (!$result['success']) {
      echo_log('Fehler bei der Überprüfung der Nachweise der letzten 5 Stunden: ' . $result['msg']);
      exit;
    }
    if ($result['num_positiv'] < 5) {
      echo_log('weniger als 5 Nachweisfehler in den letzten 5 Stunden.', 2);
      continue;
    }
    echo_log('5 Nachweisfehler in den letzten 5 Stunden gefunden', 1);
    echo_log('Der Plan ' . $auslegung->plan->get('name') . ' Nr ' . $auslegung->plan->get('nummer') . ' gml_id: ' . $auslegung->plan->get('gml_id') . ' mit Auslegungszeitraum von ' . $auslegung->get('startdatum') . ' bis ' . $auslegung->get('enddatum') . ' war am ' . $pruefstundezeit . ' auf dem Bau- und Planungsportal für 5 Stunden nicht verfügbar!', 1);
    $auslegung->veroeffentlichungsprotokoll->create_and_send_auslegung_alert($auslegung, $pruefstunde);

    // Trage für die Nachweise ein, das der Fehler gemeldet wurde
    echo_log('Trage für die Nacheise der letzten 5 Stunden ein dass sie gemeldet wurden.', 2);
    $nachweis_obj->update_attr(
      array("gemeldet_am = '" . date('Y-m-d H:i:s', $pruefzeit) . "'"),
      false,
      "
        protokoll_id = " . $auslegung->veroeffentlichungsprotokoll->get('id') . " AND
        pruefstunde > '" . date('Y-m-d H:i:s', $pruefstunde - 5 * 3600) . "' AND
        gemeldet_am IS NULL
      "
    );
  }

  // Finde beendete Auslegungen. Erzeuge, versende und schließe die Veröffentlichungsprotokolle.
  echo_log('Suche beendete Auslegungen:', 2);
  $result = Auslegung::find_completed($GUI, $pruefzeit, $GUI->formvars['plan_gml_id']);
  if (!$result['success']) {
    echo_log('Fehler in veroeffentlichungsnachweis.php 153 => ' . $result['msg'], 1);
    exit;
  }
  foreach($result['completed_auslegungen'] AS $auslegung) {
    echo_log('veroeffentlichungsnachweis.php ' . __LINE__ . ': Erzeuge und sende Protokoll der Auslegung mit ' . count($auslegung->veroeffentlichungsprotokoll->nachweise) . ' Nachweisen und ' . count($auslegung->veroeffentlichungsprotokoll->nachweis_luecken) . ' Lücken.', 2);
    $auslegung->veroeffentlichungsprotokoll->create_and_send_protokoll($auslegung);
    $result = $auslegung->veroeffentlichungsprotokoll->update_attr_prep(array("observationend" => date('Y-m-d H:i:s', $pruefzeit)), true);
    if (!$result['success']) {
      echo_log('Fehler bei der Aktualisierung des observationend des Veröffentlichungsprotokolls in Line: ' . __LINE__ . ': ' . $result['msg'], 1);
      exit;
    }
  }

  if (count($err_msgs) > 0) {
    throw new ErrorException(implode("\n", $err_msgs));
  }
}
catch (Exception $e) {
  echo_log('Fehler: ' . $e);
}

/**
 * 
 * @msg String Die Fehlermeldung
 * @debug_level Integer Je höher desto weniger wird geloggt.
 * Es wird nur geloggt wenn $debug_level kleiner oder gleich $debug_mode ist.
 */
function echo_log($msg, $debug_level = 1) {
  global $debug_mode;
  if ($debug_mode >= $debug_level) {
    echo "\n" . $msg;
  }
}

function include_($filename) {
  include_once $filename;
}

/**
 * Prüft ob ein Plan unter der angegebenen $url gefunden wurde
 */
function pruefe_auslegung($url, $gml_id) {
  echo_log('Prüfe Auslegung mit url: ' . $url, 0);
  $ch = curl_init($url);
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_FOLLOWLOCATION => true,
  ]);

  $body = curl_exec($ch);
  if ($body === false) {
    $error = curl_error($ch);
    return array(
      'msg' => 'curl_exec nicht ausführbar: ' . $error,
      'pruefcode' => 3
    );
  } else {
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  }

  curl_close($ch);
  $msg = '';

  // a) Erreichbarkeit prüfen
  if ($httpCode !== 200) {
    $msg = "Webseite nicht erreichbar (HTTP $httpCode)";
    $pruefcode = 2;
  }
  else {
    echo_log('Prüfe ob gml_id: ' . $gml_id . ' im Body vorkommt', 2);
    echo_log('Body: ' . $body, 3);
    if (strpos($body, 'value="' . $gml_id . '"') !== false) {
      $msg = "Plan veröffentlicht";
      $pruefcode = 0;
    } elseif (strpos($body, "Keinen Plan gefunden!") !== false) {
      $msg = "Plan im Bau- und Planungsportal nicht gefunden";
      $pruefcode = 1;
    } else {
      $msg = "Unerwartete Antwort";
      $pruefcode = 4;
    }
  }
  echo_log('Prüfcode: ' . $pruefcode . ' Ergebnis: ' . $msg, 2);
  return array(
    'msg' => $msg,
    'pruefcode' => $pruefcode
  );
}

/**
 * Prüft ob die Nachweise der Auslegung für das Veröffentlichungsprotokoll korrekt registriert sind.
 * Dazu wird die Anzahl der registrierten Prüfungen im Zeitraum seit observationstart mit den vergangenen Stunden verglichen.
 * Ist $pruefungen_seit_observationstart < $num_pruefungen_soll wird die observationstart und pruefungen_seit_observation neu gesetzt
 * und die Fehlermeldung mit prüfcode -1 zurückgegeben.
 * Als Prüfzeit wird die volle Stunde + 3 Minuten verwendet um eventuelle Ungenauigkeiten beim Starten des cronjobs und durch die Laufzeit des Scriptes zu berücksichtigen.
 * Ergibt die Prüfung keinen Fehler wird nur die Anzahl der Prüfungen seit Start um ein hochgezählt.
 */
function pruefe_nachweise($veroeffentlichungsprotokoll, $pruefstunde) {
  $num_pruefungen_soll = volle_stunden($veroeffentlichungsprotokoll->get('observationstart'), date("Y-m-d H:i:s", $pruefstunde));
  $num_pruefungen_ist = $veroeffentlichungsprotokoll->get('pruefungen_seit_observationstart');
  $pruef_gap = $num_pruefungen_soll - $num_pruefungen_ist;
  if ($pruef_gap < 1) {
    return array(
      'pruefcode' => 0,
      'msg' => 'Prüfung der Nachweise ergab keine Lücke.'
    );
  }
  else {
    // Es wurde eine Lücke in der Protokollierung gefunden. Starte die Überprufung zur vollen Stunde neu.
    $veroeffentlichungsprotokoll->update_attr(array(
      "observationstartdatum = '" . date('Y-m-d H:i:s', $pruefstunde) . "'",
      "pruefungen_seit_observationstart = 0"
    ), true);
    return array(
      'pruefcode' => -1,
      'gap_start' => $pruefstunde - $pruef_gap * 3600,
      'gap_end' => $pruefstunde
    );
  }
}

/**
 * Abfrage ob es in den letzten 5 Stunden nur Fehler gab.
 * Wenn in diesem Zeitraum mindestens ein positiver Nachweis gefunden wurde oder
 * wenn negative Nachweise gefunden werden, die aber schon gemeldet wurden,
 * wird das nicht als Nachweisfehler im Zeitraum gesamt gewertet.
 * Nur wenn kein positiver und auch kein schon gemeldeter gefunden wird, gilt das als Nachweisfehler.
 */
function pruefe_5_stunden_zeitraum($nachweis_obj, $auslegung, $pruefstunde) {
  global $debug_mode;
  echo_log('Suche Nachweisfehler für den Zeitraum der letzten 5 Stunden für planart: ' . $auslegung->get('planart') . ', gml_id: ' . $auslegung->get('plan_gml_id') . ', lfdnr: ' . $auslegung->get('lfdnr'), 2);
  $positive_nachweise = $nachweis_obj->find_where(
    "
      protokoll_id = " . $auslegung->veroeffentlichungsprotokoll->get('id') . " AND
      pruefstunde > '" . date('Y-m-d H:i:s', $pruefstunde - 5 * 3600) . "' AND
      gemeldet_am IS NULL
    ", // where
    "pruefstunde", // order
    "DISTINCT pruefstunde" // select
  );
  if ($fehler = pg_last_error()) {
    return array(
      'success' => false,
      'msg' => $fehler
    );
  }
  return array(
    'success' => true,
    'num_positiv' => count($positive_nachweise)
  );
}

?>