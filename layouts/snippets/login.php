<? ob_end_clean();
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
		$passwort = $gast['passwort'];	?>
		
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
		<head>
			<title><?php echo TITLE; ?></title>
			<META http-equiv=Content-Type content="text/html; charset=UTF-8">
			<link rel="stylesheet" href="layouts/main.css.php">
			<script type="text/javascript">

			function logon(){
				if(typeof(window.innerWidth) == 'number'){
					width = window.innerWidth;
					height = window.innerHeight;
				}else if(document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)){
					width = document.documentElement.clientWidth;
					height = document.documentElement.clientHeight;
				}else if(document.body && (document.body.clientWidth || document.body.clientHeight)){
					width = document.body.clientWidth;
					height = document.body.clientHeight;
				}
				document.login.browserwidth.value = width;
				document.login.browserheight.value = height;
				document.login.submit();
			}
							
			</script>
		</head>
		<body onload="logon();">
		  <form name="login" action="index.php" method="post">
				<input type="hidden" value="<? echo $username; ?>" name="username"/>
				<input type="hidden" value="<? echo $passwort; ?>" name="passwort" />
				<input type="hidden" name="browserwidth">
				<input type="hidden" name="browserheight">
				<br>
				<? 
				for($i = 0; $i < count($_REQUEST); $i++){
					if(key($_REQUEST) != 'gast'){			// sonst gibts ne Endlosschleife
						echo '<input type="hidden" name="'.key($_REQUEST).'" value="'.$_REQUEST[key($_REQUEST)].'">';
					}
					next($_REQUEST);
				}
				?>
				<table align="center" cellspacing="4" cellpadding="22" bgcolor="<? echo BG_DEFAULT; ?>" border="0" style="background-color: <? echo BG_DEFAULT; ?>; box-shadow: 12px 10px 14px #777; border: 1px solid #bbbbbb; background: linear-gradient(<? echo BG_GLEATTRIBUTE; ?> 0%, <? echo BG_DEFAULT ?> 100%);">
					<tr>
						<td align="center"><h1>Bitte warten...</h1></td>
					</tr>
				</table>
			</form>
		</body>
		</html>
		<?
		exit;
	}
	else{
		$username = $_REQUEST['username'];
		$passwort = $_REQUEST['passwort'];
	}
	$oldPassword = $_REQUEST['passwort'];
	$newPassword = $_REQUEST['newPassword'];
	$newPassword2 = $_REQUEST['newPassword2'];
	$msg = $_REQUEST['msg'];
	$mobile = $_REQUEST['mobile'];
  $remote_addr = getenv('REMOTE_ADDR');
    
	// Benutzername und Passwort werden überprüft
	if (($newPassword == '' OR ($newPassword != '' AND $newPassword2 != '')) AND $userDb->login_user($username, $passwort)) {
	#if ($userDb->login_user($username, $passwort)) {
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
		?>
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
		<head>
			<title><?php echo TITLE; ?></title>
			<META http-equiv=Content-Type content="text/html; charset=UTF-8">
			<link rel="stylesheet" href="layouts/main.css.php">
			<script type="text/javascript">

			function logon(){
				if(typeof(window.innerWidth) == 'number'){
					width = window.innerWidth;
					height = window.innerHeight;
				}else if(document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)){
					width = document.documentElement.clientWidth;
					height = document.documentElement.clientHeight;
				}else if(document.body && (document.body.clientWidth || document.body.clientHeight)){
					width = document.body.clientWidth;
					height = document.body.clientHeight;
				}
				document.login.browserwidth.value = width;
				document.login.browserheight.value = height;
				document.login.submit();
			}
			
			document.onkeydown = function(ev){
				var key;
				ev = ev || event;
				key = ev.keyCode;
				if (key == 13) {
					document.login.anmelden.click();
				}
			}
				
			</script>
		</head>
		<body style="font-family: Arial, Verdana, Helvetica, sans-serif" onload="document.login.username.focus();">
		  <form name="login" action="index.php" method="post">
				<? 
				for($i = 0; $i < count($_REQUEST); $i++){
					if (!in_array(key($_REQUEST), array('go', 'username', 'passwort'))) {
						echo '<input type="hidden" name="'.key($_REQUEST).'" value="'.$_REQUEST[key($_REQUEST)].'">';
					}
					next($_REQUEST);
				}
				?>
				<br>
				<table align="center" cellspacing="4" cellpadding="12" bgcolor="<? echo BG_DEFAULT; ?>" border="0" style="background-color: <? echo BG_DEFAULT; ?>; box-shadow: 12px 10px 14px #777; border: 1px solid #bbbbbb; background: linear-gradient(<? echo BG_GLEATTRIBUTE; ?> 0%, <? echo BG_DEFAULT ?> 100%);">
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
									<td align="center" colspan="2"><span class="fett red">Anmeldung nicht erfolgreich.</span><br><br></td>
								</tr><?php														
					}								
			        ?><tr>
									<td><span class="px16">Nutzername: </span></td>
  								<td><input style="width: 130px" type="text" value="<? echo $username; ?>" name="username"/></td>
								</tr>
								<tr>
									<td><span class="px16">Passwort: </span></td>
									<td><input style="width: 130px" type="password" value="<? echo $passwort; ?>" name="passwort" /></td>
								</tr><?php
					if (isset($newPassword) AND $newPassword!='') {
						  ?><tr>
									<td width="400" colspan="2"><span class="red"><?php echo urldecode($msg); ?></span></td>
								</tr>
								<tr>
									<td><span class="px16">Neues Passwort: </span></td>
									<td><input style="width: 130px" type="password" name="newPassword"/></td>
								</tr>
								<tr>
									<td><span class="px16">Wiederholung: </span></td>
									<td><input style="width: 130px" type="password" name="newPassword2"/></td>
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
									<td colspan="2" align="center"><input type="button" name="anmelden" onclick="logon();" value="Anmelden"/></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<input type="hidden" name="browserwidth">
				<input type="hidden" name="browserheight">
			</form>			
		 </body>
		</html><?php
		exit;
	}
?>