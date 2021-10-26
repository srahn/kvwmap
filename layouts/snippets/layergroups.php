<?php
	include(LAYOUTPATH . 'languages/layergroups_' . $this->user->rolle->language . '.php');
	$has_shared_group = array_reduce(
		$this->layergruppen,
		function($has_shared_group, $layergruppe) {
			return $has_shared_group OR $layergruppe->get('selectable_for_shared_layers');
		},
		0
	);
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
					<th><a href="index.php?go=Layergruppen_Anzeigen&order=order">Order</a></th><?
					$colspan = 2;
					if ($has_shared_group) {
						$colspan += 1;
					} ?>
					<td colspan="<? echo $colspan; ?>" align="right"><a class="btn btn-new" href="index.php?go=Layergruppe_Editor"><i titel="Legt eine neue Layergruppe an." class="fa fa-plus" style="color: white; margin-bottom: 10px"></i>&nbsp;Neue&nbsp;Gruppe</a></td>
				</tr><?php
				for ($i = 0; $i < count($this->layergruppen); $i++) { ?>
					<tr id="group_<?php echo $this->layergruppen[$i]->get('id'); ?>" onMouseover="this.bgColor='<?php echo BG_TR; ?>'" onMouseout="this.bgColor=''">
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
						</td><?
						if ($has_shared_group) { ?>
							<td align="right"><?
								if ($this->layergruppen[$i]->get('selectable_for_shared_layers')) { ?>
									<i class="fa fa-share-alt" style="padding: 3px"></i><?
								} else { ?>
									&nbsp;<?
								} ?>
							</td><?
						} ?>
						<td align="right">
							<a
								title="<?php echo $this->strChange; ?>"
								href="index.php?go=Layergruppe_Editor&selected_group_id=<?php echo $this->layergruppen[$i]->get('id'); ?>"
							><i class="fa fa-pencil" style="padding: 3px"></i></a>
						</td>
						<td>
							<span
								title="<?php echo $this->strDelete; ?>"
								onclick="group_delete(<?php echo $this->layergruppen[$i]->get('id'); ?>);"
								style="padding: 3px"
							><i class="fa fa-trash"></i></span>
						</td>
					</tr><?php
				} ?>
			</table>
		</td>
	</tr>
</table>