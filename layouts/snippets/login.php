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
			<div id="message_box" class="message_box_hidden"></div>		<!-- muss innerhalb des form stehen -->
			<input type="hidden" name="browserwidth">
			<input type="hidden" name="browserheight"><?
			echo hidden_formvars_fields($this->formvars, $this->expect); ?>
			<table align="center" cellspacing="4" cellpadding="12" bgcolor="<? echo BG_DEFAULT; ?>" border="0" style="margin-top: 20px; background-color: <? echo BG_DEFAULT; ?>; box-shadow: 12px 10px 14px #777; border: 1px solid #bbbbbb; background: linear-gradient(<? echo BG_GLEATTRIBUTE; ?> 0%, <? echo BG_DEFAULT ?> 100%);">
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
							</tr><?
							if (MOBILE == 'true') { ?>
								<tr>
								 <td>mobil:</td>
								 <td><input type="checkbox" value="on" name="mobile"/></td>
								</tr><?php
							} ?>
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
