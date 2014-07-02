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
	#$newPassword2 = $_REQUEST['newPassword2'];
	$msg = $_REQUEST['msg'];
	$mobile = $_REQUEST['mobile'];
        $remote_addr = getenv('REMOTE_ADDR');
    
	// Benutzername und Passwort werden überprüft
	##if (($newPassword == '' OR ($newPassword != '' AND $newPassword2 != '')) AND $userDb->login_user($username, $passwort)) {
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
		  <title><?php echo TITLE; ?></title>
      <META http-equiv=Content-Type content="text/html; charset=UTF-8">
			<link rel="stylesheet" href="layouts/main.css">
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
				<br>
				<table align="center" cellspacing="4" cellpadding="12" border="0" style="box-shadow: 12px 10px 14px #777; border: 1px solid #bbbbbb; background: linear-gradient(<? echo BG_GLEATTRIBUTE; ?> 0%, <? echo BG_DEFAULT ?> 100%);">
					<tr>
						<td align="center">
							<table cellspacing="0" cellpadding="2" border="0">
								<tr>
									<td align="center" colspan="2"><h1>kvwmap&nbsp;Anmeldung</h1></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
								<?php
					if($username != '' OR $passwort != ''){
						  ?><tr>
									<td align="center" colspan="2"><span class="fett" style="color: #ff0000;">Anmeldung nicht erfolgreich.</span><br><br></td>
								</tr><?php														
					}								
			        ?><tr>
									<td>Nutzername: </td>
  								<td><input style="width: 130px" type="text" value="<? echo $username; ?>" name="username"/></td>
								</tr>
								<tr>
									<td>Passwort: </td>
									<td><input style="width: 130px" type="password" value="<? echo $passwort; ?>" name="passwort" /></td>
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
									<td style="height: 40px" colspan="2">Ihre IP-Adresse: <?php echo $remote_addr; ?></td>
								</tr>
								<tr>
									<td colspan="2" align="center"><input type="submit" value="Anmelden"/></td>
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