<?php
	include(LAYOUTPATH . 'languages/menuedaten_' . rolle::$language . '.php');
	$name_column = 'name';
	if (rolle::$language != 'german') {
		$name_column .= '_' . rolle::$language;
	}
?>
<script>
	function menuedaten_delete(id) {
	  Check = confirm('Menüpunkt wirklich löschen?');
	  if (Check == true) {
			$.ajax({
				url: 'index.php',
				data: {
					go: 'Menue_Löschen',
					selected_menue_id: id,
					csrf_token: '<? echo $_SESSION['csrf_token']; ?>'
				},
				error: function(response) {
					message(response.msg);
				},
				success: function(response) {
					$('#menue_' + id).fadeOut(1000);
				}
			});
		}
	}
</script>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
	<tr align="center">
		<td>
			<h2><?php echo $strTitel; ?></h2>
			<div id="neuer_datensatz_button">
				<a class="btn btn-new" href="index.php?go=Menueeditor&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i titel="Lege einen neuen Menüpunkt an." class="fa fa-plus" style="color: white;"></i>&nbsp;Neues&nbsp;Menue</a>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<th><a href="index.php?go=Menues_Anzeigen&view_sort=id&sort_direction=<?php echo $this->formvars['sort_direction']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?php echo $this->strID; ?></a></th>
					<th><a href="index.php?go=Menues_Anzeigen&view_sort=name&sort_direction=<?php echo $this->formvars['sort_direction']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?php echo $this->strName; ?></a></th>
					<th><a href="index.php?go=Menues_Anzeigen&view_sort=obermenue&sort_direction=<?php echo $this->formvars['sort_direction']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><? echo $strTopMenue; ?></a></th>
					<th><a href="index.php?go=Menues_Anzeigen&view_sort=menueebene&sort_direction=<?php echo $this->formvars['sort_direction']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><? echo $strMenueLevel; ?></th>
					<th><a href='index.php?go=Menues_Anzeigen&view_sort="order"&sort_direction=<?php echo $this->formvars['sort_direction']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>'><? echo $strMenueOrder; ?></a></th>
					<th colspan="2"><a href='index.php?go=Menues_Anzeigen&view_sort=menueebene,"order"&sort_direction=<?php echo $this->formvars['sort_direction']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>'><? echo $strMenueLevel . ' ' . $this->strAnd . ' ' . $strMenueOrder; ?></a><br><a href="index.php?go=Menues_Anzeigen&view_sort=menueebene,name&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><? echo $strMenueLevel . ' ' . $this->strAnd . ' ' . $this->strName; ?></a></td>
				</tr><?php
				for ($i = 0; $i < count($this->menuedaten); $i++) {
					$font_size = ($this->menuedaten[$i]->get('menueebene') == '1' ? 16 : 12);
					$font_size_factor = 1.15 / 15;
					 ?>
					<tr id="menue_<?php echo $this->menuedaten[$i]->get('id'); ?>">
						<td>
							<a name="menue_<?php echo $this->menuedaten[$i]->get('id'); ?>" style="color: black; font-size: <?php echo $font_size_factor * $font_size; ?>em"><?php echo $this->menuedaten[$i]->get('id'); ?></a>
						</td>
						<td>
							<span style="font-size: <?php echo $font_size_factor * $font_size; ?>em"><?php echo $this->menuedaten[$i]->get($name_column) ?></span>
						</td>
						<td>
							<span style="font-size: <?php echo $font_size_factor * $font_size; ?>em"><?php echo $this->menuedaten[$i]->get('obermenue') ?></span>
						</td>
						<td>
							<span style="font-size: <?php echo $font_size_factor * $font_size; ?>em"><?php echo $this->menuedaten[$i]->get('menueebene'); ?></span>
						</td>
						<td>
							<span style="font-size: <?php echo $font_size_factor * $font_size; ?>em"><?php echo $this->menuedaten[$i]->get('order'); ?></span>
						</td>
						<td>&nbsp;
							<a
								title="<?php echo $this->strChange; ?>"
								href="index.php?go=Menueeditor&selected_menue_id=<?php echo $this->menuedaten[$i]->get('id'); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"
								style="font-size: <?php echo $font_size_factor * $font_size; ?>em"
							><i class="fa fa-pencil" style="color: firebrick"></i></a>
						</td>
						<td>&nbsp;
							<span
								title="<?php echo $this->strDelete; ?>"
								onclick="menuedaten_delete(<?php echo $this->menuedaten[$i]->get('id'); ?>);"
								style="cursor: pointer; color: firebrick; font-size: <?php echo $font_size_factor * $font_size; ?>em"
							><i class="fa fa-trash" style="color: firebrick"></i></span>
						</td>
					</tr><?php
				} ?>
			</table>
		</td>
	</tr>
</table>