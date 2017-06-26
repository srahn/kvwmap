<?php
	include(LAYOUTPATH . 'languages/menuedaten_' . $this->user->rolle->language . '.php');
?>
<script>
	function menuedaten_delete(id) {
		console.log('menuedaten_delete ' + id);
		$.ajax({
			url: 'index.php',
			data: {
				'go': 'Menue_Löschen',
				'selected_menue_id': id
			},
			error: function(response) {
				message(response.msg);
			},
			success: function(response) {
				$('#menue_' + id).fadeOut(1000);
			}
		});
	}
</script>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
	<tr align="center">
		<td><h2><?php echo $strTitel; ?></h2></td>
	</tr>
	<tr>
		<td align="right" colspan="4"><a class="btn btn-new" href="index.php?go=Menueeditor"><i titel="Lege einen neuen Menüpunkt an." class="fa fa-plus" style="color: white;"></i>&nbsp;Neues&nbsp;Menue</a></td>
  </tr>
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<th><a href="index.php?go=Menues_Anzeigen&order=id"><?php echo $this->strID; ?></a></th>
					<th><a href="index.php?go=Menues_Anzeigen&order=name"><?php echo $this->strName; ?></a></th>
					<td colspan="3">&nbsp;</td>
				</tr><?php
				for ($i = 0; $i < count($this->menuedaten); $i++) { ?>
					<tr id="menue_<?php echo $this->menuedaten[$i]->get('id'); ?>">
						<td><a name="menue_<?php echo $this->menuedaten[$i]->get('id'); ?>" style="color: black;"><?php echo $this->menuedaten[$i]->get('id'); ?></a></td>
						<td><?php echo $this->menuedaten[$i]->get('name'); ?></td>
						<td>&nbsp;
							<i
								title="<?php echo $this->strChange; ?>"
								class="fa fa-pencil"
								aria-hidden="true"
								onclick="location.href='index.php?go=Menueeditor&selected_menue_id=<?php echo $this->menuedaten[$i]->get('id'); ?>';"
								style="cursor: pointer; color: firebrick"
							 ><a href="index.php?go=Menueeditor&selected_menue_id=<?php echo $this->menuedaten[$i]->get('id'); ?>"></td>
						<td>&nbsp;
							<i
								title="<?php echo $this->strDelete; ?>"
								class="fa fa-trash"
								aria-hidden="true"
								onclick="menuedaten_delete(<?php echo $this->menuedaten[$i]->get('id'); ?>);"
								style="cursor: pointer; color: firebrick"
							></i>
						</td>
					</tr><?php
				} ?>
			</table>
		</td>
	</tr>
</table>