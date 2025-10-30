<?php
#include('config.php');
include_once(CLASSPATH . 'PgObject.php');
include_once(CLASSPATH . 'phpqrcode.php');
#include('funktionen/allg_funktionen.php');

session_start();

$_SESSION['angemeldet'] = false;

if (!isset($_SESSION['temp_user'])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['temp_user'];

if ($_SESSION['secret'] == '') {
	// neues Secret erzeugen
	$_SESSION['secret'] = generate_random_base32();
}
$secret = $_SESSION['secret'];

$uri = sprintf(
    'otpauth://totp/%s:%s?secret=%s&issuer=%s',
    urlencode('kvwmap'),
    urlencode($username),
    $secret,
    urlencode('kvwmap')
);
$qr_image = $username . '.png';
QRcode::png($uri, IMAGEPATH . $qr_image);

?>

<h2>2FA aktivieren</h2>
<p>Scanne den QR-Code mit Google Authenticator, Aegis oder Authy:</p>
<img src="<? echo TEMPPATH_REL . $qr_image; ?>">
<p>Oder Secret manuell eingeben: <b><?=$secret?></b></p>

<form method="post">
    <input name="code" placeholder="6-stelliger Code" required><br>
    <button type="submit">Aktivieren</button>
</form>
