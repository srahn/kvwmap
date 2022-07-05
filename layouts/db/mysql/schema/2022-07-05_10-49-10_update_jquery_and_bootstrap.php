<?
	if (isset($this) AND is_object($this) AND get_class($this) == 'administration') {
		$zip_file = '/tmp/kvwmap-server.zip';
		file_put_contents($zip_file, fopen("https://github.com/pkorduan/kvwmap-server/archive/refs/heads/develop.zip", 'r'));
		$zip = new ZipArchive;
		$res = $zip->open($zip_file);
		if ($res === TRUE) {
			$zip->extractTo('/tmp');
			$zip->close();
			$src_dir = '/tmp/kvwmap-server-develop/service-templates/web/directory_template/www/apps/3rdparty/';
			if (is_dir($src_dir)) {
				$dest_dir = WWWROOT . APPLVERSION . THIRDPARTY_PATH;
				$libs = array(
					array( 'const' => 'BOOTSTRAPTABLE_PATH', 'value' => 'bootstrap-table-1.20.2', 'content' => 'bootstrap-table-1.20.2'),
					array( 'const' => 'BOOTSTRAP_PATH', 'value' => 'bootstrap-4.6.1', 'content' => 'bootstrap-4.6.1'),
					array( 'const' => 'JQUERY_PATH', 'value' => 'jQuery-3.6.0', 'content' => 'jQuery-3.6.0/jquery.base64.js')
				);
				foreach ($libs AS $lib) {
					if (
						(is_file($src_dir . $lib['content']) AND !is_file($dest_dir . $lib['content'])) OR
						(is_dir($src_dir . $lib['content']) AND !is_dir($dest_dir . $lib['content']))
					) {
						exec('mv ' . $src_dir . $lib['content'] . ' ' . $dest_dir . $lib['content']);
						$this->database->gui->add_message('warning', 'Source ' . $src_dir . $lib['content'] . ' erfolgreich nach ' . $dest_dir . $lib['content'] . ' geschrieben.');

						exec('sed -i "/' . $lib['const'] . '/c\define(\'' . $lib['const'] . '\', \'../3rdparty/' . $lib['value'] . '/\');" ' . WWWROOT . APPLVERSION . 'config.php');
						$this->database->gui->add_message('notice', 'Konstante ' . $lib['constant'] . ' in config.php auf ../3rdparty/' . $lib['value'] . '/ gesetzt.');
						$sql = "
							UPDATE
								config
							SET
								prefix = 'THIRDPARTY_PATH',
								value = '" . $lib['value'] . "/'
							WHERE
								name = '" . $lib['const'] . "'
						";
						#echo 'Update Constant ' . $lib['const'] . ' with sql: ' . $sql;
						$ret = $this->database->execSQL($sql);
						if (!$ret['success']) {
							$this->database->gui->add_message('error', 'Fehler beim Update der Konstante ' . $lib['const'] . ' in der Tabelle config.<p>Fehler:<br>' . $this->database->mysqli->error);
						}
						else {
							$this->database->gui->add_message('warning', 'Konstante ' . $lib['const'] . ' in Datenbank Tabelle config auf ' . $lib['value'] . '/ geändert.');
						}
					}
					else {
						$this->database->gui->add_message('warning', 'Source ' . $src_dir . $lib['content'] . ' existiert nicht oder ' . $dest_dir . $lib['content'] . ' ist schon vorhanden.');
					}
				}
				exec('rm -R /tmp/kvwmap-server-develop');
				unlink($zip_file);
			}
			else {
				$this->database->gui->add_message('error', 'Verzeichnis ' . $dest_dir . ' existiert nicht!');
			}
		}
		else {
			$this->database->gui->add_message('error', 'Kann Zip-Datei: /tmp/kvwmap-server.zip nicht öffnen!');
		}
	}
	else {
		echo 'Keine Berechtigung für den Zugriff auf diese Seite.';
	}
?>