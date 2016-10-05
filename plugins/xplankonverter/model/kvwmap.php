<?php
	$GUI = $this;

	/**
	* Trigger für Konvertierungen
	*/
	$this->trigger_functions['handle_konvertierung'] = function($fired, $event, $layer = '', $oid = 0, $old_dataset = array()) use ($GUI) {
		$executed = true;
		$success = true;

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
		return array('executed' => $executed, 'success' => $success);
	};

	/**
	* Trigger für RP_Plan Objekte
	*/
	$this->trigger_functions['handle_rp_plan'] = function($fired, $event, $layer = '', $oid = 0, $old_dataset = array()) use ($GUI) {
		$executed = true;
		$success = true;

		switch(true) {

			case ($fired == 'AFTER' AND $event == 'INSERT') : {
				#echo '<br>Führe ' . $fired . ' ' . $event . ' in handle_rp_plan Funktion aus.';
				$rp_plan = RP_Plan::find_by_id($this, 'oid', $oid);
				$konvertierung_id = $rp_plan->get('konvertierung_id');
				$konvertierung = Konvertierung::find_by_id($this, 'id', $konvertierung_id);
				$konvertierung->set_status();
			} break;

			case ($fired == 'AFTER' AND $event == 'DELETE') : {
				#echo '<br>Führe ' . $fired . ' ' . $event . ' in handle_rp_plan Funktion aus.';
				$konvertierung_id = $old_dataset['konvertierung_id'];
				$konvertierung = Konvertierung::find_by_id($this, 'id', $konvertierung_id);
				$konvertierung->set_status();
			} break;

			default : {
				#echo '<br>Default Case in ' . $fired . ' ' . $event . ' Triggerfunktion.';
				$executed = false;
			}
		}
		#echo '<br>Trigger Funktion ' . $fired . ' ' . $event . ' ausgeführt? ' . $executed;
		return array('executed' => $executed, 'success' => $success);
	};

	/**
	* Trigger für Regeln
	* @params $layer Array mit Angben des Layers aus der MySQL-Datenbank
	*/
	$this->trigger_functions['handle_regel'] = function($fired, $event, $layer, $oid = 0, $old_dataset = array()) use ($GUI) {
		$executed = true;
		$success = true;

		switch(true) {

			case ($fired == 'AFTER' AND $event == 'INSERT') : {
				$this->debug->show('Führe ' . $fired . ' ' . $event . ' in handle_regel Funktion aus mit oid: ' . $oid, false);
				$regel = Regel::find_by_id($this, 'oid', $oid);
				$regel->create_gml_layer();
				$regel->konvertierung->set_status();
			} break;

			case ($fired == 'INSTEAD' AND $event == 'DELETE') : {
				$this->debug->show('Führe ' . $fired . ' ' . $event . ' in handle_regel Funktion aus.', false);
				$regel = Regel::find_by_id($this, 'oid', $oid);
				$regel->destroy();
			} break;

			case ($fired == 'AFTER' AND $event == 'DELETE') : {
				$this->debug->show('Führe ' . $fired . ' ' . $event . ' in handle_regel Funktion aus.', false);
				if (empty($old_dataset['konvertierung_id'])) {
					# hole konvertierung_id ueber plan und bereich_gml_id
					$bereich = RP_Bereich::find_by_id($this, 'gml_id', $old_dataset['bereich_gml_id']);
					$plan = RP_Plan::find_by_id($this, 'gml_id', $bereich->get('gehoertzuplan'));
					$konvertierung_id = $plan->get('konvertierung_id');
				}
				else {
					$konvertierung_id = $old_dataset['konvertierung_id'];
				}

				$konvertierung = Konvertierung::find_by_id($this, 'id', $konvertierung_id);
				#echo '<br>Konvertierung mit id: ' . $konvertierung->get('id') . ' gefunden.';
				$konvertierung->set_status();
			}

			default : {
				$executed = false;
			}
		}
		return array('executed' => $executed, 'success' => $success);
	};
?>