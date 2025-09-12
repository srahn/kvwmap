<link rel="stylesheet" href="plugins/ukos/styles/styles.css" type="text/css">
<script language="javascript" type="text/javascript">
</script>
<h2>Doppikobjekte</h2>
<div class="ukos-doppikobjekte-background">
<?php
	$icon = array(
		0 => 'fa-map-marker',
		1 => 'fa-line-chart',
		2 => 'fa-area-chart'
	);

	array_walk(
		$this->ukos_doppikklassen,
		function($klasse) use ($icon) {
			echo '
				<a href="index.php?go=ukos_new_doppikobjekt&schema_name=' . $klasse['schema_name'] . '&table_name=' . $klasse['table_name'] . '&geometry_type=' . $klasse['geometry_type'] .'">
					<div class="ukos-doppikobjekt">
						<i class="fa ' . $icon[$klasse['geometry_type']] . ' ukos-map-icon" aria-hidden="true"></i>' .
					ucwords(sonderzeichen_umwandeln_reverse(ucfirst($klasse['doppik_objekt_name']))) . '
					</div>
				</a>
			';
		}
	);
?>
</div>