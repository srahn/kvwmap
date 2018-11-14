<?php
	$title = 'kvwmap Anmeldung';
	$login_name = $this->formvars['login_name'];
	$passwort = $this->formvars['passwort'];
	if (file_exists(LAYOUTPATH . 'snippets/' . LOGIN)) {
		include(LAYOUTPATH . 'snippets/' . LOGIN);
	}
	else {
		include(LAYOUTPATH . 'snippets/login.php');
	}
	$GUI->output();
	exit;
?>
