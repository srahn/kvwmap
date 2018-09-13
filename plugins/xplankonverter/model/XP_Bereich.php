<?php
#############################
# Klasse XP_Bereich #
#############################

class XP_Bereich extends PgObject {

  static $schema = 'xplan_gml';

  function XP_Bereich($gui, $planart) {
		$this->planart = $planart;
		$this->planartAbk = strtolower(substr($planart, 0, 2));
		$this->tableName = $this->planartAbk . '_bereich';
		$this->umlName = strtoupper($this->planartAbk) . '_Bereich';
		$this->PgObject($gui, XP_Bereich::$schema, $this->tableName);
		$this->xp_objekte = array();
		$this->identifier = 'gml_id';
		$this->identifier_type = 'text';
	}

	public static	function find_by_id($gui, $by, $id) {
		$xp_bereich = new XP_Bereich($gui, $this->planart);
		$xp_bereich->find_by($by, $id);
		return $xp_bereich;
	}

  function holeObjekte($konvertierung_id) {
    $this->xp_objekte;
  }

	function get_regeln() {
		$regel = new Regel($this->gui);
		$regeln = $regel->find_where("
			bereich_gml_id = '{$this->get('gml_id')}'
		");
		return $regeln;
	}

	function get_plan() {
		$plan = new XP_Plan($this->gui, $this->planart);
		return $plan->find_by('gml_id', $this->get('gehoertzuplan'));
	}

	/*
	* Löscht alle dem Bereich zugehörigen Objekte
	*/
	function destroy_associated_objekte() {
		$sql = "
			DELETE FROM
				xplan_gml.xp_objekt
			WHERE
				gehoertzubereich = '" . $this->get('gml_id') .
			"';";
			echo $sql;
		pg_query($this->database->dbConn, $sql);
	}

	/*
	* Löscht den Bereich und alles was dazugehört
	* Löscht dazugehörige Regeln
	*/
	function destroy() {
		$regeln = $this->get_regeln();
		foreach($regeln AS $regel) {
			$regel->konvertierung = $regel->get_konvertierung();
			$regel->destroy();
		}
		$this->destroy_associated_objekte();
		$this->delete();
	}

}

?>
