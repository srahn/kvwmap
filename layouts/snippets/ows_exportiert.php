<?php
  include(LAYOUTPATH.'languages/ows_export_'.rolle::$language.'.php');
?>
<script>
	function copyToClipBoard(element) {
		var textarea = element.previousSibling;
		textarea.select();
		textarea.setSelectionRange(0, 99999); /*For mobile devices*/
		document.execCommand("copy");
		message([{msg: 'Text in Zwischenablage übernommen.', type: 'notice'}]);
	}
</script>
<style>
	.fa-clipboard {
		margin-left: 5px;
  	vertical-align: top;
	}
</style>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center">
    <td colspan="2"><h2><?php echo $this->titel; ?></h2></td>
  </tr><?php
	if ($this->mapfile) { ?>
		<tr>
			<td align="right">&nbsp;</td>
			<td align="left"><? echo $strExported1; ?></td>
		</tr><?php
			if ($this->formvars['nurVeroeffentlichte'] == 1) { ?>
				<tr>
					<td align="right">&nbsp;</td>
					<td align="left"><?php echo $strGefilterteLayerList; ?>:<br><? echo implode(', ', $this->gefilterte_layer); ?></td>
				</tr><?php
			} ?>
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
		</tr>
		<tr>
			<td align="right">&nbsp;</td>
			<td align="left"><b>WMS in HTML-Seite einbinden</b></td>
		</tr>
		<tr>
			<td align="right">&nbsp;</td>
			<td align="left" style="line-break: auto">
				Im Header einbinden:<br>
				<textarea id="headText" style="width: 860px" rows="13">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
<script>
  function init() {
    var map = L.map('map').setView([<?php echo $this->center->y; ?>, <?php echo $this->center->x; ?>], 13);
    var wmsLayer = L.tileLayer.wms("<?php echo $this->wms_onlineresource; ?>", {
        layers: '<? echo implode(',', $this->exportierte_layer); ?>',
        format: 'image/png',
        transparent: true,
        attribution: "<? echo URL; ?>"
    }).addTo(map);
  }
</script>
				</textarea><i class="fa fa-clipboard" aria-hidden="true" onclick="copyToClipBoard(this);"></i><br>
				Rufen Sie die Methode init() im Body-Tag auf:<br>
			  <textarea id="bodyText" style="width: 150px" rows="1"><body onload="init()"></textarea><i class="fa fa-clipboard" aria-hidden="true" onclick="copyToClipBoard(this);"></i><br>
				Fügen Sie dann ein div mit der id="map" an der Stelle im body ein wo die Karte erscheinen soll.<br>
			  <textarea id="divText" style="width: 360px" rows="1"><div id="map" style="width: 100%; height: 100%"></div></textarea><i class="fa fa-clipboard" aria-hidden="true" onclick="copyToClipBoard(this);"></i><br>
				Die Darstellung sieht dann so aus:<br>
				<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"/>
				<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
				<div id="wms_export_sample_map" style="width: 860px; height: 300px"></div>
				<script>
			    var wms_export_sample_map = L.map('wms_export_sample_map').setView([<?php echo $this->center->y; ?>, <?php echo $this->center->x; ?>], 10);
			    var wmsLayer = L.tileLayer.wms("<?php echo $this->wms_onlineresource; ?>", {
			        layers: '<? echo implode(',', $this->exportierte_layer); ?>',
			        format: 'image/png',
			        transparent: true,
			        attribution: "<? echo URL; ?>"
			    }).addTo(wms_export_sample_map);
			</script>
			</td>
		</tr><?
	} ?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="button" value="zurück zum Formular" onclick="document.location.href='index.php?go=WMS_Export&mapfile_name=<?php echo $this->formvars['mapfile_name']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>'"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<b><?php echo $strExportList; ?>:</b>
			<ul><?
				echo implode('', array_map(
					function($mapfile) {
						return '<li>' . $mapfile . '
							<a title="Map-Datei ' . $mapfile . ' ändern" href="index.php?go=WMS_Export&mapfile_name=' . $mapfile . '&csrf_token=' . $_SESSION['csrf_token'] . '"><i class="fa fa-pencil" aria-hidden="true" style="margin-left: 10px; color: firebrick"></i></a>
							<a title="Map-Datei ' . $mapfile . ' löschen" href="index.php?go=ows_export_loeschen&mapfile_name=' . $mapfile . '&csrf_token=' . $_SESSION['csrf_token'] . '"><i class="fa fa-trash" aria-hidden="true" style="margin-left: 10px; color: firebrick"></i></a>
						</li>';
					},
					$this->mapfiles_der_stelle
				)); ?>
			</ul>
		</td>
	</tr>
</table>
