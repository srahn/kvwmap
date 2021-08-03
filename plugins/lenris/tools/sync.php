<?php

error_reporting(E_ALL & ~(E_STRICT|E_NOTICE|E_WARNING));

if(!isset($argv[1])){
	echo 'Fehler: Keine Landkreis-ID übergeben.';
}
else {
	include('../../../config.php');
	include(CLASSPATH.'log.php');
	include(CLASSPATH.'postgresql.php');
	include(PLUGINS.'lenris/model/lenris.php');
	$debug = new Debugger(DEBUGFILE);
	$database = new pgdatabase();
	$database->open();
	$client_id = $argv[1];
	
	$lenris = new LENRIS($database);
	$client = $lenris->get_client_information($client_id);
	
	# neue Nachweise abfragen
	echo $new_nachweise = get_new_nachweise($client[0]);
}

function get_new_nachweise($client){
	return file_get_contents($client['url'] . '&go=LENRIS_get_new_nachweise');
}

?>