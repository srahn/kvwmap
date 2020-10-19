<?php
  include(LAYOUTPATH.'languages/stellendaten_'.$this->user->rolle->language.'.php');
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
						<th><a href="index.php?go=Stellen_Anzeigen&order=bezeichnung"><?php echo $this->strName; ?></a></th>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<?php
					for ($i=0;$i<count($this->stellendaten['ID']);$i++) { ?>
					<tr class="listen-tr">
						<td align="right"><?php echo $this->stellendaten['ID'][$i]; ?></td>
						<td><?php echo $this->stellendaten['Bezeichnung'][$i]; ?></td>
						<td><a href="index.php?go=Stelleneditor&selected_stelle_id=<? echo $this->stellendaten['ID'][$i]; ?>" title="<?php echo $this->strChange; ?>"><i class="fa fa-pencil"></a></td>
						<td><a href="index.php?go=Layer2Stelle_Reihenfolge&selected_stelle_id=<? echo $this->stellendaten['ID'][$i]; ?>" title="Layer"><i class="fa fa-list"></i></a></td>
						<td><a href="javascript:Bestaetigung('index.php?go=Stelle_Löschen&selected_stelle_id=<? echo $this->stellendaten['ID'][$i]; ?>&order=<? echo $this->formvars['order']; ?>','Wollen Sie die Stelle \'<? echo $this->stellendaten['Bezeichnung'][$i]; ?>\' wirklich löschen?')" title="<?php echo $this->strDelete; ?>"><i class="fa fa-trash-o"></i></a></td>
					</tr>
					<?php
					}
					?>
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
