<?php
	include(LAYOUTPATH . 'languages/menuedaten_' . $this->user->rolle->language . '.php');
?>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
	<tr align="center">
		<td><h2><?php echo $strTitel; ?></h2></td>
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
					<tr>
						<td><a name="menue_<?php echo $this->menuedaten[$i]->get('id'); ?>" style="color: black;"><?php echo $this->menuedaten[$i]->get('id'); ?></a></td>
						<td><?php echo $this->menuedaten[$i]->get('name'); ?></td>
						<td>&nbsp;<a href="index.php?go=Menueeditor&selected_menue_id=<?php echo $this->menuedaten[$i]->get('id'); ?>"><?php echo $this->strChange; ?></a></td>
						<td>&nbsp;<a href="javascript:Bestaetigung('index.php?go=Menue_Löschen&selected_menue_id=<?php echo $this->menuedaten[$i]->get('id'); ?>&order=<? echo $this->formvars['order']; ?>','Wollen Sie dieses Menü wirklich löschen?')"><?php echo $this->strDelete; ?></a></td>
					</tr><?php
				} ?>
			</table>
		</td>
	</tr>
</table>