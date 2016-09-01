<?php
#############################
# Klasse RP_Plan #
#############################

class RP_Plan extends PgObject {

	static $schema = 'gml_classes';
	static $tableName = 'rp_plan';

	function RP_Plan($gui, $select = '*') {
		$this->PgObject($gui, RP_Plan::$schema, RP_Plan::$tableName);
		$this->bereiche = array();
		$this->select = $select;
	}
}
?>
