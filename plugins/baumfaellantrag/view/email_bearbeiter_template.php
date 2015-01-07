<?php
  # Bitte hier Admin angeben, der technische Rückfragen beantworten soll.
  # An diese Addressen gehen die Reply der versendeten E-Mails.
	$mail_absender['from_email'] = 'info@gdi-service.de';
	$mail_absender['from_name'] = 'Baumfaellgenehmigung';
	#$mail_bearbeiter['to_email'] = $data['authority_email'];
	$mail_bearbeiter['to_email'] = 'peter.korduan@gdi-service.de'; # in production ersetzen durch $data['authority_email'];
  $mail_bearbeiter['cc_email'] = '';
  $mail_absender['reply_email'] = $mail_absender['from_email'];
	
	$mail_bearbeiter['subject'] = 'Baumfällantrag';
	$mail_bearbeiter['message']  = 'Sehr geehrte(r) Ansprechpartner(in) ' . $data['authority_contactPerson'] . ",\n";
	$mail_bearbeiter['message'] .= "\n";
	$mail_bearbeiter['message'] .= 'es ist ein neuer Antrag auf Baumfällgenehmigung eingegangen.' . "\n";
	$mail_bearbeiter['message'] .= "\n";
	$mail_bearbeiter['message'] .= 'Weitere Details finden Sie im WebGIS kvwmap unter folgendem Link:' . "\n";
	$mail_bearbeiter['message'] .= 'http://www.gdi-service.de/kvwmap_intern/index.php?Stelle_ID=50&go=Layer-Suche_Suchen&selected_layer_id=752&value_nr='.$antrag_id.'&operator_nr==';
	$mail_bearbeiter['message'] .= "\n";
	$mail_bearbeiter['message'] .= "\n";
	$mail_bearbeiter['message'] .= 'Für technische Rückfragen wenden Sie sich bitte an:' . "\n";
	$mail_bearbeiter['message'] .= 'Roland Grösch' . "\n";
	$mail_bearbeiter['message'] .= 'Büro kooperatives E-Government' . "\n";
	$mail_bearbeiter['message'] .= 'Ministerium für Inneres und Sport M-V' . "\n";
	$mail_bearbeiter['message'] .= 'Tel.:     0385 588 2371' . "\n";
	$mail_bearbeiter['message'] .= 'E-Mail: roland.groesch@im.mv-regierung.de' . "\n";
	$mail_bearbeiter['message'] .= 'WWW: http://www.service.m-v.de/cms/DLP_prod/DLP/index.jsp' . "\n";
?>
