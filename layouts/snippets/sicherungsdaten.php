<?php
  include(LAYOUTPATH . 'languages/sicherungsdaten_' . $this->user->rolle->language . '.php');
?>
<script>$('#gui-table').css('width', '100%')</script>
<script>
	function update_aktiv(id) {
		var aktiv = ($('#aktiv_' + id).is(':checked') ? 1 : 0);
		window.location = 'index.php?go=Sicherung_speichern&id=' + id + '&active=' + aktiv;
	}
</script>
<h2><? echo $strTitel; ?></h2>
<br>
<table cellpadding=5 cellspacing=0>
	<tr>
		<th align="left"><?php echo $strAktiv ?></th>
		<th align="left"><?php echo $strName ?></th>
		<th align="left"><?php echo $strBeschreibung ?></th>
		<th align="left"><?php echo $strZielVerz ?></th>
		<th align="left"><?php echo $strIntervall ?></th>
		<th width="70px"></th>
	</tr><?php
		foreach ($this->sicherungen as $sicherung) { ?>
			<tr<? echo ($sicherung->get('active') ? '' : ' class="small-gray"'); ?>>
				<td><input type="checkbox"
										value="<? echo $sicherung->get('id') ?>"
										id="aktiv_<? echo $sicherung->get('id') ?>"
										name="active" <? echo $sicherung->get('active') ? "checked" : "" ?>
										onchange="update_aktiv(this.value);"
							>
				<td><?php echo $sicherung->get('name'); ?></td>
				<td><?php echo $sicherung->get('beschreibung'); ?></td>
				<td><?php echo $sicherung->get('target_dir') ?></td>
				<td><?php echo $sicherung->get_intervall_as_plaintext(); ?> </td>
				<td width="70px" align="center">
					<a href="index.php?go=Sicherung_editieren&id=<?php echo $sicherung->get('id'); ?>"><i class="fa fa-pencil fa_lg" style="color: #a82e2e;"></i></a>
					<a href="index.php?go=Sicherung_loeschen&id=<?php echo $sicherung->get('id'); ?>" style="margin-left: 10px;"><i class="fa fa-trash-o fa_lg" style="color: #a82e2e;"></i></a>
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

<div style="text-align: left; margin-top: 20px; margin-left: 20px; width: 100%" id="crontab_div">
	<? echo isset($this->formvars['count_export']) ? 'Es wurden ' . $this->formvars['count_export'] . ' Sicherungen exportiert. <br>' : '';
		 echo (isset($this->formvars['count_skip']) && $this->formvars['count_skip'] > 0) ? $this->formvars['count_skip'] . ' Sicherungen wurden nicht exportiert. Entweder diese sind nicht akiv oder haben keinen Sicherungsinhalt.' : '';
	?>
	<? if (isset($this->formvars['crontab'])) { ?>
		<br><br>
	Folgender Crontab wird innerhalb der nächsten Stunde nach /etc/cron.d/kvwmap_backup_crontab kopiert.
	<div style="
		color: white;
		background-color: #000;
		border: 1px solid #fff;
		text-align: left;
		margin-top: 5px;
		margin-right: 80px;
		margin-bottom: 30px;
		padding: 5px;
		border-radius: 0px;
	"
	>
	<? echo $this->formvars['crontab'] ?>
	</div>
	<a href="#" onclick="document.getElementById('crontab_div').style.display = 'none'">Meldung schließen</a>
</div>
<?
}
?>
