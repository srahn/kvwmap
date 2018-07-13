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
			<table align="center" cellspacing="4" cellpadding="12" border="0" style="box-shadow: 12px 10px 14px #777; border: 1px solid #bbbbbb;">
				<tr>
					<td align="center">
						<table cellspacing="0" cellpadding="2" border="0">
							<tr>
								<td align="center" colspan="2"><h1 style="margin-bottom: 20px;"><?php echo $this->title; ?></h1></td>
							</tr>
							<tr>
							<tr>
								<td colspan="2" align="center">
									<div>
										<? include(SNIPPETS.AGREEMENT_MESSAGE); ?>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2" align="center"><input id="agreement_accepted" type="checkbox" name="agreement_accepted" value="1"/> Ich habe die Vereinbarung verstanden und stimme zu</td>
							</tr>
							<tr>
								<td colspan="2" align="center"><input id="absenden" type="button" name="absenden" onclick="logon();" value="Absenden"/></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>
	 </body>
</html>
