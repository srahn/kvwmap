<?php

error_reporting(E_ALL & ~(E_STRICT|E_NOTICE|E_WARNING));

include('../../../config.php');
include(PLUGINS.'nachweisverwaltung/config/config.php');
include(WWWROOT . APPLVERSION . 'funktionen/allg_funktionen.php');
include(CLASSPATH.'log.php');
include(CLASSPATH.'postgresql.php');
include(PLUGINS.'lenris/model/lenris.php');
$debug = new Debugger(DEBUGFILE);
$database = new pgdatabase();
$database->open();

$lenris = new LENRIS($database);

/*
	status:
 -1	- pausiert
	0 - warte auf Synchronisation
	1 - Sync angefordert (setzen über GUI)
	2 - Sync läuft
	3 - Erstimport angefordert	(setzen über GUI)
	4 - Erstimport läuft
	5 - Fehler
*/

function is_sync_required($client){
	return (
		($client['status'] == 0 AND $client['sync_time'] < date("H:i:s") AND $client['last_sync'] != '' AND $client['last_sync'] < date("Y-m-d")) 	# nach Plan
		OR
		$client['status'] == 1		# auf GUI angefordert
	);
}

foreach ($lenris->clients as $client) {
	$inserted_nachweise = [];
	$updated_nachweise = [];
	$deleted_nachweise = [];
	# Synchronisation
	$client = $lenris->get_client_information($client['client_id'])[0];
	if (is_sync_required($client)) {
		$lenris->update_client($client['client_id'], 'status = 2');
		$lenris->database->begintransaction();
		# neue Nachweise abfragen
		if ($new_nachweise = $lenris->get_new_nachweise($client)){
			# neue Nachweise eintragen
			$inserted_nachweise = $lenris->insert_new_nachweise($client, $new_nachweise);
		}
		# veränderte Nachweise abfragen
		if ($changed_nachweise = $lenris->get_changed_nachweise($client)) {
			# veränderte Nachweise aktualisieren
			$updated_nachweise = $lenris->update_changed_nachweise($client, $changed_nachweise);
		}
		# gelöschte Nachweise abfragen
		if ($deleted_nachweise = $lenris->get_deleted_nachweise($client)) {
			# gelöschte Nachweise löschen
			$deleted_nachweise = $lenris->delete_deleted_nachweise($client, $deleted_nachweise);
		}
		if (!empty($lenris->errors[$client['client_id']])) {
			$lenris->database->rollbacktransaction();
			$lenris->update_client($client['client_id'], "status = 5, last_sync = '" . date("Y-m-d H:i:s") . "'");
		}
		else {
			$lenris->delete_files();
			$lenris->update_client($client['client_id'], "status = 0, last_sync = '" . date("Y-m-d H:i:s") . "'");
			$lenris->database->committransaction();
			$lenris->confirm_new_nachweise($client, $inserted_nachweise);
			$lenris->confirm_changed_nachweise($client, $updated_nachweise);
			$lenris->confirm_deleted_nachweise($client, $deleted_nachweise);
		}
	}
	
	# Erstimport
	if ($client['status'] == 3){
		$lenris->update_client($client['client_id'], 'status = 4');
		$lenris->database->begintransaction();
		if ($lenris->delete_nachweise($client['client_id'])) {
			if ($all_nachweise = $lenris->get_all_nachweise($client)) {
				$lenris->log($client['client_id'], 'Erstimport: ' . count($all_nachweise) . ' Nachweise von Client ' . $client['client_id']);
				$lenris->insert_new_nachweise($client, $all_nachweise, false);
			}
		}
		if (!empty($lenris->errors[$client['client_id']])) {
			$lenris->database->rollbacktransaction();
			$lenris->update_client($client['client_id'], "status = 5");
		}
		else {
			$lenris->update_client($client['client_id'], "status = 0, last_sync = '" . date("Y-m-d H:i:s") . "'");
			$lenris->database->committransaction();
		}
	}
	
	# Dokumente holen
	$client = $lenris->get_client_information($client['client_id'])[0];
	if ($client['status'] == 0 AND $client['doc_download'] < 10){
		$downloadable_documents = $lenris->get_downloadable_documents($client);
		if (!empty($downloadable_documents)) {
			$lenris->update_client($client['client_id'], 'doc_download = doc_download + 1');
			$downloaded_documents = $lenris->download_documents($client, $downloadable_documents);
			$lenris->delete_downloadable_documents($client['client_id'], $downloaded_documents);
			$lenris->update_client($client['client_id'], 'doc_download = doc_download - 1');
		}
	}
}


?>