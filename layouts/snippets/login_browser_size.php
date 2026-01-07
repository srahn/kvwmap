<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
	<? include(SNIPPETS . 'login_head.php'); ?>
	<body onload="logon();">
		<form name="login" action="index.php" method="post">
			<input type="hidden" name="browserwidth">
			<input type="hidden" name="browserheight"><?
			echo hidden_formvars_fields($this->formvars, ['browserwidth', 'browserheight', 'go']); ?>
			<table align="center" cellspacing="4" cellpadding="22" id="login_table">
				<tr>
					<td align="center"><h1>Bitte warten...</h1></td>
				</tr>
			</table>
		</form>
	</body>
	</html>
