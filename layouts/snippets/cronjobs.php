<?php
	include(LAYOUTPATH.'languages/cronjobs_'.$this->user->rolle->language.'.php');
?>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>" style="margin-bottom: 40px;">
  <tr>
    <td align="center" colspan="6" height="40" valign="middle"><h2><?php echo $strTitle; ?></h2></td>
		<td align="right"><a class="btn btn-new" href="index.php?go=cronjob_editieren"><i class="fa fa-plus" style="color: white;"></i> Neuer Job</a></td>
  </tr>
	<tr>
		<th>id</th>
		<th>bezeichnung</th>
		<th>time</th>
		<th>query</th>
		<th>user_id</th>
		<th>stelle_id</th>
		<th></th>
	<tr><?php
	foreach($this->cronjobs AS $cronjob) { ?>
		<tr>
			<td><?php echo $cronjob->get('id'); ?></td>
			<td><?php echo $cronjob->get('bezeichnung'); ?></td>
			<td><?php echo $cronjob->get('time'); ?></td>
			<td><textarea cols="10" rows="1" readonly><?php echo $cronjob->get('query'); ?></textarea></td>
			<td><?php echo $cronjob->get('user_id'); ?></td>
			<td><?php echo $cronjob->get('stelle_id'); ?></td>
			<td>
				<a href="index.php?go=cronjob_editieren&selected_cronjob_id=<?php echo $cronjob->get('id'); ?>"><i class="fa fa-pencil fa_lg" style="color: #a82e2e;"></i></a>
				<a href="index.php?go=cronjob_löschen&selected_cronjob_id=<?php echo $cronjob->get('id'); ?>" style="margin-left: 10px;"><i class="fa fa-trash-o fa_lg" style="color: #a82e2e;"></i></a>
			</td>
		<tr><?php
	} ?>
</table>
<p>
<a class="btn btn-new" href="index.php?go=crontab_anzeigen"><i class="fa fa-clock-o" style="color: white;"></i> Crontab Anzeigen</a>