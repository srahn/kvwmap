<?php
	# E-Mail, die an den Sachbearbeiter gesendet wird. 
	$mail_bearbeiter['from_email'] = $mail_bearbeiter['reply_email'] = (MAILREPLYADDRESS != '' ? MAILREPLYADDRESS : $data['authority_email']);
	$mail_bearbeiter['from_name'] = $data['forename'] . ' ' . $data['surname'];

	$mail_bearbeiter['to_email'] = $data['authority_email'];
	$mail_bearbeiter['cc_email'] = '';

	$mail_bearbeiter['subject'] = 'Baumfaellantrag';
	$mail_bearbeiter['message']  = 'Sehr geehrte(r) Ansprechpartner(in) ' . $data['authority_contactPerson'] . ",\n";
	$mail_bearbeiter['message'] .= "\n";
	$mail_bearbeiter['message'] .= 'es ist ein neuer Antrag auf Baumfällgenehmigung eingegangen.' . "\n";
	$mail_bearbeiter['message'] .= "\n";
	$mail_bearbeiter['message'] .= 'Weitere Details finden Sie im WebGIS kvwmap unter folgendem Link:' . "\n";
	$mail_bearbeiter['message'] .= URL . APPLVERSION . 'index.php?Stelle_ID=' . BAUMFAELLANTRAG_BEARBEITER_STELLE_ID . '&go=Layer-Suche_Suchen&operator_nr==&selected_layer_id=' . BAUMFAELLANTRAG_LAYER_ID_ANTRAEGE . '&value_nr=' . $antrag_id;
	$mail_bearbeiter['message'] .= "\n";
	$mail_bearbeiter['message'] .= "\n";
	$mail_bearbeiter['message'] .= "Für technische Rückfragen senden Sie bitte eine E-Mail an die Adresse geoportal@lk-seenplatte.de\n";
	$mail_bearbeiter['attachement'] = BAUMFAELLANTRAG_UPLOAD_PATH . $zip_file_name;
?>
