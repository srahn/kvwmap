<?
	$folder = getcwd();
	if (defined('HTTP_PROXY')) {
		putenv('https_proxy=' . HTTP_PROXY);
	}
	$cmd = 'cd ' . $folder . ' && sudo -u ' . GIT_USER . ' git remote update';
	exec($cmd, $test, $ret);
	if ($ret != 0) {
		echo 'Fehler bei der Ausführung von "' . $cmd . '"';
	}
?>