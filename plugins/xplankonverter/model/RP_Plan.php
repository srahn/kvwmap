<?php
#############################
# Klasse RP_Plan #
#############################

class RP_Plan extends PgObject {

	static $schema = 'xplan_gml';
	static $tableName = 'rp_plan';

	function RP_Plan($gui, $select = '*') {
		$this->PgObject($gui, RP_Plan::$schema, RP_Plan::$tableName);
		$this->bereiche = array();
		$this->select = $select;
		$this->identifier = 'gml_id';
		$this->identifier_type = 'text';
	}

	public static	function find_by_id($gui, $by, $id) {
		$rp_plan = new RP_Plan($gui);
		$rp_plan->find_by($by, $id);
		return $rp_plan;
	}
}
?>
