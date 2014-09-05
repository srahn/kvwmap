<?php
	include (PDFCLASSPATH."class.ezpdf.php");
  $pdf=new Cezpdf();
	$pdf->selectFont(PDFCLASSPATH.'fonts/Courier.afm');
	$table_data = array();
	foreach ($data AS $key => $value) {
		if (!is_array($value)) {
			$table_data[] = array('key' => utf8_decode($key), 'value' => utf8_decode($value));
		}
	}
	$num_baueme = count($data['wood_species']);
	
	for ($i = 0; $i < $num_baueme; $i++) {
		$nr = $i + 1;
		$table_data[] = array('key' => $nr . '. Standort (lat, lng)', 'value' => $data['latitude'][$i] . ', ' . $data['longitude'][$i], 'url' => 'http://maps.google.de?q=' . $data['latitude'][$i] . ',' . $data['longitude'][$i]).'&';
		$table_data[] = array('key' => $nr . '. Baumart', 'value' => $data['wood_species'][$i]);
		$table_data[] = array('key' => utf8_decode($nr . '. Stammumfang, 1.3m Höhe'), 'value' => $data['trunk_circumference'][$i] . ' cm');
		$table_data[] = array('key' => $nr . '. Kronendurchmesser', 'value' => $data['crown_diameter'][$i]. ' m');
		if (array_key_exists('tree_image', $data) && array_key_exists($i, $data['tree_image'])) {
			$table_data[] = array('key' => 'Baumbild', 'value' => $antrag_id . '_Baum_' . $i . "_Bild_"  . basename($data['tree_image'][$i]));
		}
	}

	$pdf->ezTable(
		$table_data,
		array('key' => 'Parameter', 'value' => 'Wert'),
		utf8_decode('Antrag zur Baumfällgenehmigung Nr: ' . $antrag_id),
		array(
			'showHeadings' => 0,
			'shaded' => 1,
			'xPos' => 50,
			'xOrientation' => 'right',
			'width' => 500,
			'cols' => array(
				'key' => array(
					'width' => 170
				),
				'value' => array(
						'width'=> 360,
						'link' => 'url'
				)
			)
		)
	);
	
	$pdf_output = $pdf->ezOutput();
?>
