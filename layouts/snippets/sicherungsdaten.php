<?php
  include(LAYOUTPATH . 'languages/sicherungsdaten_' . $this->user->rolle->language . '.php');
?>
<script>$('#gui-table').css('width', '100%')</script>
<h2><? echo $strTitel; ?></h2>
<br>
<table cellpadding=5 cellspacing=0>
	<tr>
		<th><?php echo $strName ?></th>
		<th><?php echo $strBeschreibung ?></th>
		<th><?php echo $strZielVerz ?></th>
		<th><?php echo $strIntervall ?></th>
		<th width="70px"></th>
	</tr><?php
		foreach ($this->sicherungen as $sicherung) { ?>
			<tr>
				<td><?php echo $sicherung->get('name'); ?></td>
				<td><?php echo $sicherung->get('beschreibung'); ?></td>
				<td><?php echo $sicherung->get('target_dir') . $sicherung->get_folder_date_notation() ?></td>
				<td><?php echo $sicherung->get_intervall_as_plaintext(); ?> </td>
				<td width="70px" align="center">
					<a href="index.php?go=Sicherung_editieren&sicherung_id=<?php echo $sicherung->get('id'); ?>"><i class="fa fa-pencil fa_lg" style="color: #a82e2e;"></i></a>
					<a href="index.php?go=Sicherung_loeschen&sicherung_id=<?php echo $sicherung->get('id'); ?>" style="margin-left: 10px;"><i class="fa fa-trash-o fa_lg" style="color: #a82e2e;"></i></a>
				</td>
			</tr><?php
		} ?>
	<tr>
		<td colspan="3" align="left">
			<input type="button" onclick="document.location.href='index.php?go=Administratorfunktionen&func=write_backup_config_and_cron'" value="<?php echo $strCronerstellen ?>"></td>
		<td colspan="2" align="right">
			<input type="button" onclick="document.location.href='index.php?go=Sicherung_editieren'" value="<?php echo $strNeueSicherung ?>">
		</td>
	</tr>
</table>
