<?php
#############################
# Klasse Fortfuehrungsfall #
#############################

class Fortfuehrungsfall extends PgObject {
	
	static $schema = 'fortfuehrungslisten';
	static $tableName = 'ff_faelle';
	static $write_debug = false;

	function Fortfuehrungsfall($gui) {
		$gui->debug->show('Create new Object Fortfuhrungsfall', Fortfuehrungsfall::$write_debug);
		$this->PgObject($gui, Fortfuehrungsfall::$schema, Fortfuehrungsfall::$tableName);
	}

public static	function find_by_id($gui, $by, $id) {
		$ff = new Fortfuehrungsfall($gui);
		$ff->find_by($by, $id);
		return $ff;
	}
}

?>
