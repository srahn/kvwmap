<?php
	include(LAYOUTPATH.'languages/cronjobs_'.$this->user->rolle->language.'.php');
?>
<script language="javascript" type="text/javascript">
	function update_aktiv(id) {
		var aktiv = ($('#aktiv_' + id).is(':checked') ? 1 : 0);
		window.location = 'index.php?go=cronjob_speichern_Speichern&id=' + id + '&aktiv=' + aktiv;
	}
</script>
<table width="700px" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>" style="margin-bottom: 40px;">
  <tr>
    <td align="center" colspan="4"><h2><?php echo $strTitle; ?></h2></td>
	</tr>
	<tr>
		<td align="right" colspan="4"><a class="btn btn-new" href="index.php?go=cronjob_editieren&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i titel="<? echo $strTitle_create_new_cronjob; ?>" class="fa fa-plus" style="color: white;"></i>&nbsp;Neuer&nbsp;Job</a></td>
  </tr>
	<tr>
		<th><i title="<? echo $strTitle_checkbox; ?>" class="fa fa-check-square-o" aria-hidden="true"></i></th>
		<th><? echo $strLabel_bezeichnung; ?></th>
		<td><? echo $strLabel_zeit; ?></td>
		<th><? echo $strLabel_beschreibung; ?></th>
		<th><? echo $strTitle_user; ?></th>
		<!--th>Query/Command</th>
		<th>User Id</th>
		<th>Stelle Id</th//-->
		<th width="70px"></th>
	<tr><?php
	foreach($this->cronjobs AS $cronjob) { ?>
		<tr>
			<td>
				<input
					id="aktiv_<?php echo $cronjob->get('id'); ?>"
					type="checkbox"
					class="aktiv_checkboxes"
					value="<?php echo $cronjob->get('id'); ?>"
					<?php echo ($cronjob->get('aktiv') ? ' checked' : ''); ?>
					onchange="update_aktiv(this.value);"
				>
			</td>
			<td><?php echo $cronjob->get('bezeichnung'); ?></td>
			<td><?php echo $cronjob->get('time'); ?></td>
			<td><?php echo $cronjob->get('beschreibung'); ?></td>
			<td><?php echo $cronjob->get('user'); ?></td>
			<!--td><textarea cols="33" rows="1" readonly><?php echo (empty($cronjob->get('query')) ? $cronjob->get('function') : $cronjob->get('query')); ?></textarea></td>
			<td><?php echo $cronjob->get('user_id'); ?></td>
			<td><?php echo $cronjob->get('stelle_id'); ?></td//-->
			<td width="70px">
				<a href="index.php?go=cronjob_editieren&selected_cronjob_id=<?php echo $cronjob->get('id'); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i class="fa fa-pencil fa_lg" style="color: #a82e2e;"></i></a>
				<a href="index.php?go=cronjob_löschen&selected_cronjob_id=<?php echo $cronjob->get('id'); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>" style="margin-left: 10px;"><i class="fa fa-trash-o fa_lg" style="color: #a82e2e;"></i></a>
			</td>
		<tr><?php
	} ?>
</table>
<p>
<a class="btn btn-new" href="index.php?go=crontab_schreiben&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i title="Schreibe die aktiven Cronjobs in die Crontab, damit sie von da an ausgeführt werden." class="fa fa-clock-o" style="color: white;"></i> Crontab Schreiben</a>