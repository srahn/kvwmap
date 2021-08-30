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
	status:
	0 - Ruhe
	1 - Sync angefordert (setzen über GUI)
	2 - Sync läuft
	3 - Erstimport angefordert	(setzen über GUI)
	4 - Erstimport läuft
*/

function is_sync_required($client){
	return (
		($client['status'] == 0 AND $client['sync_time'] < date("H-i-s") AND $client['last_sync'] != '' AND $client['last_sync'] < date("Y-m-d")) 	# nach Plan
		OR
		$client['status'] == 1		# auf GUI angefordert
	);
}

foreach ($clients as $client) {
	# Synchronisation
	if (is_sync_required($client)) {
		$lenris->update_client($client['client_id'], 'status = 2');
		# neue Nachweise abfragen
		if ($new_nachweise = $lenris->get_new_nachweise($client)){
			# neue Nachweise eintragen
			LENRIS::log(count($new_nachweise) . ' neue Nachweise von Client ' . $client['client_id']);
			$lenris->insert_new_nachweise($client, $new_nachweise);
		}
		else{
			LENRIS::log('Keine neuen Nachweise von Client ' . $client['client_id']);
		}

		# veränderte Nachweise abfragen
		if ($changed_nachweise = $lenris->get_changed_nachweise($client)) {
			# veränderte Nachweise aktualisieren
			LENRIS::log(count($changed_nachweise) . ' veränderte Nachweise von Client ' . $client['client_id']);
			$lenris->update_changed_nachweise($client, $changed_nachweise);
		}
		else {
			LENRIS::log('Keine veränderten Nachweise von Client ' . $client['client_id']);
		}

		# gelöschte Nachweise abfragen
		if ($deleted_nachweise = $lenris->get_deleted_nachweise($client)) {
			# gelöschte Nachweise löschen
			LENRIS::log(count($deleted_nachweise) . ' gelöschte Nachweise von Client ' . $client['client_id']);
			$lenris->delete_deleted_nachweise($client, $deleted_nachweise);
		}
		else {
			LENRIS::log('Keine gelöschten Nachweise von Client ' . $client['client_id']);
		}
		$lenris->update_client($client['client_id'], 'status = 0');
	}
	
	# Erstimport
	if ($client['status'] === 3){
		
	}
	
	# Dokumente holen
	if ($client['doc_download'] != 't'){
		$downloadable_documents = $lenris->get_downloadable_documents($client['client_id']);
		if (!empty($downloadable_documents)) {
			$lenris->update_client($client['client_id'], 'doc_download = true');
			$downloaded_documents = $lenris->download_documents($client, $downloadable_documents);
			$lenris->delete_downloadable_documents($client['client_id'], $downloaded_documents);
			$lenris->update_client($client['client_id'], 'doc_download = false');
		}
	}
}


?>