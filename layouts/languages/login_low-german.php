<?php
	$strLogin = 'Anmeldung';
	$strLoginFailed = 'Anmeldung nicht erfolgreich';
	$strLoginFailedMsg = array(
		AuthErrCodes::WRONG_PASSWORD => 'Passwort %s mal falsch eingegeben!',
		AuthErrCodes::WRONG_LOGIN_NAME => 'Zugangdaten falach eingegeben!',
		AuthErrCodes::LOGIN_IS_LOCKED => 'Der Zugang ist wegen mehrfacher falscher Eingabe bis<br>%s gesperrt!',
		 => 'Der zeitlich eingeschränkte Zugang des Nutzers ist abgelaufen',
		AuthErrCodes::ACCOUNT_NOT_YET_STARTED => 'Der zeitlich eingeschränkte Zugang des Nutzers hat noch nicht begonnen.'
	);
	$strLoginLockedUntil = 'Login freigegeben';
	$strPassword = 'Passwort';
	$strUserName = 'Nutzername';
	$strYourIpAddress = 'Ihre IP-Adresse';

?>
