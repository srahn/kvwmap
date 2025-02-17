<?php
	include(LAYOUTPATH . 'languages/notifications_' . $this->user->rolle->language . '.php');
?>
<script>
	function deleteNotification(id) {
		Check = confirm('Wollen Sie die Benachrichtigung wirklich löschen?');
		if (Check) {
			//console.log('deleteNotification %s', id);
			let formData = new FormData();
			formData.append('go', 'delete_notification');
			formData.append('id', id);
			formData.append('csrf_token', '<? echo $_SESSION['csrf_token']; ?>')
			let response = fetch('index.php', {
				method: 'POST',
				body: formData
			})
			.then(response => response.text())
			.then(text => {
				try {
					const data = JSON.parse(text);
					if (data.success) {
						let notification_id = id.toString().trim();
						console.log('Lösche div mit id: notification_', notification_id);
						$('#notification_' + notification_id).remove();
					}
					else {
						message([{ 'type': 'error', 'msg' : 'Fehler beim Löschen in der Datenbank: ' + data.err_msg}]);
					}
				} catch(err) {
					message([{ 'type': 'error', 'msg' : err.name + ': ' + err.message + ' in Zeile: ' + err.lineNumber + ' Response: ' + text}]);
				}
			});
		}
	}
</script>

<div id="nfc_titel" name="titel"><? echo $strTitle; ?></div>
<div id="nfc_newIcon"><a class="btn btn-new" href="index.php?go=notification_formular&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i titel="<? echo $strTitle_create_new_notification; ?>" class="fa fa-plus" style="color: white;"></i>&nbsp;<? echo $strNewCronJob; ?></a></div>
<div id="nfc_formular">
	<div id="nfc_formular_header">
		<div><? echo $strLabel_id; ?></div>
		<div><? echo $strLabel_notification; ?></div>
		<div><? echo $strLabel_veroeffentlichungsdatum; ?></div>
		<div><? echo $strLabel_ablaufdatum; ?></div>
		<div><? echo $strLabel_stellen_filter; ?></div>
		<div><? echo $strLabel_user_filter; ?></div>
		<div></div>
	</div>
<?php
	foreach ($this->notifications AS $notification) {
?>
	<div id="notification_<? echo $notification->get('id'); ?>" class="nfc_formular_data">
		<div><? echo $notification->get('id'); ?></div>
		<div><? echo $notification->get('notification'); ?></div>
		<div><? echo $notification->get('veroeffentlichungsdatum'); ?></div>
		<div><? echo $notification->get('ablaufdatum'); ?></div>
		<div><? echo $notification->get('stellen_filter'); ?></div>
		<div><? echo $notification->get('user_filter'); ?></div>
		<div>
			<a href="index.php?go=notification_formular&id=<?php echo $notification->get('id'); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i class="fa fa-pencil fa_lg" style="color: #a82e2e;"></i></a>
			<a href="#" style="margin-left: 10px;" onclick="deleteNotification(<?php echo $notification->get('id'); ?>);"><i class="fa fa-trash-o fa_lg" style="color: #a82e2e;"></i></a>
		</div>
	</div>
<?php
	}
?>
</div>

