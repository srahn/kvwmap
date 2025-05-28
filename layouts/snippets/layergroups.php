<?php
	include(LAYOUTPATH . 'languages/layergroups_' . rolle::$language . '.php');
	#$layers = array_map(function($group) { return $group->layers; }, $this->layergruppen);
	#echo 'Layer: ' . print_r(arra$layers[15]->data, true); exit;
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
	  Check = confirm('Gruppe wirklich löschen?');
	  if (Check == true) {
			$.ajax({
				url: 'index.php',
				data: {
					go: 'Layergruppe_Löschen',
					selected_group_id: id,
					csrf_token: '<? echo $_SESSION['csrf_token']; ?>'
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
					<th><a href="index.php?go=Layergruppen_Anzeigen&order=id&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?php echo $this->strID; ?></a></th>
					<th><a href="index.php?go=Layergruppen_Anzeigen&order=Gruppenname&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?php echo $this->strName; ?></a></th>
					<th><a href="index.php?go=Layergruppen_Anzeigen&order=obergruppe&csrf_token=<? echo $_SESSION['csrf_token']; ?>">Obergruppe</th>
					<th><a href="index.php?go=Layergruppen_Anzeigen&order=order&csrf_token=<? echo $_SESSION['csrf_token']; ?>">Order</a></th>
					<th>Anzahl Layer</th><?
					$colspan = 2;
					if ($has_shared_group) {
						$colspan += 1;
					} ?>
					<td colspan="<? echo $colspan; ?>" align="right"><a class="btn btn-new" href="index.php?go=Layergruppe_Editor&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i titel="Legt eine neue Layergruppe an." class="fa fa-plus" style="color: white; margin-bottom: 10px"></i> <?php echo  $strCreateLayerGroup; ?></a></td>
				</tr><?php
				for ($i = 0; $i < count($this->layergruppen); $i++) { ?>
					<tr id="group_<?php echo $this->layergruppen[$i]->get('id'); ?>" onMouseover="this.bgColor='<?php echo BG_TR; ?>'" onMouseout="this.bgColor=''">
						<td>
							<a name="group_<?php echo $this->layergruppen[$i]->get('id'); ?>" style="color: black;"><?php echo $this->layergruppen[$i]->get('id'); ?></a>
						</td>
						<td>
							<span><?php echo $this->layergruppen[$i]->get('gruppenname') ?></span>
						</td>
						<td>
							<span><?php echo $this->layergruppen[$i]->get('obergruppe'); ?></span>
						</td>
						<td>
							<span><?php echo $this->layergruppen[$i]->get('order'); ?></span>
						</td>
						<td style="text-align: center">
							<span><?php echo (count($this->layergruppen[$i]->layers) > 0 ? count($this->layergruppen[$i]->layers) : ''); ?></span>
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
								href="index.php?go=Layergruppe_Editor&selected_group_id=<?php echo $this->layergruppen[$i]->get('id'); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"
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