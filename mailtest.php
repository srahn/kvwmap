<?php
include('funktionen/allg_funktionen.php');

$from_name = "Peter Korduan";
$from_email = "admin@geo4.d10643.imv.de";
$to_email = "peter.korduan@gdi-service.de";
$cc_email = "";
$reply_email = "pkorduan@gmx.de";
$subject = "Test subject";
$message = "Test message";
$attachement = false;

mail_att($from_name, $from_email, $to_email, $cc_email, $reply_email, $subject, $message, $attachement);

?>
E-Mail versendet:<br>
von: <?php echo $from_name; ?><br>
von email: <?php echo $from_email; ?><br>
an email: <?php echo $to_email; ?><br>
cc: <?php echo $cc_email = ""; ?><br>
reply to: <?php echo $reply_email; ?><br>
subject: <?php echo $subject; ?><br>
message: <pre><?php echo $message; ?></pre><br>
attachement: <?php echo $attachement; ?>