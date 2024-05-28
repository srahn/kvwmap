<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
	<? include(SNIPPETS . 'login_head.php'); ?>
	<body style="font-family: Arial, Verdana, Helvetica, sans-serif" onload="load()">
		<form name="login" action="index.php" method="post">
			<input type="hidden" name="agreement"><?
			echo hidden_formvars_fields($this->formvars, $this->expect); ?>
			<table align="center" cellspacing="4" cellpadding="12" border="0" style="box-shadow: 12px 10px 14px #777; border: 1px solid #bbbbbb;">
				<tr>
					<td align="center">
						<table cellspacing="0" cellpadding="2" border="0">
							<tr>
								<td colspan="2">
									<div>
										<? include(AGREEMENT_MESSAGE); ?>
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
