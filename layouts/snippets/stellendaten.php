<?php
  include(LAYOUTPATH . 'languages/stellendaten_' . $this->user->rolle->language . '.php');
	$has_shared_stelle = array_reduce(
		$this->stellendaten['show_shared_layers'],
		function($has_shared_stelle, $show_shared_layers) {
			return $has_shared_stelle OR $show_shared_layers;
		},
		0
	);
?>
<table border="0" cellpadding="5" cellspacing="0" style="width: 100%">
  <tr align="center">
    <td><h2><?php echo $strTitel; ?></h2></td>
  </tr>
  <tr>
    <td>
			<div class="stellendaten-topdiv">
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr class="stellendaten-header">
						<th align="right"><a href="index.php?go=Stellen_Anzeigen&order=ID"><?php echo $this->strID; ?></a></th>
						<th align="left"><a href="index.php?go=Stellen_Anzeigen&order=bezeichnung"><?php echo $this->strName; ?></a></th><?
						if ($has_shared_stelle) { ?>
							<td>&nbsp;</td><?
						} ?>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr><?php
					for ($i = 0; $i < count($this->stellendaten['ID']); $i++) { ?>
						<tr class="listen-tr">
							<td align="right"><?php echo $this->stellendaten['ID'][$i]; ?></td>
							<td><?php echo $this->stellendaten['Bezeichnung'][$i]; ?></td><?
							if ($has_shared_stelle) { ?>
								<td><?
									if ($this->stellendaten['show_shared_layers']) { ?>
										<i class="fa fa-share-alt" title="<? echo $strShowSharedLayer; ?>"></i><?
									}
									else { ?>
										&nbsp; <?
									} ?>
								</td><?
							} ?>
							<td>
								<a href="index.php?go=Stelleneditor&selected_stelle_id=<? echo $this->stellendaten['ID'][$i]; ?>" title="<?php echo $this->strChange; ?>">
									<i class="fa fa-pencil"></i>
								</a>
							</td>
							<td>
								<a href="index.php?go=Layer2Stelle_Reihenfolge&selected_stelle_id=<? echo $this->stellendaten['ID'][$i]; ?>" title="Layer">
									<i class="fa fa-list"></i>
								</a>
							</td>
							<td>
								<a href="javascript:Bestaetigung('index.php?go=Stelle_Löschen&selected_stelle_id=<? echo $this->stellendaten['ID'][$i]; ?>&order=<? echo $this->formvars['order']; ?>','Wollen Sie die Stelle \'<? echo $this->stellendaten['Bezeichnung'][$i]; ?>\' wirklich löschen?')" title="<?php echo $this->strDelete; ?>">
									<i class="fa fa-trash-o"></i>
								</a>
							</td>
						</tr><?php
					} ?>
				</table>
			</div>
		</td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
  </tr>
</table>
      <input type="hidden" name="go" value="Stellen">
      <input type="hidden" name="order" value="<?php echo $this->formvars['order']; ?>">
