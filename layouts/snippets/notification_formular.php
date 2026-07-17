<?php
include(LAYOUTPATH . 'languages/notifications_' . rolle::$language . '.php');
include_once(CLASSPATH . 'FormObject.php');
?>
<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<script>
	function gotoStelle(event, option_obj) {
		if (event.layerX > 300) {
			location.href = 'index.php?go=Stelleneditor&selected_stelle_id=' + option_obj.value + '&csrf_token=<? echo $_SESSION['csrf_token']; ?>';
		}
	}

	function gotoUser(event, option_obj) {
		if (event.layerX > 300) {
			location.href = 'index.php?go=Benutzerdaten_Formular&selected_user_id=' + option_obj.value + '&csrf_token=<? echo $_SESSION['csrf_token']; ?>';
		}
	}

	let formElem = document.getElementById('GUI');
	formElem.onsubmit = async (e) => {
		e.preventDefault();
		let formData = new FormData(formElem);
		formData.append('go', 'put_notification');
		let response = await fetch('index.php', {
				method: 'POST',
				body: formData
			})
			.then(response => response.text())
			.then(text => {
				try {
					const data = JSON.parse(text);
					if (data.success) {
						// ToDo pk: Load form with new notification
						window.location = 'index.php?go=notifications_anzeigen&csrf_token=<? echo $_SESSION['csrf_token']; ?>';
					} else {
						debug_data = data;
						if (typeof data.err_msg != 'undefined' && data.err_msg != '') {
							message([{
								'type': 'error',
								'msg': 'Fehler beim Speichern in der Datenbank: ' + text + ' ' + data.err_msg + ' sql: ' + data.sql
							}]);
						} else {
							data.validation_errors.forEach(
								(result) => {
									$('#form-submit-butto').show();
									show_validation_error(result);
								}
							);
						}
					}
				} catch (err) {
					message([{
						'type': 'error',
						'msg': err.name + ': ' + err.message + ' in Zeile: ' + err.lineNumber + ' Response: ' + text
					}]);
				}
			});
	};
</script>
<br>
<h2><?php echo ($this->notification->get('id') > 0 ? $strTitleEditNotification : $strTitleNewNotification); ?></h2>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
	<tr>
		<td colspan="2" align="center"><?php
			if ($this->Meldung == $strSuccess or $this->Meldung == '') {
				$bgcolor = BG_FORM;
			} else {
				$this->Fehlermeldung = $this->Meldung;
				include('Fehlermeldung.php');
				$bgcolor = BG_FORMFAIL;
			} ?>
		</td>
	</tr><?php
				if (!empty($this->notification->get('id'))) { ?>
		<tr>
			<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo 'ID'; ?></th>
			<td style="border-bottom:1px solid #C3C7C3">
				<input name="id" type="hidden" value="<?php echo $this->notification->get('id'); ?>">
				<? echo $this->notification->get('id'); ?>
			</td>
		</tr><?
				} ?>
	<tr>
		<th align="right" style="border-bottom:1px solid #C3C7C3"><? echo $strLabel_notification; ?>*</th>
		<td style="border-bottom:1px solid #C3C7C3">
			<textarea id="notification" name="notification" cols="80" rows="10" placeholder="<? echo $strPlaceholder_notification; ?>"><?	echo $this->notification->get('notification'); ?></textarea>
		</td>
	</tr>
	<tr>
		<th align="right" style="border-bottom:1px solid #C3C7C3"><? echo $strLabel_veroeffentlichungsdatum; ?> <span data-tooltip="<?php echo $strVeroeffentlichungsdatumHint; ?>"></span></th>
		<td style="border-bottom:1px solid #C3C7C3"><input id="veroeffentlichungsdatum" name="veroeffentlichungsdatum" type="text" value="<?php echo $this->notification->get('veroeffentlichungsdatum'); ?>" size="10" maxlength="10" placeholder="DD.MM.JJJJ"></td>
	</tr>
	<tr>
		<th align="right" style="border-bottom:1px solid #C3C7C3"><? echo $strLabel_ablaufdatum; ?>* <span data-tooltip="<?php echo $strAblaufdatumHint; ?>"></span></th>
		<td style="border-bottom:1px solid #C3C7C3"><input id="ablaufdatum" name="ablaufdatum" type="text" value="<?php echo $this->notification->get('ablaufdatum'); ?>" size="10" maxlength="10" placeholder="DD.MM.JJJJ"></td>
	</tr>
	<tr>
		<th align="right" style="border-bottom:1px solid #C3C7C3"><? echo $strLabel_stellen_filter; ?> <span data-tooltip="<?php echo $strStellenFilterHint; ?>"></span></th>
		<td style="border-bottom:1px solid #C3C7C3">
			<table>
				<tr valign="top">
					<td>
						<select name="selectedstellen" size="6" multiple style="position: relative; width: 340px">
							<?
							foreach ($this->notification->selstellen as $selstelle) { ?>
								<option class="select_option_link" onclick="gotoStelle(event, this)" title="<?php echo str_replace(' ', '&nbsp;', $selstelle->get('bezeichnung')); ?>" value="<?php echo $selstelle->get('id'); ?>"><?php echo $selstelle->get('bezeichnung'); ?></option>
							<?php
							} ?>
						</select>
						<input name="stellen_filter" value="<? echo $this->notification->get('stellen_filter'); ?>" type="hidden">
					</td>
					<td align="center" valign="middle" width="1">
						<input type="button" name="addStellen" value="&laquo;" onClick=addOptions(document.GUI.allstellen,document.GUI.selectedstellen,document.GUI.stellen_filter,'value')>
						<input type="button" name="substractStellen" value="&raquo;" onClick=substractOptions(document.GUI.selectedstellen,document.GUI.stellen_filter,'value')>
					</td>
					<td>
						<select name="allstellen" size="6" multiple style="position: relative; width: 340px"><?
							foreach ($this->allstellen as $stelle) { ?>
								<option class="select_option_link" onclick="gotoStelle(event, this)" title="<?php echo str_replace(' ', '&nbsp;', $stelle->get('bezeichnung')); ?>" value="<?php echo $stelle->get('id'); ?>"><?php echo $stelle->get('bezeichnung'); ?></option><?php
							} ?>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<th align="right" style="border-bottom:1px solid #C3C7C3"><? echo $strLabel_user_filter; ?> <span data-tooltip="<?php echo $strUserFilterHint; ?>"></span></th>
		<td style="border-bottom:1px solid #C3C7C3">
			<table>
				<tr valign="top">
					<td>
						<select name="selectedusers" size="6" multiple style="position: relative; width: 340px"><?
							foreach ($this->notification->selusers as $seluser) { ?>
								<option class="select_option_link" onclick="gotoUser(event, this)" title="<?php echo str_replace(' ', '&nbsp;', $seluser->get_name()); ?>" value="<?php echo $seluser->get_id(); ?>"><?php echo $seluser->get_name(); ?></option><?
							} ?>
						</select>
						<input name="user_filter" value="<? echo $this->notification->get('user_filter'); ?>" type="hidden">
					</td>
					<td align="center" valign="middle" width="1">
						<input type="button" name="addUsers" value="&laquo;" onClick="addOptions(document.GUI.allusers,document.GUI.selectedusers,document.GUI.user_filter,'value')">
						<input type="button" name="substractUsers" value="&raquo;" onClick="substractOptions(document.GUI.selectedusers,document.GUI.user_filter,'value');">
					</td>
					<td>
						<select name="allusers" size="6" multiple style="position: relative; width: 340px"><?
							foreach ($this->allusers as $user) { ?>
								<option class="select_option_link" onclick="gotoUser(event, this)" title="<?php echo str_replace(' ', '&nbsp;', $user->get_name()); ?>" value="<?php echo $user->get_id(); ?>"><?php echo $user->get_name(); ?></option><?php
							} ?>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="button" name="cancel" value="<? echo $this->strCancel; ?>" onclick="document.location.href = 'index.php?go=notifications_anzeigen&csrf_token=<? echo $_SESSION['csrf_token']; ?>'">
			<input id="form-submit-button" type="submit" name="go_plus" value="<? echo $this->strSave; ?>">
		</td>
	</tr>
</table>