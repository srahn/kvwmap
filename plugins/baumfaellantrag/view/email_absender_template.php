<?php
	# E-Mail, die an den Absender des Antrages gesendet wird falls dieser eine E-Mail-Adresse angegeben hat.
	$mail_absender['from_email'] = $mail_absender['reply_email'] = $data['authority_email'];
	$mail_absender['from_name'] = $data['authority_contactPerson'];
	$mail_absender['to_email'] = $data['email'];
	$mail_absender['cc_email'] = '';

	$mail_absender['subject'] = 'Ihr Baumfällantrag';
	$mail_absender['message']  = "Sehr geehrte(r) Antragsteller(in)\n";
	$mail_absender['message'] .= "\n";
	$mail_absender['message'] .= "hiermit bestätigen wir Ihnen, dass Sie einen Antrag für eine Baumfällgenehmigung gestellt haben.\n";
	$mail_absender['message'] .= "\n";
	$mail_absender['message'] .= "Ihre Daten wurden an die zuständige Stelle weitergeleitet.\n";
	$mail_absender['message'] .= "Der Ansprechpartner dort ist: \n";
	$mail_absender['message'] .= $data['authority_contactPerson'] . "\n";
	$mail_absender['message'] .= 'E-Mail: ' . $data['authority_email']. "\n\n";
  $mail_absender['message'] .= "Sie werden über den Bescheid per E-Mail oder per Post informiert\n\n";
	$mail_absender['message'] .= "\n";
	$mail_absender['message'] .= "Für technische Rückfragen senden Sie bitte eine E-Mail an die Adresse geoportal@lk-seenplatte.de\n";

	$mail_absender['attachement'] = '';
?>
