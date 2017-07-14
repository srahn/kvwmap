<?php
#############################
# Klasse Konvertierung #
#############################

class WasserrechtlicheZulassungenGueltigkeit extends PgObject {

	static $schema = 'wasserrecht';
	static $tableName = 'wasserrechtliche_zulassungen_gueltigkeit';
	static $write_debug = true;

	function WasserrechtlicheZulassungenGueltigkeit($gui) {
		parent::__construct($gui, WasserrechtlicheZulassungenGueltigkeit::$schema, WasserrechtlicheZulassungenGueltigkeit::$tableName);
	}
	
	public static function find_by_id($gui, $by, $id) {
		// 		echo '<br>find konvertierung by ' . $by . ' = ' . $id;
		// 		echo '<br>find anlage by ' . $by . ' = ' . $id;
		$wasserrechtlicheZulassungenGueltigkeit = new WasserrechtlicheZulassungenGueltigkeit($gui);
		$wasserrechtlicheZulassungenGueltigkeit->find_by($by, $id);
		return $wasserrechtlicheZulassungenGueltigkeit;
	}
}
?>