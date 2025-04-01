<?php
#############################
# Klasse Fortfuehrungsfall #
#############################

class Fortfuehrungsauftrag extends PgObject {
	
	static $schema = 'fortfuehrungslisten';
	static $tableName = 'ff_auftraege';
	static $write_debug = false;
	public $messages = array();

	function __construct($gui) {
		$gui->debug->show('Create new Object Fortfuehrungsauftrag', Fortfuehrungsauftrag::$write_debug);
		parent::__construct($gui, Fortfuehrungsauftrag::$schema, Fortfuehrungsauftrag::$tableName);
	}

public static	function find_by_id($gui, $by, $id) {
		$ff = new Fortfuehrungsauftrag($gui);
		$ff->find_by($by, $id);
		return $ff;
	}

public function auftragsdatei_loeschen() {
		$success = false;
		if ($this->get('auftragsdatei') == '') {
			$msg = 'Keine Auftragsdatei zum Löschen vorhanden!';
		}
		else {
			# FF_Auftrag hat Auftragsdatei -> löschen
			$file_name = $this->get_file_name();
			if (file_exists($file_name)) {
				$success = unlink($file_name); // success = true if unlink successfully
				if (!$success) {
					$messages[] = array(
						'msg' => 'Auftragsdatei konnte nicht gelöscht werden. Melden Sie dies bei Ihrem GIS-Administrator. Möglicherweise sind die Zugriffsrechte falsch gesetzt.',
						'type' => 'error'
					);
				}
			}
			else {
				$messages[] = array(
					'msg' => 'Auftragsdatei existierte nicht. Es wurde nur der Eintrag des Dateinamens gelöscht. Kommt dies öffter vor, informieren Sie Ihren GIS-Administrator. Registrierte Auftragsdateien sollten auch immer auf dem Server vorhanden sein. Möglicherweise wurden diese aber auf dem Server manuell gelöscht oder verschoben.',
					'type' => 'warning'
				);
			}

			# Name der Auftragsdatei im Datensatz des FF_Auftrag löschen
			$this->set('auftragsdatei', '');
			$this->update();
		}
		$result = array(
			'success' => $success
		);
		$this->gui->debug(print_r($result, true), true);
		return $result;
	}

public function get_file_name() {
		$auftragsdatei_parts = explode('&', $this->get('auftragsdatei'));
		$file_name = $auftragsdatei_parts[0];
		return $file_name;
	}

public function get_original_file_name() {
		$auftragsdatei_parts = explode('&', $this->get('auftragsdatei'));
		$original_file_name_parts = explode('=', $auftragsdatei_parts[1]);
		$original_file_name = $original_file_name_parts[1];
		return $original_file_name;
	}
}

?>
