<br><h2>Landesdienst</h2>
<br>
<style>
	.text-left li {
		text-align: left;
		color: #0a1288;
		margin-top: 12px;
		margin-bottom: 12px;
		margin-left: 50px;
		margin-right: 50px;
	}
</style>
<div class="text-left">
	Die Map-Datei für den WMS und WFS des Dienstes der die Zusammenzeichnungen der F-Pläne von Niedersachsen ausgibt wurde aktualisiert.<br>
	Die Dienstadresse für WMS und WFS lautet: <? echo $this->ows_onlineresource; ?><br>
	Und hier die Capabilities für <a target="_blank" href="<? echo $this->ows_onlineresource; ?>?Service=WMS&Request=GetCapabilities&Version=1.3.0">WMS</a> und <a target="_blank" href="<? echo $this->ows_onlineresource; ?>?Service=WFS&Request=GetCapabilities&Version=1.0.0">WFS</a> zum Anzeigen.
</div>
<br>
Folgende Layer wurden angelegt:<br>
<div class="text-left">
	<ul><?
		echo implode(
			'',
			array_map(
				function($layer) { ?>
					<li><b><? echo $layer; ?></b> [ <a target="_blank" href="<? echo $this->ows_onlineresource; ?>?request=GetMetadata&layer=<? echo $layer; ?>">Metadaten</a> | <a href="index.php?go=Layereditor&selected_layer_id=<?php echo $this->layers_with_content[$layer]['id']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>">Ändern</a> ]</li><?
				},
				$this->layernames_with_content
			)
		); ?>
	</ul>
</div>