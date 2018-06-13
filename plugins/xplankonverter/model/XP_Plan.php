<?php
#############################
# Klasse XP_Plan #
#############################

class XP_Plan extends PgObject {

	static $schema = 'xplan_gml';

	function XP_Plan($gui, $planart, $select = '*') {
		$this->planart = $planart;
		$this->planartAbk = strtolower(substr($planart, 0, 2));
		$this->tableName = $this->planartAbk . '_plan';
		$this->umlName = strtoupper($this->planartAbk) . '_Plan';
		$this->bereichTableName = $this->planartAbk . '_bereich';
		$this->bereichUmlName = strtoupper($this->planartAbk) . '_Bereich';
		$this->PgObject($gui, XP_Plan::$schema, $this->tableName);
		$this->bereiche = array();
		$this->select = $select;
		$this->identifier = 'gml_id';
		$this->identifier_type = 'text';
	}

	public static	function find_by_id($gui, $by, $id, $planart) {
		$xp_plan = new XP_Plan($gui, $planart);
		$xp_plan->find_by($by, $id);
		return $xp_plan;
	}

	function get_bereiche() {
		$bereiche = array();
		$bereich = new XP_Bereich($this->gui, $this->planart);
		$bereiche = $bereich->find_where("
			gehoertzuplan = '{$this->get('gml_id')}'
		");
		return $bereiche;
	}
	
	/*
	* Löscht den Plan und alles was damit verbunden ist
	* Löscht die Bereiche
	*/
	function destroy() {
		$bereiche = $this->get_bereiche();
		foreach($bereiche AS $bereich) {
			$bereich->destroy();
		}
	}
}
?>
