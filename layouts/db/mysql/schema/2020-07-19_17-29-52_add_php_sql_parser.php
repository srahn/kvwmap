<?
global $GUI;
$install_msg;
$success = true;
if (!file_exists('../../../../../3rdparty/PHP-SQL-Parser/src/PHPSQLParser.php')) {
	$install_msg .= 'Install PHPSQLParser.php';
	
	$url = 'https://gdi-service.de/public/kvwmap_resources/php-sql-parser-20140108.zip';
  $zipfile = '/var/www/apps/3rdparty/php-sql-parser-20140108.zip';
	$extract_path = '/var/www/apps/3rdparty/';
	
	$install_msg .= '<br>Download Zip-File from: ' . $url;
  $fp_zipfile = fopen ($url, 'rb');
  if ($fp_zipfile) {
    $newf = fopen ($zipfile, 'wb');
    if ($newf) {
			$install_msg .= '<br>Schreibe Zip-File nach: ' . $zipfile . '...';
	    while (!feof($fp_zipfile)) {
	      fwrite($newf, fread($fp_zipfile, 1024 * 8), 1024 * 8);
	    }
			$install_msg .= 'fertig';
			$zip = new ZipArchive;
			try {
				$res = $zip->open($zipfile);
				if ($res === TRUE) {
				  $zip->extractTo($extract_path);
				  $zip->close();
					$install_msg .= '<br>ZIP-File extrahiert nach: ' . $extract_path;
					unlink($zipfile);
				} else {
				  $install_msg .= '<br>Kann File nicht extrahieren!';
					$success = false;
				}
			} catch (Exception $e) {
				$install_msg .= '<br>Sorgen Sie für Schreibrechte im Verzeichnis: /var/www/apps/3rdparty mit dem Befehl: chmod g+w /var/www/apps/3rdparty';        	
				$success = false;
			}
    }
		else {
			$install_msg .= '<br>Kann Zip-Datei nicht speichern';
			$install_msg .= '<br>Sorgen Sie für Schreibrechte im Verzeichnis: /var/www/apps/3rdparty mit dem Befehl: chmod g+w /var/www/apps/3rdparty';        	
			$success = false;
		}
  }
  if ($fp_zipfile) {
    fclose($fp_zipfile);
  }
  if ($newf) {
    fclose($newf);
  }
}
else {
	$install_msg .= 'PHPSQLParser.php allready exists';
}
$GUI->add_message(($success ? 'Notice' : 'Fehler'), $install_msg);

$result[0] = $success;

?>