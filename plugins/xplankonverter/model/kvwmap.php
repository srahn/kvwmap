<?
	$GUI = $this;

	/**
	* Trigger für Konvertierungen
	*/
	$this->trigger_functions['handle_konvertierung'] = function($fired, $event, $layer = '', $oid = 0, $old_dataset = array()) use ($GUI) {
		$executed = true;
		switch(true) {
			# Erzeuge Layergruppe nach dem Erzeugen einer Konvertierung
			case ($fired == 'AFTER' AND $event == 'INSERT') : {
				$konvertierung = Konvertierung::find_by_id($this, 'oid', $oid);
				$konvertierung->create_layer_group('GML');
			} break;

			case ($fired == 'INSTEAD' AND $event == 'DELETE') : {
				$konvertierung = Konvertierung::find_by_id($this, 'oid', $oid);
				$konvertierung->destroy();
			} break;

			default : {
				$executed = false;
			}
		}
		return array('executed' => $executed);
	};

	/**
	* Trigger für RP_Plan Objekte
	*/
	$this->trigger_functions['handle_rp_plan'] = function($fired, $event, $layer = '', $oid = 0, $old_dataset = array()) use ($GUI) {
		$executed = true;
		switch($true) {

			case ($fired == 'AFTER' AND $event == 'INSERT') : {
				$rp_plan = RP_Plan::find_by_id($this, 'oid', $oid);
				$konvertierung_id = $rp_plan->get('konvertierung_id');
				$konvertierung = Konvertierung::find_by_id($this, 'id', $konvertierung_id);
				$konvertierung->set_status();

			} break;

			case ($fired == 'AFTER' AND $event == 'DELETE') : {
				$konvertierung_id = $old_dataset['konvertierung_id'];
				$konvertierung = Konvertierung::find_by_id($this, 'id', $konvertierung_id);
				$konvertierung->set_status();
			} break;

			default : {
				$executed = false;
			}
		}
		return array('executed' => $executed);
	};

	/**
	* Trigger für Regeln
	* @params $layer Array mit Angben des Layers aus der MySQL-Datenbank
	*/
	$this->trigger_functions['handle_regel'] = function($fired, $event, $layer, $oid = 0, $old_dataset = array()) use ($GUI) {
		$executed = true;
		switch(true) {

			case ($fired == 'AFTER' AND $event == 'INSERT') : {
				$regel = Regel::find_by_id($this, 'oid', $oid);
				$regel->create_gml_layer();
				$konvertierung = Konvertierung::find_by_id($this, 'oid', $oid);
				$konvertierung->set_state();
			} break;

			case ($fired == 'INSTEAD' AND $event == 'DELETE') : {
				$regel = Regel::find_by_id($this, 'oid', $oid);
				$regel->destroy();
			} break;

			case ($fired == 'AFTER' AND $event == 'DELETE') : {
				$konvertierung = Konvertierung::find_by_id($this, 'oid', $oid);
				$konvertierung->set_state();
			}

			default : {
				$executed = false;
			}
		}
		return array('executed' => $executed);
	};
?>