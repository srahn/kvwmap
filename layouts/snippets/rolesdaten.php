<?php
	include(LAYOUTPATH . 'languages/rolesdaten_' . rolle::$language . '.php');
?>
<script>

</script>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
	<tr align="center">
		<td>
			<h2><?php echo $strTitel; ?></h2>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<th><a href="index.php?go=role_list&view_sort=stelle_id&sort_direction=<?php echo $this->formvars['sort_direction']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>">stelle_id</a></th>
					<th><a href="index.php?go=role_list&view_sort=user_id&sort_direction=<?php echo $this->formvars['sort_direction']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>">user_id</a></th>
				</tr><?php
				foreach ($this->roles AS $role) { ?>
					<tr id="rolle_<?php echo $role->get_rolle_id(); ?>">
						<td><? echo $role->stelle->get('Bezeichnung') . ' (' . $role->stelle->get_id() . ')'; ?></td>
						<td><? echo $role->user->get_name() . ' (' . $role->user->get_id() . ')'; ?></td>
						<td>&nbsp;
							<a
								title="<?php echo $this->strChange; ?>"
								href="index.php?go=role_edit&user_id=<?php echo $role->get('user_id'); ?>&stelle_id=<? echo $role->get('stelle_id'); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"
							><i class="fa fa-pencil" style="color: firebrick"></i></a>
						</td>
					</tr><?php
				} ?>
			</table>
		</td>
	</tr>
</table>