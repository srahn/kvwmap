<script type="text/javascript">
	function bevoelkerungShowMap(jahr) {
		var geschlecht = document.getElementById("bevoelkerungGeschlecht").value,
				datenreihe = document.getElementById("bevoelkerungDatenreihe").value,
				jahr = document.getElementById("bevoelkerungMapJahrRange").value,
				mapImageFile = 'plugins/bevoelkerung/img/maps/' + datenreihe + '_' + geschlecht + '_' + jahr + '.jpg';

		document.getElementById("bevoelkerungMapJahr").innerHTML = jahr;
		document.getElementById("bevoelkerungMapImage").src = mapImageFile;
	}
</script>
<br>
<h2 style="margin-bottom: 10px">Einwohnerdaten</h2>
Datenreihe
<select id="bevoelkerungDatenreihe" onchange="bevoelkerungShowMap()"/><?php
	foreach ($this->params['datenreihe']['options'] AS $option) { ?>
		<option value="<?php echo $option['value']; ?>"><?php echo $option['output']; ?></option><?php
	}	?>
</select>

Geschlecht <select id="bevoelkerungGeschlecht" onchange="bevoelkerungShowMap(document.getElementById('bevoelkerungMapJahrRange').value)">
	<option value="g" selected>Gesamt</option>
	<option value="m">männlich</option>
	<option value="w">weiblich</option>
</select>
im Jahr <input id="bevoelkerungMapJahrRange" type="range" min="<?php echo $this->von_jahr; ?>" max="<?php echo $this->bis_jahr; ?>" value="<?php echo $this->von_jahr; ?>" step="1" onchange="bevoelkerungShowMap(this.value)" />
20<span id="bevoelkerungMapJahr"><?php echo $this->von_jahr; ?></span><br>
<img id="bevoelkerungMapImage" src="plugins/bevoelkerung/img/maps/summe_g_<?php echo $this->von_jahr; ?>.jpg" style="margin: 12px; border: 1px solid #cccccc;">
