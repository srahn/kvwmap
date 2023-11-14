<?php
	include('../config.php');
	function is_allowed() {
		if (!isset($_SESSION)) {
			session_start();
		}
		return (
			array_key_exists('angemeldet', $_SESSION) AND
			$_SESSION['angemeldet'] === true AND
			array_key_exists('csrf_token', $_SESSION) AND
			array_key_exists('csrf_token', $_REQUEST) AND
			$_SESSION['csrf_token'] == $_REQUEST['csrf_token'] AND
			array_key_exists('login_name', $_SESSION) AND
			$_SESSION['login_name'] == 'pkorduan'
		);
	}
	?>
<html>
	<head>
		<title>kvwmap Debug-Datei</title>
	</head>
	<body><?
		if (is_allowed()) { ?>
			login_name: <? echo $_SESSION['login_name']; ?><br>
			DEBUG_LEVEL: <? echo DEBUG_LEVEL; ?><br>
			DEBUGFILE: <? echo $_SESSION['login_name'] . DEBUGFILE; ?>
			LOGPATH: <? echo LOGPATH; ?>
			<? include(LOGPATH . $_SESSION['login_name'] . DEBUGFILE);
		} else {
			echo 'Access not allowed!';
		} ?>
	</body>
</html>