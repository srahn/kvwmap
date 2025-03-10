<?php
	$language = (in_array($_REQUEST['language'], array('german', 'english', 'low-german', 'polish', 'vietnamese')) ? $_REQUEST['language'] : 'german');
	include_once(LAYOUTPATH . 'languages/' . $language . '.php');
	include_once(LAYOUTPATH . 'languages/login_registration_' . $language . '.php');
	$bezeichnung_attribute = 'Bezeichnung' . ($language != 'german' ? '_' . $language : '');
?><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
	<? include(SNIPPETS . 'login_head.php'); ?>
	<body style="font-family: Arial, Verdana, Helvetica, sans-serif" onload="load()">
		<form name="login" action="index.php" method="post">
			<div id="message_box"></div> <!-- muss innerhalb des form stehen -->
			<input type="hidden" name="browserwidth">
			<input type="hidden" name="browserheight"><?
			echo hidden_formvars_fields($this->formvars, $this->expect);
			 ?>
			<table align="center" cellspacing="4" cellpadding="12" id="login_table" style="width: 400px">
				<tr>
					<td align="center">
						<table cellspacing="0" cellpadding="2" border="0">
							<tr>
								<td align="center" colspan="2"><h1 style="margin-bottom: 20px;"><?php echo TITLE . ' ' . $strRegistration; ?></h1></td>
							</tr>
							<tr>
								<td colspan="2"><? echo $strWelcomRegistration . ' ' . TITLE; ?>.
									<p>
									<b><? echo $this->invitation->inviter->get('Vorname') . ' ' . $this->invitation->inviter->get('Name'); ?><b> <?php echo $strInvitationToTask; ?>: <? echo $this->invitation->stelle->get($bezeichnung_attribute); ?><br>
									<?php echo $strNameAndContact; ?>
									<p>
									<? echo password_erstellungs_hinweis($language); ?>
									<p>
									<? echo $strWorkWith; ?>
									<p>
									<? echo $strRequests; ?> <?
									if ($this->invitation->inviter->get('email') != '') { ?>
										<? echo $strPerEMail; ?> <a href="mailto:<? echo $this->invitation->inviter->get('email'); ?>"><?
										echo $this->invitation->inviter->get('Vorname') . ' ' . $this->invitation->inviter->get('Name'); ?></a><?
									}
									if ($this->invitation->inviter->get('phon') != '') { ?>
										<? echo $strOrCall; ?> <? echo $this->invitation->inviter->get('phon') . '.';
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
								<td><span class="px16"><? echo $this->strName; ?>: *</span></td>
								<td>
									<input style="width: 200px" type="text" value="<? echo $this->formvars['Name']; ?>" name="Name" />
								</td>
							</tr>
							<tr>
								<td><span class="px16"><? echo $this->strForeName; ?>: </span></td>
								<td>
									<input style="width: 200px" type="text" value="<? echo $this->formvars['Vorname']; ?>" name="Vorname" />
								</td>
							</tr>
							<tr>
								<td><span class="px16"><? echo $this->strNameSuffix; ?>: </span></td>
								<td>
									<input style="width: 200px" type="text" value="<? echo $this->formvars['Namenszusatz']; ?>" name="Namenszusatz" />
								</td>
							</tr>
							<tr>
								<td><span class="px16"><? echo $strUserName; ?>: *</span></td>
								<td>
									<input style="width: 200px" type="text" value="<? echo $this->formvars['login_name']; ?>" name="login_name" autocomplete="off"/>
								</td>
							</tr>
							<tr>
								<td><span class="px16"><? echo $strNewPassword; ?>: *</span></td>
								<td><input style="width: 200px" type="password" value="<? echo $this->formvars['new_password']; ?>" id="new_password" name="new_password" autocomplete="off"/><i style="margin-left: -18px" class="fa fa-eye-slash" aria-hidden="true" onclick="$(this).toggleClass('fa-eye fa-eye-slash'); if ($('#new_password').attr('type') == 'text') { $('#new_password').attr('type', 'password') } else { $('#new_password').attr('type', 'text'); }"></i></td>
							</tr>
							<tr>
								<td><span class="px16"><? echo $strNewPassword2; ?>: *</span></td>
								<td><input style="width: 200px" type="password" value="<? echo $this->formvars['new_password_2']; ?>" id="new_password_2" name="new_password_2" autocomplete="off"/><i style="margin-left: -18px" class="fa fa-eye-slash" aria-hidden="true" onclick="$(this).toggleClass('fa-eye fa-eye-slash'); if ($('#new_password_2').attr('type') == 'text') { $('#new_password_2').attr('type', 'password') } else { $('#new_password_2').attr('type', 'text'); }"></i></td>
							</tr>
							<tr>
								<td><span class="px16"><? echo $strPhonNumber; ?>: </span></td>
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
