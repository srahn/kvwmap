<?
	$GUI = $this;

	/**
	* Trigger für Konvertierungen
	*/
	$this->trigger_functions['handle_konvertierung'] = function($fired, $event, $layer = '', $oid = 0, $old_dataset = array()) use ($GUI) {

		switch(true) {

			# Erzeuge Layergruppe nach dem Erzeugen einer Konvertierung
			case ($fired == 'AFTER' AND $event == 'INSERT') : {
				$konvertierung = Konvertierung::find_by_id($this, 'oid', $oid);
				$konvertierung->create_layer_group('GML');
			} break;

			# Lösche vor dem Löschen eienr Konvertierung alle Bereiche und Regeln, die dazu gehören.
			case ($fired == 'BEFORE' AND $event == 'DELETE') : {
				$konvertierung = Konvertierung::find_by_id($this, 'oid', $oid);
				$konvertierung->delete_regeln();
				# todo delete_bereiche und deren regeln.
				$konvertierung->delete_layer_group('GML');
			}
		}
	};

	/**
	* Trigger für RP_Plan Objekte
	*/
	$this->trigger_functions['handle_rp_plan'] = function($fired, $event, $layer = '', $oid = 0, $old_dataset = array()) use ($GUI) {

		# Aktualisiere Status der Konvertierung nach dem Erzeugen oder Löschen eines RP_Plan
		if ($fired == 'AFTER' AND in_array($event, array('INSERT', 'DELETE'))) {
			if ($event == 'INSERT') {
				$rp_plan = RP_Plan::find_by_id($this, 'oid', $oid);
				$konvertierung_id = $rp_plan->get('konvertierung_id');
			}

			if ($event == 'DELETE') {
				$konvertierung_id = $old_dataset['konvertierung_id'];
			}

			$konvertierung = Konvertierung::find_by_id($this, 'id', $konvertierung_id);
			$konvertierung->set_status();
		}

	};

	/**
	* Trigger für Regeln
	* @params $layer Array mit Angben des Layers aus der MySQL-Datenbank
	*/
	$this->trigger_functions['handle_regel'] = function($fired, $event, $layer, $oid = 0, $old_dataset = array()) use ($GUI) {
		switch(true) {
			case ($fired == 'AFTER' AND $event == 'INSERT') : {
				$regel = Regel::find_by_id($this, 'oid', $oid);
				$regel->create_gml_layer();
				$konvertierung = Konvertierung::find_by_id($this, 'oid', $oid);
				$konvertierung->update_state();
			} break;
			case ($fired == 'BEFORE' AND $event == 'DELETE') : {
			#	$regel = Regel::find_by_id($this, 'oid', $oid);
		#		$regel->delete_gml_layer($oid);
			} break;
			case ($fired == 'AFTER' AND $event == 'DELETE') : {
				$konvertierung = Konvertierung::find_by_id($this, 'oid', $oid);
				$konvertierung->update_state();
			}
		}
	};
?>