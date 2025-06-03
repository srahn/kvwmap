<?

$branches = [
	'kvwmap',
	'master'
];

	$folder = getcwd();
	if (defined('HTTP_PROXY')) {
		putenv('https_proxy=' . HTTP_PROXY);
	}
	$cmd = 'cd ' . $folder . (implode(' ', array_map(function ($branch) {return ' && sudo -u ' . GIT_USER . ' git fetch origin ' . $branch;}, $branches)));
	exec($cmd, $test, $ret);
	if ($ret != 0) {
		echo 'Fehler bei der Ausführung von "' . $cmd . '"';
	}
?>