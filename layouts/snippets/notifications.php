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
						$('#notification_box_' + notification_id).remove();
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
<br>
<h2><?php echo $strTitle; ?></h2>
<br>
<table width="700px" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>" style="margin-bottom: 40px;">
	<tr>
		<th><? echo $strLabel_id; ?></th>
		<th><? echo $strLabel_notification; ?></th>
		<th><? echo $strLabel_veroeffentlichungsdatum; ?></th>
		<th><? echo $strLabel_ablaufdatum; ?></th>
		<th><? echo $strLabel_stellen_filter; ?></th>
		<th><div style="width: 60px"><a class="btn btn-new" href="index.php?go=notification_formular&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i titel="<? echo $strTitle_create_new_notification; ?>" class="fa fa-plus" style="color: white;"></i>&nbsp;<? echo $strNewCronJob; ?></a></div></th>
	<tr><?php
	foreach ($this->notifications AS $notification) { ?>
		<tr id="notification_<? echo $notification->get('id'); ?>">
			<td><? echo $notification->get('id'); ?></td>
			<td><? echo $notification->get('notification'); ?></td>
			<td><? echo $notification->get('veroeffentlichungsdatum'); ?></td>
			<td><? echo $notification->get('ablaufdatum'); ?></td>
			<td><? echo $notification->get('stellen_filter'); ?></td>
			<td>
				<a href="index.php?go=notification_formular&id=<?php echo $notification->get('id'); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i class="fa fa-pencil fa_lg" style="color: #a82e2e;"></i></a>
				<a href="#" style="margin-left: 10px;" onclick="deleteNotification(<?php echo $notification->get('id'); ?>);"><i class="fa fa-trash-o fa_lg" style="color: #a82e2e;"></i></a>
			</td>
		<tr><?php
	} ?>
</table>
