<?
if (isset($this) AND is_object($this) AND get_class($this) == 'administration') {
	$versions = array('5.0.1', '5.1', '5.2', '5.4');
	foreach ($versions AS $version) {
		$dest_dir = WWWROOT . '../html/modell/xsd/' . $version;
		if (is_dir($dest_dir)) {
			$this->database->gui->add_message('error', 'Version ' . $version . ' existiert schon auf dem Server!');
		}
		else {
			$zip_file = '/tmp/xsd-' . $version . '.zip';
			file_put_contents($zip_file, fopen('https://gdi-service.de/public/schemas/xplan/xsd/' . $version . '/xplan-xsd-' . $version . '.zip', 'r'));
			$zip = new ZipArchive;
			$res = $zip->open($zip_file);
			if ($res === TRUE) {
				$zip->extractTo('/tmp');
				$zip->close();
				$src_dir = '/tmp/' . $version;
				exec('mv ' . $src_dir . ' ' . $dest_dir);
				$this->database->gui->add_message('warning', 'Source ' . $src_dir . ' erfolgreich nach ' . $dest_dir  . ' geschrieben.');
				exec('rm -R ' . $src_dir);
			}
			else {
				$this->database->gui->add_message('error', 'Kann Zip-Datei: ' . $zip_file . ' nicht öffnen!');
			}
			unlink($zip_file);
		}
	}
}
else {
	echo 'Keine Berechtigung für den Zugriff auf diese Seite.';
}
?>
