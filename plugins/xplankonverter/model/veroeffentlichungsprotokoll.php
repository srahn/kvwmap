<?php
######################################
# Klasse Veroeffentlichungsprotokoll #
######################################
/**
 * hat veroeffentlichungsprotokoll Veroeffentlichungsprotokoll
 *   hat nachweise Veroeffentlichungsnachweise
 *   hat nachweis_luecken PgObject
 *   hat zuständige_user User
 */
class Veroeffentlichungsprotokoll extends PgObject {
	static $schema = 'xplankonverter';
	static $tableName = 'veroeffentlichungsprotokolle';
	static $write_debug = false;
  public $nachweise;
  public $nachweis_luecken;
  public $zustaendige_user;

	function __construct($gui, $planart = NULL) {
		parent::__construct($gui, Veroeffentlichungsprotokoll::$schema, Veroeffentlichungsprotokoll::$tableName);
	}

  public static function find_by_auslegung($auslegung) {
    $pg_obj = new Veroeffentlichungsprotokoll($auslegung->gui);
    $results = $pg_obj->find_where("
      plan_gml_id = '" . $auslegung->get('plan_gml_id') . "' AND
      lfdnr = " . $auslegung->get('lfdnr')
    );
    $fehler = pg_last_error();
    if ($fehler) {
      return array(
        'success' => false,
        'Fehler bei der Abfrage des Veröffentlichungsprotokolls.'
      );
    }
    if (count($results) == 0) {
      $protokoll = null;
      $msg = 'Kein Veröffentlichungsprotokoll zur Auslegung gefunden.';
    }
    else {
      $protokoll = $results[0];
      $msg = 'Veröffentlichungsprotokoll zur Auslegung gefunden.';
      echo_log('class Veroeffentlichungsprotokoll func find_by_auslegung ' . __LINE__ . ': Frage Nachweise der Auslegung ab', 2);
      $result = $protokoll->find_nachweise($protokoll->get('id'));
      if (!$result['success']) {
        return $result;
      }

      $result = $protokoll->find_nachweis_luecken($protokoll->get('id'));
      if (!$result['success']) {
        return $result;
      }

      echo_log('class Veroeffentlichungsprotokoll func find_by_auslegung ' . __LINE__ . ': Frage zuständige Nutzer der Auslegung ab', 2);
      $result = $protokoll->find_zustaendige_user($auslegung->plan->get('stelle_id'));
      if (!$result['success']) {
        return $result;
      }
    }
    return array(
      'success' => true,
      'msg' => $msg,
      'protokoll' => $protokoll
    );    
  }

  function find_zustaendige_user($stelle_id) {
    $this->zustaendige_user = user::find(
      $this->gui,
      (AUSLEGUNG_MODE == 'dev' ? "u.login_name = 'pkorduan'" : "
        u.email IS NOT NULL AND
        u.email != '' AND
        u.funktion != 'admin' AND
        u.organisation NOT LIKE '%GDI%Service%' AND
        u.login_name NOT IN ('btfietz') AND
        r.stelle_id = " . $stelle_id . "
      "),
      NULL,
      "
        u.id AS user_id,
        concat_ws(' ', u.vorname, u.name) AS contact_name, 
        u.email AS contact_email
      ",
      NULL,
      (AUSLEGUNG_MODE == 'dev' ? "kvwmap.user AS u" :"
        kvwmap.rolle r JOIN
        kvwmap.user u ON r.user_id = u.id
      ")
    );
    $fehler = pg_last_error();
    if ($fehler) {
      return array(
        'success' => false,
        'msg' => 'Fehler bei der Abfrage der zuständigen Nutzer der Auslegung.' . $fehler
      );
    }
    return array(
      'success' => true,
      'msg' => 'Zuständige Nutzer erfolgreich abgefragt.'
    );
  }

  function find_nachweise($protokoll_id) {
    $result = Veroeffentlichungsnachweis::find_by_protokoll_id($this->gui, $protokoll_id);
    if (!$result['success']) {
      return array(
        'success' => false,
        'msg' => 'Fehler bei der Abfrage der Veröffentlichungsnachweise der Auslegung. ' . $result['msg']
      );
    }
    $this->nachweise = $result['nachweise'];
    return array(
      'success' => true,
      'msg' => 'Veröffentlichungsnachweise erfolgreich abgefragt.'
    );
  }

  function find_nachweis_luecken($protokoll_id) {
    $pg_obj = new PgObject($this->gui, 'xplankonverter', 'veroeffentlichungsnachweis_luecken');
    $result = $pg_obj->find_by_sql(array(
      'select' => "
        l.id,
        l.protokoll_id,
        l.gap_start,
        l.gap_end,
        v.plan_gml_id,
        v.lfdnr
      ",
      'from' => "
        xplankonverter.veroeffentlichungsnachweis_luecken l JOIN
        xplankonverter.veroeffentlichungsprotokolle v ON l.protokoll_id = v.id
      ",
      'where' => "l.protokoll_id = " . $protokoll_id,
      'order' => "gap_start"
    ), null, false);
    if (!$result['success']) {
      return array(
        'success' => false,
        'msg' => 'Fehler bei der Abfrage der Veröffentlichungsnachweislücken. Fehler in ' . $result['msg']
      );
    }
    $this->nachweis_luecken = $result['rows'];
    return array(
      'success' => true,
      'msg' => 'Veröffentlichungsnachweislücken erfolgreich abgefragt.',
      'nachweis_luecken' => $result['rows']
    );
  }

  /**
   * Eröffnet ein Protokoll in der Datenbank und
   * registriert die ausgelegten Dokumente
   */
  public static function open($auslegung, $pruefstunde) {
    $veroeffentlichungsprotokoll = new Veroeffentlichungsprotokoll($auslegung->gui);
    $result = $veroeffentlichungsprotokoll->create(array(
      'plan_gml_id' => $auslegung->get('plan_gml_id'),
      'lfdnr' => $auslegung->get('lfdnr'),
      'auslegungsstartdatum' => $auslegung->get('startdatum'),
      'auslegungsenddatum' => $auslegung->get('enddatum'),
      'observationstart' => date('Y-m-d H:i:s', $pruefstunde),
      'pruefungen_seit_observationstart' => 0
    ));
    if (!$result['success']) {
      $result['msg'] = 'Fehler bei der Erzeugung des Veröffentlichungsprotokolls. ' . $result['msg'];
      return $result;
    }

    $result = $veroeffentlichungsprotokoll->find_zustaendige_user($auslegung->plan->get('stelle_id'));
    if (!$result['success']) {
      return $ret;
    }

    $pg_obj = new PgObject($auslegung->gui, 'xplankonverter', 'veroeffentlichungsprotokoll_dokumente');
    foreach ($auslegung->plan->veroeffentlichungsprotokoll_dokumente AS $dokument) {
      echo_log('Class: Veroeffentlichungsprotokoll, Func: open, Zeile: ' . __LINE__ . ' Erzeuge Dokument Hash mit layer_id: ' . $auslegung->plan->get_plan_layer_id() . ' und dokument: ' . $dokument->get('referenzurl'), 2);
      $doc_hash = create_document_hash($auslegung->plan->get_plan_layer_id(), $dokument->get('referenzurl'));
      if ($doc_hash === false) {
        return array(
          'success' => false,
          'msg' => 'Fehler beim Erzeugen des Dokument-Hash für Datei: ' . $dokument->get('referenzurl') . ' im Layer id: ' . $auslegung->plan->get_plan_layer_id()
        );
      }
      $result = $pg_obj->create(array(
        'protokoll_id' => $veroeffentlichungsprotokoll->get('id'),
        'doc_art' => $dokument->get('art'),
        'doc_url' => $dokument->get('referenzurl'),
        'doc_beschreibung' => $dokument->get('beschreibung'),
        'doc_datum' => $dokument->get('datum'),
        'typ_beschreibung' => $dokument->get('typ_beschreibung'),
        'doc_hash' => $doc_hash
      ));
      if (!$result['success']) {
        $result['msg'] = 'Fehler bei der Erzeugung des Dokumentes des Veröffentlichungsprotokolls. ' . $result['msg'];
        return $result;
      }
    }
    // $veroeffentlichungsprotokoll->set('observationstart', date('Y-m-d H:i:s', $pruefstunde));
    return array(
      'success' => true,
      'msg' => 'Veröffentlichungsprotokoll und Dokumente erfolgreich angelegt.',
      'protokoll' => $veroeffentlichungsprotokoll
    );
  }

  function create_ueberwachungsbeginn_alert_mail($auslegung, $contact_name, $pruefzeit) {
    $url = AUSLEGUNG_URL . '?type=' . urlencode($auslegung->get('planart')) . '&id=' . $auslegung->plan->get('gml_id');
    $subject = 'Beginn der Überwachung der Auslegung ' . $auslegung->plan->get('anzeigename') . ' ' . $auslegung->get('startdatum') . ' bis ' . $auslegung->get('enddatum');
    $body = "Mitteilung für: " . $contact_name . "\n\nDie Überwachung der Auslegung des Plans " . $auslegung->plan->get('anzeigename') . " (gml_id: " . $auslegung->plan->get('gml_id') . ") mit Auslegungszeitraum von " . $auslegung->get('startdatum') . " bis " . $auslegung->get('enddatum') . " hat " . date('d.m.Y H:i', $pruefzeit) . " Uhr begonnen.\n\nSie können die Auslegung im Bau- und Planungsportal unter: " . $url . " prüfen und ggf. Angaben zur Veröffentlichung des Plans auf " . URL . " prüfen und ändern.";
    return array(
      'subject' => $subject,
      'body' => $body,
      'anhang' => ''
    );
  }

  function create_nachweis_luecke_alert_mail($auslegung, $contact_name, $pruef_result) {
    echo "\n__Prüf_result: " . print_r($pruef_result, true) . "\n";
    $url = AUSLEGUNG_URL . '?type=' . urlencode($auslegung->get('planart')) . '&id=' . $auslegung->plan->get('gml_id');
    $subject = 'Warung zur Auslegung ' . $auslegung->plan->get('anzeigename') . ' ' . $auslegung->get('startdatum') . ' bis ' . $auslegung->get('enddatum');
    $body = "Mitteilung für: " . $contact_name . "\n\nFür den " . $auslegung->plan->get('anzeigename') . " (gml_id: " . $auslegung->plan->get('gml_id') . ") mit Auslegungszeitraum von " . $auslegung->get('startdatum') . " bis " . $auslegung->get('enddatum') . " gibt es vom " . date('d.m.Y H:i:s', $pruef_result['gap_start']) . " bis " . date('d.m.Y H:i:s', $pruef_result['gap_end']) . " eine Lücke in der Überwachung der Auslegung!\n\nIm genannten Zeitraum konnte nicht automatisch geprüft werden ob der Plan im Bau- und Planungsportal unter: " . $url . " verfügbar war.\n\nDas Problem ist behoben und die Überwachung der Auslegung läuft wieder regulär.\n\nSollte das Problem wiederholt auftreten, wenden Sie sich an die GDI-Service GmbH unter der Adresse robert.kraetschmer@gdi-service.de oder per Telefon unter 0381 40344446 oder an die Koordinierungsstelle beim Landkreis LUP unter der Adresse " . XPLANKONVERTER_COORDINATOR_EMAIL . ".\n\n\nDies ist eine automatisch erstellte Nachricht vom Bauleitplanserver.\nSie können auf diese E-Mail nicht antworten. Wenden Sie sich bei Bedarf an die oben angegebenen Kontakte.";
    return array(
      'subject' => $subject,
      'body' => $body,
      'anhang' => ''
    );
  }

  function create_auslegung_alert_mail($auslegung, $contact_name, $pruefzeit) {
    $url = AUSLEGUNG_URL . '?type=' . urlencode($auslegung->get('planart')) . '&id=' . $auslegung->plan->get('gml_id');
    $subject = 'Fehler bei Auslegung von ' . $auslegung->plan->get('anzeigename') . ' ' . $auslegung->get('startdatum') . ' bis ' . $auslegung->get('enddatum');
    $body = "Mitteilung für: " . $contact_name . "\n\n" . $auslegung->plan->get('anzeigename') . " (gml_id: " . $auslegung->plan->get('gml_id') . ") mit Auslegungszeitraum von " . $auslegung->get('startdatum') . " bis " . $auslegung->get('enddatum') . " war am " . preg_replace('/:\d+(?:\.\d+)?$/', '', date('d.m.Y H:i:s', $pruefzeit)) . " auf dem Bau- und Planungsportal seit 5 Stunden nicht verfügbar!\n\nBitte prüfen Sie die Auslegung im Bau- und Planungsportal unter: " . $url . " und die Angaben zur Veröffentlichung des Plans auf " . URL . " und stellen Sie sicher, dass der Plan veröffentlicht ist.\n\nSind Ihre Angaben korrekt und sollte das Problem, dass der Plan nicht veröffentlicht wird, weiterhin bestehen, wenden Sie sich an GDI-Service unter der Adresse robert.kraetschmer@gdi-service.de oder per Telefon unter 0381 40344446 oder an die Koordinierungsstelle des Bauleitplanservers unter der Adresse " . XPLANKONVERTER_COORDINATOR_EMAIL . ".\n\n\nDies ist eine automatisch erstellte Nachricht vom Bauleitplanserver.\nSie können auf diese E-Mail nicht antworten. Wenden Sie sich bei Bedarf an die oben angegebenen Kontakte.";
    return array(
      'subject' => $subject,
      'body' => $body,
      'anhang' => ''
    );
  }

    /**
   * Erzeugt die PDF-Datei für das Veröffentlichungsprotokoll
   * @return string $datei PDF-Datei mit dem Veröffentlichungsprotokoll
   */
  function create_pdf_datei($auslegung) {
    if (XPLANKONVERTER_VEROEFF_NACHWEIS_LAYER_ID == '') {
      return array(
        'success' => false,
        'msg' => 'Die Konstante XPLANKONVERTER_VEROEFF_NACHWEIS_LAYER_ID ist nicht belegt.'
      );
    }
    if (XPLANKONVERTER_NACHWEIS_DDL == '') {
      return array(
        'success' => false,
        'msg' => 'Die Konstante XPLANKONVERTER_NACHWEIS_DDL ist nicht belegt.'
      );
    }
    $this->gui->formvars['chosen_layer_id'] = XPLANKONVERTER_VEROEFF_NACHWEIS_LAYER_ID;
    $this->gui->formvars['aktivesLayout'] = XPLANKONVERTER_VEROEFF_NACHWEIS_DDL;
    $this->gui->formvars['checkbox_names_' . XPLANKONVERTER_VEROEFF_NACHWEIS_LAYER_ID] = 'check;vp;veroeffentlichungsprotokolle;' . $auslegung->get('veroeff_id') . ';' . XPLANKONVERTER_VEROEFF_NACHWEIS_LAYER_ID;
    $this->gui->formvars['check;vp;veroeffentlichungsprotokolle;' . $auslegung->get('veroeff_id') . ';' . XPLANKONVERTER_VEROEFF_NACHWEIS_LAYER_ID] = 'on';
    $result = $this->gui->generischer_sachdaten_druck_createPDF();
    $path = XPLANKONVERTER_FILE_PATH . 'veroeffentlichungsprotokolle/';
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }
    $datei = $path . 'veroeff_nachweis_' . $auslegung->get('plan_gml_id') . '_' . str_replace('.', '', $auslegung->get('startdatum')) . '-' . str_replace('.', '', $auslegung->get('enddatum')) . '.pdf';
    if (!rename($result['pdf_file'], $datei)) {
      return array(
        'success' => false,
        'msg' => 'Fehler: Datei ' . $result['pdf_file'] . ' konnte nicht nach ' . $datei . ' verschoben werden.'
      );
    }
    $this->set('datei', $datei);
    $this->update_attr(array("datei = '" . $datei . "'"));
    unlink(str_replace('.pdf', '_thumb.jpg', $datei));
    return array(
      'success' => true,
      'msg' => 'PDF-Datei ' . $datei. ' erfolgreich angelegt'
    );
  }

  /**
   * Erstellen eines Veröffentlichungsprotokolls mit den Prüfnachweisen der Auslegung. ToDo: PDF-Dokument erstellen und Pfad zurückgeben
   * @param PgObject $nachweis_obj Objekt für die Nachweise
   * @param PgObject $auslegung Objekt der Auslegung
   * @return string Pfad zum Veröffentlichungsprotokoll
   */
  function create_veroeffentlichungsprotokoll_mail($auslegung, $contact_name) {
    $subject = 'Protokoll für Auslegung ' . $auslegung->plan->get('anzeigename'). ' ' . $auslegung->get('startdatum') . ' bis ' . $auslegung->get('enddatum');
    $body = 'Mitteilung für ' . $contact_name . "\n\n" . "Anbei erhalten Sie das Veröffentlichungsprotokoll für die Auslegung des Plans: '" . $auslegung->get('name') . "' Nr: " . $auslegung->get('nummer') . ' gml_id: ' . $auslegung->get('plan_gml_id') . ' im Zeitraum vom: ' . $auslegung->get('startdatum') . ' bis: ' . $auslegung->get('enddatum') . " auf dem Bau- und Planungsportal unter: " . AUSLEGUNG_URL . '?type=' . urlencode($auslegung->get('planart')) . '&id=' . $auslegung->plan->get('gml_id') . "\n";
    $auslegung_dauer = volle_stunden($auslegung->get('startdatum'), $auslegung->get('enddatum')) + 24;
    // Aktuell keine Ausgabe der Fehlermeldungen in der E-Mail.
    // if (count($this->nachweise) > 0) {
    //   $body .= "\nIm Auslegungszeitraum wurden folgende Fehlermeldungen erstellt:\n";
    //   foreach($this->nachweise AS $nachweis) {
    //     $body .= "Prüfzeit: " . preg_replace('/:\d+(?:\.\d+)?$/', '', $nachweis->get('pruefstunde')) . ' Ergebnis: ' . $nachweis->get('pruefergebnis') . ' gemeldet: ' . (preg_replace('/:\d+(?:\.\d+)?$/', '', $nachweis->get('gemeldet_am')) ?: '') . "\n";
    //   }
    // }
    $fehler_dauer = count($this->nachweise);
    $verfuegbarkeit_p = round(100 - ($fehler_dauer / $auslegung_dauer * 100), 2);
    $body .= "\nInsgesamt gab es " . count($this->nachweise) . " Fehlermeldungen im Auslegungszeitraum. Damit war der Plan in " . $verfuegbarkeit_p . "% der Zeit verfügbar.\n";

    $ausfall_dauer = 0;
    // Aktuell keine Ausgabe der Lücken in der E-Mail.
    if (count($this->nachweis_luecken) > 0) {
      // $body .= "\nIn folgenden Zeiträumen konnte die Auslegung nicht nachgewiesen bzw. überwacht werden:\n";
      foreach ($this->nachweis_luecken AS $nachweis_luecke) {
        $ausfall_dauer += (strtotime($nachweis_luecke->get('gap_end')) - strtotime($nachweis_luecke->get('gap_start')));
        // $body .= "von: " . preg_replace('/:\d+(?:\.\d+)?$/', '', $nachweis_luecke->get('gap_start')) . ' bis: ' . preg_replace('/:\d+(?:\.\d+)?$/', '', $nachweis_luecke->get('gap_end')) . "\n";
      }
    }
    $ausfall_dauer = $ausfall_dauer / 3600;
    $ueberwachbarkeit_p = round(100 - ($ausfall_dauer / $auslegung_dauer * 100), 2);
    $body .= "\nInsgesamt gab es einen Zeitraum von " . $ausfall_dauer . ' Stunden in denen die Auslegung nicht überwacht werden konnte. Das entspricht einer Überwachungszeit von ' . $ueberwachbarkeit_p . "% im gesamten Auslegungszeitraum.\n";

    $body .= "\n\n" . "Dies ist eine automatisch erstellte Nachricht vom Bauleitplanserver.\nSie können auf diese E-Mail nicht antworten. Wenden Sie sich bei Bedarf an GDI-Servive robert.kraetschmer@gdi-service.de oder die Koordinierungsstelle im Landkreis LUP geodatenmanagement@kreis-lup.de oder Ihren jeweilgen Ansprechpartner im zuständigen Landkreis.";
    echo_log('class Veroeffentlichungsprotokoll func create_veroeffentlichungsprotokoll_mail ' . __LINE__ . ': Erstelle Mail mit Betreff: ' . $subject . ' und Body: ' . $body, 3);
    return array(
      'subject' => $subject,
      'body' => $body,
      'anhang' => $auslegung->veroeffentlichungsprotokoll->get('datei')
    );
  }

  function create_and_send_ueberwachungsbeginn_alert($auslegung, $pruefzeit) {
    echo_log('Anzahl zu benachrichtigende Nutzer: ' . count($this->zustaendige_user), 2);
    foreach ($this->zustaendige_user AS $user) {
      $this->send_email(
        $this->create_ueberwachungsbeginn_alert_mail($auslegung, $user->get('contact_name'), $pruefzeit),
        $user->get('contact_email'),
        $user->get('contact_name')
      );
    }
  }

  function create_and_send_nachweis_luecke_alert($auslegung, $pruef_result) {
    echo_log('Anzahl zu benachrichtigende Nutzer: ' . count($this->zustaendige_user), 2);
    foreach ($this->zustaendige_user AS $user) {
      $this->send_email(
        $this->create_nachweis_luecke_alert_mail($auslegung, $user->get('contact_name'), $pruef_result),
        $user->get('contact_email'),
        $user->get('contact_name')
      );
    }
  }

  function create_and_send_auslegung_alert($auslegung, $pruefzeit) {
    echo_log('Anzahl zu benachrichtigende Nutzer: ' . count($this->zustaendige_user), 2);
    foreach ($this->zustaendige_user AS $user) {
      $this->send_email(
        $this->create_auslegung_alert_mail($auslegung, $user->get('contact_name'), $pruefzeit),
        $user->get('contact_email'),
        $user->get('contact_name')
      );
    }
  }

  function create_and_send_protokoll($auslegung) {
    $this->create_pdf_datei($auslegung);
    foreach ($this->zustaendige_user AS $user) {
      $this->send_email(
        $this->create_veroeffentlichungsprotokoll_mail($auslegung, $user->get('contact_name')),
        $user->get('contact_email'),
        $user->get('contact_name')
      );
    }
  }

  function send_email($email, $to_email, $to_name) {
    echo_log('Schreibe Mail in die mail_queue.', 3);
    $mail = mail_att(
      "Bauleitplanserver", // from_name
      MAILREPLYADDRESS, // from_email
      $to_email,
      NULL, // cc_email
      'peter.korduan@gdi-service.de', // reply_email
      $email['subject'],
      $email['body'], // message
      $email['anhang'], // attachement
      MAILMETHOD, // mode
      MAILSMTPSERVER,
      MAILSMTPPORT,
      $to_name
    );
    echo_log('E-Mail an ' . $to_email . " erstellt: \n" . $email['body'], 3);

    return array(
      'success' => $mail === 1,
      'msg' => 'E-Mail erfolgreich versendet.'
    );
  }

}