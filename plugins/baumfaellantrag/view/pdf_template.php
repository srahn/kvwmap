<?php
  error_reporting(E_ERROR | E_PARSE);
	include (CLASSPATH.'class.ezpdf.php');
  $pdf=new Cezpdf();
	$pdf->selectFont(WWWROOT . APPLVERSION . 'fonts/PDFClass/Courier.afm');
	$table_data = array();

  $table_data[] = array('key' => '', 'value' => 'Angaben zum Antragsteller');
  $table_data[] = array('key' => 'Vorname', 'value' => $data['forename']);
  $table_data[] = array('key' => 'Nachname', 'value' => $data['surname']);
  $table_data[] = array('key' => 'Straßenname', 'value' => $data['streetName']);
  $table_data[] = array('key' => 'Hausnummer', 'value' => $data['streetNo']);
  $table_data[] = array('key' => 'Ort', 'value' => $data['place']);
  $table_data[] = array('key' => 'PLZ', 'value' => $data['postcode']);
  $table_data[] = array('key' => 'Telefon', 'value' => $data['phone']);
  $table_data[] = array('key' => 'Fax', 'value' => $data['fax']);
  $table_data[] = array('key' => 'E-Mail Absender', 'value' => $data['email']);
  $table_data[] = array('key' => 'Eigentümerverhältnis', 'value' => $data['ownerinfo']);
  $table_data[] = array('key' => 'Vollmacht', 'value' => $data['mandateReference']);
  $table_data[] = array('key' => 'Begründung', 'value' => $data['reason']);
  
  $table_data[] = array('key' => '', 'value' => 'Angaben zum Flurstück (gilt nur für Position vom 1. Baum)');
  $table_data[] = array('key' => 'Länderschlüssel', 'value' => $data['cadastre_stateId']);
  $table_data[] = array('key' => 'Landkreisschlüssel', 'value' => $data['cadastre_districtId']);
  $table_data[] = array('key' => 'Gemeindeschlüssel', 'value' => $data['cadastre_municipalityId']);
  $table_data[] = array('key' => 'Gemeindename', 'value' => $data['cadastre_municipalityName']);
  $table_data[] = array('key' => 'Gemarkungsschlüssel', 'value' => $data['cadastre_boundaryId']);
  $table_data[] = array('key' => 'Gemarkungsname', 'value' => $data['cadastre_boundaryName']);
  $table_data[] = array('key' => 'Flur', 'value' => $data['cadastre_sectionId']);
  $table_data[] = array('key' => 'Flurstückskennzeichen', 'value' => $data['cadastre_parcelId']);
  $table_data[] = array('key' => 'Flurstücksnummer', 'value' => $data['cadastre_parcelNo']);
  
  $table_data[] = array('key' => '', 'value' => 'Angaben zur zuständige Stelle (gilt nur für Position vom 1. Baum)');
  $table_data[] = array('key' => 'Nr.', 'value' => $data['authority_municipalityNr']);
  $table_data[] = array('key' => 'Name', 'value' => $data['authority_municipalityName']);
  $table_data[] = array('key' => 'Landkreis', 'value' => $data['authority_districtNr']);
  $table_data[] = array('key' => 'E-Mail', 'value' => $data['cadastre_parcelId']);
  $table_data[] = array('key' => 'Ansprechpartner', 'value' => $data['authority_contactPerson']);
  $table_data[] = array('key' => 'Bearbeitungsdauer', 'value' => $data['authority_processingTime']);
  
  $table_data[] = array('key' => '', 'value' => 'Angaben zum Satzungsgebiet (gilt nur für Position vom 1. Baum)');
  $table_data[] = array('key' => 'Nr.', 'value' => $data['statute_id']);
  $table_data[] = array('key' => 'Satzungsart', 'value' => $data['statute_type']);
  $table_data[] = array('key' => 'Bezeichnung', 'value' => $data['statute_name']);
  if ($data['statute_allowedDiameter'] != '')
    $table_data[] = array('key' => 'erlaubter Baumdurchmesser', 'value' => $data['statute_allowedDiameter']);

	$num_baueme = count($data['wood_species']);
  $table_data[] = array('key' => '', 'value' => 'Angaben zu den Bäumen (beantragte Anzahl: ' . $num_baueme . ')');	
	for ($i = 0; $i < $num_baueme; $i++) {
		$nr = $i + 1;
		$table_data[] = array('key' => $nr . '. Standort (lat, lng)', 'value' => $data['latitude'][$i] . ', ' . $data['longitude'][$i], 'url' => 'http://maps.google.de?q=' . $data['latitude'][$i] . ',' . $data['longitude'][$i] .'&');
		$table_data[] = array('key' => $nr . '. Baumart', 'value' => $data['wood_species'][$i]);
		$table_data[] = array('key' => $nr . '. Stammumfang, 1.3m Höhe', 'value' => $data['trunk_circumference'][$i] . ' cm');
    if ($data['crown_diameter'][$i] == 'null')
		  $table_data[] = array('key' => $nr . '. Kronendurchmesser', 'value' => $kronendurchmesser);
		if (array_key_exists('tree_image', $data) && array_key_exists($i, $data['tree_image'])) {
			$table_data[] = array('key' => 'Baumbild', 'value' => $antrag_id . '_Baum_' . $i . "_Bild_"  . basename($data['tree_image'][$i]));
		}
	}

  $table_data = array_map(
    function($row) {
      return array('key' => utf8_decode($row['key']), 'value' => utf8_decode($row['value']));
    },
    $table_data
  );

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
