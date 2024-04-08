<?
	$GUI->custom_trigger_functions['dbak_update'] = function($fired, $event, $layer = '', $oid = 0, $old_dataset = array()) use ($GUI) {

		include_once(WWWROOT.APPLVERSION.CUSTOM_PATH . 'class/config.php');
		include_once(WWWROOT.APPLVERSION.CUSTOM_PATH . 'class/AlTeilflaeche.php');
		include_once(WWWROOT.APPLVERSION.CUSTOM_PATH . 'class/DbAk.php');

		$executed = true;
		$success = true;

		switch(true) {
			case ($fired == 'AFTER' AND $event == 'INSERT') : {
				$teilflaeche = AlTeilflaeche::find_by_id($GUI, $oid);
				$dbak = new DbAk($teilflaeche);
				try {
					$result = $dbak->create();
					if ($result['success']) {
						$this->add_message('info', $result['msg']);
					}
					else {
						$this->add_message('notice', `Fehler beim Anlegen der Altlastenfläche im DbAk-Landesportal: {$result['msg']}`);
					}
				}
				catch (Exception $e) {
					$this->add_message('notice', `Fehler beim Anlegen der Altlastenfläche im DbAk-Landesportal: {$e->getMessage()}`);
					# Weitere Maßnahmen die Erforderlich sind um den Fehler zu berücksichtigen
				}
			} break;

			case ($fired == 'AFTER' AND $event == 'UPDATE') : {
				$teilflaeche = AlTeilflaeche::find_by_id($GUI, $oid);
				$dbak = new DbAk($teilflaeche);
				try {
					$result = $dbak->update();
					if ($result['success']) {
						$this->add_message('info', $result['msg']);
					}
					else {
						$this->add_message('notice', `Fehler beim Aktualisieren der Altlastenfläche im DbAk-Landesportal: {$result['msg']}`);
					}
				}
				catch (Exception $e) {
					$this->add_message('notice', `Fehler bei der Aktualisierung der Altlastenfläche im DbAk-Landesportal: {$e->getMessage()}`);
					# Weitere Maßnahmen die Erforderlich sind um den Fehler zu berücksichtigen
				}
			} break;

			case ($fired == 'AFTER' AND $event == 'DELETE') : {
				$teilflaeche = new AlTeilflaeche($GUI);
				$teilflaeche->data = $old_dataset;
				$dbak = new DbAk($teilflaeche);
				try {
					$result = $dbak->delete();
					if ($result['success']) {
						$this->add_message('info', $result['msg']);
					}
					else {
						$this->add_message('notice', `Fehler beim Löschen der Altlastenfläche im DbAk-Landesportal: {$result['msg']}`);
					}
				}
				catch (Exception $e) {
					$this->add_message('notice', `Fehler bei der Löschung der Altlastenfläche im DbAk-Landesportal: {$e->getMessage()}`);
					# Weitere Maßnahmen die Erforderlich sind um den Fehler zu berücksichtigen
				}
			} break;

			default : {
				$executed = false;
			}
		}
		return array('executed' => $executed, 'success' => $success);
	};
?>