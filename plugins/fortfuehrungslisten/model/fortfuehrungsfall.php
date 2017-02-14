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

	/**
	* Ermittelt ob das alte Flurstück fortgeführt wurde
	* wenn es die Nummern der neuen Flurstücke in zeigtaufneuesflurstueck
	* identisch sind mit der alten Nummer in zeigtaufaltesflurstueck
	* hat sich nichts geändert und es wird false zurückgegebnen.
	* @return boolean
	*/
	function has_changed_parcels() {
		$has_changed = false;
		$altes_flst = $this->get('zeigtaufaltesflurstueck')[0];
		foreach($this->get('zeigtaufneuesflurstueck') AS $neuesflst) {
			$has_changed = ($altes_flst != $neuesflst);
		}
		return $has_changed;
	}
}

?>
