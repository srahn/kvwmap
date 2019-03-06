<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="<? echo BG_DEFAULT; ?>" style="height: 100%; background: linear-gradient(<? echo BG_GLEATTRIBUTE; ?> 0%, <? echo BG_DEFAULT ?> 100%);">

  <tr> 
	  <td width="35%" align="right" valign="middle"><span class="fett px20"><?php echo $this->Stelle->Bezeichnung; ?></span></td>
		<td width="30%" align="left">
			<? if($this->user->rolle->hist_timestamp != '') echo '<a href="index.php?go=setHistTimestamp" title="Zeitpunkt auf aktuell setzen"><h2 style="color: #a82e2e;">Zeitpunkt ALKIS-Historie: '.$this->user->rolle->hist_timestamp.'</h2></a>'; ?>
		</td>
		<td width="30%" align="right">
			<table>
				<tr>
					<td>Adresse suchen:</td><td><? include(SNIPPETS.'geo_name_search.php'); ?></td>
				</tr>
			</table>
		</td>
		<td width="5%" align="right">
			<? include(SNIPPETS . 'params.php'); ?>
		</td>
	</tr>
</table>