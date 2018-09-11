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
			<input type="hidden" name="browserwidth">
			<input type="hidden" name="browserheight"><?
			echo hidden_formvars_fields($this->formvars, $this->expect); ?>
			<table align="center" cellspacing="4" cellpadding="12" bgcolor="<? echo BG_DEFAULT; ?>" border="0" style="background-color: <? echo BG_DEFAULT; ?>; box-shadow: 12px 10px 14px #777; border: 1px solid #bbbbbb; background: linear-gradient(<? echo BG_GLEATTRIBUTE; ?> 0%, <? echo BG_DEFAULT ?> 100%);">
				<tr>
					<td align="center">
						<table cellspacing="0" cellpadding="2" border="0">
							<tr>
								<td align="center" colspan="2"><h1><?php echo $this->title; ?></h1></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td><span class="px16">Nutzername: </span></td>
								<td><? echo $this->formvars['login_name']; ?></td>
							</tr>
							<tr>
								<td><span class="px16">Passwort: </span></td>
								<td><input style="width: 130px" type="password" value="" name="passwort" /></td>
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
								<td><input style="width: 130px" type="password" value="<? echo $this->formvars['new_password']; ?>" name="new_password"/></td>
							</tr>
							<tr>
								<td><span class="px16">Wiederholung: </span></td>
								<td><input style="width: 130px" type="password" value="<? echo $this->formvars['new_password_2']; ?>" name="new_password_2"/></td>
							</tr>
							<tr>
								<td style="height: 40px" colspan="2">Ihre IP-Adresse: <?php echo   $remote_addr = getenv('REMOTE_ADDR'); ?></td>
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
