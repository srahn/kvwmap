<?php
	global $GUI;
	
	$sql = "
		SELECT *
		FROM migrations
		WHERE `filename` = '2014-09-12_16-33-22_Version2.0.sql'
		AND `component` = 'kvwmap'
		AND `type` = 'mysql'
	";
	$this->database->execSQL($sql, 0, 0);

	if ($this->database->result->num_rows > 0) {
		if(!is_writable(LAYOUTPATH.'db/mysql/schema')){
			$GUI->add_message('Fehler', 'Keine Schreibrechte in '.LAYOUTPATH.'db/mysql/schema/. Sorgen Sie bitte in diesem Verzeichnis für Gruppenschreibrechte.');
		}
		else{
			$zip_file = LAYOUTPATH.'db/mysql/schema/mysql_migrations_upto_3.0.zip';
			$zip_url = 'https://gdi-service.de/public/kvwmap_resources/mysql_migrations_upto_3.0.zip';
			stream_context_set_default(
				array(
					'ssl' => array(
						'verify_peer'      => FALSE,
						'verify_peer_name' => FALSE
					)
				)
			);
			file_put_contents($zip_file, file_get_contents($zip_url));
			if (file_exists($zip_file)) {
				unzip($zip_file, false, false, true);
				unlink($zip_file);
				$GUI->add_message('Notice', 'Herunterladen erfolgreich. <br>Der folgende Fehler in Migrationsdatei 2020-11-06_10-00-00_migrate_to_3.0.php kann ignoriert werden. Bitte nochmals auf Aktualisieren klicken.');
			}
		}
		$result[0] = true;		# abbrechen, damit nachfolgende Migrationen nicht ausgeführt werden
	}
?>