<?
  include(LAYOUTPATH.'languages/ows_export_'.$this->user->rolle->language.'.php');
?>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center">
    <td colspan="2"><h2><?php echo $this->titel; ?></h2></td>
  </tr><?
	if ($this->mapfile) { ?>
		<tr>
			<td align="right">&nbsp;</td>
			<td align="left"><? echo $strExported1; ?></td>
		</tr>
		<tr>
			<td align="right">&nbsp;</td>
			<td align="left"><? echo $strExported2; ?>:<br>
				<span class="fett"><a href="<?php echo $this->wms_onlineresource; ?>REQUEST=getCapabilities&VERSION=<?php echo SUPORTED_WMS_VERSION; ?>&SERVICE=wms" target="_blank"><?php echo $this->wms_onlineresource; ?>request=getCapabilities&amp;VERSION=<?php echo SUPORTED_WMS_VERSION; ?>&amp;SERVICE=wms</a></span></td>
		</tr>
		<tr>
			<td align="right">&nbsp;</td>
			<td align="left" style="line-break: auto"><? echo $strExported3; ?><br>
				<span class="fett"><a href="<?php echo $this->getMapRequestExample; ?>" target="_blank"><?php echo $this->getMapRequestExample; ?></a></span></td>
		</tr><?
	} ?>
	<tr>
		<td>
			
		</td>
		<td>
			<h3>Folgende Map-Files wurden bereits in dieser Stelle exportiert:</h3>
		</td>
		</tr>
	</tr>
	<tr>
		<td>
			
		</td>
		<td>
			<ul><?
				echo implode('', array_map(
					function($mapfile) {
						return '<li>' . $mapfile . ' <a title="Map-Datei ' . $mapfile . ' ändern" href="index.php?go=WMS_Export&mapfile_name=' . $mapfile . '"><i class="fa fa-pencil" aria-hidden="true" style="margin-left: 10px; color: firebrick"></i></a></li>';
					},
					$this->mapfiles_der_stelle
				)); ?>
			</ul>
		</td>
		</tr>
	} ?>
</table>
