<?
 # 2008-01-12 pkvvm
  include(LAYOUTPATH . 'languages/userdaten_formular_' . $this->user->rolle->language . '.php');
?>
<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<script type="text/javascript">

	var password_check = '<? echo PASSWORD_CHECK; ?>';
	var password_minlength = <? echo PASSWORD_MINLENGTH; ?>;

	function nutzer_aendern(){
		if (document.GUI.admin_user.value != 1 && document.GUI.selectedstellen.length == 0){
			if (!window.confirm("Wenn Sie dem Nutzer keine Stellen zuordnen, können Sie ihn anschließend nicht mehr bearbeiten. Wirklich Fortfahren?")) {
				return;
			}
		}
		submitWithValue('GUI','go_plus','Ändern');
	}

	function toggle_password() {
		document.getElementById('udf_changepasswd').classList.value;
		if (document.getElementById('udf_changepasswd').classList.length == 0) {
			$('#password_helper').show();
			$('#change_password_text').html('<? echo $strTakeOverPassword; ?>');
			document.getElementById('udf_changepasswd').classList.add('udf_pw-active');
			document.GUI.password1.disabled = false;
			document.GUI.password2.disabled = false;
			document.GUI.changepasswd.value = 1;
		}
		else {
			$('#password_helper').hide();
			$('#change_password_text').html('<? echo $strChangePassword; ?>');
			document.getElementById('udf_changepasswd').classList.remove('udf_pw-active');
			document.GUI.password1.disabled = true;
			document.GUI.password2.disabled = true;
			document.GUI.changepasswd.value = 0;
		}
	}

	function deactivate_layer(user_id, stelle_id, layer_id) {
		ahah('index.php?go=Benutzerdaten_Layer_Deaktivieren', 'user_id=' + user_id + '&stelle_id=' + stelle_id + '&layer_id=' + layer_id, new Array(), new Array());
		layer = document.getElementById('layer_'+layer_id);
		layer.parentNode.removeChild(layer);
	}

	function resetPassword() {
		var newPassword = getRandomPassword(),
		loginName = $('form[name="GUI"] input[name="loginname"]').val(),
		host = window.location.href.split('?')[0];
		$('#resetPassword').attr(
			'href',
			'mailto:' + $('form[name="GUI"] input[name="email"]').val() + '?subject=<? echo $strInvitationSubject; ?>%20<? echo (TITLE == '' ? 'kvwmap' : TITLE); ?>&body=<? echo $strInvitationBody1; ?>%20' + loginName + ',%0A%0A<? echo $strInvitationBody2; ?>.%0A%0A<? echo $strInvitationBody3; ?>:%0A' + host + '%3Fgo=logout%26login_name=' + loginName + '%26passwort=' + newPassword + '%0A<? echo $strInvitationBody4; ?>.%0A%0A<? echo $strInvitationBody5; ?>%0A<? echo $strInvitationBody6; ?>%0A'
		);
		message('<span style="font-size: larger;"><? echo $strInvitationConfirmation1; ?></span><br><br><? echo $strInvitationConfirmation2; ?>.<br><span style="color: red"><? echo $strInvitationConfirmation3; ?>!</span>', 1000, 2000, '', '<? echo $strInvitationConfirmation4; ?>');
		$('<input>').attr({
			type: 'hidden',
			name: 'password_setting_time',
			value: '<? echo date('Y-m-d', time() - (60 * 60 * 24 * 31 * ($this->Stelle->allowedPasswordAge > 0 ? $this->Stelle->allowedPasswordAge : 1))); ?>'
		}).appendTo('#GUI');
		$('#GUI input[name=password1]').prop('disabled', false).val(newPassword);
		$('#GUI input[name=password2]').prop('disabled', false).val(newPassword);
		document.GUI.changepasswd.value = 1;
	}
	
</script>

<style>
	.form_formular-input > div:nth-child(2) input[type="text"]  {
		width: 300px;
	}
	.form_formular-input > div:nth-child(2) .fa-envelope {
		margin-left: 10px;
	}
	.form_formular-input > div:nth-child(2) span[data-tooltip] {
		margin-left: 10px;
	}
	.udf_eingabe-time {
		margin-bottom: 15px;
	}
	.udf_eingabe-time span:nth-child(2) {
		margin-left: 10px;
	}
	.udf_eingabe-pw {
		text-align: left;
	}
	.udf_eingabe-pw-input input[type="password"] {
		border-radius: 2px;
		border: 1px solid #777;
		padding-left: 5px;
		height: 25px;
		width: 180px;
	}
	.udf_eingabe-pw-input input[type="password"]:first-child {
		margin-bottom: 5px;
	}
	.udf_eingabe-pw-input input[type="text"] {
		border-radius: 2px;
		border: 1px solid #777;
		padding-left: 5px;
		height: 25px;
		width: 180px;
	}
	.udf_eingabe-pw-input input[type="text"]:first-child {
		margin-bottom: 5px;
	}

	.udf_eingabe-pw-change {
		margin: 10px 0px;
		display: flex;
	}
	.udf_eingabe-pw-change div:first-child {
		margin-right: 100px;
	}
	.udf_eingabe-pw-change .fa {
		margin-right: 5px;
	}
	#udf_changepasswd, #resetPassword, .udf_eingabe-pw-change .fa {
		color: firebrick;
		cursor: pointer;
	}
	.udf_eingabe-stelle {
		margin-right: 20px;
	}
	.udf_eingabe-stelle table select {
		width: 300px;
	}
	.udf_eingabe-stelle-title {
		margin-top: 3px;
	}
	.udf_eingabe-stelle table, .udf_eingabe-layers table {
	    border-spacing: 0px;
	}
	.udf_eingabe-layers table td {
	    padding: 2px;
	}
	.udf_eingabe-layers table td:nth-child(2) {
	    padding: 0px 10px;
	}
	.udf_eingabe-layers .fa-times {
	    color: firebrick;
	}
	#udf_submit {
		margin-bottom: 30px;
	}
	.udf_back {
		text-align: right;
		right: 20px;
		position: relative;
		bottom: 10px;
	}
</style>

<div id="form-titel"><? echo $strTitle; ?></div>

<div class="udf_back"><?
	if ($this->formvars['nutzerstellen']) { ?>
		<a href="index.php?go=BenutzerStellen_Anzeigen#<? echo $this->formvars['nutzerstellen'].'user'.$this->formvars['selected_user_id']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>">&raquo;&nbsp;<? echo $this->strButtonBack; ?></a><?
	} ?>
</div>

<? $selstellen = (is_array($this->formvars['selstellen']) AND array_key_exists('ID', $this->formvars['selstellen']) ? implode(', ', $this->formvars['selstellen']["ID"]) : ''); ?>
<div id="form_formular-main">
	<input type="hidden" name="go" value="Benutzerdaten">
	<input type="hidden" name="selstellen" value="<? echo $selstellen; ?>"><?
	if ($this->Fehlermeldung != '') {
		include(LAYOUTPATH."snippets/Fehlermeldung.php");
	} ?>
	<div id="udf-wrapper"><?
		if ($this->formvars['selected_user_id'] > 0) { ?>
			<div class="form_formular-input form_formular-aic">
				<div><? echo $strDataBankID;?></div>
				<div><input name="id" type="text" value="<? echo $this->formvars['selected_user_id']; ?>" maxlength="15"></div>
			</div><?
		} ?>
		<div class="form_formular-input form_formular-aic">
			<div><? echo $strName;?>&nbsp;*</div>
			<div><input name="nachname" type="text" value="<? echo $this->formvars['nachname']; ?>" maxlength="100"></div>
		</div>

		<div class="form_formular-input form_formular-aic">
			<div><? echo $strUserTitle;?></div>
			<div><input name="Namenszusatz" type="text" value="<? echo $this->formvars['Namenszusatz']; ?>" maxlength="50"></div>
		</div>

		<div class="form_formular-input form_formular-aic">
			<div><? echo $strForeName;?></div>
			<div><input name="vorname" type="text" value="<? echo $this->formvars['vorname']; ?>" maxlength="100"></div>
		</div>

		<div class="form_formular-input form_formular-aic">
			<div><? echo $strLogInName;?>&nbsp;*</div>
			<div><input name="loginname" readonly onfocus="this.removeAttribute('readonly');" type="text" value="<? echo $this->formvars['loginname']; ?>" maxlength="100"></div>
		</div>

		<div class="form_formular-input form_formular-aic">
			<div><? echo $this->strTelephone;?></div>
			<div><input name="phon" type="text" value="<? echo $this->formvars['phon']; ?>" maxlength="25"></div>
		</div>

		<div class="form_formular-input form_formular-aic">
			<div><? echo $this->strEmail;?></div>
			<div>
				<input name="email" type="text" value="<? echo $this->formvars['email']; ?>" maxlength="100">
				<a href="mailto:<? echo $this->formvars['email']; ?>" title="<? echo $strSendEmail; ?>"><i class="fa fa-envelope" aria-hidden="true"></i></a>
			</div>
		</div>

		<div class="form_formular-input form_formular-aic">
			<div><? echo $strOrganisation;?></div>
			<div><input name="organisation" type="text" value="<? echo $this->formvars['organisation']; ?>" maxlength="255"></div>
		</div>

		<div class="form_formular-input form_formular-aic">
			<div><? echo $strPosition;?></div>
			<div><input name="position" type="text" value="<? echo $this->formvars['position']; ?>" maxlength="255"></div>
		</div>

		<div class="form_formular-input">
			<div><? echo $strPassword;?>&nbsp;*</div>
			<div class="udf_eingabe-pw"><?
				$passwordSettingUnixTime = strtotime($this->formvars['password_setting_time']);
				if ($this->formvars['selected_user_id'] > 0) { ?>
					<div class="udf_eingabe-time">
						<span><? echo $strLastModPassword;?>:&nbsp;<? echo date('d.m.Y', $passwordSettingUnixTime); ?></span><?
						if ($this->Stelle->checkPasswordAge) {
							$allowedPasswordAgeRemainDays = checkPasswordAge($this->formvars['password_setting_time'], $this->Stelle->allowedPasswordAge); ?>
							<span style="color: <? echo ($allowedPasswordAgeRemainDays < 0 ? '#f21515' : '#139513'); ?>"><?
								if ($allowedPasswordAgeRemainDays < 0) {
									echo $strPasswordExpired; ?>&nbsp;(<? echo $allowedPasswordAgeRemainDays * -1; ?>&nbsp;<? echo $strPasswordExpiredDays;?>)<?
								} else {
									echo $strPasswordValid;?>&nbsp;(<? echo $allowedPasswordAgeRemainDays; ?>&nbsp;<? echo $strPasswordExpiredDays;?>)<?
								}
							} ?>
						</span>
					</div><?
				} ?>
				<div class="udf_eingabe-pw-input">
					<div>
						<input
							id="new_password"
							name="password1"
							readonly
							onfocus="this.removeAttribute('readonly');"
							<? echo ($this->formvars['selected_user_id'] > 0 ? 'disabled="true"' : ''); ?>
							type="password"
							maxlength="30"
							placeholder="<? echo $strNewPassword; ?>"
						>
						<span id="password_helper" style="display: <? echo ($this->formvars['selected_user_id'] == '' ? 'inline' : 'none'); ?>">
							<i title="<? echo $strGenerateSecurePassword; ?>" class="fa fa-random pointer" aria-hidden="true" style="margin-left: 5px" onclick="$('#new_password, #new_password_2').val(getRandomPassword())"></i>
							<i title="<? echo $strPasswordVisiblitiy; ?>" style="margin-left: 5px;" class="fa fa-eye-slash pointer" aria-hidden="true" onclick="togglePasswordVisibility(this, 'new_password', 'new_password_2')"></i>
							<i title="<? echo $strCopyPasswordToClipboard; ?>" style="margin-left: 5px;" class="fa fa-clipboard pointer" aria-hidden="true" onclick="copyToClipboard($('#new_password').val())"></i>
						</span>
					</div>
					<div>
						<input
							id="new_password_2"
							name="password2"
							readonly
							onfocus="this.removeAttribute('readonly');"
							<? echo ($this->formvars['selected_user_id'] > 0 ? 'disabled="true"' : ''); ?>
							type="password"
							maxlength="30"
							placeholder="<? echo $strReEnterPassword; ?>"
						>
					</div>
				</div><?
				if ($this->formvars['selected_user_id'] > 0) { ?>
					<div class="udf_eingabe-pw-change">
						<div id="udf_changepasswd" class="" onclick="toggle_password();">
							<input name="changepasswd" type="hidden" value="">
							<i class="fa fa-pencil" aria-hidden="true"></i><span id="change_password_text"><? echo $strChangePassword;?></span>
						</div>

						<div>
							<i class="fa fa-undo" aria-hidden="true"></i>
							<a href="javascript:void(0);" id="resetPassword" onclick="resetPassword();"><? echo $strResetPassword; ?></a>
						</div>
					</div><?
				} ?>
			</div>
		</div>

		<div class="form_formular-input form_formular-aic">
			<div><? echo $strAllowedIps;?></div>
			<div>
				<input name="ips" type="text" value="<? echo $this->formvars['ips']; ?>">
				<span data-tooltip="Mehrere Einträge mit Semikolon trennen"></span>
			</div>
		</div>

		<div class="form_formular-input form_formular-aic">
			<div><? echo $strStart;?></div>
			<div><input name="start" type="text" value="<? echo $this->formvars['start']; ?>"></div>
		</div>

		<div class="form_formular-input form_formular-aic">
			<div><? echo $strStop;?></div>
			<div><input name="stop" type="text" value="<? echo $this->formvars['stop']; ?>"></div>
		</div>

		<div class="form_formular-input">
			<div class="udf_eingabe-stelle-title"><? echo $strAuthorizeTask;?></div>
			<div class="udf_eingabe-stelle">
				<table>
					<tr>
						<td>
							<select name="selectedstellen" style="height: auto;" size="5" multiple>
								<? 
								for ($i=0; $i < count($this->formvars['selstellen']["Bezeichnung"]); $i++) {
									echo '<option value="'.$this->formvars['selstellen']["ID"][$i].'" title="'.$this->formvars['selstellen']["Bezeichnung"][$i].'">'.$this->formvars['selstellen']["Bezeichnung"][$i].'</option>';
								}
								?>
							</select>
						</td>
						<td class="fsf_suche_form_add">
							<div><input type="button" name="addPlaces" value="&laquo;" onClick="addOptions(document.GUI.allstellen,document.GUI.selectedstellen,document.GUI.selstellen,'value');"></div>
							<div><input type="button" name="substractPlaces" value="&raquo;" onClick="substractOptions(document.GUI.selectedstellen,document.GUI.selstellen,'value');"></div>
						</td>
						<td>
							<select name="allstellen" style="height: auto;" size="5" multiple>
								<? 
								for ($i=0; $i < count($this->formvars['stellen']["Bezeichnung"]); $i++) {
									echo '<option value="'.$this->formvars['stellen']["ID"][$i].'" title="'.$this->formvars['stellen']["Bezeichnung"][$i].'">'.$this->formvars['stellen']["Bezeichnung"][$i].'</option>';
								}
								?>
							</select>
						</td>
					</tr>
				</table>
			</div>
		</div>
		
		<div class="form_formular-input form_formular-aic">
			<div><? echo $strShareRollenlayerAllowedLabel; ?></div>
			<div><input
							name="share_rollenlayer_allowed"
							type="checkbox"
							value="1"
							<?php echo ($this->formvars['share_rollenlayer_allowed'] ? 'checked' : ''); ?>
						><?php echo $strShareRollenlayerAllowedCheckboxText; ?>
						<span data-tooltip="<?php echo $strShareRollenlayerAllowedDescription; ?>"></span>
			</div>
		</div>
		
		<div class="form_formular-input form_formular-aic">
			<div><? echo $strLayerDataImportAllowedLabel; ?></div>
			<div><input
							name="layer_data_import_allowed"
							type="checkbox"
							value="1"
							<?php echo ($this->formvars['layer_data_import_allowed'] ? 'checked' : ''); ?>
						><?php echo $strLayerDataImportAllowedCheckboxText; ?>
			</div>
		</div>
		
		<?
	if ($this->formvars['selected_user_id'] > 0) {
		if (is_array($this->formvars['selstellen'])) {
			$active_stelle = array_search($this->userdaten[0]['stelle_id'], $this->formvars['selstellen']["ID"]);
			$active_stelle_bezeichnung = $this->formvars['selstellen']['Bezeichnung'][$active_stelle];
		} ?>
		<div class="form_formular-input form_formular-aic">
			<div><? echo $strActiveSite;?></div>
			<div>
				<a href="index.php?go=Stelleneditor&selected_stelle_id=<? echo $this->userdaten[0]['stelle_id']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><? echo $active_stelle_bezeichnung; ?></a>
			</div>
		</div>

		<div class="form_formular-input">
			<div><? echo $strActiveLayers;?></div>
			<div class="udf_eingabe-layers">
				<table><?	
					for ($i = 0; $i < count($this->active_layers); $i++) { ?>
						<tr id="layer_<? echo $this->active_layers[$i]['Layer_ID']; ?>" class="tr_hover">
							<td>
								<? echo $this->active_layers[$i]['alias']; ?>
							</td>
							<td>
								<a title="deaktivieren" href="javascript:deactivate_layer('<? echo $this->formvars['selected_user_id']; ?>', '<? echo $this->userdaten[0]['stelle_id']; ?>', '<? echo $this->active_layers[$i]['Layer_ID']; ?>');"><i class="fa fa-times" aria-hidden="true"></i></a>
							</td>
						</tr><?
					} ?>
				</table>
			</div>
		</div>

		<div class="form_formular-input form_formular-aic">
			<div><? echo $strChangeUser;?></div>
			<div>
				<a href="index.php?go=als_nutzer_anmelden&loginname=<? echo $this->formvars['loginname']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><? echo $strLoginAsUser; ?></a>
			</div>
		</div><?
		} ?>
	</div>
</div>

<div id="udf_submit">
	<input type="hidden" name="go_plus" id="go_plus" value="">
	<input type="hidden" name="admin_user" value="<? echo $this->admin_user; ?>"><?
	if ($this->formvars['selected_user_id'] > 0) { ?>
		<input type="hidden" name="selected_user_id" value="<? echo $this->formvars['selected_user_id']; ?>">
		<input type="button" name="dummy" value="<? echo $this->strSave; ?>" onclick="nutzer_aendern()"><?
	} ?>
	<input type="button" name="dummy" value="<? echo $strButtonSaveAs; ?>" onclick="submitWithValue('GUI','go_plus','Als neuen Nutzer eintragen');">	
</div>