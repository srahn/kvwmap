<?php
$pruefdatum = date('Y-m-d H:i:s', time());
echo_log("\n". $pruefdatum);
// Running this script with a cron job like this:
// cd /var/www/apps/kvwmap/plugins/xplankonverter/tools; php -f veroeffentlichungsnachweis.php login_name=pkorduan >> /var/www/logs/cron/veroeffentlichungsnachweis.log 2>&1
error_reporting(E_ALL & ~(E_STRICT|E_NOTICE|E_WARNING));

try {
  include('../../../credentials.php');
  include('../../../config.php');
  include(PLUGINS . 'xplankonverter/config/config.php');
  define('AUSLEGUNG_URL', "https://bplan.geodaten-mv.de/bauportal/Uebersicht/Details");
  include(WWWROOT . APPLVERSION . 'funktionen/allg_funktionen.php');
  include(CLASSPATH . 'kvwmap.php');
  include(CLASSPATH . 'log.php');
  include(CLASSPATH . 'rolle.php');
  include(CLASSPATH . 'stelle.php');
  include(CLASSPATH . 'users.php');
  include(CLASSPATH . 'Layer.php');
  include(CLASSPATH . 'postgresql.php');
  include(PLUGINS . 'xplankonverter/model/XP_Plan.php');

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
  $auslegung_obj = new PgObject($GUI, 'xplankonverter', 'auslegungen');
  $auslegungen = $auslegung_obj->find_where("now()::date BETWEEN startdatum AND enddatum", "planart", "planart, gml_id, lfdnr, startdatum, enddatum");
  $protokoll_obj = new PgObject(
    $GUI,
    'xplankonverter',
    'veroeffentlichungsprotokolle',
    array(
      array(
		  	'column' => 'plan_gml_id',
				'type' => 'uuid'
			),
      array(
				'column' => 'auslegungsstartdatum',
				'type' => 'timestamp without time zone'
			),
      array(
				'column' => 'auslegungsenddatum',
				'type' => 'timestamp without time zone'
			)
    ),
    'array'
  );
  $nachweis_obj = new PgObject(
    $GUI,
    'xplankonverter',
    'veroeffentlichungsnachweise',
    array(
      array(
        'column' => 'planart',
        'type' => 'varchar'
      ),
      array(
        'column' => 'plan_gml_id',
        'type' => 'uuid'
      ),
      array(
        'column' => 'lfdnr',
        'type' => 'integer'
      ),
      array(
        'column' => 'pruefzeit',
        'type' => 'timestamp without time zone'
      )
    ),
    "array"
  );
  $plan_obj = new PgObject($GUI, 'xplan_gml', 'xp_plan');
  $fehler_obj = new PgObject($GUI, 'xplankonverter', 'nachweisfehler');
  foreach ($auslegungen AS $auslegung) {
    if (!pruefprotokoll_exists($protokoll_obj, $auslegung)) {
      open_pruefprotokoll($protokoll_obj, $auslegung, $pruefdatum);
      echo_log('Prüfprotokoll angelegt für Planart: ' . $auslegung->get('planart') . ' gml_id: ' . $auslegung->get('gml_id') . ' lfdnr: ' . $auslegung->get('lfdnr'));
    }

    $pruef_result = pruefe_auslegung(AUSLEGUNG_URL . '?type=' . urlencode($auslegung->get('planart')) . '&id=' . $auslegung->get('gml_id'), $auslegung->get('gml_id'));

    if (!veroeffentlichungsnachweis_exists($nachweis_obj, $auslegung)) {
      $save_result = save_veroeffentlichungsnachweis($nachweis_obj, $auslegung, $pruef_result);
      if (!$save_result['success']) {
        $err_msgs[] = 'Fehler beim Speichern des Veröffentlichungsnachweises: ' . $save_result['err_msg'];
        continue;
      }
      // echo_log('Veröffentlichungsnachweis gespeichert für Planart: ' . $auslegung->get('planart') . ' gml_id: ' . $auslegung->get('gml_id') . ' lfdnr: ' . $auslegung->get('lfdnr') . ' Ergebnis: ' . $pruef_result['msg']);
    }

    $nachweisfehler = suche_nachweisfehler($fehler_obj, $auslegung);
    if (intval($nachweisfehler->get('num_fehler')) < 5) {
      continue;
    }
	  $plan = $plan_obj->find_where(
      "p.gml_id = '" . $auslegung->get('gml_id') . "'",
      NULL,
      "
        p.gml_id,
        p.name,
        p.nummer,
        s.ows_contentemailaddress,
        s.ows_contentperson,
        s.ows_distributionemailaddress,
        s.ows_distributionperson
      ",
      NULL,
      "
        xplan_gml.xp_plan p JOIN
        xplankonverter.konvertierungen k ON p.konvertierung_id = k.id JOIN
        kvwmap.stelle s ON k.stelle_id = s.id
      "
    )[0];
    $alert_result = send_alert('Fehler bei der Auslegung von Plan ' . $auslegung->get('gml_id'), create_alert($plan, $auslegung, $nachweisfehler), $plan->get('ows_contentemailaddress'), $plan->get('ows_contentperson'));

    if (!$alert_result['success']) {
      $err_msgs[] = 'Fehler beim Versenden der Benachrichtigung: ' . $alert_result['msg'];
      continue;
    }

    // Trage für die Nachweise ein, das der Fehler gemeldet wurde
    $nachweis_obj->update_attr(
      array("gemeldet_am = now()"),
      false,
      "
        planart = '" . $auslegung->get('planart') . "' AND
        plan_gml_id = '" . $auslegung->get('gml_id') . "' AND
        lfdnr = " . $auslegung->get('lfdnr') . " AND
        pruefzeit BETWEEN '" . $nachweisfehler->get('startzeit') . "' AND '" . $nachweisfehler->get('endzeit') . "'AND
        gemeldet_am IS NULL
      "
    );
  }

  // Finde beendete Auslegungen. Erzeuge, versende und schließe die Prüfprotokolle.
  foreach(find_completed_auslegungen($auslegung_obj) AS $auslegung) {
	  $plan = $plan_obj->find_where(
      "p.gml_id = '" . $auslegung->get('gml_id') . "'",
      NULL,
      "
        p.gml_id,
        p.name,
        p.nummer,
        s.ows_contentemailaddress,
        s.ows_contentperson,
        s.ows_distributionemailaddress,
        s.ows_distributionperson
      ",
      NULL,
      "
        xplan_gml.xp_plan p JOIN
        xplankonverter.konvertierungen k ON p.konvertierung_id = k.id JOIN
        kvwmap.stelle s ON k.stelle_id = s.id
      "
    )[0];
    $pruefprotokoll_result = send_pruefprotokoll(create_pruefprotokoll($plan, $nachweis_obj, $auslegung), $plan->get('ows_contentemailaddress'), $plan->get('ows_contentperson'));
    $veroeffentlichungsprotokoll = $protokoll_obj->find_by_ids(array(
      'plan_gml_id' => $auslegung->get('gml_id'),
      'auslegungsstartdatum' => $auslegung->get('startdatum'),
      'auslegungsenddatum' => $auslegung->get('enddatum')
    ));
    $veroeffentlichungsprotokoll->update_attr(array("observationend = now()"));
  }

  if (count($err_msgs) > 0) {
    throw new ErrorException(implode("\n", $err_msgs));
  }
}
catch (Exception $e) {
  echo_log('Fehler: ' . $e);
}

function echo_log($msg) {
  echo "\n" . $msg;
}

function include_($filename) {
  include_once $filename;
}

function pruefprotokoll_exists($protokoll_obj, $auslegung) {
  $results = $protokoll_obj->find_where("
    plan_gml_id = '" . $auslegung->get('gml_id') . "' AND
    auslegungsstartdatum = '" . $auslegung->get('startdatum') . "' AND
    auslegungsenddatum = '" . $auslegung->get('enddatum') . "'
  ");
  return count($results) > 0;
}

function open_pruefprotokoll($protokoll_obj, $auslegung, $pruefdatum) {
  $ret = $protokoll_obj->create(array(
    'plan_gml_id' => $auslegung->get('gml_id'),
    'auslegungsstartdatum' => $auslegung->get('startdatum'),
    'auslegungsenddatum' => $auslegung->get('enddatum'),
    'observationstart' => $pruefdatum
  ));
  return $ret;
}

/**
 * Erstellen eines Prüfprotokolls mit den Prüfnachweisen der Auslegung. ToDo: PDF-Dokument erstellen und Pfad zurückgeben
 * @param PgObject $nachweis_obj Objekt für die Nachweise
 * @param PgObject $auslegung Objekt der Auslegung
 * @return string Pfad zum Prüfprotokoll
 */
function create_pruefprotokoll($plan, $nachweis_obj, $auslegung) {
  $subject = 'Prüfprotokoll für die Auslegung des Plan ' . $plan->get('name') . ' Nr ' . $plan->get('nummer') . ' vom ' . $auslegung->get('startdatum') . ' bis ' . $auslegung->get('enddatum');
  $body = 'Mitteilung für ' . $plan->get('ows_distributionperson') . "\n\n" . 'Anbei erhalten Sie das Prüfprotokoll für die Auslegung des Plan ' . $plan->get('name') . ' Nr ' . $plan->get('nummer') . ' gml_id: ' . $auslegung->get('gml_id') . ' vom ' . $auslegung->get('startdatum') . ' bis ' . $auslegung->get('enddatum') . " auf dem Bau- und Planungsportal unter: " . AUSLEGUNG_URL . '?type=' . urlencode($auslegung->get('planart')) . '&id=' . $auslegung->get('gml_id') . "\n\n" . "Folgende Nachweise für die Auslegung des Planes wurden während des Auslegungszeitraums erstellt:\n\n";
  $pruefnachweise = $nachweis_obj->find_where(
    "
      plan_gml_id = '" . $auslegung->get('gml_id') . "' AND
      lfdnr = " . $auslegung->get('lfdnr') . " AND
      pruefzeit::date BETWEEN '" . $auslegung->get('startdatum') . "' AND '" . $auslegung->get('enddatum') . "'
    ",
    "pruefzeit"
  );
  $protokollfile = MAILQUEUEPATH . 'Prüfprotokoll_' . $auslegung->get('gml_id') . '_' . $auslegung->get('lfdnr') . '.log';
  $fp = fopen($protokollfile, 'w');
  // ToDo: PDF-Dokument erzeugen mit der Liste der Prüfnachweise
  foreach($pruefnachweise AS $pruefnachweis) {
    $msg = 'Prüfzeit: ' . $pruefnachweis->get('pruefzeit') . ' Ergebnis: ' . $pruefnachweis->get('pruefergebnis') . ' gemeldet: ' . $pruefnachweis->get('gemeldet_am');
    $body .= $msg . "\n";
    fwrite($fp, $msg);
  }
  fclose($fp);
  $body .= "\n\n" . "Dies ist eine automatisch erstellte Nachricht vom Bauleitplanserver.\nSie können auf diese E-Mail nicht antworten. Wenden Sie sich bei Bedarf an GDI-Servive robert.kraetschmer@gdi-service.de oder die Koordinierungsstelle im Landkreis LUP jens.wildner@kreis-lup.de.";
  return array(
    'subject' => $subject,
    'body' => $body,
    'anhang' => $protokollfile
  );
}

function send_pruefprotokoll($pruefprotokoll, $to_email, $to_name) {
  $mail = mail_att("Bauleitplanserver", MAILREPLYADDRESS, $to_email, NULL, 'peter.korduan@gdi-service.de', $pruefprotokoll['subject'], $pruefprotokoll['body'], $pruefprotokoll['anhang'], MAILMETHOD, MAILSMTPSERVER, MAILSMTPPORT, $to_name);
  echo_log('E-Mail an ' . $to_email . " erstellt: \n" . $pruefprotokoll['body']);

  return array(
    'success' => $mail === 1,
    'msg' => 'E-Mail erfolgreich versendet.'
  );
}

function pruefe_auslegung($url, $gml_id) {
  $ch = curl_init($url);
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_FOLLOWLOCATION => true,
  ]);

  $body = curl_exec($ch);
  if ($body === false) {
    $httpCode = 0;
    $error = curl_error($ch);
  } else {
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  }

  curl_close($ch);

  $status = 1;
  $msg = '';

  // a) Erreichbarkeit prüfen
  if ($httpCode !== 200) {
    $msg = "Webseite nicht erreichbar (HTTP $httpCode)";
    $status = 3;
  }
  else {
    // b) Inhalt prüfen
    if (strpos($body, $gml_id) !== false) {
      $msg = "Plan veröffentlicht";
      $status = 0;
    } elseif (strpos($body, "Keinen Plan gefunden!") !== false) {
      $msg = "Keinen Plan gefunden";
      $status = 1;
    } else {
      $msg = "Unerwartete Antwort";
      $status = 2;
    }
  }
  // echo "Result: $msg\n";
  // echo "Status: $status\n";
  return array(
    'msg' => $msg,
    'status' => $status
  );
}

function veroeffentlichungsnachweis_exists($nachweis_obj, $auslegung) {
  $nachweis = $nachweis_obj->find_by_ids(array(
    'plan_gml_id' => $auslegung->get('gml_id'),
    'planart' => $auslegung->get('planart'),
    'lfdnr' => $auslegung->get('lfdnr'),
    'pruefzeit' => date("Y-m-d H:00:00", time())
  ));
  if ($nachweis->data !== false) {
    // echo_log('Veröffentlichungsnachweis bereits vorhanden für planart: ' . $auslegung->get('planart') . ' gml_id: ' . $auslegung->get('gml_id') . ' lfdnr: ' . $auslegung->get('lfdnr'));
    return true;
  }
  return false;
}

/**
 * Eintragen des Ergebnisses in die Nachweistabelle
 */
function save_veroeffentlichungsnachweis($nachweis_obj, $auslegung, $pruef_result) {
  $ret = $nachweis_obj->create(array(
    'plan_gml_id' => $auslegung->get('gml_id'),
    'planart' => $auslegung->get('planart'),
    'lfdnr' => $auslegung->get('lfdnr'),
    'pruefzeit' => date("Y-m-d H:00:00", time()),
    'pruefergebnis' => $pruef_result['msg']
  ));
  if ($ret['success']) {
    $ret['msg'] = 'Nachweis erfolgreich eingetragen für planart: ' . $auslegung->get('planart') . ' gml_id: ' . $auslegung->get('gml_id') . ' lfdnr: ' . $auslegung->get('lfdnr') . ' result: ' . $pruef_result['msg'];
    $ret['nachweis'] = $nachweis_obj;
  }
  else {
    $ret['err_msg'] .= date("Y:m:d H:i:s") . ' Failed to write Result: ' . $pruef_result['msg'] . ' for planart: '. $auslegung->get('planart') . ' gml_id: ' . $auslegung->get('gml_id') . ' und lfdnr: ' . $auslegung->get('lfdnr');
  }
  return $ret;
}

/**
 * Abfrage der Anzahl der Fehler in den letzten 5 Nachweisen
 */
function suche_nachweisfehler($fehler_obj, $auslegung) {
  // echo_log('Suche Fehler in den letzten 5 Nachweisen für planart: ' . $auslegung->get('planart') . ' gml_id: ' . $auslegung->get('gml_id') . ' lfdnr: ' . $auslegung->get('lfdnr'));
  $nachweisfehler = $fehler_obj->find_by_sql(array(
    'select' => "
      min(pruefzeit) AS startzeit,
      max(pruefzeit) AS endzeit,
      sum(fehler) AS num_fehler
    ",
    'from' => "(
      SELECT
        pruefzeit,
        CASE WHEN pruefergebnis = 'Plan veröffentlicht' THEN 0 ELSE 1 END AS fehler
      FROM xplankonverter.veroeffentlichungsnachweise
      WHERE
        plan_gml_id = '" . $auslegung->get('gml_id') . "' AND
        lfdnr = " . $auslegung->get('lfdnr') . " AND
        gemeldet_am IS NULL
      ORDER BY pruefzeit DESC
      LIMIT 5
    ) AS nachweisfehler"
  ));
  return $nachweisfehler[0];
}

function create_alert($plan, $auslegung, $nachweisfehler) {
  $url = AUSLEGUNG_URL . '?type=' . urlencode($auslegung->get('planart')) . '&id=' . $auslegung->get('gml_id');
  $msg = "Mitteilung für: " . $plan->get('ows_distributionperson') . "\n\nDer Plan " . $plan->get('name') . " Nr " . $plan->get('nummer') . " gml_id: " . $auslegung->get('gml_id') . " mit Auslegungszeitraum von " . $auslegung->get('startdatum') . " bis " . $auslegung->get('enddatum') . " war am " . $nachweisfehler->get('endzeit') . " auf dem Bau- und Planungsportal für 5 Stunden nicht verfügbar!\n\nBitte prüfen Sie die Auslegung im Bau- und Planungsportal unter: " . $url . " und die Angaben zur Veröffentlichung des Plans auf " . URL . " und stellen Sie sicher, dass der Plan veröffentlicht ist.\n\nSind Ihre Angaben korrekt und sollte das Problem, dass der Plan nicht veröffentlicht wird, weiterhin bestehen, wenden Sie sich an GDI-Service unter der Adresse robert.kraetschmer@gdi-service.de oder per Telefon unter 0381 40344446 oder an die Koordinierungsstelle beim Landkreis LUP unter der Adresse jens.wildner@kreis-lup.de.\n\n\nDies ist eine automatisch erstellte Nachricht vom Bauleitplanserver.\nSie können auf diese E-Mail nicht antworten. Wenden Sie sich bei Bedarf an die oben angegebenen Kontakte.";
  return $msg;
}

function send_alert($subject, $alert, $to_email, $to_name) {
  //      mail_att($from_name,          $from_email,      $to_email, $cc_email, $reply_email,              $subject, $message, $attachement, $mode, $smtp_server, $smtp_port, $to_name)
  $mail = mail_att("Bauleitplanserver", MAILREPLYADDRESS, $to_email, NULL, 'peter.korduan@gdi-service.de', $subject, $alert, null, MAILMETHOD, MAILSMTPSERVER, MAILSMTPPORT, $to_name);
  echo_log('E-Mail an ' . $to_email . " erstellt: \n" . $alert);

  return array(
    'success' => $mail === 1,
    'msg' => 'E-Mail erfolgreich versendet.'
  );
}

function find_completed_auslegungen($auslegung_obj) {
  $completed_auslegungen = $auslegung_obj->find_where(
    "
      a.enddatum < now() AND
      p.observationend IS NULL
    ",
    NULL,
    "a.*",
    NULL,
    "
      xplankonverter.veroeffentlichungsprotokolle p JOIN
      xplankonverter.auslegungen a ON
        p.plan_gml_id = a.gml_id AND
        p.auslegungsstartdatum = a.startdatum AND
        p.auslegungsenddatum = a.enddatum
    "
  );
  return $completed_auslegungen;
}
?>