<?php
  include(LAYOUTPATH.'languages/ows_export_'.$this->user->rolle->language.'.php');
?>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center">
    <td colspan="2"><h2><?php echo $this->titel; ?></h2></td>
  </tr><?php
	if ($this->mapfile) { ?>
		<tr>
			<td align="right">&nbsp;</td>
			<td align="left"><? echo $strExported1; ?></td>
		</tr>
		<tr>
			<td align="right">&nbsp;</td>
			<td align="left"><b>Web Map Service (WMS)</b></td>
		</tr>
		<tr>
			<td align="right">&nbsp;</td>
			<td align="left">
				<? echo $strExported2; ?>:<br>
				<span class="fett"><a href="<?php echo $this->wms_onlineresource; ?>REQUEST=GetCapabilities&VERSION=<?php echo SUPORTED_WMS_VERSION; ?>&SERVICE=WMS" target="_blank"><?php echo $this->wms_onlineresource; ?>request=getCapabilities&amp;VERSION=<?php echo SUPORTED_WMS_VERSION; ?>&amp;SERVICE=wms</a></span><p>
			</td>
		</tr>
		<tr>
			<td align="right">&nbsp;</td>
			<td align="left" style="line-break: auto"><? echo $strExported3; ?><br>
				<span class="fett"><a href="<?php echo $this->getMapRequestExample; ?>" target="_blank"><?php echo $this->getMapRequestExample; ?></a></span></td>
		</tr>
		<tr>
			<td align="right">&nbsp;</td>
			<td align="left"><b>Web Feature Service (WFS)</b></td>
		</tr>
		<tr>
			<td align="right">&nbsp;</td>
			<td align="left">
				<? echo $strExported2; ?>:<br>
				<span class="fett"><a href="<?php echo $this->wms_onlineresource; ?>REQUEST=GetCapabilities&VERSION=<?php echo SUPORTED_WFS_VERSION; ?>&SERVICE=WFS" target="_blank"><?php echo $this->wms_onlineresource; ?>request=GetCapabilities&amp;VERSION=<?php echo SUPORTED_WFS_VERSION; ?>&amp;SERVICE=WFS</a></span><p>
			</td>
		</tr>
		<tr>
			<td align="right">&nbsp;</td>
			<td align="left" style="line-break: auto"><? echo $strExportedWFSExample; ?><br>
				<span class="fett"><a href="<?php echo $this->getFeatureRequestExample; ?>" target="_blank"><?php echo $this->getFeatureRequestExample; ?></a></span></td>
		</tr><?
	} ?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="button" value="zurück zum Formular" onclick="document.location.href='index.php?go=WMS_Export&mapfile_name=<?php echo $this->formvars['mapfile_name']; ?>'"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<b><?php echo $strExportList; ?>:</b>
			<ul><?
				echo implode('', array_map(
					function($mapfile) {
						return '<li>' . $mapfile . '
							<a title="Map-Datei ' . $mapfile . ' ändern" href="index.php?go=WMS_Export&mapfile_name=' . $mapfile . '"><i class="fa fa-pencil" aria-hidden="true" style="margin-left: 10px; color: firebrick"></i></a>
							<a title="Map-Datei ' . $mapfile . ' löschen" href="index.php?go=ows_export_loeschen&mapfile_name=' . $mapfile . '"><i class="fa fa-trash" aria-hidden="true" style="margin-left: 10px; color: firebrick"></i></a>
						</li>';
					},
					$this->mapfiles_der_stelle
				)); ?>
			</ul>
		</td>
	</tr>
</table>
