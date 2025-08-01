<script type="text/javascript">
	function bevoelkerungShowCharts(jahr) {
		var geschlecht = document.getElementById("bevoelkerungGeschlecht").value;
				chartFile = 'plugins/bevoelkerung/img/tc_' + geschlecht + '_' + jahr + '.png',
				useMap = '#tc_' + geschlecht + '_' + jahr + '_areas';
//		console.log('show charts in jahr ' + jahr + ' geschlecht: ' + geschlecht);
//		console.log('set usemap: ' + useMap);
		document.getElementById("bevoelkerungChartJahr").innerHTML = jahr;
		document.getElementById("bevoelkerungChartImage").src = chartFile;
		document.getElementById("bevoelkerungChartImage").setAttribute("usemap", useMap);
	}
</script>
<br>
<h2 style="margin-bottom: 10px">Verhältnis der Bevölkerung mit</h2>Geschlecht <select id="bevoelkerungGeschlecht" onchange="bevoelkerungShowCharts(document.getElementById('bevoelkerungChartJahrRange').value)">
	<option value="g" selected>Gesamt</option>
	<option value="m">männlich</option>
	<option value="w">weiblich</option>
</select>
im Jahr <input id="bevoelkerungChartJahrRange" type="range" min="<?php echo $this->von_jahr; ?>" max="<?php echo $this->bis_jahr; ?>" value="<?php echo $this->von_jahr; ?>" step="1" onchange="bevoelkerungShowCharts(this.value)" />
20<span id="bevoelkerungChartJahr"><?php echo $this->von_jahr; ?></span><br>
<img id="bevoelkerungChartImage" src="plugins/bevoelkerung/img/tc_g_<?php echo $this->von_jahr; ?>.png" usemap="#tc_g_<?php echo $this->von_jahr; ?>_areas" style="margin: 12px; border: 1px solid #cccccc;">
<?php
foreach (glob(PLUGINS . 'bevoelkerung/img/*_areas.html') AS $area_file) {
	echo file_get_contents($area_file);
}
?>
