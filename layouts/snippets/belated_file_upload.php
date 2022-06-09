<?php
	include(LAYOUTPATH . 'languages/belated_file_upload_' . $this->user->rolle->language . '.php');
?>
<script type="text/javascript">
	
	var Formdata = new FormData();
	Formdata.append('go', 'belated_file_upload_speichern');
	
</script>

<h2><?php echo $strTitel; ?></h2><?
if (count($this->belated_files) == 0) { ?>
	<div style="
		margin-top: 20px;
	">Es sind keine Dateien nachträglich hochzuladen!</div><?php
}
else { ?>
	<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
		<tr align="center">
			<td></td>
		</tr>
		<tr>
			<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<!--th><a href="index.php?go=belated_file_upload&order=user">Nutzer</th/-->
						<th><a href="index.php?go=belated_file_upload&order=layer&csrf_token=<? echo $_SESSION['csrf_token']; ?>">Layer</a></th>
						<th><a href="index.php?go=belated_file_upload&order=dataset&csrf_token=<? echo $_SESSION['csrf_token']; ?>">Datensatz</a></th>
						<th><a href="index.php?go=belated_file_upload&order=attribut_name&csrf_token=<? echo $_SESSION['csrf_token']; ?>">Attribut</a></th>
						<th><a href="index.php?go=belated_file_upload&order=name&csrf_token=<? echo $_SESSION['csrf_token']; ?>">Dateiname</a></th>
						<th><a href="index.php?go=belated_file_upload&order=lastmodified&csrf_token=<? echo $_SESSION['csrf_token']; ?>">Änderungsdatum</a></th>
						<th><a href="index.php?go=belated_file_upload&order=size&csrf_token=<? echo $_SESSION['csrf_token']; ?>">Dateigröße</a></th>
					</tr><?php
					foreach ($this->belated_files AS $belated_file) { ?>
						<tr id="$belated_file_<?php echo $belated_file->get('id'); ?>">
							<!--td>
								<span><?php echo $belated_file->get('user_id'); ?></span>
							</td/-->
							<td>
								<span><?php echo $belated_file->get('layer_id') ?></span>
							</td>
							<td>
								<a href="index.php?go=Layer-Suche_Suchen&selected_layer_id=<?php echo $belated_file->get('layer_id'); ?>&value_id=<?php echo $belated_file->get('dataset_id'); ?>&operator_id=="><?php echo $belated_file->get('dataset_id'); ?></a>
							</td>
							<td>
								<span><?php echo $belated_file->get('attributename'); ?></span>
							</td>
							<td>
								<span><?php echo $belated_file->get('name'); ?></span>
							</td>
							<td>
								<span><?php echo date('d.m.Y H:i:s', $belated_file->get('lastmodified') / 1000); ?></span>
							</td>
							<td>
								<span><?php echo $belated_file->get('size'); ?></span>
							</td>
						</tr><?php
					} ?>
				</table>
			</td>
		</tr>
	</table>
	<div id="new_109_fotos_0"></div>
	<? include(SNIPPETS . 'multi_file_upload.php');
} ?>
