<h2 style="margin-top: 10px; margin-bottom: 20px">URL's zum Laden der Ressource hinzufügen</h2>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>"><?
	if ($this->Fehlermeldung != '') { ?>
		<tr>"
			<td colspan="2"><? echo include(SNIPPETS . 'Fehlermeldung.php'); ?></td>
		</tr><?php
	}
	else {
		if ($this->formvars['action'] == 'URLs erzeugen') { ?>
			<tr>
				<th>Download-URL</th><td><? echo $this->ressource->get('download_url'); ?></td>
			</tr>
			<tr>
				<th>URL-Muster</th><td><? echo $this->formvars['url_pattern']; ?></td>
			</tr>
			<tr>
				<th>Kmq x</th><td><? echo round($this->formvars['x'] / 1000); ?></td>
			</tr>
			<tr>
				<th>Kmq y</th><td><? echo round($this->formvars['y'] / 1000); ?></td>
			</tr>
			<tr>
				<th>Radius</th><td><? echo $this->formvars['radius']; ?> km</td>
			</tr>
			<tr>
				<th>Kachelgröße</th><td><? echo $this->formvars['tile_size'] ?> m</td>
			</tr>
			<tr>
				<th>URL's-Datei</th><td><? echo $this->ressource->get_urls_file(); ?></td>
			</tr>
			<tr>
				<th style="vertical-align: top">Folgende URL's<br>wurden an die Datei<br>angehängt:</th><td><? echo implode('<br>', $this->new_urls); ?></td>
			</tr><?php
		}
		else { ?>
			<tr> 
				<td align="center" colspan="2">
					<h2><? echo $this->ressource->get('bezeichnung'); ?> (id: <?php echo $this->ressource->get('id'); ?>)</h2>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center"><?php
					if ($this->Meldung == $strSuccess OR $this->Meldung == '') {
						$bgcolor = BG_FORM;
					}
					else {
						$this->Fehlermeldung = $this->Meldung;
						include('Fehlermeldung.php');
						$bgcolor = BG_FORMFAIL;
					} ?>
				</td>
			</tr>
			<tr>
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">Download-URL's Datei</th>
				<td style="border-bottom:1px solid #C3C7C3">
					<input name="urls_file" type="text" value="<?php echo $this->ressource->get_urls_file(); ?>" style="width: 400px" disabled>
				</td>
			</tr>
			<tr>
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">Download-URL</th>
				<td style="border-bottom:1px solid #C3C7C3">
					<input name="download_url" type="text" value="<?php echo $this->ressource->get('download_url'); ?>" style="width: 400px" disabled>
				</td>
			</tr>
			<tr>
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">Mittelpunkt X-Wert</th>
				<td style="border-bottom:1px solid #C3C7C3">
					<input name="x" type="text" placeholder="Koordinate Rechtswert" style="width: 150px"> m
				</td>
			</tr>
			<tr>
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">Mittelpunkt Y-Wert</th>
				<td style="border-bottom:1px solid #C3C7C3">
					<input name="y" type="text" placeholder="Koordinate Hochwert" style="width: 150px"> m
				</td>
			</tr>
			<tr>
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">URL-Muster</th>
				<td style="border-bottom:1px solid #C3C7C3">
					<input name="url_pattern" type="text" placeholder="z.B. ${URL}dgm_${kmq_x}-${kmq_y}.zip" value="<?php echo $this->ressource->get('download_url_pattern'); ?>" style="width: 400px"> <span data-tooltip="kmq_x und kmq_y sind die Werte der linken unteren Ecke des Kilometerquadrates in welches die angegebenen Koordinaten x und y fallen, geteilt durch 1000."></span>
				</td>
			</tr>
			<tr>
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">Radius</th>
				<td style="border-bottom:1px solid #C3C7C3">
					<input name="radius" type="number" max="50" placeholder="in Kilometern" style="width: 100px"> km
				</td>
			</tr>
			<tr>
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">Kachelgröße</th>
				<td style="border-bottom:1px solid #C3C7C3">
					<input name="tile_size" type="text" placeholder="in Metern" style="width: 100px"> m
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="hidden" name="go" value="metadata_add_urls">
					<input type="hidden" name="ressource_id" value="<? echo $this->ressource->get_id(); ?>">
					<input type="button" name="cancel" value="<? echo $this->strCancel; ?>" onclick="document.location.href = 'index.php?go=Layer-Suche_Suchen&selected_layer_id=<? echo METADATA_RESSOURCES_LAYER_ID; ?>&operator_id==&value_id=<? echo $this->ressource->get_id(); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>'">
					<input type="submit" name="action" value="URLs erzeugen">
				</td>
			</tr><?php
		}
	}?>
</table> 