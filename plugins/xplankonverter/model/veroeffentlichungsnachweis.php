<?php
######################################
# Klasse Veroeffentlichungsnachweis #
######################################
class Veroeffentlichungsnachweis extends PgObject {
	static $schema = 'xplankonverter';
	static $tableName = 'veroeffentlichungsnachweise';
	static $write_debug = false;

	function __construct($gui, $planart = NULL) {
		parent::__construct($gui, Veroeffentlichungsnachweis::$schema, Veroeffentlichungsnachweis::$tableName);
	}

  public static function find_by_protokoll_id($gui, $protokoll_id) {
    $pg_obj = new Veroeffentlichungsnachweis($gui);
    $result = $pg_obj->find_by_sql(array(
      'select' => "
        v.planart,
        v.plan_gml_id,
        v.lfdnr,
        v.pruefstunde,
        v.gemeldet_am,
        v.id,
        v.pruefcode,
        c.bezeichnung AS pruefergebnis
      ",
      'from' => "
        xplankonverter.veroeffentlichungsnachweise v JOIN
        xplankonverter.pruefcodes c ON v.pruefcode = c.code
      ",
      'where' => "v.protokoll_id = " . $protokoll_id,
      'order' => "pruefstunde"
    ), null, false);
    if (!$result['success']) {
      return array(
        'success' => false,
        'msg' => 'Fehler bei der Abfrage der Veröffentlichungsnachweise. Fehler in ' . $result['msg']
      );
    }
    return array(
      'success' => true,
      'msg' => 'Nachweise erfolgreich abgefragt.',
      'nachweise' => $result['rows']
    );
  }

  /**
   * Registrierung des Veröffentlichungsnachweises.
   * Function sucht ob der Veröffentlichungsnachweis schon existiert, wenn nicht wird er eingetragen wenn ja wird das Prüfergebnis aktualisiert.
   */
  public static function save_veroeffentlichungsnachweis($auslegung, $pruefstunde, $pruef_result) {
    $pg_obj = new PgObject($auslegung->gui, 'xplankonverter', 'veroeffentlichungsnachweise');
    echo_log('Speicher Veröffentlichungsnachweis für gml_id: ' . $auslegung->get('plan_gml_id') . ', lfdnr: ' . $auslegung->get('lfdnr') . ' und pruefstunde: ' . $pruefstunde . ' pruefcode: ' . $pruef_result['pruefcode'] . ' msg: ' . $pruef_result['msg'], 2);
    $nachweise = $pg_obj->find_where("
      protokoll_id = " . $auslegung->veroeffentlichungsprotokoll->get('id') . " AND
      pruefstunde = '" . date("Y-m-d H:i:s", $pruefstunde) . "'
    ");
    $fehler = pg_last_error();
    if ($fehler) {
      return array(
        'success' => false,
        'msg' => 'Fehler bei der Suche ob es den Veröffentlichungsnachweis schon gibt: ' . $fehler
      );
    }
    if (count($nachweise) == 0) {
      echo_log('Es existiert noch kein Veröffentlichungsnachweis für diese Auslegung und Prüfstunde, lege neuen Nachweis an.', 2);
      $ret = $pg_obj->create(array(
        'protokoll_id' => $auslegung->veroeffentlichungsprotokoll->get('id'),
        'plan_gml_id' => $auslegung->get('plan_gml_id'),
        'planart' => $auslegung->get('planart'),
        'lfdnr' => $auslegung->get('lfdnr'),
        'pruefstunde' => date("Y-m-d H:i:s", $pruefstunde),
        'pruefcode' => $pruef_result['pruefcode']
      ));
      if ($ret['success']) {
        $ret['msg'] = 'Nachweis erfolgreich eingetragen für planart: ' . $auslegung->get('planart') . ' gml_id: ' . $auslegung->get('plan_gml_id') . ' lfdnr: ' . $auslegung->get('lfdnr') . ' pruefcode: ' . $pruef_result['pruefcode'];
        $ret['nachweis'] = $pg_obj;
      }
      else {
        $ret['msg'] .= date("Y-m-d H:i:s") . ' Failed to write Result: ' . $pruef_result['msg'] . ' for planart: '. $auslegung->get('planart') . ' gml_id: ' . $auslegung->get('plan_gml_id') . ' und lfdnr: ' . $auslegung->get('lfdnr');
      }
    }
    else {
      // Nachweis existiert schon, aktualisiere Prüfergebnis
      echo_log('Es existiert bereits ein Veröffentlichungsnachweis für diese Auslegung und Prüfstunde, aktualisiere den Nachweis.', 2);
      $nachweis = $nachweise[0];
      if ($nachweis->get('pruefcode') != $pruef_result['pruefcode']) {
        $ret = $nachweis->update_attr("pruefcode = " . $pruef_result['pruefcode'], true);
        $fehler = pg_last_error();
        if ($fehler) {
          return array(
            'success' => false,
            'msg' => 'Fehler beim Update des pruefcode des Veröffentlichungsnachweises: ' . $fehler
          );
        } 
      }
      else {
        $ret['success'] = true;
        $ret['msg'] = 'Der Veröffentlichungsnachweis hat bereits den Prüfcode ' . $pruef_result['pruefcode'] . ', es ist kein Update notwendig.';
      }
      $ret['nachweis'] = $nachweis;
    }
    return $ret;
  }

  public static function save_veroeffentlichungsnachweis_luecke($auslegung, $pruef_result) {
    $pg_obj = new PgObject($auslegung->gui, 'xplankonverter', 'veroeffentlichungsnachweis_luecken');
    $ret = $pg_obj->create(array(
      'protokoll_id' => $auslegung->veroeffentlichungsprotokoll->get('id'),
      'gap_start' => date('Y-m-d H:i:s', $pruef_result['gap_start']),
      'gap_end' => date('Y-m-d H:i:s', $pruef_result['gap_end'])
    ));
    if (!$ret['success']) {
      $ret['msg'] = 'von: ' . date('d.m.Y H:i:s', $pruef_result['gap_start']) . ' bis: ' . date('d.m.Y H:i:s', $pruef_result['gap_end']) . ' für Planart: ' . $auslegung->get('planart') . ' plan_gml_id: ' . $auslegung->get('plan_gml_id') . ' lfdnr: ' . $auslegung->get('lfdnr') . ' Fehler: ' . $ret['msg'];
    }
    return $ret;
  }
}