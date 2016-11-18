<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="<? echo BG_DEFAULT; ?>" style="height: 25px;background: linear-gradient(<? echo BG_GLEATTRIBUTE; ?> 0%, <? echo BG_DEFAULT ?> 100%);">
  <tr> 
	  <td width="50%" align="right" valign="middle"><span class="fett px20"><?php echo $this->Stelle->Bezeichnung; ?></span></td>
		<td width="45%" align="left">
			<? if($this->user->rolle->hist_timestamp != '') echo '<a href="index.php?go=setHistTimestamp" title="Zeitpunkt auf aktuell setzen"><h2 style="color: #a82e2e;">Zeitpunkt ALKIS-Historie: '.$this->user->rolle->hist_timestamp.'</h2></a>'; ?>
		</td>
		<td width="5%" align="right">
			<?php $params = $this->user->rolle->get_layer_params($this->Stelle->selectable_layer_params, $this->pgdatabase);
			if (!empty($params)) { ?>
				<table>
					<tr width="100%" align="center">
						<td>
							<div style="position: relative;">
								<div style="position: relative;">
									<i id="openLayerParamBarIcon" class="fa fa-bars" style="margin: 5 5 5 5" onclick="toggleLayerParamsBar();"></i>
								</div>
								<?php
									include(LAYOUTPATH . "snippets/params.php");
								?>
							</div>
						</td>
					</tr>
				</table><?php
			} ?>
		</td>
	</tr>
</table>