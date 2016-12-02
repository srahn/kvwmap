<?php
#############################
# Klasse Fortfuehrungsfall #
#############################

class Fortfuehrungsauftrag extends PgObject {
	
	static $schema = 'fortfuehrungslisten';
	static $tableName = 'ff_auftraege';
	static $write_debug = false;

	function Fortfuehrungsauftrag($gui) {
		$gui->debug->show('Create new Object Fortfuehrungsauftrag', Fortfuehrungsauftrag::$write_debug);
		$this->PgObject($gui, Fortfuehrungsauftrag::$schema, Fortfuehrungsauftrag::$tableName);
	}

public static	function find_by_id($gui, $by, $id) {
		$ff = new Fortfuehrungsauftrag($gui);
		$ff->find_by($by, $id);
		return $ff;
	}
}

?>
