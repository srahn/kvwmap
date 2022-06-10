<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
	<? include(SNIPPETS . 'login_head.php'); ?>
	<body style="font-family: Arial, Verdana, Helvetica, sans-serif" onload="load()">
		<form name="login" action="index.php" method="post">
			<div id="message_box" class="message_box_hidden"></div>		<!-- muss innerhalb des form stehen -->
			<input type="hidden" value="<? echo htmlspecialchars($this->formvars['passwort']); ?>" name="passwort"/>
			<input type="hidden" name="browserwidth">
			<input type="hidden" name="browserheight"><?
			echo hidden_formvars_fields($this->formvars, $this->expect); ?>
			<style>
				.pointer {
					margin-left: 5px;
				}
				.pointer:hover {
					cursor: pointer;
				}
			</style>
			<table align="center" cellspacing="4" cellpadding="12" id="login_table">
				<tr>
					<td align="center">
						<table cellspacing="0" cellpadding="2" border="0">
							<tr>
								<td align="center" colspan="2"><h1><?php echo TITLE . ' Passwort Änderung'; ?></h1></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td><span class="px16">Nutzername: </span></td>
								<td><? echo $this->formvars['login_name']; ?></td>
							</tr><?
							if ($this->Fehlermeldung) { ?>
								<tr>
									<td colspan="2" style="color: red;">
										<? echo $this->Fehlermeldung; ?>
									</td>
								</tr><?
							} ?>
							<tr>
								<td><span class="px16">Neues Passwort: </span></td>
								<td>
									<div style="display: flex">
										<input style="width: 130px;" type="password" value="<? echo $this->formvars['new_password']; ?>" id="new_password" name="new_password" autocomplete="off"/>
										<?
											if (defined ('PASSWORD_INFO') AND PASSWORD_INFO != '') { ?>
												<div style="margin-left: 5px;">
													<span data-tooltip="<? echo PASSWORD_INFO; ?>"></span>
												</div><?php
											}
										?>
										<i title="Sicheres Passwort generieren" class="fa fa-random pointer" aria-hidden="true" style="margin-left: 5px" onclick="setRandomPassword()"></i>
										<i title="Sichtbarkeit des Passwortes" style="margin-left: 5px" class="fa fa-eye-slash pointer" aria-hidden="true" onclick="togglePasswordVisibility(this, 'new_password', 'new_password_2')"></i>
										<i title="Password in Zwischenablage kopieren" class="fa fa-clipboard pointer" aria-hidden="true" onclick="copyToClipboard($('#new_password').val())"></i>
									</div>
								</td>
							</tr>
							<tr>
								<td><span class="px16">Wiederholung: </span></td>
								<td><input style="width: 130px" type="password" value="<? echo $this->formvars['new_password_2']; ?>" id="new_password_2" name="new_password_2" autocomplete="off"/></td>
							</tr>
							<tr>
								<td style="height: 40px" colspan="2">Ihre IP-Adresse: <?php echo	 $remote_addr = getenv('REMOTE_ADDR'); ?></td>
							</tr>
							<tr>
								<td colspan="2" align="center"><input id="anmelden" type="button" name="anmelden" onclick="logon();" value="Absenden und Anmelden"/></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>
	 </body>
</html>
