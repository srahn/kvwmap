<?php
	ob_end_clean();
	if (!$userDb->open()) {
	  echo 'Die Verbindung zur Benutzerdatenbank konnte mit folgenden Daten nicht hergestellt werden:';
	  echo '<br>Host: '.$userDb->host;
	  echo '<br>User: '.$userDb->user;
	  echo '<br>Datenbankname: '.$userDb->dbName;
	  exit;
	}
	session_start();

	if($_REQUEST['gast'] != '' AND in_array($_REQUEST['gast'], $gast_stellen)){
		$gast = $userDb->create_new_gast($_REQUEST['gast']);
		$username = $gast['username'];    
		$passwort = $gast['passwort'];
	}
	else{
		$username = $_REQUEST['username'];
		$passwort = $_REQUEST['passwort'];
	}
	$oldPassword = $_REQUEST['passwort'];
	$newPassword = $_REQUEST['newPassword'];
	$msg = $_REQUEST['msg'];
	$mobile = $_REQUEST['mobile'];
        $remote_addr = getenv('REMOTE_ADDR');
    
	// Benutzername und Passwort werden überprüft
	if ($userDb->login_user($username, $passwort)) {
		$_SESSION['angemeldet'] = true;
		$_SESSION['login_name'] = $username;
		$_SESSION['login_routines'] = true;
		if($mobile == 'on'){
			$_SESSION['mobile'] = 'true';
		}
		else{
			$_SESSION['mobile'] = 'false';
		}
	}
	else{
		?><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
		 <head>
		  <title>kvwmap</title>
		 </head>
		 <body style="font-family: Arial, Verdana, Helvetica, sans-serif" onload="document.login.username.focus();">
		  <form name="login" action="index.php" method="post">
				<input type="hidden" name="go" value="login">
				<?
				for($i = 0; $i < count($_REQUEST); $i++){
					echo '<input type="hidden" name="'.key($_REQUEST).'" value="'.$_REQUEST[key($_REQUEST)].'">';
					next($_REQUEST);
				}
				?>
				<table align="center" cellspacing="0" cellpadding="10" border="0" bgcolor="<?php echo BG_DEFAULT; ?>">
					<tr>
						<td align="center">
							<table cellspacing="0" cellpadding="2" border="0">
								<tr>
									<td align="center" colspan="2"><h2>kvwmap&nbsp;Anmeldung</h2></td>
								</tr><?php
					if($username != '' OR $passwort != ''){
						  ?><tr>
									<td colspan="2"><font color="red"><b>Anmeldung nicht erfolgreich.</b></font><br><br></td>
									</tr><?php														
					}								
			        ?><tr>
									<td>Ihre IP-Adr: </td>
									<td><?php echo $remote_addr; ?></td>
								</tr>
								<tr>
									<td>Username: </td>
  								<td><input type="text" value="<? echo $username; ?>" name="username"/></td>
								</tr>
								<tr>
									<td>Passwort: </td>
									<td><input type="password" name="passwort" size="10"/></td>
								</tr><?php
					if (isset($newPassword) AND $newPassword!='') {
						  ?><tr>
									<td width="400" colspan="2"><font size="-1"><?php echo urldecode($msg); ?></font></td>
								</tr>
								<tr>
									<td>Neues Passwort: </td>
									<td><input type="password" name="newPassword" size="10"/></td>
								</tr>
								<tr>
									<td>Wiederholung: </td>
									<td><input type="password" name="newPassword2" size="10"/></td>
								</tr><?php
					}			
					if(MOBILE == 'true'){
						  ?><tr>
									<td>mobil:</td>
									<td><input type="checkbox" value="on" name="mobile"/></td>
								</tr><?php
					}
              ?><tr>
									<td colspan="2" align="center"><br><input type="submit" value="Anmelden"/></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</form>
		 </body>
		</html><?php
		exit;
	}
?>
