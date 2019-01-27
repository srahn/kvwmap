<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
	<? include(SNIPPETS . 'login_head.php'); ?>
	<body style="font-family: Arial, Verdana, Helvetica, sans-serif" onload="load()">
		<form name="login" action="index.php" method="post">
			<div id="message_box" class="message_box_hidden"></div>		<!-- muss innerhalb des form stehen -->
			<input type="hidden" name="browserwidth">
			<input type="hidden" name="browserheight"><?
			echo hidden_formvars_fields($this->formvars, $this->expect); ?>
			<table align="center" cellspacing="4" cellpadding="12" border="0" id="login_table">
				<tr>
					<td align="center">
						<table cellspacing="0" cellpadding="2" border="0">
							<tr>
								<td align="center" colspan="2" style="padding-bottom: 15px"><h1><?php echo $this->title; ?></h1></td>
							</tr><?
							if ($this->login_failed) { ?>
								<tr>
									<td align="center" colspan="2" style="padding: 10 0 5 0;"><span class="fett red">Anmeldung nicht erfolgreich.</span></td>
								</tr><?
							} ?>
							<tr>
								<td><span class="px16">Nutzername: </span></td>
								<td><input id="login_name" style="width: 130px" type="text" value="<? echo $this->formvars['login_name']; ?>" name="login_name"/></td>
							</tr>
							<tr>
								<td><span class="px16">Passwort: </span></td>
								<td><input style="width: 130px;" type="password" value="<? echo $this->formvars['passwort']; ?>" name="passwort" /></td>
							</tr>
							<tr>
								<td colspan="2">Ihre IP-Adresse: <?php echo   $remote_addr = getenv('REMOTE_ADDR'); ?></td>
							</tr>
							<tr>
								<td colspan="2" align="center"><input id="anmelden" type="button" name="anmelden" onclick="logon();" value="Anmelden"/></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>
	 </body>
</html>
