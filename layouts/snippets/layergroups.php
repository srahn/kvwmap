<?php
	include(LAYOUTPATH . 'languages/layergroups_' . $this->user->rolle->language . '.php');
?>
<script>
	function group_delete(id) {
	  Check = confirm('Gruppe wirklick löschen?');
	  if (Check == true) {
			$.ajax({
				url: 'index.php',
				data: {
					'go': 'Layergruppe_Löschen',
					'selected_group_id': id
				},
				error: function(response) {
					message(response.msg);
				},
				success: function(response) {
					$('#group_' + id).fadeOut(1000);
				}
			});
		}
	}
</script>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
	<tr align="center">
		<td><h2><?php echo $strTitel; ?></h2></td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<th><a href="index.php?go=Layergruppen_Anzeigen&order=id"><?php echo $this->strID; ?></a></th>
					<th><a href="index.php?go=Layergruppen_Anzeigen&order=Gruppenname"><?php echo $this->strName; ?></a></th>
					<th><a href="index.php?go=Layergruppen_Anzeigen&order=obergruppe">Obergruppe</th>
					<th><a href="index.php?go=Layergruppen_Anzeigen&order=order">Order</a></th>
					<td colspan="2" align="right"><a class="btn btn-new" href="index.php?go=Layergruppe_Editor"><i titel="Legt eine neue Layergruppe an." class="fa fa-plus" style="color: white; margin-bottom: 10px"></i>&nbsp;Neue&nbsp;Gruppe</a></td>
				</tr><?php
				for ($i = 0; $i < count($this->layergruppen); $i++) { ?>
					<tr id="group_<?php echo $this->layergruppen[$i]->get('id'); ?>">
						<td>
							<a name="group_<?php echo $this->layergruppen[$i]->get('id'); ?>" style="color: black;"><?php echo $this->layergruppen[$i]->get('id'); ?></a>
						</td>
						<td>
							<span><?php echo $this->layergruppen[$i]->get('Gruppenname') ?></span>
						</td>
						<td>
							<span><?php echo $this->layergruppen[$i]->get('obergruppe'); ?></span>
						</td>
						<td>
							<span><?php echo $this->layergruppen[$i]->get('order'); ?></span>
						</td>
						<td>&nbsp;
							<a
								title="<?php echo $this->strChange; ?>"
								href="index.php?go=Layergruppe_Editor&selected_group_id=<?php echo $this->layergruppen[$i]->get('id'); ?>"
							>Ändern</a>
						</td>
						<td>&nbsp;
							<span
								title="<?php echo $this->strDelete; ?>"
								onclick="group_delete(<?php echo $this->layergruppen[$i]->get('id'); ?>);"
								style="cursor: pointer; color: firebrick;"
							>Löschen</span>
						</td>
					</tr><?php
				} ?>
			</table>
		</td>
	</tr>
</table>