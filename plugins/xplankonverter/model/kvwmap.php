<?
	$GUI = $this;

	/**
	* Trigger für Konvertierungen
	*/
	$this->trigger_functions['handle_konvertierung'] = function($fired, $event, $dataset, $layer = '', $layerdb, $table, $oid = 0) use ($GUI) {
		$konvertierung = Konvertierung::find_by_id($this, $dataset['id']);

		switch(true) {
			case ($fired == 'AFTER' AND $event == 'INSERT') : {
				$konvertierung->create_layer_group('GML');
			} break;
			case ($fired == 'BEFORE' AND $event == 'DELETE') : {
				$konvertierung->delete_regeln();
				$konvertierung->delete_layer_group('GML');
			}
		}
	};

	/**
	* Trigger für Regeln
	* @params $layer Array mit Angben des Layers aus der MySQL-Datenbank
	*/
	$this->trigger_functions['handle_regel'] = function($fired, $event, $layer, $oid = 0) use ($GUI) {
		switch(true) {
			case ($fired == 'AFTER' AND $event == 'INSERT') : {
				$regel = Regel::find_by_id($this, 'oid', $oid);
				$regel->create_gml_layer();
			} break;
			case ($fired == 'BEFORE' AND $event == 'DELETE') : {
			#	$regel = Regel::find_by_id($this, 'oid', $oid);
		#		$regel->delete_gml_layer($oid);
			} break;
		}
	};
?>