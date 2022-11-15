<?php
  include(LAYOUTPATH . 'languages/login_' . ($this->user->language ?: 'german') . '.php');
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de"><?php
	include(SNIPPETS . 'login_head.php'); ?>
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
								<td align="center" colspan="2" style="padding-bottom: 15px"><h1><?php echo TITLE . ' ' . $strLogin; ?></h1></td>
							</tr><?
							if ($this->login_failed) { ?>
								<tr>
									<td align="center" colspan="2" style="padding: 10 0 5 0;"><span id="login_unsuccessfull" class="fett red"><? echo $strLoginFailed; ?></span></td>
								</tr><?
							} ?>
							<tr>
								<td><span class="px16"><? echo $strUserName; ?>:</span></td>
								<td><input id="login_name" style="width: 130px; height: 25px;" type="text" value="<? echo htmlspecialchars($this->formvars['login_name']); ?>" name="login_name" autocomplete="off"/></td>
							</tr>
							<tr>
								<td><span class="px16"><? echo $strPassword; ?>:</span></td>
								<td><input style="width: 130px; height: 25px;" type="password" value="" id="passwort" name="passwort" autocomplete="off"/><i style="margin-left: -18px" class="fa fa-eye-slash" aria-hidden="true" onclick="$(this).toggleClass('fa-eye fa-eye-slash'); if ($('#passwort').attr('type') == 'text') { $('#passwort').attr('type', 'password') } else { $('#passwort').attr('type', 'text'); }"></i></td>
							</tr><?
							if ($this->login_failed_reason != '') {
								switch ($this->login_failed_reason) {
									case 'authentication' : {
										$this->add_message('error', sprintf($strLoginFailedMsg['authentication'], $this->formvars['num_failed']));
									} break;
									case 'login_is_locked' : {
										$this->add_message('error', sprintf($strLoginFailedMsg['login_is_locked'], (new DateTime($this->user->login_locked_until))->format('d.m.Y H:i:s')));
									} break;
									default : {
										$this->add_message('error', $strLoginFailedMsg[$this->login_reason]);
									}
								}
							}
							if (property_exists($this->user, 'login_locked_until')) {
								$login_lock_duration = strtotime((new DateTime($this->user->login_locked_until))->format('Y-m-d H:i:s')) - time();
								if ($login_lock_duration > 0) { ?>
									<script>
										var login_locked_for = <? echo $login_lock_duration; ?>;
										$('#login_name, #passwort').prop('disabled', true);
										$('#login_locked_for').html(format_duration(login_locked_for));
										function count_down(seconds) {
											$('#login_locked_for').html(format_duration(seconds));
											if (seconds > 0) {
												setTimeout(count_down, 1000, --seconds);
											}
											else {
												$('#login_name, #passwort').prop('disabled', false);
												$('#login_locked_for').html('jetzt');
												$('#message_box, #login_unsuccessfull').hide().html('');
											}
										}
										count_down(login_locked_for);
									</script>
									<tr>
										<td><span class="px16"><? echo $strLoginLockedUntil; ?>:</span></td>
										<td><span class="fett red" id="login_locked_for"><? echo (new DateTime($this->user->login_locked_until))->format('m.d.Y H:i:s'); ?></span></td>
									</tr><?
								}
							} ?>
							<tr>
								<td><span class="px16"><? echo $strYourIpAddress; ?>:</span></td>
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
