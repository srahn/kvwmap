<?php
	$strLogin = 'Login';
	$strLoginFailed = 'Login unsuccessfull';
	$strLoginFailedMsg = array(
		AuthErrCodes::WRONG_PASSWORD => 'Password entered false %s times!',
		AuthErrCodes::WRONG_LOGIN_NAME => 'Access data entered incorrectly!',
		AuthErrCodes::LOGIN_IS_LOCKED => 'Account locked due to multiple false try for login until %s',
		 => 'The time limmited account has been expired.',
		AuthErrCodes::ACCOUNT_NOT_YET_STARTED => 'The time limmitted account did not start yet.'
	);
	$strUserName = 'Login name';
	$strPassword = 'Password';
	$strLoginLockedUntil = 'Account released';
	$strYourIpAddress = 'Your IP-Address';
?>
