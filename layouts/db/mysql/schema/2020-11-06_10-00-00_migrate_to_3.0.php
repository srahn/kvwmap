<?php
	$sql = "
		SELECT count(*)
		FROM migrations
	";
	echo '<br>Frage Anzahl der registrierten Migrationen ab mit SQL: ' . $sql;
	$this->database->execSQL($sql, 0, 0);

	if ($this->database->result->num_rows > 0) {
		echo '<br>Anzahl der Einträge in migrations Tabelle: ' . $this->database->result->num_rows;
		echo '<br>Download alle Migrations-Dateien von vor Version 3.0 um eventuell fehlende auszuführen.';
		$success = false;
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
			$success = true;
		}
		$result[0] = !$success;
	}
?>