<?php
#############################
# Klasse Auslegung #
#############################
/**
 * - Hat ein veroeffentlichungsprotokoll Veroeffentlichungsprotokoll
 * - Hat ein plan XP_Plan
 */
class Auslegung extends PgObject {
	static $schema = 'xplankonverter';
	static $tableName = 'auslegungen';
	static $write_debug = false;
  public $plan;
  public $veroeffentlichungsprotokoll;

	function __construct($gui, $planart = NULL) {
		parent::__construct($gui, Auslegung::$schema, Auslegung::$tableName);
	}

  /**
   * Finde aktuelle Auslegungen und die dazugehörigen Pläne
   */
  public static	function find_aktuelle($gui, $plan_gml_id = NULL, $pruefzeit) {
		$auslegung_obj = new Auslegung($gui);
    $auslegungen = $auslegung_obj->find_where("
      '" . date('Y-m-d H:i:s', $pruefzeit) . "'::timestamp without time zone >= startdatum AND '" . date('Y-m-d H:i:s', $pruefzeit) . "'::timestamp without time zone < enddatum + 1 AND
      '" . date('Y-m-d H:i:s', $pruefzeit) . "'::timestamp without time zone >= veroeffentlichungsdatum"
      . ($plan_gml_id ? " AND plan_gml_id = '" . $plan_gml_id . "'" : ''),
      "planart",
      "planart, plan_gml_id, lfdnr, startdatum, enddatum"
    );
    foreach ($auslegungen AS $auslegung) {
      $result = $auslegung->find_plan();
      if (!$result['success']) {
        return array(
          'success' => false,
          'msg' => 'Class Auslegung Func find_aktuelle ' . __LINE__ . ': ' . $result['msg']
        );
      }
      $auslegung->plan = $result['plan'];
      $result = $auslegung->find_veroeffentlichungsprotokoll();
      if (!$result['success']) {
        return array(
          'success' => false,
          'msg' => 'Fehler in class Auslegung func find_completed ' . __LINE__ . ': ' . $result['msg']
        );
      }
      $auslegung->veroeffentlichungsprotokoll = $result['protokoll'];
    }
    return array(
      'success' => true,
      'auslegungen' => $auslegungen
    );
  }

  public static function find_completed($gui, $pruefzeit, $plan_gml_id = '') {
		$auslegung_obj = new Auslegung($gui);
    $completed_auslegungen = $auslegung_obj->find_where(
      "
        a.enddatum + INTERVAL '1 day' <= '" . date('Y-m-d H:i:s', $pruefzeit) . "'::timestamp without time zone AND
        v.observationend IS NULL"
        . ($plan_gml_id != '' ? " AND a.plan_gml_id = '" . $plan_gml_id . "'" : '') . "
      ",
      NULL,
      "
        v.id AS veroeff_id,
        a.planart,
        a.plan_gml_id,
        a.lfdnr,
        a.startdatum,
        a.enddatum,
        p.name,
        p.nummer,
        k.stelle_id
      ",
      NULL,
      "
        xplankonverter.veroeffentlichungsprotokolle v JOIN
        xplankonverter.auslegungen a ON
          v.plan_gml_id = a.plan_gml_id AND
          v.lfdnr = a.lfdnr JOIN
        xplan_gml.xp_plan p ON v.plan_gml_id = p.gml_id JOIN
        xplankonverter.konvertierungen k ON p.konvertierung_id = k.id
      "
    );
    $fehler = pg_last_error();
    if ($fehler) {
      return array(
        'success' => false,
        'msg' => 'Fehler in class Auslegung func find_completed ' . __LINE__ . ' => Fehler bei der Abfrage der abgeschlossenen Auslegungen: ' . $fehler
      );
    }
    if (count($completed_auslegungen) == 0) {
      echo_log("Keine kürzlich beendeten Auslegungen gefunden.", 2);
    }
    else {
      echo_log(count($completed_auslegungen) . ' beendete Auslegungen gefunden.', 2);
    }
    foreach ($completed_auslegungen AS $auslegung) {
      echo_log('Class Auslegung Func find_completed ' . __LINE__ . ': Frage Plan zu Auslegung ab', 2);
      $result = $auslegung->find_plan();

      if (!$result['success']) {
        return array(
          'success' => false,
          'msg' => 'Class Auslegung Func find_completed ' . __LINE__ . ': ' . $result['msg']
        );
      }
      $auslegung->plan = $result['plan'];
      echo_log('Class Auslegung Func find_completed ' . __LINE__ . ': Frage Veröffentlichungsprotokoll der Auslegung ab', 2);
      $result = Veroeffentlichungsprotokoll::find_by_auslegung($auslegung);
      if (!$result['success']) {
        return array(
          'success' => false,
          'msg' => 'Fehler in class Auslegung func find_completed ' . __LINE__ . ': ' . $result['msg']
        );
      }
      $auslegung->veroeffentlichungsprotokoll = $result['protokoll'];
    }
    return array(
      'success' => true,
      'msg' => 'Abgeschlossene Auslegungen erfolgreich abgefragt',
      'completed_auslegungen' => $completed_auslegungen
    );
  }

  function find_plan() {
    $result = XP_Plan::find_by_id_with_stelle_id($this->gui, $this->get('plan_gml_id'), $this->get('planart'));
    if (!$result['success']) {
      return array(
        'success' => false,
        'msg' => 'Class Auslegung func find_plan ' . __LINE__ . ': ' . $result['msg']
      );
    }
    $plan = $result['plan'];
    echo_log('Class: Auslegung, Func: find_plan, Zeile: ' . __LINE__ . ', Frage Dokumente der Auslegung ab', 2);
    $result = $plan->find_veroeffentlichungsprotokoll_dokumente($this->get('plan_gml_id'));
    if (!$result['success']) {
      return array(
        'success' => false,
        'msg' => 'Class Auslegung Func find_plan ' . __LINE__ . ': ' . $result['msg']
      );
    }
    return array(
      'success' => true,
      'plan' => $plan
    );
  }

  function find_veroeffentlichungsprotokoll() {
    $result = Veroeffentlichungsprotokoll::find_by_auslegung($this);
    return $result;
  }

  function get_plan_type() {
    switch ($this->get('planart')) {
      case 'BP-Plan' : return 'bplan';
      case 'FP-Plan' : return 'fplan';
      case 'SO-Plan' : return 'soplan';
      default : return $this->get('planart');
    }
  }

  /**
   * Prüft ob im Auslegungsobjekt ein Veröffentlichungsprotokoll existiert.
   */
  function veroeffentlichungsprotokoll_exists() {
    return $this->veroeffentlichungsprotokoll !== null;
  }

}