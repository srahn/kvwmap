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
								<td align="center" colspan="2" style="padding-bottom: 15px"><h1><?php echo TITLE . ' Anmeldung'; ?></h1></td>
							</tr><?
							if ($this->login_failed) { ?>
								<tr>
									<td align="center" colspan="2" style="padding: 10 0 5 0;"><span class="fett red">Anmeldung nicht erfolgreich.</span></td>
								</tr><?
							} ?>
							<tr>
								<td><span class="px16">Nutzername:</span></td>
								<td><input id="login_name" style="width: 130px; height: 25px;" type="text" value="<? echo htmlspecialchars($this->formvars['login_name']); ?>" name="login_name" autocomplete="off"/></td>
							</tr>
							<tr>
								<td><span class="px16">Passwort:</span></td>
								<td><input style="width: 130px; height: 25px;" type="password" value="" id="passwort" name="passwort" autocomplete="off"/><i style="margin-left: -18px" class="fa fa-eye-slash" aria-hidden="true" onclick="$(this).toggleClass('fa-eye fa-eye-slash'); if ($('#passwort').attr('type') == 'text') { $('#passwort').attr('type', 'password') } else { $('#passwort').attr('type', 'text'); }"></i></td>
							</tr>
							<tr>
								<td><span class="px16">Ihre IP-Adresse:</span></td>
								<td><?php echo $remote_addr = get_remote_ip(); ?></td>
							</tr>
							<tr>
								<td colspan="2" align="center"><input id="anmelden" style="margin-top: 12px;" type="button" name="anmelden" onclick="logon();" value="Anmelden"/></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>
	 </body>
</html>
