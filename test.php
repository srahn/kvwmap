<?php
function getArrayOfChars() {
	$characters = array();
	$characterNumbers = array();
	for ($i=65; $i<=90; $i++) {
	  $characterNumbers[]=$i; # Großuchstaben
	}
	for ($i=97; $i<=122; $i++) {
	  $characterNumbers[]=$i; # Kleinbuchstaben
	}
	array_push($characterNumbers,223,196,228,214,246,220,252);

	foreach ($characterNumbers as $characterNumber) {
	  $characters[] = chr($characterNumber);
	}
	return $characters;
}
$zeile="('','Text','','falschertext'','')";
foreach (getArrayOfChars() AS $character) {
	$zeile= str_replace($character."''",$character."'",$zeile);       # "character'' => character')
}
echo 'Zeile: '.$zeile;
?>