<?php
  include(LAYOUTPATH . 'languages/stellendaten_' . rolle::$language . '.php');
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
  </tr>	<? 
	
	if ($this->formvars['order'] == 'Bezeichnung') { ?>
		<tr height="50px" valign="bottom">
			<td><?
				$umlaute=array("Ä","Ö","Ü");
				for ($i = 0; $i < count($this->stellendaten['ID']); $i++) {
					if (!in_array(strtoupper(mb_substr($this->stellendaten['Bezeichnung'][$i],0,1,'UTF-8')),$umlaute) AND strtolower(mb_substr($this->stellendaten['Bezeichnung'][$i],0,1,'UTF-8')) != $first) {
						echo "<a href='#".strtoupper(mb_substr($this->stellendaten['Bezeichnung'][$i],0,1,'UTF-8'))."'><div class='menu abc'>".strtoupper(mb_substr($this->stellendaten['Bezeichnung'][$i],0,1,'UTF-8'))."</div></a>";
						$first=strtolower(mb_substr($this->stellendaten['Bezeichnung'][$i],0,1));
					}
				} ?>
			</td>
		</tr><?
	} ?>

  <tr>
    <td>
			<div class="stellendaten-topdiv">
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr class="stellendaten-header">
						<th align="right"><a href="index.php?go=Stellen_Anzeigen&order=ID&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?php echo $this->strID; ?></a></th>
						<th align="left"><a href="index.php?go=Stellen_Anzeigen&order=Bezeichnung&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?php echo $this->strName; ?></a></th>
						<th align="left"><a href="index.php?go=Stellen_Anzeigen&order=Bezeichnung_parent&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?php echo $strParentStelle; ?></a></th>
						<th style="width: 140px"><a href="index.php?go=Stellen_Anzeigen&order=last_time_id&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><? echo $strLastActivity; ?></a></th><?
						if ($has_shared_stelle) { ?>
							<th>&nbsp;</th><?
						} ?>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr><?php
					for ($i = 0; $i < count($this->stellendaten['ID']); $i++) {
						if ($this->formvars['order'] == 'Bezeichnung') {

							$first = strtoupper(mb_substr($this->stellendaten['Bezeichnung'][$i], 0, 1, 'UTF-8'));
							if (in_array($first, $umlaute)) {
								switch ($first) {
									case 'Ä': {
										$first='A';
									} break;
									case 'Ö': {
										$first='O';
									} break;
									case 'Ü': {
										$first='U';
									} break;
								}
							}
							if ($first != $nextfirst) { ?>
								<tr>
									<th align="left" style="border-top:1px solid #808080; margin:0px;">
										<? echo "<a name='".$first."'>".$first."</a>";
										$nextfirst=$first;
										if (in_array($first,$umlaute)) {
											switch ($first) {
												case 'Ä': {
													$nextfirst='A';
												} break;
												case 'Ö': {
													$nextfirst='O';
												} break;
												case 'Ü': {
													$nextfirst='U';
												} break;
											}
										} ?>
									</th><?
									$colspan = 5; ?>
									<td colspan="<? echo $colspan; ?>" align="right" style="border-top:1px solid #808080;"></td>
								</tr><?
							}
						} ?>
						<tr class="listen-tr">
							<td align="right"><?php echo $this->stellendaten['ID'][$i]; ?></td>
							<td><?php echo $this->stellendaten['Bezeichnung'][$i]; ?></td>
							<td><?php echo $this->stellendaten['Bezeichnung_parent'][$i]; ?></td>
							<td><?php echo $this->stellendaten['last_time_id'][$i]; ?></td><?
							if ($has_shared_stelle) { ?>
								<td><?
									if ($this->stellendaten['show_shared_layers'][$i]) { ?>
										<a href="javascript:void(0)" onclick="message([{'type': 'info', 'msg': '<? echo $strShowSharedLayer; ?>'}])">
											<i class="fa fa-share-alt"title="<? echo $strShowSharedLayer; ?>"></i>
										</a><?
									}
									else { ?>
										&nbsp; <?
									} ?>
								</td><?
							} ?>
							<td>
								<a href="index.php?go=Stelleneditor&selected_stelle_id=<? echo $this->stellendaten['ID'][$i]; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>" title="<?php echo $this->strChange; ?>">
									<i class="fa fa-pencil"></i>
								</a>
							</td>
							<td>
								<a href="index.php?go=Layer2Stelle_Reihenfolge&selected_stelle_id=<? echo $this->stellendaten['ID'][$i]; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>" title="Layer">
									<i class="fa fa-list"></i>
								</a>
							</td>
							<td>
								<a href="javascript:Bestaetigung('index.php?go=Stelle_Löschen&selected_stelle_id=<? echo $this->stellendaten['ID'][$i]; ?>&order=<? echo $this->formvars['order']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>','Wollen Sie die Stelle \'<? echo $this->stellendaten['Bezeichnung'][$i]; ?>\' wirklich löschen?')" title="<?php echo $this->strDelete; ?>">
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
