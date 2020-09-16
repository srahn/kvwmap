<?php
	array_shift($argv);
	$_REQUEST = array();
	foreach ($argv AS $arg) {
		
		list($key, $val) = explode('=', $arg);
		$_REQUEST[$key] = $val;
	}
	include('index.php');
?>
