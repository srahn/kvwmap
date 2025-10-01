<?php
#############################
# Klasse XP_Bereich #
#############################

class XP_Bereich extends PgObject {

	static $schema = 'xplan_gml';

	function __construct($gui, $planart) {
		$this->planart = $planart;
		$this->planartAbk = strtolower(substr($planart, 0, 2));
		$this->tableName = $this->planartAbk . '_bereich';
		$this->umlName = strtoupper($this->planartAbk) . '_Bereich';
		parent::__construct($gui, XP_Bereich::$schema, $this->tableName);
		$this->xp_objekte = array();
		$this->identifier = 'gml_id';
		$this->identifier_type = 'text';
	}

	public static	function find_by_id($gui, $by, $id, $planart) {
		$xp_bereich = new XP_Bereich($gui, $planart);
		$xp_bereich->find_by($by, $id);
		return $xp_bereich;
	}

	function holeObjekte($konvertierung_id) {
		$this->xp_objekte;
	}

	function get_regeln() {
		$regeln = array();
		$regel = new Regel($this->gui);
		$regeln = $regel->find_where("
			bereich_gml_id::text = '{$this->get('gml_id')}'::text
		");
		return $regeln;
	}

	function get_plan() {
		$plan = new XP_Plan($this->gui, $this->planart);
		return $plan->find_by('gml_id', $this->get('gehoertzuplan'));
	}

	/**
	 * Löscht alle dem Bereich zugehörigen Objekte
	 */
	function destroy_associated_objekte() {
		$sql = "
			DELETE FROM
				xplan_gml.xp_objekt
			WHERE
				gehoertzubereich::text = '" . $this->get('gml_id') . "'::text
		";
		#echo 'SQL zum Löschen der Objekte die zum Bereich ' . $this->get($this->identifier) . ' gehöhren: ' . $sql;
		$result = $this->database->execSQL($sql, 0, 3);
	}
	
		/**
	 * Löscht alle dem Bereich zugehörigen Präsentationsobjekte
	 */
	function destroy_associated_praesentationsobjekte() {
		# also deletes by konvertierung_id as gehoertzubereich does not necessarily have to be set
		$sql = "
			DELETE FROM
				xplan_gml.xp_abstraktespraesentationsobjekt
			WHERE
				gehoertzubereich::text = '" . $this->get('gml_id') . "'::text
			OR
				konvertierung_id = " . $this->get('konvertierung_id') . "
		";
		#echo 'SQL zum Löschen der Objekte die zum Bereich ' . $this->get($this->identifier) . ' gehöhren: ' . $sql;
		$result = $this->database->execSQL($sql, 0, 3);
	}

	/**
	 * Löscht die Zuordnungen von Objekten des Bereiches zu Textabschnitten
	 */
	function destroy_objekt_zu_textabschnitte() {
		$sql = "
			DELETE FROM
				xplan_gml." . $this->planartAbk . "_objekt_zu_" . $this->planartAbk . "_textabschnitt ta
			USING
				xplan_gml." . $this->planartAbk . "_objekt o
			WHERE
				ta." . $this->planartAbk . "_objekt_gml_id::text = o.gml_id::text
				AND o.gehoertzubereich::text = '" . $this->get($this->identifier) . "'::text
		";
		#echo '<br>SQL zum Löschen der Zuordnungen der Objekte des Bereiches ' . $this->get($this->identifier) . ' zu den Textabschnitten:' . $sql;
		$result = $this->database->execSQL($sql, 0, 3);
	}

	/**
	 * Löscht den Bereich und alles was dazugehört
	 * Löscht dazugehörige Regeln
	 */
	function destroy() {
		$regeln = array();
		$regeln = $this->get_regeln();
		foreach ($regeln AS $regel) {
			# Wozu hier die Konvertierung abfragen wenn danach die Regel gelöscht wird?
			#$regel->konvertierung = $regel->get_konvertierung();
			$regel->destroy();
		}
		$this->destroy_objekt_zu_textabschnitte();
		$this->destroy_associated_objekte();
		$this->destroy_associated_praesentationsobjekte();
		$sql = "
			DELETE FROM
				xplan_gml." . $this->planartAbk . "_bereich
			WHERE
				gml_id::text = '" . $this->get($this->identifier) . "'::text
		";
		$result = $this->database->execSQL($sql, 0, 3);
		//$this->delete();
	}
}
?>
