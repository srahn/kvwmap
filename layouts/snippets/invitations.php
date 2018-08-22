<?php
	include(LAYOUTPATH . 'languages/invitations_' . $this->user->rolle->language . '.php');
?>
<script>
	function invitation_delete(id) {
	  Check = confirm('Einladung wirklick löschen?');
	  if (Check == true) {
			$.ajax({
				url: 'index.php',
				data: {
					'go': 'Einladung_Löschen',
					'selected_invitation_id': id
				},
				error: function(response) {
					message(response.msg);
				},
				success: function(response) {
					$('#invitation_' + id).fadeOut(1000);
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
					<th><a href="index.php?go=Einladungen_Anzeigen&order=token">Kennung</a></th>
					<th><a href="index.php?go=Einladungen_Anzeigen&order=email">E-Mail</a></th>
					<th><a href="index.php?go=Einladungen_Anzeigen&order=stelle_id">Stelle</a></th>
					<th><a href="index.php?go=Einladungen_Anzeigen&order=name">Nutzer</th>
					<th><a href="index.php?go=Einladungen_Anzeigen&order=completed">Registriert</a></th>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
				</tr><?php
				for ($i = 0; $i < count($this->invitations); $i++) { ?>
					<tr id="invitation_<?php echo $this->invitations[$i]->get('token'); ?>">
						<td>
							<a name="invitation_<?php echo $this->invitations[$i]->get('token'); ?>" style="color: black;"><?php echo $this->invitations[$i]->get('token'); ?></a>
						</td>
						<td>
							<span><?php echo $this->invitations[$i]->get('email') ?></span>
						</td>
						<td>
							<span><?php echo $this->invitations[$i]->get('stelle_id'); ?></span>
						</td>
						<td>
							<span><?php echo $this->invitations[$i]->get('vorname') . ' ' . $this->invitations[$i]->get('name'); ?></span>
						</td>
						<td>
							<span><?php echo $this->invitations[$i]->get('completed'); ?></span>
						</td>
						<td>&nbsp;
							<a
								title="<?php echo $this->strChange; ?>"
								href="index.php?go=Einladung_Editor&selected_invitation_id=<?php echo $this->invitations[$i]->get('token'); ?>"
							><i class="fa fa-pencil" style="color: firebrick"></i></a>
						</td>
						<td>&nbsp;
							<span
								title="<?php echo $this->strDelete; ?>"
								onclick="invitation_delete(<?php echo $this->invitations[$i]->get('token'); ?>);"
								style="cursor: pointer; color: firebrick;"
							><i class="fa fa-trash" style="color: firebrick"></i></span>
						</td>
					</tr><?php
				} ?>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center" style="padding-top: 20px">
			<a class="btn btn-new" href="index.php?go=Einladung_Editor"><i titel="Legt eine neue Einladung an." class="fa fa-plus" style="color: white; margin-bottom: 10px"></i>&nbsp;Neue&nbsp;Einladung</a>
		</td>
	</tr>
</table>
