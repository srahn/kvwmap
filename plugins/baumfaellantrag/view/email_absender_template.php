<?php
  # Bitte hier Admin angeben, der technische Rückfragen beantworten soll.
  # An diese Addressen gehen die Reply der versendeten E-Mails.
	$mail_absender['from_email'] = 'info@gdi-service.de';
	$mail_absender['from_name'] = 'Baumfaellgenehmigung';
	$mail_absender['to_email'] = $data['email'];
  $mail_absender['cc_email'] = '';
  $mail_absender['reply_email'] = $mail_absender['from_email'];
	
	$mail_absender['subject'] = 'Ihr Baumfällantrag';
	$mail_absender['message']  = "Sehr geehrte(r) Antragsteller(in)\n";
	$mail_absender['message'] .= "\n";
	$mail_absender['message'] .= "hiermit bestätigen wir Ihnen, dass Sie einen Antrag für eine Baumfällgenehmigung gestellt haben.\n";
	$mail_absender['message'] .= "\n";
	$mail_absender['message'] .= "Ihre Daten wurden an die zuständige Stelle weitergeleitet.\n";
  $mail_absender['message'] .= 'Der Ansprechpartner dort ist: ' . $data['authority_contactPerson']. "\n";
  $mail_absender['message'] .= 'E-Mail: ' . $data['authority_email']. "\n\n";
  $mail_absender['message'] .= "Sie werden über den Bescheid per E-Mail oder per Post informiert\n\n";
	$mail_absender['message'] .= "\n";
	$mail_absender['message'] .= 'Für technische Rückfragen wenden Sie sich bitte an:' . "\n";
	$mail_absender['message'] .= 'Roland Grösch' . "\n";
	$mail_absender['message'] .= 'Büro kooperatives E-Government' . "\n";
	$mail_absender['message'] .= 'Ministerium für Inneres und Sport M-V' . "\n";
	$mail_absender['message'] .= 'Tel.:     0385 588 2371' . "\n";
	$mail_absender['message'] .= 'E-Mail: roland.groesch@im.mv-regierung.de' . "\n";
	$mail_absender['message'] .= 'WWW: http://www.service.m-v.de/cms/DLP_prod/DLP/index.jsp' . "\n";
?>
