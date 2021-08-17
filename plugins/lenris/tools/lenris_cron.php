<?php

error_reporting(E_ALL & ~(E_STRICT|E_NOTICE|E_WARNING));

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
$clients = $lenris->get_client_information();

/*
	0 - Ruhe
	1 - Sync angefordert (setzen über GUI)
	2 - Sync läuft
	3 - Erstimport angefordert	(setzen über GUI)
	4 - Erstimport läuft
	*/

function is_sync_required($client){
	if (
		$client['status'] === 0 AND $client['sync_time'] < date("H-i-s") AND $client['last_sync'] < date("Y-m-d") 	# nach Plan
		OR
		$client['status'] === 1		# auf GUI angefordert
	)
}

foreach ($clients as $client) {
	# Synchronisation
	if (is_sync_required($client)) {
		$lenris->update_client($client['client_id'], 'status = 2')
		# neue Nachweise abfragen
		$new_nachweise = $lenris->get_new_nachweise($client);
		# neue Nachweise eintragen
		$lenris->insert_new_nachweise($client, $new_nachweise);

		# veränderte Nachweise abfragen
		$changed_nachweise = $lenris->get_changed_nachweise($client);
		# veränderte Nachweise aktualisieren
		$lenris->update_changed_nachweise($client, $changed_nachweise);

		# gelöschte Nachweise abfragen
		$deleted_nachweis_ids = $lenris->get_deleted_nachweise($client);
		# gelöschte Nachweise löschen
		$lenris->delete_deleted_nachweise($client, $deleted_nachweis_ids);
	}
	
	# Erstimport
	if ($client['status'] === 3){
		
	}
}

# Dokumente holen


?>