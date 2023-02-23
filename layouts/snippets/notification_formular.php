<?php
	include(LAYOUTPATH.'languages/notifications_' . $this->user->rolle->language . '.php');
	include_once(CLASSPATH . 'FormObject.php');
	$stellen_filter_options = array(
		'in allen Stellen',
		'nicht in Gaststellen',
		'nur in ausgewählten Stellen'
	);
?>
<script>
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
				}
				else {
					debug_data = data;
					if (typeof data.err_msg != 'undefined' && data.err_msg != '') {
						message([{ 'type': 'error', 'msg' : 'Fehler beim Speichern in der Datenbank: ' + text + ' ' + data.err_msg + ' sql: ' + data.sql}]);
					}
					else {
						data.validation_errors.forEach(
							(result) => {
								$('#form-submit-butto').show();
								show_validation_error(result);
							}
						);
					}
				}
			} catch(err) {
				message([{ 'type': 'error', 'msg' : err.name + ': ' + err.message + ' in Zeile: ' + err.lineNumber + ' Response: ' + text}]);
			}
		});
	};
</script>
<br>
<h2><?php echo ($this->notification->get('id') > 0 ? $strTitleEditNotification : $strTitleNewNotification); ?></h2>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
	<tr>
		<td colspan="2" align="center"><?php
			if ($this->Meldung == $strSuccess OR $this->Meldung == '') {
				$bgcolor = BG_FORM;
			}
			else {
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
		<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><? echo $strLabel_notification; ?>*</th>
		<td style="border-bottom:1px solid #C3C7C3">
			<textarea id="notification" name="notification" cols="80" rows="10" placeholder="<? echo $strPlaceholder_notification; ?>"><?
				echo $this->notification->get('notification');
			?></textarea>
		</td>
	</tr>
	<tr>
		<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><? echo $strLabel_veroeffentlichungsdatum;?> <span data-tooltip="<?php echo $strVeroeffentlichungsdatumHint; ?>"></span></th>
		<td style="border-bottom:1px solid #C3C7C3"><input id="veroeffentlichungsdatum" name="veroeffentlichungsdatum" type="date" value="<?php echo $this->notification->get('veroeffentlichungsdatum'); ?>" size="10" maxlength="10" placeholder="DD.MM.JJJJ"></td>
	</tr>
	<tr>
		<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><? echo $strLabel_ablaufdatum;?>* <span data-tooltip="<?php echo $strAblaufdatumHint; ?>"></span></th>
		<td style="border-bottom:1px solid #C3C7C3"><input id="ablaufdatum" name="ablaufdatum" type="date" value="<?php echo $this->notification->get('ablaufdatum'); ?>" size="10" maxlength="10" placeholder="DD.MM.JJJJ"></td>
	</tr>
	<tr>
		<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><? echo $strLabel_stellen_filter; ?> <span data-tooltip="<?php echo $strStellenFilterHint; ?>"></span></th>
		<td style="border-bottom:1px solid #C3C7C3">
			<input id="stellen_filter" name="stellen_filter" type="text" placeholder="<? echo $strPlaceholder_stellen_filter; ?>" value="<?php echo $this->notification->get('stellen_filter'); ?>"/>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="button" name="cancle" value="<? echo $this->strCancel; ?>" onclick="document.location.href = 'index.php?go=notifications_anzeigen&csrf_token=<? echo $_SESSION['csrf_token']; ?>'">
			<input id="form-submit-button" type="submit" name="go_plus" value="<? echo $this->strSave; ?>">
		</td>
	</tr>
</table>