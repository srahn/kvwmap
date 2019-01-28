<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
	<? include(SNIPPETS . 'login_head.php'); ?>
	<body style="font-family: Arial, Verdana, Helvetica, sans-serif" onload="load()">
		<form name="login" action="index.php" method="post">
			<div id="message_box"></div> <!-- muss innerhalb des form stehen -->
			<input type="hidden" name="browserwidth">
			<input type="hidden" name="browserheight"><?
			echo hidden_formvars_fields($this->formvars, $this->expect); ?>
			<table align="center" cellspacing="4" cellpadding="12" id="login_table" style="width: 400px">
				<tr>
					<td align="center">
						<table cellspacing="0" cellpadding="2" border="0">
							<tr>
								<td align="center" colspan="2"><h1 style="margin-bottom: 20px;"><?php echo TITLE . ' Registrierung'; ?></h1></td>
							</tr>
							<tr>
								<td colspan="2">Herzlich Willkommen zur Registrierung am <? echo TITLE; ?>.
									<p>
									<? echo $this->invitation->inviter->get('Vorname') . ' ' . $this->invitation->inviter->get('Name'); ?> hat Sie zur Mitarbeit in der Arbeitsstelle <? echo $this->invitation->stelle->get('bezeichnung'); ?> eingeladen.<br>
									Vergeben Sie bitte Nutzername und Passwort und passen Sie ggf. Ihre Kontaktdaten an.
									<p>
									<? echo password_erstellungs_hinweis(array('strAnd' => 'und')); ?>
									<p>
									Nach erfolgreicher Registrierung können Sie direkt in der Web-Anwendung <? echo TITLE; ?> arbeiten.
									<p>
									Für Rückfragen wenden Sie sich bitte <?
									if ($this->invitation->inviter->get('email') != '') { ?>
										per E-Mail an <a href="mailto:<? echo $this->invitation->inviter->get('email'); ?>"><?
										echo $this->invitation->inviter->get('Vorname') . ' ' . $this->invitation->inviter->get('Name'); ?></a><?
									}
									if ($this->invitation->inviter->get('phon') != '') { ?>
										oder rufen an unter der Telefonnummer <? echo $this->invitation->inviter->get('phon') . '.';
									} ?>
								</td>
							</tr><?
							if ($this->Fehlermeldung) { ?>
								<tr>
									<td colspan="2" style="color: red;">
										<? echo $this->Fehlermeldung; ?>
									</td>
								</tr><?
							} ?>
							<tr>
								<td><span class="px16">Name: *</span></td>
								<td>
									<input style="width: 200px" type="text" value="<? echo $this->formvars['Name']; ?>" name="Name" />
								</td>
							</tr>
							<tr>
								<td><span class="px16">Vorname: </span></td>
								<td>
									<input style="width: 200px" type="text" value="<? echo $this->formvars['Vorname']; ?>" name="Vorname" />
								</td>
							</tr>
							<tr>
								<td><span class="px16">Namenszusatz: </span></td>
								<td>
									<input style="width: 200px" type="text" value="<? echo $this->formvars['Namenszusatz']; ?>" name="Namenszusatz" />
								</td>
							</tr>
							<tr>
								<td><span class="px16">Nutzername: *</span></td>
								<td>
									<input style="width: 200px" type="text" value="<? echo $this->formvars['login_name']; ?>" name="login_name" />
								</td>
							</tr>
							<tr>
								<td><span class="px16">Neues Passwort: *</span></td>
								<td><input style="width: 200px" type="password" value="<? echo $this->formvars['new_password']; ?>" name="new_password"/></td>
							</tr>
							<tr>
								<td><span class="px16">Wiederholung: *</span></td>
								<td><input style="width: 200px" type="password" value="<? echo $this->formvars['new_password_2']; ?>" name="new_password_2"/></td>
							</tr>
							<tr>
								<td><span class="px16">Telefon: </span></td>
								<td>
									<input style="width: 200px" type="text" value="<? echo $this->formvars['phon']; ?>" name="phon" />
								</td>
							</tr>
							<tr>
								<td><span class="px16">E-Mail: </span></td>
								<td>
									<input style="width: 200px" type="text" value="<? echo $this->formvars['email']; ?>" name="email" />
								</td>
							</tr>
							<tr>
								<td colspan="2" align="center"><i>Felder mit * müssen ausgefüllt werden.</i></td>
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
