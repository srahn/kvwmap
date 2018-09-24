<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
	<head>
		<title><?php echo $this->title; ?></title>
		<? include(SNIPPETS . 'gui_head.php'); ?>
		<script type="text/javascript">
			function load() {
				console.log('login form loaded.');
				$('input[name="browserwidth"]').val($(window).width());
				setTimeout(function() { $("input#login_name").focus(); }, 100);
			}

			function logon() {
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
	<body style="font-family: Arial, Verdana, Helvetica, sans-serif" onload="load()">
		<form name="login" action="index.php" method="post">
			<div id="message_box"></div> <!-- muss innerhalb des form stehen -->
			<input type="hidden" name="browserwidth">
			<input type="hidden" name="browserheight"><?
			echo hidden_formvars_fields($this->formvars, $this->expect); ?>
			<table align="center" cellspacing="4" cellpadding="12" bgcolor="<? echo BG_DEFAULT; ?>" border="0" style="background-color: <? echo BG_DEFAULT; ?>; box-shadow: 12px 10px 14px #777; border: 1px solid #bbbbbb; background: linear-gradient(<? echo BG_GLEATTRIBUTE; ?> 0%, <? echo BG_DEFAULT ?> 100%); width: 400px">
				<tr>
					<td align="center">
						<table cellspacing="0" cellpadding="2" border="0">
							<tr>
								<td align="center" colspan="2"><h1 style="margin-bottom: 20px;"><?php echo $this->title; ?></h1></td>
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
