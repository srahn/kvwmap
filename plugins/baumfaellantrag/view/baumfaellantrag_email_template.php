<?php
  # Bitte hier Admin angeben, der technische Rückfragen beantworten soll.
  # An diese Addressen gehen die Reply der versendeten E-Mails.
	$absender_email = 'roland.groesch@ego-mv.de';

	$mail['from_name'] = 'eGo-MV';
	$mail['from_email'] = $absender_email;
	
	#$mail['to_email'] = $data['authority_email'];
	#$mail['to_email'] = 'roland.groesch@ego-mv.de';	
	$mail['to_email'] = 'peter.korduan@gdi-service.de';	
	
	$mail['subject'] = 'Baumfällantrag';
	$mail['message']  = 'Sehr geehrte(r) Ansprechpartner(in) ' . $data['authority_contactPerson'] . ",\n";
	$mail['message'] .= "\n";
	$mail['message'] .= 'es ist ein neuer Antrag auf Baumfällgenehmigung eingegangen.' . "\n";
	$mail['message'] .= "\n";
	$mail['message'] .= 'Weitere Details finden Sie im WebGIS des eGo-MV unter folgendem Link:' . "\n";	
	$mail['message'] .= 'https://5.35.250.18/kvwmap/index.php?Stelle_ID=11&go=Layer-Suche_Suchen&selected_layer_id=2073&value_nr='.$antrag_id.'&operator_nr==';

	if (!empty($data['email'])) {
    $mail['cc_email'] = $data['email'];
    $mail['reply_email'] = $absender_email; // ueberschreibt $absender
  } else {
    $mail['cc_email'] = '';
	  $mail['reply_email'] = $absender_email;    
  }
	$mail['message'] .= "\n";
	$mail['message'] .= 'Für technische Rückfragen wenden Sie sich bitte an:' . "\n";
	$mail['message'] .= 'Roland Grösch' . "\n";
	$mail['message'] .= 'Sachbearbeiter Geodatenmanagement' . "\n";
	$mail['message'] .= 'Zweckverband Elektronische Verwaltung' . "\n";
	$mail['message'] .= 'in Mecklenburg-Vorpommern (eGo-MV)' . "\n";
	$mail['message'] .= 'Eckdrift 103' . "\n";
	$mail['message'] .= '19061 Schwerin/ Germany' . "\n";
	$mail['message'] .= 'Telefon  +49 (0)385/77 33 47-48' . "\n";
	$mail['message'] .= 'Telefax   +49 (0)385/77 33 47-28' . "\n";
	$mail['message'] .= 'E-Mail roland.groesch@ego-mv.de' . "\n";
?>
