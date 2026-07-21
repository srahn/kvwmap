<style>

	.inner-table {
			border-collapse: collapse;
			width: auto;
			min-width: 50%;
			border: 1px solid #ccc;
			font-family: Arial, sans-serif;
			font-size: 14px;
		}

		.inner-table th,
		.inner-table td {
			border: 1px solid #ccc;
			padding: 6px 10px;
			text-align: left;
			vertical-align: top;
		}

		.inner-table th {
			background-color: #f7f7f7;
			font-weight: bold;
			white-space: nowrap;
		}

		.inner-table tr:nth-child(even) {
			background-color: #fcfcfc;
		}
</style>
<h2 style="margin: 10px">Rechte</h2>
Angelegt mit Parametern:<br>
layer_id: <? echo $this->formvars['layer_id']; ?><br>
feature_id: <? echo $this->formvars['feature_id']; ?><br>
rechteart_id: <? echo $this->formvars['rechteart_id']; ?><br>
Buffer: <? echo ($this->formvars['buffer'] ? $this->formvars['buffer'] : '0'); ?> m<br><br><?

if ($this->layer !== null) {
	echo 'Layer mit id ' . $this->formvars['layer_id'] . ' gefunden.';
}

if ($this->feature !== null) {
	echo '<br>Feature mit id ' . $this->formvars['feature_id'] . ' gefunden.';
}

if ($this->Fehlermeldung != '') {
	include(SNIPPETS . 'Fehlermeldung.php');
}
$keys = array('id', 'bezeichnung', 'flurstueckszuordnung', 'eigentuemerzuordnung', 'teilprojekt_id', 'feature_id', 'layer_id', 'flaeche');
$aliases = array('ID', 'Bezeichnung', 'Flurstueckszuordnung', 'Eigentuemerzuordnung', 'Teilprojekt-ID', 'Feature-ID', 'Layer-ID', 'Fläche&nbsp;in&nbsp;m²'); ?>
	<table class="inner-table">
		<thead>
			<tr>
				<?php foreach ($aliases as $alias): ?>
				<th><?= $alias ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->rechte as $recht): ?>
				<tr>
				<?php foreach ($keys as $key): ?>
					<td><?= htmlspecialchars($recht->get($key)) ?></td>
				<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>