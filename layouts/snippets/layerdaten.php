<?php
	$first = $nextfirst = '';
	$has_sync_layer = array_reduce(
		$this->layerdaten['sync'],
		function($has_sync, $layerdaten) {
			return $has_sync OR $layerdaten;
		},
		0
	);
?><table border="0" cellpadding="5" cellspacing="0" width="1000px">
	<tr align="center">
		<td><h2><?php echo $this->titel; ?></h2></td>
	</tr><?
	if ($this->formvars['order']=="Name" or $this->formvars['order']=="Alias") { ?>
		<tr height="50px" valign="bottom">
			<td><?
				$umlaute=array("Ä","Ö","Ü");
				for ($i=0;$i<count($this->layerdaten['ID']);$i++) {
					if ($this->formvars['order']=="Name") {
						$anzeigename=$this->layerdaten['Bezeichnung'][$i];
					}
					if ($this->formvars['order']=="Alias") {
						$anzeigename=$this->layerdaten['alias'][$i];
					}
					if (!in_array(strtoupper(mb_substr($anzeigename,0,1,'UTF-8')),$umlaute) AND strtolower(mb_substr($anzeigename,0,1,'UTF-8')) != $first) {
						echo "<a href='#".strtoupper(mb_substr($anzeigename,0,1,'UTF-8'))."'><div class='menu abc'>".strtoupper(mb_substr($anzeigename,0,1,'UTF-8'))."</div></a>";
						$first=strtolower(mb_substr($anzeigename,0,1));
					}
				} ?>
			</td>
		</tr><?
	} ?>
	<tr>
		<td>
			<div class="layerdaten-topdiv">
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td>&nbsp;</td>
						<th align="left"><a href="index.php?go=Layer_Anzeigen&order=Layer_ID"><?php echo $this->strID; ?></a></th><?
						if ($this->formvars['order']=="Name") { ?>
							<th align="left">
								<a href="index.php?go=Layer_Anzeigen&order=Alias"><?php echo $this->strName; ?>&nbsp;[<?php echo $this->strAlias; ?>]</a>
							</th><?
						}
						if ($this->formvars['order']!="Alias" and $this->formvars['order']!="Name") { ?>
							<th align="left"><a href="index.php?go=Layer_Anzeigen&order=Name">
								<?php echo $this->strName; ?>&nbsp;[<?php echo $this->strAlias; ?>]</a>
							</th><?
						}
						if ($this->formvars['order']=="Alias") { ?>
							<th align="left"><a href="index.php?go=Layer_Anzeigen&order=Name">
								<?php echo $this->strAlias; ?>&nbsp;[<?php echo $this->strName; ?>]</a>
							</th><?
						} ?>
						<th align="left">
							<a href="index.php?go=Layer_Anzeigen&order=Gruppenname"><?php echo $this->strGroup; ?></a>
						</th><?
						if ($has_sync_layer) { ?>
							<th align="left">
								<a href="#" onclick="toggleSyncLayer()"><i class="fa fa-mobile" aria-hidden="true" style="padding: 3px; color: firebrick"></i></a>
							</th><?
						} ?>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr><?
					for ($i = 0; $i < count($this->layerdaten['ID']); $i++) {
						if ($this->formvars['order'] == "Name" or $this->formvars['order']=="Alias") {
							if ($this->formvars['order'] == "Name" or $this->layerdaten['alias'][$i]!='') {
								if ($this->formvars['order'] == "Name") {
									$anzeigename=$this->layerdaten['Bezeichnung'][$i];
								}
								if ($this->formvars['order'] == "Alias") {
									$anzeigename=$this->layerdaten['alias'][$i];
								}
								$first=strtoupper(mb_substr($anzeigename,0,1,'UTF-8'));
								if (in_array($first,$umlaute)) {
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
										</th>
										<td colspan="<? echo ($has_sync_layer ? '6' : '5'); ?>" align="right" style="border-top:1px solid #808080;"></td>
									</tr><?
								}
							}
						}
						if ($this->formvars['order']!="Alias" or ($this->formvars['order']=="Alias" and $this->layerdaten['alias'][$i]!='')) { ?>
							<tr class="listen-tr<? echo (($has_sync_layer AND $this->layerdaten['sync'][$i]) ? ' sync' : ' no-sync'); ?>">
								<td>&nbsp;</td>
								<td><?php echo $this->layerdaten['ID'][$i]; ?>&nbsp;&nbsp;</td>
								<td><?php
									if ($this->formvars['order']!="Alias") {
										echo $this->layerdaten['Bezeichnung'][$i];
										if ($this->layerdaten['alias'][$i]) {
											echo '&nbsp;['.$this->layerdaten['alias'][$i].']';
										}
									}
									if ($this->formvars['order']=="Alias") {
										echo $this->layerdaten['alias'][$i].'&nbsp;['.$this->layerdaten['Bezeichnung'][$i].']';
									} ?>
								</td>
								<td><?php echo $this->layerdaten['Gruppe'][$i]; ?></td>
								<td><?php echo ($this->layerdaten['sync'][$i] ? '<i class="fa fa-mobile" aria-hidden="true"></i>' : ''); ?></td>
								<td><a href="index.php?go=Layereditor&selected_layer_id=<? echo $this->layerdaten['ID'][$i]; ?>" title="<?php echo $this->strChange; ?>"><i class="fa fa-pencil"></a></td>
								<td><a href="javascript:Bestaetigung('index.php?go=Layer_Löschen&selected_layer_id=<? echo $this->layerdaten['ID'][$i]; ?>&order=<? echo $this->formvars['order']; ?>','Wollen Sie den Layer <?php echo $this->layerdaten['Bezeichnung'][$i]; ?> wirklich löschen?')" title="<?php echo $this->strDelete; ?>"><i class="fa fa-trash-o"></i></a></td>
							</tr><?
						}
					} // End for ?>
				</table>
			</div>
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
	</tr>
</table>
<input type="hidden" name="order" value="<?php echo $this->formvars['order']; ?>">
