<?php

include_once(PLUGINS.'nachweisverwaltung/model/nachweis.php');

class LENRIS {
  var $debug;
  var $database;
    
  function __construct($database) {
    global $debug;
		$this->nachweis = new Nachweis($database, NULL);
    $this->debug=$debug;
    $this->database=$database;
		$this->clients = $this->get_client_information();
		$this->dokumentart_mapping = $this->get_dokumentart_mapping();
		$this->vermessungsstellen_mapping = $this->get_vermessungsstellen_mapping();
		$this->dokumentarten = $this->nachweis->getDokumentarten(false);
		$this->hauptarten = $this->nachweis->getHauptDokumentarten();
  }
	
	public static function log_error($error){
		echo 'LENRIS Error log: ' . $error . chr(10);
	}
	
	public static function log($msg){
		echo 'LENRIS log: ' . $msg . chr(10);
	}	
	
	function get_client_information($client_id = NULL){
		$sql = "
			SET datestyle TO ISO, DMY;
			SELECT 
				client_id, 
				bezeichnung, 
				url, 
				nachweis_primary_attribute, 
				nachweis_secondary_attribute, 
				last_sync,
				sync_time,
				status,
				doc_download
			FROM
				lenris.clients
			" . ($client_id != NULL? ' WHERE client_id = ' . $client_id : '');
		$ret = $this->database->execSQL($sql, 4, 0, true);
		if (!$ret[0]) {
			$clients = pg_fetch_all($ret[1]);
			return $clients;
		}
		else {
			LENRIS::log_error($ret[1]);
		}
	}
	
	function update_client($client_id, $values){
		$sql = "
			UPDATE
				lenris.clients
			SET
				" . $values . "
			WHERE
				client_id = " . $client_id;
		$ret = $this->database->execSQL($sql, 4, 0);
	}
	
	function get_nachweis_info($client_id, $client_nachweis_id){
		$sql = "
			SELECT 
				cn.*,
				n.link_datei
			FROM
				lenris.client_nachweise cn,
				nachweisverwaltung.n_nachweise n
			WHERE
				cn.nachweis_id = n.id AND
				cn.client_id = " . $client_id . " AND 
				cn.client_nachweis_id = " . $client_nachweis_id . "
		";				
		$ret = $this->database->execSQL($sql, 4, 0, true);
		if (!$ret[0]) {
			$rs = pg_fetch_assoc($ret[1]);
			return $rs;
		}
		else {
			LENRIS::log_error($ret[1]);
		}
	}
	
	function get_dokumentart_mapping(){
		$sql = "
			SELECT 
				*
			FROM
				lenris.client_dokumentarten";
		$ret = $this->database->execSQL($sql, 4, 0, true);
		if (!$ret[0]) {
			while ($rs = pg_fetch_assoc($ret[1])) {
				$dokumentart_mapping[$rs['client_id']][$rs['client_dokumentart_id']] = $rs['dokumentart_id'];
			}
			return $dokumentart_mapping;
		}
		else {
			LENRIS::log_error($ret[1]);
		}
	}
	
	function get_vermessungsstellen_mapping(){
		$sql = "
			SELECT 
				*
			FROM
				lenris.client_vermessungsstellen";
		$ret = $this->database->execSQL($sql, 4, 0, true);
		if (!$ret[0]) {
			while ($rs = pg_fetch_assoc($ret[1])) {
				$vermessungsstellen_mapping[$rs['client_id']][$rs['client_vermstelle_id']] = $rs['vermstelle_id'];
			}
			return $vermessungsstellen_mapping;
		}
		else {
			LENRIS::log_error($ret[1]);
		}
	}
		
	function map_dokumentart($client_id, $art){
		$new_art = $this->dokumentart_mapping[$client_id][$art];
		return $new_art ?: 0;
	}
	
	function map_vermessungsstelle($client_id, $vermessungsstelle){
		$new_vermessungsstelle = $this->vermessungsstellen_mapping[$client_id][$vermessungsstelle];
		return $new_vermessungsstelle ?: 0;
	}	
	
	function delete_nachweise($client_id){
		$sql = "
			DELETE FROM 
				nachweisverwaltung.n_nachweise n
			USING
				lenris.client_nachweise cn
			WHERE
				cn.nachweis_id = n.id AND
				cn.client_id = " . $client_id;
		$ret = $this->database->execSQL($sql, 4, 0, true);
		if (!$ret[0]) {
			LENRIS::log('Alle Nachweise von Client ' . $client_id . ' in n_nachweise gelöscht.');
			$sql = "
				DELETE FROM 
					lenris.client_nachweise
				WHERE
					client_id = " . $client_id;
			$ret = $this->database->execSQL($sql, 4, 0, true);
			if (!$ret[0]) {
				LENRIS::log('Alle Nachweise von Client ' . $client_id . ' in client_nachweise gelöscht.');
				$sql = "
					DELETE FROM 
						lenris.zu_holende_dokumente
					WHERE
						client_id = " . $client_id;
				$ret = $this->database->execSQL($sql, 4, 0, true);
				if (!$ret[0]) {
					LENRIS::log('Alle Nachweise von Client ' . $client_id . ' in zu_holende_dokumente gelöscht.');
					$output = null;
					exec("rm -rf /var/www/data/nachweise/" . $client_id, $output, $retval);
					if ($retval == 0) {
						LENRIS::log('Alle Dokumente von Client ' . $client_id . ' gelöscht.');
					}
					else {
						LENRIS::log_error('Löschen der Dokumente von Client ' . $client_id . ' fehlgeschlagen. ' . $output);
						return false;
					}
				}
				else {
					LENRIS::log_error($ret[1]);
					return false;
				}
			}
			else {
				LENRIS::log_error($ret[1]);
				return false;
			}
		}
		else {
			LENRIS::log_error($ret[1]);
			return false;
		}
		return true;
	}
	
	function get_downloadable_documents($client){
		$sql = "
			SELECT 
				d.*
			FROM
				lenris.zu_holende_dokumente d
			WHERE	
				d.client_id = " . $client['client_id'] . " 
			LIMIT 1000 OFFSET " . $client['doc_download'] . " * 1000";
		$ret = $this->database->execSQL($sql, 4, 0, true);
		if (!$ret[0]) {
			$docs = pg_fetch_all($ret[1]);
			return $docs;
		}
		else {
			LENRIS::log_error($ret[1]);
		}
	}
	
	function delete_downloadable_documents($client_id, $client_nachweis_ids){
		if ($client_nachweis_ids) {
			$sql = "
				DELETE FROM 
					lenris.zu_holende_dokumente
				WHERE
					client_id = " . $client_id . " AND 
					client_nachweis_id IN (" . implode(',', $client_nachweis_ids) . ")";
			$ret = $this->database->execSQL($sql, 4, 0, true);
		}
	}
	
	function download_documents($client, $downloadable_documents){
		foreach ($downloadable_documents as $downloadable_document) {
			$dest_path = $downloadable_document['dest_path'];
			if (!is_dir(dirname($dest_path))) {
				mkdir(dirname($dest_path), 0777, true);
			}
			exec('wget -nv --timeout=5 -O "' . $dest_path . '" "' . $client['url'] . '&go=LENRIS_get_document&document=' . $downloadable_document['source_path'] . '"');
			if (file_exists($dest_path) AND filesize($dest_path) == 0) {
				unlink($dest_path);		# leere Dateien löschen
			}
			if (file_exists($dest_path)) {
				$successful_downloaded_docs[] = $downloadable_document['client_nachweis_id'];
				$this->nachweis->CreateNachweisDokumentVorschau($dest_path);
			}
		}
		return $successful_downloaded_docs;
	}

	function get_all_nachweise($client){
		set_time_limit(600);
		ini_set('default_socket_timeout', 600);
		if ($json = file_get_contents($client['url'] . '&go=LENRIS_get_all_nachweise'))	{
			return json_decode($json, true);
		}
	}
	
	function get_new_nachweise($client){
		if ($json = file_get_contents($client['url'] . '&go=LENRIS_get_new_nachweise'))	{
			return json_decode($json, true);
		}
	}
	
	function get_changed_nachweise($client){
		if ($json = file_get_contents($client['url'] . '&go=LENRIS_get_changed_nachweise'))	{
			return json_decode($json, true);
		}
	}
	
	function get_deleted_nachweise($client){
		if ($json = file_get_contents($client['url'] . '&go=LENRIS_get_deleted_nachweise')) {
			return json_decode($json, true);
		}
	}	
	
	function adjust_path($client, $n){		
		$doc = $n;
		$doc['artname'] = strtolower($this->hauptarten[$this->dokumentarten[$n['art']]['hauptart']]['abkuerzung']);
		$doc['Bilddatei_name'] = $n['link_datei'];
		$doc['Blattnr'] = $n['blattnummer'];		
		$zieldateiname = $this->nachweis->getZielDateiName($doc, $client['nachweis_primary_attribute'], $client['nachweis_secondary_attribute']);
		$newpath = NACHWEISDOCPATH . $client['client_id'] . '/' . $n['flurid'] . '/' . $this->nachweis->buildNachweisNr($n[$client['nachweis_primary_attribute']], $n[$client['nachweis_secondary_attribute']], $client['nachweis_primary_attribute']) . '/' . $doc['artname'] . '/' . $zieldateiname;
		return $newpath;
	}
	
	function insert_new_nachweise($client, $nachweise, $confirm = true){
		$inserted_nachweise = array();
		foreach ($nachweise as $n) {
			$n['art'] = $this->map_dokumentart($client['client_id'], $n['art']);
			$n['vermstelle'] = $this->map_vermessungsstelle($client['client_id'], $n['vermstelle']);
			$newpath = $this->adjust_path($client, $n);
			$sql = "
				INSERT INTO
					nachweisverwaltung.n_nachweise
					(flurid, blattnummer, datum, vermstelle, gueltigkeit, link_datei, 
					 format, stammnr, the_geom, fortfuehrung, rissnummer, bemerkungen, 
					 bearbeiter, zeit, erstellungszeit, bemerkungen_intern, geprueft, 
					 art, datum_bis, aenderungsnummer, antragsnummer_alt, rissfuehrer_id, 
					 messungszahlen, bov_ersetzt, zeit_geprueft, freigegeben)
				VALUES (
					" . $n['flurid'] . ", 
					'" . $n['blattnummer'] . "', 
					" . ($n['datum'] ? "'" . $n['datum'] . "'" : 'NULL') . ", 
					'" . $n['vermstelle'] . "', 
					" . $n['gueltigkeit'] . ", 
					'" . $newpath . "', 
					'" . $n['format'] . "', 
					'" . $n['stammnr'] . "', 
					'" . $n['the_geom'] . "', 
					" . ($n['fortfuehrung'] ?: 'NULL') . ", 
					'" . $n['rissnummer'] . "', 
					'" . $n['bemerkungen'] . "', 
					'" . $n['bearbeiter'] . "', 
					" . ($n['zeit'] ? "'" . $n['zeit'] . "'" : 'NULL') . ", 
					" . ($n['erstellungszeit'] ? "'" . $n['erstellungszeit'] . "'" : 'NULL') . ", 
					'" . $n['bemerkungen_intern'] . "', 
					" . $n['geprueft'] . ", 
					" . $n['art'] . ", 
					" . ($n['datum_bis'] ? "'" . $n['datum_bis'] . "'" : 'NULL') . ", 
					'" . $n['aenderungsnummer'] . "', 
					'" . $n['antragsnummer_alt'] . "', 
					" . ($n['rissfuehrer_id'] ?: 'NULL') . ", 
					" . ($n['messungszahlen'] ?: 'NULL') . ", 
					" . ($n['bov_ersetzt'] ?: 'NULL') . ", 
					" . ($n['zeit_geprueft'] ? "'" . $n['zeit_geprueft'] . "'" : 'NULL') . ", 
					'" . $n['freigegeben'] . "'
					)
				RETURNING id
			";
			$ret = $this->database->execSQL($sql, 4, 0, true);
			if (!$ret[0]) {
				$rs = pg_fetch_assoc($ret[1]);
				$sql = "
					INSERT INTO
						lenris.client_nachweise
						(nachweis_id, client_nachweis_id, client_id, document_last_modified)
					VALUES
						(" . $rs['id'] . ", " . $n['id'] . ", " . $client['client_id'] . ", " . ($n['document_last_modified'] ? "'" . $n['document_last_modified'] . "'" : 'NULL') . ")
				";				
				$ret = $this->database->execSQL($sql, 4, 0, true);
				if (!$ret[0]) {
					$sql = "
						INSERT INTO 
							lenris.zu_holende_dokumente
						VALUES
							(" . $client['client_id'] . ", " . $n['id'] . ", '" . $n['link_datei'] . "', '" . $newpath . "')
					";
					$ret = $this->database->execSQL($sql, 4, 0, true);
					
					if (!$ret[0]) {
						$inserted_nachweise[] = $n['id'];
					}
				}
				else {
					LENRIS::log_error($ret[1]);
				}
			}
			else {
				LENRIS::log_error($ret[1]);
			}
		}
		if ($confirm) {
			$this->confirm_new_nachweise($client, $inserted_nachweise);
		}
	}
	
	function confirm_new_nachweise($client, $inserted_nachweise){
		$response = curl_get_contents($client['url'] . '&go=LENRIS_confirm_new_nachweise&ids=' . implode(',', $inserted_nachweise));
		LENRIS::log('Bestätigung bei ' . $response . ' von ' . count($inserted_nachweise) . ' neuen Nachweisen für Client ' . $client['client_id'] . ' erfolgreich');
	}
	
	function delete_deleted_nachweise($client, $nachweise){
		$deleted_nachweise = array();
		foreach ($nachweise as $n) {
			$rs = $this->get_nachweis_info($client['client_id'], $n['id_nachweis']);
			if ($rs['nachweis_id'] != '') {
				$sql = "
					DELETE FROM
						nachweisverwaltung.n_nachweise
					WHERE 
						id = " . $rs['nachweis_id'];
				$ret = $this->database->execSQL($sql, 4, 0, true);
				if (!$ret[0]) {
					if (pg_affected_rows($ret[1]) > 0) {
						$sql = "
						DELETE FROM
							lenris.client_nachweise
						WHERE 
							client_id = " . $client['client_id'] . " AND 
							client_nachweis_id = " . $n['id_nachweis'];
						$ret = $this->database->execSQL($sql, 4, 0, true);
						if (!$ret[0]) {
							$deleted_nachweise[] = $n['id_nachweis'];
						}
						else {
							LENRIS::log_error($ret[1]);
						}
						# Datei löschen
						if (file_exists($rs['link_datei'])){
							unlink($rs['link_datei']);
						}
					}
					else {
						LENRIS::log_error('Zu löschenden Nachweis nicht in Tabelle nachweisverwaltung.n_nachweise gefunden (client_id = ' . $client['client_id'] . ', client_nachweis_id = ' . $nachweis_client_id . ')');
					}
				}
				else {
					LENRIS::log_error($ret[1]);
				}
			}
			else {
				LENRIS::log_error('Zu löschenden Nachweis nicht in Tabelle lenris.client_nachweise gefunden (client_id = ' . $client['client_id'] . ', client_nachweis_id = ' . $nachweis_client_id . ')');
			}
		}
		$this->confirm_deleted_nachweise($client, $deleted_nachweise);
	}
	
	function confirm_deleted_nachweise($client, $deleted_nachweise){
		$response = curl_get_contents($client['url'] . '&go=LENRIS_confirm_deleted_nachweise&ids=' . implode(',', $deleted_nachweise));
		LENRIS::log('Bestätigung bei ' . $response . ' von ' . count($deleted_nachweise) . ' gelöschten Nachweisen für Client ' . $client['client_id'] . ' erfolgreich');
	}	
	
	function update_changed_nachweise($client, $nachweise){
		$updated_nachweise = array();
		foreach ($nachweise as $n) {
			# mit der client_id und der client_nachweis_id die nachweis_id ermitteln, sowie zusätzlich den Dateipfad abfragen
			$rs = $this->get_nachweis_info($client['client_id'], $n['id']);
			if ($rs['nachweis_id'] != '') {
				$n['art'] = $this->map_dokumentart($client['client_id'], $n['art']);
				$n['vermstelle'] = $this->map_vermessungsstelle($client['client_id'], $n['vermstelle']);
				$newpath = $this->adjust_path($client, $n);
				# Nachweis aktualisieren
				$sql = "
					UPDATE
						nachweisverwaltung.n_nachweise
					SET 
						flurid = " . $n['flurid'] . ", 
						blattnummer = '" . $n['blattnummer'] . "', 
						datum = '" . $n['datum'] . "', 
						vermstelle = '" . $n['vermstelle'] . "', 
						gueltigkeit = " . $n['gueltigkeit'] . ", 
						link_datei = '" . $newpath . "', 
						format = '" . $n['format'] . "',
						stammnr = '" . $n['stammnr'] . "', 
						the_geom = '" . $n['the_geom'] . "', 
						fortfuehrung = " . ($n['fortfuehrung'] ?: 'NULL') . ", 
						rissnummer = '" . $n['rissnummer'] . "', 
						bemerkungen = '" . $n['bemerkungen'] . "', 
						bearbeiter = '" . $n['bearbeiter'] . "', 
						zeit = '" . $n['zeit'] . "', 
						erstellungszeit = '" . $n['erstellungszeit'] . "', 
						bemerkungen_intern = '" . $n['bemerkungen_intern'] . "', 
						geprueft = " . $n['geprueft'] . ", 
						art = " . $n['art'] . ", 
						datum_bis = " . ($n['datum_bis'] ? "'" . $n['datum_bis'] . "'" : 'NULL') . ", 
						aenderungsnummer = '" . $n['aenderungsnummer'] . "', 
						antragsnummer_alt = '" . $n['antragsnummer_alt'] . "', 
						rissfuehrer_id = " . ($n['rissfuehrer_id'] ?: 'NULL') . ", 
						messungszahlen = " . ($n['messungszahlen'] ?: 'NULL') . ", 
						bov_ersetzt = " . ($n['bov_ersetzt'] ?: 'NULL') . ", 
						zeit_geprueft = " . ($n['zeit_geprueft'] ? "'" . $n['zeit_geprueft'] . "'" : 'NULL') . ", 
						freigegeben = '" . $n['freigegeben'] . "'
					WHERE
						id = " . $rs['nachweis_id'] . "
				";
				$ret = $this->database->execSQL($sql, 4, 0, true);
				if (!$ret[0]) {
					if ($n['document_last_modified'] != $rs['document_last_modified']) {
						# neuen Dateipfad in zu_holende_dokumente schreiben
						$sql = "
							INSERT INTO 
								lenris.zu_holende_dokumente
							VALUES
								(" . $client['client_id'] . ", " . $n['id'] . ", '" . $n['link_datei'] . "', '" . $newpath . "')
							ON CONFLICT ON CONSTRAINT pk_zu_holende_dokumente DO UPDATE SET
								source_path = '" . $n['link_datei'] . "',
								dest_path = '" . $newpath . "'
						";
						$ret = $this->database->execSQL($sql, 4, 0);
						if (!$ret[0]) {
							# document_last_modified aktualisieren
							$sql = "
								UPDATE
									lenris.client_nachweise
								SET
									document_last_modified = '" . $n['document_last_modified'] . "'
								WHERE
									nachweis_id = " . $rs['nachweis_id'];
							$ret = $this->database->execSQL($sql, 4, 0);
							if (!$ret[0]) {
								# alte Datei löschen
								if (file_exists($rs['link_datei'])){
									unlink($rs['link_datei']);
								}
							}
							else {
								LENRIS::log_error($ret[1]);
							}
						}
						else {
							LENRIS::log_error($ret[1]);
						}
					}
					$updated_nachweise[] = $n['id'];
				}
				else {
					LENRIS::log_error($ret[1]);
				}
			}
			else {
				LENRIS::log_error('Zu aktualisierenden Nachweis nicht in Tabelle lenris.client_nachweise gefunden (client_id = ' . $client['client_id'] . ', client_nachweis_id = ' . $n['id'] . ')');
			}
		}
		$this->confirm_changed_nachweise($client, $updated_nachweise);
	}
	
	function confirm_changed_nachweise($client, $updated_nachweise){
		$response = curl_get_contents($client['url'] . '&go=LENRIS_confirm_changed_nachweise&ids=' . implode(',', $updated_nachweise));
		LENRIS::log('Bestätigung bei ' . $response . ' von ' . count($updated_nachweise) . ' veränderten Nachweisen für Client ' . $client['client_id'] . ' erfolgreich');
	}	
	
}

?>