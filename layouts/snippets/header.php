<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="<? echo BG_DEFAULT; ?>" style="height: 100%; background: linear-gradient(<? echo BG_GLEATTRIBUTE; ?> 0%, <? echo BG_DEFAULT ?> 100%);">

  <tr> 
	  <td width="31%" align="right" valign="middle"><span class="fett px20"><?php echo $this->Stelle->Bezeichnung; ?></span></td>
		<td width="30%" align="left">
			<? if ($this->user->rolle->hist_timestamp_de != '') echo '<a href="index.php?go=setHistTimestamp" title="Zeitpunkt auf aktuell setzen"><h2 style="color: #a82e2e;">Zeitpunkt ALKIS-Historie: ' . $this->user->rolle->hist_timestamp_de . '</h2></a>'; ?>
		</td>
		<td width="30%" align="right">
			<table>
				<tr>
					<td>Adresse suchen:</td><td><? include(SNIPPETS.'geo_name_search.php'); ?></td>
				</tr>
			</table>
		</td>
		<td width="1%" align="right">
			<? include(SNIPPETS . 'params.php'); ?>
		</td>		
		<td width="1%">
			<div title="Einstellungen">
				<i class="fa fa-user header-button" aria-hidden="true" onclick="
						$('#user_options').toggle();
						$('#sperr_div').toggle()
				"></i>
				<div id="user_options" class="user-options">
					<div style="position: absolute; top: 2px; right: 2px; cursor: pointer">
						<img style="border:none" src="graphics/exit2.png" onclick="$('#user_options').toggle();">
					</div>
					<div class="user-options-header">
						Angemeldet als: <?php echo $this->user->login_name; ?>
					</div>
					<div class="user-options-section-header">
						<i class="fa fa-tasks options-button"></i>in Stelle:
					</div><?php
					$this->user->Stellen = $this->user->getStellen(0);
					if (count($this->user->Stellen['ID']) > 21) { ?>
						<select onchange="window.location.href='index.php?Stelle_ID=' + this.value" style="margin: 0px 3px 0px 6px"><?
							foreach (array_keys($this->user->Stellen['ID']) AS $id) {
								echo '
									<option value="' . $this->user->Stellen['ID'][$id] . '"' . ($this->user->Stellen['ID'][$id] == $this->user->stelle_id ? ' selected' : '') . '>' .
										$this->user->Stellen['Bezeichnung'][$id] . '
									</option>
								';
							} ?>
						</select><?
					}
					else {
						foreach (array_keys($this->user->Stellen['ID']) AS $id) { ?>
							<div
								class="user-option"
								style="margin-left: 0px" <?
								if ($this->user->Stellen['ID'][$id] != $this->user->stelle_id) { ?>
									onclick="window.location.href='index.php?Stelle_ID=<? echo $this->user->Stellen['ID'][$id]; ?>'" <?
								} ?>
							><? echo $this->user->Stellen['Bezeichnung'][$id];
							if ($this->user->Stellen['ID'][$id] == $this->user->stelle_id) {
								?> <i class="fa fa-check" aria-hidden="true" style="color: #9b2434; margin: 0px 0px 0px 7px"></i><?
							} ?>
							</div><?
						}
					} ?>
					<div class="options-devider"></div>
					<div
						class="user-option"
						onclick="window.location.href='index.php?go=Stelle_waehlen&show_layer_parameter=1&hide_stellenwahl=1'"
					><i class="fa fa-ellipsis-v options-button"></i>Einstellungen</div>
				<div class="options-devider"></div>
				<div
					class="user-option"
					onclick="window.location.href='index.php?go=logout'"
				><i class="fa fa-sign-out options-button"></i>Logout</div>
				</div>
			</div>
		</td>
	</tr>
</table>