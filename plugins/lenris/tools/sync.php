<?php

error_reporting(E_ALL & ~(E_STRICT|E_NOTICE|E_WARNING));

if(!isset($argv[1])){
	echo 'Fehler: Keine Landkreis-ID übergeben.';
}
else {
	include('../../../config.php');
	include(WWWROOT . APPLVERSION . 'funktionen/allg_funktionen.php');
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
	$new_nachweise = $lenris->get_new_nachweise($client[0]);
	
	# neue Nachweise eintragen
	$lenris->insert_new_nachweise($client[0], $new_nachweise);
	
	# veränderte Nachweise abfragen
	$changed_nachweise = $lenris->get_changed_nachweise($client[0]);
	
	# veränderte Nachweise aktualisieren
	$lenris->update_changed_nachweise($client[0], $changed_nachweise);
}



?>