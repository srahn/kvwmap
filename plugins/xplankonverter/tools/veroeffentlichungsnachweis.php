<?php
// Script zur Erstellung von Nachweisen der Veröffentlichung von Plänen auf dem Bau- und Planungsportal des Landes MV
// Running this script with a cron job like this:
// cd /var/www/apps/konverter/plugins/xplankonverter/tools; php -f veroeffentlichungsnachweis.php login_name=pkorduan stelle_id=1
// plan_gml_id= für einzelne Pläne
// cd /var/www/apps/konverter/plugins/xplankonverter/tools; php -f veroeffentlichungsnachweis.php login_name=pkorduan stelle_id=1 plan_gml_id=946a1aac-8f12-11f1-8f3b-0242ac001407
// gelogged wird in /var/www/logs/cron/veroeffentlichungsnachweis.log
// /var/www/logs/cron/veroeffentlichungsnachweis.log 2>&1
// ToDos:
// debug_mode in veroeffentlichungsnachweis_config.json anpassen
$config = json_decode(file_get_contents('veroeffentlichungsnachweis_config.json'));
$pruefzeit = time();
$pruefstunde = (int)($pruefzeit / 3600) * 3600;
echo_log("\n". date('Y-m-d H:i:s', $pruefzeit) . ' Starte Prüfung ----------------------------------------', 0);
$environment = getenv('NETWORK_NAME');
// echo "\nEnvironment: " . $environment . ' zugelassen: ' . (count($config->environments) === 0 ? 'keine' : implode(', ', $config->environments)) . ' vergleich: ' . in_array($environment, $config->environments);
if (count($config->environments) === 0 OR !in_array($environment,  $config->environments)) {
  echo "\nAbbruch weil " . $environment . " nicht in einer der konfigurierten Umgebungen ist. Zugelassen sind: " . (count($config->environments) === 0 ? 'keine' : implode(', ', $config->environments)) . "\n";
  // Wenn das in anderen Umgebungen laufen werden soll, hier exit auskommentieren!
  exit;
}
 /**
 * auslegung Auslegung
 *   hat veroeffentlichungsprotokoll Veroeffentlichungsprotokoll
 *     hat nachweise Veroeffentlichungsnachweise
 *     hat nachweis_luecken PgObject
 *   hat plan XP_Plan
 *     hat veroeffentlichungsprotokoll_dokumente PgObject
 *
 */
error_reporting(E_ALL & ~(E_STRICT|E_NOTICE|E_WARNING));

try {
  include('../../../credentials.php');
  include('../../../config.php');
  include(PLUGINS . 'xplankonverter/config/config.php');
  if (
    $config->is_testzeit AND
    date('Y-m-d H:m:s') >= $config->testzeitraum->start AND
    date('Y-m-d H:m:s') < $config->testzeitraum->ende
  ) {
    define('AUSLEGUNG_MODE', 'dev');
    define('AUSLEGUNG_URL', $config->auslegung_url);
  }
  else {
    define('AUSLEGUNG_MODE', 'prod');
    define('AUSLEGUNG_URL', "https://bplan.geodaten-mv.de/bauportal/Uebersicht/Details");
  }
  $debug_mode = $config->debug_mode;
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

  // echo_log('Config: ' . print_r($config, true), 2);

  # Aktuelle Auslegungen abfragen
   $result = Auslegung::find_aktuelle($GUI, $GUI->formvars['plan_gml_id'], $pruefzeit);
  if (!$result['success']) {
    echo_log('Fehler in tool veroeffentlichungsnachweis.php ' . __LINE__ . ': ' . $result['msg'], 1);
    exit;
  }
  $auslegungen = $result['auslegungen'];
  $nachweis_obj = new PgObject($GUI, 'xplankonverter', 'veroeffentlichungsnachweise');
  if (count($auslegungen) === 0) {
    echo_log('Keine aktuellen Auslegungen gefunden.', 2);
  }
  else {
    echo_log(count($auslegungen) . ' aktuelle Auslegungen gefunden.', 2);
  }
  foreach ($auslegungen AS $auslegung) {
    echo_log("\nAuslegung: " . $auslegung->get('planart') . ' gml_id: ' . $auslegung->get('plan_gml_id') . ' lfdnr: ' . $auslegung->get('lfdnr') . ' von: ' . $auslegung->get('startdatum') . ' bis: ' . $auslegung->get('enddatum') . ' Veröff-Datum: ' . $auslegung->get('veroeffentlichungsdatum') . ' Anzahl Dokumente: ' . count($auslegung->plan->veroeffentlichungsprotokoll_dokumente), 2);
    if ($auslegung->veroeffentlichungsprotokoll_exists()) {
      echo_log('Veröffentlichungsprotokoll existiert bereits.', 2);
    }
    else {
      if ($auslegung->veroeffentlicht_in_auslegungszeitraum($pruefzeit)) {
        echo_log('Veröffentlichungsdatum vor oder im Auslegungszeitraum', 2);
        // Protokoll anlegen und prüfen ob die Veröffentlichung zu spät ist
        $result = Veroeffentlichungsprotokoll::open($auslegung, $pruefstunde);
        if (!$result['success']) {
          echo_log('Fehler beim Anlegen des Veröffentlichungsprotokolls. ' . $result['msg'], 1);
          exit;
        }
        $auslegung->veroeffentlichungsprotokoll = $result['protokoll'];
        echo_log('Veröffentlichungsprotokoll angelegt', 2);
        $auslegung->veroeffentlichungsprotokoll->send_emails = $config->send_emails;
        $auslegung->veroeffentlichungsprotokoll->create_and_send_ueberwachungsbeginn_alert($auslegung, $pruefzeit);
      }
      else {
        echo_log('Nicht im Auslegungszeitraum veröffentlicht.', 2);
        continue;
      }
    }

    $auslegung->veroeffentlichungsprotokoll->send_emails = $config->send_emails;

    // Prüfe ob es Lücken in der Überwachung gab
    $pruef_result = pruefe_nachweisluecke($auslegung->veroeffentlichungsprotokoll, $pruefstunde);
    if ($pruef_result['pruefcode'] == -1) {
      $save_result = Veroeffentlichungsnachweis::save_veroeffentlichungsnachweis_luecke($auslegung, $pruef_result);
      if (!$save_result['success']) {
        $err_msgs[] = 'Fehler beim Speichern der Nachweislücke ' . $save_result['msg'];
        continue;
      }
      $auslegung->veroeffentlichungsprotokoll->create_and_send_nachweis_luecke_alert($auslegung, $pruef_result);
    }

    // Prüfe ob die Auslegung jetzt verfügbar ist.
    $pruef_result = pruefe_auslegung(AUSLEGUNG_URL . '?type=' . urlencode($auslegung->get_plan_type()) . '&id=' . $auslegung->get('plan_gml_id'), $auslegung->get('plan_gml_id'));
    if ($pruef_result['pruefcode'] > 0) {
      $save_result = Veroeffentlichungsnachweis::save_veroeffentlichungsnachweis($auslegung, $pruefstunde, $pruef_result);
      if (!$save_result['success']) {
        $err_msgs[] = 'Fehler beim Speichern des Veröffentlichungsnachweises: ' . $save_result['msg'];
      }
    }

    // Speicher die aktuellen Prüfergebnisse im Veröffentlichungsprotokoll
    echo_log('Update veroeffentlichungsprotokoll last_pruefcode: ' . $pruef_result['pruefcode'] . ', last_pruefung: ' . date("Y-m-d H:i:s", $pruefstunde), 2);
    $result = $auslegung->veroeffentlichungsprotokoll->update_attr(array(
      "last_pruefcode = " . $pruef_result['pruefcode'],
      "last_pruefung = '" . date("Y-m-d H:i:s", $pruefstunde) . "'"
    ), true);
    $fehler = pg_last_error();
    if ($fehler) {
      echo_log('Fehler bei der Aktualisierung des Prüfprotokolls. Fehler: ' . $fehler, 2);
      exit;
    }

    // Prüfe ob einen Zeitraum von mindestens 5 Stunden gab ohne Verfügbarkeit.
    $result = find_nachweisfehler($nachweis_obj, $auslegung, $pruefstunde);
    if (!$result['success']) {
      echo_log('Fehler bei der Überprüfung der Nachweise der letzten 5 Stunden: ' . $result['msg']);
      exit;
    }
    if ($result['num_nachweisfehler'] < 5) {
      echo_log('Keine gefunden.', 2);
      continue;
    }
    echo_log('5 Nachweisfehler in den letzten 5 Stunden gefunden.', 1);
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

  if ($config->beende_auslegungen) {
    //Finde beendete Auslegungen. Erzeuge, versende und schließe die Veröffentlichungsprotokolle.
    echo_log('Suche beendete Auslegungen:', 2);
    $result = Auslegung::find_completed($GUI, $pruefzeit, $GUI->formvars['plan_gml_id']);
    if (!$result['success']) {
      echo_log('Fehler in find_completed ' . date('Y-m-d H:i:s', $pruefzeit) . ': ' . $result['msg'], 1);
      exit;
    }
    foreach($result['completed_auslegungen'] AS $auslegung) {
      $auslegung->veroeffentlichungsprotokoll->send_emails = $config->send_emails;

      $result = $auslegung->veroeffentlichungsprotokoll->create_and_send_protokoll($auslegung);
      if (!$result['success']) {
        echo_log('Fehler beim Erzeugen und Senden des Veröffentlichungsprotokolls: ' . $result['msg'], 1);
      }
      else {
        $result = $auslegung->veroeffentlichungsprotokoll->update_attr_prep(array("observationend" => date('Y-m-d H:i:s', $pruefzeit)), true);
        echo_log("Protokoll zur Auslegung: " . $auslegung->get('planart') . ' gml_id: ' . $auslegung->get('plan_gml_id') . ' lfdnr: ' . $auslegung->get('lfdnr') . ' von: ' . $auslegung->get('startdatum') . ' bis: ' . $auslegung->get('enddatum') . ' mit ' . count($auslegung->veroeffentlichungsprotokoll->nachweise) . ' Nachweisen und ' . count($auslegung->veroeffentlichungsprotokoll->nachweis_luecken) . ' Lücken abgeschlossen.', 2);
        if (!$result['success']) {
          echo_log('Fehler bei der Aktualisierung des observationend des Veröffentlichungsprotokolls in Line: ' . __LINE__ . ': ' . $result['msg'], 1);
          exit;
        }
      }
    }
  }

  if (count($err_msgs) > 0) {
    throw new ErrorException(implode("\n", $err_msgs));
  }
}
catch (Exception $e) {
  echo_log('Fehler: ' . $e);
}

echo_log(date('Y-m-d H:i:s', $pruefzeit) . " Ende Prüfung ========================================\n", 0);

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
    // echo_log('Prüfe ob gml_id: ' . $gml_id . ' im Body vorkommt', 2);
    // echo_log('Body: ' . $body, 3);
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
function pruefe_nachweisluecke($veroeffentlichungsprotokoll, $pruefstunde) {
  if ($veroeffentlichungsprotokoll->get('last_pruefung') === null) {
    // Wenn die erste Prüfung erst 1 Stunde oder später nach dem Beginn der Auslegung stattgefunden erfolgt, ergibt sich die Anzahl der Prüfungen die hätten durchgeführt werden sollen aus
    // der Differenz der aktuellen Prüfstunde und dem Auslegungsstartdatum. Da die Prüfungen seit der Überwachung 0 ist, führt das zu einer Nachweislücke, die dokumentiert wird.
    $stunden_seit_last_pruefung = volle_stunden($veroeffentlichungsprotokoll->get('auslegungsstartdatum'), date("Y-m-d H:i:s", $pruefstunde));
  }
  else {
    // Wenn später die Überwachung noch mal ausgesetzt haben sollte, ist mindestens die Anzahl der pruefungen_seit_observationstart > 0
    // und es kann einfach geprüft werden ob die vollen Stunden von observationstart bis zur aktuellen Prüfstunde mit pruefungen_seit_observationstart übereinstimmen.
    $stunden_seit_last_pruefung = volle_stunden($veroeffentlichungsprotokoll->get('last_pruefung'), date("Y-m-d H:i:s", $pruefstunde));
  }
  if ($stunden_seit_last_pruefung <= 1) {
    return array(
      'pruefcode' => 0,
      'msg' => 'Prüfung der Nachweise ergab keine Lücke.'
    );
  }
  else {
    // Es wurde eine Lücke in der Protokollierung gefunden. Starte die Überprufung zur vollen Stunde neu.
    // Wenn wir hier aber die pruefungen_seit_observationstart wieder auf 0 setzen würde das bei der nächsten Prüfung wieder so gewertet werden als hätte die Prüfung zu spät stattgefunden.
    $veroeffentlichungsprotokoll->update_attr(array(
      "last_pruefung = '" . date('Y-m-d H:i:s', $pruefstunde) . "'"
    ), true);
    return array(
      'pruefcode' => -1,
      'gap_start' => ($pruefstunde - ($stunden_seit_last_pruefung * 3600)),
      'gap_end' => $pruefstunde
    );
  }
}

/**
 * Abfrage ob es in den letzten 5 Stunden nur Fehler gab.
 * Wenn in diesem Zeitraum mindestens ein positiver Nachweis existiert oder
 * wenn negative Nachweise existieren, die aber schon gemeldet wurden,
 * wird das nicht als Nachweisfehler im Zeitraum gesamt gewertet.
 * Nur wenn 5 noch nicht gemeldetet Nachweisfehler gefunden wurden, gilt das als Nachweisfehler.
 */
function find_nachweisfehler($nachweis_obj, $auslegung, $pruefstunde) {
  global $debug_mode;
  echo_log('Suche Nachweisfehler für den Zeitraum der letzten 5 Stunden.', 2);
  $nachweisfehler = $nachweis_obj->find_where(
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
    'num_nachweisfehler' => count($nachweisfehler)
  );
}
/*
--
-- Test-Veröffentlichungsnachweis
--

-- Fremdschlüssel setzen
DELETE FROM xplan_gml.bp_plan p
WHERE NOT EXISTS (
    SELECT 1
    FROM xplankonverter.konvertierungen k
    WHERE p.konvertierung_id = k.id
);
ALTER TABLE xplan_gml.bp_plan ADD CONSTRAINT fk_konvertierung_id FOREIGN KEY (konvertierung_id) REFERENCES xplankonverter.konvertierungen(id) ON DELETE CASCADE;

-- Konvertierung anlegen
WITH konvertierung AS (
  INSERT INTO xplankonverter.konvertierungen(
    bezeichnung,
    status,
    stelle_id,
    beschreibung,
    user_id,
    planart,
    veroeffentlichungsdatum
  )
  VALUES (
    'Testkonvertierung',
    'Angaben vollständig',
    130735351,
    'Konvertierung zum testen des Veröffentlichungsnachweises 946a1aac-8f12-11f1-8f3b-0242ac001407',
    56,
    'BP-Plan',
    current_date - INTERVAL '1 day'
  )
  RETURNING id
)
-- Plan anlegen
INSERT INTO xplan_gml.bp_plan (
  gml_id,
  name,
  nummer,
  fassungsbezeichnung,
  kommentar,
  planart,
  gemeinde,
  internalid,
  auslegungsstartdatum,
  auslegungsenddatum,
  raeumlichergeltungsbereich,
  konvertierung_id
)
SELECT
  '946a1aac-8f12-11f1-8f3b-0242ac001407'::uuid,
  'Testplan' AS name,
  '1' AS nummer,
  'Ursprungsplan' AS fassungsbezeichnung,
  'Plan zum Testen des Veröffentlichungsnachsweises' AS kommentar,
  ARRAY[1000::text::xplan_gml.bp_planart] AS planart,
  ARRAY[ROW('13073005', '130735351005', 'Altenpleen', 'Prohn')::xplan_gml.xp_gemeinde],
  '946a1aac-8f12-11f1-8f3b-0242ac001407',
  ARRAY[current_date] AS auslegungsstartdatum,
  ARRAY[current_date + INTERVAL '1 month'] AS auslegungsenddatum,
  ST_GeomFromText('MULTIPOLYGON(((373033.96 6026761.94,373009.31 6026755.54,373015.92 6026737.49,372999.82 6026733.5,373006.56 6026711.49,373008.42 6026705.24,373016.39 6026679.06,373018.84 6026671.11,373030.05 6026673.38,373036.76 6026674.69,373054.64 6026678.32,373056.1 6026670.19,373058.69 6026655.66,373059.19 6026652.8,373102.56 6026662.03,373095.88 6026713.49,373094.43 6026725.22,373112.1 6026727.27,373119.26 6026734.28,373118.88 6026737.49,373117.84 6026749.61,373117.04 6026759.67,373116.64 6026765.6,373079.56 6026761.99,373077.6 6026769.9,373077.03 6026772.33,373064.95 6026769.51,373047.14 6026765.33,373039.07 6026763.24,373033.96 6026761.94)))', 25833),
  konvertierung.id
FROM
  konvertierung;

-- Auslegung abfragen
SELECT
  a.*,
  k.id,
  k.beschreibung,
  p.internalid
FROM
  xplankonverter.auslegungen a JOIN
  xplan_gml.bp_plan p ON a.plan_gml_id = p.gml_id JOIN
  xplankonverter.konvertierungen k ON p.konvertierung_id = k.id
WHERE
  a.startdatum <= current_date AND
  a.enddatum >= current_date AND
  p.gml_id = '946a1aac-8f12-11f1-8f3b-0242ac001407'::uuid;

--
-- Diesen Befehl aufrufen um die E-Mails für den Begin der Auslegung zu erzeugen.
-- cd /var/www/apps/konverter/plugins/xplankonverter/tools; php -f veroeffentlichungsnachweis.php login_name=pkorduan stelle_id=1 plan_gml_id=946a1aac-8f12-11f1-8f3b-0242ac001407
--

-- Veröffentlichungsprotokoll anbfragen
SELECT
  *
FROM
  xplankonverter.veroeffentlichungsprotokolle v
WHERE
  plan_gml_id = '946a1aac-8f12-11f1-8f3b-0242ac001407'

-- Nachweislücken abfragen
SELECT
  *
FROM
  xplankonverter.veroeffentlichungsnachweis_luecken l JOIN
  xplankonverter.veroeffentlichungsprotokolle v ON l.protokoll_id = v.id
WHERE
  v.plan_gml_id = '946a1aac-8f12-11f1-8f3b-0242ac001407'

-- Veröffentlichungsnachweise abfragen
SELECT
  *
FROM
  xplankonverter.veroeffentlichungsnachweise
WHERE
  plan_gml_id = '946a1aac-8f12-11f1-8f3b-0242ac001407'
  
-- Werte anpassen auf die Zeit nach Ablauf der Auslegung
DELETE FROM xplankonverter.veroeffentlichungsnachweise
WHERE plan_gml_id = '946a1aac-8f12-11f1-8f3b-0242ac001407';

-- Werte anpassen auf die Zeit nach Ablauf der Auslegung im Plan
UPDATE xplan_gml.bp_plan
SET
  auslegungsstartdatum = ARRAY[CURRENT_DATE - INTERVAL '1 month'],
  auslegungsenddatum = ARRAY[CURRENT_DATE - INTERVAL ' 1 day']
WHERE
  gml_id = '946a1aac-8f12-11f1-8f3b-0242ac001407'::uuid;

-- Werte anpassen auf die Zeit nach Ablauf der Auslegung im Veröffentlichungsprotokoll
UPDATE xplankonverter.veroeffentlichungsprotokolle v 
SET
  observationstart = CURRENT_DATE - INTERVAL '1 month',
  observationend = NULL,
  datei = NULL,
  auslegungsstartdatum = CURRENT_DATE - INTERVAL '1 month',
  auslegungsenddatum = CURRENT_DATE - INTERVAL ' 1 day',
  last_pruefung = CURRENT_TIMESTAMP - INTERVAL '1 hour'
WHERE
  plan_gml_id = '946a1aac-8f12-11f1-8f3b-0242ac001407'::uuid;

--
-- Diesen Befehl aufrufen um die E-Mails für das Ende der Auslegung zu erzeugen.
-- cd /var/www/apps/konverter/plugins/xplankonverter/tools; php -f veroeffentlichungsnachweis.php login_name=pkorduan stelle_id=1 plan_gml_id=946a1aac-8f12-11f1-8f3b-0242ac001407
--

--
-- Alles wieder löschen
--
DELETE FROM xplankonverter.veroeffentlichungsprotokolle
WHERE plan_gml_id = '946a1aac-8f12-11f1-8f3b-0242ac001407';

DELETE FROM xplankonverter.konvertierungen
WHERE
  beschreibung = 'Konvertierung zum testen des Veröffentlichungsnachweises 946a1aac-8f12-11f1-8f3b-0242ac001407';

ALTER TABLE xplan_gml.bp_plan DROP CONSTRAINT IF EXISTS fk_konvertierung_id;

SELECT setval(
  'xplankonverter.konvertierungen_id_seq',
  COALESCE((SELECT MAX(id) FROM xplankonverter.konvertierungen), 1),
  true
);
*/
?>