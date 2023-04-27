<?
	$new_file_path = SHAPEPATH . '/xplankonverter';

	if (XPLANKONVERTER_FILE_PATH != $new_file_path) {
		if (file_exists($new_file_path)) {
			echo '<br>Fehler beim Anlegen des Pfades ' . $new_file_path . ' weil das Verzeichnis schon existiert!';
		}
		else {
			if (file_exists(XPLANKONVERTER_FILE_PATH)) {
				rename(XPLANKONVERTER_FILE_PATH, $new_file_path);
			}
			else {
				mkdir($new_file_path, 0775);
			}
			$sql = "
				UPDATE
					config
				SET
				  prefix = 'SHAPEPATH',
					value = 'xplankonverter/'
				WHERE
				  name = 'XPLANKONVERTER_FILE_PATH'
			";
			$result = $this->database->execSQL($sql, 0, 0);
			if ($result[0]) {
				echo '<br>Fehler beim Update der Konstante XPLANKONVERTER_FILE_PATH!<br>';
				rename($new_file_path, XPLANKONVERTER_FILE_PATH);
			}
			else {
				# config.php schreiben
				$result = $this->write_config_file('xplankonverter');
			}
		}
	}
?>
